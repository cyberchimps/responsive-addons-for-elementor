<?php
/**
 * RAEL Divider.
 *
 * @since 1.4.0
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * RAEL Divider class.
 *
 * @since 1.4.0
 */
class Responsive_Addons_For_Elementor_Divider extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.4.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-divider';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.4.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Divider', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the divider widget belongs to.
	 *
	 * @since 1.4.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve divider widget icon.
	 *
	 * @since 1.4.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-divider rael-badge';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 1.4.0
	 *
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'rael', 'divider', 'shape divider', 'icon divider', 'image divider', 'splitter', 'separator' );
	}

	/**
	 * Get Custom help URL
	 *
	 * @since 1.4.0
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/divider';
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different control fields that allows the user to change and customize the widget settings.
	 *
	 * @since 1.4.0
	 *
	 * @access protected
	 */
	protected function register_controls() {
		// Content Tab.
		$this->register_content_tab_divider_section();

		// Style Tab.
		$this->register_style_tab_divider_section();
		$this->register_style_tab_text_section();
		$this->register_style_tab_icon_section();
		$this->register_style_tab_image_section();
	}

	/**
	 * Register Divider section controls under Content Tab.rael-
	 *
	 * @since 1.4.0
	 */
	protected function register_content_tab_divider_section() {
		$this->start_controls_section(
			'rael_content_tab_divider_section',
			array(
				'label' => __( 'Divider', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_divider_type',
			array(
				'label'       => __( 'Choose Layout', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'toggle'      => 'false',
				'options'     => array(
					'plain' => array(
						'title' => __( 'Plain', 'responsive-addons-for-elementor' ),
						'icon'  => 'fas fa-ellipsis-h',
					),
					'text'  => array(
						'title' => __( 'Text', 'responsive-addons-for-elementor' ),
						'icon'  => 'far fa-file-alt',
					),
					'icon'  => array(
						'title' => __( 'Icon', 'responsive-addons-for-elementor' ),
						'icon'  => 'fas fa-certificate',
					),
					'image' => array(
						'title' => __( 'Image', 'responsive-addons-for-elementor' ),
						'icon'  => 'far fa-images',
					),
				),
				'default'     => 'plain',
			)
		);

		$this->add_control(
			'rael_divider_direction',
			array(
				'label'   => __( 'Direction', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'horizontal' => __( 'Horizontal', 'responsive-addons-for-elementor' ),
					'vertical'   => __( 'Vertical', 'responsive-addons-for-elementor' ),
				),
				'default' => 'horizontal',
			)
		);

		$this->add_control(
			'rael_divider_position',
			array(
				'label'     => __( 'Divider Position', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex'  => array(
						'title' => __( 'Inline', 'responsive-addons-for-elementor' ),
						'icon'  => 'fas fa-ellipsis-v',
					),
					'block' => array(
						'title' => __( 'Block', 'responsive-addons-for-elementor' ),
						'icon'  => 'fas fa-bars',
					),
				),
				'default'   => 'flex',
				'togggle'   => false,
				'selectors' => array(
					'{{WRAPPER}} .rael-divider__content-wrapper' => 'display: {{VALUE}}; justify-content: center;',
				),
				'condition' => array(
					'rael_divider_direction' => 'horizontal',
					'rael_divider_type!'     => 'plain',
				),
			)
		);

		$this->add_control(
			'rael_divider_left_switch',
			array(
				'label'        => __( 'Show Left Divider', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'rael_divider_type!' => 'plain',
				),
			)
		);

		$this->add_control(
			'rael_divider_left_width',
			array(
				'label'      => __( 'Left Divider Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-divider__content-wrapper .rael-divider__border--left .rael-divider__border' => 'width: {{SIZE}}{{UNIT}}; margin: auto;',
				),
				'condition'  => array(
					'rael_divider_left_switch' => 'yes',
					'rael_divider_direction'   => 'horizontal',
					'rael_divider_type!'       => 'plain',
				),
			)
		);

		$this->add_control(
			'rael_divider_right_switch',
			array(
				'label'        => __( 'Show Right Divider', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'rael_divider_type!' => 'plain',
				),
			)
		);

		$this->add_control(
			'rael_divider_right_width',
			array(
				'label'      => __( 'Right Divider Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-divider__content-wrapper .rael-divider__border--right .rael-divider__border' => 'width: {{SIZE}}{{UNIT}}; margin: auto;',
				),
				'condition'  => array(
					'rael_divider_left_switch' => 'yes',
					'rael_divider_direction'   => 'horizontal',
					'rael_divider_type!'       => 'plain',
				),
			)
		);

		$this->add_control(
			'rael_divider_text',
			array(
				'label'     => __( 'Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_manager::TEXT,
				'default'   => __( 'Divider Text', 'responsive-addons-for-elementor' ),
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'rael_divider_type' => 'text',
				),
			)
		);

		$this->add_control(
			'rael_divider_icon_new',
			array(
				'label'            => __( 'Icon', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'rael_divider_icon',
				'default'          => array(
					'value'   => 'fas fa-circle',
					'library' => 'fa-solid',
				),
				'condition'        => array(
					'rael_divider_type' => 'icon',
				),
			)
		);

		$this->add_control(
			'rael_divider_text_html_tag',
			array(
				'label'     => __( 'HTML Tag', 'responsive-addons-for-elementor' ),
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
				'default'   => 'span',
				'condition' => array(
					'rael_divider_type' => 'text',
				),
			)
		);

		$this->add_control(
			'rael_divider_image',
			array(
				'label'     => __( 'Image', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'rael_divider_type' => 'image',
				),
			)
		);

		$this->add_responsive_control(
			'rael_divider_align',
			array(
				'label'     => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Divider section controls under Style Tab.
	 *
	 * @since 1.4.0
	 */
	protected function register_style_tab_divider_section() {
		$this->start_controls_section(
			'rael_style_tab_divider_section',
			array(
				'label' => __( 'Divider', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_divider_vertical_align',
			array(
				'label'                => __( 'Vertical Alignment', 'responsive-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'default'              => 'middle',
				'options'              => array(
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
				'selectors'            => array(
					'{{WRAPPER}} .rael-divider__content-wrapper' => 'align-items: {{VALUE}};',
				),
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'condition'            => array(
					'rael_divider_type!'     => 'plain',
					'rael_divider_direction' => 'horizontal',
				),
			)
		);

		$this->add_control(
			'rael_divider_style',
			array(
				'label'     => __( 'Style', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'solid'  => __( 'Solid', 'responsive-addons-for-elementor' ),
					'dashed' => __( 'Dashed', 'responsive-addons-for-elementor' ),
					'dotted' => __( 'Dotted', 'responsive-addons-for-elementor' ),
					'double' => __( 'Double', 'responsive-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-divider, {{WRAPPER}} .rael-divider__border' => 'border-style: {{VALUE}};',
				),
				'default'   => 'dashed',
			),
		);

		$this->add_responsive_control(
			'rael_divider_horizontal_height',
			array(
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 60,
					),
				),
				'default'    => array(
					'size' => 3,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-divider.rael-divider-direction--horizontal' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-divider__border' => 'border-top-width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_divider_direction' => 'horizontal',
				),
			)
		);

		$this->add_responsive_control(
			'rael_divider_vertical_height',
			array(
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 500,
					),
				),
				'default'    => array(
					'size' => 80,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-divider-wrapper.vertical .rael-divider__border' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-divider-wrapper.vertical .rael-divider.rael-divider-direction--vertical' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_divider_direction' => 'vertical',
				),
			)
		);

		$this->add_responsive_control(
			'rael_horizontal_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 1200,
					),
				),
				'default'    => array(
					'size' => 300,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-divider.rael-divider-direction--horizontal' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-divider__content-wrapper' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_divider_direction' => 'horizontal',
				),
			)
		);

		$this->add_responsive_control(
			'rael_vertical_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 3,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-divider-wrapper.vertical .rael-divider__border' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-divider-wrapper.vertical .rael-divider.rael-divider-direction--vertical' => 'border-width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_divider_direction' => 'vertical',
				),
			)
		);

		$this->add_control(
			'rael_divider_border_color',
			array(
				'label'     => __( 'Divider Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-divider, {{WRAPPER}} .rael-divider__border' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_divider_type' => 'plain',
				),
			)
		);

		$this->start_controls_tabs( 'rael_divider_style_tabs' );

		$this->start_controls_tab(
			'rael_divider_tab_before_style',
			array(
				'label'     => __( 'Before', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_divider_type!' => 'plain',
				),
			)
		);

		$this->add_control(
			'rael_divider_before_color',
			array(
				'label'     => __( 'Divider Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-divider__border--left .rael-divider__border' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_divider_type!' => 'plain',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_divider_tab_after_style',
			array(
				'label'     => __( 'After', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_divider_type!' => 'plain',
				),
			)
		);

		$this->add_control(
			'rael_divider_after_color',
			array(
				'label'     => __( 'Divider Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-divider__border--right .rael-divider__border' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_divider_type!' => 'plain',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register Text section controls under Style Tab.
	 *
	 * @since 1.4.0
	 */
	protected function register_style_tab_text_section() {
		$this->start_controls_section(
			'rael_style_tab_text_section',
			array(
				'label'     => __( 'Text', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_divider_type' => 'text',
				),
			)
		);

		$this->add_control(
			'rael_divider_text_position',
			array(
				'label'        => __( 'Position', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'      => 'center',
				'prefix_class' => 'rael-divider-content--',
			)
		);

		$this->add_control(
			'rael_divider_text_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'rael_divider_type' => 'text',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-divider__text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_divider_text_typography',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector'  => '{{WRAPPER}} .rael-divider__text',
				'condition' => array(
					'rael_divider_type' => 'text',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_divider_text_shadow',
				'selector' => '{{WRAPPER}} .rael-divider__text',
			)
		);

		$this->add_responsive_control(
			'rael_divider_text_spacing',
			array(
				'label'      => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'px' => array(
						'max' => 200,
					),
				),
				'condition'  => array(
					'rael_divider_type' => 'text',
				),
				'selectors'  => array(
					'{{WRAPPER}}.rael-divider-content--center .rael-divider-wrapper.horizontal .rael-divider__content' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-divider-content--left .rael-divider-wrapper.horizontal .rael-divider__content' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-divider-content--right .rael-divider-wrapper.horizontal .rael-divider__content' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-divider-content--center .rael-divider-wrapper.vertical .rael-divider__content' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-divider-content--left .rael-divider-wrapper.vertical .rael-divider__content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-divider-content--right .rael-divider-wrapper.vertical .rael-divider__content' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Icon section controls under Style Tab.
	 *
	 * @since 1.4.0
	 */
	protected function register_style_tab_icon_section() {
		$this->start_controls_section(
			'rael_style_tab_icon_section',
			array(
				'label'     => __( 'Icon', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_divider_type' => 'icon',
				),
			)
		);

		$this->add_control(
			'rael_divider_icon_position',
			array(
				'label'        => __( 'Position', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'condition'    => array(
					'rael_divider_type' => 'icon',
				),
				'default'      => 'center',
				'prefix_class' => 'rael-divider-content--',
			)
		);

		$this->add_control(
			'rael_divider_icon_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'rael_divider_type' => 'icon',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-divider__icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_divider_icon_size',
			array(
				'label'      => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'px' => array(
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 16,
					'unit' => 'px',
				),
				'condition'  => array(
					'rael_divider_type' => 'icon',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-divider__icon'     => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-divider__svg-icon' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_divider_icon_rotation',
			array(
				'label'      => __( 'Icon Rotation', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'px' => array(
						'max' => 360,
					),
				),
				'default'    => array(
					'unit' => 'px',
				),
				'condition'  => array(
					'rael_divider_type' => 'icon',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-divider__icon i'   => 'transform: rotate( {{SIZE}}deg );',
					'{{WRAPPER}} .rael-divider__svg-icon' => 'transform: rotate( {{SIZE}}deg );',
				),
			)
		);

		$this->add_responsive_control(
			'rael_divider_icon_spacing',
			array(
				'label'      => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'px' => array(
						'max' => 200,
					),
				),
				'condition'  => array(
					'rael_divider_type' => 'icon',
				),
				'selectors'  => array(
					'{{WRAPPER}}.rael-divider-content--center .rael-divider-wrapper.horizontal .rael-divider__content' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-divider-content--left .rael-divider-wrapper.horizontal .rael-divider__content' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-divider-content--right .rael-divider-wrapper.horizontal .rael-divider__content' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-divider-content--center .rael-divider-wrapper.vertical .rael-divider__content' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-divider-content--left .rael-divider-wrapper.vertical .rael-divider__content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-divider-content--right .rael-divider-wrapper.vertical .rael-divider__content' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Image section controls under Style Tab.
	 *
	 * @since 1.4.0
	 */
	protected function register_style_tab_image_section() {
		$this->start_controls_section(
			'rael_style_tab_image_section',
			array(
				'label'     => __( 'Image', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_divider_type' => 'image',
				),
			)
		);

		$this->add_control(
			'rael_divider_image_position',
			array(
				'label'        => __( 'Position', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'condition'    => array(
					'rael_divider_type' => 'image',
				),
				'default'      => 'center',
				'prefix_class' => 'rael-divider-content--',
			)
		);

		$this->add_responsive_control(
			'rael_divider_image_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'px' => array(
						'max' => 1200,
					),
				),
				'default'    => array(
					'size' => 80,
					'unit' => 'px',
				),
				'condition'  => array(
					'rael_divider_type' => 'image',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-divider__image' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_divider_image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( '%', 'px' ),
				'condition'  => array(
					'rael_divider_type' => 'image',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-divider__image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_divider_image_spacing',
			array(
				'label'      => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'px' => array(
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => 80,
					'unit' => 'px',
				),
				'condition'  => array(
					'rael_divider_type' => 'image',
				),
				'selectors'  => array(
					'{{WRAPPER}}.rael-divider-content--center .rael-divider-wrapper.horizontal .rael-divider__content' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-divider-content--left .rael-divider-wrapper.horizontal .rael-divider__content' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-divider-content--right .rael-divider-wrapper.horizontal .rael-divider__content' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-divider-content--center .rael-divider-wrapper.vertical .rael-divider__content' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-divider-content--left .rael-divider-wrapper.vertical .rael-divider__content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-divider-content--right .rael-divider-wrapper.vertical .rael-divider__content' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.4.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings      = $this->get_settings_for_display();
		$icon_migrated = isset( $settings['__fa4_migrated']['rael_divider_icon_new'] );
		$icon_is_new   = empty( $settings['rael_divider_icon'] );

		$this->add_render_attribute(
			'rael_divider',
			'class',
			'rael-divider'
		);

		if ( isset($settings['rael_divider_direction']) ) {
			$this->add_render_attribute( 'rael_divider', 'class', 'rael-divider-direction--' . $settings['rael_divider_direction'] );
		}

		if ( isset($settings['rael_divider_style']) ) {
			$this->add_render_attribute( 'rael_divider', 'class', 'rael-divider-style--' . $settings['rael_divider_style'] );
		}

		$this->add_render_attribute( 'rael_divider_content', 'class', 'rael-divider__' . $settings['rael_divider_type'] );

		$this->add_inline_editing_attributes( 'rael_divider_text', 'none' );
		$this->add_render_attribute( 'rael_divider_text', 'class', 'rael-divider__' . $settings['rael_divider_type'] );

		$this->add_render_attribute(
			'rael_divider_wrapper',
			array(
				'class' => array(
					'rael-divider-wrapper',
					$settings['rael_divider_direction'],
				),
			)
		);

		?>

		<div <?php $this->print_render_attribute_string( 'rael_divider_wrapper' ); ?>>
			<?php
			if ( 'plain' === $settings['rael_divider_type'] ) :
				?>
				<div <?php $this->print_render_attribute_string( 'rael_divider' ); ?>></div>
				<?php
			else :
				?>
				<div class="rael-divider-container">
					<div class="rael-divider__content-wrapper">
						<?php if ( 'yes' === $settings['rael_divider_left_switch'] ) : ?>
							<span class="rael-divider__border-wrapper rael-divider__border--left">
								<span class="rael-divider__border"></span>
							</span>
						<?php endif; ?>
						<span class="rael-divider__content">
							<?php if ( $settings['rael_divider_type'] && 'text' === $settings['rael_divider_type'] ) : ?>
								<?php printf( '<%1$s %2$s>%3$s</%1$s>', wp_kses_post( Helper::validate_html_tags( $settings['rael_divider_text_html_tag'] ) ), wp_kses_post( $this->get_render_attribute_string( 'rael_divider_text' ) ), wp_kses_post( $settings['rael_divider_text'] ) ); ?>
							<?php elseif ( 'icon' === $settings['rael_divider_type'] ) : ?>
								<span <?php $this->print_render_attribute_string( 'rael_divider_content' ); ?>>
									<?php if ( $icon_migrated || $icon_is_new ) : ?>
										<?php if ( isset( $settings['rael_divider_icon_new']['value']['url'] ) ) : ?>
											<img class="rael-divider__svg-icon" src="<?php echo esc_attr( $settings['rael_divider_icon_new']['value']['url'] ); ?>" alt="<?php echo esc_attr( get_post_meta( $settings['rael_divider_icon_new']['value']['id'], '_wp_attachment_image_alt', true ) ); ?>" />
										<?php else : ?>
											<i class="<?php echo esc_attr( $settings['rael_divider_icon_new']['value'] ); ?>" aria-hidden="true"></i>
										<?php endif; ?>
									<?php else : ?>
										<i class="<?php echo esc_attr( $settings['rael_divider_icon']['value'] ); ?>" aria-hidden="true"></i>
									<?php endif; ?>
								</span>
							<?php elseif ( 'image' === $settings['rael_divider_type'] ) : ?>
								<span <?php $this->print_render_attribute_string( 'rael_divider_content' ); ?>>
									<?php if ( isset( $settings['rael_divider_image']['url'] ) ) : ?>
										<img src="<?php echo esc_url( $settings['rael_divider_image']['url'] ); ?>" alt="<?php echo esc_attr( get_post_meta( $settings['rael_divider_image']['id'], '_wp_attachment_image_alt', true ) ); ?>">
									<?php endif; ?>
								</span>
							<?php endif; ?>
						</span>
						<?php if ( 'yes' === $settings['rael_divider_right_switch'] ) : ?>
							<span class="rael-divider__border-wrapper rael-divider__border--right">
								<span class="rael-divider__border"></span>
							</span>
						<?php endif; ?>
					</div>
				</div>
				<?php
			endif;
			?>
		</div>
		<?php
	}
}
