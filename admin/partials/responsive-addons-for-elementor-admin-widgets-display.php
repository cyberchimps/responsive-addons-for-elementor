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
					<span class="rael-widget-category rael-widget-tab pointer" id="woocommerce"><?php esc_html_e( 'Woo', 'responsive-addons-for-elementor' ); ?></span>
					<span class="rael-widget-category mx-3"><?php esc_html_e( '|', 'responsive-addons-for-elementor' ); ?></span>
					<span class="rael-widget-category rael-widget-tab pointer" id="seo"><?php esc_html_e( 'SEO', 'responsive-addons-for-elementor' ); ?></span>
					<span class="rael-widget-category mx-3"><?php esc_html_e( '|', 'responsive-addons-for-elementor' ); ?></span>
					<span class="rael-widget-category rael-widget-tab pointer" id="themebuilder"><?php esc_html_e( 'Theme Builder', 'responsive-addons-for-elementor' ); ?></span>
					<span class="rael-widget-category mx-3"><?php esc_html_e( '|', 'responsive-addons-for-elementor' ); ?></span>
					<span class="rael-widget-category rael-widget-tab pointer" id="extensions"><?php esc_html_e( 'Extensions', 'responsive-addons-for-elementor' ); ?></span>
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
			// Fetch public custom post types (not builtin)
			$custom_post_types = get_post_types(
			[
				'public'   => true,
				'_builtin' => false,
			],
			'objects'
		);

		$filtered = [];

		foreach ( $custom_post_types as $slug => $pt ) {

			// AUTO-REMOVE all Elementor / Theme Builder CPTs
			if (
				// Elementor common slug patterns
				strpos($slug, 'elementor') === 0 ||
				strpos($slug, 'e-') === 0 ||
				strpos($slug, 'elementor-') === 0 ||
				strpos($slug, 'etheme') === 0 ||

				// Elementor Pro Theme Builder labels
				stripos($pt->label, 'elementor') !== false ||
				stripos($pt->label, 'template') !== false ||
				stripos($pt->label, 'theme') !== false ||
				stripos($pt->label, 'builder') !== false ||

				// Some versions use "Kit" for Theme Style Library
				stripos($pt->label, 'kit') !== false
			) {
				continue;
			}

			// Exclude if needed
			if ( in_array($slug, ['attachment'], true) ) {
				continue;
			}

			$filtered[$slug] = $pt->label;
		}

		$custom_post_types = $filtered;

		?>
		<div class="col-lg-3 col-md-4 gy-3 rael-widget-category-card rael-widget-category-<?php echo esc_attr( $widget['category'] ); ?>">
			<div class="rael-widgets-card d-flex justify-content-between h-100">
				<div class="rael-widgets-card-text-content">
					<div class="rael-widgets-card-title"><p><?php echo array_key_exists( 'name', $widget ) ? esc_html( $widget['name'] ) : esc_html( str_replace( '-', ' ', $widget['title'] ) ); ?></p></div>
			<?php
			if ( '' !== $widget['docs'] ) {
				?>
					<div class="rael-widgets-card-docs">
						<a href="<?php echo esc_url( $widget['docs'] ); ?>" target="_blank"><?php esc_html_e( 'Docs', 'responsive-addons-for-elementor' ); ?></a> 
						<?php if ($widget['title'] == 'duplicator') {
							
							 ?>
							<span class="rael-dup-separator">| </span>
						<a href="#" class="rael-settings-trigger" data-widget="<?php echo esc_attr($widget['title']); ?>">
							 <?php esc_html_e( 'Settings', 'responsive-addons-for-elementor' ); ?>
    					</a>
						<div id="rael-settings-popup" class="rael-popup-overlay" style="display: none;">
							<div class="rael-popup">
								<div class="rael-popup-header">
									<h2 id="rael-popup-title"><?php esc_html_e('Duplicator','responsive-addons-for-elementor'); ?></h2>
									<span class="rael-popup-close">&times;</span>
								</div>
								<hr class="rae-popup-hr"/>

								<div class="rael-popup-body">
									<label><?php esc_html_e( 'Select Post Types','responsive-addons-for-elementor'); ?></label>
									<select id="rael-post-types">
										<?php 	
											$get_selected_posttypes = get_option( 'rael_duplicator_allowed_post_types', array( 'all' ) );
											$selected_type = $get_selected_posttypes[0];
										?>
										<option value="all" <?php selected( $selected_type, 'all' ); ?>><?php esc_html_e('All','responsive-addons-for-elementor'); ?></option>
										<option value="post" <?php selected( $selected_type, 'post' ); ?>><?php esc_html_e('Post','responsive-addons-for-elementor'); ?></option>
    									<option value="page" <?php selected( $selected_type, 'page' ); ?>><?php esc_html_e('Page','responsive-addons-for-elementor'); ?></option>
										<?php foreach ( $custom_post_types as $type => $label ) : ?>
											<option value="<?php echo esc_attr($type); ?>" <?php selected( $selected_type, $type ); ?>>
												<?php echo esc_html($label); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
								<hr class="rae-popup-hr"/>
								<div class="rael-popup-footer">
									<button id="rael-popup-save" class="button button-primary"><?php esc_html_e('Save', 'responsive-addons-for-elementor'); ?></button>
								</div>
							</div>
						</div>

						<?php } ?>
					</div>
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
