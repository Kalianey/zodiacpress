<?php
/**
 * ZP_Chart class
 *
 * @package     ZodiacPress
 * @copyright   Copyright (c) 2016-2017, Isabel Castillo
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * The class used to generate astrological data for a given moment in time.
 */
final class ZP_Chart {

	/**
	 * The Unix Epoch time for this chart.
	 */
	public $unix_timestamp;

	/**
	 * Universal Date for this chart
	 */
	public $ut_date;

	/**
	 * Universal Time for this chart
	 */
	public $ut_time;

	/**
	 * Latitude decimal of the location for this chart
	 */
	public $latitude;

	/**
	 * Longitude decimal of the location for this chart
	 */
	public $longitude;

	/**
	 * The chart's house cusps in logitude decimal.
	 *
	 * @var array
	 */
	public $cusps = array();

	/**
	 * The positions of the planets and points in logitude decimal.
	 *
	 * @var array
	 */
	public $planets_longitude = array();

	/**
	 * The house position number of the planets and points in houses.
	 *
	 * @var array
	 */
	public $planets_house_numbers = array();

	/**
	 * Planets list showing whether each planet is conjunct the next house cusp.
	 *
	 * @var array
	 */
	public $conjunct_next_cusp = array();

	/**
	 * The speed of the planets and points in longitude decimal degrees per day
	 *
	 * @var array
	 */
	public $planets_speed = array();

	/**
	 * The house system used for this chart's house cusps and planets in houses.
	 */
	public $house_system;

	/**
	 * The sidereal method for this chart, if this is a sidereal chart.
	 */
	public $sidereal;

	/**
	 * The calculated Ayanamsa, if this is a sidereal chart.
	 */
	public $ayanamsa;

	/**
	 * Whether this chart is for an unknown birth time.
	 */
	public $unknown_time = false;

	/**
	 * Retrieve ZP_Chart instance.
	 *
	 * @static
	 * @access public
	 *
	 * @param array $moment Validated form data for this chart.
	 * @return ZP_Chart|false Chart object, false otherwise.
	 */
	public static function get_instance( $moment = array() ) {
		if ( ! $moment ) {
			return false;
		}
		return new ZP_Chart( $moment );
	}

	/**
	 * Constructor.
	 *
	 * @param array $moment Validated form data
	 */
	public function __construct( $moment ) {
		// Set Universal time and date for this chart
		$this->setup_ut( $moment );
		$this->latitude		= $moment['zp_lat_decimal'];
		$this->longitude	= $moment['zp_long_decimal'];
		$this->sidereal		= $moment['sidereal'];
		if ( $moment['unknown_time'] ) {
			$this->unknown_time = true;
		}
		// Let the shortcode parameter for house system override the default house system
		$this->house_system	= $moment['house_system'] ?
							$moment['house_system'] :
							apply_filters( 'zp_default_house_system', 'P' );
		$this->setup_chart();

	}

	/**
	 * Given the moment data, set the Universal time and date properties.
	 * @param array $moment Validated form data
	 */
	private function setup_ut( $moment ) {
		if ( ! is_array( $moment ) ) {
			return false;
		}

		// Convert to UT
		// Adjust date and time for minus hour due to timezone offset taking the hour negative.
		$offset = $moment['zp_offset_geo'];
			
		if ( $offset >= 0 ) {
			$whole		= floor( $offset );
			$fraction	= $offset - floor( $offset );
		} else {
			$whole		= ceil( $offset );
			$fraction	= $offset - ceil( $offset );
		}
			
		$hour	= $moment['hour'] - $whole;
		$minute	= $moment['minute'] - ( $fraction * 60 );
		$second = '00';

		// mktime() uses whatever zone its server wants, so force it to use UTC here
		date_default_timezone_set('UTC');
		$this->unix_timestamp = mktime( (int) $hour, (int) $minute, (int) $second, (int) $moment['month'], (int) $moment['day'], (int) $moment['year'] );

		$this->ut_date = strftime( "%d.%m.%Y", $this->unix_timestamp );
		$this->ut_time = strftime( "%H:%M:%S", $this->unix_timestamp );

		return true;
	}

