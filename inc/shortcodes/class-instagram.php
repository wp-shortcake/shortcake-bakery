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
				array(
					'label'        => esc_html__( 'Hide Caption', 'shortcake-bakery' ),
					'attr'         => 'hidecaption',
					'type'         => 'checkbox',
					'description'  => esc_html__( 'By default, the Instagram embed will include the caption. Check this box to hide the caption.', 'shortcake-bakery' ),
				),
			),
		);
	}

	public static function reversal( $content ) {

		if ( false === stripos( $content, '<script' ) && false === stripos( $content, '<iframe' ) && false === stripos( $content, 'class="instagram-media' ) ) {
			return $content;
		}

		$needle = '#<blockquote class="instagram-media.+<a href="(https://instagram\.com/p/[^/]+/)"[^>]+>.+(?=</blockquote>)</blockquote>\n?(<script[^>]+src="//platform\.instagram\.com/[^>]+></script>)?#';
		if ( preg_match_all( $needle, $content, $matches ) ) {
			$replacements = array();
			$shortcode_tag = self::get_shortcode_tag();
			foreach ( $matches[0] as $key => $value ) {
				$replacements[ $value ] = '[' . $shortcode_tag . ' url="' . esc_url_raw( $matches[1][ $key ] ) . '"]';
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
		global $content_width;

		if ( empty( $attrs['url'] ) ) {
			return '';
		}

		$needle = '#(https?:)?//instagr(\.am|am\.com)/p/([^/]+)#i';
		if ( preg_match( $needle, $attrs['url'], $matches ) ) {
			$photo_id = $matches[3];
		} else {
			return '';
		}

		$passed_url = $attrs['url'];

		$max_width = 698;
		$min_width = 320;

		$defaults = array(
			'width'       => isset( $content_width ) ? $content_width : $max_width,
			'hidecaption' => false,
			);
		$attrs = array_merge( $defaults, $attrs );

		$attrs['width'] = absint( $attrs['width'] );
		if ( $attrs['width'] > $max_width || $min_width > $attrs['width'] ) {
			$attrs['width'] = $max_width;
		}

		$url_args = array(
			'url'      => $attrs['url'],
			'maxwidth' => $attrs['width'],
		);

		if ( $attrs['hidecaption'] ) {
			$url_args['hidecaption'] = 'true';
		}

		$url = esc_url_raw( add_query_arg( $url_args, 'https://api.instagram.com/oembed/' ) );
		$instagram_response = wp_remote_get( $url, array( 'redirection' => 0 ) );
		if ( is_wp_error( $instagram_response ) || 200 != $instagram_response['response']['code'] || empty( $instagram_response['body'] ) ) {
			return '';
		}
		$response_body = json_decode( $instagram_response['body'] );

		if ( ! empty( $response_body->html ) ) {
			wp_enqueue_script( 'shortcake-bakery-instagram', '//platform.instagram.com/en_US/embeds.js', array( 'jquery' ), false, true );
			// there's a script in the response, which we strip on purpose since it's added by this ^ script
			$ig_embed = preg_replace( '@<(script)[^>]*?>.*?</\\1>@si', '', $response_body->html );
			return $ig_embed;
		}
		return '';
	}

}
