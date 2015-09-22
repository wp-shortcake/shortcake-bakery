<?php

namespace Shortcake_Bakery\Shortcodes;

class Vine extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Vine', 'shortcake-bakery' ),
			'listItemImage' => '<img width="100px" height="100px" style="padding-top:10px;" src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/svg/icon-vine.svg' ) . '" />',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => esc_html__( 'Full URL to the Vine', 'shortcake-bakery' ),
				),
			),
		);
	}

	public static function reversal( $content ) {

		$needle = '#<iframe[^>]+src="https?://vine\.co/v/([\w]+)(/[^"]+)?"[^>]+></iframe>#';
		if ( preg_match_all( $needle, $content, $matches ) ) {
			$replacements = array();
			$shortcode_tag = self::get_shortcode_tag();
			foreach ( $matches[0] as $key => $value ) {
				$replacement_url = 'https://vine.co/v/' . $matches[1][ $key ];
				$replacements[ $value ] = '[' . $shortcode_tag . ' url="' . esc_url( $replacement_url ) . '"]';
			}
			$content = str_replace( array_keys( $replacements ), array_values( $replacements ), $content );
		}

		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		if ( empty( $attrs['url'] ) || 'vine.co' !== parse_url( $attrs['url'], PHP_URL_HOST ) ) {
			return '';
		}

		// ID is always the second part to the path
		$path = parse_url( $attrs['url'], PHP_URL_PATH );
		$parts = explode( '/', trim( $path, '/' ) );
		$embed_id = $parts[1];
		$embed_url = 'https://vine.co/v/' . $embed_id . '/embed/simple';
		return sprintf( '<iframe class="shortcake-bakery-responsive" src="%s" width="600" height="600" frameborder="0"></iframe>', esc_url( $embed_url ) );
	}

}
