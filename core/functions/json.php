<?php

namespace WPBrazilianRegistry\functions;

function safe_json_encode ( $data ) {
	$result = @json_encode( $data, \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE );
	$error = \json_last_error() !== \JSON_ERROR_NONE ? \json_last_error_msg() : false;

	if ( $error ) {
		throw_if( true, __FUNCTION__ . ": $error" );
	} else {
		// Escape </script> to prevent XSS
		// Note: Commonly `str_replace()` is not safe for corrupted string
		// But in our case, `json_encode()` already ensure `$result` as a
		// valid utf-8 string.
		return \str_replace( '</script>', '<\/script>', $result );
	}
}

function safe_json_decode ( $string, $array = true ) {
	$result = @json_decode( $string, $array );
	$error = \json_last_error() !== \JSON_ERROR_NONE ? \json_last_error_msg() : false;

	if ( $error ) {
		throw_if( true, __FUNCTION__ . ": $error" );
	}
	return $result;
}
