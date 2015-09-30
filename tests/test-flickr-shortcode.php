<?php

class Test_Flickr_Shortcode extends WP_UnitTestCase {

	public function test_post_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[flickr url="https://www.flickr.com/photos/aclfestival/15432668822/in/set-72157648250335862"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe class="shortcake-bakery-responsive" width="500" height="334" src="https://www.flickr.com/photos/aclfestival/15432668822/in/set-72157648250335862/player/" frameborder="0"></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_embed_reversal() {
		$old_content = <<<EOT
		apples before

		<iframe src="https://www.flickr.com/photos/aclfestival/15432668822/in/set-72157648250335862/player/" width="500" height="334" frameborder="0" allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>

		bananas after
EOT;

		$expected_content = <<<EOT
		apples before

		[flickr url="https://www.flickr.com/photos/aclfestival/15432668822/in/set-72157648250335862/"]

		bananas after
EOT;

		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertEquals( $expected_content, $transformed_content );
	}

}
