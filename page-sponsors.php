<?php
/**
 * Template Name: Page with sponsors
 *
 * The template for displaying pages with sponsors.
 *
 * @package ShotStyle
 */

get_header(); ?>

<div id="primary" class="content-area">

    <main id="main" class="site-main" role="main">

        <?php
        while ( have_posts() ) :
            the_post();
            ?>

            <?php get_template_part( 'template-parts/content', 'page' ); ?>

        <?php endwhile; // end of the loop. ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
