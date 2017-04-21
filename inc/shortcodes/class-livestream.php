<?php

namespace Shortcake_Bakery\Shortcodes;

class Livestream extends Shortcode {

	private static $valid_hosts = array( 'livestream.com', 'new.livestream.com' );

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Livestream', 'shortcake-bakery' ),
			'listItemImage'  => '<img src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/svg/icon-livestream.svg' ) . '" />',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => esc_html__( 'Full URL to the Livestream', 'shortcake-bakery' ),
				),
			),
		);
	}

	public static function reversal( $content ) {
		$iframes = self::parse_iframes( $content );
		if ( $iframes ) {
			$replacements = array();
			foreach ( $iframes as $iframe ) {
				if ( ! in_array( self::parse_url( $iframe->attrs['src'], PHP_URL_HOST ), self::$valid_hosts, true ) ) {
					continue;
				}
				// URL looks like: http://new.livestream.com/accounts/9035483/events/3424523/videos/64460770/player?width=480&height=270&autoPlay=false&mute=false
				$path = self::parse_url( $iframe->attrs['src'], PHP_URL_PATH );
				$path = preg_replace( '#/player/?$#', '/', $path );
				$url = 'https://livestream.com' . $path;
				$replacements[ $iframe->original ] = '[' . self::get_shortcode_tag() . ' url="' . esc_url_raw( $url ) . '"]';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}
		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		if ( empty( $attrs['url'] ) || ! in_array( self::parse_url( $attrs['url'], PHP_URL_HOST ), self::$valid_hosts, true ) ) {
			return '';
		}

		// Append /player/ to the URL if it's not already there
		if ( false === stripos( substr( $attrs['url'], strlen( $attrs['url'] ) - 8 ), '/player' ) ) {
			$attrs['url'] = rtrim( $attrs['url'], '/' ) . '/player/';
		}
		return sprintf( '<iframe class="shortcake-bakery-responsive" width="560" height="315" src="%s" frameborder="0"></iframe>', esc_url( $attrs['url'] ) );
	}

}
