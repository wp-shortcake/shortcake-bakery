<?php

class Google_Map extends Shortcode {

	public function __construct() {

		$this->add_attribute( 'q', '', array(
			'label' 		=> _x( 'Map Query Address', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'description' 	=> _x( 'Example: 3785 Brickway Blvd., Santa Rosa, CA 95403', 'Map Query Address - Attribute Description', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type' 			=> 'textarea',
		) );

		$this->add_attribute( 'z', 15, array(
			'label' 		=> _x( 'Zoom', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'description' 	=> __( 'From 0 to 21. Default is 15.', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type' 			=> 'number',
			'meta' 			=> array(
				'max' 		=> 21,
				'min' 		=> 0,
				'step' 		=> 1,
			),
		) );

		$this->add_attribute( 'href', home_url(), array(
			'label' 		=> _x( 'Link', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type' 			=> 'url',
			'meta' 			=> array(
				'placeholder' => '//',
			),
		) );

		parent::__construct( 'google_map', array(
			'label' 		=> _x( 'Google Map', 'Shortcode UI Label', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'listItemImage' => 'dashicons-location-alt',
		) );

	} // end function __construct

	/**
	 * Build the map link
	 *
	 * Google map urls have lots of available params but zoom (z) and query (q) are enough.
	 *
	 * @param string $address Required. The map address.
	 * @param int $zoom Optional. Map zoom. Default is 15.
	 */
	private function build_map_link( $address, $zoom = 15 ) {

		$query_uri = 'http://maps.google.com/maps';
		$params = array(
			'q' => $this->urlencode_address( $address ),
			'output' => 'embed',
			'z' => $zoom,
		);

		return esc_url( add_query_arg( $params, $query_uri ) );
	}

	public function callback( $atts = array(), $content = '' ) {
		parent::callback( $atts, $content );

		$href = isset( $this->shortcode_attrs['href'] ) ? $this->shortcode_attrs['href'] : '#';
		unset( $this->shortcode_attrs['href'] );

		$embed_src = $this->build_map_link( $this->shortcode_attrs['q'], $this->shortcode_attrs['z'] );
?>
<a data-toggle="modal" href="<?php echo esc_attr( $href ); ?>" role="button">
	<div id="mapContainer">
    	<iframe src="<?php echo esc_url( $embed_src ); ?>">
    		<?php echo esc_html( $this->shortcode_attrs['q'] ); ?>
    	</iframe>
    </div>
</a>
<?php
		return ob_get_clean();

	} // end function callback

	private function urlencode_address( $address ) {

		$address = strtolower( $address );
		$address = preg_replace( '/\s+/', ' ', trim( $address ) ); // Get rid of any unwanted whitespace
		$address = str_ireplace( ' ', '+', $address ); // Use + not %20
		urlencode( $address );

		return $address;
	}

} // end class Google_Map
