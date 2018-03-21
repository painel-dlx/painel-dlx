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

namespace PainelDLX\Admin\Controles;

use DLX\Ajudantes\ConstrutorSQL as AjdConstrutorSQL;
use DLX\Excecao\DLX as DLXExcecao;
use Geral\Controles\PainelDLX;
use Geral\Controles\RegistroConsulta;
use Geral\Controles\RegistroEdicao;

class ConfigEmail extends PainelDLX {
    use RegistroConsulta, RegistroEdicao;

    public function __construct() {
        parent::__construct(dirname(__DIR__), new \PainelDLX\Admin\Modelos\ConfigEmail(), 'admin/envio-de-emails');

        $this->adicionarEvento('depois', 'mostrarDetalhes', function () {
            # Visão
            $this->visao->adicionarTemplate('det_config_email');

            # Parâmetros
            $this->visao->tituloPagina($this->visao->traduzir('Configuração de envio de e-mails', 'painel-dlx'));

            $this->visao->mostrarConteudo();
        });

        $this->adicionarEvento('antes', 'excluir', function () {
            # Impedir a exlusão do conteúdo
            throw new DLXExcecao($this->visao->traduzir('Não é possível excluir a configuração de envio de e-mails.', 'painel-dlx'), 1403, '-alerta');
        });
    } // Fim do método __construct


    /**
     * Mostrar formulário de inclusão / edição do registro
     * @param  int $pk PK identificadora do registro. Quando não informada, o
     * formulário considerará que um novo registro será incluído.
     * @return void
     */
    protected function mostrarForm($pk = null) {
        $this->gerarForm('email', null, 'admin/envio-de-emails/salvar');

        # Visão
        $this->visao->adicionarTemplate('form_config_email');
        $this->visao->adicionarTemplate('comum/visoes/form_ajax');

        # JS
        $this->visao->adicionarJS('web/js/jquery-form-ajax/jquery.formajax.plugin-min.js');
        $this->visao->adicionarJS('web/js/jquery-mostrar-msg/jquery.mostrarmsg.plugin.js');

        # Parâmetros
        $this->visao->tituloPagina($this->visao->traduzir('Configuração de envio de e-mails', 'painel-dlx'));

        $this->visao->mostrarConteudo();
    } // Fim do método mostrarForm
}// Fim do controle ConfigEmail
