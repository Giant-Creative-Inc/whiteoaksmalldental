<?php
/**
 * Update saved clinic-card links. Run with wp eval-file <this-file> [apply].
 * Backups are written to a temporary file, never to the repository.
 *
 * @package BeanstalkChild
 */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}
if ( 'whiteoaksmalldental.local' !== wp_parse_url( home_url(), PHP_URL_HOST ) ) {
	WP_CLI::error( 'This migration is restricted to Local.' );
}
global $wpdb;
$posts = $wpdb->get_results( "SELECT ID, post_content FROM {$wpdb->posts} WHERE post_type <> 'revision' AND post_status NOT IN ('trash','auto-draft') AND post_content LIKE '%Book at this clinic%'" );
$changes = array();
foreach ( $posts as $post ) {
	$content = preg_replace_callback(
		'/<a\b[^>]*>(.*?)<\/a>/is',
		static function ( $match ) {
			if ( ! preg_match( '/^Book at this clinic\b/i', trim( wp_strip_all_tags( $match[1] ) ) ) ) {
				return $match[0];
			}
			$link = new WP_HTML_Tag_Processor( $match[0] );
			$link->next_tag( 'A' );
			$link->set_attribute( 'href', '/locations/white-oaks-mall/#view-hours' );
			return $link->get_updated_html();
		},
		$post->post_content
	);
	if ( $content !== $post->post_content ) {
		$changes[ $post->ID ] = array( 'before' => $post->post_content, 'after' => $content );
	}
}
WP_CLI::log( count( $changes ) . ' saved records need updating.' );
if ( ! $changes || ! in_array( 'apply', $args ?? array(), true ) ) {
	return;
}
$backup = wp_tempnam( 'white-oaks-clinic-booking-links.json' );
if ( ! $backup || false === file_put_contents( $backup, wp_json_encode( $changes ) ) ) {
	WP_CLI::error( 'Could not write backup.' );
}
foreach ( $changes as $id => $change ) {
	$result = wp_update_post( wp_slash( array( 'ID' => $id, 'post_content' => $change['after'] ) ), true );
	if ( is_wp_error( $result ) || get_post( $id )->post_content !== $change['after'] ) {
		WP_CLI::error( 'Update/readback failed for post ' . $id );
	}
	WP_CLI::log( 'Verified ' . $id . ': ' . get_permalink( $id ) );
}
WP_CLI::success( 'Saved all clinic booking links. Backup: ' . $backup );
