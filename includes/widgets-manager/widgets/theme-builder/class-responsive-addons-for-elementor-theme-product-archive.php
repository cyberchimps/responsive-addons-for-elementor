<?php
/**
 * RAEL Theme Builder's Products Archive Widget.
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\Woocommerce\Classes\Products_Renderer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * RAEL Theme Product Archive widget class.
 *
 * @since 1.8.0
 */
class Responsive_Addons_For_Elementor_Theme_Product_Archive extends Woo_Products {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-archive-products';
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
		return esc_html__( ' Archive Products', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Get Categories.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_categories() {
		return array(
			'responsive-addons-for-elementor',
		);
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
		return 'eicon-products rael-badge';
	}
	/**
	 * Register all the control settings for the product archive widget
	 *
	 * @since 1.8.0
	 * @access public
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->remove_responsive_control( 'columns' );
		$this->remove_responsive_control( 'rael_rows' );
		$this->remove_control( 'orderby' );
		$this->remove_control( 'order' );

		$this->update_control(
			'rael_products_class',
			array(
				'prefix_class' => 'rael-elementor-products-grid elementor-',
			)
		);

		// Should be kept as hidden since required for "allow_order"
		// paginate , allow_order , show_result_count is used as default because they are coming from current-query-render
		// and product-renderer.
		$this->update_control(
			'paginate',
			array(
				'type'    => 'hidden',
				'default' => 'yes',
			)
		);

		$this->update_control(
			'allow_order',
			array(
				'default' => 'yes',
			)
		);

		$this->start_injection(
			array(
				'at' => 'before',
				'of' => 'allow_order',
			)
		);

		if ( ! get_theme_support( 'woocommerce' ) ) {
			$this->add_control(
				'rael_wc_notice_wc_not_supported',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => esc_html__( 'Looks like you are using WooCommerce, while your theme does not support it. Please consider switching themes.', 'responsive-addons-for-elementor' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				)
			);
		}

		$this->add_control(
			'rael_wc_notice_use_customizer',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'To change the Products Archive’s layout, go to Appearance > Customize.', 'responsive-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'rael_wc_notice_wrong_data',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'The editor preview might look different from the live site. Please make sure to check the frontend.', 'responsive-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->end_injection();

		$this->update_control(
			'show_result_count',
			array(
				'default' => 'yes',
			)
		);

		$this->update_control(
			'rael_section_query',
			array(
				'type' => 'hidden',
			)
		);

		$this->update_control(
			Products_Renderer::QUERY_CONTROL_NAME . '_post_type',
			array(
				'default' => 'current_query',
			)
		);

		$this->start_controls_section(
			'rael_section_advanced',
			array(
				'label' => esc_html__( 'Advanced', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_nothing_found_message',
			array(
				'label'   => esc_html__( 'Nothing Found Message', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'It seems we can not find what you are looking for.', 'responsive-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_nothing_found_style',
			array(
				'tab'       => Controls_Manager::TAB_STYLE,
				'label'     => esc_html__( 'Nothing Found Message', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'nothing_found_message!' => '',
				),
			)
		);

		$this->add_control(
			'rael_nothing_found_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .{{WRAPPER}} .elementor-products-nothing-found' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'nothing_found_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .elementor-products-nothing-found',
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Get_group_name function to get the group name.
	 *
	 * @access public
	 */
	public function get_group_name() {
		return 'woocommerce';
	}

}
