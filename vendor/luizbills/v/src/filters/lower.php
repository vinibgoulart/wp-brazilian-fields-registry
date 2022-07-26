<?php

// make the $value content lowercase
return function ( $value, $args ) {
	$encoding = $args->get( 0, 'UTF-8' );
	return \mb_strtolower( (string) $value, $encoding );
};
