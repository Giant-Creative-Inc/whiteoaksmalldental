<?php
/**
 * Registers client-specific blocks.
 *
 * @package BeanstalkChild
 */

/**
 * Register White Oaks Dental blocks from block metadata.
 *
 * @return void
 */
function white_oaks_register_blocks() {
	register_block_type( get_stylesheet_directory() . '/blocks/service-tabs' );
	register_block_type( get_stylesheet_directory() . '/blocks/service-tabs/service-tab' );
	register_block_type( get_stylesheet_directory() . '/blocks/patient-stories' );
	register_block_type( get_stylesheet_directory() . '/blocks/click-to-load-map' );
}
add_action( 'init', 'white_oaks_register_blocks' );
