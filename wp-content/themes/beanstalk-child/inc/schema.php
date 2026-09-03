<?php
/**
 * Admin-managed JSON-LD schema and sitewide foundation output.
 *
 * @package BeanstalkChild
 */

defined( 'ABSPATH' ) || exit;

const WHITE_OAKS_SCHEMA_META_KEY        = '_white_oaks_schema_jsonld';
const WHITE_OAKS_SCHEMA_LOG_META_KEY    = '_white_oaks_schema_change_log';
const WHITE_OAKS_SCHEMA_NONCE_ACTION    = 'white_oaks_save_schema';
const WHITE_OAKS_SCHEMA_NONCE_NAME      = 'white_oaks_schema_nonce';
const WHITE_OAKS_SCHEMA_ERROR_TRANSIENT = 'white_oaks_schema_error_';

/**
 * Registers revision-enabled schema metadata for posts and pages.
 *
 * @return void
 */
function white_oaks_register_schema_meta() {
	$schema_args = array(
		'type'              => 'string',
		'single'            => true,
		'default'           => '',
		'sanitize_callback' => 'white_oaks_sanitize_schema_meta',
		'auth_callback'     => static function ( $allowed, $meta_key, $post_id ) {
			return current_user_can( 'unfiltered_html' ) && current_user_can( 'edit_post', $post_id );
		},
		'show_in_rest'      => array(
			'schema' => array(
				'type' => 'string',
			),
		),
		'revisions_enabled' => true,
	);

	foreach ( array( 'page', 'post' ) as $post_type ) {
		register_post_meta( $post_type, WHITE_OAKS_SCHEMA_META_KEY, $schema_args );
	}
}
add_action( 'init', 'white_oaks_register_schema_meta' );

/**
 * Keeps only valid JSON objects containing an @context and @graph.
 *
 * Invalid manual saves are handled before metadata is updated. This callback
 * is a final safety net for REST or programmatic writes.
 *
 * @param mixed $value Proposed metadata value.
 * @return string
 */
function white_oaks_sanitize_schema_meta( $value ) {
	$value = is_string( $value ) ? trim( $value ) : '';

	if ( '' === $value ) {
		return '';
	}

	return white_oaks_schema_decode( $value ) ? $value : '';
}

/**
 * Decodes and validates a complete JSON-LD graph.
 *
 * @param string $json JSON-LD source.
 * @return array|null
 */
function white_oaks_schema_decode( $json ) {
	$data = json_decode( $json, true );

	if ( JSON_ERROR_NONE !== json_last_error() || ! is_array( $data ) ) {
		return null;
	}

	if ( 'https://schema.org' !== ( $data['@context'] ?? null ) || ! isset( $data['@graph'] ) || ! is_array( $data['@graph'] ) ) {
		return null;
	}

	return $data;
}

/**
 * Adds the schema editor to posts and pages for administrators.
 *
 * @return void
 */
function white_oaks_add_schema_meta_box() {
	if ( ! current_user_can( 'unfiltered_html' ) ) {
		return;
	}

	foreach ( array( 'page', 'post' ) as $post_type ) {
		add_meta_box(
			'white-oaks-schema',
			__( 'Custom JSON-LD Schema', 'beanstalk-child' ),
			'white_oaks_render_schema_meta_box',
			$post_type,
			'normal',
			'low'
		);
	}
}
add_action( 'add_meta_boxes', 'white_oaks_add_schema_meta_box' );

/**
 * Renders the schema editor and its admin audit history.
 *
 * @param WP_Post $post Current post.
 * @return void
 */
