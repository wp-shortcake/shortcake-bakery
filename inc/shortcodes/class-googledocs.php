<?php

namespace Shortcake_Bakery\Shortcodes;

class GoogleDocs extends Shortcode {

	private static $valid_hosts = array( 'docs.google.com', 'www.google.com' );

	public static function get_shortcode_ui_args() {
		return array(
			'label'          => esc_html__( 'Google Docs', 'shortcake-bakery' ),
			'listItemImage'  => '<img src="' . esc_url( SHORTCAKE_BAKERY_URL_ROOT . 'assets/images/svg/icon-googledocs.svg' ) . '" />',
			'attrs'          => array(
				array(
					'label'        => esc_html__( 'URL', 'shortcake-bakery' ),
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => esc_html__( 'Full document URL', 'shortcake-bakery' ),
				),
				array(
					'label'        => esc_html__( 'Height', 'shortcake-bakery' ),
					'attr'         => 'height',
					'type'         => 'number',
					'description'  => esc_html__( 'Pixel height of the iframe.', 'shortcake-bakery' ),
				),
				array(
					'label'        => esc_html__( 'Width', 'shortcake-bakery' ),
					'attr'         => 'width',
					'type'         => 'number',
					'description'  => esc_html__( 'Pixel width of the iframe.', 'shortcake-bakery' ),
				),
				array(
					'label'        => esc_html__( 'Disable Responsiveness', 'shortcake-bakery' ),
					'attr'         => 'disableresponsiveness',
					'type'         => 'checkbox',
					'description'  => esc_html__( 'By default, height/width ratio of the embedded document will be maintained regardless of container width. Check this to keep constant height/width.', 'shortcake-bakery' ),
				),

				/* Options specific to "spreadsheet" document type */
				array(
					'label'        => esc_html__( 'Display spreadsheet header rows?', 'shortcake-bakery' ),
					'attr'         => 'headers',
					'type'         => 'checkbox',
				),

				/* Options specific to "presentation" document type */
				array(
					'label'        => esc_html__( 'Autostart?', 'shortcake-bakery' ),
					'attr'         => 'start',
					'type'         => 'checkbox',
				),
				array(
					'label'        => esc_html__( 'Loop?', 'shortcake-bakery' ),
					'attr'         => 'loop',
					'type'         => 'checkbox',
				),
				array(
					'label'        => esc_html__( 'Delay between slides (ms)', 'shortcake-bakery' ),
					'attr'         => 'delayms',
					'type'         => 'number',
					'default'      => 3000,
				),
			),
		);
	}

