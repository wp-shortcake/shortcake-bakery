<?php

namespace Shortcake_Bakery\Shortcodes;

class Infogram extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Infogram', 'shortcake-bakery' ),
			'listItemImage'  => 'TK',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => esc_html__( 'URL to the Infogram', 'shortcake-bakery' ),
				),
			),
		);
	}

	public static function setup_actions() {
		add_action( 'init', 'Shortcake_Bakery\Shortcodes\Infogram::action_init_register_scripts' );
	}

	public static function action_init_register_scripts() {
		wp_register_script( 'infogram-api', 'http://e.infogr.am/js/embed.js' );
	}

	public static function reversal( $content ) {
		if ( preg_match_all( '#<script id="[^<]+" src="//e\.infogr\.am/js/embed\.js\?[^>]+" type="text/javascript"></script>?#', $content, $matches ) ) {
			$replacements = array();
			$shortcode_tag = self::get_shortcode_tag();
			foreach ( $matches[0] as $key => $value ) {
				$replacements[ $value ] = '[' . $shortcode_tag . ' url="https://infogram.com/' . esc_url( $matches[1][ $key ] ) . '"]';
			}
			$content = str_replace( array_keys( $replacements ), array_values( $replacements ), $content );
		}
		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		if ( empty( $attrs['url'] ) ) {
			return '';
		}
		$id = preg_replace('((http|https)\:\/\/infogr\.am\/)', '', $attrs['url'] );
		wp_enqueue_script( 'infogram-api' );
		$out = '<div id="infogram_0_' . $id  . '"></div>';
		return $out;
	}

}
