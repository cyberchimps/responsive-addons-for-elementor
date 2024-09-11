<?php
/**
 * Adds menu and assets.
 *
 * @package    responsive-addons-for-elementor
 * @subpackage responsive-addons-for-elementor/includes
 * @author     CyberChimps <support@cyberchimps.com>
 */

use Elementor\Utils;
use Elementor\Icons_Manager;
use Responsive_Addons_For_Elementor\Helper\Helper;
use \Responsive_Addons_For_Elementor\Traits\Woo_Checkout_Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Responsive_Addons_For_Elementor
 *
 * @package Responsive_Addons_For_Elementor
 */
class Responsive_Addons_For_Elementor {

	const MINIMUM_ELEMENTOR_VERSION = '3.10.0';

	/**
	 * Represents the singleton instance.
	 *
	 * @var null|self
	 */
	private static $instance = null;

	/**
	 * Retrieves the singleton instance of the class.
	 *
	 * @return self The singleton instance.
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'i18n' ) );
		add_action( 'init', array( $this, 'responsive_addons_for_elementor_widgets_display' ) );
		add_action( 'plugins_loaded', array( $this, 'init' ) );

		add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_styles' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'widget_scripts' ) );

		add_action( 'admin_enqueue_scripts', array( &$this, 'responsive_addons_for_elementor_admin_enqueue_styles' ) );

		// Responsive Addons for Elementor Menu.
		add_action( 'admin_menu', array( $this, 'responsive_addons_for_elementor_admin_menu' ), 9 );
		add_action( 'responsive_register_admin_menu', array( $this, 'rael_register_admin_menu' ) );

		// Remove all admin notices from specific pages.
		add_action( 'admin_init', array( $this, 'responsive_addons_for_elementor_admin_init' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ), 15 );

		// Redirect to Getting Started Page on Plugin Activation.
		add_action( 'admin_init', array( $this, 'responsive_addons_for_elementor_maybe_redirect_to_getting_started' ) );

		// RAEL Getting Started Widgets Toggle.
		add_action( 'wp_ajax_rael_widgets_toggle', array( $this, 'rael_widgets_toggle' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'rael_enqueue_dashicons' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'responsive_addons_for_elementor_responsive_menu' ) );

		// MailChimp Subscribe.
		add_action( 'wp_ajax_rael_mailchimp_subscribe', array( $this, 'mailchimp_subscribe_with_ajax' ) );
		add_action( 'wp_ajax_nopriv_rael_mailchimp_subscribe', array( $this, 'mailchimp_subscribe_with_ajax' ) );

		// Facebook Feed.
		add_action( 'render_facebook_feed', array( $this, 'rael_render_facebook_feed' ), 10 );
		add_action( 'wp_ajax_facebook_feed_load_more', array( $this, 'rael_render_facebook_feed' ) );
		add_action( 'wp_ajax_nopriv_facebook_feed_load_more', array( $this, 'rael_render_facebook_feed' ) );

		// Quick View.
		add_action( 'wp_ajax_nopriv_rael_product_quickview_popup', array( Helper::class, 'rael_product_quickview_popup' ) );
		add_action( 'wp_ajax_rael_product_quickview_popup', array( Helper::class, 'rael_product_quickview_popup' ) );

		// RAEL Products.
		add_action( 'wp_ajax_rael_load_more', array( Helper::class, 'ajax_load_more' ) );
		add_action( 'wp_ajax_nopriv_rael_load_more', array( Helper::class, 'ajax_load_more' ) );

		// RAEL Products Compare.
		add_action( 'wp_ajax_nopriv_rael_products_compare', array( Helper::class, 'ajax_get_compare_table' ) );
		add_action( 'wp_ajax_rael_products_compare', array( Helper::class, 'ajax_get_compare_table' ) );

		add_action( 'wp_ajax_rael_products_pagination_product', array( Helper::class, 'ajax_rael_products_pagination_product' ) );
		add_action( 'wp_ajax_nopriv_rael_products_pagination_product', array( Helper::class, 'ajax_rael_products_pagination_product' ) );

		add_action( 'wp_ajax_rael_woo_product_pagination', array( Helper::class, 'ajax_rael_woo_product_pagination' ) );
		add_action( 'wp_ajax_nopriv_rael_woo_product_pagination', array( Helper::class, 'ajax_rael_woo_product_pagination' ) );

		// RAEL Ajax Select2.
		add_action( 'wp_ajax_rael_ajax_select2_search_post', array( Helper::class, 'rael_ajax_select2_posts_filter_autocomplete' ) );
		add_action( 'wp_ajax_nopriv_rael_ajax_select2_search_post', array( Helper::class, 'rael_ajax_select2_posts_filter_autocomplete' ) );

		add_action( 'wp_ajax_rael_ajax_select2_get_title', array( Helper::class, 'rael_ajax_select2_get_posts_value_titles' ) );
		add_action( 'wp_ajax_nopriv_rael_ajax_select2_get_title', array( Helper::class, 'rael_ajax_select2_get_posts_value_titles' ) );

		// RAEL Woo Checkout.
		add_action( 'wp_ajax_woo_checkout_update_order_review', array( $this, 'woo_checkout_update_order_review' ) );
		add_action( 'wp_ajax_nopriv_woo_checkout_update_order_review', array( $this, 'woo_checkout_update_order_review' ) );

		$this->load_dependencies();
		$this->define_admin_hooks();
	}

	/**
	 * Enqueue Dashicons.
	 */
	public function rael_enqueue_dashicons() {
		// Enqueue the dashicons stylesheet.
		wp_enqueue_style( 'dashicons' );
	}

	/**
	 * RAEL Widgets Display.
	 *
	 * @since 1.0.0
	 */
	public function responsive_addons_for_elementor_widgets_display() {
		include_once RAEL_DIR . 'includes/class-responsive-addons-for-elementor-widgets-updater.php';

		$rael_widgets_data = new Responsive_Addons_For_Elementor_Widgets_Updater();

		$rael_path = 'responsive-addons-for-elementor/responsive-addons-for-elementor.php';

		if ( ! function_exists( 'get_plugins' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$installed_plugins = get_plugins();

		if ( isset( $installed_plugins[ $rael_path ] ) ) {
			$installed_rael_version = $installed_plugins[ $rael_path ]['Version'];

			$widgets = get_option( 'rael_widgets' );
			if ( ! $widgets ) {
				$rael_widgets_data->insert_widgets_data();
			} elseif ( version_compare( RAEL_VER, $installed_rael_version, '>' ) ) {
				$this->update_frontend_assets( $widgets, true );
			}
		}
	}

	/**
	 * Retrieve internationalization words or phrases.
	 *
	 * @return array An array containing translated words.
	 */
	public function get_i18n_words() {
		$words = array(
			'loading' => esc_html__( 'Loading', 'responsive-addons-for-elementor' ),
			'added'   => esc_html__( 'Added', 'responsive-addons-for-elementor' ),
		);

		return $words;
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		new Responsive_Addons_For_Elementor_Admin_Settings();
	}

	/**
	 * Responsible for defining all actions that occur in the admin area.
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once RAEL_DIR . 'admin/class-responsive-addons-for-elementor-admin-settings.php';
		include_once RAEL_DIR . 'traits/responsive-addons-for-elementor-template-query.php';
		include_once RAEL_DIR . 'helper/helper.php';
		include_once RAEL_DIR . 'admin/class-responsive-addons-for-elementor-rst-install-helper.php';
		include_once RAEL_DIR . 'traits/responsive-addons-for-elementor-singleton.php';
		include_once RAEL_DIR . 'traits/responsive-addons-for-elementor-missing-dependency.php';
		require_once RAEL_DIR . 'traits/responsive-addons-for-elementor-products-compare.php';
		require_once RAEL_DIR . 'traits/responsive-addons-for-elementor-helperwoocheckout.php';
		require_once RAEL_DIR . 'traits/responsive-addons-for-elementor-woo-checkout-helper.php';
	}

	/**
	 * Enqueues Widgets scripts
	 */
	public function widget_scripts() {
		wp_enqueue_script( 'rael-elementor-widgets', RAEL_ASSETS_URL . 'js/widgets/rael-widgets.js', 'jquery', RAEL_VER, true );
		wp_enqueue_script( 'wp-mediaelement' );
	}

	/**
	 * Loads Plugins Text Domain
	 */
	public function i18n() {

		load_plugin_textdomain( 'responsive-addons-for-elementor' );
	}

	/**
	 * To check Minimum Elementor Version and loads widgets file.
	 */
	public function init() {

		// Check if Elementor installed and activated.
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			if ( get_option( 'elementor_experiment-e_swiper_latest' ) ) {
				update_option( 'elementor_experiment-e_swiper_latest', 'inactive' );
			}
			return;
		} else {
			add_option( 'elementor_experiment-e_swiper_latest', 'inactive' );
		}

		// Check for required minimum Elementor version.
		if ( defined( 'ELEMENTOR_VERSION' ) && ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		$this->include_widget_manager();
		$this->include_modules_manager();
		$this->include_dynamic_tags_manager();
	}

	/**
	 * Admin Notices
	 */
	public function admin_notice_missing_main_plugin() {

		$file_path         = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		if ( isset( $installed_plugins[ $file_path ] ) ) {

			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			$plugin                   = 'elementor/elementor.php';
			$elementor_activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

			$message = sprintf(
			/* translators: Name of plugin and Elementor */
				esc_html__( '"%1$s" requires "%2$s" to be activated.', 'responsive-addons-for-elementor' ),
				'<strong>' . esc_html__( 'Responsive Addons for Elementor', 'responsive-addons-for-elementor' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'responsive-addons-for-elementor' ) . '</strong>'
			);

			$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $elementor_activation_url, __( 'Activate Elementor Now', 'responsive-addons-for-elementor' ) ) . '</p>';

			printf( '<div class="notice notice-error"><p>%1$s</p></div>', wp_kses_post( $message ) );
		} else {
			if ( ! current_user_can( 'install_plugins' ) ) {
				return;
			}

			$elementor_install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
			$message               = sprintf(
			/* translators: Name of plugin and Elementor */
				esc_html__( '"%1$s" requires "%2$s" to be installed.', 'responsive-addons-for-elementor' ),
				'<strong>' . esc_html__( 'Responsive Addons for Elementor', 'responsive-addons-for-elementor' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'responsive-addons-for-elementor' ) . '</strong>'
			);

			$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $elementor_install_url, __( 'Install Elementor Now', 'responsive-addons-for-elementor' ) ) . '</p>';

			printf( '<div class="notice notice-error"><p>%1$s</p></div>', wp_kses_post( $message ) );
		}
	}

