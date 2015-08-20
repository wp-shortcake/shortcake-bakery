<?php

class Test_Facebook_Shortcode extends WP_UnitTestCase {

	public function test_post_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[facebook url="https://www.facebook.com/willpd/posts/1001217146572688"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<div class="fb-post shortcake-bakery-responsive" data-href="https://www.facebook.com/willpd/posts/1001217146572688"', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_video_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[facebook url="https://www.facebook.com/video.php?v=1095405247152119"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<div class="fb-post shortcake-bakery-responsive" data-href="https://www.facebook.com/video.php?v=1095405247152119"', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_new_video_display() {
		$test_url = 'https://www.facebook.com/FusionSoccerGods/videos/vb.1425640711027150/1621903278067558/?type=2&theater';
		$post_id = $this->factory->post->create( array( 'post_content' => '[facebook url="' . $test_url . '"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<div class="fb-post shortcake-bakery-responsive" data-href="' . esc_url( $test_url ) . '"', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_new_photo_display() {
		$test_url = 'https://www.facebook.com/RichardBranson/photos/a.10151193550160872.451061.31325960871/10151193550380872/?type=3&theater';
		$post_id = $this->factory->post->create( array( 'post_content' => '[facebook url="' . $test_url . '"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<div class="fb-post shortcake-bakery-responsive" data-href="' . esc_url( $test_url ) . '"', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_facebook_permalink_display() {
		$test_url = 'https://www.facebook.com/permalink.php?story_fbid=544645288945655&id=539097822833735';
		$post_id = $this->factory->post->create( array( 'post_content' => '[facebook url="' . $test_url . '"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<div class="fb-post shortcake-bakery-responsive" data-href="' . esc_url( $test_url ) . '"', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_facebook_group_permalink_display() {
		$test_url = 'https://www.facebook.com/groups/1487896971464517/permalink/1609354979318715/';
		$post_id = $this->factory->post->create( array( 'post_content' => '[facebook url="' . $test_url . '"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<div class="fb-post shortcake-bakery-responsive" data-href="' . esc_url( $test_url ) . '"', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_pretty_permalink_video_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[facebook url="https://www.facebook.com/coreycf/videos/953479961370562/"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<div class="fb-post shortcake-bakery-responsive" data-href="https://www.facebook.com/coreycf/videos/953479961370562/"', apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_embed_reversal() {
		$old_content = <<<EOT

		apples before

		<div id="fb-root"></div><script>(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";  fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script><div class="fb-post" data-href="https://www.facebook.com/video.php?v=1095405247152119" data-width="466"><div class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/video.php?v=1095405247152119">Post</a> by <a href="https://www.facebook.com/fusionmedianetwork">Fusion</a>.</div></div>

		bananas after
EOT;
		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertContains( '[facebook url="https://www.facebook.com/video.php?v=1095405247152119"]', $transformed_content );
		$this->assertContains( 'apples before', $transformed_content );
		$this->assertContains( 'bananas after', $transformed_content );

	}

	public function test_video_embed_reversal() {
		$old_content = <<<EOT

		apples before

		<div id="fb-root"></div><script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3"; fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script><div class="fb-video" data-allowfullscreen="1" data-href="/coreycf/videos/vb.100001257008891/953479961370562/?type=1"><div class="fb-xfbml-parse-ignore"><blockquote cite="/coreycf/videos/953479961370562/"><a href="/coreycf/videos/953479961370562/"></a><p>Here&#039;s the free styling he put on lol Brent John Janis Franklin Sioux Bob Nate Badmilk</p>Posted by <a href="https://www.facebook.com/coreycf">Corey James</a> on Saturday, June 27, 2015</blockquote></div></div>

		bananas after
EOT;
		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertContains( '[facebook url="https://www.facebook.com/coreycf/videos/953479961370562/"]', $transformed_content );
		$this->assertContains( 'apples before', $transformed_content );
		$this->assertContains( 'bananas after', $transformed_content );

	}

	public function test_photo_embed_reversal() {
		$old_content = <<<EOT

		apples before

		<div id="fb-root"></div><script>(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";  fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script><div class="fb-post" data-href="https://www.facebook.com/RichardBranson/photos/a.10151193550160872.451061.31325960871/10151193550380872/?type=1" data-width="500"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/RichardBranson/photos/a.10151193550160872.451061.31325960871/10151193550380872/?type=1">Posted by <a href="https://www.facebook.com/RichardBranson">Richard Branson</a> on&nbsp;<a href="https://www.facebook.com/RichardBranson/photos/a.10151193550160872.451061.31325960871/10151193550380872/?type=1">Thursday, January 17, 2013</a></blockquote></div></div>

		bananas after
EOT;
		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertContains( '[facebook url="https://www.facebook.com/RichardBranson/photos/a.10151193550160872.451061.31325960871/10151193550380872/?type=1"]', $transformed_content );
		$this->assertContains( 'apples before', $transformed_content );
		$this->assertContains( 'bananas after', $transformed_content );

	}

}
