var $ = jQuery.noConflict()
$(document).ready(function () {
    // Add Icon for Theme Builder under Elementor Addons Submenu.
    var targetAnchor = $('li#toplevel_page_rael_getting_started .wp-submenu.wp-submenu-wrap li a:contains("Theme Builder")');
    targetAnchor.before('<span class="responsive-theme-builder-icon dashicons dashicons-editor-break"></span>').parent().css({
        'display': 'flex',
        'margin-left': '12px'
    });
    targetAnchor.hover(function () {
        $(this).css('box-shadow', 'none');
    });

    var migrateAnchor = $('.wp-submenu.wp-submenu-wrap li a:contains("RAE Migration")');
    migrateAnchor.hide();
})