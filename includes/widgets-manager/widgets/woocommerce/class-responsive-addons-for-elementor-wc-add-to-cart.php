<?php
/**
 * File containing the RAE Custom Add To Cart widget class.
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Woocommerce;

use Elementor\Controls_Manager;
use Elementor\Widget_Button;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\QueryControl\Module;
use Responsive_Addons_For_Elementor\Traits\Missing_Dependency;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Class for the RAE Custom Add To Cart widget.
 */
class Responsive_Addons_For_Elementor_WC_Add_To_Cart extends Widget_Button {
	use Missing_Dependency;
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-wc-add-to-cart';
	}
	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Custom Add To Cart', 'responsive-addons-for-elementor' );
	}
	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-woocommerce rael-badge';
	}
	/**
	 * Retrieve the widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'woocommerce-elements' );
	}
	/**
	 * Retrieve the widget keywords.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'woocommerce', 'shop', 'store', 'cart', 'product', 'button', 'add to cart' );
	}
	/**
	 * Remove unnecessary settings during export.
	 *
	 * @param array $element Widget settings.
	 * @return array Widget settings.
	 */
	public function on_export( $element ) {
		unset( $element['settings']['product_id'] );

		return $element;
	}
	/**
	 * Unescape HTML.
	 *
	 * @param string $safe_text Safe text.
	 * @param string $text      Text.
	 * @return string Unescaped text.
	 */
	public function unescape_html( $safe_text, $text ) {
		return $text;
	}

	/**
	 * Register the controls for the widget.
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
			'section_product',
			array(
				'label' => __( 'Product', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'product_id',
			array(
				'label'        => __( 'Product', 'responsive-addons-for-elementor' ),
				'type'         => Module::QUERY_CONTROL_ID,
				'options'      => array(),
				'label_block'  => true,
				'autocomplete' => array(
					'object' => Module::QUERY_OBJECT_POST,
					'query'  => array(
						'post_type' => array( 'product' ),
					),
				),
				'filter_type'  => 'by_id',
			)
		);

		$this->add_control(
			'show_quantity',
			array(
				'label'       => __( 'Show Quantity', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_off'   => __( 'Hide', 'responsive-addons-for-elementor' ),
				'label_on'    => __( 'Show', 'responsive-addons-for-elementor' ),
				'description' => __( 'Please note that switching on this option will disable some of the design controls.', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'quantity',
			array(
				'label'     => __( 'Quantity', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'condition' => array(
					'show_quantity' => '',
				),
			)
		);

		$this->end_controls_section();

		parent::register_controls();

		$this->update_control(
			'link',
			array(
				'type'    => Controls_Manager::HIDDEN,
				'default' => array(
					'url' => '',
				),
			)
		);

		$this->update_control(
			'text',
			array(
				'default'     => __( 'Add to Cart', 'responsive-addons-for-elementor' ),
				'placeholder' => __( 'Add to Cart', 'responsive-addons-for-elementor' ),
			)
		);

		$this->update_control(
			'selected_icon',
			array(
				'default' => array(
					'value'   => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				),
			)
		);

		$this->update_control(
			'size',
			array(
				'condition' => array(
					'show_quantity' => '',
				),
			)
		);
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
		$settings = $this->get_settings_for_display();

		if ( ! empty( $settings['product_id'] ) ) {
			$product_id = $settings['product_id'];
		} elseif ( wp_doing_ajax() ) {
			$product_id = $_POST['post_id']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		} else {
			$product_id = get_queried_object_id();
		}

		global $product;
		$product = wc_get_product( $product_id );

		if ( 'yes' == $settings['show_quantity'] ) {
			$this->render_form_button( $product );
		} else {
			$this->render_ajax_button( $product );
		}
	}

	/**
	 * Render the AJAX button.
	 *
	 * @param \WC_Product $product Product object.
	 * @return void
	 */
	private function render_ajax_button( $product ) {
		$settings = $this->get_settings_for_display();

		if ( $product ) {
			if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
				$product_type = $product->get_type();
			} else {
				$product_type = $product->product_type;
			}

			$class = implode(
				' ',
				array_filter(
					array(
						'product_type_' . $product_type,
						$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
						$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
					)
				)
			);

			$this->add_render_attribute(
				'button',
				array(
					'rel'             => 'nofollow',
					'href'            => $product->add_to_cart_url(),
					'data-quantity'   => ( isset( $settings['quantity'] ) ? $settings['quantity'] : 1 ),
					'data-product_id' => $product->get_id(),
					'class'           => $class,
				)
			);

		} elseif ( current_user_can( 'manage_options' ) ) {
			$settings['text'] = __( 'Please set a valid product', 'responsive-addons-for-elementor' );
			$this->set_settings( $settings );
		}

		parent::render();
	}
	/**
	 * Render the form button.
	 *
	 * @param \WC_Product $product Product object.
	 * @return void
	 */
	private function render_form_button( $product ) {
		if ( ! $product && current_user_can( 'manage_options' ) ) {
			echo esc_html__( 'Please set a valid product', 'responsive-addons-for-elementor' );

			return;
		}

		$text_callback = function() {
			ob_start();
			$this->render_text();

			return ob_get_clean();
		};

		add_filter( 'woocommerce_get_stock_html', '__return_empty_string' );
		add_filter( 'woocommerce_product_single_add_to_cart_text', $text_callback );
		add_filter( 'esc_html', array( $this, 'unescape_html' ), 10, 2 );

		ob_start();
		woocommerce_template_single_add_to_cart();
		$form = ob_get_clean();
		$form = str_replace( 'single_add_to_cart_button', 'single_add_to_cart_button elementor-button', $form );
		echo esc_html( $form );

		remove_filter( 'woocommerce_product_single_add_to_cart_text', $text_callback );
		remove_filter( 'woocommerce_get_stock_html', '__return_empty_string' );
		remove_filter( 'esc_html', array( $this, 'unescape_html' ) );
	}

	/**
	 * Written as a Backbone JavaScript template and used to generate the live preview
	 * Force remote render.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	protected function content_template() {}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/elementor-widgets/docs/custom-add-to-cart';
	}
}
