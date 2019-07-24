/* global $ */

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
        mensagem: 'Encerrando sua sessão...',
        success: function (json) {
            if (json.retorno === 'sucesso') {
                window.location = '/';
                return;
            }

            msgUsuario.mostrar(json.mensagem);
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

        $.ajax({
            url: url_conteudo,
            data: {'pg-mestra': 'conteudo-master'},
            type: 'get',
            dataType: 'html',
            mensagem: 'Carregando conteúdo do widget...',
            success: function (html, status, xhr) {
                $this.find('.widget-home-conteudo').html(html);
                msgUsuario.fechar(xhr.id);
            }
        });
    });
});

// Ajax
// @codekit-prepend "componentes/ajax-setup/_ajax-setup.js"

// Paginação
// @codekit-prepend "componentes/paginacao/_ir-para-pagina.js"

// Configuração SMTP
// @codekit-append "componentes/config-smtp/_excluir-config-smtp.js"
// @codekit-append "componentes/config-smtp/_testar-config-smtp.js"

// Grupos de Usuários
// @codekit-append "componentes/grupos-usuarios/_excluir-grupo-usuario.js"

// Pemissões de Usuários
// @codekit-append "componentes/permissoes-usuario/_adicionar-permissao.js"
// @codekit-append "componentes/permissoes-usuario/_excluir-permissao-usuario.js"
// @codekit-append "componentes/permissoes-usuario/_retirar-permissao.js"

// Usuários
// @codekit-append "componentes/usuarios/_excluir-usuario.js"