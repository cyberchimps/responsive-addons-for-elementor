<?php
/**
 * RAEL Products widget
 *
 * @package Responsive_Addons_For_Elementor
 * @subpackage WooCommerce
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Woocommerce;

use Elementor\Widget_Base;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\QueryControl\Controls\Group_Control_Query;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\Woocommerce\Classes\Products_Renderer;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\Woocommerce\Classes\Current_Query_Renderer;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Responsive_Addons_For_Elementor\Traits\Missing_Dependency;
use Responsive_Addons_For_Elementor\Helper\Helper;
use Responsive_Addons_For_Elementor\Traits\RAEL_Products_Comparable;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * RAEL Products class.
 *
 * @since 1.0.0
 *
 * @since 1.6.0 Added more controls layouts, Product Compare, and Quick View.
 */
class Responsive_Addons_For_Elementor_Woo_Products extends Widget_Base {
	use Missing_Dependency;
	use RAEL_Products_Comparable;

	/**
	 * Is show custom add to cart
	 *
	 * @var Boolean
	 */
	private $is_show_custom_add_to_cart = false;

	/**
	 * Simple add to cart button text
	 *
	 * @var String
	 */
	private $simple_add_to_cart_button_text;

	/**
	 * Variable add to cart button text
	 *
	 * @var String
	 */
	private $variable_add_to_cart_button_text;

	/**
	 * Grouped add to cart button text
	 *
	 * @var String
	 */
	private $grouped_add_to_cart_button_text;

	/**
	 * External add to cart button text
	 *
	 * @var String
	 */
	private $external_add_to_cart_button_text;

	/**
	 * Default add to cart button text
	 *
	 * @var String
	 */
	private $default_add_to_cart_button_text;

	/**
	 * Page ID
	 *
	 * @var integer
	 */
	private $page_id;

	/**
	 * Constructor for the RAEL Product Carousel widget class.
	 *
	 * @param array $data  Optional. An array of widget data. Default is an empty array.
	 * @param mixed $args  Optional. Additional arguments for widget initialization. Default is null.
	 * @throws \Exception  Throws an exception if `$args` is null when initializing a full widget instance.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		$is_type_instance = $this->is_type_instance();

		if ( ! $is_type_instance && null === $args ) {
			throw new \Exception( '`$args` argument is required when initializing a full widget instance.' );
		}

		if ( $is_type_instance && class_exists( 'WooCommerce' ) ) {
			$this->load_quick_view_assets();
		}

		if ( ! class_exists( 'WooCommerce' ) ) {
			parent::register_controls();
		}
	}

	/**
	 * Retrieves the name of the widget.
	 *
	 * @return string The widget name.
	 */
	public function get_name() {
		return 'rael-woocommerce-products';
	}

	/**
	 * Retrieves the title of the widget.
	 *
	 * @return string The widget title.
	 */
	public function get_title() {
		return __( 'Products', 'responsive-addons-for-elementor' );
	}

	/**
	 * Retrieves the icon of the widget.
	 *
	 * @return string The widget icon.
	 */
	public function get_icon() {
		return 'eicon-products rael-badge';
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
	 * Retrieves the help url.
	 *
	 * @return string The widget icon.
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/products';
	}

	/**
	 * Retrieves the Font Awesome styles.
	 *
	 * @return string The widget icon.
	 */
	public function get_style_depends() {
		return array(
			'font-awesome-5-all',
			'font-awesome-4-shim',
		);
	}

	/**
	 * Retrieves the Font Awesome scripts.
	 *
	 * @return string The widget icon.
	 */
	public function get_script_depends() {
		return array(
			'font-awesome-4-shim',
		);
	}

