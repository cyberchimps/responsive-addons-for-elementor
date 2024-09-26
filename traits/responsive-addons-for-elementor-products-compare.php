<?php
namespace Responsive_Addons_For_Elementor\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Responsive_Addons_For_Elementor\Helper\Helper;

/**
 * Product Compare trait.
 *
 * @since 1.6.0
 */
trait RAEL_Products_Comparable {
	public static function get_wc_attr_taxonomies_list() {
		$attributes_tax = wc_get_attribute_taxonomies();
		$data           = array();
		foreach ( $attributes_tax as $item ) {
			$data[ wc_attribute_taxonomy_name( $item->attribute_name ) ] = $item->attribute_label;
		}
		return $data;
	}

	public static function get_themes() {
		return apply_filters(
			'rael/woo_product_compare/default_themes',
			array(
				''        => __( 'Theme Default', 'responsive-addons-for-elementor' ),
				'theme-1' => __( 'Theme 1', 'responsive-addons-for-elementor' ),
				'theme-2' => __( 'Theme 2', 'responsive-addons-for-elementor' ),
				'theme-3' => __( 'Theme 3', 'responsive-addons-for-elementor' ),
				'theme-4' => __( 'Theme 4', 'responsive-addons-for-elementor' ),
				'theme-5' => __( 'Theme 5', 'responsive-addons-for-elementor' ),
				'theme-6' => __( 'Theme 6', 'responsive-addons-for-elementor' ),
			)
		);
	}

	public static function get_field_types() {
		$default_types = array(
			'image'       => __( 'Image', 'responsive-addons-for-elementor' ),
			'title'       => __( 'Title', 'responsive-addons-for-elementor' ),
			'price'       => __( 'Price', 'responsive-addons-for-elementor' ),
			'add-to-cart' => __( 'Add to cart', 'responsive-addons-for-elementor' ),
			'description' => __( 'Description', 'responsive-addons-for-elementor' ),
			'sku'         => __( 'SKU', 'responsive-addons-for-elementor' ),
			'stock'       => __( 'Availability', 'responsive-addons-for-elementor' ),
			'weight'      => __( 'weight', 'responsive-addons-for-elementor' ),
			'dimension'   => __( 'Dimension', 'responsive-addons-for-elementor' ),
		);

		return apply_filters( 'rael/woo_product_compare/default-fields', array_merge( $default_types, self::get_wc_attr_taxonomies_list() ) );
	}

	public static function get_default_repeater_fields() {
		return apply_filters(
			'rael/woo_product_compare/default-repeater-fields',
			array(
				array(
					'rael_products_field_type'  => 'image',
					'rael_products_field_label' => __( 'Image', 'responsive-addons-for-elementor' ),
				),
				array(
					'rael_products_field_type'  => 'title',
					'rael_products_field_label' => __( 'Title', 'responsive-addons-for-elementor' ),
				),
				array(
					'rael_products_field_type'  => 'price',
					'rael_products_field_label' => __( 'Price', 'responsive-addons-for-elementor' ),
				),
				array(
					'rael_products_field_type'  => 'description',
					'rael_products_field_label' => __( 'Description', 'responsive-addons-for-elementor' ),
				),
				array(
					'rael_products_field_type'  => 'add-to-cart',
					'rael_products_field_label' => __( 'Add to cart', 'responsive-addons-for-elementor' ),
				),
				array(
					'rael_products_field_type'  => 'sku',
					'rael_products_field_label' => __( 'SKU', 'responsive-addons-for-elementor' ),
				),
				array(
					'rael_products_field_type'  => 'stock',
					'rael_products_field_label' => __( 'Availability', 'responsive-addons-for-elementor' ),
				),
				array(
					'rael_products_field_type'  => 'weight',
					'rael_products_field_label' => __( 'Weight', 'responsive-addons-for-elementor' ),
				),
				array(
					'rael_products_field_type'  => 'dimension',
					'rael_products_field_label' => __( 'Dimension', 'responsive-addons-for-elementor' ),
				),
				array(
					'rael_products_field_type'  => 'pa_color',
					'rael_products_field_label' => __( 'Color', 'responsive-addons-for-elementor' ),
				),
				array(
					'rael_products_field_type'  => 'pa_size',
					'rael_products_field_label' => __( 'Size', 'responsive-addons-for-elementor' ),
				),
			)
		);
	}

	public static function print_compare_button( $id = false, $btn_type = 'text' ) {
		if ( empty( $id ) ) {
			global $product;
			if ( ! $product ) {
				return;
			}
			$id = $product->get_id();
		}

		$loader      = '<svg class="rael-wc-compare-loader" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style=" shape-rendering: auto; width: 14px;" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <g transform="translate(50,50)">
              <g transform="scale(0.7)">
              <circle cx="0" cy="0" r="50" fill="#c1c1c1"></circle>
              <circle cx="0" cy="-28" r="15" fill="#ffffff">
                <animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 0 0;360 0 0"></animateTransform>
              </circle>
              </g>
            </g>
            </svg>';
		$fa_icon     = '<i class="fas fa-exchange-alt"></i>';
		$btn_content = '<span class="rael-wc-compare-text">' . __( 'Compare', 'responsive-addons-for-elementor' ) . '</span>';
		if ( 'icon' === $btn_type ) {
			printf( '<a class="rael-wc-compare rael-wc-compare-icon" data-product-id="%1$d" rel="nofollow" title="Compare">%2$s %3$s</a>', intval( $id ), wp_kses_post( $loader ), wp_kses_post( $fa_icon ) );
		} else {
			printf( '<button class="rael-wc-compare rael-wc-compare-button" data-product-id="%1$d" rel="nofollow" title="Compare">%2$s %3$s</button>', intval( $id ), wp_kses_post( $loader ), wp_kses_post( $btn_content ) );
		}
	}

