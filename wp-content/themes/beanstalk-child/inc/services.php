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
		'rewrite' => array( 'slug' => 'services', 'with_front' => false ),
		'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes' ),
		'taxonomies' => array( 'service_category' ),
		'template' => array( array( 'core/pattern', array( 'slug' => 'beanstalk-child/service-starter' ) ) ),
	) );
	register_taxonomy( 'service_category', 'service', array(
		'labels' => array( 'name' => __( 'Service Categories', 'beanstalk-child' ), 'singular_name' => __( 'Service Category', 'beanstalk-child' ) ),
		'hierarchical' => true,
		'public' => false,
		'show_ui' => true,
		'show_admin_column' => true,
		'show_in_rest' => true,
		'rewrite' => false,
	) );
	$fields = array(
		'_white_oaks_related_services' => array( 'type' => 'array', 'default' => array(), 'sanitize_callback' => 'white_oaks_sanitize_related_services', 'show_in_rest' => array( 'schema' => array( 'type' => 'array', 'items' => array( 'type' => 'integer' ) ) ) ),
		'_white_oaks_related_fallback' => array( 'type' => 'boolean', 'default' => true, 'sanitize_callback' => 'rest_sanitize_boolean', 'show_in_rest' => true ),
		'_white_oaks_primary_service_category' => array( 'type' => 'integer', 'default' => 0, 'sanitize_callback' => 'absint', 'show_in_rest' => true ),
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

function white_oaks_sanitize_related_services( $value ) {
	$ids = array_unique( array_filter( array_map( 'absint', is_array( $value ) ? $value : array() ) ) );
	return array_values( array_slice( array_filter( $ids, static function ( $id ) { return 'service' === get_post_type( $id ); } ), 0, 3 ) );
}

function white_oaks_primary_service_category( $post_id ) {
	$terms = wp_get_post_terms( $post_id, 'service_category', array( 'orderby' => 'term_id' ) );
	if ( is_wp_error( $terms ) || ! $terms ) { return null; }
	$primary = (int) get_post_meta( $post_id, '_white_oaks_primary_service_category', true );
	foreach ( $terms as $term ) { if ( $term->term_id === $primary ) { return $term; } }
	return $terms[0];
}

function white_oaks_related_service_ids( $post_id ) {
	$selected = white_oaks_sanitize_related_services( get_post_meta( $post_id, '_white_oaks_related_services', true ) );
	$ids = array_values( array_filter( $selected, static function ( $id ) use ( $post_id ) { return $id !== $post_id && 'publish' === get_post_status( $id ); } ) );
	if ( count( $ids ) < 3 && get_post_meta( $post_id, '_white_oaks_related_fallback', true ) ) {
		$term = white_oaks_primary_service_category( $post_id );
		if ( $term ) {
			$fallback = get_posts( array( 'post_type' => 'service', 'post_status' => 'publish', 'posts_per_page' => 3 - count( $ids ), 'fields' => 'ids', 'post__not_in' => array_merge( array( $post_id ), $ids ), 'orderby' => array( 'menu_order' => 'ASC', 'title' => 'ASC', 'ID' => 'ASC' ), 'tax_query' => array( array( 'taxonomy' => 'service_category', 'field' => 'term_id', 'terms' => $term->term_id ) ) ) );
			$ids = array_merge( $ids, $fallback );
		}
	}
	return $ids;
}

add_filter( 'query_loop_block_query_vars', static function ( $query, $block ) {
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
			if ( ! current_user_can( 'edit_post', $service->ID ) ) { continue; }
			printf( '<option value="%d" %s>%s</option>', $service->ID, selected( $selected[ $slot ] ?? 0, $service->ID, false ), esc_html( $service->post_title ) );
		}
		echo '</select></p>';
	}
	printf( '<p><label><input type="checkbox" name="white_oaks_related_fallback" value="1" %s> Fill empty slots from the primary category</label></p>', checked( get_post_meta( $post->ID, '_white_oaks_related_fallback', true ), true, false ) );
	echo '<p><label for="wom-primary-category">Primary category</label><br><select id="wom-primary-category" name="white_oaks_primary_service_category"><option value="0">First assigned category</option>';
	$terms = get_terms( array( 'taxonomy' => 'service_category', 'hide_empty' => false ) );
	if ( ! is_wp_error( $terms ) ) { foreach ( $terms as $term ) { printf( '<option value="%d" %s>%s</option>', $term->term_id, selected( get_post_meta( $post->ID, '_white_oaks_primary_service_category', true ), $term->term_id, false ), esc_html( $term->name ) ); } }
	echo '</select></p><p>The primary category must also be assigned under Service Categories.</p>';
	printf( '<p><label for="wom-service-type">Schema service type (optional)</label><br><input id="wom-service-type" name="white_oaks_service_type" value="%s"></p>', esc_attr( get_post_meta( $post->ID, '_white_oaks_service_type', true ) ) );
	echo '<p>The excerpt supplies the short card description and service schema description. The featured image supplies the related-service card image.</p>';
}

