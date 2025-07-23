<?php
/**
 * Login/Registration Widget
 *
 * @package    Responsive_Addons_For_Elementor
 */

namespace Responsive_Addons_For_Elementor\WidgetsManager\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}
/**
 * Elementor 'Progress Bar' widget class.
 */
class Responsive_Addons_For_Elementor_Login_Register extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve 'Login/Registration' widget name.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'rael-login-register';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve 'Login/Registration' widget title.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Login | Registration Form', 'responsive-addons-for-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve 'Login/Registration' widget icon.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-form-horizontal rael-badge';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve 'Login/Registration' widget icon.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'responsive-addons-for-elementor' );
	}

	/**
	 * Does the site allows new user registration?
	 *
	 * @var bool
	 */
	protected $user_can_register;
	/**
	 * Are you currently in Elementor Editor Screen?
	 *
	 * @var bool
	 */
	protected $in_editor;
	/**
	 * Google reCAPTCHA Site key
	 *
	 * @var string|false
	 */
	protected $recaptcha_sitekey;

	/**
	 * Login_Register constructor.
	 * Initializing the Login_Register widget class.
	 *
	 * @param array $data user data.
	 * @param array $args arguments.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		$this->user_can_register = get_option( 'users_can_register' );
		$this->recaptcha_sitekey = get_option( 'rael_login_reg_setting_site_key' );
		$this->in_editor         = Plugin::instance()->editor->is_edit_mode();
	}
	/**
	 * Get user roles
	 *
	 * @access public
	 */
	public function get_user_roles() {
		$user_roles[''] = __( 'Default', 'responsive-addons-for-elementor' );
		if ( function_exists( 'get_editable_roles' ) ) {
			$wp_roles = get_editable_roles();
			$roles    = $wp_roles ? $wp_roles : array();
			if ( ! empty( $roles ) && is_array( $roles ) ) {
				foreach ( $wp_roles as $role_key => $role ) {
					$user_roles[ $role_key ] = $role['name'];
				}
			}
		}
		return apply_filters( 'rael/login-register/new-user-roles', $user_roles );
	}

	/**
	 * Add the script dependencies required for the widget.
	 *
	 * @since 1.2.1
	 * @access public
	 *
	 * @return array List of the script dependencies.
	 */
	public function get_script_depends() {
		if ( $this->recaptcha_sitekey ) {
			wp_register_script( 'rael_login_register_recaptcha', 'https://www.google.com/recaptcha/api.js?render=explicit', array( 'jquery' ), RAEL_VER, true );

			return array( 'rael_login_register_recaptcha' );
		}
		return array();
	}

	/**
	 * Register 'Login/Registration' widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function register_controls() {

		// Content Tab.
		$this->rael_content_general_controls();
		$this->rael_form_header_controls();

		// Login Form Related.
		$this->rael_login_fields_controls();
		$this->rael_login_options_controls();

		// Registration Form Related.
		$this->rael_register_fields_controls();
		$this->rael_register_options_controls();
		$this->rael_register_user_email_controls();
		$this->rael_register_admin_email_controls();

		// Terms & Conditions.
		$this->rael_terms_and_conditions_controls();

		// Error Messages.
		$this->rael_validation_messages_controls();

		/*----Style Tab----*/
		$this->rael_general_style_controls();
		$this->rael_header_style_controls( 'login' );
		$this->rael_header_style_controls( 'register' );
		$this->rael_input_field_labels_controls();
		$this->rael_input_field_style_controls();
		$this->rael_login_button_style_controls();
		$this->rael_register_button_style_controls();
		$this->rael_login_recaptcha_style_controls();
		$this->rael_register_recaptcha_style_controls();
		$this->rael_login_link_style_controls();
		$this->rael_register_link_style_controls();
	}
	/**
	 * Get controls display condition
	 *
	 * @param string $type takes login/register as values.
	 */
	public function rael_get_controls_display_condition( $type = 'login' ) {
		$form_type = in_array(
			$type,
			array(
				'login',
				'register',
			)
		) ? $type : 'login';

		return array(
			'relation' => 'or',
			'terms'    => array(
				array(
					'name'  => "rael_show_{$form_type}_link",
					'value' => 'yes',
				),
				array(
					'name'  => 'rael_form_type',
					'value' => $form_type,
				),
			),
		);
	}
	/**
	 * RAE content general controls.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function rael_content_general_controls() {

		$this->start_controls_section(
			'rael_section_content_general',
			array(
				'label' => __( 'General', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_form_type',
			array(
				'label'              => __( 'Form Type', 'responsive-addons-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => array(
					'login'    => __( 'Login', 'responsive-addons-for-elementor' ),
					'register' => __( 'Registration', 'responsive-addons-for-elementor' ),
				),
				'default'            => 'login',
				'frontend_available' => true,
			)
		);

		if ( ! $this->user_can_register ) {
			$this->add_control(
				'rael_registration_off_notice',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					// translators: %1$s represents the opening anchor tag for the membership link, %2$s represents the closing anchor tag.
					'raw'             => sprintf( __( 'Registration is disabled on your site. Please enable it to use registration form. You can enable it from Dashboard » Settings » General » %1$sMembership%2$s.', 'responsive-addons-for-elementor' ), '<a href="' . esc_attr( esc_url( admin_url( 'options-general.php' ) ) ) . '" target="_blank">', '</a>' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
					'condition'       => array(
						'rael_form_type' => 'register',
					),
				)
			);
		}

		$this->add_control(
			'rael_hide_for_logged_in_user',
			array(
				'label'   => __( 'Hide all Forms from Logged-in Users', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);
		$this->add_control(
			'rael_login_form_popover_toggle',
			array(
				'label'        => __( 'LOGIN Form General Settings', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Controls', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Custom', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'conditions'   => $this->rael_get_controls_display_condition( 'login' ),
			)
		);

		$this->start_popover();

		$this->add_control(
			'rael_show_logout_message',
			array(
				'label'   => __( 'Show Logout Message/Link', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);
		$this->add_control(
			'rael_show_forgot_password',
			array(
				'label'   => __( 'Show "Forgot Password?"', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'rael_forgot_password_text',
			array(
				'label'       => __( 'Forgot Password Text', 'responsive-addons-for-elementor' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Forgot password?', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_show_forgot_password' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_forgot_password_link_type',
			array(
				'label'       => __( 'Forgot Password Redirects To', 'responsive-addons-for-elementor' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'default' => __( 'Default WordPress Page', 'responsive-addons-for-elementor' ),
					'custom'  => __( 'Custom URL', 'responsive-addons-for-elementor' ),
				),
				'default'     => 'default',
				'condition'   => array(
					'rael_show_forgot_password' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_forgot_password_url',
			array(
				'label'         => __( 'Custom Forgot Password URL', 'responsive-addons-for-elementor' ),
				'label_block'   => true,
				'type'          => Controls_Manager::URL,
				'show_external' => false,
				'condition'     => array(
					'rael_forgot_password_link_type' => 'custom',
					'rael_show_forgot_password'      => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_show_remember_me',
			array(
				'label'     => __( 'Show "Remember Me" Checkbox', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => __( 'Hide', 'responsive-addons-for-elementor' ),
				'label_on'  => __( 'Show', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_remember_me_text',
			array(
				'label'       => __( 'Remember Me Text', 'responsive-addons-for-elementor' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Remember Me', 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_show_remember_me' => 'yes',
				),
			)
		);

		if ( $this->user_can_register ) {

			$this->add_control(
				'rael_hr',
				array(
					'type' => Controls_Manager::DIVIDER,
				)
			);

			$this->add_control(
				'rael_show_register_link',
				array(
					'label'     => __( 'Show "Register" Link', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::SWITCHER,
					'default'   => 'yes',
					'separator' => 'before',
				)
			);

			$this->add_control(
				'rael_registration_link_text',
				array(
					'label'       => __( 'Register Link Text', 'responsive-addons-for-elementor' ),
					'label_block' => true,
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'Register Now', 'responsive-addons-for-elementor' ),
					'condition'   => array(
						'rael_show_register_link' => 'yes',
					),
				)
			);
			$this->add_control(
				'rael_registration_link_action',
				array(
					'label'       => __( 'Registration Link Action', 'responsive-addons-for-elementor' ),
					'label_block' => true,
					'type'        => Controls_Manager::SELECT,
					'options'     => array(
						'default' => __( 'WordPress Registration Page', 'responsive-addons-for-elementor' ),
						'form'    => __( 'Show Register Form', 'responsive-addons-for-elementor' ),
						'custom'  => __( 'Custom URL', 'responsive-addons-for-elementor' ),
					),
					'default'     => 'form',
					'condition'   => array(
						'rael_show_register_link' => 'yes',
					),
				)
			);

			$this->add_control(
				'custom_register_url',
				array(
					'label'         => __( 'Custom Register URL', 'responsive-addons-for-elementor' ),
					'label_block'   => true,
					'type'          => Controls_Manager::URL,
					'show_external' => false,
					'condition'     => array(
						'rael_registration_link_action' => 'custom',
						'rael_show_register_link'       => 'yes',
					),
				)
			);

		} else {

			$this->add_control(
				'rael_show_register_link',
				array(
					'label'     => __( 'Show Register Link', 'responsive-addons-for-elementor' ),
					'type'      => Controls_Manager::HIDDEN,
					'default'   => 'no',
					'separator' => 'before',
				)
			);
		}

		$this->add_control(
			'rael_enable_login_recaptcha',
			array(
				'label'        => __( 'Enable Google reCAPTCHA', 'responsive-addons-for-elementor' ),
				'description'  => __( 'reCAPTCHA will prevent spam login from bots.', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		if ( empty( $this->recaptcha_sitekey ) ) {
			$this->add_control(
				'rael_login_recaptcha_key_missing',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					// translators: %1$s represents the opening <strong> tag, %2$s represents the opening <a> tag for the settings link, %3$s represents the closing <a> and </strong> tags.
					'raw'             => sprintf( __( '<div style="padding: 1em; color: black; background-color: #ffc107; border-radius: 0px 10px 10px 0px; border-left: 0.4em #2b270a solid; line-height: 1.4;"> reCAPTCHA API keys are missing. Please add them from %1$sRAEL Dashboard » %2$sRAEL Login / Register Form settings.%3$s </div>', 'responsive-addons-for-elementor' ), '<strong>', '<a href="' . esc_attr( esc_url( admin_url( 'admin.php?page=rael-settings' ) ) ) . '" target="_blank" style="color: #468295">', '</a></strong>' ),
					'content_classes' => 'rael-warning',
					'condition'       => array(
						'rael_enable_login_recaptcha' => 'yes',
					),
				)
			);
		}

		$this->end_popover();

		/*--show registration related control only if registration is enable on the site and selected form type is "Registration"--*/
		if ( $this->user_can_register ) {

			$this->add_control(
				'rael_registration_form_popover_toggle',
				array(
					'label'        => __( 'REGISTER Form General Settings', 'responsive-addons-for-elementor' ),
					'type'         => Controls_Manager::POPOVER_TOGGLE,
					'label_off'    => __( 'Default', 'responsive-addons-for-elementor' ),
					'label_on'     => __( 'Custom', 'responsive-addons-for-elementor' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'conditions'   => $this->rael_get_controls_display_condition( 'register' ),
				)
			);

			$this->start_popover();

			$this->add_control(
				'rael_show_login_link',
				array(
					'label'   => __( 'Show Login Link', 'responsive-addons-for-elementor' ),
					'type'    => Controls_Manager::SWITCHER,
					'default' => 'yes',
				)
			);
			$this->add_control(
				'rael_login_link_text',
				array(
					'label'       => __( 'Login Link Text', 'responsive-addons-for-elementor' ),
					'label_block' => true,
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'Sign In', 'responsive-addons-for-elementor' ),
					'condition'   => array(
						'rael_show_login_link' => 'yes',
					),
				)
			);

			$this->add_control(
				'rael_login_link_action',
				array(
					'label'       => __( 'Login Link Action', 'responsive-addons-for-elementor' ),
					'label_block' => true,
					'type'        => Controls_Manager::SELECT,
					'options'     => array(
						'default' => __( 'Default WordPress Page', 'responsive-addons-for-elementor' ),
						'form'    => __( 'Show Login Form', 'responsive-addons-for-elementor' ),
						'custom'  => __( 'Custom URL', 'responsive-addons-for-elementor' ),
					),
					'default'     => 'form',
					'condition'   => array(
						'rael_show_login_link' => 'yes',
					),
				)
			);

			$this->add_control(
				'rael_custom_login_url',
				array(
					'label'         => __( 'Custom Login URL', 'responsive-addons-for-elementor' ),
					'label_block'   => true,
					'show_external' => false,
					'type'          => Controls_Manager::URL,
					'condition'     => array(
						'rael_login_link_action' => 'custom',
						'rael_show_login_link'   => 'yes',
					),
				)
			);

			$this->add_control(
				'rael_enable_register_recaptcha',
				array(
					'label'        => __( 'Enable Google reCAPTCHA', 'responsive-addons-for-elementor' ),
					'description'  => __( 'reCAPTCHA will prevent spam registration from bots.', 'responsive-addons-for-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
					'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
					'return_value' => 'yes',
				)
			);

			if ( empty( $this->recaptcha_sitekey ) ) {
				$this->add_control(
					'rael_register_recaptcha_key_missing',
					array(
						'type'            => Controls_Manager::RAW_HTML,
						// translators: %1$s represents the opening <strong> tag, %2$s represents the opening <a> tag for the settings link, %3$s represents the closing <a> tag, %4$s represents the closing </strong> tag.
						'raw'             => sprintf( __( '<div style="padding: 1em; color: black; background-color: #ffc107; border-radius: 0px 10px 10px 0px; border-left: 0.4em #2b270a solid; line-height: 1.4;"> reCAPTCHA API keys are missing. Please add them from %1$sRAEL Dashboard » %2$sRAEL Login / Register Form settings.%3$s </div>', 'responsive-addons-for-elementor' ), '<strong>', '<a href="' . esc_attr( esc_url( admin_url( 'admin.php?page=rael-settings' ) ) ) . '" target="_blank" style="color: #468295">', '</a></strong>' ),
						'content_classes' => 'rael-warning',
						'condition'       => array(
							'rael_enable_register_recaptcha' => 'yes',
						),
					)
				);
			}

			$this->end_popover();

		} else {
			$this->add_control(
				'rael_show_login_link',
				array(
					'label'   => __( 'Show Login Link', 'responsive-addons-for-elementor' ),
					'type'    => Controls_Manager::HIDDEN,
					'default' => 'no',
				)
			);
		}

		$this->end_controls_section();
	}
	/**
	 * RAE form header controls.
	 *
	 * @access protected
	 */
	protected function rael_form_header_controls() {

		$this->start_controls_section(
			'rael_form_header',
			array(
				'label' => __( 'Additional Form Content', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_form_image',
			array(
				'label' => __( 'Form Image', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$this->add_control(
			'rael_form_image_size',
			array(
				'label'     => __( 'Image Size', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'cover'   => __( 'Cover', 'responsive-addons-for-elementor' ),
					'contain' => __( 'Contain', 'responsive-addons-for-elementor' ),

				),
				'default'   => 'cover',
				'selectors' => array(
					'{{WRAPPER}} .rael-form-image' => 'background-size: {{VALUE}};',
				),
				'condition' => array(
					'rael_form_image[url]!' => '',
				),
			)
		);

		$this->add_control(
			'rael_form_image_x_y_position',
			array(
				'label'     => __( 'Image Position (X & Y axis)', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'top'    => __( 'Top', 'responsive-addons-for-elementor' ),
					'left'   => __( 'Left', 'responsive-addons-for-elementor' ),
					'center' => __( 'Center', 'responsive-addons-for-elementor' ),
					'right'  => __( 'Right', 'responsive-addons-for-elementor' ),
					'bottom' => __( 'Bottom', 'responsive-addons-for-elementor' ),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .rael-form-image' => 'background-position: {{VALUE}};',
				),
				'condition' => array(
					'rael_form_image[url]!' => '',
				),
			)
		);

		$this->add_control(
			'rael_form_image_width',
			array(
				'label'      => __( 'Form Image Width (%)', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min' => 50,
						'max' => 150,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-form-image' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_form_image[url]!' => '',
				),
			)
		);

		$this->add_control(
			'rael_form_image_position',
			array(
				'label'     => __( 'Image Position', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'  => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-arrow-left',
					),
					'right' => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-arrow-right',
					),
				),
				'default'   => 'left',
				'separator' => 'after',
			)
		);

		$this->add_control(
			'rael_form_logo',
			array(
				'label' => __( 'Logo', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$this->add_control(
			'rael_form_logo_position',
			array(
				'label'     => __( 'Logo Position', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'inline' => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-arrow-left',
					),
					'block'  => array(
						'title' => __( 'Top', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-arrow-up',
					),
				),

				'default'   => 'block',
				'separator' => 'after',
			)
		);

		$this->add_control(
			'rael_login_form_title',
			array(
				'label'       => __( 'Login Form Title', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Welcome Back!', 'responsive-addons-for-elementor' ),
				'separator'   => 'before',
				'conditions'  => $this->rael_get_controls_display_condition( 'login' ),
			)
		);

		$this->add_control(
			'rael_login_form_subtitle',
			array(
				'label'       => __( 'Login Form Sub Title', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'placeholder' => __( 'Please login to your account', 'responsive-addons-for-elementor' ),
				'conditions'  => $this->rael_get_controls_display_condition( 'login' ),
			)
		);

		$this->add_control(
			'rael_register_form_title',
			array(
				'label'       => __( 'Register Form Title', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Create a New Account', 'responsive-addons-for-elementor' ),
				'separator'   => 'before',
				'conditions'  => $this->rael_get_controls_display_condition( 'register' ),
			)
		);

		$this->add_control(
			'rael_register_form_subtitle',
			array(
				'label'       => __( 'Register Form Sub Title', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'placeholder' => __( 'Create an account to enjoy awesome features.', 'responsive-addons-for-elementor' ),
				'conditions'  => $this->rael_get_controls_display_condition( 'register' ),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * RAEL login field controls.
	 *
	 * @access protected
	 */
	protected function rael_login_fields_controls() {

		$this->start_controls_section(
			'rael_login_fields',
			array(
				'label'      => __( 'Login Form Fields', 'responsive-addons-for-elementor' ),
				'conditions' => $this->rael_get_controls_display_condition( 'login' ),
			)
		);

		$this->add_control(
			'rael_login_label_types',
			array(
				'label'   => __( 'Labels & Placeholders', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'default' => __( 'Default', 'responsive-addons-for-elementor' ),
					'custom'  => __( 'Custom', 'responsive-addons-for-elementor' ),
				),
				'default' => 'default',
			)
		);

		$this->add_control(
			'rael_show_login_labels',
			array(
				'label'   => __( 'Show Label', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'rael_login_labels_heading',
			array(
				'label'      => __( 'Labels', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::HEADING,
				'separator'  => 'before',
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_login_label_types',
							'operator' => '==',
							'value'    => 'custom',
						),
						array(
							'name'     => 'rael_show_login_labels',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_login_username_label',
			array(
				'label'       => __( 'Username Label', 'responsive-addons-for-elementor' ),
				'placeholder' => __( 'Username or Email Address', 'responsive-addons-for-elementor' ),
				'default'     => __( 'Username or Email Address', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'conditions'  => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_login_label_types',
							'operator' => '==',
							'value'    => 'custom',
						),
						array(
							'name'     => 'rael_show_login_labels',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_login_password_label',
			array(
				'label'       => __( 'Password Label', 'responsive-addons-for-elementor' ),
				'placeholder' => __( 'Password', 'responsive-addons-for-elementor' ),
				'default'     => __( 'Password', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'conditions'  => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'rael_login_label_types',
							'operator' => '==',
							'value'    => 'custom',
						),
						array(
							'name'     => 'rael_show_login_labels',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'rael_login_placeholders_heading',
			array(
				'label'     => __( 'Placeholders', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array( 'rael_login_label_types' => 'custom' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_login_username_placeholder',
			array(
				'label'       => __( 'Username Placeholder', 'responsive-addons-for-elementor' ),
				'placeholder' => __( 'Username or Email Address', 'responsive-addons-for-elementor' ),
				'default'     => __( 'Username or Email Address', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'condition'   => array( 'rael_login_label_types' => 'custom' ),
			)
		);

		$this->add_control(
			'rael_login_password_placeholder',
			array(
				'label'       => __( 'Password Placeholder', 'responsive-addons-for-elementor' ),
				'placeholder' => __( 'Password', 'responsive-addons-for-elementor' ),
				'default'     => __( 'Password', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'condition'   => array( 'rael_login_label_types' => 'custom' ),
			)
		);

		$this->add_control(
			'rael_correct_pass_padding',
			array(
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'apply_class',
				'condition' => array(
					'rael_password_toggle' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-login-password-input' => 'padding-right: 93.5px;',
				),
			)
		);

		$this->add_control(
			'rael_password_toggle',
			array(
				'label'     => __( 'Password Visibility Icon', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'responsive-addons-for-elementor' ),
				'label_on'  => __( 'Show', 'responsive-addons-for-elementor' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'rael_login_button_heading',
			array(
				'label'     => __( 'Login Button', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_login_button_text',
			array(
				'label'       => __( 'Button Text', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Log In', 'responsive-addons-for-elementor' ),
				'placeholder' => __( 'Log In', 'responsive-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * RAE login options controls.
	 *
	 * @access protected
	 */
	protected function rael_login_options_controls() {

		$this->start_controls_section(
			'rael_login_options',
			array(
				'label'      => __( 'Login Form Options', 'responsive-addons-for-elementor' ),
				'conditions' => $this->rael_get_controls_display_condition( 'login' ),
			)
		);

		$this->add_control(
			'rael_redirect_after_login',
			array(
				'label' => __( 'Redirect After Login', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'rael_redirect_url',
			array(
				'type'          => Controls_Manager::URL,
				'show_label'    => false,
				'show_external' => false,
				'placeholder'   => admin_url(),
				'description'   => __( 'Please note that only your current domain is allowed here to keep your site secure.', 'responsive-addons-for-elementor' ),
				'condition'     => array(
					'rael_redirect_after_login' => 'yes',
				),
				'default'       => array(
					'url'         => admin_url(),
					'is_external' => false,
					'nofollow'    => true,
				),
				'separator'     => 'after',
			)
		);

		$this->end_controls_section();
	}
		/**
		 * RAE register fields controls.
		 *
		 * @since 1.1.0
		 * @access protected
		 */
	protected function rael_register_fields_controls() {

		$this->start_controls_section(
			'rael_register_fields',
			array(
				'label'      => __( 'Register Form Fields', 'responsive-addons-for-elementor' ),
				'conditions' => $this->rael_get_controls_display_condition( 'register' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'rael_field_type',
			array(
				'label'   => __( 'Type', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'user_name'    => __( 'Username', 'responsive-addons-for-elementor' ),
					'email'        => __( 'Email', 'responsive-addons-for-elementor' ),
					'password'     => __( 'Password', 'responsive-addons-for-elementor' ),
					'confirm_pass' => __( 'Confirm Password', 'responsive-addons-for-elementor' ),
					'first_name'   => __( 'First Name', 'responsive-addons-for-elementor' ),
					'last_name'    => __( 'Last Name', 'responsive-addons-for-elementor' ),
					'website'      => __( 'Website', 'responsive-addons-for-elementor' ),
				),
				'default' => 'first_name',
			)
		);

		$repeater->add_control(
			'rael_field_label',
			array(
				'label'   => __( 'Label', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$repeater->add_control(
			'rael_placeholder',
			array(
				'label'   => __( 'Placeholder', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$repeater->add_control(
			'rael_required',
			array(
				'label'     => __( 'Required', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array(
					'rael_field_type!' => array(
						'user_name',
						'email',
						'password',
						'confirm_pass',
					),
				),
			)
		);

		$repeater->add_control(
			'rael_required_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'Note: This field is required by default.', 'responsive-addons-for-elementor' ),
				'condition'       => array(
					'rael_field_type' => array(
						'user_name',
						'email',
						'password',
						'confirm_pass',
					),
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$repeater->add_control(
			'rael_reg_enforce_password_rules',
			array(
				'label'     => __( 'Enforce Password Rules', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'responsive-addons-for-elementor' ),
				'label_on'  => __( 'Show', 'responsive-addons-for-elementor' ),
				'default'   => 'yes',
				'condition' => array(
					'rael_field_type' => array(
						'password',
					),
				),
			)
		);

		$repeater->add_control(
			'rael_reg_password_toggle',
			array(
				'label'        => __( 'Password Visibility Icon', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => __( 'Hide', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Show', 'responsive-addons-for-elementor' ),
				'default'      => 'no',
				'return_value' => 'yes',
				'condition'    => array(
					'rael_field_type' => array(
						'password',
					),
				),
			)
		);

		$repeater->add_control(
			'rael_correct_pass_reg_padding',
			array(
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'apply_class',
				'condition' => array(
					'rael_reg_password_toggle' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .rael-reg-password-input' => 'padding-right: 93.5px;',
				),
			)
		);

		$this->add_control(
			'rael_register_input_fields',
			array(
				'label'       => __( 'Fields', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'rael_field_type'  => 'user_name',
						'rael_field_label' => __( 'Username', 'responsive-addons-for-elementor' ),
						'rael_placeholder' => __( 'Username', 'responsive-addons-for-elementor' ),
						'rael_required'    => 'yes',
					),
					array(
						'rael_field_type'  => 'email',
						'rael_field_label' => __( 'Email', 'responsive-addons-for-elementor' ),
						'rael_placeholder' => __( 'Email', 'responsive-addons-for-elementor' ),
						'rael_required'    => 'yes',
					),
					array(
						'rael_field_type'  => 'password',
						'rael_field_label' => __( 'Password', 'responsive-addons-for-elementor' ),
						'rael_placeholder' => __( 'Password', 'responsive-addons-for-elementor' ),
						'rael_required'    => 'yes',
					),
					array(
						'rael_field_type'  => 'confirm_pass',
						'rael_field_label' => __( 'Confirm Password', 'responsive-addons-for-elementor' ),
						'rael_placeholder' => __( 'Confirm Password', 'responsive-addons-for-elementor' ),
						'rael_required'    => 'yes',
					),
				),
				'title_field' => '{{ rael_field_label }}',
			)
		);

		$this->add_control(
			'rael_show_reg_labels',
			array(
				'label'   => __( 'Show Label', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'rael_required_mark',
			array(
				'label'     => __( 'Show Required Mark', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					'rael_show_reg_labels' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_reg_button_heading',
			array(
				'label'     => __( 'Register Button', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_reg_button_text',
			array(
				'label'   => __( 'Button Text', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Register', 'responsive-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * RAE register options controls.
	 *
	 * @access protected
	 */
	protected function rael_register_options_controls() {

		$this->start_controls_section(
			'rael_register_actions',
			array(
				'label'      => __( 'Register Form Options', 'responsive-addons-for-elementor' ),
				'conditions' => $this->rael_get_controls_display_condition( 'register' ),
			)
		);

		$this->add_control(
			'rael_successful_register_action',
			array(
				'label'       => __( 'Register Actions', 'responsive-addons-for-elementor' ),
				'description' => __( 'You can select what should happen after a user registers successfully.', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'default'     => 'send_email',
				'options'     => array(
					'redirect'   => __( 'Redirect', 'responsive-addons-for-elementor' ),
					'auto_login' => __( 'Auto Login', 'responsive-addons-for-elementor' ),
					'send_email' => __( 'Notify User By Email', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_register_redirect_url',
			array(
				'type'          => Controls_Manager::URL,
				'label'         => __( 'Custom Redirect URL', 'responsive-addons-for-elementor' ),
				'show_external' => false,
				'placeholder'   => __( 'eg. https://your-link.com/wp-admin/', 'responsive-addons-for-elementor' ),
				'description'   => __( 'Please note that only your current domain is allowed here to keep your site secure.', 'responsive-addons-for-elementor' ),
				'default'       => array(
					'url'         => get_admin_url(),
					'is_external' => false,
					'nofollow'    => true,
				),
				'condition'     => array(
					'rael_successful_register_action' => 'redirect',
				),
			)
		);

		if ( current_user_can( 'create_users' ) ) {

			$user_role = $this->get_user_roles();

		} else {

			$user_role = array(
				get_option( 'default_role' ) => ucfirst( get_option( 'default_role' ) ),
			);

		}

		$this->add_control(
			'rael_register_user_role',
			array(
				'label'     => __( 'New User Role', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => $user_role,
				'separator' => 'before',
			)
		);

		$this->end_controls_section();
	}
	/**
	 * RAE user mail controls.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function rael_register_user_email_controls() {
		/* translators: %s: Site Name */
		$default_subject  = sprintf( __( 'Thank you for registering on "%s"!', 'responsive-addons-for-elementor' ), get_option( 'blogname' ) );
		$default_message  = $default_subject . "\r\n\r\n";
		$default_message .= __( 'Username: [username]', 'responsive-addons-for-elementor' ) . "\r\n\r\n";
		$default_message .= __( 'Password: [password]', 'responsive-addons-for-elementor' ) . "\r\n\r\n";
		$default_message .= __( 'To reset your password, visit the following address:', 'responsive-addons-for-elementor' ) . "\r\n\r\n";
		$default_message .= "[password_reset_link]\r\n\r\n";
		$default_message .= __( 'Please click the following address to login to your account:', 'responsive-addons-for-elementor' ) . "\r\n\r\n";
		$default_message .= wp_login_url() . "\r\n";

		$this->start_controls_section(
			'rael_reg_email',
			array(
				'label'      => __( 'Register User Email Options', 'responsive-addons-for-elementor' ),
				'conditions' => $this->rael_get_controls_display_condition( 'register' ),
			)
		);

		$this->add_control(
			'rael_reg_email_template_type',
			array(
				'label'       => __( 'Email Template Type', 'responsive-addons-for-elementor' ),
				'description' => __( 'Default template uses WordPress Default email template. So, please select the Custom Option to send the user proper information if you used any username field.', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'default',
				'render_type' => 'none',
				'options'     => array(
					'default' => __( 'WordPres Default', 'responsive-addons-for-elementor' ),
					'custom'  => __( 'Custom', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_reg_email_subject',
			array(
				'label'       => __( 'Email Subject', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => $default_subject,
				'default'     => $default_subject,
				'label_block' => true,
				'render_type' => 'none',
				'condition'   => array(
					'rael_reg_email_template_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'rael_reg_email_message',
			array(
				'label'       => __( 'Email Message', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::WYSIWYG,
				'placeholder' => __( 'Enter Your Custom Email Message...', 'responsive-addons-for-elementor' ),
				'default'     => $default_message,
				'label_block' => true,
				'render_type' => 'none',
				'condition'   => array(
					'rael_reg_email_template_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'rael_reg_email_content_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( '<strong>Note:</strong> You can use dynamic content in the email body like [fieldname]. For example [username] will be replaced by user-typed username. Available tags are: [password], [username], [email], [firstname],[lastname], [website], [loginurl], [password_reset_link] and [sitetitle] ', 'responsive-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'       => array(
					'rael_reg_email_template_type' => 'custom',
				),
				'render_type'     => 'none',
			)
		);

		$this->add_control(
			'rael_reg_email_content_type',
			array(
				'label'       => __( 'Email Content Type', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'html',
				'render_type' => 'none',
				'options'     => array(
					'html'  => __( 'HTML', 'responsive-addons-for-elementor' ),
					'plain' => __( 'Plain', 'responsive-addons-for-elementor' ),
				),
				'condition'   => array(
					'rael_reg_email_template_type' => 'custom',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * RAE admin mail controls.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function rael_register_admin_email_controls() {

		/* translators: %s: Site Name */
		$default_subject = sprintf( __( '["%s"] New User Registration', 'responsive-addons-for-elementor' ), get_option( 'blogname' ) );
		/* translators: %s: Site Name */
		$default_message  = sprintf( __( 'A new user has been registered on your site %s', 'responsive-addons-for-elementor' ), get_option( 'blogname' ) ) . "\r\n\r\n";
		$default_message .= __( 'Username: [username]', 'responsive-addons-for-elementor' ) . "\r\n\r\n";
		$default_message .= __( 'Email: [email]', 'responsive-addons-for-elementor' ) . "\r\n\r\n";

		$this->start_controls_section(
			'rael_reg_admin_email',
			array(
				'label'      => __( 'Register Admin Email Options', 'responsive-addons-for-elementor' ),
				'conditions' => $this->rael_get_controls_display_condition( 'register' ),
			)
		);

		$this->add_control(
			'rael_reg_admin_email_template_type',
			array(
				'label'       => __( 'Email Template Type', 'responsive-addons-for-elementor' ),
				'description' => __( 'Default template uses WordPress Default Admin email template. You can customize it by choosing the custom option.', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'default',
				'render_type' => 'none',
				'options'     => array(
					'default' => __( 'WordPres Default', 'responsive-addons-for-elementor' ),
					'custom'  => __( 'Custom', 'responsive-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'rael_reg_admin_email_subject',
			array(
				'label'       => __( 'Email Subject', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => $default_subject,
				'default'     => $default_subject,
				'label_block' => true,
				'render_type' => 'none',
				'condition'   => array(
					'rael_reg_admin_email_template_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'rael_reg_admin_email_message',
			array(
				'label'       => __( 'Email Message', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::WYSIWYG,
				'placeholder' => __( 'Enter Your Custom Email Message...', 'responsive-addons-for-elementor' ),
				'default'     => $default_message,
				'label_block' => true,
				'render_type' => 'none',
				'condition'   => array(
					'rael_reg_admin_email_template_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'rael_reg_admin_email_content_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( '<strong>Note:</strong> You can use dynamic content in the email body like [fieldname]. For example [username] will be replaced by user-typed username. Available tags are: [username], [email], [firstname],[lastname], [website], [loginurl] and [sitetitle] ', 'responsive-addons-for-elementor' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'       => array(
					'rael_reg_admin_email_template_type' => 'custom',
				),
				'render_type'     => 'none',
			)
		);

		$this->add_control(
			'rael_reg_admin_email_content_type',
			array(
				'label'       => __( 'Email Content Type', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'html',
				'render_type' => 'none',
				'options'     => array(
					'html'  => __( 'HTML', 'responsive-addons-for-elementor' ),
					'plain' => __( 'Plain', 'responsive-addons-for-elementor' ),
				),
				'condition'   => array(
					'rael_reg_admin_email_template_type' => 'custom',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * RAE terms and conditions controls.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function rael_terms_and_conditions_controls() {

		$this->start_controls_section(
			'rael_terms_conditions',
			array(
				'label'      => __( 'Terms & Conditions', 'responsive-addons-for-elementor' ),
				'conditions' => $this->rael_get_controls_display_condition( 'register' ),
			)
		);

		$this->add_control(
			'rael_show_terms_conditions',
			array(
				'label'        => __( 'Show "Terms & Conditions" Checkbox', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'responsive-addons-for-elementor' ),
				'label_off'    => __( 'No', 'responsive-addons-for-elementor' ),
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'acceptance_label_rael',
			array(
				'label'       => __( 'Acceptance Label', 'responsive-addons-for-elementor' ),
				'description' => __( "Eg. I accept the terms & conditions.\n Note: First line is checkbox label & Last line will be used as link text.", 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'label_block' => true,
				'placeholder' => __( 'I Accept the Terms and Conditions.', 'responsive-addons-for-elementor' ),
				'default'     => __( "I Accept the\n Terms and Conditions.", 'responsive-addons-for-elementor' ),
				'condition'   => array(
					'rael_show_terms_conditions' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_acceptance_text_source',
			array(
				'label'       => __( 'Content Source', 'responsive-addons-for-elementor' ),
				'description' => __( 'Selecting "Editor" will disable the linking condition of "Acceptance Label".', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'editor'      => __( 'Editor', 'responsive-addons-for-elementor' ),
					'custom_link' => __( 'Custom Link', 'responsive-addons-for-elementor' ),
					'default'     => __( 'Default WordPress Privacy Policy', 'responsive-addons-for-elementor' ),
				),
				'default'     => 'custom_link',
				'condition'   => array(
					'rael_show_terms_conditions' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_acceptance_text',
			array(
				'label'     => __( 'Terms & Conditions', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::WYSIWYG,
				'rows'      => 3,
				'default'   => __( 'Please go through the following terms and conditions carefully.', 'responsive-addons-for-elementor' ),
				'condition' => array(
					'rael_show_terms_conditions'  => 'yes',
					'rael_acceptance_text_source' => 'editor',
				),
			)
		);

		$this->add_control(
			'rael_acceptance_text_url',
			array(
				'label'       => __( 'Terms & Conditions URL', 'responsive-addons-for-elementor' ),
				'description' => __( 'Enter the link where your terms & condition or privacy policy is found    .', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'default'     => array(
					'is_external' => true,
					'nofollow'    => true,
				),
				'placeholder' => 'http://your-link.com',
				'condition'   => array(
					'rael_show_terms_conditions'  => 'yes',
					'rael_acceptance_text_source' => 'custom_link',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * RAE validation messages controls.
	 *
	 * @access protected
	 */
	protected function rael_validation_messages_controls() {

		$this->start_controls_section(
			'rael_error_success_messages',
			array(
				'label' => __( 'Validation Messages', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_error_message_heading',
			array(
				'label' => __( 'Error Messages', 'responsive-addons-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'rael_error_email',
			array(
				'label'       => __( 'Invalid Email', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Eg. Your email is invalid.', 'responsive-addons-for-elementor' ),
				'default'     => __( 'You have used an invalid email.', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_error_email_missing',
			array(
				'label'       => __( 'Email is missing', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Eg. Email is missing or invalid.', 'responsive-addons-for-elementor' ),
				'default'     => __( 'Email is missing or invalid.', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_error_email_used',
			array(
				'label'       => __( 'Already Used Email', 'responsive-addons-for-elementor' ),
				'description' => 'Note: this is for Registration form only.',
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Eg. Your email is already in use.', 'responsive-addons-for-elementor' ),
				'default'     => __( 'The provided email is already registered. Please login or reset password or use another email.', 'responsive-addons-for-elementor' ),
				'conditions'  => $this->rael_get_controls_display_condition( 'register' ),
			)
		);

		$this->add_control(
			'rael_error_username',
			array(
				'label'       => __( 'Invalid Username', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Eg. Your username is invalid.', 'responsive-addons-for-elementor' ),
				'default'     => __( 'You have used an invalid username.', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_error_username_used',
			array(
				'label'       => __( 'Username is already in use', 'responsive-addons-for-elementor' ),
				'description' => 'Note: this is for Registration form only.',
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Eg. Your username is already registered.', 'responsive-addons-for-elementor' ),
				'default'     => __( 'Invalid username provided or the username is already registered.', 'responsive-addons-for-elementor' ),
				'conditions'  => $this->rael_get_controls_display_condition( 'register' ),
			)
		);

		$this->add_control(
			'rael_error_password',
			array(
				'label'       => __( 'Invalid Password', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Eg. Your password is invalid.', 'responsive-addons-for-elementor' ),
				'default'     => __( 'Your password is invalid.', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_error_confirm_password',
			array(
				'label'       => __( 'Invalid Confirm Password', 'responsive-addons-for-elementor' ),
				'description' => 'Note: this is for Registration form only.',
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Eg. Passwords did not match.', 'responsive-addons-for-elementor' ),
				'default'     => __( 'Your passwords did not match.', 'responsive-addons-for-elementor' ),
				'conditions'  => $this->rael_get_controls_display_condition( 'register' ),
			)
		);

		$this->add_control(
			'rael_error_already_logged_in',
			array(
				'label'       => __( 'Already Logged In', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Eg. You are already logged in.', 'responsive-addons-for-elementor' ),
				'default'     => __( 'You are already logged in.', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_error_tc',
			array(
				'label'       => __( 'Terms & Conditions Error', 'responsive-addons-for-elementor' ),
				'description' => 'Note: this is for Registration form only.',
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Eg. You must accept the Terms & Conditions.', 'responsive-addons-for-elementor' ),
				'default'     => __( 'You did not accept the Terms and Conditions. Please accept it & try again.', 'responsive-addons-for-elementor' ),
				'conditions'  => $this->rael_get_controls_display_condition( 'register' ),
			)
		);

		$this->add_control(
			'rael_error_recaptcha',
			array(
				'label'       => __( 'reCAPTCHA Failed', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Eg. reCAPTCHA Validation Failed', 'responsive-addons-for-elementor' ),
				'default'     => __( 'You did not pass reCAPTCHA challenge.', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_error_unknown',
			array(
				'label'       => __( 'Other Errors', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Eg. Something went wrong.', 'responsive-addons-for-elementor' ),
				'default'     => __( 'Something went wrong.', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_success_message_heading',
			array(
				'label'     => __( 'Success Messages', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_success_login',
			array(
				'label'       => __( 'Successful Login', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Eg. You have logged in successfully!', 'responsive-addons-for-elementor' ),
				'default'     => __( 'You have logged in successfully!', 'responsive-addons-for-elementor' ),
				'conditions'  => $this->rael_get_controls_display_condition( 'login' ),
			)
		);

		$this->add_control(
			'rael_success_register',
			array(
				'label'       => __( 'Successful Registration', 'responsive-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'eg. You have registered successfully!', 'responsive-addons-for-elementor' ),
				'default'     => __( 'You have registered successfully!', 'responsive-addons-for-elementor' ),
				'conditions'  => $this->rael_get_controls_display_condition( 'register' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Styling Controls.
	 */
	protected function rael_general_style_controls() {

		$this->start_controls_section(
			'rael_general_styles',
			array(
				'label' => __( 'General', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Widget Container or Box.
		$this->add_control(
			'rael_widget_wrapper_popup',
			array(
				'label'        => __( 'Widget Container', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Custom', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->start_popover();

		$this->add_control(
			'rael_widget_wrapper_width',
			array(
				'label'      => esc_html__( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem', '%' ),
				'range'      => array(
					'px'  => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'rem' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => .5,
					),
					'%'   => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-login-registration-container' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_widget_wrapper_popup' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_widget_wrapper_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-login-registration-container' => $this->rael_apply_dim( 'padding' ),
				),
				'condition'  => array(
					'rael_widget_wrapper_popup' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'rael_widget_wrapper_border',
				'selector'  => '{{WRAPPER}} .rael-login-registration-container',
				'condition' => array(
					'rael_widget_wrapper_popup' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_widget_wrapper_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-login-registration-container' => $this->rael_apply_dim( 'border-radius' ),
				),
				'condition'  => array(
					'rael_widget_wrapper_popup' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'rael_widget_wrapper_bg_color',
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'types'     => array(
					'classic',
					'gradient',
				),
				'selector'  => '{{WRAPPER}} .rael-login-registration-container',
				'condition' => array(
					'rael_widget_wrapper_popup' => 'yes',
				),
			)
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'label'     => __( 'Widget Container Box Shadow', 'responsive-addons-for-elementor' ),
				'name'      => 'rael_widget_wrapper_shadow',
				'selector'  => '{{WRAPPER}} .rael-login-registration-container',
				'exclude'   => array(
					'box_shadow_position',
				),
				'separator' => 'after',
			)
		);

		// Form Container or Box.
		$this->add_control(
			'rael_form_containers_popup',
			array(
				'label'        => __( 'Form Container', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Custom', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->start_popover();

		$this->add_control(
			'rael_form_wrapper_width',
			array(
				'label'      => esc_html__( 'Width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'rem', '%' ),
				'range'      => array(
					'px'  => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'rem' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => .5,
					),
					'%'   => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-form-wrapper' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_form_containers_popup' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_form_wrapper_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-form-wrapper' => $this->rael_apply_dim( 'margin' ),
				),
				'condition'  => array(
					'rael_form_containers_popup' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rael_form_wrapper_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-form-wrapper' => $this->rael_apply_dim( 'padding' ),
				),
				'condition'  => array(
					'rael_form_containers_popup' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'rael_form_wrapper_border',
				'selector'  => '{{WRAPPER}} .rael-form-wrapper',
				'condition' => array(
					'rael_form_containers_popup' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_form_wrapper_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-form-wrapper' => $this->rael_apply_dim( 'border-radius' ),
				),
				'condition'  => array(
					'rael_form_containers_popup' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'rael_form_wrapper_bg_color',
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'types'     => array(
					'classic',
					'gradient',
				),
				'selector'  => '{{WRAPPER}} .rael-form-wrapper',
				'condition' => array(
					'rael_form_containers_popup' => 'yes',
				),
			)
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'label'    => __( 'Form Container Box Shadow', 'responsive-addons-for-elementor' ),
				'name'     => 'rael_form_wrapper_shadow',
				'selector' => '{{WRAPPER}} .rael-form-wrapper',
				'exclude'  => array(
					'box_shadow_position',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * RAE header style controls.
	 *
	 * @param string $form_type has string of login/registration.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function rael_header_style_controls( $form_type = 'login' ) {

		$this->start_controls_section(
			"rael_{$form_type}_header_style_heading",
			array(
				// translators: %s represents the form name.
				'label'      => sprintf( __( '%s Form Additional Styles', 'responsive-addons-for-elementor' ), ucfirst( $form_type ) ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => $this->rael_get_controls_display_condition( $form_type ),
			)
		);

		// Define all css selectors ahead for better management.
		$form_image        = "{{WRAPPER}} .rael-{$form_type}-wrapper .rael-form-image";
		$logo_selector     = "{{WRAPPER}} .rael-{$form_type}-wrapper .rael-header-logo";
		$title_selector    = "{{WRAPPER}} .rael-{$form_type}-wrapper .rael-header-title";
		$subtitle_selector = "{{WRAPPER}} .rael-{$form_type}-wrapper .rael-header-subtitle";

		$this->add_control(
			"rael_{$form_type}_image_po_toggle",
			array(
				'label'        => __( 'Form Image', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Custom', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->start_popover();

		$this->add_responsive_control(
			"rael_{$form_type}_image_height",
			array(
				'label'      => esc_html__( 'height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'rem',
					'%',
				),
				'range'      => array(
					'px'  => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'rem' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => .5,
					),
					'%'   => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 100,
				),
				'selectors'  => array(
					$form_image => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					"rael_{$form_type}_image_po_toggle" => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			"rael_{$form_type}_image_margin",
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					$form_image => $this->rael_apply_dim( 'margin' ),
				),
				'condition'  => array(
					"rael_{$form_type}_image_po_toggle" => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			"rael_{$form_type}_image_padding",
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					$form_image => $this->rael_apply_dim( 'padding' ),
				),
				'condition'  => array(
					"rael_{$form_type}_image_po_toggle" => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => "rael_{$form_type}_image_border",
				'selector'  => $form_image,
				'condition' => array(
					"rael_{$form_type}_image_po_toggle" => 'yes',
				),
			)
		);

		$this->add_control(
			"rael_{$form_type}_image_border_radius",
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
				),
				'selectors'  => array(
					$form_image => $this->rael_apply_dim( 'border-radius' ),
				),
				'condition'  => array(
					"rael_{$form_type}_image_po_toggle" => 'yes',
				),
			)
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'label'    => __( 'Form Image Shadow', 'responsive-addons-for-elementor' ),
				'name'     => "rael_{$form_type}_img_shadow",
				'selector' => $form_image,
				'exclude'  => array(
					'box_shadow_position',
				),
			)
		);

		$this->add_control(
			"rael_{$form_type}_logo_po_toggle",
			array(
				'label'        => __( 'Form Logo', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Custom', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->start_popover();

		$this->add_responsive_control(
			"rael_{$form_type}_logo_width",
			array(
				'label'      => esc_html__( 'width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'rem',
					'%',
				),
				'range'      => array(
					'px'  => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'rem' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => .5,
					),
					'%'   => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 100,
				),
				'selectors'  => array(
					$logo_selector => 'width: {{SIZE}}{{UNIT}}; max-width: fit-content !important;',
				),
				'condition'  => array(
					"rael_{$form_type}_logo_po_toggle" => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			"rael_{$form_type}_logo_height",
			array(
				'label'      => esc_html__( 'height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'rem',
					'%',
				),
				'range'      => array(
					'px'  => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'rem' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => .5,
					),
					'%'   => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 100,
				),
				'selectors'  => array(
					$logo_selector => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					"rael_{$form_type}_logo_po_toggle" => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			"rael_{$form_type}_logo_margin",
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					$logo_selector => $this->rael_apply_dim( 'margin' ),
				),
				'condition'  => array(
					"rael_{$form_type}_logo_po_toggle" => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			"rael_{$form_type}_logo_padding",
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					$logo_selector => $this->rael_apply_dim( 'padding' ),
				),
				'condition'  => array(
					"rael_{$form_type}_logo_po_toggle" => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => "rael_{$form_type}_logo_border",
				'selector'  => $logo_selector,
				'condition' => array(
					"rael_{$form_type}_logo_po_toggle" => 'yes',
				),
			)
		);

		$this->add_control(
			"rael_{$form_type}_logo_border_radius",
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
				),
				'selectors'  => array(
					$logo_selector => $this->rael_apply_dim( 'border-radius' ),
				),
				'condition'  => array(
					"rael_{$form_type}_logo_po_toggle" => 'yes',
				),
			)
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'label'    => __( 'Logo Shadow', 'responsive-addons-for-elementor' ),
				'name'     => "rael_{$form_type}_logo_shadow",
				'selector' => $logo_selector,
				'exclude'  => array(
					'box_shadow_position',
				),
			)
		);

		/*-- Title Typography --*/
		$this->add_control(
			"rael_{$form_type}_title_po_toggle",
			array(
				'label'        => __( 'Title', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Custom', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->start_popover();

		$this->add_responsive_control(
			"rael_{$form_type}_title_margin",
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					$title_selector => $this->rael_apply_dim( 'margin' ),
				),
				'condition'  => array(
					"rael_{$form_type}_title_po_toggle" => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			"rael_{$form_type}_title_padding",
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					$title_selector => $this->rael_apply_dim( 'padding' ),
				),
				'condition'  => array(
					"rael_{$form_type}_title_po_toggle" => 'yes',
				),
			)
		);

		$this->add_control(
			"rael_{$form_type}_title_color",
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$title_selector => 'color: {{VALUE}};',
				),
				'condition' => array(
					"rael_{$form_type}_title_po_toggle" => 'yes',
				),
			)
		);

		$this->add_control(
			"rael_{$form_type}_title_bg_color",
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$title_selector => 'background: {{VALUE}};',
				),
				'condition' => array(
					"rael_{$form_type}_title_po_toggle" => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => "rael_{$form_type}_title_border",
				'selector'  => $title_selector,
				'condition' => array(
					"rael_{$form_type}_title_po_toggle" => 'yes',
				),
			)
		);

		$this->add_control(
			"rael_{$form_type}_title_border_radius",
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
				),
				'selectors'  => array(
					$title_selector => $this->rael_apply_dim( 'border-radius' ),
				),
				'condition'  => array(
					"rael_{$form_type}_title_po_toggle" => 'yes',
				),
			)
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => "rael_{$form_type}_title_typo",
				'label'    => __( 'Title Typography', 'responsive-addons-for-elementor' ),
				'selector' => $title_selector,
			)
		);

		/*Subtitle----*/
		$this->add_control(
			"rael_{$form_type}_subtitle_po_toggle",
			array(
				'label'        => __( 'Subtitle', 'responsive-addons-for-elementor' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'responsive-addons-for-elementor' ),
				'label_on'     => __( 'Custom', 'responsive-addons-for-elementor' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->start_popover();

		$this->add_control(
			"rael_{$form_type}_subtitle_margin",
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					$subtitle_selector => $this->rael_apply_dim( 'margin' ),
				),
				'condition'  => array(
					"rael_{$form_type}_subtitle_po_toggle" => 'yes',
				),
			)
		);

		$this->add_control(
			"rael_{$form_type}_subtitle_padding",
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					$subtitle_selector => $this->rael_apply_dim( 'padding' ),
				),
				'condition'  => array(
					"rael_{$form_type}_subtitle_po_toggle" => 'yes',
				),
			)
		);

		$this->add_control(
			"rael_{$form_type}_subtitle_color",
			array(
				'label'     => __( 'Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$subtitle_selector => 'color: {{VALUE}};',
				),
				'condition' => array(
					"rael_{$form_type}_subtitle_po_toggle" => 'yes',
				),
			)
		);

		$this->add_control(
			"rael_{$form_type}_subtitle_bg_color",
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$subtitle_selector => 'background: {{VALUE}};',
				),
				'condition' => array(
					"rael_{$form_type}_subtitle_po_toggle" => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => "rael_{$form_type}_subtitle_border",
				'selector'  => $subtitle_selector,
				'condition' => array(
					"rael_{$form_type}_subtitle_po_toggle" => 'yes',
				),
			)
		);

		$this->add_control(
			"rael_{$form_type}_subtitle_border_radius",
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
				),
				'selectors'  => array(
					$subtitle_selector => $this->rael_apply_dim( 'border-radius' ),
				),
				'condition'  => array(
					"rael_{$form_type}_subtitle_po_toggle" => 'yes',
				),
			)
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => "rael_{$form_type}_subtitle_typo",
				'label'    => __( 'Subtitle Typography', 'responsive-addons-for-elementor' ),
				'selector' => $subtitle_selector,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * RAE input field label controls.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function rael_input_field_labels_controls() {

		$this->start_controls_section(
			'rael_form_labels_style',
			array(
				'label' => __( 'Form Labels', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_form_label_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-register-label' => $this->rael_apply_dim( 'margin' ),
					'{{WRAPPER}} .rael-login-label'    => $this->rael_apply_dim( 'margin' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_form_label_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-register-label' => $this->rael_apply_dim( 'padding' ),
					'{{WRAPPER}} .rael-login-label'    => $this->rael_apply_dim( 'padding' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'rael_label_typography',
				'selectors' => array(
					'{{WRAPPER}} .rael-register-label',
					'{{WRAPPER}} .rael-login-label',
				),
			)
		);

		$this->add_control(
			'rael_label_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-register-label' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-login-label'    => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'rael_req_mark_color',
			array(
				'label'     => __( 'Required Mark Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .required-asterix:after' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_pass_toggle_po_toggle',
			array(
				'label'     => __( 'Password Visibility Style', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::POPOVER_TOGGLE,
				'condition' => array(
					'rael_password_toggle' => 'yes',
				),
			)
		);

		$this->start_popover();

		$this->add_responsive_control(
			'rael_pass_toggle_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-show-password' => $this->rael_apply_dim( 'padding' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_pass_toggle_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'rem',
					'%',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .rael-show-password .dashicons' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'rael_pass_toggle_po_toggle' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_pass_toggle_open_color',
			array(
				'label'     => __( 'Open Eye Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-show-password .dashicons-visibility' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_pass_toggle_po_toggle' => 'yes',
				),
			)
		);

		$this->add_control(
			'rael_pass_toggle_close_color',
			array(
				'label'     => __( 'Close Eye Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-show-password .dashicons-hidden' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_pass_toggle_po_toggle' => 'yes',
				),
			)
		);

		$this->end_popover();

		$this->add_control(
			'rael_form_label_enforce_pass_heading',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Enforce Password', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_form_label_enforce_pass_invalid_color',
			array(
				'label'     => __( 'Color For Invalid Condition', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e50000',
				'selectors' => array(
					'{{WRAPPER}} .rael-register-form-container .rael-confirm-pass-error' => 'color: {{VALUE}};',
					'{{WRAPPER}} .rael-register-form-container .rael-enforce-pass'       => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_form_label_enforce_pass_valid_color',
			array(
				'label'     => __( 'Color For Valid Condition', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#00b300',
				'selectors' => array(
					'{{WRAPPER}} .rael-register-form-container .rael-valid' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_form_label_tc_custom_text_heading',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Terms & Conditions Custom Text', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
				'condition' => array(
					'rael_acceptance_text_source' => 'editor',
				),
			)
		);

		$this->add_control(
			'rael_form_label_tc_custom_text_color',
			array(
				'label'     => __( 'Checked Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-register-form-container .rael-acceptance-text' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'rael_acceptance_text_source' => 'editor',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * RAE input field style controls.
	 *
	 * @access protected
	 */
	protected function rael_input_field_style_controls() {

		$input_fields = '.rael-input';

		$this->start_controls_section(
			'rael_input_field_styling',
			array(
				'label' => __( 'Form Input Fields', 'responsive-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'rael_form_field_margin',
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					"{{WRAPPER}} {$input_fields}" => $this->rael_apply_dim( 'margin' ),
				),
			)
		);

		$this->add_responsive_control(
			'rael_form_field_padding',
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					"{{WRAPPER}} {$input_fields}" => $this->rael_apply_dim( 'padding' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rael_form_fields_typography',
				'selector' => "{{WRAPPER}} {$input_fields}",
			)
		);

		$this->add_responsive_control(
			'rael_form_fields_text_align_align',
			array(
				'label'     => __( 'Text Alignment', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'responsive-addons-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'   => 'left',
				'selectors' => array(
					"{{WRAPPER}} {$input_fields}" => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_form_label_colors_heading',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Colors & Border', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_form_field_color',
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					"{{WRAPPER}} {$input_fields}" => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'rael_form_field_border',
				'selector' => "{{WRAPPER}} {$input_fields}",
			)
		);

		$this->add_control(
			'rael_form_field_border_radius',
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
				),
				'selectors'  => array(
					"{{WRAPPER}} {$input_fields}" => $this->rael_apply_dim( 'border-radius' ),
				),
			)
		);

		$this->start_controls_tabs( 'rael_tabs_form_fields_style' );

		/*-----Form Input Fields NORMAL state------ */
		$this->start_controls_tab(
			'rael_normal_form_field_style_normal',
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_normal_form_field_placeholder_color',
			array(
				'label'     => __( 'Placeholder Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					"{{WRAPPER}} {$input_fields}::placeholder" => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_normal_form_field_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					"{{WRAPPER}} {$input_fields}" => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'rael_tab_form_field_style_focus',
			array(
				'label' => __( 'Focus', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'rael_focus_form_field_placeholder_color',
			array(
				'label'     => __( 'Placeholder Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					"{{WRAPPER}} {$input_fields}:focus::placeholder" => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rael_focus_form_field_bg_color',
			array(
				'label'     => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					"{{WRAPPER}} {$input_fields}:focus" => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'rael_form_label_tc_heading',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Terms & Conditions', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'rael_form_field_tc_color',
			array(
				'label'     => __( 'Checked Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .rael-register-form-container input:checked+.rael-tc-slider' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}
	/**
	 * RAE input login button style controls.
	 *
	 * @access protected
	 */
	protected function rael_login_button_style_controls() {
		$this->rael_init_button_style_template( 'login' );
	}
	/**
	 * RAE register button style controls.
	 *
	 * @access protected
	 */
	protected function rael_register_button_style_controls() {
		$this->rael_init_button_style_template( 'register' );
	}
	/**
	 * RAE login recaptcha style controls.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function rael_login_recaptcha_style_controls() {
		$this->rael_recaptcha_style_template( 'login' );
	}
	/**
	 * RAE register recaptcha style controls.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function rael_register_recaptcha_style_controls() {
		$this->rael_recaptcha_style_template( 'register' );
	}
	/**
	 * RAE login link style controls.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function rael_login_link_style_controls() {
		$this->rael_link_style_template( 'login' );
	}
		/**
		 * RAE register link style controls.
		 *
		 * @since 1.1.0
		 * @access protected
		 */
	protected function rael_register_link_style_controls() {
		$this->rael_link_style_template( 'register' );
	}

	/**
	 * Print style controls for a specific type of button.
	 *
	 * @param string $button_type the type of the button. accepts login or register.
	 */
	protected function rael_init_button_style_template( $button_type = 'login' ) {

		$button_selector = ".rael-{$button_type}-submit";

		$this->start_controls_section(
			"rael_{$button_type}_button_style",
			array(
				// translators: %s represents the button type.
				'label'      => sprintf( __( '%s Button', 'responsive-addons-for-elementor' ), ucfirst( $button_type ) ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => $this->rael_get_controls_display_condition( $button_type ),
			)
		);

		$this->add_responsive_control(
			"rael_{$button_type}_button_margin",
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					"{{WRAPPER}} {$button_selector}" => $this->rael_apply_dim( 'margin' ),
				),
			)
		);

		$this->add_responsive_control(
			"rael_{$button_type}_button_padding",
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					"{{WRAPPER}} {$button_selector}" => $this->rael_apply_dim( 'padding' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => "rael_{$button_type}_button_typography",
				'selector' => "{{WRAPPER}} {$button_selector}",
			)
		);

		$this->add_control(
			"rael_tabs_{$button_type}_button_colors_heading",
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Colors & Border', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => "rael_{$button_type}_button_border",
				'selector' => "{{WRAPPER}} {$button_selector}",
			)
		);

		$this->add_control(
			"rael_{$button_type}_button_border_radius",
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
				),
				'selectors'  => array(
					"{{WRAPPER}} {$button_selector}" => $this->rael_apply_dim( 'border-radius' ),
				),
			)
		);

		$this->start_controls_tabs( "rael_tabs_{$button_type}_button_style" );
		/*-----Login Button NORMAL state------ */
		$this->start_controls_tab(
			"rael_tab_{$button_type}_button_normal",
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			"rael_{$button_type}_button_color",
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					"{{WRAPPER}} {$button_selector}" => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => "rael_{$button_type}_button_bg_color",
				'label'    => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'types'    => array(
					'classic',
					'gradient',
				),
				'selector' => "{{WRAPPER}} {$button_selector}",
			)
		);

		$this->end_controls_tab();

		/*-----Login Button HOVER state------ */
		$this->start_controls_tab(
			"rael_tab_{$button_type}_button_hover",
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			"rael_{$button_type}_button_color_hover",
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					"{{WRAPPER}} {$button_selector}:hover" => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => "rael_{$button_type}_button_bg_color_hover",
				'label'    => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'types'    => array(
					'classic',
					'gradient',
				),
				'selector' => "{{WRAPPER}} {$button_selector}:hover",
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		/*-----ends Button tabs--------*/

		$this->add_responsive_control(
			"rael_{$button_type}_button_width",
			array(
				'label'      => esc_html__( 'Button width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'%',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					"{{WRAPPER}} {$button_selector}" => 'width: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			"rael_{$button_type}_button_height",
			array(
				'label'      => esc_html__( 'Button Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'%',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					"{{WRAPPER}} {$button_selector}" => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Print style controls for a specific type of reCAPTCHA.
	 *
	 * @param string $form_type the type of the reCAPTCHA. accepts login or register.
	 */
	protected function rael_recaptcha_style_template( $form_type = 'login' ) {

		$this->start_controls_section(
			"rael_{$form_type}_rc_style",
			array(
				// translators: %s represents the form type.
				'label'     => sprintf( __( '%s Form reCAPTCHA', 'responsive-addons-for-elementor' ), ucfirst( $form_type ) ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					"rael_enable_{$form_type}_recaptcha" => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			"rael_{$form_type}_rc_margin",
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					"{{WRAPPER}} .rael-{$form_type}-form-container .rael-recaptcha" => $this->rael_apply_dim( 'margin' ),
				),
			)
		);

		$this->add_control(
			"rael_{$form_type}_rc_theme",
			array(
				'label'   => __( 'Theme', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'light' => __( 'Light', 'responsive-addons-for-elementor' ),
					'dark'  => __( 'Dark', 'responsive-addons-for-elementor' ),
				),
				'default' => 'light',
			)
		);

		$this->add_control(
			"rael_{$form_type}_rc_size",
			array(
				'label'   => __( 'Size', 'responsive-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'normal'  => __( 'Normal', 'responsive-addons-for-elementor' ),
					'compact' => __( 'Compact', 'responsive-addons-for-elementor' ),
				),
				'default' => 'normal',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Print style controls for a specific type of link on register or login form.
	 *
	 * @param string $form_type the type of form where the link is being shown. accepts login or register.
	 */
	protected function rael_link_style_template( $form_type = 'login' ) {

		if ( 'login' === $form_type ) {
			$form_name     = __( 'Register', 'responsive-addons-for-elementor' );
			$link_selector = ".rael-{$form_type}-form-footer .rael-login-reg-link";
		} else {
			$form_name     = __( 'Login', 'responsive-addons-for-elementor' );
			$link_selector = ".rael-{$form_type}-form-footer .rael-reg-login-link";
		}

		$this->start_controls_section(
			"rael_{$form_type}_link_style",
			array(
				/* translators: %s: Insert form type name */
				'label'     => sprintf( __( '%s Link', 'responsive-addons-for-elementor' ), ucfirst( $form_name ) ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					"rael_show_{$form_type}_link" => 'yes',
				),
			)
		);

		$this->add_control(
			"rael_{$form_type}_link_style_notice",
			array(
				'type'            => Controls_Manager::RAW_HTML,
				/* translators: %s: Insert form link type and form type name */
				'raw'             => sprintf( __( 'Here you can style the %1$s link displayed on the %2$s Form.', 'responsive-addons-for-elementor' ), $form_name, ucfirst( $form_type ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_responsive_control(
			"rael_{$form_type}_link_margin",
			array(
				'label'      => __( 'Margin', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					"{{WRAPPER}} {$link_selector}" => $this->rael_apply_dim( 'margin' ),
				),
			)
		);

		$this->add_responsive_control(
			"rael_{$form_type}_link_padding",
			array(
				'label'      => __( 'Padding', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'em',
					'%',
				),
				'selectors'  => array(
					"{{WRAPPER}} {$link_selector}" => $this->rael_apply_dim( 'padding' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => "rael_{$form_type}_link_typography",
				'selector' => "{{WRAPPER}} {$link_selector}",
			)
		);

		$this->add_responsive_control(
			"rael_{$form_type}_link_display_type",
			array(
				'label'     => __( 'Display as', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'row'    => __( 'Inline', 'responsive-addons-for-elementor' ),
					'column' => __( 'Block', 'responsive-addons-for-elementor' ),
				),
				'default'   => 'row',
				'selectors' => array(
					"{{WRAPPER}} .rael-{$form_type}-form-footer" => 'display:flex; flex-direction: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			"rael_tabs_{$form_type}_link_colors_heading",
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Colors & Border', 'responsive-addons-for-elementor' ),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => "rael_{$form_type}_link_border",
				'selector' => "{{WRAPPER}} {$link_selector}",
			)
		);

		$this->add_control(
			"rael_{$form_type}_link_border_radius",
			array(
				'label'      => __( 'Border Radius', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
				),
				'selectors'  => array(
					"{{WRAPPER}} {$link_selector}" => $this->rael_apply_dim( 'border-radius' ),
				),
			)
		);

		$this->start_controls_tabs( "rael_tabs_{$form_type}_link_style" );
		/*----- Link NORMAL state------ */
		$this->start_controls_tab(
			"rael_tab_{$form_type}_link_normal",
			array(
				'label' => __( 'Normal', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			"rael_{$form_type}_link_color",
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					"{{WRAPPER}} {$link_selector}" => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => "rael_{$form_type}_link_bg_color",
				'label'    => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'types'    => array(
					'classic',
					'gradient',
				),
				'selector' => "{{WRAPPER}} {$link_selector}",
			)
		);

		$this->end_controls_tab();

		/*-----Link HOVER state------ */
		$this->start_controls_tab(
			"rael_tab_{$form_type}_link_hover",
			array(
				'label' => __( 'Hover', 'responsive-addons-for-elementor' ),
			)
		);

		$this->add_control(
			"rael_{$form_type}_link_color_hover",
			array(
				'label'     => __( 'Text Color', 'responsive-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					"{{WRAPPER}} {$link_selector}:hover" => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => "{$form_type}_link_bg_color_hover",
				'label'    => __( 'Background Color', 'responsive-addons-for-elementor' ),
				'types'    => array(
					'classic',
					'gradient',
				),
				'selector' => "{{WRAPPER}} {$link_selector}:hover",
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		/*-----ends Link tabs--------*/

		$this->add_responsive_control(
			"rael_{$form_type}_link_width",
			array(
				'label'      => esc_html__( 'Link width', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'%',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					"{{WRAPPER}} {$link_selector}" => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			"rael_{$form_type}_link_height",
			array(
				'label'      => esc_html__( 'Link Height', 'responsive-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'%',
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					"{{WRAPPER}} {$link_selector}" => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}
	
	/**
	 * Form Logo Position
	 *
	 * @var string
	 */
	protected $form_logo_pos;

	/**
	 * Page Id
	 *
	 * @var string
	 */
	protected $page_id;
	
	/**
	 * Should print login form?
	 *
	 * @var bool
	 */
	protected $should_print_login_form;
	
	/**
	 * Should print registration form?
	 *
	 * @var bool
	 */
	protected $should_print_registration_form;
	
	/**
	 * Form Image URL
	 *
	 * @var string
	 */
	protected $form_image_url;
	
	/**
	 * Logo URL
	 *
	 * @var string
	 */
	protected $logo_url;
	
	/**
	 * Render
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		if ( ! $this->in_editor && 'yes' === $settings['rael_hide_for_logged_in_user'] && is_user_logged_in() ) {
			return; // do not show any form for already logged in user but editing in the editor is allowed.
		}

		// Get ID.
		if ( Plugin::$instance->documents->get_current() ) {
			$this->page_id = Plugin::$instance->documents->get_current()->get_main_id();
		}

		// Get form logo position.
		$this->form_logo_pos = ! empty( $settings['rael_form_logo_position'] ) ? $settings['rael_form_logo_position'] : 'block';

		// Get form type.
		$default_form = $settings['rael_form_type'];

		// Get setting for which form to show.
		$this->should_print_login_form        = ( 'login' === $default_form || 'yes' === $settings['rael_show_login_link'] );
		$this->should_print_registration_form = ( $this->user_can_register && ( 'register' === $settings['rael_form_type'] || 'yes' === $settings['rael_show_register_link'] ) );

		// Get form image.
		$this->form_image_url = esc_attr( esc_url( $settings['rael_form_image']['url'] ) );

		// Get form logo.
		$this->logo_url = esc_attr( esc_url( $settings['rael_form_logo']['url'] ) );

		$login_redirect_url = '';
		if ( ! empty( $settings['rael_redirect_after_login'] ) && 'yes' === $settings['rael_redirect_after_login'] ) {
			$login_redirect_url = ! empty( $settings['rael_redirect_url']['url'] ) ? esc_url( $settings['rael_redirect_url']['url'] ) : '';
		}

		$this->rael_print_styles( $settings );
		?>
		<div
			class="rael-login-registration-container"
			id="rael-login-registration-container"
			form="<?php echo esc_html( $default_form ); ?>"
			data-recaptcha-sitekey="<?php echo esc_attr( $this->recaptcha_sitekey ); ?>"
			data-widget-id="<?php echo esc_attr( $this->get_id() ); ?>"
			data-redirect-to="<?php echo esc_attr( $login_redirect_url ); ?>">
			<?php
			$this->print_login_form( $settings );
			$this->print_registration_form( $settings );
			?>
		</div>
		<?php
	}

	/**
	 * Prints entire login form as per the content settings.
	 *
	 * @param array $settings
	 */
	protected function print_login_form( $settings ) {
		if ( $this->should_print_login_form ) {

			// Get input fields labels and placeholders.
			$display_label        = $settings['rael_show_login_labels'];
			$username_label       = __( 'Username or Email Address', 'responsive-addons-for-elementor' );
			$username_placeholder = __( 'Username or Email Address', 'responsive-addons-for-elementor' );
			$password_label       = __( 'Password', 'responsive-addons-for-elementor' );
			$password_placeholder = __( 'Password', 'responsive-addons-for-elementor' );
			if ( 'custom' === $settings['rael_login_label_types'] ) {
				if ( $display_label ) {
					$username_label = $settings['rael_login_username_label'];
					$password_label = $settings['rael_login_password_label'];
				}
				$username_placeholder = $settings['rael_login_username_placeholder'];
				$password_placeholder = $settings['rael_login_password_placeholder'];
			}

			// Get remember me details.
			$show_remember_me = esc_html( $settings['rael_show_remember_me'] );
			$remember_me_text = esc_html( $settings['rael_remember_me_text'] );

			// Get forgot password details.
			$show_fp = esc_html( $settings['rael_show_forgot_password'] );
			$fp_text = esc_html( $settings['rael_forgot_password_text'] );
			$fp_link = sprintf( '<a class="rael-login-label" href="%s">%s</a>', esc_attr( wp_lostpassword_url() ), $fp_text );
			if ( 'custom' === $settings['rael_forgot_password_link_type'] ) {
				$fp_link_fields = $settings['rael_forgot_password_url'];
				$fp_url         = ! empty( $fp_link_fields['url'] ) ? esc_url( $fp_link_fields['url'] ) : '';
				$fp_attributes  = ! empty( $fp_link_fields['is_external'] ) ? ' target="_blank"' : '';
				$fp_attributes .= ! empty( $fp_link_fields['nofollow'] ) ? ' rel="nofollow"' : '';
				$fp_attributes .= ' class="rael-login-label" ';
				$fp_link        = sprintf( '<a href="%s" %s >%s</a>', $fp_url, $fp_attributes, $fp_text );
			}

			// Get login button details.
			$login_button_text = esc_html( $settings['rael_login_button_text'] );

			// Get register link details.
			$show_register_link  = $settings['rael_show_register_link'];
			$register_link_type  = ! empty( $settings['rael_registration_link_action'] ) ? $settings['rael_registration_link_action'] : 'form';
			$register_link_text  = ! empty( $settings['rael_registration_link_text'] ) ? esc_html( $settings['rael_registration_link_text'] ) : '';
			$register_attributes = '';
			$register_url        = '';
			switch ( $register_link_type ) {
				case 'custom':
					$register_link_fields = $settings['custom_register_url'];
					$register_url         = ! empty( $register_link_fields['url'] ) ? esc_url( $register_link_fields['url'] ) : '';
					$register_attributes  = ! empty( $register_link_fields['is_external'] ) ? ' target="_blank"' : '';
					$register_attributes .= ! empty( $register_link_fields['nofollow'] ) ? ' rel="nofollow"' : '';
					break;
				case 'default':
					$register_url = wp_registration_url();
					break;
			}
			?>

			<div
				class="rael-login-wrapper"
				id="rael-login-wrapper"
				data-recaptcha-theme="<?php echo esc_attr( $settings['rael_login_rc_theme'] ); ?>"
				data-recaptcha-size="<?php echo esc_attr( $settings['rael_login_rc_size'] ); ?>">
			<?php
			if ( $settings['rael_show_logout_message'] && is_user_logged_in() && ! $this->in_editor ) {
				echo '<p class="already-logged-in">You are already logged in as ' . esc_html( wp_get_current_user()->display_name ) . ' (<a href="' . esc_url( wp_logout_url() ) . '">Logout</a>)</p>';
			} else {
				if ( 'left' === $settings['rael_form_image_position'] ) {
					$this->print_form_image( $settings );
				}
				?>
				<div class="rael-login-form-container rael-form-wrapper rael-form-logo-<?php echo esc_attr( $this->form_logo_pos ); ?>">
				<?php $this->print_form_header( $settings, 'login' ); ?>
					<form id="rael-login-form" class="rael-login-form" method="POST">
						<div class="rael-login-form-group">
						<?php if ( $display_label ) { ?>
								<label class="rael-login-label rael-login-username-label">
									<?php echo esc_html( esc_html( $username_label ) ); ?>
								</label>
							<?php } ?>
							<input class="rael-input rael-login-input"
									name="rael-login-name"
									type="text"
									placeholder="<?php echo esc_html( esc_html( $username_placeholder ) ); ?>"
									required />
						</div>
						<div class="rael-login-form-group">
							<?php if ( $display_label ) { ?>
								<label class="rael-login-label rael-login-username-label">
									<?php echo esc_html( esc_html( $password_label ) ); ?>
								</label>
							<?php } ?>
							<div class="rael-login-password">
								<input class="rael-input rael-login-input rael-login-password-input"
										name="rael-login-password"
										id="rael-login-password-input"
										type="password"
										placeholder="<?php echo esc_attr( esc_html( $password_placeholder ) ); ?>"
										required />
							<?php if ( $settings['rael_password_toggle'] ) { ?>
									<button type="button"
											id="rael-login-pass-toggle"
											class="rael-show-password"
											aria-label="Show password">
										<span id="rael-login-pass-toggle-icon" class="dashicons dashicons-visibility"
											aria-hidden="true"></span>
									</button>
								<?php } ?>
							</div>
						</div>
						<?php if ( $show_remember_me && ! empty( $remember_me_text ) || $show_fp ) { ?>
							<div class="rael-additional-form-options">
							<?php if ( $show_remember_me && ! empty( $remember_me_text ) ) { ?>
								<div class="rael-remember-me">
									<label for="remember-me" class="rael-login-label rael-remember-me-label">
									<input name="rael-remember-me"
											type="checkbox"
											id="remember-me"
											class="remember-me-input"
											value="checked">
											<?php echo esc_html( $remember_me_text ); ?>
									</label>
								</div>
								<?php
							}
							if ( $show_fp ) {
								echo '<div class="rael-forgot-password">' . esc_url( $fp_link ) . '</div>';
							}
							?>
						</div>
							<?php
						}
						$this->print_recaptcha_check( $settings, 'login' );
						?>

						<div class="rael-login-form-footer">
							<input type="submit"
									name="rael-login-submit"
									id="rael-login-submit"
									class="rael-login-submit"
									value="<?php echo esc_attr( $login_button_text ); ?>"/>
							<?php if ( $show_register_link ) { ?>
								<?php echo '<a class="rael-login-reg-link" id="rael-login-reg-link-' . esc_html( $register_link_type ) . '" href=' . esc_url( $register_url ) . esc_attr( esc_html( $register_attributes ) ) . '>' . esc_html( $register_link_text ) . '</a>'; ?>
							<?php } ?>
						</div>
						<?php
						$this->rael_print_necessary_hidden_fields( $settings, 'login' );
						?>
					</form>
					<?php
					$this->rael_print_login_validation_errors( $settings );
					?>
				</div>
				<?php
				if ( 'right' === $settings['rael_form_image_position'] ) {
					$this->print_form_image( $settings );
				}
			}
			?>
			</div>
			<?php
		}
	}

	/**
	 * Prints the registration form.
	 *
	 * @param array $settings Form settings
	 */
	protected function print_registration_form( $settings ) {
		if ( $this->should_print_registration_form ) {
			$is_pass_valid     = false; // Does the form has a password field?
			$is_pass_confirmed = false; // Does the form has a confirm password field?
			// placeholders to flag if user use one type of field more than once.
			$user_name_exists          = 0;
			$email_exists              = 0;
			$password_exists           = 0;
			$confirm_pass_exists       = 0;
			$first_name_exists         = 0;
			$last_name_exists          = 0;
			$website_exists            = 0;
			$form_input_types          = array(
				'user_name'        => __( 'Username', 'responsive-addons-for-elementor' ),
				'email'            => __( 'Email', 'responsive-addons-for-elementor' ),
				'password'         => __( 'Password', 'responsive-addons-for-elementor' ),
				'confirm_password' => __( 'Confirm Password', 'responsive-addons-for-elementor' ),
				'first_name'       => __( 'First Name', 'responsive-addons-for-elementor' ),
				'last_name'        => __( 'Last Name', 'responsive-addons-for-elementor' ),
				'website'          => __( 'Website', 'responsive-addons-for-elementor' ),
			);
			$repeated_form_input_types = array();

			// Get repeater settings.
			$register_input_fields = $settings['rael_register_input_fields'];

			// Get Register button details.
			$register_button_text = $settings['rael_reg_button_text'];

			// Get login link details.
			$show_login_link  = $settings['rael_show_login_link'];
			$login_link_text  = esc_html( $settings['rael_login_link_text'] );
			$login_link_type  = ! empty( $settings['rael_login_link_action'] ) ? $settings['rael_login_link_action'] : 'form';
			$login_url        = '';
			$login_attributes = '';
			switch ( $login_link_type ) {
				case 'custom':
					$login_link_fields = $settings['rael_custom_login_url'];
					$login_url         = ! empty( $login_link_fields['url'] ) ? esc_url( $login_link_fields['url'] ) : '';
					$login_attributes  = ! empty( $login_link_fields['is_external'] ) ? ' target="_blank"' : '';
					$login_attributes .= ! empty( $login_link_fields['nofollow'] ) ? ' rel="nofollow"' : '';
					break;
				case 'default':
					$login_url = wp_registration_url();
					break;
			}
			ob_start();
			?>
			<div
				class="rael-register-wrapper"
				id="rael-register-wrapper"
				data-recaptcha-theme="<?php echo esc_attr( $settings['rael_register_rc_theme'] ); ?>"
				data-recaptcha-size="<?php echo esc_attr( $settings['rael_register_rc_size'] ); ?>">
				<?php
				if ( 'left' === $settings['rael_form_image_position'] ) {
					$this->print_form_image( $settings );
				}
				?>
				<div class="rael-register-form-container rael-form-wrapper  rael-form-logo-<?php echo esc_attr( $this->form_logo_pos ); ?>">
					<?php
					$this->print_form_header( $settings, 'register' );
					?>
					<form id="rael-register-form" class="rael-register-form" method="POST">
						<?php
						foreach ( $register_input_fields as  $key => $item ) {
							$field_type         = $item['rael_field_type'];
							$dynamic_field_name = "{$field_type}_exists";
							$$dynamic_field_name ++; // NOTE, double $$ intentional. Dynamically update the var check eg. $username_exists++ to prevent user from using the same field twice
							// is same field repeated?
							if ( $$dynamic_field_name > 1 ) {
								$repeated_form_input_types[] = $form_input_types[ $field_type ];
							}
							if ( 'password' === $field_type ) {
								$is_pass_valid = true;
							}

							$current_field_required = ( ! empty( $item['rael_required'] ) || in_array(
								$field_type,
								array(
									'password',
									'confirm_pass',
									'email',
								),
								true
							) );

							$enforce_password_rules = ( ! empty( $item['rael_reg_enforce_password_rules'] ) && in_array(
								$field_type,
								array( 'password' ),
								true
							) );

							// keys for attribute binding.
							$input_key       = "input{$key}";
							$label_key       = "label{$key}";
							$field_group_key = "field-group{$key}";

							// determine proper input tag type.
							switch ( $field_type ) {
								case 'user_name':
								case 'first_name':
								case 'last_name':
									$input_field_type = 'text';
									break;
								case 'confirm_pass':
									$input_field_type = 'password';
									break;
								case 'website':
									$input_field_type = 'url';
									break;
								default:
									$input_field_type = $field_type;
							}

							$this->add_render_attribute(
								array(
									$input_key => array(
										'name'        => $field_type,
										'type'        => $input_field_type,
										'placeholder' => $item['rael_placeholder'],
										'class'       => array(
											'rael-input',
											'rael-register-input',
											'form-field-' . $field_type,
										),
										'id'          => 'reg-field-' . $field_type,
									),
									$label_key => array(
										'for'   => 'reg-field-' . $field_type,
										'class' => 'rael-register-label',
									),
								)
							);

							// print required input field attributes.
							if ( $current_field_required ) {
								$this->add_render_attribute(
									$input_key,
									array(
										'required'      => 'required',
										'aria-required' => 'true',
									)
								);
								if ( 'yes' === $settings['rael_required_mark'] ) {
									$this->add_render_attribute(
										$label_key,
										array(
											'class' => 'required-asterix',
										)
									);
								}
							}

							if ( $enforce_password_rules ) {
								$this->add_render_attribute(
									$input_key,
									array(
										'pattern' => '[0-9a-zA-Z!_@#$%^&-+=()]{8,}',
									)
								);
							}

							if ( 'password' === $field_type ) {
								$this->add_render_attribute(
									$input_key,
									array(
										'class' => 'rael-reg-password-input',
									)
								);
							}

							echo '<div class="rael-register-form-group">';

							if ( 'yes' === $settings['rael_show_reg_labels'] && ! empty( $item['rael_field_label'] ) ) {
								echo '<label ' . wp_kses_post( $this->get_render_attribute_string( $label_key ) ) . '>' . esc_html( $item['rael_field_label'] ) . '</label>';
							}

							if ( $item['rael_reg_password_toggle'] ) {
								echo '<div class="rael-reg-password">';
							}

							echo '<input ' . wp_kses_post( $this->get_render_attribute_string( $input_key ) ) . '>';

							if ( 'yes' === $item['rael_reg_password_toggle'] ) {
								?>
								<button type="button"
										id="rael-reg-pass-toggle"
										class="rael-show-password"
										aria-label="Show password">
									<span id="rael-reg-pass-toggle-icon" class="dashicons dashicons-visibility"
										aria-hidden="true"></span>
								</button>
							</div>
								<?php
							}

							echo '</div>';

							if ( $enforce_password_rules ) {
								?>
								<p class="rael-enforce-pass" id="eight-char">
									<i class="fas fa-times-circle" id="valid-icon"></i>
									Password must contain at least 8 characters.
								</p>
								<p class="rael-enforce-pass" id="one-digit">
									<i class="fas fa-times-circle" id="valid-icon"></i>
									Password must contain at least one digit.
								</p>
								<p class="rael-enforce-pass" id="one-lower-char">
									<i class="fas fa-times-circle" id="valid-icon"></i>
									Password must contain at least one lowercase character.
								</p>
								<p class="rael-enforce-pass" id="one-upper-char">
									<i class="fas fa-times-circle" id="valid-icon"></i>
									Password must contain at least one uppercase character.
								</p>
								<p class="rael-enforce-pass" id="one-special-char" style="margin-bottom:1.2em;">
									<i class="fas fa-times-circle" id="valid-icon"></i>
									Password must contain at least one special character.
								</p>
								<?php
							}

							if ( 'confirm_pass' === $field_type ) {
								?>
								<p class="rael-confirm-pass-error" id="confirm-pass-error" style="margin-bottom: 1.2em;">
									<i class="fas fa-times-circle" id="valid-icon"></i>
									Password and Confirm Password must match.
								</p>
								<?php
							}
						}

						$this->print_terms_condition_notice( $settings );
						$this->print_recaptcha_check( $settings, 'register' );
						$this->rael_print_necessary_hidden_fields( $settings, 'register' );
						?>

						<div class="rael-register-form-footer">
							<input type="submit"
									name="rael-register-submit"
									id="rael-register-submit"
									class="rael-register-submit rael-disabled"
									value="<?php echo esc_attr( $register_button_text ); ?>"
									disabled="disabled"/>
							<?php if ( $show_login_link ) { ?>
								<?php echo '<a class="rael-reg-login-link" id="rael-reg-login-link-' . esc_html( $login_link_type ) . '" href="' . esc_url( $login_url ) . esc_attr( esc_html( $login_attributes ) ) . '">' . esc_html( $login_link_text ) . '</a>'; ?>
							<?php } ?>
						</div>
					</form>
					<?php $this->rael_print_register_validation_message( $settings ); ?>
				</div>
			</div>
			<?php
			if ( 'right' === $settings['rael_form_image_position'] ) {
				$this->print_form_image( $settings );
			}
			$registration_form = ob_get_clean();
			// if we are in the editor then show error related to different input field.
			if ( $this->in_editor ) {
				echo '<div class="possible-reg-errors" id="possible-reg-errors">';
					$repeated               = $this->rael_field_repeated_error( $repeated_form_input_types );
					$username_input_missing = $this->rael_username_input_missing( $user_name_exists );
					$email_input_missing    = $this->rael_eamil_input_missing( $email_exists );
					$password_missing       = $this->rael_password_input_missing( $password_exists, $confirm_pass_exists );
				echo '</div>';
				if ( $repeated || $username_input_missing || $email_input_missing || $password_missing ) {
					return false;
				}
				echo wp_kses( $registration_form, self::get_allowed_tags() );
			} else {
				echo wp_kses( $registration_form, self::get_allowed_tags() );
			}
		}
	}

	public function get_allowed_tags() {
		return array(
			'div'    => array(
				'class' => array(),
				'id'    => array(),
			),
			'a'      => array(
				'href'   => array(),
				'class'  => array(),
				'target' => array(),
				'rel'    => array(),
				'id'     => array(),
			),
			'form'   => array(
				'id'     => array(),
				'class'  => array(),
				'method' => array(),
			),
			'input'  => array(
				'name'          => array(),
				'type'          => array(),
				'placeholder'   => array(),
				'class'         => array(),
				'id'            => array(),
				'required'      => array(),
				'aria-required' => array(),
				'pattern'       => array(),
				'value'         => array(),
				'disabled'      => array(),
			),
			'label'  => array(
				'for'   => array(),
				'class' => array(),
			),
			'p'      => array(
				'class' => array(),
				'id'    => array(),
			),
			'i'      => array(
				'class' => array(),
			),
			'button' => array(
				'type'       => array(),
				'id'         => array(),
				'class'      => array(),
				'aria-label' => array(),
			),
			'span'   => array(
				'class' => array(),
				'id'    => array(),
			),
		);
	}

	/**
	 * RAE print login validation errors.
	 *
	 * @param array $settings takes settings as an array in argument.
	 */
	protected function rael_print_login_validation_errors( $settings ) {
		$error_key   = 'rael_login_error_' . $this->get_id();
		$login_error = apply_filters( 'rael/login-register/login-error-message', get_option( $error_key ) );
		if ( ! empty( $login_error ) ) {
			?>
			<p class="rael-form-message rael-form-message-invalid">
				<?php echo esc_html( $login_error ); ?>
			</p>
			<?php
			delete_option( $error_key );
		}
	}
	/**
	 * RAE print register validation message
	 *
	 * @param array $settings takes settings as an array in argument.
	 */
	protected function rael_print_register_validation_message( $settings ) {
		$errors  = get_option( 'rael_register_errors_' . $this->get_id() );
		$success = get_option( 'rael_register_success_' . $this->get_id() );
		if ( empty( $errors ) && empty( $success ) ) {
			return;
		}
		if ( ! empty( $errors ) || is_array( $errors ) ) {
			$this->rael_print_registration_error_messages( $settings, $errors );
		} elseif ( ! empty( $success ) || is_array( $success ) ) {
			$this->rael_print_registration_success_messages( $settings, $success );
		}
	}
	/**
	 * RAE print registration error message.
	 *
	 * @param array $settings takes settings as an array in argument.
	 * @param array $errors takes errors as an array in argument.
	 */
	protected function rael_print_registration_error_messages( $settings, $errors ) {
		?>
		<div class="rael-form-message rael-form-message-invalid">
			<?php
			if ( is_array( $errors ) ) {
				?>
				<ul>
					<?php
					foreach ( $errors as $register_error ) {
						printf( '<li>%s</li>', esc_html( $register_error ) );
					}
					?>
				</ul>
				<?php
			} elseif ( ! is_array( $errors ) ) {
				printf( '<p>%s</p>', esc_html( $errors ) );
			} elseif ( ! empty( $settings['rael_error_unknown'] ) ) {
				printf( '<p>%s</p>', esc_html( $settings['rael_error_unknown'] ) );
			}
			?>
		</div>
		<?php
		delete_option( 'rael_register_errors_' . $this->get_id() );
	}
	/**
	 * RAE print registration success message.
	 *
	 * @param array   $settings takes settings as an array in argument.
	 * @param boolean $success takes boolean value in argument.
	 */
	protected function rael_print_registration_success_messages( $settings, $success ) {
		if ( $success ) {
			echo '<p class="rael-form-message rael-form-message-valid">' . esc_html( $settings['rael_success_register'] ) . '</p>';
			delete_option( 'rael_register_success_' . $this->get_id() );
			return true;
		}
		return false;
	}
	/**
	 * RAE print necessary hidden fields message.
	 *
	 * @param array  $settings takes settings as an array in argument.
	 * @param string $form_type takes form type as an string in argument.
	 */
	protected function rael_print_necessary_hidden_fields( $settings, $form_type = 'login' ) {
		if ( 'login' === $form_type ) {
			if ( ! empty( $settings['rael_redirect_after_login'] ) && 'yes' === $settings['rael_redirect_after_login'] ) {
				$login_redirect_url = ! empty( $settings['rael_redirect_url']['url'] ) ? esc_url( $settings['rael_redirect_url']['url'] ) : '';
				?>
				<input type="hidden"
						name="redirect_to"
						value="<?php echo esc_attr( $login_redirect_url ); ?>">
				<?php
			}
		}
		// add login/register security nonce.
		wp_nonce_field( "rael-{$form_type}-action", "rael-{$form_type}-nonce" );
		?>
		<input type="hidden"
				name="page_id"
				value="<?php echo esc_attr( $this->page_id ); ?>">
		<input type="hidden"
				name="widget_id"
				value="<?php echo esc_attr( $this->get_id() ); ?>">
		<?php
	}
	/**
	 * RAE print recaptcha check.
	 *
	 * @param array  $settings takes settings as an array in argument.
	 * @param string $form_type takes form type as an string in argument.
	 */
	protected function print_recaptcha_check( $settings, $form_type = 'login' ) {
		if ( 'yes' === $settings[ "rael_enable_{$form_type}_recaptcha" ] ) {
			$id = "{$form_type}-recaptcha-node-" . esc_attr( $this->get_id() );
			echo wp_kses_post(
				"<input type='hidden' name='g_recaptcha_enabled' value='1'/>
			<div id='{$id}' class='rael-recaptcha'></div>"
			);
		}
	}
	/**
	 * RAE password input missing.
	 *
	 * @param string $password_exists parameter password exists.
	 * @param string $confirm_pass_exists parameter confirm password exists.
	 */
	protected function rael_password_input_missing( $password_exists, $confirm_pass_exists ) {
		if ( empty( $password_exists ) ) {
			?>
			<p class='rael-reg-error elementor-alert elementor-alert-warning'>
				<?php
				/* translators: %s: Missing Password Error String */
				printf( esc_html__( 'Error! A Registration form must have a %s field', 'responsive-addons-for-elementor' ), '<strong>Password</strong>' );
				?>
			</p>
			<?php
			return true;
		} elseif ( ! empty( $password_exists ) && empty( $confirm_pass_exists ) ) {
			?>
			<p class='rael-reg-error elementor-alert elementor-alert-warning'>
				<?php
				/* translators: %s: Missing Confirm Password with Password Error String */
				printf( esc_html__( 'Error! You need to use %1$s field along with %2$s field.', 'responsive-addons-for-elementor' ), '<strong>Confirm Password</strong>', '<strong>Password</strong>' );
				?>
			</p>
			<?php
			return true;
		}
		return false;
	}
	/**
	 * RAE email input missing.
	 *
	 * @param string $email_exists parameter email exists.
	 */
	protected function rael_eamil_input_missing( $email_exists ) {
		if ( empty( $email_exists ) ) {
			?>
			<p class='rael-reg-error elementor-alert elementor-alert-warning'>
				<?php
				/* translators: %s: Missing Email Error String */
				printf( esc_html__( 'Error! A Registration form must have an %s field. ', 'responsive-addons-for-elementor' ), '<strong>Email</strong>' );
				?>
			</p>
			<?php
			return true;
		}
		return false;
	}
	/**
	 * RAE username input missing.
	 *
	 * @param string $user_name_exists parameter user name exists.
	 */
	protected function rael_username_input_missing( $user_name_exists ) {
		if ( empty( $user_name_exists ) ) {
			?>
			<p class='rael-reg-error elementor-alert elementor-alert-warning'>
				<?php
				/* translators: %s: Missing Email Error String */
				printf( esc_html__( 'Error! A Registration form must have a %s field. ', 'responsive-addons-for-elementor' ), '<strong>Username</strong>' );
				?>
			</p>
			<?php
			return true;
		}
		return false;
	}
	/**
	 * RAE field repeated error.
	 *
	 * @param string $repeated_form_input_types parameter repeated form input types.
	 */
	protected function rael_field_repeated_error( $repeated_form_input_types ) {
		if ( ! empty( $repeated_form_input_types ) ) {
			$error_field_names = '<strong>' . implode( '</strong>, <strong>', $repeated_form_input_types ) . '</strong>';
			?>
			<p class='rael-reg-error elementor-alert elementor-alert-warning'>
				<?php
				if ( 2 <= count( $repeated_form_input_types ) ) {
					/* translators: %s: Repeated Error Field Names */
					printf( esc_html__( 'Oops! It looks like you have added a few extra fields in your form, namely %s.', 'responsive-addons-for-elementor' ), esc_html( $error_field_names ) );
				} else {
					/* translators: %s: Repeated Error Field Names */
					printf( esc_html__( 'Oops! It looks like you have added an extra %s field in your form.', 'responsive-addons-for-elementor' ), esc_html( $error_field_names ) );
				}
				?>
			</p>
			<?php
			return true;
		}

		return false;
	}
	/**
	 * RAE print recaptcha check.
	 *
	 * @param array $settings takes settings as an array in argument.
	 */
	protected function print_terms_condition_notice( $settings ) {
		if ( 'yes' !== $settings['rael_show_terms_conditions'] ) {
			echo '<input type="hidden" id="no-tc-btn" name="no_terms_and_conditions_check" value="nonexistant">';
			return;
		}
		$tc_label_data = esc_html( $settings['acceptance_label_rael'] );
		$tc_label      = explode( "\n", $tc_label_data )[0];
		$tc_link_text  = explode( "\n", $tc_label_data )[1];
		$source        = $settings['rael_acceptance_text_source'];
		$tc_text       = isset( $settings['rael_acceptance_text'] ) ? $settings['rael_acceptance_text'] : '';
		$tc_link       = '';

		if ( 'default' === $source ) {
			$tc_link = sprintf( '<a href="%1$s" id="reg-tc" class="rael-reg-tc" target="_blank">%2$s</a>', esc_url( get_the_permalink( get_option( 'wp_page_for_privacy_policy' ) ) ), $tc_link_text );
		}
		if ( 'custom_link' === $source ) {
			$tc_url_settings = $settings['rael_acceptance_text_url'];
			$tc_url          = ! empty( $tc_url_settings['url'] ) ? esc_url( $tc_url_settings['url'] ) : '';
			$tc_attributes   = ! empty( $tc_url_settings['is_external'] ) ? ' target="_blank"' : '';
			$tc_attributes  .= ! empty( $tc_url_settings['nofollow'] ) ? ' rel="nofollow"' : '';
			$tc_link         = sprintf( '<a href="%1$s" id="reg-tc" class="rael-reg-tc" %2$s>%3$s</a>', esc_attr( $tc_url ), $tc_attributes, $tc_link_text );
		}
		if ( 'editor' === $source ) {
			$acceptance_text = $settings['rael_acceptance_text'];
			?>
			<div class="rael-acceptance-text">
				<p><?php echo esc_html( $acceptance_text ); ?></p>
			</div>
		<?php } ?>
		<div class="rael-tc">
			<label class="rael-switch">
				<input type="checkbox" id="tc-btn" name="terms_and_conditions_check" value="checked">
				<div class="rael-tc-slider" id="rael-tc-slider"></div>
			</label>
			<span class="rael-register-label">
				<?php
				if ( 'custom_link' === $source || 'default' === $source ) {
					echo esc_html( $tc_label ), wp_kses_post( $tc_link );
				} else {
					echo esc_html( $tc_label_data );
				}
				?>
			</span>
		</div>
		<?php
	}
	/**
	 * RAE print recaptcha check.
	 *
	 * @param array $settings takes settings as an array in argument.
	 */
	protected function print_form_image( $settings ) {
		if ( ! empty( $this->form_image_url ) ) {
			?>
			<style type="text/css">
				.rael-form-image {
					width: 50%;
					background-repeat: no-repeat;
					background-position: center;
				}
				.rael-login-wrapper, .rael-register-wrapper {
					box-shadow: 0 0 37.5px 14px #66666622;
				}
			</style>
			<div class="rael-form-image"
				style="background-image: url(<?php echo esc_url( $this->form_image_url ); ?>);">
			</div>
			<?php
		}
	}
	/**
	 * RAE print recaptcha check.
	 *
	 * @param array  $settings takes settings as an array in argument.
	 * @param string $form_type takes form type as an string in argument.
	 */
	protected function print_form_header( $settings, $form_type ) {
		$title    = esc_html( $settings[ 'rael_' . $form_type . '_form_title' ] );
		$subtitle = esc_html( $settings[ 'rael_' . $form_type . '_form_subtitle' ] );

		if ( ! empty( $this->logo_url ) || ! empty( $title ) || ! empty( $subtitle ) ) {
			?>
			<div class="rael-header-content">
				<?php if ( ! empty( $this->logo_url ) ) { ?>
					<img class="rael-header-logo" src="<?php echo esc_url( $this->logo_url ); ?>" alt="<?php esc_attr_e( 'Form Logo Image', 'responsive-addons-for-elementor' ); ?>">
				<?php } ?>
				<?php if ( ! empty( $title ) || ! empty( $subtitle ) ) { ?>
					<div class="rael-header-text">
						<?php
						if ( ! empty( $title ) ) {
							echo '<h3 class="rael-header-title">' . esc_html( $title ) . '</h3>';
						}

						if ( ! empty( $subtitle ) ) {
							echo '<span class="rael-header-subtitle">' . esc_html( $subtitle ) . '</span>';
						}
						?>
					</div>
				<?php } ?>
			</div>
			<?php
		}
	}
	/**
	 * RAE print recaptcha check.
	 *
	 * @param array $settings takes settings as an array in argument.
	 */
	protected function rael_print_styles( $settings ) {
		if ( empty( $this->form_image_url ) || '' === $settings['rael_form_image_position'] ) {
			?>
			<style type="text/css">
				.rael-login-form-container, .rael-register-form-container {
					max-width: 30em;
					width: 70%;
				}
			</style>
			<?php
		}
	}

	/**
	 * It will apply value like Elementor's dimension control to a property and return it.
	 *
	 * @param string $property_name CSS property name.
	 *
	 * @return string
	 */
	public function rael_apply_dim( $property_name ) {
		return "{$property_name}: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};";
	}

	/**
	 * Get Custom help URL
	 *
	 * @return string help URL
	 */
	public function get_custom_help_url() {
		return 'https://cyberchimps.com/docs/widgets/login-register';
	}
}
