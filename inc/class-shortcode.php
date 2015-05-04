<?php
/**
 * An abstract class for shortcode.
 */
abstract class Shortcode {

	/**
	 * Shortcode tag variable
	 */
	protected $shortcode_tag;

	/**
	 * List of supported attributes and their defaults
	 */
	protected $shortcode_attrs = array();

	/**
	 * Shortcode UI (Shortcake) arguments
	 */
	private $shortcake_args = array();

	/**
	 * Shortcode UI (Shortcake) attributes
	 */
	private $shortcake_attrs = array();

	/**
	 * Post Types
	 */
	private $post_type = array( 'post', 'page' );

	/**
	 * Shortcode class constructor
	 *
	 * @param string $shortcode_tag  Shortcode tag to be searched in post content.
	 * @param array  $shortcake_args Optional, but recommended. List of Shortcake (Shortcode UI) tags.
	 * @param bool   $add_shortcode  Optional. Whether the shortcode will be registered on WordPress.
	 */
	public function __construct( $shortcode_tag, $shortcake_args = array(), $add_shortcode = true ) {
		$this->shortcode_tag 	= $shortcode_tag;
		$this->shortcake_args 	= $shortcake_args;
		if ( $add_shortcode ) {
			$this->add_shortcode();
		}
		$this->shortcode_ui_register_for_shortcode();
	}

	/**
	 * Adds an attribute to current Shortcode attribute slist
	 *
	 * @param string $attr 		Attribute name
	 * @param mixed  $default 	Default value
	 * @param array  $args 		Shortcake (Shortcode UI) args
	 *
	 */
	public function add_attribute( $attr, $default = null, $args = array() ) {
		if ( ! is_array( $this->shortcake_attrs ) ) {
			$this->shortcake_attrs = array();
		}

		$this->shortcode_attrs[ $attr ] = apply_filters( $this->shortcode_tag . '_default_value', $default );
		$args['attr'] = $attr;
		$this->shortcake_attrs[] = $args;
	}

	/**
	 * Add hook for shortcode tag
	 *
	 * There can only be one hook for each shortcode. Which means that if another
	 * plugin has a similar shortcode, it will override yours or yours will override
	 * theirs depending on which order the plugins are included and/or ran.
	 *
	 */
	public function add_shortcode() {
		do_action( 'before_add_shortcode_' . $this->shortcode_tag );
		return \add_shortcode( $this->shortcode_tag, array( $this, 'callback' ) );
	}

	/**
	 * Register the UI for the shortcode
	 */
	public function shortcode_ui_register_for_shortcode() {
		if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
			return;
		}
		$args = $this->shortcake_args;
		$args['attrs'] = $this->shortcake_attrs;
		return \shortcode_ui_register_for_shortcode( $this->shortcode_tag, $args );
	}

	/**
	 * Render output from the shortcode.
	 *
	 * @param array $attrs Shortcode attributes.
	 *
	 * Don't forget to use ob_start() before and return ob_get_clean() after echoing code
	 */
	public function callback( $attrs = array(), $content = '' ) {
		$this->shortcode_attrs = shortcode_atts(
			$this->shortcode_attrs,
			$attrs,
			$this->shortcode_tag
		);
		ob_start();
	}

}
