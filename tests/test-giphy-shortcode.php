<?php

class Test_Giphy_Shortcode extends WP_UnitTestCase {

	public function test_post_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[giphy url="http://giphy.com/gifs/jtvedit-jtv-rogelio-de-la-vega-ihfrhIgdkQ83C"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe src="//giphy.com/embed/ihfrhIgdkQ83C" frameBorder="0" width="500" height="350" class="giphy-embed shortcake-bakery-responsive" allowFullScreen></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_post_display_height_width() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[giphy url="http://giphy.com/gifs/jtvedit-jtv-rogelio-de-la-vega-ihfrhIgdkQ83C" width="640" height="480"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe src="//giphy.com/embed/ihfrhIgdkQ83C" frameBorder="0" width="640" height="480" class="giphy-embed shortcake-bakery-responsive" allowFullScreen></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_display_url_without_hyphens() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[giphy url="http://giphy.com/gifs/FcHcQIpQD5Bmg" width="480" height="342" ]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe src="//giphy.com/embed/FcHcQIpQD5Bmg" frameBorder="0" width="480" height="342" class="giphy-embed shortcake-bakery-responsive" allowFullScreen></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_embed_reversal_with_extra_html() {
		$old_content = <<<EOT
		apples before

		<iframe src="//giphy.com/embed/ihfrhIgdkQ83C" width="480" height="293" frameBorder="0" class="giphy-embed" allowFullScreen></iframe><p><a href="http://giphy.com/gifs/jtvedit-jtv-rogelio-de-la-vega-ihfrhIgdkQ83C">via GIPHY</a></p>

		bananas after
EOT;

		$expected_content = <<<EOT
		apples before

		[giphy url="http://giphy.com/gifs/ihfrhIgdkQ83C" width="480" height="293"]

		bananas after
EOT;

		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertEquals( $expected_content, $transformed_content );
	}

	public function test_embed_reversal_without_extra_html() {
		$old_content = <<<EOT
		apples before

		<iframe src="//giphy.com/embed/ihfrhIgdkQ83C" width="480" height="293" frameBorder="0" class="giphy-embed" allowFullScreen></iframe>

		bananas after
EOT;

		$expected_content = <<<EOT
		apples before

		[giphy url="http://giphy.com/gifs/ihfrhIgdkQ83C" width="480" height="293"]

		bananas after
EOT;

		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertEquals( $expected_content, $transformed_content );
	}

}
