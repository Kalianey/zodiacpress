<?php
/**
 * Register Interpretations
 *
 * @package     ZodiacPress
 * @subpackage  Admin/Settings
 * @copyright   Copyright (c) 2016-2017, Isabel Castillo
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Get the name of an option for an Interpretations tab section.
 * 
 * @param string $tab The Interpretations tab to get the option name for
 * @param string $section The tab section to get the option name for
 * @return string The name of the db option for the Interpretations section
 */
function zp_get_interps_option_name( $tab, $section ) {

	if ( empty( $tab ) || empty( $section ) ) {
		return false;
	}
	$name = 'zp_' . $tab;
		
	// Large Interps Tabs that get a separate option per section due to large size
	$large_tabs = apply_filters( 'zp_large_tabs_separate_options', array( 'natal_aspects' ) );

	// If tab is large, append section to the option name
	if ( in_array( $tab, $large_tabs ) ) {
		$name .= '_' . $section;
	}
	return $name;	
}

/**
 * Get the section title for an Interpretations section.
 * 
 * @param string $tab The Interpretations tab for the section
 * @param string $section The section to get the title for
 * @return string The title for the Interpretations section
 */
function zp_get_interps_section_title( $tab, $section ) {

	if ( empty( $tab ) || empty( $section ) ) {
		return false;
	}

	$planet_key	= ( 'main' == $section ) ? 'sun' : $section;
	$planet		= '';

	// get planet label
	foreach ( zp_get_planets() as $p ) {
		if ( $planet_key == $p['id'] ) {
			$planet = $p['label'];
		}
	}

	switch ( $tab ) {
		case 'natal_planets_in_signs':
			$title = sprintf( __( 'Interpretations For Natal %1$s in The Signs', 'zodiacpress' ), $planet );
			break;

		case 'natal_planets_in_houses':
			$title = sprintf( __( 'Interpretations For Natal %1$s in The Houses', 'zodiacpress' ), $planet );
			break;

		case 'natal_aspects':
			$title = sprintf( __( 'Interpretations For Aspects of Natal %1$s', 'zodiacpress' ), $planet );
			break;

		default:
			$title = __( 'Natal Interpretations', 'zodiacpress' );
			break;
	}

	return $title;	
}

/**
 * Register all enabled interpretations sections and fields
 *
 * @return void
*/
function zp_register_interps() {

	// Large Interps Tabs get a separate option per section due to large size
	$large_tabs = apply_filters( 'zp_large_tabs_separate_options', array( 'natal_aspects' ) );

	foreach ( zp_get_enabled_interps() as $tab => $sections ) {

		foreach ( $sections as $section => $interps ) {

			add_settings_section(
				'zp_interps_' . $tab . '_' . $section,
				zp_get_interps_section_title( $tab, $section ),
				'__return_false',
				'zp_interps_' . $tab . '_' . $section
			);

			foreach ( $interps as $option ) {

				$name = isset( $option['name'] ) ? $option['name'] : '';

				add_settings_field(
					'zp_' . $tab . '[' . $option['id'] . ']',
					$name,
					'zp_interps_textarea_callback',
					'zp_interps_' . $tab . '_' . $section,
					'zp_interps_' . $tab . '_' . $section,
					array(
						'section'	=> $section,
						'tab'		=> $tab,
						'id'		=> isset( $option['id'] ) ? $option['id'] : null,
						'name'		=> isset( $option['name'] ) ? $option['name'] : null
					)
				);
			}

			// Tabs that hold large content (i.e. Aspects) get a db option for each section
			if ( in_array( $tab, $large_tabs ) ) {
				register_setting( 'zp_' . $tab . '_' . $section, 'zp_' . $tab . '_' . $section, 'zp_interps_sanitize' );
			}

		}

		// Tabs that are not large get a db option per whole Tab.
		if ( ! in_array( $tab, $large_tabs ) ) {
			register_setting( 'zp_' . $tab, 'zp_' . $tab, 'zp_interps_sanitize' );
		}

	}

}
add_action( 'admin_init', 'zp_register_interps' );

