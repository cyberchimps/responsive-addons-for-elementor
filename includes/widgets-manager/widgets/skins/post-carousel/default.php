<?php
/**
 * Default Skin
 *
 * @package Responsive_Addons_For_Elementor
 */

use Elementor\Group_Control_Image_Size;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$settings['rael_image_link_nofollow']         = $settings['rael_image_link_nofollow'] ? 'rel="nofollow"' : '';
$settings['rael_title_link_nofollow']         = $settings['rael_title_link_nofollow'] ? 'rel="nofollow"' : '';
$settings['rael_read_more_link_nofollow']     = $settings['rael_read_more_link_nofollow'] ? 'rel="nofollow"' : '';
$settings['rael_image_link_target_blank']     = $settings['rael_image_link_target_blank'] ? 'target="_blank"' : '';
$settings['rael_title_link_target_blank']     = $settings['rael_title_link_target_blank'] ? 'target="_blank"' : '';
$settings['rael_read_more_link_target_blank'] = $settings['rael_read_more_link_target_blank'] ? 'target="_blank"' : '';

if ( isset( $settings['rael_title_tag'] ) ) {
	$settings['rael_title_tag'] = Helper::validate_html_tags( $settings['rael_title_tag'] );
}

echo '<div class="swiper-slide">';
if ( 'two' === $settings['rael_post_preset_style'] ) :
	?>
	<article class="rael-grid-post rael-post-carousel-grid-column">
		<div class="rael-grid-post-holder">
			<div class="rael-grid-post-holder-inner">
				<?php
				if ( ( '0' === $settings['rael_show_image'] || 'yes' === $settings['rael_show_image'] ) && has_post_thumbnail() ) {
					echo '<div class="rael-post-carousel__entry-media rael-post-carousel__entry-media--none">';

					if ( 'yes' === $settings['rael_show_post_terms'] ) {
						echo wp_kses_post( Helper::get_terms_as_list( $settings['rael_post_terms'], $settings['rael_post_terms_max_length'] ) );
					}

					if ( isset( $settings['rael_post_hover_animation'] ) && 'none' !== $settings['rael_post_hover_animation'] ) {
						echo '<div class="rael-post-carousel__entry-overlay ' . esc_attr( $settings['rael_post_hover_animation'] ) . '">';

						if ( isset( $settings['__fa4_migrated']['rael_post_bg_hover_icon_new'] ) || empty( $settings['rael_post_bg_hover_icon'] ) ) {
							echo '<i class="' . esc_attr( $settings['rael_post_bg_hover_icon_new']['value'] ) . '" aria-hidden="true"></i>';
						} else {
							echo '<i class="fas fa-long-arrow-alt-right" aria-hidden="true"></i>';
						}
						echo '<a href="' . esc_url( get_the_permalink() ) . '"' . wp_kses_post( $settings['rael_image_link_nofollow'] ) . '' . wp_kses_post( $settings['rael_image_link_target_blank'] ) . '></a></div>';
					}

					echo '<div class="rael-post-carousel__entry-thumbnail">
							<a href="' . esc_url( get_the_permalink() ) . '"' . wp_kses_post( $settings['rael_image_link_nofollow'] ) . '' . wp_kses_post( $settings['rael_image_link_target_blank'] ) . '>
                            	<img src="' . esc_url( Group_Control_Image_Size::get_attachment_image_src( get_post_thumbnail_id(), 'rael_image_size', $settings ) ) . '" alt="' . esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) . '">
							</a>
						</div>';
					echo '</div>';
				}

				if ( $settings['rael_show_title'] || $settings['rael_show_meta'] || $settings['rael_show_excerpt'] ) {
					echo '<div class="rael-post-carousel__entry-wrapper">';

					echo '<header class="rael-post-carousel__entry-header">';

					if ( $settings['rael_show_meta'] && 'meta-entry-header' === $settings['rael_meta_position'] ) {


						echo '<div class="rael-post-carousel__entry-meta">';
						if ( 'yes' === $settings['rael_show_date'] ) {
							echo '<span class="rael-post-carousel__meta-posted-on"><i class="far fa-clock"></i><time datetime="' . get_the_date() . '">' . get_the_date() . '</time></span>';
						}
						if ( 'yes' === $settings['rael_show_post_terms'] ) {
							if ( 'category' === $settings['rael_post_terms'] ) {
								$terms = get_the_category();
							}
							if ( 'tags' === $settings['rael_post_terms'] ) {
								$terms = get_the_tags();
							}
							if ( ! empty( $terms ) ) {
								$html  = '<ul class="rael-post-carousel__meta-categories">';
								$count = 0;
								foreach ( $terms as $custom_term ) {
									if ( intval( $settings['rael_post_terms_max_length'] ) === $count ) {
										break;
									}
									if ( 0 === $count ) {
										$html .= '<li class="rael-post-carousel__meta-cat-icon"><i class="far fa-folder-open"></i></li>';
									}
									$url   = ( 'category' === $settings['rael_post_terms'] ) ? get_category_link( $custom_term->term_id ) : get_tag_link( $custom_term->term_id );
									$html .= '<li>';
									$html .= '<a href="' . esc_url( $url ) . '">';
									$html .= $custom_term->name;
									$html .= '</a>';
									$html .= '</li>';
									++$count;
								}
								$html .= '</ul>';
								echo wp_kses_post( $html );
							}
						}
						echo '</div>';


					}

					if ( $settings['rael_show_title'] ) {
						echo '<' . esc_html( $settings['rael_title_tag'] ) . ' class="rael-post-carousel__entry-title">';
						echo '<a class="rael-post-carousel__post-link" href="' . esc_url( get_permalink() ) . '" title="' . esc_html( get_the_title() ) . '"' . wp_kses_post( $settings['rael_title_link_nofollow'] ) . '' . wp_kses_post( $settings['rael_title_link_target_blank'] ) . '>';
						if ( empty( $settings['rael_title_length'] ) ) {
							echo esc_html( get_the_title() );
						} else {
							echo implode( ' ', wp_kses_post( array_slice( explode( ' ', get_the_title() ), 0, $settings['rael_title_length'] ) ) );
						}
						echo '</a>';
						echo '</' . esc_html( $settings['rael_title_tag'] ) . '>';
					}

					echo '</header>';


					echo '</div>';

					echo '<div class="rael-post-carousel__entry-content">
                    <div class="rael-post-carousel__excerpt">';
					if ( $settings['rael_show_excerpt'] ) {
						if ( empty( $settings['rael_excerpt_length'] ) ) {
							echo '<p>' . wp_kses_post( strip_shortcodes( get_the_excerpt() ? get_the_excerpt() : get_the_content() ) ) . '</p>';
						} else {
							echo '<p>' . wp_kses_post( wp_trim_words( strip_shortcodes( get_the_excerpt() ? get_the_excerpt() : get_the_content() ), $settings['rael_excerpt_length'], $settings['rael_excerpt_expansion_indicator'] ) ) . '</p>';
						}
					}
					if ( class_exists( 'WooCommerce' ) && 'product' === $settings['rael_post_type'] ) {
						echo '<p class="rael-post-carousel__entry-content-btn">';
						woocommerce_template_loop_add_to_cart();
						echo '</p>';
					} else {
						echo '<div class="rael-post-carousel__readmore-wrap"><a href="' . esc_url( get_the_permalink() ) . '" class="rael-post-carousel__readmore-btn"' . wp_kses_post( $settings['rael_read_more_link_nofollow'] ) . '' . wp_kses_post( $settings['rael_read_more_link_target_blank'] ) . '>' . esc_attr( $settings['rael_read_more_button_text'] ) . '</a></div>';
					}
					echo '</div>
                        </div>';

					if ( $settings['rael_show_meta'] && 'meta-entry-footer' === $settings['rael_meta_position'] ) {
						echo '<div class="rael-post-carousel__entry-footer--two">';

						echo '<div class="rael-post-carousel__entry-meta">';
						if ( 'yes' === $settings['rael_show_date'] ) {
							echo '<span class="rael-post-carousel__meta-posted-on"><i class="far fa-clock"></i><time datetime="' . get_the_date() . '">' . get_the_date() . '</time></span>';
						}
						if ( 'yes' === $settings['rael_show_post_terms'] ) {
							if ( 'category' === $settings['rael_post_terms'] ) {
								$terms = get_the_category();
							}

							if ( 'tags' === $settings['rael_post_terms'] ) {
								$terms = get_the_tags();
							}

							if ( ! empty( $terms ) ) {
								$html  = '<ul class="rael-post-carousel__meta-categories">';
								$count = 0;
								foreach ( $terms as $custom_term ) {
									if ( intval( $settings['rael_post_terms_max_length'] ) === $count ) {
										break;
									}
									if ( 0 === $count ) {
										$html .= '<li class="rael-post-carousel__meta-cat-icon"><i class="far fa-folder-open"></i></li>';
									}
									$url   = ( 'category' === $settings['rael_post_terms'] ) ? get_category_link( $custom_term->term_id ) : get_tag_link( $custom_term->term_id );
									$html .= '<li>';
									$html .= '<a href="' . esc_url( $url ) . '">';
									$html .= $custom_term->name;
									$html .= '</a>';
									$html .= '</li>';
									++$count;
								}
								$html .= '</ul>';
								echo wp_kses_post( $html );
							}
						}
						echo '</div>';

						echo '</div>';
					}
				}
				?>
			</div>
		</div>
	</article>
	<?php
