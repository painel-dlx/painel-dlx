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
                    msgUsuario.adicionar(json.mensagem, json.retorno, xhr.id);
                    window.location.reload();
                }

                msgUsuario.mostrar(json.mensagem, json.retorno, xhr.id);
            },
            'json'
        );
    }

    return false;
}