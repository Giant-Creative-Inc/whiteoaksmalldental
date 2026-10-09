<?php
/**
 * White Oaks asset loading, adapted from Beanstalk starter commit 3a911fbf.
 *
 * @package BeanstalkChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns a child-theme asset path without a leading slash.
 *
 * Parent traversal and empty paths are rejected so filtered registries cannot
 * resolve files outside the active child theme.
 *
 * @param string $relative_path Path relative to the child-theme directory.
 * @return string
 */
function beanstalk_child_normalize_asset_path( $relative_path ) {
	$relative_path = ltrim( wp_normalize_path( (string) $relative_path ), '/' );

	if ( '' === $relative_path || str_contains( $relative_path, '../' ) || '..' === $relative_path ) {
		return '';
	}

	return $relative_path;
}

/**
 * Returns a local file timestamp for child-theme cache busting.
 *
 * Falls back to the child-theme version when the requested file is absent or
 * the relative path is unsafe.
 *
 * @param string $relative_path Path relative to the child-theme directory.
 * @return string
 */
function beanstalk_child_get_asset_version( $relative_path ) {
	$relative_path = beanstalk_child_normalize_asset_path( $relative_path );
	$asset_path    = $relative_path ? get_stylesheet_directory() . '/' . $relative_path : '';

	return $asset_path && is_file( $asset_path )
		? (string) filemtime( $asset_path )
		: wp_get_theme()->get( 'Version' );
}

/**
 * Returns the current page's saved block content when it is available.
 *
 * The editor lookup is intentionally limited to the post ID supplied by core.
 * Missing or unsupported request state returns an empty string without notices.
 *
 * @return string
 */
function beanstalk_child_get_current_content() {
	if ( is_admin() ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only lookup of the post currently opened in the editor.
		$post_id = isset( $_GET['post'] ) ? absint( wp_unslash( $_GET['post'] ) ) : 0;
		$post    = $post_id ? get_post( $post_id ) : null;

		return $post instanceof WP_Post ? (string) $post->post_content : '';
	}

	$queried_object = get_queried_object();

	return $queried_object instanceof WP_Post ? (string) $queried_object->post_content : '';
}

/**
 * Returns the project-defined component asset registry.
 *
 * The starter registry is empty by design. A child project may add entries via
 * `beanstalk_child_component_assets`. Supported matching keys are `callback`,
 * `markers`, and `blocks`. Supported asset keys are `style`, `script`, and
 * `module`, with optional per-type dependencies and script arguments.
 *
 * @return array<string,array<string,mixed>>
 */
function beanstalk_child_get_component_assets() {
	$assets = apply_filters( 'beanstalk_child_component_assets', array() );

	return is_array( $assets ) ? $assets : array();
}

/**
 * Tests whether a component registry entry applies to the current request.
 *
 * @param array<string,mixed> $asset   Component registry entry.
 * @param string              $content Current saved block content.
 * @return bool
 */
