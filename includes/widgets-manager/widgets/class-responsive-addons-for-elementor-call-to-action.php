<?php
/**
 * Call to Action Widget
 *
 * @since   1.0.0
 * @package responsive-addons-for-elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Call to Action Widget
 *
 * @since   1.0.0
 * @package responsive-addons-for-elementor
 * @author  CyberChimps <support@cyberchimps.com>
 */
class Responsive_Addons_For_Elementor_Call_To_Action extends Widget_Base {


	/**
	 * Retrieve the widget name.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-call-to-action';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Call To Action', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Pricing Table widget icon.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-image-rollover rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the pricing table widget belongs to.
	 *
	 * @since  1.0.0
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
	 * @since  1.0.0
	 * @access public
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_main_image',
			array(
				'label' => __( 'Image', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'skin',
			array(
				'label'        => __( 'Skin', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'classic' => __( 'Classic', 'responsive-addons-for-elementor' ),
					'cover'   => __( 'Cover', 'responsive-addons-for-elementor' ),
				),
				'render_type'  => 'template',
				'prefix_class' => 'responsive-cta--skin-',
				'default'      => 'classic',
			)
		);

		$this->add_responsive_control(
			'layout',
			array(
				'label'        => __( 'Position', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'  => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'above' => array(
						'title' => __( 'Above', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'right' => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'prefix_class' => 'responsive-cta-%s-layout-image-',
				'condition'    => array(
					'skin!' => 'cover',
				),
			)
		);

		$this->add_control(
			'bg_image',
			array(
				'label'   => __( 'Choose Image', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array(
					'active' => true,
				),
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'bg_image', // Actually its `image_size`.
				'label'     => __( 'Image Resolution', 'responsive-addons-for-elementor' ),
				'default'   => 'large',
				'condition' => array(
					'bg_image[id]!' => '',
				),
				'separator' => 'none',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'graphic_element',
			array(
				'label'   => __( 'Graphic Element', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'none'  => array(
						'title' => __( 'None', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-ban',
					),
					'image' => array(
						'title' => __( 'Image', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-image-bold',
					),
					'icon'  => array(
						'title' => __( 'Icon', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-star',
					),
				),
				'default' => 'none',
			)
		);

		$this->add_control(
			'graphic_image',
			array(
				'label'      => __( 'Choose Image', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::MEDIA,
				'dynamic'    => array(
					'active' => true,
				),
				'default'    => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition'  => array(
					'graphic_element' => 'image',
				),
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'graphic_image', // Actually its `image_size`.
				'default'   => 'thumbnail',
				'condition' => array(
					'graphic_element'    => 'image',
					'graphic_image[id]!' => '',
				),
			)
		);

		$this->add_control(
			'selected_icon',
			array(
				'label'            => __( 'Icon', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				),
				'condition'        => array(
					'graphic_element' => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_view',
			array(
				'label'     => __( 'View', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'default' => __( 'Default', 'responsive-addons-for-elementor' ),
					'stacked' => __( 'Stacked', 'responsive-addons-for-elementor' ),
					'framed'  => __( 'Framed', 'responsive-addons-for-elementor' ),
				),
				'default'   => 'default',
				'condition' => array(
					'graphic_element' => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_shape',
			array(
				'label'     => __( 'Shape', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'circle' => __( 'Circle', 'responsive-addons-for-elementor' ),
					'square' => __( 'Square', 'responsive-addons-for-elementor' ),
				),
				'default'   => 'circle',
				'condition' => array(
					'icon_view!'      => 'default',
					'graphic_element' => 'icon',
				),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => __( 'This is the heading', 'responsive-addons-for-elementor' ),
				'placeholder' => __( 'Enter your title', 'responsive-addons-for-elementor' ),
				'label_block' => true,
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'description',
			array(
				'label'       => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'responsive-addons-for-elementor' ),
				'placeholder' => __( 'Enter your description', 'responsive-addons-for-elementor' ),
				'separator'   => 'none',
				'rows'        => 5,
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'     => __( 'Title HTML Tag', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
				),
				'default'   => 'h2',
				'condition' => array(
					'title!' => '',
				),
			)
		);

		$this->add_control(
			'button',
			array(
				'label'     => __( 'Button Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => __( 'Click Here', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => __( 'https://your-link.com', 'responsive-addons-for-elementor' ),

			)
		);

		$this->add_control(
			'link_click',
			array(
				'label'     => __( 'Apply Link On', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'box'    => __( 'Whole Box', 'responsive-addons-for-elementor' ),
					'button' => __( 'Button Only', 'responsive-addons-for-elementor' ),
				),
				'default'   => 'button',
				'separator' => 'none',
				'condition' => array(
					'link[url]!' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_ribbon',
			array(
				'label' => __( 'Ribbon', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'ribbon_title',
			array(
				'label'   => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'ribbon_horizontal_position',
			array(
				'label'     => __( 'Position', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'  => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'condition' => array(
					'ribbon_title!' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'box_style',
			array(
				'label' => __( 'Box', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'box_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'min-height',
			array(
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
					'vh' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'size_units' => array( 'px', 'vh' ),
				'selectors'  => array(
					'{{WRAPPER}} .responsive-cta__content' => 'min-height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'label'     => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta__content' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'vertical_position',
			array(
				'label'        => __( 'Vertical Position', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'top'    => array(
						'title' => __( 'Top', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'prefix_class' => 'responsive-cta--valign-',
				'separator'    => 'none',
			)
		);

		$this->add_responsive_control(
			'padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .responsive-cta__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'heading_bg_image_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Image', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'bg_image[url]!' => '',
					'skin'           => 'classic',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'image_min_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .responsive-cta__bg-wrapper' => 'min-width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'skin'    => 'classic',
					'layout!' => 'above',
				),
			)
		);

		$this->add_responsive_control(
			'image_min_height',
			array(
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
					'vh' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'size_units' => array( 'px', 'vh' ),

				'selectors'  => array(
					'{{WRAPPER}} .responsive-cta__bg-wrapper' => 'min-height: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'skin' => 'classic',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'graphic_element_style',
			array(
				'label'     => __( 'Graphic Element', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'graphic_element!' => array(
						'none',
						'',
					),
				),
			)
		);

		$this->add_control(
			'graphic_image_spacing',
			array(
				'label'     => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta__image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'graphic_element' => 'image',
				),
			)
		);

		$this->add_control(
			'graphic_image_width',
			array(
				'label'      => __( 'Size', 'responsive-addons-for-elementor' ) . ' (%)',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => '%',
				),
				'range'      => array(
					'%' => array(
						'min' => 5,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .responsive-cta__image img' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'graphic_element' => 'image',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'graphic_image_border',
				'selector'  => '{{WRAPPER}} .responsive-cta__image img',
				'condition' => array(
					'graphic_element' => 'image',
				),
			)
		);

		$this->add_control(
			'graphic_image_border_radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta__image img' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'graphic_element' => 'image',
				),
			)
		);

		$this->add_control(
			'icon_spacing',
			array(
				'label'     => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'graphic_element' => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_primary_color',
			array(
				'label'     => __( 'Primary Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .elementor-view-stacked .elementor-icon svg' => 'stroke: {{VALUE}}',
					'{{WRAPPER}} .elementor-view-framed .elementor-icon, {{WRAPPER}} .elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}}',
					'{{WRAPPER}} .elementor-view-framed .elementor-icon, {{WRAPPER}} .elementor-view-default .elementor-icon svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'graphic_element' => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_secondary_color',
			array(
				'label'     => __( 'Secondary Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'graphic_element' => 'icon',
					'icon_view!'      => 'default',
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-view-framed .elementor-icon svg' => 'stroke: {{VALUE}};',
					'{{WRAPPER}} .elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-view-stacked .elementor-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_size',
			array(
				'label'     => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'graphic_element' => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_padding',
			array(
				'label'     => __( 'Icon Padding', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
				),
				'range'     => array(
					'em' => array(
						'min' => 0,
						'max' => 5,
					),
				),
				'condition' => array(
					'graphic_element' => 'icon',
					'icon_view!'      => 'default',
				),
			)
		);

		$this->add_control(
			'icon_border_width',
			array(
				'label'     => __( 'Border Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon' => 'border-width: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'graphic_element' => 'icon',
					'icon_view'       => 'framed',
				),
			)
		);

		$this->add_control(
			'icon_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'graphic_element' => 'icon',
					'icon_view!'      => 'default',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			array(
				'label'      => __( 'Content', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'title',
							'operator' => '!=',
							'value'    => '',
						),
						array(
							'name'     => 'description',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
			)
		);

		$this->add_control(
			'heading_style_title',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Title', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'title!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'title_typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'  => '{{WRAPPER}} .responsive-cta__title',
				'condition' => array(
					'title!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'title_spacing',
			array(
				'label'     => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta__title:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'title!' => '',
				),
			)
		);

		$this->add_control(
			'heading_style_description',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Description', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
				'condition' => array(
					'description!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'description_typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector'  => '{{WRAPPER}} .responsive-cta__description',
				'condition' => array(
					'description!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'description_spacing',
			array(
				'label'     => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta__description:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'description!' => '',
				),
			)
		);

		$this->add_control(
			'heading_content_colors',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Colors', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'color_tabs' );

		$this->start_controls_tab(
			'colors_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'content_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta__content' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'skin' => 'classic',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Title Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta__title' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'title!' => '',
				),
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Description Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta__description' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'description!' => '',
				),
			)
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => __( 'Button Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta__button' => 'color: {{VALUE}}; border-color: {{VALUE}}',
				),
				'condition' => array(
					'button!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'colors_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'content_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta:hover .responsive-cta__content' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'skin' => 'classic',
				),
			)
		);

		$this->add_control(
			'title_color_hover',
			array(
				'label'     => __( 'Title Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta:hover .responsive-cta__title' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'title!' => '',
				),
			)
		);

		$this->add_control(
			'description_color_hover',
			array(
				'label'     => __( 'Description Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta:hover .responsive-cta__description' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'description!' => '',
				),
			)
		);

		$this->add_control(
			'button_color_hover',
			array(
				'label'     => __( 'Button Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta:hover .responsive-cta__button' => 'color: {{VALUE}}; border-color: {{VALUE}}',
				),
				'condition' => array(
					'button!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'button_style',
			array(
				'label'     => __( 'Button', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'button!' => '',
				),
			)
		);

		$this->add_control(
			'button_size',
			array(
				'label'   => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => array(
					'xs' => __( 'Extra Small', 'responsive-addons-for-elementor' ),
					'sm' => __( 'Small', 'responsive-addons-for-elementor' ),
					'md' => __( 'Medium', 'responsive-addons-for-elementor' ),
					'lg' => __( 'Large', 'responsive-addons-for-elementor' ),
					'xl' => __( 'Extra Large', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .responsive-cta__button',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
			)
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab(
			'button_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta .responsive-cta__button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta .responsive-cta__button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta .responsive-cta__button' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button-hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'button_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta .responsive-cta__button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta .responsive-cta__button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta .responsive-cta__button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_border_width',
			array(
				'label'     => __( 'Border Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta__button' => 'border-width: {{SIZE}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta__button' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_ribbon_style',
			array(
				'label'      => __( 'Ribbon', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => array(
					'ribbon_title!' => '',
				),
			)
		);

		$this->add_control(
			'ribbon_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_ACCENT,
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-ribbon-inner' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'ribbon_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-ribbon-inner' => 'color: {{VALUE}}',
				),
			)
		);

		$ribbon_distance_transform = is_rtl() ? 'translateY(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)' : 'translateY(-50%) translateX(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)';

		$this->add_responsive_control(
			'ribbon_distance',
			array(
				'label'     => __( 'Distance', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-ribbon-inner' => 'margin-top: {{SIZE}}{{UNIT}}; transform: ' . $ribbon_distance_transform,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'ribbon_typography',
				'selector' => '{{WRAPPER}} .elementor-ribbon-inner',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .elementor-ribbon-inner',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'hover_effects',
			array(
				'label' => __( 'Hover Effects', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'content_hover_heading',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Content', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'skin' => 'cover',
				),
			)
		);

		$this->add_control(
			'content_animation',
			array(
				'label'     => __( 'Hover Animation', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'groups'    => array(
					array(
						'label'   => __( 'None', 'responsive-addons-for-elementor' ),
						'options' => array(
							'' => __( 'None', 'responsive-addons-for-elementor' ),
						),
					),
					array(
						'label'   => __( 'Entrance', 'responsive-addons-for-elementor' ),
						'options' => array(
							'enter-from-right'  => 'Slide In Right',
							'enter-from-left'   => 'Slide In Left',
							'enter-from-top'    => 'Slide In Up',
							'enter-from-bottom' => 'Slide In Down',
							'enter-zoom-in'     => 'Zoom In',
							'enter-zoom-out'    => 'Zoom Out',
							'fade-in'           => 'Fade In',
						),
					),
					array(
						'label'   => __( 'Reaction', 'responsive-addons-for-elementor' ),
						'options' => array(
							'grow'       => 'Grow',
							'shrink'     => 'Shrink',
							'move-right' => 'Move Right',
							'move-left'  => 'Move Left',
							'move-up'    => 'Move Up',
							'move-down'  => 'Move Down',
						),
					),
					array(
						'label'   => __( 'Exit', 'responsive-addons-for-elementor' ),
						'options' => array(
							'exit-to-right'  => 'Slide Out Right',
							'exit-to-left'   => 'Slide Out Left',
							'exit-to-top'    => 'Slide Out Up',
							'exit-to-bottom' => 'Slide Out Down',
							'exit-zoom-in'   => 'Zoom In',
							'exit-zoom-out'  => 'Zoom Out',
							'fade-out'       => 'Fade Out',
						),
					),
				),
				'default'   => 'grow',
				'condition' => array(
					'skin' => 'cover',
				),
			)
		);

		/*
		*
		* Add class 'elementor-animated-content' to widget when assigned content animation
		*
		*/
		$this->add_control(
			'animation_class',
			array(
				'label'        => __( 'Animation', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::HIDDEN,
				'default'      => 'animated-content',
				'prefix_class' => 'elementor-',
				'condition'    => array(
					'content_animation!' => '',
				),
			)
		);

		$this->add_control(
			'content_animation_duration',
			array(
				'label'       => __( 'Animation Duration', 'responsive-addons-for-elementor' ) . ' (ms)',
				'type'        => Controls_Manager::SLIDER,
				'render_type' => 'template',
				'default'     => array(
					'size' => 1000,
				),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 3000,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .responsive-cta__content-item' => 'transition-duration: {{SIZE}}ms',
					'{{WRAPPER}}.responsive-cta--sequenced-animation .responsive-cta__content-item:nth-child(2)' => 'transition-delay: calc( {{SIZE}}ms / 3 )',
					'{{WRAPPER}}.responsive-cta--sequenced-animation .responsive-cta__content-item:nth-child(3)' => 'transition-delay: calc( ( {{SIZE}}ms / 3 ) * 2 )',
					'{{WRAPPER}}.responsive-cta--sequenced-animation .responsive-cta__content-item:nth-child(4)' => 'transition-delay: calc( ( {{SIZE}}ms / 3 ) * 3 )',
				),
				'condition'   => array(
					'content_animation!' => '',
					'skin'               => 'cover',
				),
			)
		);

