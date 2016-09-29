<?php
class Test_Report extends WP_UnitTestCase {

	protected $charts	= array();
	protected $chart	= array();

	public function setUp() {
		$this->charts	= ZP_Helper::create_charts();
		$this->chart	= $this->charts[0];

		// Clear all custom orbs to reset them to 8.
		$options = get_option( 'zodiacpress_settings' );
		foreach( $options  as $k => $v ) {
			if ( 0 === strpos( $k, 'orb_' ) ) {
				unset( $options[ $k ] );
			}
		}
		// update the option
		global $zodiacpress_options;
		$zodiacpress_options = $options;
		update_option( 'zodiacpress_settings', $options );			

	}

	/**
	 * Test ZP_Birth_Report::get_cleared_planets() for Planets in Signs
	 */
	public function test_get_cleared_planets_in_signs() {

		$key = 'enable_planet_signs';
		$options = get_option( 'zodiacpress_settings' );

		// Set some default enabled for Planets in Signs
		$options[ $key ] = array(
			array(
				'id'				=> 'sun',
				'label'				=> '',
				'official_index'	=> 0
			),
			array(
				'id'				=> 'moon',
				'label'				=> '',
				'official_index'	=> 1
			),
			array(
				'id'				=> 'saturn',
				'label'				=> '',
				'official_index'	=> 6
			),
			array(
				'id'				=> 'pof',
				'label'				=> '',
				'official_index'	=> 13
			),
			array(
				'id'				=> 'asc',
				'label'				=> '',
				'official_index'	=> 15
			),
		);

		// update the global variable.
		global $zodiacpress_options;
		$zodiacpress_options = $options;
		update_option( 'zodiacpress_settings', $options );

		// Get actual value of ZP_Birth_Report::get_cleared_planets()
		$object = new ZP_Birth_Report( $this->chart, array( 'unknown_time' => '' ) );
		$method = ZP_Helper::get_private_method( 'ZP_Birth_Report', 'get_cleared_planets' );
 		$actual = $method->invokeArgs( $object, array( $key ) );

		$this->assertInternalType( 'array', $actual );
		$this->assertCount( 5, $actual );
		foreach ( $options[ $key ] as $expected ) {
			$this->assertArrayHasKey( $expected['official_index'], $actual );	
		}
	}

	/**
	 * Test ZP_Birth_Report::get_cleared_planets() for Planets in Houses
	 */
	public function test_get_cleared_planets_in_houses() {

		$key = 'enable_planet_houses';
		$options = get_option( 'zodiacpress_settings' );

		// Set some default enabled for Planets in Houses
		$options[ $key ] = array(
			array(
				'id'				=> 'sun',
				'label'				=> '',
				'official_index'	=> 0
			),
			array(
				'id'				=> 'moon',
				'label'				=> '',
				'official_index'	=> 1
			),
			array(
				'id'				=> 'saturn',
				'label'				=> '',
				'official_index'	=> 6
			),
			array(
				'id'				=> 'pof',
				'label'				=> '',
				'official_index'	=> 13
			)
		);

		// update the global variable
		global $zodiacpress_options;
		$zodiacpress_options = $options;
		update_option( 'zodiacpress_settings', $options );

		// Get actual value of ZP_Birth_Report::get_cleared_planets()
		$object = new ZP_Birth_Report( $this->chart, array( 'unknown_time' => '' ) );
		$method = ZP_Helper::get_private_method( 'ZP_Birth_Report', 'get_cleared_planets' );
 		$actual = $method->invokeArgs( $object, array( $key ) );

		$this->assertInternalType( 'array', $actual );
		$this->assertCount( 4, $actual );
		foreach ( $options[ $key ] as $expected ) {
			$this->assertArrayHasKey( $expected['official_index'], $actual );	
		}
	}

