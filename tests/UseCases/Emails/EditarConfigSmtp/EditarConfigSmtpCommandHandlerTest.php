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

namespace PainelDLX\Tests\UseCases\Emails\EditarConfigSmtp;

use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PainelDLX\Domain\Emails\Exceptions\ConfigSmtpInvalidoException;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;
use PainelDLX\Domain\Emails\Services\Validators\SalvarConfigSmtpValidator;
use PainelDLX\UseCases\Emails\EditarConfigSmtp\EditarConfigSmtpCommand;
use PainelDLX\UseCases\Emails\EditarConfigSmtp\EditarConfigSmtpCommandHandler;
use PHPUnit\Framework\TestCase;

/**
 * Class EditarConfigSmtpCommandHandlerTest
 * @package PainelDLX\Tests\UseCases\Emails\EditarConfigSmtp
 * @coversDefaultClass \PainelDLX\UseCases\Emails\EditarConfigSmtp\EditarConfigSmtpCommandHandler
 */
class EditarConfigSmtpCommandHandlerTest extends TestCase
{
    /**
     * @covers ::handle
     * @throws ConfigSmtpInvalidoException
     */
    public function test_Handle_deve_alterar_informacoes_ConfigSmtp_e_salvar()
    {
        $config_smtp_repository = $this->createMock(ConfigSmtpRepositoryInterface::class);
        $config_smtp_repository->method('update')->willReturn(null);

        $validator = $this->createMock(SalvarConfigSmtpValidator::class);
        $validator->method('validar')->willReturn(true);

        $config_smtp = new ConfigSmtp('localhost', 25);
        $config_smtp->setNome('Teste Original');

        /** @var ConfigSmtpRepositoryInterface $config_smtp_repository */
        /** @var SalvarConfigSmtpValidator $validator */

        $nome_alterado = 'Configuração Alterada';
        $porta = 587;

        $command = new EditarConfigSmtpCommand(
            $config_smtp,
            $nome_alterado,
            'localhost',
            $porta
        );

        $config_smtp_alterado = (new EditarConfigSmtpCommandHandler($config_smtp_repository, $validator))->handle($command);

        $this->assertEquals($nome_alterado, $config_smtp_alterado->getNome());
        $this->assertEquals($porta, $config_smtp_alterado->getPorta());
    }
}
