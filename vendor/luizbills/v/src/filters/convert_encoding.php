<?php

// The convert_encoding filter converts a string from one encoding to another.
// The first argument is the expected output charset and the second one is the input charset:
return function ( $value, $args ) {
	$to = $args->get( 0 );
	$from = $args->get( 1, 'UTF-8' );
	return \mb_convert_encoding( (string) $value, $to, $from );
};
