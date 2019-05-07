<?php
/**
 * MIT License
 *
 * Copyright (c) 2018 PHP DLX
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace PainelDLX\Application\Routes;


use Exception;
use PainelDLX\Application\Middlewares\Autorizacao;
use PainelDLX\Application\Middlewares\DefinePaginaMestra;
use PainelDLX\Application\Middlewares\VerificarLogon;
use PainelDLX\Presentation\Site\Emails\Controllers\ConfigSmtpController;
use PainelDLX\Presentation\Site\Emails\Controllers\EditarConfigSmtpController;
use PainelDLX\Presentation\Site\Emails\Controllers\NovaConfigSmtpController;

class ConfigSmtpRouter extends PainelDLXRouter
{

    /**
     * Registrar todas as rotas
     * @throws Exception
     */
    public function registrar(): void
    {
        $router = $this->getRouter();

        $router->get(
            '/painel-dlx/config-smtp',
            [ConfigSmtpController::class, 'listaConfigSmtp']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('VER_CONFIGURACOES_SMTP'),
            new DefinePaginaMestra($this->painel_dlx->getServerRequest(), $this->session)
            // new LimparQueryString($this)
        );

        $router->get(
            '/painel-dlx/config-smtp/nova',
            [NovaConfigSmtpController::class, 'formNovaConfigSmtp']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('CRIAR_CONFIGURACAO_SMTP'),
            new DefinePaginaMestra($this->painel_dlx->getServerRequest(), $this->session)
        );

        $router->post(
            '/painel-dlx/config-smtp/salvar-config-smtp',
            [NovaConfigSmtpController::class, 'salvarNovaConfigSmtp']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('CRIAR_CONFIGURACAO_SMTP')
        );

        $router->get(
            '/painel-dlx/config-smtp/editar',
            [EditarConfigSmtpController::class, 'formEditarConfigSmtp']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('EDITAR_CONFIGURACAO_SMTP'),
            new DefinePaginaMestra($this->painel_dlx->getServerRequest(), $this->session)
        );

        $router->post(
            '/painel-dlx/config-smtp/atualizar-config-smtp',
            [EditarConfigSmtpController::class, 'editarConfigSmtp']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('EDITAR_CONFIGURACAO_SMTP')
        );

        $router->post(
            '/painel-dlx/config-smtp/excluir-config-smtp',
            [ConfigSmtpController::class, 'excluirConfigSmtp']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('EXCLUIR_CONFIGURACAO_SMTP')
        );

        $router->get(
            '/painel-dlx/config-smtp/detalhe',
            [ConfigSmtpController::class, 'detalheConfigSmtp']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('VER_CONFIGURACOES_SMTP'),
            new DefinePaginaMestra($this->painel_dlx->getServerRequest(), $this->session)
        );

        // TODO: Teste de email é GET ou POST?
        $router->get(
            '/painel-dlx/config-smtp/testar',
            [ConfigSmtpController::class, 'testarConfigSmtp']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('VER_CONFIGURACOES_SMTP')
        );

        $router->post(
            '/painel-dlx/config-smtp/testar',
            [ConfigSmtpController::class, 'testarConfigSmtp']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('VER_CONFIGURACOES_SMTP')
        );
    }
}