<?php
/**
 * Public display for the slider.
 *
 * @since      1.0.0
 */
?>

<div class="elyamani-slider" id="elyamani-slider-<?php echo esc_attr($slider['id']); ?>"
    data-slider-id="<?php echo esc_attr($slider['id']); ?>"
    data-settings='<?php echo json_encode($slider['settings']); ?>'>
    <?php foreach ($slide_data as $slide): ?>
        <div class="elyamani-slide">
            <?php
            // Get the content created by Elementor for this slide
            $slide_id = $slide['id']; // Assuming the slide has an ID
        
            // Retrieve the post object using the ID (adjust if needed)
            $post = get_post($slide_id);

            // Display the content created by Elementor (use Elementor’s built-in function)
            if ($post && 'slide' === $post->post_type) {
                // Retrieve Elementor content and display it
                echo apply_filters('the_content', $post->post_content); // This filters through the content to handle shortcodes and formatting
            }
            ?>
        </div>

    <?php endforeach; ?>
</div>
<script>
    jQuery(document).ready(function ($) {

        var sliderSettings = <?php echo wp_json_encode($slider['settings']); ?>;
        $('#elyamani-slider-<?php echo esc_attr($slider['id']); ?>').slick(sliderSettings);
    });
</script>