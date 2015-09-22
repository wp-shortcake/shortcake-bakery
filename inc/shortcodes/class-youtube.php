<?php

namespace Shortcake_Bakery\Shortcodes;

class YouTube extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'YouTube', 'shortcake-bakery' ),
			'listItemImage' => '<img width="100px" height="100px" style="padding-top:10px;" src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/svg/icon-youtube.svg' ) . '" />',
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

		$needle = '#<iframe[^>]+src="https://www.youtube.com/embed/([^/"]+)"[^>]+></iframe>#';
		if ( preg_match_all( $needle, $content, $matches ) ) {
			$replacements = array();
			$shortcode_tag = self::get_shortcode_tag();
			foreach ( $matches[0] as $key => $value ) {
				$replacement_url = 'https://www.youtube.com/watch?v=' . $matches[1][ $key ];
				$replacements[ $value ] = '[' . $shortcode_tag . ' url="' . esc_url_raw( $replacement_url ) . '"]';
			}
			$content = str_replace( array_keys( $replacements ), array_values( $replacements ), $content );
		}

		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		$valid_hosts = array( 'www.youtube.com', 'youtube.com' );
		$host = parse_url( $attrs['url'], PHP_URL_HOST );
		if ( empty( $attrs['url'] ) || ! in_array( $host, $valid_hosts ) ) {
			return '';
		}

		// https://www.youtube.com/watch?v=hDlpVFDmXrc
		if ( in_array( $host, array( 'youtube.com', 'www.youtube.com' ) ) ) {
			parse_str( parse_url( $attrs['url'], PHP_URL_QUERY ), $args );
			if ( empty( $args['v'] ) ) {
				return '';
			}
			$embed_id = $args['v'];
		}

		// ID is always the second part to the path
		$embed_url = 'https://youtube.com/embed/' . $embed_id;
		return sprintf( '<iframe class="shortcake-bakery-responsive" width="640" height="360" src="%s" frameborder="0"></iframe>', esc_url( $embed_url ) );
	}

}
