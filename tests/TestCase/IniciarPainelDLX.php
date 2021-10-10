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

namespace PainelDLX\Tests\TestCase;


use DLX\Core\Exceptions\ArquivoConfiguracaoNaoEncontradoException;
use DLX\Core\Exceptions\ArquivoConfiguracaoNaoInformadoException;
use League\Container\Container;
use League\Container\ReflectionContainer;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use PainelDLX\Application\Adapters\Router\League\LeagueContainerAdapter;
use PainelDLX\Application\Adapters\Router\League\LeagueRouterAdapter;
use PainelDLX\Application\Services\Exceptions\AmbienteNaoInformadoException;
use PainelDLX\Application\Services\PainelDLX;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\ServerRequestFactory;

trait IniciarPainelDLX
{
    /** @var PainelDLX */
    protected static $painel_dlx;

    /**
     * @param string $ambiente
     * @throws ArquivoConfiguracaoNaoEncontradoException
     * @throws ArquivoConfiguracaoNaoInformadoException
     * @throws AmbienteNaoInformadoException
     */
    public static function start(string $ambiente = 'paineldlx')
    {
        $ambiente = $ambiente ?: 'paineldlx';

        // Chegar até a página index
        $q = 0;
        $t = 10;
        while (!file_exists("./config/{$ambiente}-dev.php") && $q < $t) {
            chdir('../');
            $q++;
        }

        $request = ServerRequestFactory::fromGlobals();
        $request = $request->withQueryParams(['ambiente' => "{$ambiente}-dev"]);

        $league_container = new Container;
        $league_container->delegate(new ReflectionContainer);
        $container = new LeagueContainerAdapter($league_container);

        $strategy = new ApplicationStrategy;
        $strategy->setContainer($container);

        $league_router = new Router();
        $league_router->setStrategy($strategy);

        $router = new LeagueRouterAdapter($league_router);

        /** @var ServerRequestInterface $request */
        self::$painel_dlx = (new PainelDLX($request, $router, $container))
            ->adicionarDiretorioInclusao(dirname('.'))
            ->init();
    }
}