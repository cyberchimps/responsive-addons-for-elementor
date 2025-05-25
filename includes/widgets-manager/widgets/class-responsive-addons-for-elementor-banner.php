<?php
/**
 * Banner Widget
 *
 * @since      1.3.1
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Control_Media;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Banner widget class.
 *
 * @since 1.3.1
 */
class Responsive_Addons_For_Elementor_Banner extends Widget_Base {


	/**
	 * $options is option field of select
	 *
	 * @since 1.0.0
	 * @var integer $page_limit
	 */
	protected $options;

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.3.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-banner';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.3.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Banner', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Banner widget icon.
	 *
	 * @since 1.3.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-banner rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the Banner widget belongs to.
	 *
	 * @since 1.3.1
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget Keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @access public
	 *
	 * @return array Widget Keywords.
	 */
	public function get_keywords() {
		return array( 'image', 'box', 'info', 'banner', 'responsive' );
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/banner';
	}

	/**
	 * Get All Posts
	 *
	 * Returns an array of posts/pages
	 *
	 * @since 1.3.1
	 * @access public
	 *
	 * @return $options array posts/pages query
	 */
	public function get_all_posts() {

		$all_posts = get_posts(
			array(
				'posts_per_page' => -1,
				'post_type'      => array( 'page', 'post' ),
			)
		);

		if ( ! empty( $all_posts ) && ! is_wp_error( $all_posts ) ) {
			foreach ( $all_posts as $post ) {
				$this->options[ $post->ID ] = strlen( $post->post_title ) > 20 ? substr( $post->post_title, 0, 20 ) . '...' : $post->post_title;
			}
		}
		return $this->options;
	}

