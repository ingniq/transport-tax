<?php
/**
 * Fired during plugin activation
 *
 * @link       https://github.com/ingniq/transport-tax
 * @since      1.0.0
 *
 * @package    Transport_Tax
 * @subpackage Transport_Tax/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Transport_Tax
 * @subpackage Transport_Tax/includes
 * @author     Igor Filatov <ingniq8@gmail.com>
 */
class Transport_Tax_Activator {
	/** @var wpdb */
	private $wpdb;

	/** @var string */
	private $transport_tax_db_version;

	/** @var Transport_Tax_Data $data */
	private $data;

	public function __construct() {
		global $wpdb;
		global $transport_tax_db_version;
		$transport_tax_db_version = '1.0';

		$this->wpdb                     = $wpdb;
		$this->transport_tax_db_version = $transport_tax_db_version;

		$this->data = new Transport_Tax_Data();
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function activate() {
		$this->transport_tax_install();
		$this->transport_tax_install_data();
	}

	private function transport_tax_install() {
		$charset_collate = $this->wpdb->get_charset_collate();

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$sql = "CREATE TABLE IF NOT EXISTS `{$this->data->table_zone}` (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		ext_id integer,
		`alias` varchar(5) NOT NULL,
		`value` varchar(100) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS `{$this->data->table_year}` (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		ext_id integer,
		`value` varchar(100) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS `{$this->data->table_benefit}` (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		ext_id integer,
		`value` text NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS `{$this->data->table_category}` (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		ext_id integer,
		`value` varchar(255) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS `{$this->data->table_zone_year_benefit}` (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		zone_id integer NOT NULL,
		year_id integer,
		benefit_id integer,
		PRIMARY KEY  (id)
	) $charset_collate;";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS `{$this->data->table_zone_year_category}` (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		zone_id integer NOT NULL,
		year_id integer,
		category_id integer,
		PRIMARY KEY  (id)
	) $charset_collate;";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS `{$this->data->table_brand}` (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		ext_id integer,
		category_id integer NOT NULL,
		`value` varchar(255) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS `{$this->data->table_category_brand}` (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		category_id integer NOT NULL,
		brand_id integer,
		PRIMARY KEY  (id)
	) $charset_collate;";
		dbDelta( $sql );

		$sql = "CREATE TABLE IF NOT EXISTS `{$this->data->table_model}` (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		`value` integer,
		`text` varchar(255) NOT NULL,
		brand_id integer NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
		dbDelta( $sql );

		add_option( 'transport_tax_db_version', $this->transport_tax_db_version );
	}

	private function transport_tax_install_data() {
		$benefits_1         = file_get_contents( WP_PLUGIN_DIR . '/transport-tax/includes/data/benefits_1' );
		$benefits_2         = file_get_contents( WP_PLUGIN_DIR . '/transport-tax/includes/data/benefits_2' );
		$benefits_3         = file_get_contents( WP_PLUGIN_DIR . '/transport-tax/includes/data/benefits_3' );
		$brands             = file_get_contents( WP_PLUGIN_DIR . '/transport-tax/includes/data/brands' );
		$categories         = file_get_contents( WP_PLUGIN_DIR . '/transport-tax/includes/data/categories' );
		$category_brand     = file_get_contents( WP_PLUGIN_DIR . '/transport-tax/includes/data/category_brand' );
		$models             = file_get_contents( WP_PLUGIN_DIR . '/transport-tax/includes/data/models' );
		$years              = file_get_contents( WP_PLUGIN_DIR . '/transport-tax/includes/data/years' );
		$zones              = file_get_contents( WP_PLUGIN_DIR . '/transport-tax/includes/data/zones' );
		$zone_year_benefit  = file_get_contents( WP_PLUGIN_DIR . '/transport-tax/includes/data/zone_year_benefit' );
		$zone_year_category = file_get_contents( WP_PLUGIN_DIR . '/transport-tax/includes/data/zone_year_category' );

		$this->wpdb->query( 'START TRANSACTION' );

		$this->wpdb->query( "LOCK TABLES `{$this->data->table_benefit}` WRITE" );
		$res_benefit_1 = $this->wpdb->query( "INSERT IGNORE INTO `{$this->data->table_benefit}`  VALUES {$benefits_1}" );
		$res_benefit_2 = $this->wpdb->query( "INSERT IGNORE INTO `{$this->data->table_benefit}`  VALUES {$benefits_2}" );
		$res_benefit_3 = $this->wpdb->query( "INSERT IGNORE INTO `{$this->data->table_benefit}`  VALUES {$benefits_3}" );
		$this->wpdb->query( "UNLOCK TABLES;" );

		$this->wpdb->query( "LOCK TABLES `{$this->data->table_brand}` WRITE" );
		$res_brand = $this->wpdb->query( "INSERT IGNORE INTO `{$this->data->table_brand}`  VALUES {$brands}" );
		$this->wpdb->query( "UNLOCK TABLES;" );

		$this->wpdb->query( "LOCK TABLES `{$this->data->table_category}` WRITE" );
		$res_category = $this->wpdb->query( "INSERT IGNORE INTO `{$this->data->table_category}`  VALUES {$categories}" );
		$this->wpdb->query( "UNLOCK TABLES;" );

		$this->wpdb->query( "LOCK TABLES `{$this->data->table_category_brand}` WRITE" );
		$res_category_brand = $this->wpdb->query( "INSERT IGNORE INTO `{$this->data->table_category_brand}`  VALUES {$category_brand}" );
		$this->wpdb->query( "UNLOCK TABLES;" );

		$this->wpdb->query( "LOCK TABLES `{$this->data->table_model}` WRITE" );
		$res_model = $this->wpdb->query( "INSERT IGNORE INTO `{$this->data->table_model}`  VALUES {$models}" );
		$this->wpdb->query( "UNLOCK TABLES;" );

		$this->wpdb->query( "LOCK TABLES `{$this->data->table_year}` WRITE" );
		$res_year = $this->wpdb->query( "INSERT IGNORE INTO `{$this->data->table_year}`  VALUES {$years}" );
		$this->wpdb->query( "UNLOCK TABLES;" );

		$this->wpdb->query( "LOCK TABLES `{$this->data->table_zone}` WRITE" );
		$res_zone = $this->wpdb->query( "INSERT IGNORE INTO `{$this->data->table_zone}`  VALUES {$zones}" );
		$this->wpdb->query( "UNLOCK TABLES;" );

		$this->wpdb->query( "LOCK TABLES `{$this->data->table_zone_year_benefit}` WRITE" );
		$res_zone_year_benefit = $this->wpdb->query( "INSERT IGNORE INTO `{$this->data->table_zone_year_benefit}`  VALUES {$zone_year_benefit}" );
		$this->wpdb->query( "UNLOCK TABLES;" );

		$this->wpdb->query( "LOCK TABLES `{$this->data->table_zone_year_category}` WRITE" );
		$res_zone_year_category = $this->wpdb->query( "INSERT IGNORE INTO `{$this->data->table_zone_year_category}`  VALUES {$zone_year_category}" );
		$this->wpdb->query( "UNLOCK TABLES;" );

		if ( $res_benefit_1 && $res_benefit_2 && $res_benefit_3 && $res_brand && $res_category && $res_category_brand && $res_model && $res_year && $res_zone && $res_zone_year_benefit && $res_zone_year_category ) {
			$this->wpdb->query( 'COMMIT' );
		} else {
			$this->wpdb->query( 'ROLLBACK' );
		}
	}
}