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

 use Elementor\Plugin;
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
		
		//when the migration is complete change the template prefix from rea to rael
		$done_migration_theme_builder_templates = get_option( 'rael_done_migration_theme_builder_templates', false );

		$migrate_success_transient =  ('yes' === get_site_transient( 'rea_to_rae_migration_complete' ));
		$migrate_success_option = ('complete' === get_option( 'rea_to_rae_migration_process' ));

		$to_migrate_templates = $migrate_success_transient && $migrate_success_option;

		if ( ! $done_migration_theme_builder_templates || ! $to_migrate_templates ) {
			self::responsive_addons_for_elementor_backup_theme_builder_template_db();
			update_option( 'rael_done_migration_theme_builder_templates', true );
		}
	}
	/**
	 * To import the templates correctly from rea to rae migrate.
	 *
	 */
	public static function responsive_addons_for_elementor_backup_theme_builder_template_db() {

		if ( ! current_user_can( 'manage_options' ) ) {
			$response = array(
				'message'       => 'You do not have capabilities to manage options',
				'response_type' => 'error',
			);
			wp_send_json_error( $response, 403 );
		}

		global $wpdb;

		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT post_table.ID, postmeta_table.meta_id, post_table.post_type
				FROM {$wpdb->posts} AS post_table
				LEFT JOIN {$wpdb->postmeta} AS postmeta_table ON post_table.ID = postmeta_table.post_id
				WHERE post_table.post_status IN ('publish', 'draft')
				AND (postmeta_table.meta_key LIKE %s OR postmeta_table.meta_key LIKE %s)",
				'%rea_%',
				'%rea-%'
			),
			ARRAY_N
		);

		if ( empty( $results ) ) {
			return;
		}				

		$theme_builder_ids = array();

		if ( $results ) {
			foreach ( $results as $result ) {
				if ( 'rea-theme-template' === $result[2] ) {
					$theme_builder_ids[] = $result[0];
				}
			}
		}

		// Replace the Theme Builder Post Type.
		foreach ( $theme_builder_ids as $theme_builder_id ) {
			wp_update_post(
				array(
					'ID'        => $theme_builder_id,
					'post_type' => 'rael-theme-template',
				),
			);

			$wpdb->query(
				$wpdb->prepare(
					"UPDATE {$wpdb->prefix}postmeta
					SET meta_key = CASE
						WHEN meta_key = 'rea_hf_include_locations' THEN 'rael_hf_include_locations'
						WHEN meta_key = 'rea_hf_exclude_locations' THEN 'rael_hf_exclude_locations'
						WHEN meta_key = 'rea_hf_target_user_roles' THEN 'rael_hf_target_user_roles'
						WHEN meta_key = 'rea_hf_template_type' THEN 'rael_hf_template_type'
						ELSE meta_key
					END
					WHERE post_id = %d AND meta_key IN ('rea_hf_include_locations', 'rea_hf_exclude_locations', 'rea_hf_target_user_roles', 'rea_hf_template_type')",
					$theme_builder_id
				)
			);
		}
		Plugin::$instance->files_manager->clear_cache();
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
