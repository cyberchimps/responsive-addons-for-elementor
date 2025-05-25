<?php
/**
 * Fancy Text Widget
 *
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.

}

/**
 * Elementor 'Fancy Text' widget class.
 */
class Responsive_Addons_For_Elementor_Fancy_Text extends Widget_Base {

	/**
	 * Get name function
	 *
	 * @access public
	 */
	public function get_name() {
		return 'rael-fancy-text';
	}

	/**
	 * Get title function
	 *
	 * @access public
	 */
	public function get_title() {
		return __( 'Fancy Text', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get icon function
	 *
	 * @access public
	 */
	public function get_icon() {
		return 'eicon-animation-text rael-badge';
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
		return array( 'text', 'fancy', 'rael', 'heading', 'typewriter', 'animated', 'typing' );
	}

	/**
	 * Get custom help url function
	 *
	 * @access public
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/fancy-text';
	}

	/**
	 * Register controls function
	 *
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'rael_fancy_text_content',
			array(
				'label' => esc_html__( 'Fancy Text', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_fancy_text_prefix',
			array(
				'label'       => esc_html__( 'Prefix Text', 'responsive-addons-for-elementor' ),
				'placeholder' => esc_html__( 'Place your prefix text', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'This is the ', 'responsive-addons-for-elementor' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'rael_fancy_text_strings_text_field',
			array(
				'label'       => esc_html__( 'Fancy String', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'rael_fancy_text_strings',
			array(
				'label'       => __( 'Fancy Text Strings', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'show_label'  => true,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{ rael_fancy_text_strings_text_field }}',
				'default'     => array(
					array(
						'rael_fancy_text_strings_text_field' => __( 'First string', 'responsive-addons-for-elementor' ),
					),
					array(
						'rael_fancy_text_strings_text_field' => __( 'Second string', 'responsive-addons-for-elementor' ),
					),
					array(
						'rael_fancy_text_strings_text_field' => __( 'Third string', 'responsive-addons-for-elementor' ),
					),
				),
			)
		);

		$this->add_control(
			'rael_fancy_text_suffix',
			array(
				'label'       => esc_html__( 'Suffix Text', 'responsive-addons-for-elementor' ),
				'placeholder' => esc_html__( 'Place your suffix text', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( ' of the sentence.', 'responsive-addons-for-elementor' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_fancy_text_settings',
			array(
				'label' => esc_html__( 'Fancy Text Settings', 'responsive-addons-for-elementor' ),
			)
		);

		$style_options = apply_filters(
			'rael_fancy_text_style_types',
			array(
				'styles'     => array(
					'style-1' => esc_html__( 'Style 1', 'responsive-addons-for-elementor' ),
					'style-2' => esc_html__( 'Style 2', 'responsive-addons-for-elementor' ),
				),
				'conditions' => array( 'style-2' ),
			)
		);

		$this->add_control(
			'rael_fancy_text_style',
			array(
				'label'   => esc_html__( 'Style Type', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style-1',
				'options' => $style_options['styles'],
			)
		);

		$this->add_responsive_control(
			'rael_fancy_text_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .rael-fancy-text-container' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_fancy_text_transition_type',
			array(
				'label'   => esc_html__( 'Animation Type', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'typing',
				'options' => array(
					'typing'      => esc_html__( 'Typing', 'responsive-addons-for-elementor' ),
					'fadeIn'      => esc_html__( 'Fade', 'responsive-addons-for-elementor' ),
					'fadeInUp'    => esc_html__( 'Fade Up', 'responsive-addons-for-elementor' ),
					'fadeInDown'  => esc_html__( 'Fade Down', 'responsive-addons-for-elementor' ),
					'fadeInLeft'  => esc_html__( 'Fade Left', 'responsive-addons-for-elementor' ),
					'fadeInRight' => esc_html__( 'Fade Right', 'responsive-addons-for-elementor' ),
					'zoomIn'      => esc_html__( 'Zoom', 'responsive-addons-for-elementor' ),
					'bounce'      => esc_html__( 'Bounce', 'responsive-addons-for-elementor' ),
					'swing'       => esc_html__( 'Swing', 'responsive-addons-for-elementor' ),
					'tada'        => esc_html__( 'Tada', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_fancy_text_speed',
			array(
				'label'     => esc_html__( 'Typing Speed (ms)', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '50',
				'condition' => array(
					'rael_fancy_text_transition_type' => 'typing',
				),
			)
		);

		$this->add_control(
			'rael_fancy_text_back_speed',
			array(
				'label'     => esc_html__( 'Back Speed (ms)', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '50',
				'condition' => array(
					'rael_fancy_text_transition_type' => 'typing',
				),
			)
		);

		$this->add_control(
			'rael_fancy_text_delay',
			array(
				'label'   => esc_html__( 'Delay on Change', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '2500',
			)
		);

		$this->add_control(
			'rael_fancy_text_loop',
			array(
				'label'        => esc_html__( 'Loop the Typing', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'rael_fancy_text_transition_type' => 'typing',
				),
			)
		);

		$this->add_control(
			'rael_fancy_text_cursor',
			array(
				'label'        => esc_html__( 'Display Type Cursor', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'rael_fancy_text_transition_type' => 'typing',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_fancy_text_prefix_styles',
			array(
				'label' => esc_html__( 'Prefix Text Styles', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_fancy_text_prefix_color',
			array(
				'label'     => esc_html__( 'Prefix Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-fancy-text-prefix' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'typography',
				'global'         => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_size'   => array( 'default' => array( 'size' => 22 ) ),
					'font_weight' => array( 'default' => 600 ),
					'line_height' => array( 'default' => array( 'size' => 1 ) ),
				),
				'selector'       => '{{WRAPPER}} .rael-fancy-text-prefix',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_fancy_text_strings_styles',
			array(
				'label' => esc_html__( 'Fancy Text Styles', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_fancy_text_color_selector',
			array(
				'label'     => esc_html__( 'Choose Background Type', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'solid-color'    => array(
						'title' => __( 'Color', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-paint-brush',
					),
					'gradient-color' => array(
						'title' => __( 'Gradient', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-barcode',
					),
				),
				'toggle'    => true,
				'default'   => 'solid-color',
				'condition' => array(
					'rael_fancy_text_style' => 'style-1',
				),
			)
		);

		$this->add_control(
			'rael_fancy_text_strings_background_color',
			array(
				'label'      => esc_html__( 'Background', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '',
				'selectors'  => array(
					'{{WRAPPER}} .rael-fancy-text-strings' => 'background: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'terms' => array(
								array(
									'name'     => 'rael_fancy_text_color_selector',
									'operator' => '==',
									'value'    => 'solid-color',
								),
							),
						),
						array(
							'name'     => 'rael_fancy_text_style',
							'operator' => '==',
							'value'    => 'style-2',
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'rael_fancy_text_color_gradient',
				'types'          => array( 'gradient' ),
				'fields_options' => array(
					'background' => array(
						'label'   => __( 'Gradient Color', 'responsive-addons-for-elementor' ),
						'toggle'  => false,
						'default' => 'gradient',
					),
					'color'      => array(
						'default' => '#062ACA',
					),
					'color_b'    => array(
						'default' => '#9401D9',
					),
				),
				'selector'       => '{{WRAPPER}} .rael-fancy-text-strings',
				'condition'      => array(
					'rael_fancy_text_color_selector' => 'gradient-color',
					'rael_fancy_text_style'          => 'style-1',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'rael_fancy_text_strings_typography',
				'global'         => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_size'   => array( 'default' => array( 'size' => 22 ) ),
					'font_weight' => array( 'default' => 600 ),
				),
				'selector'       => '{{WRAPPER}} .rael-fancy-text-strings, {{WRAPPER}} .typed-cursor',
			)
		);

		$this->add_control(
			'rael_fancy_text_strings_color',
			array(
				'label'      => esc_html__( 'Solid Color', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => array(
					'{{WRAPPER}} .rael-fancy-text-strings' => 'color: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'terms' => array(
								array(
									'name'     => 'rael_fancy_text_style',
									'operator' => '==',
									'value'    => 'style-1',
								),
							),
						),
						array(
							'name'     => 'rael_fancy_text_style',
							'operator' => '==',
							'value'    => 'style-2',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_fancy_text_cursor_color',
			array(
				'label'     => esc_html__( 'Typing Cursor Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .typed-cursor' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_fancy_text_cursor' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_fancy_text_strings_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-fancy-text-strings' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_fancy_text_strings_margin',
			array(
				'label'      => esc_html__( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-fancy-text-strings' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_fancy_text_strings_border',
				'selector' => '{{WRAPPER}} .rael-fancy-text-strings',
			)
		);

		$this->add_control(
			'rael_fancy_text_strings_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-fancy-text-strings' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_fancy_text_suffix_styles',
			array(
				'label' => esc_html__( 'Suffix Text Styles', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_fancy_text_suffix_color',
			array(
				'label'     => esc_html__( 'Suffix Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-fancy-text-suffix' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'ending_typography',
				'global'         => [
					'default'     => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_size'   => array( 'default' => array( 'size' => 22 ) ),
					'font_weight' => array( 'default' => 600 ),
					'line_height' => array( 'default' => array( 'size' => 1 ) ),
				),
				'selector'       => '{{WRAPPER}} .rael-fancy-text-suffix',
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Get fancy text function
	 *
	 * @param array $settings fancy text settings.
	 *
	 * @access public
	 */
	public function get_fancy_text( $settings ) {
		$fancy_text = array( '' );
		foreach ( $settings as $item ) {
			if ( ! empty( $item['rael_fancy_text_strings_text_field'] ) ) {
				$fancy_text[] = wp_kses_post( $item['rael_fancy_text_strings_text_field'] );
			}
		}
		return implode( '|', $fancy_text );
	}

	/**
	 * Render function
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$fancy_text = $this->get_fancy_text( $settings['rael_fancy_text_strings'] );
		$this->add_render_attribute( 'fancy-text', 'class', 'rael-fancy-text-container' );
		$this->add_render_attribute( 'fancy-text', 'class', esc_attr( $settings['rael_fancy_text_style'] ) );
		$this->add_render_attribute( 'fancy-text', 'data-fancy-text-id', esc_attr( $this->get_id() ) );
		$this->add_render_attribute( 'fancy-text', 'data-fancy-text', $fancy_text );
		$this->add_render_attribute( 'fancy-text', 'data-fancy-text-transition-type', $settings['rael_fancy_text_transition_type'] );
		$this->add_render_attribute( 'fancy-text', 'data-fancy-text-speed', $settings['rael_fancy_text_speed'] );
		$this->add_render_attribute( 'fancy-text', 'data-fancy-text-back-speed', $settings['rael_fancy_text_back_speed'] );
		$this->add_render_attribute( 'fancy-text', 'data-fancy-text-delay', $settings['rael_fancy_text_delay'] );
		$this->add_render_attribute( 'fancy-text', 'data-fancy-text-cursor', $settings['rael_fancy_text_cursor'] );
		$this->add_render_attribute( 'fancy-text', 'data-fancy-text-loop', $settings['rael_fancy_text_loop'] );
		?>

		<div  <?php echo wp_kses_post( $this->get_render_attribute_string( 'fancy-text' ) ); ?> >
			<?php if ( ! empty( $settings['rael_fancy_text_prefix'] ) ) : ?>
				<span class="rael-fancy-text-prefix"><?php echo wp_kses_post( $settings['rael_fancy_text_prefix'] ); ?> </span>
			<?php endif; ?>

			<?php if ( 'fancy' === $settings['rael_fancy_text_transition_type'] ) : ?>
				<span id="rael-fancy-text-<?php echo esc_attr( $this->get_id() ); ?>" class="rael-fancy-text-strings
				<?php echo wp_kses_post( $settings['rael_fancy_text_color_selector'] ); ?>"></span>
			<?php endif; ?>

			<?php if ( 'fancy' !== $settings['rael_fancy_text_transition_type'] ) : ?>
				<span id="rael-fancy-text-<?php echo esc_attr( $this->get_id() ); ?>" class="rael-fancy-text-strings <?php echo wp_kses_post( $settings['rael_fancy_text_color_selector'] ); ?>">
				<noscript>
					<?php
					$rael_fancy_text_strings_list = '';
					foreach ( $settings['rael_fancy_text_strings'] as $item ) {
						$rael_fancy_text_strings_list .= wp_kses_post( $item['rael_fancy_text_strings_text_field'] ) . ', ';
					}
					echo wp_kses_post( rtrim( $rael_fancy_text_strings_list, ', ' ) );
					?>
				</noscript>
			</span>
			<?php endif; ?>

			<?php if ( ! empty( $settings['rael_fancy_text_suffix'] ) ) : ?>
				<span class="rael-fancy-text-suffix"> <?php echo wp_kses_post( $settings['rael_fancy_text_suffix'] ); ?></span>
			<?php endif; ?>
		</div>

		<div class="clearfix"></div>

		<?php

	}

	/**
	 * Content template function
	 *
	 * @access protected
	 */
	protected function content_template() {}

}
