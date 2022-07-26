<?php

return function ( $value, $args ) {
	$size = $args->get( 0, 1 );
	$enconding = $args->get( 1, mb_internal_encoding() );
	if ( mb_strlen( $value, $enconding ) > $size ) {
		return \mb_substr( $value, 0, $size, $enconding );
	}
	return $value;
};
