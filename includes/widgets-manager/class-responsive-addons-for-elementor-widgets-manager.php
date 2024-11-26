<?php
/**
 * Widgets Manager for Responsive Addons for Elementor
 *
 * @package responsive-addons-for-elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager;

use Elementor\Plugin;
use Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Posts;

if ( ! defined( 'WPINC' ) ) {
	exit;
}
/**
 * Class Responsive_Addons_For_Elementor_Widgets_Manager
 *
 * @package Responsive_Addons_For_Elementor\WidgetsManager
 */
class Responsive_Addons_For_Elementor_Widgets_Manager {

	const TEMPLATE_MINI_CART        = 'cart/mini-cart.php';
	const OPTION_NAME_USE_MINI_CART = 'use_mini_cart_template';

	/**
	 * Represents the singleton instance.
	 *
	 * @var null|self
	 */
	private static $instance = null;

	/**
	 * Represents the singleton instance.
	 *
	 * @var string
	 */
	protected $use_mini_cart_template;

	/**
	 * Get instance of Responsive_Addons_For_Elementor_Widgets_Manager
	 *
	 * @return Responsive_Addons_For_Elementor_Widgets_Manager
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Register Custom Controls.
		$this->register_modules();

		$this->use_mini_cart_template = 'yes' === get_option( 'elementor_' . self::OPTION_NAME_USE_MINI_CART, 'no' );

		if ( ! empty( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && is_admin() && class_exists( 'WooCommerce' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			add_action( 'init', array( $this, 'register_wc_hooks' ), 5 );
		}

		if ( class_exists( 'WooCommerce' ) ) {
			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'maybe_init_cart' ) );

			if ( $this->use_mini_cart_template ) {
				add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'menu_cart_fragments' ) );
				add_filter( 'woocommerce_locate_template', array( $this, 'woocommerce_locate_template' ), 10, 3 );
			}
		}

		// Register category for responsive addons for elementor.
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_responsive_widget_category' ) );

		// Register all the widgets.
		add_action( 'elementor/widgets/register', array( $this, 'register_responsive_widgets' ) );

		// Register Admin Scripts.
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'editor_scripts' ) );

		// Register all Controls.
		add_action( 'elementor/controls/register', array( $this, 'register_responsive_controls' ) );

		add_action( 'wp_head', array( $this, 'render_faq_schema' ) );
	}

	/**
	 * Register editor scripts.
	 */
	public function editor_scripts() {

		// Elementor Custom Scripts.
		wp_register_script(
			'rael-elementor-editor',
			RAEL_URL . 'assets/js/editor/rael-editor.js',
			array( 'jquery-elementor-select2' ),
			RAEL_VER,
			false
		);

		wp_enqueue_script( 'rael-elementor-editor' );
	}

	/**
	 * Register WC Hooks.
	 */
	public function register_wc_hooks() {
		wc()->frontend_includes();
	}

	/**
	 * Place your widgets name list here for responsive addons for elementor
	 *
	 * @return array
	 */
	public static function get_responsive_widgets_list() {
		$widget_list = array(
			'flip-box',
			'call-to-action',
			'audio',
			'content-switcher',
			'timeline',
			'sticky-video',
			'table-of-contents',
			'team-member',
			'testimonial-slider',
			'twitter-feed',
			'video',
			'one-page-navigation',
			'logo-carousel',
			'data-table',
			'advanced-tabs',
			'cf-styler',
			'content-ticker',
			'wpf-styler',
			'back-to-top',
			'banner',
			'breadcrumbs',
			'business-hour',
			'button',
			'countdown',
			'divider',
			'dual-color-header',
			'fancy-text',
			'faq',
			'feature-list',
			'icon-box',
			'image-gallery',
			'image-hotspot',
			'mc-styler',
			'progress-bar',
			'reviews',
			'search-form',
			'slider',
			'multi-button',
			'pricing-table',
			'price-list',
			'posts',
			'price-box',
			'post-carousel',
			'offcanvas',
			'nav-menu',
			'login-register',
			'media-carousel',
			'google-map',
			'lottie',
			'portfolio',
			'modal-popup',
			'gf-styler',
		);

		return $widget_list;
	}

	/**
	 * Place your woocommerce widgets name list here to include them in responsive addons for elementor
	 *
	 * @return array
	 */
	public static function get_woocommerce_responsive_widget_list() {
		$widget_list = array(
			'woo-products',
			'breadcrumb',
			'product-category-grid',
			'product-carousel',
			'woo-checkout',
			'menu-cart',
			'wc-add-to-cart',
		);

		return $widget_list;
	}