add_action( 'save_post_service', static function ( $post_id ) {
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) || ! current_user_can( 'edit_post', $post_id ) || ! isset( $_POST['white_oaks_service_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['white_oaks_service_nonce'] ) ), 'white_oaks_service_settings' ) ) { return; }
	update_post_meta( $post_id, '_white_oaks_related_services', array_values( array_diff( white_oaks_sanitize_related_services( wp_unslash( $_POST['white_oaks_related_services'] ?? array() ) ), array( $post_id ) ) ) );
	update_post_meta( $post_id, '_white_oaks_related_fallback', isset( $_POST['white_oaks_related_fallback'] ) );
	update_post_meta( $post_id, '_white_oaks_primary_service_category', absint( $_POST['white_oaks_primary_service_category'] ?? 0 ) );
	update_post_meta( $post_id, '_white_oaks_service_type', sanitize_text_field( wp_unslash( $_POST['white_oaks_service_type'] ?? '' ) ) );
} );

/** Category order is deliberate; directory membership follows primary category. */
function white_oaks_service_category_definitions() {
	return array( 'general-family-dentistry' => 'General & Family Dentistry', 'cosmetic-dentistry' => 'Cosmetic Dentistry', 'restorative-implant-dentistry' => 'Restorative & Implant Dentistry', 'emergency-surgical-dentistry' => 'Emergency & Surgical Dentistry' );
}

function white_oaks_service_directory() {
	$columns = array();
	foreach ( white_oaks_service_category_definitions() as $slug => $name ) {
		$term = get_term_by( 'slug', $slug, 'service_category' );
		if ( ! $term ) { continue; }
		$posts = get_posts( array( 'post_type' => 'service', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => array( 'menu_order' => 'ASC', 'title' => 'ASC', 'ID' => 'ASC' ), 'tax_query' => array( array( 'taxonomy' => 'service_category', 'field' => 'term_id', 'terms' => $term->term_id ) ) ) );
		$items = '';
		foreach ( $posts as $post ) {
			$primary = white_oaks_primary_service_category( $post->ID );
			if ( ! $primary || $primary->term_id !== $term->term_id ) { continue; }
			$items .= '<!-- wp:list-item --><li><a href="' . esc_url( get_permalink( $post ) ) . '">' . esc_html( $post->post_title ) . '</a></li><!-- /wp:list-item -->';
		}
		if ( ! $items ) { continue; }
		$columns[] = '<!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3,"fontSize":"h-6","textColor":"primary-shade-600"} --><h3 class="wp-block-heading has-primary-shade-600-color has-text-color has-h-6-font-size">' . esc_html( $term->name ) . '</h3><!-- /wp:heading --><!-- wp:list {"fontSize":"b-6"} --><ul class="wp-block-list has-b-6-font-size">' . $items . '</ul><!-- /wp:list --></div><!-- /wp:column -->';
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
	foreach ( $crumbs as &$crumb ) {
		if ( 'Services' === ( $crumb[0] ?? '' ) ) {
			$crumb[1] = '';
			unset( $crumb );
			return $crumbs;
		}
	}
	unset( $crumb );
	array_splice( $crumbs, count( $crumbs ) - 1, 0, array( array( 'Services', '' ) ) );
	return $crumbs;
}
add_filter( 'rank_math/frontend/breadcrumb/items', 'white_oaks_service_breadcrumb_items', 20 );

/** Rank Math marks all unlinked labels as last; reserve bold for the current page. */
function white_oaks_service_breadcrumb_html( $html ) {
	if ( ! is_singular( 'service' ) ) {
		return $html;
	}
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
