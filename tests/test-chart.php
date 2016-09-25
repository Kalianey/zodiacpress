<?php
class Test_Chart extends WP_UnitTestCase {

	protected $charts						= array();
	protected $expected_planets_longitude	= array();
	protected $expected_h_pos				= array();
	protected $expected_conjunctions		= array();
	protected $expected_cusps				= array();

	public function setUp() {
	
		$this->charts = ZP_Helper::create_charts();

		include dirname( __FILE__ ) . '/helper-expected-chart.php';
		
	}

	public function test_if_exec_enabled() {
		$this->assertTrue( zp_is_func_enabled( 'exec' ) );
	}

	/**
	 * Test Universal Date
	 */
	public function test_ut_date() {

		$expected_ut_date = array(
			'25.02.1955',// Steve Jobs
			'30.08.1958'// Michael Jackson
		);
				
		foreach ( $this->charts as $person => $chart ) {

			$property = ZP_Helper::get_private_property( 'ZP_Chart', 'ut_date' );
			$calculated_ut_date = $property->getValue( $chart );

			$this->assertEquals( $expected_ut_date[ $person ], $calculated_ut_date, 'Wrong UT date for Person ' . $person );
		}

	}

	/**
	 * Test Universal Time
	 */
	public function test_ut_time() {

		$expected_ut_time = array(
			'03:15:00',// Steve Jobs
			'00:33:00'// Michael Jackson
		);
				
		foreach ( $this->charts as $person => $chart ) {

			$property = ZP_Helper::get_private_property( 'ZP_Chart', 'ut_time' );
			$calculated_ut_time = $property->getValue( $chart );

			$this->assertEquals( $expected_ut_time[ $person ], $calculated_ut_time, 'Wrong UT time for Person ' . $person );
		}

	}
	/**
	 * Test the ephemeris output for planet/points longitudes for Placidus houses
	 */
	public function test_ephemeris_planets_longitudes() {
				
		foreach ( $this->charts as $person => $chart ) {

			// Check core planets longitudes
			for ( $x = 0; $x <= 12; $x++ ) {
				$expected	= round( $this->expected_planets_longitude[ $person ][ $x ], 5 );
				$actual		= round( $chart->planets_longitude[ $x ], 5 );
				$this->assertEquals( $expected, $actual, 'Wrong longitude for planet ' . $x . ', Person ' . $person );

			}

			// Test the Vertex
			$expected_v	= round( $this->expected_planets_longitude[ $person ][28], 5 );
			$actual_v	= round( $chart->planets_longitude[14], 5 );
			$this->assertEquals( $expected_v, $actual_v, 'Wrong Vertex for Person ' . $person );

			// Test the Ascendant
			$expected_a	= round( $this->expected_planets_longitude[ $person ][25], 5 );
			$actual_a	= round( $chart->planets_longitude[15], 5 );
			$this->assertEquals( $expected_a, $actual_a, 'Wrong Ascendant for Person ' . $person);

			// Test the MC
			$expected_mc	= round( $this->expected_planets_longitude[ $person ][26], 5 );
			$actual_mc		= round( $chart->planets_longitude[16], 5 );
			$this->assertEquals( $expected_mc, $actual_mc, 'Wrong MC for Person ' . $person );
		}

	}

	/**
	 * Test the house cusps for Placidus houses
	 */
	public function test_house_cusps() {
				
		foreach ( $this->charts as $person => $chart ) {
			// Get calculated house cusps (Placidus)
			$property			= ZP_Helper::get_private_property( 'ZP_Chart', 'cusps' );
			$calculated_cusps	= $property->getValue( $chart );

			for ( $i = 1; $i <= 12; $i++ ) {
				$actual		= zp_get_zodiac_sign_dms( $calculated_cusps[ $i ] );
				$expected	= $this->expected_cusps[ $person ][ $i ];
				$this->assertEquals( $expected, $actual, 'Wrong Placidus house cusp calculation for person ' . $person . ', cusp ' . $i );
			}
		}

	}

	/**
	 * Test the Part of Fortune calculation for Placidus houses
	 */
	public function test_pof() {

		$sec = chr(34);

		$expected_pof = array(
			'20&#176; <span class="zp-icon-leo"> </span> 17\' 37' . $sec,// Steve Jobs [0]
			'&#160;1&#176; <span class="zp-icon-virgo"> </span> 20\' 46' . $sec,// Michael Jackson
		);

		foreach ( $this->charts as $person => $chart ) {
			$actual = zp_get_zodiac_sign_dms( $chart->planets_longitude[13] );
			$this->assertEquals( $expected_pof[ $person ], $actual, 'Wrong Part of Fortune for Person ' . $person );
		}

	}

	/**
	 * Test house position numbers of planets in Placidus houses
	 */
	public function test_house_positions() {

		foreach ( $this->charts as $person => $chart ) {
		
			// Test the planets
			for ( $i = 0; $i <= 14; $i++ ) {

				$expected	= $this->expected_h_pos[ $person ][ $i ];
				$actual		= $chart->planets_house_numbers[ $i ];

				$this->assertEquals( $expected, $actual, 'Wrong house position for person ' . $person . ', planet key: ' . $i );

			}
		}
	}

	/**
 	 * Test conjunctions to the next house cusps for Placidus houses
 	 */
	public function test_house_cusp_conjunctions() {

		foreach ( $this->charts as $person => $chart ) {

			// Test the planets
			for ( $i = 0; $i <= 14; $i++ ) {
				$expected	= $this->expected_conjunctions[ $person ][ $i ];
				$actual		= $chart->conjunct_next_cusp[ $i ];

				$this->assertEquals( $expected, $actual, 'Wrong for person ' . $person . ', planet key: ' . $i );
			}
		}
	}


}