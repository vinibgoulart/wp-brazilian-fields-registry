<?php

namespace WPBrazilianRegistry\functions;

function load_class ( $class, $method = 'init', $priority = 10 ) {
	$methods = get_load_class_methods();
	$real_method = "__$method";

	throw_if( ! class_exists( $class ), "$class class not exists" );
	throw_if( ! in_array( $method, $methods ), "Invalid load_class method argument: $method" );

	if ( \method_exists( $class, $real_method ) ) {
		$wp_hook = get_load_class_hook( $method );
		\add_filter( $wp_hook, return_push_value( $class ), $priority );
	}
}

function get_load_class_methods () {
	return \apply_filters(
		prefix( 'load_class_methods' ),
		[
			'pre_boot',
			'boot',
			'init'
		]
	);
}

function get_load_class_hook ( $method ) {
	return prefix( "load_classes_on_$method" );
}

function load_classes ( $method ) {
	$wp_hook = get_load_class_hook( $method );
	$classes = \apply_filters( $wp_hook, [] );
	$real_method = "__$method";

	foreach ( $classes as $class_name ) {
		$instance = config_get_instance( $class_name, false );

		if ( false === $instance ) {
			$instance = config_set_instance( $class_name );
		}

		throw_if(
			! \method_exists( $instance, $real_method ),
			"The $class_name class don't has ${real_method}() method."
		);

		$instance->$real_method();
	}
}
