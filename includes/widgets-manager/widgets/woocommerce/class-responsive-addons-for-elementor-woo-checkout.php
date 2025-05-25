<?php
/**
 * RAEL WooCheckout widget
 *
 *  @since      1.8.0
 * @package Responsive_Addons_For_Elementor
 * @subpackage WooCommerce
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Woocommerce;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Frontend;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Widget_Base;
use \Elementor\Icons_Manager;
use \Responsive_Addons_For_Elementor\Traits\Missing_Dependency;
use \Responsive_Addons_For_Elementor\Traits\RAEL_Products_Comparable;
use \Responsive_Addons_For_Elementor\Traits\Helper_Woo_Checkout;

/**
 * 'RAEL Woo Checkout' widget class.
 *
 * @since 1.8.0
 */
class Responsive_Addons_For_Elementor_Woo_Checkout extends Widget_Base {

	use Missing_Dependency;
	use RAEL_Products_Comparable;
	use \Responsive_Addons_For_Elementor\Traits\Woo_Checkout_Helper;
	use Helper_Woo_Checkout;

	/**
	 * Constructs a new instance of the widget.
	 *
	 * Initializes the widget with provided data and arguments, ensuring required parameters are provided.
	 *
	 * @param array $data Optional. An array of data to initialize the widget. Defaults to an empty array.
	 * @param mixed $args Optional. Additional arguments to configure the widget instance. Defaults to null.
	 *
	 * @throws \Exception If `$args` argument is null and the widget is being initialized as a full instance.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		$is_type_instance = $this->is_type_instance();

		if ( ! $is_type_instance && null === $args ) {
			throw new \Exception( '`$args` argument is required when initializing a full widget instance.' );
		}

		if ( $is_type_instance && class_exists( 'woocommerce' ) ) {

			if ( is_null( WC()->cart ) ) {
				include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
				include_once WC_ABSPATH . 'includes/class-wc-cart.php';
				wc_load_cart();
			}

			add_filter( 'body_class', array( $this, 'add_checkout_body_class' ) );
			$this->rael_woocheckout_recurring();
		}
	}
	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-woocommerce-checkout';
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
		return __( 'Woo Checkout', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Woo Checkout widget icon.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-woocommerce rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.8.0
	 *
	 * @access protected
	 */
	protected function register_controls() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			$this->register_content_tab_missing_dep_warning_controls( 'WooCommerce', 'woocommerce' );
			return;
		}
		/**
		 * General Settings
		 */
		$this->start_controls_section(
			'rael_section_woo_checkout_general_settings',
			array(
				'label' => esc_html__( 'General Settings', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_control(
			'rael_woo_checkout_layout',
			array(
				'label'       => esc_html__( 'Layout', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'default',
				'label_block' => false,
				'options'     => apply_filters(
					'rael/woo-checkout/layout',
					array(
						'default'     => esc_html__( 'Default', 'responsive-addons-for-elementor' ),
						'multi-steps' => esc_html__( 'Multi Steps', 'responsive-addons-for-elementor' ),
						'split'       => esc_html__( 'Split', 'responsive-addons-for-elementor' ),
					)
				),
			)
		);

		// Tab settings when multi or split layout is selected.

		$this->add_control(
			'rael_woo_checkout_tabs_settings',
			array(
				'label'     => __( 'Tabs Label', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_woo_checkout_layout!' => 'default',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_tab_login_text',
			array(
				'label'       => __( 'Login', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => __( 'Login', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_woo_checkout_layout!' => 'default',
				),
				'description' => 'To preview the changes in Login tab, turn on the Settings from \'Login\' section below.',
			)
		);
		$this->add_control(
			'rael_woo_checkout_tab_coupon_text',
			array(
				'label'     => __( 'Coupon', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => __( 'Coupon', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_woo_checkout_layout!' => 'default',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_tab_billing_shipping_text',
			array(
				'label'     => __( 'Billing & Shipping', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => __( 'Billing & Shipping', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_woo_checkout_layout!' => 'default',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_tab_payment_text',
			array(
				'label'     => __( 'Payment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => __( 'Payment', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_woo_checkout_layout!' => 'default',
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_tabs_btn_settings',
			array(
				'label'     => __( 'Previous/Next Label', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_woo_checkout_layout!' => 'default',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_tabs_btn_next_text',
			array(
				'label'     => __( 'Next', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => __( 'Next', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_woo_checkout_layout!' => 'default',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_tabs_btn_prev_text',
			array(
				'label'     => __( 'Previous', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => __( 'Previous', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_woo_checkout_layout!' => 'default',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Order Details Settings
		 */
		$this->start_controls_section(
			'rael_section_woo_checkout_order_review_settings',
			array(
				'label' => esc_html__( 'Order Details', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_woo_checkout_order_details_title',
			array(
				'label'   => __( 'Text', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Your Order', 'responsive-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
			// Table Header.
			$this->add_control(
				'rael_woo_checkout_table_header_text',
				array(
					'label'        => esc_html__( 'Change Labels', 'responsive-addons-for-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'default'      => 'yes',
				)
			);
			$this->add_control(
				'rael_woo_checkout_table_product_text',
				array(
					'label'     => __( 'Product Text', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::TEXT,
					'dynamic'   => array(
						'active' => true,
					),
					'default'   => __( 'Product', 'responsive-addons-for-elementor' ),
					'dynamic'   => array(
						'active' => true,
					),
					'condition' => array(
						'rael_woo_checkout_table_header_text' => 'yes',
					),
				)
			);
			$this->add_control(
				'rael_woo_checkout_table_quantity_text',
				array(
					'label'     => __( 'Quantity Text', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::TEXT,
					'dynamic'   => array(
						'active' => true,
					),
					'default'   => __( 'Quantity', 'responsive-addons-for-elementor' ),
					'dynamic'   => array(
						'active' => true,
					),
					'condition' => array(
						'rael_woo_checkout_table_header_text' => 'yes',
					),
				)
			);
			$this->add_control(
				'rael_woo_checkout_table_price_text',
				array(
					'label'     => __( 'Price Text', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::TEXT,
					'dynamic'   => array(
						'active' => true,
					),
					'default'   => __( 'Price', 'responsive-addons-for-elementor' ),
					'dynamic'   => array(
						'active' => true,
					),
					'condition' => array(
						'rael_woo_checkout_table_header_text' => 'yes',
					),
				)
			);
			$this->add_control(
				'rael_woo_checkout_table_subtotal_text',
				array(
					'label'     => __( 'Subtotal Text', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => __( 'Subtotal', 'responsive-addons-for-elementor' ),
					'dynamic'   => array(
						'active' => true,
					),
					'condition' => array(
						'rael_woo_checkout_table_header_text' => 'yes',
					),
				)
			);
			$this->add_control(
				'rael_woo_checkout_table_shipping_text',
				array(
					'label'     => __( 'Shipping Text', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => __( 'Shipping', 'responsive-addons-for-elementor' ),
					'dynamic'   => array(
						'active' => true,
					),
					'condition' => array(
						'rael_woo_checkout_table_header_text' => 'yes',
					),
				)
			);
			$this->add_control(
				'rael_woo_checkout_table_total_text',
				array(
					'label'     => __( 'Total Text', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => __( 'Total', 'responsive-addons-for-elementor' ),
					'dynamic'   => array(
						'active' => true,
					),
					'condition' => array(
						'rael_woo_checkout_table_header_text' => 'yes',
					),
				)
			);
			// Shop Link.
		$this->add_control(
			'rael_woo_checkout_shop_link',
			array(
				'label'        => esc_html__( 'Shop Link', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);
		$this->add_control(
			'rael_woo_checkout_shop_link_text',
			array(
				'label'     => __( 'Link Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => __( 'Continue Shopping', 'responsive-addons-for-elementor' ),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'rael_woo_checkout_shop_link' => 'yes',
				),
			)
		);

		$this->end_controls_section();
		/**
		 * -------------------------------------------
		 * Coupon Settings
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'rael_section_woo_checkout_settings',
			array(
				'label' => esc_html__( 'Coupon', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_control(
			'rael_woo_checkout_coupon_hide',
			array(
				'label'        => esc_html__( 'Hide', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);
		$this->add_control(
			'rael_woo_checkout_coupon_icon',
			array(
				'label'   => __( 'Icon', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-percent',
					'library' => 'fa-solid',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_coupon_title',
			array(
				'label'   => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Have a coupon?', 'responsive-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_coupon_link_text',
			array(
				'label'   => __( 'Link Text', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Click here to enter your code', 'responsive-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_coupon_form_content',
			array(
				'label'   => __( 'Form Content', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'If you have a coupon code, please apply it below.', 'responsive-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_coupon_placeholder_text',
			array(
				'label'   => __( 'Placeholder Text', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Coupon code', 'responsive-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_coupon_button_text',
			array(
				'label'   => __( 'Button Text', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Apply Coupon', 'responsive-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$this->end_controls_section();
		/**
		 * -------------------------------------------
		 * Login Settings
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'rael_section_woo_login_settings',
			array(
				'label' => esc_html__( 'Login', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_control(
			'rael_section_woo_login_show',
			array(
				'label'        => __( 'Show Preview of Login', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'description'  => 'You can force show login in order to style them properly.',
			)
		);
		if ( 'yes' !== get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
			$this->add_control(
				'rael_section_woo_login_show_warning_text',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf(
						/* translators: 1: WooCommerce settings URL */
						esc_html__(
							'Allow customers to log into an existing account during checkout is disabled on your site. Please enable it to use the login form. You can enable it from WooCommerce >> Settings >> Accounts & Privacy >> %1$sGuest checkout.%2$s',
							'responsive-addons-for-elementor'
						),
						'<a target="_blank" href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=account' ) ) . '">',
						'</a>'
					),
					'content_classes' => 'rael-warning',
					'condition'       => array(
						'rael_section_woo_login_show' => 'yes',
					),
				)
			);
		}

		$this->add_control(
			'rael_woo_checkout_login_icon',
			array(
				'label'   => __( 'Icon', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-user',
					'library' => 'fa-solid',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_login_title',
			array(
				'label'   => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Returning customer?', 'responsive-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_login_message',
			array(
				'label'   => __( 'Message', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'responsive-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_login_link_text',
			array(
				'label'   => __( 'Link Text', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Click here to login', 'responsive-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Customer Details Settings
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'rael_section_woo_customer_details_settings',
			array(
				'label' => esc_html__( 'Customer Details', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_control(
			'rael_woo_checkout_billing_title',
			array(
				'label'   => __( 'Billing Title', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Billing Details', 'responsive-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_shipping_title',
			array(
				'label'   => __( 'Shipping Title', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Ship to a different address?', 'responsive-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_additional_info_title',
			array(
				'label'   => __( 'Additional Info Title', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Additional Information', 'responsive-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Payment Settings
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'rael_section_woo_payment_settings',
			array(
				'label' => esc_html__( 'Payment', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_control(
			'rael_woo_checkout_payment_title',
			array(
				'label'   => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Payment Methods', 'responsive-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_place_order_text',
			array(
				'label'   => __( 'Button text', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Place Order', 'responsive-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$this->end_controls_section();

		// tab styles settings for split and multi layout.

		$this->start_controls_section(
			'rael_section_woo_checkout_tabs_styles',
			array(
				'label'     => esc_html__( 'Tabs', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_woo_checkout_layout!' => 'default',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'rael_section_woo_checkout_tabs_typo',
				'selector'       => '{{WRAPPER}} .rael-woo-checkout.layout-split .layout-split-container .info-area .split-tabs li, {{WRAPPER}} .rael-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs .ms-tab',
				'fields_options' => array(
					'font_size' => array(
						'default' => array(
							'unit' => 'px',
							'size' => 16,
						),
					),
				),
			)
		);

		$this->start_controls_tabs( 'rael_woo_checkout_tabs_tabs' );
		$this->start_controls_tab( 'rael_woo_checkout_tabs_tab_normal', array( 'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'rael_woo_checkout_tabs_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f4f6fc',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout.layout-split .layout-split-container .info-area .split-tabs' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_woo_checkout_layout' => 'split',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_tabs_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2d2c52',
				'selectors' => array(
					'{{WRAPPER}} .split-tabs li, {{WRAPPER}} .ms-tabs li' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'rael_woo_checkout_tabs_tab_active', array( 'label' => esc_html__( 'Active', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'rael_woo_checkout_tabs_bg_color_active',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff793f',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout.layout-split .layout-split-container .info-area .split-tabs li.active' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_woo_checkout_layout' => 'split',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_tabs_color_active',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2d2c52',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout.layout-split .layout-split-container .info-area .split-tabs li.active' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_woo_checkout_layout' => 'split',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_tabs_ms_color_active',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff793f',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li.completed' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_woo_checkout_layout' => 'multi-steps',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'rael_woo_checkout_tabs_box_shadow',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .rael-woo-checkout.layout-split .layout-split-container .info-area .split-tabs li.active',
				'condition' => array(
					'rael_woo_checkout_layout' => 'split',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_woo_checkout_tabs_border_radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 05,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .split-tabs, {{WRAPPER}} .split-tab li.active' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_woo_checkout_layout' => 'split',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_tabs_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '17',
					'right'    => '17',
					'bottom'   => '17',
					'left'     => '17',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-woo-checkout.layout-split .layout-split-container .info-area .split-tabs li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_woo_checkout_layout' => 'split',
				),
			)
		);

		$this->add_responsive_control(
			'rael_woo_checkout_tabs_bottom_gap',
			array(
				'label'     => esc_html__( 'Bottom Gap', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'default'   => array(
					'unit' => 'px',
					'size' => 30,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs' => 'margin: 0 0 {{SIZE}}{{UNIT}} 0;',
				),
				'condition' => array(
					'rael_woo_checkout_layout' => 'multi-steps',
				),
			)
		);
		// multi steps.
		$this->add_control(
			'rael_woo_checkout_tabs_steps',
			array(
				'label'     => __( 'Steps', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_woo_checkout_layout' => 'multi-steps',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'rael_section_woo_checkout_tabs_steps_typo',
				'selector'       => '{{WRAPPER}} .rael-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li:before',
				'fields_options' => array(
					'font_size' => array(
						'default' => array(
							'unit' => 'px',
							'size' => 12,
						),
					),
				),
				'condition'      => array(
					'rael_woo_checkout_layout' => 'multi-steps',
				),
			)
		);
		$this->start_controls_tabs(
			'rael_woo_checkout_tabs_steps_tabs',
			array(
				'condition' => array(
					'rael_woo_checkout_layout' => 'multi-steps',
				),
			)
		);
		$this->start_controls_tab( 'rael_woo_checkout_tabs_steps_tab_normal', array( 'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ) ) );
		$this->add_control(
			'rael_woo_checkout_tabs_steps_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2d2c52',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li:before' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_tabs_steps_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li:before' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_woo_checkout_tabs_steps_border',
				'selector' => '{{WRAPPER}} .rael-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li:before',
			)
		);
		$this->add_control(
			'rael_woo_checkout_tabs_steps_connector_color',
			array(
				'label'     => esc_html__( 'Connector Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2d2c52',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li:after' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab( 'rael_woo_checkout_tabs_steps_tab_active', array( 'label' => esc_html__( 'Active', 'responsive-addons-for-elementor' ) ) );
		$this->add_control(
			'rael_woo_checkout_tabs_steps_bg_color_active',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FF793F',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li.completed:before' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_tabs_steps_color_active',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li.completed:before' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_tabs_steps_connector_color_active',
			array(
				'label'     => esc_html__( 'Connector Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff793f',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li.completed:after' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_responsive_control(
			'rael_woo_checkout_tabs_steps_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 25,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li:before' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li:after' => 'top: calc(({{SIZE}}{{UNIT}}/2) - 2px);',
				),
				'condition' => array(
					'rael_woo_checkout_layout' => 'multi-steps',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_tabs_steps_border_radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 50,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs li:before' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_woo_checkout_layout' => 'multi-steps',
				),
			)
		);

		$this->end_controls_section();

		// woo checkout section styles.
		$this->start_controls_section(
			'rael_section_woo_checkout_section_styles',
			array(
				'label'     => esc_html__( 'Section', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_woo_checkout_layout' => 'multi-steps',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_section_bg_color',
			array(
				'label'     => esc_html__( 'Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .ms-tabs-content' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_woo_checkout_section_border_radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 05,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .ms-tabs-content' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_section_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '25',
					'right'    => '25',
					'bottom'   => '25',
					'left'     => '25',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .ms-tabs-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'rael_woo_checkout_section_box_shadow',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .rael-woo-checkout.layout-multi-steps .layout-multi-steps-container .ms-tabs-content-wrap .ms-tabs-content',
			)
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style Section title
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'rael_section_woo_checkout_section_title',
			array(
				'label' => esc_html__( 'Section Title', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_woo_checkout_section_title_typography',
				'selector' => '{{WRAPPER}} h3, {{WRAPPER}} #ship-to-different-address span, {{WRAPPER}} .rael-woo-checkout #customer_details h3',
			)
		);
		$this->add_control(
			'rael_woo_checkout_section_title_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#404040',
				'selectors' => array(
					'{{WRAPPER}} h3, {{WRAPPER}} .woo-checkout-section-title, {{WRAPPER}} #ship-to-different-address span' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_section_bottom_gap',
			array(
				'label'     => esc_html__( 'Bottom Gap', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'default'   => array(
					'unit' => 'px',
					'size' => 20,
				),
				'selectors' => array(
					'{{WRAPPER}} h3, {{WRAPPER}} .woo-checkout-section-title, {{WRAPPER}} .rael-woo-checkout #customer_details h3' => 'margin-bottom: {{SIZE}}{{UNIT}}!important;',
				),
			)
		);

		$this->end_controls_section();
		/**
		 * -------------------------------------------
		 * Tab Style Order Details Style
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'rael_section_woo_checkout_order_review_style',
			array(
				'label' => esc_html__( 'Order Details', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'rael_woo_checkout_order_review_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2D2C52',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout-order-review' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_order_review_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '20',
					'right'    => '20',
					'bottom'   => '20',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-woo-checkout-order-review' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_order_review_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '5',
					'right'    => '5',
					'bottom'   => '5',
					'left'     => '5',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-woo-checkout-order-review' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_order_review_head',
			array(
				'label'     => __( 'Table Head', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'!rael_woo_checkout_layout' => 'default',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_woo_checkout_order_review_header_typo',
				'selector'  => '{{WRAPPER}} .rael-woo-checkout-order-review .table-header',
				'condition' => array(
					'!rael_woo_checkout_layout' => 'default',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_order_review_header_color',
			array(
				'label'     => esc_html__( 'Header Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout-order-review .table-header' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'!rael_woo_checkout_layout' => 'default',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_order_review_header_top_spacing',
			array(
				'label'     => __( 'Top Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 12,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce .rael-woo-checkout-order-review .rael-order-review-table li.table-header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_woo_checkout_layout' => 'default',
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_order_review_body',
			array(
				'label'     => __( 'Table Body', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'rael_woo_checkout_order_review_row_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout-order-review .table-row' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_woo_checkout_layout' => 'default',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_order_review_row_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#404040',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout-order-review .table-row' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_woo_checkout_layout' => 'default',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_order_review_row_color_pro',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout-order-review .table-row' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_woo_checkout_layout!' => 'default',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_order_review_row_border_color_pro',
			array(
				'label'     => esc_html__( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ab93f5',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout.layout-split .layout-split-container .table-area .rael-woo-checkout-order-review .rael-order-review-table .table-row, {{WRAPPER}} .rael-woo-checkout.layout-multi-steps .layout-multi-steps-container .table-area .rael-woo-checkout-order-review .rael-order-review-table .table-row, {{WRAPPER}} .rael-woo-checkout.layout-split .layout-split-container .table-area .rael-woo-checkout-order-review .rael-order-review-table-footer .footer-content .order-total, {{WRAPPER}} .rael-woo-checkout.layout-multi-steps .layout-multi-steps-container .table-area .rael-woo-checkout-order-review .rael-order-review-table-footer .footer-content .order-total' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .rael-woo-checkout .table-area .rael-woo-checkout-order-review .rael-order-review-table-footer .footer-content .recurring-wrapper td, {{WRAPPER}} .rael-woo-checkout .table-area .rael-woo-checkout-order-review .rael-order-review-table-footer .footer-content .recurring-wrapper th' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_woo_checkout_layout!' => 'default',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_woo_checkout_order_review_row_typography',
				'selector' => '{{WRAPPER}} .rael-woo-checkout-order-review .table-row',
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_order_review_row_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '5',
					'right'    => '5',
					'bottom'   => '5',
					'left'     => '5',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-woo-checkout-order-review .table-row, {{WRAPPER}} .rael-woo-checkout-order-review .product-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_woo_checkout_layout' => 'default',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_order_review_row_gap',
			array(
				'label'     => __( 'Row Gap', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce .rael-woo-checkout-order-review .rael-order-review-table li.table-row' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-woo-checkout.layout-split .layout-split-container .table-area .rael-woo-checkout-order-review .rael-order-review-table .table-row' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_order_review_footer',
			array(
				'label'     => __( 'Table Footer', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_woo_checkout_order_review_footer_typography',
				'selector' => '{{WRAPPER}} .rael-woo-checkout-order-review .footer-content, {{WRAPPER}} .rael-woo-checkout-order-review .footer-content table th, {{WRAPPER}} .rael-woo-checkout-order-review .footer-content table td .amount',
			)
		);
		$this->add_control(
			'rael_woo_checkout_order_review_footer_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout-order-review .footer-content' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_woo_checkout_layout' => 'default',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_order_review_footer_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#404040',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout-order-review .footer-content' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_woo_checkout_layout' => 'default',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_order_review_footer_color_pro',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout-order-review .footer-content' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_woo_checkout_layout!' => 'default',
				),
			)
		);
		$this->start_controls_tabs(
			'rael_woo_checkout_order_review_footer_link_color_tabs',
			array(
				'condition' => array(
					'rael_woo_checkout_layout' => 'default',
				),
			)
		);

		$this->start_controls_tab( 'rael_woo_checkout_order_review_footer_link_color_tab_normal', array( 'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'rael_woo_checkout_order_review_footer_link_color',
			array(
				'label'     => esc_html__( 'Link Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#443e6d',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout-order-review .footer-content a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'rael_woo_checkout_order_review_footer_link_color_tab_hover', array( 'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'rael_woo_checkout_order_review_footer_link_color_hover',
			array(
				'label'     => esc_html__( 'Link Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout-order-review .footer-content a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Not default.
		$this->start_controls_tabs(
			'rael_woo_checkout_order_review_footer_link_color_tabs_pro',
			array(
				'condition' => array(
					'rael_woo_checkout_layout!' => 'default',
				),
			)
		);

		$this->start_controls_tab(
			'rael_woo_checkout_order_review_footer_link_color_tab_normal_pro',
			array(
				'label' =>
											esc_html__( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_woo_checkout_order_review_footer_link_color_pro',
			array(
				'label'     => esc_html__( 'Link Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f1ecff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout-order-review .footer-content a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_woo_checkout_order_review_footer_link_color_tab_hover_pro',
			array(
				'label' =>
											esc_html__( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_woo_checkout_order_review_footer_link_color_hover_pro',
			array(
				'label'     => esc_html__( 'Link Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout-order-review .footer-content a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'rael_woo_checkout_order_review_footer_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#404040',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout-order-review .footer-content > div' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_order_review_footer_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '5',
					'right'    => '5',
					'bottom'   => '5',
					'left'     => '5',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-woo-checkout-order-review .footer-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_woo_checkout_layout' => 'default',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_order_review_footer_top_spacing',
			array(
				'label'     => __( 'Top Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce .rael-woo-checkout-order-review .rael-order-review-table-footer' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_order_review_total',
			array(
				'label'     => __( 'Total', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_woo_checkout_layout!' => 'default',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_woo_checkout_order_review_total_typo',
				'selector'  => '{{WRAPPER}} .rael-woo-checkout.layout-split .layout-split-container .table-area .rael-woo-checkout-order-review .rael-order-review-table-footer .footer-content .order-total, {{WRAPPER}} .rael-woo-checkout.layout-multi-steps .layout-multi-steps-container .table-area .rael-woo-checkout-order-review .rael-order-review-table-footer .footer-content .order-total, {{WRAPPER}} .rael-woo-checkout .table-area .rael-woo-checkout-order-review .rael-order-review-table-footer .footer-content table th, {{WRAPPER}} .rael-woo-checkout .table-area .rael-woo-checkout-order-review .rael-order-review-table-footer .footer-content table td .amount',
				'condition' => array(
					'rael_woo_checkout_layout!' => 'default',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_order_review_total_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout-order-review .footer-content .order-total, {{WRAPPER}} .rael-woo-checkout-order-review .footer-content th, {{WRAPPER}} .rael-woo-checkout-order-review .footer-content td' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_woo_checkout_layout!' => 'default',
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_order_review_shop_link',
			array(
				'label'     => __( 'Shop Link', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_woo_checkout_shop_link' => 'yes',
				),
			)
		);

		$this->start_controls_tabs(
			'rael_woo_checkout_shop_link_color_tabs',
			array(
				'condition' => array(
					'rael_woo_checkout_shop_link' => 'yes',
				),

			)
		);

		$this->start_controls_tab( 'rael_woo_checkout_shop_link_color_tab_normal', array( 'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'rael_woo_checkout_shop_link_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout-order-review .back-to-shopping' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'rael_woo_checkout_shop_link_color_tab_hover', array( 'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'rael_woo_checkout_shop_link_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7866ff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout-order-review .back-to-shopping:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_woo_checkout_shop_link_top_spacing',
			array(
				'label'     => __( 'Top Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 30,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout-order-review .back-to-shopping' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_woo_checkout_layout!' => 'default',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style Login
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'rael_section_woo_checkout_login_style',
			array(
				'label' => esc_html__( 'Login', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_woo_checkout_login_typo',
				'selector' => '{{WRAPPER}} .rael-woo-checkout .woo-checkout-login',
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_woo_checkout_login_bg_color',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-woo-checkout .woo-checkout-login',
			)
		);
		$this->add_control(
			'rael_woo_checkout_login_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#404040',
				'selectors' => array(
					'{{WRAPPER}} .woo-checkout-login, {{WRAPPER}} .woo-checkout-login .woocommerce-form-login-toggle .woocommerce-info' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_login_icon_color',
			array(
				'label'     => __( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#404040',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .rael-login-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-woo-checkout .rael-login-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_login_links_color',
			array(
				'label'     => __( 'Links Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7866ff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-login a' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_login_links_color_hover',
			array(
				'label'     => __( 'Links Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7866ff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-login a:hover' => 'color: {{VALUE}}!important;',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_login_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '20',
					'right'    => '20',
					'bottom'   => '20',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woo-checkout-login' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rael-woo-checkout .rael-login-icon' => 'top: {{TOP}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_login_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '5',
					'right'    => '5',
					'bottom'   => '5',
					'left'     => '5',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woo-checkout-login' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_login_form',
			array(
				'label'     => __( 'Form', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_login_form_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '20',
					'right'    => '20',
					'bottom'   => '20',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'.rael-woo-checkout {{WRAPPER}} .woocommerce form.woocommerce-form-login' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_login_form_margin',
			array(
				'label'      => esc_html__( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '20',
					'right'    => '20',
					'bottom'   => '20',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'.rael-woo-checkout {{WRAPPER}} .woocommerce form.woocommerce-form-login' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_woo_checkout_login_form_border_color',
				'selector' => '.rael-woo-checkout {{WRAPPER}} .woocommerce form.woocommerce-form-login',
			)
		);
		$this->add_control(
			'rael_woo_checkout_login_form_label',
			array(
				'label'     => __( 'Form Label', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_woo_checkout_login_form_label_typo',
				'selector' => '{{WRAPPER}} .rael-woo-checkout .woo-checkout-login label',
			)
		);
		$this->add_control(
			'rael_woo_checkout_login_form_label_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#404040',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woo-checkout-login label' => 'color: {{VALUE}};',
				),
			)
		);

		// Login Btn.
		$this->add_control(
			'rael_woo_checkout_login_btn',
			array(
				'label'     => __( 'Button', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_woo_checkout_login_btn_typo',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-login .button',
			)
		);

		$this->start_controls_tabs( 'rael_woo_checkout_login_btn_tabs' );
		$this->start_controls_tab(
			'rael_woo_checkout_login_btn_tab_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_woo_checkout_login_btn_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7866ff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-login .button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_login_btn_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-login .button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_woo_checkout_login_btn_border',
				'selector' => '{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-login .button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_woo_checkout_login_btn_tab_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_woo_checkout_login_btn_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7866ff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-login .button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_login_btn_color_hover',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-login .button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_login_btn_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-login .button:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_woo_checkout_login_btn_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'rael_woo_checkout_login_btn_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => '5',
					'right'    => '5',
					'bottom'   => '5',
					'left'     => '5',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-login .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_woo_checkout_login_btn_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-login .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_woo_checkout_login_btn_box_shadow',
				'selector' => '{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-login .button',
			)
		);
		$this->end_controls_section();
		/**
		 * -------------------------------------------
		 * Tab Style Coupon
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'rael_section_woo_checkout_coupon_style',
			array(
				'label' => esc_html__( 'Coupon', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_woo_checkout_coupon_typo',
				'selector' => '{{WRAPPER}} .rael-woo-checkout .woo-checkout-coupon .woocommerce-form-coupon-toggle .woocommerce-info,{{WRAPPER}} .rael-woo-checkout .woo-checkout-coupon .woocommerce-form-coupon-toggle .woocommerce-info a.showcoupon',
			)
		);
		$this->add_control(
			'rael_woo_checkout_coupon_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff793f',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woo-checkout-coupon' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_coupon_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#404040',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woo-checkout-coupon, {{WRAPPER}} .rael-woo-checkout .woo-checkout-coupon .woocommerce-form-coupon-toggle .woocommerce-info' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_coupon_icon_color',
			array(
				'label'     => __( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#404040',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .rael-coupon-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-woo-checkout .rael-coupon-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_coupon_links_color',
			array(
				'label'     => __( 'Links Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7866ff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce-form-coupon-toggle .woocommerce-info a' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_coupon_links_color_hover',
			array(
				'label'     => __( 'Links Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7866ff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce-form-coupon-toggle .woocommerce-info a:hover' => 'color: {{VALUE}}!important;',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'rael_woo_checkout_coupon_border',
				'label'       => __( 'Border', 'responsive-addons-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .rael-woo-checkout .woo-checkout-coupon',
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_coupon_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '5',
					'right'    => '5',
					'bottom'   => '5',
					'left'     => '5',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woo-checkout-coupon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'rael_woo_checkout_coupon_box_shadow',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .rael-woo-checkout .woo-checkout-coupon',
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_coupon_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '20',
					'right'    => '20',
					'bottom'   => '20',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woo-checkout-coupon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rael-woo-checkout .rael-coupon-icon' => 'top: {{TOP}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_coupon_form',
			array(
				'label'     => __( 'Form', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_coupon_form_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '20',
					'right'    => '20',
					'bottom'   => '20',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'.rael-woo-checkout {{WRAPPER}} .woocommerce form.checkout_coupon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_coupon_form_margin',
			array(
				'label'      => esc_html__( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '20',
					'right'    => '20',
					'bottom'   => '20',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'.rael-woo-checkout {{WRAPPER}} .woocommerce form.checkout_coupon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_woo_checkout_coupon_form_border_color',
				'selector' => '.rael-woo-checkout {{WRAPPER}} .woocommerce form.checkout_coupon',
			)
		);

		// Coupon Btn.
		$this->add_control(
			'rael_woo_checkout_coupon_btn',
			array(
				'label'     => __( 'Button', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_woo_checkout_coupon_btn_typo',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-coupon .button',
			)
		);

		$this->start_controls_tabs( 'rael_woo_checkout_coupon_btn_tabs' );
		$this->start_controls_tab(
			'rael_woo_checkout_coupon_btn_tab_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_woo_checkout_coupon_btn_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7866ff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-coupon .button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_coupon_btn_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-coupon .button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_woo_checkout_coupon_btn_border',
				'selector' => '{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-coupon .button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_woo_checkout_coupon_btn_tab_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_woo_checkout_coupon_btn_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7866ff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-coupon .button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_coupon_btn_color_hover',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-coupon .button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_coupon_btn_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-coupon .button:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_woo_checkout_coupon_btn_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'rael_woo_checkout_coupon_btn_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => '5',
					'right'    => '5',
					'bottom'   => '5',
					'left'     => '5',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-coupon .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_woo_checkout_coupon_btn_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-coupon .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_woo_checkout_coupon_btn_box_shadow',
				'selector' => '{{WRAPPER}} .rael-woo-checkout .woocommerce .woo-checkout-coupon .button',
			)
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style Notices
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'rael_section_woo_checkout_notices_style',
			array(
				'label' => esc_html__( 'Notices', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_section_woo_checkout_notices_typo',
				'selector' => '{{WRAPPER}} .rael-woo-checkout .woocommerce-error, {{WRAPPER}} .rael-woo-checkout .woocommerce-info, {{WRAPPER}} .rael-woo-checkout .woocommerce-message',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'rael_woo_checkout_notices_border',
				'label'       => __( 'Border', 'responsive-addons-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .rael-woo-checkout .woocommerce-error',
			)
		);

		$this->start_controls_tabs( 'rael_woo_checkout_notices_style_tabs' );

		$this->start_controls_tab(
			'rael_woo_checkout_notices_style_tab_info',
			array(
				'label' => esc_html__(
					'Info',
					'responsive-addons-for-elementor'
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_notices_info_bg_color',
			array(
				'label'     => esc_html__( 'Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#d1ecf1',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce-info' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_notices_info_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0c5460',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce-info, {{WRAPPER}} .woo-checkout-coupon .woocommerce-info, {{WRAPPER}} .woo-checkout-login .woocommerce-info' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_notices_info_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0c5460',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce-info, {{WRAPPER}} .woo-checkout-coupon .woocommerce-info, {{WRAPPER}} .woo-checkout-login .woocommerce-info' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_woo_checkout_notices_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_woo_checkout_notices_style_tab_error',
			array(
				'label' => esc_html__(
					'Error',
					'responsive-addons-for-elementor'
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_notices_error_bg_color',
			array(
				'label'     => esc_html__( 'Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFF3F5',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce-error' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_notices_error_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FF7E93',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce-error, {{WRAPPER}} .woo-checkout-coupon .woocommerce-error, {{WRAPPER}} .woo-checkout-login .woocommerce-error, {{WRAPPER}} .woocommerce-NoticeGroup .woocommerce-error' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_notices_error_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FF7E93',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce-error, {{WRAPPER}} .woo-checkout-coupon .woocommerce-error, {{WRAPPER}} .woo-checkout-login .woocommerce-error' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_woo_checkout_notices_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_woo_checkout_notices_style_tab_message',
			array(
				'label' => esc_html__(
					'Message',
					'responsive-addons-for-elementor'
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_notices_message_bg_color',
			array(
				'label'     => esc_html__( 'Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#d4edda',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce-message' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_notices_message_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#155724',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce-message, {{WRAPPER}} .woo-checkout-coupon .woocommerce-message, {{WRAPPER}} .woo-checkout-login .woocommerce-message' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_notices_message_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#155724',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce-message, {{WRAPPER}} .woo-checkout-coupon .woocommerce-message, {{WRAPPER}} .woo-checkout-login .woocommerce-message' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_woo_checkout_notices_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_woo_checkout_notices_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '5',
					'right'    => '5',
					'bottom'   => '5',
					'left'     => '5',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce-error, {{WRAPPER}} .rael-woo-checkout .woocommerce-info, {{WRAPPER}} .rael-woo-checkout .woocommerce-message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'rael_woo_checkout_notices_box_shadow',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .rael-woo-checkout .woocommerce-error, {{WRAPPER}} .rael-woo-checkout .woocommerce-info, {{WRAPPER}} .rael-woo-checkout .woocommerce-message',
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_notices_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '20',
					'right'    => '20',
					'bottom'   => '20',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce-notices-wrapper .woocommerce-error, {{WRAPPER}} .rael-woo-checkout .woocommerce-info, {{WRAPPER}} .rael-woo-checkout .woocommerce-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} calc({{LEFT}}{{UNIT}} + 30px);',
					'{{WRAPPER}} .rael-woo-checkout .woocommerce-error::before, , {{WRAPPER}} .rael-woo-checkout .woocommerce-info::before, {{WRAPPER}} .rael-woo-checkout .woocommerce-message::before' => 'top: {{TOP}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style Customer Details
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'rael_section_woo_checkout_customer_details',
			array(
				'label' => esc_html__( 'Customer Details', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'rael_woo_checkout_customer_details_label',
			array(
				'label' => __( 'Label', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_woo_checkout_customer_details_label_typography',
				'selector' => '{{WRAPPER}} #customer_details label',
			)
		);
		$this->add_control(
			'rael_woo_checkout_customer_details_label_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#443e6d',
				'selectors' => array(
					'{{WRAPPER}} #customer_details label' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_customer_details_label_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '5',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} #customer_details label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_customer_details_field_required',
			array(
				'label'     => __( 'Required (*)', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'rael_woo_checkout_customer_details_required_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff0000',
				'selectors' => array(
					'{{WRAPPER}} #customer_details label .required' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_customer_details_fields',
			array(
				'label'     => __( 'Fields', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'inputs_height',
			array(
				'label'     => __( 'Input Height', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 50,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .woocommerce .woocommerce-checkout .form-row input.input-text, {{WRAPPER}} .rael-woo-checkout .woocommerce .woocommerce-checkout .form-row select, .rael-woo-checkout {{WRAPPER}} .rael-woo-checkout .select2-container .select2-selection--single'
					=> 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_customer_details_field_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#443e6d',
				'selectors' => array(
					'{{WRAPPER}} #customer_details input, {{WRAPPER}} #customer_details select, {{WRAPPER}} #customer_details textarea' => 'color: {{VALUE}};',
				),
			)
		);
		$this->start_controls_tabs( 'rael_woo_checkout_customer_details_field_tabs' );

		$this->start_controls_tab( 'rael_woo_checkout_customer_details_field_tab_normal', array( 'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'rael_woo_checkout_customer_details_field_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#cccccc',
				'selectors' => array(
					'{{WRAPPER}} #customer_details input, {{WRAPPER}} #customer_details .select, {{WRAPPER}} #customer_details .select2-container--default .select2-selection--single, {{WRAPPER}} #customer_details textarea' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'rael_woo_checkout_customer_details_field_tab_normal_hover', array( 'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'rael_woo_checkout_customer_details_field_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7866ff',
				'selectors' => array(
					'{{WRAPPER}} #customer_details input:hover, {{WRAPPER}} #customer_details input:focus, {{WRAPPER}} #customer_details input:active' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} #customer_details textarea:hover, {{WRAPPER}} #customer_details textarea:focus, {{WRAPPER}} #customer_details textarea:active' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_woo_checkout_customer_details_field_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '5',
					'right'    => '5',
					'bottom'   => '5',
					'left'     => '5',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} #customer_details input, {{WRAPPER}} #customer_details select, {{WRAPPER}} #customer_details .select2-container--default .select2-selection--single, {{WRAPPER}} #customer_details textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_customer_details_field_spacing',
			array(
				'label'     => __( 'Bottom Spacing (PX)', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 5,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} #customer_details .form-row' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		if ( true ) {
			$this->start_controls_section(
				'rael_section_woo_checkout_pickup_point_style',
				array(
					'label' => esc_html__( 'Pickup Point', 'responsive-addons-for-elementor' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_control(
				'rael_woo_checkout_pickup_point_title_color',
				array(
					'label'     => esc_html__( 'Title Color', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#ffffff',
					'selectors' => array(
						'.rael-woo-checkout {{WRAPPER}} .woo-checkout-payment .carrier-agents-postcode-search #carrier-agent-heading' => 'color: {{VALUE}};',
					),
				)
			);

			$this->start_controls_tabs( 'rael_woo_checkout_pickup_point_tabs' );
			$this->start_controls_tab( 'rael_woo_checkout_pickup_point_tab_normal', array( 'label' => __( 'Normal', 'responsive-addons-for-elementor' ) ) );

			$this->add_control(
				'rael_woo_checkout_pickup_point_btn_bg_color',
				array(
					'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#7866ff',
					'selectors' => array(
						'{{WRAPPER}} .woo-checkout-payment .carrier-agents-postcode-search .woo-carrier-agents-postcode-input-wrapper #woo-carrier-agents-search-button' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'rael_woo_checkout_pickup_point_btn_color',
				array(
					'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#ffffff',
					'selectors' => array(
						'{{WRAPPER}} .woo-checkout-payment .carrier-agents-postcode-search .woo-carrier-agents-postcode-input-wrapper #woo-carrier-agents-search-button' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'rael_woo_checkout_pickup_point_btn_border',
					'selector' => '{{WRAPPER}} .woo-checkout-payment .carrier-agents-postcode-search .woo-carrier-agents-postcode-input-wrapper #woo-carrier-agents-search-button',
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab( 'rael_woo_checkout_pickup_point_tab_hover', array( 'label' => __( 'Hover', 'responsive-addons-for-elementor' ) ) );

			$this->add_control(
				'rael_woo_checkout_pickup_point_btn_bg_color_hover',
				array(
					'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#7866ff',
					'selectors' => array(
						'{{WRAPPER}} .woo-checkout-payment .carrier-agents-postcode-search .woo-carrier-agents-postcode-input-wrapper #woo-carrier-agents-search-button:hover' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'rael_woo_checkout_pickup_point_btn_color_hover',
				array(
					'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#ffffff',
					'selectors' => array(
						'{{WRAPPER}} .woo-checkout-payment .carrier-agents-postcode-search .woo-carrier-agents-postcode-input-wrapper #woo-carrier-agents-search-button:hover' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'rael_woo_checkout_pickup_point_btn_border_color_hover',
				array(
					'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .woo-checkout-payment .carrier-agents-postcode-search .woo-carrier-agents-postcode-input-wrapper #woo-carrier-agents-search-button:hover' => 'border-color: {{VALUE}};',
					),
					'condition' => array(
						'rael_woo_checkout_pickup_point_btn_border_border!' => '',
					),
				)
			);

			$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->end_controls_section();
		}

		/**
		 * -------------------------------------------
		 * Tab Style Payment
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'rael_section_woo_checkout_payment_style',
			array(
				'label' => esc_html__( 'Payment', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'rael_woo_checkout_payment_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2D2C52',
				'selectors' => array(
					'.rael-woo-checkout {{WRAPPER}} .woo-checkout-payment, {{WRAPPER}} #payment' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_payment_title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'.rael-woo-checkout {{WRAPPER}} .woo-checkout-payment .woo-checkout-section-title' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_payment_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '20',
					'right'    => '20',
					'bottom'   => '20',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'.rael-woo-checkout {{WRAPPER}} .woo-checkout-payment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_payment_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '5',
					'right'    => '5',
					'bottom'   => '5',
					'left'     => '5',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'.rael-woo-checkout {{WRAPPER}} .woo-checkout-payment' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Label.
		$this->add_control(
			'rael_woo_checkout_payment_label',
			array(
				'label'     => __( 'Label', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_woo_checkout_payment_label_typography',
				'selector' => '.rael-woo-checkout {{WRAPPER}} .woocommerce .woo-checkout-payment #payment .payment_methods .wc_payment_method > label',
			)
		);

		$this->start_controls_tabs( 'rael_woo_checkout_payment_label_tabs' );
		$this->start_controls_tab(
			'rael_woo_checkout_payment_label_tab_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_woo_checkout_payment_label_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#b8b6ca',
				'selectors' => array(
					'.rael-woo-checkout {{WRAPPER}} .woocommerce .woo-checkout-payment #payment .payment_methods .wc_payment_method input[type="radio"] + label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_woo_checkout_payment_label_tab_hover',
			array(
				'label' => __( 'Selected', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_woo_checkout_payment_label_color_select',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'.rael-woo-checkout {{WRAPPER}} .woocommerce .woo-checkout-payment #payment .payment_methods .wc_payment_method input[type="radio"]:checked + label' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Info.
		$this->add_control(
			'rael_woo_checkout_payment_info',
			array(
				'label'     => __( 'Methods Info', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'rael_woo_checkout_payment_methods_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FF793F',
				'selectors' => array(
					'.rael-woo-checkout {{WRAPPER}} .woo-checkout-payment .payment_box' => 'background-color: {{VALUE}}!important;',
					'.rael-woo-checkout {{WRAPPER}} .woo-checkout-payment .payment_box::before' => 'border-bottom-color: {{VALUE}}!important;',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_payment_methods_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'.rael-woo-checkout {{WRAPPER}} .woo-checkout-payment .payment_box' => 'color: {{VALUE}}!important;',
				),
			)
		);

		// Privacy Policy.
		$this->add_control(
			'rael_woo_checkout_privacy_policy',
			array(
				'label'     => __( 'Privacy Policy', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'rael_woo_checkout_privacy_policy_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#b8b6ca',
				'selectors' => array(
					'.rael-woo-checkout {{WRAPPER}} .woo-checkout-payment .woocommerce-privacy-policy-text' => 'color: {{VALUE}}!important;',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_woo_checkout_privacy_policy_typo',
				'selector' => '.rael-woo-checkout {{WRAPPER}} .woo-checkout-payment .woocommerce-privacy-policy-text',
			)
		);
		$this->add_control(
			'rael_woo_checkout_privacy_policy_link_color',
			array(
				'label'     => esc_html__( 'Link Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'.rael-woo-checkout {{WRAPPER}} .woo-checkout-payment a.woocommerce-privacy-policy-link' => 'color: {{VALUE}}!important;',
				),
			)
		);
		$this->add_control(
			'rael_woo_checkout_privacy_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#b8b6ca',
				'selectors' => array(
					'.rael-woo-checkout {{WRAPPER}} .woo-checkout-payment .place-order' => 'border-color: {{VALUE}}!important;',
				),
			)
		);
		// Privacy Policy Btn.
		$this->add_control(
			'rael_woo_checkout_payment_btn',
			array(
				'label'     => __( 'Button', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_woo_checkout_layout' => 'default',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_woo_checkout_payment_btn_typo',
				'selector'  => '.rael-woo-checkout {{WRAPPER}} .woocommerce #place_order',
				'condition' => array(
					'rael_woo_checkout_layout' => 'default',
				),
			)
		);

		$this->start_controls_tabs(
			'rael_woo_checkout_payment_btn_tabs',
			array(
				'condition' => array(
					'rael_woo_checkout_layout' => 'default',
				),
			)
		);
		$this->start_controls_tab(
			'rael_woo_checkout_payment_btn_tab_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_woo_checkout_payment_btn_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FF793F',
				'selectors' => array(
					'{{WRAPPER}} #place_order' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_payment_btn_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} #place_order' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_woo_checkout_payment_btn_border',
				'selector' => '{{WRAPPER}} #place_order',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_woo_checkout_payment_btn_tab_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_woo_checkout_payment_btn_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7866ff',
				'selectors' => array(
					'{{WRAPPER}} #place_order:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_payment_btn_color_hover',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} #place_order:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_payment_btn_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #place_order:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_woo_checkout_payment_btn_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'rael_woo_checkout_payment_btn_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => '5',
					'right'    => '5',
					'bottom'   => '5',
					'left'     => '5',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} #place_order' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_woo_checkout_layout' => 'default',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'rael_woo_checkout_payment_btn_box_shadow',
				'selector'  => '{{WRAPPER}} #place_order',
				'condition' => array(
					'rael_woo_checkout_layout' => 'default',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_payment_btn_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '15',
					'right'    => '20',
					'bottom'   => '15',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'.rael-woo-checkout {{WRAPPER}} .woocommerce #place_order' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_woo_checkout_layout' => 'default',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_payment_btn_top_spacing',
			array(
				'label'     => esc_html__( 'Top Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 20,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'.rael-woo-checkout {{WRAPPER}} .woocommerce #place_order' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_woo_checkout_layout' => 'default',
				),
			)
		);

		$this->end_controls_section();
		// woo checkout btn style.
		$this->start_controls_section(
			'rael_section_woo_checkout_steps_btn_styles',
			array(
				'label'     => esc_html__( 'Previous/Next Button', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_woo_checkout_layout!' => 'default',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_woo_checkout_steps_btn_typo',
				'selector' => '{{WRAPPER}} .steps-buttons button',
			)
		);
		$this->start_controls_tabs( 'rael_woo_checkout_steps_btn_tabs' );
		$this->start_controls_tab( 'rael_woo_checkout_steps_btn_tab_normal', array( 'label' => __( 'Normal', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'rael_woo_checkout_steps_btn_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FF793F',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .steps-buttons button,
                {{WRAPPER}} .rael-woo-checkout .steps-buttons button#rael_place_order' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_steps_btn_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .steps-buttons button,
                {{WRAPPER}} .rael-woo-checkout .steps-buttons button#rael_place_order' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_woo_checkout_steps_btn_border',
				'selector' => '{{WRAPPER}} .rael-woo-checkout .steps-buttons button,
            {{WRAPPER}} .rael-woo-checkout .steps-buttons button#rael_place_order',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'rael_woo_checkout_steps_btn_tab_hover', array( 'label' => __( 'Hover', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'rael_woo_checkout_steps_btn_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2d2c52',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .steps-buttons button:hover,
                {{WRAPPER}} .rael-woo-checkout .steps-buttons button#rael_place_order:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_steps_btn_color_hover',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .steps-buttons button:hover,
                {{WRAPPER}} .rael-woo-checkout .steps-buttons button#rael_place_order:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_woo_checkout_steps_btn_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-woo-checkout .steps-buttons button:hover,
                {{WRAPPER}} .rael-woo-checkout .steps-buttons button#rael_place_order:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_section_woo_checkout_steps_btn_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'rael_woo_checkout_steps_btn_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
				),
				'default'    => array(
					'top'      => '5',
					'right'    => '5',
					'bottom'   => '5',
					'left'     => '5',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-woo-checkout .steps-buttons button,
                {{WRAPPER}} .rael-woo-checkout .steps-buttons button#rael_place_order' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_woo_checkout_steps_btn_box_shadow',
				'selector' => '{{WRAPPER}} .rael-woo-checkout .steps-buttons button',
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_steps_btn_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'      => '13',
					'right'    => '20',
					'bottom'   => '13',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .steps-buttons button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_steps_btn_align',
			array(
				'label'     => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start'    => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'        => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'      => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'space-between' => array(
						'title' => __( 'Justified', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default'   => 'flex-start',
				'selectors' => array(
					'{{WRAPPER}} .steps-buttons' => 'justify-content: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'rael_woo_checkout_steps_btn_gap',
			array(
				'label'     => __( 'Gap', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .steps-buttons button:first-child' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .steps-buttons button:last-child' => 'margin-left: calc({{SIZE}}{{UNIT}} / 2);',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Adds custom body classes for the WooCommerce checkout page.
	 *
	 * This function checks if the current page is the checkout page in WooCommerce.
	 * If it is, it adds a custom body class 'rael-woo-checkout' to the list of existing classes.
	 *
	 * @param array $classes An array of CSS classes for the body element.
	 * @return array The modified array of CSS classes with the custom class added if on the checkout page.
	 */
	public function add_checkout_body_class( $classes ) {
		if ( is_checkout() ) {
			$classes[] = 'rael-woo-checkout';
		}
		return $classes;
	}

	/**
	 * Manages recurring payments display for WooCommerce checkout.
	 *
	 * This function checks if WooCommerce Subscriptions plugin is active.
	 * If active, it removes the default action for displaying recurring totals
	 * on the WooCommerce review order page and replaces it with a custom action.
	 * This allows customization of how recurring totals are displayed.
	 */
	public function rael_woocheckout_recurring() {
		if ( class_exists( 'WC_Subscriptions_Cart' ) ) {
			remove_action( 'woocommerce_review_order_after_order_total', array( 'WC_Subscriptions_Cart', 'display_recurring_totals' ), 10 );
			add_action(
				'rael_display_recurring_total_total',
				array(
					'WC_Subscriptions_Cart',
					'display_recurring_totals',
				),
				10
			);
		}
	}

	/**
	 * Render the widget on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.8.0
	 * @access protected
	 */
	protected function render() {
		if ( ! class_exists( 'woocommerce' ) ) {
			return;
		}

		/**
		 * Remove WC Coupon Action From  Neve Theme
		 */
		$this->rael_forcefully_remove_action( 'woocommerce_before_checkout_form', 'move_coupon', 10 );
		$this->rael_forcefully_remove_action( 'woocommerce_before_checkout_billing_form', 'clear_coupon', 10 );

		if ( class_exists( '\Woo_Carrier_Agents' ) ) {
			$this->rael_forcefully_remove_action( 'woocommerce_checkout_order_review', 'add_carrier_agent_field_before_payment', 15 );
			$wca = new \Woo_Carrier_Agents();
			add_action( 'rael_wc_multistep_checkout_after_shipping', array( $wca, 'add_carrier_agent_field_before_payment' ), 10, 0 );
		}

		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'container',
			'class',
			array(
				'rael-woo-checkout',
				'layout-' . $settings['rael_woo_checkout_layout'],
			)
		);

		global $wp;
		$order_review_change_data = array(
			'rael_woo_checkout_layout'              => $settings['rael_woo_checkout_layout'],
			'rael_woo_checkout_table_header_text'   => $settings['rael_woo_checkout_table_header_text'],
			'rael_woo_checkout_table_product_text'  => $settings['rael_woo_checkout_table_product_text'],
			'rael_woo_checkout_table_quantity_text' => $settings['rael_woo_checkout_table_quantity_text'],
			'rael_woo_checkout_table_price_text'    => $settings['rael_woo_checkout_table_price_text'],
			'rael_woo_checkout_shop_link'           => $settings['rael_woo_checkout_shop_link'],
			'rael_woo_checkout_shop_link_text'      => $settings['rael_woo_checkout_shop_link_text'],
			'rael_woo_checkout_table_subtotal_text' => $settings['rael_woo_checkout_table_subtotal_text'],
			'rael_woo_checkout_table_shipping_text' => $settings['rael_woo_checkout_table_shipping_text'],
			'rael_woo_checkout_table_total_text'    => $settings['rael_woo_checkout_table_total_text'],
		);
		$this->rael_woo_checkout_add_actions( $settings );

		?>
		<div data-checkout="<?php echo wp_kses_post( htmlspecialchars( wp_json_encode( $order_review_change_data ), ENT_QUOTES, 'UTF-8' ) ); ?>" <?php echo wp_kses_post( $this->get_render_attribute_string( 'container' ) ); ?>>
			<div class="woocommerce">
				<style>
					.woocommerce .blockUI.blockOverlay:before {
						background-image: url('<?php echo esc_url( WC_ABSPATH . 'assets/images/icons/loader.svg' ); ?>') center center !important;
					}
				</style>
				<?php

				// Backwards compatibility with old pay and thanks link arguments.
				if ( isset( $_GET['order'] ) && isset( $_GET['key'] ) ) { // phpcs:ignore: input var ok, CSRF ok.
					wc_deprecated_argument( __CLASS__ . '->' . __FUNCTION__, '2.1', '"order" is no longer used to pass an order ID. Use the order-pay or order-received endpoint instead.' );

					// Get the order to work out what we are showing.
					$order_id = absint( $_GET['order'] ); // WPCS: input var ok.
					$order    = wc_get_order( $order_id );

					if ( $order && $order->has_status( 'pending' ) ) {
						$wp->query_vars['order-pay'] = absint( $_GET['order'] ); // WPCS: input var ok.
					} else {
						$wp->query_vars['order-received'] = absint( $_GET['order'] ); // WPCS: input var ok.
					}
				}

				// Handle checkout actions.
				if ( ! empty( $wp->query_vars['order-pay'] ) ) {

					self::rael_order_pay( $wp->query_vars['order-pay'] );

				} elseif ( isset( $wp->query_vars['order-received'] ) ) {

					self::rael_order_received( $wp->query_vars['order-received'] );

				} else {
					self::rael_checkout( $settings );
				}

				?>
			</div>
		</div>
		<?php
	}

}
