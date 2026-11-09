<?php
/**
 * Emergency landing page assets and call-first header.
 *
 * @package BeanstalkChild
 */

add_filter(
	'beanstalk_child_component_assets',
	static function ( $assets ) {
		$assets['emergency-landing'] = array(
			'markers'     => array( 'emergency-call-bar' ),
			'style'       => 'assets/css/build/components/emergency-landing.min.css',
			'script'      => 'assets/js/emergency-landing.js',
			'script_args' => array( 'in_footer' => true, 'strategy' => 'defer' ),
		);
		if ( is_page( 'emergency' ) ) {
			unset( $assets['accordions'] );
		}
		return $assets;
	}
);

add_filter(
	'render_block_core/button',
	static function ( $html, $block ) {
		if ( ! is_page( 'emergency' ) || ! str_contains( $block['attrs']['className'] ?? '', 'site-header__cta' ) ) {
			return $html;
		}

		$label = str_contains( $html, '#landing-appointment-form' ) ? 'Call (519) 686-6200' : 'Call Now';

		return '<div class="wp-block-button is-style-primary site-header__cta"><a class="wp-block-button__link wp-element-button" href="tel:+15196866200">' . esc_html( $label ) . '</a></div>';
	},
	10,
	2
);
