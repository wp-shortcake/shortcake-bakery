<?php

class Test_SoundCloud_Shortcode extends WP_UnitTestCase {

	public function test_post_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[soundcloud url="https://soundcloud.com/wondalandarts/hell-you-talmbout"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A%2F%2Fsoundcloud.com%2Fwondalandarts%2Fhell-you-talmbout"></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_post_display_api_url() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[soundcloud url="https://api.soundcloud.com/tracks/219074591"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F219074591"></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_post_display_autoplay_visual() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[soundcloud url="https://soundcloud.com/wondalandarts/hell-you-talmbout" type="visual" autoplay="1"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A%2F%2Fsoundcloud.com%2Fwondalandarts%2Fhell-you-talmbout&#038;visual=true&#038;auto_play=true"></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_embed_reversal() {
		$old_content = <<<EOT
		apples before

		<iframe width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/219074591&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>

		bananas after
EOT;

		$expected_content = <<<EOT
		apples before

		[soundcloud url="https://api.soundcloud.com/tracks/219074591" type="visual" autoplay="0"]

		bananas after
EOT;

		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertEquals( $expected_content, $transformed_content );
	}

}
