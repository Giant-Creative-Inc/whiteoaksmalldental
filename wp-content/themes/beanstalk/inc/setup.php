<?php
/**
 * Theme setup.
 *
 * @package Beanstalk
 */

/**
 * Registers theme supports and classic navigation locations.
 *
 * @return void
 */
function beanstalk_setup() {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style(
		array(
			'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap',
			get_parent_theme_file_uri( 'assets/css/custom.css' ),
		)
	);
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'custom-logo',
		array(
			'height'               => 120,
			'width'                => 180,
			'flex-height'          => true,
			'flex-width'           => true,
			'unlink-homepage-logo' => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'beanstalk' ),
			'footer'  => __( 'Footer Navigation', 'beanstalk' ),
		)
	);
}
add_action( 'after_setup_theme', 'beanstalk_setup' );
