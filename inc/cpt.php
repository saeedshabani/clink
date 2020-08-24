<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Register the Clink Post Type
 */
 
function register_cpt_clink() {
	$clink_main_slug = get_option('clink_main_slug');
	if( empty( $clink_main_slug ) ){
		$clink_main_slug = 'clink';
	}
    $labels = array(
        'name'					=> __( 'Clink', 'aryan-themes' ),
        'singular_name'			=> __( 'Clink', 'aryan-themes' ),
		'menu_name'				=> __( 'clink', 'aryan-themes' ),
		'name_admin_bar'   	 	=> __( 'clink', 'aryan-themes' ),
        'add_new'				=> __( 'Add New', 'aryan-themes' ),
        'add_new_item'			=> __( 'Add New Link', 'aryan-themes' ),
        'new_item'				=> __( 'New Link', 'aryan-themes' ),
		'edit_item'				=> __( 'Edit Link', 'aryan-themes' ),
        'view_item'				=> __( 'View Link', 'aryan-themes' ),
		'all_items'				=> __( 'All Links', 'aryan-themes' ),
        'search_items'			=> __( 'Search Links', 'aryan-themes' ),
		'parent_item_colon'		=> __( 'Parent Links:', 'aryan-themes' ),
        'not_found'				=> __( 'No links found', 'aryan-themes' ),
        'not_found_in_trash'	=> __( 'No links found in Trash', 'aryan-themes' ),
    );
	
    $args = array(
        'labels' => $labels,
        'hierarchical'			=> false,
        'description'			=> 'Redirect your links in a new way',
        'supports'				=> array( 'title', 'author', 'revisions' ),
		'taxonomies'			=> array( 'clink_category' ),
        'public'				=> true,
        'show_ui'				=> true,
        'show_in_menu'			=> true,
        'menu_position'			=> 5,
        'menu_icon'				=> 'dashicons-admin-links',
        'show_in_nav_menus'		=> true,
        'publicly_queryable'	=> true,
        'exclude_from_search'	=> true,
        'has_archive'			=> false,
        'query_var'				=> true,
        'can__xport'			=> true,
        'rewrite'				=> array( 'slug' => $clink_main_slug ),
        'capability_type'		=> 'post',
    );
    register_post_type( 'clink', $args );
}
add_action( 'init', 'register_cpt_clink' );


/**
 * Register the Clink Taxonomies
 */

function register_clink_taxonomies() {
	$labels = array(
		'name'                       => _x( 'Categories', 'taxonomy general name', 'aryan-themes' ),
		'singular_name'              => _x( 'Category', 'taxonomy singular Name', 'aryan-themes' ),
		'menu_name'                  => __( 'Categories', 'aryan-themes' ),
		'all_items'                  => __( 'All Categories', 'aryan-themes' ),
		'parent_item'                => __( 'Parent Category', 'aryan-themes' ),
		'parent_item_colon'          => __( 'Parent Category:', 'aryan-themes' ),
		'new_item_name'              => __( 'New Category Name', 'aryan-themes' ),
		'add_new_item'               => __( 'Add New Category', 'aryan-themes' ),
		'edit_item'                  => __( 'Edit Category', 'aryan-themes' ),
		'update_item'                => __( 'Update Category', 'aryan-themes' ),
		'view_item'                  => __( 'View Category', 'aryan-themes' ),
		'separate_items_with_commas' => __( 'Separate Categories with commas', 'aryan-themes' ),
		'add_or_remove_items'        => __( 'Add or remove Categories', 'aryan-themes' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'aryan-themes' ),
		'popular_items'              => __( 'Popular Categories', 'aryan-themes' ),
		'search_items'               => __( 'Search Categories', 'aryan-themes' ),
		'not_found'                  => __( 'Not Found', 'aryan-themes' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => false,
	);
	register_taxonomy( 'clink_category', array( 'clink' ), $args );
}
add_action( 'init', 'register_clink_taxonomies', 0 );


/**
 * Display custom column for Clink post type
 */
 
function clink_columns_content($column, $post_id) {
	switch ( $column ) {
		case 'clink_url':
			$clink_url = get_post_meta( $post_id, 'clink_url' , true  );
			if ( $clink_url ) {
				echo '<a href="' . $clink_url . '">' . $clink_url . '</a>';
			} else {
				_e ( 'undefined', 'aryan-themes' );
			}		
			break;	
		case 'clink_permalink':
			echo '<input class="clink-permalink" type="text" value="' . esc_attr( get_permalink( $post_id ) ) . '" readonly />';
			break;			
		case 'clink_clicks':
			$clicks = get_post_meta( $post_id, 'clink_clicks' , true  );
			if ( $clicks ) {
				echo $clicks;
			} else {
				echo '0';
			}
			break;				
	}	
}


/**
 * Add custom column to Clink post list
 */
 
function add_clink_columns($columns) {
	return array(
		'cb'				=> '<input type="checkbox" />',
		'title'				=> __( 'Title', 'aryan-themes' ),
		'clink_url'			=> __( 'Redirect Url', 'aryan-themes' ),
		'clink_permalink'	=> __( 'Permalink' , 'aryan-themes' ),
		'clink_clicks'		=> __( 'Clicks', 'aryan-themes' ),
		'author'			=> __( 'Author', 'aryan-themes' ),
		'date'				=> __( 'Date', 'aryan-themes' ),			
	);
}


/**
 * Load custom columns only on Clink post type
 */
 
function clink_terms_exclusions() {
	global $current_screen;
	if( 'clink' != $current_screen->post_type )
			return;
			
	add_action( 'manage_posts_custom_column' , 'clink_columns_content', 10, 2 );
	add_filter( 'manage_clink_posts_columns' , 'add_clink_columns' );	
}
add_action( 'admin_head', 'clink_terms_exclusions' );		