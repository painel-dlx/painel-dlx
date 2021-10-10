<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 15/02/2019
 * Time: 09:18
 */

namespace PainelDLX\Testes\Presentation\Site\Home\Controllers;

use PainelDLX\Presentation\Web\Home\Controllers\PaginaInicialController;
use PainelDLX\Tests\TestCase\PainelDLXTestCase;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Exceptions\SessionAdapterInterfaceInvalidaException;
use SechianeX\Exceptions\SessionAdapterNaoEncontradoException;
use SechianeX\Factories\SessionFactory;
use Vilex\Exceptions\ContextoInvalidoException;
use Vilex\Exceptions\PaginaMestraNaoEncontradaException;
use Vilex\Exceptions\ViewNaoEncontradaException;
use Laminas\Diactoros\Response\HtmlResponse;

/**
 * Class PaginaInicialControllerTest
 * @package PainelDLX\Testes\Presentation\Web\Home\Controllers
 * @coversDefaultClass \PainelDLX\Presentation\Web\Home\Controllers\PaginaInicialController
 */
class PaginaInicialControllerTest extends PainelDLXTestCase
{
    /**
     * @return PaginaInicialController
     * @throws SessionAdapterInterfaceInvalidaException
     * @throws SessionAdapterNaoEncontradoException
     */
    public function test__construct(): PaginaInicialController
    {
        $session = SessionFactory::createPHPSession();
        $session->set('vilex:pagina-mestra', 'painel-dlx-master');

        $controller = self::$painel_dlx->getContainer()->get(PaginaInicialController::class);

        $this->assertInstanceOf(PaginaInicialController::class, $controller);

        return $controller;
    }

    /**
     * @param PaginaInicialController $controller
     * @throws ContextoInvalidoException
     * @throws PaginaMestraNaoEncontradaException
     * @throws ViewNaoEncontradaException
     * @covers ::home
     * @depends test__construct
     */
    public function test_Home_deve_retornar_um_HtmlResponse(PaginaInicialController $controller)
    {
        $request = $this->createMock(ServerRequestInterface::class);

        /** @var ServerRequestInterface $request */
        $response = $controller->home($request);

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }
}
