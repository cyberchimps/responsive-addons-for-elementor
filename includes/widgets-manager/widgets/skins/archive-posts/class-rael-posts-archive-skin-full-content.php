<?php
/**
 * Full content  Skin for Archive Post
 *
 *  @since      1.0.0
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Skins\Archive_Posts;

use Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Skins\RAEL_Skin_Full_Content;
use Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Skins\RAEL_Skin_Content_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Class RAEL_Posts_Archive_Skin_Full_Content
 */
class RAEL_Posts_Archive_Skin_Full_Content extends RAEL_Skin_Full_Content {
	use RAEL_Skin_Content_Base;
	use RAEL_Posts_Archive_Skin_Base;

	/**
	 *  Get post ID
	 */
	public function get_id() {
		return 'rael_posts_archive_full_content';
	}

	/**
	 * Remove `posts_per_page` control.
	 */
	protected function register_post_count_control() {}
}
