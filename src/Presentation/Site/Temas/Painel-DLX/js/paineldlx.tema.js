function mostrarMenu() {
    var $menu_principal = $('.menu-principal');

    if ($menu_principal.is(':visible')) {
        $('body').removeClass('mostrando-menu');
    } else {
        $('body').addClass('mostrando-menu');
    }
}