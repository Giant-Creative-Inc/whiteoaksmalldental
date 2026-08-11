<?php
/**
 * Server rendering for the click-to-load map block.
 *
 * @package BeanstalkChild
 */

$location_name       = sanitize_text_field( $attributes['locationName'] ?? '' );
$address             = sanitize_text_field( $attributes['address'] ?? '' );
$maps_url            = esc_url( $attributes['mapsUrl'] ?? 'https://www.google.com/maps' );
$button_label        = sanitize_text_field( $attributes['buttonLabel'] ?? __( 'Use interactive map', 'beanstalk-child' ) );
$directions_label    = sanitize_text_field( $attributes['directionsLabel'] ?? __( 'Get directions', 'beanstalk-child' ) );
$image_id            = absint( $attributes['imageId'] ?? 0 );
$image_url           = esc_url( $attributes['imageUrl'] ?? '' );
$image_alt           = sanitize_text_field( $attributes['imageAlt'] ?? '' );
$map_query           = trim( $location_name . ', ' . $address, ' ,' );
$encoded_destination = rawurlencode( $map_query );
$apple_maps_url      = 'https://maps.apple.com/?daddr=' . $encoded_destination;
$google_maps_url     = 'https://www.google.com/maps/dir/?api=1&destination=' . $encoded_destination;
$waze_url            = 'https://www.waze.com/ul?q=' . $encoded_destination . '&navigate=yes';

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class'          => 'click-to-load-map',
		'data-map-query' => $map_query,
	)
);

$preview_image = '';
if ( $image_id ) {
	$preview_image = wp_get_attachment_image(
		$image_id,
		'full',
		false,
		array(
			'alt'     => $image_alt,
			'loading' => 'lazy',
		)
	);
} elseif ( $image_url ) {
	$preview_image = sprintf(
		'<img src="%1$s" alt="%2$s" loading="lazy">',
		esc_url( $image_url ),
		esc_attr( $image_alt )
	);
}
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<a class="click-to-load-map__preview" href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noreferrer noopener">
		<?php echo $preview_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built from escaped attachment or URL data. ?>
	</a>
	<div class="click-to-load-map__actions wp-block-buttons">
		<div class="wp-block-button is-style-primary">
			<a class="click-to-load-map__button wp-block-button__link wp-element-button" href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noreferrer noopener">
				<span><?php echo esc_html( $button_label ); ?></span>
			</a>
		</div>
		<details class="click-to-load-map__directions wp-block-button is-style-outline" data-map-directions>
			<summary class="click-to-load-map__directions-trigger wp-block-button__link wp-element-button">
				<span><?php echo esc_html( $directions_label ); ?></span>
			</summary>
			<div class="click-to-load-map__directions-menu">
				<a href="<?php echo esc_url( $apple_maps_url ); ?>" target="_blank" rel="noreferrer noopener"><?php esc_html_e( 'Apple Maps', 'beanstalk-child' ); ?></a>
				<a href="<?php echo esc_url( $google_maps_url ); ?>" target="_blank" rel="noreferrer noopener"><?php esc_html_e( 'Google Maps', 'beanstalk-child' ); ?></a>
				<a href="<?php echo esc_url( $waze_url ); ?>" target="_blank" rel="noreferrer noopener"><?php esc_html_e( 'Waze', 'beanstalk-child' ); ?></a>
			</div>
		</details>
	</div>
	<div class="click-to-load-map__loader" role="status" aria-live="polite">
		<span class="click-to-load-map__spinner" aria-hidden="true"></span>
		<span class="screen-reader-text"><?php esc_html_e( 'Loading interactive map', 'beanstalk-child' ); ?></span>
	</div>
</div>
