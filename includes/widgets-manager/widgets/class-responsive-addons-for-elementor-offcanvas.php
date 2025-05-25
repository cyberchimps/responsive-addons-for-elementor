<?php
/**
 * RAE Offcanvas Widget
 *
 * @package  Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Elementor 'Off Canvas' widget.
 *
 * Elementor widget that displays Offcanvas.
 */
class RAEL_Offcanvas extends Widget_Base {
	/**
	 * Static property to store page templates
	 *
	 * @var array|null
	 */
	private static $page_templates = null;
	/**
	 * Property to store offcanvas navigation menu index
	 *
	 * @var int
	 */
	protected $offcanvas_nav_menu_index = 1;

	/**
	 * Elementor saved section templates list
	 *
	 * @var section_templates
	 */
	private static $section_templates = null;

	/**
	 * Elementor saved widget templates list
	 *
	 * @var widget_templates
	 */
	private static $widget_templates = null;
	/**
	 * Get the name of the widget.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-offcanvas';
	}
	/**
	 * Get the title of the widget.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'OffCanvas', 'responsive-addons-for-elementor' );
	}
	/**
	 * Get the icon for the widget.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-sidebar rael-badge';
	}
	/**
	 * Get the categories for the widget.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}
	/**
	 * Get the keywords for the widget.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'sidebar', 'sidebar menu', 'nvaigation', 'toggle', 'off canvas', 'canvas' );
	}
	/**
	 * Get the index for the offcanvas navigation menu.
	 *
	 * @return int Offcanvas navigation menu index.
	 */
	protected function get_offcanvas_nav_menu_index() {
		return $this->offcanvas_nav_menu_index++;
	}

	/**
	 * Render content type list.
	 *
	 * @since 1.2.2
	 * @return array Array of content type
	 * @access public
	 */
	public function get_content_type() {
		$content_type = array(
			'sidebar'       => __( 'Sidebar', 'responsive-addons-for-elementor' ),
			'content'       => __( 'Custom Content', 'responsive-addons-for-elementor' ),
			'saved_section' => __( 'Saved Section', 'responsive-addons-for-elementor' ),
			'menu'          => __( 'Menu ', 'responsive-addons-for-elementor' ),

		);

		if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			$content_type['saved_modules'] = __( 'Saved Widget', 'responsive-addons-for-elementor' );
		}

		$content_type['saved_page_templates'] = __( 'Saved Page Template', 'responsive-addons-for-elementor' );

