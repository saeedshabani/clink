<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Add Clink widget to the dashboard.
 */

function add_clink_dashboard_widgets() {

	wp_add_dashboard_widget(
		'clink_dashboard_widget',         // Widget slug.
		__('Popular Links','aryan-themes'),         // Title.
		'clink_dashboard_widget_function' // Display function.
    );
	
 	// Globalize the metaboxes array, this holds all the widgets for wp-admin
 
 	global $wp_meta_boxes;
 	
 	// Get the regular dashboard widgets array 
 	// (which has our new widget already but at the end)
 
 	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
 	
 	// Backup and delete our new dashboard widget from the end of the array
 
 	$clink_dashboard_backup = array( 'clink_dashboard_widget' => $normal_dashboard['clink_dashboard_widget'] );
 	unset( $normal_dashboard['clink_dashboard_widget'] );
 
 	// Merge the two arrays together so our widget is at the beginning
 
 	$sorted_dashboard = array_merge( $clink_dashboard_backup, $normal_dashboard );
 
 	// Save the sorted array back into the original metaboxes 
 
 	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
	
}
add_action( 'wp_dashboard_setup', 'add_clink_dashboard_widgets' );



/**
 * Create the function to output the contents of our Dashboard Widget.
 */

function clink_dashboard_widget_function() {

	// Display whatever it is you want to show
	
	$clink_posts = get_posts(
		array(
			'post_type' => 'clink',
			'post_status' => 'publish',
			'fields' => 'ids',
			'meta_key' => 'clink_clicks',
			'orderby' => 'meta_value_num',
			'order' => 'DESC',
			'posts_per_page' => 10,
		)
	);
		
	if ( empty( $clink_posts ) ) {
		echo '<p>' . __( 'There are no stats available yet!', 'aryan-themes' ) . '</p>';
	}else{
		$clink_clicks = array();
		
		foreach ( $clink_posts as $clink_post ) {
			$clink_clicks[ $clink_post ] = get_post_meta( $clink_post, 'clink_clicks', true );
		}

		if ( $clink_clicks ) {
			$max_count = max( $clink_clicks );
		}
		
		echo '<div class="clink_dashboard_widget_header clink-row">';
			echo '<div class="clink-col clink-col-70">' . __( 'Redirect to', 'aryan-themes' ) . '</div>';
			echo '<div class="clink-col clink-col-20">' . __( 'Clicks', 'aryan-themes' ) . '</div>';
			echo '<div class="clink-col clink-col-10">' . __('Edit', 'aryan-themes' ) . '</div>';
		echo '</div>';
		echo '<div class="clink_dashboard_widget_body">';
			echo '<ul>';
				echo '<script>';
					echo 'jQuery(function(){
						jQuery(".clink-clicks-bar").each(function(){
							var cc_bw = jQuery(this).width();
							var parentWidth = jQuery(this).offsetParent().width();
							var cc_bw = 100*cc_bw/parentWidth;							
							jQuery(this).width("0");
							jQuery(this).animate({width: cc_bw  + "%"}, 2000);
							console.log(cc_bw);
						});
					});';
				echo '</script>';
				foreach ( $clink_posts as $clink_post_id ) :
					
					$link       = get_post_meta( $clink_post_id, 'clink_url', true );
					$link_count = absint( get_post_meta( $clink_post_id, 'clink_clicks', true ) );
					$percent	= ($link_count / 1600) * 100; 
					$width		= $link_count / ( $max_count ? $max_count : 1 ) * 87;
						
					echo '<li class="clink-row">';
					
						echo '<div class="clink-clicks-bar" style="width: ' .  $width . '%"></div>';
						
						echo '<div class="clink-redirect clink-col clink-col-70">';
							echo '<a href="' . $link . '">' . $link . '</span></a>';
						echo '</div>';
						
						echo '<div class="clink-col clink-col-20">';
							echo '<span class="clink-clicks">' . $link_count . '</span>';
						echo '</div>';
						
						echo '<div class="clink-edit-link clink-col clink-col-10">';
							echo '<a href="' . get_edit_post_link( $clink_post_id ) . '"><span class="dashicons dashicons-edit"></span></a>';
						echo '</div>';
						
					echo '</li>';
					
				endforeach;
				
			echo '</ul>';
			
		echo '</div>';
	}	
}