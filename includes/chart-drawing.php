<?php
/**
 * Functions related to the chart drawing
 *
 * @package     ZodiacPress
 * @copyright   Copyright (c) 2016, Isabel Castillo
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'ZP_CUSTOMIZER_QUERY_VAR', 'customize_chart' );

/**
 * Bootstraps the Chart drawing customizer.
 *
 * Initially drop the core widgets and menus panels. 
 * If the current preview page isn't flagged as a Chart template, the core panels will be 
 * re-added and the Chart panel hidden.
 *
 * @internal This callback must be hooked before priority 10 on 'plugins_loaded' to 
 * properly unhook the core panels.
 *
 */
function _zp_bootstrap_customizer() {
	
	require_once ZODIACPRESS_PATH . 'includes/class-zp-customizer.php';

	// Drop core panels (menus, widgets) from the ZP customizer
	add_filter( 'customize_loaded_components', array( 'ZP_Customizer', '_unregister_core_panels' ) );

	// Fire up the ZP Customizer
	add_action( 'customize_register', array( 'ZP_Customizer', 'init' ), 500 );

	// Add design settings + controls to the Customizer
	add_action( 'init', array( 'ZP_Customizer_Design_Settings', 'init' ) );

	// Add a link to the Customizer
	add_action( 'admin_menu', 'zp_add_customizer_link' );

}
add_action( 'plugins_loaded', '_zp_bootstrap_customizer', 9 );

/**
 * Registers a submenu page to access the ZP chart editor panel in the Customizer.
 */
function zp_add_customizer_link() {
	// Teensy little hack on menu_slug, but it works. No redirect!
	$menu_slug = add_query_arg( array(
		'autofocus[panel]'         => ZP_Customizer::PANEL_ID,
		'return'                   => rawurlencode( admin_url() ),
		ZP_CUSTOMIZER_QUERY_VAR   => true,
	), 'customize.php' );

	// Add the theme page.
	$page = add_theme_page(
		__( 'ZP Chart Drawing', 'zp-chart-drawing' ),
		__( 'ZP Chart Drawing', 'zp-chart-drawing' ),
		'edit_theme_options',
		$menu_slug
	);// @todo perhaps move this to a submenu page under ZP
}

function zp_admin_get_preview_permalink() {
	
	// @test can this be the url of the chart image?? 

	// Steve jobs
	$longitudes = array (
		0 => 335.7481352,
		1 =>  7.7473318,
		2 => 314.3617290,
		3 => 291.1718709,
		4 => 29.0904872,
		5 => 110.5079439,
		6 => 231.1626621,
		7 => 114.1348946,
		8 => 208.0512444,
		9 => 145.3228415,
		10 => 302.3322506,
		11 => 238.5218775,
		12 => 273.4098987,
		13 => 140.2937099,// pof
		14 => 337.2458306,
		15 => 172.2912392,
		16 => 81.3150972
	);
	$cusps = array(
		1 => 172.2912392,
		2 => 198.2428191,
		3 => 228.3531711,
		4 => 261.3150972,
		5 => 294.4739505,
		6 => 325.2090266,
		7 => 352.2912392,
		8 => 18.2428191,
		9 => 48.3531711,
		10 => 81.3150972,
		11 => 114.4739505,
		12 => 145.2090266
	);
	// Hypothetical planet speeds
	$speed = array(
		0 => 1,
		1 => 1,
		2 => 1,
		3 => 1,
		4 => 1,
		5 => 1,
		6 => 1,
		7 => 1,
		8 => 1,
		9 => 1,
		10 => 1,
		11 => 1,
		12 => 1,
		13 => 1,
		14 => 1,
		15 => 1,
		16 => 1
	);

	$i18n = array(
		'hypothetical'	=> __( 'Hypothetical', 'zp-chart-drawing' ),
		'time'			=> __( 'Time: 12:00pm', 'zp-chart-drawing' )
	);

	$i = rawurlencode( serialize( $i18n ) );
	$l = rawurlencode( serialize( $longitudes ) );
	$s = rawurlencode( serialize( $speed ) );
	$c = rawurlencode( serialize( $cusps ) );

	$out = ZODIACPRESS_URL . 'image.php?zpl=' . $l . '&zps=' . $s . '&zpc=' . $c . '&zpi=' . $i;

	// $out = '//localhost/wp-content/plugins/zp-chart-drawing/image.php?zpl=' . $l . '&zps=' . $s . '&zpc=' . $c . '&zpi=' . $i;

	// $out = 'http://localhost/report/';// @test

	return $out;
}



