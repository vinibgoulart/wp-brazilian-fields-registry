<?php

// Make a value's first character uppercase.
// You can capitalize all words passing 'all' as first argument
return function ( $value, $args ) {
	$all = $args->get( 0 );
	if ( 'all' == $all ) {
		$words = \explode( ' ', $value );
		return \implode( ' ', \array_map( 'ucfirst', $words ) );
	}
	return \ucfirst( (string) $value );
};
