<?php

class Test_ABC_News_Shortcode extends WP_UnitTestCase {

	public function test_post_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[abc-news url="http://abcnews.go.com/video/embed?id=33317297"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe class="shortcake-bakery-responsive" width="640" height="480" src="http://abcnews.go.com/video/embed?id=33317297" frameborder="0"></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_embed_reversal() {
		$old_content = <<<EOT
		apples before

		<iframe src='http://abcnews.go.com/video/embed?id=33317297' width='640' height='360' scrolling='no' style='border:none;'></iframe>

		bananas after
EOT;

		$expected_content = <<<EOT
		apples before

		[abc-news url="http://abcnews.go.com/video/embed?id=33317297"]

		bananas after
EOT;

		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertEquals( $expected_content, $transformed_content );
	}

}