/************************************************************
*
* @todo @test move this here ...
*
************************************************************/

add_action( 'customize_controls_print_scripts', 'zp_add_preview_scripts' );// @test call like this instead of line above.
/**
 * Enqueues scripts and fires the 'wp_footer' action so we can output customizer scripts.
 *
 * This breaks AMP validation in the customizer but is necessary for the live preview.
 *
 // * @access public
 */
function zp_add_preview_scripts() {

		isa_log('BINGO. about to enqueue zp-customizer.js');

		wp_enqueue_script(
			'zp-customizer',
			ZODIACPRESS_URL . 'assets/js/zp-customizer.js',
			array( 'jquery', 'customize-preview', 'wp-util' )// @test try loading it in head.
		);

		// @todo remove what's not needed here...

		do_action( 'zp_customizer_enqueue_preview_scripts' );

		// do_action( 'wp_head' );// @test need? 

		/** This action is documented in wp-includes/general-template.php */
		do_action( 'wp_footer' );// @todo maybe don't need this here

}

/**
 * Get the chart drawing image element
 */
function zp_get_chart_drawing( $default, $arg, $chart ) {
	
	isa_log('incoming $chart =');// @test
	isa_log($chart);// @test


	$i18n = array(
		'hypothetical'	=> __( 'Hypothetical', 'zp-chart-drawing' ),
		'time'			=> __( 'Time: 12:00pm', 'zp-chart-drawing' )
	);

	$customizer_settings = ZP_Customizer_Settings::get_settings();

	isa_log('live customizer settings:');
	isa_log($customizer_settings);

	$custom = rawurlencode( serialize( $customizer_settings ) );

	$i = rawurlencode( serialize( $i18n ) );
	$l = rawurlencode( serialize( $chart->planets_longitude ) );
	$s = rawurlencode( serialize( $chart->planets_speed ) );
	$c = rawurlencode( serialize( $chart->cusps ) );

	$u = urlencode( serialize( $chart->unknown_time ) );

	$out = '<img src="' . ZODIACPRESS_URL . 'image.php?zpl=' . $l . '&zps=' . $s . '&zpc=' . $c . '&zpi=' . $i . '&zpcustom=' . $custom . '&zpu=' . $u . '" alt="chart drawing" />';	

	return $out;
}

/**
 * Insert chart drawing in the Birth Report, if enabled.
 */
function zp_report_append_drawing( $default, $arg, $chart ) {
	isa_log('MADE IT to zp_report_append_drawing');// @test remove
	return $default . zp_get_chart_drawing( $default, $arg, $chart );
}

/*
 * Hook into the birth report to insert the chart drawing, if enabled.
 */
function zp_insert_chart_drawing() {
	$zp_options = get_option( 'zodiacpress_settings' );
	// @test these settings:
	if ( isset( $zp_options['add_drawing_to_birthreport'] ) ) {
		if ( 'top' == $zp_options['add_drawing_to_birthreport'] ) {
			add_filter( 'zp_report_header', 'zp_report_append_drawing', 10, 3 );
		} elseif ( 'bottom' == $zp_options['add_drawing_to_birthreport'] ) {
			add_filter( 'zp_report_aspects', 'zp_report_append_drawing', 10, 3 );
		}
	}
}
add_action( 'plugins_loaded', 'zp_insert_chart_drawing' );