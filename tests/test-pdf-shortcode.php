<?php

class Test_PDF_Shortcode extends WP_UnitTestCase {

	public function test_post_display() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[pdf url="http://www.gpo.gov/fdsys/pkg/BILLS-114hr2048enr/pdf/BILLS-114hr2048enr.pdf"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( rawurlencode( 'http://www.gpo.gov/fdsys/pkg/BILLS-114hr2048enr/pdf/BILLS-114hr2048enr.pdf' ), apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_embed_non_reversal_raw() {
		$old_content = <<<EOT
apples before

http://www.gpo.gov/fdsys/pkg/BILLS-114hr2048enr/pdf/BILLS-114hr2048enr.pdf

bananas after
EOT;
		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertContains( '

http://www.gpo.gov/fdsys/pkg/BILLS-114hr2048enr/pdf/BILLS-114hr2048enr.pdf
', $transformed_content );
		$this->assertContains( 'apples before', $transformed_content );
		$this->assertContains( 'bananas after', $transformed_content );

	}

	public function test_embed_non_reversal_link() {
		$old_content = <<<EOT
apples <a href="http://www.gpo.gov/fdsys/pkg/BILLS-114hr2048enr/pdf/BILLS-114hr2048enr.pdf">before</a>

bananas http://www.aiim.org/documents/standards/19005-1_FAQ.PDF after
EOT;
		$transformed_content = wp_filter_post_kses( $old_content );
		$transformed_content = str_replace( '\"', '"', $transformed_content ); // Kses slashes the data
		$this->assertContains( '<a href="http://www.gpo.gov/fdsys/pkg/BILLS-114hr2048enr/pdf/BILLS-114hr2048enr.pdf">before</a>', $transformed_content );
		$this->assertContains( 'apples', $transformed_content );
		$this->assertContains( 'bananas http://www.aiim.org/documents/standards/19005-1_FAQ.PDF after', $transformed_content );

	}

	public function test_https_respect() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[pdf url="https://www.gpo.gov/fdsys/pkg/BILLS-114hr2048enr/pdf/BILLS-114hr2048enr.pdf"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( 'https://mozilla.github.io/pdf.js/web/viewer.html?file=' . rawurlencode( 'https://www.gpo.gov/fdsys/pkg/BILLS-114hr2048enr/pdf/BILLS-114hr2048enr.pdf' ), apply_filters( 'the_content', $post->post_content ) );
	}

	public function test_http_respect() {
		$post_id = $this->factory->post->create( array( 'post_content' => '[pdf url="http://www.gpo.gov/fdsys/pkg/BILLS-114hr2048enr/pdf/BILLS-114hr2048enr.pdf"]' ) );
		$post = get_post( $post_id );
		$this->assertContains( 'http://mozilla.github.io/pdf.js/web/viewer.html?file=' . rawurlencode( 'http://www.gpo.gov/fdsys/pkg/BILLS-114hr2048enr/pdf/BILLS-114hr2048enr.pdf' ), apply_filters( 'the_content', $post->post_content ) );
	}
}
