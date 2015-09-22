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

		$needle = '#<iframe[^>]+src="//giphy\.com/embed/([\w]+)"[^>]+></iframe>(<p><a[^>]+>via GIPHY</a></p>)?#';
		if ( preg_match_all( $needle, $content, $matches ) ) {
			$replacements = array();
			$shortcode_tag = self::get_shortcode_tag();
			foreach ( $matches[0] as $key => $value ) {
				$replacement_url = 'http://giphy.com/gifs/' . $matches[1][ $key ];
				$replacements[ $value ] = '[' . $shortcode_tag . ' url="' . esc_url_raw( $replacement_url ) . '"]';
			}
			$content = str_replace( array_keys( $replacements ), array_values( $replacements ), $content );
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
