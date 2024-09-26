<?php
/**
 * Utility trait for missing dependencies.
 *
 * @package responsive-addons-for-elementor
 * @since 1.0.0
 */

namespace Responsive_Addons_For_Elementor\Traits;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Utility trait for missing dependencies.
 *
 * @since 1.0.0
 */
trait Missing_Dependency {

	/**
	 * Register warning controls under Content Tab.
	 *
	 * Use it inside a condition when the dependency plugin is not activated.
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin             Name of the missing plugin to be displayed in the warning message.
	 * @param string $plugin_search_term Search term for the missing plugin to be used in the link.
	 *
	 * @access protected
	 */
	protected function register_content_tab_missing_dep_warning_controls( $plugin, $plugin_search_term ) {
		$this->start_controls_section(
			'rael_missing_dependency_warning_section',
			array(
				'label' => __( 'Warning!', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_missing_plugin_warning',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				// translators: %1$s is the plugin search term, %2$s is the plugin name.
				'raw'             => sprintf( __( '<strong><a href="plugin-install.php?s=%1$s&tab=search&type=term" target="_blank">%2$s</a></strong> is not installed/activated on your site. Please install and activate it first.', 'responsive-addons-for-elementor' ), $plugin_search_term, $plugin ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			)
		);

		$this->end_controls_section();
	}
}
