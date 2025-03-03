<?php
/**
 * Fired during plugin deactivation.
 *
 * @since      1.0.0
 */
class Elyamani_Slider_Deactivator
{

    /**
     * Deactivate the plugin.
     *
     * @since    1.0.0
     */
    public static function deactivate()
    {
        // Flush rewrite rules
        flush_rewrite_rules();
    }
}