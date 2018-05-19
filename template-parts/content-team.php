<?php
/**
 * @package ShotStyle
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php the_post_thumbnail( 'sparkling-featured', array( 'class' => 'single-featured' )); ?>
	<div class="post-inner-content">
		<header class="entry-header page-header">

			<h1 class="entry-title "><?php the_title(); ?></h1>
			
			<div class="entry-meta">
				<?php if ( get_edit_post_link() ) : ?>
                    <?php
                    edit_post_link(
                        sprintf(
                        /* translators: %s: Name of current post */
                            esc_html__( 'Edit %s', 'sparkling' ),
                            the_title( '<span class="screen-reader-text">"', '"</span>', false )
                        ),
                        '<i class="fa fa-edit"></i><span class="edit-link">',
                        '</span>'
                    );
                    ?>
				<?php endif; ?>

			</div><!-- .entry-meta -->

		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			
			<?php
				wp_link_pages( array(
					'before'            => '<div class="page-links">'.esc_html__( 'Pages:', 'sparkling' ),
					'after'             => '</div>',
					'link_before'       => '<span>',
					'link_after'        => '</span>',
					'pagelink'          => '%',
					'echo'              => 1
	       		) );
	    	?>
		</div><!-- .entry-content -->
		
	</div>

</article><!-- #post-## -->
