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

namespace PainelDLX\Testes\Application\UseCases\GruposUsuarios\ConfigurarPermissoes;

use Doctrine\Common\Collections\ArrayCollection;
use PainelDLX\UseCases\GruposUsuarios\ConfigurarPermissoes\ConfigurarPermissoesCommand;
use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;
use PainelDLX\Testes\TestCase\PainelDLXTestCase;

class ConfigurarPermissoesCommandTest extends PainelDLXTestCase
{
    /**
     * @var ConfigurarPermissoesCommand
     */
    private $command;

    protected function setUp()
    {
        parent::setUp();

        $this->command = new ConfigurarPermissoesCommand(new GrupoUsuario(), new ArrayCollection());
    }

    public function test_GetPermissoes_deve_retornar_uma_ArrayCollection()
    {
        $this->assertInstanceOf(ArrayCollection::class, $this->command->getPermissoes());
    }

    public function test_GetGrupoUsuario_deve_retornar_um_GrupoUsuario()
    {
        $this->assertInstanceOf(GrupoUsuario::class, $this->command->getGrupoUsuario());
    }
}
