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

trait Tema {
    /**
     * Identificador do registro
     * @var string
     */
    protected $id;

    /**
     * Nome do tema
     * @var string
     */
    protected $nome;

    /**
     * Nome da página mestra principal a ser carregada para aplicar o tema
     * @var string
     */
    protected $pagina_mestra = 'padrao';

    /**
     * Diretório onde está instalado o tema. Esse diretório deve estar dentro
     * do diretório de temas do Framework DLXs
     * @var string
     */
    protected $diretorio;

    public function getID() {
        return $this->id;
    }

    public function setID($id) {
        $this->id = filter_var(strtolower($id), FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = filter_var($nome, FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
    }

    public function getPaginaMestra() {
        return $this->pagina_mestra;
    }

    public function setPaginaMestra($pagina_mestra) {
        $this->pagina_mestra = filter_var($pagina_mestra, FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
    }

    public function getDiretorio() {
        return $this->diretorio;
    }

    public function setDiretorio($diretorio) {
        $this->diretorio = filter_var($diretorio, FILTER_VALIDATE_REGEXP, [
            'options' => ['regexp' => '~([\w\-]+/?)+~'],
            'flags'   => FILTER_NULL_ON_FAILURE
        ]);
    }
}
