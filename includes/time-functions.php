<?php
/**
 * Time Functions
 *
 * @package     ZodiacPress
 * @copyright   Copyright (c) 2016-2017, Isabel Castillo
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
if ( ! defined( 'ABSPATH' ) ) exit;
 /** 
 * Get time offset from UTC for a designated datetime & timezone identifier.
 *
 * Can backtrack for old Daylight Savings rules (at least back to 1970, give or take some errors--this is why we allow visitors to override the offset on the form).
 *
 * @param $timezone_id, Timezone name (or GeoNames timezone ID)
 * @param $datetime, Time stamp string 'YYYY-MM-DD HH:MM'
 * @return mixed Returns offset in hours, or FALSE in case of bad parameters.
 */
function zp_get_timezone_offset( $timezone_id, $datetime ) {

	$dt = new DateTime( $datetime, new DateTimeZone( $timezone_id ) );
	$offset_seconds = $dt->getOffset();
	$out = ( false === $offset_seconds ) ? false : $offset_seconds/3600;

	return $out;
}

/**
 * Convert the just the hour of a time from 24-hour format into 12-hour format
 * @param $hour mixed Hour in 24-hour format with leading zero, 00 - 23
 * @return string The hour in 12 format (g a), as in "3 pm".
 */
function zp_get_12_hour( $hour = '00' ) {

	if ( $hour < 12  ) {

		if ( '00' == $hour ) {
			$out = sprintf( _x ( '%s am, midn', 'time meridiem midnight', 'zodiacpress' ),
			zp_i18n_numbers( '12' ) );
		} else {
			$h = (int) $hour;// strip leading zero
			$out = sprintf( _x ( '%s am', 'time meridiem', 'zodiacpress' ),
			zp_i18n_numbers( $h ) );			
		}

	} elseif( '12' == $hour ) {
		$out = sprintf( _x ( '%s pm, noon', 'time meridiem noon', 'zodiacpress' ),
			zp_i18n_numbers( '12' ) );		
	} else {
		
		$h = $hour - 12;
		(int) $h;

		$out = sprintf( _x ( '%s pm', 'time meridiem', 'zodiacpress' ),
			zp_i18n_numbers( $h ) );
	}

	return $out;
}

/**
 * Detect if the Date Format set in WP settings has month before day.
 * @return bool true if month is before day, otherwise false.
 */
function zp_is_month_before_day() {

	$out = false;

	// some possible separators in the date format string, besides a 'space'
	$sep 					= array( ',', '-', '/', '.', ':' );
	
	$date_format			= get_option( 'date_format' );
	$date_format_elements 	= array_filter( explode( ' ', str_replace( $sep, ' ', $date_format ) ) );
	$date_format_elements 	= array_values( $date_format_elements );
	$month_formats 			= array( 'm', 'n', 'F', 'M' );
	$day_formats 			= array( 'd', 'j', 'dS', 'jS');

	// Is the 1st element a month?
	if ( in_array( $date_format_elements[0], $month_formats ) ) {
		$out = true;
	} else {

		if ( ! in_array( $date_format_elements[0], $day_formats ) ) {

			// 1st element is neither month nor day. Check the 2nd element if there is one.
			if ( ! empty( $date_format_elements[1] ) && in_array( $date_format_elements[1], $month_formats ) ) {
				$out = true;
			}
			
			// Month is first for ISO 8601
			if ( 'c' == $date_format_elements[0] ) {
				$out = true;
			}
			
		}
	
	}

	return apply_filters( 'zp_is_month_before_day', $out );
}

/**
 * Returns internationalized months.
 * @param int $key The month to return
 * @return mixed|array|string If key is passed, a string for that month, otherwise array of all months
 */
function zp_get_i18n_months( $key = '' ) {
	$months = array(
		'1'		=> __( 'January', 'zodiacpress' ),
		'2'		=> __( 'February', 'zodiacpress' ),
		'3'		=> __( 'March', 'zodiacpress' ),
		'4'		=> __( 'April', 'zodiacpress' ),
		'5'		=> __( 'May', 'zodiacpress' ),
		'6'		=> __( 'June', 'zodiacpress' ),
		'7'		=> __( 'July', 'zodiacpress' ),
		'8'		=> __( 'August', 'zodiacpress' ),
		'9'		=> __( 'September', 'zodiacpress' ),
		'10'	=> __( 'October', 'zodiacpress' ),
		'11'	=> __( 'November', 'zodiacpress' ),
		'12'	=> __( 'December', 'zodiacpress' )
	);

	// if $key is passed, return only that month
	return ( $key && isset( $months[ $key ] ) ) ? $months[ $key ] : $months;
}


