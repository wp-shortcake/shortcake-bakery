<?php

class Test_Silk_Shortcode extends WP_UnitTestCase {

	public function test_post_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[silk url="https://us-states-with-hiv-specific-criminal-laws.silk.co/s/embed/map/collection/states-with-hiv-specific-criminal-laws-1/location/title/on/silk.co/order/asc/states-with-hiv-specific-criminal-law"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe class="shortcake-bakery-responsive" width="600" height="600" src="https://us-states-with-hiv-specific-criminal-laws.silk.co/s/embed/map/collection/states-with-hiv-specific-criminal-laws-1/location/title/on/silk.co/order/asc/states-with-hiv-specific-criminal-law" frameborder="0"></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_post_display_responsive_size_attribute() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[silk size="responsive" url="https://us-states-with-hiv-specific-criminal-laws.silk.co/s/embed/map/collection/states-with-hiv-specific-criminal-laws-1/location/title/on/silk.co/order/asc/states-with-hiv-specific-criminal-law"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe class="shortcake-bakery-responsive" width="600" height="600" src="https://us-states-with-hiv-specific-criminal-laws.silk.co/s/embed/map/collection/states-with-hiv-specific-criminal-laws-1/location/title/on/silk.co/order/asc/states-with-hiv-specific-criminal-law" frameborder="0"></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_post_display_with_fixed_size_attribute() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[silk size="600x100%" url="https://us-states-with-hiv-specific-criminal-laws.silk.co/s/embed/map/collection/states-with-hiv-specific-criminal-laws-1/location/title/on/silk.co/order/asc/states-with-hiv-specific-criminal-law"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe class="" width="100%" height="600" src="https://us-states-with-hiv-specific-criminal-laws.silk.co/s/embed/map/collection/states-with-hiv-specific-criminal-laws-1/location/title/on/silk.co/order/asc/states-with-hiv-specific-criminal-law" frameborder="0"></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_post_display_invalid_size_attribute() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[silk size="100x100xscript" url="https://us-states-with-hiv-specific-criminal-laws.silk.co/s/embed/map/collection/states-with-hiv-specific-criminal-laws-1/location/title/on/silk.co/order/asc/states-with-hiv-specific-criminal-law"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<iframe class="shortcake-bakery-responsive" width="600" height="600" src="https://us-states-with-hiv-specific-criminal-laws.silk.co/s/embed/map/collection/states-with-hiv-specific-criminal-laws-1/location/title/on/silk.co/order/asc/states-with-hiv-specific-criminal-law" frameborder="0"></iframe>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_embed_reversal() {
		$old_content = <<<EOT

		apples before

		<iframe src="//us-states-with-hiv-specific-criminal-laws.silk.co/s/embed/map/collection/states-with-hiv-specific-criminal-laws-1/location/title/on/silk.co/order/asc/states-with-hiv-specific-criminal-law" width="600" height="600" style="height:600px;width:600px;border:0;"></iframe><div style='margin-left:5px;position:relative;margin-top:-33px;margin-bottom:10px;font-size:14px;color:gray;text-align:left;width:50%;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;'>Data from <a target='_blank' style='text-decoration:none;'href='http://us-states-with-hiv-specific-criminal-laws.silk.co'>us-states-with-hiv-specific-criminal-laws.silk.co</a></div>

		bananas after
EOT;
		$expected_content = <<<EOT

		apples before

		[silk url="//us-states-with-hiv-specific-criminal-laws.silk.co/s/embed/map/collection/states-with-hiv-specific-criminal-laws-1/location/title/on/silk.co/order/asc/states-with-hiv-specific-criminal-law"]

		bananas after
EOT;
		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertEquals( $expected_content, $transformed_content );
	}

}
