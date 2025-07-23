<?php
/**
 * Conditions for the Theme Builder template.
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\ModulesManager\Theme_Builder\Conditions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Responsive_Addons_For_Elementor\Traits\Singleton;

/**
 * RAEL_Conditions Class
 */
class RAEL_Conditions {
	use Singleton;

	/**
	 * Meta Option
	 *
	 * @access private
	 * @static
	 *
	 * @since  1.3.0
	 *
	 * @var $meta_option
	 */
	private static $meta_option;

	/**
	 * Current page type
	 *
	 * @access private
	 * @static
	 *
	 * @since  1.3.0
	 *
	 * @var $current_page_type
	 */
	private static $current_page_type = null;

	/**
	 * Current page data
	 *
	 * @access private
	 * @static
	 *
	 * @since  1.3.0
	 *
	 * @var $current_page_data
	 */
	private static $current_page_data = array();

	/**
	 * User Selection Option
	 *
	 * @access private
	 * @static
	 *
	 * @since  1.3.0
	 *
	 * @var $user_selection
	 */
	private static $user_selection;

	/**
	 * Location Selection Option
	 *
	 * @access private
	 * @static
	 *
	 * @since  1.3.0
	 *
	 * @var $location_selection
	 */
	private static $location_selection;

	/**
	 * Constructor
	 *
	 * @access private
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	private function __construct() {
		add_action( 'admin_action_edit', array( $this, 'initialize_options' ) );
		add_action( 'wp_ajax_rael_hfe_get_posts_by_query', array( $this, 'rael_hfe_get_posts_by_query' ) );
	}

	/**
	 * Initialize member variables.
	 *
	 * @access public
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	public function initialize_options() {
		self::$user_selection     = self::get_user_selections();
		self::$location_selection = self::get_location_selections();
	}

	/**
	 * Get location selection options.
	 *
	 * @access public
	 * @static
	 *
	 * @since 1.3.0
	 *
	 * @return array Array of available location options.
	 */
	public static function get_location_selections() {
		$args = array(
			'public'   => true,
			'_builtin' => true,
		);

		$post_types = get_post_types( $args, 'objects' );
		unset( $post_types['attachment'] );

		$args['_builtin'] = false;
		$custom_post_type = get_post_types( $args, 'objects' );

		$post_types = apply_filters( 'rael_hf_display_condition_post_types', array_merge( $post_types, $custom_post_type ) );

		$special_pages = array(
			'special-404'    => array(
				'label'          => __( '404 Page', 'responsive-addons-for-elementor' ),
				'template_types' => array( 'header', 'footer', 'error-404', 'single-page', 'single-post' ),
			),
			'special-search' => array(
				'label'          => __( 'Search Page', 'responsive-addons-for-elementor' ),
				'template_types' => array( 'header', 'footer', 'archive' ),
			),
			'special-blog'   => array(
				'label'          => __( 'Blog / Posts Page', 'responsive-addons-for-elementor' ),
				'template_types' => array( 'header', 'footer' ),
			),
			'special-front'  => array(
				'label'          => __( 'Front Page', 'responsive-addons-for-elementor' ),
				'template_types' => array( 'header', 'footer', 'single-page', 'single-post', 'error-404' ),
			),
			'special-date'   => array(
				'label'          => __( 'Date Archive', 'responsive-addons-for-elementor' ),
				'template_types' => array( 'header', 'footer', 'archive' ),
			),
			'special-author' => array(
				'label'          => __( 'Author Archive', 'responsive-addons-for-elementor' ),
				'template_types' => array( 'header', 'footer', 'archive' ),
			),
		);

		if ( class_exists( 'WooCommerce' ) ) {
			$special_pages['special-woo-shop'] = array(
				'label'          => __( 'WooCommerce Shop Page', 'responsive-addons-for-elementor' ),
				'template_types' => array( 'product-archive', 'header', 'footer' ),
			);
		}


		$selection_options = array(
			'basic'         => array(
				'label' => __( 'Basic', 'responsive-addons-for-elementor' ),
				'template_types' => array( 'header', 'footer', 'single-post', 'single-page', 'archive', 'error-404' ),
				'value' => array(
					'basic-global' => array(
						'label' => __( 'Entire Website', 'responsive-addons-for-elementor' ),
						'template_types' => array( 'header', 'footer' ), // only for header/footer
					),
					'basic-singulars' => array(
						'label' => __( 'All Singulars', 'responsive-addons-for-elementor' ),
						'template_types' => array( 'header', 'footer', 'single-post', 'single-page', 'error-404' ),
					),
					'basic-archives' => array(
						'label' => __( 'All Archives', 'responsive-addons-for-elementor' ),
						'template_types' => array( 'header', 'footer', 'archive', 'product-archive' ),
					),
				),
			),

			'special-pages' => array(
				'label' => __( 'Special Pages', 'responsive-addons-for-elementor' ),
				'template_types' => array( 'header', 'footer', 'single-post', 'single-page', 'archive', 'error-404', 'product-archive' ),
				'value' => $special_pages,
			),
		);

		$args = array(
			'public' => true,
		);

		$taxonomies = get_taxonomies( $args, 'objects' );

		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {

				// skip post format taxonomy.
				if ( 'post_format' === $taxonomy->name ) {
					continue;
				}

				foreach ( $post_types as $post_type ) {
					$post_opt = self::get_post_target_rule_options( $post_type, $taxonomy );
					if( empty( $post_opt ) ) continue;
					$post_key = $post_opt['post_key'];

					if ( isset( $selection_options[ $post_key ] ) ) {
						// Merge values
						if ( ! empty( $post_opt['value'] ) && is_array( $post_opt['value'] ) ) {
							foreach ( $post_opt['value'] as $key => $value ) {
								if ( ! isset( $selection_options[ $post_key ]['value'][ $key ] ) ) {
									$selection_options[ $post_key ]['value'][ $key ] = $value;
								}
							}
						}

						// Merge template_types
						if ( isset( $post_opt['template_types'] ) ) {
							$existing = isset( $selection_options[ $post_key ]['template_types'] )
								? $selection_options[ $post_key ]['template_types'] : array();

							$merged = array_unique( array_merge( $existing, $post_opt['template_types'] ) );
							$selection_options[ $post_key ]['template_types'] = $merged;
						}

					} else {
						// Create new group
						$selection_options[ $post_key ] = array(
							'label'          => $post_opt['label'],
							'value'          => $post_opt['value'],
							'template_types' => $post_opt['template_types'],
						);
					}
				}
			}
		}

		$selection_options['specific-target'] = array(
			'label' => __( 'Specific Target', 'responsive-addons-for-elementor' ),
			'value' => array(
				'specifics' => array(
					'label' => __( 'Specific Pages / Posts / Taxonomies, etc.', 'responsive-addons-for-elementor' ),
				),
			),
			'template_types' => array( 'header', 'footer', 'single-post', 'single-page', 'archive', 'error-404' ),
		);

