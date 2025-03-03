<?php
/**
 * Admin display for the create/edit slider page.
 *
 * @since      1.0.0
 */
?>

<div class="wrap elyamani-slider-wrap">
    <h1 class="wp-heading-inline">
        <?php echo $slider ? __('Edit Slider', 'elyamani-slider') : __('Create Slider', 'elyamani-slider'); ?>
    </h1>
    <a href="<?php echo admin_url('admin.php?page=elyamani-slider'); ?>"
        class="page-title-action"><?php _e('Back to Sliders', 'elyamani-slider'); ?></a>
    <hr class="wp-header-end">

    <form id="elyamani-slider-form" class="elyamani-slider-form"
        data-action="<?php echo $slider ? 'elyamani_update_slider' : 'elyamani_create_slider'; ?>">
        <?php if ($slider): ?>
        <input type="hidden" name="id" value="<?php echo esc_attr($slider['id']); ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="elyamani_slider_name"><?php _e('Slider Name', 'elyamani-slider'); ?></label>
            <input type="text" id="elyamani_slider_name" name="name"
                value="<?php echo $slider ? esc_attr($slider['name']) : ''; ?>" required>
        </div>

        <div class="elyamani-slider-tabs">
            <ul>
                <li><a href="#slides-tab"><?php _e('Slides', 'elyamani-slider'); ?></a></li>
                <li><a href="#settings-tab"><?php _e('Settings', 'elyamani-slider'); ?></a></li>
                <?php if ($slider): ?>
                <li><a href="#preview-tab"><?php _e('Preview', 'elyamani-slider'); ?></a></li>
                <?php endif; ?>
            </ul>

            <div id="slides-tab">
                <h2><?php _e('Select Slides', 'elyamani-slider'); ?></h2>

                <?php if (empty($slides)): ?>
                <div class="notice notice-warning">
                    <p>
                        <?php _e('No slides found. Please create some slides first.', 'elyamani-slider'); ?>
                        <a
                            href="<?php echo admin_url('post-new.php?post_type=elyamani_slide'); ?>"><?php _e('Create Slide', 'elyamani-slider'); ?></a>
                    </p>
                </div>
                <?php else: ?>
                <p><?php _e('Click on slides to select them for your slider.', 'elyamani-slider'); ?></p>

                <div class="elyamani-slides-container">
                    <?php
                        $selected_slides = $slider ? $slider['slides'] : array();

                        foreach ($slides as $slide):
                            $is_selected = in_array($slide->ID, $selected_slides);
                            $thumbnail = get_the_post_thumbnail_url($slide->ID, 'thumbnail');
                            if (!$thumbnail) {
                                $thumbnail = ELYAMANI_SLIDER_URL . 'admin/images/no-image.png';
                            }
                            ?>
                    <div class="elyamani-slide-item <?php echo $is_selected ? 'selected' : ''; ?>"
                        data-slide-id="<?php echo esc_attr($slide->ID); ?>"
                        data-slide-title="<?php echo esc_attr($slide->post_title); ?>">
                        <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($slide->post_title); ?>">
                        <div class="title"><?php echo esc_html($slide->post_title); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="elyamani-selected-slides">
                    <h3><?php _e('Selected Slides', 'elyamani-slider'); ?></h3>
                    <p class="description"><?php _e('Drag to reorder slides.', 'elyamani-slider'); ?></p>

                    <div class="elyamani-selected-slides-empty"
                        <?php echo !empty($selected_slides) ? 'style="display:none;"' : ''; ?>>
                        <p><?php _e('No slides selected. Click on slides above to select them.', 'elyamani-slider'); ?>
                        </p>
                    </div>

                    <div class="elyamani-selected-slides-list"></div>

                    <input type="hidden" id="elyamani_slider_slides" name="slides"
                        value="<?php echo $slider ? implode(',', $selected_slides) : ''; ?>">
                </div>
                <?php endif; ?>
            </div>

            <div id="settings-tab">
                <h2><?php _e('Slider Settings', 'elyamani-slider'); ?></h2>

                <?php
                // Get settings from slider or use defaults
                $settings = $slider ? $slider['settings'] : $default_settings;
                ?>

                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Autoplay', 'elyamani-slider'); ?></th>
                        <td>
                            <input type="checkbox" id="elyamani_slider_autoplay" name="autoplay" value="1"
                                <?php checked($settings['autoplay'], true); ?>>
                            <label
                                for="elyamani_slider_autoplay"><?php _e('Enable autoplay', 'elyamani-slider'); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Autoplay Speed (ms)', 'elyamani-slider'); ?></th>
                        <td>
                            <input type="number" id="elyamani_slider_autoplay_speed" name="autoplay_speed"
                                value="<?php echo esc_attr($settings['autoplay_speed']); ?>" min="0" step="100">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Animation Speed (ms)', 'elyamani-slider'); ?></th>
                        <td>
                            <input type="number" id="elyamani_slider_speed" name="speed"
                                value="<?php echo esc_attr($settings['speed']); ?>" min="0" step="100">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Show Arrows', 'elyamani-slider'); ?></th>
                        <td>
                            <input type="checkbox" id="elyamani_slider_arrows" name="arrows" value="1"
                                <?php checked($settings['arrows'], true); ?>>
                            <label
                                for="elyamani_slider_arrows"><?php _e('Show navigation arrows', 'elyamani-slider'); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Show Dots', 'elyamani-slider'); ?></th>
                        <td>
                            <input type="checkbox" id="elyamani_slider_dots" name="dots" value="1"
                                <?php checked($settings['dots'], true); ?>>
                            <label
                                for="elyamani_slider_dots"><?php _e('Show navigation dots', 'elyamani-slider'); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Infinite Loop', 'elyamani-slider'); ?></th>
                        <td>
                            <input type="checkbox" id="elyamani_slider_infinite" name="infinite" value="1"
                                <?php checked($settings['infinite'], true); ?>>
                            <label
                                for="elyamani_slider_infinite"><?php _e('Enable infinite loop', 'elyamani-slider'); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Fade Effect', 'elyamani-slider'); ?></th>
                        <td>
                            <input type="checkbox" id="elyamani_slider_fade" name="fade" value="1"
                                <?php checked($settings['fade'], true); ?>>
                            <label
                                for="elyamani_slider_fade"><?php _e('Use fade transition', 'elyamani-slider'); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Adaptive Height', 'elyamani-slider'); ?></th>
                        <td>
                            <input type="checkbox" id="elyamani_slider_adaptive_height" name="adaptiveHeight" value="1"
                                <?php checked($settings['adaptiveHeight'], true); ?>>
                            <label
                                for="elyamani_slider_adaptive_height"><?php _e('Adapt slider height to slides', 'elyamani-slider'); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Slides to Show', 'elyamani-slider'); ?></th>
                        <td>
                            <input type="number" id="elyamani_slider_slides_to_show" name="slidesToShow"
                                value="<?php echo isset($settings['slidesToShow']) ? esc_attr($settings['slidesToShow']) : 1; ?>"
                                min="1" max="10">
                            <p class="description">
                                <?php _e('Number of slides to show at once. Set to 1 for standard slider.', 'elyamani-slider'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Slides to Scroll', 'elyamani-slider'); ?></th>
                        <td>
                            <input type="number" id="elyamani_slider_slides_to_scroll" name="slidesToScroll"
                                value="<?php echo isset($settings['slidesToScroll']) ? esc_attr($settings['slidesToScroll']) : 1; ?>"
                                min="1" max="10">
                            <p class="description">
                                <?php _e('Number of slides to scroll at a time.', 'elyamani-slider'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>

            <?php if ($slider): ?>
            <div id="preview-tab">
                <h2><?php _e('Slider Preview', 'elyamani-slider'); ?></h2>

                <?php
                    // Get the actual slide data for preview
                    $slide_data = array();
                    if (!empty($slider['slides'])) {
                        $slide_data = Elyamani_Slider_Manager::get_slides($slider['slides']);
                    }

                    if (empty($slide_data)): ?>
                <div class="notice notice-warning">
                    <p><?php _e('No slides selected for preview.', 'elyamani-slider'); ?></p>
                </div>
                <?php else: ?>
                <div class="elyamani-slider-preview">
                    <?php foreach ($slide_data as $slide): ?>
                    <div class="elyamani-slide">
                        <?php if (!empty($slide['image'])): ?>
                        <img src="<?php echo esc_url($slide['image']); ?>"
                            alt="<?php echo esc_attr($slide['title']); ?>">
                        <?php endif; ?>

                        <?php if (!empty($slide['caption']) || !empty($slide['title'])): ?>
                        <div class="elyamani-slide-caption">
                            <h3><?php echo esc_html($slide['title']); ?></h3>
                            <div><?php echo wp_kses_post($slide['caption']); ?></div>

                            <?php if (!empty($slide['button_text']) && !empty($slide['link'])): ?>
                            <a href="<?php echo esc_url($slide['link']); ?>"
                                class="button"><?php echo esc_html($slide['button_text']); ?></a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="form-group" style="margin-top: 20px;">
            <button type="submit" class="button button-primary button-large">
                <?php echo $slider ? __('Update Slider', 'elyamani-slider') : __('Create Slider', 'elyamani-slider'); ?>
            </button>
        </div>
    </form>
</div>