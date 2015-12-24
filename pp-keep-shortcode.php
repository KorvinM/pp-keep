<?php
/*
 * pp-keep-shortcode.php
 * registers the shortcode needed to add to the Private Posts Page
*/
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
