<?php
/**
 * Updates the RAEL widgets.
 *
 * @link  https://www.cyberchimps.com
 * @since 1.0.0
 *
 * @package    responsive-addons-for-elementor
 * @subpackage responsive-addons-for-elementor/includes
 * @author     CyberChimps <support@cyberchimps.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Updates the RAEL widgets.
 *
 * @since      1.0.0
 * @package    responsive-addons-for-elementor
 * @subpackage responsive-addons-for-elementor/includes
 */
class Responsive_Addons_For_Elementor_Widgets_Updater {


	/**
	 * Retrives the RAEL widgets data.
	 *
	 * @since 1.0.0
	 */
	public function get_rael_widgets_data() {

		$widgets = array(
			array(
				'title'    => 'advanced-tabs',
				'docs'     => 'https://cyberchimps.com/docs/widgets/rea-advanced-tabs/',
				'category' => 'content',
			),
			array(
				'title'    => 'audio',
				'docs'     => 'https://cyberchimps.com/docs/widgets/audio-player',
				'category' => 'content',
			),
			array(
				'title'    => 'back-to-top',
				'docs'     => 'https://cyberchimps.com/docs/widgets/back-to-top',
				'category' => 'content',
			),
			array(
				'title'    => 'banner',
				'docs'     => 'https://cyberchimps.com/docs/widgets/banner',
				'category' => 'marketing',
			),
			array(
				'title'    => 'breadcrumbs',
				'docs'     => 'https://cyberchimps.com/docs/widgets/breadcrumbs',
				'category' => 'seo',
			),
			array(
				'title'    => 'business-hour',
				'docs'     => 'https://cyberchimps.com/docs/widgets/business-hour',
				'category' => 'marketing',
			),
			array(
				'title'    => 'button',
				'docs'     => 'https://cyberchimps.com/docs/widgets/button',
				'category' => 'marketing',
			),
			array(
				'title'    => 'call-to-action',
				'docs'     => 'https://cyberchimps.com/docs/widgets/call-to-action',
				'category' => 'marketing',
			),
			array(
				'title'    => 'cf-styler',
				'name'     => 'Contact Form Styler',
				'docs'     => 'https://cyberchimps.com/docs/widgets/contact-form-7-styler',
				'category' => 'form',
			),
			array(
				'title'    => 'content-switcher',
				'docs'     => 'https://cyberchimps.com/docs/widgets/content-switcher',
				'category' => 'content',
			),
			array(
				'title'    => 'content-ticker',
				'docs'     => 'https://cyberchimps.com/docs/widgets/content-ticker',
				'category' => 'content',
			),
			array(
				'title'    => 'countdown',
				'docs'     => 'https://cyberchimps.com/docs/widgets/countdown',
				'category' => 'creativity',
			),
			array(
				'title'    => 'data-table',
				'docs'     => 'https://cyberchimps.com/docs/widgets/data-table',
				'category' => 'creativity',
			),
			array(
				'title'    => 'divider',
				'docs'     => 'https://cyberchimps.com/docs/widgets/divider',
				'category' => 'creativity',
			),
			array(
				'title'    => 'dual-color-header',
				'docs'     => 'https://cyberchimps.com/docs/widgets/dual-color-header',
				'category' => 'creativity',
			),
			array(
				'title'    => 'faq',
				'name'     => 'FAQ',
				'docs'     => 'https://cyberchimps.com/docs/widgets/faq',
				'category' => 'seo',
			),
			array(
				'title'    => 'feature-list',
				'docs'     => 'https://cyberchimps.com/docs/widgets/feature-list',
				'category' => 'content',
			),
			array(
				'title'    => 'flip-box',
				'docs'     => 'https://cyberchimps.com/docs/widgets/flipbox',
				'category' => 'creativity',
			),
			array(
				'title'    => 'fancy-text',
				'docs'     => 'https://cyberchimps.com/docs/widgets/fancy-text',
				'category' => 'content',
			),
			array(
				'title'    => 'icon-box',
				'docs'     => 'https://cyberchimps.com/docs/widgets/icon-box',
				'category' => 'content',
			),
			array(
				'title'    => 'image-gallery',
				'docs'     => 'https://cyberchimps.com/docs/widgets/image-gallery',
				'category' => 'creativity',
			),
			array(
				'title'    => 'image-hotspot',
				'docs'     => 'https://cyberchimps.com/docs/widgets/image-hotspot',
				'category' => 'creativity',
			),
			array(
				'title'    => 'logo-carousel',
				'docs'     => 'https://cyberchimps.com/docs/widgets/logo-carousel',
				'category' => 'content',
			),
			array(
				'title'    => 'mc-styler',
				'name'     => 'MailChimp Styler',
				'docs'     => 'https://cyberchimps.com/docs/widgets/mailchimp-styler',
				'category' => 'form',
			),
			array(
				'title'    => 'multi-button',
				'docs'     => 'https://cyberchimps.com/docs/widgets/multibutton',
				'category' => 'content',
			),
			array(
				'title'    => 'one-page-navigation',
				'docs'     => 'https://cyberchimps.com/docs/widgets/one-page-navigation',
				'category' => 'creativity',
			),
			array(
				'title'    => 'product-category-grid',
				'docs'     => 'https://cyberchimps.com/docs/widgets/product-category-grid',
				'category' => 'woocommerce',
			),
			array(
				'title'    => 'progress-bar',
				'docs'     => 'https://cyberchimps.com/docs/widgets/progress-bar',
				'category' => 'content',
			),
			array(
				'title'    => 'reviews',
				'docs'     => 'https://cyberchimps.com/docs/widgets/reviews',
				'category' => 'marketing',
			),
			array(
				'title'    => 'search-form',
				'docs'     => 'https://cyberchimps.com/docs/widgets/search-form',
				'category' => 'form',
			),
			array(
				'title'    => 'slider',
				'docs'     => 'https://cyberchimps.com/docs/widgets/slider',
				'category' => 'content',
			),
			array(
				'title'    => 'sticky-video',
				'docs'     => 'https://cyberchimps.com/docs/widgets/sticky-video',
				'category' => 'marketing',
			),
			array(
				'title'    => 'table-of-contents',
				'docs'     => 'https://cyberchimps.com/docs/widgets/table-of-contents',
				'category' => 'content',
			),
			array(
				'title'    => 'team-member',
				'docs'     => 'https://cyberchimps.com/docs/widgets/team-member',
				'category' => 'content',
			),
			array(
				'title'    => 'testimonial-slider',
				'docs'     => 'https://cyberchimps.com/docs/widgets/testimonial-slider',
				'category' => 'marketing',
			),
			array(
				'title'    => 'timeline',
				'docs'     => 'https://cyberchimps.com/docs/widgets/timeline',
				'category' => 'creativity',
			),
			array(
				'title'    => 'twitter-feed',
				'docs'     => 'https://cyberchimps.com/docs/widgets/twitter-feed',
				'category' => 'marketing',
			),
			array(
				'title'    => 'video',
				'docs'     => 'https://cyberchimps.com/docs/widgets/video',
				'category' => 'content',
			),
			array(
				'title'    => 'woo-products',
				'name'     => 'WC Products',
				'docs'     => 'https://cyberchimps.com/docs/widgets/products',
				'category' => 'woocommerce',
			),
			array(
				'title'    => 'wpf-styler',
				'name'     => 'WP Form Styler',
				'docs'     => 'https://cyberchimps.com/docs/widgets/wp-forms-styler',
				'category' => 'form',
			),
			array(
				'title'    => 'breadcrumb',
				'name'     => 'WC Breadcrumbs',
				'docs'     => 'https://cyberchimps.com/docs/widgets/woocommerce-breadcrumbs',
				'category' => 'woocommerce',
			),
			array(
				'title'    => 'pricing-table',
				'docs'     => 'https://cyberchimps.com/docs/widgets/pricing-table',
				'category' => 'content',
			),
			array(
				'title'    => 'price-list',
				'docs'     => 'https://cyberchimps.com/docs/widgets/price-list',
				'category' => 'content',
			),
			array(
				'title'    => 'posts',
				'docs'     => 'https://cyberchimps.com/docs/widgets/posts',
				'category' => 'content',
			),
			array(
				'title'    => 'price-box',
				'docs'     => 'https://cyberchimps.com/docs/widgets/price-box',
				'category' => 'content',
			),
			array(
				'title'    => 'post-carousel',
				'docs'     => 'https://cyberchimps.com/docs/widgets/post-carousel',
				'category' => 'content',
			),
			array(
				'title'    => 'offcanvas',
				'docs'     => 'https://cyberchimps.com/docs/widgets/offcanvas',
				'category' => 'creativity',
			),
			array(
				'title'    => 'nav-menu',
				'docs'     => 'https://cyberchimps.com/docs/widgets/nav-menu',
				'category' => 'content',
			),
			array(
				'title'    => 'login-register',
				'docs'     => 'https://cyberchimps.com/docs/widgets/login-register',
				'category' => 'marketing',
			),
			array(
				'title'    => 'media-carousel',
				'docs'     => 'https://cyberchimps.com/docs/widgets/media-carousel',
				'category' => 'content',
			),
			array(
				'title'    => 'google-map',
				'docs'     => 'https://cyberchimps.com/docs/widgets/google-map',
				'category' => 'content',
			),
			array(
				'title'    => 'lottie',
				'docs'     => 'https://cyberchimps.com/docs/widgets/lottie/',
				'category' => 'creativity',
			),
			array(
				'title'    => 'product-carousel',
				'docs'     => 'https://cyberchimps.com/docs/widgets/product-carousel',
				'category' => 'woocommerce',
			),
			array(
				'title'    => 'woo-checkout',
				'name'     => 'WC Checkout',
				'docs'     => 'https://cyberchimps.com/docs/widgets/woo-checkout',
				'category' => 'woocommerce',
			),
			array(
				'title'    => 'portfolio',
				'docs'     => 'https://cyberchimps.com/docs/widgets/portfolio',
				'category' => 'content',
			),
			array(
				'title'    => 'menu-cart',
				'docs'     => 'https://cyberchimps.com/docs/widgets/menu-cart',
				'category' => 'woocommerce',
			),
			array(
				'title'    => 'wc-add-to-cart',
				'name'     => 'WC Add to Cart',
				'docs'     => 'https://cyberchimps.com/docs/widgets/custom-add-to-cart',
				'category' => 'woocommerce',
			),
			array(
				'title'    => 'modal-popup',
				'docs'     => 'https://cyberchimps.com/docs/widgets/modal-popup',
				'category' => 'marketing',
			),
			array(
				'title'    => 'gf-styler',
				'name'     => 'Gravity Forms Styler',
				'docs'     => 'https://cyberchimps.com/docs/widgets/gravity-forms-styler',
				'category' => 'form',
			),
			array(
				'title'    => 'facebook-feed',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/facebook-feed/',
				'category' => 'marketing',
			)
		);

		return $widgets;
	}

