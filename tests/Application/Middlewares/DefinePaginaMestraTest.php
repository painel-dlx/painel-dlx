<?php
/**
 * Created by PhpStorm.
 * User: diegolepera
 * Date: 05/02/2019
 * Time: 14:46
 */

namespace PainelDLX\Testes\Application\Middlewares;

use PainelDLX\Application\Middlewares\DefinePaginaMestra;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SechianeX\Exceptions\SessionAdapterInterfaceInvalidaException;
use SechianeX\Exceptions\SessionAdapterNaoEncontradoException;
use SechianeX\Factories\SessionFactory;

/**
 * Class DefinePaginaMestraTest
 * @package PainelDLX\Testes\Application\Middlewares
 * @coversDefaultClass \PainelDLX\Application\Middlewares\DefinePaginaMestra
 */
class DefinePaginaMestraTest extends TestCase
{
    /**
     * @throws SessionAdapterInterfaceInvalidaException
     * @throws SessionAdapterNaoEncontradoException
     */
    public function test_Process()
    {
        $pagina_mestra = 'painel-dlx-master';

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getQueryParams')->willReturn(['pg-mestra' => $pagina_mestra]);

        $response = $this->createMock(ResponseInterface::class);

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($response);

        $session = SessionFactory::createPHPSession('dlx-teste');

        /** @var ServerRequestInterface $request */
        /** @var RequestHandlerInterface $handler */

        (new DefinePaginaMestra($session))->process($request, $handler);

        $this->assertEquals($pagina_mestra, $session->get('vilex:pagina-mestra'));
    }
}
