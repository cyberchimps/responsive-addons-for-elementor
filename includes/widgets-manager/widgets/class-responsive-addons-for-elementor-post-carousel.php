<?php
/**
 * RAE Post Carousel
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 *
 * RAE Post carousel widget.
 */
class Responsive_Addons_For_Elementor_Post_Carousel extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 1.7.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-post-carousel';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.7.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Post Carousel', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.7.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-carousel rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the Post Carousel widget belongs to.
	 *
	 * @since 1.7.0
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
			'swiper',
			'e-swiper',	
		);
	}
	/**
	 * Get Custom help URL
	 *
	 * @since 1.7.0
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/post-carousel';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 1.7.0
	 *
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'post carousel', 'rael post carousel', 'post slider', 'post navigation', 'post', 'carousel', 'rael' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.7.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->register_content_tab_query_section();
		$this->register_content_tab_layout_section();
		$this->register_content_tab_carousel_section();
		$this->register_content_tab_links_section();

		$this->register_style_tab_post_section();
		$this->register_style_tab_thumbnail_section();
		$this->register_style_tab_read_more_button_section();
		$this->register_style_tab_color_typography_section();
		$this->register_style_tab_terms_section();
		$this->register_style_tab_meta_date_section();
		$this->register_style_tab_meta_date_position_section();
		$this->register_style_tab_meta_section();
		$this->register_style_tab_meta_header_section();
		$this->register_style_tab_meta_footer_section();
		$this->register_style_tab_arrows_section();
		$this->register_style_tab_dots_section();
	}
	/**
	 * Register general controls for styling elements based on provided parameters.
	 *
	 * This function generates controls for positioning and offsetting elements within the post carousel.
	 *
	 * @param string $prefix      Control prefix for unique identifier.
	 * @param string $section_label Label for the controls section.
	 * @param string $selectors    CSS selectors for the targeted elements.
	 * @param array  $conditions   Additional conditions for showing the controls.
	 */
	public function generate_general_controls( $prefix, $section_label, $selectors, $conditions ) {
		$this->start_controls_section(
			$prefix,
			array(
				'label'     => $section_label,
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => $conditions,
			)
		);

		$this->add_control(
			$prefix . '_position',
			array(
				'label'     => __( 'Position', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''         => __( 'Default', 'responsive-addons-for-elementor' ),
					'absolute' => __( 'Absolute', 'responsive-addons-for-elementor' ),
				),
				'selectors' => array(
					$selectors => 'position: {{VALUE}}',
				),
			)
		);

		$start = is_rtl() ? __( 'Right', 'responsive-addons-for-elementor' ) : __( 'Left', 'responsive-addons-for-elementor' );
		$end   = ! is_rtl() ? __( 'Right', 'responsive-addons-for-elementor' ) : __( 'Left', 'responsive-addons-for-elementor' );

		$this->add_control(
			$prefix . '_offset_orientation_h',
			array(
				'label'       => __( 'Horizontal Orientation', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'start',
				'options'     => array(
					'start' => array(
						'title' => $start,
						'icon'  => 'eicon-h-align-left',
					),
					'end'   => array(
						'title' => $end,
						'icon'  => 'eicon-h-align-right',
					),
				),
				'classes'     => 'elementor-control-start-end',
				'render_type' => 'ui',
				'condition'   => array(
					$prefix . '_position!' => '',
				),
			)
		);

		$this->add_responsive_control(
			$prefix . '_offset_x',
			array(
				'label'      => __( 'Offset', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min' => -200,
						'max' => 200,
					),
					'vw' => array(
						'min' => -200,
						'max' => 200,
					),
					'vh' => array(
						'min' => -200,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => '0',
				),
				'size_units' => array( 'px', '%', 'vw', 'vh' ),
				'selectors'  => array(
					'body:not(.rtl) ' . $selectors => 'left: {{SIZE}}{{UNIT}}',
					'body.rtl ' . $selectors       => 'right: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					$prefix . '_offset_orientation_h!' => 'end',
					$prefix . '_position!'             => '',
				),
			)
		);

		$this->add_responsive_control(
			$prefix . '_offset_x_end',
			array(
				'label'      => __( 'Offset', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -1000,
						'max'  => 1000,
						'step' => 0.1,
					),
					'%'  => array(
						'min' => -200,
						'max' => 200,
					),
					'vw' => array(
						'min' => -200,
						'max' => 200,
					),
					'vh' => array(
						'min' => -200,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => '0',
				),
				'size_units' => array( 'px', '%', 'vw', 'vh' ),
				'selectors'  => array(
					'body:not(.rtl) ' . $selectors => 'right: {{SIZE}}{{UNIT}}',
					'body.rtl ' . $selectors       => 'left: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					$prefix . '_offset_orientation_h' => 'end',
					$prefix . '_position!'            => '',
				),
			)
		);

		$this->add_control(
			$prefix . '_offset_orientation_v',
			array(
				'label'       => __( 'Vertical Orientation', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'start',
				'options'     => array(
					'start' => array(
						'title' => __( 'Top', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'end'   => array(
						'title' => __( 'Bottom', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'render_type' => 'ui',
				'condition'   => array(
					$prefix . '_position!' => '',
				),
			)
		);

		$this->add_responsive_control(
			$prefix . '_offset_y',
			array(
				'label'      => __( 'Offset', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min' => -200,
						'max' => 200,
					),
					'vh' => array(
						'min' => -200,
						'max' => 200,
					),
					'vw' => array(
						'min' => -200,
						'max' => 200,
					),
				),
				'size_units' => array( 'px', '%', 'vh', 'vw' ),
				'default'    => array(
					'size' => '0',
				),
				'selectors'  => array(
					$selectors => 'top: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					$prefix . '_offset_orientation_v!' => 'end',
					$prefix . '_position!'             => '',
				),
			)
		);

		$this->add_responsive_control(
			$prefix . '_offset_y_end',
			array(
				'label'      => __( 'Offset', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					),
					'%'  => array(
						'min' => -200,
						'max' => 200,
					),
					'vh' => array(
						'min' => -200,
						'max' => 200,
					),
					'vw' => array(
						'min' => -200,
						'max' => 200,
					),
				),
				'size_units' => array( 'px', '%', 'vh', 'vw' ),
				'default'    => array(
					'size' => '0',
				),
				'selectors'  => array(
					$selectors => 'bottom: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					$prefix . '_offset_orientation_v' => 'end',
					$prefix . '_position!'            => '',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register content query controls for the post carousel.
	 *
	 * This function defines controls related to post queries, including post types, authors, taxonomies,
	 * exclusion, posts per page, offset, orderby, and order.
	 */
	public function register_content_tab_query_section() {
		$post_types          = Helper::get_post_types();
		$post_types['by_id'] = __( 'Manual Selection', 'responsive-addons-for-elementor' );

		$taxonomies = get_taxonomies( array(), 'objects' );

		$this->start_controls_section(
			'rael_post_carousel_content_tab_query_section',
			array(
				'label' => __( 'Query', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_post_type',
			array(
				'label'   => __( 'Source', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $post_types,
				'default' => key( $post_types ),
			)
		);

		$this->add_control(
			'rael_dynamic_source_warning_text',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'This option will only affect in <strong>Archive page of RAEL Theme Builder</strong> dynamically.', 'responsive-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition'       => array(
					'rael_post_type' => 'source_dynamic',
				),
			)
		);

		$this->add_control(
			'rael_posts_ids',
			array(
				'label'       => __( 'Search & Select', 'responsive-addons-for-elementor' ),
				'type'        => 'rael-ajax-select2',
				'options'     => Helper::get_post_list(),
				'label_block' => true,
				'multiple'    => true,
				'source_name' => 'post_type',
				'source_type' => 'any',
				'condition'   => array(
					'rael_post_type' => 'by_id',
				),
			)
		);

		$this->add_control(
			'rael_authors',
			array(
				'label'       => __( 'Author', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'default'     => array(),
				'options'     => Helper::get_authors_list(),
				'condition'   => array(
					'rael_post_type!' => array( 'by_id', 'source_dynamic' ),
				),
			)
		);

		foreach ( $taxonomies as $taxonomy => $object ) {
			if ( ! isset( $object->object_type[0] ) || ! in_array( $object->object_type[0], array_keys( $post_types ) ) ) {
				continue;
			}

			$this->add_control(
				$taxonomy . '_ids',
				array(
					'label'       => $object->label,
					'type'        => 'rael-ajax-select2',
					'label_block' => true,
					'multiple'    => true,
					'source_name' => 'taxonomy',
					'source_type' => $taxonomy,
					'condition'   => array(
						'rael_post_type' => $object->object_type,
					),
				)
			);
		}

		$this->add_control(
			'rael_post_not_in',
			array(
				'label'       => __( 'Exclude', 'responsive-addons-for-elementor' ),
				'type'        => 'rael-ajax-select2',
				'label_block' => true,
				'multiple'    => true,
				'source_name' => 'post_type',
				'source_type' => 'any',
				'condition'   => array(
					'rael_post_type!' => array( 'by_id', 'source_dynamic' ),
				),
			)
		);

		$this->add_control(
			'rael_posts_per_page',
			array(
				'label'   => __( 'Posts Per Page', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '4',
				'min'     => '1',
			)
		);

		$this->add_control(
			'rael_offset',
			array(
				'label'     => __( 'Offset', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '0',
				'condition' => array(
					'rael_orderby!' => 'rand',
				),
			)
		);

		$this->add_control(
			'rael_orderby',
			array(
				'label'   => __( 'Order By', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'ID'            => __( 'Post ID', 'responsive-addons-for-elementor' ),
					'author'        => __( 'Author', 'responsive-addons-for-elementor' ),
					'title'         => __( 'Title', 'responsive-addons-for-elementor' ),
					'date'          => __( 'Date', 'responsive-addons-for-elementor' ),
					'modified'      => __( 'Last Modified Date', 'responsive-addons-for-elementor' ),
					'parent'        => __( 'Parent ID', 'responsive-addons-for-elementor' ),
					'rand'          => __( 'Random', 'responsive-addons-for-elementor' ),
					'comment_count' => __( 'Comment Count', 'responsive-addons-for-elementor' ),
					'menu_order'    => __( 'Menu Order', 'responsive-addons-for-elementor' ),
				),
				'default' => 'date',

			)
		);

		$this->add_control(
			'rael_order',
			array(
				'label'   => __( 'Order', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'asc'  => 'Ascending',
					'desc' => 'Descending',
				),
				'default' => 'desc',
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register the layout settings section for the post carousel content tab.
	 *
	 * This section includes controls for configuring the layout of the post carousel.
	 */
	public function register_content_tab_layout_section() {
		$this->start_controls_section(
			'rael_post_carousel_content_tab_layout_section',
			array(
				'label' => __( 'Layout Settings', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_template_layout',
			array(
				'label'   => esc_html__( 'Template Layout', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => __( 'Default', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_show_image',
			array(
				'label'        => __( 'Show Image', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'rael_image_size',
				'exclude'   => array( 'custom' ),
				'default'   => 'medium',
				'condition' => array(
					'rael_show_image' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_show_title',
			array(
				'label'        => __( 'Show Title', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'rael_title_tag',
			array(
				'label'     => __( 'Title Tag', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h2',
				'options'   => array(
					'h1'   => __( 'H1', 'responsive-addons-for-elementor' ),
					'h2'   => __( 'H2', 'responsive-addons-for-elementor' ),
					'h3'   => __( 'H3', 'responsive-addons-for-elementor' ),
					'h4'   => __( 'H4', 'responsive-addons-for-elementor' ),
					'h5'   => __( 'H5', 'responsive-addons-for-elementor' ),
					'h6'   => __( 'H6', 'responsive-addons-for-elementor' ),
					'span' => __( 'Span', 'responsive-addons-for-elementor' ),
					'p'    => __( 'P', 'responsive-addons-for-elementor' ),
					'div'  => __( 'Div', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_title_length',
			array(
				'label'     => __( 'Title Length', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'condition' => array(
					'rael_show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_show_excerpt',
			array(
				'label'        => __( 'Show excerpt', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'rael_excerpt_length',
			array(
				'label'     => __( 'Excerpt Words', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 10,
				'condition' => array(
					'rael_show_excerpt' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_excerpt_expansion_indicator',
			array(
				'label'       => esc_html__( 'Expansion Indicator', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'label_block' => false,
				'default'     => esc_html__( '...', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_show_excerpt' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_show_read_more_button',
			array(
				'label'        => __( 'Show Read More Button', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'rael_read_more_button_text',
			array(
				'label'     => __( 'Button Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array( 'active' => true ),
				'default'   => __( 'Read More', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_show_read_more_button' => 'yes',
				),
			)
		);

		$show_post_terms_condition = array( 'rael_show_image' => 'yes' );

		$this->add_control(
			'rael_show_post_terms',
			array(
				'label'        => __( 'Show Post Terms', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'condition'    => $show_post_terms_condition,
			)
		);

		$show_post_terms_child_condition = array(
			'rael_show_image'      => 'yes',
			'rael_show_post_terms' => 'yes',
		);

		$post_types = Helper::get_post_types();
		unset(
			$post_types['post'],
			$post_types['page'],
			$post_types['product']
		);
		$taxonomies     = get_taxonomies( array(), 'objects' );
		$post_types_tax = array();

		foreach ( $taxonomies as $taxonomy => $object ) {
			if ( ! isset( $object->object_type[0] ) || ! in_array( $object->object_type[0], array_keys( $post_types ) ) ) {
				continue;
			}

			$post_types_tax[ $object->object_type[0] ][ $taxonomy ] = $object->label;
		}

		foreach ( $post_types as $post_type => $post_taxonomies ) {
			$this->add_control(
				'rael_' . $post_type . '_terms',
				array(
					'label'     => __( 'Show Terms From', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => isset( $post_types_tax[ $post_type ] ) ? $post_types_tax[ $post_type ] : array(),
					'default'   => isset( $post_types_tax[ $post_type ] ) ? key( $post_types_tax[ $post_type ] ) : '',
					'condition' => array(
						'rael_show_image'      => 'yes',
						'rael_show_post_terms' => 'yes',
						'rael_post_type'       => $post_type,
					),
				)
			);
		}

		$this->add_control(
			'rael_post_terms',
			array(
				'label'     => __( 'Show Terms From', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'category' => __( 'Category', 'responsive-addons-for-elementor' ),
					'tags'     => __( 'Tags', 'responsive-addons-for-elementor' ),
				),
				'default'   => 'category',
				'condition' => array(
					'rael_show_image'      => 'yes',
					'rael_show_post_terms' => 'yes',
					'rael_post_type'       => array( 'post', 'page', 'product', 'by_id', 'source_dynamic' ),
				),
			)
		);

		$this->add_control(
			'rael_post_terms_max_length',
			array(
				'label'     => __( 'Max Terms to Show', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					1 => __( '1', 'responsive-addons-for-elementor' ),
					2 => __( '2', 'responsive-addons-for-elementor' ),
					3 => __( '3', 'responsive-addons-for-elementor' ),
				),
				'default'   => 1,
				'condition' => $show_post_terms_child_condition,
			)
		);

		$this->add_control(
			'rael_show_meta',
			array(
				'label'        => __( 'Show Meta', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'rael_meta_position',
			array(
				'label'     => esc_html__( 'Meta Position', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'meta-entry-footer',
				'options'   => array(
					'meta-entry-header' => esc_html__( 'Entry Header', 'responsive-addons-for-elementor' ),
					'meta-entry-footer' => esc_html__( 'Entry Footer', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_show_meta' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_show_avatar',
			array(
				'label'        => __( 'Show Avatar', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'rael_meta_position' => 'meta-entry-footer',
					'rael_show_meta'     => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_show_author',
			array(
				'label'        => __( 'Show Author Name', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'rael_show_meta' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_show_date',
			array(
				'label'        => __( 'Show Date', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'rael_show_meta' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register content tab for carousel section.
	 *
	 * This function adds controls for carousel settings in the Elementor widget.
	 */
	public function register_content_tab_carousel_section() {
		$this->start_controls_section(
			'rael_post_carousel_content_tab_carousel_section',
			array(
				'label' => __( 'Carousel Settings', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_carousel_effect',
			array(
				'label'       => __( 'Effect', 'responsive-addons-for-elementor' ),
				'description' => __( 'Sets transition effect', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'slide',
				'options'     => array(
					'slide'     => __( 'Slide', 'responsive-addons-for-elementor' ),
					'fade'      => __( 'Fade', 'responsive-addons-for-elementor' ),
					'cube'      => __( 'Cube', 'responsive-addons-for-elementor' ),
					'coverflow' => __( 'Coverflow', 'responsive-addons-for-elementor' ),
					'flip'      => __( 'Flip', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_items',
			array(
				'label'          => __( 'Visible Items', 'responsive-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array( 'size' => 3 ),
				'tablet_default' => array( 'size' => 2 ),
				'mobile_default' => array( 'size' => 1 ),
				'range'          => array(
					'px' => array(
						'min'  => 1,
						'max'  => 10,
						'step' => 1,
					),
				),
				'size_units'     => '',
				'condition'      => array(
					'rael_carousel_effect' => array( 'slide', 'coverflow' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_margin',
			array(
				'label'      => __( 'Items Gap', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 10 ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => '',
				'condition'  => array(
					'rael_carousel_effect' => array( 'slide', 'coverflow' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_post_image_height',
			array(
				'label'      => __( 'Image Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 350 ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 600,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-post-carousel__entry-thumbnail' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_slider_speed',
			array(
				'label'       => __( 'Slider Speed', 'responsive-addons-for-elementor' ),
				'description' => __( 'Duration of transition between slides (in ms)', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array( 'size' => 400 ),
				'range'       => array(
					'px' => array(
						'min'  => 100,
						'max'  => 3000,
						'step' => 1,
					),
				),
				'size_units'  => '',
			)
		);

		$this->add_control(
			'rael_autoplay',
			array(
				'label'        => __( 'Autoplay', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_autoplay_speed',
			array(
				'label'      => __( 'Autoplay Speed', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 2000 ),
				'range'      => array(
					'px' => array(
						'min'  => 500,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'size_units' => '',
				'condition'  => array(
					'rael_autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_pause_on_hover',
			array(
				'label'        => __( 'Pause On Hover', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'condition'    => array(
					'rael_autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_infinite_loop',
			array(
				'label'        => __( 'Infinite Loop', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_grab_cursor',
			array(
				'label'        => __( 'Grab Cursor', 'responsive-addons-for-elementor' ),
				'description'  => __( 'Shows grab cursor when you hover over the slider', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_navigation_heading',
			array(
				'label'     => __( 'Navigation', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_arrows',
			array(
				'label'        => __( 'Arrows', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_dots',
			array(
				'label'        => __( 'Dots', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register content tab for links section.
	 *
	 * This function adds controls for links settings in the Elementor widget.
	 */
	public function register_content_tab_links_section() {
		$this->start_controls_section(
			'rael_post_carousel_content_tab_links_section',
			array(
				'label'      => __( 'Links', 'responsive-addons-for-elementor' ),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'rael_show_image',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'rael_show_title',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'rael_show_read_more_button',
							'operator' => '==',
							'value'    => 'yes',
						),

					),
				),
			)
		);

		$this->add_control(
			'rael_image_link',
			array(
				'label'     => __( 'Image', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'rael_show_image' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_image_link_nofollow',
			array(
				'label'        => __( 'No Follow', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'true',
				'condition'    => array(
					'rael_show_image' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_image_link_target_blank',
			array(
				'label'        => __( 'Target Blank', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'true',
				'condition'    => array(
					'rael_show_image' => 'yes',
				),
				'separator'    => 'after',
			)
		);

		$this->add_control(
			'rael_title_link',
			array(
				'label'     => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'rael_show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_title_link_nofollow',
			array(
				'label'        => __( 'No Follow', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'true',
				'condition'    => array(
					'rael_show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_title_link_target_blank',
			array(
				'label'        => __( 'Target Blank', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'true',
				'condition'    => array(
					'rael_show_title' => 'yes',
				),
				'separator'    => 'after',
			)
		);

		$this->add_control(
			'rael_read_more_link',
			array(
				'label'     => __( 'Read More', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'rael_show_read_more_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_read_more_link_nofollow',
			array(
				'label'        => __( 'No Follow', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'true',
				'condition'    => array(
					'rael_show_read_more_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_read_more_link_target_blank',
			array(
				'label'        => __( 'Target Blank', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'true',
				'condition'    => array(
					'rael_show_read_more_button' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register style tab for post section.
	 *
	 * This function adds controls for post style settings in the Elementor widget.
	 */
	public function register_style_tab_post_section() {
		$this->start_controls_section(
			'rael_post_carousel_style_tab_post_section',
			array(
				'label' => __( 'Post Style', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_post_preset_style',
			array(
				'label'   => __( 'Select Style', 'responsive-addons-for-elementor' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					''      => __( 'Default', 'responsive-addons-for-elementor' ),
					'two'   => __( 'Style Two', 'responsive-addons-for-elementor' ),
					'three' => __( 'Style Three', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_post_preset_style_three_alert',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'Make sure to enable <strong>Show Date</strong> option from <strong>Layout Settings</strong>', 'responsive-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition'       => array(
					'rael_post_preset_style' => array( 'two', 'three' ),
					'rael_show_date'         => '',
				),
			)
		);

		$this->add_control(
			'rael_post_is_gradient_bg',
			array(
				'label'        => __( 'Use Gradient Background?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'rael_post_bg_color',
				'label'     => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .rael-grid-post-holder',
				'condition' => array(
					'rael_post_is_gradient_bg' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_post_bg_color',
			array(
				'label'     => __( 'Post Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-grid-post-holder' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'rael_post_is_gradient_bg' => '',
				),

			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_post_border',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-grid-post-holder',
			)
		);

		$this->add_control(
			'rael_post_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .rael-grid-post-holder' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_post_box_shadow',
				'selector' => '{{WRAPPER}} .rael-grid-post-holder',
			)
		);
		$this->add_control(
			'rael_post_box_hover',
			array(
				'label'     => __( 'Hover Style', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_post_box_hover_background',
				'label'    => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-grid-post-holder:hover',
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register styles for the Thumbnail section in the post carousel.
	 *
	 * This function defines and registers controls for configuring the style
	 * of post thumbnails, including hover effects, background, border, and more.
	 */
	public function register_style_tab_thumbnail_section() {
		$this->start_controls_section(
			'rael_post_carousel_style_tab_thumbnail_section',
			array(
				'label' => __( 'Thumbnail Style', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_post_bg_hover_icon_new',
			array(
				'label'            => __( 'Post Hover Icon', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'rael_post_bg_hover_icon',
				'default'          => array(
					'value'   => 'fas fa-long-arrow-alt-right',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'rael_post_hover_animation',
			array(
				'label'       => __( 'Hover Style', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'default'     => 'fade-in',
				'options'     => array(
					'none'     => esc_html__( 'None', 'responsive-addons-for-elementor' ),
					'fade-in'  => esc_html__( 'FadeIn', 'responsive-addons-for-elementor' ),
					'zoom-in'  => esc_html__( 'ZoomIn', 'responsive-addons-for-elementor' ),
					'slide-up' => esc_html__( 'SlideUp', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_thumbnail_is_gradient_background',
			array(
				'label'        => __( 'Use Gradient Background?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'rael_thumbnail_overlay_color',
				'label'     => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .rael-post-carousel__entry-overlay',
				'condition' => array(
					'rael_thumbnail_is_gradient_background' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_thumbnail_overlay_color',
			array(
				'label'     => __( 'Thumbnail Overlay Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0,0,0, .75)',
				'selectors' => array(
					'{{WRAPPER}} .rael-post-carousel__entry-overlay' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'rael_thumbnail_is_gradient_background' => '',
				),
			)
		);

		$this->add_control(
			'rael_thumbnail_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-post-carousel .rael-post-carousel__entry-thumbnail img, {{WRAPPER}} .rael-post-carousel .rael-post-carousel__entry-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'rael_post_thumbnail_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-post-carousel .rael-post-carousel__entry-media' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
	}
	/**
	 * Register styles for the Read More Button section in the post carousel.
	 *
	 * This function defines and registers controls for configuring the style
	 * of the "Read More" button, including typography, color, and hover effects.
	 */
	public function register_style_tab_read_more_button_section() {
		$this->start_controls_section(
			'rael_post_carousel_style_tab_read_more_section',
			array(
				'label'     => __( 'Read More Button Style', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_show_read_more_button' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_post_read_more_btn_typography',
				'selector' => '{{WRAPPER}} .rael-post-carousel__readmore-btn',
			)
		);

		$this->start_controls_tabs( 'rael_post_read_more_button_tabs' );

		$this->start_controls_tab(
			'rael_post_read_more_button_style_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_post_read_more_btn_color',
			array(
				'label'     => esc_html__( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#61ce70',
				'selectors' => array(
					'{{WRAPPER}} .rael-post-carousel__readmore-btn' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_post_read_more_btn_background',
				'label'    => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-post-carousel__readmore-btn',
				'exclude'  => array(
					'image',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_post_read_more_btn_border',
				'label'    => __( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-post-carousel__readmore-btn',
			)
		);

		$this->add_responsive_control(
			'rael_post_read_more_btn_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-post-carousel__readmore-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_post_read_more_button_style_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_post_read_more_btn_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-post-carousel__readmore-btn:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_post_read_more_btn_hover_background',
				'label'    => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-post-carousel__readmore-btn:hover',
				'exclude'  => array(
					'image',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_post_read_more_btn_hover_border',
				'label'    => __( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-post-carousel__readmore-btn:hover',
			)
		);

		$this->add_responsive_control(
			'rael_post_read_more_btn_border_hover_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-post-carousel__readmore-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_post_read_more_btn_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-post-carousel__readmore-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_post_read_more_btn_margin',
			array(
				'label'      => esc_html__( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-post-carousel__readmore-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register styles for the Color & Typography section in the post carousel.
	 *
	 * This function defines and registers controls for configuring the color
	 * and typography of post titles and excerpts in the carousel.
	 */
	public function register_style_tab_color_typography_section() {
		$this->start_controls_section(
			'rael_post_carousel_style_tab_color_typography_section',
			array(
				'label' => __( 'Color & Typography', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_post_title_style',
			array(
				'label'     => __( 'Title Style', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_post_title_color',
			array(
				'label'     => __( 'Title Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#303133',
				'selectors' => array(
					'{{WRAPPER}} .rael-post-carousel__entry-title, {{WRAPPER}} .rael-post-carousel__entry-title a' => 'color: {{VALUE}};',
				),

			)
		);

		$this->add_control(
			'rael_post_title_hover_color',
			array(
				'label'     => __( 'Title Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#23527c',
				'selectors' => array(
					'{{WRAPPER}} .rael-post-carousel__entry-title:hover, {{WRAPPER}} .rael-post-carousel__entry-title a:hover' => 'color: {{VALUE}};',
				),

			)
		);

		$this->add_responsive_control(
			'rael_post_title_alignment',
			array(
				'label'     => __( 'Title Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
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
				'selectors' => array(
					'{{WRAPPER}} .rael-post-carousel__entry-title' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_post_title_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .rael-post-carousel__entry-title, {{WRAPPER}} .rael-post-carousel__entry-title > a',
			)
		);

		$this->add_control(
			'rael_post_title_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-post-carousel__entry-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_post_excerpt_style',
			array(
				'label'     => __( 'Excerpt Style', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_post_excerpt_color',
			array(
				'label'     => __( 'Excerpt Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-post-carousel__excerpt p' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_post_excerpt_alignment',
			array(
				'label'     => __( 'Excerpt Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-post-carousel__excerpt p'                                => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .rael-post-carousel__excerpt .rael-post-carousel__readmore-btn' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_post_excerpt_typography',
				'label'    => __( 'Excerpt Typography', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-post-carousel__excerpt p',
			)
		);

		$this->add_control(
			'rael_post_excerpt_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-post-carousel__excerpt p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register styles for the Terms section in the post carousel.
	 *
	 * This function defines and registers controls for configuring the style
	 * of post terms, including color, typography, and margin.
	 */
	public function register_style_tab_terms_section() {
		$this->start_controls_section(
			'rael_post_carousel_style_tab_terms_section',
			array(
				'label'     => __( 'Terms Style', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_show_post_terms' => 'yes',
				),
			)
		);
		$this->add_control(
			'rael_post_terms_color',
			array(
				'label'     => __( 'Terms Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-post-carousel__meta-categories li a, {{WRAPPER}} .rael-post-carousel__meta-categories li, {{WRAPPER}} .rael-post-carousel__meta-categories li a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_post_terms_typography',
				'label'    => __( 'Meta Typography', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-post-carousel__meta-categories li a, {{WRAPPER}} .rael-post-carousel__meta-categories li, {{WRAPPER}} .rael-post-carousel__meta-categories li a',
			)
		);

		$this->add_control(
			'rael_post_terms_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-post-carousel__meta-categories, {{WRAPPER}} .rael-post-carousel__meta-categories' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
	}
	/**
	 * Register styles for the Meta Date section in the post carousel.
	 *
	 * This function defines and registers controls for configuring the style
	 * of the meta date, including background, color, typography, margin, and shadow.
	 */
	public function register_style_tab_meta_date_section() {
		$this->start_controls_section(
			'rael_post_carousel_style_tab_meta_date_section',
			array(
				'label'     => __( 'Meta Date Style', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_post_preset_style' => array( 'three' ),
					'rael_show_meta'         => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_post_meta_date_background',
				'label'    => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-post-carousel__meta-posted-on',
			)
		);

		$this->add_control(
			'rael_post_meta_date_color',
			array(
				'label'     => __( 'Meta Date Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-post-carousel__meta-posted-on' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_post_meta_date_typography',
				'label'    => __( 'Meta Date Typography', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-post-carousel__meta-posted-on',
			)
		);

		$this->add_control(
			'rael_post_meta_date_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-post-carousel__meta-posted-on' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'rael_post_meta_date_shadow',
				'label'     => __( 'Shadow', 'responsive-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .rael-post-carousel__meta-posted-on',
				'condition' => array(
					'rael_post_preset_style' => array( 'three' ),
				),
			)
		);
		$this->end_controls_section();
	}
	/**
	 * Register styles for the Meta Date Position section in the post carousel.
	 *
	 * This function generates general controls for configuring the position of
	 * the meta date within the post carousel.
	 */
	public function register_style_tab_meta_date_position_section() {
		$this->generate_general_controls(
			'rael_post_carousel_style_tab_meta_date_position_section',
			esc_html__( 'Meta Date Position', 'responsive-addons-for-elementor' ),
			'.rael-post-carousel__meta-posted-on',
			array(
				'rael_post_preset_style' => array( 'three' ),
				'rael_show_meta'         => 'yes',
			)
		);
	}
	/**
	 * Register styles for the Meta section in the post carousel.
	 *
	 * This function defines and registers controls for configuring the style
	 * of post meta, including color, typography, alignment, and margin.
	 */
	public function register_style_tab_meta_section() {
		$this->start_controls_section(
			'rael_post_carousel_style_tab_meta_section',
			array(
				'label'     => __( 'Meta Style', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_show_meta' => 'yes',
				),
			)
		);
		$this->add_control(
			'rael_post_meta_color',
			array(
				'label'     => __( 'Meta Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-post-carousel__entry-meta, .rael-post-carousel__entry-meta a, {{WRAPPER}} .rael-post-carousel__entry-meta > .rael-post-carousel__meta-posted-on' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_post_meta_alignment',
			array(
				'label'     => __( 'Meta Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .rael-grid-post .rael-post-carousel__entry-footer, {{WRAPPER}} .rael-grid-post .rael-post-carousel__entry-meta' => 'justify-content: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_post_meta_header_typography',
				'label'    => __( 'Meta Typography', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-post-carousel__entry-meta > span,{{WRAPPER}} .rael-post-carousel__entry-meta > .rael-post-carousel__posted-by,{{WRAPPER}} .rael-post-carousel__entry-meta > .rael-post-carousel__meta-posted-on',
			)
		);

		$this->add_control(
			'rael_post_meta_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-post-carousel__entry-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
	}
	/**
	 * Register styles for the Meta Header section in the post carousel.
	 *
	 * This function generates general controls for configuring the position of
	 * the meta header within the post carousel.
	 */
	public function register_style_tab_meta_header_section() {
		$this->generate_general_controls(
			'rael_post_carousel_style_tab_meta_header_position_section',
			__( 'Meta Position', 'responsive-addons-for-elementor' ),
			'.rael-grid-post .rael-post-carousel__entry-meta',
			array(
				'rael_show_meta'     => 'yes',
				'rael_meta_position' => array( 'meta-entry-header' ),
			)
		);
	}
	/**
	 * Register styles for the Meta Footer section in the post carousel.
	 *
	 * This function generates general controls for configuring the position of
	 * the meta footer within the post carousel.
	 */
	public function register_style_tab_meta_footer_section() {
		$this->generate_general_controls(
			'rael_post_carousel_style_tab_meta_footer_position_section',
			__( 'Meta Position', 'responsive-addons-for-elementor' ),
			'.rael-grid-post .rael-post-carousel__entry-footer',
			array(
				'rael_show_meta'     => 'yes',
				'rael_meta_position' => array( 'meta-entry-footer' ),
			)
		);
	}
	/**
	 * Register the styles for the arrows section in the post carousel widget.
	 *
	 * This function is responsible for registering controls related to the arrows section in the widget's style tab.
	 * It includes controls for arrow selection, size, position, and styles for normal and hover states.
	 */
	public function register_style_tab_arrows_section() {
		$this->start_controls_section(
			'rael_post_carousel_style_tab_arrows_section',
			array(
				'label'     => __( 'Arrows', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_arrows' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_arrow',
			array(
				'label'       => __( 'Choose Arrow', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'default'     => 'fa fa-angle-right',
				'options'     => array(
					'fa fa-angle-right'          => __( 'Angle', 'responsive-addons-for-elementor' ),
					'fa fa-angle-double-right'   => __( 'Double Angle', 'responsive-addons-for-elementor' ),
					'fa fa-chevron-right'        => __( 'Chevron', 'responsive-addons-for-elementor' ),
					'fa fa-chevron-circle-right' => __( 'Chevron Circle', 'responsive-addons-for-elementor' ),
					'fa fa-arrow-right'          => __( 'Arrow', 'responsive-addons-for-elementor' ),
					'fa fa-long-arrow-right'     => __( 'Long Arrow', 'responsive-addons-for-elementor' ),
					'fa fa-caret-right'          => __( 'Caret', 'responsive-addons-for-elementor' ),
					'fa fa-caret-square-o-right' => __( 'Caret Square', 'responsive-addons-for-elementor' ),
					'fa fa-arrow-circle-right'   => __( 'Arrow Circle', 'responsive-addons-for-elementor' ),
					'fa fa-arrow-circle-o-right' => __( 'Arrow Circle O', 'responsive-addons-for-elementor' ),
					'fa fa-toggle-right'         => __( 'Toggle', 'responsive-addons-for-elementor' ),
					'fa fa-hand-o-right'         => __( 'Hand', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_arrows_size',
			array(
				'label'      => __( 'Arrows Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => '22' ),
				'range'      => array(
					'px' => array(
						'min'  => 15,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_left_arrow_position',
			array(
				'label'      => __( 'Align Left Arrow', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 40,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_right_arrow_position',
			array(
				'label'      => __( 'Align Right Arrow', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 40,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'rael_arrows_style_tabs' );

		$this->start_controls_tab(
			'rael_arrows_tab_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_arrows_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_arrows_color_normal',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'rael_arrows_border_normal',
				'label'       => __( 'Border', 'responsive-addons-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev',
			)
		);

		$this->add_control(
			'rael_arrows_border_radius_normal',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_arrows_tab_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_arrows_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_arrows_color_hover',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_arrows_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_arrows_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register the styles for the dots section in the post carousel widget.
	 *
	 * This function is responsible for registering controls related to the dots section in the widget's style tab.
	 * It includes controls for dot position, custom width/height, size, spacing, and styles for normal, hover, and active states.
	 */
	public function register_style_tab_dots_section() {
		$this->start_controls_section(
			'rael_post_carousel_style_tab_dots_section',
			array(
				'label'     => __( 'Dots', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_dots' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_dots_position',
			array(
				'label'   => __( 'Position', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'inside'  => __( 'Inside', 'responsive-addons-for-elementor' ),
					'outside' => __( 'Outside', 'responsive-addons-for-elementor' ),
				),
				'default' => 'outside',
			)
		);

		$this->add_control(
			'rael_is_use_dots_custom_width_height',
			array(
				'label'        => __( 'Use Custom Width/Height?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_responsive_control(
			'rael_dots_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 2,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'rael_is_use_dots_custom_width_height' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_dots_height',
			array(
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 2,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'rael_is_use_dots_custom_width_height' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_dots_size',
			array(
				'label'      => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 2,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'rael_is_use_dots_custom_width_height' => '',
				),
			)
		);

		$this->add_responsive_control(
			'rael_dots_spacing',
			array(
				'label'      => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 30,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->start_controls_tabs( 'rael_dots_style_tabs' );

		$this->start_controls_tab(
			'rael_dots_tab_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_dots_color_normal',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:not(.swiper-pagination-bullet-active)' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'rael_dots_border_normal',
				'label'       => __( 'Border', 'responsive-addons-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet',
			)
		);

		$this->add_control(
			'rael_dots_border_radius_normal',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_dots_padding',
			array(
				'label'              => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px', 'em', '%' ),
				'allowed_dimensions' => 'vertical',
				'placeholder'        => array(
					'top'    => '',
					'right'  => 'auto',
					'bottom' => '',
					'left'   => 'auto',
				),
				'selectors'          => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullets' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_sub_section_dots_active_mode',
			array(
				'label'     => __( 'Dots Active Style', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_active_dot_color_normal',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_active_dots_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 2,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'rael_active_dots_height',
			array(
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 2,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'rael_active_dots_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_active_dots_shadow',
				'label'    => __( 'Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_dots_tab_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_dots_color_hover',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_dots_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}
	/**
	 * Get the full path to a specific template file for the post carousel widget.
	 *
	 * This function takes a template name as a parameter and constructs the full file path based on the widget's directory structure.
	 *
	 * @param string $template_name The name of the template file (without extension).
	 *
	 * @return string The full path to the template file.
	 */
	private function get_template( $template_name ) {
		$file_name = RAEL_DIR . 'includes/widgets-manager/widgets/skins/post-carousel/' . sanitize_file_name( $template_name ) . '.php';
		return $file_name;
	}

	/**
	 * Render the widget.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.7.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$settings['posts_ids']      = $settings['rael_posts_ids'];
		$settings['posts_per_page'] = $settings['rael_posts_per_page'];
		$settings['post__not_in']   = $settings['rael_post_not_in'];
		$settings['authors']        = $settings['rael_authors'];
		$settings['offset']         = $settings['rael_offset'];

		$args = Helper::get_query_args( $settings );
		$args = Helper::get_dynamic_args( $settings, $args );

		$this->add_render_attribute(
			'rael-post-carousel-container',
			array(
				'class' => array(
					'swiper-container-wrap',
					'rael-post-carousel-container',
					'rael-post-carousel-wrap',
					'rael-post-carousel-style--' . ( '' !== $settings['rael_post_preset_style'] ? $settings['rael_post_preset_style'] : 'default' ),
				),
				'id'    => 'rael-post-carousel-' . esc_attr( $this->get_id() ),
			)
		);

		if ( $settings['rael_dots_position'] ) {
			$this->add_render_attribute( 'rael-post-carousel-container', 'class', 'swiper-container-wrap-dots-' . $settings['rael_dots_position'] );
		}

		$this->add_render_attribute(
			'rael-post-carousel-wrap',
			array(
				'class'           => array(
					'swiper' . RAEL_SWIPER_CONTAINER,
					'rael-post-carousel',
					'swiper-container-' . esc_attr( $this->get_id() ),
					'rael-post-appender-' . esc_attr( $this->get_id() ),
				),
				'data-pagination' => '.swiper-pagination-' . esc_attr( $this->get_id() ),
				'data-arrow-next' => '.swiper-button-next-' . esc_attr( $this->get_id() ),
				'data-arrow-prev' => '.swiper-button-prev-' . esc_attr( $this->get_id() ),
			)
		);

		if ( $settings['rael_show_read_more_button'] ) {
			$this->add_render_attribute(
				'rael-post-carousel-wrap',
				'class',
				'rael-post-carousel--show-read-more-button'
			);
		}

		if ( ! empty( $settings['rael_items']['size'] ) ) {
			$this->add_render_attribute( 'rael-post-carousel-wrap', 'data-items', $settings['rael_items']['size'] );
		}
		if ( ! empty( $settings['rael_items_tablet']['size'] ) ) {
			$this->add_render_attribute( 'rael-post-carousel-wrap', 'data-items-tablet', $settings['rael_items_tablet']['size'] );
		}
		if ( ! empty( $settings['rael_items_mobile']['size'] ) ) {
			$this->add_render_attribute( 'rael-post-carousel-wrap', 'data-items-mobile', $settings['rael_items_mobile']['size'] );
		}
		if ( ! empty( $settings['rael_margin']['size'] ) ) {
			$this->add_render_attribute( 'rael-post-carousel-wrap', 'data-margin', $settings['rael_margin']['size'] );
		}
		if ( ! empty( $settings['rael_margin_tablet']['size'] ) ) {
			$this->add_render_attribute( 'rael-post-carousel-wrap', 'data-margin-tablet', $settings['rael_margin_tablet']['size'] );
		}
		if ( ! empty( $settings['rael_margin_mobile']['size'] ) ) {
			$this->add_render_attribute( 'rael-post-carousel-wrap', 'data-margin-mobile', $settings['rael_margin_mobile']['size'] );
		}
		if ( $settings['rael_carousel_effect'] ) {
			$this->add_render_attribute( 'rael-post-carousel-wrap', 'data-effect', $settings['rael_carousel_effect'] );
		}
		if ( ! empty( $settings['rael_slider_speed']['size'] ) ) {
			$this->add_render_attribute( 'rael-post-carousel-wrap', 'data-speed', $settings['rael_slider_speed']['size'] );
		}

		if ( 'yes' === $settings['rael_autoplay'] && ! empty( $settings['rael_autoplay_speed']['size'] ) ) {
			$this->add_render_attribute( 'rael-post-carousel-wrap', 'data-autoplay', $settings['rael_autoplay_speed']['size'] );
		} else {
			$this->add_render_attribute( 'rael-post-carousel-wrap', 'data-autoplay', '0' );
		}

		if ( 'yes' === $settings['rael_pause_on_hover'] ) {
			$this->add_render_attribute( 'rael-post-carousel-wrap', 'data-pause-on-hover', 'true' );
		}

		if ( 'yes' === $settings['rael_infinite_loop'] ) {
			$this->add_render_attribute( 'rael-post-carousel-wrap', 'data-loop', '1' );
		}
		if ( 'yes' === $settings['rael_grab_cursor'] ) {
			$this->add_render_attribute( 'rael-post-carousel-wrap', 'data-grab-cursor', '1' );
		}
		if ( 'yes' === $settings['rael_arrows'] ) {
			$this->add_render_attribute( 'rael-post-carousel-wrap', 'data-arrows', '1' );
		}
		if ( 'yes' === $settings['rael_dots'] ) {
			$this->add_render_attribute( 'rael-post-carousel-wrap', 'data-dots', '1' );
		}

		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael-post-carousel-container' ) ); ?>>
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael-post-carousel-wrap' ) ); ?>>
				<div class="swiper-wrapper">
					<?php

					$template = $this->get_template( $this->get_settings( 'rael_template_layout' ) );
					if ( file_exists( $template ) ) {
						$query = new \WP_Query( $args );

						if ( $query->have_posts() ) {
							while ( $query->have_posts() ) {
								$query->the_post();
								include $template;
							}
						} else {
							echo '<p class="no-posts-found">' . esc_html__( 'No posts found!', 'responsive-addons-for-elementor' ) . '</p>';
						}
						wp_reset_postdata();
					} else {
						echo '<p class="no-posts-found">' . esc_html__( 'No layout found!', 'responsive-addons-for-elementor' ) . '</p>';
					}
					?>
				</div>
			</div>
			<div class="clearfix"></div>
			<?php

			/**
			 * Render Slider Dots!
			 */
			$this->render_dots();

			/**
			 * Render Slider Navigations!
			 */
			$this->render_arrows();
			?>
		</div>
		<?php
	}
	/**
	 * Render the pagination dots for the post carousel widget.
	 *
	 * This method outputs the HTML structure for pagination dots based on the widget settings.
	 */
	public function render_dots() {
		$settings = $this->get_settings_for_display();

		if ( 'yes' === $settings['rael_dots'] ) {
			?>
				<!-- Add Pagination -->
				<div class="swiper-pagination swiper-pagination-<?php echo esc_attr( $this->get_id() ); ?>"></div>
			<?php
		}
	}
	/**
	 * Render the navigation arrows for the post carousel widget.
	 *
	 * This method outputs the HTML structure for navigation arrows based on the widget settings.
	 */
	protected function render_arrows() {
		$settings = $this->get_settings_for_display();

		if ( 'yes' === $settings['rael_arrows'] ) {
			?>
			<?php
			if ( $settings['rael_arrow'] ) {
				$next_arrow = $settings['rael_arrow'];
				$prev_arrow = str_replace( 'right', 'left', $settings['rael_arrow'] );
			} else {
				$next_arrow = 'fa fa-angle-right';
				$prev_arrow = 'fa fa-angle-left';
			}
			?>
			<!-- Add Arrows -->
			<div class="swiper-button-next swiper-button-next-<?php echo esc_attr( $this->get_id() ); ?>">
				<i class="<?php echo esc_attr( $next_arrow ); ?>"></i>
			</div>
			<div class="swiper-button-prev swiper-button-prev-<?php echo esc_attr( $this->get_id() ); ?>">
				<i class="<?php echo esc_attr( $prev_arrow ); ?>"></i>
			</div>
			<?php
		}
	}
}
