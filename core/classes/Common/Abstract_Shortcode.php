<?php

namespace WPBrazilianRegistry\Common;

use WPBrazilianRegistry\functions as h;

abstract class Abstract_Shortcode {
	use Hooker_Trait;

	public function __init () {
		\add_shortcode( $this->get_shortcode_name() , [ $this, 'callback' ] );
	}

	abstract public function get_shortcode_name ();
	abstract public function get_output ( $atts, $content );

	public function get_default_attributes () {
		return [];
	}

	public function validate_attributes ( $atts ) {
		// should returns true on success
		// or an error message on failure
		return true;
	}

	public function callback ( $atts, $content = null, $name = null ) {
		$atts = empty( $atts ) ? [] : $atts;
		$atts = \shortcode_atts( $this->get_default_attributes(), $atts, $name );
		$validate = $this->validate_attributes( $atts );
		if ( true === $validate ) {
			return $this->get_output( $atts, $content );
		}
		return h\user_is_admin() ? "<div class='shortcode-$name-error'>$validate</div>" : '';
	}
}
