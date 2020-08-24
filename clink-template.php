<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * If Clink post exist
 */	
 
if( have_posts() ) :
	the_post();
	$post_id = get_the_ID();
	$clink_url = get_post_meta($post_id, 'clink_url', true);
	$clink_countdown_option = get_option('clink_countdown_option'); // global redirect link countdown option
	$clink_post_countdown = get_post_meta($post_id, 'clink_post_countdown', true); // single post redirect link countdown option


	/**
	 * If Clink URL defined
	 */	
		 
	if( $clink_url != '' ):
		 
		clink_clicks( $post_id , false );
		
		/**
		 * Generate Clink countdown page when is needed 
		 */
		 
		function clink_page_generator(){
			global $clink_url;
			global $post_id;
			
			$clink_powered_by_text = get_option('clink_powered_by_text');
			$clink_countdown_duration = get_option('clink_countdown_duration');
			$clink_countdown_meta_tags = get_option('clink_countdown_meta_tags');
			$clink_countdown_style = get_option('clink_countdown_style');
			if( !is_numeric( $clink_countdown_duration ) or empty( $clink_countdown_duration ) ) {
				$clink_countdown_duration = 10;
			}
			?>
			<!DOCTYPE html>
			<html>
				<head>
					<?php
						echo '<title>' . get_the_title( $post_id ) . ' - ' . get_bloginfo('name') . '</title>';
						echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
						if( $clink_countdown_meta_tags ){
							echo '<meta name="robots" content="' . $clink_countdown_meta_tags . '"/>';
						}
						wp_head();
						if( $clink_countdown_style ){
							echo '<style>' . $clink_countdown_style . '</style>';
						}
						echo "<script>
							var countdown_duration =" . $clink_countdown_duration . ";
							var redirect_target_url ='" . $clink_url . "';						
						</script>";				
					?>
				</head>
				<body <?php body_class(get_bloginfo( 'language' )); ?> <?php if ( is_rtl() ){ echo 'dir="rtl"'; } ?>> 
					<div class="clink-page">
						<div class="container">
							<div class="clink-box">
								<p><?php _e('You will redirect to the destination link In a moment','aryan-themes') ?></p>
								<div id="countdown"></div>
								<p class="problem"><?php printf( __( 'If the page does not redirect automatically, click on <a href="%s">this link</a>', 'aryan-themes' ) , $clink_url ); ?></p>
							</div>
						</div>
						<?php if( $clink_powered_by_text === "1" ){
							echo '<div class="aryan-themes"><p>';
								printf( __( 'Powered By <a href="%s">Aryan Themes</a>', 'aryan-themes' ) , 'http://aryanthemes.com' );
							echo '</p></div>';
						} ?>
					</div>
				</body>
			</html>
		<?php }
		
		
		/**
		 * Decide how to redirect to the Clink URL
		 */	
		 
		if( $clink_post_countdown === '1' or $clink_post_countdown === '0' ){
		
		
			/**
			 * If Clink post countdown set
			 */	
			 
			if( $clink_post_countdown === '1' ){
			
			
				/**
				 * If Clink post countdown set to on
				 */		
				 
				clink_page_generator();
				
			}elseif( $clink_post_countdown === '0' ){
			
			
				/**
				 * If Clink post countdown set to off
				 */	
				 
				wp_redirect( $clink_url, 301 );
				
			}
		}elseif( $clink_countdown_option === '1' ){
		
		
			/**
			 * If Global Clink countdown option set to on
			 */		
			 
			clink_page_generator();
			
		}elseif( $clink_countdown_option === '0' ){
		
		
			/**
			 * If Global Clink countdown option set to off
			 */		
			 
			wp_redirect( $clink_url, 301 );
			
		}else{
		
		
			/**
			 * If Clink post countdown option and Global Clink countdown option have no value or a wrong value
			 */	
			 
			wp_die( __( '<strong>Clink:</strong> An error occurred.', 'aryan-themes') , '', array( 'response' => 404, 'back_link' => true ) );
			
		}
	else:


		/**
		 * If Clink URL not defined
		 */	

		wp_die( __( '<strong>Clink:</strong> An error occurred.', 'aryan-themes') , '', array( 'response' => 404, 'back_link' => true ) );
		
	endif;
	
else:


	/**
	 * If Clink post not exist
	 */	

	wp_die( __( '<strong>Clink:</strong> An error occurred.', 'aryan-themes') , '', array( 'response' => 404, 'back_link' => true ) );
		
endif;

?>