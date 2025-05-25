<?php
/**
 * RAEL Theme Builder's Product Price Widget.
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder\Woo_Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * RAEL Theme Product price widget class.
 *
 * @since 1.8.0
 */
class Responsive_Addons_For_Elementor_Theme_Product_Price extends Woo_Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-theme-product-price';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Product Price', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-price rael-badge';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve widget keywords.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @return string Widget keywords.
	 */
	public function get_keywords() {
		return array( 'rael', 'woocommerce', 'shop', 'store', 'price', 'product', 'sale' );
	}

	/**
	 * Get widget help url.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @return string Widget keywords.
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/';
	}

	/**
	 * Register all the control settings for the product price widget
	 *
	 * @since 1.8.0
	 * @access public
	 */
	protected function register_controls() {
		// Price Section under the style tab.
		$this->start_controls_section(
			'rael_section_price_style',
			array(
				'label' => esc_html__( 'Price', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_wc_style_warning',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'responsive-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_responsive_control(
			'rael_text_align',
			array(
				'label'     => esc_html__( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_price_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .price' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '.woocommerce {{WRAPPER}} .price',
			)
		);

		$this->add_control(
			'rael_sale_heading',
			array(
				'label'     => esc_html__( 'Sale Price', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_sale_price_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .price ins' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_sale_price_typography',
				'selector' => '.woocommerce {{WRAPPER}} .price ins',
			)
		);

		$this->add_control(
			'rael_price_block',
			array(
				'label'        => esc_html__( 'Stacked', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'prefix_class' => 'elementor-product-price-block-',
			)
		);

		$this->add_responsive_control(
			'rael_sale_price_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'em' => array(
						'min'  => 0,
						'max'  => 5,
						'step' => 0.1,
					),
				),
				'selectors'  => array(
					'body:not(.rtl) {{WRAPPER}}:not(.elementor-product-price-block-yes) del' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}}:not(.elementor-product-price-block-yes) del' => 'margin-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-product-price-block-yes del' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render function for the widget
	 *
	 * @since 1.8.0
	 *
	 * @access public
	 */
	protected function render() {
		global $product;
		$product = wc_get_product();

		if ( empty( $product ) ) {
			return;
		}

		wc_get_template( '/single-product/price.php' );
	}

	/**
	 * Render plain content function for the widget
	 *
	 * @since 1.8.0
	 *
	 * @access public
	 */
	public function render_plain_content() {}


}
