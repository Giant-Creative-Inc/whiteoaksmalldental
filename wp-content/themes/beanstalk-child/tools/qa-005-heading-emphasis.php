<?php
/**
 * Restore QA-005 emphasis in editable service hub headings.
 *
 * Preview: wp eval-file wp-content/themes/beanstalk-child/tools/qa-005-heading-emphasis.php
 * Apply: append "apply". Content backups are written outside the repository.
 * This helper is never loaded by the theme or run from a web request.
 *
 * @package BeanstalkChild
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

if ( ! in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'whiteoaksmalldental.local', 'staging.whiteoaksmalldental.com' ), true ) ) {
	WP_CLI::error( 'Only Local and staging are supported.' );
}

$apply = ( $args[0] ?? '' ) === 'apply';
if ( ! empty( $args ) && ( count( $args ) !== 1 || ! $apply ) ) {
	WP_CLI::error( 'Use no arguments for preview, or apply.' );
}

$targets = array(
	'general-and-family-dentistry'    => array( 'When you need more than a checkup.', 'more' ),
	'cosmetic-dentistry'              => array( 'Protecting your new smile .', 'smile' ),
	'restorative-and-implant-dentistry' => array( 'Choosing the right repair.', 'right' ),
	'emergency-and-surgical-dentistry' => array( 'Care beyond the emergency.', 'beyond' ),
);
$changes = array();

// Preflight all four records before any database write. Preserve every other byte.
foreach ( $targets as $slug => $target ) {
	$post = get_page_by_path( $slug, OBJECT, 'service' );
	if ( ! $post || 'publish' !== $post->post_status ) {
		WP_CLI::error( 'Published service hub not found: ' . $slug );
	}
	$pattern = '~(<h2\b[^>]*\bclass="[^"]*\bpillar-related-services__heading\b[^"]*"[^>]*>)(.*?)(</h2>)~s';
	if ( 1 !== preg_match_all( $pattern, $post->post_content, $matches, PREG_SET_ORDER ) ) {
		WP_CLI::error( 'Expected exactly one Related Services heading: ' . $slug );
	}
	$match   = $matches[0];
	$desired = str_replace( $target[1], '<em>' . $target[1] . '</em>', $target[0] );
	if ( 'cosmetic-dentistry' === $slug ) {
		$desired = str_replace( '</em> .', '.</em>', $desired );
	}
	if ( $match[2] === $desired ) {
		WP_CLI::log( $slug . ': already correct.' );
		continue;
	}
	if ( $match[2] !== $target[0] && ! ( 'cosmetic-dentistry' === $slug && in_array( $match[2], array( 'Protecting your new <em>smile</em> .', 'Protecting your new <em>smile</em>.' ), true ) ) ) {
		WP_CLI::error( 'Heading differs from the reviewed QA content: ' . $slug );
	}
	$after     = str_replace( $match[0], $match[1] . $desired . $match[3], $post->post_content );
	$changes[] = array(
		'id'     => $post->ID,
		'slug'   => $slug,
		'before' => $post->post_content,
		'after'  => $after,
	);
	WP_CLI::log( $slug . ': ' . $desired );
}

if ( ! $apply || ! $changes ) {
	WP_CLI::success( $apply ? 'No changes needed.' : 'Preview only; ' . count( $changes ) . ' headings would change.' );
	return;
}

$backup = tempnam( sys_get_temp_dir(), 'white-oaks-qa005-' );
if ( ! $backup || ! chmod( $backup, 0600 ) || false === file_put_contents( $backup, wp_json_encode( array( 'home_url' => home_url(), 'changes' => $changes ), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) ) ) {
	WP_CLI::error( 'Could not save content backup; nothing changed.' );
}
WP_CLI::log( 'Content backup: ' . $backup );

foreach ( $changes as $change ) {
	if ( get_post_field( 'post_content', $change['id'], 'raw' ) !== $change['before'] ) {
		WP_CLI::error( 'Content changed during this run; stopped. Backup: ' . $backup );
	}
	$result = wp_update_post( wp_slash( array( 'ID' => $change['id'], 'post_content' => $change['after'] ) ), true );
	if ( is_wp_error( $result ) || get_post_field( 'post_content', $change['id'], 'raw' ) !== $change['after'] ) {
		WP_CLI::error( 'Update or verification failed; stopped. Backup: ' . $backup );
	}
}
WP_CLI::success( 'Updated and verified ' . count( $changes ) . ' headings. Backup: ' . $backup );
