<?php
/**
 * RAEL Theme Builder's woocommerce widget base abstract class.
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;
use Elementor\Widget_Base;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\Woocommerce\Classes\Products_Renderer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * RAEL woocommerce widget base abstract class
 */
abstract class Woo_Widget_Base extends Widget_Base {

	/**
	 * Get text modifications variable
	 *
	 * @var gettext_modifications
	 */
	protected $gettext_modifications;

	/**
	 * Get categories
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get device default arguments
	 */
	protected function get_devices_default_args() {
		$devices_required = array();

		// Make sure device settings can inherit from larger screen sizes' breakpoint settings.
		foreach ( Breakpoints_Manager::get_default_config() as $breakpoint_name => $breakpoint_config ) {
			$devices_required[ $breakpoint_name ] = array(
				'required' => false,
			);
		}

		return $devices_required;
	}

	/**
	 * Add columns Responsive Controls
	 */
	protected function add_columns_responsive_control() {
		$this->add_responsive_control(
			'columns',
			array(
				'label'               => esc_html__( 'Columns', 'responsive-addons-for-elementor' ),
				'type'                => Controls_Manager::NUMBER,
				'prefix_class'        => 'elementor-grid%s-',
				'min'                 => 1,
				'max'                 => 12,
				'default'             => Products_Renderer::DEFAULT_COLUMNS_AND_ROWS,
				'tablet_default'      => '3',
				'mobile_default'      => '2',
				'required'            => true,
				'device_args'         => $this->get_devices_default_args(),
				'min_affected_device' => array(
					Controls_Stack::RESPONSIVE_DESKTOP => Controls_Stack::RESPONSIVE_TABLET,
					Controls_Stack::RESPONSIVE_TABLET  => Controls_Stack::RESPONSIVE_TABLET,
				),
			)
		);
	}

	/**
	 * Is WooCommerce Feature Active.
	 *
	 * Checks whether a specific WooCommerce feature is active. These checks can sometimes look at multiple WooCommerce
	 * settings at once so this simplifies and centralizes the checking.
	 *
	 * @since 3.5.0
	 *
	 * @param string $feature is parameter for feature.
	 * @return bool
	 */
	protected function is_wc_feature_active( $feature ) {
		switch ( $feature ) {
			case 'checkout_login_reminder':
				return 'yes' === get_option( 'woocommerce_enable_checkout_login_reminder' );
			case 'shipping':
				if ( class_exists( 'WC_Shipping_Zones' ) ) {
					$all_zones = \WC_Shipping_Zones::get_zones();
					if ( count( $all_zones ) > 0 ) {
						return true;
					}
				}
				break;
			case 'coupons':
				return function_exists( 'wc_coupons_enabled' ) && wc_coupons_enabled();
			case 'signup_and_login_from_checkout':
				return 'yes' === get_option( 'woocommerce_enable_signup_and_login_from_checkout' );
			case 'ship_to_billing_address_only':
				return wc_ship_to_billing_address_only();
		}

		return false;
	}

	/**
	 * Get Custom Border Type Options
	 *
	 * Return a set of border options to be used in different WooCommerce widgets.
	 *
	 * This will be used in cases where the Group Border Control could not be used.
	 *
	 * @since 3.5.0
	 *
	 * @return array
	 */
	public static function get_custom_border_type_options() {
		return array(
			'none'   => esc_html__( 'None', 'responsive-addons-for-elementor' ),
			'solid'  => esc_html__( 'Solid', 'responsive-addons-for-elementor' ),
			'double' => esc_html__( 'Double', 'responsive-addons-for-elementor' ),
			'dotted' => esc_html__( 'Dotted', 'responsive-addons-for-elementor' ),
			'dashed' => esc_html__( 'Dashed', 'responsive-addons-for-elementor' ),
			'groove' => esc_html__( 'Groove', 'responsive-addons-for-elementor' ),
		);
	}

	/**
	 * Init Gettext Modifications
	 *
	 * Should be overridden by a method in the Widget class.
	 *
	 * @since 3.5.0
	 */
	protected function init_gettext_modifications() {
		$this->gettext_modifications = array();
	}

	/**
	 * Filter Gettext.
	 *
	 * Filter runs when text is output to the page using the translation functions (`_e()`, `__()`, etc.)
	 * used to apply text changes from the widget settings.
	 *
	 * This allows us to make text changes without having to ovveride WooCommerce templates, which would
	 * lead to dev tax to keep all the templates up to date with each future WC release.
	 *
	 * @since 3.5.0
	 *
	 * @param string $translation is parameter for translation.
	 * @param string $text is text parameter.
	 * @param string $domain is the paramter for translation domain.
	 * @return string
	 */
	public function filter_gettext( $translation, $text, $domain ) {
		if ( 'woocommerce' !== $domain && 'responsive-addons-for-elementor' !== $domain ) {
			return $translation;
		}

		if ( ! isset( $this->gettext_modifications ) ) {
			$this->init_gettext_modifications();
		}

		return array_key_exists( $text, $this->gettext_modifications ) ? $this->gettext_modifications[ $text ] : $translation;
	}
}
