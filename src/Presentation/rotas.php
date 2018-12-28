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

use PainelDLX\Application\Middlewares\Autorizacao;
use PainelDLX\Presentation\Site\Controllers\AlterarSenhaUsuarioController;
use PainelDLX\Presentation\Site\Controllers\CadastroUsuarioController;
use PainelDLX\Presentation\Site\Controllers\GrupoUsuarioController;

/** @var \RautereX\RautereX $router */

// Cadastro de Usuários --------------------------------------------------------------------------------------------- //
$router->get(
    '/painel-dlx/usuarios',
    [CadastroUsuarioController::class, 'listaUsuarios']
)
    ->middlewares(new Autorizacao('ACESSAR_CADASTRO_USUARIOS'));

$router->get(
    '/painel-dlx/usuarios/novo',
    [CadastroUsuarioController::class, 'formNovoUsuario']
)
    ->middlewares(new Autorizacao('CADASTRAR_NOVO_USUARIO'));

$router->get(
    '/painel-dlx/usuarios/editar',
    [CadastroUsuarioController::class, 'formAlterarUsuario']
)
    ->middlewares(new Autorizacao('EDITAR_CADASTRO_USUARIO'));

$router->get(
    '/painel-dlx/usuarios/detalhe',
    [CadastroUsuarioController::class, 'detalheUsuario']
)
    ->middlewares(new Autorizacao('ACESSAR_CADASTRO_USUARIOS'));

$router->post(
    '/painel-dlx/usuarios/cadastrar-novo-usuario',
    [CadastroUsuarioController::class, 'cadastrarNovoUsuario']
)
    ->middlewares(new Autorizacao('CADASTRAR_NOVO_USUARIO'));


$router->post(
    '/painel-dlx/usuarios/salvar-usuario-existente',
    [CadastroUsuarioController::class, 'atualizarUsuarioExistente']
)
    ->middlewares(new Autorizacao('EDITAR_CADASTRO_USUARIO'));

$router->post(
    '/painel-dlx/usuarios/excluir-usuario',
    [CadastroUsuarioController::class, 'excluirUsuario']
)
    ->middlewares(new Autorizacao('EXCLUIR_CADASTRO_USUARIO'));

// Grupos de Usuários ----------------------------------------------------------------------------------------------- //
$router->get(
    '/painel-dlx/grupos-de-usuarios',
    [GrupoUsuarioController::class, 'listaGruposUsuarios']
)
    ->middlewares(new Autorizacao('VISUALIZAR_GRUPOS_USUARIOS'));

$router->get(
    '/painel-dlx/grupos-de-usuarios/novo',
    [GrupoUsuarioController::class, 'formNovoGrupoUsuario']
)
    ->middlewares(new Autorizacao('CADASTRAR_GRUPO_USUARIO'));

$router->get(
    '/painel-dlx/grupos-de-usuarios/editar',
    [GrupoUsuarioController::class, 'formAlterarGrupoUsuario']
)
    ->middlewares(new Autorizacao('EDITAR_GRUPO_USUARIO'));

$router->get(
    '/painel-dlx/grupos-de-usuarios/detalhe',
    [GrupoUsuarioController::class, 'detalheGrupoUsuario']
)
    ->middlewares(new Autorizacao('VISUALIZAR_GRUPOS_USUARIOS'));

$router->post(
    '/painel-dlx/grupos-de-usuarios/cadastrar',
    [GrupoUsuarioController::class, 'cadastrarNovoGrupoUsuario']
)
    ->middlewares(new Autorizacao('CADASTRAR_GRUPO_USUARIO'));

$router->post(
    '/painel-dlx/grupos-de-usuarios/salvar',
    [GrupoUsuarioController::class, 'atualizarGrupoUsuarioExistente']
)
    ->middlewares(new Autorizacao('EDITAR_GRUPO_USUARIO'));;

$router->post(
    '/painel-dlx/grupos-de-usuarios/excluir',
    [GrupoUsuarioController::class, 'excluirGrupoUsuario']
)
    ->middlewares(new Autorizacao('EXCLUIR_GRUPO_USUARIO'));

// Alteração de senha --------------------------------------------------------------------------------------------- //
$router->get(
    '/painel-dlx/usuarios/alterar-senha',
    [AlterarSenhaUsuarioController::class, 'formAlterarSenha']
)
    ->middlewares(new Autorizacao('ALTERAR_SENHA_USUARIO'));

$router->post(
    '/painel-dlx/usuarios/alterar-senha',
    [AlterarSenhaUsuarioController::class, 'alterarSenhaUsuario']
)
    ->middlewares(new Autorizacao('ALTERAR_SENHA_USUARIO'));