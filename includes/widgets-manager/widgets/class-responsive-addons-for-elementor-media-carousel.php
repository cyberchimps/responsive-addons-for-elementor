<?php
/**
 * Media Carousel Widget
 *
 * @since      1.2.1
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Embed;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor 'Media Carousel' widget.
 *
 * Elementor widget that displays Team Member.
 *
 * @since 1.2.1
 */
class Responsive_Addons_For_Elementor_Media_Carousel extends Widget_Base {
	/**
	 * The index of the current slide prints count.
	 *
	 * @var int
	 */
	private $slide_prints_count = 0;

	/**
	 *
	 * This is a variable for lightbox slide index.
	 *
	 * @var int
	 */
	private $lightbox_slide_index;
	/**
	 * Get the widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'rael-media-carousel';
	}
	/**
	 * Get the widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Media Carousel', 'responsive-addons-for-elementor' );
	}
	/**
	 * Get the widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-media-carousel rael-badge';
	}
	/**
	 * Get the widget keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'media', 'carousel', 'image', 'video', 'lightbox' );
	}
	/**
	 * Get the widget categories.
	 *
	 * @return array
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
	 * Get the default values for the repeater.
	 *
	 * @return array
	 */
	protected function get_repeater_defaults() {
		return array_fill( 0, 5, array() );
	}
	/**
	 * Register controls for the widget.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_slides',
			array(
				'label' => __( 'Slides', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_skin',
			array(
				'label'              => __( 'Skin', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'carousel',
				'options'            => array(
					'carousel'  => __( 'Carousel', 'responsive-addons-for-elementor' ),
					'slideshow' => __( 'Slideshow', 'responsive-addons-for-elementor' ),
					'coverflow' => __( 'Coverflow', 'responsive-addons-for-elementor' ),
				),
				'prefix_class'       => 'rael-elementor-skin-',
				'render_type'        => 'template',
				'frontend_available' => true,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'rael_type',
			array(
				'type'    => Controls_Manager::CHOOSE,
				'label'   => __( 'Type', 'responsive-addons-for-elementor' ),
				'default' => 'image',
				'options' => array(
					'image' => array(
						'title' => __( 'Image', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-image-bold',
					),
					'video' => array(
						'title' => __( 'Video', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-video-camera',
					),
				),
				'toggle'  => false,
			)
		);

		$repeater->add_control(
			'rael_image',
			array(
				'label'   => __( 'Image', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$repeater->add_control(
			'rael_image_link_to_type',
			array(
				'label'     => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''       => __( 'None', 'responsive-addons-for-elementor' ),
					'file'   => __( 'Media File', 'responsive-addons-for-elementor' ),
					'custom' => __( 'Custom URL', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_type' => 'image',
				),
			)
		);

		$repeater->add_control(
			'rael_image_link_to',
			array(
				'type'          => Controls_Manager::URL,
				'placeholder'   => __( 'https://your-link.com', 'responsive-addons-for-elementor' ),
				'show_external' => 'true',
				'condition'     => array(
					'rael_type'               => 'image',
					'rael_image_link_to_type' => 'custom',
				),
				'separator'     => 'none',
				'show_label'    => false,
			)
		);

		$repeater->add_control(
			'rael_video',
			array(
				'label'       => __( 'Video Link', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'Enter your video link', 'responsive-addons-for-elementor' ),
				'description' => __( 'YouTube or Vimeo link', 'responsive-addons-for-elementor' ),
				'options'     => false,
				'condition'   => array(
					'rael_type' => 'video',
				),
			)
		);

		$this->add_control(
			'rael_slides',
			array(
				'label'     => __( 'Slides', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::REPEATER,
				'fields'    => $repeater->get_controls(),
				'default'   => $this->get_repeater_defaults(),
				'separator' => 'after',
			)
		);

		$this->add_control(
			'rael_effect',
			array(
				'type'               => Controls_Manager::SELECT,
				'label'              => __( 'Effect', 'responsive-addons-for-elementor' ),
				'default'            => 'slide',
				'options'            => array(
					'slide' => __( 'Slide', 'responsive-addons-for-elementor' ),
					'fade'  => __( 'Fade', 'responsive-addons-for-elementor' ),
					'cube'  => __( 'Cube', 'responsive-addons-for-elementor' ),
				),
				'condition'          => array(
					'rael_skin!' => 'coverflow',
				),
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'rael_slideshow_height',
			array(
				'type'      => Controls_Manager::SLIDER,
				'label'     => __( 'Height', 'responsive-addons-for-elementor' ),
				'range'     => array(
					'px' => array(
						'min' => 20,
						'max' => 1000,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-main-swiper' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_skin' => 'slideshow',
				),
			)
		);

		$this->add_control(
			'rael_thumbs_title',
			array(
				'label'     => __( 'Thumbnails', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'rael_skin' => 'slideshow',
				),
			)
		);

		$slides_per_view = range( 1, 10 );
		$slides_per_view = array_combine( $slides_per_view, $slides_per_view );

		$this->add_responsive_control(
			'rael_slides_per_view',
			array(
				'type'               => Controls_Manager::SELECT,
				'label'              => __( 'Slides Per View', 'responsive-addons-for-elementor' ),
				'options'            => array( '' => __( 'Default', 'responsive-addons-for-elementor' ) ) + $slides_per_view,
				'condition'          => array(
					'rael_skin!'  => 'slideshow',
					'rael_effect' => 'slide',
				),
				'frontend_available' => true,
			),
			array( 'recursive' => true )
		);

		$this->add_responsive_control(
			'rael_slideshow_rael_slides_per_view',
			array(
				'type'               => Controls_Manager::SELECT,
				'label'              => __( 'Slides Per View', 'responsive-addons-for-elementor' ),
				'options'            => array( '' => __( 'Default', 'responsive-addons-for-elementor' ) ) + $slides_per_view,
				'condition'          => array(
					'rael_skin' => 'slideshow',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_thumbs_ratio',
			array(
				'label'        => __( 'Ratio', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => '219',
				'options'      => array(
					'169' => '16:9',
					'219' => '21:9',
					'43'  => '4:3',
					'11'  => '1:1',
				),
				'prefix_class' => 'elementor-aspect-ratio-',
				'condition'    => array(
					'rael_skin' => 'slideshow',
				),
			)
		);

		$this->add_control(
			'rael_centered_slides',
			array(
				'label'              => __( 'Centered Slides', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'condition'          => array(
					'rael_skin' => 'slideshow',
				),
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'rael_slides_to_scroll',
			array(
				'type'               => Controls_Manager::SELECT,
				'label'              => __( 'Slides to Scroll', 'responsive-addons-for-elementor' ),
				'description'        => __( 'Set how many slides are scrolled per swipe.', 'responsive-addons-for-elementor' ),
				'options'            => array( '' => __( 'Default', 'responsive-addons-for-elementor' ) ) + $slides_per_view,
				'condition'          => array(
					'rael_skin!'  => 'slideshow',
					'rael_effect' => 'slide',
				),
				'frontend_available' => true,
			),
			array( 'recursive' => true )
		);

		$this->add_responsive_control(
			'rael_height',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ),
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
					'vh' => array(
						'min' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-main-swiper' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_skin!' => 'slideshow',
				),
			),
			array( 'recursive' => true )
		);

		$this->add_responsive_control(
			'rael_width',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 1140,
					),
					'%'  => array(
						'min' => 50,
					),
				),
				'size_units' => array( '%', 'px' ),
				'default'    => array(
					'unit' => '%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-main-swiper' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_skin!' => 'slideshow',
				),
			),
			array( 'recursive' => true )
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_options',
			array(
				'label' => __( 'Additional Options', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_show_arrows',
			array(
				'type'               => Controls_Manager::SWITCHER,
				'label'              => __( 'Arrows', 'responsive-addons-for-elementor' ),
				'default'            => 'yes',
				'label_off'          => __( 'Hide', 'responsive-addons-for-elementor' ),
				'label_on'           => __( 'Show', 'responsive-addons-for-elementor' ),
				'prefix_class'       => 'elementor-arrows-',
				'render_type'        => 'template',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_pagination',
			array(
				'label'              => __( 'Pagination', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'bullets',
				'options'            => array(
					''            => __( 'None', 'responsive-addons-for-elementor' ),
					'bullets'     => __( 'Dots', 'responsive-addons-for-elementor' ),
					'fraction'    => __( 'Fraction', 'responsive-addons-for-elementor' ),
					'progressbar' => __( 'Progress', 'responsive-addons-for-elementor' ),
				),
				'condition'          => array(
					'rael_skin!' => 'slideshow',
				),
				'prefix_class'       => 'elementor-pagination-type-',
				'render_type'        => 'template',
				'frontend_available' => true,
			),
			array( 'recursive' => true )
		);

		$this->add_control(
			'rael_speed',
			array(
				'label'              => __( 'Transition Duration', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_autoplay',
			array(
				'label'              => __( 'Autoplay', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'separator'          => 'before',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_autoplay_speed',
			array(
				'label'              => __( 'Autoplay Speed', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 5000,
				'condition'          => array(
					'rael_autoplay' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_loop',
			array(
				'label'              => __( 'Infinite Loop', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_pause_on_hover',
			array(
				'label'              => __( 'Pause on Hover', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'condition'          => array(
					'rael_autoplay' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_pause_on_interaction',
			array(
				'label'              => __( 'Pause on Interaction', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'condition'          => array(
					'rael_autoplay' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_overlay',
			array(
				'label'     => __( 'Overlay', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''     => __( 'None', 'responsive-addons-for-elementor' ),
					'text' => __( 'Text', 'responsive-addons-for-elementor' ),
					'icon' => __( 'Icon', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_skin!' => 'slideshow',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_caption',
			array(
				'label'     => __( 'Caption', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'title',
				'options'   => array(
					'title'       => __( 'Title', 'responsive-addons-for-elementor' ),
					'caption'     => __( 'Caption', 'responsive-addons-for-elementor' ),
					'description' => __( 'Description', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_skin!'   => 'slideshow',
					'rael_overlay' => 'text',
				),
			)
		);

		$this->add_control(
			'rael_icon',
			array(
				'label'     => __( 'Icon', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'search-plus',
				'options'   => array(
					'search-plus' => array(
						'icon' => 'eicon-search-plus',
					),
					'plus-circle' => array(
						'icon' => 'eicon-plus-circle',
					),
					'eye'         => array(
						'icon' => 'eicon-preview-medium',
					),
					'link'        => array(
						'icon' => 'eicon-link',
					),
				),
				'condition' => array(
					'rael_skin!'   => 'slideshow',
					'rael_overlay' => 'icon',
				),
			)
		);

		$this->add_control(
			'rael_overlay_animation',
			array(
				'label'     => __( 'Animation', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fade',
				'options'   => array(
					'fade'        => 'Fade',
					'slide-up'    => 'Slide Up',
					'slide-down'  => 'Slide Down',
					'slide-right' => 'Slide Right',
					'slide-left'  => 'Slide Left',
					'zoom-in'     => 'Zoom In',
				),
				'condition' => array(
					'rael_skin!'    => 'slideshow',
					'rael_overlay!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image_size',
				'default'   => 'full',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_image_fit',
			array(
				'label'     => __( 'Image Fit', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''        => __( 'Cover', 'responsive-addons-for-elementor' ),
					'contain' => __( 'Contain', 'responsive-addons-for-elementor' ),
					'auto'    => __( 'Auto', 'responsive-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-main-swiper .elementor-carousel-image' => 'background-size: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slides_style',
			array(
				'label' => __( 'Slides', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_space_between',
			array(
				'label'              => __( 'Space Between', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => array(
					'px' => array(
						'max' => 50,
					),
				),
				'desktop_default'    => array(
					'size' => 10,
				),
				'tablet_default'     => array(
					'size' => 10,
				),
				'mobile_default'     => array(
					'size' => 10,
				),
				'frontend_available' => true,
				'selectors'          => array(
					'{{WRAPPER}}.rael-elementor-skin-slideshow .elementor-main-swiper' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
				'render_type'        => 'ui',
			)
		);

		$this->add_control(
			'rael_slide_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-main-swiper .swiper-slide' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_slide_border_size',
			array(
				'label'     => __( 'Border Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .elementor-main-swiper .swiper-slide' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'rael_slide_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'%' => array(
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-main-swiper .swiper-slide' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'rael_slide_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-main-swiper .swiper-slide' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_slide_padding',
			array(
				'label'     => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .elementor-main-swiper .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_navigation',
			array(
				'label' => __( 'Navigation', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_heading_arrows',
			array(
				'label'     => __( 'Arrows', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'none',
			)
		);

		$this->start_controls_tabs( 'rael_arrows_tabs' );

		$this->start_controls_tab(
			'rael_arrows_normal_state',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_arrows_normal_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 20,
				),
				'range'     => array(
					'px' => array(
						'min' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-swiper-button' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'rael_arrows_normal_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-swiper-button' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_arrows_hover_state',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_arrows_hover_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 20,
				),
				'range'     => array(
					'px' => array(
						'min' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-swiper-button:hover' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'rael_arrows_hover_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-swiper-button:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'rael_heading_pagination',
			array(
				'label'     => __( 'Pagination', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_skin!' => 'slideshow',
				),
			),
			array( 'recursive' => true )
		);

		$this->add_control(
			'rael_pagination_position',
			array(
				'label'        => __( 'Position', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'outside',
				'options'      => array(
					'outside' => __( 'Outside', 'responsive-addons-for-elementor' ),
					'inside'  => __( 'Inside', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'elementor-pagination-position-',
				'condition'    => array(
					'rael_skin!' => 'slideshow',
				),
			),
			array( 'recursive' => true )
		);

		$this->add_control(
			'rael_pagination_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 20,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-container-horizontal .swiper-pagination-progressbar' => 'height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-pagination-fraction' => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'rael_skin!' => 'slideshow',
				),
			),
			array( 'recursive' => true )
		);

		$this->add_control(
			'rael_pagination_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-bullet-active, {{WRAPPER}} .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .swiper-pagination-fraction' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'rael_skin!' => 'slideshow',
				),
			),
			array( 'recursive' => true )
		);

		$this->add_control(
			'rael_play_icon_title',
			array(
				'label'     => __( 'Play Icon', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_play_icon_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-custom-embed-play i' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'rael_play_icon_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 20,
						'max' => 150,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-custom-embed-play i' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'           => 'play_icon_text_shadow',
				'selector'       => '{{WRAPPER}} .elementor-custom-embed-play i',
				'fields_options' => array(
					'text_shadow_type' => array(
						'label' => _x( 'Shadow', 'Text Shadow Control', 'responsive-addons-for-elementor' ),
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_overlay',
			array(
				'label'     => __( 'Overlay', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_skin!'    => 'slideshow',
					'rael_overlay!' => '',
				),
			)
		);

		$this->add_control(
			'rael_overlay_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-elementor-carousel-image-overlay' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_overlay_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-elementor-carousel-image-overlay' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'caption_typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector'  => '{{WRAPPER}} .rael-elementor-carousel-image-overlay',
				'condition' => array(
					'rael_overlay' => 'text',
				),
			)
		);

		$this->add_control(
			'rael_icon_size',
			array(
				'label'     => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .rael-elementor-carousel-image-overlay i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_overlay' => 'icon',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_lightbox_style',
			array(
				'label' => __( 'Lightbox', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_lightbox_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'#elementor-lightbox-slideshow-{{ID}}' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_lightbox_ui_color',
			array(
				'label'     => __( 'UI Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'#elementor-lightbox-slideshow-{{ID}} .dialog-lightbox-close-button, #elementor-lightbox-slideshow-{{ID}} .elementor-swiper-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_lightbox_ui_hover_color',
			array(
				'label'     => __( 'UI Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'#elementor-lightbox-slideshow-{{ID}} .dialog-lightbox-close-button:hover, #elementor-lightbox-slideshow-{{ID}} .elementor-swiper-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_lightbox_video_width',
			array(
				'label'     => __( 'Video Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'unit' => '%',
				),
				'range'     => array(
					'%' => array(
						'min' => 50,
					),
				),
				'selectors' => array(
					'#elementor-lightbox-slideshow-{{ID}} .elementor-video-container' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}
		/**
		 * Render method for the Elementor slider widget.
		 *
		 * Generates the HTML markup for the slider based on the provided settings.
		 * Handles overlays, pagination, arrows, and individual slide display.
		 *
		 * @access protected
		 */
	protected function render() {
		$settings = $this->get_active_settings();

		if ( $settings['rael_overlay'] ) {
			$this->add_render_attribute(
				'image-overlay',
				'class',
				array(
					'rael-elementor-carousel-image-overlay',
					'e-overlay-animation-' . $settings['rael_overlay_animation'],
				)
			);
		}

		$this->print_slider();

		if ( 'slideshow' !== $settings['rael_skin'] || count( $settings['rael_slides'] ) <= 1 ) {
			return;
		}

		$settings['rael_thumbs_slider']   = true;
		$settings['rael_container_class'] = 'rael-elementor-thumbnails-swiper';
		$settings['rael_show_arrows']     = false;

		$this->print_slider( $settings );

	}
	/**
	 * Print method for rendering the Elementor slider.
	 *
	 * Generates the HTML markup for the slider using the provided settings.
	 * Includes slides, pagination, and navigation arrows if applicable.
	 *
	 * @access protected
	 *
	 * @param array|null $settings Optional. Custom settings for the slider. Defaults to null.
	 */
	protected function print_slider( array $settings = null ) {
		$this->lightbox_slide_index = 0;

		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}

