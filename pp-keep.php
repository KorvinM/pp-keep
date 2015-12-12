<?php
/*
Plugin Name: Private Posts Keep
Description: Segregates Private Posts
Version: 1
Author: Korvin M
Author URI: http://korvin.org
License: GPL2

*/

if(!class_exists('kvnPPKeep'))
{
	class kvnPPKeep{
		
		public function __construct() {
			
			add_action( 'pre_get_posts', array(&$this, 'rem_pp'));
			/*the following seems determined to create the post twice, so is commented out
			//add_action( 'wp_loaded', array(&$this, 'create'));

			$kvnPPKeep_plugin = plugin_basename(__FILE__);
			
		}// END public function __construct
	
		/*
		*/
		public function rem_pp($query){
			if( is_user_logged_in() ){
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
			if( get_page_by_title('My post') == NULL )
			// Create post object
			$post = array(
			  'post_title'    => 'My post',
			  'post_content'  => 'This is my post.',
			  'post_status'   => 'private',
			  'post_type'     => 'page',
			  'post_content'   => 'Herein Lie your private posts'
			);

			// Insert the post into the database
			wp_insert_post( $post );
			
			$post_id = wp_insert_post( $post, true );//now you can use $post_id within add_post_meta or update_post_meta	
		}
	

		
		public static function uninstall () {
			if ( ! current_user_can( 'activate_plugins' ) )
			    return;
			
		} // END public static function uninstall
		
	} // END class kvnPPKeep
} // END if(!class_exists('kvnPPKeep'))

if(class_exists('kvnPPKeep'))
{
	register_uninstall_hook(__FILE__, array('kvnPPKeep', 'uninstall'));
	$kvnPPKeep = new kvnPPKeep();// instantiate the plugin class
	//$kvnPPPage=$kvnPPKeep->create();
}

if (!class_exists('kvnPPWidget')){
	
	class kvnPPWidget extends WP_Widget {

		function __construct() {
			parent::__construct(
			// Base ID of your widget
			'pp_widget', 

			// Widget name will appear in UI
			__('Private Posts Widget'), 

			// Widget description
			array( 'description' => __( 'Displays a list of private posts for logged in users only.' ), ) 
			);
		}

		// Creating widget front-end
		// This is where the action happens
		public function widget( $args, $instance ) {
			if( is_user_logged_in() ){
			
				$title = apply_filters( 'widget_title', $instance['title'] );
				// before and after widget arguments are defined by themes
				echo $args['before_widget'];
				if ( ! empty( $title ) )
				echo $args['before_title'] . $title . $args['after_title'];

				// This is where you run the code and display the output
				//echo __( 'Private Posts' );
				  
				$loopargs = array(
						'posts_per_page' => -1,
						'post_type'=>'post',
						'post_status'=> 'private');
				$query = new WP_Query($loopargs);
				//theloop
				while ($query->have_posts() ):
				$query->the_post();
				global $more;
				$more =0;

				?>
				<h4 class="pp-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h4>

				<?php endwhile; ?>

				<?php echo $args['after_widget'];
			}
		}
				
		// Widget Backend 
		public function form( $instance ) {
			if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
			}
			else {
			$title = __( 'New title' );
			}
			// Widget admin form
			?>
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<?php 
		}
			
		// Updating widget replacing old instances with new
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			return $instance;
		}
	} // Class docs_widget ends here
}
if (class_exists('kvnPPWidget')){
	// Register and load the widget
	function pp_load_widget() {
		register_widget( 'kvnPPWidget' );
	}
	add_action( 'widgets_init', 'pp_load_widget' );
}
