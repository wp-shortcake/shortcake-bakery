<?php

namespace Shortcake_Bakery\Shortcodes;

class Infogram extends Shortcode
{

	/**
	 * Turn embed code into a proper shortcode
	 *
	 * @return array $args
	 */
	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Infogram', 'shortcake-bakery' ),
			'listItemImage' => '<img width="100px" height="100px" src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/svg/icon-infogram.svg' ) . '" />',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => esc_html__( 'URL to the Infogram', 'shortcake-bakery' ),
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
		$needle = '#<script id="[^<]+" src="//e\.infogr\.am/js/embed\.js\?[^>]+" type="text/javascript"></script>?#';
		if ( preg_match_all( $needle, $content, $matches ) ) {
			$replacements = array();
			$shortcode_tag = self::get_shortcode_tag();
			foreach ( $matches[0] as $key => $value ) {
				$parts = explode( '"', $value );
				$id = $parts[1];
				$url_string = str_replace( 'infogram_0_', '', $id );
				$replacements[ $value ] = '[' . $shortcode_tag . ' url="http://infogr.am/' . $url_string . '"]';
			}
			$content = str_replace( array_keys( $replacements ), array_values( $replacements ), $content );
		}
		return $content;
	}

	/**
	 * Render the shortcode. Remember to always return, not echo
	 *
	 * @param  array  $attrs   Shortcode attributes
	 * @param  string $content Any inner content for the shortcode (optional)
	 * @return string
	 */
	public static function callback( $attrs, $content = '' ) {
		if ( empty( $attrs['url'] ) ) {
			return '';
		}
		$id = preg_replace( '((http|https)\:\/\/infogr\.am\/)', '', $attrs['url'] );
		$out = '<script async src="//e.infogr.am/js/embed.js" id="infogram_0_';
		$out .= esc_attr( $id );
		$out .= '" type="text/javascript"></script>';
		return $out;
	}

}
