<?php
/**
 * Data Table Widget
 *
 * @package  Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use \Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.

}
/**
 * Elementor 'Data Table' widget class.
 */
class Responsive_Addons_For_Elementor_Data_Table extends Widget_Base {
	/**
	 * Slide prints count variable
	 *
	 * @var slide_prints_count
	 */
	private static $page_templates = null;

	/**
	 * Elementor saved section templates list
	 *
	 * @var section_templates
	 */
	private static $section_templates = null;

	/**
	 * Elementor saved widget templates list
	 *
	 * @var widget_templates
	 */
	private static $widget_templates = null;
	/**
	 * Get name function
	 */
	public function get_name() {
		return 'rael-data-table';
	}
	/**
	 * Get title function
	 */
	public function get_title() {
		return __( 'Data Table', 'responsive-addons-for-elementor' );
	}
	/**
	 * Get icon function
	 */
	public function get_icon() {
		return 'eicon-table rael-badge';
	}
	/**
	 * Get categories function
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}
	/**
	 * Get keywords function
	 */
	public function get_keywords() {
		return array( 'table', 'content table', 'data', 'comparison table', 'grid', 'details' );
	}
	/**
	 * Get custom help url function
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/data-table';
	}
	/**
	 * Get saved templates function
	 *
	 * @param string $type is the post type.
	 */
	public static function get_saved_templates( $type = 'page' ) {

		$template_type = $type . '_templates';

		$templates_list = array();

		if ( ( null === self::$page_templates && 'page' === $type ) || ( null === self::$section_templates && 'section' === $type ) || ( null === self::$widget_templates && 'widget' === $type ) ) {

			$posts = get_posts(
				array(
					'post_type'      => 'elementor_library',
					'orderby'        => 'title',
					'order'          => 'ASC',
					'posts_per_page' => '-1',
					'taxonomy'       => 'elementor_library_type',
					'terms'          => $type,
				)
			);

			foreach ( $posts as $post ) {

				$templates_list[] = array(
					'id'   => $post->ID,
					'name' => $post->post_title,
				);
			}

			self::${$template_type}[-1] = __( 'Select', 'responsive-addons-for-elementor' );

			if ( count( $templates_list ) ) {
				foreach ( $templates_list as $saved_row ) {

					$content_id                            = $saved_row['id'];
					$content_id                            = apply_filters( 'rael_wpml_object_id', $content_id );
					self::${$template_type}[ $content_id ] = $saved_row['name'];

				}
			} else {
				self::${$template_type}['no_template'] = __( 'It seems that, you have not saved any template yet.', 'responsive-addons-for-elementor' );
			}
		}

		return self::${$template_type};
	}
	/**
	 * Register controls function
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'rael_section_data_table_header',
			array(
				'label' => esc_html__( 'Header', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_section_data_table_sort',
			array(
				'label'        => __( 'Enable Table Sorting', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'true',
			)
		);
		$repeater = new Repeater();

		$repeater->add_control(
			'rael_data_table_header_col',
			array(
				'label'       => esc_html__( 'Column Name', 'responsive-addons-for-elementor' ),
				'default'     => esc_html__( 'Table Header', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'label_block' => false,
			)
		);

		$repeater->add_control(
			'rael_data_table_header_col_span',
			array(
				'label'       => esc_html__( 'Column Span', 'responsive-addons-for-elementor' ),
				'default'     => '',
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'label_block' => false,
			)
		);

		$repeater->add_control(
			'rael_data_table_header_col_icon_enabled',
			array(
				'label'        => esc_html__( 'Enable Header Icon', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'no', 'responsive-addons-for-elementor' ),
				'default'      => 'false',
				'return_value' => 'true',
			)
		);
		$repeater->add_control(
			'rael_data_table_header_icon_type',
			array(
				'label'     => esc_html__( 'Header Icon Type', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'none'  => array(
						'title' => esc_html__( 'None', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-ban',
					),
					'icon'  => array(
						'title' => esc_html__( 'Icon', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-star',
					),
					'image' => array(
						'title' => esc_html__( 'Image', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-picture-o',
					),
				),
				'default'   => 'icon',
				'condition' => array(
					'rael_data_table_header_col_icon_enabled' => 'true',
				),
			)
		);

		$repeater->add_control(
			'rael_data_table_header_col_icon_new',
			array(
				'label'            => esc_html__( 'Icon', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'rael_data_table_header_col_icon',
				'default'          => array(
					'value'   => 'fas fa-star',
					'library' => 'solid',
				),
				'condition'        => array(
					'rael_data_table_header_col_icon_enabled' => 'true',
					'rael_data_table_header_icon_type' => 'icon',
				),
			)
		);

		$repeater->add_control(
			'rael_data_table_header_col_img',
			array(
				'label'     => esc_html__( 'Image', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'rael_data_table_header_col_icon_enabled' => 'true',
					'rael_data_table_header_icon_type' => 'image',
				),
			)
		);

		$repeater->add_control(
			'rael_data_table_header_col_img_size',
			array(
				'label'       => esc_html__( 'Image Size(px)', 'responsive-addons-for-elementor' ),
				'default'     => '25',
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'condition'   => array(
					'rael_data_table_header_col_icon_enabled' => 'true',
					'rael_data_table_header_icon_type' => 'image',
				),
			)
		);

		$repeater->add_control(
			'rael_data_table_header_css_class',
			array(
				'label'       => esc_html__( 'CSS Class', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'label_block' => false,
			)
		);

		$repeater->add_control(
			'rael_data_table_header_css_id',
			array(
				'label'       => esc_html__( 'CSS ID', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'label_block' => false,
			)
		);

		$this->add_control(
			'rael_data_table_header_cols_data',
			array(
				'type'        => Controls_Manager::REPEATER,
				'seperator'   => 'before',
				'default'     => array(
					array( 'rael_data_table_header_col' => 'Table Header' ),
					array( 'rael_data_table_header_col' => 'Table Header' ),
					array( 'rael_data_table_header_col' => 'Table Header' ),
					array( 'rael_data_table_header_col' => 'Table Header' ),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{rael_data_table_header_col}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_data_table_cotnent',
			array(
				'label' => esc_html__( 'Content', 'responsive-addons-for-elementor' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'rael_data_table_content_row_type',
			array(
				'label'       => esc_html__( 'Row Type', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'row',
				'label_block' => false,
				'options'     => array(
					'row' => esc_html__( 'Row', 'responsive-addons-for-elementor' ),
					'col' => esc_html__( 'Column', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$repeater->add_control(
			'rael_data_table_content_row_colspan',
			array(
				'label'       => esc_html__( 'Col Span', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Default: 1 (optional).', 'responsive-addons-for-elementor' ),
				'default'     => 1,
				'min'         => 1,
				'label_block' => true,
				'condition'   => array(
					'rael_data_table_content_row_type' => 'col',
				),
			)
		);

		$repeater->add_control(
			'rael_data_table_content_type',
			array(
				'label'     => esc_html__( 'Content Type', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'icon'     => array(
						'title' => esc_html__( 'Icon', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-info',
					),
					'textarea' => array(
						'title' => esc_html__( 'Textarea', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-text-width',
					),
					'editor'   => array(
						'title' => esc_html__( 'Editor', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-pencil',
					),
					'template' => array(
						'title' => esc_html__( 'Templates', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-file',
					),
				),
				'default'   => 'textarea',
				'condition' => array(
					'rael_data_table_content_row_type' => 'col',
				),
			)
		);

		$repeater->add_control(
			'rael_data_table_content_row_rowspan',
			array(
				'label'       => esc_html__( 'Row Span', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Default: 1 (optional).', 'responsive-addons-for-elementor' ),
				'default'     => 1,
				'min'         => 1,
				'label_block' => true,
				'condition'   => array(
					'rael_data_table_content_row_type' => 'col',
				),
			)
		);

		$repeater->add_control(
			'rael_primary_templates_for_tables',
			array(
				'label'     => __( 'Choose Template', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_saved_templates(),
				'condition' => array(
					'rael_data_table_content_type' => 'template',
				),
			)
		);

		$repeater->add_control(
			'rael_data_table_icon_content_new',
			array(
				'label'            => esc_html__( 'Icon', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'rael_data_table_icon_content',
				'default'          => array(
					'value'   => 'fas fa-home',
					'library' => 'fa-solid',
				),
				'condition'        => array(
					'rael_data_table_content_type' => array( 'icon' ),
				),
			)
		);

		$repeater->add_control(
			'rael_data_table_content_row_title',
			array(
				'label'       => esc_html__( 'Cell Text', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => array( 'active' => true ),
				'label_block' => true,
				'default'     => esc_html__( 'Content', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_data_table_content_row_type' => 'col',
					'rael_data_table_content_type'     => 'textarea',
				),
			)
		);

		$repeater->add_control(
			'rael_data_table_content_row_content',
			array(
				'label'       => esc_html__( 'Cell Text', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::WYSIWYG,
				'label_block' => true,
				'default'     => esc_html__( 'Content', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_data_table_content_row_type' => 'col',
					'rael_data_table_content_type'     => 'editor',
				),
			)
		);

		$repeater->add_control(
			'rael_data_table_content_row_title_link',
			array(
				'label'         => esc_html__( 'Link', 'responsive-addons-for-elementor' ),
				'type'          => Controls_Manager::URL,
				'dynamic'       => array( 'active' => true ),
				'label_block'   => true,
				'default'       => array(
					'url'         => '',
					'is_external' => '',
				),
				'show_external' => true,
				'separator'     => 'before',
				'condition'     => array(
					'rael_data_table_content_row_type' => 'col',
					'rael_data_table_content_type'     => 'textarea',
				),
			)
		);

		$repeater->add_control(
			'rael_data_table_content_row_css_class',
			array(
				'label'       => esc_html__( 'CSS Class', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'label_block' => false,
				'condition'   => array(
					'rael_data_table_content_row_type' => 'col',
				),
			)
		);

		$repeater->add_control(
			'rael_data_table_content_row_css_id',
			array(
				'label'       => esc_html__( 'CSS ID', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'label_block' => false,
				'condition'   => array(
					'rael_data_table_content_row_type' => 'col',
				),
			)
		);

		$this->add_control(
			'rael_data_table_content_rows',
			array(
				'type'        => Controls_Manager::REPEATER,
				'seperator'   => 'before',
				'default'     => array(
					array( 'rael_data_table_content_row_type' => 'row' ),
					array( 'rael_data_table_content_row_type' => 'col' ),
					array( 'rael_data_table_content_row_type' => 'col' ),
					array( 'rael_data_table_content_row_type' => 'col' ),
					array( 'rael_data_table_content_row_type' => 'col' ),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{rael_data_table_content_row_type}}::{{rael_data_table_content_row_title || rael_data_table_content_row_content}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_adv_data_table_export',
			array(
				'label' => esc_html__( 'Export', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_adv_data_table_export_csv_button',
			array(
				'label' => __( 'Export table as CSV file', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::BUTTON,
				'text'  => __( 'Export', 'responsive-addons-for-elementor' ),
				'event' => 'rael:table:export',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_data_table_style_settings',
			array(
				'label' => esc_html__( 'General Style', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_table_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 100,
					'unit' => '%',
				),
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-data-table' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_table_alignment',
			array(
				'label'        => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'default'      => 'center',
				'options'      => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'prefix_class' => 'rael-table-align-',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_data_table_title_style_settings',
			array(
				'label' => esc_html__( 'Header Style', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_section_data_table_header_radius',
			array(
				'label'     => esc_html__( 'Header Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-data-table thead tr th:first-child' => 'border-radius: {{SIZE}}px 0px 0px 0px;',
					'{{WRAPPER}} .rael-data-table thead tr th:last-child' => 'border-radius: 0px {{SIZE}}px 0px 0px;',
					'.rtl {{WRAPPER}} .rael-data-table thead tr th:first-child' => 'border-radius: 0px {{SIZE}}px 0px 0px;',
					'.rtl {{WRAPPER}} .rael-data-table thead tr th:last-child' => 'border-radius: {{SIZE}}px 0px 0px 0px;',
				),
			)
		);

		$this->add_responsive_control(
			'rael_data_table_each_header_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-data-table .table-header th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rael-data-table tbody tr td .th-mobile-screen' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'rael_data_table_header_title_clrbg' );

		$this->start_controls_tab( 'rael_data_table_header_title_normal', array( 'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'rael_data_table_header_title_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-data-table thead tr th' => 'color: {{VALUE}};',
					'{{WRAPPER}} table.dataTable thead .sorting:after' => 'color: {{VALUE}};',
					'{{WRAPPER}} table.dataTable thead .sorting_asc:after' => 'color: {{VALUE}};',
					'{{WRAPPER}} table.dataTable thead .sorting_desc:after' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_data_table_header_title_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#4a4893',
				'selectors' => array(
					'{{WRAPPER}} .rael-data-table thead tr th' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_data_table_header_border',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-data-table thead tr th',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'rael_data_table_header_title_hover', array( 'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'rael_data_table_header_title_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-data-table thead tr th:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} table.dataTable thead .sorting:after:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} table.dataTable thead .sorting_asc:after:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} table.dataTable thead .sorting_desc:after:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_data_table_header_title_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-data-table thead tr th:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_data_table_header_hover_border',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-data-table thead tr th:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_data_table_header_title_typography',
				'selector' => '{{WRAPPER}} .rael-data-table thead > tr th .data-table-header-text',
			)
		);

		$this->add_responsive_control(
			'rael_header_icon_size',
			array(
				'label'      => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 70,
					),
				),
				'default'    => array(
					'size' => 20,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-data-table thead tr th i'                           => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-data-table thead tr th .data-table-header-svg-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_header_icon_position_from_top',
			array(
				'label'      => __( 'Icon Position', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 70,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-data-table thead tr th .data-header-icon' => 'top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_header_icon_space',
			array(
				'label'      => __( 'Icon Space', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 70,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-data-table thead tr th i, {{WRAPPER}} .rael-data-table thead tr th img' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_data_table_header_title_alignment',
			array(
				'label'        => esc_html__( 'Title Alignment', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => true,
				'options'      => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'      => 'left',
				'prefix_class' => 'rael-dt-th-align%s-',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_data_table_content_style_settings',
			array(
				'label' => esc_html__( 'Content Style', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'rael_data_table_cell_border',
				'label'     => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .rael-data-table tbody tr td',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'rael_data_table_each_cell_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-data-table tbody tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_data_table_content_typography',
				'selector' => '{{WRAPPER}} .rael-data-table tbody tr td',
			)
		);

		$this->start_controls_tabs( 'rael_data_table_content_row_cell_styles' );

		$this->start_controls_tab( 'rael_data_table_odd_cell_style', array( 'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'rael_data_table_content_odd_style_heading',
			array(
				'label' => esc_html__( 'Odd Cell', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_data_table_content_color_odd',
			array(
				'label'     => esc_html__( 'Color ( Odd Row )', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6d7882',
				'selectors' => array(
					'{{WRAPPER}} .rael-data-table tbody > tr:nth-child(2n) td' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_data_table_content_bg_odd',
			array(
				'label'     => esc_html__( 'Background ( Odd Row )', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f2f2f2',
				'selectors' => array(
					'{{WRAPPER}} .rael-data-table tbody > tr:nth-child(2n) td' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_data_table_content_even_style_heading',
			array(
				'label'     => esc_html__( 'Even Cell', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_data_table_content_even_color',
			array(
				'label'     => esc_html__( 'Color ( Even Row )', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6d7882',
				'selectors' => array(
					'{{WRAPPER}} .rael-data-table tbody > tr:nth-child(2n+1) td' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_data_table_content_bg_even_color',
			array(
				'label'     => esc_html__( 'Background Color (Even Row)', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-data-table tbody > tr:nth-child(2n+1) td' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'rael_data_table_odd_cell_hover_style', array( 'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'rael_data_table_content_odd_hover_style_heading',
			array(
				'label' => esc_html__( 'Odd Cell', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_data_table_content_hover_color_odd',
			array(
				'label'     => esc_html__( 'Color ( Odd Row )', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-data-table tbody > tr:nth-child(2n) td:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_data_table_content_hover_bg_odd',
			array(
				'label'     => esc_html__( 'Background ( Odd Row )', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-data-table tbody > tr:nth-child(2n) td:hover' => 'background: {{VALUE}};',
				),
				'seperator' => 'after',
			)
		);

		$this->add_control(
			'rael_data_table_content_even_hover_style_heading',
			array(
				'label' => esc_html__( 'Even Cell', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_data_table_content_hover_color_even',
			array(
				'label'     => esc_html__( 'Color ( Even Row )', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6d7882',
				'selectors' => array(
					'{{WRAPPER}} .rael-data-table tbody > tr:nth-child(2n+1) td:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_data_table_content_bg_even_hover_color',
			array(
				'label'     => esc_html__( 'Background Color (Even Row)', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-data-table tbody > tr:nth-child(2n+1) td:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'rael_data_table_content_link_typo',
			array(
				'label'     => esc_html__( 'Link Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'rael_data_table_link_tabs' );

		$this->start_controls_tab( 'rael_data_table_link_normal', array( 'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'rael_data_table_link_normal_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#c15959',
				'selectors' => array(
					'{{WRAPPER}} .rael-data-table-wrap table td a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'rael_data_table_link_hover', array( 'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'rael_data_table_link_hover_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6d7882',
				'selectors' => array(
					'{{WRAPPER}} .rael-data-table-wrap table td a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_data_table_content_alignment',
			array(
				'label'        => esc_html__( 'Content Alignment', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => true,
				'options'      => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'toggle'       => true,
				'default'      => 'left',
				'prefix_class' => 'rael-dt-td-align%s-',
			)
		);

		$this->add_control(
			'rael_data_table_content_icon_style',
			array(
				'label'     => esc_html__( 'Icon Style', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'rael_data_table_content_icon_size',
			array(
				'label'      => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 70,
					),
				),
				'default'    => array(
					'size' => 20,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-data-table tbody .td-content-wrapper .rael-datatable-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-data-table tbody .td-content-wrapper .rael-datatable-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->start_controls_tabs( 'rael_data_table_icon_tabs' );

		// Normal State Tab.
		$this->start_controls_tab( 'rael_data_table_icon_normal', array( 'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'rael_data_table_icon_normal_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#c15959',
				'selectors' => array(
					'{{WRAPPER}} .rael-data-table tbody .td-content-wrapper .rael-datatable-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-data-table tbody .td-content-wrapper .rael-datatable-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'rael_data_table_icon_hover', array( 'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'rael_data_table_link_hover_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6d7882',
				'selectors' => array(
					'{{WRAPPER}} .rael-data-table tbody .td-content-wrapper:hover .rael-datatable-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-data-table tbody .td-content-wrapper:hover .rael-datatable-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_data_table_responsive_style_settings',
			array(
				'label'   => esc_html__( 'Responsive Options', 'responsive-addons-for-elementor' ),
				'devices' => array( 'tablet', 'mobile' ),
				'tab'     => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_enable_responsive_header_styles',
			array(
				'label'        => __( 'Enable Responsive Table', 'responsive-addons-for-elementor' ),
				'description'  => esc_html__( 'If enabled, table header will be responsive for mobile automatically.', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_responsive_control(
			'rael_mobile_table_header_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 100,
					'unit' => 'px',
				),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-data-table .th-mobile-screen' => 'flex-basis: {{SIZE}}px;',
				),
				'condition'  => array(
					'rael_enable_responsive_header_styles' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_data_table_responsive_header_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-data-table tbody .th-mobile-screen'   => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_enable_responsive_header_styles' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_data_table_responsive_header_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-data-table tbody .th-mobile-screen'   => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_enable_responsive_header_styles' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_data_table_responsive_header_typography',
				'selector'  => '{{WRAPPER}} .rael-data-table .th-mobile-screen',
				'condition' => array(
					'rael_enable_responsive_header_styles' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'rael_data_table_responsive_header_border',
				'label'     => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} tbody td .th-mobile-screen',
				'condition' => array(
					'rael_enable_responsive_header_styles' => 'yes',
				),
			)
		);

		$this->end_controls_section();

	}
	/**
	 * Render function
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$table_tr = array();
		$table_td = array();

		foreach ( $settings['rael_data_table_content_rows'] as $content_row ) {
			$row_id = uniqid();
			if ( 'row' === $content_row['rael_data_table_content_row_type'] ) {
				$table_tr[] = array(
					'id'   => $row_id,
					'type' => $content_row['rael_data_table_content_row_type'],
				);

			}
			if ( 'col' === $content_row['rael_data_table_content_row_type'] ) {

				$icon_migrated = isset( $settings['__fa4_migrated']['rael_data_table_icon_content_new'] );
				$icon_is_new   = empty( $settings['rael_data_table_icon_content'] );

				$target   = ! empty( $content_row['rael_data_table_content_row_title_link']['is_external'] ) ? 'target="_blank"' : '';
				$nofollow = ! empty( $content_row['rael_data_table_content_row_title_link']['nofollow'] ) ? 'rel="nofollow"' : '';

				$table_tr_keys = array_keys( $table_tr );
				$last_key      = end( $table_tr_keys );

				$tbody_content = ( 'editor' === $content_row['rael_data_table_content_type'] ) ? $content_row['rael_data_table_content_row_content'] : wp_kses_post( $content_row['rael_data_table_content_row_title'] );

				$table_td[] = array(
					'row_id'           => $table_tr[ $last_key ]['id'],
					'type'             => $content_row['rael_data_table_content_row_type'],
					'content_type'     => $content_row['rael_data_table_content_type'],
					'template'         => $content_row['rael_primary_templates_for_tables'],
					'title'            => $tbody_content,
					'link_url'         => ! empty( $content_row['rael_data_table_content_row_title_link']['url'] ) ? $content_row['rael_data_table_content_row_title_link']['url'] : '',
					'icon_content_new' => ! empty( $content_row['rael_data_table_icon_content_new'] ) ? $content_row['rael_data_table_icon_content_new'] : '',
					'icon_content'     => ! empty( $content_row['rael_data_table_icon_content'] ) ? $content_row['rael_data_table_icon_content'] : '',
					'icon_migrated'    => $icon_migrated,
					'icon_is_new'      => $icon_is_new,
					'link_target'      => $target,
					'nofollow'         => $nofollow,
					'colspan'          => $content_row['rael_data_table_content_row_colspan'],
					'rowspan'          => $content_row['rael_data_table_content_row_rowspan'],
					'tr_class'         => $content_row['rael_data_table_content_row_css_class'],
					'tr_id'            => $content_row['rael_data_table_content_row_css_id'],
				);
			}
		}

		$table_th_count = count( $settings['rael_data_table_header_cols_data'] );
		$this->add_render_attribute(
			'rael_data_table_wrap',
			array(
				'class'                  => 'rael-data-table-wrap',
				'data-table_id'          => esc_attr( $this->get_id() ),
				'data-custom_responsive' => $settings['rael_enable_responsive_header_styles'] ? 'true' : 'false',
			)
		);
		if ( isset( $settings['rael_section_data_table_sort'] ) && $settings['rael_section_data_table_sort'] ) {
			$this->add_render_attribute( 'rael_data_table_wrap', 'data-table_enabled', 'true' );
		}
		$this->add_render_attribute(
			'rael_data_table',
			array(
				'class' => array( 'tablesorter rael-data-table', esc_attr( $settings['rael_table_alignment'] ) ),
				'id'    => 'rael-data-table-' . esc_attr( $this->get_id() ),
			)
		);

		$this->add_render_attribute(
			'td_content',
			array(
				'class' => 'td-content',
			)
		);

		if ( 'yes' === $settings['rael_enable_responsive_header_styles'] ) {
			$this->add_render_attribute( 'rael_data_table_wrap', 'class', 'custom-responsive-option-enable' );
		}
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_data_table_wrap' ) ); ?>>
			<table <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_data_table' ) ); ?>>
				<thead>
				<tr class="table-header">
					<?php
					$i = 0; foreach ( $settings['rael_data_table_header_cols_data'] as $header_title ) :
						$this->add_render_attribute(
							'th_class' . $i,
							array(
								'class'   => array( $header_title['rael_data_table_header_css_class'] ),
								'id'      => $header_title['rael_data_table_header_css_id'],
								'colspan' => $header_title['rael_data_table_header_col_span'],
							)
						);
							$this->add_render_attribute( 'th_class' . $i, 'class', 'sorting' );
						?>
						<th <?php echo wp_kses_post( $this->get_render_attribute_string( 'th_class' . $i ) ); ?>>
							<?php if ( 'true' === $header_title['rael_data_table_header_col_icon_enabled'] && 'icon' === $header_title['rael_data_table_header_icon_type'] ) : ?>
								<?php if ( empty( $header_title['rael_data_table_header_col_icon'] ) || isset( $header_title['__fa4_migrated']['rael_data_table_header_col_icon_new'] ) ) { ?>
									<?php if ( isset( $header_title['rael_data_table_header_col_icon_new']['value']['url'] ) ) : ?>
										<img class="data-header-icon data-table-header-svg-icon" src="<?php echo wp_kses_post( $header_title['rael_data_table_header_col_icon_new']['value']['url'] ); ?>" alt="<?php echo esc_attr( get_post_meta( $header_title['rael_data_table_header_col_icon_new']['value']['id'], '_wp_attachment_image_alt', true ) ); ?>" />
									<?php else : ?>
										<i class="<?php echo wp_kses_post( $header_title['rael_data_table_header_col_icon_new']['value'] ); ?> data-header-icon"></i>
									<?php endif; ?>
								<?php } else { ?>
									<i class="<?php echo wp_kses_post( $header_title['rael_data_table_header_col_icon'] ); ?> data-header-icon"></i>
								<?php } ?>
							<?php endif; ?>
							<?php
							if ( 'true' === $header_title['rael_data_table_header_col_icon_enabled'] && 'image' === $header_title['rael_data_table_header_icon_type'] ) :
								$this->add_render_attribute(
									'data_table_th_img' . $i,
									array(
										'src'   => esc_url( $header_title['rael_data_table_header_col_img']['url'] ),
										'class' => 'rael-data-table-th-img',
										'style' => "width:{$header_title['rael_data_table_header_col_img_size']}px;",
										'alt'   => esc_attr( get_post_meta( $header_title['rael_data_table_header_col_img']['id'], '_wp_attachment_image_alt', true ) ),
									)
								);
								?>
								<img <?php echo wp_kses_post( $this->get_render_attribute_string( 'data_table_th_img' . $i ) ); ?>><?php endif; ?><span class="data-table-header-text"><?php echo esc_html__( wp_kses_post( $header_title['rael_data_table_header_col'] ), 'responsive-addons-for-elementor' ); //phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText ?></span></th>
						<?php
						$i++;
endforeach;
					?>
				</tr>
				</thead>
				<tbody>
				<?php for ( $i = 0; $i < count( $table_tr ); $i++ ) : // phpcs:ignore Generic.CodeAnalysis.ForLoopWithTestFunctionCall.NotAllowed, Squiz.PHP.DisallowSizeFunctionsInLoops.Found, Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace ?>
					<tr>
						<?php
						for ( $j = 0; $j < count( $table_td ); $j++ ) { // phpcs:ignore Generic.CodeAnalysis.ForLoopWithTestFunctionCall.NotAllowed, Squiz.PHP.DisallowSizeFunctionsInLoops.Found, Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace
							if ( $table_tr[ $i ]['id'] === $table_td[ $j ]['row_id'] ) {

								$this->add_render_attribute(
									'table_inside_td' . $i . $j,
									array(
										'colspan' => $table_td[ $j ]['colspan'] > 1 ? $table_td[ $j ]['colspan'] : '',
										'rowspan' => $table_td[ $j ]['rowspan'] > 1 ? $table_td[ $j ]['rowspan'] : '',
										'class'   => $table_td[ $j ]['tr_class'],
										'id'      => $table_td[ $j ]['tr_id'],
									)
								);
								?>
								<?php if ( 'icon' === $table_td[ $j ]['content_type'] ) : ?>
									<td <?php echo wp_kses_post( $this->get_render_attribute_string( 'table_inside_td' . $i . $j ) ); ?>>
										<div class="td-content-wrapper">
											<?php if ( $table_td[ $j ]['icon_is_new'] || $table_td[ $j ]['icon_migrated'] ) { ?>
												<span class="rael-datatable-icon">
														<?php Icons_Manager::render_icon( $table_td[ $j ]['icon_content_new'] ); ?>
														</span>
											<?php } else { ?>
												<span class="<?php echo wp_kses_post( $table_td[ $j ]['icon_content'] ); ?>" aria-hidden="true"></span>
											<?php } ?>
										</div>
									</td>
								<?php elseif ( 'textarea' === $table_td[ $j ]['content_type'] && ! empty( $table_td[ $j ]['link_url'] ) ) : ?>
									<td <?php echo wp_kses_post( $this->get_render_attribute_string( 'table_inside_td' . $i . $j ) ); ?>>
										<div class="td-content-wrapper">
											<a href="<?php echo esc_url( $table_td[ $j ]['link_url'] ); ?>" <?php echo wp_kses_post( $table_td[ $j ]['link_target'] ); ?> <?php echo esc_attr( $table_td[ $j ]['nofollow'] ); ?>><?php echo wp_kses_post( $table_td[ $j ]['title'] ); ?></a>
										</div>
									</td>

								<?php elseif ( 'template' === $table_td[ $j ]['content_type'] && ! empty( $table_td[ $j ]['template'] ) ) : ?>
									<td <?php echo wp_kses_post( $this->get_render_attribute_string( 'table_inside_td' . $i . $j ) ); ?>>
										<div class="td-content-wrapper">
											<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'td_content' ) ); ?>>
												<?php echo esc_html( Plugin::$instance->frontend->get_builder_content( intval( $table_td[ $j ]['template'] ), true ) ); ?>
											</div>
										</div>
									</td>
								<?php else : ?>
									<td <?php echo wp_kses_post( $this->get_render_attribute_string( 'table_inside_td' . $i . $j ) ); ?>>
										<div class="td-content-wrapper"><div <?php echo wp_kses_post( $this->get_render_attribute_string( 'td_content' ) ); ?>><?php echo wp_kses_post( $table_td[ $j ]['title'] ); ?></div></div>
									</td>
								<?php endif; ?>
								<?php
							}
						}
						?>
					</tr>
				<?php endfor; ?>
				</tbody>
			</table>
		</div>
		<?php
	}
}