/**
 * Returns internationalized years from 1900+
 * @param int $key The year to return (offset by -1900, so 1 returns 1901, 2 returns 1902)
 * @return mixed|array|string If key is passed, a string for that year (key+1900) if it exists, otherwise array of all years 
 */
function zp_i18n_years( $key = '' ) {
	$years = array(
		__( '1900', 'zodiacpress' ), __( '1901', 'zodiacpress' ), __( '1902', 'zodiacpress' ), __( '1903', 'zodiacpress' ), __( '1904', 'zodiacpress' ), __( '1905', 'zodiacpress' ), __( '1906', 'zodiacpress' ), __( '1907', 'zodiacpress' ), __( '1908', 'zodiacpress' ), __( '1909', 'zodiacpress' ), __( '1910', 'zodiacpress' ), __( '1911', 'zodiacpress' ), __( '1912', 'zodiacpress' ), __( '1913', 'zodiacpress' ), __( '1914', 'zodiacpress' ), __( '1915', 'zodiacpress' ), __( '1916', 'zodiacpress' ), __( '1917', 'zodiacpress' ), __( '1918', 'zodiacpress' ), __( '1919', 'zodiacpress' ), __( '1920', 'zodiacpress' ), __( '1921', 'zodiacpress' ), __( '1922', 'zodiacpress' ), __( '1923', 'zodiacpress' ), __( '1924', 'zodiacpress' ), __( '1925', 'zodiacpress' ), __( '1926', 'zodiacpress' ), __( '1927', 'zodiacpress' ), __( '1928', 'zodiacpress' ), __( '1929', 'zodiacpress' ), __( '1930', 'zodiacpress' ), __( '1931', 'zodiacpress' ), __( '1932', 'zodiacpress' ), __( '1933', 'zodiacpress' ), __( '1934', 'zodiacpress' ), __( '1935', 'zodiacpress' ), __( '1936', 'zodiacpress' ), __( '1937', 'zodiacpress' ), __( '1938', 'zodiacpress' ), __( '1939', 'zodiacpress' ), __( '1940', 'zodiacpress' ), __( '1941', 'zodiacpress' ), __( '1942', 'zodiacpress' ), __( '1943', 'zodiacpress' ), __( '1944', 'zodiacpress' ), __( '1945', 'zodiacpress' ), __( '1946', 'zodiacpress' ), __( '1947', 'zodiacpress' ), __( '1948', 'zodiacpress' ), __( '1949', 'zodiacpress' ), __( '1950', 'zodiacpress' ), __( '1951', 'zodiacpress' ), __( '1952', 'zodiacpress' ), __( '1953', 'zodiacpress' ), __( '1954', 'zodiacpress' ), __( '1955', 'zodiacpress' ), __( '1956', 'zodiacpress' ), __( '1957', 'zodiacpress' ), __( '1958', 'zodiacpress' ), __( '1959', 'zodiacpress' ), __( '1960', 'zodiacpress' ), __( '1961', 'zodiacpress' ), __( '1962', 'zodiacpress' ), __( '1963', 'zodiacpress' ), __( '1964', 'zodiacpress' ), __( '1965', 'zodiacpress' ), __( '1966', 'zodiacpress' ), __( '1967', 'zodiacpress' ), __( '1968', 'zodiacpress' ), __( '1969', 'zodiacpress' ), __( '1970', 'zodiacpress' ), __( '1971', 'zodiacpress' ), __( '1972', 'zodiacpress' ), __( '1973', 'zodiacpress' ), __( '1974', 'zodiacpress' ), __( '1975', 'zodiacpress' ), __( '1976', 'zodiacpress' ), __( '1977', 'zodiacpress' ), __( '1978', 'zodiacpress' ), __( '1979', 'zodiacpress' ), __( '1980', 'zodiacpress' ), __( '1981', 'zodiacpress' ), __( '1982', 'zodiacpress' ), __( '1983', 'zodiacpress' ), __( '1984', 'zodiacpress' ), __( '1985', 'zodiacpress' ), __( '1986', 'zodiacpress' ), __( '1987', 'zodiacpress' ), __( '1988', 'zodiacpress' ), __( '1989', 'zodiacpress' ), __( '1990', 'zodiacpress' ), __( '1991', 'zodiacpress' ), __( '1992', 'zodiacpress' ), __( '1993', 'zodiacpress' ), __( '1994', 'zodiacpress' ), __( '1995', 'zodiacpress' ), __( '1996', 'zodiacpress' ), __( '1997', 'zodiacpress' ), __( '1998', 'zodiacpress' ), __( '1999', 'zodiacpress' ), __( '2000', 'zodiacpress' ), __( '2001', 'zodiacpress' ), __( '2002', 'zodiacpress' ), __( '2003', 'zodiacpress' ), __( '2004', 'zodiacpress' ), __( '2005', 'zodiacpress' ), __( '2006', 'zodiacpress' ), __( '2007', 'zodiacpress' ), __( '2008', 'zodiacpress' ), __( '2009', 'zodiacpress' ), __( '2010', 'zodiacpress' ), __( '2011', 'zodiacpress' ), __( '2012', 'zodiacpress' ), __( '2013', 'zodiacpress' ), __( '2014', 'zodiacpress' ), __( '2015', 'zodiacpress' ), __( '2016', 'zodiacpress' ), __( '2017', 'zodiacpress' ), __( '2018', 'zodiacpress' ) );

	return ( $key !== '' && isset( $years[ $key ] ) ) ? $years[ $key ] : $years;
}

