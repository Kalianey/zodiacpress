<?php
class Test_Orbs extends WP_UnitTestCase {

	/**
	 * Test setting custom orbs for aspects.
	 */
	public function test_custom_orbs() {

		$options = get_option( 'zodiacpress_settings' );

		// Enable all Aspects

		$options['enable_aspects'] = array(
			array(
				'id'				=> 'conjunction',
				'label'				=> '',
				'official_index'	=> 0
			),
			array(
				'id'				=> 'sextile',
				'label'				=> '',
				'official_index'	=> 1
			),
			array(
				'id'				=> 'square',
				'label'				=> '',
				'official_index'	=> 2
			),
			array(
				'id'				=> 'trine',
				'label'				=> '',
				'official_index'	=> 3
			),			
	 		array(
	 			'id'				=> 'quincunx',
	 			'label'				=> '',
	 			'official_index'	=> 4 ),
	 		array(
	 			'id'				=> 'opposition',
	 			'label'				=> '',
	 			'official_index'	=> 5 )
	 		);

		// Enable all Planets for Aspects

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
				'id'				=> 'venus',
				'label'				=> '',
				'official_index'	=> 3
			),
			array(
				'id'				=> 'mars',
				'label'				=> '',
				'official_index'	=> 4
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
				'id'				=> 'uranus',
				'label'				=> '',
				'official_index'	=> 7
			),
			array(
				'id'				=> 'neptune',
				'label'				=> '',
				'official_index'	=> 8
			),
			array(
				'id'				=> 'pluto',
				'label'				=> '',
				'official_index'	=> 9
			),
			array(
				'id'				=> 'chiron',
				'label'				=> '',
				'official_index'	=> 10
			),
			array(
				'id'				=> 'lilith',
				'label'				=> '',
				'official_index'	=> 11
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
				'id'				=> 'vertex',
				'label'				=> '',
				'official_index'	=> 14
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

		// Set some custom orbs...

		// Set all quincunx to 2
		// Set all sextile to 5
		// Set all Vertex to 1
		// Set all NN to 1.5

		$options['orb_conjunction_sun'] = 8;
		$options['orb_conjunction_mercury'] = 8;
		$options['orb_conjunction_venus'] = 8;
		$options['orb_conjunction_mars'] = 8;
		$options['orb_conjunction_saturn'] = 8;
		$options['orb_conjunction_uranus'] = 8;
		$options['orb_conjunction_neptune'] = 8;
		$options['orb_conjunction_pluto'] = 8;
		$options['orb_conjunction_chiron'] = 8;
		$options['orb_conjunction_lilith'] = 8;
		$options['orb_conjunction_nn'] = 1.5;
		$options['orb_conjunction_pof'] = 8;
		$options['orb_conjunction_vertex'] = 1;
		$options['orb_conjunction_asc'] = 8;
		$options['orb_conjunction_mc'] = 8;
		$options['orb_sextile_sun'] = 5;
		$options['orb_sextile_moon'] = 5;
		$options['orb_sextile_mercury'] = 5;
		$options['orb_sextile_venus'] = 5;
		$options['orb_sextile_mars'] = 5;
		$options['orb_sextile_jupiter'] = 5;
		$options['orb_sextile_saturn'] = 5;
		$options['orb_sextile_uranus'] = 5;
		$options['orb_sextile_neptune'] = 5;
		$options['orb_sextile_pluto'] = 5;
		$options['orb_sextile_chiron'] = 5;
		$options['orb_sextile_lilith'] = 5;
		$options['orb_sextile_nn'] = 1.5;
		$options['orb_sextile_pof'] = 5;
		$options['orb_sextile_vertex'] = 1;
		$options['orb_sextile_asc'] = 5;
		$options['orb_sextile_mc'] = 5;
		$options['orb_square_sun'] = 8;
		$options['orb_square_moon'] = 8;
		$options['orb_square_mercury'] = 8;
		$options['orb_square_venus'] = 8;
		$options['orb_square_mars'] = 8;
		$options['orb_square_jupiter'] = 8;
		$options['orb_square_saturn'] = 8;
		$options['orb_square_uranus'] = 8;
		$options['orb_square_neptune'] = 8;
		$options['orb_square_pluto'] = 8;
		$options['orb_square_chiron'] = 8;
		$options['orb_square_lilith'] = 8;
		$options['orb_square_nn'] = 1.5;
		$options['orb_square_pof'] = 8;
		$options['orb_square_vertex'] = 1;
		$options['orb_square_asc'] = 8;
		$options['orb_square_mc'] = 8;
		$options['orb_trine_sun'] = 8;
		$options['orb_trine_moon'] = 8;
		$options['orb_trine_mercury'] = 8;
		$options['orb_trine_venus'] = 8;
		$options['orb_trine_mars'] = 8;
		$options['orb_trine_jupiter'] = 8;
		$options['orb_trine_saturn'] = 8;
		$options['orb_trine_uranus'] = 8;
		$options['orb_trine_neptune'] = 8;
		$options['orb_trine_pluto'] = 8;
		$options['orb_trine_chiron'] = 8;
		$options['orb_trine_lilith'] = 8;
		$options['orb_trine_nn'] = 1.5;
		$options['orb_trine_pof'] = 8;
		$options['orb_trine_vertex'] = 1;
		$options['orb_trine_asc'] = 8;
		$options['orb_trine_mc'] = 8;
		$options['orb_quincunx_pluto'] = 2;
		$options['orb_quincunx_nn'] = 1.5;
		$options['orb_quincunx_pof'] = 2;
		$options['orb_quincunx_vertex'] = 1;
		$options['orb_quincunx_asc'] = 2;
		$options['orb_quincunx_mc'] = 2;
		$options['orb_opposition_sun'] = 8;
		$options['orb_opposition_moon'] = 8;
		$options['orb_opposition_mercury'] = 8;
		$options['orb_opposition_venus'] = 8;
		$options['orb_opposition_mars'] = 8;
		$options['orb_opposition_jupiter'] = 8;
		$options['orb_opposition_saturn'] = 8;
		$options['orb_opposition_uranus'] = 8;
		$options['orb_opposition_neptune'] = 8;
		$options['orb_opposition_pluto'] = 8;
		$options['orb_opposition_chiron'] = 8;
		$options['orb_opposition_lilith'] = 8;
		$options['orb_opposition_nn'] = 1.5;
		$options['orb_opposition_pof'] = 8;
		$options['orb_opposition_vertex'] = 1;
		$options['orb_opposition_asc'] = 8;
		$options['orb_opposition_mc'] = 8;
		$options['orb_conjunction_moon'] = 8;
		$options['orb_conjunction_jupiter'] = 8;
		$options['orb_quincunx_sun'] = 2;
		$options['orb_quincunx_moon'] = 2;
		$options['orb_quincunx_mercury'] = 2;
		$options['orb_quincunx_venus'] = 2;
		$options['orb_quincunx_mars'] = 2;
		$options['orb_quincunx_jupiter'] = 2;
		$options['orb_quincunx_saturn'] = 2;
		$options['orb_quincunx_uranus'] = 2;
		$options['orb_quincunx_neptune'] = 2;
		$options['orb_quincunx_chiron'] = 2;
		$options['orb_quincunx_lilith'] = 2;


		// update the global variable
		global $zodiacpress_options;
		$zodiacpress_options = $options;
		update_option( 'zodiacpress_settings', $options );

		// Michael Jackson's aspects, total = 65

		$expected = array(
			// 'sun_sextile_jupiter',
			'sun_sextile_neptune',
			'sun_conjunction_pluto',
			'sun_conjunction_pof',
			'sun_opposition_asc',
			// 'moon_quincunx_venus',
			// 'moon_sextile_mars',
			'moon_square_saturn',
			'moon_quincunx_uranus',
			// 'moon_opposition_vertex',
			'moon_conjunction_asc',
			'moon_square_mc',
			'mercury_square_mars',
			'mercury_sextile_jupiter',
			'mercury_trine_saturn',
			// 'mercury_sextile_neptune',
			'mercury_conjunction_pluto',
			'mercury_opposition_chiron',
			'mercury_trine_lilith',
			// 'mercury_sextile_nn',
			'mercury_conjunction_pof',
			'mercury_trine_mc',
			'venus_square_mars',
			'venus_trine_saturn',
			'venus_conjunction_uranus',
			'venus_opposition_chiron',
			'venus_trine_lilith',
			// 'venus_sextile_nn',
			// 'venus_quincunx_asc',
			'venus_trine_mc',
			// 'mars_quincunx_jupiter',
			// 'mars_quincunx_saturn',
			'mars_square_chiron',
			'mars_quincunx_nn',
			'mars_trine_vertex',
			// 'mars_quincunx_mc',
			'jupiter_conjunction_neptune',
			'jupiter_sextile_pluto',
			'jupiter_opposition_lilith',
			// 'jupiter_conjunction_nn',
			'jupiter_sextile_pof',
			'saturn_trine_uranus',
			'saturn_sextile_chiron',
			'saturn_trine_lilith',
			// 'saturn_sextile_nn',
			// 'saturn_square_vertex',
			'saturn_conjunction_mc',
			'uranus_opposition_chiron',
			'uranus_trine_lilith',
			// 'uranus_quincunx_asc',
			'uranus_trine_mc',
			'neptune_sextile_pluto',
			'neptune_sextile_pof',
			'neptune_trine_asc',
			'pluto_conjunction_pof',
			'pluto_opposition_asc',
			'chiron_sextile_lilith',
			// 'chiron_trine_nn',
			// 'chiron_quincunx_vertex',
			'chiron_sextile_mc',
			// 'lilith_opposition_nn',
			'lilith_quincunx_vertex',
			'lilith_trine_mc',
			// 'nn_sextile_mc',
			// 'vertex_square_mc'
		);

		// Exclude aspects out of orb

		$excluded = array(
			'sun_sextile_jupiter',
			'moon_quincunx_venus',
			'moon_sextile_mars',
			'moon_opposition_vertex',
			'mercury_sextile_neptune',
			'mercury_sextile_nn',
			'venus_sextile_nn',
			'venus_quincunx_asc',
			'mars_quincunx_jupiter',
			'mars_quincunx_saturn',
			'mars_quincunx_mc',
			'jupiter_conjunction_nn',
			'saturn_sextile_nn',
			'saturn_square_vertex',
			'uranus_quincunx_asc',
			'chiron_quincunx_vertex',
			'chiron_trine_nn',
			'lilith_opposition_nn',
			'nn_sextile_mc',
			'vertex_square_mc'
			);

		$this->charts	= ZP_Helper::create_charts();
		$chart	= $this->charts[1];

		// Get actual value of ZP_Birth_Report::enabled_aspects
		$zp_object = new ZP_Birth_Report( $chart, array( 'unknown_time' => '' ) );		
		$property = ZP_Helper::get_private_property( 'ZP_Birth_Report', 'enabled_aspects' );
 		$actual = $property->getValue( $zp_object );

 		$actual_aspects_list = wp_list_pluck( $actual, 'id' );

		$this->assertInternalType( 'array', $actual );

		foreach ( $expected as $expect ) {
			$this->assertContains( $expect, $actual_aspects_list );
		}

		$this->assertCount( 45, $actual );

		foreach ( $excluded as $exclude ) {
			$this->assertNotContains( $exclude, $actual_aspects_list );
		}

	}
}