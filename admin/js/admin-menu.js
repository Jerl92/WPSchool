
(function ( $ ) {
    // Close all the other parent menus
    $('.wp-has-current-submenu').removeClass('wp-has-current-submenu');

    // Open your specific parent menu
    $('.toplevel_page_school-dashbaord')
        .removeClass('wp-not-current-submenu')
        .addClass('wp-has-current-submenu wp-menu-open');
}(jQuery));