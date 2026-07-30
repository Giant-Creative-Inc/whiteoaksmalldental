<?php
/**
 * Beanstalk theme bootstrap.
 *
 * @package Beanstalk
 */

$beanstalk_autoloader = get_template_directory() . '/vendor/autoload.php';

if ( file_exists( $beanstalk_autoloader ) ) {
	require_once $beanstalk_autoloader;
}

require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/assets.php';
require_once get_template_directory() . '/inc/media.php';
require_once get_template_directory() . '/inc/patterns.php';
require_once get_template_directory() . '/inc/block-styles.php';
require_once get_template_directory() . '/inc/cover-responsive-images.php';
