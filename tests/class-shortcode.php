<?php

class Shortcode extends Shortcake_Bakery\Shortcodes\Shortcode {

	public static function parse_iframes( $content ) {
		return parent::parse_iframes( $content );
	}

	public static function parse_script_tags( $content ) {
		return parent::parse_script_tags( $content );
	}

	public static function make_replacements_to_content( $content, $replacements ) {
		return parent::make_replacements_to_content( $content, $replacements );
	}

}
