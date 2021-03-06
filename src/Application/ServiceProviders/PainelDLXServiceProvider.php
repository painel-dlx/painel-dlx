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


use DLX\Contracts\TransactionInterface;
use DLX\Core\Configure;
use DLX\Infrastructure\EntityManagerX;
use DLX\Infrastructure\ORM\Doctrine\Services\DoctrineTransaction;
use Doctrine\ORM\ORMException;
use League\Container\Container;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Tactician\CommandBus;
use PainelDLX\Application\Factories\CommandBusFactory;
use PainelDLX\Domain\Emails\Entities\ConfigSmtp;
use PainelDLX\Domain\Emails\Repositories\ConfigSmtpRepositoryInterface;
use PainelDLX\Domain\GruposUsuarios\Entities\GrupoUsuario;
use PainelDLX\Domain\GruposUsuarios\Repositories\GrupoUsuarioRepositoryInterface;
use PainelDLX\Domain\Home\Entities\Widget;
use PainelDLX\Domain\Home\Repositories\WidgetRepositoryInterface;
use PainelDLX\Domain\Modulos\Entities\Menu;
use PainelDLX\Domain\Modulos\Repositories\MenuRepositoryInterface;
use PainelDLX\Domain\PermissoesUsuario\Entities\PermissaoUsuario;
use PainelDLX\Domain\PermissoesUsuario\Repositories\PermissaoUsuarioRepositoryInterface;
use PainelDLX\Domain\Usuarios\Entities\ResetSenha;
use PainelDLX\Domain\Usuarios\Entities\Usuario;
use PainelDLX\Domain\Usuarios\Repositories\ResetSenhaRepositoryInterface;
use PainelDLX\Domain\Usuarios\Repositories\UsuarioRepositoryInterface;
use PainelDLX\Infrastructure\ORM\Doctrine\Services\RepositoryFactory;
use SechianeX\Contracts\SessionInterface;
use SechianeX\Exceptions\SessionAdapterInterfaceInvalidaException;
use SechianeX\Exceptions\SessionAdapterNaoEncontradoException;
use SechianeX\Factories\SessionFactory;
use Vilex\VileX;

class PainelDLXServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        GrupoUsuarioRepositoryInterface::class,
        CommandBus::class,
        UsuarioRepositoryInterface::class,
        PermissaoUsuarioRepositoryInterface::class,
        ConfigSmtpRepositoryInterface::class,
        ResetSenhaRepositoryInterface::class,
        TransactionInterface::class,
        MenuRepositoryInterface::class,
        WidgetRepositoryInterface::class,
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     * @throws ORMException
     */
    public function register()
    {
        /** @var Container $container */
        $container = $this->getContainer();

        $container->add(
            GrupoUsuarioRepositoryInterface::class,
            RepositoryFactory::create(GrupoUsuario::class)
        );

        $container->add(
            CommandBus::class,
            CommandBusFactory::create($container, Configure::get('app', 'mapping'))
        );

        $container->add(
            GrupoUsuarioRepositoryInterface::class,
            RepositoryFactory::create(GrupoUsuario::class)
        );

        $container->add(
            ResetSenhaRepositoryInterface::class,
            RepositoryFactory::create(ResetSenha::class)
        );

        $container->add(
            UsuarioRepositoryInterface::class,
            RepositoryFactory::create(Usuario::class)
        );

        $container->add(
            TransactionInterface::class,
            new DoctrineTransaction(EntityManagerX::getInstance())
        );

        $container->add(
            MenuRepositoryInterface::class,
            RepositoryFactory::create(Menu::class)
        );

        $container->add(
            PermissaoUsuarioRepositoryInterface::class,
            RepositoryFactory::create(PermissaoUsuario::class)
        );

        $container->add(
            WidgetRepositoryInterface::class,
            RepositoryFactory::create(Widget::class)
        );


        $container->add(
            ConfigSmtpRepositoryInterface::class,
            RepositoryFactory::create(ConfigSmtp::class)
        );
    }
}