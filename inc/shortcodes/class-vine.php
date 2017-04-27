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
				array(
					'label'        => esc_html__( 'Type', 'shortcake-bakery' ),
					'attr'         => 'type',
					'type'         => 'select',
					'options'      => array(
						'simple'   => esc_html__( 'Simple', 'shortcake-bakery' ),
						'postcard' => esc_html__( 'Postcard', 'shortcake-bakery' ),
						),
					),
				array(
					'label'        => esc_html__( 'Autoplay audio', 'shortcake-bakery' ),
					'attr'         => 'autoplay',
					'type'         => 'select',
					'options'      => array(
						'0'        => esc_html__( 'No', 'shortcake-bakery' ),
						'1'        => esc_html__( 'Yes', 'shortcake-bakery' ),
						),
					),
			),
		);
	}

	public static function reversal( $content ) {
		$iframes = self::parse_iframes( $content );
		if ( $iframes ) {
			$replacements = array();
			foreach ( $iframes as $iframe ) {
				if ( 'vine.co' !== self::parse_url( $iframe->attrs['src'], PHP_URL_HOST ) ) {
					continue;
				}
				if ( preg_match( '#//vine.co/v/([^/]+)/embed/(simple|postcard)#', $iframe->attrs['src'], $matches ) ) {
					$embed_id = $matches[1];
					$type = $matches[2];
				} else {
					continue;
				}
				$replacement_url = 'https://vine.co/v/' . $embed_id;
				if ( false !== stripos( $iframe->attrs['src'], '?audio=1' ) ) {
					$autoplay = ' autoplay="1"';
				} else {
					$autoplay = '';
				}
				$replacements[ $iframe->original ] = '[' . self::get_shortcode_tag() . ' url="' . esc_url_raw( $replacement_url ) . '" type="' . $type . '"' . $autoplay . ']';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}

		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		if ( empty( $attrs['url'] ) || 'vine.co' !== self::parse_url( $attrs['url'], PHP_URL_HOST ) ) {
			return '';
		}

		if ( ! empty( $attrs['type'] ) && 'postcard' === $attrs['type'] ) {
			$type = 'postcard';
		} else {
			$type = 'simple';
		}

		// ID is always the second part to the path
		$path = self::parse_url( $attrs['url'], PHP_URL_PATH );
		$parts = explode( '/', trim( $path, '/' ) );
		$embed_id = $parts[1];
		$embed_url = 'https://vine.co/v/' . $embed_id . '/embed/' . $type;
		if ( ! empty( $attrs['autoplay'] ) ) {
			$embed_url = add_query_arg( 'audio', '1', $embed_url );
		}
		return sprintf( '<iframe class="shortcake-bakery-responsive" src="%s" width="600" height="600" frameborder="0"></iframe>', esc_url( $embed_url ) );
	}

}
