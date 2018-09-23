<?php
/**
 * @package ShotStyle
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php the_post_thumbnail('sparkling-featured', array('class' => 'single-featured')); ?>
    <div class="post-inner-content">
        <header class="entry-header page-header">

            <h1 class="entry-title "><?php the_title(); ?></h1>

            <div class="entry-meta">
                <?php if (get_edit_post_link()) : ?>
                    <?php
                    edit_post_link(
                        sprintf(
                        /* translators: %s: Name of current post */
                            esc_html__('Edit %s', 'sparkling'),
                            the_title('<span class="screen-reader-text">"', '"</span>', false)
                        ),
                        '<i class="fa fa-edit"></i><span class="edit-link">',
                        '</span>'
                    );
                    ?>
                <?php endif; ?>

            </div><!-- .entry-meta -->

        </header><!-- .entry-header -->

        <div class="entry-content">

            <?php
            $featured_args = array(
                'ignore_sticky_posts' => 1,
                'post_type' => 'sponsor',
                'orderby' => 'rand',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'sponsor-category',
                        'field' => 'slug',
                        'terms' => get_the_title(),
                    ),
                )
            );

            $featured_query = new WP_Query($featured_args);

            if ($featured_query->have_posts()) :
                ?>
                <div class="team-sponsors">
                    <?php
                    while ($featured_query->have_posts()) : $featured_query->the_post();
                        ?>
                        <div class="team-sponsor">

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
            ?>

            <?php the_content(); ?>

            <?php
            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'sparkling'),
                'after' => '</div>',
                'link_before' => '<span>',
                'link_after' => '</span>',
                'pagelink' => '%',
                'echo' => 1
            ));
            ?>
        </div><!-- .entry-content -->

    </div>

</article><!-- #post-## -->
