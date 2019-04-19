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
use Doctrine\ORM\ORMException;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use PainelDLX\Application\Factories\CommandBusFactory;
use PainelDLX\Domain\Usuarios\Exceptions\UsuarioNaoEncontrado;
use PainelDLX\Presentation\Site\Usuarios\Controllers\ResetSenhaController;
use PainelDLX\Testes\Application\UseCases\Usuarios\SolicitarResetSenha\SolicitarResetSenhaHandlerTest;
use PainelDLX\Testes\TestCase\PainelDLXTestCase;
use PainelDLX\Testes\TestCase\TesteComTransaction;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Contracts\SessionInterface;
use Vilex\Exceptions\ContextoInvalidoException;
use Vilex\Exceptions\PaginaMestraNaoEncontradaException;
use Vilex\Exceptions\ViewNaoEncontradaException;
use Vilex\VileX;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class ResetSenhaControllerTest
 * @package PainelDLX\Testes\Presentation\Site\Usuarios\Controllers
 * @coversDefaultClass \PainelDLX\Presentation\Site\Usuarios\Controllers\ResetSenhaController
 */
class ResetSenhaControllerTest extends PainelDLXTestCase
{
    use TesteComTransaction;

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
    }

    /**
     * @return ResetSenhaController
     * @throws ORMException
     */
    public function test__construct(): ResetSenhaController
    {
        $command_bus = CommandBusFactory::create(self::$container, Configure::get('app', 'mapping'));

        /** @var SessionInterface $session */
        $controller = new ResetSenhaController(
            new VileX(),
            $command_bus(),
            $this->session,
            new DoctrineTransaction(EntityManagerX::getInstance())
        );

        $this->assertInstanceOf(ResetSenhaController::class, $controller);

        return $controller;
    }

    /**
     * @throws ContextoInvalidoException
     * @throws PaginaMestraNaoEncontradaException
     * @throws ViewNaoEncontradaException
     * @covers ::formSolicitarResetSenha
     * @depends test__construct
     */
    public function test_FormSolicitarResetSenha_deve_retornar_HtmlResponse(ResetSenhaController $controller)
    {
        /** @var ServerRequestInterface $request */
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $controller->formSolicitarResetSenha($request);

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }

    /**
     * @param ResetSenhaController $controller
     * @covers ::solicitarResetSenha
     * @depends test__construct
     */
    public function test_SolicitarResetSenha_deve_retornar_JsonResponse_sucesso(ResetSenhaController $controller)
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->method('getParsedBody')
            ->willReturn(['email' => 'dlepera88@gmail.com']);

        /** @var ServerRequestInterface $request */
        $response = $controller->solicitarResetSenha($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $json = json_decode((string)$response->getBody());

        $this->assertEquals('sucesso', $json->retorno);
        $this->assertNotNull($json->reset_senha_id);
    }

    /**
     * @param ResetSenhaController $controller
     * @throws ContextoInvalidoException
     * @throws ORMException
     * @throws PaginaMestraNaoEncontradaException
     * @throws UsuarioNaoEncontrado
     * @throws ViewNaoEncontradaException
     * @covers ::formResetSenha
     * @depends test__construct
     */
    public function test_FormResetSenha_deve_retornar_um_HtmlResponse(ResetSenhaController $controller)
    {
        $reset_senha = (new SolicitarResetSenhaHandlerTest())->test_Handle();

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->method('getQueryParams')
            ->willReturn(['hash' => $reset_senha->getHash()]);

        /** @var ServerRequestInterface $request */
        $response = $controller->formResetSenha($request);

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }

    /**
     * @param ResetSenhaController $controller
     * @throws ORMException
     * @throws UsuarioNaoEncontrado
     * @covers ::resetarSenha
     * @depends test__construct
     */
    public function teste_resetarSenha_deve_retornar_um_JsonResponse_sucesso(ResetSenhaController $controller)
    {
        $this->markTestSkipped('Erro no mock da sessão.');

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
        $response = $controller->resetarSenha($request);

        $json = json_decode((string)$response->getBody());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('sucesso', $json->retorno);
    }
}
