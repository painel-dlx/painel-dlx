/**
 * Testar uma configuração SMTP existente
 * @param {object} config_smtp
 */
function testarConfigSmtp(config_smtp) {
    $.ajax({
        url: '/painel-dlx/config-smtp/testar',
        data: config_smtp,
        type: 'post',
        dataType: 'json',
        mensagem: 'Enviando email de teste.<br>Por favor aguarde...',
        success: function (json, status, xhr) {
            msgUsuario.mostrar(json.mensagem, json.retorno, xhr.id);
        }
    });
}