/**
 * Returns internationalized numbers from 1 - 31 without leading zeros.
 * @param int $key The number to return
 * @return mixed|array|string If key is passed, a string for that number, otherwise array of numbers
 */
function zp_i18n_numbers( $key = '' ) {

	$keys	= range( 1, 31 );
	$labels	= array(
		__( '1', 'zodiacpress' ), __( '2', 'zodiacpress' ), __( '3', 'zodiacpress' ), __( '4', 'zodiacpress' ), __( '5', 'zodiacpress' ), __( '6', 'zodiacpress' ), __( '7', 'zodiacpress' ), __( '8', 'zodiacpress' ), __( '9', 'zodiacpress' ), __( '10', 'zodiacpress' ), __( '11', 'zodiacpress' ), __( '12', 'zodiacpress' ), __( '13', 'zodiacpress' ), __( '14', 'zodiacpress' ), __( '15', 'zodiacpress' ), __( '16', 'zodiacpress' ), __( '17', 'zodiacpress' ), __( '18', 'zodiacpress' ), __( '19', 'zodiacpress' ), __( '20', 'zodiacpress' ), __( '21', 'zodiacpress' ), __( '22', 'zodiacpress' ), __( '23', 'zodiacpress' ), __( '24', 'zodiacpress' ), __( '25', 'zodiacpress' ), __( '26', 'zodiacpress' ), __( '27', 'zodiacpress' ), __( '28', 'zodiacpress' ), __( '29', 'zodiacpress' ), __( '30', 'zodiacpress' ), __( '31', 'zodiacpress' ) );


	$n = array_combine( $keys, $labels );

	// if $key is passed, return only that number
	return ( $key !== '' && isset( $n[ $key ] ) ) ? $n[ $key ] : $n;
}

/**
 * Returns internationalized numbers from 00 - 59 with leading zeros up to 09.
 * @param int $key The number to return
 * @return mixed|array|string If key is passed, a string for that number, otherwise array of numbers 
 */
