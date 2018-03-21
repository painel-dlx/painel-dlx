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
$__MODULO = 'Desenvolvedor';


// Temas -------------------------------------------------------------------- //
\DLX::$dlx->adicionarRota('^%home%desenvolvedor/temas/?$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Tema',
    'acao'       => 'mostrarLista'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/temas/mostrar-detalhes/[a-z\-]+$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Tema',
    'acao'       => 'mostrarDetalhes',
    'params'     => '/-/-/-/:pk'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/temas/excluir$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Tema',
    'acao'       => 'excluir'
], 'post');


// Idiomas ----------------------------------------------------------------- //
\DLX::$dlx->adicionarRota('^%home%desenvolvedor/idiomas/?$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Idioma',
    'acao'       => 'mostrarLista'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/idiomas/mostrar-detalhes/\d+$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Idioma',
    'acao'       => 'mostrarDetalhes',
    'params'     => '/-/-/-/:pk'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/idiomas/novo$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Idioma',
    'acao'       => 'mostrarForm'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/idiomas/editar/\d+$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Idioma',
    'acao'       => 'mostrarForm',
    'params'     => '/-/-/-/:pk'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/idiomas/inserir$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Idioma',
    'acao'       => 'inserir'
], 'post');

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/idiomas/salvar$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Idioma',
    'acao'       => 'salvar'
], 'post');

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/idiomas/excluir$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Idioma',
    'acao'       => 'excluir'
], 'post');


// Formatos de data -------------------------------------------------------- //
\DLX::$dlx->adicionarRota('^%home%desenvolvedor/formatos-de-datas/?$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'FormatoData',
    'acao'       => 'mostrarLista'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/formatos-de-datas/mostrar-detalhes/\d+$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'FormatoData',
    'acao'       => 'mostrarDetalhes',
    'params'     => '/-/-/-/:pk'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/formatos-de-datas/novo$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'FormatoData',
    'acao'       => 'mostrarForm'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/formatos-de-datas/editar/\d+$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'FormatoData',
    'acao'       => 'mostrarForm',
    'params'     => '/-/-/-/:pk'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/formatos-de-datas/inserir$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'FormatoData',
    'acao'       => 'inserir'
], 'post');

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/formatos-de-datas/salvar$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'FormatoData',
    'acao'       => 'salvar'
], 'post');

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/formatos-de-datas/excluir$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'FormatoData',
    'acao'       => 'excluir'
], 'post');

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/formatos-de-datas/testar-formato$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'FormatoData',
    'acao'       => 'testarFormato'
]);


// Servidores de domínio ---------------------------------------------------- //
\DLX::$dlx->adicionarRota('^%home%desenvolvedor/servidores-de-dominio/?$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'ServidorDominio',
    'acao'       => 'mostrarLista'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/servidores-de-dominio/mostrar-detalhes/\d+$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'ServidorDominio',
    'acao'       => 'mostrarDetalhes',
    'params'     => '/-/-/-/:pk'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/servidores-de-dominio/novo$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'ServidorDominio',
    'acao'       => 'mostrarForm'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/servidores-de-dominio/editar/\d+$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'ServidorDominio',
    'acao'       => 'mostrarForm',
    'params'     => '/-/-/-/:pk'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/servidores-de-dominio/inserir$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'ServidorDominio',
    'acao'       => 'salvar'
], 'post');

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/servidores-de-dominio/salvar$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'ServidorDominio',
    'acao'       => 'salvar'
], 'post');

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/servidores-de-dominio/excluir$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'ServidorDominio',
    'acao'       => 'excluir'
], 'post');


// Módulos ------------------------------------------------------------------ //
\DLX::$dlx->adicionarRota('^%home%desenvolvedor/modulos/?$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Modulo',
    'acao'       => 'mostrarLista'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/modulos/mostrar-detalhes/\d+$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Modulo',
    'acao'       => 'mostrarDetalhes',
    'params'     => '/-/-/-/:pk'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/modulos/novo$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Modulo',
    'acao'       => 'mostrarForm'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/modulos/editar/\d+$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Modulo',
    'acao'       => 'mostrarForm',
    'params'     => '/-/-/-/:pk'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/modulos/inserir$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Modulo',
    'acao'       => 'inserir'
], 'post');

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/modulos/salvar$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Modulo',
    'acao'       => 'salvar'
], 'post');

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/modulos/excluir$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Modulo',
    'acao'       => 'excluir'
], 'post');

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/modulos/herdar-aplicativo(/\d+)?$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'Modulo',
    'acao'       => 'obterAplicativo',
    'params'     => '/-/-/-/:pk'
], ['get', 'post']);


// Ações dos módulos -------------------------------------------------------- //
\DLX::$dlx->adicionarRota('^%home%desenvolvedor/modulos/acoes/\d+$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'ModuloAcao',
    'acao'       => 'mostrarLista',
    'params'     => '/-/-/:params_sql/:modulo_id'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/modulos/acoes/mostrar-detalhes/\d+$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'ModuloAcao',
    'acao'       => 'mostrarDetalhes',
    'params'     => '/-/-/-/-/:pk'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/modulos/acoes/\d+/mostrar-detalhes/\d+$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'ModuloAcao',
    'acao'       => 'mostrarDetalhes',
    'params'     => '/-/-/-/-/-/:pk'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/modulos/acoes/\d+/novo$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'ModuloAcao',
    'acao'       => 'mostrarForm',
    'params'     => '/-/-/:pk/:modulo_id'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/modulos/acoes/novo/?$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'ModuloAcao',
    'acao'       => 'mostrarForm'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/modulos/acoes/editar/\d+$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'ModuloAcao',
    'acao'       => 'mostrarForm',
    'params'     => '/-/-/-/-/:pk'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/modulos/acoes/\d+/editar/\d+$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'ModuloAcao',
    'acao'       => 'mostrarForm',
    'params'     => '/-/-/-/-/-/:pk'
]);

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/modulos/acoes/inserir$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'ModuloAcao',
    'acao'       => 'inserir'
], 'post');

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/modulos/acoes/salvar$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'ModuloAcao',
    'acao'       => 'salvar'
], 'post');

\DLX::$dlx->adicionarRota('^%home%desenvolvedor/modulos/acoes/excluir$', [
    'aplicativo' => $__APLICATIVO,
    'modulo'     => $__MODULO,
    'controle'   => 'ModuloAcao',
    'acao'       => 'excluir'
], 'post');
