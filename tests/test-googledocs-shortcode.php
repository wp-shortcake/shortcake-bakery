<?php

class Test_Google_Docs_Shortcode extends WP_UnitTestCase {

	public function test_document_reversal() {
		$this->expect_reversal(
			'<iframe src="https://docs.google.com/document/d/1TwnxIligMjh1FLa1AWCB7F4xstvLrYrhJFqPqObvmK8/pub?embedded=true"></iframe>',
			'[googledocs url="https://docs.google.com/document/d/1TwnxIligMjh1FLa1AWCB7F4xstvLrYrhJFqPqObvmK8"]'
		);
	}

	public function test_document_callback() {
		$this->expect_callback(
			'[googledocs url="https://docs.google.com/document/d/1TwnxIligMjh1FLa1AWCB7F4xstvLrYrhJFqPqObvmK8"]',
			'<iframe class="shortcake-bakery-googledocs-document shortcake-bakery-responsive" src="https://docs.google.com/document/d/1TwnxIligMjh1FLa1AWCB7F4xstvLrYrhJFqPqObvmK8/pubhtml?embedded=true" frameborder="0" marginheight="0" marginwidth="0" ></iframe>'
		);
	}

	public function test_spreadsheet_reversal() {
		$this->expect_reversal(
			'<iframe width="400" height="800" src="https://docs.google.com/spreadsheets/d/1mtvInQiuHtJMjcbu38pZp96fv5M6jpe9CjlR4yjfqpE/pubhtml?widget=true&amp;headers=false"></iframe>',
			'[googledocs url="https://docs.google.com/spreadsheets/d/1mtvInQiuHtJMjcbu38pZp96fv5M6jpe9CjlR4yjfqpE" height=800 width=400]'
		);

		$this->expect_reversal(
			'<iframe width="400" height ="800" src="https://docs.google.com/spreadsheets/d/1mtvInQiuHtJMjcbu38pZp96fv5M6jpe9CjlR4yjfqpE/pubhtml?widget=true&amp;headers=true"></iframe>',
			'[googledocs url="https://docs.google.com/spreadsheets/d/1mtvInQiuHtJMjcbu38pZp96fv5M6jpe9CjlR4yjfqpE" height=800 width=400 headers="true"]'
		);
	}

	public function test_spreadsheet_callback() {
		$this->expect_callback(
			'[googledocs url="https://docs.google.com/spreadsheets/d/1mtvInQiuHtJMjcbu38pZp96fv5M6jpe9CjlR4yjfqpE" headers="true" height=800 width=400]',
			'<iframe class="shortcake-bakery-googledocs-spreadsheet shortcake-bakery-responsive" src="https://docs.google.com/spreadsheets/d/1mtvInQiuHtJMjcbu38pZp96fv5M6jpe9CjlR4yjfqpE/pubhtml?widget=true&#038;headers=true" width="400" height="800" frameborder="0" marginheight="0" marginwidth="0" ></iframe>'
		);
	}

	public function test_presentation_reversal() {
		$this->expect_reversal(
			'<iframe src="https://docs.google.com/presentation/d/1tQ4Q1wFpKNLj9BW8s_pCYgDMFXIeHskvTQWaRBS-aGc/embed?start=false&loop=false&delayms=3000" frameborder="0" width="960" height="569"></iframe>',
			'[googledocs url="https://docs.google.com/presentation/d/1tQ4Q1wFpKNLj9BW8s_pCYgDMFXIeHskvTQWaRBS-aGc" height=569 width=960 delayms=3000]'
		);

		$this->expect_reversal(
			'<iframe src="https://docs.google.com/presentation/d/1tQ4Q1wFpKNLj9BW8s_pCYgDMFXIeHskvTQWaRBS-aGc/embed?start=true&loop=true&delayms=3000" frameborder="0" width="960" height="569" allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true"></iframe>',
			'[googledocs url="https://docs.google.com/presentation/d/1tQ4Q1wFpKNLj9BW8s_pCYgDMFXIeHskvTQWaRBS-aGc" height=569 width=960 start="true" loop="true" delayms=3000]'
		);
	}

	public function test_presentation_callback() {
		$this->expect_callback(
			'[googledocs url="https://docs.google.com/presentation/d/1tQ4Q1wFpKNLj9BW8s_pCYgDMFXIeHskvTQWaRBS-aGc" delayms=3000 allowfullscreen="true"]',
			'<iframe class="shortcake-bakery-googledocs-presentation shortcake-bakery-responsive" src="https://docs.google.com/presentation/d/1tQ4Q1wFpKNLj9BW8s_pCYgDMFXIeHskvTQWaRBS-aGc/embed?start=false&#038;loop=false&#038;delayms=3000" allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true" ></iframe>'
		);
	}

	public function test_form_reversal() {
		$this->expect_reversal(
			'<iframe src="https://docs.google.com/forms/d/1DwyXsL7kmR2F8-0q0XAecLTwO5_xPeN1tN-ex2Zs_hY/viewform?embedded=true" width="760" height="500" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe>',
			'[googledocs url="https://docs.google.com/forms/d/1DwyXsL7kmR2F8-0q0XAecLTwO5_xPeN1tN-ex2Zs_hY" height=500 width=760]'
		);
	}

	public function test_form_callback() {
		$this->expect_callback(
			'[googledocs url="https://docs.google.com/forms/d/1DwyXsL7kmR2F8-0q0XAecLTwO5_xPeN1tN-ex2Zs_hY"]',
			'<iframe class="shortcake-bakery-googledocs-form shortcake-bakery-responsive" src="https://docs.google.com/forms/d/1DwyXsL7kmR2F8-0q0XAecLTwO5_xPeN1tN-ex2Zs_hY/viewform?embedded=true" frameborder="0" marginheight="0" marginwidth="0" >Loading...</iframe>'
		);
	}

	public function test_maps_reversal() {
		$this->expect_reversal(
			'<iframe src="https://www.google.com/maps/d/u/1/embed?mid=zEkbFn1A1xVE.kLg_5uTIa64Q" width="640" height="480"></iframe>',
			'[googledocs url="https://www.google.com/maps/d/embed?mid=zEkbFn1A1xVE.kLg_5uTIa64Q" height=480 width=640]'
		);
	}

	public function test_maps_callback() {
		$this->expect_callback(
			'[googledocs url="https://www.google.com/maps/d/embed?mid=zEkbFn1A1xVE.kLg_5uTIa64Q"]',
			'<iframe class="shortcake-bakery-googledocs-map shortcake-bakery-responsive" src="https://www.google.com/maps/d/embed?mid=zEkbFn1A1xVE.kLg_5uTIa64Q" frameborder="0" marginheight="0" marginwidth="0" ></iframe>'
		);
	}

	private function expect_reversal( $embed, $reversal ) {
		$before_content = "\napples before\n\n";
		$after_content = "\n\nbananas after\n";

		$transformed_content = wp_filter_post_kses( $before_content . $embed . $after_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertEquals( $before_content . $reversal . $after_content, $transformed_content );
	}

	private function expect_callback( $shortcode, $output ) {
		$post_id = $this->factory->post->create( array( 'post_content' => $shortcode ) );
		$post = get_post( $post_id );
		$this->assertContains( $output, apply_filters( 'the_content', $post->post_content ) );
	}
}
