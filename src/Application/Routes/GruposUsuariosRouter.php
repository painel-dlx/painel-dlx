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
use PainelDLX\Presentation\Site\GruposUsuarios\Controllers\ConfigurarPermissoesController;
use PainelDLX\Presentation\Site\GruposUsuarios\Controllers\GrupoUsuarioController;

class GruposUsuariosRouter extends PainelDLXRouter
{

    /**
     * Registrar todas as rotas
     * @throws Exception
     */
    public function registrar(): void
    {
        $router = $this->getRouter();

        $router->get(
            '/painel-dlx/grupos-de-usuarios',
            [GrupoUsuarioController::class, 'listaGruposUsuarios']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('VISUALIZAR_GRUPOS_USUARIOS'),
            new DefinePaginaMestra($this->painel_dlx->getServerRequest(), $this->session)
        );

        $router->get(
            '/painel-dlx/grupos-de-usuarios/novo',
            [GrupoUsuarioController::class, 'formNovoGrupoUsuario']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('CADASTRAR_GRUPO_USUARIO'),
            new DefinePaginaMestra($this->painel_dlx->getServerRequest(), $this->session)
        );

        $router->get(
            '/painel-dlx/grupos-de-usuarios/editar',
            [GrupoUsuarioController::class, 'formAlterarGrupoUsuario']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('EDITAR_GRUPO_USUARIO'),
            new DefinePaginaMestra($this->painel_dlx->getServerRequest(), $this->session)
        );

        $router->get(
            '/painel-dlx/grupos-de-usuarios/detalhe',
            [GrupoUsuarioController::class, 'detalheGrupoUsuario']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('VISUALIZAR_GRUPOS_USUARIOS'),
            new DefinePaginaMestra($this->painel_dlx->getServerRequest(), $this->session)
        );

        $router->post(
            '/painel-dlx/grupos-de-usuarios/cadastrar',
            [GrupoUsuarioController::class, 'cadastrarNovoGrupoUsuario']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('CADASTRAR_GRUPO_USUARIO')
        );

        $router->post(
            '/painel-dlx/grupos-de-usuarios/salvar',
            [GrupoUsuarioController::class, 'atualizarGrupoUsuarioExistente']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('EDITAR_GRUPO_USUARIO')
        );

        $router->post(
            '/painel-dlx/grupos-de-usuarios/excluir',
            [GrupoUsuarioController::class, 'excluirGrupoUsuario']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('EXCLUIR_GRUPO_USUARIO')
        );

        $router->get(
            '/painel-dlx/grupos-de-usuarios/permissoes',
            [ConfigurarPermissoesController::class, 'formConfigurarPermissao']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('GERENCIAR_PERMISSOES_GRUPOS'),
            new DefinePaginaMestra($this->painel_dlx->getServerRequest(), $this->session)
        );

        $router->post(
            '/painel-dlx/grupos-de-usuarios/configurar-permissoes',
            [ConfigurarPermissoesController::class, 'salvarConfiguracaoPermissao']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('GERENCIAR_PERMISSOES_GRUPOS')
        );
    }
}