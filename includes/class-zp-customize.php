<?php
/**
 * Adds a ZodiacPress chart wheel color editor to the Customizer.
 */
class ZP_Customize {
	/**
	 * Get default colors
	 */
	private static function default_colors() {
		$defaults = array(
		'outer_bg_color'			=> array( '#e9e9e9',
											__( 'Outer Background Color', 'zodiacpress' ) ),
		'signs_wheel_color'			=> array( '#fff',
											__( 'Signs Wheel Background Color', 'zodiacpress' ) ),
		'signs_divider_color'		=> array( '#000',
											__( 'Signs Divider Color', 'zodiacpress' ) ),
		'signs_border_color'		=> array( '#000',
											__( 'Signs Border Color', 'zodiacpress' ) ),
		'wheel_bg_color'			=> array( '#f8f8f8',
											__( 'Wheel Background Color', 'zodiacpress' ) ),
		'houses_border_color'		=> array( '#000',
											__( 'Houses Border Color', 'zodiacpress' ) ),
		'houses_divider_color' 		=> array( '#000',
											__( 'Houses Divider Color', 'zodiacpress' ) ),
		'angles_arrow_color'		=> array( '#000',
											__( 'Angles Arrow Color', 'zodiacpress' ) ),
		'planet_glyph_color'		=> array( '#000',
											__( 'Planet Glyph Color', 'zodiacpress' ) ),
		'house_number_color'		=> array( '#000',
											__( 'House Number Color', 'zodiacpress' ) ),
		'degree_color'				=> array( '#0000ff',
											__( 'Degree Color', 'zodiacpress' ) ),
		'angle_degree_color'		=> array( '#0000ff',
											__( 'Angle Degree Color', 'zodiacpress' ) ),
		'inner_wheel_color'			=> array( '#fff',
											__( 'Inner Wheel Color', 'zodiacpress' ) ),
		'inner_wheel_border_color'	=> array( '#000',
											__( 'Inner Wheel Border Color', 'zodiacpress' ) ),
		'hard_aspect_color'			=> array( '#ff0000',
											__( 'Hard Aspect Color', 'zodiacpress' ) ),
		'soft_aspect_color'			=> array( '#1f8dba',
											__( 'Soft Aspect Color', 'zodiacpress' ) ),
		'minor_aspect_color'		=> array( '#00e000',
											__( 'Minor Aspect Color', 'zodiacpress' ) ),
		'fire_sign_color'			=> array( '#cf000f',
											__( 'Fire Sign Color', 'zodiacpress' ) ),
		'earth_sign_color'			=> array( '#00d717',
											__( 'Earth Sign Color', 'zodiacpress' ) ),
		'air_sign_color'			=> array( '#f5ab35',
											__( 'Air Sign Color', 'zodiacpress' ) ),
		'water_sign_color'			=> array( '#4169e1',
											__( 'Water Sign Color', 'zodiacpress' ) ),
		);

		return $defaults;	
	}

	/**
	 * Setup the Customizer settings and controls
	 */
	public static function register( $wp_customize ) {

		$wp_customize->add_section( 'zp_chart_colors', array(
			'title'		=> __( 'ZodiacPress Chart Drawing', 'zodiacpress' )
		) );

		foreach( self::default_colors() as $key => $value ) {
			$wp_customize->add_setting( "zp_customizer[$key]", array(
				'type'				=> 'option',
				'default'			=> $value[0],
				'sanitize_callback'	=> 'sanitize_hex_color',
				'transport'			=> 'postMessage'
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control( $wp_customize, "zp_$key", array(
					'settings'	=> "zp_customizer[$key]",
					'label'		=> $value[1],
					'section'	=> 'zp_chart_colors'
				) )
			);			
		}

	}
	// Load the js to handle the live preview
	public static function scripts() {
		wp_enqueue_script(
			'zp-customize',
			ZODIACPRESS_URL . 'assets/js/zp-customize.js',
			array( 'customize-controls' )
		);

		wp_localize_script( 'zp-customize', 'zp_chart_colors', array(
			'root' => esc_url_raw( rest_url() ),
			'nonce' => wp_create_nonce( 'wp_rest' ),
			'page_title' => __( 'ZP Chart Drawing Preview', 'zodiacpress' ),
			'ajaxurl' => admin_url( 'admin-ajax.php' )
		) );
		
	}

	/**
	 * Merge settings with defaults to use default colors for any blank settings
	 */
	public static function merge_settings( $settings ) {
		// array of default values
		foreach( self::default_colors() as $k => $v ) {
			$defaults[ $k ] = $v[0];
		}

		$merged = array_merge( array_filter( $defaults, 'strval' ), array_filter( $settings, 'strval' ) );

		return $merged;
	}

	/**
	 * Gets the ZP chart customizer settings.
	 */
	public static function get_settings() {
		$settings = get_option( 'zp_customizer', array() );
		return apply_filters( 'zp_customizer_get_settings', $settings );
	}

	/**
	 * The shortcode to display a live preview of the chart drawing image in the customizer when it is first opened
	 */
	public static function preview_shortcode( $atts ) {
		return wp_kses_post( zp_get_sample_chart_drawing() );
	}

}
// Setup the Customizer settings and controls
add_action( 'customize_register', array( 'ZP_Customize', 'register' ) );

// Take care of any settings left blank
add_filter( 'zp_customizer_get_settings', array( 'ZP_Customize', 'merge_settings' ) );

// Enqueue live preview javascript in Theme Customizer admin screen
add_action( 'customize_controls_enqueue_scripts' , array( 'ZP_Customize', 'scripts' ) );

add_shortcode( 'zp_chart_drawing_preview', array( 'ZP_Customize', 'preview_shortcode' ) );
