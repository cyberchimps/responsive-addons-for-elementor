var RAEL_Login_Register = function( $scope, $ ) {

    let current_form =  $("#rael-login-registration-container").attr('form');
    let register_form = $("#rael-register-wrapper");
    let reg_errors =  $("#possible-reg-errors");
    let login_form = $("#rael-login-wrapper"); 
    if('login' === current_form) {
        login_form.css("display", "flex");
        register_form.css("display", "none");
        reg_errors.css("display", "none");
    } else {
        register_form.css("display", "flex");
        reg_errors.css("display", "block");
        login_form.css("display", "none");
    }
    
    $(document).on('click', '#rael-tc-slider', function (e) {
        e.preventDefault();
        let tc_check = $("#tc-btn").is(':checked');
        if(tc_check) {
            $("#tc-btn").attr('value', ''); // Unchecks the box
            $("#tc-btn").attr('checked', false); // Unchecks the box
        } else {
            $("#tc-btn").attr('value', 'checked');  // Checks the box
            $("#tc-btn").attr('checked', true);  // Checks the box
        }
    });

    $(document).on('click', '#rael-login-reg-link-form', function (e) {
        e.preventDefault();
        register_form.css("display", "flex");
        reg_errors.css("display", "block");
        login_form.css("display", "none");
    });

    $(document).on('click', '#rael-reg-login-link-form', function (e) {
        e.preventDefault();
        login_form.css("display", "flex");
        register_form.css("display", "none");
        reg_errors.css("display", "none");
    });

    // Login form password visibility toggle
    var login_pass_shown = false;
    $(document).on('click', '#rael-login-pass-toggle', function (e) {
        var $icon = $scope.find('#rael-login-pass-toggle-icon'); 
        var $passField = $scope.find('#rael-login-password-input');

        if (login_pass_shown) {
            $passField.attr('type', 'password');
            $icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
            login_pass_shown = false;
        } else {
            $passField.attr('type', 'text');
            $icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
            login_pass_shown = true;
        }
    });

    // Register form password visibility toggle
    var reg_pass_shown = false;
    $(document).on('click', '#rael-reg-pass-toggle', function (e) {
        var $icon = $scope.find('#rael-reg-pass-toggle-icon'); 
        var $passField = $scope.find('#reg-field-password');

        if (reg_pass_shown) {
            $passField.attr('type', 'password');
            $icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
            reg_pass_shown = false;
        } else {
            $passField.attr('type', 'text');
            $icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
            reg_pass_shown = true;
        }
    });

    $('#reg-field-confirm_pass, #reg-field-password').keyup(() => {
        let password = $('#reg-field-password').val();
        let confirm_password = $('#reg-field-confirm_pass').val();
        let reg_button = $('#rael-register-submit');
        let error_text = $('#confirm-pass-error');
        let valid_class = "rael-valid";

        if(confirm_password === password && confirm_password !== '' && password !== '') {
            reg_button.attr('disabled', false);
            reg_button.removeClass('rael-disabled');
            error_text.addClass(valid_class);
            error_text.children('#valid-icon').removeClass('fa-times-circle').addClass('fa-check-circle');
        } else if (error_text.hasClass(valid_class)) {
            reg_button.attr('disabled', true);
            reg_button.addClass('rael-disabled');
            error_text.removeClass(valid_class);
            error_text.children('#valid-icon').removeClass('fa-check-circle').addClass('fa-times-circle');
        } else {
            reg_button.attr('disabled', true);
            reg_button.addClass('rael-disabled');
        }
    });

    $('#reg-field-password').keyup(() => {
        let password = $('#reg-field-password').val();
        let valid_class = "rael-valid";

        // Selectors for error text elements
        let eight_char = $('#eight-char');

        // Valid only if password is greater than 8 characters
        if(password.length >= 8) {
            eight_char.addClass(valid_class);
            eight_char.children('#valid-icon').removeClass('fa-times-circle').addClass('fa-check-circle');
        } else if (eight_char.hasClass(valid_class)) {
            eight_char.removeClass(valid_class);
            eight_char.children('#valid-icon').removeClass('fa-check-circle').addClass('fa-times-circle');
        }

        add_remove_valid('one_digit');
        add_remove_valid('one_lower_char');
        add_remove_valid('one_upper_char');
        add_remove_valid('one_special_char');

        function add_remove_valid(selector) {
            let element, regex;
            if('one_digit' === selector) {
                element = $('#one-digit');
                regex = new RegExp('[0-9]');
            } else if ('one_lower_char' === selector) {
                element = $('#one-lower-char');
                regex = new RegExp('[a-z]');
            } else if ('one_upper_char' === selector) {
                element = $('#one-upper-char');
                regex = new RegExp('[A-Z]');
            } else {
                element = $('#one-special-char');
                regex = new RegExp('[!_@#$%^&-+=()]');
            }

            if(regex.test(password)) {
                element.addClass(valid_class);
                element.children('#valid-icon').removeClass('fa-times-circle').addClass('fa-check-circle');
            } else if (element.hasClass(valid_class)) {
                element.removeClass(valid_class);
                element.children('#valid-icon').removeClass('fa-check-circle').addClass('fa-times-circle');
            }
        }
    });

    var container = $scope.find('.rael-login-registration-container'); 
    var widgetId = container.data('widget-id');
    var recaptchaSiteKey = container.data('recaptcha-sitekey');

    // Login form captcha details
    // Using above variable login_form to target login form div
    var login_captcha_theme = login_form.data('recaptcha-theme');   
    var login_captcha_size = login_form.data('recaptcha-size');
  	
    // Register form captcha details
    // Using above variable register_form to target register form div
    var reg_captcha_theme = register_form.data('recaptcha-theme');
    var reg_captcha_size = register_form.data('recaptcha-size');
    
    var isEditMode = elementorFrontend.isEditMode();
    var recaptchaAvailable = typeof grecaptcha !== 'undefined' && grecaptcha !== null;
    // reCAPTCHA    
    var recaptchaCallback = function() {
        var loginRecaptchaNode = login_form.find("#login-recaptcha-node-" + widgetId)[0];
        var registerRecaptchaNode = register_form.find("#register-recaptcha-node-" + widgetId)[0];
        if (typeof grecaptcha.render != "function") {
            return false;      
        }      
        if (loginRecaptchaNode) {
            grecaptcha.render(loginRecaptchaNode, {
                'sitekey': recaptchaSiteKey,
                'theme': login_captcha_theme,
                'size': login_captcha_size
            });      
        }
        if (registerRecaptchaNode) {
            grecaptcha.render(registerRecaptchaNode, {          
                'sitekey': recaptchaSiteKey,          
                'theme': reg_captcha_theme,          
                'size': reg_captcha_size
            });      
        }    
    }
    if (recaptchaAvailable && isEditMode) {
        recaptchaCallback();
    } else {
        $(window).on('load', function () {          
           if (recaptchaAvailable) {            
                recaptchaCallback();          
            }        
        });      
    }
}

jQuery(window).on( 'elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction( 'frontend/element_ready/rael-login-register.default', RAEL_Login_Register );
});