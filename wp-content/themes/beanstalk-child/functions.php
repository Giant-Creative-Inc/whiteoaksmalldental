<?php
/**
 * Beanstalk child theme functions.
 *
 * @package BeanstalkChild
 */

/**
 * Enqueues the child stylesheet after the parent compiled stylesheet.
 *
 * @return void
 */
function beanstalk_child_enqueue_styles() {
	$stylesheet_path    = get_stylesheet_directory() . '/style.css';
	$stylesheet_version = file_exists( $stylesheet_path )
		? (string) filemtime( $stylesheet_path )
		: wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'beanstalk-child',
		get_stylesheet_uri(),
		array( 'beanstalk-custom' ),
		$stylesheet_version
	);
}
add_action( 'wp_enqueue_scripts', 'beanstalk_child_enqueue_styles', 20 );
