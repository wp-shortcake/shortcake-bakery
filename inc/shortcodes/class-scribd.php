<?php

namespace Shortcake_Bakery\Shortcodes;

class Scribd extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Scribd', 'shortcake-bakery' ),
			'listItemImage'  => '<img src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/svg/icon-scribd.svg' ) . '" />',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => esc_html__( 'Full URL to the Scribd document', 'shortcake-bakery' ),
				),
			),
		);
	}

	public static function reversal( $content ) {
		$iframes = self::parse_iframes( $content );
		if ( $iframes ) {
			$replacements = array();
			foreach ( $iframes as $iframe ) {
				if ( ! in_array( self::parse_url( $iframe->attrs['src'], PHP_URL_HOST ), array( 'www.scribd.com', 'scribd.com' ), true ) ) {
					continue;
				}
				// URL looks like: https://www.scribd.com/embeds/272220183/content?start_page=1&amp;view_mode=scroll&amp;show_recommendations=true
				$url = explode( 'content?', $iframe->attrs['src'] );
				$url = str_replace( '/embeds/', '/doc/', $url[0] );
				$replacements[ $iframe->original ] = '[' . self::get_shortcode_tag() . ' url="' . esc_url_raw( $url ) . '"]';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}
		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		if ( empty( $attrs['url'] ) ) {
			return '';
		}

		if ( ! preg_match( '#(http|https)://www\.scribd\.com/doc(ument)?/?(\d)+/#', $attrs['url'], $needle ) ) {
			if ( current_user_can( 'edit_posts' ) ) {
				/* Translators: Invalid document error. */
				return '<div class="shortcake-bakery-error"><p>' . sprintf( esc_html__( 'Invalid Scribd URL: %s', 'shortcake-bakery' ), esc_url( $attrs['url'] ) ) . '</p></div>';
			} else {
				return '';
			}
		}
		$exploded_url = explode( '/', $needle[0] );
		$id = $exploded_url[4];

		$url = 'https://www.scribd.com/embeds/' . $id . '/content?start_page=1&view_mode=scroll&access_key=key-ooxdrkmSg8ieauz9qYXL&show_recommendations=true';
		$out = '<iframe class="scribd_iframe_embed shortcake-bakery-responsive" src="';
		$out .= esc_url( $url );
		$out .= '" data-auto-height="false" data-aspect-ratio="0.7631133671742809" scrolling="no" width="100%" height="600" frameborder="0"></iframe>';
		return $out;
	}

}
