<?php

/**
 * Manages registered shortcodes
 */
class Shortcake_Bakery {

	private static $instance;

	private $internal_shortcode_classes = array(
		'Shortcake_Bakery\Shortcodes\Facebook',
		'Shortcake_Bakery\Shortcodes\Iframe',
		'Shortcake_Bakery\Shortcodes\Image_Comparison',
		'Shortcake_Bakery\Shortcodes\Infogram',
		'Shortcake_Bakery\Shortcodes\Rap_Genius',
		'Shortcake_Bakery\Shortcodes\PDF',
		'Shortcake_Bakery\Shortcodes\Scribd',
		'Shortcake_Bakery\Shortcodes\Script',
		'Shortcake_Bakery\Shortcodes\Playbuzz',
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
		$file = dirname( __FILE__ ) . '/shortcodes/' . str_replace( '_', '-', strtolower( implode( $parts, '/' ) ) );
		if ( file_exists( $file ) ) {
			require $file;
		}

	}

	/**
	 * Set up shortcode actions
	 */
	private function setup_actions() {
		spl_autoload_register( array( $this, 'autoload_shortcode_classes' ) );
		add_action( 'init', array( $this, 'action_init_register_shortcodes' ) );
		add_action( 'shortcode_ui_after_do_shortcode', function( $shortcode ) {
			return $this::get_shortcake_admin_dependencies();
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
			$ui_args = $class::get_shortcode_ui_args();
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
		return $class::callback( $attrs, $content, $shortcode_tag );
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
