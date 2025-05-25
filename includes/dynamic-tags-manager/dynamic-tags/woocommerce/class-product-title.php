<?php
namespace Responsive_Addons_For_Elementor\DynamicTagsManager\DynamicTags\WooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class RAEL_Product_Title extends Base_Tag {
	public function get_name() {
		return 'rael-product-title-tag';
	}

	public function get_title() {
		return esc_html__( 'Product Title', 'responsive-addons-for-elementor' );
	}

	protected function register_controls() {
		$this->add_product_id_control();
	}

	public function render() {
		$product = wc_get_product( $this->get_settings( 'product_id' ) );
		if ( ! $product ) {
			return;
		}

		echo wp_kses_post( $product->get_title() );
	}
}