	/**
	 * Test ZP_Birth_Report::get_cleared_planets() for Aspects
	 */
	public function test_get_cleared_planets_aspects() {

		$key = 'enable_planet_aspects';
		$options = get_option( 'zodiacpress_settings' );

		// Set some default enabled for Aspects
		$options[ $key ] = array(
			array(
				'id'				=> 'sun',
				'label'				=> '',
				'official_index'	=> 0
			),
			array(
				'id'				=> 'moon',
				'label'				=> '',
				'official_index'	=> 1
			),
			array(
				'id'				=> 'saturn',
				'label'				=> '',
				'official_index'	=> 6
			),
			array(
				'id'				=> 'pof',
				'label'				=> '',
				'official_index'	=> 13
			),
			array(
				'id'				=> 'asc',
				'label'				=> '',
				'official_index'	=> 15
			)			
		);

		// update the global variable
		global $zodiacpress_options;
		$zodiacpress_options = $options;
		update_option( 'zodiacpress_settings', $options );

		// Get actual value of ZP_Birth_Report::get_cleared_planets()
		$object = new ZP_Birth_Report( $this->chart, array( 'unknown_time' => '' ) );
		$method = ZP_Helper::get_private_method( 'ZP_Birth_Report', 'get_cleared_planets' );
 		$actual = $method->invokeArgs( $object, array( $key ) );

		$this->assertInternalType( 'array', $actual );
		$this->assertCount( 5, $actual );
		foreach ( $options[ $key ] as $expected ) {
			$this->assertArrayHasKey( $expected['official_index'], $actual );	
		}
	}

	/**
	 * Test the ZP_Birth_Report::enabled_planets_in_signs property with only some planets enabled.
	 */
	public function test_enabled_planets_in_signs_partial() {

		$expected = array( 'asc', 'sun', 'moon', 'saturn', 'pof' );

		// Get calculated planets_in_signs
		$zp_object = new ZP_Birth_Report( $this->chart, array( 'unknown_time' => '' ) );		
		$property = ZP_Helper::get_private_property( 'ZP_Birth_Report', 'enabled_planets_in_signs' );
 		$calculated_planets_in_signs = $property->getValue( $zp_object );

		$this->assertInternalType( 'array', $calculated_planets_in_signs );
		$this->assertCount( 5, $calculated_planets_in_signs );

		// Each $expected should be the prefix for a $calculated_planets_in_signs[id]
		$i = 0;
		foreach ( $expected as $x ) {
			$this->assertStringStartsWith( $x . '_', $calculated_planets_in_signs[ $i++ ]['id'] );
					
		}
	}

	/**
	 * Test the ZP_Birth_Report::enabled_planets_in_signs property with unknown birth time
	 */
	public function test_enabled_planets_in_signs_unknown_time() {

		$this->chart->unknown_time = 'on';

		$expected = array( 'sun', 'saturn' );

		// Get calculated planets_in_signs
		$zp_object	= new ZP_Birth_Report( $this->chart, array( 'unknown_time' => 'on' ) );		
		$property	= ZP_Helper::get_private_property( 'ZP_Birth_Report', 'enabled_planets_in_signs' );
		$calculated_planets_in_signs = $property->getValue( $zp_object );

		$this->assertInternalType( 'array', $calculated_planets_in_signs );
		$this->assertCount( 2, $calculated_planets_in_signs );

		// Each $expected should be the prefix for a $calculated_planets_in_signs[id]
		$i = 0;
		foreach ( $expected as $x ) {
			$this->assertStringStartsWith( $x . '_', $calculated_planets_in_signs[ $i++ ]['id'] );
			
		}

	}

