<?php

class Test_Shortcake_Bakery_URL_Root extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
	}


	public function test_url_root() {
		$this->assertContains( SHORTCAKE_BAKERY_URL_ROOT, 'http://example.org/wp-content/plugins/shortcake-bakery/' );
	}

}
