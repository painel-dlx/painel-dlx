/**
 * Excluir um grupo de usuário
 * @param {int} grupo_usuario_id
 * @returns {boolean}
 */
function excluirGrupoUsuario(grupo_usuario_id) {
    if (confirm('Deseja realmente excluir esse grupo de usuário?')) {
        $.ajax({
            url: '/painel-dlx/grupos-de-usuarios/excluir',
            type: 'post',
            data: {grupo_usuario_id: grupo_usuario_id},
            dataType: 'json'
        });
    }

    return false;
}