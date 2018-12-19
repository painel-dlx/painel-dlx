/**
 * Mostrar o menu
 */
function mostrarMenu() {
    var $menu_principal = $('.menu-principal');
    var $filtro = $menu_principal.find('.menu-filtro .form-controle');

    if ($menu_principal.is(':visible')) {
        $('body').removeClass('mostrando-menu');
        $filtro.val('');
        $filtro.trigger('input');
    } else {
        $('body').addClass('mostrando-menu');
        $filtro.focus();
    }
}

// Exibir no menu apenas os itens de sub-menu que contenham o termo digitado no filtro
jQuery.expr[':'].contains_ci = function(a, i, m) {
    return jQuery(a).text().toUpperCase()
        .indexOf(m[3].toUpperCase()) >= 0;
};

$('.menu-filtro .form-controle').on('input', function () {
    var $this = $(this);
    var $menu_principal = $('.menu-principal');
    var filtro = $this.val();

    $menu_principal.find('.sub-menu-link:contains_ci(' + filtro + ')').show();
    $menu_principal.find('.sub-menu-link:not(:contains_ci(' + filtro + '))').hide();
});