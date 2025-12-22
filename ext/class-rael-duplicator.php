<?php
/**
 * RAEL Duplicator Extension
 *
 * Adds RAE Duplicator link on post type (post, page, custom post types) — useful for creating multiple similar posts or creating a draft copy for editing.
 *
 * @package Responsive Addons for Elementor
 */

if ( ! defined( 'WPINC' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! class_exists( 'RAEL_Duplicator' ) ) {
	/**
	 * RAEL Duplicator class
	 *
	 * Handles the RAEL_ Duplicator functionality for post type (post, page, custom post types).
	 */
	class RAEL_Duplicator {
		private static $instance = null;
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
		 
		public function __construct() {
			// Load only if enabled in RAE Extensions
			if ( ! Helper::is_extension_active('duplicator') ) {
				return;
			}
			
			// Row actions for posts/pages
			add_filter( 'manage_posts_columns', array( $this, 'rae_add_custom_column' ) );
			add_filter( 'manage_pages_columns', array( $this, 'rae_add_custom_column' ) );

			add_filter( 'default_hidden_columns', array( $this, 'rae_hide_duplicator_column_by_default' ), 10, 2 );
			add_action( 'admin_enqueue_scripts', array( $this, 'rae_duplicator_admin_styles' ) );


			
			add_filter( 'post_row_actions', array( $this, 'rae_add_duplicator_action' ), 10, 2 );
			add_filter( 'page_row_actions', array( $this, 'rae_add_duplicator_action' ), 10, 2 );

			// Bulk action for all post types
			add_filter( 'handle_bulk_actions-edit-post', array( $this, 'rae_process_bulk_action' ), 10, 3 );
			add_action( 'admin_init', array( $this, 'rae_register_bulk_actions_for_all_post_types' ) );
			
			// Admin action for duplication
			add_action( 'admin_action_rael_duplicate_post', array( $this, 'rae_duplicate_post_handler' ) );

			add_filter( 'post_row_actions', array( $this, 'rae_add_duplicate_nonce_field' ), 10, 2 );
			add_filter( 'page_row_actions', array( $this, 'rae_add_duplicate_nonce_field' ), 10, 2 );

	}

	public function rae_add_duplicator_action( $actions, $post ) {

		// Get allowed post types saved from popup
		$allowed = get_option( 'rael_duplicator_allowed_post_types', array( 'all' ) );

		// If "all" selected → show for everything
		if ( in_array( 'all', $allowed ) ) {
			$actions['rael_duplicate'] = $this->rae_get_duplicate_link( $post->ID );
			return $actions;
		}

		// Otherwise show only for selected post types
		if ( in_array( $post->post_type, $allowed ) ) {
			$actions['rael_duplicate'] = $this->rae_get_duplicate_link( $post->ID );
		}

		return $actions;
	}
	/**
	 * Add "Duplicate" link in row action
	 */
	public function rae_get_duplicate_link( $post_id ) {

		if ( ! $this->rae_user_can_duplicate_post( $post_id ) ) {
			return '';
		}

		 $url = wp_nonce_url(
			admin_url( 'admin.php?action=rael_duplicate_post&post=' . $post_id ),
			'rael_duplicate_post_' . $post_id
		);

		return sprintf(
			'<a href="%s">%s</a>',
			esc_url( $url ),
			esc_html__( 'RAE Duplicator', 'responsive-addons-for-elementor' )
		);
	}


	/**
	 * Add bulk action
	 */
	public function rae_register_bulk_action( $bulk_actions ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return $bulk_actions;
		}
		$bulk_actions['rael_duplicate'] = __( 'Duplicate', 'responsive-addons-for-elementor' );
		return $bulk_actions;
	}


	/**
	 * Process bulk action
	 */
	public function rae_process_bulk_action( $redirect_url, $action, $post_ids ) {

		if ( $action !== 'rael_duplicate' ) {
			return $redirect_url;
		}

		foreach ( $post_ids as $post_id ) {
			if ( ! $this->rae_user_can_duplicate_post( $post_id ) ) {
				continue;
			}
			$this->rae_duplicate( $post_id );
		}

		return add_query_arg(
			'rael_duplicated',
			count( $post_ids ),
			$redirect_url
		);
	}
	public function rae_register_bulk_actions_for_all_post_types() {

    // Get all post types where UI is visible (post, page, CPTs)
    $post_types = get_post_types( array( 'show_ui' => true ), 'names' );

    // Get selected allowed post types
    $allowed = get_option( 'rael_duplicator_allowed_post_types', array( 'all' ) );

    foreach ( $post_types as $post_type ) {

        // CASE 1: "all" is selected → show for ALL post types
        if ( in_array( 'all', $allowed ) ) {

            add_filter(
                "bulk_actions-edit-{$post_type}",
                array( $this, 'rae_register_bulk_action' )
            );

            add_filter(
                "handle_bulk_actions-edit-{$post_type}",
                array( $this, 'rae_process_bulk_action' ),
                10,
                3
            );

            continue; // move to next post type
        }

        // CASE 2: only specific post types selected
        if ( in_array( $post_type, $allowed ) ) {

            add_filter(
                "bulk_actions-edit-{$post_type}",
                array( $this, 'rae_register_bulk_action' )
            );

            add_filter(
                "handle_bulk_actions-edit-{$post_type}",
                array( $this, 'rae_process_bulk_action' ),
                10,
                3
            );
        }

    }
}


	/**
	 * Handle single duplication via admin action link
	 */
	public function rae_duplicate_post_handler() {

		$post_id = isset( $_GET['post'] ) ? intval( $_GET['post'] ) : 0;
		$nonce   = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( $_GET['_wpnonce'] ) : '';

		if ( ! wp_verify_nonce( $nonce, 'rael_duplicate_post_' . $post_id ) ) {
			wp_die( esc_html__( 'Invalid request.', 'responsive-addons-for-elementor' ) );
		}

		if ( ! $this->rae_user_can_duplicate_post( $post_id ) ) {
			wp_die( esc_html__( 'You are not allowed to duplicate this post.', 'responsive-addons-for-elementor' ) );
		}

		$new_id = $this->rae_duplicate( $post_id );

		if ( is_wp_error( $new_id ) ) {
			wp_die( esc_html($new_id->get_error_message() ));
		}
		
		// Get original post type.
		$post_type = get_post_type( $post_id );
		$redirect_url = admin_url( 'edit.php' );
		
		// If CPT (not 'post')
		if ( 'post' !== $post_type ) {
			$redirect_url = admin_url( 'edit.php?post_type=' . $post_type );
		}

		wp_safe_redirect( $redirect_url );
		exit;
	}
	public function rae_add_duplicate_nonce_field( $actions, $post ) {
		$nonce = wp_create_nonce( 'rael_duplicate_post_' . $post->ID );

		echo '<input type="hidden" class="rae-dup-nonce" data-post="' . esc_attr( $post->ID ) . '" value="' . esc_attr( $nonce ) . '">';

		return $actions;
	}
	/**
	 * Core duplication logic
	 */
	/**
 * Duplicate any post safely (Elementor + Gutenberg + Classic).
 */
private function rae_duplicate( $post_id ) {

    $original = get_post( $post_id );
    if ( ! $original ) {
        return new WP_Error( 'invalid_post', __( 'Post not found.', 'responsive-addons-for-elementor' ) );
    }

    // ---- 1. CREATE THE NEW POST ----
    $new_post = [
        'post_title'     => $original->post_title . ' (Copy)',
        'post_content'   => $original->post_content,
        'post_excerpt'   => $original->post_excerpt,
        'post_status'    => 'draft',
        'post_type'      => $original->post_type,
        'post_author'    => get_current_user_id(),
        'menu_order'     => $original->menu_order,
        'comment_status' => $original->comment_status,
        'ping_status'    => $original->ping_status,
    ];

    $new_post_id = wp_insert_post( $new_post );
    if ( ! $new_post_id ) {
        return new WP_Error( 'db_error', __( 'Failed to duplicate post.', 'responsive-addons-for-elementor' ) );
    }

    // ---- 2. COPY TAXONOMIES ----
    $taxonomies = get_object_taxonomies( $original->post_type );
    foreach ( $taxonomies as $taxonomy ) {
        $terms = wp_get_object_terms( $post_id, $taxonomy, [ 'fields' => 'ids' ] );
        if ( ! is_wp_error( $terms ) ) {
            wp_set_object_terms( $new_post_id, $terms, $taxonomy );
        }
    }

    // ---- 3. SAFE META COPY (Elementor + ACF + everything else) ----
    
	global $wpdb;

	$post_meta = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = %d",
			$post_id
		)
	);

	if ( ! empty( $post_meta ) && is_array( $post_meta ) ) {

		$exclude = array(
			'_edit_lock',
			'_edit_last',
			'_wp_old_slug',
			'_elementor_css', // regenerate
		);

		$insert_values = '';
		$placeholders = [];
		$values       = [];	

		foreach ( $post_meta as $meta ) {

			$key   = sanitize_text_field( $meta->meta_key );
			$value = $meta->meta_value;

			if ( in_array( $key, $exclude ) ) {
				continue;
			}
			$placeholders[] = "(%d, %s, %s)";
			$values[] = $new_post_id;
			$values[] = $key;
			$values[] = $value;
			
		}

		if ( ! empty( $placeholders ) ) {
			

			$sql = "INSERT INTO {$wpdb->postmeta} ( post_id, meta_key, meta_value ) VALUES " . implode(', ', $placeholders);
			    
			$wpdb->query( $wpdb->prepare( $sql, $values ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

		}
	}

    return $new_post_id;
}





	public function rae_add_custom_column( $columns ) {
		$columns['rae_duplicator'] =  esc_html__( 'RAE Duplicator', 'responsive-addons-for-elementor' );
		return $columns;
	}
	public function rae_hide_duplicator_column_by_default( $hidden, $screen ) {

		if ( empty( $screen->id ) ) {
			return $hidden;
		}

		// Allowed list table screen IDs
		$screens = array(
			'edit-post',     // Posts
			'edit-page',     // Pages
			'edit-product',  // WooCommerce Products
		);

		// Apply only to above screens
		if ( in_array( $screen->id, $screens, true ) ) {
			$column = 'rae_duplicator';

			if ( ! in_array( $column, $hidden, true ) ) {
				$hidden[] = $column;
			}
		}

		return $hidden;
	}

	public function rae_duplicator_admin_styles($hook) {
		$screen = get_current_screen();
		if ( ! $screen || ! in_array( $screen->id, [ 'edit-post', 'edit-page', 'edit-product' ], true ) ) {
			return;
		}

		wp_add_inline_style( 'wp-admin', '
			th.column-rae_duplicator, td.column-rae_duplicator { display: none !important; }
		' );
	}

	/* Added to Fix vulnerability */
	private function rae_user_can_duplicate_post( $post_id ) {
		$post = get_post( $post_id );
		if ( ! $post ) {
			return false;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return false;
		}

		if ( current_user_can( 'contributor' ) && ! current_user_can( 'edit_others_posts' ) ) {

			if ( $post->post_status !== 'publish' ) {
				return false;
			}

			if ( ! empty( $post->post_password ) ) {
				return false;
			}
		}

		return true;
	}


	
	}

	new RAEL_Duplicator();
}