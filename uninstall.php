<?php
// if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

$option_name = 'transport_tax_db_version';

delete_option( $option_name );

// drop a custom database table
require_once plugin_dir_path( __FILE__ ) . 'includes/class-transport-tax-data.php';
$data = new Transport_Tax_Data();

global $wpdb;

$res_model              = $wpdb->query( "DROP TABLE IF EXISTS {$data->table_model}" );
$res_zone               = $wpdb->query( "DROP TABLE IF EXISTS {$data->table_zone}" );
$res_year               = $wpdb->query( "DROP TABLE IF EXISTS {$data->table_year}" );
$res_benefit            = $wpdb->query( "DROP TABLE IF EXISTS {$data->table_benefit}" );
$res_category           = $wpdb->query( "DROP TABLE IF EXISTS {$data->table_category}" );
$res_zone_year_benefit  = $wpdb->query( "DROP TABLE IF EXISTS {$data->table_zone_year_benefit}" );
$res_zone_year_category = $wpdb->query( "DROP TABLE IF EXISTS {$data->table_zone_year_category}" );
$res_brand              = $wpdb->query( "DROP TABLE IF EXISTS {$data->table_brand}" );
$res_category_brand     = $wpdb->query( "DROP TABLE IF EXISTS {$data->table_category_brand}" );
