<?php

class Test_PDF_Shortcode extends WP_UnitTestCase {

	public function test_post_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[pdf url="http://www.gpo.gov/fdsys/pkg/BILLS-114hr2048enr/pdf/BILLS-114hr2048enr.pdf"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( rawurlencode( 'http://www.gpo.gov/fdsys/pkg/BILLS-114hr2048enr/pdf/BILLS-114hr2048enr.pdf' ), apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_embed_reversal() {
		$old_content = <<<EOT
apples before

http://www.gpo.gov/fdsys/pkg/BILLS-114hr2048enr/pdf/BILLS-114hr2048enr.pdf

bananas after
EOT;
		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertContains( '[pdf url="http://www.gpo.gov/fdsys/pkg/BILLS-114hr2048enr/pdf/BILLS-114hr2048enr.pdf"]', $transformed_content );
		$this->assertContains( 'apples before', $transformed_content );
		$this->assertContains( 'bananas after', $transformed_content );

	}

}
