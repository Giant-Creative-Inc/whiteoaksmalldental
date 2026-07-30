<?php
/**
 * Responsive art direction for core Cover blocks.
 *
 * @package Beanstalk
 */

/**
 * Registers the responsive image attributes for core/cover on the server.
 *
 * @param array  $args       Block registration arguments.
 * @param string $block_type Block type name.
 * @return array
 */
function beanstalk_register_cover_attributes( $args, $block_type ) {
	if ( 'core/cover' !== $block_type ) {
		return $args;
	}

	$args['attributes']['tabletImage'] = array(
		'type' => 'object',
	);
	$args['attributes']['mobileImage'] = array(
		'type' => 'object',
	);

	return $args;
}
add_filter( 'register_block_type_args', 'beanstalk_register_cover_attributes', 10, 2 );

/**
 * Wraps a Cover image in a picture element when art-directed images are set.
 *
 * WordPress retains control of the fallback image's native loading,
 * fetch-priority, srcset, and sizes attributes.
 *
 * @param string $block_content Rendered block markup.
 * @param array  $block         Parsed block data.
 * @return string
 */
function beanstalk_cover_responsive_background( $block_content, $block ) {
	$tablet_id = isset( $block['attrs']['tabletImage']['id'] ) ? (int) $block['attrs']['tabletImage']['id'] : 0;
	$mobile_id = isset( $block['attrs']['mobileImage']['id'] ) ? (int) $block['attrs']['mobileImage']['id'] : 0;

	if ( ! $tablet_id && ! $mobile_id ) {
		return $block_content;
	}

	if ( ! preg_match( '/<img[^>]*wp-block-cover__image-background[^>]*>/', $block_content, $match ) ) {
		return $block_content;
	}

	$sources = '';

	if ( $mobile_id ) {
		$sources .= beanstalk_cover_picture_source( $mobile_id, '(max-width: 767px)' );
	}

	if ( $tablet_id ) {
		$sources .= beanstalk_cover_picture_source( $tablet_id, '(max-width: 1024px)' );
	}

	if ( '' === $sources ) {
		return $block_content;
	}

	$image   = $match[0];
	$picture = '<picture class="wp-block-cover__picture">' . $sources . $image . '</picture>';

	return str_replace( $image, $picture, $block_content );
}
add_filter( 'render_block_core/cover', 'beanstalk_cover_responsive_background', 10, 2 );

/**
 * Builds one responsive source element from a Media Library attachment.
 *
 * @param int    $attachment_id Media Library attachment ID.
 * @param string $media         Media query for the source.
 * @return string
 */
function beanstalk_cover_picture_source( $attachment_id, $media ) {
	$srcset = wp_get_attachment_image_srcset( $attachment_id, 'full' );

	if ( ! $srcset ) {
		$source_url = wp_get_attachment_image_url( $attachment_id, 'full' );

		if ( ! $source_url ) {
			return '';
		}

		$srcset = $source_url;
	}

	return sprintf(
		'<source media="%s" srcset="%s" sizes="100vw" />',
		esc_attr( $media ),
		esc_attr( $srcset )
	);
}
