<?php

namespace Shortcake_Bakery\Shortcodes;

class Giphy extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Giphy', 'shortcake-bakery' ),
			'listItemImage'  => '<img width="100px" height="100px" style="padding-top:10px;" src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/png/icon-giphy.png' ) . '" />',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => esc_html__( 'Full URL to the Giphy', 'shortcake-bakery' ),
				),
			),
		);
	}

	public static function reversal( $content ) {

		if ( $iframes = self::parse_iframes( $content ) ) {
			$replacements = array();
			foreach( $iframes as $iframe ) {
				if ( 'giphy.com' !== parse_url( $iframe->attrs['src'], PHP_URL_HOST ) ) {
					continue;
				}
				// Embed ID is the last part of the URL
				$parts = explode( '/', trim( parse_url( $iframe->attrs['src'], PHP_URL_PATH ), '/' ) );
				$embed_id = array_pop( $parts );
				$replacements[ $iframe->original ] = '[' . self::get_shortcode_tag() . ' url="' . esc_url_raw( 'http://giphy.com/gifs/' . $embed_id ) . '"]';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}

		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		if ( empty( $attrs['url'] ) || 'giphy.com' !== parse_url( $attrs['url'], PHP_URL_HOST ) ) {
			return '';
		}

		// ID is always the last part of the URL
		$parts = explode( '-', $attrs['url'] );
		$embed_id = array_pop( $parts );
		$embed_url = '//giphy.com/embed/' . $embed_id;
		return sprintf( '<iframe src="%s" frameBorder="0" class="giphy-embed shortcake-bakery-responsive" allowFullScreen></iframe>', esc_url( $embed_url ) );
	}

}
