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

use DLX\Infrastructure\EntityManagerX;
use Doctrine\ORM\ORMException;
use PainelDLX\UseCases\Emails\GetConfigSmtpPorId\GetConfigSmtpPorIdCommand;
use PainelDLX\UseCases\Emails\GetConfigSmtpPorId\GetConfigSmtpPorIdCommandHandler;
use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PainelDLX\Domain\Emails\Exceptions\AutentContaNaoInformadaException;
use PainelDLX\Domain\Emails\Exceptions\AutentSenhaNaoInformadaException;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;
use PainelDLX\Testes\Application\UseCases\Emails\NovaConfigSmtp\NovaConfigSmtpHandlerTest;
use PainelDLX\Testes\TestCase\PainelDLXTestCase;

class GetConfigSmtpPorIdHandlerTestCase extends PainelDLXTestCase
{
    /** @var GetConfigSmtpPorIdCommandHandler */
    private $handler;

    protected function setUp()
    {
        parent::setUp();

        /** @var ConfigSmtpRepositoryInterface $config_smtp_repository */
        $config_smtp_repository = EntityManagerX::getRepository(ConfigSmtp::class);
        $this->handler = new GetConfigSmtpPorIdCommandHandler($config_smtp_repository);
    }

    /**
     * @throws ORMException
     * @throws AutentContaNaoInformadaException
     * @throws AutentSenhaNaoInformadaException
     */
    public function test_Handle()
    {
        $config_smtp = (new NovaConfigSmtpHandlerTest())->test_Handle();
        $command = new GetConfigSmtpPorIdCommand($config_smtp->getId());

        $config_smtp2 = $this->handler->handle($command);

        $this->assertInstanceOf(ConfigSmtp::class, $config_smtp2);
        $this->assertEquals($config_smtp2, $config_smtp);
    }
}
