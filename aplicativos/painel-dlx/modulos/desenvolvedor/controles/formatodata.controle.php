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
use Geral\Controles\PainelDLX;
use Geral\Controles\RegistroConsulta;
use Geral\Controles\RegistroEdicao;

class FormatoData extends PainelDLX {
    use RegistroConsulta, RegistroEdicao;

    public function __construct() {
        parent::__construct(dirname(__DIR__), new \PainelDLX\Desenvolvedor\Modelos\FormatoData(), 'desenvolvedor/formatos-de-datas');

        $this->adicionarEvento('depois', 'mostrarDetalhes', function () {
            # Visão
            $this->visao->adicionarTemplate('comum/visoes/menu_opcoes');
            $this->visao->adicionarTemplate('det_formato_data');

            # JS
            $this->visao->adicionarJS('web/js/jquery-mostrar-msg/jquery.mostrarmsg.plugin.js');

            # Parâmetros
            $this->visao->tituloPagina($this->modelo->getDescr());

            $this->visao->mostrarConteudo();
        });
    } // Fim do método __construct


    /**
     * Mostrar a lista de registro
     * @return void
     */
    protected function mostrarLista() {
        $this->gerarLista(
            sprintf(AjdConstrutorSQL::SQL_CAMPO_COM_ALIAS, 'formato_data_id', $this->visao->traduzir('ID', 'painel-dlx')) . ',' .
            sprintf(AjdConstrutorSQL::SQL_CAMPO_COM_ALIAS, 'formato_data_descr', $this->visao->traduzir('Descrição', 'painel-dlx')) . ',' .
            sprintf(AjdConstrutorSQL::SQL_CASE_SIM_NAO, 'formato_data_padrao', $this->visao->traduzir('Padrão?', 'painel-dlx')) . ',' .
            sprintf(AjdConstrutorSQL::SQL_CASE_SIM_NAO, 'formato_data_publicar', $this->visao->traduzir('Ativo?', 'painel-dlx')),
            ['order_by' => 'formato_data_descr']
        );

        # Visão
        $this->visao->adicionarTemplate('comum/visoes/menu_opcoes');
        $this->visao->adicionarTemplate('comum/visoes/form_filtro');
        $this->visao->adicionarTemplate('comum/visoes/lista');

        # JS
        $this->visao->adicionarJS('web/js/jquery-mostrar-msg/jquery.mostrarmsg.plugin-min.js');

        # Parâmetros
        $this->visao->tituloPagina($this->visao->traduzir('Formatos de datas', 'painel-dlx'));
        $this->visao->adicionarParam('html:form-acao', $this->url_excluir);

        $this->visao->mostrarConteudo();
    } // Fim do método mostrarLista


    /**
     * Mostrar formulário de inclusão / edição do registro
     * @param  int $pk PK identificadora do registro. Quando não informada, o
     * formulário considerará que um novo registro será incluído.
     * @return void
     */
    protected function mostrarForm($pk = null) {
        $this->gerarForm('data', 'desenvolvedor/formatos-de-datas/inserir', 'desenvolvedor/formatos-de-datas/salvar', $pk);

        # Visão
        $this->visao->adicionarTemplate('comum/visoes/menu_opcoes');
        $this->visao->adicionarTemplate('form_formato_data');
        $this->visao->adicionarTemplate('comum/visoes/form_ajax');

        # JS
        $this->visao->adicionarJS('web/js/jquery-form-ajax/jquery.formajax.plugin-min.js');
        $this->visao->adicionarJS('web/js/jquery-mostrar-msg/jquery.mostrarmsg.plugin.js');

        $this->visao->mostrarConteudo();
    } // Fim do método mostrarForm


    /**
     * Testar um formato de data, exibindo a data e hora atuais, no formato desejado
     * @return void Imprime a data e hora atuais no formato indicado
     */
    public function testarFormato() {
        $formato = filter_input(INPUT_GET, 'formato-data', FILTER_SANITIZE_STRING);
        echo date($formato, time());
    } // Fim do método testarFormato
}// Fim do controle FormatoData
