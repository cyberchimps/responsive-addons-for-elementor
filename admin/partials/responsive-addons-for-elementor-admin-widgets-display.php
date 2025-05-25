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

<?php

$widgets           = get_option( 'rael_widgets', '' );
$toggle_all_status = 'checked';

foreach ( $widgets as $widget ) {
	if ( ! $widget['status'] ) {
		$toggle_all_status = '';
		break;
	}
}
?>

<div class="container">
	<div class="row" class = "rael-widget-header-container">
		<div class="col-lg-9">
			<div class="rael-widget-list">
				<p>
					<span class="rael-widget-category rael-widget-tab pointer rael-active-widget-category" id="all"><?php esc_html_e( 'All', 'responsive-addons-for-elementor' ); ?></span>
					<span class="rael-widget-category mx-3"><?php esc_html_e( '|', 'responsive-addons-for-elementor' ); ?></span>
					<span class="rael-widget-category rael-widget-tab pointer" id="content"><?php esc_html_e( 'Content', 'responsive-addons-for-elementor' ); ?></span>
					<span class="rael-widget-category mx-3"><?php esc_html_e( '|', 'responsive-addons-for-elementor' ); ?></span>
					<span class="rael-widget-category rael-widget-tab pointer" id="form"><?php esc_html_e( 'Form', 'responsive-addons-for-elementor' ); ?></span>
					<span class="rael-widget-category mx-3"><?php esc_html_e( '|', 'responsive-addons-for-elementor' ); ?></span>					
					<span class="rael-widget-category rael-widget-tab pointer" id="marketing"><?php esc_html_e( 'Marketing', 'responsive-addons-for-elementor' ); ?></span>
					<span class="rael-widget-category mx-3"><?php esc_html_e( '|', 'responsive-addons-for-elementor' ); ?></span>
					<span class="rael-widget-category rael-widget-tab pointer" id="creativity"><?php esc_html_e( 'Creativity', 'responsive-addons-for-elementor' ); ?></span>
					<span class="rael-widget-category mx-3"><?php esc_html_e( '|', 'responsive-addons-for-elementor' ); ?></span>
					<span class="rael-widget-category rael-widget-tab pointer" id="woocommerce"><?php esc_html_e( 'WooCommerce', 'responsive-addons-for-elementor' ); ?></span>
					<span class="rael-widget-category mx-3"><?php esc_html_e( '|', 'responsive-addons-for-elementor' ); ?></span>
					<span class="rael-widget-category rael-widget-tab pointer" id="seo"><?php esc_html_e( 'SEO', 'responsive-addons-for-elementor' ); ?></span>
					<span class="rael-widget-category mx-3"><?php esc_html_e( '|', 'responsive-addons-for-elementor' ); ?></span>
					<span class="rael-widget-category rael-widget-tab pointer" id="themebuilder"><?php esc_html_e( 'Theme Builder', 'responsive-addons-for-elementor' ); ?></span>
					
				</p>
			</div>
		</div>
		<div class="col-lg-3 text-center">
			<div class="rael-widget-search-box">
				<input type="text" id="rael-input-search-widgets" autocomplete="off" placeholder="<?php esc_html_e( 'Search Widgets', 'responsive-addons-for-elementor' ); ?>">
				<i class="rael-widget-search-icon"><span class="dashicons dashicons-search"></span></i>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 my-5">
			<div class="row">
				<div class="rael-widgets-toggle-all-widgets-section d-flex justify-content-center">
					<div class="rael-widgets-toggle-all-text-content">
						<p class="rael-widget-toogle-widget-title text-center"><?php esc_html_e( 'Toggle All Widgets', 'responsive-addons-for-elementor' ); ?>
						</p>
						<p class="rael-widget-toogle-widget-desc text-center"><?php esc_html_e( 'You can disable some widgets for faster page speed.', 'responsive-addons-for-elementor' ); ?></p>
					</div>
					<div class="rael-widgets-toggle-widget-switch">
						<label class="rael-widgets-switch mt-2">
							<input id="rael-widgets-toggle-widgets" <?php echo esc_attr( $toggle_all_status ); ?> type="checkbox">
							<span class="rael-widgets-slider rael-widgets-round"></span>
						</label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row rael-widget-cards-group">
		<?php
		foreach ( $widgets as $index => $widget ) {
			$widget_status = $widget['status'] ? 'checked' : '';
			?>
		<div class="col-lg-3 col-md-4 gy-3 rael-widget-category-card rael-widget-category-<?php echo esc_attr( $widget['category'] ); ?>">
			<div class="rael-widgets-card d-flex justify-content-between h-100">
				<div class="rael-widgets-card-text-content">
					<div class="rael-widgets-card-title"><p><?php echo array_key_exists( 'name', $widget ) ? esc_html( $widget['name'] ) : esc_html( str_replace( '-', ' ', $widget['title'] ) ); ?></p></div>
			<?php
			if ( '' !== $widget['docs'] ) {
				?>
					<div class="rael-widgets-card-docs"><a href="<?php echo esc_url( $widget['docs'] ); ?>" target="_blank"><?php esc_html_e( 'Docs', 'responsive-addons-for-elementor' ); ?></a></div>
				<?php
			}
			?>
				</div>
				<div class="rael-widgets-card-switch align-self-center">
					<label class="rael-widgets-switch mt-2">
						<input class="rael-widgets-input-checkbox" data-index="<?php echo esc_attr( $index ); ?>" type="checkbox" id="<?php echo esc_attr( $widget['title'] ); ?>" <?php echo esc_attr( $widget_status ); ?>>
						<span class="rael-widgets-slider rael-widgets-round"></span>
					</label>
				</div>
			</div>
		</div>
			<?php
		}
		?>
	</div>
</div>
