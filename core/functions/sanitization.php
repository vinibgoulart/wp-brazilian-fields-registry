<?php

namespace WPBrazilianRegistry\functions;

/*
```
// Usage
$values = sanitize( $_POST, [
	// key    => array of filters (https://github.com/luizbills/v/tree/master/src/filters)
	'name'    => [ 'alpha_spaces' ],
	'message' => [ 'escape' ]
] );
```
*/
function sanitize ( $fields, $filters, $defaults = [] ) {
	$result = [];
	$fields = \array_merge( $defaults, $fields );
	
	\v_set_context( get_v_context() );
	foreach ( $filters as $key => $filter ) {
		$value = array_get( $fields, $key, '' );
		$filter = array_wrap( $filter );

		if ( 'array' == gettype( $value ) ) {
			$result[ $key ] = [];

			foreach ( $value as $item ) {
				$result[ $key ][] = sanitize( $item, $filter, $defaults[ $key ] );
			}
		}
		elseif ( 'string' == gettype( $value ) ) {
			$result[ $key ] = \v( $value, ...$filter );
		}
		else {
			$result[ $key ] = $value;
		}
	}
	\v_reset_context();

	return $result;
}
