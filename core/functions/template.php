<?php

namespace WPBrazilianRegistry\functions;

function include_php_template ( $template_path, $data = [] ) {
	// ensure php extension
	$template_path .= ! str_ends_with( $template_path, '.php' ) ? '.php' : '';

	// get template variables
	$paths = [
		// search in theme templates
		'theme' => \get_stylesheet_directory() . '/plugins/' . config_get( 'SLUG' ),
		// search in plugin templates
		'plugin' => config_get( 'ROOT_DIR' ) . '/' . config_get( 'TEMPLATES_DIR' )
	];
	
	$__found = false;
	
	try {
		foreach ( $paths as $_ => $base_path ) {
			if ( \file_exists( "$base_path/$template_path" ) ) {
				$__found = true;
				$var = \apply_filters( prefix( 'php_template_data' ), $data, $template_path );
				// render
				v_set_context( get_v_context() );
				include "$base_path/$template_path";
				v_reset_context();

				// exit after render once
				break;
			}
		}
	} catch ( \Exception $e ) {
		$message = "Error while rendenring \"$template_path\" template: " . $e->getMessage();
		echo esc_html(user_is_admin() ? esc_kses("<p class='wp-template-error'>$message</p>", ['p' => ['class'=> []]]) : '');
		logf( $message );
	}

	// throws an error if the template are not found
	throw_if( ! $__found, "Can't locate template file: $template_path" );
}

function get_php_template ( $template_path, $data = [] ) {
	ob_start();
	include_php_template( $template_path, $data );
	return ob_get_clean();
}

function get_v_context () {
	return \apply_filters( prefix( 'v_context' ), config_get( 'SLUG' ) );
}

function __register_custom_v_filters () {
	if ( config_get( 'custom_v_filters_registered', false ) ) {
		return;
	}
	config_set( 'custom_v_filters_registered', true );

	$context = get_v_context();

	// prepend the plugin prefix
	\v_register_filter(
		'with_prefix',
		function ( $value, $args ) {
			return prefix( $value );
		},
		$context
	);

	// prepend the plugin slug
	\v_register_filter(
		'with_slug',
		function ( $value, $args ) {
			return config_get( 'SLUG' ) . "-$value";
		},
		$context
	);

	// overwrites the escape filter
	\v_register_filter(
		'escape',
		function ( $value, $args ) {
			$type = (string) $args->get( 0 );
			$function = $type ? "esc_$type" : false;
			if ( $function ) {
				throw_if( ! \function_exists( $function ), 'unexpected argument 1 for v filter espape: ' . $type );
				return $function( $value );
			}
			return \esc_html( $value );
		},
		$context
	);
}
\add_action( 'plugins_loaded', __NAMESPACE__ . '\\__register_custom_v_filters', 0 );
