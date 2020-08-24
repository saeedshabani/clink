<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Adds Clink_Widget widget.
 */
 
class Clink_Widget extends WP_Widget {


	/**
	 * Register widget with WordPress.
	 */
	 
	function __construct() {
		parent::__construct(
			'clink', // Base ID
			__( 'Clink Links list', 'aryan-themes' ), // Name
			array( 'description' => __( 'Dispaly Clink plugin Links with this Widget', 'aryan-themes' ), ) // Args
		);
	}

	
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	 
	public function widget( $args, $instance ) {
		
		$clink_widget_arg = array(
			'title'				=> $instance['title'],
			'links_cat'			=> $instance['links_cat'],
			'links_number'		=> $instance['links_number'],
			'links_offset'		=> $instance['links_offset'],
			'links_order'		=> $instance['links_order'],
			'links_order_by'	=> $instance['links_order_by'],
			'exclude_links'		=> explode(',', $instance['exclude_links']),
			'show_clicks'		=> $instance['show_clicks'],
		);
		
		$clink_widget_defaults = array(
			'title'				=> 'Clink links',
			'links_cat'			=> 'all-categories',
			'links_number'		=> 10,		
			'links_offset'		=> 0,			
			'links_order'		=> 'ASC',
			'links_order_by'	=> 'none',
			'exclude_links'		=> '',
		);
				
		$clink_widget_arg = wp_parse_args( $clink_widget_arg , $clink_widget_defaults );
		
		echo $args['before_widget'];
		
			if( !empty( $clink_widget_arg['title'] ) ){
				echo $args['before_title'] . apply_filters( 'widget_title', $clink_widget_arg['title'] ). $args['after_title'];
			}
			
			echo Clink_links_query_func($clink_widget_arg);
			
		echo $args['after_widget'];
	}

	
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	 
	public function form( $instance ) {
				
		$defaults = array(
			'title'				=> __('Clink Links list', 'aryan-themes'),
			'links_cat'			=> 'all-categories',
			'links_number'		=> 10,		
			'links_offset'		=> 0,			
			'links_order'		=> 'DESC',
			'links_order_by'	=> 'none',
			'exclude_links'		=> '',
			'show_clicks'		=> 1,
		);
		
		$instance = wp_parse_args( $instance, $defaults );

		$title				= esc_attr($instance['title']);
		$links_cat			= esc_attr($instance['links_cat']);
		$links_number		= esc_attr($instance['links_number']);
		$links_offset		= esc_attr($instance['links_offset']);
		$links_order 		= esc_attr($instance['links_order']);
		$links_order_by		= esc_attr($instance['links_order_by']);
		$exclude_links		= esc_attr($instance['exclude_links']);		
		
		// Clink widget title
				
		echo '<p>';
			echo '<label for="' . $this->get_field_id( 'title' ) . '">' . __( 'Title:', 'aryan-themes' ) . '</label> ';
			echo '<input class="widefat" id="' . $this->get_field_id( 'title' ) . '" name="' . $this->get_field_name( 'title' ) . '" type="text" value="' . $title . '">';
		echo '</p>';

		// links cat
		
		$links_categories = get_categories( 'taxonomy=clink_category' );
		
		echo '<p>';
			echo '<label for="' . $this->get_field_id('links_cat') . '">' . __('Category', 'aryan-themes') . '</label>';
			echo '<select name="' . $this->get_field_name('links_cat') . '" id="' . $this->get_field_id('links_cat') . '" class="widefat">';
				echo '<option value="all-categories" id="all-categories"', $links_cat == '' ? ' selected="selected"' : '', '>', __('All Categories', 'aryan-themes'), '</option>';
				foreach ( $links_categories as $option ) {
					echo '<option value="' . $option->term_id . '" id="' . $option->term_id . '"', $links_cat == $option->term_id ? ' selected="selected"' : '', '>', $option->cat_name . ' ( ' . $option->category_count . ' ) </option>';
				}
			echo '</select>';
		echo '</p>';
		
		// links Number
		
		echo '<p><label for="' . $this->get_field_id( 'links_number' ) . '">' . __( 'Number of Links to show', 'aryan-themes' ) . '</label><input name="' . $this->get_field_name( 'links_number' ) . '" type="number" id="' . $this->get_field_id( 'links_number' ) . '" value="' . $links_number . '" size="3" class="widefat" /> </p>' . "\n";		

		// links offset
		
		echo '<p><label for="' . $this->get_field_id( 'links_offset' ) . '">' . __( 'Links Offset', 'aryan-themes' ) . '</label><input name="' . $this->get_field_name( 'links_offset' ) . '" type="number" id="' . $this->get_field_id( 'links_offset' ) . '" value="' . $links_offset . '" size="3"  class="widefat"/></p>' . "\n";		
		
		// links order by
		
		$order_by_array = array(
			'none'			=> __( 'none',		'aryan-themes' ),
			'ID'			=> __( 'ID',		'aryan-themes' ),
			'author'		=> __( 'author',	'aryan-themes' ),
			'title'			=> __( 'title',		'aryan-themes' ),
			'date'			=> __( 'date',		'aryan-themes' ),
			'modified'		=> __( 'modified',	'aryan-themes' ),
			'rand'			=> __( 'rand',		'aryan-themes' ),
			'clink_clicks'	=> __( 'clicks',	'aryan-themes' ),
		);

		echo '<p><label for="' . $this->get_field_id( 'links_order_by' ) . '">' . __( 'Links order by', 'aryan-themes' ) . '</label><select name="' . $this->get_field_name( 'links_order_by' ) . '" id="' . $this->get_field_id( 'links_order_by' ) . '" class="widefat">';
		foreach ( $order_by_array as $order_by_array => $title ) {
			$is_selected = ( $links_order_by == $order_by_array ) ? ' selected="selected"' : '';
			echo '<option value="' . $order_by_array . '"' . $is_selected . '>' . $title . '</option>';
		}
		echo '</select></p>' . "\n";
		
		// links order
		
		$order_array = array(
			'DESC'	=> __( 'DESC', 'aryan-themes' ),
			'ASC'	=> __( 'ASC',  'aryan-themes' ),
		);

		echo '<p><label for="' . $this->get_field_id( 'links_order' ) . '">' . __( 'Links order', 'aryan-themes' ) . '</label><select name="' . $this->get_field_name( 'links_order' ) . '" id="' . $this->get_field_id( 'links_order' ) . '" class="widefat">';
		foreach ( $order_array as $order_array => $title ) {
			$is_selected = ( $links_order == $order_array ) ? ' selected="selected"' : '';
			echo '<option value="' . $order_array . '"' . $is_selected . '>' . $title . '</option>';
		}
		echo '</select></p>' . "\n";		
		
		// Exclude links
		
		echo '<p>';
			echo '<label for="' . $this->get_field_id( 'exclude_links' ) . '">' . __( 'Exclude links:', 'aryan-themes' ) . '</label> ';
			echo '<input class="widefat" id="' . $this->get_field_id( 'exclude_links' ) . '" name="' . $this->get_field_name( 'exclude_links' ) . '" type="text" value="' . $exclude_links . '">';
			echo '<small>' . __('links IDs, separated by commas.', 'aryan-themes') . '</small>';
		echo '</p>';		
	
		// Show clicks
		
		$is_checked = ( empty( $instance['show_clicks'] ) ) ? '' : 'checked="checked" ';
		echo '<p>';
			echo '<input name="' . $this->get_field_name( 'show_clicks' ) . '" type="checkbox" id="' . $this->get_field_id( 'show_clicks' ) . '" value="1" ' . $is_checked . '/>';
			echo '<label for="' . $this->get_field_id( 'show_clicks' ) . '">' . __( 'Display links clicks?', 'aryan-themes' ) . '</label>';
		echo '</p>';
		
	}

	
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	 
	public function update( $new_instance, $old_instance ) {
	
		$instance = $old_instance;
			
		$instance['title']			= strip_tags($new_instance['title']);
		$instance['links_cat']		= $new_instance['links_cat'];
		$instance['links_number']	= strip_tags($new_instance['links_number']);
		$instance['links_offset']	= strip_tags($new_instance['links_offset']);
		$instance['links_order']	= $new_instance['links_order'];
		$instance['links_order_by']	= $new_instance['links_order_by'];
		$instance['exclude_links']	= $new_instance['exclude_links'];
		$instance['show_clicks']	= ! empty( $new_instance['show_clicks'] );
				
		return $instance;
	}

} // class Clink_Widget


// register Clink_Widget widget

function register_clink_widget() {
    register_widget( 'Clink_Widget' );
}
add_action( 'widgets_init', 'register_clink_widget' );