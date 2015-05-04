<?php

class Heading extends Shortcode {

	public function __construct() {

		$this->add_attribute( 'level', 2, array(
			'label' 		=> _x( 'Heading Level', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'description' 	=> __( 'Heading level (from 1 to 6). Default is 2.', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type' 			=> 'number',
			'meta' 			=> array(
				'max' 		=> 6,
				'min' 		=> 1,
				'step' 		=> 1,
			),
		) );

		parent::__construct( 'heading', array(
			'label' 		=> _x( 'Heading', 'Shortcode UI Label', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'listItemImage' => 'dashicons-editor-textcolor',
			'inner_content' 	=> array(
				'label' => __( 'Title', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			),
		) );
	}

	public function callback( $atts = array(), $content = '' ) {
		parent::callback( $atts, $content );

		$level = $this->shortcode_attrs['level'];
		$slug = sanitize_title( $content );

		?>
<h<?php echo intval( $level ); ?> id="<?php echo esc_attr( $slug ); ?>">
	<a href="#<?php echo esc_attr( $slug ); ?>" title="<?php echo esc_attr( $content ); ?>">
		<?php echo esc_html( $content ); ?>
	</a>
</h<?php echo intval( $level ); ?>><?php
		return ob_get_clean();
	}

}
