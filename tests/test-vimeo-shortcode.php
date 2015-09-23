<?php

class Test_Vimeo_Shortcode extends WP_UnitTestCase {

	public function test_post_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[vimeo url="https://vimeo.com/24483780"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe class="shortcake-bakery-responsive" width="500" height="281" src="https://player.vimeo.com/video/24483780" frameborder="0" allowfullscreen></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_embed_reversal() {
		$old_content = <<<EOT
		apples before

		<iframe src="//player.vimeo.com/video/24483780" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>

		bananas after
EOT;

		$expected_content = <<<EOT
		apples before

		[vimeo url="https://vimeo.com/24483780"]

		bananas after
EOT;

		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertEquals( $expected_content, $transformed_content );
	}

}
