<?php

class Shortcake_Bakery {

	private $plugin_version;
	private $plugin_dir;
	private $plugin_url;

	private static $instance;

	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
			self::$instance->setup_actions();
			self::$instance->register_shortcodes();
		}

		return self::$instance;
	}

	function __construct() {

		$this->plugin_version 	= SHORTCAKE_BAKERY_VERSION;
		$this->plugin_dir 		= plugin_dir_path( dirname( __FILE__ ) );
		$this->plugin_url 		= plugin_dir_url( dirname( __FILE__ ) );

	}

	private function setup_actions() {

	}

	private function load_shortcode( $slug, $class ) {
		$file = dirname( __FILE__ ) . '/shortcodes/class-' . $slug . '.php';

		if ( file_exists( $file ) ) {
			require_once $file;
			if ( class_exists( $class ) ) {
				new $class;
			} // end if
		} // end if
	}

	/**
	 * Load generic shortcodes
	 *
	 * @param array $shortcodes The shortcodes [$slug => $class] array.
	 */
	private function load_shortcodes( $shortcodes = array() ) {
		if ( ! is_array( $shortcodes ) ) {
			return;
		}
		foreach ( $shortcodes as $slug => $class ) {
			$this->load_shortcode( $slug, $class );
		}
	} // end function load_shortcodes

	/**
	 * Load theme-dependend shortcodes
	 *
	 * @param array $shortcodes The shortcodes [$slug => $class] array.
	 */
	private function load_theme_shortcodes( $shortcodes = array() ) {
		if ( ! is_array( $shortcodes ) ) {
			return;
		}
		foreach ( $shortcodes as $slug => $class ) {
			if ( current_theme_supports( 'shortcode-' . $slug ) ) {
				$this->load_shortcode( $slug, $class );
			}
		}
	}

	/**
	 * Register the shortcodes and the UI for them.
	 */
	private function register_shortcodes() {

		$shortcake_bakery_shortcodes = array(
			'google-map'		=> 'Google_Map',
			'heading'			=> 'Heading',
		);
		$shortcake_bakery_shortcodes = apply_filters(
			'shortcake_bakery_shortcodes',
			$shortcake_bakery_shortcodes
		);

		$shortcake_theme_shortcodes = array(
			'bucket'			=> 'Bucket',
			'mailing-address'	=> 'Mailing_Address',
			'phone'				=> 'Phone',
			'scroll-point'		=> 'Scroll_Point',
		);
		$shortcake_theme_shortcodes = apply_filters(
			'shortcake_theme_shortcodes',
			$shortcake_theme_shortcodes
		);

		require_once dirname( __FILE__ ) . '/class-shortcode.php';

		$this->load_shortcodes( $shortcake_bakery_shortcodes );

		$this->load_theme_shortcodes( $shortcake_theme_shortcodes );

		if ( class_exists( 'Grunion_Contact_Form_Plugin' ) ) {
			$this->load_shortcode( 'contact-field', 'Contact_Field' );
		}

	}

}
