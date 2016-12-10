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

		// Expanation of regex:
		// [1] vertical padding value on image div (should be doubled because it's applied to top and bottom
		// [2] full match of embed URL
		// [3] www or not www
		// [4] script tag, if provided
		$needle = '#<blockquote class="instagram-media.+padding:([0-9.]+)% 0;.+<a href="(https://(www\.)?instagram\.com/p/[^/]+/)"[^>]+>.+(?=</blockquote>)</blockquote>\n?(<script[^>]+src="//platform\.instagram\.com/[^>]+></script>)?#';
		if ( preg_match_all( $needle, $content, $matches ) ) {
			$replacements = array();
			$shortcode_tag = self::get_shortcode_tag();
			foreach ( $matches[0] as $key => $value ) {

				$ratio = round( floatval( $matches[1][ $key ] ) * 2, 4 );
				$replacements[ $value ] = '[' . $shortcode_tag . ' url="' . esc_url_raw( $matches[2][ $key ] ) . '"' .
					( ( 100 != $ratio ) ? ' ratio="' . esc_attr( $ratio ) . '"' : '' ) .
					']';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}

		if ( $iframes = self::parse_iframes( $content ) ) {
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

		$image_ratio = ( ! empty( $attrs['ratio'] ) ) ? floatval( $attrs['ratio'] ) : 100.0;

		// Instagram embeds are a 48px header and footer, plus the responsive image section
		// (the embed has a 8px negative bottom margin, so we account for that here as well.)
		$height = 48 + round( 612 * ( $image_ratio / 100 ) ) + 48;

		return sprintf( '<iframe data-height-adjust="96" class="shortcake-bakery-responsive" src="%s" width="612" height="%s" frameborder="0" scrolling="no"></iframe>',
			esc_url( sprintf( 'https://instagram.com/p/%s/embed/', $photo_id ) ),
			esc_attr( $height )
		);
	}

}
