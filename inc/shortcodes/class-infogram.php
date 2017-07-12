<?php

namespace Shortcake_Bakery\Shortcodes;

class Infogram extends Shortcode {

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
		$scripts = self::parse_scripts( $content );
		if ( $scripts ) {
			$replacements = array();
			$host = self::parse_url( $script->attrs['src'], PHP_URL_HOST );
			foreach ( $scripts as $script ) {
				if ( 'e.infogram.com' !== $host && 'e.infogr.am' !== $host ) {
					continue;
				}
				if ( empty( $script->attrs['id'] ) ) {
					continue;
				}
				$url_string = str_replace( 'infogram_0_', '', $script->attrs['id'] );
				$replacements[ $script->original ] = '[' . self::get_shortcode_tag() . ' url="' . esc_url( 'https://infogram.com/' . $url_string ) . '"]';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}
		$iframes = self::parse_iframes( $content );
		if ( $iframes ) {
			$replacements = array();
			foreach ( $iframes as $iframe ) {
				if ( 'e.infogram.com' !== $host && 'e.infogr.am' !== $host ) {
					continue;
				}
				$url_string = ltrim( self::parse_url( $iframe->attrs['src'], PHP_URL_PATH ), '/' );
				$replacements[ $iframe->original ] = '[' . self::get_shortcode_tag() . ' url="' . esc_url( 'https://infogram.com/' . $url_string ) . '"]';
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
		$id = preg_replace( '((http|https)\:\/\/infogr(\.am|am\.com)\/)', '', $attrs['url'] );
		$out = '<script async src="//e.infogram.com/js/embed.js" id="infogram_0_';
		$out .= esc_attr( $id );
		$out .= '" type="text/javascript"></script>';
		return $out;
	}

}
