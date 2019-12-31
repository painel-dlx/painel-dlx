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

namespace PainelDLX\Testes\Presentation\Site\Usuarios\Controllers;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMException;
use PainelDLX\Presentation\Web\Usuarios\Controllers\LoginController;
use PainelDLX\Tests\Helpers\UsuarioTesteHelper;
use PainelDLX\Tests\TestCase\PainelDLXTestCase;
use PainelDLX\Tests\TestCase\TesteComTransaction;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Contracts\SessionInterface;
use SechianeX\Exceptions\SessionAdapterInterfaceInvalidaException;
use SechianeX\Exceptions\SessionAdapterNaoEncontradoException;
use SechianeX\Factories\SessionFactory;
use Vilex\Exceptions\PaginaMestraInvalidaException;
use Vilex\Exceptions\TemplateInvalidoException;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

$_SESSION = [];

/**
 * Class LoginControllerTest
 * @package PainelDLX\Testes\Presentation\Web\Usuarios\Controllers
 * @coversDefaultClass \PainelDLX\Presentation\Web\Usuarios\Controllers\LoginController
 */
class LoginControllerTest extends PainelDLXTestCase
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
     * @return LoginController
     */
    public function test__construct(): LoginController
    {
        $controller = self::$painel_dlx->getContainer()->get(LoginController::class);

        $this->assertInstanceOf(LoginController::class, $controller);

        return $controller;
    }

    /**
     * @param LoginController $controller
     * @throws PaginaMestraInvalidaException
     * @throws TemplateInvalidoException
     * @covers ::formLogin
     * @depends test__construct
     */
    public function test_FormLogin_deve_retornar_uma_instancia_HtmlResponse(LoginController $controller)
    {
        /** @var ServerRequestInterface $request */
        $request = $this->createMock(ServerRequestInterface::class);

        $response = $controller->formLogin($request);

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }

    /**
     * @param LoginController $controller
     * @throws DBALException
     * @throws ORMException
     * @covers ::fazerLogin
     * @depends test__construct
     */
    public function test_FazerLogin_deve_retornar_um_JsonResponse_sucesso(LoginController $controller)
    {
        $usuario = UsuarioTesteHelper::criarDB('Teste de Usuário', 'teste@unitario.com', '123456');

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn([
            'email' => $usuario->getEmail(),
            'senha' => $usuario->getSenha()
        ]);

        /** @var ServerRequestInterface $request */
        $response = $controller->fazerLogin($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $json = json_decode((string)$response->getBody());
        $this->assertEquals('sucesso', $json->retorno);

        // Verificar na sessão foi adicionado o usuario logado e os itens do menu
        // @todo: corrigir a verificação da sessão
        // $this->assertTrue($this->session->has('usuario-logado'));
        // $this->assertTrue($this->session->has('html:lista-menu'));
    }
}
