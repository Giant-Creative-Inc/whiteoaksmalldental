<?php
/** Scoped assets for the shared native service directory. */
defined( 'ABSPATH' ) || exit;
add_filter( 'beanstalk_child_component_assets', static function ( $assets ) {
 $assets['footer-services'] = array( 'markers' => array( 'footer-services' ), 'style' => 'assets/css/build/components/footer-services.min.css', 'style_dependencies' => array( 'white-oaks-shared' ) );
 return $assets;
} );

/** Resolve each footer group by its portable pillar slug, not a database ID. */
add_filter( 'query_loop_block_query_vars', static function ( $query, $block ) {
 if ( 'white-oaks/footer-services' !== ( $block->context['query']['whiteOaksContext'] ?? '' ) ) { return $query; }
 $slug = $block->context['query']['whiteOaksPillar'] ?? '';
 $parent = isset( white_oaks_service_pillar_definitions()[ $slug ] ) ? get_page_by_path( $slug, OBJECT, 'service' ) : null;
 unset( $query['tax_query'] );
 $query['post_type'] = 'service';
 $query['post_status'] = 'publish';
 if ( $parent ) { $query['post_parent'] = $parent->ID; }
 else { $query['post__in'] = array( 0 ); }
 return $query;
}, 10, 2 );
