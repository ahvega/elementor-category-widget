<?php
/**
 * Elementor Category Widget class.
 *
 * @package ElementorCategoryWidget
 */

namespace ElementorCategoryWidget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;  // Add this line instead
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Category Widget
 *
 * Elementor widget for displaying customizable category archives.
 *
 * @since 1.0.0
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
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
			);

		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		$post_type_options = array();
		foreach ( $post_types as $post_type ) {
			$post_type_options[ $post_type->name ] = $post_type->label;
		}

		$this->add_control(
			'post_type',
			[
				'label'   => __( 'Select Post Type', 'elementor-category-widget' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $post_type_options,
				'default' => 'post',
			]
		);

		$this->add_control(
			'categories',
			[
				'label'       => __( 'Select Category', 'elementor-category-widget' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(),
				'description' => __( 'Select a category. This will be populated after choosing a Post Type', 'elementor-category-widget' ),
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

		$this->end_controls_section();

		// Style tab.
		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Style', 'elementor-category-widget' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Typography section
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'label'    => __( 'Typography', 'elementor-category-widget' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .category-title, {{WRAPPER}} .category-description, {{WRAPPER}} .category-count',
			]
		);

		// Colors.
		$this->add_control(
			'text_color',
			[
				'label'     => __( 'Text Color', 'elementor-category-widget' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .category-description' => 'color: {{VALUE}};',
					'{{WRAPPER}} .category-count' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label'     => __( 'Background Color', 'elementor-category-widget' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label'     => __( 'Hover Color', 'elementor-category-widget' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category-item:hover .category-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .category-item:hover .category-description' => 'color: {{VALUE}};',
					'{{WRAPPER}} .category-item:hover .category-count' => 'color: {{VALUE}};',
					'{{WRAPPER}} .category-item:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		// Spacing.
		$this->add_control(
			'spacing_heading',
			[
				'label'     => __( 'Spacing', 'elementor-category-widget' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'margin',
			[
				'label'      => __( 'Margin', 'elementor-category-widget' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .category-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label'      => __( 'Padding', 'elementor-category-widget' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .category-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Borders.
		$this->add_control(
			'border_heading',
			[
				'label'     => __( 'Border', 'elementor-category-widget' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'border',
				'label'    => __( 'Border', 'elementor-category-widget' ),
				'selector' => '{{WRAPPER}} .category-item',
			]
		);

		// List style.
		$this->add_control(
			'list_style_heading',
			[
				'label'     => __( 'List Style', 'elementor-category-widget' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'list_style',
			[
				'label'   => __( 'List Style', 'elementor-category-widget' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'none'   => __( 'None', 'elementor-category-widget' ),
					'bullet' => __( 'Bullet', 'elementor-category-widget' ),
					'custom' => __( 'Custom', 'elementor-category-widget' ),
				],
				'default' => 'none',
			]
		);

		$this->add_control(
			'custom_marker',
			[
				'label'     => __( 'Custom Marker', 'elementor-category-widget' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => [
					'list_style' => 'custom',
				],
			]
		);

		// Category image.
		$this->add_control(
			'image_heading',
			[
				'label'     => __( 'Category Image', 'elementor-category-widget' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label'      => __( 'Image Width', 'elementor-category-widget' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .category-image' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label'      => __( 'Image Height', 'elementor-category-widget' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .category-image' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'show_image' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'image_border',
				'label'     => __( 'Image Border', 'elementor-category-widget' ),
				'selector'  => '{{WRAPPER}} .category-image',
				'condition' => [
					'show_image' => 'yes',
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
		static $types = null;

        if ($types === null) {
            $types = [];
            $post_types = get_post_types(['public' => true], 'objects');

            foreach ($post_types as $post_type) {
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

        $taxonomy = get_object_taxonomies($post_type, 'objects');
        $options = [];

        foreach ($taxonomy as $tax_slug => $tax) {
            if ($tax_slug === 'category' || $tax->hierarchical) {
                $terms = get_terms(['taxonomy' => $tax_slug, 'hide_empty' => false]);
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

		$post_type = $settings['post_type'];
		$selected_categories = $settings['categories'];
		$show_description = $settings['show_description'];
		$show_count = $settings['show_count'];
		$show_image = $settings['show_image'];
		$hierarchical = $settings['hierarchical'];
		$sort_order = $settings['sort_order'];
		$number_of_posts = $settings['number_of_posts'];
		$offset = $settings['offset'];
		$order_by = $settings['order_by'];

		$taxonomy = 'category'; // Default to 'category'
		$taxonomies = get_object_taxonomies($post_type, 'objects');
		foreach ($taxonomies as $tax_slug => $tax) {
			if ($tax->hierarchical) {
				$taxonomy = $tax_slug;
				break;
			}
		}

		$args = [
			'taxonomy'   => $taxonomy,
			'orderby'    => $order_by,
			'order'      => $sort_order,
			'hide_empty' => false,
			'include'    => $selected_categories,
			'number'     => $number_of_posts,
			'offset'     => $offset,
		];

		if ($hierarchical) {
			$args['parent'] = 0; // Fetch only top-level categories
		}

		$terms = get_terms($args);

		echo '<div id="' . esc_attr($widget_id) . '" class="category-widget-container">';

		if (!empty($terms) && !is_wp_error($terms)) {
			echo '<ul class="category-list">';

			foreach ($terms as $term) {
				$term_link = get_term_link($term);

				echo '<li class="category-item">';
				echo '<a href="' . esc_url($term_link) . '" class="category-title">' . esc_html($term->name) . '</a>';

				if ($show_count === 'yes') {
					echo '<span class="category-count">(' . esc_html($term->count) . ')</span>';
				}

				if ($show_description === 'yes') {
					$description = term_description($term->term_id, $taxonomy);
					if (!empty($description)) {
						echo '<div class="category-description">' . wp_kses_post($description) . '</div>';
					}
				}

				if ($show_image) {
					// Placeholder for category image. You'll need to implement a custom function to retrieve the image.
					$image_url = $this->get_category_image_url($term->term_id);
					if (!empty($image_url)) {
						echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($term->name) . '" class="category-image">';
					}
				}

				if ($hierarchical) {
					$child_args = [
						'taxonomy'   => $taxonomy,
						'orderby'    => $order_by,
						'order'      => $sort_order,
						'hide_empty' => false,
						'child_of'   => $term->term_id,
					];
					$child_terms = get_terms($child_args);

					if (!empty($child_terms) && !is_wp_error($child_terms)) {
						echo '<ul class="children">';
						foreach ($child_terms as $child_term) {
							$child_term_link = get_term_link($child_term);
							echo '<li class="category-item">';
							echo '<a href="' . esc_url($child_term_link) . '" class="category-title">' . esc_html($child_term->name) . '</a>';

							if ($show_count) {
								echo '<span class="category-count">(' . esc_html($child_term->count) . ')</span>';
							}

							if ($show_description) {
								$child_description = term_description($child_term->term_id, $taxonomy);
								if (!empty($child_description)) {
									echo '<div class="category-description">' . wp_kses_post($child_description) . '</div>';
								}
							}

							echo '</li>';
						}
						echo '</ul>';
					}
				}
				echo '</li>';
			}
			echo '</ul>';
		} else {
			echo '<p>' . esc_html__('No categories found.', 'elementor-category-widget') . '</p>';
		}

		echo '</div>';
	}

	/**
	 * Get category image URL.
	 *
	 * Retrieve the URL of the category image.
	 *
	 * @since 1.0.0
	 * @access private
	 * @param int $term_id Category ID.
	 * @return string|null Image URL or null if no image is found.
	 */
	private function get_category_image_url($term_id) {
		// Placeholder function to retrieve category image URL.
		// You'll need to implement the actual logic to fetch the image based on your setup.
		// This could involve using a custom field, a plugin, or any other method you use to associate images with categories.

		// Example using a custom field (replace 'category_image' with your actual custom field name):
		$image_id = get_term_meta($term_id, 'category_image', true);
		if ($image_id) {
			$image_url = wp_get_attachment_url($image_id);
			if ($image_url) {
				return $image_url;
			}
		}

		return null;
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
			<# if ( settings.categories ) { #>
				<div id="category-widget-{{view.getIDInt()}}" class="category-widget-container">
					<ul class="category-list">
						<# _.each( settings.categories, function( category ) { #>
							<li class="category-item">
								<# if ( settings.show_image ) { #>
									<div class="category-image"></div>
								<# } #>
								<div class="category-title">{{{ category }}}</div>
								<# if ( settings.show_description === 'yes' ) { #>
									<div class="category-description"></div>
								<# } #>
								<# if ( settings.show_count === 'yes' ) { #>
									<span class="category-count"></span>
								<# } #>
							</li>
						<# }); #>
					</ul>
				</div>
			<# } else { #>
				<p><?php echo esc_html__('No categories selected.', 'elementor-category-widget'); ?></p>
			<# } #>
		</div>
		<?php
	}
} // End of class Elementor_Category_Widget
