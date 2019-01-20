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

namespace PainelDLX\Testes\Application\UseCases\PermissoesUsuario;

use PainelDLX\Application\UseCases\PermissoesUsuario\GetListaPermissaoUsuario\GetListaPermissaoUsuarioCommand;
use PainelDLX\Testes\PainelDLXTests;

class GetListaPermissaoUsuarioCommandTest extends PainelDLXTests
{
    /** @var GetListaPermissaoUsuarioCommand */
    private $command;

    protected function setUp()
    {
        parent::setUp();

        $this->command = new GetListaPermissaoUsuarioCommand(
            ['teste' => 'teste'],
            [],
            2,
            1
        );
    }

    public function test_GetOffset()
    {
        $this->assertEquals(2, $this->command->getOffset());
    }

    public function test_GetOrderBy()
    {
        $this->assertCount(0, $this->command->getOrderBy());
    }

    public function test_GetLimit()
    {
        $this->assertEquals(1, $this->command->getLimit());
    }

    public function test_GetCriteria()
    {
        $this->assertEquals(['teste' => 'teste'], $this->command->getCriteria());
    }
}
