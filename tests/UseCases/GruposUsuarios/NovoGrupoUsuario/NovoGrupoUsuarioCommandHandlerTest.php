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

namespace PainelDLX\Testes\Application\UseCases\GruposUsuarios\NovoGrupoUsuario;

use Exception;
use PainelDLX\Domain\GruposUsuarios\Validators\AliasUtilizadoValidator;
use PainelDLX\UseCases\GruposUsuarios\NovoGrupoUsuario\NovoGrupoUsuarioCommand;
use PainelDLX\UseCases\GruposUsuarios\NovoGrupoUsuario\NovoGrupoUsuarioCommandHandler;
use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;
use PainelDLX\Domain\GruposUsuarios\Repositories\GrupoUsuarioRepositoryInterface;
use PHPUnit\Framework\TestCase;

class NovoGrupoUsuarioCommandHandlerTest extends TestCase
{
    /**
     * @return void
     * @throws Exception
     */
    public function test_Handle_deve_preencher_informacoes_Usuario_e_salvar_no_bd()
    {
        $grupo_usuario_repository = $this->createMock(GrupoUsuarioRepositoryInterface::class);
        $validator = $this->createMock(AliasUtilizadoValidator::class);

        /** @var GrupoUsuarioRepositoryInterface $grupo_usuario_repository */
        /** @var AliasUtilizadoValidator $validator */

        $nome = 'Teste 123';
        $command = new NovoGrupoUsuarioCommand($nome);
        $grupo_usuario = (new NovoGrupoUsuarioCommandHandler($grupo_usuario_repository, $validator))->handle($command);

        $this->assertInstanceOf(GrupoUsuario::class, $grupo_usuario);
        $this->assertEquals($nome, $grupo_usuario->getNome());
    }
}
