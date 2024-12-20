<?php
/**
 * Plugin Name: Elementor Category Widget
 * Description: A custom Elementor widget for displaying category archives
 * Version: 1.0.0
 * Author: Adalberto H. Vega
 * Author URI: https://patpal.me/inteldevign
 * Text Domain: elementor-category-widget
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Elementor_Category_Widget_Plugin {
    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init() {
        // Check if Elementor is installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return;
        }

        // Add Plugin actions
        add_action('elementor/widgets/register', [$this, 'init_widgets']);
        add_action('elementor/elements/categories_registered', [$this, 'add_elementor_widget_categories']);
    }

    public function admin_notice_missing_main_plugin() {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'elementor-category-widget'),
            '<strong>' . esc_html__('Elementor Category Widget', 'elementor-category-widget') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-category-widget') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function init_widgets($widgets_manager) {
        require_once(__DIR__ . '/includes/class-widget.php');
        $widgets_manager->register(new \ElementorCategoryWidget\Elementor_Category_Widget());
    }

    public function add_elementor_widget_categories($elements_manager) {
        $elements_manager->add_category(
            'custom-category',
            [
                'title' => esc_html__('Custom Category', 'elementor-category-widget'),
                'icon' => 'fa fa-plug',
            ]
        );
    }
}

// Initialize plugin
Elementor_Category_Widget_Plugin::instance();

// Register AJAX handler
add_action('wp_ajax_get_categories_by_post_type', function() {
    check_ajax_referer('elementor-editing', 'nonce');

    if (!isset($_POST['post_type'])) {
        wp_send_json_error('No post type specified');
        return;
    }

    $post_type = sanitize_text_field($_POST['post_type']);
    $taxonomy = $post_type === 'stm_service' ? 'stm_service_category' : 'category';

    $terms = get_terms([
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
    ]);

    if (is_wp_error($terms)) {
        wp_send_json_error('Error fetching categories');
        return;
    }

    $categories = [];
    foreach ($terms as $term) {
        $categories[$term->term_id] = $term->name;
    }

    wp_send_json_success($categories);
});

// End of File elementor-category-widget.php
