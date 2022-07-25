<?php
/*
Plugin Name: WP Brazilian Fields Registry
Version: 1.0.0
Description: Adiciona os campos <strong>CPF/CNPJ</strong>(junto com tipo de pessoa), <strong>Telefone</strong>, <strong>Inscricao Estadual</strong> no cadastro de um usuário no WordPress. Utiliza os mesmos campos que o Brazilian Market on WooCommerce
Author: Vinicius Blazius Goulart
Author URI: https://github.com/ViniBGoulart
Update URI: false

Text Domain: wp-brazilian-fields-registry
Domain Path: /languages

License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'WPINC' ) ) die();

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
	\WPBrazilianRegistry\Core\Plugin::run( __FILE__ );
} else {
	\add_action( 'admin_notices', function () {
		list( $plugin_name ) = \get_file_data( __FILE__, [ 'plugin name' ] );
		$message = sprintf(
			__( '%s can\'t be initialized because the Composer dependencies were not installed. Reinstall the plugin or run <code>composer install</code>.', 'wp-brazilian-fields-registry' ),
			"<strong>$plugin_name</strong>"
		);
		echo "<div class='notice notice-error'><p>$message</p></div>";
	} );
}
