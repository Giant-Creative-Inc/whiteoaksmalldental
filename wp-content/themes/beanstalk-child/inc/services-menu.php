<?php
/** Scoped native service submenu accordion binding. @package BeanstalkChild */
defined( 'ABSPATH' ) || exit;

// Category headings navigate to pillars; core keeps a separate submenu toggle.
add_filter( 'render_block_context', static function ( $context, $block ) {
	$classes = preg_split( '/\s+/', $block['attrs']['className'] ?? '' );
	if ( 'core/navigation-submenu' === $block['blockName'] && in_array( 'services-menu__group', $classes, true ) ) {
		$context['submenuVisibility'] = 'hover';
		$context['openSubmenusOnClick'] = false;
		$context['showSubmenuIcon'] = true;
	}
	return $context;
}, 10, 2 );

add_filter( 'render_block_core/navigation', static function ( $html ) {
	if ( ! str_contains( $html, 'services-menu' ) ) {
		return $html;
	}
	$processor = new WP_HTML_Tag_Processor( $html );
	$scopes = array();
	while ( $processor->next_tag( array( 'tag_closers' => 'visit' ) ) ) {
		if ( 'LI' === $processor->get_tag() ) {
			if ( $processor->is_tag_closer() ) {
				array_pop( $scopes );
			} else {
				$scopes[] = (bool) end( $scopes ) || $processor->has_class( 'services-menu' );
				if ( $processor->has_class( 'services-menu' ) ) {
					$processor->set_attribute( 'data-wp-on--pointerenter', 'white-oaks/services-menu::actions.openDesktopHover' );
					$processor->set_attribute( 'data-wp-on--pointerleave', 'white-oaks/services-menu::actions.closeDesktopHover' );
				}
			}
		}
		if ( 'BUTTON' === $processor->get_tag() && ! $processor->is_tag_closer() && end( $scopes ) && $processor->has_class( 'wp-block-navigation-submenu__toggle' ) ) {
			$processor->set_attribute( 'data-wp-bind--aria-expanded', 'white-oaks/services-menu::state.isExpanded' );
		}
	}
	return $processor->get_updated_html();
}, 10, 2 );
