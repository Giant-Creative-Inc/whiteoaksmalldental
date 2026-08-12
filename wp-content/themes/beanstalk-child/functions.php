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
 * Returns the decorative diagonal arrow used by actionable buttons.
 *
 * The path uses currentColor so every button style controls the icon colour.
 *
 * @return string Trusted inline SVG markup.
 */
function white_oaks_button_arrow_svg() {
	return '<svg class="button-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none" aria-hidden="true" focusable="false"><path fill="currentColor" d="M3.335 9.789 2.29 8.74l4.76-4.764H3.455l.009-1.432h6.061v6.067H8.09l.009-3.592z"/></svg>';
}

/**
 * Replaces diagonal-arrow text in core Button output with the shared SVG.
 *
 * Saved block markup remains unchanged so Gutenberg validation is unaffected.
 *
 * @param string $block_content Rendered Button block markup.
 * @return string
 */
function white_oaks_render_button_arrow( $block_content ) {
	if ( ! str_contains( $block_content, '↗' ) ) {
		return $block_content;
	}

	$svg = white_oaks_button_arrow_svg();

	$block_content = str_replace( '<span aria-hidden="true">↗</span>', $svg, $block_content );

	return str_replace( '↗', $svg, $block_content );
}
add_filter( 'render_block_core/button', 'white_oaks_render_button_arrow' );

/**
 * Replaces the emergency phone link's diagonal-arrow text with the shared SVG.
 *
 * @param string $block_content Rendered Paragraph block markup.
 * @param array  $block         Parsed Paragraph block.
 * @return string
 */
function white_oaks_render_emergency_phone_arrow( $block_content, $block ) {
	$class_name = $block['attrs']['className'] ?? '';

	if ( ! str_contains( $class_name, 'emergency-cta__phone' ) || ! str_contains( $block_content, '↗' ) ) {
		return $block_content;
	}

	$arrow_markup = '<span class="emergency-cta__phone-arrow" aria-hidden="true">' . white_oaks_button_arrow_svg() . '</span>';

	return str_replace( '<span class="emergency-cta__phone-arrow" aria-hidden="true">↗</span>', $arrow_markup, $block_content );
}
add_filter( 'render_block_core/paragraph', 'white_oaks_render_emergency_phone_arrow', 10, 2 );

/**
 * Makes the complete emergency call box the telephone link.
 *
 * The saved Group and Paragraph blocks remain unchanged so Gutenberg can keep
 * validating and editing their native markup.
 *
 * @param string $block_content Rendered Group block markup.
 * @param array  $block         Parsed Group block.
 * @return string
 */
function white_oaks_render_emergency_call_box_link( $block_content, $block ) {
	$class_name = $block['attrs']['className'] ?? '';

	if ( ! str_contains( $class_name, 'emergency-cta__call-box' ) ) {
		return $block_content;
	}

	if ( ! preg_match( '/<a\b[^>]*href="(tel:[^"]+)"[^>]*>/', $block_content, $phone_link ) ) {
		return $block_content;
	}

	$block_content = preg_replace( '/<a\b[^>]*href="tel:[^"]+"[^>]*>/', '', $block_content, 1 );
	$block_content = preg_replace( '/<\/a>/', '', $block_content, 1 );
	$block_content = preg_replace(
		'/^(\s*)<div\b/',
		'$1<a href="' . esc_attr( $phone_link[1] ) . '" aria-label="Call our care team at (519) 686-6200"',
		$block_content,
		1
	);

	return preg_replace( '/<\/div>\s*$/', '</a>', $block_content, 1 );
}
add_filter( 'render_block_core/group', 'white_oaks_render_emergency_call_box_link', 10, 2 );

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
 * Returns compiled component styles and the semantic classes that activate them.
 *
 * A component stylesheet is discovered automatically when its compiled filename
 * matches the component's root class. The compatibility map covers established
 * grouped stylesheets whose filename predates that convention.
 *
 * @return array<string, array{path:string, markers:array<int, string>}>
 */
