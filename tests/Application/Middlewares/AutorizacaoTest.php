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

namespace PainelDLX\Testes\Application\Middlewares;

use Exception;
use PainelDLX\Application\Middlewares\Autorizacao;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Tests\TestCase\PainelDLXTestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionException;
use ReflectionProperty;
use SechianeX\Contracts\SessionInterface;
use Vilex\Exceptions\ContextoInvalidoException;
use Vilex\Exceptions\PaginaMestraNaoEncontradaException;
use Vilex\Exceptions\ViewNaoEncontradaException;
use Laminas\Diactoros\Response\HtmlResponse;

/**
 * Class AutorizacaoTest
 * @package PainelDLX\Testes\Application\Middlewares
 * @coversDefaultClass \PainelDLX\Application\Middlewares\Autorizacao
 */
class AutorizacaoTest extends PainelDLXTestCase
{
    /**
     * @throws Exception
     * @covers ::process
     */
    public function test_Process_deve_renderizar_pagina_de_erro_403_quando_usuario_NAO_possui_acesso()
    {
        $usuario = $this->createMock(Usuario::class);
        $usuario->method('hasPermissao')->willReturn(false);

        $session = $this->createMock(SessionInterface::class);
        $session->method('get')->with('usuario-logado')->willReturn($usuario);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('withQueryParams')->willReturn($request);
        $request->method('getQueryParams')->willReturn(['erro' => 403]);

        $handler = $this->createMock(RequestHandlerInterface::class);

        /** @var SessionInterface $session */
        /** @var ServerRequestInterface $request */
        /** @var RequestHandlerInterface $handler */

        $autorizacao = (new Autorizacao($session))->setPermissoes('TESTE');
        $response = $autorizacao->process($request, $handler);

        $this->assertInstanceOf(HtmlResponse::class, $response);
        $this->assertStringContainsString('<h2 class="erro-http-titulo">403</h2>', (string)$response->getBody());
    }

    /**
     * @throws ContextoInvalidoException
     * @throws PaginaMestraNaoEncontradaException
     * @throws ViewNaoEncontradaException
     * @covers ::process
     */
    public function test_Process_deve_retornar_ResponseInterface_da_handle_quando_usuaario_tiver_permissao()
    {
        $usuario = $this->createMock(Usuario::class);
        $usuario->method('hasPermissao')->willReturn(true);

        $session = $this->createMock(SessionInterface::class);
        $session->method('get')->with('usuario-logado')->willReturn($usuario);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('withQueryParams')->willReturn($request);

        $response_esperada = $this->createMock(ResponseInterface::class);

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($response_esperada);

        /** @var SessionInterface $session */
        /** @var ServerRequestInterface $request */
        /** @var RequestHandlerInterface $handler */

        $autorizacao = (new Autorizacao($session))->setPermissoes('TESTE');
        $response = $autorizacao->process($request, $handler);

        $this->assertSame($response_esperada, $response);
    }

    /**
     * @throws ReflectionException
     * @covers ::necessitaPermissoes
     */
    public function test_NecessitaPermissoes_deve_retornar_uma_nova_instancia_com_permissoes_configuradas()
    {
        /** @var SessionInterface $session */
        $session = $this->createMock(SessionInterface::class);

        $autorizacao_original = new Autorizacao($session);
        $nova_instancia = $autorizacao_original->necessitaPermissoes('TESTE 1', 'TESTE 2');

        $rfx_permissoes = new ReflectionProperty($nova_instancia, 'permissoes');
        $rfx_permissoes->setAccessible(true);

        $this->assertNotSame($autorizacao_original, $nova_instancia);
        $this->assertCount(2, $rfx_permissoes->getValue($nova_instancia));
    }
}
