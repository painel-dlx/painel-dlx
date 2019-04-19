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

namespace PainelDLX\Testes\Application\Services;

use PainelDLX\Application\Services\EnviarEmail;
use PainelDLX\Application\Services\Exceptions\ErroAoEnviarEmailException;
use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PainelDLX\Testes\TestCase\PainelDLXTestCase;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class EnviarEmailTestCase extends PainelDLXTestCase
{
    /** @var EnviarEmail */
    private $enviar_email;

    protected function setUp()
    {
        parent::setUp();

        $config_smtp = (new ConfigSmtp())
            ->setServidor('smtp.gmail.com')
            ->setPorta(587)
            ->setRequerAutent(true)
            ->setCripto('tls')
            ->setConta('dlepera88.emails@gmail.com')
            ->setSenha('oxswveitoainkmbu')
            ->setCorpoHtml(true);

        $this->enviar_email = new EnviarEmail(
            new PHPMailer(),
            $config_smtp,
            'PainelDLX: Teste Unitário',
            '<h1>TESTE UNITÁRIO</h1>'
        );
    }


    public function test_GetCorpo()
    {
        $this->assertEquals('<h1>TESTE UNITÁRIO</h1>', $this->enviar_email->getCorpo());
    }

    public function test_GetPhpMailer()
    {
        $this->assertInstanceOf(PHPMailer::class, $this->enviar_email->getPhpMailer());
    }

    public function test_GetAssunto()
    {
        $this->assertEquals('PainelDLX: Teste Unitário', $this->enviar_email->getAssunto());
    }

    /**
     * @throws Exception
     * @throws ErroAoEnviarEmailException
     */
    public function test_EnviarPara()
    {
        $this->assertTrue($this->enviar_email->enviarPara('dlepera88@gmail.com'));
    }

    public function test_GetConfigSmtp()
    {
        $this->assertInstanceOf(ConfigSmtp::class, $this->enviar_email->getConfigSmtp());
    }
}
