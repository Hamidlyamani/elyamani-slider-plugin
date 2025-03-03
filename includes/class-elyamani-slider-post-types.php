<?php
/**
 * Register custom post types and taxonomies.
 *
 * @since      1.0.0
 */
class Elyamani_Slider_Post_Types
{

    /**
     * Register custom post types.
     *
     * @since    1.0.0
     */
    public function register_post_types()
    {
        // Register Slide post type
        $labels = array(
            'name' => _x('Slides', 'Post type general name', 'elyamani-slider'),
            'singular_name' => _x('Slide', 'Post type singular name', 'elyamani-slider'),
            'menu_name' => _x('Slides', 'Admin Menu text', 'elyamani-slider'),
            'name_admin_bar' => _x('Slide', 'Add New on Toolbar', 'elyamani-slider'),
            'add_new' => __('Add New', 'elyamani-slider'),
            'add_new_item' => __('Add New Slide', 'elyamani-slider'),
            'new_item' => __('New Slide', 'elyamani-slider'),
            'edit_item' => __('Edit Slide', 'elyamani-slider'),
            'view_item' => __('View Slide', 'elyamani-slider'),
            'all_items' => __('All Slides', 'elyamani-slider'),
            'search_items' => __('Search Slides', 'elyamani-slider'),
            'parent_item_colon' => __('Parent Slides:', 'elyamani-slider'),
            'not_found' => __('No slides found.', 'elyamani-slider'),
            'not_found_in_trash' => __('No slides found in Trash.', 'elyamani-slider'),
            'featured_image' => _x('Slide Image', 'Overrides the "Featured Image" phrase', 'elyamani-slider'),
            'set_featured_image' => _x('Set slide image', 'Overrides the "Set featured image" phrase', 'elyamani-slider'),
            'remove_featured_image' => _x('Remove slide image', 'Overrides the "Remove featured image" phrase', 'elyamani-slider'),
            'use_featured_image' => _x('Use as slide image', 'Overrides the "Use as featured image" phrase', 'elyamani-slider'),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => 'elyamani-slider',
            'query_var' => true,
            'rewrite' => array('slug' => 'slide'),
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'menu_icon' => 'dashicons-images-alt2',
        );

        register_post_type('elyamani_slide', $args);
    }

    /**
     * Register custom taxonomies.
     *
     * @since    1.0.0
     */
    public function register_taxonomies()
    {
        // Register Slide Category taxonomy
        $labels = array(
            'name' => _x('Slide Categories', 'taxonomy general name', 'elyamani-slider'),
            'singular_name' => _x('Slide Category', 'taxonomy singular name', 'elyamani-slider'),
            'search_items' => __('Search Slide Categories', 'elyamani-slider'),
            'all_items' => __('All Slide Categories', 'elyamani-slider'),
            'parent_item' => __('Parent Slide Category', 'elyamani-slider'),
            'parent_item_colon' => __('Parent Slide Category:', 'elyamani-slider'),
            'edit_item' => __('Edit Slide Category', 'elyamani-slider'),
            'update_item' => __('Update Slide Category', 'elyamani-slider'),
            'add_new_item' => __('Add New Slide Category', 'elyamani-slider'),
            'new_item_name' => __('New Slide Category Name', 'elyamani-slider'),
            'menu_name' => __('Categories', 'elyamani-slider'),
        );

        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'slide-category'),
        );

        register_taxonomy('slide_category', array('elyamani_slide'), $args);
    }
}