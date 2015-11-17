<?php

/**
 * Generic tests for the plugin
 */
class Test_Plugin extends WP_UnitTestCase {

	public function test_plugin_load() {
		$this->assertTrue( class_exists( 'Shortcake_Bakery' ) );
	}

}
