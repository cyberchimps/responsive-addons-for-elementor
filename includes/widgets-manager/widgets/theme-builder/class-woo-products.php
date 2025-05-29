<?php
namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\QueryControl\Controls\Group_Control_Query;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\Woocommerce\Classes\Products_Renderer;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\Woocommerce\Classes\Current_Query_Renderer;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Woo_Products extends Responsive_Addons_For_Elementor_Woo_Products_Base {

	public function get_name() {
		return 'rael-woocommerce-products';
	}

	public function get_title() {
		return esc_html__( 'Products', 'responsive-addons-for-elementor' );
	}

	public function get_icon() {
		return 'eicon-products rael-badge';
	}

	public function get_keywords() {
		return array( 'rael', 'woocommerce', 'shop', 'store', 'product', 'archive', 'upsells', 'cross-sells', 'cross sells', 'related' );
	}

	public function get_categories() {
		return array(
			'responsive-addons-for-elementor',
		);
	}

	protected function register_query_controls() {
		$this->start_controls_section(
			'rael_section_query',
			array(
				'label' => esc_html__( 'Query', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_group_control(
			Group_Control_Query::get_type(),
			array(
				'name'           => Products_Renderer::QUERY_CONTROL_NAME,
				'post_type'      => 'product',
				'presets'        => array( 'include', 'exclude', 'order' ),
				'fields_options' => array(
					'post_type' => array(
						'default' => 'product',
						'options' => array(
							'current_query' => esc_html__( 'Current Query', 'responsive-addons-for-elementor' ),
							'product'       => esc_html__( 'Latest Products', 'responsive-addons-for-elementor' ),
							'sale'          => esc_html__( 'Sale', 'responsive-addons-for-elementor' ),
							'featured'      => esc_html__( 'Featured', 'responsive-addons-for-elementor' ),
							'by_id'         => _x( 'Manual Selection', 'Posts Query Control', 'responsive-addons-for-elementor' ),
							'related'       => esc_html__( 'Related', 'responsive-addons-for-elementor' ),
							'upsells'       => esc_html__( 'Upsells', 'responsive-addons-for-elementor' ),
							'cross_sells'   => esc_html__( 'Cross-Sells', 'responsive-addons-for-elementor' ),
						),
					),
					'orderby'   => array(
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
					),
					'exclude'   => array(
						'options' => array(
							'current_post'     => esc_html__( 'Current Post', 'responsive-addons-for-elementor' ),
							'manual_selection' => esc_html__( 'Manual Selection', 'responsive-addons-for-elementor' ),
							'terms'            => esc_html__( 'Term', 'responsive-addons-for-elementor' ),
						),
					),
					'include'   => array(
						'options' => array(
							'terms' => esc_html__( 'Term', 'responsive-addons-for-elementor' ),
						),
					),
				),
				'exclude'        => array(
					'posts_per_page',
					'exclude_authors',
					'authors',
					'offset',
					'related_fallback',
					'related_ids',
					'query_id',
					'avoid_duplicates',
					'ignore_sticky_posts',
				),
			)
		);

		$this->add_control(
			'rael_related_products_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Note: The Related Products Query is available when creating a Single Product template', 'responsive-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'       => array(
					Products_Renderer::QUERY_CONTROL_NAME . '_post_type' => 'related',
				),
			)
		);

		$this->add_control(
			'rael_upsells_products_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Note: The Upsells Query is available when creating a Single Product template', 'responsive-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'       => array(
					Products_Renderer::QUERY_CONTROL_NAME . '_post_type' => 'upsells',
				),
			)
		);

		$this->add_control(
			'rael_cross_sells_products_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Note: The Cross-Sells Query is available when creating a Cart page', 'responsive-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'       => array(
					Products_Renderer::QUERY_CONTROL_NAME . '_post_type' => 'cross_sells',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_controls() {
		$this->start_controls_section(
			'rael_section_content',
			array(
				'label' => esc_html__( 'Content', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_columns_responsive_control();

		$this->add_control(
			'rael_rows',
			array(
				'label'       => esc_html__( 'Rows', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => Products_Renderer::DEFAULT_COLUMNS_AND_ROWS,
				'render_type' => 'template',
				'range'       => array(
					'px' => array(
						'max' => 20,
					),
				),
			)
		);

		$this->add_control(
			'paginate',
			array(
				'label'     => esc_html__( 'Pagination', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					Products_Renderer::QUERY_CONTROL_NAME . '_post_type!' => array(
						'related',
						'upsells',
						'cross_sells',
					),
				),
			)
		);

		$this->add_control(
			'allow_order',
			array(
				'label'     => esc_html__( 'Allow Order', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'paginate' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_wc_notice_frontpage',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Ordering is not available if this widget is placed in your front page. Visible on frontend only.', 'responsive-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'       => array(
					'paginate'    => 'yes',
					'allow_order' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_result_count',
			array(
				'label'     => esc_html__( 'Show Result Count', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'paginate' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->register_query_controls();

		$this->start_controls_section(
			'rael_section_products_title',
			array(
				'label'      => esc_html__( 'Title', 'responsive-addons-for-elementor' ),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => Products_Renderer::QUERY_CONTROL_NAME . '_post_type',
							'operator' => '=',
							'value'    => 'related',
						),
						array(
							'name'     => Products_Renderer::QUERY_CONTROL_NAME . '_post_type',
							'operator' => '=',
							'value'    => 'upsells',
						),
						array(
							'name'     => Products_Renderer::QUERY_CONTROL_NAME . '_post_type',
							'operator' => '=',
							'value'    => 'cross_sells',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_products_title_show',
			array(
				'label'        => esc_html__( 'Title', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => esc_html__( 'Hide', 'responsive-addons-for-elementor' ),
				'default'      => '',
				'return_value' => 'show',
				'prefix_class' => 'products-heading-',
			)
		);

		$query_type_strings = array(
			'related'     => esc_html__( 'Related Products', 'responsive-addons-for-elementor' ),
			'upsells'     => esc_html__( 'You may also like...', 'responsive-addons-for-elementor' ),
			'cross_sells' => esc_html__( 'You may be interested in...', 'responsive-addons-for-elementor' ),
		);

		foreach ( $query_type_strings as $query_type => $string ) {
			$this->add_control(
				'products_' . $query_type . '_title_text',
				array(
					'label'       => esc_html__( 'Section Title', 'responsive-addons-for-elementor' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
					'placeholder' => $string,
					'default'     => $string,
					'dynamic'     => array(
						'active' => true,
					),
					'condition'   => array(
						'products_title_show!' => '',
						Products_Renderer::QUERY_CONTROL_NAME . '_post_type' => $query_type,
					),
				)
			);
		}

		$this->add_responsive_control(
			'rael_products_title_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'start'  => array(
						'title' => esc_html__( 'Start', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'end'    => array(
						'title' => esc_html__( 'End', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--products-title-alignment: {{VALUE}};',
				),
				'condition' => array(
					'products_title_show!' => '',
				),
			)
		);

		$this->end_controls_section();

		parent::register_controls();

		$this->start_injection(
			array(
				'type' => 'section',
				'at'   => 'start',
				'of'   => 'section_design_box',
			)
		);

		$this->start_controls_section(
			'rael_products_title_style',
			array(
				'label'      => esc_html__( 'Title', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'condition'  => array(
					'products_title_show!' => '',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => Products_Renderer::QUERY_CONTROL_NAME . '_post_type',
							'operator' => '=',
							'value'    => 'related',
						),
						array(
							'name'     => Products_Renderer::QUERY_CONTROL_NAME . '_post_type',
							'operator' => '=',
							'value'    => 'upsells',
						),
						array(
							'name'     => Products_Renderer::QUERY_CONTROL_NAME . '_post_type',
							'operator' => '=',
							'value'    => 'cross_sells',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_products_title_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--products-title-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'products_title_typography',
				'selector' => '{{WRAPPER}}.products-heading-show .related > h2, {{WRAPPER}}.products-heading-show .upsells > h2, {{WRAPPER}}.products-heading-show .cross-sells > h2',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
			)
		);

		$this->add_responsive_control(
			'rael_products_title_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array( 'px' => 0 ),
				'selectors'  => array(
					'{{WRAPPER}}' => 'products-title-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->end_injection();
	}

	protected function get_shortcode_object( $settings ) {
		if ( 'current_query' === $settings[ Products_Renderer::QUERY_CONTROL_NAME . '_post_type' ] ) {
			$type = 'current_query';
			return new Current_Query_Renderer( $settings, $type );
		}
		$type = 'products';
		return new Products_Renderer( $settings, $type );
	}

	protected function render() {

		if ( WC()->session ) {
			wc_print_notices();
		}

		$settings          = $this->get_settings_for_display();
		$post_type_setting = $settings[ Products_Renderer::QUERY_CONTROL_NAME . '_post_type' ];

		// Add a wrapper class to the Add to Cart & View Items elements if the automically_align_buttons switch has been selected.
		if ( 'yes' === $settings['rael_automatically_align_buttons'] ) {
			add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'add_to_cart_wrapper' ), 10, 1 );
		}

		if ( 'related' === $post_type_setting ) {
			$content = Module::get_products_related_content( $settings );
		} elseif ( 'upsells' === $post_type_setting ) {
			$content = Module::get_upsells_content( $settings );
		} elseif ( 'cross_sells' === $post_type_setting ) {
			$content = Module::get_cross_sells_content( $settings );
		} else {
			// For Products_Renderer.
			if ( ! isset( $GLOBALS['post'] ) ) {
				$GLOBALS['post'] = null; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			}

			$shortcode = $this->get_shortcode_object( $settings );
			$content   = $shortcode->get_content();
		}

		if ( $content ) {
			$content = str_replace( '<ul class="products', '<ul class="products elementor-grid', $content );

			// PHPCS - Woocommerce output
			echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} elseif ( $this->get_settings_for_display( 'nothing_found_message' ) ) {
			echo '<div class="elementor-nothing-found elementor-products-nothing-found">' . esc_html( $this->get_settings_for_display( 'nothing_found_message' ) ) . '</div>';
		}

		if ( 'yes' === $settings['rael_automatically_align_buttons'] ) {
			remove_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'add_to_cart_wrapper' ) );
		}
	}

	public function render_plain_content() {}

	public function get_group_name() {
		return 'woocommerce';
	}
}
