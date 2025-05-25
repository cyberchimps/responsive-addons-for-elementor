<?php
/**
 * RAEL Product Category Grid Widget
 *
 * @since      1.2.2
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\WooCommerce;

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Responsive_Addons_For_Elementor\Traits\Missing_Dependency;

use Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Skins\Product_Category_Grid as Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor 'RAEL Product Category Grid' widget class.
 *
 * @since 1.2.2
 */
class Responsive_Addons_For_Elementor_Product_Category_Grid extends Widget_Base {
	use Missing_Dependency;

	/**
	 * Query variable.
	 *
	 * @var \WP_Query
	 */
	protected $query = null;

	/**
	 * Whether the widget has its skin template.
	 *
	 * @var boolean
	 */
	protected $_has_template_content = false; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-product-category-grid';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Product Category Grid', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve google map widget icon.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-categories rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the google map widget belongs to.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get custom help URL
	 *
	 * @since 1.2.2
	 * @return string Help URL.
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/product-category-grid';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'categories', 'products', 'product', 'grid', 'category', 'woocommerce', 'ecommerce' );
	}

	/**
	 * Register widget skins.
	 *
	 * @since 1.2.2
	 *
	 * @since 1.5.0 Added a condition to check whether the dependency plugin is activated or not.
	 *
	 * @access protected
	 */
	protected function register_skins() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		if ( class_exists( 'WooCommerce' ) ) {
			require_once RAEL_DIR . '/includes/widgets-manager/widgets/skins/product-category-grid/rael-skin-minimal.php';
			require_once RAEL_DIR . '/includes/widgets-manager/widgets/skins/product-category-grid/rael-skin-classic.php';

			$this->add_skin( new Skins\RAEL_Skin_Classic( $this ) );
			$this->add_skin( new Skins\RAEL_Skin_Minimal( $this ) );
		}
	}

	/**
	 * Get all the categories.
	 *
	 * @since 1.2.2
	 * @return array List of categories.
	 */
	public function get_all_categories_list() {
		$args = array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'count'      => true,
		);

		$cats = get_terms( $args );

		$categories = array();

		foreach ( $cats as $cat ) {
			$categories[ $cat->term_id ] = $cat->name;
		}

		return $categories;
	}

	/**
	 * Get Parent Categories only.
	 *
	 * @since 1.2.2
	 * @return array List of Parent Categories.
	 */
	public function get_parent_categories_list() {
		$args = array(
			'taxonomy'   => 'product_cat',
			'parent'     => 0,
			'hide_empty' => false,
			'count'      => true,
		);

		$cats = get_terms( $args );

		$categories = array( 'none' => __( 'None', 'responsive-addons-for-elementor' ) );

		foreach ( $cats as $cat ) {
			$categories[ $cat->term_id ] = $cat->name;
		}

		return $categories;
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.2.2
	 *
	 * @since 1.5.0 Added a condition to display a warning message in the editor when the dependency plugin is not activated.
	 *
	 * @access protected
	 */
	protected function register_controls() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			$this->register_content_tab_missing_dep_warning_controls( 'WooCommerce', 'woocommerce' );
		} else {
			// Content Tab.
			$this->register_content_layout_controls();
			$this->register_content_query_controls();
			$this->register_content_more_link_controls();

			// Style Tab.
			$this->register_style_layout_controls();
			$this->register_style_item_box_controls();
			$this->register_style_image_controls();
			$this->register_style_more_controls();
		}
	}

	/**
	 * Register Layout controls under Content Tab.
	 *
	 * @since 1.2.2
	 * @access public
	 */
	public function register_content_layout_controls() {
		$this->start_controls_section(
			'rael_pcg_section_layout',
			array(
				'label' => __( 'Layout', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'rael_columns',
			array(
				'label'              => __( 'Columns', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '3',
				'tablet_default'     => '2',
				'mobile_default'     => '1',
				'options'            => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'selectors'          => array(
					'{{WRAPPER}} .rael-product-category-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_category_count',
			array(
				'label'   => __( 'Category Per Page', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
			)
		);

		$this->add_control(
			'rael_category_image',
			array(
				'label'        => __( 'Featured Image', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'rael_category_image',
				'label'     => __( 'Image Size', 'responsive-addons-for-elementor' ),
				'exclude'   => array( 'custom' ),
				'condition' => array(
					'rael_category_image' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_image_overlay',
			array(
				'label'                => __( 'Image Overlay', 'responsive-addons-for-elementor' ),
				'type'                 => Controls_Manager::SWITCHER,
				'default'              => 'yes',
				'label_on'             => __( 'YES', 'responsive-addons-for-elementor' ),
				'label_off'            => __( 'NO', 'responsive-addons-for-elementor' ),
				'return_value'         => 'yes',
				'prefix_class'         => 'rael-product-category-grid--has-image-overlay-',
				'selectors_dictionary' => array(
					'yes' => 'content: \'\';',
				),
				'selectors'            => array(
					'{{WRAPPER}} .rael-product-category-grid__item-thumbnail:before' => '{{VALUE}}',
				),
				'condition'            => array(
					'rael_category_image' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_show_product_count',
			array(
				'label'        => __( 'Count Number', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'YES', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'NO', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Query controls under Content Tab.
	 *
	 * @since 1.2.2
	 * @access public
	 */
	public function register_content_query_controls() {
		$this->start_controls_section(
			'rael_pcg_section_query',
			array(
				'label' => __( 'Query', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_query_type',
			array(
				'label'   => __( 'Type', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'all',
				'options' => array(
					'all'     => __( 'All', 'responsive-addons-for-elementor' ),
					'parents' => __( 'Only Parents', 'responsive-addons-for-elementor' ),
					'child'   => __( 'Only Child', 'responsive-addons-for-elementor' ),
				),

			)
		);

		$this->start_controls_tabs(
			'rael_pcg_query_control_tabs',
			array(
				'condition' => array(
					'rael_query_type' => 'all',
				),
			)
		);

		$this->start_controls_tab(
			'rael_pcg_query_include_tab',
			array(
				'label'     => __( 'Include', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_query_type' => 'all',
				),
			)
		);

		$this->add_control(
			'rael_query_include_categories',
			array(
				'label'       => __( 'Categories', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => $this->get_all_categories_list(),
				'condition'   => array(
					'rael_query_type' => 'all',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_pcg_query_exclude_tab',
			array(
				'label'     => __( 'Exclude', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_query_type' => 'all',
				),
			)
		);

		$this->add_control(
			'rael_query_exclude_categories',
			array(
				'label'       => __( 'Categories', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => $this->get_all_categories_list(),
				'condition'   => array(
					'rael_query_type' => 'all',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'rael_parent_category',
			array(
				'label'     => __( 'Child Categories of', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => $this->get_parent_categories_list(),
				'condition' => array(
					'rael_query_type' => 'child',
				),
			)
		);

		$this->add_control(
			'rael_query_order_by',
			array(
				'label'   => __( 'Order By', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'name',
				'options' => array(
					'name'  => __( 'Name', 'responsive-addons-for-elementor' ),
					'count' => __( 'Count', 'responsive-addons-for-elementor' ),
					'slug'  => __( 'Slug', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_query_order',
			array(
				'label'   => __( 'Order', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => array(
					'DESC' => __( 'Descending', 'responsive-addons-for-elementor' ),
					'ASC'  => __( 'Ascending', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_show_empty_categories',
			array(
				'label'        => __( 'Show Empty Categories', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'YES', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'NO', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register More button controls under Content Tab.
	 *
	 * @since 1.2.2
	 * @access public
	 */
	public function register_content_more_link_controls() {
		$this->start_controls_section(
			'rael_pcg_section_load_more',
			array(
				'label' => __( 'More...', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_show_load_more_button',
			array(
				'label'        => __( 'Show Load More Button', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'YES', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'NO', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_load_more_button_text',
			array(
				'label'     => __( 'Button Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'More category', 'responsive-addons-for-elementor' ),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'rael_show_load_more_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_load_more_button_link',
			array(
				'label'     => __( 'Button URL', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::URL,
				'default'   => array(
					'url'         => '#',
					'is_external' => true,
					'nofollow'    => true,
				),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'rael_show_load_more_button' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Layout controls under Style Tab.
	 *
	 * @since 1.2.2
	 * @access public
	 */
	public function register_style_layout_controls() {
		$this->start_controls_section(
			'rael_pcg_section_layout_style',
			array(
				'label' => __( 'Layout', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_columns_gap',
			array(
				'label'      => __( 'Columns Gap', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 30,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-product-category-grid' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'rael_rows_gap',
			array(
				'label'      => __( 'Rows Gap', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 35,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-product-category-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Item Box controls under Style Tab.
	 *
	 * @since 1.2.2
	 * @access public
	 */
	public function register_style_item_box_controls() {
		$this->start_controls_section(
			'rael_pcg_section_item_box_style',
			array(
				'label' => __( 'Item Box', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_item_box_height',
			array(
				'label'     => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 1200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-product-category-grid__item-wrapper' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_item_box_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-product-category-grid__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'label'    => __( 'Border', 'responsive-addons-for-elementor' ),
				'name'     => 'rael_item_box',
				'selector' => '{{WRAPPER}} .rael-product-category-grid__item',
			)
		);

		$this->add_responsive_control(
			'rael_item_box_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-product-category-grid__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'name'     => 'rael_item_box_shadow',
				'selector' => '{{WRAPPER}} .rael-product-category-grid__item',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'label'    => __( 'Background Type', 'responsive-addons-for-elementor' ),
				'name'     => 'rael_item_box_background_type',
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .rael-product-category-grid__item',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Image style controls under Style Tab.
	 *
	 * @since 1.2.2
	 * @access public
	 */
	public function register_style_image_controls() {
		$this->start_controls_section(
			'rael_pcg_section_image_style',
			array(
				'label'     => __( 'Image', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_category_image' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_image_width',
			array(
				'label'     => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 10,
						'max' => 2000,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-product-category-grid__item-thumbnail img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_image_height',
			array(
				'label'     => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 10,
						'max' => 2000,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-product-category-grid__item-thumbnail img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-product-category-grid__item-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'rael_pcg_thumbnail_is_gradient_background',
			array(
				'label'     => __( 'Overlay Type', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_image_overlay' => 'yes',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'rael_pcg_thumbnail_overlay_color',
				'label'     => __( 'Overlay Type', 'responsive-addons-for-elementor' ),
				'types'     => array( 'classic', 'gradient' ),
				'exclude'   => array(
					'image',
				),
				'selector'  => '{{WRAPPER}} .rael-product-category-grid__item-thumbnail:before',
				'condition' => array(
					'rael_image_overlay' => 'yes',
				),
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Register More button style controls under Style Tab.
	 *
	 * @since 1.2.2
	 * @access public
	 */
	public function register_style_more_controls() {
		$this->start_controls_section(
			'rael_pcg_section_load_more_style',
			array(
				'label'     => __( 'Load More Button', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_show_load_more_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_load_more_button_alignment',
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
					'{{WRAPPER}} .rael-product-category-grid__load-more' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_load_more_button_margin_top',
			array(
				'label'      => __( 'Margin Top', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 30,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-product-category-grid__load-more' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_load_more_button_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-product-category-grid__load-more-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'name'     => 'rael_load_more_button',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .rael-product-category-grid__load-more-button',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'label'    => __( 'Border', 'responsive-addons-for-elementor' ),
				'name'     => 'rael_load_more_button',
				'selector' => '{{WRAPPER}} .rael-product-category-grid__load-more-button',
			)
		);

		$this->add_control(
			'rael_load_more_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-product-category-grid__load-more-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'rael_load_more_button_state_tabs' );

		$this->start_controls_tab(
			'rael_load_more_button_normal_state',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_load_more_button_text_color_normal',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-product-category-grid__load-more-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_load_more_button_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-product-category-grid__load-more-button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'name'     => 'rael_load_more_button_normal',
				'selector' => '{{WRAPPER}} .rael-product-category-grid__load-more-button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_load_more_button_hover_state',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_load_more_button_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-product-category-grid__load-more-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_load_more_button_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-product-category-grid__load-more-button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'name'     => 'rael_load_more_button_hover',
				'selector' => '{{WRAPPER}} .rael-product-category-grid__load-more-button:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Fetches the categories list based on the given input.
	 *
	 * @param string $category_per_page Category per page settings value.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return array Categories list.
	 */
	public function query_categories( $category_per_page ) {
		$settings = $this->get_settings_for_display();

		$query_args = array(
			'taxonomy'   => 'product_cat',
			'order'      => ( $settings['rael_query_order'] ) ? $settings['rael_query_order'] : 'ASC',
			'orderby'    => ( $settings['rael_query_order_by'] ) ? $settings['rael_query_order_by'] : 'name',
			'hide_empty' => 'yes' === $settings['rael_show_empty_categories'] ? false : true,
		);

		if ( 'all' === $settings['rael_query_type'] ) {
			if ( ! empty( $settings['rael_query_include_categories'] ) ) {
				$query_args['include'] = $settings['rael_query_include_categories'];
			}
			if ( ! empty( $settings['rael_query_exclude_categories'] ) ) {
				$query_args['exclude'] = $settings['rael_query_exclude_categories'];
			}
		} elseif ( 'parents' === $settings['rael_query_type'] ) {
			$query_args['parent'] = 0;
		} elseif ( 'child' === $settings['rael_query_type'] ) {
			if ( 'none' !== $settings['rael_parent_category'] && ! empty( $settings['rael_parent_category'] ) ) {
				$query_args['child_of'] = $settings['rael_parent_category'];
			} else {
				if ( is_admin() ) {
					return printf( '<div class="rael-pcg__category-selection-error">%s</div>', __( 'Select Parent Category from <strong>Query > Child Categories of</strong>.', 'responsive-addons-for-elementor' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			}
		}

		$product_categories = get_terms( $query_args );

		if ( ! empty( $product_categories ) && count( $product_categories ) > $category_per_page ) {
			$product_categories = array_splice( $product_categories, 0, $category_per_page );
		}

		return $product_categories;
	}

	/**
	 * Render the widget on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * This method does not have any implementation due to
	 * the use of skins for the widget.
	 *
	 * @since 1.2.2
	 * @access protected
	 */
	// protected function render() {}

	/**
	 * Render the widget on the frontend.
	 *
	 * @see render() in RAEL_Skin_Base class for its usage.
	 *
	 * @since 1.2.2
	 *
	 * @since 1.5.0 Added a condition to check whether the dependency plugin is activated or not.
	 *
	 * @access public
	 */
	public function render() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		$settings           = $this->get_settings_for_display();
		$category_per_page  = $settings['rael_category_count'];
		$product_categories = $this->query_categories( $category_per_page );
		$skin               = $settings['_skin'];

		if ( empty( $product_categories ) ) {
			if ( is_admin() ) {
				return printf( '<div class="rael-pcg__category-selection-error">%s</div>', __( 'No Results found. Please add a category.', 'responsive-addons-for-elementor' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		$this->add_render_attribute(
			'rael_pcg',
			'class',
			array(
				'rael-product-category-grid',
				'rael-product-category-grid--' . substr( $skin, 5 ),
			)
		);
		?>

		<div <?php $this->print_render_attribute_string( 'rael_pcg' ); ?>>
			<?php
			foreach ( $product_categories as $category ) :
				$image_src    = Utils::get_placeholder_image_src();
				$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
				$image        = wp_get_attachment_image_src( $thumbnail_id, $settings['rael_category_image_size'], false );

				if ( $image ) {
					$image_src = $image[0];
				}

				$this->add_render_attribute( 'rael_pcg_item_wrapper', 'class', 'rael-product-category-grid__item-wrapper' );

				if ( 'yes' === $settings['rael_category_image'] ) {
					$this->add_render_attribute( 'rael_pcg_item_wrapper', 'class', 'rael-product-category-grid--has-own-image' );
				}
				?>

					<article <?php $this->print_render_attribute_string( 'rael_pcg_item_wrapper' ); ?>>
						<div class="rael-product-category-grid__item" onclick="window.location.href='<?php echo esc_url( get_term_link( $category->term_id, 'product_cat' ) ); ?>'" >

						<?php if ( $image_src && 'yes' === $settings['rael_category_image'] ) : ?>
								<div class="rael-product-category-grid__item-thumbnail">
									<img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $category->name ); ?>">
								</div>
							<?php endif; ?>

							<div class="rael-product-category-grid__content-wrapper">
								<div class="rael-product-category-grid__content">
									<h2 class="rael-product-category-grid__content-title">
										<a href="<?php echo esc_url( get_term_link( $category->term_id, 'product_cat' ) ); ?>">
										<?php echo esc_html( $category->name ); ?>
										</a>
									</h2>

								<?php if ( 'yes' === $settings['rael_show_product_count'] ) : ?>
										<?php
											$product_count = '';
										if ( 'classic' === $skin ) {
											$product_count = '(' . $category->count . ')';
										} else {
											$product_count_suffix = $category->count > 1 ? __( 'Items', 'responsive-addons-for-elementor' ) : __( 'Item', 'responsive-addons-for-elementor' );
											$product_count        = $category->count . ' ' . $product_count_suffix;
										}
										?>

										<div class="rael-product-category-grid__product-count">
											<?php echo esc_html( $product_count ); ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</article>
					<?php
				endforeach;
			?>
		</div>

		<?php
		if ( 'yes' === $settings['rael_show_load_more_button'] ) :
			$this->add_link_attributes( 'rael_load_more_button', $settings['rael_load_more_button_link'] );
			$this->add_render_attribute( 'rael_load_more_button', 'class', 'rael-product-category-grid__load-more-button' );
			?>
		<div class="rael-product-category-grid__load-more">
			<a <?php $this->print_render_attribute_string( 'rael_load_more_button' ); ?>>
				<?php echo esc_html( $settings['rael_load_more_button_text'] ); ?>
			</a>
		</div>
			<?php
		endif;
	}
}
