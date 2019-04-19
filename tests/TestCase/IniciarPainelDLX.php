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

namespace PainelDLX\Testes\TestCase;


use DLX\Core\Exceptions\ArquivoConfiguracaoNaoEncontradoException;
use DLX\Core\Exceptions\ArquivoConfiguracaoNaoInformadoException;
use Doctrine\ORM\ORMException;
use League\Container\Container;
use League\Container\ReflectionContainer;
use PainelDLX\Application\Services\Exceptions\AmbienteNaoInformadoException;
use PainelDLX\Application\Services\PainelDLX;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequestFactory;

trait IniciarPainelDLX
{
    /** @var ContainerInterface */
    protected static $container;
    /** @var PainelDLX */
    protected static $painel_dlx;

    /**
     * @param string $ambiente
     * @throws ArquivoConfiguracaoNaoEncontradoException
     * @throws ArquivoConfiguracaoNaoInformadoException
     * @throws AmbienteNaoInformadoException
     * @throws ORMException
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

        self::$container = new Container;
        self::$container->delegate(new ReflectionContainer);

        /** @var ServerRequestInterface $request */
        self::$painel_dlx = (new PainelDLX($request, self::$container))
            ->adicionarDiretorioInclusao(dirname('.'))
            ->init();
    }
}