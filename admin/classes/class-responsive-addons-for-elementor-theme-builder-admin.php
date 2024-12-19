<?php
/**
 * RAEL Theme Builder's Admin part.
 *
 * @package  Responsive_Addons_For_Elementor
 * @package  Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\Admin\Theme_Builder;

use Responsive_Addons_For_Elementor\ModulesManager\Theme_Builder\Conditions\RAEL_Conditions;
use Responsive_Addons_For_Elementor\ModulesManager\Theme_Builder\RAEL_Theme_Builder;
use Responsive_Addons_For_Elementor\Traits\Singleton;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RAEL_HF_Admin Class.
 *
 * @since 1.3.0
 */
class RAEL_HF_Admin {
	use Singleton;

	/**
	 * Post type slug.
	 *
	 * @var string
	 */
	public $post_type = 'rael-theme-template';

	/**
	 * Template type arg for URL.
	 *
	 * @var string
	 */
	public $type_tax = 'template_type';

	/**
	 * Constructor
	 *
	 * @access private
	 *
	 * @since 1.3.0
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'register_rael_theme_builder_posttype' ) );
		add_filter( 'views_edit-' . $this->post_type, array( $this, 'print_type_tabs' ) );
		add_filter( 'parse_query', array( $this, 'prefix_parse_filter' ) );
		add_action( 'add_meta_boxes', array( $this, 'rael_hf_register_metabox' ) );
		add_action( 'save_post', array( $this, 'rael_hf_save_meta_data' ) );
		add_action( 'admin_notices', array( $this, 'location_notice' ) );
		add_action( 'template_redirect', array( $this, 'block_template_frontend' ) );
		add_filter( 'single_template', array( $this, 'load_canvas_template' ) );
		add_filter( 'manage_rael-theme-template_posts_columns', array( $this, 'add_shortcode_column' ) );
		add_filter( 'manage_rael-theme-template_posts_columns', array( $this, 'add_type_column' ) );
		add_action( 'manage_rael-theme-template_posts_custom_column', array( $this, 'render_shortcode_column' ), 10, 2 );
		add_action( 'manage_rael-theme-template_posts_custom_column', array( $this, 'render_type_column' ), 10, 2 );
		if ( defined( 'ELEMENTOR_PRO_VERSION' ) && ELEMENTOR_PRO_VERSION > 2.8 ) {
			add_action( 'elementor/editor/footer', array( $this, 'register_rael_epro_script' ), 99 );
		}

		if ( is_admin() ) {
			add_action( 'manage_rael-theme-template_posts_custom_column', array( $this, 'render_column_content' ), 10, 2 );
			add_filter( 'manage_rael-theme-template_posts_columns', array( $this, 'add_column_headings' ) );
		}
	}

	/**
	 * Parse query to filter specific temlpate type.
	 *
	 * @param object $query
	 */
	public function prefix_parse_filter( $query ) {
		global $pagenow;
		$current_page = isset( $_GET[ $this->type_tax ] ) ? $_GET[ $this->type_tax ] : '';

		if ( is_admin() &&
			'edit.php' == $pagenow &&
			isset( $_GET[ $this->type_tax ] ) &&
			$_GET[ $this->type_tax ] != '' ) {

			$query->query_vars['meta_key']     = 'rael_hf_template_type';
			$query->query_vars['meta_value']   = $current_page;
			$query->query_vars['meta_compare'] = '=';
		}
	}

