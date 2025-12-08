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
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
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
 * RAEL Stacking Cards widget class.
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
		return array( 'stacking', 'cards' );
	}

	public function get_script_depends() {
		return array( 'rael-stacking-card' );
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
				'default' => 'items',
				'options' => array(
					'posts' => __( 'Posts', 'responsive-addons-for-elementor' ),
					'items' => __( 'Items', 'responsive-addons-for-elementor' ),
				),
				'render_type' => 'template', 
			)
		);
		$this->add_control(
			'posts_source_notice',
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => __( '<strong>Note:</strong> Only latest 4 published posts will be shown.', 'responsive-addons-for-elementor' ),
				'content_classes' => 'elementor-descriptor', 
				'condition'       => array(
					'source_type' => 'posts', 
				),
			)
		);
		$source_options = array(
			'post_title'    => __( 'Post Title', 'responsive-addons-for-elementor' ),
			'post_name'     => __( 'Post Name', 'responsive-addons-for-elementor' ),
			'post_intro'    => __( 'Post Intro', 'responsive-addons-for-elementor' ),
			'post_content'  => __( 'Post Content', 'responsive-addons-for-elementor' ),
			'post_image'    => __( 'Post Featured Image', 'responsive-addons-for-elementor' ),
			'post_date'     => __( 'Post Date', 'responsive-addons-for-elementor' ),
			'post_url'      => __( 'Post URL', 'responsive-addons-for-elementor' ),
			'post_meta'     => __( 'Post Meta Field', 'responsive-addons-for-elementor' ),
			'post_term'     => __( 'Post Term', 'responsive-addons-for-elementor' ),
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
				'render_type' => 'template', 
			)
		);

		$this->add_control(
			'title_meta_field',
			array(
				'label'       => __( 'Title Meta Field', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' =>  '',
				'condition'   => array(
					'source_type' => 'posts',
					'title_source' => 'post_meta', // shows only if post_meta is selected
				),
				'render_type' => 'template', 
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
				'render_type' => 'template', 
			)
		);

		$this->add_control(
			'description_meta_field',
			array(
				'label'       => __( 'Description Meta Field', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' =>  '',
				'condition'   => array(
					'source_type' => 'posts',
					'description_source' => 'post_meta', // shows only if post_meta is selected
				),
				'render_type' => 'template', 
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
				'render_type' => 'template', 
			)
		);
		$this->add_control(
			'link_meta_field',
			array(
				'label'       => __( 'Link Meta Field', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '',
				'condition'   => array(
					'source_type' => 'posts',
					'link_source' => 'post_meta', // shows only if post_meta is selected
				),
				'render_type' => 'template', 
			)
		);
		
		$btn_source_options = array(
			'post_title'    => __( 'Post Title', 'responsive-addons-for-elementor' ),
			'post_name'     => __( 'Post Name', 'responsive-addons-for-elementor' ),
			'post_meta'     => __( 'Post Meta Field', 'responsive-addons-for-elementor' ),
		);

		$this->add_control(
			'button_text_source',
			array(
				'label'     => __( 'Button Text Source', 'responsive-addons-for-elementor' ),
				'label_block' => true,
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'options'   => $btn_source_options,
				'condition'   => array(
					'source_type' => 'posts',
				),
				'render_type' => 'template', 
			)
		);
		$this->add_control(
			'button_meta_field',
			array(
				'label'       => __( 'Button Meta Field', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '',
				'condition'   => array(
					'source_type' => 'posts',
					'button_text_source' => 'post_meta', // shows only if post_meta is selected
				),
				'render_type' => 'template', 
			)
		);

		$img_source_options = array(
			'post_image'    => __( 'Post Featured Image', 'responsive-addons-for-elementor' ),
			'post_meta'     => __( 'Post Meta Field', 'responsive-addons-for-elementor' ),
		);
		$this->add_control(
			'image_source',
			array(
				'label'     => __( 'Image Source', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'  => true,
				'default'   => 'post_image',
				'options'   => $img_source_options,
				'condition'   => array(
					'source_type' => 'posts',
				),
				'render_type' => 'template', 
			)
		);
		$this->add_control(
			'image_meta_field',
			array(
				'label'       => __( 'Image Meta Field', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '',
				'condition'   => array(
					'source_type' => 'posts',
					'image_source' => 'post_meta', // shows only if post_meta is selected
				),
				'render_type' => 'template', 
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
				'render_type' => 'template', 
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
				'default' => __( 'Creative Approaches', 'responsive-addons-for-elementor' ),
				'render_type' => 'template', 
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
				'render_type' => 'template', 
			)
		);
		

		$repeater->add_control(
			'item_desc',
			array(
				'label'   => __( 'Description', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'Harness pioneering technologies that redefine business operations and drive unprecedented productivity.', 'responsive-addons-for-elementor' ),
				'condition' => array( 'content_type' => 'editor' ),
				'render_type' => 'template', 
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
				'render_type' => 'template', 
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
				'default'      => 'yes',
				'condition' => array( 'content_type' => 'editor' ),
				'render_type' => 'template', 
			)
		);

		$repeater->add_control(
			'button_text',
			array(
				'label'     => __( 'Button Text', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Learn More', 'responsive-addons-for-elementor' ),
				'condition' => array( 'show_button' => 'yes', 'content_type' => 'editor', ),
				'render_type' => 'template', 
			)
		);

		$repeater->add_control(
			'item_image',
			array(
				'label'   => __( 'Image', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => Utils::get_placeholder_image_src() ),
				'condition' => array( 'content_type' => 'editor' ),
				'render_type' => 'template', 
			)
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'item_image_size',
				'default'   => 'medium_large',
				'separator' => 'none',
				'condition' => array( 'content_type' => 'editor' ),
				'render_type' => 'template', 
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
				'render_type' => 'template', 
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
				'render_type' => 'template', 
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
				'render_type' => 'template', 
			)
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'graphic_image_size',
				'default' => 'medium_large',
				'render_type' => 'template', 
			)
		);

		$repeater->add_control(
			'graphic_icon',
			array(
				'label' => __( 'Graphic Icon', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::ICONS,
				 'default'     => array(
					'value'   => 'fas fa-lightbulb',
					'library' => 'fa-solid',
				 ),
				'render_type' => 'template', 
			)
		);

		$repeater->add_control(
			'graphic_text',
			array(
				'label'   => __( 'Graphic Text', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'D', 'responsive-addons-for-elementor' ),
				'render_type' => 'template', 
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
				'render_type' => 'template', 
			)
		);

		$repeater->add_control(
			'background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array( 'background_type' => 'color' ),
				'render_type' => 'template', 
			)
		);

		$repeater->add_control(
			'background_image',
			array(
				'label'     => __( 'Background Image', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array( 'background_type' => 'image' ),
				'render_type' => 'template', 
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
					 array(
                'item_title'    => __('Creative Approaches','responsive-addons-for-elementor'),
                'item_desc'     => __('Harness pioneering technologies that redefine business operations and drive unprecedented productivity.','responsive-addons-for-elementor'),
                'item_image'    => array( 'url' => RAEL_ASSETS_URL . 'images/stacking-cards/card1.png'),
                'graphic_icon' => array('value' => 'fas fa-lightbulb', 'library' => 'fa-solid'),
            ),
            array(
                'item_title'    => __('Unified Experience','responsive-addons-for-elementor'),
                'item_desc'     => __('Seamlessly unify your tools and systems through flexible integrations designed around you.','responsive-addons-for-elementor'),
                'item_image'    => array( 'url' => RAEL_ASSETS_URL . 'images/stacking-cards/card2.jpg' ),
                'graphic_icon' => array('value' => 'fas fa-cogs', 'library' => 'fa-solid'),
            ),
            array(
                'item_title'    => __('User-Driven Innovation','responsive-addons-for-elementor'),
                'item_desc'     => __('Empower your audience through seamless experiences, intuitive interfaces, and thoughtful design.','responsive-addons-for-elementor'),
                'item_image'    => array( 'url' => RAEL_ASSETS_URL . 'images/stacking-cards/card3.jpg' ),
                'graphic_icon' => array('value' => 'fas fa-file-alt', 'library' => 'fa-solid'),
            ),
            array(
                'item_title'    => __('Steady Expansion','responsive-addons-for-elementor'),
                'item_desc'     => __('Unlock your organization’s potential with powerful strategies designed for long-term success and expansion.','responsive-addons-for-elementor'),
                'item_image'    => array( 'url' => RAEL_ASSETS_URL . 'images/stacking-cards/card4.jpg' ),
                'graphic_icon' => array('value' => 'fas fa-chart-line', 'library' => 'fa-solid'),
            ),
				),
				'title_field' => '{{ item_title }}',
				'render_type' => 'template', 
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
			'render_type' => 'template', 
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
			'render_type' => 'template', 
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
			'render_type' => 'template', 
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
			'render_type' => 'template', 
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
			'render_type' => 'template', 
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
			'render_type' => 'template', 
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
			'render_type' => 'template', 
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
			'render_type'  => 'template',
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
			'render_type' => 'template', 
        )
    );

    $this->add_control(
        'show_description',
        array(
            'label' => __( 'Show Description', 'responsive-addons-for-elementor' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
			'render_type' => 'template', 
        )
    );

    $this->add_control(
        'show_button',
        array(
            'label' => __( 'Show Button', 'responsive-addons-for-elementor' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
			'render_type' => 'template', 
        )
    );

    $this->add_control(
        'button_text',
        array(
            'label' => __( 'Button Text', 'responsive-addons-for-elementor' ),
            'type' => Controls_Manager::TEXT,
            'default' => __( 'Read More', 'responsive-addons-for-elementor' ),
            'condition' => array( 'show_button' => 'yes' ),
			'render_type' => 'template', 
        )
    );

    $this->add_control(
        'show_image',
        array(
            'label' => __( 'Show Image', 'responsive-addons-for-elementor' ),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'yes',
			'render_type' => 'template', 
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
			'render_type' => 'template', 
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
				'render_type' => 'template', 
			)
		);

		$this->add_control(
			'transform_origin_x',
			array(
				'label' => __( 'Transform Origin X', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array( 'size' => 0 ),
				'range' => array(
					'px' => array( 'min' => 0, 'max' => 100 ),
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-stacking-card' => '--rae-transform-origin-x: {{SIZE}}{{UNIT}};',
				),
				'dynamic' => array( 'active' => true ), 
				'render_type' => 'template', 
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
				'render_type' => 'template', 
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
				'render_type' => 'template', 
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
				'render_type' => 'template', 
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
				'default' => array( 'size' => 0 ),
				'dynamic' => array( 'active' => true ),
				'selectors' => array(
					'{{WRAPPER}}' => '--rae-scale: {{SIZE}};',
				), 
				'render_type' => 'template', 
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
				'render_type' => 'template', 
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
				'render_type' => 'template', 
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
				'render_type' => 'template', 
			),
		);
		$this->add_control(
			'scroll_rotation',
			array(
				'label'   => __( 'Rotation (deg)', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'size_units' => array( 'deg' ),
				'range'   => array(
					'deg' => array( 'min' => 0, 'max' => 100, 'step' => 1, ),
				),
				'default' => array( 'size' => 0, 'unit' => 'deg' ),
				'dynamic' => array( 'active' => true ), 
				'selectors' => array( 
					'{{WRAPPER}} .rael-stacking-card' => '--rae-rotate: {{SIZE}}deg;',
				),
				'render_type' => 'template', 
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
					'{{WRAPPER}} .rael-stacking-card' => 'width: {{SIZE}}{{UNIT}};',
				),
				'render_type' => 'ui', 
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
				'selectors' => array(
					'{{WRAPPER}} .rael-stacking-card' => 'min-height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .rael-card-inner'  => 'height: {{SIZE}}{{UNIT}};',
				),
				'render_type' => 'template', 
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
				'render_type' => 'template', 
			)
		);
		// Box Shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_box_shadow',
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-stacking-card',
				'render_type' => 'template', 
				'fields_options' => array(
					'box_shadow_type' => array(
						'default' => 'classic',
					),
					'color' => array(
						'default' => 'rgba(0, 0, 0, 0.5)',
					),
					'blur' => array(
						'default' => 10,
					),
					'spread' => array(
						'default' => 0,
					),
					'horizontal' => array(
						'default' => 0,
					),
					'vertical' => array(
						'default' => 0,
					),
				),
			)
		);
		// Border Radius
		$this->add_responsive_control(
			'card_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'    => 20,
					'right'  => 20,
					'bottom' => 20,
					'left'   => 20,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-stacking-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'render_type' => 'template', 
			)
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name' => 'card_border',
				'selector' => '{{WRAPPER}} .rael-stacking-card',
				'render_type' => 'template', 
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
				'render_type' => 'template', 
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
				'default'    => array(
					'size' => 60,
					'unit' => '%',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-card-content' => 'width: {{SIZE}}{{UNIT}};',
				),
				'render_type' => 'template', 
			)
		);
		$this->add_responsive_control(
			'content_padding',
			array(
				'label' => __( 'Content Padding', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default'    => array(
					'top'    => 40,
					'right'  => 40,
					'bottom' => 40,
					'left'   => 40,
					'unit'   => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-card-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'render_type' => 'template', 
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
				'render_type' => 'template', 
			)
		);
		$this->add_control(
			'content_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-card-content' => 'background-color: {{VALUE}};',
				),
				'render_type' => 'template', 
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
				'default'    => array(
					'size' => 50,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-card-graphic' => 'width: {{SIZE}}{{UNIT}} !important;', 
            		'{{WRAPPER}} .rael-card-graphic img' => 'width: 100% !important; height: auto !important;', 
					'{{WRAPPER}} .rael-card-graphic svg' => 'width: {{SIZE}}{{UNIT}} !important;', 
				),
				'render_type' => 'template', 
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
				'default'    => array(
					'size' => 50,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-card-graphic' => 'height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .rael-card-graphic img' => 'height: 100% !important; object-fit: cover !important;',
					'{{WRAPPER}} .rael-card-graphic svg' => 'height: 100% !important;',
				),
				'render_type' => 'template', 
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
				'default'    => array(
					'size' => 25,
					'unit' => 'px',
				),
				'selectors' => array(
				    '{{WRAPPER}} .rael-card-graphic svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;', // <- add here
            		'{{WRAPPER}} .rael-card-graphic i'   => 'font-size: {{SIZE}}{{UNIT}} !important;', // existing for icon fonts
				),
				'render_type' => 'template', 
			)
		);
		
		$this->add_control(
			'graphic_element_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'    => '#535353', 
				'selectors' => array(
					'{{WRAPPER}} .rael-card-graphic' => 'background-color: {{VALUE}};',
				),
				'render_type' => 'template', 
			)
		);
		$this->add_control(
			'graphic_element_color',
			array(
				'label' => __( 'Color', 'responsive-addons-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'    => '#FFFFFF', 
				'selectors' => array(
					'{{WRAPPER}} .rael-card-graphic svg path' => 'fill: {{VALUE}} !important;',
				),
				'render_type' => 'template', 
			)
		);
		$this->add_responsive_control(
			'graphic_element_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ), // allow multiple units
				'default'    => array(
					'top'    => 50,
					'right'  => 50,
					'bottom' => 50,
					'left'   => 50,
					'unit'   => 'px', 
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-card-graphic' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'render_type' => 'template', 
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'graphic_element_box_shadow',
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-card-graphic',
				'render_type' => 'template', 
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'graphic_element_icon_text_shadow',
				'label'    => __( 'Icon/Text Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-card-graphic',
				'render_type' => 'template', 
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name' => 'graphic_element_border',
				'selector' => '{{WRAPPER}} .rael-card-graphic',
				'render_type' => 'template', 
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
				'fields_options' => array(
					'typography' => array(
						'default' => 'Roboto',
					),
					'font_size' => array(
						'default' => array(
							'unit' => 'px',
							'size' => 50,
						),
					),
					'font_weight' => array(
						'default' => 400,
					),
				),
				'render_type' => 'ui',
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
				'render_type' => 'template', 
			)
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'title_text_shadow',
				'label'    => __( 'Text Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-card-title',
				'render_type' => 'template', 
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
				'render_type' => 'template', 
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
				'fields_options' => array(
					'typography' => array(
						'default' => 'Roboto',
					),
					'font_size' => array(
						'default' => array(
							'unit' => 'px',
							'size' => 20,
						),
					),
					'font_weight' => array(
						'default' => 400,
					),
				),
				'render_type' => 'ui', 
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
				'render_type' => 'template', 
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'description_text_shadow',
				'label'    => __( 'Text Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '{{WRAPPER}} .rael-card-desc',
				'render_type' => 'template', 
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
				'render_type' => 'template', 
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
				'render_type' => 'template', 
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
				'render_type' => 'template', 
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
				'render_type' => 'template', 
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'    => 10,
					'right'  => 15,
					'bottom' => 10,
					'left'   => 15,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-card-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'render_type' => 'template', 
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .rael-card-button',
				'render_type' => 'template', 
				'typography' => array(
					'default' => 'custom',
				),
				'fields_options' => array(
					'font_family' => array(
						'default' => 'Roboto',
					),
					'font_size' => array(
						'default' => array(
							'unit' => 'px',
							'size' => 18,
						),
					),
				),
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
				'render_type' => 'template', 
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
				'default'    => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .rael-card-button' => 'color: {{VALUE}};',
				),
				'render_type' => 'template', 
			)
		);

		$this->add_control(
			'button_background_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'    => '#535353',
				'selectors' => array(
					'{{WRAPPER}} .rael-card-button' => 'background-color: {{VALUE}};',
				),
				'render_type' => 'template', 
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'    => 8,
					'right'  => 8,
					'bottom' => 8,
					'left'   => 8,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-card-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'render_type' => 'template', 
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .rael-card-button',
				'render_type' => 'template', 
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'button_text_shadow',
				'selector' => '{{WRAPPER}} .rael-card-button',
				'render_type' => 'template', 
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

		$card_gap_size = ! empty( $settings['card_gap']['size'] ) ? $settings['card_gap']['size'] : 50;
		$card_gap_unit = $settings['card_gap']['unit'] ?? 'px';

		$card_offset = ! empty( $settings['card_top_offset']['size'] )
			? $settings['card_top_offset']['size'] . ( $settings['card_top_offset']['unit'] ?? 'px' )
			: '20px';

		// ---------- Items (repeater source) ----------
		if ( isset($settings['source_type']) && 'items' === $settings['source_type'] && ! empty( $settings['items_list'] ) ) {
			foreach ( $settings['items_list'] as $item ) {
				// Main image
				$image_html = '';
				if ( ! empty( $item['item_image']['id'] ) ) {
					$image_html = \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item, 'item_image_size', 'item_image' );
				} elseif ( ! empty( $item['item_image']['url'] ) ) {
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
					'title'        => isset( $item['item_title'] ) ? sanitize_text_field( $item['item_title'] ) : '',
					'desc'         => isset( $item['item_desc'] ) ? wp_kses_post( $item['item_desc'] ) : '',
					'image_html'   => $image_html,
					'image_url'    => '',
					'button_text'  => $item['button_text'] ?? '',
					'link_url'     => $link['url'] ?? '',
					'link_external'=> ! empty( $link['is_external'] ),
					'link_nofollow'=> ! empty( $link['nofollow'] ),
					'show_graphic_element' => $settings['show_graphic_element'] ?? 'none',
					'graphic_html' => $graphic_html,
					'graphic_icon' => $item['graphic_icon'] ?? null,
					'graphic_text' => $item['graphic_text'] ?? '',
					'background_color' => $item['background_color'] ?? '',
					'background_image' => ! empty( $item['background_image']['url'] ) ? $item['background_image']['url'] : '',
				];
			}
		}

		// ---------- Posts source ----------
		if ( isset($settings['source_type']) && 'posts' === $settings['source_type'] ) {
			$query = new \WP_Query( [
				'post_type'           => 'post',
				'post_status'         => 'publish',   // only published
				'posts_per_page'      => 4,           // limit
				'orderby'             => 'date',      // sort by publish date
				'order'               => 'DESC',      // latest first
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
						'post_id'       => $post_id,
						'title'        => get_the_title(),
						'desc'         => wp_trim_words( get_the_excerpt() ?: wp_strip_all_tags( get_the_content() ), 24 ),
						'image_html'   => '',
						'image_url'    => $img_url ?: '',
						'button_text'  => $settings['button_text'] ?? __( 'Read More', 'responsive-addons-for-elementor' ),
						'link_url'     => get_permalink( $post_id ),
						'link_external'=> false,
						'link_nofollow'=> false,
						'show_graphic_element' => $settings['show_graphic_element'] ?? 'none',
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

		$total_cards = count($items);

		$wrapper_classes = 'rael-stacking-cards-wrapper';
		if ( $rtl ) {
			$wrapper_classes .= ' rtl-enabled';
		}

		// Get the card height from settings
		$card_height_value = ! empty( $settings['card_height']['size'] ) ? $settings['card_height']['size'] : 600;
		$card_unit = ! empty( $settings['card_height']['unit'] ) ? $settings['card_height']['unit'] : 'px';

		$this->add_render_attribute( 'wrapper', 'class', $wrapper_classes );

		$this->add_render_attribute('wrapper', 'data-card-height', $card_height_value . $card_unit);
		$this->add_render_attribute('wrapper', 'data-card-offset', $card_offset);
		$this->add_render_attribute('wrapper', 'data-card-gap', $card_gap_size . $card_gap_unit );

		// Inline CSS variables for wrapper
		$wrapper_style = '--card-gap:' . $card_gap_size . $card_gap_unit . ';';
		$this->add_render_attribute( 'wrapper', 'style', $wrapper_style );

		// optional: add data attribute for scroll motion toggle
		if ( ! empty( $settings['enable_scroll_motion'] ) ) {
			$this->add_render_attribute( 'wrapper', 'data-scroll-motion', 'true' );
		}

		echo '<div ' . wp_kses_post($this->get_render_attribute_string( 'wrapper' )) . '>';

		
		$min_scale = 0.85; // first (back) card
		$max_scale = 1.0;  // front card

		$scale_step = $total_cards > 1 ? ($max_scale - $min_scale) / ($total_cards - 1) : 0;
		$yStep = 10; // vertical offset per card (optional)
		$z = 0; // z-depth

		foreach ( $items as $index => $item ) {
			$sticky_top_item = ( $sticky_top + ( $index * 10 ) ) . $sticky_unit;
			// Get numeric values
			$origin_x_val = ! empty( $settings['transform_origin_x']['size'] ) 
				? $settings['transform_origin_x']['size'] 
				: '0';
			$origin_y_val = ! empty( $settings['transform_origin_y']['size'] ) 
				? $settings['transform_origin_y']['size'] 
				: '0';

			// Get units
			$origin_x_unit = $settings['transform_origin_x']['unit'] ?? 'px';
			$origin_y_unit = $settings['transform_origin_y']['unit'] ?? 'px';

			
			
        $scale = $min_scale + ($index * $scale_step);
		$overlap_step = max(1, $card_gap_size / 6); // adjust divisor to control tightness
        // Offsets per card (use origin values as step sizes)
		$offsetX = 0; // keep centered, or use $index * $card_gap_size for horizontal gap
   		$offsetY = $index * $overlap_step;

		$offsetXVal = is_numeric($offsetX) ? $offsetX . $card_gap_unit : $offsetX;
		$offsetYVal = is_numeric($offsetY) ? $offsetY . $card_gap_unit : $offsetY;

		// sticky positioning
		$offset_value    = 'calc(' . ($index+1) . ' * ' . $card_gap_size . $card_gap_unit . ')';
		// ✅ Conditional scale logic — omit scale() if scale_step is 0
		if (empty($settings['scroll_scale']['size']) || $settings['scroll_scale']['size'] == 0) {
			// No scaling applied at all
			$transform = "translate3d({$offsetXVal}, {$offsetYVal}, {$z}px)";
		} 
		elseif ($settings['scroll_scale']['size'] < 0) {
			// Negative scale — skip scale on load (only apply on scroll)
			$transform = "translate3d({$offsetXVal}, {$offsetYVal}, {$z}px)";
		} 
		else {
			// Positive scale — include scaling
			$transform = "translate3d({$offsetXVal}, {$offsetYVal}, {$z}px) scale({$scale})";
		}

        $transform_origin = ($origin_x_val == 0 && $origin_y_val == 0) ? "50% 50%" : $origin_x_val . $origin_x_unit. ' ' . $origin_y_val . $origin_y_unit;
		$current_item_background_color = ! empty( $item['background_color'] ) ? 'background-color:' . esc_attr( $item['background_color'] ) . ';' : '';
		$current_item_background_image = ! empty( $item['background_image'] ) 
			? 'background-image:url(' . esc_url( $item['background_image'] ) . ');' 
			: '';
		
        // Add GSAP data attributes for scroll effect
		$style  = 'top:' . esc_attr($sticky_top_item) . ';';
		$style .= 'margin-top:' . esc_attr($offset_value) . ';';
		$style .= 'transform-origin:' . esc_attr($transform_origin) . ';';
		$style .= 'transform:' . esc_attr($transform) . ';';
		$style .= $current_item_background_color;
		$style .= $current_item_background_image;
		


        $this->add_render_attribute(
            'card' . $index,
            array(
                'class' => array('rael-stacking-card', 'elementor-repeater-item-' . ($index+1)),
                'style' => $style,
                'data-index'       => $index,
                'data-translate-x' => $settings['transform_origin_x']['size'] ?? 0,
                'data-translate-y' => $settings['transform_origin_y']['size'] ?? 0,
				'data-rotate'      => $settings['normal_rotation']['size'] ?? 0,
                'data-scrollrotate'      => $settings['scroll_rotation']['size'] ?? 0,
                'data-scale'       => $settings['scroll_scale']['size'] ?? 0,
                'data-blur'        => $settings['scroll_blur']['size'] ?? 0,
				'data-greyscale'   => $settings['normal_greyscale']['size'] ?? 0.2,
                'data-scrollgreyscale'   => $settings['scroll_greyscale']['size'] ?? 0.2,
				'data-offset' 	   => $offset_value,
				'data-base-x'         => $offsetX,
				'data-base-y'         => $offsetY,
				'data-base-scale'     => $scale,
				'data-gap'         => $settings['card_gap']['size'] . $settings['card_gap']['unit'], 
				'data-opacity' 	   => $settings['scroll_opacity']['size'] ?? 0,
            )
        );
			
			echo '<div ' . wp_kses_post($this->get_render_attribute_string( 'card' . $index )) . '>';

			// Card inner (flex container)
        	echo '<div class="rael-card-inner">';
			
			// Content
			echo '<div class="rael-card-content">';

			// Graphic
			$graphic_output = '';

			if ( ! empty( $item['show_graphic_element'] ) ) {
				switch ( $item['show_graphic_element'] ) {
					case 'icon':
						if ( ! empty( $item['graphic_icon'] ) ) {
							ob_start();
							\Elementor\Icons_Manager::render_icon(
								$item['graphic_icon'],
								[ 'aria-hidden' => 'true' ]
							);
							$graphic_output = ob_get_clean();
						}
						break;

					case 'image':
						if ( ! empty( $item['graphic_html'] ) ) {
							$graphic_output = $item['graphic_html'];
						}
						break;

					case 'text':
						if ( ! empty( $item['graphic_text'] ) ) {
							$graphic_output = '<div class="rael-card-graphic-text">' . esc_html( $item['graphic_text'] ) . '</div>';
						}
						break;

					case 'none':
					default:
						$graphic_output = '';
						break;
				}
			}

			if ( ! empty( $graphic_output ) ) {
				echo '<div class="rael-card-graphic">' . $graphic_output . '</div>';
			}

			// For Post Titles
			$final_title_parts = [];

			if ( ! empty( $settings['title_source'] ) ) {
				foreach ( $settings['title_source'] as $source ) {
					switch ( $source ) {
						case 'post_name':
							if ( isset( $item['post_id'] ) ) {
								$final_title_parts[] = get_post_field( 'post_name', $item['post_id'] );
							}
							break;

						case 'post_intro':
							if ( isset( $item['post_id'] ) ) {
								$intro = get_post_meta( $item['post_id'], '_intro', true ); // adjust key if custom
								if ( ! $intro ) {
									$intro = wp_trim_words( get_the_excerpt( $item['post_id'] ), 15 );
								}
								$final_title_parts[] = $intro;
							}
							break;

						case 'post_content':
							if ( isset( $item['post_id'] ) ) {
								$content = get_post_field( 'post_content', $item['post_id'] );
								$final_title_parts[] = wp_trim_words( wp_strip_all_tags( $content ), 20 );
							}
							break;

						case 'post_image':
							if ( ! empty( $item['image_url'] ) ) {
								$final_title_parts[] = '<img src="' . esc_url( $item['image_url'] ) . '" alt="">';
							}
							break;

						case 'post_date':
							if ( isset( $item['post_id'] ) ) {
								$final_title_parts[] = get_the_date( '', $item['post_id'] );
							}
							break;

						case 'post_url':
							if ( ! empty( $item['link_url'] ) ) {
								$final_title_parts[] = esc_url( $item['link_url'] );
							}
							break;

						case 'post_meta':
							if ( ! empty( $settings['title_meta_field'] ) ) {
								$meta_value = get_post_meta( $item['post_id'], $settings['title_meta_field'], true );
								if ( $meta_value ) {
									$final_title_parts[] = $meta_value;
								}
							}
							break;

						case 'post_term':
							if ( isset( $item['post_id'] ) ) {
								$post_id = $item['post_id'];
								$all_terms = [];

								// Get all taxonomies for this post type
								$taxonomies = get_object_taxonomies( get_post_type( $post_id ), 'names' );

								foreach ( $taxonomies as $taxonomy ) {
									$terms = wp_get_post_terms( $post_id, $taxonomy, [ 'fields' => 'names' ] );
									if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
										$all_terms = array_merge( $all_terms, $terms );
									}
								}

								if ( ! empty( $all_terms ) ) {
									$final_title_parts[] = implode( ', ', $all_terms );
								}
							}
							break;
					}
				}
			}
			$final_title = implode( ' ', array_filter( $final_title_parts ) );

			// Add render attribute
			$this->add_render_attribute( 'rael_card_title', 'class', 'rael-card-title' );

			$tag = ! empty( $settings['title_html_tag'] ) ? $settings['title_html_tag'] : 'div';
			if ( !empty($final_title) ) {
				echo '<' . esc_html( $tag ) . ' ' . wp_kses_post($this->get_render_attribute_string( 'rael_card_title' )) . '>' . esc_html( $final_title ) . '</' . esc_html( $tag ) . '>';

			}
			else if ( !empty ($settings['show_title']) && ! empty( $item['title'] )  ){
				echo '<' . esc_html( $tag ) . ' ' . wp_kses_post($this->get_render_attribute_string( 'rael_card_title' )) . '>' . esc_html( $item['title'] ) . '</' . esc_html( $tag ) . '>';

			}
			
			//Description 
			$final_desc_parts = [];

			if ( ! empty( $settings['description_source'] ) ) {
				$description_sources = is_array( $settings['description_source'] )
				? $settings['description_source']
				: array( $settings['description_source'] );
				foreach ( $description_sources as $source ) {
					switch ( $source ) {
						case 'post_title':
							if ( isset( $item['post_id'] ) ) {
								$final_desc_parts[] = get_post_field( 'post_title', $item['post_id'] );
							}
							break;

						case 'post_name':
							if ( isset( $item['post_id'] ) ) {
								$final_desc_parts[] = get_post_field( 'post_name', $item['post_id'] );
							}
							break;

						case 'post_intro':
							if ( isset( $item['post_id'] ) ) {
								$introdesc = get_post_meta( $item['post_id'], '_intro', true ); // adjust key if custom
								if ( ! $introdesc ) {
									$introdesc = wp_trim_words( get_the_excerpt( $item['post_id'] ), 15 );
								}
								$final_desc_parts[] = $introdesc;
							}
							break;

						case 'post_content':
							if ( isset( $item['post_id'] ) ) {
								$content_desc = get_post_field( 'post_content', $item['post_id'] );
								$final_desc_parts[] = wp_trim_words( wp_strip_all_tags( $content_desc ), 20 );
							}
							break;

						case 'post_image':
							if ( ! empty( $item['image_url'] ) ) {
								$final_desc_parts[] = '<img src="' . esc_url( $item['image_url'] ) . '" alt="">';
							}
							break;

						case 'post_date':
							if ( isset( $item['post_id'] ) ) {
								$final_desc_parts[] = get_the_date( '', $item['post_id'] );
							}
							break;

						case 'post_url':
							if ( ! empty( $item['link_url'] ) ) {
								$final_desc_parts[] = esc_url( $item['link_url'] );
							}
							break;

						case 'post_meta':
							if ( ! empty( $settings['title_meta_field'] ) ) {
								$meta_value = get_post_meta( $item['post_id'], $settings['title_meta_field'], true );
								if ( $meta_value ) {
									$final_desc_parts[] = $meta_value;
								}
							}
							break;
						case 'post_term':
							if ( isset( $item['post_id'] ) ) {
								$post_id = $item['post_id'];
								$all_terms = [];

								// Get all taxonomies for this post type
								$taxonomies = get_object_taxonomies( get_post_type( $post_id ), 'names' );

								foreach ( $taxonomies as $taxonomy ) {
									$terms = wp_get_post_terms( $post_id, $taxonomy, [ 'fields' => 'names' ] );
									if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
										$all_terms = array_merge( $all_terms, $terms );
									}
								}

								if ( ! empty( $all_terms ) ) {
									$final_desc_parts[] = implode( ', ', $all_terms );
								}
							}
							break;
					}
				}
			}
			$final_desc = implode( ' ', array_filter( $final_desc_parts ) );

			$this->add_render_attribute( 'rael_card_desc', 'class', 'rael-card-desc' );

			if ( ! empty( $final_desc ) ) {
				echo '<div ' . wp_kses_post($this->get_render_attribute_string( 'rael_card_desc' )) . '>' . wp_kses_post( $final_desc ) . '</div>';
			} 
			else if ( ! empty( $settings['show_description'] ) && ! empty( $item['desc'] ) ) {
				echo '<div ' . wp_kses_post($this->get_render_attribute_string( 'rael_card_desc' )) . '>' . wp_kses_post( $item['desc'] ) . '</div>';
			}

			// Button render
			$final_button_parts = [];

			if ( ! empty( $settings['button_text_source'] ) ) { // assume you added a button_source control
				foreach ( $settings['button_text_source'] as $source ) {
					switch ( $source ) {

                case 'post_title':
                    if ( isset( $item['post_id'] ) ) {
                        $final_button_parts[] = get_post_field( 'post_title', $item['post_id'] );
                    }
                    break;

                case 'post_name':
                    if ( isset( $item['post_id'] ) ) {
                        $final_button_parts[] = get_post_field( 'post_name', $item['post_id'] );
                    }
                    break;
				case 'post_content':
						if ( isset( $item['post_id'] ) ) {
							$content_btn = get_post_field( 'post_content', $item['post_id'] );
							$final_button_parts[] = wp_trim_words( wp_strip_all_tags( $content_btn ), 20 );
						}
						break;
				case 'post_intro':
							if ( isset( $item['post_id'] ) ) {
								$introbtn = get_post_meta( $item['post_id'], '_intro', true ); // adjust key if custom
								if ( ! $introbtn ) {
									$introbtn = wp_trim_words( get_the_excerpt( $item['post_id'] ), 15 );
								}
								$final_button_parts[] = $introbtn;
							}
							break;
                case 'post_meta':
                    if ( ! empty( $settings['button_meta_field'] ) && isset( $item['post_id'] ) ) {
                        $meta_value = get_post_meta( $item['post_id'], $settings['button_meta_field'], true );
                        if ( $meta_value ) {
                            $final_button_parts[] = $meta_value;
                        }
                    }
                    break;

                case 'post_term':
                    if ( isset( $item['post_id'] ) ) {
                        $post_id = $item['post_id'];
                        $all_terms = [];

                        $taxonomies = get_object_taxonomies( get_post_type( $post_id ), 'names' );
                        foreach ( $taxonomies as $taxonomy ) {
                            $terms = wp_get_post_terms( $post_id, $taxonomy, [ 'fields' => 'names' ] );
                            if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
                                $all_terms = array_merge( $all_terms, $terms );
                            }
                        }

                        if ( ! empty( $all_terms ) ) {
                            $final_button_parts[] = implode( ', ', $all_terms );
                        }
                    }
                    break;

                case 'post_image':
					if ( ! empty( $item['image_url'] ) ) {
						$final_button_parts[] = '<img src="' . esc_url( $item['image_url'] ) . '" alt="">';
					}
					break;

				case 'post_date':
					if ( isset( $item['post_id'] ) ) {
						$final_button_parts[] = get_the_date( '', $item['post_id'] );
					}
					break;
				case 'post_url':
						if ( ! empty( $item['link_url'] ) ) {
							$final_button_parts[] = esc_url( $item['link_url'] );
						}
						break;
            }
				}
			}

			$final_button_text = implode( ' ', array_filter( $final_button_parts ) );

			// Determine button URL: post meta first, fallback to $item['link_url']
			$button_url = '';
			if ( isset( $item['post_id'] ) && ! empty( $settings['link_meta_field'] ) ) {
				$meta_url = get_post_meta( $item['post_id'], $settings['link_meta_field'], true );
				if ( ! empty( $meta_url ) ) {
					$button_url = $meta_url;
				}
			}
			if ( empty( $button_url ) && ! empty( $item['link_url'] ) ) {
				$button_url = $item['link_url'];
			}

			if ( ! empty( $final_button_text ) ) {
				$this->add_link_attributes( 'button_' . $index, [
					'url'         => $button_url,
					'is_external' => $item['link_external'],
					'nofollow'    => $item['link_nofollow'],
				] );

				echo '<div><a ' . wp_kses_post($this->get_render_attribute_string( 'button_' . $index )) . ' class="elementor-button rael-card-button">';
				echo esc_html( $final_button_text );
				echo '</a></div>';
			}
			else if ( !empty($settings['show_button']) && ! empty( $item['button_text'] ) && ! empty( $item['link_url'] ) ) {
				$this->add_link_attributes( 'button_' . $index, [
					'url'         => $item['link_url'],
					'is_external' => $item['link_external'],
					'nofollow'    => $item['link_nofollow'],
				] );
				echo '<div><a ' . wp_kses_post($this->get_render_attribute_string( 'button_' . $index )) . ' class="elementor-button rael-card-button">';
				echo esc_html( $item['button_text'] );
				echo '</a></div>';
			}

			echo '</div>'; // content

			// Image
			$image_output = '';
			// Loop through selected image sources
			if ( ! empty( $settings['image_source'] ) && isset( $item['post_id'] ) ) {
				// Ensure $settings['image_source'] is an array
				$image_sources = is_array( $settings['image_source'] ) 
						? $settings['image_source'] 
						: array( $settings['image_source'] );
				foreach ( $image_sources as $source ) {
					// Skip if we have already found an image
					if ( ! empty( $image_output ) ) {
						continue;
					}

					switch ( $source ) {

						case 'post_image':
							$post_thumbnail_id = get_post_thumbnail_id( $item['post_id'] );
							if ( $post_thumbnail_id ) {
								$image_url = wp_get_attachment_image_url( $post_thumbnail_id, 'full' );
								if ( $image_url ) {
									$image_output = '<img src="' . esc_url( $image_url ) . '" alt="">';
								}
							}
							break;

						case 'post_meta':
							if ( ! empty( $settings['image_meta_field'] ) ) {
								$meta_image_url = get_post_meta( $item['post_id'], $settings['image_meta_field'], true );
								if ( ! empty( $meta_image_url ) ) {
									$image_output = '<img src="' . esc_url( $meta_image_url ) . '" alt="">';
								}
							}
							break;
					}
				}
			}

			// Fallback to static item image
			if ( empty( $image_output ) ) {
				if ( ! empty( $item['image_html'] ) ) {
					$image_output = $item['image_html'];
				} elseif ( ! empty( $item['image_url'] ) ) {
					$image_output = '<img src="' . esc_url( $item['image_url'] ) . '" alt="">';
				}
			}

			if ( !empty($settings['show_image']) && ! empty( $image_output ) ) {
				echo '<div class="rael-card-media">' . wp_kses_post( $image_output ) . '</div>';
			}

			echo '</div>'; // .rael-card-inner
			
			echo '</div>'; // card
		}

		echo '</div>'; // wrapper
	}

	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/stacking-cards/';
	}

}
