<?php
/**
 * WPML Compatibility for Theme Builder Template builder.
 *
 * @package     Responsive_Addons_For_Elementor
 * @author      Cyberchimps
 *
 * @since       1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Responsive_Addons_For_Elementor\Traits\Singleton;

/**
 * Set up WPML Compatibiblity Class.
 */
class HF_WPML_Compatibility {
	use Singleton;

	/**
	 * Setup actions and filters.
	 *
	 * @since  1.3.0
	 */
	private function __construct() {
		add_filter( 'rael_hfe_get_settings_type_header', array( $this, 'get_wpml_object' ) );
		add_filter( 'rael_hfe_get_settings_type_footer', array( $this, 'get_wpml_object' ) );
		add_filter( 'rael_hfe_render_template_id', array( $this, 'get_wpml_object' ) );
	}

	/**
	 * Pass the final header and footer ID from the WPML's object filter to allow strings to be translated.
	 *
	 * @since  1.3.0
	 * @param  Int $id  Post ID of the template being rendered.
	 * @return Int $id  Post ID of the template being rendered, Passed through the `wpml_object_id` id.
	 */
	public function get_wpml_object( $id ) {
		$translated_id = apply_filters( 'wpml_object_id', $id );

		if ( defined( 'POLYLANG_BASENAME' ) ) {

			if ( null === $translated_id ) {

				// The current language is not defined yet or translation is not available.
				return $id;
			} else {

				// Return translated post ID.
				return $translated_id;
			}
		}

		if ( null === $translated_id ) {
			$translated_id = '';
		}

		return $translated_id;
	}
}

/**
 * Initiate the class.
 */
HF_WPML_Compatibility::instance();
