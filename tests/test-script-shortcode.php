<?php

class Test_Script_Shortcode extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
		add_filter( 'shortcake_bakery_whitelisted_script_domains', function(){
			return array(
				'3vot.com',
				'ajax.googleapis.com',
			);
		});
	}

	public function test_post_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[script src="//3vot.com/fusion/waittimes/3vot.js"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<script src="//3vot.com/fusion/waittimes/3vot.js"></script>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_display_invalid_domain() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[script src="//baddomain.com/malicious.js"][/script]' ) );
		$post = get_post( $post_id );
		$this->assertEmpty( trim( apply_filters( 'the_content', $post->post_content ) ) );
	}
	public function test_embed_reversal() {
		$old_content = <<<EOT

		apples before

		<script src="//3vot.com/fusion/waittimes/3vot.js"></script>

		bananas after
EOT;
		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertContains( '[script src="//3vot.com/fusion/waittimes/3vot.js"]', $transformed_content );
		$this->assertContains( 'apples before', $transformed_content );
		$this->assertContains( 'bananas after', $transformed_content );

	}

	public function test_embed_double_reversal() {
		$old_content = <<<EOT

		apples before

		<script src="//3vot.com/fusion/waittimes/3vot.js"></script>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

		bananas after
EOT;
		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertContains( '[script src="//3vot.com/fusion/waittimes/3vot.js"]', $transformed_content );
		$this->assertContains( '[script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"]', $transformed_content );
		$this->assertContains( 'apples before', $transformed_content );
		$this->assertContains( 'bananas after', $transformed_content );

	}
	public function test_embed_non_reversal() {
		$old_content = '<script src="//baddomain.com/malicious.js"></script>';
		$transformed_content = wp_filter_post_kses( $old_content );
		$this->assertEmpty( trim( apply_filters( 'the_content', $transformed_content ) ) );
	}

	public function test_embed_double_non_reversal() {
		$old_content = '<script src="//baddomain.com/malicious.js"></script> <script src="//hackers.com/malicious.js"></script>';
		$transformed_content = wp_filter_post_kses( $old_content );
		$this->assertEmpty( trim( apply_filters( 'the_content', $transformed_content ) ) );
	}

	public function test_embed_non_reversal_with_http() {
		$old_content = '<script src="http://baddomain.com/malicious.js"></script>';
		$transformed_content = wp_filter_post_kses( $old_content );
		$this->assertEmpty( trim( apply_filters( 'the_content', $transformed_content ) ) );
	}

}
