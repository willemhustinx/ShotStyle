<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package ShotStyle
 */
?>

<?php
if ( is_page_template( 'page-fullwidth.php' ) ) {
    the_post_thumbnail(
        'sparkling-featured-fullwidth', array(
            'class' => 'single-featured',
        )
    );
} else {
    the_post_thumbnail(
        'sparkling-featured', array(
            'class' => 'single-featured',
        )
    );
}
?>

<div class="post-inner-content">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header page-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </header><!-- .entry-header -->

        <div class="entry-content">
            <?php
            the_content();
            wp_link_pages(
                array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'sparkling' ),
                    'after'  => '</div>',
                )
            );
            ?>

            <?php
            // Checks if this is homepage to enable homepage widgets
            if ( is_front_page() ) :
                get_sidebar( 'home' );
            endif;
            ?>
        </div><!-- .entry-content -->

        <?php
        if ( is_page_template( 'page-sponsors.php' ) ) {

            $featured_args = array(
                'ignore_sticky_posts' => 1,
                'post_type' => 'sponsor',
                'orderby' => 'rand',
            );

            $featured_query = new WP_Query($featured_args);

            if ($featured_query->have_posts()) :
                ?>
                <h3>Onze Sponsoren</h3>
                <div class="sponsors">
                    <?php
                    while ($featured_query->have_posts()) : $featured_query->the_post();
                        ?>
                        <div class="sponsor">

                            <?php

                            $url = get_post_meta(get_the_ID(), 'sponsor_url', true);

                            if (!filter_var($url, FILTER_VALIDATE_URL) === false){
                            ?>
                            <a href="<?php echo $url; ?>" target="_blank">
                                <?php
                                }

                                if (has_post_thumbnail()) {
                                    echo get_the_post_thumbnail(get_the_ID(), 'medium', array('class' => 'sponsor-img'));
                                } else {
                                    echo '<h1>' . get_the_title() . '</h1>';
                                }

                                if (!filter_var($url, FILTER_VALIDATE_URL) === false){
                                ?>
                            </a>
                        <?php
                        }
                        ?>
                        </div>

                    <?php
                    endwhile;
                    ?>
                </div>
            <?php
            endif;
            wp_reset_query();
        }
        ?>

        <?php if ( get_edit_post_link() ) : ?>
            <footer class="entry-footer">
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
            </footer><!-- .entry-footer -->
        <?php endif; ?>
    </article><!-- #post-## -->
</div>
