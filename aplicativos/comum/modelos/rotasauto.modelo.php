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

namespace Comum\Modelos;

trait RotasAuto {
    /**
     * Adicionar eventos para atualizar as rotas automaticamente.
     * Para funcionar é necessário criar o método atualizarRotas, que deve chamar o gerarRotasAuto
     * dessa trait.
     *
     * @return void
     */
    private function adicionarEventosRotas() {
        if (method_exists($this, 'atualizarRotas')) {
            $this->adicionarEvento('depois', 'salvar', function () {
                $this->atualizarRotas();
            });

            $this->adicionarEvento('depois', 'excluir', function () {
                $this->atualizarRotas();
            });
        } // Fim if
    } // Fim do método adicionarEventos

    /**
     * Gerar arquivo de rotas automaticamente.
     *
     * @param string $arquivo   Caminho do arquivo a ser gerado / alterada.
     * @param string $rota      Padrão da rota a ser criada: ^teste/:url$ onde :url é a parte dinâmica da rota.
     * @param array $registros  Array multidimensional com as informações a serem usadas nas rotas:
     *                          ['URL' => 'parte dinâmica da rota', 'ID' => 'ID a ser passado como parâmetro pela rota']
     * @param string $aplicativo Nome do aplicativo. 
     * @param [type] $modulo    Nome do módulo.
     * @param [type] $controle  Nome do controle.
     * @param string $acao      Nome da ação a ser executada.
     * @return void
     */
    private function gerarRotasAuto($arquivo, $rota, $registros, $aplicativo, $modulo, $controle, $acao = 'mostrarDetalhes') {
        $a_rota = fopen($arquivo, 'w');
        $conteudo = "<?php\n/** ARQUIVO GERADO AUTOMATICAMENTE // NÃO ALTERAR */\n\n";

        foreach ($registros as $r) {
            $rota_url = str_replace(':url', $r['URL'], $rota);
            $conteudo .= <<<ROTA
\DLX::\$dlx->adicionarRota('{$rota_url}', [
    'aplicativo' => '{$aplicativo}',
    'modulo'     => '{$modulo}',
    'controle'   => '{$controle}',
    'acao'       => '{$acao}',
    'params'     => ['pk' => {$r['ID']}]
]);\n\n
ROTA;
        } // Fim foreach

        fwrite($a_rota, $conteudo);
        fclose($a_rota);
    } // Fim do método gerarRotasAuto
}