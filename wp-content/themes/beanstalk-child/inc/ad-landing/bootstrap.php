<?php
/**
 * Opt-in support for the three paid-media landing pages.
 *
 * @package BeanstalkChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Require both an approved slug and the explicitly assigned landing template.
 * Page and form IDs intentionally vary between staging and production.
 *
 * @return bool
 */
function white_oaks_ad_landing_context() {
	if ( is_admin() ) {
		// Read-only editor context, not an action endpoint.
		$id = isset( $_GET['post'] ) ? absint( wp_unslash( $_GET['post'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	} else {
		if ( ! did_action( 'wp' ) || ! is_page() ) {
			return false;
		}
		$id = get_queried_object_id();
	}

	$post = $id ? get_post( $id ) : null;
	return $post instanceof WP_Post
		&& 'page' === $post->post_type
		&& in_array( $post->post_name, array( 'invisalign', 'wisdom-teeth', 'emergency' ), true )
		&& 'ad-landing' === get_page_template_slug( $id );
}

/** @return void */
function white_oaks_ad_landing_reset_design_cache() {
	if ( white_oaks_ad_landing_context() ) {
		// Theme settings may have been resolved before the main query existed.
		WP_Theme_JSON_Resolver::clean_cached_data();
	}
}
add_action( 'wp', 'white_oaks_ad_landing_reset_design_cache', 0 );

/**
 * Keep the approved staging design settings off every other frontend request.
 *
 * @param WP_Theme_JSON_Data $data Current child-theme data.
 * @return WP_Theme_JSON_Data
 */
function white_oaks_ad_landing_design( $data ) {
	if ( ! white_oaks_ad_landing_context() ) {
		return $data;
	}
	$design = wp_json_file_decode( __DIR__ . '/design.json', array( 'associative' => true ) );
	if ( is_array( $design ) ) {
		$data->update_with( $design );
	}
	return $data;
}
add_filter( 'wp_theme_json_data_theme', 'white_oaks_ad_landing_design' );

/** @param string[] $classes Body classes. @return string[] */
function white_oaks_ad_landing_body_class( $classes ) {
	if ( white_oaks_ad_landing_context() ) {
		$classes[] = 'white-oaks-ad-landing';
	}
	return $classes;
}
add_filter( 'body_class', 'white_oaks_ad_landing_body_class' );

/** @return void */
function white_oaks_ad_landing_assets() {
	if ( ! white_oaks_ad_landing_context() ) {
		return;
	}
	if ( ! is_admin() ) {
		// The isolated bundle contains the approved landing equivalents. In
		// particular, production's old !important header-logo rule must not
		// compete with the landing header. Keep those files intact elsewhere.
		foreach ( array( 'beanstalk-child', 'white-oaks-shared', 'white-oaks-component-clinic-gallery', 'white-oaks-component-first-visit', 'white-oaks-component-forms' ) as $handle ) {
			wp_dequeue_style( $handle );
		}
		foreach ( wp_styles()->queue as $handle ) {
			if ( str_starts_with( $handle, 'white-oaks-component-' ) ) {
				wp_dequeue_style( $handle );
			}
		}
	}

	wp_enqueue_style(
		'white-oaks-ad-landing',
		get_stylesheet_directory_uri() . '/assets/css/landing/build/landing.min.css',
		is_admin() ? array( 'wp-edit-blocks' ) : array( 'beanstalk-custom' ),
		beanstalk_child_asset_version( '/assets/css/landing/build/landing.min.css' )
	);
	if ( is_admin() ) {
		return;
	}

	// Replace the existing gallery enhancement only on opted-in landing pages.
	wp_dequeue_script( 'white-oaks-clinic-gallery' );
	$scripts = array( 'clinic-gallery', 'first-visit', 'landing-appointment' );
	$scripts[] = is_page( 'emergency' ) ? 'emergency-landing' : 'accordions';
	foreach ( $scripts as $script ) {
		$path = '/assets/js/landing/' . $script . '.js';
		wp_enqueue_script(
			'white-oaks-ad-landing-' . $script,
			get_stylesheet_directory_uri() . $path,
			array(),
			beanstalk_child_asset_version( $path ),
			array( 'in_footer' => true, 'strategy' => 'defer' )
		);
	}
}
add_action( 'wp_enqueue_scripts', 'white_oaks_ad_landing_assets', 30 );

/** @return void */
function white_oaks_ad_landing_editor_assets() {
	if ( is_admin() ) {
		white_oaks_ad_landing_assets();
	}
}
add_action( 'enqueue_block_assets', 'white_oaks_ad_landing_editor_assets', 30 );

/**
 * Apply presentation bindings without changing the shared block renderer.
 *
 * @param string $html Rendered block.
 * @param array  $block Parsed block.
 * @return string
 */
function white_oaks_ad_landing_render( $html, $block ) {
	if ( ! white_oaks_ad_landing_context() || ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $html;
	}

	$name = $block['blockName'] ?? '';
	$classes = $block['attrs']['className'] ?? '';
	if ( 'core/button' === $name && is_page( 'emergency' ) && str_contains( $classes, 'site-header__cta' ) ) {
		$tags = new WP_HTML_Tag_Processor( $html );
		if ( $tags->next_tag( 'A' ) ) {
			$tags->set_attribute( 'href', 'tel:+15196866200' );
		}
		$html = $tags->get_updated_html();
		return str_replace( array( 'Book Appointment', 'Call Us' ), array( 'Call (519) 686-6200', 'Call Now' ), $html );
	}

	if ( ! in_array( $name, array( 'white-oaks/patient-stories', 'core/cover', 'core/group' ), true ) ) {
		return $html;
	}
	$tags = new WP_HTML_Tag_Processor( $html );
	while ( $tags->next_tag() ) {
		if ( 'white-oaks/patient-stories' === $name ) {
			if ( $tags->has_class( 'patient-stories__inner' ) ) {
				$tags->add_class( 'landing-container-narrow' );
			}
			if ( $tags->has_class( 'patient-stories__rating' ) ) {
				$tags->set_attribute( 'role', 'img' );
			}
			if ( $tags->has_class( 'patient-stories__review-summary' ) ) {
				$tags->remove_class( 'has-b-6-font-size' );
				$tags->add_class( 'has-patient-stories-source-font-size' );
			}
		}
		if ( 'appointment-cta' === ( $block['attrs']['anchor'] ?? '' ) && $tags->has_class( 'wp-block-cover__inner-container' ) ) {
			$tags->add_class( 'landing-container-narrow' );
		}
		if ( str_contains( $classes, 'landing-first-visit' ) && 'IMG' === $tags->get_tag() ) {
			$tags->set_attribute( 'loading', 'lazy' );
			$tags->remove_attribute( 'fetchpriority' );
		}
	}
	return $tags->get_updated_html();
}
add_filter( 'render_block', 'white_oaks_ad_landing_render', 30, 2 );
