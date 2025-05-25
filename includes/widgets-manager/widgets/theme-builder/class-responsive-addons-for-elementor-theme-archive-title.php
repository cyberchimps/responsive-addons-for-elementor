<?php
/**
 * RAEL Theme Builder's Archive Title Widget.
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Responsive_Addons_For_Elementor\WidgetsManager\ThemeBuilder\Widgets\Responsive_Addons_For_Elementor_Title_Widget_Base as Base;

/**
 * Widget class for the RAEL Archive Title in the Theme Builder.
 *
 * @package Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder
 */
class Responsive_Addons_For_Elementor_Theme_Archive_Title extends Base {
	/**
	 * Get the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-theme-archive-title';
	}

	/**
	 * Get the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Archive Title', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-archive-title rael-badge';
	}

	/**
	 * Get the widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get the custom help URL for the widget.
	 *
	 * @return string Help URL.
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/archive-title';
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'rael', 'title', 'heading', 'archive' );
	}

	/**
	 * Get the dynamic tag name for the widget.
	 *
	 * @return string Dynamic tag name.
	 */
	public function get_dynamic_tag_name() {
		return 'rael-archive-title';
	}

	/**
	 * Get the HTML wrapper class for the widget.
	 *
	 * @return string HTML wrapper class.
	 */
	protected function get_html_wrapper_class() {
		return parent::get_html_wrapper_class() . ' rael-theme-builder-archive-title elementor-widget-' . parent::get_name();
	}
}