function beanstalk_child_component_is_required( $asset, $content ) {
	if ( ! is_array( $asset ) ) {
		return false;
	}

	if ( ! empty( $asset['callback'] ) && is_callable( $asset['callback'] ) && (bool) call_user_func( $asset['callback'] ) ) {
		return true;
	}

	foreach ( (array) ( $asset['markers'] ?? array() ) as $marker ) {
		if ( is_string( $marker ) && '' !== $marker && '' !== $content && str_contains( $content, $marker ) ) {
			return true;
		}
	}

	foreach ( (array) ( $asset['blocks'] ?? array() ) as $block_name ) {
		if ( is_string( $block_name ) && '' !== $block_name && '' !== $content && has_block( $block_name, $content ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Enqueues a configured component file when it exists in the child theme.
 *
 * @param string              $type  Asset type: style, script, or module.
 * @param string              $slug  Component slug.
 * @param array<string,mixed> $asset Component registry entry.
 * @return string Enqueued handle, or an empty string when not enqueued.
 */
function beanstalk_child_enqueue_component_file( $type, $slug, $asset ) {
	$relative_path = beanstalk_child_normalize_asset_path( $asset[ $type ] ?? '' );
	$asset_path    = $relative_path ? get_stylesheet_directory() . '/' . $relative_path : '';

	if ( ! $asset_path || ! is_file( $asset_path ) ) {
		return '';
	}

	$handle  = $asset[ $type . '_handle' ] ?? 'beanstalk-child-' . sanitize_key( $slug ) . '-' . $type;
	$src     = trailingslashit( get_stylesheet_directory_uri() ) . $relative_path;
	$version = beanstalk_child_get_asset_version( $relative_path );

	if ( 'style' === $type ) {
		wp_enqueue_style( $handle, $src, (array) ( $asset['style_dependencies'] ?? array( 'beanstalk-child' ) ), $version );
	} elseif ( 'script' === $type ) {
		$args = (array) ( $asset['script_args'] ?? array( 'in_footer' => true ) );
		wp_enqueue_script( $handle, $src, (array) ( $asset['script_dependencies'] ?? array() ), $version, $args );
	} elseif ( 'module' === $type && function_exists( 'wp_enqueue_script_module' ) ) {
		wp_enqueue_script_module( $handle, $src, (array) ( $asset['module_dependencies'] ?? array() ), $version );
	} else {
		return '';
	}

	return $handle;
}

/**
 * Enqueues only component assets represented in the current request.
 *
 * @return void
 */
function beanstalk_child_enqueue_component_assets() {
	$content = white_oaks_current_page_content();

	foreach ( beanstalk_child_get_component_assets() as $slug => $asset ) {
		if ( ! is_string( $slug ) || ! beanstalk_child_component_is_required( $asset, $content ) ) {
			continue;
		}

		foreach ( array( 'style', 'script', 'module' ) as $type ) {
			beanstalk_child_enqueue_component_file( $type, $slug, $asset );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'beanstalk_child_enqueue_component_assets', 30 );

/**
 * Enqueues the neutral child stylesheet after the parent compiled stylesheet.
 *
 * @return void
 */
function beanstalk_child_enqueue_styles() {
	wp_enqueue_style( 'white-oaks-adobe-fonts', 'https://use.typekit.net/bax3ecf.css', array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- External font provider owns versioning.
	wp_enqueue_style( 'beanstalk-child', get_stylesheet_directory_uri() . '/assets/css/build/custom.min.css', array( 'beanstalk-custom' ), beanstalk_child_get_asset_version( 'assets/css/build/custom.min.css' ) );
	wp_enqueue_style( 'white-oaks-shared', get_stylesheet_directory_uri() . '/assets/css/build/shared.min.css', array( 'beanstalk-child' ), beanstalk_child_get_asset_version( 'assets/css/build/shared.min.css' ) );
}
add_action( 'wp_enqueue_scripts', 'beanstalk_child_enqueue_styles', 20 );

/**
 * Adds file timestamps to local child-theme asset URLs.
 *
 * This also covers assets registered through block metadata and other APIs
 * that do not call the child version helper directly.
 *
 * @param string $src Asset URL.
 * @return string
 */
function beanstalk_child_version_asset_url( $src ) {
	$theme_uri = trailingslashit( get_stylesheet_directory_uri() );

	if ( ! is_string( $src ) || ! str_starts_with( $src, $theme_uri ) ) {
		return $src;
	}

	$url           = strtok( $src, '?#' );
	$relative_path = beanstalk_child_normalize_asset_path( rawurldecode( substr( $url, strlen( $theme_uri ) ) ) );
	$asset_path    = $relative_path ? get_stylesheet_directory() . '/' . $relative_path : '';

	if ( ! $asset_path || ! is_file( $asset_path ) ) {
		return $src;
	}

	return add_query_arg( 'ver', (string) filemtime( $asset_path ), remove_query_arg( 'ver', $src ) );
}
add_filter( 'style_loader_src', 'beanstalk_child_version_asset_url', 20 );
add_filter( 'script_loader_src', 'beanstalk_child_version_asset_url', 20 );
add_filter( 'script_module_loader_src', 'beanstalk_child_version_asset_url', 20 );

/**
 * Applies defer to explicitly approved child script handles.
 *
 * No script is deferred until a project adds its handle through the
 * `beanstalk_child_deferred_script_handles` filter.
 *
 * @return void
 */
function beanstalk_child_apply_script_strategies() {
	$handles = apply_filters( 'beanstalk_child_deferred_script_handles', array() );

	foreach ( array_filter( (array) $handles, 'is_string' ) as $handle ) {
		if ( '' !== $handle ) {
			wp_script_add_data( $handle, 'strategy', 'defer' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'beanstalk_child_apply_script_strategies', 100 );

/**
 * Loads explicitly verified below-the-fold child styles asynchronously.
 *
 * No stylesheet is deferred until a project adds its handle through the
 * `beanstalk_child_below_fold_style_handles` filter.
 *
 * @param string $html   Original stylesheet link markup.
 * @param string $handle Registered stylesheet handle.
 * @return string
 */
function beanstalk_child_defer_below_fold_style( $html, $handle ) {
	if ( is_admin() ) {
		return $html;
	}

	$handles = apply_filters( 'beanstalk_child_below_fold_style_handles', array() );

	if ( ! in_array( $handle, (array) $handles, true ) || ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $html;
	}

	$processor = new WP_HTML_Tag_Processor( $html );

	if ( ! $processor->next_tag( 'link' ) ) {
		return $html;
	}

	$processor->set_attribute( 'media', 'print' );
	$processor->set_attribute( 'onload', "this.onload=null;this.media='all'" );

	return $processor->get_updated_html() . '<noscript>' . $html . '</noscript>' . "\n";
}
add_filter( 'style_loader_tag', 'beanstalk_child_defer_below_fold_style', 20, 2 );

/**
 * Expands saved references without rendering blocks or executing shortcodes.
 *
 * @param string $content Saved markup.
 * @param array  $visited Reference keys already inspected, to stop cycles.
 * @return string
 */
function white_oaks_resolve_asset_content( $content, &$visited ) {
	$resolved = $content;
	foreach ( parse_blocks( $content ) as $block ) {
		$name  = $block['blockName'];
		$attrs = $block['attrs'];
		$extra = '';
		$key   = '';
		if ( 'core/block' === $name && ! empty( $attrs['ref'] ) ) {
			$key = 'post:' . absint( $attrs['ref'] );
			if ( ! isset( $visited[ $key ] ) ) {
				$post  = get_post( absint( $attrs['ref'] ) );
				$extra = $post instanceof WP_Post ? $post->post_content : '';
			}
		} elseif ( 'core/template-part' === $name && ! empty( $attrs['slug'] ) ) {
			$key = 'part:' . ( $attrs['theme'] ?? get_stylesheet() ) . '//' . $attrs['slug'];
			if ( ! isset( $visited[ $key ] ) ) {
				$part  = get_block_template( substr( $key, 5 ), 'wp_template_part' );
				$extra = $part ? $part->content : '';
			}
		}
		if ( $key && ! isset( $visited[ $key ] ) ) {
			$visited[ $key ] = true;
			$resolved      .= "\n" . white_oaks_resolve_asset_content( $extra, $visited );
		}
		if ( ! empty( $block['innerBlocks'] ) ) {
			$resolved .= "\n" . white_oaks_resolve_asset_content( serialize_blocks( $block['innerBlocks'] ), $visited );
		}
	}
	return $resolved;
}

/** Returns page and active template markup, including synced patterns. */
function white_oaks_current_page_content() {
	global $_wp_current_template_content;
	$content = beanstalk_child_get_current_content();
	if ( ! is_admin() ) {
		$content .= "\n" . (string) ( $_wp_current_template_content ?? '' );
		$content .= "\n" . (string) ( $GLOBALS['white_oaks_rendered_asset_content'] ?? '' );
	}
	$visited = array();
	return white_oaks_resolve_asset_content( $content, $visited );
}

/**
 * Captures dynamic block and Query Loop markers before the document head.
 *
 * @param string $html  Rendered markup.
 * @param array  $block Parsed block.
 * @return string
 */
function white_oaks_record_rendered_asset_content( $html, $block ) {
	if ( ! is_admin() && ! did_action( 'wp_head' ) ) {
		$GLOBALS['white_oaks_rendered_asset_content'] = ( $GLOBALS['white_oaks_rendered_asset_content'] ?? '' ) . "\n" .
			( $block['blockName'] ?? '' ) . ' ' . ( $block['attrs']['className'] ?? '' ) . ' ' . ( $block['innerHTML'] ?? '' );
		if ( empty( $block['innerHTML'] ) || 'core/shortcode' === ( $block['blockName'] ?? '' ) ) {
			$GLOBALS['white_oaks_rendered_asset_content'] .= $html;
		}
	}
	return $html;
}
add_filter( 'render_block', 'white_oaks_record_rendered_asset_content', 20, 2 );

/**
 * Configures the upstream registry with the existing client asset handles.
 *
 * @param array $assets Existing registry.
 * @return array
 */
function white_oaks_component_assets( $assets ) {
	$compatibility = array(
		'home-sections' => array( 'meet-dentists', 'home-faqs', 'footer-cta' ),
		'forms'         => array( 'gravityforms/form', '[gravityform', 'contact-section__form-embed' ),
	);
	foreach ( glob( get_stylesheet_directory() . '/assets/css/build/components/*.min.css' ) ?: array() as $path ) {
		$slug            = basename( $path, '.min.css' );
		$assets[ $slug ] = array(
			'markers'            => $compatibility[ $slug ] ?? array( $slug ),
			'style'              => 'assets/css/build/components/' . basename( $path ),
			'style_handle'       => 'white-oaks-component-' . sanitize_key( $slug ),
			'style_dependencies' => array( 'white-oaks-shared' ),
		);
	}
	foreach ( array( 'doctors-section', 'clinic-gallery', 'contact-section', 'first-visit', 'comfortable-care' ) as $slug ) {
		$assets[ $slug ]['markers']       = array( $slug );
		$assets[ $slug ]['script']        = 'assets/js/build/' . $slug . '.min.js';
		$assets[ $slug ]['script_handle'] = 'white-oaks-' . $slug;
		$assets[ $slug ]['script_args']   = array( 'in_footer' => true, 'strategy' => 'defer' );
	}
	$assets['services-menu']['markers'] = array( 'services-menu' );
	$assets['services-menu']['module'] = 'assets/js/build/services-menu.min.js';
	$assets['services-menu']['module_dependencies'] = array( '@wordpress/interactivity' );
	$assets['home'] = array(
		'callback'           => 'is_front_page',
		'markers'            => array( 'why-oaks' ),
		'style'              => 'assets/css/build/home.min.css',
		'style_handle'       => 'white-oaks-home',
		'style_dependencies' => array( 'white-oaks-shared' ),
	);
	return $assets;
}
add_filter( 'beanstalk_child_component_assets', 'white_oaks_component_assets' );

/**
 * Defers reviewed child scripts while leaving plugin dependencies to their owners.
 *
 * @param array $handles Existing deferred handles.
 * @return array
 */
function white_oaks_deferred_script_handles( $handles ) {
	$handles = array_merge( $handles, array( 'white-oaks-form-attribution' ) );
	foreach ( array( 'white-oaks/patient-stories', 'white-oaks/click-to-load-map' ) as $name ) {
		$block = WP_Block_Type_Registry::get_instance()->get_registered( $name );
		if ( $block ) {
			$handles = array_merge( $handles, $block->view_script_handles );
		}
	}
	return array_unique( $handles );
}
add_filter( 'beanstalk_child_deferred_script_handles', 'white_oaks_deferred_script_handles' );

/**
 * Keeps the established below-fold choices on the homepage only.
 *
 * @param array $handles Existing asynchronous style handles.
 * @return array
 */
function white_oaks_below_fold_style_handles( $handles ) {
	if ( is_front_page() ) {
		$handles = array_merge( $handles, array(
			'white-oaks-service-tabs',
			'white-oaks-component-clinic-gallery',
			'white-oaks-component-experience-section',
			'white-oaks-component-home-sections',
		) );
	}
	return array_unique( $handles );
}
add_filter( 'beanstalk_child_below_fold_style_handles', 'white_oaks_below_fold_style_handles' );

/** Loads all component CSS synchronously for unsaved editor insertions. */
function white_oaks_editor_component_styles() {
	if ( ! is_admin() ) {
		return;
	}
	foreach ( beanstalk_child_get_component_assets() as $slug => $asset ) {
		$asset['style_dependencies'] = array( 'wp-edit-blocks' );
		beanstalk_child_enqueue_component_file( 'style', $slug, $asset );
	}
}
add_action( 'enqueue_block_assets', 'white_oaks_editor_component_styles' );

/** Keeps global parent/child CSS synchronous; only the child allowlist is async. */
function white_oaks_configure_parent_asset_loading() {
	remove_filter( 'style_loader_tag', 'beanstalk_defer_stylesheet', 10 );
}
add_action( 'after_setup_theme', 'white_oaks_configure_parent_asset_loading', 30 );
