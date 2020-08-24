<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Set default Clink options value 
 */

if( !get_option('clink_main_slug') ){
	add_option(
		'clink_main_slug',
		$value = 'clink',
		$deprecated = '',
		$autoload = 'yes'
	);
}

if( !get_option('clink_powered_by_text') ){
	add_option(
		'clink_powered_by_text',
		$value = '1',
		$deprecated = '',
		$autoload = 'yes'
	);
}

if( !get_option('clink_countdown_option') ){
	add_option(
		'clink_countdown_option',
		$value = '1',
		$deprecated = '',
		$autoload = 'yes'
	);
}

if( !get_option('clink_countdown_duration') ){
	add_option(
		'clink_countdown_duration',
		$value = 10,
		$deprecated = '',
		$autoload = 'yes'
	);
}

if( !get_option('clink_countdown_meta_tags') ){
	add_option(
		'clink_countdown_meta_tags',
		$value = 'noindex,follow',
		$deprecated = '',
		$autoload = 'yes'
	);
}
	
/**
 * Add Clink menu to WordPress dashboard
 */
 
function clink_admin_menu() {
    add_submenu_page(
		'edit.php?post_type=clink', 
		__('Global Clink Settings','aryan-themes'),
		__('Global Settings','aryan-themes') , 'manage_options', 
        'clink-settings',
		'clink_settings'
	); 
}
add_action("admin_menu", "clink_admin_menu");


/**
 * Clink settings function
 */
 
function clink_settings(){
?>
	    <div class="wrap">
			<h2><span class="clink-main-dashicons dashicons dashicons-admin-links"></span><?php _e('Global Clink Settings','aryan-themes'); ?></h2>
			<?php settings_errors(); ?>
			<form method="post" action="options.php">
				<?php
					settings_fields("general-section");
					do_settings_sections("clink-options");      
					submit_button();
				?>
			</form>
		</div>
<?php
}

function display_clink_main_slug(){

	// validate and update clink_main_slug value

	$clink_main_slug_option = get_option('clink_main_slug');
	if( $clink_main_slug_option ){
		$clink_main_slug_option = str_replace(' ', '-', $clink_main_slug_option); // Replaces all spaces with hyphens.
		$clink_main_slug_option = preg_replace('/[^A-Za-z0-9\-]/', '', $clink_main_slug_option); // Removes special chars.	
		update_option( 'clink_main_slug', $value = $clink_main_slug_option );	
		$clink_main_slug_option = get_option('clink_main_slug');
	}elseif( empty($clink_main_slug_option ) ){
		update_option( 'clink_main_slug', $value = 'clink' );
		$clink_main_slug_option = get_option('clink_main_slug');
	}

	$settings_updated = $_GET['settings-updated'];
	
	if($settings_updated == 'true') {
		flush_rewrite_rules();
	}

	$clink_slug_structure = get_bloginfo('url') . '/' . $clink_main_slug_option . '/link-name';
	?>
    	<input type="text" name="clink_main_slug" id="clink_main_slug" value="<?php echo $clink_main_slug_option; ?>" />
		<p class="description clink-description"><?php _e('Enter the clink plugin base slug for links. In default it set to <code>clink</code>','aryan-themes'); ?></p>
		<p class="description clink-description"><?php printf( __( 'Current links structure is like: <code>%s</code>', 'aryan-themes' ) , $clink_slug_structure ); ?></p>
	<?php
}


function display_clink_powered_by_text(){
	// decide how to set clink_powered_by_text value
	$clink_powered_by_text = get_option('clink_powered_by_text');
	if( $clink_powered_by_text === '0' ){
		$clink_powered_by_text_0 = 'checked';
		$clink_powered_by_text_1 = '';
	}
	elseif( $clink_powered_by_text === '1' ){
		$clink_powered_by_text_0 = '';
		$clink_powered_by_text_1 = 'checked';
	} ?>
		<label class="clink-yes-no"><input type="radio" name="clink_powered_by_text" value="1" <?php echo $clink_powered_by_text_1; ?>><?php _e('Yes' , 'aryan-themes'); ?></label>
		<label class="clink-yes-no"><input type="radio" name="clink_powered_by_text" value="0" <?php echo $clink_powered_by_text_0; ?>><?php _e('No' , 'aryan-themes'); ?></label>		
		<p class="description clink-description"><?php _e('Do you want to display <code>Powered By Aryan Themes</code> text in redirect page?','aryan-themes'); ?></p>
	<?php
}


