<?php
/**
 * Astro Functions
 *
 * Functions related to astrological points and things.
 *
 * @package     ZodiacPress
 * @subpackage  Functions/Astro
 * @copyright   Copyright (c) 2016, Isabel Castillo
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Returns a list of planets/points.
 *
 * @param bool $houses Whether to limit list to planets that support houses
 * @param int $include Limit list to this number of planets from the top.
 *
 * @return array of planets id and label. If no parameters passed, returns all planets.
 */
function zp_get_planets( $houses = '', $include = '' ) {

	$planets = apply_filters( 'zp_get_planets', array(
		array(
			'id'		=> 'sun',
			'label'		=> __( 'Sun', 'zodiacpress' ),
			'supports'	=> array( 'houses' )
		),
		array(
			'id'		=> 'moon',
			'label'		=> __( 'Moon', 'zodiacpress' ),
			'supports'	=> array( 'houses', 'birth_time_required' )
		),
		array(
			'id'		=> 'mercury',
			'label'		=> __( 'Mercury', 'zodiacpress' ),
			'supports'	=> array( 'houses' )
		),
		array(
			'id'		=> 'venus',
			'label'		=> __( 'Venus', 'zodiacpress' ),
			'supports'	=> array( 'houses' )
		),
		array(
			'id'		=> 'mars',
			'label'		=> __( 'Mars', 'zodiacpress' ),
			'supports'	=> array( 'houses' )
		),
		array(
			'id'		=> 'jupiter',
			'label'		=> __( 'Jupiter', 'zodiacpress' ),
			'supports'	=> array( 'houses' )
		),
		array(
			'id'		=> 'saturn',
			'label'		=> __( 'Saturn', 'zodiacpress' ),
			'supports'	=> array( 'houses' )
		),
		array(
			'id'		=> 'uranus',
			'label'		=> __( 'Uranus', 'zodiacpress' ),
			'supports'	=> array( 'houses' )
		),
		array(
			'id'		=> 'neptune',
			'label'		=> __( 'Neptune', 'zodiacpress' ),
			'supports'	=> array( 'houses' )
		),
		array(
			'id'		=> 'pluto',
			'label'		=> __( 'Pluto', 'zodiacpress' ),
			'supports'	=> array( 'houses' )
		),
		array(
			'id'		=> 'chiron',
			'label'		=> __( 'Chiron', 'zodiacpress' ),
			'supports'	=> array( 'houses' )
		),
		array(
			'id'		=> 'lilith',
			'label'		=> __( 'Lilith', 'zodiacpress' ),
			'supports'	=> array( 'houses' )
		),
		array(
			'id'		=> 'nn',
			'label'		=> __( 'North Node', 'zodiacpress' ),
			'supports'	=> array( 'houses' )
		),
		array(
			'id'		=> 'pof',
			'label'		=> __( 'Part of Fortune', 'zodiacpress' ),
			'supports'	=> array( 'houses', 'birth_time_required' )
		),
		array(
			'id'		=> 'vertex',
			'label'		=> __( 'Vertex', 'zodiacpress' ),
			'supports'	=> array( 'houses', 'birth_time_required' )
		),
		array(
			'id'		=> 'asc',
			'label'		=> __( 'Ascendant', 'zodiacpress' ),
			'supports'	=> array( 'birth_time_required' )
		),
		array(
			'id'		=> 'mc',
			'label'		=> __( 'Midheaven', 'zodiacpress' ),
			'supports'	=> array( 'birth_time_required' )
		)// 16

	) );

	if ( $houses ) {
		foreach ( $planets as $p ) {
			if ( ! empty( $p['supports'] ) && in_array( 'houses', $p['supports'] ) ) {
				$house_planets[] = $p;
			}
		}
		$planets = $house_planets;
	}

	if ( $include ) {
		$include = (int) $include;
		$planets = array_slice( $planets, 0, $include );
	}

	return $planets;
}

/**
 * Return a list of available aspects.
 */
