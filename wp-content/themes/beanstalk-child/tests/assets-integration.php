<?php
/**
 * Read-only loading checks: wp eval-file wp-content/themes/beanstalk-child/tests/assets-integration.php
 *
 * No database writes, plugin deactivation, or requests to external services.
 *
 * @package BeanstalkChild
 */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit;
}
function white_oaks_asset_assert( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
	WP_CLI::log( 'PASS: ' . $message );
}
white_oaks_asset_assert( '' === beanstalk_child_normalize_asset_path( '../functions.php' ), 'Parent traversal rejected' );
white_oaks_asset_assert( '' === beanstalk_child_normalize_asset_path( '' ), 'Empty paths rejected' );
white_oaks_asset_assert( '' === beanstalk_child_enqueue_component_file( 'script', 'missing', array( 'script' => 'missing.js' ) ), 'Missing files never enqueued' );
white_oaks_asset_assert( false === beanstalk_child_component_is_required( array( 'markers' => array( 'clinic-gallery' ) ), '' ), 'Absent components do not load' );
white_oaks_asset_assert( beanstalk_child_component_is_required( array( 'blocks' => array( 'core/gallery' ) ), '<!-- wp:gallery /-->' ), 'Native block names match' );
white_oaks_asset_assert( beanstalk_child_component_is_required( array( 'callback' => static function () { return true; } ), '' ), 'Request conditions match without page content' );
$assets = beanstalk_child_get_component_assets();
foreach ( $assets as $slug => $asset ) {
	foreach ( array( 'style', 'script', 'module' ) as $type ) {
		if ( ! empty( $asset[ $type ] ) ) {
			white_oaks_asset_assert( is_file( get_stylesheet_directory() . '/' . $asset[ $type ] ), $slug . ' ' . $type . ' file exists' );
		}
	}
}
$mock_part = static function ( $pre, $id ) {
	if ( 'beanstalk-child//asset-test' === $id ) {
		return (object) array( 'content' => '<!-- wp:group {"className":"clinic-gallery"} --><div class="clinic-gallery"></div><!-- /wp:group -->' );
	}
	return $pre;
};
add_filter( 'pre_get_block_template', $mock_part, 10, 2 );
$visited  = array();
$resolved = white_oaks_resolve_asset_content( '<!-- wp:template-part {"slug":"asset-test","theme":"beanstalk-child"} /-->', $visited );
white_oaks_asset_assert( str_contains( $resolved, 'clinic-gallery' ), 'Active template-part content participates in matching' );
remove_filter( 'pre_get_block_template', $mock_part, 10 );
// Synthetic cached WP_Post objects exercise reference recursion without writing posts.
$mock_id = 2147483000;
$mock    = new WP_Post( (object) array( 'ID' => $mock_id, 'post_content' => '<!-- wp:group {"className":"contact-section"} /--><!-- wp:block {"ref":2147483000} /-->' ) );
wp_cache_add( $mock_id, $mock, 'posts' );
$visited  = array();
$resolved = white_oaks_resolve_asset_content( '<!-- wp:block {"ref":2147483000} /-->', $visited );
white_oaks_asset_assert( str_contains( $resolved, 'contact-section' ) && strlen( $resolved ) < 1000, 'Synced patterns resolve and recursive references terminate' );
wp_cache_delete( $mock_id, 'posts' );
$handle = beanstalk_child_enqueue_component_file( 'script', 'clinic-gallery', $assets['clinic-gallery'] );
white_oaks_asset_assert( 'defer' === wp_scripts()->get_data( $handle, 'strategy' ), 'Gallery requests native defer' );
white_oaks_asset_assert( str_ends_with( wp_scripts()->registered[ $handle ]->src, '/assets/js/build/clinic-gallery.min.js' ), 'Gallery uses production minified JavaScript' );
$src = get_stylesheet_directory_uri() . '/assets/js/build/clinic-gallery.min.js?custom=keep&ver=old';
$url = beanstalk_child_version_asset_url( $src );
white_oaks_asset_assert( str_contains( $url, 'custom=keep' ) && ! str_contains( $url, 'ver=old' ), 'Cache busting preserves unrelated URL parameters' );
white_oaks_asset_assert( false === has_filter( 'style_loader_tag', 'beanstalk_defer_stylesheet' ), 'Global CSS is not asynchronously rewritten by the parent' );
$test_allow = static function () { return array( 'test-style' ); };
add_filter( 'beanstalk_child_below_fold_style_handles', $test_allow, 99 );
$test_link = '<link id="test-style-css" rel="stylesheet" href="test.css" media="all">';
$async     = beanstalk_child_defer_below_fold_style( $test_link, 'test-style' );
white_oaks_asset_assert( str_contains( $async, 'media="print"' ) && str_contains( $async, '<noscript>' . $test_link . '</noscript>' ), 'Allowlisted frontend CSS has a no-JavaScript fallback' );
white_oaks_asset_assert( $test_link === beanstalk_child_defer_below_fold_style( $test_link, 'other-style' ), 'Non-allowlisted CSS stays synchronous' );
remove_filter( 'beanstalk_child_below_fold_style_handles', $test_allow, 99 );
// In the editor even allowlisted links must stay synchronous.
require_once ABSPATH . 'wp-admin/includes/class-wp-screen.php';
require_once ABSPATH . 'wp-admin/includes/screen.php';
set_current_screen( 'page' );
$original = '<link rel="stylesheet" href="test.css" media="all">';
$allow    = static function () { return array( 'test-style' ); };
add_filter( 'beanstalk_child_below_fold_style_handles', $allow, 99 );
white_oaks_asset_assert( $original === beanstalk_child_defer_below_fold_style( $original, 'test-style' ), 'Editor styles remain synchronous' );
white_oaks_editor_component_styles();
white_oaks_asset_assert( wp_style_is( 'white-oaks-component-doctors-section', 'enqueued' ) && wp_style_is( 'white-oaks-home', 'enqueued' ), 'Editor supports newly inserted components before saving' );
remove_filter( 'beanstalk_child_below_fold_style_handles', $allow, 99 );
WP_CLI::success( 'Child asset loading checks passed.' );
