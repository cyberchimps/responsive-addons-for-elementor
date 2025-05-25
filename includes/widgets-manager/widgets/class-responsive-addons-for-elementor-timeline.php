<?php
/**
 * RAEL Timeline widget
 *
 * @since   1.0.0
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Control_Media;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * RAEL Timeline widget class.
 *
 * @since 1.0.0
 */
class Responsive_Addons_For_Elementor_Timeline extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-timeline';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Timeline', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve timeline widget icon.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-time-line rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the timeline widget belongs to.
	 *
	 * @since  1.0.0
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
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'timeline', 'todo', 'task', 'plan', 'planner', 'schedule' );
	}

	/**
	 * Get Custom help URL
	 *
	 * @since  1.0.0
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return esc_url( 'https://cyberchimps.com/docs/widgets/timeline' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function register_controls() {  // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		// Content Tab.
		$this->register_content_timeline();
		$this->register_content_settings();

		// Style Tab.
		$this->register_style_content_box();
		$this->register_style_icon_box();
		$this->register_style_title();
		$this->register_style_time_and_date();
		$this->register_style_button();
	}

	/**
	 * Render Image Sizes for Timeline Widget.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function timeline_render_image_sizes() {
		$wp_image_sizes = Group_Control_Image_Size::get_all_image_sizes();
		foreach ( $wp_image_sizes as $size_key => $size_attributes ) {
			$control_title = ucwords( str_replace( '_', ' ', $size_key ) );
			if ( is_array( $size_attributes ) ) {
				$control_title .= sprintf( ' - %d x %d', $size_attributes['width'], $size_attributes['height'] );
			}

			$image_sizes[ $size_key ] = $control_title;
		}
		$image_sizes['full'] = esc_html__( 'Full', 'responsive-addons-for-elementor' );
		return $image_sizes;
	}

	/**
	 * Register Timeline controls under Content Tab.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_content_timeline() {
		$this->start_controls_section(
			'rael_timeline_content_section_timeline_controls',
			array(
				'label' => __( 'Timeline', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'rael_timeline_timeline_controls_tabs' );

		$repeater->start_controls_tab(
			'rael_timeline_content_tab',
			array(
				'label' => __( 'Content', 'responsive-addons-for-elementor' ),
			)
		);

		$repeater->add_control(
			'rael_icon_type',
			array(
				'label'   => __( 'Icon Type', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'icon'  => __( 'Icon', 'responsive-addons-for-elementor' ),
					'image' => __( 'Image', 'responsive-addons-for-elementor' ),
				),
				'default' => 'icon',
			)
		);

		$repeater->add_control(
			'rael_icon',
			array(
				'label'     => __( 'Icon', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-calendar-alt',
					'library' => 'solid',
				),
				'condition' => array(
					'rael_icon_type' => 'icon',
				),
			)
		);

		$repeater->add_control(
			'rael_image',
			array(
				'label'     => __( 'Image', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'rael_icon_type' => 'image',
				),
			)
		);

		$repeater->add_control(
			'rael_time_type',
			array(
				'label'   => __( 'Time', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'calendar' => __( 'Calendar', 'responsive-addons-for-elementor' ),
					'text'     => __( 'Text', 'responsive-addons-for-elementor' ),
				),
				'default' => 'calendar',
			)
		);

		$repeater->add_control(
			'rael_calendar_time',
			array(
				'label'      => __( 'Calendar Time', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DATE_TIME,
				'show_label' => false,
				'default'    => gmdate( 'M d Y g:i a' ),
				'condition'  => array(
					'rael_time_type' => 'calendar',
				),
			)
		);

		$repeater->add_control(
			'rael_text_time',
			array(
				'label'       => __( 'Text Time', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'show_label'  => false,
				'placeholder' => __( 'Text Time', 'responsive-addons-for-elementor' ),
				'default'     => __( '2014 - 2021', 'responsive-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'rael_time_type' => 'text',
				),
			)
		);

		$repeater->add_control(
			'rael_title',
			array(
				'label'       => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => 'This is the title',
				'placeholder' => __( 'Title', 'responsive-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'rael_gallery',
			array(
				'type' => Controls_Manager::GALLERY,
			)
		);

		$repeater->add_control(
			'rael_image_size',
			array(
				'label'   => __( 'Image Resolution', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->timeline_render_image_sizes(),
				'default' => 'thumbnail',
			)
		);

		$repeater->add_control(
			'rael_image_position',
			array(
				'label'   => __( 'Image Position', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'before' => __( 'Before Title', 'responsive-addons-for-elementor' ),
					'after'  => __( 'After Content', 'responsive-addons-for-elementor' ),
				),
				'default' => 'before',
			)
		);

		$repeater->add_control(
			'rael_content',
			array(
				'label'       => __( 'Content', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::WYSIWYG,
				'placeholder' => __( 'Content', 'responsive-addons-for-elementor' ),
				'default'     => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut',
			)
		);

		$repeater->add_control(
			'rael_button_text',
			array(
				'label'       => __( 'Button Text', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Button Text', 'responsive-addons-for-elementor' ),
				'default'     => 'Read More',
			)
		);

		$repeater->add_control(
			'rael_button_link',
			array(
				'label'     => __( 'Button Link', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::URL,
				'default'   => array(
					'url'         => '#',
					'is_external' => true,
					'nofollow'    => true,
				),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'rael_button_text!' => '',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'rael_timeline_style_tab',
			array(
				'label' => __( 'Style', 'responsive-addons-for-elementor' ),
			)
		);

		$repeater->add_control(
			'rael_individual_item_icon_color',
			array(
				'label'     => __( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .rael-timeline__icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .rael-timeline__icon svg' => 'fill: {{VALUE}}',
				),
				'condition' => array(
					'rael_icon_type' => 'icon',
				),
			)
		);

		$repeater->add_control(
			'rael_individual_item_icon_box_bg_color',
			array(
				'label'     => __( 'Icon Box Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .rael-timeline__icon' => 'background: {{VALUE}};',
				),
			)
		);

		$repeater->add_control(
			'rael_individual_item_icon_box_border_color',
			array(
				'label'       => __( 'Icon Box Border Color', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'Color will apply after setting the icon box border from the Style section.', 'responsive-addons-for-elementor' ),
				'selectors'   => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .rael-timeline__icon' => 'border-color: {{VALUE}};',
				),
			)
		);

		$repeater->add_control(
			'rael_individual_icon_box_tree_color',
			array(
				'label'     => __( 'Icon Box Tree Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .rael-timeline__tree' => 'background: {{VALUE}};',
				),
			)
		);

		$repeater->add_responsive_control(
			'rael_individual_content_box_alignment',
			array(
				'label'     => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
					'justify' => array(
						'title' => __( 'Justify', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .rael-timeline-wrapper > {{CURRENT_ITEM}} .rael-timeline__content' => 'text-align: {{VALUE}};',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'rael_timeline',
			array(
				'label'       => __( 'Content List', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'rael_icon_type'      => 'icon',
						'rael_icon'           => array(
							'value'   => 'fas fa-calendar-alt',
							'library' => 'solid',
						),
						'rael_image'          => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'rael_time'           => gmdate( 'M d Y g:i a' ),
						'rael_title'          => 'This is first title',
						'rael_image_position' => 'before',
						'rael_content'        => '<p>' . __( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut', 'responsive-addons-for-elementor' ) . '</p>',
						'rael_button_text'    => 'Button Text',
						'rael_button_link'    => array(
							'url'         => '#',
							'is_external' => true,
							'nofollow'    => true,
						),
					),
					array(
						'rael_icon_type'      => 'icon',
						'rael_icon'           => array(
							'value'   => 'fas fa-calendar-alt',
							'library' => 'solid',
						),
						'rael_image'          => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'rael_time'           => gmdate( 'M d Y g:i a' ),
						'rael_title'          => 'This is second title',
						'rael_image_position' => 'before',
						'rael_content'        => '<p>' . __( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut', 'responsive-addons-for-elementor' ) . '</p>',
						'rael_button_text'    => 'Button Text',
						'rael_button_link'    => array(
							'url'         => '#',
							'is_external' => true,
							'nofollow'    => true,
						),
					),
				),
				'title_field' => '{{{ rael_title }}}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Timeline settings controls under Content Tab.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_content_settings() {
		$this->start_controls_section(
			'rael_timeline_content_section_settings_controls',
			array(
				'label' => __( 'Settings', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_show_date',
			array(
				'label'        => __( 'Show Date?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'SHOW', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'HIDE', 'responsive-addons-for-elementor' ),
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_show_time',
			array(
				'label'        => __( 'Show Time?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'SHOW', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'HIDE', 'responsive-addons-for-elementor' ),
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_show_content_arrow',
			array(
				'label'        => __( 'Show Content Arrow?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'SHOW', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'HIDE', 'responsive-addons-for-elementor' ),
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_title_tag',
			array(
				'label'   => __( 'Title Tag', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'h2',
				'options' => array(
					'h1' => array(
						'title' => __( 'H1', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-editor-h1',
					),
					'h2' => array(
						'title' => __( 'H2', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-editor-h2',
					),
					'h3' => array(
						'title' => __( 'H3', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-editor-h3',
					),
					'h4' => array(
						'title' => __( 'H4', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-editor-h4',
					),
					'h5' => array(
						'title' => __( 'H5', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-editor-h5',
					),
					'h6' => array(
						'title' => __( 'H6', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-editor-h6',
					),
				),
				'toggle'  => false,
			)
		);

		$this->add_control(
			'rael_icon_box_alignment',
			array(
				'label'        => __( 'Icon Box Alignment', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'top'    => array(
						'title' => __( 'Top', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'toggle'       => false,
				'default'      => 'top',
				'prefix_class' => 'rael-timeline__icon-box--vertical-align-',
			)
		);

		$this->add_control(
			'rael_tree_alignment',
			array(
				'label'        => __( 'Tree Alignment', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
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
				'default'      => 'center',
				'toggle'       => false,
				'prefix_class' => 'rael-timeline__tree--align-',
			)
		);

		$this->add_control(
			'rael_show_scroll_tree',
			array(
				'label'        => __( 'Show Scroll Tree?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'rael_scroll_tree_background',
				'label'     => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .rael-timeline-scroll-tree .rael-timeline__icon, {{WRAPPER}} .rael-timeline__tree-inner',
				'condition' => array(
					'rael_show_scroll_tree' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Content Box controls under Style Tab.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_style_content_box() {
		$this->start_controls_section(
			'rael_timeline_style_section_content_box_controls',
			array(
				'label' => __( 'Content Box', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'name'     => 'rael_content_box',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-timeline__content',
			)
		);

		$this->add_control(
			'rael_content_box_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-timeline__content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_content_box_arrow_color',
			array(
				'label'     => __( 'Arrow Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					// Center Alignment.
					'(desktop){{WRAPPER}}.rael-timeline__tree--align-center .rael-timeline__content.arrow::before' => 'border-left-color: {{VALUE}};',
					'(desktop){{WRAPPER}}.rael-timeline__tree--align-center .rael-timeline__item:nth-child(even) .rael-timeline__content.arrow::before' => 'border-right-color: {{VALUE}}; border-left-color: transparent;',
					'(tablet){{WRAPPER}}.rael-timeline__tree--align-center .rael-timeline__content.arrow::before' => 'border-right-color: {{VALUE}}; border-left-color: transparent;',
					'(tablet){{WRAPPER}}.rael-timeline__tree--align-center .rael-timeline__item:nth-child(even) .rael-timeline__content.arrow::before' => 'border-right-color: {{VALUE}}; border-left-color: transparent;',
					'(mobile){{WRAPPER}}.rael-timeline__tree--align-center .rael-timeline__content.arrow::before' => 'border-right-color: {{VALUE}}; border-left-color: transparent;',
					'(mobile){{WRAPPER}}.rael-timeline__tree--align-center .rael-timeline__item:nth-child(even) .rael-timeline__content.arrow::before' => 'border-right-color: {{VALUE}}; border-left-color: transparent;',
					// Left Alignment.
					'{{WRAPPER}}.rael-timeline__tree--align-left .rael-timeline__content.arrow::before' => 'border-right-color: {{VALUE}};',
					// Right Alignment.
					'(desktop){{WRAPPER}}.rael-timeline__tree--align-right .rael-timeline__content.arrow::before' => 'border-left-color: {{VALUE}}; border-right-color: transparent;',
					'(tablet){{WRAPPER}}.rael-timeline__tree--align-right .rael-timeline__content.arrow::before' => 'border-right-color: {{VALUE}}; border-left-color: transparent;',
					'(mobile){{WRAPPER}}.rael-timeline__tree--align-right .rael-timeline__content.arrow::before' => 'border-right-color: {{VALUE}}; border-left-color: transparent;',
				),
				'condition' => array(
					'rael_show_content_arrow' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'label'    => __( 'Background Type', 'responsive-addons-for-elementor' ),
				'name'     => 'rael_content_box_background',
				'types'    => array( 'classic', 'gradient', 'video' ),
				'selector' => '{{WRAPPER}} .rael-timeline__content',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'label'     => __( 'Border Type', 'responsive-addons-for-elementor' ),
				'name'      => 'rael_content_box_border',
				'selector'  => '{{WRAPPER}} .rael-timeline__content',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'name'     => 'rael_content_box_box_shadow',
				'selector' => '{{WRAPPER}} .rael-timeline__content',
			)
		);

		$this->add_responsive_control(
			'rael_content_box_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-timeline__content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_content_box_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-timeline__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_content_box_margin_bottom',
			array(
				'label'      => __( 'Margin Bottom', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-timeline__content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-timeline__item:last-child .rael-timeline__content' => 'margin-bottom: 0;',
					'{{WRAPPER}} .rael-timeline__icon-box--vertical-align-center .rael-timeline__icon' => 'margin-top: calc(-{{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .rael-timeline__icon-box--vertical-align-center .rael-timeline__item:last-child .rael-timeline__icon' => 'margin-top: 0;',
					'{{WRAPPER}} .rael-timeline__icon-box--vertical-align-bottom .rael-timeline__icon' => 'margin-top: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-timeline__icon-box--vertical-align-bottom .rael-timeline__item:last-child .rael-timeline__icon' => 'margin-top: 0;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Icon Box controls under Style Tab.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_style_icon_box() {
		$this->start_controls_section(
			'rael_timeline_style_section_icon_box_controls',
			array(
				'label' => __( 'Icon Box', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_timeline_icon_box_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-timeline__icon' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-timeline__item:nth-child(even) .rael-timeline__icon' => 'width: {{SIZE}}{{UNIT}};',
					'(desktop){{WRAPPER}}.rael-timeline__tree--align-center .rael-timeline__content' => 'width: calc(50% - (({{rael_timeline_icon_box_width.SIZE || 48}}{{UNIT}}/2) + {{rael_icon_box_spacing.SIZE || 30}}{{UNIT}}));',
					'(tablet){{WRAPPER}}.rael-timeline__tree--align-center .rael-timeline__content' => 'width: calc(100% - (({{rael_timeline_icon_box_width_tablet.SIZE || 40}}{{UNIT}}/2) + {{rael_icon_box_spacing.SIZE || 35}}{{UNIT}}));',
					'(mobile){{WRAPPER}}.rael-timeline__tree--align-center .rael-timeline__content' => 'width: calc(100% - (({{rael_timeline_icon_box_width_mobile.SIZE || 40}}{{UNIT}}/2) + {{rael_icon_box_spacing.SIZE || 35}}{{UNIT}}));',
					'(desktop){{WRAPPER}}.rael-timeline__tree--align-left .rael-timeline__content' => 'width: calc(100% - ({{rael_timeline_icon_box_width.SIZE || 48}}{{UNIT}} + {{rael_icon_box_spacing.SIZE || 30}}{{UNIT}} + {{rael_icon_box_tree_spacing.SIZE || 110}}{{UNIT}}));',
					'(tablet){{WRAPPER}}.rael-timeline__tree--align-left .rael-timeline__content' => 'width: calc(100% - ({{rael_timeline_icon_box_width_tablet.SIZE || 40}}{{UNIT}} + {{rael_icon_box_spacing_tablet.SIZE || 30}}{{UNIT}} + {{rael_icon_box_tree_spacing_tablet.SIZE || 0}}{{UNIT}}));',
					'(mobile){{WRAPPER}}.rael-timeline__tree--align-left .rael-timeline__content' => 'width: calc(100% - ({{rael_timeline_icon_box_width_mobile.SIZE || 40}}{{UNIT}} + {{rael_icon_box_spacing_mobile.SIZE || 30}}{{UNIT}} + {{rael_icon_box_tree_spacing_mobile.SIZE || 0}}{{UNIT}}));',
					'(desktop){{WRAPPER}}.rael-timeline__tree--align-right .rael-timeline__content' => 'width: calc(100% - ({{rael_timeline_icon_box_width.SIZE || 48}}{{UNIT}} + {{rael_icon_box_spacing.SIZE || 30}}{{UNIT}} + {{rael_icon_box_tree_spacing.SIZE || 110}}{{UNIT}}));',
					'(tablet){{WRAPPER}}.rael-timeline__tree--align-right .rael-timeline__content' => 'width: calc(100% - ({{rael_timeline_icon_box_width_tablet.SIZE || 40}}{{UNIT}} + {{rael_icon_box_spacing_tablet.SIZE || 30}}{{UNIT}} + {{rael_icon_box_tree_spacing_tablet.SIZE || 0}}{{UNIT}}));',
					'(mobile){{WRAPPER}}.rael-timeline__tree--align-right .rael-timeline__content' => 'width: calc(100% - ({{rael_timeline_icon_box_width_mobile.SIZE || 40}}{{UNIT}} + {{rael_icon_box_spacing_mobile.SIZE || 30}}{{UNIT}} + {{rael_icon_box_tree_spacing_mobile.SIZE || 0}}{{UNIT}}));',
				),
			)
		);

		$this->add_responsive_control(
			'rael_timeline_icon_box_height',
			array(
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-timeline__icon' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-timeline__item:nth-child(even) .rael-timeline__icon' => 'height: {{SIZE}}{{UNIT}};',
					'(desktop){{WRAPPER}} .rael-timeline__icon-box--vertical-align-top .rael-timeline__content.arrow::before' => 'top: calc(({{rael_timeline_icon_box_height.SIZE}}{{UNIT}}/2) - 8px);',
					'(desktop){{WRAPPER}} .rael-timeline__icon-box--vertical-align-bottom .rael-timeline__content.arrow::before' => 'bottom: calc(({{rael_timeline_icon_box_height.SIZE}}{{UNIT}}/2) - 8px);',
				),
			)
		);

		$this->add_responsive_control(
			'rael_icon_box_spacing',
			array(
				'label'      => __( 'Box Space', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'selectors'  => array(
					'(desktop){{WRAPPER}}.rael-timeline__tree--align-center .rael-timeline__icon-box' => 'margin-left: {{rael_icon_box_spacing.SIZE || 30}}{{UNIT}};margin-right: 0;',
					'(desktop){{WRAPPER}}.rael-timeline__tree--align-center .rael-timeline__item:nth-child(even) .rael-timeline__icon-box' => 'margin-left: 0;margin-right: {{rael_icon_box_spacing.SIZE || 30}}{{UNIT}};',
					'(tablet){{WRAPPER}}.rael-timeline__tree--align-center .rael-timeline__icon-box' => 'margin-right: {{rael_icon_box_spacing.SIZE || 35}}{{UNIT}};margin-left: 0;',
					'(tablet){{WRAPPER}}.rael-timeline__tree--align-center .rael-timeline__item:nth-child(even) .rael-timeline__icon-box' => 'margin-left: 0;margin-right: {{rael_icon_box_spacing.SIZE || 35}}{{UNIT}};',
					'(mobile){{WRAPPER}}.rael-timeline__tree--align-center .rael-timeline__icon-box' => 'margin-right: {{rael_icon_box_spacing.SIZE || 35}}{{UNIT}};margin-left: 0;',
					'(mobile){{WRAPPER}}.rael-timeline__tree--align-center .rael-timeline__item:nth-child(even) .rael-timeline__icon-box' => 'margin-left: 0;margin-right: {{rael_icon_box_spacing.SIZE || 35}}{{UNIT}};',

				),
			)
		);

		$this->add_responsive_control(
			'rael_icon_box_tree_spacing',
			array(
				'label'      => __( 'Tree Space', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'selectors'  => array(
					'(desktop){{WRAPPER}}.rael-timeline__tree--align-left .rael-timeline__item .rael-timeline__icon-box' => 'margin-right: {{rael_icon_box_spacing.SIZE || 30}}{{UNIT}};margin-left: {{rael_icon_box_tree_spacing.SIZE || 110}}{{UNIT}};',
					'(tablet){{WRAPPER}}.rael-timeline__tree--align-left .rael-timeline__item .rael-timeline__icon-box' => 'margin-right: {{rael_icon_box_spacing_tablet.SIZE || 30}}{{UNIT}};margin-left: {{rael_icon_box_tree_spacing_tablet.SIZE || 0}}{{UNIT}};',
					'(mobile){{WRAPPER}}.rael-timeline__tree--align-left .rael-timeline__item .rael-timeline__icon-box' => 'margin-right: {{rael_icon_box_spacing_mobile.SIZE || 30}}{{UNIT}};margin-left: {{rael_icon_box_tree_spacing_mobile.SIZE || 0}}{{UNIT}};',
					'(desktop){{WRAPPER}}.rael-timeline__tree--align-right .rael-timeline__item .rael-timeline__icon-box' => 'margin-left: {{rael_icon_box_spacing.SIZE || 30}}{{UNIT}};margin-right: {{rael_icon_box_tree_spacing.SIZE || 110}}{{UNIT}};',
					'(tablet){{WRAPPER}}.rael-timeline__tree--align-right .rael-timeline__item .rael-timeline__icon-box' => 'margin-right: {{rael_icon_box_spacing_tablet.SIZE || 30}}{{UNIT}};margin-left: {{rael_icon_box_tree_spacing_tablet.SIZE || 0}}{{UNIT}};',
					'(mobile){{WRAPPER}}.rael-timeline__tree--align-right .rael-timeline__item .rael-timeline__icon-box' => 'margin-right: {{rael_icon_box_spacing_mobile.SIZE || 30}}{{UNIT}};margin-left: {{rael_icon_box_tree_spacing_mobile.SIZE || 0}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_tree_alignment!' => 'center',
				),
			)
		);

		$this->add_control(
			'rael_icon_box_background',
			array(
				'label'     => __( 'Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-timeline__icon' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_icon_box_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-timeline__icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_icon_box',
				'label'    => __( 'Border Type', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-timeline__icon',
			)
		);

		$this->add_control(
			'rael_icon_color',
			array(
				'label'     => __( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .rael-timeline__icon i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-timeline__icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_icon_box_tree_width',
			array(
				'label'      => __( 'Tree Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-timeline__tree' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-timeline__tree-inner' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_icon_box_tree_color',
			array(
				'label'     => __( 'Tree Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-timeline__tree' => 'background: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Title controls under Style Tab.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_style_title() {
		$this->start_controls_section(
			'rael_timeline_style_section_title_controls',
			array(
				'label' => __( 'Title', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_title',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .rael-timeline__title',
			)
		);

		$this->add_control(
			'rael_title_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-timeline__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_title_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-timeline__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Time and Date controls under Style Tab.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_style_time_and_date() {
		$this->start_controls_section(
			'rael_timeline_style_section_time_date_controls',
			array(
				'label'      => __( 'Time & Date Typography', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'terms' => array(
						array(
							'relation' => 'or',
							'terms'    => array(
								array(
									'name'     => 'rael_show_date',
									'operator' => '==',
									'value'    => 'yes',
								),
								array(
									'name'     => 'rael_show_time',
									'operator' => '==',
									'value'    => 'yes',
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_time_style_heading',
			array(
				'label'     => __( 'Time', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'rael_show_time' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_time',
				'label'     => __( 'Time Typography', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector'  => '{{WRAPPER}} .rael-timeline__date .time',
				'condition' => array(
					'rael_show_time' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_time_color',
			array(
				'label'     => __( 'Time Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-timeline__date .time' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_show_time' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_time_margin',
			array(
				'label'      => __( 'Time Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-timeline__date .time' => 'color: {{VALUE}};',
				),
				'condition'  => array(
					'rael_show_time' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_time_date_divider',
			array(
				'type'      => Controls_Manager::DIVIDER,
				'condition' => array(
					'rael_show_time' => 'yes',
					'rael_show_date' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_date_style_heading',
			array(
				'label'     => __( 'Date', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'rael_show_date' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'     => __( 'Date Typography', 'responsive-addons-for-elementor' ),
				'name'      => 'rael_time_date',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector'  => '{{WRAPPER}} .rael-timeline__date .date',
				'condition' => array(
					'rael_show_date' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_date_color',
			array(
				'label'     => __( 'Date Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-timeline__date .date' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_show_date' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_date_margin',
			array(
				'label'      => __( 'Date Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-timeline__date .date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_show_date' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Button controls under Style Tab.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_style_button() {
		$this->start_controls_section(
			'rael_timeline_style_section_button_controls',
			array(
				'label' => __( 'Button', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'name'     => 'rael_button',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .rael-timeline__button',
			)
		);

		$this->start_controls_tabs( 'rael_timeline_button_tabs' );

		$this->start_controls_tab(
			'rael_button_normal_state',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->register_button_state_controls_tab( 'normal' );

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_button_hover_state',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->register_button_state_controls_tab( 'hover' );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_button_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-timeline__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_button_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-timeline__button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Button state tab controls under Style Tab.
	 *
	 * Dynamically generates the control ids for button controls
	 * depending on the given parameter as the button state.
	 *
	 * @param string $state Button's state.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_button_state_controls_tab( $state ) {
		$control_id_prefix = 'rael_button_state_' . $state . '_';
		$selector_suffix   = 'normal' === $state ? '' : ':hover';

		$this->add_control(
			$control_id_prefix . 'color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					"{{WRAPPER}} .rael-timeline__button{$selector_suffix}" => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'label'    => __( 'Background Type', 'responsive-addons-for-elementor' ),
				'name'     => $control_id_prefix . 'background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => "{{WRAPPER}} .rael-timeline__button{$selector_suffix}",
			)
		);
	}

	/**
	 * Render the widget on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'rael_timeline_wrapper', 'class', 'rael-timeline-wrapper' );

		if ( 'yes' === $settings['rael_show_scroll_tree'] ) {
			$this->add_render_attribute( 'rael_timeline_wrapper', 'data-scroll-tree', $settings['rael_show_scroll_tree'] );
		}
		?>

		<div <?php $this->print_render_attribute_string( 'rael_timeline_wrapper' ); ?>>
		<?php
		if ( $settings['rael_timeline'] ) :
			foreach ( $settings['rael_timeline'] as $key => $value ) :
				$this->set_render_attribute(
					'rael_timeline_item',
					'class',
					array(
						'rael-timeline__item',
						'elementor-repeater-item-' . $value['_id'],
					)
				);

				if ( 'calendar' === $value['rael_time_type'] ) {
						$date = gmdate( 'd M Y', strtotime( $value['rael_calendar_time'] ) );
				} else {
					$date = $value['rael_text_time'];
				}

				$time = 'calendar' === $value['rael_time_type'] ? gmdate( 'g:i a', strtotime( $value['rael_calendar_time'] ) ) : '';

				// Image.
				if ( 'image' === $value['rael_icon_type'] && $value['rael_image'] ) {
					$this->add_render_attribute( 'rael_image', 'src', $value['rael_image']['url'] );
					$this->add_render_attribute( 'rael_image', 'alt', Control_Media::get_image_alt( $value['rael_image'] ) );
					$this->add_render_attribute( 'rael_image', 'title', Control_Media::get_image_title( $value['rael_image'] ) );
				}

				// Title.
				$title_key = $this->get_repeater_setting_key( 'rael_title', 'rael_timeline', $key );
				$this->add_render_attribute( $title_key, 'class', 'rael-timeline__title' );
				$this->add_inline_editing_attributes( $title_key, 'none' );

				// Content Box.
				$this->add_render_attribute( 'rael_content_box', 'class', 'rael-timeline__content' );
				if ( 'yes' === $settings['rael_show_content_arrow'] ) {
					$this->add_render_attribute( 'rael_content_box', 'class', 'arrow' );
				}

				// Content Text.
				$content_key = $this->get_repeater_setting_key( 'rael_content', 'rael_timeline', $key );
				$this->add_render_attribute( $content_key, 'class', 'rael-timeline__content-body' );
				$this->add_inline_editing_attributes( $content_key, 'none' );

				// Button.
				if ( '' !== $value['rael_button_text'] ) {
					$button_key = $this->get_repeater_setting_key( 'rael_button_text', 'rael_timeline', $key );
					$this->add_render_attribute( $button_key, 'class', 'rael-timeline__button' );
					$this->add_inline_editing_attributes( $button_key, 'none' );
					$this->add_link_attributes( $button_key, $value['rael_button_link'] );
				}
				?>
				<div <?php $this->print_render_attribute_string( 'rael_timeline_item' ); ?>>
					<div class="rael-timeline__icon-box align-center">
						<div class="rael-timeline__icon">
				<?php
				if ( 'icon' === $value['rael_icon_type'] && $value['rael_icon'] ) {
					Icons_Manager::render_icon( $value['rael_icon'], array( 'aria-hidden' => true ) );
				} else {
					echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $value, 'rael_image', 'rael_image' ) );
				}
				?>

				<?php if ( ( $date || $time ) && ( 'yes' === $settings['rael_show_date'] || 'yes' === $settings['rael_show_time'] ) ) : ?>
								<span class="rael-timeline__date rael-timeline__date--desktop">
					<?php
					if ( $date && $settings['rael_show_date'] ) {
						printf( '<span class="date">%s</span>', esc_html( $date ) );
					}

					if ( $time && $settings['rael_show_time'] ) {
						printf( '<span class="time">%s</span>', esc_html( $time ) );
					}
					?>
								</span>
				<?php endif; ?>
						</div>
						<div class="rael-timeline__tree">
					<?php if ( 'yes' === $settings['rael_show_scroll_tree'] ) : ?>
								<div class="rael-timeline__tree-inner"></div>
					<?php endif; ?>
						</div>
					</div>
					<div <?php $this->print_render_attribute_string( 'rael_content_box' ); ?>>
				<?php if ( ( $date || $time ) && ( 'yes' === $settings['rael_show_date'] || 'yes' === $settings['rael_show_time'] ) ) : ?>
						<span class="rael-timeline__date rael-timeline__date--tablet">
						<?php
						if ( $date && $settings['rael_show_date'] ) {
							printf( '<span class="date">%s</span>', esc_html( $date ) );
						}

						if ( $time && $settings['rael_show_time'] ) {
							printf( '<span class="time">%s</span>', esc_html( $time ) );
						}
						?>
						</span>
				<?php endif; ?>
				<?php
				if ( ! empty( $value['rael_gallery'] ) && 'before' === $value['rael_image_position'] ) {
					echo '<figure class="rael-timeline__image-gallery before-title">';
					foreach ( $value['rael_gallery'] as $id => $image ) {
						$alt_text = get_post_meta( $image['id'], '_wp_attachment_image_alt', true ); // Fetch alt text
    					$alt_text = !empty( $alt_text ) ? esc_attr( $alt_text ) : ''; // escape alt text

						echo wp_get_attachment_image( $image['id'], $value['rael_image_size'], false, array( 'alt' => $alt_text ) );
					}
					echo '</figure>';
				}
				?>
					<?php
					if ( '' !== $value['rael_title'] ) {
						printf( '<%1$s %2$s>%3$s</%1$s>', tag_escape( $settings['rael_title_tag'] ), wp_kses_post( $this->get_render_attribute_string( $title_key ) ), esc_html( $value['rael_title'] ) );
					}

					if ( '' !== $value['rael_content'] ) {
						printf( '<div %1$s>%2$s</div>', wp_kses_post( $this->get_render_attribute_string( $content_key ) ), wp_kses_post( $this->parse_text_editor( $value['rael_content'] ) ) );
					}

					if ( ! empty( $value['rael_gallery'] ) && 'after' === $value['rael_image_position'] ) {
							echo '<figure class="rael-timeline__image-gallery after-content">';
						foreach ( $value['rael_gallery'] as $id => $image ) {
							echo wp_get_attachment_image( $image['id'], $value['rael_image_size'], false, array( 'alt' => wp_get_attachment_caption( $image['id'] ) ) );
						}
						echo '</figure>';
					}

					if ( '' !== $value['rael_button_text'] ) {
						printf( '<a %1$s>%2$s</a>', wp_kses_post( $this->get_render_attribute_string( $button_key ) ), esc_html( $value['rael_button_text'] ) );
					}
					?>
				</div>
				</div>
				<?php
			endforeach;
		endif;
		?>
		</div>
		<?php
	}
}
