<?php
/**
 * RAE Theme Post Content
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Skins\RAEL_Skin_Content_Base;

/**
 * Widget class for the Post_Content in the Theme Builder.
 *
 * @package Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder
 */
class Responsive_Addons_For_Elementor_Theme_Post_Content extends Widget_Base {
	use RAEL_Skin_Content_Base;

	/**
	 * Get the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-theme-post-content';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Post Content', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-content rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'rael', 'rael-theme-builder', 'post-content', 'content', 'post' );
	}

	/**
	 * Get widget doc url.
	 *
	 * @return array Widget doc url.
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/post-content';
	}

	/**
	 * Register controls for Post content widget
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'rael_theme_pc_style_section',
			array(
				'label' => __( 'Style', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_theme_pc_align',
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
						'title' => __( 'Justify', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_theme_pc_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => 'color: {{VALUE}};',
				),
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'   => 'rael_theme_pc_typography',
				'global' => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render function for the widget
	 *
	 * @access public
	 */
	protected function render() {
		$this->render_post_content();
	}

	/**
	 * Render plain content function for the widget
	 *
	 * @access public
	 */
	public function render_plain_content() {}

	/**
	 * The following code is a temporary solution that should be removed after Core 3.4.0:
	 * $content_removed_filters, remove_content_filters(), restore_content_filters().
	 *
	 * @var array $content_removed_filters
	 */
	private $content_removed_filters = array();

	/**
	 * Removes specified content filters from 'the_content' hook.
	 */
	private function remove_content_filters() {
		$filters = array(
			'wpautop',
			'shortcode_unautop',
			'wptexturize',
		);

		foreach ( $filters as $filter ) {
			// Check if another plugin/theme do not already removed the filter.
			if ( has_filter( 'the_content', $filter ) ) {
				remove_filter( 'the_content', $filter );
				$this->content_removed_filters[] = $filter;
			}
		}
	}

	/**
	 * Restores previously removed content filters to 'the_content' hook.
	 */
	private function restore_content_filters() {
		foreach ( $this->content_removed_filters as $filter ) {
			add_filter( 'the_content', $filter );
		}

		$this->content_removed_filters = array();
	}
}
