<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.facebook.com/anuragsingh.me
 * @since      1.0.0
 *
 * @package    As_Performance_Reports
 * @subpackage As_Performance_Reports/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    As_Performance_Reports
 * @subpackage As_Performance_Reports/includes
 * @author     Anurag Singh <anuragsinghce@outlook.com>
 */
class As_Performance_Reports {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      As_Performance_Reports_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;


    /**
     * The Custom Post Type Name.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $as_cpt;

    /**
     * The Custom Taxonomy Name.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $as_taxo;

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

        $this->plugin_name = 'as-performance-reports';
        $this->version = '1.0.0';

        $this->as_cpt = 'Performance Excel';
        $this->as_taxo = array('report category');

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - As_Performance_Reports_Loader. Orchestrates the hooks of the plugin.
     * - As_Performance_Reports_i18n. Defines internationalization functionality.
     * - As_Performance_Reports_Admin. Defines all hooks for the admin area.
     * - As_Performance_Reports_Public. Defines all hooks for the public side of the site.
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
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-as-performance-reports-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-as-performance-reports-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-as-performance-reports-admin.php';

        /**
         * The class responsible for defining all formulas which are used to manupulate data
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-excel-formulas.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-as-performance-reports-public.php';


        /**
         * The class responsible for handling all functionality related to excel sheet
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/PHPExcel.php';


        /**
         * The class responsible for defining range of excel sheet data to be process
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class_custom_filter_for_excel.php';


        /**
         * The class responsible for defining all actions that are used to import excel data into mysql
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class_excel_to_mysql.php';

        /**
         * The class responsible for defining all actions that are used to import excel data into mysql
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class_admin_report_table.php';

        
		
		/**
		 * The class responsible for defining all actions that are used to display last month report
		 */
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-last-month-report.php';

		/**
         * The class responsible for defining all actions that are used to display all records of perticualr call type
         */
        //require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-single-call-type.php';


        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-create-new-page.php';

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-create-new-menu.php';




        $this->loader = new As_Performance_Reports_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the As_Performance_Reports_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new As_Performance_Reports_i18n();

        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new As_Performance_Reports_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_as_cpt(), $this->get_as_taxo());

        //$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        //$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'init', $plugin_admin, 'create_new_cpt');
        //$this->loader->add_action ( 'init', $plugin_admin, 'create_second_cpt');
        //$this->loader->add_action ( 'init', $plugin_admin, 'create_new_taxonomy');

        $this->loader->add_action ('admin_menu', $plugin_admin, 'add_menu_page_for_all_call_tabuler_view');

        //$this->loader->add_action( 'admin_menu', $plugin_admin, 'my_admin_menu' );

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new As_Performance_Reports_Public( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
       	$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

       	$this->loader->add_action( 'wp_ajax_my_action', $plugin_public,  'my_action_callback' );
       	$this->loader->add_action( 'wp_ajax_nopriv_my_action', $plugin_public, 'my_action_callback' );

        $this->loader->add_filter('template_include', $plugin_public, 'override_templates_for_cpt_gallery');

        $this->loader->add_shortcode( 'display_performance_report', $plugin_public, 'display_table_data');
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
     * @return    As_Performance_Reports_Loader    Orchestrates the hooks of the plugin.
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

    public function get_as_cpt() {
        return $this->as_cpt;
    }

    public function get_as_taxo() {
        return $this->as_taxo;
    }

}
