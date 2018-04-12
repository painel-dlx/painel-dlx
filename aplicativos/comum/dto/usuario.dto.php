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

trait Usuario {
    /**
     * ID do grupo de usuário ao qual esse usuário faz parte
     * @var int
     */
    protected $grupo;

    /**
     * Nome do idioma
     * @var string
     */
    protected $nome;

    /**
     * E-mail para contato, login e recuperação de senha
     * @var string
     */
    protected $email;

    /**
     * Nome de usuário utilizado para fazer login no sistema
     * @var string
     */
    protected $login;

    /**
     * HASH da senha
     * @var string
     */
    protected $senha;

    /**
     * HASH da confirmação da senha
     * @var string
     */
    protected $conf_senha;

    /**
     * ID do idioma preferencial do usuário.
     * Obs: O nome da propriedade NÃO pode ser apenas $idioma, pois já tem essa propriedade criada no
     * BaseModeloRegistro.
     * @var int
     */
    protected $pref_idioma;

    /**
     * Nome do tema (diretório do tema)
     * @var string
     */
    protected $tema = 'padrao';

    /**
     * ID do formato de data de preferência desse usuário
     * @var int
     */
    protected $formato_data;

    /**
     * Define se o usuário está bloqueado
     * @var boolean
     */
    protected $bloqueado = false;

    /**
     * Define se o usuário deve ser forçado a resetar sua a senha no próximo login
     * @var boolean
     */
    protected $reset_senha = false;

    public function getGrupo() {
        return $this->grupo;
    }

    public function setGrupo($grupo) {
        $this->grupo = filter_var($grupo, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
            'flags'   => FILTER_NULL_ON_FAILURE
        ]);
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = filter_var($nome, FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = filter_var($email, FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE);
    }

    public function getLogin() {
        return $this->login;
    }

    public function setLogin($login) {
        $this->login = filter_var($login, FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
    }

    public function getSenha() {
        return $this->senha;
    }


    /**
     * Definir um valor para a propriedade $senha
     * @param string    $senha  Valor a ser atribuído para a propriedade senha
     * @param bool|null $cripto Quando TRUE criptografa a senha usando a função
     * de criptografia informada nas configurações do aplicativo.
     */
    public function setSenha($senha, $cripto = null) {
        if (!isset($cripto)) {
            // Forçar a criptografia da senha, caso ela tenha sido informada em
            // um formulário
            $cripto = filter_var($_SERVER['REQUEST_METHOD']) === 'POST' && array_key_exists('senha', $_POST);
        } // Fim if

        $this->senha = $cripto && method_exists($this, 'criptografarSenha')
            ? $this->criptografarSenha($senha) : filter_var($senha);
    }

    public function getConfSenha() {
        return $this->conf_senha;
    }


    /**
     * Definir um valor para a propriedade $conf_senha
     * @param string    $conf_senha  Valor a ser atribuído para a propriedade senha
     * @param bool|null $cripto      Quando TRUE criptografa a senha usando a função
     * de criptografia informada nas configurações do aplicativo.
     */
    public function setConfSenha($conf_senha, $cripto = null) {
        if (!isset($cripto)) {
            // Forçar a criptografia da senha, caso ela tenha sido informada em
            // um formulário
            $cripto = filter_var($_SERVER['REQUEST_METHOD']) === 'POST' && array_key_exists('senha', $_POST);
        } // Fim if

        $this->conf_senha = $cripto && method_exists($this, 'criptografarSenha')
            ? $this->criptografarSenha($conf_senha) : filter_var($conf_senha);
    }

    public function getPrefIdioma() {
        return $this->pref_idioma;
    }

    public function setPrefIdioma($pref_idioma) {
        $this->pref_idioma = filter_var($pref_idioma, FILTER_VALIDATE_REGEXP, [
            'options' => ['regexp' => EXPREG_IDIOMA, 'default' => 'br']
        ]);
    }

    public function getTema() {
        return $this->tema;
    }

    public function setTema($tema) {
        $this->tema = filter_var(strtolower($tema), FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
    }

    public function getFormatoData() {
        return $this->formato_data;
    }

    public function setFormatoData($formato_data) {
        $this->formato_data = filter_var($formato_data, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
            'flags'   => FILTER_NULL_ON_FAILURE
        ]);
    }

    public function isBloqueado() {
        return (bool)$this->bloqueado;
    }

    public function setBloqueado($bloqueado) {
        $this->bloqueado = filter_var($bloqueado, FILTER_VALIDATE_BOOLEAN);
    }

    public function isResetSenha() {
        return (bool)$this->reset_senha;
    }

    public function setResetSenha($reset_senha) {
        $this->reset_senha = filter_var($reset_senha, FILTER_VALIDATE_BOOLEAN);
    }
}
