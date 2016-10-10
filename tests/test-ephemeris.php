<?php
class Test_Ephemeris extends WP_UnitTestCase {

	/**
	 * Test query the ephemeris
	 */
	public function test_query_ephemeris() {

		// Args for ephemeris query
		$args = array(
			'planets'	=> 4,
			'format'	=> 'l',
			'ut_date'	=> '21.10.1981',
			'ut_time'	=> '08:00:00',
			'options'	=> '-n2 -s1 -roundsec'// get 2 days
		);

		$ephemeris	= new ZP_Ephemeris( $args );
		$data		= $ephemeris->query();

		$this->assertInternalType('array', $data);
		$this->assertCount( 2, $data );
		$this->assertEquals( '150.1477663', trim( $data[0] ) );
		$this->assertEquals( '150.7319418', trim( $data[1] ) );

	}

}
