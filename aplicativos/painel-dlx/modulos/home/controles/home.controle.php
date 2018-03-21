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

namespace PainelDLX\Home\Controles;

use DLX\Ajudantes\ConstrutorSQL as AjdConstrutorSQL;
use Geral\Controles\PainelDLX;

class Home extends PainelDLX {
    /**
     * @var array $widgets (static)
     * Vetor com as configurações dos widgets a serem carregados na página inicial.
     */
    private static $widgets = [];

    public function __construct() {
        parent::__construct(dirname(__DIR__));
    } // Fim do método __construct


    protected function paginaInicial() {
        // Carregar os arquivos com as configurações dos widgets a serem carregados na página
        // inicial (HOME) do Painel DLX
        self::carregarConfguracoesWidgets();

        # Visão
        $this->visao->adicionarTemplate('home');

        # JS
        $this->visao->adicionarJS('web/js/jquery-widget-ajax/jquery.widgetajax.plugin-min.js');

        # Parâmetros
        $this->visao->tituloPagina($this->visao->traduzir('Página inicial', 'painel-dlx'));
        $this->visao->adicionarParam('lista:widgets', self::$widgets);
        
        $this->visao->mostrarConteudo();
    } // Fim do método paginaIncial


// Widgets ------------------------------------------------------------------------------------- //
    /**
     * Adicionar um widget à página inicial.
     * 
     * @param string $id Identificador desse widget. Esse ID também poderá ser usado para identificar
     * o container HTML do widget.
     * @param string $url URL a ser executada para gerar o conteúdo HTML desse widget. Essa URL será
     * executada via AJAX.
     * @param string $titulo Título do widget.
     */
    public static function adicionarWidgetHome($id, $url, $titulo = null) {
        self::$widgets[$id] = [
            'url' => $url,
            'titulo' => $titulo
        ];
    } // Fim do método adicionarWidgetHome

    /**
     * Remover um widget da página inicial.
     * @return void
     */
    public static function removerWidgetHome($id) {
        unset(self::$widgets[$id]);
    } // Fim do método removerWidgetHome


    public static function carregarConfguracoesWidgets() {
        $diretorio = __DIR__ . '/widgets/';
        $arquivos = array_filter(
            scandir($diretorio),
            function ($v) use ($diretorio) { return is_file("{$diretorio}{$v}"); }
        );

        foreach ($arquivos as $arquivo) {
            include_once "{$diretorio}{$arquivo}";
        } // Fim foreach
    } // Fim do método carregarConfguracoesWidgets
} // Fim do controle Home
