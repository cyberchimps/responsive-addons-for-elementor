<?php
/** Gravity forms styler widget Responsive Addons for Elementor
 *
 * @package responsive-addons-for-elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Responsive_Addons_For_Elementor\Traits\Missing_Dependency;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Gravity Form Styler Widget
 *
 * @package responsive-addons-for-elementor
 */
class Responsive_Addons_For_Elementor_Gf_Styler extends Widget_Base {
	use Missing_Dependency;

	/**
	 * Get widget name.
	 *
	 * Retrieve 'RAEL GF Styler' widget name.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael_gf_styler';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve 'RAEL GF Styler' widget title.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Gravity forms Styler', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve 'RAEL GF Styler' widget icon.
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
	 * Retrieve the list of categories the timeline post widget belongs to.
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
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/elementor-widgets/docs/gravity-forms-styler';
	}

	/**
	 * Returns all gravity forms with ids
	 *
	 * @since 1.1.0
	 * @return array Key Value array.
	 */
	protected function get_gravity_forms() {

		$field_options = array();

		if ( class_exists( 'GFForms' ) ) {
			$forms              = \RGFormsModel::get_forms( null, 'title' );
			$field_options['0'] = 'Select';
			if ( is_array( $forms ) ) {
				foreach ( $forms as $form ) {
					$field_options[ $form->id ] = $form->title;
				}
			}
		}

		if ( empty( $field_options ) ) {
			$field_options = array(
				'-1' => __( 'There are no Gravity Forms created.', 'responsive-addons-for-elementor' ),
			);
		}

		return $field_options;
	}

