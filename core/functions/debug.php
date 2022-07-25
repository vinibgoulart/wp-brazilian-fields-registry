<?php

namespace WPBrazilianRegistry\functions;

/*
 * `throw_if` and `handle_exception` was moved to exception.php
*/

function logf ( ...$args ) {
	$is_enabled = \apply_filters( prefix( 'debug_log_enabled' ), get_defined( 'WP_DEBUG_LOG', false ) );
	if ( ! $is_enabled ) return;
	\error_log( config_get( 'SLUG' ) . ': ' . format( ...$args ) );
}

function log ( ...$args ) {
	\error_log( '[WARNING] ' . __FUNCTION__ . ' is deprecated! Use "h\logf()" (in core/functions/debug.php) instead.' );
	return logf( ...$args );
}

function dd ( $value ) {
	\function_exists( 'dump' ) ? \dump( $value ) : \var_dump( $value);
	die(1);
}
