<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Sanitizing allowed HTML in textarea 
 *
 * @since 1.0
 */
function zoom_sanitize_allowed_html( $value ) {
	
	return wp_kses_post( force_balance_tags( $value ) );

}


/**
 * Sanitizing Radio Buttons and Select Lists
 *
 * @since 1.0
 */
function zoom_sanitize_choices( $input, $setting ) {
	
	// Make sure to add * _control * suffix for each settings control name that use this sanitize
	
    $input = sanitize_key( $input );
    $choices = array();
	
	$control = $setting->manager->get_control( $setting->id.'_control' );
	$choices = $control->choices;
	
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );

}


/**
 * Sanitize the Multiple checkbox values.
 *
 * @param string $values Values.
 * @return array Checked values.
 */
function zoom_sanitize_multiple_checkbox( $values ) {

	$multi_values = ! is_array( $values ) ? explode( ',', $values ) : $values;

    return !empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
	
}


/**
 * Sanitize the Multiple checkbox values.
 *
 * @param string $values Values.
 * @return array Checked values.
 */
function zoom_sanitize_checkbox( $values ) {

	$values = filter_var($values, FILTER_SANITIZE_NUMBER_INT);
	
	return $values;
	
}