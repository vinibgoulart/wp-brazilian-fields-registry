<?php

use WPBrazilianRegistry\functions as h;

$methods = h\get_load_class_methods();

foreach ( $classes_to_load as $class_name ) {
	foreach ( $methods as $method ) {
		$priority = 10;

		if ( \is_array( $class_name ) ) {
			$class_name = $class_name[0];
			$priority = $class_name[1];
		}

		h\load_class( $class_name, $method, $priority );
	}
}

\register_activation_hook( h\config_get( 'MAIN_FILE' ), function () use ( $classes_to_load ) {
	foreach ( $classes_to_load as $class_name ) {
		if ( \method_exists( $class_name, '__activation' ) ) {
			\call_user_func( [ $class_name, '__activation' ] );
		}
	}
} );

\register_deactivation_hook( h\config_get( 'MAIN_FILE' ), function () use ( $classes_to_load ) {
	foreach ( $classes_to_load as $class_name ) {
		if ( \method_exists( $class_name, '__deactivation' ) ) {
			\call_user_func( [ $class_name, '__deactivation' ] );
		}
	}
} );
