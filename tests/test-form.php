<?php
class Test_Form extends WP_UnitTestCase {

	protected $form_data = array();

	public function setUp() {

		$this->form_data = array(
				'name'					=> 'Michael Jackson',
				'month'					=> '8',
				'day'					=> '29',
				'year'					=> '1958',
				'hour'					=> '19',
				'minute'				=> '33',
				'geo_timezone_id'		=> 'America/Chicago',
				'place'					=> 'Gary, Indiana, United States',
				'zp_lat_decimal'		=> '41.59337',
				'zp_long_decimal'		=> '-87.34643',
				'zp_offset_geo'			=> '-5',
				'action'				=> 'zp_birthreport',
				'zp-report-variation'	=> 'birthreport',
				'unknown_time'			=> 'on',
				'house_system'			=> false,
				'sidereal'				=> false
		);
	}

	/**
	 * Unknown birth time checkbox should appear on form when 'Allow unkown time' setting is enabled.
	 */
	public function test_unknown_birth_time_checkbox_enabled() {

		$str = '<input type="checkbox" id="unknown_time" name="unknown_time"';

		// Enable setting to allow unkown birth times
		global $zodiacpress_options;
		$zodiacpress_options = get_option( 'zodiacpress_settings' );
		$zodiacpress_options['birthreport_allow_unknown_bt'] = 1;
		update_option('zodiacpress_settings', $zodiacpress_options);

		ob_start();
		zp_form( 'birthreport', array( 'report' => 'birthreport', 'house_system' => false, 'sidereal' => false) );
		$form = ob_get_clean();

		$this->assertContains( $str, $form, 'Error: Form is missing "Unknown birth time" checkbox.' );

	}


	/**
	  * The unkown birth time NOTE should appear at bottom of form when allowed.
	  */
	public function test_unknown_birthtime_note_allowed() {

		$str = 'If birth time is unknown, the report will not include positions or aspects for the Moon';

		ob_start();
		zp_form( 'birthreport', array( 'report' => 'birthreport', 'house_system' => false, 'sidereal' => false ) );
		$form = ob_get_clean();

		$this->assertContains( $str, $form, 'Error 5: Form is missing the unkown birth time NOTE even though "Allow unknown time" is enabled.' );

	}

	/**
	  * The unkown birth time NOTE should not appear on form when unknown time is disabled.
	  */
	public function test_unknown_birthtime_note_disabled() {

		$str = 'If birth time is unknown, the report will not include positions or aspects for the Moon';

		// Disable setting to allow unkown birth times
		global $zodiacpress_options;
		$zodiacpress_options = get_option( 'zodiacpress_settings' );
		unset( $zodiacpress_options['birthreport_allow_unknown_bt'] );
		update_option('zodiacpress_settings', $zodiacpress_options );

		ob_start();
		zp_form( 'birthreport', array( 'report' => 'birthreport', 'house_system' => false, 'sidereal' => false ) );
		$form = ob_get_clean();

		$this->assertFalse( strpos( $form, $str ), 'Error 6: Form is showing the unkown birth time NOTE at bottom of form even though "Allow unknown time" is disabled.' );
	}

	/**
	 * Unknown birth time checkbox should not appear on form when 'Allow unkown time' setting is disabled.
	 */
	public function test_unknown_birth_time_checkbox_disabled() {

		$str = '<input type="checkbox" id="unknown_time" name="unknown_time"';

		ob_start();
		zp_form( 'birthreport', array( 'report' => 'birthreport', 'house_system' => false, 'sidereal' => false ) );
		$form = ob_get_clean();

		$this->assertFalse( strpos( $form, $str ), 'Error: Form shows "Unknown birth time" checkbox even though the setting to allow it is disabled.' );

	}

	/**
	  * 'Birth time required' should never appear on form for regular birth report.
	  */
	public function test_birthtime_required() {

		$str = 'Birth time is required for this type of report';

		ob_start();
		zp_form( 'birthreport', array( 'report' => 'birthreport', 'house_system' => false, 'sidereal' => false ) );
		$form = ob_get_clean();

		$this->assertFalse( strpos( $form, $str ), 'Error 3: Form shows "Birth time required".' );

		// Repeat test with the setting to allow unkown birth times ENABLED.

		$zodiacpress_options = get_option( 'zodiacpress_settings' );
		$zodiacpress_options['birthreport_allow_unknown_bt'] = 1;
		update_option('zodiacpress_settings', $zodiacpress_options);

		ob_start();
		zp_form( 'birthreport', array( 'report' => 'birthreport', 'house_system' => false, 'sidereal' => false ) );
		$form = ob_get_clean();

		$this->assertFalse( strpos( $form, $str ), 'Error 4: Form shows "Birth time required".' );

	}


	/**
	 * Test that form validation for unknown birth time automatically sets time to noon.
	 */
	public function test_zp_validate_form_unknown_time() {
		
		$actual = zp_validate_form( $this->form_data, true );

		$this->assertInternalType('array', $actual);
		$this->assertEquals( 12, $actual['hour'], 'Hour failed to be set to 12' );
		$this->assertEquals( '00', $actual['minute'], 'Minute failed to be set to 00' );
	}
	/**
	 * Validation form with blank time if unknown time is checked.
	 */
	public function test_zp_validate_form_blank_time_checked() {

		// leave time blank
		$this->form_data['hour'] = '';
		$this->form_data['minute'] = '';

		$actual = zp_validate_form( $this->form_data, true );

		$this->assertInternalType('array', $actual);
		$this->assertEquals( 12, $actual['hour'], 'Hour failed to be set to 12' );
		$this->assertEquals( '00', $actual['minute'], 'Minute failed to be set to 00' );
	}
	/**
	 * Do not validation form with blank time when unknown time is not checked.
	 */
	public function test_zp_validate_form_blank_time() {

		$this->form_data['unknown_time'] = '';
		$this->form_data['hour'] = '';
		$this->form_data['minute'] = '';

		$actual = zp_validate_form( $this->form_data, true );

		$this->assertInternalType('string', $actual);
		$this->assertStringStartsWith('Please select a Birth Time', $actual);

	}

	/**
	 * Test that hours of 00 or 23 validate ok.
	 */
	public function test_zp_validate_form_hours() {

		$this->form_data['unknown_time'] = '';

		$this->form_data['hour'] = '00';
		$actual = zp_validate_form( $this->form_data, true );
		$this->assertInternalType('array', $actual);
		$this->assertEquals( '00', $actual['hour'] );


		$this->form_data['hour'] = 23;
		$actual = zp_validate_form( $this->form_data, true );
		$this->assertInternalType('array', $actual);
		$this->assertEquals( 23, $actual['hour'] );

	}
}