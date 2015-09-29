<?php

class Test_Infogram_Shortcode extends WP_UnitTestCase {

	public function test_post_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[infogram url="http://infogr.am/washington_marijuana_sales"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<script async src="//e.infogr.am/js/embed.js" id="infogram_0_washington_marijuana_sales" type="text/javascript"></script>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_embed_reversal() {
		$old_content = <<<EOT

		apples before

		<script id="infogram_0_washington_marijuana_sales" src="//e.infogr.am/js/embed.js?hbf" type="text/javascript"></script>

		bananas after
EOT;
		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertContains( '[infogram url="https://infogr.am/washington_marijuana_sales"]', $transformed_content );
		$this->assertContains( 'apples before', $transformed_content );
		$this->assertContains( 'bananas after', $transformed_content );

	}

}
