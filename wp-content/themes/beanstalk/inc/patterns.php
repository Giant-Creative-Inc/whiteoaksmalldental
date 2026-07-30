<?php
/**
 * Pattern categories and conditional pattern availability.
 *
 * @package Beanstalk
 */

/**
 * Registers Beanstalk pattern categories.
 *
 * @return void
 */
function beanstalk_register_pattern_categories() {
	register_block_pattern_category(
		'beanstalk',
		array(
			'label' => __( 'Beanstalk', 'beanstalk' ),
		)
	);

	register_block_pattern_category(
		'beanstalk-hero',
		array(
			'label' => __( 'Hero Sections', 'beanstalk' ),
		)
	);
}
add_action( 'init', 'beanstalk_register_pattern_categories' );

/**
 * Hides form patterns when the Gravity Forms block is unavailable.
 *
 * @return void
 */
function beanstalk_maybe_unregister_form_patterns() {
	if ( WP_Block_Type_Registry::get_instance()->is_registered( 'gravityforms/form' ) ) {
		return;
	}

	unregister_block_pattern( 'beanstalk/hero-centered-form' );
	unregister_block_pattern( 'beanstalk/hero-split-form' );
}
add_action( 'init', 'beanstalk_maybe_unregister_form_patterns', 20 );
