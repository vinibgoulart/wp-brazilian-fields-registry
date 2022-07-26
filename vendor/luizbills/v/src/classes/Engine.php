<?php

namespace luizbills\v;

final class Engine {
	const ROOT_CONTEXT = 'root';

	protected static $instance = null;

	protected $native_filters = [];
	protected $custom_filters = [];
	protected $current_context = null;

	protected function __construct () {
		// load default filters
		$this->load_extension( $this->get_default_filters() );
		$this->reset_context();
	}

	public static function get_instance () : self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function run_filters ( $value, ...$filters ) {
		$result = $value;

		if ( \count( $filters ) > 0 ) {
			// by default, any html in $value will be escaped
			// but the `raw` filter prevent this behavior

			$has_raw = false;
			foreach ( $filters as $expression ) {
				if ( $has_raw ) break;
				if ( ! $this->is_function( $expression ) ) {
					$has_raw = 1 === preg_match( '/(^raw$)|(^raw\()/', $expression );
				}
			}

			$has_escape = false;
			foreach ( $filters as $expression ) {
				if ( $has_escape ) break;
				if ( ! $this->is_function( $expression ) ) {
					$has_escape = 1 === preg_match( '/(^escape$)|(^escape\()/', $expression );
				}
			}

			if ( ! $has_raw && ! $has_escape ) {
				$filters = \array_merge( $filters, [ 'escape' ] );
			}
		} else {
			$filters = [ 'escape' ];
		}

		foreach ( $filters as $expression ) {
			if ( $this->is_function( $expression ) ) {
				$callback = is_string( $expression ) && '@' === substr( $expression, 0, 1 ) ? substr( $expression, 1 ) : $expression;
				$result = \call_user_func( $callback, $result );
			} else {
				$parts = \explode( '(', trim( $expression ) );
				$name = \array_shift( $parts );

				// skip the `raw` filter
				if ( 'raw' == $name ) continue;

				$callback = $this->get_filter_callback( $name );
				$expression_arguments = count( $parts ) > 0 ? '(' . implode( '(', $parts ) : '';
				$arguments = new Arguments( $expression_arguments );
				$result = \call_user_func( $callback, $result, $arguments );
			}
		}

		return $result;
	}

	public function get_filter_callback ( string $name ) : callable {
		$result = null;
		$ctx = $this->current_context;
		$filters = $this->get_context_filters( $ctx );

		if ( isset( $filters ) && isset( $filters[ $name ] ) ) {
			return $filters[ $name ];
		}
		elseif ( isset( $this->native_filters[ $name ] ) ) {
			return $this->native_filters[ $name ];
		}

		throw new \InvalidArgumentException( __METHOD__ . ": unexpected `$name` filter in `$ctx` context" );
	}

	public function register_filter ( string $name, callable $callback, string $context = '' ) {
		$name = \trim( $name );

		if ( '' === $context ) {
			$context = self::ROOT_CONTEXT;
		}

		if ( ! isset( $this->custom_filters[ $context ] ) ) {
			$this->custom_filters[ $context ] = [];
		}

		$this->custom_filters[ $context ][ $name ] = $callback;
	}

	public function set_context ( string $context ) {
		$this->current_context = $context;
	}

	public function reset_context () {
		$this->current_context = self::ROOT_CONTEXT;
	}

	public function load_extension ( array $filters ) {
		foreach ( $filters as $name => $callback ) {
			$this->native_filters[ $name ] = $callback;
		}
	}

	protected function get_default_filters () : array {
		$dir = __DIR__ . '/../filters/';
		$files = \scandir( $dir );
		$filters = [];

		unset( $files[ array_search( '.', $files, true ) ] );
		unset( $files[ array_search( '..', $files, true ) ] );

		foreach ( $files as $file ) {
			$name = \str_replace( '.php', '', $file );
			$callback = include $dir . $file;
			$filters[ $name ] = $callback;
		}

		return $filters;
	}

	protected function get_context_filters ( string $ctx ) {
		return isset( $this->custom_filters[ $ctx ] ) ? $this->custom_filters[ $ctx ] : null;
	}

	protected function is_function ( $func ) {
		if ( is_array( $func ) && is_callable( $func ) ) return true;
		if ( is_object( $func ) && $func instanceof \Closure ) return true;
		if ( is_string( $func ) && '@' === substr( $func, 0, 1 ) && function_exists( substr( $func, 1 ) ) ) return true;
		return false;
	}
}
