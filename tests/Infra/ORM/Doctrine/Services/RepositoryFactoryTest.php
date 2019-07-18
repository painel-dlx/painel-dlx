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

namespace PainelDLX\Testes\Infra\ORM\Doctrine\Services;

use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Infrastructure\ORM\Doctrine\Services\RepositoryFactory;
use PainelDLX\Testes\TestCase\PainelDLXTestCase;

/**
 * Class RepositoryFactoryTest
 * @package PainelDLX\Testes\Infra\ORM\Doctrine\Services
 * @coversDefaultClass \PainelDLX\Infrastructure\ORM\Doctrine\Services\RepositoryFactory
 */
class RepositoryFactoryTest extends PainelDLXTestCase
{
    /**
     * @covers ::create
     */
    public function test_Create_deve_retornar_uma_funcao_anonima()
    {
        $entity = Usuario::class;
        $func = RepositoryFactory::create($entity);

        $this->assertIsCallable($func);
    }
}
