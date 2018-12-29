$('.menu-lateral-item[data-mostrar-html]').on('click', function () {
    var $this = $(this);
    var $menu_lateral = $this.parents('.menu-lateral');
    var ocultar = [];
    var mostrar = $this.data('mostrar-html');

    if (!$this.hasClass('ativo')) {
        $menu_lateral.find('.menu-lateral-item[data-mostrar-html]').each(function () {
            ocultar.push($(this).data('mostrar-html'));
        });

        ocultar = ocultar.join(', ');

        $(ocultar).hide();
        $(mostrar).show();

        $menu_lateral.find('.menu-lateral-item.ativo').removeClass('ativo');
        $this.addClass('ativo');
    }
});