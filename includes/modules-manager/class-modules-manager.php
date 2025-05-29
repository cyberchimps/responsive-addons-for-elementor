<?php
/**
 * Modules Mananger for Responsive Addons for Elementor
 *
 * @package Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\ModulesManager;

if ( ! defined( 'WPINC' ) ) {
	exit;
}

use Responsive_Addons_For_Elementor\Traits\Singleton;

/**
 * Class Modules Manager
 *
 * @subpackage Responsive_Addons_For_Elementor\ModulesManager
 */
class Modules_Manager {
	use Singleton;

	/**
	 * Constructor.
	 *
	 * @access private
	 *
	 * @since 1.3.1
	 */
	private function __construct() {
		$this->include_modules();

		// Login | Register.
		$context = new Login_Register();
		add_action( 'init', array( $context, 'login_or_register_user' ) );
		add_filter( 'wp_new_user_notification_email', array( $context, 'new_user_notification_email' ), 10, 3 );
		add_filter( 'wp_new_user_notification_email_admin', array( $context, 'new_user_notification_email_admin' ), 10, 3 );
	}

	/**
	 * Get list of modules.
	 *
	 * Add the module name with Kebab case in lowercase
	 * which should be the name of the root directory of the module.
	 *
	 * @access public
	 *
	 * @since 1.3.1
	 *
	 * @return array Modules list.
	 */
	public function get_modules_list() {
		// Add the directory of the module to register.
		$modules = array(
			'theme-builder',
			'login-register',
		);

		return $modules;
	}

	/**
	 * Include all the modules main file.
	 *
	 * Modules main file should in the format class-<module-name>.php
	 * and the class name should be prefixed with "RAEL_".
	 *
	 * @access public
	 *
	 * @since 1.3.1
	 *
	 * @return void
	 */
	public function include_modules() {
		$modules = $this->get_modules_list();
		foreach ( $modules as $module ) {
			$class_file = "class-{$module}.php";
			$class_name = ucwords( str_replace( '-', '_', $module ), '_' );
			$class_name = __NAMESPACE__ . "\\{$class_name}\\RAEL_{$class_name}";

			require_once "{$module}/{$class_file}";
			if ( class_exists( $class_name ) ) {
				$class_name::instance();
			}
		}
	}
}

Modules_Manager::instance();
