<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wwdh.de
 * @since      1.0.0
 *
 * @package    Hupa_Teams
 * @subpackage Hupa_Teams/includes
 */


use Hupa\TeamMembers\Hupa_Teams_Rest_Endpoint;
use Hupa\TeamMembers\Register_Teams_Gutenberg_Patterns;
use Hupa\TeamMembers\Register_Teams_Gutenberg_Tools;
use Hupa\TeamMembers\Render_Callback_Templates;
use Hupa\TeamMembers\Team_Members_Block_Callback;
use Hupa\TeamMembers\Wp_Teams_Helper;

use HupaTeams\License\Register_Api_WP_Remote;
use HupaTeams\License\Register_Product_License;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

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
 * @package    Hupa_Teams
 * @subpackage Hupa_Teams/includes
 * @author     Jens Wiecker <email@jenswiecker.de>
 */
class Hupa_Teams {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Hupa_Teams_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected Hupa_Teams_Loader $loader;

    /**
     * TWIG autoload for PHP-Template-Engine
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Environment $twig TWIG autoload for PHP-Template-Engine
     */
    protected Environment $twig;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected string $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected string $version;

    /**
     * The current database version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $db_version    The current database version of the plugin.
     */
    protected string $db_version;

    /**
     * Store plugin main class to allow public access.
     *
     * @since    1.0.0
     * @var object The main class.
     */
    public object $main;

    /**
     * The plugin Slug Path.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_slug plugin Slug Path.
     */
    private string $plugin_slug;

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
		if ( defined( 'HUPA_TEAMS_VERSION' ) ) {
			$this->version = HUPA_TEAMS_VERSION;
		} else {
			$this->version = '1.0.0';
		}


        if ( defined( 'HUPA_TEAMS_DB_VERSION' ) ) {
            $this->db_version = HUPA_TEAMS_DB_VERSION;
        } else {
            $this->db_version = '1.0.0';
        }

        $this->plugin_name = HUPA_TEAMS_BASENAME;
        $this->plugin_slug = HUPA_TEAMS_SLUG_PATH;
        $this->main = $this;

        //Check PHP AND WordPress Version
        $this->check_dependencies();
		$this->load_dependencies();
		$this->set_locale();
        $this->define_product_license_class();
        $tempDir = plugin_dir_path(dirname(__FILE__)) . 'admin' . DIRECTORY_SEPARATOR . 'class-gutenberg' . DIRECTORY_SEPARATOR . 'callback-templates';
        $twig_loader = new FilesystemLoader($tempDir);
        $this->twig = new Environment($twig_loader);
        $this->register_team_members_render_callback();
        $this->register_team_members_callback();
        $this->register_gutenberg_patterns();
		$this->register_gutenberg_sidebar();
        $this->define_admin_hooks();
		$this->define_public_hooks();
        $this->register_hupa_team_members_endpoint();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Hupa_Teams_Loader. Orchestrates the hooks of the plugin.
	 * - Hupa_Teams_i18n. Defines internationalization functionality.
	 * - Hupa_Teams_Admin. Defines all hooks for the admin area.
	 * - Hupa_Teams_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

        /**
         * The trait for the default settings of the Hupa-Team-Members
         * of the plugin.
         */
        require_once plugin_dir_path( dirname(__FILE__) ) . 'includes/hupa_teams_members_defaults_trait.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hupa-teams-i18n.php';

        /**
         * The code that runs during plugin activation.
         * This action is documented in includes/class-hupa-teams-activator.php
         */
        require_once plugin_dir_path(dirname(__FILE__ ) ) . 'includes/class-hupa-teams-activator.php';

