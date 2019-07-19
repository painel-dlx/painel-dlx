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
use PainelDLX\Application\Middlewares\CriptografarSenhas;
use PainelDLX\Application\Middlewares\DefinePaginaMestra;
use PainelDLX\Application\Middlewares\VerificarLogon;
use PainelDLX\Application\Services\PainelDLX;
use PainelDLX\Presentation\Site\Usuarios\Controllers\AlterarSenhaUsuarioController;
use PainelDLX\Presentation\Site\Usuarios\Controllers\CadastroUsuarioController;
use PainelDLX\Presentation\Site\Usuarios\Controllers\MinhaContaController;

class UsuariosRouter extends PainelDLXRouter
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
            '/painel-dlx/usuarios',
            [CadastroUsuarioController::class, 'listaUsuarios']
        )->middlewares(
            $define_pagina_mestra,
            $verificar_logon,
            $autorizacao->setPermissoes('ACESSAR_CADASTRO_USUARIOS'),
            $configurar_paginacao
        );

        $router->get(
            '/painel-dlx/usuarios/novo',
            [CadastroUsuarioController::class, 'formNovoUsuario']
        )->middlewares(
            $define_pagina_mestra,
            $verificar_logon,
            $autorizacao->setPermissoes('CADASTRAR_NOVO_USUARIO')
        );

        $router->get(
            '/painel-dlx/usuarios/editar',
            [CadastroUsuarioController::class, 'formAlterarUsuario']
        )->middlewares(
            $define_pagina_mestra,
            $verificar_logon,
            $autorizacao->setPermissoes('EDITAR_CADASTRO_USUARIO')
        );

        $router->get(
            '/painel-dlx/usuarios/detalhe',
            [CadastroUsuarioController::class, 'detalheUsuario']
        )->middlewares(
            $define_pagina_mestra,
            $verificar_logon,
            $autorizacao->setPermissoes('ACESSAR_CADASTRO_USUARIOS')
        );

        $router->post(
            '/painel-dlx/usuarios/cadastrar-novo-usuario',
            [CadastroUsuarioController::class, 'cadastrarNovoUsuario']
        )->middlewares(
            $verificar_logon,
            $autorizacao->setPermissoes('CADASTRAR_NOVO_USUARIO'),
            new CriptografarSenhas('senha', 'senha_confirm')
        );

        $router->post(
            '/painel-dlx/usuarios/salvar-usuario-existente',
            [CadastroUsuarioController::class, 'atualizarUsuarioExistente']
        )->middlewares(
            $verificar_logon,
            $autorizacao->setPermissoes('EDITAR_CADASTRO_USUARIO')
        );

        $router->post(
            '/painel-dlx/usuarios/excluir-usuario',
            [CadastroUsuarioController::class, 'excluirUsuario']
        )->middlewares(
            $verificar_logon,
            $autorizacao->setPermissoes('EXCLUIR_CADASTRO_USUARIO')
        );

        $router->get(
            '/painel-dlx/usuarios/alterar-senha',
            [AlterarSenhaUsuarioController::class, 'formAlterarSenha']
        )->middlewares(
            $verificar_logon,
            $autorizacao->setPermissoes('ALTERAR_SENHA_USUARIO')
        );

        $router->post(
            '/painel-dlx/usuarios/alterar-senha',
            [AlterarSenhaUsuarioController::class, 'alterarSenhaUsuario']
        )->middlewares(
            $verificar_logon,
            $autorizacao->setPermissoes('ALTERAR_SENHA_USUARIO'),
            new CriptografarSenhas('senha_atual', 'senha_nova', 'senha_confirm')
        );

        $router->get(
            '/painel-dlx/minha-conta',
            [MinhaContaController::class, 'meusDados']
        )->middlewares(
            $define_pagina_mestra,
            $verificar_logon
        );

        $router->get(
            '/painel-dlx/alterar-minha-senha',
            [MinhaContaController::class, 'formAlterarMinhaSenha']
        )->middlewares(
            $define_pagina_mestra,
            $verificar_logon
        );

        $router->post(
            '/painel-dlx/alterar-minha-senha',
            [MinhaContaController::class, 'alterarMinhaSenha']
        )->middlewares(
            $verificar_logon,
            new CriptografarSenhas('senha_atual', 'senha_nova', 'senha_confirm')
        );

        $router->get(
            '/painel-dlx/resumo-usuario-logado',
            [MinhaContaController::class, 'resumoInformacoes']
        )->middlewares(
            $define_pagina_mestra,
            $verificar_logon
        );
    }
}