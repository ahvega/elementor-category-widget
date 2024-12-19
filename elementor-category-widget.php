<?php
/**
 * Plugin Name: Custom Category Archive Widget for Elementor
 * Plugin URI:
 * Description: Advanced Elementor widget for displaying customizable category archives
 * Version: 1.0.0
 * Author: Adalberto H. Vega
 * Author URI: https://paypal.me/inteldevign/
 * Text Domain: elementor-category-widget
 * License: GPL v2 or later
 *
 * @package ElementorCategoryWidget
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define plugin constants.
 */
define( 'ELEMENTOR_CATEGORY_WIDGET_VERSION', '1.0.0' );
define( 'ELEMENTOR_CATEGORY_WIDGET_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ELEMENTOR_CATEGORY_WIDGET_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Check if Elementor is installed and activated
 */
if (!function_exists('is_plugin_active')) {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

function elementor_category_widget_requirements_met() {
    return is_plugin_active('elementor/elementor.php');
}

/**
 * Initialize the plugin
 */
function elementor_category_widget_init() {
    // Check if Elementor is installed and activated
    if (!did_action('elementor/loaded')) {
        add_action('admin_notices', function() {
            echo '<div class="error"><p>' .
                 esc_html__('Elementor Category Widget requires Elementor plugin.', 'elementor-category-widget') .
                 '</p></div>';
        });
        return;
    }

    // Register category
    add_action('elementor/elements/categories_registered', function($elements_manager) {
        $elements_manager->add_category(
            'custom-category',
            [
                'title' => esc_html__('Custom Category', 'elementor-category-widget'),
                'icon' => 'fa fa-plug',
            ]
        );
    });

    // Register widget
    add_action('elementor/widgets/register', 'register_category_widget');
}

add_action('plugins_loaded', 'elementor_category_widget_init');

// Simplify scripts loading
function elementor_category_widget_scripts() {
    wp_enqueue_style(
        'elementor-category-widget',
        ELEMENTOR_CATEGORY_WIDGET_PLUGIN_URL . 'assets/css/widget.css',
        [],
        ELEMENTOR_CATEGORY_WIDGET_VERSION
    );

    if (is_admin()) {
        wp_enqueue_script(
            'elementor-category-widget',
            ELEMENTOR_CATEGORY_WIDGET_PLUGIN_URL . 'assets/js/widget.js',
            ['jquery'],
            ELEMENTOR_CATEGORY_WIDGET_VERSION,
            true
        );
    }
}
add_action('elementor/frontend/after_enqueue_styles', 'elementor_category_widget_scripts');

// Remove unused code
remove_action('elementor/widgets/register', 'init_widgets');

/**
 * Register Widget Category
 *
 * Register custom widget category for this widget.
 *
 * @since 1.0.0
 * @param array $elements_manager Elementor elements manager.
 * @return array Modified elements manager.
 */
function add_elementor_widget_categories( $elements_manager ) {
    $elements_manager->add_category(
        'custom-category',
        [
            'title' => esc_html__( 'Custom Category', 'elementor-category-widget' ),
            'icon' => 'fa fa-plug',
        ]
    );
    return $elements_manager;
}

/**
 * Init Widgets
 *
 * Include widget files and register them.
 *
 * @since 1.0.0
 * @return void
 */
function init_widgets() {
    require_once( __DIR__ . '/includes/class-widget.php' );
}
add_action( 'elementor/widgets/register', 'init_widgets' );

/**
 * Register Category Widget
 *
 * Register the custom category widget.
 *
 * @since 1.0.0
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 * @return void
 */
function register_category_widget( $widgets_manager ) {
    require_once ELEMENTOR_CATEGORY_WIDGET_PLUGIN_DIR . 'includes/class-widget.php';
    $widgets_manager->register(new \ElementorCategoryWidget\Elementor_Category_Widget());
}

// Add AJAX handlers
add_action( 'wp_ajax_get_categories_by_post_type', 'get_categories_by_post_type_callback' );
add_action( 'wp_ajax_nopriv_get_categories_by_post_type', 'get_categories_by_post_type_callback' );

function get_categories_by_post_type_callback() {
	check_ajax_referer( 'dynamic_category_nonce', 'nonce' );

	$post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : '';

	if ( empty( $post_type ) ) {
		wp_send_json_error( 'No post type provided.' );
	}

	$taxonomies = get_object_taxonomies($post_type, 'objects');
	$options = [];

	foreach ($taxonomies as $tax_slug => $tax) {
		if ($tax_slug === 'category' || $tax->hierarchical) {
			$terms = get_terms([
				'taxonomy' => $tax_slug,
				'hide_empty' => false
			]);

			if (!is_wp_error($terms)) {
				foreach ($terms as $term) {
					$options[$term->term_id] = $term->name;
				}
			}
		}
	}

	wp_send_json_success($options);
}

// Enqueue JavaScript
function enqueue_dynamic_category_scripts() {
	wp_enqueue_script( 'dynamic-category-filter', plugins_url( 'assets/js/dynamic-category-filter.js', __FILE__ ), array( 'jquery' ), '1.0', true );

	wp_localize_script( 'dynamic-category-filter', 'dynamic_category_ajax', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'dynamic_category_nonce' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'enqueue_dynamic_category_scripts' );
add_action( 'admin_enqueue_scripts', 'enqueue_dynamic_category_scripts' );
