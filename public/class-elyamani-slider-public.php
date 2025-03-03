<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 */
class Elyamani_Slider_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    string    $plugin_name       The name of the plugin.
     * @param    string    $version           The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_register_style('slick', ELYAMANI_SLIDER_URL . 'public/css/slick.css', array(), $this->version);
        wp_register_style('slick-theme', ELYAMANI_SLIDER_URL . 'public/css/slick-theme.css', array('slick'), $this->version);
        wp_register_style($this->plugin_name, ELYAMANI_SLIDER_URL . 'public/css/elyamani-slider-public.css', array('slick', 'slick-theme'), $this->version);
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_register_script('slick', ELYAMANI_SLIDER_URL . 'public/js/slick.min.js', array('jquery'), $this->version, true);
        wp_register_script($this->plugin_name, ELYAMANI_SLIDER_URL . 'public/js/elyamani-slider-public.js', array('jquery', 'slick'), $this->version, true);
    }

    /**
     * Register shortcodes.
     *
     * @since    1.0.0
     */
    public function register_shortcodes()
    {
        add_shortcode('elyamani_slider', array($this, 'slider_shortcode'));
    }

    /**
     * Shortcode callback for [elyamani_slider].
     *
     * @since    1.0.0
     * @param    array     $atts    Shortcode attributes.
     * @return   string    Slider HTML.
     */
    public function slider_shortcode($atts)
    {
        $atts = shortcode_atts(
            array(
                'id' => 0,
            ),
            $atts,
            'elyamani_slider'
        );

        $slider_id = intval($atts['id']);

        if (!$slider_id) {
            return '<p class="elyamani-slider-error">' . __('Error: Slider ID is required.', 'elyamani-slider') . '</p>';
        }

        // Enqueue required styles and scripts
        wp_enqueue_style('slick');
        wp_enqueue_style('slick-theme');
        wp_enqueue_style($this->plugin_name);
        wp_enqueue_script('slick');
        wp_enqueue_script($this->plugin_name);

        // Render the slider
        return Elyamani_Slider_Manager::render_slider($slider_id);
    }
}

/**
 * Helper function to display a slider in theme files.
 *
 * @since    1.0.0
 * @param    int       $id    The slider ID.
 * @return   string    Slider HTML.
 */
function elyamani_display_slider($id)
{
    $shortcode = sprintf('[elyamani_slider id="%d"]', intval($id));
    return do_shortcode($shortcode);
}