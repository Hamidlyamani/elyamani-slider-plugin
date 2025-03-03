<?php
/**
 * Admin display for the settings page.
 *
 * @since      1.0.0
 */
?>

<div class="wrap elyamani-slider-wrap">
    <h1><?php _e('Elyamani Slider Settings', 'elyamani-slider'); ?></h1>

    <form method="post" action="options.php" class="elyamani-slider-settings">
        <?php
        settings_fields('elyamani_slider_settings');
        do_settings_sections('elyamani-slider-settings');
        submit_button();
        ?>
    </form>
</div>