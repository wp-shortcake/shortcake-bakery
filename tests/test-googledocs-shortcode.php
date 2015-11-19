<?php

class Test_Google_Docs_Shortcode extends WP_UnitTestCase {

	public function test_document_reversal() {
		$this->expect_reversal(
			'<iframe src="https://docs.google.com/document/d/1TwnxIligMjh1FLa1AWCB7F4xstvLrYrhJFqPqObvmK8/pub?embedded=true"></iframe>',
			'[googledocs type="document" url="https://docs.google.com/document/d/1TwnxIligMjh1FLa1AWCB7F4xstvLrYrhJFqPqObvmK8"]'
		);
	}

	public function test_document_callback() {
		$this->expect_callback(
			'[googledocs type="document" url="https://docs.google.com/document/d/1TwnxIligMjh1FLa1AWCB7F4xstvLrYrhJFqPqObvmK8"]',
			'<iframe src="https://docs.google.com/document/d/1TwnxIligMjh1FLa1AWCB7F4xstvLrYrhJFqPqObvmK8/pub?embedded=true"></iframe>'
		);
	}

//'<iframe width="400" height ="800" src="https://docs.google.com/spreadsheets/d/1mtvInQiuHtJMjcbu38pZp96fv5M6jpe9CjlR4yjfqpE/pubhtml?widget=true&amp;headers=false"></iframe>',
//'<iframe src="https://docs.google.com/presentation/d/1tQ4Q1wFpKNLj9BW8s_pCYgDMFXIeHskvTQWaRBS-aGc/embed?start=false&loop=false&delayms=3000" frameborder="0" width="960" height="569" allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true"></iframe>',
//'<iframe src="https://docs.google.com/forms/d/1DwyXsL7kmR2F8-0q0XAecLTwO5_xPeN1tN-ex2Zs_hY/viewform?embedded=true" width="760" height="500" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe>',
//'<iframe src="https://www.google.com/maps/d/u/1/embed?mid=zEkbFn1A1xVE.kLg_5uTIa64Q" width="640" height="480"></iframe>',
//'<img src="https://docs.google.com/drawings/d/1WRklYKAG0B3uJrvcd5WNaaG-UABEZnfKFOes0L-kaK8/pub?w=960&amp;h=720">',


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
