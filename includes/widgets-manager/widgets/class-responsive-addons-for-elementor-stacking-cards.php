<?php
/**
 * RAEL Stacking Cards widget
 *
 * @since   1.0.0
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use \Elementor\Group_Control_Css_Filter;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * RAEL Timeline widget class.
 *
 * @since 1.0.0
 */
class Responsive_Addons_For_Elementor_Stacking_Cards extends Widget_Base
{

	public function get_name(): string {
		return 'rael-stacking-cards';
	}

	public function get_title(): string {
		return __( 'Stacking Cards', 'responsive-addons-for-elementor' );
	}

	public function get_icon(): string {
		return 'eicon-parallax rael-badge';
	}

	public function get_categories(): array {
		return array( 'responsive-addons-for-elementor' );
	}

	public function get_keywords(): array {
		return [ 'hello', 'world' ];
	}

	public function get_script_depends() {
		return [ 'rael-stacking-card' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Source', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'source_type',
			array(
				'label'   => __( 'Items Source', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'posts',
				'options' => array(
					'posts' => __( 'Posts', 'responsive-addons-for-elementor' ),
					'items' => __( 'Items', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$source_options = array(
			'static'        => __( '-- Static Value --', 'responsive-addons-for-elementor' ),
			'before'        => __( '-- Text Before --', 'responsive-addons-for-elementor' ),
			'after'         => __( '-- Text After --', 'responsive-addons-for-elementor' ),
			'separator'     => __( '-- Separator --', 'responsive-addons-for-elementor' ),
			'post_title'    => __( 'Post Title', 'responsive-addons-for-elementor' ),
			'post_name'     => __( 'Post Name', 'responsive-addons-for-elementor' ),
			'post_intro'    => __( 'Post Intro', 'responsive-addons-for-elementor' ),
			'post_content'  => __( 'Post Content', 'responsive-addons-for-elementor' ),
			'post_image'    => __( 'Post Featured Image', 'responsive-addons-for-elementor' ),
			'post_date'     => __( 'Post Date', 'responsive-addons-for-elementor' ),
			'post_url'      => __( 'Post URL', 'responsive-addons-for-elementor' ),
			'post_meta'     => __( 'Post Meta Field', 'responsive-addons-for-elementor' ),
			'post_term'     => __( 'Post Term', 'responsive-addons-for-elementor' ),
			'truncate'      => __( '-- Truncate Text --', 'responsive-addons-for-elementor' ),
		);

		$this->add_control(
			'title_source',
			array(
				'label'     => __( 'Title Source', 'responsive-addons-for-elementor' ),
				'label_block' => true,
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'default'   => array( 'post_title' ),
				'options'   => $source_options,
				'condition'   => array(
					'source_type' => 'posts',
				),
			)
		);

		// Text Before
		$this->add_control(
			'title_text_before',
			array(
				'label'     => __( 'Title Text Before', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array(
					'title_source' => 'before',
				),
			)
		);

		// Text After
		$this->add_control(
			'title_text_after',
			array(
				'label'     => __( 'Title Text After', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array(
					'title_source' => 'after',
				),
			)
		);

		// Truncate Length
		$this->add_control(
			'title_truncate_chars',
			array(
				'label'     => __( 'Title Truncate Characters', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 100,
				'condition' => array(
					'title_source' => 'truncate',
				),
			)
		);


		$this->add_control(
			'description_source',
			array(
				'label'     => __( 'Description Source', 'responsive-addons-for-elementor' ),
				'label_block' => true,
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'default'   => 'post_content',
				'options'   => $source_options,
				'condition'   => array(
					'source_type' => 'posts',
				),
			)
		);

		$this->add_control(
			'link_source',
			array(
				'label'     => __( 'Link Source', 'responsive-addons-for-elementor' ),
				'label_block' => true,
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'default'   => 'post_url',
				'options'   => $source_options,
				'condition'   => array(
					'source_type' => 'posts',
				),
			)
		);

		$this->add_control(
			'button_text_source',
			array(
				'label'     => __( 'Button Text Source', 'responsive-addons-for-elementor' ),
				'label_block' => true,
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'options'   => $source_options,
				'condition'   => array(
					'source_type' => 'posts',
				),
			)
		);

		$this->add_control(
			'image_source',
			array(
				'label'     => __( 'Image Source', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'  => true,
				'default'   => 'post_image',
				'options'   => $source_options,
				'condition'   => array(
					'source_type' => 'posts',
				),
			)
		);

		$this->add_control(
			'graphic_image_source',
			array(
				'label'     => __( 'Graphic Image Source', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'  => true,
				'options'   => $source_options,
				'condition'   => array(
					'source_type' => 'posts',
				),
			)
		);

		$this->add_control(
			'graphic_icon_source',
			array(
				'label'     => __( 'Graphic Icon Source', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'  => true,
				'options'   => $source_options,
				'condition'   => array(
					'source_type' => 'posts',
				),
			)
		);

		$this->add_control(
			'graphic_text_source',
			array(
				'label'     => __( 'Graphic Text Source', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'  => true,
				'options'   => $source_options,
				'condition'   => array(
					'source_type' => 'posts',
				),
			)
		);

		$this->add_control(
			'background_color_source',
			array(
				'label'     => __( 'Background Color Source', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'  => true,
				'options'   => $source_options,
				'condition'   => array(
					'source_type' => 'posts',
				),
			)
		);
		$this->end_controls_section();
		// ================== General Section ==================
    $this->start_controls_section(
        'general_section',
        array(
            'label' => __( 'General', 'responsive-addons-for-elementor' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        )
    );

    $this->add_responsive_control(
        'sticky_position_top_space',
        array(
            'label' => __( 'Sticky Position Top Space', 'responsive-addons-for-elementor' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => array( 'px', 'vh', '%' ),
            'range' => array(
                'px' => array(
                    'min' => 0,
                    'max' => 500,
				),
				'vh' => array(
					'min' => 10,
					'max' => 100,
				),
				'%'  => array(
					'min' => 0,
					'max' => 100,
				),
			),
            'default' => array( 'size' => 150, 'unit' => 'px' ),
        )
    );

    $this->add_responsive_control(
        'card_gap',
        array(
            'label' => __( 'Card Gap', 'responsive-addons-for-elementor' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => array( 'px', 'vh', '%' ),
            'range' => array(
                'px' => array(
                    'min' => 0,
                    'max' => 200,
				),
				'vh' => array(
					'min' => 10,
					'max' => 100,
				),
				'%'  => array(
					'min' => 0,
					'max' => 100,
				),
			),
            'default' => array( 'size' => 100, 'unit' => 'px' ),
        )
    );

    $this->add_responsive_control(
        'card_top_offset',
        array(
            'label' => __( 'Card Top Offset', 'responsive-addons-for-elementor' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => array( 'px' ),
            'range' => array(
                'px' => array(
                    'min' => 0,
                    'max' => 200,
				),
			),
            'default' => array( 'size' => 20, 'unit' => 'px' ),
			'dynamic' => array( 'active' => true ), 
        )
    );

    $this->add_control(
        'enable_scroll_motion',
        array(
            'label' => __( 'Enable Scroll Motion', 'responsive-addons-for-elementor' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Yes', 'responsive-addons-for-elementor' ),
            'label_off' => __( 'No', 'responsive-addons-for-elementor' ),
            'return_value' => 'yes',
            'default' => 'yes',
        )
    );

    $this->add_control(
        'hover_transition_duration',
        array(
            'label' => __( 'Hover Transition Duration (ms)', 'responsive-addons-for-elementor' ),
            'type' => Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 2000,
            'default' => 300,
        )
    );

    $this->add_control(
        'general_item_image_size',
        array(
            'label' => __( 'Item Image Size', 'responsive-addons-for-elementor' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'medium_large',
            'options' => array(
                'thumbnail'    => __( 'Thumbnail', 'responsive-addons-for-elementor' ),
                'medium'       => __( 'Medium', 'responsive-addons-for-elementor' ),
                'medium_large' => __( 'Medium Large (max width 768)', 'responsive-addons-for-elementor' ),
                'large'        => __( 'Large', 'responsive-addons-for-elementor' ),
                'full'         => __( 'Full', 'responsive-addons-for-elementor' ),
            ),
        )
    );

    $this->add_control(
        'rtl_enable',
        array(
            'label' => __( 'RTL', 'responsive-addons-for-elementor' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Yes', 'responsive-addons-for-elementor' ),
            'label_off' => __( 'No', 'responsive-addons-for-elementor' ),
            'return_value' => 'yes',
            'default' => 'no',
        )
    );

    $this->end_controls_section();

    // ================== Layout Section ==================
    $this->start_controls_section(
        'layout_section',
        array(
            'label' => __( 'Layout', 'responsive-addons-for-elementor' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        )
    );

    $this->add_control(
        'show_title',
        array(
            'label' => __( 'Show Title', 'responsive-addons-for-elementor' ),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __( 'Yes', 'responsive-addons-for-elementor' ),
            'label_off' => __( 'No', 'responsive-addons-for-elementor' ),
            'default' => 'yes',
        )
    );

    $this->add_control(
        'title_html_tag',
        array(
            'label' => __( 'Title HTML Tag', 'responsive-addons-for-elementor' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'div',
            'options' => array(
                'h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'h5' => 'H5',
                'h6' => 'H6',
                'div' => 'div',
                'span' => 'span',
                'p' => 'p',
            ),
        )
    );

    $this->add_control(
        'show_description',
        array(
            'label' => __( 'Show Description', 'responsive-addons-for-elementor' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        )
    );

    $this->add_control(
        'show_button',
        array(
            'label' => __( 'Show Button', 'responsive-addons-for-elementor' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        )
    );

    $this->add_control(
        'button_text',
        array(
            'label' => __( 'Button Text', 'responsive-addons-for-elementor' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Read More', 'responsive-addons-for-elementor' ),
            'condition' => array( 'show_button' => 'yes' ),
        )
    );

    $this->add_control(
        'show_image',
        array(
            'label' => __( 'Show Image', 'responsive-addons-for-elementor' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
        )
    );

    $this->add_control(
        'show_graphic_element',
        array(
            'label' => __( 'Show Graphic Element', 'responsive-addons-for-elementor' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'icon',
            'options' => array(
                'none'  => __( 'None', 'responsive-addons-for-elementor' ),
                'icon'  => __( 'Icon', 'responsive-addons-for-elementor' ),
                'image' => __( 'Image', 'responsive-addons-for-elementor' ),
                'text'  => __( 'Text', 'responsive-addons-for-elementor' ),
            ),
        )
    );

    $this->add_control(
        'graphic_element_image_size',
        array(
            'label' => __( 'Graphic Element Image Size', 'responsive-addons-for-elementor' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'medium_large',
            'options' => array(
                'thumbnail'    => __( 'Thumbnail', 'responsive-addons-for-elementor' ),
                'medium'       => __( 'Medium', 'responsive-addons-for-elementor' ),
                'medium_large' => __( 'Medium Large (max width 768)', 'responsive-addons-for-elementor' ),
                'large'        => __( 'Large', 'responsive-addons-for-elementor' ),
                'full'         => __( 'Full', 'responsive-addons-for-elementor' ),
            ),
        )
    );

    $this->end_controls_section();

		// Items (visible only if source = items)
		$this->start_controls_section(
			'item_section',
			array(
				'label'     => __( 'Items', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'source_type' => 'items',
				),
			)
		);

		$repeater = new Repeater();

		// ================== Tabs inside Items ==================
		$repeater->start_controls_tabs( 'item_tabs' );

		// ------------------ Content Tab ------------------
		$repeater->start_controls_tab(
			'tab_content',
			array( 'label' => __( 'Content', 'responsive-addons-for-elementor' ) )
		);

		$repeater->add_control(
			'item_title',
			array(
				'label'   => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Innovative Solution', 'responsive-addons-for-elementor' ),
			)
		);

		$repeater->add_control(
			'content_type',
			array(
				'label'   => __( 'Content Type', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'editor',
				'options' => array(
					'editor' => __( 'Text Editor', 'responsive-addons-for-elementor' ),
					'template' => __( 'Template', 'responsive-addons-for-elementor' ),
            		'section'  => __( 'Section ID', 'responsive-addons-for-elementor' ),
				),
			)
		);
		

		$repeater->add_control(
			'item_desc',
			array(
				'label'   => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'Explore cutting-edge technologies...', 'responsive-addons-for-elementor' ),
				'condition' => array( 'content_type' => 'editor' ),
			)
		);

		$repeater->add_control(
			'item_link',
			array(
				'label'       => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'responsive-addons-for-elementor' ),
				'default'     => array(
					'url'         => 'https://your-link.com', // Default URL
					'is_external' => true,                    // Open in new tab by default
					'nofollow'    => false,                   // No follow attribute
				),
				'condition' => array( 'content_type' => 'editor' ),
			)
		);

		$repeater->add_control(
			'show_button',
			array(
				'label'        => __( 'Show Button', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition' => array( 'content_type' => 'editor' ),
			)
		);

		$repeater->add_control(
			'button_text',
			array(
				'label'     => __( 'Button Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Learn More', 'responsive-addons-for-elementor' ),
				'condition' => array( 'show_button' => 'yes', 'content_type' => 'editor', ),
			)
		);

		$repeater->add_control(
			'item_image',
			array(
				'label'   => __( 'Image', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => Utils::get_placeholder_image_src() ),
				'condition' => array( 'content_type' => 'editor' ),
			)
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'item_image_size',
				'default'   => 'medium_large',
				'separator' => 'none',
				'condition' => array( 'content_type' => 'editor' ),
			)
		);
		// === Field for Template ===

		$repeater->add_control(
			'item_template',
			array(
				'label'     => __( 'Template', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT2,
				'options'   => $this->get_elementor_templates(),
				'condition' => array( 'content_type' => 'template' ),
			)
		);
		// === Field for Section ID ===

		$repeater->add_control(
			'item_section',
			array(
				'label'       => __( 'Section ID', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'my-section', 'responsive-addons-for-elementor' ),
				'description' => __( 'Use CSS ID of a section available in the same page.', 'responsive-addons-for-elementor' ),
				'condition'   => array( 'content_type' => 'section' ),
			)
		);
		$repeater->end_controls_tab();

		// ------------------ Graphic Tab ------------------
		$repeater->start_controls_tab(
			'tab_graphic',
			array( 'label' => __( 'Graphic', 'responsive-addons-for-elementor' ) )
		);

		$repeater->add_control(
			'graphic_image',
			array(
				'label'   => __( 'Graphic Image', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => Utils::get_placeholder_image_src() ),
			)
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'graphic_image_size',
				'default' => 'medium_large',
			)
		);

		$repeater->add_control(
			'graphic_icon',
			array(
				'label' => __( 'Graphic Icon', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::ICONS,
			)
		);

		$repeater->add_control(
			'graphic_text',
			array(
				'label'   => __( 'Graphic Text', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'D', 'responsive-addons-for-elementor' ),
			)
		);

		$repeater->end_controls_tab();

		// ------------------ Background Tab ------------------
		$repeater->start_controls_tab(
			'tab_background',
			array( 'label' => __( 'Background', 'responsive-addons-for-elementor' ) )
		);

		$repeater->add_control(
			'background_type',
			array(
				'label'   => __( 'Background Type', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'color',
				'options' => array(
					'color' => __( 'Color', 'responsive-addons-for-elementor' ),
					'image' => __( 'Image', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$repeater->add_control(
			'background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array( 'background_type' => 'color' ),
			)
		);

		$repeater->add_control(
			'background_image',
			array(
				'label'     => __( 'Background Image', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array( 'background_type' => 'image' ),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();
		// ================== Tabs End ==================

		// Add repeater to control
		$this->add_control(
			'items_list',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'item_title' => __( 'Item #1', 'responsive-addons-for-elementor' ) ),
					array( 'item_title' => __( 'Item #2', 'responsive-addons-for-elementor' ) ),
					array( 'item_title' => __( 'Item #3', 'responsive-addons-for-elementor' ) ),
				),
				'title_field' => '{{{ item_title }}}',
				'render_type' => 'template', 
			)
		);

    	$this->end_controls_section();
		// Scroll Motion
		$this->start_controls_section(
			'scroll_motion_style',
			array(
				'label' => __( 'Scroll Motion', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'enable_scroll_motion' => 'yes', // show only if switcher is 'yes'
				),
			)
		);

		$this->add_control(
			'transform_origin_x',
			array(
				'label' => __( 'Transform Origin X', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array( 'size' => 50 ),
				'range' => array(
					'px' => array( 'min' => 0, 'max' => 100 ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-stacking-card' => '--rae-transform-origin-x: {{SIZE}}{{UNIT}};',
				),
				'dynamic' => array( 'active' => true ), 
			)
		);

		$this->add_control(
			'transform_origin_y',
			array(
				'label' => __( 'Transform Origin Y', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array( 'size' => 0 ),
				'range' => array(
					'px' => array( 'min' => 0, 'max' => 100 ),
				),
				 'selectors' => array(
					'{{WRAPPER}} .rael-stacking-card' => '--rae-transform-origin-y: {{SIZE}}{{UNIT}};',
				 ),
				'dynamic' => array( 'active' => true ), 
			)
		);
		$this->start_controls_tabs( 'scroll_motion_tabs' );

		// ---------------- Normal Tab ----------------
		$this->start_controls_tab(
			'scroll_motion_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);
		$this->end_controls_tab();
		
		$this->add_control(
			'normal_greyscale', 
			array(
				'label'   => __( 'Greyscale', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range' => array(
					'%' => array( 'min' => 0, 'max' => 100 ),
				),
				'default' => array( 'size' => 0, 'unit' => '%' ),
				'dynamic' => array( 'active' => true ),
				'selectors' => array(
					'{{WRAPPER}} .rael-card-media img' => 'filter: grayscale({{SIZE}}{{UNIT}});',
				), 
			),
		);
		$this->add_control(
			'normal_rotation',
			array(
				'label'   => __( 'Rotation (deg)', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'size_units' => array( 'deg' ),
				'range'   => array(
					'deg' => array( 'min' => -360, 'max' => 360 ),
				),
				'default' => array( 'size' => 0, 'unit' => 'deg' ),
				'dynamic' => array( 'active' => true ), 
			),
		);

		$this->end_controls_tab();

// ---------------- Hover tab (Scale, Opacity, Blur, Greyscale, Rotation) ---------------- 
		$this->start_controls_tab(
			'scroll_motion_scroll',
			array( 'label' => __( 'Scroll', 'responsive-addons-for-elementor' ) )
		);

		$this->add_control(
			'scroll_scale',
			array(
				'label'   => __( 'Scale', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => array( 'px' => array( 'min' => -1, 'max' => 2, 'step' => 0.1 ) ),
				'default' => array( 'size' => 1 ),
				'dynamic' => array( 'active' => true ),
				'selectors' => array(
					'{{WRAPPER}}' => '--rae-scale: {{SIZE}};',
				), 
			),
		);
		$this->add_control(
			'scroll_opacity',
			array(
				'label'   => __( 'Opacity', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => array( 'px' => array( 'min' => 0, 'max' => 100 ) ),
				'default' => array( 'size' => 100 ),
				'dynamic' => array( 'active' => true ), 
			),
		);
		$this->add_control(
			'scroll_blur',
			array(
				'label'   => __( 'Blur', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => array( 'px' => array( 'min' => 0, 'max' => 20 ) ),
				'default' => array( 'size' => 0 ),
				'dynamic' => array( 'active' => true ), 
				
			),
		);
		$this->add_control(
			'scroll_greyscale',
			array(
				'label'   => __( 'Greyscale', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range' => array(
					'%' => array( 'min' => 0, 'max' => 100 ),
				),
				'default' => array( 'size' => 0, 'unit' => '%' ),
				'dynamic' => array( 'active' => true ), 
				'selectors' => array(
					'{{WRAPPER}} .rael-stacking-card img:hover' => 'filter: grayscale({{SIZE}}{{UNIT}});',
				),
			),
		);
		$this->add_control(
			'scroll_rotation',
			array(
				'label'   => __( 'Rotation (deg)', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'size_units' => array( 'deg' ),
				'range'   => array(
					'deg' => array( 'min' => -360, 'max' => 360 ),
				),
				'default' => array( 'size' => 0, 'unit' => 'deg' ),
				'dynamic' => array( 'active' => true ), 
				'selectors' => array( 
					'{{WRAPPER}} .rael-stacking-card' => '--rae-rotate: {{SIZE}}deg;',
				),
			),
		);


		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->end_controls_section();

		// Card
		$this->start_controls_section(
			'card_style',
			array(
				'label' => __( 'Card', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'card_width',
			array(
				'label' => __( 'Card Width', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px','%' ),
				'range' => array(
					'px' => array( 'min' => 0, 'max' => 1200 ),
					'%'  => array( 'min' => 0, 'max' => 100 ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-stacking-card' => 'width: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'card_height',
			array(
				'label' => __( 'Card Height', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array( 'px' => array( 'min' => 0, 'max' => 1200 ) ),
				'default' => array( 'size' => 600, 'unit' => 'px' ),
				// 'selectors' => array(
				// 	'{{WRAPPER}} .rael-stacking-card' => 'height: {{SIZE}}{{UNIT}}',
				// ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name' => 'card_background',
				'label' => __( 'Background', 'responsive-addons-for-elementor' ),
				'types' => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-stacking-card',
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
					),
					'color' => array(
						'default' => '#ffffff', 
					),
					'color_b' => array(
						'default' => '#f0f0f0', 
					),
				),
			)
		);
		// Box Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_box_shadow',
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-stacking-card',
			)
		);
		// Border Radius
		$this->add_responsive_control(
			'card_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-stacking-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name' => 'card_border',
				'selector' => '{{WRAPPER}} .rael-stacking-card',
			)
		);

		$this->add_responsive_control(
			'card_padding',
			array(
				'label' => __( 'Card Padding', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .rael-stacking-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Content
		$this->start_controls_section(
			'content_style',
			array(
				'label' => __( 'Content', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'content_width',
			array(
				'label' => __( 'Content Width', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px','%' ),
				'range' => array(
					'px' => array( 'min' => 0, 'max' => 1200 ),
					'%'  => array( 'min' => 0, 'max' => 100 ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-card-content' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'content_padding',
			array(
				'label' => __( 'Content Padding', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .rael-card-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_alignment',
			 array(
				'label'   => __( 'Align Content', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'left'   => __( 'Left', 'responsive-addons-for-elementor' ),
					'center' => __( 'Center', 'responsive-addons-for-elementor' ),
					'right'  => __( 'Right', 'responsive-addons-for-elementor' ),
				),
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .rael-card-content' => 'text-align: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'content_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array( 'default' => Global_Colors::COLOR_PRIMARY),
				 'default'   => 'transparent',
				'selectors' => array(
					'{{WRAPPER}} .rael-card-content' => 'background-color: {{VALUE}};',
				),
			)
		);

	
		$this->end_controls_section();
		// Graphic Element
		$this->start_controls_section(
			'graphic_element_style',
			array(
				'label' => __( 'Graphic Element', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'graphic_element_width',
			array(
				'label' => __( 'Graphic Element Width', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px','%' ),
				'range' => array(
					'px' => array( 'min' => 0, 'max' => 1200 ),
					'%'  => array( 'min' => 0, 'max' => 100 ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-card-graphic' => 'width: {{SIZE}}{{UNIT}} !important;', 
            		'{{WRAPPER}} .rael-card-graphic img' => 'width: 100% !important; height: auto !important;', 
					'{{WRAPPER}} .rael-card-graphic svg' => 'width: {{SIZE}}{{UNIT}} !important;', 
				),
			)
		);
		$this->add_responsive_control(
			'graphic_element_height',
			array(
				'label' => __( 'Graphic Element Height', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px','%' ),
				'range' => array(
					'px' => array( 'min' => 0, 'max' => 1200 ),
					'%'  => array( 'min' => 0, 'max' => 100 ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-card-graphic' => 'height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .rael-card-graphic img' => 'height: 100% !important; object-fit: cover !important;',
					'{{WRAPPER}} .rael-card-graphic svg' => 'height: 100% !important;',
				),
			)
		);
		$this->add_responsive_control(
			'graphic_element_icon_size',
			array(
				'label' => __( 'Graphic Icon Size', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px','%' ),
				'range' => array(
					'px' => array( 'min' => 0, 'max' => 1200 ),
					'%'  => array( 'min' => 0, 'max' => 100 ),
				),
				'selectors' => array(
				    '{{WRAPPER}} .rael-card-graphic svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;', // <- add here
            		'{{WRAPPER}} .rael-card-graphic i'   => 'font-size: {{SIZE}}{{UNIT}} !important;', // existing for icon fonts
				),
			)
		);
		
		$this->add_control(
			'graphic_element_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array( 'default' => Global_Colors::COLOR_PRIMARY),
				'selectors' => array(
					'{{WRAPPER}} .rael-card-graphic' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'graphic_element_color',
			array(
				'label' => __( 'Color', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-card-graphic svg path' => 'fill: {{VALUE}} !important;',
				),
			)
		);
		$this->add_responsive_control(
			'graphic_element_border_radius',
			array(
				'label' => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .rael-card-graphic' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'graphic_element_box_shadow',
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-card-graphic',
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'graphic_element_icon_text_shadow',
				'label'    => __( 'Icon/Text Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-card-graphic',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name' => 'graphic_element_border',
				'selector' => '{{WRAPPER}} .rael-card-graphic',
			)
		);

		$this->end_controls_section();

		//======== Style - Title Section ============
		$this->start_controls_section(
			'title_style',
			array(
				'label' => __( 'Title', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .rael-card-title',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label' => __( 'Color', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-card-title' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'title_text_shadow',
				'label'    => __( 'Text Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-card-title',
			)
		);

		$this->add_responsive_control(
			'title_gap',
			array(
				'label' => __( 'Gap', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 15,
					'unit' => 'px',
				),
				'size_units' => array( 'px','%' ),
				'range' => array(
					'px' => array( 'min' => 0, 'max' => 1200 ),
					'%'  => array( 'min' => 0, 'max' => 100 ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-card-title' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Style - Description
		$this->start_controls_section(
			'description_style',
			array(
				'label' => __( 'Description', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .rael-card-desc',
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label' => __( 'Color', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-card-desc' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'description_text_shadow',
				'label'    => __( 'Text Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-card-desc',
			)
		);

		$this->add_responsive_control(
			'description_gap',
			array(
				'label' => __( 'Gap', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 15,
					'unit' => 'px',
				),
				'size_units' => array( 'px','%' ),
				'range' => array(
					'px' => array( 'min' => 0, 'max' => 1200 ),
					'%'  => array( 'min' => 0, 'max' => 100 ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-card-desc' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Style - Button
		$this->start_controls_section(
			'button_style',
			array(
				'label' => __( 'Button', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'button_width_type',
			array(
				'label'   => __( 'Button Width Type', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => array(
					'auto'   => __( 'Auto', 'responsive-addons-for-elementor' ),
					'full'   => __( 'Full Width', 'responsive-addons-for-elementor' ),
					'custom' => __( 'Custom', 'responsive-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-card-button' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'auto'   => 'width: auto;',
					'full'   => 'width: 100%; display: block;',
					'custom' => 'width: var(--rael-button-custom-width, auto);', // placeholder, controlled by another slider
				),
			)
		);

		// Button custom width (only when "custom" is selected)
		$this->add_responsive_control(
			'button_custom_width',
			array(
				'label' => __( 'Button Custom Width', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range' => array(
					'px' => array( 'min' => 0, 'max' => 1200 ),
					'%'  => array( 'min' => 0, 'max' => 100 ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-card-button' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'button_width_type' => 'custom',
				),
			)
		);

		$this->add_responsive_control(
			'button_text_align',
			array(
				'label'   => __( 'Button Text Align', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => array(
					'left'   => __( 'Left', 'responsive-addons-for-elementor' ),
					'center' => __( 'Center', 'responsive-addons-for-elementor' ),
					'right'  => __( 'Right', 'responsive-addons-for-elementor' ),
				),
				'selectors_dictionary' => array(
					'left'   => 'text-align: left;',
					'center' => 'text-align: center;',
					'right'  => 'text-align: right;',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-card-button' => '{{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-card-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .rael-card-button',
			)
		);

		$this->add_responsive_control(
			'button_gap',
			array(
				'label'      => __( 'Button Gap', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'size' => 15,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-card-button' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Tabs: Normal & Hover
		$this->start_controls_tabs( 'button_tabs' );

		// Normal Tab
		$this->start_controls_tab(
			'button_tab_normal',
			array( 'label' => __( 'Normal', 'responsive-addons-for-elementor' ) )
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-card-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-card-button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-card-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .rael-card-button',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'button_text_shadow',
				'selector' => '{{WRAPPER}} .rael-card-button',
			)
		);

		$this->end_controls_tab();

		// Hover Tab
		$this->start_controls_tab(
			'button_tab_hover',
			array( 'label' => __( 'Hover', 'responsive-addons-for-elementor' ) )
		);

		$this->add_control(
			'button_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-card-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-card-button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} .rael-card-button:hover',
			)
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .rael-card-button',
			)
		);

		$this->end_controls_section();

		// Style - Image
		$this->start_controls_section(
			'image_style',
			array(
				'label' => __( 'Image', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		
		$this->add_responsive_control(
			'image_fit',
			array(
				'label'   => __( 'Image Fit', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => array(
					'cover'   => __( 'Cover', 'responsive-addons-for-elementor' ),
					'contain' => __( 'Contain', 'responsive-addons-for-elementor' ),
					'fill'    => __( 'Fill', 'responsive-addons-for-elementor' ),
					'none'    => __( 'None', 'responsive-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-card-media img' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_position',
			array(
				'label'   => __( 'Image Position', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => array(
					'left'   => __( 'Left', 'responsive-addons-for-elementor' ),
					'center' => __( 'Center', 'responsive-addons-for-elementor' ),
					'right'  => __( 'Right', 'responsive-addons-for-elementor' ),
					'top'    => __( 'Top', 'responsive-addons-for-elementor' ),
					'bottom' => __( 'Bottom', 'responsive-addons-for-elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-card-media img' => 'object-position: {{VALUE}};',
				),
			)
		);

		// Tabs: Normal & Hover
		$this->start_controls_tabs( 'image_tabs' );

		// Normal Tab
		$this->start_controls_tab(
			'image_tab_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'image_css_filters',
				'selector' => '{{WRAPPER}} .rael-card-media img',
			)
		);

		$this->end_controls_tab();

		// Hover Tab
		$this->start_controls_tab(
			'image_tab_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'image_hover_css_filters',
				'selector' => '{{WRAPPER}} .rael-card-media img:hover',
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	private function get_elementor_templates() {
		$templates = array();
		$template_library = \Elementor\Plugin::instance()->templates_manager->get_source( 'local' )->get_items();

		if ( ! empty( $template_library ) ) {
			foreach ( $template_library as $template ) {
				$templates[ $template['template_id'] ] = $template['title'];
			}
		}

		return $templates;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$items = [];
		$rtl = ! empty( $settings['rtl_enable'] ) && $settings['rtl_enable'] === 'yes';
		

		// Wrapper
		$sticky_top = ! empty( $settings['sticky_position_top_space']['size'] )
			? $settings['sticky_position_top_space']['size']
			: 0; // numeric value only

		$sticky_unit = ! empty( $settings['sticky_position_top_space']['unit'] ) 
			? $settings['sticky_position_top_space']['unit'] 
			: 'px';

		$card_gap = ! empty( $settings['card_gap']['size'] )
			? $settings['card_gap']['size'] . ( $settings['card_gap']['unit'] ?? 'px' )
			: '100px';

		$card_offset = ! empty( $settings['card_top_offset']['size'] )
			? $settings['card_top_offset']['size'] . ( $settings['card_top_offset']['unit'] ?? 'px' )
			: '20px';

		// ---------- Items (repeater source) ----------
		if ( isset($settings['source_type']) && 'items' === $settings['source_type'] && ! empty( $settings['items_list'] ) ) {
			foreach ( $settings['items_list'] as $item ) {
				// Main image
				$image_html = '';
				if ( ! empty( $item['item_image']['id'] ) ) {
					error_log('in iffffff');
					$image_html = \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item, 'item_image_size', 'item_image' );
				} elseif ( ! empty( $item['item_image']['url'] ) ) {
					error_log(message: 'in elseiffffff');
					$image_html = '<img src="' . esc_url( $item['item_image']['url'] ) . '" alt="">';
				}

				// Graphic image
				$graphic_html = '';
				if ( ! empty( $item['graphic_image']['id'] ) ) {
					$graphic_html = \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item, 'graphic_image_size', 'graphic_image' );
				}

				$link = is_array( $item['item_link'] ) ? $item['item_link'] : [
					'url'         => '',
					'is_external' => false,
					'nofollow'    => false,
				];

				$items[] = [
					'title'        => $item['item_title'] ?? '',
					'desc'         => $item['item_desc'] ?? '',
					'image_html'   => $image_html,
					'image_url'    => '',
					'button_text'  => $item['button_text'] ?? '',
					'link_url'     => $link['url'] ?? '',
					'link_external'=> ! empty( $link['is_external'] ),
					'link_nofollow'=> ! empty( $link['nofollow'] ),
					'graphic_html' => $graphic_html,
					'graphic_icon' => $item['graphic_icon'] ?? null,
					'graphic_text' => $item['graphic_text'] ?? '',
				];
			}
		}

		// ---------- Posts source ----------
		if ( isset($settings['source_type']) && 'posts' === $settings['source_type'] ) {
			$query = new \WP_Query( [
				'post_type'           => 'post',
				'posts_per_page'      => 3,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			] );

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$post_id = get_the_ID();
					$img_size = ! empty( $settings['item_image_size'] ) ? $settings['item_image_size'] : 'medium_large';
					$img_url  = get_the_post_thumbnail_url( $post_id, $img_size );

					$items[] = [
						'title'        => get_the_title(),
						'desc'         => wp_trim_words( get_the_excerpt() ?: wp_strip_all_tags( get_the_content() ), 24 ),
						'image_html'   => '',
						'image_url'    => $img_url ?: '',
						'button_text'  => $settings['button_text'] ?? __( 'Read More', 'responsive-addons-for-elementor' ),
						'link_url'     => get_permalink( $post_id ),
						'link_external'=> false,
						'link_nofollow'=> false,
						'graphic_html' => '',
						'graphic_icon' => null,
						'graphic_text' => '',
					];
				}
				wp_reset_postdata();
			}
		}

		if ( empty( $items ) ) {
			return;
		}

		$wrapper_classes = 'rael-stacking-cards-wrapper';
		if ( $rtl ) {
			$wrapper_classes .= ' rtl-enabled';
		}
		$this->add_render_attribute( 'wrapper', 'class', $wrapper_classes );
		
		// optional: add data attribute for scroll motion toggle
		if ( ! empty( $settings['enable_scroll_motion'] ) ) {
			$this->add_render_attribute( 'wrapper', 'data-scroll-motion', 'true' );
		}
		
		echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) . '>';
		foreach ( $items as $index => $item ) {
			$offset_value = 'calc(' . $index+1 . ' * ' . $card_offset . ')';
			$sticky_top_item = ( $sticky_top + ( $index * 40 ) ) . $sticky_unit;

			// Get numeric values
			$origin_x_val = ! empty( $settings['transform_origin_x']['size'] ) 
				? $settings['transform_origin_x']['size'] 
				: 0;
			$origin_y_val = ! empty( $settings['transform_origin_y']['size'] ) 
				? $settings['transform_origin_y']['size'] 
				: 0;

			// Get units
			$origin_x_unit = $settings['transform_origin_x']['unit'] ?? 'px';
			$origin_y_unit = $settings['transform_origin_y']['unit'] ?? 'px';

			// Scale factor
			$scale_step = 0;
			$scale = 1 - ($index * $scale_step);

			// Final transform
			if ( $origin_x_val == 0 && $origin_y_val == 0 ) {
				// Only scaling, no movement
				$transform = sprintf(
					'scale(%s)',
					$scale
				);
			} else {
				// Calculate scaled offsets
				$offset_x = ($origin_x_val * $index) . $origin_x_unit;
				$offset_y = ($origin_y_val * $index) . $origin_y_unit;


				$transform = sprintf(
					'translate3d(%s, %s, 0px) scale(%s)',
					$offset_x,
					$offset_y,
					$scale
				);
				// Transform origin (keeps the pivot)
				$transform_origin = $origin_x_val . $origin_x_unit; //. ' ' . $origin_y_val . $origin_y_unit;

			}

	

			$this->add_render_attribute(
                'card' . $index,
                array(
                    'class' => array( 'rael-stacking-card', 'elementor-repeater-item-' . ($index+1), ),
					'style' =>  sprintf(
						'top:%s; margin-top:%s; transform-origin:%s; transform:%s;',
						  esc_attr($sticky_top_item),
							esc_attr($offset_value),
							esc_attr($transform_origin),
							esc_attr($transform)
					),

				)
            );

			echo '<div ' . $this->get_render_attribute_string( 'card' . $index ) . '>';

			// Card inner (flex container)
        	echo '<div class="rael-card-inner">';
			
			// Content
			echo '<div class="rael-card-content">';

			// Graphic
			$graphic_output = '';
			if ( ! empty( $item['graphic_html'] ) ) {
				$graphic_output = $item['graphic_html'];
			} elseif ( ! empty( $item['graphic_icon'] ) ) {
				ob_start();
				\Elementor\Icons_Manager::render_icon( $item['graphic_icon'], [ 'aria-hidden' => 'true' ] );
				
				$graphic_output = ob_get_clean();
			} elseif ( ! empty( $item['graphic_text'] ) ) {
				$graphic_output = '<div class="rael-card-graphic-text">' . esc_html( $item['graphic_text'] ) . '</div>';
			}
			if ( ! empty( $graphic_output ) ) {
				echo '<div class="rael-card-graphic">' . $graphic_output . '</div>';
			}
			$tag = ! empty( $settings['title_html_tag'] ) ? $settings['title_html_tag'] : 'div';
			if ( !empty ($settings['show_title']) && ! empty( $item['title'] ) ) {
				echo '<' . esc_html( $tag ) . ' class="rael-card-title">' . esc_html( $item['title'] ) . '</' . esc_html( $tag ) . '>';

			}
			if ( !empty ($settings['show_description']) && ! empty( $item['desc'] ) ) {
				echo '<div class="rael-card-desc">' . wp_kses_post( $item['desc'] ) . '</div>';
			}

			if ( !empty($settings['show_button']) && ! empty( $item['button_text'] ) && ! empty( $item['link_url'] ) ) {
				$this->add_link_attributes( 'button_' . $index, [
					'url'         => $item['link_url'],
					'is_external' => $item['link_external'],
					'nofollow'    => $item['link_nofollow'],
				] );
				echo '<a ' . $this->get_render_attribute_string( 'button_' . $index ) . ' class="elementor-button rael-card-button">';
				echo esc_html( $item['button_text'] );
				echo '</a>';
			}

			echo '</div>'; // content

			// Image
			$image_output = '';
			if ( ! empty( $item['image_html'] ) ) {
				$image_output = $item['image_html'];
			} elseif ( ! empty( $item['image_url'] ) ) {
				$image_output = '<img src="' . esc_url( $item['image_url'] ) . '" alt="">';
			}
			if ( !empty($settings['show_image']) && ! empty( $image_output ) ) {
				echo '<div class="rael-card-media">' . $image_output . '</div>';
			}

			echo '</div>'; // .rael-card-inner
			
			echo '</div>'; // card
		}

		echo '</div>'; // wrapper
	}


	protected function content_template() {
		?>
		<# 
			var stickyTop  = ( settings.sticky_position_top_space && settings.sticky_position_top_space.size ) 
				? settings.sticky_position_top_space.size + ( settings.sticky_position_top_space.unit || 'px' ) 
				: '150px';

			var cardGap = ( settings.card_gap && settings.card_gap.size ) 
				? settings.card_gap.size + ( settings.card_gap.unit || 'px' ) 
				: '100px';

			var cardOffset = ( settings.card_top_offset && settings.card_top_offset.size ) 
				? settings.card_top_offset.size + 'px' 
				: '20px';
		#>

		<div class="rael-stacking-cards-wrapper" style="--sticky-top: {{{ stickyTop }}}; --card-gap: {{{ cardGap }}}; --card-offset: {{{ cardOffset }}};"
    		data-scroll-motion="{{{ settings.enable_scroll_motion }}}">
    	<# if ( settings.items_list && settings.items_list.length ) { #>
        	<# jQuery.each( settings.items_list, function( item, index ) { #>
					<div class="rael-stacking-card elementor-repeater-item-{{ item._id }}" 
					style="top: calc({{index}} * {{{ cardOffset }}});">
					
						<div class="rael-card-inner">
                    
                    <!-- Content -->
                    <div class="rael-card-content">
                        <# if ( settings.show_title == 'yes' && item.title ) { #>
                            <{{ settings.title_html_tag || 'div' }} class="rael-card-title">{{{ item.title }}}</{{ settings.title_html_tag || 'div' }}>
                        <# } #>
                        <# if ( settings.show_description == 'yes' && item.desc ) { #>
                            <div class="rael-card-desc">{{{ item.desc }}}</div>
                        <# } #>
                        <# if ( settings.show_button == 'yes' && item.button_text && item.link_url && item.link_url.url ) { #>
                            <a href="{{ item.link_url.url }}" class="elementor-button rael-card-button">{{{ item.button_text }}}</a>
                        <# } #>

                        <!-- Graphic -->
                        <# 
                            var graphic_html = item.graphic_html || '';
                            var graphic_icon = item.graphic_icon || '';
                            var graphic_text = item.graphic_text || '';
                            var graphic_styles = '';
                            if ( settings.graphic_element_width && settings.graphic_element_width.size ) {
                                graphic_styles += 'width:' + settings.graphic_element_width.size + (settings.graphic_element_width.unit||'px') + ';';
                            }
                            if ( settings.graphic_element_height && settings.graphic_element_height.size ) {
                                graphic_styles += 'height:' + settings.graphic_element_height.size + (settings.graphic_element_height.unit||'px') + ';';
                            }
                            if ( settings.graphic_element_background_color ) {
                                graphic_styles += 'background-color:' + settings.graphic_element_background_color + ';';
                            }
                            if ( settings.graphic_element_border_radius ) {
                                graphic_styles += 'border-radius:' + 
                                    settings.graphic_element_border_radius.top + (settings.graphic_element_border_radius.unit||'px') + ' ' +
                                    settings.graphic_element_border_radius.right + (settings.graphic_element_border_radius.unit||'px') + ' ' +
                                    settings.graphic_element_border_radius.bottom + (settings.graphic_element_border_radius.unit||'px') + ' ' +
                                    settings.graphic_element_border_radius.left + (settings.graphic_element_border_radius.unit||'px') + ';';
                            }
                        #>
                        <# if ( graphic_html || graphic_icon || graphic_text ) { #>
                            <div class="rael-card-graphic" style="{{ graphic_styles }}">
                                <# if ( graphic_icon ) { #>
                                    <# if ( graphic_icon.value ) { #>
                                        <i class="{{ graphic_icon.value }}" style="font-size:{{ settings.graphic_element_icon_size.size }}{{ settings.graphic_element_icon_size.unit }};"></i>
                                    <# } #>
                                <# } else if ( graphic_html ) { #>
                                    {{{ graphic_html }}}
                                <# } else if ( graphic_text ) { #>
                                    <div class="rael-card-graphic-text">{{{ graphic_text }}}</div>
                                <# } #>
                            </div>
                        <# } #>
                    </div>

                    <!-- Image -->
                    <# if ( settings.show_image == 'yes' && item.image && item.image.url ) { #>
                        <div class="rael-card-media">
                            <img src="{{ item.image.url }}" alt="{{ item.title }}">
                        </div>
                    <# } #>

                </div>
            </div>
        	<# }); #>
    		<# } #>
		</div>
	<?php
	}

	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/stacking-cards/';
	}

}