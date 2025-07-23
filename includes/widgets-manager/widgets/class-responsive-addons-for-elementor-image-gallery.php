<?php
/**
 * RAEL Image Gallery Widget
 *
 * @since      1.2.0
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Control_Media;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * RAEL Image Gallery class.
 */
class Responsive_Addons_For_Elementor_Image_Gallery extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve 'RAEL Image Gallery' widget name.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-image-gallery';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve 'RAEL Image Gallery' widget title.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Image Gallery', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve 'RAEL Image Gallery' widget icon.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-image rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the RAEL Image Gallery widget belongs to.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}
	/**
	 * Get the stylesheets required for the widget.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'swiper',
			'e-swiper',	
		);
	}
	/**
	 * Get placeholder image source.
	 *
	 * Retrieve the source of the placeholder image.
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 *
	 * @return string The source of the default placeholder image used by Elementor.
	 */
	public static function get_placeholder_image_src() {

		$placeholder_image = ELEMENTOR_ASSETS_URL . 'images/placeholder.png';

		/**
		 * Get placeholder image source.
		 *
		 * Filters the source of the default placeholder image used by Elementor.
		 *
		 * @since 1.2.0
		 *
		 * @param string $placeholder_image The source of the default placeholder image.
		 */
		$placeholder_image = apply_filters( 'elementor/utils/get_placeholder_image_src', $placeholder_image ); //phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores

		return $placeholder_image;
	}

	/**
	 * Retrieve the list of scripts the image carousel widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array(
			'rael-isotope',
			'imagesloaded',
			'rael-element-resize',
			'rael-fancybox',
			'rael-justified',
		);
	}

	/**
	 * Get image filter options.
	 *
	 * @param bool $inherit Whether to include the 'Inherit' option.
	 *
	 * @return array Image filter options.
	 */
	protected function image_filter_options( $inherit = false ) {

		$inherit_options = array();

		if ( $inherit ) {
			$inherit_options = array(
				'' => __( 'Inherit', 'responsive-addons-for-elementor' ),
			);
		}

		$filter = array(
			'normal'    => __( 'Normal', 'responsive-addons-for-elementor' ),
			'aden'      => __( 'Aden', 'responsive-addons-for-elementor' ),
			'a-1977'    => __( '1977', 'responsive-addons-for-elementor' ),
			'toaster'   => __( 'Toaster', 'responsive-addons-for-elementor' ),
			'poprocket' => __( 'Poprocket', 'responsive-addons-for-elementor' ),
			'inkwell'   => __( 'Inkwell', 'responsive-addons-for-elementor' ),
			'hudson'    => __( 'Hudson', 'responsive-addons-for-elementor' ),
			'willow'    => __( 'Willow', 'responsive-addons-for-elementor' ),
			'earlybird' => __( 'Earlybird', 'responsive-addons-for-elementor' ),
			'perpetua'  => __( 'Perpetua', 'responsive-addons-for-elementor' ),
			'sutro'     => __( 'Sutro', 'responsive-addons-for-elementor' ),
		);

		return array_merge( $inherit_options, $filter );
	}

	/**
	 * Get the control name for a new icon control.
	 *
	 * @param string $control_name Existing control name.
	 *
	 * @return string New icon control name.
	 */
	public static function get_new_icon_control_name( $control_name ) {
		if ( class_exists( 'Elementor\Icons_Manager' ) ) {
			return 'rael_new_' . $control_name . '[value]';
		} else {
			return $control_name;
		}
	}

	/**
	 * Register ImageGallery controls.
	 *
	 * @since 1.2.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'rael_section_content_images',
			array(
				'label' => __( 'Gallery', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_gallery_style',
			array(
				'label'     => __( 'Layout', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'grid',
				'options'   => array(
					'grid'      => __( 'Grid', 'responsive-addons-for-elementor' ),
					'masonry'   => __( 'Masonry', 'responsive-addons-for-elementor' ),
					'justified' => __( 'Justified', 'responsive-addons-for-elementor' ),
					'carousel'  => __( 'Carousel', 'responsive-addons-for-elementor' ),
				),
				'separator' => 'after',
			)
		);

		$this->add_control(
			'rael_wp_gallery',
			array(
				'label'   => '',
				'type'    => Controls_Manager::GALLERY,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$gallery = new Repeater();

		$gallery->add_control(
			'rael_choose_image',
			array(
				'label'   => __( 'Choose Image', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => $this->get_placeholder_image_src(),
				),
			)
		);

		$this->end_controls_section();

		/**
		 *  Content Grid / Masonry / Justified
		 */

		$this->start_controls_section(
			'rael_section_content_grid',
			array(
				'label'     => __( 'Grid / Masonry / Justified', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'rael_gallery_style' => array( 'grid', 'masonry', 'justified' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_gallery_columns',
			array(
				'label'          => __( 'Columns', 'responsive-addons-for-elementor' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '4',
				'tablet_default' => '3',
				'mobile_default' => '2',
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'prefix_class'   => 'rael-img-grid%s__column-',
				'condition'      => array(
					'rael_gallery_style!' => array( 'justified', 'carousel' ),
				),
			)
		);

		$this->add_control(
			'rael_justified_row_height',
			array(
				'label'     => __( 'Row Height', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 120,
				),
				'range'     => array(
					'px' => array(
						'min' => 50,
						'max' => 500,
					),
				),
				'condition' => array(
					'rael_gallery_style' => 'justified',
				),
			)
		);

		$this->add_control(
			'rael_last_row_layout',
			array(
				'label'     => __( 'Last Row Layout', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'nojustify',
				'options'   => array(
					'nojustify' => __( 'No Justify', 'responsive-addons-for-elementor' ),
					'justify'   => __( 'Justify', 'responsive-addons-for-elementor' ),
					'hide'      => __( 'Hide', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_gallery_style' => 'justified',
				),
			)
		);

		$this->add_control(
			'rael_masonry_filters_enable',
			array(
				'label'        => __( 'Filterable Image Gallery', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'condition'    => array(
					'rael_gallery_style' => array( 'grid', 'masonry', 'justified' ),
				),
			)
		);

		$this->add_control(
			'rael_filters_all_text',
			array(
				'label'     => __( '"All" Tab Label', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'All', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_gallery_style'          => array( 'grid', 'masonry', 'justified' ),
					'rael_masonry_filters_enable' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_default_filter_switch',
			array(
				'label'        => __( 'Default Tab on Page Load', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'label_off'    => __( 'First', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Custom', 'responsive-addons-for-elementor' ),
				'condition'    => array(
					'rael_gallery_style'          => array( 'grid', 'masonry', 'justified' ),
					'rael_masonry_filters_enable' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_default_filter_category',
			array(
				'label'     => __( 'Enter Category Name', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array(
					'rael_default_filter_switch'  => 'yes',
					'rael_gallery_style'          => array( 'grid', 'masonry', 'justified' ),
					'rael_masonry_filters_enable' => 'yes',
				),
			)
		);

			$this->add_control(
				'rael_default_filter_document',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %s: documentation */
					'raw'             => sprintf( __( 'Note: Enter the category name that you wish to set as a default on page load. Read %1$s this article %2$s for more information.', 'responsive-addons-for-elementor' ), '<a href="https://cyberchimps.com/docs/widgets/image-gallery/#how-to-display-specific-category-tab-as-a-default-on-page-load" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'rael-editor-doc',
					'condition'       => array(
						'rael_default_filter_switch'  => 'yes',
						'rael_gallery_style'          => array( 'grid', 'masonry', 'justified' ),
						'rael_masonry_filters_enable' => 'yes',
					),
				)
			);

		$this->add_control(
			'rael_responsive_support',
			array(
				'label'        => __( 'Responsive Support', 'responsive-addons-for-elementor' ),
				'description'  => __( 'Enable this option to display Filterable Tabs in a Dropdown on Mobile.', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'condition'    => array(
					'rael_gallery_style'          => array( 'grid', 'masonry', 'justified' ),
					'rael_masonry_filters_enable' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_slider_options',
			array(
				'label'     => __( 'Carousel', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SECTION,
				'condition' => array(
					'rael_gallery_style' => 'carousel',
				),
			)
		);

		$this->add_responsive_control(
			'rael_images_to_show',
			array(
				'label'              => __( 'Images to Show', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 4,
				'tablet_default'     => 3,
				'mobile_default'     => 2,
				'condition'          => array(
					'rael_gallery_style' => 'carousel',
				),
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'rael_images_to_scroll',
			array(
				'label'              => __( 'Images to Scroll', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 1,
				'tablet_default'     => 1,
				'mobile_default'     => 1,
				'condition'          => array(
					'rael_gallery_style' => 'carousel',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_autoplay',
			array(
				'label'              => __( 'Autoplay', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'default'            => '',
				'condition'          => array(
					'rael_gallery_style' => 'carousel',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_autoplay_speed',
			array(
				'label'              => __( 'Autoplay Speed (ms)', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 5000,
				'condition'          => array(
					'rael_autoplay'      => 'yes',
					'rael_gallery_style' => 'carousel',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_pause_on_hover',
			array(
				'label'              => __( 'Pause on Hover', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'default'            => 'yes',
				'condition'          => array(
					'rael_autoplay'      => 'yes',
					'rael_gallery_style' => 'carousel',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_infinite_loop',
			array(
				'label'              => __( 'Infinite Loop', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'default'            => 'yes',
				'condition'          => array(
					'rael_gallery_style' => 'carousel',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_transition_speed',
			array(
				'label'              => __( 'Transition Speed (ms)', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
				'condition'          => array(
					'rael_gallery_style' => 'carousel',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'rael_navigation',
			array(
				'label'              => __( 'Navigation', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'both',
				'options'            => array(
					'both'   => __( 'Arrows and Dots', 'responsive-addons-for-elementor' ),
					'arrows' => __( 'Arrows', 'responsive-addons-for-elementor' ),
					'dots'   => __( 'Dots', 'responsive-addons-for-elementor' ),
					'none'   => __( 'None', 'responsive-addons-for-elementor' ),
				),
				'condition'          => array(
					'rael_gallery_style' => 'carousel',
				),
				'frontend_available' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_content_general',
			array(
				'label' => __( 'Additional Options', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'image',
				'label'   => __( 'Image Size', 'responsive-addons-for-elementor' ),
				'default' => 'medium',
			)
		);
		$this->add_control(
			'rael_click_action',
			array(
				'label'   => __( 'Click Action', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'file',
				'options' => array(
					'lightbox'   => __( 'Lightbox', 'responsive-addons-for-elementor' ),
					'file'       => __( 'Media File', 'responsive-addons-for-elementor' ),
					'attachment' => __( 'Attachment Page', 'responsive-addons-for-elementor' ),
					'custom'     => __( 'Custom Link', 'responsive-addons-for-elementor' ),
					''           => __( 'None', 'responsive-addons-for-elementor' ),
				),
			)
		);
			$this->add_control(
				'rael_click_action_doc',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %s: documentation */
					'raw'             => sprintf( __( 'Learn : %1$s How to assign custom link for images? %2$s', 'responsive-addons-for-elementor' ), '<a href="https://cyberchimps.com/docs/widgets/image-gallery/#add-a-custom-link-to-image" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'rael-editor-doc',
					'condition'       => array(
						'rael_click_action' => 'custom',
					),
				)
			);

		$this->add_control(
			'rael_link_target',
			array(
				'label'     => __( 'Link Target', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '_blank',
				'options'   => array(
					'_self'  => __( 'Same Window', 'responsive-addons-for-elementor' ),
					'_blank' => __( 'New Window', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_click_action' => array( 'file', 'attachment', 'custom' ),
				),
			)
		);
		$this->add_control(
			'rael_gallery_random',
			array(
				'label'   => __( 'Ordering', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''     => __( 'Default', 'responsive-addons-for-elementor' ),
					'rand' => __( 'Random', 'responsive-addons-for-elementor' ),
				),
				'default' => '',
			)
		);

		$this->add_control(
			'rael_gallery_caption',
			array(
				'label'   => __( 'Show Caption', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''         => __( 'Never', 'responsive-addons-for-elementor' ),
					'on-image' => __( 'On Image', 'responsive-addons-for-elementor' ),
					'on-hover' => __( 'On Hover', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_caption_document',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				/* translators: %s: documentation */
				'raw'             => sprintf( __( 'Learn : %1$s How to assign captions for images? %2$s', 'responsive-addons-for-elementor' ), '<a href="https://cyberchimps.com/docs/widgets/image-gallery/#how-to-add-a-caption-for-the-image" target="_blank" rel="noopener">', '</a>' ),
				'content_classes' => 'rael-editor-doc',
				'condition'       => array(
					'rael_gallery_caption!' => '',
				),
			)
		);

		$this->add_control(
			'rael_clickable_caption',
			array(
				'label'        => __( 'Clickable Caption', 'responsive-addons-for-elementor' ),
				'description'  => __( 'Enable this option to make the captions clickable.', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'render_type'  => 'template',
				'condition'    => array(
					'rael_click_action!'    => '',
					'rael_gallery_caption!' => '',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_lightbox_layout',
			array(
				'label'     => __( 'Lightbox', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'rael_click_action' => 'lightbox',
				),
			)
		);

		$this->add_control(
			'rael_lightbox_actions',
			array(
				'label'       => __( 'Lightbox Actions', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => array(
					'zoom'       => __( 'Zoom', 'responsive-addons-for-elementor' ),
					'share'      => __( 'Social Share', 'responsive-addons-for-elementor' ),
					'slideShow'  => __( 'Slideshow', 'responsive-addons-for-elementor' ),
					'fullScreen' => __( 'Full Screen', 'responsive-addons-for-elementor' ),
					'download'   => __( 'Download', 'responsive-addons-for-elementor' ),
					'thumbs'     => __( 'Gallery', 'responsive-addons-for-elementor' ),
				),
				'label_block' => true,
				'render_type' => 'template',
				'multiple'    => true,
			)
		);

			$this->add_control(
				'rael_lightbox_link_doc_1',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %s: documentation */
					'raw'             => sprintf( __( 'Click %1$s here %2$s to learn more about this.', 'responsive-addons-for-elementor' ), '<a href="https://cyberchimps.com/docs/widgets/image-gallery/#lightbox-image-click-action" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'rael-editor-doc',
				)
			);

		$this->add_control(
			'rael_show_caption_lightbox',
			array(
				'label'        => __( 'Show Caption Below Image', 'responsive-addons-for-elementor' ),
				'description'  => __( 'Enable this option to display the caption under the image in Lightbox.', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'render_type'  => 'template',
			)
		);
			$this->add_control(
				'rael_lightbox_caption_doc',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %s: documentation */
					'raw'             => sprintf( __( 'Learn : %1$s How to assign captions for images? %2$s', 'responsive-addons-for-elementor' ), '<a href="https://cyberchimps.com/docs/widgets/image-gallery/#how-to-add-a-caption-for-the-image" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'rael-editor-doc',
					'condition'       => array(
						'rael_show_caption_lightbox' => 'yes',
					),
				)
			);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'lightbox_typography',
				'label'     => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector'  => '.rael-fancybox-gallery-{{ID}} .fancybox-caption',
				'condition' => array(
					'rael_show_caption_lightbox' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_lightbox_text_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.rael-fancybox-gallery-{{ID}} .fancybox-caption,
						.rael-fancybox-gallery-{{ID}} .fancybox-caption a' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_show_caption_lightbox' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_lightbox_margin_bottom',
			array(
				'label'     => __( 'Caption Bottom Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'.rael-fancybox-gallery-{{ID}} .fancybox-caption' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_show_caption_lightbox' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_design_layout',
			array(
				'label' => __( 'Layout', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_column_gap',
			array(
				'label'     => __( 'Columns Gap', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 0,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'condition' => array(
					'rael_gallery_style!' => 'justified',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-img-gallery-wrap .rael-grid-item' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .swiper-wrapper' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .swiper-wrapper .swiper-slide' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 ); margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
				),
			)
		);

		$this->add_responsive_control(
			'rael_row_gap',
			array(
				'label'     => __( 'Rows Gap', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 20,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-img-gallery-wrap .rael-grid-item-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_gallery_style' => array( 'grid', 'masonry' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_image_spacing',
			array(
				'label'     => __( 'Image Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 3,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'condition' => array(
					'rael_gallery_style' => 'justified',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-img-justified-wrap .rael-grid-item-content' => 'margin: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_images_valign',
			array(
				'label'     => __( 'Image Vertical</br>Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'flex-start',
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Top', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center'     => array(
						'title' => __( 'Middle', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end'   => array(
						'title' => __( 'Bottom', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'condition' => array(
					'rael_gallery_style' => 'grid',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-img-gallery-wrap .rael-grid-item' => 'align-items: {{VALUE}}; display: inline-grid;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_design_thumbnail',
			array(
				'label' => __( 'Thumbnail', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->start_controls_tabs( 'thumb_style' );

		$this->start_controls_tab(
			'rael_thumb_style_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_image_scale',
			array(
				'label'     => __( 'Scale', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 1,
						'max'  => 2,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-grid-img-thumbnail img' => 'transform: scale({{SIZE}});',
				),
			)
		);

		$this->add_control(
			'rael_image_opacity',
			array(
				'label'     => __( 'Opacity (%)', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 1,
				),
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-grid-img-thumbnail img' => 'opacity: {{SIZE}}',
				),
			)
		);

		$this->add_control(
			'rael_image_filter',
			array(
				'label'        => __( 'Image Effect', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'normal',
				'options'      => $this->image_filter_options(),
				'prefix_class' => 'rael-ins-',
			)
		);

		$this->add_control(
			'rael_overlay_background_color',
			array(
				'label'     => __( 'Overlay Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-grid-img-overlay' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_image_style_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_image_scale_hover',
			array(
				'label'     => __( 'Scale', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 1,
						'max'  => 2,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-grid-gallery-img:hover .rael-grid-img-thumbnail img' => 'transform: scale({{SIZE}});',
				),
			)
		);

		$this->add_control(
			'rael_image_opacity_hover',
			array(
				'label'     => __( 'Opacity (%)', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 1,
				),
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-grid-gallery-img:hover .rael-grid-img-thumbnail img' => 'opacity: {{SIZE}}',
				),
			)
		);

		$this->add_control(
			'rael_image_filter_hover',
			array(
				'label'        => __( 'Image Effect', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => '',
				'options'      => $this->image_filter_options( true ),
				'prefix_class' => 'rael-ins-hover-',
			)
		);

		$this->add_control(
			'rael_overlay_background_color_hover',
			array(
				'label'     => __( 'Overlay Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-grid-gallery-img:hover .rael-grid-img-overlay' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_overlay_image_type',
			array(
				'label'   => __( 'Overlay Icon', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'photo' => array(
						'title' => __( 'Image', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-regular fa-image',
					),
					'icon'  => array(
						'title' => __( 'Font Icon', 'responsive-addons-for-elementor' ),
						'icon'  => 'fa fa-info-circle',
					),
				),
				'default' => '',
				'toggle'  => true,
			)
		);

		if ( class_exists( 'Elementor\Icons_Manager' ) ) {
			$this->add_control(
				'rael_new_overlay_icon_hover',
				array(
					'label'            => __( 'Select Overlay Icon', 'responsive-addons-for-elementor' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => 'overlay_icon_hover',
					'default'          => array(
						'value'   => 'fa fa-search',
						'library' => 'fa-solid',
					),
					'condition'        => array(
						'rael_overlay_image_type' => 'icon',
					),
				)
			);
		} else {
			$this->add_control(
				'rael_overlay_icon_hover',
				array(
					'label'     => __( 'Select Overlay Icon', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::ICON,
					'default'   => 'fa fa-search',
					'condition' => array(
						'rael_overlay_image_type' => 'icon',
					),
				)
			);
		}

		$this->add_control(
			'rael_overlay_icon_color_hover',
			array(
				'label'      => __( 'Overlay Icon Color', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => $this->get_new_icon_control_name( 'overlay_icon_hover' ),
							'operator' => '!=',
							'value'    => '',
						),
						array(
							'name'     => 'rael_overlay_image_type',
							'operator' => '==',
							'value'    => 'icon',
						),
					),
				),
				'default'    => '#ffffff',
				'selectors'  => array(
					'{{WRAPPER}} .rael-grid-gallery-img .rael-grid-img-overlay i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-grid-gallery-img .rael-grid-img-overlay svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_overlay_icon_size_hover',
			array(
				'label'      => __( 'Overlay Icon Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => 40,
					'unit' => 'px',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => $this->get_new_icon_control_name( 'overlay_icon_hover' ),
							'operator' => '!=',
							'value'    => '',
						),
						array(
							'name'     => 'rael_overlay_image_type',
							'operator' => '==',
							'value'    => 'icon',
						),
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-grid-gallery-img .rael-grid-img-overlay i,
								{{WRAPPER}} .rael-grid-gallery-img .rael-grid-img-overlay svg' => 'font-size: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'rael_overlay_image_hover',
			array(
				'label'     => __( 'Overlay Image', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => $this->get_placeholder_image_src(),
				),
				'condition' => array(
					'rael_overlay_image_type' => 'photo',
				),
			)
		);
		$this->add_responsive_control(
			'rael_overlay_image_size_hover',
			array(
				'label'      => __( 'Overlay Image Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 2000,
					),
				),
				'default'    => array(
					'size' => 50,
					'unit' => 'px',
				),
				'condition'  => array(
					'rael_overlay_image_type' => 'photo',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-grid-gallery-img .rael-grid-img-overlay img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_design_caption',
			array(
				'label'     => __( 'Caption / Description', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_gallery_caption!' => '',
				),
			)
		);
		$this->add_control(
			'rael_caption_alignment',
			array(
				'label'       => __( 'Text Alignment', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
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
				'default'     => 'center',
				'selectors'   => array(
					'{{WRAPPER}} .rael-img-gallery-wrap .rael-grid-img-caption' => 'text-align: {{VALUE}};',
				),
				'condition'   => array(
					'rael_gallery_caption!' => '',
				),
			)
		);
		$this->add_control(
			'rael_caption_valign',
			array(
				'label'        => __( 'Vertical Alignment', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'default'      => 'bottom',
				'options'      => array(
					'top'    => array(
						'title' => __( 'Top', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'condition'    => array(
					'rael_gallery_caption!' => '',
				),
				'prefix_class' => 'rael-img-caption-valign-',
			)
		);

		$this->add_control(
			'rael_caption_text_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-grid-img-caption .rael-grid-caption-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-img-description' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_gallery_caption!' => '',
				),
			)
		);

		$this->add_control(
			'rael_caption_background_color',
			array(
				'label'     => __( 'Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-grid-img-caption' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_gallery_caption!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'rael_caption_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-grid-img-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_gallery_caption!' => '',
				),
			)
		);

		$this->add_control(
			'rael_caption_height',
			array(
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ) . ' (%)',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-grid-img-caption' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_gallery_caption!' => '',
				),
			)
		);

		$this->add_control(
			'rael_caption',
			array(
				'label'     => __( 'Caption', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_gallery_caption!' => '',
				),
			)
		);

		$this->add_control(
			'rael_caption_tag',
			array(
				'label'     => __( 'HTML Tag', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h1'  => 'H1',
					'h2'  => 'H2',
					'h3'  => 'H3',
					'h4'  => 'H4',
					'h5'  => 'H5',
					'h6'  => 'H6',
					'div' => 'div',
				),
				'default'   => 'h4',
				'condition' => array(
					'rael_gallery_caption!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'caption_typography',
				'label'     => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .rael-grid-img-caption .rael-grid-caption-text',
				'condition' => array(
					'rael_gallery_caption!' => '',
				),
			)
		);

		$this->add_control(
			'rael_description',
			array(
				'label'     => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_gallery_caption!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'description_typography',
				'label'     => __( 'Typography', 'responsive-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .rael-img-description',
				'condition' => array(
					'rael_gallery_caption!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'rael_caption_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-img-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_gallery_caption!' => '',
				),
			)
		);

		$this->add_control(
			'rael_caption_enable_animations',
			array(
				'label'        => __( 'Enable Animations', 'responsive-addons-for-elementor' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Enabled', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Disabled', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'    => 'before',
				'condition'    => array(
					'rael_gallery_caption' => 'on-hover',
				),
			)
		);

		$this->add_control(
			'rael_caption_animation',
			array(
				'label'     => __( 'Hover Animation', 'responsive-addons-for-elementor' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'slide-up',
				'options'   => array(
					''         => __( 'None', 'responsive-addons-for-elementor' ),
					'slide-up' => __( 'Slide Up', 'responsive-addons-for-elementor' ),
					'fade-in'  => __( 'Fade In', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_gallery_caption'           => 'on-hover',
					'rael_caption_enable_animations' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_caption_animation_speed',
			array(
				'label'     => __( 'Animation Speed (ms)', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1000,
				'step'      => 100,
				'condition' => array(
					'rael_gallery_caption'           => 'on-hover',
					'rael_caption_enable_animations' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_style_navigation',
			array(
				'label'     => __( 'Navigation', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_navigation'    => array( 'arrows', 'dots', 'both' ),
					'rael_gallery_style' => 'carousel',
				),
			)
		);

		$this->add_control(
			'rael_heading_style_arrows',
			array(
				'label'     => __( 'Arrows', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_navigation'    => array( 'arrows', 'both' ),
					'rael_gallery_style' => 'carousel',
				),
			)
		);

		$this->add_control(
			'rael_arrows_position',
			array(
				'label'        => __( 'Arrows Position', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'outside',
				'options'      => array(
					'inside'  => __( 'Inside', 'responsive-addons-for-elementor' ),
					'outside' => __( 'Outside', 'responsive-addons-for-elementor' ),
				),
				'prefix_class' => 'rael-img-carousel-arrow-',
				'condition'    => array(
					'rael_navigation'    => array( 'arrows', 'both' ),
					'rael_gallery_style' => 'carousel',
				),
			)
		);

		$this->add_control(
			'rael_arrows_size',
			array(
				'label'     => __( 'Arrows Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 20,
						'max' => 60,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .img-carousel-buttons' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_navigation'    => array( 'arrows', 'both' ),
					'rael_gallery_style' => 'carousel',
				),
			)
		);

		$this->add_responsive_control(
			'rael_carousel_buttons_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .img-carousel-buttons' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_navigation'    => array( 'arrows', 'both' ),
					'rael_gallery_style' => 'carousel',
				),
			)
		);

		$this->add_control(
			'rael_carousel_buttons_border_radius',
			array(
				'label'     => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .img-carousel-buttons' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_navigation'    => array( 'arrows', 'both' ),
					'rael_gallery_style' => 'carousel',
				),
				'separator' => 'after',
			)
		);

		$this->start_controls_tabs( 'rael_carousel_buttons' );

		$this->start_controls_tab( 'rael_carousel_buttons_normal', array( 'label' => __( 'Normal', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'rael_carousel_buttons_normal_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#91ADC6',
				'selectors' => array(
					'{{WRAPPER}} .img-carousel-buttons' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_navigation'    => array( 'arrows', 'both' ),
					'rael_gallery_style' => 'carousel',
				),
			)
		);

		$this->add_control(
			'rael_carousel_buttons_normal_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .img-carousel-buttons' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_navigation'    => array( 'arrows', 'both' ),
					'rael_gallery_style' => 'carousel',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'rael_carousel_buttons_hover', array( 'label' => __( 'Hover', 'responsive-addons-for-elementor' ) ) );

		$this->add_control(
			'rael_carousel_buttons_hover_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#AB4F19',
				'selectors' => array(
					'{{WRAPPER}} .img-carousel-buttons:hover' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_navigation'    => array( 'arrows', 'both' ),
					'rael_gallery_style' => 'carousel',
				),
			)
		);

		$this->add_control(
			'rael_carousel_buttons_hover_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .img-carousel-buttons:hover' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_navigation'    => array( 'arrows', 'both' ),
					'rael_gallery_style' => 'carousel',
				),
			)
		);

		$this->add_control(
			'rael_carousel_buttons_transition_speed',
			array(
				'label'     => __( 'Transition Speed', 'responsive-addons-for-elementor' ) . ' (ms) ',
				'type'      => Controls_Manager::NUMBER,
				'default'   => 500,
				'step'      => 100,
				'selectors' => array(
					'{{WRAPPER}} .img-carousel-buttons' => 'transition-duration: {{VALUE}}ms;',
				),
				'condition' => array(
					'rael_navigation'    => array( 'arrows', 'both' ),
					'rael_gallery_style' => 'carousel',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'rael_heading_style_dots',
			array(
				'label'     => __( 'Dots', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_navigation'    => array( 'dots', 'both' ),
					'rael_gallery_style' => 'carousel',
				),
			)
		);

		$this->add_control(
			'rael_dots_size',
			array(
				'label'     => __( 'Dots Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 5,
						'max' => 15,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-container-horizontal .swiper-pagination-progressbar' => 'height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-pagination-fraction' => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'rael_navigation'    => array( 'dots', 'both' ),
					'rael_gallery_style' => 'carousel',
				),
			)
		);

		$this->add_control(
			'rael_dots_color',
			array(
				'label'     => __( 'Dots Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_navigation'    => array( 'dots', 'both' ),
					'rael_gallery_style' => 'carousel',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'rael_section_style_cat_filters',
			array(
				'label'     => __( 'Filterable Tabs', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_gallery_style'          => array( 'grid', 'masonry', 'justified' ),
					'rael_masonry_filters_enable' => 'yes',
				),
			)
		);
		$this->add_responsive_control(
			'rael_cat_filter_align',
			array(
				'label'        => __( 'Tab Alignment', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
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
				'default'      => 'center',
				'toggle'       => false,
				'render_type'  => 'template',
				'prefix_class' => 'rael%s-gallery-filter-align-',
				'selectors'    => array(
					'{{WRAPPER}} .rael-gallery-parent .rael-masonry-filters' => 'text-align: {{VALUE}};',
				),
				'condition'    => array(
					'rael_gallery_style'          => array( 'grid', 'masonry', 'justified' ),
					'rael_masonry_filters_enable' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'all_typography',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'condition' => array(
					'rael_gallery_style'          => array( 'grid', 'masonry', 'justified' ),
					'rael_masonry_filters_enable' => 'yes',
				),
				'selector'  => '{{WRAPPER}} .rael-gallery-parent .rael-masonry-filters .rael-masonry-filter,{{WRAPPER}} .rael-img-gallery-tabs-dropdown .rael-filters-dropdown-button',
			)
		);
		$this->add_responsive_control(
			'rael_cat_filter_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-gallery-parent .rael-masonry-filters .rael-masonry-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_gallery_style'          => array( 'grid', 'masonry', 'justified' ),
					'rael_masonry_filters_enable' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_cat_filter_bet_spacing',
			array(
				'label'     => __( 'Spacing Between Tabs', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-gallery-parent .rael-masonry-filters .rael-masonry-filter' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}} .rael-gallery-parent .rael-img-gallery-tabs-dropdown .rael-masonry-filters .rael-masonry-filter' => 'margin-left: 0px; margin-right: 0px;',
				),
				'condition' => array(
					'rael_gallery_style'          => array( 'grid', 'masonry', 'justified' ),
					'rael_masonry_filters_enable' => 'yes',
				),
			)
		);
		$this->add_responsive_control(
			'rael_cat_filter_spacing',
			array(
				'label'     => __( 'Tabs Bottom Spacing', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-gallery-parent .rael-masonry-filters' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'rael_gallery_style'          => array( 'grid', 'masonry', 'justified' ),
					'rael_masonry_filters_enable' => 'yes',
				),
				'separator' => 'after',
			)
		);

		$this->start_controls_tabs( 'cat_filters_tabs_style' );

		$this->start_controls_tab(
			'rael_cat_filters_normal',
			array(
				'label'     => __( 'Normal', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_gallery_style'          => array( 'grid', 'masonry', 'justified' ),
					'rael_masonry_filters_enable' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_cat_filter_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-img-gallery-tabs-dropdown .rael-filters-dropdown-button, {{WRAPPER}} .rael-gallery-parent .rael-masonry-filters .rael-masonry-filter' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_gallery_style'          => array( 'grid', 'masonry', 'justified' ),
					'rael_masonry_filters_enable' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_cat_filter_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-gallery-parent .rael-masonry-filters .rael-masonry-filter, {{WRAPPER}} .rael-gallery-parent .rael-masonry-filters .rael-filters-dropdown-button' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_gallery_style'          => array( 'grid', 'masonry', 'justified' ),
					'rael_masonry_filters_enable' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'cat_filter_border',
				'label'     => __( 'Border', 'responsive-addons-for-elementor' ),
				'selector'  => '{{WRAPPER}} .rael-gallery-parent .rael-masonry-filters .rael-masonry-filter, {{WRAPPER}} .rael-gallery-parent .rael-masonry-filters .rael-filters-dropdown-button',
				'condition' => array(
					'rael_gallery_style'          => array( 'grid', 'masonry', 'justified' ),
					'rael_masonry_filters_enable' => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_cat_filters_hover',
			array(
				'label'     => __( 'Hover', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_gallery_style'          => array( 'grid', 'masonry', 'justified' ),
					'rael_masonry_filters_enable' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_cat_filter_hover_color',
			array(
				'label'     => __( 'Text Active / Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-gallery-parent .rael-masonry-filters .rael-masonry-filter:hover, {{WRAPPER}} .rael-gallery-parent .rael-masonry-filters .rael-current' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_gallery_style'          => array( 'grid', 'masonry', 'justified' ),
					'rael_masonry_filters_enable' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_cat_filter_bg_hover_color',
			array(
				'label'     => __( 'Background Active / Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-gallery-parent .rael-masonry-filters .rael-masonry-filter:hover, {{WRAPPER}} .rael-gallery-parent .rael-masonry-filters .rael-current' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_gallery_style'          => array( 'grid', 'masonry', 'justified' ),
					'rael_masonry_filters_enable' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_cat_filter_border_hover_color',
			array(
				'label'     => __( 'Border Hover Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => array(
					'{{WRAPPER}} .rael-gallery-parent .rael-masonry-filters .rael-masonry-filter:hover, {{WRAPPER}} .rael-gallery-parent .rael-masonry-filters .rael-current' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'rael_gallery_style'             => array( 'grid', 'masonry', 'justified' ),
					'rael_masonry_filters_enable'    => 'yes',
					'rael_cat_filter_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	/**
	 * Get image carousel settings.
	 *
	 * @param array $gallery Image gallery data.
	 *
	 * @return void
	 */
	public function get_image_carousel_settings( $gallery ) {

		$settings = $this->get_settings();

		if ( 'carousel' !== $settings['rael_gallery_style'] ) {
			return;
		}
		$is_rtl       = is_rtl();
		$direction    = $is_rtl ? 'rtl' : 'ltr';
		$show_arrows  = ( in_array( $settings['rael_navigation'], array( 'arrows', 'both' ), true ) );
		$show_dots    = ( in_array( $settings['rael_navigation'], array( 'dots', 'both' ), true ) );
		$images_count = count( $settings['rael_wp_gallery'] );
		?>
		<div class="swiper-gallery">
			<div class="swiper<?php echo esc_attr( RAEL_SWIPER_CONTAINER ); ?> gallery-carousel rael-caption-<?php echo esc_attr( $settings['rael_gallery_caption'] ); ?> swiper-gallery" dir="<?php echo esc_attr( $direction ); ?>">
				<div class="swiper-wrapper rael-img-gallery-wrap">
					<?php $this->print_image_gallery( $gallery ); ?>
				</div>
				<?php if ( 1 < $images_count ) { ?>
					<?php if ( $show_dots ) { ?>
						<div class="swiper-pagination"></div>
					<?php } ?>
					<?php if ( $show_arrows ) { ?>
						<div class="carousel-button-prev img-carousel-buttons">
							<i class="eicon-chevron-left" aria-hidden="true"></i>
							<span class="elementor-screen-only"><?php esc_html_e( 'Previous', 'responsive-addons-for-elementor' ); ?></span>
						</div>
						<div class="carousel-button-next img-carousel-buttons">
							<i class="eicon-chevron-right" aria-hidden="true"></i>
							<span class="elementor-screen-only"><?php esc_html_e( 'Next', 'responsive-addons-for-elementor' ); ?></span>
						</div>
						<?php
					}
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Print thumbnail image.
	 *
	 * @param array $image Image data.
	 *
	 * @return string Thumbnail image HTML.
	 */
	protected function print_thumbnail_image( $image ) {

		$settings                = $this->get_settings();
		$settings['image_index'] = $image;
		$click_action            = $settings['rael_click_action'];
		$image_wrap_tag          = 'figure';
		if ( '' !== $click_action ) {
			$image_wrap_tag = 'a';
		}
		$output  = '<div class="rael-grid-img-thumbnail rael-ins-target">';
		$output .= Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'image_index' );

		$output .= '</div>';
		return $output;
	}

	/**
	 * Print overlay image.
	 *
	 * @return string Overlay image HTML.
	 */
	protected function print_overlay_image() {

		$settings = $this->get_settings_for_display();

		$output = '<div class="rael-grid-img-overlay">';

		if ( 'icon' === $settings['rael_overlay_image_type'] ) {
			if ( class_exists( 'Elementor\Icons_Manager' ) ) {
				if ( ! isset( $settings['rael_overlay_icon_hover'] ) && ! \Elementor\Icons_Manager::is_migration_allowed() ) {
					// add old default.
					$settings['rael_overlay_icon_hover'] = 'fa fa-search';
				}

				$has_icon = ! empty( $settings['rael_overlay_icon_hover'] );

				if ( ! $has_icon && ! empty( $settings['rael_new_overlay_icon_hover']['value'] ) ) {
					$has_icon = true;
				}

				$migrated = isset( $settings['__fa4_migrated']['rael_new_overlay_icon_hover'] );
				$is_new   = ! isset( $settings['rael_overlay_icon_hover'] ) && \Elementor\Icons_Manager::is_migration_allowed();

				if ( $has_icon ) {
					$output .= '<span class="rael-overlay-icon">';

					if ( $is_new || $migrated ) {
						ob_start();
						\Elementor\Icons_Manager::render_icon( $settings['rael_new_overlay_icon_hover'], array( 'aria-hidden' => 'true' ) );
						$output .= ob_get_clean();
					} elseif ( ! empty( $settings['rael_overlay_icon_hover'] ) ) {
						$output .= '<i class="' . $settings['rael_overlay_icon_hover'] . '" aria-hidden="true"></i>';
					}
					$output .= '</span>';
				}
			} else {
				$output .= '<span class="rael-overlay-icon">';
				$output .= '<i class="' . $settings['rael_overlay_icon_hover'] . '" aria-hidden="true"></i>';
				$output .= '</span>';
			}
		} elseif ( 'photo' === $settings['rael_overlay_image_type'] ) {
			if ( ! empty( $settings['rael_overlay_image_hover']['url'] ) ) {
				$output .= '<img class="rael-overlay-img" src="' . $settings['rael_overlay_image_hover']['url'] . '" alt="' . Control_Media::get_image_alt( $settings['rael_overlay_image_hover'] ) . '">';
			}
		}

		$output .= '</div>';

		return $output;
	}

	/**
	 * Print image caption.
	 *
	 * @param array $image Image data.
	 *
	 * @return string Image caption HTML.
	 */
	protected function print_image_caption( $image ) {

		$settings = $this->get_settings();

		if ( '' === $settings['rael_gallery_caption'] || ! $image['caption'] ) {
			return;
		}

		$output  = '<div class="rael-grid-img-caption ' . $settings['rael_caption_animation'] . ' ">';
		$output .= '<' . Helper::validate_html_tags( $settings['rael_caption_tag'] ) . ' class="rael-grid-caption-text">';
		$output .= esc_html__( $image['caption'] );
		$output .= '</' . Helper::validate_html_tags( $settings['rael_caption_tag'] ) . '>';
		$output .= '<p class="rael-img-description">' . $image['description'] . '</p>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * Print widget styles.
	 *
	 * @return void
	 */
	protected function print_styles() {
		$settings        = $this->get_settings();
		$caption_tag     = $settings['rael_caption_tag'];
		$animation_name  = $settings['rael_caption_animation'];
		$animation_speed = $settings['rael_caption_animation_speed'];
		?>
		<style type="text/css">
			<?php echo '.rael-grid-item-content:hover .',esc_html( $animation_name ); ?> > p, /* variable escaped before hand. */
			<?php echo '.rael-grid-item-content:hover .',esc_html( $animation_name ); ?> > <?php echo esc_html( Helper::validate_html_tags( $caption_tag ) ); ?>{
				animation:
					<?php echo esc_html( $animation_name ),'-in'; ?>
					<?php echo esc_html( $animation_speed ),'ms'; ?>
					ease-out;
			}
			<?php echo '.rael-grid-item-content:not( :hover ) .',esc_html( $animation_name ); ?> > p,
			<?php echo '.rael-grid-item-content:not( :hover ) .',esc_html( $animation_name ); ?> > <?php echo esc_html( Helper::validate_html_tags( $caption_tag ) ); ?>{
				animation:
					<?php echo esc_html( $animation_name ),'-out'; ?>
					<?php echo esc_html( $animation_speed ),'ms'; ?>
					ease-out;
			}
			/* Slide up animation */
			@keyframes slide-up-in {
				from {
					transform: translateY(40%);
				}
				to {
					transform: translateY(0%);
				}
			}
			@keyframes slide-up-out {
				from {
					transform: translateY(0%);
				}
				to {
					transform: translateY(40%);
				}
			}

			/* Fade in animation */
			@keyframes fade-in-in {
				from {
					opacity: 0;
				}
				to {
					opacity: 1;
				}
			}
			@keyframes fade-in-out {
				from {
					opacity: 1;
				}
				to {
					opacity: 0;
				}
			}
		</style>
		<?php
	}

	/**
	 * Print widget script.
	 *
	 * @return void
	 */
	protected function print_script() {
		?>
		<script type="text/javascript">
	jQuery( document ).ready( function( $ ) {

		$('.rael-img-grid-masonry-wrap').each(function () {

			var $node_id    = '<?php echo esc_attr( $this->get_id() ); ?>';
			var scope       = $( '[data-id="' + $node_id + '"]' );
			var selector    = $(this);

			if ( selector.closest( scope ).length < 1 ) {
				return;
			}

			var $justified_selector = scope.find('.rael-img-justified-wrap');
			var row_height  = $justified_selector.data( 'rowheight' );
			var lastrow     = $justified_selector.data( 'lastrow' );
			var layoutMode = 'fitRows';
			var filter_cat;

			if ( selector.hasClass('rael-masonry') ) {
				layoutMode = 'masonry';
			}

			var filters = scope.find('.rael-masonry-filters');
			var def_cat = '*';

			if ( filters.length > 0 ) {

				var def_filter = filters.attr('data-default');

				if ( '' != def_filter ) {

					def_cat     = def_filter;
					def_cat_sel = filters.find('[data-filter="'+def_filter+'"]');

					if ( def_cat_sel.length > 0 ) {
						def_cat_sel.siblings().removeClass('rael-current');
						def_cat_sel.addClass('rael-current');
					}
				}
			}
			if ( $justified_selector.length > 0 ) {
				$justified_selector.imagesLoaded( function() {
				})
					.done(function( instance ) {
						$justified_selector.justifiedGallery({
							filter: def_cat,
							rowHeight : row_height,
							lastRow : lastrow,
							selector : 'div',
						});
					});
			} else {
				var masonryArgs = {
					// set itemSelector so .grid-sizer is not used in layout
					filter          : def_cat,
					itemSelector    : '.rael-grid-item',
					percentPosition : true,
					layoutMode      : layoutMode,
					hiddenStyle     : {
						opacity     : 0,
					},
				};

				var $isotopeObj = {};

				selector.imagesLoaded( function() {

					$isotopeObj = selector.isotope( masonryArgs );

					selector.find('.rael-grid-item').resize( function() {
						$isotopeObj.isotope( 'layout' );
					});
				});
			}

			if ( selector.hasClass('rael-cat-filters') ) {
				// bind filter button click
				scope.on( 'click', '.rael-masonry-filter', function() {

					var $this       = $(this);
					var filterValue = $this.attr('data-filter');

					$this.siblings().removeClass('rael-current');
					$this.addClass('rael-current');
					if( '*' == filterValue ) {
						filter_cat = scope.find('.rael-img-gallery-wrap').data('filter-default');
					} else {
						filter_cat = filterValue.substr(1);
					}

					if( scope.find( '.rael-masonry-filters' ).data( 'default' ) ){
						var def_filter = scope.find( '.rael-masonry-filters' ).data( 'default' );
					}
					else{
						var def_filter = '.' + scope.find('.rael-img-gallery-wrap').data( 'filter-default' );
					}

					var str_img_text = scope.find('.rael-current').text();
					var str_img_text = str_img_text.substring( def_filter.length - 1, str_img_text.length );
					scope.find( '.rael-filters-dropdown-button' ).text( str_img_text );

					if ( $justified_selector.length > 0 ) {
						$justified_selector.justifiedGallery({
							filter: filterValue,
							rowHeight : row_height,
							lastRow : lastrow,
							selector : 'div',
						});
					} else {
						$isotopeObj.isotope({ filter: filterValue });
					}
				});
			}

			if( scope.find( '.rael-masonry-filters' ).data( 'default' ) ){
				var def_filter = scope.find( '.rael-masonry-filters' ).data( 'default' );
			}
			else{
				var def_filter = '.' + scope.find('.rael-img-gallery-wrap').data( 'filter-default' );
			}

			var str_img_text = scope.find('.rael-current').text();
			var str_img_text = str_img_text.substring( def_filter.length - 1, str_img_text.length );
			scope.find( '.rael-filters-dropdown-button' ).text( str_img_text );
		});

	});
	</script>
		<?php
	}

	/**
	 * Print image gallery.
	 *
	 * @param array $images Image gallery data.
	 *
	 * @return void
	 */
	protected function print_image_gallery( $images ) {

		$settings       = $this->get_settings();
		$gallery        = $images;
		$img_size       = $settings['image_size'];
		$new_gallery    = array();
		$output         = '';
		$cat_filter     = array();
		$responsive_tab = ( 'yes' === $settings['rael_responsive_support'] ) ? ' rael-img-gallery-tabs-dropdown' : '';

		if ( ! is_array( $gallery ) ) {
			return;
		}

		if ( 'rand' === $settings['rael_gallery_random'] ) {
			$keys = array_keys( $gallery );
			shuffle( $keys );

			foreach ( $keys as $key ) {
				$new_gallery[ $key ] = $gallery[ $key ];
			}
		} else {
			$new_gallery = $gallery;
		}

		$click_action   = $settings['rael_click_action'];
		$image_wrap_tag = 'figure';
		$img_url        = '';

		foreach ( $new_gallery as $index => $item ) {
			if ( array_key_exists( 'url', $new_gallery ) ) {
				$img_url = $item['url'];
			}

			if ( ! $item['id'] && ! $img_url ) {
				return false;
			}

			$data = array();

			$img_url = esc_url_raw( $img_url );

			if ( ! empty( $item['id'] ) ) {

				$attachment = get_post( $item['id'] );
				if ( is_object( $attachment ) ) {
					$data['id']          = $item['id'];
					$data['url']         = $img_url;
					$data['image']       = wp_get_attachment_image( $attachment->ID, $img_size, true );
					$data['caption']     = $attachment->post_excerpt;
					$data['title']       = $attachment->post_title;
					$data['description'] = $attachment->post_content;

				}
			} else {

				if ( empty( $img_url ) ) {
					return;
				}

				$data['id']          = false;
				$data['url']         = $img_url;
				$data['image']       = '<img src="' . $img_url . '" alt="" title="" />';
				$data['caption']     = '';
				$data['title']       = '';
				$data['description'] = '';
			}

			$image = $data;

			$image_cat = array();

			$image_link = wp_get_attachment_image_src( $item['id'], 'full' );

			if ( empty( $image ) || false === $image_link ) {
				continue;
			}

			if ( ( 'grid' === $settings['rael_gallery_style'] || 'masonry' === $settings['rael_gallery_style'] || 'justified' === $settings['rael_gallery_style'] ) && 'yes' === $settings['rael_masonry_filters_enable'] ) {
				$cat = get_post_meta( $item['id'], 'rael-categories', true );

				if ( '' !== $cat ) {
					$cat_arr = explode( ',', $cat );

					foreach ( $cat_arr as $value ) {
						$cat      = trim( $value );
						$cat_slug = strtolower( str_replace( ' ', '-', $cat ) );

						$image_cat[]             = $cat_slug;
						$cat_filter[ $cat_slug ] = $cat;
					}
				}
			}

			$this->add_render_attribute(
				'grid-media-' . $index,
				'class',
				array(
					'rael-grid-img',
					'rael-grid-gallery-img',
					'rael-ins-hover',
					'elementor-clickable',
				)
			);

			$clickable_caption = '';

			if ( '' !== $click_action ) {

				$item_link         = '';
				$clickable_caption = $settings['rael_clickable_caption'];

				if ( 'lightbox' === $click_action ) {
					if ( $item['id'] ) {
						$item_link = $image_link;
						$item_link = $item_link[0];
					} else {
						$item_link = $item['url'];
					}

					$this->add_render_attribute(
						'grid-media-' . $index,
						array(
							'data-fancybox' => 'rael-gallery',
						)
					);
					$lightbox         = 'caption';
					$lightbox_content = apply_filters( 'rael_lightbox_content', $lightbox );
					// Convert HTML entities to their corresponding tags.
					$caption_raw     = html_entity_decode( $image[ $lightbox_content ], ENT_QUOTES | ENT_HTML5, 'UTF-8' );
					// Sanitize the caption to remove the HTML tags.
					$caption_cleaned = wp_kses( $caption_raw, array() );
					// Escape the caption for safe output in HTML attributes.
					$sanitized_value = esc_attr( $caption_cleaned );
					$this->add_render_attribute(
					    'grid-media-' . $index,
					    array(
					        'data-caption' => $sanitized_value,
					    )
					);
				} elseif ( 'file' === $click_action ) {
					if ( $item['id'] ) {
						$item_link = $image_link;
						$item_link = $item_link[0];
					} else {
						$item_link = $item['url'];
					}
				} elseif ( 'attachment' === $click_action ) {
					$item_link = get_permalink( $item['id'] );
				} elseif ( 'custom' === $click_action ) {
					if ( ! empty( $item['custom_link'] ) ) {
						$item_link = $item['custom_link'];
					}
				}

				if ( 'file' === $click_action || 'attachment' === $click_action || ( 'custom' === $click_action && '' !== $item_link ) ) {
					$link_target = $settings['rael_link_target'];

					if ( 'carousel' !== $settings['rael_gallery_style'] ) {
						$this->add_render_attribute( 'grid-media-' . $index, 'target', $link_target );

						if ( '_blank' === $link_target ) {
							$this->add_render_attribute( 'grid-media-' . $index, 'rel', 'nofollow' );
						}
					}
				}
				$image_wrap_tag = ( ! empty( $item_link ) ) ? 'a' : 'span';

				if ( ! empty ( $item_link ) ) {
					$this->add_render_attribute(
						'grid-media-' . $index,
						array(
							'href'						   => $item_link, 
							'data-elementor-open-lightbox' => 'no',
						)
					);
				} else {
					$this->add_render_attribute(
						'grid-media-' . $index,
						array(
							'href'						   => $item_link, 
							'data-elementor-open-lightbox' => 'no',
						)
					);
				}
			}

			if ( 'justified' === $settings['rael_gallery_style'] ) {
				$output .= '<div class="rael-grid-item ' . implode( ' ', $image_cat ) . '">';
				$output .= '<div class="rael-grid-item-content">';
			} elseif ( 'carousel' === $settings['rael_gallery_style'] ) {
				$output .= '<div class="swiper-slide">';
				$output .= '<div class="swiper-slide-contents rael-image-overlay rael-grid-item-content">';
			} else {
				$output .= '<div class="rael-grid-item ' . implode( ' ', $image_cat ) . ' rael-img-gallery-item-' . ( $index + 1 ) . '">';
				$output .= '<div class="rael-grid-item-content">';
			}

			$output .= '<' . $image_wrap_tag . ' ' . $this->get_render_attribute_string( 'grid-media-' . $index ) . '>';

			$output .= $this->print_thumbnail_image( $image );

			$output .= $this->print_overlay_image();

			if ( 'yes' === $clickable_caption ) {
				$output .= $this->print_image_caption( $image );
			}

			$output .= '</' . $image_wrap_tag . '>';

			if ( 'yes' !== $clickable_caption ) {
				$output .= $this->print_image_caption( $image );
			}

			$output .= '</div>';
			$output .= '</div>';
		}

		if ( ( 'grid' === $settings['rael_gallery_style'] || 'masonry' === $settings['rael_gallery_style'] || 'justified' === $settings['rael_gallery_style'] ) && 'yes' === $settings['rael_masonry_filters_enable'] ) {
			ksort( $cat_filter );
			$cat_filter = apply_filters( 'rael_image_gallery_tabs', $cat_filter );

			$default_cat = '';

			if ( 'yes' === $settings['rael_default_filter_switch'] && '' !== $settings['rael_default_filter_category'] ) {
				$default_cat = '.' . trim( $settings['rael_default_filter_category'] );
				$default_cat = strtolower( str_replace( ' ', '-', $default_cat ) );
			}

			$filters_output = '<div class="rael-masonry-filters-wrapper' . $responsive_tab . '">';

			$filters_output .= '<div class="rael-masonry-filters" data-default="' . $default_cat . '">';
			$filters_output .= '<div class="rael-masonry-filter rael-current" data-filter="*">' . $settings['rael_filters_all_text'] . '</div>';

			foreach ( $cat_filter as $key => $value ) {
				$filters_output .= '<div class="rael-masonry-filter" data-filter=".' . $key . '">' . $value . '</div>';
			}

			$filters_output .= '</div>';

			if ( 'yes' === $settings['rael_responsive_support'] ) {
				$filters_output .= '<div class="rael-filters-dropdown rael-masonry-filters" data-default="' . $default_cat . '">';

				$filters_output .= '<div class="rael-filters-dropdown-button">' . $settings['rael_filters_all_text'] . '</div>';

				$filters_output .= '<ul class="rael-filters-dropdown-list">';

				$filters_output .= '<li class="rael-filters-dropdown-item rael-masonry-filter rael-current" data-filter="*">' . $settings['rael_filters_all_text'] . '</li>';

				foreach ( $cat_filter as $key => $value ) {
					$filters_output .= '<li class="rael-filters-dropdown-item rael-masonry-filter" data-filter=".' . $key . '">' . $value . '</li>';
				}

				$filters_output .= '</ul>';
				$filters_output .= '</div>';
			}

			$filters_output .= '</div>';

			echo wp_kses_post( $filters_output );
		}

		if ( 'lightbox' === $click_action ) {
			$actions_arr = array();

			if ( ! empty( $settings['rael_lightbox_actions'] ) ) {
				if ( is_array( $settings['rael_lightbox_actions'] ) ) {
					foreach ( $settings['rael_lightbox_actions'] as $action ) {
						$actions_arr[] = $action;
					}
				} else {
					$actions_arr[] = $settings['rael_lightbox_actions'];
				}
			}

			$actions_arr[] = 'close';

			$this->add_render_attribute(
				'grid-wrap',
				array(
					'class'                 => 'rael-image-lightbox-wrap',
					'data-lightbox_actions' => wp_json_encode( $actions_arr ),
				)
			);
		}

		if ( 'carousel' !== $settings['rael_gallery_style'] ) {
			echo '<div ' . wp_kses_post( $this->get_render_attribute_string( 'grid-wrap' ) ) . '>';
		}
		echo wp_kses_post( $output );
		if ( 'carousel' !== $settings['rael_gallery_style'] ) {
			echo '</div>';
		}
	}

	/**
	 * Render widget.
	 *
	 * @return void
	 */
	protected function render() {
		$settings    = $this->get_settings();
		$node_id     = $this->get_id();
		$row_height  = '';
		$unjustified = '';

		$wrap_class = array(
			'rael-img-gallery-wrap',
			'rael-img-' . $settings['rael_gallery_style'] . '-wrap',
		);

		if ( 'grid' === $settings['rael_gallery_style'] || 'masonry' === $settings['rael_gallery_style'] || 'justified' === $settings['rael_gallery_style'] ) {
			$wrap_class[] = 'rael-img-grid-masonry-wrap';

			if ( 'masonry' === $settings['rael_gallery_style'] ) {
				$wrap_class[] = 'rael-masonry';
			}

			if ( 'yes' === $settings['rael_masonry_filters_enable'] ) {
				$wrap_class[] = 'rael-cat-filters';
			}
		}

		$images = $settings['rael_wp_gallery'];

		$gallery = $images;

		$image_custom_fields_migration_process = get_option( 'rea_to_rae_image_gallery_images_custom_fields_migration_process' );
		foreach ( $images as $i => $data ) {
			if ( ! $image_custom_fields_migration_process || 'complete' !== $image_custom_fields_migration_process ) {
				$this->responsive_addons_for_elementor_fetch_image_custom_field( $data['id'] );
			}
			$gallery[ $i ]['custom_link'] = get_post_meta( $data['id'], 'rael-custom-link', true );
		}

		update_option( 'rea_to_rae_image_gallery_images_custom_fields_migration_process', 'complete' );

		if ( 'carousel' === $settings['rael_gallery_style'] ) {
			$wrap_class[] = $settings['rael_navigation'];
			$this->get_image_carousel_settings( $gallery );
		}

		$this->add_render_attribute( 'grid-wrap', 'class', $wrap_class );
		$this->add_render_attribute( 'grid-wrap', 'data-filter-default', $settings['rael_filters_all_text'] );

		if ( 'justified' === $settings['rael_gallery_style'] ) {
			$row_height = ( '' !== $settings['rael_justified_row_height']['size'] ) ? $settings['rael_justified_row_height']['size'] : 120;

			$this->add_render_attribute(
				'grid-wrap',
				array(
					'data-rowheight' => $row_height,
					'data-lastrow'   => $settings['rael_last_row_layout'],
				)
			);
		}

		if ( 'justified' !== $settings['rael_gallery_style'] ) {
			$unjustified = 'rael-gallery-unjustified';
		}

		if ( 'carousel' !== $settings['rael_gallery_style'] ) {
			echo '<div class="rael-gallery-parent rael-caption-' . esc_attr( $settings['rael_gallery_caption'] ) . ' ' . esc_attr( $unjustified ) . '">';
			$this->print_image_gallery( $gallery );
			echo '</div>';
		}

		if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			if ( ( 'yes' === $settings['rael_masonry_filters_enable'] && 'grid' === $settings['rael_gallery_style'] ) || 'justified' === $settings['rael_gallery_style'] || 'masonry' === $settings['rael_gallery_style'] ) {
				$this->print_script();
			}
		}

		if ( 'yes' === $settings['rael_caption_enable_animations'] && 'on-hover' === $settings['rael_gallery_caption'] ) {
			$this->print_styles();
		}
	}

	/**
	 * Get Custom help URL
	 *
	 * @since 1.2.0
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/image-gallery';
	}

	/**
	 * Get Images Custom Fields and make them compatible with REA.
	 *
	 * @since 1.5.2
	 * @param integer $post_id Image ID.
	 * @return void
	 */
	public function responsive_addons_for_elementor_fetch_image_custom_field( $post_id ) {
		global $wpdb;

		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT meta_id, meta_key, meta_value
				FROM {$wpdb->postmeta}
				WHERE post_id = %d
				AND (meta_key LIKE %s OR meta_key LIKE %s)",
				$post_id,
				'rea_%',
				'rea-%'
			),
			ARRAY_N
		);

		if ( empty( $results ) ) {
			return;
		}

		foreach ( $results as $value ) {
			$new_key = str_replace( array( 'rea_', 'rea-' ), array( 'rael_', 'rael-' ), $value[1] );
			$wpdb->update(
				$wpdb->postmeta,
				array( 'meta_key' => $new_key ),
				array( 'meta_id' => $value[0] ),
				array( '%s' ),
				array( '%d' )
			);
		}
	}
}
