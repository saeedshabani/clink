<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Define Clink Shortcodes
 *
 * Default clink Shortcodes values is:
 *
 * [clink title="Clink Links list" cat="all" order="DESC" orderby="none" offset="0" number="10" exclude="" showclicks="true"]
 *
 * @title		: string
 * @cat			: "all" for show links from all categories, or "ids" of the categories that separated by commas
 * @order		: DESC , ASC
 * @orderby 	: none, id, author, title, date, modified, rand, clicks
 * @offset  	: number
 * @number  	: number
 * @exclude 	: Ids of posts that separated by commas
 * @showclicks	: true, false
 *
 */

function clink_shortcode_func( $atts ) {
	
    $arg = shortcode_atts( array(
        'title'			=> __( 'Clink Links list', 'aryan-themes' ),
        'cat'			=> 'all',
		'order'			=> 'DESC',
		'orderby'		=> 'none',
		'offset'		=> '0',
		'number'		=> '10',
		'exclude'		=> '',
		'showclicks'	=> 'true',
    ), $atts, 'clink' );
	
	if( $arg['cat'] == 'all' ){
		$arg['cat'] = 'all-categories';
	}else{
		$arg['cat'] = explode(',', $arg['cat']);
	}

	if( $arg['orderby'] == 'clicks' ){
		$arg['orderby'] = 'clink_clicks';
	}

	if( $arg['showclicks'] === 'true' ){
		$arg['showclicks'] = 1; 
	}
	
	$clink_arg = array(
		'title'				=> $arg['title'],
		'links_cat'			=> $arg['cat'],
		'links_order'		=> $arg['order'],
		'links_order_by'	=> $arg['orderby'],
		'links_offset'		=> $arg['offset'],
		'links_number'		=> $arg['number'],
		'exclude_links'		=> explode(',', $arg['exclude']),
		'show_clicks'    	=> $arg['showclicks'],
	);
	
	$cl_sh_str = '<div class="clink-links-shortcode">';
		$cl_sh_str .= '<div class="clink-links-shortcode-header"><h3>' . $clink_arg['title'] . '</h3></div>';
		$cl_sh_str .= '<div class="clink-links-list">' . Clink_links_query_func($clink_arg) . '</div>';
	$cl_sh_str .= '</div>';
	
	return $cl_sh_str; 
}

add_shortcode( 'clink', 'clink_shortcode_func' );