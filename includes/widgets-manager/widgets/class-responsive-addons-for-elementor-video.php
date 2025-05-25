<?php
/**
 * RAE Video widget
 *
 * @since   1.0.0
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}
/**
 * RAE Video widget class.
 *
 * @since 1.0.0
 */
class Responsive_Addons_For_Elementor_Video extends Widget_Base {


	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-video';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Video', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Video widget icon.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-youtube rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the Video widget belongs to.
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
	 * This function is used to return the keywords.
	 */
	public function get_keywords() {
		return array( 'video', 'play', 'player', 'vimeo', 'youtube' );
	}
	/**
	 * This function helps in getting the scripts.
	 */
	public function get_script_depends() {
		return array( 'rael-magnific-popup' );
	}
	/**
	 * This function is used to return the help url.
	 */
	public function get_help_url() {
		return 'https://cyberchimps.com/docs/widgets';
	}
	/**
	 * This function is used to register the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'rael_video_popup_content_section',
			array(
				'label' => esc_html__( 'Video', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_video_popup_button_icons',
			array(
				'label'            => esc_html__( 'Button Icon', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'rael_video_popup_button_icon',
				'default'          => array(
					'value'   => 'fas fa-play',
					'library' => 'fa-solid',
				),
				'label_block'      => true,
			)
		);

		$this->add_control(
			'rael_video_glow',
			array(
				'label'        => esc_html__( 'Active Glow', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'rael_video_popup_type',
			array(
				'label'   => esc_html__( 'Video Type', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'youtube',
				'options' => array(
					'youtube' => esc_html__( 'youtube', 'responsive-addons-for-elementor' ),
					'vimeo'   => esc_html__( 'vimeo', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_video_popup_url',
			array(
				'label'       => esc_html__( 'URL to embed', 'responsive-addons-for-elementor' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'input_type'  => 'url',
				'placeholder' => esc_html( 'https://www.youtube.com/watch?v=OI3gGmJzhVM' ),
				'default'     => esc_html( 'https://www.youtube.com/watch?v=OI3gGmJzhVM' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'rael_video_popup_start_time',
			array(
				'label'       => esc_html__( 'Start Time', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'input_type'  => 'number',
				'placeholder' => '',
				'default'     => '0',
				'condition'   => array( 'rael_video_popup_type' => 'youtube' ),
			)
		);

		$this->add_control(
			'rael_video_popup_end_time',
			array(
				'label'       => esc_html__( 'End Time', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'input_type'  => 'number',
				'placeholder' => '',
				'default'     => '',
				'condition'   => array( 'rael_video_popup_type' => 'youtube' ),
			)
		);

		$this->add_control(
			'rael_video_popup_auto_play',
			array(
				'label'        => esc_html__( 'Auto Play', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => '1',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'rael_video_popup_mute',
			array(
				'label'        => esc_html__( 'Mute', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => '1',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'rael_video_popup_video_loop',
			array(
				'label'        => esc_html__( 'Loop', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => '1',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'rael_video_popup_video_player_control',
			array(
				'label'        => esc_html__( 'Player Control', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => '1',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'rael_video_popup_video_intro_title',
			array(
				'label'        => esc_html__( 'Intro Title', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => '1',
				'default'      => 'no',
				'condition'    => array( 'rael_video_popup_type' => 'vimeo' ),
			)
		);

		$this->add_control(
			'rael_video_popup_video_intro_portrait',
			array(
				'label'        => esc_html__( 'Intro Portrait', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => '1',
				'default'      => 'no',
				'condition'    => array( 'rael_video_popup_type' => 'vimeo' ),
			)
		);

		$this->add_control(
			'rael_video_popup_video_intro_byline',
			array(
				'label'        => esc_html__( 'Intro Byline', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => '1',
				'default'      => 'no',
				'condition'    => array( 'rael_video_popup_type' => 'vimeo' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_video_popup_style_section',
			array(
				'label' => esc_html__( 'Wrapper Style', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'ekit_video_popup_title_align',
			array(
				'label'     => esc_html__( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(

					'left'    => array(
						'title' => esc_html__( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .rael-video-content' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_video_wrap_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-video-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_video_wrap_border',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-video-content',
			)
		);

		$this->add_control(
			'rael_video_wrap_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-video-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_video_popup_section_style',
			array(
				'label' => esc_html__( 'Button Style', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_video_popup_text_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-video-popup-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_video_popup_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 5,
					),
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-video-popup-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-video-popup-btn svg' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_video_popup_btn_use_height_and_width',
			array(
				'label'        => esc_html__( 'Use height width', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => esc_html__( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_responsive_control(
			'rael_video_popup_btn_width',
			array(
				'label'      => esc_html__( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 30,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 60,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-video-popup-btn' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_video_popup_btn_use_height_and_width' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_video_popup_btn_height',
			array(
				'label'      => esc_html__( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 30,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 60,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-video-popup-btn' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_video_popup_btn_use_height_and_width' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_video_popup_btn_line_height',
			array(
				'label'      => esc_html__( 'Line height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 30,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 45,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-video-popup-btn' => 'line-height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_video_popup_btn_use_height_and_width' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_video_popup_btn_glow_color',
			array(
				'label'     => esc_html__( 'Glow Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-video-popup-btn.glow-btn:before' => 'color: {{VALUE}}',
					'{{WRAPPER}} .rael-video-popup-btn.glow-btn:after' => 'color: {{VALUE}}',
					'{{WRAPPER}} .rael-video-popup-btn.glow-btn > i:after' => 'color: {{VALUE}}',
				),
				'default'   => '#255cff',
				'condition' => array(
					'rael_video_glow' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'rael_video_popup_button_style_tabs' );

		$this->start_controls_tab(
			'rael_video_popup_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_video_popup_btn_text_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-video-popup-btn' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-video-popup-btn svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_video_popup_btn_bg_color',
				'default'  => '',
				'selector' => '{{WRAPPER}} .rael-video-popup-btn',
			)
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'rael_video_popup_btn_tab_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_video_popup_btn_hover_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-video-popup-btn:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-video-popup-btn:hover svg path'   => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_video_popup_btn_bg_hover_color',
				'default'  => '',
				'selector' => '{{WRAPPER}} .rael-video-popup-btn:hover',
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_video_popup_border_style',
			array(
				'label' => esc_html__( 'Border Style', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_video_popup_btn_border_style',
			array(
				'label'     => esc_html_x( 'Border Type', 'Border Control', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''       => esc_html__( 'None', 'responsive-addons-for-elementor' ),
					'solid'  => esc_html_x( 'Solid', 'Border Control', 'responsive-addons-for-elementor' ),
					'double' => esc_html_x( 'Double', 'Border Control', 'responsive-addons-for-elementor' ),
					'dotted' => esc_html_x( 'Dotted', 'Border Control', 'responsive-addons-for-elementor' ),
					'dashed' => esc_html_x( 'Dashed', 'Border Control', 'responsive-addons-for-elementor' ),
					'groove' => esc_html_x( 'Groove', 'Border Control', 'responsive-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-video-popup-btn' => 'border-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_video_popup_btn_border_dimensions',
			array(
				'label'     => esc_html_x( 'Width', 'Border Control', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .rael-video-popup-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->start_controls_tabs( 'rael_video_popup__button_border_style' );
		$this->start_controls_tab(
			'rael_video_popup__button_border_normal',
			array(
				'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_video_popup_btn_border_color',
			array(
				'label'     => esc_html_x( 'Color', 'Border Control', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-video-popup-btn' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_video_popup_btn_tab_button_border_hover',
			array(
				'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_control(
			'rael_video_popup_btn_hover_border_color',
			array(
				'label'     => esc_html_x( 'Color', 'Border Control', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-video-popup-btn:hover' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_video_popup_btn_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'default'    => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-video-popup-btn, {{WRAPPER}} .rael-video-popup-btn:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_video_popup_box_shadow_style',
			array(
				'label' => esc_html__( 'Shadow Style', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_video_popup_btn_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-video-popup-btn',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'rael_video_popup_btn_text_shadow',
				'label'    => esc_html__( 'Text Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-video-popup-btn',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_video_popup_icon_style',
			array(
				'label'     => esc_html__( 'Icon', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_video_popup_button_icons__switch'  => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_video_popup_icon_padding_right',
			array(
				'label'      => esc_html__( 'Padding Right', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
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
					'{{WRAPPER}} .rael-video-popup-btn > i' => 'padding-right: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_video_popup_icon_align' => 'before',
				),
			)
		);

		$this->add_responsive_control(
			'rael_video_popup_icon_padding_left',
			array(
				'label'      => esc_html__( 'Padding Left', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
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
					'{{WRAPPER}} .rael-video-popup-btn > i' => 'padding-left: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_video_popup_icon_align' => 'after',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * This function changes the youtube url to support share button youtube url 
	 */


