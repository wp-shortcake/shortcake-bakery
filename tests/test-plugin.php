<?php

/**
 * Generic tests for the plugin
 */
class Test_Plugin extends WP_UnitTestCase {

	public function test_plugin_load() {
		$this->assertTrue( class_exists( 'Shortcake_Bakery' ) );
	}

	public function test_embed_reversal() {
		$string = '<iframe width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/219074591&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>
					bananas after';

		$reversal_test = Shortcake_Bakery::get_instance()->embed_reversal( $string );
		$this->assertTrue( $reversal_test['success'] );
		$expected_shortcode = array(
			'shortcode' => 'soundcloud',
			'attributes' => array( 'url' => 'https://api.soundcloud.com/tracks/219074591' ),
			'inner_content' => null,
			'raw' => '[soundcloud url="https://api.soundcloud.com/tracks/219074591"]'
		);
		$this->assertEquals( $expected_shortcode, $reversal_test['shortcodes'][0] );
	}
}
