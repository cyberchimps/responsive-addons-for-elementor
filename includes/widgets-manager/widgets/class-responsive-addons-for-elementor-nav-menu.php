<?php
/**
 * RAE Nav Menu.
 *
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.

}
/**
 * Elementor 'Nav Menu' widget.
 *
 * Elementor widget that displays Nav Menu.
 */
class Responsive_Addons_For_Elementor_Nav_Menu extends Widget_Base {
	/**
	 * The index of the navigation menu.
	 *
	 * @var int
	 */
	protected $nav_menu_index = 1;
	/**
	 * Get the name of the navigation menu.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'rael-nav-menu';
	}
	/**
	 * Get the title of the navigation menu.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Nav Menu', 'responsive-addons-for-elementor' );
	}
	/**
	 * Get the icon for the navigation menu.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-nav-menu rael-badge';
	}
	/**
	 * Get the categories for the navigation menu.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}
	/**
	 * Get the index for the navigation menu.
	 *
	 * @return int
	 */
	protected function get_nav_menu_index() {
		return $this->nav_menu_index++;
	}
	/**
	 * Get the script dependencies for the navigation menu.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'rael-smartmenus' );
	}
	/**
	 * Get an array of created menus with their slugs as keys and names as values.
	 *
	 * @return array
	 */
	private function get_created_menus() {
		$menus   = wp_get_nav_menus();
		$options = array();

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}
	/**
	 * Register the controls of the widget.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'responsive-addons-for-elementor' ),
			)
		);

		$menus = $this->get_created_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'rael_menu',
				array(
					'label'        => __( 'Menu', 'responsive-addons-for-elementor' ),
					'type'         => Controls_Manager::SELECT,
					'options'      => $menus,
					'default'      => array_keys( $menus )[0],
					'save_default' => true,
					'separator'    => 'after',
					/* translators: This placeholder represents the URL of the Menus screen in the WordPress admin. */
					'description'  => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'responsive-addons-for-elementor' ), admin_url( 'nav-menus.php' ) ),
				)
			);
		} else {
			$this->add_control(
				'rael_menu',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: This placeholder represents the URL of the Menus screen in the WordPress admin. */
					'raw'             => '<strong>' . __(
						'There are no menus in your site.',
						'responsive-addons-for-elementor'
					) . '</strong><br>' . sprintf(
						// translators: This placeholder represents a message indicating the absence of menus on the site.
						__( 'Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'responsive-addons-for-elementor' ),
						admin_url( 'nav-menus.php?action=edit&menu=0' )
					),
					'separator'       => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);
		}

		$this->add_control(
			'rael_layout',
			array(
				'label'              => __( 'Layout', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'horizontal',
				'options'            => array(
					'horizontal' => __( 'Horizontal', 'responsive-addons-for-elementor' ),
					'vertical'   => __( 'Vertical', 'responsive-addons-for-elementor' ),
					'dropdown'   => __( 'Dropdown', 'responsive-addons-for-elementor' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'rael_align_items',
			array(
				'label'        => __( 'Align', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'    => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
					'justify' => array(
						'title' => __( 'Stretch', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-stretch',
					),
				),
				'prefix_class' => 'elementor-rael-nav-menu__align%s-',
				'condition'    => array(
					'rael_layout!' => 'dropdown',
				),
			)
		);

		$this->add_control(
			'rael_pointer',
			array(
				'label'          => __( 'Pointer', 'responsive-addons-for-elementor' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => 'underline',
				'options'        => array(
					'none'        => __( 'None', 'responsive-addons-for-elementor' ),
					'underline'   => __( 'Underline', 'responsive-addons-for-elementor' ),
					'overline'    => __( 'Overline', 'responsive-addons-for-elementor' ),
					'double-line' => __( 'Double Line', 'responsive-addons-for-elementor' ),
					'framed'      => __( 'Framed', 'responsive-addons-for-elementor' ),
					'background'  => __( 'Background', 'responsive-addons-for-elementor' ),
					'text'        => __( 'Text', 'responsive-addons-for-elementor' ),
				),
				'style_transfer' => true,
				'condition'      => array(
					'rael_layout!' => 'dropdown',
				),
			)
		);

		$this->add_control(
			'rael_indicator',
			array(
				'label'        => __( 'Submenu Indicator', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'classic',
				'options'      => array(
					'none'    => __( 'None', 'responsive-addons-for-elementor' ),
					'classic' => __( 'Classic', 'responsive-addons-for-elementor' ),
					'angle'   => __( 'Angle', 'responsive-addons-for-elementor' ),
					'chevron' => __( 'Chevron', 'responsive-addons-for-elementor' ),
					'plus'    => __( 'Plus', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'elementor-rael-nav-menu--indicator-',
			)
		);

		$this->add_control(
			'rael_animation_line',
			array(
				'label'     => __( 'Animation', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fade',
				'options'   => array(
					'fade'     => 'Fade',
					'grow'     => 'Grow',
					'slide'    => 'Slide',
					'drop-out' => 'Drop Out',
					'drop-in'  => 'Drop In',
					'none'     => 'None',
				),
				'condition' => array(
					'rael_layout!' => 'dropdown',
					'rael_pointer' => array( 'underline', 'overline', 'double-line' ),
				),
			)
		);

		$this->add_control(
			'rael_animation_framed',
			array(
				'label'     => __( 'Animation', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fade',
				'options'   => array(
					'fade'    => 'Fade',
					'shrink'  => 'Shrink',
					'grow'    => 'Grow',
					'corners' => 'Corners',
					'draw'    => 'Draw',
					'none'    => 'None',
				),
				'condition' => array(
					'rael_layout!' => 'dropdown',
					'rael_pointer' => 'framed',
				),
			)
		);

		$this->add_control(
			'rael_animation_background',
			array(
				'label'     => __( 'Animation', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fade',
				'options'   => array(
					'fade'                   => 'Fade',
					'shrink'                 => 'Shrink',
					'grow'                   => 'Grow',
					'sweep-up'               => 'Sweep Up',
					'sweep-down'             => 'Sweep Down',
					'sweep-left'             => 'Sweep Left',
					'sweep-right'            => 'Sweep Right',
					'shutter-in-horizontal'  => 'Shutter In Horizontal',
					'shutter-out-horizontal' => 'Shutter Out Horizontal',
					'shutter-in-vertical'    => 'Shutter In Vertical',
					'shutter-out-vertical'   => 'Shutter Out Vertical',
					'none'                   => 'None',
				),
				'condition' => array(
					'rael_layout!' => 'dropdown',
					'rael_pointer' => 'background',
				),
			)
		);

		$this->add_control(
			'rael_animation_text',
			array(
				'label'     => __( 'Animation', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'grow',
				'options'   => array(
					'grow'   => 'Grow',
					'shrink' => 'Shrink',
					'sink'   => 'Sink',
					'float'  => 'Float',
					'skew'   => 'Skew',
					'rotate' => 'Rotate',
					'none'   => 'None',
				),
				'condition' => array(
					'rael_layout!' => 'dropdown',
					'rael_pointer' => 'text',
				),
			)
		);

		$this->add_control(
			'rael_heading_mobile_dropdown',
			array(
				'label'     => __( 'Mobile Dropdown', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_layout!' => 'dropdown',
				),
			)
		);

		$breakpoints = Responsive::get_breakpoints();

		$this->add_control(
			'rael_dropdown',
			array(
				'label'        => __( 'Breakpoint', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'tablet',
				'options'      => array(
					/* translators: %d: Breakpoint number. */
					'mobile' => sprintf( __( 'Mobile (< %dpx)', 'responsive-addons-for-elementor' ), $breakpoints['md'] ),
					/* translators: %d: Breakpoint number. */
					'tablet' => sprintf( __( 'Tablet (< %dpx)', 'responsive-addons-for-elementor' ), $breakpoints['lg'] ),
					'none'   => __( 'None', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'elementor-rael-nav-menu--dropdown-',
				'condition'    => array(
					'rael_layout!' => 'dropdown',
				),
			)
		);

		$this->add_control(
			'rael_full_width',
			array(
				'label'              => __( 'Full Width', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'description'        => __( 'Stretch the dropdown of the menu to full width.', 'responsive-addons-for-elementor' ),
				'prefix_class'       => 'elementor-rael-nav-menu--',
				'return_value'       => 'stretch',
				'frontend_available' => true,
				'condition'          => array(
					'rael_dropdown!' => 'none',
				),
			)
		);

		$this->add_control(
			'rael_text_align',
			array(
				'label'        => __( 'Align', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'aside',
				'options'      => array(
					'aside'  => __( 'Aside', 'responsive-addons-for-elementor' ),
					'center' => __( 'Center', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'elementor-rael-nav-menu__text-align-',
				'condition'    => array(
					'rael_dropdown!' => 'none',
				),
			)
		);

		$this->add_control(
			'rael_toggle',
			array(
				'label'              => __( 'Toggle Button', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'burger',
				'options'            => array(
					''       => __( 'None', 'responsive-addons-for-elementor' ),
					'burger' => __( 'Hamburger', 'responsive-addons-for-elementor' ),
				),
				'prefix_class'       => 'elementor-rael-nav-menu--toggle elementor-rael-nav-menu--',
				'render_type'        => 'template',
				'frontend_available' => true,
				'condition'          => array(
					'rael_dropdown!' => 'none',
				),
			)
		);

		$this->add_control(
			'rael_toggle_align',
			array(
				'label'                => __( 'Toggle Align', 'responsive-addons-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'default'              => 'center',
				'options'              => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors_dictionary' => array(
					'left'   => 'margin-right: auto',
					'center' => 'margin: 0 auto',
					'right'  => 'margin-left: auto',
				),
				'selectors'            => array(
					'{{WRAPPER}} .elementor-rael-menu-toggle' => '{{VALUE}}',
				),
				'condition'            => array(
					'rael_toggle!'   => '',
					'rael_dropdown!' => 'none',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_main_menu',
			array(
				'label'     => __( 'Main Menu', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_layout!' => 'dropdown',
				),

			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'menu_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .elementor-rael-nav-menu .elementor-item',
			)
		);

		$this->start_controls_tabs( 'tabs_menu_style' );

		$this->start_controls_tab(
			'tab_menu_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_color_menu',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-nav-menu--main .elementor-item' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_color_menu_hover',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_ACCENT,
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-nav-menu--main .elementor-item:hover,
					{{WRAPPER}} .elementor-rael-nav-menu--main .elementor-item.elementor-item-active,
					{{WRAPPER}} .elementor-rael-nav-menu--main .elementor-item.highlighted,
					{{WRAPPER}} .elementor-rael-nav-menu--main .elementor-item:focus' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'rael_pointer!' => 'background',
				),
			)
		);

		$this->add_control(
			'rael_color_menu_hover_pointer_bg',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-nav-menu--main .elementor-item:hover,
					{{WRAPPER}} .elementor-rael-nav-menu--main .elementor-item.elementor-item-active,
					{{WRAPPER}} .elementor-rael-nav-menu--main .elementor-item.highlighted,
					{{WRAPPER}} .elementor-rael-nav-menu--main .elementor-item:focus' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'rael_pointer' => 'background',
				),
			)
		);

		$this->add_control(
			'rael_pointer_color_menu_hover',
			array(
				'label'     => __( 'Pointer Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_ACCENT,
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-nav-menu--main:not(.rael--pointer-framed) .elementor-item:before,
					{{WRAPPER}} .elementor-rael-nav-menu--main:not(.rael--pointer-framed) .elementor-item:after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .rael--pointer-framed .elementor-item:before,
					{{WRAPPER}} .rael--pointer-framed .elementor-item:after' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'rael_pointer!' => array( 'none', 'text' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_active',
			array(
				'label' => __( 'Active', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_color_menu_active',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-nav-menu--main .elementor-item.elementor-item-active' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_pointer_color_menu_active',
			array(
				'label'     => __( 'Pointer Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-nav-menu--main:not(.rael--pointer-framed) .elementor-item.elementor-item-active:before,
					{{WRAPPER}} .elementor-rael-nav-menu--main:not(.rael--pointer-framed) .elementor-item.elementor-item-active:after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .rael--pointer-framed .elementor-item.elementor-item-active:before,
					{{WRAPPER}} .rael--pointer-framed .elementor-item.elementor-item-active:after' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'rael_pointer!' => array( 'none', 'text' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'rael_hr',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_responsive_control(
			'rael_pointer_width',
			array(
				'label'     => __( 'Pointer Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 30,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael--pointer-framed .elementor-item:before' => 'border-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .rael--pointer-framed.rael--animation-draw .elementor-item:before' => 'border-width: 0 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .rael--pointer-framed.rael--animation-draw .elementor-item:after' => 'border-width: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0',
					'{{WRAPPER}} .rael--pointer-framed.rael--animation-corners .elementor-item:before' => 'border-width: {{SIZE}}{{UNIT}} 0 0 {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .rael--pointer-framed.rael--animation-corners .elementor-item:after' => 'border-width: 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0',
					'{{WRAPPER}} .rael--pointer-underline .elementor-item:after,
					 {{WRAPPER}} .rael--pointer-overline .elementor-item:before,
					 {{WRAPPER}} .rael--pointer-double-line .elementor-item:before,
					 {{WRAPPER}} .rael--pointer-double-line .elementor-item:after' => 'height: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'rael_pointer' => array( 'underline', 'overline', 'double-line', 'framed' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_padding_horizontal_menu_item',
			array(
				'label'     => __( 'Horizontal Padding', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-nav-menu--main .elementor-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'rael_padding_vertical_menu_item',
			array(
				'label'     => __( 'Vertical Padding', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-nav-menu--main .elementor-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'rael_menu_space_between',
			array(
				'label'     => __( 'Space Between', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .elementor-rael-nav-menu--layout-horizontal .elementor-rael-nav-menu > li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .elementor-rael-nav-menu--layout-horizontal .elementor-rael-nav-menu > li:not(:last-child)' => 'margin-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .elementor-rael-nav-menu--main:not(.elementor-rael-nav-menu--layout-horizontal) .elementor-rael-nav-menu > li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'rael_border_radius_menu_item',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-item:before' => 'border-radius: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .rael--animation-shutter-in-horizontal .elementor-item:before' => 'border-radius: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0',
					'{{WRAPPER}} .rael--animation-shutter-in-horizontal .elementor-item:after' => 'border-radius: 0 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .rael--animation-shutter-in-vertical .elementor-item:before' => 'border-radius: 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0',
					'{{WRAPPER}} .rael--animation-shutter-in-vertical .elementor-item:after' => 'border-radius: {{SIZE}}{{UNIT}} 0 0 {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'rael_pointer' => 'background',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_dropdown',
			array(
				'label' => __( 'Dropdown', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael-dropdown_description',
			array(
				'raw'             => __( 'On desktop, this will affect the submenu. On mobile, this will affect the entire menu.', 'responsive-addons-for-elementor' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$this->start_controls_tabs( 'tabs_dropdown_style' );

		$this->start_controls_tab(
			'tab_dropdown_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_color_dropdown_item',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-nav-menu--dropdown a, {{WRAPPER}} .elementor-rael-menu-toggle' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_background_color_dropdown_item',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-nav-menu--dropdown' => 'background-color: {{VALUE}}',
				),
				'separator' => 'none',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dropdown_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_color_dropdown_item_hover',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-nav-menu--dropdown a:hover,
					{{WRAPPER}} .elementor-rael-nav-menu--dropdown a.elementor-item-active,
					{{WRAPPER}} .elementor-rael-nav-menu--dropdown a.highlighted,
					{{WRAPPER}} .elementor-rael-menu-toggle:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_background_color_dropdown_item_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-nav-menu--dropdown a:hover,
					{{WRAPPER}} .elementor-rael-nav-menu--dropdown a.elementor-item-active,
					{{WRAPPER}} .elementor-rael-nav-menu--dropdown a.highlighted' => 'background-color: {{VALUE}}',
				),
				'separator' => 'none',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dropdown_active',
			array(
				'label' => __( 'Active', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_color_dropdown_item_active',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-nav-menu--dropdown a.elementor-item-active' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_background_color_dropdown_item_active',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-nav-menu--dropdown a.elementor-item-active' => 'background-color: {{VALUE}}',
				),
				'separator' => 'none',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'dropdown_typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'exclude'   => array( 'line_height' ),
				'selector'  => '{{WRAPPER}} .elementor-rael-nav-menu--dropdown .elementor-item, {{WRAPPER}} .elementor-rael-nav-menu--dropdown  .rael-elementor-sub-item',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'dropdown_border',
				'selector'  => '{{WRAPPER}} .elementor-rael-nav-menu--dropdown',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'rael_dropdown_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-rael-nav-menu--dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-rael-nav-menu--dropdown li:first-child a' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-rael-nav-menu--dropdown li:last-child a' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'dropdown_box_shadow',
				'exclude'  => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} .elementor-rael-nav-menu--main .elementor-rael-nav-menu--dropdown, {{WRAPPER}} .elementor-rael-nav-menu__container.elementor-rael-nav-menu--dropdown',
			)
		);

		$this->add_responsive_control(
			'rael_padding_horizontal_dropdown_item',
			array(
				'label'     => __( 'Horizontal Padding', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-nav-menu--dropdown a' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
				),
				'separator' => 'before',

			)
		);

		$this->add_responsive_control(
			'rael_padding_vertical_dropdown_item',
			array(
				'label'     => __( 'Vertical Padding', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-nav-menu--dropdown a' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'rael_heading_dropdown_divider',
			array(
				'label'     => __( 'Divider', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'dropdown_divider',
				'selector' => '{{WRAPPER}} .elementor-rael-nav-menu--dropdown li:not(:last-child)',
				'exclude'  => array( 'width' ),
			)
		);

		$this->add_control(
			'rael_dropdown_divider_width',
			array(
				'label'     => __( 'Border Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-nav-menu--dropdown li:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'dropdown_divider_border!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'rael_dropdown_top_distance',
			array(
				'label'     => __( 'Distance', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => -100,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-nav-menu--main > .elementor-rael-nav-menu > li > .elementor-rael-nav-menu--dropdown, {{WRAPPER}} .elementor-rael-nav-menu__container.elementor-rael-nav-menu--dropdown' => 'margin-top: {{SIZE}}{{UNIT}} !important',
				),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_toggle',
			array(
				'label'     => __( 'Toggle Button', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_toggle!'   => '',
					'rael_dropdown!' => 'none',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_toggle_style' );

		$this->start_controls_tab(
			'tab_toggle_style_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_toggle_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} div.elementor-rael-menu-toggle' => 'color: {{VALUE}}', // Harder selector to override text color control.
				),
			)
		);

		$this->add_control(
			'rael_toggle_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-menu-toggle' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_toggle_style_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_toggle_color_hover',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} div.elementor-rael-menu-toggle:hover' => 'color: {{VALUE}}', // Harder selector to override text color control.
				),
			)
		);

		$this->add_control(
			'rael_toggle_background_color_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-menu-toggle:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_toggle_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 15,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-menu-toggle' => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'rael_toggle_border_width',
			array(
				'label'     => __( 'Border Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-rael-menu-toggle' => 'border-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'rael_toggle_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-rael-menu-toggle' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Renders the widget content.
	 */
	protected function render() {
		$created_menus = $this->get_created_menus();

		if ( ! $created_menus ) {
			return;
		}

		$settings = $this->get_active_settings();

		$args = array(
			'echo'        => false,
			'menu'        => $settings['rael_menu'],
			'menu_class'  => 'elementor-rael-nav-menu',
			'menu_id'     => 'menu-' . $this->get_nav_menu_index() . '-' . $this->get_id(),
			'fallback_cb' => '__return_empty_string',
			'container'   => '',
		);

		if ( 'vertical' == $settings['rael_layout'] ) {
			$args['menu_class'] .= ' sm-vertical';
		}

		add_filter( 'nav_menu_link_attributes', array( $this, 'handle_link_classes' ), 10, 4 );
		add_filter( 'nav_menu_submenu_css_class', array( $this, 'handle_sub_menu_classes' ) );
		add_filter( 'nav_menu_item_id', '__return_empty_string' );

		$menu_html = wp_nav_menu( $args );

		$args['menu_id']    = 'menu-' . $this->get_nav_menu_index() . '-' . $this->get_id();
		$dropdown_menu_html = wp_nav_menu( $args );

		remove_filter( 'nav_menu_link_attributes', array( $this, 'handle_link_classes' ) );
		remove_filter( 'nav_menu_submenu_css_class', array( $this, 'handle_sub_menu_classes' ) );
		remove_filter( 'nav_menu_item_id', '__return_empty_string' );

		if ( empty( $menu_html ) ) {
			return;
		}

		$this->add_render_attribute(
			'rael-menu-toggle',
			array(
				'class'         => 'elementor-rael-menu-toggle',
				'role'          => 'button',
				'tabindex'      => '0',
				'aria-label'    => __( 'Menu Toggle', 'responsive-addons-for-elementor' ),
				'aria-expanded' => 'false',
			)
		);

		if ( Plugin::instance()->editor->is_edit_mode() ) {
			$this->add_render_attribute(
				'rael-menu-toggle',
				array(
					'class' => 'elementor-clickable',
				)
			);
		}

		$this->add_render_attribute( 'main-menu', 'role', 'navigation' );

		if ( 'dropdown' != $settings['rael_layout'] ) :
			$this->add_render_attribute(
				'main-menu',
				'class',
				array(
					'elementor-rael-nav-menu--main',
					'elementor-rael-nav-menu__container',
					'elementor-rael-nav-menu--layout-' . $settings['rael_layout'],
				)
			);

			if ( $settings['rael_pointer'] ) :
				$this->add_render_attribute( 'main-menu', 'class', 'rael--pointer-' . $settings['rael_pointer'] );

				foreach ( $settings as $key => $value ) :
					if ( 0 === strpos( $key, 'rael_animation' ) && $value ) :
						$this->add_render_attribute( 'main-menu', 'class', 'rael--animation-' . $value );

						break;
					endif;
				endforeach;
			endif; ?>
			<nav <?php echo wp_kses_post( $this->get_render_attribute_string( 'main-menu' ) ); ?>><?php echo wp_kses_post( $menu_html ); ?></nav>
			<?php
		endif;
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael-menu-toggle' ) ); ?>>
			<i class="eicon-menu-bar" aria-hidden="true"></i>
			<span class="elementor-screen-only"><?php esc_html_e( 'Menu', 'responsive-addons-for-elementor' ); ?></span>
		</div>
		<nav class="elementor-rael-nav-menu--dropdown elementor-rael-nav-menu__container" role="navigation" aria-hidden="true"><?php echo wp_kses_post( $dropdown_menu_html ); ?></nav>
		<?php
	}
	/**
	 * Handle additional link classes based on menu item attributes.
	 *
	 * @param array    $atts   The HTML attributes of the link.
	 * @param WP_Post  $item   The current menu item.
	 * @param stdClass $args   An object containing wp_nav_menu() arguments.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 *
	 * @return array Modified array of link attributes.
	 */
	public function handle_link_classes( $atts, $item, $args, $depth ) {
		$classes   = $depth ? 'rael-elementor-sub-item' : 'elementor-item';
		$is_anchor = false != strpos( $atts['href'], '#' );

		if ( ! $is_anchor && in_array( 'current-menu-item', $item->classes ) ) {
			$classes .= ' elementor-item-active';
		}

		if ( $is_anchor ) {
			$classes .= ' elementor-item-anchor';
		}

		if ( empty( $atts['class'] ) ) {
			$atts['class'] = $classes;
		} else {
			$atts['class'] .= ' ' . $classes;
		}

		return $atts;
	}
	/**
	 * Handle additional sub-menu classes.
	 *
	 * @param array $classes An array of existing sub-menu classes.
	 *
	 * @return array Modified array of sub-menu classes.
	 */
	public function handle_sub_menu_classes( $classes ) {
		$classes[] = 'elementor-rael-nav-menu--dropdown';

		return $classes;
	}
	/**
	 * Render plain content (placeholder method).
	 *
	 * This method is a placeholder and does not perform any specific rendering.
	 * Implement this method to handle the actual rendering of plain content if needed.
	 */
	public function render_plain_content() {}
}
