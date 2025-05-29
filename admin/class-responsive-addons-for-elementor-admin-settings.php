<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://cyberchimps.com/
 * @since      1.0.0
 *
 * @package responsive-addons-for-elementor
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package responsive-addons-for-elementor
 * @author     Cyberchimps.com
 */
class Responsive_Addons_For_Elementor_Admin_Settings {

	const OPTION_NAME_API_KEY = 'rael_mailchimp_settings_api_key';
	/**
	 * API Base URL
	 *
	 * @var string
	 */
	private $api_base_url = '';

	/**
	 * API Key
	 *
	 * @var string
	 */
	private $api_key = '';

	/**
	 * API Request Arguments
	 *
	 * @var array
	 */
	private $api_request_args = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_' . self::OPTION_NAME_API_KEY . '_validate', array( $this, 'ajax_validate_api_token' ) );
		add_action( 'wp_ajax_rael_save_api_key_settings', array( $this, 'save_rael_api_settings' ) );
		add_action( 'wp_ajax_responsive-elementor-api-key-activate', array( $this, 'responsive_elementor_api_key_activate' ) );
		add_action( 'wp_ajax_responsive-elementor-api-key-deactivate', array( $this, 'responsive_elementor_api_key_deactivate' ) );
	}

	/**
	 * Validate API Token via AJAX
	 *
	 * @throws \Exception Invalid API Key.
	 */
	public function ajax_validate_api_token() {
		check_ajax_referer( self::OPTION_NAME_API_KEY, '_nonce' );
		if ( ! isset( $_POST['api_key'] ) ) {
			wp_send_json_error();
		}
		try {
			$api_key = $_POST['api_key']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			if ( empty( $api_key ) ) {
				throw new \Exception( 'Invalid API key' );
			}

			// The API key is in format XXXXXXXXXXXXXXXXXXXX-us2 where us2 is the server sub domain for this account.
			$key_parts = explode( '-', $api_key );
			if ( empty( $key_parts[1] ) || 0 !== strpos( $key_parts[1], 'us' ) ) {
				throw new \Exception( 'Invalid API key' );
			}

			$this->api_key          = $api_key;
			$this->api_base_url     = 'https://' . $key_parts[1] . '.api.mailchimp.com/3.0/';
			$this->api_request_args = array(
				'headers' => array(
					'Authorization' => 'Basic ' . base64_encode( 'user:' . $this->api_key ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
				),
			);
		} catch ( \Exception $exception ) {
			wp_send_json_error();
		}
		wp_send_json_success();
	}

	/**
	 * Saves the RAEL Settings.
	 *
	 * @since    1.9.2
	 */
	public function save_rael_api_settings() {
		check_ajax_referer( 'rael_save_api_key_settings', 'nonce' );
		$mailchimp_api_key      = isset( $_POST['mailchimpAPIKey'] ) ? sanitize_text_field( wp_unslash( $_POST['mailchimpAPIKey'] ) ) : '';
		$gmap_api_key           = isset( $_POST['gmapAPIKey'] ) ? sanitize_text_field( wp_unslash( $_POST['gmapAPIKey'] ) ) : '';
		$gmap_localization_lang = isset( $_POST['gmapLocalizationLang'] ) ? sanitize_text_field( wp_unslash( $_POST['gmapLocalizationLang'] ) ) : '';
		$recaptcha_site_key     = isset( $_POST['reCaptchaSiteKey'] ) ? sanitize_text_field( wp_unslash( $_POST['reCaptchaSiteKey'] ) ) : '';
		$recaptcha_secret_key   = isset( $_POST['reCaptchaSecretKey'] ) ? sanitize_text_field( wp_unslash( $_POST['reCaptchaSecretKey'] ) ) : '';

		update_option( 'rael_mailchimp_settings_api_key', $mailchimp_api_key );
		update_option( 'rael_google_map_settings_api_key', $gmap_api_key );
		update_option( 'rael_google_map_settings_localization_language', $gmap_localization_lang );
		update_option( 'rael_login_reg_setting_site_key', $recaptcha_site_key );
		update_option( 'rael_login_reg_setting_secret_key', $recaptcha_secret_key );

		wp_send_json_success();
	}
}
