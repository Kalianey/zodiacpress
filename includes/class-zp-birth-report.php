<?php
/**
 * ZP_Birth_Report class
 *
 * @package     ZodiacPress
 * @copyright   Copyright (c) 2016, Isabel Castillo
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
	 * @return string Formatted chart data including birth time, zone, place, and house system.
	 */
	public function header() {

		// Check if we have i18n of the year (we haven't i18n years prior to 1900)
		$year = zp_i18n_years( $this->form['year'] - 1900 );
		if ( is_array( $year ) ) {
			$year = $this->form['year'];
		}

		$birth_date = zp_i18n_numbers( $this->form['day'] ) . ' ' .
						zp_get_i18n_months( $this->form['month'] ) . ' ' .
						$year;

		$coordinates = zp_dd_to_dms( $this->form['zp_lat_decimal'], 'latitude' ) . ' ' .
						zp_dd_to_dms( $this->form['zp_long_decimal'], 'longitude' );

		if ( $this->form['unknown_time'] ) {
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

			$birth_time = sprintf( __( '%1$s (time zone = UTC %2$s)', 'zodiacpress' ),
				$time,
				$tz
			);
		}

		$birth_data = sprintf( __( '%1$s at %2$s', 'zodiacpress' ),
			$birth_date,
			$birth_time
		);

		// Begin header 
		
		$header = '<section class="zp-report-header"><p><strong>' .
				sprintf( __( 'Chart Data For %s', 'zodiacpress' ), $this->form['name'] ) .
				'</strong><br />' .
				esc_html( $birth_data ) .
				'<br />' .
				esc_html( stripslashes( $this->form['place'] ) . ' (' . $coordinates . ')' );

		// House system used for this chart

		if ( empty( $this->form['unknown_time'] ) ) {

			$houses_label = apply_filters( 'zp_house_system_label', __( 'Placidus', 'zodiacpress' ), $this->chart->house_system );

			$houses = '<br />' .  sprintf( __( '%s Houses', 'zodiacpress' ), $houses_label );
	
			// Allow house system to be removed with filter
			$header .= apply_filters( 'zp_report_header_houses', $houses, $this->form['zp-report-variation'] );
		}

		$header .= '</section>';
		
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

		if ( $this->form['unknown_time'] ) {
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

				$sign_pos	= floor( $this->chart->planets_longitude[ $k ] / 30 );
				$retrograde	= '';

				// Check for retrograde, but not for POF, Vertex, Asc, or MC
				if ( ! in_array( $k, array( 13, 14, 15, 16 ) ) && $this->chart->planets_speed[ $k ] < 0 ) {
					$retrograde = '&nbsp; R<sub>x</sub> ';
				}

				$planets_in_signs[] = array(
									'id'	=> $planet['id'] . '_' . $signs[ $sign_pos ]['id'],
									'label'	=> $planet['label'] . ' in ' . $signs[ $sign_pos ]['label'],
									'zodiacal_dms' => zp_get_zodiac_sign_dms( $this->chart->planets_longitude[ $k ] ) . $retrograde
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
		if ( $this->form['unknown_time'] ) {
			return;
		}

		$planets_in_houses	= array();
		$cleared_planets	= $this->get_cleared_planets( 'enable_planet_houses' );

		if ( $cleared_planets ) {

			foreach ( $cleared_planets as $k => $planet ) {

				$house_num = $this->chart->planets_house_numbers[ $k ];

				$next = '';
				
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
		$out .= apply_filters( 'zp_report_header', $this->header(), $report_var );

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
