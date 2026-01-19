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
			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_rae_animations_scripts' ), 9 );
			add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'enqueue_rae_animations_scripts' ), 9 );

			add_action( 'elementor/element/container/section_layout/before_section_start', array( $this, 'register_animations_controls' ), 10, 2 );
			add_action( 'elementor/element/section/section_layout/before_section_start', array( $this, 'register_animations_controls' ), 10, 2 );
			add_action( 'elementor/element/column/section_layout/before_section_start', array( $this, 'register_animations_controls' ), 10, 2 );

			add_action( 'elementor/frontend/column/before_render', array( $this, 'render_effects' ), 10, 1 );
			add_action( 'elementor/frontend/section/before_render', array( $this, 'render_effects' ), 10, 1 );
			add_action( 'elementor/frontend/container/before_render', array( $this, 'render_effects' ), 10, 1 );

			add_action( 'wp_enqueue_scripts', array( $this, 'rael_enqueue_motion_css' ), 20 );
			add_action( 'elementor/editor/after_enqueue_styles',  array( $this, 'rael_enqueue_motion_css' ), 20 );

		}
		/**
		 * Enqueue scripts
		 *
		 * @return void
		 */
		public function enqueue_rae_animations_scripts() {
			if ( ! Helper::is_extension_active('animations') ) {
				return;
			}
			wp_enqueue_script(
				'rael-animations-frontend',
				RAEL_ASSETS_URL . 'js/rael-animations.min.js',
				array( 'jquery', 'elementor-frontend' ),
				RAEL_VER,
				true
			);

		}


		public function register_animations_controls( $section, $args ) {
			// Only add controls if extension is active
			if ( ! Helper::is_extension_active( 'animations' ) ) {
				return;
			}
			if ( ! ( ( 'section' === $section->get_name()) || ( 'container' === $section->get_name()  ) ) ) {
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
				),
				
			);
			$section->add_control(
				'rae_animations_scroll_effects_type',
				[
					'label' => __( 'Scroll Effects Type', 'responsive-addons-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'vertical_scroll',
					'options' => [
						'vertical_scroll' => __( 'Vertical Scroll', 'responsive-addons-for-elementor' ),
						'horizontal_scroll' => __( 'Horizontal Scroll', 'responsive-addons-for-elementor' ),
					],
					'condition'    => array(
						'rae_animations_scrolling_enable' => 'yes',
					),
				]
			);
			// Vertical scroll

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
							'min' => 1,
							'max' => 10,
							'step' => 0.1,
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
						__( 'Start', 'responsive-addons-for-elementor' ),
						__( 'End', 'responsive-addons-for-elementor' ),
					],
					'scales' => 1,
					'handles' => 'range',
					'condition' => [
						'rae_animations_scroll_effects_type' => 'vertical_scroll',
						'rae_animations_scrolling_enable' => 'yes',
					],
				]
			);
			// Horizontal
			$section->add_control(
				'rae_animations_horizontal_direction',
				array(
					'label'   => __( 'Direction', 'responsive-addons-for-elementor' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'to_left',
					'options' => array(
						'to_left'   => __( 'To Left', 'responsive-addons-for-elementor' ),
						'to_right' => __( 'To right', 'responsive-addons-for-elementor' ),
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
							'min' => 1,
							'max' => 10,
							'step' => 0.1,
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
						__( 'Start', 'responsive-addons-for-elementor' ),
						__( 'End', 'responsive-addons-for-elementor' ),
					],
					'scales' => 1,
					'handles' => 'range',
					'condition' => [
						'rae_animations_scroll_effects_type' => 'horizontal_scroll',
						'rae_animations_scrolling_enable' => 'yes',
					],
				]
			);

			// Transparency
			$section->add_control(
				'rae_animations_transparency_enable',
				array(
					'label'        => __( 'Transparency', 'responsive-addons-for-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
					'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
					'return_value' => 'yes',
					'default'      => '',
					'condition' => [
						'rae_animations_scrolling_enable' => 'yes',
					],
					'separator' => 'before',
				)
			);
			
			$section->add_control(
				'rae_animations_transparency_direction',
				array(
					'label'   => __( 'Direction', 'responsive-addons-for-elementor' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'fade_in',
					'options' => array(
						'fade_in'   => __( 'Fade In', 'responsive-addons-for-elementor' ),
						'fade_out'   => __( 'Fade Out', 'responsive-addons-for-elementor' ),
						'fade_out_in'   => __( 'Fade Out In', 'responsive-addons-for-elementor' ),
						'fade_in_out'   => __( 'Fade In Out', 'responsive-addons-for-elementor' ),
					),
					'condition' => array(
						'rae_animations_scrolling_enable' => 'yes',
						'rae_animations_transparency_enable' => 'yes',
					),
				)
			);

			$section->add_control(
				'rae_animations_transparency_level',
				array(
					'label' => __( 'Level', 'responsive-addons-for-elementor' ),
					'type'  => Controls_Manager::SLIDER,
					'default' => array(
						'size' => 4,
					),
					'range' => array(
						'px' => array(
							'min' => 1,
							'max' => 10,
							'step' => 0.1,
						),
					),
					'condition' => array(
						'rae_animations_scrolling_enable' => 'yes',
						'rae_animations_transparency_enable' => 'yes',
					),
				)
			);

			$section->add_control(
				'rae_animations_transparency_viewport',
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
						__( 'Start', 'responsive-addons-for-elementor' ),
						__( 'End', 'responsive-addons-for-elementor' ),
					],
					'scales' => 1,
					'handles' => 'range',
					'condition' => [
						'rae_animations_scrolling_enable' => 'yes',
						'rae_animations_transparency_enable' => 'yes',
					],
				]
			);

			// Blur
			$section->add_control(
				'rae_animations_blur_enable',
				array(
					'label'        => __( 'Blur', 'responsive-addons-for-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
					'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
					'return_value' => 'yes',
					'default'      => '',
					'condition' => [
						'rae_animations_scrolling_enable' => 'yes',
					],
					'separator' => 'before',
				)
			);
			
			$section->add_control(
				'rae_animations_blur_direction',
				array(
					'label'   => __( 'Direction', 'responsive-addons-for-elementor' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'fade_in',
					'options' => array(
						'fade_in'   => __( 'Fade In', 'responsive-addons-for-elementor' ),
						'fade_out'   => __( 'Fade Out', 'responsive-addons-for-elementor' ),
						'fade_out_in'   => __( 'Fade Out In', 'responsive-addons-for-elementor' ),
						'fade_in_out'   => __( 'Fade In Out', 'responsive-addons-for-elementor' ),
					),
					'condition' => array(
						'rae_animations_scrolling_enable' => 'yes',
						'rae_animations_blur_enable' => 'yes',
					),
				)
			);

			$section->add_control(
				'rae_animations_blur_level',
				array(
					'label' => __( 'Level', 'responsive-addons-for-elementor' ),
					'type'  => Controls_Manager::SLIDER,
					'default' => array(
						'size' => 4,
					),
					'range' => array(
						'px' => array(
							'min' => 1,
							'max' => 10,
							'step' => 0.1,
						),
					),
					'condition' => array(
						'rae_animations_scrolling_enable' => 'yes',
						'rae_animations_blur_enable' => 'yes',
					),
				)
			);

			$section->add_control(
				'rae_animations_blur_viewport',
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
						__( 'Start', 'responsive-addons-for-elementor' ),
						__( 'End', 'responsive-addons-for-elementor' ),
					],
					'scales' => 1,
					'handles' => 'range',
					'condition' => [
						'rae_animations_scrolling_enable' => 'yes',
						'rae_animations_blur_enable' => 'yes',
					],
				]
			);
			// Scale
			$section->add_control(
				'rae_animations_scale_enable',
				array(
					'label'        => __( 'Scale', 'responsive-addons-for-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
					'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
					'return_value' => 'yes',
					'default'      => '',
					'condition' => [
						'rae_animations_scrolling_enable' => 'yes',
					],
					'separator' => 'before',
				)
			);
			
			$section->add_control(
				'rae_animations_scale_direction',
				array(
					'label'   => __( 'Direction', 'responsive-addons-for-elementor' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'scale_up',
					'options' => array(
						'scale_up'   => __( 'Scale Up', 'responsive-addons-for-elementor' ),
						'scale_down'   => __( 'Scale Down', 'responsive-addons-for-elementor' ),
						'scale_down_up'   => __( 'Scale Down Up', 'responsive-addons-for-elementor' ),
						'scale_up_down'   => __( 'Scale Up Down', 'responsive-addons-for-elementor' ),
					),
					'condition' => array(
						'rae_animations_scrolling_enable' => 'yes',
						'rae_animations_scale_enable' => 'yes',
					),
				)
			);

			$section->add_control(
				'rae_animations_scale_speed',
				array(
					'label' => __( 'Speed', 'responsive-addons-for-elementor' ),
					'type'  => Controls_Manager::SLIDER,
					'default' => array(
						'size' => 4,
					),
					'range' => array(
						'px' => array(
							'min' => 1,
							'max' => 10,
							'step' => 0.1,
						),
					),
					'condition' => array(
						'rae_animations_scrolling_enable' => 'yes',
						'rae_animations_scale_enable' => 'yes',
					),
				)
			);

			$section->add_control(
				'rae_animations_scale_viewport',
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
						__( 'Start', 'responsive-addons-for-elementor' ),
						__( 'End', 'responsive-addons-for-elementor' ),
					],
					'scales' => 1,
					'handles' => 'range',
					'condition' => [
						'rae_animations_scrolling_enable' => 'yes',
						'rae_animations_scale_enable' => 'yes',
					],
				]
			);

			// Will override motion effect transform-origin.
			$section->add_responsive_control(
				'motion_fx_transform_x_anchor_point',
				[
					'label' => esc_html__( 'X Anchor Point', 'responsive-addons-for-elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'responsive-addons-for-elementor' ),
							'icon' => 'eicon-h-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'responsive-addons-for-elementor' ),
							'icon' => 'eicon-h-align-center',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'responsive-addons-for-elementor' ),
							'icon' => 'eicon-h-align-right',
						],
					],
					'condition' => [
							'rae_animations_scrolling_enable' => 'yes',
							'rae_animations_scale_enable' => 'yes',
						],
				]
			);

			// Will override motion effect transform-origin.
			$section->add_responsive_control(
				'motion_fx_transform_y_anchor_point',
				[
					'label' => esc_html__( 'Y Anchor Point', 'responsive-addons-for-elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'top' => [
							'title' => esc_html__( 'Top', 'responsive-addons-for-elementor' ),
							'icon' => 'eicon-v-align-top',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'responsive-addons-for-elementor' ),
							'icon' => 'eicon-v-align-middle',
						],
						'bottom' => [
							'title' => esc_html__( 'Bottom', 'responsive-addons-for-elementor' ),
							'icon' => 'eicon-v-align-bottom',
						],
					],
					'condition' => [
							'rae_animations_scrolling_enable' => 'yes',
							'rae_animations_scale_enable' => 'yes',
						],
				
				]
			);
			// Rotate
				$section->add_control(
					'rae_animations_rotate_enable',
					array(
						'label'        => __( 'Rotate', 'responsive-addons-for-elementor' ),
						'type'         => Controls_Manager::SWITCHER,
						'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
						'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
						'return_value' => 'yes',
						'default'      => '',
						'condition' => [
							'rae_animations_scrolling_enable' => 'yes',
						],
						'separator' => 'before',
					)
				);
			
			$section->add_control(
				'rae_animations_rotate_direction',
				array(
					'label'   => __( 'Direction', 'responsive-addons-for-elementor' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'to_left',
					'options' => array(
						'to_left'   => __( 'To Left', 'responsive-addons-for-elementor' ),
						'to_right'   => __( 'To Right', 'responsive-addons-for-elementor' ),
					),
					'condition' => array(
						'rae_animations_scrolling_enable' => 'yes',
						'rae_animations_rotate_enable' => 'yes',
					),
				)
			);

			$section->add_control(
				'rae_animations_rotate_speed',
				array(
					'label' => __( 'Speed', 'responsive-addons-for-elementor' ),
					'type'  => Controls_Manager::SLIDER,
					'default' => array(
						'size' => 4,
					),
					'range' => array(
						'px' => array(
							'min' => 1,
							'max' => 10,
							'step' => 0.1,
						),
					),
					'condition' => array(
						'rae_animations_scrolling_enable' => 'yes',
						'rae_animations_rotate_enable' => 'yes',
					),
				)
			);

			$section->add_control(
				'rae_animations_rotate_viewport',
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
						__( 'Start', 'responsive-addons-for-elementor' ),
						__( 'End', 'responsive-addons-for-elementor' ),
					],
					'scales' => 1,
					'handles' => 'range',
					'condition' => [
						'rae_animations_scrolling_enable' => 'yes',
						'rae_animations_rotate_enable' => 'yes',
					],
				]
			);
			$section->add_control(
				'rae_animations_apply_effects_on',
				[
					'label'       => __( 'Apply Effects On', 'responsive-addons-for-elementor' ),
					'type'        => Controls_Manager::SELECT2,
					'multiple'    => true,
					'options'     => [
						'desktop' => __( 'Desktop', 'responsive-addons-for-elementor' ),
						'tablet'  => __( 'Tablet Portrait', 'responsive-addons-for-elementor' ),
						'mobile'  => __( 'Mobile Portrait', 'responsive-addons-for-elementor' ),
					],
					'default'     => [ 'desktop', 'tablet', 'mobile' ],
					'label_block' => true,
				]
			);
			// Effects Relative To
			$section->add_control(
				'rae_animations_effects_relative_to',
				[
					'label' => __( 'Effects Relative To', 'responsive-addons-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'render_type' => 'none',
					'condition' => [
						'rae_animations_scrolling_enable' => 'yes',
					],
					'default' => 'viewport',
					'options' => [
						'default' => __( 'Default', 'responsive-addons-for-elementor' ),
						'viewport' => __( 'Viewport', 'responsive-addons-for-elementor' ),
						'page' => __( 'Entire Page', 'responsive-addons-for-elementor' ),
					],
					'frontend_available' => true,
					'separator' => 'before',
				]
			);
			// Entrance Animation
			$section->add_control(
				'rae_animations_entrance',
				[
					'label' => __( 'Entrance Animation', 'responsive-addons-for-elementor' ),
					'type' => Controls_Manager::ANIMATION,
					'frontend_available' => true,
					'default' => 'none',
					'separator' => 'before',
				]
			);

			$section->add_control(
				'rae_animations_entrance_duration',
				[
					'label' => __( 'Animation Duration', 'responsive-addons-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'default' => '1000',
					'options' => [
						'2000' => __( 'Slow', 'responsive-addons-for-elementor' ),
						'1000' => __( 'Normal', 'responsive-addons-for-elementor' ),
						'800' => __( 'Fast', 'responsive-addons-for-elementor' ),
					],
					'selectors' => [
						'{{WRAPPER}} .e-floating-bars' => '--e-floating-bars-coupon-animation-duration: {{VALUE}}ms',
					],

					'conditions' => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'rae_animations_entrance',
								'operator' => '!==',
								'value' => '',
							],
							[
								'name' => 'rae_animations_entrance',
								'operator' => '!==',
								'value' => 'none',
							],
						],
					],
				]
			);

			$section->add_control(
				'rae_animations_entrance_animation_delay',
				[
					'label' => __( 'Animation Delay', 'responsive-addons-for-elementor' ) . ' (ms)',
					'type' => Controls_Manager::NUMBER,
					'min' => 0,
					'step' => 100,
					'selectors' => [
						'{{WRAPPER}} .e-floating-bars' => '--e-floating-bars-coupon-animation-delay: {{SIZE}}ms;',
					],
					'render_type' => 'none',
					'frontend_available' => true,
					'conditions' => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'rae_animations_entrance',
								'operator' => '!==',
								'value' => '',
							],
							[
								'name' => 'rae_animations_entrance',
								'operator' => '!==',
								'value' => 'none',
							],
						],
					],
				]
			);
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

		/* Get Scroll animations data */
		protected function get_rael_scroll_effects_data( array $settings ) {
			$data = [];

			if ( empty( $settings['rae_animations_scrolling_enable'] ) || $settings['rae_animations_scrolling_enable'] !== 'yes' ) {
				return $data;
			}

			/*  Horizontal Scroll (Translate X) */
			if ( $settings['rae_animations_scroll_effects_type'] === 'horizontal_scroll' ) {
				$viewport = $settings['rae_animations_horizontal_viewport']['sizes'] ?? [];

				$data['translateX'] = [
					'type'      => 'horizontal',
					'direction' => $settings['rae_animations_horizontal_direction'] ?? 'to_left',
					'speed'     => (float) ( $settings['rae_animations_horizontal_speed']['size'] ?? 4 ),
					'start'     => (int) ( $viewport['start'] ?? 0 ),
					'end'       => (int) ( $viewport['end'] ?? 100 ),
				];
			}

			/* Vertical Scroll (Translate Y) */
			if ( $settings['rae_animations_scroll_effects_type'] === 'vertical_scroll' ) {
				$viewport = $settings['rae_animations_vertical_viewport']['sizes'] ?? [];

				$data['translateY'] = [
					'type'      => 'vertical',
					'direction' => $settings['rae_animations_vertical_direction'] ?? 'up',
					'speed'     => (float) ( $settings['rae_animations_vertical_speed']['size'] ?? 4 ),
					'start'     => (int) ( $viewport['start'] ?? 0 ),
					'end'       => (int) ( $viewport['end'] ?? 100 ),
				];
			}

			/* Transparency (Opacity) */
			if ( ! empty( $settings['rae_animations_transparency_enable'] ) && $settings['rae_animations_transparency_enable'] === 'yes' ) {
				$viewport = $settings['rae_animations_transparency_viewport']['sizes'] ?? [];

				$data['opacity'] = [
					'direction' => $settings['rae_animations_transparency_direction'] ?? 'fade_in',
					'level'     => (float) ( $settings['rae_animations_transparency_level']['size'] ?? 4 ),
					'start'     => (int) ( $viewport['start'] ?? 0 ),
					'end'       => (int) ( $viewport['end'] ?? 100 ),
				];
			}

			/* Blur */
			if ( ! empty( $settings['rae_animations_blur_enable'] ) && $settings['rae_animations_blur_enable'] === 'yes' ) {
				$viewport = $settings['rae_animations_blur_viewport']['sizes'] ?? [];

				$data['blur'] = [
					'direction' => $settings['rae_animations_blur_direction'] ?? 'fade_in',
					'level'     => (float) ( $settings['rae_animations_blur_level']['size'] ?? 4 ),
					'start'     => (int) ( $viewport['start'] ?? 0 ),
					'end'       => (int) ( $viewport['end'] ?? 100 ),
				];
			}

			/* Scale */
			if ( ! empty( $settings['rae_animations_scale_enable'] ) && $settings['rae_animations_scale_enable'] === 'yes' ) {
				$viewport = $settings['rae_animations_scale_viewport']['sizes'] ?? [];

				$data['scale'] = [
					'direction' => $settings['rae_animations_scale_direction'] ?? 'scale_up',
					'speed'     => (float) ( $settings['rae_animations_scale_speed']['size'] ?? 4 ),
					'origin_x'  => $settings['motion_fx_transform_x_anchor_point'] ?? 'center',
					'origin_y'  => $settings['motion_fx_transform_y_anchor_point'] ?? 'center',
					'start'     => (int) ( $viewport['start'] ?? 0 ),
					'end'       => (int) ( $viewport['end'] ?? 100 ),
				];
			}

			/* Rotate */
			if ( ! empty( $settings['rae_animations_rotate_enable'] ) && $settings['rae_animations_rotate_enable'] === 'yes' ) {
				$viewport = $settings['rae_animations_rotate_viewport']['sizes'] ?? [];

				$data['rotate'] = [
					'direction' => $settings['rae_animations_rotate_direction'] ?? 'to_left',
					'speed'     => (float) ( $settings['rae_animations_rotate_speed']['size'] ?? 4 ),
					'start'     => (int) ( $viewport['start'] ?? 0 ),
					'end'       => (int) ( $viewport['end'] ?? 100 ),
				];
			}

			return $data;
		}


		public function render_effects( $element ) {
			$this->inject_rael_effects_attributes( $element );
		}

		protected function inject_rael_effects_attributes( $element ) {
    $settings = $element->get_settings_for_display();
    $effects  = $this->get_rael_scroll_effects_data( $settings );


	// Device logic (Apply Effects On)
	 
	if ( ! empty( $settings['rae_animations_apply_effects_on'] ) && is_array( $settings['rae_animations_apply_effects_on'] ) ) {

		$devices = $settings['rae_animations_apply_effects_on'];

		if ( ! in_array( 'desktop', $devices, true ) ) {
			$element->add_render_attribute( '_wrapper', 'class', 'rael-hide-animation-desktop' );
		}

		if ( ! in_array( 'tablet', $devices, true ) ) {
			$element->add_render_attribute( '_wrapper', 'class', 'rael-hide-animation-tablet' );
		}

		if ( ! in_array( 'mobile', $devices, true ) ) {
			$element->add_render_attribute( '_wrapper', 'class', 'rael-hide-animation-mobile' );
		}
	}

	// Scroll effects
    if ( ! empty( $effects ) ) {
        $element->add_render_attribute( '_wrapper', [
            'class' => 'rael-scroll-effects',
            'data-rael-scroll-effects' => wp_json_encode( [
                'effects'    => $effects,
                'relativeTo' => $settings['rae_animations_effects_relative_to'] ?? 'viewport',
            ] ),
        ] );
    }
    
    // Add entrance animation
    if ( ! empty( $settings['rae_animations_entrance'] ) && $settings['rae_animations_entrance'] !== 'none' ) {
        $element->add_render_attribute( '_wrapper', [
            'class' => 'rael-entrance',
            'data-rae-entrance' => $settings['rae_animations_entrance'],
        ] );
        
        // Add animation duration
        $duration = $settings['rae_animations_entrance_duration'] ?? '1000';
        $element->add_render_attribute( '_wrapper', [
            'data-rae-animation-duration' => $duration,
        ] );
        
        // Add duration class based on value
        $duration_classes = [
            '2000' => 'rae-duration-slow',
            '1000' => 'rae-duration-normal',
            '800'  => 'rae-duration-fast'
        ];
        
        if ( isset( $duration_classes[ $duration ] ) ) {
            $element->add_render_attribute( '_wrapper', [
                'class' => $duration_classes[ $duration ],
            ] );
        }
        
        // Add animation delay
        if ( ! empty( $settings['rae_animations_entrance_animation_delay'] ) ) {
            $element->add_render_attribute( '_wrapper', [
                'data-rae-animation-delay' => $settings['rae_animations_entrance_animation_delay'],
            ] );
        }
    }
}

		public function rael_enqueue_motion_css() {

			$css = '
			.rael-scroll-effects {

			--translateX: 0px;
				--translateY: 0px;
				--rotateZ: 0deg;
				--scale: 1;
				--blur: 0px;
				--opacity: 1;

				transform:
					translateX(var(--translateX))
					translateY(var(--translateY))
					rotateZ(var(--rotateZ))
					scale(var(--scale));

				filter: blur(var(--blur));
				opacity: var(--opacity);
					
					will-change: transform, filter, opacity;
			}
			/* Desktop */
			@media (min-width: 1025px) {
				.rael-hide-animation-desktop {
					transform: none !important;
				}
			}

			/* Tablet */
			@media (min-width: 768px) and (max-width: 1024px) {
				.rael-hide-animation-tablet {
					transform: none !important;
				}
			}

			/* Mobile */
			@media (max-width: 767px) {
				.rael-hide-animation-mobile {
					transform: none !important;
				}
			}	
			';

			wp_add_inline_style('elementor-frontend', $css );
		}

	}

	new Rael_Animations();
}
