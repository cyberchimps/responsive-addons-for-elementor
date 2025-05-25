<?php
/**
 * Mail chimp styler
 *
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;


if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}


/**
 * Elementor 'MC styler' widget.
 *
 * Elementor widget that styles Mail chimp.
 */
class Responsive_Addons_For_Elementor_MC_Styler extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve 'Mailchimp Styler' widget name.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-mailchimp-styler';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve 'Mailchimp Styler' widget title.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Mailchimp Styler', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve 'Mailchimp Styler' widget icon.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-mailchimp rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve 'Mailchimp Styler' widget icon.
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
	 * Retrieve Mailchimp lists.
	 *
	 * @since 1.1.0
	 * @access public
	 */
	protected function get_mailchimp_lists() {
		$lists   = array();
		$api_key = get_option( 'rael_mailchimp_settings_api_key' );

		if ( empty( $api_key ) ) {
			return $lists;
		}

		$response = wp_remote_get(
			'https://' . substr( $api_key, strpos( $api_key, '-' ) + 1 ) . '.api.mailchimp.com/3.0/lists/?fields=lists.id,lists.name&count=1000',
			array(
				'headers' => array(
					'Content - Type' => 'application / json',
					'Authorization'  => 'Basic ' . base64_encode( 'user:' . $api_key ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
				),
			)
		);

		if ( ! is_wp_error( $response ) ) {
			$response = json_decode( wp_remote_retrieve_body( $response ) );

			if ( ! empty( $response ) && ! empty( $response->lists ) ) {
				$lists[''] = __( 'Select One', 'responsive-addons-for-elementor' );

				for ( $i = 0; $i < count( $response->lists ); $i++ ) { // phpcs:ignore Squiz.PHP.DisallowSizeFunctionsInLoops.Found, Generic.CodeAnalysis.ForLoopWithTestFunctionCall.NotAllowed
					$lists[ $response->lists[ $i ]->id ] = $response->lists[ $i ]->name;
				}
			}
		}

		return $lists;
	}

	/**
	 * Register 'Mailchimp Styler' widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function register_controls() {

		$url = esc_url(
			add_query_arg(
				'page',
				'rael_getting_started#settings',
				get_admin_url() . 'admin.php'
			)
		);

		$this->start_controls_section(
			'rael-mailchimp-settings',
			array(
				'label' => __( 'Mailchimp Account Settings', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael-mailchimp-lists',
			array(
				'label'       => esc_html__( 'Mailchimp List', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'description' => 'Set your API Key from <strong>RAEL &gt; RAE Settings &gt; 
				Plugin Settings</strong> <a href="' . $url . '">Click Here</a>',
				'options'     => $this->get_mailchimp_lists(),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael-mailchimp-field-settings',
			array(
				'label' => __( 'Field Settings', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael-mailchimp-email-label',
			array(
				'label'       => __( 'Email Label', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => false,
				'default'     => 'Email',
			)
		);

		$this->add_control(
			'rael-mailchimp-fname-show',
			array(
				'label'        => __( 'Enable First Name', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);
		$this->add_control(
			'rael-mailchimp-fname-label_text',
			array(
				'label'       => __( 'First Name Label', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => false,
				'default'     => 'First Name',
				'condition'   => array(
					'rael-mailchimp-fname-show' => 'yes',
				),
			)
		);
		$this->add_control(
			'rael-mailchimp-lname-show',
			array(
				'label'        => __( 'Enable Last Name', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);
		$this->add_control(
			'rael-mailchimp-lname-label_text',
			array(
				'label'       => __( 'Last Name Label', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => false,
				'default'     => 'Last Name',
				'condition'   => array(
					'rael-mailchimp-lname-show' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael-mailchimp-button-settings',
			array(
				'label' => __( 'Button Settings', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael-mailchimp-button-text',
			array(
				'label'       => __( 'Button Text', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => false,
				'default'     => 'Subscribe',
			)
		);

		$this->add_control(
			'rael-mailchimp-loading-text',
			array(
				'label'       => __( 'Loading Text', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => false,
				'default'     => 'Submitting...',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael-mailchimp-message-settings',
			array(
				'label' => __( 'Message Settings', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael-mailchimp-success-text',
			array(
				'label'       => __( 'Success Text', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => true,
				'default'     => __( 'You have subscribed successfully!', 'responsive-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael-mailchimp-style-settings',
			array(
				'label' => __( 'General Style', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael-mailchimp-layout',
			array(
				'label'   => __( 'Layout', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'inline'  => 'Inline',
					'stacked' => 'Stacked',
				),
				'default' => 'stacked',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael-mailchimp-box-bg',
				'label'    => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'none', 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-mailchimp-wrap',
			)
		);

		$this->add_responsive_control(
			'rael-mailchimp-padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-mailchimp-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael-mailchimp-margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-mailchimp-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_mailchimp_border',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-mailchimp-wrap',
			)
		);
		$this->add_responsive_control(
			'rael_mailchimp_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-mailchimp-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_mailchimp_box_shadow',
				'selector' => '{{WRAPPER}} .rael-mailchimp-wrap',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael-mailchimp-form-fields-styles',
			array(
				'label' => __( 'Form Fields Styles', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael-mailchimp-input-background',
			array(
				'label'     => __( 'Input Field Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-mailchimp-input' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael-mailchimp-input-width',
			array(
				'label'      => __( 'Input Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 500,
					),
					'em' => array(
						'min' => 1,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-field-group' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael-mailchimp-input-height',
			array(
				'label'      => __( 'Input Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 500,
					),
					'em' => array(
						'min' => 1,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-mailchimp-input' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael-mailchimp-field-padding',
			array(
				'label'      => __( 'Fields Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-mailchimp-input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael-mailchimp-input-border-radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'separator'  => 'before',
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-mailchimp-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael-mailchimp-input-border',
				'selector' => '{{WRAPPER}} .rael-mailchimp-input',
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael-mailchimp-input-box-shadow',
				'selector' => '{{WRAPPER}} .rael-mailchimp-input',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael-mailchimp-color-typography',
			array(
				'label' => __( 'Color & Typography', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael-mailchimp-label-color',
			array(
				'label'     => esc_html__( 'Label Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-mailchimp-wrap label' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael-mailchimp-field-color',
			array(
				'label'     => esc_html__( 'Field Font Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-mailchimp-input' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael-mailchimp-field-placeholder-color',
			array(
				'label'     => esc_html__( 'Placeholder Font Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-mailchimp-wrap ::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-mailchimp-wrap ::-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-mailchimp-wrap ::-ms-input-placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael-mailchimp-label-heading',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Label Typography', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael-mailchimp-label-typography',
				'selector' => '{{WRAPPER}} .rael-mailchimp-wrap label',
			)
		);
		$this->add_control(
			'rael-mailchimp-heading-input-field',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Input Fields Typography', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael-mailchimp-input-field-typography',
				'selector' => '{{WRAPPER}} .rael-mailchimp-input',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael-mailchimp-subscribe-button-style',
			array(
				'label' => __( 'Subscribe Button Style', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael-mailchimp-button-display',
			array(
				'label'   => __( 'Button Display', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'inline' => 'Inline',
					'block'  => 'Block',
				),
				'default' => 'inline',

			)
		);

		$this->add_responsive_control(
			'rael-mailchimp-button-max-width',
			array(
				'label'      => __( 'Button Max Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 1500,
					),
					'em' => array(
						'min' => 1,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-mailchimp-submit-btn' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael-mailchimp-button-padding',
			array(
				'label'      => __( 'Button Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-mailchimp-subscribe' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael-mailchimp-button-margin',
			array(
				'label'      => __( 'Button Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-mailchimp-subscribe' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael-mailchimp-button-typography',
				'selector' => '{{WRAPPER}} .rael-mailchimp-subscribe',
			)
		);

		$this->start_controls_tabs( 'rael-mailchimp-subscribe-btn-tabs' );

		$this->start_controls_tab(
			'rael-mailchimp-subscribe-btn-normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael-mailchimp-button-text-color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'default'   => '#fff',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-mailchimp-subscribe' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael-mailchimp-button-background-color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'default'   => '#29d8d8',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-mailchimp-subscribe' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael-mailchimp-button-normal-border',
				'label'    => __( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-mailchimp-subscribe',
			)
		);

		$this->add_control(
			'rael-mailchimp-button-normal-border-radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-mailchimp-subscribe' => 'border-radius: {{SIZE}}px;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'rael-mailchimp-subscribe-btn-shadow',
				'selector'  => '{{WRAPPER}} .rael-mailchimp-subscribe',
				'separator' => 'before',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael-mailchimp-subscribe-btn-hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael-mailchimp-button-text-hover-color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'default'   => '#fff',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-mailchimp-subscribe:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael-mailchimp-button-hover-background-color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'default'   => '#27bdbd',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-mailchimp-subscribe:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael-mailchimp-button-hover-border-color',
			array(
				'label'     => esc_html__( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-mailchimp-subscribe:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'rael-mailchimp-subscribe-btn-hover-shadow',
				'selector'  => '{{WRAPPER}} .rael-mailchimp-subscribe:hover',
				'separator' => 'before',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'rael-mailchimp-message-style',
			array(
				'label' => __( 'Message Style', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael-mailchimp-message-background-color',
			array(
				'label'     => __( 'Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-mailchimp-message' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael-mailchimp-message-color',
			array(
				'label'     => __( 'Font Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-mailchimp-message' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael-mailchimp-message-alignment',
			array(
				'label'        => esc_html__( 'Text Alignment', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => true,
				'options'      => array(
					'default' => array(
						'title' => __( 'Default', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-ban',
					),
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
				),
				'default'      => 'default',
				'prefix_class' => 'rael-mailchimp-message-text-',
			)
		);

		$this->add_responsive_control(
			'rael-mailchimp-message-padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-mailchimp-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael-mailchimp-message-margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '% ' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-mailchimp-message' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael-mailchimp-message-border-radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-mailchimp-message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael-mailchimp-message-border',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-mailchimp-message',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael-mailchimp-message-box-shadow',
				'selector' => '{{WRAPPER}} .rael-mailchimp-message',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render Mailchimp Styler widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$api_key  = get_option( 'rael_mailchimp_settings_api_key' );

		if ( 'stacked' === $settings['rael-mailchimp-layout'] ) {
			$layout = 'rael-mailchimp-stacked';
		} elseif ( 'inline' === $settings['rael-mailchimp-layout'] ) {
			$layout = 'rael-mailchimp-inline';
		}
		// Button Display Class.
		if ( 'block' === $settings['rael-mailchimp-button-display'] ) {
			$subscribe_btn_display = 'rael-mailchimp-btn-block';
		} elseif ( 'inline' === $settings['rael-mailchimp-button-display'] ) {
			$subscribe_btn_display = 'rael-mailchimp-btn-inline';
		}

		$this->add_render_attribute( 'rael-mailchimp-main-wrapper', 'class', 'rael-mailchimp-wrap' );
		$this->add_render_attribute( 'rael-mailchimp-main-wrapper', 'class', esc_attr( $layout ) );
		$this->add_render_attribute( 'rael-mailchimp-main-wrapper', 'data-mailchimp-id', esc_attr( $this->get_id() ) );
		$this->add_render_attribute( 'rael-mailchimp-main-wrapper', 'data-api-key', esc_attr( $api_key ) );
		$this->add_render_attribute( 'rael-mailchimp-main-wrapper', 'data-list-id', $settings['rael-mailchimp-lists'] );
		$this->add_render_attribute( 'rael-mailchimp-main-wrapper', 'data-button-text', $settings['rael-mailchimp-button-text'] );
		$this->add_render_attribute( 'rael-mailchimp-main-wrapper', 'data-success-text', $settings['rael-mailchimp-success-text'] );
		$this->add_render_attribute( 'rael-mailchimp-main-wrapper', 'data-loading-text', $settings['rael-mailchimp-loading-text'] );

		?>
		<?php if ( ! empty( $api_key ) ) : ?>
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael-mailchimp-main-wrapper' ) ); ?> >
				<form id="rael-mailchimp-form-<?php echo esc_attr( $this->get_id() ); ?>" method="POST">
					<div class="rael-form-fields-wrapper rael-mailchimp-fields-wrapper <?php echo esc_attr( $subscribe_btn_display ); ?>">
						<div class="rael-field-group rael-mailchimp-email">
							<label for="<?php echo esc_attr( $settings['rael-mailchimp-email-label'], 'responsive-addons-for-elementor' ); ?>"><?php echo esc_html__( $settings['rael-mailchimp-email-label'], 'responsive-addons-for-elementor' ); // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText, WordPress.CodeAnalysis.EscapedNotTranslated.Found ?></label>
							<input type="email" name="rael_mailchimp_email" class="rael-mailchimp-input" placeholder="Email" required="required">
						</div>
						<?php if ( 'yes' === $settings['rael-mailchimp-fname-show'] ) : ?>
							<div class="rael-field-group rael-mailchimp-fname">
								<label for="<?php echo esc_attr( $settings['rael-mailchimp-fname-label_text'], 'responsive-addons-for-elementor' ); ?>"><?php echo esc_html__( $settings['rael-mailchimp-fname-label_text'], 'responsive-addons-for-elementor' ); // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText, WordPress.CodeAnalysis.EscapedNotTranslated.Found ?></label>
								<input type="text" name="rael_mailchimp_firstname" class="rael-mailchimp-input" placeholder="First Name">
							</div>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['rael-mailchimp-lname-show'] ) : ?>
							<div class="rael-field-group rael-mailchimp-lname">
								<label for="<?php echo esc_attr( $settings['rael-mailchimp-lname-label_text'], 'responsive-addons-for-elementor' ); ?>"><?php echo esc_html__( $settings['rael-mailchimp-lname-label_text'], 'responsive-addons-for-elementor' ); // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText, WordPress.CodeAnalysis.EscapedNotTranslated.Found ?></label>
								<input type="text" name="rael_mailchimp_lastname" class="rael-mailchimp-input" placeholder="Last Name">
							</div>
						<?php endif; ?>
						<div class="rael-field-group rael-mailchimp-submit-btn">
							<button type="submit" id="rael-subscribe-<?php echo esc_attr( $this->get_id() ); ?>" class="rael-button rael-mailchimp-subscribe">
								<div class="rael-btn-loader button__loader"></div>
								<span><?php echo esc_html__( $settings['rael-mailchimp-button-text'], 'responsive-addons-for-elementor' ); // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText ?></span>
							</button>
						</div>
					</div>
					<div class="rael-mailchimp-message"></div>
				</form>
			</div>
		<?php else : ?>
			<p class="rael-mailchimp-error">Please insert your api key</p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/mailchimp-styler';
	}
}
