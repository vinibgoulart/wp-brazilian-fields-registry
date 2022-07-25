<?php

namespace WPBrazilianRegistry\Common;

trait Hooker_Trait {

	public function add_action ( $hook_name, $callback, $priority = 10, $arguments = 1 ) {
		return \add_action( $hook_name, [ $this, $callback ], $priority, $arguments );
	}

	public function remove_action ( $hook_name, $callback, $priority = 10, $arguments = 1 ) {
		return \remove_action( $hook_name, [ $this, $callback ], $priority, $arguments );
	}

	public function add_filter ( $hook_name, $callback, $priority = 10, $arguments = 1 ) {
		return \add_filter( $hook_name, [ $this, $callback ], $priority, $arguments );
	}

	public function remove_filter ( $hook_name, $callback, $priority = 10, $arguments = 1 ) {
		return \remove_filter( $hook_name, [ $this, $callback ], $priority, $arguments );
	}
}
