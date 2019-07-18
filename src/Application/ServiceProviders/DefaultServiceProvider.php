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

namespace PainelDLX\Application\ServiceProviders;


use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use PainelDLX\Application\Services\PainelDLX;
use Psr\Http\Message\ServerRequestInterface;
use SechianeX\Contracts\SessionInterface;
use SechianeX\Factories\SessionFactory;

class DefaultServiceProvider extends AbstractServiceProvider
{
    public $provides = [
        ServerRequestInterface::class,
        SessionInterface::class,
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register()
    {
        $this->registrarServicosAplicacao();
    }

    /**
     * Registrar serviços de aplicacação
     */
    private function registrarServicosAplicacao(): void
    {
        /** @var Container $container */
        $container = $this->getContainer();

        $container->add(
            ServerRequestInterface::class,
            function () {
                return PainelDLX::getInstance()->getRequest();
            }
        );

        $container->add(
            SessionInterface::class,
            function () {
                return SessionFactory::createPHPSession();
            }
        );
    }
}