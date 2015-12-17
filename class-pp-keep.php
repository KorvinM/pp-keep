<?php
if( !class_exists( 'PP_Keep' ) ) {
	class PP_Keep{
		
		public function __construct() {
			add_action( 'pre_get_posts', array(&$this, 'rem_pp'));
			add_action( 'wp_loaded', array(&$this, 'create'));
			add_action( 'the_title', array(&$this, 'the_title_trim'));
			$kvnPPKeep_plugin = plugin_basename(__FILE__);
		}// END public function __construct
		/**
		 * Activate the plugin
		 */
		public static function activate()
		{
			// Do nothing
		} // END public static function activate
		/**
		 * Deactivate the plugin
		 */
		public static function deactivate()
		{
		} // END public static function deactivate

		public static function uninstall () {
			if ( ! current_user_can( 'activate_plugins' ) )
			    return;
			
		} // END public static function uninstall
		public function rem_pp( $query ) {
			if( current_user_can( 'read_private_posts' ) ){
			/*restrict to the posts page main query.
			 *All other views (eg. category archives) unnaffected
			 *http://codex.wordpress.org/Function_Reference/is_home
			 *http://codex.wordpress.org/Function_Reference/is_main_query*/
				if ( $query->is_home() && $query->is_main_query() ) {
					$query->set( 'post_status', 'publish' );
				}
			}
		}
		
		public function create(){
			$post = '';
			if( get_page_by_title('Private Archive') == NULL )
			// Create post object
			$post = array(
			  'post_title'    => 'Private Archive',
			  'post_status'   => 'private',
			  'post_type'     => 'page',
			  'post_content'   => 'Herein Lie your private posts'
			);

			// Insert the post into the database
			return wp_insert_post( $post );
			
			$post_id = wp_insert_post( $post, true );//now you can use $post_id within add_post_meta or update_post_meta	
		}
	
	function the_title_trim( $title ) {
		  $pattern[0] = '/Protected:/';
		  $pattern[1] = '/Private:/';
		  $replacement[0] = ''; // Enter some text to put in place of Protected:
		  $replacement[1] = ' '; // Enter some text to put in place of Private:
		  return preg_replace($pattern, $replacement, $title);
		}
	} // END class PP_Keep
} // END if(!class_exists('PP_Keep'))
