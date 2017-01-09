<?php
/**
 * ZP_Ephemeris class
 *
 * @package     ZodiacPress
 * @copyright   Copyright (c) 2016-2017, Isabel Castillo
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.3
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * The class used to query the Swiss Ephemeris
 */
class ZP_Ephemeris {

	/**
	 * Planets and points to query for. Not needed when querying for only house cusps.
	 * @var string $planets Planet selection letters
	 */
	private $planets;

	/**
	 * The ephemeris output format, in sequence letters.
	 */
	private $format;

	/**
	 * The house option which tells the ephemeris to include house cusp calculations.
	 */
	private $house;

	/**
	 * Additional options for the ephemeris query
	 */
	private $options;

	/**
	 * Universal Date for this chart
	 */
	private $ut_date;

	/**
	 * Universal Time for this chart
	 */
	private $ut_time;


	/**
	 * Constructor.
	 *
	 * @param array $args Options for the ephemeris query
	 */
	public function __construct( $args = array() ) {

		$default = array(
			'planets'		=> '',
			'format'		=> '',
			'house_system'	=> '',
			'latitude'		=> '',
			'longitude'		=> '',
			'ut_date'		=> strftime( "%d.%m.%Y" ),
			'ut_time'		=> '',
			'options'		=> ''
		);

		$params = wp_parse_args( $args, $default );

		$this->ut_date	= $params['ut_date'];
		$this->planets	= $params['planets'];

		// Optional options
		$this->format	= $params['format'] ? ( '-f' . $params['format'] ) : '';
		$this->ut_time	= $params['ut_time'] ? ( '-ut' . $params['ut_time'] ) : '';
		$this->options	= $params['options'];

		$this->setup_house( $params['house_system'], $params['latitude'], $params['longitude'] );

	}

	/**
	 * Setup up the house option
	 */
	private function setup_house( $house_system, $latitude, $longitude ) {

		if (
			( ! empty( $house_system ) ) &&
			( ! empty( $latitude ) ) &&
			( ! empty( $longitude ) )
		) {

			/**
			* Adjust latitude/longitude coordinates for precision, to match astro.com's calculations since those are more widely accepted among the astrological community. 
			* For example, GeoNames' latitude for Miami = 25.77427, whereas astro.com's is 25.766666666667.
			* (Basically, it seems astro.com is ignoring seconds (and just using degree and minutes to make the decimal), or using less significant digits.)
			* While GeoNames is more accurate, astro.com's is more widely accepted, and our discrepancy would reduce precision (from astro.com) in house cusps and ASC/MC calculations by many seconds and possibly even minutes. So here, I recalculate the decimal using only degree and minutes, to ignore seconds in favor of precision with astro.com.
			*/
			$long_dm = zp_extract_degrees_parts( $longitude );
			$longitude_degree	= $long_dm[0];
			$longitude_minute	= $long_dm[1];
			$lat_dm = zp_extract_degrees_parts( $latitude );
			$latitude_degree	= $lat_dm[0];
			$latitude_minute	= $lat_dm[1];

			$east_west		= ( $longitude >= 0 ) ? '1' : '-1';
			$north_south	= ( $latitude >= 0 ) ? '1' : '-1';

			$longitude		= $east_west * ( $longitude_degree + ( $longitude_minute/ 60 ) );
			$latitude		= $north_south * ( $latitude_degree + ( $latitude_minute / 60 ) );

			$this->house = '-house' . $longitude . ',' . $latitude . ',' . $house_system;

		} else {
			$this->house = '';
		}

	}

	/**
	 * Query the Ephemeris.
	 *
	 */
	public function query() {

		// Set up Swiss Ephemeris path
		$sweph = apply_filters( 'zp_sweph_dir', ZODIACPRESS_PATH . 'sweph' );
		$PATH = '';
		putenv( "PATH=$PATH:{$sweph}" );
		$swetest = apply_filters( 'zp_sweph_file', 'swetest' );

		// Query the ephemeris
		exec( "$swetest -edir$sweph -b{$this->ut_date} {$this->ut_time} -p{$this->planets} {$this->house} -eswe {$this->format} {$this->options} -g, -head", $out);

		return $out;
		
	}
}