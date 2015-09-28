<?php

namespace Shortcake_Bakery\Shortcodes;

class SoundCloud extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'SoundCloud', 'shortcake-bakery' ),
			'listItemImage'  => '<img width="100px" height="100px" style="padding-top:10px;" src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/svg/icon-soundcloud.svg' ) . '" />',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => esc_html__( 'Full URL to the SoundCloud track.', 'shortcake-bakery' ),
				),
			),
		);
	}

	public static function reversal( $content ) {

		if ( $iframes = self::parse_iframes( $content ) ) {
			$replacements = array();
			foreach ( $iframes as $iframe ) {
				if ( 'w.soundcloud.com' !== parse_url( $iframe->src_force_protocol, PHP_URL_HOST ) ) {
					continue;
				}
				// Track ID is exposed in the `url` parameter
				$query = parse_url( $iframe->src_force_protocol, PHP_URL_QUERY );
				parse_str( $query, $args );
				if ( empty( $args['url'] ) ) {
					continue;
				}
				$embed_url = str_replace( 'api.soundcloud.com', 'soundcloud.com', $args['url'] );
				$replacements[ $iframe->original ] = '[' . self::get_shortcode_tag() . ' url="' . esc_url_raw( $embed_url ) . '"]';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}

		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		if ( empty( $attrs['url'] ) || 'soundcloud.com' !== parse_url( $attrs['url'], PHP_URL_HOST ) ) {
			return '';
		}

		// Use the track URL in the API request. It will be redirected to the proper track ID
		$path = parse_url( $attrs['url'], PHP_URL_PATH );
		$path = str_replace( '/', '%2F', $path );
		$embed_url = 'https://w.soundcloud.com/player/?url=' . urlencode( 'https://soundcloud.com' . $path );
		return sprintf( '<iframe width="%s" height="166" scrolling="no" frameborder="no" src="%s"></iframe>', esc_attr( '100%' ), esc_url( $embed_url ) );
	}

}
