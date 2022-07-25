<?php

namespace WPBrazilianRegistry\functions;

function all_equal ( $values, $target, $strict = false ) {
	foreach ( array_wrap( $values ) as $value ) {
		$comparison = $strict ? $target !== $value : $target != $value;
		if ( $comparison ) return false;
	}
	return true;
}

function any_equal ( $values, $target, $strict = false ) {
	foreach ( array_wrap( $values ) as $value ) {
		$comparison = $strict ? $target === $value : $target == $value;
		if ( $comparison ) return true;
	}
	return false;
}

function none_equal ( $values, $target, $strict = false ) {
	return ! any_equal( $values, $target, $strict );
}
