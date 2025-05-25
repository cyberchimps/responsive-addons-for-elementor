<?php
/**
 * RAEL Lottie Widget
 *
 * @since 1.3.1
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * RAEL Lottie class
 *
 * @since 1.3.1
 */
class Responsive_Addons_For_Elementor_Lottie extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.3.1
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-lottie';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.3.1
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Lottie', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve timeline widget icon.
	 *
	 * @since 1.3.1
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-lottie rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the lottie widget belongs to.
	 *
	 * @since 1.3.1
	 *
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
	 * @since 1.3.1
	 *
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'lottie', 'animation', 'anime', 'gif', 'svg', 'canvas' );
	}

	/**
	 * Get Custom help URL
	 *
	 * @since 1.3.1
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/lottie';
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.3.1
	 *
	 * @access protected
	 */
	protected function register_controls() {
		// Content Tab.
		$this->register_content_tab_lottie_section();
		$this->register_content_tab_settings_section();

		// Style Tab.
		$this->register_style_tab_animation_section();
		$this->register_style_tab_caption_section();
	}

	/**
	 * Register Lottie section control under Content Tab.
	 *
	 * @since 1.3.1
	 *
	 * @access public
	 */
	public function register_content_tab_lottie_section() {
		$this->start_controls_section(
			'rael_content_tab_lottie_section',
			array(
				'label' => __( 'Lottie', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_lottie_source',
			array(
				'label'              => __( 'Source', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'media_file',
				'options'            => array(
					'media_file'   => __( 'Media File', 'responsive-addons-for-elementor' ),
					'external_url' => __( 'External URL', 'responsive-addons-for-elementor' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_lottie_source_external_url',
			array(
				'label'              => __( 'External URL', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::URL,
				'placeholder'        => __( 'Enter your URL', 'responsive-addons-for-elementor' ),
				'frontend_available' => true,
				'dynamic'            => array(
					'active' => true,
				),
				'condition'          => array(
					'rael_lottie_source' => 'external_url',
				),
			)
		);

		$this->add_control(
			'rael_lottie_source_json',
			array(
				'label'              => __( 'Upload JSON File', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::MEDIA,
				'media_type'         => 'application/json',
				'frontend_available' => true,
				'condition'          => array(
					'rael_lottie_source' => 'media_file',
				),
			)
		);

		$this->add_responsive_control(
			'rael_lottie_align',
			array(
				'label'        => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'flex-start' => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'      => 'center',
				'prefix_class' => 'rael-lottie--align-%s',
			)
		);

		$this->add_control(
			'rael_lottie_caption_source',
			array(
				'label'              => __( 'Caption', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'none',
				'options'            => array(
					'none'    => __( 'None', 'responsive-addons-for-elementor' ),
					'title'   => __( 'Title', 'responsive-addons-for-elementor' ),
					'caption' => __( 'Caption', 'responsive-addons-for-elementor' ),
					'custom'  => __( 'Custom', 'responsive-addons-for-elementor' ),
				),
				'condition'          => array(
					'rael_lottie_source!'           => 'external_url',
					'rael_lottie_source_json[url]!' => '',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_lottie_caption',
			array(
				'label'              => __( 'Custom Caption', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::TEXT,
				'render_type'        => 'none',
				'conditions'         => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'  => 'rael_lottie_caption_source',
							'value' => 'custom',
						),
						array(
							'name'  => 'rael_lottie_source',
							'value' => 'external_url',
						),
					),
				),
				'dynamic'            => array(
					'active' => true,
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_lottie_link',
			array(
				'label'              => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => array(
					'none'   => __( 'None', 'responsive-addons-for-elementor' ),
					'custom' => __( 'Custom URL', 'responsive-addons-for-elementor' ),
				),
				'default'            => 'none',
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_lottie_custom_link',
			array(
				'label'              => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::URL,
				'render_type'        => 'none',
				'placeholder'        => __( 'Enter your URL', 'responsive-addons-for-elementor' ),
				'condition'          => array(
					'rael_lottie_link' => 'custom',
				),
				'dynamic'            => array(
					'active' => true,
				),
				'default'            => array(
					'url' => '',
				),
				'show_label'         => false,
				'frontend_available' => true,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Settings section controls under Content Tab.
	 *
	 * @since 1.3.1
	 *
	 * @access public
	 */
	public function register_content_tab_settings_section() {
		$this->start_controls_section(
			'register_content_tab_settings_section',
			array(
				'label' => __( 'Settings', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_lottie_trigger',
			array(
				'label'              => __( 'Trigger', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'viewport',
				'options'            => array(
					'viewport' => __( 'Viewport', 'responsive-addons-for-elementor' ),
					'on_click' => __( 'On Click', 'responsive-addons-for-elementor' ),
					'on_hover' => __( 'On Hover', 'responsive-addons-for-elementor' ),
					'scroll'   => __( 'Scroll', 'responsive-addons-for-elementor' ),
					'none'     => __( 'None', 'responsive-addons-for-elementor' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_lottie_viewport',
			array(
				'label'              => __( 'Viewport', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'conditions'         => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'rael_lottie_trigger',
							'operator' => '===',
							'value'    => 'viewport',
						),
						array(
							'name'     => 'rael_lottie_trigger',
							'operator' => '===',
							'value'    => 'scroll',
						),
					),
				),
				'default'            => array(
					'sizes' => array(
						'start' => 0,
						'end'   => 100,
					),
					'unit'  => '%',
				),
				'labels'             => array(
					__( 'Bottom', 'responsive-addons-for-elementor' ),
					__( 'Top', 'responsive-addons-for-elementor' ),
				),
				'render_type'        => 'none',
				'scales'             => 1,
				'handles'            => 'range',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_lottie_effects_relative_to',
			array(
				'label'              => __( 'Effects Relative To', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'render_type'        => 'none',
				'default'            => 'viewport',
				'options'            => array(
					'viewport' => __( 'Viewport', 'responsive-addons-for-elementor' ),
					'page'     => __( 'Entire Page', 'responsive-addons-for-elementor' ),
				),
				'condition'          => array(
					'rael_lottie_trigger' => 'scroll',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_lottie_loop',
			array(
				'label'              => __( 'Loop', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'render_type'        => 'none',
				'return_value'       => 'yes',
				'default'            => 'yes',
				'frontend_available' => true,
				'condition'          => array(
					'rael_lottie_trigger!' => 'scroll',
				),
			)
		);

		$this->add_control(
			'rael_lottie_loop_times',
			array(
				'label'              => __( 'Times', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'render_type'        => 'none',
				'min'                => 0,
				'step'               => 1,
				'conditions'         => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_lottie_trigger',
							'operator' => '!==',
							'value'    => 'scroll',
						),
						array(
							'name'     => 'rael_lottie_loop',
							'operator' => '===',
							'value'    => 'yes',
						),
					),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_lottie_link_timeout',
			array(
				'label'              => __( 'Link Timeout', 'responsive-addons-for-elementor' ) . ' (ms)',
				'type'               => Controls_Manager::NUMBER,
				'description'        => __( 'Redirect to link after selected timeout', 'responsive-addons-for-elementor' ),
				'min'                => 0,
				'max'                => 5000,
				'step'               => 1,
				'render_type'        => 'none',
				'conditions'         => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_lottie_link',
							'operator' => '===',
							'value'    => 'custom',
						),
						array(
							'name'     => 'rael_lottie_trigger',
							'operator' => '===',
							'value'    => 'on_click',
						),
						array(
							'name'     => 'rael_lottie_custom_link[url]',
							'operator' => '!==',
							'value'    => '',
						),
					),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_lottie_on_hover_out',
			array(
				'label'              => __( 'On Hover Out', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'render_type'        => 'none',
				'default'            => 'default',
				'options'            => array(
					'default' => __( 'Default', 'responsive-addons-for-elementor' ),
					'reverse' => __( 'Reverse', 'responsive-addons-for-elementor' ),
					'pause'   => __( 'Pause', 'responsive-addons-for-elementor' ),
				),
				'condition'          => array(
					'rael_lottie_trigger' => 'on_hover',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_lottie_hover_area',
			array(
				'label'              => __( 'Hover Area', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'render_type'        => 'none',
				'default'            => 'animation',
				'options'            => array(
					'animation' => __( 'Animation', 'responsive-addons-for-elementor' ),
					'column'    => __( 'Column', 'responsive-addons-for-elementor' ),
					'section'   => __( 'Section', 'responsive-addons-for-elementor' ),
				),
				'condition'          => array(
					'rael_lottie_trigger' => 'on_hover',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_lottie_animation_play_speed',
			array(
				'label'              => __( 'Play Speed', 'responsive-addons-for-elementor' ) . ' (x)',
				'type'               => Controls_Manager::SLIDER,
				'render_type'        => 'none',
				'size_units'         => array( 'px' ),
				'range'              => array(
					'px' => array(
						'min'  => 0.1,
						'max'  => 5,
						'step' => 0.1,
					),
				),
				'default'            => array(
					'unit' => 'px',
					'size' => 1,
				),
				'dynamic'            => array(
					'active' => true,
				),
				'condition'          => array(
					'rael_lottie_trigger!' => 'scroll',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_lottie_start_point',
			array(
				'label'              => __( 'Start Point', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'frontend_available' => true,
				'size_units'         => array( '%' ),
				'render_type'        => 'none',
				'default'            => array(
					'size' => '0',
					'unit' => '%',
				),
			)
		);

		$this->add_control(
			'rael_lottie_end_point',
			array(
				'label'              => __( 'End Point', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( '%' ),
				'render_type'        => 'none',
				'default'            => array(
					'size' => '100',
					'unit' => '%',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_lottie_reverse_animation',
			array(
				'label'              => __( 'Reverse', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'render_type'        => 'none',
				'conditions'         => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_lottie_trigger',
							'operator' => '!==',
							'value'    => 'scroll',
						),
						array(
							'name'     => 'rael_lottie_trigger',
							'operator' => '!==',
							'value'    => 'on_hover',
						),
					),
				),
				'default'            => '',
				'return_value'       => 'yes',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_lottie_renderer',
			array(
				'label'              => __( 'Renderer', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'svg',
				'options'            => array(
					'svg'    => __( 'SVG', 'responsive-addons-for-elementor' ),
					'canvas' => __( 'Canvas', 'responsive-addons-for-elementor' ),
				),
				'separator'          => 'before',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_lottie_lazyload',
			array(
				'label'              => __( 'Lazy Load', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'default'            => '',
				'frontend_available' => true,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Animation section controls under Style Tab.
	 *
	 * @since 1.3.1
	 *
	 * @access public
	 */
	public function register_style_tab_animation_section() {
		$this->start_controls_section(
			'rael_style_tab_animation_section',
			array(
				'label' => __( 'Animation', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_lottie_animation_width',
			array(
				'label'          => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'size_units'     => array( 'px', 'vw' ),
				'range'          => array(
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .rael-lottie__container' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'rael_animation_style_tabs' );

		// Normal Tab.
		$this->start_controls_tab(
			'rael_lottie_animation_normal_tab',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_animation_normal_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-lottie__container' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_animation_normal_opacity',
			array(
				'label'       => __( 'Opacity', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'range'       => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => '1',
				),
				'selectors'   => array(
					'{{WRAPPER}} .rael-lottie__container' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'rael_animation_normal_css_filter',
				'selector' => '{{WRAPPER}} .rael-lottie__container',
			)
		);

		$this->end_controls_tab();
		// Normal Tab Ended.

		// Hover Tab.
		$this->start_controls_tab(
			'rael_lottie_animation_hover_tab',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_animation_hover_background_color',
			array(
				'label'     => __( 'Background Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-lottie__container:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_animation_hover_opacity',
			array(
				'label'       => __( 'Opacity', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'range'       => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => '1',
				),
				'selectors'   => array(
					'{{WRAPPER}} .rael-lottie__container:hover' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'rael_animation_hover_css_filter',
				'selector' => '{{WRAPPER}} .rael-lottie__container:hover',
			)
		);

		$this->end_controls_tab();
		// Hover Tab Ended.

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'rael_lottie_animation_border',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .rael-lottie__container',
			)
		);

		$this->add_control(
			'rael_lottie_animation_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-lottie__container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_lottie_animation_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-lottie__container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Caption section controls under Style Tab.
	 *
	 * @since 1.3.1
	 *
	 * @access public
	 */
	public function register_style_tab_caption_section() {
		$this->start_controls_section(
			'rael_style_tab_caption_section',
			array(
				'label'      => __( 'Caption', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'rael_lottie_caption_source',
							'operator' => '!==',
							'value'    => 'none',
						),
						array(
							'name'     => 'rael_lottie_caption',
							'operator' => '!==',
							'value'    => '',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_lottie_caption_align',
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
					'{{WRAPPER}} .rael-lottie__caption' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_lottie_caption_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-lottie__caption' => 'color: {{VALUE}};',
				),
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_lottie_caption_typography',
				'selector' => '{{WRAPPER}} .rael-lottie__caption',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
			)
		);

		$this->add_responsive_control(
			'rael_lottie_caption_spacing',
			array(
				'label'     => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'   => array(
					'unit' => 'px',
					'size' => '0',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-lottie__caption' => 'margin-top: {{SIZE}}{{UNIT}};',
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
	 * @since 1.3.1
	 *
	 * @access protected
	 */
	protected function render() {
		$settings         = $this->get_settings_for_display();
		$caption          = $this->get_caption( $settings );
		$widget_caption   = $caption ? '<p class="rael-lottie__caption">' . $caption . '</p>' : '';
		$widget_container = '<div class="rael-lottie__container"><div class="rael-lottie__animation"></div>' . $widget_caption . '</div>';

		if ( ! empty( $settings['rael_lottie_custom_link']['url'] ) && 'custom' === $settings['rael_lottie_link'] ) {
			$this->add_link_attributes( 'rael_lottie_custom_link', $settings['rael_lottie_custom_link'] );
			$widget_container = sprintf( '<a class="rael-lottie__container-link" %1$s>%2$s</a>', $this->get_render_attribute_string( 'rael_lottie_custom_link' ), $widget_container );
		}

		echo '<div class="rael-lottie-wrapper">' . wp_kses_post( $widget_container ) . '</div>';
	}

	/**
	 * Get caption element.
	 *
	 * @since 1.3.1
	 *
	 * @access protected
	 *
	 * @param array $settings Widget settings.
	 *
	 * @return string Caption element HTML or empty string.
	 */
	protected function get_caption( $settings ) {
		$is_media_file_caption   = $this->is_media_file_caption( $settings );
		$is_external_url_caption = $this->is_external_url_caption( $settings );

		if ( ( $is_media_file_caption && 'custom' === $settings['rael_lottie_caption_source'] ) || $is_external_url_caption ) {
			return $settings['rael_lottie_caption'];
		} elseif ( 'caption' === $settings['rael_lottie_caption_source'] ) {
			return wp_get_attachment_caption( $settings['rael_lottie_source_json']['id'] );
		} elseif ( 'title' === $settings['rael_lottie_caption_source'] ) {
			return get_the_title( $settings['rael_lottie_source_json']['id'] );
		}

		return '';
	}

	/**
	 * Whether caption is added while source is media file.
	 *
	 * @since 1.3.1
	 *
	 * @access protected
	 *
	 * @param array $settings Widget settings.
	 *
	 * @return boolean
	 */
	protected function is_media_file_caption( $settings ) {
		return 'media_file' === $settings['rael_lottie_source'] && 'none' !== $settings['rael_lottie_caption_source'];
	}

	/**
	 * Whether caption is added while source is from external url.
	 *
	 * @since 1.3.1
	 *
	 * @access protected
	 *
	 * @param array $settings Widget settings.
	 *
	 * @return boolean
	 */
	protected function is_external_url_caption( $settings ) {
		return 'external_url' === $settings['rael_lottie_source'] && '' !== $settings['rael_lottie_caption'];
	}

	/**
	 * Render on the editor for live preview.
	 *
	 * @since 1.3.1
	 *
	 * @access protected
	 *
	 * @return void
	 */
	protected function content_template() {
		?>
		<#
		var ensureAttachmentData = function( id, type ) {
			if ( 'caption' === type || 'title' === type ) {
				if ( 'undefined' === typeof wp.media.attachment( id ).get( type ) ) {
					wp.media.attachment( id ).fetch().then( function( data ) {
						view.render();
					} );
				}
			}
		};

		var getAttachmentData = function( id, type ) {
			if ( id && ( 'caption' === type || 'title' === type ) ) {
				ensureAttachmentData( id, type );
				return wp.media.attachment( id ).get( type );
			}

			return '';
		};

		var getCaption = function() {
			if ( ( isMediaFileCaption() && 'custom' === settings.rael_lottie_caption_source ) || isExternalUrlCaption() ) {
				return settings.rael_lottie_caption;
			} else if ( 'caption' === settings.rael_lottie_caption_source || 'title' === settings.rael_lottie_caption_source ) {
				return getAttachmentData( settings.rael_lottie_source_json.id, settings.rael_lottie_caption_source );
			}

			return '';
		};

		var isMediaFileCaption = function() {
			return 'media_file' === settings.rael_lottie_source && 'none' !== settings.rael_lottie_caption_source;
		};

		var isExternalUrlCaption = function() {
			return 'external_url' === settings.rael_lottie_source && '' !== settings.rael_lottie_caption;
		};

		var widget_caption = getCaption() ? '<p class="rael-lottie__caption">' + getCaption() + '</p>' : '';
		var widget_container = '<div class="rael-lottie__container"><div class="rael-lottie__animation"></div>' + widget_caption + '</div>';

		if ( settings.rael_lottie_custom_link.url && 'custom' === settings.rael_lottie_link ) {
			widget_container = '<a class="rael-lottie__container-link" href="' + settings.rael_lottie_custom_link.url + '">' + widget_container + '</a>';
		}

		print( '<div class="rael-lottie-wrapper">' + widget_container + '</div>' );
		#>
		<?php
	}
}
