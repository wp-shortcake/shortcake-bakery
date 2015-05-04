<?php

class Phone extends Shortcode {

	public function __construct() {

		$format = _x( 'Example: %s', 'Generic Attribute Description', SHORTCAKE_BAKERY_TEXTDOMAIN );

		$this->add_attribute( 'type', 'phone', array(
			'label' => _x( 'Phone Type', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type' => 'radio',
			'options' => array(
				'phone' => _x( 'Phone', 'Phone Type Option', SHORTCAKE_BAKERY_TEXTDOMAIN ),
				'fax' 	=> _x( 'Fax', 'Phone Type Option', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			),
		) );

		$this->add_attribute( 'number', '', array(
			'label' => _x( 'Phone Number', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'description' => _x( 'Example: 555-555-5555', 'Phone Number Description', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type'  => 'text',
		) );

		$default = _x( 'Phone:', 'Phone Shortcode Default Title', SHORTCAKE_BAKERY_TEXTDOMAIN );
		$this->add_attribute( 'text', $default, array(
			'label' => __( 'Text before number', 'shortcode-ui-essentials' ),
			'description' => sprintf( $format, $default ),
			'type' => 'text',
			'meta' => array(
				'placeholder' => $default,
			),
		) );

		$default = _x( 'Call us today!', 'Phone Shortcode Default Tooltip', SHORTCAKE_BAKERY_TEXTDOMAIN );
		$this->add_attribute( 'tooltip', $default, array(
			'label' => _x( 'Link Tooltip', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'description' => sprintf( $format, $default ),
			'type' => 'text',
			'meta' => array(
				'placeholder' => $default,
			),
		) );

		parent::__construct( 'phone', array(
			'label' => __( 'Phone', 'Shortcode UI Label', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'listItemImage' => 'dashicons-phone',
		) );

	}

	public function callback( $atts = array(), $content = '' ) {
		parent::callback( $atts, $content );

		$type    = 'fax' === $this->shortcode_attrs['type'] ? 'fax' : 'phone';
		$icon    = 'fax' === $type ? 'fa-print' : 'fa-mobile-phone';

		$number  = $this->shortcode_attrs['number'];
		$text    = $this->shortcode_attrs['text'];
		$tooltip = $this->shortcode_attrs['tooltip'];
?>
<div id="<?php echo esc_attr( $type ); ?>-area">
    <i class="fa <?php echo esc_attr( $icon ); ?>"></i>
    <?php
	echo esc_html( $text ) . '&nbsp;';
	if ( 'phone' === $type ) {
?>
<a href="tel:<?php echo esc_attr( $number ); ?>" title="<?php echo esc_attr( $tooltip ); ?>" data-placement="bottom"><?php echo esc_html( $number ); ?></a>
<?php
	} else {
		echo esc_html( $number ) . "\n";
	}
?>
</div>
<?php
		return ob_get_clean();
	}

}
