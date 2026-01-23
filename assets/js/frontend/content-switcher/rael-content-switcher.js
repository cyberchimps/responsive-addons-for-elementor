var RAELWidgetContentSwitcherHandler = function( $scope, $ ) {

    if ( 'undefined' == typeof $scope ) {
        return;
    }

    var id                 = window.location.hash.substring( 1 );
    var pattern            = new RegExp( "^[\\w\\-]+$" );
	var sanitize_input     = pattern.test( id );
    var rael_ct_section_1   = $scope.find( ".rael-ct__section-1" );
    var rael_ct_section_2   = $scope.find( ".rael-ct__section-2" );
    var rael_ct_btn         = $scope.find( ".rael-ct__switcher-button" );
    var switch_type        = rael_ct_btn.attr( 'data-switch-type' );
    var rael_ct_label_1     = $scope.find( ".rael-ct__section-heading-1" );
    var rael_ct_label_2     = $scope.find( ".rael-ct__section-heading-2" );
    var current_class;

    switch ( switch_type ) {
        case 'round_1':
            current_class = '.rael-ct__switcher--round-1';
            break;
        case 'round_2':
            current_class = '.rael-ct__switcher--round-2';
            break;
        case 'rectangle':
            current_class = '.rael-ct__switch-rectangle';
            break;
        case 'label_box':
            current_class = '.rael-ct__switch-label-box';
            break;
        default:
            current_class = 'No Class Selected';
            break;
    }
    var rael_switch = $scope.find( current_class );
    
    if( '' !== id && sanitize_input ){
        if ( id === 'content-1' || id === 'content-2' ) {
            RAELContentSwitcher._openOnLink( $scope, rael_switch );
        }			
    }		
    
    if( rael_switch.is( ':checked' ) ) {
        rael_ct_section_1.hide();
        rael_ct_section_2.show();
    } else {
        rael_ct_section_1.show();
        rael_ct_section_2.hide();
    }

    rael_switch.on('click', function(e){
        rael_ct_section_1.toggle();
        rael_ct_section_2.toggle();
    });

    /* Label 1 Click */
    rael_ct_label_1.on('click', function(e){
        // Uncheck
        rael_switch.prop("checked", false);
        rael_ct_section_1.show();
        rael_ct_section_2.hide();

    });

    /* Label 2 Click */
    rael_ct_label_2.on('click', function(e){
        // Check
        rael_switch.prop("checked", true);
        rael_ct_section_1.hide();
        rael_ct_section_2.show();
    });
};

var RAELContentSwitcher = {
    /**
     * Open specific section on click of link
     *
     */

    _openOnLink: function( $scope, rael_switch ){

        var node_id 		= $scope.data( 'id' );
        var rael_ct_btn        = $scope.find( ".rael-ct__switcher-button" );
        var switch_type     = rael_ct_btn.attr( 'data-switch-type' );
        var node          	= '.elementor-element-' + node_id;
        var node_toggle     = '#rael-toggle-init' + node;			

        $( 'html, body' ).animate( {
            scrollTop: $( '#rael-toggle-init' ).find( '.rael-ct-wrapper' ).offset().top
        }, 500 );

        if( id === 'content-1' ) {
            
            $( node_toggle +' .rael-ct__content-1' ).show();
            $( node_toggle +' .rael-ct__content-2' ).hide();
            rael_switch.prop( "checked", false );
        } else {
            
            $( node_toggle +' .rael-ct__content-2' ).show();
            $( node_toggle +' .rael-ct__content-1' ).hide();
            rael_switch.prop( "checked", true );
        }			
    },	
}

jQuery(window).on( 'elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction( 'frontend/element_ready/rael-content-switcher.default', RAELWidgetContentSwitcherHandler );
});