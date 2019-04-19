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

namespace PainelDLX\Testes\Application\UseCases\Usuarios\NovoUsuario;

use PainelDLX\Application\UseCases\Usuarios\NovoUsuario\NovoUsuarioCommand;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Testes\TestCase\PainelDLXTestCase;
use PHPUnit\Framework\TestCase;

class NovoUsuarioCommandTest extends PainelDLXTestCase
{
    /** @var NovoUsuarioCommand */
    private $command;

    protected function setUp()
    {
        parent::setUp();
        $this->command = new NovoUsuarioCommand(new Usuario('Teste', 'teste@teste.com.br'), '123456');
    }

    public function test_GetSenhaConfirm_deve_retornar_uma_string()
    {
        $this->assertIsString($this->command->getSenhaConfirm());
    }

    public function test_GetUsuario_deve_retornar_uma_instancia_Usuario()
    {
        $this->assertInstanceOf(Usuario::class, $this->command->getUsuario());
    }
}
