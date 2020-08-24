<?php

/*
Plugin Name: Clink - WordPress Link Manager
Plugin URI:  http://aryanthemes.com
Description: Clink - WordPress Link Manager is a WordPress plugin to Manage, create and track outbound links by custom links. like : name.com/clink/google 
Version:     1.2.2
Author:      Aryan Themes
Author URI:  http://aryanthemes.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages 
Text Domain: aryan-themes
*/


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define("CLINK_BASENAME", plugin_basename(__FILE__));
define("CLINK_DIR", dirname(__FILE__));
define("CLINK_URL", plugin_dir_url( __FILE__ ));

/**
 * Load Clink translations
 */
 
function clink_load_textdomain() {
	load_plugin_textdomain( 'aryan-themes', false, dirname( plugin_basename( __FILE__ ) ) .'/languages/' );
}
add_action( 'plugins_loaded', 'clink_load_textdomain', 1 );


/**
 * include major files
 */
 
require_once CLINK_DIR."/inc/cpt.php";
require_once CLINK_DIR."/inc/meta-box.php";
require_once CLINK_DIR."/inc/admin.php";
require_once CLINK_DIR."/inc/admin-widgets.php";
require_once CLINK_DIR."/inc/functions.php";
require_once CLINK_DIR."/inc/widgets.php";
require_once CLINK_DIR."/inc/shortcodes.php";


/**
 * set clink single page template
 */
 
add_filter( 'single_template', 'set_clink_single_template' );


/**
 * Enqueue scripts and styles.
 */
 
add_action( 'wp_enqueue_scripts', 'clink_scripts' );


/**
 * Add Clink admin style to the WordPress dashboard
 */
 
add_action('admin_head', 'clink_admin_style');	