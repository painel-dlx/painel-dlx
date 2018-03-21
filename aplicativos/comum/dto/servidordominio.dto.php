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

namespace Comum\DTO;

trait ServidorDominio {
    /**
     * Nome do domínio. Ex: dominio.local
     * @var string
     */
    protected $dominio;

    /**
     * IP ou hostname do servidor
     * @var string
     */
    protected $host;

    /**
     * Porta de conexão ao servidor pelo protocolo LDAP
     * @var int
     */
    protected $porta = 389;

    /**
     * Define se esse servidor está ativo ou não. Servidores inativos não serão
     * permitidos para login
     * @var boolean
     */
    protected $ativo = true;

    public function getDominio() {
        return $this->dominio;
    }

    public function setDominio($dominio) {
        $this->dominio = filter_var($dominio, FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
    }

    public function getHost() {
        return $this->host;
    }

    public function setHost($host) {
        $this->host = filter_var($host, FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
    }

    public function getPorta() {
        return $this->porta;
    }

    public function setPorta($porta) {
        $this->porta = filter_var($porta, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0, 'max_range' => 65535, 'default' => 389]
        ]);
    }

    public function isAtivo() {
        return (bool)$this->ativo;
    }

    public function setAtivo($ativo) {
        $this->ativo = filter_var($ativo, FILTER_VALIDATE_BOOLEAN);
    }
}
