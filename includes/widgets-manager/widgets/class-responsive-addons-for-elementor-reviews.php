<?php
/**
 * Reviews Widget
 *
 * @since      1.2.0
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Reviews widget class.
 */
class Responsive_Addons_For_Elementor_Reviews extends Widget_Base {

	/**
	 * Slide prints count variable
	 *
	 * @var slide_prints_count
	 */
	private $slide_prints_count = 0;

	/**
	 * Get name function
	 */
	public function get_name() {
		return 'rael-reviews';
	}

	/**
	 * Get title function
	 */
	public function get_title() {
		return __( 'Reviews', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get icon function
	 */
	public function get_icon() {
		return 'eicon-review rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the posts widget belongs to.
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
	 * Add repeater controls
	 *
	 * @param Repeater $repeater is a dynamic content widget.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function add_repeater_controls( Repeater $repeater ) {
		$repeater->add_control(
			'image',
			array(
				'label'   => __( 'Image', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$repeater->add_control(
			'name',
			array(
				'label'   => __( 'Name', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'John Doe', 'responsive-addons-for-elementor' ),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '@username',
			)
		);

		$repeater->add_control(
			'rating',
			array(
				'label' => __( 'Rating', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::NUMBER,
				'min'   => 0,
				'max'   => 5,
				'step'  => 0.1,
			)
		);

		$repeater->add_control(
			'selected_social_icon',
			array(
				'label'            => __( 'Icon', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'social_icon',
				'default'          => array(
					'value'   => 'fab fa-twitter',
					'library' => 'fa-brands',
				),
				'recommended'      => array(
					'fa-solid'  => array(
						'rss',
						'shopping-cart',
						'thumbtack',
					),
					'fa-brands' => array(
						'android',
						'apple',
						'behance',
						'bitbucket',
						'codepen',
						'delicious',
						'digg',
						'dribbble',
						'envelope',
						'facebook',
						'flickr',
						'foursquare',
						'github',
						'google-plus',
						'houzz',
						'instagram',
						'jsfiddle',
						'linkedin',
						'medium',
						'meetup',
						'mix',
						'mixcloud',
						'odnoklassniki',
						'pinterest',
						'product-hunt',
						'reddit',
						'skype',
						'slideshare',
						'snapchat',
						'soundcloud',
						'spotify',
						'stack-overflow',
						'steam',
						'telegram',
						'tripadvisor',
						'tumblr',
						'twitch',
						'twitter',
						'vimeo',
						'fa-vk',
						'weibo',
						'weixin',
						'whatsapp',
						'wordpress',
						'xing',
						'yelp',
						'youtube',
						'500px',
					),
				),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => __( 'https://your-link.com', 'responsive-addons-for-elementor' ),

			)
		);

		$repeater->add_control(
			'content',
			array(
				'label'   => __( 'Review', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-addons-for-elementor' ),
			)
		);
	}

	/**
	 * Get repeater controls
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function get_repeater_defaults() {
		$placeholder_image_src = Utils::get_placeholder_image_src();

		return array(
			array(
				'content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-addons-for-elementor' ),
				'name'    => __( 'John Doe', 'responsive-addons-for-elementor' ),
				'title'   => '@username',
				'image'   => array(
					'url' => $placeholder_image_src,
				),
			),
			array(
				'content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-addons-for-elementor' ),
				'name'    => __( 'John Doe', 'responsive-addons-for-elementor' ),
				'title'   => '@username',
				'image'   => array(
					'url' => $placeholder_image_src,
				),
			),
			array(
				'content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-addons-for-elementor' ),
				'name'    => __( 'John Doe', 'responsive-addons-for-elementor' ),
				'title'   => '@username',
				'image'   => array(
					'url' => $placeholder_image_src,
				),
			),
		);
	}

	/**
	 * Register controls
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_slides',
			array(
				'label' => __( 'Slides', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$this->add_repeater_controls( $repeater );

		$this->add_control(
			'slides',
			array(
				'label'     => __( 'Slides', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::REPEATER,
				'fields'    => $repeater->get_controls(),
				'default'   => $this->get_repeater_defaults(),
				'separator' => 'after',
			)
		);

		$slides_per_view = range( 1, 10 );
		$slides_per_view = array_combine( $slides_per_view, $slides_per_view );

		$this->add_responsive_control(
			'slides_per_view',
			array(
				'type'               => Controls_Manager::SELECT,
				'label'              => __( 'Slides Per View', 'responsive-addons-for-elementor' ),
				'options'            => array( '' => __( 'Default', 'responsive-addons-for-elementor' ) ) + $slides_per_view,
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			array(
				'type'               => Controls_Manager::SELECT,
				'label'              => __( 'Slides to Scroll', 'responsive-addons-for-elementor' ),
				'description'        => __( 'Set how many slides are scrolled per swipe.', 'responsive-addons-for-elementor' ),
				'options'            => array( '' => __( 'Default', 'responsive-addons-for-elementor' ) ) + $slides_per_view,
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'width',
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
					'{{WRAPPER}}.elementor-arrows-yes .responsive-main-swiper' => 'width: calc( {{SIZE}}{{UNIT}} - 40px )',
					'{{WRAPPER}} .responsive-main-swiper' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_options',
			array(
				'label' => __( 'Additional Options', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'show_arrows',
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
			'pagination',
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
				'prefix_class'       => 'elementor-pagination-type-',
				'render_type'        => 'template',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'              => __( 'Transition Duration', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'              => __( 'Autoplay', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'separator'          => 'before',
				'frontend_available' => true,
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
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'loop',
			array(
				'label'              => __( 'Infinite Loop', 'responsive-addons-for-elementor' ),
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
				'condition'          => array(
					'autoplay' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'pause_on_interaction',
			array(
				'label'              => __( 'Pause on Interaction', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'condition'          => array(
					'autoplay' => 'yes',
				),
				'frontend_available' => true,
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

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slides_style',
			array(
				'label' => __( 'Slides', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'space_between',
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
				'render_type'        => 'none',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'slide_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-main-swiper .swiper-slide' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'slide_border_size',
			array(
				'label'     => __( 'Border Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .responsive-main-swiper .swiper-slide' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'slide_border_radius',
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
					'{{WRAPPER}} .responsive-main-swiper .swiper-slide' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'slide_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-main-swiper .swiper-slide' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'slide_padding',
			array(
				'label'     => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .responsive-main-swiper .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'heading_header',
			array(
				'label'     => __( 'Header', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'header_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__header' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'content_gap',
			array(
				'label'     => __( 'Gap', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__header' => 'padding-bottom: calc({{SIZE}}{{UNIT}} / 2)',
					'{{WRAPPER}} .responsive-testimonial__content' => 'padding-top: calc({{SIZE}}{{UNIT}} / 2)',
				),
			)
		);

		$this->add_control(
			'show_separator',
			array(
				'label'        => __( 'Separator', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'default'      => 'has-separator',
				'return_value' => 'has-separator',
				'prefix_class' => 'elementor-review--',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'separator_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__header' => 'border-bottom-color: {{VALUE}}',
				),
				'condition' => array(
					'show_separator!' => '',
				),
			)
		);

		$this->add_control(
			'separator_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'condition' => array(
					'show_separator!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__header' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			array(
				'label' => __( 'Text', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'name_title_style',
			array(
				'label' => __( 'Name', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__name' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'name_typography',
				'selector' => '{{WRAPPER}} .responsive-testimonial__header, {{WRAPPER}} .responsive-testimonial__name',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
			)
		);

		$this->add_control(
			'heading_title_style',
			array(
				'label'     => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .responsive-testimonial__title',
			)
		);

		$this->add_control(
			'heading_review_style',
			array(
				'label'     => __( 'Review', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'content_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__text' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .responsive-testimonial__text',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			array(
				'label' => __( 'Image', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'image_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 70,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'image_gap',
			array(
				'label'     => __( 'Gap', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .responsive-testimonial__image + cite' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',
					'body.rtl {{WRAPPER}} .responsive-testimonial__image + cite' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left:0;',
				),
			)
		);

		$this->add_control(
			'image_border_radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__image img' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			array(
				'label' => __( 'Icon', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'   => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => __( 'Official', 'responsive-addons-for-elementor' ),
					'custom'  => __( 'Custom', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'icon_custom_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'icon_color' => 'custom',
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__icon:not(.elementor-testimonial__rating)' => 'color: {{VALUE}};',
					'{{WRAPPER}} .responsive-testimonial__icon:not(.elementor-testimonial__rating) svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-testimonial__icon' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .responsive-testimonial__icon svg' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_rating_style',
			array(
				'label' => __( 'Rating', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'star_style',
			array(
				'label'        => __( 'Icon', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'fa4compatibility' => 'icon',
				'options'      => array(
					'star_fontawesome' => 'Font Awesome',
					'star_unicode'     => 'Unicode',
				),
				'default'      => 'star_fontawesome',
				'render_type'  => 'template',
				'prefix_class' => 'elementor--star-style-',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'unmarked_star_style',
			array(
				'label'   => __( 'Unmarked Style', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'solid'   => array(
						'title' => __( 'Solid', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-star',
					),
					'outline' => array(
						'title' => __( 'Outline', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-star-o',
					),
				),
				'default' => 'solid',
			)
		);

		$this->add_control(
			'star_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'star_space',
			array(
				'label'     => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-left: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
	'stars_color',
	array(
		'label'     => __( 'Marked Star Color', 'responsive-addons-for-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			// Solid stars (Font Awesome solid)
			'{{WRAPPER}} .elementor-star-rating .rael-star-filled svg path' => 'fill: {{VALUE}};',
			'{{WRAPPER}} .elementor-star-rating i:before' => 'color: {{VALUE}};',

			'{{WRAPPER}} .elementor-star-rating .elementor-star-full:before' => 'color: {{VALUE}};',
			'{{WRAPPER}} .elementor-star-rating .elementor-star-full' => 'color: {{VALUE}};',
			'{{WRAPPER}}' => '--rael-star-color: {{VALUE}};',
		),
		'separator' => 'before',
	)
);

$this->add_control(
	'stars_unmarked_color',
	array(
		'label'     => __( 'Unmarked Star Color', 'responsive-addons-for-elementor' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => array(
			// Outline stars (Font Awesome regular)
			'{{WRAPPER}} .elementor-star-rating .rael-star-unmarked svg path' => 'fill: {{VALUE}};',
			'{{WRAPPER}} .elementor-star-rating i' => 'color: {{VALUE}};',
			'{{WRAPPER}} .elementor-star-rating .elementor-star-empty' => 'color: {{VALUE}};',
			'{{WRAPPER}}' => '--rael-star-unmarked-color: {{VALUE}};',
		),
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
			'heading_arrows',
			array(
				'label'     => __( 'Arrows', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'none',
			)
		);

		$this->add_control(
			'arrows_size',
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
					'{{WRAPPER}} .responsive-swiper-button' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'arrows_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .responsive-swiper-button' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'heading_pagination',
			array(
				'label'     => __( 'Pagination', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'pagination!' => '',
				),
			)
		);

		$this->add_control(
			'pagination_size',
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
					'pagination!' => '',
				),
			)
		);

		$this->add_control(
			'pagination_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-bullet-active, {{WRAPPER}} .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .swiper-pagination-fraction' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'pagination!' => '',
				),
			)
		);

		$this->end_controls_section();
	}


	/**
	 * Print icon function
	 *
	 * @param array $slide has slide information.
	 *
	 * @param int   $element_key has element key.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function print_icon( $slide, $element_key ) {
		$migration_allowed = Icons_Manager::is_migration_allowed();
		if ( ! isset( $slide['social_icon'] ) && ! $migration_allowed ) {
			// add old default.
			$slide['social_icon'] = 'fa fa-twitter';
		}

		if ( empty( $slide['social_icon'] ) && empty( $slide['selected_social_icon'] ) ) {
			return '';
		}

		$migrated = isset( $slide['__fa4_migrated']['selected_social_icon'] );
		$is_new   = empty( $slide['social_icon'] ) && $migration_allowed;
		$social   = '';

		if ( $is_new || $migrated ) {
			ob_start();
			Icons_Manager::render_icon( $slide['selected_social_icon'], array( 'aria-hidden' => 'true' ) );
			$icon = ob_get_clean();
		} else {
			$icon = '<i class="' . esc_attr( $slide['social_icon'] ) . '" aria-hidden="true"></i>';
		}

		if ( ! empty( $slide['social_icon'] ) ) {
			$social = str_replace( 'fa fa-', '', $slide['social_icon'] );
		}

		if ( ( $is_new || $migrated ) && 'svg' !== $slide['selected_social_icon']['library'] ) {
			$social = explode( ' ', $slide['selected_social_icon']['value'], 2 );
			if ( empty( $social[1] ) ) {
				$social = '';
			} else {
				$social = str_replace( 'fa-', '', $social[1] );
			}
		}
		if ( 'svg' === $slide['selected_social_icon']['library'] ) {
			$social = '';
		}

		$this->add_render_attribute( 'icon_wrapper_' . $element_key, 'class', 'responsive-testimonial__icon elementor-icon' );

		$icon .= '<span class="elementor-screen-only">' . esc_html__( 'Read More', 'responsive-addons-for-elementor' ) . '</span>';
		$this->add_render_attribute( 'icon_wrapper_' . $element_key, 'class', 'elementor-icon-' . $social );

		return '<div ' . $this->get_render_attribute_string( 'icon_wrapper_' . $element_key ) . '>' . $icon . '</div>';
	}

	/**
	 * Print slide function
	 *
	 * @param array $slide has slide information.
	 *
	 * @param array $settings has slide settings.
	 *
	 * @param int   $element_key has element key.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function print_slide( array $slide, array $settings, $element_key ) {
		$this->add_render_attribute(
			$element_key . '-testimonial',
			array(
				'class' => 'elementor-testimonial',
			)
		);

		$this->add_render_attribute(
			$element_key . '-testimonial',
			array(
				'class' => 'elementor-repeater-item-' . $slide['_id'],
			)
		);

		if ( ! empty( $slide['image']['url'] ) ) {
			$this->add_render_attribute(
				$element_key . '-image',
				array(
					'src' => $this->get_slide_image_url( $slide, $settings ),
					'alt' => ! empty( $slide['name'] ) ? $slide['name'] : '',
				)
			);
		}

		?>
	<div <?php echo wp_kses_post( $this->get_render_attribute_string( $element_key . '-testimonial' ) ); ?>>
		<?php
		if ( $slide['image']['url'] || ! empty( $slide['name'] ) || ! empty( $slide['title'] ) ) :

			$link_url       = empty( $slide['link']['url'] ) ? false : $slide['link']['url'];
			$header_tag     = ! empty( $link_url ) ? 'a' : 'div';
			$header_element = 'header_' . $slide['_id'];

			$this->add_render_attribute( $header_element, 'class', 'responsive-testimonial__header' );

			if ( ! empty( $link_url ) ) {
				$this->add_link_attributes( $header_element, $slide['link'] );
			}
			?>
			<<?php echo esc_attr( $header_tag ); ?> <?php echo wp_kses_post( $this->get_render_attribute_string( $header_element ) ); ?>>
			<?php if ( $slide['image']['url'] ) : ?>
			<div class="responsive-testimonial__image">
				<img <?php echo wp_kses_post( $this->get_render_attribute_string( $element_key . '-image' ) ); ?>>
			</div>
		<?php endif; ?>
			<?php 
				if ( empty( $slide['name'] ) && empty( $slide['title'] ) ) {
					return '';
				}

		echo '<cite class="elementor-testimonial__cite">';

		if ( ! empty( $slide['name'] ) ) {
			echo '<span class="responsive-testimonial__name">' . $slide['name'] . '</span>';
		}

		if ( ! empty( $slide['rating'] ) ) {

		$raw_rating = isset( $slide['rating'] ) ? $slide['rating'] : ( isset( $rating ) ? $rating : 0 );

			// Defensive parse: some code earlier may have used wrong precedence. Force float.
			$rating = floatval( $raw_rating );
		// Font Awesome rendering
		if ( 'star_fontawesome' === $settings['star_style'] ) {
				$style_attr = ( 'outline' === $settings['unmarked_star_style'] ) ? 'data-style="outline"' : 'data-style="filled"';

			// Round to one decimal to avoid floating noise, but keep fraction
			$rating = round( $rating, 1 );

			$floored_rating = floor( $rating );
			$fraction = $rating - $floored_rating;

			// decide half star when fraction is between 0.25 and 0.75 (flexible)
			$has_half_star = ( $fraction >= 0.25 && $fraction < 0.75 );
			echo '<div class="elementor-star-rating">';

			for ( $stars = 1; $stars <= 5; $stars++ ) {
				if ( $stars <= $floored_rating) {
					// Filled star
					$icon = [
						'value'   => 'fas fa-star',
						'library' => 'fa-solid',
					];
					echo '<span class="rael-star rael-star-filled" '.$style_attr.'>';
					\Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
					echo '</span>';

				} elseif ( $has_half_star && $stars == $floored_rating + 1  ) {
					//  Half star (overlay)
					//  Partially filled star (variable width)
					$fill_width = round( $fraction * 100 ); // e.g. 0.7 => 70%

					$icon_filled = [
						'value'   => 'fas fa-star',
						'library' => 'fa-solid',
					];

					$icon_unmarked = ( 'outline' === $settings['unmarked_star_style'] )
						? [ 'value' => 'far fa-star', 'library' => 'fa-regular' ]
						: [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ];
					

					echo '<span class="rael-star rael-star-half" '.$style_attr.'>';
						// Base (unmarked)
						\Elementor\Icons_Manager::render_icon( $icon_unmarked, [ 'aria-hidden' => 'true' ] );
						// Overlay (filled)
						echo '<span class="rael-star-half-overlay" style="width:' . esc_attr( $fill_width ) . '%;">';
							\Elementor\Icons_Manager::render_icon( $icon_filled, [ 'aria-hidden' => 'true' ] );
						echo '</span>';
					echo '</span>';
				} else {
					// Unmarked star
					$icon_type = ( 'outline' === $settings['unmarked_star_style'] ) ? 'far fa-star' : 'fas fa-star';
					$library   = ( 'outline' === $settings['unmarked_star_style'] ) ? 'fa-regular' : 'fa-solid';

					$icon = [
						'value'   => $icon_type,
						'library' => $library,
					];
					echo '<span class="rael-star rael-star-unmarked">';
					\Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
					echo '</span>';
				}
			}

			echo '</div>';
		} elseif ( 'star_unicode' === $settings['star_style'] ) {
			$icon = ( 'outline' === $settings['unmarked_star_style'] ) ? '&#9734;' : '&#9733;';
					
			for ( $stars = 1; $stars <= 5; $stars++ ) {
				$rating_unicode        = (float) $slide['rating'] > 5 ? 5 : $slide['rating'];
				$floored_rating_unicode = (int) $rating_unicode;
				if ( $stars <= $floored_rating_unicode ) {

					$stars_html .= '<i class="elementor-star-full">' . $icon . '</i>';
				} elseif ( $floored_rating_unicode + 1 === $stars && $rating_unicode != $floored_rating_unicode ) { //phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
					$stars_html .= '<i class="elementor-star-' . ( $rating_unicode - $floored_rating_unicode ) * 10 . '">' . $icon . '</i>';
				} else {
					$stars_html .= '<i class="elementor-star-empty">' . $icon . '</i>';
				}
		}
			echo '<div class="elementor-star-rating">' . $stars_html . '</div>';

		}
			
		}

		if ( ! empty( $slide['title'] ) ) {
			echo  '<span class="responsive-testimonial__title">' . $slide['title'] . '</span>';
		}
		echo '</cite>';
			?>
			<?php echo $this->print_icon( $slide, $element_key ); // phpcs:ignore?> 
			</<?php echo esc_attr( $header_tag ); ?>>
		<?php endif; ?>
		<?php if ( $slide['content'] ) : ?>
			<div class="responsive-testimonial__content">
				<div class="responsive-testimonial__text">
					<?php echo wp_kses_post( $slide['content'] ); ?>
				</div>
			</div>
		<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Print slider function
	 *
	 * @param array $settings has slider settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function print_slider( array $settings = null ) {
		if ( null === $settings ) {
			$settings = $this->get_settings_for_display();
		}

		$default_settings = array(
			'container_class' => 'responsive-testimonial-swiper responsive-main-swiper',
			'video_play_icon' => true,
		);

		$settings = array_merge( $default_settings, $settings );

		$slides_count = count( $settings['slides'] );
		?>
		<div class="responsive-swiper">
			<div class="<?php echo esc_attr( $settings['container_class'] ); ?> responsive-reviews swiper<?php echo esc_attr( RAEL_SWIPER_CONTAINER ); ?>">
				<div class="swiper-wrapper">
					<?php
					foreach ( $settings['slides'] as $index => $slide ) :
						++$this->slide_prints_count;
						?>
						<div class="swiper-slide">
							<?php $this->print_slide( $slide, $settings, 'slide-' . $index . '-' . $this->slide_prints_count ); ?>
						</div>
					<?php endforeach; ?>
				</div>
				<?php if ( 1 < $slides_count ) : ?>
					<?php if ( $settings['pagination'] ) : ?>
						<div class="swiper-pagination"></div>
					<?php endif; ?>
					<?php if ( $settings['show_arrows'] ) : ?>
						<?php 
							$pa_next_arrow = 'fa fa-angle-right';
							$pa_prev_arrow = 'fa fa-angle-left';
						?>
						<div class="responsive-swiper-button responsive-swiper-button-prev">
							<i class="<?php echo esc_attr( $pa_prev_arrow ); ?>"></i>
							<span class="elementor-screen-only"><?php esc_html_e( 'Previous', 'responsive-addons-for-elementor' ); ?></span>
						</div>
						<div class="responsive-swiper-button responsive-swiper-button-next">
							<i class="<?php echo esc_attr( $pa_next_arrow ); ?>"></i>
							<span class="elementor-screen-only"><?php esc_html_e( 'Next', 'responsive-addons-for-elementor' ); ?></span>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Get slide image url function
	 *
	 * @param array $slide has slide information.
	 *
	 * @param array $settings has slide settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function get_slide_image_url( $slide, array $settings ) {
		$image_url = Group_Control_Image_Size::get_attachment_image_src( $slide['image']['id'], 'image_size', $settings );

		if ( ! $image_url ) {
			$image_url = $slide['image']['url'];
		}

		return $image_url;
	}

	/**
	 * Render function
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$this->print_slider();
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/reviews/';
	}
}
