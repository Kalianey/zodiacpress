<?php
 
/**
 * Set up environment for my plugin's tests suite.
 */
 
/**
 * The path to the WordPress tests
 */
$_tests_dir = ( strtolower( PHP_SHLIB_SUFFIX ) === 'dll' ) ? 'C:\Apache24\tmp\wordpress-tests-lib' : '/var/tmp/wordpress-tests-lib';

/**
 * The WordPress tests functions.
 *
 * We are loading this so that we can add our tests filter
 * to load the plugin, using tests_add_filter().
 */
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin main file.
 *
 * The plugin won't be activated within the test WP environment,
 * that's why we need to load it manually.
 *
 * You will also need to perform any installation necessary after
 * loading your plugin, since it won't be installed.
 */
function _manually_load_plugin() {
	require dirname(dirname(__FILE__)) . '/zodiacpress.php';
	// require dirname(dirname(dirname(__FILE__))) . '/zp-windows-server/zp-windows-server.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );
 
/**
 * Sets up the WordPress test environment.
 */
require $_tests_dir . '/includes/bootstrap.php';

// Activate core plugin, ZodiacPress
activate_plugin( 'zodiacpress/zodiacpress.php' );
// The activation hook is not run, so do it manually
ZodiacPress::activate( null );
global $zodiacpress_options;
$zodiacpress_options = get_option( 'zodiacpress_settings' );
echo "Activated ZodiacPress ........................\n";

// Include helper
require_once 'helper.php';