		$this->add_control(
			'sequenced_animation',
			array(
				'label'        => __( 'Sequenced Animation', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Off', 'responsive-addons-for-elementor' ),
				'return_value' => 'responsive-cta--sequenced-animation',
				'prefix_class' => '',
				'condition'    => array(
					'content_animation!' => '',

				),
			)
		);

		$this->add_control(
			'background_hover_heading',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Background', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
				'condition' => array(
					'skin' => 'cover',
				),
			)
		);

		$this->add_control(
			'transformation',
			array(
				'label'        => __( 'Hover Animation', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					''           => 'None',
					'zoom-in'    => 'Zoom In',
					'zoom-out'   => 'Zoom Out',
					'move-left'  => 'Move Left',
					'move-right' => 'Move Right',
					'move-up'    => 'Move Up',
					'move-down'  => 'Move Down',
				),
				'default'      => 'zoom-in',
				'prefix_class' => 'elementor-bg-transform elementor-bg-transform-',
			)
		);

		$this->start_controls_tabs( 'bg_effects_tabs' );

		$this->start_controls_tab(
			'normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'overlay_color',
			array(
				'label'     => __( 'Overlay Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta:not(:hover) .responsive-cta__bg-overlay' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'bg_filters',
				'selector' => '{{WRAPPER}} .responsive-cta__bg',
			)
		);

		$this->add_control(
			'overlay_blend_mode',
			array(
				'label'     => __( 'Blend Mode', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''            => __( 'Normal', 'responsive-addons-for-elementor' ),
					'multiply'    => 'Multiply',
					'screen'      => 'Screen',
					'overlay'     => 'Overlay',
					'darken'      => 'Darken',
					'lighten'     => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'color-burn'  => 'Color Burn',
					'hue'         => 'Hue',
					'saturation'  => 'Saturation',
					'color'       => 'Color',
					'exclusion'   => 'Exclusion',
					'luminosity'  => 'Luminosity',
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta__bg-overlay' => 'mix-blend-mode: {{VALUE}}',
				),
				'separator' => 'none',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'overlay_color_hover',
			array(
				'label'     => __( 'Overlay Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-cta:hover .responsive-cta__bg-overlay' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'bg_filters_hover',
				'selector' => '{{WRAPPER}} .responsive-cta:hover .responsive-cta__bg',
			)
		);

		$this->add_control(
			'effect_duration',
			array(
				'label'       => __( 'Transition Duration', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'render_type' => 'template',
				'default'     => array(
					'size' => 1500,
				),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 3000,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .responsive-cta .responsive-cta__bg, {{WRAPPER}} .responsive-cta .responsive-cta__bg-overlay' => 'transition-duration: {{SIZE}}ms',
				),
				'separator'   => 'before',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Render Call to Action widget output in the frontend.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$title_tag         = Helper::validate_html_tags( $settings['title_tag'] );
		$wrapper_tag       = 'div';
		$button_tag        = 'a';
		$bg_image          = '';
		$content_animation = $settings['content_animation'];
		$animation_class   = '';
		$print_bg          = true;
		$print_content     = true;

		if ( ! empty( $settings['bg_image']['id'] ) ) {
			$bg_image = Group_Control_Image_Size::get_attachment_image_src( $settings['bg_image']['id'], 'bg_image', $settings );
		} elseif ( ! empty( $settings['bg_image']['url'] ) ) {
			$bg_image = $settings['bg_image']['url'];
		}

		if ( empty( $bg_image ) && 'classic' === $settings['skin'] ) {
			$print_bg = false;
		}

		if ( empty( $settings['title'] ) && empty( $settings['description'] ) && empty( $settings['button'] ) && 'none' === $settings['graphic_element'] ) {
			$print_content = false;
		}

		$this->add_render_attribute(
			'background_image',
			'style',
			array(
				'background-image: url(' . $bg_image . ');',
			)
		);

		$this->add_render_attribute(
			'title',
			'class',
			array(
				'responsive-cta__title',
				'responsive-cta__content-item',
				'elementor-content-item',
			)
		);

		$this->add_render_attribute(
			'description',
			'class',
			array(
				'responsive-cta__description',
				'responsive-cta__content-item',
				'elementor-content-item',
			)
		);

		$this->add_render_attribute(
			'button',
			'class',
			array(
				'responsive-cta__button',
				'elementor-button',
				'elementor-size-' . $settings['button_size'],
			)
		);

		$this->add_render_attribute(
			'graphic_element',
			'class',
			array(
				'elementor-content-item',
				'responsive-cta__content-item',
			)
		);

		if ( 'icon' === $settings['graphic_element'] ) {
			$this->add_render_attribute(
				'graphic_element',
				'class',
				array(
					'elementor-icon-wrapper',
					'responsive-cta__icon',
				)
			);
			$this->add_render_attribute( 'graphic_element', 'class', 'elementor-view-' . $settings['icon_view'] );
			if ( 'default' !== $settings['icon_view'] ) {
					$this->add_render_attribute( 'graphic_element', 'class', 'elementor-shape-' . $settings['icon_shape'] );
			}

			if ( ! isset( $settings['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
				// add old default.
				$settings['icon'] = 'fa fa-star';
			}

			if ( ! empty( $settings['icon'] ) ) {
				$this->add_render_attribute( 'icon', 'class', $settings['icon'] );
			}
		} elseif ( 'image' === $settings['graphic_element'] && ! empty( $settings['graphic_image']['url'] ) ) {
			$this->add_render_attribute( 'graphic_element', 'class', 'responsive-cta__image' );
		}

		if ( ! empty( $content_animation ) && 'cover' === $settings['skin'] ) {

			$animation_class = 'elementor-animated-item--' . $content_animation;

			$this->add_render_attribute( 'title', 'class', $animation_class );

			$this->add_render_attribute( 'graphic_element', 'class', $animation_class );

			$this->add_render_attribute( 'description', 'class', $animation_class );

		}

		if ( ! empty( $settings['link']['url'] ) ) {
			$link_element = 'button';

			if ( 'box' === $settings['link_click'] ) {
				$wrapper_tag  = 'a';
				$button_tag   = 'span';
				$link_element = 'wrapper';
			}

			$this->add_link_attributes( $link_element, $settings['link'] );
		}

		$this->add_inline_editing_attributes( 'title' );
		$this->add_inline_editing_attributes( 'description' );
		$this->add_inline_editing_attributes( 'button' );

		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

		?>
		<<?php echo esc_attr( $wrapper_tag ) . ' ' . wp_kses_post( $this->get_render_attribute_string( 'wrapper' ) ); ?> class="responsive-cta"> 
		<?php if ( $print_bg ) : ?>
			<div class="responsive-cta__bg-wrapper">
				<div class="responsive-cta__bg elementor-bg" <?php echo wp_kses_post( $this->get_render_attribute_string( 'background_image' ) ); ?>></div>
				<div class="responsive-cta__bg-overlay"></div>
			</div>
		<?php endif; ?>
		<?php if ( $print_content ) : ?>
			<div class="responsive-cta__content">
			<?php if ( 'image' === $settings['graphic_element'] && ! empty( $settings['graphic_image']['url'] ) ) : ?>
				<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'graphic_element' ) ); ?>>
				<?php echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'graphic_image' ) ); ?>
				</div>
			<?php elseif ( 'icon' === $settings['graphic_element'] && ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon'] ) ) ) : ?>
				<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'graphic_element' ) ); ?>>
					<div class="elementor-icon">
				<?php
				if ( $is_new || $migrated ) :
					Icons_Manager::render_icon( $settings['selected_icon'], array( 'aria-hidden' => 'true' ) );
						else :
							?>
							<i <?php echo wp_kses_post( $this->get_render_attribute_string( 'icon' ) ); ?>></i>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $settings['title'] ) ) : ?>
				<<?php echo esc_attr( Helper::validate_html_tags( $title_tag ) ) . ' ' . wp_kses_post( $this->get_render_attribute_string( 'title' ) ); ?>>
				<?php echo esc_html( $settings['title'] ); ?>
				</<?php echo esc_attr( Helper::validate_html_tags( $title_tag ) ); ?>>
			<?php endif; ?>