	/**
	 * Test the ZP_Birth_Report::enabled_planets_in_signs property with all planets enabled.
	 */
	public function test_enabled_planets_in_signs() {

		// Enable all planets in signs.
		$options = get_option( 'zodiacpress_settings' );
		$options['enable_planet_signs'] = zp_get_planets();
		// Update the settings
		global $zodiacpress_options;
		$zodiacpress_options = $options;
		update_option( 'zodiacpress_settings', $options );

		include dirname( __FILE__ ) . '/helper-expected-report.php';

		foreach ( $this->charts as $person => $chart ) {

			// Get calculated planets_in_signs
			$zp_object = new ZP_Birth_Report( $chart, array( 'unknown_time' => '' ) );		
			$property = ZP_Helper::get_private_property( 'ZP_Birth_Report', 'enabled_planets_in_signs' );
			$calculated_planets_in_signs = $property->getValue( $zp_object );

			for ( $i = 0; $i <= 16; $i++ ) {

				// Test the id element 
				$actual	= $calculated_planets_in_signs[ $i ]['id'];
				$expected = $expected_planets_in_signs[ $person ][ $i ];
				$this->assertEquals( $expected, $actual, 'Wrong "enabled_planets_in_signs[id]" for Person ' . $person . ', key ' . $i );

				// Test the zodiacal_dms element 
				$actual	= $calculated_planets_in_signs[ $i ]['zodiacal_dms'];
				$expected = $expected_zodiacal_dms[ $person ][ $i ];
				$this->assertEquals( $expected, $actual, 'Wrong zodiac sign dms for Person ' . $person . ', key ' . $i . ', ID: ' . $calculated_planets_in_signs[ $i ]['id'] );
			}

		}
	}

	/**
	 * Test enabled_aspects
	 */
	public function test_enabled_aspects() {

		$options = get_option( 'zodiacpress_settings' );

		// Enable some Aspects
		$options['enable_aspects'] = array(
			array(
				'id'				=> 'conjunction',
				'label'				=> '',
				'official_index'	=> 0
			),
			array(
				'id'				=> 'square',
				'label'				=> '',
				'official_index'	=> 2
			),
	 		array(
	 			'id'				=> 'quincunx',
	 			'label'				=> '',
	 			'official_index'	=> 4 ),
	 		array(
	 			'id'				=> 'opposition',
	 			'label'				=> '',
	 			'official_index'	=> 5 ),
	 		);


		// Set some enabled Planets for Aspects
		$options['enable_planet_aspects'] = array(
			array(
				'id'				=> 'sun',
				'label'				=> '',
				'official_index'	=> 0
			),
			array(
				'id'				=> 'moon',
				'label'				=> '',
				'official_index'	=> 1
			),
			array(
				'id'				=> 'mercury',
				'label'				=> '',
				'official_index'	=> 2
			),
			array(
				'id'				=> 'jupiter',
				'label'				=> '',
				'official_index'	=> 5
			),						
			array(
				'id'				=> 'saturn',
				'label'				=> '',
				'official_index'	=> 6
			),
			array(
				'id'				=> 'pluto',
				'label'				=> '',
				'official_index'	=> 9
			),
			array(
				'id'				=> 'nn',
				'label'				=> '',
				'official_index'	=> 12
			),
			array(
				'id'				=> 'pof',
				'label'				=> '',
				'official_index'	=> 13
			),
			array(
				'id'				=> 'asc',
				'label'				=> '',
				'official_index'	=> 15
			),
			array(
				'id'				=> 'mc',
				'label'				=> '',
				'official_index'	=> 16
			),			
		);

		// update the global variable.
		global $zodiacpress_options;
		$zodiacpress_options = $options;
		update_option( 'zodiacpress_settings', $options );

		$expected = array(
			'moon_square_nn',
			'mercury_quincunx_jupiter',
			'mercury_square_saturn',
			'mercury_opposition_pof',
			'mercury_quincunx_asc',
			'saturn_square_pluto',
			'saturn_square_pof',
			'saturn_quincunx_mc',
			'pluto_conjunction_pof',
			'asc_square_mc'
			);

		// Get actual value of ZP_Birth_Report::enabled_aspects
		$zp_object = new ZP_Birth_Report( $this->chart, array( 'unknown_time' => '' ) );		
		$property = ZP_Helper::get_private_property( 'ZP_Birth_Report', 'enabled_aspects' );
 		$actual = $property->getValue( $zp_object );

		$this->assertInternalType( 'array', $actual );
		$actual_aspects_list = wp_list_pluck( $actual, 'id' );
		foreach ( $expected as $expect ) {
			$this->assertContains( $expect, $actual_aspects_list );
		}
		$this->assertCount( 10, $actual );

	}

