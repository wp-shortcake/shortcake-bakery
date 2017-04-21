<?php

namespace Shortcake_Bakery\Shortcodes;

class Giphy extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Giphy', 'shortcake-bakery' ),
			'listItemImage'  => '<img src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/png/icon-giphy.png' ) . '" />',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => esc_html__( 'Full URL to the Giphy', 'shortcake-bakery' ),
				),
				array(
					'label'        => esc_html__( 'Height', 'shortcake-bakery' ),
					'attr'         => 'height',
					'type'         => 'number',
					'description'  => esc_html__( 'Pixel height of the iframe. Defaults to 500.', 'shortcake-bakery' ),
				),
				array(
					'label'        => esc_html__( 'Width', 'shortcake-bakery' ),
					'attr'         => 'width',
					'type'         => 'number',
					'description'  => esc_html__( 'Pixel width of the iframe. Defaults to 350.', 'shortcake-bakery' ),
				),
				array(
					'label'        => esc_html__( 'Disable Responsiveness', 'shortcake-bakery' ),
					'attr'         => 'disableresponsiveness',
					'type'         => 'checkbox',
					'description'  => esc_html__( 'By default, height/width ratio of Giphy embed will be maintained regardless of container width. Check this to keep constant height/width.', 'shortcake-bakery' ),
				),
			),
		);
	}

	public static function reversal( $content ) {
		$iframes = self::parse_iframes( $content );
		if ( $iframes ) {
			$replacements = array();
			foreach ( $iframes as $iframe ) {
				if ( 'giphy.com' !== self::parse_url( $iframe->attrs['src'], PHP_URL_HOST ) ) {
					continue;
				}
				// Embed ID is the last part of the URL
				$parts = explode( '/', trim( self::parse_url( $iframe->attrs['src'], PHP_URL_PATH ), '/' ) );
				$embed_id = array_pop( $parts );
				$replacement_key = $iframe->original;
				// GIPHY embeds can append <p><a href="http://giphy.com/gifs/jtvedit-jtv-rogelio-de-la-vega-ihfrhIgdkQ83C">via GIPHY</a></p>
				if ( false !== strpos( $iframe->after, '>via GIPHY</a></p>' ) ) {
					$replacement_key .= $iframe->after;
				}
				$width_and_or_height = '';
				foreach ( array( 'width', 'height' ) as $attr ) {
					if ( isset( $iframe->attrs[ $attr ] ) ) {
						$width_and_or_height .= ' ' . sanitize_key( $attr ) . '="' . (int) $iframe->attrs[ $attr ] . '"';
					}
				}
				$replacement = '[' . self::get_shortcode_tag() . ' url="' . esc_url_raw( 'http://giphy.com/gifs/' . $embed_id ) . '"' . $width_and_or_height . ']';
				$replacements[ $replacement_key ] = $replacement;
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}

		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		if ( empty( $attrs['url'] ) || 'giphy.com' !== self::parse_url( $attrs['url'], PHP_URL_HOST ) ) {
			return '';
		}

		$defaults = array(
			'width'                  => 500,
			'height'                 => 350,
			'disableresponsiveness'  => false,
			);
		$attrs = array_merge( $defaults, $attrs );

		// ID is always the last part of the URL
		$parts = preg_split( '#[-/]#', $attrs['url'], null, PREG_SPLIT_NO_EMPTY );
		$embed_id = array_pop( $parts );
		$embed_url = '//giphy.com/embed/' . $embed_id;
		$classes = 'giphy-embed';
		if ( empty( $attrs['disableresponsiveness'] ) ) {
			$classes .= ' shortcake-bakery-responsive';
		}
		return sprintf( '<iframe src="%s" frameBorder="0" width="%d" height="%d" class="%s" allowFullScreen></iframe>',
			esc_url( $embed_url ),
			(int) $attrs['width'],
			(int) $attrs['height'],
			esc_attr( $classes )
		);
	}

}
