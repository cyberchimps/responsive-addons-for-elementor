<?php
/**
 * Skin Class
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Skins;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Class RAEL_Skin_Classic
 */
class RAEL_Skin_Classic extends RAEL_Skin_Base {

	/**
	 * Retrieve the skin ID.
	 *
	 * @since 1.0.0
	 *
	 * @return string The skin ID.
	 */
	public function get_id() {
		return 'rael_classic';
	}
	/**
	 * Retrieve the skin title.
	 *
	 * @since 1.0.0
	 *
	 * @return string The skin title.
	 */
	public function get_title() {
		return __( 'Classic', 'responsive-addons-for-elementor' );
	}
	/**
	 * Registers actions for additional design controls.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		parent::_register_controls_actions();
		add_action( 'elementor/element/rael-posts/rael_classic_section_design_layout/after_section_end', array( $this, 'register_additional_design_controls' ) );
	}
	/**
	 * Register additional design controls.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_additional_design_controls() {
		$this->start_controls_section(
			'section_design_box',
			array(
				'label' => __( 'Box', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'box_border_width',
			array(
				'label'      => __( 'Border Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-post' => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'box_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-post' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'box_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'content_padding',
			array(
				'label'      => __( 'Content Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-post__text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
				'separator'  => 'after',
			)
		);

		$this->start_controls_tabs( 'bg_effects_tabs' );

		$this->start_controls_tab(
			'classic_style_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .elementor-post',
			)
		);

		$this->add_control(
			'box_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-post' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'box_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-post' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'classic_style_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow_hover',
				'selector' => '{{WRAPPER}} .elementor-post:hover',
			)
		);

		$this->add_control(
			'box_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-post:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'box_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-post:hover' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}
}
