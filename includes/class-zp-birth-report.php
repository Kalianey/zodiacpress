<?php
/**
 * ZP_Birth_Report class
 *
 * @package     ZodiacPress
 * @copyright   Copyright (c) 2016-2017, Isabel Castillo
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * The class used to build and display the birth report.
 */
class ZP_Birth_Report {

	/**
	 * The Chart object for this report.
	 */
	private $chart;

	/**
	 * The form that is submitted by user requesting report.
	 */
	private $form;

	/**
	 * Planets/points in signs for this chart, limited to planets enabled in settings, and adjusted for missing birth time, if applicable.
	 *
	 * @var array
	 */
	private $enabled_planets_in_signs = array();

	/**
	 * Planets/points in houses for this chart, limited to planets enabled in settings, and adjusted for missing birth time, if applicable.
	 *
	 * @var array
	 */
	private $enabled_planets_in_houses = array();

	/**
	 * Aspects for this chart, limited to planets enabled in settings, and adjusted for missing birth time, if applicable.
	 *
	 * @var array
	 */
	private $enabled_aspects = array();

	/**
	 * Constructor.
	 *
	 * @param object $chart A ZP_Chart object
	 * @param array $form Form data submitted by user requesting report	 
	 */
	public function __construct( $_chart, $_form ) {
		$this->chart	= $_chart;
		$this->form		= $_form;
		$this->setup_in_signs();
		$this->setup_in_houses();
		$this->setup_aspects_list();
	}

	/**
	 * Get the birth report header.
	 * @return string $header Formatted chart data including birth time, zone, place, type of zodiac, and house system.
	 */
	public function header() {
		// Check if we have i18n of the year (we haven't i18n years prior to 1900)
		$year = zp_i18n_years( $this->form['year'] - 1900 );
		$year = is_array( $year ) ? $this->form['year'] : $year;

		// Local date

		$birth_date = zp_i18n_numbers( $this->form['day'] ) . ' ' .
						zp_get_i18n_months( $this->form['month'] ) . ' ' .
						$year;

		// Coordinates

		$coordinates = zp_dd_to_dms( $this->form['zp_lat_decimal'], 'latitude' ) . ' ' .
						zp_dd_to_dms( $this->form['zp_long_decimal'], 'longitude' );

		// Local Time

		if ( $this->chart->unknown_time ) {
			$birth_time = __( 'unknown birth time', 'zodiacpress' );
		} else {
			if ( $this->form['zp_offset_geo'] < 0 ) {
				$tz = $this->form['zp_offset_geo'];
			} else {
				$tz = "+" . $this->form['zp_offset_geo'];
			}

			// display 24-hour time in original zone
			$time = $this->form['hour'] . ':' . $this->form['minute'];

			// append 12-hour formatted time
			$time .= ' (' . date( 'g:i a', strtotime( $time ) ) . ')';

			$birth_time = sprintf( __( '%1$s <span class="zp-mobile-wrap">(time zone = UTC %2$s)</span>', 'zodiacpress' ),
				$time,
				$tz
			);
		}

		$birth_data = sprintf( __( '%1$s at %2$s', 'zodiacpress' ),
			$birth_date,
			$birth_time
		);

		// Universal Time

		$ut = strftime( "%H:%M", $this->chart->unix_timestamp );
		// maybe append UT date if different from date entered on form
		$entered_day	= ( $this->form['day'] < 10 ) ? ( '0' . $this->form['day'] ) : $this->form['day'];		
		$entered_month	= ( $this->form['month'] < 10 ) ? ( '0' . $this->form['month'] ) : $this->form['month'];
		$entered_date	= $entered_day . '.' . $entered_month . '.' . $this->form['year'];
		if ( $this->chart->ut_date != $entered_date ) {
			$ut_year		= trim( strftime( "%Y", $this->chart->unix_timestamp ) );
			$i18n_ut_year	= zp_i18n_years( $ut_year - 1900 );
			$ut_year		= is_array( $i18n_ut_year ) ? $ut_year : $i18n_ut_year;

			$ut .= ' <small>' .
					zp_i18n_numbers( trim( strftime( "%e", $this->chart->unix_timestamp ) ) ) .
					' ' . zp_get_i18n_months( date( 'n', $this->chart->unix_timestamp ) ) .
					' ' . $i18n_ut_year .
					'</small>';
		}

		// Type of zodiac used

		$zodiac_type = __( 'Tropical Zodiac', 'zodiacpress' );
		if ( $this->chart->sidereal ) {
			$zodiac_type = __( 'Sidereal Zodiac,', 'zodiacpress' );

			// i18n the ayanamsa
			$ayanamsa = zp_transliterated_degrees_minutes_seconds( $this->chart->ayanamsa );
			$zodiac_type .= ' ' .
						sprintf( __( 'ayanamsa = %1$s <span class="zp-mobile-wrap">(%2$s)</span>', 'zodiacpress' ),
						$ayanamsa,
						zp_get_sidereal_methods()[ $this->chart->sidereal ]['label'] );
		}

		// Begin header HTML
  
		$header = '<table class="zp-report-header"><caption class="zp-report-caption">' .
				sprintf( __( 'Chart Data For %s', 'zodiacpress' ), $this->form['name'] ) .
				'</caption>' .
				'<tr>' .
					'<td>' . $birth_data . '</td>' .
				'</tr>';
		if ( empty( $this->chart->unknown_time ) ) {
			$header .= '<tr class="zp-report-header-ut">' .
					'<td>' . __( 'Universal Time:', 'zodiacpress' ) . ' ' . $ut . ' </td>' .
				'</tr>';
		}
		$header .= '<tr class="zp-report-header-place">' .
					'<td>' . stripslashes( $this->form['place'] ) . '</td>' .
				'</tr><tr class="zp-report-header-coordinates">' .					
					'<td>' . esc_html( $coordinates ) . '</td>' .
				'</tr><tr class="zp-report-header-zodiac-type">' .
					'<td>' . $zodiac_type . '</td>' .
				'</tr>';

		// House system used
		if ( empty( $this->chart->unknown_time ) ) {
			$houses = '<tr class="zp-report-header-houses"><td>' .
					sprintf( __( '%s Houses', 'zodiacpress' ),
					zp_get_house_systems( $this->chart->house_system ) ) .
					'</td></tr>';
			// Allow house system to be removed with filter
			$header .= apply_filters( 'zp_report_header_houses', $houses, $this->form['zp-report-variation'] );
		}

		$header .= '</table>';
		
		return $header;

	}

