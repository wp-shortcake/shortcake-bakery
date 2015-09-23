<?php

namespace Shortcake_Bakery\Shortcodes;

class Vimeo extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Vimeo', 'shortcake-bakery' ),
			'listItemImage' => '<img width="100px" height="100px" style="padding-top:10px;" src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/svg/icon-vimeo.svg' ) . '" />',
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

		$needle = '#<iframe[^>]+src="(https?:)?//player\.vimeo\.com/video/([^/"?]+)[^"]{0,}"[^>]+></iframe>#';
		if ( preg_match_all( $needle, $content, $matches ) ) {
			$replacements = array();
			$shortcode_tag = self::get_shortcode_tag();
			foreach ( $matches[0] as $key => $value ) {
				$replacement_url = 'https://vimeo.com/' . $matches[2][ $key ];
				$replacements[ $value ] = '[' . $shortcode_tag . ' url="' . esc_url_raw( $replacement_url ) . '"]';
			}
			$content = str_replace( array_keys( $replacements ), array_values( $replacements ), $content );
		}

		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		$valid_hosts = array( 'www.vimeo.com', 'vimeo.com' );
		$host = parse_url( $attrs['url'], PHP_URL_HOST );
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