			<?php if ( ! empty( $settings['description'] ) ) : ?>
				<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'description' ) ); ?>>
				<?php echo esc_html( $settings['description'] ); ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $settings['button'] ) ) : ?>
			<div class="responsive-cta__button-wrapper responsive-cta__content-item elementor-content-item <?php echo esc_attr( $animation_class ); ?>">
				<<?php echo esc_attr( $button_tag ) . ' ' . wp_kses_post( $this->get_render_attribute_string( 'button' ) ); ?>>
				<?php echo esc_html( $settings['button'] ); ?>
				</<?php echo esc_attr( $button_tag ); ?>>
				</div>
			<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php
		if ( ! empty( $settings['ribbon_title'] ) ) :
			$this->add_render_attribute( 'ribbon-wrapper', 'class', 'elementor-ribbon' );

			if ( ! empty( $settings['ribbon_horizontal_position'] ) ) {
				$this->add_render_attribute( 'ribbon-wrapper', 'class', 'elementor-ribbon-' . $settings['ribbon_horizontal_position'] );
			}
			?>
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'ribbon-wrapper' ) ); ?>>
				<div class="elementor-ribbon-inner"><?php echo esc_html( $settings['ribbon_title'] ); ?></div>
			</div>
		<?php endif; ?>
		</<?php echo esc_attr( $wrapper_tag ); ?>>
		<?php
	}

	/**
	 * Render Call to Action widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
		var wrapperTag = 'div',
		buttonTag = 'a',
		contentAnimation = settings.content_animation,
		animationClass,
		btnSizeClass = 'elementor-size-' + settings.button_size,
		printBg = true,
		printContent = true,
		iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
		migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' );

		if ( 'box' == settings.link_click ) {
		wrapperTag = 'a';
		buttonTag = 'span';
		view.addRenderAttribute( 'wrapper', 'href', '#' );
		}

		if ( '' != settings.bg_image.url ) {
		var bg_image = {
		id: settings.bg_image.id,
		url: settings.bg_image.url,
		size: settings.bg_image_size,
		dimension: settings.bg_image_custom_dimension,
		model: view.getEditModel()
		};

		var bgImageUrl = elementor.imagesManager.getImageUrl( bg_image );
		}

		if ( ! bg_image && 'classic' == settings.skin ) {
		printBg = false;
		}

		if ( ! settings.title && ! settings.description && ! settings.button && 'none' == settings.graphic_element ) {
		printContent = false;
		}

		if ( 'icon' == settings.graphic_element ) {
		var iconWrapperClasses = 'elementor-icon-wrapper';
		iconWrapperClasses += ' responsive-cta__image';
		iconWrapperClasses += ' elementor-view-' + settings.icon_view;
		if ( 'default' != settings.icon_view ) {
		iconWrapperClasses += ' elementor-shape-' + settings.icon_shape;
		}
		view.addRenderAttribute( 'graphic_element', 'class', iconWrapperClasses );

		} else if ( 'image' == settings.graphic_element && '' != settings.graphic_image.url ) {
		var image = {
		id: settings.graphic_image.id,
		url: settings.graphic_image.url,
		size: settings.graphic_image_size,
		dimension: settings.graphic_image_custom_dimension,
		model: view.getEditModel()
		};

		var imageUrl = elementor.imagesManager.getImageUrl( image );
		view.addRenderAttribute( 'graphic_element', 'class', 'responsive-cta__image' );
		}

		if ( contentAnimation && 'cover' == settings.skin ) {

		var animationClass = 'elementor-animated-item--' + contentAnimation;

		view.addRenderAttribute( 'title', 'class', animationClass );

		view.addRenderAttribute( 'description', 'class', animationClass );

		view.addRenderAttribute( 'graphic_element', 'class', animationClass );
		}

		view.addRenderAttribute( 'background_image', 'style', 'background-image: url(' + bgImageUrl + ');' );
		view.addRenderAttribute( 'title', 'class', [ 'responsive-cta__title', 'responsive-cta__content-item', 'elementor-content-item' ] );
		view.addRenderAttribute( 'description', 'class', [ 'responsive-cta__description', 'responsive-cta__content-item', 'elementor-content-item' ] );
		view.addRenderAttribute( 'button', 'class', [ 'responsive-cta__button', 'elementor-button', btnSizeClass ] );
		view.addRenderAttribute( 'graphic_element', 'class', [ 'responsive-cta__content-item', 'elementor-content-item' ] );


		view.addInlineEditingAttributes( 'title' );
		view.addInlineEditingAttributes( 'description' );
		view.addInlineEditingAttributes( 'button' );
		#>

		<{{ wrapperTag }} class="responsive-cta" {{{ view.getRenderAttributeString( 'wrapper' ) }}}>

		<# if ( printBg ) { #>
		<div class="responsive-cta__bg-wrapper">
			<div class="responsive-cta__bg elementor-bg" {{{ view.getRenderAttributeString( 'background_image' ) }}}></div>
		<div class="responsive-cta__bg-overlay"></div>
		</div>
		<# } #>
		<# if ( printContent ) { #>
		<div class="responsive-cta__content">
			<# if ( 'image' == settings.graphic_element && '' != settings.graphic_image.url ) { #>
			<div {{{ view.getRenderAttributeString( 'graphic_element' ) }}}>
			<img src="{{ imageUrl }}">
		</div>
		<#  } else if ( 'icon' == settings.graphic_element && ( settings.icon || settings.selected_icon ) ) { #>
		<div {{{ view.getRenderAttributeString( 'graphic_element' ) }}}>
		<div class="elementor-icon">
			<# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
			{{{ iconHTML.value }}}
			<# } else { #>
			<i class="{{ settings.icon }}"></i>
			<# } #>
		</div>
		</div>
		<# } #>
		<# if ( settings.title ) { #>
		<{{ elementor.helpers.validateHTMLTag( settings.title_tag ) }} {{{ view.getRenderAttributeString( 'title' ) }}}>{{{ settings.title }}}</{{ elementor.helpers.validateHTMLTag( settings.title_tag ) }}>
		<# } #>

		<# if ( settings.description ) { #>
		<div {{{ view.getRenderAttributeString( 'description' ) }}}>{{{ settings.description }}}</div>
		<# } #>

		<# if ( settings.button ) { #>
		<div class="responsive-cta__button-wrapper responsive-cta__content-item elementor-content-item {{ animationClass }}">
			<{{ buttonTag }} href="#" {{{ view.getRenderAttributeString( 'button' ) }}}>{{{ settings.button }}}</{{ buttonTag }}>
		</div>
		<# } #>
		</div>
		<# } #>
		<# if ( settings.ribbon_title ) {
		var ribbonClasses = 'elementor-ribbon';

		if ( settings.ribbon_horizontal_position ) {
		ribbonClasses += ' elementor-ribbon-' + settings.ribbon_horizontal_position;
		} #>
		<div class="{{ ribbonClasses }}">
			<div class="elementor-ribbon-inner">{{{ settings.ribbon_title }}}</div>
		</div>
		<# } #>
		</{{ wrapperTag }}>
		<?php
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return esc_url( 'https://cyberchimps.com/docs/widgets/call-to-action/' );
	}
}
