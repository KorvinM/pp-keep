<?php
/*
Plugin Name: Private Posts Keep
Description: Segregates Private Posts
Version: 0.8.1
Author: Korvin M
Author URI: http://korvin.org
License: GPL2
*/
/* exit if directly accessed */
defined( 'ABSPATH' ) || exit;
define( 'PPKEEP_PATH', plugin_dir_path( __FILE__ ) );
 
function ppkeep_shortcode_return() {
	 ob_start();
	$pp_posts=array(
		'posts_per_page' => 2,
		'post_type'=>'post',
		'post_status'=> 'private',
	);
	
	$query = new WP_Query($pp_posts);
	if ($query->have_posts() ): ?>
	<div>
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <p id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </p>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </div>
	<?php endif; 
	wp_reset_postdata();
	$return_string= ob_get_clean();
	return $return_string;
}
function ppkeep_register_shortcode(){
	add_shortcode('private-posts', 'ppkeep_shortcode_return');
}
add_action('init','ppkeep_register_shortcode');
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
function activate()
{
	// Do nothing
} // END public static function activate
/**
 * Deactivate the plugin
 */
function deactivate()
{
	$page = get_page_by_title('Private Archive');
	if (isset($page)){
		$page_id= $page->ID;
	}
	wp_delete_post($page_id, true);
} // END public static function deactivate

function uninstall () {
	if ( ! current_user_can( 'activate_plugins' ) )
	    return;
} // END public static function uninstall
register_activation_hook(__FILE__, 'activate');
register_deactivation_hook(__FILE__, 'deactivate');
register_uninstall_hook(__FILE__, 'uninstall');
