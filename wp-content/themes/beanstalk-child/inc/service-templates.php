<?php
/**
 * Select separate block templates for service pillars and treatments.
 *
 * @package BeanstalkChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Prefer the pillar template for top-level services using the default template.
 * WordPress resolves these hierarchy candidates to HTML block templates.
 *
 * @param string[] $templates Template candidates in priority order.
 * @return string[]
 */
function white_oaks_service_template_hierarchy( $templates ) {
	$post = get_queried_object();
	if ( ! is_singular( 'service' ) || ! $post instanceof WP_Post || $post->post_parent || get_page_template_slug( $post ) ) {
		return $templates;
	}

	array_unshift( $templates, 'single-pillar-service.php' );
	return $templates;
}
add_filter( 'single_template_hierarchy', 'white_oaks_service_template_hierarchy' );

add_filter( 'beanstalk_child_component_assets', static function ( $assets ) {
	$assets['service-pillar-hero'] = array(
		'markers' => array( 'service-pillar-hero' ),
		'style' => 'assets/css/build/components/service-pillar-hero.min.css',
		'style_dependencies' => array( 'white-oaks-shared' ),
	);
	return $assets;
} );

/** Identify pillar pages for corrections that must not affect treatment pages. */
add_filter( 'body_class', static function ( $classes ) {
	if ( is_singular( 'service' ) && white_oaks_is_service_pillar( get_queried_object_id() ) ) {
		$classes[] = 'pillar-service-page';
	}
	return $classes;
} );