function zp_i18n_numbers_zeros( $key = '' ) {

	// 0-9 need a leading zero
	$prepend = array( '00', '01', '02', '03', '04', '05', '06', '07', '08', '09' );
	$range = array_merge( $prepend, range( 10, 59 ) );

	$labels = array(
		__( '00', 'zodiacpress' ), __( '01', 'zodiacpress' ), __( '02', 'zodiacpress' ), __( '03', 'zodiacpress' ), __( '04', 'zodiacpress' ), __( '05', 'zodiacpress' ), __( '06', 'zodiacpress' ), __( '07', 'zodiacpress' ), __( '08', 'zodiacpress' ), __( '09', 'zodiacpress' ), __( '10', 'zodiacpress' ), __( '11', 'zodiacpress' ), __( '12', 'zodiacpress' ), __( '13', 'zodiacpress' ), __( '14', 'zodiacpress' ), __( '15', 'zodiacpress' ), __( '16', 'zodiacpress' ), __( '17', 'zodiacpress' ), __( '18', 'zodiacpress' ), __( '19', 'zodiacpress' ), __( '20', 'zodiacpress' ), __( '21', 'zodiacpress' ), __( '22', 'zodiacpress' ), __( '23', 'zodiacpress' ), __( '24', 'zodiacpress' ), __( '25', 'zodiacpress' ), __( '26', 'zodiacpress' ), __( '27', 'zodiacpress' ), __( '28', 'zodiacpress' ), __( '29', 'zodiacpress' ), __( '30', 'zodiacpress' ), __( '31', 'zodiacpress' ), __( '32', 'zodiacpress' ), __( '33', 'zodiacpress' ), __( '34', 'zodiacpress' ), __( '35', 'zodiacpress' ), __( '36', 'zodiacpress' ), __( '37', 'zodiacpress' ), __( '38', 'zodiacpress' ), __( '39', 'zodiacpress' ), __( '40', 'zodiacpress' ), __( '41', 'zodiacpress' ), __( '42', 'zodiacpress' ), __( '43', 'zodiacpress' ), __( '44', 'zodiacpress' ), __( '45', 'zodiacpress' ), __( '46', 'zodiacpress' ), __( '47', 'zodiacpress' ), __( '48', 'zodiacpress' ), __( '49', 'zodiacpress' ), __( '50', 'zodiacpress' ), __( '51', 'zodiacpress' ), __( '52', 'zodiacpress' ), __( '53', 'zodiacpress' ), __( '54', 'zodiacpress' ), __( '55', 'zodiacpress' ), __( '56', 'zodiacpress' ), __( '57', 'zodiacpress' ), __( '58', 'zodiacpress' ), __( '59', 'zodiacpress' ) );
	
	$n = array_combine( $range, $labels );

	// if $key is passed, return only that number
	return ( $key !== '' && isset( $n[ $key ] ) ) ? $n[ $key ] : $n;	
}


/**
 * Returns internationalized numbers from 0 to 180 without leading zeros.
 *
 * For use with coordinates.
 * @param int $key The number to return
 * @return mixed|array|string If key is passed, a string for that number, otherwise array of numbers 
 */
