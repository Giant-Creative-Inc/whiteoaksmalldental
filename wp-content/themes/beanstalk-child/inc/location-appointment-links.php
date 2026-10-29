<?php
/**
 * Keep location-page booking calls to action beside the location's form.
 *
 * @package BeanstalkChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Routes booking links, including inherited header and dynamic service buttons.
 *
 * @param string $html Rendered block HTML.
 * @return string
 */
function white_oaks_location_appointment_links( $html ) {
	if ( ! is_page() || 'locations/white-oaks-mall' !== get_page_uri( get_queried_object_id() ) ) {
		return $html;
	}

	return preg_replace_callback(
		'/<a\b[^>]*>(.*?)<\/a>/is',
		static function ( $match ) {
			$label = trim( html_entity_decode( wp_strip_all_tags( $match[1] ), ENT_QUOTES, 'UTF-8' ) );
			if ( ! preg_match( '/^book\s+(?:(?:an?\s+)?appointment|a\s+visit)\b/i', $label ) ) {
				return $match[0];
			}

			$link = new WP_HTML_Tag_Processor( $match[0] );
			if ( ! $link->next_tag( 'A' ) ) {
				return $match[0];
			}

			$href = $link->get_attribute( 'href' );
			if ( ! is_string( $href ) || '/contact-us/' !== wp_parse_url( $href, PHP_URL_PATH ) ) {
				return $match[0];
			}
			$host = wp_parse_url( $href, PHP_URL_HOST );
			if ( $host && wp_parse_url( home_url(), PHP_URL_HOST ) !== $host ) {
				return $match[0];
			}

			$link->set_attribute( 'href', '#view-hours' );
			return $link->get_updated_html();
		},
		$html
	);
}
add_filter( 'render_block', 'white_oaks_location_appointment_links', 25 );
