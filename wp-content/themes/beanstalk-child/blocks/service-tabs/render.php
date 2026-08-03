<?php
/**
 * Server rendering for the Service Tabs block.
 *
 * @package BeanstalkChild
 */

$items = array();

foreach ( $block->parsed_block['innerBlocks'] ?? array() as $inner_block ) {
	if ( 'white-oaks/service-tab' !== ( $inner_block['blockName'] ?? '' ) ) {
		continue;
	}

	$items[] = wp_parse_args(
		$inner_block['attrs'] ?? array(),
		array(
			'number'       => '',
			'mobileLabel'  => '',
			'desktopLabel' => '',
			'heading'      => '',
			'treatments'   => array(),
			'buttonLabel'  => '',
			'buttonUrl'    => '',
		)
	);
}

if ( empty( $items ) ) {
	return;
}

$style_path = get_stylesheet_directory() . '/blocks/service-tabs/build/style.min.css';
wp_enqueue_style(
	'white-oaks-service-tabs',
	get_stylesheet_directory_uri() . '/blocks/service-tabs/build/style.min.css',
	array(),
	file_exists( $style_path ) ? (string) filemtime( $style_path ) : wp_get_theme()->get( 'Version' )
);

$initial_active = min( max( 0, (int) ( $attributes['initialActive'] ?? 0 ) ), count( $items ) - 1 );
$heading_level  = min( 6, max( 2, (int) ( $attributes['headingLevel'] ?? 3 ) ) );
$instance_id    = sanitize_html_class( $attributes['instanceId'] ?? '' );

if ( empty( $instance_id ) ) {
	$instance_id = 'service-tabs-' . substr( md5( wp_json_encode( $items ) . wp_unique_id() ), 0, 10 );
}

$instance_id = wp_unique_id( $instance_id . '-' );

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class'               => 'white-oaks-service-tabs',
		'data-wp-interactive' => 'white-oaks/service-tabs',
		'data-wp-init'        => 'callbacks.init',
		'data-wp-context'     => wp_json_encode(
			array(
				'activeIndex' => $initial_active,
			)
		),
	)
);
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="white-oaks-service-tabs__tablist" role="tablist" aria-label="<?php esc_attr_e( 'Dental service categories', 'beanstalk-child' ); ?>">
		<?php foreach ( $items as $index => $item ) : ?>
			<?php
			$is_active = $initial_active === $index;
			$tab_id    = $instance_id . '-tab-' . $index;
			$panel_id  = $instance_id . '-panel-' . $index;
			?>
			<button
				class="white-oaks-service-tabs__tab"
				type="button"
				id="<?php echo esc_attr( $tab_id ); ?>"
				role="tab"
				aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
				aria-controls="<?php echo esc_attr( $panel_id ); ?>"
				tabindex="<?php echo $is_active ? '0' : '-1'; ?>"
				data-service-tab-index="<?php echo esc_attr( (string) $index ); ?>"
				data-wp-on--click="actions.activate"
				data-wp-on--keydown="actions.onKeydown"
			>
				<span class="white-oaks-service-tabs__tab-number" aria-hidden="true"><?php echo esc_html( $item['number'] ); ?></span>
				<span class="white-oaks-service-tabs__tab-label white-oaks-service-tabs__tab-label--desktop"><?php echo esc_html( $item['desktopLabel'] ); ?></span>
				<span class="white-oaks-service-tabs__tab-label white-oaks-service-tabs__tab-label--mobile"><?php echo esc_html( $item['mobileLabel'] ); ?></span>
				<span class="white-oaks-service-tabs__tab-indicator" aria-hidden="true">↗</span>
			</button>
		<?php endforeach; ?>
	</div>

	<div class="white-oaks-service-tabs__panels">
		<?php foreach ( $items as $index => $item ) : ?>
			<?php
			$tab_id   = $instance_id . '-tab-' . $index;
			$panel_id = $instance_id . '-panel-' . $index;
			?>
			<section
				class="white-oaks-service-tabs__panel"
				id="<?php echo esc_attr( $panel_id ); ?>"
				role="tabpanel"
				aria-labelledby="<?php echo esc_attr( $tab_id ); ?>"
				data-service-panel-index="<?php echo esc_attr( (string) $index ); ?>"
			>
				<div class="white-oaks-service-tabs__panel-main">
					<<?php echo tag_escape( 'h' . $heading_level ); ?> class="white-oaks-service-tabs__heading">
						<?php echo wp_kses_post( $item['heading'] ); ?>
					</<?php echo tag_escape( 'h' . $heading_level ); ?>>

					<a href="<?php echo esc_url( $item['buttonUrl'] ); ?>" class="wp-element-button white-oaks-service-tabs__appointment">
						<span><?php echo esc_html( $item['buttonLabel'] ); ?></span>
						<span aria-hidden="true">↗</span>
					</a>
				</div>

				<ul class="white-oaks-service-tabs__treatments">
					<?php foreach ( $item['treatments'] as $treatment ) : ?>
						<li>
							<a href="<?php echo esc_url( $treatment['url'] ?? '' ); ?>" class="white-oaks-service-tabs__treatment-link">
								<span class="white-oaks-service-tabs__star" aria-hidden="true">✦</span>
								<span><?php echo esc_html( $treatment['label'] ?? '' ); ?></span>
								<span class="white-oaks-service-tabs__link-arrow" aria-hidden="true">→</span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>

				<span class="white-oaks-service-tabs__decorative-number" aria-hidden="true"><?php echo esc_html( $item['number'] ); ?></span>
			</section>
		<?php endforeach; ?>
	</div>
</div>
