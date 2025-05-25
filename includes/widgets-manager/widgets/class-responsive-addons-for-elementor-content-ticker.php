<?php
/**
 * RAEL Content Ticker Widget
 *
 * @since 1.8.1
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use \Elementor\Widget_Base;
use \Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}
/**
 * RAEL Content Ticker widget Class.
 *
 * @since 1.8.1
 */
class Responsive_Addons_For_Elementor_Content_Ticker extends Widget_Base {

	use \Responsive_Addons_For_Elementor\Traits\RAEL_Template_Query;

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.8.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-content-ticker';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.8.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Content Ticker', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve RAEL Content Ticker widget icon.
	 *
	 * @since 1.8.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-progress-tracker rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the RAEL Content Ticker widget belongs to.
	 *
	 * @since 1.8.1
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
	 * Get widget Keywords.
	 *
	 * Retrieve the list of keywords for the RAEL Content Ticker widget.
	 *
	 * @since 1.8.1
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_keywords() {
		return array(
			'ticker',
			'rael ticker',
			'rael content ticker',
			'news headline',
			'news ticker',
			'text rotate',
			'text animation',
			'text swing',
			'text slide',
			'rael',
			'responsive elementor addons',
		);
	}

	/**
	 * Register all the control settings for the RAEL Content Ticker widget.
	 *
	 * @since 1.8.1
	 * @access public
	 */
	protected function register_controls() {
		/**
		 * Content Ticker Content Settings
		 */
		$this->start_controls_section(
			'rael_section_content_ticker_settings',
			array(
				'label' => esc_html__( 'Ticker Settings', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_dynamic_template_Layout',
			array(
				'label'   => esc_html__( 'Template Layout', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => $this->get_template_list_for_dropdown(),
			)
		);

		$ticker_options = apply_filters(
			'rael_ticker_options',
			array(
				'options'    => array(
					'dynamic' => esc_html__( 'Dynamic', 'responsive-addons-for-elementor' ),
					'custom'  => esc_html__( 'Custom', 'responsive-addons-for-elementor' ),
				),
				'conditions' => array(
					'custom',
				),
			)
		);

		$this->add_control(
			'rael_ticker_type',
			array(
				'label'       => esc_html__( 'Ticker Type', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'dynamic',
				'label_block' => false,
				'options'     => $ticker_options['options'],
			)
		);

		$this->add_control(
			'rael_ticker_tag_text',
			array(
				'label'       => esc_html__( 'Tag Text', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => false,
				'default'     => esc_html__( 'Trending Today', 'responsive-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();

		/**
		 * Variables for Dynamic Content settting
		 *
		 * @source includes/helper.php
		 */

		$post_types          = Helper::get_post_types();
		$post_types['by_id'] = __( 'Manual Selection', 'responsive-addons-for-elementor' );
		$taxonomies          = get_taxonomies( array(), 'objects' );

		/**
		 * Dynamic Content settting
		 */

		$this->start_controls_section(
			'rael_section_content_ticker_filters',
			array(
				'label'     => __( 'Dynamic Content Settings', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_ticker_type' => 'dynamic',
				),
			)
		);

		$this->add_control(
			'post_type',
			array(
				'label'   => __( 'Source', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $post_types,
				'default' => key( $post_types ),
			)
		);
		$this->add_control(
			'posts_ids',
			array(
				'label'       => __( 'Search & Select', 'responsive-addons-for-elementor' ),
				'type'        => 'rael-ajax-select2',
				'options'     => Helper::get_post_list(),
				'label_block' => true,
				'multiple'    => true,
				'source_name' => 'post_type',
				'source_type' => 'any',
				'condition'   => array(
					'post_type' => 'by_id',
				),
			)
		);

		$this->add_control(
			'authors',
			array(
				'label'       => __( 'Author', 'responsive-addons-for-elementor' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'default'     => array(),
				'options'     => Helper::get_authors_list(),
				'condition'   => array(
					'post_type!' => array( 'by_id', 'source_dynamic' ),
				),
			)
		);
		// adding controls for categories , tags , formats when posts are selected as source.
		// When products are selected as source then loop is adding product related controls.

		foreach ( $taxonomies as $taxonomy => $object ) {
			if ( ! isset( $object->object_type[0] ) || ! in_array( $object->object_type[0], array_keys( $post_types ), true ) ) {
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
						'post_type' => $object->object_type,
					),
				)
			);
		}

		$this->add_control(
			'post__not_in',
			array(
				'label'       => __( 'Exclude', 'responsive-addons-for-elementor' ),
				'type'        => 'rael-ajax-select2',
				'label_block' => true,
				'multiple'    => true,
				'source_name' => 'post_type',
				'source_type' => 'any',
				'condition'   => array(
					'post_type!' => array( 'by_id', 'source_dynamic' ),
				),
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => __( 'Posts Per Page', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '4',
				'min'     => '1',
			)
		);

		$this->add_control(
			'offset',
			array(
				'label'     => __( 'Offset', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '0',
				'condition' => array(
					'orderby!' => 'rand',
				),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => __( 'Order By', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => Helper::get_post_orderby_options(),
				'default' => 'date',

			)
		);

		$this->add_control(
			'order',
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

		/**
		 * Content Ticker Custom Content Settings
		 */
		$this->start_controls_section(
			'rael_section_ticker_custom_content_settings',
			array(
				'label'     => __( 'Custom Content Settings', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_ticker_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'rael_ticker_custom_contents',
			array(
				'type'        => Controls_Manager::REPEATER,
				'seperator'   => 'before',
				'default'     => array(
					array( 'rael_ticker_custom_content' => 'Ticker Custom Content' ),
				),
				'fields'      => array(
					array(
						'name'        => 'rael_ticker_custom_content',
						'label'       => esc_html__( 'Content', 'responsive-addons-for-elementor' ),
						'type'        => Controls_Manager::TEXT,
						'dynamic'     => array(
							'active' => true,
						),
						'label_block' => true,
						'default'     => esc_html__( 'Ticker custom content', 'responsive-addons-for-elementor' ),
					),
					array(
						'name'          => 'rael_ticker_custom_content_link',
						'label'         => esc_html__( 'Button Link', 'responsive-addons-for-elementor' ),
						'type'          => Controls_Manager::URL,
						'dynamic'       => array(
							'active' => true,
						),
						'label_block'   => true,
						'default'       => array(
							'url'         => '#',
							'is_external' => '',
						),
						'show_external' => true,
					),
				),
				'title_field' => '{{rael_ticker_custom_content}}',
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Carousel Settings
		 */
		$this->start_controls_section(
			'rael_section_additional_options',
			array(
				'label' => __( 'Animation Settings', 'responsive-addons-for-elementor' ),
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
					'slide' => __( 'Slide', 'responsive-addons-for-elementor' ),
					'fade'  => __( 'Fade', 'responsive-addons-for-elementor' ),
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
				'separator'   => 'before',
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
				'separator'    => 'before',
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
				'separator'    => 'before',
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
			'rael_direction',
			array(
				'label'     => __( 'Direction', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left'  => __( 'Left', 'responsive-addons-for-elementor' ),
					'right' => __( 'Right', 'responsive-addons-for-elementor' ),
				),
				'separator' => 'before',
				'condition' => array(
					'rael_carousel_effect' => 'slide',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Ticker Content Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'rael_section_ticker_typography_settings',
			array(
				'label' => esc_html__( 'Ticker Content', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_ticker_content_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-ticker-wrap .rael-ticker' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_ticker_content_color',
			array(
				'label'     => esc_html__( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#222222',
				'selectors' => array(
					'{{WRAPPER}} .rael-ticker-wrap .rael-ticker .ticker-content a' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_ticker_hover_content_color',
			array(
				'label'     => esc_html__( 'Text Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f44336',
				'selectors' => array(
					'{{WRAPPER}} .rael-ticker-wrap .rael-ticker .ticker-content a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_ticker_content_typography',
				'selector' => '{{WRAPPER}} .rael-ticker-wrap .rael-ticker .ticker-content a',

			)
		);

		$this->add_responsive_control(
			'rael_ticker_content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-ticker-wrap .rael-ticker .ticker-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_ticker_content_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-ticker-wrap .rael-ticker' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_ticker_tag_style_settings',
			array(
				'label' => esc_html__( 'Tag Style', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'rael_ticker_tag_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#222222',
				'selectors' => array(
					'{{WRAPPER}} .rael-ticker-wrap .ticker-badge' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_ticker_tag_color',
			array(
				'label'     => esc_html__( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-ticker-wrap .ticker-badge span' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_ticker_tag_typography',
				'selector' => '{{WRAPPER}} .rael-ticker-wrap .ticker-badge span',
			)
		);
		$this->add_responsive_control(
			'rael_ticker_tag_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-ticker-wrap .ticker-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_ticker_tag_margin',
			array(
				'label'      => esc_html__( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-ticker-wrap .ticker-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'rael_ticker_tag_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-ticker-wrap .ticker-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();

		/**
		 * Style Tab: Arrows
		 */
		$this->start_controls_section(
			'rael_section_arrows_style',
			array(
				'label'     => __( 'Arrows', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_arrows' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_prev_arrow',
			array(
				'label'       => __( 'Choose Prev Arrow', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => true,
				'default'     => array(
					'value'   => 'fas fa-angle-left',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'rael_arrow_new',
			array(
				'label'            => __( 'Choose Next Arrow', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'arrow',
				'label_block'      => true,
				'default'          => array(
					'value'   => 'fas fa-angle-right',
					'library' => 'fa-solid',
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
						'min'  => 5,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev'         => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next img, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'right: {{SIZE}}{{UNIT}};',
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
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_arrows_style' );

		$this->start_controls_tab(
			'rael_tab_arrows_normal',
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
			'rael_tab_arrows_hover',
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
	 * Render Function
	 *
	 * @since 1.8.1
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$settings = Helper::fix_old_query( $settings );
		$args     = Helper::get_query_args_ticker( $settings );

		$this->add_render_attribute( 'content-ticker-wrap', 'class', 'swiper-container-wrap rael-ticker' );

		$this->add_render_attribute( 'content-ticker', 'class', 'swiper' . RAEL_SWIPER_CONTAINER . ' rael-content-ticker' );
		$this->add_render_attribute( 'content-ticker', 'class', 'swiper-container-' . esc_attr( $this->get_id() ) );
		$this->add_render_attribute( 'content-ticker', 'data-pagination', '.swiper-pagination-' . esc_attr( $this->get_id() ) );
		$this->add_render_attribute( 'content-ticker', 'data-arrow-next', '.swiper-button-next-' . esc_attr( $this->get_id() ) );
		$this->add_render_attribute( 'content-ticker', 'data-arrow-prev', '.swiper-button-prev-' . esc_attr( $this->get_id() ) );

		if ( 'right' === $settings['rael_direction'] ) {
			$this->add_render_attribute( 'content-ticker', 'dir', 'rtl' );
		}

		if ( ! empty( $settings['margin_tablet']['size'] ) ) {
			$this->add_render_attribute( 'content-ticker', 'data-margin-tablet', $settings['margin_tablet']['size'] );
		}
		if ( ! empty( $settings['margin_mobile']['size'] ) ) {
			$this->add_render_attribute( 'content-ticker', 'data-margin-mobile', $settings['margin_mobile']['size'] );
		}
		if ( $settings['rael_carousel_effect'] ) {
			$this->add_render_attribute( 'content-ticker', 'data-effect', $settings['rael_carousel_effect'] );
		}
		if ( ! empty( $settings['rael_slider_speed']['size'] ) ) {
			$this->add_render_attribute( 'content-ticker', 'data-speed', $settings['rael_slider_speed']['size'] );
		}
		if ( 'yes' === $settings['rael_autoplay'] && ! empty( $settings['rael_autoplay_speed']['size'] ) ) {
			$this->add_render_attribute( 'content-ticker', 'data-autoplay', $settings['rael_autoplay_speed']['size'] );
		} else {
			$this->add_render_attribute( 'content-ticker', 'data-autoplay', '999999' );
		}
		if ( 'yes' === $settings['rael_pause_on_hover'] ) {
			$this->add_render_attribute( 'content-ticker', 'data-pause-on-hover', 'true' );
		}
		if ( 'yes' === $settings['rael_infinite_loop'] ) {
			$this->add_render_attribute( 'content-ticker', 'data-loop', true );
		}
		if ( 'yes' === $settings['rael_grab_cursor'] ) {
			$this->add_render_attribute( 'content-ticker', 'data-grab-cursor', true );
		}
		if ( 'yes' === $settings['rael_arrows'] ) {
			$this->add_render_attribute( 'content-ticker', 'data-arrows', '1' );
		}

		echo '<div class="rael-ticker-wrap" id="rael-ticker-wrap-' . esc_attr( $this->get_id() ) . '">';
		if ( ! empty( $settings['rael_ticker_tag_text'] ) ) {
			echo '<div class="ticker-badge">
                    <span>' . wp_kses_post( Helper::rael_wp_kses( $settings['rael_ticker_tag_text'] ) ) . '</span>
                </div>';
		}

		echo '<div ' . wp_kses_post( $this->get_render_attribute_string( 'content-ticker-wrap' ) ) . '>
                <div ' . wp_kses_post( $this->get_render_attribute_string( 'content-ticker' ) ) . '>
                    <div class="swiper-wrapper">';

		if ( 'dynamic' === $settings['rael_ticker_type'] ) {

			if ( \file_exists( $this->get_template( sanitize_file_name( $settings['rael_dynamic_template_Layout'] ) ) ) ) {
				$query = new \WP_Query( $args );
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						include $this->get_template( sanitize_file_name( $settings['rael_dynamic_template_Layout'] ) );
					}
					wp_reset_postdata();
				}
			} else {
				echo '<div class="swiper-slide"><a href="#" class="ticker-content">' . esc_html__( 'No content found!', 'responsive-addons-for-elementor' ) . '</a></div>';
			}
		} elseif ( 'custom' === $settings['rael_ticker_type'] ) {
			if ( \file_exists( $this->get_template( sanitize_file_name( $settings['rael_dynamic_template_Layout'] ) ) ) ) {
				foreach ( $settings['rael_ticker_custom_contents'] as $content ) {
					echo wp_kses_post(
						Helper::include_with_variable(
							$this->get_template( sanitize_file_name( $settings['rael_dynamic_template_Layout'] ) ),
							array(
								'content' => Helper::rael_wp_kses( $content['rael_ticker_custom_content'] ),
								'link'    => $content['rael_ticker_custom_content_link'],
							)
						)
					);
				}
			}
		}

					echo '</div>
				</div>
				' . wp_kses_post( $this->render_arrows() ) . '
			</div>
		</div>';
	}

	/**
	 * Render Content Ticker arrows output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.8.1
	 * @access public
	 */
	protected function render_arrows() {
		$settings = $this->get_settings_for_display();

		if ( 'yes' === $settings['rael_arrows'] ) {
			if ( isset( $settings['__fa4_migrated']['rael_arrow_new'] ) || empty( $settings['arrow'] ) ) {
				$arrow = $settings['rael_arrow_new']['value'];
			} else {
				$arrow = $settings['arrow'];
			}

			$html = '<div class="content-ticker-pagination">';

			$html .= '<div class="swiper-button-next swiper-button-next-' . $this->get_id() . '">';
			if ( isset( $arrow['url'] ) ) {
				$html .= '<img src="' . esc_url( $arrow['url'] ) . '" alt="' . esc_attr( get_post_meta( $arrow['id'], '_wp_attachment_image_alt', true ) ) . '" />';
			} else {
				$html .= '<i class="' . $arrow . '"></i>';
			}
			$html .= '</div>';

			$html .= '<div class="swiper-button-prev swiper-button-prev-' . $this->get_id() . '">';
			if ( isset( $settings['rael_prev_arrow']['value']['url'] ) ) {
				$html .= '<img src="' . esc_url( $settings['rael_prev_arrow']['value']['url'] ) . '" alt="' . esc_attr( get_post_meta( $settings['rael_prev_arrow']['value']['id'], '_wp_attachment_image_alt', true ) ) . '" />';
			} else {
				$html .= '<i class="' . esc_attr( $settings['rael_prev_arrow']['value'] ) . '"></i>';
			}
			$html .= '</div>';

			$html .= '</div>';

			return $html;
		}
	}

}
