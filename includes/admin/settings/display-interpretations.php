<?php
/**
 * Admin Interpretations Page
 *
 * @package     ZodiacPress
 * @subpackage  Admin/Settings
 * @copyright   Copyright (c) 2016-2017, Isabel Castillo
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
*/

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Interpretations Page
 *
 * Renders the Natal Interpretations page contents.
 *
 * @return void
 */
function zp_interpretations_page() {

	$interps_tabs = zp_get_interps_tabs();
	$interps_tabs = empty($interps_tabs) ? array() : $interps_tabs;
	$active_tab    = isset( $_GET['tab'] ) && array_key_exists( $_GET['tab'], $interps_tabs ) ? sanitize_text_field( $_GET['tab'] ) : 'natal_planets_in_signs';
	$sections      = zp_get_interps_tab_sections( $active_tab );
	
	// Get 'main' section which varies depending on which planets are enabled in settings.
	if ( $sections ) {
		reset( $sections );
		$main = key( $sections );
	}

	$section = isset( $_GET['section'] ) && ! empty( $sections ) && array_key_exists( $_GET['section'], $sections ) ? sanitize_text_field( $_GET['section'] ) : $main;

	$option_name = zp_get_interps_option_name( $active_tab, $section );

	ob_start();
	?>
	<div class="wrap">
		<span class="zp-admin-title"><?php _e( 'ZodiacPress', 'zodiacpress' ); ?></span>
		<?php zp_extend_link();
			zp_feedback_link()?>
		<h1 class="nav-tab-wrapper clear">
			<?php
			settings_errors( 'zp-intpers-notices' );
			foreach( $interps_tabs as $tab_id => $tab_name ) {

				$tab_url = add_query_arg( array(
					'settings-updated' => false,
					'tab'              => $tab_id,
				) );

				// Remove the section from the tabs so we always end up at the main section
				$tab_url = remove_query_arg( 'section', $tab_url );

				$active = $active_tab == $tab_id ? ' nav-tab-active' : '';

				echo '<a href="' . esc_url( $tab_url ) . '" title="' . esc_attr( $tab_name ) . '" class="nav-tab' . $active . '">';
					echo esc_html( $tab_name );
				echo '</a>';
			}
			?>
		</h1>
		<?php
		$number_of_sections = count( $sections );
		$number = 0;
		if ( $number_of_sections > 1 ) {

			echo '<div><ul class="subsubsub">';
			foreach( $sections as $section_id => $section_name ) {
				echo '<li>';
				$number++;
				$tab_url = add_query_arg( array(
					'settings-updated' => false,
					'tab' => $active_tab,
					'section' => $section_id
				) );
				$class = '';
				if ( $section == $section_id ) {
					$class = 'current';
				}
				echo '<a class="' . $class . '" href="' . esc_url( $tab_url ) . '">' . $section_name . '</a>';

				if ( $number != $number_of_sections ) {
					echo ' | ';
				}
				echo '</li>';
			}
			echo '</ul></div>';
		}
		?>
		<div id="tab_container">
			<form method="post" action="options.php">
				<?php
				settings_fields( $option_name );
				do_action( 'zp_interps_tab_top_' . $active_tab . '_' . $section );
				do_settings_sections( 'zp_interps_' . $active_tab . '_' . $section );
				do_action( 'zp_interps_tab_bottom_' . $active_tab . '_' . $section  );
				submit_button(); ?>
			</form>
		</div><!-- #tab_container-->
	</div><!-- .wrap -->
	<?php
	echo ob_get_clean();
}