/**
 * Retrieve the array of enabled interpretations settings
 *
 * @return array
*/
function zp_get_enabled_interps() {

	$options = get_option( 'zodiacpress_settings' );

	$zp_interps_settings = array();

	/** "In Signs" Interpretations Settings */

	$tab = 'natal_planets_in_signs';

	// A section for each planet that is enabled for the "Planets in Signs" setting
	if ( ! empty( $options['enable_planet_signs'] ) ) {

		foreach ( $options['enable_planet_signs'] as $planet ) {

			$section = ( 'sun' == $planet['id'] ) ? 'main' : $planet['id'];

			foreach ( zp_get_zodiac_signs() as $sign ) {

				$key 	= $planet['id'] . '_' . $sign['id'];
				$name 	= sprintf( __( '%1$s in %2$s', 'zodiacpress' ),
						$planet['label'],
						$sign['label']
						);

				$zp_interps_settings[ $tab ][ $section ][ $key ]['id'] = $key;
				$zp_interps_settings[ $tab ][ $section ][ $key ]['name'] = $name;

			}
		}
	}

	/**  "In Houses" Interpretations Settings */

	$tab = 'natal_planets_in_houses';

	// A section for each planet that is enabled in the "Planets in Houses" setting
	if ( ! empty( $options['enable_planet_houses'] ) ) {

		$order	= zp_ordinal_word();

		foreach ( $options['enable_planet_houses'] as $planet ) {

			$section = ( 'sun' == $planet['id'] ) ? 'main' : $planet['id'];
			
			for ( $i = 1; $i < 13; $i++ ) {

				$key 	= $planet['id'] . '_' . $i;
				$name 	= sprintf( __( '%1$s in The %2$s House', 'zodiacpress' ),
							$planet['label'],
							$order[ $i ]
							);

				$zp_interps_settings[ $tab ][ $section ][ $key ]['id']	= $key;
				$zp_interps_settings[ $tab ][ $section ][ $key ]['name'] = $name;

			}
		}
	}

	/**  "Aspects" Interpretations Settings */

	$tab = 'natal_aspects';

	// A section for each planet that is enabled in the "Aspects To Planets" setting.
	// Also, only include aspects that are enabled in the "Aspects" setting.
	
	if ( ! empty( $options['enable_planet_aspects'] ) && ! empty ( $options['enable_aspects'] ) ) {

		$planets 	= $options['enable_planet_aspects'];
		$n 			= count( $planets );
		$aspects 	= $options['enable_aspects'];
	
		for ( $i = 0; $i < $n; $i++ ) {
			for ( $j = 0; $j < $n; $j++ ) {

				if ( $j > $i ) {

					$section = ( 'sun' == $planets[$i]['id'] ) ? 'main' : $planets[$i]['id'];

					foreach ( $aspects as $aspect ) {

						$key 	= $planets[$i]['id'] . '_' . $aspect['id'] . '_' . $planets[$j]['id'];
						$name 	= $planets[$i]['label'] . ' ' . $aspect['label'] . ' ' . $planets[$j]['label'];

						$zp_interps_settings[ $tab ][ $section ][ $key ]['id']	= $key;
						$zp_interps_settings[ $tab ][ $section ][ $key ]['name'] = $name;
					}
				}
			} // end for j...
		} // end for i...
	}

	return $zp_interps_settings;
}

/**
 * Interpretations Sanitization
 *
 * Adds a settings error (for the updated message)
 *
 * @param array $input The value inputted in the field
 *
 * @return array Sanitizied value
 */
function zp_interps_sanitize( $input = array() ) {

	if ( empty( $_POST['_wp_http_referer'] ) ) {
		return $input;
	}

	parse_str( $_POST['_wp_http_referer'], $referrer );
	$interps 	= zp_get_enabled_interps();
	$tab 		= isset( $referrer['tab'] ) ? $referrer['tab'] : 'natal_planets_in_signs';
	$section 	= isset( $referrer['section'] ) ? $referrer['section'] : 'main';

	// get the Interps option for this section
	$option_name = zp_get_interps_option_name( $tab, $section );

	$interps_option = get_option( $option_name );
	$interps_option = $interps_option ? $interps_option : array();

	$input = $input ? $input : array();
	$input = apply_filters( 'zp_interps_' . $tab . '-' . $section . '_sanitize', $input );

	// Loop through the whitelist and unset any that are empty for the tab being saved
	$section_interps = ! empty( $interps[ $tab ][ $section ] ) ? $interps[ $tab ][ $section ] : array();

	if ( ! empty( $section_interps ) ) {
		foreach ( $section_interps as $key => $value ) {
			if ( empty( $input[ $key ] ) ) {
				unset( $interps_option[ $key ] );
			}
		}
	}

	// Merge our new interps with the existing
	$output = array_merge( $interps_option, $input );

	add_settings_error( 'zp-intpers-notices', '', __( 'Interpretations updated.', 'zodiacpress' ), 'updated' );

	return $output;
}

