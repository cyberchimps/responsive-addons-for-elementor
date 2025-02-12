<?php
/**
 * RAEL Minimal Skin.
 *
 * @since    1.2.2
 * @package  Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Skins\Product_Category_Grid;

use Elementor\Controls_Manager;
use Elementor\Skin_Base;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * RAEL Skin Minimal class.
 *
 * @since 1.2.2
 */
class RAEL_Skin_Minimal extends Skin_Base {
	/**
	 * Retrieve the skin id.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return string Skin ID.
	 */
	public function get_id() {
		return 'rael_minimal';
	}

	/**
	 * Retrieve the skin title.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return string Skin title(escaped).
	 */
	public function get_title() {
		return __( 'Minimal', 'responsive-addons-for-elementor' );
	}

	/**
	 * Add hooks related to the skin.
	 *
	 * @since 1.2.2
	 * @access protected
	 *
	 * @return void
	 */
	protected function _register_controls_actions() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		add_action( 'elementor/element/rael-product-category-grid/rael_pcg_section_image_style/after_section_end', array( $this, 'register_pcg_content_style_controls' ) );
	}

	/**
	 * Registered hook callback.
	 *
	 * @param Widget_Base $widget Widget on which the hook is triggered.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return void
	 */
	public function register_pcg_content_style_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_style_content_controls();
	}

	/**
	 * Register Content style controls under Content Tab.
	 *
	 * @since 1.2.2
	 * @access public
	 */
	public function register_style_content_controls() {
		$this->start_controls_section(
			'rael_pcg_section_content_style',
			array(
				'label' => __( 'Content', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_content_alignment',
			array(
				'label'        => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'default'      => 'left',
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
				'prefix_class' => 'rael-product-category-grid--align-',
			)
		);

		$this->add_responsive_control(
			'rael_content_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-product-category-grid__content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_content_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-product-category-grid__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'label'    => __( 'Background Type', 'responsive-addons-for-elementor' ),
				'name'     => 'rael_content_background',
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .rael-product-category-grid__content',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'label'    => __( 'Border Type', 'responsive-addons-for-elementor' ),
				'name'     => 'rael_content_border',
				'selector' => '{{WRAPPER}} .rael-product-category-grid__content',
			)
		);

		$this->add_responsive_control(
			'rael_content_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-product-category-grid__content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_content_title_heading',
			array(
				'label'     => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'name'     => 'rael_content_title_typography',
				'selector' => '{{WRAPPER}} .rael-product-category-grid__content-title a',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			)
		);

		$this->add_control(
			'rael_content_title_color_normal',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-product-category-grid__content-title a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_content_title_color_hover',
			array(
				'label'     => __( 'Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-product-category-grid__content-title a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_content_count_heading',
			array(
				'label'     => __( 'Count', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_show_product_count' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_content_count_spacing',
			array(
				'label'      => __( 'Top Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-product-category-grid__product-count' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_show_product_count' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'     => __( 'Typography', 'responsive-addons-for-elementor' ),
				'name'      => 'rael_content_count_typography',
				'selector'  => '{{WRAPPER}} .rael-product-category-grid__product-count',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'condition' => array(
					'rael_show_product_count' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_content_count_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-product-category-grid__product-count' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_show_product_count' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Skin render method.
	 *
	 * This implementation uses the render() of the widget.
	 *
	 * @since 1.2.2
	 * @access public
	 */
	public function render() {
		$this->parent->render();
	}

}
