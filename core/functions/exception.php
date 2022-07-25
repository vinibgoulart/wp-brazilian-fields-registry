<?php

namespace WPBrazilianRegistry\functions;

function throw_if ( $condition, $message, $error_code = -1 ) {
	if ( $condition ) {
		if ( \is_callable( $message ) ) {
			$message = $message();
		}
		$slug = config_get( 'SLUG' );
		throw new \RuntimeException( "[$slug-error] $message", (int) $error_code );
	}
	return $condition;
}

function handle_exception ( \Throwable $exception ) {
	$message = $exception->getMessage();
	$slug = config_get( 'SLUG' );
	$error_prefix = "[$slug-error]";

	if ( str_starts_with( $message, $error_prefix ) ) {
		$error_message = trim( str_after( $message, $error_prefix ) );
		return [
			'error_message' => $error_message,
			'error_code' => $exception->getCode(),
		];
	}

	return false;
}
