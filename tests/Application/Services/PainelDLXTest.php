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

namespace PainelDLX\Tests\Application\Services;

use PainelDLX\Application\Contracts\Router\ContainerInterface;
use PainelDLX\Application\Contracts\Router\RouterInterface;
use PainelDLX\Application\Services\PainelDLX;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class PainelDLXTest
 * @package PainelDLX\Tests\Application\Services
 * @coversDefaultClass \PainelDLX\Application\Services\PainelDLX
 */
class PainelDLXTest extends TestCase
{
    /**
     * @return PainelDLX
     */
    public function test__construct(): PainelDLX
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $router = $this->createMock(RouterInterface::class);
        $container = $this->createMock(ContainerInterface::class);

        /** @var ServerRequestInterface $request */
        /** @var RouterInterface $router */
        /** @var ContainerInterface $container */

        $painel_dlx = new PainelDLX($request, $router, $container);

        $this->assertInstanceOf(PainelDLX::class, $painel_dlx);
        $this->assertEquals($request, $painel_dlx->getRequest());
        $this->assertEquals($router, $painel_dlx->getRouter());
        $this->assertEquals($container, $painel_dlx->getContainer());

        return $painel_dlx;
    }

    /**
     * @covers ::getInstance
     * @depends test__construct
     */
    public function test_GetInstance_deve_retornar_intancia_atual_PainelDLX(PainelDLX $painel_dlx)
    {
        $painel_dlx_instance = PainelDLX::getInstance();
        $this->assertSame($painel_dlx, $painel_dlx_instance);
    }
}