	/**
	 * Test enabled_aspects with unknown birth time
	 */
	public function test_enabled_aspects_unknown_birth_time() {

		$this->chart->unknown_time = 'on';

		$expected = array(
			// 'moon_square_nn',
			'mercury_quincunx_jupiter',
			'mercury_square_saturn',
			// 'mercury_opposition_pof',
			// 'mercury_quincunx_asc',
			'saturn_square_pluto',
			// 'saturn_square_pof',
			// 'saturn_quincunx_mc',
			// 'pluto_conjunction_pof',
			// 'asc_square_mc'
			);

		// Get actual value of ZP_Birth_Report::enabled_aspects
		$zp_object = new ZP_Birth_Report( $this->chart, array( 'unknown_time' => 'on' ) );		
		$property = ZP_Helper::get_private_property( 'ZP_Birth_Report', 'enabled_aspects' );
 		$actual = $property->getValue( $zp_object );

		$this->assertInternalType( 'array', $actual );
		$this->assertCount( 3, $actual );

		$actual_aspects_list = wp_list_pluck( $actual, 'id' );
		foreach ( $expected as $expect ) {
			$this->assertContains( $expect, $actual_aspects_list );
		}

	}
	/**
	 * Test the ZP_Birth_Report::enabled_planets_in_houses property with only some planets enabled.
	 */
	public function test_enabled_planets_in_houses_partial() {

		$expected = array( 'sun', 'moon', 'saturn', 'pof' );

		// Get calculated planets_in_houses
		$zp_object = new ZP_Birth_Report( $this->chart, array( 'unknown_time' => '' ) );		
		$property = ZP_Helper::get_private_property( 'ZP_Birth_Report', 'enabled_planets_in_houses' );
		$calculated_planets_in_houses = $property->getValue( $zp_object );

		$this->assertInternalType( 'array', $calculated_planets_in_houses );
		$this->assertCount( 4, $calculated_planets_in_houses );

		// Each $expected should be the prefix for a $calculated_planets_in_houses[id]
		$i = 0;
		foreach ( $expected as $x ) {
			$this->assertStringStartsWith( $x . '_', $calculated_planets_in_houses[ $i++ ]['id'] );
				
		}

	}

	/**
	 * Test the ::planet_in_next_house() method
	 */
	public function test_planet_in_next_house() {

		$planet	= array(
				'id'	=> 'moon',
				'label'	=> 'Moon'
				);
										
		// Get private method ZP_Birth_Report::planet_in_next_house()
		$object = new ZP_Birth_Report( $this->chart, array( 'unknown_time' => '' ) );
		$method = ZP_Helper::get_private_method( 'ZP_Birth_Report', 'planet_in_next_house' );

		// For expected house 1
		$house_num	= '12';
 		$actual = $method->invokeArgs( $object, array( $planet, $house_num ) );
 		$this->assertEquals( 'moon_1', $actual[0] );
		$this->assertEquals( 'Moon in First House', $actual[1] );

		// For expected house 2
		$house_num	= '1';
		$actual = $method->invokeArgs( $object, array( $planet, $house_num ) );
 		$this->assertEquals( 'moon_2', $actual[0] );
		$this->assertEquals( 'Moon in Second House', $actual[1] );

		// For expected house 11
		$house_num	= '10';
		$actual = $method->invokeArgs( $object, array( $planet, $house_num ) );
 		$this->assertEquals( 'moon_11', $actual[0] );
		$this->assertEquals( 'Moon in Eleventh House', $actual[1] );

		// For expected house 12
		$house_num	= '11';
		$actual = $method->invokeArgs( $object, array( $planet, $house_num ) );
 		$this->assertEquals( 'moon_12', $actual[0] );
		$this->assertEquals( 'Moon in Twelfth House', $actual[1] );

	}
	
}