<?php

class Bucket extends Shortcode {

	public function __construct() {

		$this->add_attribute( 'href', home_url(), array(
			'label' 	=> _x( 'Link URL', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type' 		=> 'url',
			'meta' 		=> array(
				'placeholder' => '//',
			),
		) );

		$this->add_attribute( 'img_id', null, array(
			'label' 	=> _x( 'Featured Image', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'description' => __( 'Use an image from your own site.', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type' 		=> 'attachment',
			'libraryType' => array( 'image' ),
			'addButton' => _x( 'Select Image', 'Add Button', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'frameTitle' => _x( 'Select Image', 'Frame Title', SHORTCAKE_BAKERY_TEXTDOMAIN ),
		) );

		/** This filter is documented in wp-admin/includes/media.php */
		$sizes = apply_filters( 'image_size_names_choose', array(
			'thumbnail' => _x( 'Thumbnail', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'medium'    => _x( 'Medium', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'large'     => _x( 'Large', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'full'      => _x( 'Full Size', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
		) );

		$this->add_attribute( 'img_size', 'full', array(
			'label' 	=> _x( 'Image Size', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type' 		=> 'select',
			'options' 	=> $sizes,
		) );

		$this->add_attribute( 'img_src', '', array(
			'label' 	=> _x( 'External Image URL', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'description' => __( 'Use this field only if you want to use an external source image.', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type' 		=> 'url',
			'meta' 		=> array(
				'placeholder' => '//',
			),
		) );

		$this->add_attribute( 'img_width', 266, array(
			'label' 	=> _x( 'Image Width', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type' 		=> 'number',
			'meta' 		=> array(
				'min' 	=> 44,
				'step' 	=> 1,
			),
		) );

		$this->add_attribute( 'img_height', 94, array(
			'label' 	=> _x( 'Image Height', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type' 		=> 'number',
			'meta' 		=> array(
				'min' 	=> 44,
				'step' 	=> 1,
			),
		) );

		$this->add_attribute( 'class', '', array(
			'label' 	=> __( 'CSS Class', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type' 		=> 'text',
		) );

		$this->add_attribute( 'id', null, array(
			'label' 	=> __( 'Numeric ID', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'type' 		=> 'number',
			'meta' 		=> array(
				'min' 	=> 1,
				'step' 	=> 1,
			),
		) );

		parent::__construct( 'bucket', array(
			'label' 		=> _x( 'Bucket', 'Shortcode UI Label', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			'listItemImage' => 'dashicons-align-left',
			'inner_content' => array(
				'label' => _x( 'Title', 'Attribute', SHORTCAKE_BAKERY_TEXTDOMAIN ),
			)
		) );

	} // end function __construct

	public function callback( $atts = array(), $content = '' ) {
		parent::callback( $atts, $content );

		$img_id 	= $this->shortcode_attrs['img_id'];
		$img_src 	= $this->shortcode_attrs['img_src'];

		$use_local_img = isset( $img_id ) && ! empty( $img_id ) && intval( $img_id ) > 0;
		$use_external_img = isset( $img_src ) && ! empty( $img_src ) && is_null( $img_id );

		$id 		= $this->shortcode_attrs['id'];
		$class 		= $this->shortcode_attrs['class'];
		$href 		= $this->shortcode_attrs['href'];
		$img_size 	= $this->shortcode_attrs['img_size'];
		$img_width 	= $this->shortcode_attrs['img_width'];
		$img_height = $this->shortcode_attrs['img_height'];

		$image_args = array(
	    	'alt' 	=> $content,
	    	'class' => 'bucket-img bucket-img-' . intval( $id ) . ' wp-post-image',
	    );

		if ( isset( $img_height ) && ! empty( $img_height ) && intval( $img_height ) > 0 ) {
			$image_args['height'] = intval( $img_height );
		}

		if ( $use_external_img ) {
	    	$image_args['src'] 	  = $img_src;
		}

		if ( isset( $img_width ) && ! empty( $img_width ) && intval( $img_width ) > 0 ) {
			$image_args['width']  = intval( $img_width );
		}

?>
<div id="bucket<?php echo intval( $id ); ?>" class="bucket <?php echo esc_attr( $class ); ?>">
    <a
    	class="clickable"
    	href="<?php echo esc_attr( $href ); ?>"
    	title="<?php echo esc_attr( $content ); ?>">
    </a>
    <div class="featured-arrow"></div>
	<div class="featured-title-area">
      <h2><?php echo esc_html( $content ); ?></h2>
    </div>
    <div class="featured-image-area">
    	<?php
		if ( $use_local_img ) {
			echo wp_get_attachment_image( intval( $img_id ), $img_size, false, $image_args );
		}
		else {
?>
<img <?php foreach ( $image_args as $key => $value ) { echo esc_attr( $key ) . '="' . esc_attr( $value ) . '" '; } ?> />
<?php
		}
		?>
    </div>
</div>
<?php
		return ob_get_clean();

	} // end function callback

} // end class Bucket_Shortcode
