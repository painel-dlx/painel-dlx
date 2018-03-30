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

namespace Geral\Controles;

use DLX\Ajudantes\HTMLForm;
use DLX\Ajudantes\HTMLLinks;
use DLX\Ajudantes\Sessao;
use PainelDLX\Desenvolvedor\Modelos\Modulo;

abstract class PainelDLX extends BaseControleRegistro {
    /**
     * PainelDLX constructor.
     * @param string        $diretorio_visao    Diretório de fontes para a visão
     * @param object|null   $modelo             Objeto do tipo modelo referente a esse controle
     * @param string        $base_dir           Diretório base para gerenciamento dos registros
     */
    public function __construct($diretorio_visao = '.', $modelo = null, $base_dir = '.') {
        parent::__construct($diretorio_visao, $modelo, $base_dir);

        $modulo = new Modulo();

        # Adicionar templates fixos
        $this->visao->adicionarTemplate('comum/visoes/menu_principal');
        $this->visao->adicionarTemplate('comum/visoes/menu_usuario');
        $this->visao->adicionarTemplate('comum/visoes/mensagens_usuario');
        $this->visao->adicionarTemplate('comum/visoes/rodape');

        # Adicionar o suporte ao jQuery a esse aplicativo
        $this->visao->adicionarJS('web/js/jquery-3.2.1.min.js', 0);
        $this->visao->adicionarJS('web/js/framework-dlx-min.js', 1);
        $this->visao->adicionarJS('web/js/painel-dlx-min.js', 2);

        # Parâmetros comuns para todas as visões
        $this->visao->adicionarParam('html:itens-menu', $modulo->itensMenu());

        # Modificar o tema página mestra de acordo com a preferência do usuário
        $this->visao->setTema(Sessao::dadoSessao('usuario_tema', FILTER_SANITIZE_STRING, \DLX::$dlx->config('aplicativo', 'tema')));
        $this->visao->setPaginaMestra(Sessao::dadoSessao('tema_pagina_mestra', FILTER_SANITIZE_STRING, 'padrao'));

        # Adicionar novos modelos de links
        HTMLLinks::novoLink('voltar', [
            'class' => 'com-icone -voltar'
        ]);

        # Adicionar botões
        HTMLForm::novoBotao('filtrar', [
            'type'  => 'submit',
            'class' => 'botao'
        ]);
    } // Fim do método __construct
} // Fim do controle PainelDLX
