<?php

class Test_Script_Shortcode extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
		add_filter( 'shortcake_bakery_whitelisted_script_domains', function(){
			return array(
				'3vot.com',
			);
		});
	}

	public function test_post_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[script src="//3vot.com/fusion/waittimes/3vot.js"][/script]' ) );
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
		$this->assertContains( '[script src="//3vot.com/fusion/waittimes/3vot.js"][/script]', $transformed_content );
		$this->assertContains( 'apples before', $transformed_content );
		$this->assertContains( 'bananas after', $transformed_content );

	}

}
