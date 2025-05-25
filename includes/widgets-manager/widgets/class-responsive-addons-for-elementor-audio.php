<?php
/**
 * Audio Widget
 *
 * @since   1.0.0
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Audio Widget
 *
 * @since   1.0.0
 * @package Responsive_Addons_For_Elementor
 * @author  CyberChimps <support@cyberchimps.com>
 */
class Responsive_Addons_For_Elementor_Audio extends Widget_Base {


	/**
	 * Get widget name.
	 *
	 * Retrieve 'Audio' widget name.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael_audio';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve 'Audio' widget title.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Audio Player', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve 'Audio' widget icon.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-play rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the timeline post widget belongs to.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Register 'Audio' widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'audio_section',
			array(
				'label' => __( 'Audio', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'src',
			array(
				'label'        => __( 'Audio file(MP3 or ogg)', 'responsive-addons-for-elementor' ),
				'type'         => 'rael-media',
				'media_filter' => 'audio',
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => __( 'AutoPlay', 'responsive-addons-for-elementor' ),
				'description'  => __( 'Play the audio file automatically.', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Off', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'loop',
			array(
				'label'        => __( 'Repeat the audio', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'Off', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'skin_section',
			array(
				'label' => __( 'Skin', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'skin',
			array(
				'label'       => __( 'Audio Skin', 'responsive-addons-for-elementor' ),
				'description' => __( 'The skin of audio element.', 'responsive-addons-for-elementor' ),
				'type'        => 'rael-visual-select',
				'options'     => array(
					'default' => array(
						'label' => __( 'Theme Default', 'responsive-addons-for-elementor' ),
						'image' => RAEL_ASSETS_URL . 'images/visual-select/default2.svg',
					),
					'dark'    => array(
						'label' => __( 'Dark', 'responsive-addons-for-elementor' ),
						'image' => RAEL_ASSETS_URL . 'images/visual-select/audio-player-dark.svg',
					),
					'light'   => array(
						'label' => __( 'Light', 'responsive-addons-for-elementor' ),
						'image' => RAEL_ASSETS_URL . 'images/visual-select/audio-player-light.svg',
					),
				),
				'default'     => 'dark',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render image box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$args = array(
			'src'      => $settings['src']['id'],
			'loop'     => $settings['loop'],
			'autoplay' => $settings['autoplay'],
			'skin'     => $settings['skin'],
		);

		if ( is_numeric( $settings['src']['id'] ) ) {
			$args['src'] = wp_get_attachment_url( $args['src'] );
		}
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $this->rael_render_audio( $args );
	}

	/**
	 * Render image box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @param array  $atts array of attributes.
	 * @param string $shortcode_content shortcode content.
	 */
	protected function rael_render_audio( $atts, $shortcode_content = '' ) {
		// Defining default attributes.
		$default_atts = array(
			'title'         => '',    // section title.
			'src'           => '',
			'loop'          => '1',
			'autoplay'      => '0',
			'preload'       => '',
			'skin'          => '', // dark or light.

			'extra_classes' => '', // custom css class names for this element.
			'custom_el_id'  => '', // custom id attribute for this element.
			'base_class'    => 'rael-widget-audio',  // base class name for container.
		);

		// Parse shortcode attributes.
		$parsed_atts = shortcode_atts( $default_atts, $atts, __FUNCTION__ );

		if ( empty( $parsed_atts['content'] ) ) {
			$parsed_atts['content'] = $shortcode_content;
		}

		if ( empty( $parsed_atts['loadmore_per_page'] ) ) {
			$parsed_atts['loadmore_per_page'] = ! empty( $parsed_atts['num'] ) ? $parsed_atts['num'] : 12;
		}

		$result = array(
			'parsed_atts'   => '',
			'widget_header' => '',
			'widget_footer' => '',
		);

		$result['parsed_atts'] = $parsed_atts;
		extract( $result['parsed_atts'] ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

		ob_start();

		$result['widget_header'] .= sprintf( '<section class="widget-container %s">', $default_atts['base_class'] );

		echo wp_kses_post( $result['widget_header'] );

		$autoplay = $this->rael_is_true( $autoplay ) ? '1' : '0';
		$loop     = $this->rael_is_true( $loop ) ? '1' : '0';

		$class = 'wp-audio-shortcode rael-player-' . esc_attr( $skin );

		// convert attachment id to url.
		if ( is_numeric( $src ) ) {
			$src = wp_get_attachment_url( $src );
		}

		echo wp_audio_shortcode( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			array(
				'src'      => $src,
				'loop'     => $loop,
				'autoplay' => $autoplay,
				'preload'  => $preload,
				'class'    => $class,
			)
		);

		$result['widget_footer'] .= '</section>';

		echo wp_kses_post( $result['widget_footer'] );

		return ob_get_clean();
	}

	/**
	 * Returns status of setting
	 *
	 * @param string $status setting status value.
	 * @return boolean true/false
	 */
	protected function rael_is_true( $status ) {
		if ( is_bool( $status ) ) {
			return $status;
		}

		if ( is_string( $status ) ) {
			$status = strtolower( $status );
			if ( in_array( $status, array( 'yes', 'on', 'true', 'checked' ), true ) ) {
				return true;
			}
		}

		if ( is_numeric( $status ) ) {
			return (bool) $status;
		}

		return false;
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return esc_url( 'https://cyberchimps.com/docs/widgets/audio-player' );
	}
}
