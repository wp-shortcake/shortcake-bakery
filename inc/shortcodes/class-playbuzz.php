<?php

namespace Shortcake_Bakery\Shortcodes;

class Playbuzz extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label' 		=> 'Playbuzz',
			'listItemImage' => '<img src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/svg/icon-playbuzz.svg' ) . '" />',
			'attrs' 		=> array(
				array(
					'label'        => esc_html__( 'Playbuzz quiz URL', 'shortcake-bakery' ),
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => esc_html__( 'The full URL to the Playbuzz quiz (e.g. https://www.playbuzz.com/Fusion/5-mind-blowing-facts-about-cloning-from-jurassic-park-youll-never-believe-actually-exist-in-real)', 'shortcake-bakery' ),
				),
				array(
					'label'        => esc_html__( 'Display recommendations for more games', 'shortcake-bakery' ),
					'attr'         => 'recommend',
					'type'         => 'checkbox',
					),
				array(
					'label'        => esc_html__( 'Display share buttons', 'shortcake-bakery' ),
					'attr'         => 'shares',
					'type'         => 'checkbox',
					),
				array(
					'label'        => esc_html__( 'Use Facebook comments', 'shortcake-bakery' ),
					'attr'         => 'comments',
					'type'         => 'checkbox',
					),
			),
		);
	}

	public static function reversal( $content ) {

		if ( false === stripos( $content, '<script' ) ) {
			return $content;
		}

		if ( preg_match_all( '#<script([^>]+)src=["\']//cdn\.playbuzz\.com([^>]+)></script>\r?\n?<div([^>]+)class=["\']pb_feed["\']([^>]+)></div>#', $content, $matches ) ) {
			$replacements = array();
			$shortcode_tag = self::get_shortcode_tag();
			foreach ( $matches[0] as $key => $value ) {

				$div_parts = explode( ' ', $matches[4][ $key ] );
				$shortcode_attrs = array();
				foreach ( $div_parts as $div_part ) {
					$attr_parts = explode( '=', $div_part );
					$key = array_shift( $attr_parts );
					$val = ! empty( $attr_parts[0] ) ? trim( $attr_parts[0], '\'"' ) : false;
					if ( empty( $val ) ) {
						continue;
					}

					switch ( $key ) {
						case 'data-comments':
						case 'data-shares':
						case 'data-recommend':
							$shortcode_attrs[ str_replace( 'data-', '', $key ) ] = 'true' === $val ? 'true' : 'false';
							break;

						case 'data-game':
							$shortcode_attrs['url'] = esc_url( 'https://www.playbuzz.com' . $val );
							break;

					}
				}

				// Uh oh, no attributes found
				if ( empty( $shortcode_attrs ) ) {
					continue;
				}

				$attrs_string = '';
				foreach ( $shortcode_attrs as $key => $val ) {
					$attrs_string .= $key . '="' . $val . '" ';
				}
				$attrs_string = rtrim( $attrs_string );
				$replacements[ $value ] = '[' . $shortcode_tag . ' ' . $attrs_string . ']';
			} // End foreach().

			$content = self::make_replacements_to_content( $content, $replacements );
		} // End if().

		return $content;
	}

	public static function callback( $attrs, $content = '' ) {
		if ( empty( $attrs['url'] ) || ! preg_match( '#^https?://(www\.)?playbuzz\.com#', $attrs['url'] ) ) {
			if ( current_user_can( 'edit_posts' ) ) {
				return esc_html__( 'Invalid Playbuzz URL provided.', 'shortcake-bakery' );
			} else {
				return '';
			}
		}

		$playbuzz_args = array(
			'game'     => self::parse_url( $attrs['url'], PHP_URL_PATH ),
			'height'   => 'auto',
			);

		foreach ( array( 'recommend', 'comments', 'shares' ) as $key ) {
			$playbuzz_args[ $key ] = ! empty( $attrs[ $key ] ) && 'true' === $attrs[ $key ] ? 'true' : 'false';
		}

		if ( is_admin() ) {
			$playbuzz_args['feed'] = 'true';
			$embed_url = add_query_arg( $playbuzz_args, $attrs['url'] );
			return '<iframe width="100%" height="300px" src="' . esc_url( $embed_url ) . '" frameborder="0"></iframe>';
		} else {
			wp_enqueue_script( 'playbuzz-widget', '//cdn.playbuzz.com/widget/feed.js' );
			return '<div class="pb_feed" data-height="' . esc_attr( $playbuzz_args['height'] ) . '" data-game="' . esc_attr( $playbuzz_args['game'] ) . '" data-tags="All" data-recommend="' . esc_attr( $playbuzz_args['recommend'] ) . '" data-margin-top="0" data-game-info="true" data-comments="' . esc_attr( $playbuzz_args['comments'] ) . '" data-shares="' . esc_attr( $playbuzz_args['shares'] ) . '" data-key="Default"></div>';
		}

	}

}
