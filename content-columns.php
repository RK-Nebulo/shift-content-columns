<?php 
/*
Plugin Name:	SHIFT - Content Columns Shortcodes
Plugin URI:		https://github.com/nebulodesign/shift-content-columns/
Description:	Enables [columns] shortcode for use in the content editor
Version:			1.0
Author:				Nebulo Design
Author URI:		http://www.nebulodesign.com
License:			GPL
*/

/**
 * Initiate custom plugin class - fetches updates from our own public GitHub repository.
 */
if( is_admin() && class_exists( 'Shift_Plugin_Updater' ) ) new Shift_Plugin_Updater( __FILE__ );


add_shortcode( 'columns', 'shift_columns' );
function shift_columns( $attributes, $content = null ){

	extract( shortcode_atts( array(
		'n' => 2,
	), $attributes ) );

	$n = ( $n <= 6 ) ? $n : 6;

	$output = '<div class="row__colspaced content-columns"><div class="span4-4 span6-' . ( $n > 2 ? '3 span12-' . floor( 12 / $n ) : floor( 6 / $n ) ) . '">';
	$output .= do_shortcode( str_replace( '[new-column]', '[new-column n="' . $n . '"]', $content ) );
	$output .= '</div>';

	return $output;
}


add_shortcode( 'new-column', 'shift_column' );
function shift_column( $attributes, $content = null ){

	extract( shortcode_atts( array(
		'n' => 2,
	), $attributes ) );

	$n = ( $n <= 6 ) ? $n : 6;
	return '</div><div class="span4-4 span6-' . ( $n > 2 ? '3 span12-' . floor( 12 / $n ) : floor( 6 / $n ) ) . '">';
}


//add_shortcode( 'column', 'shift_column' );
//function shift_column( $attributes, $content = null ){
//	return '<div class="span4-4 span6-3">' . $content . '</div>';
//}

add_filter( 'the_content', function( $content ){

	return has_shortcode( 'columns', $content ) ? preg_replace( '/(<p>[\s\v]*)?(\[[\/]?[a-z\-]+\])([\s\v]*<\/p>)?/', '$2', force_balance_tags( $content ) ) : $content;

} );

add_action( 'init', function(){
	if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') )
	{
		add_filter('mce_buttons', function( $buttons ){
			array_push( $buttons, 'columns' );
			return $buttons;
		});

		add_filter('mce_external_plugins', function( $plugin_array ){
			$plugin_array['columns'] = plugins_url( '', __FILE__ ) . '/button.js';
			return $plugin_array;
		});
	}
});

add_action( 'admin_enqueue_scripts', function(){
	echo '<style>i.mce-i-columns { font: 400 20px/1 dashicons; } i.mce-i-columns:before { content: "\f535"; }</style>';
});