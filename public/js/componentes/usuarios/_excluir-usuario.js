/**
 * Excluir um usuário
 * @param usuario_id
 * @returns {boolean}
 */
function excluirUsuario(usuario_id) {
    if (confirm('Deseja realmente excluir esse usuário?')) {
        $.ajax({
            url: '/painel-dlx/usuarios/excluir-usuario',
            type: 'post',
            data: {usuario_id: usuario_id},
            dataType: 'json'
        });
    }

    return false;
}