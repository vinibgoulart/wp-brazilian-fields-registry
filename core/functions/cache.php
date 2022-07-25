<?php
// Inspired by https://github.com/stevegrunwell/wp-cache-remember

namespace WPBrazilianRegistry\functions;

function remember_cache ( $key, $value, $expires_in = 0, $period = 'minutes' ) {
	$cache_disabled = \apply_filters(
		prefix( 'remember_cache_disabled' ),
		config_get( 'DISABLE_CACHE', false ),
		$key
	);

	if ( ! $cache_disabled ) {
		$cached = fetch_cache( $key, false );

		if ( false !== $cached ) {
			return $cached;
		}
	}

	$result = is_callable( $value ) ? call_user_func( $value ) : $value;

	if ( $cache_disabled ) {
		logf( "function remember_cache disabled for $key" );
		return $result;
	}

	if ( false !== $result && null !== $result && ! \is_wp_error( $result ) ) {
		$transient_key = build_cache_key( $key );
		$duration = \apply_filters(
			prefix( 'remember_cache_expiration' ),
			to_seconds( $expires_in, $period ),
			$key
		);

		\set_transient( $transient_key, $result, $duration );
		logf( "function remember_cache stored key $transient_key" );
	}

	return $result;
}

function fetch_cache ( $key, $default = null ) {
	$transient_key = build_cache_key( $key );
	$cached = \get_transient( $transient_key );

	if ( false !== $cached ) {
		return $cached;
	}
	return $default;
}

function forget_cache ( $key, $default = null ) {
	$cached = fetch_cache( $key, false );

	if ( false !== $cached ) {
		$transient_key = build_cache_key( $key );
		\delete_transient( $transient_key );
		logf( "function remember_cache deleted key $key" );
		return $cached;
	}
	return $default;
}

function clear_plugin_cache ( $prefix = '' ) {
	if ( \wp_using_ext_object_cache() ) {
		logf( 'External Object Cache detected. Flusing...' );
		\wp_cache_flush();
		return;
	}

	global $wpdb;
	$prefix = get_cache_key_prefix() . $prefix;

	if ( ! \is_multisite() ) {
		// non-Multisite stores site transients in the options table.
		$wpdb->query(
			$wpdb->prepare(
				"DELETE a, b
				FROM {$wpdb->options} a, {$wpdb->options} b
				WHERE a.option_name LIKE %s
				AND b.option_name LIKE %s",
				$wpdb->esc_like( "_transient_$prefix" ) . '%',
				$wpdb->esc_like( "_transient_timeout_$prefix" ) . '%'
			)
		);
		logf( 'Cache cleared!' );
	} elseif ( \is_multisite() && \is_main_site() && \is_main_network() ) {
		// Multisite stores site transients in the sitemeta table.
		$wpdb->query(
			$wpdb->prepare(
				"DELETE a, b
				FROM {$wpdb->sitemeta} a, {$wpdb->sitemeta} b
				WHERE a.option_name LIKE %s
				AND b.option_name LIKE %s",
				$wpdb->esc_like( "_site_transient_$prefix" ) . '%',
				$wpdb->esc_like( "_site_transient_timeout_$prefix" ) . '%'
			)
		);
		logf( 'Cache cleared! (multisite)' );
	}
}

function get_cache_key_prefix () {
	return \apply_filters( prefix( 'cache_key_prefix' ), prefix() );
}

function build_cache_key ( $key ) {
	return get_cache_key_prefix() . $key . get_cache_key_suffix();
}

function get_cache_key_suffix () {
	$plugin_version = config_get( 'VERSION', '' );
	return \apply_filters( prefix( 'cache_key_suffix' ), "_{$plugin_version}" );
}
