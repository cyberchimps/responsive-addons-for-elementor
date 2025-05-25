<?php
/**
 * RAEL WPForms Styler Widget
 *
 * Style created wp forms.
 *
 * @since      1.0.0
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Responsive_Addons_For_Elementor\Traits\Missing_Dependency;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}
/**
 * Class WP_Forms_Styler
 *
 * This class is responsible for WP form styler widget.
 */
class Responsive_Addons_For_Elementor_WPF_Styler extends Widget_Base {
	use Missing_Dependency;

	/**
	 * Get widget name.
	 *
	 * Retrieve 'RAEL WP Form Styler' widget name.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael_wpf_styler';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve 'RAEL WP Form Styler' widget title.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'WPForms Styler', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve 'RAEL WP Form Styler' widget icon.
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
		return 'https://cyberchimps.com/docs/widgets/wp-forms-styler';
	}

	/**
	 * Get existing WP Forms.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function get_wp_forms() {

		$field_options = array();

		if ( class_exists( 'WPForms_Pro' ) || class_exists( 'WPForms_Lite' ) ) {

			$args               = array(
				'post_type'      => 'wpforms',
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
					'-1' => __( 'There are no WPForms created.', 'responsive-addons-for-elementor' ),
				);
			}
		}

		return $field_options;
	}

	/**
	 * RAEL WPForms Styler controls.
	 *
	 * @since 1.1.0
	 *
	 * @since 1.5.0 Added a condition to display a warning message in the editor when the dependency plugin is not activated.
	 *
	 * @access protected
	 */
	protected function register_controls() {
		if ( ! class_exists( 'WPForms_Pro' ) && ! class_exists( 'WPForms_Lite' ) ) {
			$this->register_content_tab_missing_dep_warning_controls( 'WP Forms', 'wp forms' );
			return;
		}

		$this->register_rael_general_controls();
		$this->register_rael_input_controls();
		$this->register_rael_radio_controls();
		$this->register_rael_button_controls();
		$this->register_rael_error_controls();
		$this->register_rael_spacing_controls();
		$this->register_rael_typography_controls();
	}
	/**
	 * Register RAEL WPForms Styler General Controls.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function register_rael_general_controls() {
		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'General Settings', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'select_form',
			array(
				'label'   => __( 'Select Form', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_wp_forms(),
				'default' => '0',
			)
		);

		$this->add_control(
			'form_title_desc_option',
			array(
				'label'       => __( 'Title & Description', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'default'     => 'default',
				'options'     => array(
					'default' => __( 'From WPForms', 'responsive-addons-for-elementor' ),
					'custom'  => __( 'Custom', 'responsive-addons-for-elementor' ),
					'none'    => __( 'None', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'form_title',
			array(
				'label'     => __( 'Form Title', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'form_title_desc_option' => 'custom',
				),
				'dynamic'   => array(
					'active' => true,
				),

			)
		);

		$this->add_control(
			'form_desc',
			array(
				'label'     => __( 'Form Description', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXTAREA,
				'condition' => array(
					'form_title_desc_option' => 'custom',
				),
				'dynamic'   => array(
					'active' => true,
				),
			)
		);

		$this->add_responsive_control(
			'form_title_desc_align',
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
					'form_title_desc_option!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-title, {{WRAPPER}} .rael-wpf-style .wpforms-description' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register RAEL WPForms Styler Input Controls.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function register_rael_input_controls() {
		$this->start_controls_section(
			'section_input_fields',
			array(
				'label' => __( 'Form Fields', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'wpf_style',
			array(
				'label'        => __( 'Field Style', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'box',
				'options'      => array(
					'box'       => __( 'Box', 'responsive-addons-for-elementor' ),
					'underline' => __( 'Underline', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'rael-wpf-style-',
			)
		);

		$this->add_control(
			'input_size',
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
				'prefix_class' => 'rael-wpf-input-size-',
			)
		);

		$this->add_responsive_control(
			'rael_input_container_width',
			array(
				'label'      => __( 'Input Container Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1200,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-container' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'rael_input_height',
			array(
				'label'      => __( 'Input Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 80,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field select' => 'height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'rael_textarael_height',
			array(
				'label'      => __( 'Textarea Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 400,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field textarea' => 'height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'rael_dropdown_minwidth',
			array(
				'label'      => __( 'Dropdown Min Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'default'    => array(
					'px' => 250,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-select.wpforms-field-select-style-modern .choices__list--dropdown' => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'wpf_input_padding',
			array(
				'label'      => __( 'Field Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input:not([type=submit]):not([type=image]):not([type=button]):not([type=file]):not([type=radio]):not([type=checkbox]),
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field textarea,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-description.wpforms-disclaimer-description,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field select,
						{{WRAPPER}} .rael-wpf-style .wpforms-container-full .wpforms-form ul.wpforms-image-choices-modern label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input[type="checkbox"] + label:before,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input[type="radio"] + label:before' => 'height: {{BOTTOM}}{{UNIT}}; width: {{BOTTOM}}{{UNIT}};',
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input[type="checkbox"]:checked + label:before' => 'font-size: calc( {{BOTTOM}}{{UNIT}} / 1.2 );',
				),
			)
		);

		$this->add_control(
			'wpf_input_bgcolor',
			array(
				'label'     => __( 'Field Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fafafa',
				'selectors' => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input:not([type=submit]):not([type=image]):not([type=button]):not([type=file]):not([type=radio]):not([type=checkbox]),
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field select,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-description.wpforms-disclaimer-description,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field textarea,
						{{WRAPPER}} .rael-wpf-style .wpforms-container-full .wpforms-form ul.wpforms-image-choices-modern label,
						{{WRAPPER}} .rael-wpf-container select option' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input[type="checkbox"] + label:before,
						{{WRAPPER}} .rael-wpf-style input[type="radio"] + label:before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-wpf-style input[type="radio"]:checked + label:before' => 'background-color: #7a7a7a;',
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-select.wpforms-field-select-style-modern .choices__inner,
					{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-select.wpforms-field-select-style-modern .choices__list,
					{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-select.wpforms-field-select-style-modern .choices__list--dropdown .choices__item--selectable.is-highlighted' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input[type="radio"]:checked + label:before' => 'box-shadow:inset 0px 0px 0px 4px {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'wpf_label_color',
			array(
				'label'     => __( 'Label Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-label,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-divider,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-radio li label,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-page-indicator-steps,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-divider h3,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-single-item-price,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-payment-multiple li label,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-checkbox li label,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-payment-total,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-page-indicator-page-title,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-captcha .wpforms-field-label,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-likert_scale .wpforms-field-label,
						{{WRAPPER}} .rael-wpf-style .wpforms-field-file-upload input[type=file]' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'wpf_input_color',
			array(
				'label'     => __( 'Input Text / Placeholder Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input:not([type=submit]):not([type=image]):not([type=button]):not([type=file]):not([type=radio]):not([type=checkbox]),
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input::placeholder,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field textarea,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field textarea::placeholder,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field select,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input[type="checkbox"]:checked + label:before,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-description.wpforms-disclaimer-description,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-likert_scale tbody tr th,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-select .choices' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field.wpforms-field-radio input[type="radio"]:checked + label:before, {{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field.wpforms-field-payment-multiple input[type="radio"]:checked + label:before' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_input_desc_color',
			array(
				'label'     => __( 'Sublabel / Description Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-description,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-sublabel,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-html,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-likert_scale thead tr th' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_required_color',
			array(
				'label'     => __( 'Required Asterisk Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-required-label' => 'color: {{VALUE}};',
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
					'wpf_style' => 'box',
				),
				'selectors'   => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input:not([type=submit]):not([type=image]):not([type=button]):not([type=file]):not([type=radio]):not([type=checkbox]),
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field textarea,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field select' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input[type="checkbox"] + label:before,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input[type="radio"] + label:before,
						{{WRAPPER}} .rael-wpf-style .wpforms-container-full .wpforms-form ul.wpforms-image-choices-modern label,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-description.wpforms-disclaimer-description,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-select .choices__inner,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-select .choices__list--dropdown' => 'border-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'input_border_size',
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
					'wpf_style'           => 'box',
					'input_border_style!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input:not([type=submit]):not([type=image]):not([type=button]):not([type=file]):not([type=radio]):not([type=checkbox]),
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field textarea,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field select,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-description.wpforms-disclaimer-description' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input[type="checkbox"] + label:before,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input[type="radio"] + label:before,
						{{WRAPPER}} .rael-wpf-style .wpforms-form ul.wpforms-image-choices-modern label,
						{{WRAPPER}} .rael-wpf-style .wpforms-form ul.wpforms-image-choices-modern label:hover,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-description.wpforms-disclaimer-description,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-select .choices__inner' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-select .choices__list--dropdown' => '1px {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'input_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'wpf_style'           => 'box',
					'input_border_style!' => 'none',
				),
				'default'   => '#eaeaea',
				'selectors' => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input:not([type=submit]):not([type=image]):not([type=button]):not([type=file]):not([type=radio]):not([type=checkbox]),
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field textarea,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field select,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-description.wpforms-disclaimer-description' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input[type="checkbox"] + label:before,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input[type="radio"] + label:before,
						{{WRAPPER}} .rael-wpf-style .wpforms-form ul.wpforms-image-choices-modern label,
						{{WRAPPER}} .rael-wpf-style .wpforms-form ul.wpforms-image-choices-modern label:hover,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-page-indicator.circles,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-select .choices__inner' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'wpf_border_bottom',
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
					'wpf_style' => 'underline',
				),
				'selectors'  => array(
					'{{WRAPPER}}.rael-wpf-style-underline .wpforms-form .wpforms-field input:not([type=submit]):not([type=image]):not([type=button]):not([type=file]):not([type=radio]):not([type=checkbox]),
						{{WRAPPER}}.rael-wpf-style-underline .wpforms-form .wpforms-field select,
						{{WRAPPER}}.rael-wpf-style-underline .wpforms-form .wpforms-field textarea,
						{{WRAPPER}}.rael-wpf-style-underline .wpforms-form .wpforms-field-description.wpforms-disclaimer-description' => 'border-width: 0 0 {{SIZE}}{{UNIT}} 0; border-style: solid;',
					'{{WRAPPER}}.rael-wpf-style-underline .wpforms-form .wpforms-field input[type="checkbox"] + label:before,
						{{WRAPPER}}.rael-wpf-style-underline .wpforms-form .wpforms-field input[type="radio"] + label:before,
						{{WRAPPER}}.rael-wpf-style-underline .wpforms-form ul.wpforms-image-choices-modern label,
						{{WRAPPER}}.rael-wpf-style-underline .wpforms-form ul.wpforms-image-choices-modern label:hover' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid; box-sizing: content-box;',
				),
			)
		);

		$this->add_control(
			'wpf_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'wpf_style' => 'underline',
				),
				'default'   => '#c4c4c4',
				'selectors' => array(
					'{{WRAPPER}}.rael-wpf-style-underline .wpforms-form .wpforms-field input:not([type=submit]):not([type=image]):not([type=button]):not([type=file]):not([type=radio]):not([type=checkbox]),
						{{WRAPPER}}.rael-wpf-style-underline .wpforms-form .wpforms-field select,
						{{WRAPPER}}.rael-wpf-style-underline .wpforms-form .wpforms-field textarea' => 'border-color: {{VALUE}};',
					'{{WRAPPER}}.rael-wpf-style-underline .wpforms-form .wpforms-field input[type="checkbox"] + label:before,
						{{WRAPPER}}.rael-wpf-style-underline .wpforms-form .wpforms-field input[type="radio"] + label:before,
						{{WRAPPER}}.rael-wpf-style-underline .wpforms-form .wpforms-field-description.wpforms-disclaimer-description,
						{{WRAPPER}}.rael-wpf-style-underline .wpforms-container-full .wpforms-form ul.wpforms-image-choices-modern label,
						{{WRAPPER}}.rael-wpf-style-underline .wpforms-form ul.wpforms-image-choices-modern label:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'wpf_ipborder_active',
			array(
				'label'     => __( 'Border Active Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input:not([type=submit]):not([type=image]):not([type=button]):not([type=file]):not([type=radio]):not([type=checkbox]):focus,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field select:focus,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field textarea:focus' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input[type="checkbox"]:checked + label:before,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input[type="radio"]:checked + label:before,
						{{WRAPPER}} .rael-wpf-style .wpforms-container-full .wpforms-form ul.wpforms-image-choices-modern .wpforms-selected label
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-select .choices.is-open .choices__list--dropdown' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'wpf_input_radius',
			array(
				'label'      => __( 'Rounded Corners', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input:not([type=submit]):not([type=image]):not([type=button]):not([type=file]):not([type=radio]):not([type=checkbox]),
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field textarea,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field select,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input[type="checkbox"] + label:before,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-description.wpforms-disclaimer-description,
						{{WRAPPER}}.rael-wpf-style-underline .wpforms-container-full .wpforms-form ul.wpforms-image-choices-modern label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'fields_box_shadow',
				'label'     => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'wpf_style!' => 'underline',
				),
				'selector'  => '{{WRAPPER}} .wpforms-form .wpforms-field input:not([type=submit]):not([type=image]):not([type=button]):not([type=file]):not([type=radio]):not([type=checkbox]),
									{{WRAPPER}} .wpforms-form .wpforms-field select,
									{{WRAPPER}} .wpforms-form .wpforms-field textarea,
									{{WRAPPER}} .wpforms-form .wpforms-field-description.wpforms-disclaimer-description,
									{{WRAPPER}} .wpforms-form ul.wpforms-image-choices-modern label',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register RAEL WPForms Styler Radio Input & Checkbox Input Controls.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function register_rael_radio_controls() {
		$this->start_controls_section(
			'wpf_radio_check_style',
			array(
				'label' => __( 'Radio & Checkbox', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_control(
			'wpf_radio_check_custom',
			array(
				'label'        => __( 'Override Current Style', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
				'prefix_class' => 'rael-wpf-check-',
			)
		);

		$this->add_control(
			'wpf_radio_check_size',
			array(
				'label'      => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'condition'  => array(
					'wpf_radio_check_custom!' => '',
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
					'{{WRAPPER}}.rael-wpf-check-yes .rael-wpf-style .wpforms-form .wpforms-field input[type="checkbox"] + label:before, {{WRAPPER}}.rael-wpf-check-yes .rael-wpf-style .wpforms-form .wpforms-field input[type="radio"] + label:before' => 'width: {{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.rael-wpf-check-yes .rael-wpf-style .wpforms-form .wpforms-field input[type="checkbox"]:checked + label:before'  => 'font-size: calc( {{SIZE}}{{UNIT}} / 1.2 );',
				),
			)
		);

		$this->add_control(
			'wpf_radio_check_bgcolor',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'wpf_radio_check_custom!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}}.rael-wpf-check-yes .rael-wpf-style .wpforms-form .wpforms-field input[type="checkbox"] + label:before, {{WRAPPER}}.rael-wpf-check-yes .rael-wpf-style .wpforms-form .wpforms-field input[type="radio"] + label:before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.rael-wpf-check-yes .rael-wpf-style .wpforms-form .wpforms-field input[type="radio"]:checked + label:before'    => 'box-shadow:inset 0px 0px 0px 4px {{VALUE}};',
				),
				'default'   => '#fafafa',
			)
		);

		$this->add_control(
			'wpf_selected_color',
			array(
				'label'     => __( 'Selected Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'condition' => array(
					'wpf_radio_check_custom!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}}.rael-wpf-check-yes .rael-wpf-style .wpforms-form .wpforms-field input[type="checkbox"]:checked + label:before' => 'color: {{VALUE}};',
					'{{WRAPPER}}.rael-wpf-check-yes .rael-wpf-style .wpforms-form .wpforms-field input[type="radio"]:checked + label:before' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'wpf_select_color',
			array(
				'label'     => __( 'Label Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'wpf_radio_check_custom!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}}.rael-wpf-check-yes .rael-wpf-style .wpforms-form .wpforms-field-checkbox li label,
						{{WRAPPER}}.rael-wpf-check-yes .rael-wpf-style .wpforms-form .wpforms-field-radio li label,
						{{WRAPPER}}.rael-wpf-check-yes .rael-wpf-style .wpforms-form .wpforms-field-payment-multiple li label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'wpf_check_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eaeaea',
				'condition' => array(
					'wpf_radio_check_custom!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}}.rael-wpf-check-yes .rael-wpf-style .wpforms-form .wpforms-field input[type="checkbox"] + label:before,{{WRAPPER}}.rael-wpf-check-yes .rael-wpf-style .wpforms-form .wpforms-field input[type="radio"] + label:before' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'wpf_check_border_width',
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
					'wpf_radio_check_custom!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}}.rael-wpf-check-yes .rael-wpf-style .wpforms-form .wpforms-field input[type="checkbox"] + label:before,{{WRAPPER}}.rael-wpf-check-yes .rael-wpf-style .wpforms-form .wpforms-field input[type="radio"] + label:before' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
				),
			)
		);

		$this->add_control(
			'wpf_check_border_radius',
			array(
				'label'      => __( 'Checkbox Rounded Corners', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'condition'  => array(
					'wpf_radio_check_custom!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}}.rael-wpf-check-yes .rael-wpf-style .wpforms-form .wpforms-field input[type="checkbox"] + label:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	}

	/**
	 * Register RAEL WPForms Styler Button Controls.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function register_rael_button_controls() {

		$this->start_controls_section(
			'wpf_submit_button',
			array(
				'label' => __( 'Submit Button', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_responsive_control(
			'wpf_button_align',
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
				'prefix_class' => 'rael%s-wpf-button-',
				'toggle'       => false,
			)
		);

		$this->add_control(
			'btn_size',
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
				'prefix_class' => 'rael-wpf-btn-size-',
			)
		);

		$this->add_responsive_control(
			'rael_button_container_width',
			array(
				'label'      => __( 'Button Container Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1200,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-submit-container' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'wpf_button_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form button[type=submit],
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-page-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
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
					'{{WRAPPER}} .rael-wpf-style .wpforms-form button[type=submit], {{WRAPPER}} .rael-wpf-style .wpforms-form button[type=submit]:hover, {{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-page-button, {{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-page-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form button[type=submit], {{WRAPPER}} .rael-wpf-style .wpforms-form button[type=submit]:hover, {{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-page-button, {{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-page-button:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'btn_border',
				'label'       => __( 'Border', 'responsive-addons-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .rael-wpf-style .wpforms-form button[type=submit], {{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-page-button',
			)
		);

		$this->add_responsive_control(
			'btn_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form button[type=submit], {{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-page-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .rael-wpf-style .wpforms-form button[type=submit], {{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-page-button',
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
			'btn_hover_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form button[type=submit]:hover, {{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-page-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background_hover_color',
				'label'    => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-wpf-style .wpforms-form button[type=submit]:hover, {{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-page-button:hover',
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => __( 'Border Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form button[type=submit]:hover, {{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-page-button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register RAEL WPForms Styler Error Controls.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function register_rael_error_controls() {

		$this->start_controls_section(
			'wpf_error_field',
			array(
				'label' => __( 'Success / Error Message', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'wpf_validation_message',
			array(
				'label' => __( 'Field Validation', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'wpf_highlight_style',
			array(
				'label'        => __( 'Message Style', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'default',
				'options'      => array(
					'default'      => __( 'Default', 'responsive-addons-for-elementor' ),
					'bottom_right' => __( 'Custom', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'rael-wpf-highlight-style-',
			)
		);

		$this->add_control(
			'wpf_message_color',
			array(
				'label'     => __( 'Message Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff0000',
				'condition' => array(
					'wpf_highlight_style' => 'default',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-wpf-style label.wpforms-error' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'wpf_message_highlight_color',
			array(
				'label'     => __( 'Message Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'condition' => array(
					'wpf_highlight_style' => 'bottom_right',
				),
				'selectors' => array(
					'{{WRAPPER}}.rael-wpf-highlight-style-bottom_right label.wpforms-error' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'wpf_message_bgcolor',
			array(
				'label'     => __( 'Message Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 0, 0, 0.6)',
				'condition' => array(
					'wpf_highlight_style' => 'bottom_right',
				),
				'selectors' => array(
					'{{WRAPPER}}.rael-wpf-highlight-style-bottom_right label.wpforms-error' => 'background-color: {{VALUE}}; padding: 0.1em 0.8em;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'wpf_message_typo',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-wpf-style label.wpforms-error',
			)
		);

		$this->add_control(
			'wpf_success_validation_message',
			array(
				'label'     => __( 'Form Success Message', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'wpf_success_message_color',
			array(
				'label'     => __( 'Message Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-confirmation-container-full,
						{{WRAPPER}} .rael-wpf-style .wpforms-confirmation-container' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'wpf_success_message_bgcolor',
			array(
				'label'     => __( 'Message Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-confirmation-container-full,
						{{WRAPPER}} .rael-wpf-style .wpforms-confirmation-container' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'wpf_success_border_color',
			array(
				'label'     => __( 'Message Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-confirmation-container-full,
						{{WRAPPER}} .rael-wpf-style .wpforms-confirmation-container' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'wpf_validation_typo',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-wpf-style .wpforms-confirmation-container-full,
					{{WRAPPER}} .rael-wpf-style .wpforms-confirmation-container',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register RAEL WPForms Styler spacing Controls.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function register_rael_spacing_controls() {

		$this->start_controls_section(
			'form_spacing',
			array(
				'label' => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_desc_spacing_heading',
			array(
				'label'     => __( 'Title & Description', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'form_title_desc_option!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'form_title_margin_bottom',
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
					'form_title_desc_option!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'form_desc_margin_bottom',
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
				'selectors'  => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'form_title_desc_option!' => 'none',
				),
				'separator'  => 'after',
			)
		);

		$this->add_control(
			'input_spacing_heading',
			array(
				'label' => __( 'Form Fields', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'form_fields_margin',
			array(
				'label'      => __( 'Space Between Two Fields', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field,
						{{WRAPPER}} .rael-wpf-style .wpforms-field-address .wpforms-field-row' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-wpf-style .wpforms-container.inline-fields .wpforms-field-container .wpforms-field' => 'padding-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'form_label_margin_bottom',
			array(
				'label'      => __( 'Label Bottom Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-label,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-page-indicator-steps,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-divider h3' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-wpf-style div.wpforms-container-full .wpforms-form .wpforms-page-indicator.progress .wpforms-page-indicator-page-progress-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'form_desc_margin_top',
			array(
				'label'      => __( 'Sublabel / Description Top Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-description,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-sublabel' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'check_radio_items_spacing',
			array(
				'label'      => __( 'Radio & Checkbox Items Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-radio li:not(:last-child),
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-checkbox ul li:not(:last-child),
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-payment-multiple li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}} !important; margin-right: 0{{UNIT}};',
					'{{WRAPPER}} .rael-wpf-style .wpforms-field-radio.wpforms-list-inline ul li:not(:last-child),
						{{WRAPPER}} .rael-wpf-style .wpforms-field-checkbox.wpforms-list-inline ul li:not(:last-child),
						{{WRAPPER}} .rael-wpf-style .wpforms-field-payment-multiple.wpforms-list-inline li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}} !important; margin-bottom: 0{{UNIT}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'submit_spacing',
			array(
				'label'      => __( 'Submit Button Top Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 150,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-form button[type=submit],
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-pagebreak' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.elementor-widget-rael-wpf-styler .rael-wpf-style .wpforms-container.inline-fields button[type=submit]' => 'margin-top: 0px;',
					'(mobile){{WRAPPER}}.elementor-widget-rael-wpf-styler .rael-wpf-style .wpforms-container.inline-fields button[type=submit]' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register RAEL WPForms Styler Typography Controls.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function register_rael_typography_controls() {

		$this->start_controls_section(
			'form_typo',
			array(
				'label' => __( 'Typography', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'form_title_typo',
			array(
				'label'     => __( 'Form Title', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'form_title_desc_option!' => 'none',
				),
			)
		);

		$this->add_control(
			'form_title_tag',
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
					'form_title_desc_option' => 'custom',
				),
				'default'   => 'h3',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'title_typography',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector'  => '{{WRAPPER}} .rael-wpf-style .wpforms-title',
				'condition' => array(
					'form_title_desc_option!' => 'none',
				),

			)
		);
		$this->add_control(
			'form_title_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'condition' => array(
					'form_title_desc_option!' => 'none',
				),
				'default'   => '#6EC1E4',
				'selectors' => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-title' => 'color: {{VALUE}};',
				),

			)
		);

		$this->add_control(
			'form_desc_typo',
			array(
				'label'     => __( 'Form Description', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'form_title_desc_option!' => 'none',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'desc_typography',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector'  => '{{WRAPPER}} .rael-wpf-style .wpforms-description',
				'condition' => array(
					'form_title_desc_option!' => 'none',
				),
			)
		);

		$this->add_control(
			'form_desc_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'condition' => array(
					'form_title_desc_option!' => 'none',
				),
				'default'   => '',
				'separator' => 'after',
				'selectors' => array(
					'{{WRAPPER}} .rael-wpf-style .wpforms-description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_input_typo',
			array(
				'label' => __( 'Form Fields', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'form_label_typography',
				'label'    => 'Label Typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-label,
									{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-radio li label,
									{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-checkbox li label,
									{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-page-indicator-steps,
									{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-payment-multiple li label,
									{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-single-item-price,
									{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-payment-total,
									{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-divider,
									{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-html,
									{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-divider h3,
									{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-page-indicator-steps,
									{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-page-indicator-page-title,
									{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-captcha .wpforms-field-label,
									{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-likert_scale .wpforms-field-label,
									{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-file-upload input[type=file]',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'input_typography',
				'label'    => 'Input Text Typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input:not([type=submit]):not([type=image]):not([type=button]):not([type=file]):not([type=radio]):not([type=checkbox]),
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field input::placeholder,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field textarea,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field textarea::placeholder,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field select,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-description.wpforms-disclaimer-description,
						{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-likert_scale tbody tr th',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'input_desc_typography',
				'label'    => 'Sublabel / Description Typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-description,
									{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-sublabel,
									{{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-field-likert_scale thead tr th',
			)
		);

		$this->add_control(
			'btn_typography_label',
			array(
				'label'     => __( 'Button Typography', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'btn_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .rael-wpf-style .wpforms-form button[type=submit], {{WRAPPER}} .rael-wpf-style .wpforms-form .wpforms-page-button',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render RAEL WPForms Styler output on the frontend.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function render() {

		if ( ( ! class_exists( 'WPForms_Pro' ) ) && ( ! class_exists( 'WPForms_Lite' ) ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		$forms = $this->get_wp_forms();

		$html = '';

		if ( ! empty( $forms ) && ! isset( $forms[-1] ) ) {
			if ( '0' !== $settings['select_form'] ) {
				?>
				<div class="rael-wpf-container">
					<div class="rael-wpf rael-wpf-style elementor-clickable">
						<?php
						if ( $settings['select_form'] ) {

							$title       = false;
							$description = false;

							if ( 'default' === $settings['form_title_desc_option'] ) {
								$title       = true;
								$description = true;
							} elseif ( 'custom' === $settings['form_title_desc_option'] ) {

								if ( '' !== $settings['form_title'] ) {
									?>
						<<?php echo esc_attr( Helper::validate_html_tags( $settings['form_title_tag'] ) ); ?> class="wpforms-title">
									<?php echo wp_kses_post( $settings['form_title'] ); ?></<?php echo esc_attr( Helper::validate_html_tags( $settings['form_title_tag'] ) ); ?>>
									<?php
								}

								if ( '' !== $settings['form_desc'] ) {
									?>
						<div class="wpforms-description"><?php echo wp_kses_post( $settings['form_desc'] ); ?></div>
									<?php
								}
							}

							echo do_shortcode( '[wpforms id=' . $settings['select_form'] . ' title="' . $title . '" description="' . $description . '"]' );
						}
						?>
				</div>
				</div>
				<?php
			} else {
				$html = __( 'Please select a WPForm.', 'responsive-addons-for-elementor' );
			}
		} else {
			$html = __( 'There are no WPForms created.', 'responsive-addons-for-elementor' );
		}
		echo wp_kses_post( $html );
	}
}
