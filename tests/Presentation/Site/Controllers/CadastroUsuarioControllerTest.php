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

namespace PainelDLX\Tests\Presentation\Site\Controllers;


use PainelDLX\Presentation\Site\Usuarios\Controllers\CadastroUsuarioController;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\ServerRequestFactory;

class CadastroUsuarioControllerTest extends TestCase
{
    /** @var CadastroUsuarioController */
    private static $controller;
    /** @var ServerRequest */
    private static $server_request;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$controller = new CadastroUsuarioController();
        self::$server_request = ServerRequestFactory::fromGlobals();
    }

    /**
     * @throws \Exception
     */
    public function test_formNovoUsuario()
    {
        $this->markTestSkipped();

        // TODO: configurar o PHPUnit para conseguir fazer o teste das controllers
        $response = self::$controller->formNovoUsuario();
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}