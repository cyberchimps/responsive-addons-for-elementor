<?php
/**
 * Header file in case of the elementor way
 *
 * @package Responsive_Addons_For_Elementor
 */

use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php
		// PHPCS - not a user input.
		echo Utils::get_meta_viewport( 'theme-builder' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
	<?php if ( ! current_theme_supports( 'title-tag' ) ) : ?>
		<title>
			<?php
				// PHPCS - already escaped by WordPress.
				echo wp_get_document_title(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</title>
	<?php endif; ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
	wp_body_open();
	do_action( 'rael_header' );
?>
