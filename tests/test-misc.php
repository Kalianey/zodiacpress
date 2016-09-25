<?php
class Test_Misc extends WP_UnitTestCase {

	public function test_all_interps_options_names() {

		require_once ZODIACPRESS_PATH . 'includes/admin/settings/register-interpretations.php';

		$expected = array(
			'zp_natal_planets_in_signs',
			'zp_natal_planets_in_houses',
			'zp_natal_aspects_main',
			'zp_natal_aspects_moon',
			'zp_natal_aspects_mercury',
			'zp_natal_aspects_venus',
			'zp_natal_aspects_mars',
			'zp_natal_aspects_jupiter',
			'zp_natal_aspects_saturn',
			'zp_natal_aspects_uranus',
			'zp_natal_aspects_neptune',
			'zp_natal_aspects_pluto',
			'zp_natal_aspects_chiron',
			'zp_natal_aspects_lilith',
			'zp_natal_aspects_nn',
			'zp_natal_aspects_pof',
			'zp_natal_aspects_vertex',
			'zp_natal_aspects_asc',
			'zp_natal_aspects_mc'
			);
		$actual = zp_get_all_interps_options_names();
		$this->assertInternalType( 'array', $actual );
		$this->assertCount( 19, $actual );
		foreach ( $expected as $v ) {
			$this->assertContains( $v, $actual );
		}
	}

	
}