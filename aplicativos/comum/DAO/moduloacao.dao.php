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

namespace Comum\DAO;

trait ModuloAcao {
    /**
     * ID do módulo do qual essa ação faz parte
     * @var int
     */
    protected $modulo;

    /**
     * Descrição da ação que pode ser executada
     * @var string
     */
    protected $descr;

    /**
     * Nome do controle que possui as ações
     * @var string
     */
    protected $classe;

    /**
     * Nomes dos método necessários para realizar essa ação
     * @var string
     */
    protected $metodos;

    /**
     * Vetor com os IDs dos grupos que tem permissão para executar essa ação
     * @var array
     */
    protected $grupos = [];


    public function getModulo() {
        return $this->modulo;
    }

    public function setModulo($modulo) {
        $this->modulo = filter_var($modulo, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
            'falgs'   => FILTER_NULL_ON_FAILURE
        ]);
    }

    public function getDescr() {
        return $this->descr;
    }

    public function setDescr($descr) {
        $this->descr = filter_var($descr, FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
    }

    public function getClasse() {
        return $this->classe;
    }

    public function setClasse($classe) {
        $this->classe = filter_var($classe, FILTER_VALIDATE_REGEXP, [
            'options' => ['regexp' => '~([\w]+\\[\w]+)?~'],
            'flags'   => FILTER_NULL_ON_FAILURE
        ]);
    }

    public function getMetodos() {
        return $this->metodos;
    }

    public function setMetodos($metodos) {
        $this->metodos = filter_var($metodos, FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
    }

    public function getGrupos() {
        return $this->grupos;
    }

    public function setGrupos($grupos) {
        $this->grupos = filter_var($grupos, FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
    }
}
