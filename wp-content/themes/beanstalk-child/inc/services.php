<?php
/**
 * Local service content model. Kept in the child theme by user instruction.
 * No automatic content migration, taxonomy seeding, or rewrite flushing.
 *
 * @package BeanstalkChild
 */
defined( 'ABSPATH' ) || exit;

function white_oaks_register_services() {
	register_post_type( 'service', array(
		'labels' => array( 'name' => __( 'Services', 'beanstalk-child' ), 'singular_name' => __( 'Service', 'beanstalk-child' ), 'add_new_item' => __( 'Add Service', 'beanstalk-child' ), 'edit_item' => __( 'Edit Service', 'beanstalk-child' ) ),
		'public' => true,
		'show_in_rest' => true,
		'menu_icon' => 'dashicons-heart',
		'has_archive' => false,
		'hierarchical' => true,
		'rewrite' => array( 'slug' => 'services', 'with_front' => false, 'hierarchical' => true ),
		'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes' ),
		'template' => array( array( 'core/pattern', array( 'slug' => 'beanstalk-child/service-starter' ) ) ),
	) );
	$fields = array(
		'_white_oaks_related_services' => array( 'type' => 'array', 'default' => array(), 'sanitize_callback' => 'white_oaks_sanitize_related_services', 'show_in_rest' => array( 'schema' => array( 'type' => 'array', 'items' => array( 'type' => 'integer' ) ) ) ),
		'_white_oaks_related_fallback' => array( 'type' => 'boolean', 'default' => true, 'sanitize_callback' => 'rest_sanitize_boolean', 'show_in_rest' => true ),
		'_white_oaks_service_type' => array( 'type' => 'string', 'default' => '', 'sanitize_callback' => 'sanitize_text_field', 'show_in_rest' => true ),
	);
	foreach ( $fields as $key => $args ) {
		register_post_meta( 'service', $key, array_merge( $args, array(
			'single' => true,
			'revisions_enabled' => true,
			'auth_callback' => static function ( $allowed, $meta_key, $post_id ) { return current_user_can( 'edit_post', $post_id ); },
		) ) );
	}
}
add_action( 'init', 'white_oaks_register_services' );

/** Pillar paths are independent of the retained category slugs. */
function white_oaks_service_pillar_definitions() {
	return array(
		'general-and-family-dentistry' => 'General & Family Dentistry',
		'cosmetic-dentistry' => 'Cosmetic Dentistry',
		'restorative-and-implant-dentistry' => 'Restorative & Implant Dentistry',
		'emergency-and-surgical-dentistry' => 'Emergency & Surgical Dentistry',
	);
}

function white_oaks_is_service_pillar( $post_id ) {
	$post = get_post( $post_id );
	return $post && 'service' === $post->post_type && ! $post->post_parent && isset( white_oaks_service_pillar_definitions()[ $post->post_name ] );
}

/** Only redirect unowned old flat URLs with one unambiguous published treatment. */
function white_oaks_redirect_legacy_service() {
	if ( ! is_404() || is_preview() || ! in_array( $_SERVER['REQUEST_METHOD'] ?? 'GET', array( 'GET', 'HEAD' ), true ) ) { return; }
	$path = trim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
	$prefix = $path ? $path . '/services/' : 'services/';
	$request = trim( (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ), PHP_URL_PATH ), '/' );
	if ( ! str_starts_with( $request, $prefix ) ) { return; }
	$slug = substr( $request, strlen( $prefix ) );
	if ( ! $slug || str_contains( $slug, '/' ) ) { return; }
	$posts = get_posts( array( 'post_type' => 'service', 'post_status' => 'publish', 'name' => sanitize_title( $slug ), 'posts_per_page' => 2 ) );
	if ( 1 === count( $posts ) && $posts[0]->post_parent && wp_safe_redirect( get_permalink( $posts[0] ), 301, 'White Oaks service hierarchy' ) ) { exit; }
}
add_action( 'template_redirect', 'white_oaks_redirect_legacy_service', 9 );

