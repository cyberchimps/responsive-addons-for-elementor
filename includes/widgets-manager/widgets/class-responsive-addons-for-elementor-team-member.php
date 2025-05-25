<?php
/**
 * Team Member Widget
 *
 * @since      1.3.0
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Control_Media;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor 'Team Member' widget.
 *
 * Elementor widget that displays Team Member.
 *
 * @since 1.3.0
 */
class Responsive_Addons_For_Elementor_Team_Member extends Widget_Base {


	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-team-member';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Team Member', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve slider widget icon.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-user-circle-o rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the slider widget belongs to.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}
	/**
	 * Register controls for the widget.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'rael_team_content',
			array(
				'label' => esc_html__( 'Team Member Content', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_team_style',
			array(
				'label'   => esc_html__( 'Style', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default'                   => esc_html__( 'Default', 'responsive-addons-for-elementor' ),
					'overlay'                   => esc_html__( 'Overlay', 'responsive-addons-for-elementor' ),
					'centered_style'            => esc_html__( 'Centered ', 'responsive-addons-for-elementor' ),
					'hover_info'                => esc_html__( 'Hover on social', 'responsive-addons-for-elementor' ),
					'overlay_details'           => esc_html__( 'Overlay with details', 'responsive-addons-for-elementor' ),
					'centered_style_details'    => esc_html__( 'Centered with details ', 'responsive-addons-for-elementor' ),
					'long_height_hover'         => esc_html__( 'Long height with hover ', 'responsive-addons-for-elementor' ),
					'long_height_details'       => esc_html__( 'Long height with details ', 'responsive-addons-for-elementor' ),
					'long_height_details_hover' => esc_html__( 'Long height with details & hover', 'responsive-addons-for-elementor' ),
					'overlay_circle'            => esc_html__( 'Overlay with circle shape', 'responsive-addons-for-elementor' ),
					'overlay_circle_hover'      => esc_html__( 'Overlay with circle shape & hover', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_team_image',
			array(
				'label'   => esc_html__( 'Choose Member Image', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'rael_team_thumbnail',
				'default' => 'large',
			)
		);

		$this->add_control(
			'rael_team_name',
			array(
				'label'       => esc_html__( 'Member Name', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Jane Doe', 'responsive-addons-for-elementor' ),
				'placeholder' => esc_html__( 'Member Name', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_team_position',
			array(
				'label'       => esc_html__( 'Member Position', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Designer', 'responsive-addons-for-elementor' ),
				'placeholder' => esc_html__( 'Member Position', 'responsive-addons-for-elementor' ),

			)
		);

		$this->add_control(
			'rael_team_toggle_icon',
			array(
				'label'        => esc_html__( 'Show Icon', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => esc_html__( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => array(
					'rael_team_style' => 'default',
				),
			)
		);
		$this->add_control(
			'rael_team_top_icons',
			array(
				'label'            => esc_html__( 'Icon', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'rael_team_top_icon',
				'default'          => array(
					'value'   => 'icon icon-team1',
					'library' => 'eicons',
				),
				'condition'        => array(
					'rael_team_style'       => 'default',
					'rael_team_toggle_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_team_show_short_description',
			array(
				'label'        => esc_html__( 'Show Description', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => esc_html__( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);
		$this->add_control(
			'rael_team_short_description',
			array(
				'label'       => esc_html__( 'About Member', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'A small river named Duden flows by their place and supplies it with the necessary', 'responsive-addons-for-elementor' ),
				'placeholder' => esc_html__( 'About Member', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_team_show_short_description' => 'yes',
				),

			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_team_section_social',
			array(
				'label' => esc_html__( 'Social  Profiles', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_team_social_enable',
			array(
				'label'        => esc_html__( 'Display Social Profiles?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => esc_html__( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$social = new Repeater();

		$social->add_control(
			'rael_team_icons',
			array(
				'label'            => esc_html__( 'Icon', 'responsive-addons-for-elementor' ),
				'label_block'      => true,
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'rael_team_icon',
				'default'          => array(
					'value'   => 'fa fa-facebook',
					'library' => 'fa-solid',
				),
			)
		);

		$social->add_control(
			'rael_team_label',
			array(
				'label'   => esc_html__( 'Label', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Facebook',
			)
		);

		$social->add_control(
			'rael_team_link',
			array(
				'label'   => esc_html__( 'Link', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::URL,
				'default' => array(
					'is_external' => true,
				),
			)
		);

		$social->start_controls_tabs(
			'rael_team_socialmedia_tabs'
		);

		$social->start_controls_tab(
			'rael_team_socialmedia_normal',
			array(
				'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$social->add_control(
			'rael_team_socialmedia_icon_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} > a' => 'color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} > a svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$social->add_control(
			'rael_team_socialmedia_icon_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#a1a1a1',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} > a' => 'background-color: {{VALUE}};',
				),
			)
		);

		$social->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_team_socialmedia_border',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} > a',
			)
		);

		$social->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'rael_team_socialmedia_icon_normal_text_shadow',
				'label'    => esc_html__( 'Text Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} > a',
			)
		);

		$social->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_team_socialmedai_list_box_shadow',
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} > a',
			)
		);

		$social->end_controls_tab();

		$social->start_controls_tab(
			'rael_team_socialmedia_hover',
			array(
				'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$social->add_control(
			'rael_team_socialmedia_icon_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} > a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} > a:hover svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$social->add_control(
			'rael_team_socialmedia_icon_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#3b5998',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} > a:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$social->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_team_socialmedia_border_hover',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} > a:hover',
			)
		);

		$social->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'rael_team_socialmedia_icon_hover_text_shadow',
				'label'    => esc_html__( 'Text Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} > a:hover',
			)
		);

		$social->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_team_socialmedai_list_box_shadow_hover',
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} > a:hover',
			)
		);

		$social->end_controls_tab();
		// end hover tab.

		$social->end_controls_tabs();

		$this->add_control(
			'rael_team_social_icons',
			array(
				'label'       => esc_html__( 'Add Icon', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $social->get_controls(),
				'default'     => array(
					array(
						'rael_team_label' => esc_html__( 'Facebook', 'responsive-addons-for-elementor' ),
						'rael_team_icons' => array(
							'value'   => 'fa fa-facebook',
							'library' => 'fa-solid',
						),
						'rael_team_socialmedia_icon_hover_bg_color' => '#3b5998',
					),
					array(
						'rael_team_label' => esc_html__( 'Twitter', 'responsive-addons-for-elementor' ),
						'rael_team_icons' => array(
							'value'   => 'fa fa-twitter',
							'library' => 'fa-solid',
						),
						'rael_team_socialmedia_icon_hover_bg_color' => '#1da1f2',
					),
					array(
						'rael_team_label' => esc_html__( 'Pinterest', 'responsive-addons-for-elementor' ),
						'rael_team_icons' => array(
							'value'   => 'fa fa-pinterest',
							'library' => 'fa-solid',
						),
						'rael_team_socialmedia_icon_hover_bg_color' => '#e60023',
					),
				),
				'title_field' => '{{{ rael_team_label }}}',
				'condition'   => array(
					'rael_team_social_enable' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_team_popup_details',
			array(
				'label' => esc_html__( 'Pop Up Details', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_team_chose_popup',
			array(
				'label'   => esc_html__( 'Show Popup', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'rael_team_description',
			array(
				'label'       => esc_html__( 'About Member', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'A small river named Duden flows by their place and supplies it with the necessary', 'responsive-addons-for-elementor' ),
				'placeholder' => esc_html__( 'About Member', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_team_chose_popup' => 'yes',
				),

			)
		);
		$this->add_control(
			'rael_team_phone',
			array(
				'label'       => esc_html__( 'Phone', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '+1 (859) 254-6589',
				'placeholder' => esc_html__( 'Phone Number', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_team_chose_popup' => 'yes',
				),

			)
		);
		$this->add_control(
			'rael_team_email',
			array(
				'label'       => esc_html__( 'Email', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'info@example.com',
				'placeholder' => esc_html__( 'Email Address', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_team_chose_popup' => 'yes',
				),

			)
		);

		$this->add_control(
			'rael_team_close_icon_changes',
			array(
				'label'            => esc_html__( 'Close Icon', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'rael_team_close_icon_change',
				'default'          => array(
					'value'   => 'fa fa-times',
					'library' => 'fa-solid',
				),
				'label_block'      => true,
				'condition'        => array(
					'rael_team_chose_popup' => 'yes',
				),
				'separator'        => 'before',
			)
		);

		$this->add_control(
			'rael_team_close_icon_alignment',
			array(
				'label'     => esc_html__( 'Close Icon Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'  => array(
						'title' => esc_html__( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-team-modal-close' => '{{VALUE}}: 10px;',
				),
				'default'   => 'right',
				'condition' => array(
					'rael_team_chose_popup' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_team_content_style',
			array(
				'label' => esc_html__( 'Content', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs(
			'rael_team_background_tabs'
		);

		$this->start_controls_tab(
			'rael_team_content_normal',
			array(
				'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_team_background_content_normal',
				'label'    => esc_html__( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient', 'video' ),
				'selector' => '{{WRAPPER}} .profile-card, {{WRAPPER}} .profile-image-card',
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_team_content_box_shadow',
				'selector' => '{{WRAPPER}} .profile-card, {{WRAPPER}} .profile-image-card',
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_team_content_hover',
			array(
				'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_team_background_content_hover',
				'label'    => esc_html__( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .profile-card:hover, {{WRAPPER}} .profile-image-card:hover, {{WRAPPER}} .profile-card::before, {{WRAPPER}} .profile-image-card::before, {{WRAPPER}} div .profile-card .profile-body::before, {{WRAPPER}} .image-card-v3 .profile-image-card:after',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_team_content_box_shadow_hover_group',
				'selector' => '{{WRAPPER}} .profile-card:hover, {{WRAPPER}} .profile-image-card:hover',
			)
		);

		$this->add_control(
			'team_hover_animation',
			array(
				'label' => esc_html__( 'Hover Animation', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->add_responsive_control(
			'overlay_height',
			array(
				'label'      => esc_html__( 'Overlay Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 5,
					),
				),
				'default'    => array(
					'unit' => '%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-team-style-long_height_hover:after' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_team_style' => 'long_height_hover',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'content_tabs_after',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_responsive_control(
			'rael_team_content_max_weight',
			array(
				'label'      => esc_html__( 'Max Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 380,
				),
				'selectors'  => array(
					'{{WRAPPER}} .profile-square-v .profile-card' => 'max-height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_team_style' => 'hover_info',
				),
			)
		);

		$this->add_control(
			'rael_team_content_text_align',
			array(
				'label'                => esc_html__( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'text-left'   => array(
						'title' => esc_html__( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'text-center' => array(
						'title' => esc_html__( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'text-right'  => array(
						'title' => esc_html__( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors_dictionary' => array(
					'text-left'   => 'start',
					'text-center' => 'center',
					'text-right'  => 'end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .profile-card .profile-footer .rael-team-social-list, {{WRAPPER}} .profile-image-card .profile-footer .rael-team-social-list' => 'justify-content: {{VALUE}}',
					'{{WRAPPER}} .profile-card, {{WRAPPER}} .profile-image-card' => 'text-align: {{VALUE}}',
				),
				'default'              => 'text-center',
				'toggle'               => true,
			)
		);

		$this->add_responsive_control(
			'rael_team_content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .profile-card, {{WRAPPER}} .profile-image-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_team_content_inner_padding',
			array(
				'label'      => esc_html__( 'Content Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .profile-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_team_content_border_color_group',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .profile-card, {{WRAPPER}} .profile-image-card',
			)
		);

		$this->add_responsive_control(
			'rael_team_content_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .profile-card, {{WRAPPER}} .profile-image-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_team_content_overly_color_heading',
			array(
				'label'     => esc_html__( 'Hover Overy Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_team_style' => 'overlay_details',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'rael_team_content_overly_color',
				'label'     => esc_html__( 'Background', 'responsive-addons-for-elementor' ),
				'types'     => array( 'gradient' ),
				'selector'  => '{{WRAPPER}} .image-card-v2 .profile-image-card::before',
				'condition' => array(
					'rael_team_style' => 'overlay_details',
				),
			)
		);

		$this->add_control(
			'rael_team_remove_gutters',
			array(
				'label'        => esc_html__( 'Remove Gutter?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => esc_html__( 'Yes', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_team_image_style',
			array(
				'label' => esc_html__( 'Image', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_team_image_weight',
			array(
				'label'      => esc_html__( 'Image Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 300,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .profile-header > img, {{WRAPPER}} .profile-image-card img, {{WRAPPER}} .profile-image-card, {{WRAPPER}} .profile-header ' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'default'    => array(
					'unit' => 'px',
				),
			)
		);

		$this->add_responsive_control(
			'rael_team_image_height',
			array(
				'label'      => esc_html__( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 500,
					),
				),
				'condition'  => array(
					'team_style!' => 'overlay',
				),
				'selectors'  => array(
					'{{WRAPPER}} .profile-card .profile-header' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_team_image_height_margin_bottom',
			array(
				'label'      => esc_html__( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .profile-card .profile-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_team_image_width',
			array(
				'label'      => esc_html__( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 500,
					),
				),
				'condition'  => array(
					'team_style!' => 'overlay',
				),
				'selectors'  => array(
					'{{WRAPPER}} .profile-card .profile-header' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_team_image_shadow',
				'selector' => '{{WRAPPER}} .profile-card .profile-header',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'modal_img_shadow',
				'label'     => esc_html__( 'Box Shadow (Popup)', 'responsive-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .rael-team-modal-img > img',
				'condition' => array(
					'rael_team_chose_popup' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_team_image_border',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .profile-card .profile-header',
			)
		);

		$this->add_responsive_control(
			'rael_team_image_radius',
			array(
				'label'      => esc_html__( 'Border radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-team-img.profile-header > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'    => '50',
					'right'  => '50',
					'left'   => '50',
					'bottom' => '50',
					'unit'   => '%',
				),
			)
		);

		$this->add_responsive_control(
			'rael_team_image_margin',
			array(
				'label'      => esc_html__( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'condition'  => array(
					'team_style!' => 'overlay',
				),
				'selectors'  => array(
					'{{WRAPPER}} .profile-card .profile-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_team_image_background',
				'label'    => esc_html__( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .profile-card .profile-header',
			)
		);

		$this->add_control(
			'rael_team_default_img_overlay_h',
			array(
				'label'     => esc_html__( 'Overlay', 'responsive-addons-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_team_style' => 'default',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'rael_team_default_img_overlay',
				'label'     => esc_html__( 'Overlay', 'responsive-addons-for-elementor' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .rael-team-overlay__bg-wrapper .rael-team-overlay__bg-overlay',
				'condition' => array(
					'rael_team_style' => 'default',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_team_top_icon_style',
			array(
				'label'     => esc_html__( 'Icon', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_team_style'       => 'default',
					'rael_team_toggle_icon' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_team_top_icon_align',
			array(
				'label'   => esc_html__( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'start'  => array(
						'title' => esc_html__( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'end'    => array(
						'title' => esc_html__( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'toggle'  => true,
			)
		);

		$this->add_responsive_control(
			'rael_team_top_icon_margin',
			array(
				'label'      => esc_html__( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .profile-icon > i, {{WRAPPER}} .profile-icon > svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_team_top_icon_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .profile-icon > i, {{WRAPPER}} .profile-icon > svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_team_top_icon_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .profile-icon > i, {{WRAPPER}} .profile-icon > svg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'    => '50',
					'left'   => '50',
					'right'  => '50',
					'bottom' => '50',
					'unit'   => '%',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_team_top_icon_shadow',
				'selector' => '{{WRAPPER}} .profile-icon > i, {{WRAPPER}} .profile-icon > svg',
			)
		);

		$this->add_responsive_control(
			'rael_team_top_icon_fsize',
			array(
				'label'     => esc_html__( 'Font Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 300,
					),
				),
				'default'   => array(
					'size' => 22,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .profile-icon > i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .profile-icon > svg' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_team_top_icon_hw',
			array(
				'label'        => esc_html__( 'Use Height Width', 'responsive-addons-for-elementor' ),
				'description'  => esc_html__( 'For svg icon, We don\'t need this. We will use font size and padding for adjusting size.', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => esc_html__( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_responsive_control(
			'rael_team_top_icon_width',
			array(
				'label'      => esc_html__( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => 60,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .profile-icon > i' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_team_top_icon_hw' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_team_top_icon_height',
			array(
				'label'      => esc_html__( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => 60,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .profile-icon > i' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_team_top_icon_hw' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_team_top_icon_lheight',
			array(
				'label'      => esc_html__( 'Line Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => 60,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .profile-icon > i' => 'line-height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_team_top_icon_hw' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'top_icon_colors' );
		$this->start_controls_tab(
			'rael_team_top_icon_colors_normal',
			array(
				'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_control(
			'rael_team_top_icon_n_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .profile-icon > i'        => 'color: {{VALUE}};',
					'{{WRAPPER}} .profile-icon > svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_team_top_icon_n_bgcolor',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fc0467',
				'selectors' => array(
					'{{WRAPPER}} .profile-icon > i, {{WRAPPER}} .profile-icon > svg' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_team_top_icon_n_border',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .profile-icon > i, {{WRAPPER}} .profile-icon > svg',
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_team_top_icon_colors_hover',
			array(
				'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_control(
			'rael_team_top_icon_h_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .profile-icon > i:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .profile-icon > svg:hover path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_team_top_icon_h_bgcolor',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .profile-icon > i:hover, {{WRAPPER}} .profile-icon > svg:hover' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_team_top_icon_h_border',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .profile-icon > i:hover, {{WRAPPER}} .profile-icon > svg:hover',
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_team_name_style',
			array(
				'label' => esc_html__( 'Name', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_team_name_typography',
				'selector' => '{{WRAPPER}} .profile-body .profile-title',
			)
		);

		$this->start_controls_tabs(
			'rael_team_name_tabs'
		);

		$this->start_controls_tab(
			'rael_team_name_normal',
			array(
				'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_team_name_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .profile-body .profile-title' => 'color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_team_name_hover',
			array(
				'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_team_name_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .profile-body:hover .profile-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .profile-card:hover .profile-title' => 'color: {{VALUE}} !important',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_team_name_margin',
			array(
				'label'      => esc_html__( 'Margin Bottom', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .profile-body .profile-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_team_position_style',
			array(
				'label' => esc_html__( 'Position', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_team_position_typography',
				'selector' => '{{WRAPPER}} .profile-body .profile-designation',
			)
		);

		$this->start_controls_tabs(
			'rael_team_position_tabs'
		);

		$this->start_controls_tab(
			'rael_team_position_normal',
			array(
				'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_team_position_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .profile-body .profile-designation' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_team_position_hover',
			array(
				'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_team_position_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .profile-card:hover .profile-body .profile-designation,
                    {{WRAPPER}} .profile-body .profile-designation:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'rael_team_position_hover_shadow',
				'selector' => '{{WRAPPER}} .profile-card:hover .profile-body .profile-designation,
                    {{WRAPPER}} .profile-body .profile-designation:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_team_position_margin_bottom',
			array(
				'label'      => esc_html__( 'Margin Bottom', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 150,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),

				'selectors'  => array(
					'{{WRAPPER}} .profile-body .profile-designation' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_team_text_content_style_tab',
			array(
				'label' => esc_html__( 'Description', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_team_text_content_typography',
				'selector' => '{{WRAPPER}} .profile-body .profile-content',
			)
		);

		$this->start_controls_tabs(
			'rael_team_text_content_tabs'
		);

		$this->start_controls_tab(
			'rael_team_text_content_normal',
			array(
				'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_team_text_content_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .profile-body .profile-content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_team_text_content_hover',
			array(
				'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_team_text_content_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .profile-card:hover .profile-body .profile-content' => 'color: {{VALUE}};',
					'{{WRAPPER}} .profile-image-card:hover .profile-body .profile-content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_team_text_content_margin_bottom',
			array(
				'label'      => esc_html__( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .profile-body .profile-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_team_social_style',
			array(
				'label'     => esc_html__( 'Social  Profiles', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_team_social_enable' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_socialmedai_list_display',
			array(
				'label'     => esc_html__( 'Display', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'row',
				'options'   => array(
					'row'    => esc_html__( 'Row', 'responsive-addons-for-elementor' ),
					'column' => esc_html__( 'Column', 'responsive-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-team-social-list' => 'flex-direction: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_socialmedai_list_decoration_box',
			array(
				'label'     => esc_html__( 'Decoration', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => array(
					'none'         => esc_html__( 'None', 'responsive-addons-for-elementor' ),
					'underline'    => esc_html__( 'Underline', 'responsive-addons-for-elementor' ),
					'overline'     => esc_html__( 'Overline', 'responsive-addons-for-elementor' ),
					'line-through' => esc_html__( 'Line Through', 'responsive-addons-for-elementor' ),

				),
				'selectors' => array( '{{WRAPPER}} .rael-team-social-list > li > a' => 'text-decoration: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'rael_socialmedai_list_border_radius',
			array(
				'label'      => esc_html__( 'Border radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'    => '50',
					'right'  => '50',
					'bottom' => '50',
					'left'   => '50',
					'unit'   => '%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-team-social-list > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_socialmedai_list_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-team-social-list > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_socialmedai_list_style_use_height_and_width!' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_socialmedai_list_margin',
			array(
				'label'      => esc_html__( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-team-social-list > li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_socialmedai_list_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 5,
					),
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-team-social-list > li > a i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-team-social-list > li > a svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_socialmedai_list_style_use_height_and_width',
			array(
				'label'        => esc_html__( 'Use Height Width', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => esc_html__( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_responsive_control(
			'rael_socialmedai_list_width',
			array(
				'label'      => esc_html__( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 30,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-team-social-list > li > a' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_socialmedai_list_style_use_height_and_width' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_socialmedai_list_height',
			array(
				'label'      => esc_html__( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 30,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-team-social-list > li > a' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_socialmedai_list_style_use_height_and_width' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_socialmedai_list_line_height',
			array(
				'label'      => esc_html__( 'Line Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 30,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-team-social-list > li > a' => 'line-height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_socialmedai_list_style_use_height_and_width' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_team_overlay_style',
			array(
				'label'     => esc_html__( 'Overlay', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'team_style' => 'overlay',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_team_background_overlay',
				'label'    => esc_html__( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'gradient' ),
				'selector' => '{{WRAPPER}} .profile-image-card:before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_team_modal_style',
			array(
				'label'     => esc_html__( 'Modal Controls', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_team_chose_popup' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_team_modal_heading',
			array(
				'label'     => esc_html__( 'Modal', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_team_modal_background',
				'label'    => esc_html__( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-team-popup .modal-content',
			)
		);

		$this->add_control(
			'rael_team_modal_name_heading',
			array(
				'label'     => esc_html__( 'Name', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_team_modal_name_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-team-modal-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_team_modal_name_typography',
				'selector' => '{{WRAPPER}} .rael-team-modal-title',
			)
		);

		$this->add_responsive_control(
			'rael_team_modal_name_margin_bottom',
			array(
				'label'      => esc_html__( 'Margin Bottom', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 150,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-team-modal-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_team_modal_position_heading',
			array(
				'label'     => esc_html__( 'Position', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_team_modal_position_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-team-modal-position' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_team_modal_position_typography',
				'selector' => '{{WRAPPER}} .rael-team-modal-position',
			)
		);

		$this->add_responsive_control(
			'rael_team_modal_position_margin_bottom',
			array(
				'label'      => esc_html__( 'Margin Bottom', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 150,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-team-modal-position' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'modal_desc',
			array(
				'label'     => esc_html__( 'Description', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'modal_desc_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-team-modal-content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'modal_desc_font',
				'selector' => '{{WRAPPER}} .rael-team-modal-content',
			)
		);

		$this->add_responsive_control(
			'modal_desc_margin_bottom',
			array(
				'label'      => esc_html__( 'Margin Bottom', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 150,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-team-modal-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'more_options',
			array(
				'label'     => esc_html__( 'Phone and Email', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_team_info_typography',
				'selector' => '{{WRAPPER}} .rael-team-modal-list',
			)
		);

		$this->add_control(
			'rael_team_info_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-team-modal-list' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_team_info_hover_color',
			array(
				'label'     => esc_html__( 'Color Hover', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-team-modal-list a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_team_close_icon',
			array(
				'label'     => esc_html__( 'Close Icon', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_team_chose_popup' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'rael_icon_box_icon_colors' );

		$this->start_controls_tab(
			'rael_team_icon_colors_normal',
			array(
				'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_team_icon_primary_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#656565',
				'selectors' => array(
					'{{WRAPPER}} .rael-team-modal-close' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-team-modal-close svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_team_icon_secondary_color_normal',
			array(
				'label'     => esc_html__( 'Icon BG Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-team-modal-close' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_team_border',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-team-modal-close',
			)
		);

		$this->add_responsive_control(
			'rael_team_icon_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-team-modal-close' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_icon_icon_box_shadow_normal_group',
				'selector' => '{{WRAPPER}} .rael-team-modal-close',
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_team_icon_colors_hover',
			array(
				'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_team_hover_primary_color',
			array(
				'label'     => esc_html__( 'Icon Color (Hover)', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array(
					'{{WRAPPER}} .rael-team-modal-close:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-team-modal-close:hover svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_team_hover_background_color',
			array(
				'label'     => esc_html__( 'Icon BG Color (Hover)', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#656565',
				'selectors' => array(
					'{{WRAPPER}} .rael-team-modal-close:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_team_border_icon_group',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-team-modal-close:hover',
			)
		);

		$this->add_responsive_control(
			'rael_icon_box_icons_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-team-modal-close:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_team_shadow_group',
				'selector' => '{{WRAPPER}} .rael-team-modal-close:hover',
			)
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->add_responsive_control(
			'rael_team_close_icon_size',
			array(
				'label'     => esc_html__( 'Font Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-team-modal-close' => 'font-size: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-team-modal-close svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'rael_team_close_icon_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-team-modal-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_team_close_icon_enable_height_width',
			array(
				'label'        => esc_html__( 'Use Height Width', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_responsive_control(
			'rael_team_close_icon_width',
			array(
				'label'     => esc_html__( 'Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 10,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-team-modal-close' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_team_close_icon_enable_height_width' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_team_close_icon_height',
			array(
				'label'     => esc_html__( 'Height', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 10,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-team-modal-close' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_team_close_icon_enable_height_width' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_team_close_icon_line_height',
			array(
				'label'     => esc_html__( 'Line Height', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 10,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-team-modal-close' => 'line-height: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_team_close_icon_enable_height_width' => 'yes',
				),

			)
		);

		$this->add_responsive_control(
			'rael_team_close_icon_vertical_align',
			array(
				'label'     => esc_html__( 'Vertical Position ', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => -200,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementskit-infobox .elementskit-box-header .elementskit-info-box-icon' => ' -webkit-transform: translateY({{SIZE}}{{UNIT}}); -ms-transform: translateY({{SIZE}}{{UNIT}}); transform: translateY({{SIZE}}{{UNIT}});',
				),
				'condition' => array(
					'rael_icon_box_icon_position!' => 'top',
				),

			)
		);

		$this->end_controls_section();
	}
	/**
	 * Render method for displaying the team member.
	 *
	 * This method is responsible for rendering the HTML markup for a team member.
	 * It wraps the raw rendering content within a <div> element with the class "rael-team-member".
	 * Subclasses should implement the actual rendering logic in the render_raw method.
	 *
	 * @return void
	 */
	protected function render() {
		echo '<div class="rael-team-member">';
		$this->render_raw();
		echo '</div>';
	}
	/**
	 * Render raw content for the team member.
	 *
	 * This method is responsible for rendering the raw HTML markup for a team member.
	 * It extracts settings, handles image attributes, and renders different styles based on the configuration.
	 * The method includes logic for handling social icons, hover animations, and popups.
	 * Subclasses should implement additional styling logic as needed.
	 *
	 * @return void
	 */
	protected function render_raw() {
		$settings = $this->get_settings_for_display();
		extract( $settings ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

		// Image section.
		$image_html = '';
		if ( ! empty( $rael_team_image['url'] ) ) {
			$this->add_render_attribute( 'image', 'src', $rael_team_image['url'] );
			$this->add_render_attribute( 'image', 'alt', Control_Media::get_image_alt( $rael_team_image ) );
			$this->add_render_attribute( 'image', 'title', Control_Media::get_image_title( $rael_team_image ) );

			$image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'rael_team_thumbnail', 'rael_team_image' );
		}

		if ( ! isset( $rael_team_content_text_align ) ) {
			$rael_team_content_text_align = 'text-center';
		}

		$this->add_render_attribute(
			'profile_card',
			array(
				'class' => 'profile-card elementor-animation-' . $team_hover_animation . ' ' . $rael_team_content_text_align . ' rael-team-style-' . $rael_team_style,
			)
		);

		// Social List.
		if ( 'yes' === $rael_team_social_enable ) {
			foreach ( $rael_team_social_icons as $icon ) {
				$this->add_render_attribute( 'social_item_' . $icon['_id'], 'class', 'elementor-repeater-item-' . $icon['_id'] );

				$this->add_link_attributes( 'social_link_' . $icon['_id'], $icon['rael_team_link'] );
			}
		}

		if ( in_array( $rael_team_style, array( 'default', 'centered_style', 'centered_style_details', 'long_height_details', 'long_height_details_hover' ), true ) ) :
			?>
			<?php
			if ( 'centered_style' === $rael_team_style ) :
				?>
				<div class="profile-square-v"> <?php endif; ?>
			<?php
			if ( 'centered_style_details' === $rael_team_style ) :
				?>
				<div class="profile-square-v square-v5 no_gutters"> <?php endif; ?>
			<?php
			if ( 'long_height_details' === $rael_team_style ) :
				?>
				<div class="profile-square-v square-v6 no_gutters"> <?php endif; ?>
			<?php
			if ( 'long_height_details_hover' === $rael_team_style ) :
				?>
				<div class="profile-square-v square-v6 square-v6-v2 no_gutters"><?php endif; ?>

			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'profile_card' ) ); ?>>
				<?php if ( 'yes' === $settings['rael_team_chose_popup'] ) : ?>
				<a href="#rael_team_modal_<?php echo esc_attr( $this->get_id() ); ?>" class="rael-team-popup-modal">
					<?php endif; ?>
					<div class="profile-header rael-team-img 
					<?php
					if ( '' === $settings['rael_team_image']['id'] ) {
						echo esc_attr( 'no-rael-team-img' );}
					?>
					<?php echo esc_attr( 'default' === $rael_team_style ? 'rael-img-overlay rael-team-img-block' : '' ); ?>" 
						<?php if ( 'yes' === ( isset( $settings['rael_team_chose_popup'] ) ? $rael_team_chose_popup : 'no' ) ) : ?>
						data-toggle="modal" data-target="rael_team_modal_#<?php echo esc_attr( $this->get_id() ); ?>" <?php endif; ?>>
						<?php if ( 'default' === $rael_team_style ) : ?>
							<div class="rael-team-overlay__bg-wrapper">
								<div class="rael-team-overlay__bg-overlay"></div>
							</div>
						<?php endif; ?>
						<?php echo wp_kses_post( $image_html ); ?>
					</div><!-- .profile-header END -->
					<?php if ( 'yes' === $settings['rael_team_chose_popup'] ) : ?>
				</a>
			<?php endif; ?>


				<div class="profile-body">
					<?php if ( 'default' === $rael_team_style && 'yes' === $rael_team_toggle_icon && ! empty( $rael_team_top_icons ) ) : ?>
						<div class="profile-icon<?php echo esc_attr( $rael_team_top_icon_align ) ? ' icon-align-' . esc_attr( $rael_team_top_icon_align ) : ''; ?>">

							<?php
							// new icon.
							$migrated = isset( $settings['__fa4_migrated']['rael_team_top_icons'] );
							// Check if its a new widget without previously selected icon using the old Icon control.
							$is_new = empty( $settings['rael_team_top_icon'] );
							if ( $is_new || $migrated ) {
								// new icon.
								Icons_Manager::render_icon( $settings['rael_team_top_icons'], array( 'aria-hidden' => 'true' ) );
							} else {
								?>
								<i class="<?php echo esc_attr( $settings['rael_team_top_icon'] ); ?>"
									aria-hidden="true"></i>
								<?php
							}
							?>
						</div>
					<?php endif; ?>

					<h2 class="profile-title">
						<?php if ( 'yes' === $settings['rael_team_chose_popup'] ) : ?>
							<a href="#rael_team_modal_<?php echo esc_attr( $this->get_id() ); ?>" class="rael-team-popup-modal">
								<?php echo esc_html( $rael_team_name ); ?>
							</a>
						<?php else : ?>
							<?php echo esc_html( $rael_team_name ); ?>
						<?php endif; ?>
					</h2>
					<p class="profile-designation"><?php echo esc_html( $rael_team_position ); ?></p>
					<?php if ( 'yes' === $rael_team_show_short_description && '' !== $rael_team_short_description ) : ?>
						<p class="profile-content"><?php echo wp_kses_post( $rael_team_short_description ); ?></p>
					<?php endif; ?>
				</div><!-- .profile-body END -->

				<?php if ( isset( $rael_team_social_enable ) && 'yes' === $rael_team_social_enable ) { ?>
					<div class="profile-footer">
						<ul class="rael-team-social-list">
							<?php foreach ( $rael_team_social_icons as $icon ) { ?>
								<li <?php echo wp_kses_post( $this->get_render_attribute_string( 'social_item_' . $icon['_id'] ) ); ?>>
									<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'social_link_' . $icon['_id'] ) ); ?>>
										<?php \Elementor\Icons_Manager::render_icon( $icon['rael_team_icons'], array( 'aria-hidden' => 'true' ) ); ?>
									</a>
								</li>
							<?php } ?>
						</ul>
					</div>
					<?php
				}
				?>
			</div>
			<?php
			if ( in_array( $rael_team_style, array( 'centered_style', 'centered_style_details', 'long_height_details', 'long_height_details_hover' ), true ) ) :
				?>
				</div> <?php endif; ?>
		<?php endif; ?>

		<?php if ( in_array( $rael_team_style, array( 'overlay', 'overlay_details', 'long_height_hover', 'overlay_circle', 'overlay_circle_hover' ), true ) ) : ?>
			<?php if ( 'overlay_details' === $rael_team_style ) : ?>
		<div class="image-card-v2"> <?php endif; ?>
			<?php if ( 'long_height_hover' === $rael_team_style ) : ?>
		<div class="<?php echo esc_attr( 'yes' === $settings['rael_team_remove_gutters'] ? '' : 'small-gutters' ); ?> image-card-v3"> <?php endif; ?>
			<?php if ( 'overlay_circle' === $rael_team_style ) : ?>
		<div class="style-circle rael-team-img-fit"> <?php endif; ?>
			<?php if ( 'overlay_circle_hover' === $rael_team_style ) : ?>
		<div class="image-card-v2 style-circle"> <?php endif; ?>
			<div class="profile-image-card elementor-animation-<?php echo esc_attr( $team_hover_animation ); ?> rael-team-img rael-team-style-<?php echo esc_attr( $rael_team_style ); ?> <?php
			if ( isset( $rael_team_content_text_align ) ) {
				echo esc_attr( $rael_team_content_text_align );
			}
			?>
			"><?php //phpcs:ignore Generic.WhiteSpace.ScopeIndent.IncorrectExact ?>

				<?php if ( 'long_height_hover' === $rael_team_style ) { ?>
					<?php echo wp_kses_post( $image_html ); ?>
					<?php
					$modal_class = 'team-sidebar_' . $rael_team_style . '';
				} else {
					$modal_class = 'team-modal_' . $rael_team_style . '';
					?>
					<?php echo wp_kses_post( $image_html ); ?>
				<?php } ?>
				<div class="hover-area">
					<div class="profile-body">
						<h2 class="profile-title">
							<?php if ( 'yes' === $settings['rael_team_chose_popup'] ) : ?>
								<a href="#rael_team_modal_<?php echo esc_attr( $this->get_id() ); ?>"
									class="rael-team-popup-modal">
									<?php echo esc_html( $rael_team_name ); ?>
								</a>
							<?php else : ?>
								<?php echo esc_html( $rael_team_name ); ?>
							<?php endif; ?>
						</h2>
						<p class="profile-designation"><?php echo esc_html( $rael_team_position ); ?></p>
						<?php if ( 'yes' === $rael_team_show_short_description && '' !== $rael_team_short_description ) : ?>
							<p class="profile-content"><?php echo wp_kses_post( $rael_team_short_description ); ?></p>
						<?php endif; ?>
					</div>
					<?php if ( isset( $rael_team_social_enable ) && 'yes' === $rael_team_social_enable ) { ?>
						<div class="profile-footer">
							<ul class="rael-team-social-list">
								<?php foreach ( $rael_team_social_icons as $icon ) { ?>
									<li <?php echo wp_kses_post( $this->get_render_attribute_string( 'social_item_' . $icon['_id'] ) ); ?>>
										<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'social_link_' . $icon['_id'] ) ); ?>>
											<?php \Elementor\Icons_Manager::render_icon( $icon['rael_team_icons'], array( 'aria-hidden' => 'true' ) ); ?>
										</a>
									</li>
								<?php } ?>
							</ul>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			<?php if ( in_array( $rael_team_style, array( 'overlay_details', 'long_height_hover', 'overlay_circle', 'overlay_circle_hover' ), true ) ) : ?>
		</div> <?php endif; ?>

			<?php
	endif;
		if ( 'hover_info' === $rael_team_style ) :
			?>

			<div class="profile-square-v square-v4 elementor-animation-<?php echo esc_attr( $team_hover_animation ); ?> rael-team-style-<?php echo esc_attr( $rael_team_style ); ?>">
				<div class="profile-card 
					<?php
					if ( isset( $rael_team_content_text_align ) ) {
						echo esc_attr( $rael_team_content_text_align );
					}
					?>
					">
					<div class="profile-header rael-team-img" 
					<?php
					if ( 'yes' === $settings['rael_team_chose_popup'] ) :
						?>
						data-toggle="modal" data-target="#rael_team_modal_<?php echo esc_attr( $this->get_id() ); ?>" <?php endif; ?>>
						<?php echo wp_kses_post( $image_html ); ?>
					</div><!-- .profile-header END -->
					<div class="profile-body">
						<h2 class="profile-title">
							<?php if ( 'yes' === $settings['rael_team_chose_popup'] ) : ?>
								<a href="#rael_team_modal_<?php echo esc_attr( $this->get_id() ); ?>"
									class="rael-team-popup-modal">
									<?php echo esc_html( $rael_team_name ); ?>
								</a>
							<?php else : ?>
								<?php echo esc_html( $rael_team_name ); ?>
							<?php endif; ?>
						</h2>
						<p class="profile-designation"><?php echo esc_html( $rael_team_position ); ?></p>
						<?php if ( 'yes' === $rael_team_show_short_description && '' !== $rael_team_short_description ) : ?>
							<p class="profile-content"><?php echo wp_kses_post( $rael_team_short_description ); ?></p>
						<?php endif; ?>
						<?php if ( isset( $rael_team_social_enable ) && 'yes' === $rael_team_social_enable ) { ?>
							<ul class="rael-team-social-list">
								<?php foreach ( $rael_team_social_icons as $icon ) { ?>
									<li <?php echo wp_kses_post( $this->get_render_attribute_string( 'social_item_' . $icon['_id'] ) ); ?>>
										<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'social_link_' . $icon['_id'] ) ); ?>>
											<?php \Elementor\Icons_Manager::render_icon( $icon['rael_team_icons'], array( 'aria-hidden' => 'true' ) ); ?>
										</a>
									</li>
								<?php } ?>
							</ul>
							<?php
						}
						?>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( 'yes' === $rael_team_chose_popup ) : ?>
		<div class="zoom-anim-dialog mfp-hide rael-team-popup"
				id="rael_team_modal_<?php echo esc_attr( $this->get_id() ); ?>" tabindex="-1" role="dialog"
				aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<button type="button" class="rael-team-modal-close">
						<?php Icons_Manager::render_icon( $rael_team_close_icon_changes, array( 'aria-hidden' => 'true' ) ); ?>
					</button>

					<div class="modal-body">
						<?php if ( ! empty( $image_html ) ) { ?>
							<div class="rael-team-modal-img">
								<?php echo wp_kses_post( $image_html ); ?>
							</div>
						<?php } ?>

						<div class="rael-team-modal-info<?php echo ! empty( $image_html ) ? ' has-img' : ''; ?>">
							<h2 class="rael-team-modal-title"><?php echo esc_html( $rael_team_name ); ?></h2>
							<p class="rael-team-modal-position"><?php echo esc_html( $rael_team_position ); ?></p>

							<div class="rael-team-modal-content">
								<?php echo wp_kses_post( $rael_team_description ); ?>
							</div>

							<?php if ( $rael_team_phone || $rael_team_email ) { ?>
								<ul class="rael-team-modal-list">
									<?php if ( $rael_team_phone ) : ?>
										<li><strong><?php esc_html_e( 'Phone', 'responsive-addons-for-elementor' ); ?>:</strong><a
													href="tel:<?php echo esc_attr( urlencode( $rael_team_phone ) ); ?>"><?php echo esc_html( $rael_team_phone ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.urlencode_urlencode ?></a>
										</li>
									<?php endif; ?>

									<?php if ( $rael_team_email ) : ?>
										<li><strong><?php esc_html_e( 'Email', 'responsive-addons-for-elementor' ); ?>:</strong><a
													href="mailto:<?php echo esc_attr( $rael_team_email ); ?>"><?php echo esc_html( $rael_team_email ); ?></a>
										</li>
									<?php endif; ?>
								</ul>
							<?php } ?>

							<?php if ( isset( $rael_team_social_enable ) && 'yes' === $rael_team_social_enable ) { ?>
								<ul class="rael-team-social-list">
									<?php foreach ( $rael_team_social_icons as $icon ) { ?>
										<li <?php echo wp_kses_post( $this->get_render_attribute_string( 'social_item_' . $icon['_id'] ) ); ?>>
											<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'social_link_' . $icon['_id'] ) ); ?>>
												<?php \Elementor\Icons_Manager::render_icon( $icon['rael_team_icons'], array( 'aria-hidden' => 'true' ) ); ?>
											</a>
										</li>
									<?php } ?>
								</ul>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
		<?php
	}
}
