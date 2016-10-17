<?php
class Test_Sidereal extends WP_UnitTestCase {

	public function test_if_exec_enabled() {
		$this->assertTrue( zp_is_func_enabled( 'exec' ) );
	}

	/**
	 * Test ayanamsa for all sidereal methods
	 */
	public function test_ayanamsa() {

		$sec = chr(34);

		$expected = array(
			'fagan/bradley'	=> '24&#176; 06\'50' . $sec,
			'lahiri'		=> '23&#176; 13\'50' . $sec,
			'raman'			=> '21&#176; 47\'04' . $sec,
			'krishnamurti'	=> '23&#176; 08\'03' . $sec,
		);

		foreach ( $expected as $k => $v ) {
			$charts	= ZP_Helper::create_charts( $k );
			$chart	= $charts[0];// Steve Jobs
			$property = ZP_Helper::get_private_property( 'ZP_Chart', 'ayanamsa' );
			$ayanamsa = $property->getValue( $chart );
			$actual = zp_transliterated_degrees_minutes_seconds( $ayanamsa );

			$this->assertEquals( $v, $actual, 'Wrong ' . $k . ' ayanamsa' );
		}
	}

}
