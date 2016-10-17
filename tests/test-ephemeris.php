<?php
/**
 * Tests for the ZP_Ephemeris class
 */
class Test_Ephemeris extends WP_UnitTestCase {

	protected $charts						= array();
	protected $expected_planets_longitude	= array();
	protected $latitude;
	protected $longitude;
	protected $ut_date;
	protected $ut_time;

	public function setUp() {
	
		$this->charts = ZP_Helper::create_charts();

		// get location and datetime for ephemeris query args
		$chart = $this->charts[0];
		$property	= ZP_Helper::get_private_property( 'ZP_Chart', 'latitude' );
		$this->latitude	= $property->getValue( $chart );
		$property	= ZP_Helper::get_private_property( 'ZP_Chart', 'longitude' );
		$this->longitude	= $property->getValue( $chart );
		$property	= ZP_Helper::get_private_property( 'ZP_Chart', 'ut_date' );
		$this->ut_date	= $property->getValue( $chart );
		$property	= ZP_Helper::get_private_property( 'ZP_Chart', 'ut_time' );
		$this->ut_time	= $property->getValue( $chart );

		include dirname( __FILE__ ) . '/helper-expected-ephemeris.php';
		
	}
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
	 * Confirm failure of Whole Sign house cusps so that I'll know if this gets fixed in a Sweph update
	 */
	public function test_whole_sign_house_cusps_failure() {

		// Args for the ephemeris query
		$args = array(
			'planets'		=> '0123456789DAt',
			'format'		=> 'ls',
			'house_system'	=> 'W',
			'latitude'		=> $this->latitude,
			'longitude'		=> $this->longitude,
			'ut_date'		=> $this->ut_date,
			'ut_time'		=> $this->ut_time,
		);

		$ephemeris	= new ZP_Ephemeris( $args );
		$data		= $ephemeris->query();

		$this->assertInternalType('array', $data);
			
		// Get calculated house cusps which ephemeris outputs at index 13-24
		$calculated_cusps = array_slice( $data, 13, 12 );

		$this->assertEquals( '172.2929065', trim( $calculated_cusps[0] ), 'GOOD NEWS, the ephemeris may have been fixed to calculate Whole Sign house cusps. Test 12 cusps to be sure.' );

	}
	/**
	 * Test sidereal Fagan/Bradley, planets and points longitudes
	 */
	public function test_sidereal_planets_fagan_bradley() {

		// Args for the ephemeris query
		$args = array(
			'planets'		=> '0123456789DAt',
			'format'		=> 'l',
			'house_system'	=> 'P',
			'latitude'		=> $this->latitude,
			'longitude'		=> $this->longitude,
			'ut_date'		=> $this->ut_date,
			'ut_time'		=> $this->ut_time,
			'options'		=> '-sid0'
		);
		$ephemeris	= new ZP_Ephemeris( $args );
		$data		= $ephemeris->query();

		// Test core planets longitudes
		for ( $x = 0; $x <= 12; $x++ ) {

			$expected	= round( (int) $this->expected_sidereal_fagan[ $x ], 6 );
			$actual		= round( (int) trim( $data[ $x ] ), 6 );
			$this->assertEquals( $expected, $actual, 'Wrong Sidereal Fagan/Bradley longitude for planet ' . $x );

		}

		// Test the Ascendant
		$expected	= round( (int) $this->expected_sidereal_fagan[25], 6 );
		$actual		= round( (int) trim( $data[25] ), 6 );
		$this->assertEquals( $expected, $actual, 'Wrong Sidereal Fagan/Bradley Ascendant' );
		// Test the MC
		$expected	= round( (int) $this->expected_sidereal_fagan[26], 6 );
		$actual		= round( (int) trim( $data[26] ), 6 );
		$this->assertEquals( $expected, $actual, 'Wrong Sidereal Fagan/Bradley MC' );
		// Test the Vertex
		$expected	= round( (int) $this->expected_sidereal_fagan[28], 6 );
		$actual		= round( (int) trim( $data[28] ), 6 );
		$this->assertEquals( $expected, $actual, 'Wrong Sidereal Fagan/Bradley Vertex' );
	}

	/**
	 * Test sidereal Fagan/Bradley house cusps (Placidus)
	 */
	public function test_sidereal_placidus_cusps_fagan_bradley() {

		// Args for the ephemeris query
		$args = array(
			'planets'		=> '0123456789DAt',
			'format'		=> 'l',
			'house_system'	=> 'P',
			'latitude'		=> $this->latitude,
			'longitude'		=> $this->longitude,
			'ut_date'		=> $this->ut_date,
			'ut_time'		=> $this->ut_time,
			'options'		=> '-sid0'
		);
		$ephemeris	= new ZP_Ephemeris( $args );
		$data		= $ephemeris->query();

		for ( $x = 13; $x <= 24; $x++ ) {

			$expected	= round( (int) $this->expected_sidereal_fagan[ $x ], 6 );
			$actual		= round( (int) trim( $data[ $x ] ), 6 );
			$this->assertEquals( $expected, $actual, 'Wrong Sidereal Fagan/Bradley cusp for house ' . ( $x - 12 ) );

		}
	}

	/**
	 * Test sidereal Hindu/Lahiri, planets and points longitudes
	 */
	public function test_sidereal_planets_hindu_lahiri() {

		// Args for the ephemeris query
		$args = array(
			'planets'		=> '0123456789DAt',
			'format'		=> 'l',
			'house_system'	=> 'P',
			'latitude'		=> $this->latitude,
			'longitude'		=> $this->longitude,
			'ut_date'		=> $this->ut_date,
			'ut_time'		=> $this->ut_time,
			'options'		=> '-sid1'
		);
		$ephemeris	= new ZP_Ephemeris( $args );
		$data		= $ephemeris->query();

		// Test core planets longitudes
		for ( $x = 0; $x <= 12; $x++ ) {

			$expected	= round( (int) $this->expected_sidereal_lahiri[ $x ], 6 );
			// $actual		= (int) trim( $data[ $x ] );
			$actual		= round( (int) trim( $data[ $x ] ), 6 );
			$this->assertEquals( $expected, $actual, 'Wrong Sidereal Lahiri longitude for planet ' . $x );

		}

		// Test the Ascendant
		$expected	= round( (int) $this->expected_sidereal_lahiri[25], 6 );
		$actual		= round( (int) trim( $data[25] ), 6 );
		$this->assertEquals( $expected, $actual, 'Wrong Sidereal Lahiri Ascendant' );
		// Test the MC
		$expected	= round( (int) $this->expected_sidereal_lahiri[26], 6 );
		$actual		= round( (int) trim( $data[26] ), 6 );
		$this->assertEquals( $expected, $actual, 'Wrong Sidereal Lahiri MC' );
		// Test the Vertex
		$expected	= round( (int) $this->expected_sidereal_lahiri[28], 6 );
		$actual		= round( (int) trim( $data[28] ), 6 );
		$this->assertEquals( $expected, $actual, 'Wrong Sidereal Lahiri Vertex' );
	}		
}
