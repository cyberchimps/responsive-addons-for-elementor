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

?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php
$langs = array(
	'ar'    => __( 'ARABIC', 'responsive-addons-for-elementor' ),
	'eu'    => __( 'BASQUE', 'responsive-addons-for-elementor' ),
	'bg'    => __( 'BULGARIAN', 'responsive-addons-for-elementor' ),
	'bn'    => __( 'BENGALI', 'responsive-addons-for-elementor' ),
	'ca'    => __( 'CATALAN', 'responsive-addons-for-elementor' ),
	'cs'    => __( 'CZECH', 'responsive-addons-for-elementor' ),
	'da'    => __( 'DANISH', 'responsive-addons-for-elementor' ),
	'de'    => __( 'GERMAN', 'responsive-addons-for-elementor' ),
	'el'    => __( 'GREEK', 'responsive-addons-for-elementor' ),
	'en'    => __( 'ENGLISH', 'responsive-addons-for-elementor' ),
	'en-AU' => __( 'ENGLISH (AUSTRALIAN)', 'responsive-addons-for-elementor' ),
	'en-GB' => __( 'ENGLISH (GREAT BRITAIN)', 'responsive-addons-for-elementor' ),
	'es'    => __( 'SPANISH', 'responsive-addons-for-elementor' ),
	'fa'    => __( 'FARSI', 'responsive-addons-for-elementor' ),
	'fi'    => __( 'FINNISH', 'responsive-addons-for-elementor' ),
	'fil'   => __( 'FILIPINO', 'responsive-addons-for-elementor' ),
	'fr'    => __( 'FRENCH', 'responsive-addons-for-elementor' ),
	'gl'    => __( 'GALACIAN', 'responsive-addons-for-elementor' ),
	'gu'    => __( 'GUJARATI', 'responsive-addons-for-elementor' ),
	'hi'    => __( 'HINDI', 'responsive-addons-for-elementor' ),
	'hr'    => __( 'CROATIAN', 'responsive-addons-for-elementor' ),
	'hu'    => __( 'HUNGARIAN', 'responsive-addons-for-elementor' ),
	'id'    => __( 'INDONESIAN', 'responsive-addons-for-elementor' ),
	'it'    => __( 'ITALIAN', 'responsive-addons-for-elementor' ),
	'iw'    => __( 'HEBREW', 'responsive-addons-for-elementor' ),
	'ja'    => __( 'JAPANESE', 'responsive-addons-for-elementor' ),
	'kn'    => __( 'KANNADA', 'responsive-addons-for-elementor' ),
	'ko'    => __( 'KOREAN', 'responsive-addons-for-elementor' ),
	'lt'    => __( 'LITHUANIAN', 'responsive-addons-for-elementor' ),
	'lv'    => __( 'LATVIAN', 'responsive-addons-for-elementor' ),
	'ml'    => __( 'MALAYALAM', 'responsive-addons-for-elementor' ),
	'mr'    => __( 'MARATHI', 'responsive-addons-for-elementor' ),
	'nl'    => __( 'DUTCH', 'responsive-addons-for-elementor' ),
	'no'    => __( 'NORWEGIAN', 'responsive-addons-for-elementor' ),
	'pl'    => __( 'POLISH', 'responsive-addons-for-elementor' ),
	'pt'    => __( 'PORTUGUESE', 'responsive-addons-for-elementor' ),
	'pt-BR' => __( 'PORTUGUESE (BRAZIL)', 'responsive-addons-for-elementor' ),
	'pt-PR' => __( 'PORTUGUESE (PORTUGAL)', 'responsive-addons-for-elementor' ),
	'ro'    => __( 'ROMANIAN', 'responsive-addons-for-elementor' ),
	'ru'    => __( 'RUSSIAN', 'responsive-addons-for-elementor' ),
	'sk'    => __( 'SLOVAK', 'responsive-addons-for-elementor' ),
	'sl'    => __( 'SLOVENIAN', 'responsive-addons-for-elementor' ),
	'sr'    => __( 'SERBIAN', 'responsive-addons-for-elementor' ),
	'sv'    => __( 'SWEDISH', 'responsive-addons-for-elementor' ),
	'tl'    => __( 'TAGALOG', 'responsive-addons-for-elementor' ),
	'ta'    => __( 'TAMIL', 'responsive-addons-for-elementor' ),
	'te'    => __( 'TELUGU', 'responsive-addons-for-elementor' ),
	'th'    => __( 'THAI', 'responsive-addons-for-elementor' ),
	'tr'    => __( 'TURKISH', 'responsive-addons-for-elementor' ),
	'uk'    => __( 'UKRANIAN', 'responsive-addons-for-elementor' ),
	'vi'    => __( 'VIETNAMESE', 'responsive-addons-for-elementor' ),
	'zh-CN' => __( 'CHINESE (SIMPLIFIED)', 'responsive-addons-for-elementor' ),
	'zh-TW' => __( 'CHINESE (TRADITIONAL)', 'responsive-addons-for-elementor' ),
);