		$default_settings = array(
			'rael_container_class' => 'elementor-main-swiper',
			'rael_video_play_icon' => true,
		);

		$settings = array_merge( $default_settings, $settings );

		$slides_count = count( $settings['rael_slides'] );
		?>
		<div class="elementor-swiper">
			<div class="<?php echo esc_attr( $settings['rael_container_class'] ); ?> swiper<?php echo esc_attr( RAEL_SWIPER_CONTAINER ); ?>">
				<div class="swiper-wrapper">
					<?php
					foreach ( $settings['rael_slides'] as $index => $slide ) :
						$this->slide_prints_count++;
						?>
						<div class="swiper-slide">
							<?php $this->print_slide( $slide, $settings, 'slide-' . $index . '-' . $this->slide_prints_count ); ?>
						</div>
					<?php endforeach; ?>
				</div>
				<?php if ( 1 < $slides_count ) : ?>
					<?php if ( $settings['rael_pagination'] ) : ?>
						<div class="swiper-pagination"></div>
					<?php endif; ?>
					<?php if ( $settings['rael_show_arrows'] ) : ?>
						<div class="elementor-swiper-button elementor-swiper-button-prev">
							<i class="fa fa-angle-left" aria-hidden="true"></i>
							<span class="elementor-screen-only"><?php esc_html_e( 'Previous', 'responsive-addons-for-elementor' ); ?></span>
						</div>
						<div class="elementor-swiper-button elementor-swiper-button-next">
							<i class="fa fa-angle-right" aria-hidden="true"></i>
							<span class="elementor-screen-only"><?php esc_html_e( 'Next', 'responsive-addons-for-elementor' ); ?></span>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
		/**
		 * Print method for rendering an individual slide within the Elementor slider.
		 *
		 * Generates the HTML markup for a single slide based on the provided settings.
		 * Includes the slide image, link, video play icon, and any specified overlays.
		 *
		 * @access protected
		 *
		 * @param array  $slide       The slide data.
		 * @param array  $settings    The settings for the entire slider.
		 * @param string $element_key The key for identifying the slide element.
		 */
	protected function print_slide( array $slide, array $settings, $element_key ) {

		if ( ! empty( $settings['rael_thumbs_slider'] ) ) {
			$settings['rael_video_play_icon'] = false;

			$this->add_render_attribute( $element_key . '-image', 'class', 'elementor-fit-aspect-ratio' );
		}

		$this->add_render_attribute(
			$element_key . '-image',
			array(
				'class' => 'elementor-carousel-image',
				'style' => 'background-image: url(' . $this->get_slide_image_url( $slide, $settings ) . ')',
			)
		);

		$image_link_to = $this->get_image_link_to( $slide );

		if ( $image_link_to && empty( $settings['rael_thumbs_slider'] ) ) {
			if ( 'custom' === $slide['rael_image_link_to_type'] ) {
				$this->add_link_attributes( $element_key . '_link', $slide['rael_image_link_to'] );
			} else {
				$this->add_render_attribute( $element_key . '_link', 'href', $image_link_to );

				$this->add_lightbox_data_attributes( $element_key . '_link', $slide['rael_image']['id'], 'yes', $this->get_id() );

				if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
					$this->add_render_attribute( $element_key . '_link', 'class', 'elementor-clickable' );
				}

				$this->lightbox_slide_index++;
			}

			if ( 'video' === $slide['rael_type'] && $slide['rael_video']['url'] ) {
				$embed_url_params = array(
					'autoplay' => 1,
					'rel'      => 0,
					'controls' => 0,
				);

				$this->add_render_attribute( $element_key . '_link', 'data-elementor-lightbox-video', Embed::get_embed_url( $slide['rael_video']['url'], $embed_url_params ) );
			}

			echo '<a ' . wp_kses_post( $this->get_render_attribute_string( $element_key . '_link' ) ) . '>';
		}