	public static function reversal( $content ) {
		if ( $iframes = self::parse_iframes( $content ) ) {
			$replacements = array();
			foreach ( $iframes as $iframe ) {
				if ( ! in_array( self::parse_url( $iframe->attrs['src'], PHP_URL_HOST ), self::$valid_hosts, true ) ) {
					continue;
				}

				if ( ! $parsed_from_url = self::parse_from_url( $iframe->attrs['src'] ) ) {
					continue;
				}

				switch ( $parsed_from_url['doc_type'] ) {
					case 'document':
						$replacement_url = 'https://docs.google.com/document/d/' . $parsed_from_url['embed_id'];
						$replacements[ $iframe->original ] = '[' . self::get_shortcode_tag() .
							' url="' . esc_url_raw( $replacement_url ) . '"' .
							( ! empty( $iframe->attrs['height'] ) ? ' height=' . intval( $iframe->attrs['height'] ) : '' ) .
							( ! empty( $iframe->attrs['width'] ) ? ' width=' . intval( $iframe->attrs['width'] ) : '' ) .
							']';
						break;
					case 'spreadsheet':
					case 'spreadsheets':
						parse_str( html_entity_decode( $parsed_from_url['query_string'] ), $query_vars );
						$replacement_url = 'https://docs.google.com/spreadsheets/d/' . $parsed_from_url['embed_id'];
						$replacements[ $iframe->original ] = '[' . self::get_shortcode_tag() .
							' url="' . esc_url_raw( $replacement_url ) . '"' .
							( ! empty( $iframe->attrs['height'] ) ? ' height=' . intval( $iframe->attrs['height'] ) : '' ) .
							( ! empty( $iframe->attrs['width'] ) ? ' width=' . intval( $iframe->attrs['width'] ) : '' ) .
							( ! empty( $query_vars['headers'] ) && 'false' !== $query_vars['headers'] ? ' headers="true"' : '' ) .
							']';
						break;
					case 'presentation':
						parse_str( html_entity_decode( $parsed_from_url['query_string'] ), $query_vars );
						$replacement_url = 'https://docs.google.com/presentation/d/' . $parsed_from_url['embed_id'];
						$replacements[ $iframe->original ] = '[' . self::get_shortcode_tag() .
							' url="' . esc_url_raw( $replacement_url ) . '"' .
							( ! empty( $iframe->attrs['height'] ) ? ' height=' . intval( $iframe->attrs['height'] ) : '' ) .
							( ! empty( $iframe->attrs['width'] ) ? ' width=' . intval( $iframe->attrs['width'] ) : '' ) .
							( ! empty( $query_vars['start'] ) && 'false' !== $query_vars['start'] ? ' start="true"' : '' ) .
							( ! empty( $query_vars['loop'] ) && 'false' !== $query_vars['loop'] ? ' loop="true"' : '' ) .
							( ! empty( $query_vars['delayms'] ) ? ' delayms=' . intval( $query_vars['delayms'] ) : '' ) .
							']';
						break;
					case 'form':
					case 'forms':
						$replacement_url = 'https://docs.google.com/forms/d/' . $parsed_from_url['embed_id'];
						$replacements[ $iframe->original ] = '[' . self::get_shortcode_tag() .
							' url="' . esc_url_raw( $replacement_url ) . '"' .
							( ! empty( $iframe->attrs['height'] ) ? ' height=' . intval( $iframe->attrs['height'] ) : '' ) .
							( ! empty( $iframe->attrs['width'] ) ? ' width=' . intval( $iframe->attrs['width'] ) : '' ) .
							']';
						break;
					case 'map':
					case 'maps':
						parse_str( html_entity_decode( $parsed_from_url['query_string'] ), $query_vars );
						if ( empty( $query_vars['mid'] ) ) {
							return;
						}
						$replacement_url = add_query_arg(
							array(
								'mid' => $query_vars['mid'],
							),
							'https://www.google.com/maps/d/embed'
						);
						$replacements[ $iframe->original ] = '[' . self::get_shortcode_tag() .
							' url="' . esc_url_raw( $replacement_url ) . '"' .
							( ! empty( $iframe->attrs['height'] ) ? ' height=' . intval( $iframe->attrs['height'] ) : '' ) .
							( ! empty( $iframe->attrs['width'] ) ? ' width=' . intval( $iframe->attrs['width'] ) : '' ) .
							']';
						break;
					case 'fusiontables':
						parse_str( html_entity_decode( $parsed_from_url['query_string'] ), $query_vars );
						$replacement_url = add_query_arg( $query_vars, 'https://www.google.com/fusiontables/embedviz' );
						$replacements[ $iframe->original ] = '[' . self::get_shortcode_tag() .
							' url="' . esc_url_raw( $replacement_url ) . '"' .
							( ! empty( $iframe->attrs['height'] ) ? ' height=' . intval( $iframe->attrs['height'] ) : '' ) .
							( ! empty( $iframe->attrs['width'] ) ? ' width=' . intval( $iframe->attrs['width'] ) : '' ) .
							']';
						break;
				}
			}
			$content = self::make_replacements_to_content( $content, $replacements );
		}

		return $content;
	}

