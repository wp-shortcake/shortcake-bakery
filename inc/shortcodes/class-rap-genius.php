<?php

namespace Shortcake_Bakery\Shortcodes;

class Rap_Genius extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Rap Genius', 'shortcake-bakery' ),
			'listItemImage'  => '<img src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/svg/icon-rapgenius.svg' ) . '" />',
		);
	}

	/**
	 * Render the shortcode on-demand
	 *
	 * @param array $attrs
	 * @param string $content
	 */
	public static function callback( $attrs, $content = '' ) {
		return '<script async src="https://genius.codes"></script>';
	}

	public static function reversal( $content ) {

		if ( $scripts = self::parse_scripts( $content ) ) {
			$replacements = array();
			foreach ( $scripts as $script ) {
				if ( 'genius.codes' !== self::parse_url( $script->attrs['src'], PHP_URL_HOST ) ) {
					continue;
				}
				$replacements[ $script->original ] = '[' . self::get_shortcode_tag() . ']';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}
		return $content;
	}

}
