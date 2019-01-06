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
use PainelDLX\Presentation\Site\Controllers\CadastroPermissaoController;
use PainelDLX\Application\Middlewares\VerificarLogonMiddleware;
use PainelDLX\Presentation\Site\Controllers\LoginController;
use PainelDLX\Presentation\Site\Controllers\MInhaContaController;
use SechianeX\Factories\SessionFactory;

/** @var \RautereX\RautereX $router */

$session = SessionFactory::createPHPSession();

// Cadastro de Usuários --------------------------------------------------------------------------------------------- //
$router->get(
    '/painel-dlx/usuarios',
    [CadastroUsuarioController::class, 'listaUsuarios']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('ACESSAR_CADASTRO_USUARIOS')
    );

$router->get(
    '/painel-dlx/usuarios/novo',
    [CadastroUsuarioController::class, 'formNovoUsuario']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('CADASTRAR_NOVO_USUARIO')
    );

$router->get(
    '/painel-dlx/usuarios/editar',
    [CadastroUsuarioController::class, 'formAlterarUsuario']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('EDITAR_CADASTRO_USUARIO')
    );

$router->get(
    '/painel-dlx/usuarios/detalhe',
    [CadastroUsuarioController::class, 'detalheUsuario']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('ACESSAR_CADASTRO_USUARIOS')
    );

$router->post(
    '/painel-dlx/usuarios/cadastrar-novo-usuario',
    [CadastroUsuarioController::class, 'cadastrarNovoUsuario']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('CADASTRAR_NOVO_USUARIO')
    );


$router->post(
    '/painel-dlx/usuarios/salvar-usuario-existente',
    [CadastroUsuarioController::class, 'atualizarUsuarioExistente']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('EDITAR_CADASTRO_USUARIO')
    );

$router->post(
    '/painel-dlx/usuarios/excluir-usuario',
    [CadastroUsuarioController::class, 'excluirUsuario']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('EXCLUIR_CADASTRO_USUARIO')
    );

// Grupos de Usuários ----------------------------------------------------------------------------------------------- //
$router->get(
    '/painel-dlx/grupos-de-usuarios',
    [GrupoUsuarioController::class, 'listaGruposUsuarios']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('VISUALIZAR_GRUPOS_USUARIOS')
    );

$router->get(
    '/painel-dlx/grupos-de-usuarios/novo',
    [GrupoUsuarioController::class, 'formNovoGrupoUsuario']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('CADASTRAR_GRUPO_USUARIO')
    );

$router->get(
    '/painel-dlx/grupos-de-usuarios/editar',
    [GrupoUsuarioController::class, 'formAlterarGrupoUsuario']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('EDITAR_GRUPO_USUARIO')
    );

$router->get(
    '/painel-dlx/grupos-de-usuarios/detalhe',
    [GrupoUsuarioController::class, 'detalheGrupoUsuario']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('VISUALIZAR_GRUPOS_USUARIOS')
    );

$router->post(
    '/painel-dlx/grupos-de-usuarios/cadastrar',
    [GrupoUsuarioController::class, 'cadastrarNovoGrupoUsuario']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('CADASTRAR_GRUPO_USUARIO')
    );

$router->post(
    '/painel-dlx/grupos-de-usuarios/salvar',
    [GrupoUsuarioController::class, 'atualizarGrupoUsuarioExistente']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('EDITAR_GRUPO_USUARIO')
    );

$router->post(
    '/painel-dlx/grupos-de-usuarios/excluir',
    [GrupoUsuarioController::class, 'excluirGrupoUsuario']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('EXCLUIR_GRUPO_USUARIO')
    );

// Alteração de senha ----------------------------------------------------------------------------------------------- //
$router->get(
    '/painel-dlx/usuarios/alterar-senha',
    [AlterarSenhaUsuarioController::class, 'formAlterarSenha']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('ALTERAR_SENHA_USUARIO')
    );

$router->post(
    '/painel-dlx/usuarios/alterar-senha',
    [AlterarSenhaUsuarioController::class, 'alterarSenhaUsuario']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('ALTERAR_SENHA_USUARIO')
    );

// Permissões ------------------------------------------------------------------------------------------------------- //
$router->get(
    '/painel-dlx/permissoes',
    [CadastroPermissaoController::class, 'listaPermissoesUsuarios']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('CRIAR_PERMISSOES_USUARIO')
    );

$router->get(
    '/painel-dlx/permissoes/novo',
    [CadastroPermissaoController::class, 'formNovaPermissaoUsuario']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('CRIAR_PERMISSOES_USUARIO')
    );

$router->post(
    '/painel-dlx/permissoes/criar-nova-permissao',
    [CadastroPermissaoController::class, 'criarNovaPermissao']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('CRIAR_PERMISSOES_USUARIO')
    );

$router->get(
    '/painel-dlx/permissoes/editar',
    [CadastroPermissaoController::class, 'formEditarPermissaoUsuario']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('EDITAR_PERMISSOES_USUARIO')
    );

$router->post(
    '/painel-dlx/permissoes/editar-permissao',
    [CadastroPermissaoController::class, 'alterarPermissaoUsuario']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('EDITAR_PERMISSOES_USUARIO')
    );

$router->get(
    '/painel-dlx/permissoes/detalhe',
    [CadastroPermissaoController::class, 'detalhePermissaoUsuario']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('CRIAR_PERMISSOES_USUARIO')
    );

$router->post(
    '/painel-dlx/permissoes/excluir-permissao',
    [CadastroPermissaoController::class, 'excluirPermissaoUsuario']
)
    ->middlewares(
        new VerificarLogonMiddleware($session),
        new Autorizacao('EXCLUIR_PERMISSOES_USUARIO')
    );

// Login ------------------------------------------------------------------------------------------------------------ //
$router->get(
    '/painel-dlx/login',
    [LoginController::class, 'formLogin']
);

$router->post(
    '/painel-dlx/login/fazer-login',
    [LoginController::class, 'fazerLogin']
);

$router->get(
    '/painel-dlx/login/encerrar-sessao',
    [LoginController::class, 'fazerLogout']
)
    ->middlewares(new VerificarLogonMiddleware($session));

// Minha conta ------------------------------------------------------------------------------------------------------ //
$router->get(
    '/painel-dlx/minha-conta',
    [MInhaContaController::class, 'meusDados']
)
    ->middlewares(new VerificarLogonMiddleware($session));

$router->get(
    '/painel-dlx/alterar-minha-senha',
    [MInhaContaController::class, 'formAlterarMinhaSenha']
)
    ->middlewares(new VerificarLogonMiddleware($session));

$router->post(
    '/painel-dlx/alterar-minha-senha',
    [MInhaContaController::class, 'alterarMinhaSenha']
)
    ->middlewares(new VerificarLogonMiddleware($session));