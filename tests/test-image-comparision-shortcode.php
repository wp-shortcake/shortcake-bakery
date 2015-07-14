<?php

class Test_Image_Comparison_Shortcode extends WP_UnitTestCase {

	private $attachment_id;
	private $image_src;
	private $image_path;

	// @codingStandardsIgnoreStart
	public function setUp() {
		parent::setUp();

		$this->attachment_id = $this->insert_attachment( null,
			dirname( __FILE__ ) . '/data/fusion_image_placeholder_16x9_h2000.png',
			array(
				'post_title'     => 'Post',
				'post_content'   => 'Post Content',
				'post_date'      => '2014-10-01 17:28:00',
				'post_status'    => 'publish',
				'post_type'      => 'attachment',
			)
		);

		$upload_dir = wp_upload_dir();

		$this->image_src = $upload_dir['url'] . '/fusion_image_placeholder_16x9_h2000.png';
		$this->image_path = $upload_dir['path'] . '/fusion_image_placeholder_16x9_h2000.png';
	}

	public function tearDown() {
		parent::tearDown();

		unlink( $this->image_path );
	}
	// @codingStandardsIgnoreEnd

	public function test_post_display() {
		$attachment_id = $this->attachment_id;
		$post_id = $this->factory->post->create( array( 'post_content' => '[image-comparison left="' . $attachment_id . '" right="' . $attachment_id . '"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<section class="image-comparison">', apply_filters( 'the_content', $post->post_content ) );
	}

	/**
	 * Helper function: insert an attachment to test properties of.
	 *
	 * @param int $parent_post_id
	 * @param str path to image to use
	 * @param array $post_fields Fields, in the format to be sent to `wp_insert_post()`
	 * @return int Post ID of inserted attachment
	 */
	private function insert_attachment( $parent_post_id = 0, $image = null, $post_fields = array() ) {

		$filename = rand_str().'.jpg';
		$contents = rand_str();

		if ( $image ) {
			// @codingStandardsIgnoreStart
			$filename = basename( $image );
			$contents = file_get_contents( $image );
			// @codingStandardsIgnoreEnd
		}

		$upload = wp_upload_bits( $filename, null, $contents );
		$this->assertTrue( empty( $upload['error'] ) );

		$type = '';
		if ( ! empty( $upload['type'] ) ) {
			$type = $upload['type'];
		} else {
			$mime = wp_check_filetype( $upload['file'] );
			if ( $mime ) {
				$type = $mime['type'];
			}
		}

		$attachment = wp_parse_args( $post_fields,
			array(
				'post_title' => basename( $upload['file'] ),
				'post_content' => 'Test Attachment',
				'post_type' => 'attachment',
				'post_parent' => $parent_post_id,
				'post_mime_type' => $type,
				'guid' => $upload['url'],
			)
		);

		// Save the data
		$id = wp_insert_attachment( $attachment, $upload['file'], $parent_post_id );
		wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );

		return $id;
	}

}