		return $content_type;
	}
	/**
	 * Get registered sidebars.
	 *
	 * @return array Registered sidebars.
	 */
	public static function get_registered_sidebars() {
		global $wp_registered_sidebars;
		$options = array();

		if ( ! $wp_registered_sidebars ) {
			$options[''] = __( 'No sidebars were found', 'responsive-addons-for-elementor' );
		} else {
			$options[-1] = __( 'Choose Sidebar', 'responsive-addons-for-elementor' );

			foreach ( $wp_registered_sidebars as $sidebar_id => $sidebar ) {
				$options[ $sidebar_id ] = $sidebar['name'];
			}
		}
		return $options;
	}
	/**
	 * Get created menus.
	 *
	 * @return array Created menus.
	 */
	public function get_created_menus() {
		$menus   = wp_get_nav_menus();
		$options = array();

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}
	/**
	 * Get saved templates.
	 *
	 * @param string $type Type of templates to retrieve (page, section, widget).
	 *
	 * @return array Saved templates.
	 */
	public static function get_saved_templates( $type = 'page' ) {

		$template_type = $type . '_templates';

		$templates_list = array();

		if ( ( null == self::$page_templates && 'page' == $type ) || ( null == self::$section_templates && 'section' == $type ) || ( null == self::$widget_templates && 'widget' == $type ) ) {

			$posts = get_posts(
				array(
					'post_type'      => 'elementor_library',
					'orderby'        => 'title',
					'order'          => 'ASC',
					'posts_per_page' => '-1',
					'tax_query'      => array(
						array(
							'taxonomy' => 'elementor_library_type',
							'field'    => 'slug',
							'terms'    => $type,
						),
					),
				)
			);

			foreach ( $posts as $post ) {

				$templates_list[] = array(
					'id'   => $post->ID,
					'name' => $post->post_title,
				);
			}

			self::${$template_type}[-1] = __( 'Select', 'responsive-addons-for-elementor' );

			if ( count( $templates_list ) ) {
				foreach ( $templates_list as $saved_row ) {

					$content_id                            = $saved_row['id'];
					$content_id                            = apply_filters( 'rael_wpml_object_id', $content_id );
					self::${$template_type}[ $content_id ] = $saved_row['name'];

				}
			} else {
				self::${$template_type}['no_template'] = __( 'It seems that, you have not saved any template yet.', 'responsive-addons-for-elementor' );
			}
		}

		return self::${$template_type};
	}
	/**
	 * Register the controls of the widget.
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_offcanvas',
			array(
				'label' => __( 'Offcanvas Content', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_open_offcanvas_default',
			array(
				'label'        => __( 'Open OffCanvas by Default', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
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
				'default'     => __( 'RAEL Offcanvas', 'responsive-addons-for-elementor' ),
				'placeholder' => __( 'Enter your title', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_offcanvas_content_type',
			array(
				'label'   => __( 'Content Type', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'content',
				'options' => $this->get_content_type(),
			)
		);

		$this->add_control(
			'rael_off_saved_section',
			array(
				'label'     => __( 'Select Section', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_saved_templates( 'section' ),
				'default'   => '-1',
				'condition' => array(
					'rael_offcanvas_content_type' => 'saved_section',
				),
			)
		);

		$this->add_control(
			'rael_off_saved_modules',
			array(
				'label'     => __( 'Select Widget', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_saved_templates( 'widget' ),
				'default'   => '-1',
				'condition' => array(
					'rael_offcanvas_content_type' => 'saved_modules',
				),
			)
		);

		$this->add_control(
			'rael_off_page_templates',
			array(
				'label'     => __( 'Select Page', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_saved_templates( 'page' ),
				'default'   => '-1',
				'condition' => array(
					'rael_offcanvas_content_type' => 'saved_page_templates',
				),
			)
		);

		$registered_sidebars = $this->get_registered_sidebars();

		$this->add_control(
			'rael_off_sidebar',
			array(
				'label'     => __( 'Choose Sidebar', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $registered_sidebars,
				'default'   => '-1',
				'condition' => array(
					'rael_offcanvas_content_type' => 'sidebar',
				),
			)
		);

		$menus = $this->get_created_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'rael_off_menu',
				array(
					'label'        => __( 'Menu', 'responsive-addons-for-elementor' ),
					'type'         => Controls_Manager::SELECT,
					'options'      => $menus,
					'default'      => '-1',
					'save_default' => true,
					// translators: %s represents a link to the Menus screen.
					'description'  => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'responsive-addons-for-elementor' ), admin_url( 'nav-menus.php' ) ),
					'condition'    => array(
						'rael_offcanvas_content_type' => 'menu',
					),
				)
			);
		} else {
			$this->add_control(
				'rael_off_menu',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => '<strong>' . __( 'There are no menus in your site.', 'responsive-addons-for-elementor' ) . '</strong><br>' . sprintf(
						// translators: %s represents a link to the Menus screen.
						__( 'Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'responsive-addons-for-elementor' ),
						admin_url( 'nav-menus.php?action=edit&menu=0' )
					),
					'separator'       => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);
		}

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'menu_typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'  => '.elementor-rael-off-menu li a , .rael-offcanvas-body *:not(.fas):not(.eicon):not(.fab):not(.far):not(.fa)',
				'condition' => array(
					'rael_offcanvas_content_type' => 'menu',
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Title', 'responsive-addons-for-elementor' ),
			)
		);

		$repeater->add_control(
			'description',
			array(
				'name'    => 'description',
				'label'   => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::WYSIWYG,
				'dynamic' => array(
					'active' => true,
				),
				'default' => '',
			)
		);

		$this->add_control(
			'rael_custom_content',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'title'       => __( 'Box 1', 'responsive-addons-for-elementor' ),
						'description' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'responsive-addons-for-elementor' ),
					),
					array(
						'title'       => __( 'Box 2', 'responsive-addons-for-elementor' ),
						'description' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'responsive-addons-for-elementor' ),
					),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'condition'   => array(
					'rael_offcanvas_content_type' => 'content',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_button',
			array(
				'label' => __( 'Toggle Button', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_toggle_text',
			array(
				'label'       => __( 'Button Text', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => __( 'Click Here', 'responsive-addons-for-elementor' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'rael_button_icon_new',
			array(
				'label'            => __( 'Button Icon', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'button_icon',
			)
		);

		$this->add_control(
			'rael_button_icon_position',
			array(
				'label'        => __( 'Icon Position', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'before',
				'options'      => array(
					'before' => __( 'Before', 'responsive-addons-for-elementor' ),
					'after'  => __( 'After', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'rael-offcanvas-icon-',
				'condition'    => array(
					'button_icon!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'rael_button_icon_spacing',
			array(
				'label'      => __( 'Icon Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => '5',
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}}.rael-offcanvas-icon-before .rael-offcanvas-toggle-icon' => 'margin-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.rael-offcanvas-icon-after .rael-offcanvas-toggle-icon' => 'margin-left: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'button_icon!' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => __( 'Settings', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_direction',
			array(
				'label'              => __( 'Direction', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::CHOOSE,
				'default'            => 'left',
				'options'            => array(
					'left'  => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_content_transition',
			array(
				'label'              => __( 'Content Transition', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'slide',
				'options'            => array(
					'slide'       => __( 'Slide', 'responsive-addons-for-elementor' ),
					'reveal'      => __( 'Reveal', 'responsive-addons-for-elementor' ),
					'push'        => __( 'Push', 'responsive-addons-for-elementor' ),
					'slide-along' => __( 'Slide Along', 'responsive-addons-for-elementor' ),
				),
				'frontend_available' => true,
				'separator'          => 'before',
			)
		);

		$this->add_control(
			'rael_close_button',
			array(
				'label'        => __( 'Show Close Button', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'rael_esc_close',
			array(
				'label'        => __( 'Esc to Close', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_body_click_close',
			array(
				'label'        => __( 'Click anywhere to Close', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_offcanvas',
			array(
				'label' => __( 'Offcanvas Bar', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_offcanvas_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 700,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 300,
				),
				'selectors'  => array(
					'.rael-offcanvas-content-{{ID}}' => 'width: {{SIZE}}{{UNIT}};',
					'.rael-offcanvas-content-{{ID}}-open.rael-offcanvas-content-left .rael-offcanvas-container-{{ID}}' => 'transform: translate3d({{SIZE}}{{UNIT}}, 0, 0);',
					'.rael-offcanvas-content-{{ID}}-open.rael-offcanvas-content-right .rael-offcanvas-container-{{ID}}' => 'transform: translate3d(-{{SIZE}}{{UNIT}}, 0, 0);',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'offcanvas_bar_background',
				'label'    => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '.rael-offcanvas-content-{{ID}}',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'offcanvas_bar_border',
				'label'       => __( 'Border', 'responsive-addons-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '.rael-offcanvas-content-{{ID}}',
			)
		);

		$this->add_control(
			'rael_offcanvas_bar_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'.rael-offcanvas-content-{{ID}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_offcanvas_bar_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'.rael-offcanvas-content-{{ID}} .rael-offcanvas-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'offcanvas_bar_box_shadow',
				'selector'  => '.rael-offcanvas-content-{{ID}}',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			array(
				'label'     => __( 'Content', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_offcanvas_content_type' => array( 'sidebar', 'content' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_content_align',
			array(
				'label'     => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'default'   => '',
				'selectors' => array(
					'.rael-offcanvas-content-{{ID}} .rael-offcanvas-body' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_box_heading',
			array(
				'label'     => __( 'Box', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.rael-offcanvas-content-{{ID}} .rael-offcanvas-custom-content, .rael-offcanvas-content-{{ID}} .widget' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'box_border',
				'label'       => __( 'Border', 'responsive-addons-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '.rael-offcanvas-content-{{ID}} .rael-offcanvas-custom-content, .rael-offcanvas-content-{{ID}} .widget',
			)
		);

		$this->add_control(
			'box_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'.rael-offcanvas-content-{{ID}} .rael-offcanvas-custom-content, .rael-offcanvas-content-{{ID}} .widget' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_bottom_spacing',
			array(
				'label'      => __( 'Bottom Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => '20',
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 60,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'.rael-offcanvas-content-{{ID}} .rael-offcanvas-custom-content, .rael-offcanvas-content-{{ID}} .widget' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'.rael-offcanvas-content-{{ID}} .rael-offcanvas-custom-content, .rael-offcanvas-content-{{ID}} .widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_text_heading',
			array(
				'label'     => __( 'Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_content_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.rael-offcanvas-content-{{ID}} .rael-offcanvas-body, .rael-offcanvas-content-{{ID}} .rael-offcanvas-body *:not(.fas):not(.eicon):not(.fab):not(.far):not(.fa)' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'rael_offcanvas_content_type' => array( 'sidebar', 'content' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '.rael-offcanvas-content-{{ID}} .rael-offcanvas-body, .rael-offcanvas-content-{{ID}} .rael-offcanvas-body *:not(.fas):not(.eicon):not(.fab):not(.far):not(.fa)',
			)
		);

		$this->add_control(
			'rael_links_heading',
			array(
				'label'     => __( 'Links', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'tabs_links_style' );

		$this->start_controls_tab(
			'tab_links_normal',
			array(
				'label'     => __( 'Normal', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_offcanvas_content_type' => array( 'sidebar', 'content' ),
				),
			)
		);

		$this->add_control(
			'content_links_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.rael-offcanvas-content-{{ID}} .rael-offcanvas-body a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'links_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '.rael-offcanvas-content-{{ID}} .rael-offcanvas-body a',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_tab_links_hover',
			array(
				'label'     => __( 'Hover', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_offcanvas_content_type' => array( 'sidebar', 'content' ),
				),
			)
		);

		$this->add_control(
			'rael_content_links_color_hover',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.rael-offcanvas-content-{{ID}} .rael-offcanvas-body a:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			array(
				'label' => __( 'Offcanvas Title', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_offcanvas_title_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.rael-offcanvas-content-{{ID}} .rael-offcanvas-title h3' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_offcanvas_title_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '.rael-offcanvas-content-{{ID}} .rael-offcanvas-title h3',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			array(
				'label'     => __( 'Icon', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'trigger'       => 'on-click',
					'trigger_type!' => 'button',
				),
			)
		);

		$this->add_control(
			'rael_icon_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-trigger-icon' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'trigger'      => 'on-click',
					'trigger_type' => 'icon',
				),
			)
		);

		$this->add_responsive_control(
			'rael_icon_size',
			array(
				'label'      => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => '28',
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 10,
						'max'  => 80,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-trigger-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'trigger'      => 'on-click',
					'trigger_type' => 'icon',
				),
			)
		);

		$this->add_responsive_control(
			'rael_icon_image_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 10,
						'max'  => 1200,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-trigger-image' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'trigger'      => 'on-click',
					'trigger_type' => 'image',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			array(
				'label' => __( 'Toggle Button', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_button_align',
			array(
				'label'     => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
				'options'   => array(
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
				'selectors' => array(
					'{{WRAPPER}} .rael-offcanvas-toggle-wrap' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_button_size',
			array(
				'label'   => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'md',
				'options' => array(
					'xs' => __( 'Extra Small', 'responsive-addons-for-elementor' ),
					'sm' => __( 'Small', 'responsive-addons-for-elementor' ),
					'md' => __( 'Medium', 'responsive-addons-for-elementor' ),
					'lg' => __( 'Large', 'responsive-addons-for-elementor' ),
					'xl' => __( 'Extra Large', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_toggle_button_icon_size',
			array(
				'label'      => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => '28',
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 10,
						'max'  => 80,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-offcanvas-toggle-wrap .rael-offcanvas-toggle-icon' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .rael-offcanvas-toggle-wrap .rael-offcanvas-toggle-icon.rael-offcanvas-toggle-svg-icon' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_toggle_button_icon_space',
			array(
				'label'      => __( 'Icon Space', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => '10',
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'max'  => 50,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-offcanvas-toggle-wrap .rael-offcanvas-toggle-icon' => 'margin-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .rael-offcanvas-toggle-wrap .rael-offcanvas-toggle-icon.rael-offcanvas-toggle-svg-icon' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_button_background_color_normal',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-offcanvas-toggle' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_button_text_color_normal',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-offcanvas-toggle' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border_normal',
				'label'       => __( 'Border', 'responsive-addons-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .rael-offcanvas-toggle',
			)
		);

		$this->add_control(
			'rael_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-offcanvas-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => __( 'Typography', 'responsive-addons-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .rael-offcanvas-toggle',
			)
		);

		$this->add_responsive_control(
			'rael_button_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-offcanvas-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .rael-offcanvas-toggle',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_tab_button_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_button_background_color_hover',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-offcanvas-toggle:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_button_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-offcanvas-toggle:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_button_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-offcanvas-toggle:hover' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_button_animation',
			array(
				'label' => __( 'Animation', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow_hover',
				'selector' => '{{WRAPPER}} .rael-offcanvas-toggle:hover',
			)
		);

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_close_button',
			array(
				'label'     => __( 'Close Button', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_close_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_close_button_icon_new',
			array(
				'label'            => __( 'Button Icon', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'close_button_icon',
				'default'          => array(
					'value'   => 'fas fa-times',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'rael_close_button_text_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.rael-close-offcanvas-{{ID}}' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'rael_close_button_size',
			array(
				'label'      => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => '28',
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 10,
						'max'  => 80,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'.rael-offcanvas-content-{{ID}} .rael-close-offcanvas-{{ID}}' => 'font-size: {{SIZE}}{{UNIT}}',
					'.rael-offcanvas-content-{{ID}} .rael-close-offcanvas-{{ID}} .rael-close-offcanvas-svg-icon' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_close_button' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_overlay',
			array(
				'label' => __( 'Overlay', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_overlay_background_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.rael-offcanvas-content-{{ID}}-open .rael-offcanvas-container:after' => 'background: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_overlay_opacity',
			array(
				'label'     => __( 'Opacity', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'.rael-offcanvas-content-{{ID}}-open .rael-offcanvas-container:after' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Render the close button for the offcanvas.
	 */
	protected function render_close_button() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'close-button',
			'class',
			array(
				'rael-close-offcanvas',
				'rael-close-offcanvas-' . esc_attr( $this->get_id() ),
			)
		);

		$this->add_render_attribute( 'close-button', 'role', 'button' );
		?>
		<div class="rael-offcanvas-header">
			<div class="rael-offcanvas-title">
				<h3><?php echo esc_html( $settings['rael_title'] ); ?></h3>
			</div>
			<?php if ( 'yes' == $settings['rael_close_button'] ) : ?>
				<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'close-button' ) ); ?>>
					<?php if ( isset( $settings['__fa4_migrated']['rael_close_button_icon_new'] ) || empty( $settings['close_button_icon'] ) ) { ?>
						<?php if ( isset( $settings['rael_close_button_icon_new']['value']['url'] ) ) : ?>
							<img class="rael-offcanvas-close-svg-icon" src="<?php echo esc_url( $settings['rael_close_button_icon_new']['value']['url'] ); ?>" alt="<?php echo esc_attr( get_post_meta( $settings['rael_close_button_icon_new']['value']['id'], '_wp_attachment_image_alt', true ) ); ?>">
						<?php else : ?>
							<span class="<?php echo esc_attr( $settings['rael_close_button_icon_new']['value'] ); ?>"></span>
						<?php endif; ?>
					<?php } else { ?>
						<span class="<?php echo esc_attr( $settings['close_button_icon'] ); ?>"></span>
					<?php } ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
	/**
	 * Render the sidebar content for the offcanvas.
	 */
	protected function render_sidebar() {
		$settings = $this->get_settings_for_display();

		$sidebar = $settings['rael_off_sidebar'];

		if ( empty( $sidebar ) ) :
			return;
		endif;

		dynamic_sidebar( $sidebar );
	}
	/**
	 * Render the navigation menu for the offcanvas.
	 */
	protected function render_nav_menu() {
		$created_menus = $this->get_created_menus();

		if ( ! $created_menus ) {
			return;
		}

		$settings = $this->get_active_settings();

		$args = array(
			'echo'        => false,
			'menu'        => $settings['rael_off_menu'],
			'menu_class'  => 'elementor-rael-off-menu',
			'menu_id'     => 'menu-' . $this->get_offcanvas_nav_menu_index() . '-' . $this->get_id(),
			'fallback_cb' => '__return_empty_string',
			'container'   => '',
		);

		$menu_html = wp_nav_menu( $args );
		if ( empty( $menu_html ) ) {
			return;
		}

		?>
		<nav <?php echo wp_kses_post( $this->get_render_attribute_string( 'main-menu' ) ); ?>><?php echo wp_kses_post( $menu_html ); ?></nav>
		<?php
	}
	/**
	 * Render custom content for the offcanvas.
	 */
	protected function render_custom_content() {
		$settings = $this->get_settings_for_display();

		if ( count( $settings['rael_custom_content'] ) ) {
			foreach ( $settings['rael_custom_content'] as $key => $item ) {
				?>
				<div class="rael-offcanvas-custom-content">
					<h3 class="rael-offcanvas-custom-content-title"><?php echo esc_html( $item['title'] ); ?></h3>
					<div class="rael-offcanvas-custom-content-description">
						<?php echo wp_kses_post( $item['description'] ); ?>
					</div>
				</div>
				<?php
			}
		}
	}
	/**
	 * Renders the widget content.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$mutli_settings = array(
			'content_id'       => esc_attr( $this->get_id() ),
			'esc_close'        => esc_attr( $settings['rael_esc_close'] ),
			'direction'        => esc_attr( $settings['rael_direction'] ),
			'transition'       => esc_attr( $settings['rael_content_transition'] ),
			'open_offcanvas'   => esc_attr( $settings['rael_open_offcanvas_default'] ),
			'body_click_close' => esc_attr( $settings['rael_body_click_close'] ),
		);

		$this->add_render_attribute(
			'content_wrap',
			array(
				'class'         => 'rael-content-wrap-offcanvas',
				'data-settings' => htmlspecialchars( json_encode( $mutli_settings ) ), // phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
			)
		);

		$this->add_render_attribute(
			'toggle-button',
			array(
				'class' => array(
					'rael-offcanvas-toggle',
					'rael-offcanvas-toogle-' . esc_attr( $this->get_id() ),
					'elementor-button',
					'elementor-size-' . esc_attr( $settings['rael_button_size'] ),
				),
			)
		);

		if ( $settings['rael_button_animation'] ) {
			$this->add_render_attribute( 'toggle-button', 'class', 'elementor-animation-' . esc_attr( $settings['rael_button_animation'] ) );
		}

		$this->add_render_attribute(
			'content_sidebar',
			array(
				'class' => array(
					'rael-offcanvas-content',
					'rael-offcanvas-content-' . esc_attr( $this->get_id() ),
					'rael-offcanvas-' . $mutli_settings['transition'],
					'elementor-element-' . $this->get_id(),
					'rael-offcanvas-content-' . $mutli_settings['direction'],
				),
			)
		);
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'content_wrap' ) ); ?> >
		<?php if ( '' != $settings['rael_toggle_text'] || '' == $settings['rael_toggle_text'] ) : ?>
			<div class="rael-offcanvas-toggle-wrap">
				<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'toggle-button' ) ); ?> >
					<?php if ( isset( $settings['__fa4_migrated']['rael_button_icon_new'] ) || empty( $settings['button_icon'] ) ) { ?>
						<?php if ( isset( $settings['rael_button_icon_new']['value']['url'] ) ) : ?>
							<img class="rael-offcanvas-toggle-icon rael-offcanvas-toggle-svg-icon"
								src="<?php echo esc_url( $settings['rael_button_icon_new']['value']['url'] ); ?>"
								alt="<?php echo esc_attr( get_post_meta( $settings['rael_button_icon_new']['value']['id'], '_wp_attachment_image_alt', true ) ); ?>">
						<?php else : ?>
							<span class="rael-offcanvas-toggle-icon <?php echo esc_attr( $settings['rael_button_icon_new']['value'] ); ?>"></span>
						<?php endif; ?>
					<?php } else { ?>
						<span class="rael-offcanvas-toggle-icon <?php echo esc_attr( $settings['button_icon'] ); ?>"></span>
					<?php } ?>
					<span class="rael-toggle-text">
						<?php echo esc_html( $settings['rael_toggle_text'] ); ?>
					</span>
				</div>
			</div>
		<?php endif; ?>

		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'content_sidebar' ) ); ?>>
			<?php $this->render_close_button(); ?>
				<div class="rael-offcanvas-body">
					<?php
					if ( 'sidebar' == $settings['rael_offcanvas_content_type'] ) :
						$this->render_sidebar();
						elseif ( 'content' == $settings['rael_offcanvas_content_type'] ) :
							$this->render_custom_content();
						elseif ( 'menu' == $settings['rael_offcanvas_content_type'] ) :
							$this->render_nav_menu();
						elseif ( 'saved_section' == $settings['rael_offcanvas_content_type'] && ! empty( $settings['rael_off_saved_section'] ) ) :
							echo esc_html( \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['rael_off_saved_section'] ) );
						elseif ( 'saved_page_templates' == $settings['rael_offcanvas_content_type'] && ! empty( $settings['rael_off_page_templates'] ) ) :
							echo esc_html( \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['rael_off_page_templates'] ) );
						elseif ( 'saved_modules' == $settings['rael_offcanvas_content_type'] && ! empty( $settings['rael_off_saved_modules'] ) ) :
							echo esc_html( \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['rael_off_saved_modules'] ) );
						endif;
						?>
		</div>

		<?php
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/offcanvas';
	}
}