function zp_get_aspects() {
	$aspects = array(
		array(
			'id'		=> 'conjunction',
			'label'		=> __( 'Conjunction', 'zodiacpress' ),
			'numerical'	=> '0' ),
		array(
			'id'		=> 'sextile',
			'label'		=> __( 'Sextile', 'zodiacpress' ),
			'numerical'	=> 60 ),
		array(
			'id'		=> 'square',
			'label'		=> __( 'Square', 'zodiacpress' ),
			'numerical'	=> 90 ),
		array(
			'id'		=> 'trine',
			'label'		=> __( 'Trine', 'zodiacpress' ),
			'numerical'	=> 120 ),
		array(
			'id'		=> 'quincunx',
			'label'		=> __( 'Quincunx', 'zodiacpress' ),
			'numerical'	=> 150 ),
		array(
			'id'		=> 'opposition',
			'label'		=> __( 'Opposition', 'zodiacpress' ),
			'numerical'	=> 180 ),
	);
	
	return apply_filters( 'zp_get_aspects', $aspects );
}
/**
 * Return an array of the zodiac signs
 */
function zp_get_zodiac_signs() {
	$signs = array(
			array(
				'id'	=> 'aries',
				'label'	=> __( 'Aries', 'zodiacpress' ) ),
			array(
				'id'	=> 'taurus',
				'label'	=> __( 'Taurus', 'zodiacpress' ) ),
			array(
				'id'	=> 'gemini',
				'label'	=> __( 'Gemini', 'zodiacpress' ) ),
			array(
				'id'	=> 'cancer',
				'label'	=> __( 'Cancer', 'zodiacpress' ) ),
			array(
				'id'	=> 'leo',
				'label'	=> __( 'Leo', 'zodiacpress' ) ),
			array(
				'id'	=> 'virgo',
				'label'	=> __( 'Virgo', 'zodiacpress' ) ),
			array(
				'id'	=> 'libra',
				'label'	=> __( 'Libra', 'zodiacpress' ) ),
			array(
				'id'	=> 'scorpio',
				'label'	=> __( 'Scorpio', 'zodiacpress' ) ),
			array(
				'id'	=> 'sagittarius',
				'label'	=> __( 'Sagittarius', 'zodiacpress' ) ),
			array(
				'id'	=> 'capricorn',
				'label'	=> __( 'Capricorn', 'zodiacpress' ) ),
			array(
				'id'	=> 'aquarius',
				'label'	=> __( 'Aquarius', 'zodiacpress' ) ),
			array(
				'id'	=> 'pisces',
				'label'	=> __( 'Pisces', 'zodiacpress' ) )
	);
	return $signs;
}

/**
* Convert zodiac decimal longitude from 360 degrees notation into zodiac sign degrees, minutes, seconds
* @param zodiac decimal longitude in 360 degrees notation
* @return string zodiac sign degrees in 30 degrees notation, sign glyph, minutes, seconds
*/

function zp_get_zodiac_sign_dms( $longitude ) {

	// incoming $longitude should never reach 360
	if ( $longitude >= 360 ) {
		return 'ERROR 360: undefined';
	}

	$signs			= zp_get_zodiac_signs();
	$sign_num		= floor( $longitude / 30 );
	$pos_in_sign	= $longitude - ( $sign_num * 30 );
	$deg			= floor( $pos_in_sign );
	$full_min		= ( $pos_in_sign - $deg ) * 60;
	$min			= floor( $full_min );
	// 1st round to 2 decimal places in order to match astro.com's calculations
	// because PHP round(26.498) = 26, whereas astro.com rounds that to 27.
	$sec = round( round( ( $full_min - $min ) * 60, 2) );

	// Carry the 1
	if ( $sec >= 60 ) {
		$sec = $sec - 60;
		$min++;
	}
	if ( $min >= 60 ) {
		$min	= $min - 60;
		$deg++;
	}
	if ( $deg >= 30 ) {
		$deg = $deg - 30;
		// Increase the sign
		$sign_num = ( $sign_num > 10 ) ? '0' : ++$sign_num;
	}

	// Insert leading zero when needed
	if ( $min < 10 ) {
		$min = '0' . $min;
	}
	if ( $sec < 10 ) {
		$sec = '0' . $sec;
	}
	
	$maybe_space = ( $deg < 10 ) ? '&#160;' : '';

	if ( is_rtl() ) {
		$degrees = '&#176;' . zp_i18n_coordinates( $deg ) . $maybe_space;
	} else {
		$degrees = $maybe_space . zp_i18n_coordinates( $deg ) . '&#176;';
	}

	$glyph = '<span class="zp-icon-' . $signs[ $sign_num ]['id'] . '"> </span>';

	/* translators: Attention RTL languages. 6 placeholders are zodiac sign degrees, zodiac sign glyph, minutes, minutes symbol, seconds, seconds symbol. */
	$dms = sprintf( __( '%1$s %2$s %3$s%4$s %5$s%6$s', 'zodiacpress' ),
					$degrees,
					$glyph,
					zp_i18n_numbers_zeros( $min ),
					chr(39),
					zp_i18n_numbers_zeros( $sec ),
					chr(34)
	);

	return $dms;
}

