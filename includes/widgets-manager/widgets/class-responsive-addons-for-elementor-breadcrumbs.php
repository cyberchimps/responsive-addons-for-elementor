<?php
/**
 * Breadcrumbs Widget
 *
 * @since      1.4.0
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Responsive_Addons_For_Elementor\Traits\Missing_Dependency;
use WPSEO_Breadcrumbs;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Breadcrumbs widget class.
 */
class Responsive_Addons_For_Elementor_Breadcrumbs extends Widget_Base {
	use Missing_Dependency;

	/**
	 * Get name function
	 *
	 * @access public
	 */
	public function get_name() {
		return 'rael-breadcrumbs';
	}

	/**
	 * Get title function
	 *
	 * @access public
	 */
	public function get_title() {
		return __( 'Breadcrumbs', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get icon function
	 *
	 * @access public
	 */
	public function get_icon() {
		return 'eicon-yoast rael-badge';
	}

	/**
	 * Get script depends function
	 *
	 * @access public
	 */
	public function get_script_depends() {
		return array( 'breadcrumbs' );
	}

	/**
	 * Get keywords function
	 *
	 * @access public
	 */
	public function get_keywords() {
		return array( 'rael', 'yoast', 'seo', 'breadcrumbs', 'internal links' );
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the RAEL Breadcrumbs widget belongs to.
	 *
	 * @since 2.0.5
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
	 * @since 1.4.0
	 *
	 * @since 1.5.0 Added a condition to display a warning message in the editor when the dependency plugin is not activated.
	 *
	 * @access protected
	 *
	 * @return void
	 */
	protected function register_controls() {
		if ( ! class_exists( '\WPSEO_Breadcrumbs' ) ) {
			$this->register_content_tab_missing_dep_warning_controls( 'Yoast SEO', 'yoast seo' );
			return;
		}

		$this->start_controls_section(
			'rael_section_breadcrumbs_content',
			array(
				'label' => __( 'Breadcrumbs', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_responsive_control(
			'rael_align',
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
				'prefix_class' => 'elementor%s-align-',
			)
		);

		$this->add_control(
			'rael_html_tag',
			array(
				'label'   => __( 'HTML Tag', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''     => __( 'Default', 'responsive-addons-for-elementor' ),
					'p'    => 'p',
					'div'  => 'div',
					'nav'  => 'nav',
					'span' => 'span',
				),
				'default' => '',
			)
		);

		$this->add_control(
			'rael_html_description',
			array(
				'raw'             => __( 'Additional settings are available in the Yoast SEO', 'responsive-addons-for-elementor' ) . ' ' . sprintf( '<a href="%s" target="_blank">%s</a>', admin_url( 'admin.php?page=wpseo_titles#top#breadcrumbs' ), __( 'Breadcrumbs Panel', 'responsive-addons-for-elementor' ) ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_style',
			array(
				'label' => __( 'Breadcrumbs', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_breadcrumb_typography',
				'selector' => '{{WRAPPER}}',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
			)
		);

		$this->add_control(
			'rael_breadcrumb_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => 'color: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'rael_tabs_breadcrumbs_style' );

		$this->start_controls_tab(
			'rael_breadcrumb_tab_color_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_breadcrumb_link_color',
			array(
				'label'     => __( 'Link Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_breadcrumb_tab_color_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_breadcrumb_link_hover_color',
			array(
				'label'     => __( 'Link Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get html tag function
	 *
	 * @access private
	 */
	private function get_html_tag() {
		$html_tag = Helper::validate_html_tags( $this->get_settings( 'rael_html_tag' ) );

		if ( empty( $html_tag ) ) {
			$html_tag = 'p';
		}

		return Utils::validate_html_tag( $html_tag );
	}

	/**
	 * Render function
	 *
	 * @access protected
	 */
	protected function render() {
		if ( class_exists( '\WPSEO_Breadcrumbs' ) ) {
			$html_tag = $this->get_html_tag();
			WPSEO_Breadcrumbs::breadcrumb( '<' . $html_tag . ' id="breadcrumbs">', '</' . $html_tag . '>' );
		}

	}
}
