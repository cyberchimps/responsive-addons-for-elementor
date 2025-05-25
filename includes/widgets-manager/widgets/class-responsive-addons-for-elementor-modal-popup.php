<?php
/**
 * RAEL Modal Popup Widget
 *
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Control_Media;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor 'RAEL Modal Popup' widget.
 *
 * @since 1.2.1
 */
class Responsive_Addons_For_Elementor_Modal_Popup extends Widget_Base {

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
	 * Elementor saved widget templates list
	 *
	 * @var widget_templates
	 */
	private static $widget_templates = null;

	/**
	 * Get widget name.
	 *
	 * Retrieve 'RAEL Modal Popup' widget name.
	 *
	 * @since 1.2.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-modal-popup';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve 'RAEL Modal Popup' widget title.
	 *
	 * @since 1.2.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Modal Popup', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve 'RAEL Modal Popup' widget icon.
	 *
	 * @since 1.2.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-editor-external-link rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve 'RAEL Modal Popup' widget icon.
	 *
	 * @since 1.2.1
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Render content type list.
	 *
	 * @since 1.2.1
	 * @return array Array of content type
	 * @access public
	 */
	public function get_content_type() {

		$content_type = array(
			'content'              => __( 'Content', 'responsive-addons-for-elementor' ),
			'photo'                => __( 'Photo', 'responsive-addons-for-elementor' ),
			'video'                => __( 'Video Embed Code', 'responsive-addons-for-elementor' ),
			'saved_rows'           => __( 'Saved Section', 'responsive-addons-for-elementor' ),
			'saved_page_templates' => __( 'Saved Page', 'responsive-addons-for-elementor' ),
			'youtube'              => __( 'YouTube', 'responsive-addons-for-elementor' ),
			'vimeo'                => __( 'Vimeo', 'responsive-addons-for-elementor' ),
			'iframe'               => __( 'Iframe', 'responsive-addons-for-elementor' ),
		);

		if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			$content_type['saved_modules'] = __( 'Saved Widget', 'responsive-addons-for-elementor' );
		}

