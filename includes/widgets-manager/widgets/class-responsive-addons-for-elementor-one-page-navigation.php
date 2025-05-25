<?php
/**
 * RAEL One Page Navigation
 *
 * @since      1.8.0
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Utils;
use \Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * One Page Navigation Widget
 *
 * @since 1.8.0
 */
class Responsive_Addons_For_Elementor_One_Page_Navigation extends Widget_Base {
	/**
	 * Retrieve one page navigation widget name.
	 */
	public function get_name() {
		return 'rael-one-page-nav';
	}

	/**
	 * Retrieve one page navigation widget title.
	 */
	public function get_title() {
		return __( 'One Page Navigation', 'responsive-addons-for-elementor' );
	}

	/**
	 * Retrieve the list of categories the one page navigation widget belongs to.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Retrieve the list of keywords for the one page navigation widget.
	 */
	public function get_keywords() {
		return array(
			'single page navigation',
			'rael single page navigation',
			'one page navigation',
			'rael one page navigation',
			'rael onepage navigation',
			'spa',
			'single page application',
			'page scroller',
			'page navigator',
		);
	}
	/**
	 * Retrieve one page navigation widget icon.
	 */
	public function get_icon() {
		return 'eicon-page-transition rael-badge';
	}

	/**
	 * Register one page navigation widget controls.
	 */
	protected function register_controls() {

		/**
		 * CONTENT TAB
		 */

		/**
		 * Content Tab: Navigation Dots
		 */
		$this->start_controls_section(
			'rael_section_nav_dots',
			array(
				'label' => __( 'Navigation Dots', 'responsive-addons-for-elementor' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'rael_section_title',
			array(
				'label'   => __( 'Section Title', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Section Title', 'responsive-addons-for-elementor' ),
			)
		);

		$repeater->add_control(
			'rael_section_id',
			array(
				'label'   => __( 'Section ID', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => '',
			)
		);

		$repeater->add_control(
			'rael_dot_icon_new',
			array(
				'label'            => __( 'Navigation Dot', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'dot_icon',
				'default'          => array(
					'value'   => 'fas fa-circle',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'rael_nav_dots',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'rael_section_title' => __( 'Section #1', 'responsive-addons-for-elementor' ),
						'rael_section_id'    => 'section-1',
						'dot_icon'           => 'fa fa-circle',
					),
					array(
						'rael_section_title' => __( 'Section #2', 'responsive-addons-for-elementor' ),
						'rael_section_id'    => 'section-2',
						'dot_icon'           => 'fa fa-circle',
					),
					array(
						'rael_section_title' => __( 'Section #3', 'responsive-addons-for-elementor' ),
						'rael_section_id'    => 'section-3',
						'dot_icon'           => 'fa fa-circle',
					),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ rael_section_title }}}',
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Settings
		 */
		$this->start_controls_section(
			'rael_section_onepage_nav_settings',
			array(
				'label' => __( 'Settings', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_nav_tooltip',
			array(
				'label'        => __( 'Tooltip', 'responsive-addons-for-elementor' ),
				'description'  => __( 'Show tooltip on hover', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_tooltip_arrow',
			array(
				'label'        => __( 'Tooltip Arrow', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'condition'    => array(
					'rael_nav_tooltip' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_scroll_wheel',
			array(
				'label'        => __( 'Scroll Wheel', 'responsive-addons-for-elementor' ),
				'description'  => __( 'Use mouse wheel to navigate from one row to another', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'off',
				'label_on'     => __( 'On', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Off', 'responsive-addons-for-elementor' ),
				'return_value' => 'on',
			)
		);

		$this->add_control(
			'rael_scroll_touch',
			array(
				'label'        => __( 'Touch Swipe', 'responsive-addons-for-elementor' ),
				'description'  => __( 'Use touch swipe to navigate from one row to another in mobile devices', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'off',
				'label_on'     => __( 'On', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Off', 'responsive-addons-for-elementor' ),
				'return_value' => 'on',
				'condition'    => array(
					'rael_scroll_wheel' => 'on',
				),
			)
		);

		$this->add_control(
			'rael_scroll_keys',
			array(
				'label'        => __( 'Scroll Keys', 'responsive-addons-for-elementor' ),
				'description'  => __( 'Use UP and DOWN arrow keys to navigate from one row to another', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'off',
				'label_on'     => __( 'On', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Off', 'responsive-addons-for-elementor' ),
				'return_value' => 'on',
			)
		);

		$this->add_control(
			'rael_top_offset',
			array(
				'label'      => __( 'Row Top Offset', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => '0' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 300,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
			)
		);

		$this->add_control(
			'rael_scrolling_speed',
			array(
				'label'   => __( 'Scrolling Speed', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '700',
			)
		);

		$this->end_controls_section();

		/**
		 * STYLE TAB
		 */

		/**
		 * Style Tab: Navigation Box
		 */
		$this->start_controls_section(
			'rael_section_nav_box_style',
			array(
				'label' => __( 'Navigation Box', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_heading_alignment',
			array(
				'label'              => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::CHOOSE,
				'options'            => array(
					'top'    => array(
						'title' => __( 'Top', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					),
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'            => 'right',
				'prefix_class'       => 'nav-align-',
				'frontend_available' => true,
				'selectors'          => array(
					'{{WRAPPER}} .rael-caldera-form-heading' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_nav_container_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-one-page-nav',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'rael_nav_container_border',
				'label'       => __( 'Border', 'responsive-addons-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .rael-one-page-nav',
			)
		);

		$this->add_control(
			'rael_nav_container_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-one-page-nav' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_nav_container_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-one-page-nav-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_nav_container_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-one-page-nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'rael_nav_container_box_shadow',
				'selector'  => '{{WRAPPER}} .rael-one-page-nav',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Navigation Dots
		 */
		$this->start_controls_section(
			'rael_section_dots_style',
			array(
				'label' => __( 'Navigation Dots', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_dots_size',
			array(
				'label'      => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => '10' ),
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 60,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-nav-dot' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_dots_spacing',
			array(
				'label'      => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => '10' ),
				'range'      => array(
					'px' => array(
						'min'  => 2,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}}.nav-align-right .rael-one-page-nav-item, {{WRAPPER}}.nav-align-left .rael-one-page-nav-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.nav-align-top .rael-one-page-nav-item, {{WRAPPER}}.nav-align-bottom .rael-one-page-nav-item' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: 0',
					'.rtl {{WRAPPER}}.nav-align-top .rael-one-page-nav-item, {{WRAPPER}}.nav-align-bottom .rael-one-page-nav-item' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: 0;',
				),
			)
		);

		$this->add_responsive_control(
			'rael_dots_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-nav-dot-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'rael_dots_box_shadow',
				'selector'  => '{{WRAPPER}} .rael-nav-dot-wrap',
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'rael_tabs_dots_style' );

		$this->start_controls_tab(
			'rael_tab_dots_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_dots_color_normal',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-nav-dot' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_dots_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-nav-dot-wrap' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'rael_dots_border',
				'label'       => __( 'Border', 'responsive-addons-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .rael-nav-dot-wrap',
			)
		);

		$this->add_control(
			'rael_dots_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-nav-dot-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_tab_dots_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_dots_color_hover',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-one-page-nav-item .rael-nav-dot-wrap:hover .rael-nav-dot' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_dots_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-one-page-nav-item .rael-nav-dot-wrap:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_dots_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-one-page-nav-item .rael-nav-dot-wrap:hover' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_tab_dots_active',
			array(
				'label' => __( 'Active', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_dots_color_active',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-one-page-nav-item.active .rael-nav-dot' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_dots_bg_color_active',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-one-page-nav-item.active .rael-nav-dot-wrap' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_dots_border_color_active',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-one-page-nav-item.active .rael-nav-dot-wrap' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Style Tab: Tooltip
		 */
		$this->start_controls_section(
			'rael_section_tooltips_style',
			array(
				'label'     => __( 'Tooltip', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_nav_tooltip' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_tooltip_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-nav-dot-tooltip-content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .rael-nav-dot-tooltip' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'rael_nav_tooltip' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_tooltip_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-nav-dot-tooltip-content' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'rael_nav_tooltip' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_tooltip_typography',
				'label'     => __( 'Typography', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector'  => '{{WRAPPER}} .rael-nav-dot-tooltip',
				'condition' => array(
					'rael_nav_tooltip' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_tooltip_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-nav-dot-tooltip-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}
		/**
		 * Render output for the one-page navigation.
		 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'onepage-nav-container', 'class', 'rael-one-page-nav-container' );

		$this->add_render_attribute( 'onepage-nav', 'class', 'rael-one-page-nav' );

		$this->add_render_attribute( 'onepage-nav', 'id', 'rael-one-page-nav-' . $this->get_id() );

		$this->add_render_attribute( 'onepage-nav', 'data-section-id', 'rael-one-page-nav-' . $this->get_id() );

		$this->add_render_attribute( 'onepage-nav', 'data-top-offset', $settings['rael_top_offset']['size'] );

		$this->add_render_attribute( 'onepage-nav', 'data-scroll-speed', $settings['rael_scrolling_speed'] );

		$this->add_render_attribute( 'onepage-nav', 'data-scroll-wheel', $settings['rael_scroll_wheel'] );

		$this->add_render_attribute( 'onepage-nav', 'data-scroll-touch', $settings['rael_scroll_touch'] );

		$this->add_render_attribute( 'onepage-nav', 'data-scroll-keys', $settings['rael_scroll_keys'] );

		$this->add_render_attribute( 'tooltip', 'class', 'rael-nav-dot-tooltip' );

		if ( 'yes' === $settings['rael_tooltip_arrow'] ) {
			$this->add_render_attribute( 'tooltip', 'class', 'rael-tooltip-arrow' );
		}
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'onepage-nav-container' ) ); ?>>
			<ul <?php echo wp_kses_post( $this->get_render_attribute_string( 'onepage-nav' ) ); ?>>
				<?php
				$i = 1;
				foreach ( $settings['rael_nav_dots'] as $index => $dot ) {
					$rael_section_title = $dot['rael_section_title'];
					$rael_section_id    = $dot['rael_section_id'];
					$rael_dot_icon      = ( ( isset( $settings['__fa4_migrated']['rael_dot_icon_new'] ) || empty( $dot['dot_icon'] ) || ! empty( $dot['rael_dot_icon_new']['value'] ) ) ? $dot['rael_dot_icon_new']['value'] : $dot['dot_icon'] );

					if ( 'yes' === $settings['rael_nav_tooltip'] ) {
						$rael_dot_tooltip = sprintf( '<span %1$s><span class="rael-nav-dot-tooltip-content">%2$s</span></span>', $this->get_render_attribute_string( 'tooltip' ), $rael_section_title );
					} else {
						$rael_dot_tooltip = '';
					}

					if ( isset( $rael_dot_icon['url'] ) ) {
						printf( '<li class="rael-one-page-nav-item">%1$s<a href="#" data-row-id="%2$s"><span class="rael-nav-dot-wrap"><img class="rael-nav-dot" src="%3$s" alt="%4$s" /></span></a></li>', wp_kses_post( $rael_dot_tooltip ), esc_html( $rael_section_id ), esc_url( $rael_dot_icon['url'] ), esc_attr( get_post_meta( $rael_dot_icon['id'], '_wp_attachment_image_alt', true ) ) );
					} else {
						printf( '<li class="rael-one-page-nav-item">%1$s<a href="#" data-row-id="%2$s"><span class="rael-nav-dot-wrap"><span class="rael-nav-dot %3$s"></span></span></a></li>', wp_kses_post( $rael_dot_tooltip ), esc_html( $rael_section_id ), esc_html( $rael_dot_icon ) );
					}

					$i++;
				}
				?>
			</ul>
		</div>
		<?php
	}

	/**
	 * Render one page navigation widget output in the editor.
	 */
	protected function content_template() {     }


}
