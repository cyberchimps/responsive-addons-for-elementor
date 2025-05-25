<?php
/**
 * RAEL Theme Builder's Product Meta Widget.
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * RAEL Theme Product meta widget class.
 *
 * @since 1.8.0
 */
class Responsive_Addons_For_Elementor_Theme_Product_Meta extends Woo_Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-woocommerce-product-meta';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Product Meta', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-meta rael-badge';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve widget keywords.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @return string Widget keywords.
	 */
	public function get_keywords() {
		return array( 'rael', 'woocommerce', 'shop', 'store', 'meta', 'data', 'product' );
	}

	/**
	 * Get widget help url.
	 *
	 * @since 1.8.0
	 * @access public
	 *
	 * @return string Widget keywords.
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/';
	}

	/**
	 * Register all the control settings for the product meta widget
	 *
	 * @since 1.8.0
	 * @access public
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'rael_section_product_meta_style',
			array(
				'label' => esc_html__( 'Style', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_wc_style_warning',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'responsive-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'rael_view',
			array(
				'label'        => esc_html__( 'View', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'inline',
				'options'      => array(
					'table'   => esc_html__( 'Table', 'responsive-addons-for-elementor' ),
					'stacked' => esc_html__( 'Stacked', 'responsive-addons-for-elementor' ),
					'inline'  => esc_html__( 'Inline', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'rael-elementor-woo-meta--view-',
			)
		);

		$this->add_responsive_control(
			'rael_space_between',
			array(
				'label'     => esc_html__( 'Space Between', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}:not(.rael-elementor-woo-meta--view-inline) .rael_product_meta .rael-detail-container:not(:last-child)' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}}:not(.rael-elementor-woo-meta--view-inline) .rael_product_meta .rael-detail-container:not(:first-child)' => 'margin-top: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}}.rael-elementor-woo-meta--view-inline .rael_product_meta .rael-detail-container' => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}}.rael-elementor-woo-meta--view-inline .rael_product_meta' => 'margin-right: calc(-{{SIZE}}{{UNIT}}/2); margin-left: calc(-{{SIZE}}{{UNIT}}/2)',
					'body:not(.rtl) {{WRAPPER}}.rael-elementor-woo-meta--view-inline .rael-detail-container:after' => 'right: calc( (-{{SIZE}}{{UNIT}}/2) + (-{{divider_weight.SIZE}}px/2) )',
					'body:not.rtl {{WRAPPER}}.rael-elementor-woo-meta--view-inline .rael-detail-container:after' => 'left: calc( (-{{SIZE}}{{UNIT}}/2) - ({{divider_weight.SIZE}}px/2) )',
				),
			)
		);

		$this->add_control(
			'rael_divider',
			array(
				'label'        => esc_html__( 'Divider', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => esc_html__( 'Off', 'responsive-addons-for-elementor' ),
				'label_on'     => esc_html__( 'On', 'responsive-addons-for-elementor' ),
				'selectors'    => array(
					'{{WRAPPER}} .rael_product_meta .rael-detail-container:not(:last-child):after' => 'content: ""',
				),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'rael_divider_style',
			array(
				'label'     => esc_html__( 'Style', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'solid'  => esc_html__( 'Solid', 'responsive-addons-for-elementor' ),
					'double' => esc_html__( 'Double', 'responsive-addons-for-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'responsive-addons-for-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'responsive-addons-for-elementor' ),
				),
				'default'   => 'solid',
				'condition' => array(
					'rael_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}}:not(.rael-elementor-woo-meta--view-inline) .rael_product_meta .rael-detail-container:not(:last-child):after' => 'border-top-style: {{VALUE}}',
					'{{WRAPPER}}.rael-elementor-woo-meta--view-inline .rael_product_meta .rael-detail-container:not(:last-child):after' => 'border-left-style: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_divider_weight',
			array(
				'label'     => esc_html__( 'Weight', 'responsive-addons-for-elementor' ),
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
					'rael_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}}:not(.rael-elementor-woo-meta--view-inline) .rael_product_meta .rael-detail-container:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}}; margin-bottom: calc(-{{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}}.rael-elementor-woo-meta--view-inline .rael_product_meta .rael-detail-container:not(:last-child):after' => 'border-left-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'rael_divider_width',
			array(
				'label'      => esc_html__( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'default'    => array(
					'unit' => '%',
				),
				'condition'  => array(
					'rael_divider' => 'yes',
					'rael_view!'   => 'inline',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael_product_meta .rael-detail-container:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'rael_divider_height',
			array(
				'label'      => esc_html__( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'default'    => array(
					'unit' => '%',
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
					'rael_divider' => 'yes',
					'rael_view'    => 'inline',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael_product_meta .rael-detail-container:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'rael_divider_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ddd',
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'condition' => array(
					'rael_divider' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael_product_meta .rael-detail-container:not(:last-child):after' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_heading_text_style',
			array(
				'label'     => esc_html__( 'Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_text_typography',
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'rael_text_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rael_heading_link_style',
			array(
				'label'     => esc_html__( 'Link', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_link_typography',
				'selector' => '{{WRAPPER}} a',
			)
		);

		$this->add_control(
			'rael_link_color',
			array(
				'label'     => esc_html__( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_product_meta_captions',
			array(
				'label' => esc_html__( 'Captions', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_heading_category_caption',
			array(
				'label' => esc_html__( 'Category', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_category_caption_single',
			array(
				'label'       => esc_html__( 'Singular', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Category', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_category_caption_plural',
			array(
				'label'       => esc_html__( 'Plural', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Categories', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_heading_tag_caption',
			array(
				'label'     => esc_html__( 'Tag', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_tag_caption_single',
			array(
				'label'       => esc_html__( 'Singular', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Tag', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_tag_caption_plural',
			array(
				'label'       => esc_html__( 'Plural', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Tags', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_heading_sku_caption',
			array(
				'label'     => esc_html__( 'SKU', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_sku_caption',
			array(
				'label'       => esc_html__( 'SKU', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'SKU', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_sku_missing_caption',
			array(
				'label'       => esc_html__( 'Missing', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'N/A', 'responsive-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Returns the singular or plural form of a word based on a count.
	 *
	 * This function takes a singular word, a plural word, and a count, and returns
	 * the appropriate word based on whether the count is 1 or not.
	 *
	 * @param string $single The singular form of the word.
	 * @param string $plural The plural form of the word.
	 * @param int    $count The count to determine whether to use the singular or plural form.
	 *
	 * @return string The singular or plural form of the word.
	 */
	private function get_plural_or_single( $single, $plural, $count ) {
		return 1 === $count ? $single : $plural;
	}

	/**
	 * Render function for the widget
	 *
	 * @since 1.8.0
	 *
	 * @access public
	 */
	protected function render() {
		global $product;

		$product = wc_get_product();

		if ( empty( $product ) ) {
			return;
		}

		$sku = $product->get_sku();

		$settings                = $this->get_settings_for_display();
		$sku_caption             = ! empty( $settings['sku_caption'] ) ? $settings['sku_caption'] : __( 'SKU', 'responsive-addons-for-elementor' );
		$sku_missing             = ! empty( $settings['sku_missing_caption'] ) ? $settings['sku_missing_caption'] : __( 'N/A', 'responsive-addons-for-elementor' );
		$category_caption_single = ! empty( $settings['category_caption_single'] ) ? $settings['category_caption_single'] : __( 'Category', 'responsive-addons-for-elementor' );
		$category_caption_plural = ! empty( $settings['category_caption_plural'] ) ? $settings['category_caption_plural'] : __( 'Categories', 'responsive-addons-for-elementor' );
		$tag_caption_single      = ! empty( $settings['tag_caption_single'] ) ? $settings['tag_caption_single'] : __( 'Tag', 'responsive-addons-for-elementor' );
		$tag_caption_plural      = ! empty( $settings['tag_caption_plural'] ) ? $settings['tag_caption_plural'] : __( 'Tags', 'responsive-addons-for-elementor' );
		?>
		<div class="rael_product_meta">

			<?php do_action( 'woocommerce_product_meta_start' ); ?>

			<?php if ( wc_product_sku_enabled() && ( $sku || $product->is_type( 'variable' ) ) ) : ?>
				<span class="sku_wrapper rael-detail-container"><span class="rael-detail-label"><?php echo esc_html( $sku_caption ); ?></span> <span class="sku"><?php echo $sku ? wp_kses_post( $sku ) : esc_html( $sku_missing ); ?></span></span>
			<?php endif; ?>

			<?php if ( count( $product->get_category_ids() ) ) : ?>
				<span class="posted_in rael-detail-container"><span class="rael-detail-label"><?php echo esc_html( $this->get_plural_or_single( $category_caption_single, $category_caption_plural, count( $product->get_category_ids() ) ) ); ?></span> <span class="detail-content"><?php echo get_the_term_list( $product->get_id(), 'product_cat', '', ', ' ); ?></span></span>
			<?php endif; ?>

			<?php if ( count( $product->get_tag_ids() ) ) : ?>
				<span class="tagged_as rael-detail-container"><span class="rael-detail-label"><?php echo esc_html( $this->get_plural_or_single( $tag_caption_single, $tag_caption_plural, count( $product->get_tag_ids() ) ) ); ?></span> <span class="detail-content"><?php echo get_the_term_list( $product->get_id(), 'product_tag', '', ', ' ); ?></span></span>
			<?php endif; ?>

			<?php do_action( 'woocommerce_product_meta_end' ); ?>

		</div>
		<?php
	}

	/**
	 * Render plain content function for the widget
	 *
	 * @since 1.8.0
	 *
	 * @access public
	 */
	public function render_plain_content() {}
}
