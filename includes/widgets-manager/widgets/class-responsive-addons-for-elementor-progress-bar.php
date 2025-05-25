<?php
/**
 * Progress Bar Widget
 *
 * @since      1.2.0
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.

}

/**
 * Progress Bar widget class.
 *
 * @since 1.2.0
 */
class Responsive_Addons_For_Elementor_Progress_Bar extends Widget_Base {

	/**
	 * Get name function
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function get_name() {
		return 'rael-progress-bar';
	}

	/**
	 * Get title function
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function get_title() {
		return __( 'Progress Bar', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get icon function
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function get_icon() {
		return 'eicon-skill-bar rael-badge';
	}

	/**
	 * Get categories function
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get keywords function
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function get_keywords() {
		return array( 'bar', 'progress', 'rael', 'status', 'indicator', 'progress bar' );
	}

	/**
	 * Get custom help url function
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/progress-bar';
	}

	/**
	 * Register controls function
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'progress_bar_section_layout',
			array(
				'label' => __( 'Layout', 'responsive-addons-for-elementor' ),
			)
		);

		$options = apply_filters(
			'rael_add_progressbar_layout',
			array(
				'layouts'    => array(
					'line'             => __( 'Line', 'responsive-addons-for-elementor' ),
					'line_rainbow'     => __( 'Line Rainbow', 'responsive-addons-for-elementor' ),
					'circle'           => __( 'Circle', 'responsive-addons-for-elementor' ),
					'circle_fill'      => __( 'Circle Fill', 'responsive-addons-for-elementor' ),
					'half_circle'      => __( 'Half Circle', 'responsive-addons-for-elementor' ),
					'half_circle_fill' => __( 'Half Circle Fill', 'responsive-addons-for-elementor' ),
					'box'              => __( 'Box', 'responsive-addons-for-elementor' ),
				),
				'conditions' => array(
					'line_rainbow',
					'circle_fill',
					'half_circle_fill',
					'box',
				),
			)
		);

		$this->add_control(
			'rael_progress_bar_layout',
			array(
				'label'   => __( 'Layout', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $options['layouts'],
				'default' => 'line',
			)
		);

		$this->add_control(
			'rael_progress_bar_title',
			array(
				'label'     => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => __( 'Progress Bar', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_progress_bar_title_html_tag',
			array(
				'label'     => __( 'Title HTML Tag', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h1'   => __( 'H1', 'responsive-addons-for-elementor' ),
					'h2'   => __( 'H2', 'responsive-addons-for-elementor' ),
					'h3'   => __( 'H3', 'responsive-addons-for-elementor' ),
					'h4'   => __( 'H4', 'responsive-addons-for-elementor' ),
					'h5'   => __( 'H5', 'responsive-addons-for-elementor' ),
					'h6'   => __( 'H6', 'responsive-addons-for-elementor' ),
					'div'  => __( 'div', 'responsive-addons-for-elementor' ),
					'span' => __( 'span', 'responsive-addons-for-elementor' ),
					'p'    => __( 'p', 'responsive-addons-for-elementor' ),
				),
				'default'   => 'div',
				'separator' => 'after',
			)
		);

		$this->add_control(
			'rael_progress_bar_value_type',
			array(
				'label'   => esc_html__( 'Counter Value Type', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'static'  => __( 'Static', 'responsive-addons-for-elementor' ),
					'dynamic' => __( 'Dynamic', 'responsive-addons-for-elementor' ),
				),
				'default' => 'static',
			)
		);

		$this->add_control(
			'rael_progress_bar_value',
			array(
				'label'      => __( 'Counter Value', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 50,
				),
				'condition'  => array(
					'rael_progress_bar_value_type' => 'static',
				),
			)
		);

		$this->add_control(
			'rael_progress_bar_value_dynamic',
			array(
				'label'     => __( 'Counter Value', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 100,
				'step'      => 1,
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'rael_progress_bar_value_type' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'rael_progress_bar_show_count',
			array(
				'label'        => esc_html__( 'Display Count', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'rael_progress_bar_animation_duration',
			array(
				'label'      => __( 'Animation Duration', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 1000,
						'max'  => 10000,
						'step' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 1500,
				),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'rael_progress_bar_prefix_label',
			array(
				'label'     => __( 'Prefix Label', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array( 'active' => true ),
				'default'   => __( 'Prefix', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_progress_bar_layout' => 'half_circle',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_progress_bar_postfix_label',
			array(
				'label'     => __( 'Postfix Label', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array( 'active' => true ),
				'default'   => __( 'Postfix', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_progress_bar_layout' => 'half_circle',
				),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'progress_bar_section_style_general_line',
			array(
				'label'     => __( 'General', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_progress_bar_layout' => array( 'line', 'circle_fill', 'half_circle_fill', 'box' ),
				),
			)
		);

		$this->add_control(
			'rael_progress_bar_line_alignment',
			array(
				'label'   => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default' => 'center',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'progress_bar_section_style_bg',
			array(
				'label'     => __( 'Background', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_progress_bar_layout' => array( 'line', 'circle_fill', 'half_circle_fill', 'box' ),
				),
			)
		);

		$this->add_control(
			'rael_progress_bar_line_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 100,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 100,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-progressbar-line-container' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'rael_progress_bar_line_height',
			array(
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 12,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-progressbar-line' => 'height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'rael_progress_bar_line_bg_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eee',
				'selectors' => array(
					'{{WRAPPER}} .rael-progressbar-line' => 'background-color: {{VALUE}}',
				),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'progress_bar_section_style_fill',
			array(
				'label'     => __( 'Fill', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_progress_bar_layout' => array( 'line', 'circle_fill', 'half_circle_fill', 'box' ),
				),
			)
		);

		$this->add_control(
			'rael_progress_bar_line_fill_height',
			array(
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 12,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-progressbar-line-fill' => 'height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'progress_bar_line_fill_color',
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'types'     => array( 'classic', 'gradient' ),
				'exclude'   => array(
					'image',
				),
				'condition' => array(
					'rael_progress_bar_layout' => 'line',
				),
				'selector'  => '{{WRAPPER}} .rael-progressbar-line-fill',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_progress_bar_line_fill_stripe',
			array(
				'label'        => __( 'Show Stripe', 'responsive-addons-for-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition'    => array(
					'rael_progress_bar_layout' => 'line',
				),
				'default'      => 'no',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'rael_progress_bar_line_fill_stripe_animate',
			array(
				'label'     => __( 'Stripe Animation', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'normal'  => __( 'Left To Right', 'responsive-addons-for-elementor' ),
					'reverse' => __( 'Right To Left', 'responsive-addons-for-elementor' ),
					'none'    => __( 'Disabled', 'responsive-addons-for-elementor' ),
				),
				'default'   => 'none',
				'condition' => array(
					'rael_progress_bar_line_fill_stripe' => 'yes',
					'rael_progress_bar_layout'           => 'line',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'progress_bar_section_style_general_circle',
			array(
				'label'     => __( 'General', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_progress_bar_layout' => array( 'circle', 'half_circle', 'circle_fill', 'half_circle_fill' ),
				),
			)
		);

		$this->add_control(
			'rael_progress_bar_circle_alignment',
			array(
				'label'   => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default' => 'center',
			)
		);

		$this->add_control(
			'rael_progress_bar_circle_size',
			array(
				'label'      => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 50,
						'max'  => 500,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 200,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-progressbar-circle' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-progressbar-half-circle' => 'width: {{SIZE}}{{UNIT}}; height: calc({{SIZE}} / 2 * 1{{UNIT}});',
					'{{WRAPPER}} .rael-progressbar-half-circle-after' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-progressbar-circle-shadow' => 'width: calc({{SIZE}}{{UNIT}} + 20px); height: calc({{SIZE}}{{UNIT}} + 20px);',
				),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'rael_progress_bar_circle_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-progressbar-circle-inner' => 'background-color: {{VALUE}}',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_progress_bar_circle_stroke_width',
			array(
				'label'      => __( 'Stroke Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 12,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-progressbar-circle-inner' => 'border-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .rael-progressbar-circle-half' => 'border-width: {{SIZE}}{{UNIT}}',
				),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'rael_progress_bar_circle_stroke_color',
			array(
				'label'     => __( 'Stroke Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eee',
				'selectors' => array(
					'{{WRAPPER}} .rael-progressbar-circle-inner' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_progress_bar_circle_fill_color',
			array(
				'label'     => __( 'Fill Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .rael-progressbar-circle-half' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .rael-progressbar-circle-fill .rael-progressbar-circle-half' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .rael-progressbar-half-circle-fill .rael-progressbar-circle-half' => 'background-color: {{VALUE}}',
				),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'rael_progress_bar_circle_box_shadow',
				'label'     => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .rael-progressbar-circle-shadow',
				'condition' => array(
					'rael_progress_bar_layout' => 'circle',
				),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'progress_bar_section_style_general_box',
			array(
				'label'     => __( 'General', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_progress_bar_layout' => 'box',
				),
			)
		);

		$this->add_control(
			'rael_progress_bar_box_alignment',
			array(
				'label'   => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default' => 'center',
			)
		);

		$this->add_control(
			'rael_progress_bar_box_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 100,
						'max'  => 500,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 140,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-progressbar-box' => 'width: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'rael_progress_bar_box_height',
			array(
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 100,
						'max'  => 500,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 200,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-progressbar-box' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_progress_bar_box_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-progressbar-box' => 'background-color: {{VALUE}}',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_progress_bar_box_fill_color',
			array(
				'label'     => __( 'Fill Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .rael-progressbar-box-fill' => 'background-color: {{VALUE}}',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_progress_bar_box_stroke_width',
			array(
				'label'      => __( 'Stroke Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 1,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-progressbar-box' => 'border-width: {{SIZE}}{{UNIT}}',
				),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'rael_progress_bar_box_stroke_color',
			array(
				'label'     => __( 'Stroke Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eee',
				'selectors' => array(
					'{{WRAPPER}} .rael-progressbar-box' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'progress_bar_section_style_typography',
			array(
				'label' => __( 'Typography', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'progress_bar_title_typography',
				'label'    => __( 'Title', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .rael-progressbar-title',
			)
		);

		$this->add_control(
			'rael_progress_bar_title_color',
			array(
				'label'     => __( 'Title Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-progressbar-title' => 'color: {{VALUE}}',
				),
				'separator' => 'after',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'progress_bar_count_typography',
				'label'    => __( 'Counter', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .rael-progressbar-count-wrap',
			)
		);

		$this->add_control(
			'rael_progress_bar_count_color',
			array(
				'label'     => __( 'Counter Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-progressbar-count-wrap' => 'color: {{VALUE}}',
				),
				'separator' => 'after',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'progress_bar_after_typography',
				'label'     => __( 'Prefix/Postfix', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector'  => '{{WRAPPER}} .rael-progressbar-half-circle-after span',
				'condition' => array(
					'rael_progress_bar_layout' => 'half_circle',
				),
			)
		);

		$this->add_control(
			'rael_progress_bar_after_color',
			array(
				'label'     => __( 'Prefix/Postfix Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-progressbar-half-circle-after' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'rael_progress_bar_layout' => 'half_circle',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render function
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function render() {
		$settings       = $this->get_settings_for_display();
		$wrap_classes   = array( 'rael-progressbar' );
		$circle_wrapper = array();

		$settings['rael_progress_bar_title'] = wp_kses_post( $settings['rael_progress_bar_title'] );

		if ( 'circle' === $settings['rael_progress_bar_layout'] || 'circle_fill' === $settings['rael_progress_bar_layout'] ) {
			$wrap_classes[] = 'rael-progressbar-circle';
			if ( 'circle_fill' === $settings['rael_progress_bar_layout'] ) {
				$wrap_classes[] = 'rael-progressbar-circle-fill';
			}

			$this->add_render_attribute(
				'rael-progressbar-circle',
				array(
					'class'         => $wrap_classes,
					'data-layout'   => $settings['rael_progress_bar_layout'],
					'data-count'    => 'static' === $settings['rael_progress_bar_value_type'] ? $settings['rael_progress_bar_value']['size'] : $settings['rael_progress_bar_value_dynamic'],
					'data-duration' => $settings['rael_progress_bar_animation_duration']['size'],
				)
			);

			$rael_progress_bar_show_count = '';
			if ( 'yes' === $settings['rael_progress_bar_show_count'] ) {
				$rael_progress_bar_show_count = '<span class="rael-progressbar-count-wrap"><span class="rael-progressbar-count">0</span><span class="postfix">' . esc_html__( '%', 'responsive-addons-for-elementor' ) . '</span></span>';
			}

			echo '<div class="rael-progressbar-circle-container ' . esc_attr( wp_kses_post( $settings['rael_progress_bar_circle_alignment'] ) ) . '">
				<div class="rael-progressbar-circle-shadow">
					<div ' . wp_kses_post( $this->get_render_attribute_string( 'rael-progressbar-circle' ) ) . '>
						<div class="rael-progressbar-circle-pie">
							<div class="rael-progressbar-circle-half-left rael-progressbar-circle-half"></div>
							<div class="rael-progressbar-circle-half-right rael-progressbar-circle-half"></div>
						</div>
						<div class="rael-progressbar-circle-inner"></div>
						<div class="rael-progressbar-circle-inner-content">
							' . ( wp_kses_post( $settings['rael_progress_bar_title'] ) ? sprintf( '<%1$s class="%2$s">', esc_html( Helper::validate_html_tags( wp_kses_post( $settings['rael_progress_bar_title_html_tag'] ) ) ), 'rael-progressbar-title' ) . wp_kses_post( $settings['rael_progress_bar_title'] ) . sprintf( '</%1$s>', esc_html( Helper::validate_html_tags( wp_kses_post( $settings['rael_progress_bar_title_html_tag'] ) ) ) ) : '' ) . '
							' . wp_kses_post( $rael_progress_bar_show_count ) . '
						</div>
					</div>
				</div>
				</div>';
		}

		if ( 'half_circle' === $settings['rael_progress_bar_layout'] || 'half_circle_fill' === $settings['rael_progress_bar_layout'] ) {
			$wrap_classes[] = 'rael-progressbar-half-circle';
			if ( 'half_circle_fill' === $settings['rael_progress_bar_layout'] ) {
				$wrap_classes[] = 'rael-progressbar-half-circle-fill';
			}
			$this->add_render_attribute(
				'rael-progressbar-circle-half',
				array(
					'class' => 'rael-progressbar-circle-half',
					'style' => '-webkit-transition-duration:' . $settings['rael_progress_bar_animation_duration']['size'] . 'ms;-o-transition-duration:' . $settings['rael_progress_bar_animation_duration']['size'] . 'ms;transition-duration:' . $settings['rael_progress_bar_animation_duration']['size'] . 'ms;',
				)
			);

			$this->add_render_attribute(
				'rael-progressbar-half-circle',
				array(
					'class'         => $wrap_classes,
					'data-layout'   => $settings['rael_progress_bar_layout'],
					'data-count'    => 'static' === $settings['rael_progress_bar_value_type'] ? $settings['rael_progress_bar_value']['size'] : $settings['rael_progress_bar_value_dynamic'],
					'data-duration' => $settings['rael_progress_bar_animation_duration']['size'],
				)
			);

			echo '<div class="rael-progressbar-circle-container ' . wp_kses_post( $settings['rael_progress_bar_circle_alignment'] ) . '">
                <div ' . wp_kses_post( $this->get_render_attribute_string( 'rael-progressbar-half-circle' ) ) . '>
                    <div class="rael-progressbar-circle">
                        <div class="rael-progressbar-circle-pie">
                            <div ' . wp_kses_post( $this->get_render_attribute_string( 'rael-progressbar-circle-half' ) ) . '></div>
                        </div>
                        <div class="rael-progressbar-circle-inner"></div>
                    </div>
                    <div class="rael-progressbar-circle-inner-content">
                        ' . ( wp_kses_post( $settings['rael_progress_bar_title'] ) ? sprintf( '<%1$s class="%2$s">', wp_kses_post( Helper::validate_html_tags( $settings['rael_progress_bar_title_html_tag'] ) ), 'rael-progressbar-title' ) . wp_kses_post( $settings['rael_progress_bar_title'] ) . sprintf( '</%1$s>', wp_kses_post( Helper::validate_html_tags( $settings['rael_progress_bar_title_html_tag'] ) ) ) : '' ) . '
                        ' . ( 'yes' === wp_kses_post( $settings['rael_progress_bar_show_count'] ) ? '<span class="rael-progressbar-count-wrap"><span class="rael-progressbar-count">0</span><span class="postfix">' . esc_html__( '%', 'responsive-addons-for-elementor' ) . '</span></span>' : '' ) . '
                    </div>
                </div>
                <div class="rael-progressbar-half-circle-after">
                    ' . ( wp_kses_post( $settings['rael_progress_bar_prefix_label'] ) ? sprintf( '<span class="rael-progressbar-prefix-label">%1$s</span>', wp_kses_post( $settings['rael_progress_bar_prefix_label'] ) ) : '' ) . '
                    ' . ( wp_kses_post( $settings['rael_progress_bar_postfix_label'] ) ? sprintf( '<span class="rael-progressbar-postfix-label">%1$s</span>', wp_kses_post( $settings['rael_progress_bar_postfix_label'] ) ) : '' ) . '
                </div>
            </div>';
		}

		if ( 'line' === $settings['rael_progress_bar_layout'] || 'line_rainbow' === $settings['rael_progress_bar_layout'] ) {
			$wrap_classes[] = 'rael-progressbar-line';
			if ( 'line_rainbow' === $settings['rael_progress_bar_layout'] ) {
				$wrap_classes[] = 'rael-progressbar-line-rainbow';
			}

			if ( 'yes' === $settings['rael_progress_bar_line_fill_stripe'] ) {
				$wrap_classes[] = 'rael-progressbar-line-stripe';
			}

			if ( 'normal' === $settings['rael_progress_bar_line_fill_stripe_animate'] ) {
				$wrap_classes[] = 'rael-progressbar-line-animate';
			} elseif ( 'reverse' === $settings['rael_progress_bar_line_fill_stripe_animate'] ) {
				$wrap_classes[] = 'rael-progressbar-line-animate-rtl';
			}

			$this->add_render_attribute(
				'rael-progressbar-line',
				array(
					'class'         => $wrap_classes,
					'data-layout'   => 'line',
					'data-count'    => 'static' === $settings['rael_progress_bar_value_type'] ? $settings['rael_progress_bar_value']['size'] : $settings['rael_progress_bar_value_dynamic'],
					'data-duration' => $settings['rael_progress_bar_animation_duration']['size'],
				)
			);

			$this->add_render_attribute(
				'rael-progressbar-line-fill',
				array(
					'class' => 'rael-progressbar-line-fill',
					'style' => '-webkit-transition-duration:' . $settings['rael_progress_bar_animation_duration']['size'] . 'ms;-o-transition-duration:' . $settings['rael_progress_bar_animation_duration']['size'] . 'ms;transition-duration:' . $settings['rael_progress_bar_animation_duration']['size'] . 'ms;',
				)
			);

			echo '<div class="rael-progressbar-line-container ' . wp_kses_post( $settings['rael_progress_bar_line_alignment'] ) . '">
                ' . ( wp_kses_post( $settings['rael_progress_bar_title'] ) ? sprintf( '<%1$s class="%2$s">', wp_kses_post( Helper::validate_html_tags( $settings['rael_progress_bar_title_html_tag'] ) ), 'rael-progressbar-title' ) . wp_kses_post( $settings['rael_progress_bar_title'] ) . sprintf( '</%1$s>', wp_kses_post( Helper::validate_html_tags( $settings['rael_progress_bar_title_html_tag'] ) ) ) : '' ) . '

                <div ' . wp_kses_post( $this->get_render_attribute_string( 'rael-progressbar-line' ) ) . '>
                    ' . ( 'yes' === wp_kses_post( $settings['rael_progress_bar_show_count'] ) ? '<span class="rael-progressbar-count-wrap"><span class="rael-progressbar-count">0</span><span class="postfix">' . esc_html__( '%', 'responsive-addons-for-elementor' ) . '</span></span>' : '' ) . '
                    <span ' . wp_kses_post( $this->get_render_attribute_string( 'rael-progressbar-line-fill' ) ) . '></span>
                </div>
            </div>';
		}

		if ( 'box' === $settings['rael_progress_bar_layout'] ) {
			$wrap_classes[] = 'rael-progressbar-box';

			$this->add_render_attribute(
				'rael-progressbar-box',
				array(
					'class'         => $wrap_classes,
					'data-layout'   => $settings['rael_progress_bar_layout'],
					'data-count'    => 'static' === $settings['rael_progress_bar_value_type'] ? $settings['rael_progress_bar_value']['size'] : $settings['rael_progress_bar_value_dynamic'],
					'data-duration' => $settings['rael_progress_bar_animation_duration']['size'],
				)
			);

			$this->add_render_attribute(
				'rael-progressbar-box-fill',
				array(
					'class' => 'rael-progressbar-box-fill',
					'style' => '-webkit-transition-duration:' . $settings['rael_progress_bar_animation_duration']['size'] . 'ms;-o-transition-duration:' . $settings['rael_progress_bar_animation_duration']['size'] . 'ms;transition-duration:' . $settings['rael_progress_bar_animation_duration']['size'] . 'ms;',
				)
			);

			echo '<div class="rael-progressbar-box-container ' . wp_kses_post( $settings['rael_progress_bar_box_alignment'] ) . '">
				<div ' . wp_kses_post( $this->get_render_attribute_string( 'rael-progressbar-box' ) ) . '>
	                <div class="rael-progressbar-box-inner-content">
	                    ' . ( wp_kses_post( $settings['rael_progress_bar_title'] ) ? sprintf( '<%1$s class="%2$s">', wp_kses_post( Helper::validate_html_tags( $settings['rael_progress_bar_title_html_tag'] ) ), 'rael-progressbar-title' ) . wp_kses_post( $settings['rael_progress_bar_title'] ) . sprintf( '</%1$s>', wp_kses_post( Helper::validate_html_tags( $settings['rael_progress_bar_title_html_tag'] ) ) ) : '' ) . '
	                    ' . ( 'yes' === wp_kses_post( $settings['rael_progress_bar_show_count'] ) ? '<span class="rael-progressbar-count-wrap"><span class="rael-progressbar-count">0</span><span class="postfix">' . esc_html__( '%', 'responsive-addons-for-elementor' ) . '</span></span>' : '' ) . '
	                </div>
	                <div ' . wp_kses_post( $this->get_render_attribute_string( 'rael-progressbar-box-fill' ) ) . '></div>
	            </div>
            </div>';
		}
	}

}
