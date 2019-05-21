/**
 * Testar uma configuração SMTP existente
 * @param config_smtp_id
 */
function testarConfigSmtp(config_smtp_id) {
    $.get(
        '/painel-dlx/config-smtp/testar',
        {config_smtp_id: config_smtp_id},
        function (json, status, xhr) {
            window.ajaxMsg.mostrarMsgAjax(json.mensagem, json.retorno, xhr.id);
        },
        'json'
    );
}