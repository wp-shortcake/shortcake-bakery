<?php

namespace Shortcake_Bakery\Shortcodes;

class Live_Photo extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Live Photo', 'shortcake-bakery' ),
			'listItemImage'  => '<img src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/png/icon-live-photo.png' ) . '" />',
			'attrs'          => array(
				array(
					'label'  => esc_html__( 'Live Photo Image', 'shortcake-bakery' ),
					'attr'   => 'live-photo-image',
					'type'   => 'attachment',
					'libraryType' => array( 'image' ),
					'addButton'   => esc_html__( 'Select Live Photo Image', 'shortcake-bakery' ),
					'frameTitle'  => esc_html__( 'Select Live Photo Image', 'shortcake-bakery' ),
				),
				array(
					'label'  => esc_html__( 'Live Photo Movie', 'shortcake-bakery' ),
					'attr'   => 'live-photo-movie',
					'type'   => 'attachment',
					'libraryType' => array( 'video' ),
					'addButton'   => esc_html__( 'Select Live Photo Movie', 'shortcake-bakery' ),
					'frameTitle'  => esc_html__( 'Select Live Photo Movie', 'shortcake-bakery' ),
				),
			),
		);
	}

	public static function setup_actions() {
		add_action( 'wp_enqueue_scripts', 'Shortcake_Bakery\Shortcodes\Live_Photo::action_init_register_scripts' );
		add_action( 'shortcode_ui_after_do_shortcode', function( $shortcode ) {
			if ( false !== stripos( $shortcode, '[' . self::get_shortcode_tag() ) ) {
				echo '<script type="text/javascript" src="' . esc_url( 'https://cdn.apple-livephotoskit.com/lpk/1/livephotoskit.js' ) . '"></script>';
			}
		});
	}

	public static function action_init_register_scripts() {
		wp_register_script( 'apple-live-photo', 'https://cdn.apple-livephotoskit.com/lpk/1/livephotoskit.js', array() );
	}

	public static function callback( $attrs, $content = '' ) {
		if ( empty( $attrs['live-photo-image'] ) || empty( $attrs['live-photo-movie'] ) ) {
			if ( current_user_can( 'edit_posts' ) ) {
				return '<div class="shortcake-bakery-error"><p>' . esc_html__( 'An image is required for Apple Live Photo shortcode.', 'shortcake-bakery' ) . '</p></div>';
			} else {
				return '';
			}
		}
		$live_photo_image = wp_get_attachment_url( $attrs['live-photo-image'] );
		$live_photo_movie = wp_get_attachment_url( $attrs['live-photo-movie'] );
		wp_enqueue_script( 'apple-live-photo' );
		return sprintf(
			'<div data-live-photo style="width:auto; height:400px;" data-proactively-loads-video="false" data-photo-src="%1$s" data-video-src="%2$s"></div>',
			esc_url( $live_photo_image ),
			esc_url( $live_photo_movie )
		);
	}

}
