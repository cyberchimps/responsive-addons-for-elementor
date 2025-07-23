<?php
/**
 * Dual Color Header Widget
 *
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Dual Color Header widget class.
 */
class Responsive_Addons_For_Elementor_Dual_Color_Header extends Widget_Base {


	/**
	 * Get widget name.
	 *
	 * Retrieve 'Dual Color Header' widget name.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael_dual_color_header';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve 'Dual Color Header' widget title.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Dual Color Header', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve 'Dual Color Header' widget icon.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-heading rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the Dual Color Header widget belongs to.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get custom help url
	 *
	 * @since 1.3.0
	 * @access public
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/dual-color-header';
	}

	/**
	 * Register Controls
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'rael_section_dual_color_header_content_settings',
			array(
				'label' => esc_html__( 'Content Settings', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_dual_color_header_type',
			array(
				'label'       => esc_html__( 'Content Style', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'dual-color-header-default',
				'label_block' => false,
				'options'     => array(
					'dual-color-header-default'        => esc_html__( 'Default', 'responsive-addons-for-elementor' ),
					'dual-color-header-icon-on-top'    => esc_html__( 'Icon on top', 'responsive-addons-for-elementor' ),
					'dual-color-header-icon-subtext-on-top' => esc_html__( 'Icon &amp; sub-text on top', 'responsive-addons-for-elementor' ),
					'dual-color-header-subtext-on-top' => esc_html__( 'Sub-text on top', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_show_dual_color_header_icon_content',
			array(
				'label'        => __( 'Show Icon', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);
		$this->add_control(
			'rael_show_dual_color_header_separator',
			array(
				'label'        => __( 'Show Separator', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'separator'    => 'after',
			)
		);

		$this->add_control(
			'rael_dual_color_header_icon_new',
			array(
				'label'            => esc_html__( 'Icon', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'rael_dual_color_header_icon',
				'default'          => array(
					'value'   => 'fas fa-snowflake',
					'library' => 'fa-solid',
				),
				'condition'        => array(
					'rael_show_dual_color_header_icon_content' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_title_tag',
			array(
				'label'   => __( 'Title Tag', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => array(
					'h1'   => __( 'H1', 'responsive-addons-for-elementor' ),
					'h2'   => __( 'H2', 'responsive-addons-for-elementor' ),
					'h3'   => __( 'H3', 'responsive-addons-for-elementor' ),
					'h4'   => __( 'H4', 'responsive-addons-for-elementor' ),
					'h5'   => __( 'H5', 'responsive-addons-for-elementor' ),
					'h6'   => __( 'H6', 'responsive-addons-for-elementor' ),
					'span' => __( 'Span', 'responsive-addons-for-elementor' ),
					'p'    => __( 'P', 'responsive-addons-for-elementor' ),
					'div'  => __( 'Div', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_dual_color_header_first_title',
			array(
				'label'       => esc_html__( 'Title ( First Part )', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Dual Heading', 'responsive-addons-for-elementor' ),
				'dynamic'     => array( 'action' => true ),
			)
		);

		$this->add_control(
			'rael_dual_color_header_last_title',
			array(
				'label'       => esc_html__( 'Title ( Last Part )', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Example', 'responsive-addons-for-elementor' ),
				'dynamic'     => array( 'action' => true ),
			)
		);

		$this->add_control(
			'rael_dual_color_header_subtext',
			array(
				'label'       => esc_html__( 'Sub Text', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::WYSIWYG,
				'label_block' => true,
				'default'     => esc_html__( 'Insert a meaningful line to evaluate the headline.', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_responsive_control(
			'rael_dual_color_header_content_alignment',
			array(
				'label'        => esc_html__( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => true,
				'options'      => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'      => 'center',
				'prefix_class' => 'rael-dual-header-content%s-align-',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_dual_color_header_separator_settings',
			array(
				'label'     => __( 'Separator', 'responsive-addons-for-elementor' ),
				'tab'       => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'rael_show_dual_color_header_separator' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_dual_color_header_separator_position',
			array(
				'label'   => __( 'Separator Position', 'responsive-addons-for-elementor' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'after_title',
				'options' => array(
					'before_title' => __( 'Before Title', 'responsive-addons-for-elementor' ),
					'after_title'  => __( 'After Title', 'responsive-addons-for-elementor' ),
				),
			)
		);
		$this->add_control(
			'rael_dual_color_header_separator_type',
			array(
				'label'   => __( 'Separator Type', 'responsive-addons-for-elementor' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'line',
				'options' => array(
					'line' => __( 'Line', 'responsive-addons-for-elementor' ),
					'icon' => __( 'Icon', 'responsive-addons-for-elementor' ),
				),
			)
		);
		$this->add_control(
			'rael_dual_color_header_separator_icon',
			array(
				'label'     => __( 'Icon', 'responsive-addons-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-star',
					'library' => 'solid',
				),
				'condition' => array(
					'rael_dual_color_header_separator_type' => 'icon',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_dual_color_header_style_settings',
			array(
				'label' => esc_html__( 'Dual Heading Style', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_dual_color_header_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-dual-color-header' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_dual_color_header_below_heading_spacing',
			array(
				'label'     => esc_html__( 'Below heading spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 500,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-dual-color-header .title' => 'margin-bottom: {{SIZE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'rael_dual_color_header_container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-dual-color-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_dual_color_header_container_margin',
			array(
				'label'      => esc_html__( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-dual-color-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_dual_color_header_border',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-dual-color-header',
			)
		);

		$this->add_control(
			'rael_dual_color_header_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 500,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-dual-color-header' => 'border-radius: {{SIZE}}px;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_dual_color_header_shadow',
				'selector' => '{{WRAPPER}} .rael-dual-color-header',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_dual_color_header_icon_style_settings',
			array(
				'label'     => esc_html__( 'Icon Style', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_show_dual_color_header_icon_content' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_dual_color_header_icon_size',
			array(
				'label'     => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 36,
				),
				'range'     => array(
					'px' => array(
						'min'  => 20,
						'max'  => 500,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-dual-color-header i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-dual-color-header img' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-dual-color-header .rael-dual-color-header-svg-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-dual-color-header .rael-dual-color-header-svg-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_dual_color_header_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#4d4d4d',
				'selectors' => array(
					'{{WRAPPER}} .rael-dual-color-header i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-dual-color-header svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_dual_color_header_title_style_settings',
			array(
				'label' => esc_html__( 'Color &amp; Typography', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_dual_color_header_title_heading',
			array(
				'label' => esc_html__( 'Title Style', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_dual_color_header_base_title_color',
			array(
				'label'     => esc_html__( 'Main Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#4d4d4d',
				'selectors' => array(
					'{{WRAPPER}} .rael-dual-color-header .title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_dual_color_header_dual_color_selector',
			array(
				'label'   => esc_html__( 'Dual Color', 'responsive-addons-for-elementor' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => array(
					'solid-color'    => array(
						'title' => __( 'Color', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-paint-brush',
					),
					'gradient-color' => array(
						'title' => __( 'Gradient', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-barcode',
					),
				),
				'toggle'  => true,
				'default' => 'solid-color',
			)
		);

		$this->add_control(
			'rael_dual_color_header_dual_title_color',
			array(
				'label'     => esc_html__( 'Solid Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#9401D9',
				'selectors' => array(
					'{{WRAPPER}} .rael-dual-color-header .title span.lead' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_dual_color_header_dual_color_selector' => 'solid-color',
				),
			)
		);

		$this->add_control(
			'rael_dual_color_header_dual_title_color_gradient_first',
			array(
				'label'     => esc_html__( 'First Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#062ACA',
				'condition' => array(
					'rael_dual_color_header_dual_color_selector' => 'gradient-color',
				),
			)
		);

		$this->add_control(
			'rael_dual_color_header_dual_title_color_gradient_second',
			array(
				'label'     => esc_html__( 'Second Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#9401D9',
				'condition' => array(
					'rael_dual_color_header_dual_color_selector' => 'gradient-color',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_dual_color_header_first_title_typography',
				'selector' => '{{WRAPPER}} .rael-dual-color-header .title, {{WRAPPER}} .rael-dual-color-header .title span',
			)
		);

		$this->add_control(
			'rael_dual_color_header_sub_title_heading',
			array(
				'label'     => esc_html__( 'Sub-title Style ', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_dual_color_header_subtext_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#4d4d4d',
				'selectors' => array(
					'{{WRAPPER}} .rael-dual-color-header .subtext' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_dual_color_header_subtext_typography',
				'selector' => '{{WRAPPER}} .rael-dual-color-header .subtext',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_dual_color_header_separator_style_settings',
			array(
				'label'     => esc_html__( 'Separator', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_show_dual_color_header_separator' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_section_dual_color_header_separator_icon_size',
			array(
				'label'     => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 36,
				),
				'range'     => array(
					'px' => array(
						'min'  => 20,
						'max'  => 500,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-dual-color-header .rael-dual-color-header-separator-wrap i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-dual-color-header .rael-dual-color-header-separator-wrap img' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-dual-color-header .rael-dual-color-header-separator-wrap svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_dual_color_header_separator_type' => 'icon',
				),
			)
		);

		$this->add_control(
			'rael_section_dual_color_header_separator_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#4d4d4d',
				'selectors' => array(
					'{{WRAPPER}} .rael-dual-color-header .rael-dual-color-header-separator-wrap i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-dual-color-header .rael-dual-color-header-separator-wrap svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'rael_dual_color_header_separator_type' => 'icon',
				),
			)
		);

		$this->add_control(
			'rael_section_dual_color_header_separator_alignment',
			array(
				'label'     => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Flex Start', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Flex End', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'center',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .rael-dual-color-header-separator-wrap' => 'justify-content: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'rael_section_dual_color_header_separator_distance',
			array(
				'label'      => __( 'Distance Between Lines', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-dual-color-header-separator-wrap .separator-one' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-dual-color-header-separator-wrap .separator-two' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_dual_color_header_separator_type' => 'line',
				),
			)
		);
		$this->add_control(
			'rael_section_dual_color_header_separator_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-dual-color-header-separator-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// line left & right Tabs.
		$this->start_controls_tabs(
			'rael_dual_color_header_separator_tabs',
			array(
				'condition' => array(
					'rael_dual_color_header_separator_type' => 'line',
				),
			)
		);

		$this->start_controls_tab(
			'rael_dual_color_header_separator_left_tab',
			array(
				'label' => __( 'Left Line', 'responsive-addons-for-elementor' ),
			)
		);

		// line left style.
		$this->add_control(
			'rael_dual_color_header_separator_left_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 15,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-dual-color-header-separator-wrap .separator-one' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'rael_dual_color_header_separator_left_height',
			array(
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 5,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-dual-color-header-separator-wrap .separator-one' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'rael_dual_color_header_separator_left_radius',
			array(
				'label'      => __( 'Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-dual-color-header-separator-wrap .separator-one' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_dual_color_header_separator_left_bg',
				'label'    => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-dual-color-header-separator-wrap .separator-one',
			)
		);
		$this->end_controls_tab();
		// line right style.
		$this->start_controls_tab(
			'rael_dual_color_header_separator_right_tab',
			array(
				'label' => __( 'Right Line', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_control(
			'rael_dual_color_header_separator_right_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 15,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-dual-color-header-separator-wrap .separator-two' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'rael_dual_color_header_separator_right_height',
			array(
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 5,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-dual-color-header-separator-wrap .separator-two' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'rael_dual_color_header_separator_right_radius',
			array(
				'label'      => __( 'Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-dual-color-header-separator-wrap .separator-two' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_dual_color_header_separator_right_bg',
				'label'    => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-dual-color-header-separator-wrap .separator-two',
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Return Elementor allowed tags for current widget
	 *
	 * @return array Array of allowed tags for current widget
	 */
	private function allowed_html_tags() {
		return array(
			'div'  => array(
				'class' => array(),
			),
			'span' => array(
				'class' => array(),
			),
			'svg'  => array(
				'aria-hidden' => array(),
				'class'       => array(),
				'viewBox'     => array(),
				'viewbox'     => array(),
				'xmlns'       => array(),
			),
			'path' => array(
				'd' => array(),
			),
		);
	}

