<?php
/**
 * RAE Theme Post Featured Image
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Plugin;
use Elementor\Widget_Image as Elementor_Widget_Image;

/**
 * RAEL Theme Post Featured Image widget class.
 */
class Responsive_Addons_For_Elementor_Theme_Post_Featured_Image extends Elementor_Widget_Image {

	/**
	 * Get the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-theme-post-featured-image';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Featured Image', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-featured-image rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'rael', 'post image', 'featured', 'image', 'thumbnail' );
	}

	/**
	 * Get custom help url.
	 *
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/featured-image';
	}

	/**
	 * Register 'Post Excerpt' widget controls.
	 *
	 * @access protected
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->update_control(
			'image',
			array(
				'dynamic' => array(
					'default' => Plugin::instance()->dynamic_tags->tag_data_to_tag_text( null, 'rael-post-featured-image' ),
				),
			),
			array(
				'recursive' => true,
			)
		);
	}

	/**
	 * Retrieves the HTML wrapper class for the post featured image elementor widget.
	 *
	 * @return string The HTML wrapper class.
	 */
	protected function get_html_wrapper_class() {
		return parent::get_html_wrapper_class() . ' rael-theme-builder-post-featured-image elementor-widget-' . parent::get_name();
	}
}
