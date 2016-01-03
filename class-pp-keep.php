<?php
if( !class_exists( 'PP_Keep' ) ) {
	class PP_Keep{
		public function __construct() {
			add_action( 'pre_get_posts', array(&$this, 'rem_pp'));
			add_action( 'wp_loaded', array(&$this, 'create'));
			add_filter( 'get_next_post_where', array(&$this, 'get_next_post_mod'));
			add_filter( 'get_previous_post_where', array(&$this, 'get_previous_post_mod'));
			add_action( 'the_title', array(&$this, 'the_title_trim'));
			$kvnPPKeep_plugin = plugin_basename(__FILE__);
		}// END public function __construct

		public function rem_pp( $query ) {
			if( current_user_can( 'read_private_posts' ) ){
			/*affects main query on posts page or archives.
			 *http://codex.wordpress.org/Function_Reference/is_main_query
			 *http://codex.wordpress.org/Function_Reference/is_home
			 *http://codex.wordpress.org/Function_Reference/is_archive*/
				if ( $query->is_main_query() &&  $query->is_home() || $query->is_archive() ) {
						$query->set( 'post_status', 'publish' );
					}
			}
		}
		/*
		*filter get_adjacent_post
		*/

		public function get_next_post_mod($where){
			if (is_single()){
				global $wpdb, $post;
				if ( get_post_status ( ) == 'private' ) {
					$where = str_replace( "AND ( p.post_status = 'publish' OR p.post_status = 'private' )", "AND p.post_status = 'private'", $where );
					return $where;	
				} else {
					$where = str_replace( "AND ( p.post_status = 'publish' OR p.post_status = 'private' )", "AND p.post_status = 'publish'", $where );
					return $where;	
				}

			}

		}
		
		public function get_previous_post_mod($where){
			if (is_single()){
				global $wpdb, $post;
				if ( get_post_status ( ) == 'private' ) {
					$where = str_replace( "AND ( p.post_status = 'publish' OR p.post_status = 'private' )", "AND p.post_status = 'private'", $where );
					return $where;	
				} else {
					$where = str_replace( "AND ( p.post_status = 'publish' OR p.post_status = 'private' )", "AND p.post_status = 'publish'", $where );
					return $where;	
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
			
			$insert_post = wp_insert_post( $post, true);//now you can use $post_id within add_post_meta or update_post_meta	
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
