<?php
/**
 * Form Validation Function
 *
 * @package     ZodiacPress
 * @subpackage  Form
 * @copyright   Copyright (c) 2016-2017, Isabel Castillo
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Validate and sanitize the form data
 * @param array $data The form data
 * @param bool $partial Whether only partial data has been sent (for 1st Ajax request for timezone offset)
 * @return mixed|array|string Array of form values if all is valid, otherwise the error string
 */
function zp_validate_form( $data, $partial = false ) {

	$out			= $data;
	$month			= ( isset( $data['month'] ) && is_numeric( trim( $data['month'] ) ) ) ? $data['month'] : '';
	$day			= ( isset( $data['day'] ) && is_numeric( trim( $data['day'] ) ) ) ? trim( $data['day'] ) : '';
	$year			= ( isset( $data['year'] ) && is_numeric( trim( $data['year'] ) ) ) ? trim( $data['year'] ) : '';
	$hour			= ( isset( $data['hour'] ) && is_numeric( trim( $data['hour'] ) ) ) ? trim( $data['hour'] ) : '';
	$minute			= ( isset( $data['minute'] ) && is_numeric( trim( $data['minute'] ) ) ) ? trim( $data['minute'] ) : '';
	$timezone_id	= empty( $data['geo_timezone_id'] ) ? '' : sanitize_text_field( $data['geo_timezone_id'] );
	$place			= empty( $data['place'] ) ? '' : sanitize_text_field( $data['place'] );
	$latitude		= ( isset( $data['zp_lat_decimal'] ) && is_numeric( trim( $data['zp_lat_decimal'] ) ) ) ? trim( $data['zp_lat_decimal'] ) : '';
	$longitude		= ( isset( $data['zp_long_decimal'] ) && is_numeric( trim( $data['zp_long_decimal'] ) ) ) ? trim( $data['zp_long_decimal'] ) : '';
	$unknown_time	= isset( $data['unknown_time'] ) ? $data['unknown_time'] : '';
	$report_var		= empty( $data['zp-report-variation'] ) ? 'birthreport' : sanitize_text_field( $data['zp-report-variation'] );

	// Validate date.
	if ( "" == $month || "" == $day || "" == $year ) {

		return apply_filters( 'zp_form_error_notice_empty_date', __( 'Please select a Birth Date', 'zodiacpress' ) );

	} else {

		if ( ! $validdate = checkdate( $month, $day, $year ) ) {
			return __('Birth Date is not valid', 'zodiacpress');
		}

	}

	// If unknown time is checked, skip time validation and set time to noon.
	if ( ! empty( $unknown_time ) ) {
		$hour	= 12;
		$minute	= '00';
	} else {

		// Validate time.

		// Time values should be 2 characters
		if ( strlen( utf8_decode( $hour ) ) !== 2 || strlen( utf8_decode( $minute ) ) !== 2  ) {

			global $zodiacpress_options;
			$allow_unknown_bt_key = $report_var . '_allow_unknown_bt';

			if ( empty( $zodiacpress_options[ $allow_unknown_bt_key ] ) ||
				in_array( $report_var, apply_filters( 'zp_reports_require_birthtime', array() ) ) )
			{
				$msg = __( 'Please select a Birth Time', 'zodiacpress' );
			} else {
				$msg = __( 'Please select a Birth Time or check the box for unknown time', 'zodiacpress' );
			}

			return $msg;
		}

		if ( $hour < '00' || $hour > 23 ) {
			return __('Select a valid birth hour.', 'zodiacpress' );
		}

		if ( $minute < 0 || $minute > 59 ) {
			return __('Select a birth minute between 0 and 59.', 'zodiacpress' );
		}

	}

	// Validate location.
	if ( empty( $timezone_id ) || empty( $place ) || "" == $latitude || "" == $longitude ) {
		return __( 'Please select a Birth City', 'zodiacpress' );
	}

	// Update sanitized values.
	if ( is_array( $out ) ) {
		$out['month']				= $month;
		$out['day']					= $day;
		$out['year']				= $year;
		$out['hour']				= $hour;
		$out['minute']				= $minute;
		$out['geo_timezone_id']		= $timezone_id;
		$out['place']				= $place;
		$out['zp_lat_decimal']		= $latitude;
		$out['zp_long_decimal']		= $longitude;
		$out['unknown_time']		= $unknown_time;
		$out['zp-report-variation']	= $report_var;
	}

	// If this is a partial submission, we are done.
	if ( $partial ) {
		return $out;
	}

	// Validate the remaining fields (on full final submission)

	// Require name only if field is shown for this type of report.
	if ( apply_filters( 'zp_form_show_name_field', true, $report_var ) ) {
		if ( empty( $data['name'] ) ) {
			return __('Please enter a Name', 'zodiacpress');
		}
	}

	$name = ! empty( $data['name'] ) ? sanitize_text_field( $data['name'] ) : '';

	// Validate offset.

	$offset	= ( isset( $data['zp_offset_geo'] ) && is_numeric( sanitize_text_field( $data['zp_offset_geo'] ) ) ) ? sanitize_text_field( $data['zp_offset_geo'] ) : '';

	// trim decimal from end, just in case
	$offset	= trim( $offset, '.' );

	/*
	 * Offset must match:
	 * ^-?					Optional negative sign at the start
	 * [0-9]{1,2}			1 or 2 digits
	 * (\.[0-9]{1,2})?$ 	End with optional decimal point and 1 or 2 digits
	 *
	 */

	if ( ! preg_match( '/^-?[0-9]{1,2}(\.[0-9]{1,2})?$/', $offset ) ) {

		return __( 'UTC time offset must be a number (like 5). Include a negative sign or decimal point if needed (like -9.5). If you want the offset to be calculated automatically, select the Birth City again and click Next.', 'zodiacpress' );
	}

	// Validate the sidereal hidden field
	$sidereal = empty( $data['zp_report_sidereal'] ) ? false : sanitize_text_field( $data['zp_report_sidereal'] );
	if ( ! isset( zp_get_sidereal_methods()[ $sidereal ] ) ) {
		$sidereal = false;
	}

	// Validate the house system hidden field
	$house_system = empty( $data['zp_report_house_system'] ) ? false : sanitize_text_field( $data['zp_report_house_system'] );
	if ( ! isset( zp_get_house_systems()[ $house_system ] ) ) {
		$house_system = false;
	}

	// Update the sanitize values
	if ( is_array( $out ) ) {
		$out['zp_offset_geo']	= $offset;
		$out['name']			= $name;
		$out['sidereal']		= $sidereal;
		$out['house_system']	= $house_system;
	}
	
	return $out;
}