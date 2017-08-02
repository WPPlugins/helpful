<?php
/**
 * Plugin Name: Helpful
 * Description: Add a fancy feedback form under your posts or post-types and ask your visitors a question. Give them the abbility to vote with yes or no.
 * Version: 1.0.1
 * Author: Devhats
 * Author URI: https://devhats.de
 * Text Domain: helpful
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
 
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) exit;

// ================================ //
// 				General				//
// ================================ //

// define file path
define( 'HELPFUL_FILE', __FILE__ );

// Include config
include_once plugin_dir_path( HELPFUL_FILE ) . "config.php";

// ================================ //
// 				Helpers				//
// ================================ //

// is helpful (backend)
function is_helpful() {
	$screen = get_current_screen();
	return ( $screen->base  == 'settings_page_helpful' ? true : false );
}

// ================================ //
// 			Multilanguage			//
// ================================ //

// make plugin translation ready
add_action('plugins_loaded', 'helpful_textdomain');
function helpful_textdomain() 
{
	load_plugin_textdomain( 
		'helpful', 
		false, 
		dirname( plugin_basename( HELPFUL_FILE ) ) . '/languages/' 
	);
}

// ================================ //
// 			  Core Files			//
// ================================ //

// Include functions
foreach ( glob( plugin_dir_path( HELPFUL_FILE ) . "core/*.class.php" ) as $file ) 
	include_once $file;