<?php

class Test_YouTube_Shortcode extends WP_UnitTestCase {

	public function test_post_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[youtube url="https://www.youtube.com/watch?v=hDlpVFDmXrc"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe class="shortcake-bakery-responsive" width="640" height="360" src="https://youtube.com/embed/hDlpVFDmXrc" frameborder="0"></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_post_display_with_playlist() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[youtube url="https://www.youtube.com/watch?v=r34ust62leA&list=PLxd0bZ1RXEzvZnP-sC7Byj_a1dFGHF3xt"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe class="shortcake-bakery-responsive" width="640" height="360" src="https://youtube.com/embed/r34ust62leA?list=PLxd0bZ1RXEzvZnP-sC7Byj_a1dFGHF3xt" frameborder="0"></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_post_display_with_playlist_encoded() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[youtube url="https://www.youtube.com/watch?v=r34ust62leA&#038;list=PLxd0bZ1RXEzvZnP-sC7Byj_a1dFGHF3xt"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe class="shortcake-bakery-responsive" width="640" height="360" src="https://youtube.com/embed/r34ust62leA?list=PLxd0bZ1RXEzvZnP-sC7Byj_a1dFGHF3xt" frameborder="0"></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_youtube_embed_url_filter() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[youtube url="https://www.youtube.com/watch?v=hDlpVFDmXrc"]' ) );
		$post = get_post( $post_id );
		$filter = function( $embed_url ) {
			return add_query_arg( array(
				'rel'       => 0,
				'showinfo'  => 0,
				), $embed_url );
		};
		add_filter( 'shortcake_bakery_youtube_embed_url', $filter );
		$this->assertContains( '<iframe class="shortcake-bakery-responsive" width="640" height="360" src="https://youtube.com/embed/hDlpVFDmXrc?rel=0&#038;showinfo=0" frameborder="0"></iframe>', apply_filters( 'the_content', $post->post_content ) );
		remove_filter( 'shortcake_bakery_youtube_embed_url', $filter );
	}

	public function test_embed_reversal() {
		$old_content = <<<EOT
		apples before

		<iframe width="640" height="360" src="https://www.youtube.com/embed/ogWhVMa0jCM?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>

		bananas after
EOT;

		$expected_content = <<<EOT
		apples before

		[youtube url="https://www.youtube.com/watch?v=ogWhVMa0jCM"]

		bananas after
EOT;

		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertEquals( $expected_content, $transformed_content );
	}

}
