<?php
/*
Plugin Name: Private Posts Keep
Description: Segregates Private Posts
Version: 0.7.3
Author: Korvin M
Author URI: http://korvin.org
License: GPL2
*/
/* exist if directly accessed */
defined( 'ABSPATH' ) || exit;
define( 'PPKEEP_PATH', plugin_dir_path( __FILE__ ) );
require_once( __DIR__ . '/class-pp-keep.php' );
require_once( __DIR__ . '/class-pp-keep-widget.php' );
require_once( __DIR__ . '/class-template.php' );
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
