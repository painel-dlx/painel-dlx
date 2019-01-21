/**
 * Exibir um determinado trecho HTML
 */
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

/**
 * Encerrar a sessão
 */
function encerrarSessao() {
    $.ajax({
        url: '/painel-dlx/login/encerrar-sessao',
        type: 'get',
        dataType: 'json',
        success: function (json) {
            if (json.retorno === 'sucesso') {
                window.location = '/';
            } else {
                alert(json.mensagem);
            }
        }
    })
}

// Grupos de Usuários ----------------------------------------------------------------------------------------------- //
/**
 * Mostrar popup para gerenciar configurações
 * @param grupo_usuario_id
 */
function popupGerenciarConfiguracoes(grupo_usuario_id) {
    if ($('#popup-gerenciar-permissoes').length < 1) {
        $.get(
            '/painel-dlx/grupos-de-usuarios/permissoes',
            {grupo_usuario_id: grupo_usuario_id},
            function (html) {
                $(document.createElement('div'))
                    .attr('id', 'popup-gerenciar-permissoes')
                    .addClass('box-popup')
                    .html(html)
                    .appendTo($('body'));
            }
        );
    }
}