(function ($) {
	"use strict";

	function rael_hf_hide_meta_fields () {
		var selected = $( '#rael_hf_template_type' ).val() || 'none';
		$( '.rael-hf__meta-options-table' ).removeClass().addClass( 'rael-hf__meta-options-table widefat rael-hf-selected-template-type-' + selected );
	};

	$(document).on('change', '#rael_hf_template_type', () => rael_hf_hide_meta_fields() );

	rael_hf_hide_meta_fields();

	let selectElement = $('select[name="rael-hf-include-locations[rule][0]"]');
	let option = selectElement.find('option[value="basic-global"]');

	let templateType = $('#rael_hf_template_type').val();
	if ( templateType != 'header' || templateType != 'footer' ) {
		option.remove();
	}

	$('#rael_hf_template_type').on('change', function () {
		let selectElement = $('select[name="rael-hf-include-locations[rule][0]"]');
		let option = selectElement.find('option[value="basic-global"]');
		let basicOptgroup = selectElement.find('optgroup[label="Basic"]');
		if ($(this).val() != 'header' && $(this).val() != 'footer') {
			option.remove();
		} else {
			// Check if the option is not present and add it inside the "Basic" optgroup
			if (option.length === 0) {
				basicOptgroup.prepend('<option value="basic-global">Entire Website</option>');
			}
		}
	});

})(jQuery);
