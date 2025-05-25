<?php
/**
 * RAEL Contact Form Styler Widget
 *
 * Style created Contact forms.
 *
 * @since      1.0.0
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Responsive_Addons_For_Elementor\Traits\Missing_Dependency;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}
/**
 * Elementor 'Cf Styler' widget class
 *
 * @since 1.1.0
 */
class Responsive_Addons_For_Elementor_Cf_Styler extends Widget_Base {
	use Missing_Dependency;

	/**
	 * Get widget name.
	 *
	 * Retrieve 'RAEL Cf Styler' widget name.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael_cf_styler';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve 'RAEL Cf Styler' widget title.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Contact Form 7 Styler', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve 'RAEL Cf Styler' widget icon.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-form-horizontal rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the RAEL Cf Styler widget belongs to.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the RAEL Cf Styler widget belongs to.
	 *
	 * @since 1.1.0
	 * @access protected
	 *
	 * @return array Widget categories.
	 */
	protected function get_cf7_forms() {
		$field_options = array();

		if ( class_exists( 'WPCF7_ContactForm' ) ) {
			$args               = array(
				'post_type'      => 'wpcf7_contact_form',
				'posts_per_page' => -1,
			);
			$forms              = get_posts( $args );
			$field_options['0'] = 'Select';
			if ( $forms ) {
				foreach ( $forms as $form ) {
					$field_options[ $form->ID ] = $form->post_title;
				}
			}
			if ( empty( $field_options ) ) {
				$field_options = array(
					'-1' => __( 'No Contact Form 7 yet.', 'responsive-addons-for-elementor' ),
				);
			}
		}
		return $field_options;
	}

	/**
	 * Register 'RAEL Cf Styler' widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 *
	 * @since 1.5.0 Added a condition to display a warning message in the editor when the dependency plugin is not activated.
	 *
	 * @access protected
	 */
	protected function register_controls() {
		if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
			$this->register_content_tab_missing_dep_warning_controls( 'Contact Form 7', 'contact form 7' );
			return;
		}