	/**
	 * Print library types tabs.
	 *
	 * @param array $edit_links
	 *
	 * @since 1.8.0 Added single-product.
	 * @since 1.8.0 Added Product Archive
	 * @return array containing links for navigation tabs
	 */
	public function print_type_tabs( $edit_links ) {

		$tabs = array(
			'all'             => esc_html__( 'All', 'responsive-addons-for-elementor' ),
			'header'          => esc_html__( 'Header', 'responsive-addons-for-elementor' ),
			'footer'          => esc_html__( 'Footer', 'responsive-addons-for-elementor' ),
			'single-page'     => esc_html__( 'Single Page', 'responsive-addons-for-elementor' ),
			'single-post'     => esc_html__( 'Single Post', 'responsive-addons-for-elementor' ),
			'error-404'       => esc_html__( 'Error 404', 'responsive-addons-for-elementor' ),
			'archive'         => esc_html__( 'Archive', 'responsive-addons-for-elementor' ),
			'single-product'  => esc_html__( 'Single Product', 'responsive-addons-for-elementor' ),
			'product-archive' => esc_html__( 'Product Archive', 'responsive-addons-for-elementor' ),
		);

		$active_tab = isset( $_GET[ $this->type_tax ] ) ? $_GET[ $this->type_tax ] : 'all';
		$page_link  = admin_url( 'edit.php?post_type=' . $this->post_type );

		if ( ! array_key_exists( $active_tab, $tabs ) ) {
			$active_tab = 'all';
		} ?>

		<div class="nav-tab-wrapper jet-library-tabs">
			<?php
			foreach ( $tabs as $tab => $label ) {

				$class = 'nav-tab';

				if ( $tab === $active_tab ) {
					$class .= ' nav-tab-active';
				}

				if ( 'all' !== $tab ) {
					$link = add_query_arg( array( $this->type_tax => $tab ), $page_link );
				} else {
					$link = $page_link;
				}

				printf( '<a href="%1$s" class="%3$s">%2$s</a>', esc_url( $link ), esc_html( $label ), esc_attr( $class ) );

			}
			?>
		</div>
		<br>
		<?php
		return $edit_links;
	}

	/**
	 * Script for Elementor Pro full site editing support.
	 *
	 * @access public
	 *
	 * @since 1.5.0 Added Archive to $ids.
	 * @since 1.8.0 Added Single Product to $ids.
	 * @since 1.8.0 Added Product Archive to $ids
	 *
	 * @return void
	 */
	public function register_rael_epro_script() {
		$ids = array(
			array(
				'id'    => get_rael_header_id(),
				'value' => 'Header',
			),
			array(
				'id'    => get_rael_footer_id(),
				'value' => 'Footer',
			),
			array(
				'id'    => get_rael_single_page_id(),
				'value' => 'Single Page',
			),
			array(
				'id'    => get_rael_single_post_id(),
				'value' => 'Single Post',
			),
			array(
				'id'    => get_rael_error_404_id(),
				'value' => 'Error 404',
			),
			array(
				'id'    => get_rael_archive_id(),
				'value' => 'Archive',
			),
		);
		// Single product and product archive will be added in $ids array when woocommerce is activated.
		if ( class_exists( 'WooCommerce' ) ) {
			array_push(
				$ids,
				array(
					'id'    => get_rael_single_product_id(),
					'value' => 'Single Product',
				),
				array(
					'id'    => get_rael_product_archive_id(),
					'value' => 'Product Archive',
				),
			);

		}

		wp_enqueue_script(
			'rael-hf-epro-compatibility',
			RAEL_URL . 'includes/modules-manager/theme-builder/compatibility/js/rael-theme-epro-compatibility.js',
			array( 'jquery' ),
			RAEL_VER,
			true
		);

		wp_localize_script(
			'rael-hf-epro-compatibility',
			'rael_hf_admin',
			array(
				'ids' => wp_json_encode( $ids ),
			)
		);
	}

	/**
	 * Add or remove admin table column headings.
	 *
	 * @access public
	 *
	 * @since 1.3.0
	 *
	 * @param array $columns Array of columns.
	 *
	 * @return array
	 */
	public function add_column_headings( $columns ) {
		unset( $columns['date'] );

		$columns['rael_hf_template_display_conditions'] = __( 'Display Conditions', 'responsive-addons-for-elementor' );
		$columns['date']                                = __( 'Date', 'responsive-addons-for-elementor' );

		return $columns;
	}

