<?php
/**
 * Class ZP_Helper
 *
 * Helper class for ZodiacPress UnitTests
 */
class ZP_Helper extends WP_UnitTestCase {

	/**
	 * Create test charts
	 */
	public static function create_charts( $sidereal = false ) {

		$persons = array(
			array(
				'name'					=> 'Steve Jobs',
				'month'					=> '2',
				'day'					=> '24',
				'year'					=> '1955',
				'hour'					=> '19',
				'minute'				=> '15',
				'geo_timezone_id'		=> 'America/Los_Angeles',
				'place'					=> 'San Francisco, California, United States',
				'zp_lat_decimal'		=> '37.77493',
				'zp_long_decimal'		=> '-122.41942',
				'zp_offset_geo'			=> '-8',
				'action'				=> 'zp_birthreport',
				'zp-report-variation'	=> 'birthreport',
				'unknown_time'			=> '',
				'house_system'			=> false,
				'sidereal'				=> $sidereal
			),
			array(
				'name'					=> 'Michael Jackson',
				'month'					=> '8',
				'day'					=> '29',
				'year'					=> '1958',
				'hour'					=> '19',
				'minute'				=> '33',
				'geo_timezone_id'		=> 'America/Chicago',
				'place'					=> 'Gary, Indiana, United States',
				'zp_lat_decimal'		=> '41.59337',
				'zp_long_decimal'		=> '-87.34643',
				'zp_offset_geo'			=> '-5',
				'action'				=> 'zp_birthreport',
				'zp-report-variation'	=> 'birthreport',
				'unknown_time'			=> '',
				'house_system'			=> false,
				'sidereal'				=> $sidereal
			)
		);

		// Get test charts 
		foreach ( $persons as $v ) {
			$charts[] = ZP_Chart::get_instance( $v );
		}

		return $charts;

	}

	/**
 	 * get_private_property
 	 */
	public static function get_private_property( $class_name, $property_name ) {
		$reflector = new ReflectionClass( $class_name );
		$property = $reflector->getProperty( $property_name );
		$property->setAccessible( true );
 
		return $property;
	}

	/**
 	 * get_private_method
 	 */
	public static function get_private_method( $class_name, $method_name ) {
		$reflector = new ReflectionClass( $class_name );
		$method = $reflector->getMethod( $method_name );
		$method->setAccessible( true );
 
		return $method;
	}
	/**
	 * Print output to the terminal
	 */
	public static function print_to_terminal( $var ) {
        fwrite( STDERR, print_r( $var, TRUE ) );
    }	

}
