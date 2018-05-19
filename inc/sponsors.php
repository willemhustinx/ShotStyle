<?php

/**
 * Adds Custom post type sponsors
 *
 * @package Shotstyle
 */

add_action( 'init', 'sponsors_post_types_init' );

function sponsors_post_types_init()
{
	create_sponsors();
	create_sponsor_categories();
}

function create_sponsors()
{
	register_post_type( 
		'sponsor', array(
			'labels' => array(
				'name' => __( 'Sponsors' ),
                'singular_name' => __( 'Sponsor' ),
                'add_new' => __( 'Add New' ),
                'add_new_item' => __( 'Add New Sponsor' ),
                'edit' => __( 'Edit' ),
                'edit_item' => __( 'Edit Sponsor' ),
                'new_item' => __( 'New Sponsor' ),
                'view' => __( 'View' ),
                'view_item' => __( 'View Sponsor' ),
                'search_items' => __( 'Search Sponsors' ),
                'not_found' => __( 'No Sponsors found' ),
                'not_found_in_trash' => __( 'No Sponsors found in Trash' ),
                'parent' => __( 'Parent Sponsor' )
			),

			'public' => true,
            'supports' => array( 'title', 'thumbnail' ),
            'taxonomies' => array( '' ),
            'has_archive' => true,
			'capability_type' => 'page',
			'show_ui' 				=> true,
			'publicly_queryable' 	=> true,
			'exclude_from_search' 	=> false,
			'hierarchical' 			=> true,
			'show_in_nav_menus' 	=> true,
			'menu_icon' 			=> 'dashicons-businessman',
		)
	);
}

function create_sponsor_categories()
{	
	register_taxonomy(
		'sponsor-category',
		array('sponsor'),
		array(
			'hierarchical' => true,
			'labels' => array(
				'name' => __( 'Sponsor Categories' ),
				'singular_name' => __( 'Sponsor Category' ),
				'search_items' =>  __( 'Search Categories' ),
				'all_items' => __( 'All Categories' ),
				'parent_item' => __( 'Parent Category' ),
				'parent_item_colon' => __( 'Parent Category:' ),
				'edit_item' => __( 'Edit Category' ), 
				'update_item' => __( 'Update Category' ),
				'add_new_item' => __( 'Add New Category' ),
				'new_item_name' => __( 'New Category Name' ),
				'menu_name' => __( 'Categories' ),
			),
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'sponsorcat' ),
		)
	);
}

function rename_sponsor_meta_boxes() {
	remove_meta_box( 'postimagediv', 'sponsor', 'side' );
	add_meta_box( 'postimagediv', __( 'Sponsor Logo', 'shotstyle' ), 'post_thumbnail_meta_box', 'sponsor', 'normal', 'low' );
}
add_action( 'add_meta_boxes_sponsor',  'rename_sponsor_meta_boxes' );


function sponsor_metaboxes() {
    add_meta_box( 'sponsor_website', __('Website', 'shotstyle'), 'sponsor_url', 'sponsor', 'normal', 'default', array( 'id' => '_start') );
}
add_action( 'admin_init', 'sponsor_metaboxes' );
// Metabox HTML
function sponsor_url($post, $args) {
    $metabox_id = $args['args']['id'];
    global $post, $wp_locale;
    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'sponsor_nonce' );
    
	$url = get_post_meta($post->ID, 'sponsor_url', true);
    if ( empty($url) ) {
        $url = '';
    } 
	
	?>
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="url">Url</label></th>
			<td><input name="sponsor_url" type="text" id="sponsor_url" class="regular-text" value="<?php echo $url; ?>" /></td>
		</tr>
	</table>
	<?php
	
}

// Save the Metabox Data
function sponsor_save_meta( $post_id, $post ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;
    if ( !isset( $_POST['sponsor_nonce'] ) )
        return;
    if ( !wp_verify_nonce( $_POST['sponsor_nonce'], plugin_basename( __FILE__ ) ) )
        return;
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ) )
        return;
	
	
    $sponsors_meta['sponsor_url'] = $_POST['sponsor_url'];
	
	// Add values of $sponsors_meta as custom fields
    foreach ( $sponsors_meta as $key => $value ) { // Cycle through the $sponsors_meta array!
        if ( $post->post_type == 'revision' ) return; // Don't store custom data twice
        $value = implode( ',', (array)$value ); // If $value is an array, make it a CSV (unlikely)
        if ( get_post_meta( $post->ID, $key, FALSE ) ) { // If the custom field already has a value
            update_post_meta( $post->ID, $key, $value );
        } else { // If the custom field doesn't have a value
            add_post_meta( $post->ID, $key, $value );
        }
        if ( !$value ) delete_post_meta( $post->ID, $key ); // Delete if blank
    }
}
add_action( 'save_post', 'sponsor_save_meta', 1, 2 );

?>
















