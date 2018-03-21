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

namespace PainelDLX\Admin\Modelos;

use Comum\DAO\GrupoUsuario as GrupoUsuarioDAO;
use DLX\Ajudantes\Visao as AjdVisao;
use DLX\Excecao\DLX as DLXExcecao;
use Geral\Modelos\BaseModeloRegistro;
use Geral\Modelos\RegistroConsulta;
use Geral\Modelos\RegistroEdicao;

class GrupoUsuario extends BaseModeloRegistro {
    use RegistroConsulta, RegistroEdicao, GrupoUsuarioDAO;

    public function __construct($pk = null) {
        parent::__construct('dlx_paineldlx_grupos_usuarios', 'grupo_usuario_');
        $this->set__NomeModelo(AjdVisao::traduzirTexto('grupo de usuário', 'painel-dlx'));
        $this->selecionarPK($pk);
        
        $this->adicionarEvento('antes', 'salvar', function () {
            $where_nome = ['where' => ["{$this->getBdPrefixo()}nome = '{$this->getNome()}'"]];

            if (!$this->reg_vazio) {
                $where_nome['where'][] = "{$this->getBdPrefixo()}id <> {$this->getID()}";
            } // Fim if

            // Verificar se o grupo de usuário já existe
            if ($this->qtdeRegistros((object)$where_nome) > 0) {
                throw new DLXExcecao(sprintf(AjdVisao::traduzirTexto('O grupo de usuário <b>%s</b> já existe!', 'painel-dlx'), $this->getNome()), 403, '-info');
            } // Fim if

            // Apenas um grupo de usuário pode ser definido como padrão
            if ($this->isPadrao()) {
                \DLX::$dlx->bd->query("UPDATE {$this->getBdTabela()} SET {$this->getBdPrefixo()}padrao = 0 WHERE {$this->getBdPrefixo()}padrao = 1");
            } // Fim if
        });
    } // Fim do método __construct


    /**
     * Selecionar o registro marcado como padrão
     * @return boolean Retorna TRUE quando algum registro é selecionado ou false,
     * quando nenhum registro é encontrado.
     */
    public function selecionarPadrao() {
        return $this->selecionarUK(['padrao' => 1]);
    } // Fim do método selecionarPadrao
} // Fim do modelo GrupoUsuario
