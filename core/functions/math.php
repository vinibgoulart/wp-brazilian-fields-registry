<?php

namespace WPBrazilianRegistry\functions;

function math_mean ( $numbers ) {
	return array_sum( $numbers ) / count( $numbers );
}

function math_negate ( $number ) {
	return $number > 0 ? -$number : $number;
}

function num_clamp ( $number, $min, $max ) {
	return \max( $min, \min( $max, $number ) );
}

function num_reset_after ( $number, $limit, $initial_value = 0 ) {
	return $number > $limit ? $initial_value : $number;
}

