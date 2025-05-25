<?php
/**
 * RAE Theme Builder's Product Additional Information Widget.
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder\Woo_Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * RAEL Theme Product Additional Info widget class.
 */
class Responsive_Addons_For_Elementor_Theme_Product_Additional_Info extends Woo_Widget_Base {

	/**
	 * Get widget name
	 */
	public function get_name() {
		return 'rael-theme-product-additional-info';
	}
	/**
	 * Get widget title
	 */
	public function get_title() {
		return esc_html__( 'Product Additional Information', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon
	 */
	public function get_icon() {
		return ' eicon-product-info rael-badge';
	}

	/**
	 * Get widget categories
	 */
	public function get_keywords() {
		return array( 'rael', 'woocommerce', 'shop', 'store', 'product', 'info', 'additional', 'custom' );
	}

	/**
	 * Get the group name for the controls.
	 *
	 * This method returns the group name for the controls, which is 'woocommerce'.
	 *
	 * @return string The group name for the controls.
	 */
	public function get_group_name() {
		return 'woocommerce';
	}

	/**
	 * Get custom help url
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/';
	}

	/**
	 * Register controls for the style tab of the product additional info section.
	 *
	 * This method registers controls for styling the general aspects of the product additional info section
	 * in the WooCommerce product page.
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'rael_product_additional_info_style_tab_general_section',
			array(
				'label' => esc_html__( 'General', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_show_heading',
			array(
				'label'        => esc_html__( 'Heading', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => esc_html__( 'Hide', 'responsive-addons-for-elementor' ),
				'render_type'  => 'ui',
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => 'elementor-show-heading-',
			)
		);

		$this->add_control(
			'rael_heading_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} h2' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'rael_show_heading!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_heading_typography',
				'label'     => esc_html__( 'Typography', 'responsive-addons-for-elementor' ),
				'selector'  => '.woocommerce {{WRAPPER}} h2',
				'condition' => array(
					'rael_show_heading!' => '',
				),
			)
		);

		$this->add_control(
			'rael_content_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .shop_attributes' => 'color: {{VALUE}}',
				),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_content_typography',
				'label'    => esc_html__( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '.woocommerce {{WRAPPER}} .shop_attributes',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Renders the additional information tab for a single product.
	 *
	 * This function retrieves the current product object using wc_get_product()
	 * and then includes the additional information template if the product is not empty.
	 *
	 * @return void
	 */
	protected function render() {
		global $product;
		$product = wc_get_product();

		if ( empty( $product ) ) {
			return;
		}

		wc_get_template( 'single-product/tabs/additional-information.php' );
	}

	/**
	 * Renders the plain content for this widget.
	 *
	 * This function should return the plain content to be displayed by the widget.
	 */
	public function render_plain_content() {}
}
