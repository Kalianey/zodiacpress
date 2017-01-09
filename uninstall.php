<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package     ZodiacPress
 * @copyright   Copyright (c) 2016-2017, Isabel Castillo
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( is_multisite() ) {
	global $wpdb;
	$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
	if ( $blogs ) {
		foreach ( $blogs as $blog ) {
			switch_to_blog( $blog['blog_id'] );
			zp_uninstall();
			restore_current_blog();
		}
	}
}
else {
	zp_uninstall();
}

/**
 * Uninstall function.
 *
 * The uninstall function will only proceed if
 * the user explicitly asks for all data to be removed.
 *
 * @return void
 */
function zp_uninstall() {

	$options = get_option( 'zodiacpress_settings' );

	// Make sure that the user wants to remove all the data.
	if ( isset( $options['remove_data'] ) && '1' == $options['remove_data'] ) {

		$interpretations = array(
			'zp_natal_planets_in_signs',
			'zp_natal_planets_in_houses',
			'zp_natal_aspects_main',
			'zp_natal_aspects_moon',
			'zp_natal_aspects_mercury',
			'zp_natal_aspects_venus',
			'zp_natal_aspects_mars',
			'zp_natal_aspects_jupiter',
			'zp_natal_aspects_saturn',
			'zp_natal_aspects_uranus',
			'zp_natal_aspects_neptune',
			'zp_natal_aspects_pluto',
			'zp_natal_aspects_chiron',
			'zp_natal_aspects_lilith',
			'zp_natal_aspects_nn',
			'zp_natal_aspects_pof',
			'zp_natal_aspects_vertex',
			'zp_natal_aspects_asc',
			'zp_natal_aspects_mc'
			);


		delete_option( 'zodiacpress_settings' );

		foreach ( $interpretations as $interpretation ) {
			delete_option( $interpretation );
		}
		
		zp_remove_caps();
	}
}

/**
 * Remove zodiacpress capabilities
 */
function zp_remove_caps() {
	global $wp_roles;
	if ( class_exists( 'WP_Roles' ) ) {
		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}
	}
	if ( is_object( $wp_roles ) ) {
		$wp_roles->remove_cap( 'administrator', 'manage_zodiacpress_settings' );
		$wp_roles->remove_cap( 'administrator', 'manage_zodiacpress_interps' );
	}
}