function zp_i18n_coordinates( $key = '' ) {

	$append = array(
			__( '32', 'zodiacpress' ), __( '33', 'zodiacpress' ), __( '34', 'zodiacpress' ), __( '35', 'zodiacpress' ), __( '36', 'zodiacpress' ), __( '37', 'zodiacpress' ), __( '38', 'zodiacpress' ), __( '39', 'zodiacpress' ), __( '40', 'zodiacpress' ), __( '41', 'zodiacpress' ), __( '42', 'zodiacpress' ), __( '43', 'zodiacpress' ), __( '44', 'zodiacpress' ), __( '45', 'zodiacpress' ), __( '46', 'zodiacpress' ), __( '47', 'zodiacpress' ), __( '48', 'zodiacpress' ), __( '49', 'zodiacpress' ), __( '50', 'zodiacpress' ), __( '51', 'zodiacpress' ), __( '52', 'zodiacpress' ), __( '53', 'zodiacpress' ), __( '54', 'zodiacpress' ), __( '55', 'zodiacpress' ), __( '56', 'zodiacpress' ), __( '57', 'zodiacpress' ), __( '58', 'zodiacpress' ), __( '59', 'zodiacpress' ), __( '60', 'zodiacpress' ), __( '61', 'zodiacpress' ), __( '62', 'zodiacpress' ), __( '63', 'zodiacpress' ), __( '64', 'zodiacpress' ), __( '65', 'zodiacpress' ), __( '66', 'zodiacpress' ), __( '67', 'zodiacpress' ), __( '68', 'zodiacpress' ), __( '69', 'zodiacpress' ), __( '70', 'zodiacpress' ), __( '71', 'zodiacpress' ), __( '72', 'zodiacpress' ), __( '73', 'zodiacpress' ), __( '74', 'zodiacpress' ), __( '75', 'zodiacpress' ), __( '76', 'zodiacpress' ), __( '77', 'zodiacpress' ), __( '78', 'zodiacpress' ), __( '79', 'zodiacpress' ), __( '80', 'zodiacpress' ), __( '81', 'zodiacpress' ), __( '82', 'zodiacpress' ), __( '83', 'zodiacpress' ), __( '84', 'zodiacpress' ), __( '85', 'zodiacpress' ), __( '86', 'zodiacpress' ), __( '87', 'zodiacpress' ), __( '88', 'zodiacpress' ), __( '89', 'zodiacpress' ), __( '90', 'zodiacpress' ), __( '91', 'zodiacpress' ), __( '92', 'zodiacpress' ), __( '93', 'zodiacpress' ), __( '94', 'zodiacpress' ), __( '95', 'zodiacpress' ), __( '96', 'zodiacpress' ), __( '97', 'zodiacpress' ), __( '98', 'zodiacpress' ), __( '99', 'zodiacpress' ), __( '100', 'zodiacpress' ), __( '101', 'zodiacpress' ), __( '102', 'zodiacpress' ), __( '103', 'zodiacpress' ), __( '104', 'zodiacpress' ), __( '105', 'zodiacpress' ), __( '106', 'zodiacpress' ), __( '107', 'zodiacpress' ), __( '108', 'zodiacpress' ), __( '109', 'zodiacpress' ), __( '110', 'zodiacpress' ), __( '111', 'zodiacpress' ), __( '112', 'zodiacpress' ), __( '113', 'zodiacpress' ), __( '114', 'zodiacpress' ), __( '115', 'zodiacpress' ), __( '116', 'zodiacpress' ), __( '117', 'zodiacpress' ), __( '118', 'zodiacpress' ), __( '119', 'zodiacpress' ), __( '120', 'zodiacpress' ), __( '121', 'zodiacpress' ), __( '122', 'zodiacpress' ), __( '123', 'zodiacpress' ), __( '124', 'zodiacpress' ), __( '125', 'zodiacpress' ), __( '126', 'zodiacpress' ), __( '127', 'zodiacpress' ), __( '128', 'zodiacpress' ), __( '129', 'zodiacpress' ), __( '130', 'zodiacpress' ), __( '131', 'zodiacpress' ), __( '132', 'zodiacpress' ), __( '133', 'zodiacpress' ), __( '134', 'zodiacpress' ), __( '135', 'zodiacpress' ), __( '136', 'zodiacpress' ), __( '137', 'zodiacpress' ), __( '138', 'zodiacpress' ), __( '139', 'zodiacpress' ), __( '140', 'zodiacpress' ), __( '141', 'zodiacpress' ), __( '142', 'zodiacpress' ), __( '143', 'zodiacpress' ), __( '144', 'zodiacpress' ), __( '145', 'zodiacpress' ), __( '146', 'zodiacpress' ), __( '147', 'zodiacpress' ), __( '148', 'zodiacpress' ), __( '149', 'zodiacpress' ), __( '150', 'zodiacpress' ), __( '151', 'zodiacpress' ), __( '152', 'zodiacpress' ), __( '153', 'zodiacpress' ), __( '154', 'zodiacpress' ), __( '155', 'zodiacpress' ), __( '156', 'zodiacpress' ), __( '157', 'zodiacpress' ), __( '158', 'zodiacpress' ), __( '159', 'zodiacpress' ), __( '160', 'zodiacpress' ), __( '161', 'zodiacpress' ), __( '162', 'zodiacpress' ), __( '163', 'zodiacpress' ), __( '164', 'zodiacpress' ), __( '165', 'zodiacpress' ), __( '166', 'zodiacpress' ), __( '167', 'zodiacpress' ), __( '168', 'zodiacpress' ), __( '169', 'zodiacpress' ), __( '170', 'zodiacpress' ), __( '171', 'zodiacpress' ), __( '172', 'zodiacpress' ), __( '173', 'zodiacpress' ), __( '174', 'zodiacpress' ), __( '175', 'zodiacpress' ), __( '176', 'zodiacpress' ), __( '177', 'zodiacpress' ), __( '178', 'zodiacpress' ), __( '179', 'zodiacpress' ), __( '180', 'zodiacpress' )
		);


	// Prepend 0 to the 1-31 numbers array
	$n = array_merge( array( __( '0', 'zodiacpress' ) ), zp_i18n_numbers() );

	// add 32-180 to the numbers array
	$n = array_merge( $n, $append );

	// if $key is passed, return only that number
	return ( $key !== '' && isset( $n[ $key ] ) ) ? $n[ $key ] : $n;

}

