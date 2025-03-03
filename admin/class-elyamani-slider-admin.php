<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 */
class Elyamani_Slider_Admin {

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
     * @param    string    $plugin_name       The name of this plugin.
     * @param    string    $version           The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        // Only load on our plugin pages
        if (!$this->is_plugin_page()) {
            return;
        }

        wp_enqueue_style('jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
        wp_enqueue_style('slick', ELYAMANI_SLIDER_URL . 'admin/css/slick.css', array(), $this->version);
        wp_enqueue_style('slick-theme', ELYAMANI_SLIDER_URL . 'admin/css/slick-theme.css', array('slick'), $this->version);
        wp_enqueue_style($this->plugin_name, ELYAMANI_SLIDER_URL . 'admin/css/elyamani-slider-admin.css', array(), $this->version);
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        // Only load on our plugin pages
        if (!$this->is_plugin_page()) {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('slick', ELYAMANI_SLIDER_URL . 'admin/js/slick.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->plugin_name, ELYAMANI_SLIDER_URL . 'admin/js/elyamani-slider-admin.js', array('jquery', 'slick', 'jquery-ui-sortable', 'jquery-ui-tabs'), $this->version, true);
        
        wp_localize_script($this->plugin_name, 'elyamani_slider', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('elyamani_slider_nonce'),
            'strings' => array(
                'confirm_delete' => __('Are you sure you want to delete this slider? This action cannot be undone.', 'elyamani-slider'),
                'slider_created' => __('Slider created successfully!', 'elyamani-slider'),
                'slider_updated' => __('Slider updated successfully!', 'elyamani-slider'),
                'slider_deleted' => __('Slider deleted successfully!', 'elyamani-slider'),
                'error' => __('An error occurred. Please try again.', 'elyamani-slider'),
            ),
        ));
    }

    /**
     * Check if current page is a plugin page.
     *
     * @since    1.0.0
     * @return   boolean    True if current page is a plugin page.
     */
    private function is_plugin_page() {
        $screen = get_current_screen();
        
        if (!$screen) {
            return false;
        }
        
        $plugin_pages = array(
            'toplevel_page_elyamani-slider',
            'elyamani-slider_page_elyamani-slider-create',
            'elyamani-slider_page_elyamani-slider-edit',
            'elyamani_slide',
        );
        
        return in_array($screen->id, $plugin_pages) || strpos($screen->id, 'elyamani-slider') !== false;
    }

    /**
     * Add admin menu items.
     *
     * @since    1.0.0
     */
    public function add_admin_menu() {
        // Main menu
        add_menu_page(
            __('Elyamani Slider', 'elyamani-slider'),
            __('Elyamani Slider', 'elyamani-slider'),
            'manage_options',
            'elyamani-slider',
            array($this, 'display_sliders_page'),
            'dashicons-images-alt2',
            30
        );
        
        // Sliders submenu
        add_submenu_page(
            'elyamani-slider',
            __('All Sliders', 'elyamani-slider'),
            __('All Sliders', 'elyamani-slider'),
            'manage_options',
            'elyamani-slider',
            array($this, 'display_sliders_page')
        );
        
        // Create slider submenu
        add_submenu_page(
            'elyamani-slider',
            __('Create Slider', 'elyamani-slider'),
            __('Create Slider', 'elyamani-slider'),
            'manage_options',
            'elyamani-slider-create',
            array($this, 'display_create_slider_page')
        );
        
        // Settings submenu
        add_submenu_page(
            'elyamani-slider',
            __('Settings', 'elyamani-slider'),
            __('Settings', 'elyamani-slider'),
            'manage_options',
            'elyamani-slider-settings',
            array($this, 'display_settings_page')
        );
    }

    /**
     * Register plugin settings.
     *
     * @since    1.0.0
     */
    public function register_settings() {
        register_setting('elyamani_slider_settings', 'elyamani_slider_settings');
        
        add_settings_section(
            'elyamani_slider_general_section',
            __('General Settings', 'elyamani-slider'),
            array($this, 'general_settings_section_callback'),
            'elyamani-slider-settings'
        );
        
        add_settings_field(
            'elyamani_slider_default_settings',
            __('Default Slider Settings', 'elyamani-slider'),
            array($this, 'default_settings_field_callback'),
            'elyamani-slider-settings',
            'elyamani_slider_general_section'
        );
    }

    /**
     * General settings section callback.
     *
     * @since    1.0.0
     */
    public function general_settings_section_callback() {
        echo '<p>' . __('Configure the default settings for your sliders.', 'elyamani-slider') . '</p>';
    }

    /**
     * Default settings field callback.
     *
     * @since    1.0.0
     */
    public function default_settings_field_callback() {
        $options = get_option('elyamani_slider_settings', array());
        $defaults = array(
            'autoplay' => true,
            'autoplay_speed' => 3000,
            'speed' => 500,
            'arrows' => true,
            'dots' => true,
            'infinite' => true,
            'fade' => false,
            'adaptiveHeight' => false,
        );
        
        $settings = wp_parse_args($options, $defaults);
        
        ?>
<table class="form-table">
    <tr>
        <th scope="row"><?php _e('Autoplay', 'elyamani-slider'); ?></th>
        <td>
            <input type="checkbox" name="elyamani_slider_settings[autoplay]" value="1"
                <?php checked($settings['autoplay'], true); ?>>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e('Autoplay Speed (ms)', 'elyamani-slider'); ?></th>
        <td>
            <input type="number" name="elyamani_slider_settings[autoplay_speed]"
                value="<?php echo esc_attr($settings['autoplay_speed']); ?>" min="0" step="100">
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e('Animation Speed (ms)', 'elyamani-slider'); ?></th>
        <td>
            <input type="number" name="elyamani_slider_settings[speed]"
                value="<?php echo esc_attr($settings['speed']); ?>" min="0" step="100">
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e('Show Arrows', 'elyamani-slider'); ?></th>
        <td>
            <input type="checkbox" name="elyamani_slider_settings[arrows]" value="1"
                <?php checked($settings['arrows'], true); ?>>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e('Show Dots', 'elyamani-slider'); ?></th>
        <td>
            <input type="checkbox" name="elyamani_slider_settings[dots]" value="1"
                <?php checked($settings['dots'], true); ?>>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e('Infinite Loop', 'elyamani-slider'); ?></th>
        <td>
            <input type="checkbox" name="elyamani_slider_settings[infinite]" value="1"
                <?php checked($settings['infinite'], true); ?>>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e('Fade Effect', 'elyamani-slider'); ?></th>
        <td>
            <input type="checkbox" name="elyamani_slider_settings[fade]" value="1"
                <?php checked($settings['fade'], true); ?>>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e('Adaptive Height', 'elyamani-slider'); ?></th>
        <td>
            <input type="checkbox" name="elyamani_slider_settings[adaptiveHeight]" value="1"
                <?php checked($settings['adaptiveHeight'], true); ?>>
        </td>
    </tr>
</table>
<?php
    }

    /**
     * Display the sliders page.
     *
     * @since    1.0.0
     */
    public function display_sliders_page() {
        $sliders = Elyamani_Slider_Manager::get_sliders();
        
        include ELYAMANI_SLIDER_PATH . 'admin/partials/elyamani-slider-admin-display.php';
    }

    /**
     * Display the create slider page.
     *
     * @since    1.0.0
     */
    public function display_create_slider_page() {
        $slider_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $slider = $slider_id ? Elyamani_Slider_Manager::get_slider($slider_id) : null;
        
        // Get all slides
        $slides_args = array(
            'post_type' => 'elyamani_slide',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        );
        
        $slides = get_posts($slides_args);
        
        // Get default settings
        $options = get_option('elyamani_slider_settings', array());
        $defaults = array(
            'autoplay' => true,
            'autoplay_speed' => 3000,
            'speed' => 500,
            'arrows' => true,
            'dots' => true,
            'infinite' => true,
            'fade' => false,
            'adaptiveHeight' => false,
        );
        
        $default_settings = wp_parse_args($options, $defaults);
        
        include ELYAMANI_SLIDER_PATH . 'admin/partials/elyamani-slider-admin-create.php';
    }

    /**
     * Display the settings page.
     *
     * @since    1.0.0
     */
    public function display_settings_page() {
        include ELYAMANI_SLIDER_PATH . 'admin/partials/elyamani-slider-admin-settings.php';
    }

    /**
     * Add meta boxes for the slide post type.
     *
     * @since    1.0.0
     */
    public function add_meta_boxes() {
        add_meta_box(
            'elyamani_slide_options',
            __('Slide Options', 'elyamani-slider'),
            array($this, 'render_slide_options_meta_box'),
            'elyamani_slide',
            'normal',
            'high'
        );
    }

    /**
     * Render the slide options meta box.
     *
     * @since    1.0.0
     * @param    WP_Post    $post    The post object.
     */
    public function render_slide_options_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('elyamani_slide_options', 'elyamani_slide_options_nonce');
        
        // Get saved values
        $link = get_post_meta($post->ID, '_elyamani_slide_link', true);
        $button_text = get_post_meta($post->ID, '_elyamani_slide_button_text', true);
        $caption = get_post_meta($post->ID, '_elyamani_slide_caption', true);
        
        ?>
<table class="form-table">
    <tr>
        <th scope="row">
            <label for="elyamani_slide_link"><?php _e('Slide Link', 'elyamani-slider'); ?></label>
        </th>
        <td>
            <input type="url" id="elyamani_slide_link" name="elyamani_slide_link" value="<?php echo esc_url($link); ?>"
                class="regular-text">
            <p class="description"><?php _e('Enter a URL to link this slide to.', 'elyamani-slider'); ?></p>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="elyamani_slide_button_text"><?php _e('Button Text', 'elyamani-slider'); ?></label>
        </th>
        <td>
            <input type="text" id="elyamani_slide_button_text" name="elyamani_slide_button_text"
                value="<?php echo esc_attr($button_text); ?>" class="regular-text">
            <p class="description">
                <?php _e('Enter text for the button. Leave empty for no button.', 'elyamani-slider'); ?></p>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="elyamani_slide_caption"><?php _e('Slide Caption', 'elyamani-slider'); ?></label>
        </th>
        <td>
            <textarea id="elyamani_slide_caption" name="elyamani_slide_caption" rows="3"
                class="large-text"><?php echo esc_textarea($caption); ?></textarea>
            <p class="description"><?php _e('Enter a caption for this slide.', 'elyamani-slider'); ?></p>
        </td>
    </tr>
</table>
<?php
    }

