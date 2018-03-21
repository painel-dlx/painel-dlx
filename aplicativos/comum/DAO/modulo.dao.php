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

trait Modulo {
    /**
     * ID do módulo pai desse sub-módulo
     * @var int|null
     */
    protected $pai;

    /**
     * Nome do aplicativo (diretório) do qual esse módulo faz parte
     * @var string
     */
    protected $aplicativo = 'painel-dlx';

    /**
     * Nome do módulo
     * @var string
     */
    protected $nome;

    /**
     * Descrição do módulo
     * @var string
     */
    protected $descr;

    /**
     * Define se o módulo será exibido no menu principal do aplicativo (TRUE) ou
     * não (FALSE)
     * @var boolean
     */
    protected $exibir_menu = true;

    /**
     * Link para direcionar o usuário a esse módulo. Será exigido apenas se $exibir_menu
     * for definido como TRUE
     * @var string
     */
    protected $link;

    /**
     * Ordem de exibição do módulo no menu
     * @var int
     */
    protected $ordem = 0;

    public function getPai() {
        return $this->pai;
    }

    public function setPai($pai) {
        $this->pai = filter_var($pai, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
            'flags'   => FILTER_NULL_ON_FAILURE
        ]);
    }

    public function getAplicativo() {
        return $this->aplicativo;
    }

    public function setAplicativo($aplicativo) {
        $this->aplicativo = filter_var(strtolower($aplicativo), FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = filter_var($nome, FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
    }

    public function getDescr() {
        return $this->descr;
    }

    public function setDescr($descr) {
        $this->descr = filter_var($descr);
    }

    public function isExibirMenu() {
        return (bool)$this->exibir_menu;
    }

    public function setExibirMenu($exibir_menu) {
        $this->exibir_menu = filter_var($exibir_menu, FILTER_VALIDATE_BOOLEAN);
    }

    public function getLink() {
        return $this->link;
    }

    public function setLink($link) {
        $this->link = filter_var(strtolower($link), FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
    }

    public function getOrdem() {
        return $this->ordem;
    }

    public function setOrdem($ordem) {
        $this->ordem = filter_var($ordem, FILTER_VALIDATE_INT, [
            'options' => ['default' => 0]
        ]);
    }
}
