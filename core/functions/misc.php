<?php

namespace WPBrazilianRegistry\functions;

function is_function ( $func ) {
	return is_object( $func ) && $func instanceof \Closure;
}

function get ( $value, $default = null ) {
	$result = is_function( $value ) ? $value() : $value;
	return ! empty( $result ) ? $result : $default;
}

function value ( $value, $default = null ) {
	\error_log( '[WARNING] ' . __FUNCTION__ . ' is deprecated! Use "h\get()" (in core/functions/misc.php) instead.' );
	return get( $value, $default );
}

function maybe_define ( $key, $value = true, $force_upper_case = true ) {
	$key = $force_upper_case ? \strtoupper( $key ) : $key;
	if ( ! defined( $key ) ) {
		define( $key, $value );
	}
}

function get_defined ( $key, $default = false ) {
	if ( defined( $key ) ) {
		return constant( $key );
	}
	return $default;
}

function get_class_constants ( $class_name ) {
	$reflect = new \ReflectionClass( $class_name );
	return $reflect->getConstants();
}

function format ( ...$args ) {
	$message = '';

	foreach( $args as $arg ) {
		if ( null === $arg ) {
			$message .= 'Null';
		}
		elseif ( \is_bool( $arg ) ) {
			$message .= $arg ? 'True' : 'False';
		}
		elseif ( ! \is_string( $arg ) ) {
			$message .= \print_r( $arg, true );
		} else {
			$message .= $arg;
		}
		$message .= ' ';
	}

	return $message;
}

function build_tag_attributes ( $atts_array ) {
	$result = '';
	foreach ( $atts_array as $key => $value) {
		$result .= ' ' . \esc_html( $key ) . '=' . str_add_quotes( \esc_attr( $value ) );
	}
	return \trim( $result );
}

function ns ( $include ) {
	return config_get( 'NAMESPACE_BASE' ) . $include;
}

function get_current_url ( $args = false ) {
	global $wp;
	$host = $_SERVER['HTTP_HOST'];
	$path = $wp->request;
	$protocol = isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
	$url = "$protocol://$host/$path";
	return $args ? \add_query_arg( $_GET, $url ) : $url;
}

function to_seconds ( $amount, $period = 'minutes' ) {
	$time_periods = [
		'seconds' => 1,
		'minutes' => 60,
		'hours'   => 60 * MINUTE_IN_SECONDS,
		'days'    => 24 * HOUR_IN_SECONDS,
		'weeks'   => 7 * DAY_IN_SECONDS,
		'months'  => 30 * DAY_IN_SECONDS,
		'years'   => 365 * DAY_IN_SECONDS,
	];

	throw_if(
		! isset( $time_periods[ $period ] ),
		"unknown \"$period\" period passed in " . __FUNCTION__
	);

	return $amount * $time_periods[ $period ];
}

function get_hash ( $data = null, $add_random = true ) {
	$data = (array) \json_encode( $data );
	if ( $add_random ) $data[] = \random_int( PHP_INT_MIN, PHP_INT_MAX );
	return \hash( 'sha256', \implode( ',', $data ) );
}
