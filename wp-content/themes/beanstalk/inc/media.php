<?php
/**
 * Theme media support.
 *
 * SVG uploads are sanitized before WordPress moves them into the Media Library.
 *
 * @package Beanstalk
 */

/**
 * Adds SVG file extensions to WordPress's upload allowlist.
 *
 * @param array $mimes Allowed MIME types.
 * @return array
 */
function beanstalk_allow_svg( $mimes ) {
	if ( class_exists( '\enshrined\svgSanitize\Sanitizer' ) ) {
		$mimes['svg'] = 'image/svg+xml';
	}

	return $mimes;
}
add_filter( 'upload_mimes', 'beanstalk_allow_svg' );

/**
 * Sanitizes an SVG upload and removes remote references.
 *
 * SVG uploads fail closed when the sanitizer dependency is unavailable or the
 * document cannot be parsed safely.
 *
 * @param array $file Uploaded file data.
 * @return array
 */
function beanstalk_sanitize_svg_upload( $file ) {
	$extension = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );

	if ( 'svg' !== $extension || ! empty( $file['error'] ) ) {
		return $file;
	}

	if ( ! class_exists( '\enshrined\svgSanitize\Sanitizer' ) ) {
		$file['error'] = __( 'SVG uploads are unavailable because the sanitizer is not installed.', 'beanstalk' );

		return $file;
	}

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Reading WordPress's temporary upload is required before it is moved.
	$dirty_svg = file_get_contents( $file['tmp_name'] );

	if ( false === $dirty_svg ) {
		$file['error'] = __( 'WordPress could not read the uploaded SVG.', 'beanstalk' );

		return $file;
	}

	$sanitizer = new \enshrined\svgSanitize\Sanitizer();
	$sanitizer->removeRemoteReferences( true );
	$clean_svg = $sanitizer->sanitize( $dirty_svg );

	if ( false === $clean_svg || '' === trim( $clean_svg ) ) {
		$file['error'] = __( 'The uploaded SVG is malformed or contains unsupported content.', 'beanstalk' );

		return $file;
	}

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents -- Replacing WordPress's temporary upload before it is moved.
	$bytes_written = file_put_contents( $file['tmp_name'], $clean_svg, LOCK_EX );

	if ( false === $bytes_written ) {
		$file['error'] = __( 'WordPress could not save the sanitized SVG.', 'beanstalk' );
	}

	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'beanstalk_sanitize_svg_upload' );

/**
 * Corrects SVG MIME detection after WordPress checks the uploaded file.
 *
 * @param array|string $data     File type data.
 * @param string       $file     Full path to the file.
 * @param string       $filename Original filename.
 * @return array|string
 */
function beanstalk_fix_svg_mime( $data, $file, $filename ) {
	unset( $file );

	$extension = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );

	if ( 'svg' === $extension && class_exists( '\enshrined\svgSanitize\Sanitizer' ) ) {
		$data['ext']  = 'svg';
		$data['type'] = 'image/svg+xml';
	}

	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'beanstalk_fix_svg_mime', 10, 3 );
