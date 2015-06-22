<?php

class Test_Rap_Genius_Shortcode extends WP_UnitTestCase {

	public function test_post_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[rap-genius]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<script async src="https://genius.codes"></script>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_embed_reversal() {
		$old_content = <<<EOT

		apples before

		<script async src="https://genius.codes"></script>

		bananas after
EOT;
		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertContains( '[rap-genius]', $transformed_content );
		$this->assertContains( 'apples before', $transformed_content );
		$this->assertContains( 'bananas after', $transformed_content );

	}

}
