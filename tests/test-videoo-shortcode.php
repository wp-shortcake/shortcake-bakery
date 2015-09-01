<?php

class Test_Videoo_Shortcode extends WP_UnitTestCase {

	public function test_post_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[videoo url="https://videoo.com/w/spa/444"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<script src="https://videoo.com/w/spa/444?embed=1"></script>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_embed_reversal() {
		$old_content = <<<EOT

		apples before

		<script src="https://videoo.com/w/spa/444?embed=1"></script>

		bananas after
EOT;
		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertContains( '[videoo url="https://videoo.com/w/spa/444"]', $transformed_content );
		$this->assertContains( 'apples before', $transformed_content );
		$this->assertContains( 'bananas after', $transformed_content );

	}

}
