<?php
/*
Plugin Name: Private Posts Keep
Description: Segregates Private Posts
Version: 0.8.5
Author: Korvin M
Author URI: http://korvin.org
License: GPL2
*/
/* exit if directly accessed */
defined( 'ABSPATH' ) || exit;
define( 'PPKEEP_PATH', plugin_dir_path( __FILE__ ) );

require_once( __DIR__ . '/pp-keep-shortcode.php' );
require_once( __DIR__ . '/class-pp-keep.php' );
require_once( __DIR__ . '/class-pp-keep-widget.php' );
if( class_exists( 'PP_Keep' ) ) {
	$PPKeep = new PP_Keep();// instantiate the plugin class
}

if ( class_exists( 'PP_Keep_Widget' ) ) {
	// Register and load the widget
	function pp_load_widget() {
		register_widget( 'PP_Keep_Widget' );
	}
	add_action( 'widgets_init', 'pp_load_widget' );
}

/**
 * Activate the plugin
 */
function ppkeep_activate()
{
	// Do nothing
} // END public static function activate
/**
 * Deactivate the plugin
 */
function ppkeep_deactivate()
{
	$page = get_page_by_title('Private Archive');
	if (isset($page)){
		$page_id= $page->ID;
	}
	wp_delete_post($page_id, true);
} // END public static function deactivate

function ppkeep_uninstall () {
	if ( ! current_user_can( 'activate_plugins' ) )
	    return;
} // END public static function uninstall
register_activation_hook(__FILE__, 'ppkeep_activate');
register_deactivation_hook(__FILE__, 'ppkeep_deactivate');
register_uninstall_hook(__FILE__, 'ppkeep_uninstall');