	/**
	 * Render
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function render() {
		$settings       = $this->get_settings_for_display();
		$gradient_style = '';
		if ( $settings['rael_dual_color_header_dual_title_color_gradient_first'] && $settings['rael_dual_color_header_dual_title_color_gradient_second'] ) {
			$gradient_style = 'style="background: -webkit-linear-gradient(' . $settings['rael_dual_color_header_dual_title_color_gradient_first'] . ', ' . $settings['rael_dual_color_header_dual_title_color_gradient_second'] . ');-webkit-background-clip: text;
            -webkit-text-fill-color: transparent;"';
		};
		$icon_migrated = isset( $settings['__fa4_migrated']['rael_dual_color_header_icon_new'] );
		$icon_is_new   = empty( $settings['rael_dual_color_header_icon'] );

		$separator_markup = '<div class="rael-dual-color-header-separator-wrap">';
		if ( 'icon' === $settings['rael_dual_color_header_separator_type'] ) {
			ob_start();
			Icons_Manager::render_icon( $settings['rael_dual_color_header_separator_icon'], array( 'aria-hidden' => 'true' ) );
			$separator_markup .= ob_get_clean();
		} else {
			$separator_markup .= '<span class="separator-one"></span>
			<span class="separator-two"></span>';
		}
		$separator_markup .= '</div>'; ?>

		<?php
		$title_tag = esc_attr( Helper::validate_html_tags( $settings['rael_title_tag'], 'h2' ) );
		$dual_color_class = esc_attr( $settings['rael_dual_color_header_dual_color_selector'] );
		$gradient_style_attr = esc_attr( $gradient_style );
		$first_title = wp_kses_post( $settings['rael_dual_color_header_first_title'] );
		$last_title = wp_kses_post( $settings['rael_dual_color_header_last_title'] );
		$subtext = wp_kses_post( $settings['rael_dual_color_header_subtext'] );
		$separator_position = $settings['rael_dual_color_header_separator_position'];
		$show_icon = ( 'yes' === $settings['rael_show_dual_color_header_icon_content'] );
		$separator_before = ( 'before_title' === $separator_position ) ? wp_kses( $separator_markup, $this->allowed_html_tags() ) : '';
		$separator_after = ( 'after_title' === $separator_position ) ? wp_kses( $separator_markup, $this->allowed_html_tags() ) : '';
		?>

		<?php if ( 'dual-color-header-subtext-on-top' === $settings['rael_dual_color_header_type'] ) : ?>
			<div class="rael-dual-color-header">
				<span class="subtext"><?php echo $subtext; ?></span>
				<?php echo $separator_before; ?>
				<<?php echo $title_tag; ?> class="title">
					<span style="<?php echo $gradient_style_attr; ?>" class="lead <?php echo $dual_color_class; ?>">
						<?php echo $first_title; ?>
					</span>
					<span><?php echo $last_title; ?></span>
				</<?php echo $title_tag; ?>>
				<?php echo $separator_after; ?>
				<?php if ( $show_icon ) : ?>
					<?php if ( $icon_is_new || $icon_migrated ) : ?>
						<span class="rael-dual-color-header-svg-icon">
							<?php Icons_Manager::render_icon( $settings['rael_dual_color_header_icon_new'], array( 'aria-hidden' => 'true' ) ); ?>
						</span>
					<?php else : ?>
						<i class="<?php echo esc_attr( $settings['rael_dual_color_header_icon'] ); ?>"></i>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( 'dual-color-header-icon-subtext-on-top' === $settings['rael_dual_color_header_type'] ) : ?>
			<div class="rael-dual-color-header">
				<?php if ( $show_icon ) : ?>
					<?php if ( $icon_is_new || $icon_migrated ) : ?>
						<span class="rael-dual-color-header-svg-icon">
							<?php Icons_Manager::render_icon( $settings['rael_dual_color_header_icon_new'], array( 'aria-hidden' => 'true' ) ); ?>
						</span>
					<?php else : ?>
						<i class="<?php echo esc_attr( $settings['rael_dual_color_header_icon'] ); ?>"></i>
					<?php endif; ?>
				<?php endif; ?>
				<span class="subtext"><?php echo $subtext; ?></span>
				<?php echo $separator_before; ?>
				<<?php echo $title_tag; ?> class="title">
					<span style="<?php echo $gradient_style_attr; ?>" class="lead <?php echo $dual_color_class; ?>">
						<?php echo $first_title; ?>
					</span>
					<span><?php echo $last_title; ?></span>
				</<?php echo $title_tag; ?>>
				<?php echo $separator_after; ?>
			</div>
		<?php endif; ?>

		<?php if ( 'dual-color-header-icon-on-top' === $settings['rael_dual_color_header_type'] ) : ?>
			<div class="rael-dual-color-header">
				<?php if ( $show_icon ) : ?>
					<?php if ( $icon_is_new || $icon_migrated ) : ?>
						<span class="rael-dual-color-header-svg-icon">
							<?php Icons_Manager::render_icon( $settings['rael_dual_color_header_icon_new'], array( 'aria-hidden' => 'true' ) ); ?>
						</span>
					<?php else : ?>
						<i class="<?php echo esc_attr( $settings['rael_dual_color_header_icon'] ); ?>"></i>
					<?php endif; ?>
				<?php endif; ?>
				<?php echo $separator_before; ?>
				<<?php echo $title_tag; ?> class="title">
					<span style="<?php echo $gradient_style_attr; ?>" class="lead <?php echo $dual_color_class; ?>">
						<?php echo $first_title; ?>
					</span>
					<span><?php echo $last_title; ?></span>
				</<?php echo $title_tag; ?>>
				<?php echo $separator_after; ?>
				<span class="subtext"><?php echo $subtext; ?></span>
			</div>
		<?php endif; ?>

		<?php if ( 'dual-color-header-default' === $settings['rael_dual_color_header_type'] ) : ?>
			<div class="rael-dual-color-header">
				<?php echo $separator_before; ?>
				<<?php echo $title_tag; ?> class="title">
					<span style="<?php echo $gradient_style_attr; ?>" class="lead <?php echo $dual_color_class; ?>">
						<?php echo $first_title; ?>
					</span>
					<span><?php echo $last_title; ?></span>
				</<?php echo $title_tag; ?>>
				<?php echo $separator_after; ?>
				<span class="subtext"><?php echo $subtext; ?></span>
				<?php if ( $show_icon ) : ?>
					<?php if ( $icon_is_new || $icon_migrated ) : ?>
						<span class="rael-dual-color-header-svg-icon">
							<?php Icons_Manager::render_icon( $settings['rael_dual_color_header_icon_new'], array( 'aria-hidden' => 'true' ) ); ?>
						</span>
					<?php else : ?>
						<i class="<?php echo esc_attr( $settings['rael_dual_color_header_icon'] ); ?>"></i>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>


		<?php
	}

}
