<?php
/**
 * RAEL Image Hotspot Widget
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * RAEL Image Hotspot class.
 */
class Responsive_Addons_For_Elementor_Image_Hotspot extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve 'Hotspot' widget name.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael_image_hotspot';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve 'Hotspot' widget title.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Image Hotspot', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve 'Hotspot' widget icon.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-hotspot rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the Hotspot widget belongs to.
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
	 * Register 'Hotspot' widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_controls() {

		// Content tab.
		$this->start_controls_section(
			'rael_hotspot_section',
			array(
				'label' => __( 'Hotspot', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_hotspot_background_image',
			array(
				'label'     => _x( 'Background Map Image', 'Background Control', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
				'selectors' => array(
					'{{WRAPPER}} .hotspot-image' =>
						'background-image: url({{URL}});
                        background-repeat: no-repeat;',
				),
			)
		);

		$this->add_control(
			'rael_hotspot_image_background_size',
			array(
				'label'      => _x( 'Size', 'Background Control', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'contain',
				'options'    => array(
					'cover'   => _x( 'Cover', 'Background Control', 'responsive-addons-for-elementor' ),
					'contain' => _x( 'Contain', 'Background Control', 'responsive-addons-for-elementor' ),
					'auto'    => _x( 'Auto', 'Background Control', 'responsive-addons-for-elementor' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hotspot-image' => 'background-size: {{VALUE}}',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'rael_hotspot_background_image',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_hotspot_image_background_position',
			array(
				'label'      => _x( 'Position', 'Background Control', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'center',
				'options'    => array(
					'left'   => _x( 'Left', 'Background Control', 'responsive-addons-for-elementor' ),
					'center' => _x( 'Center', 'Background Control', 'responsive-addons-for-elementor' ),
					'right'  => _x( 'Right', 'Background Control', 'responsive-addons-for-elementor' ),
					'unset'  => _x( 'Unset', 'Background Control', 'responsive-addons-for-elementor' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hotspot-image' => 'background-position: {{VALUE}}',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'rael_hotspot_background_image',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_hotspot_image_height',
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
					'em' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 400,
				),
				'size_units' => array( 'px', 'vh', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .hotspot-image' => 'height: {{SIZE}}{{UNIT}};',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'rael_hotspot_background_image',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'rael_hotspot_indicator_title',
			array(
				'label'       => __( 'Title & Description', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Title', 'responsive-addons-for-elementor' ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'rael_hotspot_indicator_description',
			array(
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Enter Description', 'responsive-addons-for-elementor' ),
			)
		);

		$repeater->add_control(
			'rael_button_text',
			array(
				'label'       => __( 'Button Text', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Text', 'responsive-addons-for-elementor' ),
				'description' => 'Please enter "Button Text" as well as "Button Link" below in order for the button to be displayed on the screen.',
			)
		);

		$repeater->add_control(
			'rael_button_link',
			array(
				'label'       => __( 'Button Link', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'responsive-addons-for-elementor' ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'rael_hotspot_indicator_image',
			array(
				'label'     => _x( 'Content Image', 'Background Control', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .content-image' =>
						'background-image: url({{URL}});
                        background-repeat: no-repeat;
						margin-right: 0.4em;
 					    width: 80px;',
				),
			)
		);

		$repeater->add_control(
			'rael_hotspot_image_width',
			array(
				'label'      => __( 'Image Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 80,
						'max' => 120,
					),
				),
				'default'    => array(
					'size' => 50,
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .content-image' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'hotspot_indicator_image[url]!' => '',
				),
			)
		);

		$repeater->add_control(
			'rael_hotspot_image_size',
			array(
				'label'      => _x( 'Size', 'Background Control', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'cover',
				'options'    => array(
					'cover'   => _x( 'Cover', 'Background Control', 'responsive-addons-for-elementor' ),
					'contain' => _x( 'Contain', 'Background Control', 'responsive-addons-for-elementor' ),
					'auto'    => _x( 'Auto', 'Background Control', 'responsive-addons-for-elementor' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .content-image' => 'background-size: {{VALUE}}',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'rael_hotspot_indicator_image[url]',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'rael_hotspot_image_background_position',
			array(
				'label'      => _x( 'Position', 'Background Control', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'center',
				'options'    => array(
					''        => _x( 'Manual', 'Background Control', 'responsive-addons-for-elementor' ),
					'left'    => _x( 'Left', 'Background Control', 'responsive-addons-for-elementor' ),
					'center'  => _x( 'Center', 'Background Control', 'responsive-addons-for-elementor' ),
					'right'   => _x( 'Right', 'Background Control', 'responsive-addons-for-elementor' ),
					'bottom'  => _x( 'Bottom', 'Background Control', 'responsive-addons-for-elementor' ),
					'initial' => _x( 'Initial', 'Background Control', 'responsive-addons-for-elementor' ),
					'top'     => _x( 'Top', 'Background Control', 'responsive-addons-for-elementor' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .content-image' => 'background-position: {{VALUE}}',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'rael_hotspot_indicator_image[url]',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'rael_hotspot_image_position_x',
			array(
				'label'     => __( 'Image Position X', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => -200,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .content-image' => 'background-position-x: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_hotspot_image_background_position' => '',
				),
			)
		);

		$repeater->add_control(
			'rael_hotspot_image_position_y',
			array(
				'label'     => __( 'Image Position Y', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => -200,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .content-image' => 'background-position-y: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_hotspot_image_background_position' => '',
				),
			)
		);

		$repeater->add_control(
			'rael_hotspot_indicator_horizontal_position',
			array(
				'label'      => __( 'Horizontal Position', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 50,
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$repeater->add_control(
			'rael_hotspot_indicator_vertical_position',
			array(
				'label'      => __( 'Vertical Position', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 50,
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_hotspot_indicator_list',
			array(
				'label'       => __( 'Hotspot Indicator List', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'show_label'  => true,
				'fields'      => $repeater->get_controls(),
				'separator'   => 'before',
				'default'     => array(
					array(
						'rael_hotspot_indicator_title' => __( 'Title 1', 'responsive-addons-for-elementor' ),
						'rael_hotspot_indicator_description' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'responsive-addons-for-elementor' ),
					),
				),
				'title_field' => '{{{ rael_hotspot_indicator_title }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_indicator_additional_settings',
			array(
				'label' => __( 'Additional Settings', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_indicator_display_glow',
			array(
				'label'        => __( 'Display Glow', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Off', 'responsive-addons-for-elementor' ),
				'return_value' => 'glow',
				'default'      => 'off',
			)
		);

		$this->add_control(
			'rael_display_inner_circle',
			array(
				'label'        => __( 'Display Inner Circle', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Off', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'rael_on_hover_or_not',
			array(
				'label'   => __( 'Display Content Condition', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'on-hover',
				'options' => array(
					'on-hover' => _x( 'On Hover', 'Background Control', 'responsive-addons-for-elementor' ),
					''         => _x( 'Display Permanently', 'Background Control', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->end_controls_section();

		// Styling tab.
		$this->start_controls_section(
			'rael_indicator_styles',
			array(
				'label'      => __( 'Indicator', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'rael_indicator_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f7a5a5',
				'selectors' => array(
					'{{WRAPPER}} .hotspot-indicator' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_indicator_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => '14',
					'right'    => '14',
					'bottom'   => '14',
					'left'     => '14',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .hotspot-indicator' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_indicator_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => '50',
					'right'    => '50',
					'bottom'   => '50',
					'left'     => '50',
					'unit'     => '%',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .hotspot-indicator' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .glow'              => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_inner_indicator',
			array(
				'label'     => __( 'Inner Indicator Circle', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'rael_display_inner_circle!' => '' ),
			)
		);

		$this->add_control(
			'rael_indicator_inner_circle_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#8a54ea',
				'selectors' => array(
					'{{WRAPPER}} .hotspot-indicator-inner' => 'background-color: {{VALUE}}',
				),
				'condition' => array( 'rael_display_inner_circle!' => '' ),
			)
		);

		$this->add_control(
			'rael_glow_style',
			array(
				'label'     => __( 'Glow', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'rael_indicator_display_glow!' => 'off' ),
			)
		);

		$this->add_control(
			'rael_glow_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .glow' => 'color: {{VALUE}}',
				),
				'condition' => array( 'rael_indicator_display_glow!' => 'off' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_content_styles',
			array(
				'label'      => __( 'Content', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'rael_content_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffe4cf',
				'selectors' => array(
					'{{WRAPPER}} .hotspot-indicator-content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .arrow-left::before' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_content_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .title'       => 'color: {{VALUE}}',
					'{{WRAPPER}} .description' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_content_border_radius',
			array(
				'label'      => __( 'Content Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hotspot-indicator-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_title_styles',
			array(
				'label'     => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_title_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '.title',
			)
		);

		$this->add_control(
			'rael_description_styles',
			array(
				'label'     => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_description_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '.description',
			)
		);

		$this->add_control(
			'rael_button_styles',
			array(
				'label'     => __( 'Button', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_button_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '.hotspot-button',
			)
		);

		$this->add_control(
			'rael_button_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#4694D8',
				'selectors' => array(
					'{{WRAPPER}} .hotspot-button' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_button_text_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .hotspot-button' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_button_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'%' => array(
						'min' => 20,
						'max' => 96,
					),
				),
				'default'    => array(
					'unit' => '%',
				),
				'size_units' => array( '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hotspot-button' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_button_border_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hotspot-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .hotspot-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Print the custom styles for the hotspot indicator element.
	 *
	 * This method outputs the CSS styles used for the hotspot indicator element,
	 * controlling its appearance, visibility, and animation effects.
	 *
	 * @access protected
	 */
	protected function print_styles() {
		?>
		<style type="text/css">
			.hotspot-indicator-content {
				z-index: 1;
				transform: translate(50px, -5px);
				visibility: hidden;
			}
			.content-hover:hover {
				visibility: inherit;
			}
			.on-hover:hover + .content-hover {
				visibility: inherit;
				animation: slide-in 500ms ease-out;
			}
			.content-hover:not( :hover ) {
				animation: slide-out 500ms ease-out backwards;
				transition: visibility 500ms ease-out;
			}
			.permanent {
				visibility: inherit;
			}
			@keyframes slide-out {
				from {
					transform: translate(50px, -5px);
					opacity: 1;
				}
				to {
					transform: translate(80px, -5px);
					opacity: 0;
				}
			}
			@keyframes slide-in {
				from {
					transform: translate(80px, -5px);
					opacity: 0;
				}
				to {
					transform: translate(50px, -5px);
					opacity: 1;
				}
			}
			.hotspot-indicator-content.arrow-left::before {
				content: " ";
				position: absolute;
				left: -7px;
				top: 15px;
				height: 13px;
				width: 13px;
				transform: rotate(45deg);
				border-right: transparent;
				border-top: transparent;
			}
		</style>
		<?php
	}

	/**
	 * Render image box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->print_styles();

		$glow_button  = $settings['rael_indicator_display_glow'];
		$inner_circle = $settings['rael_display_inner_circle'];
		$on_hover     = $settings['rael_on_hover_or_not'];

		if ( empty( $settings['rael_hotspot_background_image']['url'] ) ) {
			return;
		}

		echo '<div class="hotspot-image">';

		foreach ( $settings['rael_hotspot_indicator_list'] as $indicator ) {

			$class = 'hotspot-indicator elementor-repeater-item-' . $indicator['_id'];

			if ( 'on-hover' === $on_hover ) {
				$class .= ' on-hover';
			}

			echo '<div class="' . esc_attr( $class ) . '">';

			if ( 'glow' === $glow_button ) {
				echo '<div class="glow"></div>';
				echo '<div class="glow glow-2"></div>';
				echo '<div class="glow glow-3"></div>';
			}

			if ( 'yes' === $inner_circle ) {
				echo '<div class="hotspot-indicator-inner"></div>';
			}

			echo '</div>';

			$title       = $indicator['rael_hotspot_indicator_title'];
			$description = $indicator['rael_hotspot_indicator_description'];
			$button_text = $indicator['rael_button_text'];
			$button_link = $indicator['rael_button_link'];

			if ( null !== $title || null !== $description ) {

				$class_content = 'hotspot-indicator-content arrow-left elementor-repeater-item-' . $indicator['_id'];

				if ( '' === $on_hover ) {
					$class_content .= ' permanent';
				} else {
					$class_content .= ' content-hover';
				}

				echo '<div class="' . esc_attr( $class_content ) . '">';
					echo '<div class="content-image"></div>';
					echo '<div class="content-text">';
				if ( null !== $title ) {
					echo '<p class="title">' . esc_html( $title ) . '</p>';
				}
				if ( null !== $description ) {
					echo '<p class="description">' . esc_html( $description ) . '</p>';
				}
				if ( $button_link && $button_text ) {
					$target   = $indicator['rael_button_link']['is_external'] ? " target='_blank'" : '';
					$nofollow = $indicator['rael_button_link']['nofollow'] ? " rel='nofollow'" : '';
					echo '<a class="hotspot-button" href="' . esc_attr( $button_link['url'] ) . '"' . wp_kses_post( $target ) . wp_kses_post( $nofollow ) . '>' . wp_kses_post( $button_text ) . '</a>';
				}
					echo '</div>';
				echo '</div>';
			}
		}

		echo '</div>';
	}

	/**
	 * Define the content structure template for the hotspot indicator element in the Elementor editor.
	 *
	 * This method outputs the HTML structure used for the hotspot indicator element in the Elementor editor.
	 * It dynamically generates indicators and their associated content based on the provided settings.
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
			glow_button = settings.rael_indicator_display_glow
			inner_circle = settings.rael_display_inner_circle
			on_hover = settings.rael_on_hover_or_not
		#>
		<div class="hotspot-image">
			<?php $this->print_styles(); ?>
			<# if ( on_hover == '' ) { #>
				<# permanent = ' permanent' #>
			<# } else { #>
				<# permanent = ' content-hover' #>
			<# } #>
			<# jQuery.each( settings.rael_hotspot_indicator_list, function( index, indicator ) { #>
				<div class="hotspot-indicator elementor-repeater-item-{{ indicator._id }} {{ on_hover }}">
					<# if ( glow_button == 'glow' ) { #>
						<div class="glow"></div>
						<div class="glow glow-2"></div>
						<div class="glow glow-3"></div>
					<# } #>

					<# if ( inner_circle == 'yes' ) { #>
						<div class="hotspot-indicator-inner"></div>
					<# } #>
				</div>
				<# if ( indicator.rael_hotspot_indicator_title !== '' || indicator.rael_hotspot_indicator_description !== '' ) { #>
					<div class="hotspot-indicator-content arrow-left elementor-repeater-item-{{ indicator._id }} {{ permanent }}">
						<div class="content-image"></div>
						<div class="content-text">
							<# if ( indicator.rael_hotspot_indicator_title !== '' ) { #>
								<p class="title">{{{ indicator.rael_hotspot_indicator_title }}}</p>
							<# } #>
							<# if ( indicator.rael_hotspot_indicator_description !== '' ) { #>
								<p class="description">{{{ indicator.rael_hotspot_indicator_description }}}</p>
							<# } #>
							<# button_text = indicator.rael_button_text #>
							<# button_link = indicator.rael_button_link.url #>
							<# if ( button_text && button_link ) { #>
								<# if ( button_link.is_external ) { #>
									<# target = 'target="_blank"' #>
								<# } else { #>
									<# target = '' #>
								<# } #>
								<# if ( button_link.nofollow ) { #>
									<# rel = 'rel="nofollow"' #>
								<# } else { #>
									<# rel = '' #>
								<# } #>
								<a class="hotspot-button" href="{{ button_link }}" {{{ target }}} {{{ rel }}}> {{{ button_text }}} </a>
							<# } #>
						</div>
					</div>
					<# } #>
			<# }); #>
		</div>
		<?php
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/image-hotspot';
	}
}
