<?php
/**
 * Scripts
 *
 * @package     ZodiacPress
 * @copyright   Copyright (c) 2016, Isabel Castillo
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register styles
 */
function zp_register_scripts() {

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	wp_register_style( 'zp', ZODIACPRESS_URL . 'assets/css/zp' . $suffix . '.css', array(), ZODIACPRESS_VERSION );
	// Styles for RTL languages
	wp_register_style( 'zp-rtl', ZODIACPRESS_URL . 'assets/css/zp-rtl' . $suffix . '.css', array(), ZODIACPRESS_VERSION );
	wp_register_script( 'zp', ZODIACPRESS_URL . 'assets/js/zp' . $suffix . '.js', array( 'jquery-ui-autocomplete', 'jquery' ) );

	// If language is other than English, get lang code to tranlsate Autocomplete cities.
	$wplang = get_locale();
	$langcode = substr( $wplang, 0, 2 );
	$city_list_lang = ( 'en' != $langcode ) ? $langcode : '';

	$data = array(
			'ajaxurl'		=> admin_url( 'admin-ajax.php' ),
			'selected'		=> __( 'Selected:', 'zodiacpress' ),
			'lat'			=> __( 'Latitude:', 'zodiacpress' ),
			'long'			=> __( 'Longitude:', 'zodiacpress' ),
			'utc'			=> __( 'UTC time offset:', 'zodiacpress' ),
			'lang'			=> $city_list_lang
		);
	wp_localize_script( 'zp', 'zp_ajax_object', $data );

}
	
add_action( 'wp_enqueue_scripts', 'zp_register_scripts' );

/**
 * Load admin-specific styles.
 */
function zp_load_admin_scripts() {

	if ( ! zp_is_admin_page() ) {
		return;
	}
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_register_style( 'zp-admin', ZODIACPRESS_URL . 'assets/css/zp-admin' . $suffix . '.css' );
	wp_enqueue_style( 'zp-admin' );
}
	
add_action( 'admin_enqueue_scripts', 'zp_load_admin_scripts', 100 );
