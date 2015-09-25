<?php

class Test_Shortcode extends WP_UnitTestCase {

	public function test_parse_multiple_iframes() {

		$multiple_iframes = <<<EOT

		A single line of text

		<iframe src="http://foo.com" allowfullscreen></iframe>

		Another line of text

		<iframe src="http://bar.com"></iframe>

EOT;

		$parsed = Shortcode::parse_iframes( $multiple_iframes );
		$first_iframe = new stdClass;
		$first_iframe->original = '<iframe src="http://foo.com" allowfullscreen></iframe>';
		$first_iframe->attrs = array(
			'src'             => 'http://foo.com',
			'allowfullscreen' => null,
			);
		$this->assertEquals( $first_iframe, $parsed[0] );
		$second_iframe = new stdClass;
		$second_iframe->original = '<iframe src="http://bar.com"></iframe>';
		$second_iframe->attrs = array(
			'src'             => 'http://bar.com',
			);
		$this->assertEquals( $second_iframe, $parsed[1] );
	}

	public function test_parse_iframe_mixed_attribute_quoting() {
		$iframe_str = '<iframe src="http://foo.com" bar=\'apple\'></iframe>';
		$parsed = Shortcode::parse_iframes( $iframe_str );
		$iframe_obj = new stdClass;
		$iframe_obj->original = $iframe_str;
		$iframe_obj->attrs = array(
			'src'             => 'http://foo.com',
			'bar'             => 'apple'
			);
		$this->assertEquals( $iframe_obj, $parsed[0] );
	}

}
