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
use PainelDLX\Application\Middlewares\CriptografarSenhas;
use PainelDLX\Application\Middlewares\DefinePaginaMestra;
use PainelDLX\Application\Middlewares\VerificarLogon;
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

        $router->get(
            '/painel-dlx/usuarios',
            [CadastroUsuarioController::class, 'listaUsuarios']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('ACESSAR_CADASTRO_USUARIOS'),
            new DefinePaginaMestra($this->painel_dlx->getServerRequest(), $this->session)
        );

        $router->get(
            '/painel-dlx/usuarios/novo',
            [CadastroUsuarioController::class, 'formNovoUsuario']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('CADASTRAR_NOVO_USUARIO'),
            new DefinePaginaMestra($this->painel_dlx->getServerRequest(), $this->session)
        );

        $router->get(
            '/painel-dlx/usuarios/editar',
            [CadastroUsuarioController::class, 'formAlterarUsuario']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('EDITAR_CADASTRO_USUARIO'),
            new DefinePaginaMestra($this->painel_dlx->getServerRequest(), $this->session)
        );

        $router->get(
            '/painel-dlx/usuarios/detalhe',
            [CadastroUsuarioController::class, 'detalheUsuario']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('ACESSAR_CADASTRO_USUARIOS'),
            new DefinePaginaMestra($this->painel_dlx->getServerRequest(), $this->session)
        );

        $router->get(
            '/painel-dlx/usuarios/alterar-senha',
            [AlterarSenhaUsuarioController::class, 'formAlterarSenha']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('ALTERAR_SENHA_USUARIO')
        );

        $router->post(
            '/painel-dlx/usuarios/alterar-senha',
            [AlterarSenhaUsuarioController::class, 'alterarSenhaUsuario']
        )->middlewares(
            new VerificarLogon($this->session),
            new Autorizacao('ALTERAR_SENHA_USUARIO'),
            new CriptografarSenhas('senha_atual', 'senha_nova', 'senha_confirm')
        );

        $router->get(
            '/painel-dlx/minha-conta',
            [MinhaContaController::class, 'meusDados']
        )->middlewares(
            new VerificarLogon($this->session),
            new DefinePaginaMestra($this->painel_dlx->getServerRequest(), $this->session)
        );

        $router->get(
            '/painel-dlx/alterar-minha-senha',
            [MinhaContaController::class, 'formAlterarMinhaSenha']
        )->middlewares(
            new VerificarLogon($this->session),
            new DefinePaginaMestra($this->painel_dlx->getServerRequest(), $this->session)
        );

        $router->post(
            '/painel-dlx/alterar-minha-senha',
            [MinhaContaController::class, 'alterarMinhaSenha']
        )->middlewares(
            new VerificarLogon($this->session),
            new CriptografarSenhas('senha_atual', 'senha_nova', 'senha_confirm')
        );

        $router->get(
            '/painel-dlx/resumo-usuario-logado',
            [MinhaContaController::class, 'resumoInformacoes']
        )->middlewares(
            new VerificarLogon($this->session),
            new DefinePaginaMestra($this->painel_dlx->getServerRequest(), $this->session)
        );
    }
}