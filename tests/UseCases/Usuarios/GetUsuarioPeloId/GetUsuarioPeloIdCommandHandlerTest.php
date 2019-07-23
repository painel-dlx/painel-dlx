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

namespace PainelDLX\Tests\UseCases\Usuarios\GetUsuarioPeloId;

use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioNaoEncontradoException;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;
use PainelDLX\UseCases\Usuarios\GetUsuarioPeloId\GetUsuarioPeloIdCommand;
use PainelDLX\UseCases\Usuarios\GetUsuarioPeloId\GetUsuarioPeloIdCommandHandler;
use PHPUnit\Framework\TestCase;

/**
 * Class GetUsuarioPeloIdCommandHandlerTest
 * @package PainelDLX\Tests\UseCases\Usuarios\GetUsuarioPeloId
 * @coversDefaultClass \PainelDLX\UseCases\Usuarios\GetUsuarioPeloId\GetUsuarioPeloIdCommandHandler
 */
class GetUsuarioPeloIdCommandHandlerTest extends TestCase
{
    /**
     * @covers ::handle
     * @throws UsuarioNaoEncontradoException
     */
    public function test_Handle_deve_lancar_excecao_quando_nao_encontrar_registro_no_bd()
    {
        $usuario_repository = $this->createMock(UsuarioRepositoryInterface::class);
        $usuario_repository->method('find')->willReturn(null);

        /** @var UsuarioRepositoryInterface $usuario_repository */

        $this->expectException(UsuarioNaoEncontradoException::class);
        $this->expectExceptionCode(10);

        $command = new GetUsuarioPeloIdCommand(0);
        (new GetUsuarioPeloIdCommandHandler($usuario_repository))->handle($command);
    }

    /**
     * @covers ::handle
     * @throws UsuarioNaoEncontradoException
     */
    public function test_Handle_deve_retornar_Usuario_quando_encontrar_registro_no_bd()
    {
        $usuario_id = mt_rand();

        $usuario = $this->createMock(Usuario::class);
        $usuario->method('getId')->willReturn($usuario_id);

        $usuario_repository = $this->createMock(UsuarioRepositoryInterface::class);
        $usuario_repository->method('find')->willReturn($usuario);

        /** @var UsuarioRepositoryInterface $usuario_repository */

        $command = new GetUsuarioPeloIdCommand($usuario_id);
        $usuario_retornado = (new GetUsuarioPeloIdCommandHandler($usuario_repository))->handle($command);

        $this->assertInstanceOf(Usuario::class, $usuario_retornado);
        $this->assertEquals($usuario_id, $usuario_retornado->getId());
    }
}
