<?php
/**
 * Gravity Forms phone validation and first-touch attribution.
 *
 * @package BeanstalkChild
 */

/**
 * Returns the configured Gravity Forms field map.
 *
 * @return array<int,array<string,int>>
 */
function white_oaks_gravity_forms_field_map() {
	return array(
		1 => array(
			'phone'          => 4,
			'utm_source'     => 8,
			'utm_medium'     => 9,
			'utm_campaign'   => 10,
			'utm_term'       => 11,
			'utm_content'    => 12,
			'gclid'          => 14,
			'fbclid'         => 15,
			'gbraid'         => 30,
			'wbraid'         => 31,
			'msclkid'        => 32,
			'landing_page'   => 33,
			'referrer'       => 34,
			'form_timestamp' => 35,
		),
	);
}

/**
 * Allows the configured client-populated attribution values through Gravity
 * Forms 3.0 state validation.
 *
 * Gravity Forms state validation otherwise treats any JavaScript change to a
 * Hidden field as tampering. This exception is restricted to Form 1's known
 * attribution fields; all other fields retain their default state validation.
 *
 * @param array $form Gravity Forms form object.
 * @return array
 */
function white_oaks_allow_client_attribution_values( $form ) {
	$field_map       = white_oaks_gravity_forms_field_map()[1];
	$attribution_ids = array_map( 'intval', array_values( array_diff_key( $field_map, array( 'phone' => true ) ) ) );

	foreach ( $form['fields'] as $field ) {
		if ( in_array( (int) $field->id, $attribution_ids, true ) ) {
			$field->validateState = false;
		}
	}

	return $form;
}
add_filter( 'gform_pre_render_1', 'white_oaks_allow_client_attribution_values' );
add_filter( 'gform_pre_validation_1', 'white_oaks_allow_client_attribution_values' );
add_filter( 'gform_pre_submission_filter_1', 'white_oaks_allow_client_attribution_values' );

/**
 * Enqueues attribution capture and configured form behaviour only where a
 * configured Gravity Form is rendered.
 *
 * @return void
 */
function white_oaks_enqueue_form_attribution() {
	$content = white_oaks_current_page_content();

	if (
		'' === $content ||
		(
			! str_contains( $content, 'gravityforms/form' ) &&
			! str_contains( $content, '[gravityform' ) &&
			! str_contains( $content, 'contact-section__form-embed' )
		)
	) {
		return;
	}

	$relative_path = '/assets/js/gravity-forms-attribution.js';
	$handle        = 'white-oaks-form-attribution';
	$field_map     = white_oaks_gravity_forms_field_map();

	wp_enqueue_script(
		$handle,
		get_stylesheet_directory_uri() . $relative_path,
		array(),
		beanstalk_child_asset_version( $relative_path ),
		true
	);

	wp_localize_script(
		$handle,
		'whiteOaksFormAttribution',
		array(
			'cookieName' => 'white_oaks_attrib',
			'cookieDays' => 90,
			'forms'      => $field_map,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'white_oaks_enqueue_form_attribution', 30 );

/**
 * Validates the optional North American phone field on Gravity Form 1.
 *
 * Gravity Forms continues to enforce whether the field is required. This
 * callback validates only non-empty values.
 *
 * @param array  $result Validation result.
 * @param string $value  Submitted field value.
 * @return array
 */
function white_oaks_validate_contact_phone( $result, $value ) {
	$value = trim( (string) $value );
	$phone = json_decode( $value, true );

	if ( is_array( $phone ) && isset( $phone['formatted'] ) ) {
		$value = trim( (string) $phone['formatted'] );
	}

	if ( '' !== $value && ! preg_match( '/^(?:\(\d{3}\) \d{3}-\d{4}|1 \(\d{3}\) \d{3}-\d{4})$/', $value ) ) {
		$result['is_valid'] = false;
		$result['message']  = esc_html__( 'Please enter a valid phone number, for example (555) 123-4567.', 'beanstalk-child' );
	}

	return $result;
}
add_filter( 'gform_field_validation_1_4', 'white_oaks_validate_contact_phone', 10, 2 );

/**
 * Uses a plain telephone input for the configured North American phone field.
 *
 * Gravity Forms' formatted phone component owns and serializes its visible
 * input, which conflicts with the required 10/11-digit formatter. The field
 * remains a Gravity Forms Phone field and retains its normal label, entry value,
 * validation message, and submission name.
 *
 * @param string   $content Existing field content.
 * @param GF_Field $field   Gravity Forms field object.
 * @return string
 */
function white_oaks_render_contact_phone_input( $content, $field ) {
	if ( 4 !== (int) $field->id ) {
		return $content;
	}

	$value = rgpost( 'input_4' );
	$error = $field->failed_validation
		? sprintf(
			'<div id="validation_message_1_4" class="gfield_description validation_message gfield_validation_message">%s</div>',
			esc_html( $field->validation_message )
		)
		: '';

	return sprintf(
		'<label class="gfield_label gform-field-label" for="input_1_4">%1$s</label><div class="ginput_container ginput_container_phone"><input name="input_4" id="input_1_4" type="tel" value="%2$s" class="large" autocomplete="tel" inputmode="numeric" aria-invalid="%3$s"%4$s></div>%5$s',
		esc_html( $field->label ),
		esc_attr( is_scalar( $value ) ? (string) $value : '' ),
		$field->failed_validation ? 'true' : 'false',
		$field->failed_validation ? ' aria-describedby="validation_message_1_4"' : '',
		$error
	);
}
add_filter( 'gform_field_content_1_4', 'white_oaks_render_contact_phone_input', 10, 2 );
