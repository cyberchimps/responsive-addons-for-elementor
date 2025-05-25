<?php
/**
 * RAEL Logo Carousel
 *
 * @since 1.3.1
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RAEL Logo Carousel class.
 *
 * @since 1.3.1
 */
class Responsive_Addons_For_Elementor_Logo_Carousel extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 1.3.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-logo-carousel';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.3.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Logo Carousel', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.3.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-carousel rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the logo carousel widget belongs to.
	 *
	 * @since 1.3.1
	 * @access public
	 *
	 * @return array Widget categories.
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
			'swiper',
			'e-swiper',	
		);
	}
	/**
	 * Get widget keywords.
	 *
	 * @since 1.3.1
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'logo', 'carousel', 'logo carousel', 'slider' );
	}

	/**
	 * Get Custom help URL
	 *
	 * @since 1.3.1
	 * @access public
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/logo-carousel';
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.3.1
	 * @access protected
	 */
	protected function register_controls() {
		// Content Tab.
		$this->register_content_tab_logo_carousel();
		$this->register_content_tab_carousel_settings();

		// Style Tab.
		$this->register_style_tab_logos();
		$this->register_style_tab_title();
		$this->register_style_tab_arrows();
		$this->register_style_tab_dots();
	}

	/**
	 * Register Logo Carousel Section controls under Content Tab.
	 *
	 * @since 1.3.1
	 * @access public
	 */
	public function register_content_tab_logo_carousel() {
		$this->start_controls_section(
			'rael_content_tab_logo_carousel_section',
			array(
				'label' => __( 'Logo Carousel', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'rael_logo_item_image',
			array(
				'label'       => __( 'Upload Logo Image', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::MEDIA,
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'default'     => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$repeater->add_control(
			'rael_logo_item_title',
			array(
				'label'   => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'rael_logo_item_alt_text',
			array(
				'label'   => __( 'Alt Text', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'rael_logo_item_link',
			array(
				'name'        => 'rael_logo_item_link',
				'label'       => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array( 'active' => true ),
				'placeholder' => 'https://www.your-link.com',
				'default'     => array(
					'url' => '',
				),
			)
		);

		$this->add_control(
			'rael_carousel_logo_items',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'rael_logo_item_image' => array(
							'url' => Utils::get_placeholder_image_src(),
						),
					),
					array(
						'rael_logo_item_image' => array(
							'url' => Utils::get_placeholder_image_src(),
						),
					),
					array(
						'rael_logo_item_image' => array(
							'url' => Utils::get_placeholder_image_src(),
						),
					),
					array(
						'rael_logo_item_image' => array(
							'url' => Utils::get_placeholder_image_src(),
						),
					),
					array(
						'rael_logo_item_image' => array(
							'url' => Utils::get_placeholder_image_src(),
						),
					),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => __( 'Logo Image', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_logo_item_title_html_tag',
			array(
				'label'   => __( 'Title HTML Tag', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h4',
				'options' => array(
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
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Settings Section controls under Content Tab.
	 *
	 * @since 1.3.1
	 * @access public
	 */
	public function register_content_tab_carousel_settings() {
		$this->start_controls_section(
			'rael_content_tab_carousel_settings_section',
			array(
				'label' => __( 'Carousel Settings', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_carousel_effect',
			array(
				'label'       => __( 'Effect', 'responsive-addons-for-elementor' ),
				'description' => __( 'Sets transition effect', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'slide',
				'options'     => array(
					'slide'     => __( 'Slide', 'responsive-addons-for-elementor' ),
					'fade'      => __( 'Fade', 'responsive-addons-for-elementor' ),
					'cube'      => __( 'Cube', 'responsive-addons-for-elementor' ),
					'coverflow' => __( 'Coverflow', 'responsive-addons-for-elementor' ),
					'flip'      => __( 'Flip', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_visible_items',
			array(
				'label'          => __( 'Visible Items', 'responsive-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'range'          => array(
					'px' => array(
						'min'  => 1,
						'max'  => 10,
						'step' => 1,
					),
				),
				'default'        => array( 'size' => 3 ),
				'tablet_default' => array( 'size' => 2 ),
				'mobile_default' => array( 'size' => 1 ),
				'size_units'     => '',
				'condition'      => array(
					'rael_carousel_effect' => array( 'slide', 'coverflow' ),
				),
				'separator'      => 'before',
			)
		);

		$this->add_responsive_control(
			'rael_items_gap',
			array(
				'label'      => __( 'Items Gap', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 10 ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => '',
				'condition'  => array(
					'rael_carousel_effect' => array( 'slide', 'coverflow' ),
				),
			)
		);

		$this->add_control(
			'rael_carousel_speed',
			array(
				'label'       => __( 'Carousel Speed', 'responsive-addons-for-elementor' ),
				'description' => __( 'Duration of transition between slides (in ms)', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array( 'size' => 400 ),
				'range'       => array(
					'px' => array(
						'min'  => 100,
						'max'  => 3000,
						'step' => 1,
					),
				),
				'size_units'  => '',
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'rael_carousel_autoplay',
			array(
				'label'        => __( 'Autoplay', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'rael_carousel_autoplay_speed',
			array(
				'label'      => __( 'Autoplay Speed', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 2000 ),
				'range'      => array(
					'px' => array(
						'min'  => 500,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'size_units' => '',
				'condition'  => array(
					'rael_carousel_autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_carousel_infinite_loop',
			array(
				'label'        => __( 'Infinite Loop', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_carousel_pause_on_hover',
			array(
				'label'        => __( 'Pause On Hover', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'condition'    => array(
					'rael_carousel_autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_carousel_grab_cursor',
			array(
				'label'        => __( 'Grab Cursor', 'responsive-addons-for-elementor' ),
				'description'  => __( 'Shows grab cursor when you hover over the slider', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'rael_carousel_navigation_heading',
			array(
				'label'     => __( 'Navigation', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_carousel_arrows',
			array(
				'label'        => __( 'Arrows', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_carousel_dots',
			array(
				'label'        => __( 'Dots', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_carousel_direction',
			array(
				'label'     => __( 'Direction', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left'  => __( 'Left', 'responsive-addons-for-elementor' ),
					'right' => __( 'Right', 'responsive-addons-for-elementor' ),
				),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Logos Section controls under Style Tab.
	 *
	 * @since 1.3.1
	 * @access public
	 */
	public function register_style_tab_logos() {
		$this->start_controls_section(
			'rael_style_tab_logos_section',
			array(
				'label' => __( 'Logos', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_logo_bg',
				'label'    => __( 'Button Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'none', 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-logo-carousel__logo',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'rael_logo_border',
				'label'       => __( 'Border', 'responsive-addons-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .rael-logo-carousel__logo',
			)
		);

		$this->add_control(
			'rael_logo_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-logo-carousel__logo, {{WRAPPER}} .rael-logo-carousel__logo img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_logo_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-logo-carousel__logo' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'rael_logo_state_tabs' );

		$this->start_controls_tab(
			'rael_logo_state_normal_tab',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_logo_grayscale_normal',
			array(
				'label'        => __( 'Grayscale', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_logo_opacity_normal',
			array(
				'label'     => __( 'Opacity', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-logo-carousel img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_logo_shadow',
				'label'    => __( 'Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-logo-carousel__logo img',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_logo_state_hover_tab',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_logo_hover_bg',
				'label'    => __( 'Logo Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'none', 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-logo-carousel__logo:hover',
			)
		);

		$this->add_control(
			'rael_logo_grayscale_hover',
			array(
				'label'        => __( 'Grayscale', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_logo_opacity_hover',
			array(
				'label'     => __( 'Opacity', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-logo-carousel .swiper-slide:hover img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_logo_shadow_hover',
				'label'    => __( 'Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-logo-carousel__logo:hover img',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register Title Section controls under Style Tab.
	 *
	 * @since 1.3.1
	 * @access public
	 */
	public function register_style_tab_title() {
		$this->start_controls_section(
			'rael_style_tab_title_section',
			array(
				'label' => __( 'Title', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_logo_title_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-logo-carousel__item-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_logo_title_spacing',
			array(
				'label'      => __( 'Margin Top', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-logo-carousel__item-title' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_logo_title_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .rael-logo-carousel__item-title',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Arrows Sectioncontrols under Style Tab.
	 *
	 * @since 1.3.1
	 * @access public
	 */
	public function register_style_tab_arrows() {
		$this->start_controls_section(
			'rael_style_tab_arrows_section',
			array(
				'label'     => __( 'Arrows', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_carousel_arrows' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_carousel_arrow_previous',
			array(
				'label'            => __( 'Choose Previous Arrow', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'arrow',
				'default'          => array(
					'value'   => 'fas fa-angle-left',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'rael_carousel_arrow_next',
			array(
				'label'            => __( 'Choose Next Arrow', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'arrow',
				'default'          => array(
					'value'   => 'fas fa-angle-right',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_responsive_control(
			'rael_carousel_arrows_size',
			array(
				'label'      => __( 'Arrows Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => '22' ),
				'range'      => array(
					'px' => array(
						'min'  => 15,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrapper .swiper-button-next, {{WRAPPER}} .swiper-container-wrapper .swiper-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .swiper-container-wrapper .rael-logo-carousel__svg-icon'                                                => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_carousel_arrow_vertical_position',
			array(
				'label'      => __( 'Arrow Position', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( '%' ),
				'default'    => array(
					'unit' => '%',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrapper .swiper-button-next, {{WRAPPER}} .swiper-container-wrapper .swiper-button-prev' => 'top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_carousel_left_arrow_position',
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
					'{{WRAPPER}} .swiper-container-wrapper .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_carousel_right_arrow_position',
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
					'{{WRAPPER}} .swiper-container-wrapper .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'rael_carousel_arrows_state_tabs' );

		$this->start_controls_tab(
			'rael_carousel_arrows_normal_tab',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_carousel_arrows_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrapper .swiper-button-next, {{WRAPPER}} .swiper-container-wrapper .swiper-button-prev' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_carousel_arrows_color_normal',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrapper .swiper-button-next, {{WRAPPER}} .swiper-container-wrapper .swiper-button-prev' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'rael_carousel_arrows_border_normal',
				'label'       => __( 'Border', 'responsive-addons-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .swiper-container-wrapper .swiper-button-next, {{WRAPPER}} .swiper-container-wrapper .swiper-button-prev',
			)
		);

		$this->add_control(
			'rael_carousel_arrows_border_radius_normal',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrapper .swiper-button-next, {{WRAPPER}} .swiper-container-wrapper .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_carousel_arrows_hover_tab',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_carousel_arrows_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrapper .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrapper .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_carousel_arrows_color_hover',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrapper .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrapper .swiper-button-prev:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_carousel_arrows_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrapper .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrapper .swiper-button-prev:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_carousel_arrows_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrapper .swiper-button-next, {{WRAPPER}} .swiper-container-wrapper .swiper-button-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Dots Section controls under Style Tab.
	 *
	 * @since 1.3.1
	 * @access public
	 */
	public function register_style_tab_dots() {
		$this->start_controls_section(
			'rael_style_tab_dots_section',
			array(
				'label'     => __( 'Pagination: Dots', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_carousel_dots' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_carousel_dots_position',
			array(
				'label'     => __( 'Position', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'inside'  => __( 'Inside', 'responsive-addons-for-elementor' ),
					'outside' => __( 'Outside', 'responsive-addons-for-elementor' ),
				),
				'default'   => 'outside',
				'condition' => array(
					'rael_carousel_dots' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_carousel_dots_size',
			array(
				'label'      => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 2,
						'max'  => 40,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrapper .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'rael_carousel_dots' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_carousel_dots_spacing',
			array(
				'label'      => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrapper .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'rael_carousel_dots' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'rael_carousel_dots_state_tabs' );

		$this->start_controls_tab(
			'rael_carousel_dots_normal_tab',
			array(
				'label'     => __( 'Normal', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_carousel_dots' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_carousel_dots_color_normal',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrapper .swiper-pagination-bullet' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'rael_carousel_dots' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_carousel_active_dot_color_normal',
			array(
				'label'     => __( 'Active Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrapper .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'rael_carousel_dots' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'rael_carousel_dots_border_normal',
				'label'       => __( 'Border', 'responsive-addons-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .swiper-container-wrapper .swiper-pagination-bullet',
				'condition'   => array(
					'rael_carousel_dots' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_carousel_dots_border_radius_normal',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrapper .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_carousel_dots' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_carousel_dots_padding',
			array(
				'label'              => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px', 'em', '%' ),
				'allowed_dimensions' => 'vertical',
				'placeholder'        => array(
					'top'    => '',
					'right'  => 'auto',
					'bottom' => '',
					'left'   => 'auto',
				),
				'selectors'          => array(
					'{{WRAPPER}} .swiper-container-wrapper .swiper-pagination-bullets' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'          => array(
					'rael_carousel_dots' => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_carousel_dots_hover_tab',
			array(
				'label'     => __( 'Hover', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_carousel_dots' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_carousel_dots_color_hover',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrapper .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'rael_carousel_dots' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_carousel_dots_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrapper .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_carousel_dots' => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Render the widget.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.3.1
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$esc_id   = esc_attr( $this->get_id() );

		$this->add_render_attribute( 'rael_logo_carousel_wrapper', 'class', 'swiper-container-wrapper rael-logo-carousel-wrapper' );
		$this->add_render_attribute( 'rael_logo_carousel', 'class', 'swiper' . RAEL_SWIPER_CONTAINER . " rael-logo-carousel swiper-container-{$esc_id}" );
		$this->add_render_attribute( 'rael_logo_carousel', 'data-pagination', ".swiper-pagination-{$esc_id}" );
		$this->add_render_attribute( 'rael_logo_carousel', 'data-arrow-next', ".swiper-button-next-{$esc_id}" );
		$this->add_render_attribute( 'rael_logo_carousel', 'data-arrow-prev', ".swiper-button-prev-{$esc_id}" );

		if ( $settings['rael_carousel_dots_position'] ) {
			$this->add_render_attribute( 'rael_logo_carousel_wrapper', 'class', "swiper-container-wrapper-dots-{$settings['rael_carousel_dots_position']}" );
		}

		if ( 'right' === $settings['rael_carousel_direction'] ) {
			$this->add_render_attribute( 'rael_logo_carousel', 'dir', 'rtl' );
		}

		if ( 'yes' === $settings['rael_logo_grayscale_normal'] ) {
			$this->add_render_attribute( 'rael_logo_carousel', 'class', 'rael-logo-carousel__logo-grayscale--normal' );
		}

		if ( 'yes' === $settings['rael_logo_grayscale_hover'] ) {
			$this->add_render_attribute( 'rael_logo_carousel', 'class', 'rael-logo-carousel__logo-grayscale--hover' );
		}

		if ( ! empty( $settings['rael_visible_items']['size'] ) ) {
			$this->add_render_attribute( 'rael_logo_carousel', 'data-items', $settings['rael_visible_items']['size'] );
		}

		if ( ! empty( $settings['rael_visible_items_tablet']['size'] ) ) {
			$this->add_render_attribute( 'rael_logo_carousel', 'data-items-tablet', $settings['rael_visible_items_tablet']['size'] );
		}

		if ( ! empty( $settings['rael_visible_items_mobile']['size'] ) ) {
			$this->add_render_attribute( 'rael_logo_carousel', 'data-items-mobile', $settings['rael_visible_items_mobile']['size'] );
		}

		if ( ! empty( $settings['rael_items_gap']['size'] ) ) {
			$this->add_render_attribute( 'rael_logo_carousel', 'data-items-gap', $settings['rael_items_gap']['size'] );
		}

		if ( ! empty( $settings['rael_items_gap_tablet']['size'] ) ) {
			$this->add_render_attribute( 'rael_logo_carousel', 'data-items-gap-tablet', $settings['rael_items_gap_tablet']['size'] );
		}

		if ( ! empty( $settings['rael_items_gap_mobile']['size'] ) ) {
			$this->add_render_attribute( 'rael_logo_carousel', 'data-items-gap-mobile', $settings['rael_items_gap_mobile']['size'] );
		}

		if ( $settings['rael_carousel_effect'] ) {
			$this->add_render_attribute( 'rael_logo_carousel', 'data-effect', $settings['rael_carousel_effect'] );
		}

		if ( ! empty( $settings['rael_carousel_speed']['size'] ) ) {
			$this->add_render_attribute( 'rael_logo_carousel', 'data-speed', $settings['rael_carousel_speed']['size'] );
		}

		if ( 'yes' === $settings['rael_carousel_autoplay'] && ! empty( $settings['rael_carousel_autoplay_speed']['size'] ) ) {
			$this->add_render_attribute( 'rael_logo_carousel', 'data-autoplay-speed', $settings['rael_carousel_autoplay_speed']['size'] );
		} else {
			$this->add_render_attribute( 'rael_logo_carousel', 'data-autoplay-speed', '999999' );
		}

		if ( 'yes' === $settings['rael_carousel_pause_on_hover'] ) {
			$this->add_render_attribute( 'rael_logo_carousel', 'data-pause-on-hover', 'true' );
		}

		if ( 'yes' === $settings['rael_carousel_infinite_loop'] ) {
			$this->add_render_attribute( 'rael_logo_carousel', 'data-loop', '1' );
		}

		if ( 'yes' === $settings['rael_carousel_grab_cursor'] ) {
			$this->add_render_attribute( 'rael_logo_carousel', 'data-grab-cursor', '1' );
		}

		if ( 'yes' === $settings['rael_carousel_arrows'] ) {
			$this->add_render_attribute( 'rael_logo_carousel', 'data-arrows', '1' );
		}
		?>
		<div <?php $this->print_render_attribute_string( 'rael_logo_carousel_wrapper' ); ?>>
			<div <?php $this->print_render_attribute_string( 'rael_logo_carousel' ); ?>>
				<div class="swiper-wrapper">
					<?php
					$i = 1;

					foreach ( $settings['rael_carousel_logo_items'] as $index => $item ) :
						if ( $item['rael_logo_item_image'] ) :
							?>
							<div class="swiper-slide">
								<div class="rael-logo-carousel__logo-wrapper">
									<div class="rael-logo-carousel__logo">
										<?php
										if ( ! empty( $item['rael_logo_item_image']['url'] ) ) {
											if ( ! empty( $item['rael_logo_item_link']['url'] ) ) {
												$this->add_render_attribute(
													"rael_logo_item_{$i}",
													array(
														'class' => 'rael-logo-carousel__logo-wrapper-link',
														'href' => $item['rael_logo_item_link']['url'],
													)
												);

												if ( $item['rael_logo_item_link']['is_external'] ) {
													$this->add_render_attribute( "rael_logo_item_{$i}", 'target', '_blank' );
												}

												if ( $item['rael_logo_item_link']['nofollow'] ) {
													$this->add_render_attribute( "rael_logo_item_{$i}", 'rel', 'nofollow' );
												}

												echo '<a ' . wp_kses_post( $this->get_render_attribute_string( "rael_logo_item_{$i}" ) ) . '>';
											}

											echo '<img class="rael-logo-carousel__logo-item-image" src="' . esc_url( $item['rael_logo_item_image']['url'] ) . '" alt="' . esc_attr( $item['rael_logo_item_alt_text'] ) . '" />';

											if ( ! empty( $item['rael_logo_item_link']['url'] ) ) {
												echo '</a>';
											}
										}
										?>
									</div>
									<?php
									if ( ! empty( $item['rael_logo_item_title'] ) ) {
										printf( '<%1$s class="rael-logo-carousel__item-title">', esc_attr( Helper::validate_html_tags( $settings['rael_logo_item_title_html_tag'] ) ) );

										if ( ! empty( $item['rael_logo_item_link']['url'] ) ) {
											echo '<a ' . wp_kses_post( $this->get_render_attribute_string( "rael_logo_item_{$i}" ) ) . '>';
										}

										echo esc_html( $item['rael_logo_item_title'] );

										if ( ! empty( $item['rael_logo_item_link']['url'] ) ) {
											echo '</a>';
										}
										printf( '</%1$s>', wp_kses_post( Helper::validate_html_tags( $settings['rael_logo_item_title_html_tag'] ) ) );
									}
									?>
								</div>
							</div>
							<?php
						endif;
						$i++;
					endforeach;
					?>
				</div>
			</div>
			<?php
			if ( 'yes' === $settings['rael_carousel_dots'] ) :
				?>
				<div class="swiper-pagination swiper-pagination-<?php echo esc_attr( $esc_id ); ?>"></div>
				<?php
			endif;

			if ( 'yes' === $settings['rael_carousel_arrows'] ) {
				if ( isset( $settings['__fa4_migrated']['rael_carousel_arrow_next'] ) || empty( $settings['arrow'] ) ) {
					$arrow = $settings['rael_carousel_arrow_next']['value'];
				} else {
					$arrow = $settings['arrow'];
				}
				?>

			<div class="swiper-button-next swiper-button-next-<?php echo esc_attr( $esc_id ); ?>">
				<?php if ( isset( $arrow['url'] ) ) : ?>
					<img class="rael-logo-carousel__svg-icon" src="<?php echo esc_url( $arrow['url'] ); ?>" alt="<?php echo esc_attr( get_post_meta( $arrow['id'], '_wp_attachment_image_alt', true ) ); ?>">
				<?php else : ?>
					<i class="<?php echo esc_attr( $arrow ); ?>"></i>
				<?php endif; ?>
			</div>
			<div class="swiper-button-prev swiper-button-prev-<?php echo esc_attr( $esc_id ); ?>">
				<?php if ( isset( $settings['rael_carousel_arrow_previous']['value']['url'] ) ) : ?>
					<img class="rael-logo-carousel__svg-icon" src="<?php echo esc_url( $settings['rael_carousel_arrow_previous']['value']['url'] ); ?>" alt="<?php echo esc_attr( get_post_meta( $settings['rael_carousel_arrow_previous']['value']['id'], '_wp_attachment_image_alt', true ) ); ?>">
				<?php else : ?>
					<i class="<?php echo esc_attr( $settings['rael_carousel_arrow_previous']['value'] ); ?>"></i>
				<?php endif; ?>
			</div>
			<?php } ?>
		</div>
		<?php
	}
}
