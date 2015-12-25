<?php
if( !class_exists( 'PP_Keep' ) ) {
	class PP_Keep{
		public function __construct() {
			add_action( 'pre_get_posts', array(&$this, 'rem_pp'));
			add_action( 'wp_loaded', array(&$this, 'create'));
			add_action( 'the_title', array(&$this, 'the_title_trim'));
			$kvnPPKeep_plugin = plugin_basename(__FILE__);
		}// END public function __construct

		public function rem_pp( $query ) {
			if( current_user_can( 'read_private_posts' ) ){
			/*restrict to the posts page main query.
			 *All other views (eg. category archives) unnaffected
			 *http://codex.wordpress.org/Function_Reference/is_home
			 *http://codex.wordpress.org/Function_Reference/is_main_query*/
				if ( $query->is_main_query() &&  $query->is_home() || $query->is_archive() ) {
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
			  'post_content'   => 'This page is intended for your private posts.
				<!--Below is a shortcode which will output them in a list -->
				[private-posts]'
			);

			// Insert the post into the database
			
			$insert_post = wp_insert_post( $post);//now you can use $post_id within add_post_meta or update_post_meta	
			return $insert_post;	
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
