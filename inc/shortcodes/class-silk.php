<?php

namespace Shortcake_Bakery\Shortcodes;

class Silk extends Shortcode {

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Silk', 'shortcake-bakery' ),
			'listItemImage'  => '<img src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/svg/icon-silk.svg' ) . '" />',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => esc_html__( 'Full URL to the Silk', 'shortcake-bakery' ),
				),
				array(
					'label'        => esc_html__( 'Size', 'shortcake-bakery' ),
					'attr'         => 'size',
					'type'         => 'select',
					'options'      => array(
						'responsive' => esc_html__( 'Responsive (square)' ),
						'800x100%'  => esc_html__( '800px height, 100% width' ),
						'600x100%'  => esc_html__( '600px height, 100% width' ),
						'400x100%'  => esc_html__( '400px height, 100% width' ),
						),
					),
			),
		);
	}

	public static function reversal( $content ) {

		if ( $iframes = self::parse_iframes( $content ) ) {
			$replacements = array();
			foreach ( $iframes as $iframe ) {
				if ( 'silk.co' !== self::get_tld_from_url( $iframe->attrs['src'] ) ) {
					continue;
				}
				$replacement_key = $iframe->original;
				// Silk embeds can append >Data from <a target='_blank' style='text-decoration:none;'href='http://us-states-with-hiv-specific-criminal-laws.silk.co'>us-states-with-hiv-specific-criminal-laws.silk.co</a></div>
				if ( false !== strpos( $iframe->after, 'Data from <a target' ) ) {
					$replacement_key .= $iframe->after;
				}
				$replacements[ $replacement_key ] = '[' . self::get_shortcode_tag() . ' url="' . esc_url_raw( $iframe->attrs['src'] ) . '"]';
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}

		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		if ( empty( $attrs['url'] ) || 'silk.co' !== self::get_tld_from_url( $attrs['url'] ) ) {
			return '';
		}

		$height = 600;
		$width = 600;
		$classes = 'shortcake-bakery-responsive';

		if ( ! empty( $attrs['size'] ) && stripos( $attrs['size'], 'x' ) ) {
			$parts = explode( 'x', $attrs['size'] );
			if ( count( $parts ) === 2 ) {
				foreach ( array( 'height', 'width' ) as $key => $variable ) {
					$ending = stripos( $parts[ $key ], '%' ) ? '%' : '';
					$value = rtrim( $parts[ $key ], '%' );
					$$variable = (int) $value . $ending;
				}
				$classes = '';
			}
		}

		return sprintf( '<iframe class="%s" width="%s" height="%s" src="%s" frameborder="0"></iframe>',
			esc_attr( $classes ),
			esc_attr( $width ),
			esc_attr( $height ),
			esc_url( $attrs['url'] )
		);
	}

	/**
	 * Get the TLD from the URL
	 */
	private static function get_tld_from_url( $url ) {
		$domain = self::parse_url( $url, PHP_URL_HOST );
		if ( empty( $domain ) ) {
			return '';
		}
		$parts = explode( '.', $domain );
		$parts = array_reverse( $parts );
		return $parts[1] . '.' . $parts[0];
	}

}
