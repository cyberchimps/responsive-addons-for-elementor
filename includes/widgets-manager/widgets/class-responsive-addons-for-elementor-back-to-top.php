<?php
/**
 * RAEL Back to top Widget
 *
 * @since      1.8.1
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RAEL 'Back to Top' widget class.
 *
 * @since 1.8.1
 */
class Responsive_Addons_For_Elementor_Back_To_Top extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.8.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-back-to-top';
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
		return esc_html__( 'Back to Top', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Banner widget icon.
	 *
	 * @since 1.8.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return ' eicon-arrow-up rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories.
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
	 * Register all the control settings
	 *
	 * @since 1.8.1
	 * @access public
	 */
	public function register_controls() {
		/*
		Content Tab section
		---------------------
		-> Select appearane of back to top button
		-> Icon control
		-> Text control
		-> Aligment
		*/

		$this->start_controls_section(
			'rael_back_to_top_content_section',
			array(
				'label' => esc_html__( 'Layout and Content', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_button_appearance',
			array(
				'label'   => esc_html__( 'Appearance', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon_only',
				'options' => array(
					'icon_only'          => esc_html__( 'Icon Only', 'responsive-addons-for-elementor' ),
					'text_only'          => esc_html__( 'Text Only', 'responsive-addons-for-elementor' ),
					'progress_indicator' => esc_html__( 'Progress Indicator', 'responsive-addons-for-elementor' ),
				),
			)
		);

		// back to top icon show when user select icon only appearance.
		$this->add_control(
			'rael_btn_icons',
			array(
				'label'     => esc_html__( 'Icon', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-arrow-up',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'rael_button_appearance' => array( 'icon_only', 'progress_indicator' ),
				),
			)
		);

		// back to top text input control when user select text only appearance.
		$this->add_control(
			'rael_btn_text',
			array(
				'label'       => esc_html__( 'Button Text', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Top', 'responsive-addons-for-elementor' ),
				'placeholder' => esc_html__( 'Type button label here', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_button_appearance' => 'text_only',
				),
			)
		);

		$this->add_responsive_control(
			'rael_button_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
				'options'   => array(
					'left'   => array(
						'description' => esc_html__( 'Left', 'responsive-addons-for-elementor' ),
						'icon'        => 'eicon-text-align-left',
					),
					'center' => array(
						'description' => esc_html__( 'Center', 'responsive-addons-for-elementor' ),
						'icon'        => 'eicon-text-align-center',
					),
					'right'  => array(
						'description' => esc_html__( 'Right', 'responsive-addons-for-elementor' ),
						'icon'        => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-btt' => 'text-align: {{VALUE}};',
				),
			)
		);
		$this->end_controls_section(); // end of content tab section.

		/*
		---------------------
			Settings Tab
			-> Scroll Top Offset
		------------------------
		*/
		$this->start_controls_section(
			'rael_back_to_top_setting_section',
			array(
				'label' => esc_html__( 'Setting', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_offset_top',
			array(
				'label'   => esc_html__( 'Offset Top (px)', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'step'    => 1,
				'default' => 0,
			)
		);

		$this->add_control(
			'rael_show_button_after_switch',
			array(
				'label'     => esc_html__( 'Show button on scroll', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off' => esc_html__( 'No', 'responsive-addons-for-elementor' ),
				'default'   => 'no',
			)
		);

		$this->add_control(
			'rael_show_button_after',
			array(
				'label'     => esc_html__( 'Enter scrolled value (px)', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'step'      => 1,
				'default'   => 400,
				'condition' => array(
					'rael_show_button_after_switch' => 'yes',
				),
			)
		);

		$this->end_controls_section(); // end of content tab section.

		/*
		-------------------------
			back to top style tab
			-> Typogaphy
			-> Width
			-> Height
			-> Border radius
			-> Border control
				-> Stroke foreground and backgorund color
				-> Size of button (width and height together)
		----------------------------
		*/
			$this->start_controls_section(
				'rael_back_to_top_style_section',
				array(
					'label' => esc_html__( 'Button Style', 'responsive-addons-for-elementor' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'           => 'rael_btn_typography',
					'label'          => esc_html__( 'Typography', 'responsive-addons-for-elementor' ),
					'selector'       => '{{WRAPPER}} .rael-btt__button',
					'exclude'        => array( 'letter_spacing', 'font_style', 'text_decoration', 'line_height' ),
					'fields_options' => array(
						'typography'     => array(
							'default' => 'custom',
						),
						'font_weight'    => array(
							'default' => '400',
						),
						'font_size'      => array(
							'default'    => array(
								'size' => '16',
								'unit' => 'px',
							),
							'size_units' => array( 'px' ),
						),
						'text_transform' => array(
							'default' => 'uppercase',
						),
					),
				)
			);

			$this->add_control(
				'rael_button_size',
				array(
					'label'      => esc_html__( 'Button Size (px)', 'responsive-addons-for-elementor' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min'  => 0,
							'max'  => 200,
							'step' => 1,
						),
					),
					'default'    => array(
						'unit' => 'px',
						'size' => 50,
					),
					'selectors'  => array(
						'{{WRAPPER}} .rael-btt__button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					),
					'condition'  => array(
						'rael_button_appearance' => 'progress_indicator',
					),
				)
			);

		$this->add_control(
			'rael_button_width',
			array(
				'label'      => esc_html__( 'Width (px)', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-btt__button' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_button_appearance!' => 'progress_indicator',
				),
			)
		);

		$this->add_control(
			'rael_button_height',
			array(
				'label'      => esc_html__( 'Height (px)', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-btt__button' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_button_appearance!' => 'progress_indicator',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'rael_button_border',
				'label'     => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .rael-btt__button',
				'exclude'   => array( 'border_color' ),
				'condition' => array(
					'rael_button_appearance!' => 'progress_indicator',
				),
			)
		);

		$this->add_responsive_control(
			'rael_button_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit'     => 'px',
					'top'      => 50,
					'right'    => 50,
					'bottom'   => 50,
					'left'     => 50,
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} :is( .rael-btt__button, #canvas )' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_button_appearance!' => 'progress_indicator',
				),
			)
		);

		$this->add_control(
			'rael_button_prgoress_foreground',
			array(
				'label'     => esc_html__( 'Line Foreground color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#FF5050',
				'condition' => array(
					'rael_button_appearance' => 'progress_indicator',
				),
			)
		);

		$this->add_control(
			'rael_button_prgoress_background',
			array(
				'label'     => esc_html__( 'Line Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#eee',
				'condition' => array(
					'rael_button_appearance' => 'progress_indicator',
				),
			)
		);

		$this->start_controls_tabs( 'rael_button_tabs' );

		$this->start_controls_tab(
			'rael_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_button_normal_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .rael-btt__button' => 'color: {{VALUE}}; border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_button_normal_bg_color',
			array(
				'label'     => esc_html__( 'Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-btt__button' => 'background: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_button_hover_clr',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .rael-btt__button:hover' => 'color: {{VALUE}}; border-color: {{VALUE}}',
					'{{WRAPPER}} .rael-btt__button:focus' => 'color: {{VALUE}}; border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_button_hover_bg_clr',
			array(
				'label'     => esc_html__( 'Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-btt__button:hover' => 'background: {{VALUE}}',
					'{{WRAPPER}} .rael-btt__button:focus' => 'background: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section(); // end of back to style tab.
	}

	/**
	 * Register render function.
	 *
	 * @since 1.8.1
	 * @access public
	 */
	protected function render() {
		echo '<div class="rael-wid-con" >';
		$this->render_raw();
		echo '</div>';
	}

	/**
	 * Render raw function.
	 *
	 * @since 1.8.1
	 * @access protected
	 */
	protected function render_raw() {
		$settings   = $this->get_settings_for_display();
		$appearance = $settings['rael_button_appearance'];
		$is_scroll  = 'yes' === $settings['rael_show_button_after_switch'] ? 'yes' : '';

		$args = array(
			'offset_top'  => $settings['rael_offset_top'],
			'show_after'  => $settings['rael_show_button_after'],
			'show_scroll' => $is_scroll,
			'style'       => $appearance,
			'fg'          => $settings['rael_button_prgoress_foreground'],
			'bg'          => $settings['rael_button_prgoress_background'],
		)

		?> <div class="rael-back-to-top-container rael-btt <?php echo esc_attr( $appearance ); ?>"
				data-settings="<?php echo esc_attr( wp_json_encode( $args ) ); ?>">
				<span class="rael-btt__button <?php echo esc_attr( $is_scroll ); ?>">
						<?php
							// start container.
						switch ( $appearance ) {
							// show icon style by default.
							case 'icon_only':
								Icons_Manager::render_icon( $settings['rael_btn_icons'], array( 'aria-hidden' => 'true' ) );
								break;

							// show text only style.
							case 'text_only':
								echo esc_html( $settings['rael_btn_text'] );
								break;

							// show progress indicator style (pro feature).
							case 'progress_indicator':
								?>
										<div class="progress_indicator">
											<canvas id="canvas"> </canvas>
												<span><?php Icons_Manager::render_icon( $settings['rael_btn_icons'], array( 'aria-hidden' => 'true' ) ); ?></span>
										</div>
									<?php
								break;
						}
						?>
							</span> </div>
						<?php
						// end container.
	}


}
