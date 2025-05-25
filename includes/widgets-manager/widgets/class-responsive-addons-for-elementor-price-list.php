<?php
/**
 * Price List Widget
 *
 * @since      1.0.0
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Elementor 'Price List' widget.
 *
 * Elementor widget that displays an Price List.
 *
 * @since 1.0.0
 */
class Responsive_Addons_For_Elementor_Price_List extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-price-list';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Price List', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Pricing Table widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-price-list rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the pricing table widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Register all the control settings for the pricing table
	 *
	 * @since 1.0.0
	 * @access public
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_list',
			array(
				'label' => __( 'List', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'price',
			array(
				'label'   => __( 'Price', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'offering_discount',
			array(
				'label'        => __( 'Offering Discount?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$repeater->add_control(
			'original_price',
			array(
				'label'     => __( 'Original Price', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '$100',
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'offering_discount' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'       => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => 'true',
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'badge',
			array(
				'label'   => __( 'Badge', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'icon' => array(
						'title' => __( 'Icon', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-star',
					),
					'text' => array(
						'title' => __( 'Text', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text',
					),
				),
			)
		);

		$repeater->add_control(
			'badge_icon',
			array(
				'label'                  => __( 'Badge Icon', 'responsive-addons-for-elementor' ),
				'label_block'            => false,
				'type'                   => Controls_Manager::ICONS,
				'skin'                   => 'inline',
				'exclude_inline_options' => array( 'svg' ),
				'condition'              => array(
					'badge' => 'icon',
				),
			)
		);

		$repeater->add_control(
			'badge_icon_title',
			array(
				'label'       => __( 'Badge Icon Hover Title', 'responsive-addons-for-elementor' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'badge'              => 'icon',
					'badge_icon[value]!' => '',
				),
			)
		);

		$repeater->add_control(
			'badge_text',
			array(
				'label'       => __( 'Badge Text', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'badge' => 'text',
				),
			)
		);

		$repeater->add_control(
			'item_description',
			array(
				'label'   => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => '',
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'image',
			array(
				'label'   => __( 'Image', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'   => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '#' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'price_list',
			array(
				'label'       => __( 'List Items', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'title'             => __( 'First item on the list', 'responsive-addons-for-elementor' ),
						'item_description'  => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'responsive-addons-for-elementor' ),
						'price'             => '$20',
						'offering_discount' => '',
						'original_price'    => '$35',
						'link'              => array( 'url' => '#' ),
					),
					array(
						'title'             => __( 'Second item on the list', 'responsive-addons-for-elementor' ),
						'item_description'  => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'responsive-addons-for-elementor' ),
						'price'             => '$9',
						'offering_discount' => '',
						'original_price'    => '$12',
						'link'              => array( 'url' => '#' ),
					),
					array(
						'title'             => __( 'Third item on the list', 'responsive-addons-for-elementor' ),
						'item_description'  => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'responsive-addons-for-elementor' ),
						'price'             => '$32',
						'offering_discount' => '',
						'original_price'    => '$40',
						'link'              => array( 'url' => '#' ),
					),
				),
				'title_field' => '{{{ title }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_list_style',
			array(
				'label' => __( 'List', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading__title',
			array(
				'label' => __( 'Title & Price', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .rael-price-list-header',
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-price-list-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-price-list .rael-price-list-price' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'heading_color_hover',
			array(
				'label'     => __( 'Title Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-price-list-title:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'badge_heading',
			array(
				'label'     => __( 'Badge', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'badge_space',
			array(
				'label'      => __( 'Space', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-list .rael-price-list-badge-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-price-list .rael-price-list-badge-text' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'name'     => 'badge_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'exclude'  => array(
					'letter_spacing',
				),
				'selector' => '{{WRAPPER}} .rael-price-list .rael-price-list-badge-icon, {{WRAPPER}} .rael-price-list .rael-price-list-badge-text',
			)
		);

		$this->add_control(
			'badge_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-price-list .rael-price-list-badge-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-price-list .rael-price-list-badge-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'badge_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-price-list .rael-price-list-badge-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-price-list .rael-price-list-badge-text' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'badge_border',
				'selector' => '{{WRAPPER}} .rael-price-list .rael-price-list-badge-icon, {{WRAPPER}} .rael-price-list .rael-price-list-badge-text',
			)
		);

		$this->add_responsive_control(
			'badge_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-list .rael-price-list-badge-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rael-price-list .rael-price-list-badge-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'badge_box_shadow',
				'exclude'  => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} .rael-price-list .rael-price-list-badge-icon, {{WRAPPER}} .rael-price-list .rael-price-list-badge-text',
			)
		);

		$this->add_responsive_control(
			'badge_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-list .rael-price-list-badge-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rael-price-list .rael-price-list-badge-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'original_price',
			array(
				'label'     => __( 'Old Price', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'original_price_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-price-list-original-price' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'original_price_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-price-list-original-price',
			)
		);

		$this->add_control(
			'heading_item_description',
			array(
				'label'     => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'   => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-price-list-description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-price-list-description',
			)
		);

		$this->add_control(
			'heading_separator',
			array(
				'label'     => __( 'Separator', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'separator_style',
			array(
				'label'       => __( 'Style', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'solid'  => __( 'Solid', 'responsive-addons-for-elementor' ),
					'dotted' => __( 'Dotted', 'responsive-addons-for-elementor' ),
					'dashed' => __( 'Dashed', 'responsive-addons-for-elementor' ),
					'double' => __( 'Double', 'responsive-addons-for-elementor' ),
					'none'   => __( 'None', 'responsive-addons-for-elementor' ),
				),
				'default'     => 'dotted',
				'render_type' => 'template',
				'selectors'   => array(
					'{{WRAPPER}} .rael-price-list-separator' => 'border-bottom-style: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'separator_weight',
			array(
				'label'     => __( 'Weight', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 10,
					),
				),
				'condition' => array(
					'separator_style!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-list-separator' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				),
				'default'   => array(
					'size' => 2,
				),
			)
		);

		$this->add_control(
			'separator_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-price-list-separator' => 'border-bottom-color: {{VALUE}};',
				),
				'condition' => array(
					'separator_style!' => 'none',
				),
			)
		);

		$this->add_control(
			'separator_spacing',
			array(
				'label'     => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 40,
					),
				),
				'condition' => array(
					'separator_style!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-list-separator' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			array(
				'label'      => __( 'Image', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'image_size',
				'default' => 'thumbnail',
			)
		);

		$this->add_control(
			'border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-list-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'image_spacing',
			array(
				'label'     => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'body.rtl {{WRAPPER}} .rael-price-list-image' => 'padding-left: calc({{SIZE}}{{UNIT}}/2);',
					'body.rtl {{WRAPPER}} .rael-price-list-image + .rael-price-list-text' => 'padding-right: calc({{SIZE}}{{UNIT}}/2);',
					'body:not(.rtl) {{WRAPPER}} .rael-price-list-image' => 'padding-right: calc({{SIZE}}{{UNIT}}/2);',
					'body:not(.rtl) {{WRAPPER}} .rael-price-list-image + .rael-price-list-text' => 'padding-left: calc({{SIZE}}{{UNIT}}/2);',
				),
				'default'   => array(
					'size' => 20,
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_item_style',
			array(
				'label'      => __( 'Item', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'row_gap',
			array(
				'label'      => __( 'Rows Gap', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'max' => 50,
					),
					'em' => array(
						'max'  => 5,
						'step' => 0.1,
					),
				),
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-list li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'default'    => array(
					'size' => 20,
				),
			)
		);

		$this->add_control(
			'vertical_align',
			array(
				'label'                => __( 'Vertical Align', 'responsive-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'top'    => array(
						'title' => __( 'Top', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'            => array(
					'{{WRAPPER}} .rael-price-list-item' => 'align-items: {{VALUE}};',
				),
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'bottom' => 'flex-end',
				),
				'default'              => 'top',
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Render the image for a specific item.
	 *
	 * @param array $item     The item data containing information about the image.
	 * @param array $instance The instance data containing settings for image rendering.
	 *
	 * @return string HTML markup for the rendered image.
	 */
	private function render_image( $item, $instance ) {
		$image_id   = $item['image']['id'];
		$image_size = $instance['image_size_size'];
		if ( 'custom' === $image_size ) {
			$image_src = Group_Control_Image_Size::get_attachment_image_src( $image_id, 'image_size', $instance );
		} else {
			$image_src = wp_get_attachment_image_src( $image_id, $image_size );
			$image_src = $image_src[0];
		}

		return sprintf( '<img src="%s" alt="%s" />', $image_src, $item['title'] );
	}
	/**
	 * Render the header markup for a specific item in the price list.
	 *
	 * @param array $item The item data containing information about the header.
	 *
	 * @return string HTML markup for the item header.
	 */
	private function render_item_header( $item ) {
		$url = $item['link']['url'];

		$item_id = $item['_id'];

		if ( $url ) {
			$unique_link_id = 'item-link-' . $item_id;

			$this->add_render_attribute( $unique_link_id, 'class', 'rael-price-list-item' );

			$this->add_link_attributes( $unique_link_id, $item['link'] );

			return '<li><a ' . $this->get_render_attribute_string( $unique_link_id ) . '>';
		} else {
			return '<li class="rael-price-list-item">';
		}
	}
	/**
	 * Render the footer markup for a specific item in the price list.
	 *
	 * @param array $item The item data containing information about the footer.
	 *
	 * @return string HTML markup for the item footer.
	 */
	private function render_item_footer( $item ) {
		if ( $item['link']['url'] ) {
			return '</a></li>';
		} else {
			return '</li>';
		}
	}
	/**
	 * Render the entire Price List widget.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display(); ?>

		<ul class="rael-price-list">

			<?php foreach ( $settings['price_list'] as $index => $item ) : ?>
				<?php
				if ( ! empty( $item['title'] ) || ! empty( $item['price'] ) || ! empty( $item['item_description'] ) ) :
					$title_repeater_setting_key       = $this->get_repeater_setting_key( 'title', 'price_list', $index );
					$description_repeater_setting_key = $this->get_repeater_setting_key( 'item_description', 'price_list', $index );
					$this->add_inline_editing_attributes( $title_repeater_setting_key );
					$this->add_inline_editing_attributes( $description_repeater_setting_key );
					$this->add_render_attribute( $title_repeater_setting_key, 'class', 'rael-price-list-title' );
					$this->add_render_attribute( $description_repeater_setting_key, 'class', 'rael-price-list-description' );
					?>
					<?php echo wp_kses_post( $this->render_item_header( $item ) ); ?>
					<?php if ( ! empty( $item['image']['url'] ) ) : ?>
					<div class="rael-price-list-image">
						<?php echo wp_kses_post( $this->render_image( $item, $settings ) ); ?>
					</div>
				<?php endif; ?>

					<div class="rael-price-list-text">
						<?php if ( ! empty( $item['title'] ) || ! empty( $item['price'] ) ) : ?>
							<div class="rael-price-list-header">
								<?php if ( ! empty( $item['title'] ) ) : ?>
									<div class="rael-price-list-title-wrapper">
										<span <?php echo wp_kses_post( $this->get_render_attribute_string( $title_repeater_setting_key ) ); ?>><?php echo esc_html( $item['title'] ); ?></span>
										<?php
										if ( 'icon' === $item['badge'] && $item['badge_icon']['value'] ) {
											$icon = sprintf( '<i class="%1$s" aria-hidden="true" title="%2$s"></i>', $item['badge_icon']['value'], $item['badge_icon_title'] );
											echo sprintf( '<span class="rael-price-list-badge-icon">%s</span>', wp_kses_post( $icon ) );
										} elseif ( 'text' === $item['badge'] && $item['badge_text'] ) {
											echo sprintf( '<span class="rael-price-list-badge-text">%s</span>', esc_html( $item['badge_text'] ) );
										}
										?>
									</div>
								<?php endif; ?>
								<?php if ( 'none' !== $settings['separator_style'] ) : ?>
									<span class="rael-price-list-separator"></span>
								<?php endif; ?>
								<div class="rael-price-list-price-wrapper">
									<?php if ( 'yes' === $item['offering_discount'] ) : ?>
										<span class="rael-price-list-original-price"><?php echo esc_html( $item['original_price'] ); ?></span>
									<?php endif; ?>
									<?php if ( ! empty( $item['price'] ) ) : ?>
										<span class="rael-price-list-price"><?php echo esc_html( $item['price'] ); ?></span>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $item['item_description'] ) ) : ?>
							<p <?php echo wp_kses_post( $this->get_render_attribute_string( $description_repeater_setting_key ) ); ?>><?php echo wp_kses_post( $item['item_description'] ); ?></p>
						<?php endif; ?>
					</div>
					<?php echo wp_kses_post( $this->render_item_footer( $item ) ); ?>
				<?php endif; ?>
			<?php endforeach; ?>

		</ul>

		<?php
	}

	/**
	 * Render Price List widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<ul class="rael-price-list">
			<#
			for ( var i in settings.price_list ) {
			var item = settings.price_list[i],
			item_open_wrap = '<li class="rael-price-list-item">',
				item_close_wrap = '</li>';
			if ( item.link.url ) {
			item_open_wrap = '<li><a href="' + item.link.url + '" class="rael-price-list-item">';
					item_close_wrap = '</a></li>';
			}

			if ( ! _.isEmpty( item.title ) || ! _.isEmpty( item.price ) || ! _.isEmpty( item.description ) || ! _.isEmpty( item.image ) ) { #>

			{{{ item_open_wrap }}}
			<# if ( item.image && item.image.id ) {

			var image = {
			id: item.image.id,
			url: item.image.url,
			size: settings.image_size_size,
			dimension: settings.image_size_custom_dimension,
			model: view.getEditModel()
			};

			var image_url = elementor.imagesManager.getImageUrl( image );

			if ( image_url ) { #>
			<div class="rael-price-list-image"><img src="{{ image_url }}" alt="{{ item.title }}"></div>
			<# } #>

			<# } #>


			<# if ( ! _.isEmpty( item.title ) || ! _.isEmpty( item.price ) || ! _.isEmpty( item.item_description ) ) { #>
			<div class="rael-price-list-text">

				<# if ( ! _.isEmpty( item.title ) || ! _.isEmpty( item.price ) ) { #>
				<div class="rael-price-list-header">

					<# if ( ! _.isEmpty( item.title ) ) { #>
					<span class="rael-price-list-title">{{{ item.title }}}</span>
					<# } #>

					<# if ( 'icon' == item.badge && ! _.isEmpty(item.badge_icon.value) ) { #>
					<span class="rael-price-list-badge-icon">
						<i class="{{{item.badge_icon.value}}}" title="{{{item.badge_icon_text}}}"></i>
					</span>
					<# } else if ( 'text' == item.badge && ! _.isEmpty(item.badge_text) ) {#>
						<span class="rael-price-list-badge-text">{{{ item.badge_text }}}</span>
					<# } #>

					<# if ( 'none' != settings.separator_style ) { #>
					<span class="rael-price-list-separator"></span>
					<# } #>

					<# if ( 'yes' == item.offering_discount) { #>
					<span class="rael-price-list-original-price">{{{ item.original_price }}}</span>
					<# } #>

					<# if ( ! _.isEmpty( item.price ) ) { #>
					<span class="rael-price-list-price">{{{ item.price }}}</span>
					<# } #>

				</div>
				<# } #>

				<# if ( ! _.isEmpty( item.item_description ) ) { #>
				<p class="rael-price-list-description">{{{ item.item_description }}}</p>
				<# } #>

			</div>
			<# } #>

			{{{ item_close_wrap }}}

			<# } #>
			<# } #>
		</ul>
		<?php
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/price-list';
	}
}
