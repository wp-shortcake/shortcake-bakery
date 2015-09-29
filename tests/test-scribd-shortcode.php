<?php

class Test_Scribd_Shortcode extends WP_UnitTestCase {

	public function test_post_display() {
		$doc_url = 'http://www.scribd.com/doc/269993358/Transgender-Care-Memorandum';
		$embed_url = 'https://www.scribd.com/embeds/269993358/content?start_page=1&view_mode=scroll&access_key=key-ooxdrkmSg8ieauz9qYXL&show_recommendations=true';
		$post_id = $this->factory->post->create( array( 'post_content' => '[scribd url="' . $doc_url . '"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe class="scribd_iframe_embed shortcake-bakery-responsive" src="' . esc_url( $embed_url ) . '" data-auto-height="false" data-aspect-ratio="0.7631133671742809" scrolling="no" width="100%" height="600" frameborder="0"></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_embed_reversal() {
		$old_content = <<<EOT

		apples before

		<iframe class="scribd_iframe_embed" src="https://www.scribd.com/embeds/269993358/content?start_page=1&view_mode=scroll&access_key=key-ooxdrkmSg8ieauz9qYXL&show_recommendations=true" data-auto-height="false" data-aspect-ratio="0.7631133671742809" scrolling="no" id="doc_16187" width="100%" height="600" frameborder="0"></iframe>

		bananas after
EOT;
		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertContains( '[scribd url="https://www.scribd.com/doc/269993358/"]', $transformed_content );
		$this->assertContains( 'apples before', $transformed_content );
		$this->assertContains( 'bananas after', $transformed_content );
	}

	public function test_embed_reversal_part_two() {
		$old_content = <<<EOT

		apples before

		<iframe id="doc_94884" class="scribd_iframe_embed" src="https://www.scribd.com/embeds/272220183/content?start_page=1&amp;view_mode=scroll&amp;show_recommendations=true" width="100%" height="600" frameborder="0" scrolling="no" data-auto-height="false" data-aspect-ratio="undefined"></iframe>

		bananas after
EOT;
		$expected_content = <<<EOT

		apples before

		[scribd url="https://www.scribd.com/doc/272220183/"]

		bananas after
EOT;
		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertEquals( $expected_content, $transformed_content );
	}

}
