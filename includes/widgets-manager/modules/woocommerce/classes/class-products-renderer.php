<?php
/**
 * RAEL Product Renderer.
 *
 * @package  Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Modules\Woocommerce\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Responsive_Addons_For_Elementor\Helper\Helper;

/**
 * RAEL Products Renderer class
 */
class Products_Renderer extends Base_Products_Renderer {

	/**
	 * Widget settings
	 *
	 * @var array
	 */
	private $settings = array();

	/**
	 * RAEL set ids query args
	 *
	 * @var boolean
	 */
	private $is_added_product_filter = false;
	const QUERY_CONTROL_NAME         = 'query'; // Constraint: the class that uses the renderer, must use the same name.
	const DEFAULT_COLUMNS_AND_ROWS   = 4;

	/**
	 * Constructor for the RAEL Product Carousel widget class.
	 *
	 * @param array  $settings  Optional. An array of widget settings.
	 * @param string $type  Optional. Type.
	 */
	public function __construct( $settings = array(), $type = 'products' ) {
		$this->settings   = $settings;
		$this->type       = $type;
		$this->attributes = $this->parse_attributes(
			array(
				'columns'  => $settings['columns'],
				'rows'     => $settings['rows'],
				'paginate' => $settings['paginate'],
				'cache'    => false,
			)
		);
		$this->query_args = $this->parse_query_args();
	}

	/**
	 * Override the original `get_query_results`
	 * with modifications that:
	 * 1. Remove `pre_get_posts` action if `is_added_product_filter`.
	 *
	 * @return bool|mixed|object
	 */
	protected function get_query_results() {
		$results = parent::get_query_results();
		// Start edit.
		if ( $this->is_added_product_filter ) {
			remove_action( 'pre_get_posts', array( wc()->query, 'product_query' ) );
		}
		// End edit.

		return $results;
	}

	/**
	 * RAEL parse query args.
	 *
	 * @package  Responsive_Addons_For_Elementor
	 */
	protected function parse_query_args() {
		$prefix   = self::QUERY_CONTROL_NAME . '_';
		$settings = &$this->settings;

		$query_args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => false === wc_string_to_bool( $this->attributes['paginate'] ),
			'orderby'             => $settings[ $prefix . 'orderby' ],
			'order'               => strtoupper( $settings[ $prefix . 'order' ] ),
		);

		$query_args['meta_query'] = WC()->query->get_meta_query(); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		$query_args['tax_query']  = array(); //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query

		$front_page = is_front_page();
		if ( 'yes' === $settings['paginate'] && 'yes' === $settings['allow_order'] && ! $front_page ) {
			$ordering_args = WC()->query->get_catalog_ordering_args();
		} else {
			$ordering_args = WC()->query->get_catalog_ordering_args( $query_args['orderby'], $query_args['order'] );
		}

		$query_args['orderby'] = $ordering_args['orderby'];
		$query_args['order']   = $ordering_args['order'];
		if ( $ordering_args['meta_key'] ) {
			$query_args['meta_key'] = $ordering_args['meta_key']; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		}

		// Visibility.
		$this->set_visibility_query_args( $query_args );

		// Featured.
		$this->set_featured_query_args( $query_args );

		// Sale.
		$this->set_sale_products_query_args( $query_args );

		// IDs.
		$this->set_ids_query_args( $query_args );

		// Set specific types query args.
		if ( method_exists( $this, "set_{$this->type}_query_args" ) ) {
			$this->{"set_{$this->type}_query_args"}( $query_args );
		}

		// Categories & Tags.
		$this->set_terms_query_args( $query_args );

		// Exclude.
		$this->set_exclude_query_args( $query_args );

