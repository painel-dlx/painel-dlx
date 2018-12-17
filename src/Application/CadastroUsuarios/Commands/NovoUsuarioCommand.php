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
use PainelDLX\Domain\CadastroUsuarios\Entities\Usuario;

class NovoUsuarioCommand implements CommandInterface
{
    /** @var Usuario */
    private $usuario;
    /** @var string */
    private $senha_confirm;

    /**
     * @return Usuario
     */
    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    /**
     * @return string
     */
    public function getSenhaConfirm(): string
    {
        return $this->senha_confirm;
    }

    /**
     * NovoUsuarioCommand constructor.
     * @param Usuario $usuario
     * @param string $senha_confirm
     */
    public function __construct(Usuario $usuario, string $senha_confirm)
    {
        $this->usuario = $usuario;
        $this->senha_confirm = $senha_confirm;
    }

    /**
     * Request completa do comando
     * @return array Retorna um array associativo. A chave é o nome da propriedade e o valor seu respectivo valor
     */
    public function getRequest(): array
    {
        return [
            'usuario' => $this->getUsuario(),
            'senha_confirm' => $this->getSenhaConfirm()
        ];
    }
}