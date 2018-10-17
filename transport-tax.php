<?php
/*
Plugin Name: Transport tax
Plugin URI: https://github.com/ingniq/transport-tax
Description: Calculate Transport tax
Version: 1.0
Author: Igor Filatov
Author URI: https://github.com/ingniq
Copyright: Igor Filatov
Text Domain: transport-tax
Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'TRANSPORT_TAX_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-transport-tax-activator.php
 */
function activate_transport_tax() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-transport-tax-activator.php';
	( new Transport_Tax_Activator )->activate();
}

register_activation_hook( __FILE__, 'activate_transport_tax' );

require plugin_dir_path( __FILE__ ) . 'includes/class-transport-tax.php';

function run_transport_tax() {
	$plugin = new Transport_Tax();
	$plugin->run();
}

run_transport_tax();