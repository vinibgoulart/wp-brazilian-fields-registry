<?php

// Return a formatted string
// see: https://www.php.net/manual/en/function.sprintf.php
return function ( $value, $args ) {
	$data = $args->get_all();
	return \sprintf( (string) $value, ...$data );
};
