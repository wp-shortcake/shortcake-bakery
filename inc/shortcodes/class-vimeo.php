<?php

namespace Shortcake_Bakery\Shortcodes;

class Vimeo extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Vimeo', 'shortcake-bakery' ),
			'listItemImage'  => '<img src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/svg/icon-vimeo.svg' ) . '" />',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => esc_html__( 'Full Vimeo URL', 'shortcake-bakery' ),
				),
			),
		);
	}

	public static function reversal( $content ) {

		if ( $iframes = self::parse_iframes( $content ) ) {
			$replacements = array();
			foreach ( $iframes as $iframe ) {
				if ( 'player.vimeo.com' !== self::parse_url( $iframe->attrs['src'], PHP_URL_HOST ) ) {
					continue;
				}
				// Embed ID is the second part of the URL
				$parts = explode( '/', trim( self::parse_url( $iframe->attrs['src'], PHP_URL_PATH ), '/' ) );
				if ( empty( $parts[1] ) ) {
					continue;
				}
				$embed_id = $parts[1];
				$replacements[ $iframe->original ] = '[' . self::get_shortcode_tag() . ' url="' . esc_url_raw( 'https://vimeo.com/' . $embed_id ) . '"]';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}

		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		$valid_hosts = array( 'www.vimeo.com', 'vimeo.com' );
		$host = self::parse_url( $attrs['url'], PHP_URL_HOST );
		if ( empty( $attrs['url'] ) || ! in_array( $host, $valid_hosts ) ) {
			return '';
		}

		// Video ID is always the first part of the path
		$path = parse_url( $attrs['url'], PHP_URL_PATH );
		$parts = explode( '/', trim( $path, '/' ) );
		$video_id = $parts[0];
		$embed_url = 'https://player.vimeo.com/video/' . $video_id;
		return sprintf( '<iframe class="shortcake-bakery-responsive" width="500" height="281" src="%s" frameborder="0" allowfullscreen></iframe>', esc_url( $embed_url ) );
	}

}
