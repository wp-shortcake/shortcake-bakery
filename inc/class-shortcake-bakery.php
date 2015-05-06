<?php

/**
 * Manages registered shortcodes
 */
class Shortcake_Bakery {

	private static $instance;

	private $shortcode_classes = array();

	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new Shortcake_Bakery;
			self::$instance->setup_actions();
			self::$instance->setup_filters();
			self::$instance->register_shortcodes();
		}
		return self::$instance;
	}

	/**
	 * Autoload any of our shortcode classes
	 */
	public function autoload_shortcode_classes( $class ) {
		$class = ltrim( $class, '\\' );
		if ( 0 !== stripos( $class, 'Shortcake_Bakery\\Shortcodes' ) ) {
			return;
		}

		$parts = explode( '\\', $class );
		// Don't need "Shortcake_Bakery\Shortcodes\"
		array_shift( $parts );
		array_shift( $parts );
		$last = array_pop( $parts ); // File should be 'class-[...].php'
		$last = 'class-' . $last . '.php';
		$parts[] = $last;
		$file = dirname( __FILE__ ) . '/inc/' . str_replace( '_', '-', strtolower( implode( $parts, '/' ) ) );
		if ( file_exists( $file ) ) {
			require $file;
		}

	}

	/**
	 * Set up shortcode actions
	 */
	private function setup_actions() {
		spl_autoload_register( array( $this, 'autoload_shortcode_classes' ) );
	}

	/**
	 * Set up shortcode filters
	 */
	private function setup_filters() {
		add_filter( 'pre_kses', array( $this, 'filter_pre_kses' ) );
	}

	/**
	 * Modify post content before kses is applied
	 * Used to trans
	 */
	public function filter_pre_kses( $content ) {

		foreach ( $this->shortcode_classes as $shortcode_class ) {
			$content = $shortcode_class::reversal( $content );
		}
		return $content;
	}

	/**
	 * Register all of the shortcodes
	 */
	private function register_shortcodes() {

		foreach ( $this->shortcode_classes as $class ) {
			$shortcode_tag = $class::get_shortcode_tag();
			add_shortcode( $shortcode_tag, $class . '::callback' );
			$class::setup_actions();
			$ui_args = $class::get_shortcode_ui_args();
			if ( ! empty( $ui_args ) ) {
				shortcode_ui_register_for_shortcode( $shortcode_tag, $ui_args );
			}
		}
	}

}
