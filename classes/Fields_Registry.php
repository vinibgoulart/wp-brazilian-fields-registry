<?php

namespace WPBrazilianRegistry;

use WPBrazilianRegistry\Common\Hooker_Trait;
use WPBrazilianRegistry\functions as h;

class Fields_Registry
{
	use Hooker_Trait;

	public function __pre_boot ()
	{
		add_action( 'plugins_loaded', function() {
			/**
			 * Add fields
			 *
			 */
			add_action( 'woocommerce_register_form_start', array($this, 'registry_fields'));

			/**
			 * Validate Fields
			 *
			 */
			add_action( 'woocommerce_register_post', array($this, 'validate_fields'), 10, 3);

			/**
			 * Save Fields
			 *
			 */
		});
	}

	function registry_fields()
	{
		h\include_php_template( 'html-registry-fields.php');
	}

	function validate_fields($username, $user_email, $errors)
	{
		h\validate_fields($_POST, $errors);
	}
}
