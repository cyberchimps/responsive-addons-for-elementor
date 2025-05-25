<?php
/**
 * RAEL Theme Builder's Product title Widget.
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Widget_Heading;
use Elementor\Plugin;

/**
 * RAEL Theme Product title widget class.
 */
class Responsive_Addons_For_Elementor_Theme_Product_Title extends Widget_Heading {
	/**
	 * Retrieve the widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-theme-product-title';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Product Title', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-title rael-badge';
	}

	/**
	 * Get Categories
	 *
	 * Returns an array of tag categories
	 *
	 * @access public
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve widget keywords.
	 *
	 * @access public
	 *
	 * @return string Widget keywords.
	 */
	public function get_keywords() {
		return array( 'rael', 'woocommerce', 'shop', 'store', 'title', 'heading', 'product' );
	}

	/**
	 * Get custom url.
	 *
	 * @access public
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/';
	}

	/**
	 * Returns the inline CSS dependencies for this widget.
	 *
	 * This function returns an array of inline CSS dependencies for this widget.
	 *
	 * @return array The inline CSS dependencies for this widget.
	 */
	public function get_inline_css_depends() {
		return array(
			array(
				'name'               => 'heading',
				'is_core_dependency' => true,
			),
		);
	}

	/**
	 * Registers the controls for this widget.
	 *
	 * This function registers the controls for this widget and updates the 'title'
	 * and 'header_size' controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->update_control(
			'title',
			array(
				'dynamic' => array(
					'default' => Plugin::$instance->dynamic_tags->tag_data_to_tag_text( null, 'rael-product-title-tag' ),
				),
			),
			array(
				'recursive' => true,
			)
		);

		$this->update_control(
			'header_size',
			array(
				'default' => 'h1',
			)
		);
	}

	/**
	 * Returns the HTML wrapper class for this widget.
	 *
	 * This function returns the HTML wrapper class for this widget by calling the
	 * parent class's `get_html_wrapper_class()` method and appending the 'elementor-page-title'
	 * and 'elementor-widget-' . parent::get_name() classes.
	 *
	 * @return string The HTML wrapper class for this widget.
	 */
	protected function get_html_wrapper_class() {
		return parent::get_html_wrapper_class() . ' elementor-page-title elementor-widget-' . parent::get_name();
	}

	/**
	 * Renders the widget output.
	 *
	 * This function adds a render attribute to the 'title' element and calls the
	 * parent class's `render()` method to render the widget output.
	 *
	 * @return void
	 */
	protected function render() {
		$this->add_render_attribute( 'title', 'class', array( 'rael-product_title', 'entry-title' ) );
		parent::render();
	}

	/**
	 * Render Woocommerce Product Title output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<# view.addRenderAttribute( 'title', 'class', [ 'rael-product_title', 'entry-title' ] ); #>
		<?php
		parent::content_template();
	}


	/**
	 * Renders the plain content for this widget.
	 *
	 * This function should return the plain content to be displayed by the widget.
	 */
	public function render_plain_content() {}

	/**
	 * Returns the group name for this widget.
	 *
	 * This function returns the group name for this widget, which is 'woocommerce'.
	 *
	 * @return string The group name for this widget.
	 */
	public function get_group_name() {
		return 'woocommerce';
	}
}
