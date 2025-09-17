<?php
/**
 * Widget title
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\ThemeBuilder\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Plugin;
use Elementor\Widget_Heading as Elementor_Widget_Heading;

/**
 * Class RAEL_Theme_Title_Widget_Base
 */
abstract class RAEL_Theme_Title_Widget_Base extends Elementor_Widget_Heading {
	/**
	 * Protected function get_dynamic_tag_name
	 */
	abstract protected function get_dynamic_tag_name();
	/**
	 * This function shows page title
	 */
	protected function should_show_page_title() {
		$current_doc = Plugin::instance()->documents->get( get_the_ID() );

		if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
			return false;
		}

		return true;
	}
	/**
	 * Register controls.
	 */
	protected function register_controls() {
		parent::register_controls();

		$dynamic_tag_name = $this->get_dynamic_tag_name();
		if ($this->get_controls('title')) {
			$this->update_control(
				'title',
				array(
					'dynamic' => array(
						'default' => Plugin::instance()->dynamic_tags->tag_data_to_tag_text(null, $dynamic_tag_name),
					),
				),
				array(
					'recursive' => true,
				)
			);
		}
		if ($this->get_controls('header_size')) {
			$this->update_control(
				'header_size',
				array(
					'default' => 'h1',
				)
			);
		}
	}
	/**
	 * Render function
	 */
	protected function render() {
		if ( $this->should_show_page_title() ) {
			return parent::render();
		}
	}
}