	private function extractVideoID($url) {
		// Define the regex pattern for matching the video ID
		$pattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
		preg_match($pattern, $url, $matches);
		
		if (!empty($matches[1])) {
			return $matches[1];
		} else {
			return null;
		}
	}
	private function rael_youtube_video_url_sanitize($url){
		if(!(str_contains($url,"youtu.be/") || str_contains($url,"youtube.com/embed/"))) return $url;

		$video_id = $this->extractVideoID($url);

		$url = "https://www.youtube.com/watch?v={$video_id}";

		return $url;
	}

	/**
	 * This function helps in rendering the content.
	 */
	protected function render() {
		$settings                    = $this->get_settings_for_display();
		$rael_video_popup_auto_play  = $settings['rael_video_popup_auto_play'];
		$rael_video_popup_video_loop = $settings['rael_video_popup_video_loop'];
		if ( 1 == $settings['rael_video_popup_video_player_control'] ) {
			$rael_video_popup_video_player_control = 1;
		} else {
			$rael_video_popup_video_player_control = 0;
		}
		$rael_video_popup_video_mute   = $settings['rael_video_popup_mute'];
		$rael_video_popup_start_time   = $settings['rael_video_popup_start_time'];
		$rael_video_popup_end_time     = $settings['rael_video_popup_end_time'];
		$rael_video_popup_url          = $settings['rael_video_popup_url'];
		$rael_video_glow               = $settings['rael_video_glow'];
		$rael_video_popup_button_icons = $settings['rael_video_popup_button_icons'];

		$rael_video_popup_url = $this->rael_youtube_video_url_sanitize($rael_video_popup_url);

		$rael_video_url = $rael_video_popup_url . "?autoplay={$rael_video_popup_auto_play}&loop={$rael_video_popup_video_loop}&controls={$rael_video_popup_video_player_control}&mute={$rael_video_popup_video_mute}&start={$rael_video_popup_start_time}&end={$rael_video_popup_end_time}&version=3";
		
		?>
		<div class="rael-video-content">
			<a href="<?php echo esc_url( $rael_video_url ); ?>" class="rael-video-popup rael-video-popup-btn <?php echo esc_attr( 'yes' === $rael_video_glow ? 'glow-btn' : '' ); ?>">
				<?php if ( '' !== $rael_video_popup_button_icons ) : ?>
					<?php
						$settings = $this->get_settings_for_display();

						// new icon.
						$migrated = isset( $settings['__fa4_migrated']['rael_video_popup_button_icons'] );
						// Check if its a new widget without previously selected icon using the old Icon control.
						$is_new = empty( $settings['rael_video_popup_button_icon'] );
					if ( $is_new || $migrated ) {
						// new icon.
						Icons_Manager::render_icon( $settings['rael_video_popup_button_icons'], array( 'aria-hidden' => 'true' ) );
					} else {
						?>
							<i class="<?php echo esc_attr( $settings['rael_video_popup_button_icon'] ); ?>" aria-hidden="true"></i>
							<?php
					}
					?>
				<?php endif; ?>
			</a>
		</div>
		<?php
	}

}
