<?php

class Contact_Field extends Shortcode {

	public function __construct() {

		$this->add_attribute( 'label', '', array(
			'label' 		=> _x( 'Field Label', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type' 			=> 'text',
		) );

		$this->add_attribute( 'type', 'text', array(
			'label' 		=> _x( 'Field Type', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'description' 	=> __( 'Accepted types: name, email, text, radio & select.', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type'  		=> 'select',
			'options' 		=> array(
				'name' 		=> _x( 'Name input', 'Field Type Option', SHORTCAKE_BAKERY_TEXTDOMAIN ),
				'email' 	=> _x( 'Email input', 'Field Type Option', SHORTCAKE_BAKERY_TEXTDOMAIN ),
				'text' 		=> _x( 'Text input', 'Field Type Option', SHORTCAKE_BAKERY_TEXTDOMAIN ),
				'radio' 	=> _x( 'Radio options', 'Field Type Option', SHORTCAKE_BAKERY_TEXTDOMAIN ),
				'select' 	=> _x( 'Select box', 'Field Type Option', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			),
		) );

		$this->add_attribute( 'required', '', array(
			'label' 		=> _x( 'Required', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type' 			=> 'select',
			'options' 		=> array(
				'' 			=> __( 'No', SHORTCAKE_BAKERY_TEXTDOMAIN ),
				'1' 		=> __( 'Yes', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			),
		) );

		parent::__construct( 'contact-field', array(
			'label' 		=> _x( 'Contact Field', 'Shortcode UI Label', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'listItemImage' => 'dashicons-forms',
		), false );

	} // end function __construct

} // end class Contact_Field
