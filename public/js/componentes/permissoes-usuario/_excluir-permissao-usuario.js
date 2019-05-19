/**
 * Excluir uma permissão de usuário
 * @param permissao_usuario_id
 * @returns {boolean}
 */
function excluirPermissaoUsuario(permissao_usuario_id) {
    if (confirm('Deseja realmente excluir essa permissão?')) {
        $.ajax({
            url: '/painel-dlx/permissoes/excluir-permissao',
            type: 'post',
            data: {permissao_usuario_id: permissao_usuario_id},
            dataType: 'json'
        });
    }

    return false;
}