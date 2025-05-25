<?php
/**
 * Skin Base
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Skins;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Skin_Base as Elementor_Skin_Base;
use Elementor\Widget_Base;
use Elementor\Plugin;
use Elementor\Group_Control_Box_Shadow;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Class RAEL_Skin_Base
 */
abstract class RAEL_Skin_Base extends Elementor_Skin_Base {

	/**
	 * The string variable
	 *
	 * @var string Save current permalink to avoid conflict with plugins the filters the permalink during the post render.
	 */
	protected $current_permalink;
	/**
	 * Register control actions
	 */
	protected function _register_controls_actions() {
		add_action( 'elementor/element/rael-posts/section_layout/before_section_end', array( $this, 'register_controls' ) );
		add_action( 'elementor/element/rael-posts/rael_section_query/after_section_end', array( $this, 'register_style_sections' ) );
	}
	/**
	 * Registers style sections for a given widget.
	 *
	 * @param Widget_Base $widget The widget for which style sections are being registered.
	 * @return void
	 */
	public function register_style_sections( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_design_controls();
	}
	/**
	 * Register design controls
	 */
	public function register_design_controls() {
		$this->register_design_layout_controls();
		$this->register_design_image_controls();
		$this->register_design_content_controls();
	}
	/**
	 * Registers controls for a given widget.
	 *
	 * @param Widget_Base $widget The widget for which controls are being registered.
	 * @return void
	 */
	public function register_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_columns_controls();
		$this->register_post_count_control();
		$this->register_thumbnail_controls();
		$this->register_title_controls();
		$this->register_excerpt_controls();
		$this->register_meta_data_controls();
		$this->register_read_more_controls();
		$this->register_link_controls();

