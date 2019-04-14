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

namespace PainelDLX\Testes;


use DLX\Core\Exceptions\ArquivoConfiguracaoNaoEncontradoException;
use DLX\Core\Exceptions\ArquivoConfiguracaoNaoInformadoException;
use DLX\Infra\EntityManagerX;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\ORMException;
use League\Container\Container;
use League\Container\ReflectionContainer;
use PainelDLX\Application\ServiceProviders\PainelDLXServiceProvider;
use PainelDLX\Application\Services\Exceptions\AmbienteNaoInformadoException;
use PainelDLX\Application\Services\PainelDLX;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequestFactory;

class PainelDLXTests extends TestCase
{
    /** @var ContainerInterface */
    protected $container;
    /** @var PainelDLX */
    protected $painel_dlx;

    /**
     * @throws ArquivoConfiguracaoNaoEncontradoException
     * @throws ArquivoConfiguracaoNaoInformadoException
     * @throws AmbienteNaoInformadoException
     * @throws ORMException
     */
    protected function setUp()
    {
        parent::setUp();

        // Chegar até a página index
        $q = 0;
        $t = 10;
        while (!file_exists('./config/paineldlx-dev.php') && $q < $t) {
            chdir('../');
            $q++;
        }

        $request = ServerRequestFactory::fromGlobals();
        $request = $request->withQueryParams(['ambiente' => 'paineldlx-dev']);

        $this->container = new Container;
        $this->container
            ->delegate(new ReflectionContainer)
            ->addServiceProvider(PainelDLXServiceProvider::class);

        /** @var ServerRequestInterface $request */
        $this->painel_dlx = (new PainelDLX($request, $this->container))
            ->adicionarDiretorioInclusao(dirname('.'))
            ->init();

        EntityManagerX::beginTransaction();
    }

    /**
     * @throws MappingException
     * @throws ORMException
     */
    protected function tearDown()
    {
        parent::tearDown();

        if (EntityManagerX::getInstance()->getConnection()->isTransactionActive()) {
            EntityManagerX::rollback();
        }

        EntityManagerX::getInstance()->clear();
    }
}