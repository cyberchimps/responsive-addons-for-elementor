<?php
/**
 * Posts Widget
 *
 * @since      1.0.0
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Skins;
use Elementor\Group_Control_Typography;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\QueryControl\Module as Module_Query;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\QueryControl\Controls\Group_Control_Related;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;


if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}
/**
 * Elementor 'Posts' widget.
 *
 * Elementor widget that displays an Posts.
 *
 * @since 1.0.0
 */
class Responsive_Addons_For_Elementor_Posts extends Widget_Base {


	/**
	 * This is for WP query.
	 *
	 * @var \WP_Query
	 */
	protected $query = null;
	/**
	 * It makes the variable has_template_content initially false.
	 *
	 * @var boolean
	 */
	protected $_has_template_content = false;
	/**
	 * Get the script dependencies required for this widget.
	 *
	 * @since 1.0.0
	 *
	 * @return array An array of script dependencies.
	 */
	public function get_script_depends() {
		return array( 'imagesloaded' );
	}

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-posts';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Posts', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Posts widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the posts widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}
	/**
	 * Get the current query.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_Query The current query.
	 */
	public function get_query() {
		return $this->query;
	}
	/**
	 * Callback function for importing settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $element The imported element.
	 *
	 * @return array Modified element settings.
	 */
	public function on_import( $element ) {
		if ( ! array_key_exists( 'posts_post_type', $element['settings'] ) || ! get_post_type_object( $element['settings']['posts_post_type'] ) ) {
			$element['settings']['posts_post_type'] = 'post';
		}

		return $element;
	}
	/**
	 * Register different skins for the widget.
	 *
	 * @since 1.0.0
	 */
	protected function register_skins() {
		$this->add_skin( new Skins\RAEL_Skin_Classic( $this ) );
		$this->add_skin( new Skins\RAEL_Skin_Cards( $this ) );
	}
	/**
	 * Get the WordPress page link for the given page number.
	 *
	 * @since 1.0.0
	 *
	 * @param int $i The page number.
	 *
	 * @return string The WordPress page link.
	 */
	private function get_wp_link_page( $i ) {
		if ( ! is_singular() || is_front_page() ) {
			return get_pagenum_link( $i );
		}

		// Based on wp-includes/post-template.php:957 `_wp_link_page`.
		global $wp_rewrite;
		$post       = get_post();
		$query_args = array();
		$url        = get_permalink();

		if ( $i > 1 ) {
			if ( '' == get_option( 'permalink_structure' ) || in_array( $post->post_status, array( 'draft', 'pending' ), true ) ) {
				$url = add_query_arg( 'page', $i, $url );
			} elseif ( get_option( 'show_on_front' ) == 'page' && (int) get_option( 'page_on_front' ) == $post->ID ) {
				$url = trailingslashit( $url ) . user_trailingslashit( "$wp_rewrite->pagination_base/" . $i, 'single_paged' );
			} else {
				$url = trailingslashit( $url ) . user_trailingslashit( $i, 'single_paged' );
			}
		}

		if ( is_preview() ) {
			if ( ( 'draft' != $post->post_status ) && isset( $_GET['preview_id'], $_GET['preview_nonce'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$query_args['preview_id']    = wp_unslash( $_GET['preview_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$query_args['preview_nonce'] = wp_unslash( $_GET['preview_nonce'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			}

			$url = get_preview_post_link( $post, $query_args, $url );
		}

		return $url;
	}

	/**
	 * Get the navigation links for the posts.
	 *
	 * @since 1.0.0
	 *
	 * @param int|null $page_limit The page limit.
	 *
	 * @return array An array of navigation links.
	 */
	public function get_posts_nav_link( $page_limit = null ) {
		if ( ! $page_limit ) {
			$page_limit = $this->query->max_num_pages;
		}

		$return = array();

		$paged = $this->get_current_page();

		$link_template     = '<a class="page-numbers %s" href="%s">%s</a>';
		$disabled_template = '<span class="page-numbers %s">%s</span>';

		if ( $paged > 1 ) {
			$next_page = intval( $paged ) - 1;
			if ( $next_page < 1 ) {
				$next_page = 1;
			}

			$return['prev'] = sprintf( $link_template, 'prev', $this->get_wp_link_page( $next_page ), $this->get_settings( 'pagination_prev_label' ) );
		} else {
			$return['prev'] = sprintf( $disabled_template, 'prev', $this->get_settings( 'pagination_prev_label' ) );
		}

		$next_page = intval( $paged ) + 1;

		if ( $next_page <= $page_limit ) {
			$return['next'] = sprintf( $link_template, 'next', $this->get_wp_link_page( $next_page ), $this->get_settings( 'pagination_next_label' ) );
		} else {
			$return['next'] = sprintf( $disabled_template, 'next', $this->get_settings( 'pagination_next_label' ) );
		}

		return $return;
	}
	/**
	 * Register controls for the pagination section.
	 *
	 * @since 1.0.0
	 */
	public function register_pagination_section_controls() {
		$this->start_controls_section(
			'section_pagination',
			array(
				'label' => __( 'Pagination', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'pagination_type',
			array(
				'label'   => __( 'Pagination', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''                      => __( 'None', 'responsive-addons-for-elementor' ),
					'numbers'               => __( 'Numbers', 'responsive-addons-for-elementor' ),
					'prev_next'             => __( 'Previous/Next', 'responsive-addons-for-elementor' ),
					'numbers_and_prev_next' => __( 'Numbers', 'responsive-addons-for-elementor' ) . ' + ' . __( 'Previous/Next', 'responsive-addons-for-elementor' ),
					'infinite'              => __( 'Infinite', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'pagination_infinite_button_label',
			array(
				'label'     => __( '"Load More" Label', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Load More', 'responsive-addons-for-elementor' ),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'pagination_type' => 'infinite',
				),
			)
		);

		$this->add_control(
			'pagination_page_limit',
			array(
				'label'     => __( 'Page Limit', 'responsive-addons-for-elementor' ),
				'default'   => '5',
				'condition' => array(
					'pagination_type' => array(
						'numbers',
						'prev_next',
						'numbers_and_prev_next',
					),
				),
			)
		);

		$this->add_control(
			'pagination_numbers_shorten',
			array(
				'label'     => __( 'Shorten', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'pagination_type' => array(
						'numbers',
						'numbers_and_prev_next',
					),
				),
			)
		);

		$this->add_control(
			'pagination_prev_label',
			array(
				'label'     => __( 'Previous Label', 'responsive-addons-for-elementor' ),
				'default'   => __( '&laquo; Previous', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'pagination_type' => array(
						'prev_next',
						'numbers_and_prev_next',
					),
				),
			)
		);

		$this->add_control(
			'pagination_next_label',
			array(
				'label'     => __( 'Next Label', 'responsive-addons-for-elementor' ),
				'default'   => __( 'Next &raquo;', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'pagination_type' => array(
						'prev_next',
						'numbers_and_prev_next',
					),
				),
			)
		);

		$this->add_control(
			'pagination_align',
			array(
				'label'     => __( 'Alignment', 'responsive-addons-for-elementor' ),
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
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'pagination_type!' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_pagination_style',
			array(
				'label'     => __( 'Pagination', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'pagination_type!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'pagination_typography',
				'selector'  => '{{WRAPPER}} .elementor-pagination',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'condition' => array(
					'pagination_type!' => 'infinite',
				),
			)
		);

		$this->add_control(
			'pagination_color_heading',
			array(
				'label'     => __( 'Colors', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'pagination_type!' => 'infinite',
				),
			)
		);

		$this->start_controls_tabs( 'pagination_colors' );

		$this->start_controls_tab(
			'pagination_color_normal',
			array(
				'label'     => __( 'Normal', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'pagination_type!' => 'infinite',
				),
			)
		);

		$this->add_control(
			'pagination_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination .page-numbers:not(.dots)' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'pagination_type!' => 'infinite',
				),
			)
		);

		$this->add_control(
			'pagination_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination .page-numbers:not(.dots)' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'pagination_type!' => 'infinite',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'pagination_border',
				'label'     => __( 'Border', 'responsive-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .elementor-pagination .page-numbers',
				'condition' => array(
					'pagination_type!' => 'infinite',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_color_hover',
			array(
				'label'     => __( 'Hover', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'pagination_type!' => 'infinite',
				),
			)
		);

		$this->add_control(
			'pagination_hover_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination a.page-numbers:hover' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'pagination_type!' => 'infinite',
				),
			)
		);
		$this->add_control(
			'pagination_background_hover_color',
			array(
				'label'     => __( 'Background Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination a.page-numbers:hover' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'pagination_type!' => 'infinite',
				),
			)
		);

		$this->add_control(
			'pagination_hover_border_color',
			array(
				'label'     => __( 'Border Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination a.page-numbers:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'pagination_type!' => 'infinite',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_color_active',
			array(
				'label'     => __( 'Active', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'pagination_type!' => 'infinite',
				),
			)
		);

		$this->add_control(
			'pagination_active_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination .page-numbers.current' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'pagination_type!' => 'infinite',
				),
			)
		);

		$this->add_control(
			'pagination_background_active_color',
			array(
				'label'     => __( 'Background Active Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination .page-numbers.current' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'pagination_type!' => 'infinite',
				),
			)
		);

		$this->add_control(
			'pagination_active_border_color',
			array(
				'label'     => __( 'Border Active Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination span.page-numbers.current' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'pagination_type!' => 'infinite',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'pagination_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .elementor-pagination .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'pagination_type!' => 'infinite',
				),
			)
		);

		$this->add_control(
			'pagination_box_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .elementor-pagination .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'pagination_type!' => 'infinite',
				),
			)
		);

		$this->start_controls_tabs( 'infinite_button_load_more_tabs_style' );

			$this->start_controls_tab(
				'infinite_btn_load_more_normal',
				array(
					'label'     => __( 'Normal', 'responsive-addons-for-elementor' ),
					'condition' => array(
						'pagination_type' => 'infinite',
					),
				)
			);

				$this->add_control(
					'infinite_btn_load_more_color',
					array(
						'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ffffff',
						'global'    => array(
							'default' => Global_Colors::COLOR_TEXT,
						),
						'selectors' => array(
							'{{WRAPPER}} .elementor-pagination .rael_pagination_load_more' => 'color: {{VALUE}};',
						),
						'condition' => array(
							'pagination_type' => 'infinite',
						),
					)
				);

				$this->add_control(
					'infinite_btn_load_more_background_color',
					array(
						'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} .elementor-pagination .rael_pagination_load_more' => 'background-color: {{VALUE}};',
						),
						'global'    => array(
							'default' => Global_Colors::COLOR_ACCENT,
						),
						'condition' => array(
							'pagination_type' => 'infinite',
						),
					)
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
					array(
						'name'      => 'infinite_btn_load_more_border',
						'label'     => __( 'Border', 'responsive-addons-for-elementor' ),
						'selector'  => '{{WRAPPER}} .elementor-pagination .rael_pagination_load_more',
						'condition' => array(
							'pagination_type' => 'infinite',
						),
					)
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'infinite_btn_load_more_hover',
				array(
					'label'     => __( 'Hover', 'responsive-addons-for-elementor' ),
					'condition' => array(
						'pagination_type' => 'infinite',
					),
				)
			);

				$this->add_control(
					'infinite_btn_load_more_hover_color',
					array(
						'label'     => __( 'Text Hover Color', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} .elementor-pagination .rael_pagination_load_more:hover' => 'color: {{VALUE}};',
						),
						'condition' => array(
							'pagination_type' => 'infinite',
						),
					)
				);

				$this->add_control(
					'infinite_btn_load_more_background_hover_color',
					array(
						'label'     => __( 'Background Hover Color', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} .elementor-pagination .rael_pagination_load_more:hover' => 'background-color: {{VALUE}};',
						),
						'condition' => array(
							'pagination_type' => 'infinite',
						),
					)
				);

				$this->add_control(
					'infinite_btn_load_more_hover_border_color',
					array(
						'label'     => __( 'Border Hover Color', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} .elementor-pagination .rael_pagination_load_more:hover' => 'border-color: {{VALUE}};',
						),
						'condition' => array(
							'pagination_type' => 'infinite',
						),
					)
				);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'infinite_btn_load_more_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-pagination .rael_pagination_load_more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'pagination_type' => 'infinite',
				),
			)
		);

		$this->add_responsive_control(
			'infinite_btn_load_more_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-pagination .rael_pagination_load_more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'    => 10,
					'bottom' => 10,
					'left'   => 10,
					'right'  => 10,
					'unit'   => 'px',
				),
				'condition'  => array(
					'pagination_type' => 'infinite',
				),
			)
		);

		$this->add_control(
			'infinite_btn_load_more_loader_color',
			array(
				'label'     => __( 'Loader Color', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-post-load-more-loader .responsive-post-load-more-loader-dot' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'pagination_type' => 'infinite',
				),
			)
		);

		$this->add_control(
			'infinite_btn_load_more_loader_size',
			array(
				'label'     => __( 'Loader Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
						'min' => 5,
					),
				),
				'default'   => array(
					'size' => 18,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-post-load-more-loader .responsive-post-load-more-loader-dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'pagination_type' => 'infinite',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'infinite_btn_load_more_typography',
				'selector'  => '.elementor-pagination .rael_pagination_load_more',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'condition' => array(
					'pagination_type' => 'infinite',
				),
			)
		);

		$this->add_responsive_control(
			'pagination_spacing',
			array(
				'label'     => __( 'Space Between', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'separator' => 'before',
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'min' => -50,
						'max' => 100,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .elementor-pagination .page-numbers:not(:first-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'body:not(.rtl) {{WRAPPER}} .elementor-pagination .page-numbers:not(:last-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .elementor-pagination .page-numbers:not(:first-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .elementor-pagination .page-numbers:not(:last-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
				),
				'condition' => array(
					'pagination_type!' => 'infinite',
				),
			)
		);

		$this->add_responsive_control(
			'pagination_spacing_top',
			array(
				'label'     => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 20,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-pagination' => 'margin-top: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register Filterable Tabs Controls.
	 *
	 * This method is responsible for adding Elementor controls related to Filterable Tabs
	 * in the Elementor widget settings panel. It includes controls for configuring the display
	 * of filter tabs, styling options, and more.
	 *
	 * @since 1.0.0
	 */
	public function register_filterable_tabs_controls() {

		$this->start_controls_section(
			'rael_section_filterable_tabs',
			array(
				'label' => __( 'Filterable Tabs', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_ft_show_filters',
			array(
				'label'   => __( 'Show Filters', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'yes'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'no'      => __( 'No', 'responsive-addons-for-elementor' ),
				'default' => 'no',
			)
		);

		$this->add_control(
			'rael_ft_tax_filter',
			array(
				'label'     => __( 'Filter By', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'category' => __( 'Categories', 'responsive-addons-for-elementor' ),
					'post_tag' => __( 'Tags', 'responsive-addons-for-elementor' ),
				),
				'default'   => 'category',
				'condition' => array(
					'rael_ft_show_filters' => 'yes',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_ft_filters_all_text',
			array(
				'label'     => __( '"All" Tab Label', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'All', 'responsive-addons-for-elementor' ),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'rael_ft_show_filters' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_ft_default_filter_switch',
			array(
				'label'        => __( 'Default Tab on Page Load', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'label_off'    => __( 'First', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Custom', 'responsive-addons-for-elementor' ),
				'condition'    => array(
					'rael_ft_show_filters' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_ft_default_filter',
			array(
				'label'     => __( 'Enter Category Name', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array(
					'rael_ft_show_filters'          => 'yes',
					'rael_ft_default_filter_switch' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_ft_tabs_dropdown',
			array(
				'label'        => __( 'Responsive Support', 'responsive-addons-for-elementor' ),
				'description'  => __( 'Enable this option to display Filterable Tabs in a Dropdown on Mobile.', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'condition'    => array(
					'rael_ft_show_filters' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Filterable Tabs Styling Starts.
		$this->start_controls_section(
			'section_filterable_tabs',
			array(
				'label'     => __( 'Filterable Tabs', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_ft_show_filters' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_ft_filter_alignment',
			array(
				'label'       => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
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
				'selectors'   => array(
					'{{WRAPPER}} .rael_post_filterable_tabs_wrapper .rael_post_filterable_tabs' => 'justify-content: {{VALUE}}',
					'{{WRAPPER}} .rael_post_filterable_tabs_wrapper_dropdown' => 'justify-content: {{VALUE}}',
				),
				'condition'   => array(
					'rael_ft_show_filters' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'rael_ft_filter_tabs_style' );

			// Normal Tab Starts.
			$this->start_controls_tab(
				'rael_ft_filter_normal',
				array(
					'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
				)
			);

				$this->add_control(
					'rael_ft_filter_color',
					array(
						'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} .rael_post_filterable_tabs_wrapper .rael_post_filterable_tabs .rael_post_filterable_tab' => 'color: {{VALUE}};',
							'{{WRAPPER}} .rael_post_filterable_tabs_wrapper_dropdown .rael_post_filterable_tabs_dropdown' => 'color: {{VALUE}};',
						),
						'condition' => array(
							'rael_ft_show_filters' => 'yes',
						),
					)
				);

				$this->add_control(
					'rael_ft_filter_background_color',
					array(
						'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} .rael_post_filterable_tabs_wrapper .rael_post_filterable_tabs .rael_post_filterable_tab' => 'background-color: {{VALUE}};',
							'{{WRAPPER}} .rael_post_filterable_tabs_wrapper_dropdown .rael_post_filterable_tabs_dropdown' => 'background-color: {{VALUE}};',
						),
						'default'   => '#f1f1f1',
						'condition' => array(
							'rael_ft_show_filters' => 'yes',
						),
					)
				);

				$this->add_control(
					'rael_ft_filter_border_style',
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
							'rael_ft_show_filters' => 'yes',
						),
						'selectors'   => array(
							'{{WRAPPER}} .rael_post_filterable_tabs_wrapper .rael_post_filterable_tabs .rael_post_filterable_tab' => 'border-style: {{VALUE}};',
							'{{WRAPPER}} .rael_post_filterable_tabs_wrapper_dropdown .rael_post_filterable_tabs_dropdown' => 'border-style: {{VALUE}};',
						),
					)
				);

				$this->add_control(
					'rael_ft_filter_border_size',
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
							'rael_ft_show_filters'        => 'yes',
							'rael_ft_filter_border_style' => array( 'solid', 'double', 'dotted', 'dashed' ),
						),
						'selectors'  => array(
							'{{WRAPPER}} .rael_post_filterable_tabs_wrapper .rael_post_filterable_tabs .rael_post_filterable_tab' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							'{{WRAPPER}} .rael_post_filterable_tabs_wrapper_dropdown .rael_post_filterable_tabs_dropdown' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					)
				);

				$this->add_control(
					'rael_ft_filter_border_color',
					array(
						'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'condition' => array(
							'rael_ft_show_filters'        => 'yes',
							'rael_ft_filter_border_style' => array( 'solid', 'double', 'dotted', 'dashed' ),
						),
						'default'   => '',
						'selectors' => array(
							'{{WRAPPER}} .rael_post_filterable_tabs_wrapper .rael_post_filterable_tabs .rael_post_filterable_tab' => 'border-color: {{VALUE}};',
							'{{WRAPPER}} .rael_post_filterable_tabs_wrapper_dropdown .rael_post_filterable_tabs_dropdown' => 'border-color: {{VALUE}};',
						),
					)
				);

			$this->end_controls_tab();
			// Normal Tab Ends.

			// Active Tab Starts.
			$this->start_controls_tab(
				'rael_ft_filter_active',
				array(
					'label'     => __( 'Active', 'responsive-addons-for-elementor' ),
					'condition' => array(
						'rael_ft_show_filters' => 'yes',
					),
				)
			);

				$this->add_control(
					'rael_ft_filter_active_color',
					array(
						'label'     => __( 'Text Active / Hover Color', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ffffff',
						'selectors' => array(
							'{{WRAPPER}} .rael_post_filterable_tabs_wrapper .rael_post_filterable_tabs .rael_post_filterable_tab.rael_post_active_filterable_tab' => 'color: {{VALUE}};',
							'{{WRAPPER}} .rael_post_filterable_tabs_wrapper .rael_post_filterable_tabs .rael_post_filterable_tab:hover' => 'color: {{VALUE}};',
						),
						'condition' => array(
							'rael_ft_show_filters' => 'yes',
						),
					)
				);

				$this->add_control(
					'filter_background_active_color',
					array(
						'label'     => __( 'Background Active / Hover Color', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} .rael_post_filterable_tabs_wrapper .rael_post_filterable_tabs .rael_post_filterable_tab.rael_post_active_filterable_tab' => 'background-color: {{VALUE}};',
							'{{WRAPPER}} .rael_post_filterable_tabs_wrapper .rael_post_filterable_tabs .rael_post_filterable_tab:hover' => 'background-color: {{VALUE}};',
						),
						'default'   => '#333333',
						'condition' => array(
							'rael_ft_show_filters' => 'yes',
						),
					)
				);

				$this->add_control(
					'filter_active_border_color',
					array(
						'label'     => __( 'Border Active / Hover Color', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} .rael_post_filterable_tabs_wrapper .rael_post_filterable_tabs .rael_post_filterable_tab.rael_post_active_filterable_tab' => 'border-color: {{VALUE}};',
							'{{WRAPPER}} .rael_post_filterable_tabs_wrapper .rael_post_filterable_tabs .rael_post_filterable_tab:hover' => 'border-color: {{VALUE}};',
						),
						'condition' => array(
							'rael_ft_show_filters' => 'yes',
						),
					)
				);

			$this->end_controls_tab();
			// Active Tab Ends.

		$this->end_controls_tabs();

		$this->add_control(
			'rael_ft_filter_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael_post_filterable_tabs_wrapper .rael_post_filterable_tabs .rael_post_filterable_tab' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rael_post_filterable_tabs_wrapper_dropdown .rael_post_filterable_tabs_dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_ft_show_filters' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_ft_filter_padding',
			array(
				'label'      => __( 'Filter Tab Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael_post_filterable_tabs_wrapper .rael_post_filterable_tabs .rael_post_filterable_tab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'    => 4,
					'bottom' => 4,
					'left'   => 14,
					'right'  => 14,
					'unit'   => 'px',
				),
				'condition'  => array(
					'rael_ft_show_filters' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_ft_filter_inner_padding',
			array(
				'label'     => __( 'Spacing Between Tabs', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 12,
					'unit' => 'px',
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael_post_filterable_tabs_wrapper .rael_post_filterable_tabs .rael_post_filterable_tab' => 'margin-right: {{SIZE}}{{UNIT}}; margin-bottom:{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael_post_filterable_tabs_wrapper .rael_post_filterable_tabs .rael_post_filterable_tab:last-child' => 'margin-right: 0;',
				),
				'condition' => array(
					'rael_ft_show_filters' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_ft_filter_bottom_padding',
			array(
				'label'     => __( 'Filter Bottom Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 5,
					'unit' => 'px',
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael_post_filterable_tabs_wrapper' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael_post_filterable_tabs_wrapper_dropdown' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_ft_show_filters' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_ft_filter_separator_width',
			array(
				'label'     => __( 'Filter Separator', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 0,
					'unit' => 'px',
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael_post_filterable_tabs_wrapper .rael_post_filterable_tabs' => 'border-bottom: {{SIZE}}{{UNIT}} solid #B7B7BF;',
				),
				'condition' => array(
					'rael_ft_show_filters' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_ft_filter_separator_color',
			array(
				'label'     => __( 'Filter Separator Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael_post_filterable_tabs_wrapper .rael_post_filterable_tabs' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_ft_show_filters' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_ft_filter_typography',
				'selector'  => '{{WRAPPER}} .rael_post_filterable_tabs_wrapper_dropdown .rael_post_filterable_tabs_dropdown,{{WRAPPER}} .rael_post_filterable_tabs_wrapper .rael_post_filterable_tabs .rael_post_filterable_tab',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'condition' => array(
					'rael_ft_show_filters' => 'yes',
				),
			)
		);

		$this->end_controls_section();

	}
	/**
	 * Register Controls for the Widget.
	 *
	 * This method is responsible for registering various controls related to the widget,
	 * including layout, query settings, pagination options, and filterable tabs configuration.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->end_controls_section();

		$this->register_query_section_controls();

		$this->register_pagination_section_controls();

		$this->register_filterable_tabs_controls();
	}
	/**
	 * Query posts based on widget settings.
	 *
	 * This method constructs a query to retrieve posts based on the widget's settings,
	 * including the number of posts per page, pagination, and optional taxonomies.
	 *
	 * @since 1.0.0
	 */
	public function query_posts() {

		$query_args = array(
			'posts_per_page' => $this->get_current_skin()->get_instance_value( 'posts_per_page' ),
			'paged'          => $this->get_current_page(),
		);

		if ( 'yes' === $this->get_settings( 'rael_ft_show_filters' ) && '' !== $this->get_settings( 'rael_ft_tax_filter' ) ) {
			if ( 'yes' === $this->get_settings( 'rael_ft_default_filter_switch' ) && '' !== $this->get_settings( 'rael_ft_default_filter' ) ) {
				$query_args['tax_query'] = array(
					array(
						'taxonomy' => $this->get_settings( 'rael_ft_tax_filter' ),
						'field'    => 'slug',
						'terms'    => $this->get_settings( 'rael_ft_default_filter' ),
					),
				);
			}
		}

		/**
		*
		* Query for Elementor module.
		*
		*  @var Module_Query $elementor_query
		*/
		$elementor_query = Module_Query::instance();
		$this->query     = $elementor_query->get_query( $this, 'rael-posts', $query_args, array() );
	}
	/**
	 * Get the current page number for pagination.
	 *
	 * This method determines the current page number based on the selected pagination type.
	 * If no pagination type is specified, it returns 1.
	 *
	 * @return int The current page number.
	 */
	public function get_current_page() {
		if ( '' == $this->get_settings( 'pagination_type' ) ) {
			return 1;
		}

		return max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
	}
	/**
	 * Register controls for the Query section.
	 *
	 * This method defines controls related to the query parameters for retrieving posts.
	 * It includes a group control for related settings and excludes the 'posts_per_page' control
	 * to avoid duplication with the one from the Layout section.
	 */
	protected function register_query_section_controls() {
		$this->start_controls_section(
			'rael_section_query',
			array(
				'label' => __( 'Query', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_group_control(
			Group_Control_Related::get_type(),
			array(
				'name'    => $this->get_name(),
				'presets' => array( 'full' ),
				'exclude' => array(
					'posts_per_page', // use the one from Layout section.
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/posts';
	}
}
