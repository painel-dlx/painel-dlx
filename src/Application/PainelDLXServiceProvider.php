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

namespace PainelDLX\Application;


use DLX\Core\CommandBus\CommandBusAdapter;
use DLX\Core\Configure;
use DLX\Infra\EntityManagerX;
use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Tactician\CommandBus;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;
use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;
use PainelDLX\Domain\GruposUsuarios\Repositories\GrupoUsuarioRepositoryInterface;
use PainelDLX\Domain\PermissoesUsuario\Entities\PermissaoUsuario;
use PainelDLX\Domain\PermissoesUsuario\Repositories\PermissaoUsuarioRepositoryInterface;
use PainelDLX\Domain\Usuarios\Entities\ResetSenha;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Repositories\ResetSenhaRepositoryInterface;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;
use SechianeX\Contracts\SessionInterface;
use SechianeX\Factories\SessionFactory;
use Vilex\VileX;

class PainelDLXServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        GrupoUsuarioRepositoryInterface::class,
        CommandBus::class,
        VileX::class,
        UsuarioRepositoryInterface::class,
        PermissaoUsuarioRepositoryInterface::class,
        SessionInterface::class,
        ConfigSmtpRepositoryInterface::class,
        ResetSenhaRepositoryInterface::class,
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \SechianeX\Exceptions\SessionAdapterInterfaceInvalidaException
     * @throws \SechianeX\Exceptions\SessionAdapterNaoEncontradoException
     */
    public function register()
    {
        /** @var Container $container */
        $container = $this->getContainer();

        $container->add(
            GrupoUsuarioRepositoryInterface::class,
            EntityManagerX::getRepository(GrupoUsuario::class)
        );

        $container->add(
            CommandBus::class,
            function () use ($container) {
                return CommandBusAdapter::create(new CommandHandlerMiddleware(
                    new ClassNameExtractor,
                    new ContainerLocator($container, Configure::get('app', 'mapping')),
                    new HandleInflector
                ));
            }
        );

        $container->add(
            VileX::class,
            new VileX
        );

        $container->add(
            GrupoUsuarioRepositoryInterface::class,
            EntityManagerX::getRepository(GrupoUsuario::class)
        );

        $container->add(
            ResetSenhaRepositoryInterface::class,
            EntityManagerX::getRepository(ResetSenha::class)
        );

        $container->add(
            UsuarioRepositoryInterface::class,
            EntityManagerX::getRepository(Usuario::class)
        );

        $container->add(
            PermissaoUsuarioRepositoryInterface::class,
            EntityManagerX::getRepository(PermissaoUsuario::class)
        );

        $container->add(
            ConfigSmtpRepositoryInterface::class,
            EntityManagerX::getRepository(ConfigSmtp::class)
        );

        $container->add(
            SessionInterface::class,
            SessionFactory::createPHPSession()
        );
    }
}