		if ( 'rael-posts' === $this->parent->get_name() ) {
			$this->register_data_position_controls();
		}

	}
	/**
	 * Register control thumbnail
	 */
	protected function register_thumbnail_controls() {
		$this->add_control(
			'thumbnail',
			array(
				'label'        => __( 'Image Position', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'top',
				'options'      => array(
					'top'   => __( 'Top', 'responsive-addons-for-elementor' ),
					'left'  => __( 'Left', 'responsive-addons-for-elementor' ),
					'right' => __( 'Right', 'responsive-addons-for-elementor' ),
					'none'  => __( 'None', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'elementor-posts--thumbnail-',
			)
		);

		$this->add_control(
			'masonry',
			array(
				'label'              => __( 'Masonry', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_off'          => __( 'Off', 'responsive-addons-for-elementor' ),
				'label_on'           => __( 'On', 'responsive-addons-for-elementor' ),
				'condition'          => array(
					$this->get_control_id( 'columns!' )  => '1',
					$this->get_control_id( 'thumbnail' ) => 'top',
				),
				'render_type'        => 'ui',
				'frontend_available' => true,
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'         => 'thumbnail_size',
				'default'      => 'medium',
				'exclude'      => array( 'custom' ),
				'condition'    => array(
					$this->get_control_id( 'thumbnail!' ) => 'none',
				),
				'prefix_class' => 'elementor-posts--thumbnail-size-',
			)
		);

		$this->add_responsive_control(
			'item_ratio',
			array(
				'label'          => __( 'Image Ratio', 'responsive-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => 0.6,
				),
				'tablet_default' => array(
					'size' => '',
				),
				'mobile_default' => array(
					'size' => 0.5,
				),
				'range'          => array(
					'px' => array(
						'min'  => 0.1,
						'max'  => 2,
						'step' => 0.01,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .responsive-posts-container .elementor-post__thumbnail' => 'padding-bottom: calc( {{SIZE}} * 100% );',
					'{{WRAPPER}}:after' => 'content: "{{SIZE}}"; position: absolute; color: transparent;',
				),
				'condition'      => array(
					$this->get_control_id( 'thumbnail!' ) => 'none',
					$this->get_control_id( 'masonry' )    => '',
				),
			)
		);

		$this->add_responsive_control(
			'image_width',
			array(
				'label'          => __( 'Image Width', 'responsive-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'range'          => array(
					'%'  => array(
						'min' => 10,
						'max' => 100,
					),
					'px' => array(
						'min' => 10,
						'max' => 600,
					),
				),
				'default'        => array(
					'size' => 100,
					'unit' => '%',
				),
				'tablet_default' => array(
					'size' => '',
					'unit' => '%',
				),
				'mobile_default' => array(
					'size' => 100,
					'unit' => '%',
				),
				'size_units'     => array( '%', 'px' ),
				'selectors'      => array(
					'{{WRAPPER}} .responsive-post__thumbnail__link' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					$this->get_control_id( 'thumbnail!' ) => 'none',
				),
			)
		);
	}
	/**
	 * Register columns control
	 */
	protected function register_columns_controls() {
		$this->add_responsive_control(
			'columns',
			array(
				'label'              => __( 'Columns', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '3',
				'tablet_default'     => '2',
				'mobile_default'     => '1',
				'options'            => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'prefix_class'       => 'elementor-grid%s-',
				'frontend_available' => true,
			)
		);
	}
	/**
	 * Register post_count_control
	 */
	protected function register_post_count_control() {
		$this->add_control(
			'posts_per_page',
			array(
				'label'   => __( 'Posts Per Page', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
			)
		);
	}
	/**
	 * Register title_controls
	 */
	protected function register_title_controls() {
		$this->add_control(
			'show_title',
			array(
				'label'     => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off' => __( 'Hide', 'responsive-addons-for-elementor' ),
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'     => __( 'Title HTML Tag', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
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
				'default'   => 'h3',
				'condition' => array(
					$this->get_control_id( 'show_title' ) => 'yes',
				),
			)
		);

	}
	/**
	 * Register excerpt_controls
	 */
	protected function register_excerpt_controls() {
		$this->add_control(
			'show_excerpt',
			array(
				'label'     => __( 'Excerpt', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off' => __( 'Hide', 'responsive-addons-for-elementor' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'excerpt_length',
			array(
				'label'     => __( 'Excerpt Length', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				/** This filter is documented in wp-includes/formatting.php */
				'default'   => apply_filters( 'excerpt_length', 25 ),
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'apply_to_custom_excerpt',
			array(
				'label'     => esc_html__( 'Apply to custom Excerpt', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off' => esc_html__( 'No', 'responsive-addons-for-elementor' ),
				'default'   => 'no',
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);
	}
	/**
	 * Register read_more_controls
	 */
	protected function register_read_more_controls() {
		$this->add_control(
			'show_read_more',
			array(
				'label'     => __( 'Read More', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off' => __( 'Hide', 'responsive-addons-for-elementor' ),
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'read_more_text',
			array(
				'label'     => __( 'Read More Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Read More »', 'responsive-addons-for-elementor' ),
				'condition' => array(
					$this->get_control_id( 'show_read_more' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'read_more_type',
			array(
				'label'     => __( 'Read More Type', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'text',
				'options'   => array(
					'text'   => __( 'Text', 'responsive-addons-for-elementor' ),
					'button' => __( 'Button', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					$this->get_control_id( 'show_read_more' ) => 'yes',
				),
			)
		);

	}
	/**
	 * Register link_controls
	 */
	protected function register_link_controls() {
		$this->add_control(
			'open_new_tab',
			array(
				'label'       => __( 'Open in new window', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'   => __( 'No', 'responsive-addons-for-elementor' ),
				'default'     => 'no',
				'render_type' => 'none',
			)
		);
	}
	/**
	 * Register data_position_controls
	 */
	protected function register_data_position_controls() {
		$position_order = array(
			'0' => __( 'Select', 'responsive-addons-for-elementor' ),
			'1' => __( 'One', 'responsive-addons-for-elementor' ),
			'2' => __( 'Two', 'responsive-addons-for-elementor' ),
			'3' => __( 'Three', 'responsive-addons-for-elementor' ),
		);

		if ( 'rael_cards' !== $this->get_id() ) {
			$position_order['4'] = __( 'Four', 'responsive-addons-for-elementor' );
		}

		$this->add_control(
			'title_position',
			array(
				'label'     => __( 'Title Position', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $position_order,
				'default'   => '1',
				'condition' => array(
					$this->get_control_id( 'show_title' ) => 'yes',
				),
			)
		);

		if ( 'rael_cards' !== $this->get_id() ) {
			$this->add_control(
				'meta_data_position',
				array(
					'label'     => __( 'Meta Position', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => $position_order,
					'default'   => '2',
					'condition' => array(
						$this->get_control_id( 'meta_data!' ) => array(),
					),
				)
			);
		}

		$this->add_control(
			'excerpt_position',
			array(
				'label'     => __( 'Excerpt Position', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $position_order,
				'default'   => '3',
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'read_more_position',
			array(
				'label'     => __( 'Read More Position', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $position_order,
				'default'   => '4',
				'condition' => array(
					$this->get_control_id( 'show_read_more' ) => 'yes',
				),
			)
		);
	}
	/**
	 * Function get_optional_link_attributes_html
	 */
	protected function get_optional_link_attributes_html() {
		$settings                 = $this->parent->get_settings();
		$new_tab_setting_key      = $this->get_control_id( 'open_new_tab' );
		$optional_attributes_html = 'yes' === $settings[ $new_tab_setting_key ] ? 'target="_blank"' : '';

		return $optional_attributes_html;
	}
	/**
	 * Function register_meta_data_controls
	 */
	protected function register_meta_data_controls() {
		$this->add_control(
			'meta_data',
			array(
				'label'       => __( 'Meta Data', 'responsive-addons-for-elementor' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'default'     => array( 'date', 'comments' ),
				'multiple'    => true,
				'options'     => array(
					'author'   => __( 'Author', 'responsive-addons-for-elementor' ),
					'date'     => __( 'Date', 'responsive-addons-for-elementor' ),
					'time'     => __( 'Time', 'responsive-addons-for-elementor' ),
					'comments' => __( 'Comments', 'responsive-addons-for-elementor' ),
				),
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'meta_separator',
			array(
				'label'     => __( 'Separator Between', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '///',
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__meta-data span + span:before' => 'content: "{{VALUE}}"',
				),
				'condition' => array(
					$this->get_control_id( 'meta_data!' ) => array(),
				),
			)
		);
	}

	/**
	 * Style Tab
	 */
	protected function register_design_layout_controls() {
		$this->start_controls_section(
			'section_design_layout',
			array(
				'label' => __( 'Layout', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'column_gap',
			array(
				'label'     => __( 'Columns Gap', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 30,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-posts-container' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
					'.elementor-msie {{WRAPPER}} .elementor-post' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'.elementor-msie {{WRAPPER}} .responsive-posts-container' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				),
			)
		);

		$this->add_control(
			'row_gap',
			array(
				'label'              => __( 'Rows Gap', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'size' => 35,
				),
				'range'              => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'frontend_available' => true,
				'selectors'          => array(
					'{{WRAPPER}} .responsive-posts-container' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
					'.elementor-msie {{WRAPPER}} .elementor-post' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'alignment',
			array(
				'label'        => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
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
				'prefix_class' => 'elementor-posts--align-',
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Function register_design_image_controls
	 */
	protected function register_design_image_controls() {
		$this->start_controls_section(
			'section_design_image',
			array(
				'label'     => __( 'Image', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'thumbnail!' ) => 'none',
				),
			)
		);

		$this->add_control(
			'img_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-post__thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'thumbnail!' ) => 'none',
				),
			)
		);

		$this->add_control(
			'image_spacing',
			array(
				'label'     => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}.elementor-posts--thumbnail-left .responsive-post__thumbnail__link' => 'margin-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-posts--thumbnail-right .responsive-post__thumbnail__link' => 'margin-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-posts--thumbnail-top .responsive-post__thumbnail__link' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
				'default'   => array(
					'size' => 20,
				),
				'condition' => array(
					$this->get_control_id( 'thumbnail!' ) => 'none',
				),
			)
		);

		$this->start_controls_tabs( 'thumbnail_effects_tabs' );

		$this->start_controls_tab(
			'normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'thumbnail_filters',
				'selector' => '{{WRAPPER}} .elementor-post__thumbnail img',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'thumbnail_hover_filters',
				'selector' => '{{WRAPPER}} .elementor-post:hover .elementor-post__thumbnail img',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}
	/**
	 * Function register_design_content_controls
	 */
	protected function register_design_content_controls() {
		$this->start_controls_section(
			'section_design_content',
			array(
				'label' => __( 'Content', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_title_style',
			array(
				'label'     => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					$this->get_control_id( 'show_title' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__title, {{WRAPPER}} .elementor-post__title a' => 'color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'show_title' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'title_typography',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector'  => '{{WRAPPER}} .elementor-post__title, {{WRAPPER}} .elementor-post__title a',
				'condition' => array(
					$this->get_control_id( 'show_title' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'title_spacing',
			array(
				'label'     => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					$this->get_control_id( 'show_title' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'heading_meta_style',
			array(
				'label'     => __( 'Meta', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					$this->get_control_id( 'meta_data!' ) => array(),
				),
			)
		);

		$this->add_control(
			'meta_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__meta-data' => 'color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'meta_data!' ) => array(),
				),
			)
		);

		$this->add_control(
			'meta_separator_color',
			array(
				'label'     => __( 'Separator Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__meta-data span:before' => 'color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'meta_data!' ) => array(),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'meta_typography',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector'  => '{{WRAPPER}} .elementor-post__meta-data',
				'condition' => array(
					$this->get_control_id( 'meta_data!' ) => array(),
				),
			)
		);

		$this->add_control(
			'meta_spacing',
			array(
				'label'     => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__meta-data' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					$this->get_control_id( 'meta_data!' ) => array(),
				),
			)
		);

		$this->add_control(
			'heading_excerpt_style',
			array(
				'label'     => __( 'Excerpt', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__excerpt p' => 'color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'excerpt_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector'  => '{{WRAPPER}} .elementor-post__excerpt p',
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'excerpt_spacing',
			array(
				'label'     => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'heading_readmore_style',
			array(
				'label'     => __( 'Read More', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					$this->get_control_id( 'show_read_more' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'read_more_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'   => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__read-more' => 'color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'show_read_more' ) => 'yes',
					$this->get_control_id( 'read_more_type' ) => 'text',
				),
			)
		);
		$this->add_control(
			'read_more_hover_color',
			array(
				'label'     => __( 'Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__read-more:hover' => 'color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'show_read_more' ) => 'yes',
					$this->get_control_id( 'read_more_type' ) => 'text',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'read_more_typography',
				'selector'  => '{{WRAPPER}} .elementor-post__read-more',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'condition' => array(
					$this->get_control_id( 'show_read_more' ) => 'yes',
					$this->get_control_id( 'read_more_type' ) => 'text',
				),
			)
		);

		$this->add_control(
			'read_more_spacing',
			array(
				'label'     => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__read-more__container' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					$this->get_control_id( 'show_read_more' ) => 'yes',
					$this->get_control_id( 'read_more_type' ) => 'text',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'read_more_button_typography',
				'selector'  => '{{WRAPPER}} .elementor-button-text',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'condition' => array(
					$this->get_control_id( 'show_read_more' ) => 'yes',
					$this->get_control_id( 'read_more_type' ) => 'button',
				),
			)
		);

		$this->add_control(
			'read_more_button_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'md',
				'options'   => array(
					'xs' => __( 'Extra Small', 'responsive-addons-for-elementor' ),
					'sm' => __( 'Small', 'responsive-addons-for-elementor' ),
					'md' => __( 'Medium', 'responsive-addons-for-elementor' ),
					'lg' => __( 'Large', 'responsive-addons-for-elementor' ),
					'xl' => __( 'Extra Large', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					$this->get_control_id( 'show_read_more' ) => 'yes',
					$this->get_control_id( 'read_more_type' ) => 'button',
				),
			)
		);

		$this->add_responsive_control(
			'read_more_button_custom_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'show_read_more' ) => 'yes',
					$this->get_control_id( 'read_more_type' ) => 'button',
				),
			)
		);
		// Tabs Start.
		$this->start_controls_tabs( 'read_more_button_style_tabs' );

			// Normal Tab Start.
			$this->start_controls_tab(
				'read_more_button_normal_state',
				array(
					'label'     => __( 'Normal', 'responsive-addons-for-elementor' ),
					'condition' => array(
						$this->get_control_id( 'show_read_more' ) => 'yes',
						$this->get_control_id( 'read_more_type' ) => 'button',
					),
				)
			);

				$this->add_control(
					'read_more_button_text_color',
					array(
						'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => array(
							'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
							'{{WRAPPER}} .elementor-button' => 'border-color: {{VALUE}};',
							'{{WRAPPER}} .rael-price-box__cta .rael-button-container svg' => 'fill: {{VALUE}};',
						),
						'condition' => array(
							$this->get_control_id( 'show_read_more' ) => 'yes',
							$this->get_control_id( 'read_more_type' ) => 'button',
						),
					)
				);

				$this->add_control(
					'read_more_button_bg_color',
					array(
						'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'global'    => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
						'selectors' => array(
							'{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
						),
						'condition' => array(
							$this->get_control_id( 'show_read_more' ) => 'yes',
							$this->get_control_id( 'read_more_type' ) => 'button',
						),
					)
				);

				$this->add_control(
					'read_more_button_border',
					array(
						'label'       => __( 'Border Style', 'responsive-addons-for-elementor' ),
						'type'        => Controls_Manager::SELECT,
						'default'     => 'none',
						'label_block' => false,
						'options'     => array(
							'none'   => __( 'None', 'responsive-addons-for-elementor' ),
							'solid'  => __( 'Solid', 'responsive-addons-for-elementor' ),
							'double' => __( 'Double', 'responsive-addons-for-elementor' ),
							'dotted' => __( 'Dotted', 'responsive-addons-for-elementor' ),
							'dashed' => __( 'Dashed', 'responsive-addons-for-elementor' ),
						),
						'condition'   => array(
							$this->get_control_id( 'show_read_more' ) => 'yes',
							$this->get_control_id( 'read_more_type' ) => 'button',
						),
						'selectors'   => array(
							'{{WRAPPER}} .elementor-button' => 'border-style: {{VALUE}};',
						),
					)
				);

				$this->add_control(
					'read_more_button_border_size',
					array(
						'label'      => __( 'Border Width', 'responsive-addons-for-elementor' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px' ),
						'default'    => array(
							'top'    => '1',
							'bottom' => '1',
							'left'   => '1',
							'right'  => '1',
							'unit'   => 'px',
						),
						'condition'  => array(
							$this->get_control_id( 'show_read_more' ) => 'yes',
							$this->get_control_id( 'read_more_type' ) => 'button',
							$this->get_control_id( 'read_more_button_border' ) => array( 'solid', 'double', 'dotted', 'dashed' ),
						),
						'selectors'  => array(
							'{{WRAPPER}} .elementor-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_control(
					'read_more_button_border_color',
					array(
						'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'condition' => array(
							$this->get_control_id( 'show_read_more' ) => 'yes',
							$this->get_control_id( 'read_more_type' ) => 'button',
							$this->get_control_id( 'read_more_button_border' ) => array( 'solid', 'double', 'dotted', 'dashed' ),
						),
						'default'   => '',
						'selectors' => array(
							'{{WRAPPER}} .elementor-button' => 'border-color: {{VALUE}};',
						),
					)
				);

				$this->add_responsive_control(
					'read_more_button_border_radius',
					array(
						'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%' ),
						'selectors'  => array(
							'{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition'  => array(
							$this->get_control_id( 'show_read_more' ) => 'yes',
							$this->get_control_id( 'read_more_type' ) => 'button',
						),
					)
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					array(
						'name'      => 'read_more_button_box_shadow',
						'label'     => __( 'Button Shadow', 'responsive-addons-for-elementor' ),
						'condition' => array(
							$this->get_control_id( 'show_read_more' ) => 'yes',
							$this->get_control_id( 'read_more_type' ) => 'button',
						),
						'selector'  => '{{WRAPPER}} .elementor-button',
					)
				);

			$this->end_controls_tab();
			// Normal Tab Ends.

			// Hover Tab Start.
			$this->start_controls_tab(
				'read_more_button_hover_state',
				array(
					'label'     => __( 'Hover', 'responsive-addons-for-elementor' ),
					'condition' => array(
						$this->get_control_id( 'show_read_more' ) => 'yes',
						$this->get_control_id( 'read_more_type' ) => 'button',
					),
				)
			);

				$this->add_control(
					'read_more_button_hover_color',
					array(
						'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
						),
						'condition' => array(
							$this->get_control_id( 'show_read_more' ) => 'yes',
							$this->get_control_id( 'read_more_type' ) => 'button',
						),
					)
				);

				$this->add_control(
					'read_more_button_bg_hover_color',
					array(
						'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} .elementor-button:hover' => 'background-color: {{VALUE}};',
						),
						'condition' => array(
							$this->get_control_id( 'show_read_more' ) => 'yes',
							$this->get_control_id( 'read_more_type' ) => 'button',
						),
					)
				);

				$this->add_control(
					'read_more_button_hover_border_color',
					array(
						'label'     => __( 'Border Hover Color', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
						),
						'condition' => array(
							$this->get_control_id( 'show_read_more' ) => 'yes',
							$this->get_control_id( 'read_more_type' ) => 'button',
						),
					)
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					array(
						'name'      => 'read_more_button_hover_box_shadow',
						'label'     => __( 'Hover Shadow', 'responsive-addons-for-elementor' ),
						'selector'  => '{{WRAPPER}} .elementor-button:hover',
						'separator' => 'before',
						'condition' => array(
							$this->get_control_id( 'read_more_type' ) => 'button',
						),
					)
				);

				$this->add_control(
					'read_more_button_hover_animation',
					array(
						'label'     => __( 'Animation', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::HOVER_ANIMATION,
						'condition' => array(
							$this->get_control_id( 'read_more_type' ) => 'button',
						),
					)
				);

			$this->end_controls_tab();
			// Hover Tab Ends.

		$this->end_controls_tabs();
		// Tabs Ends.

		$this->end_controls_section();
	}
	/**
	 * Function render
	 */
	public function render() {
		$this->parent->query_posts();

		/**
		 * This is for WP query.
		 *
		 * @var \WP_Query
		 */
		$query = $this->parent->get_query();

		if ( ! $query->found_posts ) {
			return;
		}

		$this->render_loop_header();

		// It's the global `wp_query` it self. and the loop was started from the theme.
		if ( $query->in_the_loop ) {
			$this->current_permalink = get_permalink();
			$this->render_post();
		} else {
			while ( $query->have_posts() ) {
				$query->the_post();

				$this->current_permalink = get_permalink();
				$this->render_post();
			}
		}

		wp_reset_postdata();

		$this->render_loop_footer();

	}
	/**
	 * Function filter_excerpt_length
	 */
	public function filter_excerpt_length() {
		return $this->get_instance_value( 'excerpt_length' );
	}
	/**
	 * Function filter_excerpt_more
	 *
	 * @param array $more filter for excerpt.
	 */
	public function filter_excerpt_more( $more ) {
		return '';
	}
	/**
	 * Function get_container_class
	 */
	public function get_container_class() {
		return 'elementor-posts--skin-' . $this->get_id();
	}
	/**
	 * Function render_thumbnail
	 */
	protected function render_thumbnail() {
		$thumbnail = $this->get_instance_value( 'thumbnail' );

		if ( 'none' == $thumbnail && ! Plugin::instance()->editor->is_edit_mode() ) {
			return;
		}

		$settings                 = $this->parent->get_settings();
		$setting_key              = $this->get_control_id( 'thumbnail_size' );
		$settings[ $setting_key ] = array(
			'id' => get_post_thumbnail_id(),
		);
		$thumbnail_html           = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );

		if ( empty( $thumbnail_html ) ) {
			return;
		}

		$optional_attributes_html = $this->get_optional_link_attributes_html();

		?>
		<a class="responsive-post__thumbnail__link" href="<?php echo esc_url( $this->current_permalink ); ?>" <?php echo esc_attr( $optional_attributes_html ); ?>>
			<div class="elementor-post__thumbnail"><?php echo wp_kses_post( $thumbnail_html ); ?></div>
		</a>
		<?php
	}
	/**
	 * Function render_title
	 */
	protected function render_title() {
		if ( ! $this->get_instance_value( 'show_title' ) ) {
			return;
		}

		$optional_attributes_html = $this->get_optional_link_attributes_html();

		$tag = $this->get_instance_value( 'title_tag' );
		?>
		<<?php echo wp_kses_post( Helper::validate_html_tags( $tag ) ); ?> class="elementor-post__title">
		<a href="<?php echo esc_url( $this->current_permalink ); ?>" <?php echo esc_attr( $optional_attributes_html ); ?>>
			<?php the_title(); ?>
		</a>
		</<?php echo wp_kses_post( Helper::validate_html_tags( $tag ) ); ?>>
		<?php
	}
	/**
	 * Function render_excerpt
	 */
	protected function render_excerpt() {
		add_filter( 'excerpt_more', array( $this, 'filter_excerpt_more' ), 20 );
		add_filter( 'excerpt_length', array( $this, 'filter_excerpt_length' ), 20 );

		if ( ! $this->get_instance_value( 'show_excerpt' ) ) {
			return;
		}

		add_filter( 'excerpt_more', array( $this, 'filter_excerpt_more' ), 20 );
		add_filter( 'excerpt_length', array( $this, 'filter_excerpt_length' ), 20 );

		?>
		<div class="elementor-post__excerpt">
		<?php
			global $post;
			$apply_to_custom_excerpt = $this->get_instance_value( 'apply_to_custom_excerpt' );

			// Force the manually-generated Excerpt length as well if the user chose to enable 'apply_to_custom_excerpt'.
		if ( 'yes' === $apply_to_custom_excerpt && ! empty( $post->post_excerpt ) ) {
				$max_length = (int) $this->get_instance_value( 'excerpt_length' );
				$excerpt    = apply_filters( 'the_excerpt', get_the_excerpt() );
				$excerpt    = $this->trim_words( $excerpt, $max_length );
				echo wp_kses_post( $excerpt );
		} else {
			the_excerpt();
		}
		?>
		</div>
		<?php

		remove_filter( 'excerpt_length', array( $this, 'filter_excerpt_length' ), 20 );
		remove_filter( 'excerpt_more', array( $this, 'filter_excerpt_more' ), 20 );
	}
	/**
	 * Function render_read_more
	 */
	protected function render_read_more() {
		if ( ! $this->get_instance_value( 'show_read_more' ) ) {
			return;
		}

		$optional_attributes_html = $this->get_optional_link_attributes_html();
		$read_more_button_size    = $this->get_instance_value( 'read_more_button_size' );

		$read_more_button_hover_animation = $this->get_instance_value( 'read_more_button_hover_animation' ) !== '' ? 'elementor-animation-' . $this->get_instance_value( 'read_more_button_hover_animation' ) : '';

		?>
		<div class="elementor-post__read-more__container">
		<?php
		if ( 'text' === $this->get_instance_value( 'read_more_type' ) ) {
			?>
				<a class="elementor-post__read-more" href="<?php echo esc_url( $this->current_permalink ); ?>" <?php echo esc_attr( $optional_attributes_html ); ?>>
					<?php echo esc_html( $this->get_instance_value( 'read_more_text' ) ); ?>
				</a>
				<?php
		} else {
			?>
				<a href="<?php echo esc_url( $this->current_permalink ); ?>" class="elementor-button-link  elementor-button elementor-size-<?php echo esc_attr( $read_more_button_size ); ?> <?php echo esc_attr( $read_more_button_hover_animation ); ?>" <?php echo esc_html( $optional_attributes_html ); ?>>
					<span class="elementor-button-text"><?php echo esc_html( $this->get_instance_value( 'read_more_text' ) ); ?></span>
				</a>
				<?php
		}
		?>
		</div>
		<?php
	}
	/**
	 * Function render_post_header
	 */
	protected function render_post_header() {
		?>
		<article <?php post_class( array( 'elementor-post elementor-grid-item' ) ); ?>>
		<?php
	}
	/**
	 * Function render_post_footer
	 */
	protected function render_post_footer() {
		?>
		</article>
		<?php
	}
	/**
	 * Function render_text_header
	 */
	protected function render_text_header() {
		?>
		<div class="elementor-post__text">
		<?php
	}
	/**
	 * Function render_text_footer
	 */
	protected function render_text_footer() {
		?>
		</div>
		<?php
	}
	/**
	 * Function render_loop_header
	 */
	protected function render_loop_header() {
		$settings = $this->parent->get_settings();
		$classes  = array(
			'responsive-posts-container',
			'responsive-posts',
			$this->get_container_class(),
		);

		/**
		 * This is for WP query.
		 *
		 * @var \WP_Query
		 */
		$wp_query = $this->parent->get_query();

		// Use grid only if found posts.
		if ( $wp_query->found_posts ) {
			$classes[] = 'elementor-grid';
		}

		global $post;
		$page_id = $post->ID;

		$this->parent->add_render_attribute(
			'container',
			array(
				'class'           => $classes,
				'data-pid'        => $page_id,
				'data-skin'       => $this->get_id(),
				'data-pagination' => $settings['pagination_type'],
				'data-page-limit' => $settings['pagination_page_limit'],
			)
		);

		if ( strpos( $this->get_id(), 'archive' ) === false ) {
			$this->parent->add_render_attribute(
				'container',
				array(
					'data-post-per-page' => $settings[ $this->get_id() . '_posts_per_page' ],
					'data-paged'         => $wp_query->query['paged'],
				)
			);

			$terms_list                      = Helper::get_terms_list( $settings['rael_ft_tax_filter'], 'slug' );
			$disable_class_ft_tabs_on_mobile = '';
			if ( 'yes' === $settings['rael_ft_show_filters'] && 'yes' === $settings['rael_ft_tabs_dropdown'] ) {
				$disable_class_ft_tabs_on_mobile = 'rael_post_filterable_tabs_display';
				?>
				<div class="rael_post_filterable_tabs_wrapper_dropdown">
					<select class="rael_post_filterable_tabs_dropdown" data-post-per-page="<?php echo esc_attr( $settings[ $this->get_id() . '_posts_per_page' ] ); ?>" data-paged="<?php echo esc_attr( $wp_query->query['paged'] ); ?>" data-pid="<?php echo esc_attr( $page_id ); ?>" data-skin="<?php echo esc_attr( $this->get_id() ); ?>">
						<option class="rael_post_filterable_tab_dropdown" data-term="*all">All</option>
					<?php
					foreach ( $terms_list as $slug => $term ) {
						if ( 'yes' === $settings['rael_ft_default_filter_switch'] && strtolower( $settings['rael_ft_default_filter'] ) === strtolower( $term ) ) {
							?>
						<option class="rael_post_filterable_tab_dropdown" selected data-term="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $term ); ?></option>
							<?php
							continue;
						}
						?>
						<option class="rael_post_filterable_tab_dropdown" data-term="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $term ); ?></option>
						<?php
					}
					?>
					</select>
				</div>
				<?php
			}

			$active_filterable_tab_class = '';
			if ( 'yes' !== $settings['rael_ft_default_filter_switch'] || '' === $settings['rael_ft_default_filter'] ) {
				$active_filterable_tab_class = 'rael_post_active_filterable_tab';
			}
			if ( 'yes' === $settings['rael_ft_show_filters'] ) {
				?>
				<div class="rael_post_filterable_tabs_wrapper <?php echo esc_attr( $disable_class_ft_tabs_on_mobile ); ?>">
					<ul class="rael_post_filterable_tabs" data-post-per-page="<?php echo esc_attr( $settings[ $this->get_id() . '_posts_per_page' ] ); ?>" data-paged="<?php echo esc_attr( $wp_query->query['paged'] ); ?>" data-pid="<?php echo esc_attr( $page_id ); ?>" data-skin="<?php echo esc_attr( $this->get_id() ); ?>">
						<li class="rael_post_filterable_tab <?php echo esc_attr( $active_filterable_tab_class ); ?>" data-term="*all">
							<?php echo esc_html( $settings['rael_ft_filters_all_text'] ) !== '' ? esc_html( $settings['rael_ft_filters_all_text'] ) : 'All'; ?>
						</li>
					<?php
					if ( 'yes' === $settings['rael_ft_default_filter_switch'] || '' !== $settings['rael_ft_default_filter'] ) {
						$active_filterable_tab_class = 'rael_post_active_filterable_tab';
					}
					foreach ( $terms_list as $slug => $term ) {
						if ( 'yes' === $settings['rael_ft_default_filter_switch'] && strtolower( $settings['rael_ft_default_filter'] ) === strtolower( $term ) ) {
							?>
						<li class="rael_post_filterable_tab <?php echo esc_attr( $active_filterable_tab_class ); ?>" data-term="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $term ); ?></li>
							<?php
							continue;
						}
						?>
						<li class="rael_post_filterable_tab" data-term="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $term ); ?></li>
						<?php
					}
					?>
					</ul>
				</div>
				<?php
			}
		}
		?>
		<div 
		<?php echo wp_kses_post( $this->parent->get_render_attribute_string( 'container' ) ); ?>>
		<?php
	}
	/**
	 * Function render_loop_footer
	 */
	protected function render_loop_footer() {
		?>
		</div>
		<?php

		$parent_settings = $this->parent->get_settings();
		if ( '' == $parent_settings['pagination_type'] ) {
			return;
		}

		$page_limit = $this->parent->get_query()->max_num_pages;
		if ( '' !== $parent_settings['pagination_page_limit'] ) {
			$page_limit = min( $parent_settings['pagination_page_limit'], $page_limit );
		}

		if ( 2 > $page_limit ) {
			return;
		}

		$this->parent->add_render_attribute( 'pagination', 'class', 'elementor-pagination' );

		$has_numbers   = in_array( $parent_settings['pagination_type'], array( 'numbers', 'numbers_and_prev_next' ), true );
		$has_prev_next = in_array( $parent_settings['pagination_type'], array( 'prev_next', 'numbers_and_prev_next' ), true );

		$links = array();

		if ( $has_numbers ) {
			$paginate_args = array(
				'type'               => 'array',
				'current'            => $this->parent->get_current_page(),
				'total'              => $page_limit,
				'prev_next'          => false,
				'show_all'           => 'yes' != $parent_settings['pagination_numbers_shorten'],
				'before_page_number' => '<span class="elementor-screen-only">' . __( 'Page', 'responsive-addons-for-elementor' ) . '</span>',
			);

			if ( is_singular() && ! is_front_page() ) {
				global $wp_rewrite;
				if ( $wp_rewrite->using_permalinks() ) {
					$paginate_args['base']   = trailingslashit( get_permalink() ) . '%_%';
					$paginate_args['format'] = user_trailingslashit( '%#%', 'single_paged' );
				} else {
					$paginate_args['format'] = '?page=%#%';
				}
			}

			$links = paginate_links( $paginate_args );
		}

		if ( $has_prev_next ) {
			$prev_next = $this->parent->get_posts_nav_link( $page_limit );
			array_unshift( $links, $prev_next['prev'] );
			$links[] = $prev_next['next'];
		}

		?>
		<nav class="elementor-pagination rael-post-pagination" role="navigation" aria-label="<?php esc_attr_e( 'Pagination', 'responsive-addons-for-elementor' ); ?>">
		<?php
		if ( 'infinite' == $parent_settings['pagination_type'] ) {
			?>
				<button class="rael_pagination_load_more">
				<?php echo '' === $parent_settings['pagination_infinite_button_label'] ? esc_html__( 'Load More', 'responsive-addons-for-elementor' ) : esc_html( $parent_settings['pagination_infinite_button_label'] ); ?>
				</button>
				<?php
		} else {
			echo implode( PHP_EOL, ( $links ) ); //phpcs:ignore
		}
		?>
		</nav>
		<?php
	}
	/**
	 * Function render_meta_data
	 */
	protected function render_meta_data() {
		/**
		 * The setting array
		 *
		 * @var array $settings e.g. [ 'author', 'date', ... ]
		 */
		$settings = $this->get_instance_value( 'meta_data' );
		if ( empty( $settings ) ) {
			return;
		}
		?>
		<div class="elementor-post__meta-data">
			<?php
			if ( in_array( 'author', $settings, true ) ) {
				$this->render_author();
			}

			if ( in_array( 'date', $settings, true ) ) {
				$this->render_date();
			}

			if ( in_array( 'time', $settings, true ) ) {
				$this->render_time();
			}

			if ( in_array( 'comments', $settings, true ) ) {
				$this->render_comments();
			}
			?>
		</div>
		<?php
	}
	/**
	 * Function render_author
	 */
	protected function render_author() {
		?>
		<span class="elementor-post-author">
			<?php the_author(); ?>
		</span>
		<?php
	}
	/**
	 * Function render_date
	 */
	protected function render_date() {
		?>
		<span class="elementor-post-date">
			<?php
			/** This filter is documented in wp-includes/general-template.php */
			echo esc_html( apply_filters( 'the_date', get_the_date(), get_option( 'date_format' ), '', '' ) );
			?>
		</span>
		<?php
	}
	/**
	 * Function render_time
	 */
	protected function render_time() {
		?>
		<span class="elementor-post-time">
			<?php the_time(); ?>
		</span>
		<?php
	}
	/**
	 * Function render_comments
	 */
	protected function render_comments() {
		?>
		<span class="elementor-post-avatar">
			<?php comments_number(); ?>
		</span>
		<?php
	}
	/**
	 * Function render_post
	 */
	protected function render_post() {
		$content_positions_key = array(
			empty( $this->get_instance_value( 'title_position' ) ) ? 0 : $this->get_instance_value( 'title_position' ),
			empty( $this->get_instance_value( 'meta_data_position' ) ) ? 0 : $this->get_instance_value( 'meta_data_position' ),
			empty( $this->get_instance_value( 'excerpt_position' ) ) ? 0 : $this->get_instance_value( 'excerpt_position' ),
			empty( $this->get_instance_value( 'read_more_position' ) ) ? 0 : $this->get_instance_value( 'read_more_position' ),
		);

		$content_positions_value = array(
			'render_title',
			'render_meta_data',
			'render_excerpt',
			'render_read_more',
		);

		$positions = array_combine( $content_positions_key, $content_positions_value );
		ksort( $positions );

		$this->render_post_header();
		$this->render_thumbnail();
		$this->render_text_header();

		foreach ( $positions as $key => $value ) {
			if ( 0 !== $key ) {
				$this->$value();
			}
		}

		$this->render_text_footer();
		$this->render_post_footer();
	}
	/**
	 * Function render_amp
	 */
	public function render_amp() {

	}
	/**
	 * Trims a string to a specified number of words.
	 *
	 * @param string $text   The text to trim.
	 * @param int    $length The maximum number of words to keep.
	 * @return string The trimmed text.
	 */
	public static function trim_words( $text, $length ) {
		if ( $length && str_word_count( $text ) > $length ) {
			$text = explode( ' ', $text, $length + 1 );
			unset( $text[ $length ] );
			$text = implode( ' ', $text );
		}

		return $text;
	}
}
