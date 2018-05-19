<?php
/**
 * @package ShotStyle
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php the_post_thumbnail( 'sparkling-featured', array( 'class' => 'single-featured' )); ?>
	<div class="post-inner-content">
		<header class="entry-header page-header">
		
			<div class="entry-event-date">
				<?php event_get_date_box();?>
			</div>
			
			<h1 class="entry-title "><?php the_title(); ?></h1>
			
			<div class="entry-meta">
				<?php event_date_time(); ?>
				
				<?php
					/* translators: used between list items, there is a space after the comma */
					$categories_list = get_the_category_list( esc_html__( ', ', 'sparkling' ) );
					if ( $categories_list && sparkling_categorized_blog() ) :
				?>
				<span class="cat-links"><i class="fa fa-folder-open-o"></i>
					<?php printf( esc_html__( ' %1$s', 'sparkling' ), $categories_list ); ?>
				</span>
				<?php endif; // End if categories ?>
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
