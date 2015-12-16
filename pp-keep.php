<?php
/*
Plugin Name: Private Posts Keep
Description: Segregates Private Posts
Version: 1.2
Author: Korvin M
Author URI: http://korvin.org
License: GPL2

*/

define( 'PPKEEP_PATH', plugin_dir_path( __FILE__ ) );
require_once( __DIR__ . '/PPKeep.php' );
require_once( __DIR__ . '/PPWidget.php' );
if(class_exists('kvnPPKeep'))
{
	$kvnPPKeep = new kvnPPKeep();// instantiate the plugin class
	register_activation_hook(__FILE__, array('$kvnPPKeep', 'activate'));
	register_deactivation_hook(__FILE__, array('$kvnPPKeep', 'deactivate'));
	register_uninstall_hook(__FILE__, array('$kvnPPKeep', 'uninstall'));
}

if (class_exists('kvnPPWidget')){
	// Register and load the widget
	function pp_load_widget() {
		register_widget( 'kvnPPWidget' );
	}
	add_action( 'widgets_init', 'pp_load_widget' );
}
