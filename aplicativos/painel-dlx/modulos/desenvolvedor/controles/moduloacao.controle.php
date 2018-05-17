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
use DLX\Ajudantes\Sessao;
use DLX\Ajudantes\Vetores;
use Geral\Controles\PainelDLX;
use Geral\Controles\RegistroConsulta;
use Geral\Controles\RegistroEdicao;
use PainelDLX\Admin\Modelos\GrupoUsuario;
use PainelDLX\Desenvolvedor\Modelos\Modulo;

class ModuloAcao extends PainelDLX {
    use RegistroConsulta, RegistroEdicao;

    public function __construct() {
        parent::__construct(dirname(__DIR__), new \PainelDLX\Desenvolvedor\Modelos\ModuloAcao(), 'desenvolvedor/modulos/acoes');

        $this->adicionarEvento('depois', 'mostrarDetalhes', function () {
            # Visão
            $this->visao->adicionarTemplate('comum/visoes/menu_opcoes');
            $this->visao->adicionarTemplate('det_modulo_acao');

            # JS
            $this->visao->adicionarJS('web/js/jquery-mostrar-msg/jquery.mostrarmsg.plugin.js');

            $grupo_usuario = new GrupoUsuario();

            # Parâmetros
            $this->visao->tituloPagina($this->modelo->getDescr());
            $this->visao->adicionarParam('lista:grupos-usuarios',
                array_column($grupo_usuario->listar(
                    (object)[
                        'where' => 'grupo_usuario_id IN (' . implode(',', $this->modelo->getGrupos()) . ')',
                        'order_by' => 'grupo_usuario_nome'
                    ],
                    'grupo_usuario_nome'
                ), 'grupo_usuario_nome')
            );

            return $this->visao->mostrarConteudo();
        });
    } // Fim do método __construct


    /**
     * Mostrar a lista de registro
     * @param array $params_sql Vetor com os parâmetros a aserem passados para a
     * consulta SQL ao gerar a lista
     * @param int   $modulo ID do submódulo do qual essas ações fazem parte
     * @return string Retorna o conteúdo HTML da lista
     */
    protected function mostrarLista($params_sql = [], $modulo_id) {
        $modulo = new Modulo(filter_var($modulo_id, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
            'flags'   => FILTER_NULL_ON_FAILURE
        ]));

        # Validar o sub-módulo
        $this->validarSubmodulo($modulo);

        $this->gerarLista(
            sprintf(AjdConstrutorSQL::SQL_CAMPO_COM_ALIAS, 'acao_id', $this->visao->traduzir('ID', 'painel-dlx')) . ', ' .
            sprintf(AjdConstrutorSQL::SQL_CAMPO_COM_ALIAS, 'acao_descr', $this->visao->traduzir('Descrição', 'painel-dlx')) . ', ' .
            sprintf(AjdConstrutorSQL::SQL_CAMPO_COM_ALIAS, "CONCAT(acao_classe, '<br/>', acao_metodos)", $this->visao->traduzir('Controle', 'painel-dlx')),
            array_replace_recursive(['where' => ["acao_modulo = {$modulo->getID()}"], 'order_by' => 'acao_descr'], (array)$params_sql)
        );

        # Visão
        $this->visao->adicionarTemplate('comum/visoes/menu_opcoes');
        $this->visao->adicionarTemplate('comum/visoes/form_filtro');
        $this->visao->adicionarTemplate('comum/visoes/lista');

        # JS
        $this->visao->adicionarJS('web/js/jquery-mostrar-msg/jquery.mostrarmsg.plugin-min.js');

        # Parâmetros
        $this->visao->tituloPagina(sprintf($this->visao->traduzir('Ações do módulo %s', 'painel-dlx'), $modulo->getNome()));
        $this->visao->adicionarParam('html:form-acao', $this->url_excluir);

