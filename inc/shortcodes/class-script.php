<?php

namespace Shortcake_Bakery\Shortcodes;

class Script extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Script', 'shortcake-bakery' ),
			'listItemImage'  => 'dashicons-media-code',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'src',
					'type'         => 'text',
					'description'  => esc_html__( 'Full URL to the script file. Host must be whitelisted.', 'shortcake-bakery' ),
				),
			),
		);
	}


	/**
	*
	* Get the whitelisted script domains for the plugin
	* Whitelist domains using `add_filter` on this hook to return array of your site's whitelisted domaiins.
	*
	* @return array of whitelisted domains, e.g. 'ajax.googleapis.com'
	*/
	public static function get_whitelisted_script_domains() {
		return apply_filters( 'shortcake_bakery_whitelisted_script_domains', array() );
	}

	public static function reversal( $content ) {
		$scripts = self::parse_scripts( $content );
		if ( $scripts ) {
			$replacements = array();
			$whitelisted_script_domains = static::get_whitelisted_script_domains();
			$shortcode_tag = static::get_shortcode_tag();
			foreach ( $scripts as $script ) {
				$host = self::parse_url( $script->attrs['src'], PHP_URL_HOST );
				if ( ! in_array( $host, $whitelisted_script_domains, true ) ) {
					continue;
				}
				$replacements[ $script->original ] = '[' . $shortcode_tag . ' src="' . esc_url_raw( $script->attrs['src'] ) . '"]';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}
		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		if ( empty( $attrs['src'] ) ) {
			return '';
		}

		// Allow script URLs to be filtered
		$attrs['src'] = apply_filters( 'shortcake_bakery_script_src', $attrs['src'], $attrs );

		$host = self::parse_url( $attrs['src'], PHP_URL_HOST );
		if ( ! in_array( $host, static::get_whitelisted_script_domains(), true ) ) {
			if ( current_user_can( 'edit_posts' ) ) {
				/* Translators: invalid hostname warning. */
				return '<div class="shortcake-bakery-error"><p>' . sprintf( esc_html__( 'Invalid hostname in URL: %s', 'shortcake-bakery' ), esc_url( $attrs['src'] ) ) . '</p></div>';
			} else {
				return '';
			}
		}

		return '<script src="' . esc_url( $attrs['src'] ) . '"></script>';
	}

}
