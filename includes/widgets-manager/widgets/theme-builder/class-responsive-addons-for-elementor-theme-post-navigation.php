<?php
/**
 * RAEL Theme Builder's Post Navigation Widget.
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

/**
 * RAEL Theme Post Navigation widget class.
 *
 * @since 1.4.0
 */
class Responsive_Addons_For_Elementor_Theme_Post_Navigation extends Widget_Base {
	/**
	 * Get the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-theme-post-navigation';
	}
	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Post Navigation', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-navigation rael-badge';
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
		return array( 'rael', 'theme builder', 'post navigation', 'navigation', 'single', 'post' );
	}

	/**
	 * Get custom help url.
	 *
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/post-navigation';
	}

	/**
	 * Retrieves public post types.
	 *
	 * Retrieves an array of public post types with their labels.
	 *
	 * @param array $args {
	 *     Optional. Array of arguments for retrieving public post types.
	 *
	 *     @type string|array $post_type Name of the post type or an array of post types.
	 * }
	 * @return array Associative array of post types where keys are post type names and values are their labels.
	 */
	public static function get_public_post_types( $args = array() ) {
		$post_type_args = array(
			// Default is the value $public.
			'show_in_nav_menus' => true,
		);

		// Keep for backwards compatibility.
		if ( ! empty( $args['post_type'] ) ) {
			$post_typ_args['name'] = $args['post_type'];
			unset( $args['post_type'] );
		}

		$post_type_args = wp_parse_args( $post_type_args, $args );

		$_post_types = get_post_types( $post_type_args, 'objects' );

		$post_types = array();

		foreach ( $_post_types as $post_type => $object ) {
			$post_types[ $post_type ] = $object->label;
		}

		return $post_types;
	}

	/**
	 * Retrieves taxonomies based on specified arguments.
	 *
	 * Retrieves an array of taxonomies based on the provided arguments.
	 *
	 * @param array  $args {
	 *      Array of arguments for retrieving taxonomies.
	 *
	 *     @type string|string[] $object_type Name or array of names of object types.
	 *     @type bool            $public      Whether the taxonomy is publicly queryable.
	 *     @type string          $name        The name of the taxonomy.
	 *     @type string          $operator    The logical operator to use in the $args.
	 * }
	 * @param string $output    Optional. The type of output to return. Default 'names'.
	 * @param string $operator  Optional. The logical operator to use in the $args. Default 'and'.
	 * @return string[]|WP_Taxonomy[] Array of taxonomy names or WP_Taxonomy objects.
	 */
	public static function get_taxonomies( $args, $output = 'names', $operator = 'and' ) {
		global $wp_taxonomies;

		$field = 'names' === $output ? 'name' : false;

		// Handle 'object_type' separately.
		if ( isset( $args['object_type'] ) ) {
			$object_type = (array) $args['object_type'];
			unset( $args['object_type'] );
		}

		$taxonomies = wp_filter_object_list( $wp_taxonomies, $args, $operator );

		if ( isset( $object_type ) ) {
			foreach ( $taxonomies as $tax => $tax_data ) {
				if ( ! array_intersect( $object_type, $tax_data->object_type ) ) {
					unset( $taxonomies[ $tax ] );
				}
			}
		}

		if ( $field ) {
			$taxonomies = wp_list_pluck( $taxonomies, $field );
		}

		return $taxonomies;
	}

	/**
	 * Filters post types and their taxonomies.
	 *
	 * Retrieves public post types and their associated taxonomies, then filters them.
	 *
	 * @return array {
	 *     Array containing filtered post type options and their taxonomies.
	 *
	 *     @type array $options    Associative array of post type names and labels.
	 *     @type array $taxonomies Nested associative array of post type names and their associated taxonomies.
	 * }
	 */
	protected function filter_post_type() {
		$post_type_options    = array();
		$post_type_taxonomies = array();
		$public_post_types    = self::get_public_post_types();

		foreach ( $public_post_types as $post_type => $post_type_label ) {
			$taxonomies = self::get_taxonomies( array( 'object_type' => $post_type ), false );

			if ( empty( $taxonomies ) ) {
				continue;
			}

			$post_type_options[ $post_type ]    = $post_type_label;
			$post_type_taxonomies[ $post_type ] = array();

			foreach ( $taxonomies as $taxonomy ) {
				$post_type_taxonomies[ $post_type ][ $taxonomy->name ] = $taxonomy->label;
			}
		}

		return array(
			'options'    => $post_type_options,
			'taxonomies' => $post_type_taxonomies,
		);
	}

