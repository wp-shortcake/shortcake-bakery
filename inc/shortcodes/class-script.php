<?php

namespace Shortcake_Bakery\Shortcodes;

class Script extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Script', 'shortcake-bakery' ),
			'listItemImage'  => 'dashicons-media-code',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'src',
					'type'         => 'text',
					'description'  => esc_html__( 'Full URL to the script file. Host must be whitelisted.', 'shortcake-bakery' ),
				),
			),
		);
	}


	/**
	*
	* Get the whitelisted script domains for the plugin
	* Whitelist domains using `add_filter` on this hook to return array of your site's whitelisted domaiins.
	*
	* @return array of whitelisted domains, e.g. 'ajax.googleapis.com'
	*/
	public static function get_whitelisted_script_domains() {
		return apply_filters( 'shortcake_bakery_whitelisted_script_domains', array() );
	}

	public static function reversal( $content ) {

		$whitelisted_script_domains = static::get_whitelisted_script_domains();
		$shortcode_tag = static::get_shortcode_tag();

		if ( preg_match_all( '!\<script\s[^>]*?src=[\"\']([^\"\']+)[\"\'][^>]*?\>\s{0,}\</script\>!i', $content, $matches ) ) {
			$replacements = array();
			foreach ( $matches[0] as $key => $value ) {
				$url = $matches[1][ $key ];
				$url = ( 0 === strpos( $url, '//' ) ) ? 'http:' .  $url :  $url;
				$host = parse_url( $url, PHP_URL_HOST );
				if ( ! in_array( $host, $whitelisted_script_domains ) ) {
					continue;
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

		$url_for_parse = ( 0 === strpos( $attrs['src'], '//' ) ) ? 'http:' . $attrs['src'] :  $attrs['src'];
		$host = parse_url( $url_for_parse, PHP_URL_HOST );

		if ( ! in_array( $host, static::get_whitelisted_script_domains() ) ) {
			if ( current_user_can( 'edit_posts' ) ) {
				return '<div class="shortcake-bakery-error"><p>' . sprintf( esc_html__( 'Invalid hostname in URL: %s', 'shortcake-bakery' ), esc_url( $attrs['src'] ) ) . '</p></div>';
			} else {
				return '';
			}
		}

		return '<script src="' . esc_url( $attrs['src'] ) . '"></script>';
	}

}
