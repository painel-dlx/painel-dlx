/**
 * Funções referentes a formulários
 */

var formRegistro = {
    registroSalvo: function (retorno) {
        var $this = $(this),
            botoes = [];
            
        if (typeof retorno === 'object') {
            if (retorno.tipo === '-sucesso') {
                if (typeof $this.data('url-lista') !== 'undefined') {
                    botoes.push({
                        texto: 'Lista',
                        funcao: function () {
                            window.location = $this.data('url-lista');
                        },
                        params: {
                            class: 'botao com-icone -botao -lista'
                        }
                    });
                } // Fim if

                if (typeof $this.data('url-detalhes') !== 'undefined') {
                    botoes.push({
                        texto: 'Detalhes',
                        funcao: function () {
                            window.location = $this.data('url-detalhes').replace(':id', retorno.id);
                        },
                        params: {
                            class: 'botao com-icone -botao -detalhes'
                        }
                    });
                } // Fim if

                if (typeof $this.data('url-editar') !== 'undefined') {
                    botoes.push({
                        texto: 'Editar',
                        funcao: function () {
                            window.location = $this.data('url-editar').replace(':id', retorno.id);
                        },
                        params: {
                            class: 'botao com-icone -botao -editar'
                        }
                    });
                } // Fim if

                if (typeof $this.data('url-novo') !== 'undefined') {
                    botoes.push({
                        texto: 'Novo',
                        funcao: function () {
                            window.location = $this.data('url-novo');
                        },
                        params: {
                            class: 'botao com-icone -botao -inserir'
                        }
                    });
                } // Fim if
            } // Fim if

            mostrarAlerta(retorno.mensagem, {
                tipo: retorno.tipo,
                tema: 'painel-dlx',
                botoes: botoes
            });
        } else {
            mostrarAlerta(retorno, {
                tipo: '-info',
                tema: 'painel-dlx'
            });
        }
    },

    registroExcluido: function () {
        history.back();
    }
};