<?php
/**
 * Flip Box Widget
 *
 * @since   1.0.0
 * @package responsive-addons-for-elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.

}

/**
 * Flip Box Widget
 *
 * @since   1.0.0
 * @package responsive-addons-for-elementor
 */
class Responsive_Addons_For_Elementor_Flip_Box extends Widget_Base {


	/**
	 * Retrieve the widget name.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-flip-box';
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
		return __( 'Flip Box', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve slider widget icon.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-flip-box rael-badge';
	}

		/**
		 * Get widget categories.
		 *
		 * Retrieve the list of categories the timeline post widget belongs to.
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
	 * Public function get_style_depends(){} remaining add after styles have been added.
	 */
	protected function register_controls() {
		$this->start_controls_section( 'section_side_a_content', array( 'label' => __( 'Front', 'responsive-addons-for-elementor' ) ) );

		$this->start_controls_tabs( 'front_content_tabs' );

		$this->start_controls_tab( 'front_content_tab', array( 'label' => __( 'Content', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'graphic_element',
			array(
				'label'   => __( 'Graphic Element', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'none'  => array(
						'title' => __( 'None', 'responsive-addons-for-elementor' ),
						'icon'  => 'fas fa-ban',
					),
					'image' => array(
						'title' => __( 'Image', 'responsive-addons-for-elementor' ),
						'icon'  => 'far fa-image',
					),
					'icon'  => array(
						'title' => __( 'Icon', 'responsive-addons-for-elementor' ),
						'icon'  => 'fas fa-star',
					),
				),
				'default' => 'icon',
			)
		);

		$this->add_control(
			'image',
			array(
				'label'     => __( 'Choose Image', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array( 'url' => Utils::get_placeholder_image_src() ),
				'condition' => array( 'graphic_element' => 'image' ),
				'dynamic'   => array( 'active' => true ),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image',
				'label'     => __( 'Image Size', 'responsive-addons-for-elementor' ),
				'default'   => 'thumbnail',
				'condition' => array( 'graphic_element' => 'image' ),
			)
		);

		$this->add_control(
			'flip_box_icon',
			array(
				'label'            => __( 'Icon', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-star-of-life',
					'library' => 'fa-solid',
				),
				'condition'        => array( 'graphic_element' => 'icon' ),
			)
		);

		$this->add_control(
			'icon_view',
			array(
				'label'     => __( 'View', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => array(
					'default' => __( 'Default', 'responsive-addons-for-elementor' ),
					'stacked' => __( 'Stacked', 'responsive-addons-for-elementor' ),
					'framed'  => __( 'Framed', 'responsive-addons-for-elementor' ),
				),
				'condition' => array( 'graphic_element' => 'icon' ),
			)
		);

		$this->add_control(
			'icon_shape',
			array(
				'label'     => __( 'Shape', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'circle',
				'options'   => array(
					'circle' => __( 'Circle', 'responsive-addons-for-elementor' ),
					'square' => __( 'Square', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'icon_view!'      => 'default',
					'graphic_element' => 'icon',
				),
			)
		);

		$this->add_control(
			'front_title_text',
			array(
				'label'       => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'default'     => __( 'This is the heading', 'responsive-addons-for-elementor' ),
				'placeholder' => __( 'Your Title', 'responsive-addons-for-elementor' ),
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'front_description_text',
			array(
				'label'       => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => array( 'active' => true ),
				'default'     => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'responsive-addons-for-elementor' ),
				'placeholder' => __( 'Your Description', 'responsive-addons-for-elementor' ),
				'title'       => __( 'Input image text here', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'front_title_tags',
			array(
				'label'   => __( 'Title HTML Tag', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => array(
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
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'front_background_tab', array( 'label' => __( 'Background', 'responsive-addons-for-elementor' ) ) );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'front_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-flip-box-front',
			)
		);

		$this->add_control(
			'front_background_overlay',
			array(
				'label'     => __( 'Background Overlay', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .rael-flip-box-front .rael-flip-box-layer-overlay' => 'background-color: {{VALUE}};' ),
				'separator' => 'before',
				'condition' => array( 'front_background_image[id]!' => '' ),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section( 'section_back_content', array( 'label' => __( 'Back', 'responsive-addons-for-elementor' ) ) );

		$this->start_controls_tabs( 'back_content_tabs' );

		$this->start_controls_tab( 'back_content_tab', array( 'label' => __( 'Content', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'back_title_text',
			array(
				'label'       => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'default'     => __( 'This is the heading', 'responsive-addons-for-elementor' ),
				'placeholder' => __( 'Your Title', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'back_description_text',
			array(
				'label'       => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => array( 'active' => true ),
				'default'     => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'responsive-addons-for-elementor' ),
				'placeholder' => __( 'Your Description', 'responsive-addons-for-elementor' ),
				'title'       => __( 'Input image text here', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'     => __( 'Button Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array( 'active' => true ),
				'default'   => __( 'Click Here', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array( 'active' => true ),
				'placeholder' => __( 'http://your-link.com', 'responsive-addons-for-elementor' ),
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
				'condition' => array( 'link[url]!' => '' ),
			)
		);

		$this->add_control(
			'button_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'sm',
				'options'   => array(
					'xs' => __( 'Extra Small', 'responsive-addons-for-elementor' ),
					'sm' => __( 'Small', 'responsive-addons-for-elementor' ),
					'md' => __( 'Medium', 'responsive-addons-for-elementor' ),
					'lg' => __( 'Large', 'responsive-addons-for-elementor' ),
					'xl' => __( 'Extra Large', 'responsive-addons-for-elementor' ),
				),
				'condition' => array( 'button_text!' => '' ),
			)
		);

		$this->add_control(
			'back_title_tags',
			array(
				'label'   => __( 'Title HTML Tag', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => array(
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
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'back_background_tab', array( 'label' => __( 'Background', 'responsive-addons-for-elementor' ) ) );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'back_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-flip-box-back',
			)
		);

		$this->add_control(
			'back_background_overlay',
			array(
				'label'     => __( 'Background Overlay', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .rael-flip-box-back .rael-flip-box-layer-overlay' => 'background-color: {{VALUE}};' ),
				'separator' => 'before',
				'condition' => array( 'back_background_image[id]!' => '' ),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section( 'section_box_settings', array( 'label' => __( 'Settings', 'responsive-addons-for-elementor' ) ) );

		$this->add_responsive_control(
			'height',
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
				'default'    => array(
					'unit' => 'px',
					'size' => 280,
				),
				'selectors'  => array( '{{WRAPPER}} .rael-flip-box' => 'height: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'separator'  => 'after',
				'selectors'  => array( '{{WRAPPER}} .rael-flip-box-layer, {{WRAPPER}} .rael-flip-box-layer-overlay' => 'border-radius: {{SIZE}}{{UNIT}}' ),
			)
		);

		$this->add_control(
			'flip_effect',
			array(
				'label'        => __( 'Flip Effect', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'flip',
				'options'      => array(
					'flip'     => __( 'Flip', 'responsive-addons-for-elementor' ),
					'slide'    => __( 'Slide', 'responsive-addons-for-elementor' ),
					'push'     => __( 'Push', 'responsive-addons-for-elementor' ),
					'zoom-in'  => __( 'Zoom In', 'responsive-addons-for-elementor' ),
					'zoom-out' => __( 'Zoom Out', 'responsive-addons-for-elementor' ),
					'fade'     => __( 'Fade', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'rael-flip-box-effect-',
			)
		);

		$this->add_control(
			'flip_direction',
			array(
				'label'        => __( 'Flip Direction', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'up',
				'options'      => array(
					'left'  => __( 'Left', 'responsive-addons-for-elementor' ),
					'right' => __( 'Right', 'responsive-addons-for-elementor' ),
					'up'    => __( 'Up', 'responsive-addons-for-elementor' ),
					'down'  => __( 'Down', 'responsive-addons-for-elementor' ),
				),
				'condition'    => array( 'flip_effect!' => array( 'fade', 'zoom-in', 'zoom-out' ) ),
				'prefix_class' => 'rael-flip-box-direction-',
			)
		);

		$this->add_control(
			'flip_3d',
			array(
				'label'        => __( '3D Depth', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'prefix_class' => 'rael-flip-box-3d-',
				'condition'    => array( 'flip_effect' => 'flip' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_front',
			array(
				'label' => __( 'Front', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'front_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .rael-flip-box-front .rael-flip-box-layer-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'front_alignment',
			array(
				'label'       => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'fas fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'fas fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'fas fa-align-right',
					),
				),
				'default'     => 'center',
				'selectors'   => array( '{{WRAPPER}} .rael-flip-box-front .rael-flip-box-layer-overlay' => 'text-align: {{VALUE}}' ),
			)
		);

		$this->add_control(
			'front_vertical_position',
			array(
				'label'                => __( 'Vertical Position', 'responsive-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
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
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array( '{{WRAPPER}} .rael-flip-box-front .rael-flip-box-layer-overlay' => 'justify-content: {{VALUE}}' ),
			)
		);

		$this->start_controls_tabs( 'front_style_tabs' );

		$this->start_controls_tab(
			'front_icon_style_tab',
			array(
				'label'     => __( 'Icon', 'responsive-addons-for-elementor' ),
				'condition' => array( 'graphic_element' => 'icon' ),
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
				'selectors' => array( '{{WRAPPER}} .elementor-icon-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};' ),
				'condition' => array( 'graphic_element' => 'icon' ),
			)
		);

		$this->add_control(
			'icon_primary_color',
			array(
				'label'     => __( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .elementor-view-stacked .elementor-icon svg' => 'stroke: {{VALUE}}',
					'{{WRAPPER}} .elementor-view-framed .elementor-icon, {{WRAPPER}} .elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}}',
					'{{WRAPPER}} .elementor-view-framed .elementor-icon svg, {{WRAPPER}} .elementor-view-default .elementor-icon svg' => 'fill: {{VALUE}};',
				),
				'condition' => array( 'graphic_element' => 'icon' ),
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
				'selectors' => array( '{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};' ),
				'condition' => array( 'graphic_element' => 'icon' ),
			)
		);

		$this->add_control(
			'icon_padding',
			array(
				'label'     => __( 'Icon Padding', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array( '{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};' ),
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
			'icon_rotate',
			array(
				'label'     => __( 'Icon Rotate', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 0,
					'unit' => 'deg',
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon i'   => 'transform: rotate({{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .elementor-icon svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
				),
				'condition' => array( 'graphic_element' => 'icon' ),
			)
		);

		$this->add_control(
			'icon_border_width',
			array(
				'label'     => __( 'Border Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array( '{{WRAPPER}} .elementor-icon' => 'border-width: {{SIZE}}{{UNIT}}' ),
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
				'selectors'  => array( '{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
				'condition'  => array(
					'graphic_element' => 'icon',
					'icon_view!'      => 'default',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'front_title_style_tab',
			array(
				'label'     => __( 'Title', 'responsive-addons-for-elementor' ),
				'condition' => array( 'front_title_text!' => '' ),
			)
		);

		$this->add_control(
			'front_title_spacing',
			array(
				'label'     => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array( '{{WRAPPER}} .rael-flip-box-front .rael-flip-box-layer-title' => 'margin-bottom: {{SIZE}}{{UNIT}};' ),
				'condition' => array( 'front_description_text!' => '' ),
			)
		);

		$this->add_control(
			'front_title_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-flip-box-front .rael-flip-box-layer-title' => 'color: {{VALUE}}',

				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'front_title_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .rael-flip-box-front .rael-flip-box-layer-title',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'front_description_style_tab',
			array(
				'label'     => __( 'Description', 'responsive-addons-for-elementor' ),
				'condition' => array( 'front_description_text!' => '' ),
			)
		);

		$this->add_control(
			'front_description_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f5f5f5',
				'selectors' => array(
					'{{WRAPPER}} .rael-flip-box-front .rael-flip-box-layer-desc' => 'color: {{VALUE}}',

				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'front_description_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-flip-box-front .rael-flip-box-layer-desc',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'front_border',
				'selector'  => '{{WRAPPER}} .rael-flip-box-front',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_back',
			array(
				'label' => __( 'Back', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'back_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .rael-flip-box-back .rael-flip-box-layer-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'back_alignment',
			array(
				'label'       => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'fas fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'fas fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'fas fa-align-right',
					),
				),
				'default'     => 'center',
				'selectors'   => array(
					'{{WRAPPER}} .rael-flip-box-back .rael-flip-box-layer-overlay' => 'text-align: {{VALUE}}',
					'{{WRAPPER}} .rael-flip-box-button' => 'margin-{{VALUE}}: 0',
				),
			)
		);

		$this->add_control(
			'back_vertical_position',
			array(
				'label'                => __( 'Vertical Position', 'responsive-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
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
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array( '{{WRAPPER}} .rael-flip-box-back .rael-flip-box-layer-overlay' => 'justify-content: {{VALUE}}' ),
				'separator'            => 'after',
			)
		);

		$this->start_controls_tabs( 'back_style_tabs' );

		$this->start_controls_tab(
			'back_title_style_tab',
			array(
				'label'     => __( 'Title', 'responsive-addons-for-elementor' ),
				'condition' => array( 'back_title_text!' => '' ),
			)
		);

		$this->add_control(
			'back_title_spacing',
			array(
				'label'     => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array( '{{WRAPPER}} .rael-flip-box-back .rael-flip-box-layer-title' => 'margin-bottom: {{SIZE}}{{UNIT}};' ),
				'condition' => array( 'back_title_text!' => '' ),
			)
		);

		$this->add_control(
			'back_title_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-flip-box-back .rael-flip-box-layer-title' => 'color: {{VALUE}}',

				),
				'condition' => array( 'back_title_text!' => '' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'back_title_typography',
				'label'     => __( 'Typography', 'responsive-addons-for-elementor' ),
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector'  => '{{WRAPPER}} .rael-flip-box-back .rael-flip-box-layer-title',
				'condition' => array( 'back_title_text!' => '' ),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'back_description_style_tab',
			array(
				'label'     => __( 'Description', 'responsive-addons-for-elementor' ),
				'condition' => array( 'back_description_text!' => '' ),
			)
		);

		$this->add_control(
			'back_description_spacing',
			array(
				'label'     => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array( '{{WRAPPER}} .rael-flip-box-back .rael-flip-box-layer-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};' ),
				'condition' => array( 'button_text!' => '' ),
			)
		);

		$this->add_control(
			'back_description_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-flip-box-back .rael-flip-box-layer-desc' => 'color: {{VALUE}}',

				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography_b',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-flip-box-back .rael-flip-box-layer-desc',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'back_border',
				'selector'  => '{{WRAPPER}} .rael-flip-box-back',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			array(
				'label'     => __( 'Button', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'button_text!' => '' ),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab( 'tab_button_normal', array( 'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .rael-flip-box-button' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'button_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .rael-flip-box-button' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .rael-flip-box-button',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border',
				'label'       => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .rael-flip-box-button',
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array( '{{WRAPPER}} .rael-flip-box-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'button_text_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .rael-flip-box-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => esc_html__( 'Typography', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .rael-flip-box-button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_button_hover', array( 'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .rael-flip-box-button:hover' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'button_background_hover_color',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .rael-flip-box-button:hover' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .rael-flip-box-button:hover',
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .rael-flip-box-button:hover' => 'border-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'button_hover_animation',
			array(
				'label' => esc_html__( 'Animation', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Return Elementor allwed tags for current widget
	 *
	 * @param array $tag Array of tags.
	 * @return array Array of allowed tags for current widget
	 */
	private function element_pack_allow_tags( $tag = null ) {
		$tag_allowed = wp_kses_allowed_html( 'post' );

		$tag_allowed['input']  = array(
			'class'   => array(),
			'id'      => array(),
			'name'    => array(),
			'value'   => array(),
			'checked' => array(),
			'type'    => array(),
		);
		$tag_allowed['select'] = array(
			'class'    => array(),
			'id'       => array(),
			'name'     => array(),
			'value'    => array(),
			'multiple' => array(),
			'type'     => array(),
		);
		$tag_allowed['option'] = array(
			'value'    => array(),
			'selected' => array(),
		);

		$tag_allowed['title'] = array(
			'a'      => array(
				'href'  => array(),
				'title' => array(),
				'class' => array(),
			),
			'br'     => array(),
			'em'     => array(),
			'strong' => array(),
			'hr'     => array(),
		);

		$tag_allowed['text'] = array(
			'a'      => array(
				'href'  => array(),
				'title' => array(),
				'class' => array(),
			),
			'br'     => array(),
			'em'     => array(),
			'strong' => array(),
			'hr'     => array(),
			'i'      => array( 'class' => array() ),
			'span'   => array( 'class' => array() ),
		);

		$tag_allowed['svg'] = array(
			'svg'     => array(
				'version'     => array(),
				'xmlns'       => array(),
				'viewbox'     => array(),
				'xml:space'   => array(),
				'xmlns:xlink' => array(),
				'x'           => array(),
				'y'           => array(),
				'style'       => array(),
			),
			'g'       => array(),
			'path'    => array(
				'class' => array(),
				'd'     => array(),
			),
			'ellipse' => array(
				'class' => array(),
				'cx'    => array(),
				'cy'    => array(),
				'rx'    => array(),
				'ry'    => array(),
			),
			'circle'  => array(
				'class' => array(),
				'cx'    => array(),
				'cy'    => array(),
				'r'     => array(),
			),
			'rect'    => array(
				'x'         => array(),
				'y'         => array(),
				'transform' => array(),
				'height'    => array(),
				'width'     => array(),
				'class'     => array(),
			),
			'line'    => array(
				'class' => array(),
				'x1'    => array(),
				'x2'    => array(),
				'y1'    => array(),
				'y2'    => array(),
			),
			'style'   => array(),

		);

		if ( null === $tag ) {
			return $tag_allowed;
		} elseif ( is_array( $tag ) ) {
			$new_tag_allow = array();

			foreach ( $tag as $_tag ) {
				$new_tag_allow[ $_tag ] = $tag_allowed[ $_tag ];
			}

			return $new_tag_allow;
		} else {
			return isset( $tag_allowed[ $tag ] ) ? $tag_allowed[ $tag ] : array();
		}
	}

	/**
	 * Render the widget on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings    = $this->get_settings_for_display();
		$animation   = ( $settings['button_hover_animation'] ) ? ' elementor-animation-' . $settings['button_hover_animation'] : '';
		$wrapper_tag = 'div';
		$button_tag  = 'a';
		$link_url    = empty( $settings['link']['url'] ) ? '#' : $settings['link']['url'];

		$this->add_render_attribute( 'button', 'class', array( 'rael-flip-box-button', 'elementor-button', 'elementor-size-' . $settings['button_size'], $animation ) );

		$this->add_render_attribute( 'wrapper', 'class', 'rael-flip-box-layer rael-flip-box-back' );

		if ( 'box' === $settings['link_click'] ) {
			$wrapper_tag = 'a';
			$button_tag  = 'button';
			$this->add_render_attribute( 'wrapper', 'href', $link_url );
			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'wrapper', 'target', '_blank' );
			}
		} else {
			$this->add_render_attribute( 'button', 'href', $link_url );
			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'button', 'target', '_blank' );
			}
		}

		if ( 'icon' === $settings['graphic_element'] ) {
			$this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-icon-wrapper' );
			$this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-view-' . $settings['icon_view'] );
			if ( 'default' !== $settings['icon_view'] ) {
				$this->add_render_attribute( 'icon-wrapper', 'class', 'elementor-shape-' . $settings['icon_shape'] );
			}
			if ( ! empty( $settings['icon'] ) ) {
				$this->add_render_attribute( 'icon', 'class', $settings['icon'] );
			}
		}

		$this->add_render_attribute( 'box_front_title_tags', 'class', 'rael-flip-box-layer-title' );

		if ( ! isset( $settings['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default.
			$settings['icon'] = 'fas fa-star-of-life';
		}

		$has_icon = ! empty( $settings['icon'] ) || ! empty( $settings['flip_box_icon'] );
		$migrated = isset( $settings['__fa4_migrated']['flip_box_icon'] );
		$is_new   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

		?>
		<div class="rael-flip-box">
			<div class="rael-flip-box-layer rael-flip-box-front">
				<div class="rael-flip-box-layer-overlay">
					<div class="rael-flip-box-layer-inner">
		<?php if ( 'image' === $settings['graphic_element'] && ! empty( $settings['image']['url'] ) ) : ?>
							<div class="rael-flip-box-image">
				<?php echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'image' ) ); ?>
							</div>
				<?php
		elseif ( 'icon' === $settings['graphic_element'] && $has_icon ) :
			?>
							<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'icon-wrapper' ) ); ?>>
								<div class="elementor-icon">

			<?php
			if ( $is_new || $migrated ) :
				Icons_Manager::render_icon(
					$settings['flip_box_icon'],
					array(
						'aria-hidden' => 'true',
						'class'       => 'fa-fw',
					)
				);
			else :
				?>
										<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
				<?php
			endif;
			?>

								</div>
							</div>
			<?php
		endif;
		?>

		<?php if ( ! empty( $settings['front_title_text'] ) ) : ?>
						<<?php echo esc_html( Helper::validate_html_tags( $settings['front_title_tags'] ) ); ?> <?php echo wp_kses_post( $this->get_render_attribute_string( 'box_front_title_tags' ) ); ?>>
				<?php echo wp_kses( $settings['front_title_text'], $this->element_pack_allow_tags( 'title' ) ); ?>
					</<?php echo esc_html( Helper::validate_html_tags( $settings['front_title_tags'] ) ); ?>>
				<?php
		endif;
		?>

		<?php if ( ! empty( $settings['front_description_text'] ) ) : ?>
						<div class="rael-flip-box-layer-desc">
			<?php echo wp_kses( $settings['front_description_text'], $this->element_pack_allow_tags( 'text' ) ); ?>
						</div>
			<?php
		endif;
		?>
				</div>
			</div>
		</div>
		<<?php echo esc_attr( $wrapper_tag ); ?> <?php echo wp_kses_post( $this->get_render_attribute_string( 'wrapper' ) ); ?>>
		<div class="rael-flip-box-layer-overlay">
		<div class="rael-flip-box-layer-inner">
		<?php if ( ! empty( $settings['back_title_text'] ) ) : ?>
		<<?php echo esc_html( Helper::validate_html_tags( $settings['back_title_tags'] ) ); ?> <?php echo wp_kses_post( $this->get_render_attribute_string( 'box_front_title_tags' ) ); ?>>
			<?php echo wp_kses( $settings['back_title_text'], $this->element_pack_allow_tags( 'title' ) ); ?>
		</<?php echo esc_html( Helper::validate_html_tags( $settings['back_title_tags'] ) ); ?>>
			<?php
		endif;
		?>

		<?php if ( ! empty( $settings['back_description_text'] ) ) : ?>
		<div class="rael-flip-box-layer-desc">
			<?php echo wp_kses( $settings['back_description_text'], $this->element_pack_allow_tags( 'text' ) ); ?>
		</div>
			<?php
		endif;
		?>

		<?php if ( ! empty( $settings['button_text'] ) ) : ?>
		<<?php echo esc_attr( $button_tag ); ?> <?php echo wp_kses_post( $this->get_render_attribute_string( 'button' ) ); ?>>
			<?php echo wp_kses( $settings['button_text'], $this->element_pack_allow_tags( 'title' ) ); ?>
		</<?php echo esc_attr( $button_tag ); ?>>
			<?php
		endif;
		?>
		</div>
		</div>
		</<?php echo esc_attr( $wrapper_tag ); ?>>
		</div>
		<?php
	}

	/**
	 * Render Flip Box widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
		var buttonClass = 'rael-flip-box-button elementor-button elementor-size-' + settings.button_size + ' elementor-animation-' + settings.button_hover_animation;

		if ( 'image' == settings.graphic_element && '' != settings.image.url ) {
		var image = {
		id: settings.image.id,
		url: settings.image.url,
		size: settings.image_size,
		dimension: settings.image_custom_dimension,
		model: view.getEditModel()
		};

		var imageUrl = elementor.imagesManager.getImageUrl( image );
		}

		var wrapperTag = 'div',
		buttonTag = 'a';

		if ( 'box' == settings.link_click ) {
		wrapperTag = 'a';
		buttonTag = 'button';
		}

		if ( 'icon' == settings.graphic_element ) {
		var iconWrapperClasses = 'elementor-icon-wrapper';
		iconWrapperClasses += ' elementor-view-' + settings.icon_view;
		if ( 'default' != settings.icon_view ) {
		iconWrapperClasses += ' elementor-shape-' + settings.icon_shape;
		}
		}

		view.addRenderAttribute( 'box_front_title_tags', 'class', 'rael-flip-box-layer-title' );

		let hasIcon = settings.icon || settings.flip_box_icon;
		iconHTML = elementor.helpers.renderIcon( view, settings.flip_box_icon, { 'aria-hidden': true }, 'i' , 'object' );

		migrated = elementor.helpers.isIconMigrated( settings, 'flip_box_icon' );
		#>

		<div class="rael-flip-box">
			<div class="rael-flip-box-layer rael-flip-box-front">
				<div class="rael-flip-box-layer-overlay">
					<div class="rael-flip-box-layer-inner">
						<# if ( 'image' == settings.graphic_element && '' != settings.image.url ) { #>
						<div class="rael-flip-box-image">
							<img src="{{ imageUrl }}">
						</div>
						<#  } else if ( 'icon' === settings.graphic_element && hasIcon ) { #>
						<div class="{{ iconWrapperClasses }}" >
							<div class="elementor-icon">

								<# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
								{{{ iconHTML.value }}}
								<# } else { #>
								<i class="{{ settings.icon }}" aria-hidden="true"></i>
								<# } #>

							</div>
						</div>
						<# } #>

						<# if ( settings.front_title_text ) { #>
						<{{{ elementor.helpers.validateHTMLTag(settings.front_title_tags) }}} {{{ view.getRenderAttributeString( 'box_front_title_tags' ) }}}>{{{ settings.front_title_text }}}</{{{ elementor.helpers.validateHTMLTag(settings.front_title_tags) }}}>
					<# } #>

					<# if ( settings.front_description_text ) { #>
					<div class="rael-flip-box-layer-desc">{{{ settings.front_description_text }}}</div>
					<# } #>
				</div>
			</div>
		</div>
		<{{ wrapperTag }} class="rael-flip-box-layer rael-flip-box-back">
		<div class="rael-flip-box-layer-overlay">
			<div class="rael-flip-box-layer-inner">
				<# if ( settings.back_title_text ) { #>
				<{{{ elementor.helpers.validateHTMLTag(settings.back_title_tags) }}} {{{ view.getRenderAttributeString( 'box_front_title_tags' ) }}}>{{{ settings.back_title_text }}}</{{{ elementor.helpers.validateHTMLTag(settings.back_title_tags) }}}>
			<# } #>

			<# if ( settings.back_description_text ) { #>
			<div class="rael-flip-box-layer-desc">{{{ settings.back_description_text }}}</div>
			<# } #>

			<# if ( settings.button_text ) { #>
			<{{ buttonTag }} href="#" class="{{ buttonClass }}">{{{ settings.button_text }}}</{{ buttonTag }}>
		<# } #>
		</div>
		</div>
		</{{ wrapperTag }}>
		</div>
		<?php
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return esc_url( 'https://cyberchimps.com/docs/widgets/flipbox' );
	}
}
