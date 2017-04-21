<?php

namespace Shortcake_Bakery\Shortcodes;

class Guardian extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'The Guardian', 'shortcake-bakery' ),
			'listItemImage'  => '<img src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/svg/icon-guardian.svg' ) . '" />',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => esc_html__( 'URL to a Guardian video', 'shortcake-bakery' ),
				),
			),
		);
	}

	public static function reversal( $content ) {
		$iframes = self::parse_iframes( $content );
		if ( $iframes ) {
			$replacements = array();
			foreach ( $iframes as $iframe ) {
				if ( ! in_array( self::parse_url( $iframe->attrs['src'], PHP_URL_HOST ), array( 'embed.theguardian.com' ), true ) ) {
					continue;
				}
				$path = self::parse_url( $iframe->attrs['src'], PHP_URL_PATH );
				$url = 'http://www.theguardian.com' . preg_replace( '#^/embed/video/#', '/', $path );
				$replacements[ $iframe->original ] = '[' . self::get_shortcode_tag() . ' url="' . esc_url_raw( $url ) . '"]';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}

		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		if ( empty( $attrs['url'] ) || ! in_array( self::parse_url( $attrs['url'], PHP_URL_HOST ), array( 'theguardian.com', 'www.theguardian.com' ), true ) ) {
			return '';
		}

		$path = self::parse_url( $attrs['url'], PHP_URL_PATH );
		$url = 'https://embed.theguardian.com/embed/video' . $path;
		return sprintf( '<iframe class="shortcake-bakery-responsive" width="560" height="315" src="%s" frameborder="0"></iframe>', esc_url( $url ) );
	}

}
