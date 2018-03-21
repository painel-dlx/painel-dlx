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

namespace PainelDLX\Desenvolvedor\Controles;

use DLX\Ajudantes\ConstrutorSQL as AjdConstrutorSQL;
use DLX\Ajudantes\Visao as AjdVisao;
use Geral\Controles\PainelDLX;
use Geral\Controles\RegistroConsulta;
use Geral\Controles\RegistroEdicao;

class Modulo extends PainelDLX {
    use RegistroConsulta, RegistroEdicao;

    public function __construct() {
        parent::__construct(dirname(__DIR__), new \PainelDLX\Desenvolvedor\Modelos\Modulo(), 'desenvolvedor/modulos');

        $this->adicionarEvento('depois', 'mostrarDetalhes', function () {
            # Visão
            $this->visao->adicionarTemplate('comum/visoes/menu_opcoes');
            $this->visao->adicionarTemplate('det_modulo');

            # JS
            $this->visao->adicionarJS('web/js/jquery-mostrar-msg/jquery.mostrarmsg.plugin.js');

            # Parâmetros
            $this->visao->tituloPagina($this->modelo->getNome());
            $this->visao->adicionarParam('conf:mostrar-link?', $this->modelo->isExibirMenu() && !empty($this->modelo->getLink()));

            # Selecionar informações do módulo principal
            if (!empty($this->modelo->getPai())) {
                $modulo_pai = clone $this->modelo;
                $modulo_pai->selecionarPK($this->modelo->getPai());

                if (!$modulo_pai->reg_vazio) {
                    $this->visao->adicionarParam('info:modulo-principal', $modulo_pai->getNome());
                } // Fim if
            } // Fim if

            $this->visao->mostrarConteudo();
        });
    } // Fim do método __construct


    /**
     * Mostrar a lista de registro
     * @param  array  $params_sql Parâmetros a serem passados para a consulta SQL
     * para gerar a lista desejada
     * @return string Conteúdo HTML da lista
     */
    protected function mostrarLista($params_sql = []) {
        $this->gerarLista(
            sprintf(AjdConstrutorSQL::SQL_CAMPO_COM_ALIAS, 'M.modulo_id', $this->visao->traduzir('ID', 'painel-dlx')) . ',' .
            sprintf(AjdConstrutorSQL::SQL_CAMPO_COM_ALIAS, "CASE COALESCE(M.modulo_pai, 0) WHEN 0 THEN M.modulo_nome ELSE CONCAT(M.modulo_nome, '<br/>', P.modulo_nome) END", $this->visao->traduzir('Módulo', 'painel-dlx')) . ',' .
            sprintf(AjdConstrutorSQL::SQL_CAMPO_COM_ALIAS, 'M.modulo_aplicativo', $this->visao->traduzir('Aplicativo', 'painel-dlx')) . ',' .
            sprintf(AjdConstrutorSQL::SQL_CAMPO_COM_ALIAS, "CASE M.modulo_exibir_menu WHEN 0 THEN '' ELSE CONCAT('<a href=\"{$this->visao->obterParams('diretorio-relativo')}', M.modulo_link, '\">', M.modulo_link, '</a>') END", $this->visao->traduzir('Link', 'painel-dlx')) . ',' .
            sprintf(AjdConstrutorSQL::SQL_CASE_SIM_NAO, 'M.modulo_publicar', $this->visao->traduzir('Ativo?', 'painel-dlx')),
            array_replace_recursive(['order_by' => 'M.modulo_aplicativo, COALESCE(P.modulo_ordem, M.modulo_ordem), COALESCE(P.modulo_nome, M.modulo_nome)'], $params_sql)
        );

        # Visão
        $this->visao->adicionarTemplate('comum/visoes/menu_opcoes');
        $this->visao->adicionarTemplate('comum/visoes/form_filtro');
        $this->visao->adicionarTemplate('comum/visoes/lista');

        # JS
        $this->visao->adicionarJS('web/js/jquery-mostrar-msg/jquery.mostrarmsg.plugin-min.js');

        # Parâmetros
        $this->visao->tituloPagina($this->visao->traduzir('Módulos', 'painel-dlx'));
        $this->visao->adicionarParam('html:form-acao', $this->url_excluir);

        return $this->visao->mostrarConteudo();
    } // Fim do método mostrarLista


