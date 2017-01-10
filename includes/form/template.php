<?php
/**
 * Form Template
 *
 * @package     ZodiacPress
 * @subpackage  Form
 * @copyright   Copyright (c) 2016-2017, Isabel Castillo
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Render the HTML for the Name form field.
 */
function zp_name_form_field() {
	?>
	<p class="zp-name-field">
		<label for="name" class="zp-form-label"><?php _e( 'Name', 'zodiacpress' ); ?> </label>
		<span class="zp-input-text-wrap"><input id="name" name="name" class="zp-input-text" type="text" /></span>
	</p>
	<?php
}
/**
 * Renders the HTML select options for the Month form field.
 */
function zp_month_select_options() {
	$months = zp_get_i18n_months();
	?>
	<option value=""><?php _e('Month', 'zodiacpress'); ?></option>

	<?php foreach ( zp_get_i18n_months() as $key => $label ) { ?>
		<option value="<?php echo $key; ?>"><?php echo $label; ?></option>
	<?php
	}

}

/**
 * Renders the HTML select options for the Day form field.
 */
function zp_day_select_options() {

	$days 	= range( 1, 31 );
	$labels = zp_i18n_numbers();
	?>
	<option value=""><?php _e('Day', 'zodiacpress'); ?></option>
	<?php foreach ( $days as $day ) { ?>
		<option value="<?php echo $day; ?>"><?php echo $labels[ $day ]; ?></option>
		<?php
	}
}

/**
 * Renders the HTML select options for the Year form field.
 * @todo must update $accepted_yrs annually to avoid mismatch of key=>value with i18n year. Update it both here and in zp_i18n_years() so that the final year is 1 year into the future.
 */
function zp_year_select_options() {
	?>
	<option value=""><?php _e('Year', 'zodiacpress'); ?></option>
	<?php 
	$accepted_yrs = range( 1900, 2018);
	$labels = zp_i18n_years();
	$years = array_combine( $accepted_yrs, $labels );
	arsort( $years );

	// Allow years to be added with filter.
	$years = apply_filters( 'zp_year_select_options', $years );

	foreach ( $years as $year => $label ) {
		?>
		<option value="<?php echo $year; ?>"><?php echo $label; ?></option>
		<?php
	}
}
/**
 * Renders the HTML select options for the Hour form field.
 */
function zp_hour_select_options() {

	$hours = array_slice( zp_i18n_numbers_zeros(), 0, 24, true );
	?>
	<option value=""><?php _e( 'Hour', 'zodiacpress' ) ; ?></option>
	<?php

	foreach ( $hours as $hour => $label ) {
		$key = (int) $hour;
		?>
		<option value="<?php echo $hour; ?>"><?php echo $label . ' (' . zp_get_12_hour( $key ) . ')'; ?></option>
	<?php }
}

/**
 * Renders the HTML select options for the Minute form field.
 */
function zp_minute_select_options() {
	?>
	<option value=""><?php _e( 'Minute', 'zodiacpress' ) ; ?></option>
	<?php
	foreach ( zp_i18n_numbers_zeros() as $minute => $label ) { ?>
		<option value="<?php echo $minute; ?>"><?php echo $label; ?></option>

	<?php }
}

/**
 * Render the Month form field
 */
function zp_month_form_field() {
	?>
	<label for="month" class="screen-reader-text">
	<?php _e( 'Birth Month', 'zodiacpress' ); ?></label>
	<select id="month" name="month" required><?php zp_month_select_options(); ?></select>
	<?php
}

/**
 * Render the Day form field
 */
function zp_day_form_field() {
	?>
	<label for="day" class="screen-reader-text">
	<?php _e( 'Birth Day', 'zodiacpress' ); ?></label>
	<select id="day" name="day" required><?php zp_day_select_options(); ?></select>
	<?php

}

/**
 * Display the form to generate an astrology report
 *
 * @param string $report Identifier to distinguish this type of report.
 * @param array $args 
 */
