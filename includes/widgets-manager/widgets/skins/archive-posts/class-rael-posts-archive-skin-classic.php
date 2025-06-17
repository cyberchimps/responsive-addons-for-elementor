<?php
/**
 * Classic Skin for Archive Post
 *
 *  @since      1.0.0
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Skins\Archive_Posts;

use Elementor\Widget_Base;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Skins\RAEL_Skin_Classic;
/**
 * Class RAEL_Posts_Archive_Skin_Classic
 */
class RAEL_Posts_Archive_Skin_Classic extends RAEL_Skin_Classic {
	use RAEL_Posts_Archive_Skin_Base;

	/**
	 * Register control actions
	 */
	protected function _register_controls_actions() {
		add_action( 'elementor/element/rael-theme-archive-posts/section_layout/before_section_end', array( $this, 'register_controls' ) );
		add_action( 'elementor/element/rael-theme-archive-posts/section_layout/after_section_end', array( $this, 'register_style_sections' ) );
	}
	/**
	 * Get post ID
	 */
	public function get_id() {
		return 'rael_posts_archive_classic';
	}
	/**
	 * Get Post Title
	 */
	public function get_title() {
		return __( 'Classic', 'responsive-addons-for-elementor' );
	}
	/**
	 * Get Container class
	 */
	public function get_container_class() {
		// Use parent class and parent css.
		return 'elementor-posts--skin-classic';
	}

	/**
	 * Remove `posts_per_page` control.
	 */
	protected function register_post_count_control() {}

	/**
	 * Registers controls for the given widget.
	 *
	 * @param Widget_Base $widget The widget instance for which controls are registered.
	 * @return void
	 */
	public function register_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_columns_controls();
		$this->register_post_count_control();
		$this->register_thumbnail_controls();
		$this->register_title_controls();
		$this->register_excerpt_controls();
		$this->register_meta_data_controls();
		$this->register_read_more_controls();
		$this->register_link_controls();
		$this->register_data_position_controls();
	}
	/**
	 * Render post.
	 */
	protected function render_post() {
		$content_positions_key = array(
			empty( $this->get_instance_value( 'title_position' ) ) ? 0 : $this->get_instance_value( 'title_position' ),
			empty( $this->get_instance_value( 'meta_data_position' ) ) ? 0 : $this->get_instance_value( 'meta_data_position' ),
			empty( $this->get_instance_value( 'excerpt_position' ) ) ? 0 : $this->get_instance_value( 'excerpt_position' ),
			empty( $this->get_instance_value( 'read_more_position' ) ) ? 0 : $this->get_instance_value( 'read_more_position' ),
		);

		$content_positions_value = array(
			'render_title',
			'render_meta_data',
			'render_excerpt',
			'render_read_more',
		);

		$positions = array_combine( $content_positions_key, $content_positions_value );
		ksort( $positions );

		$this->render_post_header();
		$this->render_thumbnail();
		$this->render_text_header();

		foreach ( $positions as $key => $value ) {
			if ( 0 !== $key ) {
				$this->$value();
			}
		}

		$this->render_text_footer();
		$this->render_post_footer();
	}
}
