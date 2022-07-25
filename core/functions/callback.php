<?php

namespace WPBrazilianRegistry\functions;

function return_value ( $value ) {
	return function () use ( $value ) {
		return $value;
	};
}

function return_push_value ( $value ) {
	return function ( $arr ) use ( $value ) {
		$arr[] = $value;
		return $arr;
	};
}

function return_push_key_value ( $key, $value ) {
	return function ( $arr ) use ( $key, $value ) {
		$arr[ $key ] = $value;
		return $arr;
	};
}
