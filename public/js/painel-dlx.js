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
    });
}

// Grupos de Usuários ----------------------------------------------------------------------------------------------- //
/**
 * Fechar o popup de gerenciamento das configurações
 */
function fecharPopupGerenciarConfiguracoes() {
    $('#popup-gerenciar-permissoes').fadeOut('fast', function () {
        $(this).remove();
        $(window).off('keyup.__gerenciarPermissoes');
    });
}

/**
 * Mostrar popup para gerenciar configurações
 * @param grupo_usuario_id
 */
function popupGerenciarConfiguracoes(grupo_usuario_id) {
    if ($('#popup-gerenciar-permissoes').length < 1) {
        $.get(
            '/painel-dlx/grupos-de-usuarios/permissoes',
            {grupo_usuario_id: grupo_usuario_id, 'pg-mestra': 'conteudo-master'},
            function (html) {
                $(document.createElement('div'))
                    .attr('id', 'popup-gerenciar-permissoes')
                    .addClass('popup-modal')
                    .html(html)
                    .appendTo($('body'));

                $(window).on('keyup.__gerenciarPermissoes', function (evt) {
                    var kc = evt.keycode || evt.which;

                    if (kc === 27) {
                        fecharPopupGerenciarConfiguracoes();
                    }
                });
            }
        );
    }
}

// Carregar conteúdo dos widgets ------------------------------------------------------------------------------------ //
$(window).on('load.__widgets', function () {
    $('[data-widget-url]').each(function () {
        var $this = $(this);
        var url_conteudo = $this.data('widget-url');

        $.get(url_conteudo, {'pg-mestra': 'conteudo-master'}, function (html) {
            $this.find('.widget-home-conteudo').html(html);
        });
    });
});