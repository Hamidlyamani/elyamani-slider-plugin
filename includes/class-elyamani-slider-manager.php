<?php
/**
 * Handles slider creation, management, and rendering.
 *
 * @since      1.0.0
 */
class Elyamani_Slider_Manager
{

    /**
     * Get all sliders from the database.
     *
     * @since    1.0.0
     * @return   array    Array of slider objects.
     */
    public static function get_sliders()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'elyamani_sliders';

        $sliders = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC", ARRAY_A);

        return $sliders;
    }

    /**
     * Get a specific slider by ID.
     *
     * @since    1.0.0
     * @param    int      $id    The slider ID.
     * @return   array    The slider data.
     */
    public static function get_slider($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'elyamani_sliders';

        $slider = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);

        if ($slider) {
            $slider['settings'] = json_decode($slider['settings'], true);
            $slider['slides'] = json_decode($slider['slides'], true);
        }

        return $slider;
    }

    /**
     * Create a new slider.
     *
     * @since    1.0.0
     * @param    string    $name       The slider name.
     * @param    array     $settings   The slider settings.
     * @param    array     $slides     The slider slides.
     * @return   int|bool  The new slider ID or false on failure.
     */
    public static function create_slider($name, $settings, $slides)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'elyamani_sliders';

        $result = $wpdb->insert(
            $table_name,
            array(
                'name' => sanitize_text_field($name),
                'settings' => json_encode($settings),
                'slides' => json_encode($slides),
            ),
            array('%s', '%s', '%s')
        );

        if ($result) {
            return $wpdb->insert_id;
        }

        return false;
    }

    /**
     * Update an existing slider.
     *
     * @since    1.0.0
     * @param    int       $id         The slider ID.
     * @param    string    $name       The slider name.
     * @param    array     $settings   The slider settings.
     * @param    array     $slides     The slider slides.
     * @return   bool      True on success, false on failure.
     */
    public static function update_slider($id, $name, $settings, $slides)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'elyamani_sliders';

        $result = $wpdb->update(
            $table_name,
            array(
                'name' => sanitize_text_field($name),
                'settings' => json_encode($settings),
                'slides' => json_encode($slides),
            ),
            array('id' => $id),
            array('%s', '%s', '%s'),
            array('%d')
        );

        return $result !== false;
    }

    /**
     * Delete a slider.
     *
     * @since    1.0.0
     * @param    int       $id    The slider ID.
     * @return   bool      True on success, false on failure.
     */
    public static function delete_slider($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'elyamani_sliders';

        $result = $wpdb->delete(
            $table_name,
            array('id' => $id),
            array('%d')
        );

        return $result !== false;
    }

    /**
     * Get slides for a slider.
     *
     * @since    1.0.0
     * @param    array     $slide_ids    Array of slide IDs.
     * @return   array     Array of slide data.
     */
    public static function get_slides($slide_ids)
    {
        $slides = array();

        foreach ($slide_ids as $slide_id) {
            $slide = get_post($slide_id);

            if ($slide && $slide->post_type === 'elyamani_slide') {
                $slide_data = array(
                    'id' => $slide->ID,
                    'title' => $slide->post_title,
                    'content' => $slide->post_content,
                    'image' => get_the_post_thumbnail_url($slide->ID, 'full'),
                    'link' => get_post_meta($slide->ID, '_elyamani_slide_link', true),
                    'button_text' => get_post_meta($slide->ID, '_elyamani_slide_button_text', true),
                    'caption' => get_post_meta($slide->ID, '_elyamani_slide_caption', true),
                );

                $slides[] = $slide_data;
            }
        }

        return $slides;
    }

    /**
     * Render a slider.
     *
     * @since    1.0.0
     * @param    int       $id    The slider ID.
     * @return   string    The slider HTML.
     */
    public static function render_slider($id)
    {
        $slider = self::get_slider($id);

        if (!$slider) {
            return '';
        }

        // Get the actual slide data
        $slide_data = array();
        if (!empty($slider['slides'])) {
            $slide_data = self::get_slides($slider['slides']);
        }

        // Start output buffering
        ob_start();

        // Include the template
        include ELYAMANI_SLIDER_PATH . 'public/partials/elyamani-slider-public-display.php';

        // Return the buffered content
        return ob_get_clean();
    }
}