function white_oaks_component_styles() {
	$build_directory  = get_stylesheet_directory() . '/assets/css/build/components';
	$styles           = array();
	$compatibility    = array(
		'home-sections' => array( 'meet-dentists', 'home-faqs', 'footer-cta' ),
	);
	$component_assets = glob( $build_directory . '/*.min.css' );

	if ( false === $component_assets ) {
		$component_assets = array();
	}

	foreach ( $component_assets as $asset_path ) {
		$slug            = basename( $asset_path, '.min.css' );
		$styles[ $slug ] = array(
			'path'    => '/assets/css/build/components/' . basename( $asset_path ),
			'markers' => $compatibility[ $slug ] ?? array( $slug ),
		);
	}

	return $styles;
}

/**
 * Returns the saved block markup for the current frontend or editor page.
 *
 * @return string
 */
function white_oaks_current_page_content() {
	if ( is_admin() ) {
		$post_id = isset( $_GET['post'] ) ? absint( wp_unslash( $_GET['post'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$post    = $post_id ? get_post( $post_id ) : null;

		return $post instanceof WP_Post ? $post->post_content : '';
	}

	$queried_object = get_queried_object();

	return $queried_object instanceof WP_Post ? $queried_object->post_content : '';
}

/**
 * Enqueues only the component styles represented in the current page markup.
 *
 * @param string $content      Saved block markup to inspect.
 * @param array  $dependencies Style handles that must load first.
 * @return array<int, string> Enqueued component handles.
 */
function white_oaks_enqueue_component_styles( $content, $dependencies = array() ) {
	$handles = array();

	if ( '' === $content ) {
		return $handles;
	}

	foreach ( white_oaks_component_styles() as $slug => $component ) {
		$required = false;

		foreach ( $component['markers'] as $marker ) {
			if ( str_contains( $content, $marker ) ) {
				$required = true;
				break;
			}
		}

		if ( ! $required ) {
			continue;
		}

		$handle = 'white-oaks-component-' . sanitize_key( $slug );
		wp_enqueue_style(
			$handle,
			get_stylesheet_directory_uri() . $component['path'],
			$dependencies,
			beanstalk_child_asset_version( $component['path'] )
		);
		$handles[] = $handle;
	}

	return $handles;
}

/**
 * Enqueues global child assets and content-aware component styles.
 *
 * @return void
 */
function beanstalk_child_enqueue_styles() {
	$content = white_oaks_current_page_content();

	// The external provider controls the Typekit stylesheet version.
	wp_enqueue_style(
		'white-oaks-adobe-fonts',
		'https://use.typekit.net/bax3ecf.css',
		array(),
		null // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- Versioned by the external provider.
	);

	wp_enqueue_style(
		'beanstalk-child',
		get_stylesheet_directory_uri() . '/assets/css/build/custom.min.css',
		array( 'beanstalk-custom' ),
		beanstalk_child_asset_version( '/assets/css/build/custom.min.css' )
	);

	wp_enqueue_style(
		'white-oaks-shared',
		get_stylesheet_directory_uri() . '/assets/css/build/shared.min.css',
		array( 'beanstalk-child' ),
		beanstalk_child_asset_version( '/assets/css/build/shared.min.css' )
	);

	white_oaks_enqueue_component_styles(
		$content,
		array( 'white-oaks-shared' )
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
 * Loads Adobe Fonts CSS without blocking the initial render.
 *
 * @param string $html   Stylesheet HTML.
 * @param string $handle Stylesheet handle.
 * @return string
 */
function white_oaks_async_adobe_fonts( $html, $handle ) {
	if ( 'white-oaks-adobe-fonts' !== $handle ) {
		return $html;
	}

	$href = 'https://use.typekit.net/bax3ecf.css';

	return sprintf(
		'<link rel="preload" href="%1$s" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n" .
		'<noscript>%2$s</noscript>' . "\n",
		esc_url( $href ),
		$html
	);
}
add_filter( 'style_loader_tag', 'white_oaks_async_adobe_fonts', 10, 2 );

/**
 * Adds early connection hints for the approved external font provider.
 *
 * @param array  $urls          URLs queued for the relationship type.
 * @param string $relation_type Resource-hint relationship type.
 * @return array
 */
function white_oaks_font_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' !== $relation_type ) {
		return $urls;
	}

	$urls[] = array(
		'href'        => 'https://use.typekit.net',
		'crossorigin' => 'anonymous',
	);
	$urls[] = array(
		'href'        => 'https://p.typekit.net',
		'crossorigin' => 'anonymous',
	);
	return $urls;
}
add_filter( 'wp_resource_hints', 'white_oaks_font_resource_hints', 10, 2 );

/**
 * Supplies explicit loading attributes for selected Image blocks.
 *
 * WordPress cannot derive raster metadata from this sanitized SVG upload, so
 * add its approved 150:56 aspect ratio at render time without changing saved
 * block markup or Media Library data. Reception variants are deliberately
 * lazy because CSS chooses the appropriate one below the fold.
 *
 * @param string $block_content Rendered Image block markup.
 * @param array  $block         Parsed Image block.
 * @return string
 */
function white_oaks_image_loading_attributes( $block_content, $block ) {
	$attachment_id = (int) ( $block['attrs']['id'] ?? 0 );
	$class_name    = $block['attrs']['className'] ?? '';

	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );
	if ( ! $processor->next_tag( 'img' ) ) {
		return $block_content;
	}

	if ( 9 === $attachment_id ) {
		$processor->set_attribute( 'width', '150' );
		$processor->set_attribute( 'height', '56' );
		$processor->set_attribute( 'loading', str_contains( $class_name, 'site-footer__logo' ) ? 'lazy' : 'eager' );
	}

	if ( in_array( $attachment_id, array( 31, 32 ), true ) ) {
		$processor->set_attribute( 'loading', 'lazy' );
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/image', 'white_oaks_image_loading_attributes', 10, 2 );

/**
 * Makes the homepage hero image's above-the-fold priority explicit.
 *
 * @param string $block_content Rendered Cover block markup.
 * @param array  $block         Parsed Cover block.
 * @return string
 */
function white_oaks_hero_cover_image_attributes( $block_content, $block ) {
	if ( 12 !== (int) ( $block['attrs']['id'] ?? 0 ) || ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );
	if ( $processor->next_tag( 'img' ) ) {
		$processor->set_attribute( 'loading', 'eager' );
		$processor->set_attribute( 'fetchpriority', 'high' );
	}

	return $processor->get_updated_html();
}
add_filter( 'render_block_core/cover', 'white_oaks_hero_cover_image_attributes', 9, 2 );

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
				'https://use.typekit.net/bax3ecf.css',
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
 * Loads content-aware component styles and homepage styles in the editor.
 *
 * @return void
 */
function white_oaks_editor_component_styles() {
	if ( ! is_admin() ) {
		return;
	}

	$post_id       = isset( $_GET['post'] ) ? absint( wp_unslash( $_GET['post'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$front_page_id = (int) get_option( 'page_on_front' );
	$content       = white_oaks_current_page_content();

	if ( ! $post_id ) {
		return;
	}

	white_oaks_enqueue_component_styles( $content, array( 'wp-edit-blocks' ) );

	if ( $post_id === $front_page_id ) {
		wp_enqueue_style(
			'white-oaks-home-editor',
			get_stylesheet_directory_uri() . '/assets/css/build/home.min.css',
			array( 'wp-edit-blocks' ),
			beanstalk_child_asset_version( '/assets/css/build/home.min.css' )
		);
	}
}
add_action( 'enqueue_block_assets', 'white_oaks_editor_component_styles' );

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
