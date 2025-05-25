<?php
/**
 * Pricing Table Widget
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
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Elementor 'Pricing Table' widget.
 *
 * Elementor widget that displays an Pricing Table.
 *
 * @since 1.0.0
 */
class Responsive_Addons_For_Elementor_Pricing_Table extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-pricing-table';
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
		return __( 'Pricing Table', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Pricing Table widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-price-table rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the pricing table widget belongs to.
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
	 * Register all the control settings for the pricing table
	 *
	 * @since 1.0.0
	 * @access public
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_header',
			array(
				'label' => __( 'Header', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'heading',
			array(
				'label'   => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Enter title', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'sub_heading',
			array(
				'label'   => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Enter description', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'heading_tag',
			array(
				'label'   => __( 'Title HTML Tag', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				),
				'default' => 'h3',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_pricing',
			array(
				'label' => __( 'Pricing', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'currency_symbol',
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
					'peseta'       => '&#8359 ' . _x( 'Peseta', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'peso'         => '&#8369; ' . _x( 'Peso', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'pound'        => '&#163; ' . _x( 'Pound Sterling', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'real'         => 'R$ ' . _x( 'Real', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'ruble'        => '&#8381; ' . _x( 'Ruble', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'rupee'        => '&#8360; ' . _x( 'Rupee', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'indian_rupee' => '&#8377; ' . _x( 'Rupee (Indian)', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'shekel'       => '&#8362; ' . _x( 'Shekel', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'yen'          => '&#165; ' . _x( 'Yen/Yuan', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'won'          => '&#8361; ' . _x( 'Won', 'Currency Symbol', 'responsive-addons-for-elementor' ),
					'custom'       => __( 'Custom', 'responsive-addons-for-elementor' ),
				),
				'default' => 'dollar',
			)
		);

		$this->add_control(
			'currency_symbol_custom',
			array(
				'label'     => __( 'Custom Symbol', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'currency_symbol' => 'custom',
				),
			)
		);

		$this->add_control(
			'price',
			array(
				'label'   => __( 'Price', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '39.99',
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'currency_format',
			array(
				'label'   => __( 'Currency Format', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''  => '1,234.56 (Default)',
					',' => '1.234,56',
				),
			)
		);

		$this->add_control(
			'sale',
			array(
				'label'     => __( 'Sale', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'On', 'responsive-addons-for-elementor' ),
				'label_off' => __( 'Off', 'responsive-addons-for-elementor' ),
				'default'   => '',
			)
		);

		$this->add_control(
			'original_price',
			array(
				'label'     => __( 'Original Price', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '59',
				'condition' => array(
					'sale' => 'yes',
				),
				'dynamic'   => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'period',
			array(
				'label'   => __( 'Period', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Monthly', 'responsive-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_features',
			array(
				'label' => __( 'Features', 'responsive-addons-for-elementor' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_text',
			array(
				'label'   => __( 'Text', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'List Item', 'responsive-addons-for-elementor' ),
			)
		);

		$default_icon = array(
			'value'   => 'far fa-check-circle',
			'library' => 'fa-regular',
		);

		$repeater->add_control(
			'selected_item_icon',
			array(
				'label'            => __( 'Icon', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'item_icon',
				'default'          => $default_icon,
			)
		);

		$repeater->add_control(
			'item_icon_color',
			array(
				'label'     => __( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} i'   => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'features_list',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'item_text'          => __( 'List Item #1', 'responsive-addons-for-elementor' ),
						'selected_item_icon' => $default_icon,
					),
					array(
						'item_text'          => __( 'List Item #2', 'responsive-addons-for-elementor' ),
						'selected_item_icon' => $default_icon,
					),
					array(
						'item_text'          => __( 'List Item #3', 'responsive-addons-for-elementor' ),
						'selected_item_icon' => $default_icon,
					),
				),
				'title_field' => '{{{ item_text }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_footer',
			array(
				'label' => __( 'Footer', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => __( 'Button Text', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Click Here', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'responsive-addons-for-elementor' ),
				'default'     => array(
					'url' => '#',
				),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'footer_additional_info',
			array(
				'label'   => __( 'Additional Info', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'This is text element', 'responsive-addons-for-elementor' ),
				'rows'    => 3,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_ribbon',
			array(
				'label' => __( 'Ribbon', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'show_ribbon',
			array(
				'label'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'ribbon_title',
			array(
				'label'     => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Popular', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'show_ribbon' => 'yes',
				),
			)
		);

		$this->add_control(
			'ribbon_horizontal_position',
			array(
				'label'     => __( 'Position', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'  => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'condition' => array(
					'show_ribbon' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_header_style',
			array(
				'label'      => __( 'Header', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'header_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__header' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'header_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-table__header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_heading_style',
			array(
				'label'     => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__heading' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .rael-price-table__heading',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			)
		);

		$this->add_control(
			'heading_sub_heading_style',
			array(
				'label'     => __( 'Sub Title', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sub_heading_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__subheading' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sub_heading_typography',
				'selector' => '{{WRAPPER}} .rael-price-table__subheading',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_pricing_element_style',
			array(
				'label'      => __( 'Pricing', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'pricing_element_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__price' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'pricing_element_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-table__price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__currency, {{WRAPPER}} .rael-price-table__integer-part, {{WRAPPER}} .rael-price-table__fractional-part' => 'color: {{VALUE}}',
				),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .rael-price-table__price span:not(.rael-price-table__period), {{WRAPPER}} .rael-price-table__price > .rael-price-table__after-price > .rael-price-table__fractional-part',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			)
		);

		$this->add_control(
			'heading_currency_style',
			array(
				'label'     => __( 'Currency Symbol', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'currency_symbol!' => '',
				),
			)
		);

		$this->add_control(
			'currency_size',
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
					'{{WRAPPER}} .rael-price-table__currency' => 'font-size: calc({{SIZE}}em/100)',
				),
				'condition' => array(
					'currency_symbol!' => '',
				),
			)
		);

		$this->add_control(
			'currency_position',
			array(
				'label'   => __( 'Position', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'before',
				'options' => array(
					'before' => array(
						'title' => __( 'Before', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'after'  => array(
						'title' => __( 'After', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
			)
		);

		$this->add_control(
			'currency_vertical_position',
			array(
				'label'                => __( 'Vertical Position', 'responsive-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
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
				'selectors'            => array(
					'{{WRAPPER}} .rael-price-table__currency' => 'align-self: {{VALUE}}',
				),
				'condition'            => array(
					'currency_symbol!' => '',
				),
			)
		);

		$this->add_control(
			'fractional_part_style',
			array(
				'label'     => __( 'Fractional Part', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'fractional-part_size',
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
					'{{WRAPPER}} .rael-price-table__fractional-part' => 'font-size: calc({{SIZE}}em/100)',
				),
			)
		);

		$this->add_control(
			'fractional_part_vertical_position',
			array(
				'label'                => __( 'Vertical Position', 'responsive-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
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
				'selectors'            => array(
					'{{WRAPPER}} .rael-price-table__after-price' => 'justify-content: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'heading_original_price_style',
			array(
				'label'     => __( 'Original Price', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'sale'            => 'yes',
					'original_price!' => '',
				),
			)
		);

		$this->add_control(
			'original_price_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__original-price' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'sale'            => 'yes',
					'original_price!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'original_price_typography',
				'selector'  => '{{WRAPPER}} .rael-price-table__original-price',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'condition' => array(
					'sale'            => 'yes',
					'original_price!' => '',
				),
			)
		);

		$this->add_control(
			'original_price_vertical_position',
			array(
				'label'                => __( 'Vertical Position', 'responsive-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
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
				'default'              => 'bottom',
				'selectors'            => array(
					'{{WRAPPER}} .rael-price-table__original-price' => 'align-self: {{VALUE}}',
				),
				'condition'            => array(
					'sale'            => 'yes',
					'original_price!' => '',
				),
			)
		);

		$this->add_control(
			'heading_period_style',
			array(
				'label'     => __( 'Period', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'period!' => '',
				),
			)
		);

		$this->add_control(
			'period_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__period' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'period!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'period_typography',
				'selector'  => '{{WRAPPER}} .rael-price-table__period',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'condition' => array(
					'period!' => '',
				),
			)
		);

		$this->add_control(
			'period_position',
			array(
				'label'       => __( 'Position', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => array(
					'below'  => __( 'Below', 'responsive-addons-for-elementor' ),
					'beside' => __( 'Beside', 'responsive-addons-for-elementor' ),
				),
				'default'     => 'below',
				'condition'   => array(
					'period!' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_features_list_style',
			array(
				'label'      => __( 'Features', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'features_list_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__features-list' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'features_list_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-table__features-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'features_list_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'   => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__features-list' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'features_list_typography',
				'selector' => '{{WRAPPER}} .rael-price-table__features-list li',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			)
		);

		$this->add_control(
			'features_list_alignment',
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
					'{{WRAPPER}} .rael-price-table__features-list' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'item_width',
			array(
				'label'     => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'%' => array(
						'min' => 25,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__feature-inner' => 'margin-left: calc((100% - {{SIZE}}%)/2); margin-right: calc((100% - {{SIZE}}%)/2)',
				),
			)
		);

		$this->add_control(
			'list_divider',
			array(
				'label'     => __( 'Divider', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'divider_style',
			array(
				'label'     => __( 'Style', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'solid'  => __( 'Solid', 'responsive-addons-for-elementor' ),
					'double' => __( 'Double', 'responsive-addons-for-elementor' ),
					'dotted' => __( 'Dotted', 'responsive-addons-for-elementor' ),
					'dashed' => __( 'Dashed', 'responsive-addons-for-elementor' ),
				),
				'default'   => 'solid',
				'condition' => array(
					'list_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__features-list li:before' => 'border-top-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'divider_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ddd',
				'global'   => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'condition' => array(
					'list_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__features-list li:before' => 'border-top-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'divider_weight',
			array(
				'label'     => __( 'Weight', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 2,
					'unit' => 'px',
				),
				'range'     => array(
					'px' => array(
						'min' => 1,
						'max' => 10,
					),
				),
				'condition' => array(
					'list_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__features-list li:before' => 'border-top-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'divider_width',
			array(
				'label'     => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'condition' => array(
					'list_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__features-list li:before' => 'margin-left: calc((100% - {{SIZE}}%)/2); margin-right: calc((100% - {{SIZE}}%)/2)',
				),
			)
		);

		$this->add_control(
			'divider_gap',
			array(
				'label'     => __( 'Gap', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 15,
					'unit' => 'px',
				),
				'range'     => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
				),
				'condition' => array(
					'list_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__features-list li:before' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_footer_style',
			array(
				'label'      => __( 'Footer', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'footer_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__footer' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'footer_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-table__footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_footer_button',
			array(
				'label'     => __( 'Button', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'button_text!' => '',
				),
			)
		);

		$this->add_control(
			'button_size',
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
					'button_text!' => '',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label'     => __( 'Normal', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'button_text!' => '',
				),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .rael-price-table__button',
			)
		);

		$this->add_control(
			'button_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'   => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'button_border',
				'selector'  => '{{WRAPPER}} .rael-price-table__button',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-table__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_text_padding',
			array(
				'label'      => __( 'Text Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-table__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label'     => __( 'Hover', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'button_text!' => '',
				),
			)
		);

		$this->add_control(
			'button_hover_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background_hover_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_animation',
			array(
				'label' => __( 'Animation', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'heading_additional_info',
			array(
				'label'     => __( 'Additional Info', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'footer_additional_info!' => '',
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
					'{{WRAPPER}} .rael-price-table__additional_info' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'footer_additional_info!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'additional_info_typography',
				'selector'  => '{{WRAPPER}} .rael-price-table__additional_info',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'condition' => array(
					'footer_additional_info!' => '',
				),
			)
		);

		$this->add_control(
			'additional_info_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'    => 15,
					'right'  => 30,
					'bottom' => 0,
					'left'   => 30,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-price-table__additional_info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				),
				'condition'  => array(
					'footer_additional_info!' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_ribbon_style',
			array(
				'label'      => __( 'Ribbon', 'responsive-addons-for-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => array(
					'show_ribbon' => 'yes',
				),
			)
		);

		$this->add_control(
			'ribbon_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'   => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__ribbon-inner' => 'background-color: {{VALUE}}',
				),
			)
		);

		$ribbon_distance_transform = is_rtl() ? 'translateY(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)' : 'translateY(-50%) translateX(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)';

		$this->add_responsive_control(
			'ribbon_distance',
			array(
				'label'     => __( 'Distance', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__ribbon-inner' => 'margin-top: {{SIZE}}{{UNIT}}; transform: ' . $ribbon_distance_transform,
				),
			)
		);

		$this->add_control(
			'ribbon_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .rael-price-table__ribbon-inner' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'ribbon_typography',
				'selector' => '{{WRAPPER}} .rael-price-table__ribbon-inner',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .rael-price-table__ribbon-inner',
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Render the currency symbol based on the provided parameters.
	 *
	 * This function outputs an HTML span element containing the specified currency symbol
	 * if the symbol is not empty and the location matches the determined currency position setting.
	 *
	 * @param string $symbol    The currency symbol to be rendered.
	 * @param string $location  The location where the currency symbol should be placed ('before' or 'after').
	 *
	 * @return void
	 */
	private function render_currency_symbol( $symbol, $location ) {
		$currency_position = $this->get_settings( 'currency_position' );
		$location_setting  = ! empty( $currency_position ) ? $currency_position : 'before';
		if ( ! empty( $symbol ) && $location === $location_setting ) {
			echo '<span class="rael-price-table__currency elementor-currency--' . esc_attr( $location ) . '">' . esc_html( $symbol ) . '</span>';
		}
	}
	/**
	 * Get the HTML entity symbol for a given currency.
	 *
	 * @param string $symbol_name The name of the currency symbol.
	 *
	 * @return string The HTML entity symbol for the specified currency, or an empty string if not found.
	 */
	private function get_currency_symbol( $symbol_name ) {
		$symbols = array(
			'dollar'       => '&#36;',
			'euro'         => '&#128;',
			'franc'        => '&#8355;',
			'pound'        => '&#163;',
			'ruble'        => '&#8381;',
			'shekel'       => '&#8362;',
			'baht'         => '&#3647;',
			'yen'          => '&#165;',
			'won'          => '&#8361;',
			'guilder'      => '&fnof;',
			'peso'         => '&#8369;',
			'peseta'       => '&#8359;',
			'lira'         => '&#8356;',
			'rupee'        => '&#8360;',
			'indian_rupee' => '&#8377;',
			'real'         => 'R$',
			'krona'        => 'kr',
		);

		return isset( $symbols[ $symbol_name ] ) ? $symbols[ $symbol_name ] : '';
	}

	/**
	 * Render Images on the frontend for the pricing table
	 */
	public function render() {
		$settings = $this->get_settings_for_display();
		$symbol   = '';

		if ( ! empty( $settings['currency_symbol'] ) ) {
			if ( 'custom' !== $settings['currency_symbol'] ) {
				$symbol = $this->get_currency_symbol( $settings['currency_symbol'] );
			} else {
				$symbol = $settings['currency_symbol_custom'];
			}
		}
		$currency_format = empty( $settings['currency_format'] ) ? '.' : $settings['currency_format'];
		$price           = explode( $currency_format, $settings['price'] );
		$intpart         = $price[0];
		$fraction        = '';
		if ( 2 == count( $price ) ) {
			$fraction = $price[1];
		}

		$this->add_render_attribute(
			'button_text',
			'class',
			array(
				'rael-price-table__button',
				'elementor-button',
				'elementor-size-' . $settings['button_size'],
			)
		);

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'button_text', $settings['link'] );
		}

		if ( ! empty( $settings['button_hover_animation'] ) ) {
			$this->add_render_attribute( 'button_text', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}

		$this->add_render_attribute( 'heading', 'class', 'rael-price-table__heading' );
		$this->add_render_attribute( 'sub_heading', 'class', 'rael-price-table__subheading' );
		$this->add_render_attribute( 'period', 'class', array( 'rael-price-table__period', 'elementor-typo-excluded' ) );
		$this->add_render_attribute( 'footer_additional_info', 'class', 'rael-price-table__additional_info' );
		$this->add_render_attribute( 'ribbon_title', 'class', 'rael-price-table__ribbon-inner' );

		$this->add_inline_editing_attributes( 'heading', 'none' );
		$this->add_inline_editing_attributes( 'sub_heading', 'none' );
		$this->add_inline_editing_attributes( 'period', 'none' );
		$this->add_inline_editing_attributes( 'footer_additional_info' );
		$this->add_inline_editing_attributes( 'button_text' );
		$this->add_inline_editing_attributes( 'ribbon_title' );

		$period_position = $settings['period_position'];
		$period_element  = '<span ' . $this->get_render_attribute_string( 'period' ) . '>' . $settings['period'] . '</span>';
		$heading_tag     = $settings['heading_tag'];

		$migration_allowed = Icons_Manager::is_migration_allowed();
		?>

		<div class="rael-price-table">
			<?php if ( $settings['heading'] || $settings['sub_heading'] ) : ?>
				<div class="rael-price-table__header">
					<?php if ( ! empty( $settings['heading'] ) ) : ?>
						<<?php echo esc_html( Helper::validate_html_tags( $heading_tag ) ) . ' ' . wp_kses_post( $this->get_render_attribute_string( 'heading' ) ); ?>>
						<?php echo esc_html( $settings['heading'] ) . '</' . esc_html( Helper::validate_html_tags( $heading_tag ) ); ?>>
					<?php endif; ?>

					<?php if ( ! empty( $settings['sub_heading'] ) ) : ?>
						<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'sub_heading' ) ); ?>><?php echo esc_html( $settings['sub_heading'] ); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="rael-price-table__price">
				<?php if ( 'yes' === $settings['sale'] && ! empty( $settings['original_price'] ) ) : ?>
					<div class="rael-price-table__original-price elementor-typo-excluded"><?php echo esc_html( $symbol . $settings['original_price'] ); ?></div>
				<?php endif; ?>
				<?php $this->render_currency_symbol( $symbol, 'before' ); ?>
				<?php if ( ! empty( $intpart ) || 0 <= $intpart ) : ?>
					<span class="rael-price-table__integer-part"><?php echo esc_html( $intpart ); ?></span>
				<?php endif; ?>

				<?php if ( '' !== $fraction || ( ! empty( $settings['period'] ) && 'beside' === $period_position ) ) : ?>
					<div class="rael-price-table__after-price">
						<span class="rael-price-table__fractional-part"><?php echo esc_html( $fraction ); ?></span>

						<?php if ( ! empty( $settings['period'] ) && 'beside' === $period_position ) : ?>
							<?php echo wp_kses_post( $period_element ); ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php $this->render_currency_symbol( $symbol, 'after' ); ?>

				<?php if ( ! empty( $settings['period'] ) && 'below' === $period_position ) : ?>
					<?php echo wp_kses_post( $period_element ); ?>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $settings['features_list'] ) ) : ?>
				<ul class="rael-price-table__features-list">
					<?php
					foreach ( $settings['features_list'] as $index => $item ) :
						$repeater_setting_key = $this->get_repeater_setting_key( 'item_text', 'features_list', $index );
						$this->add_inline_editing_attributes( $repeater_setting_key );

						$migrated = isset( $item['__fa4_migrated']['selected_item_icon'] );
						// add old default.
						if ( ! isset( $item['item_icon'] ) && ! $migration_allowed ) {
							$item['item_icon'] = 'fa fa-check-circle';
						}
						$is_new = ! isset( $item['item_icon'] ) && $migration_allowed;
						?>
						<li class="elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
							<div class="rael-price-table__feature-inner">
								<?php
								if ( ! empty( $item['item_icon'] ) || ! empty( $item['selected_item_icon'] ) ) :
									if ( $is_new || $migrated ) :
										Icons_Manager::render_icon( $item['selected_item_icon'], array( 'aria-hidden' => 'true' ) );
									else :
										?>
										<i class="<?php echo esc_attr( $item['item_icon'] ); ?>" aria-hidden="true"></i>
										<?php
									endif;
								endif;
								?>
								<?php if ( ! empty( $item['item_text'] ) ) : ?>
									<span <?php $this->print_render_attribute_string( $repeater_setting_key ); ?>>
										<?php $this->print_unescaped_setting( 'item_text', 'features_list', $index ); ?>
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
			<?php endif; ?>

			<?php if ( ! empty( $settings['button_text'] ) || ! empty( $settings['footer_additional_info'] ) ) : ?>
				<div class="rael-price-table__footer">
					<?php if ( ! empty( $settings['button_text'] ) ) : ?>
						<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'button_text' ) ); ?>><?php echo wp_kses_post( $settings['button_text'] ); ?></a>
					<?php endif; ?>

					<?php if ( ! empty( $settings['footer_additional_info'] ) ) : ?>
						<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'footer_additional_info' ) ); ?>><?php echo wp_kses_post( $settings['footer_additional_info'] ); ?></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php
		if ( 'yes' === $settings['show_ribbon'] && ! empty( $settings['ribbon_title'] ) ) :
			$this->add_render_attribute( 'ribbon-wrapper', 'class', 'rael-price-table__ribbon' );

			if ( ! empty( $settings['ribbon_horizontal_position'] ) ) :
				$this->add_render_attribute( 'ribbon-wrapper', 'class', 'elementor-ribbon-' . $settings['ribbon_horizontal_position'] );
			endif;

			?>
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'ribbon-wrapper' ) ); ?>>
				<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'ribbon_title' ) ); ?>><?php echo wp_kses_post( $settings['ribbon_title'] ); ?></div>
			</div>
			<?php
		endif;
	}
	/**
	 * Render function for the Rea Price Table widget.
	 */
	protected function content_template() {
		?>
		<#
		var symbols = {
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

		var symbol = '',
		iconsHTML = {};

		if ( settings.currency_symbol ) {
		if ( 'custom' != settings.currency_symbol ) {
		symbol = symbols[ settings.currency_symbol ] || '';
		} else {
		symbol = settings.currency_symbol_custom;
		}
		}

		var buttonClasses = 'rael-price-table__button elementor-button elementor-size-' + settings.button_size;

		if ( settings.button_hover_animation ) {
		buttonClasses += ' elementor-animation-' + settings.button_hover_animation;
		}

		view.addRenderAttribute( 'heading', 'class', 'rael-price-table__heading' );
		view.addRenderAttribute( 'sub_heading', 'class', 'rael-price-table__subheading' );
		view.addRenderAttribute( 'period', 'class', ['rael-price-table__period', 'elementor-typo-excluded'] );
		view.addRenderAttribute( 'footer_additional_info', 'class', 'rael-price-table__additional_info'  );
		view.addRenderAttribute( 'button_text', 'class', buttonClasses  );
		view.addRenderAttribute( 'ribbon_title', 'class', 'rael-price-table__ribbon-inner'  );

		view.addInlineEditingAttributes( 'heading', 'none' );
		view.addInlineEditingAttributes( 'sub_heading', 'none' );
		view.addInlineEditingAttributes( 'period', 'none' );
		view.addInlineEditingAttributes( 'footer_additional_info' );
		view.addInlineEditingAttributes( 'button_text' );
		view.addInlineEditingAttributes( 'ribbon_title' );

		var currencyFormat = settings.currency_format || '.',
		price = settings.price.split( currencyFormat ),
		intpart = price[0],
		fraction = price[1],

		periodElement = '<span ' + view.getRenderAttributeString( "period" ) + '>' + settings.period + '</span>';

		#>
		<div class="rael-price-table">
			<# if ( settings.heading || settings.sub_heading ) { #>
			<div class="rael-price-table__header">
				<# if ( settings.heading ) { #>
				<{{ elementor.helpers.validateHTMLTag(settings.heading_tag) }} {{{ view.getRenderAttributeString( 'heading' ) }}}>{{{ settings.heading }}}</{{ elementor.helpers.validateHTMLTag(settings.heading_tag) }}>
			<# } #>
			<# if ( settings.sub_heading ) { #>
			<span {{{ view.getRenderAttributeString( 'sub_heading' ) }}}>{{{ settings.sub_heading }}}</span>
			<# } #>
		</div>
		<# } #>

		<div class="rael-price-table__price">
			<# if ( settings.sale && settings.original_price ) { #>
			<div class="rael-price-table__original-price elementor-typo-excluded">{{{ symbol + settings.original_price }}}</div>
			<# } #>

			<# if ( ! _.isEmpty( symbol ) && ( 'before' == settings.currency_position || _.isEmpty( settings.currency_position ) ) ) { #>
			<span class="rael-price-table__currency elementor-currency--before">{{{ symbol }}}</span>
			<# } #>
			<# if ( intpart ) { #>
			<span class="rael-price-table__integer-part">{{{ intpart }}}</span>
			<# } #>
			<div class="rael-price-table__after-price">
				<# if ( fraction ) { #>
				<span class="rael-price-table__fractional-part">{{{ fraction }}}</span>
				<# } #>
				<# if ( settings.period && 'beside' == settings.period_position ) { #>
				{{{ periodElement }}}
				<# } #>
			</div>

			<# if ( ! _.isEmpty( symbol ) && 'after' == settings.currency_position ) { #>
			<span class="rael-price-table__currency elementor-currency--after">{{{ symbol }}}</span>
			<# } #>

			<# if ( settings.period && 'below' == settings.period_position ) { #>
			{{{ periodElement }}}
			<# } #>
		</div>

		<# if ( settings.features_list ) { #>
		<ul class="rael-price-table__features-list">
			<# _.each( settings.features_list, function( item, index ) {

			var featureKey = view.getRepeaterSettingKey( 'item_text', 'features_list', index ),
			migrated = elementor.helpers.isIconMigrated( item, 'selected_item_icon' );

			view.addInlineEditingAttributes( featureKey ); #>

			<li class="elementor-repeater-item-{{ item._id }}">
				<div class="rael-price-table__feature-inner">
					<# if ( item.item_icon  || item.selected_item_icon ) {
					iconsHTML[ index ] = elementor.helpers.renderIcon( view, item.selected_item_icon, { 'aria-hidden': 'true' }, 'i', 'object' );
					if ( ( ! item.item_icon || migrated ) && iconsHTML[ index ] && iconsHTML[ index ].rendered ) { #>
					{{{ iconsHTML[ index ].value }}}
					<# } else { #>
					<i class="{{ item.item_icon }}" aria-hidden="true"></i>
					<# }
					} #>
					<# if ( ! _.isEmpty( item.item_text.trim() ) ) { #>
					<span {{{ view.getRenderAttributeString( featureKey ) }}}>{{{ item.item_text }}}</span>
					<# } else { #>
					&nbsp;
					<# } #>
				</div>
			</li>
			<# } ); #>
		</ul>
		<# } #>

		<# if ( settings.button_text || settings.footer_additional_info ) { #>
		<div class="rael-price-table__footer">
			<# if ( settings.button_text ) { #>
			<a href="#" {{{ view.getRenderAttributeString( 'button_text' ) }}}>{{{ settings.button_text }}}</a>
			<# } #>
			<# if ( settings.footer_additional_info ) { #>
			<p {{{ view.getRenderAttributeString( 'footer_additional_info' ) }}}>{{{ settings.footer_additional_info }}}</p>
			<# } #>
		</div>
		<# } #>
		</div>

		<# if ( 'yes' == settings.show_ribbon && settings.ribbon_title ) {
		var ribbonClasses = 'rael-price-table__ribbon';
		if ( settings.ribbon_horizontal_position ) {
		ribbonClasses += ' elementor-ribbon-' + settings.ribbon_horizontal_position;
		} #>
		<div class="{{ ribbonClasses }}">
			<div {{{ view.getRenderAttributeString( 'ribbon_title' ) }}}>{{{ settings.ribbon_title }}}</div>
		</div>
		<# } #>
		<?php
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/pricing-table';
	}
}