		return $content_type;
	}

	/**
	 *  Get Saved templates
	 *
	 *  @param string $type Type.
	 *  @since 1.2.0
	 *  @return array of templates
	 */
	public static function get_saved_data( $type = 'page' ) {

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
	 * Register Modal Popup controls.
	 *
	 * @since 1.2.1
	 * @access protected
	 */
	protected function register_controls() {

		/**
		 *  Content settings for RAEL Modal Popup
		 */

		$this->start_controls_section(
			'content',
			array(
				'label' => __( 'Content', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_preview_modal',
			array(
				'label'        => __( 'Preview Modal Popup', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_title',
			array(
				'label'   => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => __( 'This is Modal Title', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_content_type',
			array(
				'label'   => __( 'Content Type', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'photo',
				'options' => $this->get_content_type(),
			)
		);

		$this->add_control(
			'rael_ct_content',
			array(
				'label'      => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => __( 'Enter content here. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.​ Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'responsive-addons-for-elementor' ),
				'rows'       => 10,
				'show_label' => false,
				'dynamic'    => array(
					'active' => true,
				),
				'condition'  => array(
					'rael_content_type' => 'content',
				),
			)
		);

		$this->add_control(
			'rael_ct_photo',
			array(
				'label'     => __( 'Photo', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'rael_content_type' => 'photo',
				),
			)
		);

		$this->add_control(
			'rael_ct_video',
			array(
				'label'       => __( 'Embed Code / URL', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => array(
					'active'     => true,
					'categories' => array(
						TagsModule::URL_CATEGORY,
					),
				),
				'condition'   => array(
					'rael_content_type' => 'video',
				),
			)
		);

		$this->add_control(
			'rael_ct_saved_rows',
			array(
				'label'     => __( 'Select Section', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_saved_data( 'section' ),
				'default'   => '-1',
				'condition' => array(
					'rael_content_type' => 'saved_rows',
				),
			)
		);

		$this->add_control(
			'rael_ct_saved_modules',
			array(
				'label'     => __( 'Select Widget', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_saved_data( 'widget' ),
				'default'   => '-1',
				'condition' => array(
					'rael_content_type' => 'saved_modules',
				),
			)
		);

		$this->add_control(
			'rael_ct_page_templates',
			array(
				'label'     => __( 'Select Page', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_saved_data( 'page' ),
				'default'   => '-1',
				'condition' => array(
					'rael_content_type' => 'saved_page_templates',
				),
			)
		);

		$this->add_control(
			'rael_video_url',
			array(
				'label'       => __( 'Video URL', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => array(
					'active'     => true,
					'categories' => array(
						TagsModule::URL_CATEGORY,
					),
				),
				'condition'   => array(
					'rael_content_type' => array( 'youtube', 'vimeo' ),
				),
			)
		);

		$this->add_control(
			'rael_youtube_link_doc',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				/* translators: %1$s doc link */
				'raw'             => sprintf( __( '<b>Note:</b> Make sure you add the actual URL of the video and not the share URL.</br>', 'responsive-addons-for-elementor' ) ),
				'content_classes' => 'rael-editor-doc',
				'condition'       => array(
					'rael_content_type' => 'youtube',
				),
				'separator'       => 'none',
			)
		);

		$this->add_control(
			'rael_vimeo_link_doc',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				/* translators: %1$s doc link */
				'raw'             => sprintf( __( '<b>Note:</b> Make sure you add the actual URL of the video and not the categorized URL.</br>', 'responsive-addons-for-elementor' ) ),
				'content_classes' => 'rael-editor-doc',
				'condition'       => array(
					'rael_content_type' => 'vimeo',
				),
				'separator'       => 'none',
			)
		);

		$this->add_control(
			'rael_iframe_url',
			array(
				'label'       => __( 'Iframe URL', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => array(
					'active'     => true,
					'categories' => array(
						TagsModule::URL_CATEGORY,
					),
				),
				'condition'   => array(
					'rael_content_type' => 'iframe',
				),
			)
		);

		$this->add_control(
			'rael_async_iframe',
			array(
				'label'        => __( 'Async Iframe Load', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'description'  => __( 'Enabling this option will reduce the page size and page loading time. The related CSS and JS scripts will load on request. A loader will appear during loading of the Iframe.', 'responsive-addons-for-elementor' ),
				'condition'    => array(
					'rael_content_type' => 'iframe',
				),
			)
		);

		$this->add_responsive_control(
			'rael_iframe_height',
			array(
				'label'      => __( 'Height of Iframe', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'size' => '500',
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 2000,
					),
					'em' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'.raelmodal-{{ID}} .rael-modal-iframe .rael-modal-content-data' => 'height: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'rael_content_type' => 'iframe',
				),
			)
		);

		$this->add_control(
			'rael_video_ratio',
			array(
				'label'              => __( 'Aspect Ratio', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => array(
					'16_9' => '16:9',
					'4_3'  => '4:3',
					'3_2'  => '3:2',
				),
				'default'            => '16_9',
				'prefix_class'       => 'rael-aspect-ratio-',
				'frontend_available' => true,
				'condition'          => array(
					'rael_content_type' => array( 'youtube', 'vimeo' ),
				),
			)
		);

		$this->add_control(
			'rael_video_autoplay',
			array(
				'label'        => __( 'Autoplay', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'condition'    => array(
					'rael_content_type' => array( 'youtube', 'vimeo' ),
				),
			)
		);

		$this->add_control(
			'rael_youtube_related_videos',
			array(
				'label'     => __( 'Related Videos From', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'no',
				'options'   => array(
					'no'  => __( 'Current Video Channel', 'responsive-addons-for-elementor' ),
					'yes' => __( 'Any Random Video', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_content_type' => 'youtube',
				),
			)
		);

		$this->add_control(
			'rael_youtube_player_controls',
			array(
				'label'        => __( 'Disable Player Controls', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'condition'    => array(
					'rael_content_type' => 'youtube',
				),
			)
		);

		$this->add_control(
			'rael_video_controls_adv',
			array(
				'label'        => __( 'Advanced Settings', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'condition'    => array(
					'rael_content_type' => array( 'youtube', 'vimeo' ),
				),
			)
		);

		$this->add_control(
			'rael_start',
			array(
				'label'       => __( 'Start Time', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => __( 'Specify a start time (in seconds)', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_content_type'       => array( 'youtube', 'vimeo' ),
					'rael_video_controls_adv' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_end',
			array(
				'label'       => __( 'End Time', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => __( 'Specify an end time (in seconds)', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_content_type'       => 'youtube',
					'rael_video_controls_adv' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_yt_mute',
			array(
				'label'     => __( 'Mute', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array(
					'rael_content_type'       => 'youtube',
					'rael_video_controls_adv' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_yt_modestbranding',
			array(
				'label'        => __( 'Modest Branding', 'responsive-addons-for-elementor' ),
				'description'  => __( 'This option lets you use a YouTube player that does not show a YouTube logo.', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'condition'    => array(
					'rael_content_type'             => 'youtube',
					'rael_video_controls_adv'       => 'yes',
					'rael_youtube_player_controls!' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_vimeo_loop',
			array(
				'label'     => __( 'Loop', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array(
					'rael_content_type'       => 'vimeo',
					'rael_video_controls_adv' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/**
		 *  Modal Popup settings for RAEL Modal Popup
		 */

		$this->start_controls_section(
			'modal-popup',
			array(
				'label' => __( 'Modal Popup', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_responsive_control(
			'rael_modal_width',
			array(
				'label'          => __( 'Modal Popup Width', 'responsive-addons-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', 'em', '%' ),
				'default'        => array(
					'size' => '500',
					'unit' => 'px',
				),
				'tablet_default' => array(
					'size' => '500',
					'unit' => 'px',
				),
				'mobile_default' => array(
					'size' => '300',
					'unit' => 'px',
				),
				'range'          => array(
					'px' => array(
						'min' => 0,
						'max' => 1500,
					),
					'em' => array(
						'min' => 0,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'.raelmodal-{{ID}} .rael-content' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'rael_modal_effect',
			array(
				'label'       => __( 'Modal Appear Effect', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'rael-effect-1',
				'label_block' => true,
				'options'     => array(
					'rael-effect-1'  => __( 'Fade in &amp; Scale', 'responsive-addons-for-elementor' ),
					'rael-effect-2'  => __( 'Slide in (right)', 'responsive-addons-for-elementor' ),
					'rael-effect-3'  => __( 'Slide in (bottom)', 'responsive-addons-for-elementor' ),
					'rael-effect-4'  => __( 'Newspaper', 'responsive-addons-for-elementor' ),
					'rael-effect-5'  => __( 'Fall', 'responsive-addons-for-elementor' ),
					'rael-effect-6'  => __( 'Side Fall', 'responsive-addons-for-elementor' ),
					'rael-effect-8'  => __( '3D Flip (horizontal)', 'responsive-addons-for-elementor' ),
					'rael-effect-9'  => __( '3D Flip (vertical)', 'responsive-addons-for-elementor' ),
					'rael-effect-10' => __( '3D Sign', 'responsive-addons-for-elementor' ),
					'rael-effect-11' => __( 'Super Scaled', 'responsive-addons-for-elementor' ),
					'rael-effect-13' => __( '3D Slit', 'responsive-addons-for-elementor' ),
					'rael-effect-14' => __( '3D Rotate Bottom', 'responsive-addons-for-elementor' ),
					'rael-effect-15' => __( '3D Rotate In Left', 'responsive-addons-for-elementor' ),
					'rael-effect-17' => __( 'Let me in', 'responsive-addons-for-elementor' ),
					'rael-effect-18' => __( 'Make way!', 'responsive-addons-for-elementor' ),
					'rael-effect-19' => __( 'Slip from top', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_overlay_color',
			array(
				'label'     => __( 'Overlay Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0,0,0,0.75)',
				'selectors' => array(
					'.raelmodal-{{ID}} .rael-overlay' => 'background: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 *  Close button settings for RAEL Modal Popup
		 */

		$this->start_controls_section(
			'close_options',
			array(
				'label' => __( 'Close Button', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_close_source',
			array(
				'label'   => __( 'Close As', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'img'  => array(
						'title' => __( 'Image', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-picture-o',
					),
					'icon' => array(
						'title' => __( 'Icon', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-info-circle',
					),
				),
				'default' => 'icon',
			)
		);

		/**
		 * Condition: 'close_source' => 'img'
		 */
		$this->add_control(
			'rael_close_photo',
			array(
				'label'     => __( 'Close Image', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'rael_close_source' => 'img',
				),
			)
		);

		if ( class_exists( 'Elementor\Icons_Manager' ) ) {
			$this->add_control(
				'rael_new_close_icon',
				array(
					'label'            => __( 'Close Icon', 'responsive-addons-for-elementor' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => 'rael_close_icon',
					'default'          => array(
						'value'   => 'fas fa-times',
						'library' => 'fa-solid',
					),
					'condition'        => array(
						'rael_close_source' => 'icon',
					),
					'render_type'      => 'template',
				)
			);
		} else {
			$this->add_control(
				'rael_close_icon',
				array(
					'label'     => __( 'Close Icon', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::ICON,
					'default'   => 'fa fa-close',
					'condition' => array(
						'rael_close_source' => 'icon',
					),
				)
			);
		}

		$this->add_responsive_control(
			'rael_close_icon_size',
			array(
				'label'      => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 20,
				),
				'range'      => array(
					'px' => array(
						'max' => 500,
					),
				),
				'selectors'  => array(
					'.raelmodal-{{ID}} .rael-modal-close' => 'font-size: {{SIZE}}px;line-height: {{SIZE}}px;height: {{SIZE}}px;width: {{SIZE}}px;',
					'.raelmodal-{{ID}} .rael-modal-close i, .raelmodal-{{ID}} .rael-modal-close svg' => 'font-size: {{SIZE}}px;line-height: {{SIZE}}px;height: {{SIZE}}px;width: {{SIZE}}px;',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => $this->get_new_icon_control_name( 'close_icon' ),
							'operator' => '!=',
							'value'    => '',
						),
						array(
							'name'     => 'rael_close_source',
							'operator' => '==',
							'value'    => 'icon',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'rael_close_img_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 20,
				),
				'range'     => array(
					'px' => array(
						'max' => 500,
					),
				),
				'selectors' => array(
					'.raelmodal-{{ID}} .rael-modal-close' => 'font-size: {{SIZE}}px;line-height: {{SIZE}}px;height: {{SIZE}}px;width: {{SIZE}}px;',
				),
				'condition' => array(
					'rael_close_source' => 'img',
				),
			)
		);

		$this->add_control(
			'rael_close_icon_color',
			array(
				'label'      => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '#ffffff',
				'selectors'  => array(
					'.raelmodal-{{ID}} .rael-modal-close i' => 'color: {{VALUE}};',
					'.raelmodal-{{ID}} .rael-modal-close svg' => 'fill: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => $this->get_new_icon_control_name( 'close_icon' ),
							'operator' => '!=',
							'value'    => '',
						),
						array(
							'name'     => 'rael_close_source',
							'operator' => '==',
							'value'    => 'icon',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_icon_position',
			array(
				'label'       => __( 'Image / Icon Position', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'top-right',
				'label_block' => true,
				'options'     => array(
					'top-left'             => __( 'Window - Top Left', 'responsive-addons-for-elementor' ),
					'top-right'            => __( 'Window - Top Right', 'responsive-addons-for-elementor' ),
					'popup-top-left'       => __( 'Popup - Top Left', 'responsive-addons-for-elementor' ),
					'popup-top-right'      => __( 'Popup - Top Right', 'responsive-addons-for-elementor' ),
					'popup-edge-top-left'  => __( 'Popup Edge - Top Left', 'responsive-addons-for-elementor' ),
					'popup-edge-top-right' => __( 'Popup Edge - Top Right', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_esc_keypress',
			array(
				'label'        => __( 'Close on ESC Keypress', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_overlay_click',
			array(
				'label'        => __( 'Close on Overlay Click', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();

		/**
		 *  Display Settings for RAEL Modal Popup
		 */

		$this->start_controls_section(
			'modal',
			array(
				'label' => __( 'Display Settings', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_modal_on',
			array(
				'label'   => __( 'Display Modal On', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'button',
				'options' => array(
					'icon'      => __( 'Icon', 'responsive-addons-for-elementor' ),
					'photo'     => __( 'Image', 'responsive-addons-for-elementor' ),
					'text'      => __( 'Text', 'responsive-addons-for-elementor' ),
					'button'    => __( 'Button', 'responsive-addons-for-elementor' ),
					'custom'    => __( 'Custom Class', 'responsive-addons-for-elementor' ),
					'custom_id' => __( 'Custom ID', 'responsive-addons-for-elementor' ),
					'automatic' => __( 'Automatic', 'responsive-addons-for-elementor' ),
					'via_url'   => __( 'Via URL', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_via_url_message',
			array(
				'type'      => Controls_Manager::RAW_HTML,
				'raw'       => sprintf( '<p style="font-size: 11px;font-style: italic;line-height: 1.4;color: #a4afb7;">%s</p>', __( 'Append the "?rael-modal-action=modal-popup-id" at the end of your URL.', 'responsive-addons-for-elementor' ) ),
				'condition' => array(
					'rael_modal_on' => 'via_url',
				),
			)
		);

		if ( class_exists( 'Elementor\Icons_Manager' ) ) {
			$this->add_control(
				'rael_new_icon',
				array(
					'label'            => __( 'Icon', 'responsive-addons-for-elementor' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => 'rael_icon',
					'default'          => array(
						'value'   => 'fa fa-home',
						'library' => 'fa-solid',
					),
					'condition'        => array(
						'rael_modal_on' => 'icon',
					),
					'render_type'      => 'template',
				)
			);
		} else {
			$this->add_control(
				'rael_icon',
				array(
					'label'     => __( 'Icon', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::ICON,
					'default'   => 'fa fa-home',
					'condition' => array(
						'rael_modal_on' => 'icon',
					),
				)
			);
		}

		$this->add_control(
			'rael_icon_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 60,
				),
				'range'     => array(
					'px' => array(
						'max' => 500,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-modal-action i, {{WRAPPER}} .rael-modal-action svg' => 'font-size: {{SIZE}}px;width: {{SIZE}}px;height: {{SIZE}}px;line-height: {{SIZE}}px;',
				),
				'condition' => array(
					'rael_modal_on' => 'icon',
				),
			)
		);

		$this->add_control(
			'rael_icon_color',
			array(
				'label'     => __( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-modal-action i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-modal-action svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'rael_modal_on' => 'icon',
				),
			)
		);

		$this->add_control(
			'rael_icon_hover_color',
			array(
				'label'     => __( 'Icon Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-modal-action i:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-modal-action svg:hover' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'rael_modal_on' => 'icon',
				),
			)
		);

		$this->add_control(
			'rael_photo',
			array(
				'label'     => __( 'Image', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'rael_modal_on' => 'photo',
				),
			)
		);

		$this->add_control(
			'rael_img_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 60,
				),
				'range'     => array(
					'px' => array(
						'max' => 500,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-modal-action img' => 'width: {{SIZE}}px;',
				),
				'condition' => array(
					'rael_modal_on' => 'photo',
				),
			)
		);

		$this->add_control(
			'rael_modal_text',
			array(
				'label'     => __( 'Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Click Here', 'responsive-addons-for-elementor' ),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'rael_modal_on' => 'text',
				),
			)
		);

		$this->add_control(
			'rael_modal_custom',
			array(
				'label'       => __( 'Class', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Add your custom class without the dot. e.g: my-class', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_modal_on' => 'custom',
				),
			)
		);

		$this->add_control(
			'rael_modal_custom_id',
			array(
				'label'       => __( 'Custom ID', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Add your custom id without the Pound key. e.g: my-id', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_modal_on' => 'custom_id',
				),
			)
		);

		$this->add_control(
			'rael_exit_intent',
			array(
				'label'        => __( 'Exit Intent', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'condition'    => array(
					'rael_modal_on' => 'automatic',
				),
				'selectors'    => array(
					'.raelmodal-{{ID}}' => '',
				),
			)
		);

		$this->add_control(
			'rael_after_second',
			array(
				'label'        => __( 'After Few Seconds', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'condition'    => array(
					'rael_modal_on' => 'automatic',
				),
				'selectors'    => array(
					'.raelmodal-{{ID}}' => '',
				),
			)
		);

		$this->add_control(
			'rael_after_second_value',
			array(
				'label'     => __( 'Load After Seconds', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 1,
				),
				'condition' => array(
					'rael_after_second' => 'yes',
					'rael_modal_on'     => 'automatic',
				),
				'selectors' => array(
					'.raelmodal-{{ID}}' => '',
				),
			)
		);

		$this->add_control(
			'rael_enable_cookies',
			array(
				'label'        => __( 'Enable Cookies', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'condition'    => array(
					'rael_modal_on' => 'automatic',
				),
				'selectors'    => array(
					'.raelmodal-{{ID}}' => '',
				),
			)
		);

		$this->add_control(
			'rael_close_cookie_days',
			array(
				'label'     => __( 'Do Not Show After Closing (days)', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 1,
				),
				'condition' => array(
					'rael_enable_cookies' => 'yes',
					'rael_modal_on'       => 'automatic',
				),
				'selectors' => array(
					'.raelmodal-{{ID}}' => '',
				),
			)
		);

		$this->add_control(
			'rael_btn_text',
			array(
				'label'       => __( 'Button Text', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Click Me', 'responsive-addons-for-elementor' ),
				'placeholder' => __( 'Click Me', 'responsive-addons-for-elementor' ),
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'rael_modal_on' => 'button',
				),
			)
		);

		$this->add_responsive_control(
			'rael_btn_align',
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
				'default'   => 'left',
				'condition' => array(
					'rael_modal_on' => 'button',
				),
				'toggle'    => false,
			)
		);

		$this->add_control(
			'rael_btn_size',
			array(
				'label'     => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'sm',
				'options'   => array(
					'xs' => __( 'Extra Small', 'responsive-addons-for-elementor' ),
					'sm' => __( 'Small', 'responsive-addons-for-elementor' ),
					'md' => __( 'Medium', 'responsive-addons-for-elementor' ),
					'lg' => __( 'Large', 'responsive-addons-for-elementor' ),
					'xl' => __( 'Extra Large', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_modal_on' => 'button',
				),
			)
		);

		$this->add_responsive_control(
			'rael_btn_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-modal-action-wrap a.elementor-button, {{WRAPPER}} .rael-modal-action-wrap .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_modal_on' => 'button',
				),
			)
		);

		if ( class_exists( 'Elementor\Icons_Manager' ) ) {
			$this->add_control(
				'rael_new_btn_icon',
				array(
					'label'            => __( 'Icon', 'responsive-addons-for-elementor' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => 'rael_btn_icon',
					'label_block'      => true,
					'condition'        => array(
						'rael_modal_on' => 'button',
					),
					'render_type'      => 'template',
				)
			);
		} else {
			$this->add_control(
				'rael_btn_icon',
				array(
					'label'       => __( 'Icon', 'responsive-addons-for-elementor' ),
					'type'        => Controls_Manager::ICON,
					'label_block' => true,
					'condition'   => array(
						'rael_modal_on' => 'button',
					),
				)
			);
		}

		$this->add_control(
			'rael_btn_icon_align',
			array(
				'label'      => __( 'Icon Position', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'left',
				'options'    => array(
					'left'  => __( 'Before', 'responsive-addons-for-elementor' ),
					'right' => __( 'After', 'responsive-addons-for-elementor' ),
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => $this->get_new_icon_control_name( 'btn_icon' ),
							'operator' => '!=',
							'value'    => '',
						),
						array(
							'name'     => 'rael_modal_on',
							'operator' => '==',
							'value'    => 'button',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_btn_icon_indent',
			array(
				'label'      => __( 'Icon Spacing', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'max' => 50,
					),
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => $this->get_new_icon_control_name( 'btn_icon' ),
							'operator' => '!=',
							'value'    => '',
						),
						array(
							'name'     => 'rael_modal_on',
							'operator' => '==',
							'value'    => 'button',
						),
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-modal-action-wrap .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .rael-modal-action-wrap .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_all_align',
			array(
				'label'     => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'left',
				'condition' => array(
					'rael_modal_on' => array( 'icon', 'photo', 'text' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-modal-action-wrap' => 'text-align: {{VALUE}};',
				),
				'toggle'    => false,
			)
		);

		$this->end_controls_section();

		/**
		 *  Title Style Section
		 */

		$this->start_controls_section(
			'section_title_typography',
			array(
				'label'     => __( 'Title', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_title!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'rael_title_alignment',
			array(
				'label'     => __( 'Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'left',
				'selectors' => array(
					'.raelmodal-{{ID}} .rael-modal-title-wrap' => 'text-align: {{VALUE}};',
				),
				'toggle'    => false,
			)
		);

		$this->add_responsive_control(
			'rael_title_spacing',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'.raelmodal-{{ID}} .rael-modal-title-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'    => '15',
					'bottom' => '15',
					'left'   => '25',
					'right'  => '25',
					'unit'   => 'px',
				),
			)
		);

		$this->add_control(
			'rael_title_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => array(
					'.raelmodal-{{ID}} .rael-modal-title-wrap .rael-modal-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-modal-title-wrap .rael-modal-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_title_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => array(
					'.raelmodal-{{ID}} .rael-modal-title-wrap' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .rael-modal-title-wrap' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_title_tag',
			array(
				'label'   => __( 'HTML Tag', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1'   => __( 'H1', 'responsive-addons-for-elementor' ),
					'h2'   => __( 'H2', 'responsive-addons-for-elementor' ),
					'h3'   => __( 'H3', 'responsive-addons-for-elementor' ),
					'h4'   => __( 'H4', 'responsive-addons-for-elementor' ),
					'h5'   => __( 'H5', 'responsive-addons-for-elementor' ),
					'h6'   => __( 'H6', 'responsive-addons-for-elementor' ),
					'div'  => __( 'div', 'responsive-addons-for-elementor' ),
					'span' => __( 'span', 'responsive-addons-for-elementor' ),
					'p'    => __( 'p', 'responsive-addons-for-elementor' ),
				),
				'default' => 'h3',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '.raelmodal-{{ID}} .rael-modal-title-wrap .rael-modal-title, {{WRAPPER}} .rael-modal-title-wrap .rael-modal-title',
			)
		);

		$this->end_controls_section();

		/**
		 * Content style Section
		 */

		$this->start_controls_section(
			'section_content_typography',
			array(
				'label' => __( 'Content', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_content_text_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => array(
					'.raelmodal-{{ID}} .rael-content' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-content'       => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_content_type' => 'content',
				),
			)
		);

		$this->add_control(
			'rael_content_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'.raelmodal-{{ID}} .rael-content' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_modal_spacing',
			array(
				'label'      => __( 'Content Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'.raelmodal-{{ID}} .rael-content .rael-modal-content-data' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'    => '25',
					'bottom' => '25',
					'left'   => '25',
					'right'  => '25',
					'unit'   => 'px',
				),
			)
		);

		$this->add_control(
			'rael_vplay_icon_header',
			array(
				'label'      => __( 'Play Icon', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::HEADING,
				'separator'  => 'before',
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_video_autoplay',
							'operator' => '!=',
							'value'    => 'yes',
						),
						array(
							'name'     => 'rael_content_type',
							'operator' => '==',
							'value'    => 'vimeo',
						),
					),
				),
			)
		);

		if ( class_exists( 'Elementor\Icons_Manager' ) ) {
			$this->add_control(
				'rael_new_vimeo_play_icon',
				array(
					'label'            => __( 'Select Icon', 'responsive-addons-for-elementor' ),
					'description'      => __( 'Note: The Upload SVG option is not supported for the Vimeo play icon.', 'responsive-addons-for-elementor' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => 'rael_vimeo_play_icon',
					'default'          => array(
						'value'   => 'fa fa-play-circle',
						'library' => 'fa-solid',
					),
					'render_type'      => 'template',
					'conditions'       => array(
						'relation' => 'and',
						'terms'    => array(
							array(
								'name'     => 'rael_video_autoplay',
								'operator' => '!=',
								'value'    => 'yes',
							),
							array(
								'name'     => 'rael_content_type',
								'operator' => '==',
								'value'    => 'vimeo',
							),
						),
					),
				)
			);
		} else {
			$this->add_control(
				'rael_vimeo_play_icon',
				array(
					'label'      => __( 'Select Icon', 'responsive-addons-for-elementor' ),
					'type'       => Controls_Manager::ICON,
					'default'    => 'fa fa-play-circle',
					'conditions' => array(
						'relation' => 'and',
						'terms'    => array(
							array(
								'name'     => 'rael_video_autoplay',
								'operator' => '!=',
								'value'    => 'yes',
							),
							array(
								'name'     => 'rael_content_type',
								'operator' => '==',
								'value'    => 'vimeo',
							),
						),
					),
				)
			);
		}

		$this->add_control(
			'rael_vplay_size',
			array(
				'label'      => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 72,
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'.raelmodal-{{ID}} .play'        => 'width: {{SIZE}}px; height: {{SIZE}}px;',
					'.raelmodal-{{ID}} .play:before' => 'font-size: {{SIZE}}px; line-height: {{SIZE}}px;',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_video_autoplay',
							'operator' => '!=',
							'value'    => 'yes',
						),
						array(
							'name'     => 'rael_content_type',
							'operator' => '==',
							'value'    => 'vimeo',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_vplay_color',
			array(
				'label'      => __( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => 'rgba( 0,0,0,0.8 )',
				'selectors'  => array(
					'.raelmodal-{{ID}} .play:before' => 'color: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_video_autoplay',
							'operator' => '!=',
							'value'    => 'yes',
						),
						array(
							'name'     => 'rael_content_type',
							'operator' => '==',
							'value'    => 'vimeo',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_yplay_icon_header',
			array(
				'label'      => __( 'Play Icon', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::HEADING,
				'separator'  => 'before',
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_video_autoplay',
							'operator' => '!=',
							'value'    => 'yes',
						),
						array(
							'name'     => 'rael_content_type',
							'operator' => '==',
							'value'    => 'youtube',
						),
					),
				),
			)
		);

		if ( class_exists( 'Elementor\Icons_Manager' ) ) {
			$this->add_control(
				'rael_new_youtube_play_icon',
				array(
					'label'            => __( 'Select Icon', 'responsive-addons-for-elementor' ),
					'description'      => __( 'Note: The Upload SVG option is not supported for the YouTube play icon.', 'responsive-addons-for-elementor' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => 'rael_youtube_play_icon',
					'default'          => array(
						'value'   => 'fa fa-play-circle',
						'library' => 'fa-solid',
					),
					'render_type'      => 'template',
					'conditions'       => array(
						'relation' => 'and',
						'terms'    => array(
							array(
								'name'     => 'rael_video_autoplay',
								'operator' => '!=',
								'value'    => 'yes',
							),
							array(
								'name'     => 'rael_content_type',
								'operator' => '==',
								'value'    => 'youtube',
							),
						),
					),
				)
			);
		} else {
			$this->add_control(
				'rael_youtube_play_icon',
				array(
					'label'      => __( 'Select Icon', 'responsive-addons-for-elementor' ),
					'type'       => Controls_Manager::ICON,
					'default'    => 'fa fa-play-circle',
					'conditions' => array(
						'relation' => 'and',
						'terms'    => array(
							array(
								'name'     => 'rael_video_autoplay',
								'operator' => '!=',
								'value'    => 'yes',
							),
							array(
								'name'     => 'rael_content_type',
								'operator' => '==',
								'value'    => 'youtube',
							),
						),
					),
				)
			);
		}

		$this->add_control(
			'rael_yplay_size',
			array(
				'label'      => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 72,
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'.raelmodal-{{ID}} .play'        => 'width: {{SIZE}}px; height: {{SIZE}}px;',
					'.raelmodal-{{ID}} .play:before' => 'font-size: {{SIZE}}px; line-height: {{SIZE}}px;',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_video_autoplay',
							'operator' => '!=',
							'value'    => 'yes',
						),
						array(
							'name'     => 'rael_content_type',
							'operator' => '==',
							'value'    => 'youtube',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_yplay_color',
			array(
				'label'      => __( 'Icon Color', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => 'rgba( 0,0,0,0.8 )',
				'selectors'  => array(
					'.raelmodal-{{ID}} .play:before' => 'color: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_video_autoplay',
							'operator' => '!=',
							'value'    => 'yes',
						),
						array(
							'name'     => 'rael_content_type',
							'operator' => '==',
							'value'    => 'youtube',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_loader_color',
			array(
				'label'      => __( 'Iframe Loader Color', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => 'rgba( 0,0,0,0.8 )',
				'selectors'  => array(
					'.raelmodal-{{ID}} .rael-loader::before' => 'border: 3px solid {{VALUE}}; border-left-color: transparent;border-right-color: transparent;',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_async_iframe',
							'operator' => '==',
							'value'    => 'yes',
						),
						array(
							'name'     => 'rael_content_type',
							'operator' => '==',
							'value'    => 'iframe',
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'content_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector'  => '.raelmodal-{{ID}} .rael-content .rael-text-editor',
				'separator' => 'before',
				'condition' => array(
					'rael_content_type' => 'content',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Button Style section
		 */

		$this->start_controls_section(
			'section_button_style',
			array(
				'label'     => __( 'Button', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_modal_on' => 'button',
				),
			)
		);

		$this->add_control(
			'rael_btn_html_message',
			array(
				'type'      => Controls_Manager::RAW_HTML,
				'raw'       => sprintf( '<p style="font-size: 11px;font-style: italic;line-height: 1.4;color: #a4afb7;">%s</p>', __( 'To see these changes please turn off the preview setting from Content Tab.', 'responsive-addons-for-elementor' ) ),
				'condition' => array(
					'rael_preview_modal' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'btn_typography',
				'label'     => __( 'Typography', 'responsive-addons-for-elementor' ),
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector'  => '{{WRAPPER}} .rael-modal-action-wrap a.elementor-button, {{WRAPPER}} .rael-modal-action-wrap .elementor-button',
				'condition' => array(
					'rael_modal_on' => 'button',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label'     => __( 'Normal', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_modal_on' => 'button',
				),
			)
		);

		$this->add_control(
			'rael_button_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-modal-action-wrap a.elementor-button, {{WRAPPER}} .rael-modal-action-wrap .elementor-button' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_modal_on' => 'button',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'btn_background_color',
				'label'          => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'types'          => array( 'classic', 'gradient' ),
				'selector'       => '{{WRAPPER}} .rael-modal-action-wrap .elementor-button',
				'separator'      => 'before',
				'condition'      => array(
					'rael_modal_on' => 'button',
				),
				'fields_options' => array(
					'color' => array(
						'global'    => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
					),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label'     => __( 'Hover', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_modal_on' => 'button',
				),
			)
		);

		$this->add_control(
			'rael_btn_hover_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-modal-action-wrap a.elementor-button:hover, {{WRAPPER}} .rael-modal-action-wrap .elementor-button:hover' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_modal_on' => 'button',
				),
			)
		);

		$this->add_control(
			'rael_button_background_hover_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-modal-action-wrap a.elementor-button:hover, {{WRAPPER}} .rael-modal-action-wrap .elementor-button:hover' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_modal_on' => 'button',
				),
			)
		);

		$this->add_control(
			'rael_button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-modal-action-wrap a.elementor-button:hover, {{WRAPPER}} .rael-modal-action-wrap .elementor-button:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_modal_on' => 'button',
				),
			)
		);

		$this->add_control(
			'rael_btn_hover_animation',
			array(
				'label'     => __( 'Hover Animation', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HOVER_ANIMATION,
				'condition' => array(
					'rael_modal_on' => 'button',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'btn_border',
				'label'       => __( 'Border', 'responsive-addons-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .rael-modal-action-wrap .elementor-button',
				'separator'   => 'before',
				'condition'   => array(
					'rael_modal_on' => 'button',
				),
			)
		);

		$this->add_control(
			'rael_btn_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-modal-action-wrap a.elementor-button, {{WRAPPER}} .rael-modal-action-wrap .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_modal_on' => 'button',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_box_shadow',
				'selector'  => '{{WRAPPER}} .rael-modal-action-wrap .elementor-button',
				'condition' => array(
					'rael_modal_on' => 'button',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * CTA Style Section
		 */

		$this->start_controls_section(
			'section_cta_style',
			array(
				'label'     => __( 'Display Text', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_modal_on' => 'text',
				),
			)
		);

		$this->add_control(
			'rael_text_html_message',
			array(
				'type'      => Controls_Manager::RAW_HTML,
				'raw'       => sprintf( '<p style="font-size: 11px;font-style: italic;line-height: 1.4;color: #a4afb7;">%s</p>', __( 'To see these changes please turn off the preview setting from Content Tab.', 'responsive-addons-for-elementor' ) ),
				'condition' => array(
					'rael_preview_modal' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'   => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-modal-action' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_modal_on' => 'text',
				),
			)
		);

		$this->add_control(
			'rael_text_hover_color',
			array(
				'label'     => __( 'Text Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'global'   => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-modal-action:hover' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_modal_on' => 'text',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'cta_text_typography',
				'label'     => __( 'Typography', 'responsive-addons-for-elementor' ),
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector'  => '{{WRAPPER}} .rael-modal-action-wrap .rael-modal-action',
				'condition' => array(
					'rael_modal_on' => 'text',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Return the new icon name.
	 *
	 * @since 1.2.1
	 *
	 * @param string $control_name name of the control.
	 * @return string of the updated control name.
	 */
	public static function get_new_icon_control_name( $control_name ) {
		if ( class_exists( 'Elementor\Icons_Manager' ) ) {
			return 'rael_new_' . $control_name . '[value]';
		} else {
			return 'rael_' . $control_name;
		}
	}
	/**
	 * Retrieves the video embed parameters based on the settings.
	 *
	 * @return array The array of video embed parameters.
	 */
	public function get_video_embed_params() {

		$settings = $this->get_settings();

		$params = array();

		if ( 'vimeo' == $settings['rael_content_type'] ) {

			if ( 'yes' == $settings['rael_video_controls_adv'] && 'yes' == $settings['rael_vimeo_loop'] ) {
				$params['loop'] = 1;
			}

			$params['title']    = 0;
			$params['byline']   = 0;
			$params['portrait'] = 0;
			$params['badge']    = 0;

			if ( 'yes' == $settings['rael_video_autoplay'] ) {
				$params['autoplay'] = 1;
				$params['muted']    = 1;
			} else {
				$params['autoplay'] = 0;
			}
		}

		if ( 'youtube' == $settings['rael_content_type'] ) {
			$youtube_options = array( 'rel', 'controls', 'mute', 'modestbranding' );

			$params['version']     = 3;
			$params['enablejsapi'] = 1;

			$params['autoplay'] = ( 'yes' == $settings['rael_video_autoplay'] ) ? 1 : 0;

			foreach ( $youtube_options as $option ) {

				if ( 'rel' == $option ) {
					$params[ $option ] = ( 'yes' == $settings['rael_youtube_related_videos'] ) ? 1 : 0;
					continue;
				}

				if ( 'controls' == $option ) {
					if ( 'yes' == $settings['rael_youtube_player_controls'] ) {
						$params[ $option ] = 0;
					}
					continue;
				}

				if ( 'yes' == $settings['rael_video_controls_adv'] ) {
					$value             = ( 'yes' == $settings[ 'rael_yt_' . $option ] ) ? 1 : 0;
					$params[ $option ] = $value;
					$params['start']   = $settings['rael_start'];
					$params['end']     = $settings['rael_end'];
				}
			}
		}

		return $params;
	}
	/**
	 * Generates the video URL with parameters for embedding.
	 *
	 * @param array $params   The parameters for the video embed.
	 * @param int   $node_id  The ID of the node.
	 * @return string The generated video URL.
	 */
	public function get_url( $params, $node_id ) {

		$settings = $this->get_settings_for_display();
		$url      = '';

		$url = add_query_arg( $params, $url );

		$url .= ( empty( $params ) ) ? '?' : '&';

		if ( 'vimeo' == $settings['rael_content_type'] ) {

			if ( 'yes' == $settings['rael_video_controls_adv'] ) {
				if ( '' != $settings['rael_start'] ) {

					$time = gmdate( 'H\hi\ms\s', $settings['rael_start'] );
					$url .= '#t=' . $time;
				}
			}
		}

		return $url;
	}
	/**
	 * Generates the HTML code for embedding the video based on settings.
	 *
	 * @param array $settings The settings for the video.
	 * @param int   $node_id  The ID of the node.
	 * @return string The HTML code for embedding the video.
	 */
	public function get_video_embed( $settings, $node_id ) {

		if ( '' == $settings['rael_video_url'] ) {
			return '';
		}

		$url    = apply_filters( 'rael_modal_video_url', $settings['rael_video_url'], $settings );
		$vid_id = '';
		$html   = '<div class="rael-video-wrap">';

		$embed_param = $this->get_video_embed_params();

		$settings       = $this->get_settings_for_display();
		$video_url_data = '';

		$video_url_data = add_query_arg( $embed_param, $video_url_data );

		$video_url_data .= ( empty( $embed_param ) ) ? '?' : '&';

		if ( 'vimeo' == $settings['rael_content_type'] ) {

			if ( 'yes' == $settings['rael_video_controls_adv'] ) {
				if ( '' != $settings['rael_start'] ) {

					$time            = gmdate( 'H\hi\ms\s', $settings['rael_start'] );
					$video_url_data .= '#t=' . $time;
				}
			}
		}

		$params = array();

		$play_icon = '';
		if ( 'youtube' == $settings['rael_content_type'] ) {
			if ( class_exists( 'Elementor\Icons_Manager' ) ) {

				$youtube_migrated = isset( $settings['__fa4_migrated']['rael_new_youtube_play_icon'] );
				$youtube_is_new   = ! isset( $settings['rael_youtube_play_icon'] );

				if ( $youtube_is_new || $youtube_migrated ) {
					$play_icon = isset( $settings['rael_new_youtube_play_icon'] ) ? $settings['rael_new_youtube_play_icon']['value'] : '';
				} else {
					$play_icon = isset( $settings['rael_youtube_play_icon'] ) ? $settings['rael_youtube_play_icon']['value'] : '';
				}
			} else {
				$play_icon = $settings['rael_youtube_play_icon'];
			}
		} else {

			if ( class_exists( 'Elementor\Icons_Manager' ) ) {

				$vimeo_migrated = isset( $settings['__fa4_migrated']['rael_new_vimeo_play_icon'] );
				$vimeo_is_new   = ! isset( $settings['rael_vimeo_play_icon'] );

				if ( $vimeo_is_new || $vimeo_migrated ) {
					$play_icon = isset( $settings['rael_new_vimeo_play_icon'] ) ? $settings['rael_new_vimeo_play_icon']['value'] : '';
				} else {
					$play_icon = isset( $settings['rael_vimeo_play_icon'] ) ? $settings['rael_vimeo_play_icon']['value'] : '';
				}
			} else {
				$play_icon = $settings['rael_vimeo_play_icon'];
			}
		}

		if ( 'youtube' == $settings['rael_content_type'] ) {

			if ( preg_match( '/[\\?\\&]v=([^\\?\\&]+)/', $url, $matches ) ) {
				$vid_id = $matches[1];
			}

			$thumbnail = 'https://i.ytimg.com/vi/' . $vid_id . '/hqdefault.jpg';

			$html .= '<div class="rael-modal-iframe rael-video-player" data-src="youtube" data-id="' . $vid_id . '" data-thumb="' . $thumbnail . '" data-sourcelink="https://www.youtube.com/embed/' . $vid_id . $video_url_data . '" data-play-icon="' . $play_icon . '"></div>';

		} elseif ( 'vimeo' == $settings['rael_content_type'] ) {

			$vid_id = preg_replace( '/[^\/]+[^0-9]|(\/)/', '', rtrim( $url, '/' ) );

			if ( '' != $vid_id && 0 != $vid_id ) {

				$response = wp_remote_get( "https://vimeo.com/api/v2/video/$vid_id.php" );

				if ( is_wp_error( $response ) ) {
					return;
				}
				$vimeo     = maybe_unserialize( $response['body'] );
				$thumbnail = $vimeo[0]['thumbnail_large'];

				$html .= '<div class="rael-modal-iframe rael-video-player" data-src="vimeo" data-id="' . $vid_id . '" data-thumb="' . $thumbnail . '" data-sourcelink="https://player.vimeo.com/video/' . $vid_id . $video_url_data . '" data-play-icon="' . $play_icon . '" ></div>';
			}
		}
		$html .= '</div>';
		return $html;
	}
	/**
	 * Displays the close icon based on the settings.
	 *
	 * @return void
	 */
	protected function show_close_icon() {

		$settings = $this->get_settings_for_display();
		?>

		<span class="rael-modal-close rael-close-icon elementor-clickable rael-close-custom-<?php echo esc_attr( $settings['rael_icon_position'] ); ?>" >
		<?php
		if ( 'icon' == $settings['rael_close_source'] ) {
			if ( class_exists( 'Elementor\Icons_Manager' ) ) {
				$close_migrated = isset( $settings['__fa4_migrated']['rael_new_close_icon'] );
				$close_is_new   = ! isset( $settings['rael_close_icon'] );

				if ( $close_is_new || $close_migrated ) {
					\Elementor\Icons_Manager::render_icon( $settings['rael_new_close_icon'], array( 'aria-hidden' => 'true' ) );
				} elseif ( ! empty( $settings['rael_close_icon'] ) ) {
					?>
					<i class="<?php echo esc_attr( $settings['rael_close_icon'] ); ?>" aria-hidden="true"></i>
					<?php
				}
			} elseif ( ! empty( $settings['rael_close_icon'] ) ) {
				?>
				<i class="<?php echo esc_attr( $settings['rael_close_icon'] ); ?>" aria-hidden="true"></i>
				<?php
			}
		} else {
			?>
			<img class="rael-close-image" src="<?php echo ( isset( $settings['rael_close_photo']['url'] ) ) ? esc_url( $settings['rael_close_photo']['url'] ) : ''; ?>" alt="<?php echo ( isset( $settings['rael_close_photo']['url'] ) ) ? wp_kses_post( Control_Media::get_image_alt( $settings['rael_close_photo'] ) ) : ''; ?>"/>
			<?php
		}
		?>
		</span>
		<?php
	}
	/**
	 * Retrieves the modal content based on settings and content type.
	 *
	 * @param array $settings The settings for the modal.
	 * @param int   $node_id  The ID of the node.
	 * @return string The HTML content for the modal.
	 */
	public function get_modal_content( $settings, $node_id ) {

		$content_type     = $settings['rael_content_type'];
		$dynamic_settings = $this->get_settings_for_display();
		$output_html;

		switch ( $content_type ) {
			case 'content':
				global $wp_embed;
				$output_html = '<div class="rael-text-editor elementor-inline-editing" data-elementor-setting-key="rael_ct_content" data-elementor-inline-editing-toolbar="advanced">' . wpautop( $wp_embed->autoembed( $dynamic_settings['rael_ct_content'] ) ) . '</div>';
				break;
			case 'photo':
				if ( isset( $dynamic_settings['rael_ct_photo']['url'] ) ) {

					$output_html = '<img src="' . $dynamic_settings['rael_ct_photo']['url'] . '" alt="' . Control_Media::get_image_alt( $dynamic_settings['rael_ct_photo'] ) . '" />';
				} else {
					$output_html = '<img src="" alt="" />';
				}
				break;

			case 'video':
				global $wp_embed;
				$output_html = $wp_embed->autoembed( $dynamic_settings['rael_ct_video'] );
				break;
			case 'iframe':
				$_rael_iframe_url = esc_url( $dynamic_settings['rael_iframe_url'] );
				if ( 'yes' == $dynamic_settings['rael_async_iframe'] ) {

					$output_html = '<div class="rael-modal-content-type-iframe" data-src="' . $_rael_iframe_url . '" frameborder="0" allowfullscreen></div>';
				} else {
					$output_html = '<iframe src="' . $_rael_iframe_url . '" class="rael-content-iframe" frameborder="0" width="100%" height="100%" allowfullscreen></iframe>';
				}
				break;
			case 'saved_rows':
				$output_html = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( apply_filters( 'wpml_object_id', $settings['rael_ct_saved_rows'], 'page' ) );
				break;
			case 'saved_modules':
				$output_html = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['rael_ct_saved_modules'] );
				break;
			case 'saved_page_templates':
				$output_html = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['rael_ct_page_templates'] );
				break;
			case 'youtube':
			case 'vimeo':
				$output_html = $this->get_video_embed( $dynamic_settings, $node_id );
				break;
			default:
				break;
		}

		return $output_html;
	}
	/**
	 * Renders the modal component with dynamic settings.
	 *
	 * @return void
	 */
	protected function render() {
		$settings    = $this->get_settings();
		$node_id     = $this->get_id();
		$editor_mode = \Elementor\Plugin::instance()->editor->is_edit_mode();

		$this->add_inline_editing_attributes( 'rael_ct_content', 'advanced' );
		$this->add_inline_editing_attributes( 'rael_title', 'basic' );
		$this->add_inline_editing_attributes( 'rael_modal_text', 'basic' );
		$this->add_inline_editing_attributes( 'rael_btn_text', 'none' );

		$class = ( $editor_mode && 'yes' == $settings['rael_preview_modal'] ) ? 'rael-show-preview' : '';

		$this->add_render_attribute( 'rael-inner-wrapper', 'id', 'modal-' . $node_id );

		$this->add_render_attribute(
			'rael-inner-wrapper',
			'class',
			array(
				'rael-modal',
				'rael-center-modal',
				'rael-modal-custom',
				'rael-modal-' . $settings['rael_content_type'],
				$settings['rael_modal_effect'],
				$class,
				( $editor_mode ) ? 'rael-modal-editor' : '',
				'rael-aspect-ratio-' . $settings['rael_video_ratio'],
			)
		);

		$this->add_render_attribute(
			'rael-parent-wrapper',
			array(
				'id'                    => $this->get_id() . '-overlay',
				'data-trigger-on'       => $settings['rael_modal_on'],
				'data-close-on-esc'     => $settings['rael_esc_keypress'],
				'data-close-on-overlay' => $settings['rael_overlay_click'],
				'data-exit-intent'      => $settings['rael_exit_intent'],
				'data-after-sec'        => $settings['rael_after_second'],
				'data-after-sec-val'    => $settings['rael_after_second_value']['size'],
				'data-cookies'          => $settings['rael_enable_cookies'],
				'data-cookies-days'     => $settings['rael_close_cookie_days']['size'],
				'data-custom'           => $settings['rael_modal_custom'],
				'data-custom-id'        => $settings['rael_modal_custom_id'],
				'data-content'          => $settings['rael_content_type'],
				'data-autoplay'         => $settings['rael_video_autoplay'],
				'data-device'           => ( false != ( stripos( $_SERVER['HTTP_USER_AGENT'], 'iPhone' ) ) ? 'true' : 'false' ), // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				'data-async'            => ( 'yes' == $settings['rael_async_iframe'] ) ? true : false,
			)
		);

		$this->add_render_attribute(
			'rael-parent-wrapper',
			'class',
			array(
				'rael-modal-parent-wrapper',
				'rael-module-content',
				'raelmodal-' . $this->get_id(),
				'rael-aspect-ratio-' . $settings['rael_video_ratio'],
				$settings['_css_classes'] . '-popup',
			)
		);

		$parent_wrapper_attr = $this->get_render_attribute_string( 'rael-parent-wrapper' );
		?>
		<div <?php echo wp_kses_post( $parent_wrapper_attr ); ?>>
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael-inner-wrapper' ) ); ?>>
				<div class="rael-content">
					<?php
					if (
						(
							( 'icon' == $settings['rael_close_source'] && ( ! empty( $settings['rael_close_icon'] ) || ! empty( $settings['rael_new_close_icon'] ) ) ) ||
							( 'img' == $settings['rael_close_source'] && '' != $settings['rael_close_photo']['url'] )
						) &&
						(
							'top-left' != $settings['rael_icon_position'] &&
							'top-right' != $settings['rael_icon_position']
						)
					) {
						$this->show_close_icon();
					}
					if ( '' != $settings['rael_title'] ) {
						?>
					<div class="rael-modal-title-wrap">
						<<?php echo esc_attr( Helper::validate_html_tags( $settings['rael_title_tag'] ) ); ?> class="rael-modal-title elementor-inline-editing" data-elementor-setting-key="rael_title" data-elementor-inline-editing-toolbar="basic"><?php echo wp_kses_post( $this->get_settings_for_display( 'rael_title' ) ); ?></<?php echo esc_attr( Helper::validate_html_tags( $settings['rael_title_tag'] ) ); ?>>
				</div>
				<?php } ?>
				<div class="rael-modal-text rael-modal-content-data clearfix">
					<?php echo do_shortcode( $this->get_modal_content( $settings, $node_id ) ); ?>
				</div>
			</div>
		</div>

		<?php
		if (
			(
				( 'icon' == $settings['rael_close_source'] && ( ! empty( $settings['rael_close_icon'] ) || ! empty( $settings['rael_new_close_icon'] ) ) ) ||
				( 'img' == $settings['rael_close_source'] && '' != $settings['rael_close_photo'] )
			) &&
			(
				'top-left' == $settings['rael_icon_position'] ||
				'top-right' == $settings['rael_icon_position']
			)
		) {
			$this->show_close_icon();
		}
		?>
		<div class="rael-overlay"></div>
		</div>

		<div class="rael-modal-action-wrap">
			<?php $this->render_action() ; ?>
		</div>
		<?php
	}
	/**
	 * Renders the click button for triggering the modal popup.
	 *
	 * @param int   $node_id  The ID of the modal node.
	 * @param array $settings The settings for the modal.
	 * @return void
	 */
	public function render_click_button( $node_id, $settings ) {

		$this->add_render_attribute( 'wrapper', 'class', 'rael-button-wrapper elementor-button-wrapper' );
		$this->add_render_attribute( 'button', 'href', 'javascript:void(0);' );
		$this->add_render_attribute( 'button', 'class', 'rael-trigger elementor-button-link elementor-button elementor-clickable' );

		if ( ! empty( $settings['rael_btn_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['rael_btn_size'] );
		}

		$btn_align_tablet = isset( $settings['rael_btn_align_tablet'] ) ? $settings['rael_btn_align_tablet'] : '';
		$btn_align_mobile = isset( $settings['rael_btn_align_mobile'] ) ? $settings['rael_btn_align_mobile'] : '';

		if ( ! empty( $settings['rael_btn_align'] ) ) {
			$this->add_render_attribute( 'wrapper', 'class', 'elementor-align-' . $settings['rael_btn_align'] );
			$this->add_render_attribute( 'wrapper', 'class', 'elementor-tablet-align-' . $btn_align_tablet );
			$this->add_render_attribute( 'wrapper', 'class', 'elementor-mobile-align-' . $btn_align_mobile );
		}

		if ( $settings['rael_btn_hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['rael_btn_hover_animation'] );
		}

		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'wrapper' ) ); ?>>
			<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'button' ) ); ?> data-modal="<?php echo esc_attr( $node_id ); ?>">
				<?php $this->render_click_button_text(); ?>
			</a>
		</div>
		<?php
	}
	/**
	 * Renders the text content for the click button.
	 *
	 * @return void
	 */
	protected function render_click_button_text() {

		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'content-wrapper', 'class', 'elementor-button-content-wrapper' );
		$this->add_render_attribute(
			'icon-align',
			'class',
			array(
				'elementor-align-icon-' . $settings['rael_btn_icon_align'],
				'elementor-button-icon',
			)
		);

		$this->add_render_attribute(
			'btn-text',
			array(
				'class'                                 => 'elementor-button-text elementor-inline-editing',
				'data-elementor-setting-key'            => 'rael_btn_text',
				'data-elementor-inline-editing-toolbar' => 'none',
			)
		);

		?>
		<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'content-wrapper' ) ); ?>>

			<?php
			if ( class_exists( 'Elementor\Icons_Manager' ) ) {

				$button_migrated = isset( $settings['__fa4_migrated']['rael_new_btn_icon'] );
				$button_is_new   = ! isset( $settings['rael_btn_icon'] );
				?>
				<?php if ( ! empty( $settings['rael_btn_icon'] ) || ! empty( $settings['rael_new_btn_icon'] ) ) : ?>
					<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'icon-align' ) ); ?>>
						<?php
						if ( $button_is_new || $button_migrated ) {
							\Elementor\Icons_Manager::render_icon( $settings['rael_new_btn_icon'], array( 'aria-hidden' => 'true' ) );
						} elseif ( ! empty( $settings['rael_btn_icon'] ) ) {
							?>
							<i class="<?php echo esc_attr( $settings['rael_btn_icon'] ); ?>" aria-hidden="true"></i>
						<?php } ?>
					</span>
				<?php endif; ?>
				<?php
			} elseif ( ! empty( $settings['rael_btn_icon'] ) ) {
				?>
				<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'icon-align' ) ); ?>>
					<i class="<?php echo esc_attr( $settings['btn_icon'] ); ?>" aria-hidden="true"></i>
				</span>
			<?php } ?>
			<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'btn-text' ) ); ?> ><?php echo wp_kses_post( $this->get_settings_for_display( 'rael_btn_text' ) ); ?></span>
		</span>
		<?php
	}
	/**
	 * Renders the action for the modal component based on settings.
	 *
	 * @return void
	 */
	protected function render_action() {

		$settings = $this->get_settings_for_display();

		$editor_mode = \Elementor\Plugin::instance()->editor->is_edit_mode();

		if ( 'button' == $settings['rael_modal_on'] ) {

			$this->render_click_button( $this->get_id(), $settings );

		} elseif (
			(
				'custom' == $settings['rael_modal_on'] ||
				'custom_id' == $settings['rael_modal_on'] ||
				'automatic' == $settings['rael_modal_on'] ||
				'via_url' == $settings['rael_modal_on']
			)
			&&
			$editor_mode
		) {

			?>
			<div class="rael-builder-msg" style="text-align: center;">
				<h5><?php esc_attr_e( 'Modal Popup - ID ', 'responsive-addons-for-elementor' ); ?><?php echo esc_attr( $this->get_id() ); ?></h5>
				<p><?php esc_attr_e( 'Click here to edit the "Modal Popup" settings. This text will not be visible on frontend.', 'responsive-addons-for-elementor' ); ?></p>
			</div>
			<?php

		} else {

			$inner_html = '';

			$this->add_render_attribute(
				'action-wrap',
				'class',
				array(
					'rael-modal-action',
					'elementor-clickable',
					'rael-trigger',
				)
			);

			if ( 'custom' == $settings['rael_modal_on'] ||
				'custom_id' == $settings['rael_modal_on'] ||
				'automatic' == $settings['rael_modal_on'] ||
				'via_url' == $settings['rael_modal_on']
			) {
				$this->add_render_attribute( 'action-wrap', 'class', 'rael-modal-popup-hide' );
			}

			$this->add_render_attribute( 'action-wrap', 'data-modal', $this->get_id() );

			switch ( $settings['rael_modal_on'] ) {
				case 'text':
					$this->add_render_attribute(
						'action-wrap',
						array(
							'data-elementor-setting-key' => 'rael_modal_text',
							'data-elementor-inline-editing-toolbar' => 'basic',
						)
					);

					$this->add_render_attribute( 'action-wrap', 'class', 'elementor-inline-editing' );

					$inner_html = $settings['rael_modal_text'];

					break;

				case 'icon':
					$this->add_render_attribute( 'action-wrap', 'class', 'rael-modal-icon-wrap rael-modal-icon' );

					if ( class_exists( 'Elementor\Icons_Manager' ) ) {
						$icon_migrated = isset( $settings['__fa4_migrated']['rael_new_icon'] );
						$icon_is_new   = ! isset( $settings['rael_icon'] );
						if ( $icon_is_new || $icon_migrated ) {
							ob_start();
							\Elementor\Icons_Manager::render_icon( $settings['rael_new_icon'], array( 'aria-hidden' => 'true' ) );
							$inner_html = ob_get_clean();
						} elseif ( ! empty( $settings['rael_icon'] ) ) {
							$inner_html = '<i class="' . $settings['rael_icon'] . '" aria-hidden="true"></i>';
						}
					} elseif ( ! empty( $settings['rael_icon'] ) ) {
						$inner_html = '<i class="' . $settings['rael_icon'] . '" aria-hidden="true"></i>';
					}

					break;

				case 'photo':
					$this->add_render_attribute( 'action-wrap', 'class', 'rael-modal-photo-wrap' );

					$url = ( isset( $settings['rael_photo']['url'] ) && ! empty( $settings['rael_photo']['url'] ) ) ? $settings['rael_photo']['url'] : '';

					$inner_html = '<img class="rael-modal-photo" src="' . $url . '" alt="' . Control_Media::get_image_alt( $settings['rael_photo'] ) . '">';

					break;
			}
			?>
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'action-wrap' ) ); ?>>
				<?php echo $inner_html;//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<?php
		}
	}
	/**
	 * Defines the content template for the modal component.
	 *
	 * This function is responsible for rendering the structure and layout of the modal component's content.
	 * It can be used within the Elementor editor or frontend to provide a visual representation of the modal's content.
	 *
	 * @return void
	 */
	protected function content_template() {}

	/**
	 * Get Custom help URL
	 *
	 * @since 1.2.0
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/elementor-widgets/docs/modal-popup';
	}

}
