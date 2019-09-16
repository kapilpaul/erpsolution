;(function(){
    $('.sett').on('click', function() {
        $('#userSettingsCollapsed').slideToggle();
    });

    $('a.userdropdown').on('click', function() {
        $('.dropdown-menu.p-4.dropdown-menu-right').slideToggle();
    });

    $('.csbar').on('click', function() {
        $('.control-sidebar').css("right", 0);
    });

    $(".paper-nav-toggle-right").removeAttr("data-toggle");

    $('.paper-nav-toggle-right').on('click', function() {
        $('.control-sidebar, .control-sidebar-bg').css("right", '-350px');
    });

})(jQuery);