	/**
	 * Render column content.
	 *
	 * @access public
	 *
	 * @since 1.3.0
	 *
	 * @param array $column  Name of column.
	 * @param int   $post_id Post id.
	 *
	 * @return void
	 */
	public function render_column_content( $column, $post_id ) {

		if ( 'rael_hf_template_display_conditions' === $column ) {

			$locations = get_post_meta( $post_id, 'rael_hf_include_locations', true );
			if ( ! empty( $locations ) ) {
				echo '<div class="rael-hf__admin-column-include-locations-wrapper" style="margin-bottom: 5px;">';
				echo '<strong>Display: </strong>';
				$this->column_display_location_rules( $locations );
				echo '</div>';
			}

			$locations = get_post_meta( $post_id, 'rael_hf_exclude_locations', true );
			if ( ! empty( $locations ) ) {
				echo '<div class="rael-hf__admin-column-exclude-locations-wrapper" style="margin-bottom: 5px;">';
				echo '<strong>Exclusion: </strong>';
				$this->column_display_location_rules( $locations );
				echo '</div>';
			}

			$users = get_post_meta( $post_id, 'rael_hf_target_user_roles', true );
			if ( isset( $users ) && is_array( $users ) ) {
				if ( isset( $users[0] ) && ! empty( $users[0] ) ) {
					$user_label = array();
					foreach ( $users as $user ) {
						$user_label[] = RAEL_Conditions::get_user_by_key( $user );
					}
					echo '<div class="rael-hf__admin-column-target-users-wrapper">';
					echo '<strong>Users: </strong>';
					echo join( ', ', $user_label ); // phpcs:ignore
					echo '</div>';
				}
			}
		}
	}

	/**
	 * Get Markup of Location rules for Display conditions column.
	 *
	 * @access public
	 *
	 * @since 1.3.0
	 *
	 * @param array $locations Array of locations.
	 *
	 * @return void
	 */
	public function column_display_location_rules( $locations ) {

		$location_label = array();
		$index          = array_search( 'specifics', $locations['rule'] ); // phpcs:ignore
		if ( false !== $index && ! empty( $index ) ) {
			unset( $locations['rule'][ $index ] );
		}

		if ( isset( $locations['rule'] ) && is_array( $locations['rule'] ) ) {
			foreach ( $locations['rule'] as $location ) {
				$location_label[] = RAEL_Conditions::get_location_by_key( $location );
			}
		}
		if ( isset( $locations['specific'] ) && is_array( $locations['specific'] ) ) {
			foreach ( $locations['specific'] as $location ) {
				$location_label[] = RAEL_Conditions::get_location_by_key( $location );
			}
		}

		echo join( ', ', $location_label ); // phpcs:ignore
	}


