<?php

/**
 * Adds Custom post type teams
 *
 * @package Shotstyle
 */

add_action('init', 'teams_post_types_init');

function teams_post_types_init()
{
    create_teams();
    create_team_categories();
}

add_action('admin_menu', 'team_ranking_pages');

function team_ranking_pages()
{
    add_submenu_page('edit.php?post_type=team', __('Team rankings'), __('Team rankings'), 'edit_posts', basename(__FILE__), 'team_rankings_page');
}

function create_teams()
{
    register_post_type(
        'team', array(
            'labels' => array(
                'name' => __('Teams'),
                'singular_name' => __('Team'),
                'add_new' => __('Add New'),
                'add_new_item' => __('Add New Team'),
                'edit' => __('Edit'),
                'edit_item' => __('Edit Team'),
                'new_item' => __('New Team'),
                'view' => __('View'),
                'view_item' => __('View Team'),
                'search_items' => __('Search Teams'),
                'not_found' => __('No Teams found'),
                'not_found_in_trash' => __('No Teams found in Trash'),
                'parent' => __('Parent Team')
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
            'menu_icon' => 'dashicons-shield-alt',
        )
    );
}

function create_team_categories()
{
    register_taxonomy(
        'team-category',
        array('team'),
        array(
            'hierarchical' => true,
            'labels' => array(
                'name' => __('Team Categories'),
                'singular_name' => __('Team Category'),
                'search_items' => __('Search Categories'),
                'all_items' => __('All Categories'),
                'parent_item' => __('Parent Category'),
                'parent_item_colon' => __('Parent Category:'),
                'edit_item' => __('Edit Category'),
                'update_item' => __('Update Category'),
                'add_new_item' => __('Add New Category'),
                'new_item_name' => __('New Category Name'),
                'menu_name' => __('Categories'),
            ),
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'teamcat'),
        )
    );
}

function rename_team_meta_boxes()
{
    remove_meta_box('postimagediv', 'team', 'side');
    add_meta_box('postimagediv', __('Team Photo', 'shotstyle'), 'post_thumbnail_meta_box', 'team', 'side', 'low');
}

add_action('add_meta_boxes_team', 'rename_team_meta_boxes');

function competition_add()
{
    add_meta_box(
        'competition',
        __('NeVoBo competition'),
        'competition_cb',
        'team',
        'normal',
        'high'
    );
}

add_action('admin_init', 'competition_add');

add_filter('posts_orderby', 'custom_team_order');
function custom_team_order($orderby)
{
    global $wpdb;

    // Check if the query is for an archive
    if (is_archive() && (get_query_var("post_type") == "team" || is_tax('team-category'))) {
        // Query was for archive, then set order
        return "$wpdb->posts.post_title ASC";
    }

    return $orderby;
}


function competition_cb()
{
    global $post;

    $meta = get_post_meta($post->ID, '_competition_meta', TRUE);

    ?>
    <table class="form-table
        <tr valign=" top">
    <th scope="row"><label for="regio">Regio</label></th>
    <td><input name="regio" type="text" id="regio" class="regular-text" value="<?php if (isset($meta['regio'])) {
            echo $meta['regio'];
        } ?>"/></td>
    </tr>

    <tr valign="top">
        <th scope="row"><label for="poule">Poule</label></th>
        <td><input name="poule" type="text" id="poule" class="regular-text" value="<?php if (isset($meta['poule'])) {
                echo $meta['poule'];
            } ?>"/></td>
    </tr>

    <tr valign="top">
        <th scope="row"><label for="verenigingscode">Verenigings code</label></th>
        <td><input name="verenigingscode" type="text" id="verenigingscode" class="regular-text"
                   value="<?php if (isset($meta['verenigingscode'])) {
                       echo $meta['verenigingscode'];
                   } ?>"/></td>
    </tr>

    <tr valign="top">
        <th scope="row"><label for="teamtype">Team type</label></th>
        <td><input name="teamtype" type="text" id="teamtype" class="regular-text"
                   value="<?php if (isset($meta['teamtype'])) {
                       echo $meta['teamtype'];
                   } ?>"/></td>
    </tr>

    <tr valign="top">
        <th scope="row"><label for="volgnummer">Volgnummer</label></th>
        <td><input name="volgnummer" type="text" id="volgnummer" class="regular-text"
                   value="<?php if (isset($meta['volgnummer'])) {
                       echo $meta['volgnummer'];
                   } ?>"/></td>
    </tr>

    </table>

    <?php
    // create a custom nonce for submit verification later
    echo '<input type="hidden" name="competition_nonce" value="' . wp_create_nonce(__FILE__) . '" />';
}

// Save the Data
function competition_save($post_id)
{
    // verify nonce
    if (!wp_verify_nonce($_POST['competition_nonce'], __FILE__)) {
        return $post_id;
    }
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    // check permissions
    if ('team' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)){
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    $current_data = get_post_meta($post_id, '_competition_meta', TRUE);

    $new_data['regio'] = $_POST['regio'];
    $new_data['poule'] = $_POST['poule'];
    $new_data['verenigingscode'] = $_POST['verenigingscode'];
    $new_data['teamtype'] = $_POST['teamtype'];
    $new_data['volgnummer'] = $_POST['volgnummer'];

    update_post_meta($post_id, '_competition_meta', $new_data);
}

