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

namespace PainelDLX\Tests\UseCases\PermissoesUsuario\GetPermissaoUsuarioPorId;

use DLX\Infrastructure\EntityManagerX;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMException;
use PainelDLX\Domain\PermissoesUsuario\Entities\PermissaoUsuario;
use PainelDLX\Domain\PermissoesUsuario\Exceptions\PermissaoUsuarioNaoEncontradaException;
use PainelDLX\Domain\PermissoesUsuario\Repositories\PermissaoUsuarioRepositoryInterface;
use PainelDLX\UseCases\PermissoesUsuario\GetPermissaoUsuarioPorId\GetPermissaoUsuarioPorIdCommand;
use PainelDLX\UseCases\PermissoesUsuario\GetPermissaoUsuarioPorId\GetPermissaoUsuarioPorIdCommandHandler;
use PHPUnit\Framework\TestCase;

/**
 * Class GetPermissaoUsuarioPorIdCommandHandlerTest
 * @package PainelDLX\Tests\UseCases\PermissoesUsuario\GetPermissaoUsuarioPorId
 * @coversDefaultClass \PainelDLX\UseCases\PermissoesUsuario\GetPermissaoUsuarioPorId\GetPermissaoUsuarioPorIdCommandHandler
 */
class GetPermissaoUsuarioPorIdCommandHandlerTest extends TestCase
{
    /**
     * @covers ::handle
     * @throws PermissaoUsuarioNaoEncontradaException
     */
    public function test_Handle_deve_lancar_excecao_quando_nao_encontrar_registro_bd()
    {
        $permissao_usuario_repository = $this->createMock(PermissaoUsuarioRepositoryInterface::class);
        $permissao_usuario_repository->method('find')->willReturn(null);

        /** @var PermissaoUsuarioRepositoryInterface $permissao_usuario_repository */

        $this->expectException(PermissaoUsuarioNaoEncontradaException::class);
        $this->expectExceptionCode(10);

        $command = new GetPermissaoUsuarioPorIdCommand(0);
        (new GetPermissaoUsuarioPorIdCommandHandler($permissao_usuario_repository))->handle($command);
    }

    /**
     * @throws PermissaoUsuarioNaoEncontradaException
     * @covers ::handle
     */
    public function test_Handle_deve_retornar_PermissaoUsuario_quando_encontrar_registro_bd()
    {
        $permissao_usuario_id = mt_rand();

        $permissao_usuario = $this->createMock(PermissaoUsuario::class);
        $permissao_usuario->method('getId')->willReturn($permissao_usuario_id);

        $permissao_usuario_repository = $this->createMock(PermissaoUsuarioRepositoryInterface::class);
        $permissao_usuario_repository->method('find')->willReturn($permissao_usuario);

        /** @var PermissaoUsuarioRepositoryInterface $permissao_usuario_repository */

        $command = new GetPermissaoUsuarioPorIdCommand($permissao_usuario_id);
        $permissao_usuario_retornada = (new GetPermissaoUsuarioPorIdCommandHandler($permissao_usuario_repository))->handle($command);

        $this->assertInstanceOf(PermissaoUsuario::class, $permissao_usuario_retornada);
        $this->assertEquals($permissao_usuario_id, $permissao_usuario_retornada->getId());
    }
}
