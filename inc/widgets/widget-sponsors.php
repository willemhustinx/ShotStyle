<?php

/**
 * ShotStyle Recent Posts Widget
 */

class shotstyle_sponsors extends WP_Widget
{
	 function shotstyle_sponsors(){

        $widget_ops = array('classname' => 'shotstyle-sponsors','description' => esc_html__( "ShotStyle Sponsorss Widget", 'shotstyle') );
		    parent::__construct('shotstyle_sponsors', esc_html__('shotstyle Sponsors Widget','shotstyle'), $widget_ops);
    }

    function widget($args , $instance) {
    	extract($args);
        $title = isset($instance['title']) ? $instance['title'] : esc_html__('Sponsors', 'shotstyle');
		$category = isset($instance['category']) ? $instance['category'] : 'nee';
		
		
      echo $before_widget;
      echo $before_title;
      echo $title;
      echo $after_title;

		/**
		 * Widget Content
		 */
    ?>

    <!-- sponsors -->
          <div class="sponsors-wrapper">

                <?php

                $featured_args = array(
					//'posts_per_page' => 5,
					'ignore_sticky_posts' => 1,
					'post_type' => 'sponsor',
					'orderby' => 'rand',
					'tax_query' => array(
						array(
							'taxonomy' => 'sponsor-category',
							'field'    => 'term-id',
							'terms'    => $category,
						),
					)
				);

                  $featured_query = new WP_Query($featured_args);

                  
                  if($featured_query->have_posts()) : 
				  ?>
					<div class="flexsliderSponsors">
						<ul class="slides">			  
				  <?php
					while($featured_query->have_posts()) : $featured_query->the_post();

                    ?>

							<li>
								<?php 
								
								$url = get_post_meta(get_the_ID(), 'sponsor_url', true);
								
								if(!filter_var($url, FILTER_VALIDATE_URL) === false){
									?>
									<a href="<?php echo $url; ?>" target="_blank">
									<?php
								}
								
								if(has_post_thumbnail())
								{
									echo get_the_post_thumbnail(get_the_ID() , 'medium', array('class' => 'sponsor-img'));
								} else {
									echo '<h1>' . get_the_title() . '</h1>';
								}
								
								if(!filter_var($url, FILTER_VALIDATE_URL) === false){
									?>
									</a>
									<?php
								}
								?>
							</li>

                    <?php
					endwhile;
					?>					
				  		</ul>
					</div>
				  <?php
				  endif; wp_reset_query();

                ?>
          </div> <!-- end sponsors wrapper -->

		<?php

		echo $after_widget;
    }

    function form($instance) {

      if(!isset($instance['title'])) $instance['title'] = esc_html__('Sponsors', 'shotstyle');
      if(!isset($instance['limit'])) $instance['limit'] = 5;

    	?>

      <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title', 'shotstyle') ?></label>

      <input  type="text" value="<?php echo esc_attr($instance['title']); ?>"
              name="<?php echo $this->get_field_name('title'); ?>"
              id="<?php $this->get_field_id('title'); ?>"
              class="widefat" />
      </p>

      <p><label for="<?php echo $this->get_field_id('category'); ?>"><?php esc_html_e('Category', 'shotstyle') ?></label>
			<?php wp_dropdown_categories( array( 'hide_empty'=> 0, 'name' => $this->get_field_name("category"), 'selected' => $instance["category"], 'taxonomy' => 'sponsor-category' ) ); ?>
      <p>

    	<?php
    }
}
?>