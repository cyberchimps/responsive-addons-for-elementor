<?php
/**
 * RAEL Twitter Feed
 *
 * @since 1.2.2
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

/**
 * 'RAEL Twitter Feed' widget class
 *
 * @since 1.2.2
 */
class Responsive_Addons_For_Elementor_Twitter_Feed extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-twitter-feed';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'X (Twitter) Feed', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve twitter widget icon.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-twitter-feed rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the twitter widget belongs to.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'social', 'twitter', 'twitter feed', 'feed', 'tweets', 'social content', 'twitter embed' );
	}

	/**
	 * Get Custom help URL
	 *
	 * @since 1.2.2
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/twitter-feed';
	}

	/**
	 * Retrieve the list of scripts the twitter feed widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array( 'rael-isotope', 'imagesloaded' );
	}

	/**
	 * Register widget controls.
	 *
	 * @since 1.2.2
	 * @access protected
	 */
	protected function register_controls() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		// Content Tab.
		$this->register_content_account_controls();
		$this->register_content_layout_controls();
		$this->register_content_card_controls();

		// Style Tab.
		$this->register_style_card_controls();
		$this->register_style_card_hover_controls();
		$this->register_style_card_color_typography_controls();
		$this->register_style_avatar_controls();
		$this->register_style_icon_controls();
	}

	/**
	 * Register account settings controls.
	 *
	 * @since 1.2.2
	 * @access public
	 */
	public function register_content_account_controls() {
		$this->start_controls_section(
			'rael_tf_content_section_account_controls',
			array(
				'label' => __( 'Account', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_account_name',
			array(
				'label'       => __( 'Account Name', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Use @ sign with your account name.', 'responsive-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'rael_hashtag_name',
			array(
				'label'       => __( 'Hashtag Name', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Remove # sign from your hashtag name.', 'responsive-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'rael_bearer_token',
			array(
				'label'       => __( 'Bearer Token', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( '<a href="https://developer.twitter.com/en/portal/dashboard" target="_blank">Get Bearer Token.</a> Create a new app or select an existing app and enter that app\'s <strong>bearer token</strong> here.', 'responsive-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register layout controls.
	 *
	 * @since 1.2.2
	 * @access public
	 */
	public function register_content_layout_controls() {
		$this->start_controls_section(
			'rael_tf_content_section_layout_controls',
			array(
				'label' => __( 'Layout', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_content_layout',
			array(
				'label'       => __( 'Content Layout', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'masonry',
				'options'     => array(
					'list'    => __( 'List', 'responsive-addons-for-elementor' ),
					'masonry' => __( 'Masonry', 'responsive-addons-for-elementor' ),
				),
				'label_block' => false,
			)
		);

		$this->add_control(
			'rael_column_grid',
			array(
				'label'       => __( 'Column Grid', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'col-3',
				'options'     => array(
					'col-2' => __( '2 Columns', 'responsive-addons-for-elementor' ),
					'col-3' => __( '3 Columns', 'responsive-addons-for-elementor' ),
					'col-4' => __( '4 Columns', 'responsive-addons-for-elementor' ),
				),
				'label_block' => false,
				'condition'   => array(
					'rael_content_layout' => 'masonry',
				),
			)
		);

		$this->add_control(
			'rael_content_length',
			array(
				'label'   => __( 'Content Length', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 400,
				'default' => 400,
			)
		);

		$this->add_responsive_control(
			'rael_column_spacing',
			array(
				'label'     => __( 'Column Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50.,
					),
				),
				'default'   => array(
					'unit' => 'px',
					'size' => 10,
				),
				'condition' => array(
					'rael_content_layout' => 'masonry',
				),
			)
		);

		$this->add_responsive_control(
			'rael_item_spacing',
			array(
				'label'     => __( 'Item Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50.,
					),
				),
				'default'   => array(
					'unit' => 'px',
					'size' => 10,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-twitter-feed--list .rael-twitter-feed__item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_content_layout' => 'list',
				),
			)
		);

		$this->add_control(
			'rael_post_limit',
			array(
				'label'       => __( 'Post Limit', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'default'     => 10,
				'min'         => 5,
				'max'         => 100,
			)
		);

		$this->add_control(
			'rael_show_media_elements',
			array(
				'label'        => __( 'Show Media Elements', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'YES', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'NO', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register card controls.
	 *
	 * @since 1.2.2
	 * @access public
	 */
	public function register_content_card_controls() {
		$this->start_controls_section(
			'rael_tf_content_section_card_controls',
			array(
				'label' => __( 'Card', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_show_avatar',
			array(
				'label'        => __( 'Show Avatar', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'YES', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'NO', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_show_date',
			array(
				'label'        => __( 'Show Date', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'YES', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'NO', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_show_read_more',
			array(
				'label'        => __( 'Show Read More', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'YES', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'NO', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_show_icon',
			array(
				'label'        => __( 'Show Icon', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'YES', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'NO', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register card style controls.
	 *
	 * @since 1.2.2
	 * @access public
	 */
	public function register_style_card_controls() {
		$this->start_controls_section(
			'rael_tf_style_section_card_controls',
			array(
				'label' => __( 'Card', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_use_gradient_background',
			array(
				'label'        => __( 'Use gradient Background?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'YES', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'NO', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'label'     => __( 'Background Type', 'responsive-addons-for-elementor' ),
				'name'      => 'rael_card_background',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .rael-twitter-feed__item',
				'condition' => array(
					'rael_use_gradient_background' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_card_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-twitter-feed__item' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_use_gradient_background!' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_card_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'(mobile+){{WRAPPER}} .rael-twitter-feed__card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'(tablet+){{WRAPPER}} .rael-twitter-feed__card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'(desktop+){{WRAPPER}} .rael-twitter-feed__card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_card_border',
				'selector' => '{{WRAPPER}} .rael-twitter-feed__item',
			)
		);

		$this->add_control(
			'rael_card_border_radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-twitter-feed__item' => 'border-radius: {{SIZE}}px;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_card_box_shadow',
				'selector' => '{{WRAPPER}} .rael-twitter-feed__item',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register card hover style controls.
	 *
	 * @since 1.2.2
	 * @access public
	 */
	public function register_style_card_hover_controls() {
		$this->start_controls_section(
			'rael_tf_style_section_card_hover_controls',
			array(
				'label' => __( 'Card Hover', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_title_hover_color',
			array(
				'label'     => __( 'Title Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-twitter-feed__item:hover .rael-twitter-feed__author-name' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_content_hover_color',
			array(
				'label'     => __( 'Content Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-twitter-feed__item:hover .rael-twitter-feed__card-body p' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_link_hover_color',
			array(
				'label'     => __( 'Link Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-twitter-feed__item:hover .rael-twitter-feed__read-more a' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_show_read_more' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_date_hover_color',
			array(
				'label'     => __( 'Date Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-twitter-feed__item:hover .rael-twitter-feed__date' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_show_date' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_icon_hover_color',
			array(
				'label'     => __( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-twitter-feed__item:hover .rael-twitter-feed__twitter-icon' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_show_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_border_hover_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-twitter-feed__item:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'label'    => __( 'Background Type', 'responsive-addons-for-elementor' ),
				'name'     => 'rael_card_hover_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-twitter-feed__item:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_card_hover_box_shadow',
				'selector' => '{{WRAPPER}} .rael-twitter-feed__item:hover',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register card elements color and typography controls.
	 *
	 * @since 1.2.2
	 * @access public
	 */
	public function register_style_card_color_typography_controls() {
		$this->start_controls_section(
			'rael_tf_section_style_card_color_typography_controls',
			array(
				'label' => __( 'Color & Typography', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Title Style.
		$this->add_control(
			'rael_card_title_style_heading',
			array(
				'label'     => __( 'Title Style', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_card_title_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-twitter-feed__author-name' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_card_title',
				'selector' => '{{WRAPPER}} .rael-twitter-feed__author-name',
			)
		);

		// Content Style.
		$this->add_control(
			'rael_card_content_style_heading',
			array(
				'label'     => __( 'Content Style', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_card_content_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-twitter-feed__card-body p' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_card_content',
				'selector' => '{{WRAPPER}} .rael-twitter-feed__card-body p',
			)
		);

		// Link Style.
		$this->add_control(
			'rael_card_link_style_heading',
			array(
				'label'     => __( 'Link Style', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_card_link_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-twitter-feed__read-more a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_card_link_color_hover',
			array(
				'label'     => __( 'Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-twitter-feed__item .rael-twitter-feed__read-more a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_card_link',
				'selector' => '{{WRAPPER}} .rael-twitter-feed__read-more a',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register avatar style controls.
	 *
	 * @since 1.2.2
	 * @access public
	 */
	public function register_style_avatar_controls() {
		$this->start_controls_section(
			'rael_tf_section_style_avatar_controls',
			array(
				'label'     => __( 'Avatar', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_show_avatar' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_avatar_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 38,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-twitter-feed__author-avatar img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_avatar_height',
			array(
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(),
				'selectors'  => array(
					'{{WRAPPER}} .rael-twitter-feed__author-avatar img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_avatar_style',
			array(
				'label'   => __( 'Avatar Style', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'circle',
				'options' => array(
					'circle' => __( 'Circle', 'responsive-addons-for-elementor' ),
					'square' => __( 'Square', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_avatar_border',
				'selector' => '{{WRAPPER}} .rael-twitter-feed__author-avatar img',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_avatar_box_shadow',
				'selector' => '{{WRAPPER}} .rael-twitter-feed__author-avatar img',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register icon style controls.
	 *
	 * @since 1.2.2
	 * @access public
	 */
	public function register_style_icon_controls() {
		$this->start_controls_section(
			'rael_tf_section_style_icon_controls',
			array(
				'label'     => __( 'Icon', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_show_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_icon_font_size',
			array(
				'label'      => __( 'Font Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(),
				'selectors'  => array(
					'{{WRAPPER}} .rael-twitter-feed__twitter-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_icon_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-twitter-feed__twitter-icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.2.2
	 * @access protected
	 */
	protected function render() {
		$settings        = $this->get_settings_for_display();
    	$account_name    = ltrim( $settings['rael_account_name'], '@' );
    	$hashtag         = $settings['rael_hashtag_name'];
    	$column_spacing  = $settings['rael_column_spacing']['size'] ?? 10;
    	$token           = $settings['rael_bearer_token'] ?? '';
		
    	if ( empty( $token ) ) {
    	    return;
    	}

    	$user_cache_key = $this->get_name() . '_' . $this->get_id() . '__user_object';
    	$user_object    = get_transient( $user_cache_key );

    	if ( empty( $user_object ) ) {
        	$user_endpoint = "https://api.twitter.com/2/users/by/username/{$account_name}?user.fields=id,name,username,profile_image_url";
        	$response      = wp_remote_get( $user_endpoint, [
        	    'headers' => [ 'Authorization' => "Bearer $token" ]
        	]);

        	if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
        	    $user_object = json_decode( wp_remote_retrieve_body( $response ) );
        	    set_transient( $user_cache_key, $user_object, 1800 );
        	}
    	}

    	if ( empty( $user_object->data->id ) ) {
        	return;
    	}

    	$user_id       = $user_object->data->id;
    	$tweet_fields  = 'id,text,created_at,public_metrics,entities,attachments';
    	$tweet_cache_key = $this->get_name() . '_' . $this->get_id() . '__tweets_cache';
    	$tweets        = get_transient( $tweet_cache_key );

    	if ( empty( $tweets ) ) {
    	    $tweet_endpoint = "https://api.twitter.com/2/users/{$user_id}/tweets?max_results=100&tweet.fields={$tweet_fields}";
    	    $response       = wp_remote_get( $tweet_endpoint, [
    	        'headers' => [ 'Authorization' => "Bearer $token" ]
    	    ]);

    	    if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
    	        $tweets = json_decode( wp_remote_retrieve_body( $response ) );
    	        set_transient( $tweet_cache_key, $tweets, 1800 );
    	    }
    	}

    	if ( empty( $tweets->data ) ) {
    	    return;
    	}

    	$tweets_data   = $tweets->data;
    	$tweets_author = $user_object->data;

    	if ( $hashtag ) {
        	$tweets_data = array_filter( $tweets_data, [ $this, 'filter_hashtag_data' ] );
    	}

    	$tweets_data = array_splice( $tweets_data, 0, $settings['rael_post_limit'] );

    	$this->add_render_attribute('rael_twitter_feed', 'class', [
        	'rael-twitter-feed',
        	'rael-twitter-feed-' . $this->get_id(),
        	'rael-twitter-feed--' . $settings['rael_content_layout'],
        	'rael-twitter-feed--' . $settings['rael_column_grid'],
        	'clearfix',
    	]);
    
    	$this->add_render_attribute('rael_twitter_feed', 'data-gutter', $column_spacing);

    	$author_avatar = '<a href="https://twitter.com/' . $tweets_author->username . '" class="rael-twitter-feed__author-avatar" target="_blank">
                        <img src="' . $tweets_author->profile_image_url . '" class="rael-twitter-feed__avatar-image--' . $settings['rael_avatar_style'] . '" alt="' . $tweets_author->name . '" />
                      </a>'; ?>

		<div <?php $this->print_render_attribute_string( 'rael_twitter_feed' ); ?>>
			<?php foreach ( $tweets_data as $tweet ) : ?>
				<div <?php $this->print_render_attribute_string( 'rael_twitter_feed_item' ); ?>>
					<div class="rael-twitter-feed__card">
						<div class="rael-twitter-feed__card-header clearfix">
							<?php if ( 'yes' === $settings['rael_show_avatar'] ) : ?>
								<?php echo $author_avatar; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php endif; ?>

							<a href="<?php echo esc_attr( "https://twitter.com/{$tweets_author->username}" ); ?>" class="rael-twitter-feed__author-url" target="_blank">
								<?php if ( 'yes' === $settings['rael_show_icon'] ) : ?>
									<i class="fab fa-twitter rael-twitter-feed__twitter-icon"></i>
								<?php endif; ?>
								<span class="rael-twitter-feed__author-name">
									<?php echo $tweets_author->name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</span>
							</a>
							<?php if ( 'yes' === $settings['rael_show_date'] ) : ?>
								<span class="rael-twitter-feed__date">
									<?php
									/* translators: %s represents the time duration (e.g., "2 hours ago").*/
									printf( __( '%s ago', 'responsive-addons-for-elementor' ), human_time_diff( strtotime( $tweet->created_at ) ) );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
									?>
								</span>
							<?php endif; ?>
						</div>

						<div class="rael-twitter-feed__card-body">
							<?php
							$content = $tweet->text;

							if ( strlen( $content ) > $settings['rael_content_length'] ) {
								$content  = substr( $content, 0, $settings['rael_content_length'] );
								$content .= '...';
							}

							echo '<p>' . $content . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>

							<?php if ( 'yes' === $settings['rael_show_read_more'] ) : ?>
								<div class="rael-twitter-feed__read-more">
									<a href="<?php echo esc_attr( "https://twitter.com/{$tweets_author->username}/status/{$tweet->id}" ); ?>" target="_blank">
										<?php esc_html_e( 'Read More', 'responsive-addons-for-elementor' ); ?> <i class="fas fa-angle-double-right"></i>
									</a>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<?php
					if ( 'yes' === $settings['rael_show_media_elements'] ) :
						if ( isset( $tweet->attachments->media_keys ) && isset( $tweets_media ) ) {
							$media_key = $tweet->attachments->media_keys[0];

							foreach ( $tweets_media as $media ) {
								if ( 'photo' === $media->type && $media_key === $media->media_key ) {
									?>
										<div class="rael-twitter-feed__media-attachment">
											<img
												src="<?php echo esc_attr( $media->url ); ?>"
												width="<?php echo esc_attr( $media->width ); ?>"
												height="<?php echo esc_attr( $media->height ); ?>"
											/>
										</div>
									<?php
									break;
								}
							}
						}
					endif;
					?>
				</div>
			<?php endforeach; ?>
		</div>

		<?php
		echo '<style>
			.rael-twitter-feed-' . esc_attr( $this->get_id() ) . '.rael-twitter-feed--masonry.rael-twitter-feed--col-2 .rael-twitter-feed__item {
				width: calc(50% - ' . esc_attr( ceil( $column_spacing / 2 ) ) . 'px);
			}

			.rael-twitter-feed-' . esc_attr( $this->get_id() ) . '.rael-twitter-feed--masonry.rael-twitter-feed--col-3 .rael-twitter-feed__item {
				width: calc(33.33% - ' . esc_attr( ceil( $column_spacing * 2 / 3 ) ) . 'px);
			}

			.rael-twitter-feed-' . esc_attr( $this->get_id() ) . '.rael-twitter-feed--masonry.rael-twitter-feed--col-4 .rael-twitter-feed__item {
				width: calc(25% - ' . esc_attr( ceil( $column_spacing * 3 / 4 ) ) . 'px);
			}

			.rael-twitter-feed-' . esc_attr( $this->get_id() ) . '.rael-twitter-feed--masonry.rael-twitter-feed--col-2 .rael-twitter-feed__item,
			.rael-twitter-feed-' . esc_attr( $this->get_id() ) . '.rael-twitter-feed--masonry.rael-twitter-feed--col-3 .rael-twitter-feed__item,
			.rael-twitter-feed-' . esc_attr( $this->get_id() ) . '.rael-twitter-feed--masonry.rael-twitter-feed--col-4 .rael-twitter-feed__item	{
				margin-bottom: ' . esc_attr( $column_spacing ) . 'px;
			}

			@media only screen and (min-width: 768px) and (max-width: 992px) {
				.rael-twitter-feed-' . esc_attr( $this->get_id() ) . '.rael-twitter-feed--masonry.rael-twitter-feed--col-3 .rael-twitter-feed__item,
				.rael-twitter-feed-' . esc_attr( $this->get_id() ) . '.rael-twitter-feed--masonry.rael-twitter-feed--col-4 .rael-twitter-feed__item {
					width: calc(50% - ' . esc_attr( ceil( $column_spacing / 2 ) ) . 'px);
				}
			}
		</style>';
	}

	/**
	 * Checks whether the given tweet has required hashtag.
	 *
	 * It is callback function for the array_filter().
	 *
	 * @param object $tweet Each tweet object from the tweets collection.
	 * @since 1.2.22
	 *
	 * @access public
	 *
	 * @return boolean
	 */
	public function filter_hashtag_data( $tweet ) {
		$settings = $this->get_settings_for_display();
		$hashtag  = $settings['rael_hashtag_name'];

		if ( ! empty( $tweet->entities->hashtags ) ) {
			$tags = $tweet->entities->hashtags;

			foreach ( $tags as $tag ) {
				if ( 0 === strcasecmp( $hashtag, $tag->tag ) ) {
					return true;
				}
			}
		}

		return false;
	}
}
