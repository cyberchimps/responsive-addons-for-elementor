<?php
/**
 * Skin full content
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Class RAEL_Skin_Full_Content
 */
class RAEL_Skin_Full_Content extends RAEL_Skin_Classic {
	use RAEL_Skin_Content_Base;

	/**
	 * Get the ID of the skin.
	 *
	 * This method returns the ID of the skin, which is used to identify it.
	 *
	 * @since 1.0.0
	 *
	 * @return string The ID of the skin.
	 */
	public function get_id() {
		return 'rael_full_content';
	}
}