function display_clink_countdown_option(){
	// decide how to set clink_countdown_option value
	$clink_countdown_option = get_option('clink_countdown_option');
	if( $clink_countdown_option === '0' ){
		$clink_countdown_option_0 = 'checked';
		$clink_countdown_option_1 = '';
	}
	elseif( $clink_countdown_option === '1' ){
		$clink_countdown_option_0 = '';
		$clink_countdown_option_1 = 'checked';
	} ?>
		<label class="clink-yes-no"><input type="radio" name="clink_countdown_option" value="1" <?php echo $clink_countdown_option_1; ?>><?php _e('Yes' , 'aryan-themes'); ?></label>
		<label class="clink-yes-no"><input type="radio" name="clink_countdown_option" value="0" <?php echo $clink_countdown_option_0; ?>><?php _e('No' , 'aryan-themes'); ?></label>		
		<p class="description clink-description"><?php _e('Do you want to show a countdown before redirect?','aryan-themes'); ?></p>
		<p class="description clink-description"><span class="clink-notice"><?php _e('Notice','aryan-themes'); ?> : </span><?php _e('When you add or edit links, you can manage this option for each link Separately.','aryan-themes'); ?></p>
	<?php
}


function display_clink_countdown_duration(){
	
	// validate and update clink_countdown_duration value
	
	$clink_countdown_duration = get_option('clink_countdown_duration');
	if( !is_numeric( $clink_countdown_duration ) ){
		update_option( 'clink_countdown_duration', $value = 10 );	
		$clink_countdown_duration = get_option('clink_countdown_duration');
	}
	
	?>
    	<input type="number" name="clink_countdown_duration" id="clink_countdown_duration" value="<?php echo $clink_countdown_duration; ?>" />
		<p class="description clink-description"><?php _e('Enter the redirect countdown duration in seconds (example : 20). In default it set to 10 (seconds).','aryan-themes'); ?></p>
	<?php
}

function display_clink_countdown_meta_tags(){
	// decide how to set clink_countdown_meta_tags value
	$clink_countdown_meta_tags = get_option('clink_countdown_meta_tags'); ?>
		<select name="clink_countdown_meta_tags">
			<option <?php selected($clink_countdown_meta_tags, "noindex,nofollow"); ?> value="noindex,nofollow"><?php _e('noindex - nofollow' , 'aryan-themes'); ?></option>
			<option <?php selected($clink_countdown_meta_tags, "noindex,follow"); ?> value="noindex,follow"><?php _e('noindex - follow' , 'aryan-themes'); ?></option>
			<option <?php selected($clink_countdown_meta_tags, "index,nofollow"); ?> value="index,nofollow"><?php _e('index - nofollow' , 'aryan-themes'); ?></option>
			<option <?php selected($clink_countdown_meta_tags, "index,follow"); ?> value="index,follow"><?php _e('index - follow' , 'aryan-themes'); ?></option>
		</select>
		<p class="description clink-description"><?php _e('Please select meta tags for countdown page','aryan-themes'); ?></p>
	<?php
}

function display_clink_countdown_style(){
	// validate and update clink_countdown_style value

	$clink_countdown_style_option = get_option('clink_countdown_style');

	?>
    	<textarea class="clink-style-textarea" name="clink_countdown_style" id="clink_countdown_style"><?php echo $clink_countdown_style_option; ?></textarea>
		<p class="description clink-description"><?php _e('You can put your custom css style for countdown page here','aryan-themes'); ?></p>
	<?php
}


function display_clink_option_panel_fields(){
	add_settings_section(
		"general-section",
		"",
		'',
		"clink-options"
	);
	
	add_settings_field(
		"clink_main_slug",
		__('base slug','aryan-themes'),
		"display_clink_main_slug",
		"clink-options",
		"general-section"
	);
	add_settings_field(
		"clink_powered_by_text",
		__('Powered By Aryan Themes'
		,'aryan-themes'),
		"display_clink_powered_by_text",
		"clink-options",
		"general-section"
	);
	add_settings_field(
		"clink_countdown_option",
		__('countdown','aryan-themes'),
		"display_clink_countdown_option",
		"clink-options",
		"general-section"
	);
	add_settings_field(
		"clink_countdown_duration",
		__('countdown duration','aryan-themes'),
		"display_clink_countdown_duration",
		"clink-options",
		"general-section"
	);	
	add_settings_field(
		"clink_countdown_meta_tags",
		__('Countdown meta tags','aryan-themes'),
		"display_clink_countdown_meta_tags",
		"clink-options",
		"general-section"
	);
	add_settings_field(
		"clink_countdown_style",
		__('Custom countdown style'
		,'aryan-themes'),
		"display_clink_countdown_style",
		"clink-options",
		"general-section"
	);
	
	register_setting("general-section", "clink_main_slug" );
	register_setting("general-section", "clink_powered_by_text" );
	register_setting("general-section", "clink_countdown_option" );
	register_setting("general-section", "clink_countdown_duration");
	register_setting("general-section", "clink_countdown_meta_tags");
	register_setting("general-section", "clink_countdown_style");
	
}
add_action("admin_init", "display_clink_option_panel_fields");


// Adding Clink action links

function clink_action_links( $links ) {
	return array_merge(
		array(
			'settings' => '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/edit.php?post_type=clink&page=clink-settings">' . __('Global Settings','aryan-themes') . '</a>',
		),
		$links
	);
}
add_filter( 'plugin_action_links_' . CLINK_BASENAME , 'clink_action_links' );