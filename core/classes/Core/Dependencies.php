<?php

namespace WPBrazilianRegistry\Core;

use WPBrazilianRegistry\functions as h;

class Dependencies {
	public static function validate ( $fire = true ) {
		$deps = \apply_filters( h\prefix( 'plugin_dependencies' ), [] );
		$errors = [];

		foreach ( $deps as $id => $check_dependency ) {
			$error = \call_user_func( $check_dependency );

			if ( $error ) {
				$errors[ $id ] = $error;
			}
		}

		if ( count( $errors ) > 0 ) {
			if ( $fire ) \do_action( h\prefix( 'handle_missing_dependencies' ), $errors );
			return false;
		}

		return true;
	}
}