		if ( 'yes' === $settings['paginate'] ) {
			$page = absint( empty( $_GET['product-page'] ) ? 1 : $_GET['product-page'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			if ( 1 < $page ) {
				$query_args['paged'] = $page;
			}

			if ( 'yes' !== $settings['allow_order'] || $front_page ) {
				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
			}

			if ( 'yes' !== $settings['show_result_count'] ) {
				remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
			}
		}
		// fallback to the widget's default settings in case settings was left empty.
		$rows                         = ! empty( $settings['rows'] ) ? $settings['rows'] : self::DEFAULT_COLUMNS_AND_ROWS;
		$columns                      = ! empty( $settings['columns'] ) ? $settings['columns'] : self::DEFAULT_COLUMNS_AND_ROWS;
		$query_args['posts_per_page'] = intval( $columns * $rows );

		$query_args = apply_filters( 'woocommerce_shortcode_products_query', $query_args, $this->attributes, $this->type );

		// Always query only IDs.
		$query_args['fields'] = 'ids';

		return $query_args;
	}

	/**
	 * RAEL set ids query args
	 *
	 * @param array $query_args Query Arguments.
	 */
	protected function set_ids_query_args( &$query_args ) {
		$prefix = self::QUERY_CONTROL_NAME . '_';

		switch ( $this->settings[ $prefix . 'post_type' ] ) {
			case 'by_id':
				$post__in = $this->settings[ $prefix . 'posts_ids' ];
				break;
			case 'sale':
				$post__in = wc_get_product_ids_on_sale();
				break;
		}

		if ( ! empty( $post__in ) ) {
			$query_args['post__in'] = $post__in;
			remove_action( 'pre_get_posts', array( wc()->query, 'product_query' ) );
		}
	}

	/**
	 * RAEL set terms query args
	 *
	 * @param array $query_args Query Arguments.
	 */
	private function set_terms_query_args( &$query_args ) {
		$prefix = self::QUERY_CONTROL_NAME . '_';

		$query_type = $this->settings[ $prefix . 'post_type' ];

		if ( 'by_id' === $query_type || 'current_query' === $query_type ) {
			return;
		}

		if ( empty( $this->settings[ $prefix . 'include' ] ) || empty( $this->settings[ $prefix . 'include_term_ids' ] ) || ! in_array( 'terms', $this->settings[ $prefix . 'include' ], true ) ) {
			return;
		}

		$terms = array();
		foreach ( $this->settings[ $prefix . 'include_term_ids' ] as $id ) {
			$term_data            = get_term_by( 'term_taxonomy_id', $id );
			$taxonomy             = $term_data->taxonomy;
			$terms[ $taxonomy ][] = $id;
		}
		$tax_query = array();
		foreach ( $terms as $taxonomy => $ids ) {
			$query = array(
				'taxonomy' => $taxonomy,
				'field'    => 'term_taxonomy_id',
				'terms'    => $ids,
			);

			$tax_query[] = $query;
		}

		if ( ! empty( $tax_query ) ) {
			$query_args['tax_query'] = array_merge( $query_args['tax_query'], $tax_query ); //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		}
	}

	/**
	 * RAEL set featured query args
	 *
	 * @param array $query_args Query Arguments.
	 */
	protected function set_featured_query_args( &$query_args ) {
		$prefix = self::QUERY_CONTROL_NAME . '_';
		if ( 'featured' === $this->settings[ $prefix . 'post_type' ] ) {
			$product_visibility_term_ids = wc_get_product_visibility_term_ids();

			$query_args['tax_query'][] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => array( $product_visibility_term_ids['featured'] ),
			);
		}
	}

	/**
	 * RAEL set sale products query args
	 *
	 * @param array $query_args Query Arguments.
	 */
	protected function set_sale_products_query_args( &$query_args ) {
		$prefix = self::QUERY_CONTROL_NAME . '_';
		if ( 'sale' === $this->settings[ $prefix . 'post_type' ] ) {
			parent::set_sale_products_query_args( $query_args );
		}
	}

	/**
	 * RAEL set exclude query args
	 *
	 * @param array $query_args Query Arguments.
	 */
	protected function set_exclude_query_args( &$query_args ) {
		$prefix = self::QUERY_CONTROL_NAME . '_';

		if ( empty( $this->settings[ $prefix . 'exclude' ] ) ) {
			return;
		}
		$post__not_in = array();
		if ( in_array( 'current_post', $this->settings[ $prefix . 'exclude' ], true ) ) {
			if ( is_singular() ) {
				$post__not_in[] = get_queried_object_id();
			}
		}

		if ( in_array( 'manual_selection', $this->settings[ $prefix . 'exclude' ], true ) && ! empty( $this->settings[ $prefix . 'exclude_ids' ] ) ) {
			$post__not_in = array_merge( $post__not_in, $this->settings[ $prefix . 'exclude_ids' ] );
		}

		$query_args['post__not_in'] = empty( $query_args['post__not_in'] ) ? $post__not_in : array_merge( $query_args['post__not_in'], $post__not_in );

		/**
		 * WC populates `post__in` with the ids of the products that are on sale.
		 * Since WP_Query ignores `post__not_in` once `post__in` exists, the ids are filtered manually, using `array_diff`.
		 */
		if ( 'sale' === $this->settings[ $prefix . 'post_type' ] ) {
			$query_args['post__in'] = array_diff( $query_args['post__in'], $query_args['post__not_in'] );
		}

		if ( in_array( 'terms', $this->settings[ $prefix . 'exclude' ], true ) && ! empty( $this->settings[ $prefix . 'exclude_term_ids' ] ) ) {
			$terms = array();
			foreach ( $this->settings[ $prefix . 'exclude_term_ids' ] as $to_exclude ) {
				$term_data                       = get_term_by( 'term_taxonomy_id', $to_exclude );
				$terms[ $term_data->taxonomy ][] = $to_exclude;
			}
			$tax_query = array();
			foreach ( $terms as $taxonomy => $ids ) {
				$tax_query[] = array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $ids,
					'operator' => 'NOT IN',
				);
			}
			if ( empty( $query_args['tax_query'] ) ) {
				$query_args['tax_query'] = $tax_query; //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			} else {
				$query_args['tax_query']['relation'] = 'AND';
				$query_args['tax_query'][]           = $tax_query;
			}
		}
	}
}
