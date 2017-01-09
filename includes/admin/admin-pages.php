<?php
/**
 * Admin Pages
 *
 * @package     ZodiacPress
 * @subpackage  Admin/Pages
 * @copyright   Copyright (c) 2016-2017, Isabel Castillo
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Creates the ZodiacPress admin pages
 */
function zp_add_admin_pages() {

	add_menu_page( __( 'ZodiacPress', 'zodiacpress' ), __( 'ZodiacPress', 'zodiacpress' ), 'manage_zodiacpress_interps', 'zodiacpress', 'zp_interpretations_page', 'dashicons-universal-access-alt', '21.9' );

	add_submenu_page( 'zodiacpress', __( 'ZodiacPress Tools', 'zodiacpress' ), __( 'Tools', 'zodiacpress' ), 'manage_zodiacpress_settings', 'zodiacpress-tools', 'zp_tools_page' );

	add_submenu_page( 'zodiacpress', __( 'ZodiacPress Settings', 'zodiacpress' ), __( 'Settings', 'zodiacpress' ), 'manage_zodiacpress_settings', 'zodiacpress-settings', 'zp_options_page' );

}
add_action( 'admin_menu', 'zp_add_admin_pages' );
/**
 * Determines whether the current page is a ZP admin page.
 */
function zp_is_admin_page() {
	global $pagenow;
	$page 			= isset( $_GET['page'] ) ? strtolower( sanitize_text_field( $_GET['page'] ) ) : false;
	$found			= false;
	$zp_pages 		= apply_filters( 'zp_admin_pages', array( 'zodiacpress', 'zodiacpress-settings', 'zodiacpress-tools' ) );

	if ( 'admin.php' == $pagenow && in_array( $page, $zp_pages ) ) {
		$found = true;
	}
	return $found;
}