		// Filter options displayed in the display conditions select field.
		return apply_filters( 'rael_hf_display_conditions_list', $selection_options );
	}

	/**
	 * Get user selection options.
	 *
	 * @access public
	 * @static
	 *
	 * @since 1.3.0
	 *
	 * @return array Array user roles list.
	 */
	public static function get_user_selections() {
		$selection_options = array(
			'basic'    => array(
				'label' => __( 'Basic', 'responsive-addons-for-elementor' ),
				'value' => array(
					'all'        => __( 'All', 'responsive-addons-for-elementor' ),
					'logged-in'  => __( 'Logged In', 'responsive-addons-for-elementor' ),
					'logged-out' => __( 'Logged Out', 'responsive-addons-for-elementor' ),
				),
			),

			'advanced' => array(
				'label' => __( 'Advanced', 'responsive-addons-for-elementor' ),
				'value' => array(),
			),
		);

		/* User roles */
		$roles = get_editable_roles();

		foreach ( $roles as $slug => $data ) {
			$selection_options['advanced']['value'][ $slug ] = $data['name'];
		}

		// Filter options displayed in the user select field of Display conditions.
		return apply_filters( 'rael_hf_display_user_roles_list', $selection_options );
	}

	/**
	 * Get location label by key.
	 *
	 * @param string $key Location option key.
	 * @return string
	 */
	public static function get_location_by_key( $key ) {
		if ( ! isset( self::$location_selection ) || empty( self::$location_selection ) ) {
			self::$location_selection = self::get_location_selections();
		}
		$location_selection = self::$location_selection;

		foreach ( $location_selection as $location_grp ) {
			if ( isset( $location_grp['value'][ $key ] ) ) {
				if( isset( $location_grp['value'][ $key ]['label'] ) ) {
					return $location_grp['value'][ $key ]['label'];
				} else {
					return $location_grp['value'][ $key ];
				}
			}
		}

		if ( strpos( $key, 'post-' ) !== false ) {
			$post_id = (int) str_replace( 'post-', '', $key );
			return get_the_title( $post_id );
		}

		// taxonomy options.
		if ( strpos( $key, 'tax-' ) !== false ) {
			$tax_id = (int) str_replace( 'tax-', '', $key );
			$term   = get_term( $tax_id );

			if ( ! is_wp_error( $term ) ) {
				$term_taxonomy = ucfirst( str_replace( '_', ' ', $term->taxonomy ) );
				return $term->name . ' - ' . $term_taxonomy;
			} else {
				return '';
			}
		}

		return $key;
	}

	/**
	 * Get user label by key.
	 *
	 * @access public
	 * @static
	 *
	 * @since 1.3.0
	 *
	 * @param string $key User option key.
	 *
	 * @return string User label.
	 */
	public static function get_user_by_key( $key ) {
		if ( ! isset( self::$user_selection ) || empty( self::$user_selection ) ) {
			self::$user_selection = self::get_user_selections();
		}
		$user_selection = self::$user_selection;

		if ( isset( $user_selection['basic']['value'][ $key ] ) ) {
			return $user_selection['basic']['value'][ $key ];
		} elseif ( $user_selection['advanced']['value'][ $key ] ) {
			return $user_selection['advanced']['value'][ $key ];
		}
		return $key;
	}

	/**
	 * Ajax handler to return the posts based on the search query.
	 * When searching for the post/pages only titles are searched for.
	 *
	 * @access public
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	public function rael_hfe_get_posts_by_query() {

		check_ajax_referer( 'rael-hfe-get-posts-by-query', 'nonce' );

		$search_string = isset( $_POST['q'] ) ? sanitize_text_field( $_POST['q'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		$data          = array();
		$result        = array();

		$args = array(
			'public'   => true,
			'_builtin' => false,
		);

		$output     = 'names'; // names or objects, note names is the default.
		$operator   = 'and'; // also supports 'or'.
		$post_types = get_post_types( $args, $output, $operator );

		unset( $post_types['rael-theme-template'] ); // Exclude EHF templates.

		$post_types['Posts'] = 'post';
		$post_types['Pages'] = 'page';

		foreach ( $post_types as $key => $post_type ) {
			$data = array();

			add_filter( 'posts_search', array( $this, 'search_only_titles' ), 10, 2 );

			$query = new \WP_Query(
				array(
					's'              => $search_string,
					'post_type'      => $post_type,
					'posts_per_page' => - 1,
				)
			);

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$title  = get_the_title();
					$title .= ( 0 != $query->post->post_parent ) ? ' (' . get_the_title( $query->post->post_parent ) . ')' : ''; // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
					$id     = get_the_id();
					$data[] = array(
						'id'   => 'post-' . $id,
						'text' => $title,
					);
				}
			}

			if ( is_array( $data ) && ! empty( $data ) ) {
				$result[] = array(
					'text'     => $key,
					'children' => $data,
				);
			}
		}

		$data = array();

		wp_reset_postdata();

		$args = array(
			'public' => true,
		);

		$output     = 'objects'; // names or objects, note names is the default.
		$operator   = 'and'; // also supports 'or'.
		$taxonomies = get_taxonomies( $args, $output, $operator );

		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_terms( // phpcs:ignore
				$taxonomy->name,
				array(
					'orderby'    => 'count',
					'hide_empty' => 0,
					'name__like' => $search_string,
				)
			);

			$data = array();

			$label = ucwords( $taxonomy->label );

			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {
					$term_taxonomy_name = ucfirst( str_replace( '_', ' ', $taxonomy->name ) );

					$data[] = array(
						'id'   => 'tax-' . $term->term_id,
						'text' => $term->name . ' archive page',
					);

					$data[] = array(
						'id'   => 'tax-' . $term->term_id . '-single-' . $taxonomy->name,
						'text' => 'All singulars from ' . $term->name,
					);
				}
			}

			if ( is_array( $data ) && ! empty( $data ) ) {
				$result[] = array(
					'text'     => $label,
					'children' => $data,
				);
			}
		}

		// return the result in json.
		wp_send_json( $result );
	}

	/**
	 * Return search results only by post title.
	 * This is only run from rael_hfe_get_posts_by_query()
	 *
	 * @access public
	 *
	 * @since 1.3.0
	 *
	 * @param  (string)   $search   Search SQL for WHERE clause.
	 * @param  (WP_Query) $wp_query The current WP_Query object.
	 *
	 * @return (string) The Modified Search SQL for WHERE clause.
	 */
	public function search_only_titles( $search, $wp_query ) {
		if ( ! empty( $search ) && ! empty( $wp_query->query_vars['search_terms'] ) ) {
			global $wpdb;

			$q = $wp_query->query_vars;
			$n = ! empty( $q['exact'] ) ? '' : '%';

			$search = array();

			foreach ( (array) $q['search_terms'] as $term ) {
				$search[] = $wpdb->prepare( "$wpdb->posts.post_title LIKE %s", $n . $wpdb->esc_like( $term ) . $n );
			}

			if ( ! is_user_logged_in() ) {
				$search[] = "$wpdb->posts.post_password = ''";
			}

			$search = ' AND ' . implode( ' AND ', $search );
		}

		return $search;
	}

	/**
	 * Enqueue styles and scripts.
	 *
	 * @access public
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	public function admin_styles() {

		$wp_lang = get_locale();
		$lang    = '';
		if ( '' !== $wp_lang ) {
			$select2_lang = array(
				''               => 'en',
				'hi_IN'          => 'hi',
				'mr'             => 'mr',
				'af'             => 'af',
				'ar'             => 'ar',
				'ary'            => 'ar',
				'as'             => 'as',
				'azb'            => 'az',
				'az'             => 'az',
				'bel'            => 'be',
				'bg_BG'          => 'bg',
				'bn_BD'          => 'bn',
				'bo'             => 'bo',
				'bs_BA'          => 'bs',
				'ca'             => 'ca',
				'ceb'            => 'ceb',
				'cs_CZ'          => 'cs',
				'cy'             => 'cy',
				'da_DK'          => 'da',
				'de_CH'          => 'de',
				'de_DE'          => 'de',
				'de_DE_formal'   => 'de',
				'de_CH_informal' => 'de',
				'dzo'            => 'dz',
				'el'             => 'el',
				'en_CA'          => 'en',
				'en_GB'          => 'en',
				'en_AU'          => 'en',
				'en_NZ'          => 'en',
				'en_ZA'          => 'en',
				'eo'             => 'eo',
				'es_MX'          => 'es',
				'es_VE'          => 'es',
				'es_CR'          => 'es',
				'es_CO'          => 'es',
				'es_GT'          => 'es',
				'es_ES'          => 'es',
				'es_CL'          => 'es',
				'es_PE'          => 'es',
				'es_AR'          => 'es',
				'et'             => 'et',
				'eu'             => 'eu',
				'fa_IR'          => 'fa',
				'fi'             => 'fi',
				'fr_BE'          => 'fr',
				'fr_FR'          => 'fr',
				'fr_CA'          => 'fr',
				'gd'             => 'gd',
				'gl_ES'          => 'gl',
				'gu'             => 'gu',
				'haz'            => 'haz',
				'he_IL'          => 'he',
				'hr'             => 'hr',
				'hu_HU'          => 'hu',
				'hy'             => 'hy',
				'id_ID'          => 'id',
				'is_IS'          => 'is',
				'it_IT'          => 'it',
				'ja'             => 'ja',
				'jv_ID'          => 'jv',
				'ka_GE'          => 'ka',
				'kab'            => 'kab',
				'km'             => 'km',
				'ko_KR'          => 'ko',
				'ckb'            => 'ku',
				'lo'             => 'lo',
				'lt_LT'          => 'lt',
				'lv'             => 'lv',
				'mk_MK'          => 'mk',
				'ml_IN'          => 'ml',
				'mn'             => 'mn',
				'ms_MY'          => 'ms',
				'my_MM'          => 'my',
				'nb_NO'          => 'nb',
				'ne_NP'          => 'ne',
				'nl_NL'          => 'nl',
				'nl_NL_formal'   => 'nl',
				'nl_BE'          => 'nl',
				'nn_NO'          => 'nn',
				'oci'            => 'oc',
				'pa_IN'          => 'pa',
				'pl_PL'          => 'pl',
				'ps'             => 'ps',
				'pt_BR'          => 'pt',
				'pt_PT_ao90'     => 'pt',
				'pt_PT'          => 'pt',
				'rhg'            => 'rhg',
				'ro_RO'          => 'ro',
				'ru_RU'          => 'ru',
				'sah'            => 'sah',
				'si_LK'          => 'si',
				'sk_SK'          => 'sk',
				'sl_SI'          => 'sl',
				'sq'             => 'sq',
				'sr_RS'          => 'sr',
				'sv_SE'          => 'sv',
				'szl'            => 'szl',
				'ta_IN'          => 'ta',
				'te'             => 'te',
				'th'             => 'th',
				'tl'             => 'tl',
				'tr_TR'          => 'tr',
				'tt_RU'          => 'tt',
				'tah'            => 'ty',
				'ug_CN'          => 'ug',
				'uk'             => 'uk',
				'ur'             => 'ur',
				'uz_UZ'          => 'uz',
				'vi'             => 'vi',
				'zh_CN'          => 'zh',
				'zh_TW'          => 'zh',
				'zh_HK'          => 'zh',
			);

			if ( isset( $select2_lang[ $wp_lang ] ) && file_exists( RAEL_DIR . 'assets/lib/select2/i18n/' . $select2_lang[ $wp_lang ] . '.js' ) ) {
				$lang = $select2_lang[ $wp_lang ];
			}
		}

		wp_register_script(
			'rael-hf-display-conditions',
			RAEL_URL . 'admin/assets/js/rael-theme-display-conditions.js',
			array(
				'jquery',
				'rael-select2',
			),
			RAEL_VER,
			true
		);

		wp_enqueue_script( 'rael-hf-display-conditions' );

		wp_register_script(
			'rael-hf-display-conditions-user-role',
			RAEL_URL . 'admin/assets/js/rael-theme-display-conditions-user-role.js',
			array(
				'jquery',
			),
			RAEL_VER,
			true
		);

		wp_enqueue_script( 'rael-hf-display-conditions-user-role' );

		wp_register_style( 'rael-hf-display-conditions-style', RAEL_URL . 'admin/css/rael-theme-display-conditions.css', array(), RAEL_VER );
		wp_enqueue_style( 'rael-hf-display-conditions-style' );

		$localize_vars = array(
			'rael_lang'     => $lang,
			'please_enter'  => __( 'Please enter', 'responsive-addons-for-elementor' ),
			'please_delete' => __( 'Please delete', 'responsive-addons-for-elementor' ),
			'more_char'     => __( 'or more characters', 'responsive-addons-for-elementor' ),
			'character'     => __( 'character', 'responsive-addons-for-elementor' ),
			'loading'       => __( 'Loading more results…', 'responsive-addons-for-elementor' ),
			'only_select'   => __( 'You can only select', 'responsive-addons-for-elementor' ),
			'item'          => __( 'item', 'responsive-addons-for-elementor' ),
			'char_s'        => __( 's', 'responsive-addons-for-elementor' ),
			'no_result'     => __( 'No results found', 'responsive-addons-for-elementor' ),
			'searching'     => __( 'Searching…', 'responsive-addons-for-elementor' ),
			'not_loader'    => __( 'The results could not be loaded.', 'responsive-addons-for-elementor' ),
			'search'        => __( 'Search pages / post / categories', 'responsive-addons-for-elementor' ),
			'ajax_nonce'    => wp_create_nonce( 'rael-hfe-get-posts-by-query' ),
		);
		wp_localize_script( 'rael-select2', 'rael_display_conditions', $localize_vars );
	}

	/**
	 * Function to handle new input type.
	 *
	 * @access public
	 * @static
	 *
	 * @since 1.3.0
	 *
	 * @param string $name string ID.
	 * @param array  $settings string Settings array.
	 * @param array  $value string Saved locations.
	 *
	 * @return void
	 */
	public static function target_rule_settings_field( $name, $settings, $value ) {
		$input_name     = $name;
		$type           = isset( $settings['type'] ) ? $settings['type'] : 'target_rule';
		$class          = isset( $settings['class'] ) ? $settings['class'] : '';
		$rule_type      = isset( $settings['rule_type'] ) ? $settings['rule_type'] : 'target_rule';
		$add_rule_label = isset( $settings['add_rule_label'] ) ? $settings['add_rule_label'] : __( 'Add Display On Condition', 'responsive-addons-for-elementor' );
		$saved_values   = $value;
		$output         = '';

		if ( isset( self::$location_selection ) || empty( self::$location_selection ) ) {
			self::$location_selection = self::get_location_selections();
		}
		$selection_options = self::$location_selection;

		/* WP Template Format */
		$output .= '<script type="text/html" id="tmpl-rael-hf-display-conditions-' . $rule_type . '-condition">';
		$output .= '<div class="rael-hf__display-condition rael-hf__display-condition-{{data.id}}" data-rule="{{data.id}}" >';
		$output .= '<span class="rael-hf__display-condition-delete dashicons dashicons-dismiss"></span>';
		/* Condition Selection */
		$output .= '<div class="rael-hf__display-condition-wrapper" >';
		$output .= '<select name="' . esc_attr( $input_name ) . '[rule][{{data.id}}]" class="rael-hf__display-condition-input form-control rael-hf-input">';
		$output .= '<option value="" data-template-types="all">' . __( 'Select', 'responsive-addons-for-elementor' ) . '</option>';

		foreach ( $selection_options as $group => $group_data ) {
			$output .= '<optgroup label="' . esc_attr( $group_data['label'] ) . '" data-template-types="' . 
					esc_attr( isset( $group_data['template_types'] ) && is_array( $group_data['template_types'] ) 
						? implode( ',', $group_data['template_types'] ) 
						: 'all' ) . '">';

			foreach ( $group_data['value'] as $opt_key => $opt_value ) {
				$option_template_types = '';

				if ( is_array( $opt_value ) ) {
					$label = $opt_value['label'];
					$option_template_types = ! empty( $opt_value['template_types'] )
						? implode( ',', $opt_value['template_types'] )
						: 'all';
				} else {
					$label = $opt_value;
					$option_template_types = isset( $group_data['template_types'] )
						? implode( ',', $group_data['template_types'] )
						: 'all';
				}

				$output .= sprintf(
					'<option value="%s" data-template-types="%s">%s</option>',
					esc_attr( $opt_key ),
					esc_attr( $option_template_types ),
					esc_html( $label )
				);
			}
			$output .= '</optgroup>';
		}
		$output .= '</select>';
		$output .= '</div>';

		$output .= '</div> <!-- rael-hf__display-condition -->';

		/* Specific page selection */
		$output .= '<div class="rael-hf__display-condition-specific-page-wrapper" style="display:none">';
		$output .= '<select name="' . esc_attr( $input_name ) . '[specific][]" class="rael-hf__display-condition-select2 rael-hf__display-condition-specific-page form-control rael-hf-input " multiple="multiple">';
		$output .= '</select>';
		$output .= '</div>';

		$output .= '</script>';

		/* Wrapper Start */
		$output .= '<div class="rael-hf__display-condition-container rael-hf__display-condition-' . $rule_type . '-on-wrap" data-type="' . $rule_type . '">';
		$output .= '<div class="rael-hf-display-condition-selector-wrapper rael-hf-display-condition-' . $rule_type . '-on">';
		$output .= self::generate_target_rule_selector( $rule_type, $selection_options, $input_name, $saved_values, $add_rule_label );
		$output .= '</div>';

		/* Wrapper end */
		$output .= '</div>';

		echo $output;//phpcs:ignore
	}

	/**
	 * Get target rules for generating the markup for rule selector.
	 *
	 * @access public
	 * @static
	 *
	 * @since  1.3.0
	 *
	 * @param object $post_type post type parameter.
	 * @param object $taxonomy taxonomy for creating the target rule markup.
	 *
	 * @return array
	 */
	public static function get_post_target_rule_options( $post_type, $taxonomy ) {
		$post_key    = str_replace( ' ', '-', strtolower( $post_type->label ) );
		$post_label  = ucwords( $post_type->label );
		$post_name   = $post_type->name;
		$post_option = array();
		// Skip floating elements, my templates and theme builder post types.
		if( 'floating-elements' === $post_key || 'my-templates' === $post_key || 'theme-builder' === $post_key ) {
			return array();
		}

		$singular_templates         = array( 'header', 'footer', 'single-post', 'single-page', 'error-404' );
		$archive_template           = array( 'header', 'footer', 'archive' );
		$products_template          = array( 'header', 'footer', 'single-product' );
		$products_archives_template = array( 'header', 'footer', 'product-archive' );
		/* translators: %s post label */
		$all_posts                          = sprintf( __( 'All %s', 'responsive-addons-for-elementor' ), $post_label );
		$post_option[ $post_name . '|all' ] = array(
			'label'          => $all_posts,
			'template_types' => 'product' === $post_name ? $products_template : $singular_templates,
		);

		if ( 'pages' !== $post_key ) {
			/* translators: %s post label */
			$all_archive                                = sprintf( __( 'All %s Archive', 'responsive-addons-for-elementor' ), $post_label );
			$post_option[ $post_name . '|all|archive' ] = array(
				'label'          => $all_archive,
				'template_types' => 'product' === $post_name ? $products_archives_template : $archive_template,
			);
		}

		if ( in_array( $post_type->name, $taxonomy->object_type ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			$tax_label = ucwords( $taxonomy->label );
			$tax_name  = $taxonomy->name;

			/* translators: %s taxonomy label */
			$tax_archive = sprintf( __( 'All %s Archive', 'responsive-addons-for-elementor' ), $tax_label );

			$post_option[ $post_name . '|all|taxarchive|' . $tax_name ] = array(
				'label'          => $tax_archive,
				'template_types' => 'product' === $post_name ? $products_archives_template :  $archive_template,
			);
		}

		// Dynamically collect group-level template types based on children.
		$group_template_types = array();
		foreach ( $post_option as $opt ) {
			if ( isset( $opt['template_types'] ) && is_array( $opt['template_types'] ) ) {
				$group_template_types = array_merge( $group_template_types, $opt['template_types'] );
			}
		}
		$group_template_types = array_unique( $group_template_types );

		// Return the final output.
		return array(
			'post_key'       => $post_key,
			'label'          => $post_label,
			'value'          => $post_option,
			'template_types' => $group_template_types,
		);
	}

	/**
	 * Generate markup for rendering the location selection.
	 *
	 * @access public
	 * @static
	 *
	 * @since  1.3.0
	 *
	 * @param  String $type                 Rule type display|exclude.
	 * @param  Array  $selection_options     Array for available selection fields.
	 * @param  String $input_name           Input name for the settings.
	 * @param  Array  $saved_values          Array of saved valued.
	 * @param  String $add_rule_label       Label for the Add rule button.
	 *
	 * @return HTML Markup for the location settings.
	 */
	public static function generate_target_rule_selector( $type, $selection_options, $input_name, $saved_values, $add_rule_label ) {
		$output = '<div class="rael-hf__display-condition-builder-wrapper">';

		if ( ! is_array( $saved_values ) || ( is_array( $saved_values ) && empty( $saved_values ) ) ) {
			$saved_values                = array();
			$saved_values['rule'][0]     = '';
			$saved_values['specific'][0] = '';
		}

		$index = 0;

		foreach ( $saved_values['rule'] as $index => $data ) {
			$output .= '<div class="rael-hf__display-condition rael-hf__display-condition-' . $index . '" data-rule="' . $index . '" >';
			/* Condition Selection */
			$output .= '<span class="rael-hf__display-condition-delete dashicons dashicons-dismiss"></span>';
			$output .= '<div class="rael-hf__display-condition-wrapper" >';
			$output .= '<select name="' . esc_attr( $input_name ) . '[rule][' . $index . ']" class="rael-hf__display-condition-input form-control rael-hf-input">';
			$output .= '<option value="" data-template-types="all">' . __( 'Select', 'responsive-addons-for-elementor' ) . '</option>';

			foreach ( $selection_options as $group => $group_data ) {
				$output .= '<optgroup label="' . esc_attr( $group_data['label'] ) . '" data-template-types="' . 
					esc_attr( isset( $group_data['template_types'] ) && is_array( $group_data['template_types'] ) 
						? implode( ',', $group_data['template_types'] ) 
						: 'all' ) . '">';

				foreach ( $group_data['value'] as $opt_key => $opt_value ) {

					// Specific rules.
					$selected = ( $data == $opt_key ) ? 'selected="selected"' : '';

					// Get label and template types per option.
					if ( is_array( $opt_value ) ) {
						$label = isset( $opt_value['label'] ) ? $opt_value['label'] : $opt_key;
						$template_types = isset( $opt_value['template_types'] )
							? implode( ',', $opt_value['template_types'] )
							: ( isset( $group_data['template_types'] ) ? implode( ',', $group_data['template_types'] ) : 'all' );
					} else {
						$label = $opt_value;
						$template_types = isset( $group_data['template_types'] )
							? implode( ',', $group_data['template_types'] )
							: 'all';
					}

					$output .= sprintf(
						'<option value="%s" %s data-template-types="%s">%s</option>',
						esc_attr( $opt_key ),
						$selected,
						esc_attr( $template_types ),
						esc_html( $label )
					);
				}
				$output .= '</optgroup>';
			}
			$output .= '</select>';
			$output .= '</div>';

			$output .= '</div>';

			/* Specific page selection */
			$output .= '<div class="rael-hf__display-condition-specific-page-wrapper" style="display:none">';
			$output .= '<select name="' . esc_attr( $input_name ) . '[specific][]" class="rael-hf__display-condition-select2 rael-hf__display-condition-specific-page form-control rael-hf-input " multiple="multiple">';

			if ( 'specifics' === $data && isset( $saved_values['specific'] ) && null !== $saved_values['specific'] && is_array( $saved_values['specific'] ) ) {
				foreach ( $saved_values['specific'] as $data_key => $sel_value ) {
					// posts.
					if ( strpos( $sel_value, 'post-' ) !== false ) {
						$post_id    = (int) str_replace( 'post-', '', $sel_value );
						$post_title = get_the_title( $post_id );
						$output    .= '<option value="post-' . $post_id . '" selected="selected" >' . $post_title . '</option>';
					}

					// taxonomy options.
					if ( strpos( $sel_value, 'tax-' ) !== false ) {
						$tax_data = explode( '-', $sel_value );

						$tax_id    = (int) str_replace( 'tax-', '', $sel_value );
						$term      = get_term( $tax_id );
						$term_name = '';

						if ( ! is_wp_error( $term ) ) {
							$term_taxonomy = ucfirst( str_replace( '_', ' ', $term->taxonomy ) );

							if ( isset( $tax_data[2] ) && 'single' === $tax_data[2] ) {
								$term_name = 'All singulars from ' . $term->name;
							} else {
								$term_name = $term->name . ' - ' . $term_taxonomy;
							}
						}

						$output .= '<option value="' . $sel_value . '" selected="selected" >' . $term_name . '</option>';
					}
				}
			}
			$output .= '</select>';
			$output .= '</div>';
		}

		$output .= '</div>';

		/* Add new rule */
		$output .= '<div class="rael-hf__add-include-display-condition-wrapper">';
		$output .= '<a href="#" class="button" data-rule-id="' . absint( $index ) . '" data-rule-type="' . $type . '">' . $add_rule_label . '</a>';
		$output .= '</div>';

		if ( 'display' === $type ) {
			/* Add new rule */
			$output .= '<div class="rael-hf__add-exclude-display-condition-wrapper">';
			$output .= '<a href="#" class="button">' . __( 'Add Exclusion Condition', 'responsive-addons-for-elementor' ) . '</a>';
			$output .= '</div>';
		}

		return $output;
	}

	/**
	 * Checks for the display condition for the current page/
	 *
	 * @access public
	 *
	 * @since 1.3.0
	 *
	 * @param  int   $post_id Current post ID.
	 * @param  array $rules   Array of rules Display on | Exclude on.
	 *
	 * @return boolean      Returns true or false depending on if the $rules match for the current page and the layout is to be displayed.
	 */
	public function parse_layout_display_condition( $post_id, $rules ) {
		$display           = false;
		$current_post_type = get_post_type( $post_id );

		if ( isset( $rules['rule'] ) && is_array( $rules['rule'] ) && ! empty( $rules['rule'] ) ) {
			foreach ( $rules['rule'] as $key => $rule ) {
				if ( strrpos( $rule, 'all' ) !== false ) {
					$rule_case = 'all';
				} else {
					$rule_case = $rule;
				}

				switch ( $rule_case ) {
					case 'basic-global':
						$display = true;
						break;

					case 'basic-singulars':
						if ( is_singular() ) {
							$display = true;
						}
						break;

					case 'basic-archives':
						if ( is_archive() ) {
							$display = true;
						}
						break;

					case 'special-404':
						if ( is_404() ) {
							$display = true;
						}
						break;

					case 'special-search':
						if ( is_search() ) {
							$display = true;
						}
						break;

					case 'special-blog':
						if ( is_home() ) {
							$display = true;
						}
						break;

					case 'special-front':
						if ( is_front_page() ) {
							$display = true;
						}
						break;

					case 'special-date':
						if ( is_date() ) {
							$display = true;
						}
						break;

					case 'special-author':
						if ( is_author() ) {
							$display = true;
						}
						break;

					case 'special-woo-shop':
						if ( function_exists( 'is_shop' ) && is_shop() ) {
							$display = true;
						}
						break;

					case 'all':
						$rule_data = explode( '|', $rule );

						$post_type    = isset( $rule_data[0] ) ? $rule_data[0] : false;
						$archive_type = isset( $rule_data[2] ) ? $rule_data[2] : false;
						$taxonomy     = isset( $rule_data[3] ) ? $rule_data[3] : false;
						if ( false === $archive_type ) {
							$current_post_type = get_post_type( $post_id );

							if ( false !== $post_id && $current_post_type == $post_type ) { // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison 
								$display = true;
							}
						} else {
							if ( is_archive() ) {
								$current_post_type = get_post_type();
								if ( $current_post_type == $post_type ) { // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
									if ( 'archive' === $archive_type ) {
										$display = true;
									} elseif ( 'taxarchive' === $archive_type ) {
										$obj              = get_queried_object();
										$current_taxonomy = '';
										if ( '' !== $obj && null !== $obj ) {
											$current_taxonomy = $obj->taxonomy;
										}

										if ( $current_taxonomy == $taxonomy ) { // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
											$display = true;
										}
									}
								}
							}
						}
						break;

					case 'specifics':
						if ( isset( $rules['specific'] ) && is_array( $rules['specific'] ) ) {
							foreach ( $rules['specific'] as $specific_page ) {
								$specific_data = explode( '-', $specific_page );

								$specific_post_type = isset( $specific_data[0] ) ? $specific_data[0] : false;
								$specific_post_id   = isset( $specific_data[1] ) ? $specific_data[1] : false;
								if ( 'post' === $specific_post_type ) {
									if ( $specific_post_id == $post_id ) { // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
										$display = true;
									}
								} elseif ( isset( $specific_data[2] ) && ( 'single' === $specific_data[2] ) && 'tax' === $specific_post_type ) {
									if ( is_singular() ) {
										$term_details = get_term( $specific_post_id );

										if ( isset( $term_details->taxonomy ) ) {
											$has_term = has_term( (int) $specific_post_id, $term_details->taxonomy, $post_id );

											if ( $has_term ) {
												$display = true;
											}
										}
									}
								} elseif ( 'tax' === $specific_post_type ) {
									$tax_id = get_queried_object_id();
									if ( $specific_post_id == $tax_id ) { // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
										$display = true;
									}
								}
							}
						}
						break;

					default:
						break;
				}

				if ( $display ) {
					break;
				}
			}
		}

		return $display;
	}

	/**
	 * Function to handle new input type.
	 *
	 * @access public
	 * @static
	 *
	 * @since 1.3.0
	 *
	 * @param string $name string parameter.
	 * @param string $settings string parameter.
	 * @param string $value string parameter.
	 *
	 * @return void
	 */
	public static function target_user_role_settings_field( $name, $settings, $value ) {
		$input_name     = $name;
		$type           = isset( $settings['type'] ) ? $settings['type'] : 'target_rule';
		$class          = isset( $settings['class'] ) ? $settings['class'] : '';
		$rule_type      = isset( $settings['rule_type'] ) ? $settings['rule_type'] : 'target_rule';
		$add_rule_label = isset( $settings['add_rule_label'] ) ? $settings['add_rule_label'] : __( 'Add Rule', 'responsive-addons-for-elementor' );
		$saved_values   = $value;
		$output         = '';

		if ( ! isset( self::$user_selection ) || empty( self::$user_selection ) ) {
			self::$user_selection = self::get_user_selections();
		}
		$selection_options = self::$user_selection;

		/* WP Template Format */
		$output         .= '<script type="text/html" id="tmpl-rael-hf-user-role-condition">';
			$output     .= '<div class="rael-hf__user-role-condition rael-hf__user-role-{{data.id}}" data-rule="{{data.id}}" >';
				$output .= '<span class="rael-hf__user-role-condition-delete dashicons dashicons-dismiss"></span>';
				/* Condition Selection */
				$output     .= '<div class="rael-hf__user-role-condition-wrapper" >';
					$output .= '<select name="' . esc_attr( $input_name ) . '[{{data.id}}]" class="rael-hf__user-role-condition-input form-control rael-hf-input">';
					$output .= '<option value="">' . __( 'Select', 'responsive-addons-for-elementor' ) . '</option>';

		foreach ( $selection_options as $group => $group_data ) {
			$output .= '<optgroup label="' . $group_data['label'] . '">';
			foreach ( $group_data['value'] as $opt_key => $opt_value ) {
				$output .= '<option value="' . $opt_key . '">' . $opt_value . '</option>';
			}
			$output .= '</optgroup>';
		}
					$output .= '</select>';
				$output     .= '</div>';
			$output         .= '</div> <!-- rael-hf__user-role-condition -->';
		$output             .= '</script>';

		if ( ! is_array( $saved_values ) || ( is_array( $saved_values ) && empty( $saved_values ) ) ) {
			$saved_values    = array();
			$saved_values[0] = '';
		}

		$index = 0;

		$output         .= '<div class="rael-hf__user-role-wrapper rael-hf__user-role-display-on-wrap" data-type="display">';
			$output     .= '<div class="rael-hf__user-role-selector-wrapper rael-hf__user-role-display-on">';
				$output .= '<div class="rael-hf__user-role-builder-wrapper">';
		foreach ( $saved_values as $index => $data ) {
			$output     .= '<div class="rael-hf__user-role-condition rael-hf__user-role-' . $index . '" data-rule="' . $index . '" >';
				$output .= '<span class="rael-hf__user-role-condition-delete dashicons dashicons-dismiss"></span>';
				/* Condition Selection */
				$output     .= '<div class="rael-hf__user-role-condition-wrapper" >';
					$output .= '<select name="' . esc_attr( $input_name ) . '[' . $index . ']" class="rael-hf__user-role-condition-input form-control rael-hf-input">';
					$output .= '<option value="">' . __( 'Select', 'responsive-addons-for-elementor' ) . '</option>';

			foreach ( $selection_options as $group => $group_data ) {
				$output .= '<optgroup label="' . $group_data['label'] . '">';
				foreach ( $group_data['value'] as $opt_key => $opt_value ) {
					$output .= '<option value="' . $opt_key . '" ' . selected( $data, $opt_key, false ) . '>' . $opt_value . '</option>';
				}
				$output .= '</optgroup>';
			}
					$output .= '</select>';
				$output     .= '</div>';
					$output .= '</div> <!-- rael-hf__user-role-condition -->';
		}
				$output .= '</div>';
				/* Add new rule */
				$output .= '<div class="rael-hf__user-add-role-condition-wrapper">';
				$output .= '<a href="#" class="button" data-rule-id="' . absint( $index ) . '">' . $add_rule_label . '</a>';
				$output .= '</div>';
			$output     .= '</div>';
		$output         .= '</div>';

		echo $output; //phpcs:ignore
	}

	/**
	 * Parse user role condition.
	 *
	 * @access public
	 *
	 * @since  1.3.0
	 *
	 * @param  int   $post_id Post ID.
	 * @param  Array $rules   Current user rules.
	 *
	 * @return boolean  True = user condition passes. False = User condition does not pass.
	 */
	public function parse_user_role_condition( $post_id, $rules ) {
		$show_popup = true;

		if ( is_array( $rules ) && ! empty( $rules ) ) {
			$show_popup = false;

			foreach ( $rules as $i => $rule ) {
				switch ( $rule ) {
					case '':
					case 'all':
						$show_popup = true;
						break;

					case 'logged-in':
						if ( is_user_logged_in() ) {
							$show_popup = true;
						}
						break;

					case 'logged-out':
						if ( ! is_user_logged_in() ) {
							$show_popup = true;
						}
						break;

					default:
						if ( is_user_logged_in() ) {
							$current_user = wp_get_current_user();

							if ( isset( $current_user->roles )
									&& is_array( $current_user->roles )
									&& in_array( $rule, $current_user->roles )
								) {
								$show_popup = true;
							}
						}
						break;
				}

				if ( $show_popup ) {
					break;
				}
			}
		}

		return $show_popup;
	}

	/**
	 * Get current page type
	 *
	 * @access public
	 *
	 * @since  1.3.0
	 *
	 * @return string Page Type.
	 */
	public function get_current_page_type() {
		if ( null === self::$current_page_type ) {
			$page_type  = '';
			$current_id = false;

			if ( is_404() ) {
				$page_type = 'is_404';
			} elseif ( is_search() ) {
				$page_type = 'is_search';
			} elseif ( is_archive() ) {
				$page_type = 'is_archive';

				if ( is_category() || is_tag() || is_tax() ) {
					$page_type = 'is_tax';
				} elseif ( is_date() ) {
					$page_type = 'is_date';
				} elseif ( is_author() ) {
					$page_type = 'is_author';
				} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
					$page_type = 'is_woo_shop_page';
				}
			} elseif ( is_home() ) {
				$page_type = 'is_home';
			} elseif ( is_front_page() ) {
				$page_type  = 'is_front_page';
				$current_id = get_the_id();
			} elseif ( is_singular() ) {
				$page_type  = 'is_singular';
				$current_id = get_the_id();
			} else {
				$current_id = get_the_id();
			}

			self::$current_page_data['ID'] = $current_id;
			self::$current_page_type       = $page_type;
		}

		return self::$current_page_type;
	}

	/**
	 * Get posts by conditions
	 *
	 * @access public
	 *
	 * @since  1.3.0
	 *
	 * @param  string $post_type Post Type.
	 * @param  array  $option meta option name.
	 *
	 * @return object  Posts.
	 */
	public function get_posts_by_conditions( $post_type, $option ) {
		global $wpdb;
		global $post;

		$post_type = $post_type ? esc_sql( $post_type ) : esc_sql( $post->post_type );

		if ( is_array( self::$current_page_data ) && isset( self::$current_page_data[ $post_type ] ) ) {
			return apply_filters( 'rael_hf_get_display_posts_by_conditions', self::$current_page_data[ $post_type ], $post_type );
		}

		$current_page_type = $this->get_current_page_type();

		self::$current_page_data[ $post_type ] = array();

		$option['current_post_id'] = self::$current_page_data['ID'];
		$meta_header               = self::get_meta_option_post( $post_type, $option );

		/* Meta option is enabled */
		if ( false === $meta_header ) {
			$current_post_type = esc_sql( get_post_type() );
			$current_post_id   = false;
			$q_obj             = get_queried_object();

			$location = isset( $option['location'] ) ? esc_sql( $option['location'] ) : '';

			$query = "SELECT p.ID, pm.meta_value FROM {$wpdb->postmeta} as pm
						INNER JOIN {$wpdb->posts} as p ON pm.post_id = p.ID
						WHERE pm.meta_key = '{$location}'
						AND p.post_type = '{$post_type}'
						AND p.post_status = 'publish'";

			$orderby = ' ORDER BY p.post_date DESC';

			/* Entire Website */
			$meta_args = "pm.meta_value LIKE '%\"basic-global\"%'";

			switch ( $current_page_type ) {
				case 'is_404':
					$meta_args .= " OR pm.meta_value LIKE '%\"special-404\"%'";
					break;
				case 'is_search':
					$meta_args .= " OR pm.meta_value LIKE '%\"special-search\"%'";
					break;
				case 'is_archive':
				case 'is_tax':
				case 'is_date':
				case 'is_author':
					$meta_args .= " OR pm.meta_value LIKE '%\"basic-archives\"%'";
					if ( 'post' !== $current_post_type || 'page' !== $current_post_type ) $meta_args .= " OR pm.meta_value LIKE '%\"{$current_post_type}|all|archive\"%'";

					if ( 'is_tax' === $current_page_type && ( is_category() || is_tag() || is_tax() ) ) {
						if ( is_object( $q_obj ) ) {
							$meta_args .= " OR pm.meta_value LIKE '%\"{$current_post_type}|all|taxarchive|{$q_obj->taxonomy}\"%'";
							$meta_args .= " OR pm.meta_value LIKE '%\"tax-{$q_obj->term_id}\"%'";
						}
					} elseif ( 'is_date' === $current_page_type ) {
						$meta_args .= " OR pm.meta_value LIKE '%\"special-date\"%'";
					} elseif ( 'is_author' === $current_page_type ) {
						$meta_args .= " OR pm.meta_value LIKE '%\"special-author\"%'";
					}
					break;
				case 'is_home':
					$meta_args .= " OR pm.meta_value LIKE '%\"special-blog\"%'";
					$meta_args .= " OR pm.meta_value LIKE '%\"basic-archives\"%'";
					$meta_args .= " OR pm.meta_value LIKE '%\"post|all|archive\"%'";
					break;
				case 'is_front_page':
					$current_id      = esc_sql( get_the_id() );
					$current_post_id = $current_id;
					$meta_args      .= " OR pm.meta_value LIKE '%\"special-front\"%'";
					$meta_args      .= " OR pm.meta_value LIKE '%\"{$current_post_type}|all\"%'";
					$meta_args      .= " OR pm.meta_value LIKE '%\"post-{$current_id}\"%'";
					break;
				case 'is_singular':
					$current_id      = esc_sql( get_the_id() );
					$current_post_id = $current_id;
					$meta_args      .= " OR pm.meta_value LIKE '%\"basic-singulars\"%'";
					$meta_args      .= " OR pm.meta_value LIKE '%\"{$current_post_type}|all\"%'";
					$meta_args      .= " OR pm.meta_value LIKE '%\"post-{$current_id}\"%'";

					$taxonomies = get_object_taxonomies( $q_obj->post_type );
					$terms      = wp_get_post_terms( $q_obj->ID, $taxonomies );

					foreach ( $terms as $key => $term ) {
						$meta_args .= " OR pm.meta_value LIKE '%\"tax-{$term->term_id}-single-{$term->taxonomy}\"%'";
					}

					break;
				case 'is_woo_shop_page':
					$meta_args .= " OR pm.meta_value LIKE '%\"special-woo-shop\"%'";
					$meta_args .= " OR pm.meta_value LIKE '%\"product|all|archive\"%'";
					break;
				case '':
					$current_post_id = get_the_id();
					break;
			}

			// Ignore the PHPCS warning about constant declaration.
			// @codingStandardsIgnoreStart
			$posts  = $wpdb->get_results( $query . ' AND (' . $meta_args . ')' . $orderby );
			// @codingStandardsIgnoreEnd

			foreach ( $posts as $local_post ) {
				self::$current_page_data[ $post_type ][ $local_post->ID ] = array(
					'id'       => $local_post->ID,
					'location' => maybe_unserialize( $local_post->meta_value ),
				);
			}

			$option['current_post_id'] = $current_post_id;

			$this->remove_exclusion_rule_posts( $post_type, $option );
			$this->remove_user_rule_posts( $post_type, $option );
		}

		return apply_filters( 'rael_hf_get_display_posts_by_conditions', self::$current_page_data[ $post_type ], $post_type );
	}

	/**
	 * Remove exclusion rule posts.
	 *
	 * @access public
	 *
	 * @since  1.3.0
	 *
	 * @param  string $post_type Post Type.
	 * @param  array  $option meta option name.
	 *
	 * @return void
	 */
	public function remove_exclusion_rule_posts( $post_type, $option ) {
		$exclusion       = isset( $option['exclusion'] ) ? $option['exclusion'] : '';
		$current_post_id = isset( $option['current_post_id'] ) ? $option['current_post_id'] : false;

		foreach ( self::$current_page_data[ $post_type ] as $c_post_id => $c_data ) {
			$exclusion_rules = get_post_meta( $c_post_id, $exclusion, true );
			$is_exclude      = $this->parse_layout_display_condition( $current_post_id, $exclusion_rules );

			if ( $is_exclude ) {
				unset( self::$current_page_data[ $post_type ][ $c_post_id ] );
			}
		}
	}

	/**
	 * Remove user rule posts.
	 *
	 * @access public
	 *
	 * @since  1.3.0
	 *
	 * @param  int   $post_type Post Type.
	 * @param  array $option meta option name.
	 *
	 * @return void
	 */
	public function remove_user_rule_posts( $post_type, $option ) {
		$users           = isset( $option['users'] ) ? $option['users'] : '';
		$current_post_id = isset( $option['current_post_id'] ) ? $option['current_post_id'] : false;

		foreach ( self::$current_page_data[ $post_type ] as $c_post_id => $c_data ) {
			$user_rules = get_post_meta( $c_post_id, $users, true );
			$is_user    = $this->parse_user_role_condition( $current_post_id, $user_rules );

			if ( ! $is_user ) {
				unset( self::$current_page_data[ $post_type ][ $c_post_id ] );
			}
		}
	}

	/**
	 * Meta option post.
	 *
	 * @access public
	 * @static
	 *
	 * @since  1.3.0
	 *
	 * @param  string $post_type Post Type.
	 * @param  array  $option meta option name.
	 *
	 * @return false | object
	 */
	public static function get_meta_option_post( $post_type, $option ) {
		$page_meta = ( isset( $option['page_meta'] ) && '' != $option['page_meta'] ) ? $option['page_meta'] : false; // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison

		if ( false !== $page_meta ) {
			$current_post_id = isset( $option['current_post_id'] ) ? $option['current_post_id'] : false;
			$meta_id         = get_post_meta( $current_post_id, $option['page_meta'], true );

			if ( false !== $meta_id && '' != $meta_id ) { // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
				self::$current_page_data[ $post_type ][ $meta_id ] = array(
					'id'       => $meta_id,
					'location' => '',
				);

				return self::$current_page_data[ $post_type ];
			}
		}

		return false;
	}

	/**
	 * Formated rule meta value to save.
	 *
	 * @access public
	 * @static
	 *
	 * @since  1.3.0
	 *
	 * @param  array  $save_data PostData.
	 * @param  string $key varaible key.
	 *
	 * @return array Rule data.
	 */
	public static function get_format_rule_value( $save_data, $key ) {
		$meta_value = array();

		if ( isset( $save_data[ $key ]['rule'] ) ) {
			$save_data[ $key ]['rule'] = array_unique( $save_data[ $key ]['rule'] );
			if ( isset( $save_data[ $key ]['specific'] ) ) {
				$save_data[ $key ]['specific'] = array_unique( $save_data[ $key ]['specific'] );
			}

			// Unset the specifics from rule. This will be readded conditionally in next condition.
			$index = array_search( '', $save_data[ $key ]['rule'] );
			if ( false !== $index ) {
				unset( $save_data[ $key ]['rule'][ $index ] );
			}
			$index = array_search( 'specifics', $save_data[ $key ]['rule'] );
			if ( false !== $index ) {
				unset( $save_data[ $key ]['rule'][ $index ] );

				// Only re-add the specifics key if there are specific rules added.
				if ( isset( $save_data[ $key ]['specific'] ) && is_array( $save_data[ $key ]['specific'] ) ) {
					array_push( $save_data[ $key ]['rule'], 'specifics' );
				}
			}

			foreach ( $save_data[ $key ] as $meta_key => $value ) {
				if ( ! empty( $value ) ) {
					$meta_value[ $meta_key ] = array_map( 'esc_attr', $value );
				}
			}
			if ( ! isset( $meta_value['rule'] ) || ! in_array( 'specifics', $meta_value['rule'] ) ) {
				$meta_value['specific'] = array();
			}

			if ( empty( $meta_value['rule'] ) ) {
				$meta_value = array();
			}
		}

		return $meta_value;
	}
}


RAEL_Conditions::instance();
