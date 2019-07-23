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

namespace PainelDLX\Tests\UseCases\GruposUsuarios\GetGrupoUsuarioPorId;

use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;
use PainelDLX\Domain\GruposUsuarios\Exceptions\GrupoUsuarioNaoEncontradoException;
use PainelDLX\Domain\GruposUsuarios\Repositories\GrupoUsuarioRepositoryInterface;
use PainelDLX\UseCases\GruposUsuarios\GetGrupoUsuarioPorId\GetGrupoUsuarioPorIdCommand;
use PainelDLX\UseCases\GruposUsuarios\GetGrupoUsuarioPorId\GetGrupoUsuarioPorIdCommandHandler;
use PHPUnit\Framework\TestCase;

/**
 * Class GetGrupoUsuarioPorIdCommandHandlerTest
 * @package PainelDLX\Tests\UseCases\GruposUsuarios\GetGrupoUsuarioPorId
 * @coversDefaultClass \PainelDLX\UseCases\GruposUsuarios\GetGrupoUsuarioPorId\GetGrupoUsuarioPorIdCommandHandler
 */
class GetGrupoUsuarioPorIdCommandHandlerTest extends TestCase
{
    /**
     * @throws GrupoUsuarioNaoEncontradoException
     * @covers ::handle
     */
    public function test_Handle_deve_lancar_uma_excecao_quando_nao_encontrar_registro_no_bd()
    {
        $grupo_usuario_repository = $this->createMock(GrupoUsuarioRepositoryInterface::class);
        $grupo_usuario_repository->method('find')->willReturn(null);

        /** @var GrupoUsuarioRepositoryInterface $grupo_usuario_repository */

        $this->expectException(GrupoUsuarioNaoEncontradoException::class);
        $this->expectExceptionCode(10);

        $command = new GetGrupoUsuarioPorIdCommand(0);
        (new GetGrupoUsuarioPorIdCommandHandler($grupo_usuario_repository))->handle($command);
    }

    /**
     * @covers ::handle
     * @throws GrupoUsuarioNaoEncontradoException
     */
    public function test_Handle_deve_retornar_GrupoUsuario_quando_encontrar_registro_no_bd()
    {
        $grupo_usuario_id = mt_rand();

        $grupo_usuario = $this->createMock(GrupoUsuario::class);
        $grupo_usuario->method('getId')->willReturn($grupo_usuario_id);

        $grupo_usuario_repository = $this->createMock(GrupoUsuarioRepositoryInterface::class);
        $grupo_usuario_repository->method('find')->willReturn($grupo_usuario);

        /** @var GrupoUsuarioRepositoryInterface $grupo_usuario_repository */

        $command = new GetGrupoUsuarioPorIdCommand($grupo_usuario_id);
        $grupo_usuario_retornado = (new GetGrupoUsuarioPorIdCommandHandler($grupo_usuario_repository))->handle($command);

        $this->assertInstanceOf(GrupoUsuario::class, $grupo_usuario_retornado);
        $this->assertEquals($grupo_usuario_id, $grupo_usuario_retornado->getId());
    }
}
