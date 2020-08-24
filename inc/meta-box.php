<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Add Clink meta box's to the links edit screen.
 */
 
function clink_info_meta_box() {
	add_meta_box(
		'clink-info',
		__( 'Link Informations', 'aryan-themes' ),
		'clink_link_info_callback',
		'clink',
		'normal',
		'high'
	);
	add_meta_box(
		'clink-infos',
		__( 'Stats and Info', 'aryan-themes' ),
		'clink_stats_info_callback',
		'clink',
		'side',
		'high'
	);	
}
add_action( 'add_meta_boxes', 'clink_info_meta_box' );


/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
 
function clink_stats_info_callback( $post ) {

	$post_id = $post->ID;
	$clicks = get_post_meta( $post_id, 'clink_clicks' , true  );		
	if ( !$clicks ){
		$clicks = '0';
	}

	echo '<p>';
		echo '<h4>' . __('Permalink','aryan-themes') . '</h4>';
		echo '<input class="clink-permalink" type="url" readonly="" value="' . get_post_permalink($post_id) . '">';
	echo '</p>';	
	echo '<p>';
		echo '<h4>' . __('Clicks','aryan-themes') . '</h4>';
		echo '<code>' . $clicks . '</code>';
	echo '</p>';
	
} 

function clink_link_info_callback( $post ) {

	$post_id = $post->ID;
	// Add a nonce field so we can check for it later.
	wp_nonce_field( '_clink_link_info_nonce', 'clink_link_info' ); 

	
	echo '<table class="form-table">';
		echo '<tbody>';
			echo '<tr>';
				echo '<th scope="row">' . __('Redirect Link URL','aryan-themes') . '</th>';
				echo '<td>';
					echo '<input class="clink-permalink" type="url" placeholder="http://aryanthemes.com" name="clink_url" id="clink_url" value="' . get_post_meta( $post_id, 'clink_url', true ) . '">';
					echo '<p class="description clink-description"><label for="clink_url">' . __( 'Please enter the redirect link URL in this filed.', 'aryan-themes' ) . '</label></p>';
				echo '</td>';
			echo '</tr>';		
			echo '<tr>';
				echo '<th scope="row">' . __('countdown','aryan-themes') . '</th>';
				echo '<td>';
					// decide how to set clink_post_countdown value
					$clink_post_countdown =  get_post_meta( $post_id, 'clink_post_countdown', true );
					if( $clink_post_countdown != ''){
						if( $clink_post_countdown === '0' ){
							$clink_post_countdown_0 = 'checked';
							$clink_post_countdown_1 = '';
							$clink_post_countdown_global = '';
						}
						elseif( $clink_post_countdown === '1' ){
							$clink_post_countdown_0 = '';
							$clink_post_countdown_1 = 'checked';
							$clink_post_countdown_global = '';
						}					
					}else{
						$clink_post_countdown_global = 'checked';
					}
					echo '<label class="clink-yes-no"><input type="radio" name="clink_post_countdown" value="" ' . $clink_post_countdown_global . '>' . __( 'Based on global settings' , 'aryan-themes') . '</label>';
					echo '<label class="clink-yes-no"><input type="radio" name="clink_post_countdown" value="1" ' . $clink_post_countdown_1 . '>' . __( 'Yes' , 'aryan-themes') . '</label>';
					echo '<label class="clink-yes-no"><input type="radio" name="clink_post_countdown" value="0" ' . $clink_post_countdown_0 . '>' . __( 'No' , 'aryan-themes') . '</label>';					
					echo '<p class="description clink-description">' . __( 'Do you want to show a countdown before redirect?', 'aryan-themes' ) . '</p>';
					echo '<p class="description clink-description"><span class="clink-notice">' . __('Notice','aryan-themes') . ' : </span>' . __('The value of this option only applied to this link. If you want to use global countdown settings, please select <code>Based on global settings</code>','aryan-themes') . '</p>';
				echo '</td>';
			echo '</tr>';
		echo '</tbody>';
	echo '</table>';
}


/**
 * When the Clink, link is saved, saves links data.
 *
 * @param int $post_id The ID of the post being saved.
 */
 
function clink_info_save( $post_id ) {


	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	*/
	
	 
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	
	// Verify that the nonce is valid.
	if ( ! isset( $_POST['clink_link_info'] ) || ! wp_verify_nonce( $_POST['clink_link_info'], '_clink_link_info_nonce' ) ) return;
	
	// Check the user's permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	
	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}	

	/* OK, it's safe for us to save the data now. */
	
	// Sanitize user input.
	$clink_url = esc_url( $_POST['clink_url'] );
	$clink_post_countdown = sanitize_text_field( esc_attr( $_POST['clink_post_countdown'] ) );
	
	// Update the meta field in the database.
	if ( isset( $clink_url ) ){
		update_post_meta( $post_id, 'clink_url', $clink_url );
	}
		
	if ( isset( $clink_post_countdown ) ){
		if( $clink_post_countdown != "" ){
			update_post_meta( $post_id, 'clink_post_countdown', $clink_post_countdown );
		}else{
			delete_post_meta( $post_id, 'clink_post_countdown' );
		}	
	}else{
		update_post_meta( $post_id, 'clink_post_countdown', null );
	}
	
	$clicks_key = 'clink_clicks';
	$clicks = get_post_meta($post_id, $clicks_key, true);
	//If the the Post Custom Field value is empty. 
	if($clicks == ''){
		$clicks = 0; // set the clicks to one.
		
		//Delete all custom fields with the specified key from the specified post. 
		delete_post_meta($post_id, $clicks_key);
					 
		//Add a custom (meta) field (Name/value)to the specified post.
		add_post_meta($post_id, $clicks_key, $clicks);
				 
		//If the the Post Custom Field value is NOT empty.
	}
}
add_action( 'save_post', 'clink_info_save' );

?>