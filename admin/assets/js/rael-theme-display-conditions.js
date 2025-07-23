(function ( $ ) {

	var init_display_conditions  = function( selector ) {
		
		$(selector).select2({

			placeholder: rael_display_conditions.search,

			ajax: {
			    url: ajaxurl,
			    dataType: 'json',
			    method: 'post',
			    delay: 250,
			    data: function (params) {
			      	return {
			        	q: params.term, // search term
				        page: params.page,
						action: 'rael_hfe_get_posts_by_query',
						nonce: rael_display_conditions.ajax_nonce
			    	};
				},
				processResults: function (data) {
		            return {
		                results: data
		            };
		        },
			    cache: true
			},
			minimumInputLength: 2,
			language: rael_display_conditions.rael_lang
		});
	};

	var update_display_conditions_input = function(wrapper) {
		var new_value = [];
		
		wrapper.find('.rael-hf__display-condition').each(function(i) {
			
			var $this 			= $(this);
			var temp_obj 		= {};
			var rule_condition 	= $this.find('select.rael-hf__display-condition-input');
			var specific_page 	= $this.find('select.rael-hf__display-condition-specific-page');

			var rule_condition_val 	= rule_condition.val();
			var specific_page_val 	= specific_page.val();
			
			if ( '' != rule_condition_val ) {

				temp_obj = {
					type 	: rule_condition_val,
					specific: specific_page_val
				} 
				
				new_value.push( temp_obj );
			};
		})
	};

	var update_close_button = function(wrapper) {

		type 		= wrapper.closest('.rael-hf__display-condition-container').attr('data-type');
		rules 		= wrapper.find('.rael-hf__display-condition');
		show_close	= false;

		if ( 'display' == type ) {
			if ( rules.length > 1 ) {
				show_close = true;
			}
		}else{
			show_close = true;
		}

		rules.each(function() {
			if ( show_close ) {
				jQuery(this).find('.rael-hf__display-condition-delete').removeClass('rael-hf__element-hidden');
			}else{
				jQuery(this).find('.rael-hf__display-condition-delete').addClass('rael-hf__element-hidden');
			}
		});
	};

	var update_exclusion_button = function( force_show, force_hide ) {
		var display_on = $('.rael-hf__display-condition-display-on-wrap');
		var exclude_on = $('.rael-hf__display-condition-exclude-on-wrap');
		
		var exclude_field_wrap = exclude_on.closest('tr');
		var add_exclude_block  = display_on.find('.rael-hf__add-exclude-display-condition-wrapper');
		var exclude_conditions = exclude_on.find('.rael-hf__display-condition');
		
		if ( true == force_hide ) {
			exclude_field_wrap.addClass( 'rael-hf__element-hidden' );
			add_exclude_block.removeClass( 'rael-hf__element-hidden' );
		}else if( true == force_show ){
			exclude_field_wrap.removeClass( 'rael-hf__element-hidden' );
			add_exclude_block.addClass( 'rael-hf__element-hidden' );
		}else{
			
			if ( 1 == exclude_conditions.length && '' == $(exclude_conditions[0]).find('select.rael-hf__display-condition-input').val() ) {
				exclude_field_wrap.addClass( 'rael-hf__element-hidden' );
				add_exclude_block.removeClass( 'rael-hf__element-hidden' );
			}else{
				exclude_field_wrap.removeClass( 'rael-hf__element-hidden' );
				add_exclude_block.addClass( 'rael-hf__element-hidden' );
			}
		}

	};

	$(document).ready(function($) {

		jQuery( '.rael-hf__display-condition' ).each( function() {
			var $this 			= $( this ),
				condition 		= $this.find('select.rael-hf__display-condition-input'),
				condition_val 	= condition.val(),
				specific_page 	= $this.next( '.rael-hf__display-condition-specific-page-wrapper' );

			if( 'specifics' == condition_val ) {
				specific_page.slideDown( 300 );
			}
		} );

		
		jQuery('select.rael-hf__display-condition-select2').each(function(index, el) {
			init_display_conditions( el );
		});

		jQuery('.rael-hf__display-condition-container').each(function() {
			update_close_button( jQuery(this) );
		})

		/* Show hide exclusion button */
		update_exclusion_button();

		jQuery( document ).on( 'change', '.rael-hf__display-condition select.rael-hf__display-condition-input' , function( e ) {
			
			var $this 		= jQuery(this),
				this_val 	= $this.val(),
				field_wrap 	= $this.closest('.rael-hf__display-condition-container');

			if( 'specifics' == this_val ) {
				$this.closest( '.rael-hf__display-condition' ).next( '.rael-hf__display-condition-specific-page-wrapper' ).slideDown( 300 );
			} else {
				$this.closest( '.rael-hf__display-condition' ).next( '.rael-hf__display-condition-specific-page-wrapper' ).slideUp( 300 );
			}

			update_display_conditions_input( field_wrap );
		} );

		jQuery( '.rael-hf__display-condition-container' ).on( 'change', '.rael-hf__display-condition-select2', function(e) {
			var $this 		= jQuery( this ),
				field_wrap 	= $this.closest('.rael-hf__display-condition-container');

			update_display_conditions_input( field_wrap );
		});
		
		jQuery( '.rael-hf__display-condition-container' ).on( 'click', '.rael-hf__add-include-display-condition-wrapper a', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var $this 	= jQuery( this ),
				id 		= $this.attr( 'data-rule-id' ),
				new_id 	= parseInt(id) + 1,
				type 	= $this.attr( 'data-rule-type' ),
				rule_wrap = $this.closest('.rael-hf__display-condition-container').find('.rael-hf__display-condition-builder-wrapper'),
				template  = wp.template( 'rael-hf-display-conditions-' + type + '-condition' ),
				field_wrap 		= $this.closest('.rael-hf__display-condition-container');

			rule_wrap.append( template( { id : new_id, type : type } ) );
			
			init_display_conditions( '.rael-hf-display-condition-'+type+'-on .rael-hf__display-condition-select2' );
			
			$this.attr( 'data-rule-id', new_id );

			update_close_button( field_wrap );
			filterDisplayConditions($('#rael_hf_template_type').val());
		});

		jQuery( '.rael-hf__display-condition-container' ).on( 'click', '.rael-hf__display-condition-delete', function(e) {
			var $this 			= jQuery( this ),
				rule_condition 	= $this.closest('.rael-hf__display-condition'),
				field_wrap 		= $this.closest('.rael-hf__display-condition-container');
				cnt 			= 0,
				data_type 		= field_wrap.attr( 'data-type' ),
				optionVal 		= $this.siblings('.rael-hf__display-condition-wrapper').children('.rael-hf__display-condition-input').val();

			if ( 'exclude' == data_type ) {
					
				if ( 1 === field_wrap.find('.rael-hf__display-condition-input').length ) {

					field_wrap.find('.rael-hf__display-condition-input').val('');
					field_wrap.find('.rael-hf__display-condition-specific-page').val('');
					field_wrap.find('.rael-hf__display-condition-input').trigger('change');
					update_exclusion_button( false, true );

				}else{
					$this.parent('.rael-hf__display-condition').next('.rael-hf__display-condition-specific-page-wrapper').remove();
					rule_condition.remove();
				}

			} else {

				$this.parent('.rael-hf__display-condition').next('.rael-hf__display-condition-specific-page-wrapper').remove();
				rule_condition.remove();
			}

			field_wrap.find('.rael-hf__display-condition').each(function(i) {
				var condition       = jQuery( this ),
					old_rule_id     = condition.attr('data-rule'),
					select_location = condition.find('.rael-hf__display-condition-input'),
					select_specific = condition.find('.rael-hf__display-condition-specific-page'),
					location_name   = select_location.attr( 'name' );
					
				condition.attr( 'data-rule', i );

				select_location.attr( 'name', location_name.replace('['+old_rule_id+']', '['+i+']') );
				
				condition.removeClass('rael-hf__display-condition-'+old_rule_id).addClass('rael-hf__display-condition-'+i);

				cnt = i;
			});

			field_wrap.find('.rael-hf__add-include-display-condition-wrapper a').attr( 'data-rule-id', cnt )

			update_close_button( field_wrap );
			update_display_conditions_input( field_wrap );
		});
		
		jQuery( '.rael-hf__display-condition-container' ).on( 'click', '.rael-hf__add-exclude-display-condition-wrapper a', function(e) {
			e.preventDefault();
			e.stopPropagation();
			update_exclusion_button( true );
		});

		// Handle the template type change
		// This is used to filter the display conditions based on the selected template type
		// It will show or hide the options based on the template type selected
		const selectedTemplateType = $('#rael_hf_template_type').val();

		$('#rael_hf_template_type').on('change', function () {
			$('select.rael-hf__display-condition-input').val('');
			const selected = $(this).val();
				filterDisplayConditions(selected);
		});

		function filterDisplayConditions(templateType) {
			jQuery('select.rael-hf__display-condition-input').each(function () {
				const $select = jQuery(this);

				$select.find('optgroup').each(function () {
					const $optgrp = jQuery(this);
					const types = ($optgrp.data('template-types') || '').toString().split(',');
					if (types.includes(templateType) || types.includes('all')) {
						$optgrp.show();
					} else {
						$optgrp.hide();
					}
				});
				$select.find('option').each(function () {
					const $opt = jQuery(this);
					const types = ($opt.data('template-types') || '').toString().split(',');
					if (types.includes(templateType) || types.includes('all')) {
						$opt.show();
					} else {
						$opt.hide();
					}
				});
			});
		}

		// On page load
		filterDisplayConditions(selectedTemplateType);
		
	});

}(jQuery));
