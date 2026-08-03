<?php
/**
 * Beanstalk child theme functions.
 *
 * @package BeanstalkChild
 */

/**
 * Returns a cache-busting version for a child-theme asset.
 *
 * @param string $relative_path Asset path relative to the child-theme directory.
 * @return string
 */
function beanstalk_child_asset_version( $relative_path ) {
	$asset_path = get_stylesheet_directory() . $relative_path;

	return file_exists( $asset_path )
		? (string) filemtime( $asset_path )
		: wp_get_theme()->get( 'Version' );
}

/**
 * Versions child-theme CSS and JavaScript URLs using each file's edit time.
 *
 * This also covers assets registered from block.json, whose metadata version
 * would otherwise remain fixed until it is manually updated.
 *
 * @param string $src Asset URL.
 * @return string
 */
function white_oaks_version_child_asset_url( $src ) {
	$theme_uri = trailingslashit( get_stylesheet_directory_uri() );

	if ( ! str_starts_with( $src, $theme_uri ) ) {
		return $src;
	}

	$url_without_query = strtok( $src, '?#' );
	$relative_path     = rawurldecode( substr( $url_without_query, strlen( $theme_uri ) ) );
	$asset_path        = get_stylesheet_directory() . '/' . $relative_path;

	if ( ! is_file( $asset_path ) ) {
		return $src;
	}

	return add_query_arg( 'ver', (string) filemtime( $asset_path ), remove_query_arg( 'ver', $src ) );
}
add_filter( 'style_loader_src', 'white_oaks_version_child_asset_url', 20 );
add_filter( 'script_loader_src', 'white_oaks_version_child_asset_url', 20 );
add_filter( 'script_module_loader_src', 'white_oaks_version_child_asset_url', 20 );

/**
 * Enqueues global child assets and homepage-only component styles.
 *
 * @return void
 */
function beanstalk_child_enqueue_styles() {

	// The external provider controls the Typekit stylesheet version.
	wp_enqueue_style(
		'white-oaks-adobe-fonts',
		'https://use.typekit.net/pnx3ojj.css',
		array(),
		null // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- Versioned by the external provider.
	);

	// Google controls the hosted font stylesheet version.
	wp_enqueue_style(
		'white-oaks-google-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400..900&display=swap',
		array(),
		null // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- Versioned by the external provider.
	);

	wp_enqueue_style(
		'beanstalk-child',
		get_stylesheet_directory_uri() . '/assets/css/build/custom.min.css',
		array( 'beanstalk-custom', 'white-oaks-adobe-fonts', 'white-oaks-google-fonts' ),
		beanstalk_child_asset_version( '/assets/css/build/custom.min.css' )
	);

	wp_enqueue_style(
		'white-oaks-shared',
		get_stylesheet_directory_uri() . '/assets/css/build/shared.min.css',
		array( 'beanstalk-child' ),
		beanstalk_child_asset_version( '/assets/css/build/shared.min.css' )
	);

	if ( is_front_page() ) {
		wp_enqueue_style(
			'white-oaks-home',
			get_stylesheet_directory_uri() . '/assets/css/build/home.min.css',
			array( 'white-oaks-shared' ),
			beanstalk_child_asset_version( '/assets/css/build/home.min.css' )
		);
	}
}
add_action( 'wp_enqueue_scripts', 'beanstalk_child_enqueue_styles', 20 );

/**
 * Suppresses the parent's unused Poppins request while preserving its handle
 * for dependency resolution.
 *
 * @param string $html   Stylesheet markup.
 * @param string $handle Registered stylesheet handle.
 * @return string
 */
function white_oaks_remove_unused_parent_font( $html, $handle ) {
	return 'beanstalk-google-fonts' === $handle ? '' : $html;
}
add_filter( 'style_loader_tag', 'white_oaks_remove_unused_parent_font', 20, 2 );

/**
 * Replaces the parent's Poppins editor stylesheet with the client fonts and
 * the child theme's editor-relevant styles.
 *
 * @return void
 */
