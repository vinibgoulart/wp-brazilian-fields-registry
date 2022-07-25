<?php

namespace WPBrazilianRegistry\functions;

use WPBrazilianRegistry\Core\Config;

function config_set ( $key, $value ) {
	return Config::set( $key, $value );
}

function config_get ( $key, $default = null ) {
	return Config::get( $key, $default );
}

function config_set_instance ( $class_name ) {
	return Config::set( '__instance__' . $class_name, new $class_name() );
}

function config_get_instance ( $class_name, $default = null ) {
	return Config::get( '__instance__' . $class_name, $default );
}

function prefix ( $string = '' ) {
	return Config::get( 'PREFIX' ) . $string;
}
