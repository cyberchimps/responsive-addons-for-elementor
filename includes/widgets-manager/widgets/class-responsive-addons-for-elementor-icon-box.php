<?php
/**
 * Icon Box Widget
 *
 * @since      1.2.0
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor 'Icon Box' widget class.
 *
 * @since 1.2.0
 */
class Responsive_Addons_For_Elementor_Icon_Box extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-info-box';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Icon Box', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve slider widget icon.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-icon-box rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the icon box widget belongs to.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/icon-box';
	}

	/**
	 * Register all the control settings for the icon box
	 *
	 * @since 1.2.0
	 * @access public
	 */
	protected function register_controls() {    // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		// Content Section.
		$this->register_general_content_controls();
		$this->register_imgicon_content_controls();
		$this->register_separator_content_controls();
		$this->register_cta_content_controls();

		// Style Section.
		$this->register_infobox_container_style_controls();
		$this->register_icon_style_controls();
		$this->register_typography_style_controls();
		$this->register_button_style_controls();
		$this->register_seperator_style_controls();
		$this->register_margin_style_controls();
	}

	/**
	 * Add General controls section under the Content TAB
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_general_content_controls() {
		$this->start_controls_section(
			'rael_general_section',
			array(
				'label' => __( 'General', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_title',
			array(
				'label'    => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'     => Controls_Manager::TEXT,
				'selector' => '{{WRAPPER}} .rael-infobox__title',
				'dynamic'  => array(
					'active' => true,
				),
				'default'  => __( 'Icon Box', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_control(
			'rael_description',
			array(
				'label'    => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'     => Controls_Manager::TEXTAREA,
				'selector' => '{{WRAPPER}} .rael-infobox__description',
				'dynamic'  => array(
					'active' => true,
				),
				'default'  => __( 'Enter description text here.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.​', 'responsive-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add Image/Icon controls section under the Content TAB
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_imgicon_content_controls() {
		$this->start_controls_section(
			'rael_imageicon_section',
			array(
				'label' => __( 'Image/Icon', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_image_type',
			array(
				'label'   => __( 'Image Type', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'photo' => array(
						'title' => __( 'Image', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-picture-o',
					),
					'icon'  => array(
						'title' => __( 'Font Icon', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-info-circle',
					),
				),
				'default' => 'icon',
				'toggle'  => true,
			)
		);
		$this->add_control(
			'rael_icon_basics',
			array(
				'label'     => __( 'Icon Basics', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'rael_image_type' => 'icon',
				),
			)
		);

		if ( self::is_elementor_updated() ) {

			$this->add_control(
				'rael_new_select_icon',
				array(
					'label'            => __( 'Select Icon', 'responsive-addons-for-elementor' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => 'rael_select_icon',
					'default'          => array(
						'value'   => 'fa fa-star',
						'library' => 'fa-solid',
					),
					'condition'        => array(
						'rael_image_type' => 'icon',
					),
					'render_type'      => 'template',
				)
			);
		} else {
			$this->add_control(
				'rael_select_icon',
				array(
					'label'     => __( 'Select Icon', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::ICON,
					'default'   => 'fa fa-star',
					'condition' => array(
						'rael_image_type' => 'icon',
					),
				)
			);
		}

		$this->add_control(
			'rael_image_basics',
			array(
				'label'     => __( 'Image Basics', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'rael_image_type' => 'photo',
				),
			)
		);
		$this->add_control(
			'rael_photo_type',
			array(
				'label'       => __( 'Photo Source', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'media',
				'label_block' => false,
				'options'     => array(
					'media' => __( 'Media Library', 'responsive-addons-for-elementor' ),
					'url'   => __( 'URL', 'responsive-addons-for-elementor' ),
				),
				'condition'   => array(
					'rael_image_type' => 'photo',
				),
			)
		);
		$this->add_control(
			'rael_image',
			array(
				'label'     => __( 'Photo', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'rael_image_type' => 'photo',
					'rael_photo_type' => 'media',

				),
			)
		);
		$this->add_control(
			'rael_image_link',
			array(
				'label'         => __( 'Photo URL', 'responsive-addons-for-elementor' ),
				'type'          => Controls_Manager::URL,
				'default'       => array(
					'url' => '',
				),
				'show_external' => false, // Show the 'open in new tab' button.
				'condition'     => array(
					'rael_image_type' => 'photo',
					'rael_photo_type' => 'url',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'rael_image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`phpcs:ignore Squiz.PHP.CommentedOutCode.Found.
				'default'   => 'full',
				'separator' => 'none',
				'condition' => array(
					'rael_image_type' => 'photo',
					'rael_photo_type' => 'media',
				),
			)
		);

		// End of section for Image Background color if custom design enabled.
		$this->end_controls_section();
	}

	/**
	 * Check if elementor is updated.
	 *
	 * @static
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return boolean
	 */
	public static function is_elementor_updated() {
		if ( class_exists( 'Elementor\Icons_Manager' ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Return new icon name
	 *
	 * @static
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @param string $icon Icon name.
	 * @return string New Icon name.
	 */
	public static function get_new_icon_name( $icon ) {
		if ( class_exists( 'Elementor\Icons_Manager' ) ) {
			return 'rael_new_' . $icon . '[value]';
		} else {
			return 'rael_' . $icon . '[value]';
		}
	}

	/**
	 * Add Separator controls section under the Content TAB
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_separator_content_controls() {
		$this->start_controls_section(
			'rael_separator_section',
			array(
				'label' => __( 'Separator', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_toggle_separator',
			array(
				'label'        => __( 'Separator', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add CTA controls section under the Content TAB
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_cta_content_controls() {
		$this->start_controls_section(
			'rael_cta_section',
			array(
				'label' => __( 'Call To Action', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_cta_type',
			array(
				'label'       => __( 'Type', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'none',
				'label_block' => false,
				'options'     => array(
					'none'   => __( 'None', 'responsive-addons-for-elementor' ),
					'link'   => __( 'Text', 'responsive-addons-for-elementor' ),
					'button' => __( 'Button', 'responsive-addons-for-elementor' ),
					'module' => __( 'Complete Box', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_link_text',
			array(
				'label'     => __( 'Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Read More', 'responsive-addons-for-elementor' ),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'rael_cta_type' => 'link',
				),
			)
		);

		$this->add_control(
			'rael_button_text',
			array(
				'label'     => __( 'Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Click Here', 'responsive-addons-for-elementor' ),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'rael_cta_type' => 'button',
				),
			)
		);

		$this->add_control(
			'rael_text_link',
			array(
				'label'         => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'          => Controls_Manager::URL,
				'default'       => array(
					'url'         => '#',
					'is_external' => '',
				),
				'dynamic'       => array(
					'active' => true,
				),
				'show_external' => true, // Show the 'open in new tab' button.
				'condition'     => array(
					'rael_cta_type!' => 'none',
				),
				'selector'      => '{{WRAPPER}} a.elementor-button-link, {{WRAPPER}} a.rael-infobox__cta-link, {{WRAPPER}} a.rael-infobox-module-link',
			)
		);

		$this->add_control(
			'rael_button_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'sm',
				'options'   => array(
					'xs' => __( 'Extra Small', 'responsive-addons-for-elementor' ),
					'sm' => __( 'Small', 'responsive-addons-for-elementor' ),
					'md' => __( 'Medium', 'responsive-addons-for-elementor' ),
					'lg' => __( 'Large', 'responsive-addons-for-elementor' ),
					'xl' => __( 'Extra Large', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_cta_type' => 'button',
				),
			)
		);

		$this->add_control(
			'rael_icon_structure',
			array(
				'label'     => __( 'Icon', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_cta_type' => array( 'button', 'link' ),
				),
			)
		);

		if ( self::is_elementor_updated() ) {

			$this->add_control(
				'rael_new_button_icon',
				array(
					'label'            => __( 'Select Icon', 'responsive-addons-for-elementor' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => 'rael_button_icon',
					'condition'        => array(
						'rael_cta_type' => array( 'button', 'link' ),
					),
					'render_type'      => 'template',
				)
			);
		} else {
			$this->add_control(
				'rael_button_icon',
				array(
					'label'       => __( 'Select Icon', 'responsive-addons-for-elementor' ),
					'type'        => Controls_Manager::ICON,
					'condition'   => array(
						'rael_cta_type' => array( 'button', 'link' ),
					),
					'render_type' => 'template',
				)
			);
		}
		$this->add_control(
			'rael_button_icon_position',
			array(
				'label'       => __( 'Icon Position', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'right',
				'label_block' => false,
				'options'     => array(
					'right' => __( 'After Text', 'responsive-addons-for-elementor' ),
					'left'  => __( 'Before Text', 'responsive-addons-for-elementor' ),
				),
				'condition'   => array(
					'rael_cta_type' => array( 'button', 'link' ),
				),
			)
		);
		$this->add_control(
			'rael_icon_spacing',
			array(
				'label'     => __( 'Icon Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'   => array(
					'size' => '5',
					'unit' => 'px',
				),
				'condition' => array(
					'rael_cta_type' => array( 'button', 'link' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-button .elementor-align-icon-right,{{WRAPPER}} .rael-infobox__link-icon--after' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button .elementor-align-icon-left, {{WRAPPER}} .rael-infobox__link-icon--before' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button .elementor-button-content-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add register button style controls
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_button_style_controls() {
		$this->start_controls_section(
			'rael_button_style_section',
			array(
				'label'     => __( 'CTA Button', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_cta_type' => 'button',
				),
			)
		);

		$this->add_control(
			'rael_button_colors',
			array(
				'label'     => __( 'Colors', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'rael_tabs_button_style' );

		$this->start_controls_tab(
			'rael_button_normal_state',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_control(
			'rael_button_text_normal_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button, {{WRAPPER}} .elementor-button-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'rael_btn_normal_background_color',
				'label'          => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'selector'       => '{{WRAPPER}} .elementor-button',
				'fields_options' => array(
					'color' => array(
						'global'   => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
					),
				),
			)
		);

		$this->add_control(
			'rael_button_border',
			array(
				'label'       => __( 'Border Style', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'none',
				'label_block' => false,
				'options'     => array(
					'none'   => __( 'None', 'responsive-addons-for-elementor' ),
					'solid'  => __( 'Solid', 'responsive-addons-for-elementor' ),
					'double' => __( 'Double', 'responsive-addons-for-elementor' ),
					'dotted' => __( 'Dotted', 'responsive-addons-for-elementor' ),
					'dashed' => __( 'Dashed', 'responsive-addons-for-elementor' ),
				),
				'selectors'   => array(
					'{{WRAPPER}} .elementor-button' => 'border-style: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_button_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'rael_button_border!' => 'none',
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-button' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_button_border_size',
			array(
				'label'      => __( 'Border Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'default'    => array(
					'top'    => '1',
					'bottom' => '1',
					'left'   => '1',
					'right'  => '1',
					'unit'   => 'px',
				),
				'condition'  => array(
					'rael_cta_type'       => 'button',
					'rael_button_border!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_button_radius',
			array(
				'label'      => __( 'Rounded Corners', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'    => '0',
					'bottom' => '0',
					'left'   => '0',
					'right'  => '0',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_button_normal_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-button',
			)
		);

		$this->add_responsive_control(
			'rael_button_custom_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_button_hover_state',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_control(
			'rael_button_hover_color',
			array(
				'label'     => __( 'Text Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'rael_button_hover_bgcolor',
				'label'          => __( 'Background Hover Color', 'responsive-addons-for-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'selector'       => '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover',
				'fields_options' => array(
					'color' => array(
						'global'   => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
					),
				),
			)
		);

		$this->add_control(
			'rael_button_border_hover_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'rael_button_border!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_cta_hover_box_shadow',
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-infobox-button-wrapper .elementor-button-link:hover',
			)
		);

		$this->add_control(
			'rael_button_animation',
			array(
				'label'    => __( 'Hover Animation', 'responsive-addons-for-elementor' ),
				'type'     => Controls_Manager::HOVER_ANIMATION,
				'selector' => '{{WRAPPER}} .elementor-button',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Add register seperator style controls
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_seperator_style_controls() {
		$this->start_controls_section(
			'rael_seperator_style_section',
			array(
				'label'     => __( 'Seperator', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_toggle_separator' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_separator_position',
			array(
				'label'       => __( 'Position', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'default'     => 'after_heading',
				'options'     => array(
					'after_icon'        => __( 'After Icon', 'responsive-addons-for-elementor' ),
					'after_heading'     => __( 'After Heading', 'responsive-addons-for-elementor' ),
					'after_description' => __( 'After Description', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_separator_style',
			array(
				'label'       => __( 'Style', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'solid',
				'label_block' => false,
				'options'     => array(
					'solid'  => __( 'Solid', 'responsive-addons-for-elementor' ),
					'dashed' => __( 'Dashed', 'responsive-addons-for-elementor' ),
					'dotted' => __( 'Dotted', 'responsive-addons-for-elementor' ),
					'double' => __( 'Double', 'responsive-addons-for-elementor' ),
				),
				'selectors'   => array(
					'{{WRAPPER}} .rael-infobox__separator' => 'border-top-style: {{VALUE}}; display: inline-block;',
				),
			)
		);

		$this->add_control(
			'rael_separator_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'   => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-infobox__separator' => 'border-top-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_separator_thickness',
			array(
				'label'      => __( 'Thickness', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => 3,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-infobox__separator' => 'border-top-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_separator_width',
			array(
				'label'          => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'range'          => array(
					'px' => array(
						'max' => 1000,
					),
				),
				'default'        => array(
					'size' => 30,
					'unit' => '%',
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'label_block'    => true,
				'selectors'      => array(
					'{{WRAPPER}} .rael-infobox__separator' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add register icon style controls
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_icon_style_controls() {
		$this->start_controls_section(
			'rael_icon_style_section',
			array(
				'label' => __( 'Icon', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_icon_size',
			array(
				'label'      => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => 40,
					'unit' => 'px',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => self::get_new_icon_name( 'select_icon' ),
							'operator' => '!=',
							'value'    => '',
						),
						array(
							'name'     => 'rael_image_type',
							'operator' => '==',
							'value'    => 'icon',
						),
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-infobox__icon i'   => 'font-size: {{SIZE}}{{UNIT}}; text-align: center;',
					'{{WRAPPER}} .rael-infobox__icon svg' => 'height: {{SIZE}}{{UNIT}}; text-align: center;',
				),
			)
		);

		$this->add_control(
			'rael_icon_rotate',
			array(
				'label'      => __( 'Rotate', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 0,
					'unit' => 'deg',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-infobox__icon i,
					{{WRAPPER}} .rael-infobox__icon svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => self::get_new_icon_name( 'select_icon' ),
							'operator' => '!=',
							'value'    => '',
						),
						array(
							'name'     => 'rael_image_type',
							'operator' => '==',
							'value'    => 'icon',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_image_position',
			array(
				'label'       => __( 'Select Position', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'above-title',
				'label_block' => false,
				'options'     => array(
					'above-title' => __( 'Above Heading', 'responsive-addons-for-elementor' ),
					'below-title' => __( 'Below Heading', 'responsive-addons-for-elementor' ),
					'left-title'  => __( 'Left of Heading', 'responsive-addons-for-elementor' ),
					'right-title' => __( 'Right of Heading', 'responsive-addons-for-elementor' ),
					'left'        => __( 'Left of Text and Heading', 'responsive-addons-for-elementor' ),
					'right'       => __( 'Right of Text and Heading', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_imgicon_style',
			array(
				'label'       => __( 'Image/Icon Style', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'normal',
				'label_block' => false,
				'options'     => array(
					'normal' => __( 'Normal', 'responsive-addons-for-elementor' ),
					'circle' => __( 'Circle Background', 'responsive-addons-for-elementor' ),
					'square' => __( 'Square / Rectangle Background', 'responsive-addons-for-elementor' ),
					'custom' => __( 'Design your own', 'responsive-addons-for-elementor' ),
				),
				'condition'   => array(
					'rael_image_type!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'rael_image_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 2000,
					),
				),
				'default'    => array(
					'size' => 150,
					'unit' => 'px',
				),
				'condition'  => array(
					'rael_image_type' => 'photo',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-infobox__image img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_icon_bg_size',
			array(
				'label'      => __( 'Background Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => 20,
					'unit' => 'px',
				),
				'condition'  => array(
					'rael_image_type'     => array( 'icon', 'photo' ),
					'rael_imgicon_style!' => 'normal',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-infobox__icon, {{WRAPPER}} .rael-infobox__image-content img' => 'padding: {{SIZE}}{{UNIT}}; display:inline-block; box-sizing:content-box;',
				),
			)
		);

		$this->start_controls_tabs( 'rael_tabs_icon_style' );

		$this->start_controls_tab(
			'rael_icon_normal',
			array(
				'label'     => __( 'Normal', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_image_type' => array( 'icon', 'photo' ),
				),
			)
		);
		$this->add_control(
			'rael_icon_color',
			array(
				'label'      => __( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'global'     => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => self::get_new_icon_name( 'select_icon' ),
							'operator' => '!=',
							'value'    => '',
						),
						array(
							'name'     => 'rael_image_type',
							'operator' => '==',
							'value'    => 'icon',
						),
					),
				),
				'default'    => '',
				'selectors'  => array(
					'{{WRAPPER}} .rael-infobox__icon i, {{WRAPPER}} .rael-infobox__icon svg' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-infobox__icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_icon_bgcolor',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'default'   => '',
				'condition' => array(
					'rael_image_type' => array( 'icon', 'photo' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-infobox:not(.rael-infobox__imgicon-style--normal) .rael-infobox__icon, {{WRAPPER}} .rael-infobox:not(.rael-infobox__imgicon-style--normal) .rael-infobox__image .rael-infobox__image-content img' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_icon_border',
			array(
				'label'       => __( 'Border Style', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'none',
				'label_block' => false,
				'options'     => array(
					'none'   => __( 'None', 'responsive-addons-for-elementor' ),
					'solid'  => __( 'Solid', 'responsive-addons-for-elementor' ),
					'double' => __( 'Double', 'responsive-addons-for-elementor' ),
					'dotted' => __( 'Dotted', 'responsive-addons-for-elementor' ),
					'dashed' => __( 'Dashed', 'responsive-addons-for-elementor' ),
				),
				'condition'   => array(
					'rael_image_type' => array( 'icon', 'photo' ),
				),
				'selectors'   => array(
					'{{WRAPPER}} .rael-infobox__icon-wrapper .rael-infobox__icon, {{WRAPPER}} .rael-infobox__icon-wrapper .rael-infobox__image-content img' => 'border-style: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_icon_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'condition' => array(
					'rael_image_type'   => array( 'icon', 'photo' ),
					'rael_icon_border!' => 'none',
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-infobox__icon-wrapper .rael-infobox__icon, {{WRAPPER}} .rael-infobox__icon-wrapper .rael-infobox__image-content img' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_icon_border_size',
			array(
				'label'      => __( 'Border Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'default'    => array(
					'top'    => '1',
					'bottom' => '1',
					'left'   => '1',
					'right'  => '1',
					'unit'   => 'px',
				),
				'condition'  => array(
					'rael_image_type'   => array( 'icon', 'photo' ),
					'rael_icon_border!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-infobox__icon-wrapper .rael-infobox__icon, {{WRAPPER}} .rael-infobox__icon-wrapper .rael-infobox__image-content img' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; box-sizing:content-box;',
				),
			)
		);

		$this->add_responsive_control(
			'rael_icon_border_radius',
			array(
				'label'      => __( 'Rounded Corners', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'    => '5',
					'bottom' => '5',
					'left'   => '5',
					'right'  => '5',
					'unit'   => 'px',
				),
				'condition'  => array(
					'rael_image_type'   => array( 'icon', 'photo' ),
					'rael_icon_border!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-infobox__icon-wrapper .rael-infobox__icon, {{WRAPPER}} .rael-infobox__icon-wrapper .rael-infobox__image-content img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; box-sizing:content-box;',
				),
			)
		);

		$this->add_control(
			'rael_css_filters_heading',
			array(
				'label'     => __( 'Image Style', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_image_type'     => 'photo',
					'rael_imgicon_style!' => 'normal',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'      => 'rael_css_filters',
				'selector'  => '{{WRAPPER}} .rael-infobox__image-content img',
				'condition' => array(
					'rael_image_type'     => 'photo',
					'rael_imgicon_style!' => 'normal',
				),
			)
		);

		$this->add_control(
			'rael_image_opacity',
			array(
				'label'     => __( 'Image Opacity', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-infobox__image-content img' => 'opacity: {{SIZE}};',
				),
				'condition' => array(
					'rael_image_type'     => 'photo',
					'rael_imgicon_style!' => 'normal',
				),
			)
		);

		$this->add_control(
			'rael_background_hover_transition',
			array(
				'label'     => __( 'Transition Duration', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 0.3,
				),
				'range'     => array(
					'px' => array(
						'max'  => 3,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-infobox__image-content img' => 'transition-duration: {{SIZE}}s',
				),
				'condition' => array(
					'rael_image_type'     => 'photo',
					'rael_imgicon_style!' => 'normal',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_icon_hover_state',
			array(
				'label'     => __( 'Hover', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_image_type' => array( 'icon', 'photo' ),
				),
			)
		);
		$this->add_control(
			'rael_icon_hover_color_imgicon_not_normal',
			array(
				'label'      => __( 'Icon Hover Color', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => self::get_new_icon_name( 'select_icon' ),
							'operator' => '!=',
							'value'    => '',
						),
						array(
							'name'     => 'rael_image_type',
							'operator' => '==',
							'value'    => 'icon',
						),
					),
				),
				'default'    => '',
				'selectors'  => array(
					'{{WRAPPER}} .rael-infobox:hover .rael-infobox__icon > i, {{WRAPPER}} .rael-infobox:hover .rael-infobox-link-type-module .rael-infobox-module-link ~ .rael-infobox__content .rael-infobox__imgicon-wrapper i, {{WRAPPER}} .rael-infobox:hover .rael-infobox-link-type-module .rael-infobox-module-link ~ .rael-infobox__imgicon-wrapper i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-infobox:hover .rael-infobox__icon > svg, {{WRAPPER}} .rael-infobox:hover .rael-infobox-link-type-module .rael-infobox-module-link ~ .rael__infobox-content .rael-infobox__imgicon-wrapper svg, {{WRAPPER}} .rael-infobox:hover .rael-infobox-link-type-module .rael-infobox-module-link ~ .rael-infobox__imgicon-wrapper svg' => 'fill: {{VALUE}}; color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_icon_hover_bgcolor',
			array(
				'label'     => __( 'Background Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'rael_image_type'     => array( 'icon', 'photo' ),
					'rael_imgicon_style!' => 'normal',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-infobox:hover .rael-infobox__icon-wrapper .rael-infobox__icon, {{WRAPPER}} .rael-infobox__image-content img, {{WRAPPER}} .rael-infobox:hover .rael-infobox-link-type-module .rael-infobox-module-link ~ .rael-infobox__content .rael-infobox__imgicon-wrapper .rael-infobox__icon, {{WRAPPER}} .rael-infobox:hover .rael-infobox-link-type-module .rael-infobox-module-link ~ .rael-infobox__imgicon-wrapper .rael-infobox__icon,
					{{WRAPPER}} .rael-infobox:hover .rael-infobox-link-type-module .rael-infobox-module-link ~ .rael-infobox__image .rael-infobox__image-content img, {{WRAPPER}} .rael-infobox:hover .rael-infobox-link-type-module .rael-infobox-module-link ~ .rael-infobox__imgicon-wrapper img,{{WRAPPER}} .rael-infobox:hover .rael-infobox:not(.rael-infobox__imgicon-style--normal) .rael-infobox__icon-wrapper .rael-infobox__icon,
					{{WRAPPER}} .rael-infobox:hover .rael-infobox:not(.rael-infobox__imgicon-style--normal) .rael-infobox__image .rael-infobox__image-content img' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_icon_border_hover',
			array(
				'label'       => __( 'Border Style', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'none',
				'label_block' => false,
				'options'     => array(
					'none'   => __( 'None', 'responsive-addons-for-elementor' ),
					'solid'  => __( 'Solid', 'responsive-addons-for-elementor' ),
					'double' => __( 'Double', 'responsive-addons-for-elementor' ),
					'dotted' => __( 'Dotted', 'responsive-addons-for-elementor' ),
					'dashed' => __( 'Dashed', 'responsive-addons-for-elementor' ),
				),
				'condition'   => array(
					'rael_image_type' => array( 'icon', 'photo' ),
				),
				'selectors'   => array(
					'{{WRAPPER}} .rael-infobox:hover .rael-infobox__icon-wrapper .rael-infobox__icon,
					{{WRAPPER}} .rael-infobox:hover .rael-infobox__icon-wrapper .rael-infobox__image-content img' => 'border-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_icon_hover_border',
			array(
				'label'     => __( 'Border Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'rael_image_type'         => array( 'icon', 'photo' ),
					'rael_icon_border_hover!' => 'none',
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-infobox:hover .rael-infobox__icon-wrapper .rael-infobox__icon,
					{{WRAPPER}} .rael-infobox:hover .rael-infobox__image-content img,
					{{WRAPPER}} .rael-infobox:hover .rael-infobox-link-type-module .rael-infobox-module-link ~ .rael-infobox__content .rael-infobox__imgicon-wrapper .rael-infobox__icon,
					{{WRAPPER}} .rael-infobox:hover .rael-infobox-link-type-module .rael-infobox-module-link ~ .rael-infobox__imgicon-wrapper .rael-infobox__icon,
					{{WRAPPER}} .rael-infobox:hover .rael-infobox-link-type-module .rael-infobox-module-link ~ .rael-infobox__image .rael-infobox__image-content img,
					{{WRAPPER}} .rael-infobox:hover .rael-infobox-link-type-module .rael-infobox-module-link ~ .rael-infobox__imgicon-wrapper img ' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_icon_border_size_hover',
			array(
				'label'      => __( 'Border Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'default'    => array(
					'top'    => '1',
					'bottom' => '1',
					'left'   => '1',
					'right'  => '1',
					'unit'   => 'px',
				),
				'condition'  => array(
					'rael_image_type'         => array( 'icon', 'photo' ),
					'rael_icon_border_hover!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-infobox:hover .rael-infobox__icon-wrapper .rael-infobox__icon,
					{{WRAPPER}} .rael-infobox:hover .rael-infobox__icon-wrapper .rael-infobox__image-content img' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; box-sizing:content-box;',
				),
			)
		);

		$this->add_responsive_control(
			'rael_icon_border_radius_hover',
			array(
				'label'      => __( 'Rounded Corners', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'    => '5',
					'bottom' => '5',
					'left'   => '5',
					'right'  => '5',
					'unit'   => 'px',
				),
				'condition'  => array(
					'rael_image_type'         => array( 'icon', 'photo' ),
					'rael_icon_border_hover!' => 'none',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-infobox:hover .rael-infobox__icon-wrapper .rael-infobox__icon, {{WRAPPER}} .rael-infobox:hover .rael-infobox__icon-wrapper .rael-infobox__image-content img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; box-sizing:content-box;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'      => 'rael_hover_css_filters',
				'selector'  => '{{WRAPPER}} .rael-infobox:hover .rael-infobox__image-content img,
				{{WRAPPER}} .rael-infobox:hover .rael-infobox-link-type-module .rael-infobox-module-link ~ .rael-infobox__imgicon-wrapper .rael-infobox__icon,
				{{WRAPPER}} .rael-infobox:hover .rael-infobox-link-type-module .rael-infobox-module-link ~ .rael-infobox__image .rael-infobox__image-content img,
				{{WRAPPER}} .rael-infobox:hover .rael-infobox-link-type-module .rael-infobox-module-link ~ .rael-infobox__imgicon-wrapper img',
				'condition' => array(
					'rael_image_type' => 'photo',
				),
			)
		);

		$this->add_control(
			'rael_hover_image_opacity',
			array(
				'label'     => __( 'Opacity', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-infobox:hover .rael-infobox__image-content img,
					{{WRAPPER}} .rael-infobox:hover .rael-infobox-link-type-module .rael-infobox-module-link ~ .rael-infobox__imgicon-wrapper .rael-infobox__icon,
					{{WRAPPER}} .rael-infobox:hover .rael-infobox-link-type-module .rael-infobox-module-link ~ .rael-infobox__image .rael-infobox__image-content img,
					{{WRAPPER}} .rael-infobox:hover .rael-infobox-link-type-module .rael-infobox-module-link ~ .rael-infobox__imgicon-wrapper img' => 'opacity: {{SIZE}};',
				),
				'condition' => array(
					'rael_image_type' => 'photo',
				),
			)
		);

		$this->add_control(
			'rael_imgicon_animation',
			array(
				'label'     => __( 'Hover Animation', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HOVER_ANIMATION,
				'condition' => array(
					'rael_image_type' => array( 'icon', 'photo' ),
				),
			)
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'rael_normal_css_filters_heading',
			array(
				'label'     => __( 'Image Style', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_image_type'    => 'photo',
					'rael_imgicon_style' => 'normal',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'      => 'rael_normal_css_filters',
				'selector'  => '{{WRAPPER}} .rael-infobox__image .rael-infobox__image-content img',
				'condition' => array(
					'rael_image_type'    => 'photo',
					'rael_imgicon_style' => 'normal',
				),
			)
		);

		$this->add_control(
			'rael_normal_image_opacity',
			array(
				'label'     => __( 'Image Opacity', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-infobox__image .rael-infobox__image-content img' => 'opacity: {{SIZE}};',
				),
				'condition' => array(
					'rael_image_type'    => 'photo',
					'rael_imgicon_style' => 'normal',
				),
			)
		);

		$this->add_control(
			'rael_normal_bg_hover_transition',
			array(
				'label'     => __( 'Transition Duration', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 0.3,
				),
				'range'     => array(
					'px' => array(
						'max'  => 3,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-infobox__image .rael-infobox__image-content img' => 'transition-duration: {{SIZE}}s',
				),
				'condition' => array(
					'rael_image_type'    => 'photo',
					'rael_imgicon_style' => 'normal',
				),
			)
		);

		$this->add_control(
			'rael_image_responsive_support',
			array(
				'label'       => __( 'Responsive Support', 'responsive-addons-for-elementor' ),
				'description' => __( 'Choose on what breakpoint the Iconbox will stack.', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'tablet',
				'options'     => array(
					'none'   => __( 'No', 'responsive-addons-for-elementor' ),
					'tablet' => __( 'For Tablet & Mobile ', 'responsive-addons-for-elementor' ),
					'mobile' => __( 'For Mobile Only', 'responsive-addons-for-elementor' ),
				),
				'condition'   => array(
					'rael_image_type'     => array( 'icon', 'photo' ),
					'rael_image_position' => array( 'left', 'right' ),
				),
			)
		);

		$this->add_control(
			'rael_image_valign',
			array(
				'label'       => __( 'Vertical Alignment', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'top'    => array(
						'title' => __( 'Top', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					),
				),
				'default'     => 'top',
				'condition'   => array(
					'rael_image_type'     => array( 'icon', 'photo' ),
					'rael_image_position' => array( 'left-title', 'right-title', 'left', 'right' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_overall_align',
			array(
				'label'     => __( 'Overall Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'condition' => array(
					'rael_image_type!' => array( 'icon', 'photo' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-infobox, {{WRAPPER}} .rael-infobox__separator-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add register icon style controls
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_infobox_container_style_controls() {
		$this->start_controls_section(
			'rael_infobox_container',
			array(
				'label' => __( 'Icon Box Container', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_align',
			array(
				'label'     => __( 'Overall Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'center',
				'condition' => array(
					'rael_image_type'     => array( 'icon', 'photo' ),
					'rael_image_position' => array( 'above-title', 'below-title' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-infobox,  {{WRAPPER}} .rael-infobox__separator-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'rael_icon_box_style_background_tab' );
		$this->start_controls_tab(
			'rael_icon_box_section_background_style_n_tab',
			array(
				'label' => esc_html__( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_infobox_bg_group',
				'label'    => esc_html__( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient', 'video' ),
				'selector' => '{{WRAPPER}} .rael-infobox',
			)
		);
		$this->add_responsive_control(
			'rael_infobox_bg_padding',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'    => '50',
					'right'  => '40',
					'bottom' => '50',
					'left'   => '40',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-infobox' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_infobox_box_shadow_group',
				'label'    => esc_html__( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-infobox',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_infobox_border_group',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-infobox',
			)
		);
		$this->add_responsive_control(
			'rael_infobox_border_radious',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-infobox' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'rael_infobox_section_background_style_n_hv_tab',
			array(
				'label' => esc_html__( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_infobox_bg_hover_group',
				'label'    => esc_html__( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient', 'video' ),
				'selector' => '{{WRAPPER}} .rael-infobox:hover',
			)
		);

		$this->add_control(
			'rael_title_hover_color',
			array(
				'label'     => __( 'Title Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-infobox:hover  .rael-infobox__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_description_hover_color',
			array(
				'label'     => __( 'Description Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-infobox:hover .rael-infobox__content .rael-infobox__description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_infobox_bg_padding_inner',
			array(
				'label'      => esc_html__( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),

				'selectors'  => array(
					'{{WRAPPER}} .rael-infobox:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_infobox_box_shadow_hv_group',
				'label'    => esc_html__( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-infobox:hover',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_icon_box_border_hv_group',
				'label'    => esc_html__( 'Border', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-infobox:hover',
			)
		);

		$this->add_responsive_control(
			'rael_infobox_border_radious_hv',
			array(
				'label'      => esc_html__( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-infobox:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'rael_info_box_hover_animation',
			array(
				'label' => esc_html__( 'Hover Animation', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Add Typography controls section under the Style TAB
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_typography_style_controls() {
		$this->start_controls_section(
			'rael_typography_section',
			array(
				'label' => __( 'Typography', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_title_typo',
			array(
				'label'     => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'rael_title!' => '',
				),
			)
		);
		$this->add_control(
			'rael_title_tag',
			array(
				'label'     => __( 'Title Tag', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h1'  => __( 'H1', 'responsive-addons-for-elementor' ),
					'h2'  => __( 'H2', 'responsive-addons-for-elementor' ),
					'h3'  => __( 'H3', 'responsive-addons-for-elementor' ),
					'h4'  => __( 'H4', 'responsive-addons-for-elementor' ),
					'h5'  => __( 'H5', 'responsive-addons-for-elementor' ),
					'h6'  => __( 'H6', 'responsive-addons-for-elementor' ),
					'div' => __( 'div', 'responsive-addons-for-elementor' ),
					'p'   => __( 'p', 'responsive-addons-for-elementor' ),
				),
				'default'   => 'h3',
				'condition' => array(
					'rael_title!' => '',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_title_typography',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector'  => '{{WRAPPER}} .rael-infobox__title',
				'condition' => array(
					'rael_title!' => '',
				),
			)
		);
		$this->add_control(
			'rael_title_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'default'   => '',
				'condition' => array(
					'rael_title!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-infobox__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_blend_mode',
			array(
				'label'     => __( 'Blend Mode', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''            => __( 'Normal', 'responsive-addons-for-elementor' ),
					'multiply'    => 'Multiply',
					'screen'      => 'Screen',
					'overlay'     => 'Overlay',
					'darken'      => 'Darken',
					'lighten'     => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation'  => 'Saturation',
					'color'       => 'Color',
					'difference'  => 'Difference',
					'exclusion'   => 'Exclusion',
					'hue'         => 'Hue',
					'luminosity'  => 'Luminosity',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-infobox__title' => 'mix-blend-mode: {{VALUE}}',
				),
				'separator' => 'none',
			)
		);

		$this->add_control(
			'rael_description_typo',
			array(
				'label'     => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_description!' => '',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_description_typography',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector'  => '{{WRAPPER}} .rael-infobox__description',
				'condition' => array(
					'rael_description!' => '',
				),
			)
		);
		$this->add_control(
			'rael_description_color',
			array(
				'label'     => __( 'Description Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'   => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'default'   => '',
				'condition' => array(
					'rael_description!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-infobox__description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_link_typo',
			array(
				'label'     => __( 'CTA Link Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_cta_type' => 'link',
				),
			)
		);

		$this->add_control(
			'rael_button_typo',
			array(
				'label'     => __( 'CTA Button Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_cta_type' => 'button',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_cta_typography',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector'  => '{{WRAPPER}} .rael-infobox__cta-link, {{WRAPPER}} .elementor-button, {{WRAPPER}} a.elementor-button',
				'condition' => array(
					'rael_cta_type' => array( 'link', 'button' ),
				),
			)
		);
		$this->add_control(
			'rael_cta_color',
			array(
				'label'     => __( 'Link Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-infobox__cta-link' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_cta_type' => 'link',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add Margin controls section under the Style TAB
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_margin_style_controls() {
		$this->start_controls_section(
			'rael_margin_section',
			array(
				'label' => __( 'Margins', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'rael_title_margin',
			array(
				'label'      => __( 'Title Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'default'    => array(
					'top'      => '0',
					'bottom'   => '0',
					'left'     => '0',
					'right'    => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-infobox__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_title!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'rael_responsive_imgicon_margin',
			array(
				'label'      => __( 'Image/Icon Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'condition'  => array(
					'rael_image_type' => array( 'icon', 'photo' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-infobox__imgicon-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_description_margin',
			array(
				'label'      => __( 'Description Margins', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'default'    => array(
					'top'      => '0',
					'bottom'   => '0',
					'left'     => '0',
					'right'    => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'condition'  => array(
					'rael_description!' => '',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-infobox__description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_separator_margin',
			array(
				'label'      => __( 'Separator Margins', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'default'    => array(
					'top'      => '10',
					'bottom'   => '10',
					'left'     => '0',
					'right'    => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'condition'  => array(
					'rael_toggle_separator' => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-infobox__separator' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),

			)
		);
		$this->add_responsive_control(
			'rael_cta_margin',
			array(
				'label'      => __( 'CTA Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'default'    => array(
					'top'      => '10',
					'bottom'   => '0',
					'left'     => '0',
					'right'    => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-infobox__cta-link-style, {{WRAPPER}} .rael-infobox-button-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_cta_type' => array( 'link', 'button' ),
				),
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Render in the frontend
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$html     = $this->widget_template( $settings );
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Renders widget template
	 *
	 * @param object $settings Settings for the widget.
	 * @return string HTML for the widget.
	 */
	protected function widget_template( $settings ) {
		$dynamic_settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'rael_infobox_classname', 'class', 'rael-infobox-widget-content rael-infobox' );

		if ( 'icon' === $settings['rael_image_type'] || 'photo' === $settings['rael_image_type'] ) {

			$this->add_render_attribute( 'rael_infobox_classname', 'class', ' rael-infobox__imgicon-style--' . $settings['rael_imgicon_style'] );
			$this->add_render_attribute( 'rael_infobox_classname', 'class', ' elementor-animation-' . $settings['rael_info_box_hover_animation'] );

			if ( 'above-title' === $settings['rael_image_position'] || 'below-title' === $settings['rael_image_position'] ) {
				$align = isset( $settings['rael_align'] ) ? $settings['rael_align'] : 'center';
    			$this->add_render_attribute( 'rael_infobox_classname', 'class', ' rael-infobox--' . $align );
			}
			if ( 'left-title' === $settings['rael_image_position'] || 'left' === $settings['rael_image_position'] ) {
				$this->add_render_attribute( 'rael_infobox_classname', 'class', ' rael-infobox--left' );
			}
			if ( 'right-title' === $settings['rael_image_position'] || 'right' === $settings['rael_image_position'] ) {
				$this->add_render_attribute( 'rael_infobox_classname', 'class', ' rael-infobox--right' );
			}
			if ( 'icon' === $settings['rael_image_type'] ) {
				$this->add_render_attribute( 'rael_infobox_classname', 'class', ' rael-infobox--has-icon rael-infobox--icon-' . $settings['rael_image_position'] );
			}
			if ( 'photo' === $settings['rael_image_type'] ) {
				$this->add_render_attribute( 'rael_infobox_classname', 'class', ' rael-infobox--has-photo rael-infobox--photo-' . $settings['rael_image_position'] );
			}
			if ( 'above-title' !== $settings['rael_image_position'] && 'below-title' !== $settings['rael_image_position'] ) {

				if ( 'middle' === $settings['rael_image_valign'] ) {
					$this->add_render_attribute( 'rael_infobox_classname', 'class', ' rael-infobox__image-valign--middle' );
				} else {
					$this->add_render_attribute( 'rael_infobox_classname', 'class', ' rael-infobox__image-valign--top' );
				}
			}
			if ( 'left' === $settings['rael_image_position'] || 'right' === $settings['rael_image_position'] ) {
				if ( 'tablet' === $settings['rael_image_responsive_support'] ) {
					$this->add_render_attribute( 'rael_infobox_classname', 'class', ' rael-infobox--view-tablet' );
				}
				if ( 'mobile' === $settings['rael_image_responsive_support'] ) {
					$this->add_render_attribute( 'rael_infobox_classname', 'class', ' rael-infobox--view-mobile' );
				}
			}
			if ( 'right' === $settings['rael_image_position'] ) {
				if ( 'tablet' === $settings['rael_image_responsive_support'] ) {
					$this->add_render_attribute( 'rael_infobox_classname', 'class', ' rael-infobox--reverse-order-tablet' );
				}
				if ( 'mobile' === $settings['rael_image_responsive_support'] ) {
					$this->add_render_attribute( 'rael_infobox_classname', 'class', ' rael-infobox--reverse-order-mobile' );
				}
			}
		} else {
			if ( 'left' === $settings['rael_overall_align'] || 'center' === $settings['rael_overall_align'] || 'right' === $settings['rael_overall_align'] ) {
				$classname = ' rael-infobox--' . $settings['rael_overall_align'];
				$this->add_render_attribute( 'rael_infobox_classname', 'class', $classname );
			}
		}

		$this->add_render_attribute( 'rael_infobox_classname', 'class', ' rael-infobox-link-type-' . $settings['rael_cta_type'] );
		ob_start();
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_infobox_classname' ) ); ?>>
			<div class="rael-infobox-left-right-wrapper">
				<?php
				if ( 'module' === $settings['rael_cta_type'] && '' !== $settings['rael_text_link'] ) {
					$_nofollow          = ( 'on' === $dynamic_settings['rael_text_link']['nofollow'] ) ? '1' : '0';
					$_target            = ( 'on' === $dynamic_settings['rael_text_link']['is_external'] ) ? '_blank' : '';
					$_link              = ( isset( $dynamic_settings['rael_text_link']['url'] ) ) ? $dynamic_settings['rael_text_link']['url'] : '';
					$_custom_attributes = ( isset( $dynamic_settings['rael_text_link']['custom_attributes'] ) ) ? $dynamic_settings['rael_text_link']['custom_attributes'] : '';
					$_custom_attr_arr   = ( '' === $_custom_attributes ) ? array() : explode( ',', $_custom_attributes );
					?>
					<a href="<?php echo esc_url( $_link ); ?>"
					target="<?php echo esc_attr( $_target ); ?>" <?php self::get_link_rel( $_target, $_custom_attr_arr, $_nofollow, 1 ); ?>
					<?php
					if ( $_custom_attr_arr ) {
						foreach ( $_custom_attr_arr as $attr_pair ) {
							$attr_pair_arr = explode( '|', $attr_pair );
							$attr_key      = htmlspecialchars( trim( $attr_pair_arr[0] ) );
							$attr_val      = htmlspecialchars( trim( $attr_pair_arr[1] ) );
							if ( 'rel' !== $attr_key ) {
								echo ' ' . esc_attr( $attr_key ) . '="' . esc_attr( $attr_val ) . '" ';
							}
						}
					}

					?>
					class="rael-infobox-module-link"></a><?php //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php
				}
				?>
				<?php $this->render_image( 'left', $settings ); ?>
				<div class="rael-infobox__content">
					<?php $this->render_image( 'above-title', $settings ); ?>
					<?php
					if ( 'after_icon' === $settings['rael_separator_position'] ) {
						$this->render_separator( $settings );
					}
					?>
					<?php $this->render_title( $settings ); ?>
					<?php
					if ( 'after_heading' === $settings['rael_separator_position'] ) {
						$this->render_separator( $settings );
					}
					?>
					<?php
					$this->render_image( 'below-title', $settings );
					if ( ! empty( $settings['rael_description'] ) ) {
						?>
						<div class="rael-infobox__description-wrapper">
							<?php
							$this->add_render_attribute( 'rael_description', 'class', 'rael-infobox__description' );
							$this->add_inline_editing_attributes( 'rael_description', 'advanced' );
							?>
							<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_description' ) ); ?>>
								<?php echo wp_kses_post( $settings['rael_description'] ); ?>
							</div>
							<?php
							if ( 'after_description' === $settings['rael_separator_position'] ) {
								$this->render_separator( $settings );
								?>
							<?php } ?>
						</div>
					<?php } ?>
					<?php $this->render_link( $settings ); ?>
				</div>
				<?php $this->render_image( 'right', $settings ); ?>
			</div>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 *  Get link rel attribute
	 *
	 * @param string $target Target attribute to the link.
	 * @param array  $custom_attr_arr for custom attribute.
	 * @param int    $is_nofollow No follow yes/no.
	 * @param int    $echo Return or echo the output.
	 * @since 1.2.0
	 * @return string
	 */
	public static function get_link_rel( $target, $custom_attr_arr, $is_nofollow = 0, $echo = 0 ) {

		$attr       = '';
		$rel_custom = '';
		if ( $custom_attr_arr ) {
			foreach ( $custom_attr_arr as $attr_pair ) {
				$attr_pair_arr = explode( '|', $attr_pair );
				$attr_key      = htmlspecialchars( trim( $attr_pair_arr[0] ) );
				$attr_val      = htmlspecialchars( trim( $attr_pair_arr[1] ) );
				if ( 'rel' === $attr_key ) {
					$rel_custom .= ' ' . $attr_val;
				}
			}
		}
		if ( '_blank' === $target ) {
			$attr .= 'noopener';
		}

		if ( 1 === $is_nofollow ) {
			$attr .= ' nofollow';
		}

		if ( '' === $attr && '' === $rel_custom ) {
			return;
		}

		$attr       = trim( $attr );
		$rel_custom = trim( $rel_custom );
		$rel_string = trim( $attr . ' ' . $rel_custom );

		if ( ! $echo ) {
			return 'rel="' . $rel_string . '"';
		}
		echo 'rel="' . esc_attr( $rel_string ) . '"';
	}

	/**
	 * Renders RAEL Infobox Image or Icon
	 *
	 * @param string $position Position of the image.
	 * @param object $settings Control Settings of the widget.
	 * @access public
	 *
	 * @since 1.2.0
	 */
	public function render_image( $position, $settings ) {
		$set_pos    = '';
		$anim_class = '';
		$image_html = '';

		if ( 'icon' === $settings['rael_image_type'] || 'photo' === $settings['rael_image_type'] ) {
			$set_pos = $settings['rael_image_position'];
		}
		if ( $position === $set_pos ) {
			if ( 'icon' === $settings['rael_image_type'] || 'photo' === $settings['rael_image_type'] ) {

				if ( 'normal' !== $settings['rael_imgicon_style'] ) {
					$anim_class = 'elementor-animation-' . $settings['rael_imgicon_animation'];
				} elseif ( 'normal' === $settings['rael_imgicon_style'] ) {
					$anim_class = 'elementor-animation-' . $settings['rael_imgicon_animation'];
				}

				?>
				<div class="rael-infobox-widget-content rael-infobox__imgicon-wrapper"><?php /* Module Wrap */ ?>
					<?php
					/*Icon Html */
					if ( self::is_elementor_updated() ) {
						if ( ! isset( $settings['rael_select_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
							// add old default.
							$settings['rael_select_icon'] = 'fa fa-star';
						}
						$has_icon = ! empty( $settings['rael_select_icon'] );

						if ( ! $has_icon && ! empty( $settings['rael_new_select_icon']['value'] ) ) {
							$has_icon = true;
						}
						$migrated = isset( $settings['__fa4_migrated']['rael_new_select_icon'] );
						$is_new   = ! isset( $settings['rael_select_icon'] ) && Icons_Manager::is_migration_allowed();
						?>
						<?php if ( 'icon' === $settings['rael_image_type'] && $has_icon ) { ?>
							<div class="rael-infobox__icon-wrapper <?php echo esc_attr( $anim_class ); ?>">
								<div class="rael-infobox__icon">
									<?php
									if ( $is_new || $migrated ) {
										Icons_Manager::render_icon( $settings['rael_new_select_icon'], array( 'aria-hidden' => 'true' ) );
									} elseif ( ! empty( $settings['rael_select_icon'] ) ) {
										?>
										<i class="<?php echo esc_attr( $settings['rael_select_icon'] ); ?>"
											aria-hidden="true"></i>
									<?php } ?>
								</div>
							</div>
							<?php
						}
					} elseif ( 'icon' === $settings['rael_image_type'] ) {
						?>
						<div class="rael-infobox__icon-wrapper <?php echo esc_attr( $anim_class ); ?>">
							<div class="rael-infobox__icon">
								<i class="<?php echo esc_attr( $settings['rael_select_icon'] ); ?>"></i>
							</div>
						</div>
					<?php } // Icon Html End. ?>

					<?php /* Photo Html */ ?>
					<?php
					if ( 'photo' === $settings['rael_image_type'] ) {
						if ( 'media' === $settings['rael_photo_type'] ) {
							if ( ! empty( $settings['rael_image']['url'] ) ) {

								$this->add_render_attribute( 'rael_image', 'src', $settings['rael_image']['url'] );
								$this->add_render_attribute( 'rael_image', 'alt', Control_Media::get_image_alt( $settings['rael_image'] ) );
								$this->add_render_attribute( 'rael_image', 'title', Control_Media::get_image_title( $settings['rael_image'] ) );

								$image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'rael_image', 'rael_image' );
							}
						}
						if ( 'url' === $settings['rael_photo_type'] ) {
							if ( ! empty( $settings['rael_image_link'] ) ) {

								$this->add_render_attribute( 'rael_image_link', 'src', $settings['rael_image_link']['url'] );

								$image_html = '<img ' . $this->get_render_attribute_string( 'rael_image_link' ) . '>';
							}
						}
						?>
						<div class="rael-infobox__image" itemscope itemtype="http://schema.org/ImageObject">
							<div class="rael-infobox__image-content <?php echo esc_attr( $anim_class ); ?> ">
								<?php echo wp_kses_post( $image_html ); ?>
							</div>
						</div>

					<?php } // Photo Html End. ?>
				</div>
				<?php
			}
		}
	}

	/**
	 * Renders RAEL Infobox Title
	 *
	 * @param object $settings Control Settings of the widget.
	 * @access public
	 *
	 * @since 1.2.0
	 */
	public function render_title( $settings ) {
		$flag             = false;
		$dynamic_settings = $this->get_settings_for_display();

		if ( ( 'photo' === $settings['rael_image_type'] && 'left-title' === $settings['rael_image_position'] ) || ( 'icon' === $settings['rael_image_type'] && 'left-title' === $settings['rael_image_position'] ) ) {
			echo '<div class="rael-infobox-image--left-of-heading">';
			$flag = true;
		} elseif ( ( 'photo' === $settings['rael_image_type'] && 'right-title' === $settings['rael_image_position'] ) || ( 'icon' === $settings['rael_image_type'] && 'right-title' === $settings['rael_image_position'] ) ) {
			echo '<div class="rael-infobox-image--right-of-heading">';
			$flag = true;
		}

		$this->render_image( 'left-title', $settings );
		echo '<div class="rael-infobox__title-wrapper">';

		if ( ! empty( $dynamic_settings['rael_title'] ) ) {
			$this->add_render_attribute( 'rael_title', 'class', 'rael-infobox__title' );
			$this->add_inline_editing_attributes( 'rael_title', 'basic' );

			echo '<' . esc_attr( Helper::validate_html_tags( $settings['rael_title_tag'], 'h3' ) ) . ' ' . wp_kses_post( $this->get_render_attribute_string( 'rael_title' ) ) . '>';
			echo wp_kses_post( $dynamic_settings['rael_title'] );
			echo '</' . esc_attr( Helper::validate_html_tags( $settings['rael_title_tag'], 'h3' ) ) . '>';
		}
		echo '</div>';
		$this->render_image( 'right-title', $settings );

		if ( $flag ) {
			echo '</div>';
		}
	}

	/**
	 * Renders RAEL Infobox Separator element
	 *
	 * @param object $settings Control Settings of the widget.
	 * @access public
	 *
	 * @since 1.2.0
	 */
	public function render_separator( $settings ) {
		if ( 'yes' === $settings['rael_toggle_separator'] ) {
			?>
			<div class="rael-infobox__separator-wrapper">
				<div class="rael-infobox__separator"></div>
			</div>
			<?php
		}
	}

	/**
	 * Renders RAEL Infobox link
	 *
	 * @param object $settings Control Settings of the widget.
	 * @access public
	 *
	 * @since 1.2.0
	 */
	public function render_link( $settings ) {

		$dynamic_settings   = $this->get_settings_for_display();
		$_custom_attributes = ( isset( $dynamic_settings['rael_text_link']['custom_attributes'] ) ) ? $dynamic_settings['rael_text_link']['custom_attributes'] : '';
		$_custom_attr_arr   = ( '' === $_custom_attributes ) ? array() : explode( ',', $_custom_attributes );

		if ( 'link' === $settings['rael_cta_type'] ) {
			$_nofollow = ( 'on' === $dynamic_settings['rael_text_link']['nofollow'] ) ? '1' : '0';
			$_target   = ( 'on' === $dynamic_settings['rael_text_link']['is_external'] ) ? '_blank' : '';
			$_link     = ( isset( $dynamic_settings['rael_text_link']['url'] ) ) ? $dynamic_settings['rael_text_link']['url'] : '';
			?>
			<div class="rael-infobox__cta-link-style">
				<a href="<?php echo esc_url( $_link ); ?>" <?php self::get_link_rel( $_target, $_custom_attr_arr, $_nofollow, 1 ); ?>
					target="<?php echo esc_attr( $_target ); ?>"
					<?php
					if ( $_custom_attr_arr ) {
						foreach ( $_custom_attr_arr as $attr_pair ) {
							$attr_pair_arr = explode( '|', $attr_pair );
							$attr_key      = htmlspecialchars( trim( $attr_pair_arr[0] ) );
							$attr_val      = htmlspecialchars( trim( $attr_pair_arr[1] ) );
							if ( 'rel' !== $attr_key ) {
								echo ' ' . esc_attr( $attr_key ) . '="' . esc_attr( $attr_val ) . '" ';
							}
						}
					}

					?>
					class="rael-infobox__cta-link"> <?php //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php
					if ( 'left' === $settings['rael_button_icon_position'] ) {
						if ( self::is_elementor_updated() ) {
							if ( ( ! empty( $settings['rael_button_icon'] ) || ! empty( $settings['rael_new_button_icon'] ) ) ) {

								$migrated = isset( $settings['__fa4_migrated']['rael_new_button_icon'] );
								$is_new   = ! isset( $settings['rael_button_icon'] );
								?>
								<span class="rael-infobox__link-icon rael-infobox__link-icon--before">
									<?php
									if ( $is_new || $migrated ) :
										Icons_Manager::render_icon( $settings['rael_new_button_icon'], array( 'aria-hidden' => 'true' ) );
									elseif ( ! empty( $settings['rael_button_icon'] ) ) :
										?>
										<i class="<?php echo esc_attr( $settings['rael_button_icon'] ); ?>"
											aria-hidden="true"></i>
									<?php endif; ?>
								</span>
							<?php } ?>
						<?php } elseif ( ! empty( $settings['rael_button_icon'] ) ) { ?>
							<span class="rael-infobox__link-icon rael-infobox__link-icon--before">
								<i class="<?php echo esc_attr( $settings['rael_button_icon'] ); ?>" aria-hidden="true"></i>
							</span>
						<?php } ?>
					<?php } ?>
					<?php
					$this->add_inline_editing_attributes( 'rael_link_text', 'basic' );
					?>
					<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_link_text' ) ); ?> >
							<?php echo wp_kses_post( $settings['rael_link_text'] ); ?>
						</span>
					<?php
					if ( 'right' === $settings['rael_button_icon_position'] ) {
						if ( self::is_elementor_updated() ) {
							if ( ( ! empty( $settings['rael_button_icon'] ) || ! empty( $settings['rael_new_button_icon'] ) ) ) {
								$migrated = isset( $settings['__fa4_migrated']['rael_new_button_icon'] );
								$is_new   = ! isset( $settings['rael_button_icon'] );
								?>
								<span class="rael-infobox__link-icon rael-infobox__link-icon--after">
									<?php
									if ( $is_new || $migrated ) :
										Icons_Manager::render_icon( $settings['rael_new_button_icon'], array( 'aria-hidden' => 'true' ) );
									elseif ( ! empty( $settings['rael_button_icon'] ) ) :
										?>
										<i class="<?php echo esc_attr( $settings['rael_button_icon'] ); ?>"
											aria-hidden="true"></i>
									<?php endif; ?>
								</span>
							<?php } ?>
						<?php } elseif ( ! empty( $settings['rael_button_icon'] ) ) { ?>
							<span class="rael-infobox__link-icon rael-infobox__link-icon--after">
								<i class="<?php echo esc_attr( $settings['rael_button_icon'] ); ?>" aria-hidden="true"></i>
							</span>
						<?php } ?>
					<?php } ?>
				</a>
			</div>
			<?php
		} elseif ( 'button' === $settings['rael_cta_type'] ) {
			$_target   = ( 'on' === $dynamic_settings['rael_text_link']['is_external'] ) ? '_blank' : '';
			$_nofollow = ( 'on' === $dynamic_settings['rael_text_link']['nofollow'] ) ? '1' : '0';

			$this->add_render_attribute( 'rael_btn_wrapper', 'class', 'rael-infobox-button-wrapper elementor-button-wrapper' );

			if ( ! empty( $dynamic_settings['rael_text_link']['url'] ) ) {
				$this->add_render_attribute( 'rael_button', 'href', $dynamic_settings['rael_text_link']['url'] );
				$this->add_render_attribute( 'rael_button', 'class', 'elementor-button-link' );

				if ( $dynamic_settings['rael_text_link']['is_external'] ) {
					$this->add_render_attribute( 'rael_button', 'target', '_blank' );
				}
			}
			$this->add_render_attribute( 'rael_button', 'class', ' elementor-button' );

			if ( ! empty( $settings['rael_button_size'] ) ) {
				$this->add_render_attribute( 'rael_button', 'class', 'elementor-size-' . $settings['rael_button_size'] );
			}
			if ( $settings['rael_button_animation'] ) {
				$this->add_render_attribute( 'rael_button', 'class', 'elementor-animation-' . $settings['rael_button_animation'] );
			}
			?>
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_btn_wrapper' ) ); ?>>
				<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_button' ) ); ?>
				<?php self::get_link_rel( $_target, $_custom_attr_arr, $_nofollow, 1 ); ?>
					<?php
					if ( $_custom_attr_arr ) {
						foreach ( $_custom_attr_arr as $attr_pair ) {
							$attr_pair_arr = explode( '|', $attr_pair );
							$attr_key      = htmlspecialchars( trim( $attr_pair_arr[0] ) );
							$attr_val      = htmlspecialchars( trim( $attr_pair_arr[1] ) );
							if ( 'rel' !== $attr_key ) {
								echo ' ' . esc_attr( $attr_key ) . '="' . esc_attr( $attr_val ) . '" ';
							}
						}
					}

					?>
				>
					<?php $this->render_button_text(); ?>
				</a>
			</div>
			<?php
		}
	}

	/**
	 * Render RAEL Infobox Button text
	 *
	 * @access public
	 * @since 1.2.0
	 */
	public function render_button_text() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'rael_infobox_content', 'class', 'elementor-button-content-wrapper rael-infobox-align-icon-' . $settings['rael_button_icon_position'] );
		$this->add_render_attribute( 'icon-align', 'class', 'elementor-button-icon' );

		$this->add_render_attribute( 'rael_button_text', 'class', 'elementor-button-text' );
		$this->add_inline_editing_attributes( 'rael_button_text', 'none' );
		?>
		<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_infobox_content' ) ); ?>>
			<?php if ( self::is_elementor_updated() ) { ?>
				<?php
				$migrated = isset( $settings['__fa4_migrated']['rael_new_button_icon'] );
				$is_new   = ! isset( $settings['rael_button_icon'] );
				?>
				<?php if ( ! empty( $settings['rael_button_icon'] ) || ! empty( $settings['rael_new_button_icon'] ) ) : ?>
					<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'icon-align' ) ); ?>>
						<?php
						if ( $is_new || $migrated ) :
							Icons_Manager::render_icon( $settings['rael_new_button_icon'], array( 'aria-hidden' => 'true' ) );
						elseif ( ! empty( $settings['rael_button_icon'] ) ) :
							?>
							<i class="<?php echo esc_attr( $settings['rael_button_icon'] ); ?>" aria-hidden="true"></i>
						<?php endif; ?>
					</span>
				<?php endif; ?>
			<?php } elseif ( ! empty( $settings['rael_button_icon'] ) ) { ?>
				<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'icon-align' ) ); ?>>
					<i class="<?php echo esc_attr( $settings['rael_button_icon'] ); ?>" aria-hidden="true"></i>
				</span>
			<?php } ?>

			<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_button_text' ) ); ?> >
				<?php echo wp_kses_post( $settings['rael_button_text'] ); ?>
			</span>
		</span>
		<?php
	}

	/**
	 * Render template for live preview in the backend
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function content_template() {
		?>

		<#
		function widget_template() {
		view.addRenderAttribute( 'rael_infobox_classname', 'class', 'rael-infobox-widget-content rael-infobox' );

		if ( 'icon' == settings.rael_image_type || 'photo' == settings.rael_image_type ) {

		view.addRenderAttribute( 'rael_infobox_classname', 'class', 'rael-infobox__imgicon-style--' + settings.rael_imgicon_style );
		view.addRenderAttribute( 'rael_infobox_classname', 'class', 'elementor-animation-' + settings.rael_info_box_hover_animation );

		if ( 'above-title' == settings.rael_image_position || 'below-title' == settings.rael_image_position ) {
		view.addRenderAttribute( 'rael_infobox_classname', 'class', ' rael-infobox--' + settings.rael_align );
		}
		if ( 'left-title' == settings.rael_image_position || 'left' == settings.rael_image_position ) {
		view.addRenderAttribute( 'rael_infobox_classname', 'class', ' rael-infobox--left' );
		}
		if ( 'right-title' == settings.rael_image_position || 'right' == settings.rael_image_position ) {
		view.addRenderAttribute( 'rael_infobox_classname', 'class', ' rael-infobox--right' );
		}
		if ( 'icon' == settings.rael_image_type ) {
		view.addRenderAttribute( 'rael_infobox_classname', 'class', 'rael-infobox--has-icon rael-infobox--icon-' + settings.rael_image_position );
		}
		if ( 'photo' == settings.rael_image_type ) {
		view.addRenderAttribute( 'rael_infobox_classname', 'class', 'rael-infobox--has-photo rael-infobox--photo-' + settings.rael_image_position );
		}
		if ( 'above-title' != settings.rael_image_position && 'below-title' != settings.rael_image_position ) {

		if ( 'middle' == settings.rael_image_valign ) {
		view.addRenderAttribute( 'rael_infobox_classname', 'class', ' rael-infobox__image-valign--middle' );
		} else {
		view.addRenderAttribute( 'rael_infobox_classname', 'class', ' rael-infobox__image-valign--top' );
		}
		}
		if ( 'left' == settings.rael_image_position || 'right' == settings.rael_image_position ) {
		if ( 'tablet' == settings.rael_image_responsive_support ) {
		view.addRenderAttribute( 'rael_infobox_classname', 'class', ' rael-infobox--view-tablet' );
		}
		if ( 'mobile' == settings.rael_image_responsive_support ) {
		view.addRenderAttribute( 'rael_infobox_classname', 'class', ' rael-infobox--view-mobile' );
		}
		}
		if ( 'right' == settings.rael_image_position ) {
		if ( 'tablet' == settings.rael_image_responsive_support ) {
		view.addRenderAttribute( 'rael_infobox_classname', 'class', ' rael-infobox--reverse-order-tablet' );
		}
		if ( 'mobile' == settings.rael_image_responsive_support ) {
		view.addRenderAttribute( 'rael_infobox_classname', 'class', ' rael-infobox--reverse-order-mobile' );
		}
		}
		} else {
		if ( 'left' == settings.rael_overall_align || 'center' == settings.rael_overall_align || 'right' == settings.rael_overall_align ) {
		view.addRenderAttribute( 'rael_infobox_classname', 'class', ' rael-infobox--' + settings.rael_overall_align );
		}
		}

		view.addRenderAttribute( 'rael_infobox_classname', 'class', 'rael-infobox-link-type-' + settings.rael_cta_type );
		#>
		<div {{{ view.getRenderAttributeString( 'rael_infobox_classname' ) }}}>
		<div class="rael-infobox-left-right-wrapper">
			<#
			if ( 'module' == settings.rael_cta_type && '' != settings.rael_text_link ) {
			#>
			<a href="{{ settings.rael_text_link.url }}" class="rael-infobox-module-link"></a>
			<# } #>
			<# render_image( 'left' ); #>
			<div class="rael-infobox__content">
				<# render_image( 'above-title' ); #>
				<# if( 'after_icon' == settings.rael_separator_position ) {
				render_separator();
				} #>
				<# render_title(); #>
				<# if( 'after_heading' == settings.rael_separator_position ) {
				render_separator();
				} #>
				<# render_image( 'below-title' ); #>
				<# if( '' != settings.rael_description ) { #>
					<div class="rael-infobox__description-wrapper">
						<# view.addRenderAttribute('rael_description', 'class', 'rael-infobox__description'); #>
						<# view.addInlineEditingAttributes('rael_description', 'advanced'); #>
						<div {{{ view.getRenderAttributeString(
						'rael_description') }}}>
						{{{ settings.rael_description }}}
					</div>
				<# } #>
				<# if( 'after_description' == settings.rael_separator_position ) {
				render_separator();
				} #>
				<# render_link(); #>
			</div>
		</div>
		<# render_image( 'right' ); #>
		</div>
		</div>
		<#
		}
		#>

		<#
		function render_image( position ) {
		var set_pos = '';
		var media_img = '';
		var anim_class = '';
		if ( 'icon' == settings.rael_image_type || 'photo' == settings.rael_image_type ) {
		var set_pos = settings.rael_image_position;
		}
		if ( position == set_pos ) {
		if ( 'icon' == settings.rael_image_type || 'photo' == settings.rael_image_type ) {

		if( 'normal' != settings.rael_imgicon_style ) {
		anim_class = 'elementor-animation-' + settings.rael_imgicon_animation;
		} else if ( 'normal' == settings.rael_imgicon_style ) {
		anim_class = 'elementor-animation-' + settings.rael_imgicon_animation;
		} #>
		<div class="rael-infobox-widget-content rael-infobox__imgicon-wrapper">
			<# if ( 'icon' == settings.rael_image_type ) { #>
			<?php if ( self::is_elementor_updated() ) { ?>
				<# if ( settings.rael_select_icon || settings.rael_new_select_icon ) {
				var iconHTML = elementor.helpers.renderIcon( view, settings.rael_new_select_icon, { 'aria-hidden': true }, 'i' , 'object' );

				var migrated = elementor.helpers.isIconMigrated( settings, 'rael_new_select_icon' );

				#>
				<div class="rael-infobox__icon-wrapper {{ anim_class }} ">
									<span class="rael-infobox__icon">
										<# if ( iconHTML && iconHTML.rendered && ( ! settings.rael_select_icon || migrated ) ) {
										#>
											{{{ iconHTML.value }}}
										<# } else { #>
											<i class="{{ settings.rael_select_icon }}" aria-hidden="true"></i>
										<# } #>
									</span>
				</div>
				<# } #>
			<?php } else { ?>
				<div class="rael-infobox__icon-wrapper {{ anim_class }} ">
								<span class="rael-infobox__icon">
									<i class="{{ settings.rael_select_icon }}" aria-hidden="true"></i>
								</span>
				</div>
			<?php } ?>
			<# }
			if ( 'photo' == settings.rael_image_type ) {
			#>
			<div class="rael-infobox__image" itemscope itemtype="http://schema.org/ImageObject">
				<div class="rael-infobox__image-content {{ anim_class }} ">
					<#
					if ( 'media' == settings.rael_photo_type ) {
					if ( '' != settings.rael_image.url ) {

					var media_image = {
					id: settings.rael_image.id,
					url: settings.rael_image.url,
					size: settings.rael_image_size,
					dimension: settings.rael_image_custom_dimension,
					model: view.getEditModel()
					};
					media_img = elementor.imagesManager.getImageUrl( media_image );
					#>
					<img src="{{{ media_img }}}">
					<#
					}
					}
					if ( 'url' == settings.rael_photo_type ) {
					if ( '' != settings.rael_image_link ) {
					view.addRenderAttribute( 'rael_image_link', 'src', settings.rael_image_link.url );
					#>
					<img {{{ view.getRenderAttributeString( 'rael_image_link' ) }}}>
					<#
					}
					} #>
				</div>
			</div>
			<# } #>
		</div>
		<#
		}
		}
		}
		#>

		<#
		    // Define an array of allowed HTML tags
		    let allowedTags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'p'];

		    // Ensure settings.rael_title_tag exists and is a valid string
		    let titleTag = settings.rael_title_tag ? settings.rael_title_tag.toLowerCase() : '';

		    // Function to validate the HTML tag (scope is within the template)
		    let validatedTag = allowedTags.includes(titleTag) ? titleTag : 'h3';
		#>

		<#
		function render_title() {
		var flag = false;
		if ( ( 'photo' == settings.rael_image_type && 'left-title' == settings.rael_image_position ) || ( 'icon' == settings.rael_image_type && 'left-title' == settings.rael_image_position ) ) {
		#>
		<div class="rael-infobox-image--left-of-heading">
			<#
			flag = true;
			}
			if ( ( 'photo' == settings.rael_image_type && 'right-title' == settings.rael_image_position ) || ( 'icon' ==
			settings.rael_image_type && 'right-title' == settings.rael_image_position ) ) {
			#>
			<div class="rael-infobox-image--right-of-heading">
				<#
				flag = true;
				} #>
				<# render_image( 'left-title' ); #>
				<div class='rael-infobox__title-wrapper'>
				<# view.addRenderAttribute('rael_title', 'class', 'rael-infobox__title'); #>
				<# view.addInlineEditingAttributes('rael_title', 'basic'); #>

				<{{{ validatedTag }}} {{{ view.getRenderAttributeString('rael_title') }}}>
				    {{{ settings.rael_title }}}
				</{{{ validatedTag }}}>
		</div>
		<# render_image( 'right-title' ); #>
		<# if ( flag ) { #>
		</div>
		<# }
		}
		#>

		<#
		function render_link() {

		if ( 'link' == settings.rael_cta_type ) {
		#>
		<div class="rael-infobox__cta-link-style">
			<a href="{{ settings.rael_text_link.url }}" class="rael-infobox__cta-link">
				<#
				if ( 'left' == settings.rael_button_icon_position ) {
				#>
				<span class="rael-infobox__link-icon rael-infobox__link-icon--before">
								<?php if ( self::is_elementor_updated() ) { ?>
									<#
									var buttoniconHTML = elementor.helpers.renderIcon( view, settings.rael_new_button_icon, { 'aria-hidden': true }, 'i' , 'object' );

									var buttonMigrated = elementor.helpers.isIconMigrated( settings, 'rael_new_button_icon' );
									#>
									<# if ( buttoniconHTML && buttoniconHTML.rendered && ( ! settings.rael_button_icon || buttonMigrated ) ) { #>
									{{{ buttoniconHTML.value }}}
									<# } else { #>
									<i class="{{ settings.rael_button_icon }}"></i>
									<# } #>
								<?php } else { ?>
									<i class="{{ settings.rael_button_icon }}"></i>
								<?php } ?>
							</span>
				<# } #>
				<# view.addInlineEditingAttributes('rael_link_text', 'basic'); #>
				<span {{ view.getRenderAttributeString('rael_link_text') }}>
				{{{ settings.rael_link_text }}}
				</span>

				<# if ( 'right' == settings.rael_button_icon_position ) {
				#>
				<span class="rael-infobox__link-icon rael-infobox__link-icon--after">
								<?php if ( self::is_elementor_updated() ) { ?>
									<#
									var buttoniconHTML = elementor.helpers.renderIcon( view, settings.rael_new_button_icon, { 'aria-hidden': true }, 'i' , 'object' );

									var buttonMigrated = elementor.helpers.isIconMigrated( settings, 'rael_new_button_icon' );
									#>
									<# if ( buttoniconHTML && buttoniconHTML.rendered && ( ! settings.rael_button_icon || buttonMigrated ) ) { #>
									{{{ buttoniconHTML.value }}}
									<# } else { #>
									<i class="{{ settings.rael_button_icon }}"></i>
									<# } #>
								<?php } else { ?>
									<i class="{{ settings.rael_button_icon }}"></i>
								<?php } ?>
							</span>
				<# } #>
			</a>
		</div>
		<# }
		else if ( 'button' == settings.rael_cta_type ) {

		view.addRenderAttribute( 'rael_btn_wrapper', 'class', 'rael-infobox-button-wrapper elementor-button-wrapper' );
		if ( '' != settings.rael_text_link.url ) {
		view.addRenderAttribute( 'rael_button', 'href', settings.rael_text_link.url );
		view.addRenderAttribute( 'rael_button', 'class', 'elementor-button-link' );
		}
		view.addRenderAttribute( 'rael_button', 'class', 'elementor-button' );

		if ( '' != settings.rael_button_size ) {
		view.addRenderAttribute( 'rael_button', 'class', 'elementor-size-' + settings.rael_button_size );
		}

		if ( settings.rael_button_animation ) {
		view.addRenderAttribute( 'rael_button', 'class', 'elementor-animation-' + settings.rael_button_animation );
		} #>
		<div {{{ view.getRenderAttributeString( 'rael_btn_wrapper' ) }}}>
		<a {{{ view.getRenderAttributeString( 'rael_button' ) }}}>
		<#
		view.addRenderAttribute( 'rael_infobox_content', 'class', 'elementor-button-content-wrapper rael-infobox-align-icon-' + settings.rael_button_icon_position );

		view.addRenderAttribute( 'icon-align', 'class', 'elementor-button-icon' );

		view.addRenderAttribute( 'rael_button_text', 'class', 'elementor-button-text' );

		view.addInlineEditingAttributes( 'rael_button_text', 'none' );

		#>
		<span {{{ view.getRenderAttributeString( 'rael_infobox_content' ) }}}>
		<?php if ( self::is_elementor_updated() ) { ?>
		<# if ( settings.rael_button_icon || settings.rael_new_button_icon ) { #>
		<span {{{ view.getRenderAttributeString( 'icon-align' ) }}}>
		<#
		var buttoniconHTML = elementor.helpers.renderIcon( view, settings.rael_new_button_icon, { 'aria-hidden': true }, 'i' , 'object' );

		var buttonMigrated = elementor.helpers.isIconMigrated( settings, 'rael_new_button_icon' );
		#>
		<# if ( buttoniconHTML && buttoniconHTML.rendered && ( ! settings.rael_button_icon || buttonMigrated ) ) { #>
		{{{ buttoniconHTML.value }}}
		<# } else { #>
		<i class="{{ settings.rael_button_icon }}"></i>
		<# } #>
		</span>
		<# } #>
	<?php } else { ?>
		<span {{{ view.getRenderAttributeString( 'icon-align' ) }}}>
		<i class="{{ settings.rael_button_icon }}"></i>
		</span>
	<?php } ?>
		<span {{{ view.getRenderAttributeString( 'rael_button_text' ) }}}>
		{{ settings.rael_button_text }}
		</span>
		</span>
		</a>
		</div>
		<#
		}
		}
		#>

		<#
		function render_separator() {
		if ( 'yes' == settings.rael_toggle_separator ) { #>
		<div class="rael-infobox__separator-wrapper">
			<div class="rael-infobox__separator"></div>
		</div>
		<# }
		}

		widget_template();
		#>

		<?php
	}
}
