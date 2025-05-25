<?php
/**
 * Button Widget
 *
 * @since      1.0.0
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor 'Button' widget.
 *
 * Elementor widget that displays an 'Button' with lightbox.
 *
 * @since 1.0.0
 */
class Responsive_Addons_For_Elementor_Button extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve 'Button' widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-button';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve 'Button' widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Button', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve 'Button' widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-button rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve 'Button' widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Register 'Button' widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		// Section button_section.

		$this->start_controls_section(
			'button_section',
			array(
				'label' => __( 'Button', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'label',
			array(
				'label'   => __( 'Button label', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Read More',
			)
		);

		$this->add_control(
			'link',
			array(
				'label'         => __( 'Link', 'responsive-addons-for-elementor' ),
				'description'   => __( 'If you want to link your button.', 'responsive-addons-for-elementor' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => 'https://your-link.com',
				'show_external' => true,
				'dynamic'       => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'open_video_in_lightbox',
			array(
				'label'        => __( 'Open Video in Lightbox', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Off', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->end_controls_section();

		// Section general_section.

		$this->start_controls_section(
			'general_section',
			array(
				'label' => __( 'Wrapper', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'size',
			array(
				'label'   => __( 'Button size', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'medium',
				'options' => array(
					'exlarge' => __( 'Exlarge', 'responsive-addons-for-elementor' ),
					'large'   => __( 'Large', 'responsive-addons-for-elementor' ),
					'medium'  => __( 'Medium', 'responsive-addons-for-elementor' ),
					'small'   => __( 'Small', 'responsive-addons-for-elementor' ),
					'tiny'    => __( 'Tiny', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'uppercase',
			array(
				'label'        => __( 'Uppercase label', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Off', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'border',
			array(
				'label'   => __( 'Button shape', 'responsive-addons-for-elementor' ),
				'type'    => 'rael-visual-select',
				'options' => array(
					'none'  => array(
						'label' => __( 'Box', 'responsive-addons-for-elementor' ),
						'image' => RAEL_ASSETS_URL . 'images/visual-select/button-normal.svg',
					),
					'round' => array(
						'label' => __( 'Round', 'responsive-addons-for-elementor' ),
						'image' => RAEL_ASSETS_URL . 'images/visual-select/button-curved.svg',
					),
					'curve' => array(
						'label' => __( 'Curve', 'responsive-addons-for-elementor' ),
						'image' => RAEL_ASSETS_URL . 'images/visual-select/button-rounded.svg',
					),
				),
				'default' => 'none',
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => __( 'Button style', 'responsive-addons-for-elementor' ),
				'type'    => 'rael-visual-select',
				'options' => array(
					'none'    => array(
						'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
						'image' => RAEL_ASSETS_URL . 'images/visual-select/button-normal.svg',
					),
					'3d'      => array(
						'label' => __( '3D', 'responsive-addons-for-elementor' ),
						'image' => RAEL_ASSETS_URL . 'images/visual-select/button-3d.svg',
					),
					'outline' => array(
						'label' => __( 'Outline', 'responsive-addons-for-elementor' ),
						'image' => RAEL_ASSETS_URL . 'images/visual-select/button-outline.svg',
					),
				),
				'default' => 'none',
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Align', 'responsive-addons-for-elementor' ),
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
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Section sking_section.

		$this->start_controls_section(
			'sking_section',
			array(
				'label' => __( 'Skin', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'color_name',
			array(
				'label'   => __( 'Skin', 'responsive-addons-for-elementor' ),
				'type'    => 'rael-visual-select',
				'default' => 'carmine-pink',
				'options' => $this->rael_get_famous_colors_list(),
			)
		);

		$this->start_controls_tabs( 'button_background' );

		$this->start_controls_tab(
			'button_bg_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'background',
				'label'    => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-button',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .rael-button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_bg_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'hover_background',
				'label'    => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-button:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'hover_box_shadow',
				'selector' => '{{WRAPPER}} .rael-button:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Section icon_section.

		$this->start_controls_section(
			'icon_section',
			array(
				'label' => __( 'Icon', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_button_icon',
			array(
				'label' => __( 'Icon for button', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::ICONS,
			)
		);

		$this->add_control(
			'icon_align',
			array(
				'label'   => __( 'Icon alignment', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default'       => __( 'Default', 'responsive-addons-for-elementor' ),
					'left'          => __( 'Left', 'responsive-addons-for-elementor' ),
					'right'         => __( 'Right', 'responsive-addons-for-elementor' ),
					'over'          => __( 'Over', 'responsive-addons-for-elementor' ),
					'left-animate'  => __( 'Animate from Left', 'responsive-addons-for-elementor' ),
					'right-animate' => __( 'Animate from Right', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_responsive_control(
			'btn_icon_size',
			array(
				'label'      => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 10,
						'max'  => 512,
						'step' => 2,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-icon'       => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-button svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_margin',
			array(
				'label'      => __( 'Icon Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-icon, {{WRAPPER}} .rael-button svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'button_icon_color' );

		$this->start_controls_tab(
			'icon_color_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-icon'       => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-button svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .rael-button svg path' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_color_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'hover_icon_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-button:hover .rael-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-button:hover svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .rael-button:hover svg path' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'icon_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-icon, {{WRAPPER}} .rael-button svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Section text_section.

		$this->start_controls_section(
			'text_section',
			array(
				'label' => __( 'Text', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'button_text' );

		$this->start_controls_tab(
			'button_text_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'text_shadow',
				'label'    => __( 'Text Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-button .rael-text',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .rael-text',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_text_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'hover_text_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-button:hover .rael-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'hover_text_shadow',
				'label'    => __( 'Text Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-button:hover .rael-text',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'hover_text_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .rael-text',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Render image box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$icon_value = ! empty( $settings['rael_button_icon']['value'] ) ? $settings['rael_button_icon']['value'] : ( ! empty( $settings['icon'] ) ? $settings['icon'] : '' );

		$btn_target = $settings['link']['is_external'] ? '_blank' : '_self';

		$args = array(
			'label'                  => $settings['label'],
			'size'                   => $settings['size'],
			'border'                 => $settings['border'],
			'style'                  => $settings['style'],
			'uppercase'              => $settings['uppercase'],
			'icon'                   => $icon_value,
			'icon_align'             => $settings['icon_align'],
			'color_name'             => $settings['color_name'],
			'link'                   => $settings['link']['url'],
			'nofollow'               => $settings['link']['nofollow'],
			'target'                 => $btn_target,
			'open_video_in_lightbox' => $settings['open_video_in_lightbox'],
		);

		echo wp_kses( $this->rael_widget_button_callback( $args ), $this->allowed_html_tags() );
	}

	/**
	 * Return allowed tags for current widget
	 *
	 * @return array Array of allowed tags for current widget
	 */
	private function allowed_html_tags() {
		$tags_allowed = wp_kses_allowed_html( 'post' );

		$tags_allowed['svg'] = array(
			'g'           => array(),
			'width'       => array(),
			'height'      => array(),
			'fill'        => array(),
			'version'     => array(),
			'class'       => array(),
			'xmlns'       => array(),
			'viewBox'     => array(),
			'viewbox'     => array(),
			'xml:space'   => array(),
			'xmlns:xlink' => array(),
			'x'           => array(),
			'y'           => array(),
			'style'       => array(),
			'path'    => array(
				'id'    => array(),
				'class' => array(),
				'd'     => array(),
				'fill'  => array(),
			),
		);
		$tags_allowed['path'] = array(
			'id'    => array(),
			'class' => array(),
			'd'     => array(),
			'fill'  => array(),
		);

		return $tags_allowed;
	}

	/**
	 * RAEL widget button callback
	 *
	 * @param array  $atts array of attributes.
	 * @param string $shortcode_content shortcode content.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function rael_widget_button_callback( $atts = array(), $shortcode_content = null ) {

		// Defining default attributes.
		$default_atts = array(
			'label'                  => '',
			'size'                   => 'medium',
			'border'                 => '',
			'style'                  => '',
			'uppercase'              => '1',
			'dark'                   => '0',
			'icon'                   => '',
			'icon_align'             => 'default',
			'color_name'             => 'carmine-pink',
			'link'                   => '',
			'target'                 => '_self',
			'nofollow'               => false,
			'btn_attrs'              => '', // data-attr1{val1};data-attr2{val2}.
			'custom_styles'          => array(),
			'extra_classes'          => '', // custom css class names for this element.
			'custom_el_id'           => '',
			'base_class'             => 'rael-widget-button',
			'open_video_in_lightbox' => false,
		);

		// Parse shortcode attributes.
		$parsed_atts = shortcode_atts( $default_atts, $atts, __FUNCTION__ );
		extract( $parsed_atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

		// --------------------------------------------
		$btn_css_classes   = array( 'rael-button' );
		$btn_css_classes[] = 'rael-' . $size;    // size.
		$btn_css_classes[] = 'rael-' . $color_name;   // appearance.

		if ( $border ) {
			$btn_css_classes[] = 'rael-' . $border;  // border form.
		}
		if ( $style ) {
			$btn_css_classes[] = 'rael-' . $style;   // appearance.
		}
		if ( $this->rael_is_true( $uppercase ) ) {
			$btn_css_classes[] = 'rael-uppercase';   // text form.
		}
		if ( $this->rael_is_true( $dark ) ) {
			$btn_css_classes[] = 'rael-dark-text';   // text color.
		}
		if ( 'default' !== $icon_align ) {
			$btn_css_classes[] = 'rael-icon-' . $icon_align;   // icon align.
		}

		// add extra attributes to button element if defined.
		$btn_other_attrs = '';

		if ( $btn_attrs = trim( $btn_attrs, ';' ) ) { // phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found, Squiz.PHP.DisallowMultipleAssignments.FoundInControlStructure
			preg_match_all( '/([\-|\w]+)(?!{})([\w]+)/s', $btn_attrs, $btn_attr_matches );

			if ( ! empty( $btn_attr_matches[0] ) && is_array( $btn_attr_matches[0] ) ) {
				foreach ( $btn_attr_matches[0] as $i => $attr_name_value ) {
					if ( 0 === $i % 2 ) {
						$btn_other_attrs .= sprintf( ' %s', $attr_name_value );
					} else {
						$btn_other_attrs .= sprintf( '="%s"', esc_attr( trim( $attr_name_value ) ) );
					}
				}
				$btn_other_attrs = trim( $btn_other_attrs );
			}
		}

		$extra_styles = '';

		if ( isset( $custom_styles ) && ! empty( $custom_styles ) ) {

			foreach ( $custom_styles as $property => $value ) {
				if ( 'custom' === $property ) {
					$extra_styles .= $value;
				} else {
					$extra_styles .= $property . ':' . $value . ';';
				}
			}

			$extra_styles = 'style="' . $extra_styles . '"';

		}

		if ( ! empty( $extra_classes ) ) {
			$btn_css_classes[] = $extra_classes;
		}

		if ( $this->rael_is_true( $open_video_in_lightbox ) ) {
			$btn_css_classes[] = 'rael-open-video';
			$btn_other_attrs  .= ' data-type="video"';
		}

		// get escaped class attributes.
		$button_class_attr = $this->rael_make_html_class_attribute( $btn_css_classes );

		$label = empty( $label ) ? $shortcode_content : $label;
		$label = empty( $label ) ? __( 'Button', 'responsive-addons-for-elementor' ) : $label;

		$btn_content = '<span class="rael-overlay"></span>';
		$btn_label   = '<span class="rael-text">' . $this->rael_do_cleanup_shortcode( $label ) . '</span>';
		if ( 'array' === gettype( $icon ) ) {
			$btn_icon = Icons_Manager::render_uploaded_svg_icon( $icon );
		} else {
			$btn_icon = '<span class="rael-icon ' . esc_attr( $icon ) . '"></span>';
		}

		if ( 'left' === $icon_align || 'left-animate' === $icon_align ) {
			$btn_content .= $btn_icon . $btn_label;
		} elseif ( 'right' === $icon_align || 'right-animate' === $icon_align ) {
			$btn_content .= $btn_label . $btn_icon;
		} else {
			$btn_content .= $btn_label . $btn_icon;
		}

		$btn_tag  = empty( $link ) ? 'button' : 'a';
		$btn_rel  = $this->rael_is_true( $nofollow ) ? ' rel="nofollow"' : '';
		$btn_href = empty( $link ) ? '' : ' href="' . $link . '" target="' . esc_attr( $target ) . '" ' . $btn_rel;

		$output = '';

		// widget custom output.
		$output .= "<$btn_tag $btn_href $btn_other_attrs $button_class_attr $extra_styles>";
		$output .= $btn_content;
		$output .= "</$btn_tag>";

		if ( $this->rael_is_true( $open_video_in_lightbox ) ) {
			$output = '<span class="rael-lightbox-video ">' . $output . '</span>';
		}
		return $output;
	}

	/**
	 * RAEL is true function
	 *
	 * @param any $var array of attributes.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function rael_is_true( $var ) {
		if ( is_bool( $var ) ) {
			return $var;
		}

		if ( is_string( $var ) ) {
			$var = strtolower( $var );
			if ( in_array( $var, array( 'yes', 'on', 'true', 'checked' ), true ) ) {
				return true;
			}
		}

		if ( is_numeric( $var ) ) {
			return (bool) $var;
		}

		return false;
	}

	/**
	 * Cleanup Shortcode
	 *
	 * @param string $content Content.
	 *
	 * @return mixed|string
	 */
	public function rael_do_cleanup_shortcode( $content ) {

		/* Parse nested shortcodes and add formatting. */
		$content = trim( wpautop( do_shortcode( $content ) ) );

		/* Remove any instances of '<p>' '</p>'. */
		$content = $this->rael_cleanup_content( $content );

		return $content;
	}

	/**
	 * Cleanup Content function
	 *
	 * @param string $content Content.
	 *
	 * @return mixed|string
	 */
	public function rael_cleanup_content( $content ) {
		/* Remove any instances of '<p>' '</p>'. */
		return str_replace( array( '<p>', '</p>' ), array( '', '' ), $content );
	}

	/**
	 * Creates and returns an HTML class attribute
	 *
	 * @param  array        $classes   List of current classes.
	 * @param  string|array $class     One or more classes to add to the class list.
	 *
	 * @return string                  HTML class attribute
	 */
	public function rael_make_html_class_attribute( $classes = '', $class = '' ) {

		if ( ! $merged_classes = $this->rael_merge_css_classes( $classes, $class ) ) { // phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found, Squiz.PHP.DisallowMultipleAssignments.FoundInControlStructure
			return '';
		}

		return 'class="' . esc_attr( trim( join( ' ', array_unique( $merged_classes ) ) ) ) . '"';
	}

	/**
	 * Merge CSS classes
	 *
	 * @param array  $classes has array of classes.
	 * @param string $class is a string having class.
	 *
	 * @return array CSS classes
	 */
	public function rael_merge_css_classes( $classes = array(), $class = '' ) {

		if ( empty( $classes ) && empty( $class ) ) {
			return array();
		}

		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}

			$classes = array_merge( $class, $classes );
		}

		return $classes;
	}

	/**
	 * Get Famous colors list
	 *
	 * @return array Colors
	 */
	public function rael_get_famous_colors_list() {
		return array(
			'black'           => array(
				'label'     => __( 'black', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-black',
			),
			'white'           => array(
				'label'     => __( 'White', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-white',
			),
			'masala'          => array(
				'label'     => __( 'Masala', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-masala',
			),
			'dark-gray'       => array(
				'label'     => __( 'Dark Gray', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-dark-gray',
			),
			'ball-blue'       => array(
				'label'     => __( 'Ball Blue', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-ball-blue',
			),
			'fountain-blue'   => array(
				'label'     => __( 'Fountain Blue', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-fountain-blue',
			),
			'shamrock'        => array(
				'label'     => __( 'Shamrock', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-shamrock',
			),
			'curios-blue'     => array(
				'label'     => __( 'Curios Blue', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-curios-blue',
			),
			'light-sea-green' => array(
				'label'     => __( 'Light Sea Green', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-light-sea-green',
			),
			'emerald'         => array(
				'label'     => __( 'Emerald', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-emerald',
			),
			'energy-yellow'   => array(
				'label'     => __( 'Energy Yellow', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-energy-yellow',
			),
			'mikado-yellow'   => array(
				'label'     => __( 'Mikado Yellow', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-mikado-yellow',
			),
			'pink-salmon'     => array(
				'label'     => __( 'Pink Salmon', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-pink-salmon',
			),
			'wisteria'        => array(
				'label'     => __( 'Wisteria', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-wisteria',
			),
			'lilac'           => array(
				'label'     => __( 'Lilac', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-lilac',
			),
			'pale-sky'        => array(
				'label'     => __( 'Pale Sky', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-pale-sky',
			),
			'tower-gray'      => array(
				'label'     => __( 'Tower Gray', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-tower-gray',
			),

			'william'         => array(
				'label'     => __( 'William', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-william',
			),
			'carmine-pink'    => array(
				'label'     => __( 'Carmine Pink', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-carmine-pink',
			),
			'persimmon'       => array(
				'label'     => __( 'Persimmon', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-persimmon',
			),
			'tan-hide'        => array(
				'label'     => __( 'Tan Hide', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-tan-hide',
			),
			'wild-watermelon' => array(
				'label'     => __( 'Wild Watermelon', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-wild-watermelon',
			),
			'iceberg'         => array(
				'label'     => __( 'Iceberg', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-iceberg',
			),

			'dark-lavender'   => array(
				'label'     => __( 'Dark Lavender', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-dark-lavender',
			),
			'viking'          => array(
				'label'     => __( 'Viking', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-viking',
			),
			'tiffany-blue'    => array(
				'label'     => __( 'Tiffany Blue', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-tiffany-blue',
			),
			'pastel-orange'   => array(
				'label'     => __( 'Pastel Orange', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-pastel-orange',
			),
			'east-bay'        => array(
				'label'     => __( 'East Bay', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-east-bay',
			),
			'steel-blue'      => array(
				'label'     => __( 'Steel Blue', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-steel-blue',
			),
			'half-backed'     => array(
				'label'     => __( 'Half Backed', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-half-backed',
			),
			'tapestry'        => array(
				'label'     => __( 'Tapestry', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-tapestry',
			),
			'fire-engine-red' => array(
				'label'     => __( 'Fire Engine Red', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-fire-engine-red',
			),
			'dark-orange'     => array(
				'label'     => __( 'Dark Orange', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-dark-orange',
			),
			'brick-red'       => array(
				'label'     => __( 'Brick Red', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-brick-red',
			),
			'khaki'           => array(
				'label'     => __( 'Khaki', 'responsive-addons-for-elementor' ),
				'css_class' => 'rael-color-selector rael-button rael-visual-selector-khaki',
			),
		);
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/button';
	}
}
