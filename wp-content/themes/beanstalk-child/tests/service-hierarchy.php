<?php
/** Run with wp eval-file; temporary posts are rolled back. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { exit; }
function wom_hierarchy_assert( $condition, $message ) {
	if ( ! $condition ) { throw new RuntimeException( $message ); }
	WP_CLI::log( 'PASS: ' . $message );
}
global $wpdb;
$wpdb->query( 'START TRANSACTION' );
$ids = array();
$original_query = $GLOBALS['wp_query'];
try {
	$type = get_post_type_object( 'service' );
	wom_hierarchy_assert( $type->hierarchical && $type->show_in_rest && post_type_supports( 'service', 'editor' ) && post_type_supports( 'service', 'page-attributes' ), 'Gutenberg and native parent selector enabled' );
	wom_hierarchy_assert( ! $type->has_archive, 'Existing Services page retains archive ownership' );
	$parent = wp_insert_post( array( 'post_type' => 'service', 'post_title' => 'Hierarchy fixture', 'post_name' => 'hierarchy-fixture', 'post_status' => 'publish' ) );
	$ids[] = $parent;
	$child = wp_insert_post( array( 'post_type' => 'service', 'post_title' => 'Treatment fixture', 'post_name' => 'treatment-fixture', 'post_status' => 'publish', 'post_parent' => $parent ) );
	$ids[] = $child;
	$url = home_url( '/services/hierarchy-fixture/treatment-fixture/' );
	wom_hierarchy_assert( $url === get_permalink( $child ), 'Native nested permalink includes parent slug' );
	wom_hierarchy_assert( $child === url_to_postid( $url ), 'Nested rewrite resolves to child service' );
	wom_hierarchy_assert( $parent === url_to_postid( get_permalink( $parent ) ), 'Top-level pillar route resolves' );
	$captured = null;
	$capture_redirect = static function ( $location, $status ) use ( &$captured ) { $captured = array( $location, $status ); return false; };
	$original_uri = $_SERVER['REQUEST_URI'] ?? null;
	$original_method = $_SERVER['REQUEST_METHOD'] ?? null;
	add_filter( 'wp_redirect', $capture_redirect, 10, 2 );
	try {
		$GLOBALS['wp_query'] = new WP_Query();
		$GLOBALS['wp_query']->set_404();
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['REQUEST_URI'] = wp_parse_url( home_url( '/services/treatment-fixture/' ), PHP_URL_PATH );
		white_oaks_redirect_legacy_service();
		wom_hierarchy_assert( array( $url, 301 ) === $captured, 'Old flat 404 redirects to child with 301' );
		$captured = null;
		$GLOBALS['wp_query']->is_404 = false;
		white_oaks_redirect_legacy_service();
		wom_hierarchy_assert( null === $captured, 'Existing owned routes are not redirected' );
	} finally {
		remove_filter( 'wp_redirect', $capture_redirect, 10 );
		$_SERVER['REQUEST_URI'] = $original_uri;
		$_SERVER['REQUEST_METHOD'] = $original_method;
	}
	$graph = white_oaks_service_schema( $child );
	$breadcrumbs = end( $graph['@graph'] );
	wom_hierarchy_assert( in_array( get_permalink( $parent ), array_column( $breadcrumbs['itemListElement'], 'item' ), true ), 'Schema breadcrumb includes parent' );
	$GLOBALS['wp_query'] = new WP_Query( array( 'post_type' => 'service', 'p' => $child ) );
	$crumbs = white_oaks_service_breadcrumb_items( array( array( 'Home', home_url( '/' ) ), array( 'Treatment fixture', $url ) ) );
	wom_hierarchy_assert( get_permalink( $parent ) === $crumbs[count( $crumbs ) - 2][1], 'Visible breadcrumb includes parent' );
	$pillar = get_page_by_path( 'general-and-family-dentistry', OBJECT, 'service' );
	wom_hierarchy_assert( $pillar && has_blocks( $pillar->post_content ), 'Actual pillar has editable blocks and and slug' );
	$graph = white_oaks_service_schema( $pillar->ID );
	wom_hierarchy_assert( 'CollectionPage' === $graph['@graph'][0]['@type'] && ! in_array( 'Service', array_column( $graph['@graph'], '@type' ), true ), 'Pillar schema represents a collection' );
	$GLOBALS['wp_query'] = new WP_Query( array( 'post_type' => 'service', 'p' => $parent ) );
	$query_pattern = WP_Block_Patterns_Registry::get_instance()->get_registered( 'beanstalk-child/related-services' );
	$html = do_blocks( str_replace( array( 'white-oaks/related-services', 'service-related-section' ), array( 'white-oaks/pillar-services', 'pillar-services' ), $query_pattern['content'] ) );
	wom_hierarchy_assert( str_contains( $html, 'Treatment fixture' ) && str_contains( $html, $url ), 'Pillar Query renders published child link' );
	wp_set_current_user( 0 );
	$draft = wp_insert_post( array( 'post_type' => 'service', 'post_title' => 'Private hierarchy fixture', 'post_status' => 'draft' ) );
	$ids[] = $draft;
	$response = rest_do_request( '/wp/v2/service/' . $draft );
	wom_hierarchy_assert( $response->get_status() >= 400, 'Anonymous REST cannot expose draft pillar' );
} finally {
	$wpdb->query( 'ROLLBACK' );
	foreach ( $ids as $id ) { clean_post_cache( $id ); }
	$GLOBALS['wp_query'] = $original_query;
}
WP_CLI::success( 'Hierarchy fixtures rolled back.' );
