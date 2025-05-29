<?php

/**
 * Template Name: Default
 *
 *  @package    Responsive_Addons_For_Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( isset( $content ) && isset( $link ) ) {
	echo '<div class="swiper-slide">
        <div class="ticker-content">';
	if ( ! empty( $link['url'] ) ) {
		echo '<a href="' . esc_url( $link['url'] ) . '" ';

		if ( 'on' == $link['is_external'] ) {
			echo 'target="_blank" ';
		}

		if ( 'on' == $link['nofollow'] ) {
			echo 'rel="nofollow"';
		}

		echo '>';
	}

		echo wp_kses_post( $content );

	if ( ! empty( $link['url'] ) ) {
		echo '</a>';
	}
		echo '</div>
    </div>';
} else {
	echo '<div class="swiper-slide">
        <div class="ticker-content">
            <a href="' . esc_url( get_the_permalink() ) . '" class="ticker-content-link">' . esc_html( get_the_title() ) . '</a>
        </div>
    </div>';
}
