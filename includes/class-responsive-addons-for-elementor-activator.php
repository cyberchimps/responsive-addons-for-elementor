<?php
/**
 * Fired during plugin activation
 *
 * @link  https://www.cyberchimps.com
 * @since 1.0.0
 *
 * @package    responsive-addons-for-elementor
 * @subpackage responsive-addons-for-elementor/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    responsive-addons-for-elementor
 * @subpackage responsive-addons-for-elementor/includes
 * @author     CyberChimps <support@cyberchimps.com>
 */
class Responsive_Addons_For_Elementor_Activator {


	/**
	 * Short Description.
	 *
	 * Long Description.
	 *
	 * @since 1.0.0
	 */
	public static function activate() {
		set_transient( 'responsive_addons_for_elementor_activation_redirect', true, MINUTE_IN_SECONDS );

		flush_rewrite_rules();

		include_once RAEL_DIR . 'includes/class-responsive-addons-for-elementor-widgets-updater.php';

		$rael_widgets_data = new Responsive_Addons_For_Elementor_Widgets_Updater();

		$rael_widgets_data->insert_widgets_data();

		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_slug    = 'responsive-elementor-addons/responsive-elementor-addons.php';
		$plugin_version = self::get_active_plugin_version( $plugin_slug );
		if ( $plugin_version && is_plugin_active( $plugin_slug ) && version_compare( $plugin_version, '2.0.5', '<' ) ) {
			update_site_option( 'is_old_responsive_elementor_addons_active', true );
		}
	}

	/**
	 * Get the version number of the installed plugin.
	 *
	 * @param string $plugin_slug Plugin Slug.
	 * @since 1.4
	 */
	public static function get_active_plugin_version( $plugin_slug ) {
		$plugin_path = WP_PLUGIN_DIR . '/' . $plugin_slug;
		if ( file_exists( $plugin_path ) ) {

			$plugin_data    = get_plugin_data( $plugin_path );
			$plugin_version = $plugin_data['Version'];

			return $plugin_version;
		} else {
			return 0;
		}
	}
}
