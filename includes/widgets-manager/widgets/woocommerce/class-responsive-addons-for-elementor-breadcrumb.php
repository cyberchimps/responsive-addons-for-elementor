<?php
/**
 * WooCommerce Breadcrumb Widget
 *
 * @since      1.1.0
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Woocommerce;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Responsive_Addons_For_Elementor\WidgetsManager\Widgets_Manager;
use Responsive_Addons_For_Elementor\Traits\Missing_Dependency;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Countdown Widget
 *
 * @since      1.1.0
 * @package    Responsive_Addons_For_Elementor
 * @author     CyberChimps <support@cyberchimps.com>
 */
class Responsive_Addons_For_Elementor_Breadcrumb extends Widget_Base {
	use Missing_Dependency;

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-woocommerce-breadcrumb';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'WooCommerce Breadcrumbs', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve breadcrumb widget icon.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-breadcrumbs rael-badge';
	}

	/**
	 * Get widget KeyWords.
	 *
	 * Retrieve the list of keywords.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'woocommerce-elements', 'shop', 'store', 'breadcrumbs', 'internal links', 'product' );
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the breadcrumb widget belongs to.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'woocommerce-elements', 'woocommerce-elements-single' );
	}

	/**
	 * Register Controls.
	 *
	 * @since 1.1.0
	 *
	 * @since 1.5.0 Added a condition to display a warning message in the editor when the dependency plugin is not activated.
	 *
	 * @access protected
	 */
	protected function register_controls() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			$this->register_content_tab_missing_dep_warning_controls( 'WooCommerce', 'woocommerce' );
		} else {
			$this->register_controls_when_plugin_is_activated();
		}
	}

	/**
	 * Register controls for the widget if the dependency plugin is activated.
	 *
	 * @since 1.5.0
	 *
	 * @access protected
	 */
	protected function register_controls_when_plugin_is_activated() {
		$this->start_controls_section(
			'section_product_rating_style',
			array(
				'label' => __( 'Style', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'wc_style_warning',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'responsive-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-breadcrumb' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => __( 'Link Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-breadcrumb > a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .woocommerce-breadcrumb',
			)
		);

		$this->add_responsive_control(
			'alignment',
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
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-breadcrumb' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render Breadcrumb Widget
	 *
	 * @since 1.1.0
	 *
	 * @since 1.5.0 Added a condition check to whether the dependency plugin is activated or not.
	 *
	 * @access protected
	 */
	protected function render() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		woocommerce_breadcrumb();
	}

	/**
	 * Render sidebar widget as plain content.
	 *
	 * Override the default render behavior, don't render sidebar content.
	 *
	 * @since 1.1.0
	 * @access public
	 */
	public function render_plain_content() {}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/woocommerce-breadcrumbs';
	}
}
