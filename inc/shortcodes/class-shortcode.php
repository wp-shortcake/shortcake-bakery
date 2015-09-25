<?php

namespace Shortcake_Bakery\Shortcodes;

/**
 * Base class for all shortcodes to extend
 * Ensures each shortcode implements a consistent pattern
 */
abstract class Shortcode {

	/**
	 * Get the "tag" used for the shortcode. This will be stored in post_content
	 *
	 * @return string
	 */
	public static function get_shortcode_tag() {
		$parts = explode( '\\', get_called_class() );
		$shortcode_tag = array_pop( $parts );
		$shortcode_tag = strtolower( str_replace( '_', '-', $shortcode_tag ) );
		return apply_filters( 'shortcake_bakery_shortcode_tag', $shortcode_tag, get_called_class() );
	}

	/**
	 * Allow subclasses to register their own action
	 * Fires after the shortcode has been registered on init
	 *
	 * @return null
	 */
	public static function setup_actions() {
		// No base actions are necessary
	}

	public static function get_shortcode_ui_args() {
		return array();
	}

	/**
	 * Turn embed code into a proper shortcode
	 *
	 * @param string $content
	 * @return string $content
	 */
	public static function reversal( $content ) {
		return $content;
	}

	/**
	 * Render the shortcode. Remember to always return, not echo
	 *
	 * @param array $attrs Shortcode attributes
	 * @param string $content Any inner content for the shortcode (optional)
	 * @return string
	 */
	public static function callback( $attrs, $content = '' ) {
		return '';
	}

	/**
	 * Parse iframes from a string, if there are any
	 *
	 * @param string $content
	 * @return array|false
	 */
	protected static function parse_iframes( $content ) {

		if ( false === stripos( $content, '<iframe' ) ) {
			return false;
		}

		if ( preg_match_all( '#(.+\r?\n?)?(<iframe([^>]+)>[^<]{0,}</iframe>)(\r?\n?.+)?#', $content, $matches ) ) {
			$iframes = array();
			foreach ( $matches[0] as $key => $value ) {
				$iframe = new \stdClass;
				$iframe->original = $matches[2][ $key ];
				$iframe->before = $matches[1][ $key ];
				$iframe->attrs = array( 'src' => '' );
				$iframe->after = $matches[4][ $key ];
				$parts = explode( ' ', $matches[3][ $key ] );
				foreach ( $parts as $part ) {
					$attr_parts = explode( '=', $part );
					if ( empty( $attr_parts[0] ) ) {
						continue;
					}
					$iframe->attrs[ $attr_parts[0] ] = isset( $attr_parts[1] ) ? trim( $attr_parts[1], '"\'' ) : null;
				}
				$iframes[] = $iframe;
			}
			return $iframes;
		} else {
			return false;
		}
	}

	/**
	 * Make replacements on the string, provided an array of potential replacements
	 *
	 * @param string $content
	 * @param array $replacements
	 * @return string
	 */
	protected static function make_replacements_to_content( $content, $replacements ) {
		if ( empty( $replacements ) ) {
			return $content;
		}
		return str_replace( array_keys( $replacements ), array_values( $replacements ), $content );
	}

}
