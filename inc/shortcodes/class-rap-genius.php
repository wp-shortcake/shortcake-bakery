<?php

namespace Shortcake_Bakery\Shortcodes;

class Rap_Genius extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Rap Genius', 'shortcake-bakery' ),
			'listItemImage'  => 'RAP',
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
		if ( preg_match_all( '#<script async src="https://genius\.codes"></script>#', $content, $matches ) ) {
			$replacements = array();
			$shortcode_tag = self::get_shortcode_tag();
			foreach ( $matches[0] as $key => $value ) {
				$replacements[ $value ] = '[' . $shortcode_tag . ']';
			}
			$content = str_replace( array_keys( $replacements ), array_values( $replacements ), $content );
		}
		return $content;
	}

}
