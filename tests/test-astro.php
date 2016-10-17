<?php
class Test_Astro extends WP_UnitTestCase {

	/**
	 * Test the zp_get_zodiac_sign_dms() function
	 */
	public function test_get_zodiac_sign_dms() {

		$sec = chr(34);
	
		$longitudes = array(
			array( '335.7481352',	'&#160;5&#176; <span class="zp-icon-pisces"> </span> 44\' 53' . $sec ),

			array( '56.9998791',	'27&#176; <span class="zp-icon-taurus"> </span> 00\' 00' . $sec ),// Michael Jackson 3rd Placidus cusp 

		);

		foreach ( $longitudes as $expected ) {
			$this->assertEquals( $expected[1], zp_get_zodiac_sign_dms( $expected[0] ), 'Wrong zodiac sign dms for: ' . $expected[0] );
		}

	}

	/**
	 * Zodiac Sign degrees should never be greater than 29
	 */
	public function test_zodiac_sign_dms_degrees_max() {

		$sec = chr(34);
	
		$longitudes = array(

			array( '59.8', '29&#176; <span class="zp-icon-taurus"> </span> 48\' 00' . $sec ),

			// 30 degrees should become 0, increment sign
			array( '59.9998791', '&#160;0&#176; <span class="zp-icon-gemini"> </span> 00\' 00' . $sec ),

			// 30 degrees should become 0, increment sign
			array( '59.9999991', '&#160;0&#176; <span class="zp-icon-gemini"> </span> 00\' 00' . $sec ),

		);

		foreach ( $longitudes as $expected ) {
			$this->assertEquals( $expected[1], zp_get_zodiac_sign_dms( $expected[0] ), 'Wrong zodiac sign dms for: ' . $expected[0] );
		}

	}

	/**
	 * Test that 360 degrees is Aries 0 (Zodiac Sign number should never be greater than 12)
	 */
	public function test_zodiac_sign_dms_aries_zero() {

		$sec = chr(34);
		$longitudes = array(

			array( '0.0', '&#160;0&#176; <span class="zp-icon-aries"> </span> 00\' 00' . $sec ),			
			// still 29 Pisces
			array( '359.9', '29&#176; <span class="zp-icon-pisces"> </span> 54\' 00' . $sec ),

			// 0 Aries
			array( '359.9999999', '&#160;0&#176; <span class="zp-icon-aries"> </span> 00\' 00' . $sec ),

			array( '360', 'ERROR 360: undefined' ),
		);
		
		foreach ( $longitudes as $expected ) {
			$this->assertEquals( $expected[1], zp_get_zodiac_sign_dms( $expected[0] ), 'Wrong zodiac sign dms for: ' . $expected[0] );
		}
	}

	public function test_is_planet_near_ingress() {

		/*
		 * 
			limits for planet 0 are < 2 and > 28
			limits for planet 2 are < 2 and > 28
			limits for planet 3 are < 2 and > 28
			limits for planet 4 are < 2 and > 28
			limits for planet 5 are < 0.5 and > 29.5
			limits for planet 6 are < 0.266666666667 and > 29.7333333333
			limits for planet 7 are < 0.15 and > 29.85
			limits for planet 8 are < 0.116666666667 and > 29.8833333333
			limits for planet 9 are < 0.116666666667 and > 29.8833333333
			limits for planet 10 are < 0.3 and > 29.7
			limits for planet 11 are < 0.333333333333 and > 29.6666666667
			limits for planet 12 are < 0.5 and > 29.5 

		 */
		
		$expected = array(
			array( '0', '1.98', true ),
			array( '0', '2.012', false ),
			array( '0', '28.012', true ),
			array( '0', '27.012', false ),
			array( '6', '.012', true ),
			array( '6', '27.012', false ),
			array( '6', '29.721555', false ),
			array( '7', '29.98', true ),
			array( '8', '0.11445', true ),
			array( '8', '0.1167', false ),
			array( '8', '29.88443', true ),
			array( '8', '29.8831145', false ),
			array( '10', '29.8831145', true ),
			array( '11', '0.32255', true ),
			array( '11', '0.3335', false ),
		);

		foreach ( $expected as $expect ) {

			if ( $expect[2] ) {
				$this->assertTrue( zp_is_planet_near_ingress( $expect[0], $expect[1] ), 'for planet ' . $expect[0] . ', longitude ' . $expect[1] );	
			} else {
				$this->assertFalse( zp_is_planet_near_ingress( $expect[0], $expect[1] ), 'for planet ' . $expect[0] . ', longitude ' . $expect[1] );
			}
		}

	}

	/**
	 * Test the zp_is_planet_ingress_today() function for a true
	 */
	public function test_is_planet_ingress_today_true_mars() {

		// $timestamp = -468518400;// 2/26/1955 8 am
		$form = array(
				'month'				=> '2',
				'day'				=> '26',
				'year'				=> '1955',			
				'geo_timezone_id'	=> 'America/Los_Angeles',
		);
		$actual = zp_is_planet_ingress_today( 4, '29.9308316', $form );

		$this->assertInternalType('array', $actual);
		$this->assertEquals( 0, $actual[0] );// aries
		$this->assertEquals( 1, $actual[1] );// taurus

	}

	/**
	 * Test the zp_is_planet_ingress_today() function for a false
	 */
	public function test_is_planet_ingress_today_false_mars() {

		// $timestamp = -468432000;//2/27/1955 8 am utc
		$form = array(
				'month'				=> '2',
				'day'				=> '27',
				'year'				=> '1955',			
				'geo_timezone_id'	=> 'America/Los_Angeles',
		);
		$actual = zp_is_planet_ingress_today( 4, '30.6317917', $form );

		$this->assertFalse( $actual );

	}

	/**
	 * Test the zp_is_planet_ingress_today() function for a true
	 */
	public function test_is_planet_ingress_today_true_mars_2() {

		// $timestamp = 372412800;// 10/20/1981 8 am
		$form = array(
				'month'				=> '10',
				'day'				=> '20',
				'year'				=> '1981',			
				'geo_timezone_id'	=> 'America/Los_Angeles',
		);
		$actual = zp_is_planet_ingress_today( 4, '149.5622890', $form );

		$this->assertInternalType('array', $actual);
 		$this->assertEquals( 4, $actual[0] );// leo
		$this->assertEquals( 5, $actual[1] );// virgo
	}
	
	/**
	 * Test the zp_is_planet_ingress_today() function for a false
	 */
	public function test_is_planet_ingress_today_false_mars_2() {

		//$timestamp = -372499200;// 10/21/1981 8 am
		$form = array(
				'month'				=> '10',
				'day'				=> '21',
				'year'				=> '1981',			
				'geo_timezone_id'	=> 'America/Los_Angeles',
		);
		
		$actual = zp_is_planet_ingress_today( 4, '150.1477661', $form );

		$this->assertFalse( $actual );

	}

}