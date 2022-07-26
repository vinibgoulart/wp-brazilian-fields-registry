<?php

// Prepends the first argument,
// and appends the second argument (default: same as the first)
return function ( $value, $args ) {
	$before = (string) $args->get( 0, '' );
	$after = $args->get( 1, $before );
	return $before . $value . $after;
};
