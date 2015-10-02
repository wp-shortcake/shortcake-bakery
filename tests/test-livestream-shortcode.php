<?php

class Test_Livestream_Shortcode extends WP_UnitTestCase {

	public function test_post_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[livestream url="http://new.livestream.com/accounts/9035483/events/3424523/videos/64460770/"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe class="shortcake-bakery-responsive" width="560" height="315" src="http://new.livestream.com/accounts/9035483/events/3424523/videos/64460770/player/" frameborder="0"></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_embed_reversal() {
		$old_content = <<<EOT

		apples before

		<iframe src="http://new.livestream.com/accounts/9035483/events/3424523/videos/64460770/player?width=480&height=270&autoPlay=false&mute=false" width="480" height="270" frameborder="0" scrolling="no"></iframe>

		bananas after
EOT;
		$expected_content = <<<EOT

		apples before

		[livestream url="https://livestream.com/accounts/9035483/events/3424523/videos/64460770/"]

		bananas after
EOT;
		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertEquals( $expected_content, $transformed_content );
	}

}
