<?php
/**
 * RAEL Module.
 *
 * @package  Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Modules\SingleQueryControl;

use Elementor\Plugin;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\QueryControl\Module as QueryControlModule;
use Elementor\TemplateLibrary\Source_Local;

/**
 * Class Module
 *
 * @package Responsive_Addons_For_Elementor\WidgetsManager
 */
class Module extends QueryControlModule {
	const QUERY_CONTROL_ID        = 'rael-single-query';
	const AUTOCOMPLETE_ERROR_CODE = 'QueryControlAutocomplete';
	const GET_TITLES_ERROR_CODE   = 'QueryControlGetTitles';

	// Supported objects for query.
	const QUERY_OBJECT_POST             = 'post';
	const QUERY_OBJECT_TAX              = 'tax';
	const QUERY_OBJECT_AUTHOR           = 'author';
	const QUERY_OBJECT_USER             = 'user';
	const QUERY_OBJECT_LIBRARY_TEMPLATE = 'library_template';
	const QUERY_OBJECT_ATTACHMENT       = 'attachment';

	// Objects that are manipulated by js (not sent in AJAX).
	const QUERY_OBJECT_CPT_TAX = 'cpt_tax';
	const QUERY_OBJECT_JS      = 'js';

	/**
	 * Displayed IDs
	 *
	 * @var array
	 */
	public static $displayed_ids = array();

