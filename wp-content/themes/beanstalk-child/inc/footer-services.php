<?php
/** Scoped assets for the shared native service directory. */
defined( 'ABSPATH' ) || exit;
add_filter( 'beanstalk_child_component_assets', static function ( $assets ) {
 $assets['footer-services'] = array( 'markers' => array( 'footer-services' ), 'style' => 'assets/css/build/components/footer-services.min.css', 'style_dependencies' => array( 'white-oaks-shared' ) );
 return $assets;
} );

/** Core omits non-public taxonomies from front-end Query filters. */
add_filter( 'query_loop_block_query_vars', static function ( $query, $block ) {
 if ( 'white-oaks/footer-services' !== ( $block->context['query']['whiteOaksContext'] ?? '' ) ) { return $query; }
 $terms = array_map( 'absint', $block->context['query']['taxQuery']['service_category'] ?? array() );
 $query['tax_query'] = array( array( 'taxonomy' => 'service_category', 'field' => 'term_id', 'terms' => $terms, 'include_children' => false ) );
 $query['post_status'] = 'publish';
 return $query;
}, 10, 2 );
