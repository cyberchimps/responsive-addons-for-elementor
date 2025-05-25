<?php
/**
 * Business Hour Widget
 *
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.

}

/**
 * Elementor 'Business Hour' widget class.
 */
class Responsive_Addons_For_Elementor_Business_Hour extends Widget_Base {

	/**
	 * Get name function
	 *
	 * @access public
	 */
	public function get_name() {
		return 'rael-business-hour';
	}

	/**
	 * Get title function
	 *
	 * @access public
	 */
	public function get_title() {
		return __( 'Business Hour', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get icon function
	 *
	 * @access public
	 */
	public function get_icon() {
		return 'eicon-clock-o rael-badge';
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
	 * Get keywords function
	 *
	 * @access public
	 */
	public function get_keywords() {
		return array( 'watch', 'business', 'hour', 'time', 'business-hour' );
	}

	/**
	 * Register controls function
	 *
	 * @access protected
	 */
	protected function register_controls() {
		do_action( 'rael_start_register_controls', $this );

		$this->start_controls_section(
			'section_business_hour',
			array(
				'label' => __( 'Business Hour', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_title',
			array(
				'label'       => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Working Hour', 'responsive-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'rael_day',
			array(
				'label'       => __( 'Day', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Monday', 'responsive-addons-for-elementor' ),
				'placeholder' => __( 'Monday', 'responsive-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'rael_time',
			array(
				'label'       => __( 'Time', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( '10:00AM - 07:00PM', 'responsive-addons-for-elementor' ),
				'placeholder' => __( '10:00AM - 07:00PM', 'responsive-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'rael_individual_style',
			array(
				'label'          => __( 'Individual Style?', 'responsive-addons-for-elementor' ),
				'type'           => Controls_Manager::SWITCHER,
				'label_on'       => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'      => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value'   => 'yes',
				'default'        => 'no',
				'style_transfer' => true,
			)
		);

		$repeater->add_control(
			'rael_day_time_color',
			array(
				'label'          => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'           => Controls_Manager::COLOR,
				'selectors'      => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.rael-business-hour-item' => 'color: {{VALUE}};',
				),
				'condition'      => array(
					'rael_individual_style' => 'yes',
				),
				'separator'      => 'before',
				'style_transfer' => true,
			)
		);

		$repeater->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'rael_day_time_border',
				'label'          => __( 'Border', 'responsive-addons-for-elementor' ),
				'selector'       => '{{WRAPPER}} {{CURRENT_ITEM}}.rael-business-hour-item',
				'style_transfer' => true,
				'condition'      => array(
					'rael_individual_style' => 'yes',
				),
			)
		);

		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'rael_day_time_background',
				'label'          => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'selector'       => '{{WRAPPER}} {{CURRENT_ITEM}}.rael-business-hour-item',
				'condition'      => array(
					'rael_individual_style' => 'yes',
				),
				'separator'      => 'before',
				'style_transfer' => true,
			)
		);

		$repeater->add_control(
			'rael_day_time_border_radius',
			array(
				'label'          => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'           => Controls_Manager::DIMENSIONS,
				'size_units'     => array( 'px', '%', 'em' ),
				'selectors'      => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.rael-business-hour-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'      => array(
					'rael_individual_style' => 'yes',
				),
				'style_transfer' => true,
			)
		);

		$repeater->add_control(
			'rael_day_time_margin',
			array(
				'label'          => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'           => Controls_Manager::DIMENSIONS,
				'size_units'     => array( 'px', '%', 'em' ),
				'selectors'      => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.rael-business-hour-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'      => array(
					'rael_individual_style' => 'yes',
				),
				'style_transfer' => true,
			)
		);

		$this->add_control(
			'rael_business_hour_list',
			array(
				'show_label'  => false,
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ rael_day }}}',
				'default'     => array(
					array(
						'rael_day'  => __( 'Monday', 'responsive-addons-for-elementor' ),
						'rael_time' => __( '10:00AM - 07:00PM', 'responsive-addons-for-elementor' ),
					),
					array(
						'rael_day'  => __( 'Tuesday', 'responsive-addons-for-elementor' ),
						'rael_time' => __( '10:00AM - 07:00PM', 'responsive-addons-for-elementor' ),
					),
					array(
						'rael_day'  => __( 'Wednesday', 'responsive-addons-for-elementor' ),
						'rael_time' => __( '10:00AM - 07:00PM', 'responsive-addons-for-elementor' ),
					),
					array(
						'rael_day'  => __( 'Thursday', 'responsive-addons-for-elementor' ),
						'rael_time' => __( '10:00AM - 07:00PM', 'responsive-addons-for-elementor' ),
					),
					array(
						'rael_day'  => __( 'Friday', 'responsive-addons-for-elementor' ),
						'rael_time' => __( '10:00AM - 07:00PM', 'responsive-addons-for-elementor' ),
					),
					array(
						'rael_day'  => __( 'Saturday', 'responsive-addons-for-elementor' ),
						'rael_time' => __( '10:00AM - 07:00PM', 'responsive-addons-for-elementor' ),
					),
					array(
						'rael_day'  => __( 'Sunday', 'responsive-addons-for-elementor' ),
						'rael_time' => __( 'Closed', 'responsive-addons-for-elementor' ),
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_business_settings',
			array(
				'label' => __( 'Settings', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_title_alignment',
			array(
				'label'       => __( 'Title Alignment', 'responsive-addons-for-elementor' ),
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
				'toggle'      => false,
				'selectors'   => array(
					'{{WRAPPER}} .rael-business-hour-title' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_day_alignment',
			array(
				'label'       => __( 'Day Alignment', 'responsive-addons-for-elementor' ),
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
				'toggle'      => false,
				'selectors'   => array(
					'{{WRAPPER}} .rael-business-hour-item .rael-business-hour-day' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_time_alignment',
			array(
				'label'       => __( 'Time Alignment', 'responsive-addons-for-elementor' ),
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
				'toggle'      => false,
				'selectors'   => array(
					'{{WRAPPER}} .rael-business-hour-item .rael-business-hour-time' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_business_hour_title_style',
			array(
				'label' => __( 'Title', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_title_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-business-hour-title h3' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_title_typography',
				'selector' => '{{WRAPPER}} .rael-business-hour-title h3',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_title_border',
				'label'    => __( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-business-hour-title',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'rael_title_background',
				'label'     => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .rael-business-hour-title',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_title_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-business-hour-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_title_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-business-hour-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_title_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-business-hour-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_business_hour_list_style',
			array(
				'label' => __( 'Hour List', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_list_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-business-hour-item' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_list_typography',
				'selector' => '{{WRAPPER}} .rael-business-hour-item',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_list_border',
				'label'    => __( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-business-hour-item',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'rael_list_background',
				'label'     => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .rael-business-hour-item',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_list_shadow',
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-business-hour-item',
			)
		);

		$this->add_control(
			'rael_list_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-business-hour-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_list_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-business-hour-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_list_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-business-hour-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_business_hour_container_style',
			array(
				'label' => __( 'Container', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_container_border',
				'label'    => __( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-business-hour-wrapper ul',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'rael_container_background',
				'label'     => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .rael-business-hour-wrapper ul',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_container_shadow',
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-business-hour-wrapper ul',
			)
		);

		$this->add_control(
			'rael_container_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-business-hour-wrapper ul' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_container_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-business-hour-wrapper ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render function
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="rael-business-hour-wrapper">
			<ul>
				<?php if ( $settings['rael_title'] ) : ?>
					<li class="rael-business-hour-title">
						<?php printf( '<h3>%s</h3>', esc_html( $settings['rael_title'] ) ); ?>
					</li>
				<?php endif; ?>
				<?php
				if ( is_array( $settings['rael_business_hour_list'] ) && 0 !== count( $settings['rael_business_hour_list'] ) ) :
					foreach ( $settings['rael_business_hour_list'] as $key => $item ) :
						// Day Element.
						$day_key = $this->get_repeater_setting_key( 'rael_day', 'rael_business_hour_list', $key );
						$this->add_inline_editing_attributes( $day_key, 'basic' );
						$this->add_render_attribute( $day_key, 'class', 'rael-business-hour-day' );
						// Time Element.
						$time_key = $this->get_repeater_setting_key( 'rael_time', 'rael_business_hour_list', $key );
						$this->add_inline_editing_attributes( $time_key, 'basic' );
						$this->add_render_attribute( $time_key, 'class', 'rael-business-hour-time' );
						?>
						<li class="rael-business-hour-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
							<?php if ( $item['rael_day'] ) : ?>
								<span <?php echo wp_kses_post( $this->get_render_attribute_string( $day_key ) ); ?>><?php echo esc_html( $item['rael_day'] ); ?></span>
							<?php endif; ?>
							<?php if ( $item['rael_time'] ) : ?>
								<span <?php echo wp_kses_post( $this->get_render_attribute_string( $time_key ) ); ?>><?php echo esc_html( $item['rael_time'] ); ?></span>
							<?php endif; ?>
						</li>
						<?php
					endforeach;
				endif;
				?>
			</ul>
		</div>
		<?php
	}
}
