/**
 * Testar uma configuração SMTP existente
 * @param config_smtp_id
 */
function testarConfigSmtp(config_smtp_id) {
    $.ajax({
        url: '/painel-dlx/config-smtp/testar',
        data: {config_smtp_id: config_smtp_id},
        type: 'get',
        dataType: 'json',
        mensagem: 'Enviando email de teste.<br>Por favor aguarde...',
        success: function (json, status, xhr) {
            msgUsuario.mostrar(json.mensagem, json.retorno, xhr.id);
        }
    });
}