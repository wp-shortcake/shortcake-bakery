<?php

namespace Shortcake_Bakery\Shortcodes;

class Vine extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Vine', 'shortcake-bakery' ),
			'listItemImage'  => '<img src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/svg/icon-vine.svg' ) . '" />',
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

		if ( $iframes = self::parse_iframes( $content ) ) {
			$replacements = array();
			foreach ( $iframes as $iframe ) {
				if ( 'vine.co' !== parse_url( $iframe->src_force_protocol, PHP_URL_HOST ) ) {
					continue;
				}
				if ( preg_match( '#//vine.co/v/([^/]+)#', $iframe->src_force_protocol, $matches ) ) {
					$embed_id = $matches[1];
				} else {
					continue;
				}
				$replacement_url = 'https://vine.co/v/' . $embed_id;
				$replacements[ $iframe->original ] = '[' .  self::get_shortcode_tag() . ' url="' . esc_url_raw( $replacement_url ) . '"]';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
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
