<?php

// make the $value content uppercase
return function ( $value, $args ) {
	$encoding = $args->get( 0, 'UTF-8' );
	return \mb_strtoupper( (string) $value, $encoding );
};
