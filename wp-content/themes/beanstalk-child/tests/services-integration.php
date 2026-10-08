<?php
/** Run with Local's WP-CLI: wp eval-file .../tests/services-integration.php */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { exit; }
function wom_service_assert( $condition, $message ) {
 if ( ! $condition ) { throw new RuntimeException( $message ); }
 WP_CLI::log( 'PASS: ' . $message );
}
global $wpdb;
$wpdb->query( 'START TRANSACTION' );
$created = array();
try {
 $type = get_post_type_object( 'service' );
 wom_service_assert( $type && $type->show_in_rest && ! $type->has_archive, 'Gutenberg CPT and no archive collision' );
 wom_service_assert( ! taxonomy_exists( 'service_category' ), 'Taxonomy removed from active registration' );
 $parent = get_page_by_path( 'cosmetic-dentistry', OBJECT, 'service' );
 wom_service_assert( (bool) $parent, 'Cosmetic pillar exists' );
 foreach ( array( 'Current', 'Alpha', 'Beta', 'Gamma', 'Draft' ) as $name ) {
  $id = wp_insert_post( array( 'post_type' => 'service', 'post_title' => 'Integration ' . $name, 'post_status' => 'Draft' === $name ? 'draft' : 'publish', 'post_excerpt' => 'Test description.', 'post_parent' => $parent->ID ), true );
  if ( is_wp_error( $id ) ) { throw new RuntimeException( $id->get_error_message() ); }
  $created[] = $id;
 }
 list( $current, $alpha, $beta, $gamma, $draft ) = $created;
 $other_parent = get_page_by_path( 'general-and-family-dentistry', OBJECT, 'service' );
 $foreign = wp_insert_post( array( 'post_type' => 'service', 'post_title' => 'Integration Foreign', 'post_status' => 'publish', 'post_parent' => $other_parent->ID ) );
 $created[] = $foreign;
 wom_service_assert( array( $beta ) === white_oaks_sanitize_related_services( array( $parent->ID, $beta ) ), 'Pillars cannot be selected as related treatments' );
 update_post_meta( $current, '_white_oaks_related_services', array( $beta, $current, $draft ) );
 update_post_meta( $current, '_white_oaks_related_fallback', false );
 wom_service_assert( array( $beta ) === white_oaks_related_service_ids( $current ), 'Self and draft excluded; curated order retained' );
 update_post_meta( $current, '_white_oaks_related_fallback', true );
 wom_service_assert( array( $beta, $alpha, $gamma ) === white_oaks_related_service_ids( $current ), 'Sibling fallback fills three unique published services' );
 wp_update_post( array( 'ID' => $current, 'post_parent' => 0 ) );
 wom_service_assert( array( $beta ) === white_oaks_related_service_ids( $current ), 'Unparented services do not receive unrelated fallback' );
 wp_update_post( array( 'ID' => $current, 'post_parent' => $parent->ID ) );
 update_post_meta( $current, '_white_oaks_related_fallback', false );
 update_post_meta( $current, '_white_oaks_related_services', array() );
 wom_service_assert( array() === white_oaks_related_service_ids( $current ), 'Empty related state supported' );
 $source = get_post(126);
 wp_update_post( wp_slash( array( 'ID' => $current, 'post_content' => $source->post_content ) ) );
 $graph = white_oaks_service_schema( $current );
 $faq = array_values( array_filter( $graph['@graph'], static function( $node ) { return 'FAQPage' === $node['@type']; } ) );
 wom_service_assert( 6 === count( $faq[0]['mainEntity'] ), 'All six editor FAQs included automatically' );
 wom_service_assert( false === strpos( $faq[0]['mainEntity'][0]['name'], '01' ), 'Decorative FAQ numbering excluded' );
 wom_service_assert( 'Test description.' === $graph['@graph'][1]['description'], 'Schema description follows excerpt' );
 wom_service_assert( 'Cosmetic Dentistry' === $graph['@graph'][1]['category'], 'Schema uses parent pillar title' );
 wom_service_assert( null !== white_oaks_schema_decode( wp_json_encode( $graph ) ), 'Generated graph is valid managed JSON-LD' );
 $GLOBALS['wp_query'] = new WP_Query( array( 'post_type' => 'service', 'p' => $current ) );
 update_post_meta( $current, '_white_oaks_related_services', array( $beta, $alpha ) );
 $pattern = WP_Block_Patterns_Registry::get_instance()->get_registered( 'beanstalk-child/related-services' );
 wom_service_assert( ! empty( $pattern['content'] ), 'Related service pattern registered' );
 $html = do_blocks( $pattern['content'] );
 wom_service_assert( strpos( $html, 'Integration Beta' ) < strpos( $html, 'Integration Alpha' ) && false !== strpos( $html, 'Integration Beta' ), 'Native Query renders curated services in order' );
 wom_service_assert( false === strpos( $html, 'Integration Draft' ), 'Native Query excludes drafts' );
 $directory = white_oaks_service_directory();
 wom_service_assert( false !== strpos( $directory, 'Cosmetic Dentistry' ) && false === strpos( $directory, 'Integration Draft' ), 'Footer directory includes published services only' );
 $footer = get_post(290);
 $footer_html = do_blocks( $footer->post_content );
 wom_service_assert( str_contains( $footer_html, 'Integration Beta' ) && ! str_contains( $footer_html, 'Integration Draft' ), 'Saved footer Query renders published children without taxonomy' );
 $block = (object) array( 'context' => array( 'query' => array( 'whiteOaksContext' => 'white-oaks/footer-services', 'whiteOaksPillar' => 'cosmetic-dentistry' ) ) );
 $query = apply_filters( 'query_loop_block_query_vars', array(), $block );
 wom_service_assert( $parent->ID === $query['post_parent'] && ! isset( $query['tax_query'] ), 'Footer groups resolve portable parent slug' );
 $block->context['query']['whiteOaksPillar'] = 'missing-pillar';
 $query = apply_filters( 'query_loop_block_query_vars', array(), $block );
 wom_service_assert( array(0) === $query['post__in'], 'Missing pillar never exposes all services' );
 wp_set_current_user( 0 );
 $response = rest_do_request( '/wp/v2/service/' . $draft );
 wom_service_assert( $response->get_status() >= 400, 'Anonymous REST cannot read a service draft' );
 $GLOBALS['wp_query'] = new WP_Query();
} finally {
 $wpdb->query( 'ROLLBACK' );
 foreach ( $created as $id ) { clean_post_cache( $id ); }
}
WP_CLI::success( 'Service integration tests passed; all fixtures rolled back.' );
