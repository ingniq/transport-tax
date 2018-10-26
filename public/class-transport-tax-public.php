<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/ingniq/transport-tax
 * @since      1.0.0
 *
 * @package    Transport_Tax
 * @subpackage Transport_Tax/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Transport_Tax
 * @subpackage Transport_Tax/public
 * @author     Igor Filatov <ingniq8@gmail.com>
 */
class Transport_Tax_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Transport_Tax_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Transport_Tax_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/transport-tax-public.css', [], $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Transport_Tax_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Transport_Tax_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/transport-tax-public.js', [ 'jquery' ], $this->version, true );

	}

	public function transport_tax_ajax() {
		wp_localize_script( $this->plugin_name, 'taxajax',
			[
				'url' => admin_url( 'admin-ajax.php' ),
			]
		);
	}

	public function get_zones() {
		$data = new Transport_Tax_Data();

		wp_die( json_encode( $data->getZones() ) );
	}

	public function get_years() {
		$post_data = $_POST;
		$data      = new Transport_Tax_Data();

		wp_die( json_encode( $data->getYearsByZone( $post_data['alias'] ) ) );
	}

	public function get_benefits() {
		$post_data = $_POST;
		$data      = new Transport_Tax_Data();

		$zone = $data->getOneZone( [ 'alias' => $post_data['alias'] ] );
		$year = $data->getOneYear( [ 'ext_id' => $post_data['year'] ] );

		wp_die( json_encode( $data->getBenefitsByZoneAndYear( $zone->id, $year->id ) ) );
	}

	public function get_categories() {
		$post_data = $_POST;
		$data      = new Transport_Tax_Data();

		wp_die( json_encode( $data->getCategoriesByZoneAndYear( $post_data['alias'], $post_data['year'] ) ) );
	}

	public function get_brands() {
		$post_data = $_POST;
		$data      = new Transport_Tax_Data();

		wp_die( json_encode( $data->getBrandsByCategory( $post_data['category'] ) ) );
	}

	public function get_models() {
		$post_data = $_POST;
		$data      = new Transport_Tax_Data();

		$brand = $data->getOneBrand( [ 'ext_id' => $post_data['brand'] ] );

		wp_die( json_encode( $data->getModels( [ 'brand_id' => $brand->id ] ) ) );
	}

	public function calculate_tax() {
		$data = $_POST;

		$list_params = [
			'year'       => 'ctl00$ctl05$ctl00$ddlYear',
			'month'      => 'ctl00$ctl05$ctl00$ddlTaxMonth',
			'category'   => 'ctl00$ctl05$ctl00$ddlTaxCategory',
			'brand'      => 'ctl00$ctl05$ctl00$ddlBrand',
			'model'      => 'ctl00$ctl05$ctl00$ddlModel',
			'power'      => 'ctl00$ctl05$ctl00$tbValue',
			'model_year' => 'ctl00$ctl05$ctl00$tbModelYear',
			'benefit'    => 'Benefit',
		];

		$request_url = "https://www.nalog.ru/{$data['zone']}/service/calc_transport/";

		$args = [
			'method'  => 'POST',
			'headers' => [ 'Content-Type' => 'application/x-www-form-urlencoded' ],
			'body'    => [
				'ctl00$ctl05$ctl00$b_Go' => 'Рассчитать',
				'__VIEWSTATE'            => 'xcsitAQgSOUbxUzvsl4n5WdZ5Atbkje9v23RJPi9u7tLnUg9pET+sP3BpsEit+n5rMfBJQ==',
			],
		];

		foreach ( $data as $param => $val ) {
			if ( array_key_exists( $param, $list_params ) ) {
				$args['body'][ $list_params[ $param ] ] = $val;
			}
		}

		$response = wp_remote_post( $request_url, $args );

		if ( ! is_array( $response ) ) {
			$response = $this->repeatRequest( $request_url, $args );
		}

		$html = wp_remote_retrieve_body( $response );

		$dom = new DOMDocument;
		$dom->loadHTML( $html );

		$textResult = $dom->getElementById( 'ctl00_ctl05_ctl00_dResultText' )->textContent;

		preg_match( '/Ставка: (.+ коп\.)/', $textResult, $matchRate );
		preg_match( '/Льгота: (.*\%\))(.*)Сумма налога составит:/', $textResult, $matchBenefit );
		preg_match( '/Сумма налога составит: (.+ руб\.|коп\.)/', $textResult, $matchTaxCost );
		preg_match( '/Расчет произведен по формуле:(.*)/', $textResult, $matchFormula );

		$transport_data = new Transport_Tax_Data();

		$year     = $transport_data->getOneYear( [ 'ext_id' => $data['year'] ] );
		$category = $transport_data->getOneCategory( [ 'ext_id' => $data['category'] ] );

		$res = [
			'year'        => $year->value,
			'month'       => $data['month'],
			'category'    => $category->value,
			'power'       => $data['power'],
			'rate'        => trim( $matchRate[1] ),
			'benefit'     => trim( $matchBenefit[1] ),
			'sub_benefit' => trim( $matchBenefit[2] ),
			'cost'        => trim( $matchTaxCost[1] ),
			'formula'     => trim( $matchFormula[1] ),
			'info'        => trim( $textResult ),
		];

		wp_die( json_encode( $res ) );
	}

	/**
	 * @param     $request_url
	 * @param     $args
	 *
	 * @param int $index
	 *
	 * @return array|WP_Error
	 */
	private function repeatRequest( $request_url, $args, $index = 0 ) {
		$response = wp_remote_post( $request_url, $args );
		if ( ! is_array( $response ) && $index < 10 ) {
			sleep( 2 );
			$response = $this->repeatRequest( $request_url, $args, ++ $index );
		}

		return $response;
	}

}
