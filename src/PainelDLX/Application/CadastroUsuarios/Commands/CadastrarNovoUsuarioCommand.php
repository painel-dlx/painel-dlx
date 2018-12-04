<?php
/**
 * MIT License
 *
 * Copyright (c) 2018 PHP DLX
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace PainelDLX\Application\CadastroUsuarios\Commands;


use DLX\Contracts\CommandInterface;

class CadastrarNovoUsuarioCommand implements CommandInterface
{
    /** @var string */
    private $nome;
    /** @var string */
    private $email;
    /** @var string */
    private $senha;
    /** @var string */
    private $senha_confirm;

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return CadastrarNovoUsuarioCommand
     */
    public function setNome(string $nome): CadastrarNovoUsuarioCommand
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return CadastrarNovoUsuarioCommand
     */
    public function setEmail(string $email): CadastrarNovoUsuarioCommand
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getSenha(): string
    {
        return $this->senha;
    }

    /**
     * @param string $senha
     * @return CadastrarNovoUsuarioCommand
     */
    public function setSenha(string $senha): CadastrarNovoUsuarioCommand
    {
        $this->senha = $senha;
        return $this;
    }

    /**
     * @return string
     */
    public function getSenhaConfirm(): string
    {
        return $this->senha_confirm;
    }

    /**
     * @param string $senha_confirm
     * @return CadastrarNovoUsuarioCommand
     */
    public function setSenhaConfirm(string $senha_confirm): CadastrarNovoUsuarioCommand
    {
        $this->senha_confirm = $senha_confirm;
        return $this;
    }

    /**
     * Request completa do comando
     * @return array Retorna um array associativo. A chave é o nome da propriedade e o valor seu respectivo valor
     */
    public function getRequest(): array
    {
        return [
            'nome' => $this->getNome(),
            'email' => $this->getEmail(),
            'senha' => $this->getSenha(),
            'senha_confirm' => $this->getSenhaConfirm()
        ];
    }
}