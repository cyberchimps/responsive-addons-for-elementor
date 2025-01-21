<?php
/**
 * Rael Sticky Elementor plugin.
 *
 * This plugin adds sticky functionality to Elementor columns and sections.
 *
 * @package Responsive_Addons_For_Elementor
 */

if ( ! defined( 'WPINC' ) ) {
	die; // If this file is called directly, abort.
}

if ( ! class_exists( 'Rael_Sticky_Elementor' ) ) {

	/**
	 *  Adding controls to the advanced section
	 *
	 * Class Rael_Sticky_Elementor
	 */
	class Rael_Sticky_Elementor {

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

			add_action( 'elementor/element/column/section_advanced/after_section_end', array( $this, 'after_section_column_layout' ), 10, 2 );

			add_action( 'elementor/frontend/column/before_render', array( $this, 'column_before_render' ) );
			add_action( 'elementor/frontend/element/before_render', array( $this, 'column_before_render' ) );

			add_action( 'elementor/element/after_section_end', array( $this, 'register_sticky_controls' ), 10, 3 );

			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
		}

		/**
		 * After column_layout callback
		 *
		 * @param  object $obj The Object.
		 * @param  array  $args The arguments.
		 * @return void
		 */
		public function after_section_column_layout( $obj, $args ) {

			$obj->start_controls_section(
				'rael_sticky_column_sticky_section',
				array(
					'label' => esc_html__( 'RAE Sticky', 'responsive-addons-for-elementor' ),
					'tab'   => Elementor\Controls_Manager::TAB_ADVANCED,
				)
			);

			$obj->add_control(
				'rael_sticky_column_sticky_enable',
				array(
					'label'        => esc_html__( 'Sticky Column', 'responsive-addons-for-elementor' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'responsive-addons-for-elementor' ),
					'label_off'    => esc_html__( 'No', 'responsive-addons-for-elementor' ),
					'return_value' => 'true',
					'default'      => 'false',
				)
			);

			$obj->add_control(
				'rael_sticky_column_sticky_top_spacing',
				array(
					'label'     => esc_html__( 'Top Spacing', 'responsive-addons-for-elementor' ),
					'type'      => Elementor\Controls_Manager::NUMBER,
					'default'   => 50,
					'min'       => 0,
					'max'       => 500,
					'step'      => 1,
					'condition' => array(
						'rael_sticky_column_sticky_enable' => 'true',
					),
				)
			);

			$obj->add_control(
				'rael_sticky_column_sticky_bottom_spacing',
				array(
					'label'     => esc_html__( 'Bottom Spacing', 'responsive-addons-for-elementor' ),
					'type'      => Elementor\Controls_Manager::NUMBER,
					'default'   => 50,
					'min'       => 0,
					'max'       => 500,
					'step'      => 1,
					'condition' => array(
						'rael_sticky_column_sticky_enable' => 'true',
					),
				)
			);

			$obj->add_control(
				'rael_sticky_column_sticky_enable_on',
				array(
					'label'       => __( 'Sticky On', 'responsive-addons-for-elementor' ),
					'type'        => Elementor\Controls_Manager::SELECT2,
					'multiple'    => true,
					'label_block' => 'true',
					'default'     => array(
						'desktop',
						'tablet',
					),
					'options'     => array(
						'desktop' => __( 'Desktop', 'responsive-addons-for-elementor' ),
						'tablet'  => __( 'Tablet', 'responsive-addons-for-elementor' ),
						'mobile'  => __( 'Mobile', 'responsive-addons-for-elementor' ),
					),
					'condition'   => array(
						'rael_sticky_column_sticky_enable' => 'true',
					),
					'render_type' => 'none',
				)
			);

			$obj->end_controls_section();
		}

		/**
		 * Before column render callback.
		 *
		 * @param object $element The elements.
		 *
		 * @return void
		 */
		public function column_before_render( $element ) {
			$data     = $element->get_data();
			$type     = isset( $data['elType'] ) ? $data['elType'] : 'column';
			$settings = $data['settings'];

			if ( 'column' !== $type && 'container' !== $type ) {
				return;
			}

			if ( isset( $settings['rael_sticky_column_sticky_enable'] ) ) {
				$column_settings = array(
					'id'            => $data['id'],
					'sticky'        => filter_var( $settings['rael_sticky_column_sticky_enable'], FILTER_VALIDATE_BOOLEAN ),
					'topSpacing'    => isset( $settings['rael_sticky_column_sticky_top_spacing'] ) ? $settings['rael_sticky_column_sticky_top_spacing'] : 50,
					'bottomSpacing' => isset( $settings['rael_sticky_column_sticky_bottom_spacing'] ) ? $settings['rael_sticky_column_sticky_bottom_spacing'] : 50,
					'stickyOn'      => isset( $settings['rael_sticky_column_sticky_enable_on'] ) ? $settings['rael_sticky_column_sticky_enable_on'] : array( 'desktop', 'tablet' ),
				);

				if ( filter_var( $settings['rael_sticky_column_sticky_enable'], FILTER_VALIDATE_BOOLEAN ) ) {

					$element->add_render_attribute(
						'_wrapper',
						array(
							'class' => 'rael-sticky-column-sticky',
							'data-rael-sticky-column-settings' => json_encode( $column_settings ), // phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
						)
					);
				}

				$this->columns_data[ $data['id'] ] = $column_settings;
			}
		}

		/**
		 * Add sticky controls to section settings.
		 *
		 * @param object $section Element instance.
		 * @param object $section_id Element instance.
		 * @param array  $args    Element arguments.
		 */
		public function register_sticky_controls( $section, $section_id, $args ) {
			
			if ( ! (( 'section' === $section->get_name() && 'section_background' === $section_id ) || ( 'container' === $section->get_name()  && 'section_background' === $section_id  )) ) 
			{
				return ;
			}

			$section->start_controls_section(
				'rael_sticky_section_sticky_settings',
				array(
					'label' => esc_html__( 'RAE Sticky Section', 'responsive-addons-for-elementor' ),
					'tab'   => Elementor\Controls_Manager::TAB_ADVANCED,
				)
			);

			$section->add_control(
				'rael_sticky_section_sticky',
				array(
					'label'              => esc_html__( 'Sticky Section', 'responsive-addons-for-elementor' ),
					'type'               => Elementor\Controls_Manager::SWITCHER,
					'label_on'           => __( 'On', 'responsive-addons-for-elementor' ),
					'label_off'          => __( 'Off', 'responsive-addons-for-elementor' ),
					'return_value'       => 'yes',
					'default'            => '',
					'frontend_available' => true,
				)
			);

			$section->add_control(
				'rael_sticky_section_sticky_visibility',
				array(
					'label'              => esc_html__( 'Sticky Section Visibility', 'responsive-addons-for-elementor' ),
					'type'               => Elementor\Controls_Manager::SELECT2,
					'multiple'           => true,
					'label_block'        => true,
					'default'            => array( 'desktop', 'tablet', 'mobile' ),
					'options'            => array(
						'desktop' => esc_html__( 'Desktop', 'responsive-addons-for-elementor' ),
						'tablet'  => esc_html__( 'Tablet', 'responsive-addons-for-elementor' ),
						'mobile'  => esc_html__( 'Mobile', 'responsive-addons-for-elementor' ),
					),
					'condition'          => array(
						'rael_sticky_section_sticky' => 'yes',
					),
					'frontend_available' => true,
				)
			);

			$section->add_control(
				'rael_sticky_section_sticky_z_index',
				array(
					'label'       => esc_html__( 'Z-index', 'responsive-addons-for-elementor' ),
					'type'        => Elementor\Controls_Manager::NUMBER,
					'placeholder' => 1100,
					'default'     => 1100,
					'min'         => 1,
					'max'         => 10000,
					'step'        => 1,
					'selectors'   => array(
						'{{WRAPPER}}.rael-sticky-section-sticky--stuck' => 'z-index: {{VALUE}};',
					),
					'condition'   => array(
						'rael_sticky_section_sticky' => 'yes',
					),
				)
			);

			$section->add_control(
				'rael_sticky_section_sticky_max_width',
				array(
					'label'     => esc_html__( 'Max Width (px)', 'responsive-addons-for-elementor' ),
					'type'      => Elementor\Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'min' => 500,
							'max' => 2000,
						),
					),
					'selectors' => array(
						'{{WRAPPER}}.rael-sticky-section-sticky--stuck' => 'max-width: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						'rael_sticky_section_sticky' => 'yes',
					),
				)
			);

			$section->add_responsive_control(
				'rael_sticky_section_sticky_style_heading',
				array(
					'label'     => esc_html__( 'Sticky Section Style', 'responsive-addons-for-elementor' ),
					'type'      => Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => array(
						'rael_sticky_section_sticky' => 'yes',
					),
				)
			);

			$section->add_responsive_control(
				'rael_sticky_section_sticky_margin',
				array(
					'label'              => esc_html__( 'Margin', 'responsive-addons-for-elementor' ),
					'type'               => Elementor\Controls_Manager::DIMENSIONS,
					'size_units'         => array( 'px', '%' ),
					'allowed_dimensions' => 'vertical',
					'placeholder'        => array(
						'top'    => '',
						'right'  => 'auto',
						'bottom' => '',
						'left'   => 'auto',
					),
					'selectors'          => array(
						'{{WRAPPER}}.rael-sticky-section-sticky--stuck' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
					),
					'condition'          => array(
						'rael_sticky_section_sticky' => 'yes',
					),
				)
			);

			$section->add_responsive_control(
				'rael_sticky_section_sticky_padding',
				array(
					'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
					'type'       => Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'selectors'  => array(
						'{{WRAPPER}}.rael-sticky-section-sticky--stuck' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition'  => array(
						'rael_sticky_section_sticky' => 'yes',
					),
				)
			);

			$section->add_group_control(
				Elementor\Group_Control_Background::get_type(),
				array(
					'name'      => 'rael_sticky_section_sticky_background',
					'selector'  => '{{WRAPPER}}.rael-sticky-section-sticky--stuck',
					'condition' => array(
						'rael_sticky_section_sticky' => 'yes',
					),
				)
			);

			$section->add_group_control(
				Elementor\Group_Control_Box_Shadow::get_type(),
				array(
					'name'      => 'rael_sticky_section_sticky_box_shadow',
					'selector'  => '{{WRAPPER}}.rael-sticky-section-sticky--stuck',
					'condition' => array(
						'rael_sticky_section_sticky' => 'yes',
					),
				)
			);

			$section->add_control(
				'rael_sticky_section_sticky_transition',
				array(
					'label'     => esc_html__( 'Transition Duration', 'responsive-addons-for-elementor' ),
					'type'      => Elementor\Controls_Manager::SLIDER,
					'default'   => array(
						'size' => 0.1,
					),
					'range'     => array(
						'px' => array(
							'max'  => 3,
							'step' => 0.1,
						),
					),
					'selectors' => array(
						'{{WRAPPER}}.rael-sticky-section-sticky--stuck.rael-sticky-transition-in, {{WRAPPER}}.rael-sticky-section-sticky--stuck.rael-sticky-transition-out' => 'transition: margin {{SIZE}}s, padding {{SIZE}}s, background {{SIZE}}s, box-shadow {{SIZE}}s',
					),
					'condition' => array(
						'rael_sticky_section_sticky' => 'yes',
					),
				)
			);

			$section->end_controls_section();
		}

		/**
		 * Enqueue scripts
		 *
		 * @return void
		 */
		public function enqueue_scripts() {

			wp_enqueue_script(
				'jsticky',
				RAEL_URL . 'assets/lib/jsticky/jquery.jsticky.min.js',
				array( 'jquery' ),
				RAEL_VER,
				true
			);

			wp_enqueue_script(
				'rael-sticky-frontend',
				RAEL_ASSETS_URL . 'js/rael-sticky-frontend.min.js',
				array( 'jquery', 'elementor-frontend' ),
				RAEL_VER,
				true
			);

			wp_localize_script(
				'rael-sticky-frontend',
				'RaelStickySettings',
				array(
					'elements_data' => $this->columns_data,
				)
			);

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

	new Rael_Sticky_Elementor();
}
