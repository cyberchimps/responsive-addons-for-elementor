<?php
/**
 * RAEL Theme Builder's Post Comments Widget.
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\ThemeBuilder;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\SingleQueryControl\Module as RAELSingleQueryControlModule;
use Elementor\Plugin;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\QueryControl\Controls\Group_Control_Query;

/**
 * RAEL Theme Post Comments widget class
 *
 * @since 1.4.0
 */
class Responsive_Addons_For_Elementor_Theme_Post_Comments extends Widget_Base {
	const SOURCE_TYPE_CURRENT_POST = 'current_post';
	const SOURCE_TYPE_CUSTOM       = 'custom';

	/**
	 * Get the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-theme-post-comments';
	}

	/**
	 * Get the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Post Comments', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-comments rael-badge';
	}

	/**
	 * Get the widget categories.
	 *
	 * @return string Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get the widget keywords.
	 *
	 * @return string Widget keywords.
	 */
	public function get_keywords() {
		return array( 'rael', 'post comments', 'comments', 'comment form' );
	}

	/**
	 * Get the widget doc url.
	 *
	 * @return string Widget doc url.
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/post-comments';
	}

	/**
	 * Renders plain content
	 */
	public function render_plain_content() {}

	/**
	 * Registers widget controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'rael_pc_content_tab_comments_section',
			array(
				'label' => __( 'Comments', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'_skin',
			array(
				'type' => Controls_Manager::HIDDEN,
			)
		);

		$this->add_control(
			'rael_pc_skin_temp',
			array(
				'label'       => __( 'Skin', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'description' => __( 'The Theme Comments skin uses the currently active theme comments design and layout to display the comment form and comments.', 'responsive-addons-for-elementor' ),
				'options'     => array(
					'' => __( 'Theme Comments', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_pc_source_type',
			array(
				'label'     => __( 'Source Type', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					self::SOURCE_TYPE_CURRENT_POST => __( 'Current Post', 'responsive-addons-for-elementor' ),
					self::SOURCE_TYPE_CUSTOM       => __( 'Custom', 'responsive-addons-for-elementor' ),
				),
				'default'   => self::SOURCE_TYPE_CURRENT_POST,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_pc_source_custom',
			array(
				'label'        => __( 'Search & Select', 'responsive-addons-for-elementor' ),
				'type'         => RAELSingleQueryControlModule::QUERY_CONTROL_ID,
				'label_block'  => true,
				'autocomplete' => array(
					'object' => RAELSingleQueryControlModule::QUERY_OBJECT_POST,
				),
				'condition'    => array(
					'rael_pc_source_type' => self::SOURCE_TYPE_CUSTOM,
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render's Widget content on frontend
	 */
	protected function render() {
		$settings = $this->get_settings();

		if ( self::SOURCE_TYPE_CUSTOM === $settings['rael_pc_source_type'] ) {
			$post_id = (int) $settings['rael_pc_source_custom'];
			Plugin::instance()->db->switch_to_post( $post_id );
		}

		if ( ! comments_open() && ( Plugin::instance()->preview->is_preview_mode() || Plugin::instance()->editor->is_edit_mode() ) ) :
			?>
			<div class="elementor-alert elementor-alert-danger">
				<span class="elementor-alert-title">
					<?php esc_html_e( 'Comments are closed.', 'responsive-addons-for-elementor' ); ?>
				</span>
				<span class="elementor-alert-description">
					<?php esc_html_e( 'Switch on comments from either the discussion box on the WordPress post edit screen or from the WordPress discussion settings.', 'responsive-addons-for-elementor' ); ?>
				</span>
			</div>
			<?php
		else :
			comments_template();
		endif;

		if ( self::SOURCE_TYPE_CUSTOM === $settings['rael_pc_source_type'] ) {
			Plugin::instance()->db->restore_current_post();
		}
	}
}
