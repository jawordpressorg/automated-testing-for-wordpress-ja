<?php
/**
 * PHPUnit bootstrap file
 */

global $_tests_dir;

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tmpdir = getenv( 'TMPDIR' );
	if ( ! $_tmpdir ) {
		$_tmpdir = '/tmp';
	}
	$_tests_dir = preg_replace( '#/$#', '', $_tmpdir ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	throw new Exception( "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	global $_tests_dir;
	require $_tests_dir . '/data/wp-multibyte-patch/wp-multibyte-patch.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
