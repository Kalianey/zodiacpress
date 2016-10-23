<?php 
add_shortcode( 'birthreport', 'zp_birthreport_shortcode' );
/**
 * Birth report shortcode.
 */
function zp_birthreport_shortcode( $atts ) {
	// Allow addons to have different default titles
	$default_title = apply_filters( 'zp_shortcode_default_form_title', __( 'Get An Astrology Birth Report', 'zodiacpress' ), $atts );

	$report_atts = shortcode_atts( array(
		'report'		=> 'birthreport',
		'form_title'	=> $default_title,
		'sidereal'		=> false,
		'house_system'	=> false
	), $atts, 'birthreport' );

	wp_enqueue_style( 'zp' );
	if ( is_rtl() ) {
		wp_enqueue_style( 'zp-rtl' );
	}
	wp_enqueue_script( 'zp' );
	ob_start();
	?>
	<div id="zp-form-wrap">
		<?php if ( $report_atts[ 'form_title' ] ) { ?>
			<h2><?php echo esc_html( $report_atts['form_title'] ); ?></h2>
		<?php }
		wp_kses_post ( zp_form( 'birthreport', $report_atts ) ); ?>
	</div><!-- #zp-form-wrap -->

	<div id="zp-report-wrap">
		<?php
		// allow Start Over link to be manipulated with filter
		if ( apply_filters( 'zp_show_start_over_link', true, $report_atts['report'] ) ) { ?>
			<p class="zp-report-backlink">
				<a href="<?php the_permalink(); ?>"><?php _e('Start Over', 'zodiacpress'); ?></a>
			</p>
		<?php
		}

		do_action( 'zp_birthreport_content_before', array( 'report' => $report_atts['report'] ) );
		?>
		<div id="zp-report-content"></div><!-- will be filled by ajax -->
	</div>
	<?php
	return ob_get_clean();
}