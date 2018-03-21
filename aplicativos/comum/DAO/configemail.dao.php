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

trait ConfigEmail {
    /**
     * Hostname ou IP do servidor de envio de emails
     * @var string
     */
    protected $servidor = 'localhost';

    /**
     * Porta de conexão ao servidor de envio de e-mails
     * @var int
     */
    protected $porta = 25;

    /**
     * Define se o servidor requer autenticação
     * @var bool
     */
    protected $autent = false;

    /**
     * Tipo de criptografia utilizada. NULL ou '' significam que não deve ser utilizado
     * nenhum tipo de criptografia
     * @var string
     */
    protected $cripto;

    /**
     * Conta utilizada para realizar a autenticação no servidor SMTP
     * @var string
     */
    protected $conta;

    /**
     * Senha utilizada para realizar a autenticação no servidor SMTP
     * @var string
     */
    protected $senha;

    /**
     * E-mail para receber a resposta do e-mail. Será configurado no header Reply-to
     * @var string
     */
    protected $responder_para;

    /**
     * Nome a ser exibido para que recebe o e-mail
     * @var string
     */
    protected $de_nome;

    /**
     * Define se o corpo do e-mail deve ser formatado como HTML (TRUE) ou texto
     * puro (FALSE)
     * @var bool
     */
    protected $html = false;


    public function getServidor() {
        return $this->servidor;
    }

    public function setServidor($servidor) {
        $this->servidor = filter_var($servidor, FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
    }

    public function getPorta() {
        return $this->porta;
    }

    public function setPorta($porta) {
        $this->porta = filter_var($porta, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 65535, 'default' => 25]
        ]);
    }

    public function isAutent() {
        return (bool)$this->autent;
    }

    public function setAutent($autent) {
        $this->autent = filter_var($autent, FILTER_VALIDATE_BOOLEAN);
    }

    public function getCripto() {
        return $this->cripto;
    }

    public function setCripto($cripto) {
        $this->cripto = filter_var($cripto, FILTER_VALIDATE_REGEXP, [
            'options' => ['regexp' => '~^(tls|ssl)$~', 'default' => null]
        ]);
    }

    public function getConta() {
        return $this->conta;
    }

    public function setConta($conta) {
        $this->conta = filter_var($conta, FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
    }

	public function getSenha() {
		return $this->senha;
	}

	public function setSenha($senha) {
        # Por segurança a senha é colocada no formulário como '*****', para não
        # expor a senha correta. Portanto, quando essa senha for informada,
        # significa que ela não foi alterada no formulário e não deve ser alterada
        # no UPDATE / modelo
        if ($senha !== '*****') {
    		$this->senha = filter_var($senha);
        } // Fim if
	}

	public function getResponderPara() {
		return $this->responder_para;
	}

	public function setResponderPara($responder_para) {
		$this->responder_para = filter_var($responder_para, FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE);
	}

	public function getDeNome() {
		return $this->de_nome;
	}

	public function setDeNome($de_nome) {
		$this->de_nome = filter_var($de_nome, FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
	}

	public function isHtml() {
		return (bool)$this->html;
	}

	public function setHtml($html) {
		$this->html = filter_var($html, FILTER_VALIDATE_BOOLEAN);
	}
}
