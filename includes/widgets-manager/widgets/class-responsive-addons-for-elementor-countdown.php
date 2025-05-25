<?php
/**
 * Countdown Widget
 *
 * @since      1.0.0
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Plugin;


if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Countdown Widget
 *
 * @since      1.0.0
 * @package    Responsive_Addons_For_Elementor
 * @author     CyberChimps <support@cyberchimps.com>
 */
class Responsive_Addons_For_Elementor_Countdown extends Widget_Base {

	/**
	 * Default Countdown Labels
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $default_countdown_labels    Default Countdown labels.
	 */
	private $default_countdown_labels;

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-countdown';
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
		return __( 'Countdown', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve slider widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-countdown rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the slider widget belongs to.
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
	 * Register Controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_countdown',
			array(
				'label' => __( 'Countdown', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'countdown_type',
			array(
				'label'   => __( 'Type', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'due_date'  => __( 'Due Date', 'responsive-addons-for-elementor' ),
					'evergreen' => __( 'Evergreen Timer', 'responsive-addons-for-elementor' ),
				),
				'default' => 'due_date',
			)
		);

		$this->add_control(
			'due_date',
			array(
				'label'       => __( 'Due Date', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::DATE_TIME,
				'default'     => gmdate( 'Y-m-d H:i', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
				/* translators: %s: Time zone. */
				'description' => sprintf( __( 'Date set according to your timezone: %s.', 'responsive-addons-for-elementor' ), Utils::get_timezone_string() ),
				'condition'   => array(
					'countdown_type' => 'due_date',
				),
			)
		);

		$this->add_control(
			'evergreen_counter_hours',
			array(
				'label'       => __( 'Hours', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 47,
				'placeholder' => __( 'Hours', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'countdown_type' => 'evergreen',
				),
			)
		);

		$this->add_control(
			'evergreen_counter_minutes',
			array(
				'label'       => __( 'Minutes', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 59,
				'placeholder' => __( 'Minutes', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'countdown_type' => 'evergreen',
				),
			)
		);

		$this->add_control(
			'label_display',
			array(
				'label'        => __( 'View', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'block'  => __( 'Block', 'responsive-addons-for-elementor' ),
					'inline' => __( 'Inline', 'responsive-addons-for-elementor' ),
				),
				'default'      => 'block',
				'prefix_class' => 'responsive-countdown--label-',
			)
		);

		$this->add_control(
			'show_days',
			array(
				'label'     => __( 'Days', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off' => __( 'Hide', 'responsive-addons-for-elementor' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'show_hours',
			array(
				'label'     => __( 'Hours', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off' => __( 'Hide', 'responsive-addons-for-elementor' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'show_minutes',
			array(
				'label'     => __( 'Minutes', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off' => __( 'Hide', 'responsive-addons-for-elementor' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'show_seconds',
			array(
				'label'     => __( 'Seconds', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off' => __( 'Hide', 'responsive-addons-for-elementor' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'show_labels',
			array(
				'label'     => __( 'Show Label', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off' => __( 'Hide', 'responsive-addons-for-elementor' ),
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'custom_labels',
			array(
				'label'     => __( 'Custom Label', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array(
					'show_labels!' => '',
				),
			)
		);

		$this->add_control(
			'label_days',
			array(
				'label'       => __( 'Days', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Days', 'responsive-addons-for-elementor' ),
				'placeholder' => __( 'Days', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'show_labels!'   => '',
					'custom_labels!' => '',
					'show_days'      => 'yes',
				),
			)
		);

		$this->add_control(
			'label_hours',
			array(
				'label'       => __( 'Hours', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Hours', 'responsive-addons-for-elementor' ),
				'placeholder' => __( 'Hours', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'show_labels!'   => '',
					'custom_labels!' => '',
					'show_hours'     => 'yes',
				),
			)
		);

		$this->add_control(
			'label_minutes',
			array(
				'label'       => __( 'Minutes', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Minutes', 'responsive-addons-for-elementor' ),
				'placeholder' => __( 'Minutes', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'show_labels!'   => '',
					'custom_labels!' => '',
					'show_minutes'   => 'yes',
				),
			)
		);

		$this->add_control(
			'label_seconds',
			array(
				'label'       => __( 'Seconds', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Seconds', 'responsive-addons-for-elementor' ),
				'placeholder' => __( 'Seconds', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'show_labels!'   => '',
					'custom_labels!' => '',
					'show_seconds'   => 'yes',
				),
			)
		);

		$this->add_control(
			'label_position',
			array(
				'label'        => __( 'Label Position', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'separator'    => 'before',
				'label_block'  => false,
				'toggle'       => false,
				'default'      => 'bottom',
				'prefix_class' => 'responsive-countdown--label-',
				'condition'    => array(
					'show_labels' => 'yes',
				),
			)
		);

		$this->add_control(
			'label_space',
			array(
				'label'     => __( 'Label Space', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::POPOVER_TOGGLE,
				'condition' => array(
					'label_position' => 'right',
				),
			)
		);

		$this->start_popover();

		$this->add_control(
			'label_space_top',
			array(
				'label'      => __( 'Top', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => -100,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}.responsive-countdown--label-right .responsive-countdown-label' => 'margin-top: {{SIZE || 0}}{{UNIT}};',
				),
				'condition'  => array(
					'label_position' => 'right',
				),
			)
		);

		$this->add_control(
			'label_space_left',
			array(
				'label'      => __( 'Left', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => -100,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}.responsive-countdown--label-right .responsive-countdown-label' => 'margin-left: {{SIZE || 0}}{{UNIT}};',
				),
				'condition'  => array(
					'label_position' => 'right',
				),
			)
		);

		$this->end_popover();

		$this->add_control(
			'show_separator',
			array(
				'label'        => __( 'Show Separator?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'separator',
			array(
				'label'       => __( 'Separator', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
				'default'     => ':',
				'condition'   => array(
					'show_separator' => 'yes',
				),
			)
		);

		$this->add_control(
			'separator_color',
			array(
				'label'     => __( 'Separator Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-countdown-wrapper .responsive-countdown-separator' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'show_separator' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'separator_font_size',
			array(
				'label'      => __( 'Separator Font Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 45,
					'unit' => 'px',
				),
				'condition'  => array(
					'show_separator' => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}} .responsive-countdown-wrapper .responsive-countdown-separator' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'expire_actions',
			array(
				'label'       => __( 'Actions After Expire', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => array(
					'redirect' => __( 'Redirect', 'responsive-addons-for-elementor' ),
					'hide'     => __( 'Hide', 'responsive-addons-for-elementor' ),
					'message'  => __( 'Show Message', 'responsive-addons-for-elementor' ),
				),
				'label_block' => true,
				'separator'   => 'before',
				'render_type' => 'none',
				'multiple'    => true,
			)
		);

		$this->add_control(
			'message_after_expire',
			array(
				'label'     => __( 'Message', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXTAREA,
				'separator' => 'before',
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'expire_actions' => 'message',
				),
			)
		);

		$this->add_control(
			'expire_redirect_url',
			array(
				'label'     => __( 'Redirect URL', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::URL,
				'separator' => 'before',
				'options'   => false,
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'expire_actions' => 'redirect',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style',
			array(
				'label' => __( 'Boxes', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'container_width',
			array(
				'label'          => __( 'Container Width', 'responsive-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => '%',
					'size' => 100,
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'range'          => array(
					'px' => array(
						'min' => 0,
						'max' => 2000,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'size_units'     => array( '%', 'px' ),
				'selectors'      => array(
					'{{WRAPPER}} .responsive-countdown-wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'box_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-countdown-item' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'box_border',
				'selector'  => '{{WRAPPER}} .responsive-countdown-item',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'box_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .responsive-countdown-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_spacing',
			array(
				'label'     => __( 'Space Between', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .responsive-countdown-item:not(:first-of-type)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'body:not(.rtl) {{WRAPPER}} .responsive-countdown-item:not(:last-of-type)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .responsive-countdown-item:not(:first-of-type)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .responsive-countdown-item:not(:last-of-type)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
				),
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .responsive-countdown-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			array(
				'label' => __( 'Content', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_digits',
			array(
				'label' => __( 'Digits', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'digits_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-countdown-digits' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'digits_typography',
				'selector' => '{{WRAPPER}} .responsive-countdown-digits',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
			)
		);

		$this->add_control(
			'heading_label',
			array(
				'label'     => __( 'Label', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .responsive-countdown-label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .responsive-countdown-label',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_expire_message_style',
			array(
				'label'     => __( 'Message', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'expire_actions' => 'message',
				),
			)
		);

		$this->add_responsive_control(
			'align',
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
				'selectors' => array(
					'{{WRAPPER}} .responsive-countdown-expire--message' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .responsive-countdown-expire--message' => 'color: {{VALUE}};',
				),
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .responsive-countdown-expire--message',
			)
		);

		$this->add_responsive_control(
			'message_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .responsive-countdown-expire--message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get Time in String Format.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @param Object $instance Countdown Widget.
	 */
	private function get_strfmttime( $instance ) {
		$string = '';
		if ( $instance['show_days'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_days', 'responsive-countdown-days' );
			$string .= $this->render_separator( $instance );
		}
		if ( $instance['show_hours'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_hours', 'responsive-countdown-hours' );
			$string .= $this->render_separator( $instance );
		}
		if ( $instance['show_minutes'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_minutes', 'responsive-countdown-minutes' );
			$string .= $this->render_separator( $instance );
		}
		if ( $instance['show_seconds'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_seconds', 'responsive-countdown-seconds' );
		}

		return $string;
	}


	/**
	 * Render separator.
	 *
	 * @since 1.2.3
	 *
	 * @access private
	 * @param  Object $instance Countdown Widget.
	 *
	 * @return string
	 */
	private function render_separator( $instance ) {
		if ( 'yes' === $instance['show_separator'] && ! empty( $instance['separator'] ) ) {
			$string = '<span class="responsive-countdown-separator">' . $instance['separator'] . '</span>';

			return $string;
		}
		return '';
	}

	/**
	 * Initialize Default Countdown labels.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function init_default_countdown_labels() {
		$this->default_countdown_labels = array(
			'label_months'  => __( 'Months', 'responsive-addons-for-elementor' ),
			'label_weeks'   => __( 'Weeks', 'responsive-addons-for-elementor' ),
			'label_days'    => __( 'Days', 'responsive-addons-for-elementor' ),
			'label_hours'   => __( 'Hours', 'responsive-addons-for-elementor' ),
			'label_minutes' => __( 'Minutes', 'responsive-addons-for-elementor' ),
			'label_seconds' => __( 'Seconds', 'responsive-addons-for-elementor' ),
		);
	}

	/**
	 * Get Default Countdown labels.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_default_countdown_labels() {
		if ( ! $this->default_countdown_labels ) {
			$this->init_default_countdown_labels();
		}

		return $this->default_countdown_labels;
	}

	/**
	 * Render Countdown Item.
	 *
	 * @since 1.0.0
	 * @access private
	 * @param Array  $instance Countdown Widget Settings.
	 * @param string $label Label.
	 * @param string $part_class Part Class.
	 */
	private function render_countdown_item( $instance, $label, $part_class ) {
		$string = '<div class="responsive-countdown-item"><span class="responsive-countdown-digits ' . $part_class . '"></span>';

		if ( $instance['show_labels'] ) {
			$default_labels = $this->get_default_countdown_labels();
			$label          = ( $instance['custom_labels'] ) ? $instance[ $label ] : $default_labels[ $label ];
			$string        .= ' <span class="responsive-countdown-label">' . $label . '</span>';
		}

		$string .= '</div>';

		return $string;
	}

	/**
	 * Get Evergreen Interval
	 *
	 * @since 1.0.0
	 * @access private
	 * @param Array $instance Countdown Widget Settings.
	 */
	private function get_evergreen_interval( $instance ) {
		$hours              = empty( $instance['evergreen_counter_hours'] ) ? 0 : ( $instance['evergreen_counter_hours'] * HOUR_IN_SECONDS );
		$minutes            = empty( $instance['evergreen_counter_minutes'] ) ? 0 : ( $instance['evergreen_counter_minutes'] * MINUTE_IN_SECONDS );
		$evergreen_interval = $hours + $minutes;

		return $evergreen_interval;
	}

	/**
	 * Get Countdown Expite Actions.
	 *
	 * @since 1.0.0
	 * @access private
	 * @param Array $settings Countdown Widget Setting.
	 */
	private function get_actions( $settings ) {
		if ( empty( $settings['expire_actions'] ) || ! is_array( $settings['expire_actions'] ) ) {
			return false;
		}

		$actions = array();

		foreach ( $settings['expire_actions'] as $action ) {
			$action_to_run = array( 'type' => $action );
			if ( 'redirect' === $action ) {
				if ( empty( $settings['expire_redirect_url']['url'] ) ) {
					continue;
				}
				$action_to_run['redirect_url'] = $settings['expire_redirect_url']['url'];
			}
			$actions[] = $action_to_run;
		}

		return $actions;
	}

	/**
	 * Render Countdown Widget
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$instance = $this->get_settings_for_display();
		$due_date = $instance['due_date'];
		$string   = $this->get_strfmttime( $instance );

		if ( 'evergreen' === $instance['countdown_type'] ) {
			$this->add_render_attribute( 'div', 'data-evergreen-interval', $this->get_evergreen_interval( $instance ) );
		} else {
			$gmt      = get_gmt_from_date( $due_date . ':00' );
			$due_date = strtotime( $gmt );
		}

		$actions = false;

		if ( ! Plugin::instance()->editor->is_edit_mode() ) {
			$actions = $this->get_actions( $instance );
		}

		if ( $actions ) {
			$this->add_render_attribute( 'div', 'data-expire-actions', wp_json_encode( $actions ) );
		}

		$this->add_render_attribute(
			'div',
			array(
				'class'     => 'responsive-countdown-wrapper',
				'data-date' => $due_date,
			)
		);

		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'div' ) ); ?>>
			<?php echo wp_kses_post( $string ); ?>
		</div>
		<?php
		if ( $actions && is_array( $actions ) ) {
			foreach ( $actions as $action ) {
				if ( 'message' !== $action['type'] ) {
					continue;
				}
				echo '<div class="responsive-countdown-expire--message">' . wp_kses_post( $instance['message_after_expire'] ) . '</div>';
			}
		}
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/countdown';
	}
}
