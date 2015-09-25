<?php

class Test_Vine_Shortcode extends WP_UnitTestCase {

	public function test_post_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[vine url="https://vine.co/v/e5t3e7rHxvu"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe class="shortcake-bakery-responsive" src="https://vine.co/v/e5t3e7rHxvu/embed/simple" width="600" height="600" frameborder="0"></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_post_display_with_embed_in_url() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[vine url="https://vine.co/v/e5t3e7rHxvu/embed"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe class="shortcake-bakery-responsive" src="https://vine.co/v/e5t3e7rHxvu/embed/simple" width="600" height="600" frameborder="0"></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_embed_reversal() {
		$old_content = <<<EOT
		apples before

		<iframe src="https://vine.co/v/e5t3e7rHxvu/embed/simple" width="600" height="600" frameborder="0"></iframe>

		bananas after
EOT;

		$expected_content = <<<EOT
		apples before

		[vine url="https://vine.co/v/e5t3e7rHxvu"]

		bananas after
EOT;

		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertEquals( $expected_content, $transformed_content );
	}

	public function test_embed_reversal_with_trailing_nbsp() {
		$old_content = '<iframe src="https://vine.co/v/eMnmixYtwr1/embed/simple" width="300" height="300" frameborder="0">&nbsp;';
		$expected_content = '[vine url="https://vine.co/v/eMnmixYtwr1"]';
		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertEquals( $expected_content, $transformed_content );
	}

}
