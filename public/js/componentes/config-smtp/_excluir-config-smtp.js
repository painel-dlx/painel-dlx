/**
 * Excluir configuração SMTP
 * @param config_smtp_id
 * @returns {boolean}
 */
function excluirConfigSmtp(config_smtp_id) {
    if (confirm('Deseja realmente excluir essa configuração SMTP?')) {
        $.post(
            '/painel-dlx/config-smtp/excluir-config-smtp',
            {config_smtp_id: config_smtp_id},
            function(json, status, xhr) {
                window.ajaxMsg.add(json.mensagem, json.retorno, xhr.id);

                if (json.retorno === 'sucesso') {
                    window.location.reload();
                }
            },
            'json'
        );
    }

    return false;
}