	/**
	 * Loads the quick view assets.
	 */
	public function load_quick_view_assets() {
		add_action(
			'wp_footer',
			function () {
				if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
					if ( current_theme_supports( 'wc-product-gallery-zoom' ) ) {
						wp_enqueue_script( 'zoom' );
					}
					if ( current_theme_supports( 'wc-product-gallery-slider' ) ) {
						wp_enqueue_script( 'flexslider' );
					}
					if ( current_theme_supports( 'wc-product-gallery-lightbox' ) ) {
						wp_enqueue_script( 'photoswipe-ui-default' );
						wp_enqueue_style( 'photoswipe-default-skin' );
						if ( has_action( 'wp_footer', 'woocommerce_photoswipe' ) === false ) {
							add_action( 'wp_footer', 'woocommerce_photoswipe', 15 );
						}
					}
					wp_enqueue_script( 'wc-add-to-cart-variation' );
					wp_enqueue_script( 'wc-single-product' );
				}
			}
		);
	}

	/**
	 * Register Content Tab Layout Section
	 *
	 * @access protected
	 */
	protected function register_content_tab_layouts_section() {
		$this->start_controls_section(
			'rael_products_content_tab_layouts_section',
			array(
				'label' => __( 'Layouts', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_products_layout',
			array(
				'label'   => __( 'Layout', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'masonry',
				'options' => array(
					'grid'    => __( 'Grid', 'responsive-addons-for-elementor' ),
					'list'    => __( 'List', 'responsive-addons-for-elementor' ),
					'masonry' => __( 'Masonry', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_products_style_preset',
			array(
				'label'     => __( 'Style Preset', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'rael_product_simple',
				'options'   => array(
					'rael_product_default'  => __( 'Default', 'responsive-addons-for-elementor' ),
					'rael_product_simple'   => __( 'Simple Style', 'responsive-addons-for-elementor' ),
					'rael_product_reveal'   => __( 'Reveal Style', 'responsive-addons-for-elementor' ),
					'rael_product_overlay'  => __( 'Overlay Style', 'responsive-addons-for-elementor' ),
					'rael_product_preset-5' => __( 'Preset 5', 'responsive-addons-for-elementor' ),
					'rael_product_preset-6' => __( 'Preset 6', 'responsive-addons-for-elementor' ),
					'rael_product_preset-7' => __( 'Preset 7', 'responsive-addons-for-elementor' ),
					'rael_product_preset-8' => __( 'Preset 8', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_products_layout' => array( 'grid', 'masonry' ),
				),
			)
		);

		$this->add_control(
			'rael_products_list_style_preset',
			array(
				'label'     => __( 'Style Preset', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'rael_product_list_preset-1',
				'options'   => array(
					'rael_product_list_preset-1' => __( 'Preset 1', 'responsive-addons-for-elementor' ),
					'rael_product_list_preset-2' => __( 'Preset 2', 'responsive-addons-for-elementor' ),
					'rael_product_list_preset-3' => __( 'Preset 3', 'responsive-addons-for-elementor' ),
					'rael_product_list_preset-4' => __( 'Preset 4', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_products_layout' => array( 'list' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_products_grid_column',
			array(
				'label'        => __( 'Columns', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => '4',
				'options'      => array(
					'1' => __( '1', 'responsive-addons-for-elementor' ),
					'2' => __( '2', 'responsive-addons-for-elementor' ),
					'3' => __( '3', 'responsive-addons-for-elementor' ),
					'4' => __( '4', 'responsive-addons-for-elementor' ),
					'5' => __( '5', 'responsive-addons-for-elementor' ),
					'6' => __( '6', 'responsive-addons-for-elementor' ),
				),
				'toggle'       => true,
				'prefix_class' => 'rael-products-grid-column%s--',
				'condition'    => array(
					'rael_products_layout!' => 'list',
				),
			)
		);

		$this->add_responsive_control(
			'rael_products_list_column',
			array(
				'label'        => __( 'Columns', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => '2',
				'options'      => array(
					'1' => __( '1', 'responsive-addons-for-elementor' ),
					'2' => __( '2', 'responsive-addons-for-elementor' ),
				),
				'toggle'       => true,
				'prefix_class' => 'rael-products-list-column%s--',
				'condition'    => array(
					'rael_products_layout' => 'list',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Content Tab Product Settings Section
	 *
	 * @access protected
	 */
	protected function register_content_tab_product_settings_section() {
		$this->start_controls_section(
			'rael_products_content_tab_product_settings_section',
			array(
				'label' => __( 'Product Settings', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_post_type',
			array(
				'label'   => __( 'Source', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'product',
				'options' => array(
					'product'        => __( 'Products', 'responsive-addons-for-elementor' ),
					'source_dynamic' => __( 'Dynamic', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_products_dynamic_source_warning_text',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'This option will only affect in <strong>Archive page of Elementor Theme Builder</strong> dynamically.', 'responsive-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition'       => array(
					'rael_post_type' => 'source_dynamic',
				),
			)
		);

		$this->add_control(
			'rael_products_filter',
			array(
				'label'   => __( 'Filter By', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'recent_products',
				'options' => $this->get_product_filterby_options(),
			)
		);

		$this->add_control(
			'rael_orderby',
			array(
				'label'   => __( 'Order By', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_product_orderby_options(),
				'default' => 'date',
			)
		);

		$this->add_control(
			'rael_order',
			array(
				'label'   => __( 'Order', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'asc'  => __( 'Ascending', 'responsive-addons-for-elementor' ),
					'desc' => __( 'Descending', 'responsive-addons-for-elementor' ),
				),
				'default' => 'desc',
			)
		);

		$this->add_control(
			'rael_products_count',
			array(
				'label'   => __( 'Products Count', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 1000,
				'step'    => 1,
			)
		);

		$this->add_control(
			'rael_products_offset',
			array(
				'label'   => __( 'Offset', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
			)
		);

		$this->add_control(
			'rael_products_categories',
			array(
				'label'       => __( 'Product Categories', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => Helper::get_terms_list( 'product_cat', 'slug' ),
				'condition'   => array(
					'rael_post_type!' => 'source_dynamic',
				),
			)
		);

		$this->add_control(
			'rael_products_dynamic_template_layout',
			array(
				'label'   => __( 'Layout', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => __( 'Default', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_products_title_html_tag',
			array(
				'label'   => __( 'Title HTML Tag', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => array(
					'h1'   => __( 'H1', 'responsive-addons-for-elementor' ),
					'h2'   => __( 'H2', 'responsive-addons-for-elementor' ),
					'h3'   => __( 'H3', 'responsive-addons-for-elementor' ),
					'h4'   => __( 'H4', 'responsive-addons-for-elementor' ),
					'h5'   => __( 'H5', 'responsive-addons-for-elementor' ),
					'h6'   => __( 'H6', 'responsive-addons-for-elementor' ),
					'div'  => __( 'div', 'responsive-addons-for-elementor' ),
					'span' => __( 'span', 'responsive-addons-for-elementor' ),
					'p'    => __( 'p', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_products_rating',
			array(
				'label'        => __( 'Show Product Rating?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'rael_products_style_preset!' => 'rael_product_preset-8',
				),
			)
		);

		$this->add_control(
			'rael_products_price',
			array(
				'label'        => __( 'Show Product Price?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'rael_products_style_preset!' => 'rael_product_default',
				),
			)
		);

		$this->add_control(
			'rael_products_excerpt',
			array(
				'label'        => __( 'Short Description?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'rael_products_layout' => 'list',
				),
			)
		);

		$this->add_control(
			'rael_products_excerpt_length',
			array(
				'label'     => __( 'Excerpt Words', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '10',
				'condition' => array(
					'rael_products_excerpt' => 'yes',
					'rael_products_layout'  => 'list',
				),
			)
		);

		$this->add_control(
			'rael_products_excerpt_expansion_indicator',
			array(
				'label'       => __( 'Expansion Indicator', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
				'default'     => '...',
				'condition'   => array(
					'rael_products_excerpt' => 'yes',
					'rael_products_layout'  => 'list',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'        => 'rael_products_image_size',
				'exclude'     => array( 'custom' ),
				'default'     => 'medium',
				'label_block' => true,
			)
		);

		$this->add_control(
			'rael_products_show_compare',
			array(
				'label'        => __( 'Show Product Compare?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'rael_products_image_clickable',
			array(
				'label'        => __( 'Image Clickable?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Content Tab Sale Badges Section
	 *
	 * @access protected
	 */
	protected function register_content_tab_sale_stockout_badges_section() {
		$this->start_controls_section(
			'rael_products_content_tab_stockout_badges_section',
			array(
				'label' => __( 'Sale / Stock Out Badge', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_products_sale_badge_preset',
			array(
				'label'   => __( 'Style Preset', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'sale_preset-1',
				'options' => array(
					'sale_preset-1' => esc_html__( 'Preset 1', 'responsive-addons-for-elementor' ),
					'sale_preset-2' => esc_html__( 'Preset 2', 'responsive-addons-for-elementor' ),
					'sale_preset-3' => esc_html__( 'Preset 3', 'responsive-addons-for-elementor' ),
					'sale_preset-4' => esc_html__( 'Preset 4', 'responsive-addons-for-elementor' ),
					'sale_preset-5' => esc_html__( 'Preset 5', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_products_sale_badge_alignment',
			array(
				'label'     => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'  => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'right' => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'condition' => array(
					'rael_products_layout!' => 'list',
				),
			)
		);

		$this->add_control(
			'rael_products_sale_type',
			array(
				'label'     => __( 'Sale Type?', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'separator' => 'before',
				'default'   => 'static',
				'options'   => array(
					'static'             => __( 'Static Text', 'responsive-addons-for-elementor' ),
					'dynamic_price'      => __( 'Dynamic (Price Off)', 'responsive-addons-for-elementor' ),
					'dynamic_percentage' => __( 'Dynamic (Percentage)', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_products_sale_static_text',
			array(
				'label'     => __( 'Sale Static Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'rael_products_sale_type' => 'static',
				),
			)
		);

		$this->add_control(
			'rael_products_sale_dynamic_text',
			array(
				'label'     => __( 'Sale Dynamic Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Off', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_products_sale_type!' => 'static',
				),
			)
		);

		$this->add_control(
			'rael_products_stockout_text',
			array(
				'label' => __( 'Stock Out Text', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Content Tab Add to Cart Section
	 *
	 * @access protected
	 */
	protected function register_content_tab_add_to_cart_section() {
		$this->start_controls_section(
			'rael_products_content_tab_add_to_cart_section',
			array(
				'label' => __( 'Add To Cart', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_products_show_add_to_cart_custom_text',
			array(
				'label'        => __( 'Show Add To Cart Custom Text?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'true',
				'default'      => '',
			)
		);

		$this->add_control(
			'rael_products_add_to_cart_simple_product_button_text',
			array(
				'label'       => __( 'Simple Product', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'label_block' => false,
				'default'     => __( 'Buy Now', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_products_show_add_to_cart_custom_text' => 'true',
				),
			)
		);

		$this->add_control(
			'rael_products_add_to_cart_variable_product_button_text',
			array(
				'label'       => __( 'Variable Product', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'label_block' => false,
				'default'     => __( 'Select options', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_products_show_add_to_cart_custom_text' => 'true',
				),
			)
		);

		$this->add_control(
			'rael_products_add_to_cart_grouped_product_button_text',
			array(
				'label'       => __( 'Grouped Product', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'label_block' => false,
				'default'     => __( 'View products', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_products_show_add_to_cart_custom_text' => 'true',
				),
			)
		);

		$this->add_control(
			'rael_products_add_to_cart_external_product_button_text',
			array(
				'label'       => __( 'External Product', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'label_block' => false,
				'default'     => __( 'Buy Now', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_products_show_add_to_cart_custom_text' => 'true',
				),
			)
		);

		$this->add_control(
			'rael_products_add_to_cart_default_product_button_text',
			array(
				'label'       => __( 'Default Product', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'label_block' => false,
				'default'     => __( 'Read More', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_products_show_add_to_cart_custom_text' => 'true',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Style Tab Product Section
	 *
	 * @access protected
	 */
	protected function register_style_tab_products_section() {
		$this->start_controls_section(
			'rael_products_style_tab_products_section',
			array(
				'label' => __( 'Products', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_products_content_alignment',
			array(
				'label'      => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::CHOOSE,
				'options'    => array(
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
				'default'    => 'center',
				'toggle'     => true,
				'selectors'  => array(
					'{{WRAPPER}} .rael-products:not(.list) .woocommerce ul.products li.product' => 'text-align: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_products_layout',
							'operator' => '!=',
							'value'    => array(
								'list',
							),
						),
						array(
							'name'     => 'rael_products_style_preset',
							'operator' => 'in',
							'value'    => array(
								'rael_product_default',
								'rael_product_simple',
								'rael_product_reveal',
								'rael_product_overlay',
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_products_background_color',
			array(
				'label'      => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '#fff',
				'selectors'  => array(
					'{{WRAPPER}} .rael-products .woocommerce ul.products li.product, {{WRAPPER}} .rael-products .rael-products__icons-wrapper.block-box-style' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-products .woocommerce ul.products li.product.rael_product_list_preset-4 .rael-products__product-details-wrapper' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-products .woocommerce ul.products li.product.rael_product_list_preset-3, {{WRAPPER}} .rael-products .woocommerce ul.products li.product.rael_product_list_preset-4'
					=> 'background-color: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_products_layout',
							'operator' => 'in',
							'value'    => array(
								'grid',
								'list',
								'masonry',
							),
						),
						array(
							'name'     => 'rael_products_style_preset',
							'operator' => '!=',
							'value'    => array(
								'rael_product_list_preset-3',
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_products_border_color',
			array(
				'label'      => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '#ada8a8',
				'selectors'  => array(
					'{{WRAPPER}} .rael-products .rael-products__price-wrapper, {{WRAPPER}} .rael-products .rael-products__title-wrapper' => 'border-color: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_products_layout',
							'operator' => '!in',
							'value'    => array(
								'grid',
								'masonry',
							),
						),
						array(
							'name'     => 'rael_products_style_preset',
							'operator' => '==',
							'value'    => 'rael_product_list_preset-3',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_products_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-products .woocommerce ul.products li.product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_products_layout',
							'operator' => '!=',
							'value'    => array(
								'list',
							),
						),
						array(
							'name'     => 'rael_products_style_preset',
							'operator' => 'in',
							'value'    => array(
								'rael_product_default',
								'rael_product_reveal',
								'rael_product_simple',
								'rael_product_overlay',
								'rael_product_preset-5',
								'rael_product_preset-6',
								'rael_product_preset-7',
								'rael_product_preset-8',
							),
						),
					),
				),
			)
		);

		$this->start_controls_tabs(
			'rael_products_product_style_tabs',
			array(
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'rael_products_layout',
							'operator' => 'in',
							'value'    => array(
								'grid',
								'mesonry',
							),
						),
						array(
							'name'     => 'rael_products_style_preset',
							'operator' => '!in',
							'value'    => array(
								'rael_product_list_preset-3',
								'rael_product_list_preset-4',
							),
						),
					),
				),
			)
		);

		$this->start_controls_tab(
			'rael_products_product_style_normal_tab',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'rael_products_border_normal',
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'width'  => array(
						'default' => array(
							'top'      => '1',
							'right'    => '1',
							'bottom'   => '1',
							'left'     => '1',
							'isLinked' => false,
						),
					),
					'color'  => array(
						'default' => '#eee',
					),
				),
				'selector'       => '{{WRAPPER}} .rael-products .woocommerce ul.products li.product',
				'condition'      => array(
					'rael_products_style_preset' => array(
						'rael_product_default',
						'rael_product_simple',
						'rael_product_overlay',
						'rael_product_preset-5',
						'rael_product_preset-6',
						'rael_product_preset-7',
						'rael_product_preset-8',
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_products_box_shadow_normal',
				'selector' => '{{WRAPPER}} .rael-products .woocommerce ul.products li.product',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_products_product_style_hover_tab',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_products_border_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-products .woocommerce ul.products li.product:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_products_border_normal_border!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_products_box_shadow_hover',
				'selector' => '{{WRAPPER}} .rael-products .woocommerce ul.products li.product:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_products_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-products .woocommerce ul.products li.product' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
					'{{WRAPPER}} .rael-products .woocommerce ul.products li.product woocommerce-loop-product__link img' => 'border-radius: {{TOP}}px {{RIGHT}}px 0 0;',
					'{{WRAPPER}} .rael-products.list .woocommerce ul.products li.product .woocommerce-loop-product__link img' => 'border-radius: {{TOP}}px 0 0 {{LEFT}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'rael_products_image_width',
			array(
				'label'     => __( 'Image Width(%)', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'%' => array(
						'max' => 50,
					),
				),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .rael-products.list .rael-products__product-wrapper .rael-products__product-image-wrapper' => 'width: {{SIZE}}%;',
				),
				'condition' => array(
					'rael_products_layout' => 'list',
				),
			)
		);

		$this->add_control(
			'rael_products_details_heading',
			array(
				'label'      => __( 'Product Details', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::HEADING,
				'separator'  => 'before',
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_products_layout',
							'operator' => 'in',
							'value'    => array(
								'grid',
								'list',
								'masonry',
							),
						),
						array(
							'name'     => 'rael_products_style_preset',
							'operator' => '!in',
							'value'    => array(
								'rael_product_default',
								'rael_product_simple',
								'rael_product_reveal',
								'rael_product_overlay',
							),
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'rael_products_details_alignment',
			array(
				'label'      => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::CHOOSE,
				'options'    => array(
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
				'default'    => 'center',
				'selectors'  => array(
					'{{WRAPPER}} .rael-products .rael-products__product-details-wrapper' => 'text-align: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_products_layout',
							'operator' => '!=',
							'value'    => array(
								'list',
							),
						),
						array(
							'name'     => 'rael_products_style_preset',
							'operator' => '!in',
							'value'    => array(
								'rael_product_default',
								'rael_product_simple',
								'rael_product_reveal',
								'rael_product_overlay',
							),
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'rael_products_inner_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'top'      => '15',
					'right'    => '15',
					'bottom'   => '15',
					'left'     => '15',
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-products.grid .rael-products__product-wrapper .rael-products__product-details-wrapper, {{WRAPPER}} .rael-products.masonry .rael-products__product-wrapper .rael-products__product-details-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_products_layout',
							'operator' => '!=',
							'value'    => array(
								'list',
							),
						),
						array(
							'name'     => 'rael_products_style_preset',
							'operator' => '!in',
							'value'    => array(
								'rael_product_default',
								'rael_product_simple',
								'rael_product_reveal',
								'rael_product_overlay',
							),
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'rael_products_list_padding',
			array(
				'label'      => __( 'Padding (px)', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-products.list .rael_product_list_preset-1 .rael-products__product-wrapper .rael-products__product-details-wrapper, {{WRAPPER}} .rael-products.list .rael_product_list_preset-4 .rael-products__product-wrapper .rael-products__product-details-wrapper' => Helper::dimensions_css( 'padding' ),
					'{{WRAPPER}} .rael-products.list .rael_product_list_preset-2 .rael-products__product-wrapper' => Helper::dimensions_css( 'padding' ),
					'{{WRAPPER}} .rael-products.list .rael_product_list_preset-2 .rael-products__product-wrapper .rael-products__product-details-wrapper' => Helper::dimensions_css( 'padding' ),
					'{{WRAPPER}} .rael-products.list .rael_product_list_preset-3 .rael-products__product-wrapper .rael-products__product-details-wrapper' => Helper::dimensions_css( 'padding' ),
				),
				'condition'  => array(
					'rael_products_layout' => 'list',
				),
			)
		);

		$this->add_responsive_control(
			'rael_products_list_content_width',
			array(
				'label'     => __( 'Width (%)', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'%' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-products.list .rael-products__product-wrapper .rael-products__product-details-wrapper' => 'width: {{SIZE}}%;',
				),
				'condition' => array(
					'rael_products_layout' => 'list',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Style Tab Pagination Section
	 *
	 * @access protected
	 */
	protected function register_style_tab_pagination_section() {
		$this->start_controls_section(
			'rael_products_style_tab_pagination_section',
			array(
				'label'     => __( 'Pagination', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_products_show_pagination' => 'true',
					'rael_products_layout'          => array( 'grid', 'list' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_products_pagination_alignment',
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
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .rael-products__pagination' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_pagination_top_spacing',
			array(
				'label'     => __( 'Top Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'default'   => array(
					'size' => 15,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-products__pagination' => 'margin-top: {{SIZE}}px;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_products_pagination_typography',
				'selector' => '{{WRAPPER}} .rael-products__pagination',
			)
		);

		$this->start_controls_tabs( 'rael_products_pagination_style_tabs' );

		$this->start_controls_tab(
			'rael_products_pagination_style_tab_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_products_pagination_text_color_normal',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2F436C',
				'selectors' => array(
					'{{WRAPPER}} .rael-products__pagination a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_pagination_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-products__pagination a' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_products_pagination_border_normal',
				'selector' => '{{WRAPPER}} .rael-products__pagination a, {{WRAPPER}} .rael-products__pagination span',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_products_pagination_style_tab_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_products_pagination_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-products__pagination a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_pagination_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#8040FF',
				'selectors' => array(
					'{{WRAPPER}} .rael-products__pagination a:hover' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_pagination_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-products__pagination a:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_products_pagination_border_normal_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_products_pagination_style_tab_active',
			array(
				'label' => __( 'Active', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_products_pagination_text_color_active',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-products__pagination .current' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_pagination_bg_color_active',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#8040FF',
				'selectors' => array(
					'{{WRAPPER}} .rael-products__pagination .current' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_pagination_border_color_active',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-products__pagination .current' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_products_pagination_border_normal_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'rael_products_pagination_border_radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-products__pagination li > *' => 'border-radius: {{SIZE}}px;',
				),
			)
		);

		// Pagination Loader.
		$this->add_control(
			'rael_products_pagination_loader',
			array(
				'label'     => __( 'Loader', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_products_pagination_loader_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}}.rael-products__loader::after' => 'border-left-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Content Tab Load More Section
	 *
	 * @access protected
	 */
	protected function register_content_tab_load_more_section() {
		$this->start_controls_section(
			'rael_products_content_tab_load_more_section',
			array(
				'label'      => __( 'Load More', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_CONTENT,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'rael_products_layout',
							'operator' => 'in',
							'value'    => array( 'masonry' ),
						),
						array(
							'name'     => 'rael_products_show_pagination',
							'operator' => '!=',
							'value'    => 'true',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_products_show_load_more',
			array(
				'label'        => __( 'Show Load More', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'true',
				'default'      => '',
			)
		);

		$this->add_control(
			'rael_products_load_more_text',
			array(
				'label'       => __( 'Label Text', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
				'default'     => __( 'Load More', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_products_show_load_more' => array( 'yes', '1', 'true' ),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Content Tab Pagination Section
	 *
	 * @access protected
	 */
	protected function register_content_tab_pagination_section() {
		$this->start_controls_section(
			'rael_products_content_tab_pagination_section',
			array(
				'label'     => __( 'Pagination', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'rael_products_layout'          => array( 'grid', 'list' ),
					'rael_products_show_load_more!' => 'true',
				),
			)
		);

		$this->add_control(
			'rael_products_show_pagination',
			array(
				'label'        => __( 'Show Pagination', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'true',
				'default'      => '',
			)
		);

		$this->add_control(
			'rael_products_pagination_prev_label',
			array(
				'label'     => __( 'Previous Label', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( '←', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_products_show_pagination' => 'true',
				),
			)
		);

		$this->add_control(
			'rael_products_pagination_next_label',
			array(
				'label'     => __( 'Next Label', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( '→', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_products_show_pagination' => 'true',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Style Tab Color Typography Section
	 *
	 * @access protected
	 */
	protected function register_style_tab_color_typography_section() {
		$this->start_controls_section(
			'rael_products_style_tab_color_typography_section',
			array(
				'label' => __( 'Color & Typography', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_products_product_title_heading',
			array(
				'label' => __( 'Product Title', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_products_product_title_color',
			array(
				'label'     => __( 'Product Title Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#272727',
				'selectors' => array(
					'{{WRAPPER}} .rael-products .woocommerce ul.products li.product .woocommerce-loop-product__title, {{WRAPPER}} .rael-products .woocommerce ul.products li.product .rael-products__title h2' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_products_product_title_typography',
				'selector' => '{{WRAPPER}} .rael-products .woocommerce ul.products li.product .woocommerce-loop-product__title, {{WRAPPER}} .rael-products .woocommerce ul.products li.product .rael-products__title h2',
			)
		);

		$this->add_control(
			'rael_products_product_price_heading',
			array(
				'label' => __( 'Product Price', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_products_product_price_color',
			array(
				'label'     => __( 'Price Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#767676',
				'selectors' => array(
					'{{WRAPPER}} .rael-products .woocommerce ul.products li.product .price, {{WRAPPER}} .rael-products .woocommerce ul.products li.product .rael-products__price,
					{{WRAPPER}} .rael-products .woocommerce ul.products li.product .price del, {{WRAPPER}} .rael-products .woocommerce ul.products li.product .rael-products__price del' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_product_sale_price_color',
			array(
				'label'     => __( 'Sale Price Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .rael-products .woocommerce ul.products li.product .price ins, {{WRAPPER}} .rael-products .woocommerce ul.products li.product .rael-products__price ins' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_products_product_price_typography',
				'selector' => '{{WRAPPER}} .rael-products .woocommerce ul.products li.product .price,{{WRAPPER}} .rael-products .woocommerce ul.products li.product .rael-products__price',
			)
		);

		$this->add_control(
			'rael_products_product_rating_heading',
			array(
				'label' => __( 'Start Rating', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_products_product_rating_color',
			array(
				'label'     => __( 'Rating Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f2b01e',
				'selectors' => array(
					'{{WRAPPER}} .rael-products .woocommerce .star-rating::before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-products .woocommerce .star-rating span::before' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'       => 'rael_products_product_rating_typography',
				'selector'   => '{{WRAPPER}} .rael-products .woocommerce ul.products li.product .star-rating',
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'rael_products_style_preset',
							'operator' => '!in',
							'value'    => array(
								'rael_product_preset-5',
								'rael_product_preset-6',
								'rael_product_preset-7',
								'rael_product_preset-8',
							),
						),
						array(
							'name'     => 'rael_products_layout',
							'operator' => '!==',
							'value'    => 'list',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'rael_products_product_rating_size',
			array(
				'label'      => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 15,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-products .woocommerce ul.products li.product .star-rating' => 'font-size: {{SIZE}}px !important;',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'rael_products_style_preset',
							'operator' => 'in',
							'value'    => array(
								'rael_product_preset-5',
								'rael_product_preset-6',
								'rael_product_preset-7',
							),
						),
						array(
							'name'     => 'rael_products_layout',
							'operator' => '==',
							'value'    => 'list',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_products_product_desc_heading',
			array(
				'label'     => __( 'Product Description', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'rael_products_layout'  => 'list',
					'rael_products_excerpt' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_products_product_desc_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#272727',
				'selectors' => array(
					'{{WRAPPER}} .rael-products .woocommerce ul.products li.product .rael-products__excerpt' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_products_layout'  => 'list',
					'rael_products_excerpt' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_products_product_desc_typography',
				'selector'  => '{{WRAPPER}} .rael-products .woocommerce ul.products li.product .rael-products__excerpt',
				'condition' => array(
					'rael_products_layout'  => 'list',
					'rael_products_excerpt' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Style Tab Add to Cart Button Section
	 *
	 * @access protected
	 */
	protected function register_style_tab_add_to_cart_button_section() {
		$this->start_controls_section(
			'rael_products_style_tab_add_to_cart_button_section',
			array(
				'label'     => __( 'Add to Cart Button Styles', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_products_style_preset!' => array(
						'rael_product_preset-5',
						'rael_product_preset-6',
						'rael_product_preset-7',
						'rael_product_preset-8',
					),
					'rael_products_layout!'       => 'list',
				),
			)
		);

		// atc = add_to_cart.
		$this->add_control(
			'rael_products_atc_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-products .woocommerce li.product .button,
                    {{WRAPPER}} .rael-products .woocommerce li.product .button.add_to_cart_button,
                    {{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-products__link,
                    {{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .added_to_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_products_atc_radius',
			array(
				'label'      => __( 'Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-products .woocommerce li.product .button,
                    {{WRAPPER}} .rael-products .woocommerce li.product .button.add_to_cart_button,
                    {{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-products__link,
                    {{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .added_to_cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_products_atc_is_gradient_bg',
			array(
				'label'        => __( 'Use Gradient Background?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->start_controls_tabs(
			'rael_products_atc_styles_tabs'
		);

		$this->start_controls_tab(
			'rael_products_atc_styles_tab_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_products_atc_color_normal',
			array(
				'label'     => __( 'Button Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-products .woocommerce li.product .button, 
                    {{WRAPPER}} .rael-products .woocommerce li.product .button.add_to_cart_button' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-products__link' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .added_to_cart' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'rael_products_atc_gradient_background_normal',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .rael-products .woocommerce li.product .button,
                {{WRAPPER}} .rael-products .woocommerce li.product .button.add_to_cart_button,
                {{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-products__link,
                {{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .added_to_cart',
				'condition' => array(
					'rael_products_atc_is_gradient_bg' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_products_atc_background_normal',
			array(
				'label'     => __( 'Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => array(
					'{{WRAPPER}} .rael-products .woocommerce li.product .button, 
                    {{WRAPPER}} .rael-products .woocommerce li.product .button.add_to_cart_button' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-products__link' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .added_to_cart' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_products_atc_is_gradient_bg!' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_products_atc_border_normal',
				'selector' => '{{WRAPPER}} .rael-products .woocommerce li.product .button, 
                {{WRAPPER}} .rael-products .woocommerce li.product .button.add_to_cart_button, 
                {{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-products__link, 
                {{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .added_to_cart',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_products_atc_typography_normal',
				'selector'  => '{{WRAPPER}} .rael-products .woocommerce li.product .button,
                {{WRAPPER}} .rael-products .woocommerce li.product .button.add_to_cart_button',
				'condition' => array(
					'rael_products_style_preset' => array( 'rael_product_default', 'rael_product_simple' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_products_atc_style_tab_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_products_atc_color_hover',
			array(
				'label'     => __( 'Button Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-products .woocommerce li.product .button:hover,
                    {{WRAPPER}} .rael-products .woocommerce li.product .button.add_to_cart_button:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-products__link:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .added_to_cart:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'rael_products_atc_gradient_background_hover',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .rael-products .woocommerce li.product .button:hover,
                {{WRAPPER}} .rael-products .woocommerce li.product .button.add_to_cart_button:hover,
                {{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-products__link:hover,
                {{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .added_to_cart:hover',
				'condition' => array(
					'rael_products_atc_is_gradient_bg' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_products_atc_background_hover',
			array(
				'label'     => __( 'Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => array(
					'{{WRAPPER}} .rael-products .woocommerce li.product .button:hover,
                    {{WRAPPER}} .rael-products .woocommerce li.product .button.add_to_cart_button:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-products__link:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .added_to_cart:hover' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_products_atc_is_gradient_bg!' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_products_atc_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-products .woocommerce li.product .button:hover,
                    {{WRAPPER}} .rael-products .woocommerce li.product .button.add_to_cart_button:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-products__link:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .added_to_cart:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register Style Tab Sale Badge Section
	 *
	 * @access protected
	 */
	protected function register_style_tab_sale_badge_section() {
		$this->start_controls_section(
			'rael_products_style_tab_sale_badge_section',
			array(
				'label' => __( 'Sale Badge', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_products_sale_badge_color',
			array(
				'label'     => __( 'Sale Badge Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .onsale, {{WRAPPER}} .woocommerce ul.products li.product .rael-products__onsale' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_sale_badge_background',
			array(
				'label'     => __( 'Sale Badge Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff2a13',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .onsale, {{WRAPPER}} .woocommerce ul.products li.product .rael-products__onsale:not(.sale_preset-6)' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce ul.products li.product .rael-products__onsale:not(.rael-products__outofstock).sale_preset-4:after' => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce ul.products li.product .rael-products__onsale:not(.rael-products__outofstock).sale_preset-6 svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_products_sale_badge_typography',
				'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .rael-products__onsale, {{WRAPPER}} .woocommerce ul.products li.product .rael-products__onsale:not(.rael-products__outofstock), {{WRAPPER}} .woocommerce ul.products li.product .onsale, {{WRAPPER}} .woocommerce ul.products li.product .rael-products__outofstock-badge',
			)
		);

		$this->add_control(
			'rael_products_stock_out_badge_heading',
			array(
				'label' => __( 'Stock Out Badge', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_products_stock_out_badge_color',
			array(
				'label'     => __( 'Stock Out Badge Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .rael-products__outofstock-badge, {{WRAPPER}} .woocommerce ul.products li.product .rael-products__onsale.rael-products__outofstock' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_stock_out_badge_background',
			array(
				'label'     => __( 'Stock Out Badge Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff2a13',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .rael-products__outofstock-badge, {{WRAPPER}} .woocommerce ul.products li.product .rael-products__onsale.rael-products__outofstock:not(.sale_preset-6)' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce ul.products li.product .rael-products__onsale.rael-products__outofstock.sale_preset-4:after' => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce ul.products li.product .rael-products__onsale.rael-products__outofstock.sale_preset-6 svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_products_stock_out_badge_typography',
				'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .rael-products__outofstock-badge, {{WRAPPER}} .woocommerce ul.products li.product .rael-products__onsale.rael-products__outofstock',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Style Tab Buttons Section
	 *
	 * @access protected
	 */
	protected function register_style_tab_buttons_section() {
		$this->start_controls_section(
			'rael_products_style_tab_buttons_section',
			array(
				'label'      => __( 'Buttons', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_CONTENT,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'rael_products_style_preset',
							'operator' => 'in',
							'value'    => array(
								'rael_product_preset-5',
								'rael_product_preset-6',
								'rael_product_preset-7',
								'rael_product_preset-8',
							),
						),
						array(
							'name'     => 'rael_products_layout',
							'operator' => '==',
							'value'    => 'list',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_products_quick_view',
			array(
				'label'        => __( 'Show Quick view?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'rael_products_quick_view_title_tag',
			array(
				'label'     => __( 'Quick View Title Tag', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h1',
				'separator' => 'after',
				'options'   => array(
					'h1'   => __( 'H1', 'responsive-addons-for-elementor' ),
					'h2'   => __( 'H2', 'responsive-addons-for-elementor' ),
					'h3'   => __( 'H3', 'responsive-addons-for-elementor' ),
					'h4'   => __( 'H4', 'responsive-addons-for-elementor' ),
					'h5'   => __( 'H5', 'responsive-addons-for-elementor' ),
					'h6'   => __( 'H6', 'responsive-addons-for-elementor' ),
					'span' => __( 'span', 'responsive-addons-for-elementor' ),
					'p'    => __( 'p', 'responsive-addons-for-elementor' ),
					'div'  => __( 'div', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_products_quick_view' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_products_action_buttons_preset',
			array(
				'label'     => __( 'Style Preset', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'details-block-style',
				'options'   => array(
					'details-block-style'   => __( 'Preset 1', 'responsive-addons-for-elementor' ),
					'details-block-style-2' => __( 'Preset 2', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_products_layout' => 'list',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Style Tab Action Buttons Section
	 *
	 * @access protected
	 */
	protected function register_style_tab_action_buttons_section() {
		$this->start_controls_section(
			'rael_products_style_tab_action_buttons_section',
			array(
				'label'      => __( 'Button', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'rael_products_style_preset',
							'operator' => 'in',
							'value'    => array(
								'rael_product_preset-5',
								'rael_product_preset-6',
								'rael_product_preset-7',
								'rael_product_preset-8',
							),
						),
						array(
							'name'     => 'rael_products_layout',
							'operator' => '==',
							'value'    => 'list',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_products_action_buttons_bg_preset_5',
			array(
				'label'      => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '#8040FF',
				'selectors'  => array(
					'{{WRAPPER}} .rael-products .rael-products__product-wrapper .rael-products__icons-wrapper.block-style' => 'background: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_products_layout',
							'operator' => 'in',
							'value'    => array(
								'grid',
								'masonry',
							),
						),
						array(
							'name'     => 'rael_products_style_preset',
							'operator' => '==',
							'value'    => 'rael_product_preset-5',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_products_action_buttons_icon_size',
			array(
				'label'     => __( 'Icons Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'default'   => array(
					'size' => 18,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-products.list .rael-products__product-wrapper .rael-products__icons-wrapper li a i' => 'font-size: {{SIZE}}px;',
				),
				'condition' => array(
					'rael_products_grid_layout' => 'list',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_products_action_buttons_typography',
				'selector' => '{{WRAPPER}} .rael-products .rael-products__icons-wrapper li.add-to-cart a',
			)
		);

		$this->add_control(
			'rael_products_action_buttons_border_color_preset_5',
			array(
				'label'      => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '#fff',
				'selectors'  => array(
					'{{WRAPPER}} .rael-products .rael-products__icons-wrapper.block-style li' => 'border-color: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_products_layout',
							'operator' => 'in',
							'value'    => array(
								'grid',
								'masonry',
							),
						),
						array(
							'name'     => 'rael_products_style_preset',
							'operator' => '==',
							'value'    => 'rael_product_preset-5',
						),
					),
				),
			)
		);

		$this->start_controls_tabs( 'rael_products_action_buttons_style_tabs' );

		$this->start_controls_tab(
			'rael_products_action_buttons_style_tab_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_products_action_buttons_color_normal',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-products .rael-products__product-wrapper .rael-products__icons-wrapper li a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-wc-compare-icon' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_action_buttons_bg_normal',
			array(
				'label'      => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '#8040FF',
				'selectors'  => array(
					'{{WRAPPER}} .rael-products .rael-products__product-wrapper .rael-products__icons-wrapper li a' => 'background-color: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'rael_products_style_preset',
							'operator' => '!==',
							'value'    => 'rael_product_preset-5',
						),
						array(
							'name'     => 'rael_products_layout',
							'operator' => '==',
							'value'    => 'list',
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'       => 'rael_products_action_buttons_border_normal',
				'selector'   => '{{WRAPPER}} .rael-products .woocommerce li.product .button,
                {{WRAPPER}} .rael-products .woocommerce li.product .button.add_to_cart_button, 
                {{WRAPPER}} .rael-products .rael-products__product-wrapper .rael-products__icons-wrapper li a',
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'rael_products_style_preset',
							'operator' => '!==',
							'value'    => 'rael_product_preset-5',
						),
						array(
							'name'     => 'rael_products_action_buttons_preset',
							'operator' => '==',
							'value'    => 'preset-2',
						),
					),
				),
			)
		);
		$this->add_control(
			'rael_products_action_buttons_border_radius_normal',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'default'   => array(
					'size' => 3,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-products .rael-products__product-wrapper .rael-products__icons-wrapper:not(.details-block-style-2) li a' => 'border-radius: {{SIZE}}px;',
					'{{WRAPPER}} .rael-products .rael-products__product-wrapper .rael-products__icons-wrapper.details-block-style-2 li:only-child a' => 'border-radius: {{SIZE}}px!important;',
					'{{WRAPPER}} .rael-products .rael-products__product-wrapper .rael-products__icons-wrapper.details-block-style-2 li:first-child a' => 'border-radius: {{SIZE}}px 0 0 {{SIZE}}px;',
					'{{WRAPPER}} .rael-products .rael-products__product-wrapper .rael-products__icons-wrapper.details-block-style-2 li:last-child a' => 'border-radius: 0 {{SIZE}}px {{SIZE}}px 0;',
				),
			)
		);

		$this->add_control(
			'rael_products_action_buttons_top_spacing_normal',
			array(
				'label'     => __( 'Top Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-products .rael-products__product-wrapper .rael-products__icons-wrapper' => 'margin-top: {{SIZE}}px;',
				),
				'condition' => array(
					'rael_products_layout' => 'list',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_products_action_buttons_tab_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_products_action_buttons_color_hover',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#F5EAFF',
				'selectors' => array(
					'{{WRAPPER}} .rael-products .rael-products__product-wrapper .rael-products__icons-wrapper li a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_action_button_bg_hover',
			array(
				'label'      => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '#333',
				'selectors'  => array(
					'{{WRAPPER}} .rael-products .rael-products__product-wrapper .rael-products__icons-wrapper li a:hover' => 'background-color: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'rael_products_style_preset',
							'operator' => '!==',
							'value'    => 'rael_product_preset-5',
						),
						array(
							'name'     => 'rael_products_action_buttons_preset',
							'operator' => '!==',
							'value'    => 'preset-2',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_products_action_buttons_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-products .rael-products__product-wrapper .rael-products__icons-wrapper li a:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_products_action_buttons_border_normal_border!' => '',
					'rael_products_style_preset!' => 'rael_product_preset-5',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register Style Tab Load More Section
	 *
	 * @access protected
	 */
	protected function register_style_tab_load_more_section() {
		$this->start_controls_section(
			'rael_products_style_tab_load_more_section',
			array(
				'label'     => __( 'Load More Button', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_products_show_load_more' => array( 'yes', '1', 'true' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_products_load_more_button_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-products__load-more-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_products_load_more_button_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-products__load-more-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_products_load_more_button_typography',
				'selector' => '{{WRAPPER}} .rael-products__load-more-button',
			)
		);

		$this->start_controls_tabs( 'rael_products_load_more_button_tabs' );

		$this->start_controls_tab(
			'rael_products_load_more_button_normal_tab',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_products_load_more_button_text_color_normal',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-products__load-more-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_load_more_button_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#29d8d8',
				'selectors' => array(
					'{{WRAPPER}} .rael-products__load-more-button' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_products_load_more_button_normal',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-products__load-more-button',
			)
		);

		$this->add_control(
			'rael_products_load_more_button_border_radius_normal',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-products__load-more-button' => 'border-radius: {{SIZE}}px;',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'rael_products_load_more_button_shadow_normal',
				'selector'  => '{{WRAPPER}} .rael-products__load-more-button',
				'separator' => 'before',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_products_load_more_button_hover_tab',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_products_load_more_button_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-products__load-more-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_load_more_button_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#27bdbd',
				'selectors' => array(
					'{{WRAPPER}} .rael-products__load-more-button:hover' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_load_more_button_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-products__load-more-button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'rael_products_load_more_button_shadow_hover',
				'selector'  => '{{WRAPPER}} .rael-products__load-more-button:hover',
				'separator' => 'before',
			)
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_products_load_more_button_alignment',
			array(
				'label'     => __( 'Button Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .rael-products__load-more-button-wrapper' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Style Tab Popup Section
	 *
	 * @access protected
	 */
	protected function register_style_tab_popup_section() {
		$this->start_controls_section(
			'rael_products_style_tab_popup_section',
			array(
				'label' => __( 'Popup', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_products_popup_title',
			array(
				'label' => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_products_popup_title_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '.rael-pc__popup-details-render{{WRAPPER}} div.product .product_title',
			)
		);

		$this->add_control(
			'rael_products_popup_title_color',
			array(
				'label'     => __( 'Title Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#252525',
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} .rael-pc__product-quick-view-title.product_title.entry-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_popup_price',
			array(
				'label'     => __( 'Price', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_products_popup_price_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '.rael-pc__popup-details-render{{WRAPPER}} div.product .price',
			)
		);

		$this->add_control(
			'rael_products_popup_price_color',
			array(
				'label'     => __( 'Price Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0242e4',
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} div.product .price' => 'color: {{VALUE}}!important;',
				),
			)
		);

		$this->add_control(
			'rael_products_popup_sale_price_color',
			array(
				'label'     => __( 'Sale Price Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff2a13',
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} div.product .price ins' => 'color: {{VALUE}}!important;',
				),
			)
		);

		$this->add_control(
			'rael_products_popup_content',
			array(
				'label'     => __( 'Content', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_products_popup_content_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '.rael-pc__popup-details-render{{WRAPPER}} .woocommerce-product-details__short-description',
			)
		);

		$this->add_control(
			'rael_products_popup_content_color',
			array(
				'label'     => __( 'Content Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#707070',
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} .woocommerce-product-details__short-description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_popup_review_color',
			array(
				'label'     => __( 'Review Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0274be',
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} .woocommerce-product-rating .star-rating::before, .rael-pc__popup-details-render{{WRAPPER}} .woocommerce-product-rating .star-rating span::before' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_popup_review_link_color',
			array(
				'label'     => __( 'Review Link Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0274be',
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}}  a.woocommerce-review-link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_popup_review_link_hover',
			array(
				'label'     => __( 'Review Link Hover', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0274be',
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}}  a.woocommerce-review-link:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_popup_table_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ccc',
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} div.product table tbody tr, {{WRAPPER}} .rael-pc__product-popup.woocommerce div.product .product_meta' => 'border-color: {{VALUE}};',
				),
			)
		);

		// Sale.
		$this->add_control(
			'rael_products_popup_sale_style',
			array(
				'label'     => __( 'Sale', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_products_popup_sale_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '.rael-pc__popup-details-render{{WRAPPER}} .rael-pc__onsale',
			)
		);
		$this->add_control(
			'rael_products_popup_sale_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} .rael-pc__onsale' => 'color: {{VALUE}}!important;',
				),
			)
		);
		$this->add_control(
			'rael_products_popup_sale_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} .rael-pc__onsale' => 'background-color: {{VALUE}}!important;',
					'.rael-pc__popup-details-render{{WRAPPER}} .rael-pc__onsale:not(.rael-pc__out-of-stock).sale_preset-4:after'        => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
				),
			)
		);

		// Quantity.
		$this->add_control(
			'rael_products_popup_quantity',
			array(
				'label'     => __( 'Quantity', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_products_popup_quantity_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '.rael-pc__popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty, {{WRAPPER}} .rael-pc__product-popup.woocommerce div.product form.cart div.quantity > a, {{WRAPPER}} .rael-pc__product-popup.woocommerce div.product form.cart div.quantity > .button',
			)
		);

		$this->add_control(
			'rael_products_popup_quantity_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty, {{WRAPPER}} .rael-pc__product-popup.woocommerce div.product form.cart div.quantity > a, {{WRAPPER}} .rael-pc__product-popup.woocommerce div.product form.cart div.quantity > .button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_popup_quantity_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty, {{WRAPPER}} .rael-pc__product-popup.woocommerce div.product form.cart div.quantity > a, {{WRAPPER}} .rael-pc__product-popup.woocommerce div.product form.cart div.quantity > .button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_popup_quantity_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty, {{WRAPPER}} .rael-pc__product-popup.woocommerce div.product form.cart div.quantity > a, {{WRAPPER}} .rael-pc__product-popup.woocommerce div.product form.cart div.quantity > .button' => 'border-color: {{VALUE}};',
					// OceanWP.
					'.rael-pc__popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty:focus'                                                                                                                                                                         => 'border-color: {{VALUE}};',
				),
			)
		);

		// Cart Button.
		$this->add_control(
			'rael_products_popup_cart_button',
			array(
				'label'     => __( 'Cart Button', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_products_popup_cart_button_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '.rael-pc__popup-details-render{{WRAPPER}} .button, .rael-pc__popup-details-render{{WRAPPER}} button.button.alt',
			)
		);

		$this->start_controls_tabs( 'rael_products_popup_cart_button_style_tabs' );

		$this->start_controls_tab(
			'rael_products_popup_cart_button_style_tab_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_products_popup_cart_button_color_normal',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} .button, .rael-pc__popup-details-render{{WRAPPER}} button.button.alt' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_popup_cart_button_background',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#8040FF',
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} .button, .rael-pc__popup-details-render{{WRAPPER}} button.button.alt' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_products_popup_cart_button_border',
				'selector' => '{{WRAPPER}} .single_add_to_cart_button',
			)
		);
		$this->add_control(
			'rael_products_popup_cart_button_border_radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} .button, .rael-pc__popup-details-render{{WRAPPER}} button.button.alt' => 'border-radius: {{SIZE}}px;',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_products_popup_cart_button_style_tab_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_products_popup_cart_button_color_hover',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#F5EAFF',
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} .button:hover, .rael-pc__popup-details-render{{WRAPPER}} button.button.alt:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_popup_cart_button_background_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#F12DE0',
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} .button:hover, .rael-pc__popup-details-render{{WRAPPER}} button.button.alt:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_popup_cart_button_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} .button:hover, .rael-pc__popup-details-render{{WRAPPER}} button.button.alt:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_products_popup_cart_button_border_normal_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// SKU.
		$this->add_control(
			'rael_products_popup_sku_style',
			array(
				'label'     => __( 'SKU', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_products_popup_sku_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '.rael-pc__popup-details-render{{WRAPPER}} .product_meta',
			)
		);
		$this->add_control(
			'rael_products_popup_sku_title_color',
			array(
				'label'     => __( 'Title Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} .product_meta' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_products_popup_sku_content_color',
			array(
				'label'     => __( 'Content Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} .product_meta .sku, .rael-pc__popup-details-render{{WRAPPER}} .product_meta a' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_products_popup_sku_color_hover',
			array(
				'label'     => __( 'Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} .product_meta a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_popup_close_button_style',
			array(
				'label'     => __( 'Close Button', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'rael_products_popup_close_button_icon_size',
			array(
				'label'      => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
					'em' => array(
						'min' => 0,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'.rael-pc__popup-details-render{{WRAPPER}} button.rael-pc__product-popup-close' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_products_popup_close_button_size',
			array(
				'label'      => __( 'Button Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
					'em' => array(
						'min' => 0,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'.rael-pc__popup-details-render{{WRAPPER}} button.rael-pc__product-popup-close' => 'max-width: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'rael_products_popup_close_button_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} button.rael-pc__product-popup-close' => 'color: {{VALUE}}!important;',
				),
			)
		);

		$this->add_control(
			'rael_products_popup_close_button_bg',
			array(
				'label'     => __( 'Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} button.rael-pc__product-popup-close' => 'background-color: {{VALUE}}!important;',
				),
			)
		);

		$this->add_control(
			'rael_products_popup_close_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'.rael-pc__popup-details-render{{WRAPPER}} button.rael-pc__product-popup-close' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_products_popup_close_button_box_shadow',
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '.rael-pc__popup-details-render{{WRAPPER}} button.rael-pc__product-popup-close',
			)
		);

		$this->add_responsive_control(
			'rael_products_popup_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'.rael-pc__popup-details-render{{WRAPPER}}.rael-pc__product-popup-details' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_products_popup_background',
				'label'    => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '.rael-pc__popup-details-render{{WRAPPER}}.rael-pc__product-popup-details',
				'exclude'  => array(
					'image',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_products_popup_box_shadow',
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '.rael-pc__popup-details-render{{WRAPPER}}.rael-pc__product-popup-details',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add to Cart Button Custom Text.
	 *
	 * @param string $default Read more text.
	 * @access public
	 */
	public function add_to_cart_button_custom_text( $default ) {
		if ( $this->is_show_custom_add_to_cart ) {
			global $product;
			switch ( $product->get_type() ) {
				case 'external':
					return $this->external_add_to_cart_button_text;
				case 'grouped':
					return $this->grouped_add_to_cart_button_text;
				case 'simple':
					if ( ! $product->is_in_stock() ) {
						return $this->default_add_to_cart_button_text;
					}
					return $this->simple_add_to_cart_button_text;
				case 'variable':
					return $this->variable_add_to_cart_button_text;
				default:
					return $this->default_add_to_cart_button_text;
			}
		}

		if ( 'Read more' === $default ) {
			return esc_html__( 'View More', 'responsive-addons-for-elementor' );
		}

		return $default;
	}

	/**
	 * Get Product Orderby Options.
	 *
	 * @access protected
	 */
	protected function get_product_orderby_options() {
		return apply_filters(
			'rael_widgets_rael_products_orderby_options',
			array(
				'ID'         => __( 'Product ID', 'responsive-addons-for-elementor' ),
				'title'      => __( 'Product Title', 'responsive-addons-for-elementor' ),
				'_price'     => __( 'Price', 'responsive-addons-for-elementor' ),
				'_sku'       => __( 'SKU', 'responsive-addons-for-elementor' ),
				'date'       => __( 'Date', 'responsive-addons-for-elementor' ),
				'modified'   => __( 'Last Modified Date', 'responsive-addons-for-elementor' ),
				'parent'     => __( 'Parent Id', 'responsive-addons-for-elementor' ),
				'rand'       => __( 'Random', 'responsive-addons-for-elementor' ),
				'menu_order' => __( 'Menu Order', 'responsive-addons-for-elementor' ),
			)
		);
	}

	/**
	 * Get Product Filterby Options.
	 *
	 * @access protected
	 */
	protected function get_product_filterby_options() {
		return apply_filters(
			'rael_widgets_rael_products_filterby_option',
			array(
				'recent_products'       => esc_html__( 'Recent Products', 'responsive-addons-for-elementor' ),
				'featured_products'     => esc_html__( 'Featured Products', 'responsive-addons-for-elementor' ),
				'best_selling_products' => esc_html__( 'Best Selling Products', 'responsive-addons-for-elementor' ),
				'sale_products'         => esc_html__( 'Sale Products', 'responsive-addons-for-elementor' ),
				'top_products'          => esc_html__( 'Top Rated Products', 'responsive-addons-for-elementor' ),
			)
		);
	}

	/**
	 * Register controls for the widget.
	 *
	 * @since 1.0.0
	 *
	 * @since 1.5.0 Added a condition to display a warning message in the editor when the dependency plugin is not activated.
	 *
	 * @since 1.6.0 Added more controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			$this->register_content_tab_missing_dep_warning_controls( 'WooCommerce', 'woocommerce' );
			return;
		}

		// Content Tab.
		$this->register_content_tab_layouts_section();
		$this->register_content_tab_product_settings_section();
		$this->register_content_tab_sale_stockout_badges_section();
		$this->register_content_tab_add_to_cart_section();
		$this->register_content_tab_load_more_section();
		$this->register_content_tab_pagination_section();
		$this->register_style_tab_buttons_section();

		// Product Compare.
		$this->register_content_tab_product_compare_section();
		$this->register_content_tab_compare_table_settings_section();

		// Style Tab.
		$this->register_style_tab_products_section();
		$this->register_style_tab_color_typography_section();
		$this->register_style_tab_add_to_cart_button_section();
		$this->register_style_tab_sale_badge_section();
		$this->register_style_tab_action_buttons_section();
		$this->register_style_tab_load_more_section();
		$this->register_style_tab_pagination_section();
		$this->register_style_tab_popup_section();

		// Product Compare Table.
		$container_class     = '.rael-products-compare-modal';
		$table               = '.rael-products-compare-modal .rael-products-compare-wrapper table';
		$table_title_wrapper = '.rael-products-compare-modal .rael-products-compare-wrapper .first-th';
		$table_title         = '.rael-products-compare-modal .rael-products-compare-wrapper .rael-products-compare-modal__title';

		$compare_button_condition = array(
			'rael_products_style_preset!' => array(
				'rael_product_preset-5',
				'rael_product_preset-6',
				'rael_product_preset-7',
				'rael_product_preset-8',
			),
			'rael_products_layout!'       => 'list',
		);

		$this->register_style_tab_compare_button_section( $compare_button_condition );
		$this->register_style_tab_compare_button_general_section( compact( 'container_class' ) );
		$this->register_style_tab_table_style_section( compact( 'table', 'table_title', 'table_title_wrapper' ) );
		$this->register_style_tab_close_button_section();
	}

	/**
	 * Get Shortcode Object.
	 *
	 * @param array $settings settings array.
	 * @access protected
	 */
	protected function get_shortcode_object( $settings ) {

		if ( 'current_query' === $settings[ Products_Renderer::QUERY_CONTROL_NAME . '_post_type' ] ) {
			$type = 'current_query';
			return new Current_Query_Renderer( $settings, $type );
		}
		$type = 'products';
		return new Products_Renderer( $settings, $type );
	}

	/**
	 * Render Pagination.
	 *
	 * @param array $args arguments array.
	 * @param array $settings settings array.
	 * @access protected
	 */
	protected function render_pagination( $args, $settings ) {
		$args['posts_per_page'] = -1;

		$pagination_query           = new \WP_Query( $args );
		$pagination_count           = count( $pagination_query->posts );
		$pagination_limit           = $settings['rael_products_count'] ? $settings['rael_products_count'] : 4;
		$pagination_pagination_list = ceil( $pagination_count / $pagination_limit );
		$last                       = ceil( $pagination_pagination_list );
		$filtered_settings          = array();

		$widget_id  = $settings['rael_widget_id'];
		$next_label = $settings['rael_products_pagination_next_label'];
		$prev_label = $settings['rael_products_pagination_prev_label'];

		$filtered_settings['rael_widget_id']                        = $settings['rael_widget_id'];
		$filtered_settings['rael_page_id']                          = $settings['rael_page_id'];
		$filtered_settings['rael_widget_name']                      = $settings['rael_widget_name'];
		$filtered_settings['rael_products_count']                   = $settings['rael_products_count'];
		$filtered_settings['rael_products_rating']                  = $settings['rael_products_rating'];
		$filtered_settings['rael_products_dynamic_template_layout'] = $settings['rael_products_dynamic_template_layout'];
		$filtered_settings['rael_products_image_size_size']         = $settings['rael_products_image_size_size'];
		$filtered_settings['rael_products_pagination_next_label']   = $settings['rael_products_pagination_next_label'];
		$filtered_settings['rael_products_pagination_prev_label']   = $settings['rael_products_pagination_prev_label'];

		$adjacents      = '2';
		$set_pagination = '';
		if ( $pagination_pagination_list > 0 ) {

			$set_pagination .= "<nav class='rael-products__pagination'>";
			$set_pagination .= "<ul class='rael-products__page-numbers'>";

			if ( $pagination_pagination_list < 7 + ( $adjacents * 2 ) ) {

				for ( $pagination = 1; $pagination <= $pagination_pagination_list; $pagination++ ) {

					if ( 0 === $pagination || 1 === $pagination ) {
						$active = 'current';
					} else {
						$active = '';
					}

					$set_pagination .= "<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-template='" . wp_json_encode(
						array(
							'file_name' => $settings['rael_products_dynamic_template_layout'],
							'name'      => $settings['rael_widget_name'],
						),
						1
					) . "' data-widget-id='$widget_id' data-args='" . http_build_query( $args ) . "' data-settings='" . http_build_query( $filtered_settings ) . "' data-pnumber='$pagination' data-plimit='$pagination_limit'>$pagination</a></li>";
				}
			} elseif ( $pagination_pagination_list > 5 + ( $adjacents * 2 ) ) {

				for ( $pagination = 1; $pagination <= 4 + ( $adjacents * 2 ); $pagination++ ) {
					if ( 0 === $pagination || 1 === $pagination ) {
						$active = 'current';
					} else {
						$active = '';
					}

					$set_pagination .= "<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-template='" . wp_json_encode(
						array(
							'file_name' => $settings['rael_products_dynamic_template_layout'],
							'name'      => $settings['rael_widget_name'],
						),
						1
					) . "' data-widget-id='$widget_id' data-args='" . http_build_query( $args ) . "' data-settings='" . http_build_query( $filtered_settings ) . "' data-pnumber='$pagination' data-plimit='$pagination_limit'>$pagination</a></li>";
				}

				$set_pagination .= "<li class='rael-products__pagination-text dots'>...</li>";
				$set_pagination .= "<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-template='" . wp_json_encode(
					array(
						'file_name' => $settings['rael_products_dynamic_template_layout'],
						'name'      => $settings['rael_widget_name'],
					),
					1
				) . "' data-widget-id='$widget_id' data-args='" . http_build_query( $args ) . "' data-settings='" . http_build_query( $filtered_settings ) . "' data-pnumber='$pagination' data-plimit='$pagination_limit'>$pagination</a></li>";
			} else {
				for ( $pagination = 1; $pagination <= $pagination_pagination_list; $pagination++ ) {
					if ( 0 === $pagination || 1 === $pagination ) {
						$active = 'current';
					} else {
						$active = '';
					}

					$set_pagination .= "<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-template='" . wp_json_encode(
						array(
							'file_name' => $settings['rael_products_dynamic_template_layout'],
							'name'      => $settings['rael_widget_name'],
						),
						1
					) . "' data-widget-id='$widget_id' data-args='" . http_build_query( $args ) . "' data-settings='" . http_build_query( $filtered_settings ) . "' data-pnumber='$pagination' data-plimit='$pagination_limit'>$pagination</a></li>";
				}
			}

			if ( $pagination_pagination_list > 1 ) {
				$set_pagination .= "<li class='pagitext'><a href='javascript:void(0);' class='page-numbers' data-template='" . wp_json_encode(
					array(
						'file_name' => $settings['rael_products_dynamic_template_layout'],
						'name'      => $settings['rael_widget_name'],
					),
					1
				) . "' data-widget-id='$widget_id' data-args='" . http_build_query( $args ) . "' data-settings='" . http_build_query( $filtered_settings ) . "' data-pnumber='2' data-plimit='$pagination_limit'>$next_label</a></li>";
			}
			$set_pagination .= '</ul>';
			$set_pagination .= '</nav>';

			return $set_pagination;
		}
	}

	/**
	 * Render Load More Button.
	 *
	 * @param array $args arguments array.
	 * @param array $settings settings array.
	 * @access protected
	 */
	protected function render_load_more_button( $args, $settings ) {
		if ( ! isset( $this->page_id ) ) {
			if ( Plugin::$instance->documents->get_current() ) {
				$this->page_id = Plugin::$instance->documents->get_current()->get_main_id();
			} else {
				$this->page_id = null;
			}
		}

		$max_page = empty( $args['max_page'] ) ? false : $args['max_page'];
		unset( $args['max_page'] );

		$this->add_render_attribute(
			'rael_products_load_more',
			array(
				'class'          => 'rael-products__load-more-button',
				'id'             => 'rael-products-load-more-btn-' . $this->get_id(),
				'data-widget-id' => $this->get_id(),
				'data-widget'    => $this->get_id(),
				'data-page-id'   => $this->page_id,
				'data-nonce'     => wp_create_nonce( 'rael_products_load_more' ),
				'data-template'  => wp_json_encode(
					array(
						'file_name' => $settings['loadable_file_name'],
						'name'      => 'woo-products',
					),
					1
				),
				'data-class'     => get_class( $this ),
				'data-layout'    => isset( $settings['layout_mode'] ) ? $settings['layout_mode'] : '',
				'data-page'      => 1,
				'data-args'      => http_build_query( $args ),
			)
		);

		if ( $max_page ) {
			$this->add_render_attribute( 'rael_products_load_more', array( 'data-max-page' => $max_page ) );
		}

		if ( ( 'true' === $settings['rael_products_show_load_more'] || 1 === $settings['rael_products_show_load_more'] || 'yes' === $settings['rael_products_show_load_more'] ) && '-1' !== $args['posts_per_page'] ) { ?>
			<div class="rael-products__load-more-button-wrapper">
				<button <?php $this->print_render_attribute_string( 'rael_products_load_more' ); ?>>
					<div class="rael-products__button-loader button__loader"></div>
					<span><?php echo esc_html( $settings['rael_products_load_more_text'] ); ?></span>
				</button>
			</div>
			<?php
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

		$settings                   = $this->get_settings_for_display();
		$widget_id                  = $this->get_id();
		$settings['layout_mode']    = $settings['rael_products_layout'];
		$settings['rael_widget_id'] = $widget_id;

		if ( 'source_dynamic' === $settings['rael_post_type'] && is_archive() || ! empty( $_REQUEST['post_type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$settings['posts_per_page'] = $settings['rael_products_count'] ? $settings['rael_products_count'] : 4;
			$settings['offset']         = $settings['rael_products_offset'];
			$args                       = Helper::get_query_args( $settings );
			$args                       = Helper::get_dynamic_args( $settings, $args );
		} else {
			$args = $this->build_product_query( $settings );
		}

		$this->is_show_custom_add_to_cart       = boolval( $settings['rael_products_show_add_to_cart_custom_text'] );
		$this->simple_add_to_cart_button_text   = $settings['rael_products_add_to_cart_simple_product_button_text'];
		$this->variable_add_to_cart_button_text = $settings['rael_products_add_to_cart_variable_product_button_text'];
		$this->grouped_add_to_cart_button_text  = $settings['rael_products_add_to_cart_grouped_product_button_text'];
		$this->external_add_to_cart_button_text = $settings['rael_products_add_to_cart_external_product_button_text'];
		$this->default_add_to_cart_button_text  = $settings['rael_products_add_to_cart_default_product_button_text'];

		if ( Plugin::$instance->documents->get_current() ) {
			$this->page_id = Plugin::$instance->documents->get_current()->get_main_id();
		}
		$this->add_render_attribute(
			'rael_products_wrapper',
			array(
				'class'          => array(
					'rael-products',
					$settings['rael_products_style_preset'],
					$settings['rael_products_layout'],
				),
				'id'             => 'rael-products',
				'data-widget-id' => $widget_id,
				'data-page-id'   => $this->page_id,
				'data-nonce'     => wp_create_nonce( 'rael_products' ),
			)
		);

		add_filter(
			'woocommerce_product_add_to_cart_text',
			array(
				$this,
				'add_to_cart_button_custom_text',
			)
		);
		?>

		<div <?php $this->print_render_attribute_string( 'rael_products_wrapper' ); ?> >
			<div class="woocommerce">
				<?php
				do_action( 'rael_before_product_loop' );
				$template                       = $this->get_template( $settings['rael_products_dynamic_template_layout'] );
				$settings['loadable_file_name'] = $this->get_filename_only( $template );
				$found_posts                    = 0;

				if ( file_exists( $template ) ) {
					$settings['rael_page_id'] = $this->page_id ? $this->page_id : get_the_ID();
					$query                    = new \WP_Query( $args );
					if ( $query->have_posts() ) {
						$found_posts      = $query->found_posts;
						$max_page         = ceil( $found_posts / absint( $args['posts_per_page'] ) );
						$args['max_page'] = $max_page;

						echo '<ul class="products" data-layout-mode="' . wp_kses_post( $settings['rael_products_layout'] ) . '">';
						while ( $query->have_posts() ) {
							$query->the_post();
							include $template;
						}
						wp_reset_postdata();
						echo '</ul>';
					} else {
						echo '<p class="no-posts-found">' . esc_html_e( 'No posts found!', 'responsive-addons-for-elementor' ) . '</p>';
					}
				} else {
					echo '<p class="no-posts-found">' . esc_html_e( 'No layout found!', 'responsive-addons-for-elementor' ) . '</p>';
				}
				if ( 'true' === $settings['rael_products_show_pagination'] ) {
					$settings['rael_widget_name'] = $this->get_name();
					echo wp_kses_post( $this->render_pagination( $args, $settings ) );
				}

				if ( $found_posts > $args['posts_per_page'] ) {
					$this->render_load_more_button( $args, $settings );
				}
				?>
			</div>
		</div>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				var $scope = jQuery(".elementor-element-<?php echo esc_attr( $this->get_id() ); ?>");
				var $products = $('.rael-products .products', $scope);
				var $layout_mode = $products.data('layout-mode');	
				if ( 'masonry' === $layout_mode ) {
					// init isotope
					var $isotope_products = $products.isotope();
					$isotope_products.imagesLoaded().progress( function() {
						$isotope_products.isotope('layout');
					})

					$(window).on('resize', function() {
						$isotope_products.isotope('layout');
					});
				}
			});
		</script>
		<?php
		remove_filter(
			'woocommerce_product_add_to_cart_text',
			array(
				$this,
				'add_to_cart_button_custom_text',
			)
		);
	}

	/**
	 * Get Template Name.
	 *
	 * @param string $template_name Template Name.
	 * @access private
	 */
	private function get_template( $template_name ) {
		$file_name = RAEL_DIR . 'includes/widgets-manager/widgets/skins/woo-products/' . sanitize_file_name( $template_name ) . '.php';
		return $file_name;
	}

	/**
	 * Get Filemame Only.
	 *
	 * @param string $path Path of the File.
	 * @access private
	 */
	private function get_filename_only( $path ) {
		$filename = \explode( '/', $path );
		return \end( $filename );
	}

	/**
	 * Build Product Query.
	 *
	 * @param string $settings widget settings.
	 * @access public
	 */
	public function build_product_query( $settings ) {
		$args = array(
			'post_type'      => 'product',
			'post_status'    => array( 'publish', 'pending', 'future' ),
			'posts_per_page' => $settings['rael_products_count'] ? $settings['rael_products_count'] : 4,
			'order'          => ( isset( $settings['rael_order'] ) ? $settings['rael_order'] : 'desc' ),
			'offset'         => $settings['rael_products_offset'],
			'tax_query'      => array( //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				'relation' => 'AND',
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => array( 'exclude-from-search', 'exclude-from-catalog' ),
					'operator' => 'NOT IN',
				),
			),
		);
		// price & sku filter.
		if ( '_price' === $settings['rael_orderby'] ) {
			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = '_price'; //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		} elseif ( '_sku' === $settings['rael_orderby'] ) {
			$args['tax_query'] = array( //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				'relation' => 'AND',
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
				),
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => array( 'exclude-from-search', 'exclude-from-catalog' ),
					'operator' => 'NOT IN',
				),
			);

			if ( $settings['rael_products_categories'] ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $settings['rael_products_categories'],
				);
			}
		} else {
			$args['orderby'] = ( isset( $settings['rael_orderby'] ) ? $settings['rael_orderby'] : 'date' );
		}

		if ( ! empty( $settings['rael_products_categories'] ) ) {
			$args['tax_query'] = array( //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $settings['rael_products_categories'],
					'operator' => 'IN',
				),
			);
		}

		$args['meta_query'] = array( 'relation' => 'AND' ); //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query

		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			$args['meta_query'][] = array(
				'key'   => '_stock_status',
				'value' => 'instock',
			);
		}

		if ( 'featured_products' === $settings['rael_products_filter'] ) {
			$args['tax_query'] = array( //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				'relation' => 'AND',
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
				),
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => array( 'exclude-from-search', 'exclude-from-catalog' ),
					'operator' => 'NOT IN',
				),
			);

			if ( $settings['rael_products_categories'] ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $settings['rael_products_categories'],
				);
			}
		} elseif ( 'best_selling_products' === $settings['rael_products_filter'] ) {

			$args['tax_query'] = array( //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				'relation' => 'AND',
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
				),
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => array( 'exclude-from-search', 'exclude-from-catalog' ),
					'operator' => 'NOT IN',
				),
			);

			if ( $settings['rael_products_categories'] ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $settings['rael_products_categories'],
				);
			}
		} elseif ( 'sale_products' === $settings['rael_products_filter'] ) {
			$args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
		} elseif ( 'top_products' === $settings['rael_products_filter'] ) {
			$args['tax_query'] = array( //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				'relation' => 'AND',
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
				),
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => array( 'exclude-from-search', 'exclude-from-catalog' ),
					'operator' => 'NOT IN',
				),
			);

			if ( $settings['rael_products_categories'] ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $settings['rael_products_categories'],
				);
			}
		}
		return $args;
	}

	/**
	 * Renders plain content for the widget.
	 *
	 * This method is responsible for rendering plain content when the widget is viewed
	 * in the Elementor editor's plain text mode.
	 */
	public function render_plain_content() {}
}