function white_oaks_sanitize_related_services( $value ) {
	$ids = array_unique( array_filter( array_map( 'absint', is_array( $value ) ? $value : array() ) ) );
	return array_values( array_slice( array_filter( $ids, static function ( $id ) { return 'service' === get_post_type( $id ) && ! white_oaks_is_service_pillar( $id ); } ), 0, 3 ) );
}

/** Grouping is determined only by the native parent relationship. */
function white_oaks_service_parent( $post_id ) {
	$post = get_post( $post_id );
	$parent = $post && $post->post_parent ? get_post( $post->post_parent ) : null;
	return $parent && 'service' === $parent->post_type ? $parent : null;
}

function white_oaks_related_service_ids( $post_id ) {
	$selected = white_oaks_sanitize_related_services( get_post_meta( $post_id, '_white_oaks_related_services', true ) );
	$ids = array_values( array_filter( $selected, static function ( $id ) use ( $post_id ) { return $id !== $post_id && 'publish' === get_post_status( $id ); } ) );
	if ( count( $ids ) < 3 && get_post_meta( $post_id, '_white_oaks_related_fallback', true ) ) {
		$parent = white_oaks_service_parent( $post_id );
		if ( $parent ) {
			$fallback = get_posts( array( 'post_type' => 'service', 'post_status' => 'publish', 'posts_per_page' => 3 - count( $ids ), 'fields' => 'ids', 'post__not_in' => array_merge( array( $post_id ), $ids ), 'orderby' => array( 'menu_order' => 'ASC', 'title' => 'ASC', 'ID' => 'ASC' ), 'post_parent' => $parent->ID ) );
			$ids = array_merge( $ids, $fallback );
		}
	}
	return $ids;
}

add_filter( 'query_loop_block_query_vars', static function ( $query, $block ) {
	if ( 'white-oaks/pillar-services' === ( $block->context['query']['whiteOaksContext'] ?? '' ) ) {
		$query['post_type'] = 'service';
		$query['post_status'] = 'publish';
		$query['post_parent'] = get_queried_object_id();
		return $query;
	}
	if ( 'white-oaks/related-services' !== ( $block->context['query']['whiteOaksContext'] ?? '' ) ) { return $query; }
	$ids = white_oaks_related_service_ids( get_queried_object_id() );
	$query['post_type'] = 'service';
	$query['post_status'] = 'publish';
	$query['post__in'] = $ids ? $ids : array( 0 );
	$query['orderby'] = 'post__in';
	$query['posts_per_page'] = 3;
	return $query;
}, 10, 2 );

add_filter( 'render_block_core/group', static function ( $content, $block ) {
	if ( str_contains( $block['attrs']['className'] ?? '', 'service-related-section' ) && ! str_contains( $block['attrs']['className'] ?? '', 'related-services--with-placeholder' ) && ! white_oaks_related_service_ids( get_queried_object_id() ) ) { return ''; }
	return $content;
}, 20, 2 );

add_action( 'add_meta_boxes_service', static function () {
	add_meta_box( 'white-oaks-service-settings', __( 'Service settings', 'beanstalk-child' ), 'white_oaks_service_settings_box', 'service', 'side' );
} );