function white_oaks_child_editor_styles() {
	$editor_assets = array(
		'/assets/css/build/custom.min.css',
		'/assets/css/build/shared.min.css',
	);

	remove_editor_styles();
	add_editor_style(
		array_merge(
			array(
				'https://use.typekit.net/pnx3ojj.css',
				'https://fonts.googleapis.com/css2?family=Inter:wght@400..900&display=swap',
				get_parent_theme_file_uri( 'assets/css/custom.css' ),
			),
			array_map(
				static function ( $relative_path ) {
					return add_query_arg(
						'ver',
						beanstalk_child_asset_version( $relative_path ),
						get_stylesheet_directory_uri() . $relative_path
					);
				},
				$editor_assets
			)
		)
	);
}
add_action( 'after_setup_theme', 'white_oaks_child_editor_styles', 20 );

/**
 * Loads homepage component styles only in the designated front-page editor.
 *
 * @return void
 */
function white_oaks_home_editor_styles() {
	if ( ! is_admin() ) {
		return;
	}

	$post_id       = isset( $_GET['post'] ) ? absint( wp_unslash( $_GET['post'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$front_page_id = (int) get_option( 'page_on_front' );

	if ( ! $post_id || $post_id !== $front_page_id ) {
		return;
	}

	wp_enqueue_style(
		'white-oaks-home-editor',
		get_stylesheet_directory_uri() . '/assets/css/build/home.min.css',
		array( 'wp-edit-blocks' ),
		beanstalk_child_asset_version( '/assets/css/build/home.min.css' )
	);
}
add_action( 'enqueue_block_assets', 'white_oaks_home_editor_styles' );

/**
 * Adds accurate responsive-image hints to the homepage experience cards.
 *
 * @param string $block_content Rendered Cover block markup.
 * @param array  $block         Parsed Cover block.
 * @return string
 */
function white_oaks_experience_cover_image_attributes( $block_content, $block ) {
	$class_name = $block['attrs']['className'] ?? '';

	if ( ! str_contains( $class_name, 'experience-card__media' ) ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );

	if ( $processor->next_tag( 'IMG' ) ) {
		$processor->set_attribute( 'loading', 'lazy' );
		$processor->set_attribute(
			'sizes',
			'(max-width: 1024px) calc(100vw - 32px), (max-width: 1408px) calc((100vw - 208px) / 2), 600px'
		);
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/cover', 'white_oaks_experience_cover_image_attributes', 10, 2 );

/**
 * Adds responsive-image hints to the Meet Our Dentists portraits.
 *
 * @param string $block_content Rendered Cover block markup.
 * @param array  $block         Parsed Cover block.
 * @return string
 */
function white_oaks_team_cover_image_attributes( $block_content, $block ) {
	$class_name = $block['attrs']['className'] ?? '';

	if ( ! str_contains( $class_name, 'meet-dentists__media' ) ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );

	if ( $processor->next_tag( 'IMG' ) ) {
		$processor->set_attribute( 'loading', 'lazy' );
		$processor->set_attribute(
			'sizes',
			'(max-width: 767px) calc(100vw - 32px), (max-width: 1024px) calc((100vw - 48px) / 2), (max-width: 1279px) calc((100vw - 80px) / 4), 292px'
		);
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/cover', 'white_oaks_team_cover_image_attributes', 11, 2 );

/**
 * Adds colour-scheme-aware browser favicons.
 *
 * The dark artwork is used on light browser chrome and the light artwork is
 * used on dark browser chrome. The saved WordPress Site Icon remains the
 * fallback for browsers that do not support media queries on icon links.
 *
 * @return void
 */
function white_oaks_add_browser_favicons() {
	$images_uri = get_stylesheet_directory_uri() . '/assets/images/';
	?>
	<link rel="icon" type="image/png" href="<?php echo esc_url( $images_uri . 'favicon-dark.png' ); ?>" media="(prefers-color-scheme: light)">
	<link rel="icon" type="image/png" href="<?php echo esc_url( $images_uri . 'favicon-light.png' ); ?>" media="(prefers-color-scheme: dark)">
	<?php
}
add_action( 'wp_head', 'white_oaks_add_browser_favicons', 100 );

require_once get_stylesheet_directory() . '/inc/blocks.php';
