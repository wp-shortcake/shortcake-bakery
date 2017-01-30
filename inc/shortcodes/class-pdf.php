<?php

namespace Shortcake_Bakery\Shortcodes;

class PDF extends Shortcode {

	public static function get_shortcode_ui_args() {
		$shortcode_attrs = array(
			'label'          => esc_html__( 'PDF', 'shortcake-bakery' ),
			'listItemImage'  => 'dashicons-media-document',
			'attrs'          => array(
				array(
					'label'  => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'   => 'url',
					'type'   => 'text',
				),
			),
		);

		/*
		 * Filter whether to enable the CORS proxy option on the [pdf] shortcode.
		 *
		 * @param bool Returning false on this hook will prevent this option.
		 */
		if ( apply_filters( 'shortcake_bakery_pdf_enable_cors_proxy', true ) ) {

			$shortcode_attrs['attrs'][] = array(
				'label'  => esc_html__( 'Proxy through local domain?', 'shortcake-bakery' ),
				'attr'   => 'proxy',
				'type'   => 'checkbox',
				'description' => esc_html__(
					"External PDFs require proper Access-Control headers in order to embed. \nIf you are seeing 'An error occurred while loading the PDF' errors, try this.",
					'shortcake-bakery'
				),
			);
		}

		return $shortcode_attrs;
	}

	public static function callback( $attrs, $content = '' ) {
		if ( empty( $attrs['url'] ) ) {
			return '';
		}

		$url = esc_url_raw( $attrs['url'] );

		$url_for_parse = ( 0 === strpos( $url, '//' ) ) ? 'http:' . $url :  $url;
		$scheme = self::parse_url( $url_for_parse, PHP_URL_SCHEME );

		$ext = pathinfo( $url, PATHINFO_EXTENSION );
		if ( 'pdf' !== strtolower( $ext ) ) {
			return '';
		}

		if ( ! empty( $attrs['proxy'] ) && $attrs['proxy'] ) {
			$url = self::asset_proxy_url( $url );
		}

		$viewer_url = SHORTCAKE_BAKERY_URL_ROOT . 'assets/lib/pdfjs/web/viewer.html';
		$source = add_query_arg( 'file', rawurlencode( $url ), $viewer_url );

		return '<iframe class="shortcake-bakery-responsive" data-true-height="800px" data-true-width="600px" width="600px" height="800px" frameBorder="0" src="' . esc_url( $source ) . '"></iframe>';
	}

	/**
	 * Get the admin-ajax URL to proxy a PDF through local site.
	 *
	 * @param string URL
	 * @return string URL
	 */
	private static function asset_proxy_url( $url ) {
		return add_query_arg(
			array(
				'action' => 'shortcake_bakery_asset_proxy',
				'_nonce' => wp_create_nonce( 'asset-proxy-' . $url ),
				'url'    => $url,
			),
			admin_url( 'admin-ajax.php' )
		);
	}
}
