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

namespace PainelDLX\Testes\Application\UseCases\Emails\NovaConfigSmtp;

use PainelDLX\Domain\Emails\Exceptions\ConfigSmtpInvalidoException;
use PainelDLX\Domain\Emails\Services\Validators\SalvarConfigSmtpValidator;
use PainelDLX\UseCases\Emails\NovaConfigSmtp\NovaConfigSmtpCommand;
use PainelDLX\UseCases\Emails\NovaConfigSmtp\NovaConfigSmtpCommandHandler;
use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class NovaConfigSmtpHandlerTest
 * @package PainelDLX\Testes\Application\UseCases\Emails\NovaConfigSmtp
 * @coversDefaultClass \PainelDLX\UseCases\Emails\NovaConfigSmtp\NovaConfigSmtpCommandHandler
 */
class NovaConfigSmtpCommandHandlerTest extends TestCase
{
    /**
     * @return ConfigSmtp
     * @throws ConfigSmtpInvalidoException
     */
    public function test_Handle_deve_salvar_uma_nova_configuracao_smtp(): ConfigSmtp
    {
        $config_smtp_repository = $this->createMock(ConfigSmtpRepositoryInterface::class);
        $config_smtp_repository->method('create')->willReturn(null);

        $validator = $this->createMock(SalvarConfigSmtpValidator::class);
        $validator->method('validar')->willReturn(true);

        /** @var ConfigSmtpRepositoryInterface $config_smtp_repository */
        /** @var SalvarConfigSmtpValidator $validator */

        $command = new NovaConfigSmtpCommand(
            'Configuração de Teste',
            'localhost',
            25,
            'tls',
            true,
            'teste@gmail.com',
            'teste123',
            'Teste Unitário',
            'teste@gmail.com',
            true
        );
        $config_smtp = (new NovaConfigSmtpCommandHandler($config_smtp_repository, $validator))->handle($command);

        $this->assertInstanceOf(ConfigSmtp::class, $config_smtp);

        return $config_smtp;
    }
}