elseif ( 'three' === $settings['rael_post_preset_style'] ) :
	?>
	<article class="rael-grid-post rael-post-carousel-grid-column">
		<div class="rael-grid-post-holder">
			<div class="rael-grid-post-holder-inner">
			<?php
			if ( ( '0' === $settings['rael_show_image'] || 'yes' === $settings['rael_show_image'] ) && has_post_thumbnail() ) {
				echo '<div class="rael-post-carousel__entry-media rael-post-carousel__entry-media--none">';

				if ( 'yes' === $settings['rael_show_post_terms'] ) {
					echo wp_kses_post( Helper::get_terms_as_list( $settings['rael_post_terms'], $settings['rael_post_terms_max_length'] ) );
				}

				if ( isset( $settings['rael_post_hover_animation'] ) && 'none' !== $settings['rael_post_hover_animation'] ) {
					echo '<div class="eael-entry-overlay ' . esc_attr( $settings['rael_post_hover_animation'] ) . '">';

					if ( isset( $settings['__fa4_migrated']['rael_post_bg_hover_icon_new'] ) || empty( $settings['rael_post_bg_hover_icon'] ) ) {
						echo '<i class="' . esc_attr( $settings['rael_post_bg_hover_icon_new']['value'] ) . '" aria-hidden="true"></i>';
					} else {
						echo '<i class="fas fa-long-arrow-alt-right" aria-hidden="true"></i>';
					}
					echo '<a href="' . esc_url( get_the_permalink() ) . '"' . wp_kses_post( $settings['rael_image_link_nofollow'] ) . '' . wp_kses_post( $settings['rael_image_link_target_blank'] ) . '></a></div>';
				}

				echo '<div class="rael-post-carousel__entry-thumbnail">
						<a href="' . esc_url( get_the_permalink() ) . '"' . wp_kses_post( $settings['rael_image_link_nofollow'] ) . '' . wp_kses_post( $settings['rael_image_link_target_blank'] ) . '>
							<img src="' . esc_url( Group_Control_Image_Size::get_attachment_image_src( get_post_thumbnail_id(), 'rael_image_size', $settings ) ) . '" alt="' . esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) . '">
						</a>
                    </div>';
				echo '</div>';

				if ( 'yes' === $settings['rael_show_date'] ) {
					echo '<span class="rael-post-carousel__meta-posted-on"><time datetime="' . get_the_date() . '"><span>' . get_the_date( 'd' ) . '</span>' . get_the_date( 'F' ) . '</time></span>';
				}
			}


			if ( $settings['rael_show_title'] || $settings['rael_show_meta'] || $settings['rael_show_excerpt'] ) :
				echo '<div class="rael-post-carousel__entry-wrapper">';

				echo '<header class="rael-post-carousel__entry-header">';

				if ( $settings['rael_show_title'] ) {
					echo '<' . esc_html( $settings['rael_title_tag'] ) . ' class="rael-post-carousel__entry-title">';
					echo '<a class="rael-post-carousel__post-link" href="' . esc_url( get_permalink() ) . '" title="' . esc_html( get_the_title() ) . '"' . wp_kses_post( $settings['rael_title_link_nofollow'] ) . '' . wp_kses_post( $settings['rael_title_link_target_blank'] ) . '>';
					if ( empty( $settings['rael_title_length'] ) ) {
						echo esc_html( get_the_title() );
					} else {
						echo implode( ' ', wp_kses_post( array_slice( explode( ' ', get_the_title() ), 0, $settings['rael_title_length'] ) ) );
					}
					echo '</a>';
					echo '</' . esc_html( $settings['rael_title_tag'] ) . '>';
				}

				echo '</header>';



				echo '</div>';


				echo '<div class="rael-post-carousel__entry-content">
                    <div class="rael-post-carousel__excerpt">';
				if ( $settings['rael_show_excerpt'] ) {
					if ( empty( $settings['rael_excerpt_length'] ) ) {
						echo '<p>' . wp_kses_post( strip_shortcodes( get_the_excerpt() ? get_the_excerpt() : get_the_content() ) ) . '</p>';
					} else {
						echo '<p>' . wp_kses_post( wp_trim_words( strip_shortcodes( get_the_excerpt() ? get_the_excerpt() : get_the_content() ), $settings['rael_excerpt_length'], $settings['rael_excerpt_expansion_indicator'] ) ) . '</p>';
					}
				}
				if ( class_exists( 'WooCommerce' ) && 'product' === $settings['rael_post_type'] ) {
					echo '<p class="rael-post-carousel__entry-content-btn">';
					woocommerce_template_loop_add_to_cart();
					echo '</p>';
				} else {
					echo '<div class="rael-post-carousel__readmore-wrap"><a href="' . esc_url( get_the_permalink() ) . '" class="rael-post-carousel__readmore-btn"' . wp_kses_post( $settings['rael_read_more_link_nofollow'] ) . '' . wp_kses_post( $settings['rael_read_more_link_target_blank'] ) . '>' . esc_attr( $settings['rael_read_more_button_text'] ) . '</a></div>';
				}
					echo '</div>
                </div>';

			endif;
			?>
			</div>
		</div>
	</article>
	<?php
