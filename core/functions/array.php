<?php

namespace WPBrazilianRegistry\functions;

function array_wrap ( $value ) {
	return \is_array( $value ) ? $value : [ $value ];
}

function array_get ( $arr, $key, $default = null ) {
	$keys = array_wrap( $key );
	foreach ( $keys as $k) {
		if ( is_array( $arr ) && isset( $arr[ $k ] ) ) {
			$arr = $arr[ $k ];
		} else {
			return $default;
		}
	}
	return $arr;
}

function array_head ( $arr ) {
	return \reset( $arr );
}

function array_tail ( $arr ) {
	return \array_slice( $arr, 1 );
}

function array_divide ( $arr ) {
	return [ \array_keys( $arr ), \array_values( $arr ) ];
}

function array_only ( $arr, $keys ) {
	return \array_intersect_key( $arr, \array_flip( (array) $keys ) );
}

function array_filter_by_prefix ( $arr, $prefix ) {
	$group = [];
	foreach ( $arr as $key => $value ) {
		if ( str_starts_with( $key, $prefix ) ) {
			$new_key = \substr( $key, strlen( $prefix ) );
			$group[ $new_key ] = $value;
		}
	}
	return $group;
}

function array_ensure_keys ( &$arr, $keys, $default = '' ) {
	foreach ( $keys as $key ) {
		$arr[ $key ] = array_get( $arr, $key, $default );
	}
	return $arr;
}

function array_find ( $array, $args, $operator = 'AND' ) {
	return array_head(
		\wp_list_filter( $array, $args, $operator ),
		0,
		null
	);
}
