<?php
/**
 * Misc Functions
 *
 * @package     ZodiacPress
 * @copyright   Copyright (c) 2016-2017, Isabel Castillo
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Returns an orderinal word for a number, or a list of number ordinal words
 * @param int $key the number of word to return
 * @return mixed|array|string If key is passed, a string for that ordinal word, otherwise array of ordinal words
 */
function zp_ordinal_word( $key = '' ) {

	$n = array( 
		__( 'Zeroth', 'zodiacpress' ),
		__( 'First', 'zodiacpress' ),
		__( 'Second', 'zodiacpress' ),
		__( 'Third', 'zodiacpress' ),
		__( 'Fourth', 'zodiacpress' ),
		__( 'Fifth', 'zodiacpress' ),
		__( 'Sixth', 'zodiacpress' ),
		__( 'Seventh', 'zodiacpress' ),
		__( 'Eighth', 'zodiacpress' ),
		__( 'Ninth', 'zodiacpress' ),
		__( 'Tenth', 'zodiacpress' ),
		__( 'Eleventh', 'zodiacpress' ),
		__( 'Twelfth', 'zodiacpress' ) );

	// if $key is passed, return only that number
	return ( $key !== '' && isset( $n[ $key ] ) ) ? $n[ $key ] : $n;
}
/**
 * Checks if a function is enabled (not disabled).
 * @param string  $function Name of the function.
 * @return bool false if disabled, otherwise true
 */
function zp_is_func_enabled( $function ) {

	if ( function_exists( $function ) &&
		// AND NOT in the array of disabled functions
		! in_array( $function, array_map( 'trim', explode( ', ', ini_get( 'disable_functions' ) ) ) ) &&
		// AND NOT in safe mode
		ini_get( 'safe_mode' ) != 1
	) {
		return true;
	} else {
		return false;
	}
}
/**
 * Get File Extension
 *
 * Returns the file extension of a filename.
 *
 * @param unknown $str File name
 *
 * @return mixed File extension
 */
function zp_get_file_extension( $str ) {
	$parts = explode( '.', $str );
	return end( $parts );
}
/**
 * Convert an object to an associative array.
 *
 * Can handle multidimensional arrays
 *
 * @param unknown $data
 * @return array
 */
function zp_object_to_array( $data ) {
	if ( is_array( $data ) || is_object( $data ) ) {
		$result = array();
		foreach ( $data as $key => $value ) {
			$result[ $key ] = zp_object_to_array( $value );
		}
		return $result;
	}
	return $data;
}

/**
 * Returns a list of all possible Interpretations db option names, not just enabled ones.
 *
 * They're held across several database options due to large size.
 * 
 * @return array
 */
function zp_get_all_interps_options_names() {

	$option_names = array();

	// Large Interps Tabs get a separate option per section due to large size
	$large_tabs = apply_filters( 'zp_large_tabs_separate_options', array( 'natal_aspects' ) );

	foreach ( zp_get_interps_tabs() as $tab => $label ) {

		// Tabs that hold large content (i.e. Aspects) get a db option for each section
		if ( in_array( $tab, $large_tabs ) ) {

			foreach ( zp_get_planets() as $planet ) {
				$section = ( 'sun' == $planet['id'] ) ? 'main' : $planet['id'];
				$option_names[] = 'zp_' . $tab . '_' . $section;
				
			}
			
		} else {
			$option_names[] = 'zp_' . $tab;
		}
	}

	return $option_names;
}

/**
 * Returns a list of enabled Interpretations db option names.
 *
 * @return array
 */
function zp_get_enabled_interps_options_names() {

	$option_names = array();
	foreach ( zp_get_enabled_interps_sections() as $tab => $sections ) {
		// go through each section on each tab and get option name
		foreach( $sections as $section => $label ) {
			$option_names[] = zp_get_interps_option_name( $tab, $section );
		}

	}
	return array_unique( $option_names );
}

/**
 * Checks if the Ephemeris has the required file permissions.
 *
 * Attemps to set the proper permission.
 *
 * @return bool true if permission is (or gets set to) 0755, otherwise false
 */
function zp_is_sweph_executable() {

	$out			= true;
	$file			= ZODIACPRESS_PATH . 'sweph/swetest';
	$permissions	= substr( sprintf( '%o', fileperms( $file ) ), -4 );

	if ( '0755' !== $permissions ) {

		if ( zp_is_func_enabled( 'chmod' ) ) {

			// Attempt to change permission.
			$change = chmod( $file, 0755 );
			
			if ( ! $change ) {
				$out = false;
				add_action( 'admin_notices', 'zp_admin_notices_chmod_failed' );
			}
		} else {
			$out = false;
			add_action( 'admin_notices', 'zp_admin_notices_chmod_failed' );
		}
	}

	return $out;
}

/**
 * Check if web server operating system is Windows
 */
function zp_is_server_windows() {
	return ( strtolower( PHP_SHLIB_SUFFIX ) === 'dll' ) ? true : false;
}

/**
 * Search a column of a multidimentsional array for a specific value and return the key.
 *
 * Similar to array_column()
 */
function zp_search_array( $value, $column, $array ) {
   foreach ( $array as $k => $val ) {
       if ( $val[ $column ] == $value ) {
           return $k;
       }
   }
   return null;
}

/**
 * Registers new cron schedule
 *
 * @param array $schedules
 * @return array
 */
add_filter( 'cron_schedules', 'zp_add_cron_schedule' );

function zp_add_cron_schedule( $schedules = array() ) {
	
	// Adds once weekly to the existing schedules.
	$schedules['weekly'] = array(
		'interval' => 604800,
		'display'  => __( 'Once Weekly', 'zodiacpress' )
	);
	return $schedules;
}

/**
 * Schedule weekly event to check ZP extension licenses
 *
 * @return void
 */
add_action( 'wp', 'zp_weekly_events' );

function zp_weekly_events() {
	if ( ! wp_next_scheduled( 'zp_weekly_scheduled_events' ) ) {
		wp_schedule_event( current_time( 'timestamp' ), 'weekly', 'zp_weekly_scheduled_events' );
	}
}
