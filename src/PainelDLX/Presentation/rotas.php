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

use PainelDLX\Presentation\Site\Controllers\CadastroUsuarioController;

/** @var \RautereX\RautereX $router */
$router->get(
    '/painel-dlx/usuarios',
    [CadastroUsuarioController::class, 'listaUsuarios']
);
$router->get(
    '/painel-dlx/usuarios/novo',
    [CadastroUsuarioController::class, 'formNovoUsuario']
);
$router->get(
    '/painel-dlx/usuarios/editar',
    [CadastroUsuarioController::class, 'formAlterarUsuario']
);
$router->post(
    '/painel-dlx/usuarios/criar-novo-usuario',
    [CadastroUsuarioController::class, 'cadastrarNovoUsuario']
);
$router->put(
    '/painel-dlx/usuarios/salvar-usuario-existente',
    [CadastroUsuarioController::class, 'atualizarUsuarioExistente']
);
$router->delete(
    '/painel-dlx/usuarios/excluir-usuario',
    [CadastroUsuarioController::class, 'excluir']
);