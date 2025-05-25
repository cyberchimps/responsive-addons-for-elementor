<?php

namespace Responsive_Addons_For_Elementor\ModulesManager;

use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for making login-register widget form handling.
 */
class Login_Register {

	/**
	 * @var bool
	 */
	public static $send_custom_email       = false;
	public static $send_custom_email_admin = false;
	/**
	 * It will contain all email related options like email subject, content, email content type etc.
	 *
	 * @var array   $email_options {
	 * Used to build wp_mail().
	 * @type string $template_type The type of the email template; custom | default.
	 * @type string $subject       The subject of the email.
	 * @type string $message       The body of the email.
	 * @type string $content_type  The type of the email body; plain | html
	 * }
	 */
	public static $email_options = array();

	public function login_or_register_user() {
		// login or register form?
		do_action( 'rael/login-register/before-processing-login-register', $_POST );
		if ( isset( $_POST['rael-login-submit'] ) ) {
			$this->log_user_in();
		} elseif ( isset( $_POST['rael-register-submit'] ) ) {
			$this->register_user();
		}
		do_action( 'rael/login-register/after-processing-login-register', $_POST );
	}

	/**
	 * It logs the user in when the login form is submitted normally without AJAX.
	 */
	public function log_user_in() {
		$ajax = wp_doing_ajax();
		// before even thinking about login, check security and exit early if something is not right.
		$page_id = 0;
		if ( ! empty( $_POST['page_id'] ) ) {
			$page_id = intval( $_POST['page_id'], 10 );
		} else {
			$err_msg = __( 'Page ID is missing.', 'responsive-addons-for-elementor' );
		}

		$widget_id = 0;
		if ( ! empty( $_POST['widget_id'] ) ) {
			$widget_id = sanitize_text_field( $_POST['widget_id'] );
		} else {
			$err_msg = __( 'Widget ID is missing.', 'responsive-addons-for-elementor' );
		}

		if ( ! empty( $err_msg ) ) {
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'rael_login_error_' . $widget_id, $err_msg, false );

			if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
				wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
				exit();
			}
		}

		if ( empty( $_POST['rael-login-nonce'] ) ) {
			$err_msg = __( 'Insecure form submitted without a security token.', 'responsive-addons-for-elementor' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'rael_login_error_' . $widget_id, $err_msg, false );

			if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
				wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
				exit();
			}
		}

		if ( ! wp_verify_nonce( $_POST['rael-login-nonce'], 'rael-login-action' ) ) {
			$err_msg = __( 'Security token did not match.', 'responsive-addons-for-elementor' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'rael_login_error_' . $widget_id, $err_msg, false );

			if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
				wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
				exit();
			}
		}
		$settings = $this->rael_get_widget_settings( $page_id, $widget_id );

		if ( is_user_logged_in() ) {
			$err_msg = isset( $settings['rael_error_already_logged_in'] ) ? $settings['rael_error_already_logged_in'] : __( 'You are already logged in', 'responsive-addons-for-elementor' );

			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}

			update_option( 'rael_login_error_' . $widget_id, $err_msg, false );

			if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
				wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
				exit();
			}
		}

		do_action( 'rael/login-register/before-login' );

		$widget_id = ! empty( $_POST['widget_id'] ) ? sanitize_text_field( $_POST['widget_id'] ) : '';
		if ( isset( $_POST['g_recaptcha_enabled'] ) && ! $this->rael_lr_validate_recaptcha() ) {
			$err_msg = isset( $settings['rael_error_recaptcha'] ) ? $settings['rael_error_recaptcha'] : __( 'You did not pass the reCAPTCHA validation.', 'responsive-addons-for-elementor' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'rael_login_error_' . $widget_id, $err_msg, false );

			if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
				wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
				exit();
			}
		}

		$user_login = ! empty( $_POST['rael-login-name'] ) ? sanitize_text_field( $_POST['rael-login-name'] ) : '';
		if ( is_email( $user_login ) ) {
			$user_login = sanitize_email( $user_login );
		}

		$password   = ! empty( $_POST['rael-login-password'] ) ? sanitize_text_field( $_POST['rael-login-password'] ) : '';
		$rememberme = ! empty( $_POST['rael-remember-me'] ) ? sanitize_text_field( $_POST['rael-remember-me'] ) : '';

		$credentials = array(
			'user_login'    => $user_login,
			'user_password' => $password,
			'remember'      => ( 'checked' === $rememberme ),
		);
		$user_data   = wp_signon( $credentials );

		if ( is_wp_error( $user_data ) ) {
			$err_msg = '';
			if ( isset( $user_data->errors['invalid_email'][0] ) ) {
				$err_msg = isset( $settings['rael_error_email'] ) ? $settings['rael_error_email'] : __( 'Invalid Email.', 'responsive-addons-for-elementor' );
			} elseif ( isset( $user_data->errors['invalid_username'][0] ) ) {
				$err_msg = isset( $settings['rael_error_username'] ) ? $settings['rael_error_username'] : __( 'Invalid Username.', 'responsive-addons-for-elementor' );

			} elseif ( isset( $user_data->errors['incorrect_password'][0] ) || isset( $user_data->errors['empty_password'][0] ) ) {
				$err_msg = isset( $settings['rael_error_password'] ) ? $settings['rael_error_password'] : __( 'Invalid Password', 'responsive-addons-for-elementor' );
			}

			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'rael_login_error_' . $widget_id, $err_msg, false );
		} else {
			wp_set_current_user( $user_data->ID, $user_login );
			do_action( 'wp_login', $user_data->user_login, $user_data );
			do_action( 'rael/login-register/after-login', $user_data->user_login, $user_data );
			if ( $ajax ) {

				$data = array(
					'message' => isset( $settings['rael_success_login'] ) ? $settings['rael_success_login'] : __( 'You are logged in successfully', 'responsive-addons-for-elementor' ),
				);
				if ( ! empty( $_POST['redirect_to'] ) ) {
					$data['redirect_to'] = esc_url( $_POST['redirect_to'] );
				}
				wp_send_json_success( $data );
			}

			if ( ! empty( $_POST['redirect_to'] ) ) {
				wp_safe_redirect( esc_url( $_POST['redirect_to'] ) );
				exit();
			}
		}
		if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
			wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
			exit();
		}
	}

	public function register_user() {
		$ajax = wp_doing_ajax();

		if ( is_user_logged_in() ) {
			$err_msg = isset( $settings['rael_error_already_logged_in'] ) ? $settings['rael_error_already_logged_in'] : __( 'You are already logged in', 'responsive-addons-for-elementor' );

			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
		}

		if ( empty( $_POST['rael-register-nonce'] ) ) {
			$err_msg = __( 'Insecure form submitted without a security token.', 'responsive-addons-for-elementor' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
		}
		if ( ! wp_verify_nonce( $_POST['rael-register-nonce'], 'rael-register-action' ) ) {
			$err_msg = __( 'Security token did not match.', 'responsive-addons-for-elementor' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
		}
		$page_id   = 0;
		$widget_id = 0;
		if ( ! empty( $_POST['page_id'] ) ) {
			$page_id = intval( $_POST['page_id'] );
		} else {
			$err_msg = __( 'Page ID is missing.', 'responsive-addons-for-elementor' );
		}
		if ( ! empty( $_POST['widget_id'] ) ) {
			$widget_id = sanitize_text_field( $_POST['widget_id'] );
		} else {
			$err_msg = __( 'Widget ID is missing.', 'responsive-addons-for-elementor' );
		}

		if ( ! empty( $err_msg ) ) {
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			update_option( 'rael_register_errors_' . $widget_id, $err_msg, false );

			if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
				wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
				exit();
			}
			return false;
		}

		$settings = $this->rael_get_widget_settings( $page_id, $widget_id );

		do_action( 'rael/login-register/before-register' );

		// prepare the data.
		$errors               = array();
		$registration_allowed = get_option( 'users_can_register' );
		$protocol             = is_ssl() ? 'https://' : 'http://';
		$url                  = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		// vail early if reg is closed.
		if ( ! $registration_allowed ) {
			$errors['registration'] = __( 'Sorry, registration for this site has been disabled.', 'responsive-addons-for-elementor' );
			if ( $ajax ) {
				wp_send_json_error( $errors['registration'] );
			}

			// if we redirect to other page, we dont need to save value.
			wp_safe_redirect( site_url( 'wp-login.php?registration=disabled' ) );
			exit();
		}

		// prepare vars and flag errors.
		$tc_check = ! empty( $_POST['terms_and_conditions_check'] ) ? sanitize_text_field( $_POST['terms_and_conditions_check'] ) : '';
		if ( 'nonexistant' === $_POST['no_terms_and_conditions_check'] ) {
			// do nothing if there are no terms and conditions to accept.
		} elseif ( 'checked' !== $tc_check && ! isset( $_POST['no_terms_and_conditions_check'] ) ) {
			$errors['terms_conditions'] = isset( $settings['rael_error_tc'] ) ? $settings['rael_error_tc'] : __( 'Please accept the Terms & Conditions and try again.', 'responsive-addons-for-elementor' );
		}
		if ( isset( $_POST['g_recaptcha_enabled'] ) && ! $this->rael_lr_validate_recaptcha() ) {
			$errors['recaptcha'] = isset( $settings['rael_error_recaptcha'] ) ? $settings['rael_error_recaptcha'] : __( 'You did not pass the reCAPTCHA validation.', 'responsive-addons-for-elementor' );
		}

		if ( ! empty( $_POST['email'] ) && is_email( $_POST['email'] ) ) {
			$email = sanitize_email( $_POST['email'] );
			if ( email_exists( $email ) ) {
				$errors['email'] = isset( $settings['rael_error_email_used'] ) ? $settings['rael_error_email_used'] : __( 'The provided email is already registered with another account. Please login or reset password or use another email.', 'responsive-addons-for-elementor' );
			}
		} else {
			$errors['email'] = isset( $settings['rael_error_email_missing'] ) ? $settings['rael_error_email_missing'] : __( 'Email is missing or Invalid.', 'responsive-addons-for-elementor' );
		}

		// if user provided a user_name then validate & sanitize it.
		if ( isset( $_POST['user_name'] ) ) {
			$username = sanitize_text_field( $_POST['user_name'] );

			if ( ! $username ) {
				$username = sanitize_email( $_POST['email'] );
			}

			if ( mb_strlen( $username ) > 60 ) {
				$errors['user_name'] = isset( $settings['rael_error_username'] ) ? $settings['rael_error_username'] : __( 'Invalid username provided.', 'responsive-addons-for-elementor' );
			} elseif ( username_exists( $username ) ) {
				$errors['user_name'] = isset( $settings['rael_error_username_used'] ) ? $settings['rael_error_username_used'] : __( 'Provided username is already registered. Please use a different username', 'responsive-addons-for-elementor' );
			}
		} else {
			$errors['username'] = __( 'Username is missing.', 'responsive-addons-for-elementor' );
		}

		if ( ! empty( $_POST['password'] ) ) {
			$password = wp_unslash( sanitize_text_field( $_POST['password'] ) );
		}
		if ( isset( $_POST['confirm_pass'] ) ) {
			$confirm_pass = wp_unslash( sanitize_text_field( $_POST['confirm_pass'] ) );
			if ( $confirm_pass !== $password ) {
				$errors['confirm_pass'] = isset( $settings['rael_error_confirm_password'] ) ? $settings['rael_error_confirm_password'] : __( 'Password and Confirm Password must match.', 'responsive-addons-for-elementor' );
			}
		}

		// if any error found then abort.
		if ( ! empty( $errors ) ) {
			if ( $ajax ) {
				$err_msg = '<ol>';
				foreach ( $errors as $error ) {
					$err_msg .= "<li>{$error}</li>";
				}
				$err_msg .= '</ol>';
				wp_send_json_error( $err_msg );
			}
			update_option( 'rael_register_errors_' . $widget_id, $errors, false );
			wp_safe_redirect( esc_url( $url ) );
			exit();
		}

		/*------General Mail Related Stuff------*/
		self::$email_options['username']            = $username;
		self::$email_options['password']            = $password;
		self::$email_options['email']               = $email;
		self::$email_options['firstname']           = '';
		self::$email_options['lastname']            = '';
		self::$email_options['website']             = '';
		self::$email_options['password_reset_link'] = '';

		// handle registration...
		$user_data = array(
			'user_login' => $username,
			'user_pass'  => $password,
			'user_email' => $email,
		);

		if ( ! empty( $_POST['first_name'] ) ) {
			$user_data['first_name']          = sanitize_text_field( $_POST['first_name'] );
			self::$email_options['firstname'] = sanitize_text_field( $_POST['first_name'] );
		}
		if ( ! empty( $_POST['last_name'] ) ) {
			$user_data['last_name']          = sanitize_text_field( $_POST['last_name'] );
			self::$email_options['lastname'] = sanitize_text_field( $_POST['last_name'] );
		}
		if ( ! empty( $_POST['website'] ) ) {
			$user_data['user_url']          = esc_url_raw( $_POST['website'] );
			self::$email_options['website'] = esc_url_raw( $_POST['website'] );
		}
		$register_actions    = array();
		$custom_redirect_url = '';
		if ( ! empty( $settings ) ) {
			$register_actions    = ! empty( $settings['rael_successful_register_action'] ) ? (array) $settings['rael_successful_register_action'] : array();
			$custom_redirect_url = ! empty( $settings['rael_register_redirect_url']['url'] ) ? $settings['rael_register_redirect_url']['url'] : '/';
			if ( ! empty( $settings['rael_register_user_role'] ) ) {
				$user_data['role'] = sanitize_text_field( $settings['rael_register_user_role'] );
			}

			// set email related stuff.
			/*------User Mail Related Stuff------*/
			if ( in_array( 'send_email', $register_actions ) && 'custom' === $settings['rael_reg_email_template_type'] ) {
				self::$send_custom_email = true;
			}
			if ( isset( $settings['rael_reg_email_subject'] ) ) {
				self::$email_options['subject'] = $settings['rael_reg_email_subject'];
			}
			if ( isset( $settings['rael_reg_email_message'] ) ) {
				// Added esc_html to strip all tags from the message.
				self::$email_options['message'] =  sanitize_text_field( $settings['rael_reg_email_message'] ) ;
			}
			if ( isset( $settings['rael_reg_email_content_type'] ) ) {
				self::$email_options['headers'] = 'Content-Type: text/' . $settings['rael_reg_email_content_type'] . '; charset=UTF-8' . "\r\n";
			}

			/*------Admin Mail Related Stuff------*/
			self::$send_custom_email_admin = ( ! empty( $settings['rael_reg_admin_email_template_type'] ) && 'custom' === $settings['rael_reg_admin_email_template_type'] );
			if ( isset( $settings['rael_reg_admin_email_subject'] ) ) {
				self::$email_options['admin_subject'] = $settings['rael_reg_admin_email_subject'];
			}
			if ( isset( $settings['rael_reg_admin_email_message'] ) ) {
				self::$email_options['admin_message'] = $settings['rael_reg_admin_email_message'];
			}
			if ( isset( $settings['rael_reg_admin_email_content_type'] ) ) {
				self::$email_options['admin_headers'] = 'Content-Type: text/' . $settings['rael_reg_admin_email_content_type'] . '; charset=UTF-8' . "\r\n";
			}
		}

		$user_data = apply_filters( 'rael/login-register/new-user-data', $user_data );
		do_action( 'rael/login-register/before-insert-user', $user_data );
		$user_default_role = get_option( 'default_role' );

		if ( ! empty( $user_default_role ) && empty( $user_data['role'] ) ) {
			$user_data['role'] = $user_default_role;
		}

		if ( 'administrator' === strtolower( $user_data['role'] ) ) {
			$user_data['role'] = ! empty( $settings['rael_register_user_role'] ) ? $settings['rael_register_user_role'] : get_option( 'default_role' );
		}

		$user_id = wp_insert_user( $user_data );
		do_action( 'rael/login-register/after-insert-user', $user_id, $user_data );

		if ( is_wp_error( $user_id ) ) {
			// error happened during user creation.
			$errors['user_create'] = isset( $settings['rael_error_unknown'] ) ? $settings['rael_error_unknown'] : __( 'Sorry, something went wrong. User could not be registered.', 'responsive-addons-for-elementor' );
			if ( $ajax ) {
				wp_send_json_error( $errors['user_create'] );
			}
			update_option( 'rael_register_errors_' . $widget_id, $errors, false );
			wp_safe_redirect( esc_url( $url ) );
			exit();
		}

		$admin_or_both = in_array( 'send_email', $register_actions ) ? 'both' : 'admin';

		remove_action( 'register_new_user', 'wp_send_new_user_notifications' );
		do_action( 'register_new_user', $user_id );

		wp_new_user_notification( $user_id, null, $admin_or_both );

		// success & handle after registration action as defined by user in the widget.
		if ( ! $ajax && ! in_array( 'redirect', $register_actions ) ) {
			update_option( 'rael_register_success_' . $widget_id, 1, false );
		}

		// Handle after registration action.
		$data = array(
			'message' => isset( $settings['rael_success_register'] ) ? $settings['rael_success_register'] : __( 'Your registration was completed successfully.', 'responsive-addons-for-elementor' ),
		);

		// if send by mail option is checked.
		if ( in_array( 'send_email', $register_actions ) ) {
			$success_message = isset( $settings['rael_success_register'] ) ? $settings['rael_success_register'] : __( 'Your registration was completed successfully.', 'responsive-addons-for-elementor' );
			update_option( 'rael_register_success_' . $widget_id, $success_message, false );
		}

		// should user be auto logged in?
		if ( in_array( 'auto_login', $register_actions ) && ! is_user_logged_in() ) {
			wp_signon(
				array(
					'user_login'    => $username,
					'user_password' => $password,
					'remember'      => true,
				)
			);

			$this->delete_registration_options( $widget_id );

			if ( $ajax ) {
				if ( in_array( 'redirect', $register_actions ) ) {
					$data['redirect_to'] = $custom_redirect_url;
				}
				wp_send_json_success( $data );
			}

			// if custom redirect not available then refresh the current page to show admin bar.
			if ( ! in_array( 'redirect', $register_actions ) ) {
				wp_safe_redirect( esc_url( $url ) );
				exit();
			}
		}

		// custom redirect?
		if ( $ajax ) {
			if ( in_array( 'redirect', $register_actions ) ) {
				$data['redirect_to'] = $custom_redirect_url;
			}
			wp_send_json_success( $data );
		}

		if ( in_array( 'redirect', $register_actions ) ) {
			wp_safe_redirect( $custom_redirect_url );
			exit();
		}

		if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
			wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
			exit();
		}
	}

	public function rael_lr_validate_recaptcha() {
		if ( strlen( $_REQUEST['g-recaptcha-response'] ) == 0 ) {
			return false;
		}
		$endpoint = 'https://www.google.com/recaptcha/api/siteverify';
		$data     = array(
			'secret'   => get_option( 'rael_login_reg_setting_secret_key' ),
			'response' => $_REQUEST['g-recaptcha-response'],
			'ip'       => $_SERVER['REMOTE_ADDR'],
		);

		$res = json_decode( wp_remote_retrieve_body( wp_remote_post( $endpoint, array( 'body' => $data ) ) ), 1 );

		if ( isset( $res['success'] ) ) {
			return $res['success'];
		}

		return false;
	}

	public function rael_get_widget_settings( $page_id, $widget_id ) {
		$document = Plugin::$instance->documents->get( $page_id );
		$settings = array();
		if ( $document ) {
			$elements    = Plugin::instance()->documents->get( $page_id )->get_elements_data();
			$widget_data = $this->find_element_recursive( $elements, $widget_id );

			if ( ! empty( $widget_data ) ) {
				$widget = Plugin::instance()->elements_manager->create_element_instance( $widget_data );
				if ( $widget ) {
					$settings = $widget->get_settings_for_display();
				}
			}
		}
		$page_author_id = get_post_field( 'post_author', $page_id );

		$user = get_user_by( 'ID', $page_author_id );
		if ( $user ) {
			$user_roles = $user->roles;
		}

		if ( ! in_array( 'administrator', $user_roles, true ) ) {
			$settings['rael_register_user_role'] = get_option( 'default_role' );
		}

		return $settings;
	}

	/**
	 * Returns different available user roles.
	 *
	 * @since 1.0.0
	 * @access public
	 */


	/**
	 * It store data temporarily,5 minutes by default
	 *
	 * @param     $name
	 * @param     $data data to be
	 * @param int             $time time in seconds. Default is 300s = 5 minutes
	 *
	 * @return bool it returns true if the data saved, otherwise, false returned.
	 */
	public function set_transient( $name, $data, $time = 300 ) {
		$time = empty( $time ) ? (int) $time : ( 5 * MINUTE_IN_SECONDS );

		return set_transient( $name, $data, $time );
	}

	/**
	 * Filters the contents of the new user notification email sent to the new user.
	 *
	 * @param array    $email_data It contains, to, subject, message, headers etc.
	 * @param \WP_User $user       User object for new user.
	 * @param string   $blogname   The site title.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function new_user_notification_email( $email_data, $user, $blogname ) {
		if ( ! self::$send_custom_email ) {
			return $email_data;
		}

		if ( ! empty( self::$email_options['subject'] ) ) {
			$email_data['subject'] = self::$email_options['subject'];
		}

		if ( ! empty( self::$email_options['message'] ) ) {
			$email_data['message'] = $this->replace_placeholders( self::$email_options['message'], 'user' );
		}

		if ( ! empty( self::$email_options['headers'] ) ) {
			$email_data['headers'] = self::$email_options['headers'];
		}

		return apply_filters( 'rael/login-register/new-user-email-data', $email_data, $user, $blogname );

	}

	/**
	 * Filters the contents of the new user notification email sent to the site admin.
	 *
	 * @param array    $email_data It contains, to, subject, message, headers etc.
	 * @param \WP_User $user       User object for new user.
	 * @param string   $blogname   The site title.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function new_user_notification_email_admin( $email_data, $user, $blogname ) {

		if ( ! self::$send_custom_email_admin ) {
			return $email_data;
		}

		if ( ! empty( self::$email_options['admin_subject'] ) ) {
			$email_data['subject'] = self::$email_options['admin_subject'];
		}

		if ( ! empty( self::$email_options['admin_message'] ) ) {
			$email_data['message'] = $this->replace_placeholders( self::$email_options['admin_message'], 'admin' );
		}

		if ( ! empty( self::$email_options['admin_headers'] ) ) {
			$email_data['headers'] = self::$email_options['admin_headers'];
		}

		return apply_filters( 'rael/login-register/new-user-admin-email-data', $email_data, $user, $blogname );
	}

	/**
	 * Get Widget data.
	 *
	 * @param array  $elements Element array.
	 * @param string $form_id  Element ID.
	 *
	 * @return bool|array
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
	 * It replaces placeholders with dynamic value and returns it.
	 *
	 * @param        $message
	 * @param string  $receiver
	 *
	 * @return null|string|string[]
	 */
	public function replace_placeholders( $message, $receiver = 'user' ) {
		$placeholders = array(
			'/\[password\]/',
			'/\[password_reset_link\]/',
			'/\[username\]/',
			'/\[email\]/',
			'/\[firstname\]/',
			'/\[lastname\]/',
			'/\[website\]/',
			'/\[loginurl\]/',
			'/\[sitetitle\]/',
		);
		$replacement  = array(
			self::$email_options['password'],
			self::$email_options['password_reset_link'],
			self::$email_options['username'],
			self::$email_options['email'],
			self::$email_options['firstname'],
			self::$email_options['lastname'],
			self::$email_options['website'],
			wp_login_url(),
			get_option( 'blogname' ),
		);

		if ( 'user' !== $receiver ) {
			// remove password from admin mail, because admin should not see user's plain password
			unset( $placeholders[0] );
			unset( $placeholders[1] );
			unset( $replacement[0] );
			unset( $replacement[1] );
		}

		return preg_replace( $placeholders, $replacement, $message );
	}

	public function delete_registration_options( $widget_id ) {
		delete_option( 'rael_register_success_' . $widget_id );
		delete_option( 'rael_register_errors_' . $widget_id );
	}
}
