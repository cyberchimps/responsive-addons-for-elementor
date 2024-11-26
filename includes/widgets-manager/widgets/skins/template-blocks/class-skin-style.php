<?php
/**
 * RAEL Skin Style.
 *
 * @package RAEL
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets\Skins\TemplateBlocks;

use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Responsive_Addons_For_Elementor\Helper\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Skin_Style
 */
abstract class Skin_Style {


	/**
	 * Query object
	 *
	 * @since 1.9.1
	 * @var object $query
	 */
	public static $query;

	/**
	 * Query object
	 *
	 * @since 1.9.1
	 * @var object $query_obj
	 */
	public static $query_obj;

	/**
	 * Settings
	 *
	 * @since 1.9.1
	 * @var object $settings
	 */
	public static $settings;

	/**
	 * Skin
	 *
	 * @since 1.9.1
	 * @var object $skin
	 */
	public static $skin;

	/**
	 * Node ID of element
	 *
	 * @since 1.9.1
	 * @var object $node_id
	 */
	public static $node_id;

	/**
	 * Rendered Settings
	 *
	 * @since 1.9.1
	 * @var object $_render_attributes
	 */
	public $_render_attributes; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore


	/**
	 * Get featured image.
	 *
	 * Returns the featured image HTML wrap.
	 *
	 * @since 1.9.1
	 * @access public
	 */
	public function render_featured_image() {

		$settings = self::$settings;

		$thumbnail = $this->get_instance_value( 'thumbnail' );

		if ( 'none' === $thumbnail && ! Plugin::instance()->editor->is_edit_mode() ) {
			return;
		}

		$settings                 = self::$settings;
		$setting_key              = $settings[ $this->rael_prepend_skin( 'thumbnail_size' ) . '_size' ];
		$settings[ $setting_key ] = array(
			'id' => get_post_thumbnail_id(),
		);
		$thumbnail_html           = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );

		if ( empty( $thumbnail_html ) ) {
			return;
		}

		$optional_attributes_html = $this->get_optional_link_attributes_html();