	public static function callback( $attrs, $content = '' ) {

		$host = self::parse_url( $attrs['url'], PHP_URL_HOST );
		$type = self::get_document_type( $attrs['url'] );
		if ( ! in_array( $host, self::$valid_hosts, true ) || ! $type ) {
			return '';
		}

		$iframe_classes = "shortcake-bakery-googledocs-{$type}" .
			( empty( $attrs['disableresponsiveness'] ) ? ' shortcake-bakery-responsive' : '' );
		$url = '';
		$inner_content = '';
		$width_attr  = ! empty( $attrs['width'] )  ? intval( $attrs['width'] ) : '';
		$height_attr = ! empty( $attrs['height'] ) ? intval( $attrs['height'] ) : '';
		$additional_attributes = array(
			'frameborder' => '0',
			'marginheight' => '0',
			'marginwidth' => '0',
		);

		switch ( $type ) {
			case 'document':
				$url = add_query_arg(
					array(
						'embedded' => 'true',
					),
					$attrs['url'] . '/pub'
				);
				break;
			case 'spreadsheet':
				$url = add_query_arg(
					array(
						'widget' => 'true',
						'headers' => ! empty( $attrs['headers'] ) ? 'true' : 'false',
					),
					$attrs['url'] . '/pubhtml'
				);
				break;
			case 'presentation':
				$url = add_query_arg(
					array(
						'start' => ! empty( $attrs['start'] ) ? 'true' : 'false',
						'loop' => ! empty( $attrs['loop'] ) ? 'true' : 'false',
						'delayms' => ! empty( $attrs['delayms'] ) ? intval( $attrs['delayms'] ) : '3000',
					),
					$attrs['url'] . '/embed'
				);
				$additional_attributes = array(
					'allowfullscreen' => 'true',
					'mozallowfullscreen' => 'true',
					'webkitallowfullscreen' => 'true',
				);
				break;
			case 'form':
				$url = add_query_arg(
					array(
						'embedded' => 'true',
					),
					$attrs['url'] . '/viewform'
				);
				$inner_content = 'Loading...';
				break;
			case 'map':
				$url = $attrs['url'];
				break;
			case 'fusiontable':
				$url = $attrs['url'];
				break;
			default:
				return '';
		}

		return '<iframe class="' . esc_attr( $iframe_classes ) . '" ' .
			'src="' . esc_url( $url ) . '" ' .
			( $width_attr ? 'width="' . esc_attr( $width_attr ) . '" ' : '') .
			( $height_attr ? 'height="' . esc_attr( $height_attr ) . '" ' : '' ) .
			( $additional_attributes ? array_reduce(
				array_keys( $additional_attributes ),
				function( $attribute_string, $attr_key ) use ( $additional_attributes ) {
					return $attribute_string . sanitize_key( $attr_key ) . '="' . esc_attr( $additional_attributes[ $attr_key ] ) . '" ';
				} ) : '' ) . '>' .
			( $inner_content ? esc_html( $inner_content ) : '' ) .
			'</iframe>';
	}

	/**
	 * Parse the URL of an iframe into get the pieces necessary to build a shortcode
	 *
	 * @param string URL
	 * @return array Array with the following attributes: "doc_type", "embed_id", "view_name", "query_string"
	 */
	private static function parse_from_url( $url ) {
		if ( ! in_array( self::parse_url( $url, PHP_URL_HOST ), self::$valid_hosts, true ) ) {
			return;
		}

		$url_parts_regex = '#(?P<subdomain>docs|www)\.google\.com/' // The subdomain. Not used
			. '(?P<doc_type>\w*)'                                   // First path indicates the document type
			. '(/d/(?P<embed_id>.*?))?'                             // All Google Doc URLs (except fusion tables) have an embed ID
			. '(?:/(?P<view_name>\w*))?'                            // Some URLs contain a verb, like "embed" or "pub" identifying the view
			. '(?:\?(?P<query_string>[^/?]+))?$#';                  // Some have additional options stored in a query string

		if ( preg_match( $url_parts_regex, $url, $matches ) ) {
			return $matches;
		}
	}

	/**
	 * Get the document type from an embed iframe URL
	 *
	 * Differs from the regex in self::parse_from_url in that the embed urls we
	 * store here don't always contain the view_name path part.
	 *
	 * @param string URL
	 * @return string|false One of the supported document types if matched, false if otherwise
	 */
	private static function get_document_type( $url ) {
		$parsed_url = self::parse_from_url( $url );

		if ( ! $parsed_url || empty( $parsed_url['doc_type'] ) ) {
			return false;
		}

		// Sometimes the document type path part is plural, sometimes singular. Remove the trailing "s" to normalize this.
		return rtrim( $parsed_url['doc_type'], 's' );
	}

}