function zp_form( $report, $args = array() ) {

	global $zodiacpress_options;

	$allow_unknown_bt_key_prefix = $report;
	if ( false !== strpos( $args['report'], 'planet_lookup' ) ) {
		$allow_unknown_bt_key_prefix = 'planet_lookup';
	}
	$allow_unknown_bt_key = $allow_unknown_bt_key_prefix . '_allow_unknown_bt';
	$allow_unknown_bt = empty( $zodiacpress_options[ $allow_unknown_bt_key ] ) ? false : true;
	?>
	<noscript class="ui-state-highlight"><?php _e( 'This form requires JavaScript. Your browser either does not support JavaScript or has it disabled.', 'zodiacpress' ); ?></noscript>
	<form id="zp-<?php echo esc_attr( $report ); ?>-form" method="post" class="zp-form">
	
		<?php 
		// Show name field only for reports that require it
		if (  apply_filters( 'zp_form_show_name_field', true, $args['report'] ) ) {

			do_action( 'zp_form_above_name', $report, $args );
			zp_name_form_field();
			do_action( 'zp_form_below_name', $report, $args );
	
		}
		?>

		<div id="zp-ajax-birth-data">
			<fieldset class="zp-birthdate">
    			<legend><?php _e( 'Birth Date', 'zodiacpress' ); ?></legend>
				<?php 
				if ( zp_is_month_before_day() ) {
					zp_month_form_field();
					zp_day_form_field();
				} else {
					zp_day_form_field();
					zp_month_form_field();
				}
				?>

				<label for="year" class="screen-reader-text">
					<?php _e( 'Birth Year', 'zodiacpress' ); ?></label>
				<select id="year" name="year" required><?php zp_year_select_options(); ?></select>
			</fieldset>

			<fieldset class="zp-birthtime">
    			<legend><?php _e( 'Exact Birth Time', 'zodiacpress' ); ?></legend>
				<label for="hour" class="screen-reader-text">
					<?php _e( 'Birth Hour', 'zodiacpress' ); ?></label>
				<select id="hour" name="hour"><?php zp_hour_select_options(); ?></select>
				<label for="minute" class="screen-reader-text">
					<?php _e( 'Birth Minute', 'zodiacpress' ); ?></label>
				<select id="minute" name="minute"><?php zp_minute_select_options(); ?></select>
				<?php
				// Show the unknown time checkbox, but not for reports that require a birth time
				if ( ! in_array( $args['report'], apply_filters( 'zp_reports_require_birthtime', array() ) ) ) {
					// ...only if unkown time is allowed in settings
					if ( $allow_unknown_bt ) { ?>
						<p class="zp-unknown-time-field zp-small">
							<label for="unknown_time" class="screen-reader-text"><?php _e( 'Unknown Birth Time', 'zodiacpress' ); ?></label>
							<input type="checkbox" id="unknown_time" name="unknown_time" /> <?php echo apply_filters( 'zp_unknown_birth_time_checkbox', 'If birth time is unknown, check this box.', 'zodiacpress' ); ?><strong>*</strong></p>
					<?php } 			
				
				} else { 

					echo apply_filters( 'zp_birth_time_required',
						'<p class="zp-unknown-time-field zp-small">* ' .
						__( 'Birth time is required for this type of report.', 'zodiacpress' ) .
						'</p>',
						$args );
	
				} ?>
			</fieldset>
			<p class="zp-birthplace">
				<label for="place" class="zp-form-label"><?php _e( 'Birth City', 'zodiacpress' ); ?></label>
				<span class="ui-widget zp-input-text-wrap">
					<input id="place" name="place" class="zp-input-text" />
				</span>
			</p>
			
			<span id="zp-timezone-id"></span>

			<?php do_action( 'zp_form_below_person_one_ajax', $report, $args ); ?>
			<input type="hidden" name="zp-report-variation" value="<?php echo $args['report']; ?>">
			
		</div><!-- end # zp-ajax-birth-data -->

		<p id="zp-offset-wrap" class="zp-clear">
			<span id="zp-offset-label"></span>
			<input id="zp_offset_geo" name="zp_offset_geo" size="47" type="text" tabindex="-1" />
		</p>
		<?php do_action( 'zp_form_below_person_one_offset', $report, $args ); ?>

		<p>
			<button id="zp-fetch-offset" class="zp-button"><?php _e('Next', 'zodiacpress'); ?></button>
			<?php if ( $args['sidereal'] ) { ?>
				<input type="hidden" name="zp_report_sidereal" value="<?php echo $args['sidereal']; ?>">
			<?php }
			if ( $args['house_system'] ) { ?>
				<input type="hidden" name="zp_report_house_system" value="<?php echo $args['house_system']; ?>">
			<?php } ?>
			<input type="hidden" name="action" value="zp_<?php echo esc_attr( $report ); ?>">
			<input type="button" id="zp-fetch-<?php echo esc_attr( $report ); ?>" class="zp-button" value="<?php _e('Submit', 'zodiacpress'); ?>" /></p>

		<p id="zp-form-tip" class="zp-small"><?php _e( 'Tip: Make sure the <strong>UTC time offset</strong> is correct. If it\'s wrong, you can change it.', 'zodiacpress' ); ?></p>

		<?php
		// Add note about unknown birth time, but not for reports that require a birth time
		if ( ! in_array( $args['report'], apply_filters( 'zp_reports_require_birthtime', array() ) ) ) {
				// ...only if unkown time is allowed in settings
			if ( $allow_unknown_bt ) {

				echo apply_filters( 'zp_allow_unknown_time_note',
						'<p class="zp-birth-time-note zp-small">' .
						__( '<strong>* NOTE: </strong> If birth time is unknown, the report will not include positions or aspects for the Moon, Ascendant, Midheaven, Vertex, or Part of Fortune, nor will it include House positions for any planets.', 'zodiacpress' ) .
						'</p>',
						$args );
			}
		}
		
		do_action( 'zp_form_bottom', $allow_unknown_bt, $report, $args ); ?>
	</form>
	<?php
}
