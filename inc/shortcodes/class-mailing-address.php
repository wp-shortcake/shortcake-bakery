<?php

class Mailing_Address extends Shortcode {

	public function __construct() {

		$this->add_attribute( 'title', _x( 'Mailing Address', 'Mailing Address Default Title', SHORTCAKE_BAKERY_TEXTDOMAIN ), array(
			'label' => _x( 'Title', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type' 	=> 'text',
		) );

		$this->add_attribute( 'street_address', '', array(
			'label' => _x( 'Street Address', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'description' => _x( 'Example: 3785 Brickway Blvd.', 'Street Address Example', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type' => 'text',
		) );

		$this->add_attribute( 'locality', '', array(
			'label' => _x( 'Locality', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'description' => _x( 'Example: Santa Rosa', 'Locality Example', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type' => 'text',
		) );

		$this->add_attribute( 'region', '', array(
			'label' => _x( 'Region', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'description' => _x( 'Example: CA', 'Region Example', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type' => 'text',
		) );

		$this->add_attribute( 'postal_code', '', array(
			'label' => _x( 'Postal Code', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'description' => _x( 'Example: 95403', 'Postal Code Example', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type' => 'text',
		) );

		parent::__construct( 'mailing_address', array(
			'label' => _x( 'Mailing Address', 'Shortcode UI Label', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'listItemImage' => 'dashicons-location',
			'inner_content' => array(
				'label' => _x( 'Person or Business Name', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			),
		) );

	}

	public function callback( $atts = array(), $content = '' ) {
		parent::callback( $atts, $content );

		$title 			= $this->shortcode_attrs['title'];
		$street_address = $this->shortcode_attrs['street_address'];
		$locality 		= $this->shortcode_attrs['locality'];
		$region 		= $this->shortcode_attrs['region'];
		$postal_code 	= $this->shortcode_attrs['postal_code'];
?>
<div id="mailing-address">
    <i class="fa fa-map-marker"></i>
    <?php echo esc_html( $title ); ?><br /><br />
    <span><?php echo esc_html( $content ); ?></span><br />
    <span class="street-address" itemprop="streetAddress"><?php echo esc_html( $street_address ); ?></span><br />
    <span class="locality" itemprop="addressLocality"><?php echo esc_html( $locality ); ?></span>,
    <abbr class="region" itemprop="addressRegion"><?php echo esc_html( $region ); ?></abbr>
    <span class="postal-code"  itemprop="postalCode"><?php echo esc_html( $postal_code ); ?></span>
</div>
<?php
		return ob_get_clean();
	}

}
