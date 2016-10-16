<?php
class Test_Time extends WP_UnitTestCase {

	/**
	 * Test the zp_get_timezone_offset() function
	 */
	public function test_get_timezone_offset() {
	
		$datetimes = array(
			// this is the dtstamp in ajax-functions.php:
			array( 'America/Los_Angeles',	'1955-02-24 19:15:00', '-8' ),
			// this is the dtstamp in phpfiddle.net:
			array( 'America/Los_Angeles',	'1955-02-25 03:15:00', '-8' ),
			array( 'America/Chicago',		'1958-08-29 19:33', '-5' ),
			array( 'America/Managua',		'1979-03-19 11:00', '-5' ),
			array( 'Asia/Tokyo',			'1972-09-12 07:55', '9' ),
			array( 'Europe/London',			'1980-11-03 12:00', '0' ),
			array( 'Europe/London',			'1980-06-03 12:00', '1' )
		);
		foreach ( $datetimes as $dt ) {
			$this->assertEquals( $dt[2], zp_get_timezone_offset( $dt[0], $dt[1] ), 'Wrong timezone offset for ' . $dt[0] . ', ' . $dt[1] );
		}
	}

	/**
	 * Test the zp_get_12_hour() function
	 */
	public function test_get_12_hour_format() {
		$hours = array( 
			array( '00', '12 am, midn' ),
			array( '01', '1 am' ),
			array( '02', '2 am' ),
			array( '03', '3 am' ),
			array( '04', '4 am' ),
			array( '05', '5 am' ),
			array( '06', '6 am' ),
			array( '07', '7 am' ),
			array( '08', '8 am' ),
			array( '09', '9 am' ),
			array( '10', '10 am' ),
			array( '11', '11 am' ),
			array( '12', '12 pm, noon' ),
			array( '13', '1 pm' ),
			array( '14', '2 pm' ),
			array( '15', '3 pm' ),
			array( '16', '4 pm' ),
			array( '17', '5 pm' ),
			array( '18', '6 pm' ),
			array( '19', '7 pm' ),
			array( '20', '8 pm' ),
			array( '21', '9 pm' ),
			array( '22', '10 pm' ),
			array( '23', '11 pm' )
		);
		foreach ( $hours as $h ) {
			$this->assertEquals( $h[1], zp_get_12_hour( $h[0] ), 'Wrong 12 hour format.' );
		}
	}

	/**
	 * Test the zp_dd_to_dms() function
	 */
	public function test_dd_to_dms() {

		$decimals = array(
			array( '25.77427',	'latitude',		'25&#176;N46\'' ),
			array( '-80.19366',	'longitude',	'80&#176;W12\'' ),
			array( '0.0',		'longitude',	'0&#176;W0\'' ),
			array( '0',			'longitude',	'0&#176;W0\'' ),
			array( '0.0',		'latitude',		'0&#176;S0\'' ),
			array( '0',			'latitude',		'0&#176;S0\'' ),
			
		);
		foreach ( $decimals as $d ) {
			$this->assertEquals( $d[2], zp_dd_to_dms( $d[0], $d[1] ), 'Wrong degrees/minutes for decimal: ' . $d[0] );
		}

	}

	/**
	 * Test the formatted 12 hour time displayed in the report header
	 */
	public function test_formatted_12_hour_time() {

		// local form hour (in 24-hr) and minute:
		$expected = array(
			array( '19', '15', '7:15 pm' ),
			array( '07', '15', '7:15 am' ),
			);
			
		foreach ( $expected as $expect ) {

			$time = $expect[0] . ':' . $expect[1];

			$this->assertEquals( $expect[2], date( 'g:i a', strtotime( $time ) ) );
		}

	}

	/**
	 * Test the formatted 12 hour time displayed in the report header with off timezone
	 */
	public function test_formatted_12_hour_time_off_timezone() {

		// random timezone
		date_default_timezone_set( 'America/Chicago' );

		// local form hour (in 24-hr) and minute:
		$expected = array(
			array( '19', '15', '7:15 pm' ),
			array( '07', '15', '7:15 am' ),
			);
			
		foreach ( $expected as $expect ) {

			$time = $expect[0] . ':' . $expect[1];

			$this->assertEquals( $expect[2], date( 'g:i a', strtotime( $time ) ) );
		}

	}

	/**
	 * Test the zp_transliterated_degrees_minutes_seconds() function
	 */
	public function test_transliterate_dms() {

		$expected = array(
			array( ' 24° 6\'50" ', '24&#176; 06\'50"' ),
			);

		foreach ( $expected as $e ) {
			$actual = zp_transliterated_degrees_minutes_seconds( $e[0] );
			$this->assertEquals( $e[1], $actual );

		}

	}

	/**
	 * Test that Universal Date is properly adjusted a month and year forward for timezone offset.
	 */
	public function test_ut_date_day_change_next_year() {

		$person = array(
			'name'					=> 'Led Zeppelin',
			'month'					=> '12',
			'day'					=> '31',
			'year'					=> '1955',
			'hour'					=> '20',
			'minute'				=> '30',
			'place'					=> 'San Francisco, California, United States',
			'zp_lat_decimal'		=> '37.77493',
			'zp_long_decimal'		=> '-122.41942',
			'geo_timezone_id'		=> 'America/Los_Angeles',
			'zp-report-variation'	=> 'birthreport',
			'zp_offset_geo'			=> '-8',
			'action'				=> 'zp_birthreport',
			'unknown_time'			=> '',
			'sidereal'				=> false,
			);

		$chart = ZP_Chart::get_instance( $person );

		$expected_ut_date = '01.01.1956';

		$property = ZP_Helper::get_private_property( 'ZP_Chart', 'ut_date' );
		$calculated_ut_date = $property->getValue( $chart );

		$this->assertEquals( $expected_ut_date, $calculated_ut_date );

	}


	/**
	 * Test that Universal Date is properly adjusted a month and year backward for timezone offset.
	 */
	public function test_ut_date_day_change_previous_year() {

		$person = array(
			// 'name'					=> 'The Who',
			'month'					=> '1',
			'day'					=> '1',
			'year'					=> '1978',
			'hour'					=> '01',
			'minute'				=> '00',
			'place'					=> 'Hong, Arunachal Pradesh, Republic of India',
			'zp_lat_decimal'		=> '27.55922',
			'zp_long_decimal'		=> '93.8495',
			'geo_timezone_id'		=> 'Asia/Kolkata',
			'zp-report-variation'	=> 'birthreport',
			'zp_offset_geo'			=> '5.5',
			'action'				=> 'zp_birthreport',
			'unknown_time'			=> '',
			'sidereal'				=> false,
		);
		$chart = ZP_Chart::get_instance( $person );

		$expected_ut_date = '31.12.1977';

		$property = ZP_Helper::get_private_property( 'ZP_Chart', 'ut_date' );
		$calculated_ut_date = $property->getValue( $chart );

		$this->assertEquals( $expected_ut_date, $calculated_ut_date );		

	}

}