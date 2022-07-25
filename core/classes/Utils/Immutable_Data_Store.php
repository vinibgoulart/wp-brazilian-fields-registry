<?php

namespace WPBrazilianRegistry\Utils;

use WPBrazilianRegistry\functions as h;

class Immutable_Data_Store extends Data_Store {

	public function set ( $key, $value ) {
		h\throw_if( $this->has( $key ), __CLASS__ . ": key \"$key\" already assigned." );
		return parent::set( $key, $value );
	}

	public function clear ( $key ) {
		h\throw_if( true, __CLASS__ . ": It's not possible clear an immutable data store." );
	}
}

