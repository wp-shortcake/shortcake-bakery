<?php

namespace Shortcake_Bakery\Shortcodes;

class YouTube extends Shortcode {

	private static $valid_hosts = array( 'www.youtube.com', 'youtube.com' );

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'YouTube', 'shortcake-bakery' ),
			'listItemImage'  => '<img src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/svg/icon-youtube.svg' ) . '" />',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => esc_html__( 'Full YouTube URL', 'shortcake-bakery' ),
				),
			),
		);
	}

	public static function reversal( $content ) {

		if ( $iframes = self::parse_iframes( $content ) ) {
			$replacements = array();
			foreach ( $iframes as $iframe ) {
				if ( ! in_array( self::parse_url( $iframe->attrs['src'], PHP_URL_HOST ), self::$valid_hosts ) ) {
					continue;
				}
				if ( preg_match( '#youtube\.com/embed/([^/?]+)#', $iframe->attrs['src'], $matches ) ) {
					$embed_id = $matches[1];
				} else {
					continue;
				}
				$replacement_url = 'https://www.youtube.com/watch?v=' . $embed_id;
				$replacements[ $iframe->original ] = '[' . self::get_shortcode_tag() . ' url="' . esc_url_raw( $replacement_url ) . '"]';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}

		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		$host = self::parse_url( $attrs['url'], PHP_URL_HOST );
		if ( empty( $attrs['url'] ) || ! in_array( $host, self::$valid_hosts ) ) {
			return '';
		}

		$list_id = '';

		// https://www.youtube.com/watch?v=hDlpVFDmXrc
		if ( in_array( $host, self::$valid_hosts ) ) {
			$query = self::parse_url( str_replace( array( '&amp;', '&#038;' ), '&', $attrs['url'] ), PHP_URL_QUERY );
			parse_str( $query, $args );
			if ( empty( $args['v'] ) ) {
				return '';
			}
			$embed_id = $args['v'];
			if ( ! empty( $args['list'] ) ) {
				$list_id = $args['list'];
			}
		}

		// ID is always the second part to the path
		$embed_url = 'https://youtube.com/embed/' . $embed_id;
		if ( ! empty( $list_id ) ) {
			$embed_url = add_query_arg( 'list', $list_id, $embed_url );
		}
		$embed_url = apply_filters( 'shortcake_bakery_youtube_embed_url', $embed_url, $attrs );
		return sprintf( '<iframe class="shortcake-bakery-responsive" width="640" height="360" src="%s" frameborder="0"></iframe>', esc_url( $embed_url ) );
	}

}
