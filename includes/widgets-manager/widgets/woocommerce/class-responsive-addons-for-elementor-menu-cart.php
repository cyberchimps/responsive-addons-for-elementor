<?php
/**
 * RAEL Menu cart.
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\WooCommerce;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Responsive_Addons_For_Elementor\WidgetsManager\Responsive_Addons_For_Elementor_Widgets_Manager;
use Responsive_Addons_For_Elementor\Traits\Missing_Dependency;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Class representing a custom Elementor widget for displaying the menu cart.
 *
 * This class extends the Elementor\Widget_Base class and provides functionality
 * for rendering the menu cart with additional customization options.
 *
 * @package Responsive_Addons_For_Elementor
 */
class Responsive_Addons_For_Elementor_Menu_Cart extends Widget_Base {
	use Missing_Dependency;
	/**
	 * Retrieves the name of the widget.
	 *
	 * @return string The widget name.
	 */
	public function get_name() {
		return 'rael-wc-menu-cart';
	}
	/**
	 * Retrieves the title of the widget.
	 *
	 * @return string The widget title.
	 */
	public function get_title() {
		return __( 'Menu Cart', 'responsive-addons-for-elementor' );
	}
	/**
	 * Retrieves the icon of the widget.
	 *
	 * @return string The widget icon.
	 */
	public function get_icon() {
		return 'eicon-cart rael-badge';
	}
	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the slider widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}
	/**
	 * Register controls for the widget
	 *
	 * @since 1.0.0
	 *
	 * @since 1.5.0 Added a condition to display a warning message in the editor when the dependency plugin is not activated.
	 *
	 * @return void
	 */
	protected function register_controls() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			$this->register_content_tab_missing_dep_warning_controls( 'WooCommerce', 'woocommerce' );
			return;
		}
		$this->start_controls_section(
			'section_menu_icon_content',
			array(
				'label' => __( 'Menu Icon', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_control(
			'icon',
			array(
				'label'        => __( 'Icon', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'cart-light'    => __( 'Cart', 'responsive-addons-for-elementor' ) . ' ' . __( 'Light', 'responsive-addons-for-elementor' ),
					'cart-medium'   => __( 'Cart', 'responsive-addons-for-elementor' ) . ' ' . __( 'Medium', 'responsive-addons-for-elementor' ),
					'cart-solid'    => __( 'Cart', 'responsive-addons-for-elementor' ) . ' ' . __( 'Solid', 'responsive-addons-for-elementor' ),
					'basket-light'  => __( 'Basket', 'responsive-addons-for-elementor' ) . ' ' . __( 'Light', 'responsive-addons-for-elementor' ),
					'basket-medium' => __( 'Basket', 'responsive-addons-for-elementor' ) . ' ' . __( 'Medium', 'responsive-addons-for-elementor' ),
					'basket-solid'  => __( 'Basket', 'responsive-addons-for-elementor' ) . ' ' . __( 'Solid', 'responsive-addons-for-elementor' ),
					'bag-light'     => __( 'Bag', 'responsive-addons-for-elementor' ) . ' ' . __( 'Light', 'responsive-addons-for-elementor' ),
					'bag-medium'    => __( 'Bag', 'responsive-addons-for-elementor' ) . ' ' . __( 'Medium', 'responsive-addons-for-elementor' ),
					'bag-solid'     => __( 'Bag', 'responsive-addons-for-elementor' ) . ' ' . __( 'Solid', 'responsive-addons-for-elementor' ),
				),
				'default'      => 'cart-medium',
				'prefix_class' => 'toggle-icon--',
			)
		);
		$this->add_control(
			'items_indicator',
			array(
				'label'        => __( 'Items Indicator', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'none'   => __( 'None', 'responsive-addons-for-elementor' ),
					'bubble' => __( 'Bubble', 'responsive-addons-for-elementor' ),
					'plain'  => __( 'Plain', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'rael-menu-cart--items-indicator-',
				'default'      => 'bubble',
			)
		);
		$this->add_control(
			'hide_empty_indicator',
			array(
				'label'        => __( 'Hide Empty', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'hide',
				'prefix_class' => 'rael-menu-cart--empty-indicator-',
				'condition'    => array(
					'items_indicator!' => 'none',
				),
			)
		);
		$this->add_control(
			'show_subtotal',
			array(
				'label'        => __( 'Subtotal', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => 'rael-menu-cart--show-subtotal-',
			)
		);
		$this->add_control(
			'alignment',
			array(
				'label'     => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__toggle' => 'text-align: {{VALUE}}',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_toggle_style',
			array(
				'label' => __( 'Menu Icon', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->start_controls_tabs( 'toggle_button_colors' );
		$this->start_controls_tab( 'toggle_button_normal_colors', array( 'label' => __( 'Normal', 'responsive-addons-for-elementor' ) ) );
		$this->add_control(
			'toggle_button_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__toggle .elementor-button' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'toggle_button_icon_color',
			array(
				'label'     => __( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__toggle .elementor-button-icon' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'toggle_button_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__toggle .elementor-button' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'toggle_button_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__toggle .elementor-button' => 'border-color: {{VALUE}}',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab( 'toggle_button_hover_colors', array( 'label' => __( 'Hover', 'responsive-addons-for-elementor' ) ) );
		$this->add_control(
			'toggle_button_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__toggle .elementor-button:hover' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'toggle_button_hover_icon_color',
			array(
				'label'     => __( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__toggle .elementor-button:hover .elementor-button-icon' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'toggle_button_hover_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__toggle .elementor-button:hover' => 'background-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'toggle_button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__toggle .elementor-button:hover' => 'border-color: {{VALUE}}',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			'toggle_button_border_width',
			array(
				'label'     => __( 'Border Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__toggle .elementor-button' => 'border-width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'toggle_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-menu-cart__toggle .elementor-button' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'toggle_button_typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'  => '{{WRAPPER}} .rael-menu-cart__toggle .elementor-button',
				'separator' => 'before',
			)
		);
		$this->add_control(
			'heading_icon_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Icon', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
			)
		);
		$this->add_control(
			'toggle_icon_size',
			array(
				'label'      => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-menu-cart__toggle .elementor-button-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_control(
			'toggle_icon_spacing',
			array(
				'label'      => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'size-units' => array( 'px', 'em' ),
				'selectors'  => array(
					'body:not(.rtl) {{WRAPPER}} .rael-menu-cart__toggle .elementor-button-text' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .rael-menu-cart__toggle .elementor-button-text' => 'margin-left: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_responsive_control(
			'toggle_button_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-menu-cart__toggle .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);
		$this->add_control(
			'items_indicator_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Items Indicator', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
				'condition' => array(
					'items_indicator!' => 'none',
				),
			)
		);
		$this->add_control(
			'items_indicator_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__toggle .elementor-button-icon[data-counter]:before' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'items_indicator!' => 'none',
				),
			)
		);
		$this->add_control(
			'items_indicator_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__toggle .elementor-button-icon[data-counter]:before' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'items_indicator' => 'bubble',
				),
			)
		);
		$this->add_control(
			'items_indicator_distance',
			array(
				'label'     => __( 'Distance', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'unit' => 'em',
				),
				'range'     => array(
					'em' => array(
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__toggle .elementor-button-icon[data-counter]:before' => 'right: -{{SIZE}}{{UNIT}}; top: -{{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'items_indicator' => 'bubble',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_cart_style',
			array(
				'label' => __( 'Cart', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'show_divider',
			array(
				'label'        => __( 'Divider', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => 'rael-menu-cart--show-divider-',
			)
		);
		$this->add_control(
			'show_remove_icon',
			array(
				'label'        => __( 'Remove Item Icon', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => 'rael-menu-cart--show-remove-button-',
			)
		);
		$this->add_control(
			'heading_subtotal_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Subtotal', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
			)
		);
		$this->add_control(
			'subtotal_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__subtotal' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'subtotal_typography',
				'selector' => '{{WRAPPER}} .rael-menu-cart__subtotal',
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_product_tabs_style',
			array(
				'label' => __( 'Products', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'heading_product_title_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Product Title', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
			)
		);
		$this->add_control(
			'product_title_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__product-name, {{WRAPPER}} .rael-menu-cart__product-name a' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_title_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .rael-menu-cart__product-name, {{WRAPPER}} .rael-menu-cart__product-name a',
			)
		);
		$this->add_control(
			'heading_product_price_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Product Price', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
			)
		);
		$this->add_control(
			'product_price_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__product-price' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_price_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .rael-menu-cart__product-price',
			)
		);
		$this->add_control(
			'heading_product_divider_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Divider', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
			)
		);
		$this->add_control(
			'divider_style',
			array(
				'label'     => __( 'Style', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''       => __( 'None', 'responsive-addons-for-elementor' ),
					'solid'  => __( 'Solid', 'responsive-addons-for-elementor' ),
					'double' => __( 'Double', 'responsive-addons-for-elementor' ),
					'dotted' => __( 'Dotted', 'responsive-addons-for-elementor' ),
					'dashed' => __( 'Dashed', 'responsive-addons-for-elementor' ),
					'groove' => __( 'Groove', 'responsive-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__product, {{WRAPPER}} .rael-menu-cart__subtotal' => 'border-bottom-style: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'divider_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__product, {{WRAPPER}} .rael-menu-cart__subtotal' => 'border-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'divider_width',
			array(
				'label'     => __( 'Weight', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__product, {{WRAPPER}} .rael-menu-cart__products, {{WRAPPER}} .rael-menu-cart__subtotal' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_responsive_control(
			'divider_gap',
			array(
				'label'     => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__product, {{WRAPPER}} .rael-menu-cart__subtotal' => 'padding-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .rael-menu-cart__product:not(:first-of-type), {{WRAPPER}} .rael-menu-cart__footer-buttons, {{WRAPPER}} .rael-menu-cart__subtotal' => 'padding-top: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_style_buttons',
			array(
				'label' => __( 'Buttons', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'buttons_layout',
			array(
				'label'        => __( 'Layout', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'inline'  => __( 'Inline', 'responsive-addons-for-elementor' ),
					'stacked' => __( 'Stacked', 'responsive-addons-for-elementor' ),
				),
				'default'      => 'inline',
				'prefix_class' => 'rael-menu-cart--buttons-',
			)
		);
		$this->add_control(
			'space_between_buttons',
			array(
				'label'     => __( 'Space Between', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__footer-buttons' => 'grid-column-gap: {{SIZE}}{{UNIT}}; grid-row-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'product_buttons_typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'  => '{{WRAPPER}} .rael-menu-cart__footer-buttons .elementor-button',
				'separator' => 'before',
			)
		);
		$this->add_control(
			'button_border_radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-menu-cart__footer-buttons .elementor-button' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_control(
			'heading_view_cart_button_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'View Cart', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
			)
		);
		$this->add_control(
			'view_cart_button_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-button--view-cart' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'view_cart_button_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-button--view-cart' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'view_cart_border',
				'selector' => '{{WRAPPER}} .elementor-button--view-cart',
			)
		);
		$this->add_control(
			'heading_checkout_button_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Checkout', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
			)
		);
		$this->add_control(
			'checkout_button_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-button--checkout' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'checkout_button_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-button--checkout' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'checkout_border',
				'selector' => '{{WRAPPER}} .elementor-button--checkout',
			)
		);
		$this->end_controls_section();
	}
	/**
	 * Check if user did not explicitly disabled the use of our mini-cart template and set the option accordingly.
	 */
	private function maybe_use_mini_cart_template() {
		$option_value = get_option( 'elementor_' . Responsive_Addons_For_Elementor_Widgets_Manager::OPTION_NAME_USE_MINI_CART, '' );
		if ( empty( $option_value ) || 'initial' === $option_value ) {
			update_option( 'elementor_' . Responsive_Addons_For_Elementor_Widgets_Manager::OPTION_NAME_USE_MINI_CART, 'yes' );
		}
	}
	/**
	 * Render the widget.
	 *
	 * @since 1.0.0
	 *
	 * @since 1.5.0 Added a condition to check whether the dependency plugin is activated or not.
	 *
	 * @return void
	 */
	protected function render() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		$this->maybe_use_mini_cart_template();
		Responsive_Addons_For_Elementor_Widgets_Manager::render_menu_cart();
	}
	/**
	 * Renders plain content for the widget.
	 *
	 * This method is responsible for rendering plain content when the widget is viewed
	 * in the Elementor editor's plain text mode.
	 */
	public function render_plain_content() {}
	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/menu-cart';
	}
}
