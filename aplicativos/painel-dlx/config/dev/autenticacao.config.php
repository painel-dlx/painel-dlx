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

$config['autenticacao'] = [
    'nome'          => 'painel-dlx',
    'url-login'     => '%home%login',
    # Função para criptografar as senhas
    'cripto-senha'  => function ($senha) {
        return md5(md5($senha));
    },

    # Campos a serem selecionados durante o login e passados para a sessão
    'campos-login'  => 'usuario_id, usuario_grupo, usuario_nome, usuario_email,
        idioma_sigla, usuario_formato_data, usuario_tema, usuario_tema AS tema_pagina_mestra,
        usuario_bloqueado, usuario_reset_senha, grupo_usuario_nome',

    # Função para verificar a permissão de um usuário para executar uma
    # determinada ação
    'verificar-perm' => function ($controle, $acao) {
        $s_usuario_id = DLX\Ajudantes\Sessao::dadoSessao('usuario_id', FILTER_VALIDATE_INT);

        if ($s_usuario_id > 0) {
            $usuario = new PainelDLX\Admin\Modelos\Usuario($s_usuario_id);
            return $usuario->verificarPerm($controle, $acao);
        } elseif ($s_usuario_id === -1) {
            return method_exists($controle, $acao);
        } // Fim if ... else

        return false;
    },

    # Lista de classes a serem ignoradas pelo sistema de autenticação
    'ignorar-classes' => [
        'PainelDLX\\Login\\Controles\\Login'
    ]
];