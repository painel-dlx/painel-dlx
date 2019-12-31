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
use PainelDLX\Application\Middlewares\ConfigurarPaginacao;
use PainelDLX\Application\Middlewares\DefinePaginaMestra;
use PainelDLX\Application\Middlewares\VerificarLogon;
use PainelDLX\Application\Services\PainelDLX;
use PainelDLX\Presentation\Web\PermissoesUsuario\Controllers\CadastroPermissaoController;

class PermissoesRouter extends PainelDLXRouter
{

    /**
     * Registrar todas as rotas
     * @throws Exception
     */
    public function registrar(): void
    {
        $router = $this->getRouter();
        $container = PainelDLX::getInstance()->getContainer();

        /** @var VerificarLogon $verificar_logon */
        $verificar_logon = $container->get(VerificarLogon::class);
        /** @var DefinePaginaMestra $define_pagina_mestra */
        $define_pagina_mestra = $container->get(DefinePaginaMestra::class);
        /** @var ConfigurarPaginacao $configurar_paginacao */
        $configurar_paginacao = $container->get(ConfigurarPaginacao::class);
        /** @var Autorizacao $autorizacao */
        $autorizacao = $container->get(Autorizacao::class);
        
        $router->get(
            '/painel-dlx/permissoes',
            [CadastroPermissaoController::class, 'listaPermissoesUsuarios']
        )->middlewares(
            $verificar_logon,
            $autorizacao->necessitaPermissoes('CRIAR_PERMISSOES_USUARIO'),
            $define_pagina_mestra,
            $configurar_paginacao
        );

        $router->get(
            '/painel-dlx/permissoes/novo',
            [CadastroPermissaoController::class, 'formNovaPermissaoUsuario']
        )->middlewares(
            $verificar_logon,
            $autorizacao->necessitaPermissoes('CRIAR_PERMISSOES_USUARIO'),
            $define_pagina_mestra
        );

        $router->post(
            '/painel-dlx/permissoes/criar-nova-permissao',
            [CadastroPermissaoController::class, 'criarNovaPermissao']
        )->middlewares(
            $verificar_logon,
            $autorizacao->necessitaPermissoes('CRIAR_PERMISSOES_USUARIO')
        );

        $router->get(
            '/painel-dlx/permissoes/editar',
            [CadastroPermissaoController::class, 'formEditarPermissaoUsuario']
        )->middlewares(
            $verificar_logon,
            $autorizacao->necessitaPermissoes('EDITAR_PERMISSOES_USUARIO'),
            $define_pagina_mestra
        );

        $router->post(
            '/painel-dlx/permissoes/editar-permissao',
            [CadastroPermissaoController::class, 'alterarPermissaoUsuario']
        )->middlewares(
            $verificar_logon,
            $autorizacao->necessitaPermissoes('EDITAR_PERMISSOES_USUARIO')
        );

        $router->get(
            '/painel-dlx/permissoes/detalhe',
            [CadastroPermissaoController::class, 'detalhePermissaoUsuario']
        )->middlewares(
            $verificar_logon,
            $autorizacao->necessitaPermissoes('CRIAR_PERMISSOES_USUARIO'),
            $define_pagina_mestra
        );

        $router->post(
            '/painel-dlx/permissoes/excluir-permissao',
            [CadastroPermissaoController::class, 'excluirPermissaoUsuario']
        )->middlewares(
            $verificar_logon,
            $autorizacao->necessitaPermissoes('EXCLUIR_PERMISSOES_USUARIO')
        );
    }
}