<?php

class Test_SoundCloud_Shortcode extends WP_UnitTestCase {

	public function test_post_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[soundcloud url="https://soundcloud.com/wondalandarts/hell-you-talmbout"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A%2F%2Fsoundcloud.com%2Fwondalandarts%2Fhell-you-talmbout"></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_embed_reversal() {
		$old_content = <<<EOT
		apples before

		<iframe width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/219074591&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>

		bananas after
EOT;

		$expected_content = <<<EOT
		apples before

		[soundcloud url="https://soundcloud.com/tracks/219074591"]

		bananas after
EOT;

		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertEquals( $expected_content, $transformed_content );
	}

}
