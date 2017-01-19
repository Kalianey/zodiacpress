<?php
/**
 * Tools
 *
 * These are functions used for displaying ZP tools such as the export/import system.
 *
 * @package     ZodiacPress
 * @subpackage  Admin/Settings
 * @copyright   Copyright (c) 2016-2017, Isabel Castillo
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Tools
 *
 * Shows the tools panel which contains ZP-specific tools.
 *
 * @return      void
 */
function zp_tools_page() {
	$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'cleanup';
	?>
	<div class="wrap">
	<?php zp_extend_link();
		zp_feedback_link(); ?>
	<h1 class="nav-tab-wrapper clear">
		<?php foreach( zp_get_tools_tabs() as $tab_id => $tab_name ) {
			$active = $active_tab == $tab_id ? ' nav-tab-active' : '';

			$tab_url = add_query_arg( array( 'tab' => $tab_id ) );

			// Remove the arg that triggers the admin notice
			$tab_url = remove_query_arg( 'zp-done', $tab_url );

			echo '<a href="' . esc_url( $tab_url ) . '" title="' . esc_attr( $tab_name ) . '" class="nav-tab' . $active . '">' . esc_html( $tab_name ) . '</a>';
		}
		?>
	</h1>
	<div class="metabox-holder">
		<?php do_action( 'zp_tools_tab_' . $active_tab ); ?>
	</div>
	</div>
	<?php
}

/**
 * Retrieve tools tabs
 *
 * @return      array
 */
function zp_get_tools_tabs() {
	$tabs 					= array();
	$tabs['cleanup']		= __( 'Cleanup', 'zodiacpress' );
	$tabs['sysinfo']		= __( 'System Info', 'zodiacpress' );
	$tabs['import_export']	= __( 'Export/Import', 'zodiacpress' );
	return apply_filters( 'zp_tools_tabs', $tabs );
}
/**
 * Build the link that triggers a specific tool.
 *
 * @param  string $tool Tool to trigger
 * @return string       URL that triggers the tool function
 */
function zp_tool_link( $tool ) {

	$args['zp_cleanup_tool']	= $tool;
	$args['_nonce']				= wp_create_nonce( 'zp_cleanup_tool' );

	return esc_url( add_query_arg( $args, admin_url( 'admin.php' ) ) );
}

/**
 * Retrieve Cleanup tools
 *
 * @return      array
 */
function zp_get_cleanup_tools() {
	$tools = array(
		'natal_in_signs'	=> array(
								'label'	=> __( 'Natal Planets in Signs', 'zodiacpress' ),
								'desc'	=> __( 'Delete all interpretations text for natal planets/points in the signs.', 'zodiacpress' )
		),
		'natal_in_houses'	=> array(
								'label'	=> __( 'Natal Planets in Houses', 'zodiacpress' ),
								'desc'	=> __( 'Delete all interpretations text for natal planets/points in the houses.', 'zodiacpress' )
		),
		'natal_aspects'		=> array(
								'label'	=> __( 'Natal Aspects', 'zodiacpress' ),
								'desc'	=> __( 'Delete all interpretations text for natal aspects.', 'zodiacpress' )
		)
	);
	return apply_filters( 'zp_cleanup_tools', $tools );
}

/**
 * Display Cleanup Tools tab
 */
function zp_tools_cleanup_display() {
	?>
	<p><?php _e( 'Use these tools to <strong>permanently delete</strong> your Interpretations text. Be aware that clicking these buttons will <strong>permanently erase</strong> your Interpretations.', 'zodiacpress' ); ?></p>
	<table class="widefat zp-tools-table" id="zp-tools-cleanup">
		<thead>
			<tr>
				<th data-override="key" class="row-title"><?php _e( 'Cleanup Interpretations', 'zodiacpress' ); ?></th>
				<th data-override="value"></th>
			</tr>
		</thead>
		<tbody>

			<?php foreach( zp_get_cleanup_tools() as $id => $tool ) { ?>
				<tr>
					<td class="row-title"><label for="tablecell"><?php echo esc_html( $tool['label'] ); ?></label></td>
					<td><a href="<?php echo esc_url( zp_tool_link( $id ) ); ?>" class="button-secondary"><?php _e( 'Delete', 'zodiacpress' ); ?></a>
						<span class="zp-tools-desc"><?php echo esc_html( $tool['desc'] ); ?></span></td>
				</tr>
			<?php }
			do_action( 'zp_system_tools_cleanup_table' ); ?>
		</tbody>
	</table>
	<?php
}
add_action( 'zp_tools_tab_cleanup', 'zp_tools_cleanup_display' );

