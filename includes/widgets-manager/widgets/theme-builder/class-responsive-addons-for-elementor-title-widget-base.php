<?php
/**
 * File containing abstract class Responsive_Addons_For_Elementor_Title_Widget_Base.
 *
 * @package Responsive_Addons_For_Elementor\WidgetsManager\ThemeBuilder\Widgets
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\ThemeBuilder\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Plugin;
use Elementor\Widget_Heading as Elementor_Widget_Heading;

/**
 * Abstract base class for title widgets in Responsive Addons for Elementor.
 */
abstract class Responsive_Addons_For_Elementor_Title_Widget_Base extends Elementor_Widget_Heading {
	/**
	 * Abstract method to get the dynamic tag name.
	 *
	 * @return string The dynamic tag name.
	 */
	abstract protected function get_dynamic_tag_name();

	/**
	 * Checks whether to show the page title or not.
	 *
	 * @return bool Whether to show the page title or not.
	 */
	protected function should_show_page_title() {
		$current_doc = Plugin::instance()->documents->get( get_the_ID() );

		if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Registers controls for the title widget.
	 */
	protected function register_controls() {
		parent::register_controls();

		$dynamic_tag_name = $this->get_dynamic_tag_name();

		$this->update_control(
			'title',
			array(
				'dynamic' => array(
					'default' => Plugin::instance()->dynamic_tags->tag_data_to_tag_text( null, $dynamic_tag_name ),
				),
			),
			array(
				'recursive' => true,
			)
		);

		$this->update_control(
			'header_size',
			array(
				'default' => 'h1',
			)
		);

		$this->update_control(
			'size',
			array(
				'condition' => [],
			)
		);
	}

	/**
	 * Renders the title widget.
	 */
	protected function render() {
		if ( $this->should_show_page_title() ) {
			return parent::render();
		}
	}
}