	/**
	 * It renders product compare table and it accepts an argument with 3 keys, products, fields and ds. Explanation is given below.
	 *
	 * @param array $options  {
	 *
	 * @var array   $products list of WC_product object
	 * @var array   $fields   list of WC_Product feature fields
	 * @var array   $ds       Widget's display settings array
	 * }
	 */
	public static function render_compare_table( $options ) {
		$products = $fields = $ds = array();
		extract( $options );
		$not_found_text         = isset( $ds['rael_products_no_products_found_text'] ) ? $ds['rael_products_no_products_found_text'] : '';
		$title                  = isset( $ds['rael_products_table_title'] ) ? $ds['rael_products_table_title'] : '';
		$title_tag              = isset( $ds['rael_products_table_title_tag'] ) ? Helper::validate_html_tags( $ds['rael_products_table_title_tag'] ) : 'h1';
		$ribbon                 = isset( $ds['rael_products_compare_ribbon'] ) ? Helper::strip_tags_keeping_allowed_tags( $ds['rael_products__compare_ribbon'] ) : '';
		$repeat_price           = isset( $ds['rael_products_repeat_price'] ) ? $ds['rael_products_repeat_price'] : '';
		$repeat_add_to_cart     = isset( $ds['rael_products_repeat_add_to_cart'] ) ? $ds['rael_products_repeat_add_to_cart'] : '';
		$linkable_img           = isset( $ds['rael_products_linkable_image'] ) ? $ds['rael_products_linkable_image'] : '';
		$highlighted_product_id = ! empty( $ds['highlighted_product_id'] ) ? intval( $ds['highlighted_product_id'] ) : null;
		$icon                   = ! empty( $ds['rael_products_field_icon'] ) && ! empty( $ds['rael_products_field_icon']['value'] ) ? $ds['rael_products_field_icon'] : array();
		$theme_wrap_class       = $theme = '';

		if ( ! empty( $ds['rael_product_compare_theme'] ) ) {
			$theme            = esc_attr( $ds['rael_product_compare_theme'] );
			$theme_wrap_class = " custom {$theme}";
		}
		do_action( 'rael/products_compare/before_content_wrapper' ); ?>
		<div class="rael-products-compare-wrapper woocommerce <?php echo esc_attr( $theme_wrap_class ); ?>">
			<?php do_action( 'rael/products_compare/before_main_table' ); ?>
			<table class="rael-products-compare-table table-responsive">
				<tbody>
				<?php if ( empty( $products ) ) { ?>
					<tr class="no-products">
						<td><?php echo esc_html( $not_found_text ); ?></td>
					</tr>
					<?php
				} else {

					// for product grid, show remove button
					if ( 'Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Woocommerce\Responsive_Addons_For_Elementor_Woo_Products' !== self::class ) {
						echo '<tr class="remove-row"><th class="remove-th">&nbsp;</th>';
						$rm_index = 0;
						foreach ( $products as $product_id => $product ) {
							?>
							<td class="rm-col<?php echo esc_attr( $rm_index ); ?>">
								<i class="fas fa-trash rael-wc-remove" data-product-id="<?php echo esc_attr( $product_id ); ?>" title="<?php esc_attr_e( 'Remove', 'responsive-addons-for-elementor' ); ?>"></i>
							</td>
							<?php
							$rm_index ++;
						}
						echo '</tr>';
					}

					$count = 1;
					foreach ( $fields as $field => $name ) {
						$f_heading_class = 1 === $count ? 'first-th' : '';
						$count ++;
						?>
						<tr class="<?php echo esc_attr( $field ); ?>">
							<th class="thead <?php echo esc_attr( $f_heading_class ); ?>">
								<div class="rael-products-compare__table-header">
									<?php
									if ( 'image' === $field ) {
										if ( ! empty( $title ) ) {
											printf( wp_kses_post( "<{$title_tag} class='rael-products-compare__title'>%s</{$title_tag}>" ), wp_kses_post( Helper::strip_tags_keeping_allowed_tags( $title ) ) );
										}
									} else {
										if ( 'theme-5' === $theme && $field === 'title' ) {
											echo '&nbsp;';
										} else {
											if ( ! empty( $icon ) ) {
												self::print_icon( $icon );
											}
											printf( '<span class="field-name">%s</span>', wp_kses_post( Helper::strip_tags_keeping_allowed_tags( $name ) ) );

										}
									}
									?>
								</div>
							</th>

							<?php
							$index = 0;
							/**
							 * @var int        $product_id
							 * @var WC_Product $product
							 */
							foreach ( $products as $product_id => $product ) {
								$is_highlighted = $product_id === $highlighted_product_id;
								$highlighted    = $is_highlighted ? 'featured' : '';
								$product_class  = ( $index % 2 == 0 ? 'odd' : 'even' ) . " col_{$index} product_{$product_id} $highlighted"
								?>
								<td class="<?php echo esc_attr( $product_class ); ?>">
									<span>
									<?php
									if ( $field === 'image' ) {
										echo '<span class="img-inner">';
										if ( 'theme-4' === $theme && $is_highlighted && $ribbon ) {
											printf( '<span class="ribbon">%s</span>', esc_html( $ribbon ) );
										}

										if ( 'yes' === $linkable_img ) {
											printf( "<a href='%s'>", esc_url( $product->get_permalink() ) );
										}
									}

									echo ! empty( $product->fields[ $field ] ) ? esc_attr( $product->fields[ $field ] ) : '&nbsp;';

									if ( 'image' === $field ) {
										if ( 'yes' === $linkable_img ) {
											echo '</a>';
										}
										if ( 'theme-4' === $theme ) {
											echo ! empty( $product->fields['title'] ) ? sprintf( "<p class='rael-products_product-title'>%s</p>", esc_html( $product->fields['title'] ) ) : '&nbsp;';
											echo ! empty( $product->fields['price'] ) ? wp_kses_post( $product->fields['price'] ) : '&nbsp;';
										}
										echo '</span>';
									}
									?>
									</span>
								</td>

								<?php
								++ $index;
							}
							?>

						</tr>

					<?php } ?>

					<?php if ( 'yes' === $repeat_price && isset( $fields['price'] ) ) : ?>
						<tr class="rael-products-compare__price repeated">
							<th class="thead">
								<div class="rael-products-compare__table-header">
									<?php
									if ( ! empty( $icon ) ) {
										self::print_icon( $icon );
									}
									printf( '<span class="field-name">%s</span>', esc_html( $fields['price'] ) );

									?>
								</div>
							</th>

							<?php
							$index = 0;
							foreach ( $products as $product_id => $product ) :
								$highlighted   = $product_id === $highlighted_product_id ? 'featured' : '';
								$product_class = ( $index % 2 == 0 ? 'odd' : 'even' ) . " col_{$index} product_{$product_id} $highlighted"
								?>
								<td class="<?php echo esc_attr( $product_class ); ?>"><?php echo wp_kses_post( $product->fields['price'] ); ?></td>
								<?php
								++ $index;
							endforeach;
							?>

						</tr>
					<?php endif; ?>

					<?php if ( 'yes' === $repeat_add_to_cart && isset( $fields['add-to-cart'] ) ) : ?>
						<tr class="rael-products-compare__add-to-cart repeated">
							<th class="thead">
								<div class="rael-products-compare__table-header">
									<?php
									if ( ! empty( $icon ) ) {
										self::print_icon( $icon );
									}
									printf( '<span class="field-name">%s</span>', esc_html( $fields['add-to-cart'] ) );
									?>
								</div>
							</th>

							<?php
							$index = 0;
							foreach ( $products as $product_id => $product ) :
								$highlighted   = $product_id === $highlighted_product_id ? 'featured' : '';
								$product_class = ( $index % 2 == 0 ? 'odd' : 'even' ) . " col_{$index} product_{$product_id} $highlighted"
								?>
								<td class="<?php echo esc_attr( $product_class ); ?>">
									<?php woocommerce_template_loop_add_to_cart(); ?>
								</td>
								<?php
								++ $index;
							endforeach;
							?>

						</tr>
					<?php endif; ?>

				<?php } ?>
				</tbody>
			</table>
			<?php do_action( 'rael/products_compare/after_main_table' ); ?>
		</div>
		<?php
		do_action( 'rael/products_compare/after_content_wrapper' );
	}

