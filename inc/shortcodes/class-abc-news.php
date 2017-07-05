<?php

namespace Shortcake_Bakery\Shortcodes;

class ABC_News extends Shortcode {

	private static $valid_hosts = array( 'abcnews.go.com' );

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'ABC News', 'shortcake-bakery' ),
			'listItemImage'  => '<img src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/svg/icon-abc-news.svg' ) . '" />',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'Type', 'shortcake-bakery' ),
					'attr'         => 'type',
					'type'         => 'select',
					'options'      => array(
						'iframe' => esc_html__( 'Iframe', 'shortcake-bakery' ),
						'script' => esc_html__( 'Script tag', 'shortcake-bakery' ),
					),
					'description'  => esc_html__( 'URL to a ABC News video', 'shortcake-bakery' ),
				),
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => esc_html__( 'URL to a ABC News video', 'shortcake-bakery' ),
				),
			),
		);
	}

	public static function reversal( $content ) {
		$iframes = self::parse_iframes( $content );
		if ( $iframes ) {
			$replacements = array();
			foreach ( $iframes as $iframe ) {
				if ( ! in_array( self::parse_url( $iframe->attrs['src'], PHP_URL_HOST ), self::$valid_hosts, true ) ) {
					continue;
				}
				$replacements[ $iframe->original ] = '[' . self::get_shortcode_tag() . ' type="iframe" url="' . esc_url_raw( $iframe->attrs['src'] ) . '"]';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}

		if ( $scripts = self::parse_scripts( $content ) ) {
			$replacements = array();
			foreach ( $scripts as $script ) {
				if ( ! in_array( self::parse_url( $script->attrs['src'], PHP_URL_HOST ), self::$valid_hosts ) ) {
					continue;
				}
				$replacements[ $script->original ] = '[' . self::get_shortcode_tag() . ' type="script" url="' . esc_url_raw( $script->attrs['src'] ) . '"]';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}

		return $content;
	}

	public static function callback( $attrs, $content = '' ) {
		$attrs = wp_parse_args( $attrs, array( 'type' => 'iframe' ) );

		if ( empty( $attrs['url'] ) || ! in_array( self::parse_url( $attrs['url'], PHP_URL_HOST ), self::$valid_hosts, true ) ) {
			return '';
		}

		switch ( $attrs['type'] ) {
			case 'iframe':
				return sprintf( '<iframe class="shortcake-bakery-responsive" width="640" height="480" src="%s" frameborder="0"></iframe>', esc_url( $attrs['url'] ) );
			case 'script':
				return sprintf( '<script src="%s"></script>', esc_url( $attrs['url'] ) );
		}
	}

}
