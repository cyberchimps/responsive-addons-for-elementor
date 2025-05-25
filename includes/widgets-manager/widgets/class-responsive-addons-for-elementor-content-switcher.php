<?php
/**
 * Content Switcher Widget
 *
 * @since   1.0.0
 * @package responsive-addons-for-elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor 'Content Switcher' widget class.
 *
 * @since 1.0.0
 */
class Responsive_Addons_For_Elementor_Content_Switcher extends Widget_Base {

	/**
	 * Elementor Saved page templates list
	 *
	 * @var page_templates
	 */
	private static $page_templates = null;

	/**
	 * Elementor saved section templates list
	 *
	 * @var section_templates
	 */
	private static $section_templates = null;

	/**
	 * Retrieve the widget name.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-content-switcher';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Content Switcher', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve slider widget icon.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'reaicon-content-switcher rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the timeline post widget belongs to.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get content type.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array Array of content type.
	 */
	public function get_content_type() {
		$content_type = array(
			'content'              => __( 'Content', 'responsive-addons-for-elementor' ),
			'saved_rows'           => __( 'Saved Section', 'responsive-addons-for-elementor' ),
			'saved_page_templates' => __( 'Saved Page', 'responsive-addons-for-elementor' ),
		);

		return $content_type;
	}

	/**
	 *  Get Saved templates
	 *
	 * @static
	 * @param  string $type Type.
	 * @since  1.0.0
	 *
	 * @return array of templates
	 */
	public static function get_saved_data( $type = 'page' ) {

		$template_type = $type . '_templates';

		$templates_list = array();

		if ( ( null === self::$page_templates && 'page' === $type ) || ( null === self::$section_templates && 'section' === $type ) ) {

			$posts = get_posts(
				array(
					'post_type'              => 'elementor_library',
					'orderby'                => 'title',
					'order'                  => 'ASC',
					'posts_per_page'         => '-1',
					'elementor_library_type' => $type,
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
					$content_id                            = apply_filters( 'wpml_object_id', $content_id );
					self::${$template_type}[ $content_id ] = $saved_row['name'];

				}
			} else {
				self::${$template_type}['no_template'] = __( 'It seems that, you have not saved any template yet.', 'responsive-addons-for-elementor' );
			}
		}

		return self::${$template_type};
	}

	/**
	 * Get switcher type.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array Array of content type
	 */
	public function get_switch_type() {

		$switch_type = array(
			'round_1'   => __( 'Round 1', 'responsive-addons-for-elementor' ),
			'round_2'   => __( 'Round 2', 'responsive-addons-for-elementor' ),
			'rectangle' => __( 'Rectangle', 'responsive-addons-for-elementor' ),
			'label_box' => __( 'Label Box', 'responsive-addons-for-elementor' ),
		);

		return $switch_type;
	}

