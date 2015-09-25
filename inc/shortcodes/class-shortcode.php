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

		if ( preg_match_all( '#(.+\r?\n?)?(<iframe([^>]+)>([^<]+)?</iframe>)(\r?\n?.+)?#', $content, $matches ) ) {
			$iframes = array();
			foreach ( $matches[0] as $key => $value ) {
				$iframe = new \stdClass;
				$iframe->original = $matches[2][ $key ];
				$iframe->before = $matches[1][ $key ];
				$iframe->attrs = array( 'src' => '' );
				$iframe->inner = $matches[4][ $key ];
				$iframe->after = $matches[5][ $key ];
				$iframe->attrs = self::parse_tag_attributes( $matches[3][ $key ] );

				// Use src_force_protocol with parse_url() in PHP 5.3
				if ( ! empty( $iframe->attrs['src'] ) ) {
					$iframe->src_force_protocol = 0 === strpos( $iframe->attrs['src'], '//' ) ? 'http:' . $iframe->attrs['src'] : $iframe->attrs['src'];
				} else {
					$iframe->src_force_protocol = '';
				}
				$iframes[] = $iframe;
			}
			return $iframes;
		} else {
			return false;
		}
	}

	/**
	 * Parse an attribute string into it's HTML attributes.
	 *
	 * Uses the regexes defined by WordPress core in `shortcode_parse_atts`.
	 *
	 * @param str $text list of attributes
	 * @return array
	 */
	protected static function parse_tag_attributes( $text ) {
		$pattern = '/([\w-]+)\s*=\s*"([^"]*)"(?:\s|$)|([\w-]+)\s*=\s*\'([^\']*)\'(?:\s|$)|([\w-]+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
		$text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
		$atts = array();

		if ( preg_match_all($pattern, $text, $match, PREG_SET_ORDER) ) {
			foreach ($match as $m) {
				if (!empty($m[1]))
					$atts[$m[1]] = stripcslashes($m[2]);
				elseif (!empty($m[3]))
					$atts[$m[3]] = stripcslashes($m[4]);
				elseif (!empty($m[5]))
					$atts[$m[5]] = stripcslashes($m[6]);
				elseif (isset($m[7]) && strlen($m[7]))
					$atts[$m[7]] = null;
				elseif (isset($m[8]))
					$atts[$m[8]] = null;
			}
		}

		return $atts;
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