	/**
	 * Register the controls for the widget.
	 *
	 * @since 1.1.0
	 *
	 * @since 1.5.0 Added a condition to display a warning message in the editor when the dependency plugin is not activated.
	 *
	 * @return void
	 */
	protected function register_controls() {
		if ( ! class_exists( 'GFForms' ) ) {
			$this->register_content_tab_missing_dep_warning_controls( 'Gravity Forms', 'gravity forms' );
			return;
		}

		$this->start_controls_section(
			'general_settings',
			array(
				'label' => __( 'General Settings', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'select_form',
			array(
				'label'   => __( 'Select Form', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_gravity_forms(),
				'default' => '0',
			)
		);

		$this->add_control(
			'gform_ajax_option',
			array(
				'label'        => __( 'Enable AJAX Form Submission', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'default'      => 'true',
				'label_block'  => false,
				'prefix_class' => 'rael-gform-ajax-',
			)
		);

		$this->add_control(
			'gform_title_desc_option',
			array(
				'label'       => __( 'Title & Description', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'default'     => 'default',
				'options'     => array(
					'default' => __( 'From Gravity Forms', 'responsive-addons-for-elementor' ),
					'custom'  => __( 'Custom', 'responsive-addons-for-elementor' ),
					'none'    => __( 'None', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'gform_title',
			array(
				'label'     => __( 'Form Title', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'gform_title_desc_option' => 'custom',
				),
				'dynamic'   => array(
					'active' => true,
				),

			)
		);

		$this->add_control(
			'gform_desc',
			array(
				'label'     => __( 'Form Description', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXTAREA,
				'condition' => array(
					'gform_title_desc_option' => 'custom',
				),
				'dynamic'   => array(
					'active' => true,
				),
			)
		);

		$this->add_responsive_control(
			'gform_title_desc_align',
			array(
				'label'     => __( 'Title & Description </br>Alignment', 'responsive-addons-for-elementor' ),
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
				'default'   => 'left',
				'condition' => array(
					'gform_title_desc_option!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-gform-form-desc,
					{{WRAPPER}} .rael-gform-style .gform_description,
					{{WRAPPER}} .rael-gform-form-title,
					{{WRAPPER}} .rael-gform-style .gform_heading' => 'text-align: {{VALUE}};',
				),
				'toggle'    => false,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'gform_fields_section',
			array(
				'label' => __( 'Form Fields', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'gform_style',
			array(
				'label'        => __( 'Field Style', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'box',
				'options'      => array(
					'box'       => __( 'Box', 'responsive-addons-for-elementor' ),
					'underline' => __( 'Underline', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'rael-gform-style-',
			)
		);

		$this->add_control(
			'gform_input_size',
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
				'prefix_class' => 'rael-gform-input-size-',
			)
		);

		$this->add_responsive_control(
			'gform_input_padding',
			array(
				'label'      => __( 'Field Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-gform-style .ginput_container select,
					{{WRAPPER}} .rael-gform-style .ginput_container .chosen-single' => 'padding-top: calc( {{TOP}}{{UNIT}} - 2{{UNIT}} ); padding-right: {{RIGHT}}{{UNIT}}; padding-bottom: calc( {{BOTTOM}}{{UNIT}} - 2{{UNIT}} ); padding-left: {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rael-gform-style .gform_wrapper form .gform_body input:not([type="radio"]):not([type="checkbox"]):not([type="submit"]):not([type="button"]):not([type="image"]):not([type="file"]),
					{{WRAPPER}} .rael-gform-style .gform_wrapper textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rael-gform-check-style .gfield_checkbox input[type="checkbox"]:checked + label:before,
					{{WRAPPER}} .rael-gform-check-style .ginput_container_consent input[type="checkbox"]:checked + label:before'  => 'font-size: calc( {{BOTTOM}}{{UNIT}} / 1.2 );',
					'{{WRAPPER}} .rael-gform-check-style .gfield_checkbox input[type="checkbox"] + label:before,
					{{WRAPPER}} .rael-gform-check-style .gfield_radio input[type="radio"] + label:before,
					{{WRAPPER}}:not(.rael-gform-check-default-yes)  .rael-gform-check-style .gfield_radio .gchoice_label label:before,
					{{WRAPPER}} .rael-gform-check-style .ginput_container_consent input[type="checkbox"] + label:before' => 'height: {{BOTTOM}}{{UNIT}}; width: {{BOTTOM}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'gform_input_bgcolor',
			array(
				'label'     => __( 'Field Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fafafa',
				'selectors' => array(
					'{{WRAPPER}} .rael-gform-style .gform_wrapper .chosen-container-multi .chosen-choices,
					{{WRAPPER}} .rael-gform-style .gform_wrapper .chosen-container-single .chosen-single,
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=date],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=url],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=tel],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=email],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=text],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=password],
					{{WRAPPER}}:not(.rael-gform-check-default-yes)  .rael-gform-style .gfield_radio .gchoice_label label:before,
					{{WRAPPER}} .rael-gform-style .gform_wrapper textarea,
					{{WRAPPER}} .rael-gform-style .gfield_radio input[type="radio"] + label:before,
					{{WRAPPER}} .rael-gform-style .gform_wrapper select,
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=number],
					{{WRAPPER}} .rael-gform-style .gfield_checkbox input[type="checkbox"] + label:before,
					{{WRAPPER}} .rael-gform-style .gform_wrapper .gf_progressbar,
					{{WRAPPER}} .rael-gform-style .ginput_container_consent input[type="checkbox"] + label:before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-gform-style .gsection' => 'border-bottom-color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'gform_label_color',
			array(
				'label'     => __( 'Label Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-gform-style .gfield_label,
					{{WRAPPER}} .rael-gform-style .gsection_title,
					{{WRAPPER}} .rael-gform-style .ginput_product_price_label,
					{{WRAPPER}} .rael-gform-style .gfield_checkbox li label,
					{{WRAPPER}} .rael-gform-style .gf_progressbar_title,
					{{WRAPPER}} .rael-gform-style .gfield_checkbox div label,
					{{WRAPPER}} .rael-gform-style .gfield_radio li label,
					{{WRAPPER}} .rael-gform-style .ginput_product_price,
					{{WRAPPER}} .rael-gform-style .gf_page_steps,
					{{WRAPPER}} .rael-gform-style .gfield_html,
					{{WRAPPER}} .rael-gform-style .ginput_container_consent label,
					{{WRAPPER}} .rael-gform-style .gfield_radio div label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'gform_input_color',
			array(
				'label'     => __( 'Input Text / Placeholder Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-gform-style .ginput_container textarea,
					{{WRAPPER}} .rael-gform-style .ginput_container textarea::placeholder,
					{{WRAPPER}} .rael-gform-style .gform_wrapper .gfield input:not([type="radio"]):not([type="checkbox"]):not([type="submit"]):not([type="button"]):not([type="image"]):not([type="file"]),
					{{WRAPPER}} .rael-gform-style .ginput_container select,
					{{WRAPPER}} .rael-gform-style .ginput_container_consent input[type="checkbox"] + label:before,
					{{WRAPPER}} .rael-gform-style .ginput_container .chosen-single,
					{{WRAPPER}} .rael-gform-style .gform_wrapper .gfield input::placeholder,
					{{WRAPPER}} .rael-gform-style .gfield_checkbox input[type="checkbox"]:checked + label:before,
					{{WRAPPER}} .rael-gform-style .rael-gform-select-custom:after ' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .rael-gform-style .gfield_radio input[type="radio"]:checked + label:before,
					{{WRAPPER}} .rael-gform-style .gfield_radio .gchoice_button.rael-radio-active + .gchoice_label label:before' => 'background-color: {{VALUE}}; box-shadow:inset 0px 0px 0px 4px {{form_input_bgcolor.VALUE}};',
				),
			)
		);       $this->add_control(
			'gform_input_desc_color',
			array(
				'label'     => __( 'Sublabel / Description Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-gform-style .ginput_container_creditcard input + span + label,
					{{WRAPPER}} .rael-gform-style .gfield_time_hour label,
					{{WRAPPER}} .rael-gform-style .ginput_container_address label,
					{{WRAPPER}} .rael-gform-style .ginput_container_total span,
					{{WRAPPER}} .rael-gform-style .ginput_container_name input + label,
					{{WRAPPER}} .rael-gform-style .ginput_container .chosen-single + label,
					{{WRAPPER}} .rael-gform-style .gfield_time_minute label,
					{{WRAPPER}} .rael-gform-style .gform_wrapper .gfield .gfield_description,
					{{WRAPPER}} .rael-gform-style .ginput_shipping_price,
					{{WRAPPER}} .rael-gform-style .ginput_container select + label,
					{{WRAPPER}} .rael-gform-style .ginput_container input + label,
					{{WRAPPER}} .rael-gform-select-custom + label,
					{{WRAPPER}} .rael-gform-style .gform-field-label.gform-field-label--type-sub,
					{{WRAPPER}} .rael-gform-style .gsection_description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'gform_required_color',
			array(
				'label'     => __( 'Required Asterisk Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-gform-style .gform_wrapper .gfield_required' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'gform_input_border_style',
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
					'gform_style' => 'box',
				),
				'selectors'   => array(
					'{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=text],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=password],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=url],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=tel],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=number],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=date],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=email],
					{{WRAPPER}} .rael-gform-style .gform_wrapper select,
					{{WRAPPER}} .rael-gform-style .gform_wrapper .chosen-single,
					{{WRAPPER}} .rael-gform-style .gform_wrapper textarea,
					{{WRAPPER}} .rael-gform-style .gfield_checkbox input[type="checkbox"] + label:before,
					{{WRAPPER}} .rael-gform-style .ginput_container_consent input[type="checkbox"] + label:before,
					{{WRAPPER}} .rael-gform-style .gfield_radio input[type="radio"] + label:before,
					{{WRAPPER}}:not(.rael-gform-check-default-yes)  .rael-gform-style .gfield_radio .gchoice_label label:before' => 'border-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'gform_input_border_size',
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
					'gform_style'               => 'box',
					'gform_input_border_style!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=text],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=password],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=url],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=tel],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=number],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=date],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=email],
					{{WRAPPER}} .rael-gform-style .gform_wrapper select,
					{{WRAPPER}} .rael-gform-style .gform_wrapper .chosen-single,
					{{WRAPPER}} .rael-gform-style .gform_wrapper .chosen-choices,
					{{WRAPPER}} .rael-gform-style .gform_wrapper .chosen-container .chosen-drop,
					{{WRAPPER}} .rael-gform-style .gform_wrapper textarea,
					{{WRAPPER}} .rael-gform-style .gfield_checkbox input[type="checkbox"] + label:before,
					{{WRAPPER}} .rael-gform-style .ginput_container_consent input[type="checkbox"] + label:before,
					{{WRAPPER}} .rael-gform-style .gfield_radio input[type="radio"] + label:before,.gchoice_label label:before,
					{{WRAPPER}}:not(.rael-gform-check-default-yes)  .rael-gform-style .gfield_radio .gchoice_label label:before' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'gform_input_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'gform_style'               => 'box',
					'gform_input_border_style!' => 'none',
				),
				'default'   => '#eaeaea',
				'selectors' => array(
					'{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=text],
						{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=password],
						{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=url],
						{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=tel],
						{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=number],
						{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=email],
						{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=date],
						{{WRAPPER}} .rael-gform-style .gform_wrapper select,
						{{WRAPPER}} .rael-gform-style .gform_wrapper .chosen-single,
						{{WRAPPER}} .rael-gform-style .gform_wrapper .chosen-choices,
						{{WRAPPER}} .rael-gform-style .gform_wrapper .chosen-container .chosen-drop,
						{{WRAPPER}} .rael-gform-style .gform_wrapper textarea,
						{{WRAPPER}} .rael-gform-style .gfield_checkbox input[type="checkbox"] + label:before,
						{{WRAPPER}} .rael-gform-style .ginput_container_consent input[type="checkbox"] + label:before,
						{{WRAPPER}} .rael-gform-style .gfield_radio input[type="radio"] + label:before,
						{{WRAPPER}}:not(.rael-gform-check-default-yes)  .rael-gform-style .gfield_radio .gchoice_label label:before' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'gform_border_bottom',
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
					'gform_style' => 'underline',
				),
				'selectors'  => array(
					'{{WRAPPER}}.rael-gform-style-underline .gform_wrapper input[type=text],
					 {{WRAPPER}}.rael-gform-style-underline .gform_wrapper input[type=password],
					 {{WRAPPER}}.rael-gform-style-underline .gform_wrapper input[type=url],
					 {{WRAPPER}}.rael-gform-style-underline .gform_wrapper input[type=tel],
					 {{WRAPPER}}.rael-gform-style-underline .gform_wrapper input[type=number],
					 {{WRAPPER}}.rael-gform-style-underline .gform_wrapper input[type=date],
					 {{WRAPPER}}.rael-gform-style-underline .gform_wrapper input[type=email],
					 {{WRAPPER}}.rael-gform-style-underline .gform_wrapper select,
					 {{WRAPPER}}.rael-gform-style-underline .gform_wrapper .chosen-single,
					 {{WRAPPER}}.rael-gform-style-underline .gform_wrapper .chosen-choices,
					 {{WRAPPER}}.rael-gform-style-underline .gform_wrapper textarea' => 'border-width: 0 0 {{SIZE}}{{UNIT}} 0; border-style: solid;',
					'{{WRAPPER}}.rael-gform-style-underline .gform_wrapper .chosen-container .chosen-drop' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
					'{{WRAPPER}}.rael-gform-style-underline .gfield_checkbox input[type="checkbox"] + label:before,
					{{WRAPPER}}.rael-gform-style-underline .ginput_container_consent input[type="checkbox"] + label:before,
					{{WRAPPER}}.rael-gform-style-underline .gfield_radio input[type="radio"] + label:before,
					{{WRAPPER}}:not(.rael-gform-check-default-yes).rael-gform-style-underline .gfield_radio .gchoice_label label:before' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid; box-sizing: content-box;',
				),
			)
		);

		$this->add_control(
			'gform_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'gform_style' => 'underline',
				),
				'default'   => '#c4c4c4',
				'selectors' => array(
					'{{WRAPPER}}.rael-gform-style-underline .gform_wrapper input[type=text],
					 {{WRAPPER}}.rael-gform-style-underline .gform_wrapper input[type=password],
					 {{WRAPPER}}.rael-gform-style-underline .gform_wrapper input[type=url],
					 {{WRAPPER}}.rael-gform-style-underline .gform_wrapper input[type=tel],
					 {{WRAPPER}}.rael-gform-style-underline .gform_wrapper input[type=number],
					 {{WRAPPER}}.rael-gform-style-underline .gform_wrapper input[type=date],
					 {{WRAPPER}}.rael-gform-style-underline .gform_wrapper input[type=email],
					 {{WRAPPER}}.rael-gform-style-underline .gform_wrapper select,
					 {{WRAPPER}}.rael-gform-style-underline .gform_wrapper .chosen-single,
					 {{WRAPPER}}.rael-gform-style-underline .gform_wrapper .chosen-choices,
					 {{WRAPPER}}.rael-gform-style-underline .gform_wrapper .chosen-container .chosen-drop,
					 {{WRAPPER}}.rael-gform-style-underline .gform_wrapper textarea,
					 {{WRAPPER}}.rael-gform-style-underline .gfield_checkbox input[type="checkbox"] + label:before,
					 {{WRAPPER}}.rael-gform-style-underline .ginput_container_consent input[type="checkbox"] + label:before,
					 {{WRAPPER}}.rael-gform-style-underline .gfield_radio input[type="radio"] + label:before,
					 {{WRAPPER}}:not(.rael-gform-check-default-yes).rael-gform-style-underline .gfield_radio .gchoice_label label:before' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'gf_border_active_color',
			array(
				'label'     => __( 'Border Active Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'gform_style'               => 'box',
					'gform_input_border_style!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-gform-style .gfield input:focus,
					 {{WRAPPER}} .rael-gform-style .gfield textarea:focus,
					 {{WRAPPER}} .rael-gform-style .gfield select:focus,
					 {{WRAPPER}} .rael-gform-style .gform_wrapper .chosen-container-active.chosen-with-drop .chosen-single,
					 {{WRAPPER}} .rael-gform-style .gform_wrapper .chosen-container-active.chosen-container-multi .chosen-choices,
					 {{WRAPPER}} .rael-gform-style .gfield_checkbox input[type="checkbox"]:checked + label:before,
					 {{WRAPPER}} .rael-gform-style .ginput_container_consent input[type="checkbox"]:checked + label:before,
					 {{WRAPPER}} .rael-gform-style .gfield_radio input[type="radio"]:checked + label:before,
					 {{WRAPPER}} .rael-gform-style .gfield_radio .gchoice_button.rael-radio-active + .gchoice_label label:before' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'gf_border_active_color_underline',
			array(
				'label'     => __( 'Border Active Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'gform_style' => 'underline',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-gform-style .gfield textarea:focus,
					 {{WRAPPER}} .rael-gform-style .gfield select:focus,
					 {{WRAPPER}} .rael-gform-style .gfield .chosen-single:focus,
					 {{WRAPPER}} .rael-gform-style .gfield input:focus,
					 {{WRAPPER}}.rael-gform-style-underline .gfield_checkbox input[type="checkbox"]:checked + label:before,
					 {{WRAPPER}}.rael-gform-style-underline .ginput_container_consent input[type="checkbox"] + label:before,
					 {{WRAPPER}}.rael-gform-style-underline .gfield_radio input[type="radio"]:checked + label:before,
					 {{WRAPPER}}.rael-gform-style-underline .gfield_radio .gchoice_button.rael-radio-active + .gchoice_label label:before' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'form_border_radius',
			array(
				'label'      => __( 'Rounded Corners', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'default'    => array(
					'top'    => '0',
					'bottom' => '0',
					'left'   => '0',
					'right'  => '0',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=password],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=url],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=date],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=text],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=tel],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=number],
					{{WRAPPER}} .rael-gform-style .gform_wrapper input[type=email],
					{{WRAPPER}} .rael-gform-style .gform_wrapper textarea,
					{{WRAPPER}} .rael-gform-style .gform_wrapper select,
					{{WRAPPER}} .rael-gform-style .gform_wrapper .chosen-container .chosen-drop,
					{{WRAPPER}} .rael-gform-style .gform_wrapper .chosen-choices,
					{{WRAPPER}} .rael-gform-style .gform_wrapper .chosen-single,
					{{WRAPPER}} .rael-gform-style .gfield_checkbox input[type="checkbox"] + label:before,
					{{WRAPPER}} .rael-gform-style .ginput_container_consent input[type="checkbox"] + label:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'gform_radio_check_style',
			array(
				'label' => __( 'Radio & Checkbox', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'gform_radio_check_custom',
			array(
				'label'        => __( 'Override Current Style', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
				'prefix_class' => 'rael-gform-check-',
			)
		);

		$this->add_control(
			'gform_radio_check_default',
			array(
				'label'        => __( 'Default Checkboxes/Radio Buttons', 'responsive-addons-for-elementor' ),
				'description'  => __( 'This option lets you use browser default checkboxes and radio buttons. Enable this if you face any issues with custom checkboxes and radio buttons.', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'prefix_class' => 'rael-gform-check-default-',
				'condition'    => array(
					'gform_radio_check_custom!' => '',
				),
			)
		);

		$this->add_control(
			'gform_radio_check_size',
			array(
				'label'      => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'condition'  => array(
					'gform_radio_check_custom!' => '',
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 16,
				),
				'range'      => array(
					'px' => array(
						'min' => 15,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}.rael-gform-check-yes .rael-gform-check-style .gfield_checkbox input[type="checkbox"] + label:before,
					 {{WRAPPER}}:not(.rael-gform-check-default-yes).rael-gform-check-yes .rael-gform-check-style .gfield_radio .gchoice_label label:before,
					 {{WRAPPER}}.rael-gform-check-default-yes.rael-gform-check-yes .rael-gform-check-style .gfield_checkbox input[type="checkbox"],
					 {{WRAPPER}}.rael-gform-check-default-yes.rael-gform-check-yes .rael-gform-check-style .gfield_radio input[type="radio"],
					 {{WRAPPER}}.rael-gform-check-yes .rael-gform-check-style .ginput_container_consent input[type="checkbox"] + label:before,
					 {{WRAPPER}}.rael-gform-check-yes .rael-gform-check-style .gfield_radio input[type="radio"] + label:before,
					 {{WRAPPER}}.rael-gform-check-default-yes.rael-gform-check-yes .rael-gform-check-style .ginput_container_consent input[type="checkbox"]' => 'width: {{SIZE}}{{UNIT}}!important; height:{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-gform-check-yes .rael-gform-check-style .gfield_checkbox input[type="checkbox"]:checked + label:before,
					 {{WRAPPER}}.rael-gform-check-default-yes.rael-gform-check-yes .rael-gform-check-style .gfield_checkbox input[type="checkbox"]:checked,
					 {{WRAPPER}}.rael-gform-check-yes .rael-gform-check-style .ginput_container_consent input[type="checkbox"] + label:before,
					 {{WRAPPER}}.rael-gform-check-default-yes.rael-gform-check-yes .rael-gform-check-style .ginput_container_consent input[type="checkbox"]'  => 'font-size: calc( {{SIZE}}{{UNIT}} / 1.2 );',
				),
			)
		);

		$this->add_control(
			'gform_radio_check_bgcolor',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'gform_radio_check_custom!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}}.rael-gform-check-yes .rael-gform-style .gfield_checkbox input[type="checkbox"] + label:before,
					 {{WRAPPER}}.rael-gform-check-default-yes.rael-gform-check-yes .rael-gform-style .gfield_checkbox input[type="checkbox"],
					 {{WRAPPER}}.rael-gform-check-default-yes.rael-gform-check-yes .rael-gform-style .gfield_radio input[type="radio"],
					 {{WRAPPER}}.rael-gform-check-yes .rael-gform-style .ginput_container_consent input[type="checkbox"] + label:before,
					 {{WRAPPER}}:not(.rael-gform-check-default-yes).rael-gform-check-yes .rael-gform-style .gfield_radio .gchoice_label label:before,
					 {{WRAPPER}}.rael-gform-check-yes .rael-gform-style .gfield_radio input[type="radio"] + label:before,
					 {{WRAPPER}}.rael-gform-check-default-yes.rael-gform-check-yes .rael-gform-style .ginput_container_consent input[type="checkbox"]' => 'background-color: {{VALUE}};',
				),
				'default'   => '#fafafa',
			)
		);

		$this->add_control(
			'gform_selected_color',
			array(
				'label'     => __( 'Selected Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'condition' => array(
					'gform_radio_check_custom!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}}.rael-gform-check-yes .rael-gform-style .gfield_checkbox input[type="checkbox"]:checked + label:before,
					{{WRAPPER}}.rael-gform-check-default-yes.rael-gform-check-yes .rael-gform-style .gfield_checkbox input[type="checkbox"]:checked:before' => 'color: {{VALUE}};',
					'{{WRAPPER}}.rael-gform-check-yes .rael-gform-style .ginput_container_consent input[type="checkbox"]:checked + label:before,
					{{WRAPPER}}.rael-gform-check-default-yes.rael-gform-check-yes .rael-gform-style .ginput_container_consent input[type="checkbox"]:checked:before' => 'color: {{VALUE}};',
					'{{WRAPPER}}.rael-gform-check-yes .rael-gform-style .gfield_radio input[type="radio"]:checked + label:before,
					{{WRAPPER}}.rael-gform-check-yes .rael-gform-style .gfield_radio .gchoice_button.rael-radio-active + .gchoice_label label:before,
					{{WRAPPER}}.rael-gform-check-default-yes.rael-gform-check-yes .rael-gform-style .gfield_radio input[type="radio"]:checked:before'    => 'background-color: {{VALUE}}; box-shadow:inset 0px 0px 0px 4px {{gf_radio_check_bgcolor.VALUE}};',
				),
			)
		);

		$this->add_control(
			'gform_select_color',
			array(
				'label'     => __( 'Label Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'gform_radio_check_custom!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}}.rael-gform-check-yes .rael-gform-style .gfield_checkbox div label,
					{{WRAPPER}} .rael-gform-style .gfield_radio div label,
					{{WRAPPER}}.rael-gform-check-yes .rael-gform-style .gfield_checkbox li label,
					{{WRAPPER}}.rael-gform-check-yes .rael-gform-style .ginput_container_consent label,
					{{WRAPPER}} .rael-gform-style .gfield_radio li label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'gform_check_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eaeaea',
				'condition' => array(
					'gform_radio_check_custom!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}}.rael-gform-check-yes .rael-gform-style .gfield_checkbox input[type="checkbox"] + label:before,
					{{WRAPPER}}.rael-gform-check-default-yes.rael-gform-check-yes .rael-gform-style .gfield_checkbox input[type="checkbox"],
					{{WRAPPER}}:not(.rael-gform-check-default-yes).rael-gform-check-yes .rael-gform-style .gfield_radio .gchoice_label label:before,
					{{WRAPPER}}.rael-gform-check-yes .rael-gform-style .ginput_container_consent input[type="checkbox"] + label:before,
					{{WRAPPER}}.rael-gform-check-yes .rael-gform-style .gfield_radio input[type="radio"] + label:before,
					{{WRAPPER}}.rael-gform-check-default-yes.rael-gform-check-yes .rael-gform-style .gfield_radio input[type="radio"],
					{{WRAPPER}}.rael-gform-check-default-yes.rael-gform-check-yes .rael-gform-style .ginput_container_consent input[type="checkbox"]' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'gform_check_border_width',
			array(
				'label'      => __( 'Border Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'default'    => array(
					'size' => '1',
					'unit' => 'px',
				),
				'condition'  => array(
					'gform_radio_check_custom!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}}.rael-gform-check-yes .rael-gform-style .gfield_checkbox input[type="checkbox"] + label:before,
					{{WRAPPER}}.rael-gform-check-yes .rael-gform-style .gfield_radio input[type="radio"] + label:before,
					{{WRAPPER}}:not(.rael-gform-check-default-yes).rael-gform-check-yes .rael-gform-style .gfield_radio .gfield_radio .gchoice_label label:before,
					{{WRAPPER}}.rael-gform-check-yes .rael-gform-style .ginput_container_consent input[type="checkbox"] + label:before,
					{{WRAPPER}}.rael-gform-check-default-yes.rael-gform-check-yes .rael-gform-style .gfield_checkbox input[type="checkbox"],
					{{WRAPPER}}.rael-gform-check-default-yes.rael-gform-check-yes .rael-gform-style .gfield_radio input[type="radio"],
					{{WRAPPER}}.rael-gform-check-default-yes.rael-gform-check-yes .rael-gform-style .ginput_container_consent input[type="checkbox"]' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
				),
			)
		);

		$this->add_control(
			'gform_check_border_radius',
			array(
				'label'      => __( 'Checkbox Rounded Corners', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'condition'  => array(
					'gform_radio_check_custom!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}}.rael-gform-check-yes .rael-gform-style .gfield_checkbox input[type="checkbox"] + label:before,
					{{WRAPPER}}.rael-gform-check-yes .rael-gform-style .ginput_container_consent input[type="checkbox"] + label:before,
					{{WRAPPER}}.rael-gform-check-default-yes.rael-gform-check-yes .rael-gform-style .gfield_checkbox input[type="checkbox"],
					{{WRAPPER}}.rael-gform-check-default-yes.rael-gform-check-yes .rael-gform-style .ginput_container_consent input[type="checkbox"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'gform_submit_button',
			array(
				'label' => __( 'Submit Button', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_responsive_control(
			'gform_button_align',
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
				'prefix_class' => 'rael%s-gform-button-',
				'toggle'       => false,
			)
		);

		$this->add_control(
			'gform_btn_size',
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
				'prefix_class' => 'rael-gform-btn-size-',
			)
		);

		$this->add_responsive_control(
			'gform_button_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-gform-style input[type="submit"], {{WRAPPER}} .rael-gform-style input[type="button"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'gform_tab_button_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'gform_button_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-gform-style input[type="button"],
					{{WRAPPER}} .rael-gform-style input[type="submit"],
					{{WRAPPER}} .rael-gform-style .gf_progressbar_percentage span, {{WRAPPER}} .rael-gform-style .percentbar_blue span' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'gform_btn_background_color',
				'label'    => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-gform-style input[type="button"],
				{{WRAPPER}} .rael-gform-style input[type="submit"],
				{{WRAPPER}} .rael-gform-style .gf_progressbar_percentage,
				{{WRAPPER}} .rael-gform-style .gform_wrapper .percentbar_blue',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'gform_btn_border',
				'label'       => __( 'Border', 'responsive-addons-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .rael-gform-style input[type="button"],{{WRAPPER}} .rael-gform-style input[type="submit"]',
			)
		);

		$this->add_responsive_control(
			'gform_btn_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-gform-style input[type="submit"], {{WRAPPER}} .rael-gform-style input[type="button"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'gform_button_box_shadow',
				'selector' => '{{WRAPPER}} .rael-gform-style input[type="submit"], {{WRAPPER}} .rael-gform-style input[type="button"]',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'gform_tab_button_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'gform_btn_hover_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-gform-style input[type="button"]:hover,
					{{WRAPPER}} .rael-gform-style input[type="submit"]:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'gform_button_background_hover_color',
				'label'    => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' =>
					'{{WRAPPER}} .rael-gform-style input[type="button"]:hover,
					{{WRAPPER}} .rael-gform-style input[type="submit"]:hover',
			)
		);

		$this->add_control(
			'gform_button_hover_border_color',
			array(
				'label'     => __( 'Border Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-gform-style input[type="button"]:hover,
					{{WRAPPER}} .rael-gform-style input[type="submit"]:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'gform_messages_section',
			array(
				'label' => __( 'Success / Error Message', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'gform_field_validation',
			array(
				'label'     => __( 'Field Validation', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'gform_message_color',
			array(
				'label'     => __( 'Message Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff0000',
				'selectors' => array(
					'{{WRAPPER}} .rael-gform-style .gform_wrapper .gfield_description.validation_message' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'gf_message_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-gform-style .gform_wrapper .validation_message',
			)
		);
		$this->add_responsive_control(
			'gform_field_validation_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .gfield_description.validation_message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'gform_error_field_background_settings',
			array(
				'label'        => __( 'Advanced Settings', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
				'prefix_class' => 'rael-gform-error-',
			)
		);

		$this->add_control(
			'gform_error_field_bgcolor',
			array(
				'label'     => __( 'Field Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'gform_error_field_background_settings!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}}.rael-gform-error-yes .gform_wrapper .gfield.gfield_error' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'gform_error_border_color',
			array(
				'label'     => __( 'Highlight Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff0000',
				'condition' => array(
					'gform_error_field_background_settings!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}}.rael-gform-style-underline.rael-gform-error-yes .gform_wrapper li.gfield_error input[type="text"]' =>
						'border-width: 0 0 {{gf_border_bottom.SIZE}}px 0 !important; border-style: solid; border-color:{{VALUE}};',
					'{{WRAPPER}}.rael-gform-error-yes .gform_wrapper li.gfield_error input[type="text"]' =>
						'border: {{input_border_size.BOTTOM}}px {{input_border_style.VALUE}} {{VALUE}} !important;',
					'{{WRAPPER}}.rael-gform-error-yes .gform_wrapper li.gfield_error input:not([type="submit"]):not([type="button"]):not([type="image"]),
						 {{WRAPPER}}.rael-gform-error-yes li.gfield_error .ginput_container_consent input[type="checkbox"] + label:before,
						 {{WRAPPER}}.rael-gform-error-yes .gform_wrapper li.gfield.gfield_error.gfield_contains_required.gfield_creditcard_warning,
						 {{WRAPPER}}.rael-gform-error-yes li.gfield_error .gfield_checkbox input[type="checkbox"] + label:before,
						 {{WRAPPER}}.rael-gform-error-yes li.gfield_error .gfield_radio input[type="radio"] + label:before,
						 {{WRAPPER}}.rael-gform-error-yes .gform_wrapper .gfield_error .ginput_container .chosen-single,
						 {{WRAPPER}}.rael-gform-error-yes .gform_wrapper .gfield_error .ginput_container textarea,
						 {{WRAPPER}}.rael-gform-error-yes .gform_wrapper .gfield_error .ginput_container select,
						 {{WRAPPER}}.rael-gform-error-yes .gform_wrapper li.gfield.gfield_error,
						 {{WRAPPER}}:not(.rael-gform-check-default-yes).rael-gform-error-yes li.gfield_error .gfield_radio .gchoice_label label:before' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'gform_validation_message',
			array(
				'label'     => __( 'Form Error Validation', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'gform_valid_message_color',
			array(
				'label'     => __( 'Error Message Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#cccccc',
				'selectors' => array(
					'{{WRAPPER}} .rael-gform-style .gform_wrapper div.gform_validation_errors h2,
					{{WRAPPER}} .rael-gform-style .gform_wrapper div.validation_error' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'gform_valid_bgcolor',
			array(
				'label'     => __( 'Error Message Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-gform-style .gform_wrapper div.gform_validation_errors,
					{{WRAPPER}} .rael-gform-style .gform_wrapper div.validation_error' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'gform_valid_border_color',
			array(
				'label'     => __( 'Error Message Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff0000',
				'selectors' => array(
					'{{WRAPPER}} .rael-gform-style .gform_wrapper div.gform_validation_errors,
					{{WRAPPER}} .rael-gform-style .gform_wrapper div.validation_error' => 'border-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'gform_border_size',
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
					'{{WRAPPER}} .rael-gform-style .gform_wrapper div.gform_validation_errors,
					{{WRAPPER}} .rael-gform-style .gform_wrapper div.validation_error' => 'border-top: {{TOP}}{{UNIT}}; border-right: {{RIGHT}}{{UNIT}}; border-bottom: {{BOTTOM}}{{UNIT}}; border-left: {{LEFT}}{{UNIT}}; border-style: solid;',
				),
			)
		);

		$this->add_control(
			'gform_valid_border_radius',
			array(
				'label'      => __( 'Rounded Corners', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-gform-style .gform_wrapper div.gform_validation_errors,
					{{WRAPPER}} .rael-gform-style .gform_wrapper div.validation_error' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'gform_valid_message_padding',
			array(
				'label'      => __( 'Message Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => '10',
					'bottom' => '10',
					'left'   => '10',
					'right'  => '10',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-gform-style .gform_wrapper div.gform_validation_errors,
					{{WRAPPER}} .rael-gform-style .gform_wrapper div.validation_error' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'gform_error_validation_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-gform-style .gform_wrapper div.validation_error,
				{{WRAPPER}} .rael-gform-style .gform_wrapper div.gform_validation_errors',
			)
		);

		$this->add_control(
			'gform_success_message',
			array(
				'label'     => __( 'Form Success Validation', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'gform_success_message_color',
			array(
				'label'     => __( 'Success Message Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#008000',
				'selectors' => array(
					'{{WRAPPER}} .rael-gform-style .gform_confirmation_message'   => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'gform_success_validation_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-gform-style .gform_confirmation_message',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'gform_spacing_styling',
			array(
				'label' => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'gform_title_margin_bottom',
			array(
				'label'      => __( 'Form Title Bottom Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'condition'  => array(
					'gform_title_desc_option!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-gform-form-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'gform_desc_margin_bottom',
			array(
				'label'      => __( 'Form Description Bottom Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'condition'  => array(
					'gform_title_desc_option!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-gform-style .gform_description,
					{{WRAPPER}} .rael-gform-form-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'gform_fields_margin',
			array(
				'label'      => __( 'Between Two Fields', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-gform-style .gform_wrapper div.gfield,
					{{WRAPPER}} .rael-gform-style .gform_wrapper li.gfield,
					{{WRAPPER}} .rael-gform-style .gform_wrapper fieldset.gfield,
					{{WRAPPER}} .rael-gform-style .gform_wrapper .gf_progressbar_wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'gform_label_margin_bottom',
			array(
				'label'      => __( 'Label Bottom Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-gform-style .gfield_label, {{WRAPPER}} .rael-gform-style .gf_progressbar_title,
					{{WRAPPER}} .rael-gform-style .gsection_title,{{WRAPPER}} .rael-gform-style .gf_page_steps' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'gform_input_margin_top',
			array(
				'label'      => __( 'Input Top Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-gform-style .ginput_container' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'gform_input_margin_bottom',
			array(
				'label'      => __( 'Input Bottom Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-gform-style .ginput_container input' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'gform_typo_styling',
			array(
				'label' => __( 'Typography', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'gform_title_typography',
			array(
				'label'     => __( 'Form Title', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'gform_title_desc_option!' => 'none',
				),
			)
		);
		$this->add_control(
			'gform_title_tag',
			array(
				'label'     => __( 'HTML Tag', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h1'  => __( 'H1', 'responsive-addons-for-elementor' ),
					'h2'  => __( 'H2', 'responsive-addons-for-elementor' ),
					'h3'  => __( 'H3', 'responsive-addons-for-elementor' ),
					'h4'  => __( 'H4', 'responsive-addons-for-elementor' ),
					'h5'  => __( 'H5', 'responsive-addons-for-elementor' ),
					'h6'  => __( 'H6', 'responsive-addons-for-elementor' ),
					'div' => __( 'div', 'responsive-addons-for-elementor' ),
					'p'   => __( 'p', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'gform_title_desc_option!' => 'none',
				),
				'default'   => 'h3',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'gform_title_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector'  => '{{WRAPPER}} .rael-gform-form-title',
				'condition' => array(
					'gform_title_desc_option!' => 'none',
				),

			)
		);
		$this->add_control(
			'gform_title_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'condition' => array(
					'gform_title_desc_option!' => 'none',
				),
				'default'   => '#333333',
				'selectors' => array(
					'{{WRAPPER}} .rael-gform-form-title' => 'color: {{VALUE}};',
				),

			)
		);

		$this->add_control(
			'gform_desc_typo',
			array(
				'label'     => __( 'Form Description', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'gform_title_desc_option!' => 'none',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'gform_desc_typography',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector'  => '{{WRAPPER}} .rael-gform-form-desc, {{WRAPPER}} .rael-gform-style .gform_description',
				'condition' => array(
					'gform_title_desc_option!' => 'none',
				),
			)
		);
		$this->add_control(
			'gform_desc_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'condition' => array(
					'gform_title_desc_option!' => 'none',
				),
				'default'   => '',
				'separator' => 'after',
				'selectors' => array(
					'{{WRAPPER}} .rael-gform-style .gform_description,
					{{WRAPPER}} .rael-gform-form-desc' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'gform_input_typo',
			array(
				'label' => __( 'Form Fields', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'gform_label_typography',
				'label'    => 'Label Typography',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-gform-style .gfield_label,
				{{WRAPPER}} .rael-gform-style .gfield_checkbox div label,
				{{WRAPPER}} .rael-gform-style .gfield_checkbox li label,
				{{WRAPPER}} .rael-gform-style .gsection_title,
				{{WRAPPER}} .rael-gform-style .gf_progressbar_title,
				{{WRAPPER}} .rael-gform-style .gf_page_steps,
				{{WRAPPER}} .rael-gform-style .ginput_container_consent label,
				{{WRAPPER}} .rael-gform-style .ginput_product_price_label,
				{{WRAPPER}} .rael-gform-style .ginput_product_price,
				{{WRAPPER}} .rael-gform-style .gfield_radio li label,
				{{WRAPPER}} .rael-gform-style .gfield_radio div label',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'gform_input_typography',
				'label'    => 'Text Typography',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-gform-style .ginput_container select,
				 {{WRAPPER}} .rael-gform-style .ginput_container textarea,
				 {{WRAPPER}} .rael-gform-style .rael-gform-select-custom,
				 {{WRAPPER}} .rael-gform-style .ginput_container .chosen-single,
				{{WRAPPER}} .rael-gform-style .gform_wrapper .gfield input:not([type="radio"]):not([type="checkbox"]):not([type="submit"]):not([type="button"]):not([type="image"]):not([type="file"])',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'gform_input_desc_typography',
				'label'    => 'Description Typography',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-gform-style .gform_wrapper .gfield .gfield_description,
				{{WRAPPER}} .rael-gform-style .ginput_container input + label,
				{{WRAPPER}} .rael-gform-style .ginput_container select + label,
				{{WRAPPER}} .rael-gform-style .ginput_container_name input + label,
				{{WRAPPER}} .rael-gform-style .ginput_container_address label,
				{{WRAPPER}} .rael-gform-style .ginput_container_total span,
				{{WRAPPER}} .rael-gform-style .ginput_container_creditcard input + span + label,
				{{WRAPPER}} .rael-gform-style .ginput_container .chosen-single + label,
				{{WRAPPER}} .rael-gform-style .gfield_time_hour label,
				{{WRAPPER}} .rael-gform-style .gfield_time_minute label,
				{{WRAPPER}} .rael-gform-style .ginput_shipping_price,
				{{WRAPPER}} .rael-gform-style .gsection_description,
				{{WRAPPER}} .rael-gform-select-custom + label',
			)
		);

		$this->add_control(
			'gform_btn_typography_label',
			array(
				'label'     => __( 'Button Typography', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'gform_btn_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .rael-gform-style input[type="button"],
				{{WRAPPER}} .rael-gform-style input[type=submit]',
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render RAEL GF Forms Styler output on the frontend.
	 *
	 * @since 1.1.0
	 *
	 * @since 1.5.0 Added a condition to check whether the dependency plugin is activated or not.
	 *
	 * @access protected
	 */
	protected function render() {
		if ( ! class_exists( 'GFForms' ) ) {
			return;
		}

		$settings  = $this->get_settings();
		$classname = '';
		if ( 'yes' == $settings['gform_radio_check_custom'] ) {
			$classname = '';
		}
		?>
	<div class="rael-gform-style <?php echo 'rael-gform-check-style'; ?> elementor-clickable">
		<?php
		$form_title  = '';
		$description = '';
		$form_desc   = 'false';
		if ( 'default' == $settings['gform_title_desc_option'] ) {
			if ( class_exists( 'GFAPI' ) ) {
				$form       = array();
				$form       = \GFAPI::get_form( absint( $settings['select_form'] ) );
				$form_title = isset( $form['title'] ) ? $form['title'] : '';
				$form_desc  = 'true';
			}
		} elseif ( 'custom' == $settings['gform_title_desc_option'] ) {
			$form_title  = $this->get_settings_for_display( 'gform_title' );
			$description = $this->get_settings_for_display( 'gform_desc' );
			$form_desc   = 'false';
		} else {
			$form_title  = '';
			$description = '';
			$form_desc   = 'false';
		}
		if ( '' != $form_title ) {
			?>
			<<?php echo esc_attr( Helper::validate_html_tags( $settings['gform_title_tag'] ) ); ?> class="rael-gform-form-title"><?php echo wp_kses_post( $form_title ); ?></<?php echo esc_attr( Helper::validate_html_tags( $settings['gform_title_tag'] ) ); ?>>
			<?php
		}
		if ( '' != $description ) {
			?>
			<p class="rael-gform-form-desc"><?php echo wp_kses_post( $description ); ?></p>
			<?php
		}
		if ( '0' == $settings['select_form'] ) {
			esc_attr_e( 'Please select a Gravity Form', 'responsive-addons-for-elementor' );
		} elseif ( $settings['select_form'] ) {
			$ajax = ( 'yes' == $settings['gform_ajax_option'] ) ? 'true' : 'false';

			$shortcode_extra = '';
			$shortcode_extra = apply_filters( 'rael_gf_shortcode_extra_param', '', absint( $settings['select_form'] ) );

			echo do_shortcode( '[gravityform id=' . absint( $settings['select_form'] ) . ' ajax="' . $ajax . '" title="false" description="' . $form_desc . '" ' . $shortcode_extra . ']' );
		}

		?>

		</div>

		<?php
	}

}
