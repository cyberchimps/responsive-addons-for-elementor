<?php
/**
 * RAEL Theme Builder's Product Rating Widget.
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

use Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder\Woo_Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * RAEL Theme Product rating widget class.
 */
class Responsive_Addons_For_Elementor_Theme_Product_Rating extends Woo_Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-woocommerce-product-rating';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Product Rating', 'responsive-addons-for-elementor' );
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
		return 'eicon-product-rating rael-badge';
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
		return array( 'rael', 'woocommerce', 'shop', 'store', 'rating', 'review', 'comments', 'stars', 'product' );
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
	 * Register all the control settings for the product rating widget
	 *
	 * @access public
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'rael_section_product_rating_style',
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

		$this->add_control(
			'rael_star_color',
			array(
				'label'     => esc_html__( 'Star Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .star-rating span' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_empty_star_color',
			array(
				'label'     => esc_html__( 'Empty Star Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .star-rating::before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_link_color',
			array(
				'label'     => esc_html__( 'Link Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .woocommerce-review-link' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_text_typography',
				'selector' => '.woocommerce {{WRAPPER}} .woocommerce-review-link',
			)
		);

		$this->add_control(
			'rael_star_size',
			array(
				'label'     => esc_html__( 'Star Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'unit' => 'em',
				),
				'range'     => array(
					'em' => array(
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'.woocommerce {{WRAPPER}} .star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'rael_space_between',
			array(
				'label'      => esc_html__( 'Space Between', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'unit' => 'em',
				),
				'range'      => array(
					'em' => array(
						'min'  => 0,
						'max'  => 4,
						'step' => 0.1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'.woocommerce:not(.rtl) {{WRAPPER}} .star-rating' => 'margin-right: {{SIZE}}{{UNIT}}',
					'.woocommerce.rtl {{WRAPPER}} .star-rating' => 'margin-left: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'rael_alignment',
			array(
				'label'        => esc_html__( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
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
				'prefix_class' => 'elementor-product-rating--align-',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render function for the widget
	 *
	 * @access public
	 */
	protected function render() {
		if ( ! post_type_supports( 'product', 'comments' ) ) {
			return;
		}

		global $product;
		$product = wc_get_product();

		if ( empty( $product ) ) {
			return;
		}

		wc_get_template( 'single-product/rating.php' );
	}

	/**
	 * Render plain content function for the widget
	 *
	 * @access public
	 */
	public function render_plain_content() {}
}
