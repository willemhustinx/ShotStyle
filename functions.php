<?php

/**
 * Load Teams
 */
require get_stylesheet_directory() . '/inc/teams.php';

/**
 * Load Events
 */
require get_stylesheet_directory() . '/inc/events.php';

/**
 * Load Sponsors
 */
require get_stylesheet_directory() . '/inc/sponsors.php';


/**
 * Enqueue scripts and styles.
 */
function shotstyle_scripts()
{
    // Flexslider sponsors
    wp_enqueue_script(
        'flexslider-sponsors', get_stylesheet_directory_uri() . '/inc/js/flexslider-sponsors.js', array(
        'jquery',
        'flexslider-js'
    ), '20180522', true
    );

    wp_enqueue_script('flexslider-js', get_template_directory_uri() . '/assets/js/vendor/flexslider.min.js', array('jquery'), '20140222', true);
}

add_action('wp_enqueue_scripts', 'shotstyle_scripts');


/**
 * Header menu (should you choose to use one)
 */
function shotstyle_header_menu()
{
    // display the WordPress Custom Menu if available
    wp_nav_menu(array(
        'menu' => 'primary',
        'theme_location' => 'primary',
        'depth' => 3,
        'container' => 'div',
        'container_class' => 'collapse navbar-collapse navbar-ex1-collapse',
        'menu_class' => 'nav navbar-nav',
        'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
        'walker' => new wp_bootstrap_navwalker()
    ));
} /* end header menu */


add_filter('the_content_more_link', 'modify_read_more_link');
function modify_read_more_link()
{
    return '<a class="btn btn-default read-more" href="' . get_permalink() . '">' . __('Read More', 'sparkling') . '</a>';
}


/* --------------------------------------------------------------
       Theme Widgets
-------------------------------------------------------------- */

function shotstyle_widgets_init()
{

    register_widget('shotstyle_recent_posts');
    register_widget('shotstyle_upcomming_events');
    register_widget('shotstyle_sponsors');
}

add_action('widgets_init', 'shotstyle_widgets_init');

require_once(get_stylesheet_directory() . '/inc/widgets/widget-recent-posts.php');
require_once(get_stylesheet_directory() . '/inc/widgets/widget-upcomming-events.php');
require_once(get_stylesheet_directory() . '/inc/widgets/widget-sponsors.php');


/* --------------------------------------------------------------
       Remove comments
-------------------------------------------------------------- */

// Disable support for comments and trackbacks in post types
function df_disable_comments_post_types_support()
{
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}

add_action('admin_init', 'df_disable_comments_post_types_support');

// Close comments on the front-end
function df_disable_comments_status()
{
    return false;
}

add_filter('comments_open', 'df_disable_comments_status', 20, 2);
add_filter('pings_open', 'df_disable_comments_status', 20, 2);

// Hide existing comments
function df_disable_comments_hide_existing_comments($comments)
{
    return array();
}

add_filter('comments_array', 'df_disable_comments_hide_existing_comments', 10, 2);

// Remove comments page in menu
function df_disable_comments_admin_menu()
{
    remove_menu_page('edit-comments.php');
}

add_action('admin_menu', 'df_disable_comments_admin_menu');

// Redirect any user trying to access comments page
function df_disable_comments_admin_menu_redirect()
{
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }
}

add_action('admin_init', 'df_disable_comments_admin_menu_redirect');

// Remove comments metabox from dashboard
function df_disable_comments_dashboard()
{
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}

add_action('admin_init', 'df_disable_comments_dashboard');

// Remove comments links from admin bar
function df_disable_comments_admin_bar()
{
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
}

add_action('init', 'df_disable_comments_admin_bar');


function rm_comments_att($open, $post_id)
{
    $post = get_post($post_id);
    if ($post->post_type == 'attachment') {
        return false;
    }
    return $open;
}

add_filter('comments_open', 'rm_comments_att', 10, 2);


?>