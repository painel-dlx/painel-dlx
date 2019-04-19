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
use DLX\Infra\EntityManagerX;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\ORMException;
use PainelDLX\Application\Services\Exceptions\AmbienteNaoInformadoException;
use PHPUnit\Framework\TestCase;

class PainelDLXTestCase extends TestCase
{
    use IniciarPainelDLX;

    /**
     * @throws AmbienteNaoInformadoException
     * @throws ArquivoConfiguracaoNaoEncontradoException
     * @throws ArquivoConfiguracaoNaoInformadoException
     * @throws ORMException
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::start();
    }

    /**
     * @throws ArquivoConfiguracaoNaoEncontradoException
     * @throws ArquivoConfiguracaoNaoInformadoException
     * @throws AmbienteNaoInformadoException
     * @throws ORMException
     */
    protected function setUp()
    {
        parent::setUp();

        if (in_array(TesteComTransaction::class, get_declared_traits())) {
            EntityManagerX::beginTransaction();
        }
    }

    /**
     * @throws MappingException
     * @throws ORMException
     */
    protected function tearDown()
    {
        parent::tearDown();

        if (in_array(TesteComTransaction::class, get_declared_traits())) {
            if (EntityManagerX::getInstance()->getConnection()->isTransactionActive()) {
                EntityManagerX::rollback();
            }
        }
    }
}