	/**
	 * Place your theme builder widgets name list here to include them in responsive addons for elementor
	 *
	 * @return array
	 */
	public function get_theme_builder_widgets_list() {
		// Prefix the widget name with 'woocommerce' but keep the class file name as it should be.
		// This is just to identify if the widget depends on WooCommerce.
		$widgets_list = array(
			'theme-post-title',
			'theme-post-featured-image',
			'theme-post-content',
			'theme-post-info',
			'theme-post-excerpt',
			'theme-site-logo',
			'theme-author-box',
			'theme-post-comments',
			'theme-post-navigation',
			'theme-archive-title',
			'theme-archive-posts',
			'woocommerce-theme-product-title',
			'woocommerce-theme-product-images',
			'woocommerce-theme-product-additional-info',
			'woocommerce-theme-product-price',
			'woocommerce-theme-product-rating',
			'woocommerce-theme-product-stock',
			'woocommerce-theme-product-meta',
			'woocommerce-theme-product-short-description',
			'woocommerce-theme-product-related',
			'woocommerce-theme-product-content',
			'woocommerce-theme-product-data-tabs',
			'woocommerce-theme-product-upsell',
			'woocommerce-woo-products',
			'woocommerce-theme-product-archive',
			'woocommerce-theme-archive-product-description',
		);

		return $widgets_list;
	}

	/**
	 *  Include files for the responsive elementor controls
	 */
	public function register_responsive_controls() {

		$controls_manager = Plugin::$instance->controls_manager;

		include_once RAEL_DIR . '/includes/widgets-manager/controls/class-responsive-addons-for-elementor-control-media-select.php';
		include_once RAEL_DIR . '/includes/widgets-manager/controls/class-responsive-addons-for-elementor-control-visual-select.php';
		require_once RAEL_DIR . '/includes/widgets-manager/controls/class-responsive-addons-for-elementor-ajax-select2.php';

		$controls_manager->register( new Controls\Responsive_Addons_For_Elementor_Control_Media_Select() );
		$controls_manager->register( new Controls\Responsive_Addons_For_Elementor_Control_Visual_Select() );
		$controls_manager->register( new Controls\Responsive_Addons_For_Elementor_Control_Ajax_Select2() );
	}

	/**
	 * Include classes required for responsive elementor widgets.
	 */
	public function include_widget_classes() {

		require_once RAEL_DIR . '/includes/widgets-manager/widgets/skins/class-rael-skin-base.php';
		require_once RAEL_DIR . '/includes/widgets-manager/widgets/skins/class-rael-skin-classic.php';
		require_once RAEL_DIR . '/includes/widgets-manager/widgets/skins/class-rael-skin-cards.php';
		require_once RAEL_DIR . '/includes/widgets-manager/widgets/skins/class-rael-skin-content-base.php';
		require_once RAEL_DIR . '/includes/widgets-manager/widgets/skins/class-rael-skin-full-content.php';

		// RAEL Posts Archive Skins.
		require_once RAEL_DIR . '/includes/widgets-manager/widgets/skins/archive-posts/skin-base.php';
		require_once RAEL_DIR . '/includes/widgets-manager/widgets/skins/archive-posts/class-rael-posts-archive-skin-cards.php';
		require_once RAEL_DIR . '/includes/widgets-manager/widgets/skins/archive-posts/class-rael-posts-archive-skin-classic.php';
		require_once RAEL_DIR . '/includes/widgets-manager/widgets/skins/archive-posts/class-rael-posts-archive-skin-full-content.php';

		if ( class_exists( 'WooCommerce' ) ) {
			// Woocommerce Classes.
			require_once RAEL_DIR . '/includes/widgets-manager/modules/woocommerce/classes/class-base-products-renderer.php';
			require_once RAEL_DIR . '/includes/widgets-manager/modules/woocommerce/classes/class-current-query-renderer.php';
			require_once RAEL_DIR . '/includes/widgets-manager/modules/woocommerce/classes/class-products-renderer.php';
		}

		// Theme Builder Classes.
		require_once RAEL_DIR . '/includes/widgets-manager/widgets/theme-builder/class-responsive-addons-for-elementor-title-widget-base.php';

		if ( class_exists( 'WooCommerce' ) ) {
			require_once RAEL_DIR . '/includes/widgets-manager/widgets/theme-builder/class-responsive-addons-for-elementor-woo-widget-base.php';
			require_once RAEL_DIR . '/includes/widgets-manager/widgets/theme-builder/class-responsive-addons-for-elementor-woo-products-base.php';
			require_once RAEL_DIR . '/includes/widgets-manager/widgets/theme-builder/class-responsive-addons-for-elementor-woo-products.php';
		}
	}

