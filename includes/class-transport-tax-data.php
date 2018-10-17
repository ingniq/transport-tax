<?php

/**
 * Description
 *
 * @link       https://github.com/ingniq/transport-tax
 * @since      1.0.0
 *
 * @package    Transport_Tax
 * @subpackage Transport_Tax/includes
 */

/**
 * Description
 *
 * Full description
 *
 * @package    Transport_Tax
 * @subpackage Transport_Tax/includes
 * @author     Igor Filatov <ingniq8@gmail.com>
 */
class Transport_Tax_Data {

	/** @var wpdb */
	private $wpdb;

	private $zones;
	private $years;
	private $benefits;
	private $categories;
	private $brands;
	private $models;

	/** @var string */
	public $table_prefix;
	/** @var string */
	public $table_model;
	/** @var string */
	public $table_zone;
	/** @var string */
	public $table_year;
	/** @var string */
	public $table_benefit;
	/** @var string */
	public $table_category;
	/** @var string */
	public $table_zone_year_benefit;
	/** @var string */
	public $table_zone_year_category;
	/** @var string */
	public $table_brand;
	/** @var string */
	public $table_category_brand;
	/**
	 * Transport_Tax_Data constructor.
	 */
	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;

		$this->table_prefix             = $wpdb->prefix . 'transport_tax';
		$this->table_zone               = $this->table_prefix . '_zone';
		$this->table_year               = $this->table_prefix . '_year';
		$this->table_benefit            = $this->table_prefix . '_benefit';
		$this->table_category           = $this->table_prefix . '_category';
		$this->table_zone_year_benefit  = $this->table_prefix . '_zone_year_benefit';
		$this->table_zone_year_category = $this->table_prefix . '_zone_year_category';
		$this->table_brand              = $this->table_prefix . '_brand';
		$this->table_category_brand     = $this->table_prefix . '_category_brand';
		$this->table_model              = $this->table_prefix . '_model';