/**
 * Display System Tools tab.
 */
function zp_tools_sysinfo_display() {

	$out = '### Begin System Info ###' . "\n\n";
	$out .= '-- Server Info' . "\n\n";
	$out .= 'Server Software:          ' . $_SERVER['SERVER_SOFTWARE'] . "\n";
	$out .= 'PHP Version:              ' . PHP_VERSION . "\n";
	$out .= 'PHP_SHLIB_SUFFIX:         ' . PHP_SHLIB_SUFFIX . "\n";
	$out .= 'exec() Function:          ' . ( zp_is_func_enabled( 'exec' ) ? 'Enabled' : 'Disabled' ) . "\n";
	$out .= 'chmod() Function:         ' . ( zp_is_func_enabled( 'chmod' ) ? 'Enabled' : 'Disabled' ) . "\n";
	$out .= "\n" . '-- ZodiacPress Info' . "\n\n";
	$out .= 'Ephemeris permissions:    ' . ( zp_is_sweph_executable() ? 'Executable' : 'Not executable' ) . "\n";

	$out .= "\n" . '-- WordPress Info' . "\n\n";
	$out .= 'WP Version:               ' . get_bloginfo('version') . "\n";
	$out .= 'Multisite:                ' . ( is_multisite() ? 'Yes' : 'No' ) . "\n";
	$out .= 'WP_DEBUG:                 ' . ( defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' : 'Disabled' : 'Not set' ) . "\n";
	$out .= 'WP Memory Limit:          ' . WP_MEMORY_LIMIT . "\n";
	$out .= "\n" . '-- Theme' . "\n\n";
	$active_theme = wp_get_theme();
	$out .= 'Theme Name:               ' . $active_theme->Name . "\n";
	$out .= 'Theme Version:            ' . $active_theme->Version . "\n";
	if ( is_child_theme() ) {
		$parent_theme = wp_get_theme( $active_theme->Template );
		$out .= 'Parent Theme Name:        ' . $parent_theme->Name . "\n";
		$out .= 'Parent Theme Version:     ' . $parent_theme->Version . "\n";
	}
	$out .= 'Is Child Theme:           ' . ( empty( $parent_theme ) ? 'No' : 'Yes' ) . "\n";
	$out .= "\n" . '-- Active Plugins' . "\n\n";
	$active_plugins = (array) get_option( 'active_plugins', array() );

	if ( is_multisite() )
		$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );

	$wp_plugins = array();
	foreach ( $active_plugins as $plugin ) {

		$plugin_data    = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
		if ( ! empty( $plugin_data['Name'] ) ) {
			$wp_plugins[] = $plugin_data['Name'] . ' by ' . $plugin_data['AuthorName'] . ' version ' . $plugin_data['Version'];
		}
	}
	if ( sizeof( $wp_plugins ) == 0 ) {
		$out .= '-';
	} else {
		$out .= implode( ",\n", $wp_plugins );
	}
	$out .= "\n\n" . '### End System Info ###';
	?>
	<p><?php _e( 'The system info is a built-in debugging tool. If you contact support, please provide this info.', 'zodiacpress' ); ?></p>
	<p><?php _e( '(Do not be afraid to paste this info into the support forum because this info does not reveal your website name or URL.)', 'zodiacpress' ); ?></p>
	<form action="<?php echo esc_url( admin_url( 'admin.php?page=zodiacpress-tools&tab=sysinfo' ) ); ?>" method="post" dir="ltr">
	<textarea readonly="readonly" onclick="this.focus(); this.select()" id="system-info-textarea" name="zp-sysinfo" title="To copy the system info, click below then press Ctrl + C (on a PC) or Cmd + C (on a Mac)."><?php echo esc_textarea( $out ); ?>
	</textarea>
	</form>
	<?php

}
add_action( 'zp_tools_tab_sysinfo', 'zp_tools_sysinfo_display' );

/**
 * Display the tools export/import tab
 *
 * @return      void
 */
