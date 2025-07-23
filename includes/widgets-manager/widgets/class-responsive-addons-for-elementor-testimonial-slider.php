<?php
/**
 * RAEL Testimonial slider
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;

/**
 * 'RAEL Testimonial slider' widget class
 */
class Responsive_Addons_For_Elementor_Testimonial_Slider extends Widget_Base {
	/**
	 * Counter for slide prints.
	 *
	 * @var int
	 */
	private $slide_prints_count = 0;
	/**
	 * Get the name of the widget.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'rael-testimonial-slider';
	}
	/**
	 * Get the title of the widget displayed in the editor.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Testimonial Slider', 'responsive-addons-for-elementor' );
	}
	/**
	 * Get the icon for the widget displayed in the editor.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-testimonial rael-badge';
	}
	/**
	 * Get the categories for the widget in the editor.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}
	/**
	 * Get the stylesheets required for the widget.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'font-awesome-5-all',
			'font-awesome-4-shim',
			'swiper',
			'e-swiper',	
		);
	}
	/**
	 * Get the scripts required for the widget.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'font-awesome-4-shim',
		);
	}
	/**
	 * Get default values for the repeater control.
	 *
	 * @return array
	 */
	private function get_repeater_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return array(
			array(
				'content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-addons-for-elementor' ),
				'name'    => __( 'John Doe', 'responsive-addons-for-elementor' ),
				'title'   => __( 'CEO', 'responsive-addons-for-elementor' ),
				'image'   => array(
					'url' => $placeholder_image_src,
				),
			),
			array(
				'content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-addons-for-elementor' ),
				'name'    => __( 'John Doe', 'responsive-addons-for-elementor' ),
				'title'   => __( 'CEO', 'responsive-addons-for-elementor' ),
				'image'   => array(
					'url' => $placeholder_image_src,
				),
			),
			array(
				'content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-addons-for-elementor' ),
				'name'    => __( 'John Doe', 'responsive-addons-for-elementor' ),
				'title'   => __( 'CEO', 'responsive-addons-for-elementor' ),
				'image'   => array(
					'url' => $placeholder_image_src,
				),
			),
		);
	}
	/**
	 * Register controls for the widget.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'rael-section_slides',
			array(
				'label' => __( 'Slides', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'rael_testimonial_slider_icon_new',
			array(
				'label'            => esc_html__( 'Icon', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'rael_testimonial_slider_before_content_icon',
			)
		);

		$repeater->add_control(
			'content',
			array(
				'label' => __( 'Content', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::TEXTAREA,
			)
		);

		$repeater->add_control(
			'image',
			array(
				'label' => __( 'Image', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$repeater->add_control(
			'name',
			array(
				'label'   => __( 'Name', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'John Doe', 'responsive-addons-for-elementor' ),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'CEO', 'responsive-addons-for-elementor' ),
			)
		);

		$repeater->end_controls_tab();

		$this->add_control(
			'slides',
			array(
				'label'     => __( 'Slides', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::REPEATER,
				'fields'    => $repeater->get_controls(),
				'default'   => $this->get_repeater_defaults(),
				'separator' => 'after',
			)
		);

		$this->add_responsive_control(
			'rael_icon_size',
			array(
				'label'      => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => 40,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .responsive-testimonial__icon i' => 'font-size: {{SIZE}}{{UNIT}}; text-align: center;',
					'{{WRAPPER}} .responsive-testimonial__icon svg' => 'height: {{SIZE}}{{UNIT}}; text-align: center;',
				),
			)
		);

		$this->add_control(
			'skin',
			array(
				'label'        => __( 'Skin', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'default',
				'options'      => array(
					'default' => __( 'Default', 'responsive-addons-for-elementor' ),
					'bubble'  => __( 'Bubble', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'responsive-testimonial--skin-',
				'render_type'  => 'template',
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'        => __( 'Layout', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'image_inline',
				'options'      => array(
					'image_inline'  => __( 'Image Inline', 'responsive-addons-for-elementor' ),
					'image_stacked' => __( 'Image Stacked', 'responsive-addons-for-elementor' ),
					'image_above'   => __( 'Image Above', 'responsive-addons-for-elementor' ),
					'image_left'    => __( 'Image Left', 'responsive-addons-for-elementor' ),
					'image_right'   => __( 'Image Right', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'responsive-testimonial--layout-',
				'render_type'  => 'template',
			)
		);

		$this->add_control(
			'alignment',
			array(
				'label'        => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'default'      => 'center',
				'options'      => array(
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
				'prefix_class' => 'responsive-testimonial--align-',
			)
		);

		$slides_per_view = range( 1, 10 );
		$slides_per_view = array_combine( $slides_per_view, $slides_per_view );

		$this->add_responsive_control(
			'slides_per_view',
			array(
				'type'               => Controls_Manager::SELECT,
				'label'              => __( 'Slides Per View', 'responsive-addons-for-elementor' ),
				'options'            => array( '' => __( 'Default', 'responsive-addons-for-elementor' ) ) + $slides_per_view,
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			array(
				'type'               => Controls_Manager::SELECT,
				'label'              => __( 'Slides to Scroll', 'responsive-addons-for-elementor' ),
				'description'        => __( 'Set how many slides are scrolled per swipe.', 'responsive-addons-for-elementor' ),
				'options'            => array( '' => __( 'Default', 'responsive-addons-for-elementor' ) ) + $slides_per_view,
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 1140,
					),
					'%'  => array(
						'min' => 50,
					),
				),
				'size_units' => array( '%', 'px' ),
				'default'    => array(
					'unit' => '%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .responsive-testimonial-swiper' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_options',
			array(
				'label' => __( 'Additional Options', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'show_arrows',
			array(
				'type'               => Controls_Manager::SWITCHER,
				'label'              => __( 'Arrows', 'responsive-addons-for-elementor' ),
				'default'            => 'yes',
				'label_off'          => __( 'Hide', 'responsive-addons-for-elementor' ),
				'label_on'           => __( 'Show', 'responsive-addons-for-elementor' ),
				'prefix_class'       => 'elementor-arrows-',
				'render_type'        => 'template',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'pagination',
			array(
				'label'              => __( 'Pagination', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'bullets',
				'options'            => array(
					''            => __( 'None', 'responsive-addons-for-elementor' ),
					'bullets'     => __( 'Dots', 'responsive-addons-for-elementor' ),
					'fraction'    => __( 'Fraction', 'responsive-addons-for-elementor' ),
					'progressbar' => __( 'Progress', 'responsive-addons-for-elementor' ),
				),
				'prefix_class'       => 'responsive-pagination-type-',
				'render_type'        => 'template',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'              => __( 'Transition Duration', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'              => __( 'Autoplay', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'separator'          => 'before',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'              => __( 'Autoplay Speed', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 5000,
				'condition'          => array(
					'autoplay' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'loop',
			array(
				'label'              => __( 'Infinite Loop', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'              => __( 'Pause on Hover', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'condition'          => array(
					'autoplay' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'pause_on_interaction',
			array(
				'label'              => __( 'Pause on Interaction', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'condition'          => array(
					'autoplay' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image_size',
				'default'   => 'full',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slides_style',
			array(
				'label' => __( 'Slides', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'space_between',
			array(
				'label'              => __( 'Space Between', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => array(
					'px' => array(
						'max' => 50,
					),
				),
				'desktop_default'    => array(
					'size' => 10,
				),
				'tablet_default'     => array(
					'size' => 10,
				),
				'mobile_default'     => array(
					'size' => 10,
				),
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'slide_border_size',
			array(
				'label'     => __( 'Border Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial-swiper .swiper-slide' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'slide_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'%' => array(
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .responsive-testimonial-swiper .swiper-slide' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'slide_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial-swiper .swiper-slide' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'slide_padding',
			array(
				'label'     => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial-swiper .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs(
			'rael_tm_background_tabs'
		);

		$this->start_controls_tab(
			'rael_tm_style_normal',
			array(
				'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_tm_box_shadow',
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .responsive-testimonial-swiper .swiper-slide',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_team_background_content_normal',
				'label'    => esc_html__( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient', 'video' ),
				'selector' => '{{WRAPPER}} .responsive-testimonial-swiper .swiper-slide',
			)
		);

		$this->add_control(
			'rael_icon_normal_color',
			array(
				'label'     => __( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__icon i, {{WRAPPER}} .responsive-testimonial__icon svg' => 'color: {{VALUE}};',
					'{{WRAPPER}} .responsive-testimonial__icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_tm_style_hover',
			array(
				'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_tm_hover_box_shadow',
				'selector' => '{{WRAPPER}} .responsive-testimonial-swiper .swiper-slide:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_team_background_content_hover',
				'label'    => esc_html__( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient', 'video' ),
				'selector' => '{{WRAPPER}} .responsive-testimonial-swiper .swiper-slide:hover',
			)
		);

		$this->add_control(
			'rael_icon_hover_color',
			array(
				'label'     => __( 'Icon Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial-swiper .swiper-slide:hover .responsive-testimonial__icon i, {{WRAPPER}} .responsive-testimonial-swiper .swiper-slide:hover .responsive-testimonial__icon svg' => 'color: {{VALUE}};',
					'{{WRAPPER}} .responsive-testimonial-swiper .swiper-slide:hover .responsive-testimonial__icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_content_hover_color',
			array(
				'label'     => __( 'Content Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial-swiper .swiper-slide:hover .responsive-testimonial__text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_name_hover_color',
			array(
				'label'     => __( 'Name Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial-swiper .swiper-slide:hover .responsive-testimonial__name' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_title_hover_color',
			array(
				'label'     => __( 'Title Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial-swiper .swiper-slide:hover .responsive-testimonial__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			array(
				'label' => __( 'Content', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'content_gap',
			array(
				'label'     => __( 'Gap', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}.responsive-testimonial--layout-image_inline .responsive-testimonial__footer,
					{{WRAPPER}}.responsive-testimonial--layout-image_stacked .responsive-testimonial__footer' => 'margin-top: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.responsive-testimonial--layout-image_above .responsive-testimonial__footer' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.responsive-testimonial--layout-image_left .responsive-testimonial__footer' => 'padding-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.responsive-testimonial--layout-image_right .responsive-testimonial__footer' => 'padding-left: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'content_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__text' => 'color: {{VALUE}}',
				),
				'global'   => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .responsive-testimonial__text',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			)
		);

		$this->add_control(
			'name_title_style',
			array(
				'label'     => __( 'Name', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__name' => 'color: {{VALUE}}',
				),
				'global'   => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'name_typography',
				'selector' => '{{WRAPPER}} .responsive-testimonial__name',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			)
		);

		$this->add_control(
			'heading_title_style',
			array(
				'label'     => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__title' => 'color: {{VALUE}}',
				),
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .responsive-testimonial__title',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			array(
				'label' => __( 'Image', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'image_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.responsive-testimonial--layout-image_left .responsive-testimonial__content:after,
					 {{WRAPPER}}.responsive-testimonial--layout-image_right .responsive-testimonial__content:after' => 'top: calc( {{text_padding.TOP}}{{text_padding.UNIT}} + ({{SIZE}}{{UNIT}} / 2) - 8px );',

					'body:not(.rtl) {{WRAPPER}}.responsive-testimonial--layout-image_stacked:not(.responsive-testimonial--align-center):not(.responsive-testimonial--align-right) .responsive-testimonial__content:after,
					 body:not(.rtl) {{WRAPPER}}.responsive-testimonial--layout-image_inline:not(.responsive-testimonial--align-center):not(.responsive-testimonial--align-right) .responsive-testimonial__content:after,
					 {{WRAPPER}}.responsive-testimonial--layout-image_stacked.responsive-testimonial--align-left .responsive-testimonial__content:after,
					 {{WRAPPER}}.responsive-testimonial--layout-image_inline.responsive-testimonial--align-left .responsive-testimonial__content:after' => 'left: calc( {{text_padding.LEFT}}{{text_padding.UNIT}} + ({{SIZE}}{{UNIT}} / 2) - 8px ); right:auto;',

					'body.rtl {{WRAPPER}}.responsive-testimonial--layout-image_stacked:not(.responsive-testimonial--align-center):not(.responsive-testimonial--align-left) .responsive-testimonial__content:after,
					 body.rtl {{WRAPPER}}.responsive-testimonial--layout-image_inline:not(.responsive-testimonial--align-center):not(.responsive-testimonial--align-left) .responsive-testimonial__content:after,
					 {{WRAPPER}}.responsive-testimonial--layout-image_stacked.responsive-testimonial--align-right .responsive-testimonial__content:after,
					 {{WRAPPER}}.responsive-testimonial--layout-image_inline.responsive-testimonial--align-right .responsive-testimonial__content:after' => 'right: calc( {{text_padding.RIGHT}}{{text_padding.UNIT}} + ({{SIZE}}{{UNIT}} / 2) - 8px ); left:auto;',

					'body:not(.rtl) {{WRAPPER}}.responsive-testimonial--layout-image_above:not(.responsive-testimonial--align-center):not(.responsive-testimonial--align-right) .responsive-testimonial__content:after,
					 {{WRAPPER}}.responsive-testimonial--layout-image_above.responsive-testimonial--align-left .responsive-testimonial__content:after' => 'left: calc( {{text_padding.LEFT}}{{text_padding.UNIT}} + ({{SIZE}}{{UNIT}} / 2) - 8px ); right:auto;',

					'body.rtl {{WRAPPER}}.responsive-testimonial--layout-image_above:not(.responsive-testimonial--align-center):not(.responsive-testimonial--align-left) .responsive-testimonial__content:after,
					 {{WRAPPER}}.responsive-testimonial--layout-image_above.responsive-testimonial--align-right .responsive-testimonial__content:after' => 'right: calc( {{text_padding.RIGHT}}{{text_padding.UNIT}} + ({{SIZE}}{{UNIT}} / 2) - 8px ); left:auto;',
				),
			)
		);

		$this->add_responsive_control(
			'image_gap',
			array(
				'label'     => __( 'Gap', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'body.rtl {{WRAPPER}}.responsive-testimonial--layout-image_inline.responsive-testimonial--align-left .responsive-testimonial__image + cite,
					 body.rtl {{WRAPPER}}.responsive-testimonial--layout-image_above.responsive-testimonial--align-left .responsive-testimonial__image + cite,
					 body:not(.rtl) {{WRAPPER}}.responsive-testimonial--layout-image_inline .responsive-testimonial__image + cite,
					 body:not(.rtl) {{WRAPPER}}.responsive-testimonial--layout-image_above .responsive-testimonial__image + cite' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',

					'body:not(.rtl) {{WRAPPER}}.responsive-testimonial--layout-image_inline.responsive-testimonial--align-right .responsive-testimonial__image + cite,
					 body:not(.rtl) {{WRAPPER}}.responsive-testimonial--layout-image_above.responsive-testimonial--align-right .responsive-testimonial__image + cite,
					 body.rtl {{WRAPPER}}.responsive-testimonial--layout-image_inline .responsive-testimonial__image + cite,
					 body.rtl {{WRAPPER}}.responsive-testimonial--layout-image_above .responsive-testimonial__image + cite' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left:0;',

					'{{WRAPPER}}.responsive-testimonial--layout-image_stacked .responsive-testimonial__image + cite,
					 {{WRAPPER}}.responsive-testimonial--layout-image_left .responsive-testimonial__image + cite,
					 {{WRAPPER}}.responsive-testimonial--layout-image_right .responsive-testimonial__image + cite' => 'margin-top: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'image_border',
			array(
				'label'     => __( 'Border', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__image img' => 'border-style: solid',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_tm_image_shadow',
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .responsive-testimonial__image img',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_tm_hover_image_shadow',
				'label'    => __( 'Box Shadow Hover', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .responsive-testimonial__image img:hover',
			)
		);

		$this->add_control(
			'image_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__image img' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'image_border' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'image_border_width',
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
					'{{WRAPPER}} .responsive-testimonial__image img' => 'border-width: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'image_border' => 'yes',
				),
			)
		);

		$this->add_control(
			'image_border_radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__image img' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->start_controls_tabs(
			'rael_tm_image_background_tabs'
		);

		$this->start_controls_tab(
			'rael_tm_image_normal',
			array(
				'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_tm_image_box_shadow',
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .responsive-testimonial__image img',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_tm_image_hover',
			array(
				'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_tm_image_hover_box_shadow',
				'selector' => '{{WRAPPER}} .responsive-testimonial__image img:hover',
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_navigation',
			array(
				'label' => __( 'Navigation', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_arrows',
			array(
				'label'     => __( 'Arrows', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'none',
			)
		);

		$this->add_control(
			'arrow_style',
			array(
				'label'       => __( 'Choose Arrow', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'default'     => 'fa fa-angle-right',
				'options'     => array(
					'fa fa-angle-right'          => __( 'Angle', 'responsive-addons-for-elementor' ),
					'fa fa-angle-double-right'   => __( 'Double Angle', 'responsive-addons-for-elementor' ),
					'fa fa-chevron-right'        => __( 'Chevron', 'responsive-addons-for-elementor' ),
					'fa fa-chevron-circle-right' => __( 'Chevron Circle', 'responsive-addons-for-elementor' ),
					'fa fa-arrow-right'          => __( 'Arrow', 'responsive-addons-for-elementor' ),
					'fa fa-long-arrow-right'     => __( 'Long Arrow', 'responsive-addons-for-elementor' ),
					'fa fa-caret-right'          => __( 'Caret', 'responsive-addons-for-elementor' ),
					'fa fa-caret-square-o-right' => __( 'Caret Square', 'responsive-addons-for-elementor' ),
					'fa fa-arrow-circle-right'   => __( 'Arrow Circle', 'responsive-addons-for-elementor' ),
					'fa fa-arrow-circle-o-right' => __( 'Arrow Circle O', 'responsive-addons-for-elementor' ),
					'fa fa-toggle-right'         => __( 'Toggle', 'responsive-addons-for-elementor' ),
					'fa fa-hand-o-right'         => __( 'Hand', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'arrows_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 20,
				),
				'range'     => array(
					'px' => array(
						'min' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-swiper-button-prev, {{WRAPPER}} .responsive-swiper-button-next' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'left_arrow_position',
			array(
				'label'      => __( 'Align Left Arrow', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 40,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .responsive-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'right_arrow_position',
			array(
				'label'      => __( 'Align Right Arrow', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 40,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .responsive-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'arrows_height',
			array(
				'label'      => __( 'Arrows Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => '44' ),
				'range'      => array(
					'px' => array(
						'min'  => 15,
						'max'  => 200,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .responsive-swiper-button-prev, {{WRAPPER}} .responsive-swiper-button-next' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'arrows_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .responsive-swiper-button-prev, {{WRAPPER}} .responsive-swiper-button-next' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'heading_pagination',
			array(
				'label'     => __( 'Pagination', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'pagination!' => '',
				),
			)
		);

		$this->add_control(
			'pagination_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 20,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-horizontal .swiper-pagination-progressbar' => 'height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-pagination-fraction' => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'pagination!' => '',
				),
			)
		);

		$this->add_control(
			'pagination_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-bullet:not(.swiper-pagination-bullet-active), {{WRAPPER}} .swiper-pagination-progressbar' => 'background-color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .swiper-pagination-fraction' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'pagination!' => '',
				),
			)
		);

		$this->add_control(
			'pagination_active_color',
			array(
				'label'     => __( 'Active Dot Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'pagination' => array( 'bullets' ),
				),
			)
		);

		$this->add_control(
			'pagination_progress_color',
			array(
				'label'     => __( 'Progress Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'pagination' => array( 'progressbar' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_skin_style',
			array(
				'label'     => __( 'Bubble', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'skin' => 'bubble',
				),
			)
		);

		$this->add_responsive_control(
			'text_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'    => '20',
					'bottom' => '20',
					'left'   => '20',
					'right'  => '20',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .responsive-testimonial__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}}.responsive-testimonial--layout-image_left .responsive-testimonial__footer,
					{{WRAPPER}}.responsive-testimonial--layout-image_right .responsive-testimonial__footer' => 'padding-top: {{TOP}}{{UNIT}}',
					'{{WRAPPER}}.responsive-testimonial--layout-image_above .responsive-testimonial__footer,
					{{WRAPPER}}.responsive-testimonial--layout-image_inline .responsive-testimonial__footer,
					{{WRAPPER}}.responsive-testimonial--layout-image_stacked .responsive-testimonial__footer' => 'padding: 0 {{RIGHT}}{{UNIT}} 0 {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .responsive-testimonial__content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'border',
			array(
				'label'     => __( 'Border', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__content, {{WRAPPER}} .responsive-testimonial__content:after' => 'border-style: solid',
				),
			)
		);

		$this->add_control(
			'border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__content' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .responsive-testimonial__content:after' => 'border-color: transparent {{VALUE}} {{VALUE}} transparent',
				),
				'condition' => array(
					'border' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'border_width',
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
					'{{WRAPPER}} .responsive-testimonial__content, {{WRAPPER}} .responsive-testimonial__content:after' => 'border-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.responsive-testimonial--layout-image_stacked .responsive-testimonial__content:after,
					{{WRAPPER}}.responsive-testimonial--layout-image_inline .responsive-testimonial__content:after' => 'margin-top: -{{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.responsive-testimonial--layout-image_above .responsive-testimonial__content:after' => 'margin-bottom: -{{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'border' => 'yes',
				),
			)
		);

		$this->start_controls_tabs(
			'rael_tm_bubble_background_tabs'
		);

		$this->start_controls_tab(
			'rael_tm_bubble_normal',
			array(
				'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_tm_bubble_box_shadow',
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .responsive-testimonial__content',
			)
		);
		$this->add_control(
			'background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__content, {{WRAPPER}} .responsive-testimonial__content:after' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_tm_bubble_hover',
			array(
				'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_tm_bubble_hover_box_shadow',
				'selector' => '{{WRAPPER}} .responsive-testimonial__content:hover',
			)
		);

		$this->add_control(
			'background_color_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__content:hover, {{WRAPPER}} .responsive-testimonial__content:after' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_bubble_content_hover_color',
			array(
				'label'     => __( 'Content Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial-swiper .swiper-slide:hover .responsive-testimonial__text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_bubble_name_hover_color',
			array(
				'label'     => __( 'Name Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial-swiper .swiper-slide:hover .responsive-testimonial__name' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_bubble_title_hover_color',
			array(
				'label'     => __( 'Title Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial-swiper .swiper-slide:hover .responsive-testimonial__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}
	/**
	 * Render method for displaying the widget content on the frontend.
	 *
	 * @param array|null $settings Optional. Custom settings to use for rendering. Defaults to null.
	 * @return void
	 */
	protected function render( array $settings = null ) {
		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}

		if ( empty( $settings['slides'] ) ) {
			return;
		}

		$default_settings = array(
			'container_class' => 'responsive-testimonial-swiper',
			'video_play_icon' => true,
		);

		$settings = array_merge( $default_settings, $settings );

		$slides_count = count( $settings['slides'] );

		?>
		<div class="responsive-swiper">
			<div class="<?php echo esc_attr( $settings['container_class'] ); ?> swiper<?php echo esc_attr( RAEL_SWIPER_CONTAINER ); ?>">
				<div class="swiper-wrapper">
					<?php
					foreach ( $settings['slides'] as $index => $slide ) :
						$this->slide_prints_count++;
						?>
						<div class="swiper-slide">
							<?php $this->print_single_slide( $slide, $settings, 'slide-' . $index . '-' . $this->slide_prints_count ); ?>
						</div>
					<?php endforeach; ?>
				</div>
				<?php if ( 1 < $slides_count ) : ?>
					<?php if ( $settings['pagination'] ) : ?>
						<div class="swiper-pagination"></div>
					<?php endif; ?>
					<?php if ( $settings['show_arrows'] ) : ?>
						<?php
						if ( $settings['arrow_style'] ) {
							$pa_next_arrow = $settings['arrow_style'];
							$pa_prev_arrow = str_replace( 'right', 'left', $settings['arrow_style'] );
						} else {
							$pa_next_arrow = 'fa fa-angle-right';
							$pa_prev_arrow = 'fa fa-angle-left';
						}
						?>
						<!-- Add Arrows -->
						<div class="responsive-swiper-button-next elementor-swiper-button elementor-swiper-button-next swiper-button-next-<?php echo esc_attr( $this->get_id() ); ?>">
							<i class="<?php echo esc_attr( $pa_next_arrow ); ?>"></i>
						</div>
						<div class="responsive-swiper-button-prev elementor-swiper-button elementor-swiper-button-prev swiper-button-prev-<?php echo esc_attr( $this->get_id() ); ?>">
							<i class="<?php echo esc_attr( $pa_prev_arrow ); ?>"></i>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
	/**
	 * Generate HTML for displaying the testimonial title in the specified location.
	 *
	 * This method is responsible for creating HTML markup for the testimonial title based on the provided slide data
	 * and the specified location ('inside' or 'outside'). It considers the widget's skin and layout settings
	 * to determine the appropriate structure of the title.
	 *
	 * @param array  $slide    The data for the current testimonial slide.
	 * @param string $location The location where the title should be displayed ('inside' or 'outside').
	 *
	 * @return string HTML markup for the testimonial title.
	 */
	private function print_title( $slide, $location ) {
		if ( empty( $slide['name'] ) && empty( $slide['title'] ) ) {
			return '';
		}

		$skin              = $this->get_settings( 'skin' );
		$layout            = 'bubble' === $skin ? 'image_inline' : $this->get_settings( 'layout' );
		$locations_outside = array( 'image_above', 'image_right', 'image_left' );
		$locations_inside  = array( 'image_inline', 'image_stacked' );

		$print_outside = ( 'outside' === $location && in_array( $layout, $locations_outside, true ) );
		$print_inside  = ( 'inside' === $location && in_array( $layout, $locations_inside, true ) );

		$html = '';
		if ( $print_outside || $print_inside ) {
			$html = '<cite class="responsive-testimonial__cite">';
			if ( ! empty( $slide['name'] ) ) {
				$html .= '<span class="responsive-testimonial__name">' . $slide['name'] . '</span>';
			}
			if ( ! empty( $slide['title'] ) ) {
				$html .= '<span class="responsive-testimonial__title">' . $slide['title'] . '</span>';
			}
			$html .= '</cite>';
		}

		return $html;
	}
	/**
	 * Generate HTML for displaying a single testimonial slide.
	 *
	 * @param array  $slide       The data for the current slide.
	 * @param array  $settings    The settings for the testimonial slider.
	 * @param string $element_key The unique key for the element.
	 * @return void
	 */
	protected function print_single_slide( array $slide, array $settings, $element_key ) {
		$settings         = $this->get_settings_for_display();
		$tm_icon_migrated = isset( $settings['__fa4_migrated']['rael_testimonial_slider_icon_new'] );
		$tm_icon_is_new   = empty( $settings['rael_testimonial_slider_before_content_icon'] );
		$this->add_render_attribute(
			$element_key . '-testimonial',
			array(
				'class' => 'responsive-testimonial',
			)
		);

		if ( ! empty( $slide['image']['url'] ) ) {
			$this->add_render_attribute(
				$element_key . '-image',
				array(
					'src' => $this->get_slide_image_url( $slide, $settings ),
					'alt' => ! empty( $slide['name'] ) ? $slide['name'] : '',
				)
			);
		}

		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( $element_key . '-testimonial' ) ); ?>>
			<?php if ( $slide['content'] ) : ?>
					<div class="responsive-testimonial__icon" style="text-align: left;">
					<?php
					if ( $tm_icon_is_new || $tm_icon_migrated ) {
							Icons_Manager::render_icon( $slide['rael_testimonial_slider_icon_new'] );
					} else {
						echo '<i class="' . esc_attr( $slide['rael_testimonial_slider_before_content_icon'] ) . '"></i>';
					}
					?>
				</div>
				<div class="responsive-testimonial__content">
					<div class="responsive-testimonial__text">
						<?php echo esc_html( $slide['content'] ); ?>
					</div>
					<?php echo wp_kses_post( $this->print_title( $slide, 'outside' ) ); ?>
				</div>
			<?php endif; ?>
			<div class="responsive-testimonial__footer">
				<?php if ( $slide['image']['url'] ) : ?>
					<div class="responsive-testimonial__image">
						<img <?php echo wp_kses_post( $this->get_render_attribute_string( $element_key . '-image' ) ); ?>>
					</div>
				<?php endif; ?>
				<?php echo wp_kses_post( $this->print_title( $slide, 'inside' ) ); ?>
			</div>
		</div>
		<?php
	}
	/**
	 * Get the URL of the image for a slide.
	 *
	 * Retrieves the URL of the image associated with the given slide. If a specific image size is defined in settings,
	 * it uses that size; otherwise, it falls back to the original image URL.
	 *
	 * @param array $slide    The slide data containing information about the image.
	 *                        Example structure: ['image' => ['id' => 1, 'url' => 'https://example.com/image.jpg']].
	 * @param array $settings An array of settings that may include the preferred image size.
	 *                        Example: ['image_size' => 'thumbnail'].
	 *
	 * @return string The URL of the slide image.
	 */
	protected function get_slide_image_url( $slide, array $settings ) {
		$image_url = Group_Control_Image_Size::get_attachment_image_src( $slide['image']['id'], 'image_size', $settings );

		if ( ! $image_url ) {
			$image_url = $slide['image']['url'];
		}

		return $image_url;
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/testimonial-slider';
	}
}
