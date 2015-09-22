<?php

class Test_Twitter_Shortcode extends WP_UnitTestCase {

	public function test_post_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[twitter url="https://twitter.com/readerer/status/634107326434152448"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<blockquote class="twitter-tweet"><a href="https://twitter.com/readerer/status/634107326434152448">Tweet from @readerer</a></blockquote>', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_embed_reversal() {
		$old_content = <<<EOT
		apples before

		<blockquote class="twitter-tweet" lang="en"><p lang="en" dir="ltr">Why is the name of the female sex-booster-drug not something cool like Gyntasy or Estrapex? What words is <a href="https://twitter.com/hashtag/Addyi?src=hash">#Addyi</a> even portmanteauing?</p>&mdash; Rachel Riederer (@readerer) <a href="https://twitter.com/readerer/status/634107326434152448">August 19, 2015</a></blockquote>
<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>

		bananas after
EOT;

		$expected_content = <<<EOT
		apples before

		[twitter url="https://twitter.com/readerer/status/634107326434152448"]

		bananas after
EOT;

		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertEquals( $expected_content, $transformed_content );
	}

}
