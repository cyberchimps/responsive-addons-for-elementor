<?php
/**
 * Provide a admin area view for the rael plugin
 *
 * This file is used to markup the admin-facing aspects of the rael plugin.
 *
 * @link  https://cyberchimps.com/
 * @since 1.0.0
 *
 * @package responsive-addons-for-elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="responsive-addons-for-elementor-getting-started-page">
	<div class="responsive-addons-for-elementor-header">
		<div class="responsive-addons-for-elementor-brand">
		<img class="responsive-addons-for-elementor-logo" src="<?php echo esc_url( RAEL_URL ) . 'admin/images/rae-logo.svg'; ?>" alt="responsive-thumbnail">
			<div class="responsive-addons-for-elementor-version"><?php echo esc_html( RAEL_VER ); ?></div>
		</div>
		<p class="responsive-addons-for-elementor-brand-desc"><?php esc_html_e( 'Thank you for choosing Responsive Addons for Elementor - The most powerful page builder addons plugin.', 'responsive-addons-for-elementor' ); ?></p>
	</div>
	<div class="responsive-addons-for-elementor-tabs-section">
		<div class="responsive-addons-for-elementor-tabs">
			<div class="responsive-addons-for-elementor-tab responsive-addons-for-elementor-widgets-tab" data-tab="widgets">
				<p class="responsive-addons-for-elementor-tab-name"><?php esc_html_e( 'Widgets', 'responsive-addons-for-elementor' ); ?></p>
			</div>
			<div class="responsive-addons-for-elementor-tab responsive-addons-for-elementor-settings-tab" data-tab="settings">
				<p class="responsive-addons-for-elementor-tab-name"><?php esc_html_e( 'RAE Settings', 'responsive-addons-for-elementor' ); ?></p>
			</div>
			<div class="responsive-addons-for-elementor-tab responsive-addons-for-elementor-templates-tab" data-tab="templates">
				<p class="responsive-addons-for-elementor-tab-name"><?php esc_html_e( 'Starter Templates', 'responsive-addons-for-elementor' ); ?></p>
			</div>
		</div>
	</div>
	<div class="responsive-addons-for-elementor-tabs-content">
		<div class="responsive-addons-for-elementor-tabs-inner-content">
			<div class="responsive-addons-for-elementor-widgets-content responsive-addons-for-elementor-tab-content" id="rael_widgets">
				<?php require_once RAEL_DIR . 'admin/partials/responsive-addons-for-elementor-admin-widgets-display.php'; ?>
			</div>
			<div class="responsive-addons-for-elementor-widgets-content responsive-addons-for-elementor-tab-content" id="rael_settings">
				<?php require_once RAEL_DIR . 'admin/partials/responsive-addons-for-elementor-admin-settings-display.php'; ?>
			</div>
			<div class="responsive-addons-for-elementor-templates-content responsive-addons-for-elementor-tab-content" id="rael_templates">
				<?php require_once RAEL_DIR . 'admin/partials/responsive-addons-for-elementor-admin-starter-templates-display.php'; ?>
			</div>
		</div>
	</div>
	<div class="responsive-addons-for-elementor-footer">
		<div class="responsive-addons-for-elementor-footer-details">
			<div class="responsive-addons-for-elementor-footer-text">
				<p class="responsive-addons-for-elementor-footer-text-line"><?php esc_html_e( 'If you like', 'responsive-addons-for-elementor' ); ?>
					<span class="responsive-addons-for-elementor-footer-brand-name"><?php esc_html_e( 'Responsive Addons for Elementor', 'responsive-addons-for-elementor' ); ?></span>, <br class="responsive-addons-for-elementor-mobile-line-break"><?php esc_html_e( 'please leave us a', 'responsive-addons-for-elementor' ); ?> 
					<a href="https://wordpress.org/support/plugin/responsive-addons-for-elementor/reviews/#new-post" target="_blank" class="responsive-addons-for-elementor-star-rating">
						<img src="<?php echo esc_url( RAEL_URL ) . 'admin/images/ph_star-fill.svg'; ?>" alt=""><img src="<?php echo esc_url( RAEL_URL ) . 'admin/images/ph_star-fill.svg'; ?>" alt=""><img src="<?php echo esc_url( RAEL_URL ) . 'admin/images/ph_star-fill.svg'; ?>" alt=""><img src="<?php echo esc_url( RAEL_URL ) . 'admin/images/ph_star-fill.svg'; ?>" alt=""><img src="<?php echo esc_url( RAEL_URL ) . 'admin/images/ph_star-fill.svg'; ?>" alt="">
					</a> <?php esc_html_e( 'rating. Thank you!', 'responsive-addons-for-elementor' ); ?>
				</p>
			</div>
			<div class="responsive-addons-for-elementor-hr"></div>
		</div>
		<img class="responsive-addons-for-elementor-cyberchimps-logo" src="<?php echo esc_url( RAEL_URL . 'admin/images/cyberchimps-logo.png' ); ?>" alt="">
	</div>
</div>
