<?php
/**
 * The Shortcake Bakery plugin file
 *
 * @link 		https://github.com/fusioneng/shortcake-bakery
 * @since 		0.1-alpha
 * @package 	Shortcake Bakery
 *
 * @wordpress-plugin
 * Plugin Name: Shortcake Bakery
 * Version: 	0.1-alpha
 * Description: A fine selection of shortcodes for WordPress
 * Author: 		Fusion Engineering and community
 * Author URI: 	https://github.com/fusioneng
 * Plugin URI: 	https://github.com/fusioneng/shortcake-bakery
 * Text Domain: shortcake-bakery
 * Domain Path: /languages
*/

// If this file is called directly, abort.
defined( 'WPINC' ) or die();
define( 'SHORTCAKE_BAKERY_TEXTDOMAIN', 'shortcake-bakery' );
define( 'SHORTCAKE_BAKERY_VERSION', '0.1-alpha' );

/**
 * The core plugin class
 */
require_once dirname( __FILE__ ) . '/inc/class-shortcake-bakery.php';

/**
 * Begins execution of the plugin.
 *
 * @since    0.1-alpha
 */
function run_shortcake_bakery() {
	$shortcake_bakery = Shortcake_Bakery::get_instance();
}

add_action( 'init', 'run_shortcake_bakery', 5 );
