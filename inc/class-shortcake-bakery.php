<?php

/**
 * Manages registered shortcodes
 */
class Shortcake_Bakery {

	private static $instance;

	private $internal_shortcode_classes = array(
		'Shortcake_Bakery\Shortcodes\ABC_News',
		'Shortcake_Bakery\Shortcodes\Facebook',
		'Shortcake_Bakery\Shortcodes\Flickr',
		'Shortcake_Bakery\Shortcodes\Giphy',
		'Shortcake_Bakery\Shortcodes\Guardian',
		'Shortcake_Bakery\Shortcodes\Iframe',
		'Shortcake_Bakery\Shortcodes\Image_Comparison',
		'Shortcake_Bakery\Shortcodes\Infogram',
		'Shortcake_Bakery\Shortcodes\Instagram',
		'Shortcake_Bakery\Shortcodes\Livestream',
		'Shortcake_Bakery\Shortcodes\Rap_Genius',
		'Shortcake_Bakery\Shortcodes\PDF',
		'Shortcake_Bakery\Shortcodes\Playbuzz',
		'Shortcake_Bakery\Shortcodes\Scribd',
		'Shortcake_Bakery\Shortcodes\Script',
		'Shortcake_Bakery\Shortcodes\Silk',
		'Shortcake_Bakery\Shortcodes\SoundCloud',
		'Shortcake_Bakery\Shortcodes\Twitter',
		'Shortcake_Bakery\Shortcodes\Videoo',
		'Shortcake_Bakery\Shortcodes\Vimeo',
		'Shortcake_Bakery\Shortcodes\Vine',
		'Shortcake_Bakery\Shortcodes\YouTube',
		);
	private $registered_shortcode_classes = array();
	private $registered_shortcodes = array();

	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new Shortcake_Bakery;
			self::$instance->setup_actions();
			self::$instance->setup_filters();
		}
		return self::$instance;
	}

	/**
	 * Set up shortcode actions
	 */
	private function setup_actions() {
		add_action( 'init', array( $this, 'action_init_register_shortcodes' ) );
		add_action( 'shortcode_ui_after_do_shortcode', function( $shortcode ) {
			return Shortcake_Bakery::get_shortcake_admin_dependencies();
		});
	}

	/**
	 * Set up shortcode filters
	 */
	private function setup_filters() {
		add_filter( 'pre_kses', array( $this, 'filter_pre_kses' ) );
	}

	/**
	 * Register all of the shortcodes
	 */
	public function action_init_register_shortcodes() {

		$this->registered_shortcode_classes = apply_filters( 'shortcake_bakery_shortcode_classes', $this->internal_shortcode_classes );
		foreach ( $this->registered_shortcode_classes as $class ) {
			$shortcode_tag = $class::get_shortcode_tag();
			$this->registered_shortcodes[ $shortcode_tag ] = $class;
			add_shortcode( $shortcode_tag, array( $this, 'do_shortcode_callback' ) );
			$class::setup_actions();
			$ui_args = apply_filters( 'shortcake_bakery_global_ui_args', $class::get_shortcode_ui_args() );
			$ui_args = apply_filters( "shortcake_bakery_{$shortcode_tag}_ui_args", $ui_args );
			if ( ! empty( $ui_args ) && function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
				shortcode_ui_register_for_shortcode( $shortcode_tag, $ui_args );
			}
		}
	}

	/**
	 * Modify post content before kses is applied
	 * Used to trans
	 */
	public function filter_pre_kses( $content ) {

		foreach ( $this->registered_shortcode_classes as $shortcode_class ) {
			$content = $shortcode_class::reversal( $content );
		}
		return $content;
	}

	/**
	 * Do the shortcode callback
	 */
	public function do_shortcode_callback( $attrs, $content = '', $shortcode_tag ) {

		if ( empty( $this->registered_shortcodes[ $shortcode_tag ] ) ) {
			return '';
		}

		wp_enqueue_script( 'shortcake-bakery', SHORTCAKE_BAKERY_URL_ROOT . 'assets/js/shortcake-bakery.js', array( 'jquery' ), SHORTCAKE_BAKERY_VERSION );

		$class = $this->registered_shortcodes[ $shortcode_tag ];
		$output = $class::callback( $attrs, $content, $shortcode_tag );
		return apply_filters( 'shortcake_bakery_shortcode_callback', $output, $shortcode_tag, $attrs, $content );
	}

	/**
	 * Admin dependencies.
	 * Scripts required to make shortcake previews work correctly in the admin.
	 *
	 * @return string
	 */
	public static function get_shortcake_admin_dependencies() {
		if ( ! is_admin() ) {
			return;
		}
		$r = '<script src="' . esc_url( includes_url( 'js/jquery/jquery.js' ) ) . '"></script>';
		$r .= '<script type="text/javascript" src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/js/shortcake-bakery.js' ) . '"></script>';
		return $r;
	}

}
