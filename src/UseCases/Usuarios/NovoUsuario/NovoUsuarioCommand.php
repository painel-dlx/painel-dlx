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

namespace PainelDLX\UseCases\Usuarios\NovoUsuario;

/**
 * Class NovoUsuarioCommand
 * @package PainelDLX\UseCases\Usuarios\NovoUsuario
 */
class NovoUsuarioCommand
{
    /**
     * @var string
     */
    private $nome;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $senha;
    /**
     * @var string
     */
    private $senha_confirm;
    /**
     * @var array
     */
    private $grupos;

    /**
     * NovoUsuarioCommand constructor.
     * @param string $nome
     * @param string $email
     * @param string $senha
     * @param string $senha_confirm
     * @param array $grupos
     */
    public function __construct(
        string $nome,
        string $email,
        string $senha,
        string $senha_confirm,
        array $grupos
    ) {
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        $this->senha_confirm = $senha_confirm;
        $this->grupos = $grupos;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getSenha(): string
    {
        return $this->senha;
    }

    /**
     * @return string
     */
    public function getSenhaConfirm(): string
    {
        return $this->senha_confirm;
    }

    /**
     * @return array
     */
    public function getGrupos(): array
    {
        return $this->grupos;
    }
}