<?php
/**
 * The Template for displaying all single teams.
 *
 * @package shotstyle
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', 'team' );

			the_post_navigation(
				array(
					'next_text' => '<span class="post-title">%title <i class="fa fa-chevron-right"></i></span>',
					'prev_text' => '<i class="fa fa-chevron-left"></i> <span class="post-title">%title</span>',
				)
			);

		endwhile; // end of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();