	/**
	 * Register controls for the widget
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'rael_banner_global_settings',
			array(
				'label' => __( 'Image', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_banner_image',
			array(
				'label'         => __( 'Upload Image', 'responsive-addons-for-elementor' ),
				'description'   => __( 'Select an image for the Banner', 'responsive-addons-for-elementor' ),
				'type'          => Controls_Manager::MEDIA,
				'dynamic'       => array( 'active' => true ),
				'default'       => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'show_external' => true,
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'thumbnail',
				'default'   => 'full',
				'separator' => 'none',
			)
		);

		$this->add_control(
			'rael_banner_link_url_switch',
			array(
				'label' => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'rael_banner_image_link_switcher',
			array(
				'label'     => __( 'Custom Link', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array(
					'rael_banner_link_url_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_banner_image_custom_link',
			array(
				'label'         => __( 'Set custom Link', 'responsive-addons-for-elementor' ),
				'type'          => Controls_Manager::URL,
				'dynamic'       => array( 'active' => true ),
				'condition'     => array(
					'rael_banner_image_link_switcher' => 'yes',
					'rael_banner_link_url_switch'     => 'yes',
				),
				'show_external' => false,
			)
		);

		$this->add_control(
			'rael_banner_image_existing_page_link',
			array(
				'label'       => __( 'Existing Page', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'condition'   => array(
					'rael_banner_image_link_switcher!' => 'yes',
					'rael_banner_link_url_switch'      => 'yes',
				),
				'label_block' => true,
				'multiple'    => false,
				'options'     => $this->get_all_posts(),
			)
		);

		$this->add_control(
			'rael_banner_link_title',
			array(
				'label'     => __( 'Link Title', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'rael_banner_link_url_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_banner_image_link_open_new_tab',
			array(
				'label'     => __( 'New Tab', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array(
					'rael_banner_link_url_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_banner_image_link_add_nofollow',
			array(
				'label'     => __( 'Nofollow Option', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array(
					'rael_banner_link_url_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_banner_image_animation',
			array(
				'label'   => __( 'Effect', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'animation1',
				'options' => array(
					'animation1'  => __( 'Effect 1', 'responsive-addons-for-elementor' ),
					'animation5'  => __( 'Effect 2', 'responsive-addons-for-elementor' ),
					'animation13' => __( 'Effect 3', 'responsive-addons-for-elementor' ),
					'animation2'  => __( 'Effect 4', 'responsive-addons-for-elementor' ),
					'animation4'  => __( 'Effect 5', 'responsive-addons-for-elementor' ),
					'animation6'  => __( 'Effect 6', 'responsive-addons-for-elementor' ),
					'animation7'  => __( 'Effect 7', 'responsive-addons-for-elementor' ),
					'animation8'  => __( 'Effect 8', 'responsive-addons-for-elementor' ),
					'animation9'  => __( 'Effect 9', 'responsive-addons-for-elementor' ),
					'animation10' => __( 'Effect 10', 'responsive-addons-for-elementor' ),
					'animation11' => __( 'Effect 11', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_banner_active',
			array(
				'label' => __( 'Always Hovered', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'rael_banner_hover_effect',
			array(
				'label'   => __( 'Hover Effect', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'none'      => __( 'None', 'responsive-addons-for-elementor' ),
					'zoomin'    => __( 'Zoom In', 'responsive-addons-for-elementor' ),
					'zoomout'   => __( 'Zoom Out', 'responsive-addons-for-elementor' ),
					'scale'     => __( 'Scale', 'responsive-addons-for-elementor' ),
					'grayscale' => __( 'Grayscale', 'responsive-addons-for-elementor' ),
					'blur'      => __( 'Blur', 'responsive-addons-for-elementor' ),
					'bright'    => __( 'Bright', 'responsive-addons-for-elementor' ),
					'sepia'     => __( 'Sepia', 'responsive-addons-for-elementor' ),
				),
				'default' => 'none',
			)
		);

		$this->add_control(
			'rael_banner_height',
			array(
				'label'   => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'default' => __( 'Default', 'responsive-addons-for-elementor' ),
					'custom'  => __( 'Custom', 'responsive-addons-for-elementor' ),
				),
				'default' => 'default',
			)
		);

		$this->add_responsive_control(
			'rael_banner_custom_height',
			array(
				'label'     => __( 'Min Height', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'condition' => array(
					'rael_banner_height' => 'custom',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-banner-image-banner' => 'height: {{VALUE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'rael_banner_img_vertical_align',
			array(
				'label'     => __( 'Vertical Align', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'condition' => array(
					'rael_banner_height' => 'custom',
				),
				'options'   => array(
					'flex-start' => __( 'Top', 'responsive-addons-for-elementor' ),
					'center'     => __( 'Middle', 'responsive-addons-for-elementor' ),
					'flex-end'   => __( 'Bottom', 'responsive-addons-for-elementor' ),
					'inherit'    => __( 'Full', 'responsive-addons-for-elementor' ),
				),
				'default'   => 'flex-start',
				'selectors' => array(
					'{{WRAPPER}} .rael-banner-img-wrap' => 'align-items: {{VALUE}}; -webkit-align-items: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_mouse_tilt',
			array(
				'label'        => __( 'Enable Mouse Tilt', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
			)
		);

		$this->add_control(
			'rael_mouse_tilt_rev',
			array(
				'label'        => __( 'Reverse', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'condition'    => array(
					'rael_mouse_tilt' => 'true',
				),
			)
		);

		$this->add_control(
			'rael_banner_extra_class',
			array(
				'label'   => __( 'Extra Class', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_banner_image_section',
			array(
				'label' => __( 'Content', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_banner_title',
			array(
				'label'       => __( 'Title', 'responsive-addons-for-elementor' ),
				'placeholder' => __( 'Give a title to this banner', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'default'     => __( 'RAE Banner', 'responsive-addons-for-elementor' ),
				'label_block' => false,
			)
		);

		$this->add_control(
			'rael_banner_title_tag',
			array(
				'label'       => __( 'HTML Tag', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'h3',
				'options'     => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
				'label_block' => true,
			)
		);

		$this->add_control(
			'rael_banner_description_hint',
			array(
				'label' => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_banner_description',
			array(
				'label'       => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::WYSIWYG,
				'dynamic'     => array( 'active' => true ),
				'default'     => __( 'RAE Banner gives you a wide range of attractive and interactive banners with lots of customizable options', 'responsive-addons-for-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'rael_banner_link_switcher',
			array(
				'label'     => __( 'Button', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array(
					'rael_banner_link_url_switch!' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_banner_more_text',
			array(
				'label'     => __( 'Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array( 'active' => true ),
				'default'   => 'Click Here',
				'condition' => array(
					'rael_banner_link_switcher'    => 'yes',
					'rael_banner_link_url_switch!' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_banner_link_selection',
			array(
				'label'       => __( 'Link Type', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'url'  => __( 'URL', 'responsive-addons-for-elementor' ),
					'link' => __( 'Existing Page', 'responsive-addons-for-elementor' ),
				),
				'default'     => 'url',
				'label_block' => true,
				'condition'   => array(
					'rael_banner_link_switcher'    => 'yes',
					'rael_banner_link_url_switch!' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_banner_link',
			array(
				'label'       => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array( 'active' => true ),
				'default'     => array(
					'url' => '#',
				),
				'placeholder' => 'https://cyberchimps.com/',
				'label_block' => true,
				'condition'   => array(
					'rael_banner_link_selection'   => 'url',
					'rael_banner_link_switcher'    => 'yes',
					'rael_banner_link_url_switch!' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_banner_existing_link',
			array(
				'label'       => __( 'Existing Page', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->get_all_posts(),
				'multiple'    => false,
				'condition'   => array(
					'rael_banner_link_selection'   => 'link',
					'rael_banner_link_switcher'    => 'yes',
					'rael_banner_link_url_switch!' => 'yes',
				),
				'label_block' => true,
			)
		);

		$this->add_control(
			'rael_banner_title_text_align',
			array(
				'label'     => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'left',
				'toggle'    => false,
				'selectors' => array(
					'{{WRAPPER}} .rael-banner-image-banner-title, {{WRAPPER}} .rael-banner-image-banner-content, {{WRAPPER}} .rael-banner-read-more'   => 'text-align: {{VALUE}} ;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_banner_responsive_section',
			array(
				'label' => __( 'Responsive', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_banner_responsive_switcher',
			array(
				'label'       => __( 'Responsive Controls', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => __( 'If the description text is not suiting well on specific screen sizes, you may enable this option which will hide the description text.', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_banner_min_range',
			array(
				'label'       => __( 'Minimum Size', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => __( 'Note: minimum size for extra small screens is 1px.', 'responsive-addons-for-elementor' ),
				'default'     => 1,
				'condition'   => array(
					'rael_banner_responsive_switcher' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_banner_max_range',
			array(
				'label'       => __( 'Maximum Size', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => __( 'Note: maximum size for extra small screens is 767px.', 'responsive-addons-for-elementor' ),
				'default'     => 767,
				'condition'   => array(
					'rael_banner_responsive_switcher' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_banner_opacity_style',
			array(
				'label' => __( 'General', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_banner_image_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-banner-image-banner' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_banner_image_opacity',
			array(
				'label'     => __( 'Image Opacity', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 1,
				),
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => .1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-banner-image-banner img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_control(
			'rael_banner_image_hover_opacity',
			array(
				'label'     => __( 'Hover Opacity', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 1,
				),
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => .1,
					),
				),
				'separator' => 'after',
				'selectors' => array(
					'{{WRAPPER}} .rael-banner-image-banner img.active' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_control(
			'rael_banner_title_border_width',
			array(
				'label'      => __( 'Hover Border Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'condition'  => array(
					'rael_banner_image_animation' => array( 'animation13', 'animation9', 'animation10' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-banner-animation13 .rael-banner-image-banner-title::after'    => 'height: {{size}}{{unit}};',
					'{{WRAPPER}} .rael-banner-animation9 .rael-banner-image-banner-desc::before, {{WRAPPER}} .rael-banner-animation9 .rael-banner-image-banner-desc::after'    => 'height: {{size}}{{unit}};',
					'{{WRAPPER}} .rael-banner-animation10 .rael-banner-image-banner-title::after'    => 'height: {{size}}{{unit}};',
				),
			)
		);

		$this->add_control(
			'rael_banner_style3_title_border',
			array(
				'label'     => __( 'Hover Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'rael_banner_image_animation' => array( 'animation13', 'animation9', 'animation10' ),
				),
				'separator' => 'after',
				'selectors' => array(
					'{{WRAPPER}} .rael-banner-animation13 .rael-banner-image-banner-title::after'    => 'background: {{VALUE}};',
					'{{WRAPPER}} .rael-banner-animation9 .rael-banner-image-banner-desc::before, {{WRAPPER}} .rael-banner-animation9 .rael-banner-image-banner-desc::after'    => 'background: {{VALUE}};',
					'{{WRAPPER}} .rael-banner-animation10 .rael-banner-image-banner-title::after'    => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_banner_inner_border_width',
			array(
				'label'      => __( 'Hover Border Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'condition'  => array(
					'rael_banner_image_animation' => array( 'animation4', 'animation6', 'animation7', 'animation8' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-banner-animation4 .rael-banner-image-banner-desc::after, {{WRAPPER}} .rael-banner-animation4 .rael-banner-image-banner-desc::before, {{WRAPPER}} .rael-banner-animation6 .rael-banner-image-banner-desc::before' => 'border-width: {{size}}{{unit}};',
					'{{WRAPPER}} .rael-banner-animation7 .rael-banner-br.rael-banner-bleft, {{WRAPPER}} .rael-banner-animation7 .rael-banner-br.rael-banner-bright , {{WRAPPER}} .rael-banner-animation8 .rael-banner-br.rael-banner-bright,{{WRAPPER}} .rael-banner-animation8 .rael-banner-br.rael-banner-bleft' => 'width: {{size}}{{unit}};',
					'{{WRAPPER}} .rael-banner-animation7 .rael-banner-br.rael-banner-btop, {{WRAPPER}} .rael-banner-animation7 .rael-banner-br.rael-banner-bottom , {{WRAPPER}} .rael-banner-animation8 .rael-banner-br.rael-banner-bottom,{{WRAPPER}} .rael-banner-animation8 .rael-banner-br.rael-banner-btop ' => 'height: {{size}}{{unit}};',
				),
			)
		);

		$this->add_control(
			'rael_banner_scaled_border_color',
			array(
				'label'     => __( 'Hover Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'rael_banner_image_animation' => array( 'animation4', 'animation6', 'animation7', 'animation8' ),
				),
				'separator' => 'after',
				'selectors' => array(
					'{{WRAPPER}} .rael-banner-animation4 .rael-banner-image-banner-desc::after, {{WRAPPER}} .rael-banner-animation4 .rael-banner-image-banner-desc::before, {{WRAPPER}} .rael-banner-animation6 .rael-banner-image-banner-desc::before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .rael-banner-animation7 .rael-banner-br, {{WRAPPER}} .rael-banner-animation8 .rael-banner-br' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} .rael-banner-image-banner img',
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'hover_css_filters',
				'label'    => __( 'Hover CSS Filters', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-banner-image-banner img.active',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_banner_image_border',
				'selector' => '{{WRAPPER}} .rael-banner-image-banner',
			)
		);

		$this->add_responsive_control(
			'rael_banner_image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-banner-image-banner' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_blend_mode',
			array(
				'label'     => __( 'Blend Mode', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''            => __( 'Normal', 'responsive-addons-for-elementor' ),
					'multiply'    => 'Multiply',
					'screen'      => 'Screen',
					'overlay'     => 'Overlay',
					'darken'      => 'Darken',
					'lighten'     => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation'  => 'Saturation',
					'color'       => 'Color',
					'luminosity'  => 'Luminosity',
				),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .rael-banner-image-banner' => 'mix-blend-mode: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_banner_title_style',
			array(
				'label' => __( 'Title', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_banner_color_of_title',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-banner-image-banner-desc .rael_banner_title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_banner_title_typography',
				'selector' => '{{WRAPPER}} .rael-banner-image-banner-desc .rael_banner_title',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			)
		);

		$this->add_control(
			'rael_banner_style2_title_background',
			array(
				'label'       => __( 'Background', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#f2f2f2',
				'description' => __( 'Choose a background color for the title', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_banner_image_animation' => 'animation5',
				),
				'selectors'   => array(
					'{{WRAPPER}} .rael-banner-animation5 .rael-banner-image-banner-desc'    => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'label'    => __( 'Shadow', 'responsive-addons-for-elementor' ),
				'name'     => 'rael_banner_title_shadow',
				'selector' => '{{WRAPPER}} .rael-banner-image-banner-desc .rael_banner_title',
			)
		);

		$this->add_responsive_control(
			'rael_banner_title_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-banner-image-banner-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_banner_styles_of_content',
			array(
				'label' => __( 'Description', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_banner_color_of_content',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-banner .rael_banner_content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_banner_content_typhography',
				'selector' => '{{WRAPPER}} .rael-banner .rael_banner_content',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'label'    => __( 'Shadow', 'responsive-addons-for-elementor' ),
				'name'     => 'rael_banner_description_shadow',
				'selector' => '{{WRAPPER}} .rael-banner .rael_banner_content',
			)
		);

		$this->add_responsive_control(
			'rael_banner_desc_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-banner-image-banner-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_banner_styles_of_button',
			array(
				'label'     => __( 'Button', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_banner_link_switcher'    => 'yes',
					'rael_banner_link_url_switch!' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_banner_color_of_button',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-banner .rael-banner-link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_banner_hover_color_of_button',
			array(
				'label'     => __( 'Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-banner .rael-banner-link:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_banner_button_typhography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-banner-link',
			)
		);

		$this->add_control(
			'rael_banner_backcolor_of_button',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-banner .rael-banner-link' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_banner_hover_backcolor_of_button',
			array(
				'label'     => __( 'Hover Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-banner .rael-banner-link:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_banner_button_border',
				'selector' => '{{WRAPPER}} .rael-banner .rael-banner-link',
			)
		);

		$this->add_control(
			'rael_banner_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-banner-link' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'label'    => __( 'Shadow', 'responsive-addons-for-elementor' ),
				'name'     => 'rael_banner_button_shadow',
				'selector' => '{{WRAPPER}} .rael-banner .rael-banner-link',
			)
		);

		$this->add_responsive_control(
			'rael_banner_button_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-banner-read-more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_banner_button_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-banner-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_banner_container_style',
			array(
				'label' => __( 'Container', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_banner_border',
				'selector' => '{{WRAPPER}} .rael-banner',
			)
		);

		$this->add_control(
			'rael_banner_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-banner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_banner_shadow',
				'selector' => '{{WRAPPER}} .rael-banner',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'gradient_color',
				'types'     => array( 'gradient' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .rael-banner-gradient:before, {{WRAPPER}} .rael-banner-gradient:after',
				'condition' => array(
					'rael_banner_image_animation' => 'animation11',
				),
			)
		);

		$this->add_control(
			'rael_first_layer_speed',
			array(
				'label'     => __( 'First Layer Transition Speed (sec)', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 0.3,
				),
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 3,
						'step' => .1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-banner-animation11:hover .rael-banner-gradient:after' => 'transition-delay: {{SIZE}}s',
					'{{WRAPPER}} .rael-banner-animation11 .rael-banner-gradient:before' => 'transition: transform 0.3s ease-out {{SIZE}}s',
				),
				'condition' => array(
					'rael_banner_image_animation' => 'animation11',
				),
			)
		);

		$this->add_control(
			'rael_second_layer_speed',
			array(
				'label'     => __( 'Second Layer Transition Delay (sec)', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 0.15,
				),
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 3,
						'step' => .1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-banner-animation11:hover .rael-banner-gradient:before' => 'transition-delay: {{SIZE}}s',
					'{{WRAPPER}} .rael-banner-animation11 .rael-banner-gradient:after' => 'transition: transform 0.3s ease-out {{SIZE}}s',

				),
				'condition' => array(
					'rael_banner_image_animation' => 'animation11',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render function
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'banner', 'id', 'rael-banner-' . $this->get_id() );
		$this->add_render_attribute( 'banner', 'class', 'rael-banner' );

		if ( 'true' === $settings['rael_mouse_tilt'] ) {
			$this->add_render_attribute( 'banner', 'data-box-tilt', 'true' );
			if ( 'true' === $settings['rael_mouse_tilt_rev'] ) {
				$this->add_render_attribute( 'banner', 'data-box-tilt-reverse', 'true' );
			}
		}

		$this->add_inline_editing_attributes( 'rael_banner_title' );
		$this->add_render_attribute(
			'rael_banner_title',
			'class',
			array(
				'rael-banner-image-banner-title',
				'rael_banner_title',
			)
		);

		$this->add_inline_editing_attributes( 'rael_banner_description', 'advanced' );

		$title_tag  = Helper::validate_html_tags( $settings['rael_banner_title_tag'] );
		$title      = $settings['rael_banner_title'];
		$full_title = '<div class="rael-banner-title-wrap"><' . wp_kses_post( $title_tag ) . ' ' . $this->get_render_attribute_string( 'rael_banner_title' ) . '>' . $title . '</' . wp_kses_post( $title_tag ) . '></div>';

		$link = 'yes' === $settings['rael_banner_image_link_switcher'] ? $settings['rael_banner_image_custom_link']['url'] : get_permalink( $settings['rael_banner_image_existing_page_link'] );

		$link_title = 'yes' === $settings['rael_banner_link_url_switch'] ? $settings['rael_banner_link_title'] : '';

		$open_new_tab    = 'yes' === $settings['rael_banner_image_link_open_new_tab'] ? ' target="_blank"' : '';
		$nofollow_link   = 'yes' === $settings['rael_banner_image_link_add_nofollow'] ? ' rel="nofollow"' : '';
		$full_link       = '<a class="rael-banner-image-banner-link" href="' . $link . '" title="' . $link_title . '"' . $open_new_tab . $nofollow_link . '></a>';
		$animation_class = 'rael-banner-' . $settings['rael_banner_image_animation'];
		$hover_class     = ' ' . $settings['rael_banner_hover_effect'];
		$extra_class     = ! empty( $settings['rael_banner_extra_class'] ) ? ' ' . $settings['rael_banner_extra_class'] : '';
		$active          = 'yes' === $settings['rael_banner_active'] ? ' active' : '';
		$full_class      = $animation_class . $hover_class . $extra_class . $active;
		$min_size        = $settings['rael_banner_min_range'] . 'px';
		$max_size        = $settings['rael_banner_max_range'] . 'px';

		$banner_url = 'url' === $settings['rael_banner_link_selection'] ? $settings['rael_banner_link']['url'] : get_permalink( $settings['rael_banner_existing_link'] );

		$image_html = '';

		if ( ! empty( $settings['rael_banner_image']['url'] ) ) {

			$this->add_render_attribute(
				'image',
				array(
					'src'   => $settings['rael_banner_image']['url'],
					'alt'   => Control_Media::get_image_alt( $settings['rael_banner_image'] ),
					'title' => Control_Media::get_image_title( $settings['rael_banner_image'] ),
				)
			);

			$image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'rael_banner_image' );

		}

		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'banner' ) ); ?>>
			<div class="rael-banner-image-banner <?php echo esc_attr( $full_class ); ?> rael-banner-min-height">
				<?php if ( 'animation7' === $settings['rael_banner_image_animation'] || 'animation8' === $settings['rael_banner_image_animation'] ) : ?>
					<div class="rael-banner-border">
						<div class="rael-banner-br rael-banner-bleft rael-banner-brlr"></div>
						<div class="rael-banner-br rael-banner-bright rael-banner-brlr"></div>
						<div class="rael-banner-br rael-banner-btop rael-banner-brtb"></div>
						<div class="rael-banner-br rael-banner-bottom rael-banner-brtb"></div>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $settings['rael_banner_image']['url'] ) ) : ?>
					<?php if ( 'custom' === $settings['rael_banner_height'] ) : ?>
						<div class="rael-banner-img-wrap">
					<?php endif; ?>
					<?php echo wp_kses_post( $image_html ); ?>
					<?php if ( 'custom' === $settings['rael_banner_height'] ) : ?>
						</div>
						<?php
					endif;
				endif;
				?>
				<?php if ( 'animation11' === $settings['rael_banner_image_animation'] ) : ?>
					<div class="rael-banner-gradient"></div>
				<?php endif; ?>
				<div class="rael-banner-image-banner-desc">
					<div class="rael-banner-desc-centered">
						<?php
						echo wp_kses_post( $full_title );
						if ( ! empty( $settings['rael_banner_description'] ) ) :
							?>
							<div class="rael-banner-image-banner-content rael_banner_content">
								<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_banner_description' ) ); ?>>
									<?php echo wp_kses_post( $this->parse_text_editor( $settings['rael_banner_description'] ) ); ?>
								</div>
							</div>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['rael_banner_link_switcher'] && ! empty( $settings['rael_banner_more_text'] ) ) : ?>
							<div class="rael-banner-read-more">
								<a class="rael-banner-link"
									<?php
									if ( ! empty( $banner_url ) ) :
										?>
										href="<?php echo esc_url( $banner_url ); ?>"<?php endif; ?>
									<?php if ( ! empty( $settings['rael_banner_link']['is_external'] ) ) : ?>
										target="_blank"
									<?php endif; ?>
									<?php if ( ! empty( $settings['rael_banner_link']['nofollow'] ) ) : ?>
										rel="nofollow"
									<?php endif; ?>>
									<?php echo esc_html( $settings['rael_banner_more_text'] ); ?>
								</a>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<?php
				if ( 'yes' === $settings['rael_banner_link_url_switch'] && ( ! empty( $settings['rael_banner_image_custom_link']['url'] ) || ! empty( $settings['rael_banner_image_existing_page_link'] ) ) ) {
					echo wp_kses_post( $full_link );
				}
				?>
			</div>
			<?php if ( 'yes' === $settings['rael_banner_responsive_switcher'] ) : ?>
				<style>
					@media( min-width: <?php echo wp_kses_post( $min_size ); ?> ) and (max-width:<?php echo wp_kses_post( $max_size ); ?> ) {
						#rael-banner-<?php echo esc_attr( $this->get_id() ); ?> .rael-banner-image-banner-content {
							display: none;
						}
					}
				</style>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Content template for widget
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#

		view.addRenderAttribute( 'banner', 'id', 'rael-banner-' + view.getID() );
		view.addRenderAttribute( 'banner', 'class', 'rael-banner' );

		if( 'true' === settings.rael_mouse_tilt ) {
			view.addRenderAttribute( 'banner', 'data-box-tilt', 'true' );
			if( 'true' === settings.rael_mouse_tilt_rev ) {
				view.addRenderAttribute( 'banner', 'data-box-tilt-reverse', 'true' );
			}
		}

		var active = 'yes' === settings.rael_banner_active ? 'active' : '';

		view.addRenderAttribute( 'banner_inner', 'class', [
			'rael-banner-image-banner',
			'rael-banner-min-height',
			'rael-banner-' + settings.rael_banner_image_animation,
			settings.rael_banner_hover_effect,
			settings.rael_banner_extra_class,
			active
		] );

		var titleTag = elementor.helpers.validateHTMLTag( settings.rael_banner_title_tag ),
		title    = settings.rael_banner_title;

		view.addRenderAttribute( 'rael_banner_title', 'class', [
			'rael-banner-image-banner-title',
			'rael_banner_title'
		] );

		view.addInlineEditingAttributes( 'rael_banner_title' );

		var description = settings.rael_banner_description;

		view.addInlineEditingAttributes( 'description', 'advanced' );

		var linkSwitcher = settings.rael_banner_link_switcher,
		readMore     = settings.rael_banner_more_text,
		bannerUrl    = 'url' === settings.rael_banner_link_selection ? settings.rael_banner_link.url : settings.rael_banner_existing_link;

		var bannerLink = 'yes' === settings.rael_banner_image_link_switcher ? settings.rael_banner_image_custom_link.url : settings.rael_banner_image_existing_page_link,
		linkTitle = 'yes' === settings.rael_banner_link_url_switch ? settings.rael_banner_link_title : '';

		var minSize = settings.rael_banner_min_range + 'px',
		maxSize = settings.rael_banner_max_range + 'px';

		var imageHtml = '';
		if ( settings.rael_banner_image.url ) {
			var image = {
				id: settings.rael_banner_image.id,
				url: settings.rael_banner_image.url,
				size: settings.thumbnail_size,
				dimension: settings.thumbnail_custom_dimension,
				model: view.getEditModel()
			};

			var image_url = elementor.imagesManager.getImageUrl( image );

			imageHtml = '<img src="' + image_url + '"/>';

		}

		#>

		<div {{{ view.getRenderAttributeString( 'banner' ) }}}>
		<div {{{ view.getRenderAttributeString( 'banner_inner' ) }}}>

		<# if (settings.rael_banner_image_animation ==='animation7' || settings.rael_banner_image_animation ==='animation8'){ #>
			<div class="rael-banner-border">
				<div class="rael-banner-br rael-banner-bleft rael-banner-brlr"></div>
				<div class="rael-banner-br rael-banner-bright rael-banner-brlr"></div>
				<div class="rael-banner-br rael-banner-btop rael-banner-brtb"></div>
				<div class="rael-banner-br rael-banner-bottom rael-banner-brtb"></div>
			</div>
		<# } #>

		<# if( '' !== settings.rael_banner_image.url ) { #>
			<# if( 'custom' === settings.rael_banner_height ) { #>
				<div class="rael-banner-img-wrap">
					<# } #>
					{{{imageHtml}}}
					<# if( 'custom' === settings.rael_banner_height ) { #>
				</div>
			<# } #>
		<# } #>

		<# if( 'animation11' === settings.rael_banner_image_animation ) { #>
			<div class="rael-banner-gradient"></div>
			<# } #>
			<div class="rael-banner-image-banner-desc">
				<div class="rael-banner-desc-centered">
					<# if( '' !== title ) { #>
					<div class="rael-banner-title-wrap">
						<{{{titleTag}}} {{{ view.getRenderAttributeString('rael_banner_title') }}}>{{{ title }}}</{{{titleTag}}}>
					</div>
				<# } #>
				<# if( '' !== description ) { #>
					<div class="rael-banner-image-banner-content rael_banner_content">
						<div {{{ view.getRenderAttributeString( 'description' ) }}}>{{{ description }}}</div>
					</div>
				<# } #>

				<# if( 'yes' === linkSwitcher && '' !== readMore ) { #>
					<div class="rael-banner-read-more">
						<a class="rael-banner-link" href="{{ bannerUrl }}">{{{ readMore }}}</a>
					</div>
				<# } #>
				</div>
			</div>

		<# if( 'yes' === settings.rael_banner_link_url_switch  && ( '' !== settings.rael_banner_image_custom_link.url || '' !== settings.rael_banner_image_existing_page_link ) ) { #>
			<a class="rael-banner-image-banner-link" href="{{ bannerLink }}" title="{{ linkTitle }}"></a>
		<# } #>
		</div>
		<# if( 'yes' === settings.rael_banner_responsive_switcher ) { #>
			<style>
				@media( min-width: {{minSize}} ) and ( max-width: {{maxSize}} ) {
					#rael-banner-{{ view.getID() }} .rael-banner-image-banner-content {
						display: none;
					}
				}
			</style>
		<# } #>
		</div>
		<?php
	}

}
