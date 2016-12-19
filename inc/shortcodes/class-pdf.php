<?php

namespace Shortcake_Bakery\Shortcodes;

class PDF extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
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

		$viewer_url = SHORTCAKE_BAKERY_URL_ROOT . 'assets/pdf-viewer/viewer.html';
		$source = add_query_arg( 'file', rawurlencode( $url ), $viewer_url );

		return '<iframe class="shortcake-bakery-responsive" data-true-height="800px" data-true-width="600px" width="600px" height="800px" frameBorder="0" src="' . esc_url( $source ) . '"></iframe>';
	}

}
