<?php
/**
 * RAE Price Box
 *
 * @since   1.0.0
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * RAE Price box widget class.
 *
 * @since 1.0.0
 */
class Responsive_Addons_For_Elementor_Price_Box extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael_price_box';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Price Box', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-price-list rael-badge';
	}

	/**
	 * Get widget categories.
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
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function register_controls() {
		// Content TAB.
		$this->register_general_section_controls();
		$this->register_heading_section_controls();
		$this->register_description_section_controls();
		$this->register_pricing_section_controls();
		$this->register_content_section_controls();
		$this->register_tooltip_content_section_controls();
		$this->register_cta_section_controls();
		$this->register_separator_section_controls();
		$this->register_ribbon_section_controls();

		// Style TAB.
		$this->register_heading_style_controls();
		$this->register_pricing_style_controls();
		$this->register_description_style_controls();
		$this->register_content_style_controls();
		$this->register_tooltip_style_controls();
		$this->register_cta_style_controls();
		$this->register_separator_style_controls();
		$this->register_ribbon_style_controls();
	}
	/**
	 * Register controls for the General section.
	 *
	 * This method defines controls related to the general settings of the pricing box element.
	 * It includes controls for styles, hover animations, etc.
	 */
	protected function register_general_section_controls() {
		$this->start_controls_section(
			'rael_general_section',
			array(
				'label' => __( 'General', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_price_box_layout',
			array(
				'label'       => __( 'Style', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'default'     => '1',
				'options'     => array(
					'1' => __( 'Normal', 'responsive-addons-for-elementor' ),
					'2' => __( 'Features at Bottom', 'responsive-addons-for-elementor' ),
					'3' => __( 'Circular Background for Price', 'responsive-addons-for-elementor' ),
					'4' => __( 'Pricing Above Call To Action', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_price_box_hover_animation',
			array(
				'label'        => __( 'Hover Animation', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => '',
				'options'      => array(
					''                => __( 'None', 'responsive-addons-for-elementor' ),
					'float'           => __( 'Float', 'responsive-addons-for-elementor' ),
					'sink'            => __( 'Sink', 'responsive-addons-for-elementor' ),
					'wobble-vertical' => __( 'Wobble Vertical', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'elementor-animation-',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_heading_section_controls() {
		$this->start_controls_section(
			'rael_heading_section',
			array(
				'label' => __( 'Heading', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_heading_icon',
			array(
				'label'       => __( 'Icon', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::ICONS,
				'render_type' => 'template',
			)
		);
		$this->add_control(
			'rael_title',
			array(
				'label'   => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Unlimited', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_description',
			array(
				'label'     => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => __( 'Free trial 30 days.', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_price_box_layout!' => '2',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register controls for the Description section.
	 *
	 * This method defines controls related to the description settings of the pricing box element.
	 * It includes controls for the description text in a specific layout.
	 */
	protected function register_description_section_controls() {
		$this->start_controls_section(
			'rael_description_section',
			array(
				'label'     => __( 'Description', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_price_box_layout' => '2',
				),
			)
		);

		$this->add_control(
			'rael_description_for_layout_2',
			array(
				'label'     => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => __( 'Free trial 30 days.', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_price_box_layout' => '2',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register controls for the Pricing section.
	 *
	 * This method defines controls related to the pricing details of the pricing box element.
	 * It includes controls for price, discount, currency, duration, and other options.
	 */
	protected function register_pricing_section_controls() {
		$this->start_controls_section(
			'rael_pricing_section',
			array(
				'label' => __( 'Pricing', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_control(
			'rael_price',
			array(
				'label'   => __( 'Price', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( '49.99', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_control(
			'rael_sale',
			array(
				'label'        => __( 'Offering Discount?', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);
		$this->add_control(
			'rael_original_price',
			array(
				'label'     => __( 'Original Price', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '59.99',
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'rael_sale' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_currency_symbol',
			array(
				'label'   => __( 'Currency Symbol', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''             => __( 'None', 'responsive-addons-for-elementor' ),
					'dollar'       => '&#36; ' . _x( 'Dollar', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'euro'         => '&#128; ' . _x( 'Euro', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'baht'         => '&#3647; ' . _x( 'Baht', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'franc'        => '&#8355; ' . _x( 'Franc', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'guilder'      => '&fnof; ' . _x( 'Guilder', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'krona'        => 'kr ' . _x( 'Krona', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'lira'         => '&#8356; ' . _x( 'Lira', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'indian_rupee' => '&#8377; ' . _x( 'Rupee (Indian)', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'peseta'       => '&#8359 ' . _x( 'Peseta', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'peso'         => '&#8369; ' . _x( 'Peso', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'pound'        => '&#163; ' . _x( 'Pound Sterling', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'real'         => 'R$ ' . _x( 'Real', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'ruble'        => '&#8381; ' . _x( 'Ruble', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'rupee'        => '&#8360; ' . _x( 'Rupee', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'shekel'       => '&#8362; ' . _x( 'Shekel', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'yen'          => '&#165; ' . _x( 'Yen/Yuan', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'won'          => '&#8361; ' . _x( 'Won', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'custom'       => __( 'Custom', 'responsive-addons-for-elementor' ),
				),
				'default' => 'dollar',
			)
		);

		$this->add_control(
			'rael_currency_symbol_custom',
			array(
				'label'     => __( 'Currency Symbol', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'rael_currency_symbol' => 'custom',
				),
			)
		);
		$this->add_control(
			'rael_currency_format',
			array(
				'label'   => __( 'Currency Format', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''  => 'Raised',
					',' => 'Normal',
				),
			)
		);

		// Add condition for internal links.

		$this->add_control(
			'rael_help_doc_pricing',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				/* translators: %s admin link */
				'raw'             => __( 'The raised option will add a Subscript / Superscript design to the fractional part of the Price.', 'responsive-addons-for-elementor' ),
				'content_classes' => 'rael-editor-doc',
				'condition'       => array(
					'rael_currency_format' => '',
				),
				'separator'       => 'none',
			)
		);

		$this->add_control(
			'rael_duration',
			array(
				'label'   => __( 'Duration', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Monthly', 'responsive-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'rael_duration_position',
			array(
				'label'       => __( 'Duration Position', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => array(
					'below'  => __( 'Below', 'responsive-addons-for-elementor' ),
					'beside' => __( 'Beside', 'responsive-addons-for-elementor' ),
				),
				'default'     => 'below',
				'condition'   => array(
					'rael_duration!'         => '',
					'rael_price_box_layout!' => '3',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register controls for the Content section.
	 *
	 * This method defines controls related to the content (features) of the pricing box element.
	 * It includes controls for a list of features, each with customizable text, icon, and styles.
	 */
	protected function register_content_section_controls() {
		$this->start_controls_section(
			'rael_features_section',
			array(
				'label' => __( 'Content', 'responsive-addons-for-elementor' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'rael_item_text',
			array(
				'label'   => __( 'Text', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Feature', 'responsive-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'rael_tooltip_content',
			array(
				'label'   => __( 'Tooltip Content', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => __( 'This is a tooltip', 'responsive-addons-for-elementor' ),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		if ( self::is_elementor_updated() ) {

			$repeater->add_control(
				'rael_new_item_icon',
				array(
					'label'            => __( 'Icon', 'responsive-addons-for-elementor' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => 'item_icon',
					'default'          => array(
						'value'   => 'fa fa-arrow-circle-right',
						'library' => 'fa-solid',
					),
					'render_type'      => 'template',
				)
			);
		} else {
			$repeater->add_control(
				'rael_item_icon',
				array(
					'label'   => __( 'Icon', 'responsive-addons-for-elementor' ),
					'type'    => Controls_Manager::ICON,
					'default' => 'fa fa-arrow-circle-right',
				)
			);
		}

		$repeater->add_control(
			'rael_item_advanced_settings',
			array(
				'label'        => __( 'Override Global Settings', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$repeater->add_control(
			'rael_item_icon_color',
			array(
				'label'      => __( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'global'     => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => self::get_new_icon_name( 'item_icon' ),
							'operator' => '!=',
							'value'    => '',
						),
						array(
							'name'     => 'rael_item_advanced_settings',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-box__features-list {{CURRENT_ITEM}} i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-price-box__features-list {{CURRENT_ITEM}} svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$repeater->add_control(
			'rael_item_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'rael_item_advanced_settings' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
				),
			)
		);

		$repeater->add_control(
			'rael_item_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'rael_item_advanced_settings' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'rael_features_list',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'rael_item_text'     => __( 'List of Features', 'responsive-addons-for-elementor' ),
						'rael_new_item_icon' => array(
							'value'   => 'fa fa-arrow-circle-right',
							'library' => 'fa-solid',
						),
					),
					array(
						'rael_item_text'     => __( 'List of Features', 'responsive-addons-for-elementor' ),
						'rael_new_item_icon' => array(
							'value'   => 'fa fa-arrow-circle-right',
							'library' => 'fa-solid',
						),
					),
					array(
						'rael_item_text'     => __( 'List of Features', 'responsive-addons-for-elementor' ),
						'rael_new_item_icon' => array(
							'value'   => 'fa fa-arrow-circle-right',
							'library' => 'fa-solid',
						),
					),
				),
				'title_field' => '{{{ rael_item_text }}}',
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register controls for the Tooltip content section.
	 *
	 * This method defines the Elementor controls for the Tooltip section, including
	 * controls for enabling/disabling the Tooltip, setting its position, and choosing
	 * the trigger type (hover or click).
	 */
	protected function register_tooltip_content_section_controls() {
		$this->start_controls_section(
			'rael_tooltip_section',
			array(
				'label' => __( 'Tooltip', 'responsive-addons-for-elementor' ),

			)
		);

		$this->add_control(
			'rael_toggle_features_tooltip',
			array(
				'label'        => __( 'Enable Tooltip', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

			$this->add_control(
				'rael_tooltip_position',
				array(
					'label'              => __( 'Position', 'responsive-addons-for-elementor' ),
					'type'               => Controls_Manager::SELECT,
					'default'            => 'top',
					'options'            => array(
						'top'    => __( 'Top', 'responsive-addons-for-elementor' ),
						'bottom' => __( 'Bottom', 'responsive-addons-for-elementor' ),
						'left'   => __( 'Left', 'responsive-addons-for-elementor' ),
						'right'  => __( 'Right', 'responsive-addons-for-elementor' ),
					),
					'condition'          => array(
						'rael_toggle_features_tooltip' => 'yes',
					),
					'frontend_available' => true,
				)
			);

			$this->add_control(
				'rael_trigger',
				array(
					'label'              => __( 'Display on', 'responsive-addons-for-elementor' ),
					'type'               => Controls_Manager::SELECT,
					'default'            => 'hover',
					'options'            => array(
						'hover' => __( 'Hover', 'responsive-addons-for-elementor' ),
						'click' => __( 'Click', 'responsive-addons-for-elementor' ),
					),
					'condition'          => array(
						'rael_toggle_features_tooltip' => 'yes',
					),
					'frontend_available' => true,
				)
			);

			$this->add_control(
				'rael_arrow',
				array(
					'label'     => __( 'Arrow', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'true',
					'options'   => array(
						'true'  => __( 'Show', 'responsive-addons-for-elementor' ),
						'false' => __( 'Hide', 'responsive-addons-for-elementor' ),
					),
					'condition' => array(
						'rael_toggle_features_tooltip' => 'yes',
					),
				)
			);

			$this->add_control(
				'rael_distance',
				array(
					'label'       => __( 'Distance', 'responsive-addons-for-elementor' ),
					'description' => __( 'The distance between the marker and the tooltip.', 'responsive-addons-for-elementor' ),
					'type'        => Controls_Manager::SLIDER,
					'default'     => array(
						'size' => 6,
						'unit' => 'px',
					),
					'range'       => array(
						'px' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'condition'   => array(
						'rael_toggle_features_tooltip' => 'yes',
					),
				)
			);

			$this->add_control(
				'rael_tooltip_animation',
				array(
					'label'     => __( 'Animation Type', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'fade',
					'options'   => array(
						'fade'  => __( 'Default', 'responsive-addons-for-elementor' ),
						'grow'  => __( 'Grow', 'responsive-addons-for-elementor' ),
						'swing' => __( 'Swing', 'responsive-addons-for-elementor' ),
						'slide' => __( 'Slide', 'responsive-addons-for-elementor' ),
						'fall'  => __( 'Fall', 'responsive-addons-for-elementor' ),
					),
					'condition' => array(
						'rael_toggle_features_tooltip' => 'yes',
					),
				)
			);

			$this->add_control(
				'rael_responsive_support',
				array(
					'label'       => __( 'Hide Tooltip On', 'responsive-addons-for-elementor' ),
					'description' => __( 'Choose on what breakpoint the tooltip will be hidden.', 'responsive-addons-for-elementor' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'none',
					'options'     => array(
						'none'   => __( 'None', 'responsive-addons-for-elementor' ),
						'tablet' => __( 'Tablet & Mobile', 'responsive-addons-for-elementor' ),
						'mobile' => __( 'Mobile', 'responsive-addons-for-elementor' ),
					),
					'condition'   => array(
						'rael_toggle_features_tooltip' => 'yes',
					),
					'render_type' => 'template',
				)
			);

			$this->add_control(
				'rael_tooltip_adv_settings',
				array(
					'label'        => __( 'Advanced Settings', 'responsive-addons-for-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'no',
					'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
					'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
					'return_value' => 'yes',
					'condition'    => array(
						'rael_toggle_features_tooltip' => 'yes',
					),
				)
			);

			$this->add_control(
				'rael_tooltip_animation_duration',
				array(
					'label'              => __( 'Animation Duration', 'responsive-addons-for-elementor' ),
					'type'               => Controls_Manager::SLIDER,
					'range'              => array(
						'px' => array(
							'min' => 0,
							'max' => 1000,
						),
					),
					'default'            => array(
						'size' => 350,
						'unit' => 'px',
					),
					'condition'          => array(
						'rael_tooltip_adv_settings'    => 'yes',
						'rael_toggle_features_tooltip' => 'yes',
					),
					'frontend_available' => true,
				)
			);

			$this->add_responsive_control(
				'rael_tooltip_width',
				array(
					'label'              => __( 'Width', 'responsive-addons-for-elementor' ),
					'type'               => Controls_Manager::SLIDER,
					'range'              => array(
						'px' => array(
							'min' => 0,
							'max' => 1000,
						),
					),
					'condition'          => array(
						'rael_tooltip_adv_settings'    => 'yes',
						'rael_toggle_features_tooltip' => 'yes',
					),
					'selectors'          => array(
						'.rael-price-box-container-{{ID}} div[id^="tippy-"] .tippy-content' => 'width: {{SIZE}}px; max-width: {{SIZE}}{{UNIT}} !important;',
					),
					'frontend_available' => true,
				)
			);

			$this->add_responsive_control(
				'rael_tooltip_height',
				array(
					'label'              => __( 'Max Height', 'responsive-addons-for-elementor' ),
					'description'        => __( 'Note: If Tooltip Content is large, a vertical scroll will appear. Set Max Height to manage the content window height.', 'responsive-addons-for-elementor' ),
					'type'               => Controls_Manager::SLIDER,
					'range'              => array(
						'px' => array(
							'min' => 0,
							'max' => 1000,
						),
					),
					'condition'          => array(
						'rael_tooltip_adv_settings'    => 'yes',
						'rael_toggle_features_tooltip' => 'yes',
					),
					'selectors'          => array(
						'.rael-price-box-container-{{ID}} div[id^="tippy-"] .tippy-content' => 'max-height: {{SIZE}}px;',
					),
					'frontend_available' => true,
				)
			);

			$this->add_control(
				'rael_zindex',
				array(
					'label'       => __( 'Z-Index', 'responsive-addons-for-elementor' ),
					'description' => __( 'Note: Increase the z-index value if you are unable to see the tooltip. For example - 99, 999, 9999 ', 'responsive-addons-for-elementor' ),
					'type'        => Controls_Manager::NUMBER,
					'default'     => '99',
					'min'         => -9999999,
					'step'        => 1,
					'condition'   => array(
						'rael_tooltip_adv_settings'    => 'yes',
						'rael_toggle_features_tooltip' => 'yes',
					),
				)
			);

		$this->end_controls_section();
	}
	/**
	 * Register controls for the Call To Action (CTA) section.
	 *
	 * This method defines the Elementor controls for the CTA section, including controls
	 * for selecting the CTA type (button, link, or none), setting CTA text, icon, icon position,
	 * link, and disclaimer text.
	 */
	protected function register_cta_section_controls() {
		$this->start_controls_section(
			'rael_cta_button_section',
			array(
				'label' => __( 'Call To Action', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_price_cta_type',
			array(
				'label'       => __( 'Type', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'button',
				'label_block' => false,
				'options'     => array(
					'none'   => __( 'None', 'responsive-addons-for-elementor' ),
					'link'   => __( 'Text', 'responsive-addons-for-elementor' ),
					'button' => __( 'Button', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_cta_text',
			array(
				'label'     => __( 'Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Select Plan', 'responsive-addons-for-elementor' ),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'rael_price_cta_type!' => 'none',
				),
			)
		);

		if ( self::is_elementor_updated() ) {

			$this->add_control(
				'rael_new_cta_icon',
				array(
					'label'            => __( 'Select Icon', 'responsive-addons-for-elementor' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => 'rael_cta_icon',
					'condition'        => array(
						'rael_price_cta_type' => array( 'button', 'link' ),
					),
					'render_type'      => 'template',
				)
			);
		} else {
			$this->add_control(
				'rael_cta_icon',
				array(
					'label'     => __( 'Select Icon', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::ICON,
					'condition' => array(
						'rael_price_cta_type' => array( 'button', 'link' ),
					),
				)
			);
		}

		$this->add_control(
			'rael_cta_icon_position',
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
					'rael_price_cta_type' => array( 'button', 'link' ),
				),
			)
		);

		$this->add_control(
			'rael_link',
			array(
				'label'       => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'http://your-link.com',
				'default'     => array(
					'url' => '#',
				),
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'rael_price_cta_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'rael_disclaimer_text',
			array(
				'label'   => __( 'Disclaimer Text', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'dynamic' => array(
					'active' => true,
				),
			)
		);
		$this->end_controls_section();
	}
	/**
	 * Register controls for the Separator section.
	 *
	 * This method defines the Elementor controls for the Separator section, including
	 * a control for toggling the separator.
	 */
	protected function register_separator_section_controls() {
		$this->start_controls_section(
			'rael_separator_section',
			array(
				'label'     => __( 'Separator', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_price_box_layout' => '2',
				),
			)
		);

			$this->add_control(
				'rael_toggle_price_box_separator',
				array(
					'label'        => __( 'Separator', 'responsive-addons-for-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
					'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'condition'    => array(
						'rael_price_box_layout' => '2',
					),
				)
			);

			$this->end_controls_section();
	}
	/**
	 * Register controls for the Ribbon section.
	 *
	 * This method defines the Elementor controls for the Ribbon section, including
	 * controls for selecting the ribbon style, title, position, distance, size, and top distance.
	 */
	protected function register_ribbon_section_controls() {
		$this->start_controls_section(
			'rael_ribbon_section',
			array(
				'label' => __( 'Ribbon', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_show_ribbon',
			array(
				'label'       => __( 'Style', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'none',
				'label_block' => false,
				'options'     => array(
					'none' => __( 'None', 'responsive-addons-for-elementor' ),
					'1'    => __( 'Corner Ribbon', 'responsive-addons-for-elementor' ),
					'2'    => __( 'Circular Ribbon', 'responsive-addons-for-elementor' ),
					'3'    => __( 'Flag Ribbon', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_ribbon_title',
			array(
				'label'     => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'NEW', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_show_ribbon!' => 'none',
				),
			)
		);

		$this->add_control(
			'rael_ribbon_horizontal_position',
			array(
				'label'       => __( 'Horizontal Position', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'toggle'      => false,
				'options'     => array(
					'left'  => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'     => 'right',
				'condition'   => array(
					'rael_show_ribbon!' => array( 'none', '3' ),
				),
			)
		);

		$ribbon_distance_transform = is_rtl() ? 'translateY(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)' : 'translateY(-50%) translateX(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)';

		$this->add_responsive_control(
			'rael_ribbon_distance',
			array(
				'label'     => __( 'Distance', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box-ribbon-1 .rael-price-box-ribbon-content' => 'margin-top: {{SIZE}}{{UNIT}}; transform: ' . $ribbon_distance_transform,
				),
				'condition' => array(
					'rael_show_ribbon' => '1',
				),
			)
		);

		$this->add_responsive_control(
			'rael_ribbon_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'em' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'default'   => array(
					'size' => '4',
					'unit' => 'em',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box-ribbon-2 .rael-price-box-ribbon-content' => 'min-height: {{SIZE}}em; min-width: {{SIZE}}em; line-height: {{SIZE}}em; z-index: 1;',
				),
				'condition' => array(
					'rael_show_ribbon' => '2',
				),
			)
		);

		$this->add_responsive_control(
			'rael_ribbon_top_distance',
			array(
				'label'     => __( 'Top Distance', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box-ribbon-3 .rael-price-box-ribbon-content' => 'top: {{SIZE}}%;',
				),
				'condition' => array(
					'rael_show_ribbon' => '3',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register controls for the Heading Style section.
	 *
	 * This method defines the Elementor controls for the Heading Style section, including
	 * controls for heading background color, padding, heading icon style, icon size, icon padding,
	 * icon color, icon background color, and heading typography.
	 */
	protected function register_heading_style_controls() {
		$this->start_controls_section(
			'rael_header_style',
			array(
				'label'      => __( 'Heading', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		$this->add_control(
			'rael_header_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box-header' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'rael_header_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-box--layout-3 .rael-price-box-header__container, {{WRAPPER}} .rael-price-box--layout-2 .rael-price-box-header, {{WRAPPER}} .rael-price-box--layout-1 .rael-price-box-header, {{WRAPPER}} .rael-price-box--layout-4 .rael-price-box-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'rael_heading_icon_style',
			array(
				'label'     => __( 'Icon', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_heading_icon[value]!' => '',
				),
			)
		);
		$this->add_control(
			'rael_heading_icon_size',
			array(
				'label'     => __( 'Size (px)', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 40,
				),
				'range'     => array(
					'px' => array(
						'max' => 500,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box-header__icon i,
					{{WRAPPER}} .rael-price-box-header__icon svg' => 'font-size: {{SIZE}}px; width: {{SIZE}}px; height: {{SIZE}}px; line-height: {{SIZE}}px;',
				),
				'condition' => array(
					'rael_heading_icon[value]!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'rael_heading_icon_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-box-header__icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_heading_icon[value]!' => '',
				),
			)
		);
		$this->start_controls_tabs( 'rael_icon_style' );

			$this->start_controls_tab(
				'rael_heading_icon_normal_state',
				array(
					'label'     => __( 'Normal', 'responsive-addons-for-elementor' ),
					'condition' => array(
						'rael_heading_icon[value]!' => '',
					),
				)
			);
				$this->add_control(
					'rael_heading_icon_color_normal',
					array(
						'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'global'    => [
							'default' => Global_Colors::COLOR_TEXT,
						],
						'default'   => '',
						'selectors' => array(
							'{{WRAPPER}} .rael-price-box-header__icon i' => 'color: {{VALUE}};',
							'{{WRAPPER}} .rael-price-box-header__icon svg' => 'fill: {{VALUE}};',
						),
						'condition' => array(
							'rael_heading_icon[value]!' => '',
						),
					)
				);

				$this->add_control(
					'rael_heading_icon_bg_color_normal',
					array(
						'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => array(
							'{{WRAPPER}} .rael-price-box-header__icon' => 'background: {{VALUE}};',
						),
						'condition' => array(
							'rael_heading_icon[value]!' => '',
						),
					)
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'rael_heading_icon_hover_state',
				array(
					'label'     => __( 'Hover', 'responsive-addons-for-elementor' ),
					'condition' => array(
						'rael_heading_icon[value]!' => '',
					),
				)
			);

				$this->add_control(
					'rael_heading_icon_color_hover',
					array(
						'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'global'    => [
							'default' => Global_Colors::COLOR_TEXT,
						],
						'default'   => '',
						'selectors' => array(
							'{{WRAPPER}} .rael-price-box-header__icon i:hover' => 'color: {{VALUE}};',
							'{{WRAPPER}} .rael-price-box-header__icon svg:hover' => 'fill: {{VALUE}};',
						),
						'condition' => array(
							'rael_heading_icon[value]!' => '',
						),
					)
				);

				$this->add_control(
					'rael_heading_icon_bg_color_hover',
					array(
						'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => array(
							'{{WRAPPER}} .rael-price-box-header__icon:hover' => 'background: {{VALUE}};',
						),
						'condition' => array(
							'rael_heading_icon[value]!' => '',
						),
					)
				);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'rael_heading_style',
			array(
				'label'     => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'rael_heading_tag',
			array(
				'label'   => __( 'Title Tag', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1'  => __( 'H1', 'responsive-addons-for-elementor' ),
					'h2'  => __( 'H2', 'responsive-addons-for-elementor' ),
					'h3'  => __( 'H3', 'responsive-addons-for-elementor' ),
					'h4'  => __( 'H4', 'responsive-addons-for-elementor' ),
					'h5'  => __( 'H5', 'responsive-addons-for-elementor' ),
					'h6'  => __( 'H6', 'responsive-addons-for-elementor' ),
					'div' => __( 'div', 'responsive-addons-for-elementor' ),
					'p'   => __( 'p', 'responsive-addons-for-elementor' ),
				),
				'default' => 'h3',
			)
		);
		$this->add_control(
			'rael_heading_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box__heading' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_heading_typography',
				'selector' => '{{WRAPPER}} .rael-price-box__heading',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			)
		);

		$this->add_control(
			'rael_description_style',
			array(
				'label'     => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_price_box_layout!' => '2',
				),
			)
		);
		$this->add_control(
			'rael_description_tag',
			array(
				'label'     => __( 'Description Tag', 'responsive-addons-for-elementor' ),
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
				'condition' => array(
					'rael_price_box_layout!' => '2',
				),
				'default'   => 'p',
			)
		);
		$this->add_control(
			'rael_description_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'condition' => array(
					'rael_price_box_layout!' => '2',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box-description *' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_description_typography',
				'selector'  => '{{WRAPPER}} .rael-price-box-description',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'condition' => array(
					'rael_price_box_layout!' => '2',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register controls for the Description Style section (for layout 2).
	 *
	 * This method defines the Elementor controls for the Description Style section (specifically for layout 2),
	 * including controls for description tag, color, and typography.
	 */
	protected function register_description_style_controls() {

		$this->start_controls_section(
			'rael_description_style_for_layout_2',
			array(
				'label'      => __( 'Description', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => array(
					'rael_price_box_layout' => '2',
				),
			)
		);

		$this->add_control(
			'rael_description_tag_layout_2',
			array(
				'label'     => __( 'Description Tag', 'responsive-addons-for-elementor' ),
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
				'condition' => array(
					'rael_price_box_layout' => '2',
				),
				'default'   => 'p',
			)
		);
		$this->add_control(
			'rael_description_color_layout_2',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'condition' => array(
					'rael_price_box_layout' => '2',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box-description *' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_description_typography_layout_2',
				'selector'  => '{{WRAPPER}} .rael-price-box-description',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'condition' => array(
					'rael_price_box_layout' => '2',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register controls for styling the pricing section.
	 *
	 * This function is responsible for registering various Elementor controls related
	 * to the styling of the pricing section in the Responsive Elementor Addons.
	 */
	protected function register_pricing_style_controls() {
		$this->start_controls_section(
			'rael_pricing_style',
			array(
				'label'      => __( 'Pricing', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'rael_pricing_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box--layout-3 .rael-price-box__pricing, {{WRAPPER}} .rael-price-box--layout-2 .rael-price-box__price-container, {{WRAPPER}} .rael-price-box--layout-1 .rael-price-box__price-container, {{WRAPPER}} .rael-price-box--layout-4 .rael-price-box__price-container' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'rael_price_bg_size',
			array(
				'label'      => __( 'Background Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 300,
					),
					'em' => array(
						'min' => 5,
						'max' => 20,
					),
				),
				'default'    => array(
					'size' => '9',
					'unit' => 'em',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-box--layout-3 .rael-price-box__pricing' => 'min-height: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; margin-top: calc( -{{SIZE}}{{UNIT}} / 2 ); box-sizing: content-box;',
					'{{WRAPPER}} .rael-price-box--layout-3 .rael-price-box-header' => 'padding-bottom: calc( {{SIZE}}{{UNIT}} / 2 );',
				),
				'condition'  => array(
					'rael_price_box_layout' => '3',
				),
			)
		);

		$this->add_responsive_control(
			'rael_price_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'condition'  => array(
					'rael_price_box_layout!' => '3',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-box__price-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'rael_price_border_for_layout_3',
				'label'          => __( 'Border', 'responsive-addons-for-elementor' ),
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'width'  => array(
						'default' => array(
							'top'    => '3',
							'right'  => '3',
							'bottom' => '3',
							'left'   => '3',
						),
					),
				),
				'condition'      => array(
					'rael_price_box_layout' => '3',
				),
				'selector'       => '{{WRAPPER}} .rael-price-box--layout-3 .rael-price-box__pricing',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'rael_price_shadow',
				'condition' => array(
					'rael_price_box_layout' => '3',
				),
				'selector'  => '{{WRAPPER}} .rael-price-box__pricing',
			)
		);

		$this->add_control(
			'rael_main_price_style',
			array(
				'label'     => __( 'Price', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_price_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box__currency, {{WRAPPER}} .rael-price-box__integer-part, {{WRAPPER}} .rael-price-box__fractional-part, {{WRAPPER}} .rael-price-currency--normal' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_price_typography',
				'selector' => '{{WRAPPER}} .rael-pricing-value',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			)
		);

		$this->add_control(
			'rael_heading_currency_style',
			array(
				'label'     => __( 'Currency Symbol', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_currency_symbol!' => '',
					'rael_currency_format!' => ',',
				),
			)
		);

		$this->add_responsive_control(
			'rael_currency_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box__currency' => 'font-size: calc({{SIZE}}em/100)',
				),
				'condition' => array(
					'rael_currency_symbol!' => '',
					'rael_currency_format!' => ',',
				),
			)
		);

		$this->add_control(
			'rael_currency_vertical_position',
			array(
				'label'                => __( 'Vertical Position', 'responsive-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
					'top'    => array(
						'title' => __( 'Top', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'condition'            => array(
					'rael_currency_symbol!' => '',
					'rael_currency_format!' => ',',
				),
				'default'              => 'top',
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .rael-price-box__currency' => 'align-self: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'rael_fractional_part_style',
			array(
				'label'     => __( 'Fractional Part', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_currency_format!' => ',',
				),
			)
		);

		$this->add_responsive_control(
			'rael_fractional_part_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'condition' => array(
					'rael_currency_format!' => ',',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box__fractional-part' => 'font-size: calc({{SIZE}}em/100)',
				),
			)
		);

		$this->add_control(
			'rael_fractional_part_position',
			array(
				'label'                => __( 'Vertical Position', 'responsive-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
					'top'    => array(
						'title' => __( 'Top', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'              => 'top',
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'condition'            => array(
					'rael_currency_format!' => ',',
				),
				'selectors'            => array(
					'{{WRAPPER}} .rael-price-box__beside-price' => 'align-self: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_heading_original_price_style',
			array(
				'label'     => __( 'Original Price', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_sale'            => 'yes',
					'rael_original_price!' => '',
				),
			)
		);

		$this->add_control(
			'rael_original_price_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box__original-price' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_sale'            => 'yes',
					'rael_original_price!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_original_price_typography',
				'selector'  => '{{WRAPPER}} .rael-price-box__original-price',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'condition' => array(
					'rael_sale'            => 'yes',
					'rael_original_price!' => '',
				),
			)
		);

		$this->add_control(
			'rael_original_price_vertical_position',
			array(
				'label'                => __( 'Vertical Position', 'responsive-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
					'top'    => array(
						'title' => __( 'Top', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'default'              => 'middle',
				'selectors'            => array(
					'{{WRAPPER}} .rael-price-box__original-price' => 'align-self: {{VALUE}}',
				),
				'condition'            => array(
					'rael_sale'            => 'yes',
					'rael_original_price!' => '',
				),
			)
		);

		$this->add_control(
			'rael_heading_duration_style',
			array(
				'label'     => __( 'Duration', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_duration!' => '',
				),
			)
		);

		$this->add_control(
			'rael_duration_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box__duration' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'rael_duration!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_duration_typography',
				'selector'  => '{{WRAPPER}} .rael-price-box__duration',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'condition' => array(
					'rael_duration!' => '',
				),
			)
		);

		$this->add_control(
			'rael_duration_part_position',
			array(
				'label'                => __( 'Vertical Position', 'responsive-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
					'top'    => array(
						'title' => __( 'Top', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'              => 'bottom',
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'condition'            => array(
					'rael_duration_position' => 'beside',
					'rael_currency_format'   => ',',
					'rael_price_box_layout!' => '2',
				),
				'selectors'            => array(
					'{{WRAPPER}} .rael-price-box__beside-price' => 'align-self: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register controls for styling the content section.
	 *
	 * This function is responsible for registering various Elementor controls related
	 * to the styling of the content section in the Responsive Elementor Addons.
	 */
	protected function register_content_style_controls() {
		$this->start_controls_section(
			'rael_features_list_style',
			array(
				'label'      => __( 'Content', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'rael_price_features_layout',
			array(
				'label'        => __( 'Layout', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'simple',
				'label_block'  => false,
				'options'      => array(
					'simple'    => __( 'Simple', 'responsive-addons-for-elementor' ),
					'divider'   => __( 'Divider between fields', 'responsive-addons-for-elementor' ),
					'borderbox' => __( 'Box Layout', 'responsive-addons-for-elementor' ),
					'strips'    => __( 'Stripped Layout', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'rael-price-box__features--',
			)
		);

		$this->add_control(
			'rael_features_list_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'rael_price_features_layout!' => 'strips',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box__features-list, {{WRAPPER}} .rael-price-box--layout-3 .rael-price-box__price-container' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'rael_features_list_padding',
			array(
				'label'      => __( 'Box Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-box__features-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_features_list_style_fields',
			array(
				'label'     => __( 'Features List', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'rael_features_icon_spacing',
			array(
				'label'     => __( 'Icon Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 5,
					'unit' => 'px',
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box__features-list i,
					{{WRAPPER}} .rael-price-box__features-list svg' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_features_icon_size',
			array(
				'label'     => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 1,
					'unit' => 'rem',
				),
				'range'     => array(
					'rem' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box__features-list li i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-price-box__features-list svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_features_icon_color',
			array(
				'label'     => __( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box__features-list i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .rael-price-box__features-list svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_features_list_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box__features-list' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_features_list_typography',
				'selector' => '{{WRAPPER}} .rael-price-box__features-list li',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			)
		);

		$this->add_responsive_control(
			'rael_features_rows_padding',
			array(
				'label'      => __( 'Item Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-box__feature-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_features_list_alignment',
			array(
				'label'       => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
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
				'selectors'   => array(
					'{{WRAPPER}} .rael-price-box__features-list' => 'text-align: {{VALUE}}',
				),
				'default'     => 'center',
			)
		);

		$this->add_control(
			'rael_features_list_divider_heading',
			array(
				'label'     => __( 'Divider', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_price_features_layout' => 'divider',
				),
			)
		);

		$this->add_control(
			'rael_features_list_borderbox',
			array(
				'label'     => __( 'Box Layout', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_price_features_layout' => 'borderbox',
				),
			)
		);

		$this->add_control(
			'rael_divider_style',
			array(
				'label'     => __( 'Style', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'solid'  => __( 'Solid', 'responsive-addons-for-elementor' ),
					'double' => __( 'Double', 'responsive-addons-for-elementor' ),
					'dotted' => __( 'Dotted', 'responsive-addons-for-elementor' ),
					'dashed' => __( 'Dashed', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_price_features_layout' => array( 'divider', 'borderbox' ),
				),
				'default'   => 'solid',
				'selectors' => array(
					'{{WRAPPER}}.rael-price-box__features--divider .rael-price-box__features-list li:before, {{WRAPPER}}.rael-price-box__features--borderbox .rael-price-box__features-list li:before, {{WRAPPER}}.rael-price-box__features--borderbox .rael-price-box__features-list li:after' => 'border-top-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_divider_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ddd',
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'condition' => array(
					'rael_price_features_layout' => array( 'divider', 'borderbox' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box__features-list li:before, {{WRAPPER}}.rael-price-box__features--borderbox .rael-price-box__features-list li:after' => 'border-top-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_divider_weight',
			array(
				'label'     => __( 'Weight', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 1,
					'unit' => 'px',
				),
				'range'     => array(
					'px' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'condition' => array(
					'rael_price_features_layout' => array( 'divider', 'borderbox' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box__features-list li:before, {{WRAPPER}}.rael-price-box__features--borderbox .rael-price-box__features-list li:after' => 'border-top-width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'rael_divider_width',
			array(
				'label'     => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '60',
					'unit' => 'px',
				),
				'condition' => array(
					'rael_price_features_layout' => 'divider',
				),
				'selectors' => array(
					'{{WRAPPER}}.rael-price-box__features--divider .rael-price-box__features-list li:before' => 'margin-left: calc((100% - {{SIZE}}%)/2); margin-right: calc((100% - {{SIZE}}%)/2)',
				),
			)
		);

		$this->add_control(
			'rael_features_even_odd_fields',
			array(
				'label'     => __( 'Stripped Layout', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_price_features_layout' => 'strips',
				),
			)
		);

		$this->start_controls_tabs( 'rael_features_list_style_tabs' );

		$this->start_controls_tab(
			'rael_features_even',
			array(
				'label'     => __( 'Even', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_price_features_layout' => 'strips',
				),
			)
		);

		$this->add_control(
			'rael_features_bg_color_even',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box .rael-price-box__features-list li:nth-child(even)' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'rael_price_features_layout' => 'strips',
				),
			)
		);

		$this->add_control(
			'rael_features_text_color_even',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box .rael-price-box__features-list li:nth-child(even)' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'rael_price_features_layout' => 'strips',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_tab_features_odd',
			array(
				'label'     => __( 'Odd', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_price_features_layout' => 'strips',
				),
			)
		);

		$this->add_control(
			'rael_table_features_bg_color_odd',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eaeaea',
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box .rael-price-box__features-list li:nth-child(odd)' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'rael_price_features_layout' => 'strips',
				),
			)
		);

		$this->add_control(
			'rael_table_features_text_color_odd',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box .rael-price-box__features-list li:nth-child(odd)' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'rael_price_features_layout' => 'strips',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_features_spacing',
			array(
				'label'     => __( 'Item Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'   => array(
					'size' => '0',
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box .rael-price-box__features-list li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_price_features_layout' => 'strips',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register controls for styling the tooltip in the content section.
	 *
	 * This function is responsible for registering various Elementor controls related
	 * to the styling of tooltips in the Responsive Elementor Addons.
	 */
	protected function register_tooltip_style_controls() {
		$this->start_controls_section(
			'rael_tooltip_style',
			array(
				'label'     => __( 'Tooltip', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_toggle_features_tooltip' => 'yes',
				),
			)
		);

			$this->add_control(
				'rael_tooltip_align',
				array(
					'label'     => __( 'Text Alignment', 'responsive-addons-for-elementor' ),
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
					'selectors' => array(
						'.rael-price-box-container-{{ID}} div[id^="tippy-"] .tippy-content' => 'text-align: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'rael_tooltip_typography',
					'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
					'selector' => '.rael-price-box-container-{{ID}} div[id^="tippy-"] .tippy-box .rael-tooltip-text',
				)
			);

			$this->add_control(
				'rael_tooltip_color',
				array(
					'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#FFFFFF',
					'selectors' => array(
						'.rael-price-box-container-{{ID}} div[id^="tippy-"] .tippy-box .tippy-content' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'rael_tooltip_bgcolor',
				array(
					'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#666666',
					'selectors' => array(
						'.rael-price-box-container-{{ID}} div[id^="tippy-"] .tippy-content' => 'background-color: {{VALUE}};',
						'.rael-price-box-container-{{ID}} div[id^="tippy-"] .tippy-box[data-placement="bottom"] .tippy-arrow' => 'border-bottom-color: {{VALUE}};',
						'.rael-price-box-container-{{ID}} div[id^="tippy-"] .tippy-box[data-placement="left"] .tippy-arrow' => 'border-left-color: {{VALUE}};',
						'.rael-price-box-container-{{ID}} div[id^="tippy-"] .tippy-box[data-placement="right"] .tippy-arrow' => 'border-right-color: {{VALUE}};',
						'.rael-price-box-container-{{ID}} div[id^="tippy-"] .tippy-box[data-placement="top"] .tippy-arrow' => 'border-top-color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'rael_tooltip_padding',
				array(
					'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px' ),
					'default'    => array(
						'top'    => '20',
						'bottom' => '20',
						'left'   => '20',
						'right'  => '20',
						'unit'   => 'px',
					),
					'selectors'  => array(
						'.rael-price-box-container-{{ID}} div[id^="tippy-"] .tippy-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'rael_tooltip_radius',
				array(
					'label'      => __( 'Rounded Corners', 'responsive-addons-for-elementor' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'default'    => array(
						'top'    => '10',
						'bottom' => '10',
						'left'   => '10',
						'right'  => '10',
						'unit'   => 'px',
					),
					'selectors'  => array(
						'.rael-price-box-container-{{ID}} div[id^="tippy-"] .tippy-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'      => 'rael_tooltip_shadow',
					'selector'  => '.rael-price-box-container-{{ID}} div[id^="tippy-"] .tippy-content',
					'separator' => '',
				)
			);

		$this->end_controls_section();
	}
		/**
		 * Register controls for styling the Call To Action (CTA) section.
		 *
		 * This function is responsible for registering various Elementor controls related
		 * to the styling of the Call To Action section in the Responsive Elementor Addons.
		 */
	protected function register_cta_style_controls() {
		$this->start_controls_section(
			'rael_footer_style',
			array(
				'label'      => __( 'Call To Action', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'rael_footer_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box__cta' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'rael_footer_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-box__cta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_heading_footer_link',
			array(
				'label'     => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_price_cta_type' => 'link',
				),
			)
		);

		$this->add_control(
			'rael_link_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => array(
					'{{WRAPPER}} a.rael-price-box__cta-link' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_price_cta_type' => 'link',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_link_typography',
				'selector'  => '{{WRAPPER}} a.rael-price-box__cta-link',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'condition' => array(
					'rael_price_cta_type' => 'link',
				),
			)
		);

		$this->add_control(
			'rael_heading_button_icon_svg',
			array(
				'label'     => __( 'Icon', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_price_cta_type' => 'button',
				),
			)
		);

		$this->add_responsive_control(
			'rael_cta_button_icon_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 1,
					'unit' => 'rem',
				),
				'range'     => array(
					'rem' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box__cta .rael-button-container i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-price-box__cta .rael-button-container svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_heading_footer_button',
			array(
				'label'     => __( 'Button', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_price_cta_type' => 'button',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_button_typography',
				'selector'  => '{{WRAPPER}} .elementor-button, {{WRAPPER}} a.elementor-button',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'condition' => array(
					'rael_price_cta_type' => 'button',
				),
			)
		);

		$this->add_control(
			'rael_button_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'md',
				'options'   => array(
					'xs' => __( 'Extra Small', 'responsive-addons-for-elementor' ),
					'sm' => __( 'Small', 'responsive-addons-for-elementor' ),
					'md' => __( 'Medium', 'responsive-addons-for-elementor' ),
					'lg' => __( 'Large', 'responsive-addons-for-elementor' ),
					'xl' => __( 'Extra Large', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_price_cta_type' => 'button',
				),
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
				'condition'  => array(
					'rael_price_cta_type' => 'button',
				),
				'separator'  => 'after',
			)
		);

		$this->start_controls_tabs( 'rael_button_style_tabs' );

			$this->start_controls_tab(
				'rael_button_normal_state',
				array(
					'label'     => __( 'Normal', 'responsive-addons-for-elementor' ),
					'condition' => array(
						'rael_price_cta_type' => 'button',
					),
				)
			);

			$this->add_control(
				'rael_cta_text_color',
				array(
					'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
						'{{WRAPPER}} .elementor-button' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .rael-price-box__cta .rael-button-container svg' => 'fill: {{VALUE}};',
					),
					'condition' => array(
						'rael_price_cta_type' => 'button',
					),
				)
			);

			$this->add_control(
				'rael_button_bg_color',
				array(
					'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'global'    => [
						'default' => Global_Colors::COLOR_ACCENT,
					],
					'selectors' => array(
						'{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
					),
					'condition' => array(
						'rael_price_cta_type' => 'button',
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
					'condition'   => array(
						'rael_price_cta_type' => 'button',
					),
					'selectors'   => array(
						'{{WRAPPER}} .elementor-button' => 'border-style: {{VALUE}};',
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
						'rael_price_cta_type' => 'button',
						'rael_button_border!' => 'none',
					),
					'selectors'  => array(
						'{{WRAPPER}} .rael-price-box .elementor-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'rael_button_border_color',
				array(
					'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'condition' => array(
						'rael_price_cta_type' => 'button',
						'rael_button_border!' => 'none',
					),
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .rael-price-box .elementor-button' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'rael_button_border_radius',
				array(
					'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition'  => array(
						'rael_price_cta_type' => 'button',
					),
				)
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'      => 'rael_button_box_shadow',
					'label'     => __( 'Button Shadow', 'responsive-addons-for-elementor' ),
					'condition' => array(
						'rael_price_cta_type' => 'button',
					),
					'selector'  => '{{WRAPPER}} .elementor-button',
				)
			);

			$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_button_hover_state',
			array(
				'label'     => __( 'Hover', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_price_cta_type' => 'button',
				),
			)
		);

		$this->add_control(
			'rael_button_hover_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-price-box__cta .rael-button-container:hover svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'rael_price_cta_type' => 'button',
				),
			)
		);

		$this->add_control(
			'rael_button_bg_hover_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-button:hover' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_price_cta_type' => 'button',
				),
			)
		);

		$this->add_control(
			'rael_button_hover_border_color',
			array(
				'label'     => __( 'Border Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_price_cta_type' => 'button',
					'rael_button_border!' => 'none',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'rael_button_hover_box_shadow',
				'label'     => __( 'Hover Shadow', 'responsive-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .elementor-button:hover',
				'separator' => 'before',
				'condition' => array(
					'rael_price_cta_type' => 'button',
				),
			)
		);

		$this->add_control(
			'rael_button_hover_animation',
			array(
				'label'     => __( 'Animation', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HOVER_ANIMATION,
				'condition' => array(
					'rael_price_cta_type' => 'button',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'rael_heading_additional_info',
			array(
				'label'     => __( 'Disclaimer Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_disclaimer_text!' => '',
				),
			)
		);
		$this->add_control(
			'additional_info_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'   => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box__disclaimer' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'rael_disclaimer_text!' => '',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_additional_info_typography',
				'selector'  => '{{WRAPPER}} .rael-price-box__disclaimer',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'condition' => array(
					'rael_disclaimer_text!' => '',
				),
			)
		);

		$this->add_control(
			'rael_additional_info_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'    => 20,
					'right'  => 20,
					'bottom' => 20,
					'left'   => 20,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-box__disclaimer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
				'condition'  => array(
					'rael_disclaimer_text!' => '',
				),
			)
		);
		$this->end_controls_section();
	}
	/**
	 * Register style controls for the separator section.
	 */
	protected function register_separator_style_controls() {
		$this->start_controls_section(
			'rael_separator_style',
			array(
				'label'      => __( 'Separator', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => array(
					'rael_price_box_layout' => '2',
				),
			)
		);

		$this->add_control(
			'rael_price_box_separator_style',
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
				'condition'   => array(
					'rael_toggle_price_box_separator' => 'yes',
					'rael_price_box_layout'           => '2',
				),
				'selectors'   => array(
					'{{WRAPPER}} .rael-separator' => 'border-top-style: {{VALUE}}; display: inline-block;',
				),
			)
		);

		$this->add_control(
			'rael_price_box_separator_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eaeaea',
				'condition' => array(
					'rael_toggle_price_box_separator' => 'yes',
					'rael_price_box_layout'           => '2',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-separator' => 'border-top-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_price_box_separator_thickness',
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
					'size' => 2,
					'unit' => 'px',
				),
				'condition'  => array(
					'rael_toggle_price_box_separator' => 'yes',
					'rael_price_box_layout'           => '2',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-separator' => 'border-top-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_price_box_separator_width',
			array(
				'label'          => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( '%', 'px' ),
				'range'          => array(
					'px' => array(
						'max' => 1200,
					),
				),
				'default'        => array(
					'size' => 70,
					'unit' => '%',
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'label_block'    => true,
				'condition'      => array(
					'rael_toggle_price_box_separator' => 'yes',
					'rael_price_box_layout'           => '2',
				),
				'selectors'      => array(
					'{{WRAPPER}} .rael-separator' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register style controls for the ribbon section.
	 */
	protected function register_ribbon_style_controls() {
		$this->start_controls_section(
			'rael_ribbon_style',
			array(
				'label'      => __( 'Ribbon', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => array(
					'rael_show_ribbon!' => 'none',
				),
			)
		);
		$this->add_control(
			'rael_ribbon_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box-ribbon-content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .rael-price-box-ribbon-3 .rael-price-box-ribbon-content:before' => 'border-left: 8px solid {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_ribbon_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-box-ribbon-3 .rael-price-box-ribbon-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
				'condition'  => array(
					'rael_show_ribbon' => '3',
				),
			)
		);

		$this->add_control(
			'rael_ribbon_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-price-box-ribbon-content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_ribbon_typography',
				'selector' => '{{WRAPPER}} .rael-price-box-ribbon-content',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_box_shadow',
				'selector' => '{{WRAPPER}} .rael-price-box-ribbon-content',
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Get the HTML entity for a given currency symbol name.
	 *
	 * @param string $symbol_name The name of the currency symbol.
	 * @return string The HTML entity representing the currency symbol.
	 */
	public function rael_get_currency_symbol( $symbol_name ) {
		$symbols = array(
			'dollar'       => '&#36;',
			'franc'        => '&#8355;',
			'euro'         => '&#128;',
			'ruble'        => '&#8381;',
			'pound'        => '&#163;',
			'indian_rupee' => '&#8377;',
			'baht'         => '&#3647;',
			'shekel'       => '&#8362;',
			'yen'          => '&#165;',
			'guilder'      => '&fnof;',
			'won'          => '&#8361;',
			'peso'         => '&#8369;',
			'lira'         => '&#8356;',
			'peseta'       => '&#8359;',
			'rupee'        => '&#8360;',
			'real'         => 'R$',
			'krona'        => 'kr',
		);
		return isset( $symbols[ $symbol_name ] ) ? $symbols[ $symbol_name ] : '';
	}
	/**
	 * Get the data attributes for the tooltip based on the provided settings and device type.
	 *
	 * @param array $settings The settings for the tooltip.
	 * @param bool  $device   The type of device (true for mobile, false for desktop).
	 * @return string The HTML data attributes for the tooltip.
	 */
	public function rael_get_data_attrs( $settings, $device ) {

		$marker_length = count( $settings['rael_features_list'] );

		$side          = $settings['rael_tooltip_position'];
		$trigger       = '';
		$arrow         = $settings['rael_arrow'];
		$animation     = $settings['rael_tooltip_animation'];
		$zindex        = ( 'yes' == $settings['rael_tooltip_adv_settings'] ) ? $settings['rael_zindex'] : 99;
		$delay         = 300;
		$anim_duration = ( 'yes' == $settings['rael_tooltip_adv_settings'] ) ? $settings['rael_tooltip_animation_duration']['size'] : 350;
		$distance      = ( isset( $settings['rael_distance']['size'] ) && '' != $settings['rael_distance']['size'] ) ? $settings['rael_distance']['size'] : 6;
		$maxwidth      = 250;
		$minwidth      = 0;

		if ( true == $device ) {
			$trigger = 'click';
		} else {
			$trigger = $settings['rael_trigger'];
		}

		$maxwidth       = apply_filters( 'rael_tooltip_maxwidth', $maxwidth, $settings );
		$minwidth       = apply_filters( 'rael_tooltip_minwidth', $minwidth, $settings );
		$responsive     = $settings['rael_responsive_support'];
		$enable_tooltip = $settings['rael_toggle_features_tooltip'];

		$data_attr  = 'data-side="' . $side . '" ';
		$data_attr .= 'data-hotspottrigger="' . $trigger . '" ';
		$data_attr .= 'data-arrow="' . $arrow . '" ';
		$data_attr .= 'data-distance="' . $distance . '" ';
		$data_attr .= 'data-delay="' . $delay . '" ';
		$data_attr .= 'data-animation="' . $animation . '" ';
		$data_attr .= 'data-animduration="' . $anim_duration . '" ';
		$data_attr .= 'data-zindex="' . $zindex . '" ';
		$data_attr .= 'data-length="' . $marker_length . '" ';
		$data_attr .= 'data-tooltip-maxwidth="' . $maxwidth . '" ';
		$data_attr .= 'data-tooltip-minwidth="' . $minwidth . '" ';
		$data_attr .= 'data-tooltip-responsive="' . $responsive . '" ';
		$data_attr .= 'data-enable-tooltip="' . $enable_tooltip . '" ';

		return $data_attr;
	}
	/**
	 * Render the header section of the price box based on the provided settings.
	 *
	 * @param array $settings The settings for the price box.
	 */
	public function render_header( $settings ) {
		if ( '2' == $settings['rael_price_box_layout'] ) :
			if ( $settings['rael_title'] ) :
				?>
				<div class="rael-price-box-header">
					<?php $this->render_heading_icon( $settings ); ?>
					<?php $this->render_heading_text( $settings ); ?>
				</div>
				<?php
			endif;
		else :
			if ( $settings['rael_title'] || $settings['rael_description'] ) :
				?>
				<div class="rael-price-box-header">
					<div class="rael-price-box-header__container">
						<?php $this->render_heading_icon( $settings ); ?>
						<?php $this->render_heading_text( $settings ); ?>
						<?php $this->render_description( $settings ); ?>
					</div>
				</div>
				<?php
			endif;
		endif;
	}
	/**
	 * Render the icon within the price box header based on the provided settings.
	 *
	 * @param array $settings The settings for the price box.
	 */
	public function render_heading_icon( $settings ) {
		if ( ! empty( $settings['rael_heading_icon']['value'] ) ) :
			?>
			<div class="rael-price-box-header__icon">
				<?php
				Icons_Manager::render_icon( $settings['rael_heading_icon'], array( 'aria-hidden' => 'true' ) );
				?>
			</div>
			<?php
		endif;
	}
	/**
	 * Render the text within the price box header based on the provided settings.
	 *
	 * @param array $settings The settings for the price box.
	 */
	public function render_heading_text( $settings ) {
		if ( $settings['rael_title'] ) :
			if ( ! empty( $settings['rael_title'] ) ) :
				$html_tag = esc_attr( $settings['rael_heading_tag'] );

				$this->add_inline_editing_attributes( 'rael_title', 'basic' );
				$this->add_render_attribute( 'rael_title', 'class', 'rael-price-box__heading' );
				?>
				<div class="rael-price-box-header__text">
					<<?php echo esc_html( $html_tag ); ?>
						<?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_title' ) ); ?>
					>
						<?php echo wp_kses_post( $settings['rael_title'] ); ?>
					</<?php echo esc_html( $html_tag ); ?>>
				</div>
				<?php
			endif;
		endif;
	}
	/**
	 * Render the description based on the price box layout.
	 *
	 * @param array $settings The settings for the price box.
	 */
	public function render_description( $settings ) {
		if ( ! empty( $settings['rael_description'] ) || ! empty( $settings['rael_description_for_layout_2'] ) ) :
			$html_tag_layout2 = esc_attr( $settings['rael_description_tag_layout_2'] );
			$html_tag         = esc_attr( $settings['rael_description_tag'] );

			$this->add_render_attribute( 'rael_description', 'class', 'rael-price-box-description__text' );
			$this->add_inline_editing_attributes( 'rael_description', 'basic' );
			?>
			<div class="rael-price-box-description">
				<?php if ( '2' == $settings['rael_price_box_layout'] ) { ?>
					<<?php echo esc_html( $html_tag_layout2 ); ?>
						<?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_description' ) ); ?>
					>
						<?php echo wp_kses_post( $settings['rael_description_for_layout_2'] ); ?>
					</<?php echo esc_html( $html_tag_layout2 ); ?>>
				<?php } else { ?>
					<<?php echo esc_html( $html_tag ); ?>
						<?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_description' ) ); ?>
					>
						<?php echo wp_kses_post( $settings['rael_description'] ); ?>
					</<?php echo esc_html( $html_tag ); ?>>
				<?php } ?>
			</div>
			<?php
		endif;
	}
		/**
		 * Render the price based on the provided settings.
		 *
		 * @param array $settings The settings for the price box.
		 */
	public function render_price( $settings ) {
		$symbols = '';

		if ( ! empty( $settings['rael_currency_symbol'] ) ) {
			if ( 'custom' != $settings['rael_currency_symbol'] ) {
				$symbol = $this->rael_get_currency_symbol( $settings['rael_currency_symbol'] );
			} else {
				$symbol = $settings['rael_currency_symbol_custom'];
			}
		}

		$currency_format = empty( $settings['rael_currency_format'] ) ? '.' : $settings['rael_currency_format'];
		$price           = explode( $currency_format, $settings['rael_price'] );
		$intvalue        = $price[0];
		$fraction        = '';
		if ( 2 == count( $price ) ) {
			$fraction = $price[1];
		}

		$this->add_inline_editing_attributes( 'rael_duration', 'basic' );
		$this->add_render_attribute( 'rael_duration', 'class', array( 'rael-price-box__duration ', 'rael-price-box-typo-excluded' ) );

		$duration_position = $settings['rael_duration_position'];
		$duration_element  = "<span {$this->get_render_attribute_string( 'rael_duration' )}>" . wp_kses_post( $settings['rael_duration'] ) . '</span>';
		?>
		<div class="rael-price-box__price-container">
			<div class="rael-price-box__pricing">
				<div class="rael-pricing-container">
					<div class="rael-pricing-value">
						<?php if ( 'yes' == $settings['rael_sale'] && ! empty( $settings['rael_original_price'] ) ) : ?>
							<span class="rael-price-box__original-price rael-price-box-typo-excluded">
								<?php echo esc_html( $symbol ) . wp_kses_post( $settings['rael_original_price'] ); ?>
							</span>
						<?php endif; ?>

						<?php if ( ! empty( $symbol ) && ',' != $settings['rael_currency_format'] ) : ?>
							<span class="rael-price-box__currency">
								<?php echo esc_attr( $symbol ); ?>
							</span>
						<?php endif; ?>

						<?php if ( ! empty( $intvalue ) || 0 <= $intvalue ) : ?>
							<?php if ( ! empty( $symbol ) && ',' == $settings['rael_currency_format'] ) : ?>
								<span class="rael-price-currency--normal">
									<?php echo esc_attr( $symbol ); ?>
								</span>
							<?php endif; ?>
							<span class="rael-price-box__integer-part">
								<?php echo wp_kses_post( $intvalue ); ?>
							</span>
						<?php endif; ?>

						<?php if ( '' != $fraction || ( ! empty( $settings['rael_duration'] ) && 'beside' == $duration_position ) ) : ?>
							<span class="rael-price-box__beside-price">
								<span class="rael-price-box__fractional-part">
									<?php echo wp_kses_post( $fraction ); ?>
								</span>
								<?php if ( ! empty( $settings['rael_duration'] ) && 'beside' == $duration_position && '3' != $settings['rael_price_box_layout'] ) : ?>
									<?php echo wp_kses_post( $duration_element ); ?>
								<?php endif; ?>
							</span>
						<?php endif; ?>
					</div>
					<?php if ( ! empty( $settings['rael_duration'] ) ) : ?>
						<?php if ( '3' == $settings['rael_price_box_layout'] || 'below' == $duration_position ) : ?>
							<div class="rael-pricing-duration">
								<?php echo wp_kses_post( $duration_element ); ?>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}
	/**
	 * Render the features list based on the provided settings.
	 *
	 * @param array $settings The settings for the price box.
	 */
	public function render_features( $settings ) {
		if ( ! empty( $settings['rael_features_list'] ) ) :
			$node_id = $this->get_id();

			$device = false;

			$iphone  = ( false != ( stripos( $_SERVER['HTTP_USER_AGENT'], 'iPhone' ) ) ? true : false ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$ipad    = ( false != ( stripos( $_SERVER['HTTP_USER_AGENT'], 'iPad' ) ) ? true : false ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$android = ( false != ( stripos( $_SERVER['HTTP_USER_AGENT'], 'Android' ) ) ? true : false ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			if ( $iphone || $ipad || $android ) {
				$device = true;
			}
			?>
			<ul class="rael-price-box__features-list" <?php echo $this->rael_get_data_attrs( $settings, $device ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php foreach ( $settings['rael_features_list'] as  $index => $item ) : ?>
					<?php
					$title_key  = $this->get_repeater_setting_key( 'rael_item_text', 'rael_features_list', $index );
					$content_id = $this->get_id() . '-' . $item['_id'];
					$node_class = ! empty( $item['rael_tooltip_content'] ) ? 'rael-price-box__feature-content rael-price-box__content-' . $node_id : 'rael-price-box__feature-content';

					$this->add_inline_editing_attributes( $title_key, 'basic' );

					if ( 'yes' == $settings['rael_toggle_features_tooltip'] && ! empty( $item['rael_tooltip_content'] ) ) {
						$tooltip_content = '<span class="' . esc_attr( 'rael-tooltip-text' ) . '" >' . wp_kses_post( $item['rael_tooltip_content'] ) . '</span>';
						$this->add_render_attribute( 'rael_tooltip_content_' . $item['_id'], 'data-tippy-content', $tooltip_content );
					}
					?>
					<li class="elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
						<div
							class="<?php echo esc_attr( $node_class ); ?>"
							<?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_tooltip_content_' . esc_attr( $item['_id'] ) ) ); ?>
						>
							<?php
							if ( self::is_elementor_updated() ) {

								$migration_allowed = Icons_Manager::is_migration_allowed();

								if ( ! isset( $item['rael_item_icon'] ) && ! $migration_allowed ) {
									// add old default.
									$item['rael_item_icon'] = 'fa fa-arrow-circle-right';
								}
								$has_icon = ! empty( $item['rael_item_icon'] );

								if ( ! $has_icon && ! empty( $item['rael_new_item_icon']['value'] ) ) {
									$has_icon = true;
								}

								if ( $has_icon ) :
									$features_marker_migrated = isset( $item['__fa4_migrated']['rael_new_item_icon'] );
									$features_marker_is_new   = ! isset( $item['rael_item_icon'] ) && $migration_allowed;

									if ( $features_marker_migrated || $features_marker_is_new ) {
										Icons_Manager::render_icon( $item['rael_new_item_icon'], array( 'aria-hidden' => 'true' ) );
									} elseif ( ! empty( $item['rael_item_icon'] ) ) {
										?>
											<i class="<?php echo esc_attr( $item['rael_item_icon'] ); ?>" aria-hidden="true"></i>
										<?php } ?>

									<?php endif; ?>
								<?php } elseif ( ! empty( $item['rael_item_icon'] ) ) { ?>
									<i class="<?php echo esc_attr( $item['rael_item_icon'] ); ?>" aria-hidden="true"></i>
								<?php } ?>
							<?php

							if ( ! empty( $item['rael_item_text'] ) ) :
								?>
							<span <?php echo wp_kses_post( $this->get_render_attribute_string( $title_key ) ); ?>>
								<?php echo wp_kses_post( $item['rael_item_text'] ); ?>
							</span>
								<?php
							else :
								echo '&nbsp;';
							endif;
							?>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php
		endif;
	}
	/**
	 * Render the icon within the CTA button based on the provided settings and position.
	 *
	 * @param array  $settings  The settings for the CTA button.
	 * @param string $position  The position of the icon ('before' or 'after').
	 */
	public function render_button_icon( $settings, $position ) {
		$this->add_render_attribute( 'rael_cta_button_icon', 'class', 'rael-cta-link-icon rael-cta-link-icon-' . $position );
		if ( self::is_elementor_updated() ) {
			$cta_migrated = isset( $settings['__fa4_migrated']['rael_new_cta_icon'] );
			$cta_is_new   = empty( $settings['rael_cta_icon'] );
			if ( ! empty( $settings['rael_cta_icon'] ) || ! empty( $settings['rael_new_cta_icon'] ) ) {
				?>
				<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_cta_button_icon' ) ); ?>>
					<?php
					if ( $cta_is_new || $cta_migrated ) {
						Icons_Manager::render_icon( $settings['rael_new_cta_icon'], array( 'aria-hidden' => 'true' ) );
					} else {
						?>
						<i class="<?php echo esc_attr( $settings['rael_cta_icon'] ); ?>" aria-hidden="true"></i>
						<?php
					}
					?>
				</span>
			<?php } ?>
		<?php } elseif ( ! empty( $settings['rael_cta_icon'] ) ) { ?>
			<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_cta_button_icon' ) ); ?>>
				<i class="<?php echo esc_attr( $settings['rael_cta_icon'] ); ?>" aria-hidden="true"></i>
			</span>
			<?php
		}
	}
	/**
	 * Render the CTA button based on the provided settings.
	 *
	 * @param array $settings The settings for the CTA button.
	 */
	public function render_button( $settings ) {
		if ( 'link' == $settings['rael_price_cta_type'] ) {

			$_nofollow = ( 'on' == $settings['rael_link']['nofollow'] ) ? 'nofollow' : '';
			$_target   = ( 'on' == $settings['rael_link']['is_external'] ) ? '_blank' : '';
			$_link     = ( isset( $settings['rael_link']['url'] ) ) ? $settings['rael_link']['url'] : '';

			if ( ! empty( $settings['rael_link']['url'] ) ) {

				$this->add_render_attribute( 'rael_link', 'href', $settings['rael_link']['url'] );
				$this->add_render_attribute( 'rael_link', 'class', 'rael-price-box__cta-link' );

				if ( $settings['rael_link']['is_external'] ) {
					$this->add_render_attribute( 'rael_link', 'target', '_blank' );
				}
				if ( $settings['rael_link']['nofollow'] ) {
					$this->add_render_attribute( 'rael_link', 'rel', 'nofollow' );
				}
			}

			?>
			<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_link' ) ); ?>>
				<?php if ( 'left' == $settings['rael_cta_icon_position'] ) { ?>
					<?php $this->render_button_icon( $settings, 'before' ); ?>
				<?php } ?>
				<?php
				if ( ! empty( $settings['rael_cta_text'] ) ) {
					$this->add_inline_editing_attributes( 'rael_cta_text', 'basic' );
					?>
					<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_cta_text' ) ); ?>>
						<?php echo wp_kses_post( $settings['rael_cta_text'] ); ?>
					</span>
				<?php } ?>
				<?php
				if ( 'right' == $settings['rael_cta_icon_position'] ) {
					$this->render_button_icon( $settings, 'after' );
				}
				?>
			</a>
			<?php
		} elseif ( 'button' == $settings['rael_price_cta_type'] ) {

			$this->add_render_attribute( 'rael_cta_button_container', 'class', 'rael-button-container elementor-button-wrapper' );
			if ( ! empty( $settings['rael_link']['url'] ) ) {
				$this->add_render_attribute( 'rael_cta_button', 'href', $settings['rael_link']['url'] );
				$this->add_render_attribute( 'rael_cta_button', 'class', 'elementor-button-link' );

				if ( $settings['rael_link']['is_external'] ) {
					$this->add_render_attribute( 'rael_cta_button', 'target', '_blank' );
				}
				if ( $settings['rael_link']['nofollow'] ) {
					$this->add_render_attribute( 'rael_cta_button', 'rel', 'nofollow' );
				}
			}
			$this->add_render_attribute( 'rael_cta_button', 'class', ' elementor-button' );
			if ( ! empty( $settings['rael_button_size'] ) ) {
				$this->add_render_attribute( 'rael_cta_button', 'class', 'elementor-size-' . $settings['rael_button_size'] );
			}
			if ( ! empty( $settings['rael_button_hover_animation'] ) ) {
				$this->add_render_attribute( 'rael_cta_button', 'class', 'elementor-animation-' . $settings['rael_button_hover_animation'] );
			}

			?>
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_cta_button_container' ) ); ?>>
				<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_cta_button' ) ); ?>>
					<?php
						$this->add_inline_editing_attributes( 'rael_cta_text', 'none' );
						$this->add_render_attribute( 'rael_cta_text', 'class', 'elementor-button-text' );
					?>
					<?php
					if ( 'left' == $settings['rael_cta_icon_position'] ) {
						$this->render_button_icon( $settings, 'before' );
						?>
					<?php } ?>
					<?php
					if ( ! empty( $settings['rael_cta_text'] ) ) {
						?>
						<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_cta_text' ) ); ?> >
							<?php echo wp_kses_post( $settings['rael_cta_text'] ); ?>
						</span>
					<?php } ?>
					<?php
					if ( 'right' == $settings['rael_cta_icon_position'] ) {
						$this->render_button_icon( $settings, 'after' );
						?>
				<?php } ?>
				</a>
			</div>
			<?php
		}
	}
	/**
	 * Render the Call-to-Action (CTA) section based on the provided settings.
	 *
	 * @param array $settings The settings for the CTA section.
	 */
	public function render_cta( $settings ) {
		if ( 'none' != $settings['rael_price_cta_type'] || ! empty( $settings['rael_disclaimer_text'] ) ) :
			if ( ! empty( $settings['rael_cta_text'] ) || ! empty( $settings['rael_disclaimer_text'] )
					|| ! empty( $settings['rael_cta_icon'] ) || ! empty( $settings['rael_new_cta_icon'] ) ) :
				?>
				<div class="rael-price-box__cta">
					<?php if ( 'none' != $settings['rael_price_cta_type'] ) : ?>
							<?php $this->render_button( $settings ); ?>
					<?php endif; ?>

					<?php
					if ( ! empty( $settings['rael_disclaimer_text'] ) ) :
						$this->add_inline_editing_attributes( 'rael_disclaimer_text', 'basic' );
						$this->add_render_attribute( 'rael_disclaimer_text', 'class', 'rael-price-box__disclaimer' );
						?>
						<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_disclaimer_text' ) ); ?>>
							<?php echo wp_kses_post( $settings['rael_disclaimer_text'] ); ?>
						</div>
					<?php endif; ?>
				</div>
				<?php
			endif;
		endif;
	}
	/**
	 * Render the ribbon section based on the provided settings.
	 *
	 * @param array $settings The settings for the ribbon section.
	 */
	public function render_ribbon( $settings ) {
		$ribbon_style = '';

		if ( ! empty( $settings['rael_ribbon_title'] ) ) :
			if ( 'none' != $settings['rael_show_ribbon'] ) :
				if ( '1' == $settings['rael_show_ribbon'] ) {
					$ribbon_style = '1';
				} elseif ( '2' == $settings['rael_show_ribbon'] ) {
					$ribbon_style = '2';
				} elseif ( '3' == $settings['rael_show_ribbon'] ) {
					$ribbon_style = '3';
				}

				$this->add_render_attribute( 'rael_ribbon_container', 'class', 'rael-price-box-ribbon-' . $ribbon_style );

				if ( ! empty( $settings['rael_ribbon_horizontal_position'] ) ) :
					$this->add_render_attribute( 'rael_ribbon_container', 'class', 'rael-ribbon-' . $settings['rael_ribbon_horizontal_position'] );
				endif;

				$this->add_inline_editing_attributes( 'rael_ribbon_title' );
				$this->add_render_attribute( 'rael_ribbon_title', 'class', 'rael-price-box-ribbon-content' );
				?>
				<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_ribbon_container' ) ); ?>>
					<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_ribbon_title' ) ); ?>>
						<?php echo wp_kses_post( $settings['rael_ribbon_title'] ); ?>
					</div>
				</div>
				<?php
			endif;
		endif;
	}
	/**
	 * Render the separator based on the provided settings.
	 *
	 * @param array $settings The settings for the separator.
	 */
	public function render_separator( $settings ) {
		if ( ! empty( $settings['rael_features_list'] ) ) :
			if ( 'yes' == $settings['rael_toggle_price_box_separator'] && '2' == $settings['rael_price_box_layout'] ) :
				?>
				<div class="rael-separator-container">
					<div class="rael-separator"></div>
				</div>
			<?php endif; ?>
			<?php
		endif;
	}
	/**
	 * Render the layout with a specific structure (Layout 1).
	 *
	 * @param array $settings The settings for the layout.
	 */
	protected function layout_1( $settings ) {
		?>
		<div class="rael-price-box">
			<?php
			$this->render_header( $settings );
			$this->render_price( $settings );
			$this->render_features( $settings );
			$this->render_cta( $settings );
			?>
		</div>
		<?php
	}
	/**
	 * Render the layout with a specific structure (Layout 2).
	 *
	 * @param array $settings The settings for the layout.
	 */
	protected function layout_2( $settings ) {
		?>
		<div class="rael-price-box">
			<?php
			$this->render_header( $settings );
			$this->render_price( $settings );
			$this->render_description( $settings );
			$this->render_cta( $settings );
			$this->render_separator( $settings );
			$this->render_features( $settings );
			?>
		</div>
		<?php
	}
	/**
	 * Render the layout with a specific structure (Layout 3).
	 *
	 * @param array $settings The settings for the layout.
	 */
	protected function layout_3( $settings ) {
		?>
		<div class="rael-price-box">
			<?php
			$this->render_header( $settings );
			$this->render_price( $settings );
			$this->render_features( $settings );
			$this->render_cta( $settings );
			?>
		</div>
		<?php
	}
	/**
	 * Render the layout with a specific structure (Layout 4).
	 *
	 * @param array $settings The settings for the layout.
	 */
	protected function layout_4( $settings ) {
		?>
		<div class="rael-price-box">
			<?php
			$this->render_header( $settings );
			$this->render_features( $settings );
			$this->render_price( $settings );
			$this->render_cta( $settings );
			?>
		</div>
		<?php
	}

	/**
	 * Render the widget on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function render() {
		$settings     = $this->get_settings_for_display();
		$layout       = 'layout_' . $settings['rael_price_box_layout'];
		$layout_class = esc_attr( 'layout-' . $settings['rael_price_box_layout'] );
		$content_id   = esc_attr( $this->get_id() );
		?>
		<div class="rael-price-box-container rael-price-box-content rael-price-box--<?php printf( esc_attr( $layout_class ) ); ?> rael-price-box-container-<?php printf( esc_attr( $content_id ) ); ?>">
			<?php $this->{$layout}( $settings ); ?>
			<?php $this->render_ribbon( $settings ); ?>
		</div>
		<?php
	}

	/**
	 * Render output on the backend/preview window.
	 *
	 * Written in BackboneJS and used to generate the preview HTML.
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
		function rael_get_data_attributes() {
			var side			= settings.rael_tooltip_position;
			var trigger			= '';
			var arrow			= settings.rael_arrow;
			var animation		= settings.rael_tooltip_animation;
			var zindex			= ( 'yes' == settings.rael_tooltip_adv_settings ) ? settings.rael_zindex : 99;
			var delay			= 300;

			var anim_duration			= ( 'yes' == settings.rael_tooltip_adv_settings ) ? settings.rael_tooltip_animation_duration.size : 350;
			var distance			= ( '' != settings.rael_distance.size ) ? settings.rael_distance.size : 6;

			trigger = settings.rael_trigger;

			var responsive = settings.rael_responsive_support;
			var enable_tooltip = settings.rael_toggle_features_tooltip;

			var data_attr  = 'data-side="' + side + '" ';
				data_attr += 'data-hotspottrigger="' + trigger + '" ';
				data_attr += 'data-arrow="' + arrow + '" ';
				data_attr += 'data-distance="' + distance + '" ';
				data_attr += 'data-delay="' + delay + '" ';
				data_attr += 'data-animation="' + animation + '" ';
				data_attr += 'data-animduration="' + anim_duration + '" ';
				data_attr += 'data-zindex="' + zindex + '" ';
				data_attr += 'data-length="' + length + '" ';
				data_attr += 'data-tooltip-responsive="' + responsive + '" ';
				data_attr += 'data-enable-tooltip="' + enable_tooltip + '" ';
			return data_attr;
		}

		function render_heading_icon() {
			if ( '' != settings.rael_heading_icon.value && settings.rael_heading_icon.value ) {
				var headingIconsHTML = elementor.helpers.renderIcon( view, settings.rael_heading_icon, { 'aria-hidden': true }, 'i' , 'object' );

				#>
					<div class="rael-price-box-header__icon">
						{{{ headingIconsHTML.value }}}
					</div>
				<#
			}
		}

		function render_heading_text() {
			if ( settings.rael_title ) {
				if ( '' != settings.rael_title ) {
					var html_tag = settings.rael_heading_tag;

					view.addInlineEditingAttributes( 'rael_title', 'basic' );
					view.addRenderAttribute( 'rael_title', 'class', 'rael-price-box__heading' );
					#>
					<div class="rael-price-box-header__text">
						<{{{ html_tag }}} {{{ view.getRenderAttributeString( 'rael_title' ) }}}>
							{{{ settings.rael_title }}}
						</{{{ html_tag }}}>
					</div>
					<#
				}
			}
		}

		function render_description() {
			if ( '' != settings.rael_description || '2' == settings.rael_price_box_layout && '' != settings.rael_description_for_layout_2 ) {
			var html_tag_layout2 = settings.rael_description_tag_layout_2;
			var html_tag = settings.rael_description_tag;

			view.addInlineEditingAttributes( 'rael_description', 'basic' );
			view.addRenderAttribute( 'rael_description', 'class', 'rael-price-box-description__text' );

			view.addInlineEditingAttributes( 'rael_description_for_layout_2', 'basic' );
			view.addRenderAttribute( 'rael_description_for_layout_2', 'class', 'rael-price-box-description__text' );
			#>
				<div class="rael-price-box-description">
					<# if ( '2' == settings.rael_price_box_layout ) { #>
						<{{{ html_tag_layout2  }}} {{{ view.getRenderAttributeString( 'rael_description_for_layout_2' ) }}}>
							{{{ settings.rael_description_for_layout_2 }}}
						</{{{ html_tag_layout2 }}}>
					<# } else { #>
						<{{{ html_tag }}} {{{ view.getRenderAttributeString( 'rael_description' ) }}}>
							{{{ settings.rael_description }}}
						</{{{ html_tag }}}>
					<# } #>
				</div>
			<#
			}
		}

		function render_header() {
			if ( '2' == settings.rael_price_box_layout ) {
				if ( settings.rael_title ) {
					#>
					<div class="rael-price-box-header">
						<# render_heading_icon(); #>
						<# render_heading_text(); #>
					</div>
					<#
				}
			} else {
				if ( settings.rael_title || settings.rael_description ) {
					#>
					<div class="rael-price-box-header">
						<div class="rael-price-box-header__container">
							<# render_heading_icon(); #>
							<# render_heading_text(); #>
							<# render_description(); #>
						</div>
					</div>
					<#
				}
			}
		}

		function render_price() {
			var symbol = '';

			var rael_symbols = {
				dollar: '&#36;',
				euro: '&#128;',
				franc: '&#8355;',
				pound: '&#163;',
				ruble: '&#8381;',
				shekel: '&#8362;',
				baht: '&#3647;',
				yen: '&#165;',
				won: '&#8361;',
				guilder: '&fnof;',
				peso: '&#8369;',
				peseta: '&#8359;',
				lira: '&#8356;',
				rupee: '&#8360;',
				indian_rupee: '&#8377;',
				real: 'R$',
				krona: 'kr'
			};

			if ( settings.rael_currency_symbol ) {
				if ( 'custom' != settings.rael_currency_symbol ) {
					symbol = rael_symbols[ settings.rael_currency_symbol ] || '';
				} else {
					symbol = settings.rael_currency_symbol_custom;
				}
			}

			var currencyFormat = settings.rael_currency_format || '.',
				table_price = settings.rael_price.toString(),
				price = table_price.split( currencyFormat ),
				intvalue = price[0],
				fraction = price[1];

			view.addRenderAttribute( 'rael_duration', 'class', ['rael-price-box__duration ', 'rael-price-box-typo-excluded'] );
			view.addInlineEditingAttributes( 'rael_duration', 'basic' );

			var duration_position = settings.rael_duration_position;
			var	durationElement = "<span " + view.getRenderAttributeString( 'rael_duration' ) + ">" + settings.rael_duration + "</span>";
			#>
			<div class="rael-price-box__price-container">
				<div class="rael-price-box__pricing">
					<div class="rael-pricing-container">
						<div class="rael-pricing-value">
							<# if ( settings.rael_sale && settings.rael_original_price ) { #>
								<div class="rael-price-box__original-price rael-price-box-typo-excluded">
									{{{ symbol + settings.rael_original_price }}}
								</div>
							<# } #>

							<# if ( '' != symbol && ',' != settings.rael_currency_format) { #>
								<span class="rael-price-box__currency">{{{ symbol }}}</span>
							<# } #>

							<# if ( '' != intvalue || 0 <= intvalue ) { #>
								<# if ( '' != symbol && ',' == settings.rael_currency_format) { #>
									<span class="rael-price-currency--normal">{{{ symbol }}}</span>
								<# } #>
								<span class="rael-price-box__integer-part">{{{ intvalue }}}</span>
							<# } #>

							<span class="rael-price-box__beside-price">
								<# if ( '' != fraction ) { #>
									<span class="rael-price-box__fractional-part">
										{{{ fraction }}}
									</span>
								<# } #>
								<# if ( settings.rael_duration && 'beside' == duration_position && '3' != settings.rael_price_box_layout ) { #>
									{{{ durationElement }}}
								<# } #>
							</span>
						</div>
						<# if ( settings.rael_duration ) { #>
							<# if ( '3' == settings.rael_price_box_layout || 'below' == duration_position ) { #>
								<div class="rael-pricing-duration">
									{{{ durationElement }}}
								</div>
							<# } #>
						<# } #>
					</div>
				</div>
			</div>
			<#
		}

		function render_features() {
			var iconsHTML = {};
			var param = rael_get_data_attributes();
			var node_id = view.$el.data('id');

			if ( settings.rael_features_list ) { #>
				<ul class="rael-price-box__features-list" {{{ param }}}>
					<# _.each( settings.rael_features_list, function( item, index ) {
						var node_class = ( '' != item.rael_tooltip_content ) ? 'rael-price-box__feature-content rael-price-box__content-' +  node_id : 'rael-price-box__feature-content';

						if ( settings.rael_toggle_features_tooltip && '' != item.rael_tooltip_content ) {
							var tooltip_content = '<span class="rael-tooltip-text" >' + item.rael_tooltip_content + '</span>';
							view.addRenderAttribute( 'rael_tooltip_content_' + item._id, 'data-tippy-content', tooltip_content );
						}
					#>
						<li class="elementor-repeater-item-{{ item._id }}">
							<div class="{{ node_class }} " {{{ view.getRenderAttributeString( 'rael_tooltip_content_' + item._id ) }}}>
								<?php if ( self::is_elementor_updated() ) { ?>
									<# if ( item.rael_item_icon || item.rael_new_item_icon ) { #>
										<#
										iconsHTML[ index ] = elementor.helpers.renderIcon( view, item.rael_new_item_icon, { 'aria-hidden': true }, 'i' , 'object' );
										migrated = elementor.helpers.isIconMigrated( item, 'rael_new_item_icon' ); #>

										<# if ( ( ! item.rael_item_icon || migrated ) && iconsHTML[ index ] && iconsHTML[ index ].rendered ) { #>
											{{{ iconsHTML[ index ].value }}}
										<# } else if( '' != item.rael_item_icon ) { #>
											<i class="{{ item.rael_item_icon }}" aria-hidden="true"></i>
										<# } #>
									<# } #>
								<?php } else { ?>
									<i class="{{ item.rael_item_icon }}" aria-hidden="true"></i>
								<?php } ?>

								<# if ( ! _.isEmpty( item.rael_item_text.trim() ) ) { #>
									<span>{{{ item.rael_item_text }}}</span>
								<# } else { #>
									&nbsp;
								<# } #>
							</div>
						</li>
					<# } ); #>
				</ul>
			<# }
		}

		function render_cta_icon( position ) {
			view.addRenderAttribute( 'rael_cta_button_icon', 'class', 'rael-cta-link-icon rael-cta-link-icon-' + position ); #>
			<?php if ( self::is_elementor_updated() ) { ?>
				<# if ( settings.rael_cta_icon || settings.rael_new_cta_icon ) {  #>
				<#
				var cta_iconHTML = elementor.helpers.renderIcon( view, settings.rael_new_cta_icon, { 'aria-hidden': true }, 'i' , 'object' );
				var cta_migrated = elementor.helpers.isIconMigrated( settings, 'rael_new_cta_icon' );
				#>
					<span {{{ view.getRenderAttributeString( 'rael_cta_button_icon' ) }}}>
						<# if ( cta_iconHTML && cta_iconHTML.rendered && ( ! settings.rael_cta_icon || cta_migrated ) ) {
						#>
							{{{ cta_iconHTML.value }}}
						<# } else { #>
							<i class="{{{ settings.rael_cta_icon  }}}" aria-hidden="true"></i>
						<# } #>
					</span>
				<# } #>
			<?php } else { ?>
				<span {{{ view.getRenderAttributeString( 'rael_cta_button_icon' ) }}}>
					<i class="{{{ settings.rael_cta_icon }}}" aria-hidden="true"></i>
				</span>
			<?php } ?>
		<#
		}

		function render_cta() {
			if ( 'none' != settings.rael_price_cta_type || '' != settings.rael_disclaimer_text ) {
				if ( settings.rael_cta_text || settings.rael_cta_icon
					|| settings.rael_new_cta_icon || settings.rael_disclaimer_text ) { #>
					<div class="rael-price-box__cta">
						<#
						if( 'none' != settings.rael_price_cta_type ) {
							if( 'link' == settings.rael_price_cta_type ) {
								if ( '' != settings.rael_link.url ) {
									view.addRenderAttribute( 'rael_link', 'href', settings.rael_link.url );
									view.addRenderAttribute( 'rael_link', 'class', 'rael-price-box__cta-link' );
								}

								#>
								<a {{{ view.getRenderAttributeString( 'rael_link' ) }}}>
									<#
									if ( 'left' == settings.rael_cta_icon_position ) {
									#>
										<# render_cta_icon( 'before' ); #>
									<# } #>
									<#
									if ( '' != settings.rael_cta_text ) {
										view.addInlineEditingAttributes( 'rael_cta_text', 'basic' );
									#>
										<span {{{ view.getRenderAttributeString( 'rael_cta_text' ) }}}>
											{{{  settings.rael_cta_text  }}}
										</span>
									<# } #>
									<#
									if ( 'right' == settings.rael_cta_icon_position ) {
									#>
										<# render_cta_icon( 'after' ); #>
									<# } #>
								</a>
								<#
							} else if( 'button' == settings.rael_price_cta_type ) {
								view.addRenderAttribute( 'rael_cta_button_container', 'class', 'rael-button-container elementor-button-wrapper' );

								if ( '' != settings.rael_link.url ) {
									view.addRenderAttribute( 'rael_cta_button', 'href', settings.rael_link.url );
									view.addRenderAttribute( 'rael_cta_button', 'class', 'elementor-button-link' );
								}

								view.addRenderAttribute( 'rael_cta_button', 'class', 'elementor-button' );

								if ( '' != settings.rael_button_size ) {
									view.addRenderAttribute( 'rael_cta_button', 'class', 'elementor-size-' + settings.rael_button_size );
								}

								if ( settings.rael_button_hover_animation ) {
									view.addRenderAttribute( 'rael_cta_button', 'class', 'elementor-animation-' + settings.rael_button_hover_animation );
								}

								#>
								<div {{{ view.getRenderAttributeString( 'rael_cta_button_container' ) }}}>
									<a {{{ view.getRenderAttributeString( 'rael_cta_button' ) }}}>
										<#
										view.addInlineEditingAttributes( 'rael_cta_text', 'none' );
										view.addRenderAttribute( 'rael_cta_text', 'class', 'elementor-button-text' );
										#>
										<#
										if ( 'left' == settings.rael_cta_icon_position ) {
										#>
											<# render_cta_icon( 'before' ); #>
										<# } #>
										<#
											if ( '' != settings.rael_cta_text ) {
										#>
										<span {{{ view.getRenderAttributeString( 'rael_cta_text' ) }}}>
											{{{ settings.rael_cta_text }}}
										</span>
										<# } #>
										<#
										if ( 'right' == settings.rael_cta_icon_position ) {
										#>
											<# render_cta_icon( 'after' ); #>
										<# } #>
									</a>
								</div>
							<# } #>
						<# } #>
						<# if ( settings.rael_disclaimer_text ) {
							view.addInlineEditingAttributes( 'rael_disclaimer_text', 'basic' );
							view.addRenderAttribute( 'rael_disclaimer_text', 'class', 'rael-price-box__disclaimer' );
						#>
							<div {{{ view.getRenderAttributeString( 'rael_disclaimer_text' ) }}}>
								{{{ settings.rael_disclaimer_text }}}
							</div>
						<# } #>
					</div>
				<# }
			}
		}

		function render_separator() {
			if ( settings.rael_features_list ) {
				if ( 'yes' == settings.rael_toggle_price_box_separator && '2' == settings.rael_price_box_layout ) {
				#>
					<div class="rael-separator-container">
						<div class="rael-separator"></div>
					</div>
				<# }
			}
		}

		function render_ribbon() {
			var ribbon_style = '';
			if ( '' != settings.rael_ribbon_title ) {
				if ( 'none' != settings.rael_show_ribbon ) {

					if ( '1' == settings.rael_show_ribbon ) {
						ribbon_style = '1';
					} else if ( '2' == settings.rael_show_ribbon ) {
						ribbon_style = '2';
					} else if ( '3' == settings.rael_show_ribbon ) {
						ribbon_style = '3';
					}
					var ribbonClass = '';

					if ( settings.rael_ribbon_horizontal_position ) {
						ribbonClass = 'rael-ribbon-' + settings.rael_ribbon_horizontal_position;
					}

					view.addInlineEditingAttributes( 'rael_ribbon_title', 'none' );
					view.addRenderAttribute( 'rael_ribbon_title', 'class', 'rael-price-box-ribbon-content' );
					#>
					<div class="rael-price-box-ribbon-{{{ ribbon_style }}} {{{ ribbonClass }}}">
						<div {{{ view.getRenderAttributeString('rael_ribbon_title') }}}>
							{{{ settings.rael_ribbon_title }}}
						</div>
					</div>
				<#
				}
			}
		}

		if ( '1' == settings.rael_price_box_layout ) { #>
			<div class="rael-price-box-content rael-price-box-container rael-price-box-container-<?php echo esc_attr( $this->get_id() ); ?> rael-price-box--layout-{{{ settings.rael_price_box_layout }}}">
				<div class="rael-price-box">
					<# render_header(); #>
					<# render_price(); #>
					<# render_features(); #>
					<# render_cta(); #>
				</div>
				<# render_ribbon(); #>
			</div>
		<# } else if ( '2' == settings.rael_price_box_layout ) { #>
			<div class="rael-price-box-content rael-price-box-container rael-price-box-container-<?php echo esc_attr( $this->get_id() ); ?> rael-price-box--layout-{{{ settings.rael_price_box_layout }}}">
				<div class="rael-price-box">
					<# render_header(); #>
					<# render_price(); #>
					<# render_description(); #>
					<# render_cta(); #>
					<# render_separator(); #>
					<# render_features(); #>
				</div>
				<# render_ribbon(); #>
			</div>
		<# } else if ( '3' == settings.rael_price_box_layout ) { #>
			<div class="rael-price-box-content rael-price-box-container rael-price-box-container-<?php echo esc_attr( $this->get_id() ); ?> rael-price-box--layout-{{{ settings.rael_price_box_layout }}}">
				<div class="rael-price-box">
					<# render_header(); #>
					<# render_price(); #>
					<# render_features(); #>
					<# render_cta(); #>
				</div>
				<# render_ribbon(); #>
			</div>
		<# } else if( '4' == settings.rael_price_box_layout ) { #>
			<div class="rael-price-box-content rael-price-box-container rael-price-box-container-<?php echo esc_attr( $this->get_id() ); ?> rael-price-box--layout-{{{ settings.rael_price_box_layout }}}">
				<div class="rael-price-box">
					<# render_header(); #>
					<# render_features(); #>
					<# render_price(); #>
					<# render_cta(); #>
				</div>
				<# render_ribbon(); #>
			</div>
		<# }

		#>
		<?php
	}
	/**
	 * Get the name for a new icon based on the control.
	 *
	 * @param string $control The name of the control.
	 *
	 * @return string The icon name.
	 */
	public static function get_new_icon_name( $control ) {
		if ( class_exists( 'Elementor\Icons_Manager' ) ) {
			return 'rael_new_' . $control . '[value]';
		} else {
			return 'rael_' . $control;
		}
	}
		/**
		 * Check if Elementor is updated by verifying the existence of Icons_Manager class.
		 *
		 * @return bool True if Elementor is updated, false otherwise.
		 */
	public static function is_elementor_updated() {
		if ( class_exists( 'Elementor\Icons_Manager' ) ) {
			return true;
		} else {
			return false;
		}
	}
}