	/**
	 * Get an Interpretations section of the rerpot
	 * @param string $section Which section of interpretations to get, whether planets_in_signs, planets_in_houses, or aspects.
	 */
	private function get_interpretations( $section ) {
		if ( empty( $section ) ) {
			return;
		}

		// Leave if there is not at least 1 planet enabled.
		if ( empty( $this->{"enabled_${section}"} ) ) {
			return;
		}

		$content = '';

		// Get the option for the interps, only for planets in signs and in houses, not aspects because aspects are spread among several options.
		if ( 'aspects' != $section ) {
			$interps = get_option( "zp_natal_$section" );
		}

		foreach ( $this->{"enabled_${section}"} as $v ) {

			// For aspects, get the required Interpretations option
			if ( 'aspects' == $section ) {
				$option_name = 'zp_natal_aspects_' . $v['aspecting_planet'];
				$interps = get_option( $option_name );
			}

			// If birth time is unknown, check if planet ingress happens today

			if ( $this->chart->unknown_time &&
				'planets_in_signs' == $section &&
				'' !== $v['ingress_0'] &&
				'' !== $v['ingress_1'] ) {
				
				$content .= '<p class="zp-subheading">' .
							sprintf( __( 'NOTE: %1$s changed signs the day you were born. It moved from %2$s to %3$s. Therefore, you will need your exact time of birth to know which of these two signs your %1$s is in.', 'zodiacpress' ),
								zp_get_planets()[ $v['planet_key'] ]['label'],
								$v['ingress_0'],
								$v['ingress_1'] ) . 
							'</p>';

			} else {
				$content .= '<p class="zp-subheading">' . $v['label'];
				if ( isset( $v['zodiacal_dms'] ) ) {
					$content .= ' <span class="zp-zodiacal-dms">' . $v['zodiacal_dms'] . '</span>';
				}
				$content .= '</p>';

				// Does interpretation exist for this?
				if ( ! empty( $interps[ $v['id'] ] ) ) {
					$content .= '<p>' . wp_kses_post( wpautop( $interps[ $v['id'] ] ) ) . '</p>';
				}

				// Check for planets conjunct the next house cusp.
				if ( 'planets_in_houses' == $section ) {
					if ( ! empty( $v['next_label'] ) ) {
						$content .= '<p class="zp-subheading">' .
								sprintf( __( 'NOTE: Since %s is very close to the next house cusp, the next item is also relevant.', 'zodiacpress' ), $v['planet_label'] ) .
								'</p>' . 
								'<p class="zp-subheading">' . $v['next_label'] . '</p>';

						// Does interpretation exist for this?
						if ( ! empty( $interps[ $v['next_id'] ] ) ) {
							$content .=	'<p>' . wp_kses_post( wpautop( $interps[ $v['next_id'] ] ) ) . '</p>';
						}
					}
				}
			}
			
		}

		switch ( $section ) {
			case 'planets_in_signs':
				$title = __( 'Planets and Points in The Signs', 'zodiacpress' );
				break;
			case 'planets_in_houses':
				$title = __( 'Planets and Points in The Houses', 'zodiacpress' );
				break;
			case 'aspects':
				$title = __( 'Aspects', 'zodiacpress' );
				break;
		}

		$out = '<h3 class="zp-report-section-title zp-' . $section . '-title">' .
				apply_filters( "birthreport_${section}_title", $title ) .
				'</h3>';

		// Allow content to be inserted at the top of each section.
		$out .= apply_filters( "zp_report_${section}_top", '' );

		$out .= $content;
		
		return $out;

	}