	public static function get_compare_table() {
		$ajax      = wp_doing_ajax();
		$page_id   = 0;
		$widget_id = 0;

		if ( ! empty( $_POST['page_id'] ) ) {
			$page_id = intval( $_POST['page_id'], 10 );
		} else {
			$err_msg = __( 'Page ID is missing', 'responsive-addons-for-elementor' );
		}
		if ( ! empty( $_POST['widget_id'] ) ) {
			$widget_id = sanitize_text_field( $_POST['widget_id'] );
		} else {
			$err_msg = __( 'Widget ID is missing', 'responsive-addons-for-elementor' );
		}
		if ( ! empty( $_POST['product_id'] ) ) {
			$product_id = sanitize_text_field( $_POST['product_id'] );
		} else {
			$err_msg = __( 'Product ID is missing', 'responsive-addons-for-elementor' );
		}

		if ( ! empty( $_POST['product_ids'] ) ) {
			$product_ids = wp_unslash( json_decode( $_POST['product_ids'] ) );
		}

		if ( empty( $product_ids ) ) {
			$product_ids = array();
		}

		if ( ! empty( $product_id ) ) {
			$p_exist = ! empty( $product_ids ) && is_array( $product_ids );
			if ( ! empty( $_POST['remove_product'] ) && $p_exist ) {
				$product_ids = array_filter(
					$product_ids,
					function ( $id ) use ( $product_id ) {
						return $id != $product_id;
					}
				);
			} else {
				$product_ids[] = $product_id;
			}
		}

		if ( ! empty( $err_msg ) ) {
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}

			return false;
		}
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'rael_products' ) ) {
			if ( $ajax ) {
				wp_send_json_error( __( 'Security token did not match', 'responsive-addons-for-elementor' ) );
			}

			return false;
		}
		$product_ids = array_values( array_unique( $product_ids ) );

		$ds       = Helper::get_widget_settings( $page_id, $widget_id );
		$products = self::static_get_products_list( $product_ids, $ds );
		$fields   = self::static_fields( $product_ids, $ds );
		ob_start();
		self::render_compare_table( compact( 'products', 'fields', 'ds' ) );
		$table = ob_get_clean();
		wp_send_json_success(
			array(
				'compare_table' => $table,
				'product_ids'   => $product_ids,
			)
		);

		return null;
	}

	/**
	 * Return the array with all products and all attributes values
	 *
	 * @param array $products ids of wc product
	 *
	 * @return array The complete list of products with all attributes value
	 */
	public function get_products_list( $products = array() ) {
		$products_list = array();
		if ( empty( $products ) ) {
			$products = $this->products_list;
		}

		$products = apply_filters( 'rael/products_compare/products_ids', $products );
		$fields   = $this->fields( $products );
		global $product;
		if ( ! empty( $products ) && is_array( $products ) ) {
			foreach ( $products as $product_id ) {
				/** @type WC_Product $product WooCommerce Product */
				$product = wc_get_product( $product_id );
				if ( ! $product ) {
					continue;
				}

				$product->fields = array();

				// custom attributes
				foreach ( $fields as $field => $name ) {
					switch ( $field ) {
						case 'title':
							$product->fields[ $field ] = $product->get_title();
							break;
						case 'price':
							$product->fields[ $field ] = $product->get_price_html();
							break;
						case 'add-to-cart':
							ob_start();
							woocommerce_template_loop_add_to_cart();
							$product->fields[ $field ] = ob_get_clean();
							break;
						case 'image':
							$product->fields[ $field ] = $product->get_image();
							break;
						case 'description':
							$description               = apply_filters( 'woocommerce_short_description', $product->get_short_description() ? $product->get_short_description() : wc_trim_string( $product->get_description(), 400 ) );
							$product->fields[ $field ] = apply_filters( 'rael/products_compare/woocommerce_short_description', $description );
							break;
						case 'stock':
							$availability = $product->get_availability();
							if ( empty( $availability['availability'] ) ) {
								$availability['availability'] = __( 'In stock', 'responsive-addons-for-elementor' );
							}
							$product->fields[ $field ] = sprintf( '<span>%s</span>', esc_html( $availability['availability'] ) );
							break;
						case 'sku':
							$sku                       = $product->get_sku();
							! $sku && $sku             = '-';
							$product->fields[ $field ] = $sku;
							break;
						case 'weight':
							if ( $weight = $product->get_weight() ) {
								$weight = wc_format_localized_decimal( $weight ) . ' ' . esc_attr( get_option( 'woocommerce_weight_unit' ) );
							} else {
								$weight = '-';
							}
							$product->fields[ $field ] = sprintf( '<span>%s</span>', esc_html( $weight ) );
							break;
						case 'dimension':
							$dimensions                  = function_exists( 'wc_format_dimensions' ) ? wc_format_dimensions( $product->get_dimensions( false ) ) : $product->get_dimensions();
							! $dimensions && $dimensions = '-';
							$product->fields[ $field ]   = sprintf( '<span>%s</span>', esc_html( $dimensions ) );
							break;
						default:
							if ( taxonomy_exists( $field ) ) {
								$product->fields[ $field ] = array();
								$terms                     = get_the_terms( $product_id, $field );
								if ( ! empty( $terms ) && is_array( $terms ) ) {
									foreach ( $terms as $term ) {
										$term                        = sanitize_term( $term, $field );
										$product->fields[ $field ][] = $term->name;
									}
								}
								if ( ! empty( $product->fields[ $field ] ) ) {
									$product->fields[ $field ] = implode( ', ', $product->fields[ $field ] );
								} else {
									$product->fields[ $field ] = '-';
								}
							} else {
								do_action(
									'rael/products_compare/compare_field_' . $field,
									array(
										$product,
										&$product->fields,
									)
								);
							}
							break;
					}
				}

				$products_list[ $product_id ] = $product;
			}
		}

		return apply_filters( 'rael/products_compare/products_list', $products_list );
	}

	/**
	 * Return the array with all products and all attributes values
	 *
	 * @param array $products ids of wc product
	 * @param array $settings
	 *
	 * @return array The complete list of products with all attributes value
	 */
	public static function static_get_products_list( $products = array(), $settings = array() ) {
		$products_list = array();

		$products = apply_filters( 'rael/products_compare/products_ids', $products );
		$fields   = self::static_fields( $products, $settings );

		global $product;
		if ( ! empty( $products ) && is_array( $products ) ) {
			foreach ( $products as $product_id ) {
				/** @type WC_Product $product WooCommerce Product */
				$product = wc_get_product( $product_id );
				if ( ! $product ) {
					continue;
				}

				$product->fields = array();

				// custom attributes
				foreach ( $fields as $field => $name ) {
					switch ( $field ) {
						case 'title':
							$product->fields[ $field ] = $product->get_title();
							break;
						case 'price':
							$product->fields[ $field ] = $product->get_price_html();
							break;
						case 'add-to-cart':
							ob_start();
							woocommerce_template_loop_add_to_cart();
							$product->fields[ $field ] = ob_get_clean();
							break;
						case 'image':
							$product->fields[ $field ] = $product->get_image();
							break;
						case 'description':
							$description               = apply_filters( 'woocommerce_short_description', $product->get_short_description() ? $product->get_short_description() : wc_trim_string( $product->get_description(), 400 ) );
							$product->fields[ $field ] = apply_filters( 'rael/products_compare/woocommerce_short_description', $description );
							break;
						case 'stock':
							$availability = $product->get_availability();
							if ( empty( $availability['availability'] ) ) {
								$availability['availability'] = __( 'In stock', 'responsive-addons-for-elementor' );
							}
							$product->fields[ $field ] = sprintf( '<span>%s</span>', esc_html( $availability['availability'] ) );
							break;
						case 'sku':
							$sku                       = $product->get_sku();
							! $sku && $sku             = '-';
							$product->fields[ $field ] = $sku;
							break;
						case 'weight':
							if ( $weight = $product->get_weight() ) {
								$weight = wc_format_localized_decimal( $weight ) . ' ' . esc_attr( get_option( 'woocommerce_weight_unit' ) );
							} else {
								$weight = '-';
							}
							$product->fields[ $field ] = sprintf( '<span>%s</span>', esc_html( $weight ) );
							break;
						case 'dimension':
							$dimensions = function_exists( 'wc_format_dimensions' ) ? wc_format_dimensions( $product->get_dimensions( false ) ) : $product->get_dimensions();
							if ( empty( $dimensions ) ) {
								$dimensions = '-';
							}
							$product->fields[ $field ] = sprintf( '<span>%s</span>', esc_html( $dimensions ) );
							break;
						default:
							if ( taxonomy_exists( $field ) ) {
								$product->fields[ $field ] = array();
								$terms                     = get_the_terms( $product_id, $field );
								if ( ! empty( $terms ) && is_array( $terms ) ) {
									foreach ( $terms as $term ) {
										$term                        = sanitize_term( $term, $field );
										$product->fields[ $field ][] = $term->name;
									}
								}

								if ( ! empty( $product->fields[ $field ] ) ) {
									$product->fields[ $field ] = implode( ', ', $product->fields[ $field ] );
								} else {
									$product->fields[ $field ] = '-';
								}
							} else {
								do_action(
									'rael/products_compare/compare_field_' . $field,
									array(
										$product,
										&$product->fields,
									)
								);
							}
							break;
					}
				}

				$products_list[ $product_id ] = $product;
			}
		}

		return apply_filters( 'rael/products_compare/products_list', $products_list );
	}

	/**
	 * Get the fields to show in the comparison table
	 *
	 * @param array $products Optional array of products ids
	 * @param array $settings
	 *
	 * @return array $fields it returns an array of fields to show on the comparison table
	 */
	public static function static_fields( $products = array(), $settings = array() ) {
		if ( empty( $settings['rael_products_fields'] ) || ! is_array( $settings['rael_products_fields'] ) ) {
			return apply_filters( 'rael/products_compare/products_fields_none', array() );
		}
		$fields         = $settings['rael_products_fields'];
		$df             = self::get_field_types();
		$fields_to_show = array();

		foreach ( $fields as $field ) {
			if ( isset( $df[ $field['rael_products_field_type'] ] ) ) {
				$fields_to_show[ $field['rael_products_field_type'] ] = Helper::strip_tags_keeping_allowed_tags( $field['rael_products_field_label'] );
			} else {
				if ( taxonomy_exists( $field['rael_products_field_type'] ) ) {
					$fields_to_show[ $field['rael_products_field_type'] ] = wc_attribute_label( $field['rael_products_field_type'] );
				}
			}
		}

		return apply_filters( 'rael/products_compare/products_fields_to_show', $fields_to_show, $products );
	}

	public function register_content_tab_product_compare_section() {
		$section_args = array(
			'label' => __( 'Product Compare', 'responsive-addons-for-elementor' ),
		);

		if ( 'rael-product-compare' !== $this->get_name() ) {
			$section_args['condition'] = array(
				'rael_products_show_compare' => array( 'yes', 'true', '1' ),
			);
		}

		$this->start_controls_section( 'rael_products_content_tab_product_compare_section', $section_args );

		if ( 'rael-product-compare' === $this->get_name() ) {
			// Controls to be added if the current widget is Product Compare widget.
		}

		$this->add_control(
			'rael_product_compare_theme',
			array(
				'label'   => __( 'Presets', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_themes(),
				'default' => '',
			)
		);

		$this->add_control(
			'rael_product_compare_ribbon',
			array(
				'label'       => __( 'Ribbon Text', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'eg. New', 'responsive-addons-for-elementor' ),
				'default'     => __( 'New', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_product_compare_theme' => 'theme-4',
				),
			)
		);

		$this->end_controls_section();
	}

	public function register_content_tab_compare_table_settings_section() {
		$this->start_controls_section(
			'rael_products_content_tab_compare_table_settings_section',
			array(
				'label'     => __( 'Compare Table Settings', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'rael_products_show_compare' => array( 'yes', 'true', '1' ),
				),
			)
		);

		$this->add_control(
			'rael_products_table_title',
			array(
				'label'       => __( 'Table Title', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Compare Products', 'responsive-addons-for-elementor' ),
				'placeholder' => __( 'Compare Products', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_products_table_title_tag',
			array(
				'label'   => __( 'Table Title HTML Tag', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1'   => __( 'H1', 'responsive-addons-for-elementor' ),
					'h2'   => __( 'H2', 'responsive-addons-for-elementor' ),
					'h3'   => __( 'H3', 'responsive-addons-for-elementor' ),
					'h4'   => __( 'H4', 'responsive-addons-for-elementor' ),
					'h5'   => __( 'H5', 'responsive-addons-for-elementor' ),
					'h6'   => __( 'H6', 'responsive-addons-for-elementor' ),
					'div'  => __( 'div', 'responsive-addons-for-elementor' ),
					'span' => __( 'span', 'responsive-addons-for-elementor' ),
					'p'    => __( 'p', 'responsive-addons-for-elementor' ),
				),
				'default' => 'h1',
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'rael_products_field_type',
			array(
				'label'   => __( 'Type', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_field_types(),
				'default' => 'title',
			)
		);

		$repeater->add_control(
			'rael_products_field_label',
			array(
				'label'   => __( 'Label', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'rael_products_fields',
			array(
				'label'       => __( 'Fields to show', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'default'     => __( 'Select the fields to show in the comparison table', 'responsive-addons-for-elementor' ),
				'fields'      => $repeater->get_controls(),
				'default'     => $this->get_default_repeater_fields(),
				'title_field' => '{{ rael_products_field_label }}',
			)
		);

		$this->add_control(
			'rael_products_repeat_price',
			array(
				'label'        => __( 'Repeat "Price" field', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => __( 'Repeat the "Price" field at the end of the table', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'rael_products_repeat_add_to_cart',
			array(
				'label'        => __( 'Repeat "Add To Cart" field', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => __( 'Repeat the "Add to cart" field at the end of the table', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'rael_products_linkable_image',
			array(
				'label'        => __( 'Make Product Image Linkable', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => __( 'You can link the product image to product details page', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'rael_products_field_icon',
			array(
				'label' => __( 'Fields Icon', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::ICONS,
			)
		);

		$this->add_control(
			'rael_products_no_products_found_text',
			array(
				'label'       => __( 'Text for "No products are found to compare"', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'No products are added to Compare. Please add products to compare.', 'responsive-addons-for-elementor' ),
				'label_block' => true,
				'placeholder' => __( 'Eg. No products are added to Compare.', 'responsive-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();
	}

	public function register_style_tab_compare_button_section( $condition = null ) {
		$section_args = array(
			'label' => __( 'Compare Button', 'responsive-addons-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		);
		if ( is_array( $condition ) ) {
			$section_args['condition'] = $condition;
		}

		$this->start_controls_section( 'rael_woo_style_tab_compare_button_section', $section_args );

		$this->add_control(
			'rael_woo_compare_button_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-products .woocommerce li.product .rael-wc-compare-button.rael-wc-compare,
                    {{WRAPPER}} .rael-products.rael-product-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-products__link,
                    {{WRAPPER}} .rael-products.rael-product-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-wc-compare' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_woo_compare_button_radius',
			array(
				'label'      => __( 'Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .rael-products .woocommerce li.product .rael-wc-compare-button.rael-wc-compare,
                    {{WRAPPER}} .rael-products.rael-product-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-products__link,
                    {{WRAPPER}} .rael-products.rael-product-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-wc-compare' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'rael_woo_compare_button_style_tabs' );

		$this->start_controls_tab(
			'rael_woo_compare_button_style_tab_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_woo_compare_button_color',
			array(
				'label'     => __( 'Button Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-products .woocommerce li.product .rael-wc-compare-button.rael-wc-compare' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-products.rael-product-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-products__link' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-products.rael-product-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-products__link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_woo_compare_button_gradient_background',
				'label'    => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-products .woocommerce li.product .rael-wc-compare-button.rael-wc-compare,
				{{WRAPPER}} .rael-products.rael-product-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-products__link,
				{{WRAPPER}} .rael-products.rael-product-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-wc-compare',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_woo_compare_button_border',
				'selector' => '{{WRAPPER}} .rael-products .woocommerce li.product .rael-wc-compare-button.rael-wc-compare, {{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-products__link, {{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-wc-compare',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_woo_compare_button_typography',
				'selector'  => '{{WRAPPER}} .rael-products .woocommerce li.product .rael-wc-compare-button.rael-wc-compare',
				'condition' => array(
					'rael_products_style_preset' => array( 'rael_product_default', 'rael_product_simple' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_woo_compare_button_hover_tab',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_woo_compare_button_hover_color',
			array(
				'label'     => __( 'Button Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .rael-products .woocommerce li.product .rael-wc-compare-button.rael-wc-compare:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-products__link:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-wc-compare:hover' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_woo_compare_button_hover_gradient_background',
				'label'    => __( 'Background', 'responsive-addons-for-elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .rael-products .woocommerce li.product .rael-wc-comapre-button.rael-wc-compare:hover,
                {{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-products__link:hover,
                {{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-wc-compare:hover',
			)
		);

		$this->add_control(
			'rael_woo_compare_button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .rael-products .woocommerce li.product .rael-wc-compare-button.rael-wc-compare:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-products__link:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .rael-products.rael-products-overlay .woocommerce ul.products li.product .rael-products__overlay .rael-wc-compare:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function register_style_tab_compare_button_general_section( $css_classes = array() ) {
		extract( $css_classes );

		$this->start_controls_section(
			'rael_style_tab_compare_table_general_section',
			array(
				'label'     => __( 'Compare Table General', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_products_show_compare' => array( 'yes', 'true', '1' ),
				),
			)
		);

		$container_class = ! empty( $container_class ) ? $container_class : '{{WRAPPER}} .rael-products-compare-modal';

		$this->add_responsive_control(
			'rael_product_compare_container_width',
			array(
				'label'      => __( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'rem',
					'%',
				),
				'range'      => array(
					'px'  => array(
						'min'  => 0,
						'max'  => 1920,
						'step' => 5,
					),
					'rem' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => .5,
					),
					'%'   => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'desktop'    => array(
					'unit' => '%',
					'size' => 100,
				),
				'selectors'  => array(
					$container_class => 'width: {{SIZE}}{{UNIT}}; overflow-x:scroll',
				),
			)
		);

		$this->add_responsive_control(
			'rael_product_compare_container_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					$container_class => Helper::dimensions_css( 'margin' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_product_compare_container_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					$container_class => Helper::dimensions_css( 'padding' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_product_compare_container_border',
				'selector' => $container_class,
			)
		);

		$this->add_control(
			'rael_product_compare_container_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
				),
				'selectors'  => array(
					$container_class => Helper::dimensions_css( 'border-radius' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_product_compare_container_bg_color',
				'label'    => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'types'    => array(
					'classic',
					'gradient',
				),
				'selector' => $container_class,
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'label'    => __( 'Container Box Shadow', 'responsive-addons-for-elementor' ),
				'name'     => 'rael_product_compare_container_shadow',
				'selector' => $container_class,
				'exclude'  => array(
					'box_shadow_position',
				),
			)
		);
		$this->end_controls_section();
	}

	public function register_style_tab_table_style_section( $css_classes = array() ) {
		extract( $css_classes );
		$table            = isset( $table ) ? $table : '{{WRAPPER}} .rael-products-compare-wrapper table';
		$table_title      = isset( $table_title ) ? $table_title : '{{WRAPPER}} .rael-products-compare-wrapper .rael-products-compare__modal-title';
		$table_title_wrap = isset( $table_title_wrap ) ? $table_title_wrap : '{{WRAPPER}} .rael-products-compare-wrapper .first-th';

		$this->start_controls_section(
			'rael_style_tab_table_style_section',
			array(
				'label'     => __( 'Table Style', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_products_show_compare' => 'yes',
				),
			)
		);
		$this->add_control(
			'rael_product_compare_separate_col_style',
			array(
				'label' => __( 'Style Content Column Separately', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		// -------Table Style--------
		$this->add_control(
			'rael_product_compare_table_style',
			array(
				'label'        => __( 'Table Style', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Custom', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->start_popover();

		$this->add_responsive_control(
			'table_width',
			array(
				'label'      => __( 'Table Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'rem',
					'%',
				),
				'range'      => array(
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'px' => array(
						'min' => 0,
						'max' => 2000,
					),
				),
				'desktop'    => array(
					'unit' => '%',
					'size' => 100,
				),
				'selectors'  => array(
					$table => 'width: {{SIZE}}{{UNIT}}; max-width: none',
				),
				'condition'  => array(
					'rael_product_compare_table_style' => 'yes',
				),
			)
		);
		$this->add_responsive_control(
			'rael_product_compare_table_margin',
			array(
				'label'      => __( 'Table Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					$table => Helper::dimensions_css( 'margin' ),
				),
				'condition'  => array(
					'rael_product_compare_table_style' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'rael_product_compare_table_bg_color',
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'types'     => array(
					'classic',
					'gradient',
				),
				'exclude'   => array( 'image' ),
				'selector'  => $table,
				'condition' => array(
					'rael_product_compare_table_style' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_product_compare_table_border_heading',
			array(
				'label'     => __( 'Table Border', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_product_compare_table_style' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'rael_product_compare_table_border',
				'selector'  => $table,
				'condition' => array(
					'rael_product_compare_table_style' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_product_compare_table_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
				),
				'selectors'  => array(
					$table => Helper::dimensions_css( 'border-radius' ) . 'border-collapse:initial; overflow:hidden;',
				),
				'condition'  => array(
					'rael_product_compare_table_style' => 'yes',
					'rael_product_compare_table_border_border!' => '',
				),
			)
		);
		$this->end_popover();

		// Table Title Style.
		$this->add_control(
			'rael_product_compare_table_title_style',
			array(
				'label'        => __( 'Table Title Style', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Custom', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'condition'    => array(
					'rael_product_compare_table_title!' => '',
				),
			)
		);

		$this->start_popover();

		$this->add_control(
			'rael_product_compare_table_title_color',
			array(
				'label'     => __( 'Table Title Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					$table_title => 'color:{{VALUE}}',
				),
				'condition' => array(
					'rael_product_compare_table_title_style' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_product_compare_table_title_bg',
			array(
				'label'     => __( 'Table Title Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $table_title_wrap => 'background-color:{{VALUE}}' ),
				'condition' => array( 'rael_product_compare_table_title_style' => 'yes' ),
			)
		);
		$this->add_responsive_control(
			'rael_product_compare_table_title_padding',
			array(
				'label'      => __( 'Table Title Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					$table_title => Helper::dimensions_css( 'padding' ),
				),
				'condition'  => array( 'rael_product_compare_table_title_style' => 'yes' ),
			)
		);

		$this->add_control(
			'rael_product_compare_table_title_border_heading',
			array(
				'label'     => __( 'Table Title Border', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'tbl_ttl_style_pot' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'rael_product_compare_table_title_cell_border',
				'selector'  => $table_title_wrap,
				'condition' => array( 'rael_product_compare_table_title_style' => 'yes' ),
			)
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_product_compare_table_title_typography',
				'label'     => __( 'Table Title Typography', 'responsive-addons-for-elementor' ),
				'selector'  => $table_title,
				'condition' => array( 'rael_product_compare_table_title!' => '' ),
			)
		);

		$this->add_control(
			'rael_product_compare_title_row_separator',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->init_style_table_common_style( $table );

		$this->end_controls_section();

		$this->init_style_header_column_style();
		foreach ( range( 0, 2 ) as $column ) {
			$this->init_style_product_column_style( $column, $table );
		}

		$this->init_style_icon_controls( $table );
		$this->init_style_price_controls( $table );
	}

	public function register_style_tab_close_button_section() {
		$this->start_controls_section(
			'rael_style_tab_close_button_section',
			array(
				'label'     => __( 'Compare Modal Close Button', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_products_show_compare' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_product_compare_close_button_heading',
			array(
				'label'     => __( 'Close Button', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'rael_product_compare_close_button_icon_size',
			array(
				'label'      => __( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
					'em' => array(
						'min' => 0,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'.rael-products-compare-modal .rael-products-compare-modal-close' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_product_compare_close_button_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.rael-products-compare-modal .rael-products-compare-modal-close' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'rael_product_compare_close_button_bg',
			array(
				'label'     => __( 'Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.rael-products-compare-modal .rael-products-compare-modal-close' => 'background-color: {{VALUE}}!important;',
				),
			)
		);

		$this->add_control(
			'rael_product_compare_close_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'.rael-products-compare-modal .rael-products-compare-modal-close' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'rael_product_compare_close_button_box_shadow',
				'label'    => __( 'Box Shadow', 'responsive-addons-for-elementor' ),
				'selector' => '.rael-products-compare-modal .rael-products-compare-modal-close',
			)
		);
		$this->end_controls_section();
	}

	public function init_style_table_common_style( $tbl = '' ) {
		$tbl = ! empty( $tbl ) ? $tbl : '{{WRAPPER}} .rael-products-compare-wrapper table';
		$td  = "{$tbl} td";
		$th  = "{$tbl} tr:not(.image):not(.title) th:not(.first-th)"; // if we do not need to give title row weight, then remove :not(.title)

		$img_class = "{$tbl} tr.image td";
		$img       = "{$tbl} tr.image td img";
		$title_row = "{$tbl} tr.title th, {$tbl} tr.title td";
		$btn       = "{$tbl} a.button";
		$btn_hover = "{$tbl} a.button:hover";
		$tr_even   = "{$tbl} tr:nth-child(even):not(.image):not(.title) th, {$tbl} tr:nth-child(even):not(.image):not(.title) td";
		$tr_odd    = "{$tbl} tr:nth-child(odd):not(.image):not(.title) th, {$tbl} tr:nth-child(odd):not(.image):not(.title) td";

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_product_compare_title_row_typo',
				'label'     => __( 'Product Title Row Typography', 'responsive-addons-for-elementor' ),
				'selector'  => $title_row,
				'condition' => array(
					'rael_product_compare_separate_col_style!' => 'yes',
				),
			)
		);

		// common columns
		$this->add_control(
			'rael_product_compare_common_th_style_popover',
			array(
				'label'        => __( 'Header Column Style', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Custom', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'separator'    => 'before',
				'condition'    => array( 'rael_product_compare_separate_col_style!' => 'yes' ),
			)
		);

		$this->start_popover();

		$this->add_responsive_control(
			'rael_product_compare_th_width',
			array(
				'label'      => esc_html__( 'Header Column Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'rem',
					'%',
				),
				'range'      => array(
					'px'  => array(
						'min'  => 0,
						'max'  => 550,
						'step' => 5,
					),
					'rem' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => .5,
					),
					'%'   => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					$th => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'rael_product_compare_common_th_style_popover' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'rael_product_compare_th_padding',
			array(
				'label'      => __( 'Header Column Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					$th => Helper::dimensions_css( 'padding' ),
				),
				'condition'  => array( 'rael_product_compare_common_th_style_popover' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'rael_product_compare_common_h_col_border',
				'label'     => __( 'Header border', 'responsive-addons-for-elementor' ),
				'selector'  => $th,
				'condition' => array( 'rael_product_compare_common_th_style_popover' => 'yes' ),
			)
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_product_compare_th_typo',
				'label'     => __( 'Header Column Typography', 'responsive-addons-for-elementor' ),
				'selector'  => $th,
				'condition' => array(
					'rael_product_compare_separate_col_style!' => 'yes',
				),
			)
		);

		// Product column
		$this->add_control(
			'rael_product_compare_common_td_style_pop',
			array(
				'label'        => __( 'Product Column Style', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Custom', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'separator'    => 'before',
				'condition'    => array( 'rael_product_compare_separate_col_style!' => 'yes' ),
			)
		);

		$this->start_popover();

		$this->add_responsive_control(
			'rael_product_compare_td_width',
			array(
				'label'      => esc_html__( 'Product Column Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'rem',
					'%',
				),
				'range'      => array(
					'px'  => array(
						'min'  => 0,
						'max'  => 550,
						'step' => 5,
					),
					'rem' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => .5,
					),
					'%'   => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					$td => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'rael_product_compare_common_td_style_pop' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'rael_product_compare_td_padding',
			array(
				'label'      => __( 'Product Column Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					$td => Helper::dimensions_css( 'padding' ),
				),
				'condition'  => array( 'rael_product_compare_common_td_style_pop' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'rael_product_compare_img_td_padding',
			array(
				'label'      => __( 'Product Image Box Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					$img_class => Helper::dimensions_css( 'padding' ),
				),
				'condition'  => array( 'rael_product_compare_common_td_style_pop' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'rael_product_compare_img_padding',
			array(
				'label'      => __( 'Product Image Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					$img => Helper::dimensions_css( 'padding' ),
				),
				'condition'  => array( 'rael_product_compare_common_td_style_pop' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'rael_product_compare_td_col_border',
				'label'     => __( 'Product Column Border', 'responsive-addons-for-elementor' ),
				'selector'  => $td,
				'condition' => array( 'rael_product_compare_common_td_style_pop' => 'yes' ),
			)
		);

		$this->add_control(
			'rael_product_compare_img_col_border_heading',
			array(
				'label'     => __( 'Product Image Box Border', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'rael_product_compare_common_td_style_pop' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'rael_product_compare_img_col_border',
				'label'     => __( 'Image Box border', 'responsive-addons-for-elementor' ),
				'selector'  => $img_class,
				'condition' => array( 'rael_product_compare_common_td_style_pop' => 'yes' ),
			)
		);

		$this->add_control(
			'rael_product_compare_img_border_heading',
			array(
				'label'     => __( 'Product Image Border', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'rael_product_compare_common_td_style_pop' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'rael_product_compare_img_border',
				'label'     => __( 'Product Image border', 'responsive-addons-for-elementor' ),
				'selector'  => $img_class . ' img',
				'condition' => array( 'rael_product_compare_common_td_style_pop' => 'yes' ),
			)
		);

		$this->add_control(
			'rael_product_compare_img_border_radius',
			array(
				'label'      => __( 'Image Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
				),
				'selectors'  => array(
					$img_class . ' img' => Helper::dimensions_css( 'border-radius' ),
				),
				'condition'  => array(
					'rael_product_compare_common_td_style_pop' => 'yes',
				),
			)
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_product_compare__td_typo',
				'label'     => __( 'Product Column Typography', 'responsive-addons-for-elementor' ),
				'selector'  => $td,
				'condition' => array( 'rael_product_compare_separate_col_style!' => 'yes' ),
			)
		);

		// Colors
		$this->add_control(
			'rael_product_compare_common_colors_heading',
			array(
				'label'     => __( 'Colors', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'rael_product_compare_separate_col_style!' => 'yes',
				),
			)
		);

		$this->start_controls_tabs(
			'rael_product_compare_tabs_table_common_style',
			array(
				'condition' => array(
					'rael_product_compare_separate_col_style!' => 'yes',
				),
			)
		);

		/*-----Normal state------ */
		$this->start_controls_tab(
			'rael_product_compare_tab_table_common_style_normal',
			array(
				'label'     => __( 'Normal', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_product_compare_separate_col_style!' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'rael_product_compare_image_bg_normal',
				'label'    => __( 'Image Background', 'responsive-addons-for-elementor' ),
				'types'    => array(
					'classic',
					'gradient',
				),
				'selector' => $img_class,
			)
		);

		$this->add_control(
			'rael_product_compare_common_column_color_heading_normal',
			array(
				'label'     => __( 'Columns', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_product_compare_common_h_col_bg_normal',
			array(
				'label'     => __( 'Header Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $th => 'background-color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'rael_product_compare_common_h_col_color_normal',
			array(
				'label'     => __( 'Header Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $th => 'color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'rael_product_compare_common_td_col_bg_normal',
			array(
				'label'     => __( 'Product Column Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $td => 'background-color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'rael_product_compare_common_td_col_color_normal',
			array(
				'label'     => __( 'Product Column Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $td => 'color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'rael_product_compare_common_buttons_color_heading_normal',
			array(
				'label'     => __( 'Buttons', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_product_compare_btn_color_normal',
			array(
				'label'     => __( 'Button Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $btn => 'color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'rael_product_compare_btn_bg_color_normal',
			array(
				'label'     => __( 'Button Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $btn => 'background:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'rael_product_compare_common_even_odd_clr_heading_normal',
			array(
				'label'     => __( 'Even & Odd Rows', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_product_compare_common_tr_even_bg_normal',
			array(
				'label'     => __( 'Even Row Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $tr_even => 'background-color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'rael_product_compare_common_tr_even_color_normal',
			array(
				'label'     => __( 'Even Row Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $tr_even => 'color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'rael_product_compare_common_tr_odd_bg_normal',
			array(
				'label'     => __( 'Odd Row Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $tr_odd => 'background-color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'rael_product_compare_common_tr_odd_color_normal',
			array(
				'label'     => __( 'Odd Row Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $tr_odd => 'color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'rael_product_compare_title_row_color_heading_normal',
			array(
				'label'     => __( 'Title Row', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_product_compare_common_title_row_bg_normal',
			array(
				'label'     => __( 'Title Row Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $title_row => 'background-color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'rael_product_compare_common_title_row_color_normal',
			array(
				'label'     => __( 'Title Row Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $title_row => 'color:{{VALUE}}' ),
			)
		);

		$this->end_controls_tab();

		/*-----Hover state------ */
		$this->start_controls_tab(
			'rael_product_compare_tab_table_common_style_hover',
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_product_compare_btn_color_hover',
			array(
				'label'     => __( 'Button Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $btn_hover => 'color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'rael_product_compare_btn_bg_color_hover',
			array(
				'label'     => __( 'Button Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $btn_hover => 'background:{{VALUE}}' ),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
	}

	public function init_style_header_column_style( $tbl = '' ) {
		$tbl      = ! empty( $tbl ) ? $tbl : '{{WRAPPER}} .rael-products-compare-wrapper table';
		$h_col    = "{$tbl} tr:not(.image):not(.title) th:not(.first-th)";
		$title_th = "{$tbl} tr.title th";
		$tr_even  = "{$tbl} tr:nth-child(even):not(.image):not(.title) th, {$tbl} tr:nth-child(even):not(.image):not(.title) td";
		$tr_odd   = "{$tbl} tr:nth-child(odd):not(.image):not(.title) th, {$tbl} tr:nth-child(odd):not(.image):not(.title) td";

		$this->start_controls_section(
			'rael_products_compare_section_style_header_column',
			array(
				'label'     => __( 'Header Column', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_product_compare_separate_col_style' => 'yes',
					'rael_products_show_compare' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_products_compare_header_col_width',
			array(
				'label'      => esc_html__( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'rem',
					'%',
				),
				'range'      => array(
					'px'  => array(
						'min'  => 0,
						'max'  => 550,
						'step' => 5,
					),
					'rem' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => .5,
					),
					'%'   => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					$h_col => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_products_compare_header_col_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					$h_col => Helper::dimensions_css( 'padding' ),
				),
			)
		);

		$this->add_control(
			'rael_products_compare_header_col_clr_heading',
			array(
				'label' => __( 'Colors', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_products_compare_title_header_col_bg',
			array(
				'label'     => __( 'Title Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $title_th => 'background-color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'rael_products_compare_title_header_col_color',
			array(
				'label'     => __( 'Title Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $title_th => 'color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'rael_products_compare_header_col_bg',
			array(
				'label'     => __( 'Column Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $h_col => 'background-color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'rael_products_compare_header_col_color',
			array(
				'label'     => __( 'Column Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $h_col => 'color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'rael_products_compare_header_rows_clr_heading',
			array(
				'label'     => __( 'Rows', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_products_compare_tr_even_bg',
			array(
				'label'     => __( 'Even Row Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $tr_even => 'background-color:{{VALUE}}' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_products_compare_tr_even_color',
			array(
				'label'     => __( 'Even Row Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $tr_even => 'color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'rael_products_compare_tr_odd_bg',
			array(
				'label'     => __( 'Odd Row Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $tr_odd => 'background-color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'rael_products_compare_tr_odd_color',
			array(
				'label'     => __( 'Odd Row Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $tr_odd => 'color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			'rael_products_compare_title_border_heading',
			array(
				'label'     => __( 'Title Border', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_products_compare_title_header_col_border',
				'selector' => $title_th,
			)
		);

		$this->add_control(
			'rael_products_compare_header_border_heading',
			array(
				'label'     => __( 'Header Border', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_products_compare_header_col_border',
				'selector' => $h_col,
			)
		);

		$this->add_control(
			'rael_products_compare_header_typo_heading',
			array(
				'label'     => __( 'Typography', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_products_compare_title_header_col_typo',
				'label'    => __( 'Title', 'responsive-addons-for-elementor' ),
				'selector' => $title_th,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_products_compare_header_col_typo',
				'label'    => __( 'Header', 'responsive-addons-for-elementor' ),
				'selector' => $h_col,
			)
		);

		$this->end_controls_section();
	}

	public function init_style_product_column_style( $column_number, $tbl = '' ) {
		$tbl = ! empty( $tbl ) ? $tbl : '{{WRAPPER}} .rael-products-compare-wrapper table';

		$title_number = 1 + $column_number; // first column number is 0, so title number will start from 1 in the loop.
		$pfx          = "col{$column_number}";
		// New selectors
		$column_class = "{$tbl} td:nth-of-type(3n+{$title_number})";
		$title_row    = "{$tbl} tr.title td:nth-of-type(3n+{$title_number})";
		$tr_even      = "{$tbl} tr:nth-of-type(even):not(.image):not(.title) td:nth-of-type(3n+{$title_number})";
		$tr_odd       = "{$tbl} tr:nth-of-type(odd):not(.image):not(.title) td:nth-of-type(3n+{$title_number})";
		$btn          = "{$tbl} td:nth-of-type(3n+{$title_number}) a.button";
		$btn_hover    = "{$btn}:hover";
		$img_td       = "{$tbl} tr.image td:nth-of-type(3n+{$title_number})";
		$img          = "{$img_td} img";

		$this->start_controls_section(
			'rael_products_compare_section_style_' . $pfx,
			array(
				'label'     => sprintf(
				// translators: %d represents the product column number.
					__( 'Product Column %d', 'responsive-addons-for-elementor' ),
					$title_number
				),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_product_compare_separate_col_style' => 'yes',
					'rael_products_show_compare' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			"rael_products_compare_{$pfx}_width",
			array(
				'label'      => esc_html__( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'rem',
					'%',
				),
				'range'      => array(
					'px'  => array(
						'min'  => 0,
						'max'  => 550,
						'step' => 5,
					),
					'rem' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => .5,
					),
					'%'   => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					$column_class => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			"rael_products_compare_{$pfx}_padding",
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					$column_class => Helper::dimensions_css( 'padding' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => "rael_products_compare_{$pfx}_border",
				'selector' => $column_class,
			)
		);

		$this->add_control(
			"rael_products_compare_{$pfx}_img_col_brd_heading",
			array(
				'label'     => __( 'Product Image Box Border', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => "rael_products_compare_{$pfx}_img_col_border",
				'label'    => __( 'Image Box border', 'responsive-addons-for-elementor' ),
				'selector' => $img_td,
			)
		);

		$this->add_control(
			"rael_products_compare_{$pfx}_img_brd_heading",
			array(
				'label'     => __( 'Product Image Border', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => "rael_products_compare_{$pfx}_img_border",
				'label'    => __( 'Product Image border', 'responsive-addons-for-elementor' ),
				'selector' => $img,
			)
		);

		$this->add_control(
			"rael_products_compare_{$pfx}_img_border_radius",
			array(
				'label'      => __( 'Image Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
				),
				'selectors'  => array(
					$img => Helper::dimensions_css( 'border-radius' ),
				),
			)
		);

		// Typography
		$this->add_control(
			"rael_products_compare_{$pfx}_typo_heading",
			array(
				'label'     => __( 'Typography', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => "rael_products_compare_{$pfx}_title_typo",
				'label'    => sprintf( __( 'Title', 'responsive-addons-for-elementor' ), $title_number ),
				'selector' => $title_row,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => "rael_products_compare_{$pfx}_text_typo",
				'label'    => sprintf( __( 'Text', 'responsive-addons-for-elementor' ), $title_number ),
				'selector' => $column_class,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => "rael_products_compare_{$pfx}_btn_typo",
				'label'    => sprintf( __( 'Button', 'responsive-addons-for-elementor' ), $title_number ),
				'selector' => $btn,
			)
		);

		// COLORS
		$this->add_control(
			"rael_products_compare_{$pfx}_clr_heading",
			array(
				'label'     => __( 'Colors', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( "rael_products_compare_{$pfx}_tabs_style" );
		/*-----NORMAL state------ */
		$this->start_controls_tab(
			"rael_products_compare_{$pfx}_tab_style_normal",
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => "rael_products_compare_{$pfx}_img_bg",
				'label'    => __( 'Image Background', 'responsive-addons-for-elementor' ),
				'types'    => array(
					'classic',
					'gradient',
				),
				'selector' => $img_td,
				'exclude'  => array( 'image' ),
			)
		);

		$this->add_control(
			"rael_products_compare_{$pfx}_title_bg",
			array(
				'label'     => __( 'Title Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $title_row => 'background-color:{{VALUE}}' ),
				'condition' => array(
					'rael_product_compare_theme' => array(
						'theme-1',
						'theme-2',
						'theme-5',
					),
				),
			)
		);

		$this->add_control(
			"rael_products_compare_{$pfx}_title_color",
			array(
				'label'     => __( 'Title Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $title_row => 'color:{{VALUE}}' ),
				'condition' => array(
					'rael_product_compare_theme' => array(
						'theme-1',
						'theme-2',
						'theme-5',
					),
				),
			)
		);

		$this->add_control(
			"rael_products_compare_{$pfx}_button_clr_heading",
			array(
				'label'     => __( 'Button', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			"rael_products_compare_{$pfx}_btn_color",
			array(
				'label'     => __( 'Button Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $btn => 'color:{{VALUE}}' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			"rael_products_compare_{$pfx}_btn_bg",
			array(
				'label'     => __( 'Button Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $btn => 'background-color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			"rael_products_comapre_{$pfx}_rows_clr_heading",
			array(
				'label'     => __( 'Rows', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			"rael_products_comapre_{$pfx}_tr_even_bg",
			array(
				'label'     => __( 'Even Row Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $tr_even => 'background-color:{{VALUE}}' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			"rael_products_comapre_{$pfx}_tr_even_color",
			array(
				'label'     => __( 'Even Row Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $tr_even => 'color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			"rael_products_comapre_{$pfx}_tr_odd_bg",
			array(
				'label'     => __( 'Odd Row Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $tr_odd => 'background-color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			"rael_products_comapre_{$pfx}_tr_odd_color",
			array(
				'label'     => __( 'Odd Row Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $tr_odd => 'color:{{VALUE}}' ),
			)
		);

		$this->end_controls_tab();

		/*-----HOVER state------ */
		$this->start_controls_tab(
			"rael_products_comapre_{$pfx}_tab_style_hover",
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			"rael_products_comapre_{$pfx}_btn_color_hover",
			array(
				'label'     => __( 'Button Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $btn_hover => 'color:{{VALUE}}' ),
			)
		);

		$this->add_control(
			"rael_products_comapre_{$pfx}_btn_bg_hover",
			array(
				'label'     => __( 'Button Background', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $btn_hover => 'background-color:{{VALUE}}' ),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function init_style_icon_controls( $tbl = '' ) {
		$icon = "{$tbl} .elementor-icon";

		$this->start_controls_section(
			'rael_products_comapre_section_style_icon',
			array(
				'label'     => __( 'Fields Icon', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_products_field_icon[value]!' => '',
					'rael_products_show_compare'       => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_products_compare_field_icon_size',
			array(
				'label'      => esc_html__( 'Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'rem',
					'%',
				),
				'range'      => array(
					'px'  => array(
						'min'  => 0,
						'max'  => 550,
						'step' => 5,
					),
					'rem' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => .5,
					),
				),
				'selectors'  => array(
					$icon => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rael_products_compare_field_icon_size_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'rem',
				),
				'selectors'  => array(
					$icon => Helper::dimensions_css( 'margin' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_products_compare_field_icon_pos',
			array(
				'label'      => __( 'Position', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'rem',
				),
				'selectors'  => array(
					$icon => 'position:relative; top: {{TOP}}{{UNIT}};right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_products_compare_field_icon_color',
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$icon          => 'color:{{VALUE}} !important;',
					$icon . ' i'   => 'color:{{VALUE}} !important;',
					$icon . ' svg' => 'color:{{VALUE}} !important;fill:{{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_section();
	}

	public function init_style_price_controls( $tbl = '' ) {
		$strike      = "{$tbl} del";
		$price       = "{$tbl} del .woocommerce-Price-amount";
		$sales_price = "{$tbl} ins .woocommerce-Price-amount";

		$this->start_controls_section(
			'rael_products_compare_section_style_price',
			array(
				'label'     => __( 'Price Style', 'responsive-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'rael_products_show_compare' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_products_compare_price_heading',
			array(
				'label'     => __( 'Normal Price Style', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'rael_products_compare_price_size',
			array(
				'label'      => esc_html__( 'Price Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'rem',
					'%',
				),
				'range'      => array(
					'px'  => array(
						'min'  => 0,
						'max'  => 40,
						'step' => 5,
					),
					'rem' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => .5,
					),
				),
				'selectors'  => array(
					$price => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_products_compare_price_color',
			array(
				'label'     => __( 'Price Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$price => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_compare_strike_price_color',
			array(
				'label'       => __( 'Price Strike Text Color', 'responsive-addons-for-elementor' ),
				'description' => __( 'Only applicable when sales price is available', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					$strike => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_products_compare_sales_price_heading',
			array(
				'label'     => __( 'Sales Price Style', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'rael_products_compare_sales_price_size',
			array(
				'label'      => esc_html__( 'Sales Price Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'rem',
					'%',
				),
				'range'      => array(
					'px'  => array(
						'min'  => 0,
						'max'  => 40,
						'step' => 5,
					),
					'rem' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => .5,
					),
				),
				'selectors'  => array(
					$sales_price => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rael_products_compare_sales_price_color',
			array(
				'label'     => __( 'Sales Price Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$sales_price => 'color:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}
}
