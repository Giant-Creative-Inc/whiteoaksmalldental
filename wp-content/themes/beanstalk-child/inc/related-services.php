<?php
/** Scoped assets for the editable native Related Services query. @package BeanstalkChild */
defined( 'ABSPATH' ) || exit;
add_filter( 'beanstalk_child_component_assets', static function ( $assets ) {
	$assets['related-services'] = array(
		'markers' => array( 'related-services__container' ),
		'style' => 'assets/css/build/components/related-services.min.css',
		'style_dependencies' => array( 'white-oaks-shared' ),
	);
	return $assets;
} );

add_filter( 'beanstalk_child_component_assets', static function ( $assets ) {
	$assets['pillar-services'] = array(
		'markers' => array( 'pillar-services' ),
		'style' => 'assets/css/build/components/pillar-services.min.css',
		'style_dependencies' => array( 'white-oaks-shared' ),
	);
	return $assets;
} );
