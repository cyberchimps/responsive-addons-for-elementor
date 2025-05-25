<?php
/**
 * RAEL Theme Builder's Related Products Widget.
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

/**
 * RAEL Theme Product related widget class.
 */
class Responsive_Addons_For_Elementor_Theme_Product_Related extends Responsive_Addons_For_Elementor_Woo_Products_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-woocommerce-product-related';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Product Related', 'responsive-addons-for-elementor' );
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
		return 'eicon-product-related rael-badge';
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
		return array( 'rael', 'woocommerce', 'shop', 'store', 'related', 'similar', 'product' );
	}

	/**
	 * Get custom help url.
	 *
	 * @access public
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/';
	}

	/**
	 * Get_group_name function to get the group name.
	 *
	 * @access public
	 */
	public function get_group_name() {
		return 'woocommerce';
	}

	/**
	 * Register all the control settings for the product related widget
	 *
	 * @access public
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'rael_section_related_products_content',
			array(
				'label' => esc_html__( 'Related Products', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_posts_per_page',
			array(
				'label'   => esc_html__( 'Products Per Page', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'range'   => array(
					'px' => array(
						'max' => 20,
					),
				),
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
					'.woocommerce {{WRAPPER}}.elementor-wc-products .products > h2' => 'color: {{VALUE}}',
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
				'selector'  => '.woocommerce {{WRAPPER}}.elementor-wc-products .products > h2',
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
					'.woocommerce {{WRAPPER}}.elementor-wc-products .products > h2' => 'text-align: {{VALUE}}',
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
					'.woocommerce {{WRAPPER}}.elementor-wc-products .products > h2' => 'margin-bottom: {{SIZE}}{{UNIT}}',
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
		global $product;

		$product = wc_get_product();

		if ( ! $product ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		// Add a wrapper class to the Add to Cart & View Items elements if the automically_align_buttons switch has been selected.
		if ( 'yes' === $settings['rael_automatically_align_buttons'] ) {
			add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'add_to_cart_wrapper' ), 10, 1 );
		}

		$args = array(
			'rael_posts_per_page' => 4,
			'columns'             => 4,
			'orderby'             => $settings['rael_orderby'],
			'order'               => $settings['rael_order'],
		);

		if ( ! empty( $settings['rael_posts_per_page'] ) ) {
			$args['rael_posts_per_page'] = $settings['rael_posts_per_page'];
		}

		if ( ! empty( $settings['columns'] ) ) {
			$args['columns'] = $settings['columns'];
		}

		// Get visible related products then sort them at random.
		$args['related_products'] = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), $args['rael_posts_per_page'], $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );

		// Handle orderby.
		$args['related_products'] = wc_products_array_orderby( $args['related_products'], $args['orderby'], $args['order'] );

		ob_start();

		wc_get_template( 'single-product/related.php', $args );

		$related_products_html = ob_get_clean();

		if ( $related_products_html ) {
			$related_products_html = str_replace( '<ul class="products', '<ul class="products elementor-grid', $related_products_html );

			echo wp_kses_post( $related_products_html );
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
