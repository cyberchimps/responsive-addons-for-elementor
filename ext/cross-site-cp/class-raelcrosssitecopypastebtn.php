<?php
/**
 * File comment for RaelCrossSiteCopyPasteBtn.php
 *
 * This file contains the definition of the RaelCrossSiteCopyPasteBtn class.
 *
 * @package Responsive_Addons_For_Elementor
 */

use Elementor\Plugin;
use Elementor\Controls_Stack;
use Elementor\Utils;

if ( ! class_exists( 'RaelCrossSiteCopyPasteBtn' ) ) {
	/**
	 * RaelCrossSiteCopyPasteBtn class.
	 *
	 * This class handles AJAX requests and enqueues scripts for RAEL Cross Site Copy Paste.
	 */
	class RaelCrossSiteCopyPasteBtn {
		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'wp_ajax_rael_elementor_import_rael_cs_copy_assets_files', array( $this, 'ajax_import_data' ) );
			add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'rael_cs_copy_enqueue' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
		}

		/**
		 * RAEL Cross Site Copy Enqueue Scripts.
		 */
		public function rael_cs_copy_enqueue() {
			wp_enqueue_script( 'rael-cs-copy-storage-js', RAEL_URL . 'assets/lib/cross-site-cp/js/xdLocalStorage.js', array(), RAEL_VER, true );
			wp_enqueue_script( 'rael-cs-copy-scripts-js', RAEL_URL . 'assets/lib/cross-site-cp/js/rael-cs-copy-paste.js', array( 'jquery', 'elementor-editor', 'rael-cs-copy-storage-js', 'elementor-editor' ), RAEL_VER, true );
			wp_localize_script(
				'rael-cs-copy-scripts-js',
				'rael_cs_copy',
				array(
					'front_key'   => 'front_copy_data',
					'ajax_url'    => admin_url( 'admin-ajax.php' ),
					'nonce'       => wp_create_nonce( 'front_copy_data' ),
					'plugin_path' => RAEL_ASSETS_URL,
				)
			);
		}

		/**
		 * Ajax For Importing Data
		 */
		public function ajax_import_data() {
			$nonce = isset( $_REQUEST['security'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['security'] ) ) : '';
			$data  = isset( $_REQUEST['data'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['data'] ) ) : '';

			if ( ! wp_verify_nonce( $nonce, 'front_copy_data' ) || empty( $data ) ) {
				wp_send_json_error( __( 'Sorry, invalid nonce or empty content!', 'responsive-addons-for-elementor' ) );
			}

			$data = array( json_decode( $data, true ) );

			$data = $this->ready_for_import( $data );
			$data = $this->import_content( $data );

			wp_send_json_success( $data );
		}

		/**
		 * Process Import Content
		 *
		 * @param Controls_Stack $element The element.
		 * @return $element_data Element Data.
		 */
		protected function process_import_content( Controls_Stack $element ) {
			$element_data = $element->get_data();
			$method       = 'on_import';

			if ( method_exists( $element, $method ) ) {
				$element_data = $element->{$method}( $element_data );
			}

			foreach ( $element->get_controls() as $control ) {
				$control_class = Plugin::instance()->controls_manager->get_control( $control['type'] );

				if ( ! $control_class ) {
					return $element_data;
				}

				if ( method_exists( $control_class, $method ) ) {
					$element_data['settings'][ $control['name'] ] = $control_class->{$method}( $element->get_settings( $control['name'] ), $control );
				}
			}

			return $element_data;
		}

		/**
		 * Ready For Import
		 *
		 * @param mixed $content The content.
		 * @return $element Element.
		 */
		protected function ready_for_import( $content ) {
			return Plugin::instance()->db->iterate_data(
				$content,
				function ( $element ) {
					$element['id'] = Utils::generate_random_string();
					return $element;
				}
			);
		}

		/**
		 * Import Content
		 *
		 * @param mixed $content The content.
		 */
		protected function import_content( $content ) {
			return Plugin::instance()->db->iterate_data(
				$content,
				function ( $element_data ) {
					$element = Plugin::instance()->elements_manager->create_element_instance( $element_data );

					if ( ! $element ) {
						return null;
					}

					return $this->process_import_content( $element );
				}
			);
		}

		/**
		 * Localize Script and Enqueue.
		 */
		public function enqueue() {
			$params = array(
				'post_id'    => get_the_ID(),
				'ajax_url'   => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => wp_create_nonce( 'rael-cs-copy-paste-front-nonce' ),
			);
			wp_localize_script( 'jquery', 'rael_front_copy_ajax', $params );
		}
	}
}

new RaelCrossSiteCopyPasteBtn();
