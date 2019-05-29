<?php

/**
 * Adds Custom post type teams
 * 
 * https://www.taniarascia.com/wordpress-part-three-custom-fields-and-metaboxes/
 *
 * @package Shotstyle
 */

function create_post_teams()
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
add_action( 'init', 'create_post_teams' );

function add_competition_meta_box() {
    add_meta_box(
        'competition',
        __('Nevobo Competition'),
        'show_competition_meta_box',
        'team',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'add_competition_meta_box' );

function show_competition_meta_box() {
    global $post;
    $meta = get_post_meta( $post->ID, 'competition', true ); 

    $fields = array(
        array('name' => 'regio', 'label' => 'Regio', 'type' => 'text'),
        array('name' => 'poule', 'label' => 'Poule', 'type' => 'text'),
        array('name' => 'verenigingscode', 'label' => 'Verenigings Code', 'type' => 'text'),
        array('name' => 'teamtype', 'label' => 'Team Type', 'type' => 'text'),
        array('name' => 'volgnummer', 'label' => 'Volgnummer', 'type' => 'text'),
    );
    ?>

    <input type="hidden" name="competition_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">

    <table class="form-table">
        <tbody>
        <?php
        
        foreach($fields as $field){
            ?>
            <tr>
                <th scope="row"><label for="competition[<?php echo $field['name'] ?>]"><?php echo $field['label'] ?></label></th>
                <td><input name="competition[<?php echo $field['name'] ?>]" type="text" id="competition[<?php echo $field['name'] ?>]" value="<?php  if (is_array($meta) && isset($meta[$field['name']])){ echo $meta[$field['name']]; } ?>" class="regular-text">
            </tr>
            <?php
        }
        ?>

        </tbody>
    </table>


    <?php 
}

function save_competition_meta( $post_id ) {    
    // verify nonce
    if (!isset($_POST['competition_nonce']) || !wp_verify_nonce( $_POST['competition_nonce'], basename(__FILE__) ) ) {
        return $post_id;
    }
    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
    // check permissions
    if ( 'page' === $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) ) {
            return $post_id;
        } elseif ( !current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }
    }

    $old = get_post_meta( $post_id, 'competition', true );
    $new = $_POST['competition'];

    if ( $new && $new !== $old ) {
        update_post_meta( $post_id, 'competition', $new );
    } elseif ( '' === $new && $old ) {
        delete_post_meta( $post_id, 'competition', $old );
    }
}
add_action( 'save_post', 'save_competition_meta' );

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
add_filter('posts_orderby', 'custom_team_order');

/*

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

*/
?>
