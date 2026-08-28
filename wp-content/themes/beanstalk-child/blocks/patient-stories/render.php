<?php
/**
 * Server rendering for the Patient Stories block.
 *
 * @package BeanstalkChild
 */

$stories = array_values(
	array_filter(
		$attributes['testimonials'] ?? array(),
		static fn( $story ) => ! empty( $story['quote'] )
	)
);

if ( empty( $stories ) ) {
	return;
}

$instance_id = wp_unique_id( 'patient-stories-' );
$total       = count( $stories );
$background  = sanitize_key( $attributes['backgroundColorSlug'] ?? 'light' );
$text_color  = sanitize_key( $attributes['textColorSlug'] ?? 'dark' );

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => sprintf( 'alignfull has-%1$s-background-color has-background has-%2$s-color has-text-color', $background, $text_color ),
	)
);
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="patient-stories__inner">
		<div class="patient-stories__summary">
			<p class="patient-stories__eyebrow has-primary-color has-text-color has-button-font-family has-b-6-font-size is-style-eyebrow"><strong><?php echo wp_kses_post( $attributes['eyebrow'] ?? '' ); ?></strong></p>
			<h2 class="patient-stories__heading has-heading-font-family has-patient-stories-heading-font-size"><?php echo wp_kses_post( $attributes['heading'] ?? '' ); ?></h2>

			<?php /* translators: 1: Numeric review rating. 2: Review summary, such as "Google reviews". */ ?>
			<div class="patient-stories__rating" aria-label="<?php echo esc_attr( sprintf( __( 'Rated %1$s. %2$s', 'beanstalk-child' ), wp_strip_all_tags( $attributes['rating'] ?? '' ), wp_strip_all_tags( $attributes['reviewSummary'] ?? '' ) ) ); ?>">
				<p class="patient-stories__rating-number has-heading-font-family has-patient-stories-rating-font-size"><?php echo wp_kses_post( $attributes['rating'] ?? '' ); ?></p>
				<div class="patient-stories__rating-copy">
					<p class="patient-stories__stars has-primary-color has-text-color has-button-font-family has-b-6-font-size" aria-hidden="true">★★★★★</p>
					<p class="patient-stories__review-summary has-button-font-family has-b-6-font-size"><strong><?php echo wp_kses_post( $attributes['reviewSummary'] ?? '' ); ?></strong></p>
				</div>
			</div>
		</div>

		<div class="patient-stories__card has-white-background-color has-background">
			<div class="patient-stories__slides" aria-live="polite" aria-atomic="true">
				<?php foreach ( $stories as $index => $story ) : ?>
					<article
						class="patient-stories__slide"
						id="<?php echo esc_attr( $instance_id . '-slide-' . $index ); ?>"
						tabindex="-1"
						data-patient-story-slide
						<?php echo 0 === $index ? '' : 'hidden'; ?>
					>
						<span class="patient-stories__quote-mark has-primary-color has-text-color has-heading-font-family" aria-hidden="true">“</span>
						<blockquote class="patient-stories__quote has-heading-font-family has-patient-stories-quote-font-size">
							<p><?php echo wp_kses_post( $story['quote'] ?? '' ); ?></p>
						</blockquote>
						<div class="patient-stories__slide-footer">
							<div class="patient-stories__reviewer">
								<p class="patient-stories__reviewer-name has-heading-font-family has-b-4-font-size"><strong><?php echo wp_kses_post( $story['reviewer'] ?? '' ); ?></strong></p>
								<p class="patient-stories__review-source has-primary-color has-text-color has-button-font-family has-patient-stories-source-font-size is-style-eyebrow"><strong><?php echo wp_kses_post( $story['source'] ?? '' ); ?></strong></p>
							</div>
							<div class="patient-stories__controls">
								<p class="patient-stories__counter has-primary-color has-text-color has-button-font-family has-b-6-font-size" data-patient-story-counter><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?> / <?php echo esc_html( str_pad( (string) $total, 2, '0', STR_PAD_LEFT ) ); ?></p>
								<div class="patient-stories__buttons">
									<button type="button" class="patient-stories__button patient-stories__button--previous" aria-label="<?php esc_attr_e( 'Previous testimonial', 'beanstalk-child' ); ?>" data-patient-story-previous <?php disabled( $total < 2 ); ?>><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5m7 7-7-7 7-7" /></svg></button>
									<button type="button" class="patient-stories__button patient-stories__button--next" aria-label="<?php esc_attr_e( 'Next testimonial', 'beanstalk-child' ); ?>" data-patient-story-next <?php disabled( $total < 2 ); ?>><svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14m-7-7 7 7-7 7" /></svg></button>
								</div>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
