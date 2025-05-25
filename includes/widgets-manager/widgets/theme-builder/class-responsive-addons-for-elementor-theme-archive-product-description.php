<?php
/**
 * RAEL Theme Builder's Archive Description Widget.
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * RAEL Theme Archive Description widget class.
 */
class Responsive_Addons_For_Elementor_Theme_Archive_Product_Description extends Woo_Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-woocommerce-archive-description';
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
		return esc_html__( 'Archive Description', 'responsive-addons-for-elementor' );
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
		return 'eicon-product-description rael-badge';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve widget keywords.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @return string Widget keywords.
	 */
	public function get_keywords() {
		return array( 'rael', 'woocommerce', 'shop', 'store', 'text', 'description', 'category', 'product', 'archive' );
	}

	/**
	 * Get widget Category.
	 *
	 * Retrieve widget categories.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @return string Widget categories.
	 */
	public function get_categories() {
		return array(
			'responsive-addons-for-elementor',
		);
	}
	/**
	 * Register all the control settings for the widget
	 *
	 * @since 1.8.0
	 * @access public
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'rael_section_product_description_style',
			array(
				'label' => esc_html__( 'Style', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_wc_style_warning',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'responsive-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_responsive_control(
			'rael_text_align',
			array(
				'label'     => esc_html__( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .term-description' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'label'    => esc_html__( 'Typography', 'responsive-addons-for-elementor' ),
				'selector' => '.woocommerce {{WRAPPER}} .term-description',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render function for the widget
	 *
	 * @since 1.8.0
	 *
	 * @access public
	 */
	protected function render() {
		do_action( 'woocommerce_archive_description' );
	}

	/**
	 * Render plain content function for the widget
	 *
	 * @since 1.8.0
	 *
	 * @access public
	 */
	public function render_plain_content() {}

	/**
	 * Get_group_name function to get the group name.
	 *
	 * @access public
	 */
	public function get_group_name() {
		return 'woocommerce';
	}
}
