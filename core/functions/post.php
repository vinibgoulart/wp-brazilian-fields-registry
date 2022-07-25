<?php

namespace WPBrazilianRegistry\functions;

function get_post_by_type ( $id, $post_type = 'post', $exception = true ) {
	$post = \get_post( (int) $id );

	if ( $exception ) {
		throw_if( null === $post, "Invalid post ID: $id", 'invalid-post-id' );
		throw_if( $post_type !== $post->post_type, "Invalid type for post #$id: $post_type", 'invalid-post-type' );
	} else {
		$post = $post && $post_type == $post->post_type ? $post : false;
	}

	return $post;
}
