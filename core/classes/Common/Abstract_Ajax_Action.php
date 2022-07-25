<?php

namespace WPBrazilianRegistry\Common;

use WPBrazilianRegistry\functions as h;

abstract class Abstract_Ajax_Action {
	use Hooker_Trait;

	abstract public function get_action_key ();

	public function __init () {
		$this->add_action( 'wp_ajax_' . $this->get_action_name(), 'handle_request' );
		if ( $this->is_public() ) {
			$this->add_action( 'wp_ajax_nopriv_' . $this->get_action_name(), 'handle_request' );
		}

		if ( $this->get_nonce_action() ) {
			h\register_ajax_nonce(
				$this->get_action_key(),
				$this->get_nonce_query_arg(),
				\wp_create_nonce( $this->get_nonce_action() )
			);
		}
	}

	public function get_action_name () {
		return h\prefix( $this->get_action_key() );
	}

	public function is_public () {
		return false;
	}

	public function handle_get () {
		$this->send_default_response();
	}

	public function handle_post () {
		$this->send_default_response();
	}

	public function handle_request () {
		$this->validate_request();
		$method = $_SERVER['REQUEST_METHOD'];
		$callback = \strtolower( "handle_$method" );
		if ( \method_exists( $this,  $callback ) ) {
			$this->$callback();
		} else {
			$this->send_default_response();
		}
	}

	public function get_nonce_action () {
		return 'ajax_nonce_' . $this->get_action_name();
	}

	public function get_nonce_query_arg () {
		return '_ajax_nonce';
	}

	public function get_nonce_field ( $referer = true ) {
		return \wp_nonce_field(
			$this->get_nonce_action(),
			$this->get_nonce_query_arg(),
			$referer,
			false
		);
	}

	protected function validate_request () {
		// validate with wp nonce
		$nonce_action = $this->get_nonce_action();

		if ( $nonce_action ) {
			$query_arg = $this->get_nonce_query_arg();
			if ( ! \check_ajax_referer( $nonce_action, $query_arg, false ) ) {
				$message = \apply_filters(
					h\prefix( 'ajax_message_forbidden_access' ),
					'Forbidden Access.'
				);
				$this->send_json_error(
					$message,
					403
				);
			}
		}
	}

	protected function send_default_response () {
		$message = \apply_filters(
			h\prefix( 'ajax_message_invalid_request_method' ),
			'Invalid request method.'
		);
		$this->send_json_error(
			$message,
			405
		);
	}

	protected function send_json_error ( $error_message, $http_code = 400 ) {
		$this->send_json( null, $error_message, $http_code );
	}

	protected function send_json_success ( $data, $http_code = 200 ) {
		$this->send_json( $data, null, $http_code );
	}

	protected function send_json ( $data, $error_message = '', $http_code = null ) {
		$response = [];
		if ( empty( $error_message ) ) {
			$http_code = $http_code ? $http_code : 200;
		} else {
			$http_code = $http_code ? $http_code : 400;
			$response['error_message'] = $error_message;
		}
		$response['success'] = $http_code >= 200 && $http_code < 300;
		$response['data'] = $data;
		$response['meta'] = [
			'http_code' => $http_code
		];
		\wp_send_json( $response, $http_code );
	}
}
