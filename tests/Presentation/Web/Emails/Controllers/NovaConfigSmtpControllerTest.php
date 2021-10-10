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

namespace PainelDLX\Tests\Presentation\Web\Emails\Controllers;

use Doctrine\ORM\ORMException;
use PainelDLX\Presentation\Web\Emails\Controllers\NovaConfigSmtpController;
use PainelDLX\Tests\TestCase\PainelDLXTestCase;
use PainelDLX\Tests\TestCase\TesteComTransaction;
use Psr\Http\Message\ServerRequestInterface;
use Vilex\Exceptions\PaginaMestraInvalidaException;
use Vilex\Exceptions\TemplateInvalidoException;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;

/**
 * Class NovaConfigSmtpControllerTest
 * @package PainelDLX\Testes\Presentation\Web\Emails\Controllers
 * @coversDefaultClass \PainelDLX\Presentation\Web\Emails\Controllers\NovaConfigSmtpController
 */
class NovaConfigSmtpControllerTest extends PainelDLXTestCase
{
    use TesteComTransaction;

    /**
     * @var NovaConfigSmtpController
     */
    private $controller;

    /**
     * @throws ORMException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = self::$painel_dlx->getContainer()->get(NovaConfigSmtpController::class);
    }

    /**
     * @throws PaginaMestraInvalidaException
     * @throws TemplateInvalidoException
     */
    public function test_FormNovaConfigSmtp_deve_retornar_instancia_HtmlResponse()
    {
        /** @var ServerRequestInterface $request */
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->controller->formNovaConfigSmtp($request);

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }

    /**
     * @covers ::salvarNovaConfigSmtp
     */
    public function test_SalvarNovaConfigSmtp_retornar_instancia_JsonResponse()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn([
            'nome' => 'Teste',
            'servidor' => 'localhost',
            'porta' => 25,
            'cripto' => null,
            'requer_autent' => false,
            'conta' => null,
            'senha' => null,
            'de_nome' => 'Painel DLX',
            'responder_para' => null,
            'corpo_html' => true
        ]);

        /** @var ServerRequestInterface $request */
        $response = $this->controller->salvarNovaConfigSmtp($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $json = json_decode((string)$response->getBody());

        $this->assertEquals('sucesso', $json->retorno);
        $this->assertNotNull($json->config_smtp_id);
    }
}
