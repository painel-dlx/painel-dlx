/**
 * Excluir um usuário
 * @param usuario_id
 * @returns {boolean}
 */
function excluirUsuario(usuario_id) {
    if (confirm('Deseja realmente excluir esse usuário?')) {
        $.ajax({
            url: '/painel-dlx/usuarios/excluir-usuario',
            data: {usuario_id: usuario_id},
            type: 'post',
            dataType: 'json',
            mensagem: 'Excluindo usuário.<br>Por favor aguarde...',
            success: function (json, status, xhr) {
                if (json.retorno === 'sucesso') {
                    msgUsuario.adicionar(json.mensagem, json.retorno, xhr.id);
                    window.location.reload();
                }

                msgUsuario.mostrar(json.mensagem, json.retorno, xhr.id);
            }
        });
    }

    return false;
}