		$this->start_controls_section(
			'general_section',
			array(
				'label' => __( 'General Settings', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'select_form',
			array(
				'label'   => __( 'Select Form', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_cf7_forms(),
				'default' => '0',
				'help'    => __( 'Choose the CF form for styling', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'select_field_style',
			array(
				'label'        => __( 'Field Style', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'box',
				'options'      => array(
					'box'       => __( 'Box', 'responsive-addons-for-elementor' ),
					'underline' => __( 'Underline', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'rael-cf7-style-',
			)
		);

		$this->add_control(
			'select_field_size',
			array(
				'label'        => __( 'Field Size', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'sm',
				'options'      => array(
					'xs' => __( 'Extra Small', 'responsive-addons-for-elementor' ),
					'sm' => __( 'Small', 'responsive-addons-for-elementor' ),
					'md' => __( 'Medium', 'responsive-addons-for-elementor' ),
					'lg' => __( 'Large', 'responsive-addons-for-elementor' ),
					'xl' => __( 'Extra Large', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'rael-cf7-input-size-',
			)
		);

		$this->add_responsive_control(
			'field_padding',
			array(
				'label'      => __( 'Field Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-cf7-style select[multiple="multiple"]'  => 'padding: 0px;',
					'{{WRAPPER}} .rael-cf7-style select[multiple="multiple"] option'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rael-cf7-style input[type="checkbox"] + span:before,{{WRAPPER}} .rael-cf7-style input[type="radio"] + span:before' => 'height: {{TOP}}{{UNIT}}; width: {{TOP}}{{UNIT}};',
					'{{WRAPPER}}.rael-cf7-style-underline input[type="checkbox"] + span:before,{{WRAPPER}} .rael-cf7-style-underline input[type="radio"] + span:before' => 'height: {{TOP}}{{UNIT}}; width: {{TOP}}{{UNIT}};',
					'{{WRAPPER}} .rael-cf7-style input[type="checkbox"]:checked + span:before, {{WRAPPER}}.rael-cf7-style-underline input[type="checkbox"]:checked + span:before' => 'font-size: calc({{BOTTOM}}{{UNIT}} / 1.2);',
					'{{WRAPPER}} .rael-cf7-style input:not([type="submit"]), {{WRAPPER}} .rael-cf7-style select, {{WRAPPER}} .rael-cf7-style textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-moz-range-thumb' => 'font-size: {{BOTTOM}}{{UNIT}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-webkit-slider-thumb' => 'font-size: {{BOTTOM}}{{UNIT}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-ms-thumb' => 'font-size: {{BOTTOM}}{{UNIT}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-moz-range-track' => 'font-size: {{BOTTOM}}{{UNIT}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-ms-fill-upper' => 'font-size: {{BOTTOM}}{{UNIT}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-webkit-slider-runnable-track' => 'font-size: {{BOTTOM}}{{UNIT}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-ms-fill-lower' => 'font-size: {{BOTTOM}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'field_background_color',
			array(
				'label'     => __( 'Field Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f7f7f7',
				'selectors' => array(
					'{{WRAPPER}} .rael-cf7-style input:not([type=submit]), {{WRAPPER}} .rael-cf7-style select, {{WRAPPER}} .rael-cf7-style textarea, {{WRAPPER}} .rael-cf7-style .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}} .rael-cf7-style .wpcf7-acceptance input[type="checkbox"] + span:before, {{WRAPPER}} .rael-cf7-style .wpcf7-radio input[type="radio"]:not(:checked) + span:before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-ms-fill-lower,{{WRAPPER}} .rael-cf7-style input[type=range]:focus::-ms-fill-lower' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-ms-fill-upper,{{WRAPPER}} .rael-cf7-style input[type=range]:focus::-ms-fill-upper' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-moz-range-track,{{WRAPPER}} input[type=range]:focus::-moz-range-track' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.rael-cf7-style-box .wpcf7-radio input[type="radio"]:checked + span:before, {{WRAPPER}}.rael-cf7-style-underline .wpcf7-radio input[type="radio"]:checked + span:before' => 'box-shadow:inset 0px 0px 0px 4px {{VALUE}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-webkit-slider-runnable-track,{{WRAPPER}} .rael-cf7-style input[type=range]:focus::-webkit-slider-runnable-track' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'field_label_color',
			array(
				'label'     => __( 'Label Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-cf7-style .wpcf7 form.wpcf7-form:not(input)' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'input_color',
			array(
				'label'     => __( 'Input Text / Placeholder Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-cf7-style .wpcf7 input:not([type=submit]),{{WRAPPER}} .rael-cf7-style .wpcf7 input::placeholder, {{WRAPPER}} .rael-cf7-style .wpcf7 select, {{WRAPPER}} .rael-cf7-style .wpcf7 textarea, {{WRAPPER}} .rael-cf7-style .wpcf7 textarea::placeholder,{{WRAPPER}} .rael-cf7-style .rael-cf7-select-custom:after' => 'color: {{VALUE}};',
					'{{WRAPPER}}.elementor-widget-rael-cf7-styler .wpcf7-checkbox input[type="checkbox"]:checked + span:before, {{WRAPPER}}.elementor-widget-rael-cf7-styler .wpcf7-acceptance input[type="checkbox"]:checked + span:before' => 'color: {{VALUE}};',
					'{{WRAPPER}}.rael-cf7-style-box .wpcf7-radio input[type="radio"]:checked + span:before, {{WRAPPER}}.rael-cf7-style-underline .wpcf7-radio input[type="radio"]:checked + span:before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-webkit-slider-thumb' => 'border: 1px solid {{VALUE}}; background: {{VALUE}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-moz-range-thumb' => 'border: 1px solid {{VALUE}}; background: {{VALUE}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-ms-thumb' => 'border: 1px solid {{VALUE}}; background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'input_border_style',
			array(
				'label'       => __( 'Border Style', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'solid',
				'label_block' => false,
				'options'     => array(
					'none'   => __( 'None', 'responsive-addons-for-elementor' ),
					'solid'  => __( 'Solid', 'responsive-addons-for-elementor' ),
					'double' => __( 'Double', 'responsive-addons-for-elementor' ),
					'dotted' => __( 'Dotted', 'responsive-addons-for-elementor' ),
					'dashed' => __( 'Dashed', 'responsive-addons-for-elementor' ),
				),
				'condition'   => array(
					'select_field_style' => 'box',
				),
				'selectors'   => array(
					'{{WRAPPER}} .rael-cf7-style input:not([type=submit]), {{WRAPPER}} .rael-cf7-style select,{{WRAPPER}} .rael-cf7-style textarea,{{WRAPPER}}.rael-cf7-style-box .wpcf7-checkbox input[type="checkbox"]:checked + span:before, {{WRAPPER}}.rael-cf7-style-box .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.rael-cf7-style-box .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}}.rael-cf7-style-box .wpcf7-acceptance input[type="checkbox"] + span:before, {{WRAPPER}}.rael-cf7-style-box .wpcf7-radio input[type="radio"] + span:before' => 'border-style: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'input_border_width',
			array(
				'label'      => __( 'Border Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'default'    => array(
					'top'    => '1',
					'bottom' => '1',
					'left'   => '1',
					'right'  => '1',
					'unit'   => 'px',
				),
				'condition'  => array(
					'select_field_style'  => 'box',
					'input_border_style!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-cf7-style input:not([type=submit]), {{WRAPPER}} .rael-cf7-style select,{{WRAPPER}} .rael-cf7-style textarea,{{WRAPPER}}.rael-cf7-style-box .wpcf7-checkbox input[type="checkbox"]:checked + span:before, {{WRAPPER}}.rael-cf7-style-box .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.rael-cf7-style-box .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}}.rael-cf7-style-box .wpcf7-acceptance input[type="checkbox"] + span:before,{{WRAPPER}}.rael-cf7-style-box .wpcf7-radio input[type="radio"] + span:before' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'input_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'select_field_style'  => 'box',
					'input_border_style!' => 'none',
				),
				'default'   => '#eaeaea',
				'selectors' => array(
					'{{WRAPPER}} .rael-cf7-style input:not([type=submit]), {{WRAPPER}} .rael-cf7-style select,{{WRAPPER}} .rael-cf7-style textarea,{{WRAPPER}}.rael-cf7-style-box .wpcf7-checkbox input[type="checkbox"]:checked + span:before, {{WRAPPER}}.rael-cf7-style-box .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.rael-cf7-style-box .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}}.rael-cf7-style-box .wpcf7-acceptance input[type="checkbox"] + span:before, {{WRAPPER}}.rael-cf7-style-box .wpcf7-radio input[type="radio"] + span:before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-webkit-slider-runnable-track' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-moz-range-track' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-ms-fill-lower' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-ms-fill-upper' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'border_bottom',
			array(
				'label'      => __( 'Border Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'default'    => array(
					'size' => '2',
					'unit' => 'px',
				),
				'condition'  => array(
					'select_field_style' => 'underline',
				),
				'selectors'  => array(
					'{{WRAPPER}}.rael-cf7-style-underline input:not([type=submit]),{{WRAPPER}}.rael-cf7-style-underline select,{{WRAPPER}}.rael-cf7-style-underline textarea' => 'border-width: 0 0 {{SIZE}}{{UNIT}} 0; border-style: solid;',
					'{{WRAPPER}}.rael-cf7-style-underline .wpcf7-checkbox input[type="checkbox"]:checked + span:before, {{WRAPPER}}.rael-cf7-style-underline .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.rael-cf7-style-underline .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}}.rael-cf7-style-underline .wpcf7-acceptance input[type="checkbox"] + span:before,{{WRAPPER}} .wpcf7-radio input[type="radio"] + span:before' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid; box-sizing: content-box;',
				),
			)
		);

		$this->add_control(
			'border_color_underline',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'select_field_style' => 'underline',
				),
				'default'   => '#c4c4c4',
				'selectors' => array(
					'{{WRAPPER}}.rael-cf7-style-underline input:not([type=submit]),{{WRAPPER}}.rael-cf7-style-underline select,{{WRAPPER}}.rael-cf7-style-underline textarea, {{WRAPPER}}.rael-cf7-style-underline .wpcf7-checkbox input[type="checkbox"]:checked + span:before, {{WRAPPER}}.rael-cf7-style-underline .wpcf7-checkbox input[type="checkbox"] + span:before, {{WRAPPER}}.rael-cf7-style-underline .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}}.rael-cf7-style-underline .wpcf7-acceptance input[type="checkbox"] + span:before, {{WRAPPER}} .wpcf7-radio input[type="radio"] + span:before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .rael-cf7-style-underline input[type=range]::-webkit-slider-runnable-track' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-moz-range-track' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-ms-fill-lower' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-ms-fill-upper' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'border_active_color',
			array(
				'label'     => __( 'Border Active Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-cf7-style .wpcf7 form input:not([type=submit]):focus, {{WRAPPER}} .rael-cf7-style select:focus, {{WRAPPER}} .rael-cf7-style .wpcf7 textarea:focus, {{WRAPPER}} .rael-cf7-style .wpcf7-checkbox input[type="checkbox"]:checked + span:before,{{WRAPPER}} .rael-cf7-style .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}} .rael-cf7-style .wpcf7-radio input[type="radio"]:checked + span:before' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'input_radius',
			array(
				'label'      => __( 'Rounded Corners', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-cf7-style input:not([type="submit"]), {{WRAPPER}} .rael-cf7-style select, {{WRAPPER}} .rael-cf7-style textarea, {{WRAPPER}} .wpcf7-checkbox input[type="checkbox"] + span:before, {{WRAPPER}} .wpcf7-acceptance input[type="checkbox"] + span:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'    => '0',
					'bottom' => '0',
					'left'   => '0',
					'right'  => '0',
					'unit'   => 'px',
				),
			)
		);

		$this->add_responsive_control(
			'rael_text_align',
			array(
				'label'     => __( 'Field Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
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
				'selectors' => array(
					'.rael-cf7-style .wpcf7, {{WRAPPER}} .rael-cf7-style input:not([type=submit]),{{WRAPPER}} .rael-cf7-style textarea' => 'text-align: {{VALUE}};',
					' {{WRAPPER}} .rael-cf7-style select' => 'text-align-last:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'radio_check_style',
			array(
				'label' => __( 'Radio & Checkbox', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'radio_check_override',
			array(
				'label'        => __( 'Override Current Style', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
				'prefix_class' => 'rael-cf7-check-',
			)
		);

		$this->add_responsive_control(
			'radio_check_border_bottom',
			array(
				'label'      => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 15,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => '20',
					'unit' => 'px',
				),
				'condition'  => array(
					'radio_check_override!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}}.rael-cf7-check-yes .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.rael-cf7-check-yes .wpcf7-acceptance input[type="checkbox"] + span:before,{{WRAPPER}}.rael-cf7-check-yes .wpcf7-radio input[type="radio"] + span:before' => 'width: {{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-cf7-check-yes .wpcf7-checkbox input[type="checkbox"]:checked + span:before,{{WRAPPER}}.rael-cf7-check-yes .wpcf7-acceptance input[type="checkbox"]:checked + span:before'  => 'font-size: calc( {{SIZE}}{{UNIT}} / 1.2 );',
					'{{WRAPPER}}.rael-cf7-check-yes input[type=range]::-moz-range-track' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-cf7-check-yes input[type=range]::-ms-fill-upper' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-cf7-check-yes input[type=range]::-ms-fill-lower' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-cf7-check-yes input[type=range]::-webkit-slider-runnable-track' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-cf7-check-yes input[type=range]::-moz-range-thumb' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-cf7-check-yes input[type=range]::-ms-thumb' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-cf7-check-yes input[type=range]::-webkit-slider-thumb' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'radio_check_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f7f7f7',
				'condition' => array(
					'radio_check_override!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}}.rael-cf7-check-yes .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.rael-cf7-check-yes .wpcf7-acceptance input[type="checkbox"] + span:before, {{WRAPPER}}.rael-cf7-check-yes .wpcf7-radio input[type="radio"]:not(:checked) + span:before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.rael-cf7-check-yes input[type=range]::-ms-fill-upper,{{WRAPPER}}.rael-cf7-check-yes input[type=range]:focus::-ms-fill-upper' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.rael-cf7-check-yes input[type=range]::-ms-fill-lower,{{WRAPPER}}.rael-cf7-check-yes input[type=range]:focus::-ms-fill-lower' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.rael-cf7-check-yes .wpcf7-radio input[type="radio"]:checked + span:before' => 'box-shadow:inset 0px 0px 0px 4px {{VALUE}};',
					'{{WRAPPER}}.rael-cf7-check-yes input[type=range]::-webkit-slider-runnable-track,{{WRAPPER}}.rael-cf7-check-yes input[type=range]:focus::-webkit-slider-runnable-track' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.rael-cf7-check-yes input[type=range]::-moz-range-track,{{WRAPPER}} input[type=range]:focus::-moz-range-track' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'radio_check_selected_color',
			array(
				'label'     => __( 'Selected Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'condition' => array(
					'radio_check_override!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}}.rael-cf7-check-yes .wpcf7-checkbox input[type="checkbox"]:checked + span:before,{{WRAPPER}}.rael-cf7-check-yes .wpcf7-acceptance input[type="checkbox"]:checked + span:before' => 'color: {{VALUE}};',
					'{{WRAPPER}}.rael-cf7-check-yes .wpcf7-radio input[type="radio"]:checked + span:before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.rael-cf7-check-yes input[type=range]::-webkit-slider-thumb' => 'border: 1px solid {{VALUE}}; background: {{VALUE}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-moz-range-thumb' => 'border: 1px solid {{VALUE}}; background: {{VALUE}};',
					'{{WRAPPER}} .rael-cf7-style input[type=range]::-ms-thumb' => 'border: 1px solid {{VALUE}}; background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'radio_check_label_color',
			array(
				'label'     => __( 'Label Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'condition' => array(
					'radio_check_override!' => '',
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-cf7-style input[type="checkbox"] + span, .rael-cf7-style input[type="radio"] + span' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'radio_check_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'radio_check_override!' => '',
				),
				'default'   => '#eaeaea',
				'selectors' => array(
					'{{WRAPPER}}.rael-cf7-check-yes .wpcf7-checkbox input[type="checkbox"]:checked + span:before, {{WRAPPER}}.rael-cf7-check-yes .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.rael-cf7-check-yes .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}}.rael-cf7-check-yes .wpcf7-acceptance input[type="checkbox"] + span:before, {{WRAPPER}}.rael-cf7-check-yes .wpcf7-radio input[type="radio"] + span:before' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'radio_check_border_size',
			array(
				'label'      => __( 'Border Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'default'    => array(
					'size' => '1',
					'unit' => 'px',
				),
				'condition'  => array(
					'radio_check_override!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}}.rael-cf7-check-yes .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.rael-cf7-check-yes .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}}.rael-cf7-check-yes .wpcf7-acceptance input[type="checkbox"] + span:before, {{WRAPPER}}.rael-cf7-check-yes .wpcf7-radio input[type="radio"] + span:before, {{WRAPPER}}.rael-cf7-check-yes .wpcf7-checkbox input[type="checkbox"]:checked + span:before' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
				),
			)
		);

		$this->add_responsive_control(
			'radio_check_rounded_corners',
			array(
				'label'      => __( 'Checkbox Rounded Corners', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'condition'  => array(
					'radio_check_override!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}}.rael-cf7-check-yes .wpcf7-checkbox input[type="checkbox"] + span:before, {{WRAPPER}}.rael-cf7-check-yes .wpcf7-acceptance input[type="checkbox"] + span:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'    => '0',
					'bottom' => '0',
					'left'   => '0',
					'right'  => '0',
					'unit'   => 'px',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'submit_button_style',
			array(
				'label' => __( 'Submit Button', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_responsive_control(
			'submit_button_align',
			array(
				'label'        => __( 'Button Alignment', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'    => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'default'      => 'left',
				'prefix_class' => 'rael%s-cf7-button-',
				'toggle'       => false,
			)
		);

		$this->add_control(
			'button_size',
			array(
				'label'        => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'sm',
				'options'      => array(
					'xs' => __( 'Extra Small', 'responsive-addons-for-elementor' ),
					'sm' => __( 'Small', 'responsive-addons-for-elementor' ),
					'md' => __( 'Medium', 'responsive-addons-for-elementor' ),
					'lg' => __( 'Large', 'responsive-addons-for-elementor' ),
					'xl' => __( 'Extra Large', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'rael-cf7-btn-size-',
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-cf7-style input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-cf7-style input[type="submit"]' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'button_background_color',
				'label'          => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'fields_options' => array(
					'color' => array(
						'global'   => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
					),
				),
				'selector'       => '{{WRAPPER}} .rael-cf7-style input[type="submit"]',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border',
				'label'       => __( 'Border', 'responsive-addons-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .rael-cf7-style input[type="submit"]',
			)
		);

		$this->add_responsive_control(
			'btn_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-cf7-style input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .rael-cf7-style input[type="submit"]',
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
			'button_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-cf7-style input[type="submit"]:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => __( 'Border Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-cf7-style input[type="submit"]:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background_hover_color',
				'label'    => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-cf7-style input[type="submit"]:hover',
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'messages_section',
			array(
				'label' => __( 'Success / Error Message', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'field_validation',
			array(
				'label'     => __( 'Field Validation', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'message_position',
			array(
				'label'        => __( 'Message Positions', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'default',
				'options'      => array(
					'default'      => __( 'Default', 'responsive-addons-for-elementor' ),
					'bottom_right' => __( 'Bottom Right Side of the Field', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'rael-cf7-highlight-style-',
			)
		);

		$this->add_control(
			'message_color',
			array(
				'label'     => __( 'Message Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff0000',
				'condition' => array(
					'message_position' => 'default',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-cf7-style span.wpcf7-not-valid-tip' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'message_highlight_color',
			array(
				'label'     => __( 'Message Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'condition' => array(
					'message_position' => 'bottom_right',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-cf7-style span.wpcf7-not-valid-tip' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'message_background_color',
			array(
				'label'     => __( 'Message Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff0000',
				'condition' => array(
					'message_position' => 'bottom_right',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-cf7-style span.wpcf7-not-valid-tip' => 'background-color: {{VALUE}}; padding: 0.1em 0.8em;',
				),
			)
		);

		$this->add_control(
			'hightlight_borders',
			array(
				'label'        => __( 'Highlight Borders', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
				'prefix_class' => 'rael-cf7-highlight-',
			)
		);

		$this->add_control(
			'highlight_border_color',
			array(
				'label'     => __( 'Highlight Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff0000',
				'condition' => array(
					'hightlight_borders' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-cf7-style .wpcf7-form-control.wpcf7-not-valid, {{WRAPPER}} .rael-cf7-style .wpcf7-form-control.wpcf7-not-valid .wpcf7-list-item-label:before' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'cf7_message_typo',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-cf7-style span.wpcf7-not-valid-tip',
			)
		);

		$this->add_control(
			'field_validation_message',
			array(
				'label'     => __( 'Form Success / Error Validation', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'success_message_color',
			array(
				'label'     => __( 'Success Message Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-cf7-style .wpcf7-mail-sent-ok,{{WRAPPER}} .rael-cf7-style .wpcf7 form.sent .wpcf7-response-output' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'success_message_background_color',
			array(
				'label'     => __( 'Success Message Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-cf7-style .wpcf7-mail-sent-ok,{{WRAPPER}} .rael-cf7-style .wpcf7 form.sent .wpcf7-response-output' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'success_border_color',
			array(
				'label'     => __( 'Success Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#008000',
				'condition' => array(
					'border_size!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-cf7-style .wpcf7-mail-sent-ok,{{WRAPPER}} .rael-cf7-style .wpcf7 form.sent .wpcf7-response-output' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'error_message_color',
			array(
				'label'     => __( 'Error Message Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#008000',
				'selectors' => array(
					'{{WRAPPER}} .rael-cf7-style .wpcf7 .wpcf7-validation-errors, {{WRAPPER}} .rael-cf7-style div.wpcf7-mail-sent-ng,{{WRAPPER}} .rael-cf7-style .wpcf7-acceptance-missing,{{WRAPPER}} .rael-cf7-style .wpcf7 .wpcf7-response-output' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'error_message_background_color',
			array(
				'label'     => __( 'Error Message Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-cf7-style .wpcf7 .wpcf7-validation-errors, {{WRAPPER}} .rael-cf7-style div.wpcf7-mail-sent-ng,{{WRAPPER}} .rael-cf7-style .wpcf7-acceptance-missing,{{WRAPPER}} .rael-cf7-style .wpcf7 .wpcf7-response-output' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'error_border_color',
			array(
				'label'     => __( 'Error Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff0000',
				'condition' => array(
					'border_size!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-cf7-style .wpcf7 .wpcf7-validation-errors, {{WRAPPER}} .rael-cf7-style div.wpcf7-mail-sent-ng,{{WRAPPER}} .rael-cf7-style .wpcf7-acceptance-missing,{{WRAPPER}} .rael-cf7-style .wpcf7 form.invalid .wpcf7-response-output,{{WRAPPER}} .rael-cf7-style .wpcf7 form.failed .wpcf7-response-output,{{WRAPPER}} .rael-cf7-style .wpcf7 form.aborted .wpcf7-response-output ,{{WRAPPER}} .rael-cf7-style .wpcf7 form.spam .wpcf7-response-output,{{WRAPPER}} .rael-cf7-style .wpcf7 form.unaccepted .wpcf7-response-output' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'border_size',
			array(
				'label'      => __( 'Border Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => '2',
					'bottom' => '2',
					'left'   => '2',
					'right'  => '2',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-cf7-style .wpcf7 .wpcf7-validation-errors, {{WRAPPER}} .rael-cf7-style div.wpcf7-mail-sent-ng,{{WRAPPER}} .rael-cf7-style .wpcf7-acceptance-missing,{{WRAPPER}} .rael-cf7-style .wpcf7 form .wpcf7-response-output' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
				),
			)
		);

		$this->add_responsive_control(
			'valid_message_radius',
			array(
				'label'      => __( 'Rounded Corners', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-cf7-style .wpcf7 .wpcf7-validation-errors, {{WRAPPER}} .rael-cf7-style div.wpcf7-mail-sent-ng, {{WRAPPER}} .rael-cf7-style .wpcf7-acceptance-missing,{{WRAPPER}} .rael-cf7-style .wpcf7 form .wpcf7-response-output' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'valid_message_padding',
			array(
				'label'      => __( 'Message Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-cf7-style .wpcf7 .wpcf7-validation-errors, {{WRAPPER}} .rael-cf7-style div.wpcf7-mail-sent-ng, {{WRAPPER}} .rael-cf7-style .wpcf7-acceptance-missing,{{WRAPPER}} .rael-cf7-style .wpcf7 form .wpcf7-response-output' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'cf7_validation_typo',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-cf7-style .wpcf7 .wpcf7-validation-errors, {{WRAPPER}} .rael-cf7-style div.wpcf7-mail-sent-ng,{{WRAPPER}} .rael-cf7-style .wpcf7-mail-sent-ok,{{WRAPPER}} .rael-cf7-style .wpcf7-acceptance-missing,{{WRAPPER}} .rael-cf7-style .wpcf7 form .wpcf7-response-output',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'form_input_text_styling',
			array(
				'label' => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'spacing_label_input',
			array(
				'label'      => __( 'Between Label & Input', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 60,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 5,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-cf7-style input:not([type=submit]):not([type=checkbox]):not([type=radio]), {{WRAPPER}} .rael-cf7-style select, {{WRAPPER}} .rael-cf7-style textarea, {{WRAPPER}} .rael-cf7-style span.wpcf7-list-item' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'spacing_between_fields',
			array(
				'label'      => __( 'Between Fields', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 60,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 10,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-cf7-style input:not([type=submit]):not([type=checkbox]):not([type=radio]), {{WRAPPER}} .rael-cf7-style select, {{WRAPPER}} .rael-cf7-style textarea, {{WRAPPER}} .rael-cf7-style span.wpcf7-list-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'form_input_styling_typo',
			array(
				'label' => __( 'Typography', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'label_typography',
			array(
				'label'     => __( 'Form Label', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'form_label_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-cf7-style .wpcf7 form.wpcf7-form label',
			)
		);

		$this->add_control(
			'input_placeholder',
			array(
				'label'     => __( 'Input Text / Placeholder', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'input_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-cf7-style .wpcf7 input:not([type=submit]), {{WRAPPER}} .rael-cf7-style .wpcf7 input::placeholder, {{WRAPPER}} .wpcf7 select,{{WRAPPER}} .rael-cf7-style .wpcf7 textarea, {{WRAPPER}} .rael-cf7-style .wpcf7 textarea::placeholder, {{WRAPPER}} .rael-cf7-style input[type=range]::-webkit-slider-thumb,{{WRAPPER}} .rael-cf7-style .rael-cf7-select-custom',
			)
		);

		$this->add_control(
			'button_typography',
			array(
				'label'     => __( 'Button', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-cf7-style input[type=submit]',
			)
		);

		$this->add_control(
			'radio_check_typography',
			array(
				'label'     => __( 'Radio Button & Checkbox', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'radio_check_override!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'radio_check_typography',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'condition' => array(
					'radio_check_override!' => '',
				),
				'selector'  => '{{WRAPPER}} .rael-cf7-style input[type="checkbox"] + span, .rael-cf7-style input[type="radio"] + span',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render Editor Script. Which will show error at editor.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function render_editor_script() {

		if ( \Elementor\Plugin::instance()->editor->is_edit_mode() === false ) {
			return;
		}

		$pre_url = wpcf7_get_request_uri();

		if ( strpos( $pre_url, 'admin-ajax.php' ) === false ) {
			return;
		}

		?><script type="text/javascript">
			jQuery( document ).ready( function( $ ) {

				$( '.rael-cf-container' ).each( function() {

					var $node_id 	= '<?php echo esc_attr( $this->get_id() ); ?>';
					var	scope 		= $( '[data-id="' + $node_id + '"]' );
					var selector 	= $(this);

					if ( selector.closest( scope ).length < 1 ) {
						return;
					}

					if ( selector.find( 'div.wpcf7 > form' ).length < 1 ) {
						return;
					}

					selector.find( 'div.wpcf7 > form' ).each( function() {
						var $form = $( this );
						wpcf7.initForm( $form );
					} );
				});
			});
		</script>
		<?php
	}

	/**
	 * Render CF7 Styler output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function render() {

		if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
			return;
		}

		$settings      = $this->get_settings();
		$node_id       = $this->get_id();
		$field_options = array();
		$classname     = '';

		$args = array(
			'post_type'      => 'wpcf7_contact_form',
			'posts_per_page' => -1,
		);

		$forms              = get_posts( $args );
		$field_options['0'] = __( 'select', 'responsive-addons-for-elementor' );
		if ( $forms ) {
			foreach ( $forms as $form ) {
				$field_options[ $form->ID ] = $form->post_title;
			}
		}

		$forms = $this->get_cf7_forms();

		$html = '';

		if ( ! empty( $forms ) && ! isset( $forms[-1] ) ) {
			if ( '0' === $settings['select_form'] ) {
				$html = __( 'Please select a Contact Form 7.', 'responsive-addons-for-elementor' );
			} else {
				?>
				<div class = "rael-cf-container">
					<div class = "rael-cf7 rael-cf7-style elementor-clickable">
						<?php
						if ( $settings['select_form'] ) {
							echo do_shortcode( '[contact-form-7 id=' . $settings['select_form'] . ']' );
						}
						?>
					</div>
				</div>
				<?php
			}
		} else {
			$html = __( 'You have not added any Contact Form 7 yet.', 'responsive-addons-for-elementor' );
		}
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$this->render_editor_script();
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/contact-form-7-styler';
	}
}
