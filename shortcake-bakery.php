<?php
/*
Plugin Name: Shortcake Bakery
Version: 0.2.0
Description: A fine selection of Shortcake-powered shortcodes.
Author: fusionengineering, davisshaver, danielbachhuber
Author URI: http://www.fusion.net/section/tech-product/
Plugin URI: https://www.github.com/fusioneng/shortcake-bakery/
Text Domain: shortcake-bakery
Domain Path: /languages
*/

require_once dirname( __FILE__ ) . '/inc/class-shortcake-bakery.php';

define( 'SHORTCAKE_BAKERY_VERSION', '0.2.0' );
define( 'SHORTCAKE_BAKERY_URL_ROOT', plugin_dir_url( __FILE__ ) );

/**
 * Load the Shortcake Bakery
 */
// @codingStandardsIgnoreStart
function Shortcake_Bakery() {
	return Shortcake_Bakery::get_instance();
}
// @codingStandardsIgnoreEnd
add_action( 'after_setup_theme', 'Shortcake_Bakery' );

/**
 * Register our autoloader
 */
spl_autoload_register( function( $class ) {
	$class = ltrim( $class, '\\' );
	if ( 0 !== stripos( $class, 'Shortcake_Bakery' ) ) {
		return;
	}

	$parts = explode( '\\', $class );

	// Don't need "Shortcake_Bakery\"
	array_shift( $parts );

	$last = array_pop( $parts ); // File should be 'class-[...].php'
	$last = 'class-' . $last . '.php';
	$parts[] = $last;

	$file = dirname( __FILE__ ) . '/inc/' . str_replace( '_', '-', strtolower( implode( $parts, '/' ) ) );
	if ( file_exists( $file ) ) {
		require $file;
	}
} );
