<?php

namespace WPBrazilianRegistry;

use WPBrazilianRegistry\functions as h;
use WPBrazilianRegistry\Common\Hooker_Trait;

final class Plugin_Dependencies {
	use Hooker_Trait;

	public function __pre_boot () {
		$this->add_filter( h\prefix( 'plugin_dependencies' ), 'set_dependencies' );
		$this->add_action( h\prefix( 'handle_missing_dependencies' ), 'print_errors' );
	}

	public function set_dependencies ( $deps ) {
		$deps['php'] = function () {
			$php_server_version = $this->sanitize_version( \PHP_VERSION );
			$php_required_version = $this->get_required_php_version();

			if ( $php_required_version && ! $this->compare_version( $php_server_version, $php_required_version ) ) {
				$message = \sprintf(
					__( "Atualize a versão do PHP para %s ou mais recente.", 'wp-brazilian-fields-registry' ),
					$php_required_version
				);
				return \v( $message, 'safe_html', 'raw' );
			}
		};

		// $deps['woocommerce'] = function () {
		// 	if ( ! function_exists( 'WC' ) ) {
		// 		$message = \sprintf(
		// 			__( 'Instale e ative o plugin %s.', 'wp-brazilian-fields-registry' ),
		// 			'<strong>WooCommerce</strong>'
		// 		);
		// 		return \v( $message, 'safe_html', 'raw' );
		// 	}
		// };

		return $deps;
	}

	public function print_errors ( $errors ) {
		\add_action( 'admin_notices', function () use ( $errors ) {
			$name = h\config_get( 'NAME' );
			$message = sprintf(
				__( 'Não foi possível ativar o plugin %s. Siga as instruções abaixo:', 'wp-brazilian-fields-registry' ),
				$name
			);
			$message = "<strong>$message</strong>";

			foreach ( $errors as $error ) {
				$message .= \sprintf( '<br>%s%s', \str_repeat( '&nbsp;', 4 ), $error );
			}

			h\include_php_template( 'admin-notice.php', [
				'class' => 'error',
				'message' => \v( $message, 'safe_html', 'raw' ),
			] );
		});
	}

	protected function get_required_php_version () {
		// get required PHP version from composer.json
		$composer_file = h\config_get( 'ROOT_DIR' ) . '/composer.json';

		if ( \file_exists( $composer_file ) ) {
			$content = \file_get_contents( $composer_file );
			$json = h\safe_json_decode( $content, false );

			if ( isset( $json->require->php ) ) {
				return $this->sanitize_version( $json->require->php );
			}
		}

		return false;
	}

	protected function is_plugin_active ( $plugin_main_file ) {
		include_once( \ABSPATH . 'wp-admin/includes/plugin.php' );
		return \is_plugin_active( $plugin_main_file );
	}

	protected function sanitize_version ( $version ) {
		return  preg_replace( '/[^0-9\.]/', '', $version );
	}

	protected function compare_version ( $version1, $version2, $operator = '>=' ) {
		return \version_compare( $version1, $version2, $operator );
	}
}
