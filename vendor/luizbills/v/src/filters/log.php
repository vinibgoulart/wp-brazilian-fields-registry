<?php

// logs the $value (only in debug mode)
return function ( $value, $args ) {
	$id = $args->get( 0, null );
	$str_value = null;
	$type = \gettype( $value );

	switch ( $type ) {
		case 'NULL':
			$str_value = 'null';
			break;
		case 'boolean':
			$str_value = $value ? 'true' : 'false';
			break;
		case 'string':
			$str_value = "\"$value\"";
			break;
		default:
			$str_value = print_r( $value, true );
	}

	$at = ( null !== $id ? " @ $id" : '' );
	\error_log( "[v log$at] ($type) $str_value" );

	return $value;
};
