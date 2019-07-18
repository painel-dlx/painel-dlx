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
use DLX\Core\Exceptions\ArquivoConfiguracaoNaoEncontradoException;
use DLX\Core\Exceptions\ArquivoConfiguracaoNaoInformadoException;
use Doctrine\ORM\ORMException;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use PainelDLX\Application\Services\Exceptions\AmbienteNaoInformadoException;
use PainelDLX\Domain\Emails\Exceptions\AutentContaNaoInformadaException;
use PainelDLX\Domain\Emails\Exceptions\AutentSenhaNaoInformadaException;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Presentation\Site\Emails\Controllers\ConfigSmtpController;
use PainelDLX\Testes\Application\UseCases\Emails\NovaConfigSmtp\NovaConfigSmtpHandlerTestCase;
use PainelDLX\Testes\TestCase\PainelDLXTestCase;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Contracts\SessionInterface;
use SechianeX\Exceptions\SessionAdapterInterfaceInvalidaException;
use SechianeX\Exceptions\SessionAdapterNaoEncontradoException;
use SechianeX\Factories\SessionFactory;
use Vilex\Exceptions\ContextoInvalidoException;
use Vilex\Exceptions\PaginaMestraNaoEncontradaException;
use Vilex\Exceptions\ViewNaoEncontradaException;
use Vilex\VileX;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class ConfigSmtpControllerTestCase extends PainelDLXTestCase
{
    /** @var ConfigSmtpController */
    private $controller;

    /**
     * @throws ArquivoConfiguracaoNaoEncontradoException
     * @throws ArquivoConfiguracaoNaoInformadoException
     * @throws ORMException
     * @throws AmbienteNaoInformadoException
     * @throws SessionAdapterInterfaceInvalidaException
     * @throws SessionAdapterNaoEncontradoException
     */
    protected function setUp()
    {
        parent::setUp();

        $session = $this->createMock(SessionInterface::class);
        $session
            ->method('get')
            ->with('usuario-logado')
            ->willReturn((new Usuario('Diego Lepera', 'dlepera88@gmail.com')));

        /** @var SessionInterface $session */
        $this->controller = new ConfigSmtpController(
            new VileX(),
            CommandBusAdapter::create(new CommandHandlerMiddleware(
                new ClassNameExtractor,
                new ContainerLocator($this->container, Configure::get('app', 'mapping')),
                new HandleInflector
            )),
            $session
        );
    }

    /**
     * @throws ContextoInvalidoException
     * @throws PaginaMestraNaoEncontradaException
     * @throws ViewNaoEncontradaException
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
     * @throws ORMException
     * @throws AutentContaNaoInformadaException
     * @throws AutentSenhaNaoInformadaException
     */
    public function test_ExcluirConfigSmtp_deve_retornar_JsonResponse()
    {
        $config_smtp = (new NovaConfigSmtpHandlerTestCase())->test_Handle();

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->method('getParsedBody')
            ->willReturn([
                'config_smtp_id' => $config_smtp->getId()
            ]);

        /** @var ServerRequestInterface $request */
        $response = $this->controller->excluirConfigSmtp($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $json = json_decode((string)$response->getBody());

        $this->assertEquals('sucesso', $json->retorno);
    }

    /**
     * @throws ORMException
     * @throws AutentContaNaoInformadaException
     * @throws AutentSenhaNaoInformadaException
     * @throws ContextoInvalidoException
     * @throws PaginaMestraNaoEncontradaException
     * @throws ViewNaoEncontradaException
     */
    public function test_DetalheConfigSmtp_deve_retornar_um_HtmlResponse()
    {
        $config_smtp = (new NovaConfigSmtpHandlerTestCase())->test_Handle();

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->method('getQueryParams')
            ->willReturn([
                'config_smtp_id' => $config_smtp->getId()
            ]);

        /** @var ServerRequestInterface $request */
        $response = $this->controller->detalheConfigSmtp($request);

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }

    public function test_testarConfigSmtp_deve_retornar_um_JsonResponse_sucesso()
    {
        $server_request = $this->createMock(ServerRequestInterface::class);
        $server_request
            ->method('getParsedBody')
            ->willReturn([
                'servidor' => 'smtp.gmail.com',
                'porta' => 587,
                'requer_autent' => true,
                'conta' => 'dlepera88.emails@gmail.com',
                'senha' => 'oxswveitoainkmbu',
                'cripto' => 'tls',
                'de_nome' => null,
                'responder_para' => null,
                'corpo_html' => true
            ]);

        /** @var ServerRequestInterface $server_request */
        $response = $this->controller->testarConfigSmtp($server_request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $json = json_decode((string)$response->getBody());

        $this->assertEquals('sucesso', $json->retorno);
    }
}
