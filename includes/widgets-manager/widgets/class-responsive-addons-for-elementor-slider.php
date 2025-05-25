<?php
/**
 * Slider Widget
 *
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Slider widget class.
 *
 * @since 1.0.0
 */
class Responsive_Addons_For_Elementor_Slider extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-slider';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Slider', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve slider widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-slider-push rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the slider widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}
	/**
	 * Get the stylesheets required for the widget.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'font-awesome-5-all',
			'font-awesome-4-shim',
			'swiper',
			'e-swiper',	
		);
	}
	/**
	 * Get the scripts required for the widget.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'font-awesome-4-shim',
		);
	}
	/**
	 * Get button sizes.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public static function get_button_sizes() {
		return array(
			'xs' => __( 'Extra Small', 'responsive-addons-for-elementor' ),
			'sm' => __( 'Small', 'responsive-addons-for-elementor' ),
			'md' => __( 'Medium', 'responsive-addons-for-elementor' ),
			'lg' => __( 'Large', 'responsive-addons-for-elementor' ),
			'xl' => __( 'Extra Large', 'responsive-addons-for-elementor' ),
		);
	}

	/**
	 * Register all the control settings for the slider
	 *
	 * @since 1.0.0
	 * @access public
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'responsive_elementor_slider',
			array(
				'label' => __( 'RAE Slider', 'responsive-addons-for-elementor' ),
			)
		);
		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'slides_repeater' );

		$repeater->start_controls_tab( 'background', array( 'label' => __( 'Background', 'responsive-addons-for-elementor' ) ) );

		$repeater->add_control(
			'background_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0F4C81',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-bg' => 'background-color: {{VALUE}}',
				),
			)
		);

		$repeater->add_control(
			'background_image',
			array(
				'label'     => _x( 'Image', 'Background Control', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-bg' => 'background-image: url({{URL}})',
				),
			)
		);

		$repeater->add_control(
			'background_size',
			array(
				'label'      => _x( 'Size', 'Background Control', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'cover',
				'options'    => array(
					'cover'   => _x( 'Cover', 'Background Control', 'responsive-addons-for-elementor' ),
					'contain' => _x( 'Contain', 'Background Control', 'responsive-addons-for-elementor' ),
					'auto'    => _x( 'Auto', 'Background Control', 'responsive-addons-for-elementor' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .swiper-slide-bg' => 'background-size: {{VALUE}}',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'background_image[url]',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'background_overlay',
			array(
				'label'      => __( 'Background Overlay', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SWITCHER,
				'default'    => '',
				'condition' => array( 'background_image[id]!' => '' ),
			)
		);

		$repeater->add_control(
			'background_overlay_color',
			array(
				'label'      => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => 'rgba(0,0,0,0.5)',
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'background_overlay',
							'value' => 'yes',
						),
						array(
							'name'     => 'background_image[url]',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .elementor-background-overlay' => 'background-color: {{VALUE}}',
				),
			)
		);

		$repeater->add_control(
			'background_overlay_blend_mode',
			array(
				'label'      => __( 'Blend Mode', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SELECT,
				'options'    => array(
					''            => __( 'Normal', 'responsive-addons-for-elementor' ),
					'multiply'    => 'Multiply',
					'screen'      => 'Screen',
					'overlay'     => 'Overlay',
					'darken'      => 'Darken',
					'lighten'     => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'color-burn'  => 'Color Burn',
					'hue'         => 'Hue',
					'saturation'  => 'Saturation',
					'color'       => 'Color',
					'exclusion'   => 'Exclusion',
					'luminosity'  => 'Luminosity',
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'background_overlay',
							'value' => 'yes',
						),
						array(
							'name'     => 'background_image[url]',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .elementor-background-overlay' => 'mix-blend-mode: {{VALUE}}',
				),
			)
		);

		$repeater->add_control(
			'background_ken_burns',
			array(
				'label'      => __( 'Ken Burns Effect', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SWITCHER,
				'default'    => '',
				'condition' => array( 'background_image[id]!' => '' ),
			)
		);

		$repeater->add_control(
			'zoom_direction',
			array(
				'label'      => __( 'Zoom Direction', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'in',
				'options'    => array(
					'in'  => __( 'In', 'responsive-addons-for-elementor' ),
					'out' => __( 'Out', 'responsive-addons-for-elementor' ),
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'background_ken_burns',
							'operator' => '!=',
							'value'    => '',
						),
						array(
							'name'     => 'background_image[url]',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'content', array( 'label' => __( 'Content', 'responsive-addons-for-elementor' ) ) );

		$repeater->add_control(
			'heading',
			array(
				'label'       => __( 'Title & Description', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => __( 'Slide Heading', 'responsive-addons-for-elementor' ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'description',
			array(
				'label'      => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::TEXTAREA,
				'default'    => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'responsive-addons-for-elementor' ),
				'show_label' => false,
			)
		);

		$repeater->add_control(
			'button_text',
			array(
				'label'   => __( 'Button Text', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Click Here', 'responsive-addons-for-elementor' ),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'responsive-addons-for-elementor' ),
			)
		);

		$repeater->add_control(
			'link_click',
			array(
				'label'      => __( 'Apply Link On', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SELECT,
				'options'    => array(
					'slide'  => __( 'Full Slide', 'responsive-addons-for-elementor' ),
					'button' => __( 'Button Only', 'responsive-addons-for-elementor' ),
				),
				'default'    => 'slide',
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'link[url]',
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'slide_image_show',
			array(
				'label'        => __( 'Display Image', 'responsive-addons-for-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$repeater->add_control(
			'slide_image',
			array(
				'label'     => __( 'Image', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .responsive-slide-image' => 'background-image: url({{URL}}); background-repeat: no-repeat',
				),
				'condition' => array(
					'slide_image_show' => 'yes',
				),
			)
		);

		$repeater->add_responsive_control(
			'slide_image_height',
			array(
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
					'vh' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 400,
				),
				'size_units' => array( 'px', 'vh', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .responsive-slide-image' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'slide_image_show' => 'yes',
				),
			)
		);

		$repeater->add_responsive_control(
			'slide_image_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 10,
						'max' => 100,
					),
					'em' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 400,
				),
				'size_units' => array( 'px', 'vw', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .responsive-slide-image' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'slide_image_show' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'slide_image_background_size',
			array(
				'label'     => _x( 'Size', 'Background Control', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'contain',
				'options'   => array(
					'cover'   => _x( 'Cover', 'Background Control', 'responsive-addons-for-elementor' ),
					'contain' => _x( 'Contain', 'Background Control', 'responsive-addons-for-elementor' ),
					'auto'    => _x( 'Auto', 'Background Control', 'responsive-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .responsive-slide-image' => 'background-size: {{VALUE}}',
				),
				'condition' => array(
					'slide_image_show' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'slide_image_background_position',
			array(
				'label'     => _x( 'Position', 'Background Control', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'center',
				'options'   => array(
					'top'    => _x( 'Top', 'Background Control', 'responsive-addons-for-elementor' ),
					'left'   => _x( 'Left', 'Background Control', 'responsive-addons-for-elementor' ),
					'center' => _x( 'Center', 'Background Control', 'responsive-addons-for-elementor' ),
					'right'  => _x( 'Right', 'Background Control', 'responsive-addons-for-elementor' ),
					'bottom' => _x( 'Bottom', 'Background Control', 'responsive-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .responsive-slide-image' => 'background-position: {{VALUE}}',
				),
				'condition' => array(
					'slide_image_show' => 'yes',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'slides',
			array(
				'label'       => __( 'Slides', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'show_label'  => true,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'heading'          => __( 'Slide 1', 'responsive-addons-for-elementor' ),
						'description'      => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'responsive-addons-for-elementor' ),
						'button_text'      => __( 'Click Here', 'responsive-addons-for-elementor' ),
						'background_color' => '#0F4C81',
					),
					array(
						'heading'          => __( 'Slide 2', 'responsive-addons-for-elementor' ),
						'description'      => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'responsive-addons-for-elementor' ),
						'button_text'      => __( 'Click Here', 'responsive-addons-for-elementor' ),
						'background_color' => '#455292',
					),
					array(
						'heading'          => __( 'Slide 3', 'responsive-addons-for-elementor' ),
						'description'      => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'responsive-addons-for-elementor' ),
						'button_text'      => __( 'Click Here', 'responsive-addons-for-elementor' ),
						'background_color' => '#359481',
					),
				),
				'title_field' => '{{{ heading }}}',
			)
		);

		$this->add_responsive_control(
			'slides_height',
			array(
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
					'vh' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 400,
				),
				'size_units' => array( 'px', 'vh', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-slide' => 'height: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_slider_options',
			array(
				'label' => __( 'RAE Slider Options', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::SECTION,
			)
		);

		$this->add_control(
			'navigation',
			array(
				'label'              => __( 'Navigation', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'both',
				'options'            => array(
					'both'   => __( 'Arrows and Dots', 'responsive-addons-for-elementor' ),
					'arrows' => __( 'Arrows', 'responsive-addons-for-elementor' ),
					'dots'   => __( 'Dots', 'responsive-addons-for-elementor' ),
					'none'   => __( 'None', 'responsive-addons-for-elementor' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'              => __( 'Autoplay', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'              => __( 'Pause on Hover', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'frontend_available' => true,
				'condition'          => array(
					'autoplay!' => '',
				),
			)
		);

		$this->add_control(
			'pause_on_interaction',
			array(
				'label'              => __( 'Pause on Interaction', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'frontend_available' => true,
				'condition'          => array(
					'autoplay!' => '',
				),
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'              => __( 'Autoplay Speed', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 5000,
				'condition'          => array(
					'autoplay' => 'yes',
				),
				'selectors'          => array(
					'{{WRAPPER}} .swiper-slide' => 'transition-duration: calc({{VALUE}}ms*1.2)',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'infinite',
			array(
				'label'              => __( 'Infinite Loop', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'transition',
			array(
				'label'              => __( 'Transition', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'fade',
				'options'            => array(
					'slide' => __( 'Slide', 'responsive-addons-for-elementor' ),
					'fade'  => __( 'Fade', 'responsive-addons-for-elementor' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'transition_speed',
			array(
				'label'              => __( 'Transition Speed', 'responsive-addons-for-elementor' ) . ' (ms) ',
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'content_animation',
			array(
				'label'              => __( 'Animation', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'fadeInUp',
				'options'            => array(
					''            => __( 'None', 'responsive-addons-for-elementor' ),
					'fadeInUp'    => __( 'Up', 'responsive-addons-for-elementor' ),
					'fadeInDown'  => __( 'Down', 'responsive-addons-for-elementor' ),
					'fadeInLeft'  => __( 'Left', 'responsive-addons-for-elementor' ),
					'fadeInRight' => __( 'Right', 'responsive-addons-for-elementor' ),
					'zoomIn'      => __( 'Zoom', 'responsive-addons-for-elementor' ),
				),
				'frontend_available' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_slides',
			array(
				'label' => __( 'Slides', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'content_max_width',
			array(
				'label'          => __( 'Content Width', 'responsive-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'range'          => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'size_units'     => array( '%', 'px' ),
				'default'        => array(
					'size' => '60',
					'unit' => '%',
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'selectors'      => array(
					'{{WRAPPER}} .swiper-slide-contents' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'slides_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-slide-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'slides_horizontal_position',
			array(
				'label'        => __( 'Horizontal Position', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'default'      => 'center',
				'options'      => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'prefix_class' => 'responsive--h-position-',
			)
		);

		$this->add_control(
			'slides_vertical_position',
			array(
				'label'        => __( 'Vertical Position', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'default'      => 'middle',
				'options'      => array(
					'top'    => array(
						'title' => __( 'Top', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'prefix_class' => 'responsive--v-position-',
			)
		);

		$this->add_control(
			'slides_text_align',
			array(
				'label'     => __( 'Text Align', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .swiper-slide-inner' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'text_shadow',
				'selector' => '{{WRAPPER}} .swiper-slide-contents',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			array(
				'label' => __( 'Title', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'title_heading_spacing',
			array(
				'label'     => __( 'Title Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .swiper-slide-inner .responsive-slide-heading:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'title_heading_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-slide-heading' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .responsive-slide-heading',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_description',
			array(
				'label' => __( 'Description', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'description_spacing',
			array(
				'label'     => __( 'Description Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .swiper-slide-inner .responsive-slide-description:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Description Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-slide-description' => 'color: {{VALUE}}',

				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .responsive-slide-description',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			array(
				'label' => __( 'Button', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'button_size',
			array(
				'label'   => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => self::get_button_sizes(),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .responsive-slide-button',
			)
		);

		$this->add_control(
			'button_border_width',
			array(
				'label'     => __( 'Border Width', 'responsive-addons-for-elementor' ) . ( ' px ' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-slide-button' => 'border-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-slide-button' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
				'separator' => 'after',
			)
		);

		$this->start_controls_tabs( 'button_tabs_slider' );

		$this->start_controls_tab( 'normal', array( 'label' => __( 'Normal', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'button_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-slide-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-slide-button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-slide-button' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover', array( 'label' => __( 'Hover', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'button_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-slide-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-slide-button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-slide-button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_navigation',
			array(
				'label'     => __( 'Navigation', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'navigation' => array( 'arrows', 'dots', 'both' ),
				),
			)
		);

		$this->add_control(
			'heading_style_arrows',
			array(
				'label'     => __( 'Arrows', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'navigation' => array( 'arrows', 'both' ),
				),
			)
		);

		$this->add_control(
			'arrows_position',
			array(
				'label'        => __( 'Arrows Position', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'inside',
				'options'      => array(
					'inside'  => __( 'Inside', 'responsive-addons-for-elementor' ),
					'outside' => __( 'Outside', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'elementor-arrows-position-',
				'condition'    => array(
					'navigation' => array( 'arrows', 'both' ),
				),
			)
		);

		$this->add_responsive_control(
			'pagination_buttons_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .responsive-swiper-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'navigation' => array( 'arrows', 'both' ),
				),
			)
		);

		$this->add_control(
			'pagination_buttons_border_radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-swiper-button' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'navigation' => array( 'arrows', 'both' ),
				),
				'separator' => 'after',
			)
		);

		$this->start_controls_tabs(
			'pagination_buttons',
			array(
				'condition' => array(
					'navigation' => array( 'arrows', 'both' ),
				),
			)
		);

		$this->start_controls_tab( 'pagination_buttons_normal', array( 'label' => __( 'Normal', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'pagination_buttons_normal_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#91ADC6',
				'selectors' => array(
					'{{WRAPPER}} .responsive-swiper-button' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'navigation' => array( 'arrows', 'both' ),
				),
			)
		);

		$this->add_control(
			'pagination_buttons_normal_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-swiper-button' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'navigation' => array( 'arrows', 'both' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'pagination_buttons_hover', array( 'label' => __( 'Hover', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'arrows_size_hover',
			array(
				'label'     => __( 'Arrows Hover Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 20,
						'max' => 60,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-swiper-button:hover' => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'navigation' => array( 'arrows', 'both' ),
				),
			)
		);

		$this->add_control(
			'pagination_buttons_hover_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#AB4F19',
				'selectors' => array(
					'{{WRAPPER}} .responsive-swiper-button:hover' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'navigation' => array( 'arrows', 'both' ),
				),
			)
		);

		$this->add_control(
			'pagination_buttons_hover_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-swiper-button:hover' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'navigation' => array( 'arrows', 'both' ),
				),
			)
		);

		$this->add_control(
			'pagination_button_transition_speed',
			array(
				'label'     => __( 'Transition Speed', 'responsive-addons-for-elementor' ) . ' (ms) ',
				'type'      => Controls_Manager::NUMBER,
				'default'   => 500,
				'step'      => 100,
				'selectors' => array(
					'{{WRAPPER}} .responsive-swiper-button' => 'transition-duration: {{VALUE}}ms;',
				),
				'condition' => array(
					'navigation' => array( 'arrows', 'both' ),
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'heading_style_dots',
			array(
				'label'     => __( 'Dots', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'navigation' => array( 'dots', 'both' ),
				),
			)
		);

		$this->add_control(
			'dots_position',
			array(
				'label'        => __( 'Dots Position', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'inside',
				'options'      => array(
					'outside' => __( 'Outside', 'responsive-addons-for-elementor' ),
					'inside'  => __( 'Inside', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'elementor-pagination-position-',
				'condition'    => array(
					'navigation' => array( 'dots', 'both' ),
				),
			)
		);

		$this->add_control(
			'dots_size',
			array(
				'label'     => __( 'Dots Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 5,
						'max' => 15,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-container-horizontal .swiper-pagination-progressbar' => 'height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-pagination-fraction' => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'navigation' => array( 'dots', 'both' ),
				),
			)
		);

		$this->add_control(
			'dots_color',
			array(
				'label'     => __( 'Dots Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-bullet:not(.swiper-pagination-bullet-active)' => 'background-color: {{VALUE}}; opacity: 1;',
				),
				'condition' => array(
					'navigation' => array( 'dots', 'both' ),
				),
			)
		);

		$this->add_control(
			'active_dots_color',
			array(
				'label'     => __( 'Active Dots Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'navigation' => array( 'dots', 'both' ),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render Images on the frontend for the slider
	 */
	public function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['slides'] ) ) {
			return;
		}

		$this->add_render_attribute( 'button', 'class', array( 'elementor-button', 'responsive-slide-button' ) );

		if ( ! empty( $settings['button_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'responsive-size-' . $settings['button_size'] );
		}

		$slides      = array();
		$slide_count = 0;
		foreach ( $settings['slides'] as $slide ) {
			$slide_html       = '';
			$btn_attributes   = '';
			$slide_attributes = '';
			$slide_element    = 'div';
			$btn_element      = 'div';

			if ( ! empty( $slide['link']['url'] ) ) {
				$this->add_link_attributes( 'slide_link' . $slide_count, $slide['link'] );

				if ( 'button' === $slide['link_click'] ) {
					$btn_element    = 'a';
					$btn_attributes = $this->get_render_attribute_string( 'slide_link' . $slide_count );
				} else {
					$slide_element    = 'a';
					$slide_attributes = $this->get_render_attribute_string( 'slide_link' . $slide_count );
				}
			}

			$slide_html .= '<' . $slide_element . ' class="swiper-slide-inner" ' . $slide_attributes . '>';

			$slide_html .= '<div class="swiper-slide-contents">';

			if ( $slide['heading'] ) {
				$slide_html .= '<div class="responsive-slide-heading">' . $slide['heading'] . '</div>';
			}

			if ( $slide['description'] ) {
				$slide_html .= '<div class="responsive-slide-description">' . $slide['description'] . '</div>';
			}

			if ( $slide['button_text'] ) {
				$slide_html .= '<' . $btn_element . ' ' . $btn_attributes . ' ' . $this->get_render_attribute_string( 'button' ) . '>' . $slide['button_text'] . '</' . $btn_element . '>';
			}

			$slide_html .= '</div>';
			if ( 'yes' === $slide['slide_image_show'] && $slide['slide_image'] ) {
				$slide_html .= '<div class="responsive-slide-image"></div>';
			}

			$slide_html .= '</' . $slide_element . '>';

			if ( 'yes' === $slide['background_overlay'] ) {
				$slide_html = '<div class="elementor-background-overlay"></div>' . $slide_html;
			}

			$ken_class = '';

			if ( 'yes' === $slide['background_ken_burns'] ) {
				$ken_class = ' elementor-ken-burns elementor-ken-burns--' . $slide['zoom_direction'];
			}

			$active_ken_burns = 'yes' === $slide['background_ken_burns'] ? 'true' : 'false';
			$slide_html       = '<div class="swiper-slide-bg' . $ken_class . '" data-ken-burns="' . $active_ken_burns . '"></div>' . $slide_html;

			$slides[] = '<div class="elementor-repeater-item-' . $slide['_id'] . ' swiper-slide">' . $slide_html . '</div>';
			$slide_count++;
		}

		$prev      = 'left';
		$next      = 'right';
		$direction = 'ltr';

		if ( is_rtl() ) {
			$prev      = 'right';
			$next      = 'left';
			$direction = 'rtl';
		}

		$show_dots   = ( in_array( $settings['navigation'], array( 'dots', 'both' ), true ) );
		$show_arrows = ( in_array( $settings['navigation'], array( 'arrows', 'both' ), true ) );

		$slides_count = count( $settings['slides'] );
		?>
		<div class="responsive-swiper">
			<div class="responsive-slides-wrapper responsive-main-swiper swiper<?php echo esc_attr( RAEL_SWIPER_CONTAINER ); ?>" dir="<?php echo esc_attr( $direction ); ?>" data-animation="<?php echo esc_attr( $settings['content_animation'] ); ?>">
				<div class="swiper-wrapper responsive-slides">
					<?php echo wp_kses_post( implode( '', $slides ) ); ?>
				</div>
				<?php if ( 1 < $slides_count ) : ?>
					<?php if ( $show_dots ) : ?>
						<div class="swiper-pagination"></div>
					<?php endif; ?>
					<?php if ( $show_arrows ) : ?>
						<div class="responsive-swiper-button responsive-swiper-button-prev">
							<i class="fa fa-angle-<?php echo esc_attr( $prev ); ?>" aria-hidden="true"></i>
							<span class="elementor-screen-only"><?php esc_html_e( 'Previous', 'responsive-addons-for-elementor' ); ?></span>
						</div>
						<div class="responsive-swiper-button responsive-swiper-button-next">
							<i class="fa fa-angle-<?php echo esc_attr( $next ); ?>" aria-hidden="true"></i>
							<span class="elementor-screen-only"><?php esc_html_e( 'Next', 'responsive-addons-for-elementor' ); ?></span>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
		<?php

	}

	/**
	 * Content template function
	 */
	protected function content_template() {
		?>
		<#
		var direction        = elementorFrontend.config.is_rtl ? 'rtl' : 'ltr',
		next             = elementorFrontend.config.is_rtl ? 'left' : 'right',
		prev             = elementorFrontend.config.is_rtl ? 'right' : 'left',
		navi             = settings.navigation,
		showDots         = ( 'dots' == navi || 'both' == navi ),
		showArrows       = ( 'arrows' == navi || 'both' == navi ),
		buttonSize       = settings.button_size;
		#>
		<div class="responsive-swiper">
			<div class="responsive-slides-wrapper responsive-main-swiper swiper-container" dir="{{ direction }}" data-animation="{{ settings.content_animation }}">
				<div class="swiper-wrapper responsive-slides">
					<# jQuery.each( settings.slides, function( index, slide ) { #>
					<div class="elementor-repeater-item-{{ slide._id }} swiper-slide">
						<#
						var kenClass = '';

						if ( '' != slide.background_ken_burns ) {
							kenClass =  ' elementor-ken-burns elementor-ken-burns--' + slide.zoom_direction;
						}
						isKenActive = 'yes' === slide.background_ken_burns ? 'true' : 'false';
						#>
						<div class="swiper-slide-bg{{ kenClass }}" data-ken-burns="{{ isKenActive }}"></div>
						<# if ( 'yes' == slide.background_overlay ) { #>
						<div class="elementor-background-overlay"></div>
						<# } #>
						<div class="swiper-slide-inner">
							<div class="swiper-slide-contents">
								<# if ( slide.heading ) { #>
								<div class="responsive-slide-heading">{{{ slide.heading }}}</div>
								<# }
								if ( slide.description ) { #>
								<div class="responsive-slide-description">{{{ slide.description }}}</div>
								<# }
								if ( slide.button_text ) { #>
								<div class="elementor-button responsive-slide-button responsive-size-{{ buttonSize }}">{{{ slide.button_text }}}</div>
								<# } #>
							</div>
							<# if ( slide.slide_image_show ) { #>
								<div class="responsive-slide-image"></div>
							<# } #>
						</div>
					</div>
					<# } ); #>
				</div>
				<# if ( 1 < settings.slides.length ) { #>
				<# if ( showDots ) { #>
				<div class="swiper-pagination"></div>
				<# } #>
				<# if ( showArrows ) { #>
				<div class="responsive-swiper-button responsive-swiper-button-prev">
					<i class="eicon-chevron-{{ prev }}" aria-hidden="true"></i>
					<span class="elementor-screen-only"><?php esc_html_e( 'Previous', 'responsive-addons-for-elementor' ); ?></span>
				</div>
				<div class="responsive-swiper-button responsive-swiper-button-next">
					<i class="eicon-chevron-{{ next }}" aria-hidden="true"></i>
					<span class="elementor-screen-only"><?php esc_html_e( 'Next', 'responsive-addons-for-elementor' ); ?></span>
				</div>
				<# } #>
				<# } #>
			</div>
		</div>
		<?php
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/slider';
	}
}
