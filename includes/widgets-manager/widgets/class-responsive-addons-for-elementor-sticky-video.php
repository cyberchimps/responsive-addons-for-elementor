<?php
/**
 * RAEL Sticky Video
 *
 * @since 1.3.2
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * RAEL Sticky Video class.
 *
 * @since 1.3.2
 */
class Responsive_Addons_For_Elementor_Sticky_Video extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.3.2
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-sticky-video';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.3.2
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Sticky Video', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve sticky video widget icon.
	 *
	 * @since 1.3.2
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-video-playlist rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the sticky video widget belongs to.
	 *
	 * @since 1.3.2
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 1.3.2
	 *
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'rael', 'sticky', 'video', 'floating', 'mini', 'video player', 'youtube', 'vimeo', 'scrollable video' );
	}

	/**
	 * Get Custom help URL
	 *
	 * @since 1.3.2
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/sticky-video';
	}
	/**
	 * Register controls for the Table of Contents widget.
	 */
	protected function register_controls() {
		// Content Tab.
		$this->register_sv_content_tab_sticky_options_section();
		$this->register_sv_content_tab_video_section();
		$this->register_sv_content_tab_image_overlay();

		// Style Tab.
		$this->register_sv_style_tab_sticky_video_interface_section();
		$this->register_sv_style_tab_player_section();
		$this->register_sv_style_tab_interface_section();
		$this->register_sv_style_tab_bar_section();
	}
	/**
	 * Register the Sticky Options section in the content tab.
	 */
	protected function register_sv_content_tab_sticky_options_section() {
		$this->start_controls_section(
			'rael_sv_content_tab_sticky_options_section',
			array(
				'label' => __( 'Sticky Options', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_sv_is_sticky',
			array(
				'label'        => __( 'Sticky', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Off', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'rael_sv_position',
			array(
				'label'     => __( 'Position', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'top-left'     => __( 'Top Left', 'responsive-addons-for-elementor' ),
					'top-right'    => __( 'Top Right', 'responsive-addons-for-elementor' ),
					'bottom-left'  => __( 'Bottom Left', 'responsive-addons-for-elementor' ),
					'bottom-right' => __( 'Bottom Right', 'responsive-addons-for-elementor' ),
				),
				'default'   => 'bottom-right',
				'condition' => array(
					'rael_sv_is_sticky' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register the Video section in the content tab.
	 */
	protected function register_sv_content_tab_video_section() {
		$this->start_controls_section(
			'rael_sv_content_tab_video_section',
			array(
				'label' => __( 'Video', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_sv_video_source',
			array(
				'label'   => __( 'Source', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'youtube'     => __( 'Youtube', 'responsive-addons-for-elementor' ),
					'vimeo'       => __( 'Vimeo', 'responsive-addons-for-elementor' ),
					'self_hosted' => __( 'Self Hosted', 'responsive-addons-for-elementor' ),
				),
				'default' => 'youtube',
			)
		);

		$this->add_control(
			'rael_sv_youtube_link',
			array(
				'label'       => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter your URL(Youtube)', 'responsive-addons-for-elementor' ),
				'default'     => 'https://www.youtube.com/watch?v=1eKjI0qjXPI',
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'rael_sv_video_source' => 'youtube',
				),
			)
		);

		$this->add_control(
			'rael_sv_vimeo_link',
			array(
				'label'       => __( 'Link', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter your URL(Vimeo)', 'responsive-addons-for-elementor' ),
				'default'     => 'https://vimeo.com/372249619',
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'rael_sv_video_source' => 'vimeo',
				),
			)
		);

		$this->add_control(
			'rael_sv_self_hosted',
			array(
				'label'      => __( 'Choose File', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::MEDIA,
				'dynamic'    => array(
					'action'     => true,
					'categories' => array(
						TagsModule::MEDIA_CATEGORY,
					),
				),
				'media_type' => 'video',
				'condition'  => array(
					'rael_sv_video_source' => 'self_hosted',
				),
			)
		);

		$this->add_control(
			'rael_sv_video_start_time',
			array(
				'label'       => __( 'Start Time', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 10000,
				'step'        => 1,
				'default'     => '',
				'description' => __( 'Specify a start time (in seconds)', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_sv_video_source' => 'self_hosted',
				),
			)
		);

		$this->add_control(
			'rael_sv_video_end_time',
			array(
				'label'       => __( 'End Time', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 10000,
				'step'        => 1,
				'default'     => '',
				'description' => __( 'Specify an end time (in seconds)', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_sv_video_source' => 'self_hosted',
				),
			)
		);

		$this->add_control(
			'rael_sv_video_options_heading',
			array(
				'label'     => __( 'Video Options', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'seperator' => 'before',
			)
		);

		$this->add_control(
			'rael_sv_autoplay',
			array(
				'label'        => __( 'Autoplay', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_block'  => false,
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'rael_sv_mute',
			array(
				'label'        => __( 'Mute', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_block'  => false,
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'rael_sv_loop',
			array(
				'label'        => __( 'Loop', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_block'  => false,
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'rael_sv_show_bar',
			array(
				'label'       => __( 'Show Bar', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_block' => false,
				'default'     => 'yes',
				'selectors'   => array(
					'{{WRAPPER}} .plyr__controls' => 'display: flex !important;',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register the Image Overlay section in the content tab.
	 */
	protected function register_sv_content_tab_image_overlay() {
		$this->start_controls_section(
			'rael_sv_content_tab_image_overlay_section',
			array(
				'label' => __( 'Image Overlay', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_sv_show_image_overlay_options',
			array(
				'label'        => __( 'Image Overlay', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_block'  => false,
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'rael_sv_overlay_image',
			array(
				'label'       => __( 'Choose Image', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::MEDIA,
				'label_block' => true,
				'default'     => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition'   => array(
					'rael_sv_show_image_overlay_options' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'rael_sv_overlay_image_size',
				'default'   => 'full',
				'condition' => array(
					'rael_sv_show_image_overlay_options' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_sv_show_play_icon',
			array(
				'label'        => __( 'Play Icon', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_block'  => false,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'rael_sv_show_image_overlay_options' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_sv_play_icon_new',
			array(
				'label'            => __( 'Choose Icon', 'responsive-addons-for-elementor' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'rael_sv_play_icon',
				'condition'        => array(
					'rael_sv_show_image_overlay_options' => 'yes',
					'rael_sv_show_play_icon'             => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register the Sticky Video Interface section in the style tab.
	 */
	protected function register_sv_style_tab_sticky_video_interface_section() {
		$this->start_controls_section(
			'rael_sv_style_tab_sticky_video_interface_section',
			array(
				'label'     => __( 'Sticky Video Interface', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_sv_is_sticky' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_sv_width',
			array(
				'label'     => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 100,
				'max'       => 500,
				'step'      => 1,
				'default'   => 300,
				'selectors' => array(
					'{{WRAPPER}} .rael-sticky-video__player.out' => 'width: {{VALUE}}px;',
				),
				'condition' => array(
					'rael_sv_is_sticky' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_sv_height',
			array(
				'label'     => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 55,
				'max'       => 280,
				'step'      => 1,
				'default'   => 169,
				'selectors' => array(
					'{{WRAPPER}} .rael-sticky-video__player.out' => 'height: {{VALUE}}px;',
				),
				'condition' => array(
					'rael_sv_is_sticky' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_sv_scroll_height_display_sticky',
			array(
				'label'     => __( 'Scroll Height To Display Sticky (%)', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 50,
						'max'  => 200,
						'step' => 1,
					),
				),
				'default'   => array(
					'unit' => 'px',
					'size' => 70,
				),
				'condition' => array(
					'rael_sv_is_sticky' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_sv_close_button_color',
			array(
				'label'     => __( 'Close Button Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-sticky-video__player-close' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_sv_is_sticky' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register the Player section in the style tab.
	 */
	protected function register_sv_style_tab_player_section() {
		$this->start_controls_section(
			'rael_sv_style_tab_player_section',
			array(
				'label' => __( 'Player', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_sv_player_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1200,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-sticky-video-wrapper' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_sv_player_border',
				'selector' => '{{WRAPPER}} .rael-sticky-video-wrapper',
			)
		);

		$this->add_responsive_control(
			'rael_sv_player_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-sticky-video-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rael-sticky-video__overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .rael-sticky-video__player' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register the Interface section in the style tab.
	 */
	protected function register_sv_style_tab_interface_section() {
		$this->start_controls_section(
			'rael_sv_style_tab_interface_section',
			array(
				'label' => __( 'Interface', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'rael_sv_interface_color',
			array(
				'label'     => __( 'Interface Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ADD8E6',
				'selectors' => array(
					'{{WRAPPER}} .plyr__control.plyr__tab-focus' => 'box-shadow: 0 0 0 5px {{VALUE}};',
					'{{WRAPPER}} .plyr__control--overlaid' => 'background: {{VALUE}};',
					'{{WRAPPER}} .plyr--video .plyr__control.plyr__tab-focus' => 'background: {{VALUE}};',
					'{{WRAPPER}} .plyr__control--overlaid' => 'background: {{VALUE}};',
					'{{WRAPPER}} .plyr--video .plyr__control:hover' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_sv_play_button_size',
			array(
				'label'      => __( 'Play Button Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 15,
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 15,
						'max'  => 55,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .plyr__control--overlaid' => 'padding: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Register styles for the Bar section in the Sticky Video element.
	 *
	 * This section controls the styling options for the video player bar.
	 *
	 * @since 1.0.0
	 */
	protected function register_sv_style_tab_bar_section() {
		$this->start_controls_section(
			'rael_sv_style_tab_bar_section',
			array(
				'label' => __( 'Bar', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_sv_bar_padding',
			array(
				'label'      => __( 'Bar Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 10,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .plyr--video .plyr__controls' => 'padding: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_sv_bar_margin',
			array(
				'label'      => __( 'Bar Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .plyr--video .plyr__controls' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * Render the widget on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings        = $this->get_settings_for_display();
		$icon            = $settings['rael_sv_play_icon_new'];
		$is_sticky       = ( 'yes' === $settings['rael_sv_is_sticky'] ) ? 'yes' : 'no';
		$autoplay        = ( 'yes' === $settings['rael_sv_autoplay'] ) ? 'yes' : 'no';
		$overlay_options = ( 'yes' === $settings['rael_sv_show_image_overlay_options'] ) ? 'yes' : 'no';
		$video_player    = '';

		if ( 'youtube' === $settings['rael_sv_video_source'] ) {
			$video_player = $this->rael_sv_load_other_provider_player( $settings, 'youtube' );
		} elseif ( 'vimeo' === $settings['rael_sv_video_source'] ) {
			$video_player = $this->rael_sv_load_other_provider_player( $settings, 'vimeo' );
		} else {
			$video_player = $this->rael_sv_load_self_hosted_player( $settings );
		}

		echo '<div class="rael-sticky-video-wrapper">';
		if ( 'yes' === $settings['rael_sv_show_image_overlay_options'] ) {
			$icon_val = '';
			if ( 'yes' === $settings['rael_sv_show_play_icon'] ) {
				if ( '' !== $icon['value'] ) {
					if ( is_array( $icon['value'] ) ) {
						$icon_val = '<img src="' . $icon['value']['url'] . '" width="100">';
					} else {
						$icon_val = '<i class="' . $icon['value'] . '"></i>';
					}
				} else {
					$icon_val = '<i class="eicon-play"></i>';
				}
			}

			$this->add_render_attribute(
				'rael_sv_overlay_wrapper',
				array(
					'class' => 'rael-sticky-video__overlay',
					'style' => 'background-image: url("' . $settings['rael_sv_overlay_image']['url'] . '");',
				)
			);
			?>
			<div <?php $this->print_render_attribute_string( 'rael_sv_overlay_wrapper' ); ?>>
				<div class="rael-sticky-video__overlay-icon"> <?php echo wp_kses_post( $icon_val ); ?> </div>
			</div>
			<?php
		}

		$this->add_render_attribute(
			'rael_sv_player_wrapper',
			array(
				'class'              => 'rael-sticky-video__player',
				'data-sticky'        => $is_sticky,
				'data-position'      => $settings['rael_sv_position'],
				'data-width'         => $settings['rael_sv_width'],
				'data-height'        => $settings['rael_sv_height'],
				'data-scroll-height' => ! empty( $settings['rael_sv_scroll_height_display_sticky'] ) ? $settings['rael_sv_scroll_height_display_sticky']['size'] : '',
				'data-autoplay'      => $autoplay,
				'data-overlay'       => $overlay_options,
				'data-provider'      => $settings['rael_sv_video_source'],
			)
		);
		?>
			<div <?php $this->print_render_attribute_string( 'rael_sv_player_wrapper' ); ?> >
				<?php echo wp_kses_post( $video_player ); ?>
				<span class="rael-sticky-video__player-close"><i class="eicon-close-circle"></i></span>
			</div>
		</div>
		<?php
	}
	/**
	 * Load player for providers other than self-hosted.
	 *
	 * @param array  $settings The settings for the player.
	 * @param string $provider The video provider (e.g., YouTube, Vimeo).
	 *
	 * @return string The HTML markup for the player.
	 */
	public function rael_sv_load_other_provider_player( $settings, $provider ) {
		$url_id   = $this->get_url_id( $settings );
		$autoplay = $settings['rael_sv_autoplay'];
		$mute     = $settings['rael_sv_mute'];
		$loop     = $settings['rael_sv_loop'];

		$config  = ( 'yes' === $autoplay ? '"autoplay":1' : '"autoplay":0' );
		$config .= ( 'yes' === $mute ? ', "muted":1' : ', "muted":0' );
		$config .= ( 'yes' === $loop ? ', "loop": {"active": true}' : ', "loop": {"active": false}' );

		$html = '<div
			id="rael-sticky-video-player-' . $this->get_id() . '"
			data-plyr-provider="' . $provider . '"
			data-plyr-embed-id="' . esc_attr( $url_id ) . '"
			data-plyr-config="{' . esc_attr( $config ) . '}"></div>';

		return $html;
	}

	/**
	 * Load self-hosted player.
	 *
	 * @param array $settings The settings for the player.
	 *
	 * @return string The HTML markup for the self-hosted player.
	 */
	public function rael_sv_load_self_hosted_player( $settings ) {
		$video_url = '';
		$autoplay  = $settings['rael_sv_autoplay'];
		$mute      = $settings['rael_sv_mute'];
		$loop      = $settings['rael_sv_loop'];

		if ( ! empty( $settings['rael_sv_self_hosted'] ) ) {
			$video_url = $settings['rael_sv_self_hosted']['url'];
			if ( '' !== $settings['rael_sv_video_start_time'] ) {
				$video_url .= '#t=' . esc_attr( $settings['rael_sv_video_start_time'] );

				if ( '' !== $settings['rael_sv_video_end_time'] ) {
					$video_url .= ',' . esc_attr( $settings['rael_sv_video_end_time'] );
				}
			} else {
				if ( '' !== $settings['rael_sv_video_end_time'] ) {
					$video_url .= '#t=0,' . esc_attr( $settings['rael_sv_video_end_time'] );
				}
			}
		}

		$config  = ( 'yes' === $autoplay ? '"autoplay:1"' : '"autoplay:0"' );
		$config .= ( 'yes' === $mute ? ', "muted:1"' : ', "muted:0"' );
		$config .= ( 'yes' === $loop ? ', "loop": {"active": true}' : ', "loop": {"active": false}' );

		$html = '<video class="rael-sticky-video__player--self-hosted" id="rael-sticky-video-player-' . $this->get_id() . '" playsinline controls
			data-plyr-config="{' . esc_attr( $config ) . '}">
				<source src="' . esc_attr( $video_url ) . '" type="video/mp4" />
			</video>';

		return $html;
	}
	/**
	 * Get the video ID based on the selected video source.
	 *
	 * @param array $settings The settings for the player.
	 *
	 * @return string The video ID.
	 */
	public function get_url_id( $settings ) {
		if ( 'youtube' === $settings['rael_sv_video_source'] && ! empty( $settings['rael_sv_youtube_link'] ) ) {
			$url  = $settings['rael_sv_youtube_link'];
			$link = explode( '=', wp_parse_url( $url, PHP_URL_QUERY ) );
			$id   = $link[1];
		} elseif ( 'vimeo' === $settings['rael_sv_video_source'] && ! empty( $settings['rael_sv_vimeo_link'] ) ) {
			$url  = $settings['rael_sv_vimeo_link'];
			$link = explode( '/', $url );
			$id   = $link[3];
		} else {
			$id = $settings['rael_sv_self_hosted'];
		}

		return $id;
	}
	/**
	 * Empty content template function (placeholder for future content template logic).
	 */
	protected function content_template() {}
}
