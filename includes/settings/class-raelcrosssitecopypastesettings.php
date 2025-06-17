<?php
/**
 * File comment for RaelCrossSiteCopyPasteSettings.php
 *
 * This file contains the definition of the RaelCrossSiteCopyPasteSettings class.
 *
 * @package Responsive_Addons_For_Elementor
 */

use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * RaelCrossSiteCopyPasteSettings class.
 *
 * This class manages RAE Cross Site Copy Paste Settings.
 */
final class RaelCrossSiteCopyPasteSettings {
	/**
	 * Constructor.
	 *
	 * Initializes the RaelCrossSiteCopyPasteSettings class.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'rael_cs_copy_paste_callback' ) );
	}

	/**
	 * Get RAE Cross Site Copy Paste Settings.
	 *
	 * Retrieves the RAEL Cross Site Copy Paste Settings for the specified setting ID.
	 *
	 * @param string $setting_id The setting ID.
	 * @return mixed The setting value.
	 */
	public function rael_cs_copy_paste_settings( $setting_id ) {
		global $rael_cs_copy_paste_settings;
		$return = '';
		if ( ! isset( $rael_cs_copy_paste_settings['kit_settings'] ) ) {
			$kit = Plugin::$instance->documents->get( Plugin::$instance->kits_manager->get_active_id(), false );

			if ( false != $kit ) {
				$rael_cs_copy_paste_settings['kit_settings'] = $kit->get_settings();
			}
		}

		if ( isset( $rael_cs_copy_paste_settings['kit_settings'][ $setting_id ] ) ) {
			$return = $rael_cs_copy_paste_settings['kit_settings'][ $setting_id ];
		}

		return apply_filters( 'rael_cs_copy_paste_settings' . $setting_id, $return );
	}

	/**
	 * RAE Cross Site Copy Paste Callback.
	 *
	 * Handles the RAE Cross Site Copy Paste Callback.
	 */
	public function rael_cs_copy_paste_callback() {

		if ( $this->rael_cs_copy_paste_settings( 'rael_enable_copy_paste_btn' ) == 'yes' ) {
			update_option( 'rael_enable_copy_paste_btn', true );
		} else {
			update_option( 'rael_enable_copy_paste_btn', false );
		}
	}
}
new RaelCrossSiteCopyPasteSettings();
