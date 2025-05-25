<?php
/**
 * RAEL Product Carousel widget.
 *
 * @package Responsive_Addons_For_Elementor
 * @subpackage WooCommerce
 *
 * @since 1.5.0
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Woocommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Responsive_Addons_For_Elementor\Traits\Missing_Dependency;
use Responsive_Addons_For_Elementor\Helper\Helper;

/**
 * RAEL Product Carousel widget class.
 *
 * @since 1.5.0
 */
class Responsive_Addons_For_Elementor_Product_Carousel extends Widget_Base {
	use Missing_Dependency;
	/**
	 * Constructor for the RAE Product Carousel widget class.
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
			Helper::register_woo_actions();
			$this->load_quick_view_assets();
		}
	}
	/**
	 * Get the name of the RAE Product Carousel widget.
	 *
	 * @return string The name of the widget.
	 */
	public function get_name() {
		return 'rael-product-carousel';
	}
	/**
	 * Get the title of the RAE Product Carousel widget.
	 *
	 * @return string The title of the widget.
	 */
	public function get_title() {
		return esc_html__( 'Product Carousel', 'responsive-addons-for-elementor' );
	}
	/**
	 * Get the icon of the RAE Product Carousel widget.
	 *
	 * @return string The icon HTML markup.
	 */
	public function get_icon() {
		return 'rael-badge eicon-carousel';
	}
	/**
	 * Get the categories of the RAE Product Carousel widget.
	 *
	 * @return array The categories of the widget.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}
	/**
	 * Get the stylesheets required for the widget.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'swiper',
			'e-swiper',	
		);
	}
	/**
	 * Get the keywords of the RAE Product Carousel widget.
	 *
	 * @return array The keywords associated with the widget.
	 */
	public function get_keywords() {
		return array(
			'woo',
			'woocommerce',
			'product carousel',
			'product gallery',
			'products',
			'carousel',
			'rael',
		);
	}
	/**
	 * Get the custom help URL for the RAE Product Carousel widget.
	 *
	 * @return string The custom help URL.
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/product-carousel';
	}
	/**
	 * Load assets required for quick view functionality.
	 *
	 * @return void
	 */
	protected function load_quick_view_assets() {
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
						if ( false === has_action( 'wp_footer', 'woocommerce_photoswipe' ) ) {
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
	 * Get the available options for product orderby.
	 *
	 * @return array The available options for product orderby.
	 */
	protected function get_product_orderby_options() {
		return apply_filters(
			'rael/widgets/woo-product-carousel/orderby-options',
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
	 * Get the available options for product filterby.
	 *
	 * @return array The available options for product filterby.
	 */
	protected function get_product_filterby_options() {
		return apply_filters(
			'rael/widgets/woo-product-carousel/filterby-options',
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
	 * Build the product query based on widget settings.
	 *
	 * @return array The arguments for the product query.
	 */
	public function product_query_builder() {
		$settings                      = $this->get_settings_for_display();
		$widget_id                     = $this->get_id();
		$settings['rael_pc_widget_id'] = $widget_id;
		$order_by                      = $settings['rael_pc_orderby'];
		$filter                        = $settings['rael_pc_product_filter'];
		$args                          = array(
			'post_type'      => 'product',
			'post_status'    => array( 'publish', 'pending', 'future' ),
			'posts_per_page' => $settings['rael_pc_products_count'] ?: 4,
			'order'          => $settings['rael_pc_order'],
			'offset'         => $settings['rael_pc_product_offset'],
			'tax_query'      => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => array( 'exclude-from-search', 'exclude-from-catalog' ),
					'operator' => 'NOT IN',
				),
			),
		);

		if ( $order_by == '_price' || $order_by == '_sku' ) {
			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = $order_by;
		} else {
			$args['orderby'] = $order_by;
		}

		if ( $filter == 'featured_products' ) {
			$count                       = isset( $args['tax_query'] ) ? count( $args['tax_query'] ) : 0;
			$args['tax_query'][ $count ] =
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
				);
		}

		if ( $filter == 'best_selling_products' ) {
			$args['meta_key'] = 'total_sales';
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'DESC';
		}

		if ( $filter == 'top_products' ) {
			$args['meta_key'] = '_wc_average_rating';
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'DESC';
		}

		if ( get_option( 'woocommerce_hide_out_of_stock_items' ) == 'yes' ) {
			$args['meta_query']   = array( 'relation' => 'AND' );
			$args['meta_query'][] = array(
				'key'   => '_stock_status',
				'value' => 'instock',
			);
		}

		if ( $filter == 'sale_products' ) {
			$count                        = isset( $args['meta_query'] ) ? count( $args['meta_query'] ) : 0;
			$args['meta_query'][ $count ] = array(
				'relation' => 'OR',
				array(
					'key'     => '_sale_price',
					'value'   => 0,
					'compare' => '>',
					'type'    => 'numeric',
				),
				array(
					'key'     => '_min_variation_sale_price',
					'value'   => 0,
					'compare' => '>',
					'type'    => 'numeric',
				),
			);
		}

		$taxonomies      = get_taxonomies( array( 'object_type' => array( 'product' ) ), 'objects' );
		$tax_query_count = isset( $args['meta_query'] ) ? count( $args['meta_query'] ) : 0;
		foreach ( $taxonomies as $object ) {
			$setting_key = $object->name . '_ids';
			if ( ! empty( $settings[ $setting_key ] ) ) {
				$args['tax_query'][ $tax_query_count ] = array(
					'taxonomy' => $object->name,
					'field'    => 'term_id',
					'terms'    => $settings[ $setting_key ],
				);
			}
			$tax_query_count++;
		}

		return $args;
	}
	/**
	 * Register controls for the widget.
	 *
	 * @return void
	 */
	protected function register_controls() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			$this->register_content_tab_missing_dep_warning_controls( 'WooCommerce', 'woocommerce' );
			return;
		}

		// Content Tab.
		$this->register_content_tab_layout_settings_section();
		$this->register_content_tab_carousel_settings_section();
		$this->register_content_tab_query_section();
		$this->register_content_tab_product_badges_section();

		// Style Tab.
		$this->register_style_tab_products_section();
		$this->register_style_tab_color_typography_section();
		$this->register_style_tab_button_section();
		$this->register_style_tab_popup_section();
		$this->register_style_tab_dots_section();
		$this->register_style_tab_image_dots_section();
		$this->register_style_tab_arrows_section();
	}
	/**
	 * Register controls for the Content tab layout settings section.
	 *
	 * @return void
	 */
	protected function register_content_tab_layout_settings_section() {
		$this->start_controls_section(
			'rael_pc_content_tab_layout_settings_section',
			array(
				'label' => __( 'Layout Settings', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_pc_dynamic_template_layout',
			array(
				'label'   => __( 'Layout', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'preset_1',
				'options' => array(
					'preset_1' => __( 'Preset 1', 'responsive-addons-for-elementor' ),
					'preset_2' => __( 'Preset 2', 'responsive-addons-for-elementor' ),
					'preset_3' => __( 'Preset 3', 'responsive-addons-for-elementor' ),
					'preset_4' => __( 'Preset 4', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_pc_show_title',
			array(
				'label'        => __( 'Show Title', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'rael_pc_title_tag',
			array(
				'label'     => __( 'Title Tag', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h2',
				'options'   => array(
					'h1'   => __( 'H1', 'responsive-addons-for-elementor' ),
					'h2'   => __( 'H2', 'responsive-addons-for-elementor' ),
					'h3'   => __( 'H3', 'responsive-addons-for-elementor' ),
					'h4'   => __( 'H4', 'responsive-addons-for-elementor' ),
					'h5'   => __( 'H5', 'responsive-addons-for-elementor' ),
					'h6'   => __( 'H6', 'responsive-addons-for-elementor' ),
					'span' => __( 'Span', 'responsive-addons-for-elementor' ),
					'p'    => __( 'P', 'responsive-addons-for-elementor' ),
					'div'  => __( 'Div', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_pc_show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_pc_title_length',
			array(
				'label'     => __( 'Title Length', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'condition' => array(
					'rael_pc_show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_pc_rating',
			array(
				'label'        => __( 'Show Product Rating?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'rael_pc_price',
			array(
				'label'        => __( 'Show Product Price?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'rael_pc_excerpt',
			array(
				'label'        => __( 'Show Description?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'rael_pc_excerpt_length',
			array(
				'label'     => __( 'Excerpt Length', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '10',
				'condition' => array(
					'rael_pc_excerpt' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_pc_excerpt_expansion_indicator',
			array(
				'label'       => __( 'Expansion Indicator', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
				'default'     => '...',
				'condition'   => array(
					'rael_pc_excerpt' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'        => 'rael_pc_image_size',
				'exclude'     => array( 'custom' ),
				'default'     => 'medium',
				'label_block' => true,
			)
		);

		$this->add_control(
			'rael_pc_image_stretch',
			array(
				'label'        => __( 'Image Stretch', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'rael_pc_not_found_msg',
			array(
				'label'     => __( 'Not Found Message', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Products Not Found', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_pc_quick_view',
			array(
				'label'        => __( 'Show Quick View?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'rael_pc_quick_view_title_tag',
			array(
				'label'     => __( 'Quick view Title Tag', 'responsive-addons-for-elementor' ),
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
					'span' => __( 'Span', 'responsive-addons-for-elementor' ),
					'p'    => __( 'P', 'responsive-addons-for-elementor' ),
					'div'  => __( 'Div', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_pc_quick_view' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_pc_image_clickable',
			array(
				'label'        => __( 'Image Clickable?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register controls for the carousel settings section.
	 *
	 * @return void
	 */
	protected function register_content_tab_carousel_settings_section() {
		$this->start_controls_section(
			'rael_pc_content_tab_carousel_settings_section',
			array(
				'label' => __( 'Carousel Settings', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_pc_carousel_effect',
			array(
				'label'       => __( 'Effect', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'description' => __( 'Sets transition effect', 'responsive-addons-for-elementor' ),
				'default'     => 'slide',
				'options'     => array(
					'slide'     => __( 'Slide', 'responsive-addons-for-elementor' ),
					'coverflow' => __( 'Coverflow', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_pc_items',
			array(
				'label'          => __( 'Visible Product', 'responsive-addons-for-elementor' ),
				'type'           => Controls_Manager::SELECT,
				'options'        => array(
					'1' => __( '1', 'responsive-addons-for-elementor' ),
					'2' => __( '2', 'responsive-addons-for-elementor' ),
					'3' => __( '3', 'responsive-addons-for-elementor' ),
					'4' => __( '4', 'responsive-addons-for-elementor' ),
					'5' => __( '5', 'responsive-addons-for-elementor' ),
					'6' => __( '6', 'responsive-addons-for-elementor' ),
				),
				'default'        => 3,
				'tablet_default' => 2,
				'mobile_default' => 1,
				'condition'      => array(
					'rael_pc_carousel_effect' => 'slide',
				),
			)
		);

		$this->add_control(
			'rael_pc_carousel_rotate',
			array(
				'label'     => __( 'Rotate', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array( 'size' => 50 ),
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'condition' => array(
					'rael_pc_carousel_effect' => 'coverflow',
				),
			)
		);
		$this->add_control(
			'rael_pc_carousel_depth',
			array(
				'label'     => __( 'Depth', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array( 'size' => 100 ),
				'range'     => array(
					'px' => array(
						'min'  => 100,
						'max'  => 1000,
						'step' => 10,
					),
				),
				'condition' => array(
					'rael_pc_carousel_effect' => 'coverflow',
				),
			)
		);
		$this->add_control(
			'rael_pc_carousel_stretch',
			array(
				'label'     => __( 'Stretch', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array( 'size' => 10 ),
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 10,
					),
				),
				'condition' => array(
					'rael_pc_carousel_effect' => 'coverflow',
				),
			)
		);

		$this->add_responsive_control(
			'rael_pc_margin',
			array(
				'label'      => __( 'Items Gap', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 10 ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => '',
			)
		);

		$this->add_control(
			'rael_pc_slider_speed',
			array(
				'label'       => __( 'Speed', 'responsive-addons-for-elementor' ),
				'description' => __( 'Duration of transition between slides (in ms)', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array( 'size' => 400 ),
				'range'       => array(
					'px' => array(
						'min'  => 100,
						'max'  => 3000,
						'step' => 1,
					),
				),
				'size_units'  => '',
			)
		);

		$this->add_control(
			'rael_pc_autoplay',
			array(
				'label'        => __( 'Autoplay', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_pc_autoplay_speed',
			array(
				'label'      => __( 'Autoplay Speed', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 2000 ),
				'range'      => array(
					'px' => array(
						'min'  => 500,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'size_units' => '',
				'condition'  => array(
					'rael_pc_autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_pc_pause_on_hover',
			array(
				'label'        => __( 'Pause On Hover', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'condition'    => array(
					'rael_pc_autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_pc_infinite_loop',
			array(
				'label'        => __( 'Infinite Loop', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_pc_grab_cursor',
			array(
				'label'        => __( 'Grab Cursor', 'responsive-addons-for-elementor' ),
				'description'  => __( 'Shows grab cursor when you hover over the slider', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_pc_navigation_heading',
			array(
				'label'     => __( 'Navigation', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_pc_arrows',
			array(
				'label'        => __( 'Arrows', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_pc_dots',
			array(
				'label'        => __( 'Dots', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_pc_image_dots',
			array(
				'label'        => __( 'Image Dots', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'condition'    => array(
					'rael_pc_dots' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_pc_image_dots_visibility',
			array(
				'label'        => __( 'Image Dots Visibility', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'rael_pc_dots'       => 'yes',
					'rael_pc_image_dots' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_pc_direction',
			array(
				'label'     => __( 'Direction', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left'  => __( 'Left', 'responsive-addons-for-elementor' ),
					'right' => __( 'Right', 'responsive-addons-for-elementor' ),
				),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register controls for the query section.
	 *
	 * @return void
	 */
	protected function register_content_tab_query_section() {
		$this->start_controls_section(
			'rael_pc_content_tab_query_section',
			array(
				'label' => __( 'Query', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_pc_product_filter',
			array(
				'label'   => __( 'Filter By', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'recent_products',
				'options' => $this->get_product_filterby_options(),
			)
		);

		$this->add_control(
			'rael_pc_orderby',
			array(
				'label'   => __( 'Order By', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_product_orderby_options(),
				'default' => 'date',
			)
		);

		$this->add_control(
			'rael_pc_order',
			array(
				'label'   => __( 'Order', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'asc'  => 'Ascending',
					'desc' => 'Descending',
				),
				'default' => 'desc',
			)
		);

		$this->add_control(
			'rael_pc_products_count',
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
			'rael_pc_product_offset',
			array(
				'label'   => __( 'Offset', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
			)
		);

		$taxonomies = get_taxonomies( array( 'object_type' => array( 'product' ) ), 'objects' );
		foreach ( $taxonomies as $taxonomy => $object ) {
			if ( ! isset( $object->object_type[0] ) ) {
				continue;
			}

			$this->add_control(
				$taxonomy . '_ids',
				array(
					'label'       => $object->label,
					'type'        => Controls_Manager::SELECT2,
					'label_block' => true,
					'multiple'    => true,
					'object_type' => $taxonomy,
					'options'     => wp_list_pluck( get_terms( $taxonomy ), 'name', 'term_id' ),
				)
			);
		}

		$this->end_controls_section();
	}
	/**
	 * Register controls for the product badges section.
	 *
	 * @return void
	 */
	protected function register_content_tab_product_badges_section() {
		$this->start_controls_section(
			'rael_pc_content_tab_product_badges_section',
			array(
				'label' => __( 'Sale / Stock Out Badge', 'responsive-addons-for-elementor' ),

			)
		);
		$this->add_control(
			'rael_pc_sale_badge_preset',
			array(
				'label'   => esc_html__( 'Style Preset', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'sale_preset_5',
				'options' => array(
					'sale_preset_5' => esc_html__( 'Preset 1', 'responsive-addons-for-elementor' ),
					'sale_preset_2' => esc_html__( 'Preset 2', 'responsive-addons-for-elementor' ),
					'sale_preset_3' => esc_html__( 'Preset 3', 'responsive-addons-for-elementor' ),
					'sale_preset_4' => esc_html__( 'Preset 4', 'responsive-addons-for-elementor' ),
					'sale_preset_1' => esc_html__( 'Preset 5', 'responsive-addons-for-elementor' ),

				),
			)
		);

		$this->add_control(
			'rael_pc_sale_badge_alignment',
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
					'rael_pc_dynamic_template_layout!' => 'preset_2',
				),
			)
		);

		$this->add_control(
			'rael_pc_sale_text',
			array(
				'label'     => __( 'Sale Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'rael_pc_stockout_text',
			array(
				'label' => __( 'Stock Out Text', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register controls for the products section in the style tab.
	 *
	 * @return void
	 */
	protected function register_style_tab_products_section() {
		$this->start_controls_section(
			'rael_style_tab_products_section',
			array(
				'label' => __( 'Products', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_pc_alignment',
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
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .rael-product-carousel .rael-pc__product-details-wrapper' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'rael_pc_dynamic_template_layout' => 'preset_3',
				),
			)
		);

		$this->add_control(
			'rael_pc_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-product-carousel-container .rael-product-carousel' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_pc_dynamic_template_layout!' => array( 'preset_2', 'preset_4' ),
				),
			)
		);

		$this->add_control(
			'rael_pc_overlay_color',
			array(
				'label'       => __( 'Overlay Color', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'Use opacity color for overlay design.', 'responsive-addons-for-elementor' ),
				'selectors'   => array(
					'{{WRAPPER}} .rael-product-carousel-container .rael-product-carousel .rael-product-carousel__overlay' => 'background: {{VALUE}};',
				),
				'condition'   => array(
					'rael_pc_dynamic_template_layout' => array( 'preset_2', 'preset_4' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_pc_carousel_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-product-carousel' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'rael_pc_products_tabs' );

		$this->start_controls_tab(
			'rael_pc_products_tabs_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'rael_pc_products_border_normal',
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
				'selector'       => '{{WRAPPER}} .rael-product-carousel',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_pc_products_shadow_normal',
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-product-carousel',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_pc_products_tabs_hover',
			array(
				'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_pc_products_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-product-carousel:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_pc_products_border_normal_border!' => '',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_pc_products_box_shadow_hover',
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-product-carousel:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'rael_pc_products_border_radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .rael-product-carousel' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
					'{{WRAPPER}} .rael-product-carousel .rael-pc__product-image img, {{WRAPPER}} .rael-product-carousel > .rael-pc__product-image-wrapper'
														 => 'border-radius: {{TOP}}px {{RIGHT}}px 0 0;',
				),
			)
		);

		$this->add_control(
			'rael_pc_products_details_heading',
			array(
				'label'     => __( 'Product Details', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'rael_pc_products_inner_padding',
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
					'{{WRAPPER}} .rael-product-carousel .rael-pc__product-details-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Registers the settings section for styling color and typography options.
	 */
	protected function register_style_tab_color_typography_section() {

		$this->start_controls_section(
			'raels_pc_styler_tab_color_typography_section',
			array(
				'label' => __( 'Color &amp; Typography', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_pc_product_title_heading',
			array(
				'label' => __( 'Product Title', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_pc_product_title_color',
			array(
				'label'     => __( 'Product Title Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-product-carousel .woocommerce-loop-product__title, {{WRAPPER}} .rael-product-carousel .rael-pc__product-title *' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_pc_product_title_typography',
				'selector' => '{{WRAPPER}} .rael-product-carousel .woocommerce-loop-product__title, {{WRAPPER}} .rael-product-carousel .rael-pc__product-title *',
			)
		);

		$this->add_control(
			'rael_pc_product_price_heading',
			array(
				'label' => __( 'Product Price', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_pc_price_color',
			array(
				'label'     => __( 'Product Price Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-product-carousel .rael-pc_product-price, {{WRAPPER}} .rael-product-carousel .rael-pc__product-price' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_pc_product_price_typography',
				'selector' => '{{WRAPPER}} .rael-product-carousel .rael-pc__product-price',
			)
		);

		$this->add_control(
			'rael_pc_rating_heading',
			array(
				'label' => __( 'Star Rating', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_pc_rating_color',
			array(
				'label'     => __( 'Rating Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f2b01e',
				'selectors' => array(
					'{{WRAPPER}} .rael-product-carousel .star-rating::before'      => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-product-carousel .star-rating span::before' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_pc_rating_size',
			array(
				'label'     => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'default'   => array(
					'size' => 14,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-product-carousel-container .woocommerce ul.products .product .star-rating' => 'font-size: {{SIZE}}px !important;',
				),

			)
		);

		$this->add_control(
			'rael_pc_desc_heading',
			array(
				'label'     => __( 'Product Description', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'rael_pc_excerpt' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_pc_desc_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-product-carousel .rael-pc__product-excerpt' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-product-carousel .rael-pc__excerpt' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_pc_excerpt' => 'yes',
				),
			)
		);

		// For Preset1 Only.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_pc_desc_preset1_typography',
				'selector'  => '{{WRAPPER}} .rael-product-carousel .rael-pc__product-excerpt',
				'condition' => array(
					'rael_pc_excerpt'                 => 'yes',
					'rael_pc_dynamic_template_layout' => 'preset_1',
				),
			)
		);

		// For Preset 2,3 & 4.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_pc_desc_typography',
				'selector'  => '{{WRAPPER}} .rael-product-carousel .rael-pc__excerpt',
				'condition' => array(
					'rael_pc_excerpt'                  => 'yes',
					'rael_pc_dynamic_template_layout!' => 'preset_1',
				),
			)
		);

		$this->add_control(
			'rael_pc_sale_badge_heading',
			array(
				'label' => __( 'Sale Badge', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_pc_sale_badge_color',
			array(
				'label'     => __( 'Sale Badge Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .onsale, {{WRAPPER}} .woocommerce ul.products li.product .rael-pc__onsale' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_pc_sale_badge_background',
			array(
				'label'     => __( 'Sale Badge Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-product-carousel .onsale, {{WRAPPER}} .rael-product-carousel .rael-pc__onsale' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-product-carousel .rael-pc__onsale:not(.rael-pc__out-of-stock).sale_preset_4:after'        => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_pc_sale_badge_typography',
				'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .onsale, {{WRAPPER}} .woocommerce ul.products li.product .rael-pc__onsale:not(.rael-pc__out-of-stock)',
			)
		);

		// stock out badge.
		$this->add_control(
			'rael_pc_stock_out_badge_heading',
			array(
				'label' => __( 'Stock Out Badge', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_pc_stock_out_badge_color',
			array(
				'label'     => __( 'Stock Out Badge Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .outofstock-badge, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale.outofstock' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_pc_stock_out_badge_background',
			array(
				'label'     => __( 'Stock Out Badge Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff2a13',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce ul.products li.product .outofstock-badge, {{WRAPPER}} .woocommerce ul.products li.product .rael-pc__onsale.rael-pc__out-of-stock' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce ul.products li.product .rael-pc__onsale.rael-pc__out-of-stock.sale_preset_4:after'                                                => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_pc_stock_out_badge_typo',
				'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .outofstock-badge, {{WRAPPER}} .woocommerce ul.products li.product .rael-pc__onsale.rael-pc__out-of-stock',
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Registers the settings section for styling button options.
	 */
	protected function register_style_tab_button_section() {
		$this->start_controls_section(
			'rael_pc_style_tab_button_section',
			array(
				'label' => __( 'Button', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_pc_product_button_width',
			array(
				'label'     => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-product-carousel .rael-pc__icons-wrapper li a' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
				),
				'condition' => array(
					'rael_pc_dynamic_template_layout!' => 'preset_3',
				),
			)
		);

		$this->add_control(
			'rael_pc_product_button_height',
			array(
				'label'     => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-product-carousel .rael-pc__icons-wrapper' => 'height: {{SIZE}}px;',
				),
				'condition' => array(
					'rael_pc_dynamic_template_layout' => 'preset_3',
				),
			)
		);

		$this->add_control(
			'rael_pc_product_button_icon_size',
			array(
				'label'     => __( 'Icons Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-product-carousel .rael-pc__icons-wrapper li a i, {{WRAPPER}} .rael-product-carousel .rael-pc__icons-wrapper li.add-to-cart a:before' => 'font-size: {{SIZE}}px;',
				),
			)
		);

		$this->add_control(
			'rael_pc_product_button_preset3_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-product-carousel .rael-pc__icons-wrapper li' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_pc_dynamic_template_layout' => 'preset_3',
				),
			)
		);

		$this->start_controls_tabs( 'rael_pc_product_button_state_tabs' );

		$this->start_controls_tab(
			'rael_pc_product_button_tab_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_pc_product_button_color_normal',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-product-carousel .rael-pc__icons-wrapper li a' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'rael_pc_product_button_background_normal',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-product-carousel .rael-pc__icons-wrapper.rael-pc-block-style' => 'background-color: {{VALUE}} !important;',
					'{{WRAPPER}} .rael-product-carousel .rael-pc__icons-wrapper li a'        => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'rael_pc_product_button_border_normal',
				'selector'  => '{{WRAPPER}} .rael-product-carousel .button.add_to_cart_button, {{WRAPPER}} .rael-product-carousel .rael-pc__icons-wrapper li a',
				'condition' => array(
					'rael_pc_dynamic_template_layout!' => 'preset_3',
				),
			)
		);
		$this->add_control(
			'rael_pc_product_button_border_radius_normal',
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
					'{{WRAPPER}} .rael-product-carousel .rael-pc__icons-wrapper:not(.details-block-style-2) li a'       => 'border-radius: {{SIZE}}px;',
					'{{WRAPPER}} .rael-product-carousel .rael-pc__icons-wrapper.details-block-style-2 li:only-child a'  => 'border-radius: {{SIZE}}px!important;',
					'{{WRAPPER}} .rael-product-carousel .rael-pc__icons-wrapper.details-block-style-2 li:first-child a' => 'border-radius: {{SIZE}}px 0 0 {{SIZE}}px;',
					'{{WRAPPER}} .rael-product-carousel .rael-pc__icons-wrapper.details-block-style-2 li:last-child a'  => 'border-radius: 0 {{SIZE}}px {{SIZE}}px 0;',
				),
				'condition' => array(
					'rael_pc_dynamic_template_layout!' => 'preset_3',
				),
			)
		);

		$this->add_control(
			'rael_pc_product_button_top_spacing',
			array(
				'label'     => __( 'Top Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-product-carousel .rael-pc__icons-wrapper' => 'margin-top: {{SIZE}}px;',
				),
				'condition' => array(
					'rael_pc_dynamic_template_layout' => 'preset_4',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_pc_product_button_tab_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_pc_product_button_color_hover',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-product-carousel .rael-pc__icons-wrapper li a:hover' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'rael_pc_product_button_background_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-product-carousel .rael-pc__icons-wrapper li a:hover' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'rael_pc_product_button_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-product-carousel .rael-pc__icons-wrapper li a:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_pc_product_button_border_normal_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}
	/**
	 * Register the style settings for the Popup section.
	 *
	 * This method defines various controls for styling the elements within the Popup section, such as title, price, content, review, SKU, close button, etc.
	 * It utilizes Elementor's Controls_Manager to provide a user-friendly interface for customizing the appearance of the Popup.
	 */
	protected function register_style_tab_popup_section() {
		$this->start_controls_section(
			'rael_pc_style_tab_popup_section',
			array(
				'label' => __( 'Popup', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_pc_popup_title',
			array(
				'label' => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_pc_popup_title_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '.rael-pc__popup-details-render{{WRAPPER}} div.product .product_title',
			)
		);

		$this->add_control(
			'rael_pc_popup_title_color',
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
			'rael_pc_popup_price',
			array(
				'label'     => __( 'Price', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_pc_popup_price_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '.rael-pc__popup-details-render{{WRAPPER}} div.product .price',
			)
		);

		$this->add_control(
			'rael_pc_popup_price_color',
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
			'rael_pc_popup_sale_price_color',
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
			'rael_pc_popup_content',
			array(
				'label'     => __( 'Content', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_pc_popup_content_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '.rael-pc__popup-details-render{{WRAPPER}} div.product .woocommerce-product-details__short-description',
			)
		);

		$this->add_control(
			'rael_pc_popup_content_color',
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
			'rael_pc_popup_review_color',
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
			'rael_pc_popup_review_link_color',
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
			'rael_pc_popup_review_link_hover',
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
			'rael_pc_popup_table_border_color',
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
			'rael_pc_popup_sale_style',
			array(
				'label'     => __( 'Sale', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_pc_popup_sale_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '.rael-pc__popup-details-render{{WRAPPER}} .rael-pc__onsale',
			)
		);
		$this->add_control(
			'rael_pc_popup_sale_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} .rael-pc__onsale' => 'color: {{VALUE}}!important;',
				),
			)
		);
		$this->add_control(
			'rael_pc_popup_sale_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} .rael-pc__onsale' => 'background-color: {{VALUE}}!important;',
					'.rael-pc__popup-details-render{{WRAPPER}} .rael-pc__onsale:not(.rael-pc__out-of-stock).sale_preset_4:after'        => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
				),
			)
		);

		// Quantity.
		$this->add_control(
			'rael_pc_popup_quantity',
			array(
				'label'     => __( 'Quantity', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_pc_popup_quantity_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '.rael-pc__popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty, {{WRAPPER}} .rael-pc__product-popup.woocommerce div.product form.cart div.quantity > a',
			)
		);

		$this->add_control(
			'rael_pc_popup_quantity_color',
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
			'rael_pc_popup_quantity_bg_color',
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
			'rael_pc_popup_quantity_border_color',
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
			'rael_pc_popup_cart_button',
			array(
				'label'     => __( 'Cart Button', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_pc_popup_cart_button_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '.rael-pc__popup-details-render{{WRAPPER}} .button, .rael-pc__popup-details-render{{WRAPPER}} button.button.alt',
			)
		);

		$this->start_controls_tabs( 'rael_pc_popup_cart_button_style_tabs' );

		$this->start_controls_tab(
			'rael_pc_popup_cart_button_style_tab_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_pc_popup_cart_button_color_normal',
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
			'rael_pc_popup_cart_button_background',
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
				'name'     => 'rael_pc_popup_cart_button_border',
				'selector' => '.rael-pc__popup-details-render{{WRAPPER}} .button, .rael-pc__popup-details-render{{WRAPPER}} button.button.alt',
			)
		);
		$this->add_control(
			'rael_pc_popup_cart_button_border_radius',
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
			'rael_pc_popup_cart_button_style_tab_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_pc_popup_cart_button_color_hover',
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
			'rael_pc_popup_cart_button_background_hover',
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
			'rael_pc_popup_cart_button_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} .button:hover, .rael-pc__popup-details-render{{WRAPPER}} button.button.alt:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_pc_popup_cart_button_border_normal_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// SKU.
		$this->add_control(
			'rael_pc_popup_sku_style',
			array(
				'label'     => __( 'SKU', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_pc_popup_sku_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '.rael-pc__popup-details-render{{WRAPPER}} .product_meta',
			)
		);
		$this->add_control(
			'rael_pc_popup_sku_title_color',
			array(
				'label'     => __( 'Title Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} .product_meta' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_pc_popup_sku_content_color',
			array(
				'label'     => __( 'Content Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} .product_meta .sku, .rael-pc__popup-details-render{{WRAPPER}} .product_meta a' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_pc_popup_sku_color_hover',
			array(
				'label'     => __( 'Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} .product_meta a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_pc_popup_close_button_style',
			array(
				'label'     => __( 'Close Button', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'rael_pc_popup_close_button_icon_size',
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
			'rael_pc_popup_close_button_size',
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
			'rael_pc_popup_close_button_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} button.rael-pc__product-popup-close' => 'color: {{VALUE}}!important;',
				),
			)
		);

		$this->add_control(
			'rael_pc_popup_close_button_bg',
			array(
				'label'     => __( 'Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.rael-pc__popup-details-render{{WRAPPER}} button.rael-pc__product-popup-close' => 'background-color: {{VALUE}}!important;',
				),
			)
		);

		$this->add_control(
			'rael_pc_popup_close_button_border_radius',
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
				'name'     => 'rael_pc_popup_close_button_box_shadow',
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '.rael-pc__popup-details-render{{WRAPPER}} button.rael-pc__product-popup-close',
			)
		);

		$this->add_responsive_control(
			'rael_pc_popup_border_radius',
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
				'name'     => 'rael_pc_popup_background',
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
				'name'     => 'rael_pc_popup_box_shadow',
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '.rael-pc__popup-details-render{{WRAPPER}}.rael-pc__product-popup-details',
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register styles for the dots section.
	 */
	protected function register_style_tab_dots_section() {
		$this->start_controls_section(
			'rael_pc_style_tab_dots_section',
			array(
				'label'     => __( 'Dots', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_pc_dots' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_pc_dots_preset',
			array(
				'label'   => __( 'Preset', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'dots-preset-1' => __( 'Preset 1', 'responsive-addons-for-elementor' ),
					'dots-preset-2' => __( 'Preset 2', 'responsive-addons-for-elementor' ),
					'dots-preset-3' => __( 'Preset 3', 'responsive-addons-for-elementor' ),
					'dots-preset-4' => __( 'Preset 4', 'responsive-addons-for-elementor' ),
				),
				'default' => 'dots-preset-1',
			)
		);

		$this->add_control(
			'rael_pc_dots_position',
			array(
				'label'   => __( 'Position', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'inside'  => __( 'Inside', 'responsive-addons-for-elementor' ),
					'outside' => __( 'Outside', 'responsive-addons-for-elementor' ),
				),
				'default' => 'outside',
			)
		);

		$this->add_control(
			'rael_pc_use_dots_custom_width_height',
			array(
				'label'        => __( 'Use Custom Width/Height?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_responsive_control(
			'rael_pc_dots_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 2,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'rael_pc_use_dots_custom_width_height' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_pc_dots_height',
			array(
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 2,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'rael_pc_use_dots_custom_width_height' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_pc_dots_size',
			array(
				'label'      => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 2,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'rael_pc_use_dots_custom_width_height' => '',
					'rael_pc_dots_preset!'                 => 'dots-preset-1',
				),
			)
		);

		$this->add_responsive_control(
			'rael_dots_spacing',
			array(
				'label'      => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->start_controls_tabs( 'rael_pc_dots_style_tabs' );

		$this->start_controls_tab(
			'rael_pc_dots_tab_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_pc_dots_color_normal',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:not(.swiper-pagination-bullet-active)' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'rael_pc_dots_border_normal',
				'label'       => __( 'Border', 'responsive-addons-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet',
			)
		);

		$this->add_control(
			'rael_pc_dots_border_radius_normal',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_pc_dots_padding_normal',
			array(
				'label'              => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px', 'em', '%' ),
				'allowed_dimensions' => 'vertical',
				'placeholder'        => array(
					'top'    => '',
					'right'  => 'auto',
					'bottom' => '',
					'left'   => 'auto',
				),
				'selectors'          => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullets' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_pc_dots_tab_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_pc_dots_color_hover',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_pc_dots_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_pc_tab_dots_active',
			array(
				'label' => __( 'Active', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_pc_active_dot_color_normal',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'background: {{VALUE}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'rael_pc_active_dots_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 2,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'rael_pc_active_dots_height',
			array(
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 2,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'rael_pc_active_dots_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_pc_active_dots_shadow',
				'label'    => __( 'Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}
	/**
	 * Register styles for the image dots section.
	 */
	protected function register_style_tab_image_dots_section() {
		$this->start_controls_section(
			'rael_style_tab_image_dots_section',
			array(
				'label'     => __( 'Images Dots', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_pc_image_dots' => 'yes',
				),
			)
		);
		$this->add_responsive_control(
			'rael_pc_image_dots_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 350,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-pc__gallery-pagination' => 'width: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);
		$this->add_responsive_control(
			'rael_pc_image_dots_height',
			array(
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 100,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-pc__gallery-pagination' => 'height: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'rael_pc_image_dots_image_size',
			array(
				'label'      => __( 'Image Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 100,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-pc__gallery-pagination img' => 'width: {{SIZE}}{{UNIT}} !important;height: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);
		$this->add_control(
			'rael_pc_image_dots_image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-pc__gallery-pagination img' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register styles for the arrows section.
	 */
	protected function register_style_tab_arrows_section() {
		$this->start_controls_section(
			'rael_pc_style_tab_arrows_section',
			array(
				'label'     => __( 'Arrows', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_pc_arrows' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_pc_arrow',
			array(
				'label'       => __( 'Choose Arrow', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'default'     => 'fa fa-angle-right',
				'options'     => array(
					'fa fa-angle-right'          => __( 'Angle', 'responsive-addons-for-elementor' ),
					'fa fa-angle-double-right'   => __( 'Double Angle', 'responsive-addons-for-elementor' ),
					'fa fa-chevron-right'        => __( 'Chevron', 'responsive-addons-for-elementor' ),
					'fa fa-chevron-circle-right' => __( 'Chevron Circle', 'responsive-addons-for-elementor' ),
					'fa fa-arrow-right'          => __( 'Arrow', 'responsive-addons-for-elementor' ),
					'fa fa-long-arrow-right'     => __( 'Long Arrow', 'responsive-addons-for-elementor' ),
					'fa fa-caret-right'          => __( 'Caret', 'responsive-addons-for-elementor' ),
					'fa fa-caret-square-o-right' => __( 'Caret Square', 'responsive-addons-for-elementor' ),
					'fa fa-arrow-circle-right'   => __( 'Arrow Circle', 'responsive-addons-for-elementor' ),
					'fa fa-arrow-circle-o-right' => __( 'Arrow Circle O', 'responsive-addons-for-elementor' ),
					'fa fa-toggle-right'         => __( 'Toggle', 'responsive-addons-for-elementor' ),
					'fa fa-hand-o-right'         => __( 'Hand', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_pc_arrows_size',
			array(
				'label'      => __( 'Arrows Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => '40' ),
				'range'      => array(
					'px' => array(
						'min'  => 15,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_pc_arrows_icon_size',
			array(
				'label'      => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => '22' ),
				'range'      => array(
					'px' => array(
						'min'  => 15,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_pc_left_arrow_position',
			array(
				'label'      => __( 'Align Left Arrow', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 40,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_pc_right_arrow_position',
			array(
				'label'      => __( 'Align Right Arrow', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 40,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'rael_pc_arrows_style_tabs' );

		$this->start_controls_tab(
			'rael_pc_arrows_tab_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_pc_arrows_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_pc_arrows_color_normal',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'rael_pc_arrows_border_normal',
				'label'       => __( 'Border', 'responsive-addons-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev',
			)
		);

		$this->add_control(
			'rael_pc_arrows_border_radius_normal',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_pc_arrows_tab_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_pc_arrows_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_pc_arrows_color_hover',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_pc_arrows_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_pc_arrow_shadow',
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev',
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Render image dots based on provided arguments.
	 *
	 * @param array $args Query arguments for fetching posts.
	 */
	protected function render_image_dots( $args ) {
		$settings = $this->get_settings_for_display();

		$visibility = '';
		if ( 'yes' !== $settings['rael_pc_image_dots_visibility'] ) {
			$visibility .= ' rael-pc__gallery-pagination-hide-on--desktop';
		}
		if ( isset( $settings['rael_pc_image_dots_visibility_mobile'] ) && 'yes' !== $settings['rael_pc_image_dots_visibility_mobile'] ) {
			$visibility .= ' rael-pc__gallery-pagination-hide-on--mobile';
		}
		if ( isset( $settings['rael_pc_image_dots_visibility_tablet'] ) && 'yes' !== $settings['rael_pc_image_dots_visibility_tablet'] ) {
			$visibility .= ' rael-pc__gallery-pagination-hide-on--tablet';
		}

		$this->add_render_attribute(
			'rael_pc_pagination_wrapper',
			array(
				'class' => array(
					'swiper-container rael-pc__gallery-pagination',
					$visibility,
				),
			)
		);

		if ( 'yes' === $settings['rael_pc_image_dots'] ) : ?>

			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_pc_pagination_wrapper' ) ); ?>>

			<?php
			$query = new \WP_Query( $args );
			if ( $query->have_posts() ) {
				echo '<div class="swiper-wrapper">';
				while ( $query->have_posts() ) {
					$query->the_post();
					$image_arr = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
					if ( empty( $image_arr ) ) {
						$image_arr[0] = wc_placeholder_img_src( 'full' );
					}

					echo '<div class="swiper-slide">';
						echo '<div class="swiper-slide-container">';
							echo '<div class="rael-pc-pagination-thumb">';
								echo '<img class="rael-pc-thumbnail" src="' . esc_url( current( $image_arr ) ) . '">';
							echo '</div>';
						echo '</div>';
					echo '</div>';
				}
				wp_reset_postdata();
				echo '</div>';
			}
			?>

			</div>
			<?php
		endif;
	}
	/**
	 * Render image dots based on provided arguments.
	 */
	protected function render_dots() {
		$settings = $this->get_settings_for_display();

		if ( 'yes' === $settings['rael_pc_dots'] ) {
			?>
			<div class="swiper-pagination swiper-pagination-<?php echo esc_attr( $this->get_id() ) . ' ' . wp_kses_post( $settings['rael_pc_dots_preset'] );
			?>
			">
			</div>
			<?php
		}
	}
	/**
	 * Get the template file path.
	 *
	 * @param string $template_name The name of the template file.
	 * @return string The file path of the template.
	 */
	protected function get_template( $template_name ) {
		$template_name = sanitize_file_name( $template_name );
		if ( 'preset_1' === $template_name ) {
			$template_name = 'preset-1';
		} elseif ( 'preset_2' === $template_name ) {
			$template_name = 'preset-2';
		} elseif ( 'preset_3' === $template_name ) {
			$template_name = 'preset-3';
		} elseif ( 'preset_4' === $template_name ) {
			$template_name = 'preset-4';
		}
		$file_name = RAEL_DIR . 'includes/widgets-manager/widgets/skins/product-carousel/' . $template_name . '.php';
		return $file_name;
	}
		/**
		 * Render the product carousel widget.
		 */
	protected function render() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		// normalize for load more fix.
		$widget_id                     = $this->get_id();
		$settings['rael_pc_widget_id'] = $widget_id;

		$args = $this->product_query_builder();
		if ( Plugin::$instance->documents->get_current() ) {
			$this->page_id = Plugin::$instance->documents->get_current()->get_main_id();
		}

		$this->add_render_attribute(
			'rael_pc_container',
			array(
				'class'          => array(
					'swiper-container-wrap',
					'rael-product-carousel-container',
					$settings['rael_pc_dynamic_template_layout'],
				),
				'id'             => 'rael-product-carousel-' . esc_attr( $this->get_id() ),
				'data-widget-id' => $widget_id,
			)
		);

		if ( $settings['rael_pc_dots_position'] ) {
			$this->add_render_attribute(
				'rael_pc_container',
				'class',
				'swiper-container-wrap-dots-' . $settings['rael_pc_dots_position']
			);
		}

		$this->add_render_attribute(
			'rael_pc_wrapper',
			array(
				'class'           => array(
					'woocommerce',
					'swiper' . RAEL_SWIPER_CONTAINER,
					'rael-woo-product-carousel',
					'swiper-container-' . esc_attr( $this->get_id() ),
					'rael-product-appender-' . esc_attr( $this->get_id() ),
				),
				'data-pagination' => '.swiper-pagination-' . esc_attr( $this->get_id() ),
				'data-arrow-next' => '.swiper-button-next-' . esc_attr( $this->get_id() ),
				'data-arrow-prev' => '.swiper-button-prev-' . esc_attr( $this->get_id() ),
			)
		);

		if ( $settings['rael_pc_dynamic_template_layout'] ) {
			$this->add_render_attribute(
				'rael_pc_wrapper',
				'data-type',
				$settings['rael_pc_dynamic_template_layout']
			);
		}

		if ( $settings['rael_pc_image_stretch'] ) {
			$this->add_render_attribute( 'rael_pc_wrapper', 'class', 'swiper-image-stretch' );
		}

		if ( $settings['rael_pc_carousel_effect'] ) {
			$this->add_render_attribute( 'rael_pc_wrapper', 'data-effect', $settings['rael_pc_carousel_effect'] );
		}

		if ( 'slide' === $settings['rael_pc_carousel_effect'] ) {
			if ( ! empty( $settings['rael_pc_items'] ) ) {
				$this->add_render_attribute( 'rael_pc_wrapper', 'data-items', $settings['rael_pc_items'] );
			}
			if ( ! empty( $settings['rael_pc_items_tablet'] ) ) {
				$this->add_render_attribute( 'rael_pc_wrapper', 'data-items-tablet', $settings['rael_pc_items_tablet'] );
			}
			if ( ! empty( $settings['rael_pc_items_mobile'] ) ) {
				$this->add_render_attribute( 'rael_pc_wrapper', 'data-items-mobile', $settings['rael_pc_items_mobile'] );
			}
		}

		if ( 'coverflow' === $settings['rael_pc_carousel_effect'] ) {
			if ( ! empty( $settings['rael_pc_carousel_depth']['size'] ) ) {
				$this->add_render_attribute( 'rael_pc_wrapper', 'data-depth', $settings['rael_pc_carousel_depth']['size'] );
			}
			if ( ! empty( $settings['rael_pc_carousel_rotate']['size'] ) ) {
				$this->add_render_attribute( 'rael_pc_wrapper', 'data-rotate', $settings['rael_pc_carousel_rotate']['size'] );
			}
			if ( ! empty( $settings['rael_pc_carousel_stretch']['size'] ) ) {
				$this->add_render_attribute( 'rael_pc_wrapper', 'data-stretch', $settings['rael_pc_carousel_stretch']['size'] );
			}
		}

		if ( ! empty( $settings['rael_pc_margin']['size'] ) ) {
			$this->add_render_attribute( 'rael_pc_wrapper', 'data-margin', $settings['rael_pc_margin']['size'] );
		}
		if ( ! empty( $settings['rael_pc_margin_tablet']['size'] ) ) {
			$this->add_render_attribute( 'rael_pc_wrapper', 'data-margin-tablet', $settings['rael_pc_margin_tablet']['size'] );
		}
		if ( ! empty( $settings['rael_pc_margin_mobile']['size'] ) ) {
			$this->add_render_attribute( 'rael_pc_wrapper', 'data-margin-mobile', $settings['rael_pc_margin_mobile']['size'] );
		}

		if ( ! empty( $settings['rael_pc_slider_speed']['size'] ) ) {
			$this->add_render_attribute( 'rael_pc_wrapper', 'data-speed', $settings['rael_pc_slider_speed']['size'] );
		}

		if ( 'yes' === $settings['rael_pc_autoplay'] && ! empty( $settings['rael_pc_autoplay_speed']['size'] ) ) {
			$this->add_render_attribute( 'rael_pc_wrapper', 'data-autoplay', $settings['rael_pc_autoplay_speed']['size'] );
		} else {
			$this->add_render_attribute( 'rael_pc_wrapper', 'data-autoplay', '0' );
		}

		if ( 'yes' === $settings['rael_pc_pause_on_hover'] ) {
			$this->add_render_attribute( 'rael_pc_wrapper', 'data-pause-on-hover', 'true' );
		}

		if ( 'yes' === $settings['rael_pc_infinite_loop'] ) {
			$this->add_render_attribute( 'rael_pc_wrapper', 'data-loop', '1' );
		}
		if ( 'yes' === $settings['rael_pc_grab_cursor'] ) {
			$this->add_render_attribute( 'rael_pc_wrapper', 'data-grab-cursor', '1' );
		}
		if ( 'yes' === $settings['rael_pc_arrows'] ) {
			$this->add_render_attribute( 'rael_pc_wrapper', 'data-arrows', '1' );
		}
		if ( 'yes' === $settings['rael_pc_dots'] ) {
			$this->add_render_attribute( 'rael_pc_wrapper', 'data-dots', '1' );
		}

		if ( 'right' === $settings['rael_pc_direction'] ) {
			$this->add_render_attribute( 'rael_pc_wrapper', 'dir', 'rtl' );
		}

		$settings['rael_pc_title_tag']     = Helper::validate_html_tags( $settings['rael_pc_title_tag'] );
		$settings['rael_pc_sale_text']     = Helper::strip_tags_keeping_allowed_tags( $settings['rael_pc_sale_text'] );
		$settings['rael_pc_stockout_text'] = Helper::strip_tags_keeping_allowed_tags( $settings['rael_pc_stockout_text'] );
		?>

		<div <?php $this->print_render_attribute_string( 'rael_pc_container' ); ?> >
			<?php
				$template = $this->get_template( $settings['rael_pc_dynamic_template_layout'] );
			if ( file_exists( $template ) ) :
				$query = new \WP_Query( $args );
				if ( $query->have_posts() ) :
					echo '<div ' . wp_kses_post( $this->get_render_attribute_string( 'rael_pc_wrapper' ) ) . '>';
						do_action( 'rael/widgets/rael_pc_before_product_loop' );
						$settings['rael_page_id'] = get_the_ID();
						echo '<ul class="swiper-wrapper products">';
					while ( $query->have_posts() ) {
						$query->the_post();
						include $template;
					}
						wp_reset_postdata();
						echo '</ul>';
						echo '</div>';
					else :
						echo '<p class="rael-pc__no-posts-found">' . wp_kses_post( $settings['rael_pc_not_found_msg'] ) . '</p>';
					endif;
				else :
					esc_html( '<p class="rael-pc__no-posts-found">No layout found!</p>', 'responsive-addons-for-elementor' );
				endif;

				if ( 'yes' === $settings['rael_pc_image_dots'] ) {
					$this->render_image_dots( $args );
				} else {
					$this->render_dots();
				}

				if ( 'yes' === $settings['rael_pc_arrows'] ) {
					$this->render_arrows();
				}
				?>
		</div>
		<?php
	}
	/**
	 * Render the navigation arrows for the product carousel.
	 */
	protected function render_arrows() {
		$settings = $this->get_settings_for_display();

		if ( $settings['rael_pc_arrow'] ) {
			$pa_next_arrow = $settings['rael_pc_arrow'];
			$pa_prev_arrow = str_replace( 'right', 'left', $settings['rael_pc_arrow'] );
		} else {
			$pa_next_arrow = 'fa fa-angle-right';
			$pa_prev_arrow = 'fa fa-angle-left';
		}
		?>
		
		<div class="swiper-button-next swiper-button-next-<?php echo esc_attr( $this->get_id() ); ?>">
			<i class="<?php echo esc_attr( $pa_next_arrow ); ?>"></i>
		</div>
		<div class="swiper-button-prev swiper-button-prev-<?php echo esc_attr( $this->get_id() ); ?>">
			<i class="<?php echo esc_attr( $pa_prev_arrow ); ?>"></i>
		</div>
		<?php
	}
}
