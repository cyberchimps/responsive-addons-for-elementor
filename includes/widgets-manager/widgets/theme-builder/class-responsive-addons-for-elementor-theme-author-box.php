<?php
/**
 * RAEL Theme Builder's Author Box Widget.
 *
 * @package Responsive_Addons_For_Elementor
 *
 * @since 1.4.0
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;
use Responsive_Addons_For_Elementor\Helper\Helper;

/**
 * Widget class for the RAEL Author Box in the Theme Builder.
 *
 * @package Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder
 */
class Responsive_Addons_For_Elementor_Theme_Author_Box extends Widget_Base {
	/**
	 * Get the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-theme-author-box';
	}

	/**
	 * Get the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Author Box', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-person rael-badge';
	}

	/**
	 * Get the widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get the keywords associated with the widget.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array(
			'author',
			'user',
			'admin',
			'profile',
			'biography',
			'avatar',
			'testimonial',
			'rael',
		);
	}

	/**
	 * Register controls for archive title widget
	 */
	protected function register_controls() {
		$this->register_content_tab_author_info();
		$this->register_style_tab_image();
		$this->register_style_tab_text();
		$this->register_style_tab_button();
	}

	/**
	 * Registers the content tab for author info.
	 *
	 * @access protected
	 */
	protected function register_content_tab_author_info() {
		$this->start_controls_section(
			'rael_ab_content_tab_author_info_section',
			array(
				'label' => __( 'Author Info', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_ab_source',
			array(
				'label'   => __( 'Source', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'current',
				'options' => array(
					'current' => __( 'Current Author', 'responsive-addons-for-elementor' ),
					'custom'  => __( 'Custom', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_ab_show_avatar',
			array(
				'label'        => __( 'Profile Picture', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'separator'    => 'before',
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'default'      => 'yes',
				'return_value' => 'yes',
				'render_type'  => 'template',
				'prefix_class' => 'rael-author-box--avatar-',
				'condition'    => array(
					'rael_ab_source!' => 'custom',
				),
			)
		);

		$this->add_control(
			'rael_ab_author_avatar',
			array(
				'label'     => __( 'Profile Picture', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'separator' => 'before',
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'rael_ab_source' => 'custom',
				),
			)
		);

		$this->add_control(
			'rael_ab_show_name',
			array(
				'label'        => __( 'Display Name', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'default'      => 'yes',
				'render_type'  => 'template',
				'separator'    => 'before',
				'return_value' => 'yes',
				'prefix_class' => 'rael-author-box--name-',
				'condition'    => array(
					'rael_ab_source!' => 'custom',
				),
			)
		);

		$this->add_control(
			'rael_ab_author_name',
			array(
				'label'     => __( 'Name', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Jim Doe', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
				'condition' => array(
					'rael_ab_source' => 'custom',
				),
			)
		);

		$this->add_control(
			'rael_ab_author_name_tag',
			array(
				'label'   => __( 'HTML Tag', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h4',
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
				),
			)
		);

		$this->add_control(
			'rael_ab_link_to',
			array(
				'label'       => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'description' => __( 'Link for the Author Name and Image', 'responsive-addons-for-elementor' ),
				'options'     => array(
					''              => __( 'None', 'responsive-addons-for-elementor' ),
					'website'       => __( 'Website', 'responsive-addons-for-elementor' ),
					'posts_archive' => __( 'Posts Archive', 'responsive-addons-for-elementor' ),
				),
				'condition'   => array(
					'rael_ab_source!' => 'custom',
				),
			)
		);

		$this->add_control(
			'rael_ab_show_biography',
			array(
				'label'        => __( 'Show Biography', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'default'      => 'yes',
				'return_value' => 'yes',
				'render_type'  => 'template',
				'separator'    => 'before',
				'prefix_class' => 'rael-author-box--biography-',
				'condition'    => array(
					'rael_ab_source!' => 'custom',
				),
			)
		);

		$this->add_control(
			'rael_ab_show_link',
			array(
				'label'        => __( 'Archive Button', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'default'      => 'no',
				'return_value' => 'yes',
				'render_type'  => 'template',
				'prefix_class' => 'rael-author-box--link-',
				'condition'    => array(
					'rael_ab_source!' => 'custom',
				),
			)
		);

		$this->add_control(
			'rael_ab_author_website',
			array(
				'label'       => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'responsive-addons-for-elementor' ),
				'description' => __( 'Link for the Author Name and Image', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_ab_source' => 'custom',
				),
			)
		);

		$this->add_control(
			'rael_ab_author_bio',
			array(
				'label'     => __( 'Biography', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXTAREA,
				'default'   => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'responsive-addons-for-elementor' ),
				'rows'      => 3,
				'separator' => 'before',
				'condition' => array(
					'rael_ab_source' => 'custom',
				),
			)
		);

		$this->add_control(
			'rael_ab_posts_url',
			array(
				'label'       => __( 'Archive Button', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_ab_source' => 'custom',
				),
			)
		);

		$this->add_control(
			'rael_ab_link_text',
			array(
				'label'   => __( 'Archive Text', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'All Posts', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_ab_layout',
			array(
				'label'        => __( 'Layout', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'  => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'above' => array(
						'title' => __( 'Above', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'right' => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'separator'    => 'before',
				'prefix_class' => 'rael-author-box--layout-image-',
			)
		);

		$this->add_control(
			'rael_ab_alignment',
			array(
				'label'        => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Above', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'prefix_class' => 'rael-author-box--align-',
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Registers the style tab for author image.
	 *
	 * @access protected
	 */
	protected function register_style_tab_image() {
		$this->start_controls_section(
			'rael_ab_style_tab_image_section',
			array(
				'label' => __( 'Image', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_ab_image_vertical_align',
			array(
				'label'        => __( 'Vertical Align', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'top'    => array(
						'title' => __( 'Top', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					),
				),
				'prefix_class' => 'rael-author-box--image-valign-',
				'condition'    => array(
					'rael_ab_layout!' => 'above',
				),
			)
		);

		$this->add_responsive_control(
			'rael_ab_image_size',
			array(
				'label'     => __( 'Image Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-author-box__avatar img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_ab_image_gap',
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
					'body.rtl {{WRAPPER}}.rael-author-box--layout-image-left .rael-author-box__avatar,
                     body:not(.rtl) {{WRAPPER}}:not(.rael-author-box--layout-image-above) .rael-author-box__avatar' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: 0;',

					'body:not(.rtl) {{WRAPPER}}.rael-author-box--layout-image-right .rael-author-box__avatar,
                     body.rtl {{WRAPPER}}:not(.rael--author-box--layout-image-above) .rael-author-box__avatar' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',

					'{{WRAPPER}}.rael-author-box--layout-image-above .rael-author-box__avatar' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_ab_image_border',
			array(
				'label'     => __( 'Border', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => array(
					'{{WRAPPER}} .rael-author-box__avatar img' => 'border-style: solid;',
				),
			)
		);

		$this->add_control(
			'rael_ab_image_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .rael-author-box__avatar img' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_ab_image_border' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_ab_image_border_width',
			array(
				'label'     => __( 'Border Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-author-box__avatar img' => 'border-width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_ab_image_border' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_ab_image_border_radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .rael-author-box__avatar img' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'           => 'rael_ab_input_box_shadow',
				'selector'       => '{{WRAPPER}} .rael-author-box__avatar img',
				'fields_options' => array(
					'box_shadow_type' => array(
						'separator' => 'default',
					),
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Registers the style tab for author text.
	 *
	 * @access protected
	 */
	protected function register_style_tab_text() {
		$this->start_controls_section(
			'rael_ab_style_tab_text_section',
			array(
				'label' => __( 'Text', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_ab_heading_name_style',
			array(
				'label'     => __( 'Name', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_ab_name_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-author-box__name' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_ab_name_typography',
				'selector' => '{{WRAPPER}} .rael-author-box__name',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
			)
		);

		$this->add_responsive_control(
			'rael_ab_name_gap',
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
					'{{WRAPPER}} .rael-author-box__name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_ab_heading_bio_style',
			array(
				'label'     => __( 'Biography', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_ab_bio_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-author-box__bio' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_ab_bio_typography',
				'selector' => '{{WRAPPER}} .rael-author-box__bio',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
			)
		);

		$this->add_responsive_control(
			'rael_ab_bio_gap',
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
					'{{WRAPPER}} .rael-author-box__bio' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Registers the style tab for button.
	 *
	 * @access protected
	 */
	protected function register_style_tab_button() {
		$this->start_controls_section(
			'rael_ab_style_tab_button_section',
			array(
				'label' => __( 'Button', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'rael_ab_button_style_tabs' );

		$this->start_controls_tab(
			'rael_ab_button_tab_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_ab_button_text_color_normal',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-author-box__button' => 'color: {{VALUE}}; border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_ab_button_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-author-box__button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_ab_button_typography_normal',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector' => '{{WRAPPER}} .rael-author-box__button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_ab_button_style_tab_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_ab_button_color_hover',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-author-box__button:hover' => 'color: {{VALUE}}; border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_ab_button_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-author-box__button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_ab_button_animation_hover',
			array(
				'label' => __( 'Animation', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'rael_ab_button_border_width',
			array(
				'label'     => __( 'Border Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .rael-author-box__button' => 'border-width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_ab_link_text!' => '',
				),
			)
		);

		$this->add_control(
			'rael_ab_button_border_radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .rael-author-box__button' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_ab_link_text!' => '',
				),
			)
		);

		$this->add_control(
			'rael_ab_button_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .rael-author-box__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Renders content on frontend
	 */
	protected function render() {
		$settings        = $this->get_active_settings();
		$author          = array();
		$link_tag        = 'div';
		$link_url        = '';
		$link_target     = '';
		$author_name_tag = $settings['rael_ab_author_name_tag'];
		$custom_src      = 'custom' === $settings['rael_ab_source'];

		if ( 'current' === $settings['rael_ab_source'] ) {
			global $post;
			$user_id = $post->post_author;

			$avatar_args['size']    = 300;
			$author['avatar']       = get_avatar_url( $user_id, $avatar_args );
			$author['display_name'] = get_the_author_meta( 'display_name', $user_id );
			$author['website']      = get_the_author_meta( 'user_url', $user_id );
			$author['bio']          = get_the_author_meta( 'description', $user_id );
			$author['posts_url']    = get_author_posts_url( $user_id );

		} elseif ( $custom_src ) {

			if ( ! empty( $settings['rael_ab_author_avatar']['url'] ) ) {
				$avatar_src = $settings['rael_ab_author_avatar']['url'];

				if ( $settings['rael_ab_author_avatar']['id'] ) {
					$attachment_image_src = wp_get_attachment_image_src( $settings['rael_ab_author_avatar']['id'], 'medium' );

					if ( ! empty( $attachment_image_src[0] ) ) {
						$avatar_src = $attachment_image_src[0];
					}
				}

				$author['avatar'] = $avatar_src;
			}

			$author['display_name'] = $settings['rael_ab_author_name'];
			$author['website']      = $settings['rael_ab_author_website']['url'];
			$author['bio']          = wpautop( $settings['rael_ab_author_bio'] );
			$author['posts_url']    = $settings['rael_ab_posts_url']['url'];
		}

		$print_avatar = ( ( ! $custom_src && 'yes' === $settings['rael_ab_show_avatar'] ) || ( $custom_src && ! empty( $author['avatar'] ) ) );
		$print_name   = ( ( ! $custom_src && 'yes' === $settings['rael_ab_show_name'] ) || ( $custom_src && ! empty( $author['display_name'] ) ) );
		$print_bio    = ( ( ! $custom_src && 'yes' === $settings['rael_ab_show_biography'] ) || ( $custom_src && ! empty( $author['bio'] ) ) );
		$print_link   = ( ( ! $custom_src && 'yes' === $settings['rael_ab_show_link'] ) && ! empty( $settings['rael_ab_link_text'] ) || ( $custom_src && ! empty( $author['posts_url'] ) && ! empty( $settings['rael_ab_link_text'] ) ) );

		if ( ! empty( $settings['rael_ab_link_to'] ) || $custom_src ) {
			if ( ( $custom_src || 'website' === $settings['rael_ab_link_to'] ) && ! empty( $author['website'] ) ) {
				$link_tag = 'a';
				$link_url = $author['website'];

				if ( $custom_src ) {
					$link_target = $settings['rael_ab_author_website']['is_external'] ? '_blank' : '';
					$link_follow = $settings['rael_ab_author_website']['nofollow'] ? 'nofollow' : '';
				} else {
					$link_target = '_blank';
				}
			} elseif ( 'posts_archive' === $settings['rael_ab_link_to'] && ! empty( $author['posts_url'] ) ) {
				$link_tag = 'a';
				$link_url = $author['posts_url'];
			}

			if ( ! empty( $link_url ) ) {
				$this->add_render_attribute( 'rael_ab_author_link', 'href', $link_url );

				if ( ! empty( $link_target ) ) {
					$this->add_render_attribute( 'rael_ab_author_link', 'target', $link_target );
				}

				if ( ! empty( $link_follow ) ) {
					$this->add_render_attribute( 'rael_ab_author_link', 'rel', $link_follow );
				}
			}
		}

		$this->add_render_attribute(
			'rael_ab_button',
			'class',
			array(
				'elementor-button',
				'rael-author-box__button',
				'elementor-size-xs',
			)
		);

		if ( $print_link ) {
			$this->add_render_attribute( 'rael_ab_button', 'href', $author['posts_url'] );

			if ( $custom_src ) {
				$link_target = $settings['rael_ab_posts_url']['is_external'] ? '_blank' : '';
				$link_follow = $settings['rael_ab_posts_url']['nofollow'] ? 'nofollow' : '';
				if ( ! empty( $link_target ) ) {
					$this->add_render_attribute( 'rael_ab_button', 'target', $link_target );
				}

				if ( ! empty( $link_follow ) ) {
					$this->add_render_attribute( 'rael_ab_button', 'rel', $link_follow );
				}
			}
		}

		if ( $print_link && ! empty( $settings['rael_ab_button_hover_animation'] ) ) {
			$this->add_render_attribute(
				'rael_ab_button',
				'class',
				'rael-author-box__button-animation--' . $settings['button_hover_animation']
			);
		}

		if ( $print_avatar ) {
			$this->add_render_attribute( 'rael_ab_avatar', 'src', $author['avatar'] );

			if ( ! empty( $author['display_name'] ) ) {
				$this->add_render_attribute( 'rael_ab_avatar', 'alt', $author['display_name'] );
			}
		}
		?>

		<div class="rael-author-box">
			<?php if ( $print_avatar ) : ?>
				<<?php echo wp_kses_post( $link_tag ); ?> <?php echo esc_html( $this->get_render_attribute_string( 'rael_ab_author_link' ) ); ?> class="rael-author-box__avatar">
					<img <?php $this->print_render_attribute_string( 'rael_ab_avatar' ); ?>>
				</<?php echo wp_kses_post( $link_tag ); ?>>
			<?php endif; ?>

			<div class="rael-author-box__text">
				<?php if ( $print_name ) : ?>
					<<?php echo wp_kses_post( Helper::validate_html_tags( $link_tag ) ); ?> <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_ab_author_link' ) ); ?>>
						<?php echo '<' . wp_kses_post( Helper::validate_html_tags( $author_name_tag ) ) . ' class="rael-author-box__name">' . esc_html( $author['display_name'] ) . '</' . wp_kses_post( Helper::validate_html_tags( $author_name_tag ) ) . '>'; ?>
					</<?php echo wp_kses_post( Helper::validate_html_tags( $link_tag ) ); ?>>
				<?php endif; ?>

				<?php if ( $print_bio ) : ?>
					<div class="rael-author-box__bio">
						<?php echo wp_kses_post( $author['bio'] ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $print_link ) : ?>
					<a <?php $this->print_render_attribute_string( 'rael_ab_button' ); ?>>
						<?php echo esc_html( $settings['rael_ab_link_text'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<?php
	}
	/**
	 * Renders plain content
	 */
	public function render_plain_content() {}
}
