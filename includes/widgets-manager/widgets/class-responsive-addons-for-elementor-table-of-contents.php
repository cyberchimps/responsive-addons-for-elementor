<?php
/**
 * Table of Content Widget
 *
 * @since
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}
/**
 * Elementor 'Table of content' widget.
 *
 * Elementor widget that displays Table of contents.
 *
 * @since 1.3.0
 */
class Responsive_Addons_For_Elementor_Table_Of_Contents extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-table-of-contents';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Table Of Contents', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Table Of Contents widget icon.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-table-of-contents rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the Table Of Contents widget belongs to.
	 *
	 * @since 1.2.2
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}
	/**
	 * Get the keywords associated with the Table of Contents widget.
	 *
	 * @return array An array containing the keywords: 'toc', 'table', 'content'.
	 */
	public function get_keywords() {
		return array( 'toc', 'table', 'content' );
	}
	/**
	 * Register controls for the Table of Contents widget.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'table_of_contents_section',
			array(
				'label' => __( 'Table of Contents', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_title',
			array(
				'label'       => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => true,
				'default'     => __( 'Table of Contents', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_html_tag',
			array(
				'label'   => __( 'HTML Tag', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h2'  => 'H2',
					'h3'  => 'H3',
					'h4'  => 'H4',
					'h5'  => 'H5',
					'h6'  => 'H6',
					'div' => 'div',
				),
				'default' => 'h3',
			)
		);

		$this->start_controls_tabs( 'include_exclude_tags', array( 'separator' => 'before' ) );

		$this->start_controls_tab(
			'include',
			array(
				'label' => __( 'Include', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_headings_by_tags',
			array(
				'label'              => __( 'Anchors By Tags', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT2,
				'multiple'           => true,
				'default'            => array( 'h2', 'h3', 'h4', 'h5', 'h6' ),
				'options'            => array(
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				),
				'label_block'        => true,
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_container',
			array(
				'label'              => __( 'Container', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::TEXT,
				'label_block'        => true,
				'description'        => __( 'This control confines the Table of Contents to heading elements under a specific container', 'responsive-addons-for-elementor' ),
				'frontend_available' => true,
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'exclude',
			array(
				'label' => __( 'Exclude', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_exclude_headings_by_selector',
			array(
				'label'              => __( 'Anchors By Selector', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::TEXT,
				'description'        => __( 'CSS selectors, in a comma-separated list', 'responsive-addons-for-elementor' ),
				'default'            => array(),
				'label_block'        => true,
				'frontend_available' => true,
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'rael_marker_view',
			array(
				'label'              => __( 'Marker View', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'numbers',
				'options'            => array(
					'numbers' => __( 'Numbers', 'responsive-addons-for-elementor' ),
					'bullets' => __( 'Bullets', 'responsive-addons-for-elementor' ),
				),
				'separator'          => 'before',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_icon',
			array(
				'label'                  => __( 'Icon', 'responsive-addons-for-elementor' ),
				'type'                   => Controls_Manager::ICONS,
				'default'                => array(
					'value'   => 'fas fa-circle',
					'library' => 'fa-solid',
				),
				'recommended'            => array(
					'fa-solid'   => array(
						'circle',
						'dot-circle',
						'square-full',
					),
					'fa-regular' => array(
						'circle',
						'dot-circle',
						'square-full',
					),
				),
				'condition'              => array(
					'rael_marker_view' => 'bullets',
				),
				'skin'                   => 'inline',
				'label_block'            => false,
				'exclude_inline_options' => array( 'svg' ),
				'frontend_available'     => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'additional_options_section',
			array(
				'label' => __( 'Additional Options', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_word_wrap',
			array(
				'label'        => __( 'Word Wrap', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'ellipsis',
				'prefix_class' => 'rael-toc--content-',
			)
		);

		$this->add_control(
			'rael_minimize_box',
			array(
				'label'              => __( 'Minimize Box', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_expand_icon',
			array(
				'label'       => __( 'Icon', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => array(
					'value'   => 'fas fa-chevron-down',
					'library' => 'fa-solid',
				),
				'recommended' => array(
					'fa-solid'   => array(
						'chevron-down',
						'angle-down',
						'angle-double-down',
						'caret-down',
						'caret-square-down',
					),
					'fa-regular' => array(
						'caret-square-down',
					),
				),
				'skin'        => 'inline',
				'label_block' => false,
				'condition'   => array(
					'rael_minimize_box' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_collapse_icon',
			array(
				'label'       => __( 'Minimize Icon', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => array(
					'value'   => 'fas fa-chevron-up',
					'library' => 'fa-solid',
				),
				'recommended' => array(
					'fa-solid'   => array(
						'chevron-up',
						'angle-up',
						'angle-double-up',
						'caret-up',
						'caret-square-up',
					),
					'fa-regular' => array(
						'caret-square-up',
					),
				),
				'skin'        => 'inline',
				'label_block' => false,
				'condition'   => array(
					'rael_minimize_box' => 'yes',
				),
			)
		);

		$breakpoints = Responsive::get_breakpoints();

		$this->add_control(
			'rael_minimized_on',
			array(
				'label'              => __( 'Minimized On', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'tablet',
				'options'            => array(
					/* translators: %d: Breakpoint number. */
					'mobile' => sprintf( __( 'Mobile (< %dpx)', 'responsive-addons-for-elementor' ), $breakpoints['md'] ),
					/* translators: %d: Breakpoint number. */
					'tablet' => sprintf( __( 'Tablet (< %dpx)', 'responsive-addons-for-elementor' ), $breakpoints['lg'] ),
					'none'   => __( 'None', 'responsive-addons-for-elementor' ),
				),
				'prefix_class'       => 'rael-toc--minimized-on-',
				'condition'          => array(
					'rael_minimize_box!' => '',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_hierarchical_view',
			array(
				'label'              => __( 'Hierarchical View', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_collapse_subitems',
			array(
				'label'              => __( 'Collapse Subitems', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'description'        => __( 'The "Collapse" option should only be used if the Table of Contents is made sticky', 'responsive-addons-for-elementor' ),
				'condition'          => array(
					'rael_hierarchical_view' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'box_style_section',
			array(
				'label' => __( 'Box', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}' => '--box-background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--box-border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_border_width',
			array(
				'label'     => __( 'Border Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}}' => '--box-border-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'rael_border_radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}}' => '--box-border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'rael_padding',
			array(
				'label'     => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}}' => '--box-padding: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'rael_min_height',
			array(
				'label'              => __( 'Min Height', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px', 'vh' ),
				'range'              => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'selectors'          => array(
					'{{WRAPPER}}' => '--box-min-height: {{SIZE}}{{UNIT}}',
				),
				'frontend_available' => true,
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'header_style_section',
			array(
				'label' => __( 'Header', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_header_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--header-background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_header_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--header-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'header_typography',
				'selector' => '{{WRAPPER}} .rael-toc__header, {{WRAPPER}} .rael-toc__header-title',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
			)
		);

		$this->add_control(
			'rael_toggle_button_color',
			array(
				'label'     => __( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'rael_minimize_box' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--toggle-button-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_header_separator_width',
			array(
				'label'     => __( 'Separator Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}}' => '--separator-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'list_style_section',
			array(
				'label' => __( 'List', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_max_height',
			array(
				'label'      => __( 'Max Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--toc-body-max-height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'list_typography',
				'selector' => '{{WRAPPER}} .rael-toc__list-item',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
			)
		);

		$this->add_control(
			'rael_list_indent',
			array(
				'label'      => __( 'Indent', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'unit' => 'em',
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--nested-list-indent: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->start_controls_tabs( 'item_text_style' );

		$this->start_controls_tab(
			'normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_item_text_color_normal',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--item-text-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_item_text_underline_normal',
			array(
				'label'     => __( 'Underline', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => array(
					'{{WRAPPER}}' => '--item-text-decoration: underline',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_item_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_ACCENT,
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--item-text-hover-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_item_text_underline_hover',
			array(
				'label'     => __( 'Underline', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'selectors' => array(
					'{{WRAPPER}}' => '--item-text-hover-decoration: underline',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'active',
			array(
				'label' => __( 'Active', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_item_text_color_active',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--item-text-active-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_item_text_underline_active',
			array(
				'label'     => __( 'Underline', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => array(
					'{{WRAPPER}}' => '--item-text-active-decoration: underline',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'rael_heading_marker',
			array(
				'label'     => __( 'Marker', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_marker_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--marker-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'rael_marker_size',
			array(
				'label'      => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--marker-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Render the Table of Contents widget output.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$html_tag = Utils::validate_html_tag( $settings['rael_html_tag'] );
		?>
			<div class="rael-toc__header">
				<?php echo '<' . wp_kses_post( $html_tag ) . ' class="rael-toc__header-title">' . wp_kses_post( $settings['rael_title'] ) . '</' . wp_kses_post( $html_tag ) . '>'; ?>
				<?php if ( 'yes' === $settings['rael_minimize_box'] ) : ?>
					<div class="rael-toc__toggle-button rael-toc__toggle-button--expand"><?php Icons_Manager::render_icon( $settings['rael_expand_icon'] ); ?></div>
					<div class="rael-toc__toggle-button rael-toc__toggle-button--collapse"><?php Icons_Manager::render_icon( $settings['rael_collapse_icon'] ); ?></div>
				<?php endif; ?>
			</div>
		<?php
		$this->add_render_attribute( 'body', 'class', 'rael-toc__body' );

		if ( $settings['rael_collapse_subitems'] ) {
			$this->add_render_attribute( 'body', 'class', 'rael-toc__list-items--collapsible' );
		}
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'body' ) ); ?>>
			<div class="rael-toc__spinner-container">
				<i class="rael-toc__spinner eicon-loading eicon-animation-spin" aria-hidden="true"></i>
			</div>
		</div>
		<?php
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/table-of-contents';
	}
}
