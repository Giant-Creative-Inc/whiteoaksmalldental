<?php
/**
 * Block style registrations.
 *
 * Visual values for these styles live in theme.json.
 *
 * @package Beanstalk
 */

/**
 * Registers Beanstalk block styles.
 *
 * @return void
 */
function beanstalk_register_block_styles() {
	register_block_style(
		'core/group',
		array(
			'name'  => 'section',
			'label' => __( 'Section', 'beanstalk' ),
		)
	);

	register_block_style(
		'core/group',
		array(
			'name'  => 'fixed-header',
			'label' => __( 'Fixed Header', 'beanstalk' ),
		)
	);

	register_block_style(
		'core/cover',
		array(
			'name'       => 'content-height',
			'label'      => __( 'Content Height', 'beanstalk' ),
			'is_default' => true,
		)
	);

	register_block_style(
		'core/cover',
		array(
			'name'  => 'full-height',
			'label' => __( 'Full Height', 'beanstalk' ),
		)
	);

	register_block_style(
		'core/button',
		array(
			'name'       => 'primary',
			'label'      => __( 'Primary', 'beanstalk' ),
			'is_default' => true,
		)
	);

	register_block_style(
		'core/button',
		array(
			'name'  => 'text',
			'label' => __( 'Text', 'beanstalk' ),
		)
	);

	register_block_style(
		'core/paragraph',
		array(
			'name'  => 'eyebrow',
			'label' => __( 'Eyebrow', 'beanstalk' ),
		)
	);
}
add_action( 'init', 'beanstalk_register_block_styles' );
