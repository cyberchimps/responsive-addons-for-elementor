<?php

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

class Responsive_Addons_For_Elementor_Facebook_Feed extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve 'Facebook Feed' widget name.
	 *
	 * @since 1.3.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-facebook-feed';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve 'Facebook Feed' widget title.
	 *
	 * @since 1.3.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Facebook Feed', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve 'Facebook Feed' widget icon.
	 *
	 * @since 1.3.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-fb-feed rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the Facebook Feed widget belongs to.
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
	 * Register 'Facebook Feed' widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.3.1
	 * @access protected
	 */
	protected function register_controls() {
		// Content Tab.
		$this->rael_facebook_account_settings();
		$this->rael_feed_settings();
		$this->rael_general_settings();

		// Styles Tab.
		$this->rael_general_styles();
		$this->rael_card_header_styles();
		$this->rael_card_body_styles();
		$this->rael_card_footer_styles();
		$this->rael_overlay_styles();

	}

	protected function rael_facebook_account_settings() {

		$this->start_controls_section(
			'rael_facebook_account_settings_section',
			array(
				'label' => __( 'Facebook Account Settings', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_facebook_feed_page_id',
			array(
				'label'       => __( 'Page ID', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'label_block' => true,
				'default'     => '',
				'description' => __( '<a href="https://findidfb.com/" class="rael-btn" target="_blank">Find Your Page ID</a>', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_facebook_feed_access_token',
			array(
				'label'       => __( 'Access Token', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'description' => __( '<a href="https://cyberchimps.com/elementor-widgets/docs/facebook-feed/" target="_blank">Get Access Token</a>', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_facebook_feed_cache_limit',
			array(
				'label'       => __( 'Data Cache Time', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'default'     => 60,
				'description' => __( 'Cache expiration time (Minutes)', 'responsive-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();
	}

	protected function rael_feed_settings() {

		$this->start_controls_section(
			'rael_feed_settings_section',
			array(
				'label' => __( 'Feed Settings', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_facebook_feed_sort_by',
			array(
				'label'   => __( 'Sort By', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'most-recent'  => __( 'Newest', 'responsive-addons-for-elementor' ),
					'least-recent' => __( 'Oldest', 'responsive-addons-for-elementor' ),
				),
				'default' => 'most-recent',
			)
		);

		$this->add_control(
			'rael_facebook_feed_image_count',
			array(
				'label'   => __( 'Max Visible Items', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 12,
				),
				'range'   => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
			)
		);

		$this->end_controls_section();
	}

	protected function rael_general_settings() {

		$this->start_controls_section(
			'rael_general_settings_section',
			array(
				'label' => __( 'General Settings', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_facebook_feed_layout_heading',
			array(
				'label' => __( 'Layout Settings', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_facebook_feed_layout',
			array(
				'label'   => __( 'Layout', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'card'    => __( 'Card', 'responsive-addons-for-elementor' ),
					'overlay' => __( 'Overlay', 'responsive-addons-for-elementor' ),
				),
				'default' => 'card',
			)
		);

		$this->add_control(
			'rael_overlay_image',
			array(
				'label'       => __( 'Overlay Image', 'responsive-addons-for-elementor' ),
				'description' => __( 'This image will be used when an image is not available from the post.', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::MEDIA,
				'condition'   => array(
					'rael_facebook_feed_layout' => 'overlay',
				),
			)
		);

		$this->add_control(
			'rael_facebook_feed_columns',
			array(
				'label'   => __( 'Columns', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'rael-col-3',
				'options' => array(
					'rael-col-1' => __( '1', 'responsive-addons-for-elementor' ),
					'rael-col-2' => __( '2', 'responsive-addons-for-elementor' ),
					'rael-col-3' => __( '3', 'responsive-addons-for-elementor' ),
					'rael-col-4' => __( '4', 'responsive-addons-for-elementor' ),
					'rael-col-5' => __( '5', 'responsive-addons-for-elementor' ),
					'rael-col-6' => __( '6', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_facebook_feed_content_heading',
			array(
				'label' => __( 'Content Settings', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_facebook_feed_message',
			array(
				'label'        => __( 'Display Message', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'rael_facebook_feed_message_max_length',
			array(
				'label'      => __( 'Max Message Length', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 10,
				),
				'condition'  => array(
					'rael_facebook_feed_message' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_facebook_feed_likes',
			array(
				'label'        => __( 'Display Likes', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'rael_facebook_feed_comments',
			array(
				'label'        => __( 'Display Comments', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'rael_facebook_feed_date',
			array(
				'label'        => __( 'Display Date', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'rael_facebook_feed_layout' => 'card',
				),
			)
		);

		$this->add_control(
			'rael_facebook_feed_link_target',
			array(
				'label'        => __( 'Open Link In New Window', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'rael_facebook_feed_preview_heading',
			array(
				'label' => __( 'Preview Content Settings', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_facebook_feed_is_show_preview_content',
			array(
				'label'        => __( 'Show Preview Content', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'rael_facebook_feed_layout' => 'card',
				),
			)
		);

		$this->add_control(
			'rael_facebook_feed_is_show_preview_thumbnail',
			array(
				'label'        => __( 'Show Preview Thumbnail', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'rael_facebook_feed_is_show_preview_content' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_facebook_feed_is_show_preview_host',
			array(
				'label'        => __( 'Show Preview Host Name', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'rael_facebook_feed_is_show_preview_content' => 'yes',
					'rael_facebook_feed_layout' => 'card',
				),
			)
		);

		$this->add_control(
			'rael_facebook_feed_is_show_preview_title',
			array(
				'label'        => __( 'Show Preview Title', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'rael_facebook_feed_is_show_preview_content' => 'yes',
					'rael_facebook_feed_layout' => 'card',
				),
			)
		);

		$this->add_control(
			'rael_facebook_feed_is_show_preview_description',
			array(
				'label'        => __( 'Show Preview Description', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'rael_facebook_feed_is_show_preview_content' => 'yes',
					'rael_facebook_feed_layout' => 'card',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function rael_general_styles() {

		$this->start_controls_section(
			'rael_fb_feed_general_styles_section',
			array(
				'label' => __( 'Post Container', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_fb_post_spacing',
			array(
				'label'      => __( 'Space Between Posts', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-fb-feed-item-content-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'rael_fb_post_box_border',
				'label'          => __( 'Border', 'responsive-addons-for-elementor' ),
				'selector'       => '{{WRAPPER}} .rael-fb-feed-item-content-container',
				'fields_options' => array(
					'border' => array(
						'default' => 'double',
					),
					'width'  => array(
						'default' => array(
							'top'      => '12',
							'right'    => '12',
							'bottom'   => '12',
							'left'     => '12',
							'isLinked' => true,
						),
					),
					'color'  => array(
						'default' => '#52A49B',
					),
				),
			)
		);

		$this->add_control(
			'rael_fb_post_border_radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .rael-fb-feed-item-content-container' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
					'{{WRAPPER}} .hover-container .hover-content' => 'border-radius: calc({{TOP}}px - 12px) calc({{RIGHT}}px - 12px) calc({{BOTTOM}}px - 12px) calc({{LEFT}}px - 12px);',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'      => 'rael_fb_post_gradient_background',
				'label'     => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .rael-fb-feed-item-content-container',
				'condition' => array(
					'rael_facebook_feed_layout' => 'card',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_fb_post_shadow',
				'label'    => __( 'Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-fb-feed-item-content-container',
			)
		);

		$this->end_controls_section();
	}

	protected function rael_card_header_styles() {

		$this->start_controls_section(
			'rael_fb_post_card_header_styles_section',
			array(
				'label'     => __( 'Card Header', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_facebook_feed_layout' => 'card',
				),
			)
		);

		$this->add_control(
			'rael_fb_post_card_header_container_heading',
			array(
				'label' => __( 'Container', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_fb_post_card_header_gradient_background',
				'label'    => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-fb-feed-item-header',
			)
		);

		$this->add_control(
			'rael_fb_post_card_header_spacing',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-fb-feed-item-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_fb_post_card_header_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-fb-feed-item-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_fb_post_card_header_page_name_heading',
			array(
				'label' => __( 'Page Name', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_fb_post_card_header_page_name_typography',
				'selector' => '{{WRAPPER}} .rael-fb-feed-username',
			)
		);

		$this->add_control(
			'rael_fb_post_card_header_page_name_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-fb-feed-username' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_fb_post_card_header_post_time_heading',
			array(
				'label'     => __( 'Post Time', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'rael_facebook_feed_date' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_fb_post_card_header_post_time_typography',
				'selector'  => '{{WRAPPER}} .rael-fb-feed-post-time',
				'condition' => array(
					'rael_facebook_feed_date' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_fb_post_card_header_post_time_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-fb-feed-post-time' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_facebook_feed_date' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function rael_card_body_styles() {

		$this->start_controls_section(
			'rael_fb_post_card_body_styles_section',
			array(
				'label'     => __( 'Card Body', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_facebook_feed_layout' => 'card',
				),
			)
		);

		$this->add_control(
			'rael_fb_post_card_body_container_heading',
			array(
				'label' => __( 'Container', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_fb_post_card_body_spacing',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-fb-feed-item-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rael-fb-feed-url-preview' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_fb_post_card_body_message_typography',
				'selector' => '{{WRAPPER}} .rael-fb-feed-message',
			)
		);

		$this->add_control(
			'rael_fb_post_card_body_message_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-fb-feed-message' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_fb_post_card_body_media_heading',
			array(
				'label' => __( 'Media', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_fb_post_card_body_media_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-fb-feed-content-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rael-fb-feed-img'        => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rael-fb-feed-media-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_fb_post_card_body_media_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-fb-feed-img'        => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rael-fb-feed-media-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_fb_post_card_body_video_host_heading',
			array(
				'label' => __( 'Video Host', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_fb_post_card_body_video_host_typography',
				'selector' => '{{WRAPPER}} .rael-fb-feed-url-host',
			)
		);

		$this->add_control(
			'rael_fb_post_card_body_video_host_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-fb-feed-url-host' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_fb_post_card_body_video_title_heading',
			array(
				'label' => __( 'Video Title', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_fb_post_card_body_video_title_typography',
				'selector' => '{{WRAPPER}} .rael-facebook-feed-url-title',
			)
		);

		$this->add_control(
			'rael_fb_post_card_body_video_title_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-facebook-feed-url-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_fb_post_card_body_video_description_heading',
			array(
				'label' => __( 'Video Description', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_fb_post_card_body_video_description_typography',
				'selector' => '{{WRAPPER}} .rael-fb-feed-url-description',
			)
		);

		$this->add_control(
			'rael_fb_post_card_body_video_description_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-fb-feed-url-description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function rael_card_footer_styles() {

		$this->start_controls_section(
			'rael_fb_post_card_footer_styles_section',
			array(
				'label'     => __( 'Card Footer', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_facebook_feed_layout' => 'card',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_fb_post_card_footer_gradient_background',
				'label'    => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-fb-feed-item-footer',
			)
		);

		$this->add_control(
			'rael_fb_post_card_footer_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-fb-feed-item-footer' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_fb_post_card_footer_font_size',
			array(
				'label'      => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'rem',
					'%',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-fb-feed-item-footer' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_fb_post_card_footer_spacing',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-fb-feed-item-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function rael_overlay_styles() {

		$this->start_controls_section(
			'rael_fb_post_overlay_styles_section',
			array(
				'label'     => __( 'Overlay Styles', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_facebook_feed_layout' => 'overlay',
				),
			)
		);

		$this->add_control(
			'rael_fb_post_overlay_posts_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hover-container' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_fb_post_overlay_posts_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .hover-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_fb_post_overlay_image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .hover-container .rael-fb-feed-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_fb_post_overlay__content_heading',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'Styles henceforth will be visible when you hover over the image container.', 'responsive-addons-for-elementor' ),
				'content_classes' => 'elementor-control-field-description',
				'separator'       => 'before',
			)
		);

		$this->add_control(
			'rael_fb_post_overlay_content_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hover-content' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_fb_post_overlay_page_name_heading',
			array(
				'label' => __( 'Page Name', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_fb_post_overlay_page_name_typography',
				'selector' => '{{WRAPPER}} .hover-content .rael-fb-feed-username',
			)
		);

		$this->add_control(
			'rael_fb_post_overlay_page_name_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hover-content .rael-fb-feed-username' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_fb_post_overlay_body_heading',
			array(
				'label' => __( 'Body', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_fb_post_overlay_body_typography',
				'selector' => '{{WRAPPER}} .hover-content .rael-fb-feed-message',
			)
		);

		$this->add_control(
			'rael_fb_post_overlay_body_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hover-content .rael-fb-feed-message' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_fb_post_overlay_footer_heading',
			array(
				'label' => __( 'Footer', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_fb_post_overlay_footer_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .hover-content .rael-fb-feed-meta' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_fb_post_overlay_footer_font_size',
			array(
				'label'      => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'rem',
					'%',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .hover-content .rael-fb-feed-meta' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}


	protected function render() {
		$in_editor = Plugin::instance()->editor->is_edit_mode();
		$settings  = $this->get_settings_for_display();
		$post_id   = 0;
		if ( Plugin::$instance->documents->get_current() ) {
			$post_id = Plugin::$instance->documents->get_current()->get_main_id();
		}
		?>

		<div class="rael-facebook-feed-container">
			<?php
			if ( empty( $settings['rael_facebook_feed_page_id'] ) && empty( $settings['rael_facebook_feed_access_token'] ) ) {
				$this->rael_print_missing_field( 'Page ID & Access Token' );
			} elseif ( empty( $settings['rael_facebook_feed_page_id'] ) ) {
				$this->rael_print_missing_field( 'Page ID' );
			} elseif ( empty( $settings['rael_facebook_feed_access_token'] ) ) {
				$this->rael_print_missing_field( 'Access Token' );
			} else {
				?>
				<div class="rael-fb-feed-posts <?php echo esc_attr( $settings['rael_facebook_feed_columns'] ); ?>" id="rael-fb-feed-posts-<?php echo esc_attr( $this->get_id() ); ?>">
					<?php do_action( 'render_facebook_feed', $settings ); ?>
				</div>
				<?php
				echo '<div class="heightFix"></div>';
				?>
			<?php } ?>
		</div>
		<?php
	}

	protected function rael_print_missing_field( $arg ) {
		?>
		<div class="rael-facebook-feed-error-container">
			<p class="rael-fb-error-message">
				You need to input <strong> <?php echo esc_html( $arg ); ?> </strong> to retrieve your facebook feed.
			</p>
		</div>
		<?php
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/elementor-widgets/docs/facebook-feed';
	}
}