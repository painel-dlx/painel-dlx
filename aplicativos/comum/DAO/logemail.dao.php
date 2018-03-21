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

trait LogEmail {
    /**
     * @var string $classe
     * Nome da classe que fez o envio do e-mail
     */
    protected $classe;

    /**
     * @var string $assunto
     * Valor utilizado no campo 'Assunto' do e-mail
     */
    protected $assunto;

    /**
     * @var string $para
     * Endereços de e-mail adicionados no campo 'To'
     */
    protected $para;

    /**
     * @var string $copia
     * Endereços de e-mail adicionados no campo 'Copy to'
     */
    protected $copia;

    /**
     * @var string $copia_oculta
     * Endereços de e-mail adicionados no campo 'Hidden copy to'
     */
    protected $copia_oculta;

    /**
     * @var string $corpo
     * Corpo do e-mail / mensagem. Pode ser texto puro ou HTML
     */
    protected $corpo;

    /**
     * @var string $status
     * Status do envio. Em caso de sucesso, esse campo ficará vazio.
     * Em caso de falha, a mensagem de erro será armazenada nesse campo
     */
    protected $status;

    public function getClasse() {
        return $this->classe;
    }

    public function setClasse($classe) {
        $this->classe = filter_var($classe);
    }

    public function getAssunto($assunto) {
        return $this->assunto;
    }

    public function setAssunto($assunto) {
        $this->assunto = filter_var($assunto, FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
    }

    public function getPara() {
        return $this->para;
    }

    public function setPara($para) {
        $this->para = filter_var($para, FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
    }

    public function getCopia() {
        return $this->copia;
    }

    public function setCopia($copia) {
        $this->copia = filter_var($copia, FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
    }

    public function getCopiaOculta() {
        return $this->copia_oculta;
    }

    public function setCopiaOculta($copia_oculta) {
        $this->copia_oculta = filter_var($copia_oculta, FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
    }

    public function getCorpo() {
        return $this->corpo;
    }

    public function setCorpo($corpo) {
        $this->corpo = filter_var($corpo);
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = filter_var($status, FILTER_DEFAULT, FILTER_FLAG_EMPTY_STRING_NULL);
    }
}