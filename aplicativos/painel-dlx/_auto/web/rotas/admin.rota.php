<?php
/**
 * painel-dlx
 * @version: v1.17.08
 * @author: Diego Lepera
 *
 * Created by Diego Lepera on 2017-07-28. Please report any bug at
 * https://github.com/dlepera88-php/painel-dlx/issues
 *
 * The MIT License (MIT)
 * Copyright (c) 2017 Diego Lepera http://diegolepera.xyz/
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

$__APLICATIVO = 'PainelDLX';
$__MODULO = 'Admin';

// Grupos de usuários ------------------------------------------------------- //
\DLX::$dlx->adicionarRota('^%home%admin/grupos-de-usuarios/?$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'GrupoUsuario',
    'acao'       => 'mostrarLista'
]);

\DLX::$dlx->adicionarRota('^%home%admin/grupos-de-usuarios/mostrar-detalhes/\d+$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'GrupoUsuario',
    'acao'       => 'mostrarDetalhes',
    'params'     => '/-/-/-/:pk'
]);

\DLX::$dlx->adicionarRota('^%home%admin/grupos-de-usuarios/novo$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'GrupoUsuario',
    'acao'       => 'mostrarForm'
]);

\DLX::$dlx->adicionarRota('^%home%admin/grupos-de-usuarios/editar/\d+$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'GrupoUsuario',
    'acao'       => 'mostrarForm',
    'params'     => '/-/-/-/:pk'
]);

\DLX::$dlx->adicionarRota('^%home%admin/grupos-de-usuarios/inserir$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'GrupoUsuario',
    'acao'       => 'inserir'
], 'post');

\DLX::$dlx->adicionarRota('^%home%admin/grupos-de-usuarios/salvar$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'GrupoUsuario',
    'acao'       => 'salvar'
], 'post');

\DLX::$dlx->adicionarRota('^%home%admin/grupos-de-usuarios/excluir$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'GrupoUsuario',
    'acao'       => 'excluir'
], 'post');


// Usuários ----------------------------------------------------------------- //
\DLX::$dlx->adicionarRota('^%home%admin/usuarios/?$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Usuario',
    'acao'       => 'mostrarLista'
]);

\DLX::$dlx->adicionarRota('^%home%admin/usuarios/mostrar-detalhes/\d+$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Usuario',
    'acao'       => 'mostrarDetalhes',
    'params'     => '/-/-/-/:pk'
]);

\DLX::$dlx->adicionarRota('^%home%admin/usuarios/novo$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Usuario',
    'acao'       => 'mostrarForm'
]);

\DLX::$dlx->adicionarRota('^%home%admin/usuarios/editar/\d+$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Usuario',
    'acao'       => 'mostrarForm',
    'params'     => '/-/-/-/:pk'
]);

\DLX::$dlx->adicionarRota('^%home%admin/usuarios/inserir$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Usuario',
    'acao'       => 'salvar'
], 'post');

\DLX::$dlx->adicionarRota('^%home%admin/usuarios/salvar$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Usuario',
    'acao'       => 'salvar'
], 'post');

\DLX::$dlx->adicionarRota('^%home%admin/usuarios/excluir$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Usuario',
    'acao'       => 'excluir'
], 'post');

\DLX::$dlx->adicionarRota('^%home%admin/usuarios/minha-conta$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Usuario',
    'acao'       => 'formMinhaConta'
]);

\DLX::$dlx->adicionarRota('^%home%admin/usuarios/alterar-minha-senha$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Usuario',
    'acao'       => 'formAlterarSenha'
]);

\DLX::$dlx->adicionarRota('^%home%admin/usuarios/alterar-senha$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Usuario',
    'acao'       => 'alterarSenha'
], 'post');


// Configurações ------------------------------------------------------------ //
\DLX::$dlx->adicionarRota('^%home%admin/envio-de-emails/?$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'ConfigEmail',
    'acao'       => 'mostrarDetalhes'
]);

\DLX::$dlx->adicionarRota('^%home%admin/envio-de-emails/editar/?$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'ConfigEmail',
    'acao'       => 'mostrarForm'
]);

\DLX::$dlx->adicionarRota('^%home%admin/envio-de-emails/salvar$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'ConfigEmail',
    'acao'       => 'salvar'
], 'post');
