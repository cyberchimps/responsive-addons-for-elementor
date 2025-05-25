<?php
/**
 * RAE Theme Builder's Post Title Widget.
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Responsive_Addons_For_Elementor\WidgetsManager\ThemeBuilder\Widgets\Responsive_Addons_For_Elementor_Title_Widget_Base as Base;

/**
 * RAEL Theme Post Title widget class.
 */
class Responsive_Addons_For_Elementor_Theme_Post_Title extends Base {
	/**
	 * Get widget name
	 */
	public function get_name() {
		return 'rael-theme-post-title';
	}

	/**
	 * Get widget title
	 */
	public function get_title() {
		return esc_html__( 'Post Title', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon
	 */
	public function get_icon() {
		return 'eicon-post-title rael-badge';
	}

	/**
	 * Get widget categories
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get custom help url
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/post-title/';
	}

	/**
	 * Get keywords
	 */
	public function get_keywords() {
		return array( 'rael', 'title', 'heading', 'post', 'single' );
	}

	/**
	 * Get dynamic tag name
	 */
	public function get_dynamic_tag_name() {
		return 'rael-post-title';
	}

	/**
	 * Get html wrapper class for the widget
	 */
	protected function get_html_wrapper_class() {
		return parent::get_html_wrapper_class() . ' rael-theme-builder-post-title elementor-widget-' . parent::get_name();
	}
}
