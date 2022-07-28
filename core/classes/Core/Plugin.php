<?php

namespace WPBrazilianRegistry\Core;

use WPBrazilianRegistry\functions as h;
use WPBrazilianRegistry\Core\Config;
use WPBrazilianRegistry\Core\Dependencies;
use WPBrazilianRegistry\Common\Hooker_Trait;

final class Plugin {
	use Hooker_Trait;

	protected static $instance = null;
	protected static $running = false;

	public static function run ( $main_file ) {
		if ( ! self::$running ) {
			new self( $main_file );
			self::$running = true;
		}
	}

	protected function __construct ( $main_file ) {
		$root = \dirname( $main_file ); // plugin root dir

		// include the helpers
		require_once $root . '/core/load_helpers.php';
		Config::setup( $main_file ); // set Config options
		$this->whoops(); // Whoops error handler

		// set loadable classes
		require_once $root . '/load.php';
		// call loader methods
		require_once $root . '/core/load_classes.php';

		$this->add_hooks(); // init the plugin
	}

	protected function add_hooks () {
		h\load_classes( 'pre_boot' );

		$this->add_action( 'plugins_loaded', 'boot' );
		$this->add_action( 'init', 'load_plugin_textdomain', 0 );
		$this->add_action( 'init', 'init' );
	}

	public function boot () {
		if ( Dependencies::validate() ) {
			h\load_classes( 'boot' );
		}
	}

	public function init () {
		if ( Dependencies::validate( false ) ) {
			h\load_classes( 'init' );
			\do_action( h\prefix( 'after_init' ) );
		}
	}

	public function load_plugin_textdomain () {
		\load_plugin_textdomain(
			'brazilian-fields-registry',
			false,
			\dirname( \plugin_basename( h\config_get( 'MAIN_FILE' ) ) ) . '/languages/'
		);
	}

	protected function whoops () {
		$use_whoops = h\all_equal(
			[
				\class_exists( 'Whoops\\Run' ),
				! \wp_doing_ajax(),
				h\get_defined( 'WP_DEBUG' ),
				h\get_defined( 'WP_DEBUG_DISPLAY' )
			],
			true,
			true
		);

		if ( $use_whoops ) {
			@error_reporting( E_ERROR | E_WARNING | E_PARSE );
			$whoops = h\config_set( 'whoops_instance', new \Whoops\Run() );
			$whoops->pushHandler( new \Whoops\Handler\PrettyPageHandler() );
			$whoops->register();
		}
	}
}
