<?php
/**
 * Skin Card
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Skins;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Class RAEL_Skin_Cards
 */
class RAEL_Skin_Cards extends RAEL_Skin_Base {
	/**
	 * Retrieve the ID of the widget.
	 *
	 * @return string The ID of the widget.
	 */
	public function get_id() {
		return 'rael_cards';
	}
	/**
	 * Registers additional control actions for the widget.
	 *
	 * @return void
	 */
	protected function _register_controls_actions() {
		parent::_register_controls_actions();

		add_action( 'elementor/element/rael-posts/rael_cards_section_design_image/before_section_end', array( $this, 'register_additional_design_image_controls' ) );
	}
	/**
	 * Retrieve the title of the widget.
	 *
	 * @return string The title of the widget.
	 */
	public function get_title() {
		return __( 'Cards', 'responsive-addons-for-elementor' );
	}
	/**
	 * Starts a new controls tab for the widget.
	 *
	 * @param string $id   The ID of the tab.
	 * @param array  $args Optional. Additional arguments for the tab.
	 *                     Default empty array.
	 * @return void
	 */
	public function start_controls_tab( $id, $args ) {
		$args['condition']['_skin'] = $this->get_id();
		$this->parent->start_controls_tab( $this->get_control_id( $id ), $args );
	}
	/**
	 * Ends the current controls tab for the widget.
	 *
	 * @return void
	 */
	public function end_controls_tab() {
		$this->parent->end_controls_tab();
	}
	/**
	 * Starts a new controls tabs section for the widget.
	 *
	 * @param string $id The ID of the tabs section.
	 * @return void
	 */
	public function start_controls_tabs( $id ) {
		$args['condition']['_skin'] = $this->get_id();
		$this->parent->start_controls_tabs( $this->get_control_id( $id ) );
	}
	/**
	 * Ends the current controls tabs section for the widget.
	 *
	 * @return void
	 */
	public function end_controls_tabs() {
		$this->parent->end_controls_tabs();
	}
	/**
	 * Registers controls for the widget.
	 *
	 * @param Widget_Base $widget The widget instance for which controls are being registered.
	 * @return void
	 */
	public function register_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_columns_controls();
		$this->register_post_count_control();
		$this->register_thumbnail_controls();
		$this->register_title_controls();
		$this->register_excerpt_controls();
		$this->register_meta_data_controls();
		$this->register_read_more_controls();
		$this->register_link_controls();
		$this->register_badge_controls();
		$this->register_avatar_controls();
		$this->register_data_position_controls();
	}
	/**
	 * Registers design controls for the widget.
	 *
	 * @return void
	 */
	public function register_design_controls() {
		$this->register_design_layout_controls();
		$this->register_design_card_controls();
		$this->register_design_image_controls();
		$this->register_design_content_controls();
	}
	/**
	 * Registers design content controls for the widget.
	 *
	 * @return void
	 */
	protected function register_design_content_controls() {
		parent::register_design_content_controls();

		$this->remove_control( 'meta_spacing' );

		$this->update_control(
			'read_more_spacing',
			array(
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__read-more' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			),
			array(
				'recursive' => true,
			)
		);
	}
	/**
	 * Registers design card controls for the widget.
	 *
	 * @return void
	 */
	public function register_design_card_controls() {
		$this->start_controls_section(
			'section_design_card',
			array(
				'label' => __( 'Card', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'card_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__card' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'card_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__card' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'card_border_width',
			array(
				'label'      => __( 'Border Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 15,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-post__card' => 'border-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'card_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-post__card' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'card_padding',
			array(
				'label'      => __( 'Horizontal Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-post__text'   => 'padding: 0 {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .elementor-post__meta-data' => 'padding: 10px {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .elementor-post__avatar' => 'padding-right: {{SIZE}}{{UNIT}}; padding-left: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'card_vertical_padding',
			array(
				'label'      => __( 'Vertical Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-post__card' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'box_shadow_box_shadow_type', // The name of this control is like that, for future extensibility to group_control box shadow.
			array(
				'label'        => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'elementor-card-shadow-',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'hover_effect',
			array(
				'label'        => __( 'Hover Effect', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'label_block'  => false,
				'options'      => array(
					'none'     => __( 'None', 'responsive-addons-for-elementor' ),
					'gradient' => __( 'Gradient', 'responsive-addons-for-elementor' ),
				),
				'default'      => 'gradient',
				'separator'    => 'before',
				'prefix_class' => 'elementor-posts__hover-',
			)
		);

		$this->add_control(
			'meta_border_color',
			array(
				'label'     => __( 'Meta Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__card .elementor-post__meta-data' => 'border-top-color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'meta_data!' ) => array(),
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Registers badge controls for the widget.
	 *
	 * @return void
	 */
	public function register_badge_controls() {
		$this->add_control(
			'show_badge',
			array(
				'label'     => __( 'Badge', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off' => __( 'Hide', 'responsive-addons-for-elementor' ),
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'badge_taxonomy',
			array(
				'label'       => __( 'Badge Taxonomy', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'default'     => 'category',
				'options'     => $this->get_taxonomies(),
				'condition'   => array(
					$this->get_control_id( 'show_badge' ) => 'yes',
				),
			)
		);
	}
	/**
	 * Retrieves taxonomies that are enabled for navigation menus.
	 *
	 * @return array An array of taxonomy options.
	 */
	protected function get_taxonomies() {
		$taxonomies = get_taxonomies( array( 'show_in_nav_menus' => true ), 'objects' );

		$options = array( '' => '' );

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}
	/**
	 * Registers avatar controls for the widget.
	 *
	 * @return void
	 */
	public function register_avatar_controls() {
		$this->add_control(
			'show_avatar',
			array(
				'label'        => __( 'Avatar', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'show-avatar',
				'default'      => 'show-avatar',
				'separator'    => 'before',
				'prefix_class' => 'elementor-posts--',
				'render_type'  => 'template',
				'condition'    => array(
					$this->get_control_id( 'thumbnail!' ) => 'none',
				),
			)
		);
	}
	/**
	 * Registers meta data controls for the widget.
	 *
	 * @return void
	 */
	protected function register_meta_data_controls() {
		parent::register_meta_data_controls();
		$this->update_control(
			'meta_separator',
			array(
				'default' => '•',
			)
		);
	}
	/**
	 * Registers thumbnail controls for the widget.
	 *
	 * @return void
	 */
	protected function register_thumbnail_controls() {
		parent::register_thumbnail_controls();
		$this->remove_responsive_control( 'image_width' );
		$this->update_control(
			'thumbnail',
			array(
				'label'       => __( 'Show Image', 'responsive-addons-for-elementor' ),
				'options'     => array(
					'top'  => __( 'Yes', 'responsive-addons-for-elementor' ),
					'none' => __( 'No', 'responsive-addons-for-elementor' ),
				),
				'render_type' => 'template',
			)
		);
	}
	/**
	 * Registers additional design image controls for the widget.
	 *
	 * @return void
	 */
	public function register_additional_design_image_controls() {
		$this->update_control(
			'image_spacing',
			array(
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__text' => 'margin-top: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					$this->get_control_id( 'thumbnail!' ) => 'none',
				),
			)
		);

		$this->remove_control( 'img_border_radius' );

		$this->add_control(
			'heading_badge_style',
			array(
				'label'     => __( 'Badge', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					$this->get_control_id( 'show_badge' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'badge_position',
			array(
				'label'     => 'Badge Position',
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
				'default'   => 'right',
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__badge' => '{{VALUE}}: 0',
				),
				'condition' => array(
					$this->get_control_id( 'show_badge' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'badge_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__card .elementor-post__badge' => 'background-color: {{VALUE}};',
				),
				'global'   => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'condition' => array(
					$this->get_control_id( 'show_badge' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'badge_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__card .elementor-post__badge' => 'color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'show_badge' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'badge_radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__card .elementor-post__badge' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					$this->get_control_id( 'show_badge' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'badge_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 5,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__card .elementor-post__badge' => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					$this->get_control_id( 'show_badge' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'badge_margin',
			array(
				'label'     => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'default'   => array(
					'size' => 20,
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__card .elementor-post__badge' => 'margin: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					$this->get_control_id( 'show_badge' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'badge_typography',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector'  => '{{WRAPPER}} .elementor-post__card .elementor-post__badge',
				'exclude'   => array( 'font_size', 'line-height' ),
				'condition' => array(
					$this->get_control_id( 'show_badge' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'heading_avatar_style',
			array(
				'label'     => __( 'Avatar', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					$this->get_control_id( 'thumbnail!' )  => 'none',
					$this->get_control_id( 'show_avatar' ) => 'show-avatar',
				),
			)
		);

		$this->add_control(
			'avatar_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 20,
						'max' => 90,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-post__avatar' => 'top: calc(-{{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .elementor-post__avatar img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .responsive-post__thumbnail__link' => 'margin-bottom: calc({{SIZE}}{{UNIT}} / 2)',
				),
				'condition' => array(
					$this->get_control_id( 'show_avatar' ) => 'show-avatar',
				),
			)
		);
	}
	/**
	 * Renders the header of a post.
	 *
	 * @return void
	 */
	protected function render_post_header() {
		?>
		<article <?php post_class( array( 'elementor-post elementor-grid-item' ) ); ?>>
		<div class="elementor-post__card">
		<?php
	}
	/**
	 * Renders the footer of a post.
	 *
	 * @return void
	 */
	protected function render_post_footer() {
		?>
		</div>
		</article>
		<?php
	}
	/**
	 * Renders the avatar of the post author.
	 *
	 * @return void
	 */
	protected function render_avatar() {
		?>
		<div class="elementor-post__avatar">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 128, '', get_the_author_meta( 'display_name' ) ); ?>
		</div>
		<?php
	}
	/**
	 * Renders the badge associated with the post.
	 *
	 * @return void
	 */
	protected function render_badge() {
		$taxonomy = $this->get_instance_value( 'badge_taxonomy' );
		if ( empty( $taxonomy ) ) {
			return;
		}

		$terms = get_the_terms( get_the_ID(), $taxonomy );
		if ( empty( $terms[0] ) ) {
			return;
		}
		?>
		<div class="elementor-post__badge"><?php echo esc_html( $terms[0]->name ); ?></div>
		<?php
	}
	/**
	 * Renders the thumbnail of the post.
	 *
	 * @return void
	 */
	protected function render_thumbnail() {
		if ( 'none' == $this->get_instance_value( 'thumbnail' ) ) {
			return;
		}

		$settings                 = $this->parent->get_settings();
		$setting_key              = $this->get_control_id( 'thumbnail_size' );
		$settings[ $setting_key ] = array(
			'id' => get_post_thumbnail_id(),
		);
		$thumbnail_html           = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );

		if ( empty( $thumbnail_html ) ) {
			return;
		}

		$optional_attributes_html = $this->get_optional_link_attributes_html();

		?>
		<a class="responsive-post__thumbnail__link" href="<?php echo esc_url( get_permalink() ); ?>" <?php echo esc_html( $optional_attributes_html ); ?>>
			<div class="elementor-post__thumbnail"><?php echo wp_kses_post( $thumbnail_html ); ?></div>
		</a>
		<?php
		if ( $this->get_instance_value( 'show_badge' ) ) {
			$this->render_badge();
		}

		if ( $this->get_instance_value( 'show_avatar' ) ) {
			$this->render_avatar();
		}
	}
	/**
	 * Renders the entire post.
	 *
	 * @return void
	 */
	protected function render_post() {
		$content_positions_key = array(
			empty( $this->get_instance_value( 'title_position' ) ) ? 0 : $this->get_instance_value( 'title_position' ),
			empty( $this->get_instance_value( 'excerpt_position' ) ) ? 0 : $this->get_instance_value( 'excerpt_position' ),
			empty( $this->get_instance_value( 'read_more_position' ) ) ? 0 : $this->get_instance_value( 'read_more_position' ),
		);

		$content_positions_value = array(
			'render_title',
			'render_excerpt',
			'render_read_more',
		);

		$positions = array_combine( $content_positions_key, $content_positions_value );
		ksort( $positions );

		$this->render_post_header();
		$this->render_thumbnail();
		$this->render_text_header();

		foreach ( $positions as $key => $value ) {
			if ( 0 != $key ) {
				$this->$value();
			}
		}

		$this->render_text_footer();
		$this->render_meta_data();
		$this->render_post_footer();
	}

}
