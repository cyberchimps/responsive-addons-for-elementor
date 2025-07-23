<?php

namespace Responsive_Addons_For_Elementor\Helper;

use Elementor\Plugin;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\Woocommerce\Classes\Products_Renderer;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\Woocommerce\RAEL_Products;
use Responsive_Addons_For_Elementor\Traits\RAEL_Products_Comparable;

class Helper {
	// use RAEL_Products_Comparable;

	public static function register_woo_actions() {
		if ( class_exists( 'woocommerce' ) ) {
			add_action( 'rael_woo_single_product_image', 'woocommerce_show_product_images', 20 );
			add_action( 'rael_woo_single_product_summary', 'woocommerce_template_single_title', 5 );
			add_action( 'rael_woo_single_product_summary', 'woocommerce_template_single_rating', 10 );
			add_action( 'rael_woo_single_product_summary', 'woocommerce_template_single_price', 15 );
			add_action( 'rael_woo_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
			add_action( 'rael_woo_single_product_summary', 'woocommerce_template_single_add_to_cart', 25 );
			add_action( 'rael_woo_single_product_summary', 'woocommerce_template_single_meta', 30 );

			add_filter( 'woocommerce_product_get_rating_html', array( __CLASS__, 'rating_markup' ), 10, 3 );
		}
	}

	public static function get_allowed_tags() {
		return array(
			'a'       => array(
				'href'  => array(),
				'title' => array(),
				'class' => array(),
				'rel'   => array(),
				'id'    => array(),
				'style' => array(),
			),
			'q'       => array(
				'cite'  => array(),
				'class' => array(),
				'id'    => array(),
			),
			'img'     => array(
				'src'    => array(),
				'alt'    => array(),
				'height' => array(),
				'width'  => array(),
				'class'  => array(),
				'id'     => array(),
				'style'  => array(),
			),
			'span'    => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'dfn'     => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'time'    => array(
				'datetime' => array(),
				'class'    => array(),
				'id'       => array(),
				'style'    => array(),
			),
			'cite'    => array(
				'title' => array(),
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'hr'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'b'       => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'p'       => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'i'       => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'u'       => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			's'       => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'br'      => array(),
			'em'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'code'    => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'mark'    => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'small'   => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'abbr'    => array(
				'title' => array(),
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'strong'  => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'del'     => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'ins'     => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'sub'     => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'sup'     => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'div'     => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'strike'  => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'acronym' => array(),
			'h1'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'h2'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'h3'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'h4'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'h5'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'h6'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'button'  => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
		);
	}

	public static function strip_tags_keeping_allowed_tags( $content ) {
		return wp_kses( $content, self::get_allowed_tags() );
	}

	public static function validate_html_tags( $tag, $default_tag='div' ) {
		return array_key_exists( strtolower( $tag ), self::get_allowed_tags() ) ? $tag : $default_tag;
	}

	public static function ajax_get_compare_table() {
		RAEL_Products_Comparable::get_compare_table();
	}

	public static function rating_markup( $html, $rating, $count ) {

		if ( 0 === $rating ) {
			$html  = '<div class="star-rating">';
			$html .= wc_get_star_rating_html( $rating, $count );
			$html .= '</div>';
		}
		return $html;
	}

	public static function rael_product_quickview_popup() {
		// Verify Nonce.
		$widget_id  = isset( $_POST['widget_id'] ) ? sanitize_key( $_POST['widget_id'] ) : '';
		$product_id = isset( $_POST['product_id'] ) ? sanitize_key( $_POST['product_id'] ) : '';
		$page_id    = isset( $_POST['page_id'] ) ? sanitize_key( $_POST['page_id'] ) : '';

		if ( '' === $widget_id && '' === $product_id && '' === $page_id ) {
			wp_send_json_error();
		}
		global $post, $product;
		$product = wc_get_product( $product_id );
		$post    = get_post( $product_id );
		setup_postdata( $post );

		$settings = self::get_widget_settings( $page_id, $widget_id );
		ob_start();
		self::product_quick_view( $product, $settings, $widget_id );
		$data = ob_get_clean();
		wp_reset_postdata();

		wp_send_json_success( $data );
	}

	public static function get_widget_settings( $page_id, $widget_id ) {
		$document = Plugin::$instance->documents->get( $page_id );
		$settings = array();
		if ( $document ) {
			$elements    = Plugin::instance()->documents->get( $page_id )->get_elements_data();
			$widget_data = self::find_element_recursive( $elements, $widget_id );
			if ( ! empty( $widget_data ) ) {
				$widget = Plugin::instance()->elements_manager->create_element_instance( $widget_data );
				if ( $widget ) {
					$settings = $widget->get_settings_for_display();
				}
			}
		}
		return $settings;
	}

	public static function find_element_recursive( $elements, $form_id ) {

		foreach ( $elements as $element ) {
			if ( $form_id === $element['id'] ) {
				return $element;
			}

			if ( ! empty( $element['elements'] ) ) {
				$element = self::find_element_recursive( $element['elements'], $form_id );

				if ( $element ) {
					return $element;
				}
			}
		}

		return false;
	}

	public static function product_quick_view( $product, $settings, $widget_id ) {
		$sale_badge_align  = isset( $settings['rael_pc_sale_badge_alignment'] ) ? $settings['rael_pc_sale_badge_alignment'] : '';
		$sale_badge_preset = isset( $settings['rael_pc_sale_badge_preset'] ) ? $settings['rael_pc_sale_badge_preset'] : '';
		$sale_text         = ! empty( $settings['rael_pc_sale_text'] ) ? $settings['rael_pc_sale_text'] : 'Sale!';
		$stockout_text     = ! empty( $settings['rael_pc_stockout_text'] ) ? $settings['rael_pc_stockout_text'] : 'Stock Out';
		$tag               = ! empty( $settings['rael_pc_quick_view_title_tag'] ) ? self::validate_html_tags( $settings['rael_pc_quick_view_title_tag'] ) : 'h1';

		remove_action( 'rael_woo_single_product_summary', 'woocommerce_template_single_title', 5 );
		add_action(
			'rael_woo_single_product_summary',
			function () use ( $tag ) {
				the_title( '<' . $tag . ' class="rael-pc__product-quick-view-title product_title entry-title">', '</' . $tag . '>' );
			},
			5
		);

		?>
		<div id="rael-product<?php echo esc_attr( $widget_id . $product->get_id() ); ?>" class="rael-pc__product-popup
		rael-pc__product-zoom-in woocommerce">
			<div class="rael-pc__product-modal-bg"></div>
			<div class="rael-pc__product-popup-details">
				<div id="product-<?php the_ID(); ?>" <?php post_class( 'product' ); ?>>
					<div class="rael-pc__product-image-wrapper">
						<?php
						echo ( ! $product->is_in_stock() ? '<span class="rael-pc__onsale rael-pc__out-of-stock ' . esc_attr( $sale_badge_preset ) . ' ' . esc_attr( $sale_badge_align ) . '">' . esc_html( $stockout_text ) . '</span>' : ( $product->is_on_sale() ? '<span class="rael-pc__onsale ' . esc_attr( $sale_badge_preset ) . ' ' . esc_attr( $sale_badge_align ) . '">' . esc_html( $sale_text ) . '</span>' : '' ) );
						do_action( 'rael_woo_single_product_image' );
						?>
					</div>
					<div class="rael-pc__product-details-wrapper">
						<?php do_action( 'rael_woo_single_product_summary' ); ?>
					</div>
				</div>
				<button class="rael-pc__product-popup-close"><i class="fas fa-times"></i></button>
			</div>

		</div>
		<?php
	}

	public static function rael_product_add_to_cart() {

		$ajax       = wp_doing_ajax();
		$cart_items = isset( $_POST['cart_item_data'] ) ? $_POST['cart_item_data'] : array();
		$variation  = array();
		if ( ! empty( $cart_items ) ) {
			foreach ( $cart_items as $key => $value ) {
				if ( preg_match( '/^attribute*/', $value['name'] ) ) {
					$variation[ $value['name'] ] = $value['value'];
				}
			}
		}

		if ( isset( $_POST['product_data'] ) ) {
			foreach ( $_POST['product_data'] as $item ) {
				$product_id   = isset( $item['product_id'] ) ? sanitize_text_field( $item['product_id'] ) : 0;
				$variation_id = isset( $item['variation_id'] ) ? sanitize_text_field( $item['variation_id'] ) : 0;
				$quantity     = isset( $item['quantity'] ) ? sanitize_text_field( $item['quantity'] ) : 0;

				if ( $variation_id ) {
					WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
				} else {
					WC()->cart->add_to_cart( $product_id, $quantity );
				}
			}
		}
		wp_send_json_success();
	}
	public static function get_terms_list( $taxonomy = 'category', $key = 'term_id' ) {
		$options = array();
		$terms   = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => true,
			)
		);

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->{$key} ] = $term->name;
			}
		}

		return $options;
	}

	public static function dimensions_css( $property ) {
		return "{$property}: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};";
	}
	/**
	 * Fix old query
	 */

	public static function fix_old_query( $settings ) {
		 $update_query = false;

		foreach ( $settings as $key => $value ) {
			if ( strpos( $key, 'eaeposts_' ) !== false ) {
				$settings[ str_replace( 'eaeposts_', '', $key ) ] = $value;
				$update_query                                     = true;
			}
		}

		if ( $update_query ) {
			global $wpdb;

			$post_id = get_the_ID();
			$data    = get_post_meta( $post_id, '_elementor_data', true );
			$data    = str_replace( 'eaeposts_', '', $data );
			$wpdb->update(
				$wpdb->postmeta,
				array(
					'meta_value' => $data,
				),
				array(
					'post_id'  => $post_id,
					'meta_key' => '_elementor_data',
				)
			);
		}

		return $settings;
	}
	public static function get_query_args_ticker( $settings = array(), $post_type = 'post' ) {
		$settings = wp_parse_args(
			$settings,
			array(
				'post_type'      => $post_type,
				'posts_ids'      => array(),
				'orderby'        => 'date',
				'order'          => 'desc',
				'posts_per_page' => 3,
				'offset'         => 0,
				'post__not_in'   => array(),
			)
		);

		$args = array(
			'orderby'             => $settings['orderby'],
			'order'               => $settings['order'],
			'ignore_sticky_posts' => 1,
			'post_status'         => 'publish',
			'posts_per_page'      => $settings['posts_per_page'],
			'offset'              => $settings['offset'],
		);

		if ( 'by_id' === $settings['post_type'] ) {
			$args['post_type'] = 'any';
			$args['post__in']  = empty( $settings['posts_ids'] ) ? array( 0 ) : $settings['posts_ids'];
		} else {
			$args['post_type'] = $settings['post_type'];

			// if ($args['post_type'] !== 'page') {
				$args['tax_query'] = array();

				$taxonomies = get_object_taxonomies( $settings['post_type'], 'objects' );

			foreach ( $taxonomies as $object ) {
				$setting_key = $object->name . '_ids';

				if ( ! empty( $settings[ $setting_key ] ) ) {
					$args['tax_query'][] = array(
						'taxonomy' => $object->name,
						'field'    => 'term_id',
						'terms'    => $settings[ $setting_key ],
					);
				}
			}

			if ( ! empty( $args['tax_query'] ) ) {
				$args['tax_query']['relation'] = 'AND';
			}
			// }
		}

		if ( ! empty( $settings['authors'] ) ) {
			$args['author__in'] = $settings['authors'];
		}

		if ( ! empty( $settings['post__not_in'] ) ) {
			$args['post__not_in'] = $settings['post__not_in'];
		}

		return $args;
	}

	public static function get_query_args( $settings = array(), $post_type = 'post' ) {
		$settings = wp_parse_args(
			$settings,
			array(
				'post_type'      => $post_type,
				'posts_ids'      => array(),
				'orderby'        => 'date',
				'order'          => 'desc',
				'posts_per_page' => 3,
				'offset'         => 0,
				'post__not_in'   => array(),
			)
		);

		$args = array(
			'orderby'             => $settings['rael_orderby'],
			'order'               => $settings['rael_order'],
			'ignore_sticky_posts' => 1,
			'post_status'         => 'publish',
			'posts_per_page'      => $settings['posts_per_page'],
			'offset'              => $settings['offset'],
		);

		if ( 'by_id' === $settings['rael_post_type'] ) {
			$args['post_type'] = 'any';
			$args['post__in']  = empty( $settings['posts_ids'] ) ? array( 0 ) : $settings['posts_ids'];
		} else {
			$args['post_type'] = $settings['rael_post_type'];
			$args['tax_query'] = array();
			$taxonomies        = get_object_taxonomies( $settings['rael_post_type'], 'objects' );

			foreach ( $taxonomies as $object ) {
				$setting_key = $object->name . '_ids';

				if ( ! empty( $settings[ $setting_key ] ) ) {
					$args['tax_query'][] = array(
						'taxonomy' => $object->name,
						'field'    => 'term_id',
						'terms'    => $settings[ $setting_key ],
					);
				}
			}

			if ( ! empty( $args['tax_query'] ) ) {
				$args['tax_query']['relation'] = 'AND';
			}
		}

		if ( ! empty( $settings['authors'] ) ) {
			$args['author__in'] = $settings['authors'];
		}

		if ( ! empty( $settings['post__not_in'] ) ) {
			$args['post__not_in'] = $settings['post__not_in'];
		}

		return $args;
	}

	public static function get_dynamic_args( array $settings, array $args ) {
		if ( 'source_dynamic' === $settings['rael_post_type'] && is_archive() ) {
			$data = get_queried_object();

			if ( isset( $data->post_type ) ) {
				$args['post_type'] = $data->post_type;
				$args['tax_query'] = array();
			} else {
				global $wp_query;
				$args['post_type'] = $wp_query->query_vars['post_type'];
				if ( ! empty( $wp_query->query_vars['s'] ) ) {
					$args['s']      = $wp_query->query_vars['s'];
					$args['offset'] = 0;
				}
			}

			if ( isset( $data->taxonomy ) ) {
				$args['tax_query'][] = array(
					'taxonomy' => $data->taxonomy,
					'field'    => 'term_id',
					'terms'    => $data->term_id,
				);
			}

			if ( isset( $data->taxonomy ) ) {
				$args['tax_query'][] = array(
					'taxonomy' => $data->taxonomy,
					'field'    => 'term_id',
					'terms'    => $data->term_id,
				);
			}

			if ( get_query_var( 'author' ) > 0 ) {
				$args['author__in'] = get_query_var( 'author' );
			}

			if ( get_query_var( 's' ) !== '' ) {
				$args['s'] = get_query_var( 's' );
			}

			if ( get_query_var( 'year' ) || get_query_var( 'monthnum' ) || get_query_var( 'day' ) ) {
				$args['date_query'] = array(
					'year'  => get_query_var( 'year' ),
					'month' => get_query_var( 'monthnum' ),
					'day'   => get_query_var( 'day' ),
				);
			}

			if ( ! empty( $args['tax_query'] ) ) {
				$args['tax_query']['relation'] = 'AND';
			}
		}

		return $args;
	}

	public static function get_terms_as_list( $term_type = 'category', $length = 1 ) {
		$terms = get_the_terms( get_the_ID(), $term_type );

		if ( 'category' === $term_type ) {
			$terms = get_the_category();
		}

		if ( 'tags' === $term_type ) {
			$terms = get_the_tags();
		}

		if ( empty( $terms ) ) {
			return;
		}

		$count = 0;

		$html = '<ul class="rael-post-carousel-categories">';
		foreach ( $terms as $term ) {
			if ( $count === absint( $length ) ) {
				break;
			}
			$link  = ( 'category' === $term_type ) ? get_category_link( $term->term_id ) : get_tag_link( $term->term_id );
			$html .= '<li>';
			$html .= '<a href="' . esc_url( $link ) . '">';
			$html .= $term->name;
			$html .= '</a>';
			$html .= '</li>';
			$count++;
		}
		$html .= '</ul>';

		return $html;

	}

	public static function include_with_variable( $file_path, $variables = array() ) {
		if ( file_exists( $file_path ) ) {
			extract( $variables );

			ob_start();

			include $file_path;

			return ob_get_clean();
		}

		return '';
	}
	/**
	 * Post Orderby Options
	 *
	 * @return array
	 */
	public static function get_post_orderby_options() {
		 $orderby = array(
			 'ID'            => 'Post ID',
			 'author'        => 'Post Author',
			 'title'         => 'Title',
			 'date'          => 'Date',
			 'modified'      => 'Last Modified Date',
			 'parent'        => 'Parent Id',
			 'rand'          => 'Random',
			 'comment_count' => 'Comment Count',
			 'menu_order'    => 'Menu Order',
		 );

		 return $orderby;
	}

	/**
	 * Get All POst Types
	 *
	 * @return array
	 */
	public static function get_post_types() {
		$post_types = get_post_types(
			array(
				'public'            => true,
				'show_in_nav_menus' => true,
			),
			'objects'
		);
		$post_types = wp_list_pluck( $post_types, 'label', 'name' );

		return array_diff_key( $post_types, array( 'elementor_library', 'rael-theme-template', 'attachment' ) );
	}

	public static function get_query_post_list( $post_type = 'any', $limit = -1, $search = '' ) {
		global $wpdb;
		$where = '';
		$data  = array();

		if ( -1 == $limit ) {
			$limit = '';
		} elseif ( 0 == $limit ) {
			$limit = 'limit 0,1';
		} else {
			$limit = $wpdb->prepare( ' limit 0,%d', esc_sql( $limit ) );
		}

		if ( 'any' === $post_type ) {
			$in_search_post_types = get_post_types( array( 'exclude_from_search' => false ) );
			if ( empty( $in_search_post_types ) ) {
				$where .= ' AND 1=0 ';
			} else {
				$where .= " AND {$wpdb->posts}.post_type IN ('" . join(
					"', '",
					array_map( 'esc_sql', $in_search_post_types )
				) . "')";
			}
		} elseif ( ! empty( $post_type ) ) {
			$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_type = %s", esc_sql( $post_type ) );
		}

		if ( ! empty( $search ) ) {
			$where .= $wpdb->prepare( " AND {$wpdb->posts}.post_title LIKE %s", '%' . esc_sql( $search ) . '%' );
		}

		$query   = "select post_title,ID  from $wpdb->posts where post_status = 'publish' $where $limit";
		$results = $wpdb->get_results( $query ); //phpcs:ignore
		if ( ! empty( $results ) ) {
			foreach ( $results as $row ) {
				$data[ $row->ID ] = $row->post_title;
			}
		}
		return $data;
	}

	/**
	 * Get all types of post.
	 *
	 * @param  string $post_type
	 *
	 * @return array
	 */
	public static function get_post_list( $post_type = 'any' ) {
		return self::get_query_post_list( $post_type );
	}

	/**
	 * Get all Authors
	 *
	 * @return array
	 */
	public static function get_authors_list() {
		$args = array(
			'capability'          => array( 'edit_posts' ),
			'has_published_posts' => true,
			'fields'              => array(
				'ID',
				'display_name',
			),
		);

		// Capability queries were only introduced in WP 5.9.
		if ( version_compare( $GLOBALS['wp_version'], '5.9-alpha', '<' ) ) {
			$args['who'] = 'authors';
			unset( $args['capability'] );
		}

		$users = get_users( $args );

		if ( ! empty( $users ) ) {
			return wp_list_pluck( $users, 'display_name', 'ID' );
		}

		return array();
	}

	/**
	 * This function is responsible for get the post data.
	 * It will return HTML markup with AJAX call and with normal call.
	 *
	 * @return string|array of an html markup with AJAX call of content and found posts count without AJAX call.
	 */
	public static function ajax_load_more() {
		$ajax = wp_doing_ajax();

		parse_str( $_POST['args'], $args );
		if ( empty( $_POST['nonce'] ) ) {
			$err_msg = __( 'Insecure form submitted without security token', 'responsive-addons-for-elementor' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			return false;
		}

		if ( ! wp_verify_nonce( $_POST['nonce'], 'rael_products_load_more' ) ) {
			$err_msg = __( 'Security token did not match', 'responsive-addons-for-elementor' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			return false;
		}

		if ( ! empty( $_POST['page_id'] ) ) {
			$page_id = intval( $_POST['page_id'], 10 );
		} else {
			$err_msg = __( 'Page ID is missing', 'responsive-addons-for-elementor' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			return false;
		}

		if ( ! empty( $_POST['widget_id'] ) ) {
			$widget_id = sanitize_text_field( $_POST['widget_id'] );
		} else {
			$err_msg = __( 'Widget ID is missing', 'responsive-addons-for-elementor' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			return false;
		}

		$settings = self::get_widget_settings( $page_id, $widget_id );

		if ( empty( $settings ) ) {
			wp_send_json_error( array( 'message' => __( 'Widget settings are not found. Did you save the widget before using load more?', 'responsive-addons-for-elementor' ) ) );
		}

		$settings['rael_widget_id'] = $widget_id;
		$settings['rael_page_id']   = $page_id;
		$html                       = '';
		$class                      = '\\' . str_replace( '\\\\', '\\', isset( $_REQUEST['class'] ) ? $_REQUEST['class'] : '' );
		$args['offset']             = (int) $args['offset'] + ( ( (int) $_REQUEST['page'] - 1 ) * (int) $args['posts_per_page'] );

		if ( isset( $_REQUEST['taxonomy'] ) && isset( $_REQUEST['taxonomy']['taxonomy'] ) && 'all' !== $_REQUEST['taxonomy']['taxonomy'] ) {
			$args['tax_query'] = array(
				$_REQUEST['taxonomy'],
			);
		}

		$link_settings = array(
			'image_link_nofollow'         => isset( $settings['image_link_nofollow'] ) ? 'rel="nofollow"' : '',
			'image_link_target_blank'     => isset( $settings['image_link_target_blank'] ) ? 'target="_blank"' : '',
			'title_link_nofollow'         => isset( $settings['title_link_nofollow'] ) ? 'rel="nofollow"' : '',
			'title_link_target_blank'     => isset( $settings['title_link_target_blank'] ) ? 'target="_blank"' : '',
			'read_more_link_nofollow'     => isset( $settings['read_more_link_nofollow'] ) ? 'rel="nofollow"' : '',
			'read_more_link_target_blank' => isset( $settings['read_more_link_target_blank'] ) ? 'target="_blank"' : '',
		);

		$template = self::sanitize_template_param( $_REQUEST['template'] );

		if ( $template ) {
			$dir_path = sprintf( '%sincludes', trailingslashit( RAEL_DIR ) );

			$file_path = realpath(
				sprintf(
					'%s/widgets-manager/widgets/skins/%s/%s',
					$dir_path,
					$template['name'],
					$template['file_name']
				)
			);

			if ( ! $file_path || 0 !== strpos( $file_path, $dir_path ) ) {
				wp_send_json_error( 'Invalid template', 'invalid_template', 400 );
			}

			if ( $file_path ) {
				$query = new \WP_Query( $args );

				$iterator = 0;

				if ( $query->have_posts() ) {
					if ( '\Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Woocommerce\RAEL_Products' === $class && boolval( $settings['rael_products_show_add_to_cart_custom_text'] ) ) {

						$add_to_cart_text = array(
							'add_to_cart_simple_product_button_text' => $settings['rael_products_add_to_cart_simple_product_button_text'],
							'add_to_cart_variable_product_button_text' => $settings['rael_products_add_to_cart_variable_product_button_text'],
							'add_to_cart_grouped_product_button_text' => $settings['rael_products_add_to_cart_grouped_product_button_text'],
							'add_to_cart_external_product_button_text' => $settings['rael_products_add_to_cart_external_product_button_text'],
							'add_to_cart_default_product_button_text' => $settings['rael_products_add_to_cart_default_product_button_text'],
						);
						self::change_add_to_cart_text( $add_to_cart_text );
					}

					while ( $query->have_posts() ) {
						$query->the_post();

						$html .= self::include_with_variable(
							$file_path,
							array(
								'settings'      => $settings,
								'link_settings' => $link_settings,
								'iterator'      => $iterator,
							)
						);
						$iterator++;
					}
				}
			}
		}

		

		while ( ob_get_level() ) {
			ob_end_clean();
		}

		header( 'Content-Type: application/json; charset=utf-8' );

		// Just output raw JSON — server will gzip if needed
		echo wp_json_encode( $html );

		wp_die();
	}

	public static function sanitize_template_param( $template ) {
		$template = array_map( 'sanitize_text_field', $template );
		return array_map( 'sanitize_file_name', $template );
	}

	public static function change_add_to_cart_text( $add_to_cart_text ) {
		add_filter(
			'woocommerce_product_add_to_cart_text',
			function ( $default ) use ( $add_to_cart_text ) {
				global $product;
				switch ( $product->get_type() ) {
					case 'external':
						return $add_to_cart_text['add_to_cart_external_product_button_text'];
					break;
					case 'grouped':
						return $add_to_cart_text['add_to_cart_grouped_product_button_text'];
					break;
					case 'simple':
						return $add_to_cart_text['add_to_cart_simple_product_button_text'];
					break;
					case 'variable':
						return $add_to_cart_text['add_to_cart_variable_product_button_text'];
					break;
					default:
						return $default;
				}
			}
		);
	}

	public static function get_sale_badge_html( $product, $settings, $sale_badge_text, $sale_badge_preset = '', $sale_badge_align = '' ) {
		if ( 'static' === $settings['rael_products_sale_type'] ) {
			return sprintf( '<span class="rael-products__onsale %s %s">%s</span>', $sale_badge_preset, $sale_badge_align, $sale_badge_text );
		}

		if ( ! $product->is_type( 'variable' ) && ! $product->is_type( 'grouped' ) ) {
			$currency_pos    = get_option( 'woocommerce_currency_pos', 'left' );
			$currency_symbol = get_woocommerce_currency_symbol();
			$sale_badge_text = $settings['rael_products_sale_dynamic_text'];

			if ( 'dynamic_price' === $settings['rael_products_sale_type'] ) {
				$price_off = $product->get_regular_price() - $product->get_sale_price();

				if ( 'left' === $currency_pos ) {
					$sale_badge_text = $currency_symbol . $price_off . ' ' . $sale_badge_text;
				} else {
					$sale_badge_text = $price_off . ' ' . $currency_symbol . ' ' . $sale_badge_text;
				}
			} elseif ( 'dynamic_percentage' === $settings['rael_products_sale_type'] ) {
				$price_percentage = round( 100 - ( $product->get_sale_price() / $product->get_regular_price() * 100 ), 1 ) . '% ';
				$sale_badge_text  = $price_percentage . $sale_badge_text;
			}
		}

		return sprintf( '<span class="rael-products__onsale %s %s">%s</span>', $sale_badge_preset, $sale_badge_align, $sale_badge_text );
	}

	public static function ajax_rael_products_pagination_product() {
		wp_parse_str( $_REQUEST['args'], $args );
		wp_parse_str( $_REQUEST['settings'], $settings );

		$pagination_number = absint( $_POST['number'] );
		$pagination_limit  = absint( $_POST['limit'] );

		$args['posts_per_page'] = $pagination_limit;

		if ( '1' === $pagination_number ) {
			$pagination_offset_value = '0';
		} else {
			$pagination_offset_value = ( $pagination_number - 1 ) * $pagination_limit;
			$args['offset']          = $pagination_offset_value;
		}

		$template = self::sanitize_template_param( $_REQUEST['template'] );

		$dir_path = sprintf( '%sincludes', trailingslashit( RAEL_DIR ) );

		if ( 'rael-woocommerce-products' === $template['name'] ) {
			$file_path = realpath(
				sprintf(
					'%s/widgets-manager/widgets/skins/%s/%s.php',
					$dir_path,
					'woo-products',
					$template['file_name']
				)
			);
		}

		ob_start();
		$query = new \WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				include $file_path;
			}
		}
		echo wp_kses_post( ob_get_clean() );
		wp_die();
	}

	public static function ajax_rael_woo_product_pagination() {
		wp_parse_str( $_REQUEST['args'], $args );
		wp_parse_str( $_REQUEST['settings'], $settings );

		$pagination_number = absint( $_POST['number'] );
		$pagination_limit  = absint( $_POST['limit'] );

		$pagination_args                   = $args;
		$pagination_args['posts_per_page'] = -1;

		$pagination_query = new \WP_Query( $pagination_args );
		$pagination_count = count( $pagination_query->posts );
		$pagination_list  = ceil( $pagination_count / $pagination_limit );
		$last             = ceil( $pagination_list );
		$pagination_prev  = $pagination_number - 1;
		$pagination_next  = $pagination_number + 1;

		if ( $pagination_number > 1 ) {
			$pagination_prev;
		}

		if ( $pagination_number < $last ) {
			$pagination_next;
		}

		$widget_id  = sanitize_text_field( $settings['rael_widget_id'] );
		$next_label = sanitize_text_field( $settings['rael_products_pagination_next_label'] );
		$prev_label = sanitize_text_field( $settings['rael_products_pagination_prev_label'] );

		$adjacents      = '2';
		$set_pagination = '';

		if ( $pagination_list > 0 ) {

			$set_pagination .= "<ul class='rael-products__page-numbers'>";
			if ( 1 < $pagination_number ) {
				$set_pagination .= "<li><a href='javascript:void(0);' id='post' class='page-numbers' data-template='" . wp_json_encode(
					array(
						'file_name' => $settings['rael_products_dynamic_template_layout'],
						'name'      => $settings['rael_widget_name'],
					),
					1
				) . "' data-widget-id='$widget_id' data-args='" . http_build_query( $args ) . "' data-settings='" . http_build_query( $settings ) . "' data-pnumber='$pagination_prev' data-plimit='$pagination_limit'>$prev_label</a></li>";
			}

			if ( $pagination_list < 7 + ( $adjacents * 2 ) ) {

				for ( $pagination = 1; $pagination <= $pagination_list; $pagination++ ) {
					if ( $pagination_number === $pagination ) {
						$active = 'current';
					} else {
						$active = '';
					}

					$set_pagination .= "<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-template='" . wp_json_encode(
						array(
							'file_name' => $settings['rael_products_dynamic_template_layout'],
							'name'      => $settings['rael_widget_name'],
						),
						1
					) . "' data-widget-id='$widget_id' data-args='" . http_build_query( $args ) . "' data-settings='" . http_build_query( $settings ) . "' data-pnumber='$pagination' data-plimit='$pagination_limit'>$pagination</a></li>";
				}
			} elseif ( $pagination_list > 5 + ( $adjacents * 2 ) ) {

				if ( $pagination_number < 1 + ( $adjacents * 2 ) ) {

					for ( $pagination = 1; $pagination <= 4 + ( $adjacents * 2 ); $pagination++ ) {

						if ( $pagination_number === $pagination ) {
							$active = 'current';
						} else {
							$active = '';
						}

						$set_pagination .= "<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-template='" . wp_json_encode(
							array(
								'file_name' => $settings['rael_products_dynamic_template_layout'],
								'name'      => $settings['rael_widget_name'],
							),
							1
						) . "' data-widget-id='$widget_id' data-args='" . http_build_query( $args ) . "' data-settings='" . http_build_query( $settings ) . "' data-pnumber='$pagination' data-plimit='$pagination_limit'>$pagination</a></li>";
					}

					$set_pagination .= "<li class='pagitext dots'>...</li>";
					$set_pagination .= "<li class='pagitext'><a href='javascript:void(0);' class='page-numbers' data-template='" . wp_json_encode(
						array(
							'file_name' => $settings['rael_products_dynamic_template_layout'],
							'name'      => $settings['rael_widget_name'],
						),
						1
					) . "' data-widget-id='$widget_id' data-args='" . http_build_query( $args ) . "' data-settings='" . http_build_query( $settings ) . "' data-pnumber='$last' data-plimit='$pagination_limit'>" . $last . '</a></li>';

				} elseif ( $pagination_list - ( $adjacents * 2 ) > $pagination_number && $pagination_number > ( $adjacents * 2 ) ) {
					$active = '';

					$set_pagination .= "<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-template='" . wp_json_encode(
						array(
							'file_name' => $settings['rael_products_dynamic_template_layout'],
							'name'      => $settings['rael_widget_name'],
						),
						1
					) . "' data-widget-id='$widget_id' data-args='" . http_build_query( $args ) . "' data-settings='" . http_build_query( $settings ) . "' data-pnumber='1' data-plimit='$pagination_limit'>1</a></li>";

					$set_pagination .= "<li class='pagitext dots'>...</li>";

					for ( $pagination = $pagination_number - $adjacents; $pagination <= $pagination_number + $adjacents; $pagination++ ) {

						if ( $pagination_number === $pagination ) {
							$active = 'current';
						} else {
							$active = '';
						}

						$set_pagination .= "<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-template='" . wp_json_encode(
							array(
								'file_name' => $settings['rael_products_dynamic_template_layout'],
								'name'      => $settings['rael_widget_name'],
							),
							1
						) . "' data-widget-id='$widget_id' data-args='" . http_build_query( $args ) . "' data-settings='" . http_build_query( $settings ) . "' data-pnumber='$pagination' data-plimit='$pagination_limit'>$pagination</a></li>";

					}

					$set_pagination .= "<li class='pagitext dots'>...</li>";
					$set_pagination .= "<li class='pagitext'><a href='javascript:void(0);' class='page-numbers' data-template='" . wp_json_encode(
						array(
							'file_name' => $settings['rael_products_dynamic_template_layout'],
							'name'      => $settings['rael_widget_name'],
						),
						1
					) . "' data-widget-id='$widget_id' data-args='" . http_build_query( $args ) . "' data-settings='" . http_build_query( $settings ) . "' data-pnumber='$last' data-plimit='$pagination_limit'>" . $last . '</a></li>';

				} else {
					$active          = '';
					$set_pagination .= "<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-template='" . wp_json_encode(
						array(
							'file_name' => $settings['rael_products_dynamic_template_layout'],
							'name'      => $settings['rael_widget_name'],
						),
						1
					) . "' data-pnumber='1' data-plimit='$pagination_limit'>1</a></li>";
					$set_pagination .= "<li class='pagitext dots'>...</li>";

					for ( $pagination = $last - ( 2 + ( $adjacents * 2 ) ); $pagination <= $last; $pagination++ ) {

						if ( $pagination_number === $pagination ) {
							$active = 'current';
						} else {
							$active = '';
						}

						$set_pagination .= "<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-template='" . wp_json_encode(
							array(
								'file_name' => $settings['rael_products_dynamic_template_layout'],
								'name'      => $settings['rael_widget_name'],
							),
							1
						) . "' data-widget-id='$widget_id' data-args='" . http_build_query( $args ) . "' data-settings='" . http_build_query( $settings ) . "' data-pnumber='$pagination' data-plimit='$pagination_limit'>$pagination</a></li>";

					}
				}
			} else {

				for ( $pagination = 1; $pagination <= $pagination_list; $pagination++ ) {
					if ( $pagination_number === $pagination ) {
						$active = 'current';
					} else {
						$active = '';
					}

					$set_pagination .= "<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-template='" . wp_json_encode(
						array(
							'file_name' => $settings['rael_products_dynamic_template_layout'],
							'name'      => $settings['rael_widget_name'],
						),
						1
					) . "' data-widget-id='$widget_id' data-args='" . http_build_query( $args ) . "' data-settings='" . http_build_query( $settings ) . "' data-pnumber='$pagination' data-plimit='$pagination_limit'>$pagination</a></li>";
				}
			}

			if ( $pagination_number < $pagination_list ) {
				$set_pagination .= "<li class='pagitext'><a href='javascript:void(0);' class='page-numbers' data-template='" . wp_json_encode(
					array(
						'file_name' => $settings['rael_products_dynamic_template_layout'],
						'name'      => $settings['rael_widget_name'],
					),
					1
				) . "' data-widget-id='$widget_id' data-args='"
								. http_build_query( $args ) . "' data-settings='" . http_build_query( $settings ) . "' data-pnumber='$pagination_next' data-plimit='$pagination_limit'>$next_label</a></li>";
			}
			$set_pagination .= '</ul>';
		}

		echo wp_kses_post( $set_pagination );
		wp_die();
	}

	public static function rael_ajax_select2_posts_filter_autocomplete() {
		$post_type   = 'post';
		$source_name = 'post_type';

		if ( ! empty( $_GET['post_type'] ) ) {
			$post_type = sanitize_text_field( $_GET['post_type'] );
		}

		if ( ! empty( $_GET['source_name'] ) ) {
			$source_name = sanitize_text_field( $_GET['source_name'] );
		}

		$search  = ! empty( $_GET['term'] ) ? sanitize_text_field( $_GET['term'] ) : '';
		$results = $post_list = array();
		switch ( $source_name ) {
			case 'taxonomy':
				$args = array(
					'hide_empty' => false,
					'orderby'    => 'name',
					'order'      => 'ASC',
					'search'     => $search,
					'number'     => '5',
				);

				if ( 'all' !== $post_type ) {
					$args['taxonomy'] = $post_type;
				}

				$post_list = wp_list_pluck( get_terms( $args ), 'name', 'term_id' );
				break;
			case 'user':
				$users = array();

				foreach ( get_users( array( 'search' => "*{$search}*" ) ) as $user ) {
					$user_id           = $user->ID;
					$user_name         = $user->display_name;
					$users[ $user_id ] = $user_name;
				}

				$post_list = $users;
				break;
			default:
				$post_list = self::get_query_post_list( $post_type, 10, $search );
		}

		if ( ! empty( $post_list ) ) {
			foreach ( $post_list as $key => $item ) {
				$results[] = array(
					'text' => $item,
					'id'   => $key,
				);
			}
		}
		wp_send_json( array( 'results' => $results ) );
	}

	public static function rael_ajax_select2_get_posts_value_titles() {

		if ( empty( $_POST['id'] ) ) {
			wp_send_json_error( array() );
		}

		if ( empty( array_filter( $_POST['id'] ) ) ) {
			wp_send_json_error( array() );
		}
		$ids         = array_map( 'intval', $_POST['id'] );
		$source_name = ! empty( $_POST['source_name'] ) ? sanitize_text_field( $_POST['source_name'] ) : '';

		switch ( $source_name ) {
			case 'taxonomy':
				$args = array(
					'hide_empty' => false,
					'orderby'    => 'name',
					'order'      => 'ASC',
					'include'    => implode( ',', $ids ),
				);

				if ( 'all' !== $_POST['post_type'] ) {
					$args['taxonomy'] = sanitize_text_field( $_POST['post_type'] );
				}

				$response = wp_list_pluck( get_terms( $args ), 'name', 'term_id' );
				break;
			case 'user':
				$users = array();

				foreach ( get_users( array( 'include' => $ids ) ) as $user ) {
					$user_id           = $user->ID;
					$user_name         = $user->display_name;
					$users[ $user_id ] = $user_name;
				}

				$response = $users;
				break;
			default:
				$post_info = get_posts(
					array(
						'post_type' => sanitize_text_field( $_POST['post_type'] ),
						'include'   => implode( ',', $ids ),
					)
				);
				$response  = wp_list_pluck( $post_info, 'post_title', 'ID' );
		}

		if ( ! empty( $response ) ) {
			wp_send_json_success( array( 'results' => $response ) );
		} else {
			wp_send_json_error( array() );
		}
	}
	/**
	 * [rael_pro_get_taxonomies]
	 *
	 * @return [array] product texonomies
	 */
	public static function rael_get_taxonomies( $object = 'product', $skip_terms = false ) {
		$all_taxonomies  = get_object_taxonomies( $object );
		$taxonomies_list = array();
		foreach ( $all_taxonomies as $taxonomy_data ) {
			$taxonomy = get_taxonomy( $taxonomy_data );
			if ( $skip_terms === true ) {
				if ( ( $taxonomy->show_ui ) && ( 'pa_' !== substr( $taxonomy_data, 0, 3 ) ) ) {
					$taxonomies_list[ $taxonomy_data ] = $taxonomy->label;
				}
			} else {
				if ( $taxonomy->show_ui ) {
					$taxonomies_list[ $taxonomy_data ] = $taxonomy->label;
				}
			}
		}
		return $taxonomies_list;
	}
	/**
	 * Function to get options
	 */
	public static function rael_get_option( $option, $section, $default = '' ) {
		$options = get_option( $section );
		if ( isset( $options[ $option ] ) ) {
			return $options[ $option ];
		}
		return $default;
	}

	/**
	 *
	 * Strip tag based on allowed html tag
	 * rael_wp_kses
	 *
	 * @param $text
	 * @return string
	 */
	public static function rael_wp_kses( $text ) {
		return wp_kses( $text, self::rael_allowed_tags() );
	}

	/**
	 * List of allowed html tag for wp_kses
	 *
	 * rael_allowed_tags
	 *
	 * @return array
	 */
	public static function rael_allowed_tags() {
		return array(
			'a'       => array(
				'href'  => array(),
				'title' => array(),
				'class' => array(),
				'rel'   => array(),
				'id'    => array(),
				'style' => array(),
			),
			'q'       => array(
				'cite'  => array(),
				'class' => array(),
				'id'    => array(),
			),
			'img'     => array(
				'src'    => array(),
				'alt'    => array(),
				'height' => array(),
				'width'  => array(),
				'class'  => array(),
				'id'     => array(),
				'style'  => array(),
			),
			'span'    => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'dfn'     => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'time'    => array(
				'datetime' => array(),
				'class'    => array(),
				'id'       => array(),
				'style'    => array(),
			),
			'cite'    => array(
				'title' => array(),
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'hr'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'b'       => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'p'       => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'i'       => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'u'       => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			's'       => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'br'      => array(),
			'em'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'code'    => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'mark'    => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'small'   => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'abbr'    => array(
				'title' => array(),
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'strong'  => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'del'     => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'ins'     => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'sub'     => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'sup'     => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'div'     => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'strike'  => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'acronym' => array(),
			'h1'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'h2'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'h3'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'h4'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'h5'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'h6'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'button'  => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
		);
	}


}