	/**
	 *  Include all the files for responsive elementor widgets
	 */
	public function include_responsive_widgets_files() {
		$widget_list                = $this->get_responsive_widgets_list();
		$theme_builder_widgets_list = $this->get_theme_builder_widgets_list();

		if ( ! empty( $widget_list ) ) {
			foreach ( $widget_list as $handle => $data ) {
				include_once RAEL_DIR . 'includes/widgets-manager/widgets/class-responsive-addons-for-elementor-' . $data . '.php';
			}
		}

		if ( ! empty( $theme_builder_widgets_list ) ) {
			foreach ( $theme_builder_widgets_list as $handle => $data ) {
				if ( str_starts_with( $data, 'woocommerce' ) ) {
					$file = RAEL_DIR . '/includes/widgets-manager/widgets/theme-builder/class-responsive-addons-for-elementor-' . substr( $data, 12 ) . '.php';

					if ( class_exists( 'Woocommerce' ) && file_exists( $file ) ) {
						require_once $file;
					}
				} else {
					$file = RAEL_DIR . '/includes/widgets-manager/widgets/theme-builder/class-responsive-addons-for-elementor-' . $data . '.php';

					if ( file_exists( $file ) ) {
						require_once $file;
					}
				}
			}
		}

		$woo_widget_list = $this->get_woocommerce_responsive_widget_list();
		if ( ! empty( $woo_widget_list ) ) {
			foreach ( $woo_widget_list as $handle => $data ) {
				require_once RAEL_DIR . 'includes/widgets-manager/widgets/woocommerce/class-responsive-addons-for-elementor-' . $data . '.php';
			}
		}

	}

	/**
	 * Register new category for Responsive Addons for Elementor
	 *
	 * @param  object $elements_manager class.
	 * @return mixed
	 */
	public function register_responsive_widget_category( $elements_manager ) {
		$category = __( 'Responsive Addons for Elementor', 'responsive-addons-for-elementor' );

		$elements_manager->add_category(
			'responsive-addons-for-elementor',
			array(
				'title' => $category,
				'icon'  => 'eicon-font',
			)
		);

		return $elements_manager;
	}
	/**
	 * Register query control modules for all widgets requiring query module to fetch data.
	 */
	public function register_modules() {

		$modules = array(
			array(
				'file'  => RAEL_DIR . 'includes/widgets-manager/modules/query-control/class-module.php',
				'class' => 'Modules\QueryControl\Module',
			),
			array(
				'file'  => RAEL_DIR . 'includes/widgets-manager/modules/single-query-control/class-module.php',
				'class' => 'Modules\SingleQueryControl\Module',
			),
		);

		foreach ( $modules as $module ) {
			if ( ! empty( $module['file'] ) && ! empty( $module['class'] ) ) {
				include_once $module['file'];

				if ( isset( $module['instance'] ) ) {
					continue;
				}

				if ( class_exists( __NAMESPACE__ . '\\' . $module['class'] ) ) {
					$class_name = __NAMESPACE__ . '\\' . $module['class'];
				} else {
					continue;
				}
				new $class_name();
			}
		}
	}

