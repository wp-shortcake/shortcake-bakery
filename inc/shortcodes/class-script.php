<?php

namespace Shortcake_Bakery\Shortcodes;

class Script extends Shortcode {

	private static $whitelisted_script_domains = array();

	/**
	 * Get the whitelisted script domains for the plugin
	 */
	public static function get_whitelisted_script_domains() {
		return apply_filters( 'shortcake_bakery_whitelisted_script_domains', self::$whitelisted_script_domains );
	}

	public static function reversal( $content ) {

		$whitelisted_script_domains = self::get_whitelisted_script_domains();
		$shortcode_tag = self::get_shortcode_tag();

		if ( preg_match_all( '!\<script\s[^>]*?src=[\"\']([^\"\']+)[\"\'][^>]*?\>\s{0,}\</script\>!i', $content, $matches ) ) {
			$replacements = array();
			$shortcode_tag = self::get_shortcode_tag();
			foreach ( $matches[0] as $key => $value ) {
				$url = ( 0 === strpos(  $matches[1][ $key ], '//' ) ) ? 'http:' .  $matches[1][ $key ] :  $matches[1][ $key ];
				$host = parse_url( $url, PHP_URL_HOST );
				if ( ! in_array( $host, $whitelisted_script_domains ) ) {
					return;
				}
				$replacements[ $value ] = '[' . $shortcode_tag . ' src="' . esc_url( $url ) . '"][/' . $shortcode_tag . ']';
			}
			$content = str_replace( array_keys( $replacements ), array_values( $replacements ), $content );
		}
		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		if ( empty( $attrs['src'] ) ) {
			return '';
		}

		$host = parse_url( $attrs['src'], PHP_URL_HOST );
		if ( ! in_array( $host, self::get_whitelisted_script_domains() ) ) {
			return '';
		}

		return '<script src="' . esc_url( $attrs['src'] ) . '"></script>';
	}

}
