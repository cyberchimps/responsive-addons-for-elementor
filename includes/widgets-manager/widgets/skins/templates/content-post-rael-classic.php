<?php
/**
 * RAEL Post Classic Skin - Template.
 *
 * @package RAEL
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;

// Ensure visibility.
if ( empty( $post ) ) {
	return;
}

?>

<?php do_action( 'rael_single_post_before_wrap', get_the_ID(), $settings ); ?>


<article class="elementor-post elementor-grid-item">
	<?php $this->render_featured_image(); ?>
	<div class="elementor-post__text">
		<?php
		$content_positions_key = array(
			empty( $this->get_instance_value( 'title_position' ) ) ? 0 : $this->get_instance_value( 'title_position' ),
			empty( $this->get_instance_value( 'meta_data_position' ) ) ? 0 : $this->get_instance_value( 'meta_data_position' ),
			empty( $this->get_instance_value( 'excerpt_position' ) ) ? 0 : $this->get_instance_value( 'excerpt_position' ),
			empty( $this->get_instance_value( 'read_more_position' ) ) ? 0 : $this->get_instance_value( 'read_more_position' ),
		);

		$content_positions_value = array(
			'render_title',
			'render_meta_data',
			'render_excerpt',
			'render_read_more',
		);

		$positions = array_combine( $content_positions_key, $content_positions_value );
		$temp      = ksort( $positions );

		foreach ( $positions as $key => $value ) {
			if ( 0 !== $key ) {
				$this->$value();
			}
		}
		?>
	</div>
</article>




