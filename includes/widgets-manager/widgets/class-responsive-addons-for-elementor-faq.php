<?php
/**
 * FAQ Widget
 *
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Icons_Manager;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.

}

/**
 * Elementor 'FAQ' widget class.
 *
 * @since 1.2.0
 */
class Responsive_Addons_For_Elementor_FAQ extends Widget_Base {

	/**
	 * Elementor Saved page templates list
	 *
	 * @var page_templates
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
	 *
	 * @access public
	 */
	public function get_name() {
		return 'rael-faq';
	}

	/**
	 * Get title function
	 *
	 * @access public
	 */
	public function get_title() {
		return __( 'FAQ', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get icon function
	 *
	 * @access public
	 */
	public function get_icon() {
		return 'eicon-help rael-badge';
	}

	/**
	 * Get categories function
	 *
	 * @access public
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Render content type list.
	 *
	 * @since 1.2.0
	 * @return array Array of content type
	 * @access public
	 */
	public function get_content_type() {
		$content_type = array(
			'content'              => __( 'Content', 'responsive-addons-for-elementor' ),
			'saved_rows'           => __( 'Saved Section', 'responsive-addons-for-elementor' ),
			'saved_page_templates' => __( 'Saved Page', 'responsive-addons-for-elementor' ),
		);

		if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			$content_type['saved_modules'] = __( 'Saved Widget', 'responsive-addons-for-elementor' );
		}

		return $content_type;
	}

	/**
	 *  Get Saved templates
	 *
	 *  @param string $type Type.
	 *  @since 1.2.0
	 *  @return array of templates
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
					'term'           => $type,
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
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'responsive-addons-for-elementor' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'rael_question',
			array(
				'label'       => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'What is FAQ?', 'responsive-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'rael_faq_content_type',
			array(
				'label'   => __( 'Content Type', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'content',
				'options' => $this->get_content_type(),
			)
		);

		$repeater->add_control(
			'rael_ct_saved_rows',
			array(
				'label'     => __( 'Select Section', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_saved_templates( 'section' ),
				'default'   => '-1',
				'condition' => array(
					'rael_faq_content_type' => 'saved_rows',
				),
			)
		);

		$repeater->add_control(
			'rael_ct_saved_modules',
			array(
				'label'     => __( 'Select Widget', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_saved_templates( 'widget' ),
				'default'   => '-1',
				'condition' => array(
					'rael_faq_content_type' => 'saved_modules',
				),
			)
		);

		$repeater->add_control(
			'rael_ct_page_templates',
			array(
				'label'     => __( 'Select Page', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_saved_templates( 'page' ),
				'default'   => '-1',
				'condition' => array(
					'rael_faq_content_type' => 'saved_page_templates',
				),
			)
		);

		$repeater->add_control(
			'rael_answer',
			array(
				'label'      => __( 'Content', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::WYSIWYG,
				'show_label' => true,
				'dynamic'    => array(
					'active' => true,
				),
				'default'    => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'responsive-addons-for-elementor' ),

				'condition'  => array(
					'rael_faq_content_type' => 'content',
				),
			)
		);

		$this->add_control(
			'rael_tabs',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'question' => __( 'What is FAQ?', 'responsive-addons-for-elementor' ),
						'answer'   => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'responsive-addons-for-elementor' ),
					),
					array(
						'question' => __( 'What is FAQ?', 'responsive-addons-for-elementor' ),
						'answer'   => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'responsive-addons-for-elementor' ),
					),
				),
				'title_field' => '{{{ rael_question }}}',
			)
		);

		$this->add_control(
			'rael_hr',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'rael_schema_support',
			array(
				'label'     => __( 'Enable Schema Support', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off' => __( 'No', 'responsive-addons-for-elementor' ),
				'default'   => 'no',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_faq_layout',
			array(
				'label'   => __( 'Layout', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'accordion' => __( 'Accordion', 'responsive-addons-for-elementor' ),
					'grid'      => __( 'Grid', 'responsive-addons-for-elementor' ),
				),
				'default' => 'accordion',
			)
		);

		$this->add_control(
			'rael_enable_toggle_layout',
			array(
				'label'     => __( 'Toggle', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Enable', 'responsive-addons-for-elementor' ),
				'label_off' => __( 'Disable', 'responsive-addons-for-elementor' ),
				'default'   => 'Disable',
				'condition' => array(
					'rael_faq_layout' => 'accordion',

				),
			)
		);

		$this->add_control(
			'rael_faq_layout_style',
			array(
				'label'        => __( 'Enable Box Style', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'no', 'responsive-addons-for-elementor' ),
				'default'      => 'yes',
				'condition'    => array(
					'rael_faq_layout' => 'accordion',
				),
				'prefix_class' => 'rael-faq-box-layout-',
			)
		);

		$this->add_responsive_control(
			'rael_row_gap',
			array(
				'label'     => __( 'Rows Gap', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 10,
				),
				'condition' => array(
					'rael_faq_layout_style' => 'yes',
					'rael_faq_layout'       => 'accordion',

				),
				'selectors' => array(
					'{{WRAPPER}} .rael-faq-container > .rael-faq-accordion:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'rael_columns',
			array(
				'label'           => __( 'Columns', 'responsive-addons-for-elementor' ),
				'type'            => Controls_Manager::SELECT,
				'desktop_default' => 2,
				'tablet_default'  => 2,
				'mobile_default'  => 1,
				'options'         => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'prefix_class'    => 'elementor-grid%s-',
				'condition'       => array(
					'rael_faq_layout' => 'grid',
				),
			)
		);

		$this->add_responsive_control(
			'rael_grid_column_gap',
			array(
				'label'     => __( 'Columns Gap', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 10,
				),
				'condition' => array(
					'rael_faq_layout' => 'grid',
					'rael_columns!'   => '1',
				),
				'selectors' => array(
					'{{WRAPPER}}:not(.elementor-grid-0) .elementor-grid' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-grid-0 .rael-faq-accordion' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2)',
					'{{WRAPPER}}.elementor-grid-0 .elementor-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
				),

			)
		);

		$this->add_responsive_control(
			'rael_grid_row_gap',
			array(
				'label'     => __( 'Rows Gap', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 10,
				),
				'condition' => array(
					'rael_faq_layout' => 'grid',
				),
				'selectors' => array(
					'{{WRAPPER}}:not(.elementor-grid-0) .elementor-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-grid-0 .rael-faq-accordion' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'(tablet) {{WRAPPER}}.elementor-grid-tablet-0 .elementor-share-btn' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'(mobile) {{WRAPPER}}.elementor-grid-mobile-0 .elementor-share-btn' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'rael_grid_align',
			array(
				'label'     => __( 'Alignment', 'responsive-addons-for-elementor' ),
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
				'default'   => '',
				'condition' => array(
					'rael_faq_layout' => 'grid',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-faq-accordion.elementor-grid-item' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_enable_seperator',
			array(
				'label'     => __( 'Enable Separator', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off' => __( 'No', 'responsive-addons-for-elementor' ),
				'default'   => 'no',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael__icon_content',
			array(
				'label'     => __( 'Icon', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_faq_layout!' => 'grid',
				),
			)
		);

		$this->add_control(
			'rael_selected_icon',
			array(
				'label'            => __( 'Icon', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'separator'        => 'before',
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-angle-right',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'rael_selected_active_icon',
			array(
				'label'     => __( 'Active Icon', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-angle-up',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'rael_selected_icon[value]!' => '',
				),
			)
		);

		$this->add_control(
			'rael_icon_align',
			array(
				'label'        => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'  => array(
						'title' => __( 'Start', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => __( 'End', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'      => is_rtl() ? 'right' : 'left',
				'toggle'       => false,
				'label_block'  => false,
				'render_type'  => 'template',
				'prefix_class' => 'align-at-',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => __( 'Box', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_faq_border_style',
			array(
				'label'       => __( 'Border Style', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'solid',
				'label_block' => false,
				'options'     => array(
					'none'   => __( 'None', 'responsive-addons-for-elementor' ),
					'solid'  => __( 'Solid', 'responsive-addons-for-elementor' ),
					'dashed' => __( 'Dashed', 'responsive-addons-for-elementor' ),
					'dotted' => __( 'Dotted', 'responsive-addons-for-elementor' ),
					'double' => __( 'Double', 'responsive-addons-for-elementor' ),
				),
				'selectors'   => array(
					'{{WRAPPER}} .rael-faq-container .rael-faq-accordion' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .rael-faq-box-layout-yes .rael-faq-container .rael-faq-accordion' => 'border-style: {{VALUE}}; ',
					'{{WRAPPER}} .rael-faq-container .rael-faq-accordion.elementor-grid-item' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .rael-faq-container:last-child' => 'border-bottom-style: {{VALUE}};',
					'{{WRAPPER}} .rael-faq-container.rael-faq-container:last-child' => 'border-bottom-style: {{VALUE}};',
					'{{WRAPPER}} .rael-faq-container.rael-faq-layout-grid:last-child' => 'border-bottom-style: none ;',
				),
				'condition'   => array(
					'rael_faq_layout_style!' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_border_width',
			array(
				'label'     => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'default'   => array(
					'top'      => 1,
					'right'    => 1,
					'bottom'   => 1,
					'left'     => 1,
					'isLinked' => true,
				),
				'selectors' => array(

					'{{WRAPPER}} .rael-faq-container .rael-faq-accordion' => 'border-width:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0px {{LEFT}}{{UNIT}} ;',
					'{{WRAPPER}} .rael-faq-container .rael-faq-accordion.elementor-grid-item' => 'border-width:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} ;',
					'{{WRAPPER}} .rael-faq-container:last-child' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} ;',
					'{{WRAPPER}}.rael-faq-layout-grid .rael-faq-container:last-child' => 'border-bottom: 0px;',
				),
				'condition' => array(
					'rael_faq_border_style!' => 'none',
					'rael_faq_layout_style!' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_border_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-faq-container .rael-faq-accordion' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .rael-faq-accordion .rael-accordion-content' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .rael-faq-container:last-child' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .rael-faq-accordion .rael-accordion-content' => 'border-top-color: {{VALUE}};',
				),
				'default'   => '#D4D4D4',
				'condition' => array(
					'rael_faq_border_style!' => 'none',
					'rael_faq_layout_style!' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_faq_box_border_style',
			array(
				'label'       => __( 'Border Style', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'solid',
				'label_block' => false,
				'options'     => array(
					'none'   => __( 'None', 'responsive-addons-for-elementor' ),
					'solid'  => __( 'Solid', 'responsive-addons-for-elementor' ),
					'dashed' => __( 'Dashed', 'responsive-addons-for-elementor' ),
					'dotted' => __( 'Dotted', 'responsive-addons-for-elementor' ),
					'double' => __( 'Double', 'responsive-addons-for-elementor' ),
				),
				'selectors'   => array(
					'{{WRAPPER}} .rael-faq-wrapper .rael-faq-container .rael-faq-accordion' => 'border-style: {{VALUE}};',
				),
				'condition'   => array(
					'rael_faq_layout_style' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_box_border_width',
			array(
				'label'     => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'default'   => array(
					'top'      => 1,
					'right'    => 1,
					'bottom'   => 1,
					'left'     => 1,
					'isLinked' => true,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-faq-wrapper .rael-faq-container .rael-faq-accordion' => 'border-width:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} ;',

				),
				'condition' => array(
					'rael_faq_box_border_style!' => 'none',
					'rael_faq_layout_style'      => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_box_border_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-faq-wrapper .rael-faq-container .rael-faq-accordion' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .rael-faq-container .rael-faq-accordion .rael-accordion-content' => 'border-top-color: {{VALUE}};',
				),
				'default'   => '#D4D4D4',
				'condition' => array(
					'rael_faq_box_border_style!' => 'none',
					'rael_faq_layout_style'      => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_border_radius',
			array(
				'label'     => __( 'Border radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'default'   => array(
					'top'      => 1,
					'right'    => 1,
					'bottom'   => 1,
					'left'     => 1,
					'isLinked' => true,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-faq-container .rael-faq-accordion' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition' => array(
					'rael_faq_box_border_style!' => 'none',
					'rael_faq_layout_style'      => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'box_layout_shadow',
				'label'     => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .rael-faq-accordion',
				'condition' => array(
					'rael_faq_border_style!' => 'none',
					'rael_faq_layout_style'  => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'box_normal_layout_shadow',
				'label'     => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .rael-faq-wrapper',
				'condition' => array(
					'rael_faq_border_style!' => 'none',
					'rael_faq_layout_style!' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_enable_separator_heading',
			array(
				'label'     => __( 'Separator', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_enable_seperator' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_faq_separator_style',
			array(
				'label'       => __( 'Style', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'solid',
				'label_block' => false,
				'options'     => array(
					'none'   => __( 'None', 'responsive-addons-for-elementor' ),
					'solid'  => __( 'Solid', 'responsive-addons-for-elementor' ),
					'dashed' => __( 'Dashed', 'responsive-addons-for-elementor' ),
					'dotted' => __( 'Dotted', 'responsive-addons-for-elementor' ),
					'double' => __( 'Double', 'responsive-addons-for-elementor' ),
				),
				'selectors'   => array(
					'{{WRAPPER}} .rael-faq-container .rael-faq-accordion .rael-accordion-content' => 'border-style: {{VALUE}};',
				),
				'condition'   => array(
					'rael_enable_seperator' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_separator_width',
			array(
				'label'     => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'default'   => array(
					'unit' => 'px',
					'size' => 1,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-faq-container .rael-faq-accordion .rael-accordion-content' => 'border-top-width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_enable_seperator'     => 'yes',
					'rael_faq_separator_style!' => 'none',

				),
			)
		);

		$this->add_control(
			'rael_separator_border_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-faq-container .rael-faq-accordion .rael-accordion-content' => 'border-top-color: {{VALUE}};',
				),
				'default'   => '#D4D4D4',
				'condition' => array(
					'rael_enable_seperator'     => 'yes',
					'rael_faq_separator_style!' => 'none',
				),

			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_title_style',
			array(
				'label' => __( 'Title', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_heading_tag',
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
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .rael-faq-accordion .rael-accordion-title .rael-question-span, {{WRAPPER}} .rael-faq-accordion .rael-accordion-title .rael-accordion-icon',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			)
		);

		$this->start_controls_tabs( 'rael_title_colors' );

		$this->start_controls_tab(
			'rael_colors_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_title_background',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-faq-accordion .rael-accordion-title' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_active_title_background',
			array(
				'label'     => __( 'Active Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-faq-accordion .rael-accordion-title.rael-title-active' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_faq_layout' => 'accordion',
				),
			)
		);

		$this->add_control(
			'rael_title_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-faq-accordion .rael-accordion-title .rael-question-span,
						{{WRAPPER}}  .rael-accordion-icon-closed, {{WRAPPER}} span.rael-accordion-icon-opened' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-accordion-icon-closed, {{WRAPPER}} span.rael-accordion-icon-opened' => 'fill: {{VALUE}};',
				),
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
			)
		);

		$this->add_control(
			'rael_title_active_color',
			array(
				'label'     => __( 'Active Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-faq-accordion .rael-accordion-title.rael-title-active .rael-question-span,
						{{WRAPPER}} span.rael-accordion-icon-opened' => 'color: {{VALUE}};',
				),
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'condition' => array(
					'rael_faq_layout' => 'accordion',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_colors_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_title_background_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-faq-accordion .rael-accordion-title:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_active_title_hover_background',
			array(
				'label'     => __( 'Active Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-faq-accordion .rael-accordion-title.rael-title-active:hover' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_faq_layout' => 'accordion',
				),
			)
		);

		$this->add_control(
			'rael_title_hover_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-faq-accordion .rael-accordion-title .rael-question-span:hover,
					{{WRAPPER}}  .rael-accordion-icon-closed:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-accordion-icon-closed:hover' => 'fill: {{VALUE}};',
				),
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
			)
		);

		$this->add_control(
			'rael_title_active_hover_color',
			array(
				'label'     => __( 'Active Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-faq-accordion .rael-accordion-title.rael-title-active:hover .rael-question-span,
					{{WRAPPER}} span.rael-accordion-icon-opened:hover' => 'color: {{VALUE}};',
				),
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'condition' => array(
					'rael_faq_layout' => 'accordion',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_title_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'      => 10,
					'right'    => 10,
					'bottom'   => 10,
					'left'     => 10,
					'isLinked' => true,
				),
				'separator'  => 'after',
				'selectors'  => array(
					'{{WRAPPER}} .rael-faq-accordion .rael-accordion-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_content_style',
			array(
				'label' => __( 'Content', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .rael-faq-accordion .rael-accordion-content',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			)
		);

		$this->start_controls_tabs( 'rael_content_colors' );

		$this->start_controls_tab(
			'rael_content_colors_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_content_background',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-faq-accordion .rael-accordion-content' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-faq-accordion.elementor-grid-item' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_content_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-faq-accordion .rael-accordion-content' => 'color: {{VALUE}};',
				),
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_content_colors_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_content_hover_background',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-faq-accordion .rael-accordion-content:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_content_hover_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-faq-accordion .rael-accordion-content:hover' => 'color: {{VALUE}};',
				),
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_content_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'      => 10,
					'right'    => 10,
					'bottom'   => 10,
					'left'     => 10,
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-faq-accordion .rael-accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_icon_style',
			array(
				'label'     => __( 'Icon', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'rael_faq_layout!' => 'grid' ),
			)
		);

		$this->add_responsive_control(
			'rael_title_icon_size',
			array(
				'label'     => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-faq-wrapper .rael-accordion-title .rael-accordion-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-faq-wrapper .rael-accordion-title .rael-accordion-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_icon_color',
			array(
				'label'     => __( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}  .rael-accordion-icon-closed' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-accordion-icon-closed' => 'fill: {{VALUE}};',
				),
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
			)
		);

		$this->add_control(
			'rael_icon_active_color',
			array(
				'label'     => __( 'Active Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} span.rael-accordion-icon-opened'  => 'color: {{VALUE}};',
				),
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
			)
		);

		$this->add_responsive_control(
			'rael_icon_space',
			array(
				'label'     => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'   => array(
					'unit' => 'px',
					'size' => 15,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-accordion-icon.rael-accordion-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-accordion-icon.rael-accordion-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.2.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings               = $this->get_settings_for_display();
		$editor_mode            = \Elementor\Plugin::instance()->editor->is_edit_mode();
		$id                     = substr( $this->get_id_int(), 0, 3 );
		$content_schema_warning = 0;

		foreach ( $settings['rael_tabs'] as $key ) {
			if ( 'content' !== $key['rael_faq_content_type'] ) {
				$content_schema_warning = 1;
			}
		}

		if ( ( 1 === $content_schema_warning ) && ( 'yes' === $settings['rael_schema_support'] ) && ( true === $editor_mode ) ) {
			?><span>
			<?php
			echo '<div class="elementor-alert elementor-alert-warning rael-warning">';
			echo esc_attr_e( 'The FAQ Schema is only supported in the case Content.', 'responsive-addons-for-elementor' );
			echo '</div>';
			?>
			</span>
			<?php
		}

		$this->add_render_attribute( 'rael-faq-container', 'class', 'rael-faq-container rael-faq-layout-' . $settings['rael_faq_layout'] );

		if ( 'grid' === $settings['rael_faq_layout'] ) {
			$this->add_render_attribute( 'rael-faq-container', 'class', 'elementor-grid' );
		} elseif ( 'accordion' === $settings['rael_faq_layout'] ) {
			if ( 'yes' === $settings['rael_enable_toggle_layout'] ) {
				$this->add_render_attribute( 'rael-faq-container', 'data-layout', 'toggle' );
			} else {
				$this->add_render_attribute( 'rael-faq-container', 'data-layout', 'accordion' );
			}
		}
		?>

		<div id='rael-faq-wrapper-<?php echo esc_attr( $id ); ?>' class="rael-faq-wrapper">
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael-faq-container' ) ); ?> >
				<?php

				foreach ( $settings['rael_tabs'] as $key ) {
					if ( ( '' === $key['rael_question'] || '' === $key['rael_answer'] ) && 'yes' === $settings['rael_schema_support'] && ( true === $editor_mode ) ) {
						?>
						<span>
									<?php
									echo '<div class="elementor-alert elementor-alert-warning rael-warning">';
									echo esc_attr_e( 'Please fill out the empty fields in content', 'responsive-addons-for-elementor' );
									echo '</div>';
									?>
								</span>
						<?php
					}
					if ( 'grid' === $settings['rael_faq_layout'] ) {
						$this->add_render_attribute(
							'rael_faq_accordion_' . $key['_id'],
							array(
								'id'    => 'rael-accordion-' . $key['_id'],
								'class' => array( 'rael-faq-accordion', 'elementor-grid-item' ),
							)
						);
					} else {
						$this->add_render_attribute(
							'rael_faq_accordion_' . $key['_id'],
							array(
								'id'    => 'rael-accordion-' . $key['_id'],
								'class' => 'rael-faq-accordion',
							)
						);
					}

					if ( ! ( '' === $key['rael_question'] || '' === $key['rael_answer'] ) ) {
						?>
					<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_faq_accordion_' . $key['_id'] ) ); ?> role="tablist">
						<div class= "rael-accordion-title" aria-expanded="false" role="tab">
										<span class="rael-accordion-icon rael-accordion-icon-<?php echo esc_attr( $settings['rael_icon_align'] ); ?>">
											<span class="rael-accordion-icon-closed"><?php Icons_Manager::render_icon( $settings['rael_selected_icon'] ); ?></span>
											<span class="rael-accordion-icon-opened"><?php Icons_Manager::render_icon( $settings['rael_selected_active_icon'] ); ?></span>
										</span>
							<<?php echo esc_html( Helper::validate_html_tags( $settings['rael_heading_tag'] ) ); ?> class="rael-question-<?php echo esc_attr( $key['_id'] ); ?> rael-question-span" tabindex="0" ><?php echo wp_kses_post( $key['rael_question'] ); ?></<?php echo esc_html( Helper::validate_html_tags( $settings['rael_heading_tag'] ) ); ?>>
					</div>
					<div class="rael-accordion-content" role="tabpanel">
										<span>

										<?php
										$content_type = $key['rael_faq_content_type'];
										$output       = '';
										switch ( $content_type ) {
											case 'content':
												$output = '<span>' . $key['rael_answer'] . '</span>';
												break;
											case 'saved_rows':
												$output = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( apply_filters( 'rael_wpml_object_id', $key['rael_ct_saved_rows'], 'page' ) );
												break;
											case 'saved_modules':
												$output = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $key['rael_ct_saved_modules'] );
												break;
											case 'saved_page_templates':
												$output = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $key['rael_ct_page_templates'] );
												break;
											default:
												$output = '';
										}
										echo wp_kses_post( $output );
										?>
										</span>
					</div>
				</div>
						<?php
					} else {
						$content_schema_warning = 1;
					}
				}
				?>
		</div>

		</div>
		<?php
	}

	/**
	 * Get Custom help URL
	 *
	 * @since 1.2.0
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/faq';
	}
}