/**
 * Convert decimal degrees (DD) to degrees and minutes with optional cardinal direction (N,S,E,W).
 * 
 * Converts longitude and latitude coordinates from format DD to DM (degrees and minutes) 
 * with compass direction. Or converts aspect orb decimal degrees into degrees and minutes.
 *
 * @param $decimal int The degrees decimal to convert
 * @param $line string For geo coordinates only, whether longitude or latitude. Accepts longitude or latitude. If blank, cardinal direction (N,S,E,W) will not be given. Leave blank for aspect orbs.
 * @return string Formated degrees and minutes
 */
function zp_dd_to_dms( $decimal, $line = '' ) {
	$direction = '';
	$dm = zp_extract_degrees_parts( $decimal );
	$d = $dm[0];
	$m = $dm[1];
	
	if ( $line ) {

		// This is a coordinate, not an orb, so add cardinal direction
		if ( 'longitude' == $line ) {
			$direction = ( ( $decimal > 0 ) ? 'E' : 'W' );
		} elseif( 'latitude' == $line ) {
			$direction = ( ( $decimal > 0 ) ? 'N' : 'S' );
		}
	}

	if ( is_rtl() ) {
		$degrees = '&#176;' . zp_i18n_coordinates( $d );
	} else {
		$degrees = zp_i18n_coordinates( $d ) . '&#176;';
	}

	/* translators: Attention RTL languages. 3 placeholders are degrees, cardinal direction, minutes for coordinates. */
	$out = sprintf( __( '%1$s%2$s%3$s\'', 'zodiacpress' ),
			$degrees,
			$direction,
			zp_i18n_coordinates( $m )
	);	

	return $out;
}

/**
 * Extract whole degrees and whole minutes from a decimal degrees.
 * @param $decimal int The degrees decimal
 * @return $dm array Whole degrees as an absolute value (no negative symbol or direction) and whole minutes
 */
function zp_extract_degrees_parts( $decimal ) {
	$dm[] = floor( abs( $decimal ) );
	// Minutes = original decimal minus degrees part, then multiply by 60, round to integer.
	$dm[] = round( ( abs( $decimal ) - $dm[0] ) * 60 );
	return $dm;
}

/**
 * Internationalize a degrees minutes seconds string
 * @param string $dms A dms string with symbols like 24° 6'50"
 * @return string $out In English, the same string but with leading zeros for minutes and seconds if less than 10. In other languages, the numbers will be transliterated.
 */
function zp_transliterated_degrees_minutes_seconds( $dms ) {

	$parts		= explode( chr( 176), $dms );
	$deg 		= (int) trim( $parts[0] );
	$min_sec	= explode( "'", $parts[1] );
	$min		= (int) trim( $min_sec[0] );
	$sec		= (int) trim( strstr( $min_sec[1], '"', true ) );
	$i18n_degrees = zp_i18n_coordinates( $deg );
	$i18n_degrees = is_array( $i18n_degrees ) ? $deg : $i18n_degrees;
	$degrees = is_rtl() ? ( '&#176;' . $i18n_degrees ) : ( $i18n_degrees . '&#176;' );

	// Insert leading zero when needed
	if ( $min < 10 ) {
		$min = '0' . $min;
	}
	if ( $sec < 10 ) {
		$sec = '0' . $sec;
	}

	/* translators: Attention RTL languages. 5 placeholders are degrees, minutes, minutes symbol, seconds, seconds symbol. */
	$out = sprintf( __( '%1$s %2$s%3$s%4$s%5$s', 'zodiacpress' ),
				$degrees,
				zp_i18n_numbers_zeros( $min ),
				chr(39),
				zp_i18n_numbers_zeros( $sec ),
				chr(34)
		);

	return $out;

}