	/**
	 * Set up the chart data.
	 */
	private function setup_chart() {

		// Ephemeris gives wrong calculations for Whole Sign houses, so query it as Placidus, then calculate Whole Sign houses manually.
		$final_house_system = ( 'W' == $this->house_system ) ? 'P' : $this->house_system;

		// Args for the ephemeris query
		$args = array(
			'planets'		=> '0123456789DAt',
			'format'		=> 'ls',
			'house_system'	=> $final_house_system,
			'latitude'		=> $this->latitude,
			'longitude'		=> $this->longitude,
			'ut_date'		=> $this->ut_date,
			'ut_time'		=> $this->ut_time
		);

		// Is this a sidereal chart?
		if ( $this->sidereal ) {

			// Set the Ayanamsa property so we can show it in the report header
			$args['options']	= '-ay' . zp_get_sidereal_methods()[ $this->sidereal ]['id'] . ' -roundsec';
			$ephemeris			= new ZP_Ephemeris( $args );
			$ayanamsa_data		= $ephemeris->query();
			$row				= explode( ',', $ayanamsa_data[0] );
			$this->ayanamsa		= trim( $row[1] );

			// Set the sidereal flag for the chart query
			$args['options'] = '-sid' . zp_get_sidereal_methods()[ $this->sidereal ]['id'];
		}

		$ephemeris	= new ZP_Ephemeris( $args );
		$chart		= $ephemeris->query();

		if ( empty( $chart ) ) {
			return false;
		}
		
		// Set up chart properties from raw chart data.
		foreach ( $chart as $key => $line ) {
			$row = explode( ',', $line );

			// Set up core planet properties
			if ( $key <= 12 ) {
				$this->planets_longitude[ $key ]	= trim( $row[0] );
				$this->planets_speed[ $key ]		= trim( $row[1] );
			}

			/** 
			 * The ephemeris output array has Vertex at index 28, ASC at index 25, and MC at 26.
			 * Move them up to 14, 15, 16.
			 */

			// Capture the Vertex longitude
			if ( 28 == $key ) {
				$this->planets_longitude[14] = trim( $row[0] );
			}

			// Capture the Asc longitude
			if ( 25 == $key ) {
				$this->planets_longitude[15] = trim( $row[0] );
			}		

			// Capture the MC longitude
			if ( 26 == $key ) {
				$this->planets_longitude[16] = trim( $row[0] );
			}

			// Set up house cusps, but not for Whole Sign houses because Swiss Ephemeris gives wrong Whole Sign cusp calculations.
			if ( 'W' != $this->house_system ) {
				// counter to help set house cusp array keys at 1-12, not 0-11
				if ( ! isset( $i ) ) {
					$i = 0;
				}
				// Capture the house cusps, which ephemeris outputs at index 13-24
				if ( 12 < $key && $key < 25 ) {
					$this->cusps[ ++$i ] = trim( $row[0] );
				}
			}

		} // Finished with raw chart data.

		// Do Whole Sign cusps by hand because Swiss Ephemeris gives wrong Whole Sign calculations.
		if ( 'W' == $this->house_system ) {
			$ascendant			= $this->planets_longitude[15];
			$whole_sign_house_1	= floor( $ascendant / 30 ) * 30;
			for ( $x = 1; $x <= 12; $x++ ) {
				$this_house = $whole_sign_house_1 + ( 30 * ( $x - 1 ) );
				$this->cusps[ $x ] = ( $this_house >= 360 ) ? ( $this_house - 360 ) : $this_house;
			}
		}

		// Add Part of Fortune longitude at index 13
		$this->planets_longitude[13] = $this->calculate_pof( $this->planets_longitude[15], $this->planets_longitude[0], $this->planets_longitude[1] );

		// Order by keys
		ksort( $this->planets_longitude );

		// Set up house number position for planets
		for ( $x = 0; $x <= 14; $x++ ) {
			$this->planets_house_numbers[ $x ] = zp_get_planet_house_num( $this->planets_longitude[ $x ], $this->cusps );
	
		}
		
		$this->setup_conjunct_next_cusp();
		do_action( 'zp_setup_chart', $this );
	}

	/**
	 * Calculate the Part of Fortune (POF)
	 * @param string $asc ASC's longitude decimal
	 * @param string $sun Sun's longitude decimal
	 * @param string $moon Moon's longitude decimal
	 * @return string The POF position in longitude decimal
	 */
	private function calculate_pof( $asc = 0, $sun = 0, $moon = 0 ) {

		$desc = zp_calculate_descendant( $asc );

		// Is this a day chart or a night chart?
		if ( $asc > $desc ) {
			if ( $sun <= $asc && $sun > $desc ) {
				$day_chart = true;
			} else {
				$day_chart = false;
			}
		} else {
			if ( $sun > $asc && $sun <= $desc ) {
				$day_chart = false;
			} else {
				$day_chart = true;
			}
		}

		if ( true == $day_chart ) {
			// The day formula is: ASC + Moon - Sun
			$pof = $asc + $moon - $sun;
		} else {
			// The night formula: ASC - Moon + Sun
			$pof = $asc - $moon + $sun;
		}

		if ( $pof >= 360 ) {
			$pof = $pof - 360;
		}

		if ( $pof < 0 ) {
			$pof = $pof + 360;
		}

		return $pof;
	}

	/**
	 * Set up the conjunct_next_cusp property.
	 */
	private function setup_conjunct_next_cusp() {

		foreach ( $this->planets_longitude as $key => $p_long ) {

			if ( isset( $this->planets_house_numbers[ $key ] ) ) {

				$this->conjunct_next_cusp[ $key ] = zp_conjunct_next_cusp( $key, $p_long, $this->planets_house_numbers[ $key ], $this->cusps );
			}
		}
	}
}