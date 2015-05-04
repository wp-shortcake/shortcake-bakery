<?php

class Scroll_Point extends Shortcode {

	public function __construct() {

		$this->add_attribute( 'id', null, array(
			'label' => _x( 'ID', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type' 	=> 'number',
			'meta' 	=> array(
				'min'  => 1,
				'step' => 1,
			),
		) );

		parent::__construct( 'scroll_point', array(
			'label' => _x( 'Scroll Point', 'Shortcode UI Label', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'listItemImage' => 'dashicons-arrow-down',
		) );

	}

	public function callback( $atts = array(), $content = '' ) {
		parent::callback( $atts, $content );

		$class = 'row-fluid';
		$id = 'scroll-point-' . intval( $this->shortcode_attrs['id'] );
?>
<div class="<?php echo sanitize_html_class( $class ); ?> scroll-to-wrap" id="<?php echo esc_attr( $id ); ?>">
	<span class="theme-arrow"></span>
</div>
<?php
		return ob_get_clean();
	}
}