        /**
         * TWIG autoload for PHP-Template-Engine
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/Twig/autoload.php';

        /**
         * The class responsible for defining Callback Templates
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-gutenberg/class_render_callback_templates.php';

        /**
         * The class responsible for defining WP REST API Routes
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-gutenberg/class_hupa_teams_rest_endpoint.php';
        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-gutenberg/class_team_members_block_callback.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-gutenberg/class_register_teams_gutenberg_tools.php';

        /**
         * The class Helper
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class_wp_teams_helper.php';

        /**
         * The class responsible for defining all Gutenberg Patterns.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-gutenberg/class_register_teams_gutenberg_patterns.php';

        /**
         * // JOB The class responsible for defining all actions that occur in the license area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/license/class_register_product_license.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
        if ( is_file( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-hupa-teams-admin.php' ) ) {
            require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-hupa-teams-admin.php';
        }
        /**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-hupa-teams-public.php';

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hupa-teams-loader.php';

		$this->loader = new Hupa_Teams_Loader();

	}

    /**
     * Check PHP and WordPress Version
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function check_dependencies(): void
    {
        global $wp_version;
        if (version_compare(PHP_VERSION, HUPA_TEAMS_PHP_VERSION, '<') || $wp_version < HUPA_TEAMS_WP_VERSION) {
            $this->maybe_self_deactivate();
        }
    }

    /**
     * Self-Deactivate
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function maybe_self_deactivate(): void
    {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        deactivate_plugins($this->plugin_slug);
        add_action('admin_notices', array($this, 'self_deactivate_notice'));
    }

    /**
     * Self-Deactivate Admin Notiz
     * of the plugin.
     *
     * @since    1.0.0
     * @access   public
     */
    public function self_deactivate_notice(): void
    {
        echo sprintf('<div class="error" style="margin-top:5rem"><p>' . __('This plugin has been disabled because it requires a PHP version greater than %s and a WordPress version greater than %s. Your PHP version can be updated by your hosting provider.', 'hupa-teams') . '</p></div>', HUPA_TEAMS_PHP_VERSION, HUPA_TEAMS_WP_VERSION);
        exit();
    }

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Hupa_Teams_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Hupa_Teams_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

