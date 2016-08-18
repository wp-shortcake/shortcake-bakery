<?php

class Test_Iframe_Shortcode extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
		$this->filter_callback = function() {
			return array(
				'assets.fusion.net',
				'static.fusion.net',
				'interactive.fusion.net',
				'nonsecure-assets.fusion.net',
				'secure-assets.fusion.net',
			);
		};
		add_filter( 'shortcake_bakery_whitelisted_iframe_domains', $this->filter_callback );

		$this->filter_src_callback = function( $src ) {
			return str_replace( 'http://nonsecure-assets.fusion.net', 'https://secure-assets.fusion.net', $src );
		};
	}

	public function test_post_display_valid_domain() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[iframe src="//static.fusion.net/the-ultimate-choice/"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe src="//static.fusion.net/the-ultimate-choice/" width="670" height="600" data-true-width="670" data-true-height="600" frameborder="0" scrolling="no" class="shortcake-bakery-responsive"></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_iframe_height_parameter() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[iframe height="900" src="//static.fusion.net/the-ultimate-choice/"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe src="//static.fusion.net/the-ultimate-choice/" width="670" height="900" data-true-width="670" data-true-height="900" frameborder="0" scrolling="no" class="shortcake-bakery-responsive"></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_iframe_height_width_parameter() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[iframe height="900" width="1000" src="//static.fusion.net/the-ultimate-choice/"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe src="//static.fusion.net/the-ultimate-choice/" width="1000" height="900" data-true-width="1000" data-true-height="900" frameborder="0" scrolling="no" class="shortcake-bakery-responsive"></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_iframe_responsive_optout() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[iframe disableresponsiveness="true" src="//static.fusion.net/the-ultimate-choice/"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe src="//static.fusion.net/the-ultimate-choice/" width="670" height="600" data-true-width="670" data-true-height="600" frameborder="0" scrolling="no" class=""></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_display_invalid_domain() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[iframe src="http://notvalid.fusion.net/leonardo-dicaprio/"]' ) );
		$post = get_post( $post_id );
		$this->assertEmpty( trim( apply_filters( 'the_content', $post->post_content ) ) );
	}

	public function test_reversal_valid_domain() {
		$old_content = <<<EOT
		apples before

		<iframe src="//static.fusion.net/the-ultimate-choice/"></iframe>

		apples after
EOT;
		$expected_content = <<<EOT
		apples before

		[iframe src="//static.fusion.net/the-ultimate-choice/"]

		apples after
EOT;

		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertEquals( $expected_content, $transformed_content );
	}

	public function test_filter_iframe_source() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[iframe src="http://nonsecure-assets.fusion.net/"]' ) );
		$post = get_post( $post_id );
		$filtered_content = apply_filters( 'the_content', $post->post_content );

		// Assert no changes made before filter applied
		$this->assertNotContains( 'https://secure-assets.fusion.net', $filtered_content );
		$this->assertContains( 'http://nonsecure-assets.fusion.net', $filtered_content );

		add_filter( 'shortcake_bakery_iframe_src', $this->filter_src_callback );
		$filtered_content = apply_filters( 'the_content', $post->post_content );

		// Assert secure hostname used after filter applied
		$this->assertNotContains( 'http://nonsecure-assets.fusion.net', $filtered_content );
		$this->assertContains( 'https://secure-assets.fusion.net', $filtered_content );

		remove_filter( 'shortcake_bakery_iframe_src', $this->filter_src_callback );
	}

	public function tearDown() {
		parent::tearDown();
		remove_filter( 'shortcake_bakery_whitelisted_iframe_domains', $this->filter_callback );
	}

}
