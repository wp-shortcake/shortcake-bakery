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

	public function test_parse_iframe_spaces_in_attributes() {
		$iframe_str = '<iframe src="http://foo.com" class="class-one class-two"></iframe>';
		$parsed = Shortcode::parse_iframes( $iframe_str );
		$this->assertEquals( 'class-one class-two', $parsed[0]->attrs['class'] );
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

	public function test_parse_scripts() {
		$script_str = '<div id="wsd-root"></div>' . "\r\n" .
			'<script type="text/javascript" src="http://script-domain.net/assets/js/widget.js?id=3"></script>';
		$parsed = Shortcode::parse_scripts( $script_str );
		$expected = (object) array(
			'original' => '<script type="text/javascript" src="http://script-domain.net/assets/js/widget.js?id=3"></script>',
			'before' => '<div id="wsd-root"></div>' . "\r\n",
			'after' => '',
			'inner' => '',
			'attrs' => array(
				'type' => 'text/javascript',
				'src' => 'http://script-domain.net/assets/js/widget.js?id=3',
			),
		);
		$this->assertEquals( $expected, $parsed[0] );
	}

	public function test_make_replacements_to_content() {
		$original_content = <<<EOT
monkey see

<iframe src="http://foo.com">Why is there text in here?</iframe>

monkey do
EOT;
		$expected_content = <<<EOT
monkey see

[iframe src="http://foo.com"]

monkey do
EOT;
		$replacements = array( '<iframe src="http://foo.com">Why is there text in here?</iframe>' => '[iframe src="http://foo.com"]' );
		$this->assertEquals( $expected_content, Shortcode::make_replacements_to_content( $original_content, $replacements ) );
	}

	public function test_no_make_replacements_to_content() {
		$iframe_str = '<iframe src="http://foo.com">Why is there text in here?</iframe>';
		$this->assertEquals( $iframe_str, Shortcode::make_replacements_to_content( $iframe_str, array() ) );
	}

	public function test_parse_url() {
		$this->assertEquals( 'apple.com', Shortcode::parse_url( '//apple.com/foo', PHP_URL_HOST ) );
		$this->assertEquals( '/foo', Shortcode::parse_url( '//apple.com/foo', PHP_URL_PATH ) );
		$this->assertEquals( array(
			'host'           => 'apple.com',
			'path'           => '/foo',
		), Shortcode::parse_url( '//apple.com/foo' ) );
	}

}
