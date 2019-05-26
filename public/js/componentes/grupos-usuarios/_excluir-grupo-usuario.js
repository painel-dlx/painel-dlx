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
            dataType: 'json',
            mensagem: 'Excluindo grupo de usuário.<br>Por favor aguarde...',
            success: function (json, status, xhr) {
                if (json.retorno === 'sucesso') {
                    msgUsuario.adicionar(json.mensagem, json.retorno, xhr.id);
                    window.location.reload();
                    return;
                }

                msgUsuario.mostrar(json.mensagem, json.retorno, xhr.id);
            }
        });
    }

    return false;
}