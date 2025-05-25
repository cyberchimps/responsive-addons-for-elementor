<?php
/**
 * RAE Theme Post Excerpt
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Skins\RAEL_Skin_Content_Base;

/**
 * RAEL Theme Post Excerpt widget class.
 */
class Responsive_Addons_For_Elementor_Theme_Post_Excerpt extends Widget_Base {
	use RAEL_Skin_Content_Base;

	/**
	 * Get the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		// `theme` prefix is to avoid conflicts with a dynamic-tag with same name.
		return 'rael-theme-post-excerpt';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Post Excerpt', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-excerpt rael-badge';
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
		return array( 'post', 'excerpt', 'description' );
	}

	/**
	 * Get custom help url.
	 *
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/post-excerpt';
	}

	/**
	 * Register 'Post Excerpt' widget controls.
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'rael_excerpt_section_content',
			array(
				'label' => __( 'Content', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_excerpt',
			array(
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => array(
					'active'  => true,
					'default' => Plugin::instance()->dynamic_tags->tag_data_to_tag_text( null, 'rael-post-excerpt' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_excerpt_section_style',
			array(
				'label' => __( 'Style', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_excerpt_align',
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
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_excerpt_title_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}}' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_excerpt_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}}',
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
		echo wp_kses_post( $this->get_settings_for_display( 'rael_excerpt' ) );
	}
}
