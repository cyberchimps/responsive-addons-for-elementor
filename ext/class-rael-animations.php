<?php
/**
 * Rael Animations Elementor extension.
 *
 * This extension adds animations functionality to Elementor columns and sections.
 *
 * @package Responsive_Addons_For_Elementor
 */

if ( ! defined( 'WPINC' ) ) {
	die; // If this file is called directly, abort.
}

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! class_exists( 'Rael_Animations' ) ) {

	/**
	 *  Adding controls to the advanced section
	 *
	 * Class Rael_Animations
	 */
	class Rael_Animations {

		/**
		 * Sections Data
		 *
		 * @var array
		 */
		public $sections_data = array();

		/**
		 * Columns Data
		 *
		 * @var array
		 */
		public $columns_data = array();

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * Constructor.
		 *
		 * Initializes the plugin by adding actions and filters.
		 */
		
		public function __construct() {
			add_action( 'elementor/element/after_section_end', array( $this, 'register_animations_controls' ), 10, 3 );
		}


		public function register_animations_controls( $section, $section_id, $args ) {
			// Only add controls if extension is active
			if ( ! Helper::is_extension_active( 'animations' ) ) {
				return;
			}
			if ( ! ( ( 'section' === $section->get_name() && 'section_background' === $section_id ) || ( 'container' === $section->get_name() && 'section_background' === $section_id ) ) ) {
				return;
			}

			$section->start_controls_section(
				'rael_animations_section',
				array(
					'label' => esc_html__( 'RAE Animations', 'responsive-addons-for-elementor' ),
					'tab'   => Controls_Manager::TAB_ADVANCED,
				)
			);
			$section->add_control(
				'rae_animations_scrolling_enable',
				array(
					'label'        => __( 'Enable Scroll Effects', 'responsive-addons-for-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
					'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);
			$section->add_control(
				'rae_animations_scroll_effects_type',
				[
					'label' => __( 'Scroll Effects Type', 'your-text-domain' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'vertical_scroll',
					'options' => [
						'vertical_scroll' => __( 'Vertical Scroll', 'your-text-domain' ),
						'horizontal_scroll' => __( 'Horizontal Scroll', 'your-text-domain' ),
					],
					'condition'    => array(
						'rae_animations_scrolling_enable' => 'yes',
					),
					'label_block' => true, 
				]
			);
			// Vertical scroll
			// $section->add_control(
			// 	'rae_animations_vertical_scroll',
			// 	array(
			// 		'label'        => __( 'Vertical Scroll', 'responsive-addons-for-elementor' ),
			// 		'type'         => Controls_Manager::POPOVER_TOGGLE,
			// 		'label_on'     => __( 'Edit', 'responsive-addons-for-elementor' ),
			// 		'label_off'    => __( 'Edit', 'responsive-addons-for-elementor' ),
			// 		'return_value' => 'yes',
			// 		'condition'    => array(
			// 			'rae_animations_scrolling_enable' => 'yes',
			// 		),
			// 	)
			// );
			// $section->start_popover();
			

			$section->add_control(
				'rae_animations_vertical_direction',
				array(
					'label'   => __( 'Direction', 'responsive-addons-for-elementor' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'up',
					'options' => array(
						'up'   => __( 'Up', 'responsive-addons-for-elementor' ),
						'down' => __( 'Down', 'responsive-addons-for-elementor' ),
					),
					'condition' => array(
						'rae_animations_scroll_effects_type' => 'vertical_scroll',
						'rae_animations_scrolling_enable' => 'yes',
					),
				)
			);

			$section->add_control(
				'rae_animations_vertical_speed',
				array(
					'label' => __( 'Speed', 'responsive-addons-for-elementor' ),
					'type'  => Controls_Manager::SLIDER,
					'default' => array(
						'size' => 4,
					),
					'range' => array(
						'px' => array(
							'min' => -50,
							'max' => 50,
							'step' => 1,
						),
					),
					'condition' => array(
						'rae_animations_scroll_effects_type' => 'vertical_scroll',
						'rae_animations_scrolling_enable' => 'yes',

					),
				)
			);

			$section->add_control(
				'rae_animations_vertical_viewport',
				[
					'label' => __( 'Viewport', 'responsive-addons-for-elementor' ),
					'type'  => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'sizes' => [
							'start' => 0,
							'end'   => 100,
						],
						'unit'  => '%',
					],
					'labels' => [
						__( 'Bottom', 'responsive-addons-for-elementor' ),
						__( 'Top', 'responsive-addons-for-elementor' ),
					],
					'scales' => 1,
					'handles' => 'range',
					'condition' => [
						'rae_animations_scroll_effects_type' => 'vertical_scroll',
						'rae_animations_scrolling_enable' => 'yes',
					],
				]
			);
			//$section->end_popover();

			// Horizontal Scroll

			// $section->add_control(
			// 	'rae_animations_horizontal_scroll',
			// 	array(
			// 		'label'        => __( 'Horizontal Scroll', 'responsive-addons-for-elementor' ),
			// 		'type'         => Controls_Manager::POPOVER_TOGGLE,
			// 		'label_on'     => __( 'Edit', 'responsive-addons-for-elementor' ),
			// 		'label_off'    => __( 'Edit', 'responsive-addons-for-elementor' ),
			// 		'return_value' => 'yes',
			// 		'condition'    => array(
			// 			'rae_animations_scroll_effects_type' => 'yes',
			// 		),
			// 	)
			// );
			//$section->start_popover();
			

			$section->add_control(
				'rae_animations_horizontal_direction',
				array(
					'label'   => __( 'Direction', 'responsive-addons-for-elementor' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'to_left',
					'options' => array(
						'up'   => __( 'To Left', 'responsive-addons-for-elementor' ),
						'down' => __( 'To right', 'responsive-addons-for-elementor' ),
					),
					'condition' => array(
						'rae_animations_scroll_effects_type' => 'horizontal_scroll',
						'rae_animations_scrolling_enable' => 'yes',
					),
				)
			);

			$section->add_control(
				'rae_animations_horizontal_speed',
				array(
					'label' => __( 'Speed', 'responsive-addons-for-elementor' ),
					'type'  => Controls_Manager::SLIDER,
					'default' => array(
						'size' => 4,
					),
					'range' => array(
						'px' => array(
							'min' => -50,
							'max' => 50,
							'step' => 1,
						),
					),
					'condition' => array(
						'rae_animations_scroll_effects_type' => 'horizontal_scroll',
						'rae_animations_scrolling_enable' => 'yes',
					),
				)
			);

			$section->add_control(
				'rae_animations_horizontal_viewport',
				[
					'label' => __( 'Viewport', 'responsive-addons-for-elementor' ),
					'type'  => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'sizes' => [
							'start' => 0,
							'end'   => 100,
						],
						'unit'  => '%',
					],
					'labels' => [
						__( 'Bottom', 'responsive-addons-for-elementor' ),
						__( 'Top', 'responsive-addons-for-elementor' ),
					],
					'scales' => 1,
					'handles' => 'range',
					'condition' => [
						'rae_animations_scroll_effects_type' => 'horizontal_scroll',
						'rae_animations_scrolling_enable' => 'yes',
					],
				]
			);
			//$section->end_popover();

			$section->end_controls_section();
		}



		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
	}

	new Rael_Animations();
}
