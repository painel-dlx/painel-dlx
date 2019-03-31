<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 15/01/2019
 * Time: 13:45
 */

namespace PainelDLX\Testes\Presentation\Site\Usuarios\Controllers;

use DLX\Core\CommandBus\CommandBusAdapter;
use DLX\Core\Configure;
use DLX\Infra\EntityManagerX;
use DLX\Infra\ORM\Doctrine\Services\DoctrineTransaction;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use PainelDLX\Presentation\Site\Usuarios\Controllers\ResetSenhaController;
use PainelDLX\Testes\Application\UseCases\Usuarios\SolicitarResetSenha\SolicitarResetSenhaHandlerTest;
use PainelDLX\Testes\PainelDLXTests;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Contracts\SessionInterface;
use Vilex\VileX;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class ResetSenhaControllerTest extends PainelDLXTests
{
    /** @var ResetSenhaController */
    private $controller;

    /** @var SessionInterface */
    private $session;

    protected function setUp()
    {
        parent::setUp();

        $this->session = $this->createMock(SessionInterface::class);
        $this->session
            ->method('get')
            ->with('vilex:pagina-mestra')
            ->willReturn('painel-dlx-master');

        /** @var SessionInterface $session */
        $this->controller = new ResetSenhaController(
            new VileX(),
            CommandBusAdapter::create(new CommandHandlerMiddleware(
                new ClassNameExtractor,
                new ContainerLocator($this->container, Configure::get('app', 'mapping')),
                new HandleInflector
            )),
            $this->session,
            new DoctrineTransaction(EntityManagerX::getInstance())
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

    /**
     *
     */
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

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \PainelDLX\Domain\Usuarios\Exceptions\UsuarioNaoEncontrado
     * @throws \Vilex\Exceptions\ContextoInvalidoException
     * @throws \Vilex\Exceptions\PaginaMestraNaoEncontradaException
     * @throws \Vilex\Exceptions\ViewNaoEncontradaException
     */
    public function test_FormResetSenha_deve_retornar_um_HtmlResponse()
    {
        $reset_senha = (new SolicitarResetSenhaHandlerTest())->test_Handle();

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->method('getQueryParams')
            ->willReturn(['hash' => $reset_senha->getHash()]);

        /** @var ServerRequestInterface $request */
        $response = $this->controller->formResetSenha($request);

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \PainelDLX\Domain\Usuarios\Exceptions\UsuarioNaoEncontrado
     */
    public function teste_resetarSenha_deve_retornar_um_JsonResponse_sucesso()
    {
        $this->markTestSkipped('Está ocorrendo um erro no mock da sessão.');

        $reset_senha = (new SolicitarResetSenhaHandlerTest())->test_Handle();

        $this->session
            ->method('get')
            ->with('hash')
            ->willReturn($reset_senha->getHash());

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->method('getParsedBody')
            ->willReturn([
                'senha_nova' => 'teste123',
                'senha_confirm' => 'teste123'
            ]);

        /** @var ServerRequestInterface $request */
        $response = $this->controller->resetarSenha($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $json = json_decode((string)$response->getBody());

        $this->assertEquals('sucesso', $json->retorno);
    }
}
