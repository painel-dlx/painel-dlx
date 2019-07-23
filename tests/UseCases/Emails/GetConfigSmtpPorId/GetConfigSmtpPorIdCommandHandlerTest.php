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

namespace PainelDLX\Tests\UseCases\Emails\GetConfigSmtpPorId;

use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PainelDLX\Domain\Emails\Exceptions\ConfigSmtpNaoEncontradaException;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;
use PainelDLX\UseCases\Emails\GetConfigSmtpPorId\GetConfigSmtpPorIdCommand;
use PainelDLX\UseCases\Emails\GetConfigSmtpPorId\GetConfigSmtpPorIdCommandHandler;
use PHPUnit\Framework\TestCase;

/**
 * Class GetConfigSmtpPorIdCommandHandlerTest
 * @package PainelDLX\Tests\UseCases\Emails\GetConfigSmtpPorId
 * @coversDefaultClass \PainelDLX\UseCases\Emails\GetConfigSmtpPorId\GetConfigSmtpPorIdCommandHandler
 */
class GetConfigSmtpPorIdCommandHandlerTest extends TestCase
{
    /**
     * @covers ::handle
     * @throws ConfigSmtpNaoEncontradaException
     */
    public function test_Handle_deve_lancar_exception_quando_nao_encontrar_registro_no_bd()
    {
        $config_smtp_repository = $this->createMock(ConfigSmtpRepositoryInterface::class);
        $config_smtp_repository->method('find')->willReturn(null);

        /** @var ConfigSmtpRepositoryInterface $config_smtp_repository */

        $this->expectException(ConfigSmtpNaoEncontradaException::class);
        $this->expectExceptionCode(10);

        $command = new GetConfigSmtpPorIdCommand(0);
        (new GetConfigSmtpPorIdCommandHandler($config_smtp_repository))->handle($command);
    }

    /**
     * @covers ::handle
     * @throws ConfigSmtpNaoEncontradaException
     */
    public function test_Handle_deve_retornar_ConfigSmtp_quando_encontrar_registro_bd()
    {
        $config_smtp_id = mt_rand();
        $config_smtp = $this->createMock(ConfigSmtp::class);
        $config_smtp->method('getId')->willReturn($config_smtp_id);

        $config_smtp_repository = $this->createMock(ConfigSmtpRepositoryInterface::class);
        $config_smtp_repository->method('find')->willReturn($config_smtp);

        /** @var ConfigSmtpRepositoryInterface $config_smtp_repository */

        $command = new GetConfigSmtpPorIdCommand($config_smtp_id);
        $config_smtp_retornado = (new GetConfigSmtpPorIdCommandHandler($config_smtp_repository))->handle($command);

        $this->assertEquals($config_smtp, $config_smtp_retornado);
        $this->assertEquals($config_smtp_id, $config_smtp_retornado->getId());
    }
}
