<?php
/**
 * Created by PhpStorm.
 * User: diegol
 * Date: 15/01/2019
 * Time: 13:45
 */

namespace PainelDLX\Testes\Presentation\Site\Usuarios\Controllers;

use DLX\Infrastructure\EntityManagerX;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMException;
use PainelDLX\Presentation\Web\Usuarios\Controllers\ResetSenhaController;
use PainelDLX\Tests\TestCase\PainelDLXTestCase;
use PainelDLX\Tests\TestCase\TesteComTransaction;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Contracts\SessionInterface;
use SechianeX\Exceptions\SessionAdapterInterfaceInvalidaException;
use SechianeX\Exceptions\SessionAdapterNaoEncontradoException;
use SechianeX\Factories\SessionFactory;
use Vilex\Exceptions\ContextoInvalidoException;
use Vilex\Exceptions\PaginaMestraInvalidaException;
use Vilex\Exceptions\PaginaMestraNaoEncontradaException;
use Vilex\Exceptions\TemplateInvalidoException;
use Vilex\Exceptions\ViewNaoEncontradaException;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

$_SESSION = [];

/**
 * Class ResetSenhaControllerTest
 * @package PainelDLX\Testes\Presentation\Web\Usuarios\Controllers
 * @coversDefaultClass \PainelDLX\Presentation\Web\Usuarios\Controllers\ResetSenhaController
 */
class ResetSenhaControllerTest extends PainelDLXTestCase
{
    use TesteComTransaction;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @throws ORMException
     * @throws SessionAdapterInterfaceInvalidaException
     * @throws SessionAdapterNaoEncontradoException
     */
    protected function setUp()
    {
        parent::setUp();
        $this->session = SessionFactory::createPHPSession();
        $this->session->set('vilex:pagina-mestra', 'painel-dlx-master');
    }

    /**
     * @return ResetSenhaController
     */
    public function test__construct(): ResetSenhaController
    {
        $controller = self::$painel_dlx->getContainer()->get(ResetSenhaController::class);

        $this->assertInstanceOf(ResetSenhaController::class, $controller);

        return $controller;
    }

    /**
     * @param ResetSenhaController $controller
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
        $request->method('getParsedBody')->willReturn(['email' => 'dlepera88@gmail.com']);

        /** @var ServerRequestInterface $request */
        $response = $controller->solicitarResetSenha($request);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $json = json_decode((string)$response->getBody());

        $this->assertEquals('sucesso', $json->retorno);
        $this->assertNotNull($json->reset_senha_id);
    }

    /**
     * @param ResetSenhaController $controller
     * @throws DBALException
     * @throws ORMException
     * @throws PaginaMestraInvalidaException
     * @throws TemplateInvalidoException
     * @covers ::formResetSenha
     * @depends test__construct
     */
    public function test_FormResetSenha_deve_retornar_um_HtmlResponse(ResetSenhaController $controller)
    {
        $query = '
            select
                hash
            from
                ResetSenha
            where
                utilizado = 0
            order by 
                rand()
            limit 1
        ';

        $sql = EntityManagerX::getInstance()->getConnection()->executeQuery($query);
        $reset_senha_hash = $sql->fetchColumn();

        if (empty($reset_senha_hash)) {
            $this->markTestIncomplete('Nenhuma recuperação de senha encontrada para executar o teste.');
        }

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getQueryParams')->willReturn(['hash' => $reset_senha_hash]);

        /** @var ServerRequestInterface $request */
        $response = $controller->formResetSenha($request);

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }

    /**
     * @param ResetSenhaController $controller
     * @throws ORMException
     * @covers ::resetarSenha
     * @depends test__construct
     */
    public function test_ResetarSenha_deve_retornar_um_JsonResponse_sucesso(ResetSenhaController $controller)
    {
        $this->markTestSkipped('Erro no mock da sessão.');

        // $reset_senha = (new SolicitarResetSenhaCommandHandlerTest())->test_Handle();

        // $this->session->set('hash', $reset_senha->getHash());

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
