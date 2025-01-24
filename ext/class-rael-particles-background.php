<?php
/**
 * RAEL Particle Background
 *
 * Adds particle background functionality to Elementor sections and columns.
 *
 * @package Responsive Addons for Elementor
 */

if ( ! defined( 'WPINC' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Plugin;

if ( ! class_exists( 'RAEL_Particles_Background' ) ) {
	/**
	 * RAEL Particle Background class
	 *
	 * Handles the particle background functionality for Elementor sections and columns.
	 */
	class RAEL_Particles_Background {
		/**
		 * Instance of the class.
		 *
		 * @var null|array  $instanc Instance of the class.
		 */
		private static $instance = null;

		/**
		 * Constructor
		 *
		 * Initializes the particle background functionality.
		 */
		private function __construct() {
			add_action( 'wp_footer', array( $this, 'enqueue_scripts' ) );
			add_action( 'elementor/element/after_section_end', array( $this, 'register_controls' ), 10, 3 );

			add_action( 'elementor/section/print_template', array( $this, 'print_template' ), 10, 2 );
			add_action( 'elementor/column/print_template', array( $this, 'print_template' ), 10, 2 );
			add_action( 'elementor/container/print_template', array( $this, 'print_template' ), 10, 2 );

			add_action( 'elementor/frontend/column/before_render', array( $this, 'before_render' ), 10, 1 );
			add_action( 'elementor/frontend/section/before_render', array( $this, 'before_render' ), 10, 1 );
			add_action( 'elementor/frontend/container/before_render', array( $this, 'before_render' ), 10, 1 );
		}

		/**
		 * Instance
		 *
		 * Returns the instance of the class.
		 *
		 * @return RAEL_Particles_Background
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
		/**
		 * Enqueue Scripts
		 *
		 * Enqueues the necessary scripts for the particle background.
		 */
		public function enqueue_scripts() {
			$is_elementor = false;

			if ( false !== get_the_ID() ) {
				$elem_document = Plugin::$instance->documents->get( get_the_ID() );

				if ( ! is_bool( $elem_document ) ) {
					$is_elementor = $elem_document->is_built_with_elementor();
				}
			}

			if ( ( true === \Elementor\Plugin::$instance->frontend->has_elementor_in_page() ) || ( true === $is_elementor ) || ( function_exists( 'elementor_location_exits' ) && ( elementor_location_exits( 'archive', true ) || elementor_location_exits( 'single', true ) ) ) || ( function_exists( 'rea_theme_template_render_at_location' ) && ( rea_theme_template_render_at_location( 'archive' ) || rea_theme_template_render_at_location( 'single' ) ) ) ) {
				wp_add_inline_script(
					'rael-particles',
					'window.scope_array = [];
                        window.backend = 0;
                        jQuery.cachedScript = function( url, options ) {
                            // Allow user to set any option except for dataType, cache, and url.
                            options = jQuery.extend( options || {}, {
                                dataType: "script",
                                cache: true,
                                url: url
                            });
                            // Return the jqXHR object so we can chain callbacks.
                            return jQuery.ajax( options );
                        };
                        
                        jQuery( window ).on( "elementor/frontend/init", function() {
                            elementorFrontend.hooks.addAction( "frontend/element_ready/global", function( $scope, $ ){
                                if ( "undefined" === typeof $scope ) {
                                        return;
                                }

                                if ( $scope.hasClass( "rael-particle-yes" ) ) {
                                    window.scope_array.push( $scope );
                                    $scope.find(".rael-particle-wrapper").addClass("js-is-enabled");
                                } else {
                                    return;
                                }

                                if (elementorFrontend.isEditMode() && $scope.find(".rael-particle-wrapper").hasClass("js-is-enabled") && window.backend == 0 ){		
                                    var lib_url = rael_particles.particles_lib;
                                    
                                    jQuery.cachedScript( lib_url );
                                    window.backend = 1;
                                }else if(elementorFrontend.isEditMode()){
                                    var lib_url = rael_particles.particles_lib;
                                    jQuery.cachedScript( lib_url ).done(function(){
                                        var flag = true;
                                    });
                                }else{
									var lib_url = rael_particles.particles_lib;
									jQuery.cachedScript( lib_url );
								}
                            });
                        });
                             jQuery( document ).on( "ready elementor/popup/show", () => {

                                if ( jQuery.find( ".rael-particle-yes" ).length < 1 ) {
                                    return;
                                }
                                var lib_url = rael_particles.particles_lib;
                                jQuery.cachedScript = function( url, options ) {
                                    // Allow user to set any option except for dataType, cache, and url.
                                    options = jQuery.extend( options || {}, {
                                        dataType: "script",
                                        cache: true,
                                        url: url
                                    });
                                    // Return the jqXHR object so we can chain callbacks.
                                    return jQuery.ajax( options );
                                };
                                jQuery.cachedScript( lib_url );
                            });	'
				);
			}
		}

		/**
		 * Register Controls
		 *
		 * Registers the particle background controls for Elementor sections and columns.
		 *
		 * @param object $element Elementor element.
		 * @param string $section_id Section ID.
		 * @param array  $args Additional arguments.
		 */
		public function register_controls( $element, $section_id, $args ) {
			if ( ( 'section' === $element->get_name() && 'section_background' === $section_id ) || ( 'column' === $element->get_name() && 'section_style' === $section_id )  || ( 'container' === $element->get_name()  && 'section_background' === $section_id  ) ) {

				$element->start_controls_section(
					'rael_particles',
					array(
						'tab'   => Controls_Manager::TAB_STYLE,
						/* translators: %s admin link */
						'label' => sprintf( __( '%1s - Particle Backgrounds', 'responsive-addons-for-elementor' ), RAEL_PLUGIN_SHORT_NAME ),
					)
				);

				$element->add_control(
					'rael_enable_particles',
					array(
						'type'         => Controls_Manager::SWITCHER,
						'label'        => __( 'Enable Particle Background', 'responsive-addons-for-elementor' ),
						'default'      => '',
						'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
						'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
						'return_value' => 'yes',
						'prefix_class' => 'rael-particle-',
						'render_type'  => 'template',
					)
				);

				$element->add_control(
					'rael_particles_styles',
					array(
						'label'     => __( 'Style', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::SELECT,
						'default'   => 'nasa',
						'options'   => array(
							'default'    => __( 'Polygon', 'responsive-addons-for-elementor' ),
							'nasa'       => __( 'NASA', 'responsive-addons-for-elementor' ),
							'snow'       => __( 'Snow', 'responsive-addons-for-elementor' ),
							'snowflakes' => __( 'Snowflakes', 'responsive-addons-for-elementor' ),
							'christmas'  => __( 'Christmas', 'responsive-addons-for-elementor' ),
							'halloween'  => __( 'Halloween', 'responsive-addons-for-elementor' ),
							'custom'     => __( 'Custom', 'responsive-addons-for-elementor' ),
						),
						'condition' => array(
							'rael_enable_particles' => 'yes',
						),
					)
				);

				$element->add_control(
					'rael_particles_help_doc_1',
					array(
						'type'            => Controls_Manager::RAW_HTML,
						/* translators: %s admin link */
						'raw'             => __( 'Add custom JSON for the Particle Background below. To generate a completely customized background style follow steps below - ', 'responsive-addons-for-elementor' ),
						'content_classes' => '',
						'condition'       => array(
							'rael_enable_particles' => 'yes',
							'rael_particles_styles' => 'custom',
						),
					)
				);

				$element->add_control(
					'rael_particles_help_doc_2',
					array(
						'type'            => Controls_Manager::RAW_HTML,
						/* translators: %s admin link */
						'raw'             => sprintf( __( '1. Visit a link %1$s here %2$s and choose required attributes for particle </br></br> 2. Once a custom style is created, download JSON from "Download current config (json)" link </br></br> 3. Copy JSON code from the downloaded file and paste it below', 'responsive-addons-for-elementor' ), '<a href="https://vincentgarreau.com/particles.js/" target="_blank" rel="noopener">', '</a>' ),
						'content_classes' => '',
						'condition'       => array(
							'rael_enable_particles' => 'yes',
							'rael_particles_styles' => 'custom',
						),
					)
				);

				$element->add_control(
					'rael_particle_json',
					array(
						'type'        => Controls_Manager::CODE,
						'default'     => '',
						'render_type' => 'template',
						'condition'   => array(
							'rael_enable_particles' => 'yes',
							'rael_particles_styles' => 'custom',
						),
					)
				);

				$element->add_control(
					'rael_particles_color',
					array(
						'label'       => __( 'Particle Color', 'responsive-addons-for-elementor' ),
						'type'        => Controls_Manager::COLOR,
						'alpha'       => false,
						'condition'   => array(
							'rael_enable_particles'  => 'yes',
							'rael_particles_styles!' => array( 'custom', 'christmas', 'halloween' ),
						),
						'render_type' => 'template',
					)
				);

				$element->add_control(
					'rael_particles_opacity',
					array(
						'label'       => __( 'Opacity', 'responsive-addons-for-elementor' ),
						'type'        => Controls_Manager::SLIDER,
						'range'       => array(
							'px' => array(
								'min'  => 0,
								'max'  => 1,
								'step' => 0.1,
							),
						),
						'condition'   => array(
							'rael_enable_particles'  => 'yes',
							'rael_particles_styles!' => 'custom',
						),
						'render_type' => 'template',
					)
				);

				$element->add_control(
					'rael_particles_direction',
					array(
						'label'     => __( 'Flow Direction', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::SELECT,
						'default'   => 'bottom',
						'options'   => array(
							'top'          => __( 'Top', 'responsive-addons-for-elementor' ),
							'bottom'       => __( 'Bottom', 'responsive-addons-for-elementor' ),
							'left'         => __( 'Left', 'responsive-addons-for-elementor' ),
							'right'        => __( 'Right', 'responsive-addons-for-elementor' ),
							'top-left'     => __( 'Top Left', 'responsive-addons-for-elementor' ),
							'top-right'    => __( 'Top Right', 'responsive-addons-for-elementor' ),
							'bottom-left'  => __( 'Bottom Left', 'responsive-addons-for-elementor' ),
							'bottom-right' => __( 'Bottom Right', 'responsive-addons-for-elementor' ),
						),
						'condition' => array(
							'rael_enable_particles' => 'yes',
							'rael_particles_styles' => array( 'snow', 'snowflakes', 'christmas' ),
						),
					)
				);

				$element->add_control(
					'rael_enable_advanced',
					array(
						'type'         => Controls_Manager::SWITCHER,
						'label'        => __( 'Advanced Settings', 'responsive-addons-for-elementor' ),
						'default'      => 'no',
						'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
						'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
						'return_value' => 'yes',
						'prefix_class' => 'rael-particle-adv-',
						'render_type'  => 'template',
						'condition'    => array(
							'rael_enable_particles'  => 'yes',
							'rael_particles_styles!' => 'custom',
						),
					)
				);

				$element->add_control(
					'rael_particles_number',
					array(
						'label'       => __( 'Number of Particles', 'responsive-addons-for-elementor' ),
						'type'        => Controls_Manager::SLIDER,
						'range'       => array(
							'px' => array(
								'min' => 1,
								'max' => 500,
							),
						),
						'condition'   => array(
							'rael_enable_particles'  => 'yes',
							'rael_particles_styles!' => 'custom',
							'rael_enable_advanced'   => 'yes',
						),
						'render_type' => 'template',
					)
				);

				$element->add_control(
					'rael_particles_size',
					array(
						'label'       => __( 'Particle Size', 'responsive-addons-for-elementor' ),
						'type'        => Controls_Manager::SLIDER,
						'range'       => array(
							'px' => array(
								'min' => 1,
								'max' => 200,
							),
						),
						'condition'   => array(
							'rael_enable_particles'  => 'yes',
							'rael_particles_styles!' => 'custom',
							'rael_enable_advanced'   => 'yes',
						),
						'render_type' => 'template',
					)
				);

				$element->add_control(
					'rael_particles_speed',
					array(
						'label'       => __( 'Move Speed', 'responsive-addons-for-elementor' ),
						'type'        => Controls_Manager::SLIDER,
						'range'       => array(
							'px' => array(
								'min' => 1,
								'max' => 10,
							),
						),
						'condition'   => array(
							'rael_enable_particles'  => 'yes',
							'rael_particles_styles!' => 'custom',
							'rael_enable_advanced'   => 'yes',
						),
						'render_type' => 'template',
					)
				);

				$element->add_control(
					'rael_enable_interactive',
					array(
						'type'         => Controls_Manager::SWITCHER,
						'label'        => __( 'Enable Hover Effect', 'responsive-addons-for-elementor' ),
						'default'      => 'no',
						'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
						'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
						'return_value' => 'yes',
						'condition'    => array(
							'rael_enable_particles'  => 'yes',
							'rael_particles_styles!' => array( 'custom' ),
							'rael_enable_advanced'   => 'yes',
						),
						'render_type'  => 'template',
					)
				);

				$element->add_control(
					'rael_particles_hover_effect_help_doc',
					array(
						'type'            => Controls_Manager::RAW_HTML,
						/* translators: %s admin link */
						'raw'             => __( 'Particle hover effect will not work in the following scenarios - </br></br> 1. In the Elementor backend editor</br></br> 2. Content/Spacer added in the section/column occupies the entire space and leaves it inaccessible. Adding padding to the section/column can resolve this.', 'responsive-addons-for-elementor' ),
						'content_classes' => '',
						'condition'       => array(
							'rael_enable_particles'   => 'yes',
							'rael_particles_styles!'  => array( 'custom' ),
							'rael_enable_advanced'    => 'yes',
							'rael_enable_interactive' => 'yes',
						),
					)
				);

				$element->end_controls_section();
			}
		}

		/**
		 * Render Particles Background output on the frontend.
		 *
		 * Written in PHP and used to generate the final HTML.
		 *
		 * @since 1.8.0
		 *
		 * @access public
		 * @param object $element for current element.
		 */
		public function before_render( $element ) {

			if ( 'section' !== $element->get_name() && 'column' !== $element->get_name() && 'container' !== $element->get_name() ) {
				return;
			}

			$settings  = $element->get_settings();
			$node_id   = $element->get_id();
			$is_editor = \Elementor\Plugin::instance()->editor->is_edit_mode();

			if ( 'yes' === $settings['rael_enable_particles'] ) {
				$element->add_render_attribute( '_wrapper', 'data-rael-partstyle', $settings['rael_particles_styles'] );
				$element->add_render_attribute( '_wrapper', 'data-rael-partcolor', $settings['rael_particles_color'] );
				$element->add_render_attribute( '_wrapper', 'data-rael-partopacity', $settings['rael_particles_opacity']['size'] );
				$element->add_render_attribute( '_wrapper', 'data-rael-partdirection', $settings['rael_particles_direction'] );

				if ( 'yes' === $settings['rael_enable_advanced'] ) {
					$element->add_render_attribute( '_wrapper', 'data-rael-partnum', $settings['rael_particles_number']['size'] );
					$element->add_render_attribute( '_wrapper', 'data-rael-partsize', $settings['rael_particles_size']['size'] );
					$element->add_render_attribute( '_wrapper', 'data-rael-partspeed', $settings['rael_particles_speed']['size'] );
					if ( $is_editor ) {
						$element->add_render_attribute( '_wrapper', 'data-rael-interactive', 'no' );
					} else {
						$element->add_render_attribute( '_wrapper', 'data-rael-interactive', $settings['rael_enable_interactive'] );
					}
				}

				if ( 'custom' === $settings['rael_particles_styles'] ) {
					$element->add_render_attribute( '_wrapper', 'data-rael-partdata', $settings['rael_particle_json'] );
				}
			}
		}

		/**
		 * Render Particles Background output in the editor.
		 *
		 * Written as a Backbone JavaScript template and used to generate the live preview.
		 *
		 * @since 1.8.0
		 * @access public
		 *
		 * @param object $template for current template.
		 * @param object $widget for current widget.
		 */
		public function print_template( $template, $widget ) {
			if ( 'section' !== $widget->get_name() && 'column' !== $widget->get_name() && 'container' !== $widget->get_name() ) {
				return $template;
			}
			$old_template = $template;
			ob_start();
			?>
			<# if( 'yes' == settings.rael_enable_particles ) {

				view.addRenderAttribute( 'particle_data', 'id', 'rael-particle-' + view.getID() );
				view.addRenderAttribute( 'particle_data', 'class', 'rael-particle-wrapper' );
				view.addRenderAttribute( 'particle_data', 'data-rael-partstyle', settings.rael_particles_styles );
				view.addRenderAttribute( 'particle_data', 'data-rael-partcolor', settings.rael_particles_color );
				view.addRenderAttribute( 'particle_data', 'data-rael-partopacity', settings.rael_particles_opacity.size );
				view.addRenderAttribute( 'particle_data', 'data-rael-partdirection', settings.rael_particles_direction );

				if( 'yes' == settings.rael_enable_advanced ) {
					view.addRenderAttribute( 'particle_data', 'data-rael-partnum', settings.rael_particles_number.size );
					view.addRenderAttribute( 'particle_data', 'data-rael-partsize', settings.rael_particles_size.size );
					view.addRenderAttribute( 'particle_data', 'data-rael-partspeed', settings.rael_particles_speed.size );
					view.addRenderAttribute( 'particle_data', 'data-rael-interactive', 'no' );

				}
				if ( 'custom' == settings.rael_particles_styles ) {
					view.addRenderAttribute( 'particle_data', 'data-rael-partdata', settings.rael_particle_json );
				}
				#>
				<div {{{ view.getRenderAttributeString( 'particle_data' ) }}}></div>
			<# } #>
			<?php
			$slider_content = ob_get_contents();
			ob_end_clean();
			$template = $slider_content . $old_template;
			return $template;
		}
	}

	RAEL_Particles_Background::instance();
}
