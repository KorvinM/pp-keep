<?php
/*
Plugin Name: Private Posts Keep
Description: Segregates Private Posts
Version: 0.7.3
Author: Korvin M
Author URI: http://korvin.org
License: GPL2
*/
//protect unauthorised access.
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // end if

//another way I've seen the same concept
/* exit if directly accessed */
//if( ! defined( 'ABSPATH' ) ) exit;
//see http://wordpress.stackexchange.com/questions/108418/what-are-the-differences-between-wpinc-and-abspath for a brief discussion
//can also do 
//defined( 'ABSPATH' ) || exit;
//but this contravenes WP php style guide on account of being too clever
define( 'PPKEEP_PATH', plugin_dir_path( __FILE__ ) );
require_once( __DIR__ . '/class-pp-keep.php' );
require_once( __DIR__ . '/class-pp-keep-widget.php' );
if( class_exists( 'PP_Keep' ) ) {
	$PPKeep = new PP_Keep();// instantiate the plugin class
	register_activation_hook(__FILE__, array('$PPKeep', 'activate'));
	register_deactivation_hook(__FILE__, array('$PPKeep', 'deactivate'));
	register_uninstall_hook(__FILE__, array('$PPKeep', 'uninstall'));
}

if ( class_exists( 'PP_Keep_Widget' ) ) {
	// Register and load the widget
	function pp_load_widget() {
		register_widget( 'PP_Keep_Widget' );
	}
	add_action( 'widgets_init', 'pp_load_widget' );
}
