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
		$first_iframe->before = '';
		$first_iframe->after = '';
		$first_iframe->inner = '';
		$first_iframe->attrs = array(
			'src'             => 'http://foo.com',
			'allowfullscreen' => null,
			);
		$this->assertEquals( $first_iframe, $parsed[0] );
		$second_iframe = new stdClass;
		$second_iframe->original = '<iframe src="http://bar.com"></iframe>';
		$second_iframe->before = '';
		$second_iframe->after = '';
		$second_iframe->inner = '';
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
		$iframe_obj->before = '';
		$iframe_obj->after = '';
		$iframe_obj->inner = '';
		$iframe_obj->attrs = array(
			'src'             => 'http://foo.com',
			'bar'             => 'apple',
			);
		$this->assertEquals( $iframe_obj, $parsed[0] );
	}

	public function test_parse_iframe_content_after() {
		$iframe_str = '<iframe src="//giphy.com/embed/ihfrhIgdkQ83C" width="480" height="293" allowFullScreen></iframe><p><a href="http://giphy.com/gifs/jtvedit-jtv-rogelio-de-la-vega-ihfrhIgdkQ83C">via GIPHY</a></p>';
		$parsed = Shortcode::parse_iframes( $iframe_str );
		$iframe_obj = new stdClass;
		$iframe_obj->original = '<iframe src="//giphy.com/embed/ihfrhIgdkQ83C" width="480" height="293" allowFullScreen></iframe>';
		$iframe_obj->before = '';
		$iframe_obj->after = '<p><a href="http://giphy.com/gifs/jtvedit-jtv-rogelio-de-la-vega-ihfrhIgdkQ83C">via GIPHY</a></p>';
		$iframe_obj->inner = '';
		$iframe_obj->attrs = array(
			'src'             => '//giphy.com/embed/ihfrhIgdkQ83C',
			'width'           => '480',
			'height'          => '293',
			'allowFullScreen' => null,
			);
		$this->assertEquals( $iframe_obj, $parsed[0] );
	}

	public function test_parse_iframe_content_inner() {
		$iframe_str = '<iframe src="http://foo.com">Why is there text in here?</iframe>';
		$parsed = Shortcode::parse_iframes( $iframe_str );
		$iframe_obj = new stdClass;
		$iframe_obj->original = $iframe_str;
		$iframe_obj->before = '';
		$iframe_obj->after = '';
		$iframe_obj->inner = 'Why is there text in here?';
		$iframe_obj->attrs = array(
			'src'             => 'http://foo.com',
			);
		$this->assertEquals( $iframe_obj, $parsed[0] );
	}

}
