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


use DLX\Infra\EntityManagerX;
use League\Container\Container;
use League\Container\ReflectionContainer;
use PainelDLX\Application\ServiceProviders\PainelDLXServiceProvider;
use PainelDLX\Application\Services\IniciarPainelDLX;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

define ('PAINEL_DLX', '');

class PainelDLXTests extends TestCase
{
    /** @var ContainerInterface */
    protected $container;

    /**
     * @throws \DLX\Core\Exceptions\ArquivoConfiguracaoNaoEncontradoException
     * @throws \DLX\Core\Exceptions\ArquivoConfiguracaoNaoInformadoException
     * @throws \PainelDLX\Application\Services\Exceptions\AmbienteNaoInformadoException
     * @throws \Doctrine\ORM\ORMException
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

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->method('getQueryParams')
            ->willReturn(['ambiente' => 'paineldlx-dev']);

        $this->container = new Container;
        $this->container
            ->delegate(new ReflectionContainer)
            ->addServiceProvider(PainelDLXServiceProvider::class);

        /** @var ServerRequestInterface $request */
        (new IniciarPainelDLX($request, $this->container))
            ->adicionarDiretorioInclusao(dirname('.'))
            ->init();

        EntityManagerX::beginTransaction();
    }

    /**
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \Doctrine\ORM\ORMException
     */
    protected function tearDown()
    {
        parent::tearDown();

        EntityManagerX::rollback();
        EntityManagerX::getInstance()->clear();
    }
}