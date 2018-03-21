<?php
/**
 * painel-dlx
 * @version: v1.17.08
 * @author: Diego Lepera
 *
 * Created by Diego Lepera on 2017-07-28. Please report any bug at
 * https://github.com/dlepera88-php/framework-dlx/issues
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
$__MODULO = 'Login';

// Login -------------------------------------------------------------------- //
\DLX::$dlx->adicionarRota('^%home%login/?$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Login',
    'acao'       => 'mostrarForm'
]);

\DLX::$dlx->adicionarRota('^%home%login/acessar-sistema/?$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Login',
    'acao'       => 'acessarSistema'
], 'post');

\DLX::$dlx->adicionarRota('^%home%login/encerrar-sessao/?$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Login',
    'acao'       => 'encerrarSessao'
]);

\DLX::$dlx->adicionarRota('^%home%login/esqueci-minha-senha/?$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Login',
    'acao'       => 'formEsqueciMinhaSenha'
]);

\DLX::$dlx->adicionarRota('^%home%login/recuperar-senha/?$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Login',
    'acao'       => 'recuperarSenha'
], 'post');

\DLX::$dlx->adicionarRota('^%home%login/resetar-senha/[\w\d]{32}/?$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Login',
    'acao'       => 'formResetarSenha',
    'params'     => '/-/-/:hash'
]);

\DLX::$dlx->adicionarRota('^%home%login/resetar-senha/?$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Login',
    'acao'       => 'resetarSenha'
], 'post');
