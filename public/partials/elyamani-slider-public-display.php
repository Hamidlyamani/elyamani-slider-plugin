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
            <?php if (!empty($slide['image'])): ?>
                <?php if (!empty($slide['link'])): ?>
                    <a href="<?php echo esc_url($slide['link']); ?>">
                        <img src="<?php echo esc_url($slide['image']); ?>" alt="<?php echo esc_attr($slide['title']); ?>">
                    </a>
                <?php else: ?>
                    <img src="<?php echo esc_url($slide['image']); ?>" alt="<?php echo esc_attr($slide['title']); ?>">
                <?php endif; ?>
            <?php endif; ?>

            <?php if (!empty($slide['caption']) || !empty($slide['title']) || (!empty($slide['button_text']) && !empty($slide['link']))): ?>
                <div class="elyamani-slide-caption">
                    <?php if (!empty($slide['title'])): ?>
                        <h3><?php echo esc_html($slide['title']); ?></h3>
                    <?php endif; ?>

                    <?php if (!empty($slide['caption'])): ?>
                        <div class="elyamani-slide-content"><?php echo wp_kses_post($slide['caption']); ?></div>
                    <?php endif; ?>

                    <?php if (!empty($slide['button_text']) && !empty($slide['link'])): ?>
                        <a href="<?php echo esc_url($slide['link']); ?>"
                            class="button"><?php echo esc_html($slide['button_text']); ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>