<?php

namespace Shortcake_Bakery\Shortcodes;

class PDF extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'PDF', 'shortcake-bakery' ),
			'listItemImage'  => 'dashicons-media-document',
			'attrs'          => array(
				array(
					'label'       => esc_html__( 'Select or upload PDF', 'shortcake-bakery' ),
					'attr'        => 'attachment',
					'type'        => 'attachment',
					'libraryType' => 'application/pdf',
				),
				array(
					'label'       => esc_html__( '...or embed from URL', 'shortcake-bakery' ),
					'attr'        => 'url',
					'type'        => 'text',
				),
			),
		);
	}

	public static function callback( $attrs, $content = '' ) {

		if ( ! empty( $attrs['attachment'] ) && 'application/pdf' === get_post_mime_type( absint( $attrs['attachment'] ) ) ) {
			$url = get_attached_file( absint( $attrs['attachment'] ) );
		} elseif ( ! empty( $attrs['url'] ) ) {
			$url = esc_url_raw( $attrs['url'] );
		} else {
			return '';
		}

		$url_for_parse = ( 0 === strpos( $url, '//' ) ) ? 'http:' . $url :  $url;
		$scheme = self::parse_url( $url_for_parse, PHP_URL_SCHEME );

		$ext = pathinfo( $url, PATHINFO_EXTENSION );
		if ( 'pdf' !== strtolower( $ext ) ) {
			return '';
		}

		$viewer_url = SHORTCAKE_BAKERY_URL_ROOT . 'assets/lib/pdfjs/web/viewer.html';
		$source = add_query_arg( 'file', rawurlencode( $url ), $viewer_url );

		return '<iframe class="shortcake-bakery-responsive" data-true-height="800px" data-true-width="600px" width="600px" height="800px" frameBorder="0" src="' . esc_url( $source ) . '"></iframe>';
	}

}
