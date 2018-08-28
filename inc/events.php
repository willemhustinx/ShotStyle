<?php

/**
 * Adds Custom post type Events
 *
 * @package Shotstyle
 */

add_action('init', 'events_post_types_init');

function events_post_types_init()
{
    create_events();
}

function create_events()
{
    register_post_type(
        'event', array(
            'labels' => array(
                'name' => __('Events'),
                'singular_name' => __('Event'),
                'add_new' => __('Add New'),
                'add_new_item' => __('Add New Event'),
                'edit' => __('Edit'),
                'edit_item' => __('Edit Event'),
                'new_item' => __('New Event'),
                'view' => __('View'),
                'view_item' => __('View Event'),
                'search_items' => __('Search Events'),
                'not_found' => __('No Events found'),
                'not_found_in_trash' => __('No Events found in Trash'),
                'parent' => __('Parent Event')
            ),

            'public' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'revisions'),
            'taxonomies' => array(''),
            'has_archive' => true,
            'capability_type' => 'page',
            'show_ui' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'hierarchical' => true,
            'show_in_nav_menus' => true,
            'menu_icon' => 'dashicons-calendar-alt',
        )
    );
}


function ep_eventposts_metaboxes()
{
    add_meta_box('ept_event_date_start', 'Start Date and Time', 'ept_event_date', 'event', 'normal', 'default', array('id' => '_start'));
}

add_action('admin_init', 'ep_eventposts_metaboxes');
// Metabox HTML
function ept_event_date($post, $args)
{
    $metabox_id = $args['args']['id'];
    global $post, $wp_locale;
    // Use nonce for verification
    wp_nonce_field(plugin_basename(__FILE__), 'ep_eventposts_nonce');
    $time_adj = current_time('timestamp');
    $month = get_post_meta($post->ID, 'event_month', true);
    if (empty($month)) {
        $month = gmdate('m', $time_adj);
    }
    $day = get_post_meta($post->ID, 'event_day', true);
    if (empty($day)) {
        $day = gmdate('d', $time_adj);
    }
    $year = get_post_meta($post->ID, 'event_year', true);
    if (empty($year)) {
        $year = gmdate('Y', $time_adj);
    }
    $hour = get_post_meta($post->ID, 'event_hour', true);
    if (empty($hour)) {
        $hour = gmdate('H', $time_adj);
    }
    $min = get_post_meta($post->ID, 'event_minute', true);
    if (empty($min)) {
        $min = '00';
    }

    $month_s = '<select name="event_month">';
    for ($i = 1; $i < 13; $i = $i + 1) {
        $month_s .= "\t\t\t" . '<option value="' . zeroise($i, 2) . '"';
        if ($i == $month) {
            $month_s .= ' selected="selected"';
        }
        $month_s .= '>' . $wp_locale->get_month_abbrev($wp_locale->get_month($i)) . "</option>\n";
    }
    $month_s .= '</select>';

    echo '<input type="text" name="event_day" value="' . $day . '" size="2" maxlength="2" />';
    echo $month_s;
    echo '<input type="text" name="event_year" value="' . $year . '" size="4" maxlength="4" /> @ ';
    echo '<input type="text" name="event_hour" value="' . $hour . '" size="2" maxlength="2"/>:';
    echo '<input type="text" name="event_minute" value="' . $min . '" size="2" maxlength="2" />';
}

// Save the Metabox Data
function ep_eventposts_save_meta($post_id, $post)
{
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) ||
        (!isset($_POST['ep_eventposts_nonce'])) ||
        (!wp_verify_nonce($_POST['ep_eventposts_nonce'], plugin_basename(__FILE__))) ||
        (!current_user_can('edit_post', $post->ID))) {
        return;
    }

    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though
    $events_meta['event_month'] = $_POST['event_month'];
    $events_meta['event_day'] = $_POST['event_day'];
    $events_meta['event_hour'] = $_POST['event_hour'];
    $events_meta['event_year'] = $_POST['event_year'];
    $events_meta['event_hour'] = $_POST['event_hour'];
    $events_meta['event_minute'] = $_POST['event_minute'];
    $events_meta['event_timestamp'] = mktime($_POST['event_hour'], $_POST['event_minute'], 0, $_POST['event_month'], $_POST['event_day'], $_POST['event_year']);


    // Add values of $events_meta as custom fields
    foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
        if ($post->post_type == 'revision') { return; } // Don't store custom data twice
        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
        if (get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        } else { // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);
        }
        if (!$value) {
            delete_post_meta($post->ID, $key); // Delete if blank
        }
    }
}

add_action('save_post', 'ep_eventposts_save_meta', 1, 2);


/**
 * Helpers to display the date on the front end
 */
// Get the Month Abbreviation
function eventposttype_get_the_month_abbr($month)
{
    global $wp_locale;
    for ($i = 1; $i < 13; $i = $i + 1) {
        if ($i == $month) {
            $monthabbr = $wp_locale->get_month_abbrev($wp_locale->get_month($i));
        }
    }
    return $monthabbr;
}

// Display the date
function eventposttype_get_the_event_date()
{
    global $post;

    echo date('j M Y \a\t H:i', get_post_meta(get_the_ID(), 'event_timestamp', true));
}

function event_get_date_box()
{

    $box = "<time datetime=\"" . date('Y-m-d', get_post_meta(get_the_ID(), 'event_timestamp', true)) . "\" class=\"icon\">";
    $box .= "<span>" . date('j', get_post_meta(get_the_ID(), 'event_timestamp', true)) . "</span>";
    $box .= "<strong>" . date('M', get_post_meta(get_the_ID(), 'event_timestamp', true)) . "</strong>";
    $box .= "</time>";

    echo $box;

}

function event_date_time()
{
    $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';

    $date_string = sprintf($time_string,
        date('Y-m-d H:!', get_post_meta(get_the_ID(), 'event_timestamp', true)),
        date_i18n('j F Y', get_post_meta(get_the_ID(), 'event_timestamp', true))
    );

    $time_string = sprintf($time_string,
        date('Y-m-d H:i', get_post_meta(get_the_ID(), 'event_timestamp', true)),
        date_i18n('H:i', get_post_meta(get_the_ID(), 'event_timestamp', true))
    );

    printf('<span class="posted-on"><i class="fa fa-calendar-alt"></i> %1$s</span><span class="byline"> <span class="posted-on"><i class="fa fa-clock"></i> %2$s</span><span class="byline"> <i class="fa fa-user"></i> %3$s</span>',
        sprintf('<a href="%1$s" rel="bookmark">%2$s</a>',
            esc_url(get_permalink()),
            $date_string
        ),
        sprintf('<a href="%1$s" rel="bookmark">%2$s</a>',
            esc_url(get_permalink()),
            $time_string
        ),
        sprintf('<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
            esc_url(get_author_posts_url(get_the_author_meta('ID'))),
            esc_html(get_the_author())
        )
    );
}