    /**
     * Save the slide meta box data.
     *
     * @since    1.0.0
     * @param    int       $post_id    The post ID.
     * @param    WP_Post   $post       The post object.
     */
    public function save_meta_boxes($post_id, $post) {
        // Check if our nonce is set
        if (!isset($_POST['elyamani_slide_options_nonce'])) {
            return;
        }
        
        // Verify that the nonce is valid
        if (!wp_verify_nonce($_POST['elyamani_slide_options_nonce'], 'elyamani_slide_options')) {
            return;
        }
        
        // If this is an autosave, our form has not been submitted, so we don't want to do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check the user's permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save the slide link
        if (isset($_POST['elyamani_slide_link'])) {
            update_post_meta($post_id, '_elyamani_slide_link', esc_url_raw($_POST['elyamani_slide_link']));
        }
        
        // Save the button text
        if (isset($_POST['elyamani_slide_button_text'])) {
            update_post_meta($post_id, '_elyamani_slide_button_text', sanitize_text_field($_POST['elyamani_slide_button_text']));
        }
        
        // Save the caption
        if (isset($_POST['elyamani_slide_caption'])) {
            update_post_meta($post_id, '_elyamani_slide_caption', wp_kses_post($_POST['elyamani_slide_caption']));
        }
    }

    /**
     * AJAX handler for creating a slider.
     *
     * @since    1.0.0
     */
    public function create_slider() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'elyamani_slider_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'elyamani-slider')));
        }
        
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to do this.', 'elyamani-slider')));
        }
        
        // Validate and sanitize input
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $settings = isset($_POST['settings']) ? $this->sanitize_slider_settings($_POST['settings']) : array();
        $slides = isset($_POST['slides']) ? array_map('intval', $_POST['slides']) : array();
        
        if (empty($name)) {
            wp_send_json_error(array('message' => __('Slider name is required.', 'elyamani-slider')));
        }
        
        // Create the slider
        $slider_id = Elyamani_Slider_Manager::create_slider($name, $settings, $slides);
        
        if ($slider_id) {
            wp_send_json_success(array(
                'message' => __('Slider created successfully!', 'elyamani-slider'),
                'slider_id' => $slider_id,
                'redirect' => admin_url('admin.php?page=elyamani-slider'),
            ));
        } else {
            wp_send_json_error(array('message' => __('Failed to create slider.', 'elyamani-slider')));
        }
        
        wp_die();
    }

    /**
     * AJAX handler for updating a slider.
     *
     * @since    1.0.0
     */
    public function update_slider() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'elyamani_slider_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'elyamani-slider')));
        }
        
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to do this.', 'elyamani-slider')));
        }
        
        // Validate and sanitize input
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $settings = isset($_POST['settings']) ? $this->sanitize_slider_settings($_POST['settings']) : array();
        $slides = isset($_POST['slides']) ? array_map('intval', $_POST['slides']) : array();
        
        if (empty($id)) {
            wp_send_json_error(array('message' => __('Slider ID is required.', 'elyamani-slider')));
        }
        
        if (empty($name)) {
            wp_send_json_error(array('message' => __('Slider name is required.', 'elyamani-slider')));
        }
        
        // Update the slider
        $result = Elyamani_Slider_Manager::update_slider($id, $name, $settings, $slides);
        
        if ($result) {
            wp_send_json_success(array(
                'message' => __('Slider updated successfully!', 'elyamani-slider'),
                'slider_id' => $id,
                'redirect' => admin_url('admin.php?page=elyamani-slider'),
            ));
        } else {
            wp_send_json_error(array('message' => __('Failed to update slider.', 'elyamani-slider')));
        }
        
        wp_die();
    }

    /**
     * AJAX handler for deleting a slider.
     *
     * @since    1.0.0
     */
    public function delete_slider() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'elyamani_slider_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'elyamani-slider')));
        }
        
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to do this.', 'elyamani-slider')));
        }
        
        // Validate input
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        
        if (empty($id)) {
            wp_send_json_error(array('message' => __('Slider ID is required.', 'elyamani-slider')));
        }
        
        // Delete the slider
        $result = Elyamani_Slider_Manager::delete_slider($id);
        
        if ($result) {
            wp_send_json_success(array(
                'message' => __('Slider deleted successfully!', 'elyamani-slider'),
            ));
        } else {
            wp_send_json_error(array('message' => __('Failed to delete slider.', 'elyamani-slider')));
        }
        
        wp_die();
    }

    /**
     * Sanitize slider settings.
     *
     * @since    1.0.0
     * @param    array     $settings    The slider settings.
     * @return   array     Sanitized settings.
     */
    private function sanitize_slider_settings($settings) {
        $sanitized = array();
        
        // Boolean settings
        $boolean_settings = array('autoplay', 'arrows', 'dots', 'infinite', 'fade', 'adaptiveHeight');
        foreach ($boolean_settings as $setting) {
            $sanitized[$setting] = isset($settings[$setting]) ? (bool) $settings[$setting] : false;
        }
        
        // Integer settings
        $integer_settings = array('autoplay_speed', 'speed', 'slidesToShow', 'slidesToScroll');
        foreach ($integer_settings as $setting) {
            $sanitized[$setting] = isset($settings[$setting]) ? intval($settings[$setting]) : 0;
        }
        
        return $sanitized;
    }
}