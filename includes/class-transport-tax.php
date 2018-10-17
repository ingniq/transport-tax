<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/ingniq/transport-tax
 * @since      1.0.0
 *
 * @package    Transport_Tax
 * @subpackage Transport_Tax/includes
 */

/**
 * The core plugin class.
 *
 * @since      1.0.0
 * @package    Transport_Tax
 * @subpackage Transport_Tax/includes
 * @author     Igor Filatov <ingniq8@gmail.com>
 */
class Transport_Tax {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Transport_Tax_Loader $loader Maintains and registers all hooks for the plugin.
	 */

	protected $loader;

	/**
	 * Description
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Transport_Tax_Data $data Description
	 */

	protected $data;
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;
	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'TRANSPORT_TAX_VERSION' ) ) {
			$this->version = TRANSPORT_TAX_VERSION;
		} else {
			$this->version = '2.0.0';
		}
		$this->plugin_name = 'transport-tax';
		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Transport_Tax_Loader. Orchestrates the hooks of the plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-transport-tax-loader.php';

		/**
		 * Description
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-transport-tax-data.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-transport-tax-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-transport-tax-public.php';

		$this->loader = new Transport_Tax_Loader();
		$this->data   = new Transport_Tax_Data();
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Transport_Tax_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_filter( 'mce_external_plugins', $plugin_admin, 'transport_tax_add_tinymce_script' );
		$this->loader->add_filter( 'mce_buttons', $plugin_admin, 'transport_tax_register_mce_button' );
		$this->loader->add_shortcode( 'trans_tax', $plugin_admin, 'transport_tax_shortcode' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Transport_Tax_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'transport_tax_ajax' );

		$this->loader->add_action( 'wp_ajax_get_zones', $plugin_public, 'get_zones' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_zones', $plugin_public, 'get_zones' );

		$this->loader->add_action( 'wp_ajax_get_years', $plugin_public, 'get_years' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_years', $plugin_public, 'get_years' );

		$this->loader->add_action( 'wp_ajax_get_categories', $plugin_public, 'get_categories' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_categories', $plugin_public, 'get_categories' );

		$this->loader->add_action( 'wp_ajax_get_brands', $plugin_public, 'get_brands' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_brands', $plugin_public, 'get_brands' );

		$this->loader->add_action( 'wp_ajax_get_models', $plugin_public, 'get_models' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_models', $plugin_public, 'get_models' );

		$this->loader->add_action( 'wp_ajax_get_benefits', $plugin_public, 'get_benefits' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_benefits', $plugin_public, 'get_benefits' );

		$this->loader->add_action( 'wp_ajax_calculate_tax', $plugin_public, 'calculate_tax' );
		$this->loader->add_action( 'wp_ajax_nopriv_calculate_tax', $plugin_public, 'calculate_tax' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Transport_Tax_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}