else :
	?>
	<article class="rael-grid-post rael-post-carousel-grid-column">
		<div class="rael-grid-post-holder">
			<div class="rael-grid-post-holder-inner">
			<?php
			if ( ( '0' === $settings['rael_show_image'] || 'yes' === $settings['rael_show_image'] ) && has_post_thumbnail() ) {
				echo '<div class="rael-post-carousel__entry-media rael-post-carousel__entry-media--none">';

				if ( 'yes' === $settings['rael_show_post_terms'] ) {
					echo wp_kses_post( Helper::get_terms_as_list( $settings['rael_post_terms'], $settings['rael_post_terms_max_length'] ) );
				}

				if ( isset( $settings['rael_post_hover_animation'] ) && 'none' !== $settings['rael_post_hover_animation'] ) {
					echo '<div class="rael-post-carousel__entry-overlay ' . esc_attr( $settings['rael_post_hover_animation'] ) . '">';

					if ( isset( $settings['__fa4_migrated']['rael_post_bg_hover_icon_new'] ) || empty( $settings['rael_post_bg_hover_icon'] ) ) {
						echo '<i class="' . esc_attr( $settings['rael_post_bg_hover_icon_new']['value'] ) . '" aria-hidden="true"></i>';
					} else {
						echo '<i class="fas fa-long-arrow-alt-right" aria-hidden="true"></i>';
					}
					echo '<a href="' . esc_url( get_the_permalink() ) . '"' . wp_kses_post( $settings['rael_image_link_nofollow'] ) . '' . wp_kses_post( $settings['rael_image_link_target_blank'] ) . '></a></div>';
				}

				echo '<div class="rael-post-carousel__entry-thumbnail">
						<a href="' . esc_url( get_the_permalink() ) . '"' . wp_kses_post( $settings['rael_image_link_nofollow'] ) . '' . wp_kses_post( $settings['rael_image_link_target_blank'] ) . '>
							<img src="' . esc_url( Group_Control_Image_Size::get_attachment_image_src( get_post_thumbnail_id(), 'rael_image_size', $settings ) ) . '" alt="' . esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) . '">
						</a>
                    </div>';
				echo '</div>';
			}

			if ( $settings['rael_show_title'] || $settings['rael_show_meta'] || $settings['rael_show_excerpt'] ) :
				echo '<div class="rael-post-carousel__entry-wrapper">';

				$meta_markup  = '';
				$meta_markup .= '<div class="rael-post-carousel__entry-meta">';
				if ( 'yes' === $settings['rael_show_author'] ) {
					$meta_markup .= '<span class="rael-post-carousel__posted-by">' . get_the_author_posts_link() . '</span>';
				}
				if ( 'yes' === $settings['rael_show_date'] ) {
					$meta_markup .= '<span class="rael-post-carousel__meta-posted-on"><time datetime="' . get_the_date() . '">' . get_the_date() . '</time></span>';
				}
				$meta_markup .= '</div>';

				echo '<header class="rael-post-carousel__entry-header">';

				if ( $settings['rael_show_title'] ) {
					echo '<' . esc_html( $settings['rael_title_tag'] ) . ' class="rael-post-carousel__entry-title">';
					echo '<a class="rael-post-carousel__post-link" href="' . esc_url( get_permalink() ) . '" title="' . esc_html( get_the_title() ) . '"' . wp_kses_post( $settings['rael_title_link_nofollow'] ) . '' . wp_kses_post( $settings['rael_title_link_target_blank'] ) . '>';
					if ( empty( $settings['rael_title_length'] ) ) {
						echo esc_html( get_the_title() );
					} else {
						echo implode( ' ', wp_kses_post( array_slice( explode( ' ', get_the_title() ), 0, $settings['rael_title_length'] ) ) );
					}
					echo '</a>';
					echo '</' . esc_html( $settings['rael_title_tag'] ) . '>';
				}
				if ( $settings['rael_show_meta'] && 'meta-entry-header' === $settings['rael_meta_position'] ) {
					echo wp_kses_post( $meta_markup );
				}
				echo '</header>';

				echo '</div>';


				echo '<div class="rael-post-carousel__entry-content">
                    <div class="rael-post-carousel__excerpt">';
				if ( $settings['rael_show_excerpt'] ) {
					if ( empty( $settings['rael_excerpt_length'] ) ) {
						echo '<p>' . wp_kses_post( strip_shortcodes( get_the_excerpt() ? get_the_excerpt() : get_the_content() ) ) . '</p>';
					} else {
						echo '<p>' . wp_kses_post( wp_trim_words( strip_shortcodes( get_the_excerpt() ? get_the_excerpt() : get_the_content() ), $settings['rael_excerpt_length'], $settings['rael_excerpt_expansion_indicator'] ) ) . '</p>';
					}
				}
				if ( class_exists( 'WooCommerce' ) && 'product' === $settings['rael_post_type'] ) {
					echo '<p class="rael-post-carousel__entry-content-btn">';
					woocommerce_template_loop_add_to_cart();
					echo '</p>';
				} else {
					echo '<div class="rael-post-carousel__readmore-wrap"><a href="' . esc_url( get_the_permalink() ) . '" class="rael-post-carousel__readmore-btn"' . wp_kses_post( $settings['rael_read_more_link_nofollow'] ) . '' . wp_kses_post( $settings['rael_read_more_link_target_blank'] ) . '>' . esc_attr( $settings['rael_read_more_button_text'] ) . '</a></div>';
				}
					echo '</div>
                </div>';


				if ( $settings['rael_show_meta'] && 'meta-entry-footer' === $settings['rael_meta_position'] ) {

					echo '<div class="rael-post-carousel__entry-footer">';
					if ( 'yes' === $settings['rael_show_avatar'] ) {
						echo '<div class="rael-post-carousel__author-avatar">
                            <a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . get_avatar( get_the_author_meta( 'ID' ), 96 ) . '</a>
                        </div>';
					}
					echo '<div class="rael-post-carousel__entry-meta">';
					if ( 'yes' === $settings['rael_show_author'] ) {
						echo '<div class="rael-post-carousel__posted-by">' . esc_url( get_the_author_posts_link() ) . '</div>';
					}
					if ( 'yes' === $settings['rael_show_date'] ) {
						echo '<div class="rael-post-carousel__meta-posted-on"><time datetime="' . get_the_date() . '">' . get_the_date() . '</time></div>';
					}
					echo '</div>';
					echo '</div>';

				}
			endif;
			?>
			</div>
		</div>
	</article>
	<?php
endif;
echo '</div>';
