<?php
/**
 * The template for displaying the Private Posts Page 
 * Will ignore the normal page content and display a list of private posts, with excerpts
 */

get_header(); ?>

<div style="position:relative;">

<p style="position:absolute; top:50%;left:50%; transform:translate(-50%, -50%);border-bottom: 1px solid #888">
This page shows all your private posts. <br>
</p>
</div>
		<?php
		?>
		<?php
		//new query for private posts
		$pp_query=new WP_Query( array( 'post_status' => 'private' ) ); ?>
		<?php if ( $pp_query->have_posts() ) : ?>

			<!-- pagination here -->

			<!-- the loop -->
			<?php while ( $pp_query->have_posts() ) : $pp_query->the_post(); ?>
				<div class="entry-header">
				<h2><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h2>
				</div>
				<div class="entry-excerpt">
				<p><?php the_excerpt(); ?> </p>
				</div>
			<?php endwhile; ?>
			<!-- end of the loop -->

			<!-- pagination here -->

			<?php wp_reset_postdata(); ?>

		<?php else : ?>
		<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
		<?php endif; ?>


<?php get_footer(); ?>
