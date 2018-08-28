<?php

/**
 * ShotStyle Upcomming Events Widget
 */

class shotstyle_upcomming_events extends WP_Widget
{
    function shotstyle_upcomming_events()
    {

        $widget_ops = array('classname' => 'shotstyle-upcomming-events', 'description' => esc_html__("ShotStyle Upcomming Events Widget", 'shotstyle'));
        parent::__construct('shotstyle_upcomming_events', esc_html__('ShotStyle Upcomming Events Widget', 'shotstyle'), $widget_ops);
    }

    function widget($args, $instance)
    {
        extract($args);
        $title = isset($instance['title']) ? $instance['title'] : esc_html__('Upcomming Events', 'shotstyle');
        $limit = isset($instance['limit']) ? $instance['limit'] : 5;

        echo $before_widget;
        echo $before_title;
        echo $title;
        echo $after_title;

        /**
         * Widget Content
         */
        ?>

        <!-- upcomming events posts -->
        <div class="upcomming-events-wrapper">

            <?php
            $date = new DateTime();
            $beginOfDay = strtotime("midnight", $date->getTimestamp());

            $featured_args = array(
                'posts_per_page' => $limit,
                'ignore_sticky_posts' => 1,
                'post_type' => 'event',
                'meta_key' => 'event_timestamp',
                'meta_query' => array(
                    array(
                        'key' => 'event_timestamp',
                        'value' => $beginOfDay,
                        'compare' => '>='
                    )
                ),
                'orderby' => 'meta_value_num',
                'order' => 'ASC'
            );


            $featured_query = new WP_Query($featured_args);

            /**
             * Check if zilla likes plugin exists
             */
            if ($featured_query->have_posts()) : while ($featured_query->have_posts()) : $featured_query->the_post();
                ?>
                <!-- post -->
                <div class="post">

                    <!-- image -->
                    <div class="post-image <?php echo get_post_format(); ?>">

                        <a href="<?php echo get_permalink(); ?>">
                            <?php event_get_date_box(); ?></a>

                    </div> <!-- end post image -->

                    <!-- content -->
                    <div class="post-content">

                        <a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a>
                        <span class="date"><?php echo date('j M Y \@ H:i', get_post_meta(get_the_ID(), 'event_timestamp', true)); ?></span>


                    </div><!-- end content -->
                </div><!-- end post -->

            <?php

            endwhile; endif;
            wp_reset_query();

            ?>

        </div> <!-- end posts wrapper -->

        <?php

        echo $after_widget;
    }

    function form($instance)
    {

        if (!isset($instance['title'])) {
            $instance['title'] = esc_html__('Upcomming Events', 'shotstyle');
        }
        if (!isset($instance['limit'])) {
            $instance['limit'] = 5;
        }

        ?>

        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title', 'shotstyle') ?></label>

            <input type="text" value="<?php echo esc_attr($instance['title']); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>"
                   id="<?php $this->get_field_id('title'); ?>"
                   class="widefat"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('limit'); ?>"><?php esc_html_e('Limit Posts Number', 'shotstyle') ?></label>

            <input type="text" value="<?php echo esc_attr($instance['limit']); ?>"
                   name="<?php echo $this->get_field_name('limit'); ?>"
                   id="<?php $this->get_field_id('limit'); ?>"
                   class="widefat"/>
        <p>

        <?php
    }
}

?>