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

namespace PainelDLX\Testes\Application\UseCases\Emails\GetConfigSmtpPorId;

use DLX\Infra\EntityManagerX;
use PainelDLX\Application\UseCases\Emails\GetConfigSmtpPorId\GetConfigSmtpPorIdCommand;
use PainelDLX\Application\UseCases\Emails\GetConfigSmtpPorId\GetConfigSmtpPorIdHandler;
use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;
use PainelDLX\Testes\Application\UseCases\Emails\NovaConfigSmtp\NovaConfigSmtpHandlerTests;
use PainelDLX\Testes\PainelDLXTests;

class GetConfigSmtpPorIdHandlerTests extends PainelDLXTests
{
    /** @var GetConfigSmtpPorIdHandler */
    private $handler;

    protected function setUp()
    {
        parent::setUp();

        /** @var ConfigSmtpRepositoryInterface $config_smtp_repository */
        $config_smtp_repository = EntityManagerX::getRepository(ConfigSmtp::class);
        $this->handler = new GetConfigSmtpPorIdHandler($config_smtp_repository);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \PainelDLX\Domain\Emails\Exceptions\AutentContaNaoInformadaException
     * @throws \PainelDLX\Domain\Emails\Exceptions\AutentSenhaNaoInformadaException
     */
    public function test_Handle()
    {
        $config_smtp = (new NovaConfigSmtpHandlerTests())->test_Handle();
        $command = new GetConfigSmtpPorIdCommand($config_smtp->getConfigSmtpId());

        $config_smtp2 = $this->handler->handle($command);

        $this->assertInstanceOf(ConfigSmtp::class, $config_smtp2);
        $this->assertEquals($config_smtp2, $config_smtp);
    }
}
