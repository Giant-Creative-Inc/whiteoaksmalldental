<?php
/** Scoped native-block styles for the service introduction. @package BeanstalkChild */
defined( 'ABSPATH' ) || exit;
add_action( 'init', static function () {
	foreach ( array( 'container' => 'Container', 'content-stack' => 'Content Stack' ) as $name => $label ) {
		if ( ! WP_Block_Styles_Registry::get_instance()->is_registered( 'core/group', $name ) ) {
			register_block_style( 'core/group', array( 'name' => $name, 'label' => $label ) );
		}
	}
	if ( ! WP_Block_Styles_Registry::get_instance()->is_registered( 'core/buttons', 'actions' ) ) {
		register_block_style( 'core/buttons', array( 'name' => 'actions', 'label' => 'Actions' ) );
	}
} );
add_filter( 'beanstalk_child_component_assets', static function ( $assets ) {
	$assets['service-about'] = array(
		'markers' => array( 'service-about' ),
		'style' => 'assets/css/build/components/service-about.min.css',
		'style_dependencies' => array( 'white-oaks-shared' ),
	);
	return $assets;
} );