function white_oaks_render_schema_meta_box( $post ) {
	$schema = (string) get_post_meta( $post->ID, WHITE_OAKS_SCHEMA_META_KEY, true );
	$log    = get_post_meta( $post->ID, WHITE_OAKS_SCHEMA_LOG_META_KEY, true );
	$log    = is_array( $log ) ? array_reverse( array_slice( $log, -10 ) ) : array();

	wp_nonce_field( WHITE_OAKS_SCHEMA_NONCE_ACTION, WHITE_OAKS_SCHEMA_NONCE_NAME );
	?>
	<p><?php esc_html_e( 'Enter one complete JSON-LD object containing @context and @graph. Valid schema is server-rendered in wp_head; Rank Math JSON-LD is disabled on this item only.', 'beanstalk-child' ); ?></p>
	<textarea name="white_oaks_schema_jsonld" id="white-oaks-schema-jsonld" class="widefat code" rows="24" spellcheck="false"><?php echo esc_textarea( $schema ); ?></textarea>
	<p class="description"><?php esc_html_e( 'Deleting this value restores Rank Math schema output for the item. Previous values are retained in WordPress revisions.', 'beanstalk-child' ); ?></p>
	<?php if ( $log ) : ?>
		<details>
			<summary><strong><?php esc_html_e( 'Schema change history', 'beanstalk-child' ); ?></strong></summary>
			<ul>
				<?php foreach ( $log as $entry ) : ?>
					<li><?php echo esc_html( sprintf( '%1$s — %2$s — %3$s → %4$s', $entry['time'], $entry['user'], $entry['before'], $entry['after'] ) ); ?></li>
				<?php endforeach; ?>
			</ul>
		</details>
	<?php endif; ?>
	<?php
}

/**
 * Saves a validated schema graph and records the change in the admin.
 *
 * @param int $post_id Current post ID.
 * @return void
 */
function white_oaks_save_schema_meta( $post_id ) {
	if ( ! isset( $_POST[ WHITE_OAKS_SCHEMA_NONCE_NAME ] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ WHITE_OAKS_SCHEMA_NONCE_NAME ] ) ), WHITE_OAKS_SCHEMA_NONCE_ACTION ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) || ! current_user_can( 'unfiltered_html' ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$old_value = (string) get_post_meta( $post_id, WHITE_OAKS_SCHEMA_META_KEY, true );
	$new_value = isset( $_POST['white_oaks_schema_jsonld'] ) ? trim( wp_unslash( $_POST['white_oaks_schema_jsonld'] ) ) : '';

	if ( '' !== $new_value && ! white_oaks_schema_decode( $new_value ) ) {
		set_transient( WHITE_OAKS_SCHEMA_ERROR_TRANSIENT . get_current_user_id(), json_last_error_msg(), 60 );
		return;
	}

	if ( $old_value === $new_value ) {
		return;
	}

	if ( '' === $new_value ) {
		delete_post_meta( $post_id, WHITE_OAKS_SCHEMA_META_KEY );
	} else {
		update_post_meta( $post_id, WHITE_OAKS_SCHEMA_META_KEY, wp_slash( $new_value ) );
	}

	$user = wp_get_current_user();
	$log  = get_post_meta( $post_id, WHITE_OAKS_SCHEMA_LOG_META_KEY, true );
	$log  = is_array( $log ) ? $log : array();
	$log[] = array(
		'time'   => current_time( 'mysql' ),
		'user'   => $user->display_name,
		'before' => $old_value ? substr( hash( 'sha256', $old_value ), 0, 12 ) : 'empty',
		'after'  => $new_value ? substr( hash( 'sha256', $new_value ), 0, 12 ) : 'empty',
	);

	update_post_meta( $post_id, WHITE_OAKS_SCHEMA_LOG_META_KEY, array_slice( $log, -50 ) );
}
add_action( 'save_post_page', 'white_oaks_save_schema_meta' );
add_action( 'save_post_post', 'white_oaks_save_schema_meta' );

/**
 * Displays a validation failure without replacing the last valid graph.
 *
 * @return void
 */
function white_oaks_schema_admin_notice() {
	$key   = WHITE_OAKS_SCHEMA_ERROR_TRANSIENT . get_current_user_id();
	$error = get_transient( $key );

	if ( ! $error ) {
		return;
	}

	delete_transient( $key );
	printf(
		'<div class="notice notice-error"><p>%s</p></div>',
		esc_html( sprintf( __( 'Schema was not saved because the JSON-LD is invalid: %s', 'beanstalk-child' ), $error ) )
	);
}
add_action( 'admin_notices', 'white_oaks_schema_admin_notice' );

/**
 * Loads the controlled sitewide business foundation graph.
 *
 * @return array|null
 */
function white_oaks_schema_foundation() {
	$path = get_stylesheet_directory() . '/schema/foundation.json';

	if ( ! is_readable( $path ) ) {
		return null;
	}

	return white_oaks_schema_decode( (string) file_get_contents( $path ) );
}

