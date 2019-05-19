/**
 * Adicionar uma permissão ao grupo
 * @param permissao_dom
 */
function adicionarPermissao(permissao_dom) {
    $(permissao_dom)
        .removeAttr('onclick')
        .attr('onclick', 'retirarPermissao(this)')
        .appendTo($('#lista-permissoes-grupo'))
        .find(':checkbox')
        .prop('checked', true);
}