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

namespace PainelDLX\Desenvolvedor\Modelos;

use Comum\DTO\Modulo as ModuloDTO;
use DLX\Ajudantes\Sessao;
use DLX\Ajudantes\Strings;
use DLX\Ajudantes\Vetores;
use DLX\Ajudantes\Visao as AjdVisao;
use DLX\Excecao\DLX as DLXExcecao;
use Geral\Modelos\BaseModeloRegistro;
use Geral\Modelos\RegistroConsulta;
use Geral\Modelos\RegistroEdicao;

class Modulo extends BaseModeloRegistro {
    use RegistroConsulta, RegistroEdicao, ModuloDTO;

    public function __construct($pk = null) {
        parent::__construct('dlx_paineldlx_modulos', 'modulo_');
        $this->set__NomeModelo(AjdVisao::traduzirTexto('módulo', 'painel-dlx'));
        $this->selecionarPK($pk);

        $this->bd_lista = $this->bd_sql->select($this->getBdTabela(), 'M', '__CAMPOS__');
        $this->bd_lista->join($this->getBdTabela(), 'P', "(P.{$this->getBdPrefixo()}id = M.{$this->getBdPrefixo()}pai)", 'LEFT')
            ->where("M.{$this->getBdPrefixo()}delete = 0");

        $this->adicionarEvento('antes', 'salvar', function () {
            /*
             * Quando a flag exibir_menu for TRUE o link deve ser preenchido
             * obrigatoriamente
             */
            if ($this->isExibirMenu() && empty($this->getLink())) {
                throw new DLXExcecao(AjdVisao::traduzirTexto('Para exibir o módulo no menu é necessário informar o link.', 'painel-dlx'), 500, '-aviso');
            } // Fim if

            # Adicionando um sub-módulo
            if (!empty($this->getPai())) {
                $modulo_clone = clone $this;
                $modulo_clone->selecionarPK($this->getPai());

                /*
                 * Verificar se o módulo principal informado não é um sub-módulo
                 * também. Atualmente, o Painel DLX só suporta 1 nível de sub-módulo
                 */
                if (!empty($modulo_clone->getPai())) {
                    throw new DLXExcecao(AjdVisao::traduzirTexto('O módulo principal informado também é um sub-módulo. Por favor, informe um módulo principal válido e tente novamente.', 'painel-dlx'), 403, '-aviso');
                } // Fim if

                /*
                 * Quando um sub-módulo é adicionado ou editado ele deve herdar
                 * o aplicativo do módulo principal
                 */
                $this->setAplicativo($modulo_clone->getAplicativo());
            }
        });

        $this->adicionarEvento('depois', 'salvar', function () {
            /**
             * Ao editar um módulo principal, os sub-módulos deve herdar o mesmo
             * aplicativo
             */
            if (!$this->reg_vazio && empty($this->getPai())) {
                \DLX::$dlx->bd->query("UPDATE {$this->getBdTabela()} SET {$this->getBdPrefixo()}aplicativo = '{$this->getAplicativo()}' WHERE {$this->getBdPrefixo()}pai = {$this->getID()}");
            } // Fim if
        });
    } // Fim do método __construct


    /**
     * Montar o nome completo (com namespace) do provável controle que esse módulo
     * deve utilizar para executar as suas ações
     * @return string
     */
    public function provavelControle() {
        $modulo_principal = new Modulo($this->getPai());

        return sprintf('%s\\%s\\Controles\\%s',
            Strings::conveter2PSR($this->getAplicativo()),
            Strings::conveter2PSR($modulo_principal->getNome()),
            Strings::plural2singular(Strings::conveter2PSR($this->getNome()))
        );
    } // Fim do método provavelControle


    /**
     * Selecionar os itens para compor o menu de acordo com o usuário logado
     * @param string $aplicativo Nome do aplicativo que está solicitando os itens
     * do menu
     * @return array Retorna um vetor multidimensional contendo os menus e submenus
     * necessários
     */
    public function itensMenu($aplicativo = null) {
        $aplicativo = empty($aplicativo) ? \DLX::$dlx->getAplicativo() : $aplicativo;
        $filtro_perm = [];
        $filtro_menu = [
            'M.modulo_publicar = 1',
            'M.modulo_exibir_menu = 1',
            "M.modulo_aplicativo = '{$aplicativo}'"
        ];

        // Filtrar pelo permissionamento do usuário (grupo de usuário) logado,
        // desde que não seja o usuário 'root', identificado pelo ID -1
        if (Sessao::dadoSessao('usuario_id', FILTER_VALIDATE_INT, 0) > 0) {
            $filtro_perm = [
                'M.modulo_id IN (
                    SELECT DISTINCT A.acao_modulo FROM dlx_paineldlx_modulos_acoes AS A
                    INNER JOIN dlx_paineldlx_permissoes AS P ON P.permissao_acao = A.acao_id
                    WHERE P.permissao_grupo = ' . Sessao::dadoSessao('usuario_grupo', FILTER_VALIDATE_INT, 0) .
                ')'
            ];
        } // Fim if

        $modulos = $this->listar(
            (object)[
                'where'     => array_merge(['M.modulo_pai IS NULL'], $filtro_menu),
                'order_by'  => 'M.modulo_ordem, M.modulo_nome'
            ],
            'M.modulo_id, M.modulo_nome AS TEXTO, M.modulo_descr AS DESCR, M.modulo_link AS LINK'
        );
        $submodulos = Vetores::coluna2Chave($this->listar(
            (object)[
                'where'     => array_merge(['M.modulo_pai IS NOT NULL'], $filtro_menu, $filtro_perm),
                'order_by'  => 'M.modulo_ordem, M.modulo_nome'
            ],
            'M.modulo_pai, M.modulo_nome AS TEXTO, M.modulo_descr AS DESCR, M.modulo_link AS LINK'/*,
            0, 0, null, true, $bd_lista */
        ), 'modulo_pai');
        
        return array_map(function ($item) use ($submodulos) {
            $submenu = $submodulos[$item['modulo_id']];
            
            return !empty($submenu)
                ? $item + ['SUBMENU' => Vetores::arrayMulti($submenu) ? $submenu : [$submenu]]
                : $item;
        }, $modulos);
    } // Fim do método
} // Fim do modelo Modulo
