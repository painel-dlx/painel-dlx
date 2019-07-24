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

namespace PainelDLX\Testes\Application\UseCases\Emails\ExcluirConfigSmtp;

use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PainelDLX\UseCases\Emails\ExcluirConfigSmtp\ExcluirConfigSmtpCommand;
use PainelDLX\UseCases\Emails\ExcluirConfigSmtp\ExcluirConfigSmtpCommandHandler;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ExcluirConfigSmtpHandlerTest
 * @package PainelDLX\Testes\Application\UseCases\Emails\ExcluirConfigSmtp
 * @coversDefaultClass \PainelDLX\UseCases\Emails\ExcluirConfigSmtp\ExcluirConfigSmtpCommandHandler
 */
class ExcluirConfigSmtpCommandHandlerTest extends TestCase
{
    /**
     * @covers ::handle
     */
    public function test_Handle()
    {
        $config_smtp = $this->createMock(ConfigSmtp::class);

        $config_smtp_repository = $this->createMock(ConfigSmtpRepositoryInterface::class);
        $config_smtp_repository->method('delete')->willReturn(null);

        /** @var ConfigSmtp $config_smtp */
        /** @var ConfigSmtpRepositoryInterface $config_smtp_repository */

        $command = new ExcluirConfigSmtpCommand($config_smtp);
        (new ExcluirConfigSmtpCommandHandler($config_smtp_repository))->handle($command);

        $this->expectNotToPerformAssertions();
    }
}
