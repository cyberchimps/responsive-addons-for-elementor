<?php
/**
 * Plugin Name: Responsive Addons for Elementor
 * Plugin URI:  https://cyberchimps.com/responsive-addons-for-elementor/
 * Description: Responsive Addons for Elementor plugin adds Elementor widgets and seamlessly integrates with any Elementor Package (Free, Pro). It is compatible with all popular WordPress themes.
 * Version:     1.7.1
 * Author:      Cyberchimps.com
 * Author URI:  https://cyberchimps.com/responsive-addons-for-elementor/
 * License:     GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: responsive-addons-for-elementor
 * Domain Path: /languages
 *
 * Elementor tested up to: 3.27
 * Elementor Pro tested up to: 3.25
 *
 * @package responsive-addons-for-elementor
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RAEL_VER', '1.7.1' );
define( 'RAEL_DIR', plugin_dir_path( __FILE__ ) );
define( 'RAEL_URL', plugins_url( '/', __FILE__ ) );
define( 'RAEL_PATH', plugin_basename( __FILE__ ) );
define( 'RAEL_ASSETS_URL', RAEL_URL . 'assets/' );
define( 'RAEL_PLUGIN_SHORT_NAME', 'RAE' );
if ( 'active' === get_option( 'elementor_experiment-e_swiper_latest' ) ) {
	define( 'RAEL_ELEMENTOR_SWIPER', true );
	define( 'RAEL_SWIPER_CONTAINER', '' );
} elseif ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.26.0', '>=' ) ) {
	define( 'RAEL_ELEMENTOR_SWIPER', true );
	define( 'RAEL_SWIPER_CONTAINER', '' );
} else {
	define( 'RAEL_SWIPER_CONTAINER', '-container' );
}
require_once RAEL_DIR . 'includes/class-responsive-addons-for-elementor.php';

/**
 * The code that runs during plugin activation.
 */
function responsive_addons_for_elementor_activate() {
	include_once plugin_dir_path( __FILE__ ) . 'includes/class-responsive-addons-for-elementor-activator.php';
	Responsive_Addons_For_Elementor_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function responsive_addons_for_elementor_deactivate() {
	include_once plugin_dir_path( __FILE__ ) . 'includes/class-responsive-addons-for-elementor-deactivator.php';
	Responsive_Addons_For_Elementor_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'responsive_addons_for_elementor_activate' );
register_deactivation_hook( __FILE__, 'responsive_addons_for_elementor_deactivate' );

/**
 * Load the Plugin Class.
 */
function responsive_addons_for_elementor_init() {
	Responsive_Addons_For_Elementor::instance();
}

responsive_addons_for_elementor_init();
