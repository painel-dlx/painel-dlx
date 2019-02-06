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
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Factories\SessionFactory;

class DefinePaginaMestraTest extends TestCase
{
    /**
     * @throws \SechianeX\Exceptions\SessionAdapterInterfaceInvalidaException
     * @throws \SechianeX\Exceptions\SessionAdapterNaoEncontradoException
     */
    public function test_Executar()
    {
        $pagina_mestra = 'teste.mestra.php';

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->method('getQueryParams')
            ->willReturn(['pg-mestra' => $pagina_mestra]);

        $session = SessionFactory::createPHPSession('dlx-teste');

        /** @var ServerRequestInterface $request */
        $middleware = new DefinePaginaMestra($request, $session);
        $middleware->executar();

        $this->assertEquals($pagina_mestra, $session->get('vilex:pagina-mestra'));
    }
}
