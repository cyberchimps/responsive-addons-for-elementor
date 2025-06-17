<?php
/**
 * File comment for RaelCrossSiteCopyPasteControls.php
 *
 * This file contains the definition of the RaelCrossSiteCopyPasteControls class.
 *
 * @package Responsive_Addons_For_Elementor
 */

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * RaelCrossSiteCopyPasteControls class.
 *
 * This class extends Tab_Base and manages the controls for RAE Cross Site Copy Paste settings.
 */
class RaelCrossSiteCopyPasteControls extends Tab_Base {

	/**
	 * Retrieve the ID.
	 *
	 * @since 1.8.3
	 * @access public
	 *
	 * @return string ID.
	 */
	public function get_id() {
		return 'responsive-addons-for-elementor';
	}

	/**
	 * Retrieve the title.
	 *
	 * @since 1.8.3
	 * @access public
	 *
	 * @return string Title.
	 */
	public function get_title() {
		return __( 'RAE Cross Site Copy Paste', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get Icon.
	 *
	 * @since 1.8.3
	 * @access public
	 *
	 * @return string Icon.
	 */
	public function get_icon() {
		return 'eicon-clone';
	}

	/**
	 * Register all the control settings
	 *
	 * @since 1.8.3
	 * @access protected
	 */
	protected function register_tab_controls() {
		$this->start_controls_section(
			'rael_cs_copy_paste_settings',
			array(
				'label' => esc_html__( 'Settings', 'responsive-addons-for-elementor' ),
				'tab'   => 'responsive-addons-for-elementor',
			)
		);
		$this->start_controls_tabs(
			'rael_tabs_copy_page_settings'
		);
		$this->start_controls_tab(
			'rael_tab_settings_editor',
			array(
				'label' => __( 'EDITOR', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_control(
			'rael_enable_copy_paste_btn',
			array(
				'label'     => __( 'Enable Cross Site Copy Paste', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off' => __( 'No', 'responsive-addons-for-elementor' ),
				'default'   => 'yes',
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}
}
