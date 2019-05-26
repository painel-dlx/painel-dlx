/* global msgUsuario */

/**
 * Excluir configuração SMTP
 * @param config_smtp_id
 * @returns {boolean}
 */
function excluirConfigSmtp(config_smtp_id) {
    if (confirm('Deseja realmente excluir essa configuração SMTP?')) {
        $.ajax({
            url: '/painel-dlx/config-smtp/excluir-config-smtp',
            data: {config_smtp_id: config_smtp_id},
            type: 'post',
            dataType: 'json',
            success: function (json, status, xhr) {
                msgUsuario.adicionar(json.mensagem, json.retorno, xhr.id);

                if (json.retorno === 'sucesso') {
                    window.location.reload();
                }
            },
            mensagem: 'Excluindo configuração SMTP.<br>Por favor aguarde...'
        });
    }

    return false;
}