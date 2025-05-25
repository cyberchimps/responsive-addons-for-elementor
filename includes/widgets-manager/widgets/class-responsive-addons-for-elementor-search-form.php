<?php
/**
 * RAEL Search Form Widget
 *
 * @since      1.2.0
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Elementor 'Search Form' widget.
 *
 * Elementor widget that displays an Search Form.
 *
 * @since 1.2.0
 */
class Responsive_Addons_For_Elementor_Search_Form extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-search-form';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Search Bar', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve search form widget icon.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-site-search rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the search form widget belongs to.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Register widget controls
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'search_content',
			array(
				'label' => __( 'Search Form', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_skin',
			array(
				'label'              => __( 'Skin', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'classic',
				'options'            => array(
					'classic'     => __( 'Classic', 'responsive-addons-for-elementor' ),
					'minimal'     => __( 'Minimal', 'responsive-addons-for-elementor' ),
					'full_screen' => __( 'Full Screen', 'responsive-addons-for-elementor' ),
				),
				'prefix_class'       => 'rael-elementor-search-form--skin-',
				'render_type'        => 'template',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_placeholder',
			array(
				'label'     => __( 'Placeholder', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'separator' => 'before',
				'default'   => __( 'Search', 'responsive-addons-for-elementor' ) . '...',
			)
		);

		$this->add_control(
			'rael_heading_button_content',
			array(
				'label'     => __( 'Button', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_skin' => 'classic',
				),
			)
		);

		$this->add_control(
			'rael_button_type',
			array(
				'label'        => __( 'Type', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'icon',
				'options'      => array(
					'icon' => __( 'Icon', 'responsive-addons-for-elementor' ),
					'text' => __( 'Text', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'rael-elementor-search-form--button-type-',
				'render_type'  => 'template',
				'condition'    => array(
					'rael_skin' => 'classic',
				),
			)
		);

		$this->add_control(
			'rael_button_text',
			array(
				'label'     => __( 'Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Search', 'responsive-addons-for-elementor' ),
				'separator' => 'after',
				'condition' => array(
					'rael_button_type' => 'text',
					'rael_skin'        => 'classic',
				),
			)
		);

		$this->add_control(
			'rael_icon',
			array(
				'label'        => __( 'Icon', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'default'      => 'search',
				'options'      => array(
					'search' => array(
						'title' => __( 'Search', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-search',
					),
					'arrow'  => array(
						'title' => __( 'Arrow', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-arrow-right',
					),
				),
				'render_type'  => 'template',
				'prefix_class' => 'rael-elementor-search-form--icon-',
				'condition'    => array(
					'rael_button_type' => 'icon',
					'rael_skin'        => 'classic',
				),
			)
		);

		$this->add_control(
			'rael_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 50,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-elementor-search-form__container' => 'min-height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .rael-elementor-search-form__submit' => 'min-width: {{SIZE}}{{UNIT}}',
					'body:not(.rtl) {{WRAPPER}} .rael-elementor-search-form__icon' => 'padding-left: calc({{SIZE}}{{UNIT}} / 3)',
					'body.rtl {{WRAPPER}} .rael-elementor-search-form__icon' => 'padding-right: calc({{SIZE}}{{UNIT}} / 3)',
					'{{WRAPPER}} .rael-elementor-search-form__input, {{WRAPPER}}.rael-elementor-search-form--button-type-text .rael-elementor-search-form__submit' => 'padding-left: calc({{SIZE}}{{UNIT}} / 3); padding-right: calc({{SIZE}}{{UNIT}} / 3)',
				),
				'condition' => array(
					'rael_skin!' => 'full_screen',
				),
			)
		);

		$this->add_control(
			'rael_toggle_button_content',
			array(
				'label'     => __( 'Toggle', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_skin' => 'full_screen',
				),
			)
		);

		$this->add_control(
			'rael_toggle_align',
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
					'{{WRAPPER}} .rael-elementor-search-form' => 'text-align: {{VALUE}}',
				),
				'condition' => array(
					'rael_skin' => 'full_screen',
				),
			)
		);

		$this->add_control(
			'rael_toggle_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 33,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-elementor-search-form__toggle i' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'rael_skin' => 'full_screen',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_input_style',
			array(
				'label' => __( 'Input', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_icon_size_minimal',
			array(
				'label'     => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-elementor-search-form__icon' => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'rael_skin' => 'minimal',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_overlay_background_color',
			array(
				'label'     => __( 'Overlay Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.rael-elementor-search-form--skin-full_screen .rael-elementor-search-form__container' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'rael_skin' => 'full_screen',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'input_typography',
				'selector' => '{{WRAPPER}} input[type="search"].rael-elementor-search-form__input',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
			)
		);

		$this->start_controls_tabs( 'tabs_input_colors' );

		$this->start_controls_tab(
			'tab_input_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_input_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-elementor-search-form__input,
					{{WRAPPER}} .rael-elementor-search-form__icon,
					{{WRAPPER}} .rael-elementor-lightbox .dialog-lightbox-close-button,
					{{WRAPPER}} .rael-elementor-lightbox .dialog-lightbox-close-button:hover,
					{{WRAPPER}}.rael-elementor-search-form--skin-full_screen input[type="search"].rael-elementor-search-form__input' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_input_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}:not(.rael-elementor-search-form--skin-full_screen) .rael-elementor-search-form__container' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.rael-elementor-search-form--skin-full_screen input[type="search"].rael-elementor-search-form__input' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'rael_skin!' => 'full_screen',
				),
			)
		);

		$this->add_control(
			'rael_input_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}:not(.rael-elementor-search-form--skin-full_screen) .rael-elementor-search-form__container' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.rael-elementor-search-form--skin-full_screen input[type="search"].rael-elementor-search-form__input' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'           => 'input_box_shadow',
				'selector'       => '{{WRAPPER}} .rael-elementor-search-form__container',
				'fields_options' => array(
					'box_shadow_type' => array(
						'separator' => 'default',
					),
				),
				'condition'      => array(
					'rael_skin!' => 'full_screen',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_input_focus',
			array(
				'label' => __( 'Focus', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_input_text_color_focus',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}:not(.rael-elementor-search-form--skin-full_screen) .rael-elementor-search-form--focus .rael-elementor-search-form__input,
					{{WRAPPER}} .rael-elementor-search-form--focus .rael-elementor-search-form__icon,
					{{WRAPPER}} .elementor-lightbox .dialog-lightbox-close-button:hover,
					{{WRAPPER}}.rael-elementor-search-form--skin-full_screen input[type="search"].rael-elementor-search-form__input:focus' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_input_background_color_focus',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}:not(.rael-elementor-search-form--skin-full_screen) .rael-elementor-search-form--focus .rael-elementor-search-form__container' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.rael-elementor-search-form--skin-full_screen input[type="search"].rael-elementor-search-form__input:focus' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'rael_skin!' => 'full_screen',
				),
			)
		);

		$this->add_control(
			'rael_input_border_color_focus',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}:not(.rael-elementor-search-form--skin-full_screen) .rael-elementor-search-form--focus .rael-elementor-search-form__container' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.rael-elementor-search-form--skin-full_screen input[type="search"].rael-elementor-search-form__input:focus' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'           => 'input_box_shadow_focus',
				'selector'       => '{{WRAPPER}} .rael-elementor-search-form--focus .rael-elementor-search-form__container',
				'fields_options' => array(
					'box_shadow_type' => array(
						'separator' => 'default',
					),
				),
				'condition'      => array(
					'rael_skin!' => 'full_screen',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'rael_button_border_width',
			array(
				'label'     => __( 'Border Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}}:not(.rael-elementor-search-form--skin-full_screen) .rael-elementor-search-form__container' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.rael-elementor-search-form--skin-full_screen input[type="search"].rael-elementor-search-form__input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'rael_border_radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'default'   => array(
					'size' => 3,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}}:not(.rael-elementor-search-form--skin-full_screen) .rael-elementor-search-form__container' => 'border-radius: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.rael-elementor-search-form--skin-full_screen input[type="search"].rael-elementor-search-form__input' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			array(
				'label'     => __( 'Button', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_skin' => 'classic',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'selector'  => '{{WRAPPER}} .rael-elementor-search-form__submit',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'condition' => array(
					'rael_button_type' => 'text',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_colors' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_button_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-elementor-search-form__submit' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_button_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-elementor-search-form__submit' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_button_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-elementor-search-form__submit:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_button_background_color_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-elementor-search-form__submit:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_icon_size',
			array(
				'label'     => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-elementor-search-form__submit' => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'rael_button_type' => 'icon',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'rael_button_width',
			array(
				'label'     => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 1,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-elementor-search-form__submit' => 'min-width: calc( {{SIZE}} * {{rael_size.SIZE}}{{rael_size.UNIT}} )',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style',
			array(
				'label'     => __( 'Toggle', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_skin' => 'full_screen',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_toggle_color' );

		$this->start_controls_tab(
			'tab_toggle_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_toggle_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-elementor-search-form__toggle' => 'color: {{VALUE}}; border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_toggle_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-elementor-search-form__toggle i' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_toggle_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_toggle_color_hover',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-elementor-search-form__toggle:hover' => 'color: {{VALUE}}; border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_toggle_background_color_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-elementor-search-form__toggle i:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'rael_toggle_icon_size',
			array(
				'label'     => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .rael-elementor-search-form__toggle i:before' => 'font-size: calc({{SIZE}}em / 100)',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_toggle_border_width',
			array(
				'label'     => __( 'Border Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-elementor-search-form__toggle i' => 'border-width: {{SIZE}}{{UNIT}}',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_toggle_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-elementor-search-form__toggle i' => 'border-radius: {{SIZE}}{{UNIT}}',
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
		$settings = $this->get_settings();
		$this->add_render_attribute(
			'input',
			array(
				'placeholder' => $settings['rael_placeholder'],
				'class'       => 'rael-elementor-search-form__input',
				'type'        => 'search',
				'name'        => 's',
				'title'       => __( 'Search', 'responsive-addons-for-elementor' ),
				'value'       => get_search_query(),
			)
		);

		// Set the selected icon.
		$icon_class = '';
		if ( 'icon' === $settings['rael_button_type'] ) {
			$icon_class = 'search';

			if ( 'arrow' === $settings['rael_icon'] ) {
				$icon_class = is_rtl() ? 'arrow-left' : 'arrow-right';
			}

			$this->add_render_attribute(
				'icon',
				array(
					'class' => 'fa fa-' . $icon_class,
				)
			);
		} elseif ( 'text' === $settings['rael_button_type'] && 'full_screen' === $settings['rael_skin'] ) {
			$icon_class = 'search';
		}

		$migration_allowed = Icons_Manager::is_migration_allowed();
		$icon              = array(
			'value'   => 'fas fa-' . $icon_class,
			'library' => 'fa-solid',
		);
		?>
		<form class="rael-elementor-search-form" role="search" action="<?php echo esc_url( home_url() ); ?>" method="get">
			<?php do_action( 'rael_search_form_before_input', $this ); ?>
			<?php if ( 'full_screen' === $settings['rael_skin'] ) : ?>
				<div class="rael-elementor-search-form__toggle">
					<?php if ( ! $migration_allowed || ! Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) ) ) { ?>
						<i class="fa fas fa-search" aria-hidden="true"></i>
					<?php } ?>
					<span class="elementor-screen-only"><?php esc_html_e( 'Search', 'responsive-addons-for-elementor' ); ?></span>
				</div>
			<?php endif; ?>
			<div class="rael-elementor-search-form__container">
				<?php if ( 'minimal' === $settings['rael_skin'] ) : ?>
					<div class="rael-elementor-search-form__icon">
						<i class="fa fa-search" aria-hidden="true"></i>
						<span class="elementor-screen-only"><?php esc_html_e( 'Search', 'responsive-addons-for-elementor' ); ?></span>
					</div>
				<?php endif; ?>
				<input <?php echo wp_kses_post( $this->get_render_attribute_string( 'input' ) ); ?>>
				<?php do_action( 'rael_search_form_after_input', $this ); ?>
				<?php if ( 'classic' === $settings['rael_skin'] ) : ?>
					<button class="rael-elementor-search-form__submit" type="submit" title="<?php esc_attr_e( 'Search', 'responsive-addons-for-elementor' ); ?>" aria-label="<?php esc_attr_e( 'Search', 'responsive-addons-for-elementor' ); ?>">
						<?php if ( 'icon' === $settings['rael_button_type'] ) : ?>
							<i <?php echo wp_kses_post( $this->get_render_attribute_string( 'icon' ) ); ?> aria-hidden="true"></i>
							<span class="elementor-screen-only"><?php esc_html_e( 'Search', 'responsive-addons-for-elementor' ); ?></span>
						<?php elseif ( ! empty( $settings['rael_button_text'] ) ) : ?>
							<?php echo wp_kses_post( $settings['rael_button_text'] ); ?>
						<?php endif; ?>
					</button>
				<?php endif; ?>
				<?php if ( 'full_screen' === $settings['rael_skin'] ) : ?>
					<div class="dialog-lightbox-close-button dialog-close-button">
						<i class="eicon-close" aria-hidden="true"></i>
						<span class="elementor-screen-only"><?php esc_html_e( 'Close', 'responsive-addons-for-elementor' ); ?></span>
					</div>
				<?php endif ?>
			</div>
		</form>
		<?php
	}

	/**
	 * Render Search Form widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
		var iconClass = 'fa fas fa-search';

		if ( 'arrow' == settings.rael_icon ) {
			if ( elementorCommon.config.isRTL ) {
				iconClass = 'fa fas fa-arrow-left';
			} else {
				iconClass = 'fa fas fa-arrow-right';
			}
		}
		#>
		<form class="rael-elementor-search-form" action="" role="search">
			<# if ( 'full_screen' == settings.rael_skin ) { #>
			<div class="rael-elementor-search-form__toggle">
				<i class="fa fas fa-search" aria-hidden="true"></i>
				<span class="elementor-screen-only"><?php esc_html_e( 'Search', 'responsive-addons-for-elementor' ); ?></span>
			</div>
			<# } #>
			<div class="rael-elementor-search-form__container">
				<# if ( 'minimal' == settings.rael_skin ) { #>
				<div class="rael-elementor-search-form__icon">
					<i class="fa fas fa-search" aria-hidden="true"></i>
					<span class="elementor-screen-only"><?php esc_html_e( 'Search', 'responsive-addons-for-elementor' ); ?></span>
				</div>
				<# } #>
				<input type="search"
						name="s"
						title="<?php esc_attr_e( 'Search', 'responsive-addons-for-elementor' ); ?>"
						class="rael-elementor-search-form__input"
						placeholder="{{ settings.rael_placeholder }}">

				<# if ( 'classic' == settings.rael_skin ) { #>
				<button class="rael-elementor-search-form__submit" type="submit">
					<# if ( 'icon' == settings.rael_button_type ) { #>
					<i class="{{ iconClass }}" aria-hidden="true"></i>
					<span class="elementor-screen-only"><?php esc_html_e( 'Submit', 'responsive-addons-for-elementor' ); ?></span>
					<# } else if ( settings.rael_button_text ) { #>
					{{{ settings.rael_button_text }}}
					<# } #>
				</button>
				<# } #>
			</div>
		</form>
		<?php
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/search-form';
	}

}
