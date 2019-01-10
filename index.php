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

ini_set('session.save_handler', 'files');

include __DIR__ . '/vendor/autoload.php';

use DLX\Core\Configure;
use PainelDLX\Application\Middlewares\Exceptions\UsuarioNaoLogadoException;
use RautereX\RautereX;
use Zend\Diactoros\ServerRequestFactory;
use League\Container\Container;
use League\Container\ReflectionContainer;

define('BASE_DIR', dirname(__FILE__));

try {
    $server_request = ServerRequestFactory::fromGlobals();
    $params = $server_request->getQueryParams();

    Configure::init($params['ambiente'], "config/{$params['ambiente']}.php");

    $container = new Container;
    $container
        ->delegate(new ReflectionContainer)
        ->addServiceProvider(Configure::get('app', 'service-provider'));

    $router = new RautereX($container);
    include_once Configure::get('app', 'rotas');

    $response = $router->executarRota(
        $params['task'] === '/index.php' ? '/painel-dlx/usuarios' : $params['task'],
        $server_request,
        $server_request->getMethod()
    );
    echo $response->getBody();
} catch (UsuarioNaoLogadoException $e) {
    $response = $router->executarRota(
        '/painel-dlx/login',
        $server_request,
        $server_request->getMethod()
    );
    echo $response->getBody();
} catch (\Exception $e) {
    var_dump($e);
}