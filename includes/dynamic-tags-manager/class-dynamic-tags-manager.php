<?php
/**
 * Dynamic Tags Mananger for Responsive Addons for Elementor
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\DynamicTagsManager;

if ( ! defined( 'WPINC' ) ) {
	exit;
}

use Responsive_Addons_For_Elementor\Traits\Singleton;
use Elementor\Plugin;

/**
 * Class Dynamic Tags Manager
 *
 * @subpackage Responsive_Addons_For_Elementor\DynamicTagsManager
 */
class Dynamic_Tags_Manager {
	use Singleton;

	/**
	 * Constructor.
	 *
	 * @access private
	 *
	 * @since 1.3.2
	 */
	private function __construct() {
		add_action( 'elementor/dynamic_tags/register', array( $this, 'register_dynamic_tags' ) );

		$this->load_dependencies();
	}
	public function load_dependencies() {
		if ( class_exists( 'WooCommerce' ) ) {
			require_once RAEL_DIR . '/includes/dynamic-tags-manager/dynamic-tags/woocommerce/traits/tag-product-id.php';
			require_once RAEL_DIR . '/includes/dynamic-tags-manager/dynamic-tags/woocommerce/class-base-tag.php';
		}
	}

	/**
	 * Get list of dynamic tags.
	 *
	 * Add the dynamic tag name with Kebab case in lowercase
	 * which should be the name of the root directory of the tag.
	 *
	 * @access public
	 *
	 * @since 1.3.2
	 * @since 1.4.0 added site-logo and site-url
	 *
	 * @return array Dynamic Tag list.
	 */
	public function get_dynamic_tags_list() {
		// Prefix the dynamic tag name with 'woocommerce' but keep the class file name as it should be.
		// This is just to identify if the tag depends on WooCommerce.
		$modules = array(
			'post-title',
			'post-excerpt',
			'featured-image',
			'site-logo',
			'site-url',
			'archive-title',
			'woocommerce-product-title',
		);

		return $modules;
	}

	/**
	 * Include all the dynamic tags main file and register them.
	 *
	 * Tags main file should in the format class-<tag-name>.php
	 * and the class name should be prefixed with "RAEL_".
	 *
	 * @access public
	 *
	 * @param object $dynamic_tags The instance of DynamicTagsManager.
	 *
	 * @since 1.3.2
	 * @since 1.4.0 added site-group
	 *
	 * @return void
	 */
	public function register_dynamic_tags( $dynamic_tags ) {
		$tags = $this->get_dynamic_tags_list();

		// To register Post group.
		Plugin::$instance->dynamic_tags->register_group(
			'rael-post-group',
			array(
				'title' => 'Post',
			)
		);

		// To register Archive group.
		Plugin::$instance->dynamic_tags->register_group(
			'rael-archive-group',
			array(
				'title' => 'Archive',
			)
		);

		// To register Site group.
		Plugin::$instance->dynamic_tags->register_group(
			'rael-site-group',
			array(
				'title' => 'Site',
			)
		);

		if ( class_exists( 'WooCommerce' ) ) {
			// To register WooCommerce group.
			Plugin::$instance->dynamic_tags->register_group(
				'rael-woocommerce-group',
				array(
					'title' => 'WooCommerce',
				)
			);
		}

		foreach ( $tags as $tag ) {
			$file_name = 'class-rael-' . $tag . '.php';
			// checking if $tag starts with woocommerce and then checking if WooCommerce is activated.
			// only then load the widget class.
			if ( str_starts_with( $tag, 'woocommerce' ) ) {
				if ( class_exists( 'WooCommerce' ) ) {
					$file_name = 'class-' . substr( $tag, 12 ) . '.php';
					// Include the Dynamic tag class file.
					include_once 'dynamic-tags/woocommerce/' . $file_name;
				} else {
					continue;
				}
			} else {
				// Include the Dynamic tag class file.
				include_once 'dynamic-tags/' . $file_name;
			}

			// Finally register the tag.
			$class_name = ucwords( str_replace( '-', '_', $tag ), '_' );
			$class_name = __NAMESPACE__ . "\DynamicTags\\RAEL_{$class_name}";

			if ( str_starts_with( $tag, 'woocommerce' ) ) {
				$class_name = ucwords( str_replace( '-', '_', substr( $tag, 12 ) ), '_' );
				$class_name = __NAMESPACE__ . "\DynamicTags\\WooCommerce\\RAEL_{$class_name}";
			}

			$dynamic_tags->register( new $class_name() );
		}
	}
}

Dynamic_Tags_Manager::instance();
