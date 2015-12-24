<?php
if( !class_exists( 'PP_KeepShortcode' ) ) {
	class PP_KeepShortcode{
		public function __construct() {
			add_action('init', array(&$this, 'register_shortcode'));
		}

		public function pp_shortcode_return() {
			query_posts(array(
				'posts_per_page' => -1,
				'post_type'=>'post',
				'post_status'=> 'private',
			));
			if (have_posts()) :
			while (have_posts()) : the_post();
			 $return_string = '<a href="'.get_permalink().'">'.get_the_title().'</a>';
			endwhile;
			endif;
			wp_reset_query();
			   return $return_string;
		}
		public function register_shortcode(){
			add_shortcode('private-posts', array(&$this,'pp_shortcode_return'));
		}
	} // END class PP_KeepShortcode
} // END if(!class_exists('PP_KeepShortcode'))
