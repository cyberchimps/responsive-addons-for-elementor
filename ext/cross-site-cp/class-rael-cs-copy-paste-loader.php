<?php
/**
 * File comment for RaelCrossSiteCopyPasteLoader.php
 *
 * This file contains the definition of the RaelCrossSiteCopyPasteLoader class.
 *
 * @package Responsive_Addons_For_Elementor
 */

use Elementor\Core\Kits\Documents\Kit;

if ( ! class_exists( 'RaelCrossSiteCopyPasteLoader' ) ) {
	/**
	 * RaelCrossSiteCopyPasteLoader class.
	 *
	 * This class handles loading necessary files and initializing RAEL Cross Site Copy Paste settings.
	 */
	class RaelCrossSiteCopyPasteLoader {
		/**
		 * Singleton instance variable.
		 *
		 * @var RaelCrossSiteCopyPasteLoader|null $rael_instance The singleton instance of the RaelCrossSiteCopyPasteLoader class.
		 */

		private static $rael_instance = null;
		/**
		 * Constructor.
		 */
		public function __construct() {

			add_action( 'elementor/init', array( $this, 'rael_cs_copy_paste_tab_settings_init' ) );

			$this->load_files();
		}

		/**
		 * Load all the required Files.
		 */
		public function load_files() {
			if ( did_action( 'elementor/loaded' ) ) {
				require_once RAEL_DIR . 'includes/settings/rael-cs-options.php';
			}
			$enable_copy_paste = get_option( 'rael_enable_copy_paste_btn' );

			if ( isset( $enable_copy_paste ) && ( 1 == $enable_copy_paste ) ) {
				require_once RAEL_DIR . 'ext/cross-site-cp/class-rael-cs-copy-paste-btn.php';
			}

		}

		/**
		 * Initalized RAEL Cross Site copy paste tab setting.
		 */
		public function rael_cs_copy_paste_tab_settings_init() {
			require_once RAEL_DIR . 'includes/settings/rael-cs-controls.php';
			add_action(
				'elementor/kit/register_tabs',
				function ( Kit $kit ) {
					$kit->register_tab( 'responsive-addons-for-elementor', RaelCrossSiteCopyPasteControls::class );
				},
				1,
				40
			);
		}
		/**
		 * Get the singleton instance of the RaelCrossSiteCopyPasteLoader class.
		 *
		 * This method ensures that only one instance of the RaelCrossSiteCopyPasteLoader class is created.
		 * If an instance does not exist, it creates a new one; otherwise, it returns the existing instance.
		 *
		 * @return RaelCrossSiteCopyPasteLoader The singleton instance of the RaelCrossSiteCopyPasteLoader class.
		 */
		public static function get_instance() {
			if (
				is_null( self::$rael_instance )
			) {
				self::$rael_instance = new self();
			}
			return self::$rael_instance;
		}

	}
}


/**
 * Returns Instanse of the RAEL Cross Site Copy Paste
 */

if ( ! function_exists( 'rael_cs_init' ) ) {
	/**
	 * Initialize the RAEL Cross Site Copy Paste functionality.
	 *
	 * @return RaelCrossSiteCopyPasteLoader The singleton instance of the RaelCrossSiteCopyPasteLoader class.
	 */
	function rael_cs_init() {
		return RaelCrossSiteCopyPasteLoader::get_instance();
	}
}

rael_cs_init();