	/**
	 * Render button widget classes names.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array  $settings The settings array.
	 * @param int    $node_id  The node id.
	 * @param string $section  Section one or two.
	 *
	 * @return string Concatenated string of classes
	 */
	public function get_modal_content( $settings, $node_id, $section ) {

		$normal_content_1 = $this->get_settings_for_display( 'rael_ct_content_1_description' );
		$normal_content_2 = $this->get_settings_for_display( 'rael_ct_content_2_description' );
		$content_type     = $settings[ $section ];
		$output           = '';
		if ( 'rael_ct_content_1_content_type' === $section ) {
			switch ( $content_type ) {
				case 'content':
					global $wp_embed;
					$output = '<div>' . wpautop( $wp_embed->autoembed( $normal_content_1 ) ) . '</div>';
					break;
				case 'saved_rows':
					$output = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['rael_ct_content_1_saved_section'] );
					break;
				case 'saved_page_templates':
						$output = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['rael_ct_content_1_saved_page'] );
					break;
				default:
					break;
			}
		} else {
			switch ( $content_type ) {
				case 'content':
					global $wp_embed;
					$output = '<div>' . wpautop( $wp_embed->autoembed( $normal_content_2 ) ) . '</div>';
					break;
				case 'saved_rows':
					$output = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['rael_ct_content_2_saved_section'] );
					break;
				case 'saved_page_templates':
					$output = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['rael_ct_content_2_saved_page'] );
					break;
				default:
					break;
			}
		}

		return $output;
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function register_controls() {  // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		// Content TAB.
		$this->register_left_side_content_controls();
		$this->register_right_side_content_controls();

		// Style TAB.
		$this->register_switch_style_controls();
		$this->register_headings_style_controls();
		$this->register_content_style_controls();
		$this->register_spacing_style_controls();
	}

	/**
	 * Registers left side content's controls.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_left_side_content_controls() {
		$this->start_controls_section(
			'rael_ct_content_1_section',
			array(
				'label' => __( 'Content 1', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_ct_content_1_heading',
			array(
				'label'   => __( 'Heading', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Heading 1', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_ct_content_1_content_type',
			array(
				'label'   => __( 'Section', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'content',
				'options' => $this->get_content_type(),
			)
		);

		$this->add_control(
			'rael_ct_content_1_description',
			array(
				'label'      => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => __( 'This is your first content. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.​ Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-addons-for-elementor' ),
				'rows'       => 10,
				'show_label' => false,
				'dynamic'    => array(
					'active' => true,
				),
				'condition'  => array(
					'rael_ct_content_1_content_type' => 'content',
				),
			)
		);

		$this->add_control(
			'rael_ct_content_1_saved_section',
			array(
				'label'     => __( 'Select Section', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => self::get_saved_data( 'section' ),
				'default'   => '-1',
				'condition' => array(
					'rael_ct_content_1_content_type' => 'saved_rows',
				),
			)
		);

		$this->add_control(
			'rael_ct_content_1_saved_page',
			array(
				'label'     => __( 'Select Page', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => self::get_saved_data( 'page' ),
				'default'   => '-1',
				'condition' => array(
					'rael_ct_content_1_content_type' => 'saved_page_templates',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Registers right side content's controls.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_right_side_content_controls() {
		$this->start_controls_section(
			'rael_ct_content_2_section',
			array(
				'label' => __( 'Content 2', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_ct_content_2_heading',
			array(
				'label'   => __( 'Heading', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'Heading 2', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_ct_content_2_content_type',
			array(
				'label'   => __( 'Section', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'content',
				'options' => $this->get_content_type(),
			)
		);

		$this->add_control(
			'rael_ct_content_2_description',
			array(
				'label'      => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => __( 'This is your second content. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.​ Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-addons-for-elementor' ),
				'rows'       => 10,
				'show_label' => false,
				'dynamic'    => array(
					'active' => true,
				),
				'condition'  => array(
					'rael_ct_content_2_content_type' => 'content',
				),
			)
		);

		$this->add_control(
			'rael_ct_content_2_saved_section',
			array(
				'label'     => __( 'Select Section', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => self::get_saved_data( 'section' ),
				'default'   => '-1',
				'condition' => array(
					'rael_ct_content_2_content_type' => 'saved_rows',
				),
			)
		);

		$this->add_control(
			'rael_ct_content_2_saved_page',
			array(
				'label'     => __( 'Select Page', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => self::get_saved_data( 'page' ),
				'default'   => '-1',
				'condition' => array(
					'rael_ct_content_2_content_type' => 'saved_page_templates',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Switcher style controls.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_switch_style_controls() {
		$this->start_controls_section(
			'rael_ct_switcher_style_section',
			array(
				'label' => __( 'Switcher', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_ct_default_content',
			array(
				'label'        => __( 'Default Display', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'off',
				'return_value' => 'on',
				'options'      => array(
					'off' => 'Content 1',
					'on'  => 'Content 2',
				),
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'rael_ct_switcher_type',
			array(
				'label'   => __( 'Switch Style', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'round_1',
				'options' => $this->get_switch_type(),
			)
		);

		$this->add_control(
			'rael_ct_switcher_color_off',
			array(
				'label'     => __( 'Color 1', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#61CE70',
				'global'    => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-ct__slider' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-ct__toggle input[type="checkbox"] + label:before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-ct__toggle input[type="checkbox"] + label:after' => 'border: 0.3em solid {{VALUE}};',
					'{{WRAPPER}} .rael-ct__label-box-active .rael-ct__label-box-switcher' => 'background: {{VALUE}};',

				),
			)
		);

		$this->add_control(
			'rael_ct_switcher_color_on',
			array(
				'label'     => __( 'Color 2', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7A7A7A',
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-ct__switcher:checked + .rael-ct__slider' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-ct__switcher:focus + .rael-ct__slider'     => '-webkit-box-shadow: 0 0 1px {{VALUE}};box-shadow: 0 0 1px {{VALUE}};',
					'{{WRAPPER}} .rael-ct__toggle input[type="checkbox"]:checked + label:before'     => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-ct__toggle input[type="checkbox"]:checked + label:after'     => '-webkit-transform: translateX(2.5em);-ms-transform: translateX(2.5em);transform: translateX(2.5em);border: 0.3em solid {{VALUE}};',
					'{{WRAPPER}} .rael-ct__label-box-inactive .rael-ct__label-box-switcher' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_ct_switcher_controller_color',
			array(
				'label'     => __( 'Controller Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-ct__slider:before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-ct__toggle input[type="checkbox"] + label:after' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} span.rael-ct__label-box-switcher' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_ct_switch_size',
			array(
				'label'     => __( 'Switch Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 15,
				),
				'range'     => array(
					'px' => array(
						'min'  => 10,
						'max'  => 35,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-ct__switcher-button' => 'font-size: {{SIZE}}px;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Headings style controls.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_headings_style_controls() {
		$this->start_controls_section(
			'rael_ct_headings_section',
			array(
				'label' => __( 'Headings', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_ct_heading_1_style',
			array(
				'label'     => __( 'Heading 1', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_ct_heading_1_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-ct__heading-1' => 'color: {{VALUE}};',
				),
				'separator' => 'none',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_ct_heading_1_typography',
				'selector' => '{{WRAPPER}} .rael-ct__heading-1',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			)
		);

		$this->add_control(
			'rael_ct_heading_2_style',
			array(
				'label'     => __( 'Heading 2', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_ct_heading_2_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-ct__heading-2' => 'color: {{VALUE}};',
				),
				'separator' => 'none',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_ct_heading_2_typography',
				'selector' => '{{WRAPPER}} .rael-ct__heading-2',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			)
		);

		$this->add_control(
			'rael_ct_headings_tag',
			array(
				'label'     => __( 'HTML Tag', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				),
				'default'   => 'h5',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'rael_ct_headings_alignment',
			array(
				'label'     => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-ct__toggle-wrapper' => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} .rael-ct-desktop-stack--yes .rael-ct__toggle-wrapper' => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_ct_heading_layout',
			array(
				'label'        => __( 'Layout', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Stack', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Inline', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'rael_ct_heading_stack_on',
			array(
				'label'        => __( 'Stack on', 'responsive-addons-for-elementor' ),
				'description'  => __( 'Choose on what breakpoint the heading will stack.', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'mobile',
				'options'      => array(
					'none'   => __( 'None', 'responsive-addons-for-elementor' ),
					'tablet' => __( 'Tablet (1023px >)', 'responsive-addons-for-elementor' ),
					'mobile' => __( 'Mobile (767px >)', 'responsive-addons-for-elementor' ),
				),
				'condition'    => array(
					'rael_ct_heading_layout!' => 'yes',
				),
				'prefix_class' => 'rael-ct-stack--',
			)
		);

		$this->add_control(
			'rael_ct_advance_settings',
			array(
				'label'     => __( 'Advanced', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'OFF', 'responsive-addons-for-elementor' ),
				'label_on'  => __( 'ON', 'responsive-addons-for-elementor' ),
				'default'   => 'no',
				'return'    => 'yes',
			)
		);

		$this->add_control(
			'rael_ct_headings_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-ct__toggle-wrapper' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_ct_advance_settings' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'rael_ct_headings_border',
				'label'     => __( 'Border', 'responsive-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .rael-ct__toggle-wrapper',
				'condition' => array(
					'rael_ct_advance_settings' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_ct_headings_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-ct__toggle-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_ct_advance_settings' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_ct_headings_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-ct__toggle-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_ct_advance_settings' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Content style controls.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_content_style_controls() {
		$this->start_controls_section(
			'rael_ct_content_style_section',
			array(
				'label' => __( 'Content', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Content 1.
		$this->add_control(
			'rael_ct_content_1_style',
			array(
				'label'     => __( 'Content 1', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'rael_ct_content_1_content_type' => 'content',
				),
			)
		);

		$this->add_control(
			'rael_ct_content_1_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'condition' => array(
					'rael_ct_content_1_content_type' => 'content',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-ct__content-1.rael-ct__section-1' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_ct_content_1_typography',
				'selector'  => '{{WRAPPER}} .rael-ct__content-1.rael-ct__section-1',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'condition' => array(
					'rael_ct_content_1_content_type' => 'content',
				),
				'separator' => 'after',
			)
		);

		// Content 2.
		$this->add_control(
			'rael_ct_content_2_style',
			array(
				'label'     => __( 'Content 2', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'rael_ct_content_2_content_type' => 'content',
				),
			)
		);

		$this->add_control(
			'rael_ct_content_2_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'condition' => array(
					'rael_ct_content_2_content_type' => 'content',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-ct__content-2.rael-ct__section-2' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_ct_content_2_typography',
				'selector'  => '{{WRAPPER}} .rael-ct__content-2.rael-ct__section-2',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'condition' => array(
					'rael_ct_content_2_content_type' => 'content',
				),
				'separator' => 'after',
			)
		);

		// Content Advance Settings.
		$this->add_control(
			'rael_ct_content_advance_settings',
			array(
				'label'     => __( 'Advanced', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'OFF', 'responsive-addons-for-elementor' ),
				'label_on'  => __( 'ON', 'responsive-addons-for-elementor' ),
				'default'   => 'no',
				'return'    => 'yes',
			)
		);

		$this->add_control(
			'rael_ct_content_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-ct__toggle-sections' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_ct_content_advance_settings' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'rael_ct_content_border',
				'label'     => __( 'Border', 'responsive-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .rael-ct__toggle-sections',
				'condition' => array(
					'rael_ct_content_advance_settings' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_ct_content_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-ct__toggle-sections' => 'overflow: hidden;border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_ct_content_advance_settings' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_ct_content_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-ct__toggle-sections' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_ct_content_advance_settings' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Spacing style controls.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register_spacing_style_controls() {
		$this->start_controls_section(
			'rael_ct_switcher_spacing_section',
			array(
				'label' => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_ct_button_headings_spacing',
			array(
				'label'     => __( 'Button & Headings', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'%' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'   => array(
					'size' => 5,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-ct-desktop-stack--no .rael-ct__section-heading-1'         => 'margin-right: {{SIZE}}%;',
					'{{WRAPPER}} .rael-ct-desktop-stack--no .rael-ct__section-heading-2'         => 'margin-left: {{SIZE}}%;',

					'.rtl {{WRAPPER}} .rael-ct-desktop-stack--no .rael-ct__section-heading-1'         => 'margin-left: {{SIZE}}%; margin-right: 0%;',
					'.rtl {{WRAPPER}} .rael-ct-desktop-stack--no .rael-ct__section-heading-2'         => 'margin-right: {{SIZE}}%; margin-left: 0%',

					'{{WRAPPER}} .rael-ct-desktop-stack--yes .rael-ct__section-heading-1'         => 'margin-bottom: {{SIZE}}%;',
					'{{WRAPPER}} .rael-ct-desktop-stack--yes .rael-ct__section-heading-2'         => 'margin-top: {{SIZE}}%;',

					'(tablet){{WRAPPER}}.rael-ct-stack--tablet .rael-ct-desktop-stack--no .rael-ct__section-heading-1'         => 'margin-bottom: {{SIZE}}%;margin-right: 0px;',
					'(tablet){{WRAPPER}}.rael-ct-stack--tablet .rael-ct-desktop-stack--no .rael-ct__section-heading-2'         => 'margin-top: {{SIZE}}%;margin-left: 0px;',

					'(tablet){{WRAPPER}}.rael-ct-stack--tablet .rael-ct-desktop-stack--no .rael-ct__toggle-wrapper'         => 'flex-direction: column;',

					'(mobile){{WRAPPER}}.rael-ct-stack--mobile .rael-ct-desktop-stack--no .rael-ct__section-heading-1'         => 'margin-bottom: {{SIZE}}%;margin-right: 0px;',
					'(mobile){{WRAPPER}}.rael-ct-stack--mobile .rael-ct-desktop-stack--no .rael-ct__section-heading-2'         => 'margin-top: {{SIZE}}%;margin-left: 0px;',

					'(mobile){{WRAPPER}}.rael-ct-stack--mobile .rael-ct-desktop-stack--no .rael-ct__toggle-wrapper'         => 'flex-direction: column;',
				),
			)
		);

		$this->add_responsive_control(
			'rael_ct_headings_content_spacing',
			array(
				'label'     => __( 'Content & Headings', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-ct__toggle-wrapper' => 'margin-bottom: {{SIZE}}px;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();
		$node_id  = $this->get_id();
		ob_start();
		$this->render_template( $settings, $node_id );
		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Widget template for frontend.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings The settings array.
	 * @param int   $node_id  The node id.
	 *
	 * @access public
	 */
	public function render_template( $settings, $node_id ) {
		if ( 'yes' === $settings['rael_ct_heading_layout'] ) {
			$this->add_render_attribute(
				'rael_ct_wrapper',
				'class',
				array(
					'rael-ct-desktop-stack--yes',
					'rael-ct-wrapper',
				)
			);
		} else {
			$this->add_render_attribute(
				'rael_ct_wrapper',
				'class',
				array(
					'rael-ct-desktop-stack--no',
					'rael-ct-wrapper',
				)
			);
		}

		// Content Toggle wrapper.
		$this->add_render_attribute( 'rael_ct_toggle', 'class', 'rael-ct__toggle-wrapper' );

		// Toggle section.
		$this->add_render_attribute( 'rael_ct_section_heading_1', 'class', 'rael-ct__section-heading-1' );
		$this->add_render_attribute( 'rael_ct_section_heading_2', 'class', 'rael-ct__section-heading-2' );

		// Content-1 Heading.
		$this->add_render_attribute( 'rael_ct_content_1_heading', 'class', 'rael-ct__heading-1' );
		$this->add_inline_editing_attributes( 'rael_ct_content_1_heading', 'basic' );

		// Content-2 Heading.
		$this->add_render_attribute( 'rael_ct_content_2_heading', 'class', 'rael-ct__heading-2' );
		$this->add_inline_editing_attributes( 'rael_ct_content_2_heading', 'basic' );

		// Toggler Button.
		$this->add_render_attribute( 'rael_ct_btn', 'class', 'rael-ct__switcher-button' );
		$this->add_render_attribute( 'rael_ct_btn', 'data-switch-type', $settings['rael_ct_switcher_type'] );

		// Toggle Sections.
		$this->add_render_attribute( 'rael_ct_toggle_sections', 'class', 'rael-ct__toggle-sections' );
		if ( 'content' === $settings['rael_ct_content_1_content_type'] ) {
			$this->add_render_attribute( 'rael_ct_content_1', 'class', 'rael-ct__content-1' );
		}
		if ( 'content' === $settings['rael_ct_content_2_content_type'] ) {
			$this->add_render_attribute( 'rael_ct_content_2', 'class', 'rael-ct__content-2' );
		}
		if ( 'on' === $settings['rael_ct_default_content'] ) {
			$this->add_render_attribute( 'rael_ct_content_1', 'style', 'display: none;' );
		} else {
			$this->add_render_attribute( 'rael_ct_content_2', 'style', 'display: none;' );
		}

		$this->add_render_attribute( 'rael_ct_content_1', 'class', 'rael-ct__section-1' );
		$this->add_render_attribute( 'rael_ct_content_2', 'class', 'rael-ct__section-2' );
		// Toggle Switch - Round 1.
		$this->add_render_attribute( 'rael_ct_switcher_label', 'class', 'rael-ct__switcher-label' );
		$this->add_render_attribute(
			'rael_ct_switcher_round_1',
			'class',
			array(
				'rael-ct__switcher',
				'rael-ct__switcher--round-1',
				'elementor-clickable',
			)
		);
		$this->add_render_attribute( 'rael_ct_switcher_round_1', 'type', 'checkbox' );
		$this->add_render_attribute(
			'rael_ct_span_round_1',
			'class',
			array(
				'rael-ct__slider',
				'rael-ct-round',
				'elementor-clickable',
			)
		);
		// Toggle Switch - Round 2.
		$this->add_render_attribute( 'rael_ct_div_round_2', 'class', 'rael-ct__toggle' );
		$this->add_render_attribute(
			'rael_ct_input_round_2',
			'class',
			array(
				'rael-ct__switcher--round-2',
				'elementor-clickable',
			)
		);
		$this->add_render_attribute( 'rael_ct_input_round_2', 'type', 'checkbox' );
		$this->add_render_attribute( 'rael_ct_input_round_2', 'name', 'group1' );
		$this->add_render_attribute( 'rael_ct_input_round_2', 'id', 'toggle_' . $node_id );
		$this->add_render_attribute( 'rael_ct_label_round_2', 'for', 'toggle_' . $node_id );
		$this->add_render_attribute( 'rael_ct_label_round_2', 'class', 'elementor-clickable' );
		// Toggle Switch - Rectangle.
		$this->add_render_attribute( 'rael_ct_label_rect', 'class', 'rael-ct__switcher-label' );
		$this->add_render_attribute(
			'rael_ct_input_rect',
			'class',
			array(
				'rael-ct__switcher',
				'rael-ct__switch-rectangle',
				'elementor-clickable',
			)
		);
		$this->add_render_attribute( 'rael_ct_input_rect', 'type', 'checkbox' );
		$this->add_render_attribute( 'rael_ct_span_rect', 'class', 'rael-ct__slider' );
		$this->add_render_attribute( 'rael_ct_span_rect', 'class', 'elementor-clickable' );
		// Toggle Switch - Label Box.
		$this->add_render_attribute(
			'rael_ct_div_label_box',
			'class',
			array(
				'rael-ct__label-box',
				'elementor-clickable',
			)
		);
		$this->add_render_attribute( 'rael_ct_input_label_box', 'type', 'checkbox' );
		$this->add_render_attribute( 'rael_ct_input_label_box', 'name', 'rael-ct__label-box' );
		$this->add_render_attribute(
			'rael_ct_input_label_box',
			'class',
			array(
				'rael-ct__label-box-checkbox',
				'rael-ct__switch-label-box',
				'elementor-clickable',
			)
		);
		$this->add_render_attribute( 'rael_ct_input_label_box', 'id', 'myonoffswitch_' . $node_id );
		$this->add_render_attribute( 'rael_ct_label_label_box', 'class', 'rael-ct__label-box-label' );
		$this->add_render_attribute( 'rael_ct_label_label_box', 'for', 'myonoffswitch_' . $node_id );
		$this->add_render_attribute( 'rael_ct_span_inner_label_box', 'class', 'rael-ct__label-box-inner' );
		$this->add_render_attribute( 'rael_ct_span_inactive_label_box', 'class', 'rael-ct__label-box-inactive' );
		$this->add_render_attribute( 'rael_ct_span_label_box', 'class', 'rael-ct__label-box-switcher' );
		$this->add_render_attribute( 'rael_ct_span_active_label_box', 'class', 'rael-ct__label-box-active' );
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_ct_wrapper' ) ); ?>>
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_ct_toggle' ) ); ?>>
				<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_ct_section_heading_1' ) ); ?>>
					<<?php echo esc_attr( Helper::validate_html_tags( $settings['rael_ct_headings_tag'] ) ); ?> <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_ct_content_1_heading' ) ); ?> ><?php echo wp_kses_post( $this->get_settings_for_display( 'rael_ct_content_1_heading' ) ); ?></<?php echo esc_attr( Helper::validate_html_tags( $settings['rael_ct_headings_tag'] ) ); ?>>
				</div>
				<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_ct_btn' ) ); ?>>
		<?php $switch_html = ''; ?>
		<?php $is_checked = ( 'on' === $settings['rael_ct_default_content'] ) ? 'checked' : ''; ?>
		<?php
		switch ( $settings['rael_ct_switcher_type'] ) {
			case 'round_1':
				$switch_html = '<label ' . $this->get_render_attribute_string( 'rael_ct_switcher_label' ) . '><input ' . $this->get_render_attribute_string( 'rael_ct_switcher_round_1' ) . ' ' . $is_checked . '><span ' . $this->get_render_attribute_string( 'rael_ct_span_round_1' ) . '></span></label>';
				break;

			case 'round_2':
				$switch_html = '<div ' . $this->get_render_attribute_string( 'rael_ct_div_round_2' ) . '><input ' . $this->get_render_attribute_string( 'rael_ct_input_round_2' ) . ' ' . $is_checked . '><label ' . $this->get_render_attribute_string( 'rael_ct_label_round_2' ) . '></label></div>';
				break;

			case 'rectangle':
				$switch_html = '<label ' . $this->get_render_attribute_string( 'rael_ct_label_rect' ) . '><input ' . $this->get_render_attribute_string( 'rael_ct_input_rect' ) . ' ' . $is_checked . '><span ' . $this->get_render_attribute_string( 'rael_ct_span_rect' ) . '></span></label>';
				break;

			case 'label_box':
				$on_label    = __( 'ON', 'responsive-addons-for-elementor' );
				$off_label   = __( 'OFF', 'responsive-addons-for-elementor' );
				$on          = apply_filters( 'rael_toggle_on_label', $on_label, $settings );
				$off         = apply_filters( 'rael_toggle_off_label', $off_label, $settings );
				$switch_html = '<div ' . $this->get_render_attribute_string( 'rael_ct_div_label_box' ) . '><input ' . $this->get_render_attribute_string( 'rael_ct_input_label_box' ) . ' ' . $is_checked . '><label ' . $this->get_render_attribute_string( 'rael_ct_label_label_box' ) . '><span ' . $this->get_render_attribute_string( 'rael_ct_span_inner_label_box' ) . '><span ' . $this->get_render_attribute_string( 'rael_ct_span_inactive_label_box' ) . '><span ' . $this->get_render_attribute_string( 'rael_ct_span_label_box' ) . '>' . $off . '</span></span><span ' . $this->get_render_attribute_string( 'rael_ct_span_active_label_box' ) . '><span ' . $this->get_render_attribute_string( 'rael_ct_span_label_box' ) . '>' . $on . '</span></span></span></label></div>';
				break;

			default:
				break;
		}
		?>

		<!-- Display Switch -->
		<?php echo wp_kses( $switch_html, $this->allowed_html_tags() ); ?>

				</div>
				<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_ct_section_heading_2' ) ); ?>>
					<<?php echo esc_attr( Helper::validate_html_tags( $settings['rael_ct_headings_tag'] ) ); ?> <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_ct_content_2_heading' ) ); ?> ><?php echo wp_kses_post( $this->get_settings_for_display( 'rael_ct_content_2_heading' ) ); ?></<?php echo esc_attr( Helper::validate_html_tags( $settings['rael_ct_headings_tag'] ) ); ?>>
				</div>
			</div>
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_ct_toggle_sections' ) ); ?>>
				<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_ct_content_1' ) ); ?>>
		<?php echo do_shortcode( $this->get_modal_content( $settings, $node_id, 'rael_ct_content_1_content_type' ) ); ?>
				</div>
				<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_ct_content_2' ) ); ?>>
		<?php echo do_shortcode( $this->get_modal_content( $settings, $node_id, 'rael_ct_content_2_content_type' ) ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Return Elementor allowed tags for current widget
	 *
	 * @return array Array of allowed tags for current widget
	 */
	private function allowed_html_tags() {
		return array(
			'label' => array(
				'class' => array(),
				'for'   => array(),
			),
			'input' => array(
				'class'   => array(),
				'type'    => array(),
				'name'    => array(),
				'id'      => array(),
				'checked' => array(),
			),
			'span'  => array(
				'class' => array(),
			),
			'div'   => array(
				'class' => array(),
			),
		);
	}

	/**
	 * Render output on the backend/preview window.
	 *
	 * Written in BackboneJS and used to generate the preview HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function content_template() {
	}

	/**
	 * Get Custom help URL
	 *
	 * @since  1.0.0
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return esc_url( 'https://cyberchimps.com/docs/widgets/content-switcher' );
	}
}