	/**
	 * Filter enabled planets to omit moon and time-sensitive points if birth time is unknown.
	 *
	 * @param string $planets_key the settings key for the type of enabled planets to filter.
	 * @return array of planets with official planet #s as keys
	 */
	private function get_cleared_planets( $planets_key ) {
		global $zodiacpress_options;

		if ( empty( $zodiacpress_options[ $planets_key ] ) ) {
			return;
		}

		$planets			= zp_get_planets();
		$cleared_planets	= array();

		// Set up array of enabled planets and its official planet # as key.
		foreach ( $zodiacpress_options[ $planets_key ] as $enabled_planet ) {
			$key = zp_search_array( $enabled_planet['id'], 'id', $planets );
			$cleared_planets[ $key ] = array( 
										'id'	=> $enabled_planet['id'],
										'label'	=> $enabled_planet['label']
										);
		}

		// If birthtime is not known, omit planets that require birth time
		if ( $this->chart->unknown_time ) {
			foreach ( $cleared_planets as $k => $p ) {
				if ( ! empty( $planets[ $k ]['supports'] ) && in_array( 'birth_time_required', $planets[ $k ]['supports'] ) ) {
					unset( $cleared_planets[ $k ] );
				}
			}
		}

		// For planets in signs, move ASC to the top, just because it looks nicer on report
		if ( 'enable_planet_signs' == $planets_key && isset( $cleared_planets[ 15 ] ) ) {
			$cleared_planets = array( 15 => $cleared_planets[ 15 ] ) + $cleared_planets;
		}
		
		return $cleared_planets;

	}

	/**
	 * Set up the $enabled_planets_in_signs property
	 *
	 * Set up the planets and points in signs, limited to those enabled in the settings and omittimg moon and time-sensitive points if birth time is unknown.
	 */
	private function setup_in_signs() {
		$signs				= zp_get_zodiac_signs();
		$planets_in_signs	= array();
		$cleared_planets	= $this->get_cleared_planets( 'enable_planet_signs' );

		if ( $cleared_planets ) {

			foreach ( $cleared_planets as $k => $planet ) {

				$sign_num	= floor( $this->chart->planets_longitude[ $k ] / 30 );

				$retrograde	= '';
				$ingress	= '';

				// Check for retrograde, but not for POF, Vertex, Asc, or MC
				if ( ! in_array( $k, array( 13, 14, 15, 16 ) ) && $this->chart->planets_speed[ $k ] < 0 ) {
					$retrograde = '&nbsp; R<sub>x</sub> ';
				}

				// If birthtime is unknown, check if planet ingress occurs this day
				if ( $this->chart->unknown_time ) {
					$ingress = zp_is_planet_ingress_today( $k, $this->chart->planets_longitude[ $k ], $this->form );
				}

				$planets_in_signs[] = array(
							'id'			=> $planet['id'] . '_' . $signs[ $sign_num ]['id'],
							'label'			=> $planet['label'] . ' in ' . $signs[ $sign_num ]['label'],
							'zodiacal_dms'	=> zp_get_zodiac_sign_dms( $this->chart->planets_longitude[ $k ] ) . $retrograde,
							'ingress_0'		=> isset( $ingress[0] ) ? $signs[ $ingress[0] ]['label'] : '',
							'ingress_1'		=> isset( $ingress[1] ) ? $signs[ $ingress[1] ]['label'] : '',
							'planet_key'	=> $k
						);
			}
		}

		$this->enabled_planets_in_signs = $planets_in_signs;

	}


