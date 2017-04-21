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
	 * parse_url(), fully-compatible with protocol-less URLs and PHP 5.3
	 *
	 * @param string $url
	 * @param int $component
	 * @return mixed
	 */
	protected static function parse_url( $url, $component = -1 ) {
		$added_protocol = false;
		if ( 0 === strpos( $url, '//' ) ) {
			$url = 'http:' . $url;
			$added_protocol = true;
		}
		// @codingStandardsIgnoreStart
		$ret = parse_url( $url, $component );
		// @codingStandardsIgnoreEnd
		if ( $added_protocol && $ret ) {
			if ( -1 === $component && isset( $ret['scheme'] ) ) {
				unset( $ret['scheme'] );
			} elseif ( PHP_URL_SCHEME === $component ) {
				$ret = '';
			}
		}
		return $ret;
	}

	/**
	 * Parse a string of content for a given tag name.
	 *
	 * @param string $content
	 * @param string $tag_name
	 * @return array|false
	 */
	private static function parse_closed_tags( $content, $tag_name ) {

		if ( false === stripos( $content, '<' . $tag_name ) ) {
			return false;
		}

		if ( preg_match_all( '#(.+\r?\n?)?(<' . $tag_name . '([^>]+)>([^<]+)?</' . $tag_name . '>)(\r?\n?.+)?#', $content, $matches ) ) {
			$tags = array();
			foreach ( $matches[0] as $key => $value ) {
				$tag = new \stdClass;
				$tag->original = $matches[2][ $key ];
				$tag->before = $matches[1][ $key ];
				$tag->attrs = array(
					'src' => '',
				);
				$tag->inner = $matches[4][ $key ];
				$tag->after = $matches[5][ $key ];
				$tag->attrs = self::parse_tag_attributes( $matches[3][ $key ] );
				$tags[] = $tag;
			}
			return $tags;
		} else {
			return false;
		}
	}

	/**
	 * Parse iframes from a string, if there are any
	 *
	 * @param string $content
	 * @return array|false
	 */
	protected static function parse_iframes( $content ) {
		return self::parse_closed_tags( $content, 'iframe' );
	}

	/**
	 * Parse script tags from a string, if there are any
	 *
	 * @param string $content
	 * @return array|false
	 */
	protected static function parse_scripts( $content ) {
		return self::parse_closed_tags( $content, 'script' );
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
		$text = preg_replace( "/[\x{00a0}\x{200b}]+/u", ' ', $text );
		$atts = array();

		if ( preg_match_all( $pattern, $text, $match, PREG_SET_ORDER ) ) {
			foreach ( $match as $m ) {
				if ( ! empty( $m[1] ) ) {
					$atts[ $m[1] ] = stripcslashes( $m[2] );
				} elseif ( ! empty( $m[3] ) ) {
					$atts[ $m[3] ] = stripcslashes( $m[4] );
				} elseif ( ! empty( $m[5] ) ) {
					$atts[ $m[5] ] = stripcslashes( $m[6] );
				} elseif ( isset( $m[7] ) && strlen( $m[7] ) ) {
					$atts[ $m[7] ] = null;
				} elseif ( isset( $m[8] ) ) {
					$atts[ $m[8] ] = null;
				}
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