function white_oaks_service_settings_box( $post ) {
	wp_nonce_field( 'white_oaks_service_settings', 'white_oaks_service_nonce' );
	$selected = white_oaks_sanitize_related_services( get_post_meta( $post->ID, '_white_oaks_related_services', true ) );
	$services = get_posts( array( 'post_type' => 'service', 'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future' ), 'posts_per_page' => -1, 'post__not_in' => array( $post->ID ), 'orderby' => 'title', 'order' => 'ASC' ) );
	echo '<p>Choose up to three related services in display order. Only published services appear publicly.</p>';
	for ( $slot = 0; $slot < 3; $slot++ ) {
		printf( '<p><label for="wom-related-%1$d">Related service %2$d</label><br><select id="wom-related-%1$d" name="white_oaks_related_services[]"><option value="0">None</option>', $slot, $slot + 1 );
		foreach ( $services as $service ) {
			if ( white_oaks_is_service_pillar( $service->ID ) || ! current_user_can( 'edit_post', $service->ID ) ) { continue; }
			printf( '<option value="%d" %s>%s</option>', $service->ID, selected( $selected[ $slot ] ?? 0, $service->ID, false ), esc_html( $service->post_title ) );
		}
		echo '</select></p>';
	}
	printf( '<p><label><input type="checkbox" name="white_oaks_related_fallback" value="1" %s> Fill empty slots from sibling treatments</label></p>', checked( get_post_meta( $post->ID, '_white_oaks_related_fallback', true ), true, false ) );
	echo '<p>Choose the service group using the native Parent setting. Related-service fallback uses published treatments with the same parent.</p>';
	printf( '<p><label for="wom-service-type">Schema service type (optional)</label><br><input id="wom-service-type" name="white_oaks_service_type" value="%s"></p>', esc_attr( get_post_meta( $post->ID, '_white_oaks_service_type', true ) ) );
	echo '<p>The excerpt supplies the short card description and service schema description. The featured image supplies the related-service card image.</p>';
}

