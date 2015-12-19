<?php

if ( !class_exists( 'PP_Keep_Widget' ) ) {
	class PP_Keep_Widget extends WP_Widget {
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
			
			if( current_user_can('read_private_posts') ){
				$widget_title = apply_filters( 'widget_title', $instance['title'] );
				// before and after widget arguments are defined by themes
				echo $args['before_widget'];
				if ( ! empty( $widget_title ) )
				echo $args['before_title'] . $widget_title . $args['after_title'];

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
			$widget_title = $instance[ 'title' ];
			}
			else {
			$widget_title = __( 'New title' );
			}
			// Widget admin form
			?>
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $widget_title ); ?>" />
			</p>
			<?php 
		}
			
		// Updating widget replacing old instances with new
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			return $instance;
		}
	} // Class PP_Keep_Widget ends here
}
