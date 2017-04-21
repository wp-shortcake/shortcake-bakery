<?php

namespace Shortcake_Bakery\Shortcodes;

class Instagram extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Instagram', 'shortcake-bakery' ),
			'listItemImage'  => '<img src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/svg/icon-instagram.svg' ) . '" />',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => esc_html__( 'URL to an Instagram', 'shortcake-bakery' ),
				),
			),
		);
	}

	public static function reversal( $content ) {

		if ( false === stripos( $content, '<script' ) && false === stripos( $content, '<iframe' ) && false === stripos( $content, 'class="instagram-media' ) ) {
			return $content;
		}

		$needle = '#<blockquote class="instagram-media.+<a href="(https://(www\.)?instagram\.com/p/[^/]+/)"[^>]+>.+(?=</blockquote>)</blockquote>\n?(<script[^>]+src="//platform\.instagram\.com/[^>]+></script>)?#';
		if ( preg_match_all( $needle, $content, $matches ) ) {
			$replacements = array();
			$shortcode_tag = self::get_shortcode_tag();
			foreach ( $matches[0] as $key => $value ) {
				$replacements[ $value ] = '[' . $shortcode_tag . ' url="' . esc_url_raw( $matches[1][ $key ] ) . '"]';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}
		$iframes = self::parse_iframes( $content );
		if ( $iframes ) {
			$replacements = array();
			foreach ( $iframes as $iframe ) {
				if ( 'instagram.com' !== self::parse_url( $iframe->attrs['src'], PHP_URL_HOST ) ) {
					continue;
				}
				if ( preg_match( '#//instagram\.com/p/([^/]+)/embed/?#', $iframe->attrs['src'], $matches ) ) {
					$embed_id = $matches[1];
				} else {
					continue;
				}
				$replacements[ $iframe->original ] = '[' . self::get_shortcode_tag() . ' url="' . esc_url_raw( 'https://instagram.com/p/' . $embed_id . '/' ) . '"]';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}

		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		if ( empty( $attrs['url'] ) ) {
			return '';
		}

		$needle = '#(https?:)?//(www\.)?instagr(\.am|am\.com)/p/([^/]+)#i';
		if ( preg_match( $needle, $attrs['url'], $matches ) ) {
			$photo_id = $matches[4];
		} else {
			return '';
		}

		return sprintf( '<iframe class="shortcake-bakery-responsive" src="%s" width="612" height="710" frameborder="0" scrolling="no"></iframe>',
			esc_url( sprintf( 'https://instagram.com/p/%s/embed/', $photo_id ) )
		);
	}

}
