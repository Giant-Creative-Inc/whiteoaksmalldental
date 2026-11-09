<?php
/**
 * Add the shared width class to generated landing block wrappers.
 *
 * @package BeanstalkChild
 */

function white_oaks_render_landing_container( $html, $block ) {
	if ( 'ad-landing' !== get_page_template_slug() ) {
		return $html;
	}

	$inner_class = '';
	if ( 'white-oaks/patient-stories' === $block['blockName'] ) {
		$inner_class = 'patient-stories__inner';
	} elseif ( 'appointment-cta' === ( $block['attrs']['anchor'] ?? '' ) ) {
		$inner_class = 'wp-block-cover__inner-container';
	}

	if ( ! $inner_class ) {
		return $html;
	}

	$tags = new WP_HTML_Tag_Processor( $html );
	if ( $tags->next_tag( array( 'class_name' => $inner_class ) ) ) {
		$tags->add_class( 'landing-container-narrow' );
	}

	return $tags->get_updated_html();
}
add_filter( 'render_block_core/cover', 'white_oaks_render_landing_container', 10, 2 );
add_filter( 'render_block_white-oaks/patient-stories', 'white_oaks_render_landing_container', 10, 2 );
