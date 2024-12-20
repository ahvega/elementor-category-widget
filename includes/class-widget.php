<?php
/**
 * Elementor Category Widget class.
 *
 * @package ElementorCategoryWidget
 */

namespace ElementorCategoryWidget;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Typography;
use \Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Css_Filter;
use \Elementor\Group_Control_Background;
use \Elementor\Utils;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor Category Widget
 */
class Elementor_Category_Widget extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve category widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'category-widget';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve category widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Category Widget', 'elementor-category-widget' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve category widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-archive-posts';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the category widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return ['custom-category']; // Make sure this matches the category ID in add_elementor_widget_categories
	}

	/**
	 * Register category widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		// Content tab
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'elementor-category-widget' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
			);

		// Post Type Control
		$this->add_control(
			'post_type',
			[
				'label'   => __( 'Select Post Type', 'elementor-category-widget' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_post_types(),
				'default' => 'post',
			]
		);

		// Categories Control
		$this->add_control(
			'categories',
			[
				'label'       => __( 'Select Categories', 'elementor-category-widget' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->get_categories_by_post_type('stm_service'),
				'description' => __( 'Select categories to display', 'elementor-category-widget' ),
			]
		);

		// Add hidden control for category data
		$this->add_control(
			'_categories_data',
			[
				'label' => '_categories_data',
				'type' => Controls_Manager::HIDDEN,
				'default' => $this->get_categories_data('stm_service'),
			]
		);

		$this->add_control(
			'show_description',
			[
				'label'     => __( 'Show Description', 'elementor-category-widget' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'elementor-category-widget' ),
				'label_off' => __( 'Hide', 'elementor-category-widget' ),
				'return_value' => 'yes',
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'show_count',
			[
				'label'     => __( 'Show Post Count', 'elementor-category-widget' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'elementor-category-widget' ),
				'label_off' => __( 'Hide', 'elementor-category-widget' ),
				'return_value' => 'yes',
				'default'   => 'no',
			]
		);

		$this->add_control(
			'show_image',
			[
				'label'     => __( 'Show Category Image', 'elementor-category-widget' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'elementor-category-widget' ),
				'label_off' => __( 'Hide', 'elementor-category-widget' ),
				'return_value' => 'yes',
				'default'   => 'no',
			]
		);

		$this->add_control(
			'image_source',
			[
				'label'     => __( 'Image Source', 'elementor-category-widget' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'featured'  => __( 'Featured Image', 'elementor-category-widget' ),
					'custom'    => __( 'Custom Field', 'elementor-category-widget' ),
					'acf'       => __( 'ACF Field', 'elementor-category-widget' ),
				],
				'default'   => 'featured',
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_control(
			'custom_image_field',
			[
				'label'       => __( 'Custom Field Name', 'elementor-category-widget' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter custom field name', 'elementor-category-widget' ),
				'condition'   => [
					'show_image'    => 'yes',
					'image_source'  => 'custom',
				],
			]
		);

		$this->add_control(
			'acf_image_field',
			[
				'label'       => __( 'ACF Field Name', 'elementor-category-widget' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter ACF field name', 'elementor-category-widget' ),
				'condition'   => [
					'show_image'    => 'yes',
					'image_source'  => 'acf',
				],
			]
		);

		$this->add_control(
			'fallback_image',
			[
				'label'     => __( 'Fallback Image', 'elementor-category-widget' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// Style tab
		$this->start_controls_section(
			'title_style_section',
			[
				'label' => __('Title', 'elementor-category-widget'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __('Typography', 'elementor-category-widget'),
				'selector' => '{{WRAPPER}} .category-title',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __('Color', 'elementor-category-widget'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' => __('Hover Color', 'elementor-category-widget'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => __('Spacing', 'elementor-category-widget'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .category-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Description Style
		$this->start_controls_section(
			'description_style_section',
			[
				'label' => __('Description', 'elementor-category-widget'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_description' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => __('Typography', 'elementor-category-widget'),
				'selector' => '{{WRAPPER}} .category-description',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __('Color', 'elementor-category-widget'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'description_spacing',
			[
				'label' => __('Spacing', 'elementor-category-widget'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .category-description' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Post Count Style
		$this->start_controls_section(
			'count_style_section',
			[
				'label' => __('Post Count', 'elementor-category-widget'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_count' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'count_typography',
				'label' => __('Typography', 'elementor-category-widget'),
				'selector' => '{{WRAPPER}} .category-count',
			]
		);

		$this->add_control(
			'count_color',
			[
				'label' => __('Color', 'elementor-category-widget'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-count' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Image Style
		$this->start_controls_section(
			'image_style_section',
			[
				'label' => __('Image', 'elementor-category-widget'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label' => __('Height', 'elementor-category-widget'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'vh'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .category-image-wrapper' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_fit',
			[
				'label' => __('Object Fit', 'elementor-category-widget'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'cover' => __('Cover', 'elementor-category-widget'),
					'contain' => __('Contain', 'elementor-category-widget'),
					'fill' => __('Fill', 'elementor-category-widget'),
					'none' => __('None', 'elementor-category-widget'),
				],
				'default' => 'cover',
				'selectors' => [
					'{{WRAPPER}} .category-image' => 'object-fit: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => __('Border', 'elementor-category-widget'),
				'selector' => '{{WRAPPER}} .category-image-wrapper',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => __('Border Radius', 'elementor-category-widget'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .category-image-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Container Style
		$this->start_controls_section(
			'container_style_section',
			[
				'label' => __('Container', 'elementor-category-widget'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => __('Columns', 'elementor-category-widget'),
				'type' => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'selectors' => [
					'{{WRAPPER}} .category-list' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label' => __('Columns Gap', 'elementor-category-widget'),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .category-list' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => __('Background Color', 'elementor-category-widget'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => __('Box Shadow', 'elementor-category-widget'),
				'selector' => '{{WRAPPER}} .category-item',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __('Border', 'elementor-category-widget'),
				'selector' => '{{WRAPPER}} .category-item',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __('Border Radius', 'elementor-category-widget'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .category-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => __('Padding', 'elementor-category-widget'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .category-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Advanced tab
		$this->start_controls_section(
			'advanced_section',
			[
				'label' => __( 'Advanced', 'elementor-category-widget' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		// Custom CSS classes
		$this->add_control(
			'custom_css_classes',
			[
				'label'       => __( 'CSS Classes', 'elementor-category-widget' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Add custom CSS classes to the widget.', 'elementor-category-widget' ),
			]
		);

		// Custom CSS
		$this->add_control(
			'custom_css',
			[
				'label'       => __( 'Custom CSS', 'elementor-category-widget' ),
				'type'        => Controls_Manager::CODE,
				'language'    => 'css',
				'description' => __( 'Add custom CSS to style the widget.', 'elementor-category-widget' ),
				'selectors'   => [
					'{{WRAPPER}}' => '{{VALUE}}',
				],
			]
		);

		// Margin
		$this->add_responsive_control(
			'advanced_margin',
			[
				'label'      => __( 'Margin', 'elementor-category-widget' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'advanced_padding',
			[
				'label'      => __( 'Padding', 'elementor-category-widget' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Layout
		$this->add_control(
			'layout_heading',
			[
				'label'     => __( 'Layout', 'elementor-category-widget' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'display',
			[
				'label'   => __( 'Display', 'elementor-category-widget' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'block'       => __( 'Block', 'elementor-category-widget' ),
					'inline-block' => __( 'Inline Block', 'elementor-category-widget' ),
					'flex'        => __( 'Flex', 'elementor-category-widget' ),
					'inline-flex' => __( 'Inline Flex', 'elementor-category-widget' ),
					'none'        => __( 'None', 'elementor-category-widget' ),
				],
				'default'   => 'block',
				'selectors' => [
					'{{WRAPPER}}' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'position',
			[
				'label'   => __( 'Position', 'elementor-category-widget' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'static'   => __( 'Static', 'elementor-category-widget' ),
					'relative' => __( 'Relative', 'elementor-category-widget' ),
					'absolute' => __( 'Absolute', 'elementor-category-widget' ),
					'fixed'    => __( 'Fixed', 'elementor-category-widget' ),
				],
				'default'   => 'static',
				'selectors' => [
					'{{WRAPPER}}' => 'position: {{VALUE}};',
				],
			]
		);

		// Motion Effects
		$this->add_control(
			'motion_effects_heading',
			[
				'label'     => __( 'Motion Effects', 'elementor-category-widget' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'entrance_animation',
			[
				'label'   => __( 'Entrance Animation', 'elementor-category-widget' ),
				'type'    => Controls_Manager::ANIMATION,
				'default' => '',
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label'   => __( 'Hover Animation', 'elementor-category-widget' ),
				'type'    => Controls_Manager::HOVER_ANIMATION,
					'default' => '',
			]
		);

		// Responsive Controls
		$this->add_control(
			'responsive_heading',
			[
				'label'     => __( 'Responsive', 'elementor-category-widget' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'hide_desktop',
			[
				'label'     => __( 'Hide on Desktop', 'elementor-category-widget' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Hide', 'elementor-category-widget' ),
				'label_off' => __( 'Show', 'elementor-category-widget' ),
				'selectors' => [
					'@media (min-width: 1025px)' => [
						'{{WRAPPER}}' => 'display: none;',
					],
				],
			]
		);

		$this->add_control(
			'hide_tablet',
			[
				'label'     => __( 'Hide on Tablet', 'elementor-category-widget' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Hide', 'elementor-category-widget' ),
				'label_off' => __( 'Show', 'elementor-category-widget' ),
				'selectors' => [
					'@media (min-width: 768px) and (max-width: 1024px)' => [
						'{{WRAPPER}}' => 'display: none;',
					],
				],
			]
		);

		$this->add_control(
			'hide_mobile',
			[
				'label'     => __( 'Hide on Mobile', 'elementor-category-widget' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Hide', 'elementor-category-widget' ),
				'label_off' => __( 'Show', 'elementor-category-widget' ),
				'selectors' => [
					'@media (max-width: 767px)' => [
						'{{WRAPPER}}' => 'display: none;',
					],
				],
			]
		);

		// Background
		$this->add_control(
			'background_heading',
			[
				'label'     => __( 'Background', 'elementor-category-widget' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'advanced_background',
				'label'    => __( 'Background', 'elementor-category-widget' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}}',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Get available post types.
	 *
	 * Retrieve the available post types for the post type selector.
	 *
	 * @since 1.0.0
	 * @access private
	 * @return array Post types.
	 */
	private function get_post_types() {
		$post_types = get_post_types([
			'public' => true,
			'show_in_nav_menus' => true,
		], 'objects');

		$types = [];
		foreach ($post_types as $post_type) {
			// Skip WP core post types except 'post'
			if (!in_array($post_type->name, ['attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset', 'oembed_cache', 'user_request', 'wp_block'])) {
				$types[$post_type->name] = $post_type->label;
			}
		}

		return $types;
	}

	/**
	 * Get categories by post type.
	 *
	 * Retrieve the categories for the selected post type.
	 *
	 * @since 1.0.0
	 * @access private
	 * @return array Categories.
	 */
	private function get_categories_by_post_type($post_type = 'post') {
		if (empty($post_type)) {
			return [];
		}

		$taxonomies = get_object_taxonomies($post_type, 'objects');
		$options = [];

		foreach ($taxonomies as $tax_slug => $tax) {
			// Include hierarchical taxonomies and specific custom taxonomies
			if ($tax->hierarchical || in_array($tax_slug, ['category', 'stm_service_category', 'portfolio_category', 'testimonial_category', 'work_category', 'staff_category', 'vacancy_category'])) {
				$terms = get_terms([
					'taxonomy' => $tax_slug,
					'hide_empty' => false,
				]);

				if (!is_wp_error($terms)) {
					foreach ($terms as $term) {
						$options[$term->term_id] = $term->name;
					}
				}
			}
		}

		return $options;
	}

	/**
	 * Render category widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$widget_id = 'category-widget-' . $this->get_id();

		// Get selected categories
		$selected_categories = !empty($settings['categories']) ? $settings['categories'] : [];

		// Get taxonomy based on post type
		$taxonomy = $settings['post_type'] === 'stm_service' ? 'stm_service_category' : 'category';

		// Get terms
		$terms = [];
		if (!empty($selected_categories)) {
			$terms = get_terms([
				'taxonomy' => $taxonomy,
				'include' => $selected_categories,
				'hide_empty' => false,
			]);
		}

		if (is_wp_error($terms)) {
			echo '<div class="elementor-alert elementor-alert-danger">';
			echo esc_html__('Error: Could not fetch categories.', 'elementor-category-widget');
			echo '</div>';
			return;
		}

		// Start output
		echo '<div id="' . esc_attr($widget_id) . '" class="category-widget-container">';

		if (!empty($terms)) {
			echo '<ul class="category-list">';

			foreach ($terms as $term) {
				$term_link = get_term_link($term);
				if (is_wp_error($term_link)) continue;

				echo '<li class="category-item">';

				// Image
				if ($settings['show_image'] === 'yes') {
					$image_url = $this->get_category_image_url($term->term_id, $settings);
					if ($image_url) {
						echo '<div class="category-image-wrapper">';
						echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($term->name) . '" class="category-image">';
						echo '</div>';
					}
				}

				echo '<div class="category-content">';

				// Title
				echo '<h4 class="category-title">';
				echo '<a href="' . esc_url($term_link) . '">' . esc_html($term->name) . '</a>';

				// Post count
				if ($settings['show_count'] === 'yes') {
					echo '<span class="category-count"> (' . esc_html($term->count) . ')</span>';
				}
				echo '</h4>';

				// Description
				if ($settings['show_description'] === 'yes' && !empty($term->description)) {
					echo '<div class="category-description">' . wp_kses_post($term->description) . '</div>';
				}

				echo '</div>'; // .category-content
				echo '</li>';
			}

			echo '</ul>';
		} else {
			echo '<p class="elementor-warning">';
			echo esc_html__('No categories selected.', 'elementor-category-widget');
			echo '</p>';
		}

		echo '</div>'; // .category-widget-container
	}

	/**
	 * Get category image URL based on settings
	 *
	 * @param int    $term_id     The term ID.
	 * @param array  $settings    Widget settings.
	 * @return string|null        Image URL or null if not found.
	 */
	private function get_category_image_url($term_id, $settings) {
		if ($settings['show_image'] !== 'yes') {
			return null;
		}

		$image_url = null;

		try {
			switch ($settings['image_source']) {
				case 'featured':
					// Try to get the featured image from the latest post in this category
					$posts = get_posts([
						'post_type'      => $settings['post_type'],
						'posts_per_page' => 1,
						'tax_query'      => [
							[
								'taxonomy' => get_term($term_id)->taxonomy,
								'field'    => 'term_id',
								'terms'    => $term_id,
							],
						],
						'meta_query'     => [
							[
								'key' => '_thumbnail_id',
								'compare' => 'EXISTS'
							],
						],
					]);

					if (!empty($posts)) {
						$image_url = get_the_post_thumbnail_url($posts[0]->ID, 'medium');
					}
					break;

				case 'custom':
					if (!empty($settings['custom_image_field'])) {
						$image_url = get_term_meta($term_id, $settings['custom_image_field'], true);
					}
					break;

				case 'acf':
					if (!empty($settings['acf_image_field']) && function_exists('get_field')) {
						$image = get_field($settings['acf_image_field'], get_term($term_id)->taxonomy . '_' . $term_id);
						if (is_array($image) && isset($image['url'])) {
							$image_url = $image['url'];
						} elseif (is_string($image)) {
							$image_url = $image;
						}
					}
					break;
			}

			// Return fallback image if no image found and fallback is set
			if (empty($image_url) && !empty($settings['fallback_image']['url'])) {
				return $settings['fallback_image']['url'];
			}

			return $image_url;
		} catch (\Exception $e) {
			error_log('Category Widget Error: ' . $e->getMessage());
			return null;
		}
	}

	/**
	 * Render category widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<div class="elementor-category-widget-wrapper">
			<# if ( settings.categories && settings.categories.length ) { #>
				<div class="category-widget-container">
					<ul class="category-list">
						<# _.each( settings.categories, function( categoryId ) { #>
							<li class="category-item">
								<# if ( settings.show_image === 'yes' ) { #>
									<div class="category-image-wrapper">
										<img src="" class="category-image" alt="">
									</div>
								<# } #>

								<div class="category-content">
									<h4 class="category-title">
										<a href="#">{{{ settings._categories_data[categoryId] ? settings._categories_data[categoryId].name : '' }}}</a>
										<# if ( settings.show_count === 'yes' && settings._categories_data[categoryId] ) { #>
											<span class="category-count">({{{ settings._categories_data[categoryId].count }}})</span>
										<# } #>
									</h4>

									<# if ( settings.show_description === 'yes' && settings._categories_data[categoryId] ) { #>
										<div class="category-description">{{{ settings._categories_data[categoryId].description }}}</div>
									<# } #>
								</div>
							</li>
						<# }); #>
					</ul>
				</div>
			<# } else { #>
				<p class="elementor-warning"><?php echo esc_html__('No categories selected.', 'elementor-category-widget'); ?></p>
			<# } #>
		</div>
		<?php
	}

	/**
	 * Class constructor.
	 *
	 * @param array      $data Widget data. Default is an empty array.
	 * @param array|null $args Optional. Widget default arguments. Default is null.
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( 'category-widget-style', plugins_url( '/assets/css/widget.css', dirname( __FILE__ ) ), [], '1.0.0' );
		wp_register_script( 'category-widget-script', plugins_url( '/assets/js/widget.js', dirname( __FILE__ ) ), [ 'jquery' ], '1.0.0', true );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'category', 'categories', 'taxonomy', 'archive' ];
	}

	/**
	 * Get widget style dependencies.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return [ 'category-widget-style' ];
	}

	/**
	 * Get widget script dependencies.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return [ 'category-widget-script' ];
	}

	public function on_import( $element ) {
		return \Elementor\Plugin::$instance->templates_manager->get_import_images_instance()->import( $element );
	}

	private function get_categories_data($post_type = 'post') {
		$taxonomy = $post_type === 'stm_service' ? 'stm_service_category' : 'category';
		$terms = get_terms([
			'taxonomy' => $taxonomy,
			'hide_empty' => false,
		]);

		$data = [];
		if (!is_wp_error($terms)) {
			foreach ($terms as $term) {
				$data[$term->term_id] = [
					'name' => $term->name,
					'description' => $term->description,
					'count' => $term->count,
					'link' => get_term_link($term),
				];
			}
		}

		return json_encode($data);
	}

} // End of class Elementor_Category_Widget

// End of File class-widget.php