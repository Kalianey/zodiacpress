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
}