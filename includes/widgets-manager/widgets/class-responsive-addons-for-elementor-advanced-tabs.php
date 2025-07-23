<?php
/**
 * RAEL Advanced Tabs
 *
 * @since 1.3.2
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RAEL Advanced Tabs class.
 *
 * @since 1.3.2
 */
class Responsive_Addons_For_Elementor_Advanced_Tabs extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.3.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
 
	public $widget_image;
	public function get_name() {
		return 'rael-advanced-tabs';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.3.2
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Advanced Tabs', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.3.2
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-tabs rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the Advanced Tabs widget belongs to.
	 *
	 * @since 1.3.2
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
	 * @since 1.3.2
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'tab', 'navigation', 'tabs content', 'product tabs', 'advanced' );
	}
	/**
	 * Get custom help url.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/rea-advanced-tabs/';
	}
	/**
	 * Get query post list.
	 *
	 * @param string $post_type is post type.
	 *
	 * @param int    $limit is limit.
	 *
	 * @param string $search is search text.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public static function get_query_post_list( $post_type = 'any', $limit = -1, $search = '' ) {
		global $wpdb;
		$where = '';
		$data  = array();

		if ( -1 === $limit ) {
			$limit = '';
		} elseif ( 0 === $limit ) {
			$limit = 'limit 0,1';
		} else {
			$limit = $wpdb->prepare( ' limit 0,%d', esc_sql( $limit ) );
		}

		if ( 'any' === $post_type ) {
			$in_search_post_types = get_post_types( array( 'exclude_from_search' => false ) );
			if ( empty( $in_search_post_types ) ) {
				$where .= ' AND 1=0 ';
			} else {
				$where .= " AND {$wpdb->posts}.post_type IN ('" . join(
					"', '",
					array_map( 'esc_sql', $in_search_post_types )
				) . "')";
			}
		} elseif ( ! empty( $post_type ) ) {
			$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_type = %s", esc_sql( $post_type ) );
		}

		if ( ! empty( $search ) ) {
			$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_title LIKE %s", '%' . esc_sql( $search ) . '%' );
		}

		$query   = "select post_title,ID  from $wpdb->posts where post_status = 'publish' $where $limit";
		$results = $wpdb->get_results( $query );  //phpcs:ignore

		if ( ! empty( $results ) ) {
			foreach ( $results as $row ) {
				$data[ $row->ID ] = $row->post_title;
			}
		}
		return $data;
	}
	/**
	 * Get elementor templates
	 *
	 * @param string $type is type of template.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array Widget keywords.
	 */
	protected function get_elementor_templates( $type = null ) {
		$options = array();

		if ( $type ) {
			$args = array(
				'post_type'      => 'elementor_library',
				'posts_per_page' => -1,
				'taxonomy'       => 'elementor_library_type',
				'terms'          => $type,
			);

			$page_templates = get_posts( $args );

			if ( ! empty( $page_templates ) && ! is_wp_error( $page_templates ) ) {
				foreach ( $page_templates as $post ) {
					$options[ $post->ID ] = $post->post_title;
				}
			}
		} else {
			$options = $this->get_query_post_list( 'elementor_library' );
		}

		return $options;
	}
	/**
	 * Register Controls
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'rael_section_advanced_tabs_settings',
			array(
				'label' => esc_html__( 'General Settings', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_advanced_tab_layout',
			array(
				'label'       => esc_html__( 'Layout', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'rael-tabs-horizontal',
				'label_block' => false,
				'options'     => array(
					'rael-tabs-horizontal' => esc_html__( 'Horizontal', 'responsive-addons-for-elementor' ),
					'rael-tabs-vertical'   => esc_html__( 'Vertical', 'responsive-addons-for-elementor' ),
				),
			)
		);
		$this->add_control(
			'rael_advanced_tabs_icon_show',
			array(
				'label'        => esc_html__( 'Enable Icon', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);
		$this->add_control(
			'rael_advanced_tab_icon_position',
			array(
				'label'       => esc_html__( 'Icon Position', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'rael-tab-inline-icon',
				'label_block' => false,
				'options'     => array(
					'rael-tab-top-icon'    => esc_html__( 'Stacked', 'responsive-addons-for-elementor' ),
					'rael-tab-inline-icon' => esc_html__( 'Inline', 'responsive-addons-for-elementor' ),
				),
				'condition'   => array(
					'rael_advanced_tabs_icon_show' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_content_advanced_tabs_settings',
			array(
				'label' => esc_html__( 'Content', 'responsive-addons-for-elementor' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'rael_advanced_tabs_tab_show_as_default',
			array(
				'label'        => __( 'Set as Default', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'inactive',
				'return_value' => 'active-default',
			)
		);

		$repeater->add_control(
			'rael_advanced_tabs_icon_type',
			array(
				'label'       => esc_html__( 'Icon Type', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'none'  => array(
						'title' => esc_html__( 'None', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-ban',
					),
					'icon'  => array(
						'title' => esc_html__( 'Icon', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-gear',
					),
					'image' => array(
						'title' => esc_html__( 'Image', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-picture-o',
					),
				),
				'default'     => 'icon',
			)
		);

		$repeater->add_control(
			'rael_advanced_tabs_tab_title_icon_new',
			array(
				'label'            => esc_html__( 'Icon', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'rael_advanced_tabs_tab_title_icon',
				'default'          => array(
					'value'   => 'fas fa-home',
					'library' => 'fa-solid',
				),
				'condition'        => array(
					'rael_advanced_tabs_icon_type' => 'icon',
				),
			)
		);

		$repeater->add_control(
			'rael_advanced_tabs_tab_title_image',
			array(
				'label'     => esc_html__( 'Image', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'rael_advanced_tabs_icon_type' => 'image',
				),
			)
		);

		$repeater->add_control(
			'rael_advanced_tabs_tab_title',
			array(
				'name'    => 'rael_advanced_tabs_tab_title',
				'label'   => esc_html__( 'Tab Title', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Tab Title', 'responsive-addons-for-elementor' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'rael_advanced_tabs_text_type',
			array(
				'label'   => __( 'Content Type', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'content'  => __( 'Content', 'responsive-addons-for-elementor' ),
					'image'  => __( 'Image', 'responsive-addons-for-elementor' ),
					'template' => __( 'Saved Templates', 'responsive-addons-for-elementor' ),
				),
				'default' => 'content',
			)
		);

		$repeater->add_control(
			'rael_primary_templates',
			array(
				'label'     => __( 'Choose Template', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_elementor_templates(),
				'condition' => array(
					'rael_advanced_tabs_text_type' => 'template',
				),
			)
		);

		$repeater->add_control(
			'rael_advanced_tabs_tab_content',
			array(
				'label'     => esc_html__( 'Tab Content', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::WYSIWYG,
				'default'   => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat', 'responsive-addons-for-elementor' ),
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'rael_advanced_tabs_text_type' => 'content',
				),
			)
		);

		$repeater->add_control(
			'rael_advanced_tabs_tab_image',
			[
				'label' => esc_html__( 'Choose Image', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),

				],
				'condition'     => array(
					'rael_advanced_tabs_text_type' => 'image',
				),
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'rael_advanced_tabs_tab_image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default' => 'large',
				'condition' => [
					'rael_advanced_tabs_text_type' => 'image',
					'rael_advanced_tabs_tab_image[url]!' => '',
				],
			]
		);

		$repeater->add_control(
			'rael_advanced_tabs_tab_image_caption_source',
			[
				'label' => esc_html__( 'Caption', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'responsive-addons-for-elementor' ),
					'attachment' => esc_html__( 'Attachment Caption', 'responsive-addons-for-elementor' ),
					'custom' => esc_html__( 'Custom Caption', 'responsive-addons-for-elementor' ),
				],
				'default' => 'none',
				'condition' => [
					'rael_advanced_tabs_text_type' => 'image',
					'rael_advanced_tabs_tab_image[url]!' => '',
				],
			]
		);

		$repeater->add_control(
			'rael_advanced_tabs_tab_image_caption',
			[
				'label' => esc_html__( 'Custom Caption', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'Enter your image caption', 'elementor' ),
				'condition' => [
					'rael_advanced_tabs_tab_image_caption_source' => 'custom',
					'rael_advanced_tabs_tab_image[url]!' => '',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'rael_advanced_tabs_tab_image_link_to',
			[
				'label' => esc_html__( 'Link', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'responsive-addons-for-elementor' ),
					'file' => esc_html__( 'Media File', 'responsive-addons-for-elementor' ),
					'custom' => esc_html__( 'Custom URL', 'responsive-addons-for-elementor' ),
				],
				'condition' => [
					'rael_advanced_tabs_text_type' => 'image',
					'rael_advanced_tabs_tab_image[url]!' => '',
				],
			]
		);

		$repeater->add_control(
			'rael_advanced_tabs_tab_image_link',
			[
				'label' => esc_html__( 'Link', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'rael_advanced_tabs_text_type' => 'image',
					'rael_advanced_tabs_tab_image_link_to' => 'custom',
					'rael_advanced_tabs_tab_image[url]!' => '',
				],
				'show_label' => false,
			]
		);

		$repeater->add_control(
			'rael_advanced_tabs_tab_image_open_lightbox',
			[
				'label' => esc_html__( 'Lightbox', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'description' => sprintf(
					/* translators: 1: Link open tag, 2: Link close tag. */
					esc_html__( 'Manage your site’s lightbox settings in the %1$sLightbox panel%2$s.', 'elementor' ),
					'<a href="javascript: $e.run( \'panel/global/open\' ).then( () => $e.route( \'panel/global/settings-lightbox\' ) )">',
					'</a>'
				),
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'responsive-addons-for-elementor' ),
					'yes' => esc_html__( 'Yes', 'responsive-addons-for-elementor' ),
					'no' => esc_html__( 'No', 'responsive-addons-for-elementor' ),
				],
				'condition' => [
					'rael_advanced_tabs_tab_image_link_to' => 'file',
					'rael_advanced_tabs_tab_image[url]!' => '',
				],
			]
		);

		$this->add_control(
			'rael_advanced_tabs_tab',
			array(
				'type'        => Controls_Manager::REPEATER,
				'seperator'   => 'before',
				'default'     => array(
					array( 'rael_advanced_tabs_tab_title' => esc_html__( 'Tab Title 1', 'responsive-addons-for-elementor' ) ),
					array( 'rael_advanced_tabs_tab_title' => esc_html__( 'Tab Title 2', 'responsive-addons-for-elementor' ) ),
					array( 'rael_advanced_tabs_tab_title' => esc_html__( 'Tab Title 3', 'responsive-addons-for-elementor' ) ),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{rael_advanced_tabs_tab_title}}',
			)
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'rael_advanced_tabs_tab_image_style',
			[
				'label' => esc_html__( 'Image', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_responsive_control(
			'rael_advanced_tabs_tab_image_align',
			[
				'label' => esc_html__( 'Alignment', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'responsive-addons-for-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'responsive-addons-for-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'responsive-addons-for-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .rael-tabs-content .active, {{WRAPPER}} .rael-tabs-content .active a' => 'display: flex; flex-direction: column;',
					'{{WRAPPER}} img , {{WRAPPER}} figure' => 'align-self: {{VALUE}}; margin: 0px 0px;',
				],
			]
		);

		$this->add_responsive_control(
			'rael_advanced_tabs_tab_image_width',
			[
				'label' => esc_html__( 'Width', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} figure, .rael-tabs-content>.clearfix>a>img , .rael-tabs-content>.clearfix>img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} img',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'rael_advanced_tabs_tab_image_image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} img',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_style_advanced_tabs_settings',
			array(
				'label' => esc_html__( 'General', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_advanced_tabs_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-advanced-tabs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'rael_advanced_tabs_margin',
			array(
				'label'      => esc_html__( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-advanced-tabs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_advanced_tabs_border',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-advanced-tabs',
			)
		);

		$this->add_responsive_control(
			'rael_advanced_tabs_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-advanced-tabs' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_advanced_tabs_box_shadow',
				'selector' => '{{WRAPPER}} .rael-advanced-tabs',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_title_style_advanced_tabs_settings',
			array(
				'label' => esc_html__( 'Tab Title', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_advanced_tabs_heading_tag',
			array(
				'label'   => __( 'HTML Tag', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1'   => __( 'H1', 'responsive-addons-for-elementor' ),
					'h2'   => __( 'H2', 'responsive-addons-for-elementor' ),
					'h3'   => __( 'H3', 'responsive-addons-for-elementor' ),
					'h4'   => __( 'H4', 'responsive-addons-for-elementor' ),
					'h5'   => __( 'H5', 'responsive-addons-for-elementor' ),
					'h6'   => __( 'H6', 'responsive-addons-for-elementor' ),
					'div'  => __( 'div', 'responsive-addons-for-elementor' ),
					'p'    => __( 'p', 'responsive-addons-for-elementor' ),
					'span' => __( 'span', 'responsive-addons-for-elementor' ),
				),
				'default' => 'span',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_advanced_tabs_tab_title_typography',
				'selector' => '{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li, {{WRAPPER}} .rael-tab-title',
			)
		);
		$this->add_responsive_control(
			'rael_advanced_tabs_title_width',
			array(
				'label'      => __( 'Title Min Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					),
					'em' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-advanced-tabs.rael-tabs-vertical > .rael-tabs-nav > ul' => 'min-width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_advanced_tab_layout' => 'rael-tabs-vertical',
				),
			)
		);
		$this->add_responsive_control(
			'rael_advanced_tabs_tab_icon_size',
			array(
				'label'      => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 16,
					'unit' => 'px',
				),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li svg' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_advanced_tabs_tab_icon_gap',
			array(
				'label'      => __( 'Icon Gap', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 10,
					'unit' => 'px',
				),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-tab-inline-icon li i, {{WRAPPER}} .rael-tab-inline-icon li img, {{WRAPPER}} .rael-tab-inline-icon li svg' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-tab-top-icon li i, {{WRAPPER}} .rael-tab-top-icon li img, {{WRAPPER}} .rael-tab-top-icon li svg' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'rael_advanced_tabs_tab_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_advanced_tabs_tab_margin',
			array(
				'label'      => esc_html__( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'rael_advanced_tabs_header_tabs' );

		$this->start_controls_tab( 'rael_advanced_tabs_header_normal', array( 'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'rael_advanced_tabs_tab_color',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f1f1f1',
				'selectors' => array(
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_advanced_tabs_tab_bgtype',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li',
			)
		);
		$this->add_control(
			'rael_advanced_tabs_tab_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => array(
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li, {{WRAPPER}} .rael-tab-title' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_advanced_tabs_tab_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => array(
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'rael_advanced_tabs_icon_show' => 'yes',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_advanced_tabs_tab_border',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li',
			)
		);
		$this->add_responsive_control(
			'rael_advanced_tabs_tab_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab( 'rael_advanced_tabs_header_hover', array( 'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ) ) );
		$this->add_control(
			'rael_advanced_tabs_tab_color_hover',
			array(
				'label'     => esc_html__( 'Tab Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => array(
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li:hover' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_advanced_tabs_tab_bgtype_hover',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li:hover',
			)
		);
		$this->add_control(
			'rael_advanced_tabs_tab_text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li:hover > .rael-tab-title ' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_advanced_tabs_tab_icon_color_hover',
			array(
				'label'     => esc_html__( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li:hover > i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li:hover > svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'rael_advanced_tabs_icon_show' => 'yes',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_advanced_tabs_tab_border_hover',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li:hover',
			)
		);
		$this->add_responsive_control(
			'rael_advanced_tabs_tab_border_radius_hover',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab( 'rael_advanced_tabs_header_active', array( 'label' => esc_html__( 'Active', 'responsive-addons-for-elementor' ) ) );
		$this->add_control(
			'rael_advanced_tabs_tab_color_active',
			array(
				'label'     => esc_html__( 'Tab Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#444',
				'selectors' => array(
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li.active' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li.active-default' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_advanced_tabs_tab_bgtype_active',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li.active,{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li.active-default',
			)
		);
		$this->add_control(
			'rael_advanced_tabs_tab_text_color_active',
			array(
				'label'     => esc_html__( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li.active .rael-tab-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul .active-default .rael-tab-title' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_advanced_tabs_tab_icon_color_active',
			array(
				'label'     => esc_html__( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li.active > i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li.active-default > i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li.active > svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li.active-default > svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'rael_advanced_tabs_icon_show' => 'yes',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_advanced_tabs_tab_border_active',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li.active, {{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li.active-default',
			)
		);
		$this->add_responsive_control(
			'rael_advanced_tabs_tab_border_radius_active',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li.active-default' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_advanced_tabs_tab_content_style_settings',
			array(
				'label' => esc_html__( 'Content', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'rael_advanced_tabs_content_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-content > div' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_advanced_tabs_content_bgtype',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-advanced-tabs .rael-tabs-content > div',
			)
		);
		$this->add_control(
			'rael_advanced_tabs_content_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => array(
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-content > div' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_advanced_tabs_content_typography',
				'selector' => '{{WRAPPER}} .rael-advanced-tabs .rael-tabs-content > div',
			)
		);

		$this->add_responsive_control(
			'rael_advanced_tabs_content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-content > div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'rael_advanced_tabs_content_margin',
			array(
				'label'      => esc_html__( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-content > div' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_advanced_tabs_content_border',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-advanced-tabs .rael-tabs-content > div',
			)
		);
		$this->add_responsive_control(
			'rael_advanced_tabs_content_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-tabs-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'rael_advanced_tabs_content_shadow',
				'selector'  => '{{WRAPPER}} .rael-advanced-tabs .rael-tabs-content > div',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_advanced_tabs_tab_caret_style_settings',
			array(
				'label' => esc_html__( 'Caret', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'rael_advanced_tabs_tab_caret_show',
			array(
				'label'        => esc_html__( 'Show Caret on Active Tab', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);
		$this->add_control(
			'rael_advanced_tabs_tab_caret_size',
			array(
				'label'     => esc_html__( 'Caret Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li:after' => 'border-width: {{SIZE}}px; bottom: -{{SIZE}}px',
					'{{WRAPPER}} .rael-advanced-tabs.rael-tabs-vertical > .rael-tabs-nav > ul li:after' => 'right: -{{SIZE}}px; top: calc(50% - {{SIZE}}px) !important;',
					'.rtl {{WRAPPER}} .rael-advanced-tabs.rael-tabs-vertical > .rael-tabs-nav > ul li:after' => 'right: auto; left: -{{SIZE}}px !important; top: calc(50% - {{SIZE}}px) !important;',
				),
				'condition' => array(
					'rael_advanced_tabs_tab_caret_show' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_advanced_tabs_tab_caret_color',
			array(
				'label'     => esc_html__( 'Caret Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#444',
				'selectors' => array(
					'{{WRAPPER}} .rael-advanced-tabs .rael-tabs-nav > ul li:after' => 'border-top-color: {{VALUE}};',
					'{{WRAPPER}} .rael-advanced-tabs.rael-tabs-vertical > .rael-tabs-nav > ul li:after' => 'border-top-color: transparent; border-left-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_advanced_tabs_tab_caret_show' => 'yes',
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'rael_advanced_responsive_controls',
			array(
				'label' => esc_html__( 'Responsive Controls', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_advanced_responsive_vertical_layout',
			array(
				'label'        => __( 'Vertical Layout', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();
	}

	protected function render_image($tab) {
	
	
		if ( empty( $tab['rael_advanced_tabs_tab_image']['url'] ) ) {
			return;
		}
	
		$link = false;
		
		if ( 'none' === $tab['rael_advanced_tabs_tab_image_link_to'] ) {
			$link = false;
		}
		elseif ( 'custom' === $tab['rael_advanced_tabs_tab_image_link_to'] ) {
			if ( empty( $tab['rael_advanced_tabs_tab_image_link']['url'] ) ) {
				$link = false;
			}
			$link = $tab['rael_advanced_tabs_tab_image_link'];
		}
		else {
			$link = [
				'url' => $tab['rael_advanced_tabs_tab_image']['url'],
			];
		}	

		$has_caption = ! empty( $tab['rael_advanced_tabs_tab_image_caption_source'] ) && 'none' !== $tab['rael_advanced_tabs_tab_image_caption_source'] ;
		 ?>

			<?php if ( $has_caption ) : ?>
				<figure class = "rae-figure">
			<?php endif; ?>
			<?php if ( $link ) : ?>
					<a href = "<?php echo $link['url']; ?>" data-elementor-open-lightbox= "<?php echo $tab['rael_advanced_tabs_tab_image_open_lightbox']?>" >
			<?php endif; ?>
				<?php Group_Control_Image_Size::print_attachment_image_html( $tab,"rael_advanced_tabs_tab_image" ); ?>
			<?php if ( $link ) : ?>
					</a>
			<?php endif; ?>
			<?php if ( $has_caption ) : ?>
					<figcaption class = "rae-caption" ><?php
						$caption = '';
						if ( ! empty( $tab['rael_advanced_tabs_tab_image_caption_source'] ) ) {
							switch ( $tab['rael_advanced_tabs_tab_image_caption_source'] ) {
								case 'attachment':
									$caption = wp_get_attachment_caption( $tab['rael_advanced_tabs_tab_image']['id'] );
									break;
								case 'custom':
									$caption = ! Utils::is_empty( $tab['rael_advanced_tabs_tab_image_caption'] ) ? $tab['rael_advanced_tabs_tab_image_caption'] : '';
							}
						}
						echo wp_kses_post( $caption ) ;
					?></figcaption>
			<?php endif; ?>
			<?php if ( $has_caption ) : ?>
				</figure>
			<?php endif; ?>
		<?php
			
	}
	
	/**
	 * Render function
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return void
	 */
	protected function render() {
		$settings                     = $this->get_settings_for_display();
		$rael_find_default_tab        = array();
		$rael_advanced_tab_id         = 1;
		$rael_advanced_tab_content_id = 1;
		$tab_icon_migrated            = isset( $settings['__fa4_migrated']['rael_advanced_tabs_tab_title_icon_new'] );
		$tab_icon_is_new              = empty( $settings['rael_advanced_tabs_tab_title_icon'] );

		$this->add_render_attribute(
			'rael_tab_wrapper',
			array(
				'id'         => "rael-advanced-tabs-{$this->get_id()}",
				'class'      => array( 'rael-advanced-tabs', $settings['rael_advanced_tab_layout'] ),
				'data-tabid' => $this->get_id(),
			)
		);
		if ( 'yes' !== $settings['rael_advanced_tabs_tab_caret_show'] ) {
			$this->add_render_attribute( 'rael_tab_wrapper', 'class', 'active-caret-on' );
		}

		if ( 'yes' !== $settings['rael_advanced_responsive_vertical_layout'] ) {
			$this->add_render_attribute( 'rael_tab_wrapper', 'class', 'responsive-vertical-layout' );
		}
		$this->add_render_attribute( 'rael_tab_icon_position', 'class', esc_attr( $settings['rael_advanced_tab_icon_position'] ) ); ?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_tab_wrapper' ) ); ?>>
			<div class="rael-tabs-nav">
				<ul <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_tab_icon_position' ) ); ?>>
					<?php foreach ( $settings['rael_advanced_tabs_tab'] as $tab ) : ?>
						<li class="<?php echo esc_attr( $tab['rael_advanced_tabs_tab_show_as_default'] ); ?>">
							<?php
							if ( 'yes' === $settings['rael_advanced_tabs_icon_show'] ) :
								if ( 'icon' === $tab['rael_advanced_tabs_icon_type'] ) :
									?>
									<?php
									if ( $tab_icon_is_new || $tab_icon_migrated ) {
										Icons_Manager::render_icon( $tab['rael_advanced_tabs_tab_title_icon_new'] );
									} else {
										echo '<i class="' . esc_attr( $tab['rael_advanced_tabs_tab_title_icon'] ) . '"></i>';
									}
									?>
								<?php elseif ( 'image' === $tab['rael_advanced_tabs_icon_type'] ) : ?>
									<img src="<?php echo esc_attr( $tab['rael_advanced_tabs_tab_title_image']['url'] ); ?>" alt="<?php echo esc_attr( get_post_meta( $tab['rael_advanced_tabs_tab_title_image']['id'], '_wp_attachment_image_alt', true ) ); ?>">
								<?php endif; ?>
							<?php endif; ?> <<?php echo esc_html( Helper::validate_html_tags( $settings['rael_advanced_tabs_heading_tag'] ) ); ?> class="rael-tab-title"><?php echo wp_kses_post( $tab['rael_advanced_tabs_tab_title'] ); ?></<?php echo esc_html( Helper::validate_html_tags( $settings['rael_advanced_tabs_heading_tag'] ) ); ?>>

						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="rael-tabs-content">
				<?php
				foreach ( $settings['rael_advanced_tabs_tab'] as $tab ) :
					$rael_find_default_tab[] = $tab['rael_advanced_tabs_tab_show_as_default'];
					?>

					<div class="clearfix <?php echo esc_attr( $tab['rael_advanced_tabs_tab_show_as_default'] ); ?>">
						<?php if ( 'content' === $tab['rael_advanced_tabs_text_type'] ) : ?>
							<?php echo do_shortcode( $tab['rael_advanced_tabs_tab_content'] ); ?>
						<?php elseif ( 'image' === $tab['rael_advanced_tabs_text_type'] ) : { ?>
							<?php $this->render_image($tab);?>
						<?php } elseif ( 'template' === $tab['rael_advanced_tabs_text_type'] ) : ?>
							<?php	
							if ( ! empty( $tab['rael_primary_templates'] ) ) {
								$allowed_html = wp_kses_allowed_html('post');
								$allowed_html['style'] = true; 
								echo wp_kses(Plugin::$instance->frontend->get_builder_content( $tab['rael_primary_templates'], true ), $allowed_html);
							}
							?>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

}
