<?php

namespace WPBrazilianRegistry\Utils;

use WPBrazilianRegistry\Common\Hooker_Trait;
use WPBrazilianRegistry\Utils\Data_Store;
use WPBrazilianRegistry\functions as h;

class Asset_Manager {
	use Hooker_Trait;

	protected $store;
	protected $script_data = [];
	protected $enqueued = false;

	public function __construct () {
		$this->store = new Data_Store( [
			'js' => [],
			'css' => [],
		] );

		$this->add_action( 'wp_enqueue_scripts', 'enqueue_assets' );
		$this->add_action( 'admin_enqueue_scripts', 'enqueue_assets' );
		$this->add_action( 'wp_footer', 'print_script_data' );
		$this->add_action( 'admin_footer', 'print_script_data' );
	}

	public function add ( $src, $args = [] ) {
		$type = isset( $args['type'] ) ? $args['type'] : h\get_file_extension( $src );
		$scripts = $this->store->get( $type, [] );

		if ( h\str_starts_with( $src, [ 'http:', 'https:' ] ) ) {
			$url = $src;
		} else {
			$url = h\get_asset_url( $src );
		}

		$args = \array_merge(
			$this->get_defaults( $type ),
			[
				'path' => $src,
				'src' => $url,
				'handle' => h\str_slug( h\prefix( $src ), '_' ),
			],
			$args
		);

		$scripts[ $src ] = \apply_filters( h\prefix( 'asset_args' ), $args, $src );
		$this->store->set( $type, $scripts );
	}

	public function enqueue_assets () {
		$types = $this->get_types();
		$is_admin = \is_admin();

		$function = [
			'js' => 'wp_enqueue_script',
			'css' => 'wp_enqueue_style',
		];

		foreach ( $types as $type ) {
			$scripts = $this->store->get( $type, [] );

			foreach ( $scripts as $key => $args ) {
				$allowed_args = \array_keys( $this->get_defaults( $type ) );
				\extract( h\array_only( $args, $allowed_args ) );

				if ( $in_admin !== null && $is_admin !== $in_admin ) continue;
				if ( ! call_user_func( $condition ) ) continue;

				$this->enqueued = true;

				$function[ $type ](
					$handle,
					$src,
					$deps,
					$version,
					'js' == $type ? $in_footer : $media
				);

				h\logf(
					sprintf(
						'Enqueued %s: path=%s',
						\strtoupper( $type ),
						$path
					)
				);

				if ( 'js' == $type && ! is_null( $script_data ) ) {
					$script_data = \is_callable( $script_data ) ? \call_user_func( $script_data ) : $script_data;
					$this->script_data = \array_merge( $this->script_data, $script_data );
				}
			}
		}
	}

	public function print_script_data () {
		if ( $this->enqueued ) {
			$plugin_name = h\config_get( 'NAME' );
			$data = [
				'$ajax_url' => h\get_ajax_url(),
				'$prefix' => h\config_get( 'PREFIX' ),
				'$slug' => h\config_get( 'SLUG' ),
				'$debug' => h\get_defined( 'WP_DEBUG' ),
				'$nonces' => h\get_ajax_nonces(),
				'$logged_in' => \is_user_logged_in(),
			];
			echo esc_html("<!-- Script Data of $plugin_name plugin -->");
			\printf(
				'<script>window.wp_script_data=window.wp_script_data||{};wp_script_data[\'%s\']=%s</script>',
				\esc_js( h\str_slug( h\config_get( 'SLUG' ), '_' ) ),
				h\safe_json_encode( array_merge( [], $this->script_data, $data ) )
			);
			echo  esc_html("<!-- /Script Data of $plugin_name plugin -->");
		}
	}

	protected function get_types () {
		return [
			'js',
			'css',
		];
	}

	protected function is_valid_extension ( $extension ) {
		$extensions = $this->get_types();
		return \in_array( $extension, $extensions );
	}

	protected function get_defaults ( $type ) {
		$defaults = [
			'src' => null,
			'path' => null,
			'handle' => null,
			'version' => h\config_get( 'VERSION' ) . ( WP_DEBUG ? '.' . \time() : '' ),
			'deps' => false,
			'condition' => '__return_true',
			'type' => $type,
			'in_admin' => false,
		];

		if ( 'js' == $type ) {
			$defaults['in_footer'] = true;
			$defaults['script_data'] = null;
		}
		elseif ( 'css' == $type ) {
			$defaults['media'] = 'all';
		}

		return \apply_filters( h\prefix( 'assets_default_args' ), $defaults, $type );
	}
}
