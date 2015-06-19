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

	/**
	 * Turn embed code into a proper shortcode
	 *
	 * @param  string $content
	 * @return string $content
	 */
	public static function reversal( $content ) {
		$needle = '#http[^<]//[^<]+\.pdf?#';
		if ( preg_match_all( $needle, $content, $matches ) ) {
			$replacements = array();
			$shortcode_tag = self::get_shortcode_tag();
			foreach ( $matches[0] as $key => $value ) {
				$replacements[ $value ] = '[' . $shortcode_tag . ' url="' . $value . '"]';
			}
			$content = str_replace( array_keys( $replacements ), array_values( $replacements ), $content );
		}
		return $content;
	}

	public static function callback( $attrs, $content = '' ) {
		if ( empty( $attrs['url'] ) ) {
			return '';
		}

		$url = esc_url_raw( $attrs['url'] );
		$ext = pathinfo( $url, PATHINFO_EXTENSION );
		if ( 'pdf' !== strtolower( $ext ) ) {
			return '';
		}
		return '<iframe class="shortcake-bakery-responsive" data-true-height="800px" data-true-width="600px" width="600px" height="800px" frameBorder="0" src="' . esc_url( 'https://mozilla.github.io/pdf.js/web/viewer.html?file=' . rawurlencode( $url ) ) . '"></iframe>';
	}

}
