<?php

namespace WPBrazilianRegistry\functions;

function register_ajax_nonce ( $key, $arg, $nonce ) {
	\add_filter(
		prefix( 'ajax_nonces' ),
		return_push_key_value(
			$key,
			[
				'arg' => $arg,
				'value' => $nonce
			]
		)
	);
}

function get_ajax_nonces ( $key = '' ) {
	$nonces = \apply_filters( prefix( 'ajax_nonces' ), [] );
	return $key ? array_get( $nonces, $key, false ) : $nonces;
}

function get_ajax_url () {
	return \admin_url( 'admin-ajax.php' );
}