/**
 * Retrieve Interpretations tabs
 *
 * @return array $tabs
 */
function zp_get_interps_tabs() {

	$tabs 								= array();
	$tabs['natal_planets_in_signs']		= __( 'In Signs', 'zodiacpress' );
	$tabs['natal_planets_in_houses']	= __( 'In Houses', 'zodiacpress' );
	$tabs['natal_aspects']				= __( 'Aspects', 'zodiacpress' );
	return $tabs;
}

/**
 * Retrieve interpretations tab sections
 *
 * @return array $section
 */
function zp_get_interps_tab_sections( $tab = false ) {

	$sections	= false;
	$interps	= zp_get_enabled_interps_sections();

	if( $tab && ! empty( $interps[ $tab ] ) ) {
		$sections = $interps[ $tab ];
	} elseif ( $tab ) {
		$sections = false;
	}

	return $sections;
}

/**
 * Get the enabled interpretations sections for each tab
 *
 * @return array Array of tabs and sections
 */
function zp_get_enabled_interps_sections() {

	$options = get_option( 'zodiacpress_settings' );
	$sections = array();

	/** "In Signs" tab */

	// Do sections for planets that are enabled in settings.
	if ( ! empty( $options['enable_planet_signs'] ) ) {
		foreach ( $options['enable_planet_signs'] as $planet ) {

			// section name
			$section = ( 'sun' == $planet['id'] ) ? 'main' : $planet['id'];
			$sections['natal_planets_in_signs'][ $section ]	= $planet['label'];

		}
	}

	/** "In Signs" tab */

	// Do sections for planets that are enabled in settings.
	if ( ! empty( $options['enable_planet_houses'] ) ) {
		foreach ( $options['enable_planet_houses'] as $planet ) {

			// section name
			$section = ( 'sun' == $planet['id'] ) ? 'main' : $planet['id'];
			$sections['natal_planets_in_houses'][ $section ] = $planet['label'];
		}
	}

	/** Asepcts tab */

	// Only sections for planets that are enabled in the Aspects To Planets setting

	if ( ! empty( $options['enable_planet_aspects'] ) ) {

		array_pop( $options['enable_planet_aspects'] );

		foreach ( $options['enable_planet_aspects'] as $n => $planet ) {
			
			// section name
			$section = ( 'sun' == $planet['id'] ) ? 'main' : $planet['id'];
			$sections['natal_aspects'][ $section ] = $planet['label'];
		}

	}

	$sections = $sections;

	return $sections;
}

/**
 * Interpretations Textarea Callback
 *
 * Renders textarea fields for interpretations.
 *
 * @param array $args Arguments passed by the setting
 * @return void
 */
function zp_interps_textarea_callback( $args ) {

	if ( ! isset( $args['tab'] ) ) {
		return;
	}

	// get the Interps option for this setting
	$option_name = zp_get_interps_option_name( $args['tab'], $args['section'] );
	$option = get_option( $option_name );
	$value = isset( $option[ $args['id'] ] ) ? $option[ $args['id'] ] : '';

	$html = '<textarea class="large-text" cols="50" rows="5" id="' . esc_attr( $option_name ) . '[' . esc_attr( $args['id'] ) . ']" name="' . esc_attr( $option_name ) . '[' . esc_attr( $args['id'] ) . ']">' . esc_textarea( stripslashes( $value ) ) . '</textarea>';
	echo $html;
}

/**
 * Set manage_zodiacpress_interps as the cap required to save ZP Interpretations
 *
 * @return string capability required
 */
function zp_set_interps_cap( $cap ) {
	return 'manage_zodiacpress_interps';
}
/**
 * Filter the cap required to save ZP Interpretations
 */
function zp_filter_interps_options_cap() {
	$names = zp_get_enabled_interps_options_names();
	foreach ( $names as $name ) {
		add_filter( "option_page_capability_{$name}", 'zp_set_interps_cap' );
	}
}
add_action( 'init', 'zp_filter_interps_options_cap' );