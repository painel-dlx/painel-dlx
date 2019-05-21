/**
 * Excluir um usuário
 * @param usuario_id
 * @returns {boolean}
 */
function excluirUsuario(usuario_id) {
    if (confirm('Deseja realmente excluir esse usuário?')) {
        $.post(
            '/painel-dlx/usuarios/excluir-usuario',
            {usuario_id: usuario_id},
            function (json, status, xhr) {
                if (json.retorno === 'sucesso') {
                    window.ajaxMsg.add(json.mensagem, json.retorno, xhr.id);
                    window.location.reload();
                }

                window.ajaxMsg.mostrarMsgAjax(json.mensagem, json.retorno, xhr.id);
            },
            'json'
        );
    }

    return false;
}