	/**
	 * Check if RAEL widgets exists in database.
	 *
	 * @since 1.0.0
	 */
	public function is_widgets_in_db() {

		$rael_widgets = get_option( 'rael_widgets' );

		if ( ! $rael_widgets ) {
			return false;
		}
		return true;
	}

	/**
	 * Initial RAEL widgets array with status 1.
	 *
	 * @since 1.0.0
	 */
	public function initial_rael_widgets_data() {

		$widgets = $this->get_rael_widgets_data();

		foreach ( $widgets as &$widget ) {
			$widget['status'] = 1;
		}

		return $widgets;
	}

	/**
	 * Inserts the RAEL widgets into the database.
	 *
	 * @since 1.0.0
	 */
	public function insert_widgets_data() {

		$rael_widgets = $this->is_widgets_in_db();
		$widgets = $this->initial_rael_widgets_data();

		if($rael_widgets) {
			update_option( 'rael_widgets', $widgets );
		} else {
			add_option( 'rael_widgets', $widgets );
		}
	}

	/**
	 * Reset the RAEL widgets into the database.
	 */
	public function reset_widgets_data() {

		$delete_widgets = delete_option( 'rael_widgets' );
		if ( $delete_widgets ) {
			$widgets = $this->initial_rael_widgets_data();
			add_option( 'rael_widgets', $widgets );
		}

	}

}
