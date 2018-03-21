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

use Comum\DAO\Tema as TemaDAO;
use DLX\Ajudantes\Arquivos;
use DLX\Ajudantes\Visao as AjdVisao;
use DLX\Ajudantes\Vetores;
use Geral\Modelos\BaseModelo;
// use Geral\Modelos\RegistroConsulta;
// use Geral\Modelos\RegistroEdicao;

class Tema extends BaseModelo {
    use TemaDAO;

    public function __construct ($pk = null) {
        $this->set__NomeModelo(AjdVisao::traduzirTexto('tema', 'painel-dlx'));
        $this->selecionarPK($pk);
    } // Fim do método __construct


    /**
     * Gerar uma lista com os temas instalados no sistema
     * @return array Vetor multidimensional com as informações dos temas
     */
    public function listar() {
        $lista = [];
        $temas = preg_grep('~^\.~', scandir(\DLX::DIR_TEMAS), PREG_GREP_INVERT);

        foreach ($temas as $dir) {
            $config = \DLX::DIR_TEMAS . "{$dir}/config.json";

            if (file_exists($config)) {
                $abrir = fopen($config, 'r');
                $json = json_decode(fread($abrir, filesize($config)), true);
                fclose($abrir);

                $lista[] = [
                    'ID' => $dir, // O ID é necessário para gerar a lista
                    'Data da criação' => date('d/m/Y H:i', filemtime($config))
                ] + $json + ['Diretório' => $dir];
            } // Fim if
        } // Fim foreach

        return $lista;
    } // Fim do método listar


    /**
     * Obter a quantidade de temas instalados
     * @return int
     */
    public function qtdeRegistros() {
        return count($this->listar());
    } // Fim do método qtdeRegistros


    /**
     * Selecionar as informações de um tema através da sua Primary Key. No caso
     * dos temas, a PK é considerada como o diretório onde está instalado
     * @param  string $pk Nome do diretório de instalação do tema
     * @return boolean Retorna TRUE se as informações foram selecionadas e carregadas
     * ou FALSE se não foram
     */
    public function selecionarPK($pk) {
        $config = \DLX::DIR_TEMAS . "{$pk}/config.json";

        if (file_exists($config)) {
            $abrir = fopen($config, 'r');
            $json = json_decode(fread($abrir, filesize($config)), true);
            fclose($abrir);

            $this->setID($pk);
            $this->setNome($json['Nome']);
            $this->setPaginaMestra($json['Página mestra']);
            $this->setDiretorio($pk);

            # Retorna true
            return !($this->reg_vazio = false);
        } // Fim if

        # Retorna false
        return !($this->reg_vazio = true);
    } // Fim do método selecionarPK


    /**
     * Selecionar informações para incluir em um select
     * @param  boolean $json Se TRUE 'printa' o resultado na tela, no formato JSON
     * @return array Retorna um vetor contendo as informações do tema
     */
    public function carregarSelect($json = true) {
        $lista = [];
        $temas = preg_grep('~^\.~', scandir(\DLX::DIR_TEMAS), PREG_GREP_INVERT);

        foreach ($temas as $dir) {
            $config = \DLX::DIR_TEMAS . "{$dir}/config.json";

            if (file_exists($config)) {
                $abrir = fopen($config, 'r');
                $json = json_decode(fread($abrir, filesize($config)), true);
                fclose($abrir);

                $lista[] = ['VALOR' => $dir, 'TEXTO' => $json['Nome']];
            } // Fim if
        } // Fim foreach

        if ($escrever) {
            echo json_encode($lista);
        } // Fim if

        return $lista;
    } // Fim do método carregarSelect


    /**
     * Excluir um tema
     * @return boolean Retorna TRUE em caso de sucesso ou FALSE em caso de falha
     */
    protected function excluir() {
        if (!$this->reg_vazio) {
            Arquivos::removerDir(\DLX::DIR_TEMAS . $this->getDiretorio());
        } // Fim if
    } // Fim do método excluir
} // Fim do modelo Tema
