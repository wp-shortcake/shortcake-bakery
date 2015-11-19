<?php

namespace Shortcake_Bakery\Shortcodes;

class GoogleDocs extends Shortcode {

	private static $valid_hosts = array( 'docs.google.com', 'www.google.com' );

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Google Docs', 'shortcake-bakery' ),
			'listItemImage'  => '<img src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/svg/icon-googledocs.svg' ) . '" />',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => esc_html__( 'Full document URL', 'shortcake-bakery' ),
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
				if ( preg_match( '#(docs|www)\.google\.com/(\w*)/d/(.*)/(\w*)\?([^/?]+)$#', $iframe->attrs['src'], $matches ) ) {
					list( $url, $subdomain, $doc_type, $embed_id, $view_name, $query_string ) = $matches;
				} else {
					continue;
				}

				switch ( $doc_type ) {
					case 'document':
						$replacement_url = 'https://docs.google.com/document/d/' . $embed_id;
						$replacements[ $iframe->original ] = '[' . self::get_shortcode_tag() . ' type="document" url="' . esc_url_raw( $replacement_url ) . '"]';
						break;
					default:
						error_log( print_r( $matches, true ) );
				}
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}

		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		$host = self::parse_url( $attrs['url'], PHP_URL_HOST );
		if ( empty( $attrs['type'] ) || empty( $attrs['url'] ) || ! in_array( $host, self::$valid_hosts ) ) {
			return '';
		}

		switch ( $attrs['type'] ) {
			case 'document':
				return sprintf( '<iframe src="%s/pub?embedded=true"></iframe>', esc_url_raw( $attrs['url'] ) );
		}

	}

}