	/**
	 * Register RAEL Theme Builder post type.
	 *
	 * @access public
	 *
	 * @since 1.3.2 post_type changed to rael-theme-template
	 *
	 * @return void
	 */
	public function register_rael_theme_builder_posttype() {
		$labels = array(
			'name'               => __( 'Theme Builder', 'responsive-addons-for-elementor' ),
			'singular_name'      => __( 'Theme Builder', 'responsive-addons-for-elementor' ),
			'menu_name'          => __( 'Theme Template', 'responsive-addons-for-elementor' ),
			'name_admin_bar'     => __( 'Theme Template', 'responsive-addons-for-elementor' ),
			'add_new'            => __( 'Add New', 'responsive-addons-for-elementor' ),
			'add_new_item'       => __( 'Add New Template', 'responsive-addons-for-elementor' ),
			'new_item'           => __( 'New Template', 'responsive-addons-for-elementor' ),
			'edit_item'          => __( 'Edit Template', 'responsive-addons-for-elementor' ),
			'view_item'          => __( 'View Template', 'responsive-addons-for-elementor' ),
			'all_items'          => __( 'All Templates', 'responsive-addons-for-elementor' ),
			'search_items'       => __( 'Search Templates', 'responsive-addons-for-elementor' ),
			'parent_item_colon'  => __( 'Parent Template:', 'responsive-addons-for-elementor' ),
			'not_found'          => __( 'No Templates found.', 'responsive-addons-for-elementor' ),
			'not_found_in_trash' => __( 'No Templates found in Trash.', 'responsive-addons-for-elementor' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'rewrite'             => false,
			'supports'            => array( 'title', 'thumbnail', 'elementor' ),
		);

		register_post_type( 'rael-theme-template', $args );
	}

	/**
	 * Get help doc URL.
	 *
	 * @since 1.3.2 Slug changed to rael-theme-builder
	 *
	 * @return string Doc URL.
	 */
	public function get_help_url() {
		return 'https://cyberchimps.com/docs/responsive-addons-for-elementor/widgets/theme-builder/';
	}

	/**
	 * Register meta box(es).
	 *
	 * @access public
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	public function rael_hf_register_metabox() {
		$help_url     = $this->get_help_url();
		$help_element = '<a href="' . esc_url( $help_url ) . '" class="rael-hf__need-help" target="_blank">Need Help?</a>';

		add_meta_box(
			'rael-hf-meta-box',
			// translators: %1$s represents the help element.
			sprintf( __( 'Template Meta Settings %1$s', 'responsive-addons-for-elementor' ), $help_element ),
			array(
				$this,
				'rael_hf_metabox_render',
			),
			'rael-theme-template',
			'normal',
			'high'
		);
	}

	/**
	 * Render Meta box(es) content.
	 *
	 * @access public
	 *
	 * @since 1.5.0 Added Archive option.
	 * @since 1.8.0 Added single-product option.
	 *
	 * @param POST $post Current post object which is being displayed.
	 *
	 * @return void
	 */
	public function rael_hf_metabox_render( $post ) {
		$values            = get_post_custom( $post->ID );
		$template_type     = isset( $values['rael_hf_template_type'] ) ? esc_attr( $values['rael_hf_template_type'][0] ) : '';
		$display_on_canvas = isset( $values['rael-hf__enable-for-canvas'] ) ? true : false;

		// We'll use this nonce field later on when saving.
		wp_nonce_field( 'rael_hf_meta_nonce', 'rael_hf_meta_nonce' );
		?>
		<table class="rael-hf__meta-options-table widefat">
			<tbody>
				<tr class="rael-hf__meta-options-row type-of-template">
					<td class="rael-hf__meta-options-row-heading">
						<label for="rael_hf_template_type"><?php esc_html_e( 'Type of Template', 'responsive-addons-for-elementor' ); ?></label>
					</td>
					<td class="rael-hf__meta-options-row-body">
						<select name="rael_hf_template_type" id="rael_hf_template_type">
							<option value="" <?php selected( $template_type, '' ); ?>><?php esc_html_e( 'Select Template type', 'responsive-addons-for-elementor' ); ?></option>
							<option value="header" <?php selected( $template_type, 'header' ); ?>><?php esc_html_e( 'Header', 'responsive-addons-for-elementor' ); ?></option>
							<option value="footer" <?php selected( $template_type, 'footer' ); ?>><?php esc_html_e( 'Footer', 'responsive-addons-for-elementor' ); ?></option>
							<option value="single-page" <?php selected( $template_type, 'single-page' ); ?>><?php esc_html_e( 'Single Page', 'responsive-addons-for-elementor' ); ?></option>
							<option value="single-post" <?php selected( $template_type, 'single-post' ); ?>><?php esc_html_e( 'Single Post', 'responsive-addons-for-elementor' ); ?></option>
							<option value="error-404" <?php selected( $template_type, 'error-404' ); ?>><?php esc_html_e( 'Error 404', 'responsive-addons-for-elementor' ); ?></option>
							<option value="archive" <?php selected( $template_type, 'archive' ); ?>><?php esc_html_e( 'Archive', 'responsive-addons-for-elementor' ); ?></option>
							<option value="single-product" <?php selected( $template_type, 'single-product' ); ?>><?php esc_html_e( 'Single Product', 'responsive-addons-for-elementor' ); ?></option>
							<option value="product-archive" <?php selected( $template_type, 'product-archive' ); ?>><?php esc_html_e( 'Product  Archive', 'responsive-addons-for-elementor' ); ?></option>
						</select>
					</td>
				</tr>

				<?php $this->display_rules_tab(); ?>
				<tr class="rael-hf__meta-options-row rael-hf__shortcode">
					<td class="rael-hf__meta-options-row-heading">
						<label for="rael-hf__template-shortcode"><?php esc_html_e( 'Shortcode', 'responsive-addons-for-elementor' ); ?></label>
						<i class="rael-hf__meta-options-row-heading-help dashicons dashicons-editor-help" title="<?php esc_html__( 'Copy this shortcode and paste it into your post, page, or text widget content.', 'responsive-addons-for-elementor' ); ?>">
						</i>
					</td>
					<td class="rael-hf__meta-options-row-body">
						<span class="rael-hf__shortcode-column">
							<input type="text" onfocus="this.select();" readonly="readonly" value="[rael_theme_template id='<?php echo esc_attr( $post->ID ); ?>']" class="rael-hf__template-shortcode code">
						</span>
					</td>
				</tr>
				<tr class="rael-hf__meta-options-row rael-hf__enable-for-canvas">
					<td class="rael-hf__meta-options-row-heading">
						<label for="rael-hf__enable-for-canvas">
							<?php esc_html_e( 'Enable Layout for Elementor Canvas Template?', 'responsive-addons-for-elementor' ); ?>
						</label>
						<i class="rael-hf__meta-options-row-heading-help dashicons dashicons-editor-help" title="<?php esc_html_e( 'Enabling this option will allow you to display this template on pages using Elementor Canvas Template.', 'responsive-addons-for-elementor' ); ?>"></i>
					</td>
					<td class="rael-hf__meta-options-row-body">
						<input type="checkbox" id="rael-hf__enable-for-canvas" name="rael-hf__enable-for-canvas" value="1" <?php checked( $display_on_canvas, true ); ?> />
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Markup for Display Conditions Tabs.
	 *
	 * @access public
	 *
	 * @since  1.3.0
	 */
	public function display_rules_tab() {
		// Load Display Conditions assets.
		RAEL_Conditions::instance()->admin_styles();

		$include_locations = get_post_meta( get_the_id(), 'rael_hf_include_locations', true );
		$exclude_locations = get_post_meta( get_the_id(), 'rael_hf_exclude_locations', true );
		$users             = get_post_meta( get_the_id(), 'rael_hf_target_user_roles', true );
		?>
		<tr class="rael-hf__display-condition-row rael-hf__meta-options-row">
			<td class="rael-hf__display-condition-row-heading rael-hf__meta-options-row-heading">
				<label><?php esc_html_e( 'Display On', 'responsive-addons-for-elementor' ); ?></label>
				<i class="rael-hf__display-condition-row-heading-help dashicons dashicons-editor-help"
					title="<?php echo esc_attr__( 'Add the location(s) for where this template should appear.', 'responsive-addons-for-elementor' ); ?>"></i>
			</td>
			<td class="rael-hf__display-condition-row-body rael-hf__meta-options-row-body">
				<?php
				RAEL_Conditions::target_rule_settings_field(
					'rael-hf-include-locations',
					array(
						'title'          => __( 'Display Rules', 'responsive-addons-for-elementor' ),
						'value'          => '[{"type":"basic-global","specific":null}]',
						'tags'           => 'site,enable,target,pages',
						'rule_type'      => 'display',
						'add_rule_label' => __( 'Add Display On Condition', 'responsive-addons-for-elementor' ),
					),
					$include_locations
				);
				?>
			</td>
		</tr>
		<tr class="rael-hf__display-condition-row rael-hf__meta-options-row">
			<td class="rael-hf__display-condition-row-heading rael-hf__meta-options-row-heading">
				<label><?php esc_html_e( 'Do Not Display On', 'responsive-addons-for-elementor' ); ?></label>
				<i class="rael-hf__display-condition-row-heading-help dashicons dashicons-editor-help"
					title="<?php echo esc_attr__( 'Add the location(s) for where this template should not appear.', 'responsive-addons-for-elementor' ); ?>"></i>
			</td>
			<td class="rael-hf__display-condition-row-body rael-hf__meta-options-row-body">
				<?php
				RAEL_Conditions::target_rule_settings_field(
					'rael-hf-exclude-locations',
					array(
						'title'          => __( 'Exclude On', 'responsive-addons-for-elementor' ),
						'value'          => '[]',
						'tags'           => 'site,enable,target,pages',
						'add_rule_label' => __( 'Add Exclusion Rule', 'responsive-addons-for-elementor' ),
						'rule_type'      => 'exclude',
					),
					$exclude_locations
				);
				?>
			</td>
		</tr>
		<tr class="rael-hf__user-role-condition-row rael-hf__meta-options-row">
			<td class="rael-hf__user-role-condition-row-heading rael-hf__meta-options-row-heading">
				<label><?php esc_html_e( 'User Roles', 'responsive-addons-for-elementor' ); ?></label>
				<i class="rael-hf__user-role-condition-heading-help dashicons dashicons-editor-help" title="<?php echo esc_attr__( 'Display this template based on user role(s).', 'responsive-addons-for-elementor' ); ?>"></i>
			</td>
			<td class="rael-hf__user-role-condition-body rael-hf__meta-options-row-body">
				<?php
				RAEL_Conditions::target_user_role_settings_field(
					'rael-hf-target-user-roles',
					array(
						'title'          => __( 'Users', 'responsive-addons-for-elementor' ),
						'value'          => '[]',
						'tags'           => 'site,enable,target,pages',
						'add_rule_label' => __( 'Add User Rule', 'responsive-addons-for-elementor' ),
					),
					$users
				);
				?>
			</td>
		</tr>
		<?php
	}

	/**
	 * Save post meta data.
	 *
	 * @access public
	 *
	 * @since 1.3.0
	 *
	 * @param  POST $post_id Current post object which is being displayed.
	 *
	 * @return Void
	 */
	public function rael_hf_save_meta_data( $post_id ) {

		// Bail if we're doing an auto save.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// if our nonce isn't there, or we can't verify it, bail.
		if ( ! isset( $_POST['rael_hf_meta_nonce'] ) || ! wp_verify_nonce( $_POST['rael_hf_meta_nonce'], 'rael_hf_meta_nonce' ) ) { // phpcs:ignore
			return;
		}

		// if our current user can't edit this post, bail.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$target_locations = RAEL_Conditions::get_format_rule_value( $_POST, 'rael-hf-include-locations' );
		$target_exclusion = RAEL_Conditions::get_format_rule_value( $_POST, 'rael-hf-exclude-locations' );
		$target_users     = array();

		if ( isset( $_POST['rael-hf-target-user-roles'] ) ) {
			$target_users = array_map( 'sanitize_text_field', $_POST['rael-hf-target-user-roles'] ); // phpcs:ignore
		}

		update_post_meta( $post_id, 'rael_hf_include_locations', $target_locations );
		update_post_meta( $post_id, 'rael_hf_exclude_locations', $target_exclusion );
		update_post_meta( $post_id, 'rael_hf_target_user_roles', $target_users );

		if ( isset( $_POST['rael_hf_template_type'] ) ) {
			update_post_meta( $post_id, 'rael_hf_template_type', esc_attr( $_POST['rael_hf_template_type'] ) ); // phpcs:ignore
		}

		if ( isset( $_POST['rael-hf__enable-for-canvas'] ) ) {
			update_post_meta( $post_id, 'rael-hf__enable-for-canvas', esc_attr( $_POST['rael-hf__enable-for-canvas'] ) ); // phpcs:ignore
		} else {
			delete_post_meta( $post_id, 'rael-hf__enable-for-canvas' );
		}
	}

	/**
	 * Display notice when editing the template when there is one more of similar layout is active on the site.
	 *
	 * @access public
	 *
	 * @since 1.3.0
	 *
	 * @return mixed
	 */
	public function location_notice() {
		global $pagenow;
		global $post;

		if ( 'post.php' !== $pagenow || ! is_object( $post ) || 'rael-theme-template' !== $post->post_type ) {
			return;
		}

		$template_type = get_post_meta( $post->ID, 'rael_hf_template_type', true );

		if ( '' !== $template_type ) {
			$templates = RAEL_Theme_Builder::get_template_id( $template_type );

			// Check if more than one template is selected for current template type.
			if ( is_array( $templates ) && isset( $templates[1] ) && $post->ID == $templates[0] ) { // phpcs:ignore
				echo '<div class="notice notice-warning is-dismissible"><p>';
				echo esc_html__( 'A template already exists with same display conditions, creating this will override the previous template.', 'responsive-addons-for-elementor' );
				echo '</p></div>';
			}
		}
	}

	/**
	 * Convert the Template name to be added in the notice.
	 *
	 * @access public
	 *
	 * @since  1.3.0
	 *
	 * @param  String $template_type Template type name.
	 *
	 * @return String Template type name.
	 */
	public function template_location( $template_type ) {
		$template_type = ucfirst( $template_type );

		return $template_type;
	}

	/**
	 * Don't display the elementor Theme Builder & blocks templates on the frontend for non edit_posts capable users.
	 *
	 * @since  1.3.0
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function block_template_frontend() {
		if ( is_singular( 'rael-theme-template' ) && ! current_user_can( 'edit_posts' ) ) {
			wp_redirect( site_url(), 301 ); // phpcs:ignore
			die;
		}
	}

	/**
	 * Single template function which will choose our template
	 *
	 * @access public
	 *
	 * @since  1.3.0
	 *
	 * @param  String $single_template Single template.
	 */
	public function load_canvas_template( $single_template ) {
		global $post;

		if ( 'rael-theme-template' === $post->post_type ) {
			$elementor_2_0_canvas = ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';

			if ( file_exists( $elementor_2_0_canvas ) ) {
				return $elementor_2_0_canvas;
			} else {
				return ELEMENTOR_PATH . '/includes/page-templates/canvas.php';
			}
		}

		return $single_template;
	}

	/**
	 * Add type column to admin.
	 *
	 * @access public
	 *
	 * @param array $columns Columns array.
	 *
	 * @since 1.3.0
	 *
	 * @return array Array of columns after adding type column.
	 */
	public function add_type_column( $columns ) {
		$date_column = $columns['date'];

		unset( $columns['date'] );

		$columns['type'] = __( 'Type', 'responsive-addons-for-elementor' );
		$columns['date'] = $date_column;

		return $columns;
	}

	/**
	 * Add shortcode column to admin.
	 *
	 * @access public
	 *
	 * @param array $columns Columns array.
	 *
	 * @since 1.3.0
	 *
	 * @return array Array of columns after adding shortcode column.
	 */
	public function add_shortcode_column( $columns ) {
		$date_column = $columns['date'];

		unset( $columns['date'] );

		$columns['shortcode'] = __( 'Shortcode', 'responsive-addons-for-elementor' );
		$columns['date']      = $date_column;

		return $columns;
	}

	/**
	 * Render shortcode column.
	 *
	 * @access public
	 *
	 * @since 1.3.0
	 *
	 * @param array $column Column array.
	 * @param int   $post_id post id.
	 *
	 * @return void
	 */
	public function render_shortcode_column( $column, $post_id ) {
		switch ( $column ) {
			case 'shortcode':
				ob_start();
				?>
				<span class="rael-hf__shortcode-column">
					<input type="text" onfocus="this.select();" readonly="readonly" value="[rael_theme_template id='<?php echo esc_attr( $post_id ); ?>']" class="rael-hf__template-shortcode code">
				</span>

				<?php

				ob_get_contents();
				break;
		}
	}

	/**
	 * Render shortcode column.
	 *
	 * @access public
	 *
	 * @since 1.3.0
	 *
	 * @param array $column Column array.
	 * @param int   $post_id post id.
	 *
	 * @return void
	 */
	public function render_type_column( $column, $post_id ) {
		switch ( $column ) {
			case 'type':
				ob_start();
				$template_type = esc_html( get_post_meta( $post_id, 'rael_hf_template_type' )[0] );
				?>
				<span class="rael-hf__type-column">
					<a
					class="rael-hf__template-type"
					href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . $this->post_type . '&' . $this->type_tax . '=' . $template_type ) ); ?>">
					<?php echo esc_html( ucwords( str_replace( '-', ' ', $template_type ) ) ); ?></a>
				</span>

				<?php

				ob_get_contents();
				break;
		}
	}
}

RAEL_HF_Admin::instance();
