<?php
/**
 * Share the clinic's Trustindex rating across the homepage and Invisalign landing page.
 *
 * @package BeanstalkChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Read the aggregate stored by the Google review plugin.
 *
 * @return array<string, string> Formatted rating and total, or an empty array.
 */
function white_oaks_landing_google_rating() {
	$details = get_option( 'trustindex-google-page-details', array() );
	if ( ! is_array( $details ) || 'White Oaks Mall Dental' !== trim( (string) ( $details['name'] ?? '' ), '"' ) ) {
		return array();
	}

	$score = filter_var( $details['rating_score'] ?? null, FILTER_VALIDATE_FLOAT );
	$total = filter_var( $details['rating_number'] ?? null, FILTER_VALIDATE_INT );
	if ( $score <= 0 || $score > 5 || $total <= 0 ) {
		return array();
	}

	return array(
		'rating' => number_format_i18n( $score, 1 ),
		'total'  => number_format_i18n( $total ),
	);
}

add_filter(
	'render_block_core/paragraph',
	static function ( $html, $block ) {
		$classes = explode( ' ', $block['attrs']['className'] ?? '' );
		if ( ! is_page( 1058 ) || ! in_array( 'landing-hero__rating', $classes, true ) ) {
			return $html;
		}

		$reviews = white_oaks_landing_google_rating();
		if ( ! $reviews ) {
			return $html;
		}

		/* translators: 1: Google rating, 2: total Google reviews. */
		$copy = sprintf( esc_html__( '%1$s rating from %2$s reviews.', 'beanstalk-child' ), '<strong>' . esc_html( $reviews['rating'] ) . '</strong>', '<strong>' . esc_html( $reviews['total'] ) . '</strong>' );
		return preg_replace_callback( '~(<p\b[^>]*>).*?(</p>)~s', static fn( $matches ) => $matches[1] . $copy . $matches[2], $html, 1 ) ?? $html;
	},
	10,
	2
);

add_filter(
	'render_block_data',
	static function ( $block ) {
		if ( 'white-oaks/patient-stories' !== $block['blockName'] || ( ! is_front_page() && ! is_page( 1058 ) ) ) {
			return $block;
		}

		$reviews = white_oaks_landing_google_rating();
		if ( $reviews ) {
			$block['attrs']['rating'] = $reviews['rating'];
			/* translators: %s: total Google reviews. */
			$block['attrs']['reviewSummary'] = sprintf( __( '%s Google reviews', 'beanstalk-child' ), $reviews['total'] );
		}

		return $block;
	}
);
