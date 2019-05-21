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
            success: function (json, status, xhr) {
                if (json.retorno === 'sucesso') {
                    window.ajaxMsg.add(json.mensagem, json.retorno, xhr.id);
                    window.location.reload();
                    return;
                }

                window.ajaxMsg.mostrarMsgAjax(json.mensagem, json.retorno, xhr.id);
            }
        });
    }

    return false;
}