    /**
     * Register all the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_product_license_class() {

        if(!get_option('hupa_server_url')){
            update_option('hupa_server_url', $this->get_license_config()->api_server_url);
        }

        global $wpRemoteLicense;
        $wpRemoteLicense = new Register_Api_WP_Remote($this->get_plugin_name(), $this->get_version(), $this->get_license_config(), $this->main);
        global $product_license;
        $product_license = new Register_Product_License( $this->get_plugin_name(), $this->get_version(), $this->get_license_config(), $this->main );
        $this->loader->add_action( 'init', $product_license, 'license_site_trigger_check' );
        $this->loader->add_action( 'template_redirect', $product_license, 'license_callback_site_trigger_check' );
    }

    /**
     * Register all the hooks related to the Gutenberg Sidebar functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function register_gutenberg_sidebar() {
        $registerGBTools = new Register_Teams_Gutenberg_Tools($this->get_plugin_name(), $this->get_version(), $this->main);

        $this->loader->add_action( 'init', $registerGBTools, 'team_member_posts_sidebar_meta_fields' );
        $this->loader->add_action( 'init', $registerGBTools, 'wp_team_members_register_sidebar' );
        $this->loader->add_action( 'enqueue_block_editor_assets', $registerGBTools, 'team_members_sidebar_script_enqueue' );
        $this->loader->add_action( 'init', $registerGBTools, 'register_team_members_block_type' );
        $this->loader->add_action( 'enqueue_block_editor_assets', $registerGBTools, 'team_members_block_type_scripts' );
    }

    /**
     * Register all the hooks related to the Gutenberg Sidebar functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function register_gutenberg_patterns() {
        $registerPatterns = new Register_Teams_Gutenberg_Patterns($this->get_plugin_name(), $this->get_version(), $this->main);

        $this->loader->add_action( 'init', $registerPatterns, 'register_block_pattern_category' );
        $this->loader->add_action( 'init', $registerPatterns, 'register_gutenberg_patterns' );
        $this->loader->add_filter( $this->plugin_name . '/get_template_select', $registerPatterns, 'get_template_gutenberg_select' );
    }

    /**
     * Register all the hooks related to the Gutenberg Sidebar functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function register_team_members_callback() {
        global $registerTeamsCallback;
        $registerTeamsCallback = new Team_Members_Block_Callback();
    }

    /**
     * Register all the hooks related to the Gutenberg Sidebar functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function register_team_members_render_callback() {
        global $registerTeamsRenderCallback;
        $registerTeamsRenderCallback = new Render_Callback_Templates($this->get_plugin_name(), $this->get_version(), $this->main, $this->twig);
        $this->loader->add_filter($this->plugin_name.'/render_callback_template', $registerTeamsRenderCallback, 'render_callback_template');
    }

    /**
     * Register all the hooks related to the Plugin Endpoints functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function register_hupa_team_members_endpoint() {
        $registerEndpoint = new Hupa_Teams_Rest_Endpoint($this->get_plugin_name(), $this->get_version(), $this->main);
        $this->loader->add_action('rest_api_init', $registerEndpoint, 'register_routes');
        $this->loader->add_filter($this->plugin_name.'/get_custom_terms', $registerEndpoint, 'team_members_get_custom_terms');
    }


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

        if(!get_option('hupa_teams_user_role')){
            update_option('hupa_teams_user_role', 'manage_options');
        }

        if ( is_file( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-hupa-teams-admin.php' ) && get_option( "{$this->plugin_name}_product_install_authorize" ) ) {
            $postTypes = new Hupa_Teams_Activator();
            $this->loader->add_action('init', $postTypes, 'hupa_register_team_members');
            $this->loader->add_action('init', $postTypes, 'hupa_register_team_members_taxonomies');

            $plugin_admin = new Hupa_Teams_Admin($this->get_plugin_name(), $this->get_version(), $this->main);

            //JOB WARNING ADD Plugin Settings Link
           // $this->loader->add_filter('plugin_action_links_' . $this->plugin_name . '/' . $this->plugin_name . '.php', $plugin_admin, 'experience_reports_plugin_add_action_link');


            $this->loader->add_action('init', $plugin_admin, 'hupa_teams_update_checker');
            $this->loader->add_action('in_plugin_update_message-' . $this->plugin_name . '/' . $this->plugin_name . '.php', $plugin_admin, 'hupa_teams_show_upgrade_notification', 10, 2);

        }
	}

    /**
     * Register all the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function register_helper_class() {
        global $plugin_helper;
        $plugin_helper = new Wp_Teams_Helper( $this->get_plugin_name(), $this->get_version(), $this->main );
        $this->loader->add_action( $this->plugin_name.'/get_random_string', $plugin_helper, 'getRandomString' );
        $this->loader->add_action( $this->plugin_name.'/generate_random_id', $plugin_helper, 'getGenerateRandomId' );
        $this->loader->add_action( $this->plugin_name.'/array_to_object', $plugin_helper, 'ArrayToObject' );
        $this->loader->add_action( $this->plugin_name.'/svg_icons', $plugin_helper, 'svg_icons',10,3 );
    }

	/**
	 * Register all the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Hupa_Teams_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all the hooks with WordPress.
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
	public function get_plugin_name(): string
    {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Hupa_Teams_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader(): Hupa_Teams_Loader
    {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version(): string
    {
		return $this->version;
	}

    /**
     * Retrieve the database version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The database version number of the plugin.
     */
    public function get_db_version(): string {
        return $this->db_version;
    }

    /**
     * License Config for the plugin.
     *
     * @return    object License Config.
     * @since     1.0.0
     */
    public function get_license_config():object {
        $config_file = plugin_dir_path( dirname( __FILE__ ) ) . 'includes/license/config.json';

        return json_decode(file_get_contents($config_file));
    }

}