/**
 * Get the house position, as a number 1-12, of a planet or point.
 *
 * @param $planet int The longitude decimal of the planet or point
 * @param $cusps array House cusps longitudes for a specific house system.
 *
 * @return int|bool The number of house for this planet, 1-12, or false upon error.
 */
function zp_get_planet_house_num( $planet, $cusps ) {

	if ( empty( $cusps ) || empty( $planet ) ) {
		return false;
	}

	$out = false;

	$pl = $planet + (1 / 36000);

	for ( $x = 1; $x <= 12; $x++ ) {

		// Check houses 1-11 when planet longitude is greater than the next cusp, as is the case with planets in Pisces when the next cusp is Aries.
		if ( $x < 12 && $cusps[ $x ] > $cusps[ $x + 1 ] ) {
			if ( ( $pl >= $cusps[ $x ] && $pl < 360 ) || ( $pl < $cusps[ $x + 1 ] && $pl >= 0 ) ) {
				$out = $x;
				continue;
			}
		}

		// Check house 12 when planet longitude is greater than the next cusp.
		if ( $x == 12 && ( $cusps[ $x ] > $cusps[1] ) ) {
			if ( ( $pl >= $cusps[ $x ] && $pl < 360 ) || ( $pl < $cusps[1] && $pl >= 0 ) ) {
				$out = $x;
			}
			continue;
		}

		// Check houses 1-11 when next cusp longitude is greater than this planet's longitude. Most cases fit here.
		if ( ( $x < 12 ) && ( $pl >= $cusps[ $x ] ) && ( $pl < $cusps[ $x + 1 ] ) ) {
			$out = $x;
			continue;
		}

		// Check house 12 when next cusp longitude is greater than this planet's longitude. Most cases fit here.
		if ( ( $pl >= $cusps[ $x ] ) && ( $pl < $cusps[1] ) && ( $x == 12 ) ) {
			$out = $x;
		}

	}
	return $out;
}

/**
 * Check if a planet is conjunct the next house cusp.
 *
 * @param int $p_key The planet key
 * @param int $p_long The planet longitude decimal
 * @param int $p_house The house number which the planet resides in.
 * @param array $cusps Cusps for a single house system
 * 
 * @return bool True if planet is conjunct to next house cusp, otherwise false.
 */
function zp_conjunct_next_cusp( $p_key = '', $p_long, $p_house, $cusps ) {

	if ( '' === $p_key || empty( $p_long ) || empty( $p_house ) || empty( $cusps ) ) {
		return false;
	}

	$orb = apply_filters( 'zp_orb_conjunct_next_cusp', 1.5 );

	$next_house = $p_house + 1;
	if ( 13 == $next_house ) {
		$next_house = 1;
	}

	// longitude of next house cusp, minus this planet longitude.
	$distance_to_next_cusp = $cusps[ $next_house ] - $p_long;

	// if answer is < 0, add 360.
	if ( $distance_to_next_cusp < 0 ) {
		$distance_to_next_cusp += 360;
	}

	return ( $distance_to_next_cusp <= $orb ) ? true : false;

}

/**
 * Calculate the Descendant longitude degrees for a chart
 * @param int $asc the Ascendant longitude degrees of the chart
 */
function zp_calculate_descendant( $asc ) {
	if ( empty( $asc ) ) {
		return false;
	}
	$desc = (int) $asc + 180;
	return ( $desc >= 360 ) ? ( $desc - 360 ) : $desc;
}