	/**
	 * Get the id and label for a planet in the next house, rather than in the its current house.
	 * @param array $planet
	 * @param int $house_num Current house number where planet resides
	 */
	private function planet_in_next_house( $planet, $house_num ) {

		$next_num	= ( 12 == $house_num ) ? '1' : ( $house_num + 1 );
		$next_id 	= $planet['id'] . '_' . $next_num;
		$next_label	= sprintf( __( '%1$s in %2$s House', 'zodiacpress' ),
					$planet['label'],
					zp_ordinal_word( $next_num )
				);

		return array( $next_id, $next_label );
	}

	/**
	 * Set up the $enabled_planets_in_houses property
	 *
	 * Set up the planets and points in houses, limited to those enabled in the settings and omittimg moon and time-sensitive points if birth time is unknown.
	 */
	private function setup_in_houses() {

		// If birthtime is not known, omit planets in houses
		if ( $this->chart->unknown_time ) {
			return;
		}

		$planets_in_houses	= array();
		$cleared_planets	= $this->get_cleared_planets( 'enable_planet_houses' );

		if ( $cleared_planets ) {

			foreach ( $cleared_planets as $k => $planet ) {

				unset( $next ); // This is absolutely necessary.

				$house_num = $this->chart->planets_house_numbers[ $k ];

				// Check if planet is conjunct the next house cusp.
				if ( ! empty( $this->chart->conjunct_next_cusp[ $k ] ) ) {
					$next = $this->planet_in_next_house( $planet, $house_num );
				}				

				$planets_in_houses[] = array(
							'id'		=> $planet['id'] . '_' . $house_num,
							'label'		=> sprintf( __( '%1$s in %2$s House', 'zodiacpress' ),
													$planet['label'],
													zp_ordinal_word( $house_num )
												),
							'next_id'		=> isset( $next[0] ) ? $next[0] : '',
							'next_label'	=> isset( $next[1] ) ? $next[1] : '',
							'planet_label'	=> $planet['label']
				);
			}
		}

		$this->enabled_planets_in_houses = $planets_in_houses;
	}

