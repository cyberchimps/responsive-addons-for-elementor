<?php

namespace Responsive_Addons_For_Elementor\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Plugin;
use \Responsive_Addons_For_Elementor\Helper\Helper as HelperClass;
use \Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Woocommerce\Woo_Checkout;

trait Helper_Woo_Checkout {

	// use \Responsive_Addons_For_Elementor\Traits\Woo_Checkout_Helper;
	/**
	 * @param string     $tag
	 * @param string     $function_to_remove
	 * @param int|string $priority
	 */
	public function rael_forcefully_remove_action( $tag, $function_to_remove, $priority ) {
		global $wp_filter;
		if ( isset( $wp_filter[ $tag ][ $priority ] ) && is_array( $wp_filter[ $tag ][ $priority ] ) ) {
			foreach ( $wp_filter[ $tag ][ $priority ] as $callback_function => $registration ) {
				if ( strlen( $callback_function ) > 32 && strpos( $callback_function, $function_to_remove, 32 ) !== false || $callback_function === $function_to_remove ) {
					remove_action( $tag, $callback_function, $priority );
					break;
				}
			}
		}
	}

	/**
	 * Validate woocommerce post code
	 *
	 * @since  1.8.0
	 */
	public function rael_woo_checkout_post_code_validate() {
		$data = $_POST['data'];
		$validate = true;
		if ( isset( $data['postcode'] ) ) {

			$format = wc_format_postcode( $data['postcode'], $data['country'] );
			if ( '' !== $format && ! \WC_Validation::is_postcode( $data['postcode'], $data['country'] ) ) {
				$validate = false;
			}
		}
		wp_send_json( $validate );
	}

}