	/**
	 * Displayed IDs
	 *
	 * @var array
	 * @access private
	 */
	private static $supported_objects_for_query = array(
		self::QUERY_OBJECT_POST,
		self::QUERY_OBJECT_TAX,
		self::QUERY_OBJECT_AUTHOR,
		self::QUERY_OBJECT_USER,
		self::QUERY_OBJECT_LIBRARY_TEMPLATE,
		self::QUERY_OBJECT_ATTACHMENT,
	);

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->add_actions();
	}

	/**
	 * AutoComplete Query For Post.
	 *
	 * @param array $data Data.
	 */
	private function autocomplete_query_for_post( $data ) {
		if ( ! isset( $data['autocomplete']['query'] ) ) {
			return new \WP_Error( self::AUTOCOMPLETE_ERROR_CODE, 'Missing autocomplete[`query`] data' );
		}
		$query = $data['autocomplete']['query'];
		if ( empty( $query['post_type'] ) ) {
			$query['post_type'] = 'any';
		}
		$query['posts_per_page'] = -1;
		$query['s']              = $data['q'];

		return $query;
	}

	/**
	 * AutoComplete Query For Post.
	 *
	 * @param array $data Data.
	 * @access private
	 */
	private function autocomplete_query_for_library_template( $data ) {
		$query = $data['autocomplete']['query'];

		$query['post_type'] = Source_Local::CPT;
		$query['orderby']   = 'meta_value';
		$query['order']     = 'ASC';

		if ( empty( $query['posts_per_page'] ) ) {
			$query['posts_per_page'] = -1;
		}
		$query['s'] = $data['q'];

		return $query;
	}

	/**
	 * AutoComplete Query For Attachment.
	 *
	 * @param array $data Data.
	 * @access private
	 */
	private function autocomplete_query_for_attachment( $data ) {
		$query = $this->autocomplete_query_for_post( $data );
		if ( is_wp_error( $query ) ) {
			return $query;
		}
		$query['post_type']   = 'attachment';
		$query['post_status'] = 'inherit';

		return $query;
	}

	/**
	 * AutoComplete Query For Post.
	 *
	 * @param array $data Data.
	 * @access private
	 */
	private function autocomplete_query_for_tax( $data ) {
		$query = $data['autocomplete']['query'];

		if ( empty( $query['taxonomy'] ) && ! empty( $query['post_type'] ) ) {
			$query['taxonomy'] = get_object_taxonomies( $query['post_type'] );
		}
		$query['search']     = $data['q'];
		$query['hide_empty'] = false;
		return $query;
	}

	/**
	 * AutoComplete Query For Author.
	 *
	 * @param array $data Data.
	 * @access private
	 */
	private function autocomplete_query_for_author( $data ) {
		$query = $this->autocomplete_query_for_user( $data );
		if ( is_wp_error( $query ) ) {
			return $query;
		}
		$query['who'] = 'authors';
		return $query;
	}

	/**
	 * AutoComplete Query For User.
	 *
	 * @param array $data Data.
	 * @access private
	 */
	private function autocomplete_query_for_user( $data ) {
		$query = $data['autocomplete']['query'];
		if ( ! empty( $query ) ) {
			return $query;
		}

		$query = array(
			'fields'         => array(
				'ID',
				'display_name',
			),
			'search'         => '*' . $data['q'] . '*',
			'search_columns' => array(
				'user_login',
				'user_nicename',
			),
		);
		if ( 'detailed' === $data['autocomplete']['display'] ) {
			$query['fields'][] = 'user_email';
		}
		return $query;
	}

	/**
	 * Get Title Query For Post.
	 *
	 * @param array $data Data.
	 * @access private
	 */
	private function get_titles_query_for_post( $data ) {
		$query = $data['get_titles']['query'];
		if ( empty( $query['post_type'] ) ) {
			$query['post_type'] = 'any';
		}
		$query['posts_per_page'] = -1;
		$query['post__in']       = (array) $data['id'];

		return $query;
	}

	/**
	 * Get Titles Query For Attachment.
	 *
	 * @param array $data Data.
	 * @access private
	 */
	private function get_titles_query_for_attachment( $data ) {
		$query                = $this->get_titles_query_for_post( $data );
		$query['post_type']   = 'attachment';
		$query['post_status'] = 'inherit';

		return $query;
	}

	/**
	 * Get Title Query For Tax.
	 *
	 * @param array $data Data.
	 * @access private
	 */
	private function get_titles_query_for_tax( $data ) {
		$by_field = empty( $data['get_titles']['by_field'] ) ? 'term_taxonomy_id' : $data['get_titles']['by_field'];
		return array(
			$by_field    => (array) $data['id'],
			'hide_empty' => false,
		);
	}

	/**
	 * Get Titles Query For Library Template.
	 *
	 * @param array $data Data.
	 * @access private
	 */
	private function get_titles_query_for_library_template( $data ) {
		$query = $data['get_titles']['query'];

		$query['post_type'] = Source_Local::CPT;
		$query['orderby']   = 'meta_value';
		$query['order']     = 'ASC';

		if ( empty( $query['posts_per_page'] ) ) {
			$query['posts_per_page'] = -1;
		}

		return $query;
	}

	/**
	 * Get Titles Query For Author.
	 *
	 * @param array $data Data.
	 * @access private
	 */
	private function get_titles_query_for_author( $data ) {
		$query                        = $this->get_titles_query_for_user( $data );
		$query['who']                 = 'authors';
		$query['has_published_posts'] = true;
		return $query;
	}

	/**
	 * Get Titles Query For Post.
	 *
	 * @param array $data Data.
	 * @access private
	 */
	private function get_titles_query_for_user( $data ) {
		$query = $data['get_titles']['query'];
		if ( ! empty( $query ) ) {
			return $query;
		}
		$query = array(
			'fields'  => array(
				'ID',
				'display_name',
			),
			'include' => (array) $data['id'],
		);
		if ( 'detailed' === $data['get_titles']['display'] ) {
			$query['fields'][] = 'user_email';
		}
		return $query;
	}

	/**
	 * AutoComplete Query Data.
	 *
	 * @param array $data Data.
	 * @access private
	 */
	private function autocomplete_query_data( $data ) {
		if ( empty( $data['autocomplete'] ) || empty( $data['q'] ) || empty( $data['autocomplete']['object'] ) ) {
			return new \WP_Error( self::AUTOCOMPLETE_ERROR_CODE, 'Empty or incomplete data' );
		}

		$autocomplete = $data['autocomplete'];

		if ( in_array( $autocomplete['object'], self::$supported_objects_for_query, true ) ) {
			$method_name = 'autocomplete_query_for_' . $autocomplete['object'];
			if ( empty( $autocomplete['display'] ) ) {
				$autocomplete['display'] = 'minimal';
				$data['autocomplete']    = $autocomplete;
			}
			$query = $this->$method_name( $data );
			if ( is_wp_error( $query ) ) {
				return $query;
			}
			$autocomplete['query'] = $query;
		}

		return $autocomplete;
	}

	/**
	 * Get Term Name With Parents.
	 *
	 * @param \WP_Term $term Term Object.
	 * @param integer  $max  Maximum.
	 * @access private
	 */
	private function get_term_name_with_parents( \WP_Term $term, $max = 3 ) {
		if ( 0 === $term->parent ) {
			return $term->name;
		}
		$separator = is_rtl() ? ' < ' : ' > ';
		$test_term = $term;
		$names     = array();
		while ( $test_term->parent > 0 ) {
			$test_term = get_term( $test_term->parent );
			if ( ! $test_term ) {
				break;
			}
			$names[] = $test_term->name;
		}

		$names = array_reverse( $names );
		if ( count( $names ) < ( $max ) ) {
			return implode( $separator, $names ) . $separator . $term->name;
		}

		$name_string = '';
		for ( $i = 0; $i < ( $max - 1 ); $i++ ) {
			$name_string .= $names[ $i ] . $separator;
		}
		return $name_string . '...' . $separator . $term->name;
	}

	/**
	 * Get Term Name.
	 *
	 * @param \WP_Term $term Term.
	 * @param string   $display Display Condition.
	 * @param string   $request Request.
	 * @param string   $filter_name Filter Name.
	 * @access private
	 */
	private function get_term_name( $term, $display, $request, $filter_name = 'get_autocomplete' ) {
		global $wp_taxonomies;
		$term_name = $this->get_term_name_with_parents( $term );
		switch ( $display ) {
			case 'detailed':
				$text = $wp_taxonomies[ $term->taxonomy ]->labels->name . ': ' . $term_name;
				break;
			case 'minimal':
				$text = $term_name;
				break;
			default:
				$text = apply_filters( "rael_core_elements_single_query_control_{$filter_name}_display_{$display}", $term_name, $request );
				break;
		}
		return $text;
	}

	/**
	 * Get Post Name With Parents.
	 *
	 * @param WP_Post $post Data.
	 * @param integer $max Maximum.
	 * @access private
	 */
	private function get_post_name_with_parents( $post, $max = 3 ) {
		if ( 0 === $post->post_parent ) {
			return $post->post_title;
		}
		$separator = is_rtl() ? ' < ' : ' > ';
		$test_post = $post;
		$names     = array();
		while ( $test_post->post_parent > 0 ) {
			$test_post = get_post( $test_post->post_parent );
			if ( ! $test_post ) {
				break;
			}
			$names[] = $test_post->post_title;
		}

		$names = array_reverse( $names );
		if ( count( $names ) < ( $max ) ) {
			return implode( $separator, $names ) . $separator . $post->post_title;
		}

		$name_string = '';
		for ( $i = 0; $i < ( $max - 1 ); $i++ ) {
			$name_string .= $names[ $i ] . $separator;
		}
		return $name_string . '...' . $separator . $post->post_title;
	}

	/**
	 * Format Post for Display.
	 *
	 * @param WP_Post $post Post.
	 * @param string  $display Display Condition.
	 * @param string  $data Data.
	 * @param string  $filter_name Filter Name.
	 * @access private
	 */
	private function format_post_for_display( $post, $display, $data, $filter_name = 'get_autocomplete' ) {
		$post_type_obj = get_post_type_object( $post->post_type );
		switch ( $display ) {
			case 'minimal':
				$text = ( $post_type_obj->hierarchical ) ? $this->get_post_name_with_parents( $post ) : $post->post_title;
				break;
			case 'detailed':
				$text = $post_type_obj->labels->name . ': ' . ( $post_type_obj->hierarchical ) ? $this->get_post_name_with_parents( $post ) : $post->post_title;
				break;
			default:
				$text = apply_filters( "rael_core_elements_single_query_control_{$filter_name}_display_{$display}", $post->post_title, $post->ID, $data );
				break;
		}

		return esc_html( $text );
	}

	/**
	 * Format User for Display.
	 *
	 * @param WP_User $user User Object.
	 * @param string  $display Display Condition.
	 * @param string  $data Data.
	 * @param string  $filter_name Filter Name.
	 * @access private
	 */
	private function format_user_for_display( $user, $display, $data, $filter_name = 'get_autocomplete' ) {
		switch ( $display ) {
			case 'minimal':
				$text = $user->display_name;
				break;
			case 'detailed':
				$text = sprintf( '%s (%s)', $user->display_name, $user->user_email );
				break;
			default:
				$text = apply_filters( "rael_core_elements_single_query_control_{$filter_name}_display_{$display}", $user, $data );
				break;
		}

		return $text;
	}

	/**
	 * Format Post for Display.
	 *
	 * @param string $data Data.
	 * @access private
	 */
	private function get_titles_query_data( $data ) {
		if ( empty( $data['get_titles'] ) || empty( $data['id'] ) || empty( $data['get_titles']['object'] ) ) {
			return new \WP_Error( self::GET_TITLES_ERROR_CODE, 'Empty or incomplete data' );
		}

		$get_titles = $data['get_titles'];
		if ( empty( $get_titles['query'] ) ) {
			$get_titles['query'] = array();
		}

		if ( in_array( $get_titles['object'], self::$supported_objects_for_query, true ) ) {
			$method_name = 'get_titles_query_for_' . $get_titles['object'];
			$query       = $this->$method_name( $data );
			if ( is_wp_error( $query ) ) {
				return $query;
			}
			$get_titles['query'] = $query;
		}

		if ( empty( $get_titles['display'] ) ) {
			$get_titles['display'] = 'minimal';
		}

		return $get_titles;
	}

	/**
	 * Ajax Posts Control Value Titles.
	 *
	 * @param string $request Request.
	 * @access public
	 */
	public function ajax_posts_control_value_titles( $request ) {
		$query_data = $this->get_titles_query_data( $request );
		if ( is_wp_error( $query_data ) ) {
			return array();
		}
		$display                     = $query_data['display'];
		$query_args                  = $query_data['query'];
		$query_args['no_found_rows'] = true;

		$results = array();
		switch ( $query_data['object'] ) {
			case self::QUERY_OBJECT_TAX:
				$by_field = ! empty( $query_data['by_field'] ) ? $query_data['by_field'] : 'term_taxonomy_id';
				$terms    = get_terms( $query_args );

				if ( is_wp_error( $terms ) ) {
					break;
				}
				foreach ( $terms as $term ) {
					if ( apply_filters( "rael_core_elements_single_query_control_get_value_titles_tax_{$display}", true, $term, $request ) ) {
						$results[ $term->{$by_field} ] = $this->get_term_name( $term, $display, $request, 'get_value_titles' );
					}
				}
				break;

			case self::QUERY_OBJECT_ATTACHMENT:
			case self::QUERY_OBJECT_POST:
				$query = new \WP_Query( $query_args );

				foreach ( $query->posts as $post ) {
					if ( apply_filters( "rael_core_elements_single_query_control_get_value_titles_custom_{$display}", true, $post, $request ) ) {
						$results[ $post->ID ] = $this->format_post_for_display( $post, $display, $request, 'get_value_titles' );
					}
				}
				break;
			case self::QUERY_OBJECT_LIBRARY_TEMPLATE:
				$query = new \WP_Query( $query_args );

				foreach ( $query->posts as $post ) {
					$document = Plugin::$instance->documents->get( $post->ID );
					if ( $document ) {
						$results[ $post->ID ] = esc_html( $post->post_title ) . ' (' . $document->get_post_type_title() . ')';
					}
				}
				break;
			case self::QUERY_OBJECT_AUTHOR:
			case self::QUERY_OBJECT_USER:
				$user_query = new \WP_User_Query( $query_args );

				foreach ( $user_query->get_results() as $user ) {
					if ( apply_filters( "rael_core_elements_single_query_control_get_value_titles_user_{$display}", true, $user, $request ) ) {
						$results[ $user->ID ] = $this->format_user_for_display( $user, $display, $request, 'get_value_titles' );
					}
				}
				break;
			default:
				$results = apply_filters( "rael_core_elements_single_query_control_get_value_titles_{$query_data['filter_type']}", $results, $request );
		}

		return $results;
	}

	/**
	 * Ajax Posts Control Value Titles.
	 *
	 * @param array $data Request.
	 * @access public
	 * @throws \Exception Throws Exception.
	 */
	public function ajax_posts_filter_autocomplete( array $data ) {
		$query_data = $this->autocomplete_query_data( $data );

		if ( is_wp_error( $query_data ) ) {
			// Throw an exception with error code and message from $query_data.
			throw new \Exception( esc_html( $query_data->get_code() ) . ':' . esc_html( $query_data->get_message() ) );
		}

		$results                     = array();
		$display                     = $query_data['display'];
		$query_args                  = $query_data['query'];
		$query_args['no_found_rows'] = true;

		switch ( $query_data['object'] ) {
			case self::QUERY_OBJECT_TAX:
				$by_field = ! empty( $query_data['by_field'] ) ? $query_data['by_field'] : 'term_taxonomy_id';
				$terms    = get_terms( $query_args );
				if ( is_wp_error( $terms ) ) {
					break;
				}
				foreach ( $terms as $term ) {
					if ( apply_filters( "rael_core_elements_single_query_control_get_autocomplete_tax_{$display}", true, $term, $data ) ) {
						$results[] = array(
							'id'   => $term->{$by_field},
							'text' => $this->get_term_name( $term, $display, $data ),
						);
					}
				}
				break;
			case self::QUERY_OBJECT_ATTACHMENT:
			case self::QUERY_OBJECT_POST:
				$query = new \WP_Query( $query_args );

				foreach ( $query->posts as $post ) {
					if ( apply_filters( "rael_core_elements_single_query_control_get_autocomplete_custom_{$display}", true, $post, $data ) ) {
						$text      = $this->format_post_for_display( $post, $display, $data );
						$results[] = array(
							'id'   => $post->ID,
							'text' => $text,
						);
					}
				}
				break;
			case self::QUERY_OBJECT_LIBRARY_TEMPLATE:
				$query = new \WP_Query( $query_args );

				foreach ( $query->posts as $post ) {
					$document = Plugin::$instance->documents->get( $post->ID );
					if ( $document ) {
						$text      = esc_html( $post->post_title ) . ' (' . $document->get_post_type_title() . ')';
						$results[] = array(
							'id'   => $post->ID,
							'text' => $text,
						);
					}
				}
				break;
			case self::QUERY_OBJECT_USER:
			case self::QUERY_OBJECT_AUTHOR:
				$user_query = new \WP_User_Query( $query_args );

				foreach ( $user_query->get_results() as $user ) {
					if ( apply_filters( "rael_core_elements_single_query_control_get_autocomplete_user_{$display}", true, $user, $data ) ) {
						$results[] = array(
							'id'   => $user->ID,
							'text' => $this->format_user_for_display( $user, $display, $data ),
						);
					}
				}
				break;
			default:
				$results = apply_filters( 'rael_core_elements_single_query_control_get_autocomplete_' . $query_data['filter_type'], $results, $data );
		}

		return array(
			'results' => $results,
		);

	}

	/**
	 * RAEL Register Single Query Controls.
	 *
	 * @access public
	 */
	public function register_rael_single_query_controls() {
		$controls_manager = Plugin::instance()->controls_manager;

		$controls = array(
			'rael-single-query' => array(
				'file'  => __DIR__ . '/controls/class-singlequery.php',
				'class' => 'Controls\SingleQuery',
				'type'  => 'single',
			),
		);

		foreach ( $controls as $control_type => $control_info ) {
			if ( ! empty( $control_info['file'] ) && ! empty( $control_info['class'] ) ) {
				include_once $control_info['file'];

				if ( class_exists( $control_info['class'] ) ) {
					$class_name = $control_info['class'];
				} elseif ( class_exists( __NAMESPACE__ . '\\' . $control_info['class'] ) ) {
					$class_name = __NAMESPACE__ . '\\' . $control_info['class'];
				}
				$controls_manager->register( new $class_name() );
			}
		}
	}

	/**
	 * RAEL Register Ajax Single Query Actions.
	 *
	 * @param object $ajax_manager Ajax Manager.
	 * @access public
	 */
	public function register_ajax_rael_single_query_actions( $ajax_manager ) {
		$ajax_manager->register_ajax_action( 'rael_single_query_control_value_titles', array( $this, 'ajax_posts_control_value_titles' ) );
		$ajax_manager->register_ajax_action( 'rael_single_query_panel_posts_control_filter_autocomplete', array( $this, 'ajax_posts_filter_autocomplete' ) );
	}

	/**
	 * Format Post for Display.
	 *
	 * @access protected
	 */
	protected function add_actions() {
		add_action( 'elementor/ajax/register_actions', array( $this, 'register_ajax_rael_single_query_actions' ) );
		add_action( 'elementor/controls/register', array( $this, 'register_rael_single_query_controls' ) );

		add_filter( 'elementor_pro/editor/localize_settings', array( $this, 'localize_settings' ) );

		/**
		 * Making Custom Queries using Offset and Pagination
		 *
		 * @see https://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
		 */
		add_action( 'pre_get_posts', array( $this, 'fix_query_offset' ), 1 );
		add_filter( 'found_posts', array( $this, 'fix_query_found_posts' ), 1, 2 );
	}
}