		?>
		<a class="responsive-post__thumbnail__link" href="<?php echo esc_url( get_the_permalink() ); ?>" <?php echo esc_html( $optional_attributes_html ); ?>>
			<div class="elementor-post__thumbnail"><?php echo wp_kses_post( $thumbnail_html ); ?></div>
		</a>
		<?php
		if ( 'rael_cards' === $settings['_skin'] ) {
			if ( $this->get_instance_value( 'show_badge' ) ) {
				$this->render_badge();
			}
			if ( $this->get_instance_value( 'show_avatar' ) ) {
				$this->render_avatar();
			}
		}
	}

	/**
	 * Displays the avatar image.
	 *
	 * Returns the avatar image of the user.
	 *
	 * @since 1.9.1
	 * @access public
	 */
	public function render_avatar() {
		?>
		<div class="elementor-post__avatar">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 128, '', get_the_author_meta( 'display_name' ) ); ?>
		</div>
		<?php
	}

	/**
	 * Displays the badge.
	 *
	 * Returns the term and displays it in the badge.
	 *
	 * @since 1.9.1
	 * @access public
	 */
	public function render_badge() {
		$taxonomy = $this->get_instance_value( 'badge_taxonomy' );
		if ( empty( $taxonomy ) ) {
			return;
		}

		$terms = get_the_terms( get_the_ID(), $taxonomy );
		if ( empty( $terms[0] ) ) {
			return;
		}
		?>
		<div class="elementor-post__badge"><?php echo esc_html( $terms[0]->name ); ?></div>
		<?php
	}

	/**
	 * Get post title.
	 *
	 * Returns the post title HTML wrap.
	 *
	 * @since 1.9.1
	 * @access public
	 */
	public function render_title() {

		if ( ! $this->get_instance_value( 'show_title' ) ) {
			return;
		}

		$optional_attributes_html = $this->get_optional_link_attributes_html();

		$tag = $this->get_instance_value( 'title_tag' );
		?>
		<<?php echo esc_html( $tag ); ?> class="elementor-post__title">
		<a href="<?php echo esc_url( get_the_permalink() ); ?>" <?php echo esc_attr( $optional_attributes_html ); ?>>
			<?php the_title(); ?>
		</a>
		</<?php echo esc_html( $tag ); ?>>
		<?php
	}

	/**
	 * Get post title.
	 *
	 * Returns the post title HTML wrap.
	 *
	 * @param string $setting Setting Name.
	 * @since 1.9.1
	 * @access public
	 */
	public function rael_prepend_skin( $setting ) {
		$settings = self::$settings;
		$skin     = $settings['_skin'];
		return $skin . '_' . $setting;
	}


	/**
	 * Get post meta.
	 *
	 * Returns the post meta HTML wrap.
	 *
	 * @since 1.9.1
	 * @access public
	 */
	public function render_meta_data() {

		$settings = self::$settings;

		do_action( 'rael_single_post_before_meta', get_the_ID(), $settings );

		$sequence = apply_filters( 'rael_post_meta_sequence', $settings[ $this->rael_prepend_skin( 'meta_data' ) ] );
		?>
		<div class="elementor-post__meta-data">
		<?php
		foreach ( $sequence as $key => $seq ) {
			switch ( $seq ) {
				case 'author':
					$this->render_author();
					break;

				case 'date':
					$this->render_date();
					break;

				case 'comments':
					$this->render_comments();
					break;

				case 'time':
					$this->render_time();
					break;
			}
		}
		?>
		</div>
		<?php
		do_action( 'rael_single_post_after_meta', get_the_ID(), $settings );
	}

	/**
	 * Get post author.
	 *
	 * Returns the post author HTML wrap.
	 *
	 * @since 1.9.1
	 * @access public
	 */
	public function render_author() {

		$settings = self::$settings;

		do_action( 'rael_single_post_before_author', get_the_ID(), $settings );
		?>
		<span class="elementor-post-author"><?php the_author(); ?></span>
		<?php
		do_action( 'rael_single_post_after_author', get_the_ID(), $settings );
	}

	/**
	 * Get post published date.
	 *
	 * Returns the post published date HTML wrap.
	 *
	 * @since 1.9.1
	 * @access public
	 */
	public function render_date() {

		$settings = self::$settings;

		do_action( 'rael_single_post_before_date', get_the_ID(), $settings );
		?>
		<span class="elementor-post-date">
			<?php
			/** This filter is documented in wp-includes/general-template.php */
			echo wp_kses_post( apply_filters( 'the_date', get_the_date(), get_option( 'date_format' ), '', '' ) );
			?>
		</span>
		<?php
		do_action( 'rael_single_post_after_date', get_the_ID(), $settings );
	}

	/**
	 * Get post related comments.
	 *
	 * Returns the post related comments HTML wrap.
	 *
	 * @since 1.9.1
	 * @access public
	 */
	public function render_comments() {

		$settings = self::$settings;

		do_action( 'rael_single_post_before_comments', get_the_ID(), $settings );
		?>
		<span class="elementor-post-avatar">
			<?php comments_number(); ?>
		</span>
		<?php
		do_action( 'rael_single_post_after_comments', get_the_ID(), $settings );
	}

	/**
	 * Get post related comments.
	 *
	 * Returns the post related comments HTML wrap.
	 *
	 * @since 1.9.1
	 * @access public
	 */
	public function render_time() {
		$settings = self::$settings;

		do_action( 'rael_single_post_before_time', get_the_ID(), $settings );
		?>
		<span class="elementor-post-time">
			<?php the_time(); ?>
		</span>
		<?php
		do_action( 'rael_single_post_after_time', get_the_ID(), $settings );
	}

	/**
	 * Get post related terms.
	 *
	 * Returns the post related terms HTML wrap.
	 *
	 * @param string $position Position value of term.
	 * @since 1.9.1
	 * @access public
	 */
	public function render_terms( $position ) {

		$settings = self::$settings;

		if ( $position !== $this->get_instance_value( 'terms_position' ) ) {
			return;
		}

		$this->render_term_html();
	}

	/**
	 * Get post excerpt.
	 *
	 * Returns the post excerpt HTML wrap.
	 *
	 * @since 1.9.1
	 * @access public
	 */
	public function render_excerpt() {

		add_filter( 'excerpt_more', array( $this, 'filter_excerpt_more' ), 20 );
		add_filter( 'excerpt_length', array( $this, 'filter_excerpt_length' ), 20 );

		if ( ! $this->get_instance_value( 'show_excerpt' ) ) {
			return;
		}

		add_filter( 'excerpt_more', array( $this, 'filter_excerpt_more' ), 20 );
		add_filter( 'excerpt_length', array( $this, 'filter_excerpt_length' ), 20 );

		?>
		<div class="elementor-post__excerpt">
		<?php
			global $post;
			$apply_to_custom_excerpt = $this->get_instance_value( 'apply_to_custom_excerpt' );

			// Force the manually-generated Excerpt length as well if the user chose to enable 'apply_to_custom_excerpt'.
		if ( 'yes' === $apply_to_custom_excerpt && ! empty( $post->post_excerpt ) ) {
				$max_length = (int) $this->get_instance_value( 'excerpt_length' );
				$excerpt    = apply_filters( 'the_excerpt', get_the_excerpt() );
				$excerpt    = $this->trim_words( $excerpt, $max_length );
				echo wp_kses_post( $excerpt );
		} else {
			the_excerpt();
		}
		?>
		</div>
		<?php

		remove_filter( 'excerpt_length', array( $this, 'filter_excerpt_length' ), 20 );
		remove_filter( 'excerpt_more', array( $this, 'filter_excerpt_more' ), 20 );
	}

	/**
	 * Get post excerpt length.
	 *
	 * Returns the length of post excerpt.
	 *
	 * @since 1.9.1
	 * @access public
	 */
	public function filter_excerpt_length() {
		return $this->get_instance_value( 'excerpt_length' );
	}

	/**
	 * Get post excerpt end text.
	 *
	 * Returns the string to append to post excerpt.
	 *
	 * @param string $more returns string.
	 * @since 1.9.1
	 * @access public
	 */
	public function filter_excerpt_more( $more ) {
		return '';
	}

	/**
	 * Get Trimmed Words.
	 *
	 * Returns the text according the provided length.
	 *
	 * @param string  $text text to be trimmed.
	 * @param integer $length length of the text.
	 * @since 1.9.1
	 * @access public
	 */
	public static function trim_words( $text, $length ) {
		if ( $length && str_word_count( $text ) > $length ) {
			$text = explode( ' ', $text, $length + 1 );
			unset( $text[ $length ] );
			$text = implode( ' ', $text );
		}

		return $text;
	}

	/**
	 * Get post call to action.
	 *
	 * Returns the post call to action HTML wrap.
	 *
	 * @since 1.9.1
	 * @access public
	 */
	public function render_read_more() {

		if ( ! $this->get_instance_value( 'show_read_more' ) ) {
			return;
		}

		$optional_attributes_html = $this->get_optional_link_attributes_html();
		$read_more_button_size    = $this->get_instance_value( 'read_more_button_size' );

		$read_more_button_hover_animation = $this->get_instance_value( 'read_more_button_hover_animation' ) !== '' ? 'elementor-animation-' . $this->get_instance_value( 'read_more_button_hover_animation' ) : '';

		?>
		<div class="elementor-post__read-more__container">
		<?php
		if ( 'text' === $this->get_instance_value( 'read_more_type' ) ) {
			?>
				<a class="elementor-post__read-more" href="<?php echo esc_url( get_permalink() ); ?>" <?php echo esc_attr( $optional_attributes_html ); ?>>
					<?php echo esc_html( $this->get_instance_value( 'read_more_text' ) ); ?>
				</a>
				<?php
		} else {
			?>
				<a href="<?php echo esc_url( get_permalink() ); ?>" class="elementor-button-link  elementor-button elementor-size-<?php echo esc_attr( $read_more_button_size ); ?> <?php echo esc_attr( $read_more_button_hover_animation ); ?>" <?php echo esc_html( $optional_attributes_html ); ?>>
					<span class="elementor-button-text"><?php echo esc_html( $this->get_instance_value( 'read_more_text' ) ); ?></span>
				</a>
				<?php
		}
		?>
		</div>
		<?php
	}

	/**
	 * Opens in new tab.
	 *
	 * Returns whether to open link in new tab.
	 *
	 * @since 1.9.1
	 * @access public
	 */
	public function get_optional_link_attributes_html() {
		$optional_attributes_html = 'yes' === $this->get_instance_value( 'open_new_tab' ) ? 'target="_blank"' : '';
		return $optional_attributes_html;
	}

	/**
	 * Get Pagination.
	 *
	 * Returns the Pagination HTML.
	 *
	 * @since 1.9.1
	 * @access public
	 */
	public function render_pagination() {

		$settings = self::$settings;

		$paged = self::$query_obj->get_paged();

		if ( 'none' === $settings['pagination_type'] ) {
			return;
		}

		$page_limit = self::$query->max_num_pages;

		if ( '' !== $settings['pagination_page_limit'] ) {
			$page_limit = min( $settings['pagination_page_limit'], $page_limit );
		}

		if ( 2 > $page_limit && 'infinite' !== $settings['pagination_type'] ) {
			return;
		}

		$has_numbers   = in_array( $settings['pagination_type'], array( 'numbers', 'numbers_and_prev_next' ), true );
		$has_prev_next = in_array( $settings['pagination_type'], array( 'prev_next', 'numbers_and_prev_next' ), true );

		$links = array();

		if ( $has_numbers ) {
			$paginate_args = array(
				'type'               => 'array',
				'current'            => $paged,
				'total'              => $page_limit,
				'prev_next'          => false,
				'show_all'           => 'yes' !== $settings['pagination_numbers_shorten'],
				'before_page_number' => '<span class="elementor-screen-only">' . __( 'Page', 'responsive-addons-for-elementor' ) . '</span>',
			);

			if ( is_singular() && ! is_front_page() ) {
				global $wp_rewrite;
				if ( $wp_rewrite->using_permalinks() ) {
					$paginate_args['base']   = trailingslashit( get_permalink() ) . '%_%';
					$paginate_args['format'] = user_trailingslashit( '%#%', 'single_paged' );
				} else {
					$paginate_args['format'] = '?page=%#%';
				}
			}

			$links = paginate_links( $paginate_args );

		}

		if ( $has_prev_next ) {
			$prev_next = $this->get_posts_nav_link( $page_limit );
			array_unshift( $links, $prev_next['prev'] );
			$links[] = $prev_next['next'];
		}

		$class = 'infinite' === $settings['pagination_type'] && $paged == self::$query->max_num_pages ? 'style="display:none;"' : '';

		?>
		<nav class="elementor-pagination rael-post-pagination" <?php echo wp_kses_post( $class ); ?> role="navigation" aria-label="<?php esc_attr_e( 'Pagination', 'responsive-addons-for-elementor' ); ?>">
		<?php
		if ( 'infinite' === $settings['pagination_type'] ) {
			?>
				<button class="rael_pagination_load_more">
					<?php echo '' === $settings['pagination_infinite_button_label'] ? esc_html__( 'Load More', 'responsive-addons-for-elementor' ) : esc_html( $settings['pagination_infinite_button_label'] ); ?>
				</button>
			<?php
		} else {
			echo implode( PHP_EOL, $links ); // phpcs:ignore
		}
		?>
		</nav>
		<?php
	}

	/**
	 * Get Posts Navigation Link.
	 *
	 * Returns the Pagination Links.
	 *
	 * @param integer $page_limit page limit.
	 * @since 1.9.1
	 * @access public
	 */
	public function get_posts_nav_link( $page_limit = null ) {
		$settings = self::$settings;

		if ( ! $page_limit ) {
			$page_limit = $this->query->max_num_pages;
		}

		$return = array();

		$paged = self::$query_obj->get_paged();

		$link_template     = '<a class="page-numbers %s" href="%s">%s</a>';
		$disabled_template = '<span class="page-numbers %s">%s</span>';

		if ( $paged > 1 ) {
			$next_page = intval( $paged ) - 1;
			if ( $next_page < 1 ) {
				$next_page = 1;
			}

			$return['prev'] = sprintf( $link_template, 'prev', $this->get_wp_link_page( $next_page ), $settings['pagination_prev_label'] );
		} else {
			$return['prev'] = sprintf( $disabled_template, 'prev', $settings['pagination_prev_label'] );
		}

		$next_page = intval( $paged ) + 1;

		if ( $next_page <= $page_limit ) {
			$return['next'] = sprintf( $link_template, 'next', $this->get_wp_link_page( $next_page ), $settings['pagination_next_label'] );
		} else {
			$return['next'] = sprintf( $disabled_template, 'next', $settings['pagination_next_label'] );
		}

		return $return;
	}

	/**
	 * Get Current Page.
	 *
	 * Returns the paged number.
	 *
	 * @since 1.9.1
	 * @access public
	 */
	public function get_current_page() {
		$settings = self::$settings;
		if ( '' == $settings['pagination_type'] ) {
			return 1;
		}

		return max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
	}

	/**
	 * Get Page Link in pagination.
	 *
	 * Returns the Pagination link for a page.
	 *
	 * @param integer $i page number.
	 * @since 1.9.1
	 * @access public
	 */
	public function get_wp_link_page( $i ) {
		if ( ! is_singular() || is_front_page() ) {
			return get_pagenum_link( $i );
		}

		// Based on wp-includes/post-template.php:957 `_wp_link_page`.
		global $wp_rewrite;
		$post       = get_post();
		$query_args = array();
		$url        = get_permalink();

		if ( $i > 1 ) {
			if ( '' == get_option( 'permalink_structure' ) || in_array( $post->post_status, array( 'draft', 'pending' ), true ) ) {
				$url = add_query_arg( 'page', $i, $url );
			} elseif ( get_option( 'show_on_front' ) == 'page' && (int) get_option( 'page_on_front' ) == $post->ID ) {
				$url = trailingslashit( $url ) . user_trailingslashit( "$wp_rewrite->pagination_base/" . $i, 'single_paged' );
			} else {
				$url = trailingslashit( $url ) . user_trailingslashit( $i, 'single_paged' );
			}
		}

		if ( is_preview() ) {
			if ( ( 'draft' != $post->post_status ) && isset( $_GET['preview_id'], $_GET['preview_nonce'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$query_args['preview_id']    = wp_unslash( $_GET['preview_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$query_args['preview_nonce'] = wp_unslash( $_GET['preview_nonce'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			}

			$url = get_preview_post_link( $post, $query_args, $url );
		}

		return $url;
	}

	/**
	 * Get category name.
	 *
	 * Adds the category class.
	 *
	 * @since 1.20.0
	 * @access public
	 */
	public function get_category_name() {

		foreach ( get_the_category( get_the_ID() ) as $category ) {

			$category_name = str_replace( ' ', '-', $category->name );

			echo esc_attr( strtolower( $category_name ) ) . ' ';
		}
	}

	/**
	 * Render settings array for selected skin
	 *
	 * @since 1.9.1
	 * @param string $control_base_id Skin ID.
	 * @access public
	 */
	public function get_instance_value( $control_base_id ) {
		if ( isset( self::$settings[ self::$skin . '_' . $control_base_id ] ) ) {
			return self::$settings[ self::$skin . '_' . $control_base_id ];
		} else {
			return null;
		}
	}

	/**
	 * Add render attribute.
	 *
	 * Used to add attributes to a specific HTML element.
	 *
	 * The HTML tag is represented by the element parameter, then you need to
	 * define the attribute key and the attribute key. The final result will be:
	 * `<element attribute_key="attribute_value">`.
	 *
	 * Example usage:
	 *
	 * `$this->add_render_attribute( 'wrapper', 'class', 'custom-widget-wrapper-class' );`
	 * `$this->add_render_attribute( 'widget', 'id', 'custom-widget-id' );`
	 * `$this->add_render_attribute( 'button', [ 'class' => 'custom-button-class', 'id' => 'custom-button-id' ] );`
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array|string $element   The HTML element.
	 * @param array|string $key       Optional. Attribute key. Default is null.
	 * @param array|string $value     Optional. Attribute value. Default is null.
	 * @param bool         $overwrite Optional. Whether to overwrite existing
	 *                                attribute. Default is false, not to overwrite.
	 *
	 * @return Element_Base Current instance of the element.
	 */
	public function add_render_attribute( $element, $key = null, $value = null, $overwrite = false ) {
		if ( is_array( $element ) ) {
			foreach ( $element as $element_key => $attributes ) {
				$this->add_render_attribute( $element_key, $attributes, null, $overwrite );
			}

			return $this;
		}

		if ( is_array( $key ) ) {
			foreach ( $key as $attribute_key => $attributes ) {
				$this->add_render_attribute( $element, $attribute_key, $attributes, $overwrite );
			}

			return $this;
		}

		if ( empty( $this->_render_attributes[ $element ][ $key ] ) ) {
			$this->_render_attributes[ $element ][ $key ] = array();
		}

		settype( $value, 'array' );

		if ( $overwrite ) {
			$this->_render_attributes[ $element ][ $key ] = $value;
		} else {
			$this->_render_attributes[ $element ][ $key ] = array_merge( $this->_render_attributes[ $element ][ $key ], $value );
		}

		return $this;
	}

	/**
	 * Get render attribute string.
	 *
	 * Used to retrieve the value of the render attribute.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array|string $element The element.
	 *
	 * @return string Render attribute string, or an empty string if the attribute
	 *                is empty or not exist.
	 */
	public function get_render_attribute_string( $element ) {
		if ( empty( $this->_render_attributes[ $element ] ) ) {
			return '';
		}

		$render_attributes = $this->_render_attributes[ $element ];

		$attributes = array();

		foreach ( $render_attributes as $attribute_key => $attribute_values ) {
			$attributes[] = sprintf( '%1$s="%2$s"', $attribute_key, esc_attr( implode( ' ', $attribute_values ) ) );
		}

		return implode( ' ', $attributes );
	}

	/**
	 * Render post HTML via AJAX call.
	 *
	 * @param array|string $style_id  The style ID.
	 * @param array|string $widget    Widget object.
	 * @since 1.9.1
	 * @access public
	 */
	public function inner_render( $style_id, $widget ) {

		ob_start();

		$term = ( isset( $_POST['data']['term'] ) ) ? $_POST['data']['term'] : '';

		self::$settings = $widget->get_settings_for_display();
		require_once RAEL_DIR . 'includes/widgets-manager/widgets/skins/template-blocks/class-build-post-query.php';
		self::$query_obj = new Build_Post_Query( $style_id, self::$settings, $term );
		self::$query_obj->query_posts();
		self::$query = self::$query_obj->get_query();
		self::$skin  = $style_id;
		$query       = self::$query;
		// $settings and $is_featured are used in the templates files.
		$settings    = self::$settings;
		$is_featured = false;
		$count       = 0;

		while ( $query->have_posts() ) {
			$is_featured = false;

			if ( 0 === $count && 'featured' === $this->get_instance_value( 'post_structure' ) ) {
				$is_featured = true;
			}

			$query->the_post();
			include RAEL_DIR . 'includes/widgets-manager/widgets/skins/templates/content-post-' . str_replace( '_', '-', $style_id ) . '.php';

			$count++;
		}

		wp_reset_postdata();

		return ob_get_clean();
	}

	/**
	 * Render post pagination HTML via AJAX call.
	 *
	 * @param array|string $style_id  The style ID.
	 * @param array|string $widget    Widget object.
	 * @since 1.9.1
	 * @access public
	 */
	public function page_render( $style_id, $widget ) {

		ob_start();

		$term = ( isset( $_POST['data']['term'] ) ) ? $_POST['data']['term'] : '';

		self::$settings  = $widget->get_settings_for_display();
		self::$query_obj = new Build_Post_Query( $style_id, self::$settings, $term );
		self::$query_obj->query_posts();
		self::$query = self::$query_obj->get_query();
		self::$skin  = $style_id;
		$query       = self::$query;
		$settings    = self::$settings;
		$is_featured = false;

		$this->render_pagination();

		return ob_get_clean();
	}
}
