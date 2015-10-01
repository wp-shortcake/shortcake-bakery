<?php

class Test_Guardian_Shortcode extends WP_UnitTestCase {

	public function test_post_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[guardian url="http://www.theguardian.com/us-news/video/2015/sep/17/ahmed-mohamed-father-move-schools-arrest-homemade-clock-video"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe class="shortcake-bakery-responsive" width="560" height="315" src="https://embed.theguardian.com/embed/video/us-news/video/2015/sep/17/ahmed-mohamed-father-move-schools-arrest-homemade-clock-video" frameborder="0"></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_embed_reversal() {
		$old_content = <<<EOT
		apples before

		<iframe src="https://embed.theguardian.com/embed/video/us-news/video/2015/sep/17/ahmed-mohamed-father-move-schools-arrest-homemade-clock-video" width="560" height="315" frameborder="0" allowfullscreen></iframe>

		bananas after
EOT;

		$expected_content = <<<EOT
		apples before

		[guardian url="http://www.theguardian.com/us-news/video/2015/sep/17/ahmed-mohamed-father-move-schools-arrest-homemade-clock-video"]

		bananas after
EOT;

		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertEquals( $expected_content, $transformed_content );
	}

}