		$this->print_slide_image( $slide, $element_key, $settings );

		if ( $image_link_to ) {
			echo '</a>';
		}
	}
	/**
	 * Get the URL of the image for a slide.
	 *
	 * Retrieves the image URL based on the provided slide data and overall slider settings.
	 *
	 * @access protected
	 *
	 * @param array $slide    The slide data.
	 * @param array $settings The settings for the entire slider.
	 *
	 * @return string The URL of the slide image.
	 */
	protected function get_slide_image_url( $slide, array $settings ) {
		$image_url = Group_Control_Image_Size::get_attachment_image_src( $slide['rael_image']['id'], 'image_size', $settings );

		if ( ! $image_url ) {
			$image_url = $slide['rael_image']['url'];
		}

		return $image_url;
	}
	/**
	 * Get the link destination for a slide image.
	 *
	 * Determines the appropriate link destination for a slide image based on the slide data.
	 *
	 * @access protected
	 *
	 * @param array $slide The slide data.
	 *
	 * @return string The URL of the link destination for the slide image.
	 */
	protected function get_image_link_to( $slide ) {
		if ( ! empty( $slide['rael_video']['url'] ) ) {
			return $slide['rael_image']['url'];
		}

		if ( ! $slide['rael_image_link_to_type'] ) {
			return '';
		}

		if ( 'custom' === $slide['rael_image_link_to_type'] ) {
			return $slide['rael_image_link_to']['url'];
		}

		return $slide['rael_image']['url'];
	}
	/**
	 * Print the HTML markup for an individual slide image.
	 *
	 * Generates the HTML markup for displaying the image of a slide, including any overlays.
	 *
	 * @access protected
	 *
	 * @param array  $slide    The slide data.
	 * @param string $element_key The key for identifying the slide element.
	 * @param array  $settings The settings for the entire slider.
	 */
	protected function print_slide_image( array $slide, $element_key, array $settings ) {
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( $element_key . '-image' ) ); ?>>
			<?php if ( 'video' === $slide['rael_type'] && $settings['rael_video_play_icon'] ) : ?>
				<div class="elementor-custom-embed-play">
					<i class="eicon-play" aria-hidden="true"></i>
					<span class="elementor-screen-only"><?php esc_html_e( 'Play', 'responsive-addons-for-elementor' ); ?></span>
				</div>
			<?php endif; ?>
		</div>
		<?php if ( $settings['rael_overlay'] ) : ?>
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'image-overlay' ) ); ?>>
				<?php if ( 'text' === $settings['rael_overlay'] ) : ?>
					<?php echo wp_kses_post( $this->get_image_caption( $slide ) ); ?>
				<?php else : ?>
					<i class="fa fa-<?php echo wp_kses_post( $settings['rael_icon'] ); ?>"></i>
				<?php endif; ?>
			</div>
			<?php
		endif;
	}
	/**
	 * Get the caption for a slide image.
	 *
	 * Retrieves the caption for a slide image based on the specified caption type.
	 *
	 * @access protected
	 *
	 * @param array $slide The slide data.
	 *
	 * @return string The caption for the slide image.
	 */
	protected function get_image_caption( $slide ) {
		$caption_type = $this->get_settings( 'rael_caption' );

		if ( empty( $caption_type ) ) {
			return '';
		}

		$attachment_post = get_post( $slide['rael_image']['id'] );

		if ( 'caption' === $caption_type ) {
			return $attachment_post->post_excerpt;
		}

		if ( 'title' === $caption_type ) {
			return $attachment_post->post_title;
		}

		return $attachment_post->post_content;
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/media-carousel';
	}

}
