<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @since      1.0.0
 */
class Elyamani_Slider
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Elyamani_Slider_Loader    $loader    Maintains and registers all hooks for the plugin.
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
     * Define the core functionality of the plugin.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->plugin_name = 'elyamani-slider';
        $this->version = ELYAMANI_SLIDER_VERSION;

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once ELYAMANI_SLIDER_PATH . 'includes/class-elyamani-slider-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once ELYAMANI_SLIDER_PATH . 'includes/class-elyamani-slider-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once ELYAMANI_SLIDER_PATH . 'admin/class-elyamani-slider-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once ELYAMANI_SLIDER_PATH . 'public/class-elyamani-slider-public.php';

        /**
         * The class responsible for defining all custom post types.
         */
        require_once ELYAMANI_SLIDER_PATH . 'includes/class-elyamani-slider-post-types.php';

        /**
         * The class responsible for defining all slider functionality.
         */
        require_once ELYAMANI_SLIDER_PATH . 'includes/class-elyamani-slider-manager.php';

        $this->loader = new Elyamani_Slider_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new Elyamani_Slider_i18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Elyamani_Slider_Admin($this->get_plugin_name(), $this->get_version());

        // Enqueue admin styles and scripts
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        // Add admin menu
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_admin_menu');

        // Register settings
        $this->loader->add_action('admin_init', $plugin_admin, 'register_settings');

        // Add AJAX handlers
        $this->loader->add_action('wp_ajax_elyamani_create_slider', $plugin_admin, 'create_slider');
        $this->loader->add_action('wp_ajax_elyamani_update_slider', $plugin_admin, 'update_slider');
        $this->loader->add_action('wp_ajax_elyamani_delete_slider', $plugin_admin, 'delete_slider');

        // Register post types
        $post_types = new Elyamani_Slider_Post_Types();
        $this->loader->add_action('init', $post_types, 'register_post_types');
        $this->loader->add_action('init', $post_types, 'register_taxonomies');

        // Add meta boxes
        $this->loader->add_action('add_meta_boxes', $plugin_admin, 'add_meta_boxes');
        $this->loader->add_action('save_post', $plugin_admin, 'save_meta_boxes', 10, 2);
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new Elyamani_Slider_Public($this->get_plugin_name(), $this->get_version());

        // Enqueue public styles and scripts
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        // Register shortcodes
        $this->loader->add_action('init', $plugin_public, 'register_shortcodes');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Elyamani_Slider_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
}