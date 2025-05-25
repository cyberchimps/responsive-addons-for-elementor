<?php
/**
 * Google Map Widget
 *
 * @since      1.2.1
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Widget_Base;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Elementor 'Google Map' widget class.
 *
 * @since 1.2.1
 */
class Responsive_Addons_For_Elementor_Google_Map extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.2.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-google-map';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.2.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Google Map', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve google map widget icon.
	 *
	 * @since 1.2.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-google-maps rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the google map widget belongs to.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Get custom help URL
	 *
	 * @since 1.2.1
	 * @return string Help URL.
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/google-map';
	}

	/**
	 * Add the script dependencies required for the widget.
	 *
	 * @since 1.2.1
	 * @access public
	 *
	 * @return array List of the script dependencies.
	 */
	public function get_script_depends() {
		$key      = get_option( 'rael_google_map_settings_api_key' );
		$language = get_option( 'rael_google_map_settings_localization_language' );
		$api_url  = 'https://maps.googleapis.com';

		if ( '' != $language ) {
			if ( 'zh-CN' == $language || 'zh-TW' == $language ) {
				$api_url = 'http://maps.googleapis.cn';
			}

			$api_url .= '/maps/api/js?key=' . $key . '&language=' . $language;
		} else {
			$api_url .= '/maps/api/js?key=' . $key;
		}

		wp_register_script( 'rael_google_map_api', $api_url, array( 'jquery' ), RAEL_VER, true );
		wp_register_script( 'rael_google_map_marker_cluster', 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js', array( 'jquery' ), RAEL_VER, true );

		return array( 'rael_google_map_api', 'rael_google_map_marker_cluster' );
	}

	/**
	 * Register all the control settings for the google map.
	 *
	 * @since 1.2.1
	 * @access protected
	 */
	protected function register_controls() { //phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		$this->register_addresses_controls();
		$this->register_layout_controls();
		$this->register_controls_controls();
		$this->register_info_window_controls();
	}

	/**
	 * Add Addresses controls section under the Conten TAB.
	 *
	 * @since 1.8.0
	 * @access public
	 */
	public function register_addresses_controls() {
		$this->start_controls_section(
			'rael_gm_address_section',
			array(
				'label' => __( 'Addresses', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		if ( ! get_option( 'rael_google_map_settings_api_key' ) || '' == get_option( 'rael_google_map_settings_api_key' ) ) {
			$text = sprintf(
				// translators: %1$s represents the Google Map API Key link, %2$s represents the admin menu link.
				__( 'In order to use Google Map, you need to add %1$s in the %2$s.', 'responsive-addons-for-elementor' ),
				'<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">' . __( 'Google Map API Key', 'responsive-addons-for-elementor' ) . '</a>',
				'<a target="_blank" href="' . admin_url( 'admin.php?page=rael-settings' ) . '">' . __( 'admin menu', 'responsive-addons-for-elementor' ) . '</a>'
			);
			$this->add_control(
				'rael_api_key_not_found',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: 1: Google Map API Key link, 2: Admin menu link */
					'raw'             => $text,
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				)
			);
		}

		$repeater = new Repeater();

		$repeater->add_control(
			'rael_latitude',
			array(
				'label'       => __( 'Latitude', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => sprintf( '<a href="https://www.gps-coordinates.net/" target="_blank">%1$s</a> %2$s', __( 'Click here', 'responsive-addons-for-elementor' ), __( 'to find Latitude of your location', 'responsive-addons-for-elementor' ) ),
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'rael_longitude',
			array(
				'label'       => __( 'Longitude', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'description' => sprintf( '<a href="https://www.gps-coordinates.net/" target="_blank">%1$s</a> %2$s', __( 'Click here', 'responsive-addons-for-elementor' ), __( 'to find Longitude of your location', 'responsive-addons-for-elementor' ) ),
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'rael_address_title',
			array(
				'label'       => __( 'Address Title', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'rael_marker_info_window',
			array(
				'label'       => __( 'Display Info Window', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'default'     => 'none',
				'options'     => array(
					'none'  => __( 'None', 'responsive-addons-for-elementor' ),
					'click' => __( 'On mouse click', 'responsive-addons-for-elementor' ),
					'load'  => __( 'On page load', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$repeater->add_control(
			'rael_address_description',
			array(
				'label'       => __( 'Address Information', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'condition'   => array(
					'rael_marker_info_window!' => 'none',
				),
			)
		);

		$repeater->add_control(
			'rael_marker_icon_type',
			array(
				'label'   => __( 'Marker Icon', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => __( 'Default', 'responsive-addons-for-elementor' ),
					'custom'  => __( 'Custom', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$repeater->add_control(
			'rael_custom_marker_icon',
			array(
				'label'       => __( 'Select Marker', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::MEDIA,
				'label_block' => true,
				'condition'   => array(
					'rael_marker_icon_type' => 'custom',
				),
			)
		);

		$repeater->add_control(
			'rael_custom_marker_size',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => __( 'Marker Size', 'responsive-addons-for-elementor' ),
				'size_units'  => array( 'px' ),
				'description' => __( 'Note: If you want to retain the image original size, then set the Marker Size as blank.', 'responsive-addons-for-elementor' ),
				'default'     => array(
					'size' => 30,
					'unit' => 'px',
				),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'condition'   => array(
					'rael_marker_icon_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'rael_addresses',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'rael_latitude'            => '22.564796327858716',
						'rael_longitude'           => '88.34326449731287',
						'rael_address_title'       => __( 'Eden Gardens Cricket Stadium', 'responsive-addons-for-elementor' ),
						'rael_address_description' => '',
					),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '<i class="fa fa-map-marker"></i> {{{ rael_address_title }}}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add Layout controls section under the Content TAB.
	 *
	 * @since 1.2.1
	 * @access public
	 */
	public function register_layout_controls() {
		$this->start_controls_section(
			'rael_gm_layout_section',
			array(
				'label' => __( 'Layout', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_map_type',
			array(
				'label'   => __( 'Map type', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'roadmap',
				'options' => array(
					'roadmap'   => __( 'Road Map', 'responsive-addons-for-elementor' ),
					'satellite' => __( 'Satellite', 'responsive-addons-for-elementor' ),
					'hybrid'    => __( 'Hybrid', 'responsive-addons-for-elementor' ),
					'terrain'   => __( 'Terrain', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_map_skin',
			array(
				'label'     => __( 'Map Skin', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'standard',
				'options'   => array(
					'standard'     => __( 'Standard', 'responsive-addons-for-elementor' ),
					'silver'       => __( 'Silver', 'responsive-addons-for-elementor' ),
					'retro'        => __( 'Retro', 'responsive-addons-for-elementor' ),
					'dark'         => __( 'Dark', 'responsive-addons-for-elementor' ),
					'night'        => __( 'Night', 'responsive-addons-for-elementor' ),
					'aubergine'    => __( 'Aubergine', 'responsive-addons-for-elementor' ),
					'aqua'         => __( 'Aqua', 'responsive-addons-for-elementor' ),
					'classic_blue' => __( 'Classic Blue', 'responsive-addons-for-elementor' ),
					'earth'        => __( 'Earth', 'responsive-addons-for-elementor' ),
					'magnesium'    => __( 'Magnesium', 'responsive-addons-for-elementor' ),
					'custom'       => __( 'Custom', 'responsive-addons-for-elementor' ),
				),
				'condition' => array(
					'rael_map_type!' => 'satellite',
				),
			)
		);

		$this->add_control(
			'rael_map_custom_skin',
			array(
				'label'       => __( 'Custom Style', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'description' => sprintf( '<a href="https://mapstyle.withgoogle.com/" target="_blank">%1$s</a> %2$s', __( 'Click here', 'responsive-addons-for-elementor' ), __( 'to get JSON style code to style your map', 'responsive-addons-for-elementor' ) ),
				'condition'   => array(
					'rael_map_skin'  => 'custom',
					'rael_map_type!' => 'satellite',
				),
			)
		);

		$this->add_control(
			'rael_marker_animation',
			array(
				'label'   => __( 'Marker Animation', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'   => __( 'None', 'responsive-addons-for-elementor' ),
					'drop'   => __( 'On Load', 'responsive-addons-for-elementor' ),
					'bounce' => __( 'Continuous', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_map_zoom',
			array(
				'label'      => __( 'Map Zoom', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size'  => 12,
					'units' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 22,
					),
				),
			)
		);

		$this->add_control(
			'rael_map_height',
			array(
				'label'      => __( 'Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 500,
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 80,
						'max' => 1200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-google-map' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add Map controls section under the Content TAB.
	 *
	 * @since 1.2.1
	 * @access public
	 */
	public function register_controls_controls() {
		$this->start_controls_section(
			'rael_gm_controls_section',
			array(
				'label' => __( 'Controls', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_street_view_control',
			array(
				'label'     => __( 'Street View', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'On', 'responsive-addons-for-elementor' ),
				'label_off' => __( 'Off', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_map_type_control',
			array(
				'label'     => __( 'Map Type Control', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'On', 'responsive-addons-for-elementor' ),
				'label_off' => __( 'Off', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_zoom_control',
			array(
				'label'     => __( 'Zoom Control', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'On', 'responsive-addons-for-elementor' ),
				'label_off' => __( 'Off', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_fullscreen_control',
			array(
				'label'     => __( 'Fullscreen Control', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'On', 'responsive-addons-for-elementor' ),
				'label_off' => __( 'Off', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_zoom_on_scroll_control',
			array(
				'label'     => __( 'Zoom on Scroll', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'On', 'responsive-addons-for-elementor' ),
				'label_off' => __( 'Off', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_map_alignment',
			array(
				'label'       => __( 'Map Alignment', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'center',
				'options'     => array(
					'center'   => __( 'Center', 'responsive-addons-for-elementor' ),
					'moderate' => __( 'Moderate', 'responsive-addons-for-elementor' ),
				),
				'description' => __( 'Generally, the map is center aligned. If you have multiple locations & wish to make your first location as a center point, then switch to moderate mode.', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_cluster_markers',
			array(
				'label'     => __( 'Cluster the Markers', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'  => __( 'On', 'responsive-addons-for-elementor' ),
				'label_off' => __( 'Off', 'responsive-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Add Info Window controls section under the Content TAB.
	 *
	 * @since 1.2.1
	 * @access public
	 */
	public function register_info_window_controls() {
		$this->start_controls_section(
			'rael_gm_info_window_section',
			array(
				'label' => __( 'Info Window', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'rael_info_window_max_width',
			array(
				'label'      => __( 'Max Width for Info Window', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 250,
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min'  => 50,
						'max'  => 1000,
						'step' => 1,
					),
				),
			)
		);

		$this->add_responsive_control(
			'rael_info_window_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-google-map__info-window-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_title_info_spacing',
			array(
				'label'      => __( 'Spacing Between Title and Info.', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 5,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-google-map__info-window-description' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_info_window_title_heading',
			array(
				'label' => __( 'Address Title', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_info_window_title_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-google-map__info-window-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_info_window_title',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .rael-google-map__info-window-title',
			)
		);

		$this->add_control(
			'rael_info_window_description_heading',
			array(
				'label' => __( 'Address Information', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_info_window_description_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-google-map__info-window-description' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-google-map__info-window-title' => 'font-weight: bold;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_info_window_description',
				'selector' => '{{WRAPPER}} .rael-google-map__info-window-description',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Retrieve address locations in a JSON format.
	 *
	 * @since 1.2.1
	 * @access public
	 *
	 * @return array
	 */
	public function get_address_locations() {
		$settings          = $this->get_settings_for_display();
		$address_locations = array();

		foreach ( $settings['rael_addresses'] as $i => $address ) {
			$latitude            = apply_filters( 'rael_gm_latitude', $address['rael_latitude'] );
			$longitude           = apply_filters( 'rael_gm_longitude', $address['rael_longitude'] );
			$show_marker_info    = ( 'none' != $address['rael_marker_info_window'] );
			$address_title       = apply_filters( 'rael_gm_address_title', $address['rael_address_title'] );
			$address_description = apply_filters( 'rael_gm_address_description', $address['rael_address_description'] );

			$locations_object = array(
				$latitude,
				$longitude,
				$show_marker_info,
				$address_title,
				$address_description,
			);

			if ( 'custom' == $address['rael_marker_icon_type'] && is_array( $address['rael_custom_marker_icon'] ) && '' != $address['rael_custom_marker_icon']['url'] ) {
				$locations_object[] = 'custom';
				$locations_object[] = $address['rael_custom_marker_icon']['url'];
				$locations_object[] = $address['rael_custom_marker_size']['size'];
			} else {
				$locations_object[] = '';
				$locations_object[] = '';
				$locations_object[] = '';
			}

			$locations_object[] = 'load' == $address['rael_marker_info_window'] ? 'iw_open' : '';

			$address_locations[] = $locations_object;
		}

		return $address_locations;
	}

	/**
	 * Retrieve the map options.
	 *
	 * @since 1.2.1
	 * @access public
	 *
	 * @return array
	 */
	public function get_map_options() {
		$settings = $this->get_settings_for_display();

		$options = array(
			'zoom'              => ( ! empty( $settings['rael_map_zoom']['size'] ) ) ? $settings['rael_map_zoom']['size'] : 4,
			'mapTypeId'         => ( ! empty( $settings['rael_map_type'] ) ) ? $settings['rael_map_type'] : 'roadmap',
			'mapTypeControl'    => ( 'yes' == $settings['rael_map_type_control'] ) ? true : false,
			'streetViewControl' => ( 'yes' == $settings['rael_street_view_control'] ) ? true : false,
			'zoomControl'       => ( 'yes' == $settings['rael_zoom_control'] ) ? true : false,
			'fullscreenControl' => ( 'yes' == $settings['rael_fullscreen_control'] ) ? true : false,
			'gestureHandling'   => ( 'yes' == $settings['rael_zoom_on_scroll_control'] ) ? true : false,
		);

		return $options;
	}

	/**
	 * Render in the frontend
	 *
	 * @since 1.2.1
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$map_options       = $this->get_map_options();
		$address_locations = $this->get_address_locations();
		$cluster_options   = array();
		$cluster_attrs     = apply_filters( 'rael_gm_cluster_options', $cluster_options );

		$this->add_render_attribute(
			'rael_google_map',
			array(
				'id'                   => 'rael-google-map-' . esc_attr( $this->get_id() ),
				'class'                => 'rael-google-map',
				'data-map-options'     => wp_json_encode( $map_options ),
				'data-iw-max-width'    => $settings['rael_info_window_max_width']['size'],
				'data-locations'       => wp_json_encode( $address_locations ),
				'data-animation'       => $settings['rael_marker_animation'],
				'data-auto-center'     => $settings['rael_map_alignment'],
				'data-cluster'         => $settings['rael_cluster_markers'],
				'data-cluster-options' => wp_json_encode( $cluster_attrs ),
			)
		);

		if ( 'standard' != $settings['rael_map_skin'] ) {
			if ( 'custom' != $settings['rael_map_skin'] ) {
				$this->add_render_attribute( 'rael_google_map', 'data-predefined-style', $settings['rael_map_skin'] );
			} else {
				$this->add_render_attribute( 'rael_google_map', 'data-custom-style', $settings['rael_map_skin'] );
			}
		}

		ob_start();
		?>

		<div class="rael-google-map-wrapper">
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'rael_google_map' ) ); ?>></div>
		</div>

		<?php
		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render template for live preview in the backend
	 *
	 * @since 1.2.1
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
		function get_address_locations() {
			var address_locations = [];
			_.each(settings.rael_addresses, function(address){
				var latitude = address.rael_latitude,
					longitude = address.rael_longitude,
					show_marker_info = ('none' != address.rael_marker_info_window),
					address_title = address.rael_address_title,
					address_description = address.rael_address_description;
				var location = [
					latitude,
					longitude,
					show_marker_info,
					address_title,
					address_description,
				];
				if ('custom' == address.rael_marker_icon_type && 'undefined' != typeof address.rael_custom_marker_icon && '' != address.rael_custom_marker_icon.url) {
					location.push('custom');
					location.push(address.rael_custom_marker_icon.url);
					location.push(address.rael_custom_marker_size);
				} else {
					location.push('');
					location.push('');
					location.push('');
				}
				location.push('load' == address.rael_marker_info_window);
				address_locations.push(location);
			});
			return address_locations;
		}
		function get_map_options() {
			const options = {
				'zoom' : ('' != settings.rael_map_zoom.size) ? settings.rael_map_zoom.size : '',
				'mapTypeId' : ('' != settings.rael_map_type) ? settings.rael_map_type : 'roadmap',
				'mapTypeControl' : ('yes' == settings.rael_map_type_control) ? true : false,
				'streetViewControl' : ('yes' == settings.rael_street_view_control) ? true : false,
				'zoomControl' : ('yes' == settings.rael_zoom_control) ? true : false,
				'fullscreenControl' : ('yes' == settings.rael_fullscreen_control) ? true : false,
				'gestureHandling' : ('yes' == settings.rael_zoom_on_scroll_control) ? true : false
			};
			return options;
		}
		const map_options = get_map_options(),
		address_locations = get_address_locations(),
		cluster_options = [];
		view.addRenderAttribute(
			'rael_google_map',
			{
				'class' : 'rael-google-map',
				'data-map-options' : JSON.stringify(map_options),
				'data-iw-max-width' : settings.rael_info_window_max_width,
				'data-locations' : JSON.stringify(address_locations),
				'data-animation' : settings.rael_marker_animation,
				'data-auto-center' : settings.rael_map_alignment,
				'data-cluster' : settings.rael_cluster_markers,
				'data-cluster-options' : JSON.stringify(cluster_options),
			}
		);
		if ('standard' != settings.rael_map_skin) {
			if ('custom' != settings.rael_map_skin) {
				view.addRenderAttribute('rael_google_map', 'data-predefined-style', settings.rael_map_skin);
			} else {
				view.addRenderAttribute('rael_google_map', 'data-custom-style', settings.rael_map_skin);
			}
		}
		#>
		<div class="rael-google-map-wrapper">
			<div {{{ view.getRenderAttributeString('rael_google_map') }}}></div>
		</div>
		<# elementorFrontend.hooks.doAction( 'frontend/element_ready/rael-google-map.default' ); #>
		<?php
	}
}
