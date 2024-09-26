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
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/rea-advanced-tabs/',
				'category' => 'content',
			),
			array(
				'title'    => 'audio',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/audio-player',
				'category' => 'content',
			),
			array(
				'title'    => 'back-to-top',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/back-to-top',
				'category' => 'content',
			),
			array(
				'title'    => 'banner',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/banner',
				'category' => 'marketing',
			),
			array(
				'title'    => 'breadcrumbs',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/breadcrumbs',
				'category' => 'seo',
			),
			array(
				'title'    => 'business-hour',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/business-hour',
				'category' => 'marketing',
			),
			array(
				'title'    => 'button',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/button',
				'category' => 'marketing',
			),
			array(
				'title'    => 'call-to-action',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/call-to-action',
				'category' => 'marketing',
			),
			array(
				'title'    => 'cf-styler',
				'name'     => 'Contact Form Styler',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/contact-form-7-styler',
				'category' => 'form',
			),
			array(
				'title'    => 'content-switcher',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/content-switcher',
				'category' => 'content',
			),
			array(
				'title'    => 'content-ticker',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/content-ticker',
				'category' => 'content',
			),
			array(
				'title'    => 'countdown',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/countdown',
				'category' => 'creativity',
			),
			array(
				'title'    => 'data-table',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/data-table',
				'category' => 'creativity',
			),
			array(
				'title'    => 'divider',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/divider',
				'category' => 'creativity',
			),
			array(
				'title'    => 'dual-color-header',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/dual-color-header',
				'category' => 'creativity',
			),
			array(
				'title'    => 'faq',
				'name'     => 'FAQ',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/faq',
				'category' => 'seo',
			),
			array(
				'title'    => 'feature-list',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/feature-list',
				'category' => 'content',
			),
			array(
				'title'    => 'flip-box',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/flipbox',
				'category' => 'creativity',
			),
			array(
				'title'    => 'fancy-text',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/fancy-text',
				'category' => 'content',
			),
			array(
				'title'    => 'icon-box',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/icon-box',
				'category' => 'content',
			),
			array(
				'title'    => 'image-gallery',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/image-gallery',
				'category' => 'creativity',
			),
			array(
				'title'    => 'image-hotspot',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/image-hotspot',
				'category' => 'creativity',
			),
			array(
				'title'    => 'logo-carousel',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/logo-carousel',
				'category' => 'content',
			),
			array(
				'title'    => 'mc-styler',
				'name'     => 'MailChimp Styler',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/mailchimp-styler',
				'category' => 'form',
			),
			array(
				'title'    => 'multi-button',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/multibutton',
				'category' => 'content',
			),
			array(
				'title'    => 'one-page-navigation',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/one-page-navigation',
				'category' => 'creativity',
			),
			array(
				'title'    => 'product-category-grid',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/product-category-grid',
				'category' => 'woocommerce',
			),
			array(
				'title'    => 'progress-bar',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/progress-bar',
				'category' => 'content',
			),
			array(
				'title'    => 'reviews',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/reviews',
				'category' => 'marketing',
			),
			array(
				'title'    => 'search-form',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/search-form',
				'category' => 'form',
			),
			array(
				'title'    => 'slider',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/slider',
				'category' => 'content',
			),
			array(
				'title'    => 'sticky-video',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/sticky-video',
				'category' => 'marketing',
			),
			array(
				'title'    => 'table-of-contents',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/table-of-contents',
				'category' => 'content',
			),
			array(
				'title'    => 'team-member',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/team-member',
				'category' => 'content',
			),
			array(
				'title'    => 'testimonial-slider',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/testimonial-slider',
				'category' => 'marketing',
			),
			array(
				'title'    => 'timeline',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/timeline',
				'category' => 'creativity',
			),
			array(
				'title'    => 'twitter-feed',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/twitter-feed',
				'category' => 'marketing',
			),
			array(
				'title'    => 'video',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/video',
				'category' => 'content',
			),
			array(
				'title'    => 'woo-products',
				'name'     => 'WC Products',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/products',
				'category' => 'woocommerce',
			),
			array(
				'title'    => 'wpf-styler',
				'name'     => 'WP Form Styler',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/wp-forms-styler',
				'category' => 'form',
			),
			array(
				'title'    => 'breadcrumb',
				'name'     => 'WC Breadcrumbs',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/woocommerce-breadcrumbs',
				'category' => 'woocommerce',
			),
			array(
				'title'    => 'pricing-table',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/pricing-table',
				'category' => 'content',
			),
			array(
				'title'    => 'price-list',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/price-list',
				'category' => 'content',
			),
			array(
				'title'    => 'posts',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/posts',
				'category' => 'content',
			),
			array(
				'title'    => 'price-box',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/price-box',
				'category' => 'content',
			),
			array(
				'title'    => 'post-carousel',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/post-carousel',
				'category' => 'content',
			),
			array(
				'title'    => 'offcanvas',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/offcanvas',
				'category' => 'creativity',
			),
			array(
				'title'    => 'nav-menu',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/nav-menu',
				'category' => 'content',
			),
			array(
				'title'    => 'login-register',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/login-register',
				'category' => 'marketing',
			),
			array(
				'title'    => 'media-carousel',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/media-carousel',
				'category' => 'content',
			),
			array(
				'title'    => 'google-map',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/google-map',
				'category' => 'content',
			),
			array(
				'title'    => 'lottie',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/lottie/',
				'category' => 'creativity',
			),
			array(
				'title'    => 'product-carousel',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/product-carousel',
				'category' => 'woocommerce',
			),
			array(
				'title'    => 'woo-checkout',
				'name'     => 'WC Checkout',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/woo-checkout',
				'category' => 'woocommerce',
			),
			array(
				'title'    => 'portfolio',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/portfolio',
				'category' => 'content',
			),
			array(
				'title'    => 'menu-cart',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/menu-cart',
				'category' => 'woocommerce',
			),
			array(
				'title'    => 'wc-add-to-cart',
				'name'     => 'WC Add to Cart',
				'docs'     => 'https://cyberchimps.com/esponsive-addons-for-elementor/docs/custom-add-to-cart',
				'category' => 'woocommerce',
			),
			array(
				'title'    => 'modal-popup',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/modal-popup',
				'category' => 'marketing',
			),
			array(
				'title'    => 'gf-styler',
				'name'     => 'Gravity Forms Styler',
				'docs'     => 'https://cyberchimps.com/responsive-addons-for-elementor/docs/gravity-forms-styler',
				'category' => 'form',
			),
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

		if ( ! $this->is_widgets_in_db() ) {
			foreach ( $widgets as &$widget ) {
				$widget['status'] = 1;
			}
		}

		return $widgets;
	}

	/**
	 * Inserts the RAEL widgets into the database.
	 *
	 * @since 1.0.0
	 */
	public function insert_widgets_data() {

		$widgets = $this->initial_rael_widgets_data();

		add_option( 'rael_widgets', $widgets );
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
