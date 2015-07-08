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

		$content = preg_replace_callback( '!\<script\s[^>]*?src=[\"\']([^\"\']+)[\"\'][^>]*?\>\s{0,}\</script\>!i', function( $match ) use ( $whitelisted_script_domains ) {

			$url = ( 0 === strpos( $match[1], '//' ) ) ? 'http:' . $match[1] : $match[1];
			$host = parse_url( $url, PHP_URL_HOST );
			if ( ! in_array( $host, $whitelisted_script_domains ) ) {
				return $match[0];
			}
			$replacement = '[' . $shortcode_tag . ' src="' . esc_url( $match[1] ) . '"][/' . $shortcode_tag . ']';
			return $replacement;
		}, $content );

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
