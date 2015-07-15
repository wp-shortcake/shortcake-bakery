<?php

namespace Shortcake_Bakery\Shortcodes;

class Iframe extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Iframe', 'shortcake-bakery' ),
			'listItemImage'  => 'dashicons-admin-site',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'src',
					'type'         => 'text',
					'description'  => esc_html__( 'Full URL to the iFrame source. Host must be whitelisted.', 'shortcake-bakery' ),
				),
			),
		);
	}


	/**
	*
	* Get the whitelisted iframe domains for the plugin
	* Whitelist domains using `add_filter` on this hook to return array of your site's whitelisted domaiins.
	*
	* @return array of whitelisted domains, e.g. 'assets.yourdomain.com'
	*/
	public static function get_whitelisted_iframe_domains() {
		return apply_filters( 'shortcake_bakery_whitelisted_iframe_domains', array() );
	}

	public static function reversal( $content ) {

		$whitelisted_iframe_domains = static::get_whitelisted_iframe_domains();
		$shortcode_tag = static::get_shortcode_tag();

		if ( preg_match_all( '!\<iframe\s[^>]*?src=[\"\']([^\"\']+)[\"\'][^>]*?\>\s{0,}\</iframe\>!i', $content, $matches ) ) {
			$replacements = array();
			foreach ( $matches[0] as $key => $value ) {
				$url = $matches[1][ $key ];
				$url = ( 0 === strpos( $url, '//' ) ) ? 'http:' .  $url :  $url;
				$host = parse_url( $url, PHP_URL_HOST );
				var_dump( $host );
				if ( ! in_array( $host, $whitelisted_iframe_domains ) ) {
					continue;
				}
				$replacements[ $value ] = '[' . $shortcode_tag . ' src="' . esc_url( $url ) . '"]';
			}
			$content = str_replace( array_keys( $replacements ), array_values( $replacements ), $content );
		}
		return $content;
	}


	public static function callback( $attrs, $content = '' ) {
		if ( empty( $attrs['src'] ) ) {
			return '';
		}

		$defaults = array(
			'height'      => 600,
			'width'       => '100%',
			);
		$attrs = array_merge( $defaults, $attrs );
		$whitelisted_iframe_domains = static::get_whitelisted_iframe_domains();

		$host = parse_url( $attrs['src'], PHP_URL_HOST );
		if ( ! in_array( $host, $whitelisted_iframe_domains ) ) {
			return '';
		}

		return sprintf(
			'<iframe src="%s" width="%s" height="%s" frameborder="0" scrolling="no" class="shortcake-bakery-responsive"></iframe>',
			esc_url( $attrs['src'] ),
			esc_attr( $attrs['width'] ),
			esc_attr( $attrs['height'] )
		);
	}

}