	/**
	 * Registers controls for the element.
	 *
	 * Registers controls for different tabs and sections related to the element's content and style.
	 */
	protected function register_controls() {
		// Content Tab.
		$this->register_content_tab_post_navigation_section();

		// Style Tab.
		$this->register_style_tab_label_section();
		$this->register_style_tab_title_section();
		$this->register_style_tab_arrow_section();
		$this->register_style_tab_borders_section();
	}

	/**
	 * Registers the controls for the content tab post navigation section.
	 *
	 * This method registers controls related to post navigation in the content tab.
	 * It adds controls for configuring post navigation labels, arrows, post titles, borders, and taxonomy filters.
	 */
	protected function register_content_tab_post_navigation_section() {
		$filtered_post_type = $this->filter_post_type();

		$this->start_controls_section(
			'rael_pn_content_tab_post_navigation_section',
			array(
				'label' => __( 'Post Navigation', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_pn_show_label',
			array(
				'label'        => __( 'Label', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_pn_prev_label',
			array(
				'label'     => __( 'Previous Label', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Previous', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_pn_show_label' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_pn_next_label',
			array(
				'label'     => __( 'Next Label', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Next', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_pn_show_label' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_pn_show_arrow',
			array(
				'label'        => __( 'Arrows', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_pn_arrow',
			array(
				'label'     => __( 'Arrows Type', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'fa fa-angle-left'          => __( 'Angle', 'responsive-addons-for-elementor' ),
					'fa fa-angle-double-left'   => __( 'Double Angle', 'responsive-addons-for-elementor' ),
					'fa fa-chevron-left'        => __( 'Chevron', 'responsive-addons-for-elementor' ),
					'fa fa-chevron-circle-left' => __( 'Chevron Circle', 'responsive-addons-for-elementor' ),
					'fa fa-caret-left'          => __( 'Caret', 'responsive-addons-for-elementor' ),
					'fa fa-arrow-left'          => __( 'Arrow', 'responsive-addons-for-elementor' ),
					'fa fa-long-arrow-left'     => __( 'Long Arrow', 'responsive-addons-for-elementor' ),
					'fa fa-arrow-circle-left'   => __( 'Arrow Circle', 'responsive-addons-for-elementor' ),
					'fa fa-arrow-circle-o-left' => __( 'Arrow Circle Negative', 'responsive-addons-for-elementor' ),
				),
				'default'   => 'fa fa-angle-left',
				'condition' => array(
					'rael_pn_show_arrow' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_pn_show_title',
			array(
				'label'        => __( 'Post Title', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'default'      => 'yes',
				'responsive-addons-for-elementor',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rael_pn_show_borders',
			array(
				'label'        => __( 'Borders', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'default'      => 'yes',
				'return_value' => 'yes',
				'prefix_class' => 'rael-post-navigation-borders--',
			)
		);

		$this->add_control(
			'rael_pn_in_same_term',
			array(
				'label'       => __( 'In same term', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $filtered_post_type['options'],
				'default'     => '',
				'multiple'    => true,
				'label_block' => true,
				'description' => __( 'Indicates whether next post must be within the same taxonomy term as the current post, this lets you set a taxonomy per each post type.', 'responsive-addons-for-elementor' ),
			)
		);

		foreach ( $filtered_post_type['options'] as $post_type => $post_type_label ) {
			$this->add_control(
				'rael_pn_' . $post_type . '_taxonomy',
				array(
					'label'     => $post_type_label . __( ' Taxonomy', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => $filtered_post_type['taxonomies'][ $post_type ],
					'default'   => '',
					'condition' => array(
						'rael_pn_in_same_term' => $post_type,
					),
				)
			);
		}

		$this->end_controls_section();
	}

	/**
	 * Registers the controls for the style tab label section.
	 *
	 * This method registers controls related to styling the labels in the style tab.
	 * It adds controls for configuring the color and typography of the labels in normal and hover states.
	 */
	protected function register_style_tab_label_section() {
		$this->start_controls_section(
			'rael_pn_style_tab_label_section',
			array(
				'label'     => __( 'Label', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_pn_show_label' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'rael_pn_label_tabs' );

		$this->start_controls_tab(
			'rael_pn_label_normal_tab',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_pn_label_color_normal',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-post-navigation__prev--label, {{WRAPPER}} .rael-post-navigation__next--label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_pn_label_hover_tab',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_pn_label_color_hover',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-post-navigation__prev--label:hover, {{WRAPPER}} .rael-post-navigation__next--label:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_pn_label_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
				'selector' => '{{WRAPPER}} .rael-post-navigation__prev--label, {{WRAPPER}} .rael-post-navigation__next--label',
				'exclude'  => array( 'line_height' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Registers the controls for the style tab title section.
	 *
	 * This method registers controls related to styling the titles in the style tab.
	 * It adds controls for configuring the color and typography of the titles in normal and hover states.
	 */
	protected function register_style_tab_title_section() {
		$this->start_controls_section(
			'rael_pn_style_tab_title_section',
			array(
				'label'     => __( 'Title', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_pn_show_title' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'rael_pn_title_tabs' );

		$this->start_controls_tab(
			'rael_pn_title_normal_tab',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_pn_title_color_normal',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-post-navigation__prev--title, {{WRAPPER}} .rael-post-navigation__next--title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_pn_title_hover_tab',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_pn_title_color_hover',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-post-navigation__prev--title:hover, {{WRAPPER}} .rael-post-navigation__next--title:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_pn_title_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				),
				'selector' => '{{WRAPPER}} .rael-post-navigation__prev--title, {{WRAPPER}} .rael-post-navigation__next--title',
				'exclude'  => array( 'line_height' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Registers the controls for the style tab arrow section.
	 *
	 * This method registers controls related to styling the arrows in the style tab.
	 * It adds controls for configuring the color, size, and gap of the arrows in normal and hover states.
	 */
	protected function register_style_tab_arrow_section() {
		$this->start_controls_section(
			'rael_pn_style_tab__section',
			array(
				'label'     => __( 'Arrow', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_pn_show_arrow' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'rael_pn_arrow_tabs' );

		$this->start_controls_tab(
			'rael_pn_arrow_normal_tab',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_pn_arrow_color_normal',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-post-navigation__arrow-wrapper' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_pn_arrow_hover_tab',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_pn_arrow_color_hover',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-post-navigation__arrow-wrapper:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'rael_pn_arrow_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-post-navigation__arrow-wrapper' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_pn_arrow_padding',
			array(
				'label'     => __( 'Gap', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'em' => array(
						'min' => 0,
						'max' => 5,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-post-navigation__arrow--prev' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-post-navigation__arrow--next' => 'padding-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Registers the controls for the style tab borders section.
	 *
	 * This method registers controls related to styling the borders in the style tab.
	 * It adds controls for configuring the color, width, and spacing of the borders.
	 */
	protected function register_style_tab_borders_section() {
		$this->start_controls_section(
			'rael_pn_style_tab_borders_section',
			array(
				'label'     => __( 'Borders', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_pn_show_borders' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_pn_separator_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-post-navigation__separator' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-post-navigation' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_pn_borders_width',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-post-navigation__separator' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .rael-post-navigation' => 'border-top-width: {{SIZE}}{{UNIT}}; border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-post-navigation__next.rael-post-navigation__link' => 'width: calc(50% - ({{SIZE}}{{UNIT}} / 2))',
					'{{WRAPPER}} .rael-post-navigation__prev.rael-post-navigation__link' => 'width: calc(50% - ({{SIZE}}{{UNIT}} / 2))',
				),
			)
		);

		$this->add_control(
			'rael_pn_borders_spacing',
			array(
				'label'     => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'em' => array(
						'min' => 0,
						'max' => 5,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-post-navigation' => 'padding: {{SIZE}}{{UNIT}} 0;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Renders the content tab meta data controls based on the provided settings.
	 */
	protected function render() {
		$settings = $this->get_active_settings();

		$prev_label   = '';
		$next_label   = '';
		$prev_arrow   = '';
		$next_arrow   = '';
		$prev_title   = '';
		$next_title   = '';
		$in_same_term = false;
		$taxonomy     = 'category';
		$post_type    = get_post_type( get_queried_object_id() );

		if ( 'yes' === $settings['rael_pn_show_label'] ) {
			$prev_label = '<span class="rael-post-navigation__prev--label">' . $settings['rael_pn_prev_label'] . '</span>';
			$next_label = '<span class="rael-post-navigation__next--label">' . $settings['rael_pn_next_label'] . '</span>';
		}

		if ( 'yes' === $settings['rael_pn_show_arrow'] ) {
			$prev_icon_class = $settings['rael_pn_arrow'];
			$next_icon_class = str_replace( 'left', 'right', $settings['rael_pn_arrow'] );

			$prev_arrow = '<span class="rael-post-navigation__arrow-wrapper rael-post-navigation__arrow--prev"><i class="' . $prev_icon_class . '" aria-hidden="true"></i><span class="elementor-screen-only">' . esc_html__( 'Prev', 'responsive-addons-for-elementor' ) . '</span></span>';
			$next_arrow = '<span class="rael-post-navigation__arrow-wrapper rael-post-navigation__arrow--next"><i class="' . $next_icon_class . '" aria-hidden="true"></i><span class="elementor-screen-only">' . esc_html__( 'Next', 'responsive-addons-for-elementor' ) . '</span></span>';
		}

		if ( 'yes' === $settings['rael_pn_show_title'] ) {
			$prev_title = '<span class="rael-post-navigation__prev--title">%title</span>';
			$next_title = '<span class="rael-post-navigation__next--title">%title</span>';
		}

		if ( ! empty( $settings['rael_pn_in_same_term'] ) && is_array( $settings['rael_pn_in_same_term'] ) && in_array( $post_type, $settings['rael_pn_in_same_term'], true ) ) {
			if ( isset( $settings[ 'rael_pn_' . $post_type . '_taxonomy' ] ) ) {
				$in_same_term = true;
				$taxonomy     = $settings[ 'rael_pn_' . $post_type . '_taxonomy' ];
			}
		}
		?>
		<div class="rael-post-navigation">
			<div class="rael-post-navigation__prev rael-post-navigation__link">
				<?php previous_post_link( '%link', $prev_arrow . '<span class="rael-post-navigation__link--prev">' . $prev_label . $prev_title . '</span>', $in_same_term, '', $taxonomy ); ?>
			</div>
			<?php if ( 'yes' === $settings['rael_pn_show_borders'] ) : ?>
				<div class="rael-post-navigation__separator-wrapper">
					<div class="rael-post-navigation__separator"></div>
				</div>
			<?php endif; ?>
			<div class="rael-post-navigation__next rael-post-navigation__link">
				<?php next_post_link( '%link', '<span class="rael-post-navigation__link--next">' . $next_label . $next_title . '</span>' . $next_arrow, $in_same_term, '', $taxonomy ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render plain content (placeholder method).
	 *
	 * This method is a placeholder and does not perform any specific rendering.
	 * Implement this method to handle the actual rendering of plain content if needed.
	 */
	public function render_plain_content() {}
}
