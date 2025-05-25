<?php
/**
 * Portfolio Widget
 *
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\QueryControl\Module as Module_Query;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\QueryControl\Controls\Group_Control_Related;
use Responsive_Addons_For_Elementor\Helper\Helper;
if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}
/**
 * Elementor 'Portfolio' widget.
 *
 * Elementor widget that displays Portfolio.
 *
 * @since 1.0.0
 */
class Responsive_Addons_For_Elementor_Portfolio extends Widget_Base {
	/**
	 *
	 * Query variable initially set to null.
	 *
	 * @var \WP_Query
	 */
	private $rael_query = null;
	/**
	 * Flag indicating whether the template has content.
	 *
	 * @var bool $rael_has_template_content True if the template has content, false otherwise.
	 */
	protected $rael_has_template_content = false;
	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-portfolio';
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
		return __( 'PortFolio', 'responsive-addons-for-elementor' );
	}
	/**
	 * Get widget icon.
	 *
	 * Retrieve slider widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-gallery-grid rael-badge';
	}
	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the slider widget belongs to.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}
	/**
	 * Retrieves the script dependencies required for the Elementor widget.
	 *
	 * @return array An array of script dependencies.
	 */
	public function get_script_depends() {
		return array( 'imagesloaded' );
	}
	/**
	 * Handles the import process for the Elementor widget settings.
	 * If the 'posts_post_type' key is missing or invalid, sets it to 'post'.
	 *
	 * @param array $element The imported Elementor widget settings.
	 *
	 * @return array Modified Elementor widget settings.
	 */
	public function on_import( $element ) {
		if ( ! array_key_exists( 'posts_post_type', $element['settings'] ) || ! get_post_type_object( $element['settings']['posts_post_type'] ) ) {
			$element['settings']['posts_post_type'] = 'post';
		}
		return $element;
	}
	/**
	 * Retrieves the query object used for fetching posts in the Elementor widget.
	 *
	 * @return WP_Query|null The WP_Query object or null if not available.
	 */
	public function get_query() {
		return $this->_query;
	}
	/**
	 * Register all the control settings for the slider
	 *
	 * @since 1.0.0
	 * @access public
	 */
	protected function register_controls() {
		$this->register_query_section_controls();
	}
	/**
	 * Registers controls for the query section in the Elementor widget.
	 * Handles layout, number of columns, posts per page, masonry, item ratio, etc.
	 */
	private function register_query_section_controls() {
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_responsive_control(
			'columns',
			array(
				'label'              => __( 'Columns', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '3',
				'tablet_default'     => '2',
				'mobile_default'     => '1',
				'options'            => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'prefix_class'       => 'elementor-grid%s-',
				'frontend_available' => true,
				'selectors'          => array(
					'.elementor-msie {{WRAPPER}} .responsive-portfolio-item' => 'width: calc( 100% / {{SIZE}} )',
				),
			)
		);
		$this->add_control(
			'posts_per_page',
			array(
				'label'   => __( 'Posts Per Page', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
			)
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'         => 'thumbnail_size',
				'exclude'      => array( 'custom' ),
				'default'      => 'medium',
				'prefix_class' => 'responsive-portfolio--thumbnail-size-',
			)
		);
		$this->add_control(
			'masonry',
			array(
				'label'              => __( 'Masonry', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_off'          => __( 'Off', 'responsive-addons-for-elementor' ),
				'label_on'           => __( 'On', 'responsive-addons-for-elementor' ),
				'condition'          => array(
					'columns!' => '1',
				),
				'render_type'        => 'ui',
				'frontend_available' => true,
			)
		);
		$this->add_control(
			'item_ratio',
			array(
				'label'              => __( 'Item Ratio', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'size' => 0.66,
				),
				'range'              => array(
					'px' => array(
						'min'  => 0.1,
						'max'  => 2,
						'step' => 0.01,
					),
				),
				'selectors'          => array(
					'{{WRAPPER}} .responsive-post__thumbnail__link' => 'padding-bottom: calc( {{SIZE}} * 100% )',
					'{{WRAPPER}}:after' => 'content: "{{SIZE}}"; position: absolute; color: transparent;',
				),
				'condition'          => array(
					'masonry' => '',
				),
				'frontend_available' => true,
			)
		);
		$this->add_control(
			'show_title',
			array(
				'label'     => __( 'Show Title', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => __( 'Off', 'responsive-addons-for-elementor' ),
				'label_on'  => __( 'On', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_control(
			'title_tag',
			array(
				'label'     => __( 'Title HTML Tag', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
				'default'   => 'h3',
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_query',
			array(
				'label' => __( 'Query', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_group_control(
			Group_Control_Related::get_type(),
			array(
				'name'    => 'posts',
				'presets' => array( 'full' ),
				'exclude' => array(
					'posts_per_page', // use the one from Layout section.
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'filter_bar',
			array(
				'label' => __( 'Filter Bar', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'show_filter_bar',
			array(
				'label'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'responsive-addons-for-elementor' ),
				'label_on'  => __( 'On', 'responsive-addons-for-elementor' ),
			)
		);
		$this->add_control(
			'taxonomy',
			array(
				'label'       => __( 'Taxonomy', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'default'     => array(),
				'options'     => $this->get_taxonomies(),
				'condition'   => array(
					'show_filter_bar'  => 'yes',
					'posts_post_type!' => 'by_id',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_design_layout',
			array(
				'label' => __( 'Items', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		/*
		 * The `item_gap` control is replaced by `column_gap` and `row_gap` controls since v 2.1.6
		 * It is left (hidden) in the widget, to provide compatibility with older installs
		 */
		$this->add_control(
			'item_gap',
			array(
				'label'              => __( 'Item Gap', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'selectors'          => array(
					'{{WRAPPER}}' => '--grid-row-gap: {{SIZE}}{{UNIT}}; --grid-column-gap: {{SIZE}}{{UNIT}};',
				),
				'frontend_available' => true,
				'classes'            => 'elementor-hidden',
			)
		);
		$this->add_control(
			'column_gap',
			array(
				'label'     => __( 'Columns Gap', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => ' --grid-column-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_control(
			'row_gap',
			array(
				'label'              => __( 'Rows Gap', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'frontend_available' => true,
				'selectors'          => array(
					'{{WRAPPER}}' => '--grid-row-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_control(
			'border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .responsive-portfolio-item__img, {{WRAPPER}} .responsive-portfolio-item__overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_design_overlay',
			array(
				'label' => __( 'Item Overlay', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'color_background',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_ACCENT,
				),
				'selectors' => array(
					'{{WRAPPER}} a .responsive-portfolio-item__overlay' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'color_title',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} a .responsive-portfolio-item__title' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'typography_title',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'  => '{{WRAPPER}} .responsive-portfolio-item__title',
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_design_filter',
			array(
				'label'     => __( 'Filter Bar', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_filter_bar' => 'yes',
				),
			)
		);
		$this->add_control(
			'color_filter',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-portfolio__filter' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'color_filter_active',
			array(
				'label'     => __( 'Active Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-portfolio__filter.elementor-active' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography_filter',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .responsive-portfolio__filter',
			)
		);
		$this->add_control(
			'filter_item_spacing',
			array(
				'label'     => __( 'Space Between', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-portfolio__filter:not(:last-child)' => 'margin-right: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .responsive-portfolio__filter:not(:first-child)' => 'margin-left: calc({{SIZE}}{{UNIT}}/2)',
				),
			)
		);
		$this->add_control(
			'filter_spacing',
			array(
				'label'     => __( 'Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .responsive-portfolio__filters' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->end_controls_section();
	}
	/**
	 * Retrieves available taxonomies for filtering in the Elementor widget.
	 *
	 * @return array List of available taxonomies.
	 */
	protected function get_taxonomies() {
		$taxonomies = get_taxonomies( array( 'show_in_nav_menus' => true ), 'objects' );
		$options    = array( '' => '' );
		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}
		return $options;
	}
	/**
	 * Retrieves post tags and assigns them to each post in the query result.
	 */
	protected function get_posts_tags() {
		$taxonomy = $this->get_settings( 'taxonomy' );
		foreach ( $this->_query->posts as $post ) {
			if ( ! $taxonomy ) {
				$post->tags = array();
				continue;
			}
			$tags       = wp_get_post_terms( $post->ID, $taxonomy );
			$tags_slugs = array();
			foreach ( $tags as $tag ) {
				$tags_slugs[ $tag->term_id ] = $tag;
			}
			$post->tags = $tags_slugs;
		}
	}
	/**
	 * Queries the posts based on the Elementor widget settings.
	 */

	/**
	 * Query result will be stored in this array
	 *
	 * @var array
	 */
	public $_query;
	
	public function query_posts() {
		$query_args = array(
			'posts_per_page' => $this->get_settings( 'posts_per_page' ),
		);
		/**
		 *
		 * Query module for elementor.
		 *
		 *  @var Module_Query $elementor_query
		 */
		$elementor_query = Module_Query::instance();
		$this->_query    = $elementor_query->get_query( $this, 'posts', $query_args, array() );
	}
	/**
	 * Renders the portfolio posts based on the query result.
	 * Includes the loop header, individual posts, and the loop footer.
	 */
	public function render() {
		$this->query_posts();
		$wp_query = $this->get_query();
		if ( ! $wp_query->found_posts ) {
			return;
		}
		$this->get_posts_tags();
		$this->render_loop_header();
		while ( $wp_query->have_posts() ) {
			$wp_query->the_post();
			$this->render_post();
		}
		$this->render_loop_footer();
		wp_reset_postdata();
	}
	/**
	 * Renders the thumbnail for a single portfolio post.
	 */
	protected function render_thumbnail() {
		$settings                   = $this->get_settings();
		$settings['thumbnail_size'] = array(
			'id' => get_post_thumbnail_id(),
		);
		$thumbnail_html             = Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail_size' );
		?>
		<div class="responsive-portfolio-item__img elementor-post__thumbnail">
			<?php echo wp_kses_post( $thumbnail_html ); ?>
		</div>
		<?php
	}
	/**
	 * Renders the filter menu for filtering portfolio posts by taxonomy.
	 */
	protected function render_filter_menu() {
		$taxonomy = $this->get_settings( 'taxonomy' );
		if ( ! $taxonomy ) {
			return;
		}
		$terms = array();
		foreach ( $this->_query->posts as $post ) {
			$terms += $post->tags;
		}
		if ( empty( $terms ) ) {
			return;
		}
		usort(
			$terms,
			function( $a, $b ) {
				return strcmp( $a->name, $b->name );
			}
		);
		?>
		<ul class="responsive-portfolio__filters">
			<li class="responsive-portfolio__filter elementor-active" data-filter="__all"><?php echo esc_html_e( 'All', 'responsive-addons-for-elementor' ); ?></li>
			<?php foreach ( $terms as $term ) { ?>
				<li class="responsive-portfolio__filter" data-filter="<?php echo esc_attr( $term->term_id ); ?>"><?php echo wp_kses_post( $term->name ); ?></li>
			<?php } ?>
		</ul>
		<?php
	}
	/**
	 * Renders the title for a single portfolio post.
	 */
	protected function render_title() {
		if ( ! $this->get_settings( 'show_title' ) ) {
			return;
		}
		$tag = $this->get_settings( 'title_tag' );
		?>
		<<?php echo wp_kses_post( Helper::validate_html_tags( $tag ) ); ?> class="responsive-portfolio-item__title">
		<?php the_title(); ?>
		</<?php echo wp_kses_post( Helper::validate_html_tags( $tag ) ); ?>>
		<?php
	}
	/**
	 * Renders the header of a single portfolio post.
	 */
	protected function render_post_header() {
		global $post;
		$tags_classes = array_map(
			function( $tag ) {
				return 'elementor-filter-' . $tag->term_id;
			},
			$post->tags
		);
		$classes      = array(
			'responsive-portfolio-item',
			'elementor-post',
			implode( ' ', $tags_classes ),
		);
		?>
		<article <?php post_class( $classes ); ?>>
		<a class="responsive-post__thumbnail__link" href="<?php echo esc_url( get_permalink() ); ?>">
		<?php
	}
	/**
	 * Renders the footer of a single portfolio post.
	 */
	protected function render_post_footer() {
		?>
		</a>
		</article>
		<?php
	}
	/**
	 * Renders the header of the overlay for a single portfolio post.
	 */
	protected function render_overlay_header() {
		?>
		<div class="responsive-portfolio-item__overlay">
		<?php
	}
	/**
	 * Renders the footer of the overlay for a single portfolio post.
	 */
	protected function render_overlay_footer() {
		?>
		</div>
		<?php
	}
	/**
	 * Renders the header for the loop of portfolio posts.
	 */
	protected function render_loop_header() {
		if ( $this->get_settings( 'show_filter_bar' ) ) {
			$this->render_filter_menu();
		}
		?>
		<div class="responsive-portfolio elementor-grid responsive-posts-container">
		<?php
	}
	/**
	 * Renders the footer for the loop of portfolio posts.
	 */
	protected function render_loop_footer() {
		?>
		</div>
		<?php
	}
	/**
	 * Renders a single portfolio post.
	 */
	protected function render_post() {
		$this->render_post_header();
		$this->render_thumbnail();
		$this->render_overlay_header();
		$this->render_title();
		$this->render_overlay_footer();
		$this->render_post_footer();
	}
	/**
	 * Placeholder method for rendering plain content in the portfolio element.
	 * This method can be overridden in child classes.
	 */
	public function render_plain_content() {}
	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/elementor-widgets/docs/portfolio';
	}
}