function zp_tools_import_export_display() {
	?>
	<div class="stuffbox">
		<h3><span><?php _e( 'Export Interpretations', 'zodiacpress' ); ?></span></h3>
		<div class="inside">
			<p><?php _e( 'Export your ZodiacPress interpretations for this site as a .json file. This allows you to easily import your interpretations into another site.', 'zodiacpress' ); ?></p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin.php?page=zodiacpress-tools&tab=import_export' ) ); ?>">
				<p>
					<input type="hidden" name="zp-action" value="export_interps" />
					<?php wp_nonce_field( 'zp_export_interps_nonce', 'zp_export_interps_nonce' ); ?>
					<?php submit_button( __( 'Export Interpretations', 'zodiacpress' ), 'secondary', 'submit', false ); ?>
				</p>
			</form>
		</div>
	</div>

	<div class="stuffbox">
		<h3><span><?php _e( 'Import Interpretations', 'zodiacpress' ); ?></span></h3>
		<div class="inside">
			<p><?php _e( 'Import your ZodiacPress interpretations from a .json file. This file can be obtained by exporting the interpretations on another site using the button above.', 'zodiacpress' ); ?></p>
			<p><?php _e( 'NOTE: IMPORTED INTERPRETATIONS WILL COMPLETELY OVERRIDE ANY CURRENT EXISTING INTERPRETATIONS ON THIS SITE. ANY EXISTING INTERPRETATIONS ON THIS SITE WILL BE DELETED.', 'zodiacpress' ); ?></p>
			<form method="post" enctype="multipart/form-data" action="<?php echo esc_url( admin_url( 'admin.php?page=zodiacpress-tools&tab=import_export' ) ); ?>">
				<p>
					<input type="file" name="import_file"/>
				</p>
				<p>
					<input type="hidden" name="zp-action" value="import_interps" />
					<?php wp_nonce_field( 'zp_import_interps_nonce', 'zp_import_interps_nonce' ); ?>
					<?php submit_button( __( 'Import Interpretations', 'zodiacpress' ), 'secondary', 'submit', false ); ?>
				</p>
			</form>
		</div>
	</div>

	<div class="stuffbox">
		<h3><span><?php _e( 'Export Settings', 'zodiacpress' ); ?></span></h3>
		<div class="inside">
			<p><?php _e( 'Export the ZodiacPress settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'zodiacpress' ); ?></p>
			<form action="<?php  echo esc_url( admin_url( 'admin.php?page=zodiacpress-tools&tab=import_export' ) ); ?>" method="post">
				<p>
					<input type="hidden" name="zp-action" value="export_settings" />
					<?php wp_nonce_field( 'zp_export_settings_nonce', 'zp_export_settings_nonce' ); ?>
					<?php submit_button( __( 'Export Settings', 'zodiacpress' ), 'secondary', 'submit', false ); ?>
				</p>
			</form>
		</div>
	</div>

	<div class="stuffbox">
		<h3><span><?php _e( 'Import Settings', 'zodiacpress' ); ?></span></h3>
		<div class="inside">
			<p><?php _e( 'Import the ZodiacPress settings from a .json file. This file can be obtained by exporting the settings on another site using the button above.', 'zodiacpress' ); ?></p>
			<form method="post" enctype="multipart/form-data" action="<?php echo esc_url( admin_url( 'admin.php?page=zodiacpress-tools&tab=import_export' ) ); ?>">
				<p>
					<input type="file" name="import_file"/>
				</p>
				<p>
					<input type="hidden" name="zp-action" value="import_settings" />
					<?php wp_nonce_field( 'zp_import_settings_nonce', 'zp_import_settings_nonce' ); ?>
					<?php submit_button( __( 'Import Settings', 'zodiacpress' ), 'secondary', 'submit', false ); ?>
				</p>
			</form>
		</div>
	</div>
	<?php
}
add_action( 'zp_tools_tab_import_export', 'zp_tools_import_export_display' );

/**
 * Process a settings export that generates a .json file of the ZodiacPress settings
 *
 * @return      void
 */
function zp_tools_process_export_settings() {

	if ( empty( $_POST['zp_export_settings_nonce'] ) )
		return;
	if ( ! wp_verify_nonce( $_POST['zp_export_settings_nonce'], 'zp_export_settings_nonce' ) )
		return;
	if ( ! current_user_can( 'manage_zodiacpress_settings' ) )
		return;
	$settings = array();
	$settings = get_option( 'zodiacpress_settings' );
	ignore_user_abort( true );
	if ( zp_is_func_enabled( 'set_time_limit' ) ) {
		set_time_limit( 0 );
	}
	nocache_headers();
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=zp-settings-export-' . date( 'm-d-Y' ) . '.json' );
	header( "Expires: 0" );
	echo json_encode( $settings );
	exit;
}
add_action( 'zp_export_settings', 'zp_tools_process_export_settings' );

/**
 * Process a settings import from a json file
 *
 * @return void
 */
function zp_tools_process_import_settings() {
	if( empty( $_POST['zp_import_settings_nonce'] ) )
		return;
	if( ! wp_verify_nonce( $_POST['zp_import_settings_nonce'], 'zp_import_settings_nonce' ) )
		return;
	if( ! current_user_can( 'manage_zodiacpress_settings' ) )
		return;

	if( zp_get_file_extension( $_FILES['import_file']['name'] ) != 'json' ) {
		wp_die( __( 'Please upload a valid .json file', 'zodiacpress' ), __( 'Error', 'zodiacpress' ), array( 'response' => 400 ) );
	}

	if ( 0 !== strpos( $_FILES['import_file']['name'], 'zp-settings-export' ) ) {
		wp_die( __( 'Please upload a valid ZodiacPress zp-settings-export file', 'zodiacpress' ), __( 'Error', 'zodiacpress' ), array( 'response' => 400 ) );	
	}

	$import_file = $_FILES['import_file']['tmp_name'];
	if( empty( $import_file ) ) {
		wp_die( __( 'Please upload a file to import', 'zodiacpress' ), __( 'Error', 'zodiacpress' ), array( 'response' => 400 ) );
	}
	// Retrieve the settings from the file and convert the json object to an array
	$settings = zp_object_to_array( json_decode( file_get_contents( $import_file ) ) );

	update_option( 'zodiacpress_settings', $settings );
	wp_safe_redirect( esc_url_raw( admin_url( 'admin.php?page=zodiacpress-tools&tab=import_export&zp-done=settings-imported' ) ) ); exit;
}
add_action( 'zp_import_settings', 'zp_tools_process_import_settings' );

/**
 * Process an Interpretations export that generates a .json file of your ZP Interpretations
 *
 * @return      void
 */
function zp_tools_process_export_interps() {
	if ( empty( $_POST['zp_export_interps_nonce'] ) )
		return;
	if ( ! wp_verify_nonce( $_POST['zp_export_interps_nonce'], 'zp_export_interps_nonce' ) )
		return;
	if ( ! current_user_can( 'manage_zodiacpress_settings' ) )
		return;

	// Collect all interpretions options
	$option_names = zp_get_all_interps_options_names();

	$interps = array();

	foreach ( $option_names as $option ) {
		$interps[ $option ] = get_option( $option );
	}

	ignore_user_abort( true );
	if ( zp_is_func_enabled( 'set_time_limit' ) ) {
		set_time_limit( 0 );
	}
	nocache_headers();
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=zp-interpretations-' . date( 'm-d-Y' ) . '.json' );
	header( "Expires: 0" );
	echo json_encode( $interps );
	exit;
}
add_action( 'zp_export_interps', 'zp_tools_process_export_interps' );

/**
 * Process an Interpretations import from a json file
 *
 * @return void
 */
function zp_tools_process_import_interps() {

	if( empty( $_POST['zp_import_interps_nonce'] ) )
		return;
	if( ! wp_verify_nonce( $_POST['zp_import_interps_nonce'], 'zp_import_interps_nonce' ) )
		return;
	if( ! current_user_can( 'manage_zodiacpress_settings' ) )
		return;

	if( zp_get_file_extension( $_FILES['import_file']['name'] ) != 'json' ) {
		wp_die( __( 'Please upload a valid .json file', 'zodiacpress' ), __( 'Error', 'zodiacpress' ), array( 'response' => 400 ) );
	}

	if ( 0 !== strpos( $_FILES['import_file']['name'], 'zp-interpretations' ) ) {
		wp_die( __( 'Please upload a valid ZodiacPress interpretations file', 'zodiacpress' ), __( 'Error', 'zodiacpress' ), array( 'response' => 400 ) );	
	}

	$import_file = $_FILES['import_file']['tmp_name'];
	if( empty( $import_file ) ) {
		wp_die( __( 'Please upload a file to import', 'zodiacpress' ), __( 'Error', 'zodiacpress' ), array( 'response' => 400 ) );
	}
	// Retrieve the interpretations from the file and convert the json object to an array
	$interps = zp_object_to_array( json_decode( file_get_contents( $import_file ) ) );

	foreach ( $interps as $option => $value ) {

		// Make sure this option is 1 of our interpretations
		$all_possible_interps_options = zp_get_all_interps_options_names();
		if ( in_array( $option, $all_possible_interps_options ) ) {
			update_option( $option, $value );
		}

	}
	wp_safe_redirect( esc_url_raw( admin_url( 'admin.php?page=zodiacpress-tools&tab=import_export&zp-done=interps-imported' ) ) ); exit;
}
add_action( 'zp_import_interps', 'zp_tools_process_import_interps' );