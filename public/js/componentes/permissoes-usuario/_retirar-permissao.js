/**
 * Excluir uma permissão do grupo
 * @param permissao_dom
 */
function retirarPermissao(permissao_dom) {
    $(permissao_dom)
        .removeAttr('onclick')
        .attr('onclick', 'adicionarPermissao(this)')
        .appendTo($('#lista-permissoes-sistema'))
        .find(':checkbox')
        .prop('checked', false);
}