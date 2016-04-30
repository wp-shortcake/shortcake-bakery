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
		'Shortcake_Bakery\Shortcodes\GoogleDocs',
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
		add_action( 'shortcode_ui_loaded_editor', array( $this, 'action_admin_enqueue_scripts' ) );
		add_action( 'media_buttons', array( $this, 'action_media_buttons' ) );
		add_action( 'wp_ajax_shortcake_bakery_embed_reverse', array( $this, 'action_ajax_shortcake_bakery_embed_reverse' ) );
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
			$ui_args = apply_filters( 'shortcake_bakery_shortcode_ui_args', $class::get_shortcode_ui_args(), $shortcode_tag );
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

	public function action_admin_enqueue_scripts() {
		wp_enqueue_script( 'shortcake-bakery-admin', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/build/shortcake-bakery-admin.js', array( 'media-views', 'shortcode-ui' ) );
		wp_enqueue_style( 'shortcake-bakery', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/shortcake-bakery.css' );

		$strings = array(
			'text' => array(
				'addEmbed'           => __( 'Insert Embed Code', 'shortcake-bakery' ),
				'insertButton'       => __( 'Insert embed', 'shortcake-bakery' ),
				'customEmbedLabel'   => __( 'Paste any custom embed code here. If it matches a known post element, that post element will be used rather than the embed code.', 'shortcake-bakery' ),
				'noReversalMatches'  => __( 'The embed code provided doesn\'t match any known post elements. This means that it may not display as expected.', 'shortcake-bakery' ),
			),
			'nonces' => array(
				'customEmbedReverse' => wp_create_nonce( 'embed-reverse' ),
			),
			'shortcodes' => array_flip( $this->registered_shortcodes ),
		);

		wp_localize_script( 'shortcake-bakery-admin', 'ShortcakeBakery', $strings );
	}


	/**
	 * Output the "Add embed" button with WP media buttons.
	 *
	 * @return void
	 */
	public function action_media_buttons( $editor_id ) {
		printf( '<button type="button" class="button insert-embed shortcake-bakery-insert-embed" data-editor="%s"><span class="dashicons dashicons-editor-code"></span> %s</button>',
			esc_attr( $editor_id ),
			esc_html__( 'Add Embed', 'shortcake-bakery' )
		);
	}

	public function action_ajax_shortcake_bakery_embed_reverse() {
		if ( empty( $_POST['_wpnonce'] ) || empty( $_POST['custom_embed_code'] ) ) {
			exit;
		}
		check_ajax_referer( 'embed-reverse', '_wpnonce' );
		$post_id = intval( $_POST['post_id'] );
		$provided_embed_code = wp_unslash( $_POST['custom_embed_code'] );
		$result = $this->reverse_embed( $provided_embed_code );

		/**
		 * Hook to transform the embed reversal response before returning it to the editor.
		 *
		 * @param array Return value of `reverse_embed()`
		 * @param string Original string provided
		 * @param int Post ID
		 */
		$result = apply_filters( 'shortcake_bakery_embed_reversal', $result, $provided_embed_code, $post_id );

		/*
		 * Fired whenever an embed code is reversed through Ajax action.
		 *
		 * @param array Return value of `reverse_embed()`
		 * @param string Original string provided
		 * @param int Post ID
		 */
		do_action( 'shortcake_bakery_reversed_embed', $result, $provided_embed_code, $post_id );

		wp_send_json( $result );
		exit;
	}

	/**
	 * Given a string containing embed codes, returns any shortcodes which
	 * could be extracted from that code by a reversal process.
	 *
	 * @param string
	 * @return array Array to send in a JSON response {
	 *    @val bool "success" Whether any shortcodes were found in the reversal process
	 *    @val string "reversal" Output string after reversals performed
	 *    @val array "shortcodes" Array of matched shortcodes
	 * }
	 */
	public function reverse_embed( $provided_embed_code ) {
		$success = false;
		$shortcodes = array();
		$reversal = apply_filters( 'pre_kses', $provided_embed_code );
		if ( $reversal !== $provided_embed_code && preg_match_all( '/' . get_shortcode_regex() . '/s', $reversal, $matched_shortcodes, PREG_SET_ORDER ) ) {
			$success = true;

			foreach ( $matched_shortcodes as $matched_shortcode ) {
				$shortcodes[] = array(
					'shortcode' => $matched_shortcode[2],
					'attributes' => shortcode_parse_atts( $matched_shortcode[3] ),
					'inner_content' => $matched_shortcode[5],
					'raw' => $matched_shortcode[0],
				);
			}
		}
		return array(
			'success' => $success,
			'reversal' => $reversal,
			'shortcodes' => $shortcodes,
		);
	}
}
