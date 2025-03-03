<?php
/**
 * Admin display for the sliders list page.
 *
 * @since      1.0.0
 */
?>

<div class="wrap elyamani-slider-wrap">
    <h1 class="wp-heading-inline"><?php _e('Elyamani Slider', 'elyamani-slider'); ?></h1>
    <a href="<?php echo admin_url('admin.php?page=elyamani-slider-create'); ?>"
        class="page-title-action"><?php _e('Add New', 'elyamani-slider'); ?></a>
    <hr class="wp-header-end">

    <?php if (empty($sliders)): ?>
        <div class="notice notice-info">
            <p><?php _e('No sliders found. Click the "Add New" button to create your first slider.', 'elyamani-slider'); ?>
            </p>
        </div>
    <?php else: ?>
        <table class="wp-list-table widefat fixed striped elyamani-slider-table">
            <thead>
                <tr>
                    <th><?php _e('ID', 'elyamani-slider'); ?></th>
                    <th><?php _e('Name', 'elyamani-slider'); ?></th>
                    <th><?php _e('Shortcode', 'elyamani-slider'); ?></th>
                    <th><?php _e('Slides', 'elyamani-slider'); ?></th>
                    <th><?php _e('Created', 'elyamani-slider'); ?></th>
                    <th><?php _e('Actions', 'elyamani-slider'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sliders as $slider):
                    $slides = json_decode($slider['slides'], true);
                    $slide_count = is_array($slides) ? count($slides) : 0;
                    ?>
                    <tr>
                        <td><?php echo esc_html($slider['id']); ?></td>
                        <td><?php echo esc_html($slider['name']); ?></td>
                        <td><code>[elyamani_slider id="<?php echo esc_attr($slider['id']); ?>"]</code></td>
                        <td><?php echo esc_html($slide_count); ?></td>
                        <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($slider['created_at']))); ?></td>
                        <td class="actions">
                            <a href="<?php echo admin_url('admin.php?page=elyamani-slider-create&id=' . $slider['id']); ?>"
                                class="button button-small"><?php _e('Edit', 'elyamani-slider'); ?></a>
                            <button class="button button-small elyamani-delete-slider"
                                data-slider-id="<?php echo esc_attr($slider['id']); ?>"><?php _e('Delete', 'elyamani-slider'); ?></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="elyamani-slider-info">
        <h2><?php _e('How to use', 'elyamani-slider'); ?></h2>
        <p><?php _e('To display a slider on your site, use the shortcode shown in the table above. For example:', 'elyamani-slider'); ?>
        </p>
        <code>[elyamani_slider id="1"]</code>

        <h3><?php _e('PHP Function', 'elyamani-slider'); ?></h3>
        <p><?php _e('You can also use the following PHP function in your theme files:', 'elyamani-slider'); ?></p>
        <pre>
&lt;?php 
if (function_exists('elyamani_display_slider')) {
    elyamani_display_slider(1); // Replace 1 with your slider ID
}
?&gt;
        </pre>
    </div>
</div>