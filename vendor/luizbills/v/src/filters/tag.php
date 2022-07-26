<?php

return function ( $value, $args ) {
	$tag = (string) $args->get( 0 );
	$class = $args->get( 1, '' );
	$id = $args->get( 2, '' );

	if ( '' == $tag ) {
		throw new InvalidArgumentException( 'First argument of "tag" filter must be a non-empty string' );
	}

	$tag = filter_var( $tag, FILTER_SANITIZE_STRING );
	$id = filter_var( $id, FILTER_SANITIZE_STRING );
	$class = filter_var( $class, FILTER_SANITIZE_STRING );

	return "<$tag class='$class' id='$id'>$value</$tag>";
};
