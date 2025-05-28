<?php

namespace Responsive_Addons_For_Elementor\WidgetsManager\Controls;

use Elementor\Base_Data_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * RAEL Ajax select2 control.
 *
 * A base control for creating dynamic/ajax select control. Displays options based on the
 * user input in the select input box.
 *
 * @since 1.7.0
 */
class Responsive_Addons_For_Elementor_Control_Ajax_Select2 extends Base_Data_Control {

	public function get_type() {
		return 'rael-ajax-select2';
	}

	public function enqueue() {

		wp_register_script(
			'rael-ajax-select2',
			RAEL_ASSETS_URL . 'js/rael-ajax-select2.min.js',
			array( 'jquery-elementor-select2' ),
			RAEL_VER,
			false
		);

		wp_localize_script(
			'rael-ajax-select2',
			'rael_ajax_select2_localize',
			array(
				'ajaxurl'       => esc_url( admin_url( 'admin-ajax.php' ) ),
				'search_text'   => esc_html__( 'Search', 'responsive-addons-for-elementor' ),
				'remove'        => __( 'Remove', 'responsive-addons-for-elementor' ),
				'thumbnail'     => __( 'Image', 'responsive-addons-for-elementor' ),
				'name'          => __( 'Title', 'responsive-addons-for-elementor' ),
				'price'         => __( 'Price', 'responsive-addons-for-elementor' ),
				'quantity'      => __( 'Quantity', 'responsive-addons-for-elementor' ),
				'subtotal'      => __( 'Subtotal', 'responsive-addons-for-elementor' ),
				'user_status'   => __( 'User Status', 'responsive-addons-for-elementor' ),
				'post_type'     => __( 'Post Type', 'responsive-addons-for-elementor' ),
				'browser'       => __( 'Browser', 'responsive-addons-for-elementor' ),
				'date_time'     => __( 'Date & Time', 'responsive-addons-for-elementor' ),
				'dynamic_field' => __( 'Dynamic Field', 'responsive-addons-for-elementor' ),
				'nonce'         => wp_create_nonce( 'responsive-addons-for-elementor' ),
			)
		);

		wp_enqueue_script( 'rael-ajax-select2' );
	}

	protected function get_default_settings() {
		return array(
			'multiple'    => false,
			'source_name' => 'post_type',
			'source_type' => 'post',
		);
	}

	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<# var controlUID = '<?php echo esc_html( $control_uid ); ?>'; #>
		<# var currentID = elementor.panel.currentView.currentPageView.model.attributes.settings.attributes[data.name]; #>
		<div class="elementor-control-field">
			<# if ( data.label ) { #>
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{data.label }}}</label>
			<# } #>
			<div class="elementor-control-input-wrapper elementor-control-unit-5">
				<# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
				<select id="<?php echo esc_attr( $control_uid ); ?>" {{ multiple }} class="rael-ajax-select2" data-setting="{{ data.name }}"></select>
			</div>
		</div>
		<#
		( function( $ ) {
			$( document.body ).trigger( 'rael_ajax_select2_init',{currentID:data.controlValue,data:data,controlUID:controlUID,multiple:data.multiple} );
		}( jQuery ) );
		#>
		<?php
	}
}
