<?php

namespace Shortcake_Bakery\Shortcodes;

class Facebook extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Facebook', 'shortcake-bakery' ),
			'listItemImage'  => 'dashicons-facebook',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => esc_html__( 'Full URL to the Facebook Post or Video', 'shortcake-bakery' ),
				),
			),
		);
	}

	public static function setup_actions() {
		add_action( 'init', 'Shortcake_Bakery\Shortcodes\Facebook::action_init_register_scripts' );
	}

	public static function action_init_register_scripts() {
		wp_register_script( 'facebook-api', '//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0' );
	}

	public static function reversal( $content ) {
		if ( preg_match_all( '#<div id="fb-root"></div><script>[^<]+</script><div[^>]+href=[\'\"]([^\'\"]+)[\'\"].+</div>(</div>)?#', $content, $matches ) ) {
			$replacements = array();
			$shortcode_tag = self::get_shortcode_tag();
			foreach ( $matches[0] as $key => $value ) {
				$replacements[ $value ] = '[' . $shortcode_tag . ' url="' . esc_url( $matches[1][ $key ] ) . '"]';
			}
			$content = str_replace( array_keys( $replacements ), array_values( $replacements ), $content );
		}
		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		if ( empty( $attrs['url'] ) ) {
			return '';
		}

		if ( ! preg_match( '#https?://(www)?\.facebook\.com/[^/]+/posts/[\d]+#', $attrs['url'] ) && ! preg_match( '#https?://(www)?\.facebook\.com\/video\.php\?v=[\d]+#', $attrs['url'] ) ) {
			if ( current_user_can( 'edit_posts' ) ) {
				return '<div class="shortcake-bakery-error"><p>' . sprintf( esc_html__( 'Invalid Facebook URL: %s', 'shortcake-bakery' ), esc_url( $attrs['url'] ) ) . '</p></div>';
			} else {
				return '';
			}
		}

		wp_enqueue_script( 'facebook-api' );
		$out = '<div id="fb-root"></div>';
		$out .= '<div class="fb-post" data-href="' . esc_url( $attrs['url'] ) . '" data-width="350px" data-true-height="550px" data-true-width="350px"><div class="fb-xfbml-parse-ignore"></div></div>';
		return $out;
	}

}