	/**
	 * Register the responsive elementor widgets
	 *
	 * @throws \Exception Throws Exception.
	 */
	public function register_responsive_widgets() {

		$this->include_widget_classes();

		$this->include_responsive_widgets_files();

		// Theme Builder widgets.
		Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Post_Excerpt() );
		Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Post_Title() );
		Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Post_Featured_Image() );
		Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Post_Content() );
		Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Post_Info() );
		Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Site_Logo() );
		Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Author_Box() );
		Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Post_Comments() );
		Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Post_Navigation() );
		Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Archive_Title() );
		Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Archive_Posts() );
		if ( class_exists( 'WooCommerce' ) ) {
			Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Product_Title() );
			Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Product_Images() );
			Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Product_Additional_Info() );
			Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Product_Related() );
			Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Product_Price() );
			Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Product_Rating() );
			Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Product_Stock() );
			Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Product_Meta() );
			Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Product_Short_Description() );
			Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Product_Content() );
			Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Product_Data_Tabs() );
			Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Product_Upsell() );
			Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Woo_Products() );
			Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Product_Archive() );
			Plugin::instance()->widgets_manager->register( new Widgets\ThemeBuilder\Responsive_Addons_For_Elementor_Theme_Archive_Product_Description() );
		}

		$rael_widgets = get_option( 'rael_widgets' );
		if ( ! $rael_widgets ) {
			include_once RAEL_DIR . 'includes/class-responsive-addons-for-elementor-widgets-updater.php';
			$rael_widgets_data = new \Responsive_Addons_For_Elementor_Widgets_Updater();
			$rael_widgets_data->insert_widgets_data();
			$rael_widgets = get_option( 'rael_widgets' );
		}
		if ( is_array( $rael_widgets ) ) {
			foreach ( $rael_widgets as $rael_widget ) {
				if ( $rael_widget['status'] ) {
					switch ( $rael_widget['title'] ) {
						case 'audio':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Audio() );
							break;
						case 'back-to-top':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Back_To_Top() );
							break;
						case 'banner':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Banner() );
							break;
						case 'breadcrumbs':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Breadcrumbs() );
							break;
						case 'business-hour':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Business_Hour() );
							break;
						case 'button':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Button() );
							break;
						case 'call-to-action':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Call_To_Action() );
							break;
						case 'content-switcher':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Content_Switcher() );
							break;
						case 'countdown':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Countdown() );
							break;
						case 'divider':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Divider() );
							break;
						case 'dual-color-header':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Dual_Color_Header() );
							break;
						case 'fancy-text':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Fancy_Text() );
							break;
						case 'faq':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_FAQ() );
							break;
						case 'feature-list':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Feature_List() );
							break;
						case 'flip-box':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Flip_Box() );
							break;
						case 'icon-box':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Icon_Box() );
							break;
						case 'image-gallery':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Image_Gallery() );
							break;
						case 'image-hotspot':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Image_Hotspot() );
							break;
						case 'mc-styler':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_MC_Styler() );
							break;
						case 'progress-bar':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Progress_Bar() );
							break;
						case 'reviews':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Reviews() );
							break;
						case 'search-form':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Search_Form() );
							break;
						case 'slider':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Slider() );
							break;
						case 'timeline':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Timeline() );
							break;
						case 'sticky-video':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Sticky_Video() );
							break;
						case 'table-of-contents':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Table_Of_Contents() );
							break;
						case 'team-member':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Team_Member() );
							break;
						case 'testimonial-slider':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Testimonial_Slider() );
							break;
						case 'twitter-feed':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Twitter_Feed() );
							break;
						case 'video':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Video() );
							break;
						case 'one-page-navigation':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_One_Page_Navigation() );
							break;
						case 'logo-carousel':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Logo_Carousel() );
							break;
						case 'data-table':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Data_Table() );
							break;
						case 'advanced-tabs':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Advanced_Tabs() );
							break;
						case 'cf-styler':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Cf_Styler() );
							break;
						case 'wpf-styler':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_WPF_Styler() );
							break;
						case 'content-ticker':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Content_Ticker() );
							break;
						case 'pricing-table':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Pricing_Table() );
							break;
						case 'price-list':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Price_List() );
							break;
						case 'posts':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Posts() );
							break;
						case 'price-box':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Price_Box() );
							break;
						case 'post-carousel':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Post_Carousel() );
							break;
						case 'offcanvas':
							Plugin::instance()->widgets_manager->register( new Widgets\RAEL_Offcanvas() );
							break;
						case 'nav-menu':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Nav_Menu() );
							break;
						case 'login-register':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Login_Register() );
							break;
						case 'media-carousel':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Media_Carousel() );
							break;
						case 'google-map':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Google_Map() );
							break;
						case 'lottie':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Lottie() );
							break;
						case 'multi-button':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Multi_Button() );
							break;
						case 'woo-products':
							if ( class_exists( 'WooCommerce' ) ) {
								Plugin::instance()->widgets_manager->register( new Widgets\Woocommerce\Responsive_Addons_For_Elementor_Woo_Products() );
							}
							break;
						case 'breadcrumb':
							if ( class_exists( 'WooCommerce' ) ) {
								Plugin::instance()->widgets_manager->register( new Widgets\Woocommerce\Responsive_Addons_For_Elementor_Breadcrumb() );
							}
							break;
						case 'product-category-grid':
							if ( class_exists( 'WooCommerce' ) ) {
								Plugin::instance()->widgets_manager->register( new Widgets\Woocommerce\Responsive_Addons_For_Elementor_Product_Category_Grid() );
							}
							break;
						case 'product-carousel':
							if ( class_exists( 'WooCommerce' ) ) {
								Plugin::instance()->widgets_manager->register( new Widgets\Woocommerce\Responsive_Addons_For_Elementor_Product_Carousel() );
							}
							break;
						case 'woo-checkout':
							if ( class_exists( 'WooCommerce' ) ) {
								Plugin::instance()->widgets_manager->register( new Widgets\Woocommerce\Responsive_Addons_For_Elementor_Woo_Checkout() );
							}
							break;
						case 'portfolio':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Portfolio() );
							break;
						case 'menu-cart':
							if ( class_exists( 'WooCommerce' ) ) {
								Plugin::instance()->widgets_manager->register( new Widgets\Woocommerce\Responsive_Addons_For_Elementor_Menu_Cart() );
							}
							break;
						case 'wc-add-to-cart':
							if ( class_exists( 'WooCommerce' ) ) {
								Plugin::instance()->widgets_manager->register( new Widgets\Woocommerce\Responsive_Addons_For_Elementor_WC_Add_To_Cart() );
							}
							break;
						case 'modal-popup':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Modal_Popup() );
							break;
						case 'gf-styler':
							Plugin::instance()->widgets_manager->register( new Widgets\Responsive_Addons_For_Elementor_Gf_Styler() );
							break;
					}
				}
			}
		}
	}

	/**
	 * Refresh the Menu Cart button and items counter.
	 * The mini-cart itself will be rendered by WC functions.
	 *
	 * @param array $fragments Fragments.
	 *
	 * @return array
	 */
	public function menu_cart_fragments( $fragments ) {
		$has_cart = is_a( WC()->cart, 'WC_Cart' );
		if ( ! $has_cart || ! $this->use_mini_cart_template ) {
			return $fragments;
		}

		ob_start();
		self::render_menu_cart_toggle_button();
		$menu_cart_toggle_button_html = ob_get_clean();

		if ( ! empty( $menu_cart_toggle_button_html ) ) {
			$fragments['body:not(.elementor-editor-active) div.elementor-element.elementor-widget.elementor-widget-rael-wc-menu-cart div.rael-menu-cart__toggle.elementor-button-wrapper'] = $menu_cart_toggle_button_html;
		}

		return $fragments;
	}

	/**
	 * Add plugin path to wc template search path.
	 * Based on: https://www.skyverge.com/blog/override-woocommerce-template-file-within-a-plugin/
	 *
	 * @param string $template       Template.
	 * @param string $template_name  Template name.
	 * @param string $template_path  Template path.
	 *
	 * @return string
	 */
	public function woocommerce_locate_template( $template, $template_name, $template_path ) {

		if ( self::TEMPLATE_MINI_CART !== $template_name ) {
			return $template;
		}

		if ( ! $this->use_mini_cart_template ) {
			return $template;
		}

		$plugin_path = RAEL_DIR . 'includes/widgets-manager/wc-templates/';

		if ( file_exists( $plugin_path . $template_name ) ) {
			$template = $plugin_path . $template_name;
		}

		return $template;
	}

	/**
	 * Render toggle button for menu cart widget.
	 */
	public static function render_menu_cart_toggle_button() {
		if ( null === WC()->cart ) {
			return;
		}
		$product_count = WC()->cart->get_cart_contents_count();
		$sub_total     = WC()->cart->get_cart_subtotal();
		$counter_attr  = 'data-counter="' . $product_count . '"';

		?>
		<div class="rael-menu-cart__toggle elementor-button-wrapper">
			<a id="rael-menu-cart__toggle_button" href="#" class="elementor-button elementor-size-sm">
				<span class="elementor-button-text"><?php echo wp_kses_post( $sub_total ); ?></span>
				<span class="elementor-button-icon" <?php echo wp_kses_post( $counter_attr ); ?>>
					<i class="eicon" aria-hidden="true"></i>
					<span class="elementor-screen-only"><?php esc_html_e( 'Cart', 'responsive-addons-for-elementor' ); ?></span>
				</span>
			</a>
		</div>

		<?php
	}

	/**
	 * Render menu cart markup.
	 * The `widget_shopping_cart_content` div will be populated by woocommerce js.
	 */
	public static function render_menu_cart() {
		if ( null === WC()->cart ) {
			return;
		}

		$widget_cart_is_hidden = apply_filters( 'woocommerce_widget_cart_is_hidden', false );
		?>
		<div class="rael-menu-cart__wrapper">
			<?php if ( ! $widget_cart_is_hidden ) : ?>
				<div class="rael-menu-cart__container elementor-lightbox" aria-expanded="false">
					<div class="rael-menu-cart__main" aria-expanded="false">
						<div class="rael-menu-cart__close-button"></div>
						<div class="widget_shopping_cart_content"><?php	wc_get_template( 'cart/mini-cart.php' ); ?></div>
					</div>
				</div>
				<?php self::render_menu_cart_toggle_button(); ?>
			<?php endif; ?>
		</div> <!-- close rael-menu-cart__wrapper -->
		<?php
	}

	/**
	 * Maybe Init Cart.
	 */
	public function maybe_init_cart() {
		$has_cart = is_a( WC()->cart, 'WC_Cart' );

		if ( ! $has_cart ) {
			$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
			WC()->session  = new $session_class();
			WC()->session->init();
			WC()->cart     = new \WC_Cart();
			WC()->customer = new \WC_Customer( get_current_user_id(), true );
		}
	}

	/**
	 * Render the FAQ schema.
	 *
	 * @since 1.2.0
	 *
	 * @access public
	 */
	public function render_faq_schema() {
		$faqs_data = $this->get_faqs_data();
		if ( $faqs_data ) {
			$schema_data = array(
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => $faqs_data,
			);

			$encoded_data = wp_json_encode( $schema_data );
			?>
			<script type="application/ld+json">
				<?php print_r( $encoded_data ); ?>
			</script>
			<?php
		}
	}

	/**
	 * Get FAQ data.
	 *
	 * @since 1.2.0
	 *
	 * @access public
	 */
	public function get_faqs_data() {
		$elementor = \Elementor\Plugin::$instance;
		$document  = $elementor->documents->get( get_the_ID() );

		if ( ! $document ) {
			return;
		}

		$data        = $document->get_elements_data();
		$widget_ids  = $this->get_widget_ids();
		$object_data = array();

		foreach ( $widget_ids as $widget_id ) {
			$widget_data            = $this->find_element_recursive( $data, $widget_id );
			$widget                 = $elementor->elements_manager->create_element_instance( $widget_data );
			$settings               = $widget->get_settings();
			$content_schema_warning = 0;
			$enable_schema          = $settings['rael_schema_support'];

			foreach ( $settings['rael_tabs'] as $key ) {
				if ( 'content' !== $key['rael_faq_content_type'] ) {
					$content_schema_warning = 1;
				}
			}

			if ( 'yes' === $enable_schema && ( 0 === $content_schema_warning ) ) {
				foreach ( $settings['rael_tabs'] as $faqs ) {
					$new_data = array(
						'@type'          => 'Question',
						'name'           => $faqs['rael_question'],
						'acceptedAnswer' =>
							array(
								'@type' => 'Answer',
								'text'  => $faqs['rael_answer'],
							),
					);
					array_push( $object_data, $new_data );
				}
			}
		}

		return $object_data;
	}

	/**
	 * Get the widget ID.
	 *
	 * @since 1.2.0
	 *
	 * @access public
	 */
	public function get_widget_ids() {
		$elementor = \Elementor\Plugin::$instance;
		$document  = $elementor->documents->get( get_the_ID() );

		if ( ! $document ) {
			return;
		}

		$data       = $document->get_elements_data();
		$widget_ids = array();

		$elementor->db->iterate_data(
			$data,
			function ( $element ) use ( &$widget_ids ) {
				if ( isset( $element['widgetType'] ) && 'rael-faq' === $element['widgetType'] ) {
					array_push( $widget_ids, $element['id'] );
				}
			}
		);
		return $widget_ids;
	}

	/**
	 * Get Widget Setting data.
	 *
	 * @since 1.2.0
	 * @access public
	 * @param array  $elements Element array.
	 * @param string $form_id Element ID.
	 * @return Boolean True/False.
	 */
	public function find_element_recursive( $elements, $form_id ) {

		foreach ( $elements as $element ) {
			if ( $form_id === $element['id'] ) {
				return $element;
			}

			if ( ! empty( $element['elements'] ) ) {
				$element = $this->find_element_recursive( $element['elements'], $form_id );

				if ( $element ) {
					return $element;
				}
			}
		}

		return false;
	}

}

Responsive_Addons_For_Elementor_Widgets_Manager::instance();
