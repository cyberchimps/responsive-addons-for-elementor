<?php
/**
 * RAEL Multi Button widget
 *
 * @since 1.2.1
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Control_Icon;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * RAEL Multi Button widget class
 *
 * @since 1.2.1
 */
class Responsive_Addons_For_Elementor_Multi_Button extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.2.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-multi-button';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.2.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Multi Button', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve multi button widget icon.
	 *
	 * @since 1.2.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-dual-button rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the multi button widget belongs to.
	 *
	 * @since 1.2.1
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 1.2.1
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'button', 'btn', 'multi', 'switch', 'link', 'dual', 'fancy' );
	}

	/**
	 * Get Custom help URL
	 *
	 * @since 1.2.1
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/multibutton';
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.2.1
	 * @access protected
	 */
	protected function register_controls() {
		// Content Tab.
		do_action( 'rael_start_register_controls', $this );
		$this->register_buttons_content_controls();

		// Style Tab.
		$this->register_common_style_controls();
		$this->register_button_style_controls( 'primary' );
		$this->register_connector_style_controls();
		$this->register_button_style_controls( 'secondary' );
	}

	/**
	 * Registers Multi Buttons controls under Content Tab.
	 *
	 * @since 1.2.1
	 * @access public
	 */
	public function register_buttons_content_controls() {
		$this->start_controls_section(
			'rael_multi_button_controls',
			array(
				'label' => __( 'Multi Buttons', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->start_controls_tabs( 'rael_multi_buttons_tabs' );

		// Start Primary Button Tab.
		$this->start_controls_tab(
			'rael_primary_button_content_tab',
			array(
				'label' => __( 'PRIMARY', 'responsive-addons-for-elementor' ),
			)
		);

		$this->register_button_content_controls( 'primary' );

		$this->end_controls_tab();
		// End Primary Button Tab.

		// Start Connector Tab.
		$this->start_controls_tab(
			'rael_connector_content_tab',
			array(
				'label' => __( 'CONNECTOR', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_control(
			'rael_hide_connector',
			array(
				'label'          => __( 'Hide Connector?', 'responsive-addons-for-elementor' ),
				'type'           => Controls_Manager::SWITCHER,
				'label_on'       => __( 'Hide', 'responsive-addons-for-elementor' ),
				'label_off'      => __( 'Show', 'responsive-addons-for-elementor' ),
				'style_transfer' => true,
			)
		);

			$this->add_control(
				'rael_connector_type',
				array(
					'label'          => __( 'Connector Type', 'responsive-addons-for-elementor' ),
					'type'           => Controls_Manager::CHOOSE,
					'label_block'    => false,
					'options'        => array(
						'text' => array(
							'title' => __( 'Text', 'responsive-addons-for-elementor' ),
							'icon'  => 'fas fa-font',
						),
						'icon' => array(
							'title' => __( 'Icon', 'responsive-addons-for-elementor' ),
							'icon'  => 'fas fa-icons',
						),
					),
					'toggle'         => false,
					'default'        => 'text',
					'condition'      => array(
						'rael_hide_connector!' => 'yes',
					),
					'style_transfer' => true,
				)
			);

					$this->add_control(
						'rael_connector_text',
						array(
							'label'     => __( 'Text', 'responsive-addons-for-elementor' ),
							'type'      => Controls_Manager::TEXT,
							'default'   => 'Or',
							'dynamic'   => array(
								'active' => true,
							),
							'condition' => array(
								'rael_hide_connector!' => 'yes',
								'rael_connector_type'  => 'text',
							),
						)
					);

		if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '2.6.0', '<' ) ) {
			$this->add_control(
				'rael_connector_icon_old',
				array(
					'label'     => __( 'Icon', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::ICON,
					'options'   => Control_Icon::get_icons(),
					'condition' => array(
						'rael_hide_connector!' => 'yes',
						'rael_connector_type'  => 'icon',
					),
				)
			);
		} else {
			$this->add_control(
				'rael_connector_icon_new',
				array(
					'label'            => __( 'Icon', 'responsive-addons-for-elementor' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => 'rael_connector_icon_old',
					'default'          => array(
						'library' => 'solid',
						'value'   => 'fas fa-adjust',
					),
					'condition'        => array(
						'rael_hide_connector!' => 'yes',
						'rael_connector_type'  => 'icon',
					),
				)
			);
		}

		$this->end_controls_tab();
		// End Connector Tab.

		// Start Secondary Button Tab.
		$this->start_controls_tab(
			'rael_secondary_button_content_tab',
			array(
				'label' => __( 'SECONDARY', 'responsive-addons-for-elementor' ),
			)
		);

		$this->register_button_content_controls( 'secondary' );

		$this->end_controls_tab();
		// End Secondary Button Tab.

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_buttons_view',
			array(
				'label'           => __( 'Layout', 'responsive-addons-for-elementor' ),
				'type'            => Controls_Manager::CHOOSE,
				'label_block'     => false,
				'options'         => array(
					'queue' => array(
						'title' => __( 'Queue', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-grip-horizontal',
					),
					'stack' => array(
						'title' => __( 'Stack', 'responsive-addons-for-elementor' ),
						'icon'  => 'fas fa-grip-vertical',
					),
				),
				'toggle'          => false,
				'desktop_default' => 'queue',
				'tablet_default'  => 'queue',
				'mobile_default'  => 'queue',
				'separator'       => 'before',
				'prefix_class'    => 'rael-multi-button-%s-view-',
				'style_transfer'  => true,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Button Controls
	 *
	 * It register controls dynamically based on the $type.
	 *
	 * @param string $type Type of button.
	 *
	 * @since 1.2.1
	 * @access public
	 */
	public function register_button_content_controls( $type ) {
		$control_id_prefix = 'rael_' . $type;

		$this->add_control(
			$control_id_prefix . '_button_text',
			array(
				'label'       => __( 'Text', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => 'Button Text',
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			$control_id_prefix . '_button_link',
			array(
				'label'       => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => 'https://example.com',
				'default'     => array(
					'url' => '#',
				),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '2.6.0', '<' ) ) {
			$this->add_control(
				$control_id_prefix . '_button_icon_old',
				array(
					'label'   => __( 'Icon', 'responsive-addons-for-elementor' ),
					'type'    => Controls_Manager::ICON,
					'options' => Control_Icon::get_icons(),
				)
			);

			$condition = array( $control_id_prefix . '_button_icon_old!' => '' );
		} else {
			$this->add_control(
				$control_id_prefix . '_button_icon_new',
				array(
					'label'            => __( 'Icon', 'responsive-addons-for-elementor' ),
					'type'             => Controls_Manager::ICONS,
					'label_block'      => true,
					'fa4compatibility' => $control_id_prefix . '_button_icon_old,',
				)
			);

			$condition = array( $control_id_prefix . '_button_icon_new[value]!' => '' );
		}

		$this->add_control(
			$control_id_prefix . '_button_icon_position',
			array(
				'label'          => __( 'Icon Position', 'responsive-addons-for-elementor' ),
				'type'           => Controls_Manager::CHOOSE,
				'label_block'    => false,
				'options'        => array(
					'before' => array(
						'title' => __( 'Before', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'after'  => array(
						'title' => __( 'After', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'toggle'         => false,
				'default'        => 'before',
				'style_transfer' => true,
				'condition'      => $condition,
			)
		);

		$this->add_control(
			$control_id_prefix . '_button_icon_spacing',
			array(
				'label'     => __( 'Icon Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'condition' => $condition,
				'selectors' => array(
					'{{WRAPPER}} .rael-multi-button__' . $type . '-btn-icon--before' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-multi-button__' . $type . '-btn-icon--after' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

	}

	/**
	 * Register Common styles for widget.
	 *
	 * @return void
	 */
	public function register_common_style_controls() {
		$this->start_controls_section(
			'rael_multi_button_common_style_controls',
			array(
				'label' => __( 'Common', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_multi_button_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-multi-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_spacing_between_buttons',
			array(
				'label'      => __( 'Spacing Between Buttons', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'(desktop+){{WRAPPER}}.rael-multi-button--view-queue .rael-multi-button__primary-btn' => 'margin-right: calc({{rael_spacing_between_buttons.SIZE}}{{UNIT}}/2);',
					'(desktop+){{WRAPPER}}.rael-multi-button--view-stack .rael-multi-button__primary-btn' => 'margin-bottom: calc({{rael_spacing_between_buttons.SIZE}}{{UNIT}}/2);',
					'(desktop+){{WRAPPER}}.rael-multi-button--view-queue .rael-multi-button__secondary-btn' => 'margin-left: calc({{rael_spacing_between_buttons.SIZE}}{{UNIT}}/2);',
					'(desktop+){{WRAPPER}}.rael-multi-button--view-stack .rael-multi-button__secondary-btn' => 'margin-top: calc({{rael_spacing_between_buttons.SIZE}}{{UNIT}}/2);',

					'(tablet){{WRAPPER}}.rael-multi-button--tablet-view-queue .rael-multi-button__primary-btn' => 'margin-right: calc({{rael_spacing_between_buttons_tablet.SIZE || rael_spacing_between_buttons.SIZE}}{{UNIT}}/2); margin-bottom: 0;',
					'(tablet){{WRAPPER}}.rael-multi-button--tablet-view-stack .rael-multi-button__primary-btn' => 'margin-bottom: calc({{rael_spacing_between_buttons_tablet.SIZE || rael_spacing_between_buttons.SIZE}}{{UNIT}}/2); margin-right: 0;',
					'(tablet){{WRAPPER}}.rael-multi-button--tablet-view-queue .rael-multi-button__secondary-btn' => 'margin-left: calc({{rael_spacing_between_buttons_tablet.SIZE || rael_spacing_between_buttons.SIZE}}{{UNIT}}/2); margin-top: 0;',
					'(tablet){{WRAPPER}}.rael-multi-button--tablet-view-stack .rael-multi-button__secondary-btn' => 'margin-top: calc({{rael_spacing_between_buttons_tablet.SIZE || rael_spacing_between_buttons.SIZE}}{{UNIT}}/2); margin-left: 0;',

					'(mobile){{WRAPPER}}.rael-multi-button--mobile-view-queue .rael-multi-button__primary-btn' => 'margin-right: calc({{rael_spacing_between_buttons_mobile.SIZE || rael_spacing_between_buttons_tablet.SIZE || rael_spacing_between_buttons.SIZE}}{{UNIT}}/2); margin-bottom: 0;',
					'(mobile){{WRAPPER}}.rael-multi-button--mobile-view-stack .rael-multi-button__primary-btn' => 'margin-bottom: calc({{rael_spacing_between_buttons_mobile.SIZE || rael_spacing_between_buttons_tablet.SIZE || rael_spacing_between_buttons.SIZE}}{{UNIT}}/2); margin-right: 0;',
					'(mobile){{WRAPPER}}.rael-multi-button--mobile-view-queue .rael-multi-button__secondary-btn' => 'margin-left: calc({{rael_spacing_between_buttons_mobile.SIZE || rael_spacing_between_buttons_tablet.SIZE || rael_spacing_between_buttons.SIZE}}{{UNIT}}/2); margin-top: 0;',
					'(mobile){{WRAPPER}}.rael-multi-button--mobile-view-stack .rael-multi-button__secondary-btn' => 'margin-top: calc({{rael_spacing_between_buttons_mobile.SIZE || rael_spacing_between_buttons_tablet.SIZE || rael_spacing_between_buttons.SIZE}}{{UNIT}}/2); margin-left: 0;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_button_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-multi-button',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_multi_button_box_shadow',
				'selector' => '{{WRAPPER}} .rael-multi-button',
			)
		);

		$this->add_responsive_control(
			'rael_multi_button_alignment',
			array(
				'label'        => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'options'      => array(
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
				'default'      => 'center',
				'toggle'       => true,
				'prefix_class' => 'rael-multi-button-%s-align-',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Button style controls.
	 *
	 * Pass a type parameter to generate the controls for
	 * that type of button.
	 *
	 * @param string $type Button type(Primary or Secondary).
	 *
	 * @access public
	 * @since 1.2.1
	 */
	public function register_button_style_controls( $type ) {
		$id_prefix = 'rael_' . $type . '_button';

		switch ( $type ) {
			case 'primary':
				$section_label = __( 'Primary Button', 'responsive-addons-for-elementor' );
				break;
			case 'secondary':
				$section_label = __( 'Secondary Button', 'responsive-addons-for-elementor' );
				break;
		}

		$this->start_controls_section(
			$id_prefix . '_style_controls',
			array(
				'label' => $section_label,
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			$id_prefix . '_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-multi-button__' . $type . '-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => $id_prefix . '_border',
				'label'    => __( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-multi-button__' . $type . '-btn',
			)
		);

		$this->add_responsive_control(
			$id_prefix . '_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-multi-button__' . $type . '-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => $id_prefix . '_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .rael-multi-button__' . $type . '-btn',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => $id_prefix . '_box_shadow',
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-multi-button__' . $type . '-btn',
			)
		);

		$this->start_controls_tabs( 'rael_' . $type . '_button_state_tabs' );

		$this->start_controls_tab(
			$id_prefix . '_normal_state',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			$id_prefix . '_normal_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-multi-button__' . $type . '-btn' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			$id_prefix . '_normal_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-multi-button__' . $type . '-btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			$id_prefix . '_hover_state',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			$id_prefix . '_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-multi-button__' . $type . '-btn:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			$id_prefix . '_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-multi-button__' . $type . '-btn:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			$id_prefix . '_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-multi-button__' . $type . '-btn:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register Multi Button Connector style controls.
	 *
	 * @access public
	 * @since 1.2.1
	 */
	public function register_connector_style_controls() {
		$this->start_controls_section(
			'rael_multi_button_connector_style_controls',
			array(
				'label' => __( 'Connector', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_connector_disabled_msg',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'Connector is disabled, to enable the connector switch off the Hide Connector option from Content Tab -> Connector.', 'responsive-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'       => array(
					'rael_hide_connector' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_connector_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-multi-button__connector' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_connector_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-multi-button__connector' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_connector_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-multi-button__connector',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_connector_box_shadow',
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-multi-button__connector',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.2.1
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Primary Button.
		$this->add_render_attribute(
			'rael_primary_button',
			array(
				'class' => 'rael-multi-button rael-multi-button__primary-btn',
				'href'  => $settings['rael_primary_button_link'],
			)
		);

		$this->add_inline_editing_attributes( 'rael_primary_button_text', 'none' );

		if ( ! empty( $settings['rael_primary_button_icon_old'] ) || ! empty( $settings['rael_primary_button_icon_new'] ) ) {
			$this->add_render_attribute(
				'rael_primary_button_icon',
				'class',
				array(
					'rael-multi-button-icon',
					'rael-multi-button__primary-btn-icon--' . esc_attr( $settings['rael_primary_button_icon_position'] ),
				)
			);
		}

		// Connector.
		$this->add_render_attribute( 'rael_connector_text', 'class', 'rael-multi-button__connector' );
		if ( 'yes' !== $settings['rael_hide_connector'] ) {
			if ( 'icon' === $settings['rael_connector_type'] && ( ! empty( $settings['rael_connector_icon_old'] ) || ! empty( $settings['rael_connector_icon_new'] ) ) ) {
				$this->add_render_attribute( 'rael_connector_text', 'class', 'rael-multi-button__connector-icon' );
			} else {
				$this->add_render_attribute( 'rael_connector_text', 'class', 'rael-multi-button__connector-text' );
				$this->add_inline_editing_attributes( 'rael_connector_text', 'none' );
			}
		}

		// Secondary Button.
		$this->add_render_attribute(
			'rael_secondary_button',
			array(
				'class' => 'rael-multi-button rael-multi-button__secondary-btn',
				'href'  => $settings['rael_secondary_button_link'],
			)
		);

		$this->add_inline_editing_attributes( 'rael_secondary_button_text', 'none' );

		if ( ! empty( $settings['rael_secondary_button_icon_old'] ) || ! empty( $settings['rael_secondary_button_icon_new'] ) ) {
			$this->add_render_attribute(
				'rael_secondary_button_icon',
				'class',
				array(
					'rael-multi-button-icon',
					'rael-multi-button__secondary-btn-icon--' . esc_attr( $settings['rael_secondary_button_icon_position'] ),
				)
			);
		}
		?>

		<div class="rael-multi-button-wrapper">
			<div class="rael-multi-button__primary-btn-wrapper">
				<a <?php $this->print_render_attribute_string( 'rael_primary_button' ); ?>>
					<?php if ( 'before' === $settings['rael_primary_button_icon_position'] && ( ! empty( $settings['rael_primary_button_icon_old'] ) || ! empty( $settings['rael_primary_button_icon_new'] ) ) ) : ?>
						<span <?php $this->print_render_attribute_string( 'rael_primary_button_icon' ); ?>>
							<?php self::render_icon( $settings, 'rael_primary_button_icon_old', 'rael_primary_button_icon_new' ); ?>
						</span>
					<?php endif; ?>
					<?php if ( ! empty( $settings['rael_primary_button_text'] ) ) : ?>
						<span <?php $this->print_render_attribute_string( 'rael_primary_button_text' ); ?>>
							<?php echo esc_html( $settings['rael_primary_button_text'] ); ?>
						</span>
					<?php endif; ?>
					<?php if ( 'after' === $settings['rael_primary_button_icon_position'] && ( ! empty( $settings['rael_primary_button_icon_old'] ) || ! empty( $settings['rael_primary_button_icon_new'] ) ) ) : ?>
						<span <?php $this->print_render_attribute_string( 'rael_primary_button_icon' ); ?>>
							<?php self::render_icon( $settings, 'rael_primary_button_icon_old', 'rael_primary_button_icon_new' ); ?>
						</span>
					<?php endif; ?>
				</a>
				<?php if ( 'yes' !== $settings['rael_hide_connector'] ) : ?>
					<span <?php $this->print_render_attribute_string( 'rael_connector_text' ); ?>>
						<?php if ( 'icon' === $settings['rael_connector_type'] && ( ! empty( $settings['rael_connector_icon_old'] ) || ! empty( $settings['rael_connector_icon_new'] ) ) ) : ?>
							<?php self::render_icon( $settings, 'rael_connector_icon_old', 'rael_connector_icon_new' ); ?>
						<?php else : ?>
							<?php echo esc_html( $settings['rael_connector_text'] ); ?>
						<?php endif; ?>
					</span>
				<?php endif; ?>
			</div>
			<div class="rael-multi-button__secondary-btn-wrapper">
				<a <?php $this->print_render_attribute_string( 'rael_secondary_button' ); ?>>
					<?php if ( 'before' === $settings['rael_secondary_button_icon_position'] && ( ! empty( $settings['rael_secondary_button_icon_old'] ) || ! empty( $settings['rael_secondary_button_icon_new'] ) ) ) : ?>
						<span <?php $this->print_render_attribute_string( 'rael_secondary_button_icon' ); ?>>
							<?php self::render_icon( $settings, 'rael_secondary_button_icon_old', 'rael_secondary_button_icon_new' ); ?>
						</span>
					<?php endif; ?>
					<?php if ( ! empty( $settings['rael_secondary_button_text'] ) ) : ?>
						<span <?php $this->print_render_attribute_string( 'rael_secondary_button_text' ); ?>>
							<?php echo esc_html( $settings['rael_secondary_button_text'] ); ?>
						</span>
					<?php endif; ?>
					<?php if ( 'after' === $settings['rael_secondary_button_icon_position'] && ( ! empty( $settings['rael_secondary_button_icon_old'] ) || ! empty( $settings['rael_secondary_button_icon_new'] ) ) ) : ?>
						<span <?php $this->print_render_attribute_string( 'rael_secondary_button_icon' ); ?>>
							<?php self::render_icon( $settings, 'rael_secondary_button_icon_old', 'rael_secondary_button_icon_new' ); ?>
						</span>
					<?php endif; ?>
				</a>
			</div>
		</div>
		<?php
	}

	/**
	 * Render an icon using Elementor's Icons_Manager.
	 *
	 * This method checks if the widget settings have migrated to a new icon format
	 * and renders the icon accordingly using Elementor's Icons_Manager.
	 *
	 * @param array  $settings     Widget settings.
	 * @param string $old_icon     The old icon control key.
	 * @param string $new_icon     The new icon control key.
	 * @param array  $attributes   Additional HTML attributes for the icon.
	 *
	 * @return void
	 */
	protected static function render_icon( $settings, $old_icon, $new_icon, $attributes = array() ) {
		// Check if its already migrated.
		$migrated = isset( $settings['__fa4_migrated'][ $new_icon ] );
		// Check if its a new widget without previously selected icon using the old Icon control.
		$is_new = empty( $settings[ $old_icon ] );

		$attributes['aria-hidden'] = 'true';

		if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '2.6.0', '>=' ) && ( $is_new || $migrated ) ) {
			\Elementor\Icons_Manager::render_icon( $settings[ $new_icon ], $attributes );
		} else {
			if ( empty( $attributes['class'] ) ) {
				$attributes['class'] = $settings[ $old_icon ];
			} else {
				if ( is_array( $attributes['class'] ) ) {
					$attributes['class'][] = $settings[ $old_icon ];
				} else {
					$attributes['class'] .= ' ' . $settings[ $old_icon ];
				}
			}
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			printf( '<i %s></i>', \Elementor\Utils::render_html_attributes( $attributes ) );
		}
	}
}
