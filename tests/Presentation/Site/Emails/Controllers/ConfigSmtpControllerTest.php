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

namespace PainelDLX\Testes\Presentation\Site\Emails\Controllers;

use DLX\Core\CommandBus\CommandBusAdapter;
use DLX\Core\Configure;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use PainelDLX\Presentation\Site\Emails\Controllers\ConfigSmtpController;
use PainelDLX\Testes\Application\UserCases\Emails\NovaConfigSmtp\NovaConfigSmtpHandlerTest;
use PainelDLX\Testes\PainelDLXTest;
use Psr\Http\Message\ServerRequestInterface;
use Vilex\VileX;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class ConfigSmtpControllerTest extends PainelDLXTest
{
    /** @var ConfigSmtpController */
    private $controller;

    protected function setUp()
    {
        parent::setUp();

        $this->controller = new ConfigSmtpController(
            new VileX(),
            CommandBusAdapter::create(new CommandHandlerMiddleware(
                new ClassNameExtractor,
                new ContainerLocator($this->container, Configure::get('app', 'mapping')),
                new HandleInflector
            ))
        );
    }

    /**
     * @throws \Vilex\Exceptions\ContextoInvalidoException
     * @throws \Vilex\Exceptions\PaginaMestraNaoEncontradaException
     * @throws \Vilex\Exceptions\ViewNaoEncontradaException
     */
    public function test_ListaConfigSmtp_deve_retornar_um_HtmlResponse()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->method('getQueryParams')
            ->willReturn([]);

        /** @var ServerRequestInterface $request */
        $response = $this->controller->listaConfigSmtp($request);

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \PainelDLX\Domain\Emails\Exceptions\AutentContaNaoInformadaException
     * @throws \PainelDLX\Domain\Emails\Exceptions\AutentSenhaNaoInformadaException
     */
    public function test_ExcluirConfigSmtp_deve_retornar_JsonResponse()
    {
        $config_smtp = (new NovaConfigSmtpHandlerTest())->test_Handle();

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->method('getParsedBody')
            ->willReturn([
                'config_smtp_id' => $config_smtp->getConfigSmtpId()
            ]);

        /** @var ServerRequestInterface $request */
        $response = $this->controller->excluirConfigSmtp($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $json = json_decode((string)$response->getBody());

        $this->assertEquals('sucesso', $json->retorno);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \PainelDLX\Domain\Emails\Exceptions\AutentContaNaoInformadaException
     * @throws \PainelDLX\Domain\Emails\Exceptions\AutentSenhaNaoInformadaException
     * @throws \Vilex\Exceptions\ContextoInvalidoException
     * @throws \Vilex\Exceptions\PaginaMestraNaoEncontradaException
     * @throws \Vilex\Exceptions\ViewNaoEncontradaException
     */
    public function test_DetalheConfigSmtp_deve_retornar_um_HtmlResponse()
    {
        $config_smtp = (new NovaConfigSmtpHandlerTest())->test_Handle();

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->method('getQueryParams')
            ->willReturn([
                'config_smtp_id' => $config_smtp->getConfigSmtpId()
            ]);

        /** @var ServerRequestInterface $request */
        $response = $this->controller->detalheConfigSmtp($request);

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }
}
