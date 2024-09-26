jQuery(document).ready(function ($) {

    $('#rael_migration_notice_button').on('click', function() {
		$('.rael-consent-popup-form-wrapper-outer').show();
    });
	
	$('#rael-consent-popup-form-checkbox').on('click', function() {
		if( $(this).is(":checked") ) {
			$('#rael-consent-popup-form-migrate').attr('disabled', false);
            $('#rael-consent-popup-form-migrate').css('background-color', '#428A58');
            $('#rael-consent-popup-form-migrate').css('border-color', '#428A58');
            $('#rael-consent-popup-form-migrate').css('color', '#FFFFFF');
		} else {
			$('#rael-consent-popup-form-migrate').attr('disabled', true);
            $('#rael-consent-popup-form-migrate').css('background-color', '');
            $('#rael-consent-popup-form-migrate').css('border-color', '');
            $('#rael-consent-popup-form-migrate').css('color', '');
		}
	});
	
	$('.rael-consent-popup-form-close-btn').on('click', function() {
		$('.rael-consent-popup-form-wrapper-outer').hide();
	});

    $('#rael-consent-popup-form-migrate').on('click', function() {
        let nonce = $(this).data('nonce');

        $('.rael-consent-popup-form-close-btn').hide();
        $(this).addClass('updating-message');
        $(this).text('MIGRATING...');

        $.ajax(
            {
                type: 'POST',
                url: localize.ajaxurl,
                data:
                {
                    action: 'rael_rea_to_rae_migration',
                    _nonce: nonce,
                },
                success: function success( data )
                {
                    console.log(data.data?.response_type)
                    if ( data.data?.response_type && 'success' === data.data?.response_type ) {
                        $('.rael-migration-pending').hide();
                        $('.rael-migration-complete').show();
                        $('.rael-consent-popup-form-wrapper-outer').hide()
                        $('.rael-migration-complete').show()
                        $('.rael-activated').show()
                    }
                },
                error: function error( data )
                {
                    console.log(data.data?.response_type)
                }
            }
        );
    });
});