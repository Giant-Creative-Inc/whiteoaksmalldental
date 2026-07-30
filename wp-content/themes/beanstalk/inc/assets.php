<?php
/**
 * Front-end and editor assets.
 *
 * @package Beanstalk
 */

/**
 * Returns the parent theme version for cache busting.
 *
 * @return string
 */
function beanstalk_get_version() {
	return wp_get_theme( 'beanstalk' )->get( 'Version' );
}

/**
 * Returns a file modification timestamp for asset cache busting.
 *
 * Falls back to the theme version if the asset is unavailable.
 *
 * @param string $relative_path Path relative to the parent theme.
 * @return string
 */
function beanstalk_get_asset_version( $relative_path ) {
	$asset_path = get_parent_theme_file_path( $relative_path );

	if ( file_exists( $asset_path ) ) {
		return (string) filemtime( $asset_path );
	}

	return beanstalk_get_version();
}

/**
 * Returns the critical CSS filename for the current front-end template.
 *
 * @return string
 */
function beanstalk_get_critical_css_template() {
	if ( is_front_page() ) {
		return 'front-page';
	}

	if ( is_page_template( 'page-no-title.html' ) ) {
		return 'page-no-title';
	}

	if ( is_page() ) {
		return 'page';
	}

	if ( is_single() ) {
		return 'single';
	}

	return 'index';
}

/**
 * Prints the current template's generated above-the-fold CSS.
 *
 * @return void
 */
function beanstalk_print_critical_css() {
	$template = beanstalk_get_critical_css_template();
	$path     = get_parent_theme_file_path( "assets/css/critical/{$template}.css" );

	if ( ! file_exists( $path ) ) {
		return;
	}

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Reading a generated, trusted theme asset.
	$critical_css = file_get_contents( $path );

	if ( false === $critical_css || '' === trim( $critical_css ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Generated CSS is a trusted local build artifact.
	printf( '<style id="beanstalk-critical-css">%s</style>' . "\n", $critical_css );
}
add_action( 'wp_head', 'beanstalk_print_critical_css', 1 );

/**
 * Enqueues front-end theme assets.
 *
 * @return void
 */
function beanstalk_enqueue_assets() {
	wp_enqueue_style(
		'beanstalk-google-fonts',
		'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap',
		array(),
		beanstalk_get_version()
	);

	wp_enqueue_style(
		'beanstalk-custom',
		get_parent_theme_file_uri( 'assets/css/custom.css' ),
		array( 'beanstalk-google-fonts' ),
		beanstalk_get_asset_version( 'assets/css/custom.css' )
	);

	wp_enqueue_script(
		'beanstalk-header',
		get_parent_theme_file_uri( 'assets/js/header.js' ),
		array(),
		beanstalk_get_asset_version( 'assets/js/header.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'beanstalk_enqueue_assets' );

/**
 * Loads non-critical front-end stylesheets without blocking first paint.
 *
 * @param string $html   Original stylesheet link.
 * @param string $handle Registered stylesheet handle.
 * @param string $href   Stylesheet URL.
 * @param string $media  Stylesheet media attribute.
 * @return string
 */
function beanstalk_defer_stylesheet( $html, $handle, $href, $media ) {
	$deferred_handles = array(
		'beanstalk-google-fonts',
		'beanstalk-custom',
		'beanstalk-child',
	);

	if ( ! in_array( $handle, $deferred_handles, true ) ) {
		return $html;
	}

	$media_attribute = $media && 'all' !== $media
		? sprintf( ' media="%s"', esc_attr( $media ) )
		: '';
	$stylesheet_id   = esc_attr( "{$handle}-css" );
	$stylesheet_url  = esc_url( $href );

	// phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet -- Filtering links already created by wp_enqueue_style().
	return sprintf(
		'<link rel="preload" as="style" id="%1$s" href="%2$s"%3$s onload="this.onload=null;this.rel=\'stylesheet\'">' .
		'<noscript><link rel="stylesheet" id="%1$s-noscript" href="%2$s"%3$s></noscript>' . "\n",
		$stylesheet_id,
		$stylesheet_url,
		$media_attribute
	);
	// phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet
}
add_filter( 'style_loader_tag', 'beanstalk_defer_stylesheet', 10, 4 );

/**
 * Enqueues assets used by the editor interface.
 *
 * @return void
 */
function beanstalk_editor_assets() {
	wp_enqueue_script(
		'beanstalk-cover-responsive-bg',
		get_parent_theme_file_uri( 'assets/js/cover-responsive-bg.js' ),
		array( 'wp-blocks', 'wp-element', 'wp-hooks', 'wp-compose', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
		beanstalk_get_asset_version( 'assets/js/cover-responsive-bg.js' ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'beanstalk_editor_assets' );
