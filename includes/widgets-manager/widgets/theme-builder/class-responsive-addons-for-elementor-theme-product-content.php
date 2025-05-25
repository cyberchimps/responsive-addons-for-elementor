<?php
/**
 * RAEL Theme Builder's Product Content Widget.
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

use Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Post_Content;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * RAEL Theme Product Content widget class.
 *
 * @since 1.8.0
 */
class Responsive_Addons_For_Elementor_Theme_Product_Content extends Responsive_Addons_For_Elementor_Theme_Post_Content {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-woocommerce-product-content';
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
		return esc_html__( 'Product Content', 'responsive-addons-for-elementor' );
	}

	/**
	 * Retrieve the widget category.
	 *
	 * @since 1.8.0
	 * @access public
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
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
		return array( 'rael', 'content', 'post', 'product' );
	}
}
