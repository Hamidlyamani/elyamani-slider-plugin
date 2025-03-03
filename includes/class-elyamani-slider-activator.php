<?php
/**
 * Fired during plugin activation.
 *
 * @since      1.0.0
 */
class Elyamani_Slider_Activator
{

    /**
     * Activate the plugin.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        // Create necessary database tables
        self::create_tables();

        // Flush rewrite rules to ensure our custom post types work
        flush_rewrite_rules();
    }

    /**
     * Create custom database tables for the plugin.
     *
     * @since    1.0.0
     */
    private static function create_tables()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // Table for storing slider configurations
        $table_name = $wpdb->prefix . 'elyamani_sliders';

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            settings longtext NOT NULL,
            slides longtext NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}