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
