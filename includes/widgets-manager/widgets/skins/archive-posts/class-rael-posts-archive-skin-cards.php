<?php
/**
 * Skin Card
 *
 * @since      1.0.0
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Skins\Archive_Posts;

use Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Skins\RAEL_Skin_Cards;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Register warning controls under Content Tab.
 */
class RAEL_Posts_Archive_Skin_Cards extends RAEL_Skin_Cards {
	use RAEL_Posts_Archive_Skin_Base;

	/**
	 * Register archive post section layout
	 */
	protected function _register_controls_actions() {
		add_action( 'elementor/element/rael-theme-archive-posts/section_layout/before_section_end', array( $this, 'register_controls' ) );
		add_action( 'elementor/element/rael-theme-archive-posts/section_layout/after_section_end', array( $this, 'register_style_sections' ) );
		add_action( 'elementor/element/rael-theme-archive-posts/archive_cards_section_design_image/before_section_end', array( $this, 'register_additional_design_image_controls' ) );
	}
	/**
	 * Fetch  post Id
	 */
	public function get_id() {
		return 'rael_posts_archive_cards';
	}
	/**
	 * Fetch  post title
	 */
	public function get_title() {
		return __( 'Cards', 'responsive-addons-for-elementor' );
	}
	/**
	 * Get parent class
	 */
	public function get_container_class() {
		// Use parent class and parent css.
		return 'elementor-posts--skin-cards';
	}

	/**
	 * Remove `posts_per_page` control.
	 */
	protected function register_post_count_control() {}
}