	/**
	 * Set up the $enabled_aspects property
	 *
	 * Set up the list of aspects, limited to those enabled in the settings and omittimg moon and time-sensitive points if birth time is unknown.
	 */
	private function setup_aspects_list() {

		global $zodiacpress_options;

		if ( empty( $zodiacpress_options['enable_aspects'] ) ) {
			return;
		}		

		$aspects_list		= array();
		$cleared_planets	= $this->get_cleared_planets( 'enable_planet_aspects' );

		if ( $cleared_planets ) {
			$active_aspects = $zodiacpress_options['enable_aspects'];// enabled in settings
			$all_aspects    = zp_get_aspects();

			foreach ( $cleared_planets as $key_1 => $p_1 ) {

				foreach ( $cleared_planets as $key_2 => $p_2 ) {

					if ( $key_2 > $key_1 ) {

						// Calculate angular distance between 2 planets/points

						$angular_distance = abs( $this->chart->planets_longitude[ $key_1 ] - $this->chart->planets_longitude[ $key_2 ] );
						
						if ( $angular_distance > 180) {
							$angular_distance = 360 - $angular_distance;
						}

						$aspecting_planet = ( 'sun' == $p_1['id'] ) ? 'main' : $p_1['id'];

						// Check for aspects within orb
						foreach ( $active_aspects as $asp ) {

							// Get the numerical degrees for this aspect.
							$aspect_key	= zp_search_array( $asp['id'], 'id', $all_aspects );
							$num		= (int) $all_aspects[ $aspect_key ]['numerical'];

							// Check custom orb for both planets and use the smaller orb.
							$key1			= 'orb_' . $asp['id'] . '_' . $p_1['id'];
							$allowed_orb1	= empty( $zodiacpress_options[ $key1 ] ) ? 8 : $zodiacpress_options[ $key1 ];
							$allowed_orb1	= is_numeric( $allowed_orb1 ) ? abs( $allowed_orb1 ) : 8;
							$key2			= 'orb_' . $asp['id'] . '_' . $p_2['id'];
							$allowed_orb2	= empty( $zodiacpress_options[ $key2 ] ) ? 8 : $zodiacpress_options[ $key2 ];
							$allowed_orb2	= is_numeric( $allowed_orb2 ) ? abs( $allowed_orb2 ) : 8;
							$allowed_orb	=  min( $allowed_orb1, $allowed_orb2 );

							// Check for oppositions differently than for other aspects.
							if ( 180 === $num ) {

								if ( $angular_distance >= ( $num - $allowed_orb ) ) {

									$orb = zp_dd_to_dms( abs( $num - $angular_distance ) );

									$aspects_list[] = array(
												'id'	 			=> $p_1['id'] . '_' . $asp['id'] . '_' . $p_2['id'],
												'aspecting_planet'	=> $aspecting_planet,
												'label'				=> $p_1['label'] . ' ' . $asp['label'] . ' ' . $p_2['label'] . ' <span class="zp-orb">(' . __('orb', 'zodiacpress' ) . ' ' . $orb . ')</span>',
									);

								}
							
							} else {

								// Check for all other aspects that are not oppositions.
								if ( ( $angular_distance <= ( $num + $allowed_orb ) ) && ( $angular_distance >= ( $num - $allowed_orb ) ) ) {

									$orb = zp_dd_to_dms( abs( $angular_distance - $num ) );

									$aspects_list[] = array(
												'id'				=> $p_1['id'] . '_' . $asp['id'] . '_' . $p_2['id'],
												'aspecting_planet'	=> $aspecting_planet,
												'label'				=> $p_1['label'] . ' ' . $asp['label'] . ' ' . $p_2['label'] . ' <span class="zp-orb">(' . __('orb', 'zodiacpress' ) . ' ' . $orb . ')</span>',
									);			
								}

							}
						}
					}

				}

			}

		}

		$this->enabled_aspects = $aspects_list;
	}	

	/**
	 * Return all parts of the birth report.
	 */
	public function get_report() {
		global $zodiacpress_options;

		if ( ! is_array( $this->form ) ) {
			return;
		}

		if ( ! is_object( $this->chart ) ) {
			return;
		}

		// Variation of the Natal rerport. For use by extensions.
		$report_var = $this->form['zp-report-variation'];

		$out = '';
		$out .= apply_filters( 'zp_report_header', $this->header(), $report_var, $this->chart );

		if ( 'birthreport' == $report_var ) {
			
			// Intro
			if ( ! empty( $zodiacpress_options['birthreport_intro'] ) ) {
				$intro = '<h3 class="zp-report-section-title zp-intro-title">' . apply_filters( 'birthreport_intro_title', __( 'Introduction', 'zodiacpress' ) ) . '</h3>';
				$intro .= wpautop( $zodiacpress_options['birthreport_intro'] );
				$out .= apply_filters( 'zp_report_intro', $intro );
			}

			$out .= apply_filters( 'zp_report_in_signs', $this->get_interpretations( 'planets_in_signs' ) );
			$out .= apply_filters( 'zp_report_in_houses', $this->get_interpretations( 'planets_in_houses' ) );
			$out .= apply_filters( 'zp_report_aspects', $this->get_interpretations( 'aspects' ), $this->form, $this->chart );

			// Closing
			if ( ! empty( $zodiacpress_options['birthreport_closing'] ) ) {
				$closing = '<h3 class="zp-report-section-title zp-closing-title">' . apply_filters( 'birthreport_closing_title', __( 'Closing', 'zodiacpress' ) ) . '</h3>';
				$closing .= wpautop( $zodiacpress_options['birthreport_closing'] );
				$out .= apply_filters( 'zp_report_closing', $closing );
			}

		} else {
			// Allow extensions to create custom reports
			$out .= apply_filters( "zp_{$report_var}_report", '', $this->form, $this->chart );
		}
		return $out;
	}

}