	/**
	 * Admin notice to check for the minimum Elementor version
	 */
	public function admin_notice_minimum_elementor_version() {

		$message = sprintf(
		/* translators: Name of plugin, Elementor and minimum elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'responsive-addons-for-elementor' ),
			'<strong>' . esc_html__( 'Responsive Addons for Elementor', 'responsive-addons-for-elementor' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'responsive-addons-for-elementor' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', esc_html( $message ) );
	}

	/**
	 * Include widget manager
	 */
	public function include_widget_manager() {
		// Load the widgets.
		include RAEL_DIR . 'includes/widgets-manager/class-responsive-addons-for-elementor-widgets-manager.php';
	}

	/**
	 * Include modules manager
	 */
	public function include_modules_manager() {
		// Load the modules.
		require RAEL_DIR . 'includes/modules-manager/class-modules-manager.php';
	}

	/**
	 * Include dynamic tags manager
	 */
	public function include_dynamic_tags_manager() {
		// Load the modules.
		require RAEL_DIR . 'includes/dynamic-tags-manager/class-dynamic-tags-manager.php';
	}

	/**
	 * Enqueue frontend scripts
	 */
	public function enqueue_frontend_scripts() {
		wp_enqueue_script(
			'rael-frontend',
			RAEL_URL . 'assets/js/frontend/rael-frontend.js',
			array(
				'elementor-frontend',
			),
			RAEL_VER,
			true
		);

		if ( class_exists( 'WooCommerce' ) ) {
			$has_cart = is_a( WC()->cart, 'WC_Cart' );
			if ( $has_cart ) {
				$locale_settings = array(
					'menu_cart' => array(
						'cart_page_url'     => wc_get_cart_url(),
						'checkout_page_url' => wc_get_checkout_url(),
					),
				);
			}
		}

		$locale_settings['ajaxurl'] = admin_url( 'admin-ajax.php' );
		$locale_settings['nonce']   = wp_create_nonce( 'facebook_feed_ajax_nonce' );
		$locale_settings['i18n']    = $this->get_i18n_words();

		wp_localize_script(
			'rael-frontend',
			'localize',
			$locale_settings
		);

		Utils::print_js_config(
			'rael-frontend',
			'RAELFrontendConfig',
			$locale_settings
		);
	}

	/**
	 * Enqueue Styles
	 */
	public function enqueue_styles() {

		$frontend_file_url = RAEL_ASSETS_URL . 'css/rael-frontend.css';

		if ( ! Icons_Manager::is_migration_allowed() ) {
			wp_enqueue_style( 'font-awesome' );
		} else {
			Icons_Manager::enqueue_shim();
		}

		wp_enqueue_style(
			'rael-frontend',
			$frontend_file_url,
			array(),
			RAEL_VER
		);

		wp_enqueue_style( 'wp-mediaelement' );

		if ( class_exists( 'GFForms' ) ) {
			$gf_forms = \RGFormsModel::get_forms( null, 'title' );

			foreach ( $gf_forms as $form ) {

				if ( '0' !== $form->id ) {
					wp_enqueue_script( 'gform_gravityforms' );
					gravity_form_enqueue_scripts( $form->id );
				}
			}
		}
	}

	/**
	 * Load assets
	 */
	public function load_assets() {

		$rael_widgets  = get_option( 'rael_widgets' );
		$included_libs = array();

		// Localize the Swiper Library on the basis of version.
		$swiper_class = array( 'swiper' => 'swiper-container-' );
		if ( defined( 'RAEL_ELEMENTOR_SWIPER' ) && true === RAEL_ELEMENTOR_SWIPER ) {
			$swiper_class = array( 'swiper' => 'swiper-' );
		}

		foreach ( $rael_widgets as $rael_widget ) {
			if ( $rael_widget['status'] ) {
				switch ( $rael_widget['title'] ) {
					case 'product-carousel':
					case 'woo-products':
						if ( ! isset( $included_libs['rael-photoswipe'] ) ) {
							$included_libs['rael-photoswipe'] = true;
							wp_enqueue_script( 'rael-photoswipe', RAEL_ASSETS_URL . 'lib/photoswipe/photoswipe.min.js', array( 'jquery', 'masonry', 'imagesloaded' ), RAEL_VER, true );
							wp_enqueue_script( 'rael-photoswipe-ui', RAEL_ASSETS_URL . 'lib/photoswipe/photoswipe-ui-default.min.js', array(), RAEL_VER, true );
							wp_enqueue_script( 'rael-scripts', RAEL_ASSETS_URL . 'js/rael-photoswipe.min.js', array( 'rael-photoswipe', 'rael-photoswipe-ui' ), RAEL_VER, true );
							wp_register_style( 'rael-photoswipe-style', RAEL_ASSETS_URL . 'lib/photoswipe/photoswipe.min.css', null, RAEL_VER );
							wp_enqueue_style( 'rael-photoswipe-style' );
							wp_register_style( 'rael-photoswipe-default-skin', RAEL_ASSETS_URL . 'lib/photoswipe/default-skin.min.css', null, RAEL_VER );
							wp_enqueue_style( 'rael-photoswipe-default-skin' );
						}
						if ( ! isset( $included_libs['rael-swiper'] ) ) {
							$included_libs['rael-swiper'] = true;
							wp_enqueue_script( 'rael-swiper', RAEL_ASSETS_URL . 'lib/swiper/swiper.min.js', array(), RAEL_VER, true );
							wp_localize_script( 'rael-swiper', 'rael_elementor_swiper', $swiper_class );
						}
						break;
					case 'image-gallery':
						if ( ! isset( $included_libs['rael-fancybox'] ) ) {
							$included_libs['rael-fancybox'] = true;
							wp_enqueue_script( 'rael-fancybox', RAEL_ASSETS_URL . 'lib/fancybox/jquery_fancybox.min.js', array( 'jquery' ), RAEL_VER, true );
							wp_register_style( 'rael-fancybox-style', RAEL_ASSETS_URL . 'lib/fancybox/jquery-fancybox.min.css', null, RAEL_VER );
							wp_enqueue_style( 'rael-fancybox-style' );
						}
						wp_enqueue_script( 'rael-justified', RAEL_ASSETS_URL . 'lib/justifiedgallery/justifiedgallery.min.js', array( 'jquery' ), RAEL_VER, true );
						wp_enqueue_script( 'rael-element-resize', RAEL_ASSETS_URL . 'lib/jquery-element-resize/jquery_resize.min.js', array( 'jquery' ), RAEL_VER, true );
						if ( ! isset( $included_libs['rael-isotope'] ) ) {
							$included_libs['rael-isotope'] = true;
							wp_enqueue_script( 'rael-isotope', RAEL_ASSETS_URL . 'lib/isotope/isotope.min.js', array( 'jquery' ), RAEL_VER, true );
						}
						if ( ! isset( $included_libs['rael-swiper'] ) ) {
							$included_libs['rael-swiper'] = true;
							wp_enqueue_script( 'rael-swiper', RAEL_ASSETS_URL . 'lib/swiper/swiper.min.js', array(), RAEL_VER, true );
							wp_localize_script( 'rael-swiper', 'rael_elementor_swiper', $swiper_class );
						}
						break;
					case 'twitter-feed':
						if ( ! isset( $included_libs['rael-isotope'] ) ) {
							$included_libs['rael-isotope'] = true;
							wp_enqueue_script( 'rael-isotope', RAEL_ASSETS_URL . 'lib/isotope/isotope.min.js', array( 'jquery' ), RAEL_VER, true );
						}
						break;
					case 'nav-menu':
						wp_enqueue_script( 'rael-smartmenus', RAEL_ASSETS_URL . 'lib/smartmenus/jquery.smartmenus.min.js', array(), RAEL_VER, true );
						break;
					case 'video':
						wp_enqueue_script( 'rael-magnific-popup', RAEL_ASSETS_URL . 'lib/magnific-popup/jquery.magnific-popup.min.js', array(), RAEL_VER, true );
						wp_register_style( 'rael-magnific-popup-style', RAEL_ASSETS_URL . 'lib/magnific-popup/magnific-popup.min.css', null, RAEL_VER );
						wp_enqueue_style( 'rael-magnific-popup-style' );
						break;
					case 'lottie':
						wp_enqueue_script( 'rael-lottie-lib', RAEL_ASSETS_URL . 'lib/lottie/lottie.min.js', array(), RAEL_VER, true );
						break;
					case 'sticky-video':
						wp_enqueue_script( 'rael-plyr', RAEL_ASSETS_URL . 'lib/plyr/plyr.min.js', array(), RAEL_VER, true );
						wp_register_style( 'rael-plyr-style', RAEL_ASSETS_URL . 'lib/plyr/plyr.min.css', null, RAEL_VER );
						wp_enqueue_style( 'rael-plyr-style' );
						break;
					case 'content-ticker':
					case 'logo-carousel':
					case 'media-carousel':
					case 'post-carousel':
					case 'reviews':
					case 'slider':
					case 'testimonial-slider':
						if ( ! isset( $included_libs['rael-swiper'] ) ) {
							$included_libs['rael-swiper'] = true;
							wp_enqueue_script( 'rael-swiper', RAEL_ASSETS_URL . 'lib/swiper/swiper.js', array(), RAEL_VER, true );
							wp_localize_script( 'rael-swiper', 'rael_elementor_swiper', $swiper_class );
						}
						break;
					case 'banner':
						wp_enqueue_script( 'rael-tilt', RAEL_ASSETS_URL . 'lib/universal-tilt/universal-tilt.min.js', array(), RAEL_VER, true );
						break;
					case 'fancy-text':
						wp_enqueue_script( 'rael-morphext', RAEL_ASSETS_URL . 'lib/morphext/morphext.min.js', array(), RAEL_VER, true );
						wp_enqueue_script( 'rael-typed', RAEL_ASSETS_URL . 'lib/typed/typed.min.js', array(), RAEL_VER, true );
						break;
					case 'data-table':
						wp_enqueue_script( 'rael-table-sorter', RAEL_ASSETS_URL . 'lib/table-sorter/jquery.tablesorter.min.js', array(), RAEL_VER, true );
						break;
					case 'progress-bar':
						wp_enqueue_script( 'rael-inview', RAEL_ASSETS_URL . 'lib/inview/inview.min.js', array(), RAEL_VER, true );
						break;
				}
			}
		}
		wp_register_style( 'rael-animate-style', RAEL_ASSETS_URL . 'lib/animate/animate.min.css', null, RAEL_VER );
		wp_enqueue_style( 'rael-animate-style' );
	}

	/**
	 * Enqueue admin styles
	 */
	public function enqueue_admin_styles() {
		wp_register_style(
			'rael-style',
			RAEL_ASSETS_URL . 'css/rael-admin.css',
			array(),
			RAEL_VER
		);

		wp_enqueue_style( 'rael-style' );

		wp_register_style(
			'rael-icons',
			RAEL_URL . 'admin/css/rael-icon.css',
			array(),
			RAEL_VER
		);

		wp_enqueue_style( 'rael-icons' );

		wp_register_script(
			'rael-elementor-control-js',
			RAEL_ASSETS_URL . 'js/controls/rael-control.js',
			array( 'jquery-elementor-select2' ),
			RAEL_VER,
			false
		);

		wp_register_script(
			'rael-elementor-visualselect',
			RAEL_ASSETS_URL . 'js/controls/rael-visual-select.js',
			array( 'jquery' ),
			RAEL_VER,
			false
		);
	}

	/**
	 * Include Admin css
	 *
	 * @param string $hook Hook.
	 *
	 * @return void [description]
	 */
	public function responsive_addons_for_elementor_admin_enqueue_styles( $hook = '' ) {

		if ( 'toplevel_page_rael_getting_started' !== $hook && 'responsive_page_rael_getting_started' !== $hook ) {
			return;
		}
		// Registering Bootstrap scripts.
		wp_enqueue_script( 'rael-frontend', RAEL_URL . 'admin/assets/lib/bootstrap/js/bootstrap.bundle.min.js', array( 'jquery' ), RAEL_VER, true );
		wp_enqueue_script( 'rael-frontend-toastify', RAEL_URL . 'admin/assets/lib/toastify/js/toastify-js.js', array( 'jquery' ), RAEL_VER, true );

		// Responsive Ready Sites admin styles.
		wp_register_style( 'responsive-addons-for-elementor-admin', RAEL_URL . 'admin/css/rael-admin.css', false, RAEL_VER );
		wp_enqueue_style( 'responsive-addons-for-elementor-admin' );
		wp_enqueue_script(
			'responsive-addons-for-elementor-admin-jsfile',
			RAEL_URL . 'admin/js/rael-admin.js',
			array( 'jquery' ),
			RAEL_VER,
			true
		);

		wp_localize_script(
			'responsive-addons-for-elementor-admin-jsfile',
			'localize',
			array(
				'ajaxurl'        => admin_url( 'admin-ajax.php' ),
				'raelurl'        => RAEL_URL,
				'siteurl'        => site_url(),
				'isRSTActivated' => is_plugin_active( 'responsive-add-ons/responsive-add-ons.php' ),
				'nonce'          => wp_create_nonce( 'responsive-addons-for-elementor' ),
			)
		);

		wp_enqueue_script( 'rael-rst-admin', RAEL_URL . '/admin/js/rael-rst-plugin-install.js', array( 'jquery' ), true, RAEL_VER );
		wp_enqueue_script( 'updates' );
		wp_localize_script(
			'rael-rst-admin',
			'rstPluginInstall',
			array(
				'installing'            => esc_html__( 'Installing ', 'responsive-addons-for-elementor' ),
				'activating'            => esc_html__( 'Activating ', 'responsive-addons-for-elementor' ),
				'verify_network'        => esc_html__( 'Not connect. Verify Network.', 'responsive-addons-for-elementor' ),
				'page_not_found'        => esc_html__( 'Requested page not found. [404]', 'responsive-addons-for-elementor' ),
				'internal_server_error' => esc_html__( 'Internal Server Error [500]', 'responsive-addons-for-elementor' ),
				'json_parse_failed'     => esc_html__( 'Requested JSON parse failed', 'responsive-addons-for-elementor' ),
				'timeout_error'         => esc_html__( 'Time out error', 'responsive-addons-for-elementor' ),
				'ajax_req_aborted'      => esc_html__( 'Ajax request aborted', 'responsive-addons-for-elementor' ),
				'uncaught_error'        => esc_html__( 'Uncaught Error', 'responsive-addons-for-elementor' ),
			)
		);

		remove_filter( 'update_footer', 'core_update_footer' );
	}

	/**
	 * Register the menu for the plugin.
	 *
	 * @return void [description]
	 */
	public function responsive_addons_for_elementor_admin_menu() {

		$theme = wp_get_theme();

		if ( ( 'Responsive' !== $theme->name && 'Responsive' !== $theme->parent_theme ) && is_plugin_inactive( 'responsive-block-editor-addons/responsive-block-editor-addons.php' ) ) {
			add_menu_page(
				'Responsive',
				'Responsive',
				'manage_options',
				'rael_getting_started',
				array( $this, 'responsive_addons_for_elementor_getting_started' ),
				esc_url( RAEL_URL ) . 'admin/images/responsive-add-ons-menu-icon.png',
				59
			);
			$parent_slug = 'rael_getting_started';
			do_action( 'responsive_register_admin_menu', $parent_slug );
		}

		if ( ( 'Responsive' === $theme->name || 'Responsive' === $theme->parent_theme ) && version_compare( RESPONSIVE_THEME_VERSION, '4.9.7.1', '<=' ) ) {
			add_menu_page(
				__( 'REA', 'responsive-elementor-addons' ),
				__( 'REA', 'responsive-elementor-addons' ),
				'manage_options',
				'rea_getting_started',
				array( $this, 'responsive_addons_for_elementor_getting_started' ),
				'dashicons-chart-area',
				26
			);

			add_submenu_page(
				'rea_getting_started',
				__( 'Getting Started', 'responsive-elementor-addons' ),
				__( 'Getting Started', 'responsive-elementor-addons' ),
				'manage_options',
				'rea_getting_started',
				array( $this, 'responsive_addons_for_elementor_getting_started' ),
				10
			);

			add_submenu_page(
				'rea_getting_started',
				__( 'Theme Builder', 'responsive-elementor-addons' ),
				__( 'Theme Builder', 'responsive-elementor-addons' ),
				'edit_pages',
				'edit.php?post_type=rea-theme-template'
			);

			add_submenu_page(
				'rea_getting_started',
				__( 'REA Settings', 'responsive-elementor-addons' ),
				__( 'Settings', 'responsive-elementor-addons' ),
				'manage_options',
				'rea_getting_started#settings',
				array( $this, 'display_rea_admin_settings' ),
				20
			);
		}
	}

	/**
	 * Display Getting Started Page.
	 *
	 * Output the content for the getting started page.
	 *
	 * @access public
	 */
	public function responsive_addons_for_elementor_getting_started() {
		include_once RAEL_DIR . 'admin/partials/responsive-addons-for-elementor-admin-getting-started.php';
	}

	/**
	 * On admin init.
	 *
	 * Preform actions on WordPress admin initialization.
	 *
	 * Fired by `admin_init` action.
	 *
	 * @access public
	 */
	public function responsive_addons_for_elementor_admin_init() {

		$this->responsive_addons_for_elementor_remove_all_admin_notices();
	}

	/**
	 * [responsive_addons_for_elementor_remove_all_admin_notices description]
	 */
	private function responsive_addons_for_elementor_remove_all_admin_notices() {
		$responsive_addons_for_elementor_pages = array(
			'responsive_addons_for_elementor',
			'rael_getting_started',
		);

		if ( empty( $_GET['page'] ) || ! in_array( $_GET['page'], $responsive_addons_for_elementor_pages, true ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		remove_all_actions( 'admin_notices' );
	}

	/**
	 * Responsive_addons_for_elementor_maybe_redirect_to_getting_started description
	 *
	 * @return [type] [description]
	 */
	public function responsive_addons_for_elementor_maybe_redirect_to_getting_started() {
		if ( ! get_transient( 'responsive_addons_for_elementor_activation_redirect' ) ) {
			return;
		}

		if ( wp_doing_ajax() ) {
			return;
		}

		delete_transient( 'responsive_addons_for_elementor_activation_redirect' );

		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		wp_safe_redirect( admin_url( 'admin.php?page=rael_getting_started' ) );

		exit;
	}

	/**
	 * Ajax Request to Toggle RAEL Widgets.
	 *
	 * @since 1.0.0
	 */
	public function rael_widgets_toggle() {

		check_ajax_referer( 'responsive-addons-for-elementor', '_nonce' );

		$rael_widgets = get_option( 'rael_widgets' );

		if ( isset( $_POST['toggle_value'] ) ) {
			$status = filter_var( wp_unslash( $_POST['toggle_value'] ), FILTER_VALIDATE_BOOLEAN ) ? 1 : 0;
			foreach ( $rael_widgets as &$widget ) {
				$widget['status'] = $status;
			}
		} else {

			if ( ! isset( $_POST['index'] ) || ! isset( $_POST['value'] ) ) {
				wp_send_json_error();
			}

			$index = sanitize_key( $_POST['index'] );
			$value = sanitize_key( $_POST['value'] );

			$rael_widgets[ $index ]['status'] = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
		}

		update_option( 'rael_widgets', $rael_widgets );

		$this->update_frontend_assets( $rael_widgets );
	}

	/**
	 * Updates the frontend asset files when widgets are toggled.
	 *
	 * @param array $rael_widgets RAEL Widgets fetched from Database.
	 * @param bool  $extend Checks toogle on/off status.
	 * @since 1.0.0
	 */
	public function update_frontend_assets( $rael_widgets, $extend = false ) {

		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		$js_files    = array();
		$css_files   = array();
		$included_js = array();

		$js_files_path  = RAEL_DIR . 'assets/js/frontend/';
		$css_files_path = RAEL_DIR . 'assets/css/frontend/';

		$target_js_file_path  = RAEL_DIR . 'assets/js/frontend/rael-frontend.js';
		$target_css_file_path = RAEL_DIR . 'assets/css/rael-frontend.css';

		$ext         = '.min.js';
		$css_min_ext = '.min.css';

		foreach ( $rael_widgets as $rael_widget ) {
			if ( $rael_widget['status'] ) {
				switch ( $rael_widget['title'] ) {
					case 'audio':
						array_push( $css_files, $css_files_path . 'audio/audio' . $css_min_ext );
						break;
					case 'back-to-top':
						array_push( $js_files, $js_files_path . 'back-to-top/back-to-top' . $ext );
						array_push( $css_files, $css_files_path . 'back-to-top/back-to-top' . $css_min_ext );
						break;
					case 'banner':
						array_push( $js_files, $js_files_path . 'banner/banner' . $ext );
						array_push( $css_files, $css_files_path . 'banner/banner' . $css_min_ext );
						break;
					case 'business-hour':
						array_push( $css_files, $css_files_path . 'business-hour/business-hour' . $css_min_ext );
						break;
					case 'button':
						array_push( $css_files, $css_files_path . 'button/button' . $css_min_ext );
						break;
					case 'call-to-action':
						array_push( $css_files, $css_files_path . 'call-to-action/cta-frontend' . $css_min_ext );
						break;
					case 'content-switcher':
						array_push( $js_files, $js_files_path . 'content-switcher/content-switcher' . $ext );
						array_push( $css_files, $css_files_path . 'content-switcher/content-switcher' . $css_min_ext );
						break;
					case 'countdown':
						array_push( $js_files, $js_files_path . 'countdown/countdown' . $ext );
						array_push( $css_files, $css_files_path . 'countdown/countdown-frontend' . $css_min_ext );
						break;
					case 'divider':
						array_push( $css_files, $css_files_path . 'divider/divider' . $css_min_ext );
						break;
					case 'dual-color-header':
						array_push( $css_files, $css_files_path . 'dual-color-header/dual-color-header' . $css_min_ext );
						break;
					case 'fancy-text':
						array_push( $js_files, $js_files_path . 'fancy-text/fancy-text' . $ext );
						array_push( $css_files, $css_files_path . 'fancy-text/fancy-text' . $css_min_ext );
						break;
					case 'faq':
						array_push( $js_files, $js_files_path . 'faq/faq' . $ext );
						array_push( $css_files, $css_files_path . 'faq/faq' . $css_min_ext );
						break;
					case 'feature-list':
						array_push( $css_files, $css_files_path . 'feature-list/feature-list' . $css_min_ext );
						break;
					case 'flip-box':
						array_push( $css_files, $css_files_path . 'flipbox/rael-flipbox' . $css_min_ext );
						break;
					case 'icon-box':
						array_push( $css_files, $css_files_path . 'infobox/infobox' . $css_min_ext );
						break;
					case 'image-gallery':
						array_push( $js_files, $js_files_path . 'image-gallery/image-gallery' . $ext );
						array_push( $css_files, $css_files_path . 'image-gallery/image-gallery' . $css_min_ext );
						break;
					case 'image-hotspot':
						array_push( $css_files, $css_files_path . 'image-hotspot/image-hotspot' . $css_min_ext );
						break;
					case 'mc-styler':
						array_push( $js_files, $js_files_path . 'mailchimp/mailchimp' . $ext );
						array_push( $css_files, $css_files_path . 'mcstyler/mcstyler' . $css_min_ext );
						break;
					case 'multi-button':
						array_push( $css_files, $css_files_path . 'multi-button/multi-button' . $css_min_ext );
						break;
					case 'progress-bar':
						array_push( $js_files, $js_files_path . 'progress-bar/progress-bar' . $ext );
						array_push( $css_files, $css_files_path . 'progress-bar/progress-bar' . $css_min_ext );
						break;
					case 'reviews':
						if ( ! in_array( $js_files_path . 'testimonial/testimonial' . $ext, $js_files, true ) ) {
							array_push( $included_js, $js_files_path . 'testimonial/testimonial' . $ext );
							array_push( $js_files, $js_files_path . 'testimonial/testimonial' . $ext );
						}
						array_push( $css_files, $css_files_path . 'reviews/reviews' . $css_min_ext );
						break;
					case 'search-form':
						array_push( $js_files, $js_files_path . 'search-form/search-form' . $ext );
						array_push( $css_files, $css_files_path . 'search-form/search-form' . $css_min_ext );
						break;
					case 'slider':
						array_push( $js_files, $js_files_path . 'rael-slider/rael-slider' . $ext );
						array_push( $css_files, $css_files_path . 'slider/rael-frontend' . $css_min_ext );
						break;
					case 'timeline':
						array_push( $js_files, $js_files_path . 'timeline/timeline' . $ext );
						array_push( $css_files, $css_files_path . 'timeline/timeline' . $css_min_ext );
						break;
					case 'wpf-styler':
						array_push( $css_files, $css_files_path . 'wpfstyler/wpfstyler' . $css_min_ext );
						break;
					case 'sticky-video':
						array_push( $js_files, $js_files_path . 'sticky-video/sticky-video' . $ext );
						array_push( $css_files, $css_files_path . 'sticky-video/sticky-video' . $css_min_ext );
						break;
					case 'table-of-contents':
						array_push( $js_files, $js_files_path . 'table-of-contents/table-of-contents' . $ext );
						array_push( $css_files, $css_files_path . 'table-of-contents/table-of-contents' . $css_min_ext );
						break;
					case 'team-member':
						array_push( $js_files, $js_files_path . 'team-member/team-member' . $ext );
						array_push( $css_files, $css_files_path . 'team-member/team-member' . $css_min_ext );
						break;
					case 'testimonial-slider':
						if ( ! in_array( $js_files_path . 'testimonial/testimonial' . $ext, $js_files, true ) ) {
							array_push( $included_js, $js_files_path . 'testimonial/testimonial' . $ext );
							array_push( $js_files, $js_files_path . 'testimonial/testimonial' . $ext );
						}
						array_push( $css_files, $css_files_path . 'testimonial-slider/testimonial-slider' . $css_min_ext );
						array_push( $css_files, $css_files_path . 'media-carousel/media-carousel' . $css_min_ext );
						break;
					case 'twitter-feed':
						array_push( $js_files, $js_files_path . 'twitter-feed/twitter-feed' . $ext );
						array_push( $css_files, $css_files_path . 'twitter-feed/twitter-feed' . $css_min_ext );
						break;
					case 'video':
						array_push( $js_files, $js_files_path . 'video/video' . $ext );
						array_push( $css_files, $css_files_path . 'video/video' . $css_min_ext );
						break;
					case 'one-page-navigation':
						array_push( $js_files, $js_files_path . 'one-page-navigation/one-page-navigation' . $ext );
						array_push( $css_files, $css_files_path . 'one-page-navigation/one-page-navigation' . $css_min_ext );
						break;
					case 'logo-carousel':
						array_push( $js_files, $js_files_path . 'logo-carousel/logo-carousel' . $ext );
						array_push( $css_files, $css_files_path . 'logo-carousel/logo-carousel' . $css_min_ext );
						break;
					case 'data-table':
						array_push( $js_files, $js_files_path . 'data-table/data-table' . $ext );
						array_push( $css_files, $css_files_path . 'data-table/data-table' . $css_min_ext );
						break;
					case 'content-ticker':
						array_push( $js_files, $js_files_path . 'content-ticker/content-ticker' . $ext );
						array_push( $css_files, $css_files_path . 'content-ticker/content-ticker' . $css_min_ext );
						break;
					case 'cf-styler':
						array_push( $js_files, $js_files_path . 'contact-form/contact-form' . $ext );
						array_push( $css_files, $css_files_path . 'cf7/cf7styler' . $css_min_ext );
						break;
					case 'advanced-tabs':
						array_push( $js_files, $js_files_path . 'advanced-tabs/advanced-tabs' . $ext );
						array_push( $css_files, $css_files_path . 'advanced-tabs/advanced-tabs' . $css_min_ext );
						break;
					case 'pricing-table':
						array_push( $css_files, $css_files_path . 'pricing-table/pricing-table-frontend' . $css_min_ext );
						break;
					case 'price-list':
						array_push( $css_files, $css_files_path . 'price-list/price-list-frontend' . $css_min_ext );
						break;
					case 'posts':
						array_push( $js_files, $js_files_path . 'posts/posts' . $ext );
						array_push( $js_files, $js_files_path . 'posts/posts-cards' . $ext );
						array_push( $css_files, $css_files_path . 'posts/posts' . $css_min_ext );
						break;
					case 'price-box':
						array_push( $js_files, $js_files_path . 'price-box/price-box' . $ext );
						array_push( $css_files, $css_files_path . 'price-box/price-box' . $css_min_ext );
						break;
					case 'post-carousel':
						array_push( $js_files, $js_files_path . 'post-carousel/post-carousel' . $ext );
						array_push( $css_files, $css_files_path . 'post-carousel/post-carousel' . $css_min_ext );
						break;
					case 'offcanvas':
						array_push( $js_files, $js_files_path . 'offcanvas/offcanvas' . $ext );
						array_push( $css_files, $css_files_path . 'offcanvas/offcanvas' . $css_min_ext );
						break;
					case 'nav-menu':
						array_push( $js_files, $js_files_path . 'nav-menu/nav-menu' . $ext );
						array_push( $css_files, $css_files_path . 'nav-menu/nav-menu' . $css_min_ext );
						array_push( $css_files, $css_files_path . 'nav-menu/pointer' . $css_min_ext );
						break;
					case 'login-register':
						array_push( $js_files, $js_files_path . 'login-register/login-register' . $ext );
						array_push( $css_files, $css_files_path . 'login-register/login-register' . $css_min_ext );
						break;
					case 'media-carousel':
						array_push( $js_files, $js_files_path . 'media-carousel/base-slider' . $ext );
						array_push( $js_files, $js_files_path . 'media-carousel/media-carousel' . $ext );
						array_push( $css_files, $css_files_path . 'media-carousel/media-carousel' . $css_min_ext );
						break;
					case 'google-map':
						array_push( $js_files, $js_files_path . 'google-map/google-map' . $ext );
						array_push( $css_files, $css_files_path . 'google-map/google-map' . $css_min_ext );
						break;
					case 'lottie':
						array_push( $js_files, $js_files_path . 'lottie/lottie' . $ext );
						array_push( $css_files, $css_files_path . 'lottie/lottie' . $css_min_ext );
						break;
					case 'woo-products':
						if ( ! in_array( $js_files_path . 'product-carousel/quick-view' . $ext, $js_files, true ) ) {
							array_push( $included_js, $js_files_path . 'product-carousel/quick-view' . $ext );
							array_push( $js_files, $js_files_path . 'product-carousel/quick-view' . $ext );
						}
						array_push( $js_files, $js_files_path . 'products/products' . $ext );
						array_push( $js_files, $js_files_path . 'products/loadmore' . $ext );
						array_push( $css_files, $css_files_path . 'products/products' . $css_min_ext );
						array_push( $css_files, $css_files_path . 'products/loadmore' . $css_min_ext );
						break;
					case 'wc-add-to-cart':
						array_push( $css_files, $css_files_path . 'add-to-cart/add-to-cart-frontend' . $css_min_ext );
						break;
					case 'product-category-grid':
						array_push( $css_files, $css_files_path . 'product-category-grid/product-category-grid' . $css_min_ext );
						break;
					case 'product-carousel':
						if ( ! in_array( $js_files_path . 'product-carousel/quick-view' . $ext, $js_files, true ) ) {
							array_push( $included_js, $js_files_path . 'product-carousel/quick-view' . $ext );
							array_push( $css_files, $css_files_path . 'quick-view/quick-view' . $css_min_ext );
							array_push( $js_files, $js_files_path . 'product-carousel/quick-view' . $ext );
						}
						array_push( $css_files, $css_files_path . 'quick-view/quick-view' . $css_min_ext );
						array_push( $js_files, $js_files_path . 'product-carousel/product-carousel' . $ext );
						array_push( $css_files, $css_files_path . 'product-carousel/product-carousel' . $css_min_ext );
						break;
					case 'woo-checkout':
						array_push( $js_files, $js_files_path . 'woo-checkout/woo-checkout-main' . $ext );
						array_push( $js_files, $js_files_path . 'woo-checkout/woo-checkout' . $ext );
						array_push( $css_files, $css_files_path . 'woo-checkout/woo-checkout' . $css_min_ext );
						break;
				}
			}
		}
		// Theme Builder & Module CSS.
		$theme_builder_widget_css = array(
			'theme-post-info/theme-post-info',
			'theme-author-box/theme-author-box',
			'theme-post-navigation/theme-post-navigation',
			'product-meta/product-meta',
			'product-archive/product-archive',
			'theme-archive-posts/theme-archive-posts',
			'theme-builder/style',
		);

		foreach ( $theme_builder_widget_css as $file_name ) {
			$css_files[] = $css_files_path . $file_name . $css_min_ext;
		}

		// Theme Builder & Module CSS.
		$theme_builder_widget_css = array(
			'theme-post-info/theme-post-info',
			'theme-author-box/theme-author-box',
			'theme-post-navigation/theme-post-navigation',
			'product-meta/product-meta',
			'product-archive/product-archive',
			'theme-archive-posts/theme-archive-posts',
			'theme-builder/style',
		);

		foreach ( $theme_builder_widget_css as $file_name ) {
			$css_files[] = $css_files_path . $file_name . $css_min_ext;
		}

		$js_success  = $this->write_frontend_file( $js_files, $target_js_file_path );
		$css_success = $this->write_frontend_file( $css_files, $target_css_file_path );

		if ( $js_success && $css_success ) {
			if ( true === $extend ) {
				return true;
			}
			wp_send_json_success();
		} else {
			if ( true === $extend ) {
				return false;
			}
			wp_send_json_error();
		}
	}

	/**
	 * Writes the JS & CSS content of user allowed widgets
	 *
	 * @param array  $asset_files      Array of widgets js files.
	 * @param string $target_file_path File path of frontend.
	 * @since 1.0.0
	 */
	public function write_frontend_file( $asset_files, $target_file_path ) {

		global $wp_filesystem;
		WP_Filesystem();

		$combined_content = ''; // Initialize an empty string to store combined content.

		foreach ( $asset_files as $file ) {
			$content           = $wp_filesystem->get_contents( $file );
			$combined_content .= $content; // Append content from each asset file.
		}

		// Write the combined content to the target file.
		$wp_filesystem->put_contents( $target_file_path, $combined_content, FILE_APPEND );

		// Set permissions after writing combined content.
		$wp_filesystem->chmod( $target_file_path, 0666 );

		return true;
	}

	/**
	 * RAEL Register Admin Menu.
	 *
	 * @param string $slug parent slug of submenu.
	 * @since 2.0.2
	 */
	public function rael_register_admin_menu( $slug ) {

		add_submenu_page(
			$slug,
			__( 'Responsive Elementor Add-Ons', 'responsive-addons-for-elementor' ),
			__( 'Elementor Add-Ons', 'responsive-addons-for-elementor' ),
			'manage_options',
			'rael_getting_started',
			array( $this, 'responsive_addons_for_elementor_getting_started' ),
			10
		);

		add_submenu_page(
			$slug,
			__( 'Theme Builder', 'responsive-addons-for-elementor' ),
			__( 'Theme Builder', 'responsive-addons-for-elementor' ),
			'edit_pages',
			'edit.php?post_type=rael-theme-template'
		);

	}

	/**
	 * Add Icon for Theme Builder under Elementor Addons Submenu.
	 *
	 * @since 2.0.2
	 */
	public function responsive_addons_for_elementor_responsive_menu() {
		wp_enqueue_style( 'responsive_addons_for_elementor_responsive_menu-style', RAEL_URL . 'admin/css/rael-responsive-menu.css', array(), RAEL_VER );
		wp_enqueue_script( 'responsive_addons_for_elementor_responsive_menu', RAEL_URL . 'admin/js/rael-responsive-menu.js', array( 'jquery' ), RAEL_VER, true );
	}

	/**
	 * Mailchip Subscribe.
	 */
	public function mailchimp_subscribe_with_ajax() {
		if ( ! isset( $_POST['fields'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Missing
			return;
		}

		$api_key = $_POST['apiKey']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing
		$list_id = $_POST['listId']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing

		parse_str( $_POST['fields'], $settings ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing

		$merge_fields = array(
			'FNAME' => ! empty( $settings['rael_mailchimp_firstname'] ) ? $settings['rael_mailchimp_firstname'] : '',
			'LNAME' => ! empty( $settings['rael_mailchimp_lastname'] ) ? $settings['rael_mailchimp_lastname'] : '',
		);

		$response = wp_remote_post(
			'https://' . substr(
				$api_key,
				strpos(
					$api_key,
					'-'
				) + 1
			) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5( strtolower( $settings['rael_mailchimp_email'] ) ),
			array(
				'method'  => 'PUT',
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => 'Basic ' . base64_encode( 'user:' . $api_key ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
				),
				'body'    => wp_json_encode(
					array( // phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
					'email_address' => $settings['rael_mailchimp_email'],
					'status'        => 'subscribed',
					'merge_fields'  => $merge_fields,
					)
				),
			)
		);

		if ( ! is_wp_error( $response ) ) {
			$response = json_decode( wp_remote_retrieve_body( $response ) );

			if ( ! empty( $response ) ) {
				if ( 'subscribed' === $response->status ) {
					wp_send_json(
						array(
							'status' => 'subscribed',
						)
					);
				} else {
					wp_send_json(
						array(
							'status' => $response->title,
						)
					);
				}
			}
		}
		die();
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'rael-select2', RAEL_URL . 'admin/assets/lib/select2/select2.js', array( 'jquery' ), RAEL_VER, true );

		// Select2 i18n.
		$wp_local_lang = get_locale();

		if ( '' !== $wp_local_lang ) {
			$select2_available_lang = array(
				''               => 'en',
				'hi_IN'          => 'hi',
				'mr'             => 'mr',
				'af'             => 'af',
				'ar'             => 'ar',
				'ary'            => 'ar',
				'as'             => 'as',
				'azb'            => 'az',
				'az'             => 'az',
				'bel'            => 'be',
				'bg_BG'          => 'bg',
				'bn_BD'          => 'bn',
				'bo'             => 'bo',
				'bs_BA'          => 'bs',
				'ca'             => 'ca',
				'ceb'            => 'ceb',
				'cs_CZ'          => 'cs',
				'cy'             => 'cy',
				'da_DK'          => 'da',
				'de_CH'          => 'de',
				'de_DE'          => 'de',
				'de_DE_formal'   => 'de',
				'de_CH_informal' => 'de',
				'dzo'            => 'dz',
				'el'             => 'el',
				'en_CA'          => 'en',
				'en_GB'          => 'en',
				'en_AU'          => 'en',
				'en_NZ'          => 'en',
				'en_ZA'          => 'en',
				'eo'             => 'eo',
				'es_MX'          => 'es',
				'es_VE'          => 'es',
				'es_CR'          => 'es',
				'es_CO'          => 'es',
				'es_GT'          => 'es',
				'es_ES'          => 'es',
				'es_CL'          => 'es',
				'es_PE'          => 'es',
				'es_AR'          => 'es',
				'et'             => 'et',
				'eu'             => 'eu',
				'fa_IR'          => 'fa',
				'fi'             => 'fi',
				'fr_BE'          => 'fr',
				'fr_FR'          => 'fr',
				'fr_CA'          => 'fr',
				'gd'             => 'gd',
				'gl_ES'          => 'gl',
				'gu'             => 'gu',
				'haz'            => 'haz',
				'he_IL'          => 'he',
				'hr'             => 'hr',
				'hu_HU'          => 'hu',
				'hy'             => 'hy',
				'id_ID'          => 'id',
				'is_IS'          => 'is',
				'it_IT'          => 'it',
				'ja'             => 'ja',
				'jv_ID'          => 'jv',
				'ka_GE'          => 'ka',
				'kab'            => 'kab',
				'km'             => 'km',
				'ko_KR'          => 'ko',
				'ckb'            => 'ku',
				'lo'             => 'lo',
				'lt_LT'          => 'lt',
				'lv'             => 'lv',
				'mk_MK'          => 'mk',
				'ml_IN'          => 'ml',
				'mn'             => 'mn',
				'ms_MY'          => 'ms',
				'my_MM'          => 'my',
				'nb_NO'          => 'nb',
				'ne_NP'          => 'ne',
				'nl_NL'          => 'nl',
				'nl_NL_formal'   => 'nl',
				'nl_BE'          => 'nl',
				'nn_NO'          => 'nn',
				'oci'            => 'oc',
				'pa_IN'          => 'pa',
				'pl_PL'          => 'pl',
				'ps'             => 'ps',
				'pt_BR'          => 'pt',
				'pt_PT_ao90'     => 'pt',
				'pt_PT'          => 'pt',
				'rhg'            => 'rhg',
				'ro_RO'          => 'ro',
				'ru_RU'          => 'ru',
				'sah'            => 'sah',
				'si_LK'          => 'si',
				'sk_SK'          => 'sk',
				'sl_SI'          => 'sl',
				'sq'             => 'sq',
				'sr_RS'          => 'sr',
				'sv_SE'          => 'sv',
				'szl'            => 'szl',
				'ta_IN'          => 'ta',
				'te'             => 'te',
				'th'             => 'th',
				'tl'             => 'tl',
				'tr_TR'          => 'tr',
				'tt_RU'          => 'tt',
				'tah'            => 'ty',
				'ug_CN'          => 'ug',
				'uk'             => 'uk',
				'ur'             => 'ur',
				'uz_UZ'          => 'uz',
				'vi'             => 'vi',
				'zh_CN'          => 'zh',
				'zh_TW'          => 'zh',
				'zh_HK'          => 'zh',
			);

			if ( isset( $select2_available_lang[ $wp_local_lang ] ) && file_exists( RAEL_URL . 'admin/assets/lib/select2/i18n/' . $select2_available_lang[ $wp_local_lang ] . '.js' ) ) {
				wp_enqueue_script(
					'rael-select2-lang',
					RAEL_URL . 'admin/assets/lib/select2/i18n/' . $select2_available_lang[ $wp_local_lang ] . '.js',
					array( 'jquery', 'rael-select2' ),
					RAEL_VER,
					true
				);
			}
		}

		wp_register_style( 'rael-select2-style', RAEL_URL . 'admin/assets/lib/select2/select2.css', array(), RAEL_VER );
		wp_enqueue_style( 'rael-select2-style' );

		wp_enqueue_script(
			'rael-admin',
			RAEL_URL . 'admin/assets/js/admin.js',
			array( 'jquery' ),
			RAEL_VER,
			true
		);

		$locale_settings = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'responsive-addons-for-elementor' ),
		);

		wp_localize_script(
			'rael-admin',
			'localize',
			$locale_settings
		);
	}

	/**
	 * Facebook Feed
	 *
	 * @param array $settings optional widget's settings.
	 * @return false|string|void
	 * @since 1.3.1
	 */
	public function rael_render_facebook_feed( $settings ) {

		// check if ajax request.
		if ( ! empty( $_REQUEST['action'] ) && 'facebook_feed_load_more' === $_REQUEST['action'] ) {

			$ajax = wp_doing_ajax();
			// check ajax referer.
			check_ajax_referer( 'facebook_feed_ajax_nonce' );

			// init vars.
			$page = isset( $_REQUEST['page'] ) ? intval( $_REQUEST['page'], 10 ) : 0;
			if ( ! empty( $_POST['post_id'] ) ) {
				$post_id = intval( $_POST['post_id'], 10 );
			} else {
				$err_msg = __( 'Post ID is missing', 'responsive-elementor-addons' );
				if ( $ajax ) {
					wp_send_json_error( $err_msg );
				}
				return false;
			}
			if ( ! empty( $_POST['widget_id'] ) ) {
				$widget_id = sanitize_text_field( wp_unslash( $_POST['widget_id'] ) );
			} else {
				$err_msg = __( 'Widget ID is missing', 'responsive-elementor-addons' );
				if ( $ajax ) {
					wp_send_json_error( $err_msg );
				}
				return false;
			}
			$settings = $this->rael_get_widget_settings( $post_id, $widget_id );

		} else {
			$page = 0;
		}

		$html    = '';
		$page_id = $settings['rael_facebook_feed_page_id'];
		$token   = $settings['rael_facebook_feed_access_token'];

		if ( empty( $page_id ) || empty( $token ) ) {
			return;
		}

		$key           = 'rael_facebook_feed_' . hash( 'sha256', str_replace( '.', '', $page_id . $token ) ) . $settings['rael_facebook_feed_cache_limit'];
		$facebook_data = get_transient( $key );

		if ( false === $facebook_data ) {
			$facebook_data = wp_remote_retrieve_body( wp_remote_get( "https://graph.facebook.com/v11.0/{$page_id}/posts?fields=status_type,created_time,from,message,story,full_picture,permalink_url,attachments.limit(1){type,media_type,title,description,unshimmed_url},comments.summary(total_count),reactions.summary(total_count)&limit=99&access_token={$token}", array( 'timeout' => 70 ) ) );

			$facebook_data = json_decode( $facebook_data, true );

			if ( isset( $facebook_data['data'] ) ) {
				set_transient( $key, $facebook_data, ( $settings['rael_facebook_feed_cache_limit'] * MINUTE_IN_SECONDS ) );
			}
		}

		if ( ! isset( $facebook_data['data'] ) ) {
			return;
		}
		$facebook_data = $facebook_data['data'];

		switch ( $settings['rael_facebook_feed_sort_by'] ) {
			case 'least-recent':
				$facebook_data = array_reverse( $facebook_data );
				break;
		}
		$items = array_splice( $facebook_data, ( $page * $settings['rael_facebook_feed_image_count']['size'] ), $settings['rael_facebook_feed_image_count']['size'] );

		foreach ( $items as $item ) {
			$max           = 'rael_facebook_feed_message_max_length';
			$limit         = isset( $settings[ $max ] ) && isset( $settings[ $max ]['size'] ) ? $settings[ $max ]['size'] : null;
			$message       = wp_trim_words( ( isset( $item['message'] ) ? $item['message'] : ( isset( $item['story'] ) ? $item['story'] : '' ) ), $limit, '...' );
			$photo         = ( isset( $item['full_picture'] ) ? esc_url( $item['full_picture'] ) : '' );
			$likes         = ( isset( $item['reactions'] ) ? $item['reactions']['summary']['total_count'] : 0 );
			$post_comments = ( isset( $item['comments'] ) ? $item['comments']['summary']['total_count'] : 0 );

			$fb_url = 'https://www.facebook.com/';
			if ( 'card' === $settings['rael_facebook_feed_layout'] ) {
				$html .= '<div class="rael-fb-feed-item">
					<div class="rael-fb-feed-item-content-container">
					<header class="rael-fb-feed-item-header">
						<div class="rael-fb-feed-item-user">
							<a class="rael-fb-feed-user-image" href="' . $fb_url . $page_id . '" target="' . ( 'yes' === $settings['rael_facebook_feed_link_target'] ? '_blank' : '_self' ) . '"><img src="https://graph.facebook.com/v11.0/' . $page_id . '/picture" alt="' . $item['from']['name'] . '" class="rael-fb-feed-avatar"></a>
							<a href="' . $fb_url . $page_id . '" target="' . ( 'yes' === $settings['rael_facebook_feed_link_target'] ? '_blank' : '_self' ) . '"><p class="rael-fb-feed-username">' . $item['from']['name'] . '</p></a>
						</div>';

				if ( $settings['rael_facebook_feed_date'] ) {
					$html .= '<a href="' . $item['permalink_url'] . '" target="' . ( $settings['rael_facebook_feed_link_target'] ? '_blank' : '_self' ) . '" class="rael-fb-feed-post-time"><i class="far fa-clock" aria-hidden="true"></i> ' . gmdate( 'd M Y', strtotime( $item['created_time'] ) ) . '</a>';
				}
				$html .= '</header>';

				if ( $settings['rael_facebook_feed_message'] && ! empty( $message ) ) {
					$html .= '<div class="rael-fb-feed-item-content">
								<p class="rael-fb-feed-message">' . esc_html( $message ) . '</p>
							</div>';
				}

				if ( ! empty( $photo ) || isset( $item['attachments']['data'] ) ) {
					$html .= '<div class="rael-fb-feed-media-wrap">';
					if ( 'shared_story' === $item['status_type'] ) {
						if ( isset( $settings['rael_facebook_feed_is_show_preview_thumbnail'] ) && 'yes' === $settings['rael_facebook_feed_is_show_preview_thumbnail'] ) {
							$html .= '<a href="' . $item['permalink_url'] . '" target="' . ( 'yes' === $settings['rael_facebook_feed_link_target'] ? '_blank' : '_self' ) . '" class="rael-fb-feed-media-link">';
							if ( 'video' === $item['attachments']['data'][0]['media_type'] ) {
								$html .= '<img class="rael-fb-feed-content-image" src="' . $photo . '" />
											<div class="rael-fb-feed-content-image-overlay"><i class="far fa-play-circle" aria-hidden="true"></i></div>';
							} else {
								$html .= '<img class="rael-fb-feed-content-image" src="' . $photo . '" />';
							}
							$html .= '</a>';
						}

						$html .= '<div class="rael-fb-feed-url-preview">';
						if ( isset( $settings['rael_facebook_feed_is_show_preview_host'] ) && 'yes' === $settings['rael_facebook_feed_is_show_preview_host'] ) {
							$html .= '<p class="rael-fb-feed-url-host">' . wp_parse_url( $item['attachments']['data'][0]['unshimmed_url'] )['host'] . '</p>';
						}
						if ( isset( $settings['rael_facebook_feed_is_show_preview_title'] ) && 'yes' === $settings['rael_facebook_feed_is_show_preview_title'] ) {
							$html .= '<h2 class="rael-facebook-feed-url-title">' . $item['attachments']['data'][0]['title'] . '</h2>';
						}

						if ( isset( $settings['rael_facebook_feed_is_show_preview_description'] ) && 'yes' === $settings['rael_facebook_feed_is_show_preview_description'] ) {
							$html .= '<p class="rael-fb-feed-url-description">' . $item['attachments']['data'][0]['description'] . '</p>';
						}
						$html .= '</div>';
					} elseif ( 'added_video' === $item['status_type'] ) {
						if ( isset( $settings['rael_facebook_feed_is_show_preview_thumbnail'] ) && 'yes' === $settings['rael_facebook_feed_is_show_preview_thumbnail'] ) {
							$html .= '<a href="' . $item['permalink_url'] . '" target="' . ( 'yes' === $settings['rael_facebook_feed_link_target'] ? '_blank' : '_self' ) . '" class="rael-fb-feed-preview-img">
											<img class="rael-fb-feed-img" src="' . $photo . '">
											<div class="rael-facebook-feed-preview-overlay"><i class="far fa-play-circle" aria-hidden="true"></i></div>
										</a>';
						}
					} else {
						if ( isset( $settings['rael_facebook_feed_is_show_preview_thumbnail'] ) && 'yes' === $settings['rael_facebook_feed_is_show_preview_thumbnail'] ) {
							$html .= '<a href="' . $item['permalink_url'] . '" target="' . ( 'yes' === $settings['rael_facebook_feed_link_target'] ? '_blank' : '_self' ) . '" class="rael-fb-feed-preview-img">
											<img class="rael-fb-feed-img" src="' . $photo . '">
										</a>';
						}
					}
					$html .= '</div>';
				}
				if ( $settings['rael_facebook_feed_likes'] || $settings['rael_facebook_feed_comments'] ) {
					$html .= '<footer class="rael-fb-feed-item-footer">';
					if ( $settings['rael_facebook_feed_likes'] ) {
						$html .= '<span class="rael-fb-feed-post-likes"><i class="far fa-thumbs-up" aria-hidden="true"></i> ' . $likes . '</span>';
					}
					if ( $settings['rael_facebook_feed_comments'] ) {
						$html .= '<span class="rael-fb-feed-post-comments"><i class="far fa-comments" aria-hidden="true"></i> ' . $post_comments . '</span>';
					}
					$html .= '</footer>';
				}
				$html .= '</div></div>';
			} else {
				if ( isset( $settings['rael_facebook_feed_is_show_preview_thumbnail'] ) && 'yes' === $settings['rael_facebook_feed_is_show_preview_thumbnail'] ) {
					if ( empty( $photo ) ) {
						if ( empty( $settings['rael_overlay_image']['url'] ) ) {
							$photo_url = RAEL_ASSETS_URL . 'images/facebook-feed/abstract_leaves.png';
						} else {
							$photo_url = $settings['rael_overlay_image']['url'];
						}
					} else {
						$photo_url = $photo;
					}
				} else {
					if ( empty( $settings['rael_overlay_image']['url'] ) ) {
						$photo_url = RAEL_ASSETS_URL . 'images/facebook-feed/abstract_leaves.png';
					} else {
						$photo_url = $settings['rael_overlay_image']['url'];
					}
				}
				$html .= '<a href="' . $item['permalink_url'] . '" target="' . ( $settings['rael_facebook_feed_link_target'] ? '_blank' : '_self' ) . '" class="rael-fb-feed-item">
						<div class="rael-fb-feed-item-content-container hover-container">
							<img class="rael-fb-feed-img" src="' . $photo_url . '">';

				if ( $settings['rael_facebook_feed_likes'] || $settings['rael_facebook_feed_comments'] ) {
					$html .= '<div class="rael-fb-feed-item-overlay hover-content">
								<div class="rael-fb-feed-item-overlay-inner">';

					if ( $settings['rael_facebook_feed_message'] && ! empty( $message ) ) {
						$html .= '<div class="rael-fb-feed-item-content">
													<div class="hover-user-content">
														<img src="https://graph.facebook.com/v11.0/' . $page_id . '/picture" alt="' . $item['from']['name'] . '" class="rael-fb-feed-avatar">
														<p class="rael-fb-feed-username">' . esc_html( $item['from']['name'] ) . '</p>
													</div>
													<p class="rael-fb-feed-message">' . esc_html( $message ) . '</p>
												</div>';
					}
									$html .= '<div class="rael-fb-feed-meta">';
					if ( $settings['rael_facebook_feed_likes'] ) {
						$html .= '<span class="rael-fb-feed-post-likes"><i class="far fa-thumbs-up" aria-hidden="true"></i> ' . $likes . '</span>';
					}
					if ( $settings['rael_facebook_feed_comments'] ) {
						$html .= '<span class="rael-fb-feed-post-comments"><i class="far fa-comments" aria-hidden="true"></i> ' . $post_comments . '</span>';
					}
									$html .= '</div>
								</div>
							</div>';
				}
				$html .= '</div></a>';
			}
		}

		if ( isset( $_REQUEST['action'] ) && 'facebook_feed_load_more' === $_REQUEST['action'] ) {
			$data = array(
				'num_pages' => ceil( count( $facebook_data ) / $settings['rael_facebook_feed_image_count']['size'] ),
				'html'      => $html,
			);
			while ( ob_get_status() ) {
				ob_end_clean();
			}
			if ( function_exists( 'gzencode' ) ) {
				$response = gzencode( wp_json_encode( $data ) );
				header( 'Content-Type: application/json; charset=utf-8' );
				header( 'Content-Encoding: gzip' );
				header( 'Content-Length: ' . strlen( $response ) );

				echo wp_kses_post( $response );
			} else {
				wp_send_json( $data );
			}
			wp_die();
		}

		echo wp_kses_post( $html );
	}

	/**
	 * Return widget settings.
	 *
	 * @param integer $page_id    Page ID.
	 * @param string  $widget_id  Widget ID.
	 *
	 * @access public
	 */
	public function rael_get_widget_settings( $page_id, $widget_id ) {
		$document = Plugin::$instance->documents->get( $page_id );
		$settings = array();
		if ( $document ) {
			$elements    = $document->get_elements_data();
			$widget_data = self::find_element_recursive( $elements, $widget_id );

			if ( ! empty( $widget_data ) ) {
				$widget = Plugin::instance()->elements_manager->create_element_instance( $widget_data );
				if ( $widget ) {
					$settings = $widget->get_settings_for_display();
				}
			}
		}
		return $settings;
	}

	/**
	 * Get Widget data.
	 *
	 * @param array  $elements Element array.
	 * @param string $form_id  Element ID.
	 *
	 * @return bool|array
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

	/**
	 * Woo Checkout Update Order Review
	 * return order review data
	 *
	 * @access public
	 * @return void
	 * @since 1.8.0
	 */
	public function woo_checkout_update_order_review() {
		if ( isset( $_POST['orderReviewData'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Missing
			$setting = $_POST['orderReviewData']; //phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			ob_start();
			Woo_Checkout_Helper::checkout_order_review_default( $setting );
			$woo_checkout_update_order_review = ob_get_clean();
			wp_send_json(
				array(
					'order_review' => $woo_checkout_update_order_review,
				)
			);
		}
	}

}
