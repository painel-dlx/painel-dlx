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
            '/painel-dlx/grupos-de-usuarios',
            [GrupoUsuarioController::class, 'listaGruposUsuarios']
        )->middlewares(
            $define_pagina_mestra,
            $verificar_logon,
            $autorizacao->setPermissoes('VISUALIZAR_GRUPOS_USUARIOS'),
            $configurar_paginacao
        );

        $router->get(
            '/painel-dlx/grupos-de-usuarios/novo',
            [GrupoUsuarioController::class, 'formNovoGrupoUsuario']
        )->middlewares(
            $define_pagina_mestra,
            $verificar_logon,
            $autorizacao->setPermissoes('CADASTRAR_GRUPO_USUARIO')
        );

        $router->get(
            '/painel-dlx/grupos-de-usuarios/editar',
            [GrupoUsuarioController::class, 'formAlterarGrupoUsuario']
        )->middlewares(
            $define_pagina_mestra,
            $verificar_logon,
            $autorizacao->setPermissoes('EDITAR_GRUPO_USUARIO')
        );

        $router->get(
            '/painel-dlx/grupos-de-usuarios/detalhe',
            [GrupoUsuarioController::class, 'detalheGrupoUsuario']
        )->middlewares(
            $define_pagina_mestra,
            $verificar_logon,
            $autorizacao->setPermissoes('VISUALIZAR_GRUPOS_USUARIOS')
        );

        $router->post(
            '/painel-dlx/grupos-de-usuarios/cadastrar',
            [GrupoUsuarioController::class, 'cadastrarNovoGrupoUsuario']
        )->middlewares(
            $verificar_logon,
            $autorizacao->setPermissoes('CADASTRAR_GRUPO_USUARIO')
        );

        $router->post(
            '/painel-dlx/grupos-de-usuarios/salvar',
            [GrupoUsuarioController::class, 'atualizarGrupoUsuarioExistente']
        )->middlewares(
            $verificar_logon,
            $autorizacao->setPermissoes('EDITAR_GRUPO_USUARIO')
        );

        $router->post(
            '/painel-dlx/grupos-de-usuarios/excluir',
            [GrupoUsuarioController::class, 'excluirGrupoUsuario']
        )->middlewares(
            $verificar_logon,
            $autorizacao->setPermissoes('EXCLUIR_GRUPO_USUARIO')
        );

        $router->get(
            '/painel-dlx/grupos-de-usuarios/permissoes',
            [ConfigurarPermissoesController::class, 'formConfigurarPermissao']
        )->middlewares(
            $define_pagina_mestra,
            $verificar_logon,
            $autorizacao->setPermissoes('GERENCIAR_PERMISSOES_GRUPOS')
        );

        $router->post(
            '/painel-dlx/grupos-de-usuarios/configurar-permissoes',
            [ConfigurarPermissoesController::class, 'salvarConfiguracaoPermissao']
        )->middlewares(
            $verificar_logon,
            $autorizacao->setPermissoes('GERENCIAR_PERMISSOES_GRUPOS')
        );
    }
}