		return;
	}

	/**
	 * @return mixed
	 */
	public function getZones() {

		$this->zones = $this->wpdb->get_results( "SELECT * FROM {$this->table_zone}" );

		return $this->zones;
	}

	/**
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getOneZone( $params = [] ) {
		$listParams = [
			'id'     => '%d',
			'ext_id' => '%d',
			'alias'  => '%s',
			'value'  => '%s',
		];

		$args = [];
		list( $where, $args ) = $this->addWhere( $params, $listParams, $args );

		$this->zones = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$this->table_zone}" . $where, $args ) );

		return $this->zones;
	}

	/**
	 * @return mixed
	 */
	public function getYears() {
		$this->years = $this->wpdb->get_results( "SELECT * FROM {$this->table_year}" );

		return $this->years;
	}

	/**
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getOneYear( $params = [] ) {
		$listParams = [
			'id'     => '%d',
			'ext_id' => '%d',
			'value'  => '%s',
		];

		$args = [];
		list( $where, $args ) = $this->addWhere( $params, $listParams, $args );

		$this->years = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$this->table_year}" . $where, $args ) );

		return $this->years;
	}

	public function getYearsByZone( $alias ) {
		$zone = $this->getOneZone( [ 'alias' => $alias ] );

		$listParams = [ 'zone_id' => '%d' ];
		$params     = [ 'zone_id' => $zone->id ];

		$args = [];
		list( $where, $args ) = $this->addWhere( $params, $listParams, $args );

		$yearIds = array_map( function ( stdClass $year ) {
			return $year->year_id;
		}, $this->wpdb->get_results( $this->wpdb->prepare( "SELECT year_id FROM {$this->table_zone_year_category}" . $where, $args ) )
		);

		$yearIds     = implode( ', ', array_unique( $yearIds ) );
		$this->years = $this->wpdb->get_results( "SELECT * FROM {$this->table_year} WHERE id IN ({$yearIds})" );

		return $this->years;
	}

	/**
	 *
	 * @return mixed
	 */
	public function getBenefits() {
		$this->benefits = $this->wpdb->get_results( "SELECT * FROM {$this->table_benefit}" );

		return $this->benefits;
	}

	/**
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getOneBenefit( $params = [] ) {
		$listParams = [
			'id'      => '%d',
			'ext_id'  => '%d',
			'zone_id' => '%d',
			'year_id' => '%d',
			'value'   => '%s',
		];

		$args = [];
		list( $where, $args ) = $this->addWhere( $params, $listParams, $args );

		$this->benefits = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$this->table_benefit}" . $where, $args ) );

		return $this->benefits;
	}

	public function getBenefitsByZoneAndYear( $zone_id, $year_id ) {
		$listParams = [
			'zone_id' => '%d',
			'year_id' => '%d',
		];
		$params     = [
			'zone_id' => $zone_id,
			'year_id' => $year_id,
		];

		$args = [];
		list( $where, $args ) = $this->addWhere( $params, $listParams, $args );

		$benefitIds = array_map( function ( stdClass $benefit ) {
			return $benefit->benefit_id;
		}, $this->wpdb->get_results( $this->wpdb->prepare( "SELECT benefit_id FROM {$this->table_zone_year_benefit}" . $where, $args ) )
		);

		$benefitIds     = implode( ', ', array_unique( $benefitIds ) );
		$this->benefits = $this->wpdb->get_results( "SELECT * FROM {$this->table_benefit} WHERE id IN ({$benefitIds})" );

		return $this->benefits;
	}

	/**
	 * @return mixed
	 */
	public function getCategories() {
		$this->categories = $this->wpdb->get_results( "SELECT * FROM {$this->table_category}" );

		return $this->categories;
	}

	/**
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getOneCategory( $params = [] ) {
		$listParams = [
			'id'     => '%d',
			'ext_id' => '%d',
			'value'  => '%s',
		];

		$args = [];
		list( $where, $args ) = $this->addWhere( $params, $listParams, $args );

		$this->categories = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$this->table_category}" . $where, $args ) );

		return $this->categories;
	}

	public function getCategoriesByZoneAndYear( $alias, $ext_id ) {

		$zone = $this->getOneZone( [ 'alias' => $alias ] );
		$year = $this->getOneYear( [ 'ext_id' => $ext_id ] );

		$listParams = [
			'zone_id' => '%d',
			'year_id' => '%d',
		];
		$params     = [
			'zone_id' => $zone->id,
			'year_id' => $year->id,
		];

		$args = [];
		list( $where, $args ) = $this->addWhere( $params, $listParams, $args );

		$categoryIds = array_map( function ( stdClass $benefit ) {
			return $benefit->category_id;
		}, $this->wpdb->get_results( $this->wpdb->prepare( "SELECT category_id FROM {$this->table_zone_year_category}" . $where, $args ) )
		);

		$categoryIds      = implode( ', ', array_unique( $categoryIds ) );
		$this->categories = $this->wpdb->get_results( "SELECT * FROM {$this->table_category} WHERE id IN ({$categoryIds})" );

		return $this->categories;
	}

	/**
	 * @return mixed
	 */
	public function getBrands() {
		$this->brands = $this->wpdb->get_results( "SELECT * FROM {$this->table_brand}" );

		return $this->brands;
	}

	/**
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getOneBrand( $params = [] ) {
		$listParams = [
			'id'     => '%d',
			'ext_id' => '%d',
			'value'  => '%s',
		];

		$args = [];
		list( $where, $args ) = $this->addWhere( $params, $listParams, $args );

		$this->brands = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM {$this->table_brand}" . $where, $args ) );

		return $this->brands;
	}

	public function getBrandsByCategory( $ext_id ) {
		$category = $this->getOneCategory( [ 'ext_id' => $ext_id ] );

		$listParams = [ 'category_id' => '%d' ];
		$params     = [ 'category_id' => $category->id ];

		$args = [];
		list( $where, $args ) = $this->addWhere( $params, $listParams, $args );

		$brandIds = array_map( function ( stdClass $year ) {
			return $year->brand_id;
		}, $this->wpdb->get_results( $this->wpdb->prepare( "SELECT brand_id FROM {$this->table_category_brand}" . $where, $args ) )
		);

		$brandIds     = implode( ', ', array_unique( $brandIds ) );
		$this->brands = $this->wpdb->get_results( "SELECT * FROM {$this->table_brand} WHERE id IN ({$brandIds})" );

		return $this->brands;
	}

	/**
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getModels( $params = [] ) {
		$listParams = [
			'id'       => '%d',
			'ext_id'   => '%d',
			'brand_id' => '%d',
			'value'    => '%s',
		];

		$args = [];
		list( $where, $args ) = $this->addWhere( $params, $listParams, $args );

		$this->models = $this->wpdb->get_results( $this->wpdb->prepare( "SELECT * FROM {$this->table_model}" . $where, $args ) );

		return $this->models;
	}

	/**
	 * @param $params
	 * @param $listParams
	 * @param $args
	 *
	 * @return array
	 */
	private function addWhere( $params, $listParams, $args ) {
		$where = '';
		if ( ! empty ( $params ) ) {
			$whereArgs  = [];
			$whereItems = [];

			$where = " WHERE ";
			foreach ( $params as $param => $value ) {
				if ( array_key_exists( $param, $listParams ) ) {
					$whereItems[] = "{$param} = {$listParams[$param]}";
					$whereArgs[]  = $value;
				} else {
					throw new BadMethodCallException( "Parameter '{$param}' is bad." );
				}
			}
			$where .= implode( ' AND ', $whereItems );

			$args = array_merge( $args, $whereArgs );
		}

		return [ $where, $args ];
	}

}