/**
 * Returns the current post's valid admin-managed graph.
 *
 * @return array|null
 */
function white_oaks_schema_current_graph() {
	if ( ! is_singular() && ! is_front_page() ) {
		return null;
	}

	$post_id = (int) get_queried_object_id();
	$json    = $post_id ? (string) get_post_meta( $post_id, WHITE_OAKS_SCHEMA_META_KEY, true ) : '';

	return $json ? white_oaks_schema_decode( $json ) : null;
}

/**
 * Returns page-specific Speakable selectors confirmed against frontend markup.
 *
 * @return array
 */
function white_oaks_schema_speakable_selectors() {
	if ( is_front_page() ) {
		return array(
			'.homepage-hero__heading',
			'.homepage-hero__lead',
			'.services-section__header',
			'.white-oaks-service-tabs__treatments',
			'.home-faqs__list',
			'.patient-stories__heading',
			'.patient-stories__review-summary',
			'.patient-stories__quote',
		);
	}

	if ( is_page( 'invisalign' ) ) {
		return array(
			'.invisalign-hero',
			'.why-invisalign__header',
			'.why-invisalign__cards',
			'.first-visit__header',
			'.first-visit__steps',
			'.home-faqs__list',
		);
	}

	if ( is_page( 'contact-us' ) ) {
		return array(
			'.contact-section__hero h1',
			'.contact-section__hero-copy',
			'.contact-section__form-header',
			'.contact-section__clinic-card',
		);
	}

	return array();
}

/**
 * Adds page-specific Speakable markup using stable frontend selectors.
 *
 * @param array $graph Complete JSON-LD graph.
 * @return array
 */
function white_oaks_schema_add_speakable( $graph ) {
	$selectors = white_oaks_schema_speakable_selectors();

	if ( ! $selectors ) {
		return $graph;
	}

	foreach ( $graph['@graph'] as &$node ) {
		$types = isset( $node['@type'] ) ? (array) $node['@type'] : array();

		if ( in_array( 'WebPage', $types, true ) ) {
			$node['speakable'] = array(
				'@type'       => 'SpeakableSpecification',
				'cssSelector' => $selectors,
			);
			break;
		}
	}
	unset( $node );

	return $graph;
}

/**
 * Adds Speakable markup when Rank Math owns the current page graph.
 *
 * @param array $data Rank Math JSON-LD data.
 * @return array
 */
function white_oaks_add_rank_math_speakable( $data ) {
	$selectors = white_oaks_schema_speakable_selectors();

	if ( ! $selectors ) {
		return $data;
	}

	foreach ( $data as &$node ) {
		$types = isset( $node['@type'] ) ? (array) $node['@type'] : array();

		if ( in_array( 'WebPage', $types, true ) ) {
			$node['speakable'] = array(
				'@type'       => 'SpeakableSpecification',
				'cssSelector' => $selectors,
			);
			break;
		}
	}
	unset( $node );

	return $data;
}
add_filter( 'rank_math/json_ld', 'white_oaks_add_rank_math_speakable', 90 );

/**
 * Outputs controlled JSON-LD in server-rendered page source.
 *
 * @return void
 */
function white_oaks_output_schema() {
	if ( is_admin() || is_feed() || is_404() || is_search() || is_preview() ) {
		return;
	}

	$current_graph = white_oaks_schema_current_graph();
	$current_graph = $current_graph ? white_oaks_schema_add_speakable( $current_graph ) : null;
	$graphs        = array_filter( array( white_oaks_schema_foundation(), $current_graph ) );

	foreach ( $graphs as $graph ) {
		printf(
			"\n<script type=\"application/ld+json\" class=\"white-oaks-schema\">%s</script>\n",
			wp_json_encode( $graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT )
		);
	}
}
add_action( 'wp_head', 'white_oaks_output_schema', 20 );

/**
 * Prevents duplicate Rank Math entities when a valid managed graph is active.
 *
 * @param array $data Rank Math JSON-LD data.
 * @return array
 */
function white_oaks_disable_rank_math_schema( $data ) {
	return white_oaks_schema_current_graph() ? array() : $data;
}
add_filter( 'rank_math/json_ld', 'white_oaks_disable_rank_math_schema', 99 );
