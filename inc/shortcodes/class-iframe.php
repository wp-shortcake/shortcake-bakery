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
				array(
					'label'        => esc_html__( 'Height', 'shortcake-bakery' ),
					'attr'         => 'height',
					'type'         => 'number',
					'description'  => esc_html__( 'Height of the iframe. Defaults to 600.', 'shortcake-bakery' ),
				),
				array(
					'label'        => esc_html__( 'Width', 'shortcake-bakery' ),
					'attr'         => 'width',
					'type'         => 'number',
					'description'  => esc_html__( 'Pixel width of the iframe. Defaults to 640.', 'shortcake-bakery' ),
				),
				array(
					'label'        => esc_html__( 'Disable Responsiveness', 'shortcake-bakery' ),
					'attr'         => 'disableresponsiveness',
					'type'         => 'checkbox',
					'description'  => esc_html__( 'By default, height/width ratio of iframe will be maintained regardless of container width. Check this to keep constant height/width.', 'shortcake-bakery' ),
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

	public static function callback( $attrs, $content = '' ) {
		if ( empty( $attrs['src'] ) ) {
			return '';
		}

		$defaults = array(
			'height'                  => 600,
			'width'                   => 670,
			'disableresponsiveness'   => false,
			);
		$attrs = array_merge( $defaults, $attrs );
		$whitelisted_iframe_domains = static::get_whitelisted_iframe_domains();

		$url_for_parse = ( 0 === strpos( $attrs['src'], '//' ) ) ? 'http:' . $attrs['src'] :  $attrs['src'];
		$host = parse_url( $url_for_parse, PHP_URL_HOST );
		if ( ! in_array( $host, $whitelisted_iframe_domains ) ) {
			return '';
		}

		if ( $attrs['disableresponsiveness'] ) {
			$class = '';
		} else {
			$class = 'shortcake-bakery-responsive';
		}

		return sprintf(
			'<iframe src="%s" width="%s" height="%s" data-true-width="%s" data-true-height="%s" frameborder="0" scrolling="no" class="%s"></iframe>',
			esc_url( $attrs['src'] ),
			esc_attr( $attrs['width'] ),
			esc_attr( $attrs['height'] ),
			esc_attr( $attrs['width'] ),
			esc_attr( $attrs['height'] ),
			esc_attr( $class )
		);
	}

}