        return $this->visao->mostrarConteudo();
    } // Fim do método mostrarLista


    /**
     * Mostrar formulário de inclusão / edição do registro
     * @param  int $pk          PK identificadora do registro. Quando não informada,
     * o formulário considerará que um novo registro será incluído.
     * @param  inf $modulo_id   ID do módulo para incluir essa ação
     * @return string Retorna o conteúdo HTML do formulário
     */
    protected function mostrarForm($pk = null, $modulo_id = null) {
        $modulo = new Modulo();

        if (empty($pk) || $pk < 1) {
            $modulo->selecionarPK(filter_input(INPUT_GET, 'modulo', FILTER_VALIDATE_INT));
        } else {
            $this->modelo->selecionarPK(filter_var($pk, FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1],
                'flags'   => FILTER_NULL_ON_FAILURE
            ]));

            $modulo->selecionarPK($this->modelo->getModulo());
        } // Fim if ... else
        
        // Se o módulo for selecionado, devo verificar se ele é apto para receber
        // ações e permições
        !$modulo->reg_vazio and $this->validarSubmodulo($modulo);

        $this->gerarForm('acao', 'desenvolvedor/modulos/acoes/inserir', 'desenvolvedor/modulos/acoes/salvar', $pk);

        # Visão
        $this->visao->adicionarTemplate('comum/visoes/menu_opcoes');
        $this->visao->adicionarTemplate('form_modulo_acao');
        $this->visao->adicionarTemplate('comum/visoes/form_ajax');

        # JS
        $this->visao->adicionarJS('web/js/jquery-form-ajax/jquery.formajax.plugin-min.js');
        $this->visao->adicionarJS('web/js/jquery-mostrar-msg/jquery.mostrarmsg.plugin-min.js');
        $this->visao->adicionarJS('web/js/jquery-campo-tags/jquery.campotags.plugin-min.js');

        $grupo_usuario = new GrupoUsuario();
        
        # Parâmetros
        $this->visao->adicionarParam('modelo:modulo', $modulo);
        $this->visao->adicionarParam('info:provavel-controle', $modulo->provavelControle());
        $this->visao->adicionarParam('lista:sub-modulos', Vetores::coluna2Chave(
            $modulo->listar((object)[
                'where' => ['M.modulo_pai IS NOT NULL', "M.modulo_aplicativo = 'painel-dlx'"],
                'order_by' => 'COALESCE(P.modulo_ordem, M.modulo_ordem), COALESCE(P.modulo_nome, M.modulo_nome)'
            ], 'P.modulo_nome AS MODULO, M.modulo_id AS ID, M.modulo_nome AS SUB'), 'MODULO')
        );
        $this->visao->adicionarParam('lista:grupos-usuarios',
            $grupo_usuario->listar((object)[
                'where' => [
                    'grupo_usuario_publicar = 1',
                    'grupo_usuario_id <> ' . Sessao::dadoSessao('usuario_id', FILTER_VALIDATE_INT, 0)
                ],
                'order_by' => 'grupo_usuario_nome'
            ], 'grupo_usuario_id, grupo_usuario_nome, grupo_usuario_autoperm')
        );

        return $this->visao->mostrarConteudo();
    } // Fim do método mostrarForm


    /**
     * Validar se o módulo é um submódulo válido
     * @param  Modelo $modulo   Instância do modelo PainelDLX\Desenvolvedor\Modelos\Modulo;
     * @param  string $tipo_msg Informa como a mensagem deve ser exibida ao usuário
     * @return void             Não tem retorno, mas exibe mensagens para o usuário
     * de acordo com a validação.
     */
    private function validarSubmodulo($modulo, $tipo_msg = 'html') {
        if ($modulo->reg_vazio) {
            $this->mostrarMensagemUsuario($this->visao->traduzir('Módulo não identificado!', 'painel-dlx'), '-aviso', $tipo_msg);
        } elseif (empty($modulo->getPai())) {
            $this->mostrarMensagemUsuario($this->visao->traduzir('As ações devem ser incluídas apenas em submódulos.', 'painel-dlx'), '-aviso', $tipo_msg);
        } // Fim if ... elseif
    } // Fim do método validarSubmodulo
}// Fim do controle ModuloAcao