?>
<div class="responsive-elementor-main-settings-div">
	<div class="responsive-elementor-settings-tab-selection col-md-3">
		<div id="responsive-elementor-plugin-tab" class="mb-4 responsive-addons-for-elementor-tab-button responsive-addons-for-elementor-flex-display responsive-elementor-settings-active-tab">
			<span class="dashicons dashicons-admin-settings"></span>
			<p class="responsive-addons-for-elementor-margin-zero"><?php esc_html_e( 'Plugin Settings', 'responsive-addons-for-elementor' ); ?></p>
		</div>
	</div>
	<div class="responsive-addons-for-elementor-settings col-md-9 responsive-addons-for-elementor-setting-border-left">
		<div id="responsive-elementor-plugin-settings" class="responsive-elementor-plugin-settings-section responsive-elementor-sections">
			<div class="responsive-addons-for-elementor-inner-settings responsive-addons-for-elementor-mailchimp-section">
				<div class="resposive-elementor-addons-title-section">
					<p class="responsive-addons-for-elementor-setting-title"><?php esc_html_e( 'RAE Mailchimp Settings', 'responsive-addons-for-elementor' ); ?></p>
					<p class="responsive-addons-for-elementor-setting-desc"><?php esc_html_e( 'These setting apply to RAE Mailchimp Styler widget', 'responsive-addons-for-elementor' ); ?>.<a style="color: #2271b1; text-decoration: none;" href="https://cyberchimps.com/docs/widgets/mailchimp-styler/"><?php esc_html_e( ' Learn More.', 'responsive-addons-for-elementor' ); ?></a></p>
				</div>
				<div class="resposive-elementor-addons-setting-section responsive-addons-for-elementor-mailchimp-setting-section">
					<div class="responsive-addons-for-elementor-setting">
						<p class="responsive-addons-for-elementor-setting-label"><?php esc_html_e( 'RAE Mailchimp API Setting', 'responsive-addons-for-elementor' ); ?></p>
						<input id="rael_mailchimp_settings_api_key" type="text" class="responsive-addons-for-elementor-setting-input" value="<?php echo esc_attr( get_option( 'rael_mailchimp_settings_api_key', '' ) ); ?>">
					</div>
					<button id="rael_mailchimp_api_key_button" class="button elementor-button-spinner responsive-addons-for-elementor-validate-key" data-nonce="<?php echo esc_html( wp_create_nonce( 'rael_mailchimp_settings_api_key' ) ); ?>"><?php esc_html_e( 'Validate API Key', 'responsive-addons-for-elementor' ); ?></button>
				</div>
			</div>
			<div class="responsive-addons-for-elementor-inner-settings responsive-addons-for-elementor-gmap-section">
			<div class="resposive-elementor-addons-title-section">
				<p class="responsive-addons-for-elementor-setting-title"><?php esc_html_e( 'RAE Google Map settings', 'responsive-addons-for-elementor' ); ?></p>
				<p class="responsive-addons-for-elementor-setting-desc"><?php esc_html_e( 'These settings apply to RAE Google Map widget.', 'responsive-addons-for-elementor' ); ?><a style="color: #2271b1; text-decoration: none;" href="https://cyberchimps.com/docs/widgets/google-map/"><?php esc_html_e( ' Learn More.', 'responsive-addons-for-elementor' ); ?></a></p>
			</div>
			<div class="resposive-elementor-addons-setting-section responsive-addons-for-elementor-gmap-setting-section">	
				<div class="responsive-addons-for-elementor-setting">
					<p class="responsive-addons-for-elementor-setting-label"><?php esc_html_e( 'RAE Google Map API Key', 'responsive-addons-for-elementor' ); ?></p>
					<input id="rael_google_map_settings_api_key" type="text" class="responsive-addons-for-elementor-setting-input" value="<?php echo esc_attr( get_option( 'rael_google_map_settings_api_key', '' ) ); ?>">
				</div>
				<div class="responsive-addons-for-elementor-setting">
					<p class="responsive-addons-for-elementor-setting-label mt-4"><?php esc_html_e( 'RAE Google Map Localization Language', 'responsive-addons-for-elementor' ); ?></p>
					<select id="rael_google_map_settings_localization_language" class="responsive-addons-for-elementor-setting-input responsive-addons-for-elementor-setting-select" name="rael_google_map_settings_localization_language" id="rael_google_map_settings_localization_language">
						<option value="" selected="">Default</option>
						<?php
						foreach ( $langs as $index => $lang ) {
							if ( '' !== get_option( 'rael_google_map_settings_localization_language' ) && get_option( 'rael_google_map_settings_localization_language' ) === $index ) {
								?>
								<option selected value="<?php echo esc_attr( $index ); ?>"><?php echo esc_html( $lang ); ?></option>
								<?php
							}
							?>
							<option value="<?php echo esc_attr( $index ); ?>"><?php echo esc_html( $lang ); ?></option>
							<?php
						}
						?>
					</select>
				</div>
			</div>
		</div>
		<div class="responsive-addons-for-elementor-inner-settings responsive-addons-for-elementor-gmap-section">
			<div class="resposive-elementor-addons-title-section">
				<p class="responsive-addons-for-elementor-setting-title"><?php esc_html_e( 'RAE Login / Register Form Settings', 'responsive-addons-for-elementor' ); ?></p>
				<p class="responsive-addons-for-elementor-setting-desc"><?php esc_html_e( 'These settings apply to RAE Login / Register Form widget.', 'responsive-addons-for-elementor' ); ?><a style="color: #2271b1; text-decoration: none;" href="https://cyberchimps.com/docs/widgets/login-register/"><?php esc_html_e( ' Learn More.', 'responsive-addons-for-elementor' ); ?></a></p>
			</div>
			<div class="resposive-elementor-addons-setting-section responsive-addons-for-elementor-gmap-setting-section">
				<div class="responsive-addons-for-elementor-setting">
					<p class="responsive-addons-for-elementor-setting-label"><?php esc_html_e( 'RAE Google reCAPTCHA v2 Site key', 'responsive-addons-for-elementor' ); ?></p>
					<input id="rael_login_reg_setting_site_key" type="text" class="responsive-addons-for-elementor-setting-input" value="<?php echo esc_attr( get_option( 'rael_login_reg_setting_site_key', '' ) ); ?>">
				</div>
				<div class="responsive-addons-for-elementor-setting">
					<p class="responsive-addons-for-elementor-setting-label mt-4"><?php esc_html_e( 'RAE Google reCAPTCHA v2 Secret key', 'responsive-addons-for-elementor' ); ?></p>
					<input id="rael_login_reg_setting_secret_key" type="text" class="responsive-addons-for-elementor-setting-input" value="<?php echo esc_attr( get_option( 'rael_login_reg_setting_secret_key', '' ) ); ?>">
				</div>
			</div>
		</div>
			<button id="rael_settings_save_changes" class="responsive-addons-for-elementor-save-settings-btn mt-4" data-nonce="<?php echo esc_html( wp_create_nonce( 'rael_save_api_key_settings' ) ); ?>"><?php esc_html_e( 'Save Changes', 'responsive-addons-for-elementor' ); ?></button>
		</div>
	</div>
</div>
