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
			'listItemImage'  => '<img src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/svg/icon-infogram.svg' ) . '" />',
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

		if ( $scripts = self::parse_scripts( $content ) ) {
			$replacements = array();
			foreach ( $scripts as $script ) {
				if ( 'e.infogr.am' !== self::parse_url( $script->attrs['src'], PHP_URL_HOST ) ) {
					continue;
				}
				if ( empty( $script->attrs['id'] ) ) {
					continue;
				}
				$url_string = str_replace( 'infogram_0_', '', $script->attrs['id'] );
				$replacements[ $script->original ] = '[' . self::get_shortcode_tag() . ' url="' . esc_url( 'https://infogr.am/' . $url_string ) . '"]';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}
		if ( $iframes = self::parse_iframes( $content ) ) {
			$replacements = array();
			foreach ( $iframes as $iframe ) {
				if ( 'e.infogr.am' !== self::parse_url( $iframe->attrs['src'], PHP_URL_HOST ) ) {
					continue;
				}
				$url_string = ltrim( self::parse_url( $iframe->attrs['src'], PHP_URL_PATH ), '/' );
				$replacements[ $iframe->original ] = '[' . self::get_shortcode_tag() . ' url="' . esc_url( 'https://infogr.am/' . $url_string ) . '"]';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
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
