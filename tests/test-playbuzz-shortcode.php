<?php

class Test_Playbuzz_Shortcode extends WP_UnitTestCase {

	public function test_post_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[playbuzz url="https://www.playbuzz.com/Fusion/5-mind-blowing-facts-about-cloning-from-jurassic-park-youll-never-believe-actually-exist-in-real"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( '<div class="pb_feed" data-height="auto" data-game="/Fusion/5-mind-blowing-facts-about-cloning-from-jurassic-park-youll-never-believe-actually-exist-in-real" data-tags="All" data-recommend="false" data-margin-top="0" data-game-info="true" data-comments="false" data-shares="false" data-key="Default"></div>', apply_filters( 'the_content', $post->post_content ) );

	}

	public function test_embed_reversal() {
		$old_content = <<<EOT
		<script type="text/javascript" src="//cdn.playbuzz.com/widget/feed.js"></script>
<div class="pb_feed" data-height="auto" data-game="/Fusion/5-mind-blowing-facts-about-cloning-from-jurassic-park-youll-never-believe-actually-exist-in-real" data-tags="All" data-recommend="true" data-margin-top="0" data-game-info="true" data-comments="true" data-shares="true" data-embed-by="0ebde15d-4750-4d3e-bf25-5a3445bb304b" data-key="Default"></div>
EOT;
		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertContains( '[playbuzz url="https://www.playbuzz.com/Fusion/5-mind-blowing-facts-about-cloning-from-jurassic-park-youll-never-believe-actually-exist-in-real" recommend="true" comments="true" shares="true"]', $transformed_content );
		$this->assertFalse( strpos( $transformed_content, '<script type="text/javascript" src="//cdn.playbuzz.com/widget/feed.js">' ) );
	}

}
