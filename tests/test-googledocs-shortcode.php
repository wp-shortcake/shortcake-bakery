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
			'<iframe class="shortcake-bakery-googledocs-document shortcake-bakery-responsive" src="https://docs.google.com/document/d/1TwnxIligMjh1FLa1AWCB7F4xstvLrYrhJFqPqObvmK8/pub?embedded=true" frameborder="0" marginheight="0" marginwidth="0" ></iframe>'
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

	public function test_fusiontable_reversal() {
		$this->expect_reversal(
			'<iframe width="500" height="300" scrolling="no" frameborder="no" src="https://www.google.com/fusiontables/embedviz?q=select+col12+from+1-941Px73b_XWWn3pmPHKp6WhbbSVNiEmKMadwe0&amp;viz=MAP&amp;h=false&amp;lat=10.992086799750266&amp;lng=-85.45998246582036&amp;t=1&amp;z=11&amp;l=col12&amp;y=45&amp;tmplt=51&amp;hml=TWO_COL_LAT_LNG"></iframe>',
			'[googledocs url="https://www.google.com/fusiontables/embedviz?q=select%20col12%20from%201-941Px73b_XWWn3pmPHKp6WhbbSVNiEmKMadwe0&viz=MAP&h=false&lat=10.992086799750266&lng=-85.45998246582036&t=1&z=11&l=col12&y=45&tmplt=51&hml=TWO_COL_LAT_LNG" height=300 width=500]'
		);
		$this->expect_reversal(
			'<iframe width="500" height="300" scrolling="yes" frameborder="no" src="https://www.google.com/fusiontables/embedviz?viz=CARD&amp;q=select+*+from+1-941Px73b_XWWn3pmPHKp6WhbbSVNiEmKMadwe0&amp;tmplt=6559&amp;cpr=2"></iframe>',
			'[googledocs url="https://www.google.com/fusiontables/embedviz?viz=CARD&q=select%20*%20from%201-941Px73b_XWWn3pmPHKp6WhbbSVNiEmKMadwe0&tmplt=6559&cpr=2" height=300 width=500]'
		);
	}

	public function test_fusiontable_callback() {
		$this->expect_callback(
			'[googledocs url="https://www.google.com/fusiontables/embedviz?q=select%20col12%20from%201-941Px73b_XWWn3pmPHKp6WhbbSVNiEmKMadwe0&viz=MAP&h=false&lat=10.992086799750266&lng=-85.45998246582036&t=1&z=11&l=col12&y=45&tmplt=51&hml=TWO_COL_LAT_LNG" height=300 width=500]',
			'<iframe class="shortcake-bakery-googledocs-fusiontable shortcake-bakery-responsive" src="https://www.google.com/fusiontables/embedviz?q=select%20col12%20from%201-941Px73b_XWWn3pmPHKp6WhbbSVNiEmKMadwe0&#038;viz=MAP&#038;h=false&#038;lat=10.992086799750266&#038;lng=-85.45998246582036&#038;t=1&#038;z=11&#038;l=col12&#038;y=45&#038;tmplt=51&#038;hml=TWO_COL_LAT_LNG" width="500" height="300" frameborder="0" marginheight="0" marginwidth="0" ></iframe>'
		);
		$this->expect_callback(
			'[googledocs url="https://www.google.com/fusiontables/embedviz?q=select%20col12%20from%201-941Px73b_XWWn3pmPHKp6WhbbSVNiEmKMadwe0&viz=MAP&h=false&lat=10.992086799750266&lng=-85.45998246582036&t=1&z=11&l=col12&y=45&tmplt=51&hml=TWO_COL_LAT_LNG" height=300 width=500]',
			'<iframe class="shortcake-bakery-googledocs-fusiontable shortcake-bakery-responsive" src="https://www.google.com/fusiontables/embedviz?q=select%20col12%20from%201-941Px73b_XWWn3pmPHKp6WhbbSVNiEmKMadwe0&#038;viz=MAP&#038;h=false&#038;lat=10.992086799750266&#038;lng=-85.45998246582036&#038;t=1&#038;z=11&#038;l=col12&#038;y=45&#038;tmplt=51&#038;hml=TWO_COL_LAT_LNG" width="500" height="300" frameborder="0" marginheight="0" marginwidth="0" ></iframe>'
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
