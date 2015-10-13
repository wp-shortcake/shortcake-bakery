<?php

class Shortcode extends Shortcake_Bakery\Shortcodes\Shortcode {

	public static function parse_iframes( $content ) {
		return parent::parse_iframes( $content );
	}

	public static function parse_scripts( $content ) {
		return parent::parse_scripts( $content );
	}

	public static function make_replacements_to_content( $content, $replacements ) {
		return parent::make_replacements_to_content( $content, $replacements );
	}

	public static function parse_url( $url, $component = -1 ) {
		return parent::parse_url( $url, $component );
	}

}
