/* global console, adicionarEvento, solicitarConfirmacao, mostrarAlerta, encontrarFuncaoNS */
/* jshint evil:true */

function excluirRegistro(opcoes) {
    /**
     * Caso o botão que tenha disparado esse evento esteja dentro de um formulário,
     * pego as informações dele como opções padrão
     */
    var $form = $(this).parents('form');

    opcoes = $.extend({
        mensagem: 'Está certo disso?',
        func_depois: function () {
            return true;
        },
        func_nao: function () { 
            return true;
        },
        pk: $form.serialize(),
        url: $form.attr('action')
    }, opcoes);
    
    // Ajustar a função a ser executada depois, pois caso tenha sido enviado apenas
    // o nome da função como uma string, devo encontrar a função e colocar no objeto
    if (typeof opcoes.func_depois === 'string') {
        opcoes.func_depois = $.proxy(encontrarFuncaoNS(opcoes.func_depois), this);
    } // Fim if
    
    // Solicitar a confirmação para o usuário
    solicitarConfirmacao(opcoes.mensagem, function () {
        if (typeof opcoes.url === 'undefined') {
            console.error('[excluirRegistro] É necessário informar a URL para executar a exclusão do registro.');
            return false;
        } // Fim if
        
        $.ajax({
            url: opcoes.url,
            type: 'post',
            data: opcoes.pk,
            // dataType: 'json',
            success: function (json) {
                var botoes = [];
                
                try {
                    json = JSON.parse(json);

                    // Se o resultado for um sucesso, incluir a função func_depois no botão
                    // do alerta
                    if (json.tipo === '-sucesso' && typeof opcoes.func_depois === 'function') {
                        botoes = [{ funcao: opcoes.func_depois }];
                    } // Fim if

                    mostrarAlerta(json.mensagem, {
                        tema: 'painel-dlx',
                        tipo: json.tipo,
                        botoes: botoes
                    });
                } catch (e) {
                    mostrarAlerta(json, {
                        tema: 'painel-dlx',
                        tipo: '-info'
                    });
                } // Fim try ... catch
            }
        });
    }, function () {
        return opcoes.func_nao();
    });
}

adicionarEvento($('[data-acao="excluir-registro"]'), 'click.__excluirRegistro', function () {
    excluirRegistro.apply(this, [$(this).data()]);
});
