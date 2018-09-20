<?php
/*
Plugin Name: Transport tax
Plugin URI: https://github.com/ingniq/transport-tax
Description: Calculate Transport tax
Version: 1.0
Author: Igor Filatov
Author URI: https://github.com/ingniq
Copyright: Igor Filatov
Text Domain: ttax
Domain Path: /lang
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'TRANSPORT_TAX_VERSION', '1.0.0' );

require plugin_dir_path( __FILE__ ) . 'includes/class-transport-tax.php';

function run_transport_tax() {
	$plugin = new Transport_Tax();
	$plugin->run();
}

run_transport_tax();