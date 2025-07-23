<?php
/**
 * RAEL Feature List Widget
 *
 * @since      1.2.2
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Elementor 'Feature List' widget class.
 *
 * @since 1.2.2
 */
class Responsive_Addons_For_Elementor_Feature_List extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-feature-list';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Feature List', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Feature List widget icon.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-list rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the Feature List widget belongs to.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of categories the Feature List widget belongs to.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'feature', 'list', 'connector' );
	}

	/**
	 * Register Controls.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'feature_list_content_section',
			array(
				'label' => __( 'Content Settings', 'responsive-addons-for-elementor' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'rael_icon_type',
			array(
				'label'       => __( 'Icon Type', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'icon',
				'options'     => array(
					'icon'  => array(
						'title' => __( 'Icon', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-star',
					),
					'image' => array(
						'title' => __( 'Image', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-image',
					),
				),
				'label_block' => false,
			)
		);

		$repeater->add_control(
			'rael_feature_list_icon_new',
			array(
				'label'            => esc_html__( 'Icon', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'rael_feature_list_icon',
				'condition'        => array(
					'rael_icon_type' => 'icon',
				),
			)
		);

		$repeater->add_control(
			'rael_icon_style',
			array(
				'label'            => esc_html__( 'Icon Style', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::SWITCHER,
				'default'          => '',
				'label_on'         => __( 'ON', 'responsive-addons-for-elementor' ),
				'label_off'        => __( 'OFF', 'responsive-addons-for-elementor' ),
				'return_value'     => 'on',
				'fa4compatibility' => 'rael_feature_list_icon',
				'condition'        => array(
					'rael_icon_type' => 'icon',
				),
			)
		);

		$repeater->add_control(
			'rael_feature_list_icon_individual_color',
			array(
				'label'            => esc_html__( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::COLOR,
				'global'           => [
					'default'       => Global_Colors::COLOR_PRIMARY,
				],
				'condition'        => array(
					'rael_icon_style' => 'on',
				),
			)
		);

		$repeater->add_control(
			'rael_feature_list_icon_bg_color',
			array(
				'label'            => esc_html__( 'Icon Background', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::COLOR,
				'global'           => [
					'default'       => Global_Colors::COLOR_PRIMARY,
				],
				'fa4compatibility' => 'rael_feature_list_icon',
				'condition'        => array(
					'rael_icon_style' => 'on',
				),
			)
		);
		$repeater->add_control(
			'rael_feature_list_icon_box_bg_color',
			array(
				'label'            => esc_html__( 'Icon Box Background', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::COLOR,
				'global'           => [
					'default'       => Global_Colors::COLOR_PRIMARY,
				],
				'fa4compatibility' => 'rael_feature_list_icon',
				'condition'        => array(
					'rael_icon_style' => 'on',
				),
			)
		);

		$repeater->add_control(
			'rael_feature_list_image',
			array(
				'label'     => esc_html__( 'Image', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'rael_icon_type' => 'image',
				),
			)
		);

		$repeater->add_control(
			'rael_feature_title',
			array(
				'label'       => esc_html__( 'Title', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Feature Item 1', 'responsive-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'rael_feature_content',
			array(
				'label'   => esc_html__( 'Content', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__(
					'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore 
						et dolore magna aliqua.',
					'responsive-addons-for-elementor'
				),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'rael_feature_list_link',
			array(
				'label'       => esc_html__( 'Link', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array( 'active' => true ),
				'placeholder' => esc_html__( 'https://your-link.com', 'responsive-addons-for-elementor' ),
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'rael_feature_list',
			array(
				'label'       => esc_html__( 'Feature Item', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'seperator'   => 'before',
				'default'     => array(
					array(
						'rael_feature_list_icon_new' => array(
							'value'   => 'fas fa-check',
							'library' => 'fa-solid',
						),
						'rael_feature_title'         => esc_html__( 'Feature Item 1', 'responsive-addons-for-elementor' ),
						'rael_feature_content'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisi cing elit, sed do eiusmod tempor incididunt ut abore et dolore magna aliqua', 'responsive-addons-for-elementor' ),
					),
					array(
						'rael_feature_list_icon_new' => array(
							'value'   => 'fas fa-times',
							'library' => 'fa-solid',
						),
						'rael_feature_title'         => esc_html__( 'Feature Item 2', 'responsive-addons-for-elementor' ),
						'rael_feature_content'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisi cing elit, sed do eiusmod tempor incididunt ut abore et dolore magna aliqua', 'responsive-addons-for-elementor' ),
					),
					array(
						'rael_feature_list_icon_new' => array(
							'value'   => 'fas fa-anchor',
							'library' => 'fa-solid',
						),
						'rael_feature_title'         => esc_html__( 'Feature Item 3', 'responsive-addons-for-elementor' ),
						'rael_feature_content'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisi cing elit, sed do eiusmod tempor incididunt ut abore et dolore magna aliqua', 'responsive-addons-for-elementor' ),
					),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '<i class="{{ rael_feature_list_icon_new.value }}" aria-hidden="true"></i> {{{ rael_feature_title }}}',
			)
		);

		$this->add_control(
			'rael_feature_title',
			array(
				'label'     => __( 'HTML Tag', 'responsive-addons-for-elementor' ),
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
					'p'    => 'p',
				),
				'default'   => 'h3',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_feature_list_icon_shape',
			array(
				'label'       => esc_html__( 'Icon Shape', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'circle',
				'label_block' => false,
				'options'     => array(
					'circle'  => esc_html__( 'Circle', 'responsive-addons-for-elementor' ),
					'square'  => esc_html__( 'Square', 'responsive-addons-for-elementor' ),
					'rhombus' => esc_html__( 'Rhombus', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_feature_list_icon_shape_view',
			array(
				'label'       => esc_html__( 'Shape View', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'stacked',
				'label_block' => false,
				'options'     => array(
					'framed'  => esc_html__( 'Framed', 'responsive-addons-for-elementor' ),
					'stacked' => esc_html__( 'Stacked', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_feature_list_icon_position',
			array(
				'label'           => esc_html__( 'Icon Position', 'responsive-addons-for-elementor' ),
				'type'            => Controls_Manager::CHOOSE,
				'options'         => array(
					'left'  => array(
						'title' => esc_html__( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'top'   => array(
						'title' => esc_html__( 'Top', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'         => 'left',
				'devices'         => array( 'desktop', 'tablet', 'mobile' ),
				'desktop_default' => 'left',
				'tablet_default'  => 'left',
				'mobile_default'  => 'left',
				'toggle'          => false,
			)
		);

		$this->add_responsive_control(
			'rael_arrow_indicator_position',
			array(
				'label'      => __( 'Arrow Indicator Position', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 35,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-feature-list-items.connector-type-modern .rael-feature-list-item:after' => 'top: {{SIZE}}{{UNIT}} !important;',
				),
				'condition'  => array(
					'rael_feature_list_icon_position' => 'top',
				),
			)
		);

		$this->add_control(
			'rael_feature_list_connector',
			array(
				'label'        => esc_html__( 'Show Connector', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'feature_list_style',
			array(
				'label' => __( 'List', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_list_space_between',
			array(
				'label'     => __( 'Space Between', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 15,
				),
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-feature-list-items .rael-feature-list-item:not(:last-child)'   => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .rael-feature-list-items .rael-feature-list-item:not(:first-child)'  => 'padding-top: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .rael-feature-list-items.connector-type-modern .rael-feature-list-item:not(:last-child):before' => 'height: calc(100% + {{SIZE}}{{UNIT}})',
					'body.rtl {{WRAPPER}} .rael-feature-list-items .rael-feature-list-item:after'  => 'left: calc(-{{SIZE}}{{UNIT}}/2)',
				),
			)
		);

		$this->add_control(
			'rael_list_connector_type',
			array(
				'label'       => esc_html__( 'Connector Type', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'connector-type-classic',
				'label_block' => false,
				'options'     => array(
					'connector-type-classic' => esc_html__( 'Classic', 'responsive-addons-for-elementor' ),
					'connector-type-modern'  => esc_html__( 'Modern', 'responsive-addons-for-elementor' ),
				),
				'condition'   => array(
					'rael_feature_list_connector'      => 'yes',
					'rael_feature_list_icon_position!' => 'top',
				),
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'rael_feature_list_connector_styles',
			array(
				'label'       => esc_html__( 'Connector Styles', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'solid',
				'label_block' => false,
				'options'     => array(
					'solid'  => esc_html__( 'Solid', 'responsive-addons-for-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'responsive-addons-for-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'responsive-addons-for-elementor' ),
				),
				'condition'   => array(
					'rael_feature_list_connector' => 'yes',
				),
				'selectors'   => array(
					'{{WRAPPER}} .connector-type-classic .connector'  => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .connector-type-modern .rael-feature-list-item:before, {{WRAPPER}} .connector-type-modern .rael-feature-list-item:after' => 'border-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_feature_list_connector_color',
			array(
				'label'     => esc_html__( 'Connector Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'default'   => '#37368e',
				'selectors' => array(
					'{{WRAPPER}} .connector-type-classic .connector'  => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .connector-type-modern .rael-feature-list-item:before, {{WRAPPER}} .connector-type-modern .rael-feature-list-item:after' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_feature_list_connector' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_feature_list_connector_width',
			array(
				'label'     => esc_html__( 'Connector Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'unit' => 'px',
					'size' => 1,
				),
				'range'     => array(
					'px' => array(
						'min' => 1,
						'max' => 5,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .connector-type-classic .connector'  => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-feature-list-items.connector-type-modern .rael-feature-list-item:before, {{WRAPPER}} .rael-feature-list-items.connector-type-modern .rael-feature-list-item:after' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-icon-position-left .connector-type-modern .rael-feature-list-item:before, {{WRAPPER}} .rael-icon-position-left .connector-type-modern .rael-feature-list-item:after'       => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-icon-position-right .connector-type-modern .rael-feature-list-item:before, {{WRAPPER}} .rael-icon-position-right .connector-type-modern .rael-feature-list-item:after'     => 'border-width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_feature_list_connector' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'feature_icon_style',
			array(
				'label' => __( 'Icon', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_feature_list_icon_background',
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array(
					'image',
				),
				'color'    => array(
					'default' => '#3858f4',
				),
				'selector' => '{{WRAPPER}} .rael-feature-list-items .rael-feature-list-icon-box .rael-feature-list-icon-inner',
			)
		);

		$this->add_control(
			'rael_feature_list_secondary_color',
			array(
				'label'     => esc_html__( 'Secondary Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-feature-list-items.framed .rael-feature-list-icon' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_feature_list_icon_shape_view' => 'framed',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_feature_list_icon_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-feature-list-items.stacked .rael-feature-list-icon, {{WRAPPER}} .rael-feature-list-items.framed .rael-feature-list-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-feature-list-items.stacked .rael-feature-list-icon svg, {{WRAPPER}} .rael-feature-list-items.framed .rael-feature-list-icon svg' => 'fill: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'rael_feature_list_icon_circle_size',
			array(
				'label'     => esc_html__( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 70,
				),
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-feature-list-icon-box .rael-feature-list-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-feature-list-items.connector-type-classic .connector' => 'right: calc(100% - {{SIZE}}{{UNIT}});',
				),
			)
		);
		$this->add_responsive_control(
			'rael_feature_list_icon_size',
			array(
				'label'     => esc_html__( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 21,
				),
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 150,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-feature-list-icon-box .rael-feature-list-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-feature-list-icon-box .rael-feature-list-icon img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-feature-list-image' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_feature_list_icon_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => 15,
					'right'    => 15,
					'bottom'   => 15,
					'left'     => 15,
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-feature-list-icon-box .rael-feature-list-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_feature_list_icon_border_width',
			array(
				'label'     => esc_html__( 'Border Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 1,
				),
				'range'     => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-feature-list-icon-box .rael-feature-list-icon-inner' => 'padding: {{SIZE}}{{UNIT}};',

				),
				'condition' => array(
					'rael_feature_list_icon_shape_view' => 'framed',
				),
			)
		);

		$this->add_control(
			'rael_feature_list_icon_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-feature-list-icon-box .rael-feature-list-icon-inner'   => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rael-feature-list-icon-box .rael-feature-list-icon-inner .rael-feature-list-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_feature_list_icon_shape_view' => 'framed',
				),
			)
		);

		$this->add_responsive_control(
			'rael_feature_list_icon_space',
			array(
				'label'           => esc_html__( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'            => Controls_Manager::SLIDER,
				'range'           => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'devices'         => array( 'desktop', 'tablet', 'mobile' ),
				'desktop_default' => array(
					'size' => 30,
					'unit' => 'px',
				),
				'tablet_default'  => array(
					'size' => 20,
					'unit' => 'px',
				),
				'mobile_default'  => array(
					'size' => 10,
					'unit' => 'px',
				),
				'selectors'       => array(
					'{{WRAPPER}} .rael-icon-position-left .rael-feature-list-content-box, {{WRAPPER}} .rael-icon-position-right .rael-feature-list-content-box, {{WRAPPER}} .rael-icon-position-top .rael-feature-list-content-box' => 'margin: {{SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}} .rael-mobile-icon-position-left .rael-feature-list-content-box' => 'margin: 0 0 0 {{SIZE}}{{UNIT}} !important;',
					'(mobile){{WRAPPER}} .rael-mobile-icon-position-right .rael-feature-list-content-box'    => 'margin: 0 {{SIZE}}{{UNIT}} 0 0 !important;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'feature_content_style',
			array(
				'label' => __( 'Content', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_feature_list_text_align',
			array(
				'label'     => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'condition' => array(
					'rael_feature_list_icon_position' => 'top',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-feature-list-item' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_feature_list_heading_title',
			array(
				'label' => esc_html__( 'Title', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'rael_feature_list_title_bottom_space',
			array(
				'label'     => esc_html__( 'Title Bottom Space', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-feature-list-item .rael-feature-list-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_feature_list_title_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#414247',
				'selectors' => array(
					'{{WRAPPER}} .rael-feature-list-content-box .rael-feature-list-title, {{WRAPPER}} .rael-feature-list-content-box .rael-feature-list-title > a, {{WRAPPER}} .rael-feature-list-content-box .rael-feature-list-title:visited' => 'color: {{VALUE}};',
				),
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_feature_list_title_typography',
				'selector' => '{{WRAPPER}} .rael-feature-list-content-box .rael-feature-list-title, {{WRAPPER}} .rael-feature-list-content-box .rael-feature-list-title a',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			)
		);

		$this->add_control(
			'rael_feature_list_description',
			array(
				'label'     => esc_html__( 'Description', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_feature_list_description_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-feature-list-content-box .rael-feature-list-content' => 'color: {{VALUE}};',
				),
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'rael_feature_list_description_typography',
				'selector'       => '{{WRAPPER}} .rael-feature-list-content-box .rael-feature-list-content',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'fields_options' => array(
					'font_size' => array(
						'default' => array(
							'unit' => 'px',
							'size' => 14,
						),
					),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content Template.
	 *
	 * @since 1.2.2
	 * @access protected
	 */
	protected function content_template() {}

	/**
	 * Render Function
	 *
	 * @since 1.2.2
	 * @access protected
	 */
	protected function render() {
		$settings            = $this->get_settings_for_display();
		$icon_circle_size = 70;
		if ( isset( $settings['rael_feature_list_icon_circle_size']['size'] ) ) {
			$icon_circle_size = intval( $settings['rael_feature_list_icon_circle_size']['size'] );
		}
		$feature_list_css_id = 'rael-feature-list-' . esc_attr( $this->get_id() );

		$icon_tablet_pos = isset( $settings['rael_feature_list_icon_position_tablet'] ) ? $settings['rael_feature_list_icon_position_tablet'] : 'left';
		$icon_mobile_pos = isset( $settings['rael_feature_list_icon_position_mobile'] ) ? $settings['rael_feature_list_icon_position_mobile'] : 'left';

		$this->add_render_attribute(
			'rael_feature_list_wrapper',
			array(
				'class' => array(
					'rael-icon-position-' . $settings['rael_feature_list_icon_position'],
					'rael-tablet-icon-position-' . $icon_tablet_pos,
					'rael-mobile-icon-position-' . $icon_mobile_pos,
				),
			)
		);

		$this->add_render_attribute(
			'rael_feature_list',
			array(
				'id'    => $feature_list_css_id,
				'class' => array(
					'rael-feature-list-items',
					$settings['rael_feature_list_icon_shape'],
					$settings['rael_feature_list_icon_shape_view'],
					$settings['rael_list_connector_type'],
				),
			)
		);

		$this->add_render_attribute( 'rael_feature_list_item', 'class', 'rael-feature-list-item' );

		if ( 'top' === $settings['rael_feature_list_icon_position'] && 'yes' === $settings['rael_feature_list_connector'] ) {
			$this->add_render_attribute( 'rael_feature_list', 'class', 'connector-type-modern' );
		}

		if ( isset( $settings['rael_feature_list_icon_border_width']['right'] ) && isset( $settings['rael_feature_list_icon_border_width']['left'] ) ) {
			$border = $settings['rael_feature_list_icon_border_width']['right'] + $settings['rael_feature_list_icon_border_width']['left'];
		}

		if ( 'rhombus' === $settings['rael_feature_list_icon_shape'] ) {
			$margin          = 30;
			$connector_width = intval( $icon_circle_size + $margin + ( ! empty( $settings['rael_feature_list_connector_width']['size'] ) ? $settings['rael_feature_list_connector_width']['size'] : 0 ) );
		} else {
			$connector_width = intval( $icon_circle_size + ( ! empty( $settings['rael_feature_list_connector_width']['size'] ) ? $settings['rael_feature_list_connector_width']['size'] : 0 ) );
		}

		if ( 'right' === $settings['rael_feature_list_icon_position'] ) {
			$connector = 'left: calc(100% - ' . $connector_width . 'px); right: 0;';
		} else {
			$connector = 'right: calc(100% - ' . $connector_width . 'px); left: 0;';
		}

		if ( 'right' === $icon_tablet_pos ) {
			$connector_tablet = 'left: calc(100% - ' . $connector_width . 'px); right: 0;';
		} else {
			$connector_tablet = 'right: calc(100% - ' . $connector_width . 'px); left: 0;';
		}

		if ( 'right' === $icon_mobile_pos ) {
			$connector_mobile = 'left: calc(100% - ' . $connector_width . 'px); right: 0;';
		} else {
			$connector_mobile = 'right: calc(100% - ' . $connector_width . 'px); left: 0;';
		}

		?>

		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_feature_list_wrapper' ) ); ?>>
			<ul <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_feature_list' ) ); ?>>
			<?php
			foreach ( $settings['rael_feature_list'] as $index => $item ) :

				$icon_color     = ( 'on' === $item['rael_icon_style'] && isset( $item['rael_feature_list_icon_individual_color'] ) ) ? esc_attr( $item['rael_feature_list_icon_individual_color'] ) : '';
				$icon_bg        = ( ( 'on' === $item['rael_icon_style'] ) ? ' ' . esc_attr( $item['rael_feature_list_icon_bg_color'] ) : '' );
				$icon_box_bg    = ( ( 'on' === $item['rael_icon_style'] ) ? ' style="background-color:' . esc_attr( $item['rael_feature_list_icon_box_bg_color'] ) . '"' : '' );
				$feat_title_tag = Utils::validate_html_tag( $settings['rael_feature_title'] );

				$this->add_render_attribute( 'rael_feature_list_icon' . $index, 'class', 'rael-feature-list-icon fl-icon-' . $index );
				$this->add_render_attribute( 'rael_feature_list_title' . $index, 'class', 'rael-feature-list-title' );
				$this->add_render_attribute( 'rael_feature_list_content' . $index, 'class', 'rael-feature-list-content' );

				if ( $item['rael_feature_list_link']['url'] ) {
					$this->add_render_attribute( 'rael_feature_list_title_anchor' . $index, 'href', esc_url( $item['rael_feature_list_link']['url'] ) );

					if ( $item['rael_feature_list_link']['is_external'] ) {
						$this->add_render_attribute( 'rael_feature_list_title_anchor' . $index, 'target', '_blank' );
					}

					if ( $item['rael_feature_list_link']['nofollow'] ) {
						$this->add_render_attribute( 'rael_feature_list_title_anchor' . $index, 'rel', 'nofollow' );
					}
				}

				$feature_icon_tag = 'span';

				$feature_has_icon = ( ! empty( $item['rael_feature_list_icon'] ) || ! empty( $item['rael_feature_list_icon_new'] ) );

				if ( $item['rael_feature_list_link']['url'] ) {
					$this->add_render_attribute( 'rael_feature_list_link' . $index, 'href', $item['rael_feature_list_link']['url'] );

					if ( $item['rael_feature_list_link']['is_external'] ) {
						$this->add_render_attribute( 'rael_feature_list_link' . $index, 'target', '_blank' );
					}

					if ( $item['rael_feature_list_link']['nofollow'] ) {
						$this->add_render_attribute( 'rael_feature_list_link' . $index, 'rel', 'nofollow' );
					}
					$feature_icon_tag = 'a';
				}
				?>
				<li class="rael-feature-list-item">
					<?php if ( 'yes' === $settings['rael_feature_list_connector'] ) : ?>
						<span class="connector" style="<?php echo wp_kses_post( $connector ); ?>"></span>
						<span class="connector connector-tablet" style="<?php echo wp_kses_post( $connector_tablet ); ?>"></span>
						<span class="connector connector-mobile" style="<?php echo wp_kses_post( $connector_mobile ); ?>"></span>
					<?php endif; ?>

					<div class="rael-feature-list-icon-box">
						<div class="rael-feature-list-icon-inner"<?php echo wp_kses_post( $icon_box_bg ); ?>>
							<<?php echo wp_kses_post( $feature_icon_tag ) . ' ' . wp_kses_post( $this->get_render_attribute_string( 'rael_feature_list_icon' . $index ) ) . $this->get_render_attribute_string( 'rael_feature_list_link' . $index ) . 'style="background-color:' . esc_attr( $icon_bg ) . '"' ?>>
				<?php
				if ( 'icon' === $item['rael_icon_type'] && $feature_has_icon ) {

					if ( empty( $item['rael_feature_list_icon'] ) || isset( $item['__fa4_migrated']['rael_feature_list_icon_new'] ) ) {

						if ( $icon_color ) {
							?>
						<style>
							<?php
								echo wp_kses_post( "#{$feature_list_css_id} .rael-feature-list-icon.fl-icon-{$index} svg { color: {$icon_color} !important; fill: {$icon_color} !important; } " );
							?>
						</style>
							<?php
						}

						Icons_Manager::render_icon(
							$item['rael_feature_list_icon_new'],
							array(
								'aria-hidden' => 'true',
							)
						);
					} else {
						echo '<i class="' . esc_attr( $item['rael_feature_list_icon'] ) . '" aria-hidden="true"></i>';
					}
				}

				if ( 'image' === $item['rael_icon_type'] ) {
					$this->add_render_attribute(
						'feature_list_image' . $index,
						array(
							'src'   => esc_url( $item['rael_feature_list_image']['url'] ),
							'class' => 'rael-feature-list-image',
							'alt'   => esc_attr( get_post_meta( $item['rael_feature_list_image']['id'], '_wp_attachment_image_alt', true ) ),
						)
					);

					echo '<img ' . wp_kses_post( $this->get_render_attribute_string( 'feature_list_image' . $index ) ) . '>';

				}
				?>
							</<?php echo esc_attr( $feature_icon_tag ); ?>>
						</div>
					</div>
					<div class="rael-feature-list-content-box">
						<?php
						echo '<' . implode(
							' ',
							array(
								esc_attr( $feat_title_tag ),
								wp_kses_post( $this->get_render_attribute_string( 'rael_feature_list_title' . $index ) ),
							)
						);//phpcs:ignore Generic.WhiteSpace.ScopeIndent.Incorrect
						?>
							><?php echo ! empty( $item['rael_feature_list_link']['url'] ) ? '<a ' . wp_kses_post( $this->get_render_attribute_string( 'rael_feature_list_title_anchor' . $index ) ) . '>' : ''; ?><?php echo wp_kses_post( $item['rael_feature_title'] ); ?><?php echo ! empty( $item['rael_feature_list_link']['url'] ) ? '</a>' : ''; ?></<?php echo esc_attr( $feat_title_tag ); ?>
						>
						<p <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_feature_list_content' . $index ) ); ?>><?php echo wp_kses_post( $item['rael_feature_content'] ); ?></p>
						</div>

					</li>
				<?php
				endforeach;
			?>
			</ul>
		</div>
		<?php
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/feature-list';
	}

}
