<?php
/**
 * RAEL_Compatibility_Compat setup
 *
 * @package Responsive_Addons_For_Elementor
 */

use Responsive_Addons_For_Elementor\ModulesManager\Theme_Builder\RAEL_Theme_Builder;

/**
 * Astra theme compatibility.
 */
class RAEL_Compatibility_Compat {

	/**
	 * Instance of RAEL_Compatibility_Compat.
	 *
	 * @var RAEL_Compatibility_Compat
	 */
	private static $instance;

	/**
	 *  Initiator
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new RAEL_Compatibility_Compat();

			add_action( 'wp', array( self::$instance, 'hooks' ) );
		}

		return self::$instance;
	}

	/**
	 * Run all the Actions / Filters.
	 */
	public function hooks() {
		if ( rael_header_enabled() ) {
			add_action( 'template_redirect', array( $this, 'setup_header_compatibility' ), 10 );
			add_action( 'astra_header', 'rael_render_header' );
		}

		if ( rael_footer_enabled() ) {
			add_action( 'template_redirect', array( $this, 'setup_footer_compatibility' ), 10 );
			add_action( 'astra_footer', 'rael_render_footer' );
		}

		if ( rael_single_enabled() || rael_archive_enabled() || get_rael_error_404_id() || rael_single_page_enabled() ) {
			// Replace templates.
			add_filter( 'template_include', array( $this, 'override_single' ), 11 );
		}
	}

	/**
	 * Disable header from the theme.
	 */
	public function setup_header_compatibility() {
		remove_action( 'astra_header', 'astra_header_markup' );

		// Remove the new header builder action.
		if ( class_exists( 'Astra_Builder_Helper' ) && Astra_Builder_Helper::$is_header_footer_builder_active ) {
			remove_action( 'astra_header', array( Astra_Builder_Header::get_instance(), 'prepare_header_builder_markup' ) );
		}
	}

	/**
	 * Disable footer from the theme.
	 */
	public function setup_footer_compatibility() {
		remove_action( 'astra_footer', 'astra_footer_markup' );

		// Remove the new footer builder action.
		if ( class_exists( 'Astra_Builder_Helper' ) && Astra_Builder_Helper::$is_header_footer_builder_active ) {
			remove_action( 'astra_footer', array( Astra_Builder_Footer::get_instance(), 'footer_markup' ) );
		}
	}

	/**
	 * Function for overriding the single,archive templates in the elmentor way.
	 *
	 * @return void
	 */
	public function override_single() {

		if ( is_404() ) {
			require RAEL_DIR . 'themes/default/rael-header-footer-single.php';
		}
		if ( is_page() ) {
			require RAEL_DIR . 'themes/default/rael-header-footer-single.php';
		}
		if ( is_single() ) {
			require RAEL_DIR . 'themes/default/rael-header-footer-single.php';
		}
		if ( is_archive() ) {
			require RAEL_DIR . 'themes/default/rael-header-footer-archive.php';
		}
	}
}

RAEL_Compatibility_Compat::instance();
