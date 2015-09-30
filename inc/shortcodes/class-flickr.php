<?php

namespace Shortcake_Bakery\Shortcodes;

class Flickr extends Shortcode {

	private static $valid_hosts = array( 'flickr.com', 'www.flickr.com' );

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Flickr', 'shortcake-bakery' ),
			'listItemImage' => '<img width="50px" height="50px" src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/svg/icon-flickr.svg' ) . '" />',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => esc_html__( 'URL to a Flickr gallery', 'shortcake-bakery' ),
				),
			),
		);
	}

	public static function reversal( $content ) {

		if ( $iframes = self::parse_iframes( $content ) ) {
			$replacements = array();
			foreach ( $iframes as $iframe ) {
				if ( ! in_array( parse_url( $iframe->src_force_protocol, PHP_URL_HOST ), self::$valid_hosts ) ) {
					continue;
				}
				$url = preg_replace( '#/player/?$#', '/', $iframe->src_force_protocol );
				$replacements[ $iframe->original ] = '[' . self::get_shortcode_tag() . ' url="' . esc_url_raw( $url ) . '"]';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}

		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		if ( empty( $attrs['url'] ) || ! in_array( parse_url( $attrs['url'], PHP_URL_HOST ), self::$valid_hosts ) ) {
			return '';
		}

		// Append /player/ to the URL if it's not already there
		if ( false === stripos( substr( $attrs['url'], strlen( $attrs['url'] ) - 8 ), '/player' ) ) {
			$attrs['url'] = rtrim( $attrs['url'], '/' ) . '/player/';
		}
		return sprintf( '<iframe class="shortcake-bakery-responsive" width="500" height="334" src="%s" frameborder="0"></iframe>', esc_url( $attrs['url'] ) );
	}

}
