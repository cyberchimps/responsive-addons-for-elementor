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
				'name'     => 'Advanced Tabs',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/rea-advanced-tabs/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/advanced-tabs/',
				'category' => 'content',
			),
			array(
				'title'    => 'animations',
				'name'     => 'Animations',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/rae-animations/',
				'demo'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/rae-animations/',
				'category' => 'extensions',
			),
			array(
				'title'    => 'audio',
				'name'     => 'Audio',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/audio-player',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/audio-player-widget/',
				'category' => 'content',
			),
			array(
				'title'    => 'theme-author-box',
				'name'     => 'Author box',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/author-box/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/author-box-widget/',
				'category' => 'marketing',
			),
			array(
				'title'    => 'woocommerce-theme-archive-product-description',
				'name'     => 'Archive description',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/archive-description/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'woocommerce-theme-product-archive',
				'name'     => 'Product Archive',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/archive-product/',
				'category' => 'themebuilder',
			),

			array(
				'title'    => 'theme-archive-posts',
				'name'     => 'Archive posts',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/archive-posts/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/archive-posts/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'theme-archive-title',
				'name'     => 'Archive title',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/archive-title/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/archive-title/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'back-to-top',
				'name'     => 'Back To Top',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/back-to-top',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/back-to-top/',
				'category' => 'content',
			),
			array(
				'title'    => 'banner',
				'name'     => 'Banner',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/banner',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/banner-widget/',
				'category' => 'marketing',
			),
			array(
				'title'    => 'before-after-slider',
				'name'     => 'Before After Slider',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/before-after-slider/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/before-after-widget/',
				'category' => 'creativity',
			),
			array(
				'title'    => 'breadcrumbs',
				'name'     => 'Breadcrumbs',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/breadcrumbs',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/breadcrumb-widget/',
				'category' => 'seo',
			),
			array(
				'title'    => 'business-hour',
				'name'     => 'Business Hour',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/business-hour',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/business-hour-widget/',
				'category' => 'marketing',
			),
			array(
				'title'    => 'button',
				'name'     => 'Button',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/button',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/button-widget/',
				'category' => 'marketing',
			),
			array(
				'title'    => 'call-to-action',
				'name'     => 'Call To Action',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/call-to-action',
				'demo'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/call-to-action-2/',
				'category' => 'marketing',
			),
			array(
				'title'    => 'cf-styler',
				'name'     => 'Contact Form Styler',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/contact-form-7-styler',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/contact-form-7-styler/',
				'category' => 'form',
			),
			array(
				'title'    => 'content-switcher',
				'name'     => 'Content Switcher',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/content-switcher',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/content-switcher-widget/',
				'category' => 'content',
			),
			array(
				'title'    => 'content-ticker',
				'name'     => 'Content Ticker',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/content-ticker',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/content-ticker/',
				'category' => 'content',
			),
			array(
				'title'    => 'countdown',
				'name'     => 'Countdown',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/countdown',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/countdown-widget/',
				'category' => 'creativity',
			),
			array(
				'title'    => 'data-table',
				'name'     => 'Data Table',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/data-table',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/data-table-widget/',
				'category' => 'creativity',
			),
			array(
				'title'    => 'divider',
				'name'     => 'Divider',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/divider',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/divider/',
				'category' => 'creativity',
			),
			array(
				'title'    => 'dual-color-header',
				'name'     => 'Dual Color Header',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/dual-color-header',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/dual-color-header-widget/',
				'category' => 'creativity',
			),
			array(
				'title'    => 'duplicator',
				'name'     => 'Duplicator',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/rae-duplicator/',
				'demo'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/rae-duplicator/',
				'category' => 'extensions',
			),
			array(
				'title'    => 'facebook-feed',
				'name'     => 'Facebook Feed',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/facebook-feed/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/facebook-feed/',
				'category' => 'marketing',
			),
			array(
				'title'    => 'fancy-text',
				'name'     => 'Fancy Text',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/fancy-text',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/fancy-text/',
				'category' => 'content',
			),
			array(
				'title'    => 'faq',
				'name'     => 'FAQ',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/faq',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/faq/',
				'category' => 'seo',
			),
			array(
				'title'    => 'feature-list',
				'name'     => 'Feature List',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/feature-list',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/features-list-widget/',
				'category' => 'content',
			),

			array(
				'title'    => 'theme-post-featured-image',
				'name'     => 'Featured image',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/featured-image/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/feature-image-widget/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'flip-box',
				'name'     => 'Flip Box',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/flipbox',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/flip-box-widget/',
				'category' => 'creativity',
			),
			array(
				'title'    => 'gf-styler',
				'name'     => 'Gravity Forms Styler',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/gravity-forms-styler',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/gravity-form-styler/',
				'category' => 'form',
			),
			array(
				'title'    => 'google-map',
				'name'     => 'Google Map',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/google-map',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/google-maps-widget/',
				'category' => 'content',
			),
			array(
				'title'    => 'icon-box',
				'name'     => 'Icon Box',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/icon-box',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/icon-box/',
				'category' => 'content',
			),
			array(
				'title'    => 'image-gallery',
				'name'     => 'Image Gallery',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/image-gallery',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/image-gallery-widget/',
				'category' => 'creativity',
			),
			array(
				'title'    => 'image-hotspot',
				'name'     => 'Image Hotspot',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/image-hotspot',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/image-hotspot-widget/',
				'category' => 'creativity',
			),
			array(
				'title'    => 'login-register',
				'name'     => 'Login Register',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/login-register',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/login-registration-form/',
				'category' => 'marketing',
			),
			array(
				'title'    => 'logo-carousel',
				'name'     => 'Logo Carousel',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/logo-carousel',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/logo-carousel-widget/',
				'category' => 'content',
			),
			array(
				'title'    => 'mc-styler',
				'name'     => 'MailChimp Styler',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/mailchimp-styler',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/mailchimp-styler/',
				'category' => 'form',
			),
			array(
				'title'    => 'media-carousel',
				'name'     => 'Media Carousel',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/media-carousel',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/media-carousel-widget/',
				'category' => 'content',
			),
			array(
				'title'    => 'menu-cart',
				'name'     => 'Menu Cart',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/menu-cart',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/menu-cart/',
				'category' => 'woocommerce',
			),
			array(
				'title'    => 'modal-popup',
				'name'     => 'Modal Popup',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/modal-popup',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/modal-popup-widget/',
				'category' => 'marketing',
			),
			array(
				'title'    => 'multi-button',
				'name'     => 'Multi Button',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/multibutton',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/multi-button-widget/',
				'category' => 'content',
			),
			array(
				'title'    => 'nav-menu',
				'name'     => 'Nav Menu',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/nav-menu',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/nav-menu-widget/',
				'category' => 'content',
			),
			array(
				'title'    => 'offcanvas',
				'name'     => 'Offcanvas',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/offcanvas',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/off-canvas/',
				'category' => 'creativity',
			),
			array(
				'title'    => 'one-page-navigation',
				'name'     => 'One Page Navigation',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/one-page-navigation',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/one-page-navigation-widget/',
				'category' => 'creativity',
			),
			array(
				'title'    => 'particle-backgrounds',
				'name'     => 'Particle Backgrounds',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/particle-backgrounds-for-elementor/',
				'demo'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/particle-backgrounds-for-elementor/',
				'category' => 'extensions',
			),
			array(
				'title'    => 'portfolio',
				'name'     => 'Portfolio',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/portfolio',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/portfolio-widget/',
				'category' => 'content',
			),
			array(
				'title'    => 'post-carousel',
				'name'     => 'Post Carousel',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/post-carousel',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/post-carousel/',
				'category' => 'content',
			),
			array(
				'title'    => 'posts',
				'name'     => 'Posts',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/posts',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/post-widget/',
				'category' => 'content',
			),
			array(
				'title'    => 'price-box',
				'name'     => 'Price Box',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/price-box',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/price-box-widget/',
				'category' => 'content',
			),
			array(
				'title'    => 'price-list',
				'name'     => 'Price List',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/price-list',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/price-list/',
				'category' => 'content',
			),
			array(
				'title'    => 'pricing-table',
				'name'     => 'Pricing Table',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/pricing-table',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/pricing-table/',
				'category' => 'content',
			),
			array(
				'title'    => 'product-carousel',
				'name'     => 'Product Carousel',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/product-carousel',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/product-carousel/',
				'category' => 'woocommerce',
			),
			array(
				'title'    => 'product-category-grid',
				'name'     => 'Product Category Grid',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/product-category-grid',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/product-category-grid/',
				'category' => 'woocommerce',
			),
			array(
				'title'    => 'progress-bar',
				'name'     => 'Progress Bar',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/progress-bar',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/progress-bar/',
				'category' => 'content',
			),

			array(
				'title'    => 'woocommerce-theme-product-content',
				'name'     => 'Product content',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/product-content/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/product-content-widget/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'woocommerce-theme-product-data-tabs',
				'name'     => 'Product data tabs',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/product-data-tabs/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'woocommerce-theme-product-images',
				'name'     => 'Product images',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/product-image/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/product-image-widget/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'woocommerce-theme-product-meta',
				'name'     => 'Product meta',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/product-meta/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/product-meta-widget/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'woocommerce-theme-product-price',
				'name'     => 'Product price',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/product-price/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/product-price-widget/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'woocommerce-theme-product-rating',
				'name'     => 'Product rating',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/product-rating/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/product-rating-widget/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'woocommerce-theme-product-related',
				'name'     => 'Product related',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/related-products-widget/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'woocommerce-theme-product-short-description',
				'name'     => 'Product short description',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/short-description/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/product-short-description-widget/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'woocommerce-theme-product-stock',
				'name'     => 'Product stock',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/product-stock/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/product-stock/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'woocommerce-theme-product-title',
				'name'     => 'Product title',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/product-title/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/product-title-widget/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'woocommerce-theme-product-upsell',
				'name'     => 'Product upsells',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/product-upsells/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/product-upsells/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'woocommerce-theme-product-additional-info',
				'name'     => 'Product additional info',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/product-additional-information/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/product-additional-information-widget/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'theme-post-comments',
				'name'     => 'Post comments',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/post-comments/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/post-comment-widget/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'theme-post-content',
				'name'     => 'Post content',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/post-content/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/post-content/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'theme-post-excerpt',
				'name'     => 'Post excerpt',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/post-excerpt/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/post-excerpt-widget/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'theme-post-info',
				'name'     => 'Post info',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/post-info/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/post-info/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'theme-post-navigation',
				'name'     => 'Post navigation',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/post-navigation/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/post-navigation-widget/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'theme-post-title',
				'name'     => 'Post title',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/post-title/',
				'demo'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/post-title/',
				'category' => 'themebuilder',
			),
			array(
				'title'    => 'reviews',
				'name'     => 'Reviews',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/reviews',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/reviews/',
				'category' => 'marketing',
			),
			array(
				'title'    => 'search-form',
				'name'     => 'Search Form',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/search-form',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/search-form-styler/',
				'category' => 'form',
			),
			array(
				'title'    => 'theme-site-logo',
				'name'     => 'Site logo',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/site-logo/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/site-logo-widget/',
				'category' => 'marketing',
			),
			array(
				'title'    => 'slider',
				'name'     => 'Slider',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/slider',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/slider/',
				'category' => 'content',
			),
			array(
				'title'    => 'stacking-cards',
				'name'     => 'Stacking Cards',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/stacking-cards/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/stacking-cards-widget/',
				'category' => 'creativity',
			),
			array(
				'title'    => 'sticky-section',
				'name'     => 'Sticky Section',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/sticky-section/',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/sticky-section-widget/',
				'category' => 'extensions',
			),
			array(
				'title'    => 'sticky-video',
				'name'     => 'Sticky Video',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/sticky-video',
				'demo'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/sticky-video',
				'category' => 'marketing',
			),
			array(
				'title'    => 'table-of-contents',
				'name'     => 'Table of Contents',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/table-of-contents',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/table-of-contents-widget/',
				'category' => 'content',
			),
			array(
				'title'    => 'team-member',
				'name'     => 'Team Member',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/team-member',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/team-member-widget/',
				'category' => 'content',
			),
			array(
				'title'    => 'testimonial-slider',
				'name'     => 'Testimonial Slider',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/testimonial-slider',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/testimonial-slider-widget/',
				'category' => 'marketing',
			),
			array(
				'title'    => 'timeline',
				'name'     => 'Timeline',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/timeline',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/timeline/',
				'category' => 'creativity',
			),
			array(
				'title'    => 'twitter-feed',
				'name'     => 'Twiter Feed',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/twitter-feed',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/twitter-feeds-widget/',
				'category' => 'marketing',
			),
			array(
				'title'    => 'video',
				'name'     => 'Video',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/video',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/video/',
				'category' => 'content',
			),
			array(
				'title'    => 'woo-products',
				'name'     => 'WC Products',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/products',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/product-widget/',
				'category' => 'woocommerce',
			),
			array(
				'title'    => 'wpf-styler',
				'name'     => 'WP Form Styler',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/wp-forms-styler',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/wpforms-styler-widget/',
				'category' => 'form',
			),
			array(
				'title'    => 'wc-add-to-cart',
				'name'     => 'WC Add to Cart',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/custom-add-to-cart',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/custom-add-to-cart/',
				'category' => 'woocommerce',
			),
			array(
				'title'    => 'breadcrumb',
				'name'     => 'WC Breadcrumbs',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/woocommerce-breadcrumbs',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/woocommerce-breadcrumbs-widget/',
				'category' => 'woocommerce',
			),
			array(
				'title'    => 'woo-checkout',
				'name'     => 'WC Checkout',
				'docs'     => 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/woo-checkout',
				'demo'     => 'https://cyberchimps.com/responsive-addons-for-elementor/woo-checkout/',
				'category' => 'woocommerce',
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
		$widgets      = $this->initial_rael_widgets_data();

		if ( $rael_widgets ) {
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
