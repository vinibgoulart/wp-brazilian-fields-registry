<?php

return function ( $value, $args ) {
	$url = $args->get( 0, '#' );
	$class = $args->get( 1, '' );

	$url = filter_var( $url, FILTER_SANITIZE_URL );
	$class = filter_var( $class, FILTER_SANITIZE_STRING );

	return "<a href='$url' class='$class'>$value</a>";
};
