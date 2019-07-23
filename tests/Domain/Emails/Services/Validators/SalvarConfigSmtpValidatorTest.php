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

namespace PainelDLX\Testes\Domain\Emails\Services\Validators;

use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PainelDLX\Domain\Emails\Exceptions\ConfigSmtpInvalidoException;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;
use PainelDLX\Domain\Emails\Services\Validators\SalvarConfigSmtpValidator;
use PHPUnit\Framework\TestCase;

/**
 * Class SalvarConfigSmtpValidatorTest
 * @package PainelDLX\Testes\Domain\Emails\Services\Validators
 * @coversDefaultClass \PainelDLX\Domain\Emails\Services\Validators\SalvarConfigSmtpValidator
 */
class SalvarConfigSmtpValidatorTest extends TestCase
{
    /**
     * @throws ConfigSmtpInvalidoException
     * @covers ::validar
     */
    public function test_Validar_ConfigSmtp_com_nome_repetido()
    {
        $config_smtp = new ConfigSmtp();
        $config_smtp->setNome('Qualquer nome');

        $config_smtp_repository = $this->createMock(ConfigSmtpRepositoryInterface::class);
        $config_smtp_repository->method('existsOutroSmtpMesmoNome')->willReturn(true);

        $this->expectException(ConfigSmtpInvalidoException::class);
        $this->expectExceptionCode(10);

        /** @var ConfigSmtpRepositoryInterface $config_smtp_repository */
        (new SalvarConfigSmtpValidator($config_smtp_repository))->validar($config_smtp);
    }

    /**
     * @throws ConfigSmtpInvalidoException
     * @covers ::validar
     */
    public function test_Validar_ConfigSmtp_requer_autent_sem_conta()
    {
        $config_smtp = new ConfigSmtp();
        $config_smtp
            ->setRequerAutent(true)
            ->setSenha('lekrhj93');

        $config_smtp_repository = $this->createMock(ConfigSmtpRepositoryInterface::class);
        $config_smtp_repository->method('existsOutroSmtpMesmoNome')->willReturn(false);

        $this->expectException(ConfigSmtpInvalidoException::class);
        $this->expectExceptionCode(11);

        /** @var ConfigSmtpRepositoryInterface $config_smtp_repository */
        (new SalvarConfigSmtpValidator($config_smtp_repository))->validar($config_smtp);
    }

    /**
     * @throws ConfigSmtpInvalidoException
     * @covers ::validar
     */
    public function test_Validar_ConfigSmtp_requer_autent_sem_senha()
    {
        $config_smtp = new ConfigSmtp();
        $config_smtp
            ->setRequerAutent(true)
            ->setConta('nome@email.com');

        $config_smtp_repository = $this->createMock(ConfigSmtpRepositoryInterface::class);
        $config_smtp_repository
            ->method('existsOutroSmtpMesmoNome')
            ->willReturn(false);

        $this->expectException(ConfigSmtpInvalidoException::class);
        $this->expectExceptionCode(12);

        /** @var ConfigSmtpRepositoryInterface $config_smtp_repository */
        (new SalvarConfigSmtpValidator($config_smtp_repository))->validar($config_smtp);
    }

    /**
     * @throws ConfigSmtpInvalidoException
     * @covers ::validar
     */
    public function test_Validar_ConfigSmtp_requer_autent_com_conta_e_senha()
    {
        $config_smtp = new ConfigSmtp();
        $config_smtp
            ->setRequerAutent(true)
            ->setConta('nome@email.com')
            ->setSenha('whie72Ohwue');

        $config_smtp_repository = $this->createMock(ConfigSmtpRepositoryInterface::class);
        $config_smtp_repository->method('existsOutroSmtpMesmoNome')->willReturn(false);

        /** @var ConfigSmtpRepositoryInterface $config_smtp_repository */
        $validacao = (new SalvarConfigSmtpValidator($config_smtp_repository))->validar($config_smtp);

        $this->assertTrue($validacao);
    }
}
