/**
 * File responsibe for editor js
 *
 * @package responsive-addons-for-elementor
 */

/**
 * Initialize all modules
 */

(function ($, window, document, undefined) {

	$( window ).on(
		'elementor:init',
		function () {

			var RaelControlSelect2 = elementor.modules.controls.Select2;

			// ControlSelect2View prototype.
			var RaelControlSelect2View = RaelControlSelect2.extend(
				{
					getSelect2Options: function () {
						return {
							dir: elementor.config.is_rtl ? 'rtl' : 'ltr'
						};
					},

					templateHelpers: function () {
						var helpers = RaelControlSelect2View.prototype.templateHelpers.apply( this, arguments ),
						fonts       = this.model.get( 'options' );

						helpers.getFontsByGroups = function ( groups ) {
							var filteredFonts = {};

							_.each(
								fonts,
								function ( fontType, fontName ) {
									if (_.isArray( groups ) && _.contains( groups, fontType ) || fontType === groups ) {
										filteredFonts[ fontName ] = fontName;
									}
								}
							);

							return filteredFonts;
						};

						console.log( helpers );

						return helpers;
					}
				}
			);

			var ControlQueryPostsItemView = RaelControlSelect2.extend( {
				cache: null,
	
				isTitlesReceived: false,
	
				getSelect2Placeholder: function getSelect2Placeholder() {
					return {
						id: '',
						text: 'All'
					};
				},
	
				getControlValueByName: function getControlValueByName(controlName) {
					var name = this.model.get('group_prefix') + controlName;
					return this.elementSettingsModel.attributes[name];
				},
	
				getSelect2DefaultOptions: function getSelect2DefaultOptions() {
					var self = this;
	
					return jQuery.extend(RaelControlSelect2.prototype.getSelect2DefaultOptions.apply(this, arguments), {
						ajax: {
							transport: function transport(params, success, failure) {
								var data = {
									q: params.data.q,
									filter_type: self.model.get('filter_type'),
									object_type: self.model.get('object_type'),
									include_type: self.model.get('include_type'),
									query: self.model.get('query')
								};
	
								if ('cpt_taxonomies' === data.filter_type) {
									data.query = {
										post_type: self.getControlValueByName('post_type')
									};
								}
	
								return elementorCommon.ajax.addRequest('panel_posts_control_filter_autocomplete', {
									data: data,
									success: success,
									error: failure
								});
							},
							data: function data(params) {
								return {
									q: params.term,
									page: params.page
								};
							},
							cache: true
						},
						escapeMarkup: function escapeMarkup(markup) {
							return markup;
						},
						minimumInputLength: 1
					});
				},
	
				getValueTitles: function getValueTitles() {
					var self = this,
						ids = this.getControlValue(),
						filterType = this.model.get('filter_type');
	
					if (!ids || !filterType) {
						return;
					}
	
					if (!_.isArray(ids)) {
						ids = [ids];
					}
	
					elementorCommon.ajax.loadObjects({
						action: 'query_control_value_titles',
						ids: ids,
						data: {
							filter_type: filterType,
							object_type: self.model.get('object_type'),
							include_type: self.model.get('include_type'),
							unique_id: '' + self.cid + filterType,
							query: self.model.get('query')
						},
						before: function before() {
							self.addControlSpinner();
						},
						success: function success(data) {
							self.isTitlesReceived = true;
	
							self.model.set('options', data);
	
							self.render();
						}
					});
				},
	
				addControlSpinner: function addControlSpinner() {
					this.ui.select.prop('disabled', true);
					this.$el.find('.elementor-control-title').after('<span class="elementor-control-spinner">&nbsp;<i class="fa fa-spinner fa-spin"></i>&nbsp;</span>');
				},
	
				onReady: function onReady() {
					// Safari takes it's time to get the original select width
					setTimeout(RaelControlSelect2.prototype.onReady.bind(this));
	
					this.getSelect2DefaultOptions();
	
					if (!this.isTitlesReceived) {
						this.getValueTitles();
					}
				}
			} );
	
			var SingleQueryControlView = ControlQueryPostsItemView.extend({
				getQueryData: function() {
					// Use a clone to keep model data unchanged:
					var autocomplete = elementorCommon.helpers.cloneObject(this.model.get('autocomplete'));
	
					if (_.isEmpty(autocomplete.query)) {
					autocomplete.query = {};
					} // Specific for Group_Control_Query
	
	
					if ('cpt_tax' === autocomplete.object) {
						autocomplete.object = 'tax';
	
						if (_.isEmpty(autocomplete.query) || _.isEmpty(autocomplete.query.post_type)) {
							autocomplete.query.post_type = this.getControlValueByName('post_type');
						}
					}
	
					return {
						autocomplete: autocomplete
					};
				},
	
				getSelect2DefaultOptions: function() {
					var self = this;
	
					return jQuery.extend(RaelControlSelect2.prototype.getSelect2DefaultOptions.apply(this, arguments), {
						ajax: {
							transport: function(params, success, failure) {
								var data = self.getQueryData(),
									action = 'rael_single_query_panel_posts_control_filter_autocomplete';
								data.q = params.data.q;
	
								return elementorCommon.ajax.addRequest(action, {
									data: data,
									success: success,
									error: failure
								});
							},
							data: function(params) {
								return {
									q: params.term,
									page: params.page,
								};
							},
							cache: true
						},
						escapeMarkup: function(markup) {
							return markup;
						},
						minimumInputLength: 1,
					});
				},
	
				getValueTitles: function() {
					var self = this,
						data = {};
	
					var ids = this.getControlValue(),
						action = 'rael_single_query_control_value_titles',
						filterTypeName = 'autocomplete',
						filterType = {};
	
					if (!ids || !filterType) {
						return;
					}
	
					if (!_.isArray(ids)) {
						ids = [ids];
					}
					
					filterType = this.model.get(filterTypeName).object;
					data.get_titles = self.getQueryData().autocomplete;
					data.unique_id = '' + self.cid + filterType;
	
					elementorCommon.ajax.loadObjects({
						action: action,
						ids: ids,
						data: data,
						before: function () {
							self.addControlSpinner();
						},
						success: function (response) {
							self.isTitlesReceived = true;
							self.model.set('options', response);
							self.render();
						}
					});
				},
	
				onReady: function() {
					// Safari takes it's time to get the original select width
					setTimeout(RaelControlSelect2.prototype.onReady.bind(this));
	
					if (!this.isTitlesReceived) {
						this.getValueTitles();
					}
				}
			});

			elementor.addControlView( 'rael-query', ControlQueryPostsItemView );
        	elementor.addControlView( 'rael-single-query', SingleQueryControlView )

		}
	);

})( jQuery, window, document );
