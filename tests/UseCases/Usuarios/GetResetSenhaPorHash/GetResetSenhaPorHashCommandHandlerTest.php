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

namespace PainelDLX\Testes\Application\UseCases\Usuarios\GetResetSenhaPorHash;

use DLX\Infrastructure\EntityManagerX;
use Doctrine\ORM\ORMException;
use PainelDLX\Domain\Usuarios\Exceptions\ResetSenhaNaoEncontradoException;
use PainelDLX\UseCases\Usuarios\GetResetSenhaPorHash\GetResetSenhaPorHashCommand;
use PainelDLX\UseCases\Usuarios\GetResetSenhaPorHash\GetResetSenhaPorHashCommandHandler;
use PainelDLX\Domain\Usuarios\Entities\ResetSenha;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioNaoEncontradoException;
use PainelDLX\Domain\Usuarios\Repositories\ResetSenhaRepositoryInterface;
use PainelDLX\Testes\Application\UseCases\Usuarios\SolicitarResetSenha\SolicitarResetSenhaCommandHandlerTest;
use PHPUnit\Framework\TestCase;

/**
 * Class GetResetSenhaPorHashHandlerTest
 * @package PainelDLX\Testes\Application\UseCases\Usuarios\GetResetSenhaPorHash
 * @coversDefaultClass \PainelDLX\UseCases\Usuarios\GetResetSenhaPorHash\GetResetSenhaPorHashCommandHandler
 */
class GetResetSenhaPorHashCommandHandlerTest extends TestCase
{

    /**
     * @covers ::handle
     */
    public function test_Handle_deve_retornar_ResetSenha_quando_encontrar_registro_no_bd()
    {
        $hash = uniqid();

        $reset_senha = $this->createMock(ResetSenha::class);
        $reset_senha->method('getHash')->willReturn($hash);

        $reset_senha_repository = $this->createMock(ResetSenhaRepositoryInterface::class);
        $reset_senha_repository->method('findResetSenhaAtivoPorHash')->willReturn($reset_senha);

        /** @var ResetSenha $reset_senha */
        /** @var ResetSenhaRepositoryInterface $reset_senha_repository */

        $command = new GetResetSenhaPorHashCommand($reset_senha->getHash());
        $reset_senha_retornado = (new GetResetSenhaPorHashCommandHandler($reset_senha_repository))->handle($command);

        $this->assertInstanceOf(ResetSenha::class, $reset_senha_retornado);
        $this->assertEquals($hash, $reset_senha_retornado->getHash());
    }

    /**
     * @covers ::handle
     */
    public function test_Handle_deve_lancar_excecao_quando_nao_encontrar_registro_no_bd()
    {
        $reset_senha_repository = $this->createMock(ResetSenhaRepositoryInterface::class);
        $reset_senha_repository->method('findResetSenhaAtivoPorHash')->willReturn(null);

        /** @var ResetSenhaRepositoryInterface $reset_senha_repository */

        $this->expectException(ResetSenhaNaoEncontradoException::class);
        $this->expectExceptionCode(11);

        $command = new GetResetSenhaPorHashCommand('teste');
        (new GetResetSenhaPorHashCommandHandler($reset_senha_repository))->handle($command);
    }
}
