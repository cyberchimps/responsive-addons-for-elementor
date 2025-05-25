<?php
/**
 * RAEL Module.
 *
 * @package  Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Modules\QueryControl;

use Elementor\Controls_Manager;
use Elementor\Core\Common\Modules\Ajax\Module as Ajax;
use Elementor\Widget_Base;
use Elementor\Core\Base\Module as Module_Base;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\QueryControl\Controls\Group_Control_Posts;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\QueryControl\Controls\Group_Control_Query;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\QueryControl\Controls\Group_Control_Related;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\QueryControl\Classes\Elementor_Post_Query;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\QueryControl\Classes\Elementor_Related_Query;
use Responsive_Addons_For_Elementor\WidgetsManager\Modules\QueryControl\Controls\Query;
use Elementor\Plugin;
use Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Module
 *
 * @package Responsive_Addons_For_Elementor\WidgetsManager
 */
class Module extends Module_Base {

	const QUERY_CONTROL_ID = 'rael-query';

	const QUERY_OBJECT_POST = 'post';

	/**
	 * Displayed IDs
	 *
	 * @var array
	 */
	public static $displayed_ids = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->add_actions();
	}

	/**
	 * Add to Avoid List.
	 *
	 * @param array $ids Ids.
	 */
	public static function add_to_avoid_list( $ids ) {
		self::$displayed_ids = array_merge( self::$displayed_ids, $ids );
	}

	/**
	 * Get Avoid List Ids.
	 */
	public static function get_avoid_list_ids() {
		return self::$displayed_ids;
	}

	/**
	 * Add Exclude Controls
	 *
	 * @param object $widget Widget.
	 */
	public static function add_exclude_controls( $widget ) {

		$widget->add_control(
			'exclude',
			array(
				'label'       => __( 'Exclude', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => array(
					'current_post'     => __( 'Current Post', 'responsive-addons-for-elementor' ),
					'manual_selection' => __( 'Manual Selection', 'responsive-addons-for-elementor' ),
				),
				'label_block' => true,
			)
		);

		$widget->add_control(
			'exclude_ids',
			array(
				'label'       => __( 'Search & Select', 'responsive-addons-for-elementor' ),
				'type'        => 'rael-query',
				'post_type'   => '',
				'options'     => array(),
				'label_block' => true,
				'multiple'    => true,
				'filter_type' => 'by_id',
				'condition'   => array(
					'exclude' => 'manual_selection',
				),
			)
		);

		$widget->add_control(
			'avoid_duplicates',
			array(
				'label'       => __( 'Avoid Duplicates', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => __( 'Set to Yes to avoid duplicate posts from showing up on the page. This only affects the frontend.', 'responsive-addons-for-elementor' ),
			)
		);

	}

	/**
	 * Get Module Name.
	 */
	public function get_name() {
		return 'query-control';
	}

	/**
	 * Add to Avoid List.
	 *
	 * @param array $query_params Query Parameters.
	 * @param array $data Query Data.
	 */
	private function search_taxonomies( $query_params, $data ) {
		$by_field = $this->extract_term_by_field( $data );
		$terms    = get_terms( $query_params );

		global $wp_taxonomies;

		$results = array();

		foreach ( $terms as $term ) {
			$term_name = $this->get_term_name_with_parents( $term );
			if ( ! empty( $data['include_type'] ) ) {
				$text = $wp_taxonomies[ $term->taxonomy ]->labels->name . ': ' . $term_name;
			} else {
				$text = $term_name;
			}

			$results[] = array(
				'id'   => $term->{$by_field},
				'text' => $text,
			);
		}

		return $results;

	}

	/**
	 * Extract Term By Field
	 *
	 * @param array $data Field Data.
	 * @return string
	 */
	private function extract_term_by_field( $data ) {
		if ( ! empty( $data['query'] ) && ! empty( $data['query']['by_field'] ) ) {
			return $data['query']['by_field'];
		}

		return 'term_taxonomy_id';
	}

	/**
	 * Ajax Post Filter AutoComplete.
	 *
	 * @param array $data Filter Data.
	 *
	 * @return array
	 * @throws \Exception Bad Request.
	 */
	public function ajax_posts_filter_autocomplete( array $data ) {
		if ( empty( $data['filter_type'] ) || empty( $data['q'] ) ) {
			throw new \Exception( 'Bad Request' );
		}

		$results = array();

		switch ( $data['filter_type'] ) {
			case 'taxonomy':
				$query_params = array(
					'taxonomy'   => $this->extract_post_type( $data ),
					'search'     => $data['q'],
					'hide_empty' => false,
				);

				$results = $this->search_taxonomies( $query_params, $data );

				break;

			case 'cpt_taxonomies':
				$post_type = $this->extract_post_type( $data );

				$taxonomies = get_object_taxonomies( $post_type );

				$query_params = array(
					'taxonomy'   => $taxonomies,
					'search'     => $data['q'],
					'hide_empty' => false,
				);

				$results = $this->search_taxonomies( $query_params, $data );

				break;

			case 'by_id':
			case 'post':
				$query_params = array(
					'post_type'      => $this->extract_post_type( $data ),
					's'              => $data['q'],
					'posts_per_page' => -1,
				);

				if ( 'attachment' === $query_params['post_type'] ) {
					$query_params['post_status'] = 'inherit';
				}

				$query = new \WP_Query( $query_params );

				foreach ( $query->posts as $post ) {
					$post_type_obj = get_post_type_object( $post->post_type );
					if ( ! empty( $data['include_type'] ) ) {
						$text = $post_type_obj->labels->name . ': ' . $post->post_title;
					} else {
						$text = ( $post_type_obj->hierarchical ) ? $this->get_post_name_with_parents( $post ) : $post->post_title;
					}

					$results[] = array(
						'id'   => $post->ID,
						'text' => $text,
					);
				}
				break;

			case 'author':
				$query_params = array(
					'who'                 => 'authors',
					'has_published_posts' => true,
					'fields'              => array(
						'ID',
						'display_name',
					),
					'search'              => '*' . $data['q'] . '*',
					'search_columns'      => array(
						'user_login',
						'user_nicename',
					),
				);

				$user_query = new \WP_User_Query( $query_params );

				foreach ( $user_query->get_results() as $author ) {
					$results[] = array(
						'id'   => $author->ID,
						'text' => $author->display_name,
					);
				}
				break;
			default:
				$results = apply_filters( 'rael_core_elements_query_control_get_autocomplete_' . $data['filter_type'], array(), $data );
		}

		return array(
			'results' => $results,
		);
	}

	/**
	 * Ajax Posts Control Value Titles.
	 *
	 * @param array $request Request Ids.
	 */
	public function ajax_posts_control_value_titles( $request ) {
		$ids = (array) $request['id'];

		$results = array();

		switch ( $request['filter_type'] ) {
			case 'cpt_taxonomies':
			case 'taxonomy':
				$by_field = $this->extract_term_by_field( $request );
				$terms    = get_terms(
					array(
						$by_field    => $ids,
						'hide_empty' => false,
					)
				);

				global $wp_taxonomies;
				foreach ( $terms as $term ) {
					$term_name = $this->get_term_name_with_parents( $term );
					if ( ! empty( $request['include_type'] ) ) {
						$text = $wp_taxonomies[ $term->taxonomy ]->labels->name . ': ' . $term_name;
					} else {
						$text = $term_name;
					}
					$results[ $term->{$by_field} ] = $text;
				}
				break;

			case 'by_id':
			case 'post':
				$query = new \WP_Query(
					array(
						'post_type'      => 'any',
						'post__in'       => $ids,
						'posts_per_page' => -1,
					)
				);

				foreach ( $query->posts as $post ) {
					$results[ $post->ID ] = $post->post_title;
				}
				break;

			case 'author':
				$query_params = array(
					'who'                 => 'authors',
					'has_published_posts' => true,
					'fields'              => array(
						'ID',
						'display_name',
					),
					'include'             => $ids,
				);

				$user_query = new \WP_User_Query( $query_params );

				foreach ( $user_query->get_results() as $author ) {
					$results[ $author->ID ] = $author->display_name;
				}
				break;
			default:
				$results = apply_filters( 'rael_core_elements_query_control_get_value_titles_' . $request['filter_type'], array(), $request );
		}

		return $results;
	}

	/**
	 * Register Controls.
	 */
	public function register_controls() {
		$controls_manager = Plugin::instance()->controls_manager;

		$controls = array(
			'rael-query'         => array(
				'file'  => __DIR__ . '/controls/class-query.php',
				'class' => 'Controls\Query',
				'type'  => 'single',
			),
			'rael-posts'         => array(
				'file'  => __DIR__ . '/controls/class-group-control-posts.php',
				'class' => 'Controls\Group_Control_Posts',
				'type'  => 'group',
			),
			'rael-query-group'   => array(
				'file'  => __DIR__ . '/controls/class-group-control-query.php',
				'class' => 'Controls\Group_Control_Query',
				'type'  => 'group',
			),
			'rael-related-query' => array(
				'file'  => __DIR__ . '/controls/class-group-control-related.php',
				'class' => 'Controls\Group_Control_Related',
				'type'  => 'group',
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

				if ( 'group' === $control_info['type'] ) {
					$controls_manager->add_group_control( $control_type, new $class_name() );
				} else {
					$controls_manager->register( new $class_name() );
				}
			}
		}

	}

	/**
	 * Extract Post Type.
	 *
	 * @param array $data Data.
	 */
	private function extract_post_type( $data ) {

		if ( ! empty( $data['query'] ) && ! empty( $data['query']['post_type'] ) ) {
			return $data['query']['post_type'];
		}

		return $data['object_type'];
	}

	/**
	 * Function Get Term Name with Parents
	 *
	 * @param \WP_Term $term Terms.
	 * @param int      $max  Maximum.
	 *
	 * @return string
	 */
	private function get_term_name_with_parents( \WP_Term $term, $max = 3 ) {
		if ( 0 === $term->parent ) {
			return $term->name;
		}
		$separator = is_rtl() ? ' < ' : ' > ';
		$test_term = $term;
		$names     = array();
		while ( $test_term->parent > 0 ) {
			$test_term = get_term_by( 'term_taxonomy_id', $test_term->parent );
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
	 * Get post name with parents.
	 *
	 * @param \WP_Post $post Post Object.
	 * @param int      $max  Maximum.
	 *
	 * @return string
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
	 * Get Query Args.
	 *
	 * @param array $control_id control ids.
	 * @param array $settings settings.
	 */
	public function get_query_args( $control_id, $settings ) {

		$controls_manager = Plugin::instance()->controls_manager;

		/**
		 * Post Query
		 *
		 * @var Group_Control_Posts $posts_query
		 */
		$posts_query = $controls_manager->get_control_groups( Group_Control_Posts::get_type() );

		return $posts_query->get_query_args( $control_id, $settings );
	}

	/**
	 * Get Query
	 *
	 * @param \ElementorPro\Base\Base_Widget $widget Widget Object.
	 * @param string                         $name  Name.
	 * @param array                          $query_args Query Args.
	 * @param array                          $fallback_args Fallback Args.
	 *
	 * @return \WP_Query
	 */
	public function get_query( $widget, $name, $query_args = array(), $fallback_args = array() ) {
		$prefix    = $name . '_';
		$post_type = $widget->get_settings( $prefix . 'post_type' );
		include_once __DIR__ . '/classes/elementor-post-query.php';
		if ( 'related' === $post_type ) {
			include_once __DIR__ . '/classes/elementor-related-query.php';
			$elementor_query = new Elementor_Related_Query( $widget, $name, $query_args, $fallback_args );
		} else {
			$elementor_query = new Elementor_Post_Query( $widget, $name, $query_args );
		}
		return $elementor_query->get_query();
	}

	/**
	 * Fix Query Offset.
	 *
	 * @param object $query Query.
	 */
	public function fix_query_offset( &$query ) {
		if ( ! empty( $query->query_vars['offset_to_fix'] ) ) {
			if ( $query->is_paged ) {
				$query->query_vars['offset'] = $query->query_vars['offset_to_fix'] + ( ( $query->query_vars['paged'] - 1 ) * $query->query_vars['posts_per_page'] );
			} else {
				$query->query_vars['offset'] = $query->query_vars['offset_to_fix'];
			}
		}
	}

	/**
	 * Fix Query Found Posts.
	 *
	 * @param int    $found_posts Found Posts.
	 * @param object $query Query.
	 */
	public static function fix_query_found_posts( $found_posts, $query ) {
		$offset_to_fix = $query->get( 'offset_to_fix' );

		if ( $offset_to_fix ) {
			$found_posts -= $offset_to_fix;
		}

		return $found_posts;
	}

	/**
	 * Register Ajax Actions
	 *
	 * @param Ajax $ajax_manager Ajax Manager.
	 */
	public function register_ajax_actions( $ajax_manager ) {
		$ajax_manager->register_ajax_action( 'query_control_value_titles', array( $this, 'ajax_posts_control_value_titles' ) );
		$ajax_manager->register_ajax_action( 'panel_posts_control_filter_autocomplete', array( $this, 'ajax_posts_filter_autocomplete' ) );
	}

	/**
	 * Localize Settings
	 *
	 * @param array $settings Settings.
	 */
	public function localize_settings( $settings ) {
		$settings = array_replace_recursive(
			$settings,
			array(
				'i18n' => array(
					'all' => __( 'All', 'responsive-addons-for-elementor' ),
				),
			)
		);

		return $settings;
	}

	/**
	 * RAEL Get Post By Terms.
	 */
	public function rael_get_posts_by_terms() {
		if ( isset( $_POST['data']['term'] ) && '' !== $_POST['data']['term'] ) { //phpcs:ignore WordPress.Security.NonceVerification.Missing
			$term      = isset( $_POST['data']['term'] ) ? wp_kses_post( wp_unslash( $_POST['data']['term'] ) ) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Missing
			$post_id   = isset( $_POST['data']['pid'] ) ? wp_kses_post( wp_unslash( $_POST['data']['pid'] ) ) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Missing
			$widget_id = isset( $_POST['data']['widget_id'] ) ? wp_kses_post( wp_unslash( $_POST['data']['widget_id'] ) ) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Missing
			$style_id  = isset( $_POST['data']['skin'] ) ? wp_kses_post( wp_unslash( $_POST['data']['skin'] ) ) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Missing

			$elementor = \Elementor\Plugin::$instance;
			$meta      = $elementor->documents->get( $post_id )->get_elements_data();

			$widget_data = $this->find_element_recursive( $meta, $widget_id );

			if ( null !== $widget_data ) {
				// Restore default values.
				$widget = $elementor->elements_manager->create_element_instance( $widget_data );

				// Return data and call your function according to your need for ajax call.
				// You will have access to settings variable as well as some widget functions.
				require_once RAEL_DIR . 'includes/widgets-manager/widgets/skins/template-blocks/class-skin-init.php';
				$skin = \Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Skins\TemplateBlocks\Skin_Init::get_instance( $style_id );

				// Here you will just need posts based on ajax requst to attache in layout.
				$html = $skin->inner_render( $style_id, $widget );

				$pagination = $skin->page_render( $style_id, $widget );

			}

			wp_send_json(
				array(
					'term'       => $term,
					'post_id'    => $post_id,
					'widget_id'  => $widget_id,
					'style_id'   => $style_id,
					'html'       => $html,
					'pagination' => $pagination,
				)
			);
		}
	}

	/**
	 * Find Element Recursive
	 *
	 * @param array   $elements Ajax Manager.
	 * @param integer $form_id Form ID.
	 */
	public function find_element_recursive( $elements, $form_id ) {

		foreach ( $elements as $element ) {
			if ( $form_id === $element['id'] ) {
				return $element;
			}

			if ( ! empty( $element['elements'] ) ) {
				$element = $this->find_element_recursive( $element['elements'], $form_id );

				if ( $element ) {
					return $element;
				}
			}
		}

		return false;
	}

	/**
	 * Format Post for Display.
	 *
	 * @access protected
	 */
	protected function add_actions() {
		add_action( 'elementor/ajax/register_actions', array( $this, 'register_ajax_actions' ) );
		add_action( 'elementor/controls/register', array( $this, 'register_controls' ) );

		add_filter( 'rael/core_elements/editor/localize_settings', array( $this, 'localize_settings' ) );

		/**
		 * Making Custom Queries using Offset and Pagination
		 *
		 * @see https://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
		 */
		add_action( 'pre_get_posts', array( $this, 'fix_query_offset' ), 1 );
		add_filter( 'found_posts', array( $this, 'fix_query_found_posts' ), 1, 2 );

		add_action( 'wp_ajax_rael_get_posts_by_terms', array( $this, 'rael_get_posts_by_terms' ) );
		add_action( 'wp_ajax_nopriv_rael_get_posts_by_terms', array( $this, 'rael_get_posts_by_terms' ) );
	}
}