add_action('save_post', 'competition_save');

function team_get_competition_standings()
{
    if(!is_single(get_the_ID())){
        return;
    }

    $competitionmeta = get_post_meta(get_the_ID(), '_competition_meta', TRUE);

    if (isset($competitionmeta['regio']) && $competitionmeta['regio'] != "" &&
        isset($competitionmeta['poule']) && $competitionmeta['poule'] != "") {
        $url = 'https://api.nevobo.nl/export/poule/%1$s/%2$s/stand.rss';

        $file = sprintf($url, $competitionmeta['regio'], $competitionmeta['poule']);

        $xml = simplexml_load_file($file);
        $data = (string)$xml->channel[0]->item->description;
        $data = explode("<br />", $data);
        $data = array_slice($data, 1, -1);

        $content = "<table class=\"table table-hover\">";
        $content .= "    <thead>";
        $content .= "        <tr>";
        $content .= "            <th></th>";
        $content .= "            <th>Team</th>";
        $content .= "            <th>Wedstrijden</th>";
        $content .= "            <th>Punten</th>";
        $content .= "        </tr>";
        $content .= "    </thead>";
        $content .= "    <tbody>";

        foreach ($data as $string) {
            list($nummer, $string) = explode('. ', $string, 2);
            list($team, $wedstrijden, $punten) = explode(', ', $string, 3);
            $wedstrijden = substr($wedstrijden, 8);
            $punten = substr($punten, 8);

            $content .= "        <tr>";
            $content .= "            <td>" . $nummer . "</td>";
            $content .= "            <td>" . $team . "</td>";
            $content .= "            <td>" . $wedstrijden . "</td>";
            $content .= "            <td>" . $punten . "</td>";
            $content .= "        </tr>";
        }

        $content .= "    </tbody>";
        $content .= "</table>";
    }

    return $content;
}

add_shortcode('team_standings', 'team_get_competition_standings');


function team_get_matches()
{
    if(!is_single(get_the_ID())){
        return;
    }

    $competitionmeta = get_post_meta(get_the_ID(), '_competition_meta', TRUE);

    if (isset($competitionmeta['verenigingscode']) && $competitionmeta['verenigingscode'] != "" &&
        isset($competitionmeta['teamtype']) && $competitionmeta['teamtype'] != "" &&
        isset($competitionmeta['volgnummer']) && $competitionmeta['volgnummer'] != "") {
        $file = 'https://api.nevobo.nl/export/team/' . $competitionmeta['verenigingscode'] . '/' . $competitionmeta['teamtype'] . '/' . $competitionmeta['volgnummer'] . '/programma.rss';

        $xml = simplexml_load_file($file);

        $data = $xml->channel[0]->item;

        $content = "<table class=\"table table-hover\">";
        $content .= "    <thead>";
        $content .= "        <tr>";
        $content .= "            <th>Datum<br/>Tijd</th>";
        $content .= "            <th colspan=\"2\">Wedstrijd</th>";
        $content .= "            <th>Code</th>";
        $content .= "            <th>Locatie</th>";
        $content .= "        </tr>";
        $content .= "    </thead>";
        $content .= "    <tbody>";

        foreach ($data as $item) {
            $titel = trim((string)$item->title);
            list($datum, $titel) = explode(': ', $titel, 2);
            $datum = substr($datum, 0, -6);
            list($team1, $team2) = explode(' - ', $titel, 2);

            $string = trim((string)$item->description);
            list($wedstrijdcode, $zooi, $tijd, $locatie) = explode(', ', $string, 4);
            $wedstrijdcode = substr($wedstrijdcode, 15);
            $locatie = substr($locatie, 14);
            $locatie = str_replace(',', '<br />', $locatie);

            $content .= "        <tr>";
            $content .= "            <td>" . $datum . "<br/>" . $tijd . "</td>";
            $content .= "            <td>" . $team1 . "</td>";
            $content .= "            <td>" . $team2 . "</td>";
            $content .= "            <td>" . $wedstrijdcode . "</td>";
            $content .= "            <td>" . $locatie . "</td>";
            $content .= "        </tr>";
        }

        $content .= "    </tbody>";
        $content .= "</table>";
    }

    return $content;
}

add_shortcode('team_matches', 'team_get_matches');


function team_rankings_page()
{


    $featured_args = array(
        'posts_per_page' => -1,
        'ignore_sticky_posts' => 1,
        'post_type' => 'team',
        'orderby' => 'title',
        'order' => 'ASC',
    );

    $featured_query = new WP_Query($featured_args);


    if ($featured_query->have_posts()) :

        while ($featured_query->have_posts()) : $featured_query->the_post();

            $competitionmeta = get_post_meta(get_the_ID(), '_competition_meta', TRUE);

            if (isset($competitionmeta['regio']) && $competitionmeta['regio'] != "" &&
                isset($competitionmeta['poule']) && $competitionmeta['poule'] != "") :

                ?>

                <div>
                    <?php echo '<h1>' . get_the_title() . '</h1>'; ?>

                    <?php echo team_get_competition_standings(); ?>
                </div>

            <?php

            endif;

        endwhile;

    endif;
    wp_reset_query();
}

?>
















