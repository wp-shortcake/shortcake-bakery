<?php

namespace Shortcake_Bakery\Shortcodes;

class SoundCloud extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'SoundCloud', 'shortcake-bakery' ),
			'listItemImage'  => '<img src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/svg/icon-soundcloud.svg' ) . '" />',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => esc_html__( 'Full URL to the SoundCloud track.', 'shortcake-bakery' ),
				),
				array(
					'label'        => esc_html__( 'Type', 'shortcake-bakery' ),
					'attr'         => 'type',
					'type'         => 'select',
					'options'      => array(
						'simple'   => esc_html__( 'Simple', 'shortcake-bakery' ),
						'visual'   => esc_html__( 'Visual', 'shortcake-bakery' ),
						),
					),
				array(
					'label'        => esc_html__( 'Autoplay', 'shortcake-bakery' ),
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
				if ( 'w.soundcloud.com' !== self::parse_url( $iframe->attrs['src'], PHP_URL_HOST ) ) {
					continue;
				}
				// Track ID is exposed in the `url` parameter
				$query = self::parse_url( $iframe->attrs['src'], PHP_URL_QUERY );
				$query = str_replace( '&amp;', '&', $query );
				parse_str( $query, $args );
				if ( empty( $args['url'] ) ) {
					continue;
				}
				$type = ! empty( $args['visual'] ) && 'true' === $args['visual'] ? 'visual' : 'simple';
				$autoplay = ! empty( $args['auto_play'] ) && 'true' === $args['auto_play'] ? '1' : '0';
				$replacements[ $iframe->original ] = '[' . self::get_shortcode_tag() . ' url="' . esc_url_raw( $args['url'] ) . '" type="' . $type . '" autoplay="' . $autoplay . '"]';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}

		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		$host = self::parse_url( $attrs['url'], PHP_URL_HOST );
		if ( empty( $attrs['url'] ) || ! in_array( $host, array( 'soundcloud.com', 'api.soundcloud.com' ), true ) ) {
			return '';
		}

		// Use the track URL in the API request. It will be redirected to the proper track ID
		$embed_url = 'https://w.soundcloud.com/player/?url=' . rawurlencode( $attrs['url'] );
		$height = 166;
		if ( ! empty( $attrs['type'] ) && 'visual' === $attrs['type'] ) {
			$embed_url = add_query_arg( 'visual', 'true', $embed_url );
			$height = 450;
		}
		if ( ! empty( $attrs['autoplay'] ) ) {
			$embed_url = add_query_arg( 'auto_play', 'true', $embed_url );
		}
		return sprintf( '<iframe width="%s" height="%d" scrolling="no" frameborder="no" src="%s"></iframe>', esc_attr( '100%' ), esc_attr( $height ), esc_url( $embed_url ) );
	}

}
