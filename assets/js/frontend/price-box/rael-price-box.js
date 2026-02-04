import tippy from 'tippy.js';

var RAELWidgetPriceBoxHandler = function( $scope, $ ) {

    if ( 'undefined' == typeof $scope ) {
        return;
    }

    var id 				  = $scope.data( 'id' );
    var $this 			  = $scope.find( '.rael-price-box__features-list' );
    var side			  = $this.data( 'side' );
    var trigger			  = $this.data( 'hotspottrigger' ) == 'hover' ? 'mouseenter' : $this.data( 'hotspottrigger' );
    var arrow			  = $this.data( 'arrow' );
    var distance		  = $this.data( 'distance' );
    var delay 			  = $this.data( 'delay' );
    var anim_duration 	  = $this.data( 'animduration' );
    var zindex			  = $this.data( 'zindex' );	
    var tooltip_maxwidth  = $this.data( 'tooltip-maxwidth' );
    var enable_tooltip    = $this.data( 'enable-tooltip' );
    var animation         = $this.data( 'animation' );

    var options = {
        allowHTML: true,
        maxWidth: tooltip_maxwidth,
        interactive: true,
        arrow: arrow,
        animation: animation,
        duration: anim_duration,
        delay: delay,
        offset: [0, distance],
        placement: side,
        zIndex: zindex,
        trigger: trigger
    };

    if( 'yes' === enable_tooltip ){
        // Execute Tippy function
        var selector = '.rael-price-box__features-list [data-tippy-content]';
        tippy( selector, options);
    }
}

jQuery(window).on( 'elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction( 'frontend/element_ready/rael_price_box.default', RAELWidgetPriceBoxHandler );
});