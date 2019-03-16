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
use PainelDLX\Presentation\Site\PermissoesUsuario\Controllers\CadastroPermissaoController;

class PermissoesRouter extends PainelDLXRouter
{

    /**
     * Registrar todas as rotas
     * @throws Exception
     */
    public function registrar(): void
    {
        $router = $this->getRouter();

        $router->get(
            '/painel-dlx/permissoes',
            [CadastroPermissaoController::class, 'listaPermissoesUsuarios']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('CRIAR_PERMISSOES_USUARIO'),
            new DefinePaginaMestra($this->painel_dlx->getServerRequest(), $this->session)
        );

        $router->get(
            '/painel-dlx/permissoes/novo',
            [CadastroPermissaoController::class, 'formNovaPermissaoUsuario']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('CRIAR_PERMISSOES_USUARIO'),
            new DefinePaginaMestra($this->painel_dlx->getServerRequest(), $this->session)
        );

        $router->post(
            '/painel-dlx/permissoes/criar-nova-permissao',
            [CadastroPermissaoController::class, 'criarNovaPermissao']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('CRIAR_PERMISSOES_USUARIO')
        );

        $router->get(
            '/painel-dlx/permissoes/editar',
            [CadastroPermissaoController::class, 'formEditarPermissaoUsuario']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('EDITAR_PERMISSOES_USUARIO'),
            new DefinePaginaMestra($this->painel_dlx->getServerRequest(), $this->session)
        );

        $router->post(
            '/painel-dlx/permissoes/editar-permissao',
            [CadastroPermissaoController::class, 'alterarPermissaoUsuario']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('EDITAR_PERMISSOES_USUARIO')
        );

        $router->get(
            '/painel-dlx/permissoes/detalhe',
            [CadastroPermissaoController::class, 'detalhePermissaoUsuario']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('CRIAR_PERMISSOES_USUARIO'),
            new DefinePaginaMestra($this->painel_dlx->getServerRequest(), $this->session)
        );

        $router->post(
            '/painel-dlx/permissoes/excluir-permissao',
            [CadastroPermissaoController::class, 'excluirPermissaoUsuario']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('EXCLUIR_PERMISSOES_USUARIO')
        );
    }
}