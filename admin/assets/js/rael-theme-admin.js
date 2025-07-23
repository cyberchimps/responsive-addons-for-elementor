(function ($) {
	"use strict";

	function rael_hf_hide_meta_fields () {
		var selected = $( '#rael_hf_template_type' ).val() || 'none';
		$( '.rael-hf__meta-options-table' ).removeClass().addClass( 'rael-hf__meta-options-table widefat rael-hf-selected-template-type-' + selected );
	};

	$(document).on('change', '#rael_hf_template_type', () => rael_hf_hide_meta_fields() );

	rael_hf_hide_meta_fields();

})(jQuery);
