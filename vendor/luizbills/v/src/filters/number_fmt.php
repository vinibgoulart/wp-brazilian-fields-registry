<?php

// Format a number with grouped thousands
// see: https://www.php.net/manual/en/function.number-format.php
return function ( $value, $args ) {
	$decimals = (int) $args->get( 0 );
	$dec_point = $args->get( 1, '.' );
	$thousands_sep = $args->get( 2, '' );
	return \number_format( (float) $value, $decimals, $dec_point, $thousands_sep );
};
