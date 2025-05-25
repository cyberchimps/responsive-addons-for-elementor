<?php
/**
 * RAEL Theme Builder's Product Upsell Widget.
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * RAEL Theme Product Upsell widget class.
 */
class Responsive_Addons_For_Elementor_Theme_Product_Upsell extends Responsive_Addons_For_Elementor_Woo_Products_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-woocommerce-product-upsell';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Product Upsells', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-upsell rael-badge';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve widget keywords.
	 *
	 * @access public
	 *
	 * @return string Widget keywords.
	 */
	public function get_keywords() {
		return array( 'rael', 'woocommerce', 'shop', 'store', 'upsell', 'product' );
	}

	/**
	 * Get widget group name.
	 *
	 * Retrieve widget group name.
	 *
	 * @access public
	 *
	 * @return string Widget group name.
	 */
	public function get_group_name() {
		return 'woocommerce';
	}

	/**
	 * Get widget URL.
	 *
	 * Retrieve widget URL.
	 *
	 * @access public
	 *
	 * @return string Widget URL.
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/';
	}

	/**
	 * Register all the control settings for the widget
	 *
	 * @access public
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'rael_section_upsell_content',
			array(
				'label' => esc_html__( 'Upsells', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_columns_responsive_control();

		$this->add_control(
			'rael_orderby',
			array(
				'label'   => esc_html__( 'Order By', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'       => esc_html__( 'Date', 'responsive-addons-for-elementor' ),
					'title'      => esc_html__( 'Title', 'responsive-addons-for-elementor' ),
					'price'      => esc_html__( 'Price', 'responsive-addons-for-elementor' ),
					'popularity' => esc_html__( 'Popularity', 'responsive-addons-for-elementor' ),
					'rating'     => esc_html__( 'Rating', 'responsive-addons-for-elementor' ),
					'rand'       => esc_html__( 'Random', 'responsive-addons-for-elementor' ),
					'menu_order' => esc_html__( 'Menu Order', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_order',
			array(
				'label'   => esc_html__( 'Order', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => array(
					'asc'  => esc_html__( 'ASC', 'responsive-addons-for-elementor' ),
					'desc' => esc_html__( 'DESC', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->end_controls_section();

		parent::register_controls();

		$this->start_injection(
			array(
				'at' => 'before',
				'of' => 'section_design_box',
			)
		);

		$this->start_controls_section(
			'rael_section_heading_style',
			array(
				'label' => esc_html__( 'Heading', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_show_heading',
			array(
				'label'        => esc_html__( 'Heading', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => esc_html__( 'Hide', 'responsive-addons-for-elementor' ),
				'label_on'     => esc_html__( 'Show', 'responsive-addons-for-elementor' ),
				'default'      => 'yes',
				'return_value' => 'yes',
				'prefix_class' => 'show-heading-',
			)
		);

		$this->add_control(
			'rael_heading_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}}.elementor-wc-products .products > h2' => 'color: {{VALUE}}',
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
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'  => '{{WRAPPER}}.elementor-wc-products .products > h2',
				'condition' => array(
					'rael_show_heading!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'rael_heading_text_align',
			array(
				'label'     => esc_html__( 'Text Align', 'responsive-addons-for-elementor' ),
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
					'{{WRAPPER}}.elementor-wc-products .products > h2' => 'text-align: {{VALUE}}',
				),
				'condition' => array(
					'rael_show_heading!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'rael_heading_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}}.elementor-wc-products .products > h2' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'rael_show_heading!' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->end_injection();
	}

	/**
	 * Render function for the widget
	 *
	 * @access public
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Add a wrapper class to the Add to Cart & View Items elements if the automically_align_buttons switch has been selected.
		if ( 'yes' === $settings['rael_automatically_align_buttons'] ) {
			add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'add_to_cart_wrapper' ), 10, 1 );
		}

		$limit   = '-1';
		$columns = 4;
		$orderby = 'rand';
		$order   = 'desc';

		if ( ! empty( $settings['columns'] ) ) {
			$columns = $settings['columns'];
		}

		if ( ! empty( $settings['rael_orderby'] ) ) {
			$orderby = $settings['rael_orderby'];
		}

		if ( ! empty( $settings['rael_order'] ) ) {
			$order = $settings['rael_order'];
		}

		ob_start();

		woocommerce_upsell_display( $limit, $columns, $orderby, $order );

		$upsells_html = ob_get_clean();

		if ( $upsells_html ) {
			$upsells_html = str_replace( '<ul class="products', '<ul class="products elementor-grid', $upsells_html );

			echo wp_kses_post( $upsells_html );
		}

		if ( 'yes' === $settings['rael_automatically_align_buttons'] ) {
			remove_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'add_to_cart_wrapper' ) );
		}
	}

	/**
	 * Render plain content function for the widget
	 *
	 * @access public
	 */
	public function render_plain_content() {}

}