    /**
     * Mostrar formulário de inclusão / edição do registro
     * @param  int $pk PK identificadora do registro. Quando não informada, o formulário considerará
     * que um novo registro será incluído.
     * @return string Conteúdo HTML do formulário
     */
    protected function mostrarForm($pk = null) {
        $this->gerarForm('modulo', 'desenvolvedor/modulos/inserir', 'desenvolvedor/modulos/salvar', $pk);

        // Verificar se esse módulo é um módulo principal
        $e_modulo_principal = !$this->modelo->reg_vazio && empty($this->modelo->getPai());
        $e_submodulo = !$this->modelo->reg_vazio && !empty($this->modelo->getPai());

        # Visão
        $this->visao->adicionarTemplate('comum/visoes/menu_opcoes');
        $this->visao->adicionarTemplate('form_modulo');
        $this->visao->adicionarTemplate('comum/visoes/form_ajax');

        # JS
        $this->visao->adicionarJS('web/js/jquery-form-ajax/jquery.formajax.plugin-min.js');
        $this->visao->adicionarJS('web/js/jquery-mostrar-msg/jquery.mostrarmsg.plugin.js');

        # Parâmetros
        $this->visao->adicionarParam('lista:modulos-principais',
            $this->modelo->listar((object)['where' => "M.modulo_publicar = 1 AND M.modulo_pai IS NULL AND M.modulo_aplicativo = 'painel-dlx'", 'order_by' => 'M.modulo_nome'], 'M.modulo_id AS VALOR, M.modulo_nome AS TEXTO')
        );
        $this->visao->adicionarParam('conf:modulo-principal?', $e_modulo_principal);
        $this->visao->adicionarParam('conf:submodulo?', $e_submodulo);

        if ($e_modulo_principal || $e_submodulo) {
            // Para módulos principais, será exibida a lista de sub-módulos
            if ($e_modulo_principal) {
                // Adicionar lista de submódulos do módulo atual
                $this->visao->adicionarParam('html:lista-submodulos',
                    AjdVisao::extrairHTMLInterno(new Modulo(), 'mostrarLista', [
                        'params_sql' => ['where' => "M.modulo_pai = {$this->modelo->getID()}"]
                    ], 'vazio')
                );
            } // Fim if

            // Para sub-módulos, será exibida a lista de ações do módulo
            if ($e_submodulo) {
                $this->visao->adicionarParam('html:lista-acoes',
                    AjdVisao::extrairHTMLInterno(new ModuloAcao(), 'mostrarLista', [
                        'params_sql' => [],
                        'modulo_id'  => $this->modelo->getID()
                    ], 'vazio')
                );
            } // Fim if

            $this->visao->adicionarTemplate('comum/visoes/lista_form_ajax');
        } // Fim if

        return $this->visao->mostrarConteudo();
    } // Fim do método mostrarForm


    /**
     * Obter o nome do aplicativo de um determinado módulo principal
     * @param  int      $pk         ID do módulo principal
     * @param  boolean  $escrever   Se TRUE escreve o nome do módulo na tela.
     * @return string|null  Retorna o nome do aplicativo ou NULL se o módulo principal
     * não foi selecionado
     */
    public function obterAplicativo($pk = null, $escrever = true) {
        $modulo_principal = $_SERVER['REQUEST_METHOD'] === 'POST'
            ? filter_input(INPUT_POST, 'modulo-principal', FILTER_VALIDATE_INT)
            : filter_var($pk, FILTER_VALIDATE_INT);

        $this->modelo->selecionarPK($modulo_principal);

        if (!$this->modelo->reg_vazio && $escrever) {
            echo $this->modelo->getAplicativo();
        } // Fim if

        return $this->modelo->getAplicativo();
    } // Fim do método obterAplicativo
}// Fim do controle Modulo
