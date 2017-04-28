<?php

namespace Shortcake_Bakery;

/**
 * Proxy handler for remote assets which need local headers to bypass CORS
 * restrictions. Used by the [pdf] shortcode.
 *
 */
class Asset_Proxy {

	private static $instance;

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Asset_Proxy;
			self::$instance->setup_actions();
		}
		return self::$instance;
	}

	private function setup_actions() {
		add_action( 'wp_ajax_shortcake_bakery_asset_proxy', array( $this, 'handle_asset_proxy' ) );
		add_action( 'wp_ajax_nopriv_shortcake_bakery_asset_proxy', array( $this, 'handle_asset_proxy' ) );
	}

	public function handle_asset_proxy() {
		$asset_url = stripslashes( $_REQUEST['url'] );
		$security_check = $_REQUEST['_nonce'];

		if ( empty( $asset_url ) || ! wp_verify_nonce( $security_check, 'asset-proxy-' . $asset_url ) ) {
			die();
		}

		/*
		 * Filter the stream context options for the asset proxy.
		 *
		 * Can be used to increase or decrease the timeout at which point the
		 * server will stop loading the CORS asset proxy.
		 *
		 * @param array HTTP options for asset stream
		 */
		$stream_options = apply_filters( 'shortcake_bakery_asset_proxy_stream_options',
			array(
				'http' => array(
					'method'  => 'GET',
					'timeout' => 5,     // Hang up if PDF takes more than 5 seconds to transfer
				),
			)
		);
		$stream_context = stream_context_create( $stream_options );

		// @codingStandardsIgnoreStart
		// (Note: the `fopen()`s here happen in a stream context, so we can't use
		// the WP_Filesystem class for it. Because we're opening the remote file in a
		// stream, it also isn't required that the allow_url_fopen directive be
		// enabled in PHP for this to work.)
		$input_handle = fopen( esc_url_raw( $asset_url ), 'r', null, $stream_context );
		$output_handle = fopen( 'php://output', 'w+', null, $stream_context );

		foreach ( get_headers( $asset_url ) as $header_str ) {
			if ( 0 === strpos( $header_str, 'Content-' ) ) {
				header( $header_str );
			}
		}

		stream_copy_to_stream( $input_handle, $output_handle );
		// @codingStandardsIgnoreEnd

		exit;
	}
}
