<?php
/**
 * Plugin Name: Facebook Ratings
 * Description: A simple Wordpress plugin that allows users to display their Facebook Page Ratings into a neat slider
 * Version: 1.0
 * Author: Wazo Lab
 * Author URI: http://wazo-lab.io
 * Text Domain: wp-fb-ratings
 */

if( !function_exists( 'add_action' ) ){
  die( "Hi there ! I'm just a plugin, not much I can do when called directly." );
}

session_start();

// Setup
define( 'FBR_PLUGIN_URL', __FILE__ );


// Includes
include( 'includes/activate.php' );
include( 'includes/deactivate.php' );
include( 'includes/init.php' );
include( 'includes/admin/menus.php' );
include( 'includes/admin/init.php' );
include( dirname( FBR_PLUGIN_URL ) . '/includes/widgets.php' );
include( 'includes/front/enqueue.php' );
include( 'includes/widgets/ratings-slider.php' );
include( 'process/save-options.php' );
include( 'process/select-page.php' );
include( 'vendor/autoload.php' );


// Hooks
register_activation_hook( __FILE__, 'fbr_activate_plugin' );
register_deactivation_hook( __FILE__, 'fbr_deactivate_plugin' );
add_action( 'init', 'fbr_init' );
add_action( 'admin_menu', 'fbr_admin_menus' );
add_action( 'admin_init', 'fbr_admin_init' );
add_action( 'admin_post_fbr_save_options', 'fbr_save_options' );
add_action( 'admin_post_fbr_select_page', 'fbr_select_page' );
add_action( 'wp_enqueue_scripts', 'fbr_enqueue_scripts', 100 );
add_action( 'widgets_init', 'fbr_widgets_init' );

// Shortcodes