add_action( 'save_post_service', static function ( $post_id ) {
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) || ! current_user_can( 'edit_post', $post_id ) || ! isset( $_POST['white_oaks_service_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['white_oaks_service_nonce'] ) ), 'white_oaks_service_settings' ) ) { return; }
	update_post_meta( $post_id, '_white_oaks_related_services', array_values( array_diff( white_oaks_sanitize_related_services( wp_unslash( $_POST['white_oaks_related_services'] ?? array() ) ), array( $post_id ) ) ) );
	update_post_meta( $post_id, '_white_oaks_related_fallback', isset( $_POST['white_oaks_related_fallback'] ) );
	update_post_meta( $post_id, '_white_oaks_service_type', sanitize_text_field( wp_unslash( $_POST['white_oaks_service_type'] ?? '' ) ) );
} );

function white_oaks_service_directory() {
	$columns = array();
	foreach ( white_oaks_service_pillar_definitions() as $slug => $name ) {
		$parent = get_page_by_path( $slug, OBJECT, 'service' );
		if ( ! $parent ) { continue; }
		$posts = get_posts( array( 'post_type' => 'service', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => array( 'menu_order' => 'ASC', 'title' => 'ASC', 'ID' => 'ASC' ), 'post_parent' => $parent->ID ) );
		$items = '';
		foreach ( $posts as $post ) {
			$items .= '<!-- wp:list-item --><li><a href="' . esc_url( get_permalink( $post ) ) . '">' . esc_html( $post->post_title ) . '</a></li><!-- /wp:list-item -->';
		}
		if ( ! $items ) { continue; }
		$columns[] = '<!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3,"fontSize":"h-6","textColor":"primary-shade-600"} --><h3 class="wp-block-heading has-primary-shade-600-color has-text-color has-h-6-font-size">' . esc_html( $parent->post_title ) . '</h3><!-- /wp:heading --><!-- wp:list {"fontSize":"b-6"} --><ul class="wp-block-list has-b-6-font-size">' . $items . '</ul><!-- /wp:list --></div><!-- /wp:column -->';
	}
	if ( ! $columns ) { return ''; }
	return do_blocks( '<!-- wp:group {"align":"full","backgroundColor":"primary-tint-900","layout":{"type":"constrained"}} --><div class="wp-block-group alignfull has-primary-tint-900-background-color has-background"><!-- wp:group {"layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|32","bottom":"var:preset|spacing|32"}}}} --><div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--32);padding-bottom:var(--wp--preset--spacing--32)"><!-- wp:columns -->' . '<div class="wp-block-columns">' . implode( '', $columns ) . '</div><!-- /wp:columns --></div><!-- /wp:group --></div><!-- /wp:group -->' );
}
add_shortcode( 'white_oaks_service_directory', 'white_oaks_service_directory' );

/** Adds the Services grouping label without exposing a thin archive link. */
function white_oaks_service_breadcrumb_items( $crumbs ) {
	if ( ! is_singular( 'service' ) || count( $crumbs ) < 2 ) {
		return $crumbs;
	}
	$current = array_pop( $crumbs );
	$items = array( $crumbs[0] );
	$items[] = array( 'Services', '' );
	foreach ( array_reverse( get_post_ancestors( get_queried_object_id() ) ) as $ancestor_id ) {
		$items[] = array( get_the_title( $ancestor_id ), 'publish' === get_post_status( $ancestor_id ) ? get_permalink( $ancestor_id ) : '' );
	}
	$items[] = $current;
	return $items;
}
add_filter( 'rank_math/frontend/breadcrumb/items', 'white_oaks_service_breadcrumb_items', 20 );

/** Keep Services as a grouping label in every breadcrumb trail. */
function white_oaks_is_services_breadcrumb_url( $url ) {
	return untrailingslashit( home_url( '/services/' ) ) === untrailingslashit( $url );
}

function white_oaks_unlink_services_breadcrumb( $crumbs ) {
	foreach ( $crumbs as &$crumb ) {
		if ( white_oaks_is_services_breadcrumb_url( $crumb[1] ?? '' ) || 'services' === strtolower( trim( wp_strip_all_tags( $crumb[0] ) ) ) ) {
			$crumb[1] = '';
		}
	}
	unset( $crumb );
	return $crumbs;
}
add_filter( 'rank_math/frontend/breadcrumb/items', 'white_oaks_unlink_services_breadcrumb', 30 );

/** Apply the same grouping rule to editable core-block pillar breadcrumbs. */
function white_oaks_unlink_pillar_services_breadcrumb( $html, $block ) {
	$classes = preg_split( '/\s+/', $block['attrs']['className'] ?? '' );
	if ( ! in_array( 'service-pillar-hero__breadcrumbs', $classes, true ) ) {
		return $html;
	}
	return preg_replace_callback( '/<a\b[^>]*>.*?<\/a>/is', static function ( $match ) {
		$processor = new WP_HTML_Tag_Processor( $match[0] );
		if ( $processor->next_tag( 'A' ) && white_oaks_is_services_breadcrumb_url( $processor->get_attribute( 'href' ) ?? '' ) ) {
			return preg_replace( '/^<a\b[^>]*>(.*?)<\/a>$/is', '$1', $match[0] );
		}
		return $match[0];
	}, $html );
}
add_filter( 'render_block_core/group', 'white_oaks_unlink_pillar_services_breadcrumb', 20, 2 );

/** Rank Math marks all unlinked labels as last; reserve bold for the current page. */
function white_oaks_service_breadcrumb_html( $html ) {
	$processor = new WP_HTML_Tag_Processor( $html );
	$count     = 0;
	while ( $processor->next_tag( array( 'tag_name' => 'SPAN', 'class_name' => 'last' ) ) ) {
		++$count;
	}
	$processor = new WP_HTML_Tag_Processor( $html );
	$index     = 0;
	while ( $processor->next_tag( array( 'tag_name' => 'SPAN', 'class_name' => 'last' ) ) ) {
		++$index;
		if ( $index < $count ) {
			$processor->remove_class( 'last' );
			$processor->add_class( 'has-primary-shade-300-color' );
		} else {
			$processor->set_attribute( 'aria-current', 'page' );
		}
	}
	return $processor->get_updated_html();
}
add_filter( 'rank_math/frontend/breadcrumb/html', 'white_oaks_service_breadcrumb_html', 20 );
