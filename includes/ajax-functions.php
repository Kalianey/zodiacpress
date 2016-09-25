<?php
/**
 * AJAX Functions
 *
 * Process the front-end AJAX actions.
 *
 * @package     ZodiacPress
 * @subpackage  Functions/AJAX
 * @copyright   Copyright (c) 2016, Isabel Castillo
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Handles ajax request to calculate timezone offset and send back to form fields
 */
function zp_ajax_get_time_offset() {

	parse_str( $_POST['post_data'], $post_data );

	$offset_geo = null;

	$validated = zp_validate_form( $post_data, true );

	if ( ! is_array( $validated )  ) {

		// We have an error
	
		echo json_encode( array( 'error' => $validated ) );
		wp_die();

	}

	// get datetime stamp
	$dtstamp = strftime("%Y-%m-%d %H:%M:%S", mktime( $validated['hour'], $validated['minute'], 0, $validated['month'], $validated['day'], $validated['year'] ));

	// get time offset
	$offset_geo = $validated['geo_timezone_id'] ? zp_get_timezone_offset( $validated['geo_timezone_id'], $dtstamp ) : null;

	echo json_encode( array( 'offset_geo' => $offset_geo ) );
	wp_die();
}
add_action( 'wp_ajax_zp_tz_offset', 'zp_ajax_get_time_offset' );
add_action( 'wp_ajax_nopriv_zp_tz_offset', 'zp_ajax_get_time_offset' );

/**
 * Handles ajax request get the Birth Report upon form submission.
 */
function zp_ajax_get_birthreport() {
	$validated = zp_validate_form( $_POST );
	if ( ! is_array( $validated )  ) {
		echo json_encode( array( 'error' => $validated ) );
		wp_die();
	}
	$chart = ZP_Chart::get_instance( $validated );
	if ( empty( $chart->planets_longitude ) ) {
		$report = __('The Swiss Ephemeris is not working.', 'zodiacpress' );
	} else {
		$birth_report	= new ZP_Birth_Report( $chart, $validated );
		$report = wp_kses_post( $birth_report->get_report() );
	}
	$out = ( $report ) ? $report : __( 'Something went wrong. Please try again.', 'zodiacpress' );
	echo json_encode( array( 'report' => $out ) );
	wp_die();
}
add_action( 'wp_ajax_zp_birthreport', 'zp_ajax_get_birthreport' );
add_action( 'wp_ajax_nopriv_zp_birthreport', 'zp_ajax_get_birthreport' );
