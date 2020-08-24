<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Clink set single template function
 */

function set_clink_single_template($single_template) {

	$object = get_queried_object();
	
	if ($object->post_type == 'clink') {

		function clink_style() {
			global $wp_styles;
			$wp_styles = '';
			wp_enqueue_style( 'clink', CLINK_URL . 'assets/css/style.css');
			if ( get_bloginfo( 'language' ) == "fa-IR" ){
				wp_enqueue_style( 'clink-fa_IR', CLINK_URL . 'assets/css/fa_IR.css');
			}	
		}

		add_action('wp_print_styles','clink_style');
		
		function countdown_clink_scripts() {
			global $wp_scripts;
			$wp_scripts = '';
			wp_enqueue_script('jquery');
			wp_enqueue_script( 'countdown360', CLINK_URL . 'assets/js/jquery.countdown360.min.js' );
			wp_enqueue_script( 'clink', CLINK_URL . 'assets/js/main.js' );
		}	
		
		add_action( 'wp_enqueue_scripts', 'countdown_clink_scripts' );	
	
        $single_template = CLINK_DIR .'/clink-template.php';
		
		// Clean wp_head() function

		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'start_post_rel_link' );
		remove_action( 'wp_head', 'index_rel_link' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head');
		
    }
	
	return $single_template;
}


/**
 * Clink scripts function
 */
 
function clink_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'clink-main', CLINK_URL . 'assets/css/clink-style.css', array(), null );
}


/**
 * Add Clink admin style to the WordPress dashboard
 */
 
function clink_admin_style() {
	wp_enqueue_style( 'clink-admin-style', CLINK_URL . 'assets/css/admin.css' );
}


/**
 * Clink Count URL clicks function
 */
		
function clink_clicks( $ID , $view ) {
	$post_ID = $ID;
	$clicks_key = 'clink_clicks';
	$clicks = get_post_meta($post_ID, $clicks_key, true);
	if( !$view ){		
		//If the the Post Custom Field value is empty. 
		if($clicks == ''){
			$clicks = 1; // set the clicks to one.
					 
			//Delete all custom fields with the specified key from the specified post. 
			delete_post_meta($post_ID, $clicks_key);
					 
			//Add a custom (meta) field (Name/value)to the specified post.
			add_post_meta($post_ID, $clicks_key, $clicks);
				 
			//If the the Post Custom Field value is NOT empty.
		}else{
			$clicks++; //increment the counter by 1.
			//Update the value of an existing meta key (custom field) for the specified post.
			update_post_meta($post_ID, $clicks_key, $clicks);
		}
	}else{
		if($clicks == ''){
			$clicks = 0;
		}
		return $clicks;
	}
}


/**
 * Clink links query function
 */
 
function Clink_links_query_func(

	$arg = array(
		'links_cat'			=> 'all-categories',
		'links_number'		=> '10',
		'links_order'		=> 'DESC',
		'links_order_by'	=> 'clink_clicks',
		'show_clicks'		=> 1,
	)
	
){

	if( $arg['links_order_by'] == 'clink_clicks' ){
		$arg['links_order_by'] = 'meta_value_num';
		$links_meta_key	= 'clink_clicks';
	}else{
		$links_meta_key = '';
	}
		
	if( $arg['links_cat'] == 'all-categories' ){
		$links_tax_query = '';
	}else{
		$links_tax_query = array(
			array(
				'taxonomy' => 'clink_category',
				'field' => 'term_id',
				'terms'    => $arg['links_cat'],
			)
		);
	}
	
	$clink_widget_query_arg = array(
		'post_type'			=> 'clink',
		'post_status'		=> 'publish',
		'tax_query'			=> $links_tax_query,
		'order'				=> $arg['links_order'],
		'orderby'			=> $arg['links_order_by'],
		'meta_key'			=> $links_meta_key,
		'offset'			=> $arg['links_offset'],
		'post__not_in'		=> $arg['exclude_links'],
		'posts_per_page'	=> $arg['links_number'],
	);
	
 	$clink_widget_query = new WP_Query( $clink_widget_query_arg );
		
	if( $clink_widget_query->have_posts() ) :
	
		$Cl_qu_str = '<ul>';
		
			while( $clink_widget_query->have_posts() ) : 
			
				$clink_widget_query->the_post();
				$clink_link_title = get_the_title();
				$clink_link_url = get_the_permalink();
				
				if( $arg['show_clicks'] == 1 ){
					$clink_clicks = clink_clicks( get_the_ID() , true );
					$clink_clicks = '<span class="clink-clicks"> ' . $clink_clicks . ' </span>';
				}
	
				$Cl_qu_str .= '<li class="clink-' . get_the_ID() . '"><a href="' . $clink_link_url . '">' . $clink_link_title . $clink_clicks . '</a></li>';
				
			endwhile;
			
		$Cl_qu_str .= '</ul>';
	
	else:
	
		$Cl_qu_str = '<p>' . __( 'No links found!', 'aryan-themes' ) . '</p>';
	
	endif;
	
	wp_reset_query();

	return $Cl_qu_str;	
}