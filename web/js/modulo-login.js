/**
 * painel-dlx
 * @version: v1.17.08
 * @author: Diego Lepera
 *
 * Created by Diego Lepera on 2017-07-28. Please report any bug at
 * https://github.com/dlepera88-php/framework-dlx/issues
 *
 * The MIT License (MIT)
 * Copyright (c) 2017 Diego Lepera http://diegolepera.xyz/
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/* global console, mostrarAlerta */

// Verificar se o plugin $.fn.formAjax foi carregado
if (typeof $.fn.formAjax !== 'function') {
    console.error('O plugin $.fn.formAjax não foi carregado. Por favor, adicione o plugin antes de incluir o arquivo modulo-login.js');
} // Fim if

function voltarPaginaLogin() {
    window.location = '';
} // Fim function voltarPaginaLogin

// Formulário de login --------------------------------------------------------------- //
$('[data-form-ajax="login"]').formAjax({
    func_depois: function (retorno) {
        if (typeof retorno.tipo !== 'undefined') {
            if (retorno.tipo === '-sucesso') {
                window.location.reload();
            } else {
                mostrarAlerta(retorno.mensagem, {
                    tipo: retorno.tipo,
                    tema: 'painel-dlx'
                });
            } // Fim if ... else
        } else {
            mostrarAlerta(retorno, { tema: 'painel-dlx' });
        } // Fim if ... else
    }
});

// Esqueci minha senha --------------------------------------------------------------- //
$('[data-form-ajax="esqueci-minha-senha"]').formAjax({
    func_depois: function (retorno, form) {
        if (typeof retorno.tipo !== 'undefined') {
            var botoes = [], $form = $(form);

            switch (retorno.tipo) {
                case '-sucesso':
                    botoes = [
                        { texto: 'Reenviar o email', funcao: $.proxy($form.trigger, $form, 'submit') },
                        { texto: 'Voltar para a página de login', funcao: voltarPaginaLogin }
                    ];
                    break;
                case '-erro':
                    botoes = [
                        { texto: 'Tentar novamente' },
                        { texto: 'Voltar para a página de login', funcao: voltarPaginaLogin }
                    ];
                    break;
            } // Fim switch

            mostrarAlerta(retorno.mensagem, {
                tipo: retorno.tipo,
                tema: 'painel-dlx',
                botoes: botoes
            });
        } else {
            mostrarAlerta(retorno, { tema: 'painel-dlx' });
        } // Fim if ... else
    }
});

// Solicitar reset de senha ---------------------------------------------------------- //
$('[data-form-ajax="reset-senha"]').formAjax({
    func_depois: function (retorno) {
        if (typeof retorno.tipo !== 'undefined') {
            var botoes = [];

            switch (retorno.tipo) {
                case '-sucesso':
                    botoes = [
                        { texto: 'Voltar para a página de login', funcao: voltarPaginaLogin }
                    ];
                    break;

                case '-erro': 
                    botoes = [
                        { texto: 'Tentar novamente' }
                    ];
                    break;
            } // Fim switch

            mostrarAlerta(retorno.mensagem, {
                tipo: retorno.tipo,
                tema: 'painel-dlx',
                botoes: botoes
            });
        } else {
            mostrarAlerta(retorno, { tema: 'painel-dlx' });
        } // Fim if ... else
    }
});