<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 15/01/2019
 * Time: 13:45
 */

namespace PainelDLX\Testes\Presentation\Site\Emails\Controllers;

use DLX\Core\CommandBus\CommandBusAdapter;
use DLX\Core\Configure;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use PainelDLX\Presentation\Site\Usuarios\Controllers\ResetSenhaController;
use PainelDLX\Testes\PainelDLXTests;
use Psr\Http\Message\ServerRequestInterface;
use Vilex\VileX;
use Zend\Diactoros\Response\HtmlResponse;

class ResetSenhaControllerTest extends PainelDLXTests
{
    /** @var ResetSenhaController */
    private $controller;

    protected function setUp()
    {
        parent::setUp();

        $this->controller = new ResetSenhaController(
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
    public function test_FormSolicitarResetSenha_deve_retornar_HtmlResponse()
    {
        /** @var ServerRequestInterface $request */
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->controller->formSolicitarResetSenha($request);

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }

    public function test_SolicitarResetSenha_deve_retornar_JsonResponse_sucesso()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->method('getParsedBody')
            ->willReturn(['email' => 'dlepera88@gmail.com']);

        /** @var ServerRequestInterface $request */
        $response = $this->controller->solicitarResetSenha($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $json = json_decode((string)$response->getBody());

        $this->assertEquals('sucesso', $json->retorno);
        $this->assertNotNull($json->reset_senha_id);
    }
}
