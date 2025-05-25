<?php
/**
 * RAEL Theme Builder's Post Info Widget.
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Repeater;

/**
 * RAEL Theme Post Info widget class
 *
 * @since 1.3.2
 */
class Responsive_Addons_For_Elementor_Theme_Post_Info extends Widget_Base {

	/**
	 * Get the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-theme-post-info';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Post Info', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-info rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'rael', 'theme builder', 'post info', 'info', 'single', 'post' );
	}

	/**
	 * Get custom help url.
	 *
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/post-info';
	}

	/**
	 * Register 'Post Excerpt' widget controls.
	 *
	 * @access protected
	 */
	protected function register_controls() {
		// Content Tab.
		$this->register_content_tab_meta_data_controls();

		// Style Tab.
		$this->register_style_tab_list_controls();
		$this->register_style_tab_icon_controls();
		$this->register_style_tab_text_controls();
	}

	/**
	 * Register content tab meta data controls.
	 *
	 * This method sets up controls for meta data in the content tab.
	 */
	protected function register_content_tab_meta_data_controls() {
		$this->start_controls_section(
			'rael_content_tab_meta_data_section',
			array(
				'label' => __( 'Meta Data', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_pi_view',
			array(
				'label'       => __( 'Layout', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'inline',
				'options'     => array(
					'traditional' => array(
						'title' => __( 'Default', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-editor-list-ul',
					),
					'inline'      => array(
						'title' => __( 'Inline', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-ellipsis-h',
					),
				),
				'render_type' => 'template',
				'classes'     => 'elementor-control-start-end',
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'rael_meta_data_type',
			array(
				'label'   => __( 'Type', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'author'   => __( 'Author', 'responsive-addons-for-elementor' ),
					'date'     => __( 'Date', 'responsive-addons-for-elementor' ),
					'time'     => __( 'Time', 'responsive-addons-for-elementor' ),
					'comments' => __( 'Comments', 'responsive-addons-for-elementor' ),
					'terms'    => __( 'Terms', 'responsive-addons-for-elementor' ),
					'custom'   => __( 'Custom', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$repeater->add_control(
			'rael_meta_data_date_format',
			array(
				'label'     => __( 'Date Format', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => array(
					'default' => __( 'Default', 'responsive-addons-for-elementor' ),
					'0'       => _x( 'August 14, 2021 (F j, Y)', 'Date Format', 'responsive-addons-for-elementor' ),
					'1'       => '2021-08-14 (Y-m-d)',
					'2'       => '08/14/2021 (m/d/Y)',
					'3'       => '14/08/2021 (d/m/Y)',
					'custom'  => __( 'Custom', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_meta_data_type' => 'date',
				),
			)
		);

		$repeater->add_control(
			'rael_meta_data_custom_date_format',
			array(
				'label'       => __( 'Custom Date Format', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'F j, Y',
				'description' => sprintf(
					/* translators: %s: Allowed data letters (see: http://php.net/manual/en/function.date.php). */
					__( 'Use the letters: %s', 'responsive-addons-for-elementor' ),
					'l D d j S F m M n Y y'
				),
				'condition'   => array(
					'rael_meta_data_type'        => 'date',
					'rael_meta_data_date_format' => 'custom',
				),
			)
		);

		$repeater->add_control(
			'rael_meta_data_time_format',
			array(
				'label'     => __( 'Time Format', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => array(
					'default' => __( 'Default', 'responsive-addons-for-elementor' ),
					'0'       => '12:45 pm (g:i a)',
					'1'       => '12:45 PM (g:i A)',
					'2'       => '18:30 (H:i)',
					'custom'  => __( 'Custom', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_meta_data_type' => 'time',
				),
			)
		);

		$repeater->add_control(
			'rael_meta_data_custom_time_format',
			array(
				'label'       => __( 'Custom Time Format', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'g:i a',
				'placeholder' => 'g:i a',
				'description' => sprintf(
					/* translators: %s: Allowed time letters (see: http://php.net/manual/en/function.time.php). */
					__( 'Use the letters: %s', 'responsive-addons-for-elementor' ),
					'g G H i a A'
				),
				'condition'   => array(
					'rael_meta_data_type'        => 'time',
					'rael_meta_data_time_format' => 'custom',
				),
			)
		);

		$repeater->add_control(
			'rael_meta_data_taxonomy',
			array(
				'label'       => __( 'Taxonomy', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'default'     => array(),
				'options'     => $this->get_taxonomies(),
				'condition'   => array(
					'rael_meta_data_type' => 'terms',
				),
			)
		);

		$repeater->add_control(
			'rael_meta_data_text_prefix',
			array(
				'label'     => __( 'Before', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array(
					'rael_meta_data_type!' => 'custom',
				),
			)
		);

		$repeater->add_control(
			'rael_meta_data_show_avatar',
			array(
				'label'        => __( 'Show Avatar', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition'    => array(
					'rael_meta_data_type' => 'author',
				),
			)
		);

		$repeater->add_responsive_control(
			'rael_meta_data_avatar_size',
			array(
				'label'     => __( 'Avatar Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .rael-post-info__icon-list-icon' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_meta_data_show_avatar' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'rael_meta_data_comments_custom_strings',
			array(
				'label'     => __( 'Custom Format', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => false,
				'condition' => array(
					'rael_meta_data_type' => 'comments',
				),
			)
		);

		$repeater->add_control(
			'rael_meta_data_string_no_comments',
			array(
				'label'       => __( 'No Comments', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'No Comments', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_meta_data_comments_custom_strings' => 'yes',
					'rael_meta_data_type' => 'comments',
				),
			)
		);

		$repeater->add_control(
			'rael_meta_data_string_one_comment',
			array(
				'label'       => __( 'One Comment', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'One Comment', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_meta_data_comments_custom_strings' => 'yes',
					'rael_meta_data_type' => 'comments',
				),
			)
		);

		$repeater->add_control(
			'rael_meta_data_string_commens',
			array(
				'label'       => __( 'Comments', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				// translators: Placeholder %s represents the number of comments.
				'placeholder' => __( '%s Comments', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_meta_data_comments_custom_strings' => 'yes',
					'rael_meta_data_type' => 'comments',
				),
			)
		);

		$repeater->add_control(
			'rael_meta_data_custom_text',
			array(
				'label'       => __( 'Custom', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'rael_meta_data_type' => 'custom',
				),
			)
		);

		$repeater->add_control(
			'rael_meta_data_link',
			array(
				'label'     => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					'rael_meta_data_type!' => 'time',
				),
			)
		);

		$repeater->add_control(
			'rael_meta_data_custom_url',
			array(
				'label'     => __( 'Custom URL', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::URL,
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'rael_meta_data_type' => 'custom',
				),
			)
		);

		$repeater->add_control(
			'rael_meta_data_show_icon',
			array(
				'label'     => __( 'Icon', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'none'    => __( 'None', 'responsive-addons-for-elementor' ),
					'default' => __( 'Default', 'responsive-addons-for-elementor' ),
					'custom'  => __( 'Custom', 'responsive-addons-for-elementor' ),
				),
				'default'   => 'default',
				'condition' => array(
					'rael_meta_data_show_avatar!' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'rael_meta_data_selected_icon',
			array(
				'label'            => __( 'Choose Icon', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'rael_meta_data_icon',
				'condition'        => array(
					'rael_meta_data_show_icon'    => 'custom',
					'rael_meta_data_show_avatar!' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_pi_icon_list',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'rael_meta_data_type'          => 'author',
						'rael_meta_data_selected_icon' => array(
							'value'   => 'far fa-user-circle',
							'library' => 'fa-regular',
						),
					),
					array(
						'rael_meta_data_type'          => 'date',
						'rael_meta_data_selected_icon' => array(
							'value'   => 'fas fa-calendar',
							'library' => 'fa-solid',
						),
					),
					array(
						'rael_meta_data_type'          => 'time',
						'rael_meta_data_selected_icon' => array(
							'value'   => 'far fa-clock',
							'library' => 'fa-regular',
						),
					),
					array(
						'rael_meta_data_type'          => 'comments',
						'rael_meta_data_selected_icon' => array(
							'value'   => 'far fa-comment-dots',
							'library' => 'fa-regular',
						),
					),
				),
				'title_field' => '{{{ elementor.helpers.renderIcon( this, rael_meta_data_selected_icon, {}, "i", "panel" ) || \'<i class="{{ rael_meta_data_icon }}" aria-hidden="true"></i>\' }}} <span style="text-transform: capitalize;">{{{ rael_meta_data_type }}}</span>',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register style tab list controls.
	 *
	 * This method sets up controls for styling the list in the style tab.
	 */
	protected function register_style_tab_list_controls() {
		$this->start_controls_section(
			'rael_style_tab_list_section',
			array(
				'label' => __( 'List', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_pi_space_between',
			array(
				'label'     => __( 'Space between', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-post-info__icon-list-items:not(.rael-post-info--inline-items) .rael-post-info__icon-list-item:not(:last-child)' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .rael-post-info__icon-list-items:not(.rael-post-info--inline-items) .rael-post-info__icon-list-item:not(:first-child)' => 'margin-top: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .rael-post-info__icon-list-items.rael-post-info--inline-items .rael-post-info__icon-list-item' => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .rael-post-info__icon-list-items.rael-post-info--inline-items' => 'margin-right: calc(-{{SIZE}}{{UNIT}}/2); margin-left: calc(-{{SIZE}}{{UNIT}}/2);',
					'body.rtl{{WRAPPER}} .rael-post-info__icon-list-items.rael-post-info--inline-items .rael-post-info__icon-list-icon' => 'left: calc(-{{SIZE}}{{UNIT}}/2);',
					'body:not(.rtl) {{WRAPPER}} .rael-post-info__icon-list-items.rael-post-info--inline-items .rael-post-info__icon-list-item:after' => 'right: calc(-{{SIZE}}{{UNIT}}/2);',
				),
			)
		);

		$this->add_responsive_control(
			'rael_pi_icon_align',
			array(
				'label'        => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'   => array(
						'title' => __( 'Start', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'End', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'prefix_class' => 'rael-post-info--align%s-',
			)
		);

		$this->add_control(
			'rael_pi_divider',
			array(
				'label'     => __( 'Divider', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'responsive-addons-for-elementor' ),
				'label_on'  => __( 'On', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .rael-post-info__icon-list-item:not(:last-child):after' => 'content: "";',
				),
			)
		);

		$this->add_control(
			'rael_pi_divider_style',
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
					'rael_pi_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-post-info__icon-list-items:not(.rael-post-info--inline-items) .rael-post-info__icon-list-item:not(:last-child):after' => 'border-top-style: {{VALUE}};',
					'{{WRAPPER}} .rael-post-info__icon-list-items.rael-post-info--inline-items .rael-post-info__icon-list-item:not(:last-child):after' => 'border-left-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_pi_divider_weight',
			array(
				'label'     => __( 'Weight', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 1,
				),
				'range'     => array(
					'px' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'condition' => array(
					'rael_pi_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-post-info__icon-list-items:not(.rael-post-info--inline-items) .rael-post-info__icon-list-item:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-post-info__icon-list-items.rael-post-info--inline-items .rael-post-info__icon-list-item:not(:last-child):after' => 'border-left-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_pi_divider_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'units' => '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'condition'  => array(
					'rael_pi_divider' => 'yes',
					'rael_pi_view!'   => 'inline',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-post-info__icon-list-item:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_pi_divider_height',
			array(
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'units' => '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'condition'  => array(
					'rael_pi_divider' => 'yes',
					'rael_pi_view'    => 'inline',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-post-info__icon-list-item:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_pi_divider_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ddd',
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'condition' => array(
					'rael_pi_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-post-info__icon-list-item:not(:last-child):after' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register style tab icon controls.
	 *
	 * This method sets up controls for styling icons in the style tab.
	 */
	protected function register_style_tab_icon_controls() {
		$this->start_controls_section(
			'rael_style_tab_icon_section',
			array(
				'label' => __( 'Icon', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_pi_icon_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6ec1e4',
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-post-info__icon-list-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-post-info__icon-list-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_pi_icon_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 14,
				),
				'range'     => array(
					'px' => array(
						'min' => 6,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-post-info__icon-list-icon' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-post-info__icon-list-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-post-info__icon-list-icon svg' => '--rael-pi-icon-list-icon-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register style tab text controls.
	 *
	 * This method sets up controls for styling text in the style tab.
	 *
	 * @since 1.0.0
	 */
	protected function register_style_tab_text_controls() {
		$this->start_controls_section(
			'rael_style_tab_text_section',
			array(
				'label' => __( 'Text', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_pi_text_indent',
			array(
				'label'     => __( 'Indent', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .rael-post-info__icon-list-text' => 'padding-left: {{SIZE}}{{UNIT}};',
					'body.rtl {{WRAPPER}} .rael-post-info__icon-list-text' => 'padding-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_pi_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#54595f',
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-post-info__icon-list-text, {{WRAPPER}} .rael-post-info__icon-list-text a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_pi_icon_typography',
				'selector' => '{{WRAPPER}} .rael-post-info__icon-list-item',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get taxonomies.
	 *
	 * Retrieves taxonomies that are set to show in navigation menus and returns them as options for controls.
	 *
	 * @return array Array of taxonomies with keys as taxonomy names and values as taxonomy labels.
	 */
	protected function get_taxonomies() {
		$taxonomies = get_taxonomies(
			array( 'show_in_nav_menus' => true ),
			'objects'
		);

		$options = array(
			'' => __( 'Choose', 'responsive-addons-for-elementor' ),
		);

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	/**
	 *
	 * Renders the content tab meta data controls based on the provided settings.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		ob_start();
		if ( ! empty( $settings['rael_pi_icon_list'] ) ) {
			foreach ( $settings['rael_pi_icon_list'] as $repeater_item ) {
				$this->render_item( $repeater_item );
			}
		}

		$html = ob_get_clean();

		if ( empty( $html ) ) {
			return;
		}

		if ( 'inline' === $settings['rael_pi_view'] ) {
			$this->add_render_attribute( 'rael_pi_icon_list', 'class', 'rael-post-info--inline-items' );
		}

		$this->add_render_attribute( 'rael_pi_icon_list', 'class', array( 'rael-post-info__icon-list-items', 'rael-post-info' ) );
		?>
		<ul <?php $this->print_render_attribute_string( 'rael_pi_icon_list' ); ?>>
			<?php echo $html; //phpcs:ignore ?> 
		</ul>
		<?php
	}

	/**
	 * Render item.
	 *
	 * Renders an individual item within the content tab meta data controls.
	 *
	 * @param array $item The item data to be rendered.
	 */
	protected function render_item( $item ) {
		$item_data = $this->get_meta_data( $item );
		$index     = $item['_id'];

		if ( empty( $item_data['text'] ) && empty( $item_data['terms_list'] ) ) {
			return;
		}

		$has_link = false;
		$link_key = 'link_' . $index;
		$item_key = 'item_' . $index;

		$this->add_render_attribute(
			$item_key,
			'class',
			array(
				'rael-post-info__icon-list-item',
				'elementor-repeater-item-' . $index,
			)
		);

		$active_settings = $this->get_active_settings();

		if ( 'inline' === $active_settings['rael_pi_view'] ) {
			$this->add_render_attribute( $item_key, 'class', 'rael-post-info--inline-item' );
		}

		if ( ! empty( $item_data['url']['url'] ) ) {
			$has_link = true;

			$this->add_link_attributes(
				$link_key,
				$item_data['url']
			);
		}

		if ( ! empty( $item_data['itemprop'] ) ) {
			$this->add_render_attribute( $item_key, 'itemprop', $item_data['itemprop'] );
		}

		?>

		<li <?php $this->print_render_attribute_string( $item_key ); ?>>
			<?php if ( $has_link ) : ?>
				<a <?php $this->print_render_attribute_string( $link_key ); ?>>
			<?php endif; ?>

				<?php $this->render_item_icon_or_image( $item_data, $item, $index ); ?>
				<?php $this->render_item_text( $item_data, $index ); ?>

			<?php if ( $has_link ) : ?>
				</a>
			<?php endif; ?>
		</li>
		<?php
	}

	/**
	 * Render item icon or image.
	 *
	 * Renders the icon or image associated with the item data.
	 *
	 * @param array $item_data The data associated with the item.
	 * @param array $item      The item being rendered.
	 * @param int   $index     The index of the item in the repeater.
	 */
	protected function render_item_icon_or_image( $item_data, $item, $index ) {
		if ( empty( $item_data['icon'] ) && empty( $item_data['selected_icon'] ) && empty( $item_data['image'] ) ) {
			return;
		}

		// Set icon according to user settings.
		$migration_allowed = Icons_Manager::is_migration_allowed();
		if ( ! $migration_allowed ) {
			if ( 'custom' === $item['rael_meta_data_show_icon'] && ! empty( $item['rael_meta_data_icon'] ) ) {
				$item_data['icon'] = $item['rael_meta_data_icon'];
			} elseif ( 'none' === $item['rael_meta_data_show_icon'] ) {
				$item_data['icon'] = '';
			}
		} else {
			if ( 'custom' === $item['rael_meta_data_show_icon'] && ! empty( $item['rael_meta_data_selected_icon'] ) ) {
				$item_data['selected_icon'] = $item['rael_meta_data_selected_icon'];
			} elseif ( 'none' === $item['rael_meta_data_show_icon'] ) {
				$item_data['selected_icon'] = array();
			}
		}

		$migrated  = isset( $item['__fa4_migrated']['selected_icon'] );
		$is_new    = empty( $item['rael_meta_data_icon'] ) && $migration_allowed;
		$show_icon = 'none' !== $item['rael_meta_data_show_icon'];

		if ( ! empty( $item_data['image'] ) || $show_icon ) {
			?>
			<span class="rael-post-info__icon-list-icon">
				<?php
				if ( ! empty( $item_data['image'] ) ) :
					$image_data = 'image_' . $index;
					$this->add_render_attribute( $image_data, 'src', $item_data['image'] );
					$this->add_render_attribute( $image_data, 'alt', $item_data['text'] );
					?>
					<img class="rael-post-info__avatar" <?php $this->print_render_attribute_string( $image_data ); ?> />
				<?php elseif ( $show_icon ) : ?>
					<?php
					if ( $is_new || $migrated ) :
						Icons_Manager::render_icon( $item_data['selected_icon'], array( 'aria-hidden' => 'true' ) );
					else :
						?>
						<i class="<?php echo esc_attr( $item_data['icon'] ); ?>" aria-hidden="true"></i>
					<?php endif; ?>
				<?php endif; ?>
			</span>
			<?php
		}
	}

	/**
	 * Render item text.
	 *
	 * Renders the text associated with the item data.
	 *
	 * @param array $item_data The data associated with the item.
	 * @param int   $index     The index of the item in the repeater.
	 */
	protected function render_item_text( $item_data, $index ) {
		$item_setting_key = $this->get_repeater_setting_key( 'rael_meta_data_show', 'rael_pi_icon_list', $index );

		$this->add_render_attribute(
			$item_setting_key,
			'class',
			array(
				'rael-post-info__icon-list-text',
				'rael-post-info__item',
				'rael-post-info__item--type-' . $item_data['type'],
			)
		);

		if ( ! empty( $item_data['terms_list'] ) ) {
			$this->add_render_attribute( $item_setting_key, 'class', 'rael-post-info__terms-list-wrapper' );
		}

		?>

		<span <?php $this->print_render_attribute_string( $item_setting_key ); ?>>
			<?php if ( ! empty( $item_data['text_prefix'] ) ) : ?>
				<span class="rael-post-info__item-prefix"><?php echo esc_html( $item_data['text_prefix'] ); ?></span>
			<?php endif; ?>
			<?php
			if ( ! empty( $item_data['terms_list'] ) ) :
				$terms_list = array();
				$item_class = 'rael-post-info__terms-list-item';
				?>
			<span class="rael-post-info__terms-list">
				<?php
				foreach ( $item_data['terms_list'] as $term ) :
					if ( ! empty( $term['url'] ) ) :
						$terms_list[] = '<a href="' . esc_attr( $term['url'] ) . '" class="' . $item_class . '">' . esc_html( $term['text'] ) . '</a>';
					else :
						$terms_list[] = '<span class="' . esc_attr( $item_class ) . '">' . esc_html( $term['text'] ) . '</span>';
					endif;
				endforeach;

				echo implode( ', ', $terms_list );//phpcs:ignore
				?>
			</span>
			<?php else : ?>
				<?php
				echo wp_kses(
					$item_data['text'],
					array(
						'a' => array(
							'href'  => array(),
							'title' => array(),
							'rel'   => array(),
						),
					)
				);
				?>
			<?php endif; ?>
		</span>
		<?php
	}

	/**
	 * Get meta data.
	 *
	 * Retrieves meta data for an item based on its settings.
	 *
	 * @param array $item The item for which to retrieve meta data.
	 *
	 * @return array An array containing the meta data for the item.
	 */
	protected function get_meta_data( $item ) {
		$item_data = array();

		switch ( $item['rael_meta_data_type'] ) {
			case 'author':
				$item_data['text']          = get_the_author_meta( 'display_name' );
				$item_data['icon']          = 'fa fa-user-circle-o';
				$item_data['selected_icon'] = array(
					'value'   => 'far fa-user-circle',
					'library' => 'fa-regular',
				);
				$item_data['itemprop']      = 'author';

				if ( 'yes' === $item['rael_meta_data_link'] ) {
					$item_data['url'] = array(
						'url' => get_author_posts_url( get_the_author_meta( 'ID' ) ),
					);
				}

				if ( 'yes' === $item['rael_meta_data_show_avatar'] ) {
					$item_data['image'] = get_avatar_url( get_the_author_meta( 'ID' ), 96 );
				}

				break;

			case 'date':
				$custom_date_format = empty( $item['rael_meta_data_custom_date_format'] ) ? 'F j, Y' : $item['rael_meta_data_custom_date_format'];

				$format_options = array(
					'default' => 'F j, Y',
					'0'       => 'F j, Y',
					'1'       => 'Y-m-d',
					'2'       => 'm/d/Y',
					'3'       => 'd/m/Y',
					'custom'  => $custom_date_format,
				);

				$item_data['text']          = get_the_time( $format_options[ $item['rael_meta_data_date_format'] ] );
				$item_data['icon']          = 'fa fa-calendar';
				$item_data['selected_icon'] = array(
					'value'   => 'fas fa-calendar',
					'library' => 'fa-solid',
				);
				$item_data['itemprop']      = 'datePublished';

				if ( 'yes' === $item['rael_meta_data_link'] ) {
					$item_data['url'] = array(
						'url' => get_day_link( get_post_time( 'Y' ), get_post_time( 'm' ), get_post_time( 'j' ) ),
					);
				}

				break;

			case 'time':
				$custom_time_format = empty( $item['rael_meta_data_custom_time_format'] ) ? 'g:i a' : $item['rael_meta_data_custom_time_format'];

				$format_options             = array(
					'default' => 'g:i a',
					'0'       => 'g:i a',
					'1'       => 'g:i A',
					'2'       => 'H:i',
					'custom'  => $custom_time_format,
				);
				$item_data['text']          = get_the_time( $format_options[ $item['rael_meta_data_time_format'] ] );
				$item_data['icon']          = 'fa fa-clock-o';
				$item_data['selected_icon'] = array(
					'value'   => 'far fa-clock',
					'library' => 'fa-regular',
				);

				break;

			case 'comments':
				if ( comments_open() ) {
					$default_strings = array(
						'string_no_comments' => __( 'No Comments', 'responsive-addons-for-elementor' ),
						'string_one_comment' => __( 'One Comment', 'responsive-addons-for-elementor' ),
						// translators: %s is the number of comments.
						'string_comments'    => __( '%s Comments', 'responsive-addons-for-elementor' ),
					);

					if ( 'yes' === $item['rael_meta_data_comments_custom_strings'] ) {
						if ( ! empty( $item['rael_meta_data_string_no_comments'] ) ) {
							$default_strings['string_no_comments'] = $item['rael_meta_data_string_no_comments'];
						}

						if ( ! empty( $item['rael_meta_data_string_one_comment'] ) ) {
							$default_strings['string_one_comment'] = $item['rael_meta_data_string_one_comment'];
						}

						if ( ! empty( $item['rael_meta_data_string_comments'] ) ) {
							$default_strings['string_comments'] = $item['rael_meta_data_string_comments'];
						}
					}

					$number_of_comments = (int) get_comments_number();

					if ( 0 === $number_of_comments ) {
						$item_data['text'] = $default_strings['string_no_comments'];
					} else {
						$item_data['text'] = sprintf( _n( $default_strings['string_one_comment'], $default_strings['string_comments'], $number_of_comments, 'responsive-addons-for-elementor' ), $number_of_comments ); //phpcs:ignore
					}

					if ( 'yes' === $item['rael_meta_data_link'] ) {
						$item_data['url'] = array(
							'url' => get_comments_link(),
						);
					}

					$item_data['icon']          = 'fa fa-commenting-o'; // Default icon.
					$item_data['selected_icon'] = array(
						'value'   => 'far fa-comment-dots',
						'library' => 'fa-regular',
					); // Default icons.
					$item_data['itemprop']      = 'commentCount';
				}
				break;

			case 'terms':
				$item_data['icon']          = 'fa fa-tags';
				$item_data['selected_icon'] = array(
					'value'   => 'fas fa-tags',
					'library' => 'fa-solid',
				); // Default icons.
				$item_data['itemprop']      = 'about';

				$taxonomy = $item['rael_meta_data_taxonomy'];
				$terms    = wp_get_post_terms( get_the_ID(), $taxonomy );
				foreach ( $terms as $term ) {
					$item_data['terms_list'][ $term->term_id ]['text'] = $term->name;

					if ( 'yes' === $item['rael_meta_data_link'] ) {
						$item_data['terms_list'][ $term->term_id ]['url'] = get_term_link( $term );
					}
				}

				break;

			case 'custom':
				$item_data['text']          = $item['rael_meta_data_custom_text'];
				$item_data['icon']          = 'fa fa-info-circle'; // Default icon.
				$item_data['selected_icon'] = array(
					'value'   => 'far fa-tags',
					'library' => 'fa-regular',
				);

				if ( 'yes' === $item['rael_meta_data_link'] && ! empty( $item['rael_meta_data_custom_url'] ) ) {
					$item_data['url'] = $item['rael_meta_data_custom_url'];
				}

				break;
		}

		$item_data['type'] = $item['rael_meta_data_type'];

		if ( ! empty( $item['rael_meta_data_text_prefix'] ) ) {
			$item_data['text_prefix'] = esc_html( $item['rael_meta_data_text_prefix'] );
		